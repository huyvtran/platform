-- MySQL dump 10.13  Distrib 5.6.23, for Win64 (x86_64)
--
-- Host: 45.117.77.125    Database: game_platform
-- ------------------------------------------------------
-- Server version	5.6.33-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `access_token`
--

DROP TABLE IF EXISTS `access_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `access_token` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `app` varchar(45) COLLATE utf8_unicode_ci NOT NULL COMMENT '1',
  `user_id` int(10) NOT NULL,
  `token` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT '1',
  `type` varchar(45) COLLATE utf8_unicode_ci NOT NULL COMMENT '1',
  `created` int(10) NOT NULL,
  `expired` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  KEY `user_id_app_key_expired` (`user_id`,`app`,`expired`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_token`
--

LOCK TABLES `access_token` WRITE;
/*!40000 ALTER TABLE `access_token` DISABLE KEYS */;
INSERT INTO `access_token` VALUES (1,'app1',1,'bW9iX3Rva2VuPTAuNDY3NTE1MDAgMTM3MjAxOTEzNDg1M','request',1372019134,2147483647),(2,'app1',4,'4C8BF88561AA03FC822EADE9B0491A6845E061CE','request',1494781983,2147483647),(3,'app1',10,'923CBC3A6D70FB82FB3EB6387440D4BEF1280D85','request',1494782216,2147483647),(4,'c3fc6b14acd9619bdd41c973a0278b20',10,'558BFD26A6AF54064427A4FC61EC12A0F5E0EBC6','request',1494865189,2147483647),(5,'app1',13,'2EFE3FB53962A55ABABDCD16C412A62A1FE8AD03','request',1494926060,2147483647),(6,'app1',3,'3DE5A687DE8F0F26156D70F8D45D4CA0E4ADAF99','request',1494930435,2147483647),(7,'c3fc6b14acd9619bdd41c973a0278b20',3,'B4E77F953F104052E6A1FC4C99D32F017511A5F5','request',1494930577,2147483647),(8,'ad7dd116b7c9c66293c029a6b96c5568',14,'24A949ACBFFF01C99CBCFB1DCC7A07BE306EAC42','request',1495025369,2147483647),(9,'ad7dd116b7c9c66293c029a6b96c5568',15,'740B046A8D904527B3527170959A6A33AEFF7ACB','request',1495025404,2147483647),(10,'ad7dd116b7c9c66293c029a6b96c5568',16,'FD4CCA1D9F0D479F3BA72F3500A5D90E77EC8592','request',1495025652,2147483647),(11,'ad7dd116b7c9c66293c029a6b96c5568',17,'F60A68C2A73876A639F472818CBBBBC9F900EB6D','request',1495025699,2147483647),(12,'ad7dd116b7c9c66293c029a6b96c5568',18,'43ECFD134B0B2E0F74438EBAA5913A15C2732B15','request',1495025762,2147483647),(13,'ad7dd116b7c9c66293c029a6b96c5568',19,'8A3B48F4F5D961BAD3FD67F4C4277E97A021A1DF','request',1495025834,2147483647),(14,'ad7dd116b7c9c66293c029a6b96c5568',20,'57E739C534C534BB764D1A60B3C9A85D22D6DDBB','request',1495025953,2147483647),(15,'ad7dd116b7c9c66293c029a6b96c5568',21,'8D1E61093D552516B143C81F9624CDC8B3A690C9','request',1495025980,2147483647),(16,'ad7dd116b7c9c66293c029a6b96c5568',22,'0AA40AED4455C6E8853E4229B38CB767E89992F7','request',1495026074,2147483647),(17,'ad7dd116b7c9c66293c029a6b96c5568',23,'6FE17C21EDA3E485F8D67DE9EBE2EE61106E3227','request',1495026116,2147483647),(18,'ad7dd116b7c9c66293c029a6b96c5568',24,'9F5946552290A8E5EA920493A0C6259A3A2BB80D','request',1495026376,2147483647),(19,'ad7dd116b7c9c66293c029a6b96c5568',25,'13CED3B003BDA45775CB9F0E2E7A1FACE6F2C670','request',1495026398,2147483647),(20,'ad7dd116b7c9c66293c029a6b96c5568',26,'6AFEFB42635E67902B145850469DDCBA45679815','request',1495026535,2147483647),(21,'ad7dd116b7c9c66293c029a6b96c5568',27,'C9F242183DAD668EEA3CB8D1431C364A69638504','request',1495027102,2147483647),(22,'ad7dd116b7c9c66293c029a6b96c5568',28,'702A1FDC670D8462204601ED1D61EABC14F6A06E','request',1495028873,2147483647),(23,'ad7dd116b7c9c66293c029a6b96c5568',29,'FE779BE6959FFFED7DB9DDA8C1196A4C0C95EC0C','request',1495028966,2147483647),(24,'ad7dd116b7c9c66293c029a6b96c5568',30,'83A7E14682D1D550C223727E863928C43CFEAE97','request',1495094493,2147483647),(25,'ad7dd116b7c9c66293c029a6b96c5568',31,'8919F4DCC49E5E69151F8EC0E351AE0F5F3A41F5','request',1495113505,2147483647),(26,'ad7dd116b7c9c66293c029a6b96c5568',32,'6EA837F6A971372D3277FC90A10DB82C2FC7FFFC','request',1495114152,2147483647),(27,'ad7dd116b7c9c66293c029a6b96c5568',33,'D7A0B19F25E4609E008C2B7AC617420BFF31B901','request',1495115557,2147483647),(28,'ad7dd116b7c9c66293c029a6b96c5568',34,'FA749B08378C6E384B0C847AEB6B8F036446514F','request',1495138682,2147483647),(29,'ad7dd116b7c9c66293c029a6b96c5568',35,'A028C47F8B5EEAE81B383D50A1AD8AFC4BA2D9F7','request',1495162589,2147483647);
/*!40000 ALTER TABLE `access_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `game_id` int(10) NOT NULL,
  `account_id` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `fb_id` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_game_center` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_google_play` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_game_id` (`user_id`,`game_id`),
  KEY `user_id` (`user_id`),
  KEY `game_id` (`game_id`),
  KEY `game_id_fb_id` (`game_id`,`fb_id`),
  KEY `created` (`created`),
  KEY `account_id_game_id` (`account_id`,`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,1,1,100000,'0000-00-00 00:00:00','2014-03-05 19:21:01',NULL,NULL,NULL),(2,4,1,23,'2017-05-15 00:13:03','2017-05-15 00:13:03',NULL,NULL,NULL),(3,10,1,33,'2017-05-15 00:16:56','2017-05-15 00:16:56',NULL,NULL,NULL),(4,10,2,33,'2017-05-15 23:19:49','2017-05-15 23:19:49',NULL,NULL,NULL),(5,13,1,54,'2017-05-16 16:14:20','2017-05-16 16:14:20',NULL,NULL,NULL),(6,3,2,65,'2017-05-16 17:29:37','2017-05-16 17:29:37',NULL,NULL,NULL),(7,3,1,74,'2017-05-16 18:02:51','2017-05-16 18:02:51',NULL,NULL,NULL),(8,14,3,81,'2017-05-17 19:49:29','2017-05-17 19:49:29',NULL,NULL,NULL),(9,15,3,99,'2017-05-17 19:50:04','2017-05-17 19:50:04',NULL,NULL,NULL),(10,16,3,109,'2017-05-17 19:54:12','2017-05-17 19:54:12',NULL,NULL,NULL),(11,17,3,114,'2017-05-17 19:54:59','2017-05-17 19:54:59',NULL,NULL,NULL),(12,18,3,129,'2017-05-17 19:56:02','2017-05-17 19:56:02',NULL,NULL,NULL),(13,19,3,131,'2017-05-17 19:57:14','2017-05-17 19:57:14',NULL,NULL,NULL),(14,20,3,142,'2017-05-17 19:59:13','2017-05-17 19:59:13',NULL,NULL,NULL),(15,21,3,156,'2017-05-17 19:59:40','2017-05-17 19:59:40',NULL,NULL,NULL),(16,22,3,166,'2017-05-17 20:01:14','2017-05-17 20:01:14',NULL,NULL,NULL),(17,23,3,178,'2017-05-17 20:01:56','2017-05-17 20:01:56',NULL,NULL,NULL),(18,24,3,185,'2017-05-17 20:06:16','2017-05-17 20:06:16',NULL,NULL,NULL),(19,25,3,198,'2017-05-17 20:06:38','2017-05-17 20:06:38',NULL,NULL,NULL),(20,26,3,208,'2017-05-17 20:08:55','2017-05-17 20:08:55',NULL,NULL,NULL),(21,27,3,219,'2017-05-17 20:18:22','2017-05-17 20:18:22',NULL,NULL,NULL),(22,28,3,228,'2017-05-17 20:47:53','2017-05-17 20:47:53',NULL,NULL,NULL),(23,29,3,233,'2017-05-17 20:49:26','2017-05-17 20:49:26',NULL,NULL,NULL),(24,30,3,246,'2017-05-18 15:01:32','2017-05-18 15:01:32',NULL,NULL,NULL),(25,31,3,258,'2017-05-18 20:18:25','2017-05-18 20:18:25',NULL,NULL,NULL),(26,32,3,265,'2017-05-18 20:29:12','2017-05-18 20:29:12',NULL,NULL,NULL),(27,33,3,273,'2017-05-18 20:52:37','2017-05-18 20:52:37',NULL,NULL,NULL),(28,34,3,287,'2017-05-19 03:18:02','2017-05-19 03:18:02',NULL,NULL,NULL),(29,35,3,292,'2017-05-19 09:56:29','2017-05-19 09:56:29',NULL,NULL,NULL);
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `parsed_body` text COLLATE utf8_unicode_ci NOT NULL,
  `summary` text COLLATE utf8_unicode_ci,
  `user_id` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `position` int(10) NOT NULL,
  `category_id` int(10) DEFAULT NULL,
  `type_of_category` int(10) DEFAULT NULL COMMENT 'Kiểu bài viết phụ cho từng chuyên mục',
  `slug` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `published_date` datetime DEFAULT NULL,
  `is_hot` tinyint(1) NOT NULL DEFAULT '0',
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `is_event` tinyint(1) NOT NULL DEFAULT '0',
  `website_id` int(10) NOT NULL,
  `event_start` datetime DEFAULT NULL,
  `event_end` datetime DEFAULT NULL,
  `key_words` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `markup` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `is_share` tinyint(1) DEFAULT NULL,
  `is_open_server` tinyint(1) DEFAULT '0',
  `open_server_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `position` (`position`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  KEY `title` (`title`(255)),
  KEY `created` (`created`),
  KEY `published_date` (`published_date`),
  KEY `website_id_published_event_start_event_end` (`website_id`,`published`,`event_start`,`event_end`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'Chốt hướng cải cách triệt để lương công chức, viên chức từ 2018?','Ký báo cáo về việc thực hiện nghị quyết của Quốc hội về chất vấn và trả lời chất vấn tại kỳ họp Quốc hội thứ 2 (diễn ra cuối năm 2016), Thứ trưởng Bộ Nội vụ Nguyễn Trọng Thừa thông tin về tiến độ xây dựng đề án cải cách tiền lương, biên chế công chức, viên chức.\r\n\r\nLãnh đạo Bộ Nội vụ nhấn mạnh, tiền lương luôn là lĩnh vực được các vị đại biểu Quốc hội quan tâm. Về phía mình, Bộ đã thực hiện 3 nhiệm vụ: Trình Thủ tướng ban hành quyết định về việc thành lập Ban chỉ đạo Trung ương cách chính sách tiền lương, bảo hiểm xã hội và ưu đãi người có công; Trình Phó Thủ tướng Vương Đình Huệ - Trưởng ban Chỉ đạo Trung ương về cải cách chính sách tiền lương, bảo hiểm xã hội và ưu đãi người có công ban hành quyết định số 15 kèm theo quy chế làm việc của Ban Chỉ đạo và phối hợp với các cơ quan thành viên Ban Chỉ đạo tiền lương nhà nước triển khai xây dựng dự thảo đề án về cải cách chính sách tiền lương đối với cán bộ, công chức, viên chức, lực lượng vũ trang và người lao động trong các doanh nghiệp để trình hội nghị Trung ương 7 (tháng 4/2018) xem xét.','<p>Ký báo cáo về việc thực hiện nghị quyết của Quốc hội về chất vấn và trả lời chất vấn tại kỳ họp Quốc hội thứ 2 (diễn ra cuối năm 2016), Thứ trưởng Bộ Nội vụ Nguyễn Trọng Thừa thông tin về tiến độ xây dựng đề án cải cách tiền lương, biên chế công chức, viên chức.</p>\n\n<p>Lãnh đạo Bộ Nội vụ nhấn mạnh, tiền lương luôn là lĩnh vực được các vị đại biểu Quốc hội quan tâm. Về phía mình, Bộ đã thực hiện 3 nhiệm vụ: Trình Thủ tướng ban hành quyết định về việc thành lập Ban chỉ đạo Trung ương cách chính sách tiền lương, bảo hiểm xã hội và ưu đãi người có công; Trình Phó Thủ tướng Vương Đình Huệ - Trưởng ban Chỉ đạo Trung ương về cải cách chính sách tiền lương, bảo hiểm xã hội và ưu đãi người có công ban hành quyết định số 15 kèm theo quy chế làm việc của Ban Chỉ đạo và phối hợp với các cơ quan thành viên Ban Chỉ đạo tiền lương nhà nước triển khai xây dựng dự thảo đề án về cải cách chính sách tiền lương đối với cán bộ, công chức, viên chức, lực lượng vũ trang và người lao động trong các doanh nghiệp để trình hội nghị Trung ương 7 (tháng 4/2018) xem xét.</p>\n','Chốt hướng cải cách triệt để lương công chức, viên chức từ 2018?',1,'2017-05-18 10:59:20','2017-05-18 11:17:03',1,5,NULL,'chot-huong-cai-cach-triet-de-luong-cong-chuc-vien-chuc-tu-2018',1,'2017-05-18 11:17:03',1,0,0,2,NULL,NULL,NULL,'markdown',NULL,0,NULL);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `type` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Article',
  `article_count` int(10) NOT NULL,
  `lft` int(10) NOT NULL,
  `rght` int(10) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `category_id` int(10) DEFAULT NULL,
  `website_id` int(10) DEFAULT NULL,
  `for_dashboard` tinyint(1) NOT NULL DEFAULT '0',
  `level` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Slug` (`slug`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'News','news','News','Article',0,1,2,'2017-05-18 09:27:01','2017-05-18 09:27:01',NULL,NULL,0,NULL),(2,'Events','events','Events','Article',0,3,4,'2017-05-18 09:27:16','2017-05-18 09:27:16',NULL,NULL,0,NULL),(5,'News','news','News','Article',1,5,6,'2017-05-18 10:21:31','2017-05-18 10:21:31',NULL,2,0,NULL),(6,'Events','events','Events','Article',0,7,8,'2017-05-18 10:21:42','2017-05-18 10:21:42',NULL,2,0,NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `app` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `secret_key` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `parsed_description` text COLLATE utf8_unicode_ci,
  `changelog` text COLLATE utf8_unicode_ci,
  `parsed_changelog` text COLLATE utf8_unicode_ci,
  `released` date DEFAULT NULL,
  `play_count` int(10) NOT NULL DEFAULT '0',
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `short_words` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `g_ver` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `support_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fbpage_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `support_devices` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appstore_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `jailbreak_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_gaid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dashboard_gaid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_theme` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_testflightid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_paypalid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forum_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language_default` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `apns` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `os` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `website_id` int(10) DEFAULT NULL,
  `gcm_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_appid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_appsecret` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal` text COLLATE utf8_unicode_ci,
  `screen` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `show_on_mobpage` tinyint(1) DEFAULT '0',
  `version_code` int(11) NOT NULL DEFAULT '1',
  `mobhub_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobhub_published` tinyint(1) NOT NULL DEFAULT '0',
  `mobhub_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobhub_package` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobhub_md5` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_on_funtap` tinyint(1) DEFAULT '0',
  `published_date` datetime DEFAULT NULL,
  `show_on_mail` int(2) DEFAULT '0',
  `show_on_gate_app` tinyint(1) NOT NULL DEFAULT '0',
  `show_image_gate_app` tinyint(1) NOT NULL DEFAULT '0',
  `group` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hide_all_payment` int(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `app_key` (`app`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (1,'Demo App','app1','c3fc6b14acd961','- Coming Soon\r\n- Coming Soon\r\n- Coming Soon\r\n- [Coming Soon][1]\r\n\r\n  [1]: http://localhost:8088/platform','<ul>\n<li>Coming Soon</li>\n<li>Coming Soon</li>\n<li>Coming Soon</li>\n<li><a href=\"http://localhost:8088/platform\">Coming Soon</a></li>\n</ul>\n',NULL,NULL,'2013-05-02',5,'demoapp-ios','','2013-05-02 00:44:44','2017-05-18 11:22:22','0.1','support@mobgame.vn','109336685751749','> iOS 4.3','itms-apps://itunes.apple.com/vn/app/tinh-kiem/id597768531?mt=8&uo=4','itms-services://?action=download-manifest&url=http://dungbx.com/mobgame/MobGameDemo.plist','UA-41972645-3','','Dashboard','1','1','http://forum.soha.vn','vie',1,'','demoapp','ios',1,'','','','a:8:{s:20:\"client_id_production\";s:0:\"\";s:17:\"client_id_sandbox\";s:0:\"\";s:13:\"client_secret\";s:0:\"\";s:14:\"merchant_email\";s:0:\"\";s:13:\"merchant_name\";s:0:\"\";s:11:\"privacy_url\";s:0:\"\";s:18:\"user_agreement_url\";s:0:\"\";s:19:\"accept_credit_cards\";s:1:\"0\";}','','a:41:{s:10:\"google_iab\";a:2:{s:7:\"hashkey\";s:0:\"\";s:5:\"token\";s:0:\"\";}s:16:\"hide_login_email\";s:1:\"0\";s:19:\"hide_login_facebook\";s:1:\"0\";s:12:\"hide_payment\";s:1:\"0\";s:21:\"hide_for_game_version\";s:0:\"\";s:5:\"admob\";a:3:{s:13:\"conversion_id\";s:0:\"\";s:16:\"conversion_label\";s:0:\"\";s:16:\"conversion_value\";s:0:\"\";}s:5:\"adway\";a:2:{s:15:\"appIdPartyTrack\";s:0:\"\";s:16:\"appKeyPartyTrack\";s:0:\"\";}s:9:\"appsflyer\";a:4:{s:17:\"appsflyer_dev_key\";s:0:\"\";s:12:\"apple_app_id\";s:0:\"\";s:8:\"currency\";s:0:\"\";s:11:\"is_use_http\";s:1:\"0\";}s:19:\"hide_update_account\";s:1:\"0\";s:13:\"hide_giftcode\";s:1:\"0\";s:6:\"invite\";a:4:{s:9:\"invite_fb\";s:0:\"\";s:10:\"invite_sms\";s:0:\"\";s:18:\"invite_title_email\";s:0:\"\";s:12:\"invite_email\";s:0:\"\";}s:7:\"payment\";a:1:{s:11:\"testallowed\";s:0:\"\";}s:10:\"fbpage_url\";s:0:\"\";s:11:\"group_fb_id\";s:0:\"\";s:13:\"support_skype\";s:8:\"quanvh90\";s:13:\"fanpage_image\";a:5:{s:4:\"name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"tmp_name\";s:0:\"\";s:5:\"error\";s:1:\"4\";s:4:\"size\";s:1:\"0\";}s:15:\"invitefb2_image\";a:5:{s:4:\"name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"tmp_name\";s:0:\"\";s:5:\"error\";s:1:\"4\";s:4:\"size\";s:1:\"0\";}s:18:\"fanpage_image_vote\";a:5:{s:4:\"name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"tmp_name\";s:0:\"\";s:5:\"error\";s:1:\"4\";s:4:\"size\";s:1:\"0\";}s:6:\"hockey\";a:1:{s:6:\"app_id\";s:0:\"\";}s:7:\"qr_code\";a:6:{s:3:\"url\";s:69:\"https://s3-ap-southeast-1.amazonaws.com/emagbom.plf/558bdaeff18d9.png\";s:4:\"name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"tmp_name\";s:0:\"\";s:5:\"error\";s:1:\"4\";s:4:\"size\";s:1:\"0\";}s:21:\"image_background_game\";a:6:{s:3:\"url\";s:69:\"https://s3-ap-southeast-1.amazonaws.com/emagbom.plf/55aefe9272706.png\";s:4:\"name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"tmp_name\";s:0:\"\";s:5:\"error\";s:1:\"4\";s:4:\"size\";s:1:\"0\";}s:17:\"short_description\";s:0:\"\";s:16:\"game_update_hour\";s:0:\"\";s:16:\"game_update_date\";s:0:\"\";s:17:\"hide_login_google\";s:1:\"0\";s:15:\"hide_popup_coin\";s:1:\"0\";s:8:\"hide_ads\";s:1:\"1\";s:16:\"hide_menu_submit\";s:1:\"0\";s:16:\"enable_dashboard\";s:1:\"1\";s:13:\"hide_play_now\";s:1:\"1\";s:25:\"show_register_user_detail\";s:1:\"0\";s:17:\"allow_game_by_age\";s:1:\"0\";s:11:\"age_allowed\";s:0:\"\";s:9:\"vcurrency\";a:1:{s:4:\"type\";s:0:\"\";}s:7:\"pem_url\";N;s:9:\"client_id\";s:0:\"\";s:7:\"key_ads\";s:0:\"\";s:4:\"menu\";a:11:{s:4:\"news\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:8:\"giftcode\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:6:\"report\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:9:\"offerwall\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"0\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:6:\"invite\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";}s:5:\"guide\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"0\";}s:7:\"website\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";}s:7:\"fanpage\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:9:\"community\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:5:\"email\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";}s:7:\"profile\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}}s:9:\"time_zone\";s:0:\"\";s:8:\"is_close\";s:1:\"0\";s:15:\"game_updated_at\";s:19:\"2017-05-13 00:00:00\";}',1,1,'',1,'','','','quanvh',0,'2015-07-20 02:33:22',0,0,0,NULL,0),(2,'Demo App','c3fc6b14acd9619bdd41c973a0278b20','c3fc6b14acd961','Ai là gà',NULL,NULL,NULL,'2013-05-03',2,'demoapp-android','','2013-05-03 19:37:03','2017-05-18 11:21:57',NULL,'','',NULL,NULL,NULL,'','','Dashboard','','',NULL,'vie',0,NULL,'demoapp','android',1,'','12345667','afasfnsadg','a:8:{s:20:\"client_id_production\";s:0:\"\";s:17:\"client_id_sandbox\";s:0:\"\";s:13:\"client_secret\";s:0:\"\";s:14:\"merchant_email\";s:0:\"\";s:13:\"merchant_name\";s:0:\"\";s:11:\"privacy_url\";s:0:\"\";s:18:\"user_agreement_url\";s:0:\"\";s:19:\"accept_credit_cards\";s:1:\"0\";}','','a:27:{s:10:\"google_iab\";a:2:{s:7:\"hashkey\";s:0:\"\";s:5:\"token\";s:0:\"\";}s:16:\"hide_login_email\";s:1:\"0\";s:19:\"hide_login_facebook\";s:1:\"0\";s:12:\"hide_payment\";s:1:\"0\";s:19:\"hide_update_account\";s:1:\"0\";s:13:\"hide_giftcode\";s:1:\"0\";s:21:\"hide_for_game_version\";s:0:\"\";s:5:\"admob\";a:3:{s:13:\"conversion_id\";s:0:\"\";s:16:\"conversion_label\";s:0:\"\";s:16:\"conversion_value\";s:0:\"\";}s:5:\"adway\";a:2:{s:15:\"appIdPartyTrack\";s:0:\"\";s:16:\"appKeyPartyTrack\";s:0:\"\";}s:9:\"appsflyer\";a:4:{s:17:\"appsflyer_dev_key\";s:0:\"\";s:12:\"apple_app_id\";s:0:\"\";s:8:\"currency\";s:0:\"\";s:11:\"is_use_http\";s:1:\"0\";}s:6:\"hockey\";a:1:{s:6:\"app_id\";s:0:\"\";}s:6:\"invite\";a:4:{s:9:\"invite_fb\";s:0:\"\";s:10:\"invite_sms\";s:0:\"\";s:18:\"invite_title_email\";s:0:\"\";s:12:\"invite_email\";s:0:\"\";}s:7:\"payment\";a:1:{s:11:\"testallowed\";s:0:\"\";}s:17:\"hide_login_google\";s:1:\"0\";s:15:\"hide_popup_coin\";s:1:\"0\";s:8:\"hide_ads\";s:1:\"0\";s:16:\"hide_menu_submit\";s:1:\"0\";s:16:\"enable_dashboard\";s:1:\"1\";s:13:\"hide_play_now\";s:1:\"1\";s:25:\"show_register_user_detail\";s:1:\"0\";s:17:\"allow_game_by_age\";s:1:\"0\";s:11:\"age_allowed\";s:0:\"\";s:9:\"vcurrency\";a:1:{s:4:\"type\";s:0:\"\";}s:7:\"pem_url\";N;s:9:\"client_id\";s:0:\"\";s:7:\"key_ads\";s:0:\"\";s:4:\"menu\";a:11:{s:4:\"news\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:8:\"giftcode\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:6:\"report\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:9:\"offerwall\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"0\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:6:\"invite\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";}s:5:\"guide\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"0\";}s:7:\"website\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";}s:7:\"fanpage\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:9:\"community\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:5:\"email\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";}s:7:\"profile\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}}}',0,1,'',1,'','','','quanvh',0,NULL,0,0,0,NULL,0),(3,'MU Takan','ad7dd116b7c9c66293c029a6b96c5568','568bf93d8f88611d2313b0abb3e4d089','MU Takan','<p>MU Takan</p>\n',NULL,NULL,NULL,22,'mu-takan-android','TaKan','2017-05-17 18:50:24','2017-05-18 19:57:28','','','','','','',NULL,'','TakanDashboard',NULL,'',NULL,'vie',0,NULL,'mu-takan','android',2,'','','','a:0:{}','','a:13:{s:10:\"fbpage_url\";s:0:\"\";s:11:\"group_fb_id\";s:0:\"\";s:17:\"short_description\";s:0:\"\";s:16:\"game_update_hour\";s:0:\"\";s:16:\"game_update_date\";s:0:\"\";s:13:\"support_skype\";s:0:\"\";s:9:\"time_zone\";s:0:\"\";s:8:\"is_close\";s:1:\"0\";s:16:\"hide_login_email\";s:1:\"1\";s:19:\"hide_login_facebook\";s:1:\"0\";s:19:\"hide_update_account\";s:1:\"0\";s:13:\"hide_giftcode\";s:1:\"0\";s:21:\"hide_for_game_version\";s:0:\"\";}',0,1,NULL,0,NULL,NULL,NULL,'quanvh',0,'2017-05-18 11:23:40',0,0,0,NULL,0);
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games_genres`
--

DROP TABLE IF EXISTS `games_genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games_genres` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `game_id` int(10) NOT NULL,
  `genre_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games_genres`
--

LOCK TABLES `games_genres` WRITE;
/*!40000 ALTER TABLE `games_genres` DISABLE KEYS */;
/*!40000 ALTER TABLE `games_genres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genres`
--

DROP TABLE IF EXISTS `genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genres`
--

LOCK TABLES `genres` WRITE;
/*!40000 ALTER TABLE `genres` DISABLE KEYS */;
INSERT INTO `genres` VALUES (1,'Casual','','2013-05-28 02:45:27','2013-05-28 02:45:27','casual'),(2,'MMO','','2013-05-28 02:45:34','2013-05-28 02:45:34','mmo'),(3,'RPG','','2013-05-28 02:45:47','2013-05-28 02:45:47','rpg');
/*!40000 ALTER TABLE `genres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `i18n`
--

DROP TABLE IF EXISTS `i18n`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `i18n` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `locale` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `foreign_key` int(10) NOT NULL,
  `field` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `locale` (`locale`),
  KEY `model` (`model`),
  KEY `row_id` (`foreign_key`),
  KEY `field` (`field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `i18n`
--

LOCK TABLES `i18n` WRITE;
/*!40000 ALTER TABLE `i18n` DISABLE KEYS */;
/*!40000 ALTER TABLE `i18n` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_entergames`
--

DROP TABLE IF EXISTS `log_entergames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_entergames` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `game_id` int(10) NOT NULL,
  `g_ver` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sdk_ver` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `os` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `network` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area_id` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_created` (`user_id`,`created`),
  KEY `role_id_game_id_area_id_created` (`role_id`,`game_id`,`created`,`area_id`),
  KEY `role_id_game_id_created` (`role_id`,`game_id`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_entergames`
--

LOCK TABLES `log_entergames` WRITE;
/*!40000 ALTER TABLE `log_entergames` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_entergames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_logins`
--

DROP TABLE IF EXISTS `log_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_logins` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `os` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resolution` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sdk_ver` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_id` int(10) NOT NULL,
  `g_ver` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `network` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_created` (`user_id`,`created`),
  KEY `created_game_id` (`created`,`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_logins`
--

LOCK TABLES `log_logins` WRITE;
/*!40000 ALTER TABLE `log_logins` DISABLE KEYS */;
INSERT INTO `log_logins` VALUES (1,4,'2017-05-15 00:13:03','2017-05-15 00:14:43','','','',1,'','','','10.0.2.2'),(2,4,'2017-05-15 00:15:39','2017-05-15 00:16:07','','','',1,'','','','10.0.2.2'),(3,10,'2017-05-15 00:16:56','2017-05-15 00:17:15','','','',1,'','','','10.0.2.2'),(4,4,'2017-05-15 00:15:39','2017-05-15 23:15:47','','','',1,'','','','10.0.2.2'),(5,10,'2017-05-15 23:14:26','2017-05-15 23:15:47','','','',1,'','','','10.0.2.2'),(6,10,'2017-05-15 23:17:24','2017-05-15 23:17:51','','','',1,'','','','10.0.2.2'),(7,10,'2017-05-15 23:19:49','2017-05-15 23:20:43','','','',2,'','','','10.0.2.2'),(8,10,'2017-05-16 14:49:56','2017-05-16 14:57:22','','','',2,'','','','14.162.169.33'),(9,10,'2017-05-16 14:58:27','2017-05-16 15:02:31','','','',2,'','','','14.162.169.33'),(10,10,'2017-05-16 15:40:45','2017-05-16 15:41:46','','','',2,'','','','14.162.169.33'),(11,10,'2017-05-16 15:45:47','2017-05-16 15:45:48','','','',2,'','','','14.162.169.33'),(12,10,'2017-05-16 15:47:06','2017-05-16 15:47:06','','','',2,'','','','14.162.169.33'),(13,10,'2017-05-16 16:08:11','2017-05-16 16:08:12','','','',2,'','','','14.162.169.33'),(14,10,'2017-05-16 16:11:21','2017-05-16 16:11:22','','','',2,'','','','14.162.169.33'),(15,10,'2017-05-16 16:13:07','2017-05-16 16:13:08','','','',1,'','','','14.162.169.33'),(16,13,'2017-05-16 16:14:20','2017-05-16 16:14:21','','','',1,'','','','14.162.169.33'),(17,10,'2017-05-16 17:24:22','2017-05-16 17:24:22','','','',2,'','','','14.162.169.33'),(18,10,'2017-05-16 17:29:21','2017-05-16 17:29:21','','','',2,'','','','14.162.169.33'),(19,3,'2017-05-16 17:29:37','2017-05-16 17:29:38','','','',2,'','','','14.162.169.33'),(20,3,'2017-05-17 09:36:26','2017-05-17 09:38:02','','','',1,'','','','42.112.210.35'),(21,3,'2017-05-17 09:50:54','2017-05-17 09:50:54','','','',1,'','','','42.112.210.35'),(22,14,'2017-05-17 19:49:29','2017-05-17 19:49:29','','','',3,'','','','14.162.169.33'),(23,15,'2017-05-17 19:50:04','2017-05-17 19:50:04','','','',3,'','','','14.162.169.33'),(24,16,'2017-05-17 19:54:12','2017-05-17 19:54:13','','','',3,'','','','14.162.169.33'),(25,17,'2017-05-17 19:54:59','2017-05-17 19:55:00','','','',3,'','','','14.162.169.33'),(26,18,'2017-05-17 19:56:02','2017-05-17 19:56:03','','','',3,'','','','14.162.169.33'),(27,19,'2017-05-17 19:57:14','2017-05-17 19:57:14','','','',3,'','','','14.162.169.33'),(28,20,'2017-05-17 19:59:13','2017-05-17 19:59:13','','','',3,'','','','14.162.169.33'),(29,21,'2017-05-17 19:59:40','2017-05-17 19:59:40','','','',3,'','','','14.162.169.33'),(30,22,'2017-05-17 20:01:14','2017-05-17 20:01:15','','','',3,'','','','14.162.169.33'),(31,23,'2017-05-17 20:01:57','2017-05-17 20:01:57','','','',3,'','','','14.162.169.33'),(32,23,'2017-05-17 20:05:42','2017-05-17 20:05:43','','','',3,'','','','14.162.169.33'),(33,24,'2017-05-17 20:06:16','2017-05-17 20:06:16','','','',3,'','','','14.162.169.33'),(34,25,'2017-05-17 20:06:38','2017-05-17 20:06:39','','','',3,'','','','14.162.169.33'),(35,25,'2017-05-17 20:06:47','2017-05-17 20:06:47','','','',3,'','','','14.162.169.33'),(36,26,'2017-05-17 20:08:55','2017-05-17 20:08:55','','','',3,'','','','14.162.169.33'),(37,14,'2017-05-17 20:17:15','2017-05-17 20:17:15','','','',3,'','','','14.162.169.33'),(38,27,'2017-05-17 20:18:22','2017-05-17 20:18:22','','','',3,'','','','14.162.169.33'),(39,27,'2017-05-17 20:19:27','2017-05-17 20:19:28','','','',3,'','','','14.162.169.33'),(40,27,'2017-05-17 20:19:50','2017-05-17 20:19:50','','','',3,'','','','14.162.169.33'),(41,28,'2017-05-17 20:47:53','2017-05-17 20:47:54','','','',3,'','','','14.162.169.33'),(42,28,'2017-05-17 20:48:05','2017-05-17 20:48:06','','','',3,'','','','14.162.169.33'),(43,29,'2017-05-17 20:49:26','2017-05-17 20:49:26','','','',3,'','','','14.162.169.33'),(44,29,'2017-05-17 20:49:38','2017-05-17 20:49:39','','','',3,'','','','14.162.169.33'),(45,29,'2017-05-17 20:49:45','2017-05-17 20:49:46','','','',3,'','','','14.162.169.33'),(46,29,'2017-05-17 20:49:58','2017-05-17 20:49:58','','','',3,'','','','14.162.169.33'),(47,29,'2017-05-17 20:50:04','2017-05-17 20:50:05','','','',3,'','','','14.162.169.33'),(48,27,'2017-05-17 20:59:42','2017-05-17 20:59:42','','','',3,'','','','27.67.1.135'),(49,30,'2017-05-18 15:01:33','2017-05-18 15:01:33','','','',3,'','','','14.162.169.33'),(50,31,'2017-05-18 20:18:25','2017-05-18 20:18:25','','','',3,'','','','14.162.169.33'),(51,25,'2017-05-18 20:29:04','2017-05-18 20:29:04','','','',3,'','','','14.162.169.33'),(52,32,'2017-05-18 20:29:12','2017-05-18 20:29:12','','','',3,'','','','14.162.169.33'),(53,33,'2017-05-18 20:52:37','2017-05-18 20:52:38','','','',3,'','','','14.162.169.33'),(54,33,'2017-05-18 20:59:56','2017-05-18 20:59:56','','','',3,'','','','14.162.169.33'),(55,33,'2017-05-19 04:53:32','2017-05-19 04:53:32','','','',3,'','','','14.162.169.33'),(56,35,'2017-05-19 09:56:29','2017-05-19 09:56:30','','','',3,'','','','14.162.169.33'),(57,35,'2017-05-19 09:56:42','2017-05-19 09:56:43','','','',3,'','','','14.162.169.33');
/*!40000 ALTER TABLE `log_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_updated_accounts`
--

DROP TABLE IF EXISTS `log_updated_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_updated_accounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guest` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `game_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_updated_accounts`
--

LOCK TABLES `log_updated_accounts` WRITE;
/*!40000 ALTER TABLE `log_updated_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_updated_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `model` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `foreign_key` int(10) NOT NULL,
  `type` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None',
  `access` int(4) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,3,'game',1,'default',1,'2017-05-14 23:49:11','2017-05-14 23:49:11'),(2,3,'game',2,'default',1,'2017-05-14 23:49:11','2017-05-14 23:49:11'),(3,3,'website',2,'default',1,'2017-05-14 23:49:11','2017-05-14 23:49:11'),(4,3,'game',1,'stats',1,'2017-05-14 23:49:13','2017-05-14 23:49:13'),(5,3,'game',2,'stats',1,'2017-05-14 23:49:13','2017-05-14 23:49:13');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profiles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `email_contact` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email_contact_token` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email_contact_token_expires` datetime DEFAULT NULL,
  `email_contact_verified` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `peopleId` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `peopleId_place_get` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `peopleId_date_get` datetime DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `question1` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `answer1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `question2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `answer2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_verified` tinyint(1) DEFAULT '0',
  `facebook_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday2` datetime DEFAULT NULL,
  `phone_code` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `birthday` (`birthday`),
  KEY `user_id_and_birthday` (`user_id`,`birthday`),
  KEY `birthday2` (`birthday2`),
  KEY `user_id_birthday2` (`user_id`,`birthday2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schema_migrations`
--

DROP TABLE IF EXISTS `schema_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schema_migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schema_migrations`
--

LOCK TABLES `schema_migrations` WRITE;
/*!40000 ALTER TABLE `schema_migrations` DISABLE KEYS */;
INSERT INTO `schema_migrations` VALUES (1,'InitMigrations','Migrations','2017-05-17 14:15:01'),(2,'ConvertVersionToClassNames','Migrations','2017-05-17 14:15:01'),(3,'IncreaseClassNameLength','Migrations','2017-05-17 14:15:02'),(4,'CreateDatabase','app','2017-05-17 14:15:24');
/*!40000 ALTER TABLE `schema_migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_token` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT '0',
  `email_temp_verified` int(3) DEFAULT '0',
  `email_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_token_expires` datetime DEFAULT NULL,
  `tos` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `last_action` datetime DEFAULT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'User',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `facebook_uid` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` int(10) DEFAULT NULL,
  `fb_verified` tinyint(1) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_code` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `count_daily` int(10) DEFAULT '0',
  `phone` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'phone_login',
  `phone_verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`),
  KEY `BY_USERNAME` (`username`),
  KEY `BY_EMAIL` (`email`),
  KEY `created` (`created`),
  KEY `last_action_user` (`last_action`),
  KEY `device_id_email` (`device_id`,`email`),
  KEY `facebook_uid` (`facebook_uid`),
  KEY `last_action` (`last_login`),
  KEY `role` (`role`),
  KEY `email_token` (`email_token`),
  KEY `password_token` (`password_token`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'quanvh',NULL,'quanvh','2facbaacd7061422759430780f5d922b104f1660',NULL,'quanhongvu@gmail.com',1,0,NULL,NULL,0,1,'2013-05-24 17:07:33','2017-05-18 19:47:54','Admin','2013-04-20 20:08:08','2017-05-18 19:47:55',NULL,NULL,1495111675,NULL,NULL,'',0,NULL,0),(2,'quanvh2',NULL,'quanvh2','2facbaacd7061422759430780f5d922b104f1660',NULL,'quanvh2@gmail.com',0,0,NULL,NULL,0,1,NULL,NULL,'Content','2017-05-12 21:32:49','2017-05-12 21:32:49',NULL,NULL,1494599569,NULL,NULL,'',0,NULL,0),(3,'quanvh90',NULL,'quanvh90','2facbaacd7061422759430780f5d922b104f1660',NULL,'quanvh90@gmail.com',0,0,NULL,NULL,0,0,NULL,'2017-05-17 15:50:46','Marketing','2017-05-11 22:13:40','2017-05-17 16:19:49',NULL,NULL,1495012789,NULL,'test chang pass','',0,NULL,0),(4,'test02',NULL,'test02','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1494781983@myapp.com',0,0,'39f5cbe5e0144beeae809032398506c3','2017-05-22 00:13:03',0,1,NULL,'2017-05-15 00:15:18','User','2017-05-15 00:13:03','2017-05-15 23:15:46',NULL,NULL,1494864946,NULL,NULL,'unknown',0,'0985005412',0),(9,'',NULL,'',NULL,NULL,NULL,0,0,NULL,NULL,0,0,NULL,'2017-05-15 00:12:46','User','2017-05-15 00:14:43','2017-05-15 00:14:43',NULL,NULL,1494782083,NULL,NULL,'',0,NULL,0),(10,'test01',NULL,'test01','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1494782216@myapp.com',0,0,'3b307cf18f1a60a7f906700e92d3f91f','2017-05-22 00:16:56',0,1,NULL,'2017-05-16 17:29:21','User','2017-05-15 00:16:56','2017-05-16 17:29:21',NULL,NULL,1494930561,NULL,NULL,'unknown',0,'0985005412',0),(11,'trungnt',NULL,'trungnt','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'trungthanhnguyen.riotgame@gmail.com',0,0,NULL,NULL,0,1,NULL,NULL,'Admin','2017-05-16 14:29:55','2017-05-16 14:29:55',NULL,NULL,1494919795,NULL,NULL,'',0,NULL,0),(12,'vuongnc',NULL,'vuongnc','025f02f24688d9fb8ff164c1e005b5f06df3b954',NULL,'vuongnguyencong.riotgame@gmail.com',0,0,NULL,NULL,0,1,NULL,'2017-05-19 09:34:02','Admin','2017-05-16 14:31:50','2017-05-19 09:34:02',NULL,NULL,1495161242,NULL,NULL,'',0,NULL,0),(13,'test03',NULL,'test03','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1494926060@myapp.com',0,0,'12d081718c0f755749a236da3ef42bbb','2017-05-23 16:14:20',0,1,NULL,'2017-05-16 16:17:32','User','2017-05-16 16:14:20','2017-05-16 16:17:33',NULL,NULL,1494926253,NULL,NULL,'Vietnam',0,'0985005412',0),(14,'tanka_quan11',NULL,'tanka_quan11','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1495025369@myapp.com',0,0,'59810abde928f80567797a4057cf19a1','2017-05-24 19:49:29',0,1,NULL,NULL,'User','2017-05-17 19:49:29','2017-05-17 19:49:29',NULL,NULL,1495025369,NULL,NULL,'Vietnam',0,NULL,0),(15,'tanka_MU_oilnssh',NULL,'tanka_mu_oilnssh','2f5efc2444f81228d19e3e7888850e8af1415068',NULL,'1495025403@myapp.com',0,0,'aa5a54cdcad59ef094d5c003d8b4e468','2017-05-24 19:50:04',0,1,NULL,NULL,'User','2017-05-17 19:50:04','2017-05-17 19:50:04',NULL,NULL,1495025404,NULL,NULL,'Vietnam',0,NULL,0),(16,'tanka_MU_wrh17axzgd',NULL,'tanka_mu_wrh17axzgd','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1495025652@myapp.com',0,0,'959b717b7aa875e963b1a355544e6277','2017-05-24 19:54:12',0,1,NULL,NULL,'User','2017-05-17 19:54:12','2017-05-17 19:54:12',NULL,NULL,1495025652,NULL,NULL,'Vietnam',0,NULL,0),(17,'tanka_MU_4iixicqgco',NULL,'tanka_mu_4iixicqgco','6dce208ca8f9a8f5fc3e259fd02815e33a247418',NULL,'1495025699@myapp.com',0,0,'01930b181687d5bbd680ce059499501b','2017-05-24 19:54:59',0,1,NULL,NULL,'User','2017-05-17 19:54:59','2017-05-17 19:54:59',NULL,NULL,1495025699,NULL,NULL,'Vietnam',0,NULL,0),(18,'tanka_MU_xw59grz061',NULL,'tanka_mu_xw59grz061','acc0000a143747e4ffeed877898968571764721b',NULL,'1495025762@myapp.com',0,0,'8827081d24a068f434cae3689561e491','2017-05-24 19:56:02',0,1,NULL,NULL,'User','2017-05-17 19:56:02','2017-05-17 19:56:02',NULL,NULL,1495025762,NULL,NULL,'Vietnam',0,NULL,0),(19,'tanka_MU_q0uu0yie0d',NULL,'tanka_mu_q0uu0yie0d','ac8ec1aef2071f71a4bf8b01d254395b540e22d1',NULL,'1495025834@myapp.com',0,0,'bb161ea347d36ba1fc80afbde502acf2','2017-05-24 19:57:14',0,1,NULL,NULL,'User','2017-05-17 19:57:14','2017-05-17 19:57:14',NULL,NULL,1495025834,NULL,NULL,'Vietnam',0,NULL,0),(20,'tanka_MU_njqirrn9vl',NULL,'tanka_mu_njqirrn9vl','f1c5972700db53513fd23a201bf769b04ff5ae3c',NULL,'1495025953@myapp.com',0,0,'eb389dd8b897146d1a0883a49c502580','2017-05-24 19:59:13',0,1,NULL,NULL,'User','2017-05-17 19:59:13','2017-05-17 19:59:13',NULL,NULL,1495025953,NULL,NULL,'Vietnam',0,NULL,0),(21,'tanka_MU_hlrd0agy7j',NULL,'tanka_mu_hlrd0agy7j','5697b02c27200e7ba72caa2e346aace21e932879',NULL,'1495025980@myapp.com',0,0,'afa4934bdb12a8648637fd437c048f51','2017-05-24 19:59:40',0,1,NULL,NULL,'User','2017-05-17 19:59:40','2017-05-17 19:59:40',NULL,NULL,1495025980,NULL,NULL,'Vietnam',0,NULL,0),(22,'tanka_MU_0dit0v7427',NULL,'tanka_mu_0dit0v7427','7b2a68003d6ec45b21a5ee217f5db640535dde81',NULL,'1495026074@myapp.com',0,0,'23e194ce84a0f3df94c4aeb8ba744c0d','2017-05-24 20:01:14',0,1,NULL,NULL,'User','2017-05-17 20:01:14','2017-05-17 20:01:14',NULL,NULL,1495026074,NULL,NULL,'Vietnam',0,NULL,0),(23,'tanka_MU_pqvco8ns6j',NULL,'tanka_mu_pqvco8ns6j','fc6856f0c39730f26676d40d8a0a6f43c8728b67',NULL,'1495026116@myapp.com',0,0,'349bd9d76ea4aa61b4069dfe75391ac2','2017-05-24 20:01:56',0,1,NULL,NULL,'User','2017-05-17 20:01:56','2017-05-17 20:01:56',NULL,NULL,1495026116,NULL,NULL,'Vietnam',0,NULL,0),(24,'tanka_MU_jiiedb5g3f',NULL,'tanka_mu_jiiedb5g3f','779adf49495dae804c10b06593cff1d4ee4390c6',NULL,'1495026376@myapp.com',0,0,'445f4c8ae25ff4fcfd1dd6d01c2fe177','2017-05-24 20:06:16',0,1,NULL,NULL,'User','2017-05-17 20:06:16','2017-05-17 20:06:16',NULL,NULL,1495026376,NULL,NULL,'Vietnam',0,NULL,0),(25,'tanka_anhvuong',NULL,'tanka_anhvuong','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1495026398@myapp.com',0,0,'e175eefad30bbc7f322939dcda2e88ab','2017-05-24 20:06:38',0,1,NULL,NULL,'User','2017-05-17 20:06:38','2017-05-17 20:06:38',NULL,NULL,1495026398,NULL,NULL,'Vietnam',0,NULL,0),(26,'tanka_MU_l5yb1lwyym',NULL,'tanka_mu_l5yb1lwyym','4877137685b4c7c5fc13eafae0faaa9313e37a62',NULL,'1495026535@myapp.com',0,0,'5c283ecfce2ad81d6f715534803c49d6','2017-05-24 20:08:55',0,1,NULL,NULL,'User','2017-05-17 20:08:55','2017-05-17 20:08:55',NULL,NULL,1495026535,NULL,NULL,'Vietnam',0,NULL,0),(27,'tanka_quan22',NULL,'tanka_quan22','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1495027102@myapp.com',0,0,'220f1b64e76d83b52739d16c3f459a9f','2017-05-24 20:18:22',0,1,NULL,NULL,'User','2017-05-17 20:18:22','2017-05-17 20:18:22',NULL,NULL,1495027102,NULL,NULL,'Vietnam',0,NULL,0),(28,'tanka_MU_b9p507w6n2',NULL,'tanka_mu_b9p507w6n2','3dac3cce39c7ad91f9f707989fbba3eb9dec6d89',NULL,'1495028873@myapp.com',0,0,'599eb2950e2d91cd4a834482417d15eb','2017-05-24 20:47:53',0,1,NULL,NULL,'User','2017-05-17 20:47:53','2017-05-17 20:47:53',NULL,NULL,1495028873,NULL,NULL,'Vietnam',0,NULL,0),(29,'tanka_bagiano',NULL,'tanka_bagiano','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1495028965@myapp.com',0,0,'330b99578463d9cca7d0aa36e149c71b','2017-05-24 20:49:26',0,1,NULL,NULL,'User','2017-05-17 20:49:26','2017-05-17 20:49:26',NULL,NULL,1495028966,NULL,NULL,'Vietnam',0,NULL,0),(30,'tanka_MU_1vuq88lrev',NULL,'tanka_mu_1vuq88lrev','ca041d43daa7a820b832597b9c8d9935117d72e6',NULL,'1495094492@myapp.com',0,0,'09f6e6d312a96ce55d867ce891de14f1','2017-05-25 15:01:32',0,1,NULL,NULL,'User','2017-05-18 15:01:32','2017-05-18 15:01:32',NULL,NULL,1495094492,NULL,NULL,'Vietnam',0,NULL,0),(31,'tanka_Anhvuongdayma',NULL,'tanka_anhvuongdayma','133d2d30858ca5131e1e9eb51b8935611b6022f8',NULL,'1495113504@myapp.com',0,0,'6b7e8e1cf97f7ce3f143a3e5c9196fbe','2017-05-25 20:18:25',0,1,NULL,NULL,'User','2017-05-18 20:18:25','2017-05-18 20:18:25',NULL,NULL,1495113505,NULL,NULL,'Vietnam',0,NULL,0),(32,'tanka_MU_7m1o3kk4du',NULL,'tanka_mu_7m1o3kk4du','32a1c7735fd2e7bb65659897a04ecbc1e624976f',NULL,'1495114152@myapp.com',0,0,'a1fa9741645c09c42d7c7195085333d8','2017-05-25 20:29:12',0,1,NULL,NULL,'User','2017-05-18 20:29:12','2017-05-18 20:29:12',NULL,NULL,1495114152,NULL,NULL,'Vietnam',0,NULL,0),(33,'tanka_MU_35jhsvi6rs',NULL,'tanka_mu_35jhsvi6rs','f9277097b54496d3cde9eb8ada9853df0b6ce0dc',NULL,'1495115557@myapp.com',0,0,'fc92c2662e3c512ade3cb537edf09171','2017-05-25 20:52:37',0,1,NULL,NULL,'User','2017-05-18 20:52:37','2017-05-18 20:52:37',NULL,NULL,1495115557,NULL,NULL,'Vietnam',0,NULL,0),(34,'tanka_muathuxanh',NULL,'tanka_muathuxanh','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1495138682@myapp.com',0,0,'2982053251ee63d416bebc4fb9011fff','2017-05-26 03:18:02',0,1,NULL,NULL,'User','2017-05-19 03:18:02','2017-05-19 03:18:02',NULL,NULL,1495138682,NULL,NULL,'Vietnam',0,NULL,0),(35,'tanka_mrvnn21',NULL,'tanka_mrvnn21','6af86f5ac0e526325393db6f496afcbce4d46dab',NULL,'1495162589@myapp.com',0,0,'eae0629e3355db94cdb58bc3a74468d8','2017-05-26 09:56:29',0,1,NULL,NULL,'User','2017-05-19 09:56:29','2017-05-19 09:56:29',NULL,NULL,1495162589,NULL,NULL,'Vietnam',0,NULL,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variables`
--

DROP TABLE IF EXISTS `variables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `variables` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variables`
--

LOCK TABLES `variables` WRITE;
/*!40000 ALTER TABLE `variables` DISABLE KEYS */;
/*!40000 ALTER TABLE `variables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `websites`
--

DROP TABLE IF EXISTS `websites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `websites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'use SERVER_NAME',
  `theme2` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_id` int(10) DEFAULT NULL,
  `theme` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `theme_mobile` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `lang` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `websites`
--

LOCK TABLES `websites` WRITE;
/*!40000 ALTER TABLE `websites` DISABLE KEYS */;
INSERT INTO `websites` VALUES (1,'Localhost','localhost',NULL,'localhost',NULL,'DauTruongPocket','DauTruongPocket','2014-06-25 12:32:16','2017-05-18 14:31:32','vie',0),(2,'MU - Takan','45.117.77.125',NULL,'45.117.77.125',NULL,'MUTakan','MUTakan','2015-11-10 12:14:00','2017-05-18 14:33:32','vie',0);
/*!40000 ALTER TABLE `websites` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-05-19 10:16:40
