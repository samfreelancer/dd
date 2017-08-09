DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (	
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `session_id` VARCHAR(64) NOT NULL,
  `total_price` DECIMAL(15,2) DEFAULT '0',
  `sub_total_price` DECIMAL(15,2) DEFAULT '0',
  `addedon` DATETIME NOT NULL,
  `updatedon` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `cartItems` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) unsigned DEFAULT NULL,
  `domain` VARCHAR(255) NOT NULL,
  `quantity` int(2) unsigned DEFAULT NULL,
  `unit_price` DECIMAL(15,2) DEFAULT '0',  
  `total_price` DECIMAL(15,2) DEFAULT '0',
  `revised_price` DECIMAL(15,2) DEFAULT '0',
  `addedon` DATETIME NOT NULL,
  `updatedon` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
