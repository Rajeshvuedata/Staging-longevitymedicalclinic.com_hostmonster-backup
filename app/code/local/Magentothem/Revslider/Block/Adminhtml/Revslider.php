<?php
class Magentothem_Revslider_Block_Adminhtml_Revslider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_revslider';
    $this->_blockGroup = 'revslider';
    $this->_headerText = Mage::helper('revslider')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('revslider')->__('Add Item');
	$this->_addButton('exportslide', array(
		'label'     => Mage::helper('revslider')->__('Export Slide'),
		'onclick'   => "setLocation('".$this->getUrl('*/*/exportSlide')."')",
	));
    parent::__construct();
  }
}