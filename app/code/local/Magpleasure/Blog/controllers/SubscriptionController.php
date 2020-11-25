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

class Magpleasure_Blog_SubscriptionController extends Mage_Core_Controller_Front_Action
{
    /**
     * Helper
     *
     * @return Magpleasure_Blog_Helper_Data
     */
    public function _helper()
    {
        return Mage::helper('mpblog');
    }

    /**
     * Customer Session
     *
     * @return Mage_Core_Model_Session
     */
    protected function _getCoreSession()
    {
        return Mage::getSingleton('core/session');
    }

    public function unsubscribeAction()
    {
        if ($hash = $this->getRequest()->getParam('h')){

            /** @var $subscription Magpleasure_Blog_Model_Comment_Subscription */
            $subscription = Mage::getModel('mpblog/comment_subscription');

            $subscription->load($hash, 'hash');
            if ($subscription->getId()){
                $subscription->setStatus(Magpleasure_Blog_Model_Comment_Subscription::STATUS_UNSUBSCRIBED)->save();
                $this->_getCoreSession()->addSuccess($this->__("Email %s was successfully unsubscribed for this post.", $subscription->getEmail()));
                $this->_redirectUrl($this->_helper()->_url()->getUrl($subscription->getPostId(), Magpleasure_Blog_Helper_Url::ROUTE_POST));
                return ;
            }
        }

        $this->_getCoreSession()->addError($this->__("Can not find your subscription."));
        $this->_redirectUrl(Mage::getBaseUrl());
        return ;
    }
}