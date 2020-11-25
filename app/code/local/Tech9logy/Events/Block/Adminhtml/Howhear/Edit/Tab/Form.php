<?php
class Tech9logy_Events_Block_Adminhtml_Howhear_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("events_form", array("legend"=>Mage::helper("events")->__("Item information")));

				
						$fieldset->addField("from_where_hear", "text", array(
						"label" => Mage::helper("events")->__("Hear From"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "from_where_hear",
						));
									
						 $fieldset->addField('status', 'select', array(
						'label'     => Mage::helper('events')->__('status'),
						'values'   => Tech9logy_Events_Block_Adminhtml_Howhear_Grid::getValueArray1(),
						'name' => 'status',					
						"class" => "required-entry",
						"required" => true,
						));

				if (Mage::getSingleton("adminhtml/session")->getHowhearData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getHowhearData());
					Mage::getSingleton("adminhtml/session")->setHowhearData(null);
				} 
				elseif(Mage::registry("howhear_data")) {
				    $form->setValues(Mage::registry("howhear_data")->getData());
				}
				return parent::_prepareForm();
		}
}
