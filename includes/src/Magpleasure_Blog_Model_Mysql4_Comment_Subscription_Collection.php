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

class Magpleasure_Blog_Model_Mysql4_Comment_Subscription_Collection extends Magpleasure_Blog_Model_Mysql4_Abstract_Collection
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/comment_subscription');
    }

    public function addPostTextFilter($filter)
    {
        $postTable = Mage::getModel('mpblog/post')->getResource()->getMainTable();
        $this->getSelect()->join(array('post'=>$postTable), "main_table.post_id = post.post_id AND post.title LIKE ('%{$filter}%')", array());
        return $this;
    }

}