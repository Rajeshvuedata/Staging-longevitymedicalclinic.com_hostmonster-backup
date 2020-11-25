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

class Magpleasure_Blog_Helper_Import_Wordpress extends Magpleasure_Blog_Helper_Import
{
    protected $_wpToMpCatgory = array();
    protected $_wpToMpPost = array();

    protected $_connector;
    protected $_data = array();
    protected $_blogId = null;

    protected $_categoryMask = array(
        'categoryName' => array('name', 'meta_title'),
        'categoryId' => 'sort_order',
        'htmlUrl' => 'url_key',
    );

    protected $_postMask = array(
        'title' => array('title', 'meta_title'),
        'mt_text_more' => 'full_content',
        'description' => array('short_content'),
        'wp_slug' => 'url_key',
        'wp_author_display_name' => 'posted_by',
        'mt_keywords' => array('meta_tags', 'tags'),
        'mt_allow_comments' => 'use_comments',
    );

    protected $_commentMask = array(
        'date_created_gmt' => 'created_at',
        'author' => 'name',
        'author_email' => 'email',
        'content' => 'message',
    );

    protected function _getWordpressUrl()
    {
        return $this->_data['host']."xmlrpc.php";
    }

    /**
     * XML-RPC Connector
     *
     * @return Zend_XmlRpc_Client
     */
    protected function _getConnector()
    {
        if (!$this->_connector){
            $httpClient = new  Zend_Http_Client($this->_getWordpressUrl(), array('timeout' => 120));
            $connector = new Zend_XmlRpc_Client($this->_getWordpressUrl(), $httpClient);
            $this->_connector = $connector;
        }
        return $this->_connector;
    }

    protected $_postStatusConvert = array(
        'publish' => Magpleasure_Blog_Model_Post::STATUS_ENABLED,
    );

    protected $_commentStatusConvert = array(
        'hold' => Magpleasure_Blog_Model_Comment::STATUS_PENDING,
        'approve' => Magpleasure_Blog_Model_Comment::STATUS_APPROVED,
    );

    protected function _getParams($after = array(), $missBlogId = false)
    {
        $login = array(
            'username' => $this->_data['username'],
            'password' => $this->_data['password'],
        );

        if (!$missBlogId){
            $login = array_merge(array(
                'blogid' => $this->_getBlogId(),
            ), $login);
        }
        return array_merge($login, $after);
    }

    protected function _getBlogId()
    {
        if (is_null($this->_blogId)){
            $params = $this->_getParams(array(), true);
            $users = $this->_getConnector()->call("wp.getUsersBlogs", $params);
            foreach ($users as $user){
                $this->_blogId = $user['blogid'];
                break;
            }
        }
        return $this->_blogId;
    }

    public function import($verbose = false, $data = array())
    {
        $this->_data = $data;
        $this->importCategories($verbose);
        $this->importPosts($verbose);
        $this->importComments($verbose);
    }

    public function importCategories($verbose = false)
    {
        if ($verbose){
            echo "- Import Categories \n";
        }

        $params = $this->_getParams();
        $wpCategories = $this->_getConnector()->call("metaWeblog.getCategories", $params);

        foreach ($wpCategories as $wpCategory){

            $mpCategory = Mage::getModel('mpblog/category');
            $mpCategory
                ->addData($this->_prepareDataFromArray($this->_categoryMask, $wpCategory))
                ->setStatus(Magpleasure_Blog_Model_Category::STATUS_ENABLED)
                ->setUrlKey(str_replace( array($this->_data['host']."category/", $this->_data['host'], "/"), "", $mpCategory->getUrlKey()))
                ->setStores($this->_data['stores'])
                ->save()
                ;

            $this->_wpToMpCatgory[$wpCategory['categoryName']] = $mpCategory->getId();
        }

        return $this;
    }

    protected function _getCategories(array $wpCategories)
    {
        $result = array();
        foreach ($wpCategories as $wpCategoryId){
            if (isset($this->_wpToMpCatgory[$wpCategoryId])){
                $result[] = $this->_wpToMpCatgory[$wpCategoryId];
            }
        }
        return $result;
    }


