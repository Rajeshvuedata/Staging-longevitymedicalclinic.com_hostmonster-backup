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


class Magpleasure_Blog_Block_Adminhtml_Form_Element_Autocomplete extends Varien_Data_Form_Element_Text
{
    public function getTagsUrl()
    {
        /** @var Mage_Adminhtml_Model_Url $urlModel  */
        $urlModel = Mage::getSingleton('adminhtml/url');

        $params = array();
        $storeId = Mage::app()->getRequest()->getParam('store');
        if ($storeId !== null){
            $params['store'] = $storeId;
        }

        return $urlModel->getUrl('mpblog_admin/adminhtml_post/tags', $params);
    }

    public function getHtml()
    {
        $tagsUrl = $this->getTagsUrl();
        $script = '
        <script type="text/javascript">
                    (function($){
                        $(document).ready(function(e){
                            $("#tags").autocomplete("'.$tagsUrl.'", {
                                width: 436,
                                max: 12,
                                highlight: false,
                                multiple: true,
                                multipleSeparator: ", ",
                                scroll: true,
                                scrollHeight: 300
                            });
                        });
                    })(jQuery);
        </script>
        ';
        return parent::getHtml().$script;
    }
}