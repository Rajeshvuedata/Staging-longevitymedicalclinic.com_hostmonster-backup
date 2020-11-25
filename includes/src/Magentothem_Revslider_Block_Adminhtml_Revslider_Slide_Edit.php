<?php

class Magentothem_Revslider_Block_Adminhtml_Revslider_Slide_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'slide_id';
        $this->_blockGroup = 'revslider';
        $this->_controller = 'adminhtml_revslider_slide';
        
        //$this->_updateButton('save', 'label', Mage::helper('revslider')->__('Save Item'));
		$this->_removeButton('save');
        $this->_updateButton('delete', 'label', Mage::helper('revslider')->__('Delete Item'));
		$id = $this->getRequest()->getParam('revslider_id');
		$this->_addButton('listslider', array(
			'label'     => Mage::helper('revslider')->__('More Slides'),
			 'onclick'   => "setLocation('".Mage::helper("adminhtml")->getUrl("revslider/adminhtml_revslider/slide/revslider_id/".$id)."')",
         ));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save button_save_slide',
        ), -100);
		
		$this->_addButton('saveandclose', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Close'),
            'class'     => 'save button_save_close',
        ), -100);


        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('revslider_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'revslider_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'revslider_content');
                }
            }

            function saveAndContinueEdit(){
                //editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('revslider_slide') && Mage::registry('revslider_slide')->getSlideId() ) {
            return Mage::helper('revslider')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('revslider_data')->getTitle()));
        } else {
            return Mage::helper('revslider')->__('Add Item');
        }
    }
}