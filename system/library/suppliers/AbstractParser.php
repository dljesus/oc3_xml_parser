<?php
abstract class AbstractParser
{
    protected $error = array();
    protected $feed;
    protected $options = array();
    protected $id = '';
    protected $fileName = '';
    protected $url = '';
    protected $name = '';
    protected $type = '';
    protected $categorys;
    protected $products;
    protected $newCategory;

    public function __construct($registry, $supplier)
    {
        $this->id = $supplier['supplier_id'];
        $this->options = $supplier['options'];
        $this->name = $supplier['name'];
        $this->fileName = $supplier['supplier_id'] . $supplier['name'];
        $this->url = $supplier['url'];
        $this->type = $supplier['type'];
        $this->download();
    }


    public function download () {
        set_time_limit(0);
        $file_name = DIR_CACHE . 'suppliers/'  . $this->fileName . '.xml';
        if (!file_exists(DIR_CACHE . 'suppliers')) {
            if(!mkdir(DIR_CACHE . 'suppliers' , 0777)){
                return array('error' => 'failed to create directory');
            }
        }
        $fp = fopen ($file_name, 'w');
        $ch = curl_init(str_replace(" ","%20",$this->url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    protected function getFeed() {
        if (!$this->feed) {
            $this->feed = file_get_contents(DIR_CACHE . 'suppliers/'  . $this->fileName . '.xml');
        }
        return $this->feed;
    }

    abstract public function getProduct ();
    abstract public function getCategory ();
}