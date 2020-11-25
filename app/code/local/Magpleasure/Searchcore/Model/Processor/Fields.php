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

class Magpleasure_Searchcore_Model_Processor_Fields extends Magpleasure_Searchcore_Model_Processor_Abstract
{
    /**
     * Helper
     *
     * @return Magpleasure_Searchcore_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('searchcore');
    }

    public function process(Mage_Core_Model_Abstract $object)
    {
        $data = array();

        # 1. Collect data from fields
        foreach ($this->getConfig()->getFields() as $field){

            /** @var $field Magpleasure_Searchcore_Model_Field_Default */
            if ($value = $field->getProcessableValue($object)){
                $data[] = $value;
            }
        }

        foreach ($this->getConfig()->getGetters() as $getter){
            if ($value = $object->$getter()){
                try {
                    $data[] = (string) $value;
                } catch (Exception $e) {
                    $this->_helper()->getCommon()->getException($e);
                }
            }
        }

        return implode("|", $data);
    }
}