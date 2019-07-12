<?php
require_once ('AbstractParser.php');

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
        preg_match_all($regex , $this->feed, $matches, PREG_SET_ORDER );
        foreach ($matches as $match) {
            $this->categorys[$match[2]]['name'] = $match[4];
            $this->categorys[$match[2]]['external_id'] = $match[2];
            if ($match[3]){
                $this->categorys[$match[2]]['parent_id'] = $match[3];
            } elseif ($match[1]){
                $this->categorys[$match[2]]['parent_id'] = $match[1];
            } else {
                $this->categorys[$match[2]]['parent_id'] = 0;
            }
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
            $this->products[$id] = array(
                'id'            => $id,
                'price'         => $price,
                'name'          => $name,
                'description'   => $description,
                'image'         => array_shift($images),
                'images'        => $images,
                'category'      => $category,
            );
        }
    }

    public function getImages($offer, $name){
        $images = array();
        $file = $this->translit($name);
        $path = DIR_IMAGE . 'catalog/' . $file{0} . '/' . $file{1};
        $regex = '/<picture>(.*?)<\/picture>/sui';
        $matches = array();
        preg_match_all ($regex, $offer, $matches, PREG_SET_ORDER);
        foreach ($matches as $key => $match) {
            $ext = pathinfo($match[1] , PATHINFO_EXTENSION);
            $filePath = $path . '/' . $name . '-' . $key . '.' . $ext ;
            $image = $this->downloadImage($match[1], $filePath);
            if ($image){
                $images[] = $image;
            }
        }
        return $images;
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