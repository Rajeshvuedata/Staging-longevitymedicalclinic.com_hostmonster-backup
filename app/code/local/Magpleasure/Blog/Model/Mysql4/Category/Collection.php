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

class Magpleasure_Blog_Model_Mysql4_Category_Collection extends Magpleasure_Blog_Model_Mysql4_Abstract_Collection
{
    protected $_loadStores = false;

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/category');
    }

    public function addStoreData()
    {
        $this->_loadStores = true;
        return $this;
    }

    public function addStoreFilter($store)
    {
        if (!is_array($store)){
            $store = array($store);
        }

        $table = $this->getMainTable()."_store";
        $idFieldName = "category_id";
        $storesFilter = "'".implode("','", $store)."'";
        $this->getSelect()->joinInner(array('store'=>$table), "store.{$idFieldName} = main_table.{$idFieldName} AND store.store_id IN ({$storesFilter})", array());

        if (count($store) > 1){
            $this->getSelect()->group("main_table.category_id");
        }

        return $this;
    }

    protected function _afterLoad()
    {
        parent::_afterLoad();
        if ($this->_loadStores){
            foreach ($this as $item){
                $item->getResource()->loadStores($item);
            }
        }
        return $this;
    }

    public function addPostFilter($postId)
    {
        $postTable = $this->getTable('mpblog/post')."_category";

        $this->getSelect()
            ->join(array('post'=>$postTable), "post.category_id = main_table.category_id", array())
            ->where("post.post_id = ?", $postId)
            ;

        return $this;
    }

    public function setSortOrder($direction)
    {
        $this->getSelect()->order("main_table.sort_order {$direction}");
        return $this;
    }

    public function getSortOrders()
    {
        return $this->_collectValuesByField('sort_order');
    }

    /**
     * Get SQL for get record count
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $categoryIdsSelect = clone $this->getSelect();
        $categoryIdsSelect->reset(Zend_Db_Select::ORDER);
        $categoryIdsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $categoryIdsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $categoryIdsSelect->reset(Zend_Db_Select::COLUMNS);
        $categoryIdsSelect->columns('main_table.category_id');

        $countSelect = new Zend_Db_Select($this->getResource()->getReadConnection());
        $tableName = $this->getMainTable();
        $countSelect->from(array('mt'=>$tableName), array(new Zend_Db_Expr("COUNT(*)")));
        $countSelect->where(new Zend_Db_Expr(sprintf("`mt`.`category_id` in (%s)", $categoryIdsSelect->__toString())));

        return $countSelect;
    }

}