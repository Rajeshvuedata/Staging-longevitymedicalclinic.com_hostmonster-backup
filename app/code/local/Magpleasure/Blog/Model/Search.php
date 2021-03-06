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

class Magpleasure_Blog_Model_Search extends Magpleasure_Blog_Model_Abstract implements Magpleasure_Blog_Model_Interface
{
    const SEARCH_QUERY_KEY = "mp_blog_search_query";

    protected $_posts;

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/search');
    }

    /**
     * Collection of Posts
     *
     * @return Magpleasure_Blog_Model_Mysql4_Post_Collection
     */
    public function getPosts()
    {
        if (!$this->_posts){
            /** @var Magpleasure_Blog_Model_Mysql4_Post_Collection $collection */
            $collection = Mage::getModel('mpblog/post')->getCollection();

            $collection
                ->addFieldToFilter('status', Magpleasure_Blog_Model_Post::STATUS_ENABLED)
                ->addStoreFilter(Mage::app()->getStore()->getId())
                ;

            /** @var Magpleasure_Searchcore_Model_Query $query */
            $query = Mage::registry(self::SEARCH_QUERY_KEY);
            if ($query){
                $query->applyFilterToCollection($collection, 'mpblog_post');
            }

            $this->_posts = $collection;
        }
        return $this->_posts;
    }

    public function load($id, $field=null)
    {
        # Nothing to do
        return $this;
    }

    public function getUrl($params = array(), $page = 1)
    {
        return $this->_helper()->_url($this->getStoreId())->getUrl(null, Magpleasure_Blog_Helper_Url::ROUTE_SEARCH, $page);
    }
}