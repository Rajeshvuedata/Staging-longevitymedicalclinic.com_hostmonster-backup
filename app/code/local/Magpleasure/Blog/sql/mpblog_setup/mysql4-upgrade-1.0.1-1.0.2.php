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

$installer->run("

ALTER TABLE `{$this->getTable('mp_blog_posts')}`
  ADD COLUMN `published_at` TIMESTAMP NULL AFTER `updated_at`,
  ADD COLUMN `notify_on_enable` SMALLINT(1) DEFAULT 0  NOT NULL AFTER `published_at`,
  ADD COLUMN `display_short_content` SMALLINT(1) DEFAULT 1  NOT NULL AFTER `notify_on_enable`,
  ADD COLUMN `user_define_publish` SMALLINT(1) DEFAULT 0  NOT NULL AFTER `published_at`,
  ADD COLUMN `comments_enabled` SMALLINT(1) DEFAULT 1  NOT NULL AFTER `display_short_content`;

ALTER TABLE `{$this->getTable('mp_blog_tags')}`
  ADD COLUMN `meta_title` VARCHAR(255) NULL AFTER `url_key`,
  ADD COLUMN `meta_tags` VARCHAR(255) NULL AFTER `meta_title`,
  ADD COLUMN `meta_description` TINYTEXT NULL AFTER `meta_tags`;

    ");

$installer->endSetup();