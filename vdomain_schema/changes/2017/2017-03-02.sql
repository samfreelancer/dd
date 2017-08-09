ALTER TABLE `transactions` ADD COLUMN `cavv_result` INT(11) DEFAULT NULL;
ALTER TABLE `transactions`  ADD COLUMN `original_trans` VARCHAR(2000) DEFAULT NULL;
ALTER TABLE `domain` ADD COLUMN `is_voice_domain` BOOLEAN DEFAULT '0';
