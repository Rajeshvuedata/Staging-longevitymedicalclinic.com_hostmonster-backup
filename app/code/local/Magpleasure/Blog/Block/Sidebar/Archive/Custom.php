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

class Magpleasure_Blog_Block_Sidebar_Archive_Custom extends Magpleasure_Blog_Block_Sidebar_Archive
    implements Mage_Widget_Block_Interface
{
    protected $_customCollection;

    public function getDisplay()
    {
        return true;
    }

    protected function _getCacheParams()
    {
        $this->_keysToCache = array(
            'label',
            'record_limit',
        );

        $params = parent::_getCacheParams();
        $params[] = 'archive_custom';

        return $params;
    }

    public function getLimit()
    {
        return $this->getData('record_limit');
    }

    public function getBlockHeader()
    {
        return $this->getData('label');
    }
}