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

class Magpleasure_Blog_Helper_Data_Store
{
    const RESET_VALUE = 'reset';

    protected $_author;

    /**
     * Request
     *
     * @return Mage_Core_Controller_Request_Http
     */
    public function getRequest()
    {
        return Mage::app()->getRequest();
    }

    public function isSingleStoreMode()
    {
        return Mage::app()->isSingleStoreMode();
    }

    public function isStoreFilterApplied()
    {
        return $this->getAppliedStoreId() !== null;
    }

    public function getAppliedStoreId()
    {
        return $this->getRequest()->getParam('store');
    }
    public function getCommonParams()
    {
        $params = array();

        if ($this->isStoreFilterApplied()){
            $params['store'] = $this->getAppliedStoreId();
        }

        return $params;
    }

    /**
     * Author
     *
     * @return Magpleasure_Blog_Model_Author
     */
    public function getAuthor()
    {
        if (!$this->_author){

            /** @var Magpleasure_Blog_Model_Author $author */
            $author = Mage::getModel('mpblog/author');
            $author->load($author->getUser()->getId(), 'user_id');
            if (!$author->getId()){
                $author
                    ->setUserId($author->getUser()->getId())
                    ->setName($author->getDefaultName())
                    ->save()
                    ;
            }

            $this->_author = $author;
        }

        return $this->_author;
    }

    public function saveStoreId($storeId)
    {
        $this
            ->getAuthor()
            ->updateStoreId($storeId)
            ;

        return $this;
    }

    public function clearSavedStoreId()
    {
        $this->saveStoreId(0);
        return $this;
    }

    public function getSavesStoreId()
    {
        $author = $this->getAuthor();
        return $author->getData('store_id') ? $author->getData('store_id') : false;
    }

}