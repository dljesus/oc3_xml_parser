<?php
class ModelExtensionSuppliersAsperXmlSuppliers extends Model {
    public function newSupplier ($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "asper_supplier SET `name` = '" . $data['name'] . "', `url` = '" . $data['url'] . "', date_add = NOW()");
        $id = $this->db->getLastId();
        return $id;
    }
}