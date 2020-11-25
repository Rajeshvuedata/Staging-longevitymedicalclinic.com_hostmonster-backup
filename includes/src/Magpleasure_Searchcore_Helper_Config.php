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

class Magpleasure_Searchcore_Helper_Config extends Magpleasure_Searchcore_Helper_Data
{
    const CONFIG_FILE = 'mpsearch.xml';
    const CONFIG_PATH_TYPES = 'search/types';
    const CONFIG_PATH_TYPE_KEYS = 'search/types/%s';
    const CONFIG_PATH_TYPE_VALUES = 'search/types/%s/%s';
    const CONFIG_PATH_TYPE_FIELD_TYPE = 'search/types/%s/fields/%s';

    const CACHE_KEY = 'mp_searchcore_config_types';

    protected $_config;

    protected function _getConfig()
    {
        if (!$this->_config){
            $config = Mage::getConfig()->loadModulesConfiguration(self::CONFIG_FILE);
            $this->_config = $config;
        }

        return $this->_config;
    }


    /**
     * Search Types
     *
     * @return array
     */
    public function getTypes()
    {
        $cache = $this->getCommon()->getCache();

        if (!($cachedValue = $cache->getPreparedHtml(self::CACHE_KEY))){

            $config = $this->_getConfig();
            $types = $this->getCommon()->getConfig()->getArrayFromPath(self::CONFIG_PATH_TYPES, $config);
            $cache->savePreparedHtml(self::CACHE_KEY, serialize($types));

        } else {

            try {
                $types = unserialize($cachedValue);
            } catch (Exception $e){
                $this->getCommon()->getException()->logException($e);
                $types = array();
            }
        }

        return $types;
    }

    /**
     * Type Object
     *
     * @param string $type
     * @return Varien_Object
     */
    public function getTypeConfig($type)
    {
        $config = $this->_getConfig();
        $keys = $this->getCommon()->getConfig()->getArrayFromPath(sprintf(self::CONFIG_PATH_TYPE_KEYS, $type), $config);

        $data = array();
        foreach ($keys as $key){

            if ($value = $this->getCommon()->getConfig()->getValueFromPath(sprintf(self::CONFIG_PATH_TYPE_VALUES, $type, $key), $config)){
                $data[$key] = $value;
            } else {

                $array = $this->getCommon()->getConfig()->getArrayFromPath(sprintf(self::CONFIG_PATH_TYPE_VALUES, $type, $key), $config);
                if ($array && count($array)){

                    if ($key == 'fields') {
                        $fields = array();
                        foreach ($array as $fieldName){

                            $defaultType = 'default';
                            $definedType = $this->getCommon()->getConfig()->getValueFromPath(sprintf(self::CONFIG_PATH_TYPE_FIELD_TYPE, $type, $fieldName), $config);

                            /** @var Magpleasure_Searchcore_Model_Field_Default $fieldModel */
                            $fieldModel = Mage::getModel('searchcore/field_'.($definedType ? $definedType : $defaultType));
                            $fieldModel->setKey($fieldName);

                            $fields[] = $fieldModel;
                        }
                        $data[$key] = $fields;

                    } elseif ($key = 'getters') {
                        $getters = array();
                        foreach ($array as $fieldName){
                            $getters[] = $fieldName;
                        }
                        $data[$key] = $getters;
                    } else {
                        $data[$key] = $array;
                    }



                }

            }
        }

        return $data;
    }

}