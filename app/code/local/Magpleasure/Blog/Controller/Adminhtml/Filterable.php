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

class Magpleasure_Blog_Controller_Adminhtml_Filterable extends Magpleasure_Common_Controller_Adminhtml_Action
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function getAppliedStoreId()
    {
        return $this->_helper()->getStoreHelper()->getAppliedStoreId();
    }

    public function isStoreFilterApplied()
    {
        return $this->_helper()->getStoreHelper()->isStoreFilterApplied();
    }

    protected function _getCommonParams()
    {
        return $this->_helper()->getStoreHelper()->getCommonParams();
    }

    protected function _prepareStoreFilter()
    {
        if (Mage::app()->isSingleStoreMode()){

            # Nothing to do
            return $this;
        }

        $request = $this->getRequest();
        $storeHelper = $this->_helper()->getStoreHelper();

        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();
        $route = "{$moduleName}/$controllerName/$actionName";
        $params = $request->getParams();

        if (!$request->getParam('store') && $storeHelper->getSavesStoreId()) {

            $params['store'] = $storeHelper->getSavesStoreId();

            $this->_redirectUrl($this->getUrl($route, $params));
            $this->getResponse()->sendHeaders();
            exit;

        } elseif ($request->getParam('store') == Magpleasure_Blog_Helper_Data_Store::RESET_VALUE) {

            $storeHelper->clearSavedStoreId();

            if (isset($params['store'])){
                unset($params['store']);
            }

            $this->_redirectUrl($this->getUrl($route, $params));
            $this->getResponse()->sendHeaders();
            exit;
        } elseif ($storeId = $request->getParam('store')){

            $storeHelper->saveStoreId($storeId);
        }

        return $this;
    }
}