    protected function _getCommentStatus($wpStatus)
    {
        if (isset($this->_commentStatusConvert[$wpStatus])){
            return $this->_commentStatusConvert[$wpStatus];
        }
        return false;
    }

    protected function _getPostStatus($wpStatus)
    {
        if (isset($this->_postStatusConvert[$wpStatus])){
            return $this->_postStatusConvert[$wpStatus];
        }
        return false;
    }

    protected function _prepareDateTime($stamp)
    {
        $date = new Zend_Date($stamp, Zend_Date::ISO_8601);
        return $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    }

    protected function _prepareWpContent($content)
    {
        # 1. Remove Captions
        $pattern = '/\[(\/|)caption(([\w\s1-9\=\"]{1,}\])|(\]))/';
        if ($newContent = preg_replace($pattern, "", $content)){
            $content = $newContent;
        }

        # 2. Import Images to Local Media Folder
        try {

            $simpleDom = $this->_helper()->getCommon()->getSimpleDOM()->str_get_html($content);
            $from = array();
            $to = array();

            foreach ($simpleDom->find('img') as $img){
                /** @var $img Magpleasure_Common_Helper_Simpledom_Dom_Node */
                $src = $img->getAttribute('src');
                $fileContent = $this->_helper()->getCommon()->getFiles()->getContentFromFile($src);

                if ($fileContent){

                    $remoteBasePath = $this->_data['host']."wp-content/uploads/";
                    $localBaseWebPath = Mage::getBaseUrl('media')."wysiwyg/wordpress/";
                    $localBasePath = Mage::getBaseDir('media').DS."wysiwyg".DS."wordpress";

                    $filePostfix = str_replace($remoteBasePath, "", $src);
                    $localWebFileName = $localBaseWebPath.$filePostfix;
                    $localPathFileName = $localBasePath.DS.str_replace("/", DS, $filePostfix);

                    $this->_helper()->getCommon()->getFiles()->saveContentToFile($localPathFileName, $fileContent);

                    $from[] = $src;
                    $to[] = $localWebFileName;
                }
            }

            $content = str_replace($from, $to, $content);

        } catch (Exception $e){
            $this->_helper()->getCommon()->getException()->logException($e);
        }

        # 3. Parse Youtube Embedded Links
        $youtubePattern = '<iframe class="youtube-player" type="text/html" '.
            '{{attributes}} src="//www.youtube.com/embed/{{v}}" '.
            'frameborder="0" allowFullScreen></iframe>';

        if (strpos($content, "[youtube") !== false){

            preg_match_all('/\[youtube(.*?)](.*?)\[\/youtube\]/msi', $content, $matches);

            if (count($matches[0])){
                for ($i=0;$i <= count($matches[0]) - 1;$i++){
                    if (isset($matches[0][$i]) && isset($matches[1][$i]) && isset($matches[2][$i])){


                        $youtubeBBCode = $matches[0][$i];
                        $attributesHtml = "";
                        $youtubeUrl = false;

                        if ($youtubeBBCode){

                            $attributesCode = $matches[1][$i];
                            $youtubeUrl = $matches[2][$i];

                            if ($attributesCode){

                                preg_match_all('/(\w*?)=\"(.*?)\"/msi', $youtubeBBCode, $attrMatches);

                                if (isset($attrMatches[0]) && is_array($attrMatches[0])){
                                    $attributesHtml = implode(" ", $attrMatches[0]);
                                }
                            }
                        }

                        $keyValParams = array();

                        $youtubeQuery = parse_url($youtubeUrl, PHP_URL_QUERY);

                        if ($youtubeQuery){
                            $youtubeQuery = explode("&", $youtubeQuery);

                            foreach ($youtubeQuery as $keyValString){
                                list($key, $value) = explode("=", $keyValString);

                                if ($key &&!is_null($value)){
                                    $keyValParams[$key] = $value;
                                }
                            }
                        }

                        $vParam = isset($keyValParams['v']) ? $keyValParams['v'] : false;

                        if ($vParam){
                            $readyYoutubeCode = str_replace(
                                array("{{attributes}}", "{{src}}", "{{v}}"),
                                array($attributesHtml, $youtubeUrl, $vParam),
                                $youtubePattern
                            );

                            $content = str_replace($youtubeBBCode, $readyYoutubeCode, $content);
                        }
                    }
                }

            }
        }

        # 4. Replace NL to Paragraphs
        $parts = explode("\n", $content);
        $content = "<p>".implode("</p><p>", $parts)."</p>";

        return $content;
    }

