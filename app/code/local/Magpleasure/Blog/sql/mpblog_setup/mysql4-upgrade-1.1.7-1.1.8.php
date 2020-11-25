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

$installer = $this;
$installer->startSetup();


try {

    $installer->run("

ALTER TABLE `{$this->getTable('mp_blog_comments')}`
  ADD INDEX (`post_id`, `store_id`, `status`, `reply_to`),
  ADD INDEX (`store_id`, `status`, `reply_to`);

    ");

} catch (Exception $e) {

    /** @var Magpleasure_Common_Helper_Data $helper */
    $helper = Mage::helper('magpleasure');
    $helper->getException()->logException($e);
}

try {

    $installer->run("

ALTER TABLE `{$this->getTable('mp_blog_tags')}`
  ADD INDEX (`url_key`);

    ");

} catch (Exception $e) {

    /** @var Magpleasure_Common_Helper_Data $helper */
    $helper = Mage::helper('magpleasure');
    $helper->getException()->logException($e);
}

try {

    $installer->run("

ALTER TABLE `{$this->getTable('mp_blog_posts')}`
  ADD INDEX (`status`, `url_key`),
  ADD INDEX (`status`);

    ");

} catch (Exception $e) {

    /** @var Magpleasure_Common_Helper_Data $helper */
    $helper = Mage::helper('magpleasure');
    $helper->getException()->logException($e);
}


try {

    $installer->run("

ALTER TABLE `{$this->getTable('mp_blog_categories')}`
  ADD INDEX (`url_key`, `status`);

    ");

} catch (Exception $e) {

    /** @var Magpleasure_Common_Helper_Data $helper */
    $helper = Mage::helper('magpleasure');
    $helper->getException()->logException($e);
}

$installer->endSetup();