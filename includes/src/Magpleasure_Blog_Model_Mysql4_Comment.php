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

class Magpleasure_Blog_Model_Mysql4_Comment extends Magpleasure_Common_Model_Resource_Abstract
{
    /**
     * Zend_Date date format for Mysql requests
     */
    const MYSQL_ZEND_DATE_FORMAT = 'yyyy-MM-dd HH:mm:ss';

    protected $_needToNotifyAdmin = false;
    protected $_needToNotifyCustomer = false;

    public function _construct()
    {    
        $this->_init('mpblog/comment', 'comment_id');
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

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId()){
            if (!$this->_helper()->getCommentsAutoapprove() && (Mage::app()->getLayout()->getArea() == 'frontend') ){
                $this->_needToNotifyAdmin = true;
            }
        }

        if ($this->_helper()->getCommentNotificationsEnabled()){
            if (($object->getStatus() == Magpleasure_Blog_Model_Comment::STATUS_APPROVED) && !$object->getNotified()){
                $object->setNotified(1);
                $this->_needToNotifyCustomer = true;
            }
        }

        parent::_beforeSave($object);
    }


    protected function _prepareCache(Mage_Core_Model_Abstract $object)
    {
        # Clean cache for Posts and Routes
        $this->_helper()->getCommon()->getCache()->cleanCachedData(
            array(
                Magpleasure_Blog_Model_Comment::CACHE_TAG,
            )
        );

        return $this;
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        parent::_afterSave($object);

        # Clean Comments related cache
        $this->_prepareCache($object);

        if ($this->_needToNotifyAdmin){
            $this->_helper()->_notifier()->notifyAboutPendingComment($object);
        }

        if ($this->_needToNotifyCustomer){

            # Answer subscriptions

            if ($replyId = $object->getReplyTo()){

                /** @var $replied Magpleasure_Blog_Model_Comment */
                $replied = Mage::getModel('mpblog/comment')->load($replyId);

                if ($replied->getEmail() != $object->getEmail()){
                    /** @var $subscriptions Magpleasure_Blog_Model_Mysql4_Comment_Subscription_Collection */
                    $subscriptions = Mage::getModel('mpblog/comment_subscription')->getCollection();

                    $subscriptions
                        ->addFieldToFilter('post_id', $replied->getPostId())
                        ->addFieldToFilter('email', $replied->getEmail())
                        ->addFieldToFilter('store_id', $replied->getStoreId())
                        ->addFieldToFilter('subscription_type', Magpleasure_Blog_Model_Comment_Subscription::TYPE_ANSWERS)
                        ;

                    foreach ($subscriptions as $subscription){
                        /** @var $subscription Magpleasure_Blog_Model_Comment_Subscription */
                        $subscription->notifyAboutComment($object);
                    }
                }
            }

            # All comments subscriptions

            /** @var $subscriptions Magpleasure_Blog_Model_Mysql4_Comment_Subscription_Collection */
            $subscriptions = Mage::getModel('mpblog/comment_subscription')->getCollection();

            $subscriptions
                ->addFieldToFilter('post_id', $object->getPostId())
                ->addFieldToFilter('email', array('neq' => $object->getEmail()))
                ->addFieldToFilter('store_id', $object->getStoreId())
                ->addFieldToFilter('subscription_type', Magpleasure_Blog_Model_Comment_Subscription::TYPE_ALL)
                ->getSelect()
                    ->group('email')
            ;

            foreach ($subscriptions as $subscription){
                /** @var $subscription Magpleasure_Blog_Model_Comment_Subscription */
                $subscription->notifyAboutComment($object);
            }

        }

        return  $this;
    }

    protected function _afterDelete(Mage_Core_Model_Abstract $object)
    {
        parent::_afterDelete($object);
        $this->_prepareCache($object);

        return $this;
    }
}