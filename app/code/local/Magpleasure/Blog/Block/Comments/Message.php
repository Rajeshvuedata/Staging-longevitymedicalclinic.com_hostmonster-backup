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

class Magpleasure_Blog_Block_Comments_Message extends Magpleasure_Blog_Block_Comments_Abstract
{
    protected $_collection;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("mpblog/comments/message.phtml");
    }

    /**
     * Comment
     *
     * @return Magpleasure_Blog_Model_Comment
     */
    public function getMessage()
    {
        return $this->getData('message');
    }

    public function getContent()
    {
        return $this->_helper()->_render()->render($this->getMessage()->getMessage());
    }

    public function getMessageId()
    {
        return $this->getMessage()->getId();
    }

    public function getAuthor()
    {
        return $this->getMessage()->getName();
    }

    public function getDate()
    {
        return $this->_helper()->renderDate($this->getMessage()->getCreatedAt());
    }

    public function getTime()
    {
        return $this->_helper()->renderTime($this->getMessage()->getCreatedAt());
    }

    public function getRepliesCollection()
    {
        if (!$this->_collection){
            /** @var Magpleasure_Blog_Model_Mysql4_Comment_Collection $comments  */
            $comments = Mage::getModel('mpblog/comment')->getCollection();

            if (!Mage::app()->isSingleStoreMode()){
                $comments->addStoreFilter(Mage::app()->getStore()->getId());
            }

            $comments
                ->addActiveFilter($this->_helper()->getCommentsAutoapprove() ? null : $this->getCustomerSession()->getSessionId() )
            ;

            $comments
                ->setDateOrder()
                ->setReplyToFilter($this->getMessage()->getId())
            ;

            $this->_collection = $comments;
        }
        return $this->_collection;
    }

    /**
     * Replies Html
     *
     * @return string
     */
    public function getRepliesHtml()
    {
        $html = "";
        foreach ($this->getRepliesCollection() as $message){
            $messageBlock = $this->getLayout()->createBlock('mpblog/comments_message');
            if ($messageBlock){
                $messageBlock->setMessage($message);
                $html .= $messageBlock->toHtml();
            }
        }
        return $html;
    }

    public function isReply()
    {
        if ($this->getIsAjax()){
            return false;
        }
        if ($this->getMessage()->getReplyTo()){
            $flag = 'mpblog_reply_'.$this->getMessage()->getReplyTo();
            if (!Mage::registry($flag)){
                Mage::register($flag, true);
                return true;
            }
        }
        return false;
    }

    public function getCountCode()
    {
        return $this->getCommentsCount() ? $this->__("%s comments", $this->getCommentsCount()) : $this->__("No comments");
    }

    public function getNeedApproveMessage()
    {
        return ($this->getMessage()->getStatus() == Magpleasure_Blog_Model_Comment::STATUS_PENDING);
    }

}