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

class Magpleasure_Blog_Block_Content_Custom extends Magpleasure_Blog_Block_Sidebar_Custom
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("mpblog/custom.phtml");
    }

    protected function _getCacheParams()
    {
        $this->_keysToCache = array(
            'category_id',
            'display_short',
            'record_limit',
            'display_date',
        );

        $ids = $this->_cachedIds;

        $params = parent::_getCacheParams();
        $params[] = 'content';

        return $params;
    }

    public function getReadMoreUrl($post)
    {
        return $this->_helper()->_url()->getUrl($post->getId());
    }
}