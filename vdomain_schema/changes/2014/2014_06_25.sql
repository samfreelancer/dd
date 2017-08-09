ALTER TABLE `domain` DROP `readings`;
ALTER TABLE `domain` ADD  `user_id` INT UNSIGNED NOT NULL;

CREATE TABLE IF NOT EXISTS `domainReadingPart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` int(10) unsigned NOT NULL,
  `phrase_id` int(10) unsigned NOT NULL,
  `phrase_order` int(10) unsigned NOT NULL,
  `word_id` int(10) unsigned NOT NULL,
  `separator` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `word` ADD  `origin` ENUM(  'default',  'custom' ) NOT NULL DEFAULT  'default'