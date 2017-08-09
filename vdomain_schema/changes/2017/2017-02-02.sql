ALTER TABLE  `transactions` ADD  `addedon` DATETIME NOT NULL , ADD  `updatedon` DATETIME DEFAULT NULL ;
ALTER TABLE `transactions` MODIFY COLUMN `domain_id` INT(11) DEFAULT NULL;
ALTER TABLE `domain` ADD `quantity` INT(2) UNSIGNED DEFAULT NULL, ADD `unit_price` decimal(15,2) DEFAULT NULL, ADD `revised_price` decimal(15,2) DEFAULT NULL, ADD `order_type` VARCHAR(255) DEFAULT NULL;
