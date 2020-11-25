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

class Magpleasure_Searchcore_Model_Index extends Mage_Index_Model_Indexer_Abstract
{
    const EVENT_MATCH_RESULT_KEY = 'magpleasure_search_core_match_result';

    protected $_storeId;

    protected function _getMatchedEntites()
    {
        $entities = array();

        foreach ($this->_helper()->getTypeList() as $typeCode){
            $entities[$typeCode] = array(Mage_Index_Model_Event::TYPE_SAVE, Mage_Index_Model_Event::TYPE_DELETE);
        }

        return $entities;
    }

    /**
     * Set Store Id
     *
     * @param $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        $this->getResource()->setStoreId($storeId);
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
        parent::_construct();
        $this->_init('searchcore/index');
        $this->_matchedEntities = $this->_getMatchedEntites();
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
     * Retrieve model resource
     *
     * @return Magpleasure_Searchcore_Model_Resource_Index
     */
    public function getResource()
    {
        return $this->_getResource();
    }

    /**
     * Get Indexer name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_helper()->__("Magpleasure Search Index");
    }

    /**
     * Retrieve Indexer description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->_helper()->__('Rebuild Magpleasure Extensions Indexes used for Search Actions');
    }

    protected function _isManualUpdate()
    {
        return $this->getResource()->isManualUpdateModeEnabled();
    }

    /**
     * Register indexer required data inside event object
     *
     * @param   Mage_Index_Model_Event $event
     */
    protected function _registerEvent(Mage_Index_Model_Event $event)
    {
        $event->addNewData(self::EVENT_MATCH_RESULT_KEY, true);
    }

    /**
     * Process event based on event state data
     *
     * @param Mage_Index_Model_Event $event
     * @return $this
     */
    protected function _processEvent(Mage_Index_Model_Event $event)
    {
        /** @var Magpleasure_Common_Model_Abstract $dataObject */
        $dataObject = $event->getDataObject();
        $entityCode = $event->getEntity();

        $typeCode = $event->getType();
        $entity = $this->_helper()->getTypeByCode($entityCode);

        if ($dataObject && $entity){

            $config = $entity->getConfig();

            if ($typeCode == Mage_Index_Model_Event::TYPE_SAVE){

                $this->getResource()->reindexItem($dataObject, $config);

            } elseif ($typeCode == Mage_Index_Model_Event::TYPE_DELETE) {

                $this->getResource()->deleteIndex($dataObject, $config);
            }
        }

        return $this;
    }

    public function getCollection()
    {
        /** @var Magpleasure_Searchcore_Model_Resource_Index_Collection $collection */
        $collection = Mage::getResourceModel('searchcore/index_collection');
        $collection->setStoreId($this->getStoreId());

        return $collection;
    }

    /**
     * Load Abstract Model by few key fields
     *
     * @param array $data
     * @return Magpleasure_Common_Model_Resource_Abstract
     */
    public function loadByFewFields(array $data)
    {
        $this->getResource()->loadByFewFields($this, $data);
        return $this;
    }

    public function getResultTableName()
    {
        return $this->getResource()->getResultTableName();
    }

    public function getIndexTableName()
    {
        return $this->getResource()->getIndexTableName();

    }


}