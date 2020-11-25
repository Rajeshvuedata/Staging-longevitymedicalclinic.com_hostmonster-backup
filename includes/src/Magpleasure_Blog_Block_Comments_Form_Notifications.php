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

class Magpleasure_Blog_Block_Comments_Form_Notifications extends Magpleasure_Blog_Block_Comments_Form
{
    protected $_subscription;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("mpblog/comments/form/notifications.phtml");
    }


    /**
     * Current subscription
     *
     * @return Magpleasure_Blog_Model_Comment_Subscription
     */
    public function getSubscription()
    {
        if (!$this->_subscription){

            /** @var $subscription Magpleasure_Blog_Model_Comment_Subscription */
            $subscription = Mage::getModel('mpblog/comment_subscription');

            if ($this->getCustomerSession()->isLoggedIn()){
                $subscription->loadByEmail($this->getPost()->getId(), $this->getCustomerSession()->getCustomer()->getEmail());
            } else {
                $subscription->loadBySessionId($this->getPost()->getId(), $this->getSessionId());
            }

            $this->_subscription = $subscription;
        }

        return $this->_subscription;
    }

    public function getSubscriptionTypes()
    {
        return $this->getSubscription()->getTypesArray();
    }

    public function getSubscriptionType()
    {
        return $this->getSubscription()->getSubscriptionType();
    }


}