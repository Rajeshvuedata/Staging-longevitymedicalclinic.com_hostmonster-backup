<?php 
define('ROOT', '../../../../');
$mage_php_url = ROOT.'app/Mage.php';
if (!empty($mage_php_url) && file_exists($mage_php_url) && !is_dir($mage_php_url))
{
	// Include Magento's Mage.php file.
	require_once ( $mage_php_url );
	umask(0);
	Mage::app();
}
$helper = Mage::helper('revslider/data');
$cssContent = $helper->getCaptionData();
header("Content-type: text/css; charset: UTF-8");
echo $cssContent;