<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('revslider')};
CREATE TABLE {$this->getTable('revslider')} (
  `revslider_id` int(11) unsigned NOT NULL auto_increment,
   title tinytext NOT NULL,
   alias tinytext,
   params text NOT NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`revslider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('revslider')};
CREATE TABLE IF NOT EXISTS {$this->getTable('revslider/slide')} (
 `slide_id` int(10) NOT NULL auto_increment,
 `revslider_id` int(10) NOT NULL,
 `title` varchar(255) NOT NULL default '',
 `params` text NOT NULL default '',
 `layers` text NOT NULL default '',
 `status` varchar(255) NOT NULL default '1',
 `s_order` int(10) NOT NULL default '0',
 PRIMARY KEY  (`slide_id`),
 KEY `FK_revslider` (`revslider_id`),
 CONSTRAINT `FK_revslider` FOREIGN KEY (`revslider_id`) REFERENCES {$this->getTable('revslider')} (`revslider_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('revslider')};
CREATE TABLE IF NOT EXISTS {$this->getTable('revslider/caption')} (
 `caption_id` int(10) NOT NULL auto_increment,
  handle text NOT NULL,
  settings text,
  hover text,
  params text NOT NULL,
 PRIMARY KEY  (`caption_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('revslider')};
CREATE TABLE IF NOT EXISTS {$this->getTable('revslider/animation')} (
 `animation_id` int(10) NOT NULL auto_increment,
  handle text NOT NULL,
  params text NOT NULL,
 PRIMARY KEY  (`animation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->run("
INSERT INTO `revslider_caption` (`caption_id`, `handle`, `settings`, `hover`, `params`) VALUES
(1, '.tp-caption.title_banner', '{\"hover\":\"\"}', '\"\"', '{\"text-decoration\":\"none\",\"font-size\":\"28px\",\"color\":\"#444444\",\"font-family\":\"breeserifregular\",\"background-color\":\"transparent\",\"border-width\":\"0px\",\"border-color\":\"rgb(68, 68, 68)\",\"border-style\":\"none\"}'),
(2, '.tp-caption.title-banner', '{\"hover\":\"\"}', '\"\"', '{\"text-decoration\":\"none\",\"background-color\":\"transparent\",\"font-size\":\"28px\",\"font-family\":\"breeserifregular\",\"color\":\"#444444\",\"text-transform\":\"capitalize\",\"border-width\":\"0px\",\"border-color\":\"rgb(68, 68, 68)\",\"border-style\":\"none\"}'),
(3, '.tp-caption.big_black1', '{\"hover\":\"\"}', '\"\"', '{\"font-family\":\"''Palatino Linotype'',''Book Antiqua'',Palatino,serif\",\"text-decoration\":\"none\",\"background-color\":\"transparent\",\"border-width\":\"0px\",\"border-color\":\"rgb(34, 34, 34)\",\"border-style\":\"none\"}'),
(4, '.tp-caption.des_banner', '{\"hover\":\"\"}', '\"\"', '{\"text-decoration\":\"none\",\"background-color\":\"transparent\",\"font-family\":\"LatoRegular\",\"color\":\"#444444\",\"font-size\":\"14px\",\"border-width\":\"0px\",\"border-color\":\"rgb(34, 34, 34)\",\"border-style\":\"none\"}'),
(5, '.tp-caption.des1_banner', '{\"hover\":\"\"}', '\"\"', '{\"text-decoration\":\"none\",\"background-color\":\"transparent\",\"font-size\":\"12px\",\"font-family\":\"LatoBold\",\"color\":\"#ffffff\",\"text-shadow\":\"1px 1px #cbcbcd\",\"text-transform\":\"uppercase\",\"border-width\":\"0px\",\"border-color\":\"rgb(34, 34, 34)\",\"border-style\":\"none\"}'),
(6, '.tp-caption.title_banner1', '{\"hover\":\"\"}', '\"\"', '{\"font-family\":\"breeserifregular\",\"color\":\"#ffffff\",\"text-decoration\":\"none\",\"font-size\":\"30px\",\"text-transform\":\"uppercase\",\"border-width\":\"0px\",\"border-color\":\"rgb(255, 255, 255)\",\"border-style\":\"none\"}'),
(7, '.tp-caption.des_banner1', '{\"hover\":\"\"}', '\"\"', '{\"text-decoration\":\"underline\",\"background\":\"#848486\",\"font-size\":\"14px\",\"font-family\":\"latoheavy\",\"color\":\"#444444\",\"padding\":\"3px 16px 5px 16px\",\"background-color\":\"transparent\",\"text-transform\":\"uppercase\",\"border-width\":\"0px\",\"border-color\":\"rgb(68, 68, 68)\",\"border-style\":\"none\"}'),
(8, '.tp-caption.price_banner1', '{\"hover\":\"\"}', '\"\"', '{\"text-decoration\":\"none\",\"text-transform\":\"uppercase\",\"font-size\":\"20px\",\"font-family\":\"breeserifregular\",\"color\":\"#ffffff\",\"background\":\"#ff9d34\",\"padding\":\"6px 16px 8px 16px\",\"border-width\":\"0px\",\"border-color\":\"rgb(255, 255, 255)\",\"border-style\":\"none\"}'),
(9, '.tp-caption.price1_banner1', '{\"hover\":\"\"}', '\"\"', '{\"text-decoration\":\"none\",\"background\":\"#848486\",\"font-size\":\"13px\",\"font-family\":\"breeserifregular\",\"color\":\"#ffffff\",\"padding\":\"3px 16px 5px 16px\",\"border-width\":\"0px\",\"border-color\":\"rgb(34, 34, 34)\",\"border-style\":\"none\"}'),
(10, '.tp-caption.title_banner2', '{\"hover\":\"\"}', '\"\"', '{\"text-decoration\":\"none\",\"font-size\":\"30px\",\"font-family\":\"breeserifregular\",\"color\":\"#444444\",\"text-transform\":\"uppercase\",\"border-width\":\"0px\",\"border-color\":\"rgb(68, 68, 68)\",\"border-style\":\"none\"}');
");


$helper =  Mage::helper('revslider/data'); 
$helper ->importRevsliderData();
$installer->endSetup(); 