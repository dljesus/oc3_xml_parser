<?php
namespace suppliers;

use \suppliers\ParserFactory;

require_once('ParserFactory.php');


class SupplierParser
{
    protected $db;
    protected $category;
    private $registry;
    protected $languages;
    protected $feed_id;
    protected $urls;
    protected $stock_status_id = 1;
    protected $quantity = 1;
    protected $type;

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->db = $registry->get('db');
    }

    public function startParse($id, $supplier = false , $init = false)
    {
        $this->feed_id = $id;
        $this->urls = $this->getUrls();
        if (!$supplier) {
            $supplier = $this->getSupplier($id);
        }
        $this->languages = $this->getLanguageId();
        $category = $this->getCategory($id);
        $this->quantity = $supplier['quantity'];
        if ($supplier['stock_status_id'] && !$init){
            return false;
        }
        $this->stock_status_id = $supplier['stock_status_id'];
        $parser = new ParserFactory($this->registry);
        $feed = $parser->getParser($supplier);
        $this->type = $feed->type;
        //var_dump($feed);die;
        $category_feed = $feed->getCategory();
        $newCategory = array();

        foreach ($category_feed as $key => $value) {
            if (isset($category[$key])) {
                if ($category[$key]['parent_id'] != $value['parent_id']) {
                    $category[$key]['parent_id'] = $value['parent_id'];
                    $category[$key]['update'] = true;
                }
            } else {
                $newCategory[] = $value;
            }
        }
        //$this->updateCategory($category);
        $this->newCategory($newCategory);
        $this->updateSupplier();
        if ( $init){
            return $id;
        }
        $feed_products = $feed->getProduct();
        $old_products = $this->getProduct($id);
        $newProduct = array();
        $updatePrice = array();
        foreach ($feed_products as $key => $value) {
            if (isset($old_products[$key])) {
                if ($old_products[$key]['parsed']) {
                    if ($old_products[$key]['product_id']) {
                        if ($old_products[$key]['price'] && $old_products[$key]['price'] != $value['price']) {
                            $updatePrice[$key]['price'] = $value['price'];
                            $updatePrice[$key]['product_id'] = $value['product_id'];
                        }
                    } else {
                        GOTO else_case;
                    }
                }
            } else {
                else_case:
                $newProduct[$key] = $value;
                if (isset($category[$value['category']])) {
                    if ($category[$value['category']]['not_add']) {
                        unset($newProduct[$key]);
                    } else {
                        $newProduct[$key]['category_id'] = $category[$value['category']]['category_id'];
                        if ($category[$value['category']]['add_to_parent']) {
                            if (isset($category[$value['category']]['path'])) {
                                $newProduct[$key]['path'] = $category[$value['category']]['path'];
                            }

                        }
                        $newProduct[$key]['add_to_parent'] = $category[$value['category']]['add_to_parent'];
                    }
                }
            }
        }
        $this->updateProduct($updatePrice);
        $this->addProduct($newProduct);
        return true;
    }

    public function initParser($id){

    }

    protected function getCategory($id)
    {
        $query_path = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path");
        $paths = array();
        foreach ($query_path->rows as $path) {
            $paths[$path['category_id']][] = $path['path_id'];
        }
        $categorys = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "asper_supplier_category WHERE supplier_id='" . $id . "'");
        foreach ($query->rows as $cat) {
            $categorys[$cat['external_id']] = array(
                'id' => $cat['id'],
                'external_id' => $cat['external_id'],
                'name' => $cat['name'],
                'parent_id' => $cat['parent_id'],
                'not_add' => $cat['not_add'],
                'add_to_parent' => $cat['add_to_parent'],
                'category_id' => $cat['category_id'],
            );
            if (isset($paths[$cat['category_id']])) {
                $categorys[$cat['external_id']]['path'] = $paths[$cat['category_id']];
            }
        }
        return $categorys;
    }

    protected function getSupplier($id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "asper_supplier WHERE supplier_id='" . $id . "'");
        return $query->row;
    }

    protected function getProduct($id)
    {
        $products = array();
        $query = $this->db->query("SELECT asp.*, p.price FROM " . DB_PREFIX . "asper_supplier_products asp LEFT JOIN " . DB_PREFIX . "product p ON p.product_id = asp.product_id  WHERE supplier_id='" . $id . "'");
        foreach ($query->rows as $cat) {
            $products[$cat['external_id']] = array(
                'id' => $cat['external_id'],
                'external_id' => $cat['external_id'],
                'name' => $cat['name'],
                'data' => $cat['data'],
                'product_id' => $cat['product_id'],
                'parsed' => $cat['parsed'],
                'external_category_id' => $cat['external_category_id'],
            );
        }
        return $products;
    }

    protected function updateProduct($products)
    {
        $step = 1;
        $steps = 50;
        $count = count($products);
        $sqls = array();
        $ids = array();
        foreach ($products as $key => $value) {
            if ($step < $steps) {
                $sqls[] = "WHEN product_id = " . $value['product_id'] . " THEN " . $value['price'];
                $ids[] = $value['product_id'];
            }
            if ($step == $steps || $step == $count) {
                $step = 1;
                $sql = "UPDATE " . DB_PREFIX . "product SET price = CASE ";
                $sql .= implode(' ', $sqls);
                $sql .= "END WHERE product_id IN (";
                $sql .= implode(',', $ids);
                $sql .= ")";
                $this->db->query($sql);
                $sqls = array();
                $ids = array();
            } else {
                $step++;
            }
        }
    }

    protected function getLanguageId()
    {
        $query = $this->db->query("SELECT code, language_id FROM " . DB_PREFIX . "language ");
        $language = array();
        foreach ($query->rows as $l) {
            $language[$l['code']] = $l['language_id'];
        }
        return $language;
    }

    protected function getUrls()
    {
        $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url");
        $urls = array();
        foreach ($query->rows as $url) {
            $urls[$url['keyword']] = $url['keyword'];
        }
        return $urls;
    }

    protected function addProduct($products)
    {
        foreach ($products as $product) {
            $product_id = '';
            if ($product['category_id']) {
                $image = ($product['image']) ? ", image = '" . $product['image'] . "'" : '';
                $this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($product['id'] . $this->feed_id) . "', sku = '', upc = '', ean = '', jan = '', isbn = '', mpn = '', location = '', quantity = '$this->quantity', minimum = '1', stock_status_id = '" . $this->stock_status_id . "', shipping = '1', price = '" . (float)$product['price'] . "', status = '1', date_added = NOW(), date_modified = NOW()" . $image);

                $product_id = $this->db->getLastId();
                foreach ($this->languages as $language_id) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($product['name']) . "', description = '" . $this->db->escape($product['description']) . "', tag = '', meta_title = '" . $this->db->escape($product['name']) . "', meta_description = '" . $this->db->escape($product['name']) . "', meta_keyword = ''");
                }
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");

                if (isset($product['images'])) {
                    foreach ($product['images'] as $product_image) {

                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image) . "', sort_order = '0'");
                    }
                }
                if ($product['add_to_parent']) {
                    foreach ($product['path'] as $category_id) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
                    }
                } elseif ($product['category_id']) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$product['category_id'] . "'");
                }

                if (isset($product['url'])) {
                    foreach ($this->languages as $cod => $id) {
                        if (isset($this->languages[$product['url']])) {
                            $url = $product['url'] . '-' . $product_id;
                        } else {
                            $url = $product['url'];
                        }
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '0', language_id = '" . (int)$id . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($url) . "'");
                    }
                }
            }
            $this->db->query("INSERT INTO " . DB_PREFIX . "asper_supplier_products SET  supplier_id = '" . $this->feed_id . "',external_id = '" . $product['id'] . '_' . $this->feed_id . "', product_id = '" . (int)$product_id . "', `data` = '" . $this->db->escape(serialize($product)) . "', name = '" . $product['name'] . "' ON DUPLICATE KEY UPDATE `data` = VALUES(data), `product_id` = VALUES(product_id)");
        }
    }

    protected function newCategory($categorys)
    {
        foreach ($categorys as $category) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "asper_supplier_category SET supplier_id = '" . (int)$this->feed_id . "', external_id = '" . (int)$category['external_id'] . "', name = '" . $category['name'] . "', parent_id = '" . $category['parent_id'] . "'");
        }
    }
    protected function updateSupplier(){
        $this->db->query("UPDATE " . DB_PREFIX . "asper_supplier SET date_parse = NOW(), type = '". $this->type ."' WHERE supplier_id ='" . $this->feed_id . "'");
    }
}