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


class Magpleasure_Blog_Block_Adminhtml_Category_Edit extends Magpleasure_Blog_Block_Adminhtml_Filterable_Edit
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    protected function _getId()
    {
        if ($id = $this->getRequest()->getParam('id')){
            return $id;
        } else {
            return false;
        }
    }

    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'mpblog';
        $this->_controller = 'adminhtml_category';

        $this->_updateButton('save', 'label', $this->_helper()->__('Save Category'));
        $this->_updateButton('delete', 'label', $this->_helper()->__('Delete Category'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        if ($this->_getId()){
            $this->addButton('duplicate', array(
                'title' => $this->_helper()->__("Duplicate"),
                'label' => $this->_helper()->__("Duplicate"),
                'onclick' => "duplicate();",
                'class' => 'scalable save duplicate',
            ), 1, 2);

            $params = $this->_getCommonParams();
            $params['id'] = $this->_getId();

            $duplicateUrl = $this->getUrl('*/*/duplicate', $params);
            $confirmationMessage = $this->_helper()->__("Please confirm duplicating. All data that hasn't been saved will be lost.");
            $confirmationMessage = str_replace("'", "\\'",$confirmationMessage);
            $this->_formScripts[] = "
                function duplicate(){
                    if (confirm('{$confirmationMessage}')){
                        window.location = '{$duplicateUrl}';
                    }
                }
            ";
        }

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_category') && Mage::registry('current_category')->getId()) {
            return $this->_helper()->__("Edit Category '%s'", $this->escapeHtml(Mage::registry('current_category')->getName()));
        } else {
            return $this->_helper()->__('New Category');
        }
    }
}