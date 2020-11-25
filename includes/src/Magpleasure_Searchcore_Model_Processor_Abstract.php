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

abstract class Magpleasure_Searchcore_Model_Processor_Abstract extends Varien_Object
{
    protected $_config;

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
     * Set Config
     *
     * @param Magpleasure_Searchcore_Model_Type_Config $config
     * @return $this
     */
    public function setConfig($config)
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * Get Config
     *
     * @return Magpleasure_Searchcore_Model_Type_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    abstract public function process(Mage_Core_Model_Abstract $object);
}