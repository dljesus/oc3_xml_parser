-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.40 - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              9.3.0.5119
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица asper.xml.oc_asper_supplier
CREATE TABLE IF NOT EXISTS `oc_asper_supplier` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_parse` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `cron` tinyint(1) NOT NULL DEFAULT '1',
  `stock_status_id` int(5) DEFAULT NULL,
  `quantity` int(5) DEFAULT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы asper.xml.oc_asper_supplier: ~1 rows (приблизительно)
/*!40000 ALTER TABLE `oc_asper_supplier` DISABLE KEYS */;
INSERT INTO `oc_asper_supplier` (`supplier_id`, `name`, `url`, `type`, `date_add`, `date_parse`, `status`, `cron`, `stock_status_id`, `quantity`) VALUES
	(1, 'asdasd', 'https://mrjunior.com.ua/yandex_market.xml?html_description=0&hash_tag=32454267faed6bc7fdab829030b92516&yandex_cpa=&group_ids=18964796&exclude_fields=&label_ids=&sales_notes=&process_presence_sure=&product_ids=', 'YandexYml', '2019-07-16 20:58:16', NULL, 1, 1, NULL, NULL);
/*!40000 ALTER TABLE `oc_asper_supplier` ENABLE KEYS */;

-- Дамп структуры для таблица asper.xml.oc_asper_supplier_category
CREATE TABLE IF NOT EXISTS `oc_asper_supplier_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `external_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL COMMENT 'inner id',
  `parent_id` int(11) DEFAULT '0',
  `not_add` tinyint(4) NOT NULL DEFAULT '0',
  `add_to_parent` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы asper.xml.oc_asper_supplier_category: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `oc_asper_supplier_category` DISABLE KEYS */;
INSERT INTO `oc_asper_supplier_category` (`id`, `supplier_id`, `external_id`, `name`, `category_id`, `parent_id`, `not_add`, `add_to_parent`) VALUES
	(1, 1, 6321187, 'Мебель в детскую комнату', 18, 0, 0, 1),
	(2, 1, 18964796, 'Овальные кроватки трансформер 7 в 1', 28, 6321187, 0, 1);
/*!40000 ALTER TABLE `oc_asper_supplier_category` ENABLE KEYS */;

-- Дамп структуры для таблица asper.xml.oc_asper_supplier_products
CREATE TABLE IF NOT EXISTS `oc_asper_supplier_products` (
  `external_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `external_category_id` int(11) NOT NULL,
  `parsed` tinyint(4) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `supplier_id` int(11) NOT NULL,
  PRIMARY KEY (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы asper.xml.oc_asper_supplier_products: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `oc_asper_supplier_products` DISABLE KEYS */;
INSERT INTO `oc_asper_supplier_products` (`external_id`, `product_id`, `data`, `external_category_id`, `parsed`, `name`, `supplier_id`) VALUES
	('497011324_1', 56, 'a:11:{s:2:"id";s:9:"497011324";s:5:"price";s:4:"4200";s:4:"name";s:90:"Круглая кровакта / Овальная кроватка 9 в 1 белая Pite";s:11:"description";s:3167:"Кроватка  растет с Вашим малышом с 0 и до 10 лет.\n\n Детская многофункциональная кроватка-трансформер  - это гораздо больше, чем просто детская кроватка. Она создает комфортную среду обитания для новорожденного с первых дней его жизни. Круглая кроватка похожа на маленькое уютное гнездышко, удобное и безопасное. \n\n\n	Размеры колыбельки (ДхШхВ) 76x76x82\n\n\n \n\n\n	Размеры овальной кроватки (ДхШхВ) 128x76x82\n\n\n \n\n\n	Размер транспортируемой коробки 0.77х0.57х0.37, Вес 27кг.\n\n\n \n \n\nВ  комплект входит:\n\n \n\n1. Кроватка-трансформер:\n\n\n\n- 1-я ступень - круглая колыбелька с самого рождения \n\n- 2-я ступень пеленальный столик\n\n- 3-я ступень - овальная кроватка\n\n- 4-я ступень - диванчик\n\n- 5-я ступень - манеж\n\n- 6-я ступень 2 кресла и столик\n\n- 7-я ступень подростковая кровать\n\nПреимущества:\n\n \n\n\n	Легко трансформируется в колыбельку для новорожденных, полноценную кроватку, манеж, диван и два комфортных кресла;\n	4 положения по высоте \n	Сделана из экологически чистых и прочных материалов - массива ольхи. Выстоит под натиском прорезающихся зубов;\n	Наличие оптимальной естественной вентиляции;\n	Круглая форма маленькой колыбельки напоминает новорожденному мамин животик, малышу будет очень уютно и комфортно, как бы он не поворачивался.\n	Когда Ваш маленький исследователь подрастет, он сможет самостоятельно и с интересом забираться к себе в кроватку!\n	Древесина обработана по специальной технологии без возможности нанесения вреда ребенку. Лакировка сертифицирована лаки на водной основе, является экологическим. Характеризуется очень коротким временем высыхания и хорошей липкостью к основанию. Лак образует поверхность, соответствующую требованиям нормы , что является рекомендацией к его применению для покрытия детской мебели и игрушек. Обладает высокой механической устойчивостью.";s:5:"image";s:72:"catalog/k/r/kruglaya-krovakta--ovalnaya-krovatka-9-v-1-belaya-pite-0.jpg";s:6:"images";a:7:{i:0;s:72:"catalog/k/r/kruglaya-krovakta--ovalnaya-krovatka-9-v-1-belaya-pite-1.jpg";i:1;s:72:"catalog/k/r/kruglaya-krovakta--ovalnaya-krovatka-9-v-1-belaya-pite-2.jpg";i:2;s:72:"catalog/k/r/kruglaya-krovakta--ovalnaya-krovatka-9-v-1-belaya-pite-3.jpg";i:3;s:72:"catalog/k/r/kruglaya-krovakta--ovalnaya-krovatka-9-v-1-belaya-pite-4.jpg";i:4;s:72:"catalog/k/r/kruglaya-krovakta--ovalnaya-krovatka-9-v-1-belaya-pite-5.jpg";i:5;s:72:"catalog/k/r/kruglaya-krovakta--ovalnaya-krovatka-9-v-1-belaya-pite-6.jpg";i:6;s:72:"catalog/k/r/kruglaya-krovakta--ovalnaya-krovatka-9-v-1-belaya-pite-7.jpg";}s:8:"category";s:8:"18964796";s:3:"url";s:54:"kruglaya-krovakta--ovalnaya-krovatka-9-v-1-belaya-pite";s:11:"category_id";s:2:"28";s:4:"path";a:2:{i:0;s:2:"25";i:1;s:2:"28";}s:13:"add_to_parent";s:1:"1";}', 0, 1, 'Круглая кровакта / Овальная кроватка 9 в 1 белая Pite', 1),
	('497012977_1', 57, 'a:11:{s:2:"id";s:9:"497012977";s:5:"price";s:4:"4200";s:4:"name";s:107:"Круглая кроватка / Овальная кроватка 9 в 1 слоновая кость Pite";s:11:"description";s:3168:"С 0 и до 10 лет. Кроватка  растет с Вашим малышом. Детская многофункциональная кроватка-трансформер  - это гораздо больше, чем просто детская кроватка. Она создает комфортную среду обитания для новорожденного с первых дней его жизни. Круглая кроватка похожа на маленькое уютное гнездышко, удобное и безопасное. \n\n \n\n	Размеры колыбельки (ДхШхВ) 76x76x82\n\n\n \n\n\n	Размеры овальной кроватки (ДхШхВ) 128x76x82\n\n\n \n\n\n	Размер транспортируемой коробки 0.77х0.57х0.37, Вес 27кг.\n\n\n \n \n\nВ  комплект входит:\n\n \n\n1. Кроватка-трансформер:\n\n\n\n- 1-я ступень - круглая колыбелька с самого рождения \n\n- 2-я ступень пеленальный столик\n\n- 3-я ступень - овальная кроватка\n\n- 4-я ступень - диванчик\n\n- 5-я ступень - манеж\n\n- 6-я ступень 2 кресла и столик\n\n- 7-я ступень подростковая кровать\n\nПреимущества:\n\n \n\n\n	Легко трансформируется в колыбельку для новорожденных, полноценную кроватку, манеж, диван и два комфортных кресла;\n	4 положения по высоте \n	Сделана из экологически чистых и прочных материалов - массива ольхи. Выстоит под натиском прорезающихся зубов;\n	Наличие оптимальной естественной вентиляции;\n	Круглая форма маленькой колыбельки напоминает новорожденному мамин животик, малышу будет очень уютно и комфортно, как бы он не поворачивался.\n	Когда Ваш маленький исследователь подрастет, он сможет самостоятельно и с интересом забираться к себе в кроватку!\n	Древесина обработана по специальной технологии без возможности нанесения вреда ребенку. Лакировка сертифицирована лаки на водной основе, является экологическим. Характеризуется очень коротким временем высыхания и хорошей липкостью к основанию. Лак образует поверхность, соответствующую требованиям нормы , что является рекомендацией к его применению для покрытия детской мебели и игрушек. Обладает высокой механической устойчивостью.";s:5:"image";s:80:"catalog/k/r/kruglaya-krovatka--ovalnaya-krovatka-9-v-1-slonovaya-kost-pite-0.jpg";s:6:"images";a:5:{i:0;s:80:"catalog/k/r/kruglaya-krovatka--ovalnaya-krovatka-9-v-1-slonovaya-kost-pite-1.jpg";i:1;s:80:"catalog/k/r/kruglaya-krovatka--ovalnaya-krovatka-9-v-1-slonovaya-kost-pite-2.jpg";i:2;s:80:"catalog/k/r/kruglaya-krovatka--ovalnaya-krovatka-9-v-1-slonovaya-kost-pite-3.jpg";i:3;s:80:"catalog/k/r/kruglaya-krovatka--ovalnaya-krovatka-9-v-1-slonovaya-kost-pite-4.jpg";i:4;s:80:"catalog/k/r/kruglaya-krovatka--ovalnaya-krovatka-9-v-1-slonovaya-kost-pite-5.jpg";}s:8:"category";s:8:"18964796";s:3:"url";s:62:"kruglaya-krovatka--ovalnaya-krovatka-9-v-1-slonovaya-kost-pite";s:11:"category_id";s:2:"28";s:4:"path";a:2:{i:0;s:2:"25";i:1;s:2:"28";}s:13:"add_to_parent";s:1:"1";}', 0, 1, 'Круглая кроватка / Овальная кроватка 9 в 1 слоновая кость Pite', 1),
	('497013724_1', 58, 'a:11:{s:2:"id";s:9:"497013724";s:5:"price";s:4:"4200";s:4:"name";s:97:"Круглая кроватка /Овальная кроватка  9  в 1 ореховая Pite";s:11:"description";s:3168:"С 0 и до 10 лет. Кроватка  растет с Вашим малышом. Детская многофункциональная кроватка-трансформер  - это гораздо больше, чем просто детская кроватка. Она создает комфортную среду обитания для новорожденного с первых дней его жизни. Круглая кроватка похожа на маленькое уютное гнездышко, удобное и безопасное. \n\n \n\n	Размеры колыбельки (ДхШхВ) 76x76x82\n\n\n \n\n\n	Размеры овальной кроватки (ДхШхВ) 128x76x82\n\n\n \n\n\n	Размер транспортируемой коробки 0.77х0.57х0.37, Вес 27кг.\n\n\n \n \n\nВ  комплект входит:\n\n \n\n1. Кроватка-трансформер:\n\n\n\n- 1-я ступень - круглая колыбелька с самого рождения \n\n- 2-я ступень пеленальный столик\n\n- 3-я ступень - овальная кроватка\n\n- 4-я ступень - диванчик\n\n- 5-я ступень - манеж\n\n- 6-я ступень 2 кресла и столик\n\n- 7-я ступень подростковая кровать\n\nПреимущества:\n\n \n\n\n	Легко трансформируется в колыбельку для новорожденных, полноценную кроватку, манеж, диван и два комфортных кресла;\n	4 положения по высоте \n	Сделана из экологически чистых и прочных материалов - массива ольхи. Выстоит под натиском прорезающихся зубов;\n	Наличие оптимальной естественной вентиляции;\n	Круглая форма маленькой колыбельки напоминает новорожденному мамин животик, малышу будет очень уютно и комфортно, как бы он не поворачивался.\n	Когда Ваш маленький исследователь подрастет, он сможет самостоятельно и с интересом забираться к себе в кроватку!\n	Древесина обработана по специальной технологии без возможности нанесения вреда ребенку. Лакировка сертифицирована лаки на водной основе, является экологическим. Характеризуется очень коротким временем высыхания и хорошей липкостью к основанию. Лак образует поверхность, соответствующую требованиям нормы , что является рекомендацией к его применению для покрытия детской мебели и игрушек. Обладает высокой механической устойчивостью.";s:5:"image";s:74:"catalog/k/r/kruglaya-krovatka-ovalnaya-krovatka-9-v-1-orehovaya-pite-0.jpg";s:6:"images";a:7:{i:0;s:74:"catalog/k/r/kruglaya-krovatka-ovalnaya-krovatka-9-v-1-orehovaya-pite-1.jpg";i:1;s:74:"catalog/k/r/kruglaya-krovatka-ovalnaya-krovatka-9-v-1-orehovaya-pite-2.jpg";i:2;s:74:"catalog/k/r/kruglaya-krovatka-ovalnaya-krovatka-9-v-1-orehovaya-pite-3.jpg";i:3;s:74:"catalog/k/r/kruglaya-krovatka-ovalnaya-krovatka-9-v-1-orehovaya-pite-4.jpg";i:4;s:74:"catalog/k/r/kruglaya-krovatka-ovalnaya-krovatka-9-v-1-orehovaya-pite-5.jpg";i:5;s:74:"catalog/k/r/kruglaya-krovatka-ovalnaya-krovatka-9-v-1-orehovaya-pite-6.jpg";i:6;s:74:"catalog/k/r/kruglaya-krovatka-ovalnaya-krovatka-9-v-1-orehovaya-pite-7.jpg";}s:8:"category";s:8:"18964796";s:3:"url";s:56:"kruglaya-krovatka-ovalnaya-krovatka-9-v-1-orehovaya-pite";s:11:"category_id";s:2:"28";s:4:"path";a:2:{i:0;s:2:"25";i:1;s:2:"28";}s:13:"add_to_parent";s:1:"1";}', 0, 1, 'Круглая кроватка /Овальная кроватка  9  в 1 ореховая Pite', 1);
/*!40000 ALTER TABLE `oc_asper_supplier_products` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
