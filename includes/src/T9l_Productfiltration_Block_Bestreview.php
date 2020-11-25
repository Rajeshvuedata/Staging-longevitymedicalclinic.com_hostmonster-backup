<?php
class T9l_Productfiltration_Block_Bestreview extends Mage_Catalog_Block_Product_Abstract
{
public function getReviewedProductID()
	{
	$storeId = Mage::app()->getStore()->getId();
	$product = Mage::getModel('catalog/product');
	$products = $product->getCollection()->addStoreFilter($storeId)->getData();
	$product_id=array();
	for($i=0;$i<count($products);$i++){
	array_push($product_id,$products[$i]['entity_id']);
		}
	return $product_id;
	}



public function getTotalReviews($id)
    {	for($i=0;$i<count($id);$i++){
	$reviewsCount = Mage::getModel('review/review')->getTotalReviews($id[$i], true, Mage::app()->getStore()->getId());
	$arr[$id[$i]]=$reviewsCount;
    	}
	return $arr;

    }

}
?>

