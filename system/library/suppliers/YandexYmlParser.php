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
            $productId = $match['1'];
            $productName = $this->getName($match['1']);
        }


        // TODO: Implement getProduct() method.
    }

    protected function getName($offer){

    }
}