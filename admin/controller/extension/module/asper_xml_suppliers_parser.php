<?php
class ControllerExtensionModuleAsperXmlSuppliersParser extends Controller
{

    private $error = array();
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

}