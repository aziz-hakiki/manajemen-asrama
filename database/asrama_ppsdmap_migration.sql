-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: asrama_ppsdmap
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kamar_id` bigint(20) unsigned NOT NULL,
  `peserta_id` bigint(20) unsigned DEFAULT NULL,
  `diklat_id` bigint(20) unsigned DEFAULT NULL,
  `nama_pemesan` varchar(255) NOT NULL,
  `instansi` varchar(255) DEFAULT NULL,
  `no_telepon` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('booked','checkin','batal') NOT NULL DEFAULT 'booked',
  `keterangan` text DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_kamar_id_foreign` (`kamar_id`),
  KEY `bookings_peserta_id_foreign` (`peserta_id`),
  KEY `bookings_diklat_id_foreign` (`diklat_id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  CONSTRAINT `bookings_diklat_id_foreign` FOREIGN KEY (`diklat_id`) REFERENCES `diklats` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_kamar_id_foreign` FOREIGN KEY (`kamar_id`) REFERENCES `kamars` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `pesertas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (13,63,93,6,'AZIZ HAKIKI','BPSDM Jakarta',NULL,'2026-09-26','2026-09-30','checkin',NULL,26,'2026-09-23 06:36:51','2026-09-28 00:47:14'),(14,11,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 06:59:44','2026-09-23 07:03:25'),(15,12,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','booked','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 06:59:44','2026-09-23 06:59:44'),(16,66,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','booked','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 06:59:44','2026-09-23 06:59:44'),(17,62,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:11:49'),(18,63,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:31:53'),(19,65,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:04'),(20,67,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:06'),(21,68,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:31'),(22,69,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:20'),(23,70,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:18'),(24,71,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:17'),(25,72,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:29'),(26,73,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:27'),(27,74,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:25'),(28,75,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:23'),(29,76,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:22'),(30,77,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:14'),(31,78,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:13'),(32,79,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:10:27','2026-09-23 07:32:11'),(33,63,NULL,4,'Contoh Diklat','Peserta Contoh Diklat',NULL,'2026-09-21','2026-09-24','batal','Booking Diklat Contoh Diklat',26,'2026-09-23 07:34:07','2026-09-23 07:36:38'),(35,63,NULL,4,'Contoh Diklat','Peserta Contoh Diklat',NULL,'2026-09-21','2026-09-24','batal','Booking Diklat Contoh Diklat',26,'2026-09-23 07:40:55','2026-09-23 07:41:25'),(37,65,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:42:49'),(38,67,27,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','checkin','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:43:25'),(39,68,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:43:11'),(40,69,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:43:08'),(41,70,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:43:07'),(42,71,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:43:05'),(43,72,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:43:03'),(44,73,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:43:01'),(45,74,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:43:00'),(46,75,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:42:58'),(47,76,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:42:57'),(48,77,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:42:55'),(49,78,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:42:53'),(50,79,NULL,5,'Mentoring Diklat','Peserta Mentoring Diklat',NULL,'2026-09-23','2026-09-30','batal','Booking Diklat Mentoring Diklat',26,'2026-09-23 07:42:25','2026-09-23 07:42:52'),(51,67,95,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-07','2026-10-12','checkin','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-23 07:52:24','2026-09-28 01:06:11'),(52,68,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:20:53','2026-09-28 02:21:18'),(53,69,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:20:53','2026-09-28 02:21:20'),(54,70,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:20:53','2026-09-28 02:21:22'),(55,71,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:20:53','2026-09-28 02:21:23'),(64,72,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:21:38','2026-09-29 01:03:09'),(65,73,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:21:38','2026-09-29 01:03:03'),(66,74,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:21:38','2026-09-29 01:03:01'),(67,75,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:21:38','2026-09-29 01:02:59'),(68,76,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:21:38','2026-09-29 01:02:58'),(69,77,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:21:38','2026-09-29 01:02:56'),(70,78,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:21:38','2026-09-29 01:02:45'),(71,79,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-10-01','2026-10-12','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:21:38','2026-09-29 01:02:42'),(72,62,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-09-29','2026-09-30','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:28:15','2026-09-28 04:39:14'),(73,63,NULL,6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','Peserta Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',NULL,'2026-09-29','2026-09-30','batal','Booking Diklat Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026',26,'2026-09-28 02:28:15','2026-09-29 07:08:15');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('asrama_ppsdmap_cache_resepsionis@example.com|10.213.14.86','i:2;',1790645590),('asrama_ppsdmap_cache_resepsionis@example.com|10.213.14.86:timer','i:1790645590;',1790645590),('asrama_ppsdmap_cache_resepsionis@example.com|10.213.15.204','i:3;',1790048775),('asrama_ppsdmap_cache_resepsionis@example.com|10.213.15.204:timer','i:1790048775;',1790048775),('asrama_ppsdmap_cache_resepsionis@example.com|10.213.15.222','i:1;',1790671704),('asrama_ppsdmap_cache_resepsionis@example.com|10.213.15.222:timer','i:1790671704;',1790671704),('asrama_ppsdmap_cache_resepsionis@example.com|10.213.24.66','i:1;',1790671561),('asrama_ppsdmap_cache_resepsionis@example.com|10.213.24.66:timer','i:1790671561;',1790671561);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diklats`
--

DROP TABLE IF EXISTS `diklats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `diklats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_diklat` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diklats`
--

LOCK TABLES `diklats` WRITE;
/*!40000 ALTER TABLE `diklats` DISABLE KEYS */;
INSERT INTO `diklats` VALUES (1,'Effective Coaching and Mentoring Angkatan III 2026','2026-09-09','2026-09-11','2026-09-15 04:36:08','2026-09-15 04:36:08'),(2,'PPKLLAJ','2026-09-14','2026-09-17','2026-09-15 04:36:08','2026-09-15 04:36:08'),(4,'Contoh Diklat','2026-09-21','2026-09-24','2026-09-21 01:56:07','2026-09-22 03:08:30'),(5,'Mentoring Diklat','2026-09-23','2026-09-30','2026-09-21 04:07:15','2026-09-29 01:21:03'),(6,'Pelaksanaan OSN Diksus Tingkat Nasional Tahun 2026','2026-10-01','2026-10-12','2026-09-22 01:47:57','2026-09-24 01:27:01');
/*!40000 ALTER TABLE `diklats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gedungs`
--

DROP TABLE IF EXISTS `gedungs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gedungs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_gedung` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gedungs`
--

LOCK TABLES `gedungs` WRITE;
/*!40000 ALTER TABLE `gedungs` DISABLE KEYS */;
INSERT INTO `gedungs` VALUES (3,'Asrama C','2026-09-15 06:15:09','2026-09-15 06:15:09'),(4,'Asrama B','2026-09-16 07:08:03','2026-09-16 07:08:03'),(5,'Asrama A','2026-09-16 07:08:08','2026-09-16 07:08:08');
/*!40000 ALTER TABLE `gedungs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kamars`
--

DROP TABLE IF EXISTS `kamars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kamars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gedung_id` bigint(20) unsigned NOT NULL,
  `nomor_kamar` varchar(255) NOT NULL,
  `kapasitas` int(11) NOT NULL DEFAULT 1,
  `status` enum('kosong','terisi','rusak') NOT NULL DEFAULT 'kosong',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kamars_gedung_id_foreign` (`gedung_id`),
  CONSTRAINT `kamars_gedung_id_foreign` FOREIGN KEY (`gedung_id`) REFERENCES `gedungs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kamars`
--

LOCK TABLES `kamars` WRITE;
/*!40000 ALTER TABLE `kamars` DISABLE KEYS */;
INSERT INTO `kamars` VALUES (11,3,'101',2,'kosong','2026-09-15 06:16:13','2026-09-23 06:34:23'),(12,3,'102',2,'kosong','2026-09-15 06:16:49','2026-09-23 06:12:16'),(61,4,'101',2,'kosong','2026-09-16 07:36:02','2026-09-23 06:33:25'),(62,5,'101',2,'kosong','2026-09-16 07:36:12','2026-09-24 01:24:56'),(63,5,'102',2,'kosong','2026-09-17 02:01:58','2026-09-28 00:47:54'),(64,4,'102',2,'kosong','2026-09-17 02:02:17','2026-09-23 02:57:03'),(65,5,'103',2,'kosong','2026-09-17 02:50:58','2026-09-23 06:15:39'),(66,3,'103',3,'kosong','2026-09-21 02:01:30','2026-09-23 06:34:01'),(67,5,'104',2,'kosong','2026-09-23 01:45:52','2026-09-28 01:07:11'),(68,5,'105',2,'kosong','2026-09-23 01:51:02','2026-09-23 01:51:02'),(69,5,'106',2,'kosong','2026-09-23 01:51:45','2026-09-23 01:51:45'),(70,5,'107',2,'kosong','2026-09-23 02:03:57','2026-09-23 02:03:57'),(71,5,'108',2,'kosong','2026-09-23 02:04:08','2026-09-23 02:04:08'),(72,5,'109',2,'kosong','2026-09-23 02:04:22','2026-09-23 02:04:22'),(73,5,'110',2,'kosong','2026-09-23 03:04:43','2026-09-23 03:04:43'),(74,5,'111',2,'kosong','2026-09-23 03:04:59','2026-09-23 03:04:59'),(75,5,'112',2,'kosong','2026-09-23 03:05:07','2026-09-23 03:05:07'),(76,5,'113',2,'kosong','2026-09-23 03:05:17','2026-09-23 03:05:17'),(77,5,'114',2,'kosong','2026-09-23 03:25:54','2026-09-23 03:25:54'),(78,5,'115',2,'kosong','2026-09-23 03:26:15','2026-09-23 03:26:15'),(79,5,'116',2,'rusak','2026-09-23 03:26:28','2026-09-29 07:06:05'),(81,5,'117',2,'kosong','2026-09-29 07:06:17','2026-09-29 07:06:17'),(82,5,'118',1,'kosong','2026-09-29 07:06:32','2026-09-29 07:06:32');
/*!40000 ALTER TABLE `kamars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_18_013400_create_gedungs_table',1),(5,'2026_08_18_013400_create_kamars_table',1),(6,'2026_08_18_013401_create_diklats_table',1),(7,'2026_08_18_013401_create_pesertas_table',1),(8,'2026_08_18_013401_create_transaksi_asramas_table',1),(9,'2026_09_03_151339_add_jenis_kelamin_and_keterangan_to_pesertas_table',1),(10,'2026_09_14_103457_update_status_enum_in_kamars_table',1),(11,'2026_09_17_134549_add_gedung_id_to_users_table',2),(12,'2026_09_23_081852_create_bookings_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesertas`
--

DROP TABLE IF EXISTS `pesertas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesertas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `diklat_id` bigint(20) unsigned NOT NULL,
  `nama_peserta` varchar(255) NOT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `nip_nik` varchar(255) DEFAULT NULL,
  `instansi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT 'Peserta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesertas_diklat_id_foreign` (`diklat_id`),
  CONSTRAINT `pesertas_diklat_id_foreign` FOREIGN KEY (`diklat_id`) REFERENCES `diklats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesertas`
--

LOCK TABLES `pesertas` WRITE;
/*!40000 ALTER TABLE `pesertas` DISABLE KEYS */;
INSERT INTO `pesertas` VALUES (27,6,'WAHYUDI SAPUTRA','Laki-laki','198211072005021001','Kementrian Perhubungan','Peserta','2026-09-15 04:43:50','2026-09-28 04:38:13'),(28,6,'WIBIE CHANDRA PRASETYO','Laki-laki','198412272008121001','Kementrian Perhubungan','Panitia','2026-09-15 04:43:50','2026-09-28 04:38:18'),(29,6,'WIDIAH NUR WAHYUNI','Perempuan','198308142006042001','Kementrian Perhubungan','Peserta','2026-09-15 04:43:50','2026-09-28 04:38:23'),(30,6,'ZAID ARHAM','Laki-laki','198408042015031004','Kementrian Perhubungan','Peserta','2026-09-15 04:43:50','2026-09-28 04:38:28'),(93,6,'AZIZ HAKIKI','Laki-laki','3602060310020001','BPSDM Jakarta','Narasumber','2026-09-21 01:56:42','2026-09-23 03:35:08'),(94,6,'MUHAMAD FERARI','Laki-laki','3602060310020002','Kementrian Perhubungan','Panitia','2026-09-21 04:13:19','2026-09-28 04:38:07'),(95,6,'AKBAR','Laki-laki','3602060310020009','Kementrian Perhubungan','Panitia','2026-09-28 01:05:15','2026-09-28 01:05:15'),(96,6,'Dedy','Laki-laki','3602060310020008','BPSDM Jakarta','Narasumber','2026-09-28 04:36:05','2026-09-28 04:36:05');
/*!40000 ALTER TABLE `pesertas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0KesUJ4L2f2aHyMhKcLBPg2MwfcVV48ppHmIpu5I',23,'10.213.24.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiS3Y4RDJNc0ZtTjI3dmdzYjJIcUg3bjBRQlZNbVZPelE4bmVrRmVzMSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjU2OiJodHRwOi8vMTAuMjEzLjI0LjY2OjgwODAvcGltcGluYW4vZGFzaGJvYXJkP3BlcmlvZGU9MjAyNiI7czo1OiJyb3V0ZSI7czoxODoicGltcGluYW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjM7fQ==',1790672270),('6tHWbtliKUwLzl9MGurjVwoaP6RqjdEYV5FsAXpE',NULL,'10.213.24.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidktIY1ZvQUdGdVB6dGYyR0Q5a0l3MFhFNzNXVmVyTzhCcmhyZ2VqNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly8xMC4yMTMuMjQuNjY6ODA4MCI7czo1OiJyb3V0ZSI7Tjt9fQ==',1790672324),('u9b3QB85cJiHmXESh7rXcoT5yjwFzdwtE0F7FN5l',21,'10.213.24.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOGM0cE9QYXA2QjVnNDRpTWMwVTJEOFcyM0NiWnp1RXBiVWpqTkpvaSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vMTAuMjEzLjI0LjY2OjgwODAvYWRtaW4vdXNlciI7czo1OiJyb3V0ZSI7czoxNjoiYWRtaW4udXNlci5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjIxO30=',1790665911),('YJGS5nOOv1Cn4znC5rVpwpQQvL18tommCACUqxIW',23,'10.213.15.222','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRmdEYTBzbjdITUpDaWNTTjA3RVZFeGd0b0ozQlRxRHliRFJsR2lFUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly8xMC4yMTMuMjQuNjY6ODA4MC9waW1waW5hbi9kYXNoYm9hcmQ/cGVyaW9kZT0yMDI2IjtzOjU6InJvdXRlIjtzOjE4OiJwaW1waW5hbi5kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMzt9',1790671913);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi_asramas`
--

DROP TABLE IF EXISTS `transaksi_asramas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaksi_asramas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `peserta_id` bigint(20) unsigned NOT NULL,
  `kamar_id` bigint(20) unsigned NOT NULL,
  `tanggal_masuk` datetime NOT NULL,
  `tanggal_keluar` datetime DEFAULT NULL,
  `status` enum('menginap','selesai') NOT NULL DEFAULT 'menginap',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_asramas_peserta_id_foreign` (`peserta_id`),
  KEY `transaksi_asramas_kamar_id_foreign` (`kamar_id`),
  CONSTRAINT `transaksi_asramas_kamar_id_foreign` FOREIGN KEY (`kamar_id`) REFERENCES `kamars` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_asramas_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `pesertas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi_asramas`
--

LOCK TABLES `transaksi_asramas` WRITE;
/*!40000 ALTER TABLE `transaksi_asramas` DISABLE KEYS */;
INSERT INTO `transaksi_asramas` VALUES (62,93,12,'2026-09-21 08:56:00','2026-09-21 09:09:36','selesai','2026-09-21 01:57:20','2026-09-21 02:09:36'),(63,27,65,'2026-09-21 09:06:00','2026-09-21 09:09:38','selesai','2026-09-21 02:07:01','2026-09-21 02:09:38'),(64,28,61,'2026-09-21 09:07:00','2026-09-21 09:09:41','selesai','2026-09-21 02:07:11','2026-09-21 02:09:41'),(65,93,64,'2026-09-21 09:11:00','2026-09-21 09:55:25','selesai','2026-09-21 02:11:59','2026-09-21 02:55:25'),(66,27,61,'2026-09-21 09:12:00','2026-09-21 09:55:19','selesai','2026-09-21 02:12:11','2026-09-21 02:55:19'),(67,28,61,'2026-09-21 09:12:00','2026-09-21 09:55:22','selesai','2026-09-21 02:12:20','2026-09-21 02:55:22'),(68,29,64,'2026-09-21 09:12:00','2026-09-21 09:55:23','selesai','2026-09-21 02:12:31','2026-09-21 02:55:23'),(69,30,62,'2026-09-21 09:13:00','2026-09-21 09:55:17','selesai','2026-09-21 02:13:26','2026-09-21 02:55:17'),(70,94,62,'2026-09-21 11:13:00','2026-09-21 13:30:42','selesai','2026-09-21 04:13:36','2026-09-21 06:30:42'),(71,93,62,'2026-10-07 09:14:00','2026-09-22 10:48:48','selesai','2026-09-22 02:15:50','2026-09-22 03:48:48'),(80,94,62,'2026-09-23 13:30:00','2026-09-23 14:44:46','selesai','2026-09-23 06:30:15','2026-09-23 07:44:46'),(81,27,67,'2026-09-23 14:43:00','2026-09-23 14:46:20','selesai','2026-09-23 07:43:25','2026-09-23 07:46:20'),(82,93,63,'2026-09-28 07:47:14','2026-09-28 07:47:54','selesai','2026-09-28 00:47:14','2026-09-28 00:47:54');
/*!40000 ALTER TABLE `transaksi_asramas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','resepsionis','pimpinan') NOT NULL DEFAULT 'resepsionis',
  `gedung_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_gedung_id_foreign` (`gedung_id`),
  CONSTRAINT `users_gedung_id_foreign` FOREIGN KEY (`gedung_id`) REFERENCES `gedungs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (21,'Admin User','admin@example.com','2026-09-15 04:35:46','$2y$12$OgjeyE.ODUNS6NQBVpEXuOhjg.Hrzo37yy9/kC8gOY7WLmEYjeDOi','admin',NULL,'fTPIYeJysds2EeQZib6PflC5adGIOIYLIyKFLneamFhUOBfOLESlkzzivfS8','2026-09-15 04:35:47','2026-09-15 04:35:47'),(22,'Asrama A','asramaA@gmail.com','2026-09-15 04:35:47','$2y$12$/7iIW90eVpkmaL.n57waWuZRJdZmJElNEozqupziQ4Z64NuqDrdJa','resepsionis',5,'czQAJvAQxYASua09z3yPX6Woxk2JMxAtvoKLse5tk1wvBerxsVoaLH8q8pCW','2026-09-15 04:35:47','2026-09-21 06:24:13'),(23,'Pimpinan User','pimpinan@example.com','2026-09-15 04:35:48','$2y$12$eXrYDFAqHrcLTkZpQgUhC.YI8cdAvRIjYEKBZb1cY6AsnbAmYLnha','pimpinan',NULL,'WtXI0byPmRKdwwCOS908W1uDYWfPP9cu9YSA3szhtGzcnPVrjc49T0udQSS9','2026-09-15 04:35:48','2026-09-15 04:35:48'),(24,'Asrama B','asramaB@gmail.com',NULL,'$2y$12$QZDaKBTmFlTdriNi8JYnVOR/HPwAPCAshNC9AE2wUNiXdDX8Vnmle','resepsionis',4,NULL,'2026-09-17 06:55:00','2026-09-21 06:24:38'),(25,'Asrama C','asramaC@gmail.com',NULL,'$2y$12$QM5ylqMdOYnzT6OaP0g/suhJlugcgoWqaxxXfXRgDhQthQXkS2mR2','resepsionis',3,NULL,'2026-09-17 06:55:39','2026-09-21 06:24:49'),(26,'All Asrama','resepsionis@gmail.com',NULL,'$2y$12$SRraMCEZnDeoetwLXlTdNul8wfBaH0VI02lx1ZJzF6pZU6yyO3pLi','resepsionis',NULL,'nuCHieqplPi7c5rVZRXy4GhX32Ur1BmyxMLkIf7Aj9nz7uXFJ9aXJUqjuoaJ','2026-09-17 06:56:25','2026-09-21 06:25:10');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'asrama_ppsdmap'
--

--
-- Dumping routines for database 'asrama_ppsdmap'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-30  8:32:05
