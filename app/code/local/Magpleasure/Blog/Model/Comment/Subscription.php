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

class Magpleasure_Blog_Model_Comment_Subscription extends Mage_Core_Model_Abstract
{
    const STATUS_SUBSCRIBED = 2;
    const STATUS_UNSUBSCRIBED = 3;

    const TYPE_NO = 0;
    const TYPE_ANSWERS = 1;
    const TYPE_ALL = 2;

    protected $_post;

    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    /**
     * URL Instance
     *
     * @return Mage_Core_Model_Url
     */
    protected function _getUrlInstance()
    {
        return Mage::getSingleton('core/url');
    }

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/comment_subscription');
    }

    public function getOptionsArray()
    {
        return array(
            self::STATUS_SUBSCRIBED => $this->_helper()->__("Subscribed"),
            self::STATUS_UNSUBSCRIBED => $this->_helper()->__("Unsubscribed"),
        );
    }

    public function toOptionArray()
    {
        $result = array();
        foreach ($this->getOptionsArray() as $value=>$label){
            $result[] = array('value'=>$value, 'label'=>$label);
        }
        return $result;
    }

    public function toTypesArray()
    {
        $result = array();
        foreach ($this->getTypesArray() as $value=>$label){
            $result[] = array('value'=>$value, 'label'=>$label);
        }
        return $result;
    }


    public function getTypesArray()
    {
        return array(
            self::TYPE_NO => $this->_helper()->__("Never for this Post"),
            self::TYPE_ANSWERS => $this->_helper()->__("Only when someone replies to my comments in this Post"),
            self::TYPE_ALL => $this->_helper()->__("Each time a new comment is added to the Post"),
        );
    }

    public function loadBySessionId($postId, $sessionId)
    {
        $this->getResource()->loadBySessionId($this, $postId, $sessionId);
        return $this;
    }

    public function loadByEmail($postId, $email)
    {
        $this->getResource()->loadByEmail($this, $postId, $email);
        return $this;
    }

    public function loadByCustomerId($postId, $customerId)
    {
        $this->getResource()->loadByCustomerId($this, $postId, $customerId);
        return $this;
    }

    public function generateHash()
    {
        if (!$this->getHash()){
            $hash = md5(microtime());
            $this->setHash($hash);
        } else {
            Mage::throwException($this->_helper()->__("Can't generate Subscription Hash twice."));
        }

        return $this;
    }


    public function notifyAboutComment(Magpleasure_Blog_Model_Comment $comment)
    {

        if ($comment->getEmail() !== $this->getEmail()){

            ///TODO
            /** @var $notification Magpleasure_Blog_Model_Comment_Notification */
            $notification = Mage::getModel('mpblog/comment_notification');

            $notification
                ->setPostId($this->getPostId())
                ->setSubscriptionId($this->getId())
                ->setCommentId($comment->getId())
                ->setStatus(Magpleasure_Blog_Model_Comment_Notification::STATUS_WAIT)
                ->setStoreId($this->getStoreId())
                ->save()
                ;

        }
        return $this;
    }

    /**
     * Retrieves unsubscribe URL
     *
     * @return string
     */
    public function getUnsubscribeUrl()
    {
        return $this->_getUrlInstance()->getUrl('mpblog/subscription/unsubscribe', array('h'=> $this->getHash()));
    }
}