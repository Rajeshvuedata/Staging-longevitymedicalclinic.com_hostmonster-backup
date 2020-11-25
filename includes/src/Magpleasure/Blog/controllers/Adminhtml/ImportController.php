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

class Magpleasure_Blog_Adminhtml_ImportController extends Magpleasure_Common_Controller_Adminhtml_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/mpblog/import');
    }

    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/mpblog/import')
            ->_addBreadcrumb($this->_helper()->__('Blog'), $this->_helper()->__('Import'));
        return $this;
    }

    public function wordpressAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function awblogAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function processAction()
    {
        $type = $this->getRequest()->getParam('type');
        $post = $this->getRequest()->getPost();
        if ($type){
            $importer = $this->_helper()->_importer();
            $method = 'import'.ucfirst(strtolower($type));
            if (!($message = $importer->$method(false, $post))){
                $this->_getSession()->addSuccess($this->_helper()->__("Success."));
            } else {
                $this->_getSession()->addError($message);
                Mage::getSingleton('adminhtml/session')->setData(Magpleasure_Blog_Block_Adminhtml_Import_Form::SESSION_KEY, $post);
            }
        } else {
            $this->_getSession()->addError($this->_helper()->__("Undefined TYPE param."));
        }
        $this->_redirectReferer();
    }
}