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

class Magpleasure_Blog_Block_System_Config_Form_Element_Colors_Render
    extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mpblog/system/config/form/element/colors.phtml');
    }

    /**
     * Helper
     *
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function getSchemesData()
    {
        $data = array();
        foreach (array_keys($this->getSchemes()) as $key){

            $schemeData = $this
                ->_helper()
                ->getCommon()
                ->getConfig()
                ->getArrayFromPath("mpblog/tag/color_schemes/{$key}/data", null, true)
            ;

            if ($schemeData && is_array($schemeData)){
                $data[$key] = $schemeData;
            }
        }
        return $data;
    }

    public function getSchemesDataJson()
    {
        return Zend_Json::encode($this->getSchemesData());
    }

    public function getSchemes()
    {
        $schemeKeys = $this->_helper()->getCommon()->getConfig()->getArrayFromPath('mpblog/tag/color_schemes');
        $schemes = array();

        $schemes['_select_'] = $this->_helper()->__("Select one and press Apply");
        foreach ($schemeKeys as $key){

            $label = $this->_helper()->getCommon()->getConfig()->getValueFromPath("mpblog/tag/color_schemes/{$key}/label");
            if ($label){
                $schemes[$key] = $this->_helper()->__($label);
            }
        }

        return $schemes;
    }



}

