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

class Magpleasure_Blog_Model_Mysql4_Comment_Subscription extends Magpleasure_Common_Model_Resource_Abstract
{

    public function _construct()
    {    
        $this->_init('mpblog/comment_subscription', 'subscription_id');
        $this->setUseUpdateDatetimeHelper(true);
    }

    /**
     * Helper
     *
     * @return Magpleasure_Blog_Helper_Data
     */
    public function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function loadByEmail(Mage_Core_Model_Abstract $object, $postId, $email)
    {
        /** @var $collection Magpleasure_Blog_Model_Mysql4_Comment_Subscription_Collection */
        $collection = Mage::getModel('mpblog/comment_subscription')->getCollection();

        $collection
            ->addFieldToFilter('post_id', $postId)
            ->addFieldToFilter('email', $email)
            ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
        ;

        foreach ($collection as $item){
            $subscription = Mage::getModel('mpblog/comment_subscription')->load($item->getId());
            $object->addData($subscription->getData());
            return $this;
        }

        return $this;
    }

    public function loadBySessionId(Mage_Core_Model_Abstract $object, $postId, $sessionId)
    {
        /** @var $collection Magpleasure_Blog_Model_Mysql4_Comment_Subscription_Collection */
        $collection = Mage::getModel('mpblog/comment_subscription')->getCollection();

        $collection
            ->addFieldToFilter('post_id', $postId)
            ->addFieldToFilter('session_id', $sessionId)
            ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
            ;

        foreach ($collection as $item){
            $subscription = Mage::getModel('mpblog/comment_subscription')->load($item->getId());
            $object->addData($subscription->getData());
            return $this;
        }

        return $this;
    }


    public function loadByCustomerId(Mage_Core_Model_Abstract $object, $postId, $customerId)
    {
        /** @var $collection Magpleasure_Blog_Model_Mysql4_Comment_Subscription_Collection */
        $collection = Mage::getModel('mpblog/comment_subscription')->getCollection();

        $collection
            ->addFieldToFilter('post_id', $postId)
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
        ;

        foreach ($collection as $item){
            $object = Mage::getModel('mpblog/comment_subscription')->load($item->getId());
            return $this;
        }

        return $this;
    }

}