<?php
/**
 * Magpleasure Ltd.
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
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Searchcore
 * @version    1.0.6
 * @copyright  Copyright (c) 2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Searchcore_Model_Resource_Index_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_storeId;

    protected $_tableName;

    public function setMainTable($table)
    {
        $this->_tableName = $table;
        $this->_select->reset(Zend_Db_Select::FROM);
        $this->_initSelect();
        return $this;
    }

    public function getMainTable()
    {
        return $this->_tableName ? $this->_tableName : $this->getResource()->getMainTable();
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        $resource = $this->getResource()->setStoreId($storeId);
        $this->setMainTable($resource->getMainTable());

        return $storeId;
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    protected function _construct()
    {
        $this->_init('searchcore/index');
    }

    /**
     * Helper
     *
     * @return Magpleasure_Common_Helper_Data
     */
    public function _commonHelper()
    {
        return Mage::helper('magpleasure');
    }

    public function flush()
    {
        $this->_flush();
        $this->_resetAutoInc();
        return $this;
    }

    protected function _resetAutoInc()
    {
        try {
            $write = $this->_commonHelper()->getDatabase()->getWriteConnection();
            $tableName = $this->getMainTable();
            $write->query("ALTER TABLE `{$tableName}` AUTO_INCREMENT = 1;");

        } catch (Exception $e){
            $this->_commonHelper()->getException()->logException($e);
        }
        return $this;
    }

    protected function _getResultTableName()
    {
        return $this->_commonHelper()->getDatabase()->getTableName("mp_search_result_{$this->_storeId}");
    }

    protected function _flush()
    {
        try {

            # 1. Reset queries
            /** @var Magpleasure_Searchcore_Model_Query $query */
            $query  = Mage::getModel('searchcore/query');
            $query->setStoreId($this->getStoreId());

            $queries = $query->getCollection();
            $queries
                ->addFieldToFilter('store_id', $this->getStoreId())
                ->resetQueries()
            ;

            # 2. Flush results
            $write = $this->_commonHelper()->getDatabase()->getWriteConnection();
            $write->beginTransaction();

            $resultTable = $this->_getResultTableName();
            $where = "";
            $write->delete($resultTable, $where);

            # 3. Flush index rows
            $tableName = $this->getMainTable();
            $where = "";
            $write->delete($tableName, $where);

            $write->commit();

        } catch (Exception $e){
            $this->_commonHelper()->getException()->logException($e);
        }
        return $this;
    }

    public function addSearchFilter($q)
    {
        $words = explode(" ", $q);
        $queryParts = array();
        foreach ($words as $word){
            $word = trim($word, "'+- \t.");
            $queryParts[] = $word;
        }

        if (count($queryParts)){
            $query = "+".implode(" +", $queryParts);
            $this
                ->getSelect()
                ->where("MATCH(`data_index`) AGAINST ('{$query}' IN BOOLEAN MODE)")
            ;
        } else {
            $this
                ->getSelect()
                ->where("MATCH(`data_index`) AGAINST ('{$q}')")
            ;
        }

        return $this;
    }

}