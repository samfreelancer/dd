CREATE TABLE IF NOT EXISTS `transactionItems` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transaction_id` INT(11) NOT NULL,
  `domain` VARCHAR(50) NOT NULL,
  `order_type` VARCHAR(255) DEFAULT NULL,
  `quantity` INT(2) UNSIGNED DEFAULT NULL,
  `unit_price` DECIMAL(15,2) DEFAULT NULL,
  `revised_price` DECIMAL(15,2) DEFAULT NULL,
  `revised_percentage` DECIMAL(5,2) DEFAULT NULL,
  `total_price` DECIMAL(15,2) DEFAULT NULL,
  `addedon` DATETIME NOT NULL,
  `updatedon` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `transactions` ADD `status` VARCHAR(20) NOT NULL AFTER `user_id`, ADD `amount_paid` DECIMAL(15,2) NOT NULL AFTER `status`, DROP COLUMN `domain_id`;

