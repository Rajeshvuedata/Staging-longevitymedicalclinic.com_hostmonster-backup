<?php
class Magentothem_Revslider_Block_Adminhtml_Revslider_Slide_Slide extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_revslider_slide';
    $this->_blockGroup = 'revslider';
    $this->_headerText = Mage::helper('revslider')->__('Slide Manager');
    $this->_addButtonLabel = Mage::helper('revslider')->__('');
	$id = $this->getRequest()->getParam('revslider_id');
	$slide_id = $this->getRequest()->getParam('slide_id');
	$this->_addButton('addslide', array(
		'label'     => Mage::helper('revslider')->__('Add Slide'),
		'onclick'   => "setLocation('".$this->getUrl('*/*/editbanner/revslider_id/'.$id.'/slide_id/'.$slide_id)."')",
	));
    parent::__construct();
	$this->_removeButton('add');
	
  }
}