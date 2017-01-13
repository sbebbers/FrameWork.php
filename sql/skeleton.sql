CREATE DATABASE  IF NOT EXISTS `skeleton` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `skeleton`;
-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: skeleton
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.19-MariaDB

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
-- Table structure for table `view`
--

DROP TABLE IF EXISTS `view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(56) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'This is the page segment, for instance /home or /mypage - file extensions must not be included',
  `header` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The generic H1 for the page, if required',
  `sub_header` varchar(255) CHARACTER SET utf8_unicode_ci DEFAULT NULL COMMENT 'The sub-header (h2 or h3 set in the view)',
  `content` text COLLATE utf8_unicode_ci COMMENT 'Assuming a simple HTML page, put your HTML here',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view`
--

LOCK TABLES `view` WRITE;
/*!40000 ALTER TABLE `view` DISABLE KEYS */;
INSERT INTO `view` VALUES (1,'home','FrameWork.php','This is a simple OO MVC PHP 5/7 framework','&lt;p&gt;This was developed as a teaching tool to mentor a junior PHP developer between March and December 2016. I have decided to continue to develop it as a new framework which will hopefully prove useful to others.&lt;/p&gt;&lt;p&gt;Despite never being a finished product, it has been pen-tested and deployed on a web-based application. Once you know the system, one can quickly build fairly complex PHP-based web software.&lt;/p&gt;');
/*!40000 ALTER TABLE `view` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-13 10:27:30
