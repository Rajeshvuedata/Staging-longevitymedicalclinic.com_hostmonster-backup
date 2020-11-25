<?php
/**
 * MagPleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * MagPleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   MagPleasure
 * @package    Magpleasure_Blog
 * @version    1.2.3
 * @copyright  Copyright (c) 2012-2013 MagPleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Blog_Block_Adminhtml_Post_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_values;

    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    protected function _getValues()
    {
        if (Mage::getSingleton('adminhtml/session')->getPostData()) {
            $this->_values = Mage::getSingleton('adminhtml/session')->getPostData();
            Mage::getSingleton('adminhtml/session')->getPostData(null);
        } elseif (Mage::registry('current_post')) {
            $this->_values = Mage::registry('current_post')->getData();
        }

        $this->_values['current_status'] = isset($this->_values['status']) ? $this->_values['status'] : null;

        return $this->_values;
    }

    protected function _isNew()
    {
        return !(Mage::registry('current_post') && Mage::registry('current_post')->getId());
    }

    protected function _getGenerateUrlKeyButtonHtml()
    {
        /** @var $button Mage_Adminhtml_Block_Widget_Button */
        $button = $this->getLayout()->createBlock('adminhtml/widget_button');

        if ($button){
            $button->addData(array(
                'label' => $this->_helper()->__("Update"),
                'title' => $this->_helper()->__("Update"),
                'onclick' => "Transliteration.transliterate($('title').value, 'url_key'); return false;",
                'style'   => 'display: none;',
                'id'    => 'generate_permalink'

            ));
            return $button->toHtml()."
            <script type=\"text/javascript\">
            $('title').observe('blur', function(e){
                if (!$('url_key').value){
                    Transliteration.transliterate($('title').value, 'url_key');
                    $('generate_permalink').style.display = 'inline';
                }
            });
            </script>
            ";
        }
        return "";
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('blog_form', array('legend' => $this->_helper()->__('Content')));

        $fieldset->addField('title', 'text', array(
            'label' => $this->_helper()->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
            'style'  => 'width: 36em;',
        ));

        $fieldset->addField('url_key', 'text', array(
            'label' => $this->_helper()->__('URL Key'),
            'class' => 'validate-identifier',
            'required' => true,
            'name' => 'url_key',
            'style' => 'display: inline; width: 29em;',
            'note' => $this->_helper()->__('Relative to Website Base URL'),
            'after_element_html' => $this->_isNew() ? $this->_getGenerateUrlKeyButtonHtml() : "",
        ));
		/******  added custom image field *****************/
		 $fieldset->addField('postimage', 'image', array(
            'label' => $this->_helper()->__('Upload Post Image'),
            'required' => false,
            'name' => 'postimage',
        ));		
		
		/******  added custom image field *****************/
		

        /** @var Magpleasure_Blog_Model_Post $post  */
        $post = Mage::getSingleton('mpblog/post');

        $statusEnabled = Magpleasure_Blog_Model_Post::STATUS_ENABLED;
        $statusScheduled = Magpleasure_Blog_Model_Post::STATUS_SCHEDULED;
        $message = $this->_helper()->__("Post is scheduled. Do you want to publish it right now?");

        $script = "
        <script type=\"text/javascript\">

            var changePostStatus = function(el){
                var currentStatus = $('current_status').value;
                var statusEnabled = '{$statusEnabled}';
                var statusScheduled = '{$statusScheduled}';
                var newStatus = $(el).value;

                if ((currentStatus == statusScheduled) && (newStatus == statusEnabled)){
                    if (confirm('{$message}')){
                        $('published_at').value = $('current_date').value;
                    } else {
                        $(el).value = statusScheduled;
                    }
                }
            };

        </script>
        ";

        $fieldset->addField('current_status', 'hidden',
            array(
                'name'      => 'current_status',
            ));

        $fieldset->addField('status', 'select',
            array(
                'name'      => 'status',
                'label'     => $this->_helper()->__('Status'),
                'values'    => $post->toOptionArray(),
                'onchange'  => "changePostStatus(this);",
                'after_element_html' => $script,
        ));

        try{
            $fullConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
                array('tab_id' => $this->getTabId())
            );
            $fullConfig->setData($this->_helper()->recursiveReplace(
                    '/mpblog_admin/',
                    '/'.(string)Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName').'/',
                    $fullConfig->getData()
                )
            );

            $shortConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
                array('tab_id' => $this->getTabId())
            );
            $shortConfig->setData($this->_helper()->recursiveReplace(
                    '/mpblog_admin/',
                    '/'.(string)Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName').'/',
                    $fullConfig->getData()
                )
            );

        } catch (Exception $ex){
            $fullConfig = array();
            $shortConfig = array();
        }

        $fieldset->addType('mp_short_editor', 'Magpleasure_Blog_Block_Adminhtml_Widget_Form_Renderer_Editor');

        $values = $this->_getValues();
        $fieldset->addField('short_content', 'mp_short_editor', array(
            'name'      => 'short_content',
            'label'     => $this->_helper()->__('Short Content'),
            'title'     => $this->_helper()->__('Short Content'),
            'style'     => 'height:12em; width: 567px;',
            'required'  =>  false,
            'config'    => $shortConfig,
            'display_short_content' => isset($values['display_short_content']) ? $values['display_short_content'] : null,
        ));

        $plugins = $fullConfig->getPlugins();
        $plugins[] = array(
            'name' => 'blogcut',
            'src'  => Mage::getBaseUrl('js')."mpblog/tiny_mce/plugins/blogcut/editor_plugin.js",
        );
        $fullConfig->setPlugins($plugins);

        $fieldset->addField('full_content', 'editor', array(
            'name'      => 'full_content',
            'label'     => $this->_helper()->__('Content'),
            'title'     => $this->_helper()->__('Content'),
            'style'     => 'height:12em; width: 36em;',
            'required'  => true,
            'config'    => $fullConfig,
        ));

        $fieldset->addType('autocomplete', 'Magpleasure_Blog_Block_Adminhtml_Form_Element_Autocomplete');
        $fieldset->addField('tags', 'autocomplete', array(
            'label' => $this->_helper()->__('Tags'),
            'required' => false,
            'name' => 'tags',
            'style'     => 'width: 36em;',
            'note'  => $this->_helper()->__("Please enter Tags separated by comma"),
        ));

        $form->setValues($this->_getValues());
        return parent::_prepareForm();
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->_helper()->__("Content");
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->_helper()->__("Content");
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}