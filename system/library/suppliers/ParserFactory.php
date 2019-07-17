<?php
namespace suppliers;
use \suppliers\YandexYmlParser;
/**
 * Created by PhpStorm.
 * User: asper.pro
 * Date: 09.07.2019
 * Time: 18:34
 */
class ParserFactory
{
    private $db;
    private $registry;
    private $regexType = array(
        'Google'        => '/<\?xml [^>]*>[\s\n\r\n]*?<rss\sxmlns:g=\s?["|\']http[s]?:\/\/base.google.com/sui' ,
        'YandexYml'    => '/<\?xml[^>]*>[\s\n\r\n]*?(?:<!doctype\s[^>]*?yml_catalog[^>]*?>|)[\s\n\r\n]*?<yml_catalog\sdate\s?=\s?["|\']{1}[^"\']*["|\']{1}>/sui',
    );

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->db = $registry->get('db');
    }
    public function getParser($supplier) {
        if (!$supplier['type']) {
            $type = $this->parseType($supplier['supplier_id'], $supplier['url'], $supplier['name']);
            if (isset($type['type'])) {
                $supplier['type']=$type['type'];
            }
        }
        $class = $supplier['type'] . 'Parser';
        $className = '\\suppliers\\' . $supplier['type'] . 'Parser';

        $file = DIR_SYSTEM . 'library/suppliers/' . $class . '.php';
        if (is_file($file)) {
            return new $className($this->registry, $supplier);
        } else {
            return false;
        }
    }

    private function parseType($id, $url,$name) {
        $filename = $this->download($id, $url, $name);

        $feed = file_get_contents($filename);

        foreach ($this->regexType as $type=>$regex) {
            if(preg_match($regex, $feed)){
                unset($feed);
                return $type;
            }
        }
        unset($feed);
    }

    public function download ($id, $url, $name) {
        set_time_limit(0);
        $file_name = DIR_CACHE . 'suppliers/'  . $id . $name . '.xml';
        if (!file_exists(DIR_CACHE . 'suppliers')) {
            if(!mkdir(DIR_CACHE . 'suppliers' , 0777)){
                return array('error' => 'failed to create directory');
            }
        }
        $fp = fopen ($file_name, 'w');
        $ch = curl_init(str_replace(" ","%20",$url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return  $file_name;
    }
}