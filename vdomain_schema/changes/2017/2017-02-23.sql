ALTER TABLE `domain` ADD COLUMN `auto_renew` BOOLEAN DEFAULT '1';
ALTER TABLE `domain`  ADD COLUMN `last_auto_renewal` DATETIME;
