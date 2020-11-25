<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
-- DROP TABLE IF EXISTS {$this->getTable('tech9_events')};
CREATE TABLE {$this->getTable('tech9_events')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `event_date` varchar(255) NULL,
  `event_time` varchar(255) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  `street` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `state` varchar(255) NOT NULL default '',
  `zipcode` varchar(255) NOT NULL default '',    
  `event_start_date` varchar(255) NULL,
  `event_end_date` varchar(255) NULL,
  `event_start_time` varchar(255) NOT NULL default '',
  `event_end_time` varchar(255) NOT NULL default '',
  `description` longtext NOT NULL default '',
  `google_map` longtext NOT NULL default '',
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('tech9_seminar_reg')};
CREATE TABLE {$this->getTable('tech9_seminar_reg')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `fname` varchar(255) NOT NULL default '',
  `lname` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `phone` varchar(255) NOT NULL default '',
  `seminar` varchar(255) NOT NULL default '',
  `howhear` varchar(255) NOT NULL default '',
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
    ");
 
$installer->endSetup();