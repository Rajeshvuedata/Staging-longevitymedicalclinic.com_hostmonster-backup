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


class Magpleasure_Blog_Block_Adminhtml_Widget_Grid_Column_Renderer_Post
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $postId = $this->_getValue($row);
        if ($postId) {
            $html = "";
            /** @var Magpleasure_Blog_Model_Post $post  */
            $post = Mage::getModel('mpblog/post')->load($postId);
            $title = $this->escapeHtml($post->getTitle());

            $params = array(
                'id' => $postId
            );

            $storeId = $this->getRequest()->getParam('store');
            if ($storeId !== null){
                $params['store'] = $storeId;
            }

            $url = $this->getUrl('mpblog_admin/adminhtml_post/edit', $params);
            $html .= "<a href=\"{$url}\" target=\"_blank\">{$title}</a>";
            return $html;
        }
        return parent::render($row);
    }



}