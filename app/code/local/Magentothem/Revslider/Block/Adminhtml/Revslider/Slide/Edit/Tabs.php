<?php

class Magentothem_Revslider_Block_Adminhtml_Revslider_Slide_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('revslider_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('revslider')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('revslider')->__(' General'),
          'title'     => Mage::helper('revslider')->__(' General'),
          'content'   => $this->getLayout()->createBlock('revslider/adminhtml_revslider_slide_edit_tab_form')->toHtml(),
      ));
	  
	  $this->addTab('form_section_banner', array(
            'label' => Mage::helper('revslider')->__('Banner'),
            'title' => Mage::helper('revslider')->__('Banner'),
            'content' => $this->getLayout()->createBlock('revslider/adminhtml_revslider_slide_edit_tab_banner')->toHtml(),
            'class' => 'collapseable'
        ));
     
      return parent::_beforeToHtml();
  }
}