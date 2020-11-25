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

class Magpleasure_Blog_Block_Rss_Abstract extends Mage_Rss_Block_Abstract
{
    protected $_collection;

    protected function _construct()
    {
        $action = $this->getRequest()->getActionName();
        $storeId = $this->getStoreId();
        $this->setCacheKey("mpblog_rss_{$action}_{$storeId}");
        $this->setCacheLifetime(600);
        parent::_construct();
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

    public function getDataCollection()
    {
        return array();
    }

    public function getStoreId()
    {
        return  $this->getRequest()->getParam('store_id') ?
                $this->getRequest()->getParam('store_id') :
                Mage::app()->getAnyStoreView()->getId();
    }

    public function createRssXml()
    {
        /** @var Mage_Rss_Model_Rss $rssObj  */
        $rssObj = Mage::getModel('rss/rss');

        $data = array(
            'title'    => $this->getRssTitle(),
            'link'     => $this->getRssUrl(),
            'charset'  => 'UTF-8',
            'atom:link' => 'test',

//            array(
//                'href' => 'http://feeds.bbci.co.uk/news/rss.xml',
//                'rel' => 'self',
//                'type' => 'application/rss+xml',
//            ),
        );

        $rssObj->_addHeader($data);

        foreach ($this->getDataCollection() as $data){
            $rssObj->_addEntry($data);
        }
        return $rssObj->createRssXml();
    }

    public function getRssUrl()
    {
        return $this->_helper()->_url()->getUrl();
    }

}