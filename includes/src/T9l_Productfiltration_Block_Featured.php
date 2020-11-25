<?php
class T9l_Productfiltration_Block_Featured extends Mage_Catalog_Block_Product_Abstract
{
public function getFeaturedProduct()
{
$totalPerPage = ($this->show_total) ? $this->show_total : 6;
$visibility = array(
Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
);
$storeId = Mage::app()->getStore()->getId();
$_productCollection = Mage::getResourceModel('reports/product_collection')
->addAttributeToSelect('*')
->setStoreId($storeId)
->addStoreFilter($storeId)
->addAttributeToFilter('visibility', $visibility)
->addAttributeToFilter('featured', true)
->setOrder('created_at', 'desc')
->addAttributeToSelect('status')
->setPageSize($totalPerPage);

return $_productCollection;
}
}
?>
