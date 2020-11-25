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

class Magpleasure_Blog_Block_Content_List_Wrapper extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("mpblog/list/wrapper.phtml");
    }

    protected function _getPager()
    {
        return $this->getLayout()->getBlock(Magpleasure_Blog_Block_Content_List::PAGER_BLOCK_NAME);
    }

    public function getNextUrl()
    {
        if ($pager = $this->_getPager()){
            if (!$pager->isLastPage()){
                return $pager->getNextPageUrl();
            }
        }
        return "";
    }

    public function getPreviousUrl()
    {
        if ($pager = $this->_getPager()){
            if (!$pager->isFirstPage()){
                return $pager->getPreviousPageUrl();
            }
        }
        return "";
    }

}