<?php
require_once ('ParserFactory.php');

class SupplierParser {
    protected $db;
    protected $category;
    private $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->db = $registry->db;
    }

    public function startParse ($id, $supplier = false) {
        if(!$supplier){
            $supplier = $this->getSupplier($id);
        }
        $category = $this->getCategory($id);

        $parser = new ParserFactory($this->registry);
        $feed = $parser->getParser($supplier);
        $category_feed = $feed->getCategory();
        $newCategory = array();
        foreach ($category_feed as $key=>$value){
            if (isset($category[$key])){
                if($category[$key]['parent_id'] != $value['parent_id']){
                    $category[$key]['parent_id'] = $value['parent_id'];
                    $category[$key]['update'] = true;
                }
            } else {
                $newCategory[] = $value;
            }
        }
        $feed_products = $feed->getProduct();
        $old_products = $this->getProduct($id);
        $newProduct = array();
        $updatePrice = array();
        foreach ($feed_products as $key => $value){
            if (isset($old_products[$key])) {
                if($old_products[$key]['parsed'] && $old_products[$key]['product_id'] ){
                   if ($old_products[$key]['price'] && $old_products[$key]['price']!= $value['price']){
                       $updatePrice[$key]['price'] = $value['price'];
                       $updatePrice[$key]['product_id'] = $value['product_id'];
                   }
                }
            } else {
                $newProduct[$key] = $value;
                if (isset($category[$value['category']])){
                    if ($category[$value['category']]['not_add']){
                        unset($newProduct[$key]);
                    } else {
                        $newProduct[$key]['category_id'] = $category[$value['category']]['category_id'];
                        if ($category[$value['category']]['add_to_parent']){
                            $newProduct[$key]['path'] = $category[$value['category']]['path'];
                        }
                        $newProduct[$key]['add_to_parent'] = $category[$value['category']]['add_to_parent'];
                    }
                }
            }
        }

        $this->updateProduct($updatePrice);
    }
    protected function getCategory ($id) {
        $query_path = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path");
        $paths = array();
        foreach ($query_path->rows as $path){
            $paths[$path['category_id']][] = $path['path_id'];
        }
        $categorys = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "asper_supplier_category WHERE supplier_id='" . $id . "'");
        foreach ($query->rows as $cat) {
            $categorys[$cat['external_id']] = array(
                'id'            => $cat['id'],
                'external_id'   => $cat['external_id'],
                'name'          => $cat['name'],
                'parent_id'     => $cat['parent_id'],
                'not_add'       => $cat['not_add'],
                'add_to_parent' => $cat['add_to_parent'],
                'category_id'   => $cat['category_id'],
            );
            if(isset($paths[$cat['category_id']])){
                $categorys[$cat['external_id']]['path'] = $paths[$cat['category_id']];
            }
        }
        return $categorys;
    }

    protected function getSupplier($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "asper_supplier WHERE supplier_id='" . $id . "'");
        return $query->row;
    }

    protected function getProduct($id) {
        $products = array();
        $query = $this->db->query("SELECT asp.*, p.price FROM " . DB_PREFIX . "asper_supplier_products asp LEFT JOIN " . DB_PREFIX . "product p ON p.product_id = asp.product_id  WHERE supplier_id='" . $id . "'");
        foreach ($query->rows as $cat) {
            $products[$cat['external_id']] = array(
                'id'            => $cat['id'],
                'external_id'   => $cat['external_id'],
                'name'          => $cat['name'],
                'data'     => $cat['data'],
                'product_id'   => $cat['product_id'],
                'parsed'   => $cat['parsed'],
                'external_category_id'   => $cat['external_category_id'],
            );
        }
        return $products;
    }

    protected function updateProduct($products){
        $step = 1;
        $steps = 50;
        $count = count($products);
        $sqls = array();
        $ids = array();
        foreach ($products as $key=>$value){
            if ($step<$steps){
                $sqls[]= "WHEN product_id = " . $value['product_id'] . " THEN " . $value['price'];
                $ids[] = $value['product_id'];
            }
            if ($step == $steps || $step == $count){
                $step = 1;
                $sql = "UPDATE " . DB_PREFIX ."product SET price = CASE ";
                $sql.= implode( ' ' , $sqls);
                $sql.= "END WHERE product_id IN (";
                $sql.= implode( ',' , $ids);
                $sql.= ")";
                $this->db->query($sql);
                $sqls = array();
                $ids = array();
            } else {
                $step++;
            }
        }
    }
}