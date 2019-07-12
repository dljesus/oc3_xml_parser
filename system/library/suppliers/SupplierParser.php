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

    }
    protected function getCategory ($id) {
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
        }
        return $categorys;
    }

    protected function getSupplier($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "asper_supplier WHERE supplier_id='" . $id . "'");
        return $query->row;
    }

    protected function getProduct($id) {
        $products = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "asper_supplier_products WHERE supplier_id='" . $id . "'");
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
}