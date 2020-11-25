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

class Magpleasure_Blog_Helper_Notifier extends Mage_Core_Model_Abstract
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function notifyAboutPendingComment(Magpleasure_Blog_Model_Comment $comment)
    {

        $data = array();
        $data['post'] = $comment->getPost();
        $data['comment'] = $comment;
        $data['store'] = Mage::app()->getStore();
        $storeId = Mage::app()->getStore()->getId();

        $template = Mage::getStoreConfig('mpblog/notify_admin_new_comment/email_template', $storeId);
        $sender = Mage::getStoreConfig('mpblog/notify_admin_new_comment/sender', $storeId);
        $receivers = explode(",", Mage::getStoreConfig('mpblog/notify_admin_new_comment/receiver', $storeId));

        foreach ($receivers as $receiver){
            if (trim($receiver)){
                /** @var Mage_Core_Model_Email_Template $mailTemplate  */
                $mailTemplate = Mage::getModel('core/email_template');
                try {
                    $mailTemplate
                        ->setDesignConfig(array('area' => 'frontend', 'store'=>$storeId))
                        ->sendTransactional(
                        $template,
                        $sender,
                        trim($receiver),
                        $this->_helper()->__('Administrator'),
                        $data,
                        $storeId
                    )
                    ;

                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }

        return $this;
    }

    public function notifyAboutPostPublish(Magpleasure_Blog_Model_Post $post)
    {
        $storeId = $post->getDefaultStoreId();

        $data = array();
        $data['post'] = $post;
        $data['store'] = Mage::app()->getStore($storeId);

        $template = Mage::getStoreConfig('mpblog/notify_admin_scheduled_post/email_template', $storeId);
        $sender = Mage::getStoreConfig('mpblog/notify_admin_scheduled_post/sender', $storeId);
        $receivers = explode(",", Mage::getStoreConfig('mpblog/notify_admin_scheduled_post/receiver', $storeId));

        foreach ($receivers as $receiver){
            if (trim($receiver)){
                /** @var Mage_Core_Model_Email_Template $mailTemplate  */
                $mailTemplate = Mage::getModel('core/email_template');
                try {
                    $mailTemplate
                        ->setDesignConfig(array('area' => 'frontend', 'store'=>$storeId))
                        ->sendTransactional(
                            $template,
                            $sender,
                            trim($receiver),
                            $this->_helper()->__('Administrator'),
                            $data,
                            $storeId
                        )
                    ;

                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
        return $this;
    }

}