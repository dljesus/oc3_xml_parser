<?php
require_once ('AbstractSupplierParser.php');

class YandexYmlParser extends AbstractParser
{
    public function __construct($registry, $supplier)
    {
        parent::__construct($registry, $supplier);
    }

    public function getCategory()
    {
        $regex = '/<category\s+?(?:parentId\s?=\s?["|\'](\d*)["|\']\s*?|)id\s?=\s?["|\'](\d*)["|\']\s*?(?:parentId\s?=\s?["|\'](\d*)["|\']\s*?|)>(.*?)<\/category>/sui';
        $matches = array();
        preg_match_all($regex , $this->feed, $matches);

        foreach ($matches as $match) {

        }
    }
    public function getProduct()
    {
        $regexOffer = '/<offer[^>]*?id="(.*?)"[^>]*?>.*?<\/offer>/sui';
        $matches = array();
        preg_match_all($regexOffer , $this->feed, $matches);
        foreach ($matches as $match) {
            $id = $match['1'];
            $price = $this->getPrice($match['0']);
            $name = $this->getName($match['0']);
            $description = $this->getDescription($match[0]);
            $category = $this->getProductCategory($match[0]);
            $images = $this->getImages($match[0], $name);
        }
    }

    public function getImages($offer, $name){
        $images = array();
        $file = $this->translit($name) . ;
        $path = DIR_IMAGE . 'catalog/' . $file{0} . '/' . $file{1};
        $regex = '/<picture>(.*?)<\/picture>/sui';
        $matches = array();
        preg_match_all ($regex, $offer, $matches, PREG_SET_ORDER);
        foreach ($matches as $key => $match) {
            $image = $this->downloadImage($match[1], $fileName);

        }

    }

    protected function getName($offer){
        $regex = '/<name>(.*?)<\/name>/sui';
        $match = array();
        preg_match ($regex, $offer, $match);
        return $match['1'];
    }

    protected function getPrice($offer){
        $regex = '/<price>(.*?)<\/price>/sui';
        $match = array();
        preg_match ($regex, $offer, $match);
        return $match['1'];
    }
    protected function getProductCategory($offer){
        $regex = '/<categoryId>(.*?)<\/categoryId>/sui';
        $match = array();
        preg_match ($regex, $offer, $match);
        return $match['1'];
    }
    protected function getDescription($offer){
        $regex = '/<description>(?:<!\[CDATA\[|)(.*?)(?:\]\]>|)<\/description>/sui';
        $match = array();
        preg_match ($regex, $offer, $match);
        return trim($match['1']);
    }
}