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
 * @copyright  Copyright (c) 2012 MagPleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Blog_Block_Sidebar_Recentpost extends Magpleasure_Blog_Block_Sidebar_Abstract
{
    protected $_collection;

    protected $_cachedIds;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("mpblog/sidebar/recentpost.phtml");
        $this->_route = 'display_recent';

        $cacheTags = $this->getCacheTags();
        $cacheTags[] = Magpleasure_Blog_Model_Post::CACHE_TAG;
        $this->setCacheTags($cacheTags);
    }

    protected function _getPostLimit()
    {
        return $this->_helper()->getRecentPostsLimit();
    }

    protected function _getCacheParams()
    {
        if (!$this->_cachedIds){
            Varien_Profiler::start('mpblog::cache::prepare_recent_posts_ids');
            $clonedCollection = clone $this->getCollection();
            $this->_prepareCollectionToStart($clonedCollection, $this->getPostsLimit());
            $ids = $clonedCollection->getSelectedIds();
            $ids = count($ids) ? implode("_", $ids) : "NULL";
            $this->_cachedIds = $ids;
            Varien_Profiler::stop('mpblog::cache::prepare_recent_posts_ids');
        }

        $ids = $this->_cachedIds;

        $params = parent::_getCacheParams();
        $params[] = 'recent_posts';
        $params[] = $ids;

        return $params;
    }

    public function getPostsLimit()
    {
        return $this->_helper()->getRecentPostsLimit();
    }

    public function getBlockHeader()
    {
        return $this->__('Featured Post');
    }

    public function getCollection()
    {
        if (!$this->_collection){
            /** @var Magpleasure_Blog_Model_Mysql4_Post_Collection  $collection  */
            $collection = Mage::getModel('mpblog/post')->getCollection();
            if (!Mage::app()->isSingleStoreMode()){
                $collection->addStoreFilter(Mage::app()->getStore()->getId());
            }
            $collection->addFieldToFilter('status', Magpleasure_Blog_Model_Post::STATUS_ENABLED);
            $collection->setUrlKeyIsNotNull();
            $collection->setDateOrder();

            $this->_checkCategory($collection);
            $collection->setPageSize($this->getPostsLimit());

            $this->_collection = $collection;
        }
        return $this->_collection;
    }

    public function showThesis()
    {
        return $this->_helper()->getRecentPostsDisplayShort();
    }

    public function showDate()
    {
        return $this->_helper()->getRecentPostsDisplayDate();
    }

    public function renderDate($datetime)
    {
        return $this->_helper()->renderDate($datetime);
    }
}