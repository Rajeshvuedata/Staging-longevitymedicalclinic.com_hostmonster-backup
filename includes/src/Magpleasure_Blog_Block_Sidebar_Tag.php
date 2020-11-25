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

class Magpleasure_Blog_Block_Sidebar_Tag extends Magpleasure_Blog_Block_Sidebar_Abstract
{
    protected $_collection;

    protected $_sizes = array(
        1 => '11px',
        2 => '11px',
        3 => '14ox',
        4 => '16px',
        5 => '19px',
        6 => '22px',
        7 => '26px',
        8 => '28px',
        9 => '32px',
        10 => '34px',
    );

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("mpblog/sidebar/tag.phtml");
        $this->_route = 'use_tags';
    }

    protected function _getCacheParams()
    {
        $params = parent::_getCacheParams();
        $params[] = 'tags';

        return $params;
    }

    public function getCollection()
    {
        if (!$this->_collection){
            /** @var Magpleasure_Blog_Model_Mysql4_Tag_Collection  $collection  */
            $collection = Mage::getModel('mpblog/tag')->getCollection();
            $store = Mage::app()->isSingleStoreMode() ? null : Mage::app()->getStore()->getId();
            $collection
                ->addWieghtData($store)
                ->setMinimalPostCountFilter($this->_helper()->getTagsMinimalPostCount())
                ->setPostStatusFilter(Magpleasure_Blog_Model_Post::STATUS_ENABLED)
                ->setNameOrder()
                ;

            $this->_collection = $collection;
        }
        return $this->_collection;
    }

    public function getMtEnabled()
    {
        return $this->_helper()->getTagsMtEnabled();
    }

    public function getMtPath()
    {
        return Mage::getBaseUrl('js')."mpblog/wp-cumulus/tagcloud.swf";
    }

    public function getMtWidth()
    {
        return $this->_helper()->getTagsMtWidth();
    }

    public function getMtHeight()
    {
        return $this->_helper()->getTagsMtHeight();
    }

    public function getMtBackground()
    {
        return $this->_helper()->getTagsMtBackground();
    }

    public function getMtTextColor()
    {
        return $this->_helper()->getTagsMtTextcolor();
    }

    public function getMtTextColor2()
    {
        return $this->_helper()->getTagsMtTextcolor2();
    }

    public function getMtHiColor()
    {
        return $this->_helper()->getTagsMtHiColor();
    }

    protected function _getTagSize($tagType)
    {
        return $this->_sizes[$tagType];
    }

    public function getMtTagsHtml()
    {
        $tags = "";
        foreach ($this->getCollection() as $tag){
            $url = $tag->getTagUrl();
            $size = $this->_getTagSize($tag->getTagType());
            $name = $tag->getName();
            $title = $this->__("%s Topics", $tag->getPostCount());
            $tags .= "<a href='{$url}' style='font-size: {$size};' title='{$title}'>{$name}</a>";

        }
        return urlencode("<tags>".$tags."</tags>");
    }

    public function isActive(Magpleasure_Blog_Model_Tag $tag)
    {
        if (
            ($this->getRequest()->getModuleName() == "mpblog") &&
            ($this->getRequest()->getControllerName() == "index") &&
            ($this->getRequest()->getActionName() == "tag") &&
            ($this->getRequest()->getParam('id') == $tag->getTagId())
        ){
            return true;
        } else {
            return false;
        }
    }
}