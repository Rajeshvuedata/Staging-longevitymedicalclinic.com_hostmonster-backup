<?php

class Magentothem_Revslider_Model_Slide extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('revslider/slide');
    }
	
	public function getSlideByRevId($rev_id = NULL) {
		$data = Mage::getModel('revslider/slide')
				->getCollection()
				->addAttributeToFilter('revslider_id',$rev_id);
		 if($data) return $data; 		
	}
}