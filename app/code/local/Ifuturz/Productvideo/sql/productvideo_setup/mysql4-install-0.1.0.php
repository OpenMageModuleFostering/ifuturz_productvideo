<?php 
/**
 * @package Ifuturz_Productvideo
 */
$installer = $this;
$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('ifuturz_productvideo')};
CREATE TABLE {$this->getTable('ifuturz_productvideo')} (
  	`video_id` int(11) unsigned NOT NULL auto_increment,
	`product_id` int(11) NULL,	
	`video_name` varchar(255) NULL,
  PRIMARY KEY (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('productvideo_lck')};
CREATE TABLE {$this->getTable('productvideo_lck')} ( 	
	`flag` varchar(4),
	`value` ENUM('0','1') DEFAULT '0' NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{$installer->getTable('productvideo_lck')}` VALUES ('LCK','1');
");

$attributeInstaller = new Mage_Catalog_Model_Resource_Setup();

$attributeInstaller->addAttribute('catalog_product', 'product_video', array(  
  'type'              => 'text',
  'backend'           => '',
  'frontend'          => '',
  'label'             => 'Video',
  'input'             => 'textarea',
  'class'             => '',
  'backend'           => 'eav/entity_attribute_backend_array',   
  'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
  'visible'           => false,
  'required'          => false,
  'user_defined'      => false,
  'default'           => '',
  'searchable'        => false,
  'filterable'        => false,
  'comparable'        => false,
  'visible_on_front'  => false,
  'unique'            => false,
  'note'			  => 'add embed code from youtube, Vimeo, Brightcove, TidalTv, Wistia, Kaltura, dailymotion, LongTail, thePlatform, sublime video.etc..(recommended iframe width=350 & height=315)'
));

$installer->endSetup(); 

