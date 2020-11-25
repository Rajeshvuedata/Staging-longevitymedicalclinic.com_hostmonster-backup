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

class Magpleasure_Blog_Helper_Data extends Mage_Core_Helper_Abstract
{
    const KEY_CUSTOMER_NAME = 'mpblog-customer-name';
    const KEY_CUSTOMER_EMAIL = 'mpblog-customer-email';

    /**
     * Core
     *
     * @return Mage_Core_Helper_Data
     */
    public function _core()
    {
        return Mage::helper('core');
    }

    /**
     * Common Helper
     *
     * @return Magpleasure_Common_Helper_Data
     */
    public function getCommon()
    {
        return Mage::helper('magpleasure');
    }

    public function getMenuEnabled()
    {
        return Mage::getStoreConfig('mpblog/menu/enabled');
    }

    public function getMenuPosition()
    {
        return Mage::getStoreConfig('mpblog/menu/position');
    }

    public function getMenuLabel()
    {
        return Mage::getStoreConfig('mpblog/menu/label');
    }

    public function getUseCategories()
    {
        return !!Mage::getStoreConfig('mpblog/general/use_categories');
    }

    public function getUseTags()
    {
        return !!Mage::getStoreConfig('mpblog/general/use_tags');
    }

    public function getBlogPostfix()
    {
        return Mage::getStoreConfig('mpblog/redirect/url_postfix');
    }

    public function getBlogMetaTitle()
    {
        return Mage::getStoreConfig('mpblog/seo/meta_title');
    }

    public function getShowPrintLink()
    {
        return Mage::getStoreConfig('mpblog/list/display_print');
    }

    public function getBlogMetaTags()
    {
        return Mage::getStoreConfig('mpblog/seo/meta_tags');
    }

    public function getBlogMetaDescription()
    {
        return Mage::getStoreConfig('mpblog/seo/meta_description');
    }

    public function getIconStyle()
    {
        return Mage::getStoreConfig('mpblog/style/icon_style');
    }

    public function getIconColorClass()
    {
        return Mage::getStoreConfig('mpblog/style/color_sheme');
    }

    /**
     * Retrieves Layout Code
     *
     * @return string
     */
    public function getLayoutCode()
    {
        return Mage::getStoreConfig('mpblog/general/layout');
    }

    public function getSeoTitle()
    {
        return Mage::getStoreConfig('mpblog/seo/title');
    }

    public function getRecentPostsLimit()
    {
        return Mage::getStoreConfig('mpblog/recent_posts/record_limit');
    }

    public function getRecentPostsDisplayDate()
    {
        return Mage::getStoreConfig('mpblog/recent_posts/display_date');
    }

    public function getRecentCommentsDisplayDate()
    {
        return Mage::getStoreConfig('mpblog/comments/display_date');
    }

    public function getRecentCommentsDisplayShort()
    {
        return Mage::getStoreConfig('mpblog/comments/display_short');
    }

    public function getRecentPostsDisplayShort()
    {
        return Mage::getStoreConfig('mpblog/recent_posts/display_short');
    }

    public function getRecentPostsShortLimit()
    {
        return Mage::getStoreConfig('mpblog/recent_posts/short_limit');
    }

    public function getTagsMinimalPostCount($storeId = null)
    {
        return Mage::getStoreConfig('mpblog/tags/minimal_post_count', $storeId);
    }

    public function getTagsMtWidth()
    {
        return Mage::getStoreConfig('mpblog/tags/mt_width');
    }

    public function getTagsMtHeight()
    {
        return Mage::getStoreConfig('mpblog/tags/mt_height');
    }

    public function getTagsMtBackground()
    {
        return Mage::getStoreConfig('mpblog/tags/mt_background');
    }

    public function getTagsMtTextcolor()
    {
        return Mage::getStoreConfig('mpblog/tags/mt_textcolor');
    }

    public function getTagsMtTextcolor2()
    {
        return Mage::getStoreConfig('mpblog/tags/mt_textcolor2');
    }

    public function  getTagsMtHiColor()
    {
        return Mage::getStoreConfig('mpblog/tags/mt_hicolor');
    }

    public function getTagsMtEnabled()
    {
        return Mage::getStoreConfig('mpblog/tags/mt_enabled');
    }

    public function getCommentNotificationsEnabled()
    {
        return Mage::getStoreConfig('mpblog/notify_customer_comment_replyed/enabled');
    }

    public function getCommentsEnabled()
    {
        return Mage::getStoreConfig('mpblog/comments/use_comments');
    }

    public function getCommentsAllowGuests()
    {
        return Mage::getStoreConfig('mpblog/comments/allow_guests');
    }

    public function getCommentsAutoapprove()
    {
        return Mage::getStoreConfig('mpblog/comments/autoapprove');
    }

    public function getCommentsLimit()
    {
        return Mage::getStoreConfig('mpblog/comments/record_limit');
    }

