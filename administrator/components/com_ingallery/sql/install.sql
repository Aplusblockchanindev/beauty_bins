
CREATE TABLE IF NOT EXISTS `#__ingallery_gals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `config` text NOT NULL,
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_name` varchar(255) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_by_name` varchar(255) NOT NULL DEFAULT '',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
