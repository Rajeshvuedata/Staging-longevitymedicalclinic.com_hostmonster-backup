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

class Magpleasure_Blog_Model_Comment extends Magpleasure_Blog_Model_Abstract
{
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    const CACHE_TAG = "MPBLOG_COMMENT";

    protected $_post;

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/comment');
    }

    public function getOptionsArray()
    {
        return array(
            self::STATUS_PENDING => $this->_helper()->__("Pending"),
            self::STATUS_APPROVED => $this->_helper()->__("Approved"),
            self::STATUS_REJECTED => $this->_helper()->__("Rejected"),
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

    public function approve()
    {
        $this
            ->setSessionId(null)
            ->setStatus(self::STATUS_APPROVED)
            ->save();
        return $this;
    }

    public function reject()
    {
        $this
            ->setSessionId(null)
            ->setStatus(self::STATUS_REJECTED)
            ->save();
        return $this;
    }

    public function getPost()
    {
        if (!$this->_post){
            /** @var Magpleasure_Blog_Model_Post $post  */
            $post = Mage::getModel("mpblog/post")->load($this->getPostId());
            $post->setStoreId($this->getStoreId());

            $this->_post = $post;
        }
        return $this->_post;
    }

    public function getPostTitle()
    {
        return $this->getPost() ? $this->getPost()->getTitle() : '';
    }

    public function getCommentUrl()
    {
        return $this->_helper()->_url($this->getStoreId())->getUrl($this->getPost()->getId())."#mp-blog-comment-".$this->getId();
    }


    protected function _prepareComment($message)
    {
        $message = html_entity_decode($message);
        return strip_tags($message);
    }

    public function comment(array $data)
    {
        $this->addData($data);
        $this->setStoreId(Mage::app()->getStore()->getId());
        if ($this->_helper()->getCommentsAutoapprove()){
            $this->setStatus(self::STATUS_APPROVED);
            $this->setSessionId(null);
        } else {
            $this->setStatus(self::STATUS_PENDING);
        }
        $this->setMessage( $this->_prepareComment($data['message']) );
        $this->save();
        return $this;
    }

    public function reply(array $data)
    {
        /** @var Magpleasure_Blog_Model_Comment $comment  */
        $comment = Mage::getModel('mpblog/comment');
        $comment->setReplyTo($this->getId());
        $comment->comment($data);
        return $comment;
    }


    public function afterCommitCallback()
    {
        parent::afterCommitCallback();

        # Update recently Post Comment Changed form Adminhtml
        if ($this->getStatus() == self::STATUS_APPROVED){

            $now = new Zend_Date();
            $now = $now->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
            $this
                ->getPost()
                ->setIsCommentUpdateFlag(true)
                ->setRecentlyCommentedAt($now)
                ->save($now)
            ;
        } else {

            # Add Comment Id to Cookie
            $this->_helper()->getCommon()->getCookie()->addToCookie($this->_helper()->getDynamicCookieName(), $this->getId());
        }
    }

}