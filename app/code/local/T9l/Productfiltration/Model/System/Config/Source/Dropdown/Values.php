<?php

class T9l_Productfiltration_Model_System_Config_Source_Dropdown_Values
{
    public function toOptionArray()
    {
	 $dropval=Mage::getStoreConfig('tab1/general/generaldropdown');
	 $drpval=explode(',',$dropval);
	 $data =array();
	 foreach($drpval as $key=>$val){
	if($val== "mostviewed"){
	$data[] =array('value' => 'mostviewed',
                'label' => 'Most Viewed Product');
	}
	if($val== "bestreviewed"){
	$data[] =array('value' => 'bestreviewed',
                'label' => 'Best Reviewed Product');
	}
	if($val== "featured"){
	$data[]  =array('value' => 'featured',
                'label' => 'Featured Product');
	}
	if($val== "bestseller"){
	$data[] =array('value' => 'bestseller',
                'label' => 'Best Seller Product');
	}
	if($val== "latest"){
	$data[] =array('value' => 'latest',
                'label' => 'Latest Product');
	}
	
	}
	return $data;
    }
}
