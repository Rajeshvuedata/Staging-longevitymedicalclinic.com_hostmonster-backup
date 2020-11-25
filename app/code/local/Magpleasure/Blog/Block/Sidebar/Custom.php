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

class Magpleasure_Blog_Block_Sidebar_Custom extends Magpleasure_Blog_Block_Sidebar_Recentpost
{
    protected function _construct()
    {
        if ($transferedData = Mage::registry(Magpleasure_Blog_Block_Custom::TRANSFER_KEY)){
            foreach ($transferedData as $key => $value){
                $this->setData($key, $value);
            }
        }

        parent::_construct();
    }

    protected function _getCacheParams()
    {
        $this->_keysToCache = array(
            'category_id',
            'display_short',
            'record_limit',
            'display_date',
        );

        $params = parent::_getCacheParams();
        $params[] = 'custom';

        return $params;
    }


    public function showThesis()
    {
        return $this->getData('display_short');
    }

    public function showDate()
    {
        return $this->getData('display_date');
    }

    public function getDisplay()
    {
        return true;
    }

    public function getPostsLimit()
    {
        return $this->getData('record_limit');
    }

    public function getCategoryId()
    {
        if (($categoryId = $this->getData('category_id')) && ($categoryId !== '-')){
            return $categoryId;
        }
        return false;
    }

    public function getBlockHeader()
    {
        if ($this->getCategoryId()){
            /** @var Magpleasure_Blog_Model_Category $category  */
            $category = Mage::getModel('mpblog/category');
            if (!Mage::app()->isSingleStoreMode()){
                $category->setStore(Mage::app()->getStore()->getId());
            }
            $category->load($this->getCategoryId());
            return $this->escapeHtml($category->getName());
        }
        return parent::getBlockHeader();
    }

    protected function _checkCategory($collection)
    {
        if ($this->getCategoryId()){
            /** @var Magpleasure_Blog_Model_Mysql4_Post_Collection $collection */
            $collection->addCategoryFilter($this->getCategoryId());
        }
        return $this;
    }


}