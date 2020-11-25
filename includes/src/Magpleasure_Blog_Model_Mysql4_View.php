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

class Magpleasure_Blog_Model_Mysql4_View extends Magpleasure_Common_Model_Resource_Abstract
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function _construct()
    {    
        $this->_init('mpblog/view', 'view_id');
        $this->setUseUpdateDatetimeHelper(true);
    }

    public function loadByPostAndSession(Mage_Core_Model_Abstract $object, $postId, $sessionId)
    {
        /** @var $views Magpleasure_Blog_Model_Mysql4_View_Collection */
        $views = Mage::getModel('mpblog/view')->getCollection();

        $views->addFieldToFilter('post_id', $postId);
        $views->addFieldToFilter('session_id', $sessionId);

        foreach ($views as $view){
            $object->addData($view->getData());
            return $this;
        }

        return $this;
    }

    public function deleteOldRows($date)
    {
        $write = $this->_getWriteAdapter();
        $write->beginTransaction();
        $write->delete($this->getMainTable(), "created_at <= '{$date}'");
        $write->commit();
        return $this;
    }

}