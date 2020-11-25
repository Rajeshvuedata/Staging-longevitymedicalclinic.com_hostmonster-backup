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

DROP TABLE IF EXISTS `{$this->getTable('mp_blog_views')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('mp_blog_views')}`(
  `view_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` INT(10) UNSIGNED NOT NULL,
  `customer_id` INT(10) UNSIGNED,
  `session_id` VARCHAR(255) NOT NULL,
  `remote_addr` BIGINT(20) NOT NULL,
  `store_id` SMALLINT(5) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `referer_url` TINYTEXT NULL,
  PRIMARY KEY (`view_id`),
  INDEX `MPBLOG_VIEWS_POST_ID` (`post_id`),
  INDEX `MPBLOG_VIEWS_CUSTOMER_ID` (`customer_id`),
  INDEX `MPBLOG_VIEWS_SESSION_ID` (`session_id`),
  CONSTRAINT `FK_MPBLOG_VIEWS_POST` FOREIGN KEY (`post_id`) REFERENCES `{$this->getTable('mp_blog_posts')}`(`post_id`) ON UPDATE CASCADE ON DELETE CASCADE
);

ALTER TABLE `{$this->getTable('mp_blog_posts')}`
  ADD COLUMN `views` INT(10) DEFAULT 0  NOT NULL AFTER `comments_enabled`;

    ");

$installer->endSetup();