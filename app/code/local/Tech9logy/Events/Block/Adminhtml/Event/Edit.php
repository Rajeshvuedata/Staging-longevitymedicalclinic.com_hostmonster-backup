<?php
	
class Tech9logy_Events_Block_Adminhtml_Event_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "events";
				$this->_controller = "adminhtml_event";
				$this->_updateButton("save", "label", Mage::helper("events")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("events")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("events")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("event_data") && Mage::registry("event_data")->getId() ){

				    return Mage::helper("events")->__("Edit Event '%s'", $this->htmlEscape(Mage::registry("event_data")->getId()));

				} 
				else{

				     return Mage::helper("events")->__("Add Item");

				}
		}
}