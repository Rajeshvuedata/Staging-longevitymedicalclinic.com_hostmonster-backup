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

class Magpleasure_Blog_Block_Adminhtml_Post_Edit_Tab_Additional
    extends Magpleasure_Blog_Block_Adminhtml_Filterable_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_values = array();

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
        if (!$this->_values){

            if (Mage::getSingleton('adminhtml/session')->getPostData()) {
                $this->_values = Mage::getSingleton('adminhtml/session')->getPostData();
                Mage::getSingleton('adminhtml/session')->getPostData(null);
            } elseif (Mage::registry('current_post')) {
                $this->_values = Mage::registry('current_post')->getData();
            }

            # Correct timezone for Published At
            $publishedAt = isset($this->_values['published_at']) ? $this->_values['published_at'] : null;
            if ($publishedAt){

                try {
                    $publishedAt = new Zend_Date($publishedAt, Zend_Date::ISO_8601);
                } catch (Exception $e){
                    $publishedAt = new Zend_Date($publishedAt, Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
                }

                $publishedAt->subSecond($this->_helper()->getTimezoneOffset());
                $this->_values['published_at'] = $publishedAt->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
            }

            $currentDate = new Zend_Date();
            $currentDate->subSecond($this->_helper()->getTimezoneOffset());
            $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
            $this->_values['current_date'] = $currentDate->toString($format);

            /** @var Magpleasure_Blog_Model_Author $author */
            $author = Mage::getModel('mpblog/author');

            if (!isset($this->_values['posted_by'])){
                $this->_values['posted_by'] = $author->getDefaultName();
            }

            if (!isset($this->_values['google_profile'])){
                $this->_values['google_profile'] = $author->getDefaultGoogleProfile();
            }

            if (!Mage::app()->isSingleStoreMode() && $this->isStoreFilterApplied()){
                $this->_values['stores'] = $this->getAppliedStoreId();
            } elseif (Mage::app()->isSingleStoreMode()) {
                $this->_values['stores'] = Mage::app()->getDefaultStoreView()->getId();
            }

        }
        return $this->_values;
    }


    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('blog_form_author', array('legend' => $this->_helper()->__('Author')));

        $fieldset->addField('posted_by', 'text', array(
            'label' => $this->_helper()->__("Name"),
            'required' => false,
            'name' => 'posted_by',
        ));


        $example = "https://plus.google.com/109412257237874861202";
        $url = "https://support.google.com/webmasters/answer/2539557?hl=en";

        $fieldset->addField('google_profile', 'text', array(
            'label' => $this->_helper()->__("Google Profile"),
            'required' => false,
            'name' => 'google_profile',
            'note'      => $this->_helper()->__("Example: <i>%s</i><br/>Read more here - <a href='%s' target='_blank'>Using rel=author</a>.", $example, $url),
        ));


        $fieldset = $form->addFieldset('blog_form', array('legend' => $this->_helper()->__('Additional')));

        $values = new Varien_Object($this->_getValues());
        $_isNew = !$values->getCreatedAt();

        $fieldset->addField('user_define_publish', 'hidden', array(
            'name'  => 'user_define_publish',
            'default' => '0',
        ));

        $imagePath = $this->getSkinUrl('images/grid-cal.gif');
        $showLabel = $this->_helper()->__("Define Publish Time");
        $cancelLabel = $this->_helper()->__("Publish Right Now");

        $showStyle = $values->getUserDefinePublish() ? 'display: none;' : 'display: inline;';
        $cancelStyle = $values->getUserDefinePublish() ? 'display: inline;' : 'display: none;';

        $html = "
        <a href=\"#\" onclick=\"setPostDateTime(); return false;\" id=\"published_at_show_button\" style=\"{$showStyle}\">{$showLabel}</a>
        <a href=\"#\" onclick=\"cancelPostDateTime(); return false;\" id=\"published_at_cancel_button\" style=\"{$cancelStyle}\">{$cancelLabel}</a>
        <script type=\"text/javascript\">

            $('published_at_trig').style.display = 'none';
            $('published_at_trig').src = '{$imagePath}';

            var setPostDateTime = function(){

                Effect.Appear('published_at_trig', {duration: 0.5});
                Effect.Appear('published_at', {duration: 0.5, afterFinish: function(e){
                    $('published_at_show_button').style.display = 'none';
                    $('published_at_cancel_button').style.display = 'inline';
                    $('user_define_publish').value = '1';
                }});
            };

            var cancelPostDateTime = function(){

                Effect.Fade('published_at_trig', {duration: 0.3});
                Effect.Fade('published_at', {duration: 0.3, afterFinish: function(e){
                    $('published_at_show_button').style.display = 'inline';
                    $('published_at_cancel_button').style.display = 'none';
                    $('user_define_publish').value = '0';
                }});

            };
        </script>
        ";

        $outputFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('current_date', 'hidden', array(
            'name'      => 'current_date',
            'time'      => true,
        ));

        $fieldset->addField('published_at', 'date', array(
            'label'  => $this->_helper()->__('Publish Date'),
            'title'  => $this->_helper()->__('Publish Date'),
            'name'      => 'published_at',
            'time'      => true,
            'style'     => !$_isNew ? 'width: 110px !important; display: inline;' : 'width: 110px !important; display: none;',
            'image'     => !$_isNew ? $imagePath : null,
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format'       => $outputFormat,
            'after_element_html' => $_isNew ? $html : '',
        ));

        $fieldset->addField('notify_on_enable', 'checkbox', array(
            'label'     => $this->_helper()->__("Send Notification On Enabling"),
            'required'  => false,
            'name'      => 'notify_on_enable',
            'checked'   => $values->getNotifyOnEnable(),
        ));


		/* ********* Added Custom Featured Post Field  
		$fieldset->addField('featuredpost', 'checkbox', array(
            'label'     => $this->_helper()->__("Featured Post"),
            'required'  => false,
            'name'      => 'featuredpost',
            'checked'   => $values->getFeaturedpost(),
        ));
		
		*/
		$selectField = $fieldset->addField('featuredpost', 'select', array(
            'label' => 'Featured Post',
            'name' => 'featuredpost',           
            'values' => array(
                array(
                    'value' => 1,
                    'label' => 'Yes',
                ),
                array(
                    'value' => 0,
                    'label' => 'NO',
                ),
            ),
            'onChange'  => 'showHideField()',
        ));
		
		$selectField->setAfterElementHtml('
                        <script>
                        function showHideField() {                                              
                            $("field_to_hide").toggle()
                        }
                        </script>
                    ');
		/* ********* Added Custom Featured Post Field  */
		
        /** @var Mage_Adminhtml_Model_System_Store $systemStore */
        $systemStore = Mage::getSingleton('adminhtml/system_store');

        $stores = false;

        if (!Mage::app()->isSingleStoreMode()){
            if ($this->isStoreFilterApplied()){
                $stores = array($this->getAppliedStoreId());
            } else {
                $stores = $this->_helper()->getCommon()->getStore()->getFrontendStoreIds();
            }
        }

        /** @var Magpleasure_Blog_Model_Category $category  */
        $category= Mage::getModel('mpblog/category');



        $categoryField = $fieldset->addField('categories', 'multiselect',
            array(
                'label'     => $this->_helper()->__('Posted in'),
                'name'      => 'categories[]',
                'values'    => $category->getCategoryList($stores),
            ));


        # Hint to create category if no one exists
        if (!count($category->getCategoryList($stores))){
            $params = $this->_getCommonParams();
            $params['back_to'] = $this->_helper()->getCommon()->getCore()->urlEncode(
                $this->_helper()->getCommon()->getRequest()->getCurrentManegtoUrl()
            );

            $categoryCreateUrl = $this->getUrl('mpblog_admin/adminhtml_category/new', $params);
            $categoryCreateLabel = $this->_helper()->__("Create New");
            $categoryLink = '[<a href="'.$categoryCreateUrl.'" target="_blank">'.$categoryCreateLabel.'</a>]';

            $categoryField->setData('note', $this->_helper()->__("You have no one category")."&nbsp;".$categoryLink);
        }


        if (!Mage::app()->isSingleStoreMode()){
            if ($this->isStoreFilterApplied()){
                $fieldset->addField('stores', 'hidden',
                    array(
                        'name' => 'stores[]',
                    ));

            } else {

                $fieldset->addField('stores', 'multiselect',
                    array(
                        'label'     => $this->_helper()->__('Visible in'),
                        'required'  => true,
                        'name'      => 'stores[]',
                        'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm()
                    ));
            }
        } else {
            $fieldset->addField('stores', 'hidden',
                array(
                    'name' => 'stores[]',
                )
            );
        }

        $fieldset->addField('comments_enabled', 'checkbox', array(
            'label'     => $this->_helper()->__("Allow Comments"),
            'required'  => false,
            'name'      => 'comments_enabled',
            'checked'   => $values->getPostId() ? $values->getCommentsEnabled() : true,
        ));

        $fieldset->addField('views', 'text', array(
            'label' => $this->_helper()->__('Number of Views'),
            'required' => false,
            'name' => 'views',
            'default' => '0'
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
        return $this->_helper()->__("Additional");
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->_helper()->__("Additional");
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