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

/** Filterable Container */
class Magpleasure_Blog_Block_Adminhtml_Filterable extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function isSingleStoreMode()
    {
        return $this->_helper()->getStoreHelper()->isSingleStoreMode();
    }

    public function isStoreFilterApplied()
    {
        return $this->_helper()->getStoreHelper()->isStoreFilterApplied();
    }

    public function getAppliedStoreId()
    {
        return $this->_helper()->getStoreHelper()->getAppliedStoreId();
    }

    protected function _getCommonParams()
    {
        return $this->_helper()->getStoreHelper()->getCommonParams();
    }

    public function getCreateUrl()
    {
        $params = $this->_getCommonParams();
        return $this->getUrl('*/*/new', $params);
    }
}