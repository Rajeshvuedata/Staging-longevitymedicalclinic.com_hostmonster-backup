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

class Magpleasure_Blog_Model_Tag extends Magpleasure_Blog_Model_Abstract implements Magpleasure_Blog_Model_Interface
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/tag');
    }

    /**
     * Link Tag with Post
     *
     * @param $postId
     * @return Magpleasure_Blog_Model_Tag
     */
    public function linkWith($postId)
    {
        $this->getResource()->linkWith($this, $postId);
        return $this;
    }

    /**
     * Unlink Tag with Post
     *
     * @param $postId
     * @return Magpleasure_Blog_Model_Tag
     */
    public function unlinkWith($postId)
    {
        $this->getResource()->unlinkWith($this, $postId);
        return $this;
    }

    public function getTagUrl($page = 1)
    {
        return $this->_helper()->_url($this->getStoreId())->getUrl($this->getId(), Magpleasure_Blog_Helper_Url::ROUTE_TAG, $page);
    }

    public function getUrl($params = array(), $page = 1)
    {
        return $this->getTagUrl($page);
    }
}