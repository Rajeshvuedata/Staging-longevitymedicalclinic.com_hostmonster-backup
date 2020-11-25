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

class Magpleasure_Searchcore_Model_Type_Config extends Varien_Object
{
    protected $_isLoaded = false;
    protected $_typeId;

    /**
     * Type Id
     *
     * @param $typeId
     * @return bool
     */
    public function setTypeId($typeId)
    {
        $this->_typeId = $typeId;
        return false;
    }

    /**
     * Type Id
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->_typeId;
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

    public function getId()
    {
        return !!$this->_isLoaded;
    }

    public function loadByCode($typeCode)
    {
        if ($typeCode){
            $data = $this->_helper()->getSearchConfig()->getTypeConfig($typeCode);
            $this->setData($data);
        }

        return $this;
    }

    /**
     * Model Name
     *
     * @return string
     */
    public function getModel()
    {
        return $this->getData('model');
    }

    public function getFields()
    {
        $fields = $this->getData('fields');
        if (is_array($fields)){
            return $fields;
        }

        return array();
    }

    public function getGetters()
    {
        $getters = $this->getData('getters');
        if (is_array($getters)){
            return $getters;
        }

        return array();
    }


    /**
     *
     *
     * @return bool|object
     */
    public function getTargetResource()
    {
        if ($modelName = $this->getModel()){
            $collection = Mage::getResourceModel($modelName);

            return $collection;
        }

        return false;
    }

    public function getProcessor()
    {
        $processorName = $this->getData('processor');

        /** @var Magpleasure_Searchcore_Model_Processor_Abstract $processor */
        $processor = Mage::getSingleton("searchcore/processor_{$processorName}");
        $processor->setConfig($this);

        return $processor;

    }
}