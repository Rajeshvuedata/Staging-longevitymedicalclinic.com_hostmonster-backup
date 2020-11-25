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

class Magpleasure_Blog_Adminhtml_Notifications_CommentsController
    extends Magpleasure_Blog_Controller_Adminhtml_Filterable
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/mpblog/notifications/comments');
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('cms/mpblog/notifications/comments')
            ->_addBreadcrumb($this->_helper()->__('Blog'), $this->_helper()->__('Blog'));
        return $this;
    }


    public function indexAction()
    {
        $this->_prepareStoreFilter();

        $this->_initAction()
            ->renderLayout();
    }

    /**
     * Delete comment
     * @param int|string $id
     * @return boolean
     */
    protected function _delete($id)
    {
        $subscription = Mage::getModel('mpblog/comment_subscription')->load($id);
        if ($subscription->getId()){
            try{
                $subscription->delete();
                return true;
            } catch(Exception $e) {
                return false;
            }
        }
        return false;
    }

    protected function _updateType($id, $type)
    {
        if ($id){
            try {
                $subscription = Mage::getModel('mpblog/comment_subscription')->load($id);
                $subscription->setSubscriptionType($type);
                $subscription->save();
                return true;
            } catch (Exception $e){
                return false;
            }
        }
    }

    protected function _updateStatus($id, $status)
    {
        if ($id){
            try {
                $subscription = Mage::getModel('mpblog/comment_subscription')->load($id);
                $subscription->setStatus($status);
                $subscription->save();
                return true;
            } catch (Exception $e){
                return false;
            }
        }
    }

    public function massTypeAction()
    {
        $subscriptions = $this->getRequest()->getPost('subscriptions');
        $type = $this->getRequest()->getPost('subscription_type');
        if ($subscriptions){
            $success = 0;
            $error = 0;
            foreach ($subscriptions as $subscriptionId){
                if ($this->_updateType($subscriptionId, $type)){
                    $success++;
                } else {
                    $error++;
                }
            }
            if ($success){
                $this->_getSession()->addSuccess($this->_helper()->__("%s subscriptions successfully updated.", $success));
            }
            if ($error){
                $this->_getSession()->addError($this->_helper()->__("%s subscriptions was not updated.", $error));
            }
        }
        $this->_redirectReferer();
    }

    public function massStatusAction()
    {
        $subscriptions = $this->getRequest()->getPost('subscriptions');
        $status = $this->getRequest()->getPost('status');
        if ($subscriptions){
            $success = 0;
            $error = 0;
            foreach ($subscriptions as $subscriptionId){
                if ($this->_updateStatus($subscriptionId, $status)){
                    $success++;
                } else {
                    $error++;
                }
            }
            if ($success){
                $this->_getSession()->addSuccess($this->_helper()->__("%s subscriptions successfully updated.", $success));
            }
            if ($error){
                $this->_getSession()->addError($this->_helper()->__("%s subscriptions was not updated.", $error));
            }
        }
        $this->_redirectReferer();
    }

    public function massDeleteAction()
    {
        $subscriptions = $this->getRequest()->getPost('subscriptions');
        if ($subscriptions){
            $success = 0;
            $error = 0;
            foreach ($subscriptions as $subscriptionId){
                if ($this->_delete($subscriptionId)){
                    $success++;
                } else {
                    $error++;
                }
            }
            if ($success){
                $this->_getSession()->addSuccess($this->_helper()->__("%s subscriptions successfully deleted.", $success));
            }
            if ($error){
                $this->_getSession()->addError($this->_helper()->__("%s subscriptions was not deleted.", $error));
            }
        }
        $this->_redirectReferer();
    }


    public function gridAction()
    {
        $grid = $this->getLayout()->createBlock('mpblog/adminhtml_subscription_comment_grid');
        if ($grid){
            $this->getResponse()->setBody($grid->toHtml());
        }
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id){
            try {
                $this->_delete($id);
                $this->_getSession()->addSuccess($this->_helper()->__("Subscription was successfully deleted."));
            } catch (Exception $e){
                $this->_getSession()->addError($this->_helper()->__("Subscription was not deleted. %s", $e->getMessage()));
                $this->_redirectReferer();
                return;
            }
        }
        $this->_redirect('*/*/index', $this->_getCommonParams());
    }

    public function subscribeAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id){
            try {
                $this->_updateStatus($id, Magpleasure_Blog_Model_Comment_Subscription::STATUS_UNSUBSCRIBED);
                $this->_getSession()->addSuccess($this->_helper()->__("Customer was successfully subscribed."));
            } catch (Exception $e){
                $this->_getSession()->addError($this->_helper()->__("Customer was not subscribed. %s", $e->getMessage()));
                $this->_redirectReferer();
                return;
            }
        }
        $this->_redirect('*/*/index', $this->_getCommonParams());
    }


    public function unsubscribeAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id){
            try {
                $this->_updateStatus($id, Magpleasure_Blog_Model_Comment_Subscription::STATUS_UNSUBSCRIBED);
                $this->_getSession()->addSuccess($this->_helper()->__("Customer was successfully unsubscribed."));
            } catch (Exception $e){
                $this->_getSession()->addError($this->_helper()->__("Customer was not unsubscribed. %s", $e->getMessage()));
                $this->_redirectReferer();
                return;
            }
        }
        $this->_redirect('*/*/index', $this->_getCommonParams());
    }


}