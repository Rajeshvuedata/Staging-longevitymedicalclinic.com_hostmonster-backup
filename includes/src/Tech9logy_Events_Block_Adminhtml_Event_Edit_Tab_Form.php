<?php
class Tech9logy_Events_Block_Adminhtml_Event_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{
				$model = Mage::registry('event_data');
				$form = new Varien_Data_Form();
				$this->setForm($form);
				//echo $model->getIswebinar();
				$fieldset = $form->addFieldset("events_form", array("legend"=>Mage::helper("events")->__("Event information")));

						$web = $fieldset->addField("iswebinar", "checkbox", array(
						  "label" => Mage::helper("events")->__("Webinar"),
						  "name"      => "iswebinar",
						  "checked"    => $model->getIswebinar()==1 ? 'true' : '',
						  "onclick" => "this.value = this.checked ? 1 : 0;",             
						));
		
						$fieldset->addField("title", "text", array(
						"label" => Mage::helper("events")->__("Title"),
						"name" => "title",
						"required"  => true,
						));
					
						$fieldset->addField("event_date", "text", array(
						"label" => Mage::helper("events")->__("Event Date"),
						"name" => "event_date",
						));
						
						$fieldset->addField("event_time", "text", array(
						"label" => Mage::helper("events")->__("Event Time"),
						"name" => "event_time",
						));
						
						$location = $fieldset->addField("location", "text", array(
						"label" => Mage::helper("events")->__("Location"),
						"name" => "location",
						));
						
						$street = $fieldset->addField("street", "text", array(
						"label" => Mage::helper("events")->__("Street"),
						"name" => "street",
						));
						
						$fieldset->addField("city", "text", array(
						"label" => Mage::helper("events")->__("City"),
						"name" => "city",
						));
						
						$state = $fieldset->addField("state", "text", array(
						"label" => Mage::helper("events")->__("State"),
						"name" => "state",
						));
						
						$zip = $fieldset->addField("zipcode", "text", array(
						"label" => Mage::helper("events")->__("Zipcode"),
						"name" => "zipcode",
						));
						
						$fieldset->addField("event_start_date", "text", array(
						"label" => Mage::helper("events")->__("Display Start Date"),
						"name" => "event_start_date",
						));
						
						$fieldset->addField("event_end_date", "text", array(
						"label" => Mage::helper("events")->__("Display End Date"),
						"name" => "event_end_date",
						));
						
						$fieldset->addField("event_start_time", "text", array(
						"label" => Mage::helper("events")->__("Display Start Time"),
						"name" => "event_start_time",
						));
						
						$fieldset->addField("event_end_time", "text", array(
						"label" => Mage::helper("events")->__("Display End Time"),
						"name" => "event_end_time",
						));
						
						$desc = $fieldset->addField("description", "textarea", array(
						"label" => Mage::helper("events")->__("Description"),
						"name" => "description",
						));
						
						$map = $fieldset->addField("google_map", "textarea", array(
						"label" => Mage::helper("events")->__("Google Map"),
						"name" => "google_map",
						));

						$img = $fieldset->addField("image", "file", array(
						"label" => Mage::helper("events")->__("Image"),
						"name" => "image",
						));

						
						
					
				/*
				if (Mage::getSingleton("adminhtml/session")->getEventData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getEventData());
					Mage::getSingleton("adminhtml/session")->setEventData(null);
				} 
				elseif(Mage::registry("event_data")) {
				    $form->setValues(Mage::registry("event_data")->getData());
				}*/
				
				 if (Mage::getSingleton('adminhtml/session')->getEventData()) {
					$_data = Mage::getSingleton('adminhtml/session')->getEventData();
					Mage::getSingleton('adminhtml/session')->setEventData(null);
				} elseif (Mage::registry('event_data')) {
					$_data = Mage::registry('event_data')->getData();
				}

				$form->setValues($_data);

				$this->setForm($form);
				
				$this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
					->addFieldMap($location->getHtmlId(), $location->getName())
					->addFieldMap($street->getHtmlId(), $street->getName())
					->addFieldMap($state->getHtmlId(), $state->getName())
					->addFieldMap($zip->getHtmlId(), $zip->getName())
					->addFieldMap($desc->getHtmlId(), $desc->getName())
					->addFieldMap($map->getHtmlId(), $map->getName())
					->addFieldMap($img->getHtmlId(), $img->getName())
					->addFieldMap($web->getHtmlId(), $web->getName())
					->addFieldDependence(
						$location->getName(),
						$web->getName(),
						'0'
					)
					->addFieldDependence(
						$street->getName(),
						$web->getName(),
						'0'
					)
					->addFieldDependence(
						$state->getName(),
						$web->getName(),
						'0'
					)
					->addFieldDependence(
						$zip->getName(),
						$web->getName(),
						'0'
					)
					->addFieldDependence(
						$desc->getName(),
						$web->getName(),
						'0'
					)
					->addFieldDependence(
						$map->getName(),
						$web->getName(),
						'0'
					)
					->addFieldDependence(
						$img->getName(),
						$web->getName(),
						'0'
					)
				);
				
				
				
				
				return parent::_prepareForm();
		}
}