    public function getRssDisplayOnList($storeId = null)
    {
        return Mage::getStoreConfig('mpblog/rss/display_on_list', $storeId);
    }

    public function getRssPost($storeId = null)
    {
        return Mage::getStoreConfig('mpblog/rss/post_feed', $storeId);
    }

    public function getRssCatgeory($storeId = null)
    {
        return Mage::getStoreConfig('mpblog/rss/category_feed', $storeId);
    }

    public function getRssComment($storeId = null)
    {
        return Mage::getStoreConfig('mpblog/rss/comment_feed', $storeId);
    }

    public function getSocialEnabled()
    {
        return Mage::getStoreConfig('mpblog/social/enabled');
    }

    public function getPostsLimit()
    {
        return Mage::getStoreConfig('mpblog/list/count_per_page');
    }

    public function getDisplayViews()
    {
        return Mage::getStoreConfig('mpblog/list/display_views');
    }

    /**
     * Retrieves enabled social buttons
     *
     * @return array
     */
    public function getSocialNetworks()
    {
        return explode(",", Mage::getStoreConfig('mpblog/social/networks'));
    }

    /**
     * Sitemap Enabled
     *
     * @param null $storeId
     * @return mixed
     */
    public function getSitemapEnabled($storeId = null)
    {
        return Mage::getStoreConfig('mpblog/sitemap/enabled', $storeId);
    }

    public function getSitemapIncluded($storeId = null)
    {
        return explode(",", Mage::getStoreConfig('mpblog/sitemap/include', $storeId));
    }

    /**
     * Url Helper
     *
     * @param null $storeId
     * @return Magpleasure_Blog_Helper_Url
     */
    public function _url($storeId = null)
    {
        /** @var $urlHelper Magpleasure_Blog_Helper_Url */
        $urlHelper = Mage::helper('mpblog/url');
        $urlHelper->setStoreId($storeId ? $storeId : Mage::app()->getStore()->getId());

        return $urlHelper;
    }

    /**
     * Page Layout Helper
     *
     * @return Mage_Page_Helper_Layout
     */
    public function _layout()
    {
        return Mage::helper('page/layout');
    }

    /**
     * Email Notifier
     *
     * @return Magpleasure_Blog_Helper_Notifier
     */
    public function _notifier()
    {
        return Mage::helper('mpblog/notifier');
    }

    /**
     * Strings
     *
     * @return Magpleasure_Blog_Helper_Strings
     */
    public function _strings()
    {
        return Mage::helper('mpblog/strings');
    }

    /*
     * Recursively searches and replaces all occurrences of search in subject values replaced with the given replace value
     *
     * @param string $search The value being searched for
     * @param string $replace The replacement value
     * @param array $subject Subject for being searched and replaced on
     * @return array Array with processed values
     */
    public function recursiveReplace($search, $replace, $subject)
    {
        if(!is_array($subject))
            return $subject;

        foreach($subject as $key => $value)
            if(is_string($value))
                $subject[$key] = str_replace($search, $replace, $value);
            elseif(is_array($value))
                $subject[$key] = self::recursiveReplace($search, $replace, $value);

        return $subject;
    }

    public function getHeaderHtml($post = null)
    {
        if ($post){
            $id = $post->getId();
        }

        $details = Mage::app()->getLayout()->createBlock('mpblog/content_list_details');
        if ($details){
            $details
                ->setPost($post)
                ->setTemplate("mpblog/list/post_header.phtml");
            ;
            return $details->toHtml();
        }

        return false;
    }

    public function getFooterHtml($post = null)
    {
        if ($post){
            $id = $post->getId();
        }
        $details = Mage::app()->getLayout()->createBlock('mpblog/content_list_details');
        if ($details){
            $details
                ->setPost($post)
                ->setTemplate("mpblog/list/post_footer.phtml");
            ;
            return $details->toHtml();
        }
        return false;
    }

    /**
     * Render
     *
     * @return Magpleasure_Blog_Helper_Comment_Render
     */
    public function _render()
    {
        return Mage::helper("mpblog/comment_render");
    }

    /**
     * Importer
     *
     * @return Magpleasure_Blog_Helper_Import
     */
    public function _importer()
    {
        return Mage::helper("mpblog/import");
    }

    /**
     * Comment Secure
     *
     * @return Magpleasure_Blog_Helper_Comment_Secure
     */
    public function _secure()
    {
        return Mage::helper("mpblog/comment_secure");
    }

    /**
     * Process Date
     *
     * @param Zend_Date $date
     * @return Zend_Date
     */
    protected function _processTimezone(Zend_Date $date)
    {
        $date->subSecond($this->getTimezoneOffset());
        return $date;
    }

    public function renderTime($datetime, $missTimezone = false)
    {
        $date = new Zend_Date($datetime, Zend_Date::ISO_8601, Mage::app()->getLocale()->getLocaleCode());
        if (!$missTimezone){
            $date = $this->_processTimezone($date);
        }
        return $date->toString(Zend_Date::TIME_SHORT);
    }

