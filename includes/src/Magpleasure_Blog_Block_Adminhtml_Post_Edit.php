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


class Magpleasure_Blog_Block_Adminhtml_Post_Edit extends Magpleasure_Blog_Block_Adminhtml_Filterable_Edit
{

    /**
     * Frontend URL Model
     *
     * @return Mage_Core_Model_Url
     */
    protected function _getFrontendUrlModel()
    {
        return Mage::getSingleton("core/url");
    }

    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'mpblog';
        $this->_controller = 'adminhtml_post';

        $this->_updateButton('save', 'label', $this->_helper()->__('Save'));
        $this->_updateButton('delete', 'label', $this->_helper()->__('Delete'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        if ($this->_getId()){
            $this->addButton('duplicate', array(
                'title' => $this->_helper()->__("Duplicate"),
                'label' => $this->_helper()->__("Duplicate"),
                'onclick' => "duplicate();",
                'class' => 'scalable save duplicate',
            ), 1, 2);

            $params = $this->_getCommonParams();
            $params['id'] = $this->_getId();

            $duplicateUrl = $this->getUrl('*/*/duplicate', $params);
            $confirmationMessage = $this->_helper()->__("Please confirm duplicating. All data that hasn't been saved will be lost.");
            $confirmationMessage = str_replace("'", "\\'",$confirmationMessage);
            $this->_formScripts[] = "
                function duplicate(){
                    if (confirm('{$confirmationMessage}')){
                        window.location = '{$duplicateUrl}';
                    }
                }
            ";
        }

        $this->addButton('preview', array(
            'title' => $this->_helper()->__("Preview"),
            'label' => $this->_helper()->__("Preview"),
            'onclick' => "preview();",
            'id'    => 'preview_button',
            'class' => 'scalable show-hide',
        ), 1, 3);

        $scheduled = Magpleasure_Blog_Model_Post::STATUS_SCHEDULED;

        /** @var Mage_Adminhtml_Block_Customer $block */
        $previewParams = array(
            'url' => $this->_getFrontendUrlModel()->getUrl("mpblog/preview/window"),
            'content_id' => 'full_content',
            'header_id' => 'title',
            'button_id' => 'preview_button',
            'width'     => '900',
            'height'     => '700',
        );

        $previewJSON = $this->_helper()->getJSON($previewParams);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

            function preview(){
                mpBlogPreview.preview();
            }
            var mpBlogPreview = new MpBlogPreview({$previewJSON});
        ";

    }

    protected function _getId()
    {
        if ($id = $this->getRequest()->getParam('id')){
            return $id;
        } else {
            return false;
        }
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_post') && Mage::registry('current_post')->getId()) {
            return $this->_helper()->__("Edit Post '%s'", $this->escapeHtml(Mage::registry('current_post')->getTitle()));
        } else {
            return $this->_helper()->__('New Post');
        }
    }
}