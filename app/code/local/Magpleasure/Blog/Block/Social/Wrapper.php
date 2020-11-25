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

class Magpleasure_Blog_Block_Social_Wrapper extends Magpleasure_Blog_Block_Content_Post
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mpblog/social/wrapper.phtml');

    }

    protected function _getCacheParams()
    {
        $params = parent::_getCacheParams();
        $params[] = 'social_wrapper';
        return $params;
    }

    public function isGooglePlusEnabled()
    {
        return (in_array('googleplus', $this->_helper()->getSocialNetworks()));
    }

    public function isFacebookPlusEnabled()
    {
        return (in_array('facebook', $this->_helper()->getSocialNetworks()));
    }

    public function getPostTitle()
    {
        return $this->getPost()->getTitle();
    }

    public function getPostMetaDescription()
    {
        return $this->getPost()->getMetaDescription();
    }

    public function getPostUrl()
    {
        return $this->getPost()->getPostUrl();
    }

}