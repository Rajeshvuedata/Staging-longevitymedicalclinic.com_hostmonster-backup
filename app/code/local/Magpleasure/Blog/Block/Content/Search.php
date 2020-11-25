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

class Magpleasure_Blog_Block_Content_Search extends Magpleasure_Blog_Block_Content_List
{
    protected $_search;

    protected function _construct()
    {
        $this->_isSearch = true;
        parent::_construct();
    }

    protected function _getCacheParams()
    {
        $params = parent::_getCacheParams();
        $params[] = 'search';
        $params[] = $this->getRequest()->getParam('query') ? $this->getRequest()->getParam('query') : 'NULL';

        return $params;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getToolbar()
            ->setPagerObject($this->getSearch())
            ->setUrlPostfix(sprintf("?query=%s", $this->getRequest()->getParam('query')))
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
                'link' =>  $this->_helper()->_url()->getUrl(),
            ));

            $breadcrumbs->addCrumb($this->getSearch()->getUrlKey(), array(
                'label' => $this->getTitle(),
                'title' => $this->getTitle(),
            ));
        }
    }

    public function getCollection()
    {
        if (!$this->_collection){

            /** @var $collection Magpleasure_Blog_Model_Mysql4_Post_Collection */
            $collection = $this->getSearch()->getPosts();
            $this->_collection = $collection;
        }
        return $this->_collection;
    }

    protected function _getQueryText()
    {
        return $this->getRequest()->getParam('query');
    }

    public function getTitle()
    {
        return $this->__("Search results for '%s'", $this->escapeHtml($this->_getQueryText()));
    }

    public function getPageHeader()
    {
        return $this->getTitle();
    }

    public function getMetaTitle()
    {
        return $this->_helper()->checkForPrefix($this->getTitle());
    }

    public function getDescription()
    {
        return $this->__("There are following posts founded for the search request '%s'", $this->escapeHtml($this->_getQueryText()));
    }

    public function getKeywords()
    {
        return $this->escapeHtml($this->_getQueryText());
    }

    /**
     * Archive Model
     *
     * @return Magpleasure_Blog_Model_Search
     */
    public function getSearch()
    {
        if (!$this->_search){
            $search = Mage::getModel('mpblog/search');
            $this->_search = $search;
        }
        return $this->_search;
    }

    public function getShowRssLink()
    {


        return false;
    }
}