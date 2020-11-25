<?php

class Magentothem_Revslider_Block_Adminhtml_Revslider_Slide_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('slide_form', array('legend'=>Mage::helper('revslider')->__('Main Slider Setting')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('revslider')->__('Slider Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
		  'after_element_html' => '<small>The title of the slide, will be shown in the slides list.</small>',
      ));
	  
	  $fieldset->addField('slot_amount', 'text', array(
          'label'     => Mage::helper('revslider')->__('Slot Amount'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'slot_amount',
		  'after_element_html' => '<small>   The number of slots or boxes the slide is divided into. If you use boxfade, over 7 slots can be juggy.</small>',
  
      ));
	  
	  $fieldset->addField('transition_rotation', 'text', array(
          'label'     => Mage::helper('revslider')->__('Rotation'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'transition_rotation',
		  'after_element_html' => '<small>Rotation (-720 -> 720, 999 = random) Only for Simple Transitions.</small>',

      ));
	  
	  $slideTrans = Mage::helper('revslider/data')->getSlideTrasition();
	  $fieldset->addField('slide_transition', 'select', array(
          'label'     => Mage::helper('revslider')->__('Slide Transition'),
          'name'      => 'slide_transition',
          'values'    =>$slideTrans,
		  'after_element_html' => '<small>The appearance transitions of this slide.</small>',

      ));
	  
	   $fieldset->addField('transition_duration', 'text', array(
          'label'     => Mage::helper('revslider')->__('Transition duration'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'transition_duration',
		  'after_element_html' => '<small>The duration of the transition (Default:300, min: 100 max 2000).</small>',

      ));
	  
	  $fieldset->addField('delay', 'text', array(
          'label'     => Mage::helper('revslider')->__('Delay'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'delay',
		  'after_element_html' => '<small>	  A new delay value for the Slide. If no delay defined per slide, the delay defined via Options (9000ms) will be used
</small>',

      ));
	  
	  $fieldset->addField('enable_link', 'select', array(
          'label'     => Mage::helper('revslider')->__('Enable Link'),
          'name'      => 'enable_link',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('revslider')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('revslider')->__('Disabled'),
              ),
          ),
		  
      ));
	  
	  $fieldset->addField('link_type', 'radios', array(
            'label' => Mage::helper('revslider')->__('Link Type:'),
            'name' => 'link_type',
            'onclick' => "",
            'onchange' => "",
            'value' => '1',
            'values' => array(
                array('value' => 'regular', 'label' => 'Regurlar'),
                array('value' => 'slide ', 'label' => 'to Slide '),
            ),
            'readonly' => false,
            'tabindex' => 1
        ));	 
	  
	 $fieldset->addField('link', 'text', array(
          'label'     => Mage::helper('revslider')->__('Link'),
          'class'     => 'required-entry regular-text',
          'required'  => false,
          'name'      => 'link',
      ));
	  
	 
				
				
      $fieldset->addField('link_open_in', 'select', array(
          'label'     => Mage::helper('revslider')->__('Link Open In:'),
          'name'      => 'link_open_in',
          'values'    => array(
              array(
                  'value'     => 'same',
                  'label'     => Mage::helper('revslider')->__('Same Window'),
              ),

              array(
                  'value'     => 'new',
                  'label'     => Mage::helper('revslider')->__('New Window'),
              ),
          ),
      ));

	 		
      $fieldset->addField('slide_link', 'select', array(
          'label'     => Mage::helper('revslider')->__('Link To Slide:'),
          'name'      => 'slide_link',
          'values'    => array(
              array(
                  'value'     => 'nothing',
                  'label'     => Mage::helper('revslider')->__('--Not Chosen--'),
              ),

              array(
                  'value'     => 'next',
                  'label'     => Mage::helper('revslider')->__('Next Slide'),
              ),
              array(
                  'value'     => 'pre',
                  'label'     => Mage::helper('revslider')->__('Previous Slide'),
              ),
          ),
      ));
	  
	  $fieldset->addField('link_pos', 'radios', array(
            'label' => Mage::helper('revslider')->__('Link Position:'),
            'name' => 'link_pos',
            'onclick' => "",
            'onchange' => "",
            'value' => 'front',
            'values' => array(
                array('value' => 'front', 'label' => 'Front'),
                array('value' => 'back ', 'label' => 'Back '),
            ),
            'readonly' => false,
            'tabindex' => 1
        ));	
		
		  $fieldset->addField('slide_thumb', 'thumbnail', array(
			'label' => Mage::helper('revslider')->__('Thumbnail'),
			'name' => 'slide_thumb',
			'value' => 'value1'
		 ));
	
			
				
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('revslider')->__('Link To Slide:'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('revslider')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('revslider')->__('Disabled'),
              ),
          ),
      ));
  
	   if ( Mage::getSingleton('adminhtml/session')->getSlideData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getSlideData());
          Mage::getSingleton('adminhtml/session')->setSlideData(null);
      } elseif ( Mage::registry('slide_data') ) { 
	
          $form->setValues(Mage::registry('slide_data')->getData());
      }
	  
      return parent::_prepareForm();
  }
}