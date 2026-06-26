/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: layermaster
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-0+deb13u1 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `addons`
--

DROP TABLE IF EXISTS `addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `addons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addons`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `addons` WRITE;
/*!40000 ALTER TABLE `addons` DISABLE KEYS */;
INSERT INTO `addons` VALUES
(1,'手工焦糖餅乾',50),
(2,'極致鮮奶油醬',80),
(3,'季節限定草莓醬',100);
/*!40000 ALTER TABLE `addons` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cake_designs`
--

DROP TABLE IF EXISTS `cake_designs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cake_designs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('待審核','已採用') DEFAULT '待審核',
  `royalty_earned` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cake_designs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cake_designs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cake_designs` WRITE;
/*!40000 ALTER TABLE `cake_designs` DISABLE KEYS */;
/*!40000 ALTER TABLE `cake_designs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cake_models`
--

DROP TABLE IF EXISTS `cake_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cake_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `crust` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cake_models`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cake_models` WRITE;
/*!40000 ALTER TABLE `cake_models` DISABLE KEYS */;
INSERT INTO `cake_models` VALUES
(1,'經典原味千層','經典原味',1200,'2026-06-20 08:45:31'),
(2,'小山園抹茶千層','抹茶風味',1350,'2026-06-20 08:45:31'),
(3,'法式可可千層','可可風味',1300,'2026-06-20 08:45:31'),
(11,'頂級極上抹茶金箔千層','京都小山園抹茶薄餅皮',1200,'2026-06-20 10:42:17'),
(12,'法芙娜 85% 濃苦黑巧千層','法式純手工可可薄餅皮',1150,'2026-06-20 10:42:17'),
(13,'大溪地頂級香草籽千層','職人 30 層手工餅皮',1050,'2026-06-20 10:42:17'),
(14,'大湖精選草莓奶香千層','紅絲絨風味鮮乳餅皮',1350,'2026-06-20 10:42:17'),
(15,'阿里山頂級伯爵茶千層','手工炭焙茶香餅皮',1100,'2026-06-20 10:42:17'),
(16,'手工熬製大甲芋泥千層','鮮奶油夾心厚乳餅皮',1080,'2026-06-20 10:42:17'),
(17,'義大利頂級海鹽起司千層','焦糖酥脆香料餅皮',1250,'2026-06-20 10:42:17');
/*!40000 ALTER TABLE `cake_models` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `coupon_name` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `coupons_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES
(1,NULL,'WELCOME2026','2026-12-31'),
(2,NULL,'SUMMER50','2026-08-31'),
(3,NULL,'LAYER100','2026-09-30'),
(4,NULL,'SAVE20','2026-07-15'),
(5,NULL,'GIFT10','2026-10-01'),
(6,NULL,'CAKE2026','2026-12-31'),
(7,NULL,'PROMO88','2026-07-20'),
(8,NULL,'HAPPY888','2026-11-11'),
(9,NULL,'FRIEND50','2026-09-01'),
(10,NULL,'THANKS100','2026-12-25');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('guest','member','vip','admin') DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES
(1,'alice_vip','alice@example.com','123456','member','2026-06-20 12:17:45'),
(2,'bob_member','bob@example.com','123456','member','2026-06-20 12:17:45'),
(3,'charlie_admin','admin@example.com','admin123','vip','2026-06-20 12:17:45'),
(4,'david_user','david@example.com','123456','member','2026-06-20 12:17:45'),
(5,'eve_vip','eve@example.com','123456','vip','2026-06-20 12:17:45'),
(6,'a1133302','a1133302@mail.nuk.edu.tw','123456','vip','2026-06-20 12:18:17'),
(7,'admin','123456@gmail.com','123456','admin','2026-06-21 00:18:57'),
(8,'lucy','lucy@gmail.com','lucy123','member','2026-06-22 05:41:10'),
(9,'jack','jack@gmail.com','jack123','member','2026-06-22 05:43:36'),
(10,'jenny','jenny@gmail.com','jenny123','member','2026-06-22 05:46:11'),
(11,'Elly','elly950504@gmail.com','Elly1597','member','2026-06-22 05:46:35'),
(12,'a1133324','elly950405@gmail.com','123456','member','2026-06-22 06:32:34'),
(13,'1234','123@gmail.com','12345','member','2026-06-22 07:08:11');
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `discount_value` int(11) NOT NULL,
  `is_used` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES
(1,1,'加購：極致鮮奶油醬',80,1,0,0),
(2,2,'加購：極致鮮奶油醬',80,1,0,0),
(3,3,'手工焦糖餅乾',50,1,0,0),
(4,4,'客製化千層蛋糕 (25層 | 內餡: 鮮奶油 | 裝飾: 無 | 造型: 圓形)',1000,1,0,0),
(5,5,'義大利頂級海鹽起司千層',1250,0,0,0),
(6,6,'客製化千層蛋糕 (15層 | 內餡: 鮮奶油 | 裝飾: 無 | 造型: 圓形)',1000,1,0,0),
(7,7,'手工熬製大甲芋泥千層',1080,0,0,0),
(8,7,'客製化千層蛋糕 (15層 (入門級滑順口感) | 內餡: 大湖新鮮香草草莓內餡(+NT$150) | 裝飾: 海鹽起司奶蓋淋醬(+NT$80) | 造型: 傳統經典圓形(1.0x))',1230,1,0,0),
(9,10,'手工熬製大甲芋泥千層',1080,1,462,0),
(10,10,'客製化千層蛋糕 (15 | 內餡: 鮮奶油 | 裝飾: 不加裝飾 | 造型: 心形)',1000,1,462,0),
(11,10,'客製化千層蛋糕 (20 | 內餡: 草莓 | 裝飾: 不加裝飾 | 造型: 圓形)',1000,1,462,0),
(12,11,'義大利頂級海鹽起司千層',1250,1,0,0),
(13,11,'客製化千層蛋糕 (15層 (入門級滑順口感) | 內餡: 香醇特調鮮奶油(基礎價) | 裝飾: 海鹽起司奶蓋淋醬(+NT$80) | 造型: 傳統經典圓形(1.0x))',1080,1,0,0),
(14,12,'客製化千層蛋糕 (15層 (入門級滑順口感) | 內餡: 香醇特調鮮奶油(基礎價) | 裝飾: 不加裝飾 | 造型: 浪漫告白心形(1.2x造型手工費))',1200,1,120,0),
(15,13,'大溪地頂級香草籽千層',1050,1,0,0),
(16,13,'手工熬製大甲芋泥千層',1080,1,0,0),
(17,13,'客製化千層蛋糕 (25 | 內餡: 草莓 | 裝飾: 不加裝飾 | 造型: 圓形)',1000,1,0,0);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `delivery_date` date NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES
(1,'jojo','09090909','lantengjing331@gmail.com','56','2026-06-25','cash',180,'2026-06-20 15:36:29','shipped'),
(2,'jojo','09090909','lantengjing331@gmail.com','56','2026-06-25','cash',180,'2026-06-20 15:39:14','pending'),
(3,'jojo','09090909','lantengjing331@gmail.com','56','2026-06-25','cash',145,'2026-06-20 15:54:58','pending'),
(4,'jojo','09090909','lantengjing331@gmail.com','56','2026-06-25','cash',1100,'2026-06-20 16:46:27','pending'),
(5,'jojo','09090909','lantengjing331@gmail.com','56','2026-06-25','cash',1350,'2026-06-20 16:52:28','pending'),
(6,'jojo','09090909','lantengjing331@gmail.com','56','2026-06-25','cash',1100,'2026-06-20 17:20:11','pending'),
(7,'apple','3333333','lantengjing331@gmail.com','56','2026-06-25','credit_card',2410,'2026-06-21 00:15:26','pending'),
(8,'lucy','09123456','abc@gmail.com','test','2026-06-25','cash',2718,'2026-06-22 01:52:04','pending'),
(9,'lucy','09123456','abc@gmail.com','test','2026-06-25','cash',2718,'2026-06-22 01:52:13','pending'),
(10,'lucy','09123456','abc@gmail.com','test','2026-06-25','cash',2718,'2026-06-22 01:57:57','pending'),
(11,'Cady','12345567','qwer@mail.com','Xxxxxxxx','2026-06-27','cash',2430,'2026-06-22 01:59:52','pending'),
(12,'E','0900000000','elly950405@gmail.com','彰化市林森路160號','2026-06-27','cash',1180,'2026-06-22 05:52:20','pending'),
(13,'elly Huang','0911111111','elly950405@gmail.com','彰化市','2026-06-27','cash',3230,'2026-06-22 07:07:05','pending');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `promotions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
INSERT INTO `promotions` VALUES
(1,'618','買兩個以上蛋糕，打85折','2026-06-20 18:05:15');
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `top_flavors`
--

DROP TABLE IF EXISTS `top_flavors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `top_flavors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flavor_name` varchar(100) NOT NULL,
  `rank` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `top_flavors`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `top_flavors` WRITE;
/*!40000 ALTER TABLE `top_flavors` DISABLE KEYS */;
INSERT INTO `top_flavors` VALUES
(1,'經典原味鮮奶油',1,NULL),
(2,'濃郁芋頭泥',2,NULL),
(3,'小山園抹茶千層',3,NULL),
(4,'苦甜比利時巧克力',4,NULL),
(5,'季節限定草莓',5,NULL),
(6,'檸檬糖霜蛋糕',6,NULL),
(7,'焦糖巴斯克乳酪',7,NULL),
(8,'伯爵紅茶千層',8,NULL),
(9,'香蕉巧克力',9,NULL),
(10,'蜂蜜海鹽戚風',10,NULL);
/*!40000 ALTER TABLE `top_flavors` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `vip_level` enum('銀色','金色','黑色') DEFAULT '銀色',
  `points` int(11) DEFAULT 0,
  `order_history` text DEFAULT NULL,
  `uploaded_models` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'王小明','test1@example.com','password123','銀色',50,'[{\"item\":\"u5c0fu86cbu7cd5\",\"date\":\"2026-06-21 02:58:59\"}]',NULL),
(2,'李大華','test2@example.com','password456','黑色',3200,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-06-23  6:05:44

-- 新增 user_id 並建立關聯
ALTER TABLE orders 
ADD COLUMN user_id INT,
ADD CONSTRAINT fk_orders_user
FOREIGN KEY (user_id) REFERENCES users(id)
ON DELETE CASCADE;

