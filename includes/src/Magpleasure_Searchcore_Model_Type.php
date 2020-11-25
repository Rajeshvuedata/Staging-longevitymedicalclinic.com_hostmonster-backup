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

class Magpleasure_Searchcore_Model_Type extends Magpleasure_Common_Model_Abstract
{
    protected $_config;

    protected function _construct()
    {
        parent::_construct();
        $this->_init('searchcore/type');
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

    public function getTypesHash()
    {
        Varien_Profiler::start('mp::searchcore::type_hash');

        /** @var Magpleasure_Searchcore_Model_Resource_Type_Collection $collection */
        $collection = $this->getCollection();
        $collection->setOrder('type_code', Varien_Db_Select::SQL_ASC);
        $types = $collection->getColumnValues('type_code');

        Varien_Profiler::stop('mp::searchcore::type_hash');

        return $this->_helper()->getCommon()->getHash()->getFastMd5Hash($types);
    }

    public function processTypes(array $types)
    {

        # Check Existence Types
        foreach ($types as $typeCode){

            /** @var Magpleasure_Searchcore_Model_Type $type */
            $type = Mage::getModel('searchcore/type');
            $type->load($typeCode, 'type_code');

            if (!$type->getId()){
                $type
                    ->setTypeCode($typeCode)
                    ->save()
                    ;
            }
        }

        # Remove not existence Types

        /** @var Magpleasure_Searchcore_Model_Resource_Type_Collection $collection */
        $collection = $this->getCollection();
        $collection->addFieldToFilter('type_code', array('nin' => $types));

        $collection->flushSelected();

        return $this;
    }

    public function getConfig()
    {
        if (!$this->_config){
            /** @var Magpleasure_Searchcore_Model_Type_Config $config */
            $config = Mage::getModel('searchcore/type_config');
            $config->loadByCode($this->getTypeCode());
            $config->setTypeId($this->getId());

            $this->_config = $config;
        }
        return $this->_config;
    }
}