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


class Magpleasure_Blog_Block_Adminhtml_Post_Category_Edit extends Magpleasure_Blog_Block_Adminhtml_Filterable_Edit
{
    protected $_posts = array();

    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'mpblog';
        $this->_controller = 'adminhtml_post_category';

        $this->_updateButton('save', 'label', $this->_helper()->__('Update'));
        $this->_removeButton('delete');
        $this->_removeButton('reset');

        $this->_formScripts[] = "
        var checkboxChanged = function(id){
            $(id+'_ids').disabled = !$(id).checked;
        };
        ";
    }

    public function getHeaderText()
    {
        return $this->_helper()->__('Update Category');
    }

}