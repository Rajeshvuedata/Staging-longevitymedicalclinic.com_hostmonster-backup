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

class Magpleasure_Searchcore_Model_Resource_Index extends Mage_Core_Model_Mysql4_Abstract
{
    const TABLE_PREFIX = "mp_search_index_";

    const INDEXER_PROCESS_ID = 'magpleasure_searchcore';

    protected $_storeId;

    /**
     * Set Store Id
     *
     * @param $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        $this->_mainTable = self::TABLE_PREFIX.$storeId;
        $this->_tables['index'] = $this->_mainTable;

        # Validate Store
        if (in_array($storeId, $this->_getStores())){

            # If has no index
            if (!$this->hasStoreIndex($storeId)){

                # Add index
                $this->addStoreIndex($storeId);
            }
        }

        return $this;
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
        $this->_init('searchcore/index', 'index_id');
    }

    public function getMainTable()
    {
        return $this->_helper()->getCommon()->getDatabase()->getTableName($this->_mainTable);
    }

    /**
     * Helper
     *
     * @return Magpleasure_Searchcore_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('searchcore');
    }

    protected function _wrapModel($modelName)
    {
        ///TODO Change in future
        return $modelName;
    }

    protected function _getResultTableName($storeId)
    {
        return $this->_helper()->getCommon()->getDatabase()->getTableName("mp_search_result_{$storeId}");
    }

    protected function _getIndexTableName($storeId)
    {
        return $this->_helper()->getCommon()->getDatabase()->getTableName("mp_search_index_{$storeId}");
    }

    protected function _getStores()
    {
        $storeIds = array('0');
        /** @var Mage_Core_Model_Mysql4_Store_Collection $stores */
        $stores = Mage::getModel('core/store')->getCollection();

        foreach ($stores as $store){
            $storeIds[] = $store->getId();
        }

