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


class Magpleasure_Blog_Model_System_Config_Source_Colors
{	
    public function toOptionArray()
    {
        return array(
            array('value'=>'mpblog-classic', 'label'=>Mage::helper('mpblog')->__('Classic')),
            array('value'=>'mpblog-red', 'label'=>Mage::helper('mpblog')->__('Red')),
            array('value'=>'mpblog-green', 'label'=>Mage::helper('mpblog')->__('Green')),
            array('value'=>'mpblog-blue', 'label'=>Mage::helper('mpblog')->__('Blue')),
        );
    }

}

