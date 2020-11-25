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

class Magpleasure_Searchcore_Model_Query extends Magpleasure_Common_Model_Abstract
{
    const STATUS_NO = 0;
    const STATUS_YES = 1;
    const STATUS_LOCKED = 2;

    const LOCK_TIMEOUT = 300;       # 5 min
    const PROCESSED_TIMEOUT = 3600; # 1 hour

    const STATUS_FIELD = 'is_processed';

    protected $_defaultData = array(
        'is_active' => 1,
        'num_results' => 0,
        'popularity' => 0,
        'display_in_terms' => 1,
        self::STATUS_FIELD => self::STATUS_NO,
    );

    protected function _construct()
    {
        parent::_construct();
        $this->_init('searchcore/query');
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


    /**
     * Set Store Id
     *
     * @param $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->setData('store_id', $storeId);
        $this->getResource()->setStoreId($storeId);
        return $this;
    }

    /**
     * Get Store Id
     *
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->getData('store_id');
    }

    /**
     * Resource
     *
     * @return Magpleasure_Searchcore_Model_Resource_Query
     */
    public function getResource()
    {
        return parent::getResource();
    }

    /**
     * Retrieves search query object
     *
     * @param $q
     * @param null $storeId
     * @return Magpleasure_Searchcore_Model_Query
     */
    public function getQueryByQ($q, $storeId = null)
    {
        /** @var Magpleasure_Searchcore_Model_Query $query */
        $query = Mage::getModel('searchcore/query');

        if (Mage::app()->isSingleStoreMode()){
            $storeId = '0';
        } else {
            $storeId = ($storeId !== null) ? $storeId : Mage::app()->getStore()->getId();
        }

        $this->setStoreId($storeId);
        $query->setStoreId($storeId);
        $query->loadByQ($q);

        if ($query->getId()){

            return $query;
        } else {

            $query->addData($this->_defaultData);
            $query->addData(array(
                'query_text' => $q,
                'store_id' => $storeId,
            ));

            $query->save();

            return $query;
        }
    }

    protected function _getResultCollection($typeIds = null)
    {
        ///TODO Prepare Collection

        return null;
    }

    public function getResultCount()
    {
        return 0;
    }

    /**
     * Get Result Ids if it's possible to get it
     * ------------------------------------------------
     * Result Ids are Ids in index what should be used
     * to link index rows to master collection
     *
     * @param array $typeIds
     * @return array
     */
    public function getResultIds($typeIds = null)
    {
        if (!is_array($typeIds)){
            $typeIds = array($typeIds);
        }

        $this->_askForResults();

        return $this->_getResultIds($typeIds);
    }

    protected function _processResults()
    {
        $q = $this->getData('query_text');
        $this->getResource()->proceedResults($q, $this->getStoreId(), $this);

        return $this;
    }

    protected function _getResultIds(array $typeIds = null)
    {
        ///TODO In future

        return array();
    }

    public function loadByQ($q)
    {
        $this->loadByFewFields(array(
            'query_text' => $q,
            'store_id' => $this->getStoreId(),
        ));

        return $this;
    }

    /**
     * Update Processed Status
     *
     * @param $newStatus
     * @return $this
     */
    protected function _updateStatus($newStatus)
    {
        $this
            ->setData(self::STATUS_FIELD, $newStatus)
            ->save()
        ;
        return $this;
    }

    public function incPopularity()
    {
        $this->getResource()->incPopularity($this);
        return $this;
    }

    protected function _isExpired($timeout)
    {
        $timeFirst  = strtotime($this->getUpdatedAt());
        $timeSecond = time();
        $differenceInSeconds = $timeSecond - $timeFirst;
        return ($differenceInSeconds) > $timeout;
    }

    public function isProceed()
    {
        return ($this->getData(self::STATUS_FIELD) == self::STATUS_YES)
                && !$this->_isExpired(self::PROCESSED_TIMEOUT);
    }

    public function isLocked()
    {
        return $this->getData(self::STATUS_FIELD) == self::STATUS_LOCKED
                && !$this->_isExpired(self::LOCK_TIMEOUT);
    }

    public function lock()
    {
        $this->_updateStatus(self::STATUS_LOCKED);
        return $this;
    }

    public function unlock()
    {
        $this->_updateStatus(self::STATUS_YES);
        return $this;
    }

    public function reset()
    {
        # Reset proceed flag
        $this->_updateStatus(self::STATUS_NO);
        return $this;
    }

    /**
     * Query Collection
     *
     * @return Magpleasure_Searchcore_Model_Resource_Query_Collection
     */
    public function getCollection()
    {
        /** @var Magpleasure_Searchcore_Model_Resource_Query_Collection $collection */
        $collection = parent::getCollection();
        $collection->setStoreId($this->getStoreId());
        return $collection;
    }

    protected function _askForResults()
    {
        # If non proceed
        if (!$this->isProceed()){

            # Try to lock it
            if (!$this->isLocked()){

                $this->lock();

                Varien_Profiler::start('mp::serachcore::process_results');

                $this->_processResults();

                Varien_Profiler::stop('mp::serachcore::process_results');

                $this->unlock();
            }
        }

        $this->incPopularity();

        return $this;
    }

    public function applyFilterToCollection(Magpleasure_Common_Model_Resource_Collection_Abstract $collection, $typeCode)
    {
        if ($select = $collection->getSelect()){

            if ($type = $this->_helper()->getTypeByCode($typeCode)){

                $pkField = $type->getConfig()->getPkField();
                if ($pkField){

                    $this->_askForResults();

                    /** @var Magpleasure_Searchcore_Model_Index $index */
                    $index = Mage::getModel('searchcore/index');
                    $index->setStoreId($this->getStoreId());

                    $resultTable = $index->getResultTableName();
                    $indexTable = $index->getIndexTableName();
                    $queryId = $this->getId();


                    $select
                        ->joinInner(array('res' => $resultTable), "res.query_id = '{$queryId}'", array())
                        ->joinInner(array('indx' => $indexTable), "indx.index_id = res.index_id AND indx.entity_id = main_table.{$pkField}", array())
                        ->order("res.relevance ASC")
                    ;

                }
            }
        }

        return $this;
    }
}