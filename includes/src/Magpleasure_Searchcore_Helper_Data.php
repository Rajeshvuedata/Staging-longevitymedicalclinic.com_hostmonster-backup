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

class Magpleasure_Searchcore_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CACHE_KEY_SEARCHABLE_TYPE_PREFIX = 'mp_searchcore_search_type_';

    /**
     * Common Helper
     *
     * @return Magpleasure_Common_Helper_Data
     */
    public function getCommon()
    {
        return Mage::helper('magpleasure');
    }

    /**
     * Search Config
     *
     * @return Magpleasure_Searchcore_Helper_Config
     */
    public function getSearchConfig()
    {
        return Mage::helper('searchcore/config');
    }

    protected function _getTypeCollection()
    {
        return Mage::getModel('searchcore/type')->getCollection();
    }

    public function getTypeList()
    {
        $typeList = array();
        foreach ($this->_getTypeCollection() as $item){
            $typeList[] = $item->getTypeCode();
        }
        return $typeList;
    }

    /**
     * Type by Code
     *
     * @param $typeCode
     * @return bool|Magpleasure_Searchcore_Model_Type
     */
    public function getTypeByCode($typeCode)
    {
        /** @var Magpleasure_Searchcore_Model_Type $type */
        $type = Mage::getModel('searchcore/type');
        $type->load($typeCode, 'type_code');
        return $type->getId() ? $type : false;
    }

    protected function _getSearchableTypeId($modelName)
    {
        $cacheKey = self::CACHE_KEY_SEARCHABLE_TYPE_PREFIX.md5($modelName);
        $typeId = $this->getCommon()->getCache()->getPreparedValue($cacheKey);

        if ($typeId === null){

            $typeId = false;

            $collection = $this->_getTypeCollection();
            foreach ($collection as $type){
                /** @var $type Magpleasure_Searchcore_Model_Type */

                /** @var Magpleasure_Searchcore_Model_Type_Config $typeConfig */
                $typeConfig = $type->getConfig();

                if ($typeConfig->getModel() == $modelName){
                    $typeId = $type->getId();
                    $this->getCommon()->getCache()->savePreparedValue($cacheKey, $typeId);
                    return $typeId;
                }
            }

            $this->getCommon()->getCache()->savePreparedValue($cacheKey, false);
        }

        return $typeId ? $typeId : false;
    }


    public function getSearchableType($modelName)
    {
        if ($typeId = $this->_getSearchableTypeId($modelName)){
            /** @var Magpleasure_Searchcore_Model_Type $type */
            $type = Mage::getModel('searchcore/type')->load($typeId);
            return $type;
        }

        return false;
    }
}