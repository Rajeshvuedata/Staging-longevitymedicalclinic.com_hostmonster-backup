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

class Magpleasure_Blog_Model_Author extends Magpleasure_Blog_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/author');
    }

    /**
     * User
     *
     * @return Mage_Admin_Model_User
     */
    protected function _getUser()
    {
        return Mage::getSingleton('admin/session')->getUser();
    }

    public function getUser()
    {
        return $this->_getUser();
    }

    public function getDefaultName()
    {
        if (!$this->getId()){
            if ($user = $this->_getUser()){
                $this->load($user->getId(), 'user_id');
            }
        }

        return $this->getId() ? $this->getName() : $user->getName();
    }

    public function getDefaultGoogleProfile()
    {
        if (!$this->getId()){
            if ($user = $this->_getUser()){
                $this->load($user->getId(), 'user_id');
            }
        }

        if ($this->getId()){
            return $this->getGoogleProfile();
        }

        return false;
    }

    public function updateStoreId($storeId)
    {
        if ($this->getData('store_id') != $storeId){
            $this
                ->setData('store_id', $storeId)
                ->save()
            ;
        }

        return $this;
    }
}