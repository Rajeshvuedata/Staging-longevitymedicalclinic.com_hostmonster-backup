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

class Magpleasure_Blog_Block_Comments_Form extends Magpleasure_Blog_Block_Comments_Abstract
{
    protected $_collection;

    protected $_post;
    protected $_replyTo;
    protected $_formData = array();

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("mpblog/comments/form.phtml");
    }

    public function setPost($value)
    {
        $this->_post = $value;
        return $this;
    }

    public function setReplyTo($value)
    {
        $this->_replyTo = $value;
    }

    public function canPostComments()
    {
        return $this->_helper()->getCommentsAllowGuests();
    }

    /**
     * Comment
     *
     * @return Magpleasure_Blog_Model_Comment
     */
    public function getReplyTo()
    {
        return $this->_replyTo ? $this->_replyTo->getId() : 0;
    }

    /**
     * Post
     * @return Magpleasure_Blog_Model_Post
     */
    public function getPost()
    {
        return $this->_post;
    }

    public function getPostId()
    {
        return $this->getPost()->getId();
    }

    public function isReply()
    {
        return !!$this->getReplyTo();
    }

    public function canPost()
    {
        return $this->_helper()->getCommentsAllowGuests() || $this->isLoggedId();
    }

    public function setFormData(array $data)
    {
        $this->_formData = $data;
    }

    public function getFormData()
    {
        return new Varien_Object($this->_formData);
    }

    public function getRegisterUrl()
    {
        return $this->getUrl('customer/account/create');
    }

    public function getLoginUrl()
    {
        return $this->getUrl('customer/account/login');
    }

    public function isLoggedId()
    {
        return $this->getCustomerSession()->isLoggedIn();
    }

    public function getCustomerId()
    {
        return $this->getCustomerSession()->getCustomerId();
    }

    public function getCustomerName()
    {
        return $this->isLoggedId() ? $this->getCustomerSession()->getCustomer()->getName() : $this->_helper()->loadCommentorName();
    }

    public function getCustomerEmail()
    {
        return $this->isLoggedId() ? $this->getCustomerSession()->getCustomer()->getEmail() : $this->_helper()->loadCommentorEmail();
    }

    public function getSessionId()
    {
        return $this->getData('session_id');
    }

    public function getMessageBlockHtml()
    {
        $block = $this->getMessagesBlock();
        if ($block){
            $block->setMessages($this->getCustomerSession()->getMessages(true));
        }
        return $block->toHtml();
    }

    public function getEmailsEnabled()
    {
        return $this->_helper()->getCommentNotificationsEnabled();
    }

    /**
     * Notifications
     *
     * @return string
     */
    public function getEmailNotificationsHtml()
    {
        /** @var $notifications Magpleasure_Blog_Block_Comments_Form_Notifications */
        $notifications = $this->getLayout()->createBlock('mpblog/comments_form_notifications');
        if ($notifications){
            $notifications->setPost($this->getPost())->setSessionId($this->getSessionId());
            return $notifications->toHtml();
        }
        return "";
    }
}