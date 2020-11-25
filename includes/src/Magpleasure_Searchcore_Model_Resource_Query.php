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

class Magpleasure_Searchcore_Model_Resource_Query extends Magpleasure_Common_Model_Resource_Abstract
{
    protected $_storeId;

    const PATTERN_TABLE_NAME = 'mp_search_result_%s';

    protected function _construct()
    {
        parent::_construct();
        $this->_init('searchcore/query', 'query_id');
        $this->setUseUpdateDatetimeHelper(true);
    }

    /**
     * Get Store
     *
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * Set Store
     *
     * @param $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    public function proceedResults($q, $storeId, Magpleasure_Searchcore_Model_Query $query)
    {
        # 1. Find ned results from index

        /** @var Magpleasure_Searchcore_Model_Index $indexModel */
        $indexModel = Mage::getModel('searchcore/index');
        $indexModel->setStoreId($storeId);
        $indexCollection = $indexModel->getCollection();
        $indexCollection->addSearchFilter($q);

        Varien_Profiler::start('mp::searchcore::search_in_index');
        $indexIds = $indexCollection->getAllIds();
        Varien_Profiler::stop('mp::searchcore::search_in_index');

        # 2. Lock result table
        Varien_Profiler::start('mp::searchcore::refresh_results_table');
        $write = $this->_commonHelper()->getDatabase()->getWriteConnection();
        $write->beginTransaction();

        # 3. Remove old results
        $resultTable = $this->_commonHelper()->getDatabase()->getTableName(sprintf(self::PATTERN_TABLE_NAME, $storeId));
        $queryId = $query->getId();
        $write->delete($resultTable, "`query_id` = '{$queryId}'");

        # 4. Fill new results

        $bindData = array();
        $rev = 1;
        foreach ($indexIds as $indexId){
            $bindData[] = array(
                'query_id' => $queryId,
                'index_id' => $indexId,
                'relevance' => $rev++,
            );
        }

        $query->setNumResults(count($bindData));

        if (count($bindData)){
            $write->insertMultiple($resultTable, $bindData);
        }

        # 5. Unlock results table
        $write->commit();
        Varien_Profiler::stop('mp::searchcore::refresh_results_table');

        return $this;
    }

    public function incPopularity(Magpleasure_Searchcore_Model_Query $query)
    {
        if ($queryId = $query->getId()){

            $queryTable = $this->getMainTable();
            $write = $this->_commonHelper()->getDatabase()->getWriteConnection();
            $write->beginTransaction();
            $write->update($queryTable, array('popularity' => new Zend_Db_Expr("`popularity` + 1")), "query_id = '{$queryId}'");
            $write->commit();
        }

        return $this;
    }

}