        return $storeIds;
    }

    protected function _disableKeys($storeId = null)
    {
        if ($storeId !== null){
            $stores = array($storeId);
        } else {
            $stores = $this->_getStores();
        }


        try {

            $write = $this->_getWriteAdapter();

            foreach ($stores as $store){
                $table = $this->_getIndexTableName($store);
                $query = "ALTER TABLE `{$table}` DISABLE KEYS";
                $write->query($query);
            }

        } catch (Exception $e) {
            $this->_helper()->getCommon()->getException()->logException($e);
        }

        return $this;
    }

    protected function _enableKeys($storeId = null)
    {
        if ($storeId !== null){
            $stores = array($storeId);
        } else {
            $stores = $this->_getStores();
        }


        try {

            $write = $this->_getWriteAdapter();

            foreach ($stores as $store){
                $table = $this->_getIndexTableName($store);
                $query = "ALTER TABLE `{$table}` ENABLE KEYS";
                $write->query($query);
            }

        } catch (Exception $e) {
            $this->_helper()->getCommon()->getException()->logException($e);
        }


        return $this;
    }

    protected function _optimizeIndex($storeId = null)
    {
        if ($storeId !== null){
            $stores = array($storeId);
        } else {
            $stores = $this->_getStores();
        }

        try {

            $write = $this->_getWriteAdapter();

            foreach ($stores as $store){
                $table = $this->_getIndexTableName($store);
                $query = "OPTIMIZE TABLE {$table}";
                $write->query($query);
            }

        } catch (Exception $e) {
            $this->_helper()->getCommon()->getException()->logException($e);
        }

        return $this;
    }

    public function optimizeIndex($storeId = null)
    {
        return $this->_optimizeIndex($storeId);
    }

    public function reindexAll()
    {
        # 1. Disable keys
        $this->_disableKeys();

        # 2. Flush old index records

        Varien_Profiler::start('mp::searchcore::reindex_all::flush_index');

        foreach ($this->_getStores() as $storeId){
            /** @var Magpleasure_Searchcore_Model_Index $indexModel */
            $indexModel = Mage::getModel('searchcore/index');
            $indexModel->setStoreId($storeId);
            $collection = $indexModel->getCollection();
            $collection->flush();
        }

        Varien_Profiler::stop('mp::searchcore::reindex_all::flush_index');

        # 3. Get list of Types
        $collection = Mage::getModel('searchcore/type')->getCollection();

        # 4. For each type get indexing params
        $types = array();
        foreach ($collection as $item){
            /** @var Magpleasure_Searchcore_Model_Type $item */

            $config = $item->getConfig();

            /** @var Magpleasure_Common_Model_Resource_Abstract $collection */
            if ($resource = $config->getTargetResource()){
                if (method_exists($resource, 'reindexAll')){
                    $resource->reindexAll();
                } else {

                    Varien_Profiler::start('mp::searchcore::reindex_all::reindex_collection::'.$item->getTypeCode());

                    $modelName = $this->_wrapModel($config->getModel());

                    /** @var Mage_Core_Model_Resource_Db_Collection_Abstract $modelCollection */
                    $modelCollection = Mage::getModel($modelName)->getCollection();
                    /** @var Mage_Core_Model_Resource_Abstract $resourceModel */
                    $resourceModel = Mage::getResourceModel($modelName);

                    foreach ($modelCollection as $modelItem){
                        if (method_exists($resourceModel, 'reindexItem')){
                            $resourceModel->reindexItem($modelItem, $config);
                        } else {
                            $this->reindexItem($modelItem, $config, true);
                        }
                    }

                    Varien_Profiler::start('mp::searchcore::reindex_all::reindex_collection'.$item->getTypeCode());
                }
            }
        }

        # 5. Enable keys
        $this->_enableKeys();

        # 6. Optimize table
        $this->optimizeIndex();

        return $this;
    }

    /**
     * Reindex Abstract Item
     *
     * @param Mage_Core_Model_Abstract $object
     * @param Magpleasure_Searchcore_Model_Type_Config $config
     * @param bool $isFullIndex
     * @return $this
     */
    public function reindexItem(Mage_Core_Model_Abstract $object, Magpleasure_Searchcore_Model_Type_Config $config, $isFullIndex = false)
    {
        # 1. Load object to preprocess data we need to index
        if ($config->getLoadBeforeIndex() && $isFullIndex){
            $object->load($object->getId());
        }

        $stores = array();

        if (($storeKeys = $config->getStores()) && !Mage::app()->isSingleStoreMode()){

            # 2.1 Get store keys to reindex
            $stores = $object->getData($storeKeys);

            # 2.2 Get store keys to be deleted
            if ($origStores = $object->getOrigData($storeKeys)){

                $storesToDelete = $this->_helper()->getCommon()->getArrays()->findDeletedValues($origStores, $stores);

                # 2.3 Delete index rows from store indexes
                $this->_deleteFromStores($config, $object->getId(), $storesToDelete);
            }
        }

        $stores[] = '0';
        $stores = array_unique($stores);

        # 3. Reindex
        foreach ($stores as $storeId){

            try {
                if (in_array($storeId, $this->_getStores())){

                    if (!Mage::app()->isSingleStoreMode()){
                        $object->setStoreId($storeId);
                    }

                    $storeFullText = $config->getProcessor()->process($object);

                    if ($storeFullText){
                        /** @var Magpleasure_Searchcore_Model_Index $indexModel */
                        $indexModel = Mage::getModel('searchcore/index');
                        $indexModel->setStoreId($storeId);

                        if (!$isFullIndex){
                            $indexModel->loadByFewFields(array(
                                'type_id' => $config->getTypeId(),
                                'entity_id' => $object->getId(),
                            ));
                        }

                        $indexModel
                            ->setTypeId($config->getTypeId())
                            ->setEntityId($object->getId())
                            ->setDataIndex($storeFullText)
                            ->save()
                        ;

                    }

                }
            } catch (Exception $e){
                $this->_helper()->getCommon()->getException()->logException($e);
            }
        }

        return $this;
    }

    /**
     * Delete Index records form
     *
     * @param Magpleasure_Searchcore_Model_Type_Config $config
     * @param $entityId
     * @param array $storeIds
     * @return $this
     */
    protected function _deleteFromStores(Magpleasure_Searchcore_Model_Type_Config $config, $entityId, array $storeIds)
    {
        foreach ($storeIds as $storeId){
            if (in_array($storeId, $this->_getStores())){
                /** @var Magpleasure_Searchcore_Model_Index $indexModel */
                $indexModel = Mage::getModel('searchcore/index');
                $indexModel->setStoreId($storeId);

                $indexModel->loadByFewFields(array(
                    'type_id' => $config->getTypeId(),
                    'entity_id' => $entityId,
                ));

                if ($indexModel->getId()){
                    $indexModel->delete();
                }
            }
        }

        return $this;
    }

    public function deleteIndex(Mage_Core_Model_Abstract $object, Magpleasure_Searchcore_Model_Type_Config $config)
    {
        $stores = array();

        if (($storeKeys = $config->getStores()) && !Mage::app()->isSingleStoreMode()){
            $stores = $object->getData($storeKeys);
        }
        $stores[] = 0;
        $this->_deleteFromStores($config, $object->getId(), $stores);

        return $this;
    }

    public function initIndexes()
    {
        foreach ($this->_getStores() as $storeId){

            if ($this->hasStoreIndex($storeId)){
                $this->removeStoreIndex($storeId);
            }

            $this->addStoreIndex($storeId);
        }
    }

    /**
     * Add Store Search Index
     *
     * @param $storeId
     * @param bool $stopFkCheck
     */
    public function addStoreIndex($storeId, $stopFkCheck = false)
    {
        $dbHelper = $this->_helper()->getCommon()->getDatabase();
        $write = $dbHelper->getWriteConnection();

        $resultTable = $this->_getResultTableName($storeId);
        $indexTable = $this->_getIndexTableName($storeId);

        $indexTableSql = "
            CREATE TABLE IF NOT EXISTS `{$indexTable}` (
              `index_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
              `entity_id` INT(10) UNSIGNED NOT NULL,
              `type_id` SMALLINT(5) UNSIGNED NOT NULL,
              `data_index` TEXT NOT NULL,
              PRIMARY KEY (`index_id`),
              UNIQUE INDEX `UNQ_MP_SEARCH_INDEX_{$storeId}` (`entity_id`, `type_id`),
              FULLTEXT INDEX `FTI_MP_SEARCH_INDEX_{$storeId}` (`data_index`),
              FOREIGN KEY (`type_id`) REFERENCES {$dbHelper->getTableName('mp_search_type')}(`type_id`) ON UPDATE CASCADE ON DELETE CASCADE
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ";

        $resultTableSql = "
            CREATE TABLE IF NOT EXISTS `{$resultTable}` (
              `query_id` INT(10) UNSIGNED NOT NULL,
              `index_id` BIGINT(20) UNSIGNED NOT NULL,
              `relevance` DECIMAL(20,4) NOT NULL DEFAULT 0,
              PRIMARY KEY (`query_id`, `index_id`),
              INDEX `IDX_MP_SEARCH_RESULT_QUERY_{$storeId}` (`query_id`),
              INDEX `IDX_MP_SEARCH_RESULT_INDEX_{$storeId}` (`index_id`),
              FOREIGN KEY (`query_id`) REFERENCES `{$dbHelper->getTableName('mp_search_query')}`(`query_id`) ON UPDATE CASCADE ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        ";

        if ($stopFkCheck){
            $write->query("SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;");
        }

        $write->query($indexTableSql);
        $write->query($resultTableSql);

        if ($stopFkCheck){
            $write->query("SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS=0, 0, 1);");
        }
    }

    public function removeStoreIndex($storeId)
    {
        $dbHelper = $this->_helper()->getCommon()->getDatabase();

        $resultTable = $this->_getResultTableName($storeId);
        $indexTable = $this->_getIndexTableName($storeId);

        $dbHelper->dropTable($resultTable);
        $dbHelper->dropTable($indexTable);

        return $this;
    }

    public function hasStoreIndex($storeId)
    {
        $dbHelper = $this->_helper()->getCommon()->getDatabase();

        $resultTable = $this->_getResultTableName($storeId);
        $indexTable = $this->_getIndexTableName($storeId);

        return $dbHelper->isTableExists($resultTable) && $dbHelper->isTableExists($indexTable);
    }

    public function removeStoreIndexes()
    {
        foreach ($this->_getStores() as $storeId){
            $this->removeStoreIndex($storeId);
        }

        return $this;
    }

    public function setReindexRequiredFlag()
    {
        /** @var Mage_Index_Model_Process $process */
        $process = Mage::getModel('index/process')->load(self::INDEXER_PROCESS_ID, 'indexer_code');
        if ($process->getId()){
            $process->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);
        }

        return $this;
    }

    public function isManualUpdateModeEnabled()
    {
        /** @var Mage_Index_Model_Process $process */
        $process = Mage::getModel('index/process')->load(self::INDEXER_PROCESS_ID, 'indexer_code');
        if ($process->getId()){
            return $process->getMode() == Mage_Index_Model_Process::MODE_MANUAL;
        }

        return false;
    }

    /**
     * Load Absctract Collection by few key fields
     *
     * @param Mage_Core_Model_Abstract $object
     * @param array $data
     * @return Magpleasure_Common_Model_Resource_Abstract
     */
    public function loadByFewFields(Mage_Core_Model_Abstract $object, array $data)
    {
        /** @var $itemModel Magpleasure_Searchcore_Model_Index */
        $itemModel = Mage::getModel('searchcore/index');
        $itemModel->setStoreId($this->getStoreId());
        $collection = $itemModel->getCollection();

        if ($collection){
            foreach ($data as $field => $value){
                $collection->addFieldToFilter($field, $value);
            }

            foreach ($collection as $item){
                /** @var $item Mage_Core_Model_Abstract */
                if ($itemId = $item->getId()){
                    $itemModel = Mage::getModel('searchcore/index');
                    $itemModel->setStoreId($this->getStoreId());
                    $itemModel->load($item->getId());
                    $object->setData($itemModel->getData());

                    return $this;
                }
            }
        }

        return $this;
    }

    protected function _freeResultsFor($indexId)
    {
        # 1. Reset search relations

        /** @var Magpleasure_Searchcore_Model_Query $query */
        $query  = Mage::getModel('searchcore/query');
        $query->setStoreId($this->getStoreId());

        $queries = $query->getCollection();
        $queries
            ->addIndexRelatedFilter($indexId)
            ->resetQueries()
        ;

        # 2. Remove old results

        $tableName = $this->_getResultTableName($this->getStoreId());
        $write = $this->_helper()->getCommon()->getDatabase()->getWriteConnection();
        $write->beginTransaction();
        $write->delete($tableName, "index_id = '{$indexId}'");
        $write->commit();

        return $this;
    }

    /**
     * Perform actions after object delete
     *
     * @param Mage_Core_Model_Abstract $object
     * @return $this|Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterDelete(Mage_Core_Model_Abstract $object)
    {
        parent::_afterDelete($object);

        # Free result records due to
        # it can't be done using Foreign Keys
        if ($indexId = $object->getId()){
            $this->_freeResultsFor($indexId);
        }

        return $this;
    }

    /**
     * Perform actions after object save
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        parent::_afterSave($object);

        if ($indexId = $object->getId()){
            $this->_freeResultsFor($indexId);
        }

        return $this;
    }

    public function getResultTableName($storeId = null)
    {
        return $this->_getResultTableName($storeId ? $storeId : $this->_storeId);
    }

    public function getIndexTableName($storeId = null)
    {
        return $this->_getIndexTableName($storeId ? $storeId : $this->_storeId);
    }

}