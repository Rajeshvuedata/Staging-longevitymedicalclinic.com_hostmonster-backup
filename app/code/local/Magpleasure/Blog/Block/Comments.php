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

class Magpleasure_Blog_Block_Comments extends Mage_Core_Block_Template
{
    protected $_collection;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("mpblog/comments.phtml");
    }

    /**
     * Customer Session
     *
     * @return Mage_Customer_Model_Session
     */
    public function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
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

    /**
     * Core
     *
     * @return Mage_Core_Helper_Data
     */
    public function _core()
    {
        return Mage::helper('core');
    }

    /**
     * Post
     *
     * @return mixed
     */
    public function getPost()
    {
        $parent = $this->getParentBlock();
        if ($parent){
            if ($parent instanceof Magpleasure_Blog_Block_Content_Post){
                return $parent->getPost();
            }
        } else {
            return Mage::registry('current_post');
        }
        return false;
    }

    public function getCommentsCount()
    {
        /** @var Magpleasure_Blog_Model_Mysql4_Comment_Collection $comments  */
        $comments = Mage::getModel('mpblog/comment')->getCollection();

        if (!Mage::app()->isSingleStoreMode()){
            $comments->addStoreFilter(Mage::app()->getStore()->getId());
        }

        $comments
            ->addPostFilter($this->getPost()->getId())
            ->addActiveFilter($this->_helper()->getCommentsAutoapprove() ? null : $this->getCustomerSession()->getSessionId() )
        ;

        return $comments->getSize();
    }

    public function getCollection()
    {
        if (!$this->_collection){
            /** @var Magpleasure_Blog_Model_Mysql4_Comment_Collection $comments  */
            $comments = Mage::getModel('mpblog/comment')->getCollection();

            if (!Mage::app()->isSingleStoreMode()){
                $comments->addStoreFilter(Mage::app()->getStore()->getId());
            }

            $comments
                ->addPostFilter($this->getPost()->getId())
                ->addActiveFilter($this->_helper()->getCommentsAutoapprove() ? null : $this->getCustomerSession()->getSessionId() )
            ;

            $comments
                ->setDateOrder('ASC')
                ->setNotReplies()
                ;

            $this->_collection = $comments;
        }
        return $this->_collection;
    }

    public function getSessionId()
    {
        return $this->getCustomerSession()->getSessionId();
    }

    public function getMessageHtml(Magpleasure_Blog_Model_Comment $message)
    {
        $messageBlock = $this->getLayout()->createBlock('mpblog/comments_message');
        if ($messageBlock){
            $messageBlock->setMessage($message);
            return $messageBlock->toHtml();
        }
        return false;
    }

    public function getFormUrl()
    {
        return $this->getUrl('mpblog/index/form', array(
                                                        'reply_to'=>'{{reply_to}}',
                                                        'post_id'=>'{{post_id}}',
                                                        'session_id'=>'{{session_id}}',
                                                    ));
    }

    public function getPostId()
    {
        return $this->_core()->urlEncode($this->_core()->encrypt($this->getPost()->getId()));
    }

    public function getPostUrl()
    {
        return $this->getUrl('mpblog/index/postForm', array(
            'reply_to'=>'{{reply_to}}',
            'post_id'=>'{{post_id}}',
        ));
    }

    public function showRss()
    {
        return $this->_helper()->getRssComment();
    }

    public function getRssCommentFeedUrl()
    {
        $params = array();
        if (!Mage::app()->isSingleStoreMode()){
            $params['store_id'] = Mage::app()->getStore()->getId();
        }
        return $this->getUrl('mpblog/rss/comment', $params);
    }

    public function isOldStyle()
    {
        return ($this->_helper()->getIconStyle() == 'old');
    }

    public function getColorClass()
    {
        return $this->_helper()->getIconColorClass();
    }
}