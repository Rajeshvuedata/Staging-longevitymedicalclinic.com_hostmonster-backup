<?php

class Magentothem_Revslider_Block_Adminhtml_Revslider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'revslider_id';
        $this->_blockGroup = 'revslider';
        $this->_controller = 'adminhtml_revslider';
        
        $this->_updateButton('save', 'label', Mage::helper('revslider')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('revslider')->__('Delete Item'));
		$id = $this->getRequest()->getParam('id');

		$slide_id = $this->getRequest()->getParam('slide_id');
		$this->_addButton('addslide', array(
			'label'     => Mage::helper('revslider')->__('Add Slide'),
			'onclick'   => "setLocation('".$this->getUrl('*/*/editbanner/revslider_id/'.$id.'/slide_id/'.$slide_id)."')",
        ));
		//	'onclick'   => "setLocation('".Mage::helper("adminhtml")->getUrl("revslider/adminhtml_slider/editbanner/id/".$id)."')",
         
		 $this->_addButton('listslider', array(
			'label'     => Mage::helper('revslider')->__('More Slides'),
			 'onclick'   => "setLocation('".Mage::helper("adminhtml")->getUrl("revslider/adminhtml_revslider/slide/revslider_id/".$id)."')",
         ));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
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
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('revslider_data') && Mage::registry('revslider_data')->getId() ) {
            return Mage::helper('revslider')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('revslider_data')->getTitle()));
        } else {
            return Mage::helper('revslider')->__('Add Item');
        }
    }
}