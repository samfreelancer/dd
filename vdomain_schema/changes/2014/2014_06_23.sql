CREATE TABLE IF NOT EXISTS `domain` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain` char(255) NOT NULL,
  `readings` blob NOT NULL,
  `addedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `word` ADD  `usage` TINYINT UNSIGNED NOT NULL DEFAULT  '65';
