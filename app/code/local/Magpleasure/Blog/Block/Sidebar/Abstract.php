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

class Magpleasure_Blog_Block_Sidebar_Abstract extends Mage_Core_Block_Template
{
    const CACHE_PREFIX = 'mpblog_sidebar_';

    /**
     * Route to get configuration
     *
     * @var string
     */
    protected $_route = 'abstract';

    /**
     * Place to define displaying
     *
     * @var string
     */
    protected $_place;

    protected $_keysToCache = false; # Can be array

    /**
     * Helper
     *
     * @return Magpleasure_Blog_Helper_Data
     */
    public function _helper()
    {
        return Mage::helper('mpblog');
    }

    protected function _isRequestMatchParams($moduleName, $controller, $action)
    {
        $request = $this->getRequest();
        return
            $request->getModuleName() == $moduleName &&
            $request->getControllerName() == $controller &&
            $request->getActionName() == $action ;
    }

    protected function _prepareCollectionToStart(Magpleasure_Blog_Model_Mysql4_Post_Collection $collection, $limit)
    {
        $collection
            ->setPageSize($limit)
            ->setCurPage(1)
        ;

        return $this;
    }

    protected function _construct()
    {
        parent::_construct();

        $this->addData(array(
            'cache_lifetime'    => 2600,
            'cache_tags'        => array(
                Magpleasure_Common_Helper_Cache::MAGPLEASURE_CACHE_KEY,
                'CONFIG',
            ),
            'cache_key'         => $this->getCacheKey(),
        ));
    }

    protected function _dataHash()
    {
        if ($this->_keysToCache && is_array($this->_keysToCache)){
            $values = array();
            foreach ($this->_keysToCache as $key){
                if ($this->getData($key)){
                    $values[] = $this->getData($key);
                }
            }
            return implode("_", $values);
        }
        return false;
    }

    public function getCacheKey()
    {
        return self::CACHE_PREFIX.md5(implode($this->_getCacheParams()));
    }

    protected function _getCacheParams()
    {
        $params = array(Mage::app()->getStore()->getId());

        $data = $this->_dataHash();
        if ($data){
            $params[] = $data;
        }

        return  $params;
    }

    /**
     * Wrapper for standard strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool $allowHtmlEntities
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $allowHtmlEntities = false)
    {
        return $this->_helper()->stripTags($data, $allowableTags, $allowHtmlEntities);
    }

    /**
     * Set Place
     *
     * @param $place
     * @return Magpleasure_Blog_Block_Sidebar_Abstract
     */
    public function setPlace($place)
    {
        $this->_place = $place;
        return $this;
    }

    public function getConfPlace()
    {
        return Mage::getStoreConfig("mpblog/general/".$this->getRoute());
    }

    public function getRoute()
    {
        return $this->_route;
    }

    public function getDisplay()
    {
        return ($this->getConfPlace() && ($this->_place == $this->getConfPlace()));
    }

    public function getStrippedConent($content)
    {
        $limit = $this->_helper()->getRecentPostsShortLimit();
        $content = $this->stripTags($content);
        if (strlen($content) > $limit){
            $content = substr($content, 0, $limit);
            if (strpos($content, " ") !== false){
                $cuts = explode(" ", $content);
                if (count($cuts) && count($cuts) > 1){
                    unset($cuts[count($cuts) - 1]);
                    $content = implode(" ", $cuts);
                }
            }
        }
        return $content."...";
    }

    protected function _checkCategory($collection)
    {
        return $this;
    }

    public function getHeaderHtml($post = null)
    {
        return $this->_helper()->getHeaderHtml($post);
    }

    public function getFooterHtml($post = null)
    {
        return $this->_helper()->getFooterHtml($post);
    }

    public function isOldStyle()
    {
        return ($this->_helper()->getIconStyle() == 'old');
    }

    public function getColorClass()
    {
        return $this->_helper()->getIconColorClass();
    }

}
