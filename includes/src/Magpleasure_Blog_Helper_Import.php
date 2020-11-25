<?php
/**
 * MagPleasure Ltd.
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
 * MagPleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   MagPleasure
 * @package    Magpleasure_Blog
 * @version    1.2.3
 * @copyright  Copyright (c) 2012-2013 MagPleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Blog_Helper_Import extends Mage_Core_Helper_Data
{
    /**
     * Helper
     *
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    /**
     * Prepare data by mask
     * @param array $mask
     * @param array $source
     * @return array
     */
    protected function _prepareDataFromArray(array $mask, array $source)
    {
        $data = array();
        foreach ($mask as $sourceKey => $targetKey){
            if (is_array($targetKey)){
                foreach ($targetKey as $targetSubKey){
                    $data[$targetSubKey] = $source[$sourceKey];
                }
            } else {
                $data[$targetKey] = $source[$sourceKey];
            }
        }
        return $data;
    }

    /**
     * Prepare data by mask
     * @param array $mask
     * @param Mage_Core_Model_Abstract $source
     * @return array
     */
    protected function _prepareData(array $mask, Mage_Core_Model_Abstract $source)
    {
        $data = array();
        foreach ($mask as $sourceKey => $targetKey){
            if (is_array($targetKey)){
                foreach ($targetKey as $targetSubKey){
                    $data[$targetSubKey] = $source->getData($sourceKey);
                }
            } else {
                $data[$targetKey] = $source->getData($sourceKey);
            }
        }
        return $data;
    }

   /**
    * Import Records from AW_Blog
    * (Retrives 0 if success)
    *
    * @param bool $verbose
    * @param array $data
    * @return bool|string
    */
    public function importAwblog($verbose = false, $data = array())
    {
        /** @var  Magpleasure_Blog_Helper_Import_Awblog $importer  */
        $importer = Mage::helper('mpblog/import_awblog');
        try {
            $importer->import($verbose, $data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return false;
    }

    public function importWordpress($verbose = false, $data = array())
    {
        /** @var  Magpleasure_Blog_Helper_Import_Wordpress $importer  */
        $importer = Mage::helper('mpblog/import_wordpress');
        try {
            $importer->import($verbose, $data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return false;
    }

}