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

class Magpleasure_Blog_Block_Content_Post extends Magpleasure_Blog_Block_Content_Abstract
{
    const CACHE_PREFIX = 'mpblog_post_';

    protected $_post;
    protected $_cacheParams = array();

    protected function _construct()
    {
        $this->addData(array(
            'cache_lifetime'    => 2600,
            'cache_tags'        => array(
                                        Magpleasure_Common_Helper_Cache::MAGPLEASURE_CACHE_KEY,
                                        'CONFIG',
                                        Magpleasure_Blog_Model_Post::CACHE_TAG
                                    ),
            'cache_key'         => $this->getCacheKey(),
        ));

        parent::_construct();

        $this->setTemplate('mpblog/post.phtml');
    }

    public function getCacheKey()
    {
        return self::CACHE_PREFIX.md5(implode($this->_getCacheParams()));
    }

    protected function _getCacheParams()
    {
        $dynamicCommentIds = $this->_helper()->getCommon()->getCookie()->getAllFromCookie($this->_helper()->getDynamicCookieName());

        $params = array(
            Mage::app()->getStore()->getId(),
            $this->getPost()->getId(),
            str_replace(" ", "_", $this->getPost()->getRecentlyCommentedAt()),
            str_replace(" ", "_", $this->getPost()->getUpdatedAt()),
            implode("_", $dynamicCommentIds),
        );

        return  $params;
    }

    /**
     * Post
     * @return Magpleasure_Blog_Model_Post
     */
    public function getPost()
    {
        if (!$this->_post){
            if ($postId = $this->getRequest()->getParam('id')){
                /** @var Magpleasure_Blog_Model_Post $post  */
                $post = Mage::getModel('mpblog/post');
                if (!Mage::app()->isSingleStoreMode()){
                    $post->setStore(Mage::app()->getStore()->getId());
                }
                $post->load($postId);
                $this->_post = $post;
            } else {
                Mage::throwException($this->__("Unknown post id."));
            }
        }
        return $this->_post;
    }

    protected function _prepareLayout()
    {
        $this->_title = $this->getPost()->getTitle();
        parent::_prepareLayout();
        return $this;
    }

    public function getMetaTitle()
    {
        return $this->getPost()->getMetaTitle() ? $this->getPost()->getMetaTitle() : $this->_helper()->checkForPrefix($this->getPost()->getTitle());
    }

    public function getDescription()
    {
        return $this->getPost()->getMetaDescription();
    }

    public function getKeywords()
    {
        return $this->getPost()->getMetaTags();
    }

    protected function _prepareBreadcrumbs()
    {
        parent::_prepareBreadcrumbs();

        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs){
            $breadcrumbs->addCrumb('blog', array(
                'label' => $this->_helper()->getMenuLabel(),
                'title' => $this->_helper()->getMenuLabel(),
                'link' => $this->_helper()->_url()->getUrl(),
            ));

            $breadcrumbs->addCrumb('post', array(
                'label' => $this->getTitle(),
                'title' => $this->getTitle(),
            ));
        }
    }

    public function getCommentsHtml()
    {
        return $this->getChildHtml('mpblog_comments');
    }

    public function getSocialHtml()
    {
        return $this->getChildHtml('mpblog_social');
    }

    public function getColorClass()
    {
        return $this->_helper()->getIconColorClass();
    }

    public function getShowPrintLink()
    {
        return $this->_helper()->getShowPrintLink();
    }
}