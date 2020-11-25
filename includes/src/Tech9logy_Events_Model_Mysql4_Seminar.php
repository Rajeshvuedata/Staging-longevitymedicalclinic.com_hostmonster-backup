<?php
class Tech9logy_Events_Model_Mysql4_Seminar extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("events/seminar", "id");
		
    }
}