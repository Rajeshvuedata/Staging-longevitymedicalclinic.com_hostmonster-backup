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

class Magpleasure_Blog_Block_Social extends Magpleasure_Blog_Block_Content_Post
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mpblog/social.phtml');
    }

    protected function _getCacheParams()
    {
        $params = parent::_getCacheParams();
        $params[] = 'social';
        return $params;
    }

    /**
     * Social Networks
     *
     * @return array
     */
    public function getButtons()
    {
        /** @var Magpleasure_Blog_Model_Networks $networks  */
        $networks = Mage::getModel('mpblog/networks');
        return $networks->getNetworks();
    }

    public function getButtonsCount()
    {
        return count($this->getButtons());
    }

    public function getButtonUrl($button)
    {
        $url = $button->getUrl();

        $url = str_replace("{url}", urlencode($this->getPost()->getPostUrl()), $url);
        $url = str_replace("{title}", urlencode($this->getPost()->getTitle()), $url);
        $url = str_replace("{description}", urlencode($this->getPost()->getMetaDescription()), $url);

        if ($button->getImage()){
            $url = str_replace("{img}", urlencode($this->getPost()->getImageUrl()), $url);
        }

        return $url;
    }

    public function getButtonHtml($button)
    {
        if ($key = $button->getValue()){
            /** @var Mage_Core_Block_Template $block  */
            $block = $this->getLayout()->createBlock('mpblog/social_button');
            if ($block){
                $block
                    ->setTemplate("mpblog/social/{$key}.phtml")
                    ->setButton($button)
                    ;
                return $block->toHtml();
            }
        }
        return '';
    }

    public function getHasImage($button)
    {
        return ($button->getImage() && !!$this->getPost()->getImageUrl()) ||
                !$button->getImage();
    }

}