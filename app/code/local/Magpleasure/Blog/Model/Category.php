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

class Magpleasure_Blog_Model_Category extends Magpleasure_Blog_Model_Abstract implements Magpleasure_Blog_Model_Interface
{
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    const CACHE_TAG = 'MPBLOG_CATEGORY';

    protected $_storeId;

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/category');
    }

    public function getOptionsArray()
    {
        return array(
            self::STATUS_ENABLED => $this->_helper()->__("Enabled"),
            self::STATUS_DISABLED => $this->_helper()->__("Disabled"),
        );
    }

    public function toOptionArray()
    {
        $result = array();
        foreach ($this->getOptionsArray() as $value=>$label){
            $result[] = array('value'=>$value, 'label'=>$label);
        }
        return $result;
    }

    public function getCategoryList($storeIds = null)
    {
        /** @var Magpleasure_Blog_Model_Mysql4_Category_Collection $collection */
        $collection = $this->getCollection();

        if ($storeIds && is_array($storeIds) && count($storeIds)){
            $collection->addStoreFilter($storeIds);
        }

        $cats = array();
        foreach ($collection as $category){
            $cats[] = array('value'=>$category->getId(), 'label'=>$category->getName());
        }

        return $cats;
    }

    public function getCategoryUrl($page = 1)
    {
        return $this->_helper()->_url($this->getStoreId())->getUrl($this->getId(), Magpleasure_Blog_Helper_Url::ROUTE_CATEGORY, $page);
    }

    public function setStore($storeId)
    {
        if ($storeId){
            $this->_storeId = $storeId;
        }
    }

    public function getIsActive()
    {
        if (
            (Mage::app()->getRequest()->getRouteName() == 'mpblog') &&
            (Mage::app()->getRequest()->getActionName() == 'category') &&
            (Mage::app()->getRequest()->getParam('id') == $this->getId())

        ){
            return true;
        }
        return false;
    }

    public function duplicate()
    {
        $data = array();
        foreach ($this->getData() as $key => $value){
            if (!in_array($key, array('category_id', 'created_at'))){
                $data[$key] = $this->getData($key);
            }
        }

        $newCategory = Mage::getModel('mpblog/category');
        $newCategory
            ->addData($data)
            ->setStatus(self::STATUS_DISABLED)
            ->save()
        ;
        return $newCategory;
    }

    public function getUrl($params = array(), $page = 1)
    {
        return $this->getCategoryUrl($page);
    }

    public function getMaxSortOrder($storeId = null)
    {

        /** @var Magpleasure_Blog_Model_Mysql4_Category_Collection $collection */
        $collection = $this->getCollection();

        if ($storeId){
            if (!is_array($storeId)){
                $storeId = array($storeId);
            }

            $collection->addStoreFilter($storeId);
        }

        ///TODO Insert AdminGWS support there

        $sortOrders = $collection->getSortOrders();
        return count($sortOrders) ? max($sortOrders) : 0;
    }
}