<?php 

define('ROOT', '../../../');
$mage_php_url = ROOT.'app/Mage.php';
if (!empty($mage_php_url) && file_exists($mage_php_url) && !is_dir($mage_php_url))
{
	// Include Magento's Mage.php file.
	require_once ( $mage_php_url );
	umask(0);
	Mage::app();
}
$helper = Mage::helper('revslider/data');
$collection = Mage::getModel('revslider/caption')->getCollection(); 
	$arrayCaptions = array();
	foreach($collection as $caption) {
		$arrayCaptions[] = $caption->getData(); 
	}
	$contentCss = $helper->parseDbArrayToCss($arrayCaptions);
	echo "<pre>";print_r($contentCss); echo "</pre>";