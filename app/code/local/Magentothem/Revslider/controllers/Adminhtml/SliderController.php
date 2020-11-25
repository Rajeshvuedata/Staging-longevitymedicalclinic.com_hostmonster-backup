<?php

class Magentothem_Revslider_Adminhtml_SliderController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('revslider/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

	
 
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		
	}
 
	
}