<?php
class ControllerExtensionModuleAsperXmlSuppliersParser extends Controller
{

    private $error = array();
    private $feed = '';
    private $options = array();
    private $catgorys = array();
    private $products = array();
    private $id;
    private $fileName;
    private $name = '';
    private $type = '';
    private $regexType =
        array('google' => '/<\?xml [^>]*>[\s\n\r\n]*?<rss\sxmlns:g=\s?["|\']http[s]?:\/\/base.google.com/sui' ,
            'yandex-yml' => '/<\?xml[^>]*>[\s\n\r\n]*?(?:<!doctype\s[^>]*?yml_catalog[^>]*?>|)[\s\n\r\n]*?<yml_catalog\sdate\s?=\s?["|\']{1}[^"\']*["|\']{1}>/sui');
    private $regexCategory = array();
    private $version = '0.0 alpha';

    public function load($url = false)
    {
        $data = array();
        $this->load->language('extension/module/asper_xml_suppliers');

        if (!$url) {
            $data['error'] = $this->language->get('error_no_link');
        }


        $this->document->setTitle($this->language->get('heading_title') . $this->version);

        $this->load->model('extension/suppliers/asper_xml_suppliers');

        $this->getList();
    }
    public function index (){
        return 1234;
    }

    public function download ($id, $url = false , $name = false) {
        //return $id;
        if (!$url && !$name && $id) {
            $this->load->model('extension/suppliers/asper_xml_suppliers');
            $supplier = $this->model_extension_suppliers_asper_xml_suppliers->getSupplier($id);
            $name = $supplier['name'];

            $url = $supplier['url'];
        }
        $this->name = $name;
        set_time_limit(0);
        $file_name = DIR_CACHE . 'suppliers/'  . $id . $name . '.xml';
        $this->fileName = $file_name;
        if (!file_exists(DIR_CACHE . 'suppliers')) {
            if(mkdir(DIR_CACHE . 'suppliers' , 0777)){
                return array('error' => 'failed to create directory');
            }
        }
        $fp = fopen ($file_name, 'w');
        $ch = curl_init(str_replace(" ","%20",$url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $id;
    }
    public function analysis ($id, $name = false, $options = array()) {
        //return $id;
        $data = array();

        if (!$name && $id && !$options) {
            $this->load->model('extension/suppliers/asper_xml_suppliers');
            $supplier = $this->model_extension_suppliers_asper_xml_suppliers->getSupplier($id);
            $file_name = DIR_CACHE . 'suppliers/' . $id . $supplier['name'] . '.xml';
            $options = $supplier['options'];
        } else {
            $file_name = DIR_CACHE . 'suppliers/' . $id . $name . '.xml';
        }
        $this->id = $id;
        $this->fileName = $file_name;
        $data['type'] = $this->getType();
    }

    private function getType() {
        if ($this->type) {
            return $this->type;
        }
        return $this->parseType();
    }

    private function parseType() {

    }

    private function getFeed() {
        if ($this->feed){
            return $this->feed;
        } elseif ($this->id) {

        }

    }

    private function getCategory() {

    }
    private function getProduct () {

    }
}