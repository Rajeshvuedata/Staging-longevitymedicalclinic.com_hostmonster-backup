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

class Magpleasure_Blog_Model_Mysql4_Post_Collection extends Magpleasure_Blog_Model_Mysql4_Abstract_Collection
{
    protected $_loadStores = false;

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/post');
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
        $idFieldName = "post_id";
        $storesFilter = "'".implode("','", $store)."'";
        $this
            ->getSelect()
            ->joinInner(array('store'=>$table), "store.{$idFieldName} = main_table.{$idFieldName} AND store.store_id IN ({$storesFilter})", array())
            ->group("main_table.post_id")
            ;
        return $this;
    }

    protected function _afterLoad()
    {
        parent::_afterLoad();
        if ($this->_loadStores){
            foreach ($this as $item){
                $item->getResource()->loadAdditionalData($item);
            }
        }
        return $this;
    }

    /**
     * Add tag filter
     *
     * @param int $tagId Tag Id
     * @return Magpleasure_Blog_Model_Mysql4_Post_Collection
     */
    public function addTagFilter($tagId)
    {
        $tagTable = $this->getMainTable()."_tag";
        $this->getSelect()
            ->join(array('tags'=>$tagTable), "tags.post_id = main_table.post_id", array())
            ->where("tags.tag_id = ?", $tagId)
            ;
        return $this;
    }

    public function addCategoryFilter($categoryId)
    {
        $categoryTable = $this->getMainTable()."_category";
        $this->getSelect()
            ->join(array('categories'=>$categoryTable), "categories.post_id = main_table.post_id", array())
            ->where("categories.category_id = ?", $categoryId)
        ;
        return $this;
    }
	
	/* public function getCategoryStatus($postId)
    {
        $categoryTable = $this->getMainTable()."_category";
      	$connection = Mage::getModel('core/resource')->getConnection('core_read');
		$sql = 'SELECT COUNT(*) as row_count FROM '.$categoryTable.' where post_id='.$postId.' ';
		$category = $connection->fetchAll($sql);
		return $category;
    }*/
	
	
	/*Function Filtering for Podcasts*/
	public function filterpodcasts()
	{
		$categoryTable = $this->getMainTable()."_category";
		$connection = Mage::getModel('core/resource')->getConnection('core_read');
		$sql = 'SELECT post_id FROM '.$categoryTable.' where category_id=119';
		$category = $connection->fetchAll($sql);
		//Mage::log($category);
		foreach($category as $cat)
		{
			$this->getSelect()->where("main_table.post_id != ".$cat['post_id']."");
		} 
    
       	
		 return $this;
	}
    /**
     *
     * @return Magpleasure_Blog_Model_Mysql4_Post_Collection
     */
    public function setDateOrder()
    {
        $this->getSelect()->order("IFNULL(main_table.published_at, main_table.created_at) DESC");
        return $this;
    }

    public function addToFilter($date)
    {
        $this->getSelect()->where(new Zend_Db_Expr("IFNULL(`main_table`.`published_at`, `main_table`.`created_at`) <= '{$date}'"));
        return $this;
    }

    public function addFromFilter($date)
    {
        $this->getSelect()->where(new Zend_Db_Expr("IFNULL(`main_table`.`published_at`, `main_table`.`created_at`) >= '{$date}'"));
        return $this;
    }

    public function setUrlKeyIsNotNull()
    {
        $this->getSelect()->where("main_table.url_key != ''");
    }

    /**
     * Get SQL for get record count
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $postIdsSelect = clone $this->getSelect();
        $postIdsSelect->reset(Zend_Db_Select::ORDER);
        $postIdsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $postIdsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $postIdsSelect->reset(Zend_Db_Select::COLUMNS);
        $postIdsSelect->columns('main_table.post_id');

        $countSelect = new Zend_Db_Select($this->getResource()->getReadConnection());
        $tableName = $this->getMainTable();
        $countSelect->from(array('mt'=>$tableName), array(new Zend_Db_Expr("COUNT(*)")));
        $countSelect->where(new Zend_Db_Expr(sprintf("`mt`.`post_id` in (%s)", $postIdsSelect->__toString())));

        return $countSelect;
    }

    public function getSelectedIds()
    {
        $idsSelect = clone $this->getSelect();
        $idsSelect->limit($this->_pageSize, ($this->_curPage - 1) * $this->_pageSize);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);
        $idsSelect->columns($this->getResource()->getIdFieldName(), 'main_table');

        return $this->getConnection()->fetchCol($idsSelect);
    }
}