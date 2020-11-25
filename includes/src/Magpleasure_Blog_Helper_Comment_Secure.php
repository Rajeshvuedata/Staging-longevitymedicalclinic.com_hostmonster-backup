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

class Magpleasure_Blog_Helper_Comment_Secure extends Mage_Core_Helper_Data
{
    const SESSSION_KEY = 'mpblog_customer_keys';

    /**
     * Helper
     *
     * @return Magpleasure_Blog_Helper_Data
     */
    public function _helper()
    {
        return Mage::helper('mpblog');
    }

    /**
     * Customer Session
     *
     * @return Mage_Customer_Model_Session
     */
    public function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    protected function _getMd5Hash()
    {
        return md5(time() + rand(1, 1000));
    }

    protected function _save($key, $values)
    {
        $session = $this->getCustomerSession();
        $keys = $session->getData(self::SESSSION_KEY);
        if (!$keys || !is_array($keys)){
            $keys = array();
        }
        $keys[$key] = $values;
        $session->setData(self::SESSSION_KEY, $keys);
    }

    protected function _load($key)
    {
        $session = $this->getCustomerSession();
        $keys = $session->getData(self::SESSSION_KEY);
        if ($keys && is_array($keys)){
            if (isset($keys[$key])){
                $result = $keys[$key];
                unset($keys[$key]);
                return $result;
            }
        }
        return false;
    }

    public function getSecureCode($postId, $replyTo)
    {
        $key = $this->_getMd5Hash();
        $data = array(
            'post_id' => $postId,
            'reply_to' => $replyTo,
        );
        $data = serialize($data);
        $this->_save($key, $data);
        return $key;
    }

    public function validate($secure, $postId, $replyTo)
    {
        if (!$this->_helper()->getCommentsAllowGuests() && !$this->getCustomerSession()->isLoggedIn()){
            return false;
        }
        $data = $this->_load($secure);
        if ($data){
            try {
                $data = unserialize($data);
                if (is_array($data)){
                    if (isset($data['post_id']) && isset($data['reply_to'])){
                        return (($data['post_id'] == $postId) && ($data['reply_to'] == $replyTo));
                    }
                }
            } catch (Exception $e){
                return false;
            }
        }
        return false;
    }


}