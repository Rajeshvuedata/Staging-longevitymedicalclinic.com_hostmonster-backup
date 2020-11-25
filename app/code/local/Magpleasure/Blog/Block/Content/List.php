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

class Magpleasure_Blog_Block_Content_List extends Magpleasure_Blog_Block_Content_Abstract
{
    const CACHE_PREFIX = 'mpblog_list_';

    const PAGER_BLOCK_NAME = 'mpblog_list_pager';

    protected $_collection;

    protected $_isCategory = false;
    protected $_isTag = false;

    protected $_toolbar = null;

    protected $_cachedIds;

    protected function _construct()
    {
        parent::_construct();

        $this->addData(array(
            'cache_lifetime'    => 2600,
            'cache_tags'        => array(
                Magpleasure_Common_Helper_Cache::MAGPLEASURE_CACHE_KEY,
                'CONFIG',
            ),
            'cache_key'         => $this->getCacheKey(),
        ));

        $this->setTemplate('mpblog/list.phtml');
    }

    protected function _prepareCollectionToStart(Magpleasure_Blog_Model_Mysql4_Post_Collection $collection)
    {
        /** @var Mage_Page_Block_Html_Pager $pager */
        $pager = new Mage_Page_Block_Html_Pager();

        $page = $this->getRequest()->getParam($pager->getPageVarName()) ?
                (int)$this->getRequest()->getParam($pager->getPageVarName()) :
                1;

        $collection
            ->setPageSize($this->_helper()->getPostsLimit())
            ->setCurPage($page)
            ;

        return $this;
    }

    public function getCacheKey()
    {
        return self::CACHE_PREFIX.md5(implode($this->_getCacheParams()));
    }

    protected function _getCacheParams()
    {
        if (!$this->_cachedIds){
            Varien_Profiler::start('mpblog::cache::prepare_list_ids');
            $clonedCollection = clone $this->getCollection();
            $this->_prepareCollectionToStart($clonedCollection);
            $ids = $clonedCollection->getSelectedIds();
            $ids = count($ids) ? implode("_", $ids) : "NULL";
            $this->_cachedIds = $ids;
            Varien_Profiler::stop('mpblog::cache::prepare_list_ids');
        }

        $ids = $this->_cachedIds;

        $params = array(
            Mage::app()->getStore()->getId(),
            $ids,
        );

        if ($entityId = $this->getRequest()->getParam('id')){
            $params[] = $entityId;
        }

        return  $params;
    }

    protected function _prepareLayout()
    {
        $this->_title = $this->_helper()->getSeoTitle();
        parent::_prepareLayout();

        $this->getToolbar()
            ->setPagerObject(Mage::getModel('mpblog/list'))
            ->setLimit($this->_helper()->getPostsLimit())
            ->setCollection($this->getCollection())
            ->setTemplate('mpblog/list/pager.phtml')
        ;

        return $this;
    }

    protected function _prepareBreadcrumbs()
    {
        parent::_prepareBreadcrumbs();

        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs){
            $breadcrumbs->addCrumb('blog', array(
                'label' => $this->_helper()->getMenuLabel(),
                'title' => $this->_helper()->getMenuLabel(),
            ));
        }
    }

    public function getReadMoreUrl($post)
    {
        return $this->_helper()->_url()->getUrl($post->getId());
    }

    public function getPageHeader()
    {
        return $this->_helper()->getMenuLabel();
    }

    protected function _checkTag(Magpleasure_Blog_Model_Mysql4_Post_Collection $collection)
    {
        if (($id = $this->getRequest()->getParam('id')) && $this->getIsTag()){
            $collection->addTagFilter($id);
        }
        return $this;
    }

    protected function _checkCategory(Magpleasure_Blog_Model_Mysql4_Post_Collection $collection)
    {
		//Mage::log('_checkCategory');
        if (($id = $this->getRequest()->getParam('id')) && $this->getIsCategory()){
		//Mage::log('_checkCategory inside');
            $collection->addCategoryFilter($id);
        }
		else
		{
			$collection->filterpodcasts();
		}
        return $this;
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
            $this->_checkTag($collection);

            $this->_collection = $collection;
			
        }
        return $this->_collection;
    }

    /**
     * SEO Pager
     *
     * @return Magpleasure_Blog_Block_Content_List_Pager
     */
    public function getToolbar()
    {
        if (!$this->_toolbar){
            $toolbar = $this->getLayout()->createBlock('mpblog/content_list_pager');
            $this->getLayout()->setBlock(self::PAGER_BLOCK_NAME, $toolbar);
            $this->_toolbar = $toolbar;
        }
        return $this->_toolbar;
    }

    /**
     * Toolbar
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getToolbar()->toHtml();
    }

    public function getIsTag()
    {
        return $this->_isTag;
    }

    public function getIsCategory()
    {
        return $this->_isCategory;
    }


    public function getIsArchive()
    {
        return $this->_isArchive;
    }


    public function getMetaTitle()
    {
        return $this->_helper()->getBlogMetaTitle() ? $this->_helper()->getBlogMetaTitle() : $this->getTitle();
    }

    public function getKeywords()
    {
        return $this->_helper()->getBlogMetaTags();
    }

    public function getDescription()
    {
        return $this->_helper()->getBlogMetaDescription();
    }

    public function getColorClass()
    {
        return $this->_helper()->getIconColorClass();
    }

    public function getRssFeedUrl()
    {
        if ($this->_isCategory){
            return $this->getUrl('mpblog/rss/category', array(
                'store_id' => Mage::app()->getStore()->getId(),
                'id' => $this->getRequest()->getParam('id'),
            ));
        } elseif ($this->_isTag) {
            return $this->getUrl('mpblog/rss/tag', array(
                'store_id' => Mage::app()->getStore()->getId(),
                'id' => $this->getRequest()->getParam('id'),
            ));
        } else {
            return $this->getUrl('mpblog/rss/post', array(
                'store_id' => Mage::app()->getStore()->getId(),
            ));
        }
    }

    public function getShowRssLink()
    {
        return $this->_helper()->getRssDisplayOnList();
    }

}