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

class Magpleasure_Blog_Model_Post extends Magpleasure_Blog_Model_Abstract implements Magpleasure_Blog_Model_Interface
{
    /**
     * Zend_Date date format for Mysql requests
     */
    const MYSQL_ZEND_DATE_FORMAT = 'yyyy-MM-dd HH:mm:ss';

    const DUPLICATE_FLAG = 'mp_blog_post_duplicate_flag';

    const STATUS_DISABLED = 0;
    const STATUS_HIDDEN = 1;
    const STATUS_ENABLED = 2;
    const STATUS_SCHEDULED = 3;

    const CACHE_TAG = 'MPBLOG_POST';

    const CUT_LIMITER = '<!-- blogcut -->';

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/post');
    }

    public function getOptionsArray()
    {
        return array(
            self::STATUS_ENABLED => $this->_helper()->__("Enabled"),
            self::STATUS_DISABLED => $this->_helper()->__("Disabled"),
            self::STATUS_HIDDEN => $this->_helper()->__("Hidden"),
            self::STATUS_SCHEDULED => $this->_helper()->__("Scheduled"),
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

    public function getPostUrl($page = 1)
    {
        return $this
            ->_helper()
            ->_url($this->getStoreId())
            ->getUrl($this->getId(), Magpleasure_Blog_Helper_Url::ROUTE_POST, $page);
    }

    public function getCommentsEnabled()
    {
        return $this->_helper()->getCommentsEnabled() && $this->getData('comments_enabled');
    }

    public function duplicate()
    {
        $data = array();
        $this->_setDuplicateFlag();
        foreach ($this->getData() as $key => $value){
            if (!in_array($key, array('post_id', 'notify_on_enable', 'views', 'published_at'))){
                $data[$key] = $this->getData($key);
            }
        }

        $newPost = Mage::getModel('mpblog/post');
        $newPost
            ->addData($data)
            ->setStatus(self::STATUS_DISABLED)
            ->save()
            ;

        return $newPost;
    }

    protected function _getContent($key)
    {
        $content = $this->getData($key);
        /** @var Mage_Widget_Model_Template_Filter $processor  */
        $processor = Mage::getModel('widget/template_filter');
        Varien_Profiler::start("mp::blog::filter_content");
        $content = $processor->filter($content);
        $content = str_replace('target="_self"', "", $content);
        Varien_Profiler::stop("mp::blog::filter_content");
        return $content;
    }

    public function getShortContent()
    {
        if ($this->getDisplayShortContent()){
            return $this->_getContent('short_content');
        } else {
            $content = $this->_getContent('full_content');
            $parts = explode(self::CUT_LIMITER, $content);
            if (count($parts) > 1){
                return $parts[0];
            } else {
                return $content;
            }
        }
    }

    protected function _setDuplicateFlag()
    {
        Mage::register(self::DUPLICATE_FLAG, true, true);
        return $this;
    }

    public function getFullContent()
    {
        return str_replace(self::CUT_LIMITER, "", $this->_getContent('full_content'));
    }

    public function isScheduled()
    {
        return $this->getStatus() == self::STATUS_SCHEDULED;
    }

    public function isHidden()
    {
        return $this->getStatus() == self::STATUS_HIDDEN;
    }

    public function activateScheduled()
    {
        if ($this->isScheduled()){
            $this->getResource()->forceSave();
            $this
                ->setStatus(self::STATUS_ENABLED)
                ->save()
                ;

            if ($this->getNotifyOnEnable()){
                $this->_helper()->_notifier()->notifyAboutPostPublish($this);
            }

        }
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->getPublishedAt() ? $this->getPublishedAt() : $this->getData('created_at');
    }

    public function getUrl($params = array(), $page = 1)
    {
        return $this->getPostUrl($page);
    }

    public function getViews()
    {
        return $this->getData('views') + $this->getFlyViews();
    }

    public function getFlyViews()
    {
        /** @var $views Magpleasure_Blog_Model_Mysql4_View_Collection  */
        $views = Mage::getModel('mpblog/view')->getCollection();
        $views
            ->addFieldToFilter('post_id', $this->getPostId())
            ;

        return $views->getSize();
    }

    public function getScheduledDate()
    {
        $date = new Zend_Date($this->getPublishedAt());
        $date->subSecond($this->_helper()->getTimezoneOffset());
        $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        return $date->toString($format);
    }

    public function getRecentPostId()
    {
        /** @var $collection Magpleasure_Blog_Model_Mysql4_Post_Collection */
        $collection = $this->getCollection();

        $collection
            ->setDateOrder()
            ->addFieldToFilter('status', self::STATUS_ENABLED)
            ->setPageSize(1)
        ;

        if (!Mage::app()->isSingleStoreMode()){
            $collection->addStoreFilter(Mage::app()->getStore()->getId());
        }

        if ($collection->getSize()){
            foreach ($collection as $post){
                return $post->getId();
            }
        }

        return false;
    }

    public function getCategoriesText()
    {
        $categoryLabels = array();

        $categories = $this->getCategories();
        if ($categories && is_array($categories) && count($categories)){
            foreach ($this->getCategories() as $categoryId){
                $categoryLabels[] = $this->_helper()->getCategoryHelper()->getCategoryName($categoryId);
            }
        }

        return implode(",", $categoryLabels);
    }

    public function getCommentMessages(Magpleasure_Blog_Model_Comment $parentComment = null)
    {
        $commentMessages = array();

        /** @var Magpleasure_Blog_Model_Mysql4_Comment_Collection $comments  */
        $comments = Mage::getModel('mpblog/comment')->getCollection();

        if (!Mage::app()->isSingleStoreMode()){
            $comments->addStoreFilter($this->getStoreId());
        }

        if ($parentComment){

            $comments->setReplyToFilter($parentComment->getId());
        } else {

            $comments
                ->addPostFilter($this->getId())
                ->setNotReplies()
            ;
        }

        $comments
            ->addActiveFilter()
            ->setDateOrder('ASC')
        ;

        foreach ($comments as $comment){

            /** @var Magpleasure_Blog_Block_Comments $comment */
            if ($message = $this->_helper()->escapeHtml($comment->getMessage())){
                $commentMessages[] = $this->_helper()->escapeHtml($comment->getMessage());
            }

            $commentMessages = array_merge($commentMessages, $this->getCommentMessages($comment));
        }

        return $commentMessages;
    }


    public function getCommentsText()
    {
        $commentMessages = array();
        if ($this->getCommentsEnabled()){
            $commentMessages = $this->getCommentMessages();
        }
        return implode("|", $commentMessages);
    }

    public function getDefaultStoreId()
    {
        $stores = $this->getStores();
        if ($stores && is_array($stores) && count($stores) && isset($stores[0])){
            $storeId = $stores[0];
        } else {
            $storeId = Mage::app()->getDefaultStoreView()->getId();
        }

        return $storeId;
    }
}