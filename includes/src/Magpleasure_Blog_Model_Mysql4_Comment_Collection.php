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

class Magpleasure_Blog_Model_Mysql4_Comment_Collection extends Magpleasure_Blog_Model_Mysql4_Abstract_Collection
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/comment');
    }

    public function addStoreFilter($storeIds)
    {
        if (!is_array($storeIds)){
            $storeIds = array($storeIds);
        }

        $storeIds = "'".implode("','", $storeIds)."'";
        $this
            ->getSelect()
            ->where(new Zend_Db_Expr("main_table.store_id IN ({$storeIds})"))
            ;

        return $this;
    }

    public function addPostFilter($postId)
    {
        $this->addFieldToFilter('post_id', $postId);
        return $this;
    }

    public function addActiveFilter($ownerSessionId = null)
    {
        if ($ownerSessionId){
            $activeStatus = Magpleasure_Blog_Model_Comment::STATUS_APPROVED;
            $pendingStatus = Magpleasure_Blog_Model_Comment::STATUS_PENDING;
            $this->getSelect()
                ->where(new Zend_Db_Expr("(main_table.status = '{$activeStatus}') OR ((main_table.status = '{$pendingStatus}') AND (main_table.session_id = '$ownerSessionId'))"))
                ;

        } else {
            $this->addFieldToFilter('status', Magpleasure_Blog_Model_Comment::STATUS_APPROVED);
        }
        return $this;
    }

    public function addReplyTo()
    {
        $this->getSelect()
            ->joinLeft(array('replied'=>$this->getMainTable()), "replied.comment_id = main_table.reply_to", array('reply_to_text'=>'replied.message'))
            ;
        return $this;
    }

    public function addReplyToTextFilter($filter)
    {
        $this->getSelect()
            ->where("replied.message LIKE ('%{$filter}%')")
            ;
        return $this;
    }


    public function addMessageTextFilter($filter)
    {
        $this->getSelect()
            ->where("main_table.message LIKE ('%{$filter}%')")
            ;
        return $this;
    }

    public function addPostTextFilter($filter)
    {
        $postTable = Mage::getModel('mpblog/post')->getResource()->getMainTable();
        $this->getSelect()->join(array('post'=>$postTable), "main_table.post_id = post.post_id AND post.title LIKE ('%{$filter}%')", array());
        return $this;
    }

    public function addPostStoreFilter($storeIds)
    {
        if (!is_array($storeIds)){
            $storeIds = array($storeIds);
        }

        $table = Mage::getModel('mpblog/post')->getResource()->getMainTable()."_store";
        $storeIds = "'".implode("','", $storeIds)."'";
        $this->getSelect()->joinInner(array('store'=>$table), "store.post_id = main_table.post_id AND store.store_id IN ({$storeIds})", array());
        return $this;
    }

    public function setDateOrder($dir = 'DESC')
    {
        $this->getSelect()
            ->order("main_table.created_at {$dir}");
        return $this;
    }

    public function setNotReplies()
    {
        $this->getSelect()
            ->where("main_table.reply_to IS NULL")
            ;

        return $this;
    }

    public function setReplyToFilter($commentId)
    {
        $this->getSelect()
            ->where("main_table.reply_to = ?", $commentId)
            ;
        return $this;
    }

    public function addStatusFilter($statusId)
    {
        $this->getSelect()
            ->where(new Zend_Db_Expr("main_table.status = '{$statusId}'"))
            ;
        return $this;
    }

    public function addCategoryFilter($categoryId)
    {
        $postTable = $this->getTable('mpblog/post')."_category";

        $this->getSelect()
            ->join(array('post_category'=>$postTable), "post_category.post_id = main_table.post_id", array())
            ->where("post_category.category_id = ?", $categoryId)
        ;

        return $this;
    }

}