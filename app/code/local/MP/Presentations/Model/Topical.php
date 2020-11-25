<?php

class MP_Presentations_Model_Topical extends Mage_Core_Model_Abstract
{
    /**
     * Class constructor
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('mp_presentations/topical');
    }
}
