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


class Magpleasure_Blog_Block_Adminhtml_Import extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function __construct()
    {
        parent::__construct();
        $this->_objectId = null;
        $this->_blockGroup = null;
        $this->_controller = null;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $form = $this->getLayout()->createBlock('mpblog/adminhtml_import_form');
        $this->setChild('form', $form);
    }

    protected function _beforeToHtml()
    {
        $this
            ->_removeButton('back')
            ->_removeButton('reset')
            ->_removeButton('save')
            ->addButton('import', array(
                'label'     => $this->__('Import'),
                'onclick'   => "editForm.submit();",
                'class'     => 'save',
            ))
            ;
        parent::_beforeToHtml();
    }

    public function getLabel()
    {
        return (string)$this->_helper()->getConfigValue($this->getImportType(), 'label');
    }

    public function getHeaderText()
    {
        return $this->__("Import - %s", $this->getLabel());
    }

}