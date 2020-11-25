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

class Magpleasure_Blog_Block_Rss_Post extends Magpleasure_Blog_Block_Rss_Abstract
{
    public function getRssTitle()
    {
        return $this->_helper()->checkForPrefix($this->_helper()->__("Post Feed"));
    }

    public function getDataCollection()
    {
        $posts = array();

        /** @var Magpleasure_Blog_Model_Mysql4_Post_Collection $collection  */
        $collection = Mage::getModel('mpblog/post')->getCollection();

        if (!Mage::app()->isSingleStoreMode()){
            $collection->addStoreFilter($this->getStoreId());
        }

        $collection
            ->setDateOrder()
            ->setPageSize(10)
            ->addFieldToFilter('status', Magpleasure_Blog_Model_Post::STATUS_ENABLED)
            ;

        foreach ($collection as $post){
            $posts[] = array(
                'title'         => $post->getTitle(),
                'link'          => $post->getPostUrl(),
                'description'   => $post->getFullContent(),
                'lastUpdate' 	=> strtotime($post->getUpdatedAt()),
            );
        }

        return $posts;
    }


}