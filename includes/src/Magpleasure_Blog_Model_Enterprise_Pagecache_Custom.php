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


class Magpleasure_Blog_Model_Enterprise_Pagecache_Custom extends Magpleasure_Blog_Model_Enterprise_Pagecache_Abstract
{
    /**
     * Cache tag prefix
     */
    const CACHE_TAG_PREFIX = 'mpblog_custom';

    /**
     * Get cache identifier
     *
     * @return string
     */
    protected function _getCacheId()
    {
        $cacheIdParts = array(self::CACHE_TAG_PREFIX);

        $cacheIdParts[] = $this->_placeholder->getAttribute('block_type');
        $cacheIdParts[] = $this->_placeholder->getAttribute('category_id');
        $cacheIdParts[] = $this->_placeholder->getAttribute('display_short') ? '1' : '0';
        $cacheIdParts[] = $this->_placeholder->getAttribute('record_limit') ? '1' : '0';
        $cacheIdParts[] = $this->_placeholder->getAttribute('display_date') ? '1' : '0';
        $cacheIdParts[] = $this->_placeholder->getAttribute('store_id');

        $cacheId = implode("_", $cacheIdParts);
        return $cacheId;
    }

    /**
     * Get container individual additional cache id
     *
     * @return string
     */
    protected function _getAdditionalCacheId()
    {
        return md5($this->_placeholder->getName() . '_' . $this->_placeholder->getAttribute('cache_id'));
    }

    /**
     * Render block content
     *
     * @return string
     */
    protected function _renderBlock()
    {
        /** @var $block Magpleasure_Ajaxbookmarks_Block_Widget */
        $block = $this->_getSafePlaceHolderBlock();

        $block->addData(array(
            'block_type' => $this->_placeholder->getAttribute('bookmark_type'),
            'category_id' => $this->_placeholder->getAttribute('category_id'),
            'display_short' => $this->_placeholder->getAttribute('display_short'),
            'record_limit' => $this->_placeholder->getAttribute('record_limit'),
            'display_date' => $this->_placeholder->getAttribute('display_date'),
        ));

        Mage::dispatchEvent('render_block', array('block' => $block, 'placeholder' => $this->_placeholder));
        return $block->toHtml();
    }


}