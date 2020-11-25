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
  ADD COLUMN `google_profile` VARCHAR(255) NULL AFTER `posted_by`;

CREATE TABLE IF NOT EXISTS `{$this->getTable('mp_blog_authors')}`(
  `author_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `google_profile` VARCHAR(255),
  `created_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`author_id`)
);


");

$installer->endSetup();