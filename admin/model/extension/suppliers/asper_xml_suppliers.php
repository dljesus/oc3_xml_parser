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
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "asper_supplier WHERE supplier_id='" . $id . "'");
        return $query->row;
    }
    public function getCatgorys($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "asper_supplier_category WHERE supplier_id = '" . (int)$id . "'");
        return $query->rows;
    }
}