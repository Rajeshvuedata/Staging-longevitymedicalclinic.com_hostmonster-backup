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

class Magpleasure_Blog_Block_Custom extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    const TRANSFER_KEY = 'MP_BLOG_CUSTOM_WIDGET_TRANSFER_DATA';

    /**
     * Get cache key informative items
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $cacheKeyInfo = parent::getCacheKeyInfo();

        $cacheKeyInfo['block_type'] = $this->getBlockType();
        $cacheKeyInfo['category_id'] = $this->getCategoryId();
        $cacheKeyInfo['display_short'] = $this->getDisplayShort();
        $cacheKeyInfo['record_limit'] = $this->getRecordLimit();
        $cacheKeyInfo['display_date'] = $this->getDisplayDate();

        $cacheKeyInfo['store_id'] = Mage::app()->getStore()->getId();


        return $cacheKeyInfo;
    }

    protected $_dataToTransfer = array(
                                    'category_id',
                                    'display_short',
                                    'record_limit',
                                    'display_date',
                                );

    /**
     * Helper
     *
     * @return Magpleasure_Blog_Helper_Data
     */
    public function _helper()
    {
        return Mage::helper('mpblog');
    }

    protected function _toHtml()
    {
        if ($blockType = $this->getBlockType()){

            $transfer = array();
            foreach ($this->_dataToTransfer as $key){
                $transfer[$key] = $this->getData($key);
            }

            Mage::register(self::TRANSFER_KEY, $transfer, true);
            $block = $this->getLayout()->createBlock("mpblog/{$blockType}_custom");
            if ($block){

                return $block->toHtml();
            }
        }
        return  false;
    }

}