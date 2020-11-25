<?php

class Magentothem_Revslider_Model_Mysql4_Animation extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the revslider_id refers to the key field in your database table.
        $this->_init('revslider/animation', 'animation_id');
    }
}