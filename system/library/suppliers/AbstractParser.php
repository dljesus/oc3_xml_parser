<?php
namespace suppliers;
use suppliers\YandexYmlParser;
abstract class AbstractParser
{
    protected $error = array();
    protected $feed;
    protected $options = array();
    public $id = '';
    protected $fileName = '';
    public $url = '';
    protected $name = '';
    public $type = '';
    protected $categorys;
    protected $products;
    protected $newCategory;

    public function __construct($registry, $supplier)
    {
        $this->id = $supplier['supplier_id'];
        $this->name = $supplier['name'];
        $this->fileName = $supplier['supplier_id'] . $supplier['name'];
        $this->url = $supplier['url'];
        $this->type = $supplier['type'];
        $this->download();
    }


    public function download()
    {
        set_time_limit(0);
        $file_name = DIR_CACHE . 'suppliers/' . $this->fileName . '.xml';
        if (!file_exists(DIR_CACHE . 'suppliers')) {
            if (!mkdir(DIR_CACHE . 'suppliers', 0777)) {
                return array('error' => 'failed to create directory');
            }
        }
        $fp = fopen($file_name, 'w');
        $ch = curl_init(str_replace(" ", "%20", $this->url));
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    protected function getFeed()
    {
        if (!$this->feed) {
            $this->feed = file_get_contents(DIR_CACHE . 'suppliers/' . $this->fileName . '.xml');
        }
        return $this->feed;
    }

    public function downloadImage($url, $file)
    {
        if (!is_file($file)) {
            $dirname = pathinfo($file, PATHINFO_DIRNAME);
            if (!file_exists($dirname)) {
                if (!mkdir($dirname, 777, true)) {
                    return false;
                }
            }
            set_time_limit(0);
            $fp = fopen($file, 'w');
            $ch = curl_init(str_replace(" ", "%20", $url));
            curl_setopt($ch, CURLOPT_TIMEOUT, 180);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $result = curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        } else {
            $result = true;
        }
        if ($result) {
            $file = str_replace(DIR_IMAGE, '', $file);
            return $file;
        }
        return false;
    }

    abstract public function getProduct();

    abstract public function getCategory();

    protected function translit($s)
    {
        $s = (string)$s;
        $s = strip_tags($s);
        $s = str_replace(array("\n", "\r"), " ", $s);
        $s = preg_replace("/\s+/", ' ', $s);
        $s = trim($s);
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s);
        $s = strtr($s, array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => '', 'ґ' => 'g', 'є' => 'ye', 'і' => 'i', 'ї' => 'yi'));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s);
        $s = str_replace(" ", "-", $s);
        // print_r($s);
        return $s;
    }
}