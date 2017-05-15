CREATE DATABASE  IF NOT EXISTS `game_platform` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `game_platform`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: game_platform
-- ------------------------------------------------------
-- Server version	5.6.33-0ubuntu0.14.04.1-log

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_token`
--

LOCK TABLES `access_token` WRITE;
/*!40000 ALTER TABLE `access_token` DISABLE KEYS */;
INSERT INTO `access_token` VALUES (1,'app1',1,'bW9iX3Rva2VuPTAuNDY3NTE1MDAgMTM3MjAxOTEzNDg1M','request',1372019134,2147483647),(2,'app1',4,'4C8BF88561AA03FC822EADE9B0491A6845E061CE','request',1494781983,2147483647),(3,'app1',10,'923CBC3A6D70FB82FB3EB6387440D4BEF1280D85','request',1494782216,2147483647),(4,'c3fc6b14acd9619bdd41c973a0278b20',10,'558BFD26A6AF54064427A4FC61EC12A0F5E0EBC6','request',1494865189,2147483647);
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,1,1,100000,'0000-00-00 00:00:00','2014-03-05 19:21:01',NULL,NULL,NULL),(2,4,1,23,'2017-05-15 00:13:03','2017-05-15 00:13:03',NULL,NULL,NULL),(3,10,1,33,'2017-05-15 00:16:56','2017-05-15 00:16:56',NULL,NULL,NULL),(4,10,2,33,'2017-05-15 23:19:49','2017-05-15 23:19:49',NULL,NULL,NULL);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (1,'Tình kiếm','app1','c3fc6b14acd961','- Coming Soon\r\n- Coming Soon\r\n- Coming Soon\r\n- [Coming Soon][1]\r\n\r\n  [1]: http://localhost:8088/platform','<ul>\n<li>Coming Soon</li>\n<li>Coming Soon</li>\n<li>Coming Soon</li>\n<li><a href=\"http://localhost:8088/platform\">Coming Soon</a></li>\n</ul>\n',NULL,NULL,'2013-05-02',3,'tinhkiem','','2013-05-02 00:44:44','2015-07-31 11:41:02','0.1','support@mobgame.vn','109336685751749','> iOS 4.3','itms-apps://itunes.apple.com/vn/app/tinh-kiem/id597768531?mt=8&uo=4','itms-services://?action=download-manifest&url=http://dungbx.com/mobgame/MobGameDemo.plist','UA-41972645-3','','Dashboard','1','1','http://forum.soha.vn','eng',1,'','tinhkiem','ios',2,'','','','a:8:{s:20:\"client_id_production\";s:0:\"\";s:17:\"client_id_sandbox\";s:0:\"\";s:13:\"client_secret\";s:0:\"\";s:14:\"merchant_email\";s:0:\"\";s:13:\"merchant_name\";s:0:\"\";s:11:\"privacy_url\";s:0:\"\";s:18:\"user_agreement_url\";s:0:\"\";s:19:\"accept_credit_cards\";s:1:\"0\";}','','a:41:{s:10:\"google_iab\";a:2:{s:7:\"hashkey\";s:0:\"\";s:5:\"token\";s:0:\"\";}s:16:\"hide_login_email\";s:1:\"0\";s:19:\"hide_login_facebook\";s:1:\"0\";s:12:\"hide_payment\";s:1:\"0\";s:21:\"hide_for_game_version\";s:0:\"\";s:5:\"admob\";a:3:{s:13:\"conversion_id\";s:0:\"\";s:16:\"conversion_label\";s:0:\"\";s:16:\"conversion_value\";s:0:\"\";}s:5:\"adway\";a:2:{s:15:\"appIdPartyTrack\";s:0:\"\";s:16:\"appKeyPartyTrack\";s:0:\"\";}s:9:\"appsflyer\";a:4:{s:17:\"appsflyer_dev_key\";s:0:\"\";s:12:\"apple_app_id\";s:0:\"\";s:8:\"currency\";s:0:\"\";s:11:\"is_use_http\";s:1:\"0\";}s:19:\"hide_update_account\";s:1:\"0\";s:13:\"hide_giftcode\";s:1:\"0\";s:6:\"invite\";a:4:{s:9:\"invite_fb\";s:0:\"\";s:10:\"invite_sms\";s:0:\"\";s:18:\"invite_title_email\";s:0:\"\";s:12:\"invite_email\";s:0:\"\";}s:7:\"payment\";a:1:{s:11:\"testallowed\";s:0:\"\";}s:10:\"fbpage_url\";s:0:\"\";s:11:\"group_fb_id\";s:0:\"\";s:13:\"support_skype\";s:8:\"quanvh90\";s:13:\"fanpage_image\";a:5:{s:4:\"name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"tmp_name\";s:0:\"\";s:5:\"error\";s:1:\"4\";s:4:\"size\";s:1:\"0\";}s:15:\"invitefb2_image\";a:5:{s:4:\"name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"tmp_name\";s:0:\"\";s:5:\"error\";s:1:\"4\";s:4:\"size\";s:1:\"0\";}s:18:\"fanpage_image_vote\";a:5:{s:4:\"name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"tmp_name\";s:0:\"\";s:5:\"error\";s:1:\"4\";s:4:\"size\";s:1:\"0\";}s:6:\"hockey\";a:1:{s:6:\"app_id\";s:0:\"\";}s:7:\"qr_code\";a:6:{s:3:\"url\";s:69:\"https://s3-ap-southeast-1.amazonaws.com/emagbom.plf/558bdaeff18d9.png\";s:4:\"name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"tmp_name\";s:0:\"\";s:5:\"error\";s:1:\"4\";s:4:\"size\";s:1:\"0\";}s:21:\"image_background_game\";a:6:{s:3:\"url\";s:69:\"https://s3-ap-southeast-1.amazonaws.com/emagbom.plf/55aefe9272706.png\";s:4:\"name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"tmp_name\";s:0:\"\";s:5:\"error\";s:1:\"4\";s:4:\"size\";s:1:\"0\";}s:17:\"short_description\";s:0:\"\";s:16:\"game_update_hour\";s:0:\"\";s:16:\"game_update_date\";s:0:\"\";s:17:\"hide_login_google\";s:1:\"0\";s:15:\"hide_popup_coin\";s:1:\"0\";s:8:\"hide_ads\";s:1:\"1\";s:16:\"hide_menu_submit\";s:1:\"0\";s:16:\"enable_dashboard\";s:1:\"1\";s:13:\"hide_play_now\";s:1:\"1\";s:25:\"show_register_user_detail\";s:1:\"0\";s:17:\"allow_game_by_age\";s:1:\"0\";s:11:\"age_allowed\";s:0:\"\";s:9:\"vcurrency\";a:1:{s:4:\"type\";s:0:\"\";}s:7:\"pem_url\";N;s:9:\"client_id\";s:0:\"\";s:7:\"key_ads\";s:0:\"\";s:4:\"menu\";a:11:{s:4:\"news\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:8:\"giftcode\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:6:\"report\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:9:\"offerwall\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"0\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:6:\"invite\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";}s:5:\"guide\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"0\";}s:7:\"website\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";}s:7:\"fanpage\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:9:\"community\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:5:\"email\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";}s:7:\"profile\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}}s:9:\"time_zone\";s:0:\"\";s:8:\"is_close\";s:1:\"0\";s:15:\"game_updated_at\";s:19:\"2017-05-13 00:00:00\";}',1,1,'',1,'','','','quanvh',0,'2015-07-20 02:33:22',0,0,0,NULL,0),(2,'Tình kiếm','c3fc6b14acd9619bdd41c973a0278b20','c3fc6b14acd961','Ai là gà',NULL,NULL,NULL,'2013-05-03',1,'tinhkiem-android','','2013-05-03 19:37:03','2017-05-14 23:47:35',NULL,'','',NULL,NULL,NULL,'','','Dashboard','','',NULL,'eng',0,NULL,'tinhkiem','android',2,'','12345667','afasfnsadg','a:8:{s:20:\"client_id_production\";s:0:\"\";s:17:\"client_id_sandbox\";s:0:\"\";s:13:\"client_secret\";s:0:\"\";s:14:\"merchant_email\";s:0:\"\";s:13:\"merchant_name\";s:0:\"\";s:11:\"privacy_url\";s:0:\"\";s:18:\"user_agreement_url\";s:0:\"\";s:19:\"accept_credit_cards\";s:1:\"0\";}','','a:27:{s:10:\"google_iab\";a:2:{s:7:\"hashkey\";s:0:\"\";s:5:\"token\";s:0:\"\";}s:16:\"hide_login_email\";s:1:\"0\";s:19:\"hide_login_facebook\";s:1:\"0\";s:12:\"hide_payment\";s:1:\"0\";s:19:\"hide_update_account\";s:1:\"0\";s:13:\"hide_giftcode\";s:1:\"0\";s:21:\"hide_for_game_version\";s:0:\"\";s:5:\"admob\";a:3:{s:13:\"conversion_id\";s:0:\"\";s:16:\"conversion_label\";s:0:\"\";s:16:\"conversion_value\";s:0:\"\";}s:5:\"adway\";a:2:{s:15:\"appIdPartyTrack\";s:0:\"\";s:16:\"appKeyPartyTrack\";s:0:\"\";}s:9:\"appsflyer\";a:4:{s:17:\"appsflyer_dev_key\";s:0:\"\";s:12:\"apple_app_id\";s:0:\"\";s:8:\"currency\";s:0:\"\";s:11:\"is_use_http\";s:1:\"0\";}s:6:\"hockey\";a:1:{s:6:\"app_id\";s:0:\"\";}s:6:\"invite\";a:4:{s:9:\"invite_fb\";s:0:\"\";s:10:\"invite_sms\";s:0:\"\";s:18:\"invite_title_email\";s:0:\"\";s:12:\"invite_email\";s:0:\"\";}s:7:\"payment\";a:1:{s:11:\"testallowed\";s:0:\"\";}s:17:\"hide_login_google\";s:1:\"0\";s:15:\"hide_popup_coin\";s:1:\"0\";s:8:\"hide_ads\";s:1:\"0\";s:16:\"hide_menu_submit\";s:1:\"0\";s:16:\"enable_dashboard\";s:1:\"1\";s:13:\"hide_play_now\";s:1:\"1\";s:25:\"show_register_user_detail\";s:1:\"0\";s:17:\"allow_game_by_age\";s:1:\"0\";s:11:\"age_allowed\";s:0:\"\";s:9:\"vcurrency\";a:1:{s:4:\"type\";s:0:\"\";}s:7:\"pem_url\";N;s:9:\"client_id\";s:0:\"\";s:7:\"key_ads\";s:0:\"\";s:4:\"menu\";a:11:{s:4:\"news\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:8:\"giftcode\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:6:\"report\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"1\";s:15:\"horizontal_icon\";s:0:\"\";}s:9:\"offerwall\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"0\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:6:\"invite\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";}s:5:\"guide\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"0\";}s:7:\"website\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";}s:7:\"fanpage\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:9:\"community\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"1\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}s:5:\"email\";a:4:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";}s:7:\"profile\";a:7:{s:5:\"title\";s:0:\"\";s:4:\"icon\";s:0:\"\";s:8:\"external\";s:1:\"0\";s:4:\"show\";s:1:\"1\";s:16:\"horizontal_title\";s:0:\"\";s:15:\"horizontal_show\";s:1:\"0\";s:15:\"horizontal_icon\";s:0:\"\";}}}',0,1,'',1,'','','','quanvh',0,NULL,0,0,0,NULL,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_logins`
--

