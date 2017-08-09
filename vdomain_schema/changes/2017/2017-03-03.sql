ALTER TABLE `user` ADD COLUMN `profile_id` INT(11) DEFAULT NULL;
ALTER TABLE `user` ADD COLUMN `payment_profile_id_list` VARCHAR(255) DEFAULT NULL;
ALTER TABLE `user` ADD COLUMN `shipping_address_id_list` VARCHAR(255) DEFAULT NULL;
