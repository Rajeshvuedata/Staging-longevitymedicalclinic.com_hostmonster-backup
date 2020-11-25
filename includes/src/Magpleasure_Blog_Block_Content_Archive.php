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

class Magpleasure_Blog_Block_Content_Archive extends Magpleasure_Blog_Block_Content_List
{
    protected $_archive;

    protected function _construct()
    {
        $this->_isArchive = true;
        parent::_construct();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getToolbar()->setPagerObject($this->getArchive());
        return $this;
    }

    protected function _getCacheParams()
    {
        $params = parent::_getCacheParams();
        $params[] = 'archive';
        return $params;
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

            $breadcrumbs->addCrumb($this->getArchive()->getUrlKey(), array(
                'label' => $this->getTitle(),
                'title' => $this->getTitle(),
            ));
        }
    }

    public function getCollection()
    {
        if (!$this->_collection){

            /** @var $collection Magpleasure_Blog_Model_Mysql4_Post_Collection */
            $collection = parent::getCollection();

            $from = $this->getArchive()->getFromFilter();
            $to = $this->getArchive()->getToFilter();

            $collection
                ->addFromFilter($from)
                ->addToFilter($to)
            ;

            $this->_collection = $collection;
        }
        return $this->_collection;
    }

    public function getTitle()
    {
        return $this->getArchive()->getLabel();
    }

    public function getPageHeader()
    {
        return $this->getTitle();
    }

    public function getMetaTitle()
    {
        return $this->getArchive()->getMetaTitle() ? $this->getArchive()->getMetaTitle() : $this->_helper()->checkForPrefix($this->getTitle());
    }


    /**
     * Archive Model
     *
     * @return Magpleasure_Blog_Model_Archive
     */
    public function getArchive()
    {
        if (!$this->_archive){
            $archive = Mage::getModel('mpblog/archive')->load($this->getRequest()->getParam('id'));
            $this->_archive = $archive;
        }
        return $this->_archive;
    }

    public function getShowRssLink()
    {
        return false;
    }
}