<?php
class ModelExtensionSuppliersAsperXmlSuppliers extends Model {
    public function newSupplier ($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "asper_supplier SET `name` = '" . $data['name'] . "', `url` = '" . $data['url'] . "'");
        $id = $this->db->getLastId();
        return $id;
    }
    public function getSuppliers ($data) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "asper_supplier");
        return $query->rows;
    }
    public function getSupplier ($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "asper_supplier s WHERE s.supplier_id='" . $id . "'");
        return $query->row;
    }
    public function getCatgorys($id) {
        $query = $this->db->query("SELECT sp.* , (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM oc_category_path cp LEFT JOIN oc_category_description cd1 ON (cp.path_id = cd1.category_id) WHERE cp.category_id = sp.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) as path FROM " . DB_PREFIX . "asper_supplier_category sp WHERE sp.supplier_id = '" . (int)$id . "'");
        return $query->rows;
    }

    public function editSupplier ($supplier_id, $data){
        $this->db->query("UPDATE  " . DB_PREFIX . "asper_supplier SET name = '" . $this->db->escape($data['name']) . "', url = '" . $this->db->escape($data['url']) . "', status = '" . $data['status'] . "', cron = '" . $data['cron'] . "', stock_status_id = '" . $data['stock_status_id'] . "', quantity = '" . $data['quantity'] . "' WHERE supplier_id = " . $supplier_id);
        if (isset($data['categorys'])){
            foreach ($data['categorys'] as $key=>$category){
                if(isset( $category['parent_id'])){
                    $cat_id =  $category['parent_id'];
                } else {
                    $cat_id = 0;
                }
                $this->db->query("UPDATE " . DB_PREFIX . "asper_supplier_category SET category_id = '" . $cat_id . "', add_to_parent = '" . $category['add_to_parent'] . "', not_add= '" . $category['not_add'] . "' WHERE id = " . $key);
            }
        }
    }
}