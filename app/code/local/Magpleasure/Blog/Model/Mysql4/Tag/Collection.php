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

class Magpleasure_Blog_Model_Mysql4_Tag_Collection extends Magpleasure_Blog_Model_Mysql4_Abstract_Collection
{
    const MIN_SIZE = 1;
    const MAX_SIZE = 10;

    protected $_addWheightData = false;

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/tag');
    }

    public function addPostFilter($postId)
    {
        $postTable = $this->getTable('mpblog/post')."_tag";
        $this->getSelect()
            ->join(array('post'=>$postTable), "post.tag_id = main_table.tag_id", array())
            ->where("post.post_id = ?", $postId)
            ;

        return $this;
    }

    public function setNameOrder()
    {
        $this->getSelect()->order("main_table.name ASC");
        return $this;
    }

    public function addWieghtData($store = null)
    {
        $this->_addWheightData = true;
        $postTagTable = Mage::getModel('mpblog/post')->getResource()->getMainTable()."_tag";
        $this->getSelect()
            ->join(array('post'=>$postTagTable), "post.tag_id = main_table.tag_id", array('post_count' => new Zend_Db_Expr("COUNT(post.post_id)")))
            ->group("main_table.tag_id")
            ;

        if ($store){

            if (!is_array($store)){
                $store = array($store);
            }

            $store = "'".implode("','", $store)."'";
            $postStoreTable = Mage::getModel('mpblog/post')->getResource()->getMainTable()."_store";
            $this->getSelect()
                ->join(array('store'=>$postStoreTable), "post.post_id = store.post_id", array())
                ->where(new Zend_Db_Expr("store.store_id IN ({$store})"))
                ;
        }
        return $this;
    }

    public function setMinimalPostCountFilter($count)
    {
        if ($this->_addWheightData){
            $this->getSelect()
                ->having("COUNT(post.post_id) >= ?", $count)
            ;
        }
        return $this;
    }

    protected  function _afterLoad()
    {
        parent::_afterLoad();

        if ($this->_addWheightData){
            $tags = array();
            $sizes = array();

            foreach ($this as $tag){
                $tags[$tag->getId()] = $tag->getPostCount();
            }

            if (count($tags)){

                $minimum_count = min(array_values($tags));
                $maximum_count = max(array_values($tags));

                $spread = $maximum_count - $minimum_count;
                if($spread == 0) {
                    $spread = 1;
                }

                foreach ($tags as $tagId => $tagCount){
                    $sizes[$tagId] = self::MIN_SIZE + ($tagCount - $minimum_count) * (self::MAX_SIZE - self::MIN_SIZE) / $spread;
                }

                foreach ($this as $tag){
                    if (isset($sizes[$tag->getId()])){
                        $tag->setTagType($sizes[$tag->getId()]);
                    }
                }
            }
        }
        return $this;
    }

    public function setPostStatusFilter($status)
    {
        if (!is_array($status)){
            $status = array($status);
        }

        $postTable = Mage::getModel('mpblog/post')->getResource()->getMainTable();
        $this->getSelect()
                ->join(array('postEntity' => $postTable), "post.post_id = postEntity.post_id", array())
                ->where("postEntity.status IN (?)", $status);

        return $this;
    }

    /**
     * Add select order
     *
     * @param   string $field
     * @param   string $direction
     * @return  Varien_Data_Collection_Db
     */
    public function setOrder($field, $direction = self::SORT_ORDER_DESC)
    {
        if ($field == 'post_count'){

            $this->getSelect()->order("COUNT(post.post_id) {$direction}");
        } else {
            parent::setOrder($field, $direction);
        }

        return $this;
    }
}