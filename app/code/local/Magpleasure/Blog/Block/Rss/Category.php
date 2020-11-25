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

class Magpleasure_Blog_Block_Rss_Category extends Magpleasure_Blog_Block_Rss_Abstract
{
    protected function _categoryName()
    {
        /** @var Magpleasure_Blog_Model_Category $category */
        $category = Mage::getModel('mpblog/category');
        if ($categoryId = $this->getRequest()->getParam('id')) {
            $category->load($categoryId);

            if ($category->getId()) {
                return $category->getName();
            }
        }

        return false;
    }

    public function getRssTitle()
    {
        $categoryName = $this->_categoryName();
        if ($categoryName){
            return $this->_helper()->checkForPrefix($this->_helper()->__("%s Feed", $categoryName));
        } else {
            return $this->_helper()->checkForPrefix($this->_helper()->__("Category Feed"));
        }
    }

    public function getDataCollection()
    {
        $posts = array();

        /** @var Magpleasure_Blog_Model_Mysql4_Post_Collection $collection */
        $collection = Mage::getModel('mpblog/post')->getCollection();

        if ($categoryId = $this->getRequest()->getParam('id')) {
            $collection->addCategoryFilter($categoryId);
        }

        if (!Mage::app()->isSingleStoreMode()) {
            $collection->addStoreFilter($this->getStoreId());
        }

        $collection
            ->setDateOrder()
            ->setPageSize(10)
            ->addFieldToFilter('status', Magpleasure_Blog_Model_Post::STATUS_ENABLED);

        foreach ($collection as $post) {
            $posts[] = array(
                'title' => $post->getTitle(),
                'link' => $post->getPostUrl(),
                'description' => $post->getFullContent(),
                'lastUpdate' => strtotime($post->getUpdatedAt()),
            );
        }

        return $posts;
    }
}