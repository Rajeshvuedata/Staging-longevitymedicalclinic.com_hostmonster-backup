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

class Magpleasure_Blog_Block_Sidebar_Search extends Magpleasure_Blog_Block_Sidebar_Abstract
{
    protected $_search;


    protected function _getCacheParams()
    {
        $params = parent::_getCacheParams();
        $params[] = 'search';
        $params[] = $this->getRequest()->getParam('query');

        return $params;
    }

    /**
     * Archive Model
     *
     * @return Magpleasure_Blog_Model_Search
     */
    public function getSearch()
    {
        if (!$this->_search){
            $search = Mage::getModel('mpblog/search');
            $this->_search = $search;
        }
        return $this->_search;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("mpblog/sidebar/search.phtml");
        $this->_route = 'display_search';
    }

    public function getBlockHeader()
    {
        return $this->__("Search the Blog");
    }

    public function getSearchUrl()
    {
        return $this->getSearch()->getUrl();
    }

    public function getQuery()
    {
        return $this->stripTags($this->getRequest()->getParam('query'));
    }
}