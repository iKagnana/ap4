# ************************************************************
# Sequel Ace SQL dump
# Version 20052
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: localhost (MySQL 8.0.36)
# Database: ap4
# Generation Time: 2024-02-13 08:45:26 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id_cat` int NOT NULL AUTO_INCREMENT,
  `name_cat` varchar(100) NOT NULL,
  PRIMARY KEY (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

# Dump of table modify
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modify`;

CREATE TABLE `modify` (
  `id_p` int NOT NULL,
  `id_o` int NOT NULL,
  `id_p_Provider` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id_p`,`id_o`,`id_p_Provider`),
  KEY `Modifier_Order0_FK` (`id_o`),
  KEY `Modifier_Provider1_FK` (`id_p_Provider`),
  CONSTRAINT `Modifier_Order0_FK` FOREIGN KEY (`id_o`) REFERENCES `orders` (`id_o`),
  CONSTRAINT `Modifier_Product_FK` FOREIGN KEY (`id_p`) REFERENCES `products` (`id_p`),
  CONSTRAINT `Modifier_Provider1_FK` FOREIGN KEY (`id_p_Provider`) REFERENCES `provider` (`id_p`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id_o` int NOT NULL AUTO_INCREMENT,
  `date_o` date NOT NULL,
  `price_o` float NOT NULL,
  `id_u` int NOT NULL,
  `id_u_User` int NOT NULL,
  PRIMARY KEY (`id_o`),
  KEY `Order_User_FK` (`id_u`),
  KEY `Order_User0_FK` (`id_u_User`),
  CONSTRAINT `Order_User0_FK` FOREIGN KEY (`id_u_User`) REFERENCES `users` (`id_u`),
  CONSTRAINT `Order_User_FK` FOREIGN KEY (`id_u`) REFERENCES `users` (`id_u`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table orders_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders_details`;

CREATE TABLE `orders_details` (
  `id_o` int NOT NULL,
  `id_p` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id_p`,`id_o`),
  KEY `Modifier_Order1_FK` (`id_o`),
  CONSTRAINT `Modifier_Order1_FK` FOREIGN KEY (`id_o`) REFERENCES `orders` (`id_o`),
  CONSTRAINT `Modifier_Product0_FK` FOREIGN KEY (`id_p`) REFERENCES `products` (`id_p`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id_p` int NOT NULL AUTO_INCREMENT,
  `name_p` varchar(100) NOT NULL,
  `stock` int NOT NULL,
  `price` float NOT NULL,
  `access_level` int NOT NULL,
  `id_cat` int NOT NULL,
  PRIMARY KEY (`id_p`),
  KEY `Product_Category_FK` (`id_cat`),
  CONSTRAINT `Product_Category_FK` FOREIGN KEY (`id_cat`) REFERENCES `categories` (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table provider
# ------------------------------------------------------------

DROP TABLE IF EXISTS `provider`;

CREATE TABLE `provider` (
  `id_p` int NOT NULL AUTO_INCREMENT,
  `name_p` varchar(100) NOT NULL,
  `email_p` varchar(100) NOT NULL,
  PRIMARY KEY (`id_p`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id_u` int NOT NULL AUTO_INCREMENT,
  `lastname_u` varchar(100) NOT NULL,
  `firstname_u` varchar(100) NOT NULL,
  `email_u` varchar(100) NOT NULL UNIQUE,
  `password` varchar(100) NOT NULL,
  `role` int NOT NULL,
  PRIMARY KEY (`id_u`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
