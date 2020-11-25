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

class Magpleasure_Blog_Block_Rss_Wrapper extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mpblog/rss/wrapper.phtml');
    }

    public function isBlogPage()
    {
        return ('mpblog' == $this->getRequest()->getModuleName());
    }

    /**
     * Helper
     *
     * @return Magpleasure_Blog_Helper_Data
     */
    public function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function getPostFeedUrl()
    {
        $params = array();
        if (!Mage::app()->isSingleStoreMode()){
            $params['store_id'] = Mage::app()->getStore()->getId();
        }
        return $this->getUrl('mpblog/rss/post', $params);
    }

    public function getCommentFeedUrl()
    {
        $params = array();
        if (!Mage::app()->isSingleStoreMode()){
            $params['store_id'] = Mage::app()->getStore()->getId();
        }
        return $this->getUrl('mpblog/rss/comment', $params);
    }

    public function getCategoryFeedUrl()
    {
        $params = array('id'=>$this->getRequest()->getParam('id'));
        if (!Mage::app()->isSingleStoreMode()){
            $params['store_id'] = Mage::app()->getStore()->getId();
        }
        return $this->getUrl('mpblog/rss/category', $params);
    }

    public function checkForPrefix($title)
    {
        return $this->_helper()->checkForPrefix($title);
    }

    public function getCategoryName()
    {
        if ($this->isCategoryPage()){
            /** @var Magpleasure_Blog_Model_Category $category */
            $category = Mage::getModel('mpblog/category');
            if ($categoryId = $this->getRequest()->getParam('id')){
                $category->load($categoryId);

                if ($category->getId()){
                    return $category->getName();
                }
            }
        }
        return false;
    }

    public function isCategoryPage()
    {
        return $this->getRequest()->getActionName() == 'category';
    }
}