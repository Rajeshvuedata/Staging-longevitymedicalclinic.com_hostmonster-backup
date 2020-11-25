<?php

/**
 * 
 * @author anonymous
 * 
 * Mage_Core_Model_Mysql4_Collection_Abstract
 * Mage_Core_Model_Resource_Db_Collection_Abstract
 */
class MP_Presentations_Model_Resource_Topical_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * 
     * {@inheritDoc}
     * @see Mage_Core_Model_Resource_Db_Collection_Abstract::_construct()
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('mp_presentations/topical');
        $this->addFieldToSelect('id');
    }
}