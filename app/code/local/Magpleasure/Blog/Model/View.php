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

class Magpleasure_Blog_Model_View extends Magpleasure_Common_Model_Abstract
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/view');
    }

    /**
     * Customer Session
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function registerMe(Mage_Core_Controller_Request_Http $request, $refererUrl = null)
    {
        $this->getResource()->loadByPostAndSession($this, $request->getParam('id'), $this->_getCustomerSession()->getSessionId());
        if (!$this->getId()){

            /* @var $helper Mage_Core_Helper_Http */
            $helper = Mage::helper('core/http');
            $now = new Zend_Date();
            $this
                ->setPostId($request->getParam('id'))
                ->setCustomerId($this->_getCustomerSession()->isLoggedIn() ? $this->_getCustomerSession()->getCustomerId() : null)
                ->setSessionId($this->_getCustomerSession()->getSessionId())
                ->setRemoteAddr($helper->getRemoteAddr(true))
                ->setStoreId(Mage::app()->getStore()->getId())
                ->setCreatedAt($now->toString(Varien_Date::DATETIME_INTERNAL_FORMAT))
                ->setRefererUrl($refererUrl)
                ->save()
                ;
        }
        return $this;
    }
}