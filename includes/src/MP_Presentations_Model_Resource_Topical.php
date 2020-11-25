<?php

/**
 * 
 * @author anonymous
 *
 */
class MP_Presentations_Model_Resource_Topical extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * 
     * {@inheritDoc}
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     */
    public function _construct()
    {
        $this->_init('mp_presentations/topical', 'id');
    }
}