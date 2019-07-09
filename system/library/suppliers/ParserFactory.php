<?php

/**
 * Created by PhpStorm.
 * User: asper.pro
 * Date: 09.07.2019
 * Time: 18:34
 */
class ParserFactory
{
    private $db;
    private $regexType = array(
        'google'        => '/<\?xml [^>]*>[\s\n\r\n]*?<rss\sxmlns:g=\s?["|\']http[s]?:\/\/base.google.com/sui' ,
        'yandex-yml'    => '/<\?xml[^>]*>[\s\n\r\n]*?(?:<!doctype\s[^>]*?yml_catalog[^>]*?>|)[\s\n\r\n]*?<yml_catalog\sdate\s?=\s?["|\']{1}[^"\']*["|\']{1}>/sui',
    );

    public function __construct($registry)
    {
        $this->db = $registry->db;
    }
    public function getParser($supplier) {
        if (!$supplier['type']) {

        }
    }
}