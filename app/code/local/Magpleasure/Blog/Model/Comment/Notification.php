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

class Magpleasure_Blog_Model_Comment_Notification extends Magpleasure_Common_Model_Abstract
{
    const STATUS_WAIT = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILED = 3;

    protected $_subscription;
    protected $_comment;

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
        $this->_init('mpblog/comment_notification');
    }

    public function getOptionsArray()
    {
        return array(
            self::STATUS_WAIT => $this->_helper()->__("Wait"),
            self::STATUS_SUCCESS => $this->_helper()->__("Success"),
            self::STATUS_FAILED => $this->_helper()->__("Failed"),
        );
    }

    /**
     * Subscription
     *
     * @return Magpleasure_Blog_Model_Comment_Subscription
     */
    public function getSubscription()
    {
        if (!$this->_subscription){
            $this->_subscription = Mage::getModel('mpblog/comment_subscription')->load($this->getSubscriptionId());
        }
        return $this->_subscription;
    }

    /**
     * Comment
     *
     * @return Magpleasure_Blog_Model_Comment
     */
    public function getComment()
    {
        if (!$this->_comment){
            $this->_comment = Mage::getModel('mpblog/comment')->load($this->getCommentId());
        }
        return $this->_comment;
    }


    /**
     * Send Notification Email
     *
     * @return Magpleasure_Blog_Model_Comment_Notification
     */
    public function send()
    {
        if ($this->_helper()->getCommentNotificationsEnabled()){


            $data = array();
            $data['post'] = $this->getComment()->getPost();
            $data['comment'] = $this->getComment();
            $data['subscription'] = $this->getSubscription();

            $storeId = $this->getStoreId();
            $data['store'] = Mage::app()->getStore($storeId);

            $template = Mage::getStoreConfig('mpblog/notify_customer_comment_replyed/email_template', $storeId);
            $sender = Mage::getStoreConfig('mpblog/notify_customer_comment_replyed/sender', $storeId);
            $receiver = $this->getSubscription()->getEmail();

            if (trim($receiver)){
                /** @var Mage_Core_Model_Email_Template $mailTemplate  */
                $mailTemplate = Mage::getModel('core/email_template');
                try {
                    $mailTemplate
                        ->setDesignConfig(array('area' => 'frontend', 'store'=>$storeId))
                        ->sendTransactional(
                        $template,
                        $sender,
                        trim($receiver),
                        $this->getComment()->getName(),
                        $data,
                        $storeId
                    )
                    ;

                    $this->setStatus(self::STATUS_SUCCESS)->save();

                } catch (Exception $e) {
                    $this->setStatus(self::STATUS_FAILED)->save();
                    Mage::logException($e);
                }
            }
        }
        return $this;
    }
}