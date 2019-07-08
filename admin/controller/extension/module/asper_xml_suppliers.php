<?php
class ControllerExtensionModuleAsperXmlSuppliers extends Controller {

    private $error = array();
    private $version = '0.0 alpha';

    public function index() {
        $this->load->language('extension/module/asper_xml_suppliers');

        $this->document->setTitle($this->language->get('heading_title') . $this->version);

        $this->getList();
    }

    public function getList() {
        $data = array();
        $this->load->model('extension/suppliers/asper_xml_suppliers');
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );


        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/asper_xml_suppliers', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['suppliers'] =  $this->model_extension_suppliers_asper_xml_suppliers->getSuppliers($sort);

        //actions
        $data['add'] = $this->url->link('extension/module/asper_xml_suppliers/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('extension/module/asper_xml_suppliers/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        //componets
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/asper_xml_suppliers_list', $data));
    }

    public function add() {

        $this->load->language('extension/module/asper_xml_suppliers');

        $this->document->setTitle($this->language->get('heading_title') . ' ' . $this->version);

        $this->load->model('extension/suppliers/asper_xml_suppliers');

        $data = array();

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );


        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/asper_xml_suppliers', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('extension/module/asper_xml_suppliers/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('extension/module/asper_xml_suppliers/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['user_token'] = $this->session->data['user_token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/asper_xml_suppliers_add', $data));
    }

    public function create () {
        $json = array();
        $this->load->language('extension/module/asper_xml_suppliers');
        if (isset($this->request->post['url']) && $this->request->post['url']) {
            $data = array();
            $this->load->model('extension/suppliers/asper_xml_suppliers');
            $url = parse_url($this->request->post['url']);
            if (isset($url['host']) && isset($url['path']) && $url['path'] ){

                $data['url'] = $this->request->post['url'];
                $data['name'] = trim(mb_strtolower(preg_replace('~[\\\/":?<>|\.]~', '_', $url['path'])), '_');

                try {
                    $id = $this->model_extension_suppliers_asper_xml_suppliers->newSupplier($data);
                    $json['data'] = array();
                    $json['data']['id'] = $id;
                    $json['success'] = $this->language->get('success_create');
                } catch (Exception $e) {
                    $json['error'] = $this->language->get('error_something') .  $e->getMessage();
                }

            } else {
                $json['error'] = $this->language->get('error_invalid_link');
            }
        } else {
            $json['error'] = $this->language->get('error_no_link');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function download() {

        $this->load->language('extension/module/asper_xml_suppliers');

        if (isset($this->request->post['id']) && $this->request->post['id']) {
            try {
                $id = $this->load->controller('extension/module/asper_xml_suppliers_parser/download', $this->request->post['id']);
                $json['data'] = array();
                $json['data']['id'] = $id;
                $json['success'] = $this->language->get('success_download');
            } catch (Exception $e) {
                $json['error'] = $this->language->get('error_something') . $e->getMessage();
            }
        } else {
            $json['error'] = $this->language->get('error_no_id');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function initialization() {

        $this->load->language('extension/module/asper_xml_suppliers');

        if (isset($this->request->post['id']) && $this->request->post['id']) {
            try {
                $id = $this->load->controller('extension/module/asper_xml_suppliers_parser/analysis', $this->request->post['id']);
                if (is_int($id)){
                    $json['data'] = array();
                    $json['data']['id'] = $id;
                    $json['success'] = $this->language->get('success_download');
                } else {
                    $json['error'] = $id;
                }
            } catch (Exception $e) {
                $json['error'] = $this->language->get('error_something') . $e->getMessage();
            }
        } else {
            $json['error'] = $this->language->get('error_no_id');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/amazon_pay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function install() {
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('amazon_pay');
    }

    public function uninstall() {
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('amazon_pay');
    }

    public function test() {
        $a = $this->request->get['method'];
        var_dump($this->$a());die;
        return $this->$a();
    }

    private function test1() {
        return 'test1';
    }
    private function test2() {
        return 'test2';
    }
    private function test3() {
        return 'test3';
    }
}
