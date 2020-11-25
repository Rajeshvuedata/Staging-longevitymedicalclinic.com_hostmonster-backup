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

class Magpleasure_Blog_Model_Adminhtml_Observer
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function generateBlockAfter($event)
    {
        $block = $event->getBlock();

        if ($block && ($block instanceof Mage_Adminhtml_Block_System_Account_Edit)) {


            /** @var $root Magpleasure_Common_Block_Adminhtml_Widget_Ajax_Form_Root */
            $root = Mage::app()->getLayout()->createBlock('magpleasure/adminhtml_widget_ajax_form_root');
            $root->createForm('mpblogAuthorInfoForm', array(
                'container' => 'mpblog/adminhtml_author_edit',
                'width' => '600',
                'height' => '300',
                'default_entity_id' => 1,
            ));

            try {
                $block->addButton('smart_button', array(
                    'label' => $this->_helper()->__("Author Info"),
                    'title' => $this->_helper()->__("Default Author Info for Blog Pro"),
                    'onclick' => $root->getJsObjectName().'.open()',
                    'class' => 'go',
                    'after_html' => $root->toHtml(),
                ), null, -10000);
            } catch (Exception $e) {
                $this->_helper()->getCommon()->getException()->logException($e);
            }
        }
    }

}