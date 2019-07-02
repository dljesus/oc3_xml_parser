<?php
// Version
die;
$ch = curl_init("http://detopt.com.ua/content/export_opt.xml");
$fp = fopen("yml3.xml", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
fclose($fp);
var_dump(123);
die;
$xmldas = SDO_DAS_XML::create("letter.xsd");
var_dump($xmldas);die;
//$document = $xmldas->loadFile("letter.xml");
//$root_data_object = $document->getRootDataObject();
//$root_data_object->date = "September 03, 2004";
//$root_data_object->firstName = "Anantoju";
//$root_data_object->lastName = "Madhu";
//$xmldas->saveFile($document, "letter-out.xml");
//echo "New file has been written:\n";
//print file_get_contents("letter-out.xml");

define('VERSION', '3.0.3.2');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');