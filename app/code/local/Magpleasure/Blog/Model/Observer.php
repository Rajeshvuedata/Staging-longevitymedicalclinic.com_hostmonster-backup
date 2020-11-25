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


class Magpleasure_Blog_Model_Observer
{
    /**
     * Customer Session
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _customerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function customerLoginAfter($event)
    {
        /** @var Mage_Customer_Model_Customer $customer  */
        $customer = $event->getCustomer();
        $sessionId = $this->_customerSession()->getSessionId();

        # Comments

        /** @var $comments Magpleasure_Blog_Model_Mysql4_Comment_Collection */
        $comments = Mage::getModel('mpblog/comment')->getCollection();
        $comments
            ->addFieldToFilter('session_id', $sessionId)
            ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
            ->getSelect()
                ->where("main_table.customer_id IS NULL")
            ;

        foreach ($comments as $comment){
            $comment->setCustomerId($customer->getId())->save();
        }

        # Subscriptions

        /** @var $subscriptions Magpleasure_Blog_Model_Mysql4_Comment_Subscription_Collection */
        $subscriptions = Mage::getModel('mpblog/comment_subscription')->getCollection();
        $subscriptions
            ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
            ->addFieldToFilter('session_id', $sessionId)
            ->getSelect()
                ->where("main_table.customer_id IS NULL")
            ;

        foreach ($subscriptions as $subscription){
            $subscription->setCustomerId($customer->getId())->save();
        }

        # Views

        /** @var $views Magpleasure_Blog_Model_Mysql4_View_Collection */
        $views = Mage::getModel('mpblog/view')->getCollection();
        $views
            ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
            ->addFieldToFilter('session_id', $sessionId)
            ->getSelect()
                ->where("main_table.customer_id IS NULL")
            ;

        foreach ($views as $view){
            $view->setCustomerId($customer->getId())->save();
        }
    }

}