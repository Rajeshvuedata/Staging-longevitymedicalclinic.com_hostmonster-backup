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

class Magpleasure_Blog_Block_Adminhtml_Post_Category_Edit_Form extends Magpleasure_Blog_Block_Adminhtml_Filterable_Widget_Form
{
    const POSTS_KEY = 'mpblog_post_update_posts';

    protected $_values = array();

    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/massUpdateCategoryGo', $this->_getCommonParams()),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            )
        );


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

        $fieldset = $form->addFieldset('add_category_legend', array('legend' => $this->_helper()->__('Add Categories')));

        $fieldset->addField('post_ids', 'hidden', array(
            'name'  => 'post_ids',
        ));

        $fieldset->addField('add_category', 'checkbox', array(
            'label'     => $this->_helper()->__("Add Categories"),
            'required'  => false,
            'name'      => 'add_category',
            'checked'   => false,
            'onchange'  => "checkboxChanged('add_category');",
        ));

        $fieldset->addField('add_category_ids', 'multiselect',array(
            'label'     => $this->_helper()->__('Categories to add'),
            'required'  => false,
            'name'      => 'add_category_ids[]',
            'values'    => $category->getCategoryList($stores),
            'disabled'  => true,
        ));

        $fieldset = $form->addFieldset('remove_category_legend', array('legend' => $this->_helper()->__('Remove Categories')));

        $fieldset->addField('remove_category', 'checkbox', array(
            'label'     => $this->_helper()->__("Remove Categories"),
            'required'  => false,
            'name'      => 'remove_category',
            'checked'   => false,
            'onchange'  => "checkboxChanged('remove_category');",
        ));

        $fieldset->addField('remove_category_ids', 'multiselect',array(
            'label'     => $this->_helper()->__('Categories to remove'),
            'required'  => false,
            'name'      => 'remove_category_ids[]',
            'values'    => $category->getCategoryList($stores),
            'disabled'  => true,
        ));


        $form->setUseContainer(true);
        $form->setValues($this->_getValues());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _getValues()
    {
        if (Mage::getSingleton('adminhtml/session')->getPostUpdateData()) {
            $this->_values = Mage::getSingleton('adminhtml/session')->getPostUpdateData();
            Mage::getSingleton('adminhtml/session')->getPostUpdateData(null);
        }

        $this->_values['post_ids'] = implode(",", Mage::registry(self::POSTS_KEY));
        return $this->_values;
    }

}
