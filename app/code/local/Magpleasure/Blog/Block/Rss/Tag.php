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

class Magpleasure_Blog_Block_Rss_Tag extends Magpleasure_Blog_Block_Rss_Abstract
{
    protected function _tagName()
    {
        /** @var Magpleasure_Blog_Model_Tag $tag */
        $tag = Mage::getModel('mpblog/tag');
        if ($tagId = $this->getRequest()->getParam('id')) {
            $tag->load($tagId);

            if ($tag->getId()) {
                return $tag->getName();
            }
        }

        return false;
    }

    public function getRssTitle()
    {
        $tagName = $this->_tagName();
        if ($tagName){
            return $this->_helper()->checkForPrefix($this->_helper()->__("'%s' Tag Feed", $tagName));
        } else {
            return $this->_helper()->checkForPrefix($this->_helper()->__("Tag Feed"));
        }
    }

    public function getDataCollection()
    {
        $tags = array();

        /** @var Magpleasure_Blog_Model_Mysql4_Post_Collection $collection */
        $collection = Mage::getModel('mpblog/post')->getCollection();

        if ($id = $this->getRequest()->getParam('id')) {
            $collection->addTagFilter($id);
        }

        if (!Mage::app()->isSingleStoreMode()) {
            $collection->addStoreFilter($this->getStoreId());
        }

        $collection
            ->setDateOrder()
            ->setPageSize(10)
            ->addFieldToFilter('status', Magpleasure_Blog_Model_Post::STATUS_ENABLED);

        foreach ($collection as $tag) {
            $tags[] = array(
                'title' => $tag->getTitle(),
                'link' => $tag->getPostUrl(),
                'description' => $tag->getFullContent(),
                'lastUpdate' => strtotime($tag->getUpdatedAt()),
            );
        }

        return $tags;
    }
}