    public function renderDate($datetime, $missTimezone = false)
    {
        $date = new Zend_Date($datetime, Zend_Date::ISO_8601, Mage::app()->getLocale()->getLocaleCode());
        if (!$missTimezone){
            $date = $this->_processTimezone($date);
        }
        return $date->toString(Zend_Date::DATE_LONG);
    }

    public function renderDateAndTime($datetime, $missTimezone = false)
    {
        $date = new Zend_Date($datetime, Zend_Date::ISO_8601, Mage::app()->getLocale()->getLocaleCode());
        if (!$missTimezone){
            $date = $this->_processTimezone($date);
        }
        return $date->toString(Zend_Date::DATETIME_MEDIUM);
    }

    /**
     * FirePHP
     *
     * @return Inchoo_Developer_Helper_Firephp_Data
     */
    public function _console()
    {
        return Mage::helper('firephp');
    }

    public function checkForPrefix($title)
    {
        if ($prefix = Mage::getStoreConfig('mpblog/seo/title')){
            $title = $prefix." - ".$title;
        }
        return $title;
    }

    /**
     * Wrapper for standart strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool $escape
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $escape = false)
    {
        $result = strip_tags($data, $allowableTags);
        return $escape ? $this->escapeHtml($result, $allowableTags) : $result;
    }

    public function getConfigValue($type, $name)
    {
        return Mage::getConfig()->getNode("mpblog/tools/import/{$type}/{$name}");
    }

    /**
     * Customer Session
     *
     * @return Mage_Customer_Model_Session
     */
    public function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function saveCommentorName($name)
    {
        $this->getCustomerSession()->setData(self::KEY_CUSTOMER_NAME, $name);
        return $this;
    }

    public function loadCommentorName()
    {
        return $this->getCustomerSession()->getData(self::KEY_CUSTOMER_NAME);
    }

    public function saveCommentorEmail($email)
    {
        $this->getCustomerSession()->setData(self::KEY_CUSTOMER_EMAIL, $email);
        return $this;
    }

    public function loadCommentorEmail()
    {
        return $this->getCustomerSession()->getData(self::KEY_CUSTOMER_EMAIL);
    }

    /**
     * Retrives global timezone
     * @return string
     */
    public function getTimezone()
    {
        return Mage::app()->getStore()->getConfig('general/locale/timezone');
    }

    /**
     * Retrieves global timezone offset in seconds
     *
     * @param boolean $isMysql If true retrieves mysql formmatted offset (+00:00) in hours
     * @return int
     */
    public function getTimeZoneOffset($isMysql = false)
    {
        $date = new Zend_Date();
        $date->setTimezone($this->getTimezone());
        if ($isMysql){
            $offsetInt = -$date->getGmtOffset();
            $offset = ($offsetInt >= 0 ? '+' : '-').sprintf( '%02.0f', round( abs($offsetInt/3600) )).':'.( sprintf('%02.0f', abs( round( ( abs( $offsetInt ) - round( abs( $offsetInt / 3600 )  ) * 3600 ) / 60 ) ) ) );
            return $offset;
        } else {
            return $date->getGmtOffset();
        }
    }


    /**
     * Prepare JSON
     *
     * @param array $data
     * @return string
     */
    public function getJSON($data)
    {   $parts = array();
        foreach ($data as $key=>$value){
            if (is_bool($value)){
                $sVal = $value ? 'true' : 'false';
            } elseif (is_numeric($value)){
                $sVal = (string) $value;
            } else {
                $sVal = "'{$value}'";
            }
            $parts[] = "{$key}:$sVal";
        }
        return "{".implode(",", $parts)."}";
    }

    /**
     * Redirect to Recent Post if requested Post is not found
     *
     * @return bool
     */
    public function getRedirectToRecentIfPostNotFound()
    {
        return !!Mage::getStoreConfig('mpblog/redirect/redirect_if_notfound');
    }

    /**
     * Redirect to Recent Post if requested Post is not found
     *
     * @return bool
     */
    public function getRedirectToSeoFormattedUrl()
    {
        return !!Mage::getStoreConfig('mpblog/redirect/redirect_to_seo_formatted_url');
    }

    /**
     * Category Data Helper
     *
     * @return Magpleasure_Blog_Helper_Data_Categories
     */
    public function getCategoryHelper()
    {
        return Mage::helper('mpblog/data_categories');
    }

    /**
     * Store Data Helper
     *
     * @return Magpleasure_Blog_Helper_Data_Store
     */
    public function getStoreHelper()
    {
        return Mage::helper('mpblog/data_store');
    }

    public function getDynamicCookieName()
    {
        return "mpblog_customer_comments";
    }

    public function escapeHtml($data, $allowedTags = null)
    {
        return $this->getCommon()->getCore()->escapeHtml($data, $allowedTags);
    }

}