<?php

class Magentothem_Revslider_Block_Adminhtml_Revslider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _getHeaderTitleHtml($element)
    { die;
        return '<div class="entry-edit-head collapseable" ><a id="' . $element->getHtmlId()
            . '-head" href="#" onclick="Fieldset.toggleCollapse(\'' . $element->getHtmlId() . '\', \''
            . $this->getUrl('*/*/state') . '\'); return false;">' . $element->getLegend() . '</a></div>';
    }
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('revslider_form', array('legend'=>Mage::helper('revslider')->__('Main Slider Setting')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('revslider')->__('Slider Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
		  'after_element_html' => '<small>The title of the slider. Example: Slider1</small>',

      ));
	  
	  $fieldset->addField('alias', 'text', array(
          'label'     => Mage::helper('revslider')->__('Slider Alias:'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'alias',
		  'after_element_html' => '<small>The alias that will be used for embedding the slider. Example: slider1</small>',

      ));

	  // $fieldset->addField('store_ids', 'multiselect', array(
			// 'name' => 'store_ids[]',
			// 'label' => $this->__('Store View'),
			// 'required' => TRUE,
			// 'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(FALSE, TRUE),
		// ));
		
	  $fieldset->addField('slide_layout', 'radios', array(
            'label' => Mage::helper('revslider')->__('Slider Layout:'),
            'name' => 'slide_layout',
            'onclick' => "",
            'onchange' => "",
            'value' => '1',
            'values' => array(
                array('value' => 'fix', 'label' => 'Fixed'),
                array('value' => 'auto_res', 'label' => 'Auto Responsive'),
				array('value' => 'full', 'label' => 'Full Screen'),
            ),
            'readonly' => false,
            'tabindex' => 1
        ));	

			$fieldset->addField('fullscreen_offset_container', 'text', array(
			  'label'     => Mage::helper('revslider')->__('Offset Container:'),
			  'required'  => false,
			  'name'      => 'fullscreen_offset_container',
				'after_element_html' => '<small>Example: #header or .header, .footer, #somecontainer | The height of fullscreen slider will be decreased with the height of these Containers to fit perfect in the screen</small>',

		  ));	
		   $fieldset->addField('fullscreen_min_height', 'text', array(
			  'label'     => Mage::helper('revslider')->__('Min. Fullscreen Height:'),
			  'required'  => false,
			  'name'      => 'fullscreen_min_height',
		  ));
		
		  $fieldset->addField('full_screen', 'radios', array(
            'label' => Mage::helper('revslider')->__('Full Screen Align:'),
            'name' => 'full_screen',
            'onclick' => "",
            'onchange' => "",
            'value' => '1',
            'values' => array(
                array('value' => 'on', 'label' => 'On'),
                array('value' => 'off', 'label' => 'Off'),
            ),
            'readonly' => false,
            'tabindex' => 1
        ));	
		
		  $fieldset->addField('un_height', 'radios', array(
            'label' => Mage::helper('revslider')->__('Unlimited Height:'),
            'name' => 'un_height',
            'onclick' => "",
            'onchange' => "",
            'value' => '1',
            'values' => array(
                array('value' => 'on', 'label' => 'On'),
                array('value' => 'off', 'label' => 'Off'),
            ),
            'readonly' => false,
            'tabindex' => 1
        ));	
        
		 $fieldset->addField('full_width', 'radios', array(
					'label' => Mage::helper('revslider')->__('Force Full Width:'),
					'name' => 'full_width',
					'onclick' => "",
					'onchange' => "",
					'value' => '1',
					'values' => array(
						array('value' => 'on', 'label' => 'On'),
						array('value' => 'off', 'label' => 'Off'),
					),
					'readonly' => false,
					'tabindex' => 1
				));	
				//Grid Setting
				$fieldset = $form->addFieldset('revslider_form_size', array('legend'=>Mage::helper('revslider')->__('Grid Settings')));
					$fieldset->addField('grid_width', 'text', array(
					'label'     => Mage::helper('revslider')->__('Grid Width:'),
					'class'     => 'required-entry',
					'required'  => false,
					'name'      => 'grid_width',
				));
				
				$fieldset->addField('grid_height', 'text', array(
					'label'     => Mage::helper('revslider')->__('Grid Height:'),
					'class'     => 'required-entry',
					'required'  => false,
					'name'      => 'grid_height',
				));			
				//General
				$fieldset = $form->addFieldset('revslider_form_general', array('legend'=>Mage::helper('revslider')->__('General Settings')));
								
				$fieldset->addField('delay', 'text', array(
					'label'     => Mage::helper('revslider')->__('Delay:'),
					'class'     => 'required-entry',
					'required'  => false,
					'name'      => 'delay',
				));					
				$fieldset->addField('shuffle_mode', 'radios', array(
					'label' => Mage::helper('revslider')->__('Shuffle Mode:'),
					'name' => 'shuffle_mode',
					'onclick' => "",
					'onchange' => "",
					'value' => '1',
					'values' => array(
						array('value' => 'on', 'label' => 'On'),
						array('value' => 'off', 'label' => 'Off'),
					),
					'readonly' => false,
					'tabindex' => 1
				));	

				// $fieldset->addField('lazy_load', 'radios', array(
					// 'label' => Mage::helper('revslider')->__('Lazy Load:'),
					// 'name' => 'lazy_load',
					// 'onclick' => "",
					// 'onchange' => "",
					// 'value' => '1',
					// 'values' => array(
						// array('value' => 'on', 'label' => 'On'),
						// array('value' => 'off', 'label' => 'Off'),
					// ),
					// 'readonly' => false,
					// 'tabindex' => 1
				// ));	
				$fieldset->addField('stop_slider', 'radios', array(
					'label' => Mage::helper('revslider')->__('Stop Slider:'),
					'name' => 'stop_slider',
					'onclick' => "",
					'onchange' => "",
					'value' => '1',
					'values' => array(
						array('value' => 'on', 'label' => 'On'),
						array('value' => 'off', 'label' => 'Off'),
					),
					'readonly' => false,
					'tabindex' => 1
				));	

				$fieldset->addField('after_loop', 'text', array(
					'label'     => Mage::helper('revslider')->__('Stop After Loops:'),
					'class'     => 'required-entry',
					'required'  => false,
					'name'      => 'after_loop',
				));			
				$fieldset->addField('stop_at', 'text', array(
					'label'     => Mage::helper('revslider')->__('Stop At Slide:'),
					'class'     => 'required-entry',
					'required'  => false,
					'name'      => 'stop_at',
				));			
				$fieldset = $form->addFieldset('revslider_form_position', array('legend'=>Mage::helper('revslider')->__('Position Settings')));
			
				   $fieldset->addField('position_on', 'select', array(
							'label' => Mage::helper('revslider')->__('Position on the page'),
							'name' => 'position_on',
							'values' => array(
								array(
									'value' => 'banner-top',
									'label' => Mage::helper('revslider')->__('Banner Top'),
								),
								array(
									'value' => 'menu-top',
									'label' => Mage::helper('revslider')->__('Menu Top'),
								),
								array(
									'value' => 'page-bottom',
									'label' => Mage::helper('revslider')->__('Page Bottom'),
								),
								array(
									'value' => 'sidebar-left-top',
									'label' => $this->__('Sidebar left top')
								),
								array(
									'value' => 'sidebar-left-bottom',
									'label' => $this->__('Sidebar left bottom')
								),
								array(
									'value' => 'sidebar-right-top',
									'label' => $this->__('Sidebar right top')
								),
								array(
									'value' => 'sidebar-right-bottom',
									'label' => $this->__('Sidebar right bottom')
								),
							),
						));
				
				$fieldset->addField('margin_top', 'text', array(
					'label' => Mage::helper('revslider')->__('Margin Top:'),
					'required' => false,
					'name' => 'margin_top',
				));

				$fieldset->addField('margin_bottom', 'text', array(
					'label' => Mage::helper('revslider')->__('Margin Bottom:'),
					'required' => false,
					'name' => 'margin_bottom',
				));


				$fieldset->addField('margin_left', 'text', array(
					'label' => Mage::helper('revslider')->__('Margin Left:'),
					'required' => false,
					'name' => 'margin_left',
				));

				$fieldset->addField('margin_right', 'text', array(
					'label' => Mage::helper('revslider')->__('Margin Right:'),
					'required' => false,
					'name' => 'margin_right',
				));
				
			$fieldset = $form->addFieldset('revslider_form_appearance', array('legend'=>Mage::helper('revslider')->__('Appearance Settings')));
			$fieldset->addField('shadow_type', 'select', array(
				'label' => Mage::helper('revslider')->__('Shadow Type'),
				'name' => 'shadow_type',
				'values' => array(
					array(
						'value' => 0,
						'label' => Mage::helper('revslider')->__('No Shadow'),
					),
					array(
						'value' => 1,
						'label' => Mage::helper('revslider')->__('1'),
					),
					array(
						'value' => 2,
						'label' => Mage::helper('revslider')->__('2'),
					),
					array(
						'value' => 3,
						'label' => Mage::helper('revslider')->__('3'),
					),
					),
			));
			$fieldset->addField('timer_on', 'select', array(
				'label' => Mage::helper('revslider')->__('Show Timer Line:'),
				'name' => 'timer_on',
				'values' => array(
					array(
						'value' => 'top',
						'label' => Mage::helper('revslider')->__('Top'),
					),
					array(
						'value' => 'bottom',
						'label' => Mage::helper('revslider')->__('Bottom'),
					),
					array(
						'value' => 'hide',
						'label' => Mage::helper('revslider')->__('Hide'),
					),
					),
			));
			
				$fieldset->addField('padding_border', 'text', array(
					'label' => Mage::helper('revslider')->__('Padding(border):'),
					'required' => false,
					'name' => 'padding_border',
				));
				$jsUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS);
				$fieldset->addField('bg_color', 'text', array(
					'label' => Mage::helper('revslider')->__('Background color:'),
					'required' => false,
					'name' => 'bg_color',
					'after_element_html' => '<input type="hidden" id="js_url" value="'.$jsUrl.'"/>',

				));
				
				
				$fieldset->addField('dotted_overlay', 'select', array(
					'label' => Mage::helper('revslider')->__('Dotted Overlay Size:'),
					'name' => 'dotted_overlay',
					'values' => array(
						array(
							'value' => 'none',
							'label' => Mage::helper('revslider')->__('None'),
						),
						array(
							'value' => 'twoxtwo',
							'label' => Mage::helper('revslider')->__('2 x 2 Black'),
						),
						array(
							'value' => 'twoxtwowhite',
							'label' => Mage::helper('revslider')->__('2 x 2 White'),
						),
						array(
							'value' => 'threexthree',
							'label' => Mage::helper('revslider')->__('3 x 3 Black'),
						),
						array(
							'value' => 'threexthreewhite',
							'label' => Mage::helper('revslider')->__('3 x 3 White'),
						),
						),
				));
				
				$fieldset->addField('bg_image', 'radios', array(
					'label' => Mage::helper('revslider')->__('Show Background Image:'),
					'name' => 'bg_image',
					'onclick' => "",
					'onchange' => "",
					'value' => '1',
					'values' => array(
						array('value' => 'true', 'label' => 'Yes'),
						array('value' => 'false', 'label' => 'No'),
					),
					'readonly' => false,
					'tabindex' => 1
				));	
				
				$fieldset->addField('bg_url', 'text', array(
					'label' => Mage::helper('revslider')->__('Background Image Url:'),
					'required' => false,
					'name' => 'bg_url',
				));
				
			$fieldset->addField('bg_fit', 'select', array(
				'label' => Mage::helper('revslider')->__('Background Fit:'),
				'name' => 'bg_fit',
				'values' => array(
					array(
						'value' => 'cover',
						'label' => Mage::helper('revslider')->__('Cover'),
					),
					array(
						'value' => 'contain',
						'label' => Mage::helper('revslider')->__('Contain'),
					),
					array(
						'value' => 'normal',
						'label' => Mage::helper('revslider')->__('Normal'),
					),
					),
			));
		
				$fieldset->addField('bg_repeat', 'select', array(
				'label' => Mage::helper('revslider')->__('Background Repeat:'),
				'name' => 'bg_repeat',
				'values' => array(
					array(
						'value' => 'no-repeat',
						'label' => Mage::helper('revslider')->__('No repeat'),
					),
					array(
						'value' => 'repeat',
						'label' => Mage::helper('revslider')->__('repeat'),
					),
					array(
						'value' => 'repeat-x',
						'label' => Mage::helper('revslider')->__('Repeat x'),
					),
					array(
						'value' => 'repeat-y',
						'label' => Mage::helper('revslider')->__('Repeat Y'),
					),
					),
			));
				$fieldset->addField('bg_position', 'select', array(
				'label' => Mage::helper('revslider')->__('Background Position:'),
				'name' => 'bg_position',
				'values' => array(
					array(
						'value' => 'center-top',
						'label' => Mage::helper('revslider')->__('Center top'),
					),
					array(
						'value' => 'center-right',
						'label' => Mage::helper('revslider')->__('Center right'),
					),
					array(
						'value' => 'center-bottom',
						'label' => Mage::helper('revslider')->__('Center bottom'),
					),
					array(
						'value' => 'center-center',
						'label' => Mage::helper('revslider')->__('Center center'),
					),
					array(
						'value' => 'left-top',
						'label' => Mage::helper('revslider')->__('Left top'),
					),
					array(
						'value' => 'left-bottom',
						'label' => Mage::helper('revslider')->__('Left bottom'),
					),
					array(
						'value' => 'left-center',
						'label' => Mage::helper('revslider')->__('Left center'),
					),
										array(
						'value' => 'right-top',
						'label' => Mage::helper('revslider')->__('Right top'),
					),
					array(
						'value' => 'right-bottom',
						'label' => Mage::helper('revslider')->__('Right bottom'),
					),
					array(
						'value' => 'right-center',
						'label' => Mage::helper('revslider')->__('Right center'),
					),


					
					),
			));
		  //Navigation
		  $fieldset = $form->addFieldset('revslider_form_navigation', array('legend'=>Mage::helper('revslider')->__('Navigation Settings')));
			$fieldset->addField('touch_enable', 'radios', array(
				'label' => Mage::helper('revslider')->__('Touch Enable:'),
				'name' => 'touch_enable',
				'onclick' => "",
				'onchange' => "",
				'value' => '1',
				'values' => array(
					array('value' => 'on', 'label' => 'On'),
					array('value' => 'off', 'label' => 'Off'),
				),
				'readonly' => false,
				'tabindex' => 1
			));	
				$fieldset->addField('on_hover', 'radios', array(
				'label' => Mage::helper('revslider')->__('Stop On Hover:'),
				'name' => 'on_hover',
				'onclick' => "",
				'onchange' => "",
				'value' => '1',
				'values' => array(
					array('value' => 'on', 'label' => 'On'),
					array('value' => 'off', 'label' => 'Off'),
				),
				'readonly' => false,
				'tabindex' => 1
			));	
			
				
			$fieldset->addField('navigation_type', 'select', array(
				'label' => Mage::helper('revslider')->__('Navigation Type:'),
				'name' => 'navigation_type',
				'values' => array(
					array(
						'value' => 'none',
						'label' => Mage::helper('revslider')->__('None'),
					),
					array(
						'value' => 'bullet',
						'label' => Mage::helper('revslider')->__('Bullet'),
					),
					array(
						'value' => 'thumb',
						'label' => Mage::helper('revslider')->__('thumb'),
					),
					),
			));
			$fieldset->addField('navigation_arrow', 'select', array(
				'label' => Mage::helper('revslider')->__('Navigation Arrow:'),
				'name' => 'navigation_arrow',
				'values' => array(
					array(
						'value' => 'nexttobullets',
						'label' => Mage::helper('revslider')->__('With Bullets'),
					),
					array(
						'value' => 'solo',
						'label' => Mage::helper('revslider')->__('Solo'),
					),
					array(
						'value' => 'none',
						'label' => Mage::helper('revslider')->__('None'),
					),
					),
			));
			$fieldset->addField('navigation_style', 'select', array(
				'label' => Mage::helper('revslider')->__('Navigation Style:'),
				'name' => 'navigation_style',
				'values' => array(
					array(
						'value' => 'round',
						'label' => Mage::helper('revslider')->__('Round'),
					),
					array(
						'value' => 'navbar',
						'label' => Mage::helper('revslider')->__('Navbar'),
					),
					array(
						'value' => 'round-old',
						'label' => Mage::helper('revslider')->__('Old Round'),
					),
					array(
						'value' => 'square-old',
						'label' => Mage::helper('revslider')->__('Old Square'),
					),
					array(
						'value' => 'navbar-old',
						'label' => Mage::helper('revslider')->__('Old Navbar'),
					),
					),
			));
			
			$fieldset->addField('show_nav', 'select', array(
				'label' => Mage::helper('revslider')->__('Allway Show Navigation:'),
				'name' => 'show_nav',
				'values' => array(
					array(
						'value' => 'true',
						'label' => Mage::helper('revslider')->__('Yes'),
					),
					array(
						'value' => 'false',
						'label' => Mage::helper('revslider')->__('No'),
					),
					),
			));

			$fieldset->addField('nav_after', 'text', array(
				'label' => Mage::helper('revslider')->__('Hide Navigation After:'),
				'required' => false,
				'name' => 'nav_after',
			));
			
			$fieldset->addField('nav_align_hor', 'select', array(
				'label' => Mage::helper('revslider')->__('Navigation Horizontal Align :'),
				'name' => 'nav_align_hor',
				'values' => array(
					array(
						'value' => 'left',
						'label' => Mage::helper('revslider')->__('Left'),
					),
					array(
						'value' => 'center',
						'label' => Mage::helper('revslider')->__('Center'),
					),
					array(
						'value' => 'right',
						'label' => Mage::helper('revslider')->__('Right'),
					),
					),
			));
			

			$fieldset->addField('nav_align_vert', 'select', array(
				'label' => Mage::helper('revslider')->__('Navigation Vertical Align  :'),
				'name' => 'nav_align_vert',
				'values' => array(
					array(
						'value' => 'top',
						'label' => Mage::helper('revslider')->__('Top'),
					),
					array(
						'value' => 'center',
						'label' => Mage::helper('revslider')->__('Center'),
					),
					array(
						'value' => 'bottom',
						'label' => Mage::helper('revslider')->__('Bottom'),
					),
					),
			));
			$fieldset->addField('nav_offset_hor', 'text', array(
				'label' => Mage::helper('revslider')->__('Navigation Horizontal Offset :'),
				'required' => false,
				'name' => 'nav_offset_hor',
			));
			$fieldset->addField('nav_offset_vert', 'text', array(
				'label' => Mage::helper('revslider')->__('Navigation Vertical Offset :'),
				'required' => false,
				'name' => 'nav_offset_vert',
			));
			

			$fieldset->addField('leftarrow_align_hor', 'select', array(
				'label' => Mage::helper('revslider')->__('Left Arrow Horizontal Align :'),
				'name' => 'leftarrow_align_hor',
				'values' => array(
					array(
						'value' => 'left',
						'label' => Mage::helper('revslider')->__('Left'),
					),
					array(
						'value' => 'center',
						'label' => Mage::helper('revslider')->__('Center'),
					),
					array(
						'value' => 'right',
						'label' => Mage::helper('revslider')->__('Right'),
					),
					),
			));
			
			$fieldset->addField('leftarrow_align_vert', 'select', array(
				'label' => Mage::helper('revslider')->__('Left Arrow Vertical Align  :'),
				'name' => 'leftarrow_align_vert',
				'values' => array(
					array(
						'value' => 'top',
						'label' => Mage::helper('revslider')->__('Top'),
					),
					array(
						'value' => 'center',
						'label' => Mage::helper('revslider')->__('Center'),
					),
					array(
						'value' => 'bottom',
						'label' => Mage::helper('revslider')->__('Bottom'),
					),
					),
			));
			$fieldset->addField('leftarrow_offset_hor', 'text', array(
				'label' => Mage::helper('revslider')->__('Left Arrow Horizontal Offset :'),
				'required' => false,
				'name' => 'leftarrow_offset_hor',
			));
			$fieldset->addField('leftarrow_offset_vert', 'text', array(
				'label' => Mage::helper('revslider')->__('Left Arrow Vertical Offset :'),
				'required' => false,
				'name' => 'leftarrow_offset_vert',
			));	
			
			$fieldset->addField('rightarrow_align_hor', 'select', array(
				'label' => Mage::helper('revslider')->__('Right Arrow Horizontal Align :'),
				'name' => 'rightarrow_align_hor',
				'values' => array(
					array(
						'value' => 'left',
						'label' => Mage::helper('revslider')->__('Left'),
					),
					array(
						'value' => 'center',
						'label' => Mage::helper('revslider')->__('Center'),
					),
					array(
						'value' => 'right',
						'label' => Mage::helper('revslider')->__('Right'),
					),
					),
			));
			
			$fieldset->addField('rightarrow_align_vert', 'select', array(
				'label' => Mage::helper('revslider')->__('Right Arrow Vertical Align  :'),
				'name' => 'rightarrow_align_vert',
				'values' => array(
					array(
						'value' => 'top',
						'label' => Mage::helper('revslider')->__('Top'),
					),
					array(
						'value' => 'center',
						'label' => Mage::helper('revslider')->__('Center'),
					),
					array(
						'value' => 'bottom',
						'label' => Mage::helper('revslider')->__('Bottom'),
					),
					),
			));
			$fieldset->addField('rightarrow_offset_hor', 'text', array(
				'label' => Mage::helper('revslider')->__('Right Arrow Horizontal Offset :'),
				'required' => false,
				'name' => 'rightarrow_offset_hor',
			));
			$fieldset->addField('rightarrow_offset_vert', 'text', array(
				'label' => Mage::helper('revslider')->__('Right Arrow Vertical Offset :'),
				'required' => false,
				'name' => 'rightarrow_offset_vert',
			));	
			
			$fieldset = $form->addFieldset('revslider_form_Thumbnail', array('legend'=>Mage::helper('revslider')->__('Thumbnail Settings')));
			$fieldset->addField('thumb_width', 'text', array(
				'label' => Mage::helper('revslider')->__('Thumb width:'),
				'required' => false,
				'name' => 'thumb_width',
			));	
			$fieldset->addField('thumb_hight', 'text', array(
				'label' => Mage::helper('revslider')->__('Thumb hight :'),
				'required' => false,
				'name' => 'thumb_hight',
			));	
			$fieldset->addField('thumb_amount', 'text', array(
				'label' => Mage::helper('revslider')->__('Thumb Amount :'),
				'required' => false,
				'name' => 'thumb_amount',
			));				
				
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('revslider')->__('Status'),
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
	  
	  
	    $fieldset = $form->addFieldset('revslider_form_firstslide', array('legend' => Mage::helper('revslider')->__('Firs slide information')));


        $fieldset->addField('start_with_slide', 'text', array(
            'label' => Mage::helper('revslider')->__('Start With Slide:'),
            'required' => false,
            'name' => 'start_with_slide',
        ));

        $fieldset->addField('first_tran_active', 'radios', array(
            'label' => Mage::helper('revslider')->__('First Transition Active:'),
            'name' => 'first_tran_active',
            'onclick' => "",
            'onchange' => "",
            'value' => '1',
            'values' => array(
                array('value' => 'on', 'label' => 'On'),
                array('value' => 'off ', 'label' => 'Off'),
            ),
            'readonly' => false,
            'tabindex' => 1
        ));

        $fieldset->addField('first_tran_type', 'select', array(
            'label' => Mage::helper('revslider')->__('First Transition Type'),
            'name' => 'first_tran_type',
            'values' => array(
                array(
                    'value' => 'fade',
                    'label' => Mage::helper('revslider')->__('Fade'),
                ),
                array(
                    'value' => 'slidehorizontal',
                    'label' => Mage::helper('revslider')->__('Slide Horizontal'),
                ),
                array(
                    'value' => 'slidevertical',
                    'label' => Mage::helper('revslider')->__('Slide Vertical'),
                ),
                array(
                    'value' => 'boxslide',
                    'label' => Mage::helper('revslider')->__('Box Slide'),
                ),
                array(
                    'value' => 'boxfade',
                    'label' => Mage::helper('revslider')->__('Box Fade'),
                ),
                array(
                    'value' => 'slotzoom-horizontal',
                    'label' => Mage::helper('revslider')->__('SlotZoom Horizontal'),
                ),
                array(
                    'value' => 'slotslide-horizontal',
                    'label' => Mage::helper('revslider')->__('SlotSlide Horizontal'),
                ),
                array(
                    'value' => 'slotfade-horizontal',
                    'label' => Mage::helper('revslider')->__('SlotFade Horizontal'),
                ),
                array(
                    'value' => 'slotzoom-vertical',
                    'label' => Mage::helper('revslider')->__('SlotZoom Vertical'),
                ),
                array(
                    'value' => 'slotslide-vertical',
                    'label' => 'SlotSlide Vertical'
                ),
                array(
                    'value' => 'slotfade-vertical',
                    'label' => 'SlotFade Vertical'
                ),
                array(
                    'value' => 'curtain-1',
                    'label' => 'Curtain 1'
                ),
                array(
                    'value' => 'curtain-2',
                    'label' => 'Curtain 2'
                ), 
                array(
                    'value' => 'curtain-3',
                    'label' => 'Curtain 3'
                ),
                array(
                    'value' => 'slideleft',
                    'label' => 'Slide Left'
                ),
                array(
                    'value' => 'slideright',
                    'label' => 'Slide Right'
                ),
                array(
                    'value' => 'slideup',
                    'label' => 'Slide Up'
                ), 
                array(
                    'value' => 'slidedown',
                    'label' => 'Slide Down'
                ), 
                array(
                    'value' => 'papercut',
                    'label' => 'Premium - Paper Cut'
                ),
                array(
                    'value' => '3dcurtain-horizontal',
                    'label' => 'Premium - 3D Curtain Horizontal'
                ),
                array(
                    'value' => '3dcurtain-vertical',
                    'label' => 'Premium - 3D Curtain Vertical'
                ),
                array(
                    'value' => 'flyin',
                    'label' => 'Premium - Fly In'
                ), 
                array(
                    'value' => 'turnoff',
                    'label' => 'Premium - Turn Off'
                ), 
                array(
                    'value' => 'cubic',
                    'label' => 'Premium - Cubic'
                )
            ),
        ));
		
		 $fieldset->addField('first_tran_du', 'text', array(
            'label' => Mage::helper('revslider')->__('First Transition Duration:'),
            'required' => false,
            'name' => 'first_tran_du',
        ));


        $fieldset->addField('first_tran_slot_amoun', 'text', array(
            'label' => Mage::helper('revslider')->__('First Transition Slot Amoun:'),
            'required' => false,
            'name' => 'first_tran_slot_amoun',
        ));

		
	   $fieldset = $form->addFieldset('revslider_form_mobile', array('legend' => Mage::helper('revslider')->__('Mobile information')));
       
        $fieldset->addField('hide_slider_under', 'text', array(
            'label' => Mage::helper('revslider')->__('Hide Slider Under Width:'),
            'required' => false,
            'name' => 'hide_slider_under',
            'after_element_html' => '<small>px</small>',
            'style' => 'width:50px;'
        ));


        $fieldset->addField('hide_defined_layers_under', 'text', array(
            'label' => Mage::helper('revslider')->__('Hide Defined Layers Under Width:'),
            'required' => false,
            'name' => 'hide_defined_layers_under',
            'after_element_html' => '<small>px</small>',
            'style' => 'width:50px;'
        ));

        $fieldset->addField('hide_all_layers_under', 'text', array(
            'label' => Mage::helper('revslider')->__('Hide All Layers Under Width:'),
            'required' => false,
            'name' => 'hide_all_layers_under',
            'after_element_html' => '<small>px</small>',
             'style' => 'width:50px;'
        ));
		
		 $fieldset->addField('hide_arrows_on_mobile', 'radios', array(
            'label' => Mage::helper('revslider')->__('Hide Arrows on Mobile:'),
            'name' => 'hide_arrows_on_mobile',
            'onclick' => "",
            'onchange' => "",
            'value' => '1',
            'values' => array(
                array('value' => 'on', 'label' => 'On'),
                array('value' => 'off', 'label' => 'Off'),
            ),
            'readonly' => false,
            'tabindex' => 1
        ));	
		 $fieldset->addField('hide_bullets_on_mobile', 'radios', array(
            'label' => Mage::helper('revslider')->__('Hide Bullets on Mobile:'),
            'name' => 'hide_bullets_on_mobile',
            'onclick' => "",
            'onchange' => "",
            'value' => '1',
            'values' => array(
                array('value' => 'on', 'label' => 'On'),
                array('value' => 'off', 'label' => 'Off'),
            ),
            'readonly' => false,
            'tabindex' => 1
        ));	
		 $fieldset->addField('hide_thumbs_on_mobile', 'radios', array(
            'label' => Mage::helper('revslider')->__('Hide Thumbnails on Mobile:'),
            'name' => 'hide_thumbs_on_mobile',
            'onclick' => "",
            'onchange' => "",
            'value' => '1',
            'values' => array(
                array('value' => 'on', 'label' => 'On'),
                array('value' => 'off', 'label' => 'Off'),
            ),
            'readonly' => false,
            'tabindex' => 1
        ));	
		
		 $fieldset->addField('hide_thumbs_under_resolution', 'text', array(
            'label' => Mage::helper('revslider')->__('Hide Thumbs Under Width:'),
            'required' => false,
            'name' => 'hide_thumbs_under_resolution',
            'after_element_html' => '<small>px</small>',
            'style' => 'width:50px;'
        ));

       
  
	   if ( Mage::getSingleton('adminhtml/session')->getRevsliderData() )
      {
	  
          $form->setValues(Mage::getSingleton('adminhtml/session')->getRevsliderData());
          Mage::getSingleton('adminhtml/session')->setRevsliderData(null);
      } elseif ( Mage::registry('revslider_data') ) { 
	
          $form->setValues(Mage::registry('revslider_data')->getData());
      }
	   if(!$this->getRequest()->getParam('id')){
			$data['slide_layout'] = 'full';
			$data['grid_width'] = 1170;
			$data['grid_height'] = 350;
			
			$data['delay'] = 3000;
			$data['after_loop'] = 5;
			$data['stop_at'] = 5;
			$data['margin_top'] = 0;
			$data['margin_bottom'] = 0;
			$data['margin_left'] = 0;
			
			 $data['margin_right'] = 0;
			// $data['lazy_load'] = 'off';
			 $data['bg_color'] = '#FF346D';
			 $data['touch_enable'] = 'on';
			 $data['on_hover'] = 'on';
			 $data['thumb_width'] = 100;
			 $data['thumb_hight'] = 100;
			 $data['thumb_amount'] = 5;
			 $data['start_with_slide'] = 0; 
			 $data['first_tran_du'] = 1000;
			 $data['first_tran_slot_amoun'] = 5;
			 $data['nav_after'] = 0;
			 $data['nav_offset_hor'] = 0;
			 $data['nav_offset_vert'] = 0;
			 $data['leftarrow_offset_hor'] = 0;
			 $data['leftarrow_offset_vert'] = 0;
			 $data['rightarrow_offset_hor'] = 0;
			 $data['rightarrow_offset_vert'] = 0;
			 $data['hide_slider_under'] = 200;
			 $data['hide_defined_layers_under'] = 200;
			 $data['hide_all_layers_under'] = 200;
			 $data['hide_thumbs_under_resolution'] = 200;
		  $form->setValues($data);
		}
      return parent::_prepareForm();
  }
}