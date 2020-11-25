<?php

class Magentothem_Revslider_Block_Adminhtml_Revslider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
          'label'     => Mage::helper('revslider')->__('Settings'),
          'title'     => Mage::helper('revslider')->__('Settings'),
          'content'   => $this->getLayout()->createBlock('revslider/adminhtml_revslider_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}