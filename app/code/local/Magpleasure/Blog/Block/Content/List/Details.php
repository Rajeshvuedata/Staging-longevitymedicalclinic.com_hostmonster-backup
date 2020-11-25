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

class Magpleasure_Blog_Block_Content_List_Details extends Mage_Core_Block_Template
{
    protected $_post;

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
     * Set current post
     *
     * @param Magpleasure_Blog_Model_Post $post
     * @return Magpleasure_Blog_Block_Content_List_Details
     */
    public function setPost(Magpleasure_Blog_Model_Post $post)
    {
        $this->_post = $post;
        return $this;
    }

    /**
     * Post
     *
     * @return Magpleasure_Blog_Model_Post|null
     */
    public function getPost()
    {
        return $this->_post;
    }

    public function isPost()
    {
        return ($this->getRequest()->getActionName() == 'post');
    }

    public function renderDate($datetime)
    {
        return $this->_helper()->renderDate($datetime);
    }

    public function getLeaveCommentUrl()
    {
        return $this->getPost()->getPostUrl()."#form";
    }

    public function getCommentsUrl()
    {
        return $this->getPost()->getPostUrl()."#comments";
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
            ->addActiveFilter()
            ;
        return $comments->getSize();
    }

    public function getTagsHtml()
    {
        $tagDetails = $this->getLayout()->createBlock('mpblog/content_list_details');
        if ($tagDetails){
            $tagDetails
                ->setPost($this->getPost())
                ->setTemplate('mpblog/list/tags.phtml');
            ;
            return $tagDetails->toHtml();
        }
        return false;
    }

    public function getCategoriesHtml()
    {
        $catDetails = $this->getLayout()->createBlock('mpblog/content_list_details');
        if ($catDetails){
            $catDetails
                ->setPost($this->getPost())
                ->setTemplate('mpblog/list/categories.phtml');
                ;
            return $catDetails->toHtml();
        }
        return false;
    }

    /**
     * Tags
     *
     * @return Magpleasure_Blog_Model_Mysql4_Tag_Collection
     */
    public function getTags()
    {
        /** @var Magpleasure_Blog_Model_Mysql4_Tag_Collection $tags  */
        $tags = Mage::getModel('mpblog/tag')->getCollection();
        $tags->addPostFilter($this->getPost()->getId());

        return $tags;
    }

    /**
     * Categories
     *
     * @return Magpleasure_Blog_Model_Mysql4_Category_Collection
     */
    public function getCategories()
    {
        /** @var Magpleasure_Blog_Model_Mysql4_Category_Collection $categories  */
        $categories = Mage::getModel('mpblog/category')->getCollection();
        $categories
            ->addPostFilter($this->getPost()->getId())
            ->addFieldToFilter('status', Magpleasure_Blog_Model_Category::STATUS_ENABLED)
        ;

        if (!Mage::app()->isSingleStoreMode()){
            $categories->addStoreFilter(Mage::app()->getStore()->getId());
        }
        return $categories;
    }

    public function useGoogleProfile()
    {
        return !!$this->getPost()->getPostedBy() && !!$this->getPost()->getGoogleProfile();
    }

    public function getGoogleProfileUrl()
    {
        return $this->getPost()->getGoogleProfile()."?rel=author";
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