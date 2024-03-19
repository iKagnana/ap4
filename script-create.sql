# ************************************************************
# Sequel Ace SQL dump
# Version 20052
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: localhost (MySQL 8.0.36)
# Database: ap4
# Generation Time: 2024-03-19 11:38:06 +0000
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

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;

INSERT INTO `categories` (`id_cat`, `name_cat`)
VALUES
	(1,'Composant médicamenteux'),
	(2,'Verrerie'),
	(3,'Combinaison'),
	(4,'Matériel de recherche');

/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;


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
  CONSTRAINT `Modifier_Provider1_FK` FOREIGN KEY (`id_p_Provider`) REFERENCES `provider` (`id_pro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id_o` int NOT NULL AUTO_INCREMENT,
  `date_o` datetime NOT NULL,
  `price_o` float NOT NULL,
  `id_u` int NOT NULL,
  `id_u_User` int DEFAULT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `reason` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_pro` int DEFAULT NULL,
  PRIMARY KEY (`id_o`),
  KEY `Order_User_FK` (`id_u`),
  KEY `Order_User0_FK` (`id_u_User`),
  KEY `Order_PROVIDER_FK` (`id_pro`),
  CONSTRAINT `Order_PROVIDER_FK` FOREIGN KEY (`id_pro`) REFERENCES `provider` (`id_pro`),
  CONSTRAINT `Order_User0_FK` FOREIGN KEY (`id_u_User`) REFERENCES `users` (`id_u`),
  CONSTRAINT `Order_User_FK` FOREIGN KEY (`id_u`) REFERENCES `users` (`id_u`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;

INSERT INTO `orders` (`id_o`, `date_o`, `price_o`, `id_u`, `id_u_User`, `status`, `reason`, `id_pro`)
VALUES
	(1,'2024-03-03 15:42:59',39,1,1,'Validé','',NULL),
	(2,'2024-03-05 21:20:36',48,1,1,'Refusé','pas besoin',NULL),
	(3,'2024-03-05 22:07:21',162,1,1,'Validé','',NULL),
	(4,'2024-03-05 22:08:33',369,1,1,'Validé','RAS',1),
	(9,'2024-03-18 08:13:32',24,5,1,'Validé','',NULL),
	(10,'2024-03-18 10:39:32',37,1,1,'Validé','',NULL),
	(11,'2024-03-18 11:11:28',918,1,1,'Validé','',NULL),
	(12,'2024-03-18 11:11:48',204,1,NULL,'En attente de validation','',NULL);

/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`%` */ /*!50003 TRIGGER `UpdateProductStockTrigger` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
	IF NEW.status = "Validé" THEN
    		UPDATE products
    		JOIN orders_details ON orders_details.id_o = NEW.id_o
    		SET stock = orders_details.quantity + products.stock
    		WHERE products.id_p = orders_details.id_p;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table orders_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders_details`;

CREATE TABLE `orders_details` (
  `id_o` int NOT NULL,
  `id_p` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id_p`,`id_o`),
  KEY `Modifier_Order1_FK` (`id_o`),
  CONSTRAINT `Modifier_Order1_FK` FOREIGN KEY (`id_o`) REFERENCES `orders` (`id_o`) ON DELETE CASCADE,
  CONSTRAINT `Modifier_Product0_FK` FOREIGN KEY (`id_p`) REFERENCES `products` (`id_p`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `orders_details` WRITE;
/*!40000 ALTER TABLE `orders_details` DISABLE KEYS */;

INSERT INTO `orders_details` (`id_o`, `id_p`, `quantity`)
VALUES
	(1,2,3),
	(3,2,3),
	(10,2,-3),
	(2,3,3),
	(11,3,-9),
	(12,3,-2),
	(4,4,3),
	(9,4,-1),
	(2,5,1),
	(9,5,-2),
	(3,8,1);

/*!40000 ALTER TABLE `orders_details` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;

INSERT INTO `products` (`id_p`, `name_p`, `stock`, `price`, `access_level`, `id_cat`)
VALUES
	(2,'Blouse blanche',50,12.5,1,3),
	(3,'Molécule ARM',1,102,3,1),
	(4,'Fiole 25ml',98,3.8,1,2),
	(5,'Masque en papier (lot de 20)',196,10.5,1,3),
	(8,'Pipette',346,3.8,1,4);

/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table provider
# ------------------------------------------------------------

DROP TABLE IF EXISTS `provider`;

CREATE TABLE `provider` (
  `id_pro` int NOT NULL AUTO_INCREMENT,
  `name_pro` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email_pro` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_pro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `provider` WRITE;
/*!40000 ALTER TABLE `provider` DISABLE KEYS */;

INSERT INTO `provider` (`id_pro`, `name_pro`, `email_pro`)
VALUES
	(1,'MatMed','matmed@mail.com');

/*!40000 ALTER TABLE `provider` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id_u` int NOT NULL AUTO_INCREMENT,
  `lastname_u` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `firstname_u` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email_u` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` int NOT NULL,
  `level_access` int NOT NULL DEFAULT '1',
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'En attente de validation',
  `enterprise` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_u`),
  UNIQUE KEY `email_u` (`email_u`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id_u`, `lastname_u`, `firstname_u`, `email_u`, `password`, `role`, `level_access`, `status`, `enterprise`)
VALUES
	(1,'ITH','Kagnana','ikagnana@gmail.com','$2y$10$yP8ek7JYk9aJjwROAlqGUObbKR.AWRBSbEW/Q.K3jbpv3zPBxpH8i',0,3,'Validé','GSB'),
	(2,'Doe','John','j.doe@mail.com','$2y$10$VVOoCZ0N.iX0LxQN2Llu4.oTjwwY3PfngviW1zc0urU8PY82VPvwa',1,1,'Refusé','GSB'),
	(5,'Dupont','Jean','d.jean@fidele.com','$2y$10$b8SdYnGlQq4n87Isqj8eme0o9DSbKpd.wSccOT4NN73jLcw9VSuK6',2,2,'Validé','Fidele');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
