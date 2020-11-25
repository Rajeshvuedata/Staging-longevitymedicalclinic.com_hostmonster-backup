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

class Magpleasure_Blog_Model_Mysql4_Tag extends Magpleasure_Common_Model_Resource_Abstract
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function _construct()
    {    
        $this->_init('mpblog/tag', 'tag_id');
        $this->setUseUpdateDatetimeHelper(true);
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId()){
            $slug = $this->_helper()->getCommon()->getStrings()->generateSlug($object->getName());
            $object->setUrlKey($slug);
        }
        return parent::_beforeSave($object);
    }

    /**
     * Link Tag with Post
     *
     * @param Mage_Core_Model_Abstract $object
     * @param int $postId
     * @return Magpleasure_Blog_Model_Mysql4_Tag
     */
    public function linkWith($object, $postId)
    {
        if ($object->getId()){
            $id = $object->getId();
            $this->unlinkWith($object, $postId);
            $writeAdapter = $this->_getWriteAdapter()->beginTransaction();
            $tableName = $this->getTable('mpblog/post')."_tag";
            $select = $writeAdapter->select();
            $writeAdapter->insert($tableName, array(
                'post_id' => $postId,
                'tag_id' => $id,
            ));
            $writeAdapter->commit();
        }
        return $this;
    }

    /**
     * Unlink Tag with Post
     *
     * @param Mage_Core_Model_Abstract $object
     * @param int $postId
     * @return Magpleasure_Blog_Model_Mysql4_Tag
     */
    public function unlinkWith($object, $postId)
    {
        if ($tagId = $object->getId()){

            $writeAdapter = $this->_getWriteAdapter()->beginTransaction();
            $tableName = $this->getTable('mpblog/post')."_tag";
            $writeAdapter->delete($tableName, array(
                'tag_id = ?' => $tagId,
                'post_id = ?' => $postId,
            ));
            $writeAdapter->commit();

        }
        return $this;
    }

}