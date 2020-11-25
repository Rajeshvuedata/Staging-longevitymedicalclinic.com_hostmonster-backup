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

class Magpleasure_Blog_Block_Adminhtml_Author_Edit
    extends Magpleasure_Common_Block_Adminhtml_Widget_Ajax_Form_Container
{

    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'mpblog';
        $this->_controller = 'adminhtml_author';
    }

    public function getHeaderText()
    {
        return $this->_helper()->__('Default Author Info for Blog Pro');
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

    public function onSave($id = null, array $data)
    {
        $userId = false;

        /** @var Mage_Admin_Model_User $user */
        $user = $this->_adminSesstion()->getUser();

        if ($user) {
            $userId = $user->getId();
        }

        $author = Mage::getModel('mpblog/author');
        if ($userId) {
            $author
                ->load($userId, 'user_id')
                ->addData($data)
                ->setUserId($userId)
                ->save();


        }

        return $this;
    }

    /**
     * Session
     *
     * @return Mage_Admin_Model_Session
     */
    protected function _adminSesstion()
    {
        return Mage::getSingleton('admin/session');
    }

    public function onLoad($id = null)
    {
        $userId = false;

        /** @var Mage_Admin_Model_User $user */
        $user = $this->_adminSesstion()->getUser();

        if ($user) {
            $userId = $user->getId();
            Mage::register('default_author_name', $user->getName(), true);
        }

        if ($userId) {
            $author = Mage::getModel('mpblog/author')->load($userId, 'user_id');
            if ($author->getId()) {
                Mage::register('author_model', $author, true);
            }
        }
        return $this;
    }


}