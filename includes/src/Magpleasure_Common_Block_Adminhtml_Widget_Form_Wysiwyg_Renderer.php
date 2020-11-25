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
 * @package    Magpleasure_Common
 * @version    0.7.9
 * @copyright  Copyright (c) 2012-2014 MagPleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Common_Block_Adminhtml_Widget_Form_Wysiwyg_Renderer extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('magpleasure/widget/form/wysiwyg.phtml');
    }

    public function getTinyThemeJsUrl()
    {
        return Mage::getBaseUrl('js')."magpleasure/libs/tinymce/themes/modern/theme.min.js";
    }

    public function getTinySkinUrl()
    {
        return Mage::getBaseUrl('js')."magpleasure/libs/tinymce/skins/lightgray/skin.min.css";
    }

    public function getTinyContentUrl()
    {
        return Mage::getBaseUrl('js')."magpleasure/libs/tinymce/skins/lightgray/content.min.css";
    }
}