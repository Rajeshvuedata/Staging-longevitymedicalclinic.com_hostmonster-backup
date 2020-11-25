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

class Magpleasure_Searchcore_Model_Observer
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

    public function updateSearchTypes()
    {
        $types = $this->_helper()->getSearchConfig()->getTypes();
        asort($types);
        $actualHash = $this->_helper()->getCommon()->getHash()->getFastMd5Hash($types);

        /** @var Magpleasure_Searchcore_Model_Type $typeModel */
        $typeModel = Mage::getModel('searchcore/type');
        $typesHash = $typeModel->getTypesHash();

        if ($actualHash != $typesHash){
            $typeModel->processTypes($types);
        }

        return $this;
    }

    public function storeSaveCommitAfter($event)
    {
        /** @var Mage_Core_Model_Store $store */
        $store = $event->getStore();

        if ($store && $store->getId()){

            try {
                /** @var Magpleasure_Searchcore_Model_Resource_Index $indexResource */
                $indexResource = Mage::getResourceModel('searchcore/index');

                if (!$indexResource->hasStoreIndex($store->getId())){
                    $indexResource->addStoreIndex($store->getId(), true);
                    $indexResource->setReindexRequiredFlag();
                }

            } catch (Exception $e){
                $this->_helper()->getCommon()->getException()->logException($e);
            }
        }

        return $this;
    }

    public function storeDeleteCommitAfter($event)
    {
        /** @var Mage_Core_Model_Store $store */
        $store = $event->getStore();

        if ($store && $store->getId()){

            try {

                /** @var Magpleasure_Searchcore_Model_Resource_Index $indexResource */
                $indexResource = Mage::getResourceModel('searchcore/index');

                if ($indexResource->hasStoreIndex($store->getId())){
                    $indexResource->removeStoreIndex($store->getId());
                }
            } catch (Exception $e){
                $this->_helper()->getCommon()->getException()->logException($e);
            }
        }

        return $this;
    }

}