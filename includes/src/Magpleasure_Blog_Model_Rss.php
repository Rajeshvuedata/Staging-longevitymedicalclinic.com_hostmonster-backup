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

class Magpleasure_Blog_Model_Rss
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function blockCreated($event)
    {
        if (!$this->_helper()->getRssComment() && !$this->_helper()->getRssPost()){
            return ;
        }

        /** @var Mage_Core_Model_Layout $layout  */
        $layout = $event->getLayout();
        if ($layout){
            $rssList = $layout->getBlock('rss.list');
            if ($rssList instanceof Mage_Rss_Block_List){
                $alias = $rssList->getBlockAlias();
                $name = $rssList->getNameInLayout();
                $templte = $rssList->getTemplate();
                $parent = $rssList->getParentBlock();

                $layout->unsetBlock($name);
                $parent->unsetChild($alias);
                /** @var Magpleasure_Blog_Block_Rss_List $newRss  */
                $newRss = $layout->createBlock('mpblog/rss_list', $name);
                $newRss->setTemplate($templte);
                $parent->setChild($alias, $name);
                $parent->insert($newRss, $name);
            }
        }
    }

}