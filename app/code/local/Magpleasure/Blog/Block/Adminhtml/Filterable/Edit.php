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


class Magpleasure_Blog_Block_Adminhtml_Filterable_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function getAppliedStoreId()
    {
        return $this->_helper()->getStoreHelper()->getAppliedStoreId();
    }

    public function isStoreFilterApplied()
    {
        return $this->_helper()->getStoreHelper()->isStoreFilterApplied();
    }

    /**
     * Retrive params with common filter flags
     *
     * @return array
     */
    protected function _getCommonParams()
    {
        return $this->_helper()->getStoreHelper()->getCommonParams();
    }

    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        if ($this->hasFormActionUrl()) {
            return $this->getData('form_action_url');
        }

        $params = $this->_getCommonParams();
        return $this->getUrl('*/' . $this->_controller . '/save', $params);
    }

    public function getBackUrl()
    {
        $params = $this->_getCommonParams();
        return $this->getUrl('*/*/', $params);
    }

    public function getDeleteUrl()
    {
        $params = $this->_getCommonParams();
        $params[$this->_objectId] = $this->getRequest()->getParam($this->_objectId);
        return $this->getUrl('*/*/delete', $params);
    }
}