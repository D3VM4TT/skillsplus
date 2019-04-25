-- MySQL dump 10.13  Distrib 8.0.13, for Win64 (x86_64)
--
-- Host: localhost    Database: uat-poultec
-- ------------------------------------------------------
-- Server version	5.5.60-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `craft_assetfiles`
--

DROP TABLE IF EXISTS `craft_assetfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_assetfiles` (
  `id` int(11) NOT NULL,
  `sourceId` int(11) DEFAULT NULL,
  `folderId` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `kind` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unknown',
  `width` int(11) unsigned DEFAULT NULL,
  `height` int(11) unsigned DEFAULT NULL,
  `size` bigint(20) unsigned DEFAULT NULL,
  `dateModified` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_assetfiles_filename_folderId_unq_idx` (`filename`,`folderId`),
  KEY `craft_assetfiles_sourceId_fk` (`sourceId`),
  KEY `craft_assetfiles_folderId_fk` (`folderId`),
  CONSTRAINT `craft_assetfiles_folderId_fk` FOREIGN KEY (`folderId`) REFERENCES `craft_assetfolders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_assetfiles_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_assetfiles_sourceId_fk` FOREIGN KEY (`sourceId`) REFERENCES `craft_assetsources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_assetfiles`
--

LOCK TABLES `craft_assetfiles` WRITE;
/*!40000 ALTER TABLE `craft_assetfiles` DISABLE KEYS */;
INSERT INTO `craft_assetfiles` VALUES (428,NULL,9,'evidence.pdf','pdf',NULL,NULL,11708,'2018-04-23 10:42:09','2018-04-23 10:42:09','2018-04-23 10:42:09','48753b3e-a2bd-4594-aa83-7e90ae78e367'),(430,NULL,9,'evidence_180423_104254.pdf','pdf',NULL,NULL,11708,'2018-04-23 10:42:53','2018-04-23 10:42:54','2018-04-23 10:42:54','da535487-bbce-4d26-bd36-b62dbd44e3ea'),(432,NULL,9,'evidence_180423_105359.pdf','pdf',NULL,NULL,11708,'2018-04-23 10:53:59','2018-04-23 10:53:59','2018-04-23 10:53:59','710545de-961e-429b-b947-21d18217fb50'),(435,NULL,9,'evidence_180423_105559.pdf','pdf',NULL,NULL,11708,'2018-04-23 10:55:58','2018-04-23 10:55:59','2018-04-23 10:55:59','33cb68e1-eb2c-4dee-a067-a0bcfd2990da'),(437,NULL,9,'evidence_180423_105654.pdf','pdf',NULL,NULL,11708,'2018-04-23 10:56:53','2018-04-23 10:56:54','2018-04-23 10:56:54','8af10230-a940-4a55-8d9b-9139123637e2'),(440,NULL,9,'evidence_180423_110232.pdf','pdf',NULL,NULL,11708,'2018-04-23 11:02:31','2018-04-23 11:02:32','2018-04-23 11:02:32','c010ee68-862e-4d6f-81e1-0ed326b64151'),(443,NULL,9,'evidence_180423_115500.pdf','pdf',NULL,NULL,11708,'2018-04-23 11:54:59','2018-04-23 11:55:00','2018-04-23 11:55:00','7de46a75-3700-4987-a326-50cdcb32c278'),(446,NULL,9,'evidence_180424_090746.pdf','pdf',NULL,NULL,11708,'2018-04-24 09:07:44','2018-04-24 09:07:47','2018-04-24 09:07:47','361d07e9-b81d-4138-a4e1-f3a80862598a'),(448,NULL,9,'evidence_180424_091154.pdf','pdf',NULL,NULL,11708,'2018-04-24 09:11:53','2018-04-24 09:11:55','2018-04-24 09:11:55','b91e24ce-89b2-4272-aa82-7c110598a13e'),(450,NULL,9,'evidence_180424_091223.pdf','pdf',NULL,NULL,11708,'2018-04-24 09:12:22','2018-04-24 09:12:24','2018-04-24 09:12:24','af2555b3-16b6-414b-acef-3010c7a0b937'),(497,NULL,16,'evidence.pdf','pdf',NULL,NULL,11708,'2018-04-29 14:01:19','2018-04-29 14:01:19','2018-04-29 14:01:19','5abc0419-958b-419c-9ab2-9b802dae2156'),(1014,NULL,62,'Koala.jpg','image',1024,768,692706,'2018-06-08 11:32:14','2018-06-08 11:32:15','2018-06-08 11:32:15','73ceb5eb-6daa-40d6-bc85-d3d51ac27ce1'),(1190,NULL,9,'unit.png','image',400,400,6130,'2018-09-11 12:03:11','2018-09-11 12:03:11','2018-09-11 12:03:11','a4883ef7-6f02-492d-94f5-97f230129571'),(1200,NULL,9,'unit_180911_120731.png','image',400,400,6130,'2018-09-11 12:07:31','2018-09-11 12:07:31','2018-09-11 12:07:31','5a5da034-9271-41cf-9f24-f65769fe9ab1'),(1269,4,67,'LantraAwards_logo.png','image',434,307,57860,'2018-09-26 15:02:40','2018-09-26 15:02:40','2018-09-26 15:02:40','41387def-acad-4ec6-87f3-34339e92395f'),(1270,4,67,'bground-img-1.jpg','image',2550,2287,1917300,'2018-09-26 15:03:18','2018-09-26 15:03:18','2018-09-26 15:03:18','3ae9959f-103c-4c40-bbad-2edce9e946d0'),(1271,4,67,'bground-img-2.jpg','image',2550,2287,1124412,'2018-09-26 15:03:20','2018-09-26 15:03:20','2018-09-26 15:03:20','3c79b243-99c9-4871-b82a-a9f4e3e961c8'),(1272,4,67,'bground-img-3.jpg','image',2550,2287,921942,'2018-09-26 15:03:22','2018-09-26 15:03:22','2018-09-26 15:03:22','da2dc6ad-59c7-4229-810c-d22e002c5924'),(1273,4,67,'bground-img-4.jpg','image',2550,2287,1040254,'2018-09-26 15:03:23','2018-09-26 15:03:23','2018-09-26 15:03:23','23ee79df-dce3-423f-8ee6-c7e0595a1036'),(1535,NULL,4,'Example-evidence.docx','word',NULL,NULL,6149,'2018-11-07 12:10:53','2018-11-07 12:10:54','2018-11-07 12:10:54','dc32c3af-b278-4311-a51d-6b86f9c4e0e8'),(1537,NULL,4,'Example-evidence_181107_121324.docx','word',NULL,NULL,6149,'2018-11-07 12:13:24','2018-11-07 12:13:24','2018-11-07 12:13:24','d7b6af82-c1c0-4a7f-a553-7a46db535eee'),(1539,NULL,4,'Example-evidence_181107_121503.docx','word',NULL,NULL,6149,'2018-11-07 12:15:03','2018-11-07 12:15:03','2018-11-07 12:15:03','cc73f5c1-5658-491e-acb9-0fbea43c51a1'),(1541,NULL,4,'Example-evidence_181107_121521.docx','word',NULL,NULL,6149,'2018-11-07 12:15:21','2018-11-07 12:15:21','2018-11-07 12:15:21','7bb37792-2509-429a-b40d-21de0e7b457c'),(1543,NULL,4,'Example-evidence_181107_122321.docx','word',NULL,NULL,6149,'2018-11-07 12:23:21','2018-11-07 12:23:21','2018-11-07 12:23:21','aa71eeda-98c3-4d19-9eb5-de96e498a2df'),(1591,4,67,'about_us.jpg','image',1200,803,554065,'2018-11-15 12:21:05','2018-11-15 12:21:06','2018-11-15 12:21:06','02d1b7c3-c739-482c-aef4-685c1c1efc70'),(1678,NULL,73,'Koala.jpg','image',1024,768,854770,'2018-12-07 11:23:47','2018-12-07 11:23:47','2018-12-07 11:23:47','36c46810-731a-4538-924d-c2242184ef3b'),(1682,NULL,73,'Koala_181207_112804.jpg','image',1024,768,854770,'2018-12-07 11:28:04','2018-12-07 11:28:04','2018-12-07 11:28:04','21e45341-8c2c-4116-91cb-8e7976e92e94'),(1687,NULL,77,'Desert.jpg','image',1024,768,807872,'2018-12-07 12:11:52','2018-12-07 12:11:52','2018-12-07 12:11:52','8802f5c0-beb5-4009-8e03-e03462d54020'),(1895,NULL,4,'Example-evidence.txt','text',NULL,NULL,3,'2018-12-10 08:39:08','2018-12-10 08:39:09','2018-12-10 08:39:09','48579d2b-88a0-4ea7-8341-3b78cd0964ef'),(1896,NULL,4,'Example-evidence.pdf','pdf',NULL,NULL,14010,'2018-12-10 08:39:09','2018-12-10 08:39:09','2018-12-10 08:39:09','33a31def-56d4-4ecc-9a6b-05ffab14a131'),(1925,NULL,82,'dairy-milk.jpg','image',540,540,32441,'2018-12-10 09:27:51','2018-12-10 09:27:51','2018-12-10 09:27:51','1d908b8d-5a2b-4674-9a26-0e1c6daab663'),(2040,NULL,9,'current-draft.JPG','image',988,435,39858,'2018-12-11 09:30:40','2018-12-11 09:30:40','2018-12-11 09:30:40','2031f296-151e-4289-a36e-89bb07abd061'),(2047,NULL,9,'unit_181211_093556.png','image',400,400,6151,'2018-12-11 09:35:56','2018-12-11 09:35:56','2018-12-11 09:35:56','d8630dfa-9d8e-4887-9abd-4ad4d80049c9'),(2058,NULL,9,'unit.jpg','image',400,400,10416,'2018-12-11 10:01:14','2018-12-11 10:01:14','2018-12-11 10:01:14','ab344600-3c03-4756-88f2-2fd06b120b21'),(2078,NULL,73,'Desert.jpg','image',1024,768,894162,'2018-12-11 10:36:19','2018-12-11 10:36:19','2018-12-11 10:36:19','2428bb7a-ebc6-45c9-85f0-cf3a4273e345'),(2089,NULL,82,'lantra_logo.png','image',1416,308,63846,'2018-12-11 10:44:53','2018-12-11 10:44:53','2018-12-11 10:44:53','2f1da21f-dab8-4a27-8306-4e5e8896057c'),(2269,NULL,9,'unit_181212_031035.png','image',400,400,5627,'2018-12-12 03:10:35','2018-12-12 03:10:35','2018-12-12 03:10:35','7dbf27c4-00b1-41a3-bf83-326ef56d0363'),(2271,NULL,9,'unit_181212_031132.png','image',400,400,5627,'2018-12-12 03:11:32','2018-12-12 03:11:32','2018-12-12 03:11:32','bd39583a-51b2-4ebc-a2f8-3d64729896de'),(2273,NULL,9,'unit_181212_031309.png','image',400,400,5627,'2018-12-12 03:13:09','2018-12-12 03:13:09','2018-12-12 03:13:09','69e4a950-bc36-4525-9732-19b8eb582493'),(2275,NULL,9,'unit_181212_053614.jpg','image',400,400,10416,'2018-12-12 05:36:14','2018-12-12 05:36:14','2018-12-12 05:36:14','d5fbceed-d9e9-4059-93c5-df7e91474326'),(2277,NULL,9,'unit_181212_053630.png','image',400,400,5627,'2018-12-12 05:36:30','2018-12-12 05:36:30','2018-12-12 05:36:30','bfa5ee8e-f9bb-49b7-b68e-903b8f11ec8d'),(2279,NULL,9,'unit_181212_054206.png','image',400,400,5627,'2018-12-12 05:42:06','2018-12-12 05:42:06','2018-12-12 05:42:06','e7538d4e-4073-4cff-807a-212159032654'),(2281,NULL,9,'unit_181212_055558.png','image',400,400,5627,'2018-12-12 05:55:58','2018-12-12 05:55:58','2018-12-12 05:55:58','16952243-54f4-4aae-979a-8a36a1df17f1'),(2283,NULL,9,'unit_181212_055625.png','image',400,400,5627,'2018-12-12 05:56:25','2018-12-12 05:56:25','2018-12-12 05:56:25','e5047e6e-cf5a-4541-90e6-d05388fee826'),(2285,NULL,9,'unit_181212_064134.png','image',400,400,5627,'2018-12-12 06:41:34','2018-12-12 06:41:34','2018-12-12 06:41:34','805fba5b-ca43-45fe-a502-71a01a2ea6fe'),(2287,NULL,9,'unit_181212_064451.png','image',400,400,5627,'2018-12-12 06:44:51','2018-12-12 06:44:51','2018-12-12 06:44:51','b05e9df6-a9f7-45f6-aaa6-b23dc83589c8'),(2289,NULL,9,'unit_181212_064659.png','image',400,400,5627,'2018-12-12 06:46:59','2018-12-12 06:46:59','2018-12-12 06:46:59','c6cfc14f-9afd-4f00-87bf-1f3377083f73'),(2291,NULL,9,'unit_181212_064710.jpg','image',400,400,10416,'2018-12-12 06:47:10','2018-12-12 06:47:10','2018-12-12 06:47:10','eec97227-cc1e-4c08-8a76-0a07455cf2a5'),(2315,NULL,9,'unit_181212_070405.png','image',400,400,5627,'2018-12-12 07:04:05','2018-12-12 07:04:05','2018-12-12 07:04:05','51248251-26a0-4bb2-a6e2-42603b6696cc'),(3231,NULL,116,'Request-for-PP-Sales-Invoice.doc','word',NULL,NULL,72192,'2019-01-29 09:40:47','2019-01-29 09:40:47','2019-01-29 09:40:47','9c311f48-d2a2-421e-a81c-a167bba18df7'),(3239,NULL,116,'Request-for-LTP-Sales-Invoice.doc','word',NULL,NULL,65024,'2019-01-29 09:56:46','2019-01-29 09:56:46','2019-01-29 09:56:46','e1e9f4ff-cdf3-4b9c-99dc-b24683fa0675'),(3255,NULL,116,'Request-for-PTR-Sales-Invoice.doc','word',NULL,NULL,67584,'2019-01-29 11:10:03','2019-01-29 11:10:03','2019-01-29 11:10:03','68a7243a-abdc-4550-bcd6-03be9c5bbe2c');
/*!40000 ALTER TABLE `craft_assetfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_assetfolders`
--

DROP TABLE IF EXISTS `craft_assetfolders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_assetfolders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) DEFAULT NULL,
  `sourceId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_assetfolders_name_parentId_sourceId_unq_idx` (`name`,`parentId`,`sourceId`),
  KEY `craft_assetfolders_parentId_fk` (`parentId`),
  KEY `craft_assetfolders_sourceId_fk` (`sourceId`),
  CONSTRAINT `craft_assetfolders_parentId_fk` FOREIGN KEY (`parentId`) REFERENCES `craft_assetfolders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_assetfolders_sourceId_fk` FOREIGN KEY (`sourceId`) REFERENCES `craft_assetsources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_assetfolders`
--

LOCK TABLES `craft_assetfolders` WRITE;
/*!40000 ALTER TABLE `craft_assetfolders` DISABLE KEYS */;
INSERT INTO `craft_assetfolders` VALUES (1,NULL,1,'Evidence','','2017-10-24 10:57:32','2017-10-24 10:57:32','5568e8dd-7d3e-4d8d-8ced-5afeddb60ba8'),(2,NULL,NULL,'Temporary source',NULL,'2017-10-24 11:20:45','2017-10-24 11:20:45','f721e36e-80cc-4e92-9adf-32c1568860ff'),(3,2,NULL,'user_1','user_1/','2017-10-24 11:20:45','2017-10-24 11:20:45','df66eb36-bfe0-4a80-87f8-739a041fc407'),(4,3,NULL,'field_17','user_1/field_17/','2017-10-24 11:20:45','2017-10-24 11:20:45','b9de12f6-d1c6-4c9b-890f-6f2aef29f2e4'),(7,NULL,2,'Uploads','','2018-04-13 10:22:09','2018-04-13 10:22:09','51799375-adaa-4f52-84d9-338008463d77'),(8,2,NULL,'user_143','user_143/','2018-04-13 18:27:07','2018-04-13 18:27:07','c9c15e16-d724-4e76-94ad-97b589ee5f77'),(9,8,NULL,'field_17','user_143/field_17/','2018-04-13 18:27:07','2018-04-13 18:27:07','4ea25a1f-5443-42b2-ad65-43a47634309f'),(15,2,NULL,'user_51','user_51/','2018-04-29 14:01:19','2018-04-29 14:01:19','afd60c79-6027-4118-98f2-2c15eb13236f'),(16,15,NULL,'field_17','user_51/field_17/','2018-04-29 14:01:19','2018-04-29 14:01:19','1d33812a-533f-449b-a0bb-319e1d9cc310'),(18,2,NULL,'user_177','user_177/','2018-04-29 14:30:04','2018-04-29 14:30:04','afd7889e-bebf-419b-998f-ad4401e19fa3'),(19,18,NULL,'field_17','user_177/field_17/','2018-04-29 14:30:04','2018-04-29 14:30:04','e8a879c8-78c4-46ce-83fa-dbc7f1b5a084'),(21,2,NULL,'user_570','user_570/','2018-05-10 13:07:01','2018-05-10 13:07:01','0c0ba86b-fd9a-488f-9169-8093d3c03308'),(22,21,NULL,'field_17','user_570/field_17/','2018-05-10 13:07:01','2018-05-10 13:07:01','037cf71c-b494-4047-ab3a-5f987fab285e'),(24,2,NULL,'user_666','user_666/','2018-05-10 15:40:08','2018-05-10 15:40:08','b8cd2d95-da95-4727-8b61-55414110126a'),(25,24,NULL,'field_17','user_666/field_17/','2018-05-10 15:40:08','2018-05-10 15:40:08','2586d473-e60c-4575-acde-1e82e6d7d4fa'),(27,2,NULL,'user_691','user_691/','2018-05-10 16:50:13','2018-05-10 16:50:13','6318393f-8438-4a99-ade8-5493655d1fb1'),(28,27,NULL,'field_17','user_691/field_17/','2018-05-10 16:50:13','2018-05-10 16:50:13','d14f4667-6872-45b5-b978-98a284283441'),(30,2,NULL,'user_704','user_704/','2018-05-10 17:24:36','2018-05-10 17:24:36','f17277d4-e7e5-4f8c-bc92-df31494db8eb'),(31,30,NULL,'field_17','user_704/field_17/','2018-05-10 17:24:36','2018-05-10 17:24:36','4dc59f4e-7a34-402a-b1f4-c574e543c9d4'),(33,2,NULL,'user_727','user_727/','2018-05-11 09:32:39','2018-05-11 09:32:39','b0b3ab6a-c8bb-4719-adb8-83fe7b1746bf'),(34,33,NULL,'field_17','user_727/field_17/','2018-05-11 09:32:39','2018-05-11 09:32:39','83c2464e-6415-45fb-800b-b77c413732d6'),(36,2,NULL,'user_795','user_795/','2018-05-15 09:52:24','2018-05-15 09:52:24','45fc9c3f-fb60-4f88-814a-1e436b18c123'),(37,36,NULL,'field_17','user_795/field_17/','2018-05-15 09:52:24','2018-05-15 09:52:24','12e2a18a-22e2-40ea-b461-4e19d019695c'),(39,2,NULL,'user_801','user_801/','2018-05-15 16:03:03','2018-05-15 16:03:03','67743b2a-c452-46d8-ac36-f352a8abe44f'),(40,39,NULL,'field_17','user_801/field_17/','2018-05-15 16:03:03','2018-05-15 16:03:03','8d11988b-6408-4f96-a276-aa963a7f68fc'),(42,2,NULL,'user_802','user_802/','2018-05-15 16:13:14','2018-05-15 16:13:14','2954ce1c-4ec3-40c1-8780-6377e047f23a'),(43,42,NULL,'field_17','user_802/field_17/','2018-05-15 16:13:14','2018-05-15 16:13:14','30ed93b5-29ca-4f16-b84f-89fbd8d7e6cf'),(45,2,NULL,'user_796','user_796/','2018-05-15 16:14:45','2018-05-15 16:14:45','2b53e1ac-2e80-4251-a394-9e79d71f2d2f'),(46,45,NULL,'field_17','user_796/field_17/','2018-05-15 16:14:45','2018-05-15 16:14:45','856e2042-8639-43f0-8a7e-bc229b63e36e'),(48,2,NULL,'user_821','user_821/','2018-05-16 10:46:15','2018-05-16 10:46:15','a59b15b4-990c-4d1c-84b2-21ebc5476f1b'),(49,48,NULL,'field_17','user_821/field_17/','2018-05-16 10:46:15','2018-05-16 10:46:15','535625f0-39fa-455b-ada2-1ec806f9bd3e'),(51,2,NULL,'user_857','user_857/','2018-05-17 09:36:08','2018-05-17 09:36:08','a9a888e0-6ee1-46da-9283-c3e1f92a96d7'),(52,51,NULL,'field_17','user_857/field_17/','2018-05-17 09:36:08','2018-05-17 09:36:08','97152970-2b17-4d04-924e-506d05b46ec5'),(54,2,NULL,'user_868','user_868/','2018-05-29 08:29:16','2018-05-29 08:29:16','a5a9d092-7d42-46e4-8f8f-dd40ea63fdb0'),(55,54,NULL,'field_17','user_868/field_17/','2018-05-29 08:29:16','2018-05-29 08:29:16','e8bae52b-ec32-43d1-996f-0af1b27ef16d'),(57,2,NULL,'user_869','user_869/','2018-05-29 08:30:58','2018-05-29 08:30:58','55da642f-aaba-44ab-946c-e70341f2d91e'),(58,57,NULL,'field_17','user_869/field_17/','2018-05-29 08:30:58','2018-05-29 08:30:58','86681c43-e90b-44fe-a45a-2f5a3a97787f'),(60,NULL,3,'Data','','2018-06-02 10:40:02','2018-06-02 10:40:02','6d6d12c5-d123-463b-87c8-94e34018c0d8'),(61,2,NULL,'user_1011','user_1011/','2018-06-08 11:32:14','2018-06-08 11:32:14','a104941c-8bb4-47cc-9316-fcf4a8e2d28a'),(62,61,NULL,'field_17','user_1011/field_17/','2018-06-08 11:32:14','2018-06-08 11:32:14','a22f56b4-d4f9-4a4d-bf4d-3838fc368c35'),(64,2,NULL,'user_782','user_782/','2018-06-13 11:06:39','2018-06-13 11:06:39','ae0bff41-638a-4df9-9db1-ab63e254884d'),(65,64,NULL,'field_17','user_782/field_17/','2018-06-13 11:06:39','2018-06-13 11:06:39','e1f34d88-f3a4-4925-8b6c-f0010ef2f41d'),(67,NULL,4,'Theme','','2018-08-21 15:26:26','2018-08-21 15:26:26','196acc39-467c-4de5-b68f-b049ebe48e58'),(72,2,NULL,'user_1438','user_1438/','2018-12-07 11:23:47','2018-12-07 11:23:47','d031a7ac-d829-4619-83ac-7f517573111b'),(73,72,NULL,'field_17','user_1438/field_17/','2018-12-07 11:23:47','2018-12-07 11:23:47','c36caa83-4dd2-4a2d-82b9-0a592da91dc4'),(76,2,NULL,'user_1686','user_1686/','2018-12-07 12:11:52','2018-12-07 12:11:52','bdf88fa6-041e-4832-8b25-a3b4e60e9248'),(77,76,NULL,'field_17','user_1686/field_17/','2018-12-07 12:11:52','2018-12-07 12:11:52','c5dcb6a0-de54-44ed-afd6-d250e247330c'),(81,2,NULL,'user_1423','user_1423/','2018-12-10 09:27:50','2018-12-10 09:27:50','11637c93-219e-426f-b17e-523ad67c6b24'),(82,81,NULL,'field_17','user_1423/field_17/','2018-12-10 09:27:50','2018-12-10 09:27:50','eaa13ce9-4b11-49ef-9770-2fd2d6b10780'),(92,2,NULL,'user_1666','user_1666/','2018-12-12 00:42:37','2018-12-12 00:42:37','145e82a5-e447-48e8-88d8-ace6d8d2da99'),(93,92,NULL,'field_17','user_1666/field_17/','2018-12-12 00:42:37','2018-12-12 00:42:37','f8828389-5f3d-4c98-9297-ea088ea25e20'),(97,2,NULL,'user_2338','user_2338/','2018-12-13 10:36:45','2018-12-13 10:36:45','72290fa8-02d2-4a49-a694-257cc2817aed'),(98,97,NULL,'field_17','user_2338/field_17/','2018-12-13 10:36:45','2018-12-13 10:36:45','2aabf7a4-2c70-474c-958f-954da69ce9dc'),(100,2,NULL,'user_2355','user_2355/','2018-12-13 11:42:19','2018-12-13 11:42:19','ef761cd7-70a4-4b7b-946f-a72f3a7b5577'),(101,100,NULL,'field_17','user_2355/field_17/','2018-12-13 11:42:19','2018-12-13 11:42:19','7af889cf-e7f4-44a7-afb1-1b0c38d3b1f2'),(105,2,NULL,'user_2385','user_2385/','2019-01-14 15:53:04','2019-01-14 15:53:04','f1b8adb4-1a3b-4b92-830b-bf775372aa4b'),(106,105,NULL,'field_17','user_2385/field_17/','2019-01-14 15:53:05','2019-01-14 15:53:05','3ca4ea0f-a65e-4730-87a6-7f8a985862b0'),(109,2,NULL,'user_2450','user_2450/','2019-01-16 10:51:06','2019-01-16 10:51:06','b0151bb0-7e73-45c8-8939-0c12958055dd'),(110,109,NULL,'field_17','user_2450/field_17/','2019-01-16 10:51:06','2019-01-16 10:51:06','63b52cdf-2e21-43ae-900d-dda91b389410'),(113,2,NULL,'user_2462','user_2462/','2019-01-18 09:57:58','2019-01-18 09:57:58','bb947ffc-4a91-483a-a9a5-ee80049e75b5'),(114,113,NULL,'field_17','user_2462/field_17/','2019-01-18 09:57:58','2019-01-18 09:57:58','b3bc9037-cf68-47cf-8ab6-ceec48205aa9'),(115,2,NULL,'user_3217','user_3217/','2019-01-29 09:40:47','2019-01-29 09:40:47','65e9b157-47a0-476a-a1d7-58a710239615'),(116,115,NULL,'field_17','user_3217/field_17/','2019-01-29 09:40:47','2019-01-29 09:40:47','13cbe008-a9b4-431d-a99c-9dfc254bcaf8');
/*!40000 ALTER TABLE `craft_assetfolders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_assetindexdata`
--

DROP TABLE IF EXISTS `craft_assetindexdata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_assetindexdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sessionId` varchar(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sourceId` int(10) NOT NULL,
  `offset` int(10) NOT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recordId` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_assetindexdata_sessionId_sourceId_offset_unq_idx` (`sessionId`,`sourceId`,`offset`),
  KEY `craft_assetindexdata_sourceId_fk` (`sourceId`),
  CONSTRAINT `craft_assetindexdata_sourceId_fk` FOREIGN KEY (`sourceId`) REFERENCES `craft_assetsources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_assetindexdata`
--

LOCK TABLES `craft_assetindexdata` WRITE;
/*!40000 ALTER TABLE `craft_assetindexdata` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_assetindexdata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_assetsources`
--

DROP TABLE IF EXISTS `craft_assetsources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_assetsources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `fieldLayoutId` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_assetsources_name_unq_idx` (`name`),
  UNIQUE KEY `craft_assetsources_handle_unq_idx` (`handle`),
  KEY `craft_assetsources_fieldLayoutId_fk` (`fieldLayoutId`),
  CONSTRAINT `craft_assetsources_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_assetsources`
--

LOCK TABLES `craft_assetsources` WRITE;
/*!40000 ALTER TABLE `craft_assetsources` DISABLE KEYS */;
INSERT INTO `craft_assetsources` VALUES (1,'Evidence','evidence','Local','{\"path\":\"{basePath}craft-assets\\/evidence\\/\",\"publicURLs\":\"\",\"url\":\"\"}',1,288,'2017-10-24 10:57:32','2019-02-11 12:41:54','a6dafdf7-e4c0-4943-aff3-e481bbdf23fc'),(2,'Uploads','uploads','Local','{\"path\":\"{basePath}html\\/assets\\/uploads\\/\",\"publicURLs\":\"1\",\"url\":\"\\/assets\\/uploads\\/\"}',2,289,'2018-04-13 10:22:09','2019-02-11 12:42:03','f6dc0b30-2f16-4c2d-ad2e-849d3de5d0f9'),(3,'Data','data','Local','{\"path\":\"{basePath}craft-assets\\/data\\/\",\"publicURLs\":\"\",\"url\":\"\"}',3,290,'2018-06-02 10:40:02','2019-02-11 12:42:13','e1099881-d76d-4c4c-9b8b-607658d27413'),(4,'Theme','theme','Local','{\"path\":\"{basePath}assets\\/theme\\/\",\"publicURLs\":\"1\",\"url\":\"\\/assets\\/theme\\/\"}',4,291,'2018-08-21 15:26:26','2019-02-11 12:42:21','f2ee622f-5058-4e7d-a3d0-1d003dc32c29');
/*!40000 ALTER TABLE `craft_assetsources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_assettransformindex`
--

DROP TABLE IF EXISTS `craft_assettransformindex`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_assettransformindex` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fileId` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `format` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sourceId` int(11) DEFAULT NULL,
  `fileExists` tinyint(1) DEFAULT NULL,
  `inProgress` tinyint(1) DEFAULT NULL,
  `dateIndexed` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_assettransformindex_sourceId_fileId_location_idx` (`sourceId`,`fileId`,`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_assettransformindex`
--

LOCK TABLES `craft_assettransformindex` WRITE;
/*!40000 ALTER TABLE `craft_assettransformindex` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_assettransformindex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_assettransforms`
--

DROP TABLE IF EXISTS `craft_assettransforms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_assettransforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mode` enum('stretch','fit','crop') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'crop',
  `position` enum('top-left','top-center','top-right','center-left','center-center','center-right','bottom-left','bottom-center','bottom-right') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'center-center',
  `height` int(10) DEFAULT NULL,
  `width` int(10) DEFAULT NULL,
  `format` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quality` int(10) DEFAULT NULL,
  `dimensionChangeTime` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_assettransforms_name_unq_idx` (`name`),
  UNIQUE KEY `craft_assettransforms_handle_unq_idx` (`handle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_assettransforms`
--

LOCK TABLES `craft_assettransforms` WRITE;
/*!40000 ALTER TABLE `craft_assettransforms` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_assettransforms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_categories`
--

DROP TABLE IF EXISTS `craft_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_categories` (
  `id` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_categories_groupId_fk` (`groupId`),
  CONSTRAINT `craft_categories_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_categorygroups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_categories_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_categories`
--

LOCK TABLES `craft_categories` WRITE;
/*!40000 ALTER TABLE `craft_categories` DISABLE KEYS */;
INSERT INTO `craft_categories` VALUES (1762,1,'2018-12-07 13:43:58','2019-02-21 14:21:33','c224c291-0a5d-4977-acda-eac90837c372');
/*!40000 ALTER TABLE `craft_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_categorygroups`
--

DROP TABLE IF EXISTS `craft_categorygroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_categorygroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `structureId` int(11) NOT NULL,
  `fieldLayoutId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hasUrls` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `template` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_categorygroups_name_unq_idx` (`name`),
  UNIQUE KEY `craft_categorygroups_handle_unq_idx` (`handle`),
  KEY `craft_categorygroups_structureId_fk` (`structureId`),
  KEY `craft_categorygroups_fieldLayoutId_fk` (`fieldLayoutId`),
  CONSTRAINT `craft_categorygroups_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `craft_categorygroups_structureId_fk` FOREIGN KEY (`structureId`) REFERENCES `craft_structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_categorygroups`
--

LOCK TABLES `craft_categorygroups` WRITE;
/*!40000 ALTER TABLE `craft_categorygroups` DISABLE KEYS */;
INSERT INTO `craft_categorygroups` VALUES (1,1,221,'Job Roles','roles',0,NULL,'2017-10-23 14:49:32','2018-10-04 15:34:00','d83da856-af9d-458e-8b34-08ff74c012b5'),(2,2,255,'Module Groups','moduleGroups',0,NULL,'2018-11-26 17:51:54','2018-12-11 07:56:08','98a87e5b-9324-4727-ab66-b99bfdccbc68');
/*!40000 ALTER TABLE `craft_categorygroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_categorygroups_i18n`
--

DROP TABLE IF EXISTS `craft_categorygroups_i18n`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_categorygroups_i18n` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `urlFormat` text COLLATE utf8_unicode_ci,
  `nestedUrlFormat` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_categorygroups_i18n_groupId_locale_unq_idx` (`groupId`,`locale`),
  KEY `craft_categorygroups_i18n_locale_fk` (`locale`),
  CONSTRAINT `craft_categorygroups_i18n_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_categorygroups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_categorygroups_i18n_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_categorygroups_i18n`
--

LOCK TABLES `craft_categorygroups_i18n` WRITE;
/*!40000 ALTER TABLE `craft_categorygroups_i18n` DISABLE KEYS */;
INSERT INTO `craft_categorygroups_i18n` VALUES (1,1,'en_gb',NULL,NULL,'2017-10-23 14:49:32','2017-10-23 14:49:32','9cb701d5-3e22-4da9-ac47-81672685da1d'),(2,2,'en_gb',NULL,NULL,'2018-11-26 17:51:54','2018-11-26 17:51:54','79529454-cfa8-4708-ae60-2dc6d1a918e7');
/*!40000 ALTER TABLE `craft_categorygroups_i18n` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_content`
--

DROP TABLE IF EXISTS `craft_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_pageBody` text COLLATE utf8_unicode_ci,
  `field_pageHeading` text COLLATE utf8_unicode_ci,
  `field_locationName` text COLLATE utf8_unicode_ci,
  `field_teamName` text COLLATE utf8_unicode_ci,
  `field_companyRemainingLicences` int(10) unsigned DEFAULT '0',
  `field_schemeRemainingLicences` int(10) unsigned DEFAULT '0',
  `field_unitType` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'evidence',
  `field_unitUrl` text COLLATE utf8_unicode_ci,
  `field_resultScore` int(10) unsigned DEFAULT '0',
  `field_resultStatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_teamDescription` text COLLATE utf8_unicode_ci,
  `field_moduleDescription` text COLLATE utf8_unicode_ci,
  `field_resultEndorsedDate` datetime DEFAULT NULL,
  `field_unitDescription` text COLLATE utf8_unicode_ci,
  `field_moduleCompletedValue` int(10) unsigned DEFAULT '0',
  `field_unitValue` int(10) unsigned DEFAULT '0',
  `field_testPassPercent` tinyint(3) unsigned DEFAULT '0',
  `field_moduleExpiryDays` int(10) unsigned DEFAULT '0',
  `field_testMaxAttempts` int(10) unsigned DEFAULT '0',
  `field_schemeName` text COLLATE utf8_unicode_ci,
  `field_dateFormat` text COLLATE utf8_unicode_ci,
  `field_defaultLimit` int(10) unsigned DEFAULT '0',
  `field_schemeExpiryDate` datetime DEFAULT NULL,
  `field_userTelephone` text COLLATE utf8_unicode_ci,
  `field_individualLicenceDays` int(10) unsigned DEFAULT '0',
  `field_userExpiryDate` datetime DEFAULT NULL,
  `field_resultNotes` text COLLATE utf8_unicode_ci,
  `field_individualLicencePaypalButton` text COLLATE utf8_unicode_ci,
  `field_reportType` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_reportAllCompanies` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_reportAllTeams` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_reportAllModules` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_reportAllRoles` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_reportRecipients` text COLLATE utf8_unicode_ci,
  `field_reportSendFrequency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_reportSendValue` int(10) unsigned DEFAULT '0',
  `field_reportDescription` text COLLATE utf8_unicode_ci,
  `field_reportCount` int(10) unsigned DEFAULT '0',
  `field_reportLastSentDate` datetime DEFAULT NULL,
  `field_reportResultExpiry` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_schemeDescription` text COLLATE utf8_unicode_ci,
  `field_themeColorPrimary` char(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_themeColorSecondary` char(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_userStartDate` datetime DEFAULT NULL,
  `field_userDateOfBirth` datetime DEFAULT NULL,
  `field_userAddress` text COLLATE utf8_unicode_ci,
  `field_userEditCustomFields` text COLLATE utf8_unicode_ci,
  `field_managerLevel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_unitEndorsementManagerLevel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_schemeTeams` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_managerReadOnly` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_schemeEmailDomain` text COLLATE utf8_unicode_ci,
  `field_resultStartDate` datetime DEFAULT NULL,
  `field_resultFinishDate` datetime DEFAULT NULL,
  `field_resultLocation` text COLLATE utf8_unicode_ci,
  `field_resultValue` int(10) unsigned DEFAULT '0',
  `field_legacyCompanyId` int(10) unsigned DEFAULT '0',
  `field_legacyEmail` text COLLATE utf8_unicode_ci,
  `field_legacyGroup` text COLLATE utf8_unicode_ci,
  `field_legacyId` int(10) unsigned DEFAULT '0',
  `field_legacyJobRoleId` int(10) unsigned DEFAULT '0',
  `field_legacyParentId` int(10) unsigned DEFAULT '0',
  `field_resultDisplayLocation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_resultDisplayStartDate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_resultDisplayFinishDate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_resultDisplayEndorsedDate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_resultDisplayType` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_resultDisplayValue` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_resultDisplayExpiryDate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_resultDisplayEvidence` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_resultDisplayStatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_resultDisplayHours` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_resultHours` text COLLATE utf8_unicode_ci,
  `field_userDummyEmail` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_addAchievement` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_userLicenceSource` text COLLATE utf8_unicode_ci,
  `field_resultSearch` text COLLATE utf8_unicode_ci,
  `field_userSearch` text COLLATE utf8_unicode_ci,
  `field_schemeTestEmailAddress` text COLLATE utf8_unicode_ci,
  `field_addAchievementHide` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_schemeUserReadOnly` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_userEditDob` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_userEditAddress` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_userEditPhoto` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_userEditName` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_userEditEmail` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_userEditTelephone` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_userEditRole` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_userCompanyName` text COLLATE utf8_unicode_ci,
  `field_unitHeading` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_content_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_content_title_idx` (`title`),
  KEY `craft_content_locale_fk` (`locale`),
  CONSTRAINT `craft_content_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_content_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2333 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_content`
--

LOCK TABLES `craft_content` WRITE;
/*!40000 ALTER TABLE `craft_content` DISABLE KEYS */;
INSERT INTO `craft_content` VALUES (1,1,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'07900 545 470',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,'',NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2017-10-23 13:26:42','2019-01-24 10:36:07','b5ba33c4-0e66-4812-8c20-e11a3242c2f7'),(118,143,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ss',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,'',NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,'',NULL,'2018-03-10 15:10:18','2019-02-21 14:36:41','1339ddb9-1eb5-4db5-a6d9-93871c3af6d9'),(251,428,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-23 10:42:09','2018-04-23 10:42:09','f01a7d13-dc96-4b30-9c9c-3d2ab770dcda'),(253,430,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-23 10:42:54','2018-04-23 10:42:54','e192016b-dd65-4835-aef5-6b312b4505c1'),(255,432,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-23 10:53:59','2018-04-23 10:53:59','bfcad42c-45ce-444f-8ec5-16283e959fa3'),(258,435,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-23 10:55:59','2018-04-23 10:55:59','194bc541-0c12-4286-bcf4-17589e9119cd'),(260,437,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-23 10:56:54','2018-04-23 10:56:54','7d564773-252e-4fba-b552-295648a21add'),(263,440,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-23 11:02:32','2018-04-23 11:02:32','3ef41033-3510-4b7c-9816-6c4016a62b97'),(266,443,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-23 11:55:00','2018-04-23 11:55:00','630a80cb-d0b8-4932-96d0-5f0468532468'),(269,446,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-24 09:07:46','2018-04-24 09:07:46','64d5422f-0699-40de-8c80-4e50af062f73'),(271,448,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-24 09:11:54','2018-04-24 09:11:54','5eefbf8d-98ca-470a-b68c-921fb9275938'),(273,450,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-24 09:12:23','2018-04-24 09:12:23','c6677227-f64f-4d00-b39f-ee835470d6a2'),(292,488,'en_gb',NULL,NULL,NULL,NULL,NULL,0,1000,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,'',NULL,0,NULL,NULL,365,NULL,NULL,'<form action=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_top\">\r\n<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">\r\n<input type=\"hidden\" name=\"hosted_button_id\" value=\"RKVQSNXFXJK4G\">\r\n<input type=\"image\" src=\"https://www.sandbox.paypal.com/en_US/GB/i/btn/btn_buynowCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal – The safer, easier way to pay online!\">\r\n<img alt=\"\" border=\"0\" src=\"https://www.sandbox.paypal.com/en_GB/i/scr/pixel.gif\" width=\"1\" height=\"1\">\r\n<input type=\"hidden\" name=\"custom\" value=\"{{ currentUser.id }}\">\r\n</form>\r\n',NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'margaret.murray@lantra.co.uk, portia.hartley@skills-plus.co.uk',0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-26 16:31:48','2019-02-21 14:20:06','b1c69c96-f3be-4cad-972f-95f97406d6e9'),(293,489,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,'d/m/y',10,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-29 12:39:32','2018-09-13 15:49:59','69c56cd5-0c2b-4c7b-8ea2-1baf75f76561'),(297,497,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-04-29 14:01:19','2018-04-29 14:01:19','ac39c15e-5ebc-438c-9504-97b371f006d0'),(724,1014,'en_gb','Koala',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-06-08 11:32:15','2018-06-08 11:32:15','8ee6101e-6636-4f57-971a-55be86284b8a'),(787,1079,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,'Lantra CPD',NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,'','#00bfbf','#1c2b39',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-08-21 15:22:30','2019-02-21 14:36:13','d51542c1-39e0-49fb-bd83-dcbbf9b37120'),(794,1086,'en_gb','About Lantra','','Industry plays an essential part in Lantra\'s work. Our role often involves working closely with industry groups to deliver solutions to specific industry needs.',NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-08-23 14:29:32','2018-12-07 10:17:14','49137c8c-c9db-4d58-88ee-c65b492eae02'),(795,1090,'en_gb','Contact Us','','We\'re here to help. Use the form below to contact a member of the team.',NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-08-23 14:30:31','2019-01-18 13:30:11','5a8e0c4a-e1c8-4723-a13f-8330e39b4a65'),(876,1190,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-09-11 12:03:11','2018-09-11 12:03:11','87b288f8-c727-4502-80de-6f8689b681b9'),(886,1200,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-09-11 12:07:31','2018-09-11 12:07:31','e7e3098c-5517-45de-abee-11b067348989'),(944,1269,'en_gb','Lantra Awards Logo',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-09-26 15:02:40','2018-09-26 15:02:40','1223a596-cec6-44f6-9980-701104c2e31d'),(945,1270,'en_gb','Bground Img 1',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-09-26 15:03:18','2018-09-26 15:03:18','4121608c-212f-4207-8958-06ae2d16b2d1'),(946,1271,'en_gb','Bground Img 2',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-09-26 15:03:20','2018-09-26 15:03:20','49b6f7b3-bddf-47d3-824b-545be91b4aea'),(947,1272,'en_gb','Bground Img 3',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-09-26 15:03:22','2018-09-26 15:03:22','8f00663c-add7-4fa2-b142-b368feec19b0'),(948,1273,'en_gb','Bground Img 4',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-09-26 15:03:23','2018-09-26 15:03:23','64e0fb01-c19e-4936-a0af-dbca6d22af9b'),(1074,1423,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-10-04 15:35:59','2018-10-15 11:33:23','fcae252c-2935-46ec-8989-56332f8bac9d'),(1086,1438,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-10-11 09:02:13','2018-12-11 10:59:05','1af36f27-7549-413d-87b8-460a8764dc7b'),(1088,1440,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-10-12 09:36:14','2018-10-12 09:36:14','65e64a6a-b2b7-42d4-be75-26cd3b902cd0'),(1170,1535,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-11-07 12:10:54','2018-11-07 12:10:54','56b156e6-658f-4ed2-9933-3fa041713a22'),(1172,1537,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-11-07 12:13:24','2018-11-07 12:13:24','b8d7a24a-c942-428f-aaf4-a73387a366d4'),(1174,1539,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-11-07 12:15:03','2018-11-07 12:15:03','4bf19b30-d897-43be-86f4-4d0d914ba919'),(1176,1541,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-11-07 12:15:21','2018-11-07 12:15:21','fd7ee229-d7a9-4042-89d7-05a7a2ccdfd2'),(1178,1543,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-11-07 12:23:21','2018-11-07 12:23:21','51017243-9026-4377-908d-c4cc92ad1691'),(1221,1591,'en_gb','About Us',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-11-15 12:21:06','2018-11-15 12:21:06','be0f1ff2-503b-4ce0-bd17-32dcabc63797'),(1225,1595,'en_gb','FAQs','','Learning a new skill doesn’t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.',NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-11-20 14:06:55','2018-11-23 15:46:13','73faf4c5-ec4f-40e4-b0bc-7e4ae1f8e63d'),(1283,1678,'en_gb','Koala',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-07 11:23:47','2018-12-07 11:23:47','2f81eb1e-a25e-48f6-9afd-ac35aa0e0619'),(1286,1682,'en_gb','Koala',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-07 11:28:04','2018-12-07 11:28:04','01d5e06b-e3e9-42cc-b98c-2907beacafe4'),(1291,1687,'en_gb','Desert',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-07 12:11:52','2018-12-07 12:11:52','460602db-9c6d-45f5-ac85-54ae9518fd9d'),(1366,1762,'en_gb','Site Admin',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-07 13:43:58','2019-02-21 14:21:33','9978139e-b1e1-45a9-a6f4-ced5e7d571d1'),(1499,1895,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-10 08:39:08','2018-12-10 08:39:08','94142fbb-9089-4f51-a395-9ced17b706eb'),(1500,1896,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-10 08:39:09','2018-12-10 08:39:09','d5b17bb5-83d9-4bff-b536-1bf6aca1ec4c'),(1527,1925,'en_gb','Dairy Milk',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-10 09:27:51','2018-12-10 09:27:51','86e055c1-80d6-4f82-b5e2-ceaf8afc5f7c'),(1636,2040,'en_gb','Current Draft',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-11 09:30:40','2018-12-11 09:30:40','c7628d89-50c1-468f-827a-b5b2eb70125d'),(1643,2047,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-11 09:35:56','2018-12-11 09:35:56','698ce255-2f1b-4e10-bc5d-f90840d7661f'),(1654,2058,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-11 10:01:14','2018-12-11 10:01:14','d6dbb009-d989-4f14-b909-9a2cb94ef134'),(1668,2078,'en_gb','Desert',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-11 10:36:19','2018-12-11 10:36:19','6c2b0338-d09c-44b5-a9fd-dfaf748f4b61'),(1677,2089,'en_gb','Lantra Logo',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-11 10:44:53','2018-12-11 10:44:53','fea06937-c4af-4dc5-b4ec-a4642cbad4f6'),(1856,2269,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 03:10:35','2018-12-12 03:10:35','eba281e5-b4b0-4c1e-9645-3ba622115fbe'),(1858,2271,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 03:11:32','2018-12-12 03:11:32','04bff7d7-7120-4370-b84a-ff49aadbee02'),(1860,2273,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 03:13:09','2018-12-12 03:13:09','0424cfa8-961f-4d2c-ad8d-0e39d468bdac'),(1862,2275,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 05:36:14','2018-12-12 05:36:14','76fff7c2-b922-41ae-a079-c92b1dff9000'),(1864,2277,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 05:36:30','2018-12-12 05:36:30','ad2329bd-b9c1-40ca-8287-e11b134603bd'),(1866,2279,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 05:42:06','2018-12-12 05:42:06','7fdab0e8-4b37-4940-aa91-adda2c04a2c5'),(1868,2281,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 05:55:58','2018-12-12 05:55:58','e535c7da-dd78-4113-a1a3-8381184e2603'),(1870,2283,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 05:56:25','2018-12-12 05:56:25','0bc89977-3c85-40ad-ba2e-3bceb76da6b4'),(1872,2285,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 06:41:34','2018-12-12 06:41:34','bee8767c-e3ab-420c-807c-85a3c1e7d582'),(1874,2287,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 06:44:51','2018-12-12 06:44:51','7c226437-5a1b-4591-8409-d22d6f82b0f4'),(1876,2289,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 06:46:59','2018-12-12 06:46:59','f4f412cf-1e08-441c-8df4-793ef3924d61'),(1878,2291,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 06:47:10','2018-12-12 06:47:10','66697f48-7139-4c99-88b7-fa9d519e5465'),(1902,2315,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2018-12-12 07:04:05','2018-12-12 07:04:05','ed859329-37f9-4d2b-86b4-d936a4998c3b'),(2136,3231,'en_gb','Request For Pp Sales Invoice',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2019-01-29 09:40:47','2019-01-29 09:40:47','1391fb50-bff7-418b-b2f3-5570e3dd2005'),(2142,3239,'en_gb','Request For Ltp Sales Invoice',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2019-01-29 09:56:46','2019-01-29 09:56:46','e9695ec6-9e69-492d-ae8f-d2a10ba48687'),(2152,3255,'en_gb','Request For Ptr Sales Invoice',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,0,0,0,0,0,NULL,NULL,'2019-01-29 11:10:03','2019-01-29 11:10:03','25d59e95-a5c6-4ecf-9ff1-2a4155fcba39'),(2161,3266,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,NULL,0,0,1,1,1,0,1,1,0,NULL,NULL,'2019-02-01 09:43:26','2019-02-21 14:21:11','9a099711-e428-4fa7-a174-522f86835549');
/*!40000 ALTER TABLE `craft_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_deprecationerrors`
--

DROP TABLE IF EXISTS `craft_deprecationerrors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_deprecationerrors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fingerprint` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastOccurrence` datetime NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `line` smallint(6) unsigned NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `templateLine` smallint(6) unsigned DEFAULT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `traces` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_deprecationerrors_key_fingerprint_unq_idx` (`key`,`fingerprint`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_deprecationerrors`
--

LOCK TABLES `craft_deprecationerrors` WRITE;
/*!40000 ALTER TABLE `craft_deprecationerrors` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_deprecationerrors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_elementindexsettings`
--

DROP TABLE IF EXISTS `craft_elementindexsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_elementindexsettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_elementindexsettings_type_unq_idx` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_elementindexsettings`
--

LOCK TABLES `craft_elementindexsettings` WRITE;
/*!40000 ALTER TABLE `craft_elementindexsettings` DISABLE KEYS */;
INSERT INTO `craft_elementindexsettings` VALUES (1,'Entry','{\"sources\":{\"section:3\":{\"tableAttributes\":{\"1\":\"field:4\",\"2\":\"field:12\",\"3\":\"field:164\",\"4\":\"field:14\"}},\"section:5\":{\"tableAttributes\":{\"1\":\"field:7\",\"2\":\"field:10\"}},\"*\":{\"tableAttributes\":{\"1\":\"section\",\"2\":\"postDate\",\"3\":\"expiryDate\",\"4\":\"author\",\"5\":\"link\"}},\"section:10\":{\"tableAttributes\":{\"1\":\"postDate\",\"2\":\"type\",\"3\":\"field:67\",\"4\":\"field:27\",\"5\":\"field:29\",\"6\":\"field:33\",\"7\":\"author\"}},\"section:12\":{\"tableAttributes\":{\"1\":\"field:55\",\"2\":\"postDate\"}},\"section:6\":{\"tableAttributes\":{\"1\":\"field:2\",\"2\":\"field:166\",\"3\":\"author\",\"4\":\"postDate\",\"5\":\"field:28\"}},\"section:7\":{\"tableAttributes\":{\"1\":\"postDate\",\"2\":\"author\",\"3\":\"field:150\",\"4\":\"field:16\"}}},\"sourceOrder\":[[\"key\",\"*\"],[\"heading\",\"Channels\"],[\"key\",\"section:3\"],[\"key\",\"section:5\"],[\"heading\",\"\"],[\"key\",\"section:6\"],[\"key\",\"section:7\"],[\"heading\",\"\"],[\"key\",\"section:12\"],[\"key\",\"section:10\"],[\"heading\",\"\"],[\"key\",\"section:14\"],[\"key\",\"section:13\"]]}','2017-10-23 14:56:37','2019-02-11 10:32:32','46cff6c7-fc41-4f58-9737-edf547b8fca3'),(6,'User','{\"sources\":{\"group:4\":{\"tableAttributes\":{\"1\":\"fullName\",\"2\":\"field:3\",\"3\":\"field:128\",\"4\":\"email\"}},\"*\":{\"tableAttributes\":{\"1\":\"fullName\",\"2\":\"email\",\"3\":\"dateCreated\",\"4\":\"lastLoginDate\",\"5\":\"field:3\",\"6\":\"field:128\",\"7\":\"field:30\"}},\"group:2\":{\"tableAttributes\":{\"1\":\"fullName\",\"2\":\"email\",\"3\":\"dateCreated\",\"4\":\"lastLoginDate\",\"5\":\"field:193\"}}}}','2017-10-23 15:25:46','2019-02-11 13:46:48','2a6d741b-4629-46af-9ad5-9d3106f6639d'),(8,'Category','{\"sources\":{\"group:1\":{\"tableAttributes\":{\"1\":\"field:36\"}}}}','2018-02-14 12:55:14','2018-02-14 12:55:52','71a86f38-5618-4b32-9d3c-70106dab420d'),(10,'Asset','{\"sources\":{\"folder:1\":{\"tableAttributes\":{\"1\":\"filename\",\"2\":\"size\",\"3\":\"dateModified\",\"4\":\"id\"}}}}','2018-04-29 14:44:37','2018-04-29 14:44:37','d276cc4b-7e70-489d-be61-991d6b8d7980');
/*!40000 ALTER TABLE `craft_elementindexsettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_elements`
--

DROP TABLE IF EXISTS `craft_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `archived` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_elements_type_idx` (`type`),
  KEY `craft_elements_enabled_idx` (`enabled`),
  KEY `craft_elements_archived_dateCreated_idx` (`archived`,`dateCreated`)
) ENGINE=InnoDB AUTO_INCREMENT=3618 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_elements`
--

LOCK TABLES `craft_elements` WRITE;
/*!40000 ALTER TABLE `craft_elements` DISABLE KEYS */;
INSERT INTO `craft_elements` VALUES (1,'User',1,0,'2017-10-23 13:26:42','2019-01-24 10:36:07','524123d7-58d3-4156-b2e4-194bdb860e15'),(143,'User',1,0,'2018-03-10 15:10:18','2019-02-21 14:36:41','b712ebaf-3929-48e9-91e2-19c8cfe36a97'),(428,'Asset',1,0,'2018-04-23 10:42:09','2018-04-23 10:42:09','9fbc263e-e4a8-431a-8f26-6bec001f9768'),(430,'Asset',1,0,'2018-04-23 10:42:54','2018-04-23 10:42:54','4e3d427f-5b4a-4811-9309-b5d0c3b7447a'),(432,'Asset',1,0,'2018-04-23 10:53:59','2018-04-23 10:53:59','52a0cc5a-27bb-4c5e-8afc-8f829ecb9795'),(435,'Asset',1,0,'2018-04-23 10:55:59','2018-04-23 10:55:59','c55dca30-fcf3-4561-b8f2-7c9189afc4da'),(437,'Asset',1,0,'2018-04-23 10:56:54','2018-04-23 10:56:54','601cace6-d578-4e99-b0da-585a6992b9c4'),(440,'Asset',1,0,'2018-04-23 11:02:32','2018-04-23 11:02:32','6d3b0f51-8200-495f-b5d2-f220307008ca'),(443,'Asset',1,0,'2018-04-23 11:55:00','2018-04-23 11:55:00','70befe02-3555-4c8b-8031-7cf1ec7a88b1'),(446,'Asset',1,0,'2018-04-24 09:07:46','2018-04-24 09:07:46','05a8202c-58e2-41c5-9b46-193027b66827'),(448,'Asset',1,0,'2018-04-24 09:11:54','2018-04-24 09:11:54','2c4b76bc-0c1f-40e7-92d8-cd492ba313db'),(450,'Asset',1,0,'2018-04-24 09:12:23','2018-04-24 09:12:23','b91113e2-b34e-4be0-af18-271efb2de451'),(488,'GlobalSet',1,0,'2018-04-26 16:31:48','2019-02-21 14:20:06','3dbe078b-ac19-44ed-9bdf-f890372262c1'),(489,'GlobalSet',1,0,'2018-04-29 12:39:31','2018-09-13 15:49:59','b26030b8-7dc6-4258-9871-8591fd3e7d2b'),(497,'Asset',1,0,'2018-04-29 14:01:19','2018-04-29 14:01:19','edce154a-f2b3-4f01-8b52-8ede6f5a939c'),(1014,'Asset',1,0,'2018-06-08 11:32:15','2018-06-08 11:32:15','fa5009f1-dee4-4460-a1c4-7ce3c6f45e6a'),(1079,'GlobalSet',1,0,'2018-08-21 15:22:30','2019-02-21 14:36:13','ba35b6ec-9035-4058-9861-57675ce5a7cf'),(1086,'Entry',1,0,'2018-08-23 14:29:32','2018-12-07 10:17:14','b3b67af4-dd09-4294-807f-4da868ecb774'),(1087,'MatrixBlock',1,0,'2018-08-23 14:29:32','2018-12-07 10:17:14','972ce54c-e7d6-4d65-b157-992514c69a63'),(1088,'MatrixBlock',1,0,'2018-08-23 14:29:32','2018-12-07 10:17:14','cbf9c229-4b7e-47b1-8d58-7108bd63591e'),(1090,'Entry',1,0,'2018-08-23 14:30:31','2019-01-18 13:30:11','631d4811-6a36-4d7c-838c-5b92865702b6'),(1091,'MatrixBlock',1,0,'2018-08-23 14:37:44','2019-02-21 14:36:13','825b35c7-5427-4996-849b-69dbfc3e5cc4'),(1190,'Asset',1,0,'2018-09-11 12:03:11','2018-09-11 12:03:11','08301291-5802-42aa-be12-5db4779577a9'),(1200,'Asset',1,0,'2018-09-11 12:07:31','2018-09-11 12:07:31','26850877-4522-4c84-8ba2-60c2f4ae61a3'),(1269,'Asset',1,0,'2018-09-26 15:02:40','2018-09-26 15:02:40','e86fcf66-ba14-40cc-9355-26e6687c6155'),(1270,'Asset',1,0,'2018-09-26 15:03:18','2018-09-26 15:03:18','85f0cc13-064a-46ae-856c-cb0d77821673'),(1271,'Asset',1,0,'2018-09-26 15:03:20','2018-09-26 15:03:20','812ee6c8-df10-4566-9105-24ba72c9f7b2'),(1272,'Asset',1,0,'2018-09-26 15:03:22','2018-09-26 15:03:22','01aad2a1-440c-4a0d-8253-caf75da79179'),(1273,'Asset',1,0,'2018-09-26 15:03:23','2018-09-26 15:03:23','7dd64050-3e20-497a-82b7-984ddb406488'),(1423,'User',1,0,'2018-10-04 15:35:59','2018-10-15 11:33:23','909c7412-43e2-4158-bd60-def1f077e468'),(1438,'User',1,0,'2018-10-11 09:02:13','2018-12-11 10:59:05','d6e079ba-0c6c-449a-9970-53e41f187ba0'),(1440,'User',1,0,'2018-10-12 09:36:14','2018-10-12 09:36:14','9d9ee405-fd93-4c35-b756-0d0239e2e0f8'),(1535,'Asset',1,0,'2018-11-07 12:10:54','2018-11-07 12:10:54','8004aa12-754b-4230-b042-c6c9b489aef6'),(1537,'Asset',1,0,'2018-11-07 12:13:24','2018-11-07 12:13:24','22fe7790-84d0-4357-bd13-51474f18a7e9'),(1539,'Asset',1,0,'2018-11-07 12:15:03','2018-11-07 12:15:03','4245cd14-1328-45e3-a4ad-ac318eed9696'),(1541,'Asset',1,0,'2018-11-07 12:15:21','2018-11-07 12:15:21','fe99c208-9ef0-48e5-a207-fc1214b548ff'),(1543,'Asset',1,0,'2018-11-07 12:23:21','2018-11-07 12:23:21','ab4a9291-95f7-4b45-8cb2-0acd5ddf4440'),(1591,'Asset',1,0,'2018-11-15 12:21:06','2018-11-15 12:21:06','c1e4537d-1b4e-43de-a927-27c0683e6dbc'),(1595,'Entry',1,0,'2018-11-20 14:06:55','2018-11-23 15:46:13','26f8fe10-b554-4d4d-a6a3-25d7caa8718f'),(1620,'MatrixBlock',1,0,'2018-11-23 11:08:56','2019-01-18 13:30:11','a98716f2-71c5-4b4e-b823-35fa2ba37997'),(1678,'Asset',1,0,'2018-12-07 11:23:47','2018-12-07 11:23:47','9b9019dd-7aa7-4415-8d3c-292ae3d23fad'),(1682,'Asset',1,0,'2018-12-07 11:28:04','2018-12-07 11:28:04','900ef5cf-4991-4d9d-bfd1-238ab39bad6e'),(1687,'Asset',1,0,'2018-12-07 12:11:52','2018-12-07 12:11:52','357a0b01-24b7-4db6-9ec1-2f0b99388ad7'),(1762,'Category',1,0,'2018-12-07 13:43:58','2019-02-21 14:21:33','8fdec7b7-406d-4bd4-8fd5-fc74ba7abd66'),(1895,'Asset',1,0,'2018-12-10 08:39:08','2018-12-10 08:39:08','5e5d8d76-1653-4b2f-9dfa-c6347e7f78e2'),(1896,'Asset',1,0,'2018-12-10 08:39:09','2018-12-10 08:39:09','9cfcbc9f-26ef-4ca3-b8f5-9621a1ff27fa'),(1925,'Asset',1,0,'2018-12-10 09:27:51','2018-12-10 09:27:51','82fe526e-3aa0-476c-88fb-9764faf7dfe9'),(2040,'Asset',1,0,'2018-12-11 09:30:40','2018-12-11 09:30:40','a1b49509-907f-4622-96c3-ecb69db4cc91'),(2047,'Asset',1,0,'2018-12-11 09:35:56','2018-12-11 09:35:56','27fcca96-84e8-4a19-bff3-594a9c8bffb5'),(2058,'Asset',1,0,'2018-12-11 10:01:14','2018-12-11 10:01:14','98a3591b-1350-4d9e-881e-701e210fb683'),(2078,'Asset',1,0,'2018-12-11 10:36:19','2018-12-11 10:36:19','85daddf2-cf78-4625-aee6-ac519099af78'),(2089,'Asset',1,0,'2018-12-11 10:44:53','2018-12-11 10:44:53','5439aed2-c705-4679-b7f4-96b014dc778c'),(2269,'Asset',1,0,'2018-12-12 03:10:35','2018-12-12 03:10:35','3ab955c8-6002-4bfb-a721-7356c0585d5b'),(2271,'Asset',1,0,'2018-12-12 03:11:32','2018-12-12 03:11:32','9ebbf530-9a1f-4384-baa5-f3048452da83'),(2273,'Asset',1,0,'2018-12-12 03:13:09','2018-12-12 03:13:09','4879608f-530d-4044-a9d2-83abc9cf2dd6'),(2275,'Asset',1,0,'2018-12-12 05:36:14','2018-12-12 05:36:14','f3e75901-9478-40b5-9da2-24f445012591'),(2277,'Asset',1,0,'2018-12-12 05:36:30','2018-12-12 05:36:30','36063254-670e-44e6-a332-da9ce8a75767'),(2279,'Asset',1,0,'2018-12-12 05:42:06','2018-12-12 05:42:06','5ae906ab-436b-41e5-a9dc-e99615e065ab'),(2281,'Asset',1,0,'2018-12-12 05:55:58','2018-12-12 05:55:58','444902a1-1338-4684-b181-125897c772ea'),(2283,'Asset',1,0,'2018-12-12 05:56:25','2018-12-12 05:56:25','16982ded-8df2-4901-9a5b-c08b9a453ee7'),(2285,'Asset',1,0,'2018-12-12 06:41:34','2018-12-12 06:41:34','f7487f78-8d0e-4d7e-b48f-29b22f41e53c'),(2287,'Asset',1,0,'2018-12-12 06:44:51','2018-12-12 06:44:51','cb1c43ba-5158-40d5-a400-59d8fed108cf'),(2289,'Asset',1,0,'2018-12-12 06:46:59','2018-12-12 06:46:59','7dc2a6c9-123b-4c8d-9a5c-07c68eb56de1'),(2291,'Asset',1,0,'2018-12-12 06:47:10','2018-12-12 06:47:10','fcb1e53d-de25-4842-889c-5fd0bc3481ae'),(2315,'Asset',1,0,'2018-12-12 07:04:05','2018-12-12 07:04:05','03a7bb8f-0a21-4676-9e18-e9188788ee3b'),(2485,'MatrixBlock',1,0,'2019-01-16 11:52:08','2019-01-24 10:36:07','7b08bc50-d1ed-47cb-b41c-21b3b4a95e84'),(2528,'SuperTable_Block',1,0,'2019-01-21 16:04:37','2019-02-21 14:36:13','e246513f-bdcf-4e93-bac7-c55801b05972'),(2529,'SuperTable_Block',1,0,'2019-01-21 16:04:37','2019-02-21 14:36:13','3d0558a9-b090-422e-9eb5-683ba7a157db'),(2530,'SuperTable_Block',1,0,'2019-01-21 16:04:37','2019-02-21 14:36:13','e9c9adbe-00b2-4d8f-b620-39150aad9377'),(2531,'SuperTable_Block',1,0,'2019-01-21 16:04:37','2019-02-21 14:36:13','4d983628-00ca-45d1-b478-979bd093e255'),(2541,'SuperTable_Block',1,0,'2019-01-21 19:11:24','2019-01-21 19:11:24','37d5f51d-a3da-4ac7-8476-b078b721d2ed'),(2542,'SuperTable_Block',1,0,'2019-01-21 19:11:24','2019-01-21 19:11:24','eda83d38-c745-4c61-90e3-69f1e10813c4'),(2543,'SuperTable_Block',1,0,'2019-01-21 19:11:29','2019-01-21 19:11:29','0985504f-e5ed-4814-9668-9d6e32d05751'),(2544,'SuperTable_Block',1,0,'2019-01-21 19:11:30','2019-01-21 19:11:30','73cee2a0-a55a-4929-90a7-a828d4f10736'),(2546,'SuperTable_Block',1,0,'2019-01-21 19:15:01','2019-01-21 19:15:01','6f206787-7e5b-49e2-9063-730ef7f8dc12'),(2547,'SuperTable_Block',1,0,'2019-01-21 19:15:01','2019-01-21 19:15:01','6622045c-105b-438f-8f26-1ea161274d79'),(2548,'SuperTable_Block',1,0,'2019-01-21 19:18:23','2019-01-21 19:18:23','e73ea490-acfd-4ead-8cd3-eced4cb47d1a'),(2549,'SuperTable_Block',1,0,'2019-01-21 19:18:23','2019-01-21 19:18:23','1526203e-b6bc-47e1-b208-6811fd504dd0'),(3124,'SuperTable_Block',1,0,'2019-01-21 19:38:11','2019-01-21 19:38:11','7418a0b9-2e76-467e-bc32-e9cc720951eb'),(3125,'SuperTable_Block',1,0,'2019-01-21 19:38:17','2019-01-21 19:38:17','5bca0125-9344-46d5-a2a5-61a28150d49a'),(3126,'SuperTable_Block',1,0,'2019-01-21 19:40:53','2019-01-21 19:40:53','da6323ff-e852-41b5-bb2f-f7e746f46f29'),(3127,'SuperTable_Block',1,0,'2019-01-21 19:41:04','2019-01-21 19:41:04','67d6c6dd-05fb-4d05-9705-6f4204bd37ef'),(3169,'SuperTable_Block',1,0,'2019-01-24 14:15:25','2019-02-21 14:36:13','3433069a-900d-45e2-bf51-48e997706c35'),(3183,'SuperTable_Block',1,0,'2019-01-25 11:43:18','2019-02-21 14:36:13','05bd23d7-8fe0-445b-9564-8823d7365759'),(3231,'Asset',1,0,'2019-01-29 09:40:47','2019-01-29 09:40:47','3e3da0b6-bcb1-4a70-bad1-0f97c3b93cca'),(3239,'Asset',1,0,'2019-01-29 09:56:46','2019-01-29 09:56:46','44639a11-3787-4712-bcba-4f5e76524239'),(3255,'Asset',1,0,'2019-01-29 11:10:03','2019-01-29 11:10:03','b033ba0f-6e71-474d-9fa6-5a92091d2536'),(3266,'GlobalSet',1,0,'2019-02-01 09:43:26','2019-02-21 14:21:11','fb40dd6d-0260-492a-a51a-0444dd3494b5'),(3267,'MatrixBlock',1,0,'2019-02-01 09:51:20','2019-02-01 09:51:20','2b07466b-0191-4015-967b-af7ec51b5acf');
/*!40000 ALTER TABLE `craft_elements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_elements_i18n`
--

DROP TABLE IF EXISTS `craft_elements_i18n`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_elements_i18n` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_elements_i18n_elementId_locale_unq_idx` (`elementId`,`locale`),
  UNIQUE KEY `craft_elements_i18n_uri_locale_unq_idx` (`uri`,`locale`),
  KEY `craft_elements_i18n_slug_locale_idx` (`slug`,`locale`),
  KEY `craft_elements_i18n_enabled_idx` (`enabled`),
  KEY `craft_elements_i18n_locale_fk` (`locale`),
  CONSTRAINT `craft_elements_i18n_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_elements_i18n_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3617 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_elements_i18n`
--

LOCK TABLES `craft_elements_i18n` WRITE;
/*!40000 ALTER TABLE `craft_elements_i18n` DISABLE KEYS */;
INSERT INTO `craft_elements_i18n` VALUES (1,1,'en_gb','',NULL,1,'2017-10-23 13:26:42','2019-01-24 10:36:07','55278b20-342c-40a8-b2b7-e25032c0539c'),(143,143,'en_gb','',NULL,1,'2018-03-10 15:10:18','2019-02-21 14:36:41','cff619e3-294f-44e5-a532-017c766df960'),(428,428,'en_gb','evidence',NULL,1,'2018-04-23 10:42:09','2018-04-23 10:42:09','ee28c735-9842-45c0-be0e-0947e5707b94'),(430,430,'en_gb','evidence',NULL,1,'2018-04-23 10:42:54','2018-04-23 10:42:54','87239c12-2058-48a3-8dd7-99f04e4523d7'),(432,432,'en_gb','evidence',NULL,1,'2018-04-23 10:53:59','2018-04-23 10:53:59','63f3920e-3ddd-4334-9157-cb39602ff40f'),(435,435,'en_gb','evidence',NULL,1,'2018-04-23 10:55:59','2018-04-23 10:55:59','d9c8e3a1-f99b-43f7-b6bd-a9a66bc6ee58'),(437,437,'en_gb','evidence',NULL,1,'2018-04-23 10:56:54','2018-04-23 10:56:54','b567e30a-0cd1-4c7f-a0d2-136fbebf646b'),(440,440,'en_gb','evidence',NULL,1,'2018-04-23 11:02:32','2018-04-23 11:02:32','31e815c7-88a9-4ac1-8e90-2bc09f1d7bfc'),(443,443,'en_gb','evidence',NULL,1,'2018-04-23 11:55:00','2018-04-23 11:55:00','4b30da7b-5d2b-49dd-b369-9558fad7c1cd'),(446,446,'en_gb','evidence',NULL,1,'2018-04-24 09:07:47','2018-04-24 09:07:47','cab5c199-38bc-4574-bbf0-61def86f9729'),(448,448,'en_gb','evidence',NULL,1,'2018-04-24 09:11:55','2018-04-24 09:11:55','77fc99f9-2057-427d-8e89-1a324eee6568'),(450,450,'en_gb','evidence',NULL,1,'2018-04-24 09:12:24','2018-04-24 09:12:24','f0c68b3d-1b24-483c-ad3b-7c6c08b8cd03'),(488,488,'en_gb','',NULL,1,'2018-04-26 16:31:48','2019-02-21 14:20:06','c7ce7030-bc33-4088-8e14-67b1159432a1'),(489,489,'en_gb','',NULL,1,'2018-04-29 12:39:32','2018-09-13 15:49:59','65075589-0e0d-4db0-aca4-dbc0a5256d54'),(497,497,'en_gb','evidence',NULL,1,'2018-04-29 14:01:19','2018-04-29 14:01:19','ab92c7a8-12e7-447e-a680-fd0e27f5f752'),(1014,1014,'en_gb','koala',NULL,1,'2018-06-08 11:32:15','2018-06-08 11:32:15','afd84ab4-f352-45a4-a5cf-e860186980f6'),(1079,1079,'en_gb','',NULL,1,'2018-08-21 15:22:30','2019-02-21 14:36:13','d1439197-cc47-4ed9-a2d0-14c6ae27102f'),(1086,1086,'en_gb','about-lantra','public/about-lantra',1,'2018-08-23 14:29:32','2018-12-07 10:17:14','642c9c51-3c70-4cbb-9a0e-b5ffb21e9628'),(1087,1087,'en_gb','',NULL,1,'2018-08-23 14:29:32','2018-12-07 10:17:14','b03da23a-d8c6-401b-87bd-22cea0888ed1'),(1088,1088,'en_gb','',NULL,1,'2018-08-23 14:29:32','2018-12-07 10:17:14','ccec872f-81c9-4c6f-a4b4-dc432de02824'),(1090,1090,'en_gb','contact-us','public/contact-us',1,'2018-08-23 14:30:31','2019-01-18 13:30:11','a35d938c-83b1-444f-840c-a863b3abedfe'),(1091,1091,'en_gb','',NULL,1,'2018-08-23 14:37:44','2019-02-21 14:36:13','1fc06123-1d59-4b7d-bfd9-4d2625c83eed'),(1190,1190,'en_gb','unit',NULL,1,'2018-09-11 12:03:11','2018-09-11 12:03:11','0bb82439-3b05-4deb-b107-b1f619de89c1'),(1200,1200,'en_gb','unit',NULL,1,'2018-09-11 12:07:31','2018-09-11 12:07:31','94c33fda-95ff-484c-931d-35085ca3429c'),(1269,1269,'en_gb','lantra-awards-logo',NULL,1,'2018-09-26 15:02:40','2018-09-26 15:02:40','1a633da2-4f6a-4eda-aadf-215b966c250a'),(1270,1270,'en_gb','bground-img-1',NULL,1,'2018-09-26 15:03:18','2018-09-26 15:03:18','78c4cfe2-6ea6-48c0-a93a-0bebe776b142'),(1271,1271,'en_gb','bground-img-2',NULL,1,'2018-09-26 15:03:20','2018-09-26 15:03:20','7b5e9190-28e3-4bed-b3a8-7ede42b19650'),(1272,1272,'en_gb','bground-img-3',NULL,1,'2018-09-26 15:03:22','2018-09-26 15:03:22','76b1c220-75a5-4b0a-a0ed-3014d1141718'),(1273,1273,'en_gb','bground-img-4',NULL,1,'2018-09-26 15:03:23','2018-09-26 15:03:23','56ff6b4e-eac6-4c65-ae4f-453a0851ac6b'),(1423,1423,'en_gb','',NULL,1,'2018-10-04 15:36:00','2018-10-15 11:33:23','f58da1d3-427d-4ea4-b042-25f0d2df682c'),(1438,1438,'en_gb','',NULL,1,'2018-10-11 09:02:13','2018-12-11 10:59:05','762b4f31-5411-42b5-a423-984534f73068'),(1440,1440,'en_gb','',NULL,1,'2018-10-12 09:36:14','2018-10-12 09:36:14','4bcecb3e-6df0-4652-81ce-c59c2537e8a6'),(1535,1535,'en_gb','example-evidence',NULL,1,'2018-11-07 12:10:54','2018-11-07 12:10:54','40ffc3e9-59a5-4bd9-aab0-82e289cd8ca5'),(1537,1537,'en_gb','example-evidence',NULL,1,'2018-11-07 12:13:24','2018-11-07 12:13:24','48cdae32-23ec-4613-ac84-5d8760a0242b'),(1539,1539,'en_gb','example-evidence',NULL,1,'2018-11-07 12:15:03','2018-11-07 12:15:03','76edce40-a748-4a0a-92ad-5b3c11908745'),(1541,1541,'en_gb','example-evidence',NULL,1,'2018-11-07 12:15:21','2018-11-07 12:15:21','bec60166-c481-4cab-8fff-b668dc967dfd'),(1543,1543,'en_gb','example-evidence',NULL,1,'2018-11-07 12:23:21','2018-11-07 12:23:21','066a6a17-bff9-46ab-ad95-a9429d7dcc8b'),(1591,1591,'en_gb','about-us',NULL,1,'2018-11-15 12:21:06','2018-11-15 12:21:06','aa1b3e35-74c5-4111-9ab7-2adc494250f2'),(1595,1595,'en_gb','faqs','public/faqs',1,'2018-11-20 14:06:55','2018-11-23 15:46:13','83cd20e5-be70-4d93-aace-b3fd7d8ee8de'),(1620,1620,'en_gb','',NULL,1,'2018-11-23 11:08:56','2019-01-18 13:30:12','9262bb0c-b3de-4eec-ae9f-f92d29541051'),(1678,1678,'en_gb','koala',NULL,1,'2018-12-07 11:23:47','2018-12-07 11:23:47','ec227f5f-8aca-451e-a9f2-8291e4020a0d'),(1682,1682,'en_gb','koala',NULL,1,'2018-12-07 11:28:04','2018-12-07 11:28:04','fb1dc075-f67d-4ed3-97b9-fe2753984d94'),(1687,1687,'en_gb','desert',NULL,1,'2018-12-07 12:11:52','2018-12-07 12:11:52','80275b07-e379-42d9-a993-467370c53f0d'),(1762,1762,'en_gb','site-admin',NULL,1,'2018-12-07 13:43:58','2019-02-21 14:21:33','4801c135-4662-4c9b-8037-0842cfaacf26'),(1895,1895,'en_gb','example-evidence',NULL,1,'2018-12-10 08:39:09','2018-12-10 08:39:09','69f48b2f-0724-4eee-95d0-792776818121'),(1896,1896,'en_gb','example-evidence',NULL,1,'2018-12-10 08:39:09','2018-12-10 08:39:09','fc58a3a9-7c7f-4da4-b0f2-7bda30f202e7'),(1925,1925,'en_gb','dairy-milk',NULL,1,'2018-12-10 09:27:51','2018-12-10 09:27:51','6ed700f3-f6c0-433c-984b-70b118c2590f'),(2040,2040,'en_gb','current-draft',NULL,1,'2018-12-11 09:30:40','2018-12-11 09:30:40','fa469a83-7510-43ee-aa65-80b3797e4d22'),(2047,2047,'en_gb','unit',NULL,1,'2018-12-11 09:35:56','2018-12-11 09:35:56','45e0289b-fb16-4394-842d-835fab887c08'),(2058,2058,'en_gb','unit',NULL,1,'2018-12-11 10:01:14','2018-12-11 10:01:14','97cdf0b6-c237-40ab-b7ab-c999b9916b9b'),(2078,2078,'en_gb','desert',NULL,1,'2018-12-11 10:36:19','2018-12-11 10:36:19','9b4a8123-fe4d-475f-ae0b-8c1fe01a7cd1'),(2089,2089,'en_gb','lantra-logo',NULL,1,'2018-12-11 10:44:53','2018-12-11 10:44:53','14c880a6-4a75-4b72-96c7-95241a73c79b'),(2269,2269,'en_gb','unit',NULL,1,'2018-12-12 03:10:35','2018-12-12 03:10:35','fab83a51-f5f5-4ea8-bcc9-bfcdea847431'),(2271,2271,'en_gb','unit',NULL,1,'2018-12-12 03:11:32','2018-12-12 03:11:32','22ada2cd-6c0b-4996-b082-b901d7eaba40'),(2273,2273,'en_gb','unit',NULL,1,'2018-12-12 03:13:09','2018-12-12 03:13:09','d7f96ba4-2355-4e57-a318-de214a3a0d5a'),(2275,2275,'en_gb','unit',NULL,1,'2018-12-12 05:36:14','2018-12-12 05:36:14','b1b0de79-c9f3-4655-8f38-811beb3508da'),(2277,2277,'en_gb','unit',NULL,1,'2018-12-12 05:36:30','2018-12-12 05:36:30','6115fae0-9449-48d3-a03b-1a71eb6d4b8b'),(2279,2279,'en_gb','unit',NULL,1,'2018-12-12 05:42:06','2018-12-12 05:42:06','2d3ee5cb-9543-4e77-8b35-f8b54a530c4b'),(2281,2281,'en_gb','unit',NULL,1,'2018-12-12 05:55:58','2018-12-12 05:55:58','ef131920-5fc2-4396-9eeb-745b8fd1b54d'),(2283,2283,'en_gb','unit',NULL,1,'2018-12-12 05:56:25','2018-12-12 05:56:25','fb6c7045-f441-4733-96f7-2a2bf17f8f79'),(2285,2285,'en_gb','unit',NULL,1,'2018-12-12 06:41:34','2018-12-12 06:41:34','9f51d1aa-b564-4c9c-a0d5-3454f854bede'),(2287,2287,'en_gb','unit',NULL,1,'2018-12-12 06:44:51','2018-12-12 06:44:51','7cb9e309-7f94-42ad-9104-492039d84e6d'),(2289,2289,'en_gb','unit',NULL,1,'2018-12-12 06:46:59','2018-12-12 06:46:59','d56c070b-95d1-4be3-a7a0-caf818074c71'),(2291,2291,'en_gb','unit',NULL,1,'2018-12-12 06:47:10','2018-12-12 06:47:10','61833e30-afb3-4aac-af50-b1a79e965e03'),(2315,2315,'en_gb','unit',NULL,1,'2018-12-12 07:04:05','2018-12-12 07:04:05','d28f81f3-19de-423a-937c-313d0d7627da'),(2485,2485,'en_gb','',NULL,1,'2019-01-16 11:52:08','2019-01-24 10:36:07','af7bba1c-68f4-4880-a750-28ca8cd2ac23'),(2528,2528,'en_gb','',NULL,1,'2019-01-21 16:04:37','2019-02-21 14:36:13','75dcef41-6d14-4eb2-9fa7-36ac318b69d6'),(2529,2529,'en_gb','',NULL,1,'2019-01-21 16:04:37','2019-02-21 14:36:13','8949a76a-c79c-4bae-a5c7-e436ea48df31'),(2530,2530,'en_gb','',NULL,1,'2019-01-21 16:04:37','2019-02-21 14:36:13','0d23bbba-69af-4edf-a30f-7b8b3efda98d'),(2531,2531,'en_gb','',NULL,1,'2019-01-21 16:04:37','2019-02-21 14:36:13','4c1e828a-53bb-41dd-86d0-50c28f369b92'),(2541,2541,'en_gb','',NULL,1,'2019-01-21 19:11:24','2019-01-21 19:11:24','6b1756c0-ace3-4199-a958-f8b0dc22c8f3'),(2542,2542,'en_gb','',NULL,1,'2019-01-21 19:11:24','2019-01-21 19:11:24','c9a29af1-6573-49f1-92c8-280458445044'),(2543,2543,'en_gb','',NULL,1,'2019-01-21 19:11:29','2019-01-21 19:11:29','c7cfbc7d-a14a-403b-ac5e-460e7660ed88'),(2544,2544,'en_gb','',NULL,1,'2019-01-21 19:11:30','2019-01-21 19:11:30','46168c71-4e8d-4e8a-bde4-b202dfac1f4d'),(2546,2546,'en_gb','',NULL,1,'2019-01-21 19:15:01','2019-01-21 19:15:01','709840ac-4895-45a8-9662-d143a4527e9b'),(2547,2547,'en_gb','',NULL,1,'2019-01-21 19:15:01','2019-01-21 19:15:01','0b97f8f5-6d8e-4442-9d1a-dd82b2c25958'),(2548,2548,'en_gb','',NULL,1,'2019-01-21 19:18:23','2019-01-21 19:18:23','3d3606ba-230b-4771-a8de-1545e3211f2b'),(2549,2549,'en_gb','',NULL,1,'2019-01-21 19:18:23','2019-01-21 19:18:23','648219d4-2378-42ac-9f53-63c737bc1947'),(3123,3124,'en_gb','',NULL,1,'2019-01-21 19:38:11','2019-01-21 19:38:11','2049fca9-7fff-4087-b51c-a8802d9c7cc0'),(3124,3125,'en_gb','',NULL,1,'2019-01-21 19:38:17','2019-01-21 19:38:17','8a3e86f5-bb5e-42b6-98ab-8bc7042b4524'),(3125,3126,'en_gb','',NULL,1,'2019-01-21 19:40:53','2019-01-21 19:40:53','704e4102-40ba-4a1a-a37f-123c80537c59'),(3126,3127,'en_gb','',NULL,1,'2019-01-21 19:41:04','2019-01-21 19:41:04','8033c94d-4bb5-4c79-b9ee-2fbe3eafb96b'),(3168,3169,'en_gb','',NULL,1,'2019-01-24 14:15:25','2019-02-21 14:36:13','d8661ff1-04d6-4a11-a6d5-2ab31edaef1f'),(3182,3183,'en_gb','',NULL,1,'2019-01-25 11:43:18','2019-02-21 14:36:13','c6675d9c-1d86-412b-bb4b-18d5c518fcf7'),(3230,3231,'en_gb','request-for-pp-sales-invoice',NULL,1,'2019-01-29 09:40:47','2019-01-29 09:40:47','68c4dc2e-88ac-427f-a5a2-dd72d92ece26'),(3238,3239,'en_gb','request-for-ltp-sales-invoice',NULL,1,'2019-01-29 09:56:46','2019-01-29 09:56:46','999ec0d0-d3b7-4869-97c7-a54888dec0f5'),(3254,3255,'en_gb','request-for-ptr-sales-invoice',NULL,1,'2019-01-29 11:10:03','2019-01-29 11:10:03','70d307fe-257c-45be-8733-6deb2ea21c87'),(3265,3266,'en_gb','',NULL,1,'2019-02-01 09:43:26','2019-02-21 14:21:11','e70c4204-b13e-43a6-b9f8-afae02b1bcaa'),(3266,3267,'en_gb','',NULL,1,'2019-02-01 09:51:20','2019-02-01 09:51:20','95b7fde9-d817-431f-b4cf-11885e2f3024');
/*!40000 ALTER TABLE `craft_elements_i18n` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_emailmessages`
--

DROP TABLE IF EXISTS `craft_emailmessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_emailmessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` char(150) COLLATE utf8_unicode_ci NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_emailmessages_key_locale_unq_idx` (`key`,`locale`),
  KEY `craft_emailmessages_locale_fk` (`locale`),
  CONSTRAINT `craft_emailmessages_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_emailmessages`
--

LOCK TABLES `craft_emailmessages` WRITE;
/*!40000 ALTER TABLE `craft_emailmessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_emailmessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_entries`
--

DROP TABLE IF EXISTS `craft_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_entries` (
  `id` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `typeId` int(11) DEFAULT NULL,
  `authorId` int(11) DEFAULT NULL,
  `postDate` datetime DEFAULT NULL,
  `expiryDate` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_entries_sectionId_idx` (`sectionId`),
  KEY `craft_entries_typeId_idx` (`typeId`),
  KEY `craft_entries_postDate_idx` (`postDate`),
  KEY `craft_entries_expiryDate_idx` (`expiryDate`),
  KEY `craft_entries_authorId_fk` (`authorId`),
  CONSTRAINT `craft_entries_authorId_fk` FOREIGN KEY (`authorId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_entries_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_entries_sectionId_fk` FOREIGN KEY (`sectionId`) REFERENCES `craft_sections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_entries_typeId_fk` FOREIGN KEY (`typeId`) REFERENCES `craft_entrytypes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_entries`
--

LOCK TABLES `craft_entries` WRITE;
/*!40000 ALTER TABLE `craft_entries` DISABLE KEYS */;
INSERT INTO `craft_entries` VALUES (1086,14,16,1,'2018-08-23 14:29:00',NULL,'2018-08-23 14:29:32','2018-12-07 10:17:14','d84d3134-dad4-4daf-b1fd-00003773bb47'),(1090,14,16,1,'2018-08-23 14:30:00',NULL,'2018-08-23 14:30:31','2019-01-18 13:30:12','763592ea-f057-4189-8ff1-599684ac000a'),(1595,14,16,1,'2018-11-20 14:06:00',NULL,'2018-11-20 14:06:56','2018-11-23 15:46:13','b59ecc66-c634-4d48-a935-015f20c611ba');
/*!40000 ALTER TABLE `craft_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_entrydrafts`
--

DROP TABLE IF EXISTS `craft_entrydrafts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_entrydrafts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entryId` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `creatorId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notes` tinytext COLLATE utf8_unicode_ci,
  `data` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_entrydrafts_entryId_locale_idx` (`entryId`,`locale`),
  KEY `craft_entrydrafts_sectionId_fk` (`sectionId`),
  KEY `craft_entrydrafts_creatorId_fk` (`creatorId`),
  KEY `craft_entrydrafts_locale_fk` (`locale`),
  CONSTRAINT `craft_entrydrafts_creatorId_fk` FOREIGN KEY (`creatorId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_entrydrafts_entryId_fk` FOREIGN KEY (`entryId`) REFERENCES `craft_entries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_entrydrafts_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `craft_entrydrafts_sectionId_fk` FOREIGN KEY (`sectionId`) REFERENCES `craft_sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_entrydrafts`
--

LOCK TABLES `craft_entrydrafts` WRITE;
/*!40000 ALTER TABLE `craft_entrydrafts` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_entrydrafts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_entrytypes`
--

DROP TABLE IF EXISTS `craft_entrytypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_entrytypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sectionId` int(11) NOT NULL,
  `fieldLayoutId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hasTitleField` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `titleLabel` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Title',
  `titleFormat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_entrytypes_name_sectionId_unq_idx` (`name`,`sectionId`),
  UNIQUE KEY `craft_entrytypes_handle_sectionId_unq_idx` (`handle`,`sectionId`),
  KEY `craft_entrytypes_sectionId_fk` (`sectionId`),
  KEY `craft_entrytypes_fieldLayoutId_fk` (`fieldLayoutId`),
  CONSTRAINT `craft_entrytypes_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `craft_entrytypes_sectionId_fk` FOREIGN KEY (`sectionId`) REFERENCES `craft_sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_entrytypes`
--

LOCK TABLES `craft_entrytypes` WRITE;
/*!40000 ALTER TABLE `craft_entrytypes` DISABLE KEYS */;
INSERT INTO `craft_entrytypes` VALUES (3,3,231,'Company','company',1,'Title',NULL,1,'2017-10-23 13:43:33','2018-10-16 13:49:35','2fbdc981-206f-44f9-9f39-fdb605cff8f2'),(5,5,71,'Team','team',0,NULL,'{teamCompany.first.title} - {teamName}',1,'2017-10-23 14:46:37','2018-03-07 14:02:01','ed2a03c5-bea9-4bed-8fe2-2a9e97abb03e'),(6,6,269,'Module','module',1,'Title',NULL,1,'2017-10-24 09:39:33','2019-01-21 17:37:44','dbd7f9dd-1424-48cc-8aba-f0533bce83be'),(7,7,282,'Unit','unit',1,'Title',NULL,1,'2017-10-24 09:45:57','2019-02-11 12:37:37','6bbfb167-5ead-4bd3-971d-7f756a5b4c8b'),(10,10,298,'Unit Result','unitResult',0,NULL,'[unit {resultUnit.first.id}] {author.firstName} {author.lastName}',1,'2018-02-14 12:41:38','2019-04-25 13:26:07','3bf342f8-6a3e-465e-b665-d25d1860be54'),(12,12,122,'Attempt','attempt',0,NULL,'[unit {attemptUnit.first.id}] {author.firstName} {author.lastName}',1,'2018-04-13 10:12:01','2018-04-19 14:53:29','39d4cfb6-0d4f-496d-82ca-64d29f904a62'),(14,10,299,'Module Result','moduleResult',0,NULL,'[module {resultModule.first().id}] {author.firstName} {author.lastName} ',2,'2018-04-23 10:05:23','2019-04-25 13:26:07','abaff843-e57c-4cfa-bab2-3f4927f556d3'),(15,13,165,'Reports','reports',1,'Title',NULL,1,'2018-08-13 12:10:21','2018-08-20 15:36:56','6aaa6123-97b5-479c-8de3-488d8813933e'),(16,14,258,'Pages','pages',1,'Title',NULL,1,'2018-08-21 13:08:51','2018-12-12 00:07:57','006ef470-d860-40ff-a431-e2515805f2e8'),(17,10,300,'User Result','userResult',1,'',NULL,3,'2018-08-28 13:07:54','2019-04-25 13:26:07','e34f85df-0855-47ca-a101-0519afac3c0a');
/*!40000 ALTER TABLE `craft_entrytypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_entryversions`
--

DROP TABLE IF EXISTS `craft_entryversions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_entryversions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entryId` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `creatorId` int(11) DEFAULT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `num` smallint(6) unsigned NOT NULL,
  `notes` tinytext COLLATE utf8_unicode_ci,
  `data` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_entryversions_entryId_locale_idx` (`entryId`,`locale`),
  KEY `craft_entryversions_sectionId_fk` (`sectionId`),
  KEY `craft_entryversions_creatorId_fk` (`creatorId`),
  KEY `craft_entryversions_locale_fk` (`locale`),
  CONSTRAINT `craft_entryversions_creatorId_fk` FOREIGN KEY (`creatorId`) REFERENCES `craft_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `craft_entryversions_entryId_fk` FOREIGN KEY (`entryId`) REFERENCES `craft_entries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_entryversions_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `craft_entryversions_sectionId_fk` FOREIGN KEY (`sectionId`) REFERENCES `craft_sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2076 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_entryversions`
--

LOCK TABLES `craft_entryversions` WRITE;
/*!40000 ALTER TABLE `craft_entryversions` DISABLE KEYS */;
INSERT INTO `craft_entryversions` VALUES (489,1086,14,143,'en_gb',1,'','{\"typeId\":null,\"authorId\":\"143\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034572,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget.Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"An established and trusted provider of online solutions for Local Government that help you work better, do more and save money.\",\"115\":[\"1084\"]}}','2018-08-23 14:29:32','2018-08-23 14:29:32','e81ae74f-7801-46e3-a85c-d0ae7a3fa9b7'),(490,1090,14,143,'en_gb',1,'','{\"typeId\":null,\"authorId\":\"143\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034631,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"<p>[CONTACT US CONTENT]<\\/p>\",\"112\":[],\"2\":\"An established and trusted provider of online solutions for Local Government that help you work better, do more and save money.\",\"115\":\"\"}}','2018-08-23 14:30:31','2018-08-23 14:30:31','51a98672-ffda-4a88-84ab-59520a3fc6e7'),(583,1090,14,143,'en_gb',2,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-09-26 14:44:04','2018-09-26 14:44:04','33ef22fe-1d72-4b94-9f3f-826507eab87a'),(584,1086,14,143,'en_gb',2,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"\",\"115\":[\"1084\"]}}','2018-09-26 14:44:27','2018-09-26 14:44:27','10078807-1287-4423-9a16-b634dfc41311'),(585,1086,14,1,'en_gb',3,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"<p>cgfhfgh<\\/p>\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"\",\"115\":\"\"}}','2018-11-15 12:19:36','2018-11-15 12:19:36','b819be2d-d775-4eaf-9429-d2da57377ae5'),(586,1086,14,1,'en_gb',4,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"fsdfdssf\",\"115\":\"\"}}','2018-11-15 12:19:59','2018-11-15 12:19:59','68ce0b12-f456-4655-bf2b-0b80911896e2'),(587,1086,14,1,'en_gb',5,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"\",\"115\":[\"1591\"]}}','2018-11-15 12:21:10','2018-11-15 12:21:10','003e1c06-7382-4db5-92b9-6f63cb414505'),(588,1086,14,1,'en_gb',6,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-15 12:21:55','2018-11-15 12:21:55','51be70e9-001b-4470-9d6a-24d5779cf385'),(589,1595,14,1,'en_gb',1,'','{\"typeId\":null,\"authorId\":\"1\",\"title\":\"Terms and Privacy Policy\",\"slug\":\"terms-and-privacy-policy\",\"postDate\":1542722815,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-11-20 14:06:56','2018-11-20 14:06:56','ace560c0-6b34-404f-ae2d-73cdabb4edd5'),(590,1595,14,1,'en_gb',2,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Terms and Privacy Policy\",\"slug\":\"terms-and-privacy-policy\",\"postDate\":1542722760,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-11-20 14:07:33','2018-11-20 14:07:33','419ad77c-8127-45fa-9be9-28e285310c36'),(591,1086,14,1,'en_gb',7,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Uss\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-20 14:08:17','2018-11-20 14:08:17','a4a41c45-af0f-4119-8ac5-7542ae3c35a6'),(592,1086,14,1,'en_gb',8,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-20 14:08:27','2018-11-20 14:08:27','3cb23103-eb08-410c-9170-a49e2b485a1f'),(593,1595,14,1,'en_gb',3,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Terms and Privacy Policy\",\"slug\":\"terms-and-privacy-policy\",\"postDate\":1542722760,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1596\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Terms of use\",\"columnHtml\":\"\"}},\"1597\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Privacy Policy\",\"columnHtml\":\"\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":\"\"}}','2018-11-20 14:09:10','2018-11-20 14:09:10','090454d6-6819-4230-999b-9ac0e3331f12'),(619,1090,14,1,'en_gb',3,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":\"\"}}','2018-11-23 11:05:54','2018-11-23 11:05:54','b32f768c-4ceb-450d-ba39-6c067897a29e'),(620,1090,14,1,'en_gb',4,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":\"\"}}','2018-11-23 11:08:56','2018-11-23 11:08:56','f4e52587-cd65-4718-9d44-1cfd667a157b'),(621,1086,14,1,'en_gb',9,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-23 11:34:29','2018-11-23 11:34:29','f41f359f-a41f-46f6-866f-45438ec7430b'),(622,1086,14,1,'en_gb',10,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Text<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Text<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-23 11:35:08','2018-11-23 11:35:08','5e13bb5f-190d-428e-8ac3-6046bba28b3c'),(623,1086,14,1,'en_gb',11,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Text<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Text<\\/p>\"}},\"1621\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<pre>\\r\\n&lt;div class=\\\"content-col left\\\"&gt;\\r\\n        &lt;h2&gt;Supportive&lt;\\/h2&gt;\\r\\n        &lt;p&gt;&lt;\\/p&gt;&lt;p&gt;Text&lt;\\/p&gt;&lt;p&gt;&lt;\\/p&gt;\\r\\n&lt;\\/div&gt;<\\/pre>\"}},\"1622\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<pre>\\r\\n&lt;div class=\\\"content-col right\\\"&gt;\\r\\n        &lt;h2&gt;Supportive&lt;\\/h2&gt;\\r\\n        &lt;p&gt;&lt;\\/p&gt;&lt;p&gt;Text&lt;\\/p&gt;&lt;p&gt;&lt;\\/p&gt;\\r\\n&lt;\\/div&gt;<\\/pre>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-23 14:16:19','2018-11-23 14:16:19','d81c89f9-8d08-47cd-bd74-d6f104ae095b'),(624,1086,14,1,'en_gb',12,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Text<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Text<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-23 14:18:15','2018-11-23 14:18:15','2146d0ea-a031-45e0-bce4-b04ca26c3304'),(625,1086,14,1,'en_gb',13,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Lantra\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"How we work\",\"columnHtml\":\"<p>Industry plays an essential part in Lantra\'s work. Our role often involves working closely with industry groups to deliver solutions to specific industry needs. The opinions and ideas of such groups help us to change and improve the industry, promoting the importance of skills recognition, training and development with the aim of increasing productivity, sustainability - and ultimately profitability.<\\/p>\\r\\n<p>Liaising closely with industries within the land-based sector we represent, along with governments, funding agencies, learning providers, trade associations and the media, we can shape important strategies for the future.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Working with industry groups and employers\",\"columnHtml\":\"<p><\\/p><p>At the heart of Lantra\'s organisation are environmental and land-based employers - experts in their own field who know first hand the requirements of their industries. Employers play an integral part in the corporate structure of the organisation from working groups through to Lantra\'s board of directors, and play a key leadership role in forming and shaping Lantra\'s strategies, products and services.<\\/p><p><\\/p>\"}}},\"2\":\"\",\"115\":[\"1591\"]}}','2018-11-23 14:20:46','2018-11-23 14:20:46','2f79d67b-0a01-486e-907a-9dba54434073'),(626,1086,14,1,'en_gb',14,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Lantra\",\"slug\":\"about-lantra\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"How we work\",\"columnHtml\":\"<p>Industry plays an essential part in Lantra\'s work. Our role often involves working closely with industry groups to deliver solutions to specific industry needs. The opinions and ideas of such groups help us to change and improve the industry, promoting the importance of skills recognition, training and development with the aim of increasing productivity, sustainability - and ultimately profitability.<\\/p>\\r\\n<p>Liaising closely with industries within the land-based sector we represent, along with governments, funding agencies, learning providers, trade associations and the media, we can shape important strategies for the future.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Working with industry groups and employers\",\"columnHtml\":\"<p>At the heart of Lantra\'s organisation are environmental and land-based employers - experts in their own field who know first hand the requirements of their industries. Employers play an integral part in the corporate structure of the organisation from working groups through to Lantra\'s board of directors, and play a key leadership role in forming and shaping Lantra\'s strategies, products and services.<\\/p>\"}}},\"2\":\"\",\"115\":[\"1591\"]}}','2018-11-23 14:21:00','2018-11-23 14:21:00','be011e8e-f0da-4676-8bc1-6d4a3a24a74c'),(627,1086,14,1,'en_gb',15,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Lantra\",\"slug\":\"about-lantra\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"How we work\",\"columnHtml\":\"<p>The opinions and ideas of such groups help us to change and improve the industry, promoting the importance of skills recognition, training and development with the aim of increasing productivity, sustainability - and ultimately profitability.<\\/p>\\r\\n<p>Liaising closely with industries within the land-based sector we represent, along with governments, funding agencies, learning providers, trade associations and the media, we can shape important strategies for the future.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Working with industry groups and employers\",\"columnHtml\":\"<p>At the heart of Lantra\'s organisation are environmental and land-based employers - experts in their own field who know first hand the requirements of their industries. Employers play an integral part in the corporate structure of the organisation from working groups through to Lantra\'s board of directors, and play a key leadership role in forming and shaping Lantra\'s strategies, products and services.<\\/p>\"}}},\"2\":\"Industry plays an essential part in Lantra\'s work. Our role often involves working closely with industry groups to deliver solutions to specific industry needs.\",\"115\":[\"1591\"]}}','2018-11-23 14:31:13','2018-11-23 14:31:13','58b3ffa6-68ac-4c1b-ab94-ea54e07a4982'),(628,1090,14,1,'en_gb',5,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<p><\\/p><p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p><p><\\/p>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 14:42:33','2018-11-23 14:42:33','8a79d24d-931a-4bef-bc0d-bb4bd6fbf3d9'),(629,1090,14,1,'en_gb',6,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\",\"rawHtml\":\"<p>Hello<\\/p>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 14:59:06','2018-11-23 14:59:06','86697375-7e03-45f5-bd36-0772599777fb'),(630,1090,14,1,'en_gb',7,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<pre>\\r\\n&lt;title&gt;Title of the document&lt;\\/title&gt;\\r\\nThe content of the document......\\r\\n<\\/pre>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:12:23','2018-11-23 15:12:23','c3da3c94-cdf2-4772-9cfa-561b5ed09ab4'),(631,1090,14,1,'en_gb',8,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<p>&lt;title&gt;Title of the document&lt;\\/title&gt;\\r\\nThe content of the document......\\r\\n<\\/p>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:12:44','2018-11-23 15:12:44','dd6e66b9-bd4e-42ac-9d5e-ac93f2bb4a97'),(632,1090,14,1,'en_gb',9,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<p>&lt;!DOCTYPE&nbsp;html&gt;<br>&lt;html&gt;<br>&lt;head&gt;<br>&lt;title&gt;Title of the document&lt;\\/title&gt;<br>&lt;\\/head&gt;<br><br>&lt;body&gt;<br>The content of the document......<br>&lt;\\/body&gt;<br><br>&lt;\\/html&gt;<\\/p>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:12:57','2018-11-23 15:12:57','9c749eb2-4216-4573-8216-39113bf1b76e'),(633,1090,14,1,'en_gb',10,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<pre>\\r\\n&lt;!DOCTYPE&nbsp;html&gt;<br>&lt;html&gt;<br>&lt;head&gt;<br>&lt;title&gt;Title of the document&lt;\\/title&gt;<br>&lt;\\/head&gt;<br><br>&lt;body&gt;<br>The content of the document......<br>&lt;\\/body&gt;<br><br>&lt;\\/html&gt;<\\/pre>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:13:07','2018-11-23 15:13:07','cdc3a224-7533-42ec-a7a4-0a9b24d77d55'),(634,1090,14,1,'en_gb',11,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<!DOCTYPE html>\\r\\n<html>\\r\\n<head>\\r\\n<title>Title of the document<\\/title>\\r\\n<\\/head>\\r\\n\\r\\n<body>\\r\\nThe content of the document......\\r\\n<\\/body>\\r\\n\\r\\n<\\/html>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:16:59','2018-11-23 15:16:59','cee85da3-c88d-4acd-b452-1cfae51eca0f'),(635,1090,14,1,'en_gb',12,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&amp;amp;ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:18:26','2018-11-23 15:18:26','9db58e19-e7d8-41b7-a093-d907f5282c99'),(636,1090,14,1,'en_gb',13,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&amp;amp;ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:19:39','2018-11-23 15:19:39','934e3087-4687-40b9-9a9a-490f4872a5de'),(637,1090,14,1,'en_gb',14,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&amp;amp;ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:20:16','2018-11-23 15:20:16','38455940-c3c2-41c5-a1d0-cabb0cb71358'),(638,1090,14,1,'en_gb',15,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:21:44','2018-11-23 15:21:44','c85d3de1-8c0b-4d8d-8d04-1ef0b22c0d69'),(639,1090,14,1,'en_gb',16,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:21:57','2018-11-23 15:21:57','b714b5bf-1d12-456b-992b-3d2c4bc6147a'),(640,1090,14,1,'en_gb',17,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"\",\"115\":\"\"}}','2018-11-23 15:25:26','2018-11-23 15:25:26','8d596129-7921-4387-8ccd-80ff460cdd33'),(641,1090,14,1,'en_gb',18,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:25:47','2018-11-23 15:25:47','cdb9d38b-0443-422d-aa5e-1926e5ddd94f'),(642,1090,14,1,'en_gb',19,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<button class=\\\"button primary-bg webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:34:38','2018-11-23 15:34:38','020b4641-157f-4c8b-87ea-4b5d10820008'),(643,1595,14,1,'en_gb',4,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"FAQs\",\"slug\":\"faqs\",\"postDate\":1542722760,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":\"\"}}','2018-11-23 15:46:13','2018-11-23 15:46:13','93f24616-dda2-421b-9c96-4be4870a92d4'),(649,1086,14,1438,'en_gb',16,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Lantra\",\"slug\":\"about-lantra\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"How we work\",\"columnHtml\":\"<p>The opinions and ideas of such groups help us to change and improve the industry, promoting the importance of skills recognition, training and development with the aim of increasing productivity, sustainability - and ultimately profitability.<\\/p>\\r\\n<p>Liaising closely with industries within the land-based sector we represent, along with governments, funding agencies, learning providers, trade associations and the media, we can shape important strategies for the future.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Working with industry groups and employers\",\"columnHtml\":\"<p>At the heart of Lantra\'s organisation are environmental and land-based employers - experts in their own field who know first hand the requirements of their industries. Employers play an integral part in the corporate structure of the organisation from working groups through to Lantra\'s board of directors, and play a key leadership role in forming and shaping Lantra\'s strategies, products and services.<\\/p>\"}}},\"2\":\"Industry plays an essential part in Lantra\'s work. Our role often involves working closely with industry groups to deliver solutions to specific industry needs.\",\"115\":\"\"}}','2018-12-07 10:17:14','2018-12-07 10:17:14','304a50b8-660f-4e75-8894-25b60ad200e3'),(994,1090,14,1,'en_gb',20,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<a href=\\\"mailto:Portia.Hartley@lantra.co.uk\\\">Send us an email<\\/a>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2019-01-16 11:17:04','2019-01-16 11:17:04','8efd146d-206a-4857-a8fc-22ddae02501d'),(1068,1090,14,1,'en_gb',21,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<p><a href=\\\"mailto:Portia.Hartley@lantra.co.uk?subject=Testing\\\">Send us an email<\\/a><\\/p>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2019-01-18 13:30:12','2019-01-18 13:30:12','a7a05edd-8f07-4af2-8bc9-6d806d6b374c');
/*!40000 ALTER TABLE `craft_entryversions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_export_map`
--

DROP TABLE IF EXISTS `craft_export_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_export_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settings` text COLLATE utf8_unicode_ci,
  `map` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_export_map`
--

LOCK TABLES `craft_export_map` WRITE;
/*!40000 ALTER TABLE `craft_export_map` DISABLE KEYS */;
INSERT INTO `craft_export_map` VALUES (1,'{\"elementvars\":{\"groups\":[\"5\"]},\"sort\":\"asc\",\"type\":\"User\"}','{\"id\":{\"name\":\"ID\",\"label\":\"ID\",\"checked\":\"\"},\"username\":{\"name\":\"Username\",\"label\":\"Username\",\"checked\":\"\"},\"firstName\":{\"name\":\"First Name\",\"label\":\"First Name\",\"checked\":\"1\"},\"lastName\":{\"name\":\"Last Name\",\"label\":\"Last Name\",\"checked\":\"1\"},\"email\":{\"name\":\"Email\",\"label\":\"Email\",\"checked\":\"1\"},\"preferredLocale\":{\"name\":\"Preferred Locale\",\"label\":\"Preferred Locale\",\"checked\":\"\"},\"weekStartDay\":{\"name\":\"Week Start Day\",\"label\":\"Week Start Day\",\"checked\":\"\"},\"status\":{\"name\":\"Status\",\"label\":\"Status\",\"checked\":\"\"},\"lastLoginDate\":{\"name\":\"Last Login Date\",\"label\":\"Last Login Date\",\"checked\":\"\"},\"invalidLoginCount\":{\"name\":\"Invalid Login Count\",\"label\":\"Invalid Login Count\",\"checked\":\"\"},\"lastInvalidLoginDate\":{\"name\":\"Last Invalid Login Date\",\"label\":\"Last Invalid Login Date\",\"checked\":\"\"},\"userTeam\":{\"name\":\"User Team\",\"label\":\"User Team\",\"checked\":\"1\"},\"userRole\":{\"name\":\"User Role\",\"label\":\"User Role\",\"checked\":\"\"},\"userTelephone\":{\"name\":\"User Telephone\",\"label\":\"User Telephone\",\"checked\":\"\"},\"userExpiryDate\":{\"name\":\"User Expiry Date\",\"label\":\"User Expiry Date\",\"checked\":\"\"},\"userPayments\":{\"name\":\"User Payments\",\"label\":\"User Payments\",\"checked\":\"\"}}','2018-06-02 09:37:37','2018-06-02 10:57:32','21f5101a-1b99-4b81-adea-d86cbe29acee'),(2,'{\"elementvars\":{\"groups\":[\"2\"]},\"sort\":\"asc\",\"type\":\"User\"}','{\"id\":{\"name\":\"ID\",\"label\":\"ID\",\"checked\":\"\"},\"username\":{\"name\":\"Username\",\"label\":\"Username\",\"checked\":\"1\"},\"firstName\":{\"name\":\"First Name\",\"label\":\"First Name\",\"checked\":\"1\"},\"lastName\":{\"name\":\"Last Name\",\"label\":\"Last Name\",\"checked\":\"1\"},\"email\":{\"name\":\"Email\",\"label\":\"Email\",\"checked\":\"1\"},\"preferredLocale\":{\"name\":\"Preferred Locale\",\"label\":\"Preferred Locale\",\"checked\":\"\"},\"weekStartDay\":{\"name\":\"Week Start Day\",\"label\":\"Week Start Day\",\"checked\":\"\"},\"status\":{\"name\":\"Status\",\"label\":\"Status\",\"checked\":\"\"},\"lastLoginDate\":{\"name\":\"Last Login Date\",\"label\":\"Last Login Date\",\"checked\":\"\"},\"invalidLoginCount\":{\"name\":\"Invalid Login Count\",\"label\":\"Invalid Login Count\",\"checked\":\"\"},\"lastInvalidLoginDate\":{\"name\":\"Last Invalid Login Date\",\"label\":\"Last Invalid Login Date\",\"checked\":\"\"},\"userTeam\":{\"name\":\"User Team\",\"label\":\"User Team\",\"checked\":\"1\"},\"userRole\":{\"name\":\"User Role\",\"label\":\"User Role\",\"checked\":\"1\"},\"userTelephone\":{\"name\":\"User Telephone\",\"label\":\"User Telephone\",\"checked\":\"1\"},\"userExpiryDate\":{\"name\":\"User Expiry Date\",\"label\":\"User Expiry Date\",\"checked\":\"1\"},\"userPayments\":{\"name\":\"User Payments\",\"label\":\"User Payments\",\"checked\":\"1\"}}','2018-06-02 10:51:05','2018-06-02 10:51:05','f0fd80b5-cba1-4951-9567-cb29c32b754d');
/*!40000 ALTER TABLE `craft_export_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_fieldgroups`
--

DROP TABLE IF EXISTS `craft_fieldgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_fieldgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_fieldgroups_name_unq_idx` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_fieldgroups`
--

LOCK TABLES `craft_fieldgroups` WRITE;
/*!40000 ALTER TABLE `craft_fieldgroups` DISABLE KEYS */;
INSERT INTO `craft_fieldgroups` VALUES (1,'Pages','2017-10-23 13:26:43','2017-10-23 15:32:42','0d277023-1a22-42a9-9b23-8d89a23cc677'),(2,'Users','2017-10-23 13:52:07','2017-10-23 13:52:07','7f07c2bb-4fec-43ab-a281-bcb564148b2b'),(3,'Structure','2017-10-23 13:53:03','2017-10-23 13:53:03','f09af15b-015a-415a-b707-81fa9d1a1d69'),(4,'Modules','2017-10-23 15:32:48','2017-10-24 10:00:46','67ac5777-9885-4cde-bb93-527d48b311e0'),(5,'Tests','2018-04-13 10:14:17','2018-04-13 10:14:17','cce231c0-8af7-4fa9-8bba-17840a90fcc2'),(6,'Results','2018-04-13 18:56:00','2018-04-13 18:56:00','24e619f3-3a4b-47a9-beea-4dc60343c548'),(8,'Reports','2018-08-13 12:10:38','2018-08-13 12:10:38','169df08f-5dca-4d57-8005-9e0226ec5dcc'),(9,'Theme','2018-08-21 15:21:57','2018-08-21 15:21:57','3f934bd7-b755-4330-88af-dd41d6ea990b'),(10,'Globals','2018-09-26 14:45:56','2018-09-26 14:45:56','47ae7220-5336-4c43-9d2c-e7dedf6003df'),(11,'Legacy','2018-10-04 15:30:21','2018-10-04 15:30:21','f5eec841-a56b-474d-b01a-8ad8158d89b8');
/*!40000 ALTER TABLE `craft_fieldgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_fieldlayoutfields`
--

DROP TABLE IF EXISTS `craft_fieldlayoutfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_fieldlayoutfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `layoutId` int(11) NOT NULL,
  `tabId` int(11) NOT NULL,
  `fieldId` int(11) NOT NULL,
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_fieldlayoutfields_layoutId_fieldId_unq_idx` (`layoutId`,`fieldId`),
  KEY `craft_fieldlayoutfields_sortOrder_idx` (`sortOrder`),
  KEY `craft_fieldlayoutfields_tabId_fk` (`tabId`),
  KEY `craft_fieldlayoutfields_fieldId_fk` (`fieldId`),
  CONSTRAINT `craft_fieldlayoutfields_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_fieldlayoutfields_layoutId_fk` FOREIGN KEY (`layoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_fieldlayoutfields_tabId_fk` FOREIGN KEY (`tabId`) REFERENCES `craft_fieldlayouttabs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1285 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_fieldlayoutfields`
--

LOCK TABLES `craft_fieldlayoutfields` WRITE;
/*!40000 ALTER TABLE `craft_fieldlayoutfields` DISABLE KEYS */;
INSERT INTO `craft_fieldlayoutfields` VALUES (142,71,59,8,1,1,'2018-03-07 14:02:01','2018-03-07 14:02:01','be2ec4c3-0419-4ee0-85ed-383fd474b399'),(143,71,59,9,1,2,'2018-03-07 14:02:01','2018-03-07 14:02:01','79d4125e-07fc-4790-8844-6ffbf75a23c3'),(144,71,60,10,0,1,'2018-03-07 14:02:01','2018-03-07 14:02:01','c31f458a-8337-4a88-8ba1-de805f85c1a6'),(145,71,60,11,0,2,'2018-03-07 14:02:01','2018-03-07 14:02:01','7c35538d-737f-4b65-b8c9-ccf9c5f46f11'),(152,74,64,20,0,1,'2018-03-09 11:20:19','2018-03-09 11:20:19','a76f8c65-d603-415d-991e-47d012c32a5b'),(153,74,64,25,0,2,'2018-03-09 11:20:19','2018-03-09 11:20:19','0c2e5be6-1063-4ccb-8d65-35e20e641b04'),(200,94,81,41,0,1,'2018-04-13 18:13:59','2018-04-13 18:13:59','535e9d33-d0b9-4666-9301-4d79a1234e9a'),(201,94,81,42,0,2,'2018-04-13 18:13:59','2018-04-13 18:13:59','b42cbecb-f943-499c-89e2-c2e3bdf95d11'),(202,94,81,43,0,3,'2018-04-13 18:13:59','2018-04-13 18:13:59','cbcd1834-bfbd-4da1-84bf-5d9394370a54'),(203,95,82,44,0,1,'2018-04-13 18:13:59','2018-04-13 18:13:59','a22af8ec-e0a1-4b1e-be0a-6b2b878cf967'),(204,95,82,45,0,2,'2018-04-13 18:13:59','2018-04-13 18:13:59','b8db2050-54dd-4949-a2ef-0c042d0fb61a'),(205,95,82,46,0,3,'2018-04-13 18:13:59','2018-04-13 18:13:59','c0978586-eda4-4dce-a815-e51f4fb5e04c'),(206,96,83,47,0,1,'2018-04-13 18:13:59','2018-04-13 18:13:59','f73ae004-5537-4c91-98a0-cede31ee0e80'),(207,96,83,48,0,2,'2018-04-13 18:13:59','2018-04-13 18:13:59','3b27b72c-9d12-47a0-9ee6-7365f4769ad6'),(208,97,84,51,0,1,'2018-04-13 18:20:05','2018-04-13 18:20:05','5b6108e8-0f9f-4c87-8904-ca650d6628a8'),(209,97,84,56,0,2,'2018-04-13 18:20:05','2018-04-13 18:20:05','34ec8639-44ae-47f8-afdd-cd7c67b4babc'),(210,97,84,52,0,3,'2018-04-13 18:20:05','2018-04-13 18:20:05','b73bfe8e-50a1-47bf-84e2-56ab89fd499c'),(211,97,84,53,0,4,'2018-04-13 18:20:05','2018-04-13 18:20:05','364af5a5-85e5-41ce-8eba-ba6d0a29d417'),(369,122,124,55,0,1,'2018-04-19 14:53:29','2018-04-19 14:53:29','2c8deac2-efef-43be-8d62-9f6b3277abcb'),(370,122,124,50,0,2,'2018-04-19 14:53:29','2018-04-19 14:53:29','78108094-3732-4b5b-abe2-8ef1ca36634c'),(408,138,141,72,0,1,'2018-04-29 12:42:06','2018-04-29 12:42:06','b22fa490-73b0-4fcb-96ff-6a12ced5bfaa'),(409,138,141,73,0,2,'2018-04-29 12:42:06','2018-04-29 12:42:06','b943b614-2d63-4d4c-b0c0-bd9e24322ea1'),(434,147,151,78,0,1,'2018-05-31 13:06:54','2018-05-31 13:06:54','a17113f2-1c8b-4ded-aa97-8480966e7303'),(435,147,151,79,0,2,'2018-05-31 13:06:54','2018-05-31 13:06:54','f40fddda-59a3-41a1-a3e6-b3c5e0dd04ef'),(436,147,151,80,0,3,'2018-05-31 13:06:54','2018-05-31 13:06:54','6e713066-a96c-460d-8cdc-4e2ad9eb7a42'),(530,165,180,102,0,1,'2018-08-20 15:36:56','2018-08-20 15:36:56','89c1dbf2-a280-4eee-9056-7df6f7e10f8c'),(531,165,180,90,0,2,'2018-08-20 15:36:56','2018-08-20 15:36:56','19f51c2e-e24e-423f-9a11-da31c63eedd8'),(532,165,180,103,0,3,'2018-08-20 15:36:56','2018-08-20 15:36:56','ddf27439-9d91-4a3b-9f8a-214f6827023a'),(533,165,180,104,0,4,'2018-08-20 15:36:56','2018-08-20 15:36:56','e58ccf82-7445-4172-a5de-80e8491fae4b'),(534,165,181,91,0,1,'2018-08-20 15:36:56','2018-08-20 15:36:56','cb62ae7e-ae57-4fb3-96c8-b48f923ea321'),(535,165,181,95,0,2,'2018-08-20 15:36:56','2018-08-20 15:36:56','407539f5-2705-4db6-ac3b-12be87138ea7'),(536,165,181,92,0,3,'2018-08-20 15:36:56','2018-08-20 15:36:56','5ee54b2a-e0a3-4e3e-b973-1dacd2987a45'),(537,165,181,96,0,4,'2018-08-20 15:36:56','2018-08-20 15:36:56','27d7f888-990d-4529-ab8c-c2bed5eafbaa'),(538,165,181,94,0,5,'2018-08-20 15:36:56','2018-08-20 15:36:56','dd4f95e5-c828-4032-804a-2ffb0ca086a1'),(539,165,181,98,0,6,'2018-08-20 15:36:56','2018-08-20 15:36:56','df3adcfc-c154-46fc-ae7d-0bfb0dad83a2'),(540,165,181,93,0,7,'2018-08-20 15:36:56','2018-08-20 15:36:56','4f6b6f00-3f5f-41f9-a1c6-a988cbeda11f'),(541,165,181,97,0,8,'2018-08-20 15:36:56','2018-08-20 15:36:56','42395d2b-a73c-47b0-b5ac-cc386313f78f'),(542,165,181,106,0,9,'2018-08-20 15:36:56','2018-08-20 15:36:56','40a42a5d-8746-4853-93db-988f76a75435'),(543,165,182,99,0,1,'2018-08-20 15:36:56','2018-08-20 15:36:56','1a7aa133-43df-4fe9-a66a-e46bd6e53aa6'),(544,165,182,100,0,2,'2018-08-20 15:36:56','2018-08-20 15:36:56','1b228741-085e-42d9-a82a-88750e7b3fc5'),(545,165,182,101,0,3,'2018-08-20 15:36:56','2018-08-20 15:36:56','10e5c029-d341-4c2f-86b1-a74775313d14'),(546,165,183,105,0,1,'2018-08-20 15:36:56','2018-08-20 15:36:56','58d6bed5-2a4c-49c0-9718-546d1786bbdd'),(550,171,185,108,0,1,'2018-08-21 15:27:31','2018-08-21 15:27:31','fb4b2d8d-7a39-4809-b6a3-c70399ff2712'),(551,171,185,109,0,2,'2018-08-21 15:27:31','2018-08-21 15:27:31','2de1c44e-b18e-4f66-a60a-fa003e6d86f2'),(552,171,185,110,0,3,'2018-08-21 15:27:32','2018-08-21 15:27:32','22f03fdf-c0d8-4445-b1d6-3ecc6865bb1e'),(834,221,258,150,0,1,'2018-10-04 15:34:00','2018-10-04 15:34:00','1fa3378e-2e16-4ef3-b191-4470741e0943'),(937,231,280,4,0,1,'2018-10-16 13:49:35','2018-10-16 13:49:35','e8f3adfb-08eb-4007-862b-cfc69f201967'),(938,231,280,12,0,2,'2018-10-16 13:49:35','2018-10-16 13:49:35','899a9586-cf4a-44c9-860f-39e7bd2a0912'),(939,231,280,164,0,3,'2018-10-16 13:49:35','2018-10-16 13:49:35','9865b149-719f-477b-be76-46596dcbcb98'),(940,231,280,14,1,4,'2018-10-16 13:49:35','2018-10-16 13:49:35','8a73c679-69d6-4d2c-a426-4a93a0bfbf57'),(941,231,281,150,0,1,'2018-10-16 13:49:35','2018-10-16 13:49:35','aa1bce66-b954-4472-a6f9-d43a94e890f1'),(942,231,281,152,0,2,'2018-10-16 13:49:35','2018-10-16 13:49:35','b6d222fd-8857-4edb-86a6-dab9954935eb'),(956,247,288,113,0,1,'2018-11-23 15:20:11','2018-11-23 15:20:11','7d1ab612-ba98-4ecd-8638-c0c15df5cc26'),(957,247,288,114,0,2,'2018-11-23 15:20:11','2018-11-23 15:20:11','9c0d9854-d850-4c81-8722-7b11e550bd17'),(1010,255,304,167,0,1,'2018-12-11 07:56:08','2018-12-11 07:56:08','0d5c5bbe-27b4-41a9-a30e-fb39e839340b'),(1011,255,304,171,0,2,'2018-12-11 07:56:08','2018-12-11 07:56:08','118d8be3-f2b2-4d41-8b4d-0c04f6e5bccc'),(1040,258,311,2,0,1,'2018-12-12 00:07:57','2018-12-12 00:07:57','cdcc546f-ac61-44be-b612-3306fe70732d'),(1041,258,311,112,0,2,'2018-12-12 00:07:57','2018-12-12 00:07:57','43aa11a8-90dc-4341-b979-e5fa48e3dfdd'),(1042,258,311,115,0,3,'2018-12-12 00:07:57','2018-12-12 00:07:57','076d84db-5399-43a3-a392-7975cf0a158b'),(1115,268,331,71,0,1,'2019-01-21 16:04:08','2019-01-21 16:04:08','4e25e23f-0b28-4a50-8f71-ab1ae7e93337'),(1116,268,331,116,0,2,'2019-01-21 16:04:08','2019-01-21 16:04:08','1a4ffb90-36cd-420d-ae5a-2b8d9f7ce94c'),(1117,268,331,117,0,3,'2019-01-21 16:04:08','2019-01-21 16:04:08','bbcaedc5-95fc-4010-8a78-14344d6fdc98'),(1118,268,331,118,0,4,'2019-01-21 16:04:08','2019-01-21 16:04:08','ce71ab55-1c7d-45f9-afb7-a61d1d862046'),(1119,268,331,119,0,5,'2019-01-21 16:04:08','2019-01-21 16:04:08','4d02760d-405e-4fb6-a991-91e92b7ac9ce'),(1120,268,331,107,0,6,'2019-01-21 16:04:08','2019-01-21 16:04:08','4bf1ca52-151d-44c1-a628-22eb36d00bd4'),(1121,268,331,111,0,7,'2019-01-21 16:04:08','2019-01-21 16:04:08','11bbae80-8f5e-43ad-9400-16fac9f45ebe'),(1122,268,331,172,0,8,'2019-01-21 16:04:08','2019-01-21 16:04:08','7ac760d1-b16b-4c4c-a50c-96b4d87abbb0'),(1123,268,331,167,0,9,'2019-01-21 16:04:08','2019-01-21 16:04:08','fe78ca00-43d4-4fa6-a44f-4f8bb7c9a84a'),(1124,269,332,166,1,1,'2019-01-21 17:37:43','2019-01-21 17:37:43','b5def280-4a9c-4390-bb0f-26b37e657d01'),(1125,269,332,2,0,2,'2019-01-21 17:37:44','2019-01-21 17:37:44','33c64bb5-4862-4989-9905-7e451abe77e5'),(1126,269,332,38,0,3,'2019-01-21 17:37:44','2019-01-21 17:37:44','cfb92fde-a226-4eb4-b800-343927cee329'),(1127,269,332,68,0,4,'2019-01-21 17:37:44','2019-01-21 17:37:44','1d3c705d-e244-42b8-b0cd-a1badef447e2'),(1128,269,332,181,0,5,'2019-01-21 17:37:44','2019-01-21 17:37:44','20e5f82a-eef5-4fe1-9830-f043c0f2245b'),(1129,269,332,19,0,6,'2019-01-21 17:37:44','2019-01-21 17:37:44','8400f2a5-907e-418f-bcd3-e1f829fa618f'),(1130,269,333,28,0,1,'2019-01-21 17:37:44','2019-01-21 17:37:44','183824ed-a995-4a08-8d3e-b9cc9b56bee8'),(1158,275,338,15,0,1,'2019-02-01 09:49:13','2019-02-01 09:49:13','6356ad3b-43ff-49d8-af0f-a339219c2d21'),(1159,275,338,75,0,2,'2019-02-01 09:49:13','2019-02-01 09:49:13','0dcf3451-3069-4b91-a872-8643029b0a6a'),(1160,275,338,131,0,3,'2019-02-01 09:49:13','2019-02-01 09:49:13','4ab20479-fa4b-43ac-aa11-7baff2a67adf'),(1161,275,338,182,0,4,'2019-02-01 09:49:13','2019-02-01 09:49:13','27d2e567-10a3-44f4-945e-3949821561d0'),(1162,275,338,141,0,5,'2019-02-01 09:49:13','2019-02-01 09:49:13','0e69ffe4-1367-45b8-bc4c-d718f513aabb'),(1163,275,338,176,0,6,'2019-02-01 09:49:13','2019-02-01 09:49:13','467568b8-0a68-40a7-8a4a-4bcf50111b31'),(1164,275,338,145,0,7,'2019-02-01 09:49:13','2019-02-01 09:49:13','2c475842-af78-4279-a63b-db0d7389d731'),(1165,275,338,82,0,8,'2019-02-01 09:49:13','2019-02-01 09:49:13','4a76f953-22af-430b-bc37-23a02323b7e3'),(1166,275,338,89,0,9,'2019-02-01 09:49:13','2019-02-01 09:49:13','6a57262a-bb99-4c95-85d2-3bc6137af2bd'),(1167,276,339,187,0,1,'2019-02-01 09:55:55','2019-02-01 09:55:55','12afeb93-d159-426e-b905-a2a592db9bd0'),(1168,276,339,188,0,2,'2019-02-01 09:55:55','2019-02-01 09:55:55','49767b7d-0d4e-444f-b3a6-5f4beeee192b'),(1169,276,339,185,0,3,'2019-02-01 09:55:55','2019-02-01 09:55:55','bcac99d9-7ec3-4c6a-aa20-5cce7f7f8ecb'),(1170,276,339,189,0,4,'2019-02-01 09:55:55','2019-02-01 09:55:55','c4eb773d-5bc4-4033-9912-bcbc86ee8d8f'),(1171,276,339,184,0,5,'2019-02-01 09:55:55','2019-02-01 09:55:55','d9b359e8-7bbf-40bc-896b-2af1517c4316'),(1172,276,339,190,0,6,'2019-02-01 09:55:55','2019-02-01 09:55:55','16e9cde5-350d-46a1-b63b-3da6a696e4b3'),(1173,276,339,186,0,7,'2019-02-01 09:55:55','2019-02-01 09:55:55','041f8b93-d1a7-4bc7-87f9-9d68026e62df'),(1174,276,339,127,0,8,'2019-02-01 09:55:55','2019-02-01 09:55:55','d289e2b9-e60e-4789-a866-f70db9d42bb3'),(1175,277,340,125,0,1,'2019-02-01 09:58:38','2019-02-01 09:58:38','8030c7a1-3dc7-4982-86d5-438167c2f541'),(1176,277,340,126,0,2,'2019-02-01 09:58:38','2019-02-01 09:58:38','12b8519d-b3b1-4878-aaa7-b9663109a761'),(1185,280,343,178,0,1,'2019-02-08 11:24:44','2019-02-08 11:24:44','d2db2a7f-5a5e-4ca0-af55-86faf7fcc09e'),(1186,280,343,179,0,2,'2019-02-08 11:24:44','2019-02-08 11:24:44','c1267bc0-b00f-4f54-a759-bd14971c7737'),(1187,280,343,180,0,3,'2019-02-08 11:24:44','2019-02-08 11:24:44','daeabb91-3804-40e2-894c-c24c5e793e45'),(1188,280,343,191,0,4,'2019-02-08 11:24:44','2019-02-08 11:24:44','da1b5ae2-ae49-4bff-8af5-6f0214cb9a2e'),(1189,282,344,16,0,1,'2019-02-11 12:37:37','2019-02-11 12:37:37','c13c9609-f4d5-462c-b80a-1ee548b9fcd5'),(1190,282,344,18,0,2,'2019-02-11 12:37:37','2019-02-11 12:37:37','b490c357-67cc-4d42-8338-a85efaae0ef4'),(1191,282,344,39,0,3,'2019-02-11 12:37:37','2019-02-11 12:37:37','0b149733-386d-452b-8c04-c4db7e276d5c'),(1192,282,344,130,0,4,'2019-02-11 12:37:37','2019-02-11 12:37:37','8f722174-4cf5-496b-880e-9a70974e7f8b'),(1193,282,344,37,0,5,'2019-02-11 12:37:37','2019-02-11 12:37:37','b470da29-51b3-4ae2-a2fd-0ed49f90de53'),(1194,282,344,74,0,6,'2019-02-11 12:37:37','2019-02-11 12:37:37','27f97b8c-474f-4d96-b95b-73beff63b2a6'),(1195,282,344,192,0,7,'2019-02-11 12:37:37','2019-02-11 12:37:37','85f1c526-c8b4-47a5-bfbd-47eed66896ef'),(1196,282,345,49,0,1,'2019-02-11 12:37:37','2019-02-11 12:37:37','8a7dc9a3-31d8-4a52-b333-725063f9032f'),(1197,282,345,70,0,2,'2019-02-11 12:37:37','2019-02-11 12:37:37','800f08ef-e799-428d-a145-2d84f5896178'),(1198,282,345,40,0,3,'2019-02-11 12:37:37','2019-02-11 12:37:37','2731b26f-a6f9-414a-a329-ed341aafc73e'),(1199,282,346,150,0,1,'2019-02-11 12:37:37','2019-02-11 12:37:37','5bdb6201-52e5-4f83-acba-dd5670aa05b9'),(1200,292,347,128,0,1,'2019-02-11 13:40:11','2019-02-11 13:40:11','6d54ee7a-f43f-4c9b-ba0d-156d8ea930f8'),(1201,292,347,3,0,2,'2019-02-11 13:40:11','2019-02-11 13:40:11','14be60b8-c1c8-41ea-8405-23feb790536e'),(1202,292,347,30,0,3,'2019-02-11 13:40:11','2019-02-11 13:40:11','8865fcd0-2b08-463d-baaa-02ccf64a3d94'),(1203,292,347,129,0,4,'2019-02-11 13:40:12','2019-02-11 13:40:12','1bfe8bf3-e533-4fac-847e-0fb1d47512d9'),(1204,292,347,132,0,5,'2019-02-11 13:40:12','2019-02-11 13:40:12','4a6fad96-c362-4b50-b2c6-ae110f30de6e'),(1205,292,347,170,0,6,'2019-02-11 13:40:12','2019-02-11 13:40:12','980ba1f8-0432-4104-b81b-e80c7b8dc193'),(1206,292,347,173,0,7,'2019-02-11 13:40:12','2019-02-11 13:40:12','5ea3fd47-c040-4bfb-95a8-43a7345b5607'),(1207,292,348,121,0,1,'2019-02-11 13:40:12','2019-02-11 13:40:12','1fd52e10-81e4-4f7c-9657-7555fd1f55ec'),(1208,292,348,122,0,2,'2019-02-11 13:40:12','2019-02-11 13:40:12','e931b550-a241-45c6-94a6-04ec394032ee'),(1209,292,348,123,0,3,'2019-02-11 13:40:12','2019-02-11 13:40:12','0d8a1034-4f99-46cc-b567-d77b9368ed8b'),(1210,292,348,76,0,4,'2019-02-11 13:40:12','2019-02-11 13:40:12','76e278ca-57ff-4d7b-8bda-faaac303043c'),(1211,292,348,84,0,5,'2019-02-11 13:40:12','2019-02-11 13:40:12','31df58fd-6296-4574-b3d5-037f76016272'),(1212,292,348,193,0,6,'2019-02-11 13:40:12','2019-02-11 13:40:12','d0efda03-82ca-41d1-b0b1-d608314e79bb'),(1213,292,349,77,0,1,'2019-02-11 13:40:12','2019-02-11 13:40:12','138d07f2-fa47-43e6-a4e8-a5749b9d12c8'),(1214,292,350,124,0,1,'2019-02-11 13:40:12','2019-02-11 13:40:12','a162ac2f-a4ec-4912-bd05-010004e4c845'),(1215,292,351,150,0,1,'2019-02-11 13:40:12','2019-02-11 13:40:12','a20752ba-47a5-4ad4-87a3-6c0c2275ea15'),(1216,292,351,147,0,2,'2019-02-11 13:40:12','2019-02-11 13:40:12','f579cec4-ae19-4df6-a4a9-a3269c8a23e2'),(1217,292,351,148,0,3,'2019-02-11 13:40:12','2019-02-11 13:40:12','b9ddc243-9d46-4e9f-9be0-0cd9c5138926'),(1218,292,351,149,0,4,'2019-02-11 13:40:12','2019-02-11 13:40:12','dfb415a9-5684-4bb5-a3a0-2cf0c759480f'),(1219,292,351,151,0,5,'2019-02-11 13:40:12','2019-02-11 13:40:12','256ff7a2-dc4e-40d9-9846-2fd4f1010a99'),(1220,282,344,194,0,8,'2019-04-25 13:25:42','2019-04-25 13:25:42','22452e23-9358-4e38-a98b-09562814edd3'),(1248,296,363,168,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','0344b799-c414-402c-b0a8-96b83e8d47f8'),(1249,296,363,169,0,2,'2019-04-25 13:26:07','2019-04-25 13:26:07','b3362090-848a-4384-a5de-a8600625f1d2'),(1250,296,363,195,0,3,'2019-04-25 13:26:07','2019-04-25 13:26:07','b5c7b331-af1f-4002-8218-24f25574a021'),(1251,296,363,196,0,4,'2019-04-25 13:26:07','2019-04-25 13:26:07','2608a391-ed37-476c-bd6a-c8c792f862b0'),(1252,296,363,197,0,5,'2019-04-25 13:26:07','2019-04-25 13:26:07','44d62eef-fac5-4833-be70-112b04695f71'),(1253,296,363,198,0,6,'2019-04-25 13:26:07','2019-04-25 13:26:07','e4bff9b2-5251-4a6c-8cc9-1196f41ad25f'),(1254,297,364,200,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','ad2b5396-a1c4-487d-9594-0c110ca57031'),(1255,297,364,201,0,2,'2019-04-25 13:26:07','2019-04-25 13:26:07','a59d306f-92e9-41a5-bbcb-749cf0c14759'),(1256,298,365,29,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','4e86499b-5313-48ad-a96d-9149e0717fe7'),(1257,298,365,26,0,2,'2019-04-25 13:26:07','2019-04-25 13:26:07','f0373686-98ee-436b-8883-ceb3c876a203'),(1258,298,365,33,0,3,'2019-04-25 13:26:07','2019-04-25 13:26:07','d1d22ec5-483f-4f44-9d49-dcc7b24127c0'),(1259,298,365,130,0,4,'2019-04-25 13:26:07','2019-04-25 13:26:07','ae4ffcb3-e732-4e74-a2be-d33dfeae200f'),(1260,298,366,17,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','13df993a-e5bc-4768-b157-06b2c5ddfaae'),(1261,298,366,86,0,2,'2019-04-25 13:26:07','2019-04-25 13:26:07','337079b6-cd18-4f5d-b5be-e5d882a6a2cb'),(1262,298,366,142,0,3,'2019-04-25 13:26:07','2019-04-25 13:26:07','1037310b-e1a2-4bf0-9d44-e303e1967be2'),(1263,298,366,143,0,4,'2019-04-25 13:26:07','2019-04-25 13:26:07','3900ed0b-cfc8-4b2b-9a85-d9941185da27'),(1264,298,366,144,0,5,'2019-04-25 13:26:07','2019-04-25 13:26:07','aa52e69f-16d8-4502-a045-ada1a4f14321'),(1265,298,367,58,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','edd598bc-33fc-42e9-8eed-f20e7faedfe8'),(1266,298,367,27,0,2,'2019-04-25 13:26:07','2019-04-25 13:26:07','dee10ee8-3709-45a4-8e8e-84edc674588a'),(1267,298,368,177,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','e6cdb9f0-9432-4bfb-a082-2ff7a8d999b6'),(1268,298,369,199,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','6ffbd82f-1a21-415c-ac50-a1da33663a36'),(1269,299,370,67,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','50ee0a6d-e510-473b-ac08-c24ab2e117ae'),(1270,299,370,29,0,2,'2019-04-25 13:26:07','2019-04-25 13:26:07','5f1dea42-da1e-4caa-b693-f2fe41c258bb'),(1271,299,371,177,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','2d0058aa-1dc4-43a9-b267-9b4eb21f54d2'),(1272,300,372,29,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','444957e5-f746-43b5-bb71-e653ca3edd62'),(1273,300,372,33,0,2,'2019-04-25 13:26:07','2019-04-25 13:26:07','9f4f5f0f-3d6b-44b3-bb1e-153833a3d6dd'),(1274,300,373,17,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','c6ee2082-ab5c-4ddd-ace1-b4c3b1a4c593'),(1275,300,373,142,0,2,'2019-04-25 13:26:07','2019-04-25 13:26:07','82b0d87c-ce4f-492f-9000-adf0e8b9e0c5'),(1276,300,373,143,0,3,'2019-04-25 13:26:07','2019-04-25 13:26:07','7573aec0-b2c3-4090-9e55-40c13fa60b68'),(1277,300,373,144,0,4,'2019-04-25 13:26:07','2019-04-25 13:26:07','74480069-688e-4cf6-a426-32c91b390474'),(1278,300,373,163,0,5,'2019-04-25 13:26:07','2019-04-25 13:26:07','07ca8ebf-e071-4eb5-93ca-6b162a6e22df'),(1279,300,373,86,0,6,'2019-04-25 13:26:07','2019-04-25 13:26:07','e495bdfc-e90e-468a-95d9-b66dd2e917e9'),(1280,300,373,130,0,7,'2019-04-25 13:26:07','2019-04-25 13:26:07','ff03c8dd-a930-42d3-8645-f8b280aff99d'),(1281,300,373,67,0,8,'2019-04-25 13:26:07','2019-04-25 13:26:07','50c21c75-28d5-422b-8def-6ee36b5556d6'),(1282,300,373,146,0,9,'2019-04-25 13:26:07','2019-04-25 13:26:07','7e56ad6a-cf35-4a4e-95b5-5d01184c2000'),(1283,300,374,177,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','6af18ad2-4d43-4bec-b900-556cdac3c32d'),(1284,300,375,199,0,1,'2019-04-25 13:26:07','2019-04-25 13:26:07','e452dded-1b56-4797-bf57-97d24982a28b');
/*!40000 ALTER TABLE `craft_fieldlayoutfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_fieldlayouts`
--

DROP TABLE IF EXISTS `craft_fieldlayouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_fieldlayouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_fieldlayouts_type_idx` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_fieldlayouts`
--

LOCK TABLES `craft_fieldlayouts` WRITE;
/*!40000 ALTER TABLE `craft_fieldlayouts` DISABLE KEYS */;
INSERT INTO `craft_fieldlayouts` VALUES (1,'Tag','2017-10-23 13:26:43','2017-10-23 13:26:43','a33c5200-627f-4920-a685-580ea708b281'),(71,'Entry','2018-03-07 14:02:01','2018-03-07 14:02:01','bcaf5920-c460-4465-a5db-e7f4bad29958'),(74,'MatrixBlock','2018-03-09 11:20:19','2018-03-09 11:20:19','11bf57e1-f8b5-4045-9861-210b0d80c82b'),(94,'MatrixBlock','2018-04-13 18:13:59','2018-04-13 18:13:59','e8be7915-d15b-4c24-ab6e-7fd24b6960df'),(95,'MatrixBlock','2018-04-13 18:13:59','2018-04-13 18:13:59','0df79c77-16b6-457e-9e38-f90d2b470226'),(96,'MatrixBlock','2018-04-13 18:13:59','2018-04-13 18:13:59','a4e23074-f1e3-4121-bffd-2b6ca10ae1d6'),(97,'MatrixBlock','2018-04-13 18:20:05','2018-04-13 18:20:05','b6774e86-2d02-4f1e-8fcc-df899659d76c'),(122,'Entry','2018-04-19 14:53:29','2018-04-19 14:53:29','46202269-1d4b-4803-aef3-1a626eaf5054'),(138,'GlobalSet','2018-04-29 12:42:06','2018-04-29 12:42:06','9f201540-f939-4736-88e9-92545fb318dc'),(147,'MatrixBlock','2018-05-31 13:06:54','2018-05-31 13:06:54','aca00fb2-fff5-4778-bbcb-48193d2a64ec'),(165,'Entry','2018-08-20 15:36:56','2018-08-20 15:36:56','7cd7bc12-750d-4828-bf80-02a229e934e2'),(171,'MatrixBlock','2018-08-21 15:27:31','2018-08-21 15:27:31','d3af77e8-adbb-403d-8ca0-78f119435628'),(221,'Category','2018-10-04 15:34:00','2018-10-04 15:34:00','6c3df5bf-813e-461a-8b1d-1f611d961515'),(231,'Entry','2018-10-16 13:49:35','2018-10-16 13:49:35','ed88ea3b-52b6-408a-aa05-a271ac4fce97'),(247,'MatrixBlock','2018-11-23 15:20:11','2018-11-23 15:20:11','33a2cd8b-939a-4112-af78-f861c965887f'),(255,'Category','2018-12-11 07:56:08','2018-12-11 07:56:08','9d3f4f85-fa54-4b4d-961e-49e4fb6f86ad'),(258,'Entry','2018-12-12 00:07:57','2018-12-12 00:07:57','6d710b1b-95a9-4417-b0dd-78a03a5baa40'),(268,'GlobalSet','2019-01-21 16:04:08','2019-01-21 16:04:08','8c4cb10f-efa5-4668-b26a-e9986c340f76'),(269,'Entry','2019-01-21 17:37:43','2019-01-21 17:37:43','70e84763-f654-4aea-8ba8-7ef0b4a62538'),(275,'GlobalSet','2019-02-01 09:49:13','2019-02-01 09:49:13','9fa634f9-8d10-4b2c-a9ea-0faf2c735391'),(276,'GlobalSet','2019-02-01 09:55:55','2019-02-01 09:55:55','fae23f0d-a855-454c-b83e-a10ad992da82'),(277,'MatrixBlock','2019-02-01 09:58:38','2019-02-01 09:58:38','d7aeffce-740a-42a3-b581-cd245fa7934c'),(280,'SuperTable_Block','2019-02-08 11:24:44','2019-02-08 11:24:44','21ce6d78-5c1b-4d6b-84b6-73b6b453d065'),(282,'Entry','2019-02-11 12:37:37','2019-02-11 12:37:37','3d94edeb-73ba-41ad-b224-c77a85c59742'),(288,'Asset','2019-02-11 12:41:54','2019-02-11 12:41:54','bd3725d2-a115-4fea-8ee5-0249bb9d5976'),(289,'Asset','2019-02-11 12:42:03','2019-02-11 12:42:03','05dc745f-9573-49c5-9353-2e9059555a60'),(290,'Asset','2019-02-11 12:42:13','2019-02-11 12:42:13','27dc94f5-3da8-48e3-9017-8b2837da39a3'),(291,'Asset','2019-02-11 12:42:21','2019-02-11 12:42:21','605105fb-4098-479d-bbcd-c898793aac73'),(292,'User','2019-02-11 13:40:11','2019-02-11 13:40:11','29bbd130-4180-46de-9b29-52a4627cbdf6'),(296,'SuperTable_Block','2019-04-25 13:26:07','2019-04-25 13:26:07','bf76d587-8510-4c03-8541-56b969e4eb32'),(297,'SuperTable_Block','2019-04-25 13:26:07','2019-04-25 13:26:07','92c764d5-c74d-44b3-aa9e-73abc60aecc1'),(298,'Entry','2019-04-25 13:26:07','2019-04-25 13:26:07','39a5c302-5671-4cf4-b3f1-d8f5103ad9e7'),(299,'Entry','2019-04-25 13:26:07','2019-04-25 13:26:07','34ffc658-8526-4ade-84d2-07fe79d792e4'),(300,'Entry','2019-04-25 13:26:07','2019-04-25 13:26:07','953c4f0c-0f3e-49c3-bf43-0381ba7ee019');
/*!40000 ALTER TABLE `craft_fieldlayouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_fieldlayouttabs`
--

DROP TABLE IF EXISTS `craft_fieldlayouttabs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_fieldlayouttabs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `layoutId` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_fieldlayouttabs_sortOrder_idx` (`sortOrder`),
  KEY `craft_fieldlayouttabs_layoutId_fk` (`layoutId`),
  CONSTRAINT `craft_fieldlayouttabs_layoutId_fk` FOREIGN KEY (`layoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=376 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_fieldlayouttabs`
--

LOCK TABLES `craft_fieldlayouttabs` WRITE;
/*!40000 ALTER TABLE `craft_fieldlayouttabs` DISABLE KEYS */;
INSERT INTO `craft_fieldlayouttabs` VALUES (59,71,'Team',1,'2018-03-07 14:02:01','2018-03-07 14:02:01','17726e0f-bba0-4387-b32c-c4b7669d5314'),(60,71,'Managers',2,'2018-03-07 14:02:01','2018-03-07 14:02:01','34dad6b8-3121-4920-b52a-49d33fb80d19'),(64,74,'Content',1,'2018-03-09 11:20:19','2018-03-09 11:20:19','bd3bfe6c-42d3-4054-a222-764ae1c800f0'),(81,94,'Content',1,'2018-04-13 18:13:59','2018-04-13 18:13:59','d3b7f8dd-022f-4c0e-a0b6-106deab1b09b'),(82,95,'Content',1,'2018-04-13 18:13:59','2018-04-13 18:13:59','ff556011-72dc-4030-8dae-7b28e210bb74'),(83,96,'Content',1,'2018-04-13 18:13:59','2018-04-13 18:13:59','57d39d0a-5b6d-49e7-8642-4ad057eb0b6b'),(84,97,'Content',1,'2018-04-13 18:20:05','2018-04-13 18:20:05','6e36f4f6-b9eb-4f91-8bc1-40b9eb04b033'),(124,122,'Attempt',1,'2018-04-19 14:53:29','2018-04-19 14:53:29','e90ea629-d627-463a-a052-71d59156dd67'),(141,138,'Content',1,'2018-04-29 12:42:06','2018-04-29 12:42:06','eaaf9451-d897-4a7a-a633-c3e6d1ba8a17'),(151,147,'Content',1,'2018-05-31 13:06:54','2018-05-31 13:06:54','37d94016-ac46-48be-8516-349f49617815'),(180,165,'Reports',1,'2018-08-20 15:36:56','2018-08-20 15:36:56','9c3b8d5c-312f-441a-bc5d-d396d87f0804'),(181,165,'Criteria',2,'2018-08-20 15:36:56','2018-08-20 15:36:56','12ecb01b-3efc-4031-a77d-ae6a479edaf1'),(182,165,'Recipients',3,'2018-08-20 15:36:56','2018-08-20 15:36:56','b4a728e4-1321-48ce-803e-131c176f2162'),(183,165,'Data',4,'2018-08-20 15:36:56','2018-08-20 15:36:56','679094b0-a76c-4690-a24d-a825bba5c754'),(185,171,'Content',1,'2018-08-21 15:27:31','2018-08-21 15:27:31','8ff98f59-a3c6-4829-ac4a-dc5cac1d1fc0'),(258,221,'Fields',1,'2018-10-04 15:34:00','2018-10-04 15:34:00','8013455a-8bb2-456d-a519-5ec056f4a9fc'),(280,231,'Company',1,'2018-10-16 13:49:35','2018-10-16 13:49:35','72c60610-5fb7-428b-80e8-456b4215c6bc'),(281,231,'Legacy',2,'2018-10-16 13:49:35','2018-10-16 13:49:35','4f722052-d6b7-4c46-a11a-530f79c3d210'),(288,247,'Content',1,'2018-11-23 15:20:11','2018-11-23 15:20:11','47b51dff-5092-47cc-88a1-f91f4d87f915'),(304,255,'Settings',1,'2018-12-11 07:56:08','2018-12-11 07:56:08','4f046bcb-0a6c-4ff3-b4ec-5493293ffca6'),(311,258,'Pages',1,'2018-12-12 00:07:57','2018-12-12 00:07:57','94ad671f-0d63-4c8e-819e-eeeed2cb3747'),(331,268,'Content',1,'2019-01-21 16:04:08','2019-01-21 16:04:08','ed3f64dd-5546-4469-a7ef-11dfdbc93a87'),(332,269,'Module',1,'2019-01-21 17:37:43','2019-01-21 17:37:43','8e615677-602a-4115-a3d8-9bcb2146ffe3'),(333,269,'Job Roles',2,'2019-01-21 17:37:44','2019-01-21 17:37:44','4963bf3d-0ffc-48fc-8691-4ff5c4a69146'),(338,275,'Content',1,'2019-02-01 09:49:13','2019-02-01 09:49:13','ecf44772-2d0b-49b6-bc27-43fa4dc7cdc5'),(339,276,'Content',1,'2019-02-01 09:55:55','2019-02-01 09:55:55','6d89f12d-1f1c-4c29-945e-f27a6c6083cf'),(340,277,'Content',1,'2019-02-01 09:58:38','2019-02-01 09:58:38','43d273a5-e2b9-4624-b9ac-81bd4f4cdc45'),(343,280,'Content',1,'2019-02-08 11:24:44','2019-02-08 11:24:44','3106c15c-a0a1-448c-b4bb-d885ea6200f2'),(344,282,'Unit',1,'2019-02-11 12:37:37','2019-02-11 12:37:37','0589227e-b6a0-484c-b1a1-e73518ea9b86'),(345,282,'Test',2,'2019-02-11 12:37:37','2019-02-11 12:37:37','895482a1-ac1c-4bec-8657-7aed13be6e0e'),(346,282,'Legacy',3,'2019-02-11 12:37:37','2019-02-11 12:37:37','1e64e57d-80e9-4cc1-b466-31802f69ac58'),(347,292,'Structure',1,'2019-02-11 13:40:11','2019-02-11 13:40:11','d73aa7af-87b3-4b60-bf85-3d27d0681c99'),(348,292,'Profile',2,'2019-02-11 13:40:12','2019-02-11 13:40:12','a345d58d-ab2f-4aec-83ee-13ace493d4bd'),(349,292,'Payments',3,'2019-02-11 13:40:12','2019-02-11 13:40:12','78ed80d1-9232-4229-997d-1c97a1f814de'),(350,292,'Custom Scheme User Fields',4,'2019-02-11 13:40:12','2019-02-11 13:40:12','b449f9a8-c189-41d7-a369-432617536b93'),(351,292,'Legacy',5,'2019-02-11 13:40:12','2019-02-11 13:40:12','68c43fb0-5af2-4f35-b34e-9dedd8927451'),(363,296,'Content',1,'2019-04-25 13:26:07','2019-04-25 13:26:07','1d08b702-ff3b-49ed-8f81-36d538bc5d25'),(364,297,'Content',1,'2019-04-25 13:26:07','2019-04-25 13:26:07','6e494343-b598-41b3-8fbd-d812e8361623'),(365,298,'Result',1,'2019-04-25 13:26:07','2019-04-25 13:26:07','af7e7715-98c1-4a93-b3d0-951dd5aecfcf'),(366,298,'Evidence',2,'2019-04-25 13:26:07','2019-04-25 13:26:07','f75dd8d0-37fc-45d4-8b2d-a36a8e772c2f'),(367,298,'Test',3,'2019-04-25 13:26:07','2019-04-25 13:26:07','b0057abb-ba6e-4631-86de-d4bb72249d17'),(368,298,'Comments',4,'2019-04-25 13:26:07','2019-04-25 13:26:07','81368db8-23ba-41a6-9d60-8951fde66535'),(369,298,'Custom Values',5,'2019-04-25 13:26:07','2019-04-25 13:26:07','a2b04dbf-1a45-429c-b8fb-b26a76b41d20'),(370,299,'Result',1,'2019-04-25 13:26:07','2019-04-25 13:26:07','0f720a9a-8ce4-4e76-b9d4-d0ee2b989f6c'),(371,299,'Comments',2,'2019-04-25 13:26:07','2019-04-25 13:26:07','2adc0178-ff15-4860-9363-ef06415f19b0'),(372,300,'Result',1,'2019-04-25 13:26:07','2019-04-25 13:26:07','4d78018f-127c-4d15-bb26-c9b8136a3ffe'),(373,300,'Evidence',2,'2019-04-25 13:26:07','2019-04-25 13:26:07','eef22470-dafe-4154-aa36-f504d7179a04'),(374,300,'Comments',3,'2019-04-25 13:26:07','2019-04-25 13:26:07','755987ac-5fde-4710-825a-63340b269978'),(375,300,'Custom Values',4,'2019-04-25 13:26:07','2019-04-25 13:26:07','9593f9a5-f68f-4aab-b02e-4dbb0e1ac12c');
/*!40000 ALTER TABLE `craft_fieldlayouttabs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_fields`
--

DROP TABLE IF EXISTS `craft_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(58) COLLATE utf8_unicode_ci NOT NULL,
  `context` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'global',
  `instructions` text COLLATE utf8_unicode_ci,
  `translatable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_fields_handle_context_unq_idx` (`handle`,`context`),
  KEY `craft_fields_context_idx` (`context`),
  KEY `craft_fields_groupId_fk` (`groupId`),
  CONSTRAINT `craft_fields_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_fieldgroups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_fields`
--

LOCK TABLES `craft_fields` WRITE;
/*!40000 ALTER TABLE `craft_fields` DISABLE KEYS */;
INSERT INTO `craft_fields` VALUES (1,1,'Body','pageBody','global','',0,'RichText','{\"configFile\":\"Standard.json\",\"availableAssetSources\":\"*\",\"availableTransforms\":\"*\",\"cleanupHtml\":\"1\",\"purifyHtml\":\"1\",\"purifierConfig\":\"\",\"columnType\":\"text\"}','2017-10-23 13:26:43','2017-10-23 13:51:25','f16d7384-1325-4296-8acb-e213c2765770'),(2,1,'Heading','pageHeading','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2017-10-23 13:26:43','2017-10-23 13:51:52','6b431a5f-e1ee-4520-ab30-89ca72993c00'),(3,2,'User Team','userTeam','global','',0,'Entries','{\"sources\":[\"section:5\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 13:52:38','2018-03-09 11:55:49','ca3cc99a-ddf3-4e81-b35c-37d0ecd39ebb'),(4,3,'Company Parent','companyParent','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 13:53:58','2018-02-14 11:16:21','75ec7211-a9c5-4390-963a-9d63dfd0dbd4'),(5,3,'Location Company ','locationCompany','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 13:54:27','2017-10-23 14:38:05','779ea841-f13a-4c87-aa19-bb807eaa7b16'),(6,3,'Location Name','locationName','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2017-10-23 14:37:47','2017-10-23 14:37:54','14ddc2fe-abf2-424c-882c-3f23d87dc4d2'),(8,3,'Team Name','teamName','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2017-10-23 14:52:21','2017-10-23 14:52:21','fcef71c8-c93e-4e97-a8f3-4fc223b2d223'),(9,3,'Team Company','teamCompany','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 14:52:44','2018-02-14 11:19:18','0578b9f0-6e63-4ecd-b386-295f3b276282'),(10,3,'Team Primary Manager','teamPrimaryManager','global','',0,'Users','{\"sources\":[\"group:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 15:07:43','2017-10-23 15:07:43','5839b521-ccd0-4768-bbe4-ef59335fb732'),(11,3,'Team Secondary Managers','teamSecondaryManagers','global','',0,'Users','{\"sources\":[\"group:3\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2017-10-23 15:08:10','2017-10-23 15:08:10','838b996f-8f5d-4df2-9a75-0d1692348632'),(12,3,'Company Primary Manager','companyPrimaryManager','global','',0,'Users','{\"sources\":[\"group:2\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 15:10:55','2018-10-17 13:02:10','fa734878-4241-4654-86c9-50964d8a8692'),(14,3,'Company Remaining Licences','companyRemainingLicences','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2017-10-23 15:34:07','2017-10-23 15:34:07','a8d84419-f7b9-4d64-b7cb-ce827c7ca810'),(15,10,'Scheme Remaining Licences','schemeRemainingLicences','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2017-10-23 15:35:34','2018-09-26 14:46:55','bba70dad-a42e-494b-b2a4-8355984dcadf'),(16,4,'Unit Type','unitType','global','',0,'Dropdown','{\"options\":[{\"label\":\"Evidence\",\"value\":\"evidence\",\"default\":\"1\"},{\"label\":\"E-Learning\",\"value\":\"elearning\",\"default\":\"\"}]}','2017-10-24 09:47:09','2018-12-11 07:30:09','995ba33c-928b-43a5-8d39-53c4c36782ed'),(17,6,'Result Evidence','resultEvidence','global','',0,'Assets','{\"useSingleFolder\":\"1\",\"sources\":\"*\",\"defaultUploadLocationSource\":\"1\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"{author.id}\",\"restrictFiles\":\"\",\"allowedKinds\":[\"image\",\"pdf\"],\"limit\":\"\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2017-10-24 09:47:52','2018-12-12 07:11:46','e7ff470a-3e85-47be-9037-1508ea540121'),(18,4,'URL (e-learning)','unitUrl','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2017-10-24 09:50:33','2017-10-24 09:55:56','28e570b6-14a0-4b5e-be07-37e03f6b9b8f'),(19,4,'Unit Groups','moduleUnitGroups','global','',0,'Matrix','{\"maxBlocks\":null}','2017-10-24 09:55:27','2018-03-09 11:20:18','6f94bf9b-3168-437e-b8f2-7d082dcdd831'),(20,NULL,'Name','groupName','matrixBlockType:1','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2017-10-24 09:55:27','2018-03-09 11:20:19','45d289dd-249a-42e9-95ae-62ab979e8b02'),(25,NULL,'Unit Entries','unitEntries','matrixBlockType:1','',0,'Entries','{\"sources\":[\"section:7\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2017-10-24 10:13:39','2018-03-09 11:20:19','a773e69f-0bf1-46fd-ae75-5182be37772c'),(26,6,'Result Unit','resultUnit','global','',0,'Entries','{\"sources\":[\"section:7\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-24 10:47:23','2018-04-13 18:58:17','3103beed-feb4-4eed-b71a-68cb81d67f7e'),(27,6,'Result Score','resultScore','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2017-10-24 10:51:16','2018-04-13 18:57:05','5af516f7-8267-4333-b0d0-0ff4f5ee41bb'),(28,4,'Job Roles','moduleRoles','global','',0,'Categories','{\"source\":\"group:1\",\"limit\":\"\",\"selectionLabel\":\"\"}','2017-10-24 10:54:23','2017-10-26 12:53:07','f08f684f-1af1-417b-94f2-5df2f3ac7da6'),(29,6,'Result Status','resultStatus','global','',0,'Status','{\"statuses\":{\"col1\":{\"name\":\"Pending\",\"handle\":\"pending\",\"color\":\"#ffff00\",\"default\":\"1\"},\"1\":{\"name\":\"Failed\",\"handle\":\"failed\",\"color\":\"#ff0000\",\"default\":\"\"},\"0\":{\"name\":\"Endorsed\",\"handle\":\"endorsed\",\"color\":\"#008000\",\"default\":\"\"},\"2\":{\"name\":\"Active\",\"handle\":\"active\",\"color\":\"#000000\",\"default\":\"\"},\"3\":{\"name\":\"Complete\",\"handle\":\"complete\",\"color\":\"#008000\",\"default\":\"\"},\"4\":{\"name\":\"Blocked\",\"handle\":\"blocked\",\"color\":\"#000000\",\"default\":\"\"},\"5\":{\"name\":\"Draft\",\"handle\":\"draft\",\"color\":\"#0080c0\",\"default\":\"\"}}}','2017-10-24 11:50:38','2019-02-08 15:13:38','3ccae116-4500-45d0-a56a-024f2e8d4d03'),(30,2,'User Role','userRole','global','',0,'Categories','{\"source\":\"group:1\",\"limit\":\"\",\"selectionLabel\":\"\"}','2018-02-14 11:23:17','2018-02-14 11:24:28','dbbf1f49-3b86-4c6d-aa5a-4170483a35f1'),(31,3,'Team Description','teamDescription','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"4\"}','2018-02-14 11:29:48','2018-02-14 11:29:48','2722a41a-2635-4f3b-8ecb-d39db9efa049'),(32,4,'Module Description','moduleDescription','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-02-14 11:46:56','2018-02-14 11:46:56','d1739b26-92c4-4d41-8e2b-2064ddd0ae41'),(33,6,'Result Endorsed Date','resultEndorsedDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-02-14 11:59:46','2018-04-13 18:56:39','5dd21124-5f28-49d6-8f3e-d70184415478'),(37,4,'Unit Description','unitDescription','global','Add a short description of this unit',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-03-09 11:00:26','2018-05-30 09:26:25','98e01bd1-bcac-4523-8399-0c55010d13b6'),(38,4,'Module Completed Value','moduleCompletedValue','global','Enter the total value of the points needed to complete this module.',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-03-09 11:10:06','2018-03-09 11:12:13','c6890220-ae06-4dc6-a162-58ef3815e8c5'),(39,4,'Unit Value','unitValue','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-03-09 11:21:02','2018-03-09 11:21:02','c708fb49-2ead-4372-bb3f-c925d3ab9f41'),(40,5,'Test Questions','testQuestions','global','',0,'Matrix','{\"maxBlocks\":null}','2018-04-13 10:18:08','2018-04-13 18:13:59','e875ae7a-06f3-4063-a7c9-0b3fbb8f75b9'),(41,NULL,'Question','question','matrixBlockType:2','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','265e7462-688b-4c0b-a1b2-6f25d1b08525'),(42,NULL,'Answer','answer','matrixBlockType:2','',0,'Lightswitch','{\"default\":\"\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','9bb07531-d5ed-4c48-9a33-67ca0c17882b'),(43,NULL,'Asset','asset','matrixBlockType:2','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:7\"],\"defaultUploadLocationSource\":\"2\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"limit\":\"1\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','6f6554bf-571c-46f8-a4eb-37e34c65b1a8'),(44,NULL,'Question','question','matrixBlockType:3','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','29245631-dbe4-4182-85d0-ae4290604622'),(45,NULL,'Answers','answers','matrixBlockType:3','',0,'Table','{\"columns\":{\"col1\":{\"heading\":\"Answer\",\"handle\":\"answer\",\"width\":\"\",\"type\":\"singleline\"},\"col2\":{\"heading\":\"Correct\",\"handle\":\"correct\",\"width\":\"\",\"type\":\"checkbox\"}},\"defaults\":{\"row1\":{\"col1\":\"\",\"col2\":\"\"}}}','2018-04-13 10:18:09','2018-04-13 18:13:59','6e83784c-1c34-4973-9929-0307ffb6cff0'),(46,NULL,'Asset','asset','matrixBlockType:3','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:7\"],\"defaultUploadLocationSource\":\"2\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"limit\":\"\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','f523e5d2-5acc-48c3-ab1d-fba29e427581'),(47,NULL,'Question','question','matrixBlockType:4','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','74e30584-f92e-4d01-a638-7d173cc7b930'),(48,NULL,'Asset','asset','matrixBlockType:4','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:7\"],\"defaultUploadLocationSource\":\"2\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"limit\":\"\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','08a001af-8773-420d-ab49-41848341478c'),(49,5,'Test Pass Percent','testPassPercent','global','',0,'Number','{\"min\":\"0\",\"max\":\"100\",\"decimals\":\"0\"}','2018-04-13 10:24:11','2018-04-13 10:36:15','8a7a272d-f35d-4ec6-9c9a-571c7f0f40d7'),(50,5,'Attempt Answers','attemptAnswers','global','',0,'Matrix','{\"maxBlocks\":null}','2018-04-13 10:29:34','2018-04-13 18:20:05','4b25bd1b-f51c-4175-8db7-e871de866f2f'),(51,NULL,'Question ID','questionId','matrixBlockType:5','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-04-13 10:29:34','2018-04-13 18:20:05','3a779dd7-470c-4c09-9363-7f1be3a04a59'),(52,NULL,'Answer','answer','matrixBlockType:5','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-13 10:29:34','2018-04-13 18:20:05','c7ee8208-f166-48c8-b7c2-e8eab9555b64'),(53,NULL,'Correct','correct','matrixBlockType:5','',0,'Lightswitch','{\"default\":\"\"}','2018-04-13 10:29:34','2018-04-13 18:20:05','57ccb83f-5e54-480a-90b7-e80dfbc8cc94'),(55,5,'Attempt Unit','attemptUnit','global','',0,'Entries','{\"sources\":[\"section:7\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2018-04-13 10:30:46','2018-04-19 13:59:15','fbd50700-a63f-4a13-b729-1947510b8a14'),(56,NULL,'Question','question','matrixBlockType:5','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-13 18:20:05','2018-04-13 18:20:05','09efb818-9488-45a7-b860-1fc9d3c99155'),(58,6,'Result Attempts','resultAttempts','global','',0,'Entries','{\"sources\":[\"section:12\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-04-13 18:52:48','2018-04-27 19:38:25','6cb9aa27-e25b-4a44-8dca-f20fca5f3588'),(67,6,'Result Module','resultModule','global','',0,'Entries','{\"sources\":[\"section:6\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-04-19 14:25:30','2018-04-19 14:25:40','295e5043-483c-4ad9-bfaa-bac22b99dc19'),(68,4,'Module Expiry Days','moduleExpiryDays','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-04-19 16:07:42','2018-04-19 16:07:42','646454e2-66f9-4f03-bac7-b94a89aba17a'),(70,5,'Max Attempts','testMaxAttempts','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-04-27 19:31:41','2018-04-27 20:09:48','4209884c-57c0-48b6-bb15-06361f5e0182'),(71,9,'Scheme Name','schemeName','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-29 12:40:10','2018-08-23 14:40:08','0c1394ba-3007-4f8c-89cb-7164b2b3b463'),(72,10,'Date Format','dateFormat','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-29 12:41:15','2018-09-26 14:46:17','5cd2f261-b203-491f-9549-8d181170d03c'),(73,10,'Default Limit','defaultLimit','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-04-29 12:41:44','2018-09-26 14:46:12','444569cd-536b-42db-8c1a-01d52860bd86'),(74,4,'Unit Image','unitImage','global','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:7\"],\"defaultUploadLocationSource\":\"2\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"1\",\"allowedKinds\":[\"image\"],\"limit\":\"1\",\"viewMode\":\"list\",\"selectionLabel\":\"Add a unit image\"}','2018-05-02 10:29:20','2018-05-02 10:29:20','6616a98b-5530-40e0-93db-6e73e439e274'),(75,10,'Scheme Expiry Date','schemeExpiryDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-05-02 10:37:28','2018-09-26 14:47:29','6aa5a52d-d403-4d48-bb69-c949bf60a15e'),(76,2,'User Telephone','userTelephone','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-05-30 09:43:57','2018-05-31 13:41:33','e85a0c45-d4c3-4d25-81e6-80819d599872'),(77,2,'User Payments','userPayments','global','',0,'Matrix','{\"maxBlocks\":null}','2018-05-31 13:06:53','2018-05-31 13:06:53','7f1c4f49-327b-48eb-b631-5950532840c2'),(78,NULL,'Payer Email','payer_email','matrixBlockType:6','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-05-31 13:06:53','2018-05-31 13:06:53','70223568-9924-4df6-a581-7134a87dc443'),(79,NULL,'Payment Amount','mc_gross','matrixBlockType:6','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-05-31 13:06:54','2018-05-31 13:06:54','0507fd56-62a9-42b1-a4ce-7f5e221df8a2'),(80,NULL,'Transaction ID','txn_id','matrixBlockType:6','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-05-31 13:06:54','2018-05-31 13:06:54','c82b9786-ae99-4b9d-89ef-a97b92d4366d'),(82,10,'Individual Licence Days','individualLicenceDays','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-05-31 13:42:07','2018-09-26 14:47:19','2c306d61-76de-4616-9db0-1f7a27cd922f'),(84,2,'User Expiry Date','userExpiryDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-06-01 11:18:50','2018-06-01 11:18:50','041dc464-9e48-4a7d-9999-6cf36dd10aaa'),(86,6,'Result Notes','resultNotes','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"4\"}','2018-06-02 11:22:42','2018-06-02 11:22:42','53ded07f-6c81-4e7c-8e25-520b62d1a532'),(89,10,'Individual Licence PayPal Button','individualLicencePaypalButton','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"4\"}','2018-06-05 13:23:38','2018-09-26 14:46:33','82d74ce5-66da-4c2e-95f5-80488adc8406'),(90,8,'Report Type','reportType','global','',0,'Dropdown','{\"options\":[{\"label\":\"Users\",\"value\":\"users\",\"default\":\"\"},{\"label\":\"Results\",\"value\":\"results\",\"default\":\"\"}]}','2018-08-13 12:11:11','2018-08-20 15:41:51','1a27fa24-b0d3-491d-8b77-f1656c3afc77'),(91,8,'Report Companies','reportCompanies','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-08-13 12:11:30','2018-08-13 12:11:30','d4776dfe-b66f-4a88-931c-d2bebb2d68d1'),(92,8,'Report Teams','reportTeams','global','',0,'Entries','{\"sources\":[\"section:5\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-08-13 12:11:46','2018-08-13 12:11:46','abb92e86-cb1b-456e-a618-befafa8e8a28'),(93,8,'Report Modules','reportModules','global','',0,'Entries','{\"sources\":[\"section:6\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-08-13 12:11:59','2018-08-13 12:11:59','c2e23d12-1d3f-4cbd-a3cb-be22ea64bdca'),(94,8,'Report Roles','reportRoles','global','',0,'Categories','{\"source\":\"group:1\",\"limit\":\"\",\"selectionLabel\":\"\"}','2018-08-13 12:12:16','2018-08-13 12:41:43','2f940be6-d44c-47bf-95b6-392608ee2547'),(95,8,'Report All Companies','reportAllCompanies','global','',0,'Lightswitch','{\"default\":\"\"}','2018-08-13 12:12:32','2018-08-13 12:12:32','d500f1e4-fb5b-435a-af01-cc2402b4a8c3'),(96,8,'Report All Teams','reportAllTeams','global','',0,'Lightswitch','{\"default\":\"\"}','2018-08-13 12:12:41','2018-08-13 12:12:53','7b7af1dc-c01f-4f73-b3f4-a83ff572e941'),(97,8,'Report All Modules','reportAllModules','global','',0,'Lightswitch','{\"default\":\"\"}','2018-08-13 12:13:21','2018-08-13 12:13:21','c9d661c9-fd44-473b-a7ef-70f4bc6340ea'),(98,8,'Report All Roles','reportAllRoles','global','',0,'Lightswitch','{\"default\":\"\"}','2018-08-13 12:13:44','2018-08-13 12:41:35','27e57a2d-2938-41e8-ab58-72f377ca20c1'),(99,8,'Report Recipients','reportRecipients','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-13 12:14:16','2018-08-13 12:14:16','e0c4c343-2eda-4447-baf6-836f09723985'),(100,8,'Report Send Frequency','reportSendFrequency','global','',0,'Dropdown','{\"options\":[{\"label\":\"Never\",\"value\":\"never\",\"default\":\"\"},{\"label\":\"Weekly\",\"value\":\"weekly\",\"default\":\"\"},{\"label\":\"Monthly\",\"value\":\"monthly\",\"default\":\"\"}]}','2018-08-13 12:15:58','2018-08-13 13:08:11','dbedebea-c884-4f61-8f0f-ebc64b3a5ed2'),(101,8,'Report Send Value','reportSendValue','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-08-13 12:16:58','2018-08-13 12:16:58','c996fdb5-0318-4df3-87bd-b42b45db2cab'),(102,8,'Report Description','reportDescription','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"2\"}','2018-08-13 12:20:32','2018-08-13 12:23:06','d1300c08-840a-419d-b910-de865842ddd8'),(103,8,'Report Count','reportCount','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-08-13 12:20:53','2018-08-13 12:20:53','bebe1bc3-81ca-46c1-86ac-1ad88d0a0f92'),(104,8,'Report Last Sent Date','reportLastSentDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-08-13 12:21:06','2018-08-13 18:12:54','1a8a5dc7-66a0-4520-957d-7dec3aa3cf2f'),(105,8,'Report Data','reportData','global','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:60\"],\"defaultUploadLocationSource\":\"3\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"allowedKinds\":[\"text\"],\"limit\":\"\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-08-15 15:07:56','2018-08-15 15:30:00','c0866ad5-ca17-43d8-8a57-52ea3e84bbe2'),(106,8,'Report Result Expiry','reportResultExpiry','global','',0,'Dropdown','{\"options\":[{\"label\":\"0\",\"value\":\"0\",\"default\":\"\"},{\"label\":\"30\",\"value\":\"30\",\"default\":\"\"},{\"label\":\"90\",\"value\":\"90\",\"default\":\"\"},{\"label\":\"180\",\"value\":\"180\",\"default\":\"\"},{\"label\":\"365\",\"value\":\"365\",\"default\":\"\"},{\"label\":\"365+\",\"value\":\"\",\"default\":\"\"}]}','2018-08-20 15:35:57','2018-08-20 15:36:36','8200bbc7-18f0-49da-a017-2fb3a82ee950'),(107,9,'Home Slider','homeSlider','global','',0,'Matrix','{\"maxBlocks\":null}','2018-08-21 15:24:55','2018-08-21 15:27:31','4a4bc5cd-115e-4309-a51a-3e5095ceea90'),(108,NULL,'Title','slideTitle','matrixBlockType:7','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-21 15:24:55','2018-08-21 15:27:31','111e6e76-917a-43f6-aa70-c659bd131921'),(109,NULL,'Heading','slideHeading','matrixBlockType:7','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-21 15:24:55','2018-08-21 15:27:31','4978c00f-2afb-4601-83f2-12952bc08242'),(110,NULL,'Image','slideImage','matrixBlockType:7','',0,'Assets','{\"useSingleFolder\":\"1\",\"sources\":\"*\",\"defaultUploadLocationSource\":\"1\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"4\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"1\",\"allowedKinds\":[\"image\"],\"limit\":\"1\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-08-21 15:24:55','2018-08-21 15:27:31','89bc5035-7e58-43a7-a7e5-bcaa9de73d4a'),(111,9,'Theme Navigation Public','themeNavigationPublic','global','',0,'Entries','{\"sources\":[\"section:14\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-08-21 15:37:05','2018-12-11 08:10:22','5b3dd4d1-e38c-4ffd-a19d-981f6331ca88'),(112,1,'Content','pageContent','global','',0,'Matrix','{\"maxBlocks\":null}','2018-08-21 15:46:47','2018-11-23 15:20:10','b8c430cf-c3d3-4d15-a8a8-21eb2fb94ace'),(113,NULL,'Title','columnTitle','matrixBlockType:8','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-21 15:46:47','2018-11-23 15:20:11','56f0758a-86e6-4c78-ae70-e09843a275a2'),(114,NULL,'HTML','columnHtml','matrixBlockType:8','',0,'RichText','{\"configFile\":\"Standard.json\",\"availableAssetSources\":\"*\",\"availableTransforms\":\"*\",\"cleanupHtml\":\"\",\"purifyHtml\":\"\",\"purifierConfig\":\"\",\"columnType\":\"text\"}','2018-08-21 15:46:47','2018-11-23 15:20:11','9a8e1aa6-c705-41e6-ab80-8c987022d24b'),(115,1,'Image','pageImage','global','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:67\"],\"defaultUploadLocationSource\":\"4\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"1\",\"allowedKinds\":[\"image\"],\"limit\":\"1\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-08-21 15:47:47','2018-08-21 15:47:47','5d8461b5-aba0-482c-a677-b59c1ec759ab'),(116,9,'Scheme Description','schemeDescription','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-21 15:48:47','2018-08-23 14:39:54','f25d1178-ab4c-4b7b-bf06-b381c58c5df1'),(117,9,'Scheme Logo','schemeLogo','global','',0,'Assets','{\"useSingleFolder\":\"1\",\"sources\":[\"folder:67\"],\"defaultUploadLocationSource\":\"3\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"4\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"limit\":\"1\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-08-23 14:39:40','2018-08-23 14:39:40','835b518c-d291-4c01-a514-025ab5475788'),(118,9,'Theme Color Primary','themeColorPrimary','global','',0,'Color',NULL,'2018-08-23 18:51:41','2018-08-23 18:51:58','4f290131-f1cf-4d75-aea2-32cb5522a36e'),(119,9,'Theme Color Secondary','themeColorSecondary','global','',0,'Color',NULL,'2018-08-23 18:52:13','2018-08-23 18:52:13','45b6ffab-ea34-4493-8170-ad3812081dc6'),(121,2,'User Start Date','userStartDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-08-26 14:02:35','2018-08-26 14:02:35','aa4a666c-db2c-4515-b356-344c43b82ca1'),(122,2,'User Date of Birth','userDateOfBirth','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-08-26 14:02:56','2018-08-26 14:31:53','f0039d1f-5cfd-4bf6-970e-5592f86712de'),(123,2,'User Address','userAddress','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"2\"}','2018-08-26 14:03:09','2018-08-26 14:03:09','027f626e-2eaa-4fca-9665-c8812bce20a9'),(124,2,'User Custom Fields','userCustomFields','global','',0,'Matrix','{\"maxBlocks\":null}','2018-08-26 14:04:54','2019-02-01 09:58:38','53b87ea0-021a-4671-bfbd-8000933bcf0e'),(125,NULL,'Custom Name','customName','matrixBlockType:9','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-26 14:04:54','2019-02-01 09:58:38','03b567a4-41fe-44fc-8b41-a896abbe328f'),(126,NULL,'Custom Value','customValue','matrixBlockType:9','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-26 14:04:54','2019-02-01 09:58:38','e4cbe147-47ff-4ad4-845e-cc788f8b8829'),(127,10,'User Edit Custom Fields','userEditCustomFields','global','Enter a name (lowercase, no spaces) and label text for each custom user field.',0,'Table','{\"columns\":{\"col1\":{\"heading\":\"Name\",\"handle\":\"name\",\"width\":\"\",\"type\":\"singleline\"},\"col2\":{\"heading\":\"Label\",\"handle\":\"label\",\"width\":\"\",\"type\":\"singleline\"},\"col3\":{\"heading\":\"User Edit\",\"handle\":\"userEdit\",\"width\":\"\",\"type\":\"checkbox\"}},\"defaults\":{\"row1\":{\"col1\":\"\",\"col2\":\"\",\"col3\":\"\"}}}','2018-08-26 14:06:22','2019-02-01 09:57:54','f4d82e76-729e-45e9-9f95-6130a87ce30e'),(128,2,'User Company','userCompany','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2018-08-26 15:13:40','2018-08-26 15:50:25','4ac0fb06-5ba4-4129-ad0a-664727323d89'),(129,2,'Manager Level','managerLevel','global','',0,'Dropdown','{\"options\":[{\"label\":\"Level One\",\"value\":\"1\",\"default\":\"\"},{\"label\":\"Level Two\",\"value\":\"2\",\"default\":\"\"},{\"label\":\"Level Three\",\"value\":\"3\",\"default\":\"\"},{\"label\":\"Level Four\",\"value\":\"4\",\"default\":\"\"}]}','2018-08-26 16:01:28','2018-08-26 16:01:28','af392c27-30aa-4dde-a30c-fd69e3dd665e'),(130,4,'Unit Endorsement Manager Level','unitEndorsementManagerLevel','global','',0,'Dropdown','{\"options\":[{\"label\":\"Level One\",\"value\":\"1\",\"default\":\"\"},{\"label\":\"Level Two\",\"value\":\"2\",\"default\":\"\"},{\"label\":\"Level Three\",\"value\":\"3\",\"default\":\"\"},{\"label\":\"Level Four\",\"value\":\"4\",\"default\":\"\"}]}','2018-08-26 16:03:44','2018-08-26 16:03:44','d09c7ee9-90ad-4c7e-a8ec-a84e5f3457f0'),(131,10,'Scheme Teams','schemeTeams','global','Enable teams for this scheme?',0,'Lightswitch','{\"default\":\"1\"}','2018-08-29 12:54:17','2018-09-26 14:47:04','46c15055-2508-42c0-8e05-e43cb4eb99b6'),(132,2,'Manager Read Only','managerReadOnly','global','',0,'Lightswitch','{\"default\":\"1\"}','2018-08-29 14:53:21','2018-10-15 13:55:04','8df69537-0612-4b94-af60-c27617fddb4d'),(141,10,'Scheme Email Domain','schemeEmailDomain','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-09-10 16:15:52','2018-09-26 14:48:14','a1d57a54-2461-4d2d-8895-d743c1d3bb89'),(142,6,'Result Start date','resultStartDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-09-18 16:30:42','2018-09-18 16:30:42','f7a6d059-9c07-4f32-a734-4bd9b5f2e210'),(143,6,'Result Finish Date','resultFinishDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-09-18 16:31:08','2018-09-18 16:31:08','c29bb59d-f545-409b-be92-c9b57e8cf603'),(144,6,'Result Location','resultLocation','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-09-18 16:31:16','2018-09-18 16:31:16','d363cf24-3e53-4eea-b5f4-e9c72a3e9f65'),(145,10,'Individual Company','individualCompany','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2018-09-26 14:40:11','2018-09-26 14:47:14','cbe0ac27-ad35-410a-bd25-9c4d25fe0ba9'),(146,6,'Result Value','resultValue','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-09-26 14:41:11','2018-09-26 14:41:11','a1d83c2a-d7f2-45d2-8430-7e2efd8d12ec'),(147,11,'Legacy Company ID','legacyCompanyId','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-10-04 15:30:44','2018-10-04 15:30:44','3f2f2e5a-9c13-4199-aaa4-0d0d1eb8abfc'),(148,11,'Legacy Email','legacyEmail','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-10-04 15:30:56','2018-10-04 15:30:56','85884bff-7c6a-4759-a769-d3f056254b7b'),(149,11,'Legacy Group','legacyGroup','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-10-04 15:31:04','2018-10-04 15:31:04','b7d6d158-4e92-4be7-ab53-c7e655c7e062'),(150,11,'Legacy ID','legacyId','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-10-04 15:31:18','2018-10-04 15:31:18','dc88c84b-17f2-4b8b-8cc6-124b5115c9cc'),(151,11,'Legacy Job Role ID','legacyJobRoleId','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-10-04 15:31:30','2018-10-04 15:31:30','bb511299-1a44-4db4-b8f0-32035ca24b2d'),(152,11,'Legacy Parent ID','legacyParentId','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-10-04 15:32:07','2018-10-04 15:32:07','3d52518d-7b85-4ef8-af1c-fb8a813e0cce'),(153,4,'Result Display Location','resultDisplayLocation','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:46:47','2018-10-16 12:48:14','8669508d-c032-489a-bcd2-7f9e2f0fffc5'),(154,4,'Result Display Start Date','resultDisplayStartDate','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:46:59','2018-10-16 12:48:21','8e065945-beff-47e0-a90b-2d7a57b91899'),(155,4,'Result Display Finish Date','resultDisplayFinishDate','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:47:14','2018-10-16 12:52:32','93485201-c508-48f9-a114-db4fb3558c38'),(156,4,'Result Display Endorsed Date','resultDisplayEndorsedDate','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:47:32','2018-10-16 12:47:41','03884584-2dbd-48fb-90e9-2be46d75b3b8'),(157,4,'Result Display Type','resultDisplayType','global','',0,'Lightswitch','{\"default\":\"1\"}','2018-10-16 12:49:34','2018-10-16 12:52:43','2d42f3fb-897c-4246-8d75-1774e8ef2cc8'),(158,4,'Result Display Value','resultDisplayValue','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:49:44','2018-10-16 12:49:44','63152307-1708-49e9-9d3c-3cb9275371b4'),(159,4,'Result Display Expiry Date','resultDisplayExpiryDate','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:49:57','2018-10-16 12:49:57','d3b38d67-f2d6-4349-9ffc-0d39810c1028'),(160,4,'Result Display Evidence','resultDisplayEvidence','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:50:12','2018-10-16 12:50:12','6ed0db07-3bf7-438a-80c4-0d64a6542446'),(161,4,'Result Display Status','resultDisplayStatus','global','',0,'Lightswitch','{\"default\":\"1\"}','2018-10-16 12:51:48','2018-10-16 12:52:52','5cee14f9-7873-40b9-b788-aab8210d1e0e'),(162,4,'Result Display Hours','resultDisplayHours','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:58:47','2018-10-16 12:58:47','563ab173-4e2b-4622-b7dd-284d3df35f95'),(163,6,'Result Hours','resultHours','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-10-16 13:16:00','2018-10-16 13:26:27','06f9a436-94f4-4170-80ec-6e08d13115ee'),(164,3,'Company Secondary Managers','companySecondaryManagers','global','',0,'Users','{\"sources\":[\"group:2\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-10-16 13:47:14','2018-10-16 13:47:37','8a8d1ecb-3c6c-4ade-a2e7-8e6996e7fe86'),(166,4,'Module Group','moduleGroup','global','',0,'Categories','{\"source\":\"group:2\",\"limit\":\"1\",\"selectionLabel\":\"\"}','2018-11-26 17:52:45','2018-11-26 17:52:45','fbd8c758-3ca5-4ccb-8452-d1d82d3f97e4'),(167,10,'Column Layout','columnLayout','global','',0,'SuperTable','{\"columns\":{\"168\":{\"width\":\"\"},\"169\":{\"width\":\"\"},\"new3\":{\"width\":\"\"},\"new4\":{\"width\":\"\"},\"new5\":{\"width\":\"\"},\"new6\":{\"width\":\"\"}},\"fieldLayout\":\"table\",\"staticField\":null,\"selectionLabel\":\"Add a row\",\"maxRows\":null,\"minRows\":null}','2018-12-06 17:37:16','2019-04-25 13:26:06','bfce8aaa-028f-4d73-8f1d-20ba8f5a871b'),(168,NULL,'Field Type','fieldType','superTableBlockType:2','',0,'Dropdown','{\"options\":[{\"label\":\"Type\",\"value\":\"unitType\",\"default\":\"\"},{\"label\":\"Value\",\"value\":\"unitValue\",\"default\":\"\"},{\"label\":\"Status\",\"value\":\"resultStatus\",\"default\":\"\"},{\"label\":\"Endorsed Date\",\"value\":\"resultEndorsedDate\",\"default\":\"\"},{\"label\":\"Expiry Date\",\"value\":\"resultExpiryDate\",\"default\":\"\"},{\"label\":\"Start Date\",\"value\":\"resultStartDate\",\"default\":\"\"},{\"label\":\"Finish Date\",\"value\":\"resultFinishDate\",\"default\":\"\"},{\"label\":\"Location\",\"value\":\"resultLocation\",\"default\":\"\"},{\"label\":\"Hours\",\"value\":\"resultHours\",\"default\":\"\"},{\"label\":\"Evidence\",\"value\":\"resultEvidence\",\"default\":\"\"},{\"label\":\"Custom\",\"value\":\"resultCustom\",\"default\":\"\"}]}','2018-12-06 17:37:16','2019-04-25 13:26:06','8fec0242-3542-431e-8a9a-d2d86b3e3b06'),(169,NULL,'Field Label','fieldLabel','superTableBlockType:2','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-12-06 17:37:16','2019-04-25 13:26:06','19794a8d-aa57-4cea-9297-7c4ccfb2d561'),(170,2,'User Dummy Email','userDummyEmail','global','',0,'Lightswitch','{\"default\":\"\"}','2018-12-11 07:10:42','2018-12-11 07:10:42','6e432459-1ae7-4301-a7cd-e23c574bd569'),(171,3,'Include Add Achievement? ','addAchievement','global','',0,'Lightswitch','{\"default\":\"\"}','2018-12-11 07:49:56','2018-12-11 07:54:55','c3c65d33-35c6-416d-9e88-c5a678f09161'),(172,9,'Theme Navigation Private','themeNavigationPrivate','global','',0,'Entries','{\"sources\":[\"section:14\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-12-11 08:09:02','2018-12-11 08:09:02','6fa87955-dc3d-4695-9142-9250995d8cd7'),(173,2,'User Licence Source','userLicenceSource','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-12-11 23:46:07','2018-12-11 23:46:07','27f36190-a4c4-4f11-b546-5e4ffb5efc06'),(174,6,'Result Search','resultSearch','global','',0,'PreparseField_Preparse','{\"fieldTwig\":\"{% if entry.type == \'unitResult\' %}{{ entry.resultUnit.first.title|raw }}{% else %}{{ entry.resultModule.first.title|raw }}{% endif %} \\r\\n{{ entry.author.userTeam.first.title|raw }} {{ entry.au\",\"columnType\":\"text\",\"decimals\":\"0\",\"parseBeforeSave\":\"\",\"parseOnMove\":\"\",\"showField\":\"1\",\"allowSelect\":\"\"}','2018-12-12 07:22:19','2018-12-12 07:24:02','2078306f-b4b1-40bc-9837-4604f48a4939'),(175,2,'User Search','userSearch','global','',0,'PreparseField_Preparse','{\"fieldTwig\":\"{{ user.userTeam.first.title|raw  }} {{ user.userTeam.first.teamCompany.first.title|raw }}\",\"columnType\":\"text\",\"decimals\":\"0\",\"parseBeforeSave\":\"\",\"parseOnMove\":\"\",\"showField\":\"\",\"allowSelect\":\"\"}','2018-12-12 07:22:45','2018-12-12 07:24:41','bb9d49ae-464b-4650-b8f1-fa4f12b3c769'),(176,10,'Scheme Test Email Address','schemeTestEmailAddress','global','In dev mode all lantra notifications will be sent to this address. Separate emails with commas.',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2019-01-16 10:01:46','2019-01-16 10:40:05','ad328aab-5b8a-478e-bb71-22fa0f8a62b9'),(177,6,'Result Comments','resultComments','global','',0,'SuperTable','{\"columns\":{\"178\":{\"width\":\"\"},\"179\":{\"width\":\"\"},\"180\":{\"width\":\"\"},\"191\":{\"width\":\"\"}},\"fieldLayout\":\"table\",\"staticField\":null,\"selectionLabel\":\"Add a row\",\"maxRows\":null,\"minRows\":null}','2019-01-18 16:57:15','2019-02-08 11:24:43','61e98c20-6536-4fb2-8420-7b21d34b4dd5'),(178,NULL,'User','user','superTableBlockType:3','',0,'Users','{\"sources\":\"*\",\"limit\":\"1\",\"selectionLabel\":\"\"}','2019-01-18 16:57:15','2019-02-08 11:24:44','0c515fa0-cbdd-4bd9-8267-917a447e5702'),(179,NULL,'Comment','comment','superTableBlockType:3','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2019-01-18 16:57:15','2019-02-08 11:24:44','3c50a00d-c61b-4018-bed4-f1685a215e01'),(180,NULL,'Date','date','superTableBlockType:3','',0,'Date','{\"minuteIncrement\":\"30\",\"showTime\":1,\"showDate\":1}','2019-01-18 16:57:15','2019-02-08 11:24:44','cea55199-bbc5-4529-8451-c12a00ff1080'),(181,4,'Hide Add Achievement?','addAchievementHide','global','',0,'Lightswitch','{\"default\":\"\"}','2019-01-21 17:37:19','2019-01-21 17:37:19','3bfea235-c57e-4998-b079-8c15a1ebbf0e'),(182,10,'Scheme User Read Only','schemeUserReadOnly','global','',0,'Lightswitch','{\"default\":\"\"}','2019-01-21 18:01:03','2019-01-21 18:01:03','4ac70c98-b02f-41d0-95c0-ba265b575521'),(184,10,'User Edit Date of Birth','userEditDob','global','',0,'Lightswitch','{\"default\":\"1\"}','2019-02-01 09:35:31','2019-02-01 09:35:31','307b59a1-03b8-4175-ba32-e9a2a1f252fc'),(185,10,'User Edit Address','userEditAddress','global','',0,'Lightswitch','{\"default\":\"1\"}','2019-02-01 09:35:55','2019-02-01 09:35:55','dff2c061-6d4b-45fd-9a9c-d4e916e33aa5'),(186,10,'User Edit Photo','userEditPhoto','global','',0,'Lightswitch','{\"default\":\"1\"}','2019-02-01 09:38:00','2019-02-01 09:38:00','1d9a728a-9968-48ae-8f0b-69aff2ff8cad'),(187,10,'User Edit Name','userEditName','global','',0,'Lightswitch','{\"default\":\"1\"}','2019-02-01 09:38:19','2019-02-01 09:38:19','3da407f2-a88c-4011-b2d6-8cb884f8dd22'),(188,10,'User Edit Email','userEditEmail','global','',0,'Lightswitch','{\"default\":\"\"}','2019-02-01 09:42:53','2019-02-01 09:42:53','a137f970-729d-41ab-8f7c-9ff107211b16'),(189,10,'User Edit Telephone','userEditTelephone','global','',0,'Lightswitch','{\"default\":\"1\"}','2019-02-01 09:45:16','2019-02-01 09:45:16','147bb51f-ac4e-43ae-be3e-733df273579b'),(190,10,'User Edit Role','userEditRole','global','',0,'Lightswitch','{\"default\":\"\"}','2019-02-01 09:45:53','2019-02-01 09:45:53','2713a14e-549b-45be-89ab-832233315a39'),(191,NULL,'Read','read','superTableBlockType:3','',0,'Lightswitch','{\"default\":\"\"}','2019-02-08 10:57:47','2019-02-08 11:24:44','974df618-fa82-42bd-9ad4-612f8f4a985c'),(192,4,'Unit Files','unitFiles','global','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:7\"],\"defaultUploadLocationSource\":\"1\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"limit\":\"\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2019-02-11 12:37:20','2019-02-11 12:37:20','1e5a2205-0d2d-4f4c-b087-a5f706ac0449'),(193,2,'User Company Name','userCompanyName','global','',0,'PreparseField_Preparse','{\"fieldTwig\":\"{{ user.userCompany | length ? user.userCompany.first().title }}\",\"columnType\":\"text\",\"decimals\":\"0\",\"parseBeforeSave\":\"\",\"parseOnMove\":\"\",\"showField\":\"1\",\"allowSelect\":\"\"}','2019-02-11 13:39:42','2019-02-11 13:49:45','d1847ab4-a98b-4069-b9db-3ef5cc3124f0'),(194,4,'Unit Heading','unitHeading','global',NULL,1,'PlainText','{\"multiline\":1,\"initialRows\":4}','2019-04-25 13:25:42','2019-04-25 13:25:42','f2ab3047-5922-481b-a6bc-d1ced215dc10'),(195,NULL,'Field Manager Only','fieldManagerOnly','superTableBlockType:2','',0,'Lightswitch','{\"default\":\"\"}','2019-04-25 13:26:06','2019-04-25 13:26:06','31a1729c-6625-4e9e-9ffe-3fcce0da9bbd'),(196,NULL,'Field Custom Key','fieldCustomKey','superTableBlockType:2','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2019-04-25 13:26:06','2019-04-25 13:26:06','74329152-64bf-43a9-95b3-0458edd36e4f'),(197,NULL,'Field Custom Type','fieldCustomType','superTableBlockType:2','',0,'Dropdown','{\"options\":[{\"label\":\"Text\",\"value\":\"text\",\"default\":\"1\"},{\"label\":\"Dropdown\",\"value\":\"dropdown\",\"default\":\"\"}]}','2019-04-25 13:26:07','2019-04-25 13:26:07','6cb593e0-eb38-45a0-bd9b-22a23586dfbe'),(198,NULL,'Field Custom Options','fieldCustomOptions','superTableBlockType:2','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2019-04-25 13:26:07','2019-04-25 13:26:07','a37728d1-3a88-46ca-b9d5-ec79b66ba635'),(199,6,'Result Custom','resultCustom','global','',0,'SuperTable','{\"columns\":{\"new1\":{\"width\":\"\"},\"new2\":{\"width\":\"\"}},\"fieldLayout\":\"table\",\"staticField\":null,\"selectionLabel\":\"Add a row\",\"maxRows\":null,\"minRows\":null}','2019-04-25 13:26:07','2019-04-25 13:26:07','a75d2fa6-54eb-4155-9483-d7729fb83004'),(200,NULL,'Custom Key','customKey','superTableBlockType:4','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2019-04-25 13:26:07','2019-04-25 13:26:07','5f11fa33-3356-44d4-ad91-0b9ca0ad5f6b'),(201,NULL,'Custom Value','customValue','superTableBlockType:4','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2019-04-25 13:26:07','2019-04-25 13:26:07','70a4b8f2-f454-40e6-82d7-ff577995e2a4');
/*!40000 ALTER TABLE `craft_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_globalsets`
--

DROP TABLE IF EXISTS `craft_globalsets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_globalsets` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fieldLayoutId` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_globalsets_name_unq_idx` (`name`),
  UNIQUE KEY `craft_globalsets_handle_unq_idx` (`handle`),
  KEY `craft_globalsets_fieldLayoutId_fk` (`fieldLayoutId`),
  CONSTRAINT `craft_globalsets_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `craft_globalsets_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_globalsets`
--

LOCK TABLES `craft_globalsets` WRITE;
/*!40000 ALTER TABLE `craft_globalsets` DISABLE KEYS */;
INSERT INTO `craft_globalsets` VALUES (488,'Scheme','globalsScheme',275,'2018-04-26 16:31:48','2019-02-01 09:49:13','c802382c-aba0-4067-ba99-d7b7e8d1417c'),(489,'System','globalsSystem',138,'2018-04-29 12:39:32','2018-04-29 12:42:06','d5e126b7-81ae-498b-9410-8e5f9b934ad1'),(1079,'Theme','globalsTheme',268,'2018-08-21 15:22:30','2019-01-21 16:04:08','a1969b12-a29f-4cbc-ad95-96798d730775'),(3266,'User Profile','userProfile',276,'2019-02-01 09:43:26','2019-02-01 09:55:55','0d172326-6782-4d9c-beb4-743322431b06');
/*!40000 ALTER TABLE `craft_globalsets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_import_entries`
--

DROP TABLE IF EXISTS `craft_import_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_import_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `historyId` int(11) DEFAULT NULL,
  `entryId` int(11) DEFAULT NULL,
  `versionId` int(11) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_import_entries_historyId_fk` (`historyId`),
  KEY `craft_import_entries_entryId_fk` (`entryId`),
  KEY `craft_import_entries_versionId_fk` (`versionId`),
  CONSTRAINT `craft_import_entries_entryId_fk` FOREIGN KEY (`entryId`) REFERENCES `craft_entries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_import_entries_historyId_fk` FOREIGN KEY (`historyId`) REFERENCES `craft_import_history` (`id`) ON DELETE SET NULL,
  CONSTRAINT `craft_import_entries_versionId_fk` FOREIGN KEY (`versionId`) REFERENCES `craft_entryversions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_import_entries`
--

LOCK TABLES `craft_import_entries` WRITE;
/*!40000 ALTER TABLE `craft_import_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_import_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_import_history`
--

DROP TABLE IF EXISTS `craft_import_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_import_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rows` int(10) DEFAULT NULL,
  `behavior` enum('append','replace','delete') COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('started','finished','reverted') COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_import_history_userId_fk` (`userId`),
  CONSTRAINT `craft_import_history_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_import_history`
--

LOCK TABLES `craft_import_history` WRITE;
/*!40000 ALTER TABLE `craft_import_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_import_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_import_log`
--

DROP TABLE IF EXISTS `craft_import_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_import_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `historyId` int(11) DEFAULT NULL,
  `line` int(10) DEFAULT NULL,
  `errors` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_import_log_historyId_fk` (`historyId`),
  CONSTRAINT `craft_import_log_historyId_fk` FOREIGN KEY (`historyId`) REFERENCES `craft_import_history` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_import_log`
--

LOCK TABLES `craft_import_log` WRITE;
/*!40000 ALTER TABLE `craft_import_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_import_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_info`
--

DROP TABLE IF EXISTS `craft_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `schemaVersion` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `edition` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `siteName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `siteUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `on` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `maintenance` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_info`
--

LOCK TABLES `craft_info` WRITE;
/*!40000 ALTER TABLE `craft_info` DISABLE KEYS */;
INSERT INTO `craft_info` VALUES (1,'2.6.3015','2.6.14',2,'Lantra CPD','/','UTC',1,0,'2017-10-23 13:26:41','2019-04-25 13:25:42','0058b800-0ac6-4829-83e2-aa8d406795bd');
/*!40000 ALTER TABLE `craft_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_locales`
--

DROP TABLE IF EXISTS `craft_locales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_locales` (
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`locale`),
  KEY `craft_locales_sortOrder_idx` (`sortOrder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_locales`
--

LOCK TABLES `craft_locales` WRITE;
/*!40000 ALTER TABLE `craft_locales` DISABLE KEYS */;
INSERT INTO `craft_locales` VALUES ('en_gb',1,'2017-10-23 13:26:41','2017-10-23 13:26:41','c7a0c91d-d2b7-44f9-bd1e-12493edec567');
/*!40000 ALTER TABLE `craft_locales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_mailer_log`
--

DROP TABLE IF EXISTS `craft_mailer_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_mailer_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `htmlBody` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('finished','running','failed') COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateFinished` datetime DEFAULT NULL,
  `success` int(10) DEFAULT NULL,
  `errors` text COLLATE utf8_unicode_ci,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_mailer_log`
--

LOCK TABLES `craft_mailer_log` WRITE;
/*!40000 ALTER TABLE `craft_mailer_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_mailer_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_mailer_scheduler`
--

DROP TABLE IF EXISTS `craft_mailer_scheduler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_mailer_scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `htmlBody` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('finished','running','failed') COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateFinished` datetime DEFAULT NULL,
  `success` int(10) DEFAULT NULL,
  `errors` text COLLATE utf8_unicode_ci,
  `postData` text COLLATE utf8_unicode_ci,
  `dateToSend` datetime DEFAULT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_mailer_scheduler`
--

LOCK TABLES `craft_mailer_scheduler` WRITE;
/*!40000 ALTER TABLE `craft_mailer_scheduler` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_mailer_scheduler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_matrixblocks`
--

DROP TABLE IF EXISTS `craft_matrixblocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_matrixblocks` (
  `id` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `fieldId` int(11) NOT NULL,
  `typeId` int(11) DEFAULT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `ownerLocale` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_matrixblocks_ownerId_idx` (`ownerId`),
  KEY `craft_matrixblocks_fieldId_idx` (`fieldId`),
  KEY `craft_matrixblocks_typeId_idx` (`typeId`),
  KEY `craft_matrixblocks_sortOrder_idx` (`sortOrder`),
  KEY `craft_matrixblocks_ownerLocale_fk` (`ownerLocale`),
  CONSTRAINT `craft_matrixblocks_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixblocks_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixblocks_ownerId_fk` FOREIGN KEY (`ownerId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixblocks_ownerLocale_fk` FOREIGN KEY (`ownerLocale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `craft_matrixblocks_typeId_fk` FOREIGN KEY (`typeId`) REFERENCES `craft_matrixblocktypes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixblocks`
--

LOCK TABLES `craft_matrixblocks` WRITE;
/*!40000 ALTER TABLE `craft_matrixblocks` DISABLE KEYS */;
INSERT INTO `craft_matrixblocks` VALUES (1087,1086,112,8,1,NULL,'2018-08-23 14:29:32','2018-12-07 10:17:14','b482ceb6-1f39-4208-ba81-48d23c40fb7c'),(1088,1086,112,8,2,NULL,'2018-08-23 14:29:32','2018-12-07 10:17:14','1965972d-6c42-43f0-9682-6b455076ec1e'),(1091,1079,107,7,1,NULL,'2018-08-23 14:37:44','2019-02-21 14:36:13','b8c2dceb-df37-4404-a531-8c0431ad5d16'),(1620,1090,112,8,1,NULL,'2018-11-23 11:08:56','2019-01-18 13:30:12','1c74cd76-2296-4bf3-8b8e-00ea1c55b601'),(2485,1,124,9,1,NULL,'2019-01-16 11:52:08','2019-01-24 10:36:07','64403ad4-2148-4379-bacd-2a91b985957c'),(3267,3266,124,9,1,NULL,'2019-02-01 09:51:20','2019-02-01 09:51:20','3e2c7ba8-331f-432f-911a-2853303bd05b');
/*!40000 ALTER TABLE `craft_matrixblocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_matrixblocktypes`
--

DROP TABLE IF EXISTS `craft_matrixblocktypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_matrixblocktypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldId` int(11) NOT NULL,
  `fieldLayoutId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_matrixblocktypes_name_fieldId_unq_idx` (`name`,`fieldId`),
  UNIQUE KEY `craft_matrixblocktypes_handle_fieldId_unq_idx` (`handle`,`fieldId`),
  KEY `craft_matrixblocktypes_fieldId_fk` (`fieldId`),
  KEY `craft_matrixblocktypes_fieldLayoutId_fk` (`fieldLayoutId`),
  CONSTRAINT `craft_matrixblocktypes_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixblocktypes_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixblocktypes`
--

LOCK TABLES `craft_matrixblocktypes` WRITE;
/*!40000 ALTER TABLE `craft_matrixblocktypes` DISABLE KEYS */;
INSERT INTO `craft_matrixblocktypes` VALUES (1,19,74,'Unit Group','unitGroup',1,'2017-10-24 09:55:27','2018-03-09 11:20:19','79541871-603b-4a9c-871d-c5668970f9e0'),(2,40,94,'True False','trueFalse',1,'2018-04-13 10:18:09','2018-04-13 18:13:59','cc1d2063-7fd4-4e67-b9be-33cdc205890d'),(3,40,95,'Choices','choices',2,'2018-04-13 10:18:09','2018-04-13 18:13:59','63a61dbd-d2dd-49bc-9e8a-6b45a2f698bc'),(4,40,96,'Text','text',3,'2018-04-13 10:18:09','2018-04-13 18:13:59','656f85bc-0a54-422d-a9a3-48d536fc7aa3'),(5,50,97,'Answer','answer',1,'2018-04-13 10:29:34','2018-04-13 18:20:05','c4592d20-fbfb-48ba-bb58-796a7e3ba778'),(6,77,147,'PayPal','paypal',1,'2018-05-31 13:06:53','2018-05-31 13:06:54','a00978f4-c38c-4cb9-8379-855950bf682b'),(7,107,171,'Slide','slide',1,'2018-08-21 15:24:55','2018-08-21 15:27:32','31396686-93c4-4dbe-a8c9-9328d191a511'),(8,112,247,'Columns','columns',1,'2018-08-21 15:46:47','2018-11-23 15:20:11','e21ca603-4cc1-4573-9a88-a769b8a0d695'),(9,124,277,'User Custom Field','userCustomField',1,'2018-08-26 14:04:54','2019-02-01 09:58:38','d9a83f47-4dd1-4c78-830c-73f6be0bdebe');
/*!40000 ALTER TABLE `craft_matrixblocktypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_matrixcontent_attemptanswers`
--

DROP TABLE IF EXISTS `craft_matrixcontent_attemptanswers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_matrixcontent_attemptanswers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_answer_questionId` int(10) unsigned DEFAULT '0',
  `field_answer_answer` text COLLATE utf8_unicode_ci,
  `field_answer_correct` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_answer_question` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_matrixcontent_attemptanswers_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_matrixcontent_attemptanswers_locale_idx` (`locale`),
  CONSTRAINT `craft_matrixcontent_attemptanswers_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixcontent_attemptanswers_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixcontent_attemptanswers`
--

LOCK TABLES `craft_matrixcontent_attemptanswers` WRITE;
/*!40000 ALTER TABLE `craft_matrixcontent_attemptanswers` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_matrixcontent_attemptanswers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_matrixcontent_homeslider`
--

DROP TABLE IF EXISTS `craft_matrixcontent_homeslider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_matrixcontent_homeslider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_slide_slideTitle` text COLLATE utf8_unicode_ci,
  `field_slide_slideHeading` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_matrixcontent_homeslider_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_matrixcontent_homeslider_locale_fk` (`locale`),
  CONSTRAINT `craft_matrixcontent_homeslider_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixcontent_homeslider_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixcontent_homeslider`
--

LOCK TABLES `craft_matrixcontent_homeslider` WRITE;
/*!40000 ALTER TABLE `craft_matrixcontent_homeslider` DISABLE KEYS */;
INSERT INTO `craft_matrixcontent_homeslider` VALUES (1,1091,'en_gb','Welcome to Lantra CPD','Support your own staff and non-employed individuals across your entire workforce.','2018-08-23 14:37:44','2019-02-21 14:36:13','e23f28ca-8240-4034-b96d-4d6489a026b6');
/*!40000 ALTER TABLE `craft_matrixcontent_homeslider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_matrixcontent_moduleunitgroups`
--

DROP TABLE IF EXISTS `craft_matrixcontent_moduleunitgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_matrixcontent_moduleunitgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_unitGroup_groupName` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_matrixcontent_moduleunitgroups_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_matrixcontent_moduleunitgroups_locale_idx` (`locale`),
  CONSTRAINT `craft_matrixcontent_moduleunitgroups_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixcontent_moduleunitgroups_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixcontent_moduleunitgroups`
--

LOCK TABLES `craft_matrixcontent_moduleunitgroups` WRITE;
/*!40000 ALTER TABLE `craft_matrixcontent_moduleunitgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_matrixcontent_moduleunitgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_matrixcontent_pagecontent`
--

DROP TABLE IF EXISTS `craft_matrixcontent_pagecontent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_matrixcontent_pagecontent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_columns_columnTitle` text COLLATE utf8_unicode_ci,
  `field_columns_columnHtml` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_matrixcontent_pagecontent_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_matrixcontent_pagecontent_locale_fk` (`locale`),
  CONSTRAINT `craft_matrixcontent_pagecontent_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixcontent_pagecontent_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixcontent_pagecontent`
--

LOCK TABLES `craft_matrixcontent_pagecontent` WRITE;
/*!40000 ALTER TABLE `craft_matrixcontent_pagecontent` DISABLE KEYS */;
INSERT INTO `craft_matrixcontent_pagecontent` VALUES (1,1087,'en_gb','How we work','<p>The opinions and ideas of such groups help us to change and improve the industry, promoting the importance of skills recognition, training and development with the aim of increasing productivity, sustainability - and ultimately profitability.</p>\r\n<p>Liaising closely with industries within the land-based sector we represent, along with governments, funding agencies, learning providers, trade associations and the media, we can shape important strategies for the future.</p>','2018-08-23 14:29:32','2018-12-07 10:17:14','0c1af4bb-0417-4094-b496-d1087fba4835'),(2,1088,'en_gb','Working with industry groups and employers','<p>At the heart of Lantra\'s organisation are environmental and land-based employers - experts in their own field who know first hand the requirements of their industries. Employers play an integral part in the corporate structure of the organisation from working groups through to Lantra\'s board of directors, and play a key leadership role in forming and shaping Lantra\'s strategies, products and services.</p>','2018-08-23 14:29:32','2018-12-07 10:17:14','9d2d10f7-8a24-4985-8996-dcae5bca2251'),(6,1620,'en_gb','','<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.</p>\r\n<p><a href=\"mailto:Portia.Hartley@lantra.co.uk?subject=Testing\">Send us an email</a></p>','2018-11-23 11:08:56','2019-01-18 13:30:11','b3d1bacf-a47f-4f55-a3ef-a9e866036ade');
/*!40000 ALTER TABLE `craft_matrixcontent_pagecontent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_matrixcontent_testquestions`
--

DROP TABLE IF EXISTS `craft_matrixcontent_testquestions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_matrixcontent_testquestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_trueFalse_question` text COLLATE utf8_unicode_ci,
  `field_trueFalse_answer` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_choices_question` text COLLATE utf8_unicode_ci,
  `field_choices_answers` text COLLATE utf8_unicode_ci,
  `field_text_question` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_matrixcontent_testquestions_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_matrixcontent_testquestions_locale_fk` (`locale`),
  CONSTRAINT `craft_matrixcontent_testquestions_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixcontent_testquestions_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixcontent_testquestions`
--

LOCK TABLES `craft_matrixcontent_testquestions` WRITE;
/*!40000 ALTER TABLE `craft_matrixcontent_testquestions` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_matrixcontent_testquestions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_matrixcontent_usercustomfields`
--

DROP TABLE IF EXISTS `craft_matrixcontent_usercustomfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_matrixcontent_usercustomfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_userCustomField_customName` text COLLATE utf8_unicode_ci,
  `field_userCustomField_customValue` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_matrixcontent_usercustomfields_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_matrixcontent_usercustomfields_locale_fk` (`locale`),
  CONSTRAINT `craft_matrixcontent_usercustomfields_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixcontent_usercustomfields_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixcontent_usercustomfields`
--

LOCK TABLES `craft_matrixcontent_usercustomfields` WRITE;
/*!40000 ALTER TABLE `craft_matrixcontent_usercustomfields` DISABLE KEYS */;
INSERT INTO `craft_matrixcontent_usercustomfields` VALUES (1,2485,'en_gb','Custom field 1','','2019-01-16 11:52:08','2019-01-24 10:36:07','122d496c-bf49-4d1c-ba87-66d0be4c18d9'),(3,3267,'en_gb','Payroll','payroll','2019-02-01 09:51:20','2019-02-01 09:51:20','e7748ac3-ce69-416e-b8a6-18a4acb3f06c');
/*!40000 ALTER TABLE `craft_matrixcontent_usercustomfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_matrixcontent_userpayments`
--

DROP TABLE IF EXISTS `craft_matrixcontent_userpayments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_matrixcontent_userpayments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_paypal_payer_email` text COLLATE utf8_unicode_ci,
  `field_paypal_mc_gross` text COLLATE utf8_unicode_ci,
  `field_paypal_txn_id` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_matrixcontent_userpayments_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_matrixcontent_userpayments_locale_fk` (`locale`),
  CONSTRAINT `craft_matrixcontent_userpayments_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_matrixcontent_userpayments_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixcontent_userpayments`
--

LOCK TABLES `craft_matrixcontent_userpayments` WRITE;
/*!40000 ALTER TABLE `craft_matrixcontent_userpayments` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_matrixcontent_userpayments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_migrations`
--

DROP TABLE IF EXISTS `craft_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pluginId` int(11) DEFAULT NULL,
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `applyTime` datetime NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_migrations_version_unq_idx` (`version`),
  KEY `craft_migrations_pluginId_fk` (`pluginId`),
  CONSTRAINT `craft_migrations_pluginId_fk` FOREIGN KEY (`pluginId`) REFERENCES `craft_plugins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_migrations`
--

LOCK TABLES `craft_migrations` WRITE;
/*!40000 ALTER TABLE `craft_migrations` DISABLE KEYS */;
INSERT INTO `craft_migrations` VALUES (1,NULL,'m000000_000000_base','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','2eaf975a-b268-4cb4-bfa4-68b4a1226283'),(2,NULL,'m140730_000001_add_filename_and_format_to_transformindex','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','495b0628-c389-4af0-bec2-2a98abbe48be'),(3,NULL,'m140815_000001_add_format_to_transforms','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','1afb1205-ef2e-4633-adbc-a97ebb1db4f9'),(4,NULL,'m140822_000001_allow_more_than_128_items_per_field','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','d6168e8f-5c01-42a3-822f-b84a6e020fb7'),(5,NULL,'m140829_000001_single_title_formats','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','a9e8512c-b255-4359-ae30-41fa543f085a'),(6,NULL,'m140831_000001_extended_cache_keys','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','783e30fe-d457-4345-b3ee-31d2b4ba331d'),(7,NULL,'m140922_000001_delete_orphaned_matrix_blocks','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','2e85c070-c354-49ed-8e15-ca0fdf1829f3'),(8,NULL,'m141008_000001_elements_index_tune','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','314dd934-1d00-4d2e-b899-343955e317f5'),(9,NULL,'m141009_000001_assets_source_handle','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','b91e8b58-de2e-49c2-b9d1-a23eeae2d040'),(10,NULL,'m141024_000001_field_layout_tabs','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','d50d225b-9503-4031-a7af-868e2ca57b50'),(11,NULL,'m141030_000000_plugin_schema_versions','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','b98c3d2e-cdce-4bee-969f-1f00cf93c06b'),(12,NULL,'m141030_000001_drop_structure_move_permission','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','db924f1b-a0eb-4e0d-83da-5e2392e343a6'),(13,NULL,'m141103_000001_tag_titles','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','2a4c986d-4055-4518-a23d-c267204773ef'),(14,NULL,'m141109_000001_user_status_shuffle','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','8cf0a288-38e8-47aa-8d71-19cd893c0cd6'),(15,NULL,'m141126_000001_user_week_start_day','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','01bffd04-15e3-4cbc-bfd4-acc26b70eb23'),(16,NULL,'m150210_000001_adjust_user_photo_size','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','87293d41-215d-4388-ba81-9d459aeb4010'),(17,NULL,'m150724_000001_adjust_quality_settings','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','823acb7d-4682-4d4e-bd00-baf0701c2426'),(18,NULL,'m150827_000000_element_index_settings','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','8bb4c1f3-7b63-420d-9582-146f93c86a2b'),(19,NULL,'m150918_000001_add_colspan_to_widgets','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','8325ef7b-8e34-4b9f-a2e7-597344631042'),(20,NULL,'m151007_000000_clear_asset_caches','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','70402275-6cd3-4dd7-a7b5-3f18c05b9835'),(21,NULL,'m151109_000000_text_url_formats','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','dfac615d-ac88-4e4d-a642-49d5f27985fd'),(22,NULL,'m151110_000000_move_logo','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','fd3ac617-297c-4a0c-a442-8307cfe5e9eb'),(23,NULL,'m151117_000000_adjust_image_widthheight','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','930e62f7-0ec2-472b-9df6-2298fc8841b9'),(24,NULL,'m151127_000000_clear_license_key_status','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','32c71707-4d25-4978-a830-8b4080146567'),(25,NULL,'m151127_000000_plugin_license_keys','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','3a0598c0-6223-4774-b4e9-92308ac1de09'),(26,NULL,'m151130_000000_update_pt_widget_feeds','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','ed22be10-ebc6-4abc-b9ce-862bd7118cc5'),(27,NULL,'m160114_000000_asset_sources_public_url_default_true','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','bdce00a9-8650-4591-8659-65af2c2538c3'),(28,NULL,'m160223_000000_sortorder_to_smallint','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','3619fb25-d12a-45e5-865d-f0c1fd406414'),(29,NULL,'m160229_000000_set_default_entry_statuses','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','5a08032e-f7a9-45b4-9446-52922ffa22e7'),(30,NULL,'m160304_000000_client_permissions','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','f52d0821-c99c-46ef-bf55-c116067d6426'),(31,NULL,'m160322_000000_asset_filesize','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','5bfecd9b-62f9-4783-b494-3278e1a2855b'),(32,NULL,'m160503_000000_orphaned_fieldlayouts','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','1d7215d9-6dc1-413d-8176-e7d412813bfb'),(33,NULL,'m160510_000000_tasksettings','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','f98b105a-07a2-4c7a-b7b2-82107264f492'),(34,NULL,'m160829_000000_pending_user_content_cleanup','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','a1f6e618-2035-476e-9ca8-4922d45ea045'),(35,NULL,'m160830_000000_asset_index_uri_increase','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','ab658839-e7ad-4c91-91f8-c642096a52fa'),(36,NULL,'m160919_000000_usergroup_handle_title_unique','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','b56b1cf3-2a25-471f-88ef-f9f8bd084656'),(37,NULL,'m161108_000000_new_version_format','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','779d7aa5-569b-4846-94f3-3120ab53f2e3'),(38,NULL,'m161109_000000_index_shuffle','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','883bd624-b8b2-469c-8c80-3094f1d3b0db'),(39,NULL,'m170612_000000_route_index_shuffle','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','1f59e9a2-e913-4a21-bfbe-74052888b324'),(40,NULL,'m171107_000000_assign_group_permissions','2018-04-29 12:43:55','2018-04-29 12:43:55','2018-04-29 12:43:55','e296a7be-1955-4364-b03a-065105050eaf'),(41,NULL,'m171117_000001_templatecache_index_tune','2018-04-29 12:43:55','2018-04-29 12:43:55','2018-04-29 12:43:55','3e4152c0-091d-4d07-9d25-005465c170ee'),(42,NULL,'m171204_000001_templatecache_index_tune_deux','2018-04-29 12:43:55','2018-04-29 12:43:55','2018-04-29 12:43:55','f786fe92-23af-4da1-b311-b2d9e1541932'),(43,NULL,'m180406_000000_pro_upgrade','2018-04-29 12:43:55','2018-04-29 12:43:55','2018-04-29 12:43:55','c03db24e-f159-469b-885a-ed34ba2837ae'),(44,6,'m151229_000001_sproutReports_addReportGroupsTable','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','7f374b67-6880-49f8-853d-6ae6b1e2ed23'),(45,6,'m151229_000002_sproutReports_updateSingleNumberWidgetToNumberWidget','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','a361ee21-1e95-455a-b0cc-32501be172f3'),(46,6,'m151229_000003_sproutReports_removeOldReportColumns','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','7f63ad99-a423-4b05-a3b1-88cbf45d6924'),(47,6,'m151229_000004_sproutReports_addNewReportColumns','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','aa5150aa-d1db-40fc-b94a-65a5033442d4'),(48,6,'m151229_000005_sproutReports_migrateAndRemoveCustomQueryColumn','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','2520f789-83d6-4a9a-9b8e-efecc4206dc9'),(49,6,'m151229_000006_sproutReports_addDetailsToReportsTable','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','e3de4428-a4e4-4acc-b2ad-37486ffd8f18'),(50,6,'m161101_134003_sproutreports_createDataSourceTable','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','e0016f66-7fab-4d9e-a867-eae0a77594f0'),(51,6,'m161122_000000_sproutReports_addAllowHtmlColumn','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','cadd9390-5505-47dd-9647-e4e71b2eb844'),(52,6,'m171020_000000_sproutReports_addDynamicNameColumn','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','cd8d99ea-0f7f-4027-9407-6d17c9822e05'),(53,7,'m140924_111621_export_CreateExportMap','2018-06-02 09:37:09','2018-06-02 09:37:09','2018-06-02 09:37:09','53a33448-8802-452c-9d21-796aa910756b'),(54,8,'m140430_122214_import_ImportHistory','2018-06-02 09:37:11','2018-06-02 09:37:11','2018-06-02 09:37:11','e4a9f07e-9813-478e-bc52-4be542e6ad6c'),(55,8,'m140616_080724_import_saveEntryIdAndVersion','2018-06-02 09:37:11','2018-06-02 09:37:11','2018-06-02 09:37:11','3fdd9e6b-3b56-49dd-9264-be8759babf80'),(56,8,'m140903_075432_import_ImportElements','2018-06-02 09:37:11','2018-06-02 09:37:11','2018-06-02 09:37:11','325e1b3c-8ac0-4a18-b25e-221992b57f98'),(57,13,'m150901_144609_superTable_fixForContentTables','2018-12-06 17:33:46','2018-12-06 17:33:46','2018-12-06 17:33:46','72d82e5c-8c79-4b83-9185-40500b8bd327'),(58,4,'m190319_160000_lantra_unitHeading','2019-04-25 13:25:42','2019-04-25 13:25:42','2019-04-25 13:25:42','4baee4c2-7950-4160-a54b-1860a2040af0'),(59,14,'m190424_142144_migration_result_custom','2019-04-25 13:26:07','2019-04-25 13:26:07','2019-04-25 13:26:07','683d03c1-2530-40fc-b0b1-c6a3b6d16738'),(60,14,'m190424_171346_migration_delete_globals','2019-04-25 13:26:08','2019-04-25 13:26:08','2019-04-25 13:26:08','6ca35a30-578a-4f82-9997-ed063e2415eb');
/*!40000 ALTER TABLE `craft_migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_plugins`
--

DROP TABLE IF EXISTS `craft_plugins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `schemaVersion` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `licenseKey` char(24) COLLATE utf8_unicode_ci DEFAULT NULL,
  `licenseKeyStatus` enum('valid','invalid','mismatched','unknown') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unknown',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `settings` text COLLATE utf8_unicode_ci,
  `installDate` datetime NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_plugins`
--

LOCK TABLES `craft_plugins` WRITE;
/*!40000 ALTER TABLE `craft_plugins` DISABLE KEYS */;
INSERT INTO `craft_plugins` VALUES (2,'Status','1.0.0',NULL,NULL,'unknown',1,NULL,'2017-10-24 11:48:49','2017-10-24 11:48:49','2019-04-25 13:25:59','fe2dbfed-7388-4cc0-a5b5-7885929ef5e5'),(4,'Lantra','0.0.2',NULL,NULL,'unknown',1,NULL,'2018-04-24 09:58:57','2018-04-24 09:58:57','2019-04-25 13:25:59','4ab50cac-aee2-4870-9c8f-af13d682f3b2'),(5,'InternalAssets','1.0',NULL,NULL,'unknown',1,NULL,'2018-04-26 16:28:41','2018-04-26 16:28:41','2019-04-25 13:25:59','e03e03e3-cec4-49a6-9816-01265b32ac3f'),(6,'SproutReports','0.9.3','0.9.1',NULL,'unknown',1,NULL,'2018-05-02 09:18:48','2018-05-02 09:18:48','2019-04-25 13:25:59','b504bea7-b005-4991-b0fa-137d617e9d08'),(7,'Export','0.5.10',NULL,NULL,'unknown',0,NULL,'2018-06-02 09:37:09','2018-06-02 09:37:09','2019-02-21 14:34:53','1a12d433-5a43-4573-b1cd-b4bf393331a6'),(8,'Import','0.8.33',NULL,NULL,'unknown',0,NULL,'2018-06-02 09:37:11','2018-06-02 09:37:11','2019-02-21 14:34:55','a2284062-a061-42b6-8fc9-9d116c2347fe'),(9,'Printmaker','1.0.3','0.0.0.0',NULL,'unknown',1,NULL,'2018-06-04 09:23:06','2018-06-04 09:23:06','2019-04-25 13:25:59','63198fa2-bcbf-4724-838b-3a2f08346eec'),(10,'PreparseField','0.3.6','1.0.0',NULL,'unknown',1,NULL,'2018-06-04 10:45:10','2018-06-04 10:45:10','2019-04-25 13:25:59','b866617e-b5b3-4880-b709-28810e59caea'),(12,'Mailer','0.5.1',NULL,NULL,'unknown',0,NULL,'2018-08-08 12:58:45','2018-08-08 12:58:45','2019-02-21 14:35:11','6e91f1b3-e928-4e73-bed8-692639368355'),(13,'SuperTable','1.0.6','1.0.0',NULL,'unknown',1,NULL,'2018-12-06 17:33:46','2018-12-06 17:33:46','2019-04-25 13:25:59','c5d31ad7-2241-44bd-bfcd-2db3f6304091'),(14,'MigrationManager','1.0.9.2','1.0.0',NULL,'unknown',1,NULL,'2019-04-25 13:25:59','2019-04-25 13:25:59','2019-04-25 13:25:59','31cdbe13-7deb-4387-bf78-4c6c3c002c0e');
/*!40000 ALTER TABLE `craft_plugins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_rackspaceaccess`
--

DROP TABLE IF EXISTS `craft_rackspaceaccess`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_rackspaceaccess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `connectionKey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `storageUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cdnUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_rackspaceaccess_connectionKey_unq_idx` (`connectionKey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_rackspaceaccess`
--

LOCK TABLES `craft_rackspaceaccess` WRITE;
/*!40000 ALTER TABLE `craft_rackspaceaccess` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_rackspaceaccess` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_relations`
--

DROP TABLE IF EXISTS `craft_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_relations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldId` int(11) NOT NULL,
  `sourceId` int(11) NOT NULL,
  `sourceLocale` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `targetId` int(11) NOT NULL,
  `sortOrder` smallint(6) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_relations_fieldId_sourceId_sourceLocale_targetId_unq_idx` (`fieldId`,`sourceId`,`sourceLocale`,`targetId`),
  KEY `craft_relations_sourceId_fk` (`sourceId`),
  KEY `craft_relations_sourceLocale_fk` (`sourceLocale`),
  KEY `craft_relations_targetId_fk` (`targetId`),
  CONSTRAINT `craft_relations_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_relations_sourceId_fk` FOREIGN KEY (`sourceId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_relations_sourceLocale_fk` FOREIGN KEY (`sourceLocale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `craft_relations_targetId_fk` FOREIGN KEY (`targetId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7153 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_relations`
--

LOCK TABLES `craft_relations` WRITE;
/*!40000 ALTER TABLE `craft_relations` DISABLE KEYS */;
INSERT INTO `craft_relations` VALUES (5517,178,2541,NULL,143,1,'2019-01-21 19:11:24','2019-01-21 19:11:24','48817050-c733-455e-b3e2-7efbd0f8924f'),(5518,178,2542,NULL,143,1,'2019-01-21 19:11:24','2019-01-21 19:11:24','04976a9d-2d61-4a6e-a29e-9eec1b3fa3f8'),(5520,178,2543,NULL,143,1,'2019-01-21 19:11:29','2019-01-21 19:11:29','4eee28c0-ade4-4c7f-b0e2-4da318974111'),(5521,178,2544,NULL,143,1,'2019-01-21 19:11:30','2019-01-21 19:11:30','b26f05fb-6c67-4be3-bdb8-0899442af229'),(5526,178,2546,NULL,143,1,'2019-01-21 19:15:01','2019-01-21 19:15:01','cbd9fe70-b088-4561-9eda-202c957a0244'),(5527,178,2547,NULL,143,1,'2019-01-21 19:15:01','2019-01-21 19:15:01','c45612ab-3944-4c3f-9c13-8752e0b9de1f'),(5529,178,2548,NULL,143,1,'2019-01-21 19:18:23','2019-01-21 19:18:23','8e75cc01-942a-4cd2-bde7-139d7a16a4cb'),(5530,178,2549,NULL,143,1,'2019-01-21 19:18:23','2019-01-21 19:18:23','705c65d7-90dd-4d0c-9a8e-2f34f7b354c5'),(6112,178,3124,NULL,143,1,'2019-01-21 19:38:11','2019-01-21 19:38:11','069658e8-2c8d-4f49-b7f1-0806bc89f555'),(6114,178,3125,NULL,143,1,'2019-01-21 19:38:17','2019-01-21 19:38:17','951fe261-73b2-4264-b0d0-d20c76ecae24'),(6116,178,3126,NULL,143,1,'2019-01-21 19:40:53','2019-01-21 19:40:53','8ed619de-d5dc-42ca-bd20-2fb4c35e38ea'),(6118,178,3127,NULL,143,1,'2019-01-21 19:41:04','2019-01-21 19:41:04','d6a33281-cb16-4b1d-8ec5-581f42d0b5a2'),(7149,117,1079,NULL,1269,1,'2019-02-21 14:36:13','2019-02-21 14:36:13','13dc440d-146f-45f8-bb5d-8b9a8d5a40f3'),(7150,110,1091,NULL,1270,1,'2019-02-21 14:36:13','2019-02-21 14:36:13','6a733363-c3db-4dae-a45c-d4685e06fb9f'),(7151,111,1079,NULL,1090,1,'2019-02-21 14:36:13','2019-02-21 14:36:13','7a048729-0901-490d-9120-87106b3dc49a'),(7152,172,1079,NULL,1090,1,'2019-02-21 14:36:13','2019-02-21 14:36:13','de0e1a55-a023-4fe2-a15f-433158a82a45');
/*!40000 ALTER TABLE `craft_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_routes`
--

DROP TABLE IF EXISTS `craft_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locale` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `urlParts` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `urlPattern` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_routes_locale_idx` (`locale`),
  KEY `craft_routes_urlPattern_idx` (`urlPattern`),
  CONSTRAINT `craft_routes_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_routes`
--

LOCK TABLES `craft_routes` WRITE;
/*!40000 ALTER TABLE `craft_routes` DISABLE KEYS */;
INSERT INTO `craft_routes` VALUES (1,NULL,'{\"0\":\"management\\/\",\"1\":[\"*\",\"[^\\\\\\/]+\"],\"3\":\"\\/edit\\/\",\"4\":[\"*\",\"[^\\\\\\/]+\"]}','management\\/([^\\/]+)\\/edit\\/([^\\/]+)','management/index',3,'2018-03-14 15:28:14','2019-01-18 17:36:44','f14a240f-e452-47a4-8860-599a132604e2'),(2,NULL,'{\"0\":\"management\\/\",\"1\":[\"*\",\"[^\\\\\\/]+\"],\"3\":\"\\/new\"}','management\\/([^\\/]+)\\/new','management/index',4,'2018-03-14 15:28:38','2019-01-18 17:36:44','86fa9b96-eb71-4527-b040-5b962a9ec653'),(3,NULL,'[\"public\\/passport\\/\",[\"*\",\"[^\\\\\\/]+\"]]','public\\/passport\\/([^\\/]+)','public/passport',2,'2018-03-30 15:24:35','2019-01-18 17:36:44','f8b2518c-0ae9-45b9-b102-61561eeae24e'),(7,NULL,'{\"0\":\"cpd\\/\",\"1\":[\"number\",\"\\\\d+\"],\"3\":\"\\/\",\"4\":[\"number\",\"\\\\d+\"],\"6\":\"\\/\",\"7\":[\"number\",\"\\\\d+\"],\"9\":\"\\/test\"}','cpd\\/(?P<number>\\d+)\\/(?P<number2>\\d+)\\/(?P<number3>\\d+)\\/test','cpd/unit',7,'2018-05-02 08:41:13','2019-01-18 17:36:44','04efe3b7-703e-4004-b61b-d51c1335062a'),(8,NULL,'[\"reporting\\/user\\/\",[\"number\",\"\\\\d+\"]]','reporting\\/user\\/(?P<number>\\d+)','reporting/user',14,'2018-06-03 14:03:45','2019-01-18 17:36:44','ab4653c9-26cb-4b3c-b9e8-cc5c3b8a93a9'),(9,NULL,'[\"reporting\\/\",[\"slug\",\"[^\\\\\\/]+\"]]','reporting\\/(?P<slug>[^\\/]+)','reporting',16,'2018-06-04 08:59:37','2019-01-18 17:36:44','38933379-4841-49d3-a1c4-9b67b33d56f6'),(10,NULL,'[\"reporting\\/\",[\"slug\",\"[^\\\\\\/]+\"],\"\\/csv\"]','reporting\\/(?P<slug>[^\\/]+)\\/csv','reporting',15,'2018-06-04 08:59:52','2019-01-18 17:36:44','ca2f35c1-6d57-4271-9da2-21f968bd1556'),(11,NULL,'[\"public\\/certificate\\/\",[\"number\",\"\\\\d+\"],\"\\/\",[\"number\",\"\\\\d+\"]]','public\\/certificate\\/(?P<number>\\d+)\\/(?P<number2>\\d+)','public/certificate',1,'2018-06-04 09:49:58','2019-01-18 17:36:44','0edddf7a-cb3d-4311-818d-c6a6675b8803'),(13,NULL,'[\"cpd\\/\",[\"number\",\"\\\\d+\"]]','cpd\\/(?P<number>\\d+)','cpd/index',5,'2018-08-29 16:49:05','2019-01-18 17:36:44','c679d894-a6e1-4a4e-925c-78715c8aad07'),(14,NULL,'[\"reporting\\/custom\"]','reporting\\/custom','reporting/custom/index',13,'2018-09-04 13:45:15','2019-01-18 17:36:44','9df15ced-290b-42e8-a4da-e20fcdc2261b'),(15,NULL,'[\"reporting\\/custom\\/new\"]','reporting\\/custom\\/new','reporting/custom/_form',12,'2018-09-04 13:45:26','2019-01-18 17:36:44','e11932aa-6acf-45d3-b31f-61ebdc013904'),(16,NULL,'[\"reporting\\/custom\\/edit\\/\",[\"number\",\"\\\\d+\"]]','reporting\\/custom\\/edit\\/(?P<number>\\d+)','reporting/custom/_form',11,'2018-09-04 13:45:56','2019-01-18 17:36:44','565f50ea-ae72-426c-8093-bf7d571a539c'),(17,NULL,'[\"profile\\/\"]','profile\\/','profile/index',17,'2018-09-12 10:05:02','2019-01-18 17:36:44','d1e6176a-211b-4910-8882-6d7dd1be4811'),(18,NULL,'[\"cpd\\/\",[\"number\",\"\\\\d+\"],\"\\/achievement\"]','cpd\\/(?P<number>\\d+)\\/achievement','cpd/achievement',8,'2018-12-06 17:42:24','2019-01-18 17:41:38','e6ff9c53-6302-4e2f-b5b4-1ee3137a21d3'),(19,NULL,'[\"cpd\\/\",[\"number\",\"\\\\d+\"],\"\\/result\\/\",[\"number\",\"\\\\d+\"]]','cpd\\/(?P<number>\\d+)\\/result\\/(?P<number2>\\d+)','cpd/achievement',10,'2018-12-06 17:42:41','2019-01-18 17:37:19','d39a65cb-31f6-4710-9a7b-d9de75c28c92'),(21,NULL,'[\"public\\/licence\\/thanks\"]','public\\/licence\\/thanks','public/licence',18,'2018-12-18 21:41:14','2019-01-18 17:36:44','cd4b3cb9-d234-4f1e-8568-a4a61b208353'),(22,NULL,'[\"cpd\\/\",[\"number\",\"\\\\d+\"],\"\\/\",[\"number\",\"\\\\d+\"],\"\\/\",[\"number\",\"\\\\d+\"]]','cpd\\/(?P<number>\\d+)\\/(?P<number2>\\d+)\\/(?P<number3>\\d+)','cpd/unit',6,'2019-01-18 17:19:59','2019-01-18 17:36:44','e861b119-4576-497f-8610-cf13e5aa2b9e'),(23,NULL,'[\"cpd\\/\",[\"number\",\"\\\\d+\"],\"\\/achievement\\/\",[\"number\",\"\\\\d+\"]]','cpd\\/(?P<number>\\d+)\\/achievement\\/(?P<number2>\\d+)','cpd/achievement',9,'2019-01-18 17:35:49','2019-01-18 17:36:44','9d5415e0-fad8-4ec6-96b9-ca655c2bad68');
/*!40000 ALTER TABLE `craft_routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_searchindex`
--

DROP TABLE IF EXISTS `craft_searchindex`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_searchindex` (
  `elementId` int(11) NOT NULL,
  `attribute` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `fieldId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`elementId`,`attribute`,`fieldId`,`locale`),
  FULLTEXT KEY `craft_searchindex_keywords_idx` (`keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_searchindex`
--

LOCK TABLES `craft_searchindex` WRITE;
/*!40000 ALTER TABLE `craft_searchindex` DISABLE KEYS */;
INSERT INTO `craft_searchindex` VALUES (1,'username',0,'en_gb',' jason thisistraffic co uk '),(1,'firstname',0,'en_gb',' jason '),(1,'lastname',0,'en_gb',' church '),(1,'fullname',0,'en_gb',' jason church '),(1,'email',0,'en_gb',' jason thisistraffic co uk '),(1,'slug',0,'en_gb',''),(1438,'field',77,'en_gb',''),(1191,'field',33,'en_gb',''),(1190,'extension',0,'en_gb',' png '),(1190,'kind',0,'en_gb',' image '),(1190,'slug',0,'en_gb',' unit '),(1190,'title',0,'en_gb',' unit '),(1191,'field',29,'en_gb',' pending '),(1255,'field',29,'en_gb',' pending '),(489,'field',73,'en_gb',' 10 '),(489,'field',72,'en_gb',' d m y '),(489,'slug',0,'en_gb',''),(488,'field',71,'en_gb',''),(1170,'title',0,'en_gb',' test for user one '),(1,'field',3,'en_gb',''),(1,'field',30,'en_gb',' ﻿level 1 egg collector '),(1269,'filename',0,'en_gb',' lantraawards_logo png '),(1269,'extension',0,'en_gb',' png '),(1269,'kind',0,'en_gb',' image '),(1269,'slug',0,'en_gb',' lantra awards logo '),(1269,'title',0,'en_gb',' lantra awards logo '),(1191,'field',86,'en_gb',''),(1191,'field',34,'en_gb',''),(1191,'field',17,'en_gb',' unit '),(1191,'field',87,'en_gb',''),(143,'field',124,'en_gb',''),(143,'field',84,'en_gb',''),(143,'field',77,'en_gb',''),(143,'field',121,'en_gb',''),(143,'field',122,'en_gb',''),(143,'field',123,'en_gb',' ss '),(143,'field',76,'en_gb',''),(143,'field',3,'en_gb',''),(143,'field',30,'en_gb',''),(143,'username',0,'en_gb',' robin coffeebean design '),(143,'firstname',0,'en_gb',' robin '),(143,'lastname',0,'en_gb',' willmott '),(143,'fullname',0,'en_gb',' robin willmott '),(143,'email',0,'en_gb',' robin coffeebean design '),(143,'slug',0,'en_gb',''),(1190,'filename',0,'en_gb',' unit png '),(194,'field',51,'en_gb',' 1 '),(194,'field',52,'en_gb',' false '),(194,'field',53,'en_gb',' 1 '),(194,'slug',0,'en_gb',''),(195,'field',51,'en_gb',' 182 '),(195,'field',52,'en_gb',' false '),(195,'field',53,'en_gb',' 0 '),(195,'slug',0,'en_gb',''),(196,'field',51,'en_gb',' 182 '),(196,'field',52,'en_gb',' false '),(196,'field',53,'en_gb',' 0 '),(196,'slug',0,'en_gb',''),(370,'field',33,'en_gb',''),(370,'field',34,'en_gb',''),(370,'field',58,'en_gb',''),(370,'field',27,'en_gb',' 0 '),(370,'field',59,'en_gb',' 0 '),(370,'slug',0,'en_gb',' unit 355 robin willmott '),(370,'title',0,'en_gb',' unit 355 robin willmott '),(370,'field',17,'en_gb',''),(369,'title',0,'en_gb',' unit robin willmott '),(370,'field',26,'en_gb',' dummy evidence '),(369,'slug',0,'en_gb',' unit robin willmott '),(369,'field',26,'en_gb',''),(369,'field',17,'en_gb',''),(369,'field',33,'en_gb',''),(369,'field',34,'en_gb',''),(369,'field',58,'en_gb',''),(369,'field',27,'en_gb',' 0 '),(369,'field',59,'en_gb',' 0 '),(1191,'title',0,'en_gb',' dd '),(1191,'slug',0,'en_gb',' dd '),(446,'kind',0,'en_gb',' pdf '),(430,'title',0,'en_gb',' evidence '),(430,'kind',0,'en_gb',' pdf '),(430,'slug',0,'en_gb',' evidence '),(430,'extension',0,'en_gb',' pdf '),(430,'filename',0,'en_gb',' evidence_180423_104254 pdf '),(373,'field',59,'en_gb',' 0 '),(373,'field',27,'en_gb',' 0 '),(373,'field',58,'en_gb',''),(373,'field',34,'en_gb',''),(373,'field',33,'en_gb',''),(373,'field',26,'en_gb',' dummy evidence '),(373,'field',17,'en_gb',''),(373,'slug',0,'en_gb',' unit 355 robin willmott '),(373,'title',0,'en_gb',' unit 355 robin willmott '),(374,'field',26,'en_gb',' dummy evidence '),(374,'field',17,'en_gb',''),(374,'field',33,'en_gb',''),(374,'field',34,'en_gb',''),(374,'field',58,'en_gb',''),(374,'field',27,'en_gb',' 0 '),(374,'field',59,'en_gb',' 0 '),(374,'slug',0,'en_gb',' unit 355 robin willmott '),(374,'title',0,'en_gb',' unit 355 robin willmott '),(375,'field',26,'en_gb',' dummy evidence '),(375,'field',17,'en_gb',''),(375,'field',33,'en_gb',''),(375,'field',34,'en_gb',''),(375,'field',58,'en_gb',''),(375,'field',27,'en_gb',' 0 '),(375,'field',59,'en_gb',' 0 '),(375,'slug',0,'en_gb',' unit 355 robin willmott '),(375,'title',0,'en_gb',' unit 355 robin willmott '),(376,'field',26,'en_gb',' dummy evidence '),(376,'field',17,'en_gb',''),(376,'field',33,'en_gb',''),(376,'field',34,'en_gb',''),(376,'field',58,'en_gb',''),(376,'field',27,'en_gb',' 0 '),(376,'field',59,'en_gb',' 0 '),(376,'slug',0,'en_gb',' unit 355 robin willmott '),(376,'title',0,'en_gb',' unit 355 robin willmott '),(377,'field',26,'en_gb',' dummy evidence '),(377,'field',17,'en_gb',''),(377,'field',33,'en_gb',''),(377,'field',34,'en_gb',''),(377,'field',58,'en_gb',''),(377,'field',27,'en_gb',' 0 '),(377,'field',59,'en_gb',' 0 '),(377,'slug',0,'en_gb',' unit 355 robin willmott '),(377,'title',0,'en_gb',' unit 355 robin willmott '),(446,'extension',0,'en_gb',' pdf '),(446,'filename',0,'en_gb',' evidence_180424_090746 pdf '),(428,'slug',0,'en_gb',' evidence '),(428,'title',0,'en_gb',' evidence '),(428,'kind',0,'en_gb',' pdf '),(428,'extension',0,'en_gb',' pdf '),(428,'filename',0,'en_gb',' evidence pdf '),(440,'filename',0,'en_gb',' evidence_180423_110232 pdf '),(440,'extension',0,'en_gb',' pdf '),(446,'title',0,'en_gb',' evidence '),(497,'kind',0,'en_gb',' pdf '),(497,'slug',0,'en_gb',' evidence '),(497,'title',0,'en_gb',' evidence '),(143,'field',88,'en_gb',''),(440,'kind',0,'en_gb',' pdf '),(440,'slug',0,'en_gb',' evidence '),(440,'title',0,'en_gb',' evidence '),(432,'filename',0,'en_gb',' evidence_180423_105359 pdf '),(432,'extension',0,'en_gb',' pdf '),(432,'kind',0,'en_gb',' pdf '),(432,'slug',0,'en_gb',' evidence '),(432,'title',0,'en_gb',' evidence '),(443,'title',0,'en_gb',' evidence '),(443,'slug',0,'en_gb',' evidence '),(443,'kind',0,'en_gb',' pdf '),(443,'filename',0,'en_gb',' evidence_180423_115500 pdf '),(443,'extension',0,'en_gb',' pdf '),(435,'filename',0,'en_gb',' evidence_180423_105559 pdf '),(435,'extension',0,'en_gb',' pdf '),(435,'kind',0,'en_gb',' pdf '),(435,'slug',0,'en_gb',' evidence '),(435,'title',0,'en_gb',' evidence '),(446,'slug',0,'en_gb',' evidence '),(437,'title',0,'en_gb',' evidence '),(437,'slug',0,'en_gb',' evidence '),(437,'kind',0,'en_gb',' pdf '),(437,'extension',0,'en_gb',' pdf '),(437,'filename',0,'en_gb',' evidence_180423_105654 pdf '),(497,'extension',0,'en_gb',' pdf '),(497,'filename',0,'en_gb',' evidence pdf '),(448,'filename',0,'en_gb',' evidence_180424_091154 pdf '),(448,'extension',0,'en_gb',' pdf '),(448,'kind',0,'en_gb',' pdf '),(448,'slug',0,'en_gb',' evidence '),(448,'title',0,'en_gb',' evidence '),(450,'filename',0,'en_gb',' evidence_180424_091223 pdf '),(450,'extension',0,'en_gb',' pdf '),(450,'kind',0,'en_gb',' pdf '),(450,'slug',0,'en_gb',' evidence '),(450,'title',0,'en_gb',' evidence '),(1200,'filename',0,'en_gb',' unit_180911_120731 png '),(1200,'extension',0,'en_gb',' png '),(1200,'kind',0,'en_gb',' image '),(1200,'slug',0,'en_gb',' unit '),(1200,'title',0,'en_gb',' unit '),(1423,'slug',0,'en_gb',''),(1423,'email',0,'en_gb',' portia hartley lantra co uk '),(1423,'fullname',0,'en_gb',' portia hartley '),(143,'field',132,'en_gb',' 0 '),(143,'field',128,'en_gb',''),(143,'field',129,'en_gb',' 1 '),(488,'field',13,'en_gb',''),(488,'field',15,'en_gb',' 1000 '),(488,'slug',0,'en_gb',''),(1423,'lastname',0,'en_gb',' hartley '),(1423,'field',148,'en_gb',''),(1423,'field',149,'en_gb',''),(1423,'field',151,'en_gb',' 0 '),(1423,'username',0,'en_gb',' portia hartley lantra co uk '),(1423,'firstname',0,'en_gb',' portia '),(488,'field',75,'en_gb',''),(1423,'field',147,'en_gb',' 0 '),(1423,'field',150,'en_gb',' 0 '),(1423,'field',124,'en_gb',''),(1423,'field',77,'en_gb',''),(1423,'field',84,'en_gb',''),(1423,'field',88,'en_gb',''),(1423,'field',76,'en_gb',''),(1423,'field',123,'en_gb',''),(1423,'field',122,'en_gb',''),(1423,'field',121,'en_gb',''),(1423,'field',132,'en_gb',' 0 '),(1423,'field',129,'en_gb',' 1 '),(1423,'field',30,'en_gb',''),(1423,'field',3,'en_gb',''),(1423,'field',128,'en_gb',''),(1,'field',132,'en_gb',' 0 '),(1271,'extension',0,'en_gb',' jpg '),(1271,'kind',0,'en_gb',' image '),(1271,'slug',0,'en_gb',' bground img 2 '),(1271,'title',0,'en_gb',' bground img 2 '),(1272,'filename',0,'en_gb',' bground img 3 jpg '),(1272,'extension',0,'en_gb',' jpg '),(1272,'kind',0,'en_gb',' image '),(1272,'slug',0,'en_gb',' bground img 3 '),(1272,'title',0,'en_gb',' bground img 3 '),(1273,'filename',0,'en_gb',' bground img 4 jpg '),(1273,'extension',0,'en_gb',' jpg '),(1273,'kind',0,'en_gb',' image '),(1273,'slug',0,'en_gb',' bground img 4 '),(1273,'title',0,'en_gb',' bground img 4 '),(488,'field',141,'en_gb',''),(1256,'title',0,'en_gb',' test custom result '),(1256,'slug',0,'en_gb',' test custom result '),(1256,'field',33,'en_gb',''),(1256,'field',34,'en_gb',''),(1256,'field',87,'en_gb',''),(1256,'field',17,'en_gb',''),(1256,'field',142,'en_gb',' 2018 09 01 '),(1256,'field',143,'en_gb',' 2018 09 07 '),(1256,'field',144,'en_gb',' london '),(1256,'field',86,'en_gb',' result notes '),(1256,'field',29,'en_gb',' pending '),(1255,'title',0,'en_gb',' test custom result '),(1255,'slug',0,'en_gb',' test custom result '),(1255,'field',144,'en_gb',' london '),(1255,'field',86,'en_gb',' result notes '),(488,'field',127,'en_gb',''),(488,'field',83,'en_gb',' 50 '),(488,'field',82,'en_gb',' 365 '),(488,'field',85,'en_gb',''),(1255,'field',143,'en_gb',' 2018 09 07 '),(1255,'field',142,'en_gb',' 2018 09 01 '),(1255,'field',87,'en_gb',''),(1255,'field',17,'en_gb',''),(488,'field',131,'en_gb',' 0 '),(488,'field',89,'en_gb',''),(1,'field',124,'en_gb',' custom field 1 '),(1,'field',123,'en_gb',''),(1,'field',122,'en_gb',''),(1,'field',121,'en_gb',''),(1,'field',129,'en_gb',' 1 '),(1,'field',128,'en_gb',' leroy s test company '),(1170,'field',87,'en_gb',''),(1170,'field',17,'en_gb',''),(1170,'field',86,'en_gb',' test '),(1170,'field',29,'en_gb',' pending '),(1170,'field',33,'en_gb',''),(1170,'field',34,'en_gb',''),(1170,'slug',0,'en_gb',' test for user one '),(1255,'field',34,'en_gb',''),(1014,'filename',0,'en_gb',' koala jpg '),(1014,'extension',0,'en_gb',' jpg '),(1014,'kind',0,'en_gb',' image '),(1014,'slug',0,'en_gb',' koala '),(1014,'title',0,'en_gb',' koala '),(1015,'field',29,'en_gb',' pending '),(1015,'field',26,'en_gb',' working at heights '),(1015,'field',33,'en_gb',''),(1015,'field',34,'en_gb',''),(1015,'field',87,'en_gb',''),(1015,'field',17,'en_gb',' koala '),(1015,'field',86,'en_gb',' koala in a tree '),(1015,'field',58,'en_gb',''),(1015,'field',27,'en_gb',' 0 '),(1015,'slug',0,'en_gb',' unit 880 richard crompton '),(1015,'title',0,'en_gb',' unit 880 richard crompton '),(1438,'field',84,'en_gb',''),(1438,'field',88,'en_gb',''),(1438,'field',76,'en_gb',''),(1438,'field',123,'en_gb',''),(1438,'field',122,'en_gb',''),(1438,'field',121,'en_gb',''),(1438,'field',132,'en_gb',' 0 '),(1438,'field',129,'en_gb',' 1 '),(1438,'field',30,'en_gb',''),(1,'field',76,'en_gb',' 07900 545 470 '),(1,'field',84,'en_gb',''),(1,'field',88,'en_gb',''),(1,'field',77,'en_gb',''),(1255,'field',33,'en_gb',''),(1079,'field',119,'en_gb',' 1c2b39 '),(1079,'field',118,'en_gb',' 00bfbf '),(1088,'field',113,'en_gb',' working with industry groups and employers '),(1088,'field',114,'en_gb',' at the heart of lantra s organisation are environmental and land based employers experts in their own field who know first hand the requirements of their industries employers play an integral part in the corporate structure of the organisation from working groups through to lantra s board of directors and play a key leadership role in forming and shaping lantra s strategies products and services '),(1079,'field',117,'en_gb',' lantra awards logo '),(1079,'field',71,'en_gb',' lantra cpd '),(1079,'field',116,'en_gb',''),(1087,'slug',0,'en_gb',''),(1091,'slug',0,'en_gb',''),(1091,'field',110,'en_gb',' bground img 1 '),(1438,'field',3,'en_gb',''),(1438,'field',128,'en_gb',''),(1091,'field',109,'en_gb',' support your own staff and non employed individuals across your entire workforce '),(1091,'field',108,'en_gb',' welcome to lantra cpd '),(1086,'field',115,'en_gb',''),(1086,'slug',0,'en_gb',' about lantra '),(1086,'title',0,'en_gb',' about lantra '),(1087,'field',113,'en_gb',' how we work '),(1087,'field',114,'en_gb',' the opinions and ideas of such groups help us to change and improve the industry promoting the importance of skills recognition training and development with the aim of increasing productivity sustainability and ultimately profitability liaising closely with industries within the land based sector we represent along with governments funding agencies learning providers trade associations and the media we can shape important strategies for the future '),(1090,'title',0,'en_gb',' contact us '),(1090,'field',115,'en_gb',''),(1090,'slug',0,'en_gb',' contact us '),(1090,'field',112,'en_gb',' you can send a direct contact to lantra by completing this form we will get back to you with our answer by either telephone or email depending on which option that you chose we aim to reply to all enquiries by the end of the next working day send us an email '),(1,'field',173,'en_gb',''),(1090,'field',2,'en_gb',' we re here to help use the form below to contact a member of the team '),(1090,'field',1,'en_gb',''),(1086,'field',112,'en_gb',' the opinions and ideas of such groups help us to change and improve the industry promoting the importance of skills recognition training and development with the aim of increasing productivity sustainability and ultimately profitability liaising closely with industries within the land based sector we represent along with governments funding agencies learning providers trade associations and the media we can shape important strategies for the future how we work at the heart of lantra s organisation are environmental and land based employers experts in their own field who know first hand the requirements of their industries employers play an integral part in the corporate structure of the organisation from working groups through to lantra s board of directors and play a key leadership role in forming and shaping lantra s strategies products and services working with industry groups and employers '),(1086,'field',1,'en_gb',''),(1086,'field',2,'en_gb',' industry plays an essential part in lantra s work our role often involves working closely with industry groups to deliver solutions to specific industry needs '),(488,'field',116,'en_gb',''),(1270,'filename',0,'en_gb',' bground img 1 jpg '),(1270,'extension',0,'en_gb',' jpg '),(1270,'kind',0,'en_gb',' image '),(1270,'slug',0,'en_gb',' bground img 1 '),(1270,'title',0,'en_gb',' bground img 1 '),(1271,'filename',0,'en_gb',' bground img 2 jpg '),(1079,'slug',0,'en_gb',''),(1079,'field',107,'en_gb',' support your own staff and non employed individuals across your entire workforce bground img 1 welcome to lantra cpd '),(1079,'field',111,'en_gb',' contact us '),(1088,'slug',0,'en_gb',''),(1079,'field',120,'en_gb',' 000000 '),(488,'field',145,'en_gb',''),(1438,'field',124,'en_gb',''),(1438,'field',150,'en_gb',' 0 '),(1438,'field',147,'en_gb',' 0 '),(1438,'field',148,'en_gb',''),(1438,'field',149,'en_gb',''),(1438,'field',151,'en_gb',' 0 '),(1438,'username',0,'en_gb',' margaret murray skills plus co uk '),(1438,'firstname',0,'en_gb',' margaret '),(1438,'lastname',0,'en_gb',' murray '),(1438,'fullname',0,'en_gb',' margaret murray '),(1438,'email',0,'en_gb',' margaret murray skills plus co uk '),(1438,'slug',0,'en_gb',''),(1440,'field',128,'en_gb',''),(1440,'field',3,'en_gb',''),(1440,'field',30,'en_gb',''),(1440,'field',129,'en_gb',' 1 '),(1440,'field',132,'en_gb',' 0 '),(1440,'field',121,'en_gb',''),(1440,'field',122,'en_gb',''),(1440,'field',123,'en_gb',''),(1440,'field',76,'en_gb',''),(1440,'field',88,'en_gb',''),(1440,'field',84,'en_gb',''),(1440,'field',77,'en_gb',''),(1440,'field',124,'en_gb',''),(1440,'field',150,'en_gb',' 0 '),(1440,'field',147,'en_gb',' 0 '),(1440,'field',148,'en_gb',''),(1440,'field',149,'en_gb',''),(1440,'field',151,'en_gb',' 0 '),(1440,'username',0,'en_gb',' stuart smith lantra co uk '),(1440,'firstname',0,'en_gb',' stuart '),(1440,'lastname',0,'en_gb',' smith '),(1440,'fullname',0,'en_gb',' stuart smith '),(1440,'email',0,'en_gb',' stuart smith lantra co uk '),(1440,'slug',0,'en_gb',''),(2041,'field',144,'en_gb',''),(2041,'field',86,'en_gb',''),(2041,'field',17,'en_gb',' current draft '),(1925,'filename',0,'en_gb',' dairy milk jpg '),(1925,'extension',0,'en_gb',' jpg '),(1925,'kind',0,'en_gb',' image '),(1925,'slug',0,'en_gb',' dairy milk '),(1925,'title',0,'en_gb',' dairy milk '),(1926,'field',29,'en_gb',' pending '),(1926,'field',26,'en_gb',' first quarter '),(1926,'field',33,'en_gb',''),(1926,'field',130,'en_gb',''),(1926,'field',34,'en_gb',''),(1926,'field',87,'en_gb',''),(1926,'field',17,'en_gb',' dairy milk '),(1926,'field',86,'en_gb',''),(1926,'field',142,'en_gb',''),(1926,'field',143,'en_gb',''),(1926,'field',144,'en_gb',''),(1926,'field',58,'en_gb',''),(1926,'field',27,'en_gb',' 0 '),(1926,'slug',0,'en_gb',' unit 1659 portia hartley '),(1926,'title',0,'en_gb',' unit 1659 portia hartley '),(3239,'extension',0,'en_gb',' doc '),(3239,'kind',0,'en_gb',' word '),(3239,'slug',0,'en_gb',' request for ltp sales invoice '),(3239,'title',0,'en_gb',' request for ltp sales invoice '),(3231,'extension',0,'en_gb',' doc '),(3231,'kind',0,'en_gb',' word '),(3231,'slug',0,'en_gb',' request for pp sales invoice '),(3231,'title',0,'en_gb',' request for pp sales invoice '),(1079,'field',172,'en_gb',' contact us '),(3266,'field',187,'en_gb',' 0 '),(2041,'field',142,'en_gb',''),(2041,'field',33,'en_gb',''),(2041,'field',130,'en_gb',''),(2041,'field',34,'en_gb',''),(2041,'field',87,'en_gb',''),(1535,'filename',0,'en_gb',' example evidence docx '),(1535,'extension',0,'en_gb',' docx '),(1535,'kind',0,'en_gb',' word '),(1535,'slug',0,'en_gb',' example evidence '),(1535,'title',0,'en_gb',' example evidence '),(1536,'field',29,'en_gb',' pending '),(1536,'field',26,'en_gb',' demonstrate ability to outline the importance of undertaking inspections '),(1536,'field',33,'en_gb',''),(1536,'field',130,'en_gb',''),(1536,'field',34,'en_gb',''),(1536,'field',87,'en_gb',''),(1536,'field',17,'en_gb',' example evidence '),(1536,'field',86,'en_gb',''),(1536,'field',142,'en_gb',''),(1536,'field',143,'en_gb',''),(1536,'field',144,'en_gb',''),(1536,'field',58,'en_gb',''),(1536,'field',27,'en_gb',' 0 '),(1536,'slug',0,'en_gb',' unit 1479 jason church '),(1536,'title',0,'en_gb',' unit 1479 jason church '),(1537,'filename',0,'en_gb',' example evidence_181107_121324 docx '),(1537,'extension',0,'en_gb',' docx '),(1537,'kind',0,'en_gb',' word '),(1537,'slug',0,'en_gb',' example evidence '),(1537,'title',0,'en_gb',' example evidence '),(1538,'field',29,'en_gb',' pending '),(1538,'field',26,'en_gb',' demonstrate ability to outline the importance of undertaking inspections '),(1538,'field',33,'en_gb',''),(1538,'field',130,'en_gb',''),(1538,'field',34,'en_gb',''),(1538,'field',87,'en_gb',''),(1538,'field',17,'en_gb',' example evidence '),(1538,'field',86,'en_gb',''),(1538,'field',142,'en_gb',''),(1538,'field',143,'en_gb',''),(1538,'field',144,'en_gb',''),(1538,'field',58,'en_gb',''),(1538,'field',27,'en_gb',' 0 '),(1538,'slug',0,'en_gb',' unit 1479 jason church '),(1538,'title',0,'en_gb',' unit 1479 jason church '),(1539,'filename',0,'en_gb',' example evidence_181107_121503 docx '),(1539,'extension',0,'en_gb',' docx '),(1539,'kind',0,'en_gb',' word '),(1539,'slug',0,'en_gb',' example evidence '),(1539,'title',0,'en_gb',' example evidence '),(1540,'field',29,'en_gb',' pending '),(1540,'field',26,'en_gb',' demonstrate ability to outline the importance of undertaking inspections '),(1540,'field',33,'en_gb',''),(1540,'field',130,'en_gb',''),(1540,'field',34,'en_gb',''),(1540,'field',87,'en_gb',''),(1540,'field',17,'en_gb',' example evidence '),(1540,'field',86,'en_gb',''),(1540,'field',142,'en_gb',''),(1540,'field',143,'en_gb',''),(1540,'field',144,'en_gb',''),(1540,'field',58,'en_gb',''),(1540,'field',27,'en_gb',' 0 '),(1540,'slug',0,'en_gb',' unit 1479 jason church '),(1540,'title',0,'en_gb',' unit 1479 jason church '),(1541,'filename',0,'en_gb',' example evidence_181107_121521 docx '),(1541,'extension',0,'en_gb',' docx '),(1541,'kind',0,'en_gb',' word '),(1541,'slug',0,'en_gb',' example evidence '),(1541,'title',0,'en_gb',' example evidence '),(1542,'field',29,'en_gb',' pending '),(1542,'field',26,'en_gb',' demonstrate ability to outline the importance of undertaking inspections '),(1542,'field',33,'en_gb',''),(1542,'field',130,'en_gb',''),(1542,'field',34,'en_gb',''),(1542,'field',87,'en_gb',''),(1542,'field',17,'en_gb',' example evidence '),(1542,'field',86,'en_gb',''),(1542,'field',142,'en_gb',''),(1542,'field',143,'en_gb',''),(1542,'field',144,'en_gb',''),(1542,'field',58,'en_gb',''),(1542,'field',27,'en_gb',' 0 '),(1542,'slug',0,'en_gb',' unit 1479 jason church '),(1542,'title',0,'en_gb',' unit 1479 jason church '),(1543,'filename',0,'en_gb',' example evidence_181107_122321 docx '),(1543,'extension',0,'en_gb',' docx '),(1543,'kind',0,'en_gb',' word '),(1543,'slug',0,'en_gb',' example evidence '),(1543,'title',0,'en_gb',' example evidence '),(1544,'field',29,'en_gb',' pending '),(1544,'field',26,'en_gb',' demonstrate ability to outline the importance of undertaking inspections '),(1544,'field',33,'en_gb',''),(1544,'field',130,'en_gb',''),(1544,'field',34,'en_gb',''),(1544,'field',87,'en_gb',''),(1544,'field',17,'en_gb',' example evidence '),(1544,'field',86,'en_gb',''),(1544,'field',142,'en_gb',''),(1544,'field',143,'en_gb',''),(1544,'field',144,'en_gb',''),(1544,'field',58,'en_gb',''),(1544,'field',27,'en_gb',' 0 '),(1544,'slug',0,'en_gb',' unit 1479 jason church '),(1544,'title',0,'en_gb',' unit 1479 jason church '),(3266,'field',127,'en_gb',''),(3267,'slug',0,'en_gb',''),(3267,'field',126,'en_gb',' payroll '),(3267,'field',183,'en_gb',' 1 '),(3267,'field',125,'en_gb',' payroll '),(3266,'field',189,'en_gb',' 1 '),(3266,'field',190,'en_gb',' 0 '),(3266,'field',124,'en_gb',' payroll payroll 1 '),(3266,'field',186,'en_gb',' 1 '),(3266,'field',184,'en_gb',' 1 '),(3266,'field',188,'en_gb',' 1 '),(3266,'field',185,'en_gb',' 1 '),(3266,'slug',0,'en_gb',''),(1896,'filename',0,'en_gb',' example evidence pdf '),(1896,'extension',0,'en_gb',' pdf '),(1896,'kind',0,'en_gb',' pdf '),(1896,'slug',0,'en_gb',' example evidence '),(1896,'title',0,'en_gb',' example evidence '),(1895,'kind',0,'en_gb',' text '),(1895,'slug',0,'en_gb',' example evidence '),(1895,'title',0,'en_gb',' example evidence '),(1895,'extension',0,'en_gb',' txt '),(1895,'filename',0,'en_gb',' example evidence txt '),(3239,'filename',0,'en_gb',' request for ltp sales invoice doc '),(1687,'filename',0,'en_gb',' desert jpg '),(1687,'extension',0,'en_gb',' jpg '),(1687,'kind',0,'en_gb',' image '),(1687,'slug',0,'en_gb',' desert '),(1687,'title',0,'en_gb',' desert '),(1688,'field',29,'en_gb',' pending '),(1688,'field',26,'en_gb',' first quarter '),(1688,'field',33,'en_gb',''),(1688,'field',130,'en_gb',''),(1688,'field',34,'en_gb',''),(1688,'field',87,'en_gb',''),(1688,'field',17,'en_gb',' desert '),(1688,'field',86,'en_gb',''),(1688,'field',142,'en_gb',''),(1688,'field',143,'en_gb',''),(1688,'field',144,'en_gb',''),(1688,'field',58,'en_gb',''),(1688,'field',27,'en_gb',' 0 '),(1688,'slug',0,'en_gb',' unit 1659 billy hives '),(1688,'title',0,'en_gb',' unit 1659 billy hives '),(1683,'field',67,'en_gb',' bacteria test '),(1683,'field',146,'en_gb',' 0 '),(1683,'slug',0,'en_gb',' dog grooming '),(1683,'title',0,'en_gb',' dog grooming '),(1683,'field',130,'en_gb',' 1 '),(1683,'field',86,'en_gb',''),(1683,'field',163,'en_gb',''),(1683,'field',144,'en_gb',''),(1683,'field',17,'en_gb',' koala '),(1683,'field',142,'en_gb',''),(1683,'field',143,'en_gb',''),(1683,'field',33,'en_gb',''),(1683,'field',34,'en_gb',''),(1683,'field',87,'en_gb',''),(1683,'field',29,'en_gb',' pending '),(1682,'title',0,'en_gb',' koala '),(1682,'slug',0,'en_gb',' koala '),(1682,'filename',0,'en_gb',' koala_181207_112804 jpg '),(1682,'extension',0,'en_gb',' jpg '),(1682,'kind',0,'en_gb',' image '),(1679,'title',0,'en_gb',' unit 1656 margaret murray '),(1679,'slug',0,'en_gb',' unit 1656 margaret murray '),(1679,'field',58,'en_gb',''),(1679,'field',27,'en_gb',' 0 '),(1679,'field',143,'en_gb',''),(1679,'field',144,'en_gb',''),(1679,'field',142,'en_gb',''),(1679,'field',86,'en_gb',''),(1679,'field',17,'en_gb',' koala '),(1679,'field',33,'en_gb',''),(1679,'field',130,'en_gb',''),(1679,'field',34,'en_gb',''),(1679,'field',87,'en_gb',''),(1679,'field',26,'en_gb',' professional indemnity '),(1679,'field',29,'en_gb',' pending '),(1678,'slug',0,'en_gb',' koala '),(1678,'title',0,'en_gb',' koala '),(2089,'filename',0,'en_gb',' lantra_logo png '),(1678,'filename',0,'en_gb',' koala jpg '),(1678,'kind',0,'en_gb',' image '),(1678,'extension',0,'en_gb',' jpg '),(1591,'filename',0,'en_gb',' about_us jpg '),(1591,'extension',0,'en_gb',' jpg '),(1591,'kind',0,'en_gb',' image '),(1591,'slug',0,'en_gb',' about us '),(1591,'title',0,'en_gb',' about us '),(1595,'field',112,'en_gb',''),(1595,'field',115,'en_gb',''),(1595,'field',2,'en_gb',' learning a new skill doesn t have to interrupt your busy schedule our on demand videos and interactive code challenges are there for you when you need them '),(1595,'field',1,'en_gb',''),(1595,'slug',0,'en_gb',' faqs '),(1595,'title',0,'en_gb',' faqs '),(1762,'title',0,'en_gb',' site admin '),(1762,'field',150,'en_gb',' 0 '),(1762,'slug',0,'en_gb',' site admin '),(2047,'filename',0,'en_gb',' unit_181211_093556 png '),(2040,'kind',0,'en_gb',' image '),(2040,'filename',0,'en_gb',' current draft jpg '),(2040,'extension',0,'en_gb',' jpg '),(2040,'slug',0,'en_gb',' current draft '),(2040,'title',0,'en_gb',' current draft '),(2041,'field',29,'en_gb',' pending '),(2041,'field',26,'en_gb',' public liability '),(2041,'field',143,'en_gb',''),(2041,'field',58,'en_gb',''),(2041,'field',27,'en_gb',' 0 '),(2041,'slug',0,'en_gb',' unit 1657 robin willmott '),(2041,'title',0,'en_gb',' unit 1657 robin willmott '),(2048,'field',142,'en_gb',''),(2048,'field',143,'en_gb',''),(2048,'field',144,'en_gb',''),(2048,'field',86,'en_gb',''),(2048,'field',33,'en_gb',''),(2048,'field',130,'en_gb',''),(2048,'field',34,'en_gb',''),(2048,'field',87,'en_gb',''),(2048,'field',17,'en_gb',' unit '),(2048,'field',26,'en_gb',' public liability '),(2047,'title',0,'en_gb',' unit '),(2048,'field',29,'en_gb',' pending '),(2047,'slug',0,'en_gb',' unit '),(2047,'extension',0,'en_gb',' png '),(1620,'field',113,'en_gb',''),(1620,'field',114,'en_gb',' you can send a direct contact to lantra by completing this form we will get back to you with our answer by either telephone or email depending on which option that you chose we aim to reply to all enquiries by the end of the next working day send us an email '),(1,'field',170,'en_gb',' 0 '),(1620,'slug',0,'en_gb',''),(1620,'field',165,'en_gb',' hello '),(2047,'kind',0,'en_gb',' image '),(1,'field',150,'en_gb',' 0 '),(1,'field',147,'en_gb',' 0 '),(1,'field',148,'en_gb',''),(1,'field',149,'en_gb',''),(1,'field',151,'en_gb',' 0 '),(2048,'field',58,'en_gb',''),(2048,'field',27,'en_gb',' 0 '),(2048,'slug',0,'en_gb',' unit 1657 robin willmott '),(2048,'title',0,'en_gb',' unit 1657 robin willmott '),(3231,'filename',0,'en_gb',' request for pp sales invoice doc '),(2058,'filename',0,'en_gb',' unit jpg '),(2058,'extension',0,'en_gb',' jpg '),(2058,'kind',0,'en_gb',' image '),(2058,'slug',0,'en_gb',' unit '),(2058,'title',0,'en_gb',' unit '),(2059,'field',29,'en_gb',' pending '),(2059,'field',26,'en_gb',' public liability '),(2059,'field',33,'en_gb',''),(2059,'field',130,'en_gb',''),(2059,'field',34,'en_gb',''),(2059,'field',87,'en_gb',''),(2059,'field',17,'en_gb',' unit '),(2059,'field',86,'en_gb',''),(2059,'field',142,'en_gb',''),(2059,'field',143,'en_gb',''),(2059,'field',144,'en_gb',''),(2059,'field',58,'en_gb',''),(2059,'field',27,'en_gb',' 0 '),(2059,'slug',0,'en_gb',' unit 1657 robin willmott '),(2059,'title',0,'en_gb',' unit 1657 robin willmott '),(2078,'filename',0,'en_gb',' desert jpg '),(2078,'extension',0,'en_gb',' jpg '),(2078,'kind',0,'en_gb',' image '),(2078,'slug',0,'en_gb',' desert '),(2078,'title',0,'en_gb',' desert '),(2079,'field',29,'en_gb',' pending '),(2079,'field',26,'en_gb',' yearly renewal t c s '),(2079,'field',33,'en_gb',''),(2079,'field',130,'en_gb',''),(2079,'field',34,'en_gb',''),(2079,'field',87,'en_gb',''),(2079,'field',17,'en_gb',' desert '),(2079,'field',86,'en_gb',''),(2079,'field',142,'en_gb',''),(2079,'field',143,'en_gb',''),(2079,'field',144,'en_gb',''),(2079,'field',58,'en_gb',''),(2079,'field',27,'en_gb',' 0 '),(2079,'slug',0,'en_gb',' unit 1658 margaret murray '),(2079,'title',0,'en_gb',' unit 1658 margaret murray '),(2089,'extension',0,'en_gb',' png '),(2089,'kind',0,'en_gb',' image '),(2089,'slug',0,'en_gb',' lantra logo '),(2089,'title',0,'en_gb',' lantra logo '),(2090,'field',29,'en_gb',' pending '),(2090,'field',26,'en_gb',' induction '),(2090,'field',33,'en_gb',''),(2090,'field',130,'en_gb',''),(2090,'field',34,'en_gb',''),(2090,'field',87,'en_gb',''),(2090,'field',17,'en_gb',' lantra logo '),(2090,'field',86,'en_gb',''),(2090,'field',142,'en_gb',''),(2090,'field',143,'en_gb',''),(2090,'field',144,'en_gb',' stoneleigh '),(2090,'field',58,'en_gb',''),(2090,'field',27,'en_gb',' 0 '),(2090,'slug',0,'en_gb',' unit 2070 portia hartley '),(2090,'title',0,'en_gb',' unit 2070 portia hartley '),(1438,'field',170,'en_gb',' 0 '),(2315,'kind',0,'en_gb',' image '),(2315,'slug',0,'en_gb',' unit '),(2315,'title',0,'en_gb',' unit '),(2315,'extension',0,'en_gb',' png '),(2315,'filename',0,'en_gb',' unit_181212_070405 png '),(2269,'filename',0,'en_gb',' unit_181212_031035 png '),(2269,'extension',0,'en_gb',' png '),(2269,'kind',0,'en_gb',' image '),(2269,'slug',0,'en_gb',' unit '),(2269,'title',0,'en_gb',' unit '),(2270,'field',29,'en_gb',' pending '),(2270,'field',33,'en_gb',''),(2270,'field',34,'en_gb',''),(2270,'field',87,'en_gb',''),(2270,'field',17,'en_gb',' unit '),(2270,'field',142,'en_gb',''),(2270,'field',143,'en_gb',''),(2270,'field',144,'en_gb',''),(2270,'field',163,'en_gb',''),(2270,'field',86,'en_gb',''),(2270,'field',130,'en_gb',' 1 '),(2270,'field',67,'en_gb',' level 3 training '),(2270,'field',146,'en_gb',' 0 '),(2270,'slug',0,'en_gb',' test result '),(2270,'title',0,'en_gb',' test result '),(2271,'filename',0,'en_gb',' unit_181212_031132 png '),(2271,'extension',0,'en_gb',' png '),(2271,'kind',0,'en_gb',' image '),(2271,'slug',0,'en_gb',' unit '),(2271,'title',0,'en_gb',' unit '),(2272,'field',29,'en_gb',' pending '),(2272,'field',33,'en_gb',''),(2272,'field',34,'en_gb',''),(2272,'field',87,'en_gb',''),(2272,'field',17,'en_gb',' unit '),(2272,'field',142,'en_gb',''),(2272,'field',143,'en_gb',''),(2272,'field',144,'en_gb',''),(2272,'field',163,'en_gb',''),(2272,'field',86,'en_gb',''),(2272,'field',130,'en_gb',' 1 '),(2272,'field',67,'en_gb',' level 3 training '),(2272,'field',146,'en_gb',' 0 '),(2272,'slug',0,'en_gb',' test result '),(2272,'title',0,'en_gb',' test result '),(2273,'filename',0,'en_gb',' unit_181212_031309 png '),(2273,'extension',0,'en_gb',' png '),(2273,'kind',0,'en_gb',' image '),(2273,'slug',0,'en_gb',' unit '),(2273,'title',0,'en_gb',' unit '),(2274,'field',29,'en_gb',' pending '),(2274,'field',33,'en_gb',''),(2274,'field',34,'en_gb',''),(2274,'field',87,'en_gb',''),(2274,'field',17,'en_gb',' unit '),(2274,'field',142,'en_gb',''),(2274,'field',143,'en_gb',''),(2274,'field',144,'en_gb',''),(2274,'field',163,'en_gb',''),(2274,'field',86,'en_gb',''),(2274,'field',130,'en_gb',' 1 '),(2274,'field',67,'en_gb',' level 3 training '),(2274,'field',146,'en_gb',' 0 '),(2274,'slug',0,'en_gb',' test result '),(2274,'title',0,'en_gb',' test result '),(2275,'filename',0,'en_gb',' unit_181212_053614 jpg '),(2275,'extension',0,'en_gb',' jpg '),(2275,'kind',0,'en_gb',' image '),(2275,'slug',0,'en_gb',' unit '),(2275,'title',0,'en_gb',' unit '),(2276,'field',29,'en_gb',' pending '),(2276,'field',33,'en_gb',''),(2276,'field',34,'en_gb',''),(2276,'field',87,'en_gb',''),(2276,'field',17,'en_gb',' unit '),(2276,'field',142,'en_gb',''),(2276,'field',143,'en_gb',''),(2276,'field',144,'en_gb',''),(2276,'field',163,'en_gb',''),(2276,'field',86,'en_gb',''),(2276,'field',130,'en_gb',' 1 '),(2276,'field',67,'en_gb',' cpd '),(2276,'field',146,'en_gb',' 0 '),(2276,'slug',0,'en_gb',' test result '),(2276,'title',0,'en_gb',' test result '),(2277,'filename',0,'en_gb',' unit_181212_053630 png '),(2277,'extension',0,'en_gb',' png '),(2277,'kind',0,'en_gb',' image '),(2277,'slug',0,'en_gb',' unit '),(2277,'title',0,'en_gb',' unit '),(2278,'field',29,'en_gb',' pending '),(2278,'field',33,'en_gb',''),(2278,'field',34,'en_gb',''),(2278,'field',87,'en_gb',''),(2278,'field',17,'en_gb',' unit '),(2278,'field',142,'en_gb',''),(2278,'field',143,'en_gb',''),(2278,'field',144,'en_gb',''),(2278,'field',163,'en_gb',''),(2278,'field',86,'en_gb',''),(2278,'field',130,'en_gb',' 1 '),(2278,'field',67,'en_gb',' cpd '),(2278,'field',146,'en_gb',' 0 '),(2278,'slug',0,'en_gb',' test result '),(2278,'title',0,'en_gb',' test result '),(2279,'filename',0,'en_gb',' unit_181212_054206 png '),(2279,'extension',0,'en_gb',' png '),(2279,'kind',0,'en_gb',' image '),(2279,'slug',0,'en_gb',' unit '),(2279,'title',0,'en_gb',' unit '),(2280,'field',29,'en_gb',' pending '),(2280,'field',33,'en_gb',''),(2280,'field',34,'en_gb',''),(2280,'field',87,'en_gb',''),(2280,'field',17,'en_gb',' unit '),(2280,'field',142,'en_gb',''),(2280,'field',143,'en_gb',''),(2280,'field',144,'en_gb',''),(2280,'field',163,'en_gb',''),(2280,'field',86,'en_gb',''),(2280,'field',130,'en_gb',' 1 '),(2280,'field',67,'en_gb',' cpd '),(2280,'field',146,'en_gb',' 0 '),(2280,'slug',0,'en_gb',' test result '),(2280,'title',0,'en_gb',' test result '),(2281,'filename',0,'en_gb',' unit_181212_055558 png '),(2281,'extension',0,'en_gb',' png '),(2281,'kind',0,'en_gb',' image '),(2281,'slug',0,'en_gb',' unit '),(2281,'title',0,'en_gb',' unit '),(2282,'field',29,'en_gb',' pending '),(2282,'field',33,'en_gb',''),(2282,'field',34,'en_gb',''),(2282,'field',87,'en_gb',''),(2282,'field',17,'en_gb',' unit '),(2282,'field',142,'en_gb',''),(2282,'field',143,'en_gb',''),(2282,'field',144,'en_gb',''),(2282,'field',163,'en_gb',''),(2282,'field',86,'en_gb',''),(2282,'field',130,'en_gb',' 1 '),(2282,'field',67,'en_gb',' cpd '),(2282,'field',146,'en_gb',' 0 '),(2282,'slug',0,'en_gb',' test result '),(2282,'title',0,'en_gb',' test result '),(2283,'filename',0,'en_gb',' unit_181212_055625 png '),(2283,'extension',0,'en_gb',' png '),(2283,'kind',0,'en_gb',' image '),(2283,'slug',0,'en_gb',' unit '),(2283,'title',0,'en_gb',' unit '),(2284,'field',29,'en_gb',' pending '),(2284,'field',33,'en_gb',''),(2284,'field',34,'en_gb',''),(2284,'field',87,'en_gb',''),(2284,'field',17,'en_gb',' unit '),(2284,'field',142,'en_gb',''),(2284,'field',143,'en_gb',''),(2284,'field',144,'en_gb',''),(2284,'field',163,'en_gb',''),(2284,'field',86,'en_gb',''),(2284,'field',130,'en_gb',' 1 '),(2284,'field',67,'en_gb',' cpd '),(2284,'field',146,'en_gb',' 0 '),(2284,'slug',0,'en_gb',' test result '),(2284,'title',0,'en_gb',' test result '),(2285,'filename',0,'en_gb',' unit_181212_064134 png '),(2285,'extension',0,'en_gb',' png '),(2285,'kind',0,'en_gb',' image '),(2285,'slug',0,'en_gb',' unit '),(2285,'title',0,'en_gb',' unit '),(2286,'field',29,'en_gb',' pending '),(2286,'field',33,'en_gb',''),(2286,'field',34,'en_gb',''),(2286,'field',87,'en_gb',''),(2286,'field',17,'en_gb',' unit '),(2286,'field',142,'en_gb',''),(2286,'field',143,'en_gb',''),(2286,'field',144,'en_gb',''),(2286,'field',163,'en_gb',''),(2286,'field',86,'en_gb',''),(2286,'field',130,'en_gb',' 1 '),(2286,'field',67,'en_gb',' cpd '),(2286,'field',146,'en_gb',' 0 '),(2286,'slug',0,'en_gb',' test result '),(2286,'title',0,'en_gb',' test result '),(2287,'filename',0,'en_gb',' unit_181212_064451 png '),(2287,'extension',0,'en_gb',' png '),(2287,'kind',0,'en_gb',' image '),(2287,'slug',0,'en_gb',' unit '),(2287,'title',0,'en_gb',' unit '),(2288,'field',29,'en_gb',' pending '),(2288,'field',33,'en_gb',''),(2288,'field',34,'en_gb',''),(2288,'field',87,'en_gb',''),(2288,'field',17,'en_gb',' unit '),(2288,'field',142,'en_gb',''),(2288,'field',143,'en_gb',''),(2288,'field',144,'en_gb',''),(2288,'field',163,'en_gb',''),(2288,'field',86,'en_gb',''),(2288,'field',130,'en_gb',' 1 '),(2288,'field',67,'en_gb',' cpd '),(2288,'field',146,'en_gb',' 0 '),(2288,'slug',0,'en_gb',' test result '),(2288,'title',0,'en_gb',' test result '),(2289,'filename',0,'en_gb',' unit_181212_064659 png '),(2289,'extension',0,'en_gb',' png '),(2289,'kind',0,'en_gb',' image '),(2289,'slug',0,'en_gb',' unit '),(2289,'title',0,'en_gb',' unit '),(2290,'field',29,'en_gb',' pending '),(2290,'field',33,'en_gb',''),(2290,'field',34,'en_gb',''),(2290,'field',87,'en_gb',''),(2290,'field',17,'en_gb',' unit '),(2290,'field',142,'en_gb',''),(2290,'field',143,'en_gb',''),(2290,'field',144,'en_gb',''),(2290,'field',163,'en_gb',''),(2290,'field',86,'en_gb',''),(2290,'field',130,'en_gb',' 1 '),(2290,'field',67,'en_gb',' cpd '),(2290,'field',146,'en_gb',' 0 '),(2290,'slug',0,'en_gb',' test result '),(2290,'title',0,'en_gb',' test result '),(2291,'filename',0,'en_gb',' unit_181212_064710 jpg '),(2291,'extension',0,'en_gb',' jpg '),(2291,'kind',0,'en_gb',' image '),(2291,'slug',0,'en_gb',' unit '),(2291,'title',0,'en_gb',' unit '),(2292,'field',29,'en_gb',' pending '),(2292,'field',33,'en_gb',''),(2292,'field',34,'en_gb',''),(2292,'field',87,'en_gb',''),(2292,'field',17,'en_gb',' unit '),(2292,'field',142,'en_gb',''),(2292,'field',143,'en_gb',''),(2292,'field',144,'en_gb',''),(2292,'field',163,'en_gb',''),(2292,'field',86,'en_gb',''),(2292,'field',130,'en_gb',' 1 '),(2292,'field',67,'en_gb',' cpd '),(2292,'field',146,'en_gb',' 0 '),(2292,'slug',0,'en_gb',' test result '),(2292,'title',0,'en_gb',' test result '),(3169,'field',168,'en_gb',' resultlocation '),(3169,'slug',0,'en_gb',''),(3169,'field',169,'en_gb',''),(143,'field',193,'en_gb',''),(3183,'field',168,'en_gb',' resultexpirydate '),(3183,'field',169,'en_gb',''),(3183,'slug',0,'en_gb',''),(488,'field',176,'en_gb',' margaret murray lantra co uk portia hartley skills plus co uk '),(2485,'field',125,'en_gb',' custom field 1 '),(2485,'field',126,'en_gb',''),(2485,'slug',0,'en_gb',''),(143,'field',170,'en_gb',' 0 '),(143,'field',173,'en_gb',''),(143,'field',150,'en_gb',' 0 '),(143,'field',147,'en_gb',' 0 '),(143,'field',148,'en_gb',''),(143,'field',149,'en_gb',''),(143,'field',151,'en_gb',' 0 '),(3255,'title',0,'en_gb',' request for ptr sales invoice '),(3255,'slug',0,'en_gb',' request for ptr sales invoice '),(3255,'kind',0,'en_gb',' word '),(3255,'extension',0,'en_gb',' doc '),(3255,'filename',0,'en_gb',' request for ptr sales invoice doc '),(1079,'field',167,'en_gb',' unittype unitvalue resultstatus resultendorseddate resultlocation resultexpirydate '),(2528,'field',168,'en_gb',' unittype '),(2528,'field',169,'en_gb',''),(2528,'slug',0,'en_gb',''),(2529,'field',168,'en_gb',' unitvalue '),(2529,'field',169,'en_gb',''),(2529,'slug',0,'en_gb',''),(2530,'field',168,'en_gb',' resultstatus '),(2530,'field',169,'en_gb',''),(2530,'slug',0,'en_gb',''),(2531,'field',168,'en_gb',' resultendorseddate '),(2531,'field',169,'en_gb',''),(2531,'slug',0,'en_gb',''),(488,'field',182,'en_gb',' 0 '),(2541,'field',180,'en_gb',' 2019 01 21 '),(2541,'slug',0,'en_gb',''),(2542,'field',178,'en_gb',' robin coffeebean design '),(2541,'field',178,'en_gb',' robin coffeebean design '),(2541,'field',179,'en_gb',' fffff '),(2542,'field',179,'en_gb',' fffff '),(2542,'field',180,'en_gb',' 2019 01 21 '),(2542,'slug',0,'en_gb',''),(2543,'field',178,'en_gb',' robin coffeebean design '),(2543,'field',179,'en_gb',' ddddd '),(2543,'field',180,'en_gb',' 2019 01 21 '),(2543,'slug',0,'en_gb',''),(2544,'field',178,'en_gb',' robin coffeebean design '),(2544,'field',179,'en_gb',' ddddd '),(2544,'field',180,'en_gb',' 2019 01 21 '),(2544,'slug',0,'en_gb',''),(2546,'field',178,'en_gb',' robin coffeebean design '),(2546,'field',179,'en_gb',' sssss '),(2546,'field',180,'en_gb',' 2019 01 21 '),(2546,'slug',0,'en_gb',''),(2547,'field',178,'en_gb',' robin coffeebean design '),(2547,'field',179,'en_gb',' sssss '),(2547,'field',180,'en_gb',' 2019 01 21 '),(2547,'slug',0,'en_gb',''),(2548,'field',178,'en_gb',' robin coffeebean design '),(2548,'field',179,'en_gb',' sssss '),(2548,'field',180,'en_gb',' 2019 01 21 '),(2548,'slug',0,'en_gb',''),(2549,'field',178,'en_gb',' robin coffeebean design '),(2549,'field',179,'en_gb',' sssss '),(2549,'field',180,'en_gb',' 2019 01 21 '),(2549,'slug',0,'en_gb',''),(3124,'slug',0,'en_gb',''),(3124,'field',180,'en_gb',' 2019 01 21 '),(3124,'field',179,'en_gb',' xcvxvczxcvzxcv '),(3124,'field',178,'en_gb',' robin coffeebean design '),(3125,'field',178,'en_gb',' robin coffeebean design '),(3125,'field',179,'en_gb',' xsxxxssdafasdfa '),(3125,'field',180,'en_gb',' 2019 01 21 '),(3125,'slug',0,'en_gb',''),(3126,'field',178,'en_gb',' robin coffeebean design '),(3126,'field',179,'en_gb',' ccccc '),(3126,'field',180,'en_gb',' 2019 01 21 '),(3126,'slug',0,'en_gb',''),(3127,'field',178,'en_gb',' robin coffeebean design '),(3127,'field',179,'en_gb',' cxcxc '),(3127,'field',180,'en_gb',' 2019 01 21 '),(3127,'slug',0,'en_gb','');
/*!40000 ALTER TABLE `craft_searchindex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_sections`
--

DROP TABLE IF EXISTS `craft_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `structureId` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('single','channel','structure') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'channel',
  `hasUrls` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `template` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enableVersioning` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_sections_name_unq_idx` (`name`),
  UNIQUE KEY `craft_sections_handle_unq_idx` (`handle`),
  KEY `craft_sections_structureId_fk` (`structureId`),
  CONSTRAINT `craft_sections_structureId_fk` FOREIGN KEY (`structureId`) REFERENCES `craft_structures` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_sections`
--

LOCK TABLES `craft_sections` WRITE;
/*!40000 ALTER TABLE `craft_sections` DISABLE KEYS */;
INSERT INTO `craft_sections` VALUES (3,NULL,'Companies','companies','channel',0,NULL,0,'2017-10-23 13:43:33','2017-10-23 13:43:33','86faf121-d218-4348-8ab8-de457b46aa63'),(5,NULL,'Teams','teams','channel',0,NULL,0,'2017-10-23 14:46:37','2017-10-23 14:46:37','bcf0ea1d-9495-423e-ac83-ef089790460c'),(6,3,'Modules','modules','structure',1,'module/_entry',0,'2017-10-24 09:39:33','2019-01-24 11:47:39','e9e30b72-2c16-4035-b4b9-e1e4c42ebb3a'),(7,NULL,'Units','units','channel',0,NULL,0,'2017-10-24 09:45:57','2018-05-02 10:04:43','090b3c24-a9ac-4935-a919-c4fc8a3099b0'),(10,NULL,'Results','results','channel',0,NULL,1,'2018-02-14 12:41:38','2019-04-25 13:26:07','c0345752-9e56-4de1-a2d2-da641c3708e0'),(12,NULL,'Attempts','attempts','channel',0,NULL,1,'2018-04-13 10:12:01','2018-04-13 10:32:47','51a18e4b-1a36-4210-8769-a5b8f28f3a5f'),(13,NULL,'Reports','reports','channel',1,'reporting/custom/_entry',0,'2018-08-13 12:10:21','2018-09-04 13:48:46','1d590683-b16e-4955-be62-315accb3e628'),(14,NULL,'Pages','pages','channel',1,'pages/_entry',1,'2018-08-21 13:08:51','2018-08-23 08:45:08','8c1af673-0f01-4434-a6f0-86f01675d799');
/*!40000 ALTER TABLE `craft_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_sections_i18n`
--

DROP TABLE IF EXISTS `craft_sections_i18n`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_sections_i18n` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sectionId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `enabledByDefault` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `urlFormat` text COLLATE utf8_unicode_ci,
  `nestedUrlFormat` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_sections_i18n_sectionId_locale_unq_idx` (`sectionId`,`locale`),
  KEY `craft_sections_i18n_locale_fk` (`locale`),
  CONSTRAINT `craft_sections_i18n_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `craft_sections_i18n_sectionId_fk` FOREIGN KEY (`sectionId`) REFERENCES `craft_sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_sections_i18n`
--

LOCK TABLES `craft_sections_i18n` WRITE;
/*!40000 ALTER TABLE `craft_sections_i18n` DISABLE KEYS */;
INSERT INTO `craft_sections_i18n` VALUES (3,3,'en_gb',1,NULL,NULL,'2017-10-23 13:43:33','2017-10-23 13:43:33','cd55a2f6-8399-4ff0-abe0-af7f75fdbdba'),(5,5,'en_gb',1,NULL,NULL,'2017-10-23 14:46:37','2017-10-23 14:46:37','b21f333b-9eff-4ec8-bc05-7d496ac020c5'),(6,6,'en_gb',1,'module/{id}',NULL,'2017-10-24 09:39:33','2018-05-02 10:05:20','74e5321a-1a80-4bdc-ae84-7450381b7a94'),(7,7,'en_gb',1,NULL,NULL,'2017-10-24 09:45:57','2018-05-02 10:04:43','06404fae-9e1c-4c57-984c-60ab0d27b2e9'),(10,10,'en_gb',1,NULL,NULL,'2018-02-14 12:41:38','2018-02-14 12:41:38','1c6dc525-e6b6-4104-8495-b669c056941e'),(12,12,'en_gb',1,NULL,NULL,'2018-04-13 10:12:01','2018-04-13 10:12:01','b5c83c2c-5948-4f7c-80f2-ff1713450d96'),(13,13,'en_gb',1,'reporting/custom/{id}',NULL,'2018-08-13 12:10:21','2018-09-04 13:48:46','30f2b2ce-7b11-472d-a632-02f8c551619f'),(14,14,'en_gb',1,'public/{slug}',NULL,'2018-08-21 13:08:51','2018-08-23 08:45:08','a90733f3-2a77-445d-9bd1-e22e00019e0f');
/*!40000 ALTER TABLE `craft_sections_i18n` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_sessions`
--

DROP TABLE IF EXISTS `craft_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `token` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_sessions_uid_idx` (`uid`),
  KEY `craft_sessions_token_idx` (`token`),
  KEY `craft_sessions_dateUpdated_idx` (`dateUpdated`),
  KEY `craft_sessions_userId_fk` (`userId`),
  CONSTRAINT `craft_sessions_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1517 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_sessions`
--

LOCK TABLES `craft_sessions` WRITE;
/*!40000 ALTER TABLE `craft_sessions` DISABLE KEYS */;
INSERT INTO `craft_sessions` VALUES (846,1,'579aea9de4c644bc296d4b0bf991edb465b5fde3czozMjoiM3M1SGY4MWtodkM5WWVRQXI5X1pOUHA5OTR1aDF3bGIiOw==','2018-11-15 15:36:40','2018-11-15 15:36:40','0a57c20f-bb64-434d-840b-0d9f5dba2feb'),(847,1,'759202993102bcccea4611ce7777e59ed8db55ffczozMjoiMWsxNkZEN3J6SnhJcUJubkE5Vn4wNzVmZllhaXJ0YVgiOw==','2018-11-15 16:01:02','2018-11-15 16:01:02','f77e48da-b2a1-4005-b44b-4e72692f960b'),(851,1,'d18fd4c3daecf0a8b7685f2eb303b1d0e2e646daczozMjoiRVlEV2FTb2hsbGVPTnlRSmJVWUp1c3BscGtHam5YZHoiOw==','2018-11-16 09:17:40','2018-11-16 09:17:40','b9a482ee-9b4c-49b1-be27-5203e8a92809'),(853,1,'0c60afc947ffd9395c8f10d830a86a70f90e1237czozMjoiZmt+azVoYkJneFV3X353cWdjOUtEQ201NlFqMWgwbmQiOw==','2018-11-16 09:49:11','2018-11-16 09:49:11','6053535c-895f-47a3-8005-b869fd960301'),(863,1,'656badb8867b117b323bb61a6721658c83631367czozMjoiTW1pbVJxSHBTYzRKR0t0U2dGSjVsYVRKdXcza35ZR2YiOw==','2018-11-20 11:44:22','2018-11-20 11:44:22','26d76c6c-3312-45eb-bf3c-bc3f405d94e9'),(864,1,'e23d814653b0c37c0edc7b49fb49d83b4beaff39czozMjoiN0hNRVVSMkR6Zk5XVH5TemxKNjhUfjcwNWVidERwRnciOw==','2018-11-20 12:09:34','2018-11-20 12:09:34','0a3b80c5-e2da-4714-8c8f-ee3427d97540'),(865,1,'7f5655043320fc4c2d013b392198b62506b68687czozMjoiMmgxMmRScFE2WDRVMTJQZFJnYWo1eVVVbUpXS3BDd2UiOw==','2018-11-20 12:28:44','2018-11-20 12:28:44','e5de4435-02bd-42c4-bec3-060275417c6b'),(866,1,'4fc532de8a4d9063aca824d1515d264dc15ef468czozMjoiNEVkRGdUQTRIczU2T0U1aGcxd1JaSWg5RG1RamZqWV8iOw==','2018-11-20 12:44:17','2018-11-20 12:44:17','23211e52-5ac6-4a32-a3b6-c4258bbb1f63'),(867,1,'d98fafb3899ab1f5d80f1b84a53916d47421c80dczozMjoiQU4zRWRZZF9pRjUwTEpFcEZEUkdVcVZUWFhrTl9nb0ciOw==','2018-11-20 12:47:37','2018-11-20 12:47:37','02913d54-a8cc-4b3f-8c61-90d1510c7442'),(868,1,'efd45a698fe9d40255c9b1e0343e50be76ee743aczozMjoiM1doNERDbm5OTFp5WjhqRVdXU2p6N2Z+Z0dPVXdkVEMiOw==','2018-11-20 12:49:11','2018-11-20 13:41:47','82b52708-655c-457f-8b86-0dfd2e31b4e7'),(869,1,'df1a0db89a58d1053ea56e0a2a46539f75043b70czozMjoiTDR0RXg4RWM1RWZJeDQ5VG1xNX5mTms0MTZzeUI4cmYiOw==','2018-11-20 14:05:51','2018-11-20 14:05:51','df9b15d7-67c3-457a-9c10-2a3259875539'),(870,1,'80566d770e26ccf64b0e291d28ff50930054de4eczozMjoiaXJZVEpRT0l+SDlWcDRIc0F1SkZabWJjc1BHZnRTYXIiOw==','2018-11-20 14:06:04','2018-11-20 14:06:04','25eb6c58-66a7-4d2c-a2c7-29f3398d113e'),(871,1,'aa54eda4c754ca6f0f1b68d38e738594071f989dczozMjoiU3FqeWNmOTVMZTA0SEdtMGZiR3lNdWxseGRXdFZFQnIiOw==','2018-11-20 14:07:58','2018-11-20 14:07:58','64a42217-4e91-477b-aad2-2c81afc6f64b'),(872,1,'e97af4e0ab5a5df6790e06e530433215dfd4fb8bczozMjoiN09lNDMwdDhXa292Un5zcEZmR3NSdH5GRDFkWVRyU3YiOw==','2018-11-20 14:45:40','2018-11-20 14:45:40','9d19dd3b-1088-4615-a80c-aaa8cc0e1a5a'),(873,1,'a72188ef5a6f78b5ac6a8564ef17cead6b9cc727czozMjoiZVBJelQ2aGc2THFLaTRzNHFQX0g4Z0NXRWR6TXNXdk8iOw==','2018-11-20 14:47:22','2018-11-20 14:47:22','77ed6a83-ca31-4a65-97b3-e885de6b38e8'),(874,1,'d2f3108444e3fc5b59f6ef97792f3c77378800d8czozMjoieHFUa0FJUEJMU2JvVWxFVWU4bUpZTlRLYzNhUDlEUEciOw==','2018-11-20 14:48:12','2018-11-20 14:48:12','cc74906a-d37d-486f-9f15-e94ce1f29602'),(875,1,'dda5883b9e19b9e31825e291fcbc57681bdb4536czozMjoibUF3N2w3SHk1aXR1SFlSMno5SW1CbkR5Y3VsOVQ5WEUiOw==','2018-11-20 14:49:25','2018-11-20 14:49:25','bb9096fc-46b1-4876-8d5c-9d74fd3937a8'),(876,1,'a9ca174d992ded7392dcd53742210ec75ab7a0d8czozMjoifjdrc1hufmtIczdhZUtQOU9XYzdQRGJ5TFQ3Q25rY18iOw==','2018-11-20 15:40:06','2018-11-20 15:40:06','99970189-6e3a-4d01-bd37-d88b59eb8201'),(877,1,'05accbf80df03d9c518cf578595b166f22c1b785czozMjoiSUdLMHo3dzJfNW5wMWFEMk16cWZQSGhQbzFuNTM2YV8iOw==','2018-11-20 15:53:53','2018-11-20 15:53:53','f045fc76-0094-4194-b590-0546e822c312'),(878,1,'6690f09ee452d1f0aa5369d8abba310a324e830dczozMjoiNlRITzhTZzRldkhfVklSRGlaSjE3WjUwWnBfb2NtOU8iOw==','2018-11-20 15:56:17','2018-11-20 15:56:17','449d33c2-1a85-4f26-83a4-fe2ebe4e7bda'),(879,1,'bc5443232901b0fa45889ee3bc95ccc5b009a84dczozMjoiRnBXWTNzTnd2ZWFWWGhlQzA2aVF0NUx1ZEhNelNSWGYiOw==','2018-11-20 16:01:27','2018-11-20 16:01:27','a444dfa8-3a0f-4d17-8d8b-93260da30cc1'),(880,1,'0ed8be7b13e11f8f8a4da2741a592f7036b3c727czozMjoib2hVZ2J4V1NBdXpLMk4zU1ZKVE0xNm01NnBRTXFGcHUiOw==','2018-11-20 16:12:26','2018-11-20 16:12:26','471d084f-aeea-4d85-b893-e7a4fe0c09c2'),(881,1,'4d83b3874683b57cbfd8332d0a7575b9e1730e1cczozMjoiUGV3MUpTQlFPcE01R1RVY3BwSTg0fkRWSU1WRVF0ekUiOw==','2018-11-20 16:17:42','2018-11-20 16:17:42','32f63c1d-7d3d-45bf-af95-015c9b9caa09'),(883,1,'3e2410cfefa8f6cd5862fa246438ae2fd7924c36czozMjoicVBuNFVBOEFQTVlqdkI3MkR+fl9TcTRfbkM1MFhpa2kiOw==','2018-11-20 16:22:24','2018-11-20 16:22:24','244a48cf-9e9f-4824-996d-ac3a939998dd'),(884,1,'efe44b9db5e8f6c6b538036f61ebcfc54fe6def2czozMjoiV0txaFFobG5DSllUM0hrU1VZR1pUMF91OEFXamhfSFYiOw==','2018-11-20 16:32:51','2018-11-20 16:32:51','9afee58f-10a8-45b2-b3b4-9dea2cd124fc'),(886,1,'3f875232914c66a43d789109af86d12afa78c33aczozMjoieUo1c3VCcTcxbVFYZEdJNkNZUUF2QTI5TUZhfkRQU0UiOw==','2018-11-21 08:46:58','2018-11-21 08:46:58','fd3ead33-b3e5-440d-a9b7-91e6c363d8ea'),(887,1,'ff589281ddc6c57120eb77a6ab26210417b01ca0czozMjoiZUtQcHh1aXg5Z1RGZEVyek5WdHlIUlFEQUZ1N0F5UFQiOw==','2018-11-21 10:07:15','2018-11-21 10:07:15','3acb25ce-7259-422a-9f6c-64f068a6b4fc'),(888,1,'7964306e1d6583d1748b384e9c041743111ec268czozMjoiMHJsZTN4b0pRZVBEY0plc1h6b3IzR3J6cThhZ3U2VngiOw==','2018-11-21 10:24:25','2018-11-21 10:24:25','fca1e571-5bd1-465f-8b83-452e96f4b24f'),(889,1,'129d02a63e49e6f1673dd589664fa4ef07781bf3czozMjoidDQ5bkt+TzlNcUpjaG0xMmc4UXRTbzBHYU5sNWppMU0iOw==','2018-11-21 11:02:02','2018-11-21 11:02:02','26e3a2bc-3688-47cb-af85-77cdcbbcfef6'),(890,1,'d82fcc8d0a31b7e033f6ac529398d39d98d036bdczozMjoiaTJOamE2VThvWGt+SlhEZ1prUUxRc3Jrdn5qbjBhMnkiOw==','2018-11-21 11:11:51','2018-11-21 11:11:51','95df71bc-57e8-4dbf-b55f-d761b2503851'),(891,1,'52618bc22c969b716fcd0eb994bfd035e10d5cccczozMjoiSTloWnZISzhxYXRwM2ZYUXBNdXFyb0tXR1d4M3dDNHUiOw==','2018-11-21 11:18:22','2018-11-21 11:18:22','f35597bd-52a5-440a-a975-3fecf30fe0e9'),(892,1,'13499ed8e6d44f5fecbf6fd13f0234d2128765ffczozMjoiT1oxVU54S1MyYUZhajNEOWg4UEFLZEcyRUtNenR5b3QiOw==','2018-11-21 11:26:50','2018-11-21 11:26:50','5664bd5b-f68d-473c-9114-07ae394a06c0'),(893,1,'2a4602755d8380b9b2cd4f7dd793eb031b8cb5a5czozMjoiOUp0bFI3aklpSTI0YVdwMlVfck1CNFNkaThQYTZEdnciOw==','2018-11-21 11:35:05','2018-11-21 11:35:05','197aa7b9-c950-4c44-adc8-8e24e30c9e70'),(894,1,'2d8e707de7c6adbf110d3157d4131108e98a2e1fczozMjoiX3lvUDNwdldRZEdSNmJLa3VaR09XYVZOZXJWZ0E3WDUiOw==','2018-11-21 11:36:33','2018-11-21 11:36:33','42b73841-9bd1-48ef-bdfb-a411148b93ec'),(895,1,'1142a50ed366f58f76ce281b41d6e7224322a9aaczozMjoiYmlHR1p4SjB6ZFpYM19JTX5+RWZoVnJ+RjB0SDRUZ34iOw==','2018-11-21 11:43:30','2018-11-21 11:43:30','58d3b3dc-0930-4dd8-aa25-51324a1ca055'),(896,1,'956cbc81ba6282672b229018de04f5f767fd6021czozMjoieDVFSVc5bjFlaWhGUERhNDIxYXRTfl9wWnZqdVlrckEiOw==','2018-11-21 11:47:53','2018-11-21 11:47:53','34a1ae2a-3210-4a7d-af4a-79885cd6feed'),(898,1,'24a5ececa3ef9c911221599a6c6fd3456a963152czozMjoiZWdIX2kyTEtEWXo2N0F4UjVUb0UxQU5SWU41NVZRZzMiOw==','2018-11-21 11:54:00','2018-11-21 11:54:00','2333e017-1281-4860-84c8-66a518231a33'),(899,1,'06af497591545b88322da055dadb5c401f9bea20czozMjoiUlhRb1RMT1lOem1BbHpFZ1BTTUNDN0c0flJsQXViQzQiOw==','2018-11-21 12:01:30','2018-11-21 12:01:30','fa64797e-465c-45b5-a89f-53aabaddefa9'),(900,1,'f902efc9366693d15c6bc1856eddd9a4b1b70fa0czozMjoiaUpUWGM4aUFzYUVfOGFuSGNSSXdyZXlTNmFCTWMwREYiOw==','2018-11-21 12:03:47','2018-11-21 12:03:47','bec2ec20-3fed-403c-a538-a6cf33528b4f'),(901,1,'1de9fca510e8f9026e4316484cfba31adef80ebaczozMjoiYVd4VXNURTdoUDcxVVpmaldhX0s3alB5VzlxdXJCMWoiOw==','2018-11-21 12:16:23','2018-11-21 12:16:23','750fabb1-ff16-42a2-a10a-c90ea1eeffd2'),(902,1,'e179bbb51aa8f9c19a5a6a157fb628b056c1a62bczozMjoiQ3RTcGRIS2d3ZzRxZGF4eTgxZ1Z0UktyX192b3M0UVAiOw==','2018-11-21 13:34:29','2018-11-21 13:34:29','2592cdd9-48f0-4f09-b2b5-ee533362e1a3'),(903,1,'26594c09104ad8a89847cc917ad4fcf71bdb1c73czozMjoiMHJXMHFtNTI1MGVDRGN5aFBBb3hhWmJHbVphYVAxZkYiOw==','2018-11-21 13:36:14','2018-11-21 13:36:14','db373b7e-059e-4482-9b4d-2a9642686d7e'),(904,1,'70fe7985882a0815da66187c6d3dcdcf0d4567a8czozMjoiNEhhUVY4cWRQUVpHNGNSdGpiOTBETG45Y29STHF1VngiOw==','2018-11-21 13:37:02','2018-11-21 13:37:02','b4bb6a39-de8c-416f-948c-55e6fee4c745'),(905,1,'71d9279b87a794867a6a3c9813c4eba69914f91eczozMjoiaktFQlpHenVJVGI3dnA0Q25DWW5ZYmhHR0ZUT1ZoUm4iOw==','2018-11-21 13:39:58','2018-11-21 13:39:58','fdfdaad6-451a-4585-ba8a-3c75d8b86e77'),(906,1,'ecd2851d1f45ff94f8a2f8e52416e13fbd63fd78czozMjoiUFlQaU5IRmE2NWJYWG5EQUl5YmZxTUFmRzFsNzJraEwiOw==','2018-11-21 13:50:30','2018-11-21 13:50:30','33b12fd5-bd2c-4f3a-bc37-23c6b3ab5c3a'),(907,1,'f903cb9807fa493ddb56bdf411aa0aa39b3a4504czozMjoiUUR1b0xmSXpQaW8yVWdMeWljZlVvdDVPU1lyVTJyRnAiOw==','2018-11-21 13:52:37','2018-11-21 13:52:37','f06a0f93-ed1b-4890-b95f-e2d5d2876b29'),(908,1,'7bbcda5bc921ca981d33d6a0b624f791c28c04f9czozMjoiRUZIa3dxVThpOXI1TG5EZ2poT3N1TGkzZVZ4Yld2TW0iOw==','2018-11-21 13:54:30','2018-11-21 13:54:30','61f9d077-e4c3-41a8-88e7-d1ad2a71b7e6'),(909,1,'d5fd5c031f22d229ba77829b6aa00b28dbec92ceczozMjoiQmlhSmgxaGY5a1g5bGt+enVuREdCTXZIcXJQcnIydEQiOw==','2018-11-21 14:03:18','2018-11-21 14:03:18','e2e36538-069a-47c2-973d-480a4a0798b9'),(913,1,'8b0262cadb6937a1c4b55bf1abc8ec3d282f59b5czozMjoiaVZtYWlSUG1QZ0tQUl8xbld0NEN+WXBLTU9uaXhfcFYiOw==','2018-11-21 14:08:47','2018-11-21 14:08:47','a71262c0-11a4-4933-ac60-ff9e4ac44242'),(914,1,'ce74487804409818e9672d4862598649ba21e9f5czozMjoiZ0x+ZVFQVGlSTExKZFhxUmNydjhta3piS3VkWHA1VXkiOw==','2018-11-21 14:12:27','2018-11-21 14:12:27','911719ab-2e3e-4803-bc0f-47e21edd67e0'),(915,1,'5bcc30d676ae9807179ed55885f87750095ecf57czozMjoiZG50UG10dEZmNkNiZFk3TnBtWGRyeH5xRWVNfjRPRVUiOw==','2018-11-21 14:15:20','2018-11-21 14:15:20','ec6fa44e-25e0-4c76-9f55-fed19e6fa043'),(916,1,'e53b9cba085ab019d62775a216d479927f8a9ef8czozMjoiM3VGQkJJVkN4YjJYaEJnXzR5bGJIYTdqcEU0RlozZWsiOw==','2018-11-21 14:18:07','2018-11-21 14:18:07','19a53f5a-b2dc-49c8-af3a-49eda3d54d6e'),(917,1,'6a2d8a82228b5b68a567931f60818e22a3a1eabcczozMjoibWpUNng5aE1Wfk0xVFdxYm1ZSkpyZlZwdGtFaDhtbHciOw==','2018-11-21 14:22:36','2018-11-21 14:22:36','a4c1b930-64cb-4387-95cd-c0970d3dfb5e'),(918,1,'0bd142501947547c294fefd0fcc3dda3e70271a2czozMjoiZ3VZd19rUkVEN3MzUEpheVJMfjFXVmxoajdacm5ncFEiOw==','2018-11-21 14:24:34','2018-11-21 14:24:34','9192a6b7-c381-49ed-b885-1ae0c2fdb27f'),(919,1,'fa79f34c5c8119f639704e7125028caee8b3e6f3czozMjoiQTVSSmRtQlJqOUJJUUtuSTR0cW44ZVE0b0JtYWpVT3YiOw==','2018-11-21 14:25:51','2018-11-21 14:25:51','4c360882-7e96-4244-b764-f9c1309639e5'),(920,1,'b09a820d5b54479b0fcd6c2441c473bbcbab845bczozMjoieTBScVBxRER4T3VrdmlPTVg0Nko1R05jUGdiVkJWdlEiOw==','2018-11-21 14:27:05','2018-11-21 14:27:05','5fa3b8a3-3a6b-48af-9b37-4337aa88cf5d'),(921,1,'6c30b099153f5b3038a79bd54277ae29b7a478adczozMjoiSlp3bGlwZndzWFg4OXljRThuN1cwU09lZDBkcUtLaUwiOw==','2018-11-21 14:28:01','2018-11-21 14:28:01','da023d00-08b5-43be-839d-acac216fe76b'),(922,1,'4e24008be1c419d7f00f37bfb0097c835befaab8czozMjoib083V0RHajFxVjhzUGRteEFRTzVBdHZBYnQ1T0psYmEiOw==','2018-11-21 14:29:29','2018-11-21 14:29:29','8946298a-afd6-40c1-95b0-167bd7393a24'),(923,1,'22cb2d6d5a8cece8bd39835e7e2d4ca005c5c296czozMjoiWjZUQU9lS1ZOODdmbXN2WWtQOHBHOFE1T2Q0akc3WmEiOw==','2018-11-21 14:48:51','2018-11-21 14:48:51','41e002f1-0e40-412a-9942-a4acdade43cc'),(924,1,'a092fe745da13d99f964ce5a38a48b534d98afacczozMjoiM2o2b2JPVTNseGVlc21yaTFDTWhZcWZLOXd4dU5SbzYiOw==','2018-11-21 14:59:50','2018-11-21 14:59:50','ebe01dfb-668a-4b20-943e-0f225bcb5265'),(925,1,'896bed52a4b9ab22ab7ba94d5bab0a77a42e9a38czozMjoib35JSFE0R1ViR2owelN0RnF3UUFjc25YNG94UFVYeXoiOw==','2018-11-21 15:01:03','2018-11-21 15:01:03','9327ec37-4f49-47f6-8663-5a4750363e11'),(926,1,'9890206027aa9e2d68fd7d1ad8e514526021bb2aczozMjoiWFVlamhJU0ZzZ1IyNFJOM0lnYlVyRjl+NWtCVmVUMFYiOw==','2018-11-21 15:04:05','2018-11-21 15:04:05','2e1c2b69-2e3f-46b3-912c-0010a3decc83'),(927,1,'132e459060abb2430d6de7e02d9c7b5f5f0b6677czozMjoiNX5JaFc0Z1RNcmlLX2M3N2hFVjJLMURuVHlHZWNNUHMiOw==','2018-11-21 15:07:02','2018-11-21 15:07:02','f1c6b9ac-576f-4c96-9f42-d034435e628d'),(928,1,'b2e7784d687da6d003d7181fd628c02bd58da531czozMjoiZzRHVVFEVlR1MWZHOUNld2RDd3l4S21BRWt2Q0tmS0EiOw==','2018-11-21 15:09:52','2018-11-21 15:09:52','21e2c3ec-0772-4536-82ec-859ac5c65acf'),(929,1,'fc83d315ecdbe2518e264733fa41ddaf475fbce6czozMjoiYlViSTBsR0RnT2F5NnZic3M4MU1aflJFem9oSVVxQVMiOw==','2018-11-21 15:10:57','2018-11-21 15:10:57','fc00b447-4931-4a36-9597-daf50f28c96d'),(930,1,'05e95d7d0651898215ae8ee6ba0c8acd099df6f4czozMjoiMmVZQmlZdnJTNFBxOTBmbzhzc004d2NZV0luTlBtaU4iOw==','2018-11-21 15:13:21','2018-11-21 15:13:21','81ff76eb-69da-4bfa-9083-eae582333c54'),(931,1,'2458dee19c2912135a9a25afa1f354e803cc6268czozMjoib1BWZV9vNUV5dzdGNkdEVHN6Rl9hUm9tUHlQSEprRTQiOw==','2018-11-21 15:14:00','2018-11-21 15:14:00','f4737bb1-c066-4976-897e-95defc721cc4'),(932,1,'03eb42549d890e6bd3812df904ef990fc0432978czozMjoiNmRWMEJaQVVfcUdHbjA0WjNqUWF6T01uNk01UVNOekEiOw==','2018-11-21 15:15:14','2018-11-21 15:15:14','9bedfe57-1ae9-4357-a879-187da67c93e6'),(933,1,'ef5150f1821b9cf626a9569c147005a168e464e4czozMjoiYWI2WWJyYWFXaXdqX3BxSDBvWF9BaUVFWmZZZTV3SVAiOw==','2018-11-21 15:19:39','2018-11-21 15:19:39','8c0d56a9-2750-4d7a-ad55-dc0db3f23cf4'),(934,1,'16e77a174f77cbc654f2ba6ac4be7eeed7434407czozMjoicjhaOFhDOXdvUXZmSWwxZn5Dc09QekxIcXhNOVJ1WWwiOw==','2018-11-21 15:25:36','2018-11-21 15:25:36','3c600a88-876a-4a1f-9180-cad0ebf1b5e3'),(935,1,'380c60e84e9d63738608f198ff1bb1d7f47ec694czozMjoiSmF1OGlLaTRIZ2ZzT2JwM2NjYWhQczRObVJrMmQzbUEiOw==','2018-11-21 15:26:06','2018-11-21 15:26:06','45e78f17-1104-4fc3-9b51-7da71671e558'),(936,1,'4fd656bfdb681e9b353fa00687c0c67f406c6edeczozMjoiUldqTDl2WVFUWXBfaEtYN1l2dVdrenJ2NkZQMDQ4XzMiOw==','2018-11-21 15:26:51','2018-11-21 15:26:51','9ecc2245-ffcb-49a5-8305-f38aedaef30c'),(937,1,'7c168f36e9cf5de435f9831e9e677e7be5e4f83eczozMjoiQkZGZm5lZzRkUjRPQVFvcnRXQ0lLZGtUMVRMTVdHVWEiOw==','2018-11-21 15:29:26','2018-11-21 15:29:26','6c6b5d65-eacc-41f3-b9f7-e9bb79b10b2d'),(938,1,'faee30047b700ef9b11defcc6095f5e770bf1770czozMjoibEtqZ0dGN0w4djh2NGpMdX44WFF2bWtrZ2tBazM1TXIiOw==','2018-11-21 15:31:54','2018-11-21 15:31:54','57ed9704-c4ee-4b4e-850d-3327e05033ba'),(939,1,'5a02937d1d97816f9349f4d35f3dcbba88a8b501czozMjoiMWFUSnBtUzZkWEJDSE5ZeW9Pa05sdmJYflIwaDUzeWQiOw==','2018-11-21 15:32:39','2018-11-21 15:32:39','68e24082-27bf-4cab-a42d-5497efc9fc09'),(940,1,'c84c99684767413bdf8de223e072cb7ca4f29fd4czozMjoic09rZmVYM211TEJjUFN4WUlOTHF0YXRhWDFta0ZTNXkiOw==','2018-11-21 15:42:28','2018-11-21 15:42:28','91030f0c-ed76-4261-8d6f-cc876838964a'),(941,1,'eb889064d8e42d1814e6cd3b4e25ec6708ca40a1czozMjoiRXpwU1g4NzJHanp6bV9ZTUdFYkhRSXlNOG5ndTZRWU0iOw==','2018-11-21 16:18:59','2018-11-21 16:18:59','daea352c-5593-4f1a-b398-d6b874cb8aba'),(942,1,'da2aedbd85e440b289b7cdae970f3b901747ede3czozMjoicndfWGFIcmZNQXZxdHVwV0xOQUU3ZU85WUp+eWpjYlEiOw==','2018-11-21 16:21:20','2018-11-21 16:21:20','051a7783-dc10-471e-a8c8-4f1cb51db928'),(943,1,'00b3c7e436dd8e07eed96af3de3342ce1f675829czozMjoiWXdWdGJfazdWT3lJcXVTdmIzWVRDcEpDaldKME9pZDciOw==','2018-11-21 16:34:22','2018-11-21 17:14:42','85b448d7-5abc-42fa-ba2b-9fb45e8d77cf'),(944,1,'85c257d9233cf4bbf518e34939a6ffef684ddd12czozMjoickJGa21fcEFMbldkNk5Ddnc5ZndGaXA1akhrbHRpUTQiOw==','2018-11-21 17:16:10','2018-11-21 17:16:10','d51a7f22-e50c-43f5-810b-b1a052df0af1'),(945,1,'621dda944899da262325b88ff55a89f6c9e99a79czozMjoiMmc1fkk3Yn41VDIwZFZVMHFJbFA0ODdxUW81aWtMNWwiOw==','2018-11-21 17:26:33','2018-11-21 17:26:33','a72f56fe-5bcc-443e-9fae-8bd2dd69b7d9'),(946,1,'f5d5868d17355ef748ae86b5cddc2453417772afczozMjoiXzNhaE9JMV95ZTcwbl83b1dEeWpsYW9kc0RoalpoZzIiOw==','2018-11-21 17:27:45','2018-11-21 17:27:45','1a639d17-1c04-4343-9228-31c03e320eb3'),(947,1,'b308d31805f6b215e9b3d41ee36d4afd8cca2244czozMjoiUmlwalNnQjJEWmZfdUdEU2pxMjZENGpFbGlib3U3NXAiOw==','2018-11-21 17:29:06','2018-11-21 17:29:06','a671fc1c-68f2-4bce-8ef9-cb9331180260'),(948,1,'ef54fc86fafe40e0b18953a9117713d8a4a05ae4czozMjoiS1dfZFRrT0ZMMWY1cXh3M0pFcVN1YkVnd0VJX3VSTWQiOw==','2018-11-21 17:32:07','2018-11-21 17:32:07','5467943f-3268-407a-8abd-11fbc228693b'),(950,1,'3bd951f77be9e7c7b08423cdba09b28dfea74585czozMjoidm55VkRGRDdLTTJJTEFFakZoNTF0c3hwNEQ0OXd5al8iOw==','2018-11-22 09:12:59','2018-11-22 09:12:59','11f1b69b-1620-44ac-b9ee-7a4ef032439d'),(951,1,'dbf157ea2d266824ba593d5502f60ab8fba78183czozMjoiYkpQU2UwNEN1cjBvSGd2TU9KUDlRZUZ6cFhCbGFwOEEiOw==','2018-11-22 09:13:03','2018-11-22 09:13:03','db3d33b1-626b-42f3-9ae5-ca6891237d66'),(953,1,'e2808d43313167b3a59267681d4443c2e64b35e9czozMjoiTGc0SktzdlRYRlA1cnoyZDR0Y0p3OUNQSmRBdTZKSTIiOw==','2018-11-22 11:23:01','2018-11-22 11:23:01','ed825052-f010-4474-92c2-76379edc3796'),(955,1,'6408502d2c14a86066a87a5d4f89cb1600aa5b50czozMjoicn4zeHlFcnk4dGVwX2R3XzhkX214cmc0bTNZbjZNb1EiOw==','2018-11-22 13:57:17','2018-11-22 13:57:17','8bf64261-48c3-47f4-af7c-3a7bef09ca36'),(956,1,'bd8ed76886e5c1c0e98ae79cc583a47bd5a59549czozMjoiemdXZll5MFhaSnJCcnA2fk9ZU0t5SXhNZnd+Nmd2b1oiOw==','2018-11-22 14:07:40','2018-11-22 14:07:40','18c304f0-ecaf-44a8-a39b-ea396a7d45dd'),(958,1,'939ede5f9345ee4d3debd094f6260673f950df01czozMjoiSGZQZE1URGZ5OG1RTmtzejJsbHdsVDlXS3pMalZfeEgiOw==','2018-11-22 14:09:29','2018-11-22 14:09:29','de71c849-0795-455e-8aff-fa2e902dd866'),(959,1,'57c2cb116d0a7fd9f4871784ce4b60cd29d354b0czozMjoiRThOVG9JSll+b00wc21Ob2FkWkxMcUMxQ0h3WnZyejgiOw==','2018-11-22 14:14:01','2018-11-22 14:14:01','ae346689-de7c-414e-a32a-afdd3a453048'),(960,1,'f7d006ee3523b806b5934166193892d83f2f97cbczozMjoiVVBVNzQyQ2ZuWHpEcFNGRUs4NmJXQ1g1U0FKOWVkWU8iOw==','2018-11-22 14:16:34','2018-11-22 14:16:34','f72971b1-99c0-4346-b892-238c8ce272a6'),(961,1,'ff5b0e9ec8b389bd62660ac6adb46bdd63f4c630czozMjoiUUxLOVdKMjRVbjhSbmZyZDhuX0hnflR1bGVmTnRLdl8iOw==','2018-11-22 14:20:07','2018-11-22 14:20:07','22ae4618-a451-454b-a40e-d75312b240a1'),(962,1,'b5759bf8a0923f8d9e0818b5159218dd2a6bf08aczozMjoiWEZQX2ZHSkd4MGNjTFc0VE5UVVZkb3EzOGFDNHRFZn4iOw==','2018-11-22 14:24:53','2018-11-22 14:24:53','186c4f18-5b64-4053-83b7-f285479043da'),(963,1,'2cde32e44931fe088adef2cf6c6dda3a464e9aebczozMjoiNEhHQzVTbWMxNXgzcXhWZDJRX2JieWd1Qnl5VzJRTHkiOw==','2018-11-22 14:25:58','2018-11-22 14:25:58','6fd7fb35-bf1d-4eeb-8764-723be267816e'),(964,1,'3996c8c62b7fb1ad8e621b8cb6fe03c0be8c671fczozMjoic1dvfmlYaThUfmtXZ1o1UXJEZ19UT0pNTVY3dWF3WGYiOw==','2018-11-22 14:26:22','2018-11-22 14:26:22','415b6988-ace7-489b-9f29-c72425d55513'),(965,1,'305a5277dffe556f72534b87c91018bbe556e380czozMjoibjlQc1dYRVU5UmtXTXFtTkJYcWl3cE1VazU1MXJoNjgiOw==','2018-11-22 14:27:20','2018-11-22 14:27:20','02176dc8-8039-43e1-b3c3-5c3cf2bd3651'),(966,1,'e1e86d28b19e4e780c6f70839bbca03b45f2b846czozMjoiV1VWek1mV0ZDZDk4dG5wZkhORVlDVlByMXRjUDRSbE0iOw==','2018-11-22 14:27:46','2018-11-22 14:27:46','803b388e-e75e-45cd-907c-4fcc8319b467'),(967,1,'566092cc642491efc53336a7e35d28dacbb3981eczozMjoielZqVW1PWU1BUWtJcmtfcFhfZWRVd2FqWjJzZGNNZGUiOw==','2018-11-22 14:29:03','2018-11-22 14:29:03','faca7329-d011-47ff-a773-62ed6ecb1e7a'),(968,1,'983da80b96f87d052fc91956df3102b8fa8b828fczozMjoiUVUyM1FZelh5VkxoR2R1OFVjdDN3Nm9tMzlRYWRWaXUiOw==','2018-11-22 14:29:33','2018-11-22 14:29:33','62a26293-a9d6-4319-8097-81973840098e'),(969,1,'0dabe7fbb4e21db5503e26f5bf8dff71846a484cczozMjoiZjB2TmpnbG9mVVdCZHZyQTNCY3h0T25qSExYUml2TXoiOw==','2018-11-22 14:31:28','2018-11-22 14:31:28','ae9cf412-13ca-4e63-9b4a-c8458bf2452c'),(970,1,'967b7779b23ebf65759c9321ac2c155d209087f3czozMjoiWF85YWpVRmQ3d0lPfnY3SXdzR0x4cnZFU0JDTGR1U28iOw==','2018-11-22 14:33:07','2018-11-22 14:33:07','9381057b-1ef1-4ae0-9fa5-0a0c57d96f02'),(971,1,'0e5a1a9d670336b3bd3260df45912c9d6d97d520czozMjoiZVJUR0l+c3V4NEp4fmlUVmZXTnZyZE1Fd0RZbkFQTGEiOw==','2018-11-22 14:33:40','2018-11-22 14:33:40','345741f0-3a7d-423e-bf93-0db1960f48ee'),(972,1,'d15ff88ab32a488f18d2a7f7e42ab3b2fc9d696dczozMjoiS082RHkyekVWUWpTUU04MmpOUHFWa0dVZkFyZm9fREIiOw==','2018-11-22 14:39:28','2018-11-22 14:39:28','063c2ff7-f261-4b27-9254-a5e12bb6f712'),(973,1,'e540fbcc03c90f223f21e517b17c5de8ae003e3dczozMjoiU3FmZmUyeXg5WXJ2V2R3VFFhU0ZkMWxTRFJGNzNHTDUiOw==','2018-11-22 14:47:35','2018-11-22 14:47:35','785ec578-f445-4ab4-be55-e77c198690e6'),(974,1,'8c8a9601a8021f552f986f6fd4d28b957168896aczozMjoidEppQ2lXdGNRSlh3cVd3dlZpVEZ0MXhwdEZyTE5oVmoiOw==','2018-11-22 14:49:46','2018-11-22 14:49:46','18a9934a-38d1-4a0e-8592-3e0086c79f9b'),(975,1,'248d846aed5f673c3bd27d4e1727743de374613aczozMjoid1JENjV0dGE4Zm1HcUt6NjZSdG93WGFDOWp0eGFaak4iOw==','2018-11-22 14:54:10','2018-11-22 14:54:10','4cdc5e06-a66d-4a08-864c-8d7f6f0c395a'),(976,1,'022b69c54b38669ed4a981999a5abc04aefd25e9czozMjoiT3BtaVIyT2xXV003dTFydmNFQmRaa35WT3A3eURrenUiOw==','2018-11-22 14:55:03','2018-11-22 14:55:03','7355227e-ecd5-42aa-b4bf-a094360efa19'),(977,1,'1db1eb8c12a71404cbe65f32a700c4c0ae0da513czozMjoiWTJWU1BvNUt3RF8wRmhNWDkzcV9GRjhpVjlHZWdRWjgiOw==','2018-11-22 14:55:33','2018-11-22 14:55:33','44315f75-c9ee-4cae-b15c-7c983fb19636'),(978,1,'4723a403ec5f780cc7c7481f722fd5a4a962c684czozMjoiejFtTTBlckhXYXNYczhzWEVqRHJSdUZRTVVzRkptUnQiOw==','2018-11-22 14:55:54','2018-11-22 14:55:54','30c2c75c-ece8-4612-9bc3-5b7b4bca0449'),(979,1,'13c435dde86ecc4ad10739691a9c89cefbce18bfczozMjoiSEZRZkpqa3g3eVZCNG42UjRYX0VvU2hlX0xuUUFCRjUiOw==','2018-11-22 14:56:42','2018-11-22 14:56:42','6284b66c-6728-4380-8cd4-dee2495cff51'),(980,1,'929d8431cbbad40faca1416f4fa5654e369a6507czozMjoiM3JveG1hWDVMV1R5a1BxVThwdG5SUzIzZEt0V3lmNlEiOw==','2018-11-22 14:56:42','2018-11-22 14:56:42','a01d188e-57b8-42b9-83e7-ea166c502092'),(981,1,'b59b3610e86f968e61ccfe13c384238df03262efczozMjoiUWl5cUtaRXl4MUpFRGVrTVd4QWFZUjVyY21sVDlUYUIiOw==','2018-11-22 14:58:16','2018-11-22 14:58:16','a095f534-6918-4bf4-b7bb-2042d2e8f7df'),(982,1,'da0d9d54a4ad1b9aadd8247f80ceecee2be067adczozMjoiMERYdnNqT0REZWV+cDAzT2FnfkxjZlJEdGI2NFpxSDkiOw==','2018-11-22 14:59:32','2018-11-22 14:59:32','d03f1fca-1dbc-41ef-b90d-57e2bdec6733'),(983,1,'0127d1a53a0c8d7c0c938af5c0ee9ed45e03addbczozMjoiU21LWldwVWs5Z1JaX3JtendUblY5QTBFQ2d0ckRSSEIiOw==','2018-11-22 15:00:25','2018-11-22 15:00:25','74507fa7-6b49-481a-8e9c-c804737bb8aa'),(984,1,'c3d22a9940af1639bfea6b7270e554b7fcc4f016czozMjoiMkUzOWJpaGNPUTRmaUtRNkVfM3JRNGNTM3BNQmZIa0giOw==','2018-11-22 15:03:01','2018-11-22 15:03:01','4cf0b93d-08e7-4796-80e2-2f6f7a50f614'),(985,1,'a149aaf0bb6f6c156ce7788b0be142a79ffc3227czozMjoiZzRkdWJ+ejV+Q2FaNk9LVWJZa0NXenJXcjB6ekJ1eHQiOw==','2018-11-22 15:04:04','2018-11-22 15:04:04','c635aad8-009d-42ec-9e68-9788f0fb66a8'),(986,1,'1c4e2889ee88fc62c2c08b37fb7904f8e2b4c42cczozMjoicWRBaHJISkFUdW12WTFXVDZkUFRXS0QxX0FkUVJYT2MiOw==','2018-11-22 15:06:50','2018-11-22 15:06:50','26b9ac25-df24-48a7-a843-345b65a0057d'),(987,1,'cb3d9aabfdee9da9fdc6b17d75a7182ed7a74358czozMjoiSVpKQ3NRTEZhTlYyN35EZERvWlphd1lpcnNZZ1dSVFkiOw==','2018-11-22 15:08:45','2018-11-22 15:08:45','44f6460f-9d5a-4c5a-84b5-05159249bb59'),(988,1,'227f5084952fb95cca60eba25995f8f5ed874b84czozMjoiX1pJZUVBdWRva3YzUWZsQlFoYThQUnZBNmd0OG5QWUYiOw==','2018-11-22 15:14:08','2018-11-22 15:14:08','f097dc51-6c39-47dd-8518-e15ffb0d2b64'),(989,1,'de6ac8e02971e74926db4c7f3194d14550cd0454czozMjoiOFBkR21SZVRMdlp6aFhXd0NmOVpfZ1JPbHZoZkhYTmsiOw==','2018-11-22 15:20:54','2018-11-22 15:20:54','b1058350-8fc1-4ddd-a021-c4a4b0a72150'),(990,1,'63728408ffe6aa9f476f926940ffe15a131a9081czozMjoiNWlrfkxRcG1iMjZ0ZkpDbTNxcDNkbVRPSnVwNUtkUVgiOw==','2018-11-22 15:21:42','2018-11-22 15:21:42','87a0df9e-ee53-4422-b0bf-03857924c92f'),(991,1,'8c0c652e57ce9fc4703d382c6501aaa0285c20c2czozMjoiT1AxdmRqaWZMd2wyNF84SlRCYUxpQlhxdlZrWDI4SGYiOw==','2018-11-22 15:25:06','2018-11-22 15:25:06','c9b2195e-a64d-486b-93c4-b0ec0fbc580a'),(992,1,'c246c0bdc0838b5ab49c7ebf204e9bf964e344d1czozMjoiMGcxSzFQMkNfYnM5ZXoyRXZJTmJfSlphUnlKQmszMUUiOw==','2018-11-22 15:27:03','2018-11-22 15:27:03','972ad559-b5bf-46de-8327-41ac63cdcdbb'),(993,1,'6915b50b0b996203b2f1aa41106958ad94faa07aczozMjoiWmNkUUhzdUZETn43ZERRfmFXd3lyfjNUNzJmdWdUSnciOw==','2018-11-22 15:31:08','2018-11-22 15:31:08','d260aa97-247c-4954-9e73-850f0094af8c'),(994,1,'81e442a3099aa398a074589ec5dff1cb8f89f6f5czozMjoiazUyX1ZwM0dMV25FT1JjdExGM1BWMnkwV0lLSXA1WDEiOw==','2018-11-22 15:31:56','2018-11-22 15:31:56','45087cfd-63e4-40e3-b5df-e0a967956e62'),(995,1,'9114a00820bed14003838e7ced82b0a5b8c51bedczozMjoieTI1NFNjMGQ4el9JY3dDbl8xVzU5Z2FkSldGNUc4aTMiOw==','2018-11-22 15:59:46','2018-11-22 15:59:46','db5bc0d5-183f-4301-9157-d8c6ec486402'),(996,1,'b912bd395f6ff094c3a24244f2d204dbf833658dczozMjoiQlVPbXFkVGFnQ1NXalczRGllbmNXRXBmSzF1TWRGR0QiOw==','2018-11-22 16:01:02','2018-11-22 16:01:02','329773cb-cdad-4091-83fd-2d44cc157bb3'),(997,1,'b55a27ba39ed98e863de3f39930400b48cc60bcfczozMjoiZnJ6MzduM3BqdVNKN2RqSjNCcVJjVTRTX1MxV2xudWoiOw==','2018-11-22 16:02:11','2018-11-22 16:02:11','49f4b083-ade5-473f-9906-20d47e1c1b58'),(998,1,'8ec47596ec7eea83aef7bd4ca4e41ebb73daae4dczozMjoiRVhPM2NoZEVTNHVOUHNufk83U0pTU1VHQVgyaG5HdTEiOw==','2018-11-22 16:03:13','2018-11-22 16:03:13','e5dac582-ef5b-48b4-b49f-ca2abfca5431'),(999,1,'5eae03906e0a2d20165271c44dba2abba647d430czozMjoiZXh6U3gzcmhIZXBNOHlUdHFyQlRob3RWeVVMSE80ZlEiOw==','2018-11-22 16:06:45','2018-11-22 16:06:45','382cbb47-b35d-4bb0-a19c-a3a66351c682'),(1000,1,'423f66ad53a5363e00b24c04c8e3f7221b9eafddczozMjoieDk3QjdCalJlSGk5bWM5bUJYYX5YNk13cGFMdWlFaUwiOw==','2018-11-22 16:11:12','2018-11-22 16:11:12','ece348aa-ba7d-43a7-88cc-bd63a0d8129a'),(1001,1,'266a597c034c9623018fd3788662956f736ed377czozMjoiUlRuM3pxYk9aamxHeWhuTW50cGkzMnlkbG5iTFg4fnciOw==','2018-11-22 16:39:33','2018-11-22 16:39:33','ce0ceb63-006a-4ca3-b4a6-679f4b8a6ec6'),(1002,1,'f2c1f98cd6b812152e775aa2fe0faa5914f7fdb3czozMjoiRnBxOF80clNzNUtreUFGOTkzb0xJcmUyeGdGZkloRE4iOw==','2018-11-23 09:17:06','2018-11-23 09:17:06','716f23d4-d67e-4f16-b52b-b093bb9c651b'),(1004,1,'64734c131b00b9990f1815a2da3e575207177cc1czozMjoieDU2Z01LV1l3Rnd1RXRkWmZXcXd0dmZYWld4eHNqfnUiOw==','2018-11-23 10:19:23','2018-11-23 10:19:23','f1cb48c9-14f9-4163-b179-4e59a823de56'),(1005,1,'311987f9cc086d47e6f836dc3195fc849e586689czozMjoiUlQ2T1M2S3RsazF2WnladENzcU1waURfZzl+VXRIMzMiOw==','2018-11-23 10:21:27','2018-11-23 10:21:27','655be5fd-491f-4fc4-b6eb-6e8b2be14ee6'),(1006,1,'97fedfa34d1fe8996070e2dee8404b05550c7874czozMjoiblZQMHZ3bURWYzBEQ1VjX2ZOeDU5ZjRiVTFwQkNJNVQiOw==','2018-11-23 10:25:01','2018-11-23 10:25:01','3cdf91fd-5276-48aa-87b3-f2222ad5e546'),(1010,1,'53bb7cdb52892c2219b6b665d5a4df25905164d3czozMjoiUENzQkUyaExibl9NdDAwT2w4cEZ0RUxtZ1FjQVlRT0EiOw==','2018-11-23 11:10:05','2018-11-23 11:10:05','41ecd065-fcf0-4232-a913-e928d0a0ec17'),(1011,1,'e9601ad5f91408320f94d3aaf81a28b6da33b60cczozMjoidElkZkZ+SFV4YWF2N0xqQTRpfjBYcm1hdzE2aVpuOWsiOw==','2018-11-23 11:33:53','2018-11-23 11:33:53','bb0ad77d-edaa-4c84-9c5b-b172ee2971dd'),(1013,1,'faf54a3f657eb020e622de9962decde526ddd42dczozMjoiZjJfVGg0OFhPYVdDbFJCSnJ6dUU2MDhmbVZtZDk3X1ciOw==','2018-11-23 14:14:23','2018-11-23 14:14:23','fba37935-5874-4ffb-945a-d98843aa3e2a'),(1014,1,'6ee2e430fe90e1f55433216c22be085045b212caczozMjoiS0dSOTBLaXNSSGRaVUZtZ1lEcXpBTEdNc2RtU3JJbmUiOw==','2018-11-23 14:30:44','2018-11-23 14:30:44','4fbdd1d9-ee21-4085-acc5-36d414cc52f1'),(1015,1,'693a0c44a29cf61e8a5e871aa7fea286b0141a79czozMjoiczYzanVvQ3dqM3ZmYUh1WDV3bW5iWHNSN1VQVGZLU2kiOw==','2018-11-23 14:36:08','2018-11-23 14:36:08','ebb00136-e901-4114-b630-c5e415346bd4'),(1016,1,'354633e99feffed1a7544096dcb5028cea7ff215czozMjoiS2RKTlVKcmpzYmtSWE5nZ3djeWh+cHNoM0doM0lWSkkiOw==','2018-11-23 14:39:55','2018-11-23 14:39:55','eb155128-ad4b-432e-8ed4-baa1c72db1a2'),(1017,1,'f75b8dc5dd03c1f0c5a6082b189de18a61ca52b0czozMjoiT3hxNU12R1pmQkJ+MHlFTDhoaWx5azRxSXNqc3JNaXAiOw==','2018-11-23 14:51:40','2018-11-23 14:51:40','36ebbe9d-6e2b-469b-8265-aa98f9bcc833'),(1018,1,'b1c693ea8f0f63ff3631dd7c7b68a46afb865d7aczozMjoiWTN4RnRWeVJJTGdoVmFuOGhueDFzcUM0anlNSzVGa2wiOw==','2018-11-23 15:30:33','2018-11-23 15:30:33','8ec3cefa-de35-4a72-9c1a-b9ca361a0653'),(1022,1,'75e8fee3816830e6fe98143d1d1c11ae0bcc19cfczozMjoiSVQwcE5GdmVHcEJCdmVaYmhHWlpKSjZsYjVHeDVNSXIiOw==','2018-11-23 15:47:35','2018-11-23 15:47:35','0bee333a-09dd-4f24-a97d-08db4af4b026'),(1024,1,'b3ffffbad967eee9040af7e0ab5916657b321204czozMjoiaGwwWFNLR1Z+QUVLeDhiYTRJT3dlbHpVb0FvcDhHQ0IiOw==','2018-11-23 15:50:06','2018-11-23 15:50:06','e1adb113-23c2-45ac-b348-cdb7cd671d2c'),(1028,1,'4b1843556946683ecdd7f9ebe070af9a0012e6e7czozMjoic1ZGRXVYZjZaV1AwZXZubEpMNzFlMEh+S0VNTnJ6OHkiOw==','2018-11-23 15:58:58','2018-11-23 15:58:58','ba04753b-443f-4fa1-b792-333ad5a3ca86'),(1031,1,'367dd8b4e55ab7110f218a9c7f0bf3f759a6dc1eczozMjoiQ2xHazR0NUp0aG1vekU5T0JvZ2Y2TzVZcUVHelk1QkIiOw==','2018-11-26 08:46:50','2018-11-26 08:46:50','a9842df1-a07e-490a-8169-382df1d67a89'),(1032,143,'e8e84cdcd079627568c365837b0faff7f676765fczozMjoiVWRYV1V2OXBBN3M4Nk9tWTNmSHhOaUVxS2dvdHN3QnUiOw==','2018-11-26 10:44:16','2018-11-26 10:44:16','c23f9803-7d4a-4b9d-ad74-5a43971b97c9'),(1033,1,'8647322fdefb40f4936c8a68ae05cb1826e740a4czozMjoiRmF6MFJlMjh6fmwzNG44b1dkQ05hazJxWG5wTVpQZ1UiOw==','2018-11-26 10:44:23','2018-11-26 10:44:23','15ff9e23-32f8-4f5b-a83b-504ffe29cb8f'),(1034,1,'83ec8317c12e7f2b1208902470d2db56dee9a332czozMjoicWg5QllWc3hXTTB1RlVIOUs2ZWtSQ2s5aUVoMkM0bWIiOw==','2018-11-26 12:03:53','2018-11-26 12:03:53','3e745b7a-6544-4b62-8529-1fe7e7a5c2b8'),(1035,1,'cc4978d1ca7533c6f574b0859b728f7468b3e002czozMjoiajJpX3UwaTBqQmtWUjcxQThUazdGSnk5dmRuUVpCV1IiOw==','2018-11-26 15:15:02','2018-11-26 15:15:02','a045d5c2-5eae-48fd-b188-de80e3088e06'),(1036,143,'0eac1dc5f25160ae4c4df6562d3b5d654fc414d0czozMjoiYkZuVmc0WkVZeG81NzJ2Q2pmaExGYnhjRlZkTFNQRU4iOw==','2018-11-26 15:15:04','2018-11-26 15:15:04','35f14660-a2dd-4dfb-8f88-a9c2e2eef57f'),(1037,143,'d1200ed27fc1b91019df627d14ef61ed699cd765czozMjoiOE83dFFTUkhGRk9nRjR6R2IyMjB6eHJBWmZFaXhSQ0UiOw==','2018-11-26 17:12:18','2018-11-26 17:12:18','5cdfa5b7-e956-4068-a027-fa7a59da2ed9'),(1039,1,'b67afcca08f64b668c798e5760beea812437935fczozMjoiczlibW9hR2Z0ZkQyOWhnOEg0dW1FdUFIT2FwTXIwakkiOw==','2018-11-27 09:13:36','2018-11-27 09:13:36','1d1a7ed5-36f9-46fe-8721-58795449d1e7'),(1040,1,'bf3e9ae1cac8a578f50201ef32745018bce59723czozMjoiYk9lMmF6WDlMfjN6Y3JtTFdwWF9KbHI4QlZyazIySk8iOw==','2018-11-27 09:13:37','2018-11-27 09:13:37','ffef57e5-29cf-4f65-9d62-d6e4d1b861c0'),(1041,1,'0d71495e8c143efc518b209b789e91383615035fczozMjoiYXIyTFBsWVlqRFpIWTQ0UWhiTVFWaG9EfnkzTVhFWk0iOw==','2018-11-27 09:15:41','2018-11-27 09:15:41','5d4da071-1028-4e98-9753-42ce9eb12d01'),(1042,1,'c9a072c4fd23c625034356807ed5aa03c53a3770czozMjoiVkM4d0E1NlBaNlJLRlN1WnNzOWhLaTJQdFVzbGFYb1MiOw==','2018-11-27 09:16:52','2018-11-27 09:16:52','df20291e-602c-496a-9021-f4cdac8f321d'),(1043,1,'364d97d9a188b0fc9149c39c0ff684d400e1b5c2czozMjoiSEFQZzdvUjlqQm1xcn5tc0tuNU04aEREWU5oUld0RDIiOw==','2018-11-27 09:21:51','2018-11-27 09:21:51','5b495a5b-f0c6-496f-9ec6-6a9d560d860f'),(1044,1,'9bb6a730dd745d2bc94f95c56d0e6c137e3a41a0czozMjoidTAwY3Nqa3Nhb1JPRDJPMTlKQVh6UmlIdFQwc1JyR1QiOw==','2018-11-27 09:23:15','2018-11-27 09:23:15','aba341c4-3ed3-487d-a05f-4689606f2923'),(1045,1,'372816f3b9b07a67b5b4d0bdc12ba355d9f8a505czozMjoiYUF6ZV9sakhHb2hRQU9RY0JxVjlEbmpqVXJaUUVKRGYiOw==','2018-11-27 09:26:01','2018-11-27 09:26:01','b6dc2d07-2fbf-4817-a90c-1719edc4580e'),(1046,1,'4e2d9c5b8618b4c8184a52784d8facf6b59ed3d2czozMjoiUzBoZHlLQlRtcWlhRnphck9TbG9LOHE5THNnaGRxYWIiOw==','2018-11-27 09:28:30','2018-11-27 09:28:30','0f82aaa5-a28e-4d2e-a3fa-450cc4c4d7a4'),(1047,1,'5fa3905db06d67d5314d2872ee1555615af3eab5czozMjoiUXZENFNCMzFIWnFnV0ROQ005NEEyYUNrR3ZwTGEzQWIiOw==','2018-11-27 09:30:54','2018-11-27 09:30:54','fffdcb0f-bb77-4078-b7ba-57974aed71f5'),(1048,1,'954ef8e4df178ab231ce36969ec7ba4de8431ec0czozMjoiZFVpZE0zZ2xmenRGTVNJamt+STR4RU9UcE56a3lkRk0iOw==','2018-11-27 09:39:03','2018-11-27 09:39:03','2d57c712-5aca-4c39-8bbe-b50542335a1a'),(1049,1,'c0c216f346501ba179051e6245b5ce7deeaf0023czozMjoiRXdMTEJYX0M3M2FzNG5lY2hvRDJwQ0xiVVB3Z1lQSDMiOw==','2018-11-27 09:40:35','2018-11-27 09:40:35','df9b276f-92e2-4a82-ad09-b311e529744c'),(1050,143,'05ad51c7b456c8286dd785610a548bf636eab39eczozMjoiNnN1cDlNOGxYSTFDUFpvN24xcVVVTTRFS1lCYUk2OFAiOw==','2018-11-27 09:43:38','2018-11-27 09:43:38','42454a6a-328a-44fc-9502-92959cdc9f87'),(1051,1,'a202478c9b50f7911e4c97fc798a903a309c1209czozMjoidFdoZzR1Tm5pSksyVWR0Um4yVHUxakhmaWRhQ0NHUkkiOw==','2018-11-27 09:55:21','2018-11-27 09:55:21','72efe4c1-f2a7-4946-9d52-7049b07d60ed'),(1052,1,'ef6abcf96d1b5d8c787de0c04f3b10cb1db404d4czozMjoiVzJPM3ZJNW9CazFBd0xSN3ZzVjlqc0J+ZGxVajNmQ1ciOw==','2018-11-27 09:56:31','2018-11-27 09:56:31','b0b264b0-b886-4ea9-8dc5-7b405d488870'),(1053,1,'71f48c9b253d44b89558b3fbd103948d1fc7bfb4czozMjoiVjFSZHJwUk41NVJWMXp2REpBVEl0Y3Z5dl84bWM2YkkiOw==','2018-11-27 10:04:51','2018-11-27 10:04:51','350971b1-4036-4c82-983e-89b3bb186007'),(1054,1,'2073774a9a4b8e3a639543bf2ab648e0faf2c6d1czozMjoiNmJlWVdqZU1wMW4yblExY1I0TTRzTktncVFEbFNlckgiOw==','2018-11-27 10:24:40','2018-11-27 10:26:38','add1f949-73a5-4ea5-9a3e-0dfd50d11aeb'),(1056,143,'b3d4d723d80f526d2358355d45508c2db943b77eczozMjoiX2F5a3BuZ0hFRU5Sb1p0aUJ6Nzl4SlIxMEV4M0lJazMiOw==','2018-11-27 10:55:51','2018-11-27 10:55:51','130bbfa2-7ce8-44f7-bb1d-53db4c00c3db'),(1057,143,'31f2f4b902f09b9a16a6cd26cd33ced4235a7d3fczozMjoiSDJmOHBtVHRUY2F1dUJUSng3NWRDMFNTaEdnfk5hMEMiOw==','2018-12-06 17:26:42','2018-12-06 17:26:42','ba73b323-4cd2-4a92-9243-06936840c437'),(1058,1423,'c10fc275d7c7a84e0cf63680c3b13a5119127718czozMjoidm52eFcyR2x4NkdieUoxUE1DY1lzNE9WeXFOQUNzazkiOw==','2018-12-07 08:28:32','2018-12-07 08:28:32','7d0ec89e-8fb6-4afb-98b1-ad04fa0e8c52'),(1064,1423,'a4280e5157df5820bc043365f2502fc4c261e743czozMjoiaDZYVXd0R0lDQXJVTW5ZdERFc3lpMTlfdkR4ejhYVGsiOw==','2018-12-07 12:01:09','2018-12-07 12:01:09','678a8551-a504-41e9-8dc1-374556ba83fc'),(1068,143,'8161d6e69eaf7c13b5116c1bf4ae705fa492da35czozMjoiNEthZE5WejVsV3FXb2VoUnFZSmhlbXdjZFR5Q2NTangiOw==','2018-12-07 13:37:57','2018-12-07 13:37:57','cec4bd04-a5e3-43f2-97ca-add78cc7806b'),(1071,1423,'34c1d945ba2ba0a6ba5bbc1f99b9f38a738e1b3cczozMjoia3d2ejJmS2JUMTZnSlp0eWZMaGlZek9wZ19tWWxwNEYiOw==','2018-12-10 09:49:21','2018-12-10 09:49:21','fef4f618-a094-4ab1-8f9a-0b79ad848374'),(1072,1423,'7629e832679d0f1df4dca98f3cb61c2cd8c75998czozMjoiV2F2SU4yY2dKdmtQMmRjTmtZMzZQX3d4a3lhelVrSzEiOw==','2018-12-10 15:15:27','2018-12-10 15:15:27','051a9e43-1952-4db6-a912-03ae6f41cbcd'),(1073,143,'f58ac579a027843ae87d600b042487e6ac7894e7czozMjoiMGJCVHFtV05lODZaQnlna05fcmU2Sk8xd2dvT1MxR1UiOw==','2018-12-10 18:27:06','2018-12-10 18:27:06','74df60e9-0371-4428-b544-a997bac5317c'),(1075,143,'abaf3a24b23d1247269057cff15db95cb161d468czozMjoiNVpfeDBFMmJyaXIzQk9COVZUcDk4NU1XSHZmYUpyTWQiOw==','2018-12-11 08:19:12','2018-12-11 08:19:12','aa88c7c5-bc06-4dcf-81ea-4a77be99092f'),(1077,1423,'eed5cf58d3e2c0f7898781f08a595359f49c8002czozMjoiS35zUnlKamYzb0lLNVdvWDdrOV85M1podGZIaDBXQzciOw==','2018-12-11 09:23:32','2018-12-11 09:23:32','773efb9f-6241-496e-b14a-b785f642da11'),(1082,1438,'901dc1033e5cdbcecb7cd6eaf8650f752d98a7b3czozMjoidzVXT3hPRH5+OFNwX1dBMW0zSFE2bVVkZzBoY0d5R00iOw==','2018-12-11 11:34:08','2018-12-11 11:34:08','d4f7aa3f-b43b-4237-9854-062677168f21'),(1083,1423,'bc3ccf2b8b69aa6d6a2029d5d679c7c4d73d6a88czozMjoickdDVkRydUx5YTFDOUl3dkJsR2NYNHVIdGxYY0ROVW0iOw==','2018-12-11 13:13:36','2018-12-11 13:13:36','741c9c4a-76ca-477d-8266-6349fd7bbdeb'),(1084,1423,'e5721823b089cf9ac6af5b1b27008318adc7c715czozMjoiaEQxMnhVTWY5YWFVT1RjdXpLeWpJSmEycXBnTXhPclciOw==','2018-12-11 15:05:27','2018-12-11 15:05:27','0b998e65-a153-436e-b2bd-49d3ac76cebb'),(1085,1,'b1d63ea2efd8ddc8504bb496521b3bbb1b99ae0cczozMjoiVkJqbEc1OUxEa3h+UkVmdFRwU05seHRLZX5nbGY4Mn4iOw==','2018-12-11 15:16:21','2018-12-11 15:16:21','4db31796-44cc-4d17-b3a7-c3392d52337f'),(1086,143,'9d0d85ecfa5dc2e4ae89d0bf67fe995de5c94103czozMjoiWjVtdXY3ZkhIcFhkTDBIVWdWTjZXWXZ6dDd1VVBNWVkiOw==','2018-12-11 23:27:04','2018-12-11 23:27:04','487decf2-0faa-4641-8a94-2fb1a122e0f2'),(1087,1423,'b227895895d5f9fda26f5fab24e1bf4defdeffb2czozMjoiZEd5VUx3THk1M1Q4YnFJa2V1VzM0UmYzYmVvNEt5S1AiOw==','2018-12-12 00:21:59','2018-12-12 00:21:59','1b9803ea-bd88-488a-b3ac-34b07fc07ff9'),(1089,143,'1779cd92ce3683dd2d71a989ed863688c065f9d7czozMjoibFF6ZmNlVU1ZSHVGT1B+SmRHRV9zTjh+ODJCeEtqQ08iOw==','2018-12-12 00:38:38','2018-12-12 00:38:38','ef247fd9-e494-484b-b865-b8de47f84771'),(1090,143,'2661c378a2ca5a851abd788ef1a27a605c95da06czozMjoiQ3hxcUhaMFNZdzhQODhhVm55YTRZVGhraW00dm5BN28iOw==','2018-12-12 03:01:06','2018-12-12 03:01:06','bea5f76f-064f-44b7-960e-4060981165d9'),(1091,143,'ce163740a8fd9cff79e793e8a73e8f0d32db0c11czozMjoibHdnYTJTMkNIblBTUn5oNTJtMjZaQndFRm5DUHZScFkiOw==','2018-12-12 05:35:39','2018-12-12 05:35:39','00dafc7c-db4d-4435-8dcd-331ec6143c99'),(1093,1438,'78e47ceb30be76d0eb07f48a004bb4958c698435czozMjoiUnIzWDZBV2JiMml1dUFwVm1wM1JZV3ptVTBmekxsYWMiOw==','2018-12-12 14:18:29','2018-12-12 14:18:29','9e14f5a2-bce4-4efe-9885-78b13967c806'),(1094,1438,'963dfc9176f57771442556ce443d9628f4dc1e71czozMjoid0xzdFVRWDBNR2pqbVdvTW0yVlJmVm05bDFWNTFtNGkiOw==','2018-12-12 15:42:14','2018-12-12 15:42:14','9e72b5d3-34b6-4b16-b5b6-e442f6e65f3d'),(1095,1438,'90be0c7d3b6f77b5d11eaa45da87ce8917d8e49eczozMjoiOU5pMGFzNGkzMThkNUtyRjdZfjRFWWVlN1EwdFduVGoiOw==','2018-12-13 08:45:55','2018-12-13 08:45:55','51042c16-20a0-4300-aa88-cf602447023c'),(1102,1423,'1702750a7e4a925d2ec54470d145254a91840d10czozMjoiOTc2dVM2NjNudmNyc3VZc1o2fk13SkZtT2xQZU9VX0oiOw==','2018-12-13 11:06:47','2018-12-13 11:06:47','4ccaa352-0a3b-4a48-819c-c75dfc201080'),(1106,1423,'e0efd4e5fdaafb512b7241b7fe6a51d2d1b04c24czozMjoiQ3VSRVk0em8xeTRkZk0xdlNQYWNUd2N3WnB4em1ma18iOw==','2018-12-13 12:00:26','2018-12-13 12:00:26','34c45dd0-2c3a-4a0e-a596-a9f5e1c4ee9d'),(1107,1438,'7303788fb6c8eb9a84962d50bfdb27bd5fa795c4czozMjoiOFF6WGh3T1dhUWVnbngzUGdKT21ZY0xCajljOXVHUlEiOw==','2018-12-13 13:37:27','2018-12-13 13:37:27','9cda379e-bbbc-4e42-b844-6336918a4ad3'),(1110,1423,'b9615b6a0c4b64547bab054256aee0ed6fe21bdfczozMjoiS2ZWaVVFUTlyTF9wY0k3dFpKZEVRR29BMWZNQUV3X0siOw==','2018-12-13 14:28:34','2018-12-13 14:28:34','d9635ee3-1159-4524-bbcb-a3321aff96c2'),(1113,1423,'eeff45c9ec752f9c1c60fa2db0b986f510e067f8czozMjoiUjJvcW9iNXVqRmQ4dGVaejNLWXBINHFtWnk0bUtEQ3AiOw==','2018-12-13 16:29:01','2018-12-13 16:29:01','ce964da3-6ad7-4acd-99a8-c33f4770369c'),(1115,1,'c43a8599e707703c9293c448c839be819b79dc30czozMjoiaEZwQWFEbTh6OWEwNjFSZmtDYTFZRXF1TU50dVM4WEMiOw==','2018-12-14 11:10:43','2018-12-14 11:10:43','adacff9b-9f58-44d7-8ff9-cf00c817a8a1'),(1116,143,'ba943444e952e4d23232c3caa1d981bd989ca16fczozMjoiclM0dE5MYkk2ZGxTT190U2phYkVFcXpUQkFGMmdXT1QiOw==','2018-12-14 17:50:42','2018-12-14 17:50:42','bccb356e-aa6b-4a96-91eb-77dbeffc9fd1'),(1117,1423,'5b57cb17781be85f82bcd618be611189d78790ccczozMjoiMnFXUVFGa3Rza2pkcTNXUVh5RH4yYXd+N0htaFlobWMiOw==','2018-12-17 08:29:25','2018-12-17 08:29:25','da92fec1-0606-4f0a-a759-8ec1d9d2859e'),(1118,1423,'1be0a089b99f672486fbf3f361a08393f7b9d04dczozMjoia09kZUdnaGY4aWZKWDZNOH5NaGcxTUpyQzVjeUF5VX4iOw==','2018-12-17 08:32:33','2018-12-17 08:32:33','17c6b8f5-ee53-4bed-91a2-b9a35b5412a9'),(1120,1423,'6f5adf87c0420721f238887a11930d2c93e36ffeczozMjoiMXRZbk9jZ0xuQ1pJd2ZxUzh5MzlJWlNXYlhPc2NtRkciOw==','2018-12-17 08:47:36','2018-12-17 08:47:36','8eff63dd-717b-4b91-a486-d23c14e77bee'),(1122,1423,'ea1b618019346cc55a232a75c40728adda55805eczozMjoiSktmSVloSlNWS2haVU1FNldqQWZkT25xaENZM3JvSn4iOw==','2018-12-17 08:50:24','2018-12-17 08:50:24','5983b016-f7db-4775-a72a-c39f5b49115b'),(1124,1423,'9cdfc3dcdfc281e4627226babe6dd4622eebc896czozMjoiRzMxaXc0QndYYzZGcmNyOG5FVHNuZjJlOEk3WTNWYnIiOw==','2018-12-17 08:57:22','2018-12-17 08:57:22','57c643b6-138f-4550-b10c-3c33a6c2d05c'),(1126,1423,'6696bd68c744cf275d8b1560cacf3506e0640469czozMjoiaWRGTkhiRkdWOWhPVTduOVJyZjRFckZwTll5VnNQVVIiOw==','2018-12-17 09:17:57','2018-12-17 09:17:57','c53b5489-27db-47b8-9074-d7d8688d6a18'),(1128,1438,'a3fcacea1fef6029ee11543089f8f5adbf4141f2czozMjoiNk5OcEw1akxmNXQ1dXE0VGt5MWVkQmRSQnQ1UnJjU2siOw==','2018-12-17 10:01:57','2018-12-17 10:01:57','05853834-79e4-48e2-80ae-0c4886f6f459'),(1129,1438,'2b083c8605ade8f80cd6ef55dfcf663a090e8f14czozMjoiVWxJWmcwbmo1YzdCY3VhRUtqfmJ0MzJueWlIQUtHMFciOw==','2018-12-17 11:07:32','2018-12-17 11:07:32','b91887f8-ba6d-4c3c-9d99-ee7a96fe8bbe'),(1130,1423,'11ed1d55db3aca2d21cbc05b5460c8d18d74b89fczozMjoiSEQxcWdIMWVPRnJMYkhDZUpTcmxFUXJxV2pWZEpFdVYiOw==','2018-12-17 11:45:03','2018-12-17 11:45:03','b3a11e16-b07e-400f-8ee3-351cfcc6ae3e'),(1132,1423,'439c7e73dd1c5d5139761a6d74c65e842dc1cc3dczozMjoiaEFUQVJydklTU21OQXc3Rk96d0dxYnRaRGdoflJ6TUciOw==','2018-12-17 12:22:33','2018-12-17 12:22:33','f6a18550-77df-4505-a7d7-353f5d3d5dae'),(1134,1438,'057a1dfe448cf3e8c2a8550a80a6b24437dc5b61czozMjoiVjd1cjlObnppNU1+VkJRN3NGNnA3VWRQUUVyZzVafnQiOw==','2018-12-17 13:42:53','2018-12-17 13:42:53','ccddb211-4177-449c-8a1f-cf72bd60a168'),(1135,1438,'cffe0a1ba5d2bb8006c08efc90eee387f38ef00cczozMjoiSERueVZVd29pTHFZOTNuemZ6fjlfang5TENyWFJXSGgiOw==','2018-12-17 15:07:14','2018-12-17 15:07:14','9f41feae-b3c9-4dbe-a7e1-e4291b87aeba'),(1138,1423,'7be17a3083a2791e5a1b1907f125533d84d53563czozMjoiNmt+dHdkbEJ0UEpkOFNNTXAzcVdJYkpMdWVPQnpnSTAiOw==','2018-12-18 15:15:40','2018-12-18 15:15:40','8388f10d-8cff-46ce-af35-66745eec962d'),(1140,143,'1008f52b9e8e1207605fdec5a44208e78d7b8d19czozMjoiemM5OXV4SGo0aE14RVpud2JoajB3QX50M252azZFSVIiOw==','2018-12-18 21:40:45','2018-12-18 21:40:45','281f6ffb-8edd-4dee-a5fb-77d90d01cba5'),(1144,1,'42097d3c36bb8445fd1d3fcf27fe0e5949891ffdczozMjoiWDMxQTdCX25pclh6bGtBeGhoajRtQTk4MzRYTUJXSnMiOw==','2018-12-19 10:14:48','2018-12-19 10:14:48','38da3d5d-3b0b-40c9-895e-839a162824ce'),(1147,143,'703842f8739051e1ea657a0bd7af5a5c27d58cfeczozMjoidDF+WG9JcUk0d2Yzc1lyaktOMjF4TWFCYkhoTTgzUkoiOw==','2018-12-20 00:29:49','2018-12-20 00:29:49','2a9ac2ab-1ea0-4a5d-821a-b72a22ff585f'),(1149,143,'458c9b09a5affd6a06f7e09c527bfb8cd2e77acfczozMjoiamlDeWFUYzNITWNReGxveExyOXoxV1N2TUptfmJMOEwiOw==','2018-12-20 00:31:05','2018-12-20 00:31:05','3ac2d57c-e8a3-4b9e-a0df-b11d3ca1ba94'),(1150,143,'56e58546702ad41d2ff431f2874dcdcb0b4bd17dczozMjoiUn5ZSmxLQXk0amdtMkNxMjVWeE4wU2Jxd3ppbHkyZkUiOw==','2018-12-20 01:01:47','2018-12-20 01:01:47','82ec4953-04a9-43e6-8ce9-7df4a2a459bc'),(1153,143,'c19348ef7239674fdbb25917a172a16ee97444a4czozMjoiZ344UUlDczF3fkVLMlI4a3k0Rk9ka2dGOXh6Zk9Sfk0iOw==','2018-12-20 04:41:50','2018-12-20 04:41:50','f1e0baee-b362-45e6-80de-4e5a01e6d542'),(1156,1423,'ac185179ab97e8b5ec434426fbb74c6a4165e46cczozMjoiTXN0R1Q3NE0yVGxpSVBYX2hkSHRrN0hlVH43bHp1cEIiOw==','2018-12-20 10:14:14','2018-12-20 10:14:14','739c1276-7040-4856-8616-562f107538d6'),(1157,1423,'6a578863712df8c042269bfed39d72efe41dd3d7czozMjoiOVJ2VHhhR01+T0UyR25hWkN+c1RraW83cEJ4RlhBVjEiOw==','2018-12-20 14:37:31','2018-12-20 14:37:31','0ff88a49-f503-43b5-9f1e-e82ece478aa1'),(1158,143,'e509bd99d4d900e43e11c052b211797b02f70573czozMjoieVlKMFpsZWs5WWJjUGw3dl9DflVMNnhpdTlQMTF4M18iOw==','2018-12-20 19:28:57','2018-12-20 19:28:57','dd3f01ab-4f0d-4bf0-83b5-ba9762b459d4'),(1159,1,'2a463f72f6c0c6e75426e4568643ab01d53eb14cczozMjoibHEzZ3VVYVVRY2p4aERYYlJrSm4xUzUyMnBScmVrRjYiOw==','2018-12-21 14:09:31','2018-12-21 14:09:31','a0dcdca8-658f-413f-93f0-dec8c2a21c2e'),(1160,1423,'4bbe1190bdbae2875b4f1f6ab177c880cf4376e7czozMjoiUWNzdnM2OVAzZXJNVVZ2aUtCVk1sc2I1NUk5MjFfZE4iOw==','2019-01-04 15:37:28','2019-01-04 15:37:28','eaed7dfe-96ce-4cd7-bd0d-33a512adb96d'),(1161,1,'41edb62fe32fd8a060b5337e4373c984c3959d8bczozMjoiV092NWVTVHozWmRLbnZVSEhxQU0wQUpQTXdVdnRtQ0IiOw==','2019-01-07 14:48:17','2019-01-07 14:48:17','cd89da12-a3e5-459e-9d78-151ff8d5a54c'),(1163,1,'a78a8385cb580c6a71112ab9bd3f8397ba221a13czozMjoiTzM4bnVndHQ3UnBhZ3hOWWYzVjBJdzM2Q0ljY09YMnEiOw==','2019-01-08 14:19:44','2019-01-08 14:19:44','17e823f7-6409-4275-b62f-c88e6f152c39'),(1164,1,'b494e058b1f467f18d3e5fbb42205de04712e5bcczozMjoibFlRcHR0dTd5aURJNkVZSHA3cFdQS1JYQV9rbkdsUG0iOw==','2019-01-09 13:26:17','2019-01-09 13:26:17','fe743a7e-4156-411d-b90f-12aba4b29982'),(1166,1423,'b383d8b7b3ff7edac0d00930d3c40f6ab0a60d79czozMjoidWEwVTJMNWExakM2T0xFaDNuekpOTmZLb19+eWMzNFMiOw==','2019-01-14 11:35:30','2019-01-14 11:35:30','6906f91a-41fe-4849-be6f-e4266ed26190'),(1167,1423,'46c70ff67b034fa5c9cfde061512f745b3400db4czozMjoiMkhkNGE4QmU1fjU0aVFRaUpLZEhmZEVieXlYQ1NCbGgiOw==','2019-01-14 13:35:10','2019-01-14 13:35:10','9a303424-f1f7-4858-8f41-ee23b6b5261d'),(1169,1423,'a1ba7a7ee3ee8bca94c3b082a79bf89a1a97151bczozMjoiYkR0T25SMlMwclR6ZVZRc2FjbDN0RGZXcndTSlluVWIiOw==','2019-01-14 14:19:00','2019-01-14 14:19:00','beb4ce61-f95e-4384-8c78-a335b978bd43'),(1171,1423,'ffc207681f0ed05c9f723b37db50e9a2ba71aa7dczozMjoiMUxabkM5eFp6VjlvUk92TTlucnR2UWpuSkFsS2dNY08iOw==','2019-01-14 14:21:20','2019-01-14 14:21:20','eba418c7-efc6-4128-80df-3311dd4e7ba7'),(1172,1423,'8ab6c04019b2b77806fbe4aa0173bee8fb9d3237czozMjoiak1QcHRQMlZVc3c3WTN5N0RLZ2UxbDZBeUtFNnJraHMiOw==','2019-01-14 14:47:59','2019-01-14 14:47:59','565894a0-1ff3-46e9-b289-e1a6170cb0d9'),(1173,1438,'362b5d5bf5eee255510e731da3090388eccfc3efczozMjoiVzd2bmt1X0lRaUNkZWJsN0tlc0l0d285cHRXMTloSW8iOw==','2019-01-14 15:14:45','2019-01-14 15:14:45','523efa4e-e860-4975-b21c-457667ca0723'),(1175,1423,'dc6de144abef431bf48441a916dd6deb4eeeafc6czozMjoiRzVJbDJFblFMX2s0NWpyYnVVfjFRT1l6dGttWHFqcVgiOw==','2019-01-14 15:54:39','2019-01-14 15:54:39','cac9fe75-ba33-4193-8b25-6ab7a2337062'),(1177,1423,'53306bf4b074bd199289df94ca8110f1bbaf183dczozMjoicktqVk5wM3d3cExOQlY0cGdicmdLRVNJUWl2dnRNeVIiOw==','2019-01-14 16:21:36','2019-01-14 16:21:36','f8fbf87a-7672-4deb-8f4d-f600b91a1693'),(1179,1423,'e5cfb551bc286b9a465031587a2810e16bccee85czozMjoibFFEflI0WEUwbFFjMlNrM1Z1VWk5eFBUU2dfb3BQQlIiOw==','2019-01-14 16:22:35','2019-01-14 16:22:35','7d643c54-03d6-4d52-88f7-47309738839b'),(1181,1423,'86fe2707194d78a5b1b7ea87d03cd86dc1b8a854czozMjoiemxENWN2aFFhWXh2aWZIX0lZRFpXOHk1MFY5VjBQYzEiOw==','2019-01-14 16:23:17','2019-01-14 16:23:17','d0c73bf5-753c-4efd-a2c6-606b049701aa'),(1184,1423,'61d4ad7efa411c978b9a117e5b5c5b57701612e5czozMjoiTVI0MFdHNU5hWUFxellCMDVBek5rMEh0RGwxNk0zcFYiOw==','2019-01-15 08:50:15','2019-01-15 08:50:15','3a01163f-621b-4cf3-bede-b2eed70db505'),(1188,1438,'dddce93e8ce1fd36707a63ffe3c2a11647b647d0czozMjoiYlRSUDJ3TVBFNU5JekZhOEFFNEc3NVk0RTJGYjcybjMiOw==','2019-01-15 10:31:06','2019-01-15 10:31:06','1fa81bf5-b85d-4087-bc0a-b033f986c301'),(1189,1438,'fb0783fb2b86a640aae283424ebf183cd573c72cczozMjoiMUVGTXplYko4NzBoZzZURGZwdWdwWExiY0R1TDZFMDYiOw==','2019-01-15 10:37:06','2019-01-15 10:37:06','80e45447-dfe6-45a0-9f3e-99db72e5db46'),(1190,1438,'b32f77e016fb56925a310483f5a36007c492fb4dczozMjoiSlN4V3ZCR2xGMDZSdXpWVmJjY043VTVpRVVaemxiQ3AiOw==','2019-01-15 10:42:28','2019-01-15 10:42:28','8ab7e363-0134-4e69-bed7-708894f6258f'),(1192,1438,'2e07ecf02188bb0673de38eefb46319bb46b4e76czozMjoiMHFEOUE2MkVuc1A3NGtKOFowbUhXNU5UUDYydEM5NGEiOw==','2019-01-15 10:45:07','2019-01-15 10:45:07','34298d4f-8872-43ba-89fc-f71d32bb9d6b'),(1193,1438,'b68cc521a69af17449edbd625ebe6845d93322a5czozMjoiQ0JXS1BDV2lpT0U0OE5TcmRSWWVfZU51SUdyQXFyfjQiOw==','2019-01-15 11:47:56','2019-01-15 11:47:56','b097f9f6-dd69-4757-a2d0-5b0098bc5b71'),(1194,1423,'03bfc17f75cb20f750074894a17f87ca9d7e1359czozMjoiZE8xZjF4ek1ORGQxOTVCaFpzenFZVTVYSTZLRFl5WVoiOw==','2019-01-15 11:56:20','2019-01-15 11:56:20','aeb6d90d-a9be-4acc-9dad-281ef8174c30'),(1196,1423,'94da127c1eff9b7b390763f05f0f07722484f8b3czozMjoidUloa2sxdGVXRjNWVVNxcGhVWnBUMVpNMUo2a2FpbnQiOw==','2019-01-15 11:58:30','2019-01-15 11:58:30','c1ad0dc9-0c9e-4ab8-b9e4-1f25b32e4c5b'),(1199,1438,'63363faebc5cff4899060ad013dd2ff500f883dbczozMjoiMmRwWDZ4fmZ+REZ1MGNEflEwN1RJYzVQT1MyYU50dzUiOw==','2019-01-15 13:32:34','2019-01-15 13:32:34','9dea66c1-e5f4-43a0-95e5-075270bcc924'),(1201,1,'a5c993abb21ec92e75126e3ef121e38474c294f9czozMjoiM3VEWWtpMGpCRm1qNkpBTDNrY28zV2xfQjEyUnBvTUEiOw==','2019-01-15 14:11:31','2019-01-15 14:11:31','3b2c6fb8-bec7-436d-b9ec-fa0189abcbdc'),(1203,143,'9b45180d9eb4a6be18613efba2f45b762d795753czozMjoiR05SUGhSYWtDVks4dUNjNldNNzBaUkVtTjBvTXFnYkQiOw==','2019-01-16 09:55:47','2019-01-16 09:55:47','2600d5d4-a3db-4bb7-a3ec-afee2fec1130'),(1209,1438,'b38a6c865410e94fa098b3c289c220a50e6b0dcbczozMjoicjd6eVJDaVpCMnl0dTBEbFAzMXlKZ0VGTTQ5a0l+ZHIiOw==','2019-01-16 10:59:02','2019-01-16 10:59:02','504415f8-4c59-40f4-ac60-1e81d83a69cd'),(1210,1,'87d802a596e6d2f8b3949b1c78b760d8e640376eczozMjoidG5YQmtUcTUwfjRvcnVFMm5OaHlGb2tqUlJaVGhSNmMiOw==','2019-01-16 11:10:28','2019-01-16 11:10:28','c1d4cd7e-98c2-444b-a6f7-7a046818e5b1'),(1211,1,'52068ce5d759bfcce6e98937164ab4d3864bafefczozMjoiNHZTYUhQN0RCam9Td2hmOTdnOThTNDlTMXNlYmdzNDciOw==','2019-01-16 12:00:16','2019-01-16 12:00:16','c7b7bb31-adc3-428e-bf79-e3459d48a42c'),(1212,143,'14d32a2448ceef080f9da5852e48a66fe1318fb5czozMjoiR2ZQYXZ6eUNnN2hWcVNhY1ZhRG12XzhEOUgzWFBrd1AiOw==','2019-01-16 12:01:04','2019-01-16 12:01:04','a5c57f99-4eef-4192-bc17-70f2da982fd8'),(1214,1,'95209fc42e863c840784fb63bf3a0400d36f320eczozMjoiclBGUkNIMWNITlJGc1FBbklCZ0ozOGhNbkNHd1hvWTAiOw==','2019-01-16 12:01:30','2019-01-16 12:01:30','f3f1b1f1-81fb-4a6b-89cb-c8467d19fac0'),(1215,1,'5002985e56494ec2799f6502c7fa40233d48fb57czozMjoibkNsdURVdWcwakdmeGd6ZHVHaFN1SXpUWU9qVGFVVHkiOw==','2019-01-16 12:02:12','2019-01-16 12:02:12','870bdea9-7fe6-4224-b7fa-4cd60b497f1f'),(1216,143,'214ed7fdc911f3ac2a1a8d3545e1aad5f414a678czozMjoiNW9Qb3VTa25QTGJ4YXBFNEttSkxNTGxYN2UxWmNCSmciOw==','2019-01-16 13:48:59','2019-01-16 13:48:59','7b9981df-749a-48d1-afb0-f0f04f198f5e'),(1217,1438,'cea2a765594a7785de0fc1a40e864daa077db162czozMjoibG1lSmZZQ0xGa3diZzh+T3BERHl0MHdoNmJrV2pTY0EiOw==','2019-01-16 14:34:12','2019-01-16 14:34:12','6020405b-0e83-4458-ad74-ac5721ff2ea9'),(1219,1,'c09f4532b7179f120f132684ac47aa1a94076607czozMjoiWGRGMjczMmd4S3BrbldkdVM2ZDh2RTllRkpHbWpxb2UiOw==','2019-01-17 10:03:33','2019-01-17 10:03:33','b961423a-c7eb-46fe-bcb5-f55943057a27'),(1221,1,'7c5f942c9bedfb45f06af5e58966f3641d785cb8czozMjoiV0JWaHlPOF9HZ3k2SEl1TlJ+dzc1MGtXaH44X0wxTG8iOw==','2019-01-17 10:08:15','2019-01-17 10:08:15','89fe0781-17ff-44fb-a038-8aab1e05198b'),(1224,1438,'0f86bc6d315e0b62f6ca20e16986393c9048b9e0czozMjoib1B1NlpzTHc2V01PdGRRMVJmWGFIYVZiQkNPVXFlemsiOw==','2019-01-17 10:13:26','2019-01-17 10:13:26','403e75a0-02af-45cc-8adb-604b08c10041'),(1225,1423,'0b12f11aa412299f3858d8d69a8dc3a67f1ebaa5czozMjoicXdxMW0ydU1ZQ184RTlGV1FwZVZteXpjcG80UEk3c08iOw==','2019-01-17 10:21:49','2019-01-17 10:21:49','6ba8912e-7901-46ba-a42b-9dd6fb778246'),(1226,143,'4fce368102d80dac3f8e9a9de88ed15ca24808d4czozMjoiT0lIVVFnOV9NbTBPcjZKWk0weWRUTDd+Z25IfjBWVXYiOw==','2019-01-17 10:51:03','2019-01-17 10:51:03','0ba35b54-7268-41d2-9cfa-2e27e0d5d54c'),(1227,1423,'228e4e15b5b03d429a8b9318c64363cb620986a6czozMjoicktqenMxcGpDVjlxV1IwVzRjeTZqS3hrZ0taZnZZaFgiOw==','2019-01-17 11:43:45','2019-01-17 11:43:45','c40d29d5-89a8-4350-8c3e-0cceb1e459c3'),(1228,1438,'9b784a7f9f8692a7c468fc9a1aac93f98133a4edczozMjoiSU1uUkIwSWZIc2JfQmw5U1NYVzI5MWQ5T3JaZGczZU0iOw==','2019-01-17 12:03:55','2019-01-17 12:03:55','8e4ee796-d5c0-404f-9780-969328fc30ce'),(1229,143,'f247a39c0fbe6260aed09bbd68920f7caa75ad50czozMjoiVWtZUlVIWDREb2Q4eWczVFBTN1FBbGg3VWlOeG9rb00iOw==','2019-01-17 13:32:31','2019-01-17 13:32:31','650d61a2-bdc8-44b0-843e-024cc8f74b30'),(1232,1423,'35739463b5eb16b636753ae4f0831857916d5174czozMjoiM1pSRklWeHZfYTlFRlIxcGhjcVhUQUtfYm5LY1V1MVYiOw==','2019-01-17 15:33:20','2019-01-17 15:33:20','aa63f811-6ab1-447e-96ea-0771d47ed346'),(1233,1438,'b944a4f1f8f35d479e6379ad8bfaf7862b719d10czozMjoidnoyYTl+MXBhS3BjVVpnX2Jrb25fMDROYTAwX0N4Q0UiOw==','2019-01-17 15:33:36','2019-01-17 15:33:36','02f613c9-5d50-4af0-bde1-f683ca30430c'),(1235,1438,'16e393013879428fbed26bd35721039ea9dd6a57czozMjoiRGM5OXNJMDl6NFFkb1o0UzkxMlI5MXRDOUdlamJMdlgiOw==','2019-01-18 09:47:02','2019-01-18 09:47:02','b8b3ea55-089c-460e-9bd1-876fdc77266f'),(1237,143,'ba5779158d2270dc053d841a957bbeeca36a359cczozMjoiM2VZUzJtWGY0c0xWamlfMW9vOGxNRU5KelFSbGJDQUsiOw==','2019-01-18 09:53:48','2019-01-18 09:53:48','7f93535a-c8ff-42e1-aea9-042655e52d59'),(1239,1423,'07666d2da446e11987b6094835f50d7ce88a47aaczozMjoibGhFZjBjQkNRbzA2dkVrVEoxS3lCaElOUkYydTJwelMiOw==','2019-01-18 09:59:01','2019-01-18 09:59:01','a6464e6d-c511-48b8-a806-217299d137a3'),(1243,1423,'e86d6af1e0c853a3420c2be9cf796cc801221aabczozMjoiWWNxdVkyMHZnWHlhSGFhRkpXakhxX35ZSkE3SFdBRX4iOw==','2019-01-18 10:12:36','2019-01-18 10:12:36','509aa495-fb4d-4486-9813-ba7c70775964'),(1244,1423,'61526d5539dc732b864d3c18c852aa6470b31c9bczozMjoicEszV1Q4WnFWaDhyb2liZDRudWpaenFVdn4wQ2ZqNVgiOw==','2019-01-18 10:18:46','2019-01-18 10:18:46','9c4b3c20-e350-4b09-ab28-40e480e69180'),(1245,1,'65d6c3970cb2e567d9a57635e47f7ec4c8ba4fceczozMjoiS2k2QWcxbkhiUzFwfjJZMWE2aE1iSXZaWUJEMDhuSXgiOw==','2019-01-18 11:24:38','2019-01-18 11:24:38','fa34dd53-8d3d-443b-b736-bd21ebb63345'),(1246,1423,'80e0a001fb6cca68e07c9bb5007e487010ade809czozMjoiazVQc0t4WEVSd3d2b280a0g5SzE1UWhybkRwazVIeGoiOw==','2019-01-18 12:07:16','2019-01-18 12:07:16','3e44e445-4b61-43e2-a3c8-d862f2ca4eb4'),(1247,1423,'dc298a24c5238b523b86132124257f30e0b64da3czozMjoiRjlXVDRYMXBmb3R3cUtVZ2JTWGdyS0R+ZndZT1ZqU2QiOw==','2019-01-18 12:07:17','2019-01-18 12:07:17','129bbad8-6249-4587-b9b5-78a39a3c038a'),(1248,143,'e8ee8311db810b2dc38ac78b7a6d4572bb62e00bczozMjoiT3NXelRYVGlEYkU5M3RXbk81TkpXQmJUVm55TFdEdUYiOw==','2019-01-18 12:07:51','2019-01-18 12:07:51','a0e48f40-4a1f-4d63-b04a-56ee743dca05'),(1249,1438,'fc9dc54cb9114c07ddeecbb397da2ab3c699d1edczozMjoiUV9TcVRfOXBKTkdfREFqWmpUfndrS0VURGdCOF9QQU8iOw==','2019-01-18 13:18:54','2019-01-18 13:18:54','d5e638c8-6788-4043-9038-448a38c39138'),(1250,143,'b0d69f4632accf70c89fa1503b87ef2c80cc20aaczozMjoiazVINGFvZDNNRUd1aUZhMkxIQmJGbWpkNlhpdmNEbjEiOw==','2019-01-18 16:46:17','2019-01-18 16:46:17','e6a7734c-9137-474e-86c5-f7bb8725e948'),(1251,143,'00e0cf434ed9e72a187c864f919bb3f691acfdeaczozMjoiTmhKZkV+Nk9paFdESjhjYVNWYVZmRW5YZW9rTkExSkoiOw==','2019-01-21 15:33:13','2019-01-21 15:33:13','4eb11a93-ff6b-455d-bfad-668ce9a195b2'),(1252,143,'28845b0412a73478e92b9c8931a2726cc0c7f8e9czozMjoiaHZydU1LSWVUOHFsS0VJYTRDQVptalJaeTBXRDZIWjUiOw==','2019-01-22 11:48:19','2019-01-22 11:48:19','433a244f-8b90-43dc-bcaf-846d9a4d50a0'),(1253,143,'47df7acae6e7f6f9f84110c5b8547802e6224da2czozMjoiRFJyeUVtQ3RISHdVa3M1UnZEVEVZbzl2fjBPTEExUloiOw==','2019-01-22 14:13:15','2019-01-22 14:13:15','c455309a-6c93-47e9-8c42-6a7838deaff5'),(1255,1,'0139bf95e0e251f7acfc9a201b7bb7f03fc34be3czozMjoickltdlRNXzZYdjlLeTF5OHJ0YUpRdFp3b2x3TG5CM0QiOw==','2019-01-23 14:31:33','2019-01-23 14:31:33','669e9a90-36b5-4a73-89b4-4d1ee5a56fe7'),(1256,143,'2c5487486e1e9724bc029fddb79425a982d3b63eczozMjoiTnRKRWNZVTdJM0RkbEZSZ3U2YVg5N2RuVFFsVmI0MGUiOw==','2019-01-23 15:12:52','2019-01-23 15:12:52','41df2c72-b249-4c41-b1ea-b943340abbd9'),(1257,1,'01ce295cd1d546439c7c5642a3262da5dff67a74czozMjoieG13N2FtR21RS2dXYzlZck5RUDl4SV9QQ3BrZHd5UEgiOw==','2019-01-23 15:15:57','2019-01-23 15:15:57','621c6845-8213-456e-a96b-91d7410a7d65'),(1258,1423,'cc9a360725f78cb32b30fcdeed863a530611b689czozMjoiRVl5WXNFR1doUkRjY3l+OHRaTWlKbn5FVnZneVlMfnoiOw==','2019-01-24 09:27:36','2019-01-24 09:27:36','7c8d3893-cdf2-4137-a85a-394a6d5340fa'),(1259,1438,'d48c98db433fb538ad572a7171a295684f64ff39czozMjoiWXNrRzN3bXM3TUZ0UWFWNXpRcGZqOTVCM2ZORjdwclkiOw==','2019-01-24 09:27:41','2019-01-24 09:27:41','dfb9753d-9296-45e1-901b-81e2e2862796'),(1260,1,'010f8b4ed68f0a9f6674fabee10b9887cffe1799czozMjoiSHV6Tmk3Yn5lUnZnVjRIOWFVN21wflpQRGNHc2VIVlkiOw==','2019-01-24 09:57:46','2019-01-24 09:57:46','6d0c457f-eb63-442a-85a5-7988a31169ca'),(1261,1,'0a93fd4c87653ae8b8345f4fcd72ea974438b2d0czozMjoiVmxNb3FEbm5qWEVTVmhLSFFKTHVrcGlHSkJ0TXJsVEciOw==','2019-01-24 10:34:17','2019-01-24 10:34:17','6e702294-9196-4b57-9996-60b80dc8462d'),(1262,143,'ca06aed7914683c321e9dc2035f67e59775e2a51czozMjoic1VucDU1WjdfNGZiTUlBTEluNDFaaWxCMTV4Ym9QQlQiOw==','2019-01-24 11:46:59','2019-01-24 11:46:59','0193f6b4-12f4-40d6-bff6-021b8cfd4d98'),(1263,1,'1616fc1154910437159300082b633e6660cb4b47czozMjoielRnaVd6YXNIQ3NobWVCTkNpQVphd2xXcEFkUzFfU0IiOw==','2019-01-24 11:56:14','2019-01-24 11:56:14','ce284df0-2a3f-4fd3-8113-044a6607aa74'),(1264,1438,'c9f84e38a14861e6b59868942cc770772cbb4814czozMjoiS2dvUXUyVmI0QXIzWjFrNDlrRHozNFhOblRpV0lNQ3AiOw==','2019-01-24 13:21:00','2019-01-24 13:21:00','00b2a13c-71bf-4c83-9c1d-705f9dacd63d'),(1266,1,'6a28b004af5121ed75a2ec2b5949dbd8e821e899czozMjoiamR5Z1VGZjgwQVltQm9DQXhUWGtNUkxlbnh3czJ0bTkiOw==','2019-01-24 14:07:26','2019-01-24 14:07:26','d41fe215-bf4f-4308-aa2b-376e6db655b4'),(1267,143,'64232d9942d5620e65a8266dbcc55e6218c7a393czozMjoiRlA1cGRvalF2RDhPcEt6eDBJM2Zma2Q2VVN2TnFlMUciOw==','2019-01-24 14:28:43','2019-01-24 14:28:43','7c90a5b8-84de-45b8-9981-bd5ae9d48141'),(1268,1423,'7d3235744182493777832237478574ea0b2fddacczozMjoiWGluTk1JalRJOG04STh5ZzhBazZ4TU14XzVlcEt1T1YiOw==','2019-01-24 14:47:30','2019-01-24 14:47:30','847038bc-7a46-457a-ab0b-3ff8b8cf1c5c'),(1270,1423,'2eae8e0f87b6e9fed565955f3a896d35841f1386czozMjoifkFXfjRWfmVRNUZ5M09WY2c3Vl9qWXo2S0FSU0VHaXMiOw==','2019-01-24 15:45:15','2019-01-24 15:45:15','260f08c9-a2dc-4b94-a4b3-8558cf33c01f'),(1272,1423,'c613c5d8afedf17badeeaa9e726f23faa13421efczozMjoibW5qQmJ1cnl+aHRqU3NNUGVuNkxTcUJOQXFQZWx4MTMiOw==','2019-01-24 15:46:39','2019-01-24 15:46:39','e5431bbc-fc17-4c31-8422-4994655a418b'),(1274,1423,'043c260f35f745125c5bdd2ff90336ed64d3e261czozMjoiQ0FhTm9RWlVaZ2JJU284RHhobVBfalFOamwyU2hzfm4iOw==','2019-01-24 15:48:13','2019-01-24 15:48:13','562200c7-73c9-4548-81cd-96e461371449'),(1275,143,'9cfbd065d537afbd7c424483373f07999385885cczozMjoiOHNXb35FQk1HSFhubDF3OGZxcjFlcVlfRDk3WlB0N3ciOw==','2019-01-24 15:48:49','2019-01-24 15:48:49','3ffa845c-7cc1-4273-ab44-7a63f484172f'),(1276,1423,'7c31d637ac709feeb86dacec5952fbbe6eca443fczozMjoiOExUSTNHN3ZQT1NwUFV2TnphNUVDQWJ+UHQ3NUNsNEYiOw==','2019-01-24 16:54:08','2019-01-24 16:54:08','656fee44-29ca-407e-b09d-9274acc0d826'),(1277,1423,'ec05d9c09b90d9faf0dc814c0aa73e6c04f21710czozMjoiRUFaUEExOFI2THQ3V3ZsY090YWdHYmw1clZaZDBqc0oiOw==','2019-01-24 17:16:00','2019-01-24 17:16:00','22d22886-508c-4ec5-826a-30a9376a4ee9'),(1278,1438,'eeba8781e76e60b8fec024d296fdab64ab723361czozMjoibmp5QVZWaHMxRmM4TmE5MkJ4QWRlTTNWRTBSWVd5TlEiOw==','2019-01-25 10:33:09','2019-01-25 10:33:09','5fa5afa9-c58e-4d09-9d44-a96f86861066'),(1281,1,'2df26d904968d757d5ee0e81267a9443f28ddb04czozMjoiWDhYWDFvTnpxb1BQM1Z+X3R6bGZDejZrUXppbG5YdXQiOw==','2019-01-25 10:56:29','2019-01-25 10:56:29','f2de4668-8407-4dcb-aaf4-89e64eeb4d7f'),(1286,1438,'472bdde4750b04ae6456a9f87c0a7a4aef0dcfdaczozMjoiOTBPU1UzeFhaQ1d3aTdEdnVGdDIwQklRTUZvS3NxX3YiOw==','2019-01-25 13:23:30','2019-01-25 13:23:30','cce6bc4a-f182-4422-9388-161b2c4b59f7'),(1302,1,'eabd919278b43d8fd367a126bf0f31b85e044c24czozMjoiMzJocUVUZ01yN1h4WjBqQ0NhQVpaQl94S3lFNVU1M0wiOw==','2019-01-25 14:26:19','2019-01-25 14:26:19','08926091-f5eb-40d1-8c63-bffe59501d4e'),(1304,1438,'583e9527320dbd358ba3546945e043f3d96139daczozMjoic2U5cFRrZnNfTkJlYmIweF9LanRjTlFfYVJvRUczMjYiOw==','2019-01-25 14:38:56','2019-01-25 14:38:56','afe1ee70-fb22-42fc-a461-106cf8ce124c'),(1306,1438,'8dca3e14534bb4b48d078bffde19bb2c110c6c22czozMjoic2lGaHZnSmt6SzhQbEJDMDlRNjQ1bH5EQjl1bFM2NWQiOw==','2019-01-28 09:52:47','2019-01-28 09:52:47','f41319eb-abb0-4a93-8d26-6638031c040c'),(1307,1438,'373eae6f25917d17e03124eaded499e233d1156aczozMjoiSHRuYzdBNlhTejF2dzRSbUJ1M2VtV1RXeDBFY1JBfkgiOw==','2019-01-28 09:52:50','2019-01-28 09:52:50','0b2ae3cc-6a2f-4dac-a345-51a88accbb3a'),(1308,1,'40caa9677409597c6648ec33c14842967b5a4296czozMjoiTDJzZn5uc3RkTWNpNjRQM045TEVfQ3pVelFZWHNCeDUiOw==','2019-01-28 11:49:27','2019-01-28 11:49:27','cbd39634-2fbc-4d1d-93be-bdf9a2f8a7e5'),(1311,1423,'29d137dddcda1c3d33aee9ee29f8e336ceba5189czozMjoiUDh+R0MzTUZlT1RsN0lacmpyV051YmVsRVpYN21mcDciOw==','2019-01-29 08:29:58','2019-01-29 08:29:58','aab7255b-eb22-46fc-965a-3290e4188cd4'),(1312,1423,'370b8b23d8439fae0fdd2b7f0f54244f332c028cczozMjoiUnZWNGVPU2RIbHZHSzBwdldzZGJxN0VhdTlQMDJIdzYiOw==','2019-01-29 08:46:47','2019-01-29 08:46:47','9309d7f2-e3d7-4772-bc6e-f478759ef5e0'),(1314,1438,'d1ce1e321e81a52e3eb38ee98cd0b856269113b3czozMjoiRVJEOV8wMldiUlBGQUpqOV9iYkR+bmRUeDRlMHdNdWUiOw==','2019-01-29 08:55:02','2019-01-29 08:55:02','9c776daa-9c7a-47b4-b944-f438fe70ef19'),(1334,1423,'347b86dc307661a1ccd90c2ef06158a233c934b9czozMjoicEpVaG9HcjZjcktRREZIaFJHa3lWcnZxalBVUnczalgiOw==','2019-01-29 15:08:05','2019-01-29 15:08:05','377102e0-d487-4efd-8e2f-7cd7b18cb1d0'),(1339,1,'72221aa8ad4d2add01961677ad5f2872bd5725f9czozMjoiZExWVnJWaDJVS2c4S29HbF82cjAzNjM1Smp3STZwblMiOw==','2019-01-29 15:46:04','2019-01-29 15:46:04','484f5d60-a183-4c0d-a7f3-81c6420c5445'),(1345,1438,'320800760f83226a65491d00d345b066ff09c632czozMjoiRjVFWEpON01QcUdaTmRUNVFOb2xtSlpFRjF+cHRxbVIiOw==','2019-01-30 09:00:35','2019-01-30 09:00:35','93720c07-38a1-44bd-8f55-93851c336dd2'),(1354,1438,'713a1322803def2353545983570f86c1b6736437czozMjoibnBlTn5oNTNHM1d6dmc1WGZkQU53YktYTFhuUk5mMEYiOw==','2019-01-30 09:25:27','2019-01-30 09:25:27','4e0abb21-70d6-4dd0-af7b-c2b0d80fbbb2'),(1356,1438,'2a963ba84bc142c67da4ddba5c6b7143ebba44baczozMjoibzhDVUM0U1djd3p5a2Ria0hTVF9DVDUzVnJFV0NlT0QiOw==','2019-01-30 09:51:04','2019-01-30 09:51:04','77523bde-3ec4-4a5a-a23f-a8f57a6e5fad'),(1357,1,'dd17490f3d70f39256c4255680013e342d3e74d9czozMjoiQ1o5ajVNczRkNzFaZHA1aGlsNjZJRVZ5ZXV4R1F+c34iOw==','2019-01-30 17:43:52','2019-01-30 17:43:52','c59ff06d-53f1-4558-9eaa-0b93ffd90b9a'),(1367,1438,'04500cb0df83afd0ac088af2daefd2204fc30f80czozMjoiTnBHUX5xWWp0SnBZZVR4SjJpdW1wYkRvMkppR3pDbVYiOw==','2019-01-31 09:25:09','2019-01-31 09:25:09','17c9a3dc-8c4a-486a-b680-8b05dd52fc69'),(1368,1438,'00a33481aff12538172175a587afb305e3b56bd2czozMjoiX0VGWXgwa1FhaV9+ZnZVYUg5U1dpUFhuOUVrYTR1ZHMiOw==','2019-01-31 09:26:35','2019-01-31 09:26:35','c5687899-9d48-40a0-9d58-8d49cb437142'),(1369,1438,'1785287ca731e7379ab7880de178c8f88c17ffc2czozMjoiUHZ2d3ZYcnpib29XanpBdTF6ckVrbXU5UWFrcDBwbngiOw==','2019-01-31 09:27:16','2019-01-31 09:27:16','f4c4586c-afb4-453c-b167-d7fe9066a154'),(1372,1438,'dcf5fa4353f01ad839e1d5b65b9a406e05263c96czozMjoiUDBHeGk1V29ZbWxHYzREdk1YfjFLY3FnM3NYSFlOMDUiOw==','2019-01-31 10:33:51','2019-01-31 10:33:51','dc008f10-c71d-4735-b4e2-01f291592866'),(1373,1,'539d5b1fd4f5a860f62799141010e1fe6bcfc271czozMjoiaH4wTXRJWjFvdGpWX0RXdTFPVjlxYTRndUtFQlFVQTUiOw==','2019-01-31 11:07:57','2019-01-31 11:07:57','10799ac4-46b1-4cd8-987e-ae4548f4d35d'),(1374,1423,'19be964e293aba0bd78a8df8803ff7b084c223fbczozMjoiMzZOdDJvM0ZfbzhiWHNjZWlxSFdkTnNQdUtPMUFYaWsiOw==','2019-01-31 11:08:07','2019-01-31 11:08:07','eb7c355f-5a6d-439b-bdd5-aa79dc856a0a'),(1375,1,'3d53a6d7a6299f0a9c10fcf2e3819db7563bea05czozMjoiV2ZGWU9jTzZNNndnZ1J0VVVFek5hVVlkMXE1b05DbWciOw==','2019-01-31 13:53:16','2019-01-31 13:53:16','388b8b93-be03-46fc-bcf0-1ea29d4a4cb7'),(1376,1423,'d63d0c5578dc614d7975b3a4c1895b6875e04a7dczozMjoiTkd1Rk5GQ1BzMU5iVW05SDB5VGN4Sn5KdnZ+emlUZkgiOw==','2019-01-31 14:05:01','2019-01-31 14:05:01','54788131-6758-4729-bf41-3cb63225c2a5'),(1377,143,'b7f9a3fd7b5498389bcd8069bb8e085618bb90f0czozMjoiTmQ3OWNjZG5PTG5ZMFljbzF+fkNYQUhjdW9KX0RreDgiOw==','2019-02-01 09:32:36','2019-02-01 09:32:36','7191f975-6a7b-4816-8a34-b86e827a81c7'),(1378,1423,'0f07528d0ea111a1b16719224353ca39c7869d1aczozMjoicmdjMG5wQlE4azU0dE9aa1ppTnVHMUpTVGp3OGI1ejYiOw==','2019-02-01 15:53:11','2019-02-01 15:53:11','e9459fd8-2cb2-415a-8787-63be5d90816f'),(1379,1423,'5c470c55c40b140c96bd413951aef1f640d894deczozMjoiU0RkNmtDNU1iajd6bEF0VTBfYn5LdlFLT21ZdVhQX0YiOw==','2019-02-07 10:33:00','2019-02-07 10:33:00','c7b4d28c-2fee-4193-b99c-ad304a039d5d'),(1382,143,'ecea3c549e29ff4a4ecd3d62d18eace8fd800b41czozMjoiMDJKdkcyRlNWbTJRNUwzNlVyfjJKVFFjN2l0fjJYTEQiOw==','2019-02-07 13:22:43','2019-02-07 13:22:43','0d87f4ed-b735-44c1-8f07-efe540e5d97d'),(1383,143,'727632f276aff9a84913024111ef60efe405b27eczozMjoiV3dFcU1ZRzJOOThuVGFTYUtvbEhUbGhXbnU5Vm10cE4iOw==','2019-02-07 15:19:33','2019-02-07 15:19:33','e1ec912e-b02a-4a23-8e72-92817da659b0'),(1385,1423,'9985bc96aaed80cad1182291df847d0471ced626czozMjoiRmppZ0w0R1dyTTRxSlpkME5CaDhGeFpSSHVCWHV5TkQiOw==','2019-02-08 08:28:32','2019-02-08 08:28:32','895121fa-cb45-4dcb-809d-db65234e68aa'),(1388,1423,'97c96b687b158a5bdd021a9f6dcce764f632e110czozMjoiflNtX0ZJRXRjdVBjeUN6Yk1PS1lhSHd1a1RHX2FJaFgiOw==','2019-02-08 08:46:52','2019-02-08 08:46:52','9a95d3ae-03c6-4f59-bca9-e742058d6e2d'),(1390,1423,'01f8df539e9459805626e8e754c46d769f4c5f3cczozMjoiRnNERG5oWDBScHZsMGRrbHpkaXFmOEZCcGI4V3RkR0oiOw==','2019-02-08 08:54:32','2019-02-08 08:54:32','d881d36e-535f-4f84-97ad-7984d97d2c6a'),(1394,1423,'a875f67f51ecdb4de0f14b288f4df45fb31f63c0czozMjoiWUpaQkJ3Z3pQNW5sa2d2aUpYRVVqODA3QlNIblZ6XzUiOw==','2019-02-08 09:16:54','2019-02-08 09:16:54','ea2bfe36-fe06-4714-8abd-a514715fe257'),(1395,1,'68c399b788658998835b16ab739335e81cad5d94czozMjoiOEc0NWJpQ1lEQUJFbjdBZlFzenlDMkZGcDB3eG5PQkoiOw==','2019-02-08 10:21:24','2019-02-08 10:21:24','086b2088-5b12-49a6-8140-8eb9a246f498'),(1396,1423,'ae0e23f13c046e57b10989ca2d53175e571ff819czozMjoiQkJsYldsUmpZc3N4cDExOERrVTB6cTdQZ182QkR+aWkiOw==','2019-02-08 10:33:17','2019-02-08 10:33:17','c1e4f01b-971a-4931-996b-1084d3640a01'),(1397,143,'aec6ebea4fffaf6d051b0c516a306fc13e6801afczozMjoidVNyRUxfRk5LQW9Va0Y5UjVTWWZSeGwwaklZVHg4d20iOw==','2019-02-08 10:56:39','2019-02-08 10:56:39','54323911-2dc3-465c-a7d9-3b950609d9dd'),(1399,1,'313c5f3132fa3d5db3c5f8fba1a31353ebc44761czozMjoiYzZpRlphNlhnSGZWVzlsaX5kQVc0bUNuNEJwR3pVbHUiOw==','2019-02-08 11:50:53','2019-02-08 11:50:53','83026325-fb71-42cb-b850-c9a1d57b559e'),(1400,143,'f35d50add7eee53afbeabd3c629f1ca97f895ea7czozMjoia3RCbG1KTVJBb2tURFJ3WHREM3B5SXNJNmlEZHp2WHQiOw==','2019-02-08 11:53:28','2019-02-08 11:53:28','5e624c99-a651-4835-ad78-c7483d9fb844'),(1401,143,'2885c7a3b1fa716985b3815e145b2c47a4ef52f1czozMjoiSVNZancxelJYQmR2bEpxM2MyV082bG5mYzVkMERYckgiOw==','2019-02-08 12:59:31','2019-02-08 12:59:31','abe018db-ae67-4675-8ebc-2a4a082d53cd'),(1402,143,'2bdefe520cff05100a456405fce7025ed08177d6czozMjoiQkNqYTRMZk1zSFBLeEhVSE8zRldWfjBtY3BHd0x4c0EiOw==','2019-02-08 14:42:37','2019-02-08 14:42:37','70640297-0b5b-43a6-92c1-29f64d9fdce3'),(1403,1,'acc84c3e7922bd30cbcf758257c3c72d852d74ffczozMjoidFkzVGg4T2xSekpJYWJUZHVzc1RScnVEc1B4dzdNSEkiOw==','2019-02-10 11:34:14','2019-02-10 11:34:14','e37c7531-d351-4017-9622-e52c046dc2b1'),(1404,1423,'455fa90980337d1f0540d8f713a6e4178eb9ab5fczozMjoiZHVWWkU3aGZUUFJXWjFrWW5nSFNQU2FIMWxNRWx2THQiOw==','2019-02-11 08:39:41','2019-02-11 08:39:41','ffcdf946-be48-4935-b985-4310e038ec41'),(1406,1,'5b5722b8d1d05e54baa32a376a4f9ab895443b66czozMjoiTzRnODdLUGRNTWwxcmdCYTlKS3QzeDc2cjdBTlU3STciOw==','2019-02-11 09:33:00','2019-02-11 09:33:00','602dbd0c-189d-4c09-a722-29cd88aa2a0f'),(1408,1423,'074a7eaedb0513f0db9363841938d9c1460301daczozMjoibnZpQXBMVTRhdElWM3c5dVFtWDJyfjVDWmxibmN0cn4iOw==','2019-02-11 09:49:45','2019-02-11 09:49:45','15dea4c6-8766-4af0-89ec-18549c5be299'),(1409,143,'abe3f6c0cdef6d5fc08f69b7d14a99c68c81cacfczozMjoiN1IxNHIyNHFxZURZanN6aGh1dEk0TXhUNncySzYwdXAiOw==','2019-02-11 09:53:31','2019-02-11 09:53:31','1c4b73b7-5a83-4cb9-98c8-5e02109c6610'),(1410,1,'ec8ca26b5fcb9064527eef684bd27adc9ca7f7a0czozMjoiZVBXdDdBdzJjNGZfTzIwbFpNQ1d1dlNDTkFwQjRIWmciOw==','2019-02-11 10:25:14','2019-02-11 10:25:14','2a5804bb-82d8-4413-a8b1-c5add08be082'),(1411,1,'2b01570551afcf210aeb41adf8854c148694f94bczozMjoiV1FKdTA4ZHluSjdjd3M3cmt1T3VWendsUXNuWjlEREUiOw==','2019-02-11 10:37:09','2019-02-11 10:37:09','bb473c58-77e5-471f-ad57-777b9a5865ba'),(1413,1423,'c110564ccd0cce208ddf616515178a1f83f0d37dczozMjoiYk9Wa1JXVENTeFRnNU1QWFNKU20zblFEYnZqTGh4enUiOw==','2019-02-11 11:04:00','2019-02-11 11:04:00','4a5eb661-b9e9-47f1-a7de-90fe52c7b7d2'),(1414,1423,'af7aaa2d3c4297ecbb25a90b34269f54cacee88cczozMjoiUE9HQzZTQ3RQRHp0MXpvdlA5d1l1Wnl1TURHTFdPcmoiOw==','2019-02-11 11:17:41','2019-02-11 11:17:41','2a2c7fd8-e5a4-4866-9c22-8124f57c157b'),(1415,1423,'a4e64057e37d6b5028d08a617e6de6cae7bbb85eczozMjoibGxEeHV3bjB0VV9vNmRPWFcyZDVJQllqcVk1T1dIUlYiOw==','2019-02-11 11:33:24','2019-02-11 11:33:24','91f34de6-0f01-40f8-a3d1-c1c383748a05'),(1416,143,'f66552b299de876c0722cd2e637332782503fc04czozMjoicDQzVDQxUjhPZlpiX3JTSUR1NVNyV0lMZzM0YkhURG0iOw==','2019-02-11 12:04:54','2019-02-11 12:04:54','73fa04de-d2d0-4c9c-98bb-fe26148a118c'),(1417,1,'d459e2b16d5c74a157150e3d0095d045c3a65627czozMjoiOTNKR0ptRDUxdFpCUGRZQW1uczhOYXRyR3VxYzVHSm4iOw==','2019-02-11 12:04:58','2019-02-11 12:04:58','e5a231af-3fda-4a0a-a736-ab28aae75dbb'),(1418,1438,'06f97c417dde9100dc70435159a15f9a064f6d29czozMjoiRTRsQUlmeGMyZHk1TDh3NWRydlVlX3ZvMEw1RGltSk0iOw==','2019-02-11 13:41:47','2019-02-11 13:41:47','4e212038-4b17-4b9f-a618-399761e4353e'),(1422,1423,'a77fef7801d190459ae24d8228e1b3bd44c1a1f4czozMjoicGhaVEd4MXcyREdPN243RElVOE12bnBSMFNQMEIzQzQiOw==','2019-02-11 13:54:51','2019-02-11 13:54:51','05b4d835-edba-4b85-8ed4-619eda014615'),(1424,1423,'b28ac87e6a042c3ac8d1474355546ea4b757f9cdczozMjoiSGZZbERIZFlSUTNrRE1SbHFvMm1VR2FMV35rWjdpa18iOw==','2019-02-11 14:06:43','2019-02-11 14:06:43','a07c1ad2-8079-4a70-b09b-6b640e06c867'),(1426,1423,'bd2e978e603beb02ae5fd48525b3f54c80187d68czozMjoiMldDM3ZFb2xyT2psVV92Y3pBZ3VKZlpJakd5OHo2UkciOw==','2019-02-11 14:07:53','2019-02-11 14:07:53','1f8a266c-4907-448d-ac23-3e491feed5a6'),(1428,1438,'af5ed7efc4ecb7685f285da26c37f129ddbdb8c5czozMjoiRUxxZUJiOVh4VmlNQ0dOSkdpfjYwWTQ3TUJRREc4cEciOw==','2019-02-12 08:50:02','2019-02-12 08:50:02','274a6185-c262-44d2-a6d6-590fcf7706f2'),(1429,1,'008a836926d758eb01ee0f0c91d7b5deb72daf6dczozMjoiZnNNV3NaRHkyYkFQT1kyVVpSU0dhVk5nc3BzUGVKY0MiOw==','2019-02-12 11:35:13','2019-02-12 11:35:13','03b5b2ed-2dbd-4024-89f0-cc02757e2f48'),(1430,1,'e79b1e6afae9729d8c379a341d4e142caae2c4e1czozMjoiV35JZ1pBT3EzYXVOYzFDcTJiMWt5R19KZTFEX0hzaGsiOw==','2019-02-12 13:29:15','2019-02-12 13:29:15','1adaf628-f650-40f0-b10c-d1d213fe9c4e'),(1432,1,'036d0c4d0998412bd12285271d82b3bbe8a9b32dczozMjoiNmNjQ3huSjRySHZreDhaWmVyYUx1ZUZLRWVSSkRCY3kiOw==','2019-02-14 08:55:13','2019-02-14 08:55:13','f7788bf5-4513-4fe1-8403-2ef330bf2766'),(1434,1,'8aa69ff8b734f790d429015ff147f0843c2727a6czozMjoieWFia0pRMk9BVlhsY1JiMm1SbHhSYnp+bHZGTHhlVkciOw==','2019-02-14 08:56:35','2019-02-14 08:56:35','195b4199-5526-451f-ba0b-d8c69536ee8a'),(1436,1,'4dc142fb1f644610abdf4f8323866c8fffbefc22czozMjoiYU1VNW5yZlM1YkNCblBNMU1rcDdBc3ZyQTdJTFZ4MmEiOw==','2019-02-14 09:35:52','2019-02-14 09:35:52','4c5aab01-38ca-4816-9cfe-ba5af7c12451'),(1438,1,'e8b7b5f7825aa87075935e2a968c3e8aa815da7cczozMjoieUl6X085REd+SzBXOTNOODFMY01SZEhmUFVaWDRhWXoiOw==','2019-02-14 12:36:00','2019-02-14 12:36:00','f419eeb1-f7ff-42ae-8436-b8dc8ff8d3cd'),(1439,1,'221d7d6266073c7f391e41b9681184c658cae4baczozMjoiYXJPVmhEYTRKeXNIVlZ4N0ZSZWdidEFueFJBU0psZngiOw==','2019-02-14 12:36:19','2019-02-14 12:36:19','dd82b0a8-60e5-48d2-bcd4-f371396e5f1b'),(1441,143,'3b04341d1e14a03a0cfb8d4852e9839fb6bbd331czozMjoifkNUWUVQOHZpNTZneENCNDM5WndrS05+VWlQVHNCQXEiOw==','2019-02-14 12:37:28','2019-02-14 12:37:28','df01582e-d238-499a-916b-fd9de2717c58'),(1443,143,'6f8a65c5f79fcdbad974ea4c33c202824f128d4cczozMjoiMlp5RlRMX3EzdzZ0OFZuV3RXYUxTMklHWVRBTUt5U0MiOw==','2019-02-14 12:44:23','2019-02-14 12:44:23','cfd5fafe-9300-4ebf-90a7-b4a3c24fd7dd'),(1444,1,'e5019dd1c43cbf456acd3c5a4d3448755a8ff72cczozMjoiYkdEM1gwUk9zcW1oRnE1OFVDQlVHZFJvU1NUbHU4RlgiOw==','2019-02-14 12:59:51','2019-02-14 12:59:51','29a6342f-f647-44ec-80be-a92bdb4d2b99'),(1447,143,'93da2bf4f4baf9c80480e046e5620cea64b0628eczozMjoiaUJUT3pIVjRLdDB0ZFFZZm8yeWpxdUVvWn53NV96cU0iOw==','2019-02-14 15:01:00','2019-02-14 15:01:00','01e3f4bd-0c20-4f46-bba5-e52d46936de6'),(1448,143,'38bbafe9d35f73090516875f48eaec1076c535f0czozMjoidjdqZHlldzZlQVU5bkFaTTVLbEhmM2dSbmVSMFJLSEsiOw==','2019-02-14 15:01:49','2019-02-14 15:01:49','359eadcc-3784-4c7d-b2eb-c0af31914a9e'),(1449,1,'723923ecd4547942b360a65239a66bcc174df934czozMjoiWTIwdmR0fjhhbURVNGhRZ3J3WWZDQ29EfjBvZHM1b0EiOw==','2019-02-15 08:34:18','2019-02-15 08:34:18','f8cf9642-ec39-4285-8ae3-56e94844ebe5'),(1450,1,'58fc4445aedd45f9c51d41f7dadb10f22fda79c3czozMjoiNVp2eTFvdDAxamFJMkJtX2UwazBxS344UDBobUZMYzMiOw==','2019-02-15 08:34:48','2019-02-15 08:34:48','cd0fbcf8-2853-4239-8cd5-1082a2b8bd56'),(1452,1,'2c3d82fd36a021920624cc1ee9d46b21c32e2d3aczozMjoibWoxbmE1S1c3NkNjY0llU3pjOWY3c19DemZ2ZXk2Wn4iOw==','2019-02-15 08:36:22','2019-02-15 08:36:22','950dec7a-cf2d-44d9-8030-6d3f2e2214a0'),(1454,143,'11b76471f848332fabc35e533a441f9c41f19b78czozMjoiNURRa0R1cWpkenZDMWl+VlhXQ1BNMDM1a0ZUTnRvc18iOw==','2019-02-15 09:59:25','2019-02-15 09:59:25','22a50b19-34a3-4abb-b424-85f78b42d0bb'),(1455,1423,'efb896efb753ca98e29b049a4816772debd20db6czozMjoiNFk5TXIwX0lKUnR0TnN3XzZKSFdueEk1NDhBMjRuMk8iOw==','2019-02-15 10:07:51','2019-02-15 10:07:51','ccff66e6-172d-4233-90bc-bb34bd96b13d'),(1456,1,'d416bfcf241d78d31d2b44fff400060f06fab902czozMjoiQ0M5a05aRDE4QnRZM21yfjRialZVMmwxd1J2VXYzd1kiOw==','2019-02-15 10:45:19','2019-02-15 10:45:19','c35aa817-aa4c-4744-9ff7-6e94bf1d32a6'),(1458,143,'37eae7c695dc1fbb0027806b3830aeb1fa4eccfcczozMjoiTVFLZ2g3cTlUYnZIZGtSOXRGQ1ZaY3VtV3VfMnhGcnoiOw==','2019-02-15 11:04:11','2019-02-15 11:04:11','8a9acfb2-f2d3-4874-af47-8043b6203ea8'),(1459,1438,'ff371ed05ffc5d660edc1ad1f50f3ab775c88942czozMjoiVDRpaDlZcEMzX2FVRmJiNVNsZ2VMOTRqak1lT1oxTXYiOw==','2019-02-15 11:18:37','2019-02-15 11:18:37','45dfd32c-a5a6-470e-8fd3-b85bb3ff008e'),(1464,1423,'5106253346fb7dcbe6e6e28eb6239175746ef66dczozMjoiTzFobzJMSE5rN0h6MkZMVlUwMG9WSHRhMGk4flZBejUiOw==','2019-02-15 13:57:58','2019-02-15 13:57:58','1a73fd77-5dee-4c5b-af62-f5f299c0cb04'),(1465,1,'ef69a52d50f31e5203dc47b35576b4472137db86czozMjoiRE9WS25lQ1R5T0p0UVcxNVhOdzAwM2FhWn5Zb3FLYlMiOw==','2019-02-15 13:59:52','2019-02-15 13:59:52','e39c2843-4842-412a-81e7-bcd8ffa6d4a7'),(1466,1,'b3f6f1701057f53e2e29f4844d8d34e96ffa46c0czozMjoiYkhtank5dF9+NFJwMll2S344RmREUDRkNmZJNFJ+OFIiOw==','2019-02-15 14:00:24','2019-02-15 14:00:24','af708590-add7-4b3d-99da-5f9992b23a5b'),(1468,1,'a19a71b57f87ffb4fdffb24c482da59cb4242be8czozMjoiT2FTT1dCdElna29QSUtSVXVvWTcwTzVnTnIwUVFkbEUiOw==','2019-02-15 14:01:23','2019-02-15 14:01:23','d61bf234-85f5-43c0-9f46-71aa3bc8a911'),(1471,143,'24fe8f300525d5da8b5406768ffca01aed1ad16dczozMjoiX2V6V3ExNHNWZEw1eVJzME1TT3JvV2N2Vk1xamR0dWkiOw==','2019-02-15 14:27:16','2019-02-15 14:27:16','8ea5dd91-4f25-4ffb-8904-5152e187ff8f'),(1472,1438,'025b1f871e4c4c1d27e543b1952504539a9ff96bczozMjoiWWZucnJYUjI3UXl+enN3fjlGTEFPWlRmMEl6eXNYREoiOw==','2019-02-15 14:47:24','2019-02-15 14:47:24','94861d08-a356-4719-ba4d-74d6ee46ccb2'),(1474,1438,'70f7ef6c1a3cff5978758a2488f84c99b94f548cczozMjoia2VXcTVvVmR1WndoNmhXcWlMcVVLSkt3cXRtbGtRakYiOw==','2019-02-18 10:11:17','2019-02-18 10:11:17','a4cf1891-504e-48ae-a6d4-5782d153030f'),(1480,1438,'dbd0b64929810f2740274a72135a406cb0be773eczozMjoiVWw1OVBmQmU4c341Z1RTNmpIaEkxY3ZySTdaX181b1oiOw==','2019-02-18 13:35:03','2019-02-18 13:35:03','a5a4ad05-21ab-4d40-92bc-ce3d953fd3b9'),(1484,1438,'e5083741efed086801663025b19429cb673f792bczozMjoiUVdNcjlpZGU5VWdNWmQ2SnZybUlMdzFKalVTaGN4ZFkiOw==','2019-02-19 08:44:44','2019-02-19 08:44:44','823ccd25-8258-4c12-b3fa-9db8ccbe8650'),(1489,1438,'ee17e5974550f1146fb18cac08910ab59984496dczozMjoibmdIUmJoUVpXakdQdUlEb0dZbHdBZ3BabGNQZ3NyWlEiOw==','2019-02-19 10:51:09','2019-02-19 10:51:09','39accfac-0042-435f-8bd1-d324bb18369a'),(1491,1423,'bd5703d927fc4dcb2d65b82b1483ee93a12db94aczozMjoiOVNCaTViZTlNNzRQNkJjfkQ0V25qQV9IYzJKNkNnRGYiOw==','2019-02-19 10:51:25','2019-02-19 10:51:25','b9f7d6e6-5e34-4ec2-abfe-e64228230df1'),(1492,1423,'b3f8cd3003fbcbd51005ffa9f03be575042c57b0czozMjoiUX43VTJzT1Z2eVZrRjJzZHJkRFRlNGgzYlFIRk9OY0QiOw==','2019-02-19 11:17:27','2019-02-19 11:17:27','27381441-c684-4c0c-b637-efabb049e6e9'),(1494,1423,'c5c04eb12aef18e7a47d027f3e19f4e547261451czozMjoiVFhkalhnTkx2dXhHMFhSQWtwcDZuNHhjalNzaU5UR2ciOw==','2019-02-19 11:20:21','2019-02-19 11:20:21','246d98bb-8f92-4d1f-89a3-3ec79708ba3e'),(1496,1423,'cff65fbdfe159026d30970d8dc7ef54540381979czozMjoiOE4wNFRsSGFfNTM0fmlPWWF1UXFfbk5xVmtsejdlWXciOw==','2019-02-19 11:24:29','2019-02-19 11:24:29','d51836b6-c30f-4883-b779-8a673d715176'),(1497,1423,'974101ebdc9364e2410a6c4c3cd7b2fd73e9b45aczozMjoiS1pyc19ZTkdYN3d0dkNJOHVzSn42dE9yZzFtQjI0Z2ciOw==','2019-02-19 11:28:28','2019-02-19 11:28:28','c69fd5ca-38c8-402d-8f89-b947ca34ffa0'),(1499,1423,'5cededd29ab26eaa24c964d57521e65c3680fc62czozMjoiMk1WcHB6VnR5cGxFZTNzRjNtdjhRSFR5WWNIMmt5SzkiOw==','2019-02-19 11:31:05','2019-02-19 11:31:05','5ea0518c-2605-453f-a38c-ca9f6bffb811'),(1502,1423,'63cd7b69b9a2df69a19629eaf149d7cc7d1e9100czozMjoiSkRCNW54S1dyOE5qOTBmOGpCVGFEY1UyamdMfkdyd1QiOw==','2019-02-19 11:40:49','2019-02-19 11:40:49','80c0b57f-a849-4578-b23b-5954cfdc4778'),(1504,1423,'597ec3750fca4e56a614fba29612d8c879ea5f0cczozMjoiTlI4M3hCOXZuQ2Ntd3pJX041b3JhdU9ubmpteWhfSGMiOw==','2019-02-19 11:44:22','2019-02-19 11:44:22','c695e408-33d0-4dd6-809e-478b3f12f999'),(1507,1438,'226ab603c6860ef254b877c0f1267862bd2d9645czozMjoiZH5USlNEV3dzbk1DV3UwUHlvbDFpWHBFWFk2Tl9TNGMiOw==','2019-02-20 10:54:46','2019-02-20 10:54:46','8dca9518-12cd-42a0-965d-9f5932a5b31c'),(1508,1438,'a8f3d234da13f0a31d8ad6f58195b2a845134f20czozMjoiUzZ2eTJkTTQ5fmhxZmRvQ1NBNVBzcDNEeFFqYlMxZ2giOw==','2019-02-20 14:54:45','2019-02-20 14:54:45','4a83d61b-6b11-4529-9f1f-f935aa31cae9'),(1509,1438,'9f6d9ad6f4b8c5ae14001e6417580211c009c3ceczozMjoibUtlMFMzTHpzWDI0TXMybVlkVURIVHRXa1ZwOXdqd0QiOw==','2019-02-21 08:37:54','2019-02-21 08:37:54','635e1cb6-7ed6-491d-bfa4-3c5b294de3e5'),(1510,1438,'2d006740590209b90f3d9cb65df0d9f40714ce29czozMjoieWdzUG5ybXRtS3BIZ0hKNmlFR3ZKQmNWeGdfU1RUeHgiOw==','2019-02-21 10:32:54','2019-02-21 10:32:54','af6ab84c-a1ed-401c-b6b3-9f1da02973b2'),(1515,143,'c8b2a2c04d8801690e26f506086787374bbb1be4czozMjoiY1ZodzJ6UUlaMGVQVEg1RktMd3FSZ2NjbnZVb05SV0EiOw==','2019-02-21 14:36:00','2019-02-21 14:36:00','4fc1c15a-218f-4b81-91d4-b407a0f914f4'),(1516,143,'7d4dca09457b9f365b30f2266536369dee14dca5czozMjoiYkd1S2FXeXdIcU5ud2ZSWTdQdDdkdWp2ZkNyMTd3Mn4iOw==','2019-04-25 13:25:54','2019-04-25 13:25:54','5cfafc8e-0274-4f6c-b64a-85d0085bccb3');
/*!40000 ALTER TABLE `craft_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_shunnedmessages`
--

DROP TABLE IF EXISTS `craft_shunnedmessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_shunnedmessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expiryDate` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_shunnedmessages_userId_message_unq_idx` (`userId`,`message`),
  CONSTRAINT `craft_shunnedmessages_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_shunnedmessages`
--

LOCK TABLES `craft_shunnedmessages` WRITE;
/*!40000 ALTER TABLE `craft_shunnedmessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_shunnedmessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_sproutreports_datasources`
--

DROP TABLE IF EXISTS `craft_sproutreports_datasources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_sproutreports_datasources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataSourceId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `options` text COLLATE utf8_unicode_ci,
  `allowNew` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_sproutreports_datasources`
--

LOCK TABLES `craft_sproutreports_datasources` WRITE;
/*!40000 ALTER TABLE `craft_sproutreports_datasources` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_sproutreports_datasources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_sproutreports_reportgroups`
--

DROP TABLE IF EXISTS `craft_sproutreports_reportgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_sproutreports_reportgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_sproutreports_reportgroups_name_unq_idx` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_sproutreports_reportgroups`
--

LOCK TABLES `craft_sproutreports_reportgroups` WRITE;
/*!40000 ALTER TABLE `craft_sproutreports_reportgroups` DISABLE KEYS */;
INSERT INTO `craft_sproutreports_reportgroups` VALUES (1,'Sprout Reports','2018-05-02 09:18:48','2018-05-02 09:18:48','169c0a10-a6bd-45e3-ac7d-553e5492c11d');
/*!40000 ALTER TABLE `craft_sproutreports_reportgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_sproutreports_reports`
--

DROP TABLE IF EXISTS `craft_sproutreports_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_sproutreports_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(10) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nameFormat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `allowHtml` tinyint(1) unsigned DEFAULT '0',
  `dataSourceId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `options` text COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_sproutreports_reports_name_handle_unq_idx` (`name`,`handle`),
  KEY `craft_sproutreports_reports_dataSourceId_idx` (`dataSourceId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_sproutreports_reports`
--

LOCK TABLES `craft_sproutreports_reports` WRITE;
/*!40000 ALTER TABLE `craft_sproutreports_reports` DISABLE KEYS */;
INSERT INTO `craft_sproutreports_reports` VALUES (1,1,'Users and User Groups','usersAndUserGroups','Create a list of all users and their user groups.',NULL,0,'sproutreports.users','{\"userGroups\":[\"4\"],\"displayUserGroupColumns\":\"1\"}',1,'2018-05-02 09:18:48','2018-05-10 13:16:18','756468b3-c90c-43ed-8433-f60bc17701f4');
/*!40000 ALTER TABLE `craft_sproutreports_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_structureelements`
--

DROP TABLE IF EXISTS `craft_structureelements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_structureelements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `structureId` int(11) NOT NULL,
  `elementId` int(11) DEFAULT NULL,
  `root` int(11) unsigned DEFAULT NULL,
  `lft` int(11) unsigned NOT NULL,
  `rgt` int(11) unsigned NOT NULL,
  `level` smallint(6) unsigned NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_structureelements_structureId_elementId_unq_idx` (`structureId`,`elementId`),
  KEY `craft_structureelements_root_idx` (`root`),
  KEY `craft_structureelements_lft_idx` (`lft`),
  KEY `craft_structureelements_rgt_idx` (`rgt`),
  KEY `craft_structureelements_level_idx` (`level`),
  KEY `craft_structureelements_elementId_fk` (`elementId`),
  CONSTRAINT `craft_structureelements_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_structureelements_structureId_fk` FOREIGN KEY (`structureId`) REFERENCES `craft_structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_structureelements`
--

LOCK TABLES `craft_structureelements` WRITE;
/*!40000 ALTER TABLE `craft_structureelements` DISABLE KEYS */;
INSERT INTO `craft_structureelements` VALUES (1,1,NULL,1,1,4,0,'2017-10-23 14:49:48','2017-10-23 14:49:48','e8485dc4-058d-40ed-b468-e62f2e0dcc1a'),(48,2,NULL,48,1,2,0,'2018-11-26 17:52:08','2018-11-26 17:52:08','b80f6bf7-f337-4fad-8d8d-9cdc8409b22d'),(67,1,1762,1,2,3,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','8a63f84f-2d13-4a48-a2c9-6148b37baa43'),(72,3,NULL,72,1,2,0,'2019-01-24 11:47:39','2019-01-24 11:47:39','84bb6756-e974-4fa3-b3ab-ffedc3360d53');
/*!40000 ALTER TABLE `craft_structureelements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_structures`
--

DROP TABLE IF EXISTS `craft_structures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_structures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maxLevels` smallint(6) unsigned DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_structures`
--

LOCK TABLES `craft_structures` WRITE;
/*!40000 ALTER TABLE `craft_structures` DISABLE KEYS */;
INSERT INTO `craft_structures` VALUES (1,1,'2017-10-23 14:49:32','2018-10-04 15:34:00','cddeab59-d20c-4b6e-80e2-3912183a9657'),(2,NULL,'2018-11-26 17:51:54','2018-12-11 07:56:08','bc778126-df63-4aaa-98b1-a3cbd027192f'),(3,1,'2019-01-24 11:47:39','2019-01-24 11:47:39','55c15d69-5336-44fc-8720-699b170f1847');
/*!40000 ALTER TABLE `craft_structures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_supertableblocks`
--

DROP TABLE IF EXISTS `craft_supertableblocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_supertableblocks` (
  `id` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `fieldId` int(11) NOT NULL,
  `typeId` int(11) DEFAULT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `ownerLocale` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_supertableblocks_ownerId_idx` (`ownerId`),
  KEY `craft_supertableblocks_fieldId_idx` (`fieldId`),
  KEY `craft_supertableblocks_typeId_idx` (`typeId`),
  KEY `craft_supertableblocks_sortOrder_idx` (`sortOrder`),
  KEY `craft_supertableblocks_ownerLocale_fk` (`ownerLocale`),
  CONSTRAINT `craft_supertableblocks_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_supertableblocks_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_supertableblocks_ownerId_fk` FOREIGN KEY (`ownerId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_supertableblocks_ownerLocale_fk` FOREIGN KEY (`ownerLocale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `craft_supertableblocks_typeId_fk` FOREIGN KEY (`typeId`) REFERENCES `craft_supertableblocktypes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_supertableblocks`
--

LOCK TABLES `craft_supertableblocks` WRITE;
/*!40000 ALTER TABLE `craft_supertableblocks` DISABLE KEYS */;
INSERT INTO `craft_supertableblocks` VALUES (2528,1079,167,2,1,NULL,'2019-01-21 16:04:37','2019-02-21 14:36:13','5bd3631e-ece6-4cfa-a2ad-5bbae62e3ee6'),(2529,1079,167,2,2,NULL,'2019-01-21 16:04:37','2019-02-21 14:36:13','71f0b2f6-f3f8-44bb-b488-c16d09a35019'),(2530,1079,167,2,3,NULL,'2019-01-21 16:04:37','2019-02-21 14:36:13','73408af8-1147-4df6-9f46-9d76ec097a34'),(2531,1079,167,2,4,NULL,'2019-01-21 16:04:37','2019-02-21 14:36:13','0d4b6eff-8f0e-4561-aa79-fdd2706b7217'),(3169,1079,167,2,5,NULL,'2019-01-24 14:15:25','2019-02-21 14:36:13','bb908e3a-9dc2-45d5-bdf5-c767d71793b1'),(3183,1079,167,2,6,NULL,'2019-01-25 11:43:18','2019-02-21 14:36:13','67fd8871-3ba4-4a48-845a-d1a5d884d5e5');
/*!40000 ALTER TABLE `craft_supertableblocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_supertableblocktypes`
--

DROP TABLE IF EXISTS `craft_supertableblocktypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_supertableblocktypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldId` int(11) NOT NULL,
  `fieldLayoutId` int(11) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_supertableblocktypes_fieldId_fk` (`fieldId`),
  KEY `craft_supertableblocktypes_fieldLayoutId_fk` (`fieldLayoutId`),
  CONSTRAINT `craft_supertableblocktypes_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `craft_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_supertableblocktypes_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_supertableblocktypes`
--

LOCK TABLES `craft_supertableblocktypes` WRITE;
/*!40000 ALTER TABLE `craft_supertableblocktypes` DISABLE KEYS */;
INSERT INTO `craft_supertableblocktypes` VALUES (1,148,221,'2018-11-27 16:59:46','2018-11-27 16:59:46','3c5cbb51-70a3-4a2d-a11e-113ad119bdcb'),(2,167,296,'2018-12-06 17:37:16','2019-04-25 13:26:07','922f36f2-d6ec-4530-be00-d8784974eb73'),(3,177,280,'2019-01-18 16:57:15','2019-02-08 11:24:44','e1217b3a-3108-405d-9e31-243f335635c8'),(4,199,297,'2019-04-25 13:26:07','2019-04-25 13:26:07','44e21cea-14c2-44e9-9bbf-8c104a4c7128');
/*!40000 ALTER TABLE `craft_supertableblocktypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_supertablecontent_columnlayout`
--

DROP TABLE IF EXISTS `craft_supertablecontent_columnlayout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_supertablecontent_columnlayout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_fieldType` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_fieldLabel` text COLLATE utf8_unicode_ci,
  `field_fieldManagerOnly` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_fieldCustomKey` text COLLATE utf8_unicode_ci,
  `field_fieldCustomType` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'text',
  `field_fieldCustomOptions` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_supertablecontent_columnlayout_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_supertablecontent_columnlayout_locale_fk` (`locale`),
  CONSTRAINT `craft_supertablecontent_columnlayout_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_supertablecontent_columnlayout_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_supertablecontent_columnlayout`
--

LOCK TABLES `craft_supertablecontent_columnlayout` WRITE;
/*!40000 ALTER TABLE `craft_supertablecontent_columnlayout` DISABLE KEYS */;
INSERT INTO `craft_supertablecontent_columnlayout` VALUES (36,2528,'en_gb','unitType','',0,NULL,'text',NULL,'2019-01-21 16:04:37','2019-02-21 14:36:13','837bd5d1-2625-4226-9fd9-58a9e49a90d9'),(37,2529,'en_gb','unitValue','',0,NULL,'text',NULL,'2019-01-21 16:04:37','2019-02-21 14:36:13','81c3ce13-edbb-4dc8-9513-5400d0073325'),(38,2530,'en_gb','resultStatus','',0,NULL,'text',NULL,'2019-01-21 16:04:37','2019-02-21 14:36:13','707b8bab-b251-4907-87f7-ff69c9f5f3cf'),(39,2531,'en_gb','resultEndorsedDate','',0,NULL,'text',NULL,'2019-01-21 16:04:37','2019-02-21 14:36:13','81fdb820-d815-47c8-b74b-58c65e6af558'),(40,3169,'en_gb','resultLocation','',0,NULL,'text',NULL,'2019-01-24 14:15:25','2019-02-21 14:36:13','9fcd36ee-099e-47c2-a43b-f32c3737d728'),(42,3183,'en_gb','resultExpiryDate','',0,NULL,'text',NULL,'2019-01-25 11:43:18','2019-02-21 14:36:13','1f37a3fc-c045-42e9-b6d5-51ca0e596270');
/*!40000 ALTER TABLE `craft_supertablecontent_columnlayout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_supertablecontent_resultcomments`
--

DROP TABLE IF EXISTS `craft_supertablecontent_resultcomments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_supertablecontent_resultcomments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_comment` text COLLATE utf8_unicode_ci,
  `field_date` datetime DEFAULT NULL,
  `field_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_supertablecontent_resultcomments_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_supertablecontent_resultcomments_locale_idx` (`locale`),
  CONSTRAINT `craft_supertablecontent_resultcomments_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_supertablecontent_resultcomments_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=767 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_supertablecontent_resultcomments`
--

LOCK TABLES `craft_supertablecontent_resultcomments` WRITE;
/*!40000 ALTER TABLE `craft_supertablecontent_resultcomments` DISABLE KEYS */;
INSERT INTO `craft_supertablecontent_resultcomments` VALUES (11,2541,'en_gb','fffff','2019-01-21 19:11:24',0,'2019-01-21 19:11:24','2019-01-21 19:11:24','8cbced20-1023-45f2-8f13-75f09b1f866f'),(12,2542,'en_gb','fffff','2019-01-21 19:11:24',0,'2019-01-21 19:11:24','2019-01-21 19:11:24','cdd2c5c3-a226-4541-af5d-fa5da24242f5'),(13,2543,'en_gb','ddddd','2019-01-21 19:11:29',0,'2019-01-21 19:11:29','2019-01-21 19:11:29','7d0589d1-86cb-4500-8169-87be770fa75e'),(14,2544,'en_gb','ddddd','2019-01-21 19:11:30',0,'2019-01-21 19:11:30','2019-01-21 19:11:30','fbf04c91-cdf7-4966-ad6b-a04f9286977b'),(16,2546,'en_gb','sssss','2019-01-21 19:15:01',0,'2019-01-21 19:15:01','2019-01-21 19:15:01','f98552c0-daf6-4b84-bfe1-8efb27bbd349'),(17,2547,'en_gb','sssss','2019-01-21 19:15:01',0,'2019-01-21 19:15:01','2019-01-21 19:15:01','0bf84c26-2421-4a6a-bf23-80b62a8eb275'),(18,2548,'en_gb','sssss','2019-01-21 19:18:23',0,'2019-01-21 19:18:23','2019-01-21 19:18:23','bd0401c1-9a70-4074-bc61-b54fd9f7bbd0'),(19,2549,'en_gb','sssss','2019-01-21 19:18:23',0,'2019-01-21 19:18:23','2019-01-21 19:18:23','54dc48e6-a181-4e56-a395-bc937c2a7111'),(594,3124,'en_gb','xcvxvczxcvzxcv','2019-01-21 19:38:11',0,'2019-01-21 19:38:11','2019-01-21 19:38:11','b5745659-5faa-4fa1-bd0f-3157b78b2ec4'),(595,3125,'en_gb','xsxxxssdafasdfa','2019-01-21 19:38:17',0,'2019-01-21 19:38:17','2019-01-21 19:38:17','4074636a-90a0-477a-aa13-60f126e2584b'),(596,3126,'en_gb','ccccc','2019-01-21 19:40:53',0,'2019-01-21 19:40:53','2019-01-21 19:40:53','450c1811-e150-43a1-8d73-348bf99451f0'),(597,3127,'en_gb','cxcxc','2019-01-21 19:41:04',0,'2019-01-21 19:41:04','2019-01-21 19:41:04','8580e08c-9850-433e-82ee-b0342a88b057');
/*!40000 ALTER TABLE `craft_supertablecontent_resultcomments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_supertablecontent_resultcustom`
--

DROP TABLE IF EXISTS `craft_supertablecontent_resultcustom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_supertablecontent_resultcustom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementId` int(11) NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `field_customKey` text COLLATE utf8_unicode_ci,
  `field_customValue` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_supertablecontent_resultcustom_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_supertablecontent_resultcustom_locale_fk` (`locale`),
  CONSTRAINT `craft_supertablecontent_resultcustom_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_supertablecontent_resultcustom_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_supertablecontent_resultcustom`
--

LOCK TABLES `craft_supertablecontent_resultcustom` WRITE;
/*!40000 ALTER TABLE `craft_supertablecontent_resultcustom` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_supertablecontent_resultcustom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_systemsettings`
--

DROP TABLE IF EXISTS `craft_systemsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_systemsettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_systemsettings_category_unq_idx` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_systemsettings`
--

LOCK TABLES `craft_systemsettings` WRITE;
/*!40000 ALTER TABLE `craft_systemsettings` DISABLE KEYS */;
INSERT INTO `craft_systemsettings` VALUES (1,'email','{\"template\":\"\",\"protocol\":\"php\",\"emailAddress\":\"jason@thisistraffic.co.uk\",\"senderName\":\"Lantra CPD\"}','2017-10-23 13:26:43','2019-01-16 10:35:52','674fcaa0-de04-4e9a-8f71-d4d9162bf84c'),(2,'users','{\"requireEmailVerification\":0,\"allowPublicRegistration\":1,\"defaultGroup\":\"5\"}','2018-04-29 12:56:30','2018-05-31 12:57:11','a4832c83-8bf5-4f96-a578-d926632416c5');
/*!40000 ALTER TABLE `craft_systemsettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_taggroups`
--

DROP TABLE IF EXISTS `craft_taggroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_taggroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fieldLayoutId` int(10) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_taggroups_name_unq_idx` (`name`),
  UNIQUE KEY `craft_taggroups_handle_unq_idx` (`handle`),
  KEY `craft_taggroups_fieldLayoutId_fk` (`fieldLayoutId`),
  CONSTRAINT `craft_taggroups_fieldLayoutId_fk` FOREIGN KEY (`fieldLayoutId`) REFERENCES `craft_fieldlayouts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_taggroups`
--

LOCK TABLES `craft_taggroups` WRITE;
/*!40000 ALTER TABLE `craft_taggroups` DISABLE KEYS */;
INSERT INTO `craft_taggroups` VALUES (1,'Default','default',1,'2017-10-23 13:26:43','2017-10-23 13:26:43','d507d3b5-cbbc-4860-b80c-49906ef43a9e');
/*!40000 ALTER TABLE `craft_taggroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_tags`
--

DROP TABLE IF EXISTS `craft_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_tags` (
  `id` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_tags_groupId_fk` (`groupId`),
  CONSTRAINT `craft_tags_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_taggroups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_tags_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_tags`
--

LOCK TABLES `craft_tags` WRITE;
/*!40000 ALTER TABLE `craft_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_tasks`
--

DROP TABLE IF EXISTS `craft_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `root` int(11) unsigned DEFAULT NULL,
  `lft` int(11) unsigned NOT NULL,
  `rgt` int(11) unsigned NOT NULL,
  `level` smallint(6) unsigned NOT NULL,
  `currentStep` int(11) unsigned DEFAULT NULL,
  `totalSteps` int(11) unsigned DEFAULT NULL,
  `status` enum('pending','error','running') COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `settings` mediumtext COLLATE utf8_unicode_ci,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_tasks_root_idx` (`root`),
  KEY `craft_tasks_lft_idx` (`lft`),
  KEY `craft_tasks_rgt_idx` (`rgt`),
  KEY `craft_tasks_level_idx` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=763 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_tasks`
--

LOCK TABLES `craft_tasks` WRITE;
/*!40000 ALTER TABLE `craft_tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_templatecachecriteria`
--

DROP TABLE IF EXISTS `craft_templatecachecriteria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_templatecachecriteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cacheId` int(11) NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `criteria` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `craft_templatecachecriteria_cacheId_fk` (`cacheId`),
  KEY `craft_templatecachecriteria_type_idx` (`type`),
  CONSTRAINT `craft_templatecachecriteria_cacheId_fk` FOREIGN KEY (`cacheId`) REFERENCES `craft_templatecaches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_templatecachecriteria`
--

LOCK TABLES `craft_templatecachecriteria` WRITE;
/*!40000 ALTER TABLE `craft_templatecachecriteria` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_templatecachecriteria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_templatecacheelements`
--

DROP TABLE IF EXISTS `craft_templatecacheelements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_templatecacheelements` (
  `cacheId` int(11) NOT NULL,
  `elementId` int(11) NOT NULL,
  KEY `craft_templatecacheelements_cacheId_fk` (`cacheId`),
  KEY `craft_templatecacheelements_elementId_fk` (`elementId`),
  CONSTRAINT `craft_templatecacheelements_cacheId_fk` FOREIGN KEY (`cacheId`) REFERENCES `craft_templatecaches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_templatecacheelements_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_templatecacheelements`
--

LOCK TABLES `craft_templatecacheelements` WRITE;
/*!40000 ALTER TABLE `craft_templatecacheelements` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_templatecacheelements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_templatecaches`
--

DROP TABLE IF EXISTS `craft_templatecaches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_templatecaches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cacheKey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `locale` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiryDate` datetime NOT NULL,
  `body` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `craft_templatecaches_locale_fk` (`locale`),
  KEY `craft_templatecaches_cacheKey_locale_expiryDate_idx` (`cacheKey`,`locale`,`expiryDate`),
  KEY `craft_templatecaches_cacheKey_locale_expiryDate_path_idx` (`cacheKey`,`locale`,`expiryDate`,`path`),
  CONSTRAINT `craft_templatecaches_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_templatecaches`
--

LOCK TABLES `craft_templatecaches` WRITE;
/*!40000 ALTER TABLE `craft_templatecaches` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_templatecaches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_tokens`
--

DROP TABLE IF EXISTS `craft_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `route` text COLLATE utf8_unicode_ci,
  `usageLimit` tinyint(3) unsigned DEFAULT NULL,
  `usageCount` tinyint(3) unsigned DEFAULT NULL,
  `expiryDate` datetime NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_tokens_token_unq_idx` (`token`),
  KEY `craft_tokens_expiryDate_idx` (`expiryDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_tokens`
--

LOCK TABLES `craft_tokens` WRITE;
/*!40000 ALTER TABLE `craft_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_usergroups`
--

DROP TABLE IF EXISTS `craft_usergroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_usergroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_usergroups_name_unq_idx` (`name`),
  UNIQUE KEY `craft_usergroups_handle_unq_idx` (`handle`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_usergroups`
--

LOCK TABLES `craft_usergroups` WRITE;
/*!40000 ALTER TABLE `craft_usergroups` DISABLE KEYS */;
INSERT INTO `craft_usergroups` VALUES (1,'Scheme Managers','schemeManagers','2017-10-23 13:50:22','2019-01-17 13:52:20','2a75e796-1574-407e-8869-545d14d6d126'),(2,'Company Managers','companyManagers','2017-10-23 13:50:37','2018-12-12 00:42:30','0ff8d9a2-50f8-473e-93c9-5eb5d41750f6'),(3,'Team Managers','teamManagers','2017-10-23 13:50:51','2018-08-24 18:45:49','7765376d-5d95-4bb2-ad49-e9dbcd7acf4a'),(4,'Users','users','2017-10-23 15:06:23','2018-04-27 19:33:37','ab9197b3-7032-4015-ba4e-4939118952dd'),(5,'Individuals','individuals','2018-05-31 12:56:44','2018-05-31 12:56:44','32ed3edf-a621-4c98-8c9f-785e45ed329f');
/*!40000 ALTER TABLE `craft_usergroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_usergroups_users`
--

DROP TABLE IF EXISTS `craft_usergroups_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_usergroups_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_usergroups_users_groupId_userId_unq_idx` (`groupId`,`userId`),
  KEY `craft_usergroups_users_userId_fk` (`userId`),
  CONSTRAINT `craft_usergroups_users_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_usergroups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_usergroups_users_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1165 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_usergroups_users`
--

LOCK TABLES `craft_usergroups_users` WRITE;
/*!40000 ALTER TABLE `craft_usergroups_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_usergroups_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_userpermissions`
--

DROP TABLE IF EXISTS `craft_userpermissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_userpermissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_userpermissions_name_unq_idx` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_userpermissions`
--

LOCK TABLES `craft_userpermissions` WRITE;
/*!40000 ALTER TABLE `craft_userpermissions` DISABLE KEYS */;
INSERT INTO `craft_userpermissions` VALUES (1,'createentries:8','2017-10-24 11:28:19','2017-10-24 11:28:19','522df09d-ffe1-4b4e-a917-a808213b500b'),(2,'editentries:8','2017-10-24 11:28:19','2017-10-24 11:28:19','922429ee-823f-4b3c-87f5-f22769a0e2dc'),(3,'accesscp','2017-10-24 11:37:26','2017-10-24 11:37:26','834c5901-4348-49a6-92c8-7d03e4d09e0e'),(4,'publishentries:8','2017-10-24 11:52:27','2017-10-24 11:52:27','6d7a140d-af76-4c36-82a4-94277c797b43'),(5,'deleteentries:8','2017-10-24 11:52:27','2017-10-24 11:52:27','c7bd2e4b-8489-4a54-8c17-2d1d4581fe5a'),(6,'publishpeerentries:8','2017-10-24 11:52:27','2017-10-24 11:52:27','25d71b7d-36c6-44a7-a0cf-67f71ac5d7f1'),(7,'deletepeerentries:8','2017-10-24 11:52:27','2017-10-24 11:52:27','6ab161dc-90ec-445b-8c8a-84bbbfafeeee'),(8,'editpeerentries:8','2017-10-24 11:52:27','2017-10-24 11:52:27','83cd63ec-e65a-4566-9be0-b8d05e5c59f8'),(9,'publishpeerentrydrafts:8','2017-10-24 11:52:27','2017-10-24 11:52:27','bdacb3a4-92a4-4719-be56-2ec0a9d59b4b'),(10,'deletepeerentrydrafts:8','2017-10-24 11:52:27','2017-10-24 11:52:27','6f98a9b1-35c7-4b1c-a4b7-3b37e860f170'),(11,'editpeerentrydrafts:8','2017-10-24 11:52:27','2017-10-24 11:52:27','5a6f0d3f-3705-45a1-8777-b85921cdf526'),(12,'createentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','0311c112-fdfa-4eff-816f-220ab95f5348'),(13,'publishentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','0d6a8e3c-5e66-4dd1-ab6d-bbb916bc6415'),(14,'deleteentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','0036eea5-60d3-4f02-9e42-c14ff2f6120b'),(15,'publishpeerentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','a6a17622-3c67-488f-be63-d58cc5330cbd'),(16,'deletepeerentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','762f18ae-453e-4ebc-b077-8512945c77d8'),(17,'editpeerentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','66a6bb0f-b3a4-4995-81a0-d50197492bf7'),(18,'publishpeerentrydrafts:12','2018-04-13 10:39:01','2018-04-13 10:39:01','83ab6ed6-61d5-4f68-ae2c-6daa9156e2e4'),(19,'deletepeerentrydrafts:12','2018-04-13 10:39:01','2018-04-13 10:39:01','e7a78973-0d7d-445c-9820-477f816c0347'),(20,'editpeerentrydrafts:12','2018-04-13 10:39:01','2018-04-13 10:39:01','307cee2c-b08b-4979-b4e8-2f203c59b76b'),(21,'editentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','247b62ca-9948-4574-ba2b-3eb6e11c6ca4'),(22,'createentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','854e48c4-4977-4dba-a800-fd7763e9b6a2'),(23,'publishentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','d29c110f-0a3c-44ae-9fb9-b66a4f4d45d9'),(24,'deleteentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','b498e8f3-8e49-421b-8ede-5b548cf2b985'),(25,'publishpeerentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','d7bb2da3-3d75-4603-a4f7-fffa799375ae'),(26,'deletepeerentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','573b7634-54bb-419a-8971-a9616a13e730'),(27,'editpeerentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','3b230546-f668-47fe-858c-4533c6ab9dd4'),(28,'publishpeerentrydrafts:5','2018-04-27 19:32:40','2018-04-27 19:32:40','0a2d58d1-1845-44b0-abd0-11d83df37660'),(29,'deletepeerentrydrafts:5','2018-04-27 19:32:40','2018-04-27 19:32:40','dee1160b-dcb3-4585-ac1d-0a524e378a67'),(30,'editpeerentrydrafts:5','2018-04-27 19:32:40','2018-04-27 19:32:40','77598d92-2e84-4612-b91f-2c43f4bede78'),(31,'editentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','bed835fd-1f06-4f1a-8ca1-e204d4a2f93a'),(32,'uploadtoassetsource:1','2018-04-27 19:32:40','2018-04-27 19:32:40','3bdffd00-5d15-4292-b25c-0c9e6edda4e4'),(33,'createsubfoldersinassetsource:1','2018-04-27 19:32:40','2018-04-27 19:32:40','5f70d3b5-7b4a-40d3-9e66-8039cd0aef25'),(34,'removefromassetsource:1','2018-04-27 19:32:40','2018-04-27 19:32:40','2989cbec-1dfb-4366-9967-ecda31ea47f9'),(35,'viewassetsource:1','2018-04-27 19:32:40','2018-04-27 19:32:40','f9822513-0272-4a46-8411-6063f68d961e'),(36,'createentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','60c3b22b-7a2e-493f-9a40-39d291d7506c'),(37,'publishentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','989daebc-2d45-44db-8cdf-039a3f330b21'),(38,'deleteentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','c246bc5d-ffba-42f6-9c8f-d97ed39c27ee'),(39,'publishpeerentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','527eae7b-9315-47ba-b1f2-a1f086ff81ad'),(40,'deletepeerentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','57fedc51-6675-4fcd-aa46-970532384936'),(41,'editpeerentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','fc20e7d3-a0a6-400b-9f6c-71c8875e5d1e'),(42,'publishpeerentrydrafts:10','2018-04-27 19:33:38','2018-04-27 19:33:38','1b376249-680b-4b9b-be9f-d4d5e1dcea90'),(43,'deletepeerentrydrafts:10','2018-04-27 19:33:38','2018-04-27 19:33:38','3ad1add9-ca13-4266-865e-c8b9e046697b'),(44,'editpeerentrydrafts:10','2018-04-27 19:33:38','2018-04-27 19:33:38','90282832-0a4f-4f50-b12b-18b158a47100'),(45,'editentries:10','2018-04-27 19:33:38','2018-04-27 19:33:38','263d66a6-4aa9-4c2b-9b31-09197fb6b4b1'),(46,'registerusers','2018-04-27 19:33:50','2018-04-27 19:33:50','6d3ee0bf-ff81-4739-a076-6c4974d0d7d2'),(47,'assignuserpermissions','2018-04-27 19:33:50','2018-04-27 19:33:50','11e09aed-c382-4c72-876a-42101762c291'),(48,'changeuseremails','2018-04-27 19:33:50','2018-04-27 19:33:50','ba69c6bf-9860-49c8-8b7e-c05a93010f77'),(49,'administrateusers','2018-04-27 19:33:50','2018-04-27 19:33:50','dc561fe6-c662-452a-9ec6-72d18a58a928'),(50,'editusers','2018-04-27 19:33:50','2018-04-27 19:33:50','0bad6d7a-094f-4843-9c6f-637c2bda8da9'),(51,'deleteusers','2018-04-27 19:33:50','2018-04-27 19:33:50','fa3cd546-53be-4854-97f7-b0bbd70b5336'),(52,'createentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','a40366b3-5930-4f8d-83ed-08bac76d5fe9'),(53,'publishentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','c2a2d6d3-d91f-4c80-96a5-d396e63b7a4f'),(54,'deleteentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','433464cc-1a3d-42d2-abed-7dd1a8e5f388'),(55,'publishpeerentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','0c262595-46f6-418d-8aa2-ea7911dfbeab'),(56,'deletepeerentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','04221546-711f-4012-9b58-ec33d3487849'),(57,'editpeerentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','1a3821a5-b599-47b7-9971-dd2d48cc989e'),(58,'publishpeerentrydrafts:3','2018-04-27 19:34:15','2018-04-27 19:34:15','ca6e4f70-e68f-4368-9ee1-ad4612bfb83b'),(59,'deletepeerentrydrafts:3','2018-04-27 19:34:15','2018-04-27 19:34:15','7a33dc4c-a30b-4d30-973c-e7bd37d4e413'),(60,'editpeerentrydrafts:3','2018-04-27 19:34:15','2018-04-27 19:34:15','f8c82046-49ad-4820-beee-09ae1a8d2ede'),(61,'editentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','f43e33a8-de9c-4ed3-8018-37a13d9a660b'),(62,'editcategories:1','2018-04-27 19:34:15','2018-04-27 19:34:15','94cd895d-961b-42f3-9099-b9f0e96bfc9a'),(63,'assignusergroups','2018-04-29 12:43:55','2018-04-29 12:43:55','a51aaaab-d271-4392-b891-0e2b2f5a2aab'),(64,'assignusergroup:2','2018-04-29 12:43:55','2018-04-29 12:43:55','b0a03411-d7f9-4aba-980b-ac5dc2fbf645'),(65,'assignusergroup:1','2018-04-29 12:43:55','2018-04-29 12:43:55','877aae1a-b8b7-427c-9081-a5decac8b153'),(66,'assignusergroup:3','2018-04-29 12:43:55','2018-04-29 12:43:55','7789273f-dd44-4a42-a230-da794d128932'),(67,'assignusergroup:4','2018-04-29 12:43:55','2018-04-29 12:43:55','4c0beabc-0b32-4d80-9569-75216c2c3fa2'),(68,'createentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','40f24ed8-bb27-4742-840a-5b23a2b1a94c'),(69,'publishentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','8ac2d3b0-904d-42df-a219-b78b152993cf'),(70,'deleteentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','3c08266e-a971-42b0-b774-e30a52e74e88'),(71,'publishpeerentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','55ddcc88-0c2f-4e56-9835-210ef3a88819'),(72,'deletepeerentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','7dc956e2-2d02-4097-aab9-3570082d9148'),(73,'editpeerentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','eb9fed02-390d-4334-bd24-b5e0f302cf4f'),(74,'publishpeerentrydrafts:6','2018-04-29 13:45:38','2018-04-29 13:45:38','49bea8fe-143d-42a4-a81a-62ebd4d91c2c'),(75,'deletepeerentrydrafts:6','2018-04-29 13:45:38','2018-04-29 13:45:38','564c8348-4142-44b1-9a9d-1deea1c425f7'),(76,'editpeerentrydrafts:6','2018-04-29 13:45:38','2018-04-29 13:45:38','7d2193dc-0864-48f9-94f8-c64ebd0c9793'),(77,'editentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','71d308bb-22be-4cfa-97f2-130f4e584464'),(78,'createentries:13','2018-08-24 18:45:22','2018-08-24 18:45:22','86f7f546-4e25-4929-a192-fab4e55d9f0f'),(79,'publishentries:13','2018-08-24 18:45:22','2018-08-24 18:45:22','104e91b6-9222-4d0f-a75e-dee804d413f9'),(80,'deleteentries:13','2018-08-24 18:45:22','2018-08-24 18:45:22','5b555e50-5083-489f-a8e3-ab96b8e02600'),(81,'publishpeerentries:13','2018-08-24 18:45:23','2018-08-24 18:45:23','8b0bc0fc-b5b0-41db-8acf-73cae312df81'),(82,'deletepeerentries:13','2018-08-24 18:45:23','2018-08-24 18:45:23','654aa191-916a-498a-b1a5-75a889a5c02a'),(83,'editpeerentries:13','2018-08-24 18:45:23','2018-08-24 18:45:23','103f1d46-85c3-4a9f-9aa9-7d4c6b164c2d'),(84,'publishpeerentrydrafts:13','2018-08-24 18:45:23','2018-08-24 18:45:23','e0217d93-3061-4181-bd68-3f7e48f98d52'),(85,'deletepeerentrydrafts:13','2018-08-24 18:45:23','2018-08-24 18:45:23','e406b1d9-3794-419b-bb11-e0e8b4010ac7'),(86,'editpeerentrydrafts:13','2018-08-24 18:45:23','2018-08-24 18:45:23','edbedef1-f9b4-4a8c-9b09-fe7c48334a04'),(87,'editentries:13','2018-08-24 18:45:23','2018-08-24 18:45:23','7cf81fa9-8baa-4456-8b34-0b7b7c94df63'),(88,'createentries:7','2019-01-17 13:52:21','2019-01-17 13:52:21','d7a9206b-8d14-4f25-bff8-db1eb458800d'),(89,'publishentries:7','2019-01-17 13:52:21','2019-01-17 13:52:21','2d207183-e5fd-42cc-a3b9-ac33e8cbd664'),(90,'deleteentries:7','2019-01-17 13:52:21','2019-01-17 13:52:21','215a7a79-0064-4091-9995-56828d239713'),(91,'publishpeerentries:7','2019-01-17 13:52:21','2019-01-17 13:52:21','08c44482-96ae-4d16-bf2f-14664a45bd08'),(92,'deletepeerentries:7','2019-01-17 13:52:21','2019-01-17 13:52:21','a8207b68-7606-4063-b817-3fd1635fa2ba'),(93,'editpeerentries:7','2019-01-17 13:52:21','2019-01-17 13:52:21','1fb43fa0-c3f3-473d-ae56-a529a5187835'),(94,'publishpeerentrydrafts:7','2019-01-17 13:52:21','2019-01-17 13:52:21','64e79f92-80a9-42fb-a34c-f6e96cc90050'),(95,'deletepeerentrydrafts:7','2019-01-17 13:52:21','2019-01-17 13:52:21','5bca933b-42e7-46fc-b6c5-d50438fa5441'),(96,'editpeerentrydrafts:7','2019-01-17 13:52:21','2019-01-17 13:52:21','b96e3600-c007-4c5f-bf4a-1e664f4b70f9'),(97,'editentries:7','2019-01-17 13:52:21','2019-01-17 13:52:21','4c48457d-b695-44c7-9ca3-445f3a31d144');
/*!40000 ALTER TABLE `craft_userpermissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_userpermissions_usergroups`
--

DROP TABLE IF EXISTS `craft_userpermissions_usergroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_userpermissions_usergroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permissionId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_userpermissions_usergroups_permissionId_groupId_unq_idx` (`permissionId`,`groupId`),
  KEY `craft_userpermissions_usergroups_groupId_fk` (`groupId`),
  CONSTRAINT `craft_userpermissions_usergroups_groupId_fk` FOREIGN KEY (`groupId`) REFERENCES `craft_usergroups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_userpermissions_usergroups_permissionId_fk` FOREIGN KEY (`permissionId`) REFERENCES `craft_userpermissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=506 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_userpermissions_usergroups`
--

LOCK TABLES `craft_userpermissions_usergroups` WRITE;
/*!40000 ALTER TABLE `craft_userpermissions_usergroups` DISABLE KEYS */;
INSERT INTO `craft_userpermissions_usergroups` VALUES (102,12,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','5178225d-8544-4760-8488-1f9a516ba8b8'),(103,13,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','a7ec1f66-aff4-4327-9505-168cfa0d3b76'),(104,14,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','7d9df0df-3934-4909-b503-9249617e8ca6'),(105,15,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','d0248881-339b-4b7f-bc55-73ffc191afab'),(106,16,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','a5534cb0-9637-4548-b115-0c5f1b687c96'),(107,17,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','da615584-5665-4a4e-8202-f8491d256eec'),(108,18,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','eac3a6d3-0aea-4fc9-9363-3c446b501a7e'),(109,19,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','8326833d-4595-4041-9948-58f52b2cab18'),(110,20,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','f7274415-f471-401e-92f2-72b9adf2bd55'),(111,21,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','581d048e-e4df-4cbf-a982-0f39aaba8755'),(112,36,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','dd155d2b-2dd7-419c-a1e5-962e60942a6a'),(113,37,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','a2a26e6d-e4df-4525-b633-999e308e89e3'),(114,38,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','39d0a838-1cd2-4f4e-845d-1a0e1f8df6fe'),(115,39,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','91f6a328-35a3-443f-b457-82122ebd28db'),(116,40,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','1f25122f-43c4-46ca-bc08-5f4bbd42c22c'),(117,41,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','dcc8fd1b-0cb8-453c-bee7-5fb3fd65e876'),(118,42,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','5935cab4-676a-4eb3-b5dd-9d3c13552c12'),(119,43,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','550fb24c-a97f-4a47-892f-d2db8f58df07'),(120,44,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','ef8ae685-24bf-4154-999f-0d41ae805df6'),(121,45,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','e2ea20e9-0a34-4e37-b8b3-bbccd4153f1c'),(122,32,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','d6357049-b496-4d5b-a4ab-cd27256a176b'),(123,33,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','704e489b-0cd7-4f4e-80ba-85484a5e5c8f'),(124,34,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','2399dde2-5b8b-44b5-bd7e-c1cdebf0c39e'),(125,35,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','af3f0c7e-7c4c-4acd-b0d6-8fa659bff482'),(365,46,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','aec97587-0bdd-4b3e-9126-dace2802899d'),(366,48,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','92205f08-cc36-44e9-8fc8-f83226ceb4cd'),(367,49,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','ed97895c-6371-4569-9753-c047d39a3679'),(368,47,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','d8187876-8670-4666-80e8-ad81cf22580b'),(369,64,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','8061df49-809a-4676-bead-bc2e8920eddd'),(370,65,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','792c6cee-1e13-4052-a9b9-9111f8768d68'),(371,66,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','32ba3acd-bffc-4245-a870-076daf0af35f'),(372,67,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','9c2fb388-61c5-42f6-ac3f-3c5c24d9f47b'),(373,63,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','770da4bb-80a9-4970-8f89-0daaa00b3af6'),(374,50,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','355fca6c-2421-44fb-9097-57194d5267b4'),(375,51,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','d462fab5-af8b-451a-b2fd-cffdb796f617'),(376,78,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','0a4c5d32-c719-42ec-a4bc-9129240caca8'),(377,79,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','9d080382-4e62-4301-ba94-992c54082f15'),(378,80,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','e87a4688-ddc2-487b-bf8f-b77346281d6b'),(379,81,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','e2319023-2205-41c7-b11e-ed53698780c7'),(380,82,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','31368b80-38e2-49a5-912a-af863790fd70'),(381,83,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','457c8b41-97ee-4947-81d9-c5e3c9f71067'),(382,84,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','9f87fd21-e30c-4806-9493-68e71bdc3f68'),(383,85,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','561b5a43-d76a-471c-bad5-f3d811b6cd89'),(384,86,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','ad4d1407-57f9-487a-9045-e78d2a9cfec0'),(385,87,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','b55c5a1a-667f-404d-99cb-cceed4a167ff'),(386,62,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','1a44c052-8614-46af-b2e5-24c0648c8f33'),(387,46,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','36c11321-e659-45eb-a1cf-7cb82bdb6f83'),(388,48,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','dc067383-5e15-46a6-b25e-e3753fdddc25'),(389,49,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','82e5ecf8-ba79-40c7-9c58-b224b917a40a'),(390,47,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','c4f00358-b0a6-4a63-88da-e713523887dc'),(391,64,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','cd05c9f8-1870-41dc-af3d-58add0407fae'),(392,65,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','2dbb5c1c-4af9-4a57-aa81-0a88ab5cfb18'),(393,66,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','23862555-c832-45ca-98eb-992c07bda0dc'),(394,67,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','a718c99c-0085-43a5-91f4-0f8d7570256e'),(395,63,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','eedb7b08-458c-4ebb-8c2b-6777f19c7f34'),(396,50,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','d0891e83-e2df-468e-a62b-5428aca859ec'),(397,51,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','ab91a1bb-0a6b-4487-8bc1-a40ff0ff59e0'),(398,78,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','e3423ca9-4ddd-4c7b-a473-73f13c3dbfe6'),(399,79,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','01fa1056-adb2-4768-b155-86d01b9f2a15'),(400,80,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','451a3591-ef1c-4f03-862b-dbd28284cb78'),(401,81,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','400de6a5-c015-4b93-8a72-bf8d90dcf9b8'),(402,82,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','63a6bd0a-73b0-4ca5-bb1c-f43c12587ae0'),(403,83,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','c5c8d50f-d361-4e95-80d5-ea0ca495a866'),(404,84,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','370f2ce4-5a8e-49ae-a0e9-3e8b0702826f'),(405,85,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','a7603046-6c6a-4dd2-8d23-09f52ba68f43'),(406,86,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','b0843422-8c0c-49d9-b9b2-05934d21fd94'),(407,87,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','40b625f4-bb07-4637-a088-50f2d7cc28b0'),(408,36,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','f49de788-de17-459d-b6af-8a22db2a719b'),(409,37,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','b0c5a20a-4dac-4f96-ae0b-416980ed1585'),(410,39,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','d68aae1e-3933-49e2-bf4c-03d785165c37'),(411,41,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','3647a7c1-7501-4220-ad9d-1b85561f0f7c'),(412,42,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','d7322110-6b17-4623-b81f-f657eb4e09df'),(413,44,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','357e8aef-d132-49a3-850d-907a96117ac3'),(414,45,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','a796c851-a6a7-4038-9e37-737abb0c7050'),(415,22,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','1a071514-427f-423a-b27d-21fe00ccf3ed'),(416,23,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','758796e6-ce3c-4af7-b89d-53e4f77e2a3b'),(417,24,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','a4a7acb3-53b4-4282-9ddb-c24fbb7955ea'),(418,25,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','284a32c0-1d0e-46f0-9f64-a99d38e2aff9'),(419,26,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','1612f14b-5dd9-4894-b81f-0dd45b590181'),(420,27,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','e7f74329-e95c-4b90-8814-3488f74ee2fd'),(421,28,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','e6e6d284-3833-4531-9d8d-3d66d97d6bed'),(422,29,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','51474557-c0f2-4f16-9b4a-381cdafac388'),(423,30,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','68267869-564d-44b8-a085-66273870c0a8'),(424,31,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','e4e90ccc-c268-49dd-959a-f8c91478aa2c'),(425,62,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','84a561f5-949a-4623-af09-ec3740910f3d'),(426,32,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','82731463-bf44-49f2-a9bd-b5dd64784dab'),(427,33,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','edc0527e-7c99-40fb-a5ec-25fa884d8f3e'),(428,34,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','b4c40d6e-7877-4b0c-87e3-aecb8359fead'),(429,35,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','04291920-3d0f-4d9f-b96a-628417b4c69f'),(430,46,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','b038a07d-dcca-4954-9f90-bdc9a8f80e51'),(431,48,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','3cfad94a-9f9e-458d-b56b-6d64987fd4f7'),(432,49,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','4ac66115-9530-4255-8ba3-ec7afcf8bacd'),(433,47,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','110d9523-bee1-4531-aacd-e22123715772'),(434,64,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','222edcec-9d76-43be-a1f4-7b481e2796a9'),(435,65,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','14d60b25-3c82-46ea-af9c-8de1e6f9d148'),(436,66,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','e4d61683-f866-4d97-85ec-825585a2fe9e'),(437,67,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','0a0ad598-91e3-4b0a-8f72-b997382a4974'),(438,63,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','a3e7e506-f59e-47b5-9e17-935d379e71b1'),(439,50,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','e1f66afc-3cb9-428f-8575-ad41bf50a5bb'),(440,51,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','ebd14aa7-8688-416b-8de5-65566460aa58'),(441,52,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','988570ee-33dd-4e15-b445-fdf44538f230'),(442,53,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','4c2a447e-219b-4b5d-b33e-40fe7c3dbcc1'),(443,54,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','754eb460-5887-4781-9f59-f519bebb779b'),(444,55,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','19733bcc-d04d-4f08-ac42-e0a32f6f3fd2'),(445,56,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','208e11a0-b153-4708-ac02-c81cde1a3512'),(446,57,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','1e880d15-40c4-40e9-94d7-3e147140d29e'),(447,58,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','d0d8a9c3-1d6a-4269-b794-10e0631faeee'),(448,59,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','582ccce7-72e7-45fb-944c-90cf941e715b'),(449,60,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','4edddf56-6df6-4d8d-96b2-251a1440ab99'),(450,61,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','4d4538e4-8c7b-4231-b7f6-ce94d57e0a8c'),(451,68,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','19659368-54f2-40c4-8915-2a4880388b04'),(452,69,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','5d92309d-fee6-41ea-a1fc-ba38271ae6b8'),(453,70,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','da9cf41f-e5e7-4d59-bd6a-160a5e626ace'),(454,71,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','30884005-dac9-4f21-ba36-4f48e83ac3d7'),(455,72,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','d95064a7-ae60-4211-9a71-11d9dac88c21'),(456,73,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','6549b23e-a445-49e2-93b4-c47597ce382b'),(457,74,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','ba52bf18-c2cd-4fac-968a-28faff8767e4'),(458,75,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','c0e91834-dfd0-4ec0-8a5f-67fd89f3d38d'),(459,76,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','1bc2006e-b691-4364-bbdf-0040ccbdec30'),(460,77,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','ab9db399-f02a-486a-a2d5-c1251df3d9d0'),(461,78,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','a8e6af85-6b38-4696-8a98-947392a838f4'),(462,79,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','ea36748d-d140-4a67-b2af-c2f6bc3d6468'),(463,80,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','45b4515c-5536-4085-8aca-da48515c0bbd'),(464,81,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','0abdeb6b-9398-43c8-9535-ef2def14fb69'),(465,82,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','72b7611e-63ee-4eca-a45f-19e7b1437c42'),(466,83,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','d782c80b-7328-46d2-9aa1-d3d75b29d391'),(467,84,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','9c849796-a8b4-4438-a17b-fdd1f3ec6977'),(468,85,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','8b84f891-7824-4162-8644-531e0d05f94b'),(469,86,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','7fbb7b88-fa88-4495-8493-c97d0979be38'),(470,87,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','b6a19132-8343-4c3d-9432-c107241c756e'),(471,36,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','a5a42133-9650-4bb4-89af-160b5a27d106'),(472,37,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','cda57fe0-2171-4130-a2ed-c5e0217e2434'),(473,38,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','e8114c23-0e40-434a-a051-571a0e9d4474'),(474,39,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','60f571d2-f074-4fd7-9208-9005027565a6'),(475,40,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','8981d0d5-d524-427b-80d4-3c93144e00b4'),(476,41,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','270ecdd6-2beb-480c-a947-3236ee9109be'),(477,42,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','d1c954cf-4921-4234-aa1a-f179299ce203'),(478,43,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','a5063471-addc-4bc7-8832-064780b4c950'),(479,44,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','d571a185-254f-41d4-87cc-87e54277f409'),(480,45,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','1f4e87bf-9ddf-464f-a01c-deebecefc9b8'),(481,22,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','56c83a38-9ad4-41c3-8371-387ed69a6f07'),(482,23,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','2db7e6e1-c24a-4867-aaae-c6cc7d9e1ca0'),(483,24,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','d9385228-cf3c-4cf3-8c8e-b20abfe3879f'),(484,25,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','2c460f4b-1f1b-4595-a651-b45a9a4456f6'),(485,26,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','0f71e2a5-d43b-4722-a46c-68eafc89c2fa'),(486,27,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','ef22eac0-d099-4f05-9959-5d53d856f87f'),(487,28,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','88ebbff3-6447-4df1-90c0-900c61dfb447'),(488,29,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','51362834-e5e1-4018-aab1-d285b8024979'),(489,30,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','ae31dfe8-58d9-425d-90f1-79fe9f7628aa'),(490,31,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','ece1fd1c-8306-4f40-93c9-8bbd501ddda5'),(491,88,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','72d95364-e5e0-4e82-afba-6d943c09a77d'),(492,89,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','ed1f3963-b59d-43fc-9288-efe8d4d9fcc5'),(493,90,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','5d068eaa-c618-4b59-9f79-edad2173d0a9'),(494,91,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','b6bc4af3-31f7-4b50-bedf-b0edff24c2e0'),(495,92,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','160960e0-2357-4094-a59a-cacd087fcfe8'),(496,93,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','6779c1db-b1e4-4679-a12f-8cc03a3af288'),(497,94,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','353920de-2bf6-44b7-8223-fb8941cde038'),(498,95,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','e8b79295-c16a-4313-b5a5-045b7d5b3708'),(499,96,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','da765d76-5e89-461c-9ae9-5186f64c8760'),(500,97,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','2813ea4d-742e-43bc-9e3c-9df2579a5ceb'),(501,62,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','529b88f7-7e00-45c2-ab3e-6e2a6db948c3'),(502,32,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','8fe39261-6a9b-40e5-a71e-81cb4b5fb3d2'),(503,33,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','2c733dbe-8af2-4b8f-b506-80f3cb07694e'),(504,34,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','37e3bee0-4696-4c2b-aeb5-8954b20a6794'),(505,35,1,'2019-01-17 13:52:21','2019-01-17 13:52:21','5d5b471d-fccc-4da3-905a-e43ba502b8dc');
/*!40000 ALTER TABLE `craft_userpermissions_usergroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_userpermissions_users`
--

DROP TABLE IF EXISTS `craft_userpermissions_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_userpermissions_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permissionId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_userpermissions_users_permissionId_userId_unq_idx` (`permissionId`,`userId`),
  KEY `craft_userpermissions_users_userId_fk` (`userId`),
  CONSTRAINT `craft_userpermissions_users_permissionId_fk` FOREIGN KEY (`permissionId`) REFERENCES `craft_userpermissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_userpermissions_users_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_userpermissions_users`
--

LOCK TABLES `craft_userpermissions_users` WRITE;
/*!40000 ALTER TABLE `craft_userpermissions_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `craft_userpermissions_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_users`
--

DROP TABLE IF EXISTS `craft_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `preferredLocale` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weekStartDay` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `client` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `suspended` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pending` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `archived` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lastLoginDate` datetime DEFAULT NULL,
  `lastLoginAttemptIPAddress` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invalidLoginWindowStart` datetime DEFAULT NULL,
  `invalidLoginCount` tinyint(4) unsigned DEFAULT NULL,
  `lastInvalidLoginDate` datetime DEFAULT NULL,
  `lockoutDate` datetime DEFAULT NULL,
  `verificationCode` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verificationCodeIssuedDate` datetime DEFAULT NULL,
  `unverifiedEmail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwordResetRequired` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lastPasswordChangeDate` datetime DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_users_username_unq_idx` (`username`),
  UNIQUE KEY `craft_users_email_unq_idx` (`email`),
  KEY `craft_users_verificationCode_idx` (`verificationCode`),
  KEY `craft_users_uid_idx` (`uid`),
  KEY `craft_users_preferredLocale_fk` (`preferredLocale`),
  CONSTRAINT `craft_users_id_fk` FOREIGN KEY (`id`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_users_preferredLocale_fk` FOREIGN KEY (`preferredLocale`) REFERENCES `craft_locales` (`locale`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_users`
--

LOCK TABLES `craft_users` WRITE;
/*!40000 ALTER TABLE `craft_users` DISABLE KEYS */;
INSERT INTO `craft_users` VALUES (1,'jason@thisistraffic.co.uk','index.png','Jason','Church','jason@thisistraffic.co.uk','$2y$13$YwZnbIjJ.OVBJOjJe2wUHOha6jy9sJU0P4aHXyq7/Sl15CzxcZiZa',NULL,1,1,0,0,0,0,0,'2019-02-15 14:01:23','80.3.48.54',NULL,NULL,'2019-01-23 15:15:44',NULL,NULL,NULL,NULL,0,'2018-06-27 09:24:41','2017-10-23 13:26:42','2019-02-15 14:01:23','9c19a49f-9856-4261-9b3c-0fbe784c7409'),(143,'robin@coffeebean.design','person-unknown.jpg','Robin','Willmott','robin@coffeebean.design','$2y$13$VqPGqTddUw4b91fjr8UoE.SlHT4ySQ5Y5X9YYz2No5fGhtx7m2HyO',NULL,0,1,0,0,0,0,0,'2019-04-25 13:25:54','109.224.208.196',NULL,NULL,'2019-02-14 15:01:42',NULL,NULL,NULL,NULL,0,'2019-01-16 11:59:19','2018-03-10 15:10:18','2019-04-25 13:25:54','493a9c3c-495a-4e76-a6bc-eb19686a2032'),(1423,'portia.hartley@lantra.co.uk',NULL,'Portia','Hartley','portia.hartley@lantra.co.uk','$2y$13$oB/PKSkKxsRXTj99hbM57O1PEHczqeRC55IO6LqmcsQE.RaaJJt5.',NULL,0,1,0,0,0,0,0,'2019-02-19 11:44:22','5.148.54.98',NULL,NULL,'2019-02-08 08:46:25',NULL,NULL,NULL,NULL,0,'2018-10-04 15:36:35','2018-10-04 15:36:00','2019-02-19 11:44:22','0235a0f4-3e0a-4a17-8d38-95b4ed489444'),(1438,'margaret.murray@skills-plus.co.uk','Koala.jpg','Margaret','Murray','margaret.murray@skills-plus.co.uk','$2y$13$WvolCzLah2JyrVKBORxgo.iF5QQNea57O9l9KvWLzMrHOdUDdmH2K',NULL,0,1,0,0,0,0,0,'2019-02-21 10:32:54','5.148.54.98',NULL,NULL,'2019-01-30 09:25:53',NULL,NULL,NULL,NULL,0,'2019-01-15 08:47:16','2018-10-11 09:02:13','2019-02-21 10:32:54','4182bda5-f6b8-4125-8893-94a432f7691e'),(1440,'stuart.smith@lantra.co.uk',NULL,'Stuart','Smith','stuart.smith@lantra.co.uk','$2y$13$cyymCrsLr/LXcrEiTpllgetQb9mB95PhwFsYbBHF8N7ESD/c84rzm',NULL,0,1,0,0,0,0,0,'2018-10-12 09:37:16','5.148.54.98',NULL,NULL,NULL,NULL,'$2y$13$ua6pPqKP/lwmmOo6XbGj2u95G.OLT/Ks3hcfjdi.Ke4tiK4fdanFy','2018-10-12 13:44:59',NULL,0,'2018-10-12 09:37:02','2018-10-12 09:36:14','2018-10-12 13:44:59','25793302-2ef2-4e8f-ac24-9fd5eadbbd62');
/*!40000 ALTER TABLE `craft_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_widgets`
--

DROP TABLE IF EXISTS `craft_widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `craft_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `sortOrder` smallint(6) unsigned DEFAULT NULL,
  `colspan` tinyint(4) unsigned DEFAULT NULL,
  `settings` text COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `craft_widgets_userId_fk` (`userId`),
  CONSTRAINT `craft_widgets_userId_fk` FOREIGN KEY (`userId`) REFERENCES `craft_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_widgets`
--

LOCK TABLES `craft_widgets` WRITE;
/*!40000 ALTER TABLE `craft_widgets` DISABLE KEYS */;
INSERT INTO `craft_widgets` VALUES (1,1,'RecentEntries',1,NULL,NULL,1,'2017-10-23 13:40:04','2017-10-23 13:40:04','33867b09-4c47-4cf4-8f13-a3b92fb989dc'),(2,1,'GetHelp',2,NULL,NULL,1,'2017-10-23 13:40:04','2017-10-23 13:40:04','039d6d22-aabe-4ef8-b2b5-82a1685348c8'),(3,1,'Updates',3,NULL,NULL,1,'2017-10-23 13:40:04','2017-10-23 13:40:04','c08d85e1-a2fe-4c10-ab66-0f07ff2a01c1'),(4,1,'Feed',4,NULL,'{\"url\":\"https:\\/\\/craftcms.com\\/news.rss\",\"title\":\"Craft News\"}',1,'2017-10-23 13:40:04','2017-10-23 13:40:04','450841e4-16ad-4b0e-9a56-c06a409ba0d3'),(13,143,'RecentEntries',1,2,'{\"section\":\"10\",\"limit\":\"10\"}',1,'2018-03-10 15:11:05','2018-04-29 12:44:41','016428ca-ab5e-4834-a18a-55b810dd3dd4'),(14,143,'GetHelp',2,NULL,NULL,0,'2018-03-10 15:11:05','2018-03-14 12:59:16','d61fa348-6f28-4485-8729-1f7d4af5e5a5'),(15,143,'Updates',3,NULL,NULL,0,'2018-03-10 15:11:05','2018-03-14 12:59:18','ccaab691-0a2a-465d-bf14-06bdc2938f38'),(16,143,'Feed',4,NULL,'{\"url\":\"https:\\/\\/craftcms.com\\/news.rss\",\"title\":\"Craft News\"}',0,'2018-03-10 15:11:05','2018-03-14 12:59:27','8aeb81bb-c7e8-425e-a423-2dbae538aeee'),(21,143,'RecentEntries',5,2,'{\"section\":\"6\",\"limit\":\"10\"}',1,'2018-04-29 12:44:34','2018-04-29 12:44:43','75b3e609-430b-4555-9208-38c839d9100b'),(22,1423,'RecentEntries',1,NULL,NULL,1,'2018-10-04 15:36:57','2018-10-04 15:36:57','6f7aab71-7466-476a-9e9b-f15960948922'),(23,1423,'GetHelp',2,NULL,NULL,1,'2018-10-04 15:36:57','2018-10-04 15:36:57','4c256a8f-cfb8-4784-9736-ca0ac0a5a550'),(24,1423,'Updates',3,NULL,NULL,1,'2018-10-04 15:36:57','2018-10-04 15:36:57','5e42048d-f177-44b5-9355-1fd471df57e5'),(25,1423,'Feed',4,NULL,'{\"url\":\"https:\\/\\/craftcms.com\\/news.rss\",\"title\":\"Craft News\"}',1,'2018-10-04 15:36:57','2018-10-04 15:36:57','5c7fc0de-9604-44f5-b4c7-6be11f80ef7b'),(26,1438,'RecentEntries',1,NULL,NULL,1,'2018-10-11 09:30:30','2018-10-11 09:30:30','1e7a677d-0c19-43be-8750-75dc6cb2b7da'),(27,1438,'GetHelp',2,NULL,NULL,1,'2018-10-11 09:30:30','2018-10-11 09:30:30','9539ea4b-d024-4b78-9320-d8dc5367d7ba'),(28,1438,'Updates',3,NULL,NULL,1,'2018-10-11 09:30:30','2018-10-11 09:30:30','482da76a-fe47-4550-940a-6dc4a85bb158'),(29,1438,'Feed',4,NULL,'{\"url\":\"https:\\/\\/craftcms.com\\/news.rss\",\"title\":\"Craft News\"}',1,'2018-10-11 09:30:30','2018-10-11 09:30:30','c9a78653-4bec-42c9-8fe9-18cadeb3c455'),(30,1440,'RecentEntries',1,NULL,NULL,1,'2018-10-12 09:37:16','2018-10-12 09:37:16','cb409cfb-e54c-4de2-aeb2-1689dd2bc626'),(31,1440,'GetHelp',2,NULL,NULL,1,'2018-10-12 09:37:16','2018-10-12 09:37:16','7b9f089c-babe-4c86-8a33-71b22df17bbf'),(32,1440,'Updates',3,NULL,NULL,1,'2018-10-12 09:37:16','2018-10-12 09:37:16','c1cb96eb-53c3-4581-a106-fba8fd690f2e'),(33,1440,'Feed',4,NULL,'{\"url\":\"https:\\/\\/craftcms.com\\/news.rss\",\"title\":\"Craft News\"}',1,'2018-10-12 09:37:16','2018-10-12 09:37:16','e0e0af45-4ea2-45a8-a7b1-1c6f88614d65');
/*!40000 ALTER TABLE `craft_widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lantra_users`
--

DROP TABLE IF EXISTS `lantra_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `lantra_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `legacyId` varchar(45) DEFAULT NULL,
  `legacyCompanyId` varchar(45) DEFAULT NULL,
  `legacyGroup` varchar(45) DEFAULT NULL,
  `legacyJobRoleId` int(11) DEFAULT NULL,
  `userDateOfBirth` varchar(45) DEFAULT NULL,
  `userStartDate` varchar(45) DEFAULT NULL,
  `userAddress` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1 COMMENT='	';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lantra_users`
--

LOCK TABLES `lantra_users` WRITE;
/*!40000 ALTER TABLE `lantra_users` DISABLE KEYS */;
INSERT INTO `lantra_users` VALUES (1,'trainingadmin','poultry.passport@poultec.co.uk','87234','34338','User',1,'01/01/1990','10/12/2010','N/A'),(2,'sherrie.woodcock@poultec.co.uk','sherrie.woodcock@poultec.co.uk','87237','34338','User',2,'01/01/2001','01/01/2001','Poultec Course Admin'),(3,'leroy.burrell@poultec.co.uk','leroy.burrell@poultec.co.uk','94130','34338','Administrator',2,'03/12/2010','01/01/2001','Poultec Training Limited'),(4,'ben.bandrowski@poultec.co.uk','ben.bandrowski@poultec.co.uk','94131','34338','User',1,'01/01/2001','01/01/2001','.'),(5,'kelly.falgate@poultec.co.uk','kelly.falgate@poultec.co.uk','94133','34338','User',2,'01/01/1900','22/11/2015','NULL'),(6,'alison.baker@poultec.co.uk1','alison.baker@poultec.co.uk','94175','34338','User',1,'01/01/2001','01/01/2001','Poultec Record Admin'),(7,'ellen.warner@poultec.co.uk','ellen.warner@poultec.co.uk','103182','34338','User',2,'01/01/1900','20/02/2017','PTL Training Admin'),(8,'sherrie.woodcock@poultec.co.uk','sherrie.woodcock@poultec.co.uk','87237','34338','User',2,'01/01/2001','01/01/2001','Poultec Course Admin'),(9,'leroy.burrell@poultec.co.uk','leroy.burrell@poultec.co.uk','94130','34338','Administrator',2,'03/12/2010','01/01/2001','Poultec Training Limited'),(10,'ben.bandrowski@poultec.co.uk','ben.bandrowski@poultec.co.uk','94131','34338','User',1,'01/01/2001','01/01/2001','.'),(11,'kelly.falgate@poultec.co.uk','kelly.falgate@poultec.co.uk','94133','34338','User',2,'01/01/1900','22/11/2015','NULL'),(12,'alison.baker@poultec.co.uk1','alison.baker@poultec.co.uk','94175','34338','User',1,'01/01/2001','01/01/2001','Poultec Record Admin'),(13,'ellen.warner@poultec.co.uk','ellen.warner@poultec.co.uk','103182','34338','User',2,'01/01/1900','20/02/2017','PTL Training Admin'),(14,'sherrie.woodcock@poultec.co.uk','sherrie.woodcock@poultec.co.uk','87237','34338','User',2,'01/01/2001','01/01/2001','Poultec Course Admin'),(15,'leroy.burrell@poultec.co.uk','leroy.burrell@poultec.co.uk','94130','34338','Administrator',2,'03/12/2010','01/01/2001','Poultec Training Limited'),(16,'ben.bandrowski@poultec.co.uk','ben.bandrowski@poultec.co.uk','94131','34338','User',1,'01/01/2001','01/01/2001','.'),(17,'kelly.falgate@poultec.co.uk','kelly.falgate@poultec.co.uk','94133','34338','User',2,'01/01/1900','22/11/2015','NULL'),(18,'alison.baker@poultec.co.uk1','alison.baker@poultec.co.uk','94175','34338','User',1,'01/01/2001','01/01/2001','Poultec Record Admin'),(19,'ellen.warner@poultec.co.uk','ellen.warner@poultec.co.uk','103182','34338','User',2,'01/01/1900','20/02/2017','PTL Training Admin'),(20,'trainingadmin','poultry.passport@poultec.co.uk','87234','34339','User',0,'01/01/1990','10/12/2010','N/A'),(21,'sherrie.woodcock@poultec.co.uk','sherrie.woodcock@poultec.co.uk','87237','34339','User',0,'01/01/2001','01/01/2001','Poultec Course Admin'),(22,'daniel_dring@pdhook.co.uk','daniel_dring@pdhook.co.uk','87238','34339','Manager',0,'01/01/2001','01/01/2001','PD Hook Group');
/*!40000 ALTER TABLE `lantra_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-25 14:26:51