LOCK TABLES `log_logins` WRITE;
/*!40000 ALTER TABLE `log_logins` DISABLE KEYS */;
INSERT INTO `log_logins` VALUES (1,4,'2017-05-15 00:13:03','2017-05-15 00:14:43','','','',1,'','','','10.0.2.2'),(2,4,'2017-05-15 00:15:39','2017-05-15 00:16:07','','','',1,'','','','10.0.2.2'),(3,10,'2017-05-15 00:16:56','2017-05-15 00:17:15','','','',1,'','','','10.0.2.2'),(4,4,'2017-05-15 00:15:39','2017-05-15 23:15:47','','','',1,'','','','10.0.2.2'),(5,10,'2017-05-15 23:14:26','2017-05-15 23:15:47','','','',1,'','','','10.0.2.2'),(6,10,'2017-05-15 23:17:24','2017-05-15 23:17:51','','','',1,'','','','10.0.2.2'),(7,10,'2017-05-15 23:19:49','2017-05-15 23:20:43','','','',2,'','','','10.0.2.2');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'quanvh',NULL,'quanvh','2facbaacd7061422759430780f5d922b104f1660',NULL,'quanhongvu@gmail.com',1,0,NULL,NULL,0,1,'2013-05-24 17:07:33','2017-05-15 23:13:39','Admin','2013-04-20 20:08:08','2017-05-15 23:15:47',NULL,NULL,1494864947,NULL,NULL,'',0,NULL,0),(2,'quanvh2',NULL,'quanvh2','2facbaacd7061422759430780f5d922b104f1660',NULL,'quanvh2@gmail.com',0,0,NULL,NULL,0,1,NULL,NULL,'Content','2017-05-12 21:32:49','2017-05-12 21:32:49',NULL,NULL,1494599569,NULL,NULL,'',0,NULL,0),(3,'quanvh90',NULL,'quanvh90','2facbaacd7061422759430780f5d922b104f1660',NULL,'quanvh90@gmail.com',0,0,NULL,NULL,0,1,NULL,NULL,'Marketing','2017-05-11 22:13:40','2017-05-14 23:49:13',NULL,NULL,1494780553,NULL,NULL,'',0,NULL,0),(4,'test02',NULL,'test02','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1494781983@myapp.com',0,0,'39f5cbe5e0144beeae809032398506c3','2017-05-22 00:13:03',0,1,NULL,'2017-05-15 00:15:18','User','2017-05-15 00:13:03','2017-05-15 23:15:46',NULL,NULL,1494864946,NULL,NULL,'unknown',0,'0985005412',0),(9,'',NULL,'',NULL,NULL,NULL,0,0,NULL,NULL,0,0,NULL,'2017-05-15 00:12:46','User','2017-05-15 00:14:43','2017-05-15 00:14:43',NULL,NULL,1494782083,NULL,NULL,'',0,NULL,0),(10,'test01',NULL,'test01','9ef3555f7c20f10bea26f67a518b3a2687a3d53a',NULL,'1494782216@myapp.com',0,0,'3b307cf18f1a60a7f906700e92d3f91f','2017-05-22 00:16:56',0,1,NULL,'2017-05-15 23:18:36','User','2017-05-15 00:16:56','2017-05-15 23:19:09',NULL,NULL,1494865149,NULL,NULL,'unknown',0,'0985005412',0);
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
INSERT INTO `websites` VALUES (1,'Localhost','localhost',NULL,'localhost',NULL,'DauTruongPocket','DauTruongPocket','2014-06-25 12:32:16','2017-05-09 22:44:51','vie',1),(2,'Huyền Thoai hero 3','dev.smobgame.com',NULL,'dev.smobgame.com',NULL,'FunTap','RoR','2015-11-10 12:14:00','2016-05-04 15:31:46','vie',1);
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

-- Dump completed on 2017-05-15 23:26:46
