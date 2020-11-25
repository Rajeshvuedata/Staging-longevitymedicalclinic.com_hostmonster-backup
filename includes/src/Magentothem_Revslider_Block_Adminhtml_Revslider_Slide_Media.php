<?php
class Magentothem_Revslider_Block_Adminhtml_Revslider_Slide_Media extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct() {


        $this->_controller = 'adminhtml_revslider_slide';
        $this->_blockGroup = 'revslider';
        $this->_headerText = Mage::helper('revslider')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('revslider')->__('Add Item');

        parent::__construct();
    }
	 public function getSlide() {
      $collection = Mage::getModel('revslider/slide')->getCollection();
      return $collection;
  }
}