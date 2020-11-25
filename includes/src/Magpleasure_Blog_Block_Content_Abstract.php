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

class Magpleasure_Blog_Block_Content_Abstract extends Mage_Core_Block_Template
{
    /**
     * Route to get configuration
     *
     * @var string
     */
    protected $_route = 'abstract';

    protected $_title = 'Default Blog Title';

    /**
     * Page Config
     *
     * @return Mage_Page_Model_Config
     */
    protected function _getPageConfig()
    {
        return Mage::getSingleton('page/config');
    }

    protected function _prepareBreadcrumbs()
    {
        /** @var Mage_Page_Block_Html_Breadcrumbs $breadcrumbs  */
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs){
            $breadcrumbs->addCrumb('home', array(
                                'label' => $this->_helper()->__("Home"),
                                'title' => $this->_helper()->__("Home"),
                                'link' => Mage::getBaseUrl('web')
                            ));
        }
        return $this;
    }

    protected function _preparePage()
    {
        /** @var Mage_Page_Block_Html_Head $head  */
        $head = $this->getLayout()->getBlock('head');
        if ($head){
            $head->setTitle($this->getMetaTitle());
            $head->setKeywords($this->getKeywords());
            $head->setDescription($this->getDescription());
        }

        $root = $this->getLayout()->getBlock('root');
        if ($root){

            $layout = $this->_getPageConfig()->getPageLayout($this->_helper()->getLayoutCode());
            if ($layout){
                $root->setTemplate($layout->getTemplate());
            }
        }

        $this->_prepareBreadcrumbs();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->_preparePage();

        return $this;
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

    public function getTitle()
    {
        return $this->_title;
    }

    public function getMetaTitle()
    {
        return $this->getTitle();
    }

    public function getKeywords()
    {
        return '';
    }

    public function getDescription()
    {
        return '';
    }

    public function getHeaderHtml($post = null)
    {
        return $this->_helper()->getHeaderHtml($post);
    }

    public function getFooterHtml($post = null)
    {
        return $this->_helper()->getFooterHtml($post);
    }

}
