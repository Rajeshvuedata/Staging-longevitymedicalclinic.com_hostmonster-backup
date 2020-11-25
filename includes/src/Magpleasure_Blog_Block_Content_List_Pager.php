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

class Magpleasure_Blog_Block_Content_List_Pager extends Mage_Page_Block_Html_Pager
{
    /** @var Magpleasure_Blog_Model_Interface */
    protected $_object = null;

    protected $_urlPostfix = null;

    public function setPagerObject(Magpleasure_Blog_Model_Interface $object)
    {
        $this->_object = $object;
        return $this;
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

    /**
     * Pager Object
     *
     * @return Magpleasure_Blog_Model_Interface
     */
    public function getPagerObject()
    {
        return $this->_object;
    }

    public function getPageUrl($page)
    {
        return $this->getPagerObject()->getUrl(null, $page).$this->getUrlPostfix();
    }

    public function isOldStyle()
    {
        return ($this->_helper()->getIconStyle() == 'old');
    }

    public function getColorClass()
    {
        return $this->_helper()->getIconColorClass();
    }

    /**
     * Get Url Postfix
     *
     * @return null
     */
    public function getUrlPostfix()
    {
        return $this->_urlPostfix;
    }

    /**
     * Set URL postfix
     *
     * @param $urlPostfix
     * @return $this
     */
    public function setUrlPostfix($urlPostfix)
    {
        $this->_urlPostfix = $urlPostfix;
        return $this;
    }

    /**
     * Return current page
     *
     * @return int
     */
    public function getCurrentPage()
    {
        if (is_object($this->_collection)) {
            return $this->_collection->getCurPage();
        }

        $pageNum = (int) $this->getRequest()->getParam($this->getPageVarName());
        return $pageNum ? $pageNum : 1;
    }
}