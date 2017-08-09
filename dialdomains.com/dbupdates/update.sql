#2015-03-12 adding dbpuser to domains table
ALTER TABLE `user` ADD `dbpuser` INT(7) NOT NULL AFTER `user`;

#2015-03-12 adding period and purchased fields to domain table
ALTER TABLE `domain` ADD `period` INT(3) NOT NULL AFTER `orderid`;
ALTER TABLE `domain` ADD `purchased` TINYINT(1) NOT NULL AFTER `user_id`;

#2015-03-20 adding filds to domain table
ALTER TABLE `domain` ADD `deleted` TINYINT(1) NOT NULL AFTER `data`;
ALTER TABLE `domain` ADD `paid` TINYINT(1) NOT NULL AFTER `data`;
ALTER TABLE `domain` ADD `voice_domain` VARCHAR(255) NOT NULL AFTER `domain`;

#2015-03-25 change period from int to DATETIME
ALTER TABLE `domain` CHANGE `period` `period` DATETIME NOT NULL;