    public function importPosts($verbose = false)
    {
        if ($verbose){
            echo "- Import Posts \n";
        }

        $params = $this->_getParams(array('numberOfPosts' => 1000));

        try {
            $wpPosts = $this->_getConnector()->call("metaWeblog.getRecentPosts", $params);
        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
            $wpPosts = array();
        }

        if (is_array($wpPosts)){
            $wpPosts = array_reverse($wpPosts);
        }

        foreach ($wpPosts as $wpPost){

            $mpPost = Mage::getModel('mpblog/post');
            $mpPost
                ->addData($this->_prepareDataFromArray($this->_postMask, $wpPost))
                ->setStatus($this->_getPostStatus($wpPost['post_status']))
                ->setCategories($this->_getCategories($wpPost['categories']))
                ->setData('full_content', $this->_prepareWpContent($mpPost->getData('short_content').$mpPost->getData('full_content')))
                ->setData('short_content', $this->_prepareWpContent($mpPost->getData('short_content')))
                ->setStores($this->_data['stores'])
                ->save()
            ;

            $mpPost
                ->setCreatedAt($this->_prepareDateTime($wpPost['date_created_gmt']))
                ->setPublishedAt($this->_prepareDateTime($wpPost['date_created_gmt']))
                ->save()
                ;

            $this->_wpToMpPost[$wpPost['postid']] = $mpPost->getId();
        }
        return $this;
    }

    protected function _prepareComment($message)
    {
        $message = html_entity_decode($message);
        return strip_tags($message);
    }

    protected function _getCustomerId($email, $storeId = null)
    {
        /** @var Mage_Customer_Model_Customer $customer  */
        $customer = Mage::getModel('customer/customer');
        $customer
            ->setStore(Mage::app()->getStore($storeId))
            ->loadByEmail($email)
        ;

        if ($customer->getId()){
            return $customer->getId();
        }
        return null;
    }

    protected function _getStoreId()
    {
        if (count($this->_data['stores'])){
            return $this->_data['stores'][0];
        }

        return Mage::app()->getDefaultStoreView()->getId();
    }

    public function importComments($verbose = false)
    {
        if ($verbose){
            echo "- Import Comments \n";
        }

        $filter = array(
            'filter' => array(
                'number' => 10000,
            ),
        );

        $params = $this->_getParams($filter);
        $wpComments = $this->_getConnector()->call("wp.getComments", $params);

        foreach ($wpComments as $wpComment){

            if (isset($this->_wpToMpPost[$wpComment['post_id']])){

                /** @var Magpleasure_Blog_Model_Comment $mpComment */
                $mpComment = Mage::getModel('mpblog/comment');

                $storeId = $this->_getStoreId();
                $mpComment
                    ->addData($this->_prepareDataFromArray($this->_commentMask, $wpComment))
                    ->setStatus($this->_getCommentStatus($wpComment['status']))
                    ->setCustomerId($this->_getCustomerId($wpComment['author_email'], $storeId))
                    ->setPostId($this->_wpToMpPost[$wpComment['post_id']])
                    ->setStoreId($storeId)
                    ->save()
                    ;

                $mpComment
                    ->setCreatedAt($this->_prepareDateTime($wpComment['date_created_gmt']))
                    ->save()
                ;

            }
        }
        return $this;
    }

}