-- MySQL dump 10.13  Distrib 8.0.13, for Win64 (x86_64)
--
-- Host: localhost    Database: cpd_local
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.16.04.1

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
INSERT INTO `craft_assetfiles` VALUES (428,NULL,9,'evidence.pdf','pdf',NULL,NULL,11708,'2018-04-23 10:42:09','2018-04-23 10:42:09','2018-04-23 10:42:09','48753b3e-a2bd-4594-aa83-7e90ae78e367'),(430,NULL,9,'evidence_180423_104254.pdf','pdf',NULL,NULL,11708,'2018-04-23 10:42:53','2018-04-23 10:42:54','2018-04-23 10:42:54','da535487-bbce-4d26-bd36-b62dbd44e3ea'),(432,NULL,9,'evidence_180423_105359.pdf','pdf',NULL,NULL,11708,'2018-04-23 10:53:59','2018-04-23 10:53:59','2018-04-23 10:53:59','710545de-961e-429b-b947-21d18217fb50'),(435,NULL,9,'evidence_180423_105559.pdf','pdf',NULL,NULL,11708,'2018-04-23 10:55:58','2018-04-23 10:55:59','2018-04-23 10:55:59','33cb68e1-eb2c-4dee-a067-a0bcfd2990da'),(437,NULL,9,'evidence_180423_105654.pdf','pdf',NULL,NULL,11708,'2018-04-23 10:56:53','2018-04-23 10:56:54','2018-04-23 10:56:54','8af10230-a940-4a55-8d9b-9139123637e2'),(440,NULL,9,'evidence_180423_110232.pdf','pdf',NULL,NULL,11708,'2018-04-23 11:02:31','2018-04-23 11:02:32','2018-04-23 11:02:32','c010ee68-862e-4d6f-81e1-0ed326b64151'),(443,NULL,9,'evidence_180423_115500.pdf','pdf',NULL,NULL,11708,'2018-04-23 11:54:59','2018-04-23 11:55:00','2018-04-23 11:55:00','7de46a75-3700-4987-a326-50cdcb32c278'),(446,NULL,9,'evidence_180424_090746.pdf','pdf',NULL,NULL,11708,'2018-04-24 09:07:44','2018-04-24 09:07:47','2018-04-24 09:07:47','361d07e9-b81d-4138-a4e1-f3a80862598a'),(448,NULL,9,'evidence_180424_091154.pdf','pdf',NULL,NULL,11708,'2018-04-24 09:11:53','2018-04-24 09:11:55','2018-04-24 09:11:55','b91e24ce-89b2-4272-aa82-7c110598a13e'),(450,NULL,9,'evidence_180424_091223.pdf','pdf',NULL,NULL,11708,'2018-04-24 09:12:22','2018-04-24 09:12:24','2018-04-24 09:12:24','af2555b3-16b6-414b-acef-3010c7a0b937'),(497,NULL,16,'evidence.pdf','pdf',NULL,NULL,11708,'2018-04-29 14:01:19','2018-04-29 14:01:19','2018-04-29 14:01:19','5abc0419-958b-419c-9ab2-9b802dae2156'),(1014,NULL,62,'Koala.jpg','image',1024,768,692706,'2018-06-08 11:32:14','2018-06-08 11:32:15','2018-06-08 11:32:15','73ceb5eb-6daa-40d6-bc85-d3d51ac27ce1'),(1190,NULL,9,'unit.png','image',400,400,6130,'2018-09-11 12:03:11','2018-09-11 12:03:11','2018-09-11 12:03:11','a4883ef7-6f02-492d-94f5-97f230129571'),(1200,NULL,9,'unit_180911_120731.png','image',400,400,6130,'2018-09-11 12:07:31','2018-09-11 12:07:31','2018-09-11 12:07:31','5a5da034-9271-41cf-9f24-f65769fe9ab1'),(1269,4,67,'LantraAwards_logo.png','image',434,307,57860,'2018-09-26 15:02:40','2018-09-26 15:02:40','2018-09-26 15:02:40','41387def-acad-4ec6-87f3-34339e92395f'),(1270,4,67,'bground-img-1.jpg','image',2550,2287,1917300,'2018-09-26 15:03:18','2018-09-26 15:03:18','2018-09-26 15:03:18','3ae9959f-103c-4c40-bbad-2edce9e946d0'),(1271,4,67,'bground-img-2.jpg','image',2550,2287,1124412,'2018-09-26 15:03:20','2018-09-26 15:03:20','2018-09-26 15:03:20','3c79b243-99c9-4871-b82a-a9f4e3e961c8'),(1272,4,67,'bground-img-3.jpg','image',2550,2287,921942,'2018-09-26 15:03:22','2018-09-26 15:03:22','2018-09-26 15:03:22','da2dc6ad-59c7-4229-810c-d22e002c5924'),(1273,4,67,'bground-img-4.jpg','image',2550,2287,1040254,'2018-09-26 15:03:23','2018-09-26 15:03:23','2018-09-26 15:03:23','23ee79df-dce3-423f-8ee6-c7e0595a1036'),(1535,NULL,4,'Example-evidence.docx','word',NULL,NULL,6149,'2018-11-07 12:10:53','2018-11-07 12:10:54','2018-11-07 12:10:54','dc32c3af-b278-4311-a51d-6b86f9c4e0e8'),(1537,NULL,4,'Example-evidence_181107_121324.docx','word',NULL,NULL,6149,'2018-11-07 12:13:24','2018-11-07 12:13:24','2018-11-07 12:13:24','d7b6af82-c1c0-4a7f-a553-7a46db535eee'),(1539,NULL,4,'Example-evidence_181107_121503.docx','word',NULL,NULL,6149,'2018-11-07 12:15:03','2018-11-07 12:15:03','2018-11-07 12:15:03','cc73f5c1-5658-491e-acb9-0fbea43c51a1'),(1541,NULL,4,'Example-evidence_181107_121521.docx','word',NULL,NULL,6149,'2018-11-07 12:15:21','2018-11-07 12:15:21','2018-11-07 12:15:21','7bb37792-2509-429a-b40d-21de0e7b457c'),(1543,NULL,4,'Example-evidence_181107_122321.docx','word',NULL,NULL,6149,'2018-11-07 12:23:21','2018-11-07 12:23:21','2018-11-07 12:23:21','aa71eeda-98c3-4d19-9eb5-de96e498a2df'),(1591,4,67,'about_us.jpg','image',1200,803,554065,'2018-11-15 12:21:05','2018-11-15 12:21:06','2018-11-15 12:21:06','02d1b7c3-c739-482c-aef4-685c1c1efc70'),(1678,NULL,73,'Koala.jpg','image',1024,768,854770,'2018-12-07 11:23:47','2018-12-07 11:23:47','2018-12-07 11:23:47','36c46810-731a-4538-924d-c2242184ef3b'),(1682,NULL,73,'Koala_181207_112804.jpg','image',1024,768,854770,'2018-12-07 11:28:04','2018-12-07 11:28:04','2018-12-07 11:28:04','21e45341-8c2c-4116-91cb-8e7976e92e94'),(1687,NULL,77,'Desert.jpg','image',1024,768,807872,'2018-12-07 12:11:52','2018-12-07 12:11:52','2018-12-07 12:11:52','8802f5c0-beb5-4009-8e03-e03462d54020'),(1895,NULL,4,'Example-evidence.txt','text',NULL,NULL,3,'2018-12-10 08:39:08','2018-12-10 08:39:09','2018-12-10 08:39:09','48579d2b-88a0-4ea7-8341-3b78cd0964ef'),(1896,NULL,4,'Example-evidence.pdf','pdf',NULL,NULL,14010,'2018-12-10 08:39:09','2018-12-10 08:39:09','2018-12-10 08:39:09','33a31def-56d4-4ecc-9a6b-05ffab14a131'),(1925,NULL,82,'dairy-milk.jpg','image',540,540,32441,'2018-12-10 09:27:51','2018-12-10 09:27:51','2018-12-10 09:27:51','1d908b8d-5a2b-4674-9a26-0e1c6daab663'),(2040,NULL,9,'current-draft.JPG','image',988,435,39858,'2018-12-11 09:30:40','2018-12-11 09:30:40','2018-12-11 09:30:40','2031f296-151e-4289-a36e-89bb07abd061'),(2047,NULL,9,'unit_181211_093556.png','image',400,400,6151,'2018-12-11 09:35:56','2018-12-11 09:35:56','2018-12-11 09:35:56','d8630dfa-9d8e-4887-9abd-4ad4d80049c9'),(2058,NULL,9,'unit.jpg','image',400,400,10416,'2018-12-11 10:01:14','2018-12-11 10:01:14','2018-12-11 10:01:14','ab344600-3c03-4756-88f2-2fd06b120b21'),(2078,NULL,73,'Desert.jpg','image',1024,768,894162,'2018-12-11 10:36:19','2018-12-11 10:36:19','2018-12-11 10:36:19','2428bb7a-ebc6-45c9-85f0-cf3a4273e345'),(2089,NULL,82,'lantra_logo.png','image',1416,308,63846,'2018-12-11 10:44:53','2018-12-11 10:44:53','2018-12-11 10:44:53','2f1da21f-dab8-4a27-8306-4e5e8896057c'),(2269,NULL,9,'unit_181212_031035.png','image',400,400,5627,'2018-12-12 03:10:35','2018-12-12 03:10:35','2018-12-12 03:10:35','7dbf27c4-00b1-41a3-bf83-326ef56d0363'),(2271,NULL,9,'unit_181212_031132.png','image',400,400,5627,'2018-12-12 03:11:32','2018-12-12 03:11:32','2018-12-12 03:11:32','bd39583a-51b2-4ebc-a2f8-3d64729896de'),(2273,NULL,9,'unit_181212_031309.png','image',400,400,5627,'2018-12-12 03:13:09','2018-12-12 03:13:09','2018-12-12 03:13:09','69e4a950-bc36-4525-9732-19b8eb582493'),(2275,NULL,9,'unit_181212_053614.jpg','image',400,400,10416,'2018-12-12 05:36:14','2018-12-12 05:36:14','2018-12-12 05:36:14','d5fbceed-d9e9-4059-93c5-df7e91474326'),(2277,NULL,9,'unit_181212_053630.png','image',400,400,5627,'2018-12-12 05:36:30','2018-12-12 05:36:30','2018-12-12 05:36:30','bfa5ee8e-f9bb-49b7-b68e-903b8f11ec8d'),(2279,NULL,9,'unit_181212_054206.png','image',400,400,5627,'2018-12-12 05:42:06','2018-12-12 05:42:06','2018-12-12 05:42:06','e7538d4e-4073-4cff-807a-212159032654'),(2281,NULL,9,'unit_181212_055558.png','image',400,400,5627,'2018-12-12 05:55:58','2018-12-12 05:55:58','2018-12-12 05:55:58','16952243-54f4-4aae-979a-8a36a1df17f1'),(2283,NULL,9,'unit_181212_055625.png','image',400,400,5627,'2018-12-12 05:56:25','2018-12-12 05:56:25','2018-12-12 05:56:25','e5047e6e-cf5a-4541-90e6-d05388fee826'),(2285,NULL,9,'unit_181212_064134.png','image',400,400,5627,'2018-12-12 06:41:34','2018-12-12 06:41:34','2018-12-12 06:41:34','805fba5b-ca43-45fe-a502-71a01a2ea6fe'),(2287,NULL,9,'unit_181212_064451.png','image',400,400,5627,'2018-12-12 06:44:51','2018-12-12 06:44:51','2018-12-12 06:44:51','b05e9df6-a9f7-45f6-aaa6-b23dc83589c8'),(2289,NULL,9,'unit_181212_064659.png','image',400,400,5627,'2018-12-12 06:46:59','2018-12-12 06:46:59','2018-12-12 06:46:59','c6cfc14f-9afd-4f00-87bf-1f3377083f73'),(2291,NULL,9,'unit_181212_064710.jpg','image',400,400,10416,'2018-12-12 06:47:10','2018-12-12 06:47:10','2018-12-12 06:47:10','eec97227-cc1e-4c08-8a76-0a07455cf2a5'),(2315,NULL,9,'unit_181212_070405.png','image',400,400,5627,'2018-12-12 07:04:05','2018-12-12 07:04:05','2018-12-12 07:04:05','51248251-26a0-4bb2-a6e2-42603b6696cc');
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
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_assetfolders`
--

LOCK TABLES `craft_assetfolders` WRITE;
/*!40000 ALTER TABLE `craft_assetfolders` DISABLE KEYS */;
INSERT INTO `craft_assetfolders` VALUES (1,NULL,1,'Evidence','','2017-10-24 10:57:32','2017-10-24 10:57:32','5568e8dd-7d3e-4d8d-8ced-5afeddb60ba8'),(2,NULL,NULL,'Temporary source',NULL,'2017-10-24 11:20:45','2017-10-24 11:20:45','f721e36e-80cc-4e92-9adf-32c1568860ff'),(3,2,NULL,'user_1','user_1/','2017-10-24 11:20:45','2017-10-24 11:20:45','df66eb36-bfe0-4a80-87f8-739a041fc407'),(4,3,NULL,'field_17','user_1/field_17/','2017-10-24 11:20:45','2017-10-24 11:20:45','b9de12f6-d1c6-4c9b-890f-6f2aef29f2e4'),(7,NULL,2,'Uploads','','2018-04-13 10:22:09','2018-04-13 10:22:09','51799375-adaa-4f52-84d9-338008463d77'),(8,2,NULL,'user_143','user_143/','2018-04-13 18:27:07','2018-04-13 18:27:07','c9c15e16-d724-4e76-94ad-97b589ee5f77'),(9,8,NULL,'field_17','user_143/field_17/','2018-04-13 18:27:07','2018-04-13 18:27:07','4ea25a1f-5443-42b2-ad65-43a47634309f'),(15,2,NULL,'user_51','user_51/','2018-04-29 14:01:19','2018-04-29 14:01:19','afd60c79-6027-4118-98f2-2c15eb13236f'),(16,15,NULL,'field_17','user_51/field_17/','2018-04-29 14:01:19','2018-04-29 14:01:19','1d33812a-533f-449b-a0bb-319e1d9cc310'),(18,2,NULL,'user_177','user_177/','2018-04-29 14:30:04','2018-04-29 14:30:04','afd7889e-bebf-419b-998f-ad4401e19fa3'),(19,18,NULL,'field_17','user_177/field_17/','2018-04-29 14:30:04','2018-04-29 14:30:04','e8a879c8-78c4-46ce-83fa-dbc7f1b5a084'),(21,2,NULL,'user_570','user_570/','2018-05-10 13:07:01','2018-05-10 13:07:01','0c0ba86b-fd9a-488f-9169-8093d3c03308'),(22,21,NULL,'field_17','user_570/field_17/','2018-05-10 13:07:01','2018-05-10 13:07:01','037cf71c-b494-4047-ab3a-5f987fab285e'),(24,2,NULL,'user_666','user_666/','2018-05-10 15:40:08','2018-05-10 15:40:08','b8cd2d95-da95-4727-8b61-55414110126a'),(25,24,NULL,'field_17','user_666/field_17/','2018-05-10 15:40:08','2018-05-10 15:40:08','2586d473-e60c-4575-acde-1e82e6d7d4fa'),(27,2,NULL,'user_691','user_691/','2018-05-10 16:50:13','2018-05-10 16:50:13','6318393f-8438-4a99-ade8-5493655d1fb1'),(28,27,NULL,'field_17','user_691/field_17/','2018-05-10 16:50:13','2018-05-10 16:50:13','d14f4667-6872-45b5-b978-98a284283441'),(30,2,NULL,'user_704','user_704/','2018-05-10 17:24:36','2018-05-10 17:24:36','f17277d4-e7e5-4f8c-bc92-df31494db8eb'),(31,30,NULL,'field_17','user_704/field_17/','2018-05-10 17:24:36','2018-05-10 17:24:36','4dc59f4e-7a34-402a-b1f4-c574e543c9d4'),(33,2,NULL,'user_727','user_727/','2018-05-11 09:32:39','2018-05-11 09:32:39','b0b3ab6a-c8bb-4719-adb8-83fe7b1746bf'),(34,33,NULL,'field_17','user_727/field_17/','2018-05-11 09:32:39','2018-05-11 09:32:39','83c2464e-6415-45fb-800b-b77c413732d6'),(36,2,NULL,'user_795','user_795/','2018-05-15 09:52:24','2018-05-15 09:52:24','45fc9c3f-fb60-4f88-814a-1e436b18c123'),(37,36,NULL,'field_17','user_795/field_17/','2018-05-15 09:52:24','2018-05-15 09:52:24','12e2a18a-22e2-40ea-b461-4e19d019695c'),(39,2,NULL,'user_801','user_801/','2018-05-15 16:03:03','2018-05-15 16:03:03','67743b2a-c452-46d8-ac36-f352a8abe44f'),(40,39,NULL,'field_17','user_801/field_17/','2018-05-15 16:03:03','2018-05-15 16:03:03','8d11988b-6408-4f96-a276-aa963a7f68fc'),(42,2,NULL,'user_802','user_802/','2018-05-15 16:13:14','2018-05-15 16:13:14','2954ce1c-4ec3-40c1-8780-6377e047f23a'),(43,42,NULL,'field_17','user_802/field_17/','2018-05-15 16:13:14','2018-05-15 16:13:14','30ed93b5-29ca-4f16-b84f-89fbd8d7e6cf'),(45,2,NULL,'user_796','user_796/','2018-05-15 16:14:45','2018-05-15 16:14:45','2b53e1ac-2e80-4251-a394-9e79d71f2d2f'),(46,45,NULL,'field_17','user_796/field_17/','2018-05-15 16:14:45','2018-05-15 16:14:45','856e2042-8639-43f0-8a7e-bc229b63e36e'),(48,2,NULL,'user_821','user_821/','2018-05-16 10:46:15','2018-05-16 10:46:15','a59b15b4-990c-4d1c-84b2-21ebc5476f1b'),(49,48,NULL,'field_17','user_821/field_17/','2018-05-16 10:46:15','2018-05-16 10:46:15','535625f0-39fa-455b-ada2-1ec806f9bd3e'),(51,2,NULL,'user_857','user_857/','2018-05-17 09:36:08','2018-05-17 09:36:08','a9a888e0-6ee1-46da-9283-c3e1f92a96d7'),(52,51,NULL,'field_17','user_857/field_17/','2018-05-17 09:36:08','2018-05-17 09:36:08','97152970-2b17-4d04-924e-506d05b46ec5'),(54,2,NULL,'user_868','user_868/','2018-05-29 08:29:16','2018-05-29 08:29:16','a5a9d092-7d42-46e4-8f8f-dd40ea63fdb0'),(55,54,NULL,'field_17','user_868/field_17/','2018-05-29 08:29:16','2018-05-29 08:29:16','e8bae52b-ec32-43d1-996f-0af1b27ef16d'),(57,2,NULL,'user_869','user_869/','2018-05-29 08:30:58','2018-05-29 08:30:58','55da642f-aaba-44ab-946c-e70341f2d91e'),(58,57,NULL,'field_17','user_869/field_17/','2018-05-29 08:30:58','2018-05-29 08:30:58','86681c43-e90b-44fe-a45a-2f5a3a97787f'),(60,NULL,3,'Data','','2018-06-02 10:40:02','2018-06-02 10:40:02','6d6d12c5-d123-463b-87c8-94e34018c0d8'),(61,2,NULL,'user_1011','user_1011/','2018-06-08 11:32:14','2018-06-08 11:32:14','a104941c-8bb4-47cc-9316-fcf4a8e2d28a'),(62,61,NULL,'field_17','user_1011/field_17/','2018-06-08 11:32:14','2018-06-08 11:32:14','a22f56b4-d4f9-4a4d-bf4d-3838fc368c35'),(64,2,NULL,'user_782','user_782/','2018-06-13 11:06:39','2018-06-13 11:06:39','ae0bff41-638a-4df9-9db1-ab63e254884d'),(65,64,NULL,'field_17','user_782/field_17/','2018-06-13 11:06:39','2018-06-13 11:06:39','e1f34d88-f3a4-4925-8b6c-f0010ef2f41d'),(67,NULL,4,'Theme','','2018-08-21 15:26:26','2018-08-21 15:26:26','196acc39-467c-4de5-b68f-b049ebe48e58'),(72,2,NULL,'user_1438','user_1438/','2018-12-07 11:23:47','2018-12-07 11:23:47','d031a7ac-d829-4619-83ac-7f517573111b'),(73,72,NULL,'field_17','user_1438/field_17/','2018-12-07 11:23:47','2018-12-07 11:23:47','c36caa83-4dd2-4a2d-82b9-0a592da91dc4'),(76,2,NULL,'user_1686','user_1686/','2018-12-07 12:11:52','2018-12-07 12:11:52','bdf88fa6-041e-4832-8b25-a3b4e60e9248'),(77,76,NULL,'field_17','user_1686/field_17/','2018-12-07 12:11:52','2018-12-07 12:11:52','c5dcb6a0-de54-44ed-afd6-d250e247330c'),(81,2,NULL,'user_1423','user_1423/','2018-12-10 09:27:50','2018-12-10 09:27:50','11637c93-219e-426f-b17e-523ad67c6b24'),(82,81,NULL,'field_17','user_1423/field_17/','2018-12-10 09:27:50','2018-12-10 09:27:50','eaa13ce9-4b11-49ef-9770-2fd2d6b10780'),(92,2,NULL,'user_1666','user_1666/','2018-12-12 00:42:37','2018-12-12 00:42:37','145e82a5-e447-48e8-88d8-ace6d8d2da99'),(93,92,NULL,'field_17','user_1666/field_17/','2018-12-12 00:42:37','2018-12-12 00:42:37','f8828389-5f3d-4c98-9297-ea088ea25e20'),(97,2,NULL,'user_2338','user_2338/','2018-12-13 10:36:45','2018-12-13 10:36:45','72290fa8-02d2-4a49-a694-257cc2817aed'),(98,97,NULL,'field_17','user_2338/field_17/','2018-12-13 10:36:45','2018-12-13 10:36:45','2aabf7a4-2c70-474c-958f-954da69ce9dc'),(100,2,NULL,'user_2355','user_2355/','2018-12-13 11:42:19','2018-12-13 11:42:19','ef761cd7-70a4-4b7b-946f-a72f3a7b5577'),(101,100,NULL,'field_17','user_2355/field_17/','2018-12-13 11:42:19','2018-12-13 11:42:19','7af889cf-e7f4-44a7-afb1-1b0c38d3b1f2');
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
INSERT INTO `craft_assetsources` VALUES (1,'Evidence','evidence','Local','{\"path\":\"craft-assets\\/evidence\\/\",\"publicURLs\":\"\",\"url\":\"\"}',1,240,'2017-10-24 10:57:32','2018-11-15 12:03:48','a6dafdf7-e4c0-4943-aff3-e481bbdf23fc'),(2,'Uploads','uploads','Local','{\"path\":\"html\\/assets\\/uploads\\/\",\"publicURLs\":\"1\",\"url\":\"\\/assets\\/uploads\\/\"}',2,239,'2018-04-13 10:22:09','2018-11-15 12:03:44','f6dc0b30-2f16-4c2d-ad2e-849d3de5d0f9'),(3,'Data','data','Local','{\"path\":\"craft-assets\\/data\\/\",\"publicURLs\":\"\",\"url\":\"\"}',3,238,'2018-06-02 10:40:02','2018-11-15 12:03:39','e1099881-d76d-4c4c-9b8b-607658d27413'),(4,'Theme','theme','Local','{\"path\":\"assets\\/theme\\/\",\"publicURLs\":\"1\",\"url\":\"\\/assets\\/theme\\/\"}',4,237,'2018-08-21 15:26:26','2018-11-15 12:03:31','f2ee622f-5058-4e7d-a3d0-1d003dc32c29');
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
INSERT INTO `craft_categories` VALUES (1478,1,'2018-11-07 11:03:08','2018-12-06 17:39:28','a2469327-0890-4be7-8790-cda8bd4d0b69'),(1624,2,'2018-11-26 17:52:08','2018-12-11 07:57:26','f65c3097-0d03-4ca0-baf6-ab96a120e234'),(1625,2,'2018-11-26 17:52:16','2018-12-11 09:36:32','ae037da4-27e3-41a9-afc1-ff2873cd0a75'),(1650,1,'2018-12-07 10:37:23','2018-12-07 10:37:23','3bcfad19-581e-42b5-950f-b1cf3044ffe6'),(1651,2,'2018-12-07 10:42:35','2018-12-07 10:42:35','0aee3fb9-ee2b-4552-9d69-231bf916451d'),(1667,2,'2018-12-07 11:10:07','2018-12-11 07:57:06','3ae31f36-fb47-4694-8efa-62f9bacfb115'),(1756,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','c5a15dbe-c9d3-4763-94fb-9b4a7a6c32ba'),(1757,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','9dba8d32-4ecf-4934-825e-988d7ce56dcf'),(1758,1,'2018-12-07 13:43:58','2018-12-07 13:52:22','dfa70ee8-b971-47fa-acf4-67dd8584ce05'),(1759,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','2e622594-9ea1-41ef-89fe-a410615bcb6f'),(1760,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','fa959cad-fe12-4878-b3e3-ddb764f9577e'),(1761,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','fab0278e-6175-4c49-87af-057e39d2db3a'),(1762,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','c224c291-0a5d-4977-acda-eac90837c372'),(2062,2,'2018-12-11 10:23:52','2018-12-13 10:19:35','18018bd5-5432-4176-a861-9c6034ddd787');
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
  `field_resultComments` text COLLATE utf8_unicode_ci,
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
  `field_schemeUserCustomFields` text COLLATE utf8_unicode_ci,
  `field_managerLevel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_unitEndorsementManagerLevel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_schemeTeams` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_managerReadOnly` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_notifyFooter` text COLLATE utf8_unicode_ci,
  `field_notifySubjectManagerSummary` text COLLATE utf8_unicode_ci,
  `field_notifySubjectEndorsementResult` text COLLATE utf8_unicode_ci,
  `field_notifySubjectBlockedResult` text COLLATE utf8_unicode_ci,
  `field_notifySubjectModuleResult` text COLLATE utf8_unicode_ci,
  `field_notifySubjectLicencesRemaining` text COLLATE utf8_unicode_ci,
  `field_notifySubjectUserExpiry` text COLLATE utf8_unicode_ci,
  `field_notifySubjectSchemeExpiry` text COLLATE utf8_unicode_ci,
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
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_content_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_content_title_idx` (`title`),
  KEY `craft_content_locale_fk` (`locale`),
  CONSTRAINT `craft_content_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_content_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1964 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_content`
--

LOCK TABLES `craft_content` WRITE;
/*!40000 ALTER TABLE `craft_content` DISABLE KEYS */;
INSERT INTO `craft_content` VALUES (1,1,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2017-10-23 13:26:42','2018-12-07 14:06:23','b5ba33c4-0e66-4812-8c20-e11a3242c2f7'),(118,143,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-03-10 15:10:18','2018-09-26 15:18:51','1339ddb9-1eb5-4db5-a6d9-93871c3af6d9'),(251,428,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-23 10:42:09','2018-04-23 10:42:09','f01a7d13-dc96-4b30-9c9c-3d2ab770dcda'),(253,430,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-23 10:42:54','2018-04-23 10:42:54','e192016b-dd65-4835-aef5-6b312b4505c1'),(255,432,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-23 10:53:59','2018-04-23 10:53:59','bfcad42c-45ce-444f-8ec5-16283e959fa3'),(258,435,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-23 10:55:59','2018-04-23 10:55:59','194bc541-0c12-4286-bcf4-17589e9119cd'),(260,437,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-23 10:56:54','2018-04-23 10:56:54','7d564773-252e-4fba-b552-295648a21add'),(263,440,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-23 11:02:32','2018-04-23 11:02:32','3ef41033-3510-4b7c-9816-6c4016a62b97'),(266,443,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-23 11:55:00','2018-04-23 11:55:00','630a80cb-d0b8-4932-96d0-5f0468532468'),(269,446,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-24 09:07:46','2018-04-24 09:07:46','64d5422f-0699-40de-8c80-4e50af062f73'),(271,448,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-24 09:11:54','2018-04-24 09:11:54','5eefbf8d-98ca-470a-b68c-921fb9275938'),(273,450,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-24 09:12:23','2018-04-24 09:12:23','c6677227-f64f-4d00-b39f-ee835470d6a2'),(292,488,'en_gb',NULL,NULL,NULL,NULL,NULL,0,1000,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,'',NULL,0,NULL,NULL,365,NULL,NULL,'<form action=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_top\">\r\n<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">\r\n<input type=\"hidden\" name=\"hosted_button_id\" value=\"RKVQSNXFXJK4G\">\r\n<input type=\"image\" src=\"https://www.sandbox.paypal.com/en_US/GB/i/btn/btn_buynowCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal – The safer, easier way to pay online!\">\r\n<img alt=\"\" border=\"0\" src=\"https://www.sandbox.paypal.com/en_GB/i/scr/pixel.gif\" width=\"1\" height=\"1\">\r\n<input type=\"hidden\" name=\"custom\" value=\"{{ currentUser.id }}\">\r\n</form>\r\n',NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-26 16:31:48','2018-12-14 18:22:10','b1c69c96-f3be-4cad-972f-95f97406d6e9'),(293,489,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,'d/m/y',10,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-29 12:39:32','2018-09-13 15:49:59','69c56cd5-0c2b-4c7b-8ea2-1baf75f76561'),(297,497,'en_gb','Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-04-29 14:01:19','2018-04-29 14:01:19','ac39c15e-5ebc-438c-9504-97b371f006d0'),(724,1014,'en_gb','Koala',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-06-08 11:32:15','2018-06-08 11:32:15','8ee6101e-6636-4f57-971a-55be86284b8a'),(787,1079,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,'Lantra Scheme',NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,'','#2e338f','#1c2b39',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-08-21 15:22:30','2018-12-14 18:23:18','d51542c1-39e0-49fb-bd83-dcbbf9b37120'),(794,1086,'en_gb','About Lantra','','Industry plays an essential part in Lantra\'s work. Our role often involves working closely with industry groups to deliver solutions to specific industry needs.',NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-08-23 14:29:32','2018-12-07 10:17:14','49137c8c-c9db-4d58-88ee-c65b492eae02'),(795,1090,'en_gb','Contact Us','','We\'re here to help. Use the form below to contact a member of the team.',NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-08-23 14:30:31','2018-11-23 15:34:38','5a8e0c4a-e1c8-4723-a13f-8330e39b4a65'),(834,1138,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'','Manager Summary','Endorsement Required','Result Blocked','Module Completed','Licences Remaining','User Expiry Date','Scheme Expiry Date',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-08-29 16:27:02','2018-09-26 14:44:48','ec4a09ab-3abd-49a6-a7c5-eaca7c2e44d1'),(876,1190,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-09-11 12:03:11','2018-09-11 12:03:11','87b288f8-c727-4502-80de-6f8689b681b9'),(886,1200,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-09-11 12:07:31','2018-09-11 12:07:31','e7e3098c-5517-45de-abee-11b067348989'),(944,1269,'en_gb','Lantra Awards Logo',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-09-26 15:02:40','2018-09-26 15:02:40','1223a596-cec6-44f6-9980-701104c2e31d'),(945,1270,'en_gb','Bground Img 1',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-09-26 15:03:18','2018-09-26 15:03:18','4121608c-212f-4207-8958-06ae2d16b2d1'),(946,1271,'en_gb','Bground Img 2',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-09-26 15:03:20','2018-09-26 15:03:20','49b6f7b3-bddf-47d3-824b-545be91b4aea'),(947,1272,'en_gb','Bground Img 3',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-09-26 15:03:22','2018-09-26 15:03:22','8f00663c-add7-4fa2-b142-b368feec19b0'),(948,1273,'en_gb','Bground Img 4',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-09-26 15:03:23','2018-09-26 15:03:23','64e0fb01-c19e-4936-a0af-dbca6d22af9b'),(1074,1423,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-10-04 15:35:59','2018-10-15 11:33:23','fcae252c-2935-46ec-8989-56332f8bac9d'),(1086,1438,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-10-11 09:02:13','2018-12-11 10:59:05','1af36f27-7549-413d-87b8-460a8764dc7b'),(1088,1440,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,'0.0',0,0,NULL,NULL,NULL,'2018-10-12 09:36:14','2018-10-12 09:36:14','65e64a6a-b2b7-42d4-be75-26cd3b902cd0'),(1123,1478,'en_gb','Test Job Role',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-11-07 11:03:07','2018-12-06 17:39:28','3fbc99e5-8251-4d24-b6ab-31007f3235c4'),(1170,1535,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-11-07 12:10:54','2018-11-07 12:10:54','56b156e6-658f-4ed2-9933-3fa041713a22'),(1172,1537,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-11-07 12:13:24','2018-11-07 12:13:24','b8d7a24a-c942-428f-aaf4-a73387a366d4'),(1174,1539,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-11-07 12:15:03','2018-11-07 12:15:03','4bf19b30-d897-43be-86f4-4d0d914ba919'),(1176,1541,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-11-07 12:15:21','2018-11-07 12:15:21','fd7ee229-d7a9-4042-89d7-05a7a2ccdfd2'),(1178,1543,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-11-07 12:23:21','2018-11-07 12:23:21','51017243-9026-4377-908d-c4cc92ad1691'),(1221,1591,'en_gb','About Us',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-11-15 12:21:06','2018-11-15 12:21:06','be0f1ff2-503b-4ce0-bd17-32dcabc63797'),(1225,1595,'en_gb','FAQs','','Learning a new skill doesn’t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.',NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-11-20 14:06:55','2018-11-23 15:46:13','73faf4c5-ec4f-40e4-b0bc-7e4ae1f8e63d'),(1248,1623,'en_gb','About Us','','About Us',NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-11-23 15:48:51','2018-12-14 18:21:38','21ed4eb0-9c54-4d79-9e2c-16d73c8428b1'),(1249,1624,'en_gb','CPD',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,1,NULL,NULL,NULL,'2018-11-26 17:52:07','2018-12-11 07:57:26','e607fcb4-f05e-400e-9f65-1aa838550628'),(1250,1625,'en_gb','Qualifications',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,1,NULL,NULL,NULL,'2018-11-26 17:52:16','2018-12-11 09:36:32','ff702805-cb1a-4a63-980b-c9dea2fe8f3a'),(1251,1626,'en_gb',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,'',0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'1',NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-06 17:47:14','2018-12-06 17:47:14','4e0c13c9-3a55-42d1-8044-dedc2ee6d08b'),(1265,1650,'en_gb','CAT A Member',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 10:37:23','2018-12-07 10:37:23','4150de5e-ee22-479a-be9e-3acb88624411'),(1266,1651,'en_gb','Centre Details',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 10:42:35','2018-12-07 10:42:35','04aa360d-92df-4271-9c61-c5e6c36ac891'),(1279,1667,'en_gb','Skills',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 11:10:07','2018-12-11 07:57:06','af438399-8a91-4dea-9c44-3636dd7e5a5c'),(1283,1678,'en_gb','Koala',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 11:23:47','2018-12-07 11:23:47','2f81eb1e-a25e-48f6-9afd-ac35aa0e0619'),(1286,1682,'en_gb','Koala',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 11:28:04','2018-12-07 11:28:04','01d5e06b-e3e9-42cc-b98c-2907beacafe4'),(1291,1687,'en_gb','Desert',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 12:11:52','2018-12-07 12:11:52','460602db-9c6d-45f5-ac85-54ae9518fd9d'),(1360,1756,'en_gb','﻿Level 1 - Egg Collector',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,49041,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 13:43:58','2018-12-07 13:43:58','6ef180eb-8801-4425-ac46-3b25be183ebf'),(1361,1757,'en_gb','Level 2 - Senior Egg Collector',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,49042,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 13:43:58','2018-12-07 13:43:58','61340a25-7790-451d-aa50-fe2a735b694f'),(1362,1758,'en_gb','Level 3 - Manager',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,49043,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 13:43:58','2018-12-07 13:52:22','0dd345e8-98d5-40f4-8d5d-0e410ddc8ba6'),(1363,1759,'en_gb','Level 1 - Egg Grader (farm)',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,49584,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 13:43:58','2018-12-07 13:43:58','84282093-d333-407a-b839-03305996deda'),(1364,1760,'en_gb','Level 2 - Stock Person',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,49586,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 13:43:58','2018-12-07 13:43:58','ac0c2005-248d-41a6-ac4a-1e025e5b10c1'),(1365,1761,'en_gb','Level 3 - Assistant Manager',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,49590,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 13:43:58','2018-12-07 13:43:58','613858bd-d8ad-4698-b76e-40a5414948f2'),(1366,1762,'en_gb','Site Admin',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,49170,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-07 13:43:58','2018-12-07 13:43:58','9978139e-b1e1-45a9-a6f4-ced5e7d571d1'),(1499,1895,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-10 08:39:08','2018-12-10 08:39:08','94142fbb-9089-4f51-a395-9ced17b706eb'),(1500,1896,'en_gb','Example Evidence',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-10 08:39:09','2018-12-10 08:39:09','d5b17bb5-83d9-4bff-b536-1bf6aca1ec4c'),(1527,1925,'en_gb','Dairy Milk',NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-10 09:27:51','2018-12-10 09:27:51','86e055c1-80d6-4f82-b5e2-ceaf8afc5f7c'),(1636,2040,'en_gb','Current Draft',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-11 09:30:40','2018-12-11 09:30:40','c7628d89-50c1-468f-827a-b5b2eb70125d'),(1643,2047,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-11 09:35:56','2018-12-11 09:35:56','698ce255-2f1b-4e10-bc5d-f90840d7661f'),(1654,2058,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-11 10:01:14','2018-12-11 10:01:14','d6dbb009-d989-4f14-b909-9a2cb94ef134'),(1658,2062,'en_gb','LTP Qualifications',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,1,NULL,NULL,NULL,'2018-12-11 10:23:52','2018-12-13 10:19:35','9f84f8a6-5347-40e4-849b-c69c3e6ee044'),(1668,2078,'en_gb','Desert',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-11 10:36:19','2018-12-11 10:36:19','6c2b0338-d09c-44b5-a9fd-dfaf748f4b61'),(1677,2089,'en_gb','Lantra Logo',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-11 10:44:53','2018-12-11 10:44:53','fea06937-c4af-4dc5-b4ec-a4642cbad4f6'),(1856,2269,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 03:10:35','2018-12-12 03:10:35','eba281e5-b4b0-4c1e-9645-3ba622115fbe'),(1858,2271,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 03:11:32','2018-12-12 03:11:32','04bff7d7-7120-4370-b84a-ff49aadbee02'),(1860,2273,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 03:13:09','2018-12-12 03:13:09','0424cfa8-961f-4d2c-ad8d-0e39d468bdac'),(1862,2275,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 05:36:14','2018-12-12 05:36:14','76fff7c2-b922-41ae-a079-c92b1dff9000'),(1864,2277,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 05:36:30','2018-12-12 05:36:30','ad2329bd-b9c1-40ca-8287-e11b134603bd'),(1866,2279,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 05:42:06','2018-12-12 05:42:06','7fdab0e8-4b37-4940-aa91-adda2c04a2c5'),(1868,2281,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 05:55:58','2018-12-12 05:55:58','e535c7da-dd78-4113-a1a3-8381184e2603'),(1870,2283,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 05:56:25','2018-12-12 05:56:25','0bc89977-3c85-40ad-ba2e-3bceb76da6b4'),(1872,2285,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 06:41:34','2018-12-12 06:41:34','bee8767c-e3ab-420c-807c-85a3c1e7d582'),(1874,2287,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 06:44:51','2018-12-12 06:44:51','7c226437-5a1b-4591-8409-d22d6f82b0f4'),(1876,2289,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 06:46:59','2018-12-12 06:46:59','f4f412cf-1e08-441c-8df4-793ef3924d61'),(1878,2291,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 06:47:10','2018-12-12 06:47:10','66697f48-7139-4c99-88b7-fa9d519e5465'),(1902,2315,'en_gb','Unit',NULL,NULL,NULL,NULL,0,0,'evidence',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,'2018-12-12 07:04:05','2018-12-12 07:04:05','ed859329-37f9-4d2b-86b4-d936a4998c3b');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_elementindexsettings`
--

LOCK TABLES `craft_elementindexsettings` WRITE;
/*!40000 ALTER TABLE `craft_elementindexsettings` DISABLE KEYS */;
INSERT INTO `craft_elementindexsettings` VALUES (1,'Entry','{\"sources\":{\"section:3\":{\"tableAttributes\":{\"1\":\"field:4\",\"2\":\"field:12\",\"3\":\"field:164\",\"4\":\"field:14\"}},\"section:5\":{\"tableAttributes\":{\"1\":\"field:7\",\"2\":\"field:10\"}},\"*\":{\"tableAttributes\":{\"1\":\"section\",\"2\":\"postDate\",\"3\":\"expiryDate\",\"4\":\"author\",\"5\":\"link\"}},\"section:10\":{\"tableAttributes\":{\"1\":\"postDate\",\"2\":\"type\",\"3\":\"field:67\",\"4\":\"field:27\",\"5\":\"field:29\",\"6\":\"field:33\",\"7\":\"author\"}},\"section:12\":{\"tableAttributes\":{\"1\":\"field:55\",\"2\":\"postDate\"}},\"section:6\":{\"tableAttributes\":{\"1\":\"field:166\",\"2\":\"postDate\",\"3\":\"expiryDate\",\"4\":\"author\",\"5\":\"link\"}}},\"sourceOrder\":[[\"key\",\"*\"],[\"heading\",\"Channels\"],[\"key\",\"section:3\"],[\"key\",\"section:5\"],[\"heading\",\"\"],[\"key\",\"section:6\"],[\"key\",\"section:7\"],[\"heading\",\"\"],[\"key\",\"section:12\"],[\"key\",\"section:10\"],[\"heading\",\"\"],[\"key\",\"section:14\"],[\"key\",\"section:13\"]]}','2017-10-23 14:56:37','2018-12-07 13:39:09','46cff6c7-fc41-4f58-9737-edf547b8fca3'),(6,'User','{\"sources\":{\"group:4\":{\"tableAttributes\":{\"1\":\"fullName\",\"2\":\"field:3\",\"3\":\"field:128\",\"4\":\"email\"}},\"*\":{\"tableAttributes\":{\"1\":\"fullName\",\"2\":\"email\",\"3\":\"dateCreated\",\"4\":\"lastLoginDate\",\"5\":\"field:3\",\"6\":\"field:128\",\"7\":\"field:30\"}}}}','2017-10-23 15:25:46','2018-12-07 13:50:31','2a6d741b-4629-46af-9ad5-9d3106f6639d'),(8,'Category','{\"sources\":{\"group:1\":{\"tableAttributes\":{\"1\":\"field:36\"}}}}','2018-02-14 12:55:14','2018-02-14 12:55:52','71a86f38-5618-4b32-9d3c-70106dab420d'),(10,'Asset','{\"sources\":{\"folder:1\":{\"tableAttributes\":{\"1\":\"filename\",\"2\":\"size\",\"3\":\"dateModified\",\"4\":\"id\"}}}}','2018-04-29 14:44:37','2018-04-29 14:44:37','d276cc4b-7e70-489d-be61-991d6b8d7980');
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
) ENGINE=InnoDB AUTO_INCREMENT=2380 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_elements`
--

LOCK TABLES `craft_elements` WRITE;
/*!40000 ALTER TABLE `craft_elements` DISABLE KEYS */;
INSERT INTO `craft_elements` VALUES (1,'User',1,0,'2017-10-23 13:26:42','2018-12-07 14:06:23','524123d7-58d3-4156-b2e4-194bdb860e15'),(143,'User',1,0,'2018-03-10 15:10:18','2018-09-26 15:18:51','b712ebaf-3929-48e9-91e2-19c8cfe36a97'),(428,'Asset',1,0,'2018-04-23 10:42:09','2018-04-23 10:42:09','9fbc263e-e4a8-431a-8f26-6bec001f9768'),(430,'Asset',1,0,'2018-04-23 10:42:54','2018-04-23 10:42:54','4e3d427f-5b4a-4811-9309-b5d0c3b7447a'),(432,'Asset',1,0,'2018-04-23 10:53:59','2018-04-23 10:53:59','52a0cc5a-27bb-4c5e-8afc-8f829ecb9795'),(435,'Asset',1,0,'2018-04-23 10:55:59','2018-04-23 10:55:59','c55dca30-fcf3-4561-b8f2-7c9189afc4da'),(437,'Asset',1,0,'2018-04-23 10:56:54','2018-04-23 10:56:54','601cace6-d578-4e99-b0da-585a6992b9c4'),(440,'Asset',1,0,'2018-04-23 11:02:32','2018-04-23 11:02:32','6d3b0f51-8200-495f-b5d2-f220307008ca'),(443,'Asset',1,0,'2018-04-23 11:55:00','2018-04-23 11:55:00','70befe02-3555-4c8b-8031-7cf1ec7a88b1'),(446,'Asset',1,0,'2018-04-24 09:07:46','2018-04-24 09:07:46','05a8202c-58e2-41c5-9b46-193027b66827'),(448,'Asset',1,0,'2018-04-24 09:11:54','2018-04-24 09:11:54','2c4b76bc-0c1f-40e7-92d8-cd492ba313db'),(450,'Asset',1,0,'2018-04-24 09:12:23','2018-04-24 09:12:23','b91113e2-b34e-4be0-af18-271efb2de451'),(488,'GlobalSet',1,0,'2018-04-26 16:31:48','2018-12-14 18:22:10','3dbe078b-ac19-44ed-9bdf-f890372262c1'),(489,'GlobalSet',1,0,'2018-04-29 12:39:31','2018-09-13 15:49:59','b26030b8-7dc6-4258-9871-8591fd3e7d2b'),(497,'Asset',1,0,'2018-04-29 14:01:19','2018-04-29 14:01:19','edce154a-f2b3-4f01-8b52-8ede6f5a939c'),(1014,'Asset',1,0,'2018-06-08 11:32:15','2018-06-08 11:32:15','fa5009f1-dee4-4460-a1c4-7ce3c6f45e6a'),(1079,'GlobalSet',1,0,'2018-08-21 15:22:30','2018-12-14 18:23:18','ba35b6ec-9035-4058-9861-57675ce5a7cf'),(1086,'Entry',1,0,'2018-08-23 14:29:32','2018-12-07 10:17:14','b3b67af4-dd09-4294-807f-4da868ecb774'),(1087,'MatrixBlock',1,0,'2018-08-23 14:29:32','2018-12-07 10:17:14','972ce54c-e7d6-4d65-b157-992514c69a63'),(1088,'MatrixBlock',1,0,'2018-08-23 14:29:32','2018-12-07 10:17:14','cbf9c229-4b7e-47b1-8d58-7108bd63591e'),(1090,'Entry',1,0,'2018-08-23 14:30:31','2018-11-23 15:34:38','631d4811-6a36-4d7c-838c-5b92865702b6'),(1091,'MatrixBlock',1,0,'2018-08-23 14:37:44','2018-12-14 18:23:18','825b35c7-5427-4996-849b-69dbfc3e5cc4'),(1138,'GlobalSet',1,0,'2018-08-29 16:27:02','2018-09-26 14:44:47','d63554c0-fadf-4659-92e8-c7469e895357'),(1190,'Asset',1,0,'2018-09-11 12:03:11','2018-09-11 12:03:11','08301291-5802-42aa-be12-5db4779577a9'),(1200,'Asset',1,0,'2018-09-11 12:07:31','2018-09-11 12:07:31','26850877-4522-4c84-8ba2-60c2f4ae61a3'),(1269,'Asset',1,0,'2018-09-26 15:02:40','2018-09-26 15:02:40','e86fcf66-ba14-40cc-9355-26e6687c6155'),(1270,'Asset',1,0,'2018-09-26 15:03:18','2018-09-26 15:03:18','85f0cc13-064a-46ae-856c-cb0d77821673'),(1271,'Asset',1,0,'2018-09-26 15:03:20','2018-09-26 15:03:20','812ee6c8-df10-4566-9105-24ba72c9f7b2'),(1272,'Asset',1,0,'2018-09-26 15:03:22','2018-09-26 15:03:22','01aad2a1-440c-4a0d-8253-caf75da79179'),(1273,'Asset',1,0,'2018-09-26 15:03:23','2018-09-26 15:03:23','7dd64050-3e20-497a-82b7-984ddb406488'),(1423,'User',1,0,'2018-10-04 15:35:59','2018-10-15 11:33:23','909c7412-43e2-4158-bd60-def1f077e468'),(1438,'User',1,0,'2018-10-11 09:02:13','2018-12-11 10:59:05','d6e079ba-0c6c-449a-9970-53e41f187ba0'),(1440,'User',1,0,'2018-10-12 09:36:14','2018-10-12 09:36:14','9d9ee405-fd93-4c35-b756-0d0239e2e0f8'),(1478,'Category',1,0,'2018-11-07 11:03:07','2018-12-06 17:39:28','3351e49c-3aab-420c-9e64-c513a6e2a30e'),(1535,'Asset',1,0,'2018-11-07 12:10:54','2018-11-07 12:10:54','8004aa12-754b-4230-b042-c6c9b489aef6'),(1537,'Asset',1,0,'2018-11-07 12:13:24','2018-11-07 12:13:24','22fe7790-84d0-4357-bd13-51474f18a7e9'),(1539,'Asset',1,0,'2018-11-07 12:15:03','2018-11-07 12:15:03','4245cd14-1328-45e3-a4ad-ac318eed9696'),(1541,'Asset',1,0,'2018-11-07 12:15:21','2018-11-07 12:15:21','fe99c208-9ef0-48e5-a207-fc1214b548ff'),(1543,'Asset',1,0,'2018-11-07 12:23:21','2018-11-07 12:23:21','ab4a9291-95f7-4b45-8cb2-0acd5ddf4440'),(1591,'Asset',1,0,'2018-11-15 12:21:06','2018-11-15 12:21:06','c1e4537d-1b4e-43de-a927-27c0683e6dbc'),(1595,'Entry',1,0,'2018-11-20 14:06:55','2018-11-23 15:46:13','26f8fe10-b554-4d4d-a6a3-25d7caa8718f'),(1620,'MatrixBlock',1,0,'2018-11-23 11:08:56','2018-11-23 15:34:38','a98716f2-71c5-4b4e-b823-35fa2ba37997'),(1623,'Entry',1,0,'2018-11-23 15:48:51','2018-12-14 18:21:38','b3d1e674-447a-432e-9313-30091ac448dc'),(1624,'Category',1,0,'2018-11-26 17:52:07','2018-12-11 07:57:26','e72f8297-093e-4ee5-97af-7c226afcc786'),(1625,'Category',1,0,'2018-11-26 17:52:16','2018-12-11 09:36:32','801dd181-38d4-4ad5-b9a2-4a082c326164'),(1626,'User',1,0,'2018-12-06 17:47:14','2018-12-06 17:47:14','c0ee8c7f-d17d-4b62-bfbb-1b2d3bca999d'),(1630,'SuperTable_Block',1,0,'2018-12-06 17:49:33','2018-12-11 09:36:32','509ba700-ab4a-480f-8c63-1c285a91a01b'),(1631,'SuperTable_Block',1,0,'2018-12-06 17:49:33','2018-12-11 09:36:32','debae42e-62fb-4be2-9865-f45ec5264a09'),(1632,'SuperTable_Block',1,0,'2018-12-06 17:49:33','2018-12-11 09:36:32','4c87dfd2-6544-4ee2-96bb-e6a884da959c'),(1633,'SuperTable_Block',1,0,'2018-12-06 17:49:33','2018-12-11 09:36:32','7cb9617f-f51b-4e86-a63b-f1e7228294b4'),(1634,'SuperTable_Block',1,0,'2018-12-06 17:49:33','2018-12-11 09:36:32','83cc204f-426e-4328-912a-ee9999d8a3dc'),(1635,'SuperTable_Block',1,0,'2018-12-06 17:49:33','2018-12-11 09:36:32','4416e6a8-8619-438d-8998-c74238983f66'),(1637,'SuperTable_Block',1,0,'2018-12-06 17:49:33','2018-12-11 09:36:32','dcaa6ace-6193-499f-b123-1c5344c8166e'),(1650,'Category',1,0,'2018-12-07 10:37:23','2018-12-07 10:37:23','17ff0651-df1a-4c21-91ad-c1726426a468'),(1651,'Category',1,0,'2018-12-07 10:42:35','2018-12-07 10:42:35','7d1356ad-da26-487e-b016-343ae73d9fcf'),(1667,'Category',1,0,'2018-12-07 11:10:07','2018-12-11 07:57:06','5fa1141c-7c95-40da-ab03-c4da44384a02'),(1675,'SuperTable_Block',1,0,'2018-12-07 11:22:16','2018-12-11 07:57:06','d00140cf-1fba-438a-aadf-934f1d9d3b66'),(1676,'SuperTable_Block',1,0,'2018-12-07 11:22:16','2018-12-11 07:57:06','a26ea347-1f5c-48fb-b5ba-1d98e841178c'),(1677,'SuperTable_Block',1,0,'2018-12-07 11:22:16','2018-12-11 07:57:06','522ae216-5ef9-4527-8635-cb76a07e13cf'),(1678,'Asset',1,0,'2018-12-07 11:23:47','2018-12-07 11:23:47','9b9019dd-7aa7-4415-8d3c-292ae3d23fad'),(1682,'Asset',1,0,'2018-12-07 11:28:04','2018-12-07 11:28:04','900ef5cf-4991-4d9d-bfd1-238ab39bad6e'),(1687,'Asset',1,0,'2018-12-07 12:11:52','2018-12-07 12:11:52','357a0b01-24b7-4db6-9ec1-2f0b99388ad7'),(1756,'Category',1,0,'2018-12-07 13:43:58','2018-12-07 13:43:58','6c02f48f-049e-41f0-ac68-78919a5fc96a'),(1757,'Category',1,0,'2018-12-07 13:43:58','2018-12-07 13:43:58','a616f819-691a-47a6-b9e8-bb54f5210212'),(1758,'Category',1,0,'2018-12-07 13:43:58','2018-12-07 13:52:22','0171a1f5-8b32-42c9-bc55-fe7733da41a7'),(1759,'Category',1,0,'2018-12-07 13:43:58','2018-12-07 13:43:58','c3d93907-8c6d-4483-a582-38563e224890'),(1760,'Category',1,0,'2018-12-07 13:43:58','2018-12-07 13:43:58','b3cb0901-a64f-45e1-8557-17a4098dfbb1'),(1761,'Category',1,0,'2018-12-07 13:43:58','2018-12-07 13:43:58','f91dfad8-3429-45b0-a5fb-add5405856c2'),(1762,'Category',1,0,'2018-12-07 13:43:58','2018-12-07 13:43:58','8fdec7b7-406d-4bd4-8fd5-fc74ba7abd66'),(1895,'Asset',1,0,'2018-12-10 08:39:08','2018-12-10 08:39:08','5e5d8d76-1653-4b2f-9dfa-c6347e7f78e2'),(1896,'Asset',1,0,'2018-12-10 08:39:09','2018-12-10 08:39:09','9cfcbc9f-26ef-4ca3-b8f5-9621a1ff27fa'),(1923,'MatrixBlock',1,0,'2018-12-10 09:11:48','2018-12-14 18:21:38','363368d6-371b-42ef-bfdf-09daa89c3a33'),(1925,'Asset',1,0,'2018-12-10 09:27:51','2018-12-10 09:27:51','82fe526e-3aa0-476c-88fb-9764faf7dfe9'),(1940,'SuperTable_Block',1,0,'2018-12-10 09:33:53','2018-12-11 07:57:26','568dd9b0-da8b-4d34-8825-25859dfafa43'),(1941,'SuperTable_Block',1,0,'2018-12-10 09:33:53','2018-12-11 07:57:26','07027a79-a140-4ce7-89b9-e11329afc5eb'),(1942,'SuperTable_Block',1,0,'2018-12-10 09:33:53','2018-12-11 07:57:26','cb476a62-2ffd-47a6-9af2-01ed53bab491'),(1943,'SuperTable_Block',1,0,'2018-12-10 09:33:53','2018-12-11 07:57:26','fdfa0780-a513-4c2e-9421-28ddb74edc82'),(1944,'SuperTable_Block',1,0,'2018-12-10 09:33:53','2018-12-11 07:57:26','138e2edb-e8d2-4b2c-8be1-b3a368cfeba8'),(2040,'Asset',1,0,'2018-12-11 09:30:40','2018-12-11 09:30:40','a1b49509-907f-4622-96c3-ecb69db4cc91'),(2047,'Asset',1,0,'2018-12-11 09:35:56','2018-12-11 09:35:56','27fcca96-84e8-4a19-bff3-594a9c8bffb5'),(2058,'Asset',1,0,'2018-12-11 10:01:14','2018-12-11 10:01:14','98a3591b-1350-4d9e-881e-701e210fb683'),(2062,'Category',1,0,'2018-12-11 10:23:52','2018-12-13 10:19:35','441b18ea-a605-4395-b4a1-76632c630f42'),(2063,'SuperTable_Block',1,0,'2018-12-11 10:23:52','2018-12-13 10:19:35','ccf8bfda-6ccf-4fbd-88b6-9bd735a5d239'),(2064,'SuperTable_Block',1,0,'2018-12-11 10:23:52','2018-12-13 10:19:35','7ce0392e-3d7e-4d22-b324-43d34c555cd3'),(2065,'SuperTable_Block',1,0,'2018-12-11 10:23:52','2018-12-13 10:19:35','adc4c06b-0c0a-4d0a-ba7f-e14c35f54c4c'),(2066,'SuperTable_Block',1,0,'2018-12-11 10:23:52','2018-12-13 10:19:35','641fbafa-df7f-4ff5-be4e-090868b56b4a'),(2067,'SuperTable_Block',1,0,'2018-12-11 10:23:52','2018-12-13 10:19:35','a391700e-90ae-4eb0-ba62-7c639ab41ce6'),(2078,'Asset',1,0,'2018-12-11 10:36:19','2018-12-11 10:36:19','85daddf2-cf78-4625-aee6-ac519099af78'),(2089,'Asset',1,0,'2018-12-11 10:44:53','2018-12-11 10:44:53','5439aed2-c705-4679-b7f4-96b014dc778c'),(2269,'Asset',1,0,'2018-12-12 03:10:35','2018-12-12 03:10:35','3ab955c8-6002-4bfb-a721-7356c0585d5b'),(2271,'Asset',1,0,'2018-12-12 03:11:32','2018-12-12 03:11:32','9ebbf530-9a1f-4384-baa5-f3048452da83'),(2273,'Asset',1,0,'2018-12-12 03:13:09','2018-12-12 03:13:09','4879608f-530d-4044-a9d2-83abc9cf2dd6'),(2275,'Asset',1,0,'2018-12-12 05:36:14','2018-12-12 05:36:14','f3e75901-9478-40b5-9da2-24f445012591'),(2277,'Asset',1,0,'2018-12-12 05:36:30','2018-12-12 05:36:30','36063254-670e-44e6-a332-da9ce8a75767'),(2279,'Asset',1,0,'2018-12-12 05:42:06','2018-12-12 05:42:06','5ae906ab-436b-41e5-a9dc-e99615e065ab'),(2281,'Asset',1,0,'2018-12-12 05:55:58','2018-12-12 05:55:58','444902a1-1338-4684-b181-125897c772ea'),(2283,'Asset',1,0,'2018-12-12 05:56:25','2018-12-12 05:56:25','16982ded-8df2-4901-9a5b-c08b9a453ee7'),(2285,'Asset',1,0,'2018-12-12 06:41:34','2018-12-12 06:41:34','f7487f78-8d0e-4d7e-b48f-29b22f41e53c'),(2287,'Asset',1,0,'2018-12-12 06:44:51','2018-12-12 06:44:51','cb1c43ba-5158-40d5-a400-59d8fed108cf'),(2289,'Asset',1,0,'2018-12-12 06:46:59','2018-12-12 06:46:59','7dc2a6c9-123b-4c8d-9a5c-07c68eb56de1'),(2291,'Asset',1,0,'2018-12-12 06:47:10','2018-12-12 06:47:10','fcb1e53d-de25-4842-889c-5fd0bc3481ae'),(2315,'Asset',1,0,'2018-12-12 07:04:05','2018-12-12 07:04:05','03a7bb8f-0a21-4676-9e18-e9188788ee3b'),(2342,'SuperTable_Block',1,0,'2018-12-13 09:01:03','2018-12-13 10:19:35','8b7f76ce-ff6a-4f1f-8192-222688e99d5c'),(2343,'SuperTable_Block',1,0,'2018-12-13 09:01:03','2018-12-13 10:19:35','42331513-124a-43d3-8fd5-782d00b68e00');
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
) ENGINE=InnoDB AUTO_INCREMENT=2380 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_elements_i18n`
--

LOCK TABLES `craft_elements_i18n` WRITE;
/*!40000 ALTER TABLE `craft_elements_i18n` DISABLE KEYS */;
INSERT INTO `craft_elements_i18n` VALUES (1,1,'en_gb','',NULL,1,'2017-10-23 13:26:42','2018-12-07 14:06:23','55278b20-342c-40a8-b2b7-e25032c0539c'),(143,143,'en_gb','',NULL,1,'2018-03-10 15:10:18','2018-09-26 15:18:51','cff619e3-294f-44e5-a532-017c766df960'),(428,428,'en_gb','evidence',NULL,1,'2018-04-23 10:42:09','2018-04-23 10:42:09','ee28c735-9842-45c0-be0e-0947e5707b94'),(430,430,'en_gb','evidence',NULL,1,'2018-04-23 10:42:54','2018-04-23 10:42:54','87239c12-2058-48a3-8dd7-99f04e4523d7'),(432,432,'en_gb','evidence',NULL,1,'2018-04-23 10:53:59','2018-04-23 10:53:59','63f3920e-3ddd-4334-9157-cb39602ff40f'),(435,435,'en_gb','evidence',NULL,1,'2018-04-23 10:55:59','2018-04-23 10:55:59','d9c8e3a1-f99b-43f7-b6bd-a9a66bc6ee58'),(437,437,'en_gb','evidence',NULL,1,'2018-04-23 10:56:54','2018-04-23 10:56:54','b567e30a-0cd1-4c7f-a0d2-136fbebf646b'),(440,440,'en_gb','evidence',NULL,1,'2018-04-23 11:02:32','2018-04-23 11:02:32','31e815c7-88a9-4ac1-8e90-2bc09f1d7bfc'),(443,443,'en_gb','evidence',NULL,1,'2018-04-23 11:55:00','2018-04-23 11:55:00','4b30da7b-5d2b-49dd-b369-9558fad7c1cd'),(446,446,'en_gb','evidence',NULL,1,'2018-04-24 09:07:47','2018-04-24 09:07:47','cab5c199-38bc-4574-bbf0-61def86f9729'),(448,448,'en_gb','evidence',NULL,1,'2018-04-24 09:11:55','2018-04-24 09:11:55','77fc99f9-2057-427d-8e89-1a324eee6568'),(450,450,'en_gb','evidence',NULL,1,'2018-04-24 09:12:24','2018-04-24 09:12:24','f0c68b3d-1b24-483c-ad3b-7c6c08b8cd03'),(488,488,'en_gb','',NULL,1,'2018-04-26 16:31:48','2018-12-14 18:22:10','c7ce7030-bc33-4088-8e14-67b1159432a1'),(489,489,'en_gb','',NULL,1,'2018-04-29 12:39:32','2018-09-13 15:49:59','65075589-0e0d-4db0-aca4-dbc0a5256d54'),(497,497,'en_gb','evidence',NULL,1,'2018-04-29 14:01:19','2018-04-29 14:01:19','ab92c7a8-12e7-447e-a680-fd0e27f5f752'),(1014,1014,'en_gb','koala',NULL,1,'2018-06-08 11:32:15','2018-06-08 11:32:15','afd84ab4-f352-45a4-a5cf-e860186980f6'),(1079,1079,'en_gb','',NULL,1,'2018-08-21 15:22:30','2018-12-14 18:23:18','d1439197-cc47-4ed9-a2d0-14c6ae27102f'),(1086,1086,'en_gb','about-lantra','public/about-lantra',1,'2018-08-23 14:29:32','2018-12-07 10:17:14','642c9c51-3c70-4cbb-9a0e-b5ffb21e9628'),(1087,1087,'en_gb','',NULL,1,'2018-08-23 14:29:32','2018-12-07 10:17:14','b03da23a-d8c6-401b-87bd-22cea0888ed1'),(1088,1088,'en_gb','',NULL,1,'2018-08-23 14:29:32','2018-12-07 10:17:14','ccec872f-81c9-4c6f-a4b4-dc432de02824'),(1090,1090,'en_gb','contact-us','public/contact-us',1,'2018-08-23 14:30:31','2018-11-23 15:34:38','a35d938c-83b1-444f-840c-a863b3abedfe'),(1091,1091,'en_gb','',NULL,1,'2018-08-23 14:37:44','2018-12-14 18:23:18','1fc06123-1d59-4b7d-bfd9-4d2625c83eed'),(1138,1138,'en_gb','',NULL,1,'2018-08-29 16:27:02','2018-09-26 14:44:48','ec88f430-d197-4fb2-8a9f-f6e5715ec4f0'),(1190,1190,'en_gb','unit',NULL,1,'2018-09-11 12:03:11','2018-09-11 12:03:11','0bb82439-3b05-4deb-b107-b1f619de89c1'),(1200,1200,'en_gb','unit',NULL,1,'2018-09-11 12:07:31','2018-09-11 12:07:31','94c33fda-95ff-484c-931d-35085ca3429c'),(1269,1269,'en_gb','lantra-awards-logo',NULL,1,'2018-09-26 15:02:40','2018-09-26 15:02:40','1a633da2-4f6a-4eda-aadf-215b966c250a'),(1270,1270,'en_gb','bground-img-1',NULL,1,'2018-09-26 15:03:18','2018-09-26 15:03:18','78c4cfe2-6ea6-48c0-a93a-0bebe776b142'),(1271,1271,'en_gb','bground-img-2',NULL,1,'2018-09-26 15:03:20','2018-09-26 15:03:20','7b5e9190-28e3-4bed-b3a8-7ede42b19650'),(1272,1272,'en_gb','bground-img-3',NULL,1,'2018-09-26 15:03:22','2018-09-26 15:03:22','76b1c220-75a5-4b0a-a0ed-3014d1141718'),(1273,1273,'en_gb','bground-img-4',NULL,1,'2018-09-26 15:03:23','2018-09-26 15:03:23','56ff6b4e-eac6-4c65-ae4f-453a0851ac6b'),(1423,1423,'en_gb','',NULL,1,'2018-10-04 15:36:00','2018-10-15 11:33:23','f58da1d3-427d-4ea4-b042-25f0d2df682c'),(1438,1438,'en_gb','',NULL,1,'2018-10-11 09:02:13','2018-12-11 10:59:05','762b4f31-5411-42b5-a423-984534f73068'),(1440,1440,'en_gb','',NULL,1,'2018-10-12 09:36:14','2018-10-12 09:36:14','4bcecb3e-6df0-4652-81ce-c59c2537e8a6'),(1478,1478,'en_gb','test-job-role',NULL,1,'2018-11-07 11:03:07','2018-12-06 17:39:28','3b37e3af-aaaf-49ce-af41-243c4ae09aca'),(1535,1535,'en_gb','example-evidence',NULL,1,'2018-11-07 12:10:54','2018-11-07 12:10:54','40ffc3e9-59a5-4bd9-aab0-82e289cd8ca5'),(1537,1537,'en_gb','example-evidence',NULL,1,'2018-11-07 12:13:24','2018-11-07 12:13:24','48cdae32-23ec-4613-ac84-5d8760a0242b'),(1539,1539,'en_gb','example-evidence',NULL,1,'2018-11-07 12:15:03','2018-11-07 12:15:03','76edce40-a748-4a0a-92ad-5b3c11908745'),(1541,1541,'en_gb','example-evidence',NULL,1,'2018-11-07 12:15:21','2018-11-07 12:15:21','bec60166-c481-4cab-8fff-b668dc967dfd'),(1543,1543,'en_gb','example-evidence',NULL,1,'2018-11-07 12:23:21','2018-11-07 12:23:21','066a6a17-bff9-46ab-ad95-a9429d7dcc8b'),(1591,1591,'en_gb','about-us',NULL,1,'2018-11-15 12:21:06','2018-11-15 12:21:06','aa1b3e35-74c5-4111-9ab7-2adc494250f2'),(1595,1595,'en_gb','faqs','public/faqs',1,'2018-11-20 14:06:55','2018-11-23 15:46:13','83cd20e5-be70-4d93-aace-b3fd7d8ee8de'),(1620,1620,'en_gb','',NULL,1,'2018-11-23 11:08:56','2018-11-23 15:34:38','9262bb0c-b3de-4eec-ae9f-f92d29541051'),(1623,1623,'en_gb','about-us','public/about-us',1,'2018-11-23 15:48:51','2018-12-14 18:21:38','b6f1b2d9-58ee-49ee-af9c-771f9c977b29'),(1624,1624,'en_gb','cpd',NULL,1,'2018-11-26 17:52:08','2018-12-11 07:57:26','74cc3017-a912-47a1-83f0-e97b1702a55c'),(1625,1625,'en_gb','qualifications',NULL,1,'2018-11-26 17:52:16','2018-12-11 09:36:32','8c883408-88b2-4b52-aeda-f381a9d79a1b'),(1626,1626,'en_gb','',NULL,1,'2018-12-06 17:47:14','2018-12-06 17:47:14','bad60079-9b40-44ab-b66b-26ce910b4323'),(1630,1630,'en_gb','',NULL,1,'2018-12-06 17:49:33','2018-12-11 09:36:32','b403407e-1ab7-4ec8-b0e5-ea3168005865'),(1631,1631,'en_gb','',NULL,1,'2018-12-06 17:49:33','2018-12-11 09:36:32','34b80b96-9aef-4355-83af-560597aae4bc'),(1632,1632,'en_gb','',NULL,1,'2018-12-06 17:49:33','2018-12-11 09:36:32','42944586-e794-479e-a9d4-7c2adea44729'),(1633,1633,'en_gb','',NULL,1,'2018-12-06 17:49:33','2018-12-11 09:36:32','3e00fe25-4909-4084-9aae-38c5019db005'),(1634,1634,'en_gb','',NULL,1,'2018-12-06 17:49:33','2018-12-11 09:36:32','0dade472-5014-4ecf-a750-dc15bd060b05'),(1635,1635,'en_gb','',NULL,1,'2018-12-06 17:49:33','2018-12-11 09:36:32','b3e553a9-3306-4999-b16c-d6a4a6cf1b65'),(1637,1637,'en_gb','',NULL,1,'2018-12-06 17:49:33','2018-12-11 09:36:32','e57a6d9c-933d-444c-a81c-89a057cc7174'),(1650,1650,'en_gb','cat-a-member',NULL,1,'2018-12-07 10:37:23','2018-12-07 10:37:23','83c011b8-bb28-4e16-90bd-614fa2a45e72'),(1651,1651,'en_gb','centre-details',NULL,1,'2018-12-07 10:42:35','2018-12-07 10:42:35','34682669-6af8-442c-9845-b17c25fc19a2'),(1667,1667,'en_gb','skills',NULL,1,'2018-12-07 11:10:07','2018-12-11 07:57:06','9162f712-66ba-4e59-a703-16fefaef55de'),(1675,1675,'en_gb','',NULL,1,'2018-12-07 11:22:16','2018-12-11 07:57:06','c7c8fe74-5bd7-4259-8645-c68f3197065d'),(1676,1676,'en_gb','',NULL,1,'2018-12-07 11:22:16','2018-12-11 07:57:06','95a2fa69-fc8a-4d4c-8366-ca3f54a7c811'),(1677,1677,'en_gb','',NULL,1,'2018-12-07 11:22:16','2018-12-11 07:57:06','995ea2a4-7191-4a09-8cd3-5ed19cc5befa'),(1678,1678,'en_gb','koala',NULL,1,'2018-12-07 11:23:47','2018-12-07 11:23:47','ec227f5f-8aca-451e-a9f2-8291e4020a0d'),(1682,1682,'en_gb','koala',NULL,1,'2018-12-07 11:28:04','2018-12-07 11:28:04','fb1dc075-f67d-4ed3-97b9-fe2753984d94'),(1687,1687,'en_gb','desert',NULL,1,'2018-12-07 12:11:52','2018-12-07 12:11:52','80275b07-e379-42d9-a993-467370c53f0d'),(1756,1756,'en_gb','level-1-egg-collector',NULL,1,'2018-12-07 13:43:58','2018-12-07 13:43:59','07d0e33b-6ee4-4939-b8e9-41d078823e35'),(1757,1757,'en_gb','level-2-senior-egg-collector',NULL,1,'2018-12-07 13:43:58','2018-12-07 13:43:59','397cabf5-12a7-4fcb-9d1c-6d2405c5ee8a'),(1758,1758,'en_gb','level-3-manager',NULL,1,'2018-12-07 13:43:58','2018-12-07 13:52:22','2cde69fd-c6e2-4f7c-bb04-5a68ce6f2408'),(1759,1759,'en_gb','level-1-egg-grader-farm',NULL,1,'2018-12-07 13:43:58','2018-12-07 13:43:59','29a488f0-ea8a-4d2f-bf63-ce93bb93de4e'),(1760,1760,'en_gb','level-2-stock-person',NULL,1,'2018-12-07 13:43:58','2018-12-07 13:43:59','0b5e0ac1-afd6-4d84-ba34-8f34ef63ec60'),(1761,1761,'en_gb','level-3-assistant-manager',NULL,1,'2018-12-07 13:43:58','2018-12-07 13:43:59','9f213295-c3cc-48b3-8f7f-79893ab9892f'),(1762,1762,'en_gb','site-admin',NULL,1,'2018-12-07 13:43:58','2018-12-07 13:43:59','4801c135-4662-4c9b-8037-0842cfaacf26'),(1895,1895,'en_gb','example-evidence',NULL,1,'2018-12-10 08:39:09','2018-12-10 08:39:09','69f48b2f-0724-4eee-95d0-792776818121'),(1896,1896,'en_gb','example-evidence',NULL,1,'2018-12-10 08:39:09','2018-12-10 08:39:09','fc58a3a9-7c7f-4da4-b0f2-7bda30f202e7'),(1923,1923,'en_gb','',NULL,1,'2018-12-10 09:11:48','2018-12-14 18:21:38','8002e591-d044-4b99-a34d-1e9cfd0faf17'),(1925,1925,'en_gb','dairy-milk',NULL,1,'2018-12-10 09:27:51','2018-12-10 09:27:51','6ed700f3-f6c0-433c-984b-70b118c2590f'),(1940,1940,'en_gb','',NULL,1,'2018-12-10 09:33:53','2018-12-11 07:57:26','995e6360-f61e-439d-bbdf-d74982407478'),(1941,1941,'en_gb','',NULL,1,'2018-12-10 09:33:53','2018-12-11 07:57:26','f8ba8cf9-f31e-49b6-aefa-004e2c4a0f33'),(1942,1942,'en_gb','',NULL,1,'2018-12-10 09:33:53','2018-12-11 07:57:26','5f7f0ac1-6115-440e-82a0-a98e2a28019e'),(1943,1943,'en_gb','',NULL,1,'2018-12-10 09:33:53','2018-12-11 07:57:26','14fa14ec-0dc3-44ba-82aa-dd4a7e03e4a0'),(1944,1944,'en_gb','',NULL,1,'2018-12-10 09:33:53','2018-12-11 07:57:26','821aa004-4a54-4781-ab47-4c84b0bd4430'),(2040,2040,'en_gb','current-draft',NULL,1,'2018-12-11 09:30:40','2018-12-11 09:30:40','fa469a83-7510-43ee-aa65-80b3797e4d22'),(2047,2047,'en_gb','unit',NULL,1,'2018-12-11 09:35:56','2018-12-11 09:35:56','45e0289b-fb16-4394-842d-835fab887c08'),(2058,2058,'en_gb','unit',NULL,1,'2018-12-11 10:01:14','2018-12-11 10:01:14','97cdf0b6-c237-40ab-b7ab-c999b9916b9b'),(2062,2062,'en_gb','ltp-qualifications',NULL,1,'2018-12-11 10:23:52','2018-12-13 10:19:35','2f969321-74f5-43d8-9712-69f0b215380a'),(2063,2063,'en_gb','',NULL,1,'2018-12-11 10:23:52','2018-12-13 10:19:35','b1ef38da-94b6-42f1-b938-a2448369d8e3'),(2064,2064,'en_gb','',NULL,1,'2018-12-11 10:23:52','2018-12-13 10:19:35','4309784d-f12f-4209-a265-261d1ff63072'),(2065,2065,'en_gb','',NULL,1,'2018-12-11 10:23:52','2018-12-13 10:19:35','e8316731-3440-4431-981d-26a4d6a5528a'),(2066,2066,'en_gb','',NULL,1,'2018-12-11 10:23:52','2018-12-13 10:19:35','2aa7b139-d46b-46c9-b7de-dcdcb67060d7'),(2067,2067,'en_gb','',NULL,1,'2018-12-11 10:23:52','2018-12-13 10:19:35','b29209a6-d2be-4f31-a50c-4a8a8e7a9eae'),(2078,2078,'en_gb','desert',NULL,1,'2018-12-11 10:36:19','2018-12-11 10:36:19','9b4a8123-fe4d-475f-ae0b-8c1fe01a7cd1'),(2089,2089,'en_gb','lantra-logo',NULL,1,'2018-12-11 10:44:53','2018-12-11 10:44:53','14c880a6-4a75-4b72-96c7-95241a73c79b'),(2269,2269,'en_gb','unit',NULL,1,'2018-12-12 03:10:35','2018-12-12 03:10:35','fab83a51-f5f5-4ea8-bcc9-bfcdea847431'),(2271,2271,'en_gb','unit',NULL,1,'2018-12-12 03:11:32','2018-12-12 03:11:32','22ada2cd-6c0b-4996-b082-b901d7eaba40'),(2273,2273,'en_gb','unit',NULL,1,'2018-12-12 03:13:09','2018-12-12 03:13:09','d7f96ba4-2355-4e57-a318-de214a3a0d5a'),(2275,2275,'en_gb','unit',NULL,1,'2018-12-12 05:36:14','2018-12-12 05:36:14','b1b0de79-c9f3-4655-8f38-811beb3508da'),(2277,2277,'en_gb','unit',NULL,1,'2018-12-12 05:36:30','2018-12-12 05:36:30','6115fae0-9449-48d3-a03b-1a71eb6d4b8b'),(2279,2279,'en_gb','unit',NULL,1,'2018-12-12 05:42:06','2018-12-12 05:42:06','2d3ee5cb-9543-4e77-8b35-f8b54a530c4b'),(2281,2281,'en_gb','unit',NULL,1,'2018-12-12 05:55:58','2018-12-12 05:55:58','ef131920-5fc2-4396-9eeb-745b8fd1b54d'),(2283,2283,'en_gb','unit',NULL,1,'2018-12-12 05:56:25','2018-12-12 05:56:25','fb6c7045-f441-4733-96f7-2a2bf17f8f79'),(2285,2285,'en_gb','unit',NULL,1,'2018-12-12 06:41:34','2018-12-12 06:41:34','9f51d1aa-b564-4c9c-a0d5-3454f854bede'),(2287,2287,'en_gb','unit',NULL,1,'2018-12-12 06:44:51','2018-12-12 06:44:51','7cb9e309-7f94-42ad-9104-492039d84e6d'),(2289,2289,'en_gb','unit',NULL,1,'2018-12-12 06:46:59','2018-12-12 06:46:59','d56c070b-95d1-4be3-a7a0-caf818074c71'),(2291,2291,'en_gb','unit',NULL,1,'2018-12-12 06:47:10','2018-12-12 06:47:10','61833e30-afb3-4aac-af50-b1a79e965e03'),(2315,2315,'en_gb','unit',NULL,1,'2018-12-12 07:04:05','2018-12-12 07:04:05','d28f81f3-19de-423a-937c-313d0d7627da'),(2342,2342,'en_gb','',NULL,1,'2018-12-13 09:01:03','2018-12-13 10:19:35','29a65056-3d0a-4a96-980b-0aa25cc412fd'),(2343,2343,'en_gb','',NULL,1,'2018-12-13 09:01:03','2018-12-13 10:19:35','6d80d8fa-643d-4bdd-b885-c2cfd773c52b');
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
INSERT INTO `craft_entries` VALUES (1086,14,16,1,'2018-08-23 14:29:00',NULL,'2018-08-23 14:29:32','2018-12-07 10:17:14','d84d3134-dad4-4daf-b1fd-00003773bb47'),(1090,14,16,1,'2018-08-23 14:30:00',NULL,'2018-08-23 14:30:31','2018-11-23 15:34:38','763592ea-f057-4189-8ff1-599684ac000a'),(1595,14,16,1,'2018-11-20 14:06:00',NULL,'2018-11-20 14:06:56','2018-11-23 15:46:13','b59ecc66-c634-4d48-a935-015f20c611ba'),(1623,14,16,1,'2018-11-23 15:48:00',NULL,'2018-11-23 15:48:51','2018-12-14 18:21:39','a1c1ba71-3f52-4bdf-8c0c-4423b747501f');
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
INSERT INTO `craft_entrytypes` VALUES (3,3,231,'Company','company',1,'Title',NULL,1,'2017-10-23 13:43:33','2018-10-16 13:49:35','2fbdc981-206f-44f9-9f39-fdb605cff8f2'),(5,5,71,'Team','team',0,NULL,'{teamCompany.first.title} - {teamName}',1,'2017-10-23 14:46:37','2018-03-07 14:02:01','ed2a03c5-bea9-4bed-8fe2-2a9e97abb03e'),(6,6,251,'Module','module',1,'Title',NULL,1,'2017-10-24 09:39:33','2018-12-06 17:45:11','dbd7f9dd-1424-48cc-8aba-f0533bce83be'),(7,7,254,'Unit','unit',1,'Title',NULL,1,'2017-10-24 09:45:57','2018-12-11 07:29:35','6bbfb167-5ead-4bd3-971d-7f756a5b4c8b'),(10,10,260,'Unit Result','unitResult',0,NULL,'[unit {resultUnit.first.id}] {author.firstName} {author.lastName}',1,'2018-02-14 12:41:38','2018-12-12 07:00:33','3bf342f8-6a3e-465e-b665-d25d1860be54'),(12,12,122,'Attempt','attempt',0,NULL,'[unit {attemptUnit.first.id}] {author.firstName} {author.lastName}',1,'2018-04-13 10:12:01','2018-04-19 14:53:29','39d4cfb6-0d4f-496d-82ca-64d29f904a62'),(14,10,155,'Module Result','moduleResult',0,NULL,'[module {resultModule.first().id}] {author.firstName} {author.lastName} ',2,'2018-04-23 10:05:23','2018-06-04 11:08:02','abaff843-e57c-4cfa-bab2-3f4927f556d3'),(15,13,165,'Reports','reports',1,'Title',NULL,1,'2018-08-13 12:10:21','2018-08-20 15:36:56','6aaa6123-97b5-479c-8de3-488d8813933e'),(16,14,258,'Pages','pages',1,'Title',NULL,1,'2018-08-21 13:08:51','2018-12-12 00:07:57','006ef470-d860-40ff-a431-e2515805f2e8'),(17,10,259,'User Result','userResult',1,'',NULL,3,'2018-08-28 13:07:54','2018-12-12 06:50:08','e34f85df-0855-47ca-a101-0519afac3c0a');
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
) ENGINE=InnoDB AUTO_INCREMENT=849 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_entryversions`
--

LOCK TABLES `craft_entryversions` WRITE;
/*!40000 ALTER TABLE `craft_entryversions` DISABLE KEYS */;
INSERT INTO `craft_entryversions` VALUES (489,1086,14,143,'en_gb',1,'','{\"typeId\":null,\"authorId\":\"143\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034572,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget.Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"An established and trusted provider of online solutions for Local Government that help you work better, do more and save money.\",\"115\":[\"1084\"]}}','2018-08-23 14:29:32','2018-08-23 14:29:32','e81ae74f-7801-46e3-a85c-d0ae7a3fa9b7'),(490,1090,14,143,'en_gb',1,'','{\"typeId\":null,\"authorId\":\"143\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034631,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"<p>[CONTACT US CONTENT]<\\/p>\",\"112\":[],\"2\":\"An established and trusted provider of online solutions for Local Government that help you work better, do more and save money.\",\"115\":\"\"}}','2018-08-23 14:30:31','2018-08-23 14:30:31','51a98672-ffda-4a88-84ab-59520a3fc6e7'),(583,1090,14,143,'en_gb',2,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-09-26 14:44:04','2018-09-26 14:44:04','33ef22fe-1d72-4b94-9f3f-826507eab87a'),(584,1086,14,143,'en_gb',2,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"\",\"115\":[\"1084\"]}}','2018-09-26 14:44:27','2018-09-26 14:44:27','10078807-1287-4423-9a16-b634dfc41311'),(585,1086,14,1,'en_gb',3,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"<p>cgfhfgh<\\/p>\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"\",\"115\":\"\"}}','2018-11-15 12:19:36','2018-11-15 12:19:36','b819be2d-d775-4eaf-9429-d2da57377ae5'),(586,1086,14,1,'en_gb',4,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"fsdfdssf\",\"115\":\"\"}}','2018-11-15 12:19:59','2018-11-15 12:19:59','68ce0b12-f456-4655-bf2b-0b80911896e2'),(587,1086,14,1,'en_gb',5,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"\",\"115\":[\"1591\"]}}','2018-11-15 12:21:10','2018-11-15 12:21:10','003e1c06-7382-4db5-92b9-6f63cb414505'),(588,1086,14,1,'en_gb',6,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-15 12:21:55','2018-11-15 12:21:55','51be70e9-001b-4470-9d6a-24d5779cf385'),(589,1595,14,1,'en_gb',1,'','{\"typeId\":null,\"authorId\":\"1\",\"title\":\"Terms and Privacy Policy\",\"slug\":\"terms-and-privacy-policy\",\"postDate\":1542722815,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-11-20 14:06:56','2018-11-20 14:06:56','ace560c0-6b34-404f-ae2d-73cdabb4edd5'),(590,1595,14,1,'en_gb',2,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Terms and Privacy Policy\",\"slug\":\"terms-and-privacy-policy\",\"postDate\":1542722760,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-11-20 14:07:33','2018-11-20 14:07:33','419ad77c-8127-45fa-9be9-28e285310c36'),(591,1086,14,1,'en_gb',7,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Uss\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-20 14:08:17','2018-11-20 14:08:17','a4a41c45-af0f-4119-8ac5-7542ae3c35a6'),(592,1086,14,1,'en_gb',8,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}},\"1089\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Valuable\",\"columnHtml\":\"<p>We have a wide range of plans to fit your goals and budget. Check out a free trial to see what works for you and then pay monthly&mdash;you\\u2019ll never have to shell out thousands up front like some bootcamps or traditional colleges.<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-20 14:08:27','2018-11-20 14:08:27','3cb23103-eb08-410c-9170-a49e2b485a1f'),(593,1595,14,1,'en_gb',3,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Terms and Privacy Policy\",\"slug\":\"terms-and-privacy-policy\",\"postDate\":1542722760,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1596\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Terms of use\",\"columnHtml\":\"\"}},\"1597\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Privacy Policy\",\"columnHtml\":\"\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":\"\"}}','2018-11-20 14:09:10','2018-11-20 14:09:10','090454d6-6819-4230-999b-9ac0e3331f12'),(619,1090,14,1,'en_gb',3,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":\"\"}}','2018-11-23 11:05:54','2018-11-23 11:05:54','b32f768c-4ceb-450d-ba39-6c067897a29e'),(620,1090,14,1,'en_gb',4,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":\"\"}}','2018-11-23 11:08:56','2018-11-23 11:08:56','f4e52587-cd65-4718-9d44-1cfd667a157b'),(621,1086,14,1,'en_gb',9,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Join hundreds of thousands of students in our supportive online community. They\\u2019re always available to lend support and nudge you to keep going.<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-23 11:34:29','2018-11-23 11:34:29','f41f359f-a41f-46f6-866f-45438ec7430b'),(622,1086,14,1,'en_gb',10,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Text<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Text<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-23 11:35:08','2018-11-23 11:35:08','5e13bb5f-190d-428e-8ac3-6046bba28b3c'),(623,1086,14,1,'en_gb',11,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Text<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Text<\\/p>\"}},\"1621\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<pre>\\r\\n&lt;div class=\\\"content-col left\\\"&gt;\\r\\n        &lt;h2&gt;Supportive&lt;\\/h2&gt;\\r\\n        &lt;p&gt;&lt;\\/p&gt;&lt;p&gt;Text&lt;\\/p&gt;&lt;p&gt;&lt;\\/p&gt;\\r\\n&lt;\\/div&gt;<\\/pre>\"}},\"1622\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<pre>\\r\\n&lt;div class=\\\"content-col right\\\"&gt;\\r\\n        &lt;h2&gt;Supportive&lt;\\/h2&gt;\\r\\n        &lt;p&gt;&lt;\\/p&gt;&lt;p&gt;Text&lt;\\/p&gt;&lt;p&gt;&lt;\\/p&gt;\\r\\n&lt;\\/div&gt;<\\/pre>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-23 14:16:19','2018-11-23 14:16:19','d81c89f9-8d08-47cd-bd74-d6f104ae095b'),(624,1086,14,1,'en_gb',12,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Flexible\",\"columnHtml\":\"<p>Text<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Supportive\",\"columnHtml\":\"<p>Text<\\/p>\"}}},\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":[\"1591\"]}}','2018-11-23 14:18:15','2018-11-23 14:18:15','2146d0ea-a031-45e0-bce4-b04ca26c3304'),(625,1086,14,1,'en_gb',13,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Lantra\",\"slug\":\"about-us\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"How we work\",\"columnHtml\":\"<p>Industry plays an essential part in Lantra\'s work. Our role often involves working closely with industry groups to deliver solutions to specific industry needs. The opinions and ideas of such groups help us to change and improve the industry, promoting the importance of skills recognition, training and development with the aim of increasing productivity, sustainability - and ultimately profitability.<\\/p>\\r\\n<p>Liaising closely with industries within the land-based sector we represent, along with governments, funding agencies, learning providers, trade associations and the media, we can shape important strategies for the future.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Working with industry groups and employers\",\"columnHtml\":\"<p><\\/p><p>At the heart of Lantra\'s organisation are environmental and land-based employers - experts in their own field who know first hand the requirements of their industries. Employers play an integral part in the corporate structure of the organisation from working groups through to Lantra\'s board of directors, and play a key leadership role in forming and shaping Lantra\'s strategies, products and services.<\\/p><p><\\/p>\"}}},\"2\":\"\",\"115\":[\"1591\"]}}','2018-11-23 14:20:46','2018-11-23 14:20:46','2f79d67b-0a01-486e-907a-9dba54434073'),(626,1086,14,1,'en_gb',14,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Lantra\",\"slug\":\"about-lantra\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"How we work\",\"columnHtml\":\"<p>Industry plays an essential part in Lantra\'s work. Our role often involves working closely with industry groups to deliver solutions to specific industry needs. The opinions and ideas of such groups help us to change and improve the industry, promoting the importance of skills recognition, training and development with the aim of increasing productivity, sustainability - and ultimately profitability.<\\/p>\\r\\n<p>Liaising closely with industries within the land-based sector we represent, along with governments, funding agencies, learning providers, trade associations and the media, we can shape important strategies for the future.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Working with industry groups and employers\",\"columnHtml\":\"<p>At the heart of Lantra\'s organisation are environmental and land-based employers - experts in their own field who know first hand the requirements of their industries. Employers play an integral part in the corporate structure of the organisation from working groups through to Lantra\'s board of directors, and play a key leadership role in forming and shaping Lantra\'s strategies, products and services.<\\/p>\"}}},\"2\":\"\",\"115\":[\"1591\"]}}','2018-11-23 14:21:00','2018-11-23 14:21:00','be011e8e-f0da-4676-8bc1-6d4a3a24a74c'),(627,1086,14,1,'en_gb',15,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Lantra\",\"slug\":\"about-lantra\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"How we work\",\"columnHtml\":\"<p>The opinions and ideas of such groups help us to change and improve the industry, promoting the importance of skills recognition, training and development with the aim of increasing productivity, sustainability - and ultimately profitability.<\\/p>\\r\\n<p>Liaising closely with industries within the land-based sector we represent, along with governments, funding agencies, learning providers, trade associations and the media, we can shape important strategies for the future.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Working with industry groups and employers\",\"columnHtml\":\"<p>At the heart of Lantra\'s organisation are environmental and land-based employers - experts in their own field who know first hand the requirements of their industries. Employers play an integral part in the corporate structure of the organisation from working groups through to Lantra\'s board of directors, and play a key leadership role in forming and shaping Lantra\'s strategies, products and services.<\\/p>\"}}},\"2\":\"Industry plays an essential part in Lantra\'s work. Our role often involves working closely with industry groups to deliver solutions to specific industry needs.\",\"115\":[\"1591\"]}}','2018-11-23 14:31:13','2018-11-23 14:31:13','58b3ffa6-68ac-4c1b-ab94-ea54e07a4982'),(628,1090,14,1,'en_gb',5,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<p><\\/p><p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p><p><\\/p>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 14:42:33','2018-11-23 14:42:33','8a79d24d-931a-4bef-bc0d-bb4bd6fbf3d9'),(629,1090,14,1,'en_gb',6,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\",\"rawHtml\":\"<p>Hello<\\/p>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 14:59:06','2018-11-23 14:59:06','86697375-7e03-45f5-bd36-0772599777fb'),(630,1090,14,1,'en_gb',7,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<pre>\\r\\n&lt;title&gt;Title of the document&lt;\\/title&gt;\\r\\nThe content of the document......\\r\\n<\\/pre>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:12:23','2018-11-23 15:12:23','c3da3c94-cdf2-4772-9cfa-561b5ed09ab4'),(631,1090,14,1,'en_gb',8,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<p>&lt;title&gt;Title of the document&lt;\\/title&gt;\\r\\nThe content of the document......\\r\\n<\\/p>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:12:44','2018-11-23 15:12:44','dd6e66b9-bd4e-42ac-9d5e-ac93f2bb4a97'),(632,1090,14,1,'en_gb',9,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<p>&lt;!DOCTYPE&nbsp;html&gt;<br>&lt;html&gt;<br>&lt;head&gt;<br>&lt;title&gt;Title of the document&lt;\\/title&gt;<br>&lt;\\/head&gt;<br><br>&lt;body&gt;<br>The content of the document......<br>&lt;\\/body&gt;<br><br>&lt;\\/html&gt;<\\/p>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:12:57','2018-11-23 15:12:57','9c749eb2-4216-4573-8216-39113bf1b76e'),(633,1090,14,1,'en_gb',10,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<pre>\\r\\n&lt;!DOCTYPE&nbsp;html&gt;<br>&lt;html&gt;<br>&lt;head&gt;<br>&lt;title&gt;Title of the document&lt;\\/title&gt;<br>&lt;\\/head&gt;<br><br>&lt;body&gt;<br>The content of the document......<br>&lt;\\/body&gt;<br><br>&lt;\\/html&gt;<\\/pre>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:13:07','2018-11-23 15:13:07','cdc3a224-7533-42ec-a7a4-0a9b24d77d55'),(634,1090,14,1,'en_gb',11,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<!DOCTYPE html>\\r\\n<html>\\r\\n<head>\\r\\n<title>Title of the document<\\/title>\\r\\n<\\/head>\\r\\n\\r\\n<body>\\r\\nThe content of the document......\\r\\n<\\/body>\\r\\n\\r\\n<\\/html>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:16:59','2018-11-23 15:16:59','cee85da3-c88d-4acd-b452-1cfae51eca0f'),(635,1090,14,1,'en_gb',12,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&amp;amp;ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:18:26','2018-11-23 15:18:26','9db58e19-e7d8-41b7-a093-d907f5282c99'),(636,1090,14,1,'en_gb',13,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&amp;amp;ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:19:39','2018-11-23 15:19:39','934e3087-4687-40b9-9a9a-490f4872a5de'),(637,1090,14,1,'en_gb',14,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&amp;amp;ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:20:16','2018-11-23 15:20:16','38455940-c3c2-41c5-a1d0-cabb0cb71358'),(638,1090,14,1,'en_gb',15,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Email your enquiry\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:21:44','2018-11-23 15:21:44','c85d3de1-8c0b-4d8d-8d04-1ef0b22c0d69'),(639,1090,14,1,'en_gb',16,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:21:57','2018-11-23 15:21:57','b714b5bf-1d12-456b-992b-3d2c4bc6147a'),(640,1090,14,1,'en_gb',17,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"\",\"115\":\"\"}}','2018-11-23 15:25:26','2018-11-23 15:25:26','8d596129-7921-4387-8ccd-80ff460cdd33'),(641,1090,14,1,'en_gb',18,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<input type=\\\"hidden\\\" name=\\\"details[sid]\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_num]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[page_count]\\\" value=\\\"1\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"details[finished]\\\" value=\\\"0\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_build_id\\\" value=\\\"form-HexsFLGmPz76ydsrjq3Bilb6-m9jgVgYF6cjiQdvWQw\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"form_id\\\" value=\\\"webform_client_form_447\\\">\\r\\n<fieldset class=\\\"captcha panel panel-default form-wrapper\\\">\\r\\n          <legend class=\\\"panel-heading\\\">\\r\\n      <span class=\\\"panel-title fieldset-legend fieldset-title\\\">CAPTCHA<\\/span>\\r\\n    <\\/legend>\\r\\n          <div class=\\\"panel-body\\\">\\r\\n    <div class=\\\"help-block\\\">This question is for testing whether or not you are a human visitor and to prevent automated spam submissions.<\\/div>    <input type=\\\"hidden\\\" name=\\\"captcha_sid\\\" value=\\\"73432\\\">\\r\\n<input type=\\\"hidden\\\" name=\\\"captcha_token\\\" value=\\\"3d9cf076122253ec035f4c096ba7954b\\\">\\r\\n<img class=\\\"img-responsive\\\" src=\\\"\\/image_captcha?sid=73432&ts=1542984049\\\" alt=\\\"Image CAPTCHA\\\" title=\\\"Image CAPTCHA\\\" width=\\\"180\\\" height=\\\"60\\\"><div class=\\\"form-item form-item-captcha-response form-type-textfield form-group\\\"> <label class=\\\"control-label\\\" for=\\\"edit-captcha-response\\\">What code is in the image? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n<input class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-captcha-response\\\" name=\\\"captcha_response\\\" value=\\\"\\\" size=\\\"15\\\" maxlength=\\\"128\\\" autocomplete=\\\"off\\\"><div class=\\\"help-block\\\">Enter the characters shown in the image.<\\/div><\\/div>  <\\/div>\\r\\n  <\\/fieldset><div class=\\\"form-actions\\\"><button class=\\\"webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/div><\\/div><\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:25:47','2018-11-23 15:25:47','cdb9d38b-0443-422d-aa5e-1926e5ddd94f'),(642,1090,14,1,'en_gb',19,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"Contact Us\",\"slug\":\"contact-us\",\"postDate\":1535034600,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1620\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.<\\/p>\\r\\n<form class=\\\"webform-client-form webform-client-form-447 webform-conditional-processed\\\" enctype=\\\"multipart\\/form-data\\\" action=\\\"\\/webform\\/contact-us-7\\\" method=\\\"post\\\" id=\\\"webform-client-form-447\\\" accept-charset=\\\"UTF-8\\\"><div><div class=\\\"form-item webform-component webform-component-textfield webform-component--name form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-name\\\">Name <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"form-control form-text required\\\" type=\\\"text\\\" id=\\\"edit-submitted-name\\\" name=\\\"submitted[name]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-email webform-component--email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-email\\\">Email <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <input required=\\\"required\\\" class=\\\"email form-control form-text form-email required\\\" type=\\\"email\\\" id=\\\"edit-submitted-email\\\" name=\\\"submitted[email]\\\" size=\\\"60\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textfield webform-component--telephone form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-telephone\\\">Telephone <span class=\\\"form-optional\\\"><\\/span><\\/label>\\r\\n <input class=\\\"form-control form-text\\\" type=\\\"text\\\" id=\\\"edit-submitted-telephone\\\" name=\\\"submitted[telephone]\\\" value=\\\"\\\" size=\\\"60\\\" maxlength=\\\"128\\\">\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-reason-for-contacting-us\\\">Reason for contacting us <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <select required=\\\"required\\\" class=\\\"form-control form-select required\\\" id=\\\"edit-submitted-reason-for-contacting-us\\\" name=\\\"submitted[reason_for_contacting_us]\\\"><option value=\\\"\\\" selected=\\\"selected\\\">- Select -<\\/option><option value=\\\"1\\\">General Enquiry<\\/option><\\/select>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-textarea webform-component--comments form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-comments\\\">Comments <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div class=\\\"form-textarea-wrapper resizable textarea-processed resizable-textarea\\\"><textarea required=\\\"required\\\" class=\\\"form-control form-textarea required\\\" id=\\\"edit-submitted-comments\\\" name=\\\"submitted[comments]\\\" cols=\\\"60\\\" rows=\\\"5\\\"><\\/textarea><div class=\\\"grippie\\\"><\\/div><\\/div>\\r\\n<\\/div>\\r\\n<div class=\\\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\\\">\\r\\n  <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\">Would you prefer a response via telephone or email? <span class=\\\"form-required\\\" title=\\\"This field is required.\\\">*<\\/span><\\/label>\\r\\n <div id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\\\" class=\\\"form-radios\\\"><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"email\\\" checked=\\\"checked\\\" class=\\\"form-radio\\\">Email <\\/label>\\r\\n<\\/div><div class=\\\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\\\"> <label class=\\\"control-label\\\" for=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\"><input required=\\\"required\\\" type=\\\"radio\\\" id=\\\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\\\" name=\\\"submitted[would_you_prefer_a_response_via_telephone_or_email]\\\" value=\\\"telephone\\\" class=\\\"form-radio\\\">Telephone <\\/label>\\r\\n<\\/div><\\/div>\\r\\n<\\/div>\\r\\n<button class=\\\"button primary-bg webform-submit button-primary btn btn-primary form-submit\\\" name=\\\"op\\\" value=\\\"Submit\\\" type=\\\"submit\\\">Submit<\\/button>\\r\\n<\\/form>\"}}},\"2\":\"We\'re here to help. Use the form below to contact a member of the team.\",\"115\":\"\"}}','2018-11-23 15:34:38','2018-11-23 15:34:38','020b4641-157f-4c8b-87ea-4b5d10820008'),(643,1595,14,1,'en_gb',4,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"FAQs\",\"slug\":\"faqs\",\"postDate\":1542722760,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"Learning a new skill doesn\\u2019t have to interrupt your busy schedule. Our on-demand videos and interactive code challenges are there for you when you need them.\",\"115\":\"\"}}','2018-11-23 15:46:13','2018-11-23 15:46:13','93f24616-dda2-421b-9c96-4be4870a92d4'),(644,1623,14,1,'en_gb',1,'','{\"typeId\":null,\"authorId\":\"1\",\"title\":\"About BICS\",\"slug\":\"about-us\",\"postDate\":1542988131,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-11-23 15:48:51','2018-11-23 15:48:51','53d28858-fe77-48c7-b70a-21434a11e089'),(645,1623,14,1,'en_gb',2,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"BICS\",\"slug\":\"about-us\",\"postDate\":1542988080,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-11-23 15:50:16','2018-11-23 15:50:16','4867c981-591d-4189-8217-ac6d90561d36'),(646,1623,14,1,'en_gb',3,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"BICS Scheme\",\"slug\":\"about-us\",\"postDate\":1542988080,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-11-23 15:50:32','2018-11-23 15:50:32','07455449-877b-451c-bf12-87d2e221c98b'),(647,1623,14,1,'en_gb',4,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About us\",\"slug\":\"about-us\",\"postDate\":1542988080,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-11-23 16:06:36','2018-11-23 16:06:36','b685c7f4-1dca-423e-bc98-127d14290257'),(648,1623,14,1438,'en_gb',5,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About CHA\",\"slug\":\"about-us\",\"postDate\":1542988080,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"<p><strong><\\/strong><strong>THE BENEFITS OF HYDROTHERAPY<\\/strong><\\/p>\\r\\n<p>Extensive work in human physiotherapy has demonstrated that a suitably monitored course of hydrotherapy acts by encouraging a full range of joint motion in reduced weight bearing conditions, thus improving muscle tone and promoting tissue repair, without imposing undue stress on damaged tissues.\\r\\nControlled swimming helps to improve cardiovascular stamina, muscle tone, range of movement and is particularly helpful in aiding recovery from injury or surgery whilst also improving general fitness, especially in the management of obesity.\\r\\nMuscle wastage begins within 3 days of any immobilisation&nbsp;so to prevent further weakness or injury it is important to rebuild, through safe exercise, any muscles that have deteriorated.\\r\\nIt is better to&nbsp;treat dogs in heated water since cold water causes constriction of the blood vessels near the skin and to the superficial muscles (those just under the skin) which restricts the flow of blood making the muscles less efficient.\\r\\n<\\/p>\",\"112\":[],\"2\":\"\",\"115\":\"\"}}','2018-12-07 09:55:43','2018-12-07 09:55:43','50b48166-9e2b-4f62-9d9f-c40f5fbd11b5'),(649,1086,14,1438,'en_gb',16,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Lantra\",\"slug\":\"about-lantra\",\"postDate\":1535034540,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1087\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"How we work\",\"columnHtml\":\"<p>The opinions and ideas of such groups help us to change and improve the industry, promoting the importance of skills recognition, training and development with the aim of increasing productivity, sustainability - and ultimately profitability.<\\/p>\\r\\n<p>Liaising closely with industries within the land-based sector we represent, along with governments, funding agencies, learning providers, trade associations and the media, we can shape important strategies for the future.<\\/p>\"}},\"1088\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"Working with industry groups and employers\",\"columnHtml\":\"<p>At the heart of Lantra\'s organisation are environmental and land-based employers - experts in their own field who know first hand the requirements of their industries. Employers play an integral part in the corporate structure of the organisation from working groups through to Lantra\'s board of directors, and play a key leadership role in forming and shaping Lantra\'s strategies, products and services.<\\/p>\"}}},\"2\":\"Industry plays an essential part in Lantra\'s work. Our role often involves working closely with industry groups to deliver solutions to specific industry needs.\",\"115\":\"\"}}','2018-12-07 10:17:14','2018-12-07 10:17:14','304a50b8-660f-4e75-8894-25b60ad200e3'),(661,1623,14,1,'en_gb',6,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About CHA\",\"slug\":\"about-us\",\"postDate\":1542988080,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"<p><strong>THE BENEFITS OF HYDROTHERAPY<\\/strong><\\/p>\\r\\n<p>Extensive work in human physiotherapy has demonstrated that a suitably monitored course of hydrotherapy acts by encouraging a full range of joint motion in reduced weight bearing conditions, thus improving muscle tone and promoting tissue repair, without imposing undue stress on damaged tissues.\\r\\nControlled swimming helps to improve cardiovascular stamina, muscle tone, range of movement and is particularly helpful in aiding recovery from injury or surgery whilst also improving general fitness, especially in the management of obesity.\\r\\nMuscle wastage begins within 3 days of any immobilisation so to prevent further weakness or injury it is important to rebuild, through safe exercise, any muscles that have deteriorated.\\r\\nIt is better to treat dogs in heated water since cold water causes constriction of the blood vessels near the skin and to the superficial muscles (those just under the skin) which restricts the flow of blood making the muscles less efficient.\\r\\n<\\/p>\",\"112\":{\"1923\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"THE BENEFITS OF HYDROTHERAPY\",\"columnHtml\":\"<p>Extensive work in human physiotherapy has demonstrated that a suitably monitored course of hydrotherapy acts by encouraging a full range of joint motion in reduced weight bearing conditions, thus improving muscle tone and promoting tissue repair, without imposing undue stress on damaged tissues. Controlled swimming helps to improve cardiovascular stamina, muscle tone, range of movement and is particularly helpful in aiding recovery from injury or surgery whilst also improving general fitness, especially in the management of obesity. Muscle wastage begins within 3 days of any immobilisation so to prevent further weakness or injury it is important to rebuild, through safe exercise, any muscles that have deteriorated. It is better to treat dogs in heated water since cold water causes constriction of the blood vessels near the skin and to the superficial muscles (those just under the skin) which restricts the flow of blood making the muscles less efficient.<\\/p>\"}}},\"2\":\"Test heading\",\"115\":\"\"}}','2018-12-10 09:11:48','2018-12-10 09:11:48','51c8940b-1b9d-43c7-a044-24866fdaf21c'),(662,1623,14,1,'en_gb',7,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About CHA\",\"slug\":\"about-us\",\"postDate\":1542988080,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1923\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"THE BENEFITS OF HYDROTHERAPY\",\"columnHtml\":\"<p>Extensive work in human physiotherapy has demonstrated that a suitably monitored course of hydrotherapy acts by encouraging a full range of joint motion in reduced weight bearing conditions, thus improving muscle tone and promoting tissue repair, without imposing undue stress on damaged tissues. Controlled swimming helps to improve cardiovascular stamina, muscle tone, range of movement and is particularly helpful in aiding recovery from injury or surgery whilst also improving general fitness, especially in the management of obesity. Muscle wastage begins within 3 days of any immobilisation so to prevent further weakness or injury it is important to rebuild, through safe exercise, any muscles that have deteriorated. It is better to treat dogs in heated water since cold water causes constriction of the blood vessels near the skin and to the superficial muscles (those just under the skin) which restricts the flow of blood making the muscles less efficient.<\\/p>\"}}},\"2\":\"Test heading\",\"115\":\"\"}}','2018-12-10 09:12:09','2018-12-10 09:12:09','bff43cd5-9217-400d-917c-035714de7672'),(663,1623,14,1,'en_gb',8,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About CHA\",\"slug\":\"about-us\",\"postDate\":1542988080,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"1\":\"\",\"112\":{\"1923\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"<p>Extensive work in human physiotherapy has demonstrated that a suitably monitored course of hydrotherapy acts by encouraging a full range of joint motion in reduced weight bearing conditions, thus improving muscle tone and promoting tissue repair, without imposing undue stress on damaged tissues. Controlled swimming helps to improve cardiovascular stamina, muscle tone, range of movement and is particularly helpful in aiding recovery from injury or surgery whilst also improving general fitness, especially in the management of obesity. Muscle wastage begins within 3 days of any immobilisation so to prevent further weakness or injury it is important to rebuild, through safe exercise, any muscles that have deteriorated. It is better to treat dogs in heated water since cold water causes constriction of the blood vessels near the skin and to the superficial muscles (those just under the skin) which restricts the flow of blood making the muscles less efficient.<\\/p>\"}},\"1924\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"\"}}},\"2\":\"The benefits of hydrotherapy\",\"115\":\"\"}}','2018-12-10 09:13:11','2018-12-10 09:13:11','db672cbd-8053-4d28-8482-3663f08233af'),(848,1623,14,143,'en_gb',9,'','{\"typeId\":\"16\",\"authorId\":\"1\",\"title\":\"About Us\",\"slug\":\"about-us\",\"postDate\":1542988080,\"expiryDate\":null,\"enabled\":1,\"parentId\":null,\"fields\":{\"112\":{\"1923\":{\"type\":\"columns\",\"enabled\":\"1\",\"fields\":{\"columnTitle\":\"\",\"columnHtml\":\"\"}}},\"2\":\"About Us\",\"115\":\"\"}}','2018-12-14 18:21:39','2018-12-14 18:21:39','43ceb09e-5e04-4574-88e0-bf9609da1b31');
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
) ENGINE=InnoDB AUTO_INCREMENT=1067 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_fieldlayoutfields`
--

LOCK TABLES `craft_fieldlayoutfields` WRITE;
/*!40000 ALTER TABLE `craft_fieldlayoutfields` DISABLE KEYS */;
INSERT INTO `craft_fieldlayoutfields` VALUES (142,71,59,8,1,1,'2018-03-07 14:02:01','2018-03-07 14:02:01','be2ec4c3-0419-4ee0-85ed-383fd474b399'),(143,71,59,9,1,2,'2018-03-07 14:02:01','2018-03-07 14:02:01','79d4125e-07fc-4790-8844-6ffbf75a23c3'),(144,71,60,10,0,1,'2018-03-07 14:02:01','2018-03-07 14:02:01','c31f458a-8337-4a88-8ba1-de805f85c1a6'),(145,71,60,11,0,2,'2018-03-07 14:02:01','2018-03-07 14:02:01','7c35538d-737f-4b65-b8c9-ccf9c5f46f11'),(152,74,64,20,0,1,'2018-03-09 11:20:19','2018-03-09 11:20:19','a76f8c65-d603-415d-991e-47d012c32a5b'),(153,74,64,25,0,2,'2018-03-09 11:20:19','2018-03-09 11:20:19','0c2e5be6-1063-4ccb-8d65-35e20e641b04'),(200,94,81,41,0,1,'2018-04-13 18:13:59','2018-04-13 18:13:59','535e9d33-d0b9-4666-9301-4d79a1234e9a'),(201,94,81,42,0,2,'2018-04-13 18:13:59','2018-04-13 18:13:59','b42cbecb-f943-499c-89e2-c2e3bdf95d11'),(202,94,81,43,0,3,'2018-04-13 18:13:59','2018-04-13 18:13:59','cbcd1834-bfbd-4da1-84bf-5d9394370a54'),(203,95,82,44,0,1,'2018-04-13 18:13:59','2018-04-13 18:13:59','a22af8ec-e0a1-4b1e-be0a-6b2b878cf967'),(204,95,82,45,0,2,'2018-04-13 18:13:59','2018-04-13 18:13:59','b8db2050-54dd-4949-a2ef-0c042d0fb61a'),(205,95,82,46,0,3,'2018-04-13 18:13:59','2018-04-13 18:13:59','c0978586-eda4-4dce-a815-e51f4fb5e04c'),(206,96,83,47,0,1,'2018-04-13 18:13:59','2018-04-13 18:13:59','f73ae004-5537-4c91-98a0-cede31ee0e80'),(207,96,83,48,0,2,'2018-04-13 18:13:59','2018-04-13 18:13:59','3b27b72c-9d12-47a0-9ee6-7365f4769ad6'),(208,97,84,51,0,1,'2018-04-13 18:20:05','2018-04-13 18:20:05','5b6108e8-0f9f-4c87-8904-ca650d6628a8'),(209,97,84,56,0,2,'2018-04-13 18:20:05','2018-04-13 18:20:05','34ec8639-44ae-47f8-afdd-cd7c67b4babc'),(210,97,84,52,0,3,'2018-04-13 18:20:05','2018-04-13 18:20:05','b73bfe8e-50a1-47bf-84e2-56ab89fd499c'),(211,97,84,53,0,4,'2018-04-13 18:20:05','2018-04-13 18:20:05','364af5a5-85e5-41ce-8eba-ba6d0a29d417'),(369,122,124,55,0,1,'2018-04-19 14:53:29','2018-04-19 14:53:29','2c8deac2-efef-43be-8d62-9f6b3277abcb'),(370,122,124,50,0,2,'2018-04-19 14:53:29','2018-04-19 14:53:29','78108094-3732-4b5b-abe2-8ef1ca36634c'),(408,138,141,72,0,1,'2018-04-29 12:42:06','2018-04-29 12:42:06','b22fa490-73b0-4fcb-96ff-6a12ced5bfaa'),(409,138,141,73,0,2,'2018-04-29 12:42:06','2018-04-29 12:42:06','b943b614-2d63-4d4c-b0c0-bd9e24322ea1'),(434,147,151,78,0,1,'2018-05-31 13:06:54','2018-05-31 13:06:54','a17113f2-1c8b-4ded-aa97-8480966e7303'),(435,147,151,79,0,2,'2018-05-31 13:06:54','2018-05-31 13:06:54','f40fddda-59a3-41a1-a3e6-b3c5e0dd04ef'),(436,147,151,80,0,3,'2018-05-31 13:06:54','2018-05-31 13:06:54','6e713066-a96c-460d-8cdc-4e2ad9eb7a42'),(465,155,162,67,0,1,'2018-06-04 11:08:02','2018-06-04 11:08:02','104182d1-6e6e-4a82-a71d-bf075f690517'),(466,155,162,29,0,2,'2018-06-04 11:08:02','2018-06-04 11:08:02','01ce06eb-0865-4a1e-ab15-b6639ead0553'),(530,165,180,102,0,1,'2018-08-20 15:36:56','2018-08-20 15:36:56','89c1dbf2-a280-4eee-9056-7df6f7e10f8c'),(531,165,180,90,0,2,'2018-08-20 15:36:56','2018-08-20 15:36:56','19f51c2e-e24e-423f-9a11-da31c63eedd8'),(532,165,180,103,0,3,'2018-08-20 15:36:56','2018-08-20 15:36:56','ddf27439-9d91-4a3b-9f8a-214f6827023a'),(533,165,180,104,0,4,'2018-08-20 15:36:56','2018-08-20 15:36:56','e58ccf82-7445-4172-a5de-80e8491fae4b'),(534,165,181,91,0,1,'2018-08-20 15:36:56','2018-08-20 15:36:56','cb62ae7e-ae57-4fb3-96c8-b48f923ea321'),(535,165,181,95,0,2,'2018-08-20 15:36:56','2018-08-20 15:36:56','407539f5-2705-4db6-ac3b-12be87138ea7'),(536,165,181,92,0,3,'2018-08-20 15:36:56','2018-08-20 15:36:56','5ee54b2a-e0a3-4e3e-b973-1dacd2987a45'),(537,165,181,96,0,4,'2018-08-20 15:36:56','2018-08-20 15:36:56','27d7f888-990d-4529-ab8c-c2bed5eafbaa'),(538,165,181,94,0,5,'2018-08-20 15:36:56','2018-08-20 15:36:56','dd4f95e5-c828-4032-804a-2ffb0ca086a1'),(539,165,181,98,0,6,'2018-08-20 15:36:56','2018-08-20 15:36:56','df3adcfc-c154-46fc-ae7d-0bfb0dad83a2'),(540,165,181,93,0,7,'2018-08-20 15:36:56','2018-08-20 15:36:56','4f6b6f00-3f5f-41f9-a1c6-a988cbeda11f'),(541,165,181,97,0,8,'2018-08-20 15:36:56','2018-08-20 15:36:56','42395d2b-a73c-47b0-b5ac-cc386313f78f'),(542,165,181,106,0,9,'2018-08-20 15:36:56','2018-08-20 15:36:56','40a42a5d-8746-4853-93db-988f76a75435'),(543,165,182,99,0,1,'2018-08-20 15:36:56','2018-08-20 15:36:56','1a7aa133-43df-4fe9-a66a-e46bd6e53aa6'),(544,165,182,100,0,2,'2018-08-20 15:36:56','2018-08-20 15:36:56','1b228741-085e-42d9-a82a-88750e7b3fc5'),(545,165,182,101,0,3,'2018-08-20 15:36:56','2018-08-20 15:36:56','10e5c029-d341-4c2f-86b1-a74775313d14'),(546,165,183,105,0,1,'2018-08-20 15:36:56','2018-08-20 15:36:56','58d6bed5-2a4c-49c0-9718-546d1786bbdd'),(550,171,185,108,0,1,'2018-08-21 15:27:31','2018-08-21 15:27:31','fb4b2d8d-7a39-4809-b6a3-c70399ff2712'),(551,171,185,109,0,2,'2018-08-21 15:27:31','2018-08-21 15:27:31','2de1c44e-b18e-4f66-a60a-fa003e6d86f2'),(552,171,185,110,0,3,'2018-08-21 15:27:32','2018-08-21 15:27:32','22f03fdf-c0d8-4445-b1d6-3ecc6865bb1e'),(645,194,209,125,0,1,'2018-08-26 15:03:37','2018-08-26 15:03:37','03771268-b05d-4880-b0c5-187d89ba4eb6'),(646,194,209,126,0,2,'2018-08-26 15:03:37','2018-08-26 15:03:37','850cec24-d6ca-4c6a-adf3-003168a792b8'),(739,207,238,136,0,1,'2018-08-29 16:37:18','2018-08-29 16:37:18','869aac6e-9c38-4dba-a790-51d2c98105fb'),(740,207,238,135,0,2,'2018-08-29 16:37:18','2018-08-29 16:37:18','19367e3e-84ae-4003-b8d0-435328ede3f5'),(741,207,238,134,0,3,'2018-08-29 16:37:18','2018-08-29 16:37:18','fabf87d3-597e-4eca-a344-32178747fe54'),(742,207,238,138,0,4,'2018-08-29 16:37:18','2018-08-29 16:37:18','d7fcbd5d-7cf5-438c-abdd-7c6434736481'),(743,207,238,137,0,5,'2018-08-29 16:37:18','2018-08-29 16:37:18','8a8bcfc6-8006-4c89-b677-1c92ba383edd'),(744,207,238,140,0,6,'2018-08-29 16:37:18','2018-08-29 16:37:18','27717cc4-e631-4ce5-8401-9910c0aa14b4'),(745,207,238,139,0,7,'2018-08-29 16:37:18','2018-08-29 16:37:18','0447cf0d-e12f-42ef-98de-7b0614696ae6'),(746,207,238,133,0,8,'2018-08-29 16:37:18','2018-08-29 16:37:18','852ada71-9da8-459f-aed4-77305401034f'),(780,212,246,15,0,1,'2018-09-26 14:40:45','2018-09-26 14:40:45','68403d08-3e89-4971-9992-c48b0f79d12a'),(781,212,246,75,0,2,'2018-09-26 14:40:45','2018-09-26 14:40:45','241ff99b-6587-4950-973c-7d9ac67cfad9'),(782,212,246,127,0,3,'2018-09-26 14:40:45','2018-09-26 14:40:45','4621428b-2c6e-4486-b38a-9ab757cb6400'),(783,212,246,131,0,4,'2018-09-26 14:40:45','2018-09-26 14:40:45','30c872a3-b437-46e4-b13d-9a9bed25b7a6'),(784,212,246,141,0,5,'2018-09-26 14:40:45','2018-09-26 14:40:45','f73d758b-2904-459b-8670-e6fd9db266ad'),(785,212,246,145,0,6,'2018-09-26 14:40:45','2018-09-26 14:40:45','356043a0-1f6b-4310-9d0b-4eddcef879e8'),(786,212,246,82,0,7,'2018-09-26 14:40:45','2018-09-26 14:40:45','1fbfa41a-ee16-46de-b799-d64726ae1870'),(787,212,246,89,0,8,'2018-09-26 14:40:45','2018-09-26 14:40:45','d9dead9e-c323-499d-bfba-47420770d5e0'),(834,221,258,150,0,1,'2018-10-04 15:34:00','2018-10-04 15:34:00','1fa3378e-2e16-4ef3-b191-4470741e0943'),(937,231,280,4,0,1,'2018-10-16 13:49:35','2018-10-16 13:49:35','e8f3adfb-08eb-4007-862b-cfc69f201967'),(938,231,280,12,0,2,'2018-10-16 13:49:35','2018-10-16 13:49:35','899a9586-cf4a-44c9-860f-39e7bd2a0912'),(939,231,280,164,0,3,'2018-10-16 13:49:35','2018-10-16 13:49:35','9865b149-719f-477b-be76-46596dcbcb98'),(940,231,280,14,1,4,'2018-10-16 13:49:35','2018-10-16 13:49:35','8a73c679-69d6-4d2c-a426-4a93a0bfbf57'),(941,231,281,150,0,1,'2018-10-16 13:49:35','2018-10-16 13:49:35','aa1bce66-b954-4472-a6f9-d43a94e890f1'),(942,231,281,152,0,2,'2018-10-16 13:49:35','2018-10-16 13:49:35','b6d222fd-8857-4edb-86a6-dab9954935eb'),(956,247,288,113,0,1,'2018-11-23 15:20:11','2018-11-23 15:20:11','7d1ab612-ba98-4ecd-8638-c0c15df5cc26'),(957,247,288,114,0,2,'2018-11-23 15:20:11','2018-11-23 15:20:11','9c0d9854-d850-4c81-8722-7b11e550bd17'),(973,250,292,168,0,1,'2018-12-06 17:37:16','2018-12-06 17:37:16','5b913087-3688-4ed5-95a9-d57eed567270'),(974,250,292,169,0,2,'2018-12-06 17:37:16','2018-12-06 17:37:16','7f28301b-b968-43a4-a91b-a9af4bc9919c'),(975,251,293,166,1,1,'2018-12-06 17:45:11','2018-12-06 17:45:11','b6bb901e-638e-4bdc-98a4-51c801c8068a'),(976,251,293,38,0,2,'2018-12-06 17:45:11','2018-12-06 17:45:11','7d078679-9ae7-4f95-b81f-52da804c9cbb'),(977,251,293,68,0,3,'2018-12-06 17:45:11','2018-12-06 17:45:11','4130508a-3b00-448e-b6d8-9496c94ea3ab'),(978,251,293,19,0,4,'2018-12-06 17:45:11','2018-12-06 17:45:11','1d8e23e9-bd5d-4206-a9a1-4a0005a0801c'),(979,251,294,28,0,1,'2018-12-06 17:45:11','2018-12-06 17:45:11','35095f8b-e344-4ab6-8041-f11290a91d06'),(1000,254,301,16,0,1,'2018-12-11 07:29:35','2018-12-11 07:29:35','262ff915-ab40-4fd8-b8f1-3e95ae28df1d'),(1001,254,301,18,0,2,'2018-12-11 07:29:35','2018-12-11 07:29:35','376f9600-de8e-41f1-8816-90b1925f6d59'),(1002,254,301,39,0,3,'2018-12-11 07:29:35','2018-12-11 07:29:35','da313ef0-c6d9-4d16-ad7d-e931085ab487'),(1003,254,301,130,0,4,'2018-12-11 07:29:35','2018-12-11 07:29:35','d68c6867-df75-48f0-b12b-328490130cdb'),(1004,254,301,37,0,5,'2018-12-11 07:29:35','2018-12-11 07:29:35','9e48a434-d3e6-4f2a-bb82-f7a9fb023474'),(1005,254,301,74,0,6,'2018-12-11 07:29:35','2018-12-11 07:29:35','3ac07e4c-5a03-40a2-948a-5e62e014acef'),(1006,254,302,49,0,1,'2018-12-11 07:29:35','2018-12-11 07:29:35','8b22419d-e21c-4ab8-9d43-fcedb5ada8cf'),(1007,254,302,70,0,2,'2018-12-11 07:29:35','2018-12-11 07:29:35','ac466b04-4e58-4f38-8c24-95a4b13f8458'),(1008,254,302,40,0,3,'2018-12-11 07:29:35','2018-12-11 07:29:35','680940a1-2944-4490-8c0a-83d6417b41bf'),(1009,254,303,150,0,1,'2018-12-11 07:29:35','2018-12-11 07:29:35','07de9b6a-a4e4-4f9d-930f-254ba332c8bc'),(1010,255,304,167,0,1,'2018-12-11 07:56:08','2018-12-11 07:56:08','0d5c5bbe-27b4-41a9-a30e-fb39e839340b'),(1011,255,304,171,0,2,'2018-12-11 07:56:08','2018-12-11 07:56:08','118d8be3-f2b2-4d41-8b4d-0c04f6e5bccc'),(1012,256,305,71,0,1,'2018-12-11 08:11:03','2018-12-11 08:11:03','fb0608fe-8ee4-4bce-a464-b6084e6574a2'),(1013,256,305,116,0,2,'2018-12-11 08:11:03','2018-12-11 08:11:03','ff7ce352-0218-4473-965c-a2faaa6bdd86'),(1014,256,305,117,0,3,'2018-12-11 08:11:03','2018-12-11 08:11:03','bb151e5d-d2dd-48b4-84ce-cd9a3a991129'),(1015,256,305,118,0,4,'2018-12-11 08:11:03','2018-12-11 08:11:03','b76a4c73-c08c-45de-84dd-52ac5395d2ec'),(1016,256,305,119,0,5,'2018-12-11 08:11:03','2018-12-11 08:11:03','533a4e44-dece-4489-a99a-45e13265fc93'),(1017,256,305,107,0,6,'2018-12-11 08:11:03','2018-12-11 08:11:03','039e96c8-b7f9-49e8-b6a4-bb51935a3480'),(1018,256,305,111,0,7,'2018-12-11 08:11:03','2018-12-11 08:11:03','846e02fe-94b9-4d31-98a2-2e450de6bb08'),(1019,256,305,172,0,8,'2018-12-11 08:11:03','2018-12-11 08:11:03','6156c267-c9aa-45e4-ad6e-e481997e3b9b'),(1020,257,306,128,0,1,'2018-12-11 23:48:25','2018-12-11 23:48:25','3e978ea6-a242-47f3-9b90-897063b85b16'),(1021,257,306,3,0,2,'2018-12-11 23:48:25','2018-12-11 23:48:25','ade1260c-ced0-4a7c-9c82-203e83729544'),(1022,257,306,30,0,3,'2018-12-11 23:48:25','2018-12-11 23:48:25','ddbae8f6-aa6c-4ee6-97df-43e64c476f48'),(1023,257,306,129,0,4,'2018-12-11 23:48:25','2018-12-11 23:48:25','02016164-a591-4f6e-8df2-8c1f1127938d'),(1024,257,306,132,0,5,'2018-12-11 23:48:25','2018-12-11 23:48:25','5e6ff2c3-c3c8-4e8c-a0f6-b4d220c4f3fb'),(1025,257,306,170,0,6,'2018-12-11 23:48:25','2018-12-11 23:48:25','1c75e2e7-b2d2-4605-8e1b-f92066b52366'),(1026,257,306,173,0,7,'2018-12-11 23:48:25','2018-12-11 23:48:25','a3afc2d0-114b-4e99-9b0e-c7ee1552b261'),(1027,257,307,121,0,1,'2018-12-11 23:48:25','2018-12-11 23:48:25','d753bc5c-c036-4669-a71b-27d68ce007b6'),(1028,257,307,122,0,2,'2018-12-11 23:48:25','2018-12-11 23:48:25','7b7012f0-2be9-42b2-b350-539bd803786e'),(1029,257,307,123,0,3,'2018-12-11 23:48:25','2018-12-11 23:48:25','f39d6761-81c7-4b29-9fd3-51ce801850d8'),(1030,257,307,76,0,4,'2018-12-11 23:48:25','2018-12-11 23:48:25','8fe3a170-10d8-4048-a656-167a52ca29bb'),(1032,257,307,84,0,6,'2018-12-11 23:48:25','2018-12-11 23:48:25','3314a343-35a1-4635-9a48-2eed8bb93d8c'),(1033,257,308,77,0,1,'2018-12-11 23:48:25','2018-12-11 23:48:25','34a2baa2-cf37-4054-b5f0-15fc797db883'),(1034,257,309,124,0,1,'2018-12-11 23:48:25','2018-12-11 23:48:25','eb6b5dfe-c00d-4ab8-a1df-6a4da609a0a4'),(1035,257,310,150,0,1,'2018-12-11 23:48:25','2018-12-11 23:48:25','80dfc790-41fc-466f-a956-f1a4ff4ded75'),(1036,257,310,147,0,2,'2018-12-11 23:48:25','2018-12-11 23:48:25','888fc0ba-6441-4099-990e-7c64b5f3a9d3'),(1037,257,310,148,0,3,'2018-12-11 23:48:25','2018-12-11 23:48:25','46534a9d-d3f4-4e50-bd0a-dffce0e7c41b'),(1038,257,310,149,0,4,'2018-12-11 23:48:25','2018-12-11 23:48:25','1ac3cb65-3787-4010-92ea-4e0ce6a7696f'),(1039,257,310,151,0,5,'2018-12-11 23:48:25','2018-12-11 23:48:25','6fe56960-ca8f-4a1b-8e9d-6e3c2dd9fb32'),(1040,258,311,2,0,1,'2018-12-12 00:07:57','2018-12-12 00:07:57','cdcc546f-ac61-44be-b612-3306fe70732d'),(1041,258,311,112,0,2,'2018-12-12 00:07:57','2018-12-12 00:07:57','43aa11a8-90dc-4341-b979-e5fa48e3dfdd'),(1042,258,311,115,0,3,'2018-12-12 00:07:57','2018-12-12 00:07:57','076d84db-5399-43a3-a392-7975cf0a158b'),(1043,259,312,29,0,1,'2018-12-12 06:50:08','2018-12-12 06:50:08','5dcd039e-f236-477e-8241-b61e33346b9a'),(1044,259,312,33,0,2,'2018-12-12 06:50:08','2018-12-12 06:50:08','b67705f3-d753-45ed-b4cc-9ad24550cd53'),(1045,259,312,34,0,3,'2018-12-12 06:50:08','2018-12-12 06:50:08','6e4e5b95-0546-40bd-b7c7-a126042188eb'),(1046,259,313,17,0,1,'2018-12-12 06:50:08','2018-12-12 06:50:08','ea398a05-4413-4933-a2ae-17ef16df1ae5'),(1047,259,313,142,0,2,'2018-12-12 06:50:08','2018-12-12 06:50:08','6181afd8-aff4-47a4-ada5-61477bf87fd5'),(1048,259,313,143,0,3,'2018-12-12 06:50:08','2018-12-12 06:50:08','2945d9b6-91e1-4102-96a0-142df8220f19'),(1049,259,313,144,0,4,'2018-12-12 06:50:08','2018-12-12 06:50:08','bde025e2-c7df-4603-8c6f-cc3940435603'),(1050,259,313,163,0,5,'2018-12-12 06:50:08','2018-12-12 06:50:08','8fcdf85e-5bea-47d6-ad70-00a3247e2f15'),(1051,259,313,86,0,6,'2018-12-12 06:50:08','2018-12-12 06:50:08','008f18e1-1643-4812-91da-b7c195e50de0'),(1052,259,313,130,0,7,'2018-12-12 06:50:08','2018-12-12 06:50:08','b99f93e3-813b-4b74-8df6-7c340ef85f91'),(1053,259,313,67,0,8,'2018-12-12 06:50:08','2018-12-12 06:50:08','c69efe2a-79c0-4693-b0aa-342654320dd7'),(1054,259,313,146,0,9,'2018-12-12 06:50:08','2018-12-12 06:50:08','72d0a30f-fe02-4d5c-b660-b511666641a2'),(1055,260,314,29,0,1,'2018-12-12 07:00:33','2018-12-12 07:00:33','3598f0f8-f4ee-4a45-b064-53952ca75f99'),(1056,260,314,26,0,2,'2018-12-12 07:00:33','2018-12-12 07:00:33','d320e9a1-3556-466e-b2af-964ef2f61265'),(1057,260,314,33,0,3,'2018-12-12 07:00:33','2018-12-12 07:00:33','63bbd473-6216-46d6-8870-2a4812b4be7e'),(1058,260,314,130,0,4,'2018-12-12 07:00:33','2018-12-12 07:00:33','d7d60f59-e101-4598-aa5d-5dd3ae492e8a'),(1059,260,314,34,0,5,'2018-12-12 07:00:33','2018-12-12 07:00:33','51533969-7136-4da5-92f3-bb1704d355d5'),(1060,260,315,17,0,1,'2018-12-12 07:00:33','2018-12-12 07:00:33','6f9fccec-f0b1-488d-a847-ed992fb76be7'),(1061,260,315,86,0,2,'2018-12-12 07:00:33','2018-12-12 07:00:33','ad61b6d3-8ce9-4cba-9575-5a3254336e34'),(1062,260,315,142,0,3,'2018-12-12 07:00:33','2018-12-12 07:00:33','579dae83-021d-4bef-bcf2-5f7f56b436ce'),(1063,260,315,143,0,4,'2018-12-12 07:00:33','2018-12-12 07:00:33','fe9274f4-826b-443a-9cfe-e3cf2cdeb784'),(1064,260,315,144,0,5,'2018-12-12 07:00:33','2018-12-12 07:00:33','ff9f1572-4495-434e-9ebc-23ca8af81e89'),(1065,260,316,58,0,1,'2018-12-12 07:00:33','2018-12-12 07:00:33','34b9d28f-6c13-45a0-a38d-d043f041e8c5'),(1066,260,316,27,0,2,'2018-12-12 07:00:33','2018-12-12 07:00:33','5ef2bfb4-4646-4afc-9f2a-0a2789b6d92a');
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
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_fieldlayouts`
--

LOCK TABLES `craft_fieldlayouts` WRITE;
/*!40000 ALTER TABLE `craft_fieldlayouts` DISABLE KEYS */;
INSERT INTO `craft_fieldlayouts` VALUES (1,'Tag','2017-10-23 13:26:43','2017-10-23 13:26:43','a33c5200-627f-4920-a685-580ea708b281'),(71,'Entry','2018-03-07 14:02:01','2018-03-07 14:02:01','bcaf5920-c460-4465-a5db-e7f4bad29958'),(74,'MatrixBlock','2018-03-09 11:20:19','2018-03-09 11:20:19','11bf57e1-f8b5-4045-9861-210b0d80c82b'),(94,'MatrixBlock','2018-04-13 18:13:59','2018-04-13 18:13:59','e8be7915-d15b-4c24-ab6e-7fd24b6960df'),(95,'MatrixBlock','2018-04-13 18:13:59','2018-04-13 18:13:59','0df79c77-16b6-457e-9e38-f90d2b470226'),(96,'MatrixBlock','2018-04-13 18:13:59','2018-04-13 18:13:59','a4e23074-f1e3-4121-bffd-2b6ca10ae1d6'),(97,'MatrixBlock','2018-04-13 18:20:05','2018-04-13 18:20:05','b6774e86-2d02-4f1e-8fcc-df899659d76c'),(122,'Entry','2018-04-19 14:53:29','2018-04-19 14:53:29','46202269-1d4b-4803-aef3-1a626eaf5054'),(138,'GlobalSet','2018-04-29 12:42:06','2018-04-29 12:42:06','9f201540-f939-4736-88e9-92545fb318dc'),(147,'MatrixBlock','2018-05-31 13:06:54','2018-05-31 13:06:54','aca00fb2-fff5-4778-bbcb-48193d2a64ec'),(155,'Entry','2018-06-04 11:08:02','2018-06-04 11:08:02','c191d023-b6eb-4e41-a923-50b86effcac5'),(165,'Entry','2018-08-20 15:36:56','2018-08-20 15:36:56','7cd7bc12-750d-4828-bf80-02a229e934e2'),(171,'MatrixBlock','2018-08-21 15:27:31','2018-08-21 15:27:31','d3af77e8-adbb-403d-8ca0-78f119435628'),(194,'MatrixBlock','2018-08-26 15:03:37','2018-08-26 15:03:37','97bd0860-90f2-4176-ae3e-bc916e7617e1'),(207,'GlobalSet','2018-08-29 16:37:18','2018-08-29 16:37:18','ff73935a-12c8-4b9e-b421-15d3dc44cf53'),(212,'GlobalSet','2018-09-26 14:40:45','2018-09-26 14:40:45','aabd98d8-acda-4f5c-bfe8-06ec0851cc50'),(221,'Category','2018-10-04 15:34:00','2018-10-04 15:34:00','6c3df5bf-813e-461a-8b1d-1f611d961515'),(231,'Entry','2018-10-16 13:49:35','2018-10-16 13:49:35','ed88ea3b-52b6-408a-aa05-a271ac4fce97'),(237,'Asset','2018-11-15 12:03:31','2018-11-15 12:03:31','a07a0368-cebb-4fac-9adf-68c930cde7ad'),(238,'Asset','2018-11-15 12:03:39','2018-11-15 12:03:39','d9ac80cd-0aa0-4845-b996-98bb74732f09'),(239,'Asset','2018-11-15 12:03:44','2018-11-15 12:03:44','423e10b2-7ff9-4384-a7e5-f920e986619f'),(240,'Asset','2018-11-15 12:03:48','2018-11-15 12:03:48','efb190cb-6f86-4264-ae6a-54c8995dad64'),(247,'MatrixBlock','2018-11-23 15:20:11','2018-11-23 15:20:11','33a2cd8b-939a-4112-af78-f861c965887f'),(250,'SuperTable_Block','2018-12-06 17:37:16','2018-12-06 17:37:16','98d38a79-4323-4b94-bcb7-3fd55fec5717'),(251,'Entry','2018-12-06 17:45:11','2018-12-06 17:45:11','36292cd2-5fa9-4c5a-b8a0-ff00b4ec24d5'),(254,'Entry','2018-12-11 07:29:35','2018-12-11 07:29:35','18dcb8d9-f4fc-4c59-9835-d45a967377b1'),(255,'Category','2018-12-11 07:56:08','2018-12-11 07:56:08','9d3f4f85-fa54-4b4d-961e-49e4fb6f86ad'),(256,'GlobalSet','2018-12-11 08:11:03','2018-12-11 08:11:03','c188e085-0e55-4267-8971-8bcaaaa67e88'),(257,'User','2018-12-11 23:48:25','2018-12-11 23:48:25','613b0c98-e1f7-4440-a71c-37cb38fc13cf'),(258,'Entry','2018-12-12 00:07:57','2018-12-12 00:07:57','6d710b1b-95a9-4417-b0dd-78a03a5baa40'),(259,'Entry','2018-12-12 06:50:08','2018-12-12 06:50:08','2c289e80-377b-4aca-a4e2-1f6f34f7af18'),(260,'Entry','2018-12-12 07:00:33','2018-12-12 07:00:33','b50f1b3d-3a97-492f-bc02-621a1f373c14');
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
) ENGINE=InnoDB AUTO_INCREMENT=317 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_fieldlayouttabs`
--

LOCK TABLES `craft_fieldlayouttabs` WRITE;
/*!40000 ALTER TABLE `craft_fieldlayouttabs` DISABLE KEYS */;
INSERT INTO `craft_fieldlayouttabs` VALUES (59,71,'Team',1,'2018-03-07 14:02:01','2018-03-07 14:02:01','17726e0f-bba0-4387-b32c-c4b7669d5314'),(60,71,'Managers',2,'2018-03-07 14:02:01','2018-03-07 14:02:01','34dad6b8-3121-4920-b52a-49d33fb80d19'),(64,74,'Content',1,'2018-03-09 11:20:19','2018-03-09 11:20:19','bd3bfe6c-42d3-4054-a222-764ae1c800f0'),(81,94,'Content',1,'2018-04-13 18:13:59','2018-04-13 18:13:59','d3b7f8dd-022f-4c0e-a0b6-106deab1b09b'),(82,95,'Content',1,'2018-04-13 18:13:59','2018-04-13 18:13:59','ff556011-72dc-4030-8dae-7b28e210bb74'),(83,96,'Content',1,'2018-04-13 18:13:59','2018-04-13 18:13:59','57d39d0a-5b6d-49e7-8642-4ad057eb0b6b'),(84,97,'Content',1,'2018-04-13 18:20:05','2018-04-13 18:20:05','6e36f4f6-b9eb-4f91-8bc1-40b9eb04b033'),(124,122,'Attempt',1,'2018-04-19 14:53:29','2018-04-19 14:53:29','e90ea629-d627-463a-a052-71d59156dd67'),(141,138,'Content',1,'2018-04-29 12:42:06','2018-04-29 12:42:06','eaaf9451-d897-4a7a-a633-c3e6d1ba8a17'),(151,147,'Content',1,'2018-05-31 13:06:54','2018-05-31 13:06:54','37d94016-ac46-48be-8516-349f49617815'),(162,155,'Result',1,'2018-06-04 11:08:02','2018-06-04 11:08:02','36b82542-45b0-40f5-900a-658e7defff8b'),(180,165,'Reports',1,'2018-08-20 15:36:56','2018-08-20 15:36:56','9c3b8d5c-312f-441a-bc5d-d396d87f0804'),(181,165,'Criteria',2,'2018-08-20 15:36:56','2018-08-20 15:36:56','12ecb01b-3efc-4031-a77d-ae6a479edaf1'),(182,165,'Recipients',3,'2018-08-20 15:36:56','2018-08-20 15:36:56','b4a728e4-1321-48ce-803e-131c176f2162'),(183,165,'Data',4,'2018-08-20 15:36:56','2018-08-20 15:36:56','679094b0-a76c-4690-a24d-a825bba5c754'),(185,171,'Content',1,'2018-08-21 15:27:31','2018-08-21 15:27:31','8ff98f59-a3c6-4829-ac4a-dc5cac1d1fc0'),(209,194,'Content',1,'2018-08-26 15:03:37','2018-08-26 15:03:37','484920ed-ad0b-44c0-b735-3e340c5bef02'),(238,207,'Content',1,'2018-08-29 16:37:18','2018-08-29 16:37:18','0f601fef-931d-49c6-8cfb-301a58247aec'),(246,212,'Content',1,'2018-09-26 14:40:45','2018-09-26 14:40:45','92097e6e-072f-4092-9292-7621d4d35924'),(258,221,'Fields',1,'2018-10-04 15:34:00','2018-10-04 15:34:00','8013455a-8bb2-456d-a519-5ec056f4a9fc'),(280,231,'Company',1,'2018-10-16 13:49:35','2018-10-16 13:49:35','72c60610-5fb7-428b-80e8-456b4215c6bc'),(281,231,'Legacy',2,'2018-10-16 13:49:35','2018-10-16 13:49:35','4f722052-d6b7-4c46-a11a-530f79c3d210'),(288,247,'Content',1,'2018-11-23 15:20:11','2018-11-23 15:20:11','47b51dff-5092-47cc-88a1-f91f4d87f915'),(292,250,'Content',1,'2018-12-06 17:37:16','2018-12-06 17:37:16','39bb2cf5-c326-4a8d-8b80-0b0e79c0d1f6'),(293,251,'Module',1,'2018-12-06 17:45:11','2018-12-06 17:45:11','3d20f4b1-a66a-4c74-b8d2-8c0e0272d82c'),(294,251,'Job Roles',2,'2018-12-06 17:45:11','2018-12-06 17:45:11','2b9db27f-9dbc-43b4-be96-fae09946c117'),(301,254,'Unit',1,'2018-12-11 07:29:35','2018-12-11 07:29:35','7b8981a4-cceb-4ba3-9139-e27e222ee64e'),(302,254,'Test',2,'2018-12-11 07:29:35','2018-12-11 07:29:35','f5f11893-bbff-4514-bc52-5cf2d656e421'),(303,254,'Legacy',3,'2018-12-11 07:29:35','2018-12-11 07:29:35','2d9d5839-e15c-48b4-9f65-eece7f42b779'),(304,255,'Settings',1,'2018-12-11 07:56:08','2018-12-11 07:56:08','4f046bcb-0a6c-4ff3-b4ec-5493293ffca6'),(305,256,'Content',1,'2018-12-11 08:11:03','2018-12-11 08:11:03','9488ac69-423e-48b2-a62c-9198f19b6af5'),(306,257,'Structure',1,'2018-12-11 23:48:25','2018-12-11 23:48:25','bea53fe3-f3e9-4187-a6f4-39cb4eb9023e'),(307,257,'Profile',2,'2018-12-11 23:48:25','2018-12-11 23:48:25','c4a634aa-4ba9-4840-96df-fe9ed0975421'),(308,257,'Payments',3,'2018-12-11 23:48:25','2018-12-11 23:48:25','5dfecd57-6d45-4fb0-aa3f-5a33641e41df'),(309,257,'Custom Scheme User Fields',4,'2018-12-11 23:48:25','2018-12-11 23:48:25','266f2402-e897-423b-95b2-664f260785f3'),(310,257,'Legacy',5,'2018-12-11 23:48:25','2018-12-11 23:48:25','e3c3e501-d68c-4387-972b-6b3904e34a6e'),(311,258,'Pages',1,'2018-12-12 00:07:57','2018-12-12 00:07:57','94ad671f-0d63-4c8e-819e-eeeed2cb3747'),(312,259,'Result',1,'2018-12-12 06:50:08','2018-12-12 06:50:08','277c7732-576f-4da5-8abe-248120558930'),(313,259,'Evidence',2,'2018-12-12 06:50:08','2018-12-12 06:50:08','e559e5b1-dc3a-45bc-8f1f-e54cad8abf05'),(314,260,'Result',1,'2018-12-12 07:00:33','2018-12-12 07:00:33','2f944b4b-dfc3-4451-bb0e-b73fd67ef3f4'),(315,260,'Evidence',2,'2018-12-12 07:00:33','2018-12-12 07:00:33','7bed5acb-5b7f-4ddb-aae5-f93b6938e7da'),(316,260,'Test',3,'2018-12-12 07:00:33','2018-12-12 07:00:33','9a805b1d-53c8-4165-aa28-51c596ade157');
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
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_fields`
--

LOCK TABLES `craft_fields` WRITE;
/*!40000 ALTER TABLE `craft_fields` DISABLE KEYS */;
INSERT INTO `craft_fields` VALUES (1,1,'Body','pageBody','global','',0,'RichText','{\"configFile\":\"Standard.json\",\"availableAssetSources\":\"*\",\"availableTransforms\":\"*\",\"cleanupHtml\":\"1\",\"purifyHtml\":\"1\",\"purifierConfig\":\"\",\"columnType\":\"text\"}','2017-10-23 13:26:43','2017-10-23 13:51:25','f16d7384-1325-4296-8acb-e213c2765770'),(2,1,'Heading','pageHeading','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2017-10-23 13:26:43','2017-10-23 13:51:52','6b431a5f-e1ee-4520-ab30-89ca72993c00'),(3,2,'User Team','userTeam','global','',0,'Entries','{\"sources\":[\"section:5\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 13:52:38','2018-03-09 11:55:49','ca3cc99a-ddf3-4e81-b35c-37d0ecd39ebb'),(4,3,'Company Parent','companyParent','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 13:53:58','2018-02-14 11:16:21','75ec7211-a9c5-4390-963a-9d63dfd0dbd4'),(5,3,'Location Company ','locationCompany','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 13:54:27','2017-10-23 14:38:05','779ea841-f13a-4c87-aa19-bb807eaa7b16'),(6,3,'Location Name','locationName','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2017-10-23 14:37:47','2017-10-23 14:37:54','14ddc2fe-abf2-424c-882c-3f23d87dc4d2'),(8,3,'Team Name','teamName','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2017-10-23 14:52:21','2017-10-23 14:52:21','fcef71c8-c93e-4e97-a8f3-4fc223b2d223'),(9,3,'Team Company','teamCompany','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 14:52:44','2018-02-14 11:19:18','0578b9f0-6e63-4ecd-b386-295f3b276282'),(10,3,'Team Primary Manager','teamPrimaryManager','global','',0,'Users','{\"sources\":[\"group:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 15:07:43','2017-10-23 15:07:43','5839b521-ccd0-4768-bbe4-ef59335fb732'),(11,3,'Team Secondary Managers','teamSecondaryManagers','global','',0,'Users','{\"sources\":[\"group:3\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2017-10-23 15:08:10','2017-10-23 15:08:10','838b996f-8f5d-4df2-9a75-0d1692348632'),(12,3,'Company Primary Manager','companyPrimaryManager','global','',0,'Users','{\"sources\":[\"group:2\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-23 15:10:55','2018-10-17 13:02:10','fa734878-4241-4654-86c9-50964d8a8692'),(14,3,'Company Remaining Licences','companyRemainingLicences','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2017-10-23 15:34:07','2017-10-23 15:34:07','a8d84419-f7b9-4d64-b7cb-ce827c7ca810'),(15,10,'Scheme Remaining Licences','schemeRemainingLicences','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2017-10-23 15:35:34','2018-09-26 14:46:55','bba70dad-a42e-494b-b2a4-8355984dcadf'),(16,4,'Unit Type','unitType','global','',0,'Dropdown','{\"options\":[{\"label\":\"Evidence\",\"value\":\"evidence\",\"default\":\"1\"},{\"label\":\"E-Learning\",\"value\":\"elearning\",\"default\":\"\"}]}','2017-10-24 09:47:09','2018-12-11 07:30:09','995ba33c-928b-43a5-8d39-53c4c36782ed'),(17,6,'Result Evidence','resultEvidence','global','',0,'Assets','{\"useSingleFolder\":\"1\",\"sources\":\"*\",\"defaultUploadLocationSource\":\"1\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"{author.id}\",\"restrictFiles\":\"\",\"allowedKinds\":[\"image\",\"pdf\"],\"limit\":\"\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2017-10-24 09:47:52','2018-12-12 07:11:46','e7ff470a-3e85-47be-9037-1508ea540121'),(18,4,'URL (e-learning)','unitUrl','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2017-10-24 09:50:33','2017-10-24 09:55:56','28e570b6-14a0-4b5e-be07-37e03f6b9b8f'),(19,4,'Unit Groups','moduleUnitGroups','global','',0,'Matrix','{\"maxBlocks\":null}','2017-10-24 09:55:27','2018-03-09 11:20:18','6f94bf9b-3168-437e-b8f2-7d082dcdd831'),(20,NULL,'Name','groupName','matrixBlockType:1','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2017-10-24 09:55:27','2018-03-09 11:20:19','45d289dd-249a-42e9-95ae-62ab979e8b02'),(25,NULL,'Unit Entries','unitEntries','matrixBlockType:1','',0,'Entries','{\"sources\":[\"section:7\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2017-10-24 10:13:39','2018-03-09 11:20:19','a773e69f-0bf1-46fd-ae75-5182be37772c'),(26,6,'Result Unit','resultUnit','global','',0,'Entries','{\"sources\":[\"section:7\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2017-10-24 10:47:23','2018-04-13 18:58:17','3103beed-feb4-4eed-b71a-68cb81d67f7e'),(27,6,'Result Score','resultScore','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2017-10-24 10:51:16','2018-04-13 18:57:05','5af516f7-8267-4333-b0d0-0ff4f5ee41bb'),(28,4,'Job Roles','moduleRoles','global','',0,'Categories','{\"source\":\"group:1\",\"limit\":\"\",\"selectionLabel\":\"\"}','2017-10-24 10:54:23','2017-10-26 12:53:07','f08f684f-1af1-417b-94f2-5df2f3ac7da6'),(29,6,'Result Status','resultStatus','global','',0,'Status','{\"statuses\":{\"col1\":{\"name\":\"Pending\",\"handle\":\"pending\",\"color\":\"#ffff00\",\"default\":\"1\"},\"1\":{\"name\":\"Failed\",\"handle\":\"failed\",\"color\":\"#ff0000\",\"default\":\"\"},\"0\":{\"name\":\"Endorsed\",\"handle\":\"endorsed\",\"color\":\"#008000\",\"default\":\"\"},\"2\":{\"name\":\"Active\",\"handle\":\"active\",\"color\":\"#000000\",\"default\":\"\"},\"3\":{\"name\":\"Complete\",\"handle\":\"complete\",\"color\":\"#008000\",\"default\":\"\"},\"4\":{\"name\":\"Blocked\",\"handle\":\"blocked\",\"color\":\"#000000\",\"default\":\"\"}}}','2017-10-24 11:50:38','2018-06-02 11:35:16','3ccae116-4500-45d0-a56a-024f2e8d4d03'),(30,2,'User Role','userRole','global','',0,'Categories','{\"source\":\"group:1\",\"limit\":\"\",\"selectionLabel\":\"\"}','2018-02-14 11:23:17','2018-02-14 11:24:28','dbbf1f49-3b86-4c6d-aa5a-4170483a35f1'),(31,3,'Team Description','teamDescription','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"4\"}','2018-02-14 11:29:48','2018-02-14 11:29:48','2722a41a-2635-4f3b-8ecb-d39db9efa049'),(32,4,'Module Description','moduleDescription','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-02-14 11:46:56','2018-02-14 11:46:56','d1739b26-92c4-4d41-8e2b-2064ddd0ae41'),(33,6,'Result Endorsed Date','resultEndorsedDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-02-14 11:59:46','2018-04-13 18:56:39','5dd21124-5f28-49d6-8f3e-d70184415478'),(34,6,'Result Comments','resultComments','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"4\"}','2018-02-14 12:01:52','2018-04-13 18:56:27','917de597-1803-4130-bea7-cced2a8c05f4'),(37,4,'Unit Description','unitDescription','global','Add a short description of this unit',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-03-09 11:00:26','2018-05-30 09:26:25','98e01bd1-bcac-4523-8399-0c55010d13b6'),(38,4,'Module Completed Value','moduleCompletedValue','global','Enter the total value of the points needed to complete this module.',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-03-09 11:10:06','2018-03-09 11:12:13','c6890220-ae06-4dc6-a162-58ef3815e8c5'),(39,4,'Unit Value','unitValue','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-03-09 11:21:02','2018-03-09 11:21:02','c708fb49-2ead-4372-bb3f-c925d3ab9f41'),(40,5,'Test Questions','testQuestions','global','',0,'Matrix','{\"maxBlocks\":null}','2018-04-13 10:18:08','2018-04-13 18:13:59','e875ae7a-06f3-4063-a7c9-0b3fbb8f75b9'),(41,NULL,'Question','question','matrixBlockType:2','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','265e7462-688b-4c0b-a1b2-6f25d1b08525'),(42,NULL,'Answer','answer','matrixBlockType:2','',0,'Lightswitch','{\"default\":\"\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','9bb07531-d5ed-4c48-9a33-67ca0c17882b'),(43,NULL,'Asset','asset','matrixBlockType:2','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:7\"],\"defaultUploadLocationSource\":\"2\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"limit\":\"1\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','6f6554bf-571c-46f8-a4eb-37e34c65b1a8'),(44,NULL,'Question','question','matrixBlockType:3','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','29245631-dbe4-4182-85d0-ae4290604622'),(45,NULL,'Answers','answers','matrixBlockType:3','',0,'Table','{\"columns\":{\"col1\":{\"heading\":\"Answer\",\"handle\":\"answer\",\"width\":\"\",\"type\":\"singleline\"},\"col2\":{\"heading\":\"Correct\",\"handle\":\"correct\",\"width\":\"\",\"type\":\"checkbox\"}},\"defaults\":{\"row1\":{\"col1\":\"\",\"col2\":\"\"}}}','2018-04-13 10:18:09','2018-04-13 18:13:59','6e83784c-1c34-4973-9929-0307ffb6cff0'),(46,NULL,'Asset','asset','matrixBlockType:3','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:7\"],\"defaultUploadLocationSource\":\"2\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"limit\":\"\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','f523e5d2-5acc-48c3-ab1d-fba29e427581'),(47,NULL,'Question','question','matrixBlockType:4','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','74e30584-f92e-4d01-a638-7d173cc7b930'),(48,NULL,'Asset','asset','matrixBlockType:4','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:7\"],\"defaultUploadLocationSource\":\"2\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"limit\":\"\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-04-13 10:18:09','2018-04-13 18:13:59','08a001af-8773-420d-ab49-41848341478c'),(49,5,'Test Pass Percent','testPassPercent','global','',0,'Number','{\"min\":\"0\",\"max\":\"100\",\"decimals\":\"0\"}','2018-04-13 10:24:11','2018-04-13 10:36:15','8a7a272d-f35d-4ec6-9c9a-571c7f0f40d7'),(50,5,'Attempt Answers','attemptAnswers','global','',0,'Matrix','{\"maxBlocks\":null}','2018-04-13 10:29:34','2018-04-13 18:20:05','4b25bd1b-f51c-4175-8db7-e871de866f2f'),(51,NULL,'Question ID','questionId','matrixBlockType:5','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-04-13 10:29:34','2018-04-13 18:20:05','3a779dd7-470c-4c09-9363-7f1be3a04a59'),(52,NULL,'Answer','answer','matrixBlockType:5','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-13 10:29:34','2018-04-13 18:20:05','c7ee8208-f166-48c8-b7c2-e8eab9555b64'),(53,NULL,'Correct','correct','matrixBlockType:5','',0,'Lightswitch','{\"default\":\"\"}','2018-04-13 10:29:34','2018-04-13 18:20:05','57ccb83f-5e54-480a-90b7-e80dfbc8cc94'),(55,5,'Attempt Unit','attemptUnit','global','',0,'Entries','{\"sources\":[\"section:7\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2018-04-13 10:30:46','2018-04-19 13:59:15','fbd50700-a63f-4a13-b729-1947510b8a14'),(56,NULL,'Question','question','matrixBlockType:5','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-13 18:20:05','2018-04-13 18:20:05','09efb818-9488-45a7-b860-1fc9d3c99155'),(58,6,'Result Attempts','resultAttempts','global','',0,'Entries','{\"sources\":[\"section:12\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-04-13 18:52:48','2018-04-27 19:38:25','6cb9aa27-e25b-4a44-8dca-f20fca5f3588'),(67,6,'Result Module','resultModule','global','',0,'Entries','{\"sources\":[\"section:6\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-04-19 14:25:30','2018-04-19 14:25:40','295e5043-483c-4ad9-bfaa-bac22b99dc19'),(68,4,'Module Expiry Days','moduleExpiryDays','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-04-19 16:07:42','2018-04-19 16:07:42','646454e2-66f9-4f03-bac7-b94a89aba17a'),(70,5,'Max Attempts','testMaxAttempts','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-04-27 19:31:41','2018-04-27 20:09:48','4209884c-57c0-48b6-bb15-06361f5e0182'),(71,9,'Scheme Name','schemeName','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-29 12:40:10','2018-08-23 14:40:08','0c1394ba-3007-4f8c-89cb-7164b2b3b463'),(72,10,'Date Format','dateFormat','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-04-29 12:41:15','2018-09-26 14:46:17','5cd2f261-b203-491f-9549-8d181170d03c'),(73,10,'Default Limit','defaultLimit','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-04-29 12:41:44','2018-09-26 14:46:12','444569cd-536b-42db-8c1a-01d52860bd86'),(74,4,'Unit Image','unitImage','global','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:7\"],\"defaultUploadLocationSource\":\"2\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"1\",\"allowedKinds\":[\"image\"],\"limit\":\"1\",\"viewMode\":\"list\",\"selectionLabel\":\"Add a unit image\"}','2018-05-02 10:29:20','2018-05-02 10:29:20','6616a98b-5530-40e0-93db-6e73e439e274'),(75,10,'Scheme Expiry Date','schemeExpiryDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-05-02 10:37:28','2018-09-26 14:47:29','6aa5a52d-d403-4d48-bb69-c949bf60a15e'),(76,2,'User Telephone','userTelephone','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-05-30 09:43:57','2018-05-31 13:41:33','e85a0c45-d4c3-4d25-81e6-80819d599872'),(77,2,'User Payments','userPayments','global','',0,'Matrix','{\"maxBlocks\":null}','2018-05-31 13:06:53','2018-05-31 13:06:53','7f1c4f49-327b-48eb-b631-5950532840c2'),(78,NULL,'Payer Email','payer_email','matrixBlockType:6','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-05-31 13:06:53','2018-05-31 13:06:53','70223568-9924-4df6-a581-7134a87dc443'),(79,NULL,'Payment Amount','mc_gross','matrixBlockType:6','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-05-31 13:06:54','2018-05-31 13:06:54','0507fd56-62a9-42b1-a4ce-7f5e221df8a2'),(80,NULL,'Transaction ID','txn_id','matrixBlockType:6','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-05-31 13:06:54','2018-05-31 13:06:54','c82b9786-ae99-4b9d-89ef-a97b92d4366d'),(82,10,'Individual Licence Days','individualLicenceDays','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-05-31 13:42:07','2018-09-26 14:47:19','2c306d61-76de-4616-9db0-1f7a27cd922f'),(84,2,'User Expiry Date','userExpiryDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-06-01 11:18:50','2018-06-01 11:18:50','041dc464-9e48-4a7d-9999-6cf36dd10aaa'),(86,6,'Result Notes','resultNotes','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"4\"}','2018-06-02 11:22:42','2018-06-02 11:22:42','53ded07f-6c81-4e7c-8e25-520b62d1a532'),(89,10,'Individual Licence PayPal Button','individualLicencePaypalButton','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"4\"}','2018-06-05 13:23:38','2018-09-26 14:46:33','82d74ce5-66da-4c2e-95f5-80488adc8406'),(90,8,'Report Type','reportType','global','',0,'Dropdown','{\"options\":[{\"label\":\"Users\",\"value\":\"users\",\"default\":\"\"},{\"label\":\"Results\",\"value\":\"results\",\"default\":\"\"}]}','2018-08-13 12:11:11','2018-08-20 15:41:51','1a27fa24-b0d3-491d-8b77-f1656c3afc77'),(91,8,'Report Companies','reportCompanies','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-08-13 12:11:30','2018-08-13 12:11:30','d4776dfe-b66f-4a88-931c-d2bebb2d68d1'),(92,8,'Report Teams','reportTeams','global','',0,'Entries','{\"sources\":[\"section:5\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-08-13 12:11:46','2018-08-13 12:11:46','abb92e86-cb1b-456e-a618-befafa8e8a28'),(93,8,'Report Modules','reportModules','global','',0,'Entries','{\"sources\":[\"section:6\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-08-13 12:11:59','2018-08-13 12:11:59','c2e23d12-1d3f-4cbd-a3cb-be22ea64bdca'),(94,8,'Report Roles','reportRoles','global','',0,'Categories','{\"source\":\"group:1\",\"limit\":\"\",\"selectionLabel\":\"\"}','2018-08-13 12:12:16','2018-08-13 12:41:43','2f940be6-d44c-47bf-95b6-392608ee2547'),(95,8,'Report All Companies','reportAllCompanies','global','',0,'Lightswitch','{\"default\":\"\"}','2018-08-13 12:12:32','2018-08-13 12:12:32','d500f1e4-fb5b-435a-af01-cc2402b4a8c3'),(96,8,'Report All Teams','reportAllTeams','global','',0,'Lightswitch','{\"default\":\"\"}','2018-08-13 12:12:41','2018-08-13 12:12:53','7b7af1dc-c01f-4f73-b3f4-a83ff572e941'),(97,8,'Report All Modules','reportAllModules','global','',0,'Lightswitch','{\"default\":\"\"}','2018-08-13 12:13:21','2018-08-13 12:13:21','c9d661c9-fd44-473b-a7ef-70f4bc6340ea'),(98,8,'Report All Roles','reportAllRoles','global','',0,'Lightswitch','{\"default\":\"\"}','2018-08-13 12:13:44','2018-08-13 12:41:35','27e57a2d-2938-41e8-ab58-72f377ca20c1'),(99,8,'Report Recipients','reportRecipients','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-13 12:14:16','2018-08-13 12:14:16','e0c4c343-2eda-4447-baf6-836f09723985'),(100,8,'Report Send Frequency','reportSendFrequency','global','',0,'Dropdown','{\"options\":[{\"label\":\"Never\",\"value\":\"never\",\"default\":\"\"},{\"label\":\"Weekly\",\"value\":\"weekly\",\"default\":\"\"},{\"label\":\"Monthly\",\"value\":\"monthly\",\"default\":\"\"}]}','2018-08-13 12:15:58','2018-08-13 13:08:11','dbedebea-c884-4f61-8f0f-ebc64b3a5ed2'),(101,8,'Report Send Value','reportSendValue','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-08-13 12:16:58','2018-08-13 12:16:58','c996fdb5-0318-4df3-87bd-b42b45db2cab'),(102,8,'Report Description','reportDescription','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"2\"}','2018-08-13 12:20:32','2018-08-13 12:23:06','d1300c08-840a-419d-b910-de865842ddd8'),(103,8,'Report Count','reportCount','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-08-13 12:20:53','2018-08-13 12:20:53','bebe1bc3-81ca-46c1-86ac-1ad88d0a0f92'),(104,8,'Report Last Sent Date','reportLastSentDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-08-13 12:21:06','2018-08-13 18:12:54','1a8a5dc7-66a0-4520-957d-7dec3aa3cf2f'),(105,8,'Report Data','reportData','global','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:60\"],\"defaultUploadLocationSource\":\"3\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"allowedKinds\":[\"text\"],\"limit\":\"\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-08-15 15:07:56','2018-08-15 15:30:00','c0866ad5-ca17-43d8-8a57-52ea3e84bbe2'),(106,8,'Report Result Expiry','reportResultExpiry','global','',0,'Dropdown','{\"options\":[{\"label\":\"0\",\"value\":\"0\",\"default\":\"\"},{\"label\":\"30\",\"value\":\"30\",\"default\":\"\"},{\"label\":\"90\",\"value\":\"90\",\"default\":\"\"},{\"label\":\"180\",\"value\":\"180\",\"default\":\"\"},{\"label\":\"365\",\"value\":\"365\",\"default\":\"\"},{\"label\":\"365+\",\"value\":\"\",\"default\":\"\"}]}','2018-08-20 15:35:57','2018-08-20 15:36:36','8200bbc7-18f0-49da-a017-2fb3a82ee950'),(107,9,'Home Slider','homeSlider','global','',0,'Matrix','{\"maxBlocks\":null}','2018-08-21 15:24:55','2018-08-21 15:27:31','4a4bc5cd-115e-4309-a51a-3e5095ceea90'),(108,NULL,'Title','slideTitle','matrixBlockType:7','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-21 15:24:55','2018-08-21 15:27:31','111e6e76-917a-43f6-aa70-c659bd131921'),(109,NULL,'Heading','slideHeading','matrixBlockType:7','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-21 15:24:55','2018-08-21 15:27:31','4978c00f-2afb-4601-83f2-12952bc08242'),(110,NULL,'Image','slideImage','matrixBlockType:7','',0,'Assets','{\"useSingleFolder\":\"1\",\"sources\":\"*\",\"defaultUploadLocationSource\":\"1\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"4\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"1\",\"allowedKinds\":[\"image\"],\"limit\":\"1\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-08-21 15:24:55','2018-08-21 15:27:31','89bc5035-7e58-43a7-a7e5-bcaa9de73d4a'),(111,9,'Theme Navigation Public','themeNavigationPublic','global','',0,'Entries','{\"sources\":[\"section:14\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-08-21 15:37:05','2018-12-11 08:10:22','5b3dd4d1-e38c-4ffd-a19d-981f6331ca88'),(112,1,'Content','pageContent','global','',0,'Matrix','{\"maxBlocks\":null}','2018-08-21 15:46:47','2018-11-23 15:20:10','b8c430cf-c3d3-4d15-a8a8-21eb2fb94ace'),(113,NULL,'Title','columnTitle','matrixBlockType:8','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-21 15:46:47','2018-11-23 15:20:11','56f0758a-86e6-4c78-ae70-e09843a275a2'),(114,NULL,'HTML','columnHtml','matrixBlockType:8','',0,'RichText','{\"configFile\":\"Standard.json\",\"availableAssetSources\":\"*\",\"availableTransforms\":\"*\",\"cleanupHtml\":\"\",\"purifyHtml\":\"\",\"purifierConfig\":\"\",\"columnType\":\"text\"}','2018-08-21 15:46:47','2018-11-23 15:20:11','9a8e1aa6-c705-41e6-ab80-8c987022d24b'),(115,1,'Image','pageImage','global','',0,'Assets','{\"useSingleFolder\":\"\",\"sources\":[\"folder:67\"],\"defaultUploadLocationSource\":\"4\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"1\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"1\",\"allowedKinds\":[\"image\"],\"limit\":\"1\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-08-21 15:47:47','2018-08-21 15:47:47','5d8461b5-aba0-482c-a677-b59c1ec759ab'),(116,9,'Scheme Description','schemeDescription','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-21 15:48:47','2018-08-23 14:39:54','f25d1178-ab4c-4b7b-bf06-b381c58c5df1'),(117,9,'Scheme Logo','schemeLogo','global','',0,'Assets','{\"useSingleFolder\":\"1\",\"sources\":[\"folder:67\"],\"defaultUploadLocationSource\":\"3\",\"defaultUploadLocationSubpath\":\"\",\"singleUploadLocationSource\":\"4\",\"singleUploadLocationSubpath\":\"\",\"restrictFiles\":\"\",\"limit\":\"1\",\"viewMode\":\"list\",\"selectionLabel\":\"\"}','2018-08-23 14:39:40','2018-08-23 14:39:40','835b518c-d291-4c01-a514-025ab5475788'),(118,9,'Theme Color Primary','themeColorPrimary','global','',0,'Color',NULL,'2018-08-23 18:51:41','2018-08-23 18:51:58','4f290131-f1cf-4d75-aea2-32cb5522a36e'),(119,9,'Theme Color Secondary','themeColorSecondary','global','',0,'Color',NULL,'2018-08-23 18:52:13','2018-08-23 18:52:13','45b6ffab-ea34-4493-8170-ad3812081dc6'),(121,2,'User Start Date','userStartDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-08-26 14:02:35','2018-08-26 14:02:35','aa4a666c-db2c-4515-b356-344c43b82ca1'),(122,2,'User Date of Birth','userDateOfBirth','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-08-26 14:02:56','2018-08-26 14:31:53','f0039d1f-5cfd-4bf6-970e-5592f86712de'),(123,2,'User Address','userAddress','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"2\"}','2018-08-26 14:03:09','2018-08-26 14:03:09','027f626e-2eaa-4fca-9665-c8812bce20a9'),(124,2,'User Custom Fields','userCustomFields','global','',0,'Matrix','{\"maxBlocks\":null}','2018-08-26 14:04:54','2018-08-26 15:03:37','53b87ea0-021a-4671-bfbd-8000933bcf0e'),(125,NULL,'Custom Name','customName','matrixBlockType:9','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-26 14:04:54','2018-08-26 15:03:37','03b567a4-41fe-44fc-8b41-a896abbe328f'),(126,NULL,'Custom Value','customValue','matrixBlockType:9','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-26 14:04:54','2018-08-26 15:03:37','e4cbe147-47ff-4ad4-845e-cc788f8b8829'),(127,10,'Scheme User Custom Fields','schemeUserCustomFields','global','Enter a name (lowercase, no spaces) and label text for each custom user field.',0,'Table','{\"columns\":{\"col1\":{\"heading\":\"Name\",\"handle\":\"name\",\"width\":\"\",\"type\":\"singleline\"},\"col2\":{\"heading\":\"Label\",\"handle\":\"label\",\"width\":\"\",\"type\":\"singleline\"}},\"defaults\":{\"row1\":{\"col1\":\"\",\"col2\":\"\"}}}','2018-08-26 14:06:22','2018-09-26 14:47:36','f4d82e76-729e-45e9-9f95-6130a87ce30e'),(128,2,'User Company','userCompany','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2018-08-26 15:13:40','2018-08-26 15:50:25','4ac0fb06-5ba4-4129-ad0a-664727323d89'),(129,2,'Manager Level','managerLevel','global','',0,'Dropdown','{\"options\":[{\"label\":\"Level One\",\"value\":\"1\",\"default\":\"\"},{\"label\":\"Level Two\",\"value\":\"2\",\"default\":\"\"},{\"label\":\"Level Three\",\"value\":\"3\",\"default\":\"\"},{\"label\":\"Level Four\",\"value\":\"4\",\"default\":\"\"}]}','2018-08-26 16:01:28','2018-08-26 16:01:28','af392c27-30aa-4dde-a30c-fd69e3dd665e'),(130,4,'Unit Endorsement Manager Level','unitEndorsementManagerLevel','global','',0,'Dropdown','{\"options\":[{\"label\":\"Level One\",\"value\":\"1\",\"default\":\"\"},{\"label\":\"Level Two\",\"value\":\"2\",\"default\":\"\"},{\"label\":\"Level Three\",\"value\":\"3\",\"default\":\"\"},{\"label\":\"Level Four\",\"value\":\"4\",\"default\":\"\"}]}','2018-08-26 16:03:44','2018-08-26 16:03:44','d09c7ee9-90ad-4c7e-a8ec-a84e5f3457f0'),(131,10,'Scheme Teams','schemeTeams','global','Enable teams for this scheme?',0,'Lightswitch','{\"default\":\"1\"}','2018-08-29 12:54:17','2018-09-26 14:47:04','46c15055-2508-42c0-8e05-e43cb4eb99b6'),(132,2,'Manager Read Only','managerReadOnly','global','',0,'Lightswitch','{\"default\":\"1\"}','2018-08-29 14:53:21','2018-10-15 13:55:04','8df69537-0612-4b94-af60-c27617fddb4d'),(133,9,'Notify Footer','notifyFooter','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"1\",\"initialRows\":\"4\"}','2018-08-29 16:27:51','2018-08-29 16:27:51','fc17e50a-4f18-4b00-b9dc-49d590770784'),(134,9,'Notify Subject Manager Summary','notifySubjectManagerSummary','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-29 16:29:40','2018-08-29 16:29:40','3e60020f-6828-4803-b841-1714369c3d6d'),(135,9,'Notify Subject Endorsement Result','notifySubjectEndorsementResult','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-29 16:31:38','2018-08-29 16:31:38','d529eed7-2b00-41eb-abfb-5002d8caa43c'),(136,9,'Notify Subject Blocked Result','notifySubjectBlockedResult','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-29 16:32:36','2018-08-29 16:32:36','d370fe88-7ab1-497b-a560-9b7ac5de4ae3'),(137,9,'Notify Subject Module Result','notifySubjectModuleResult','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-29 16:33:24','2018-08-29 16:33:24','3e2f8e90-3c8a-48e0-aa64-d44e5bac3eb8'),(138,9,'Notify Subject Licences Remaining','notifySubjectLicencesRemaining','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-29 16:34:39','2018-08-29 16:34:39','7ef0e674-5a5c-49c8-9fd3-922c8db403b8'),(139,9,'Notify Subject User Expiry','notifySubjectUserExpiry','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-29 16:35:30','2018-08-29 16:35:30','963040fc-2669-42da-bec8-564b72968cea'),(140,9,'Notify Subject Scheme Expiry','notifySubjectSchemeExpiry','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-08-29 16:36:21','2018-08-29 16:36:21','e5fd9bba-88e9-4747-8f49-1da8d72717e3'),(141,10,'Scheme Email Domain','schemeEmailDomain','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-09-10 16:15:52','2018-09-26 14:48:14','a1d57a54-2461-4d2d-8895-d743c1d3bb89'),(142,6,'Result Start date','resultStartDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-09-18 16:30:42','2018-09-18 16:30:42','f7a6d059-9c07-4f32-a734-4bd9b5f2e210'),(143,6,'Result Finish Date','resultFinishDate','global','',0,'Date','{\"minuteIncrement\":\"30\",\"showDate\":1,\"showTime\":0}','2018-09-18 16:31:08','2018-09-18 16:31:08','c29bb59d-f545-409b-be92-c9b57e8cf603'),(144,6,'Result Location','resultLocation','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-09-18 16:31:16','2018-09-18 16:31:16','d363cf24-3e53-4eea-b5f4-e9c72a3e9f65'),(145,10,'Individual Company','individualCompany','global','',0,'Entries','{\"sources\":[\"section:3\"],\"limit\":\"1\",\"selectionLabel\":\"\"}','2018-09-26 14:40:11','2018-09-26 14:47:14','cbe0ac27-ad35-410a-bd25-9c4d25fe0ba9'),(146,6,'Result Value','resultValue','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-09-26 14:41:11','2018-09-26 14:41:11','a1d83c2a-d7f2-45d2-8430-7e2efd8d12ec'),(147,11,'Legacy Company ID','legacyCompanyId','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-10-04 15:30:44','2018-10-04 15:30:44','3f2f2e5a-9c13-4199-aaa4-0d0d1eb8abfc'),(148,11,'Legacy Email','legacyEmail','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-10-04 15:30:56','2018-10-04 15:30:56','85884bff-7c6a-4759-a769-d3f056254b7b'),(149,11,'Legacy Group','legacyGroup','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-10-04 15:31:04','2018-10-04 15:31:04','b7d6d158-4e92-4be7-ab53-c7e655c7e062'),(150,11,'Legacy ID','legacyId','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-10-04 15:31:18','2018-10-04 15:31:18','dc88c84b-17f2-4b8b-8cc6-124b5115c9cc'),(151,11,'Legacy Job Role ID','legacyJobRoleId','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-10-04 15:31:30','2018-10-04 15:31:30','bb511299-1a44-4db4-b8f0-32035ca24b2d'),(152,11,'Legacy Parent ID','legacyParentId','global','',0,'Number','{\"min\":\"0\",\"max\":\"\",\"decimals\":\"0\"}','2018-10-04 15:32:07','2018-10-04 15:32:07','3d52518d-7b85-4ef8-af1c-fb8a813e0cce'),(153,4,'Result Display Location','resultDisplayLocation','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:46:47','2018-10-16 12:48:14','8669508d-c032-489a-bcd2-7f9e2f0fffc5'),(154,4,'Result Display Start Date','resultDisplayStartDate','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:46:59','2018-10-16 12:48:21','8e065945-beff-47e0-a90b-2d7a57b91899'),(155,4,'Result Display Finish Date','resultDisplayFinishDate','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:47:14','2018-10-16 12:52:32','93485201-c508-48f9-a114-db4fb3558c38'),(156,4,'Result Display Endorsed Date','resultDisplayEndorsedDate','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:47:32','2018-10-16 12:47:41','03884584-2dbd-48fb-90e9-2be46d75b3b8'),(157,4,'Result Display Type','resultDisplayType','global','',0,'Lightswitch','{\"default\":\"1\"}','2018-10-16 12:49:34','2018-10-16 12:52:43','2d42f3fb-897c-4246-8d75-1774e8ef2cc8'),(158,4,'Result Display Value','resultDisplayValue','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:49:44','2018-10-16 12:49:44','63152307-1708-49e9-9d3c-3cb9275371b4'),(159,4,'Result Display Expiry Date','resultDisplayExpiryDate','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:49:57','2018-10-16 12:49:57','d3b38d67-f2d6-4349-9ffc-0d39810c1028'),(160,4,'Result Display Evidence','resultDisplayEvidence','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:50:12','2018-10-16 12:50:12','6ed0db07-3bf7-438a-80c4-0d64a6542446'),(161,4,'Result Display Status','resultDisplayStatus','global','',0,'Lightswitch','{\"default\":\"1\"}','2018-10-16 12:51:48','2018-10-16 12:52:52','5cee14f9-7873-40b9-b788-aab8210d1e0e'),(162,4,'Result Display Hours','resultDisplayHours','global','',0,'Lightswitch','{\"default\":\"\"}','2018-10-16 12:58:47','2018-10-16 12:58:47','563ab173-4e2b-4622-b7dd-284d3df35f95'),(163,6,'Result Hours','resultHours','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-10-16 13:16:00','2018-10-16 13:26:27','06f9a436-94f4-4170-80ec-6e08d13115ee'),(164,3,'Company Secondary Managers','companySecondaryManagers','global','',0,'Users','{\"sources\":[\"group:2\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-10-16 13:47:14','2018-10-16 13:47:37','8a8d1ecb-3c6c-4ade-a2e7-8e6996e7fe86'),(166,4,'Module Group','moduleGroup','global','',0,'Categories','{\"source\":\"group:2\",\"limit\":\"1\",\"selectionLabel\":\"\"}','2018-11-26 17:52:45','2018-11-26 17:52:45','fbd8c758-3ca5-4ccb-8452-d1d82d3f97e4'),(167,10,'Column Layout','columnLayout','global','',0,'SuperTable','{\"columns\":{\"new1\":{\"width\":\"\"},\"new2\":{\"width\":\"\"}},\"fieldLayout\":\"table\",\"staticField\":null,\"selectionLabel\":\"Add a row\",\"maxRows\":null,\"minRows\":null}','2018-12-06 17:37:16','2018-12-06 17:37:16','bfce8aaa-028f-4d73-8f1d-20ba8f5a871b'),(168,NULL,'Field Type','fieldType','superTableBlockType:2','',0,'Dropdown','{\"options\":[{\"label\":\"Type\",\"value\":\"unitType\",\"default\":\"\"},{\"label\":\"Value\",\"value\":\"unitValue\",\"default\":\"\"},{\"label\":\"Status\",\"value\":\"resultStatus\",\"default\":\"\"},{\"label\":\"Endorsed Date\",\"value\":\"resultEndorsedDate\",\"default\":\"\"},{\"label\":\"Expiry Date\",\"value\":\"resultExpiryDate\",\"default\":\"\"},{\"label\":\"Start Date\",\"value\":\"resultStartDate\",\"default\":\"\"},{\"label\":\"Finish Date\",\"value\":\"resultFinishDate\",\"default\":\"\"},{\"label\":\"Location\",\"value\":\"resultLocation\",\"default\":\"\"},{\"label\":\"Hours\",\"value\":\"resultHours\",\"default\":\"\"},{\"label\":\"Evidence\",\"value\":\"resultEvidence\",\"default\":\"\"}]}','2018-12-06 17:37:16','2018-12-06 17:37:16','8fec0242-3542-431e-8a9a-d2d86b3e3b06'),(169,NULL,'Field Label','fieldLabel','superTableBlockType:2','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-12-06 17:37:16','2018-12-06 17:37:16','19794a8d-aa57-4cea-9297-7c4ccfb2d561'),(170,2,'User Dummy Email','userDummyEmail','global','',0,'Lightswitch','{\"default\":\"\"}','2018-12-11 07:10:42','2018-12-11 07:10:42','6e432459-1ae7-4301-a7cd-e23c574bd569'),(171,3,'Include Add Achievement? ','addAchievement','global','',0,'Lightswitch','{\"default\":\"\"}','2018-12-11 07:49:56','2018-12-11 07:54:55','c3c65d33-35c6-416d-9e88-c5a678f09161'),(172,9,'Theme Navigation Private','themeNavigationPrivate','global','',0,'Entries','{\"sources\":[\"section:14\"],\"limit\":\"\",\"selectionLabel\":\"\"}','2018-12-11 08:09:02','2018-12-11 08:09:02','6fa87955-dc3d-4695-9142-9250995d8cd7'),(173,2,'User Licence Source','userLicenceSource','global','',0,'PlainText','{\"placeholder\":\"\",\"maxLength\":\"\",\"multiline\":\"\",\"initialRows\":\"4\"}','2018-12-11 23:46:07','2018-12-11 23:46:07','27f36190-a4c4-4f11-b546-5e4ffb5efc06'),(174,6,'Result Search','resultSearch','global','',0,'PreparseField_Preparse','{\"fieldTwig\":\"{% if entry.type == \'unitResult\' %}{{ entry.resultUnit.first.title|raw }}{% else %}{{ entry.resultModule.first.title|raw }}{% endif %} \\r\\n{{ entry.author.userTeam.first.title|raw }} {{ entry.au\",\"columnType\":\"text\",\"decimals\":\"0\",\"parseBeforeSave\":\"\",\"parseOnMove\":\"\",\"showField\":\"1\",\"allowSelect\":\"\"}','2018-12-12 07:22:19','2018-12-12 07:24:02','2078306f-b4b1-40bc-9837-4604f48a4939'),(175,2,'User Search','userSearch','global','',0,'PreparseField_Preparse','{\"fieldTwig\":\"{{ user.userTeam.first.title|raw  }} {{ user.userTeam.first.teamCompany.first.title|raw }}\",\"columnType\":\"text\",\"decimals\":\"0\",\"parseBeforeSave\":\"\",\"parseOnMove\":\"\",\"showField\":\"\",\"allowSelect\":\"\"}','2018-12-12 07:22:45','2018-12-12 07:24:41','bb9d49ae-464b-4650-b8f1-fa4f12b3c769');
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
INSERT INTO `craft_globalsets` VALUES (488,'Scheme','globalsScheme',212,'2018-04-26 16:31:48','2018-09-26 14:40:45','c802382c-aba0-4067-ba99-d7b7e8d1417c'),(489,'System','globalsSystem',138,'2018-04-29 12:39:32','2018-04-29 12:42:06','d5e126b7-81ae-498b-9410-8e5f9b934ad1'),(1079,'Theme','globalsTheme',256,'2018-08-21 15:22:30','2018-12-11 08:11:03','a1969b12-a29f-4cbc-ad95-96798d730775'),(1138,'Notify','globalsNotify',207,'2018-08-29 16:27:02','2018-08-29 16:37:18','51a53e5b-5171-4250-9e8f-9fa21c5ac58c');
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
INSERT INTO `craft_info` VALUES (1,'2.6.3015','2.6.14',2,'Lantra Scheme','/','UTC',1,0,'2017-10-23 13:26:41','2018-12-14 18:27:36','0058b800-0ac6-4829-83e2-aa8d406795bd');
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
INSERT INTO `craft_matrixblocks` VALUES (1087,1086,112,8,1,NULL,'2018-08-23 14:29:32','2018-12-07 10:17:14','b482ceb6-1f39-4208-ba81-48d23c40fb7c'),(1088,1086,112,8,2,NULL,'2018-08-23 14:29:32','2018-12-07 10:17:14','1965972d-6c42-43f0-9682-6b455076ec1e'),(1091,1079,107,7,1,NULL,'2018-08-23 14:37:44','2018-12-14 18:23:18','b8c2dceb-df37-4404-a531-8c0431ad5d16'),(1620,1090,112,8,1,NULL,'2018-11-23 11:08:56','2018-11-23 15:34:38','1c74cd76-2296-4bf3-8b8e-00ea1c55b601'),(1923,1623,112,8,1,NULL,'2018-12-10 09:11:48','2018-12-14 18:21:39','24442ddf-4df8-4a80-b1ba-5fbe6c983f18');
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
INSERT INTO `craft_matrixblocktypes` VALUES (1,19,74,'Unit Group','unitGroup',1,'2017-10-24 09:55:27','2018-03-09 11:20:19','79541871-603b-4a9c-871d-c5668970f9e0'),(2,40,94,'True False','trueFalse',1,'2018-04-13 10:18:09','2018-04-13 18:13:59','cc1d2063-7fd4-4e67-b9be-33cdc205890d'),(3,40,95,'Choices','choices',2,'2018-04-13 10:18:09','2018-04-13 18:13:59','63a61dbd-d2dd-49bc-9e8a-6b45a2f698bc'),(4,40,96,'Text','text',3,'2018-04-13 10:18:09','2018-04-13 18:13:59','656f85bc-0a54-422d-a9a3-48d536fc7aa3'),(5,50,97,'Answer','answer',1,'2018-04-13 10:29:34','2018-04-13 18:20:05','c4592d20-fbfb-48ba-bb58-796a7e3ba778'),(6,77,147,'PayPal','paypal',1,'2018-05-31 13:06:53','2018-05-31 13:06:54','a00978f4-c38c-4cb9-8379-855950bf682b'),(7,107,171,'Slide','slide',1,'2018-08-21 15:24:55','2018-08-21 15:27:32','31396686-93c4-4dbe-a8c9-9328d191a511'),(8,112,247,'Columns','columns',1,'2018-08-21 15:46:47','2018-11-23 15:20:11','e21ca603-4cc1-4573-9a88-a769b8a0d695'),(9,124,194,'User Custom Field','userCustomField',1,'2018-08-26 14:04:54','2018-08-26 15:03:37','d9a83f47-4dd1-4c78-830c-73f6be0bdebe');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
INSERT INTO `craft_matrixcontent_homeslider` VALUES (1,1091,'en_gb','Welcome to the Lantra Scheme','Support your own staff and non-employed individuals across your entire workforce.','2018-08-23 14:37:44','2018-12-14 18:23:18','e23f28ca-8240-4034-b96d-4d6489a026b6');
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
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixcontent_pagecontent`
--

LOCK TABLES `craft_matrixcontent_pagecontent` WRITE;
/*!40000 ALTER TABLE `craft_matrixcontent_pagecontent` DISABLE KEYS */;
INSERT INTO `craft_matrixcontent_pagecontent` VALUES (1,1087,'en_gb','How we work','<p>The opinions and ideas of such groups help us to change and improve the industry, promoting the importance of skills recognition, training and development with the aim of increasing productivity, sustainability - and ultimately profitability.</p>\r\n<p>Liaising closely with industries within the land-based sector we represent, along with governments, funding agencies, learning providers, trade associations and the media, we can shape important strategies for the future.</p>','2018-08-23 14:29:32','2018-12-07 10:17:14','0c1af4bb-0417-4094-b496-d1087fba4835'),(2,1088,'en_gb','Working with industry groups and employers','<p>At the heart of Lantra\'s organisation are environmental and land-based employers - experts in their own field who know first hand the requirements of their industries. Employers play an integral part in the corporate structure of the organisation from working groups through to Lantra\'s board of directors, and play a key leadership role in forming and shaping Lantra\'s strategies, products and services.</p>','2018-08-23 14:29:32','2018-12-07 10:17:14','9d2d10f7-8a24-4985-8996-dcae5bca2251'),(6,1620,'en_gb','','<p>You can send a direct contact to Lantra by completing this form. We will get back to you with our answer by either telephone or email, depending on which option that you chose. We aim to reply to all enquiries by the end of the next working day.</p>\r\n<form class=\"webform-client-form webform-client-form-447 webform-conditional-processed\" enctype=\"multipart/form-data\" action=\"/webform/contact-us-7\" method=\"post\" id=\"webform-client-form-447\" accept-charset=\"UTF-8\"><div><div class=\"form-item webform-component webform-component-textfield webform-component--name form-group\">\r\n  <label class=\"control-label\" for=\"edit-submitted-name\">Name <span class=\"form-required\" title=\"This field is required.\">*</span></label>\r\n <input required=\"required\" class=\"form-control form-text required\" type=\"text\" id=\"edit-submitted-name\" name=\"submitted[name]\" value=\"\" size=\"60\" maxlength=\"128\">\r\n</div>\r\n<div class=\"form-item webform-component webform-component-email webform-component--email form-group\">\r\n  <label class=\"control-label\" for=\"edit-submitted-email\">Email <span class=\"form-required\" title=\"This field is required.\">*</span></label>\r\n <input required=\"required\" class=\"email form-control form-text form-email required\" type=\"email\" id=\"edit-submitted-email\" name=\"submitted[email]\" size=\"60\">\r\n</div>\r\n<div class=\"form-item webform-component webform-component-textfield webform-component--telephone form-group\">\r\n  <label class=\"control-label\" for=\"edit-submitted-telephone\">Telephone <span class=\"form-optional\"></span></label>\r\n <input class=\"form-control form-text\" type=\"text\" id=\"edit-submitted-telephone\" name=\"submitted[telephone]\" value=\"\" size=\"60\" maxlength=\"128\">\r\n</div>\r\n<div class=\"form-item webform-component webform-component-select webform-component--reason-for-contacting-us form-group\">\r\n  <label class=\"control-label\" for=\"edit-submitted-reason-for-contacting-us\">Reason for contacting us <span class=\"form-required\" title=\"This field is required.\">*</span></label>\r\n <select required=\"required\" class=\"form-control form-select required\" id=\"edit-submitted-reason-for-contacting-us\" name=\"submitted[reason_for_contacting_us]\"><option value=\"\" selected=\"selected\">- Select -</option><option value=\"1\">General Enquiry</option></select>\r\n</div>\r\n<div class=\"form-item webform-component webform-component-textarea webform-component--comments form-group\">\r\n  <label class=\"control-label\" for=\"edit-submitted-comments\">Comments <span class=\"form-required\" title=\"This field is required.\">*</span></label>\r\n <div class=\"form-textarea-wrapper resizable textarea-processed resizable-textarea\"><textarea required=\"required\" class=\"form-control form-textarea required\" id=\"edit-submitted-comments\" name=\"submitted[comments]\" cols=\"60\" rows=\"5\"></textarea><div class=\"grippie\"></div></div>\r\n</div>\r\n<div class=\"form-item webform-component webform-component-radios webform-component--would-you-prefer-a-response-via-telephone-or-email form-group\">\r\n  <label class=\"control-label\" for=\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\">Would you prefer a response via telephone or email? <span class=\"form-required\" title=\"This field is required.\">*</span></label>\r\n <div id=\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email\" class=\"form-radios\"><div class=\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\"> <label class=\"control-label\" for=\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\"><input required=\"required\" type=\"radio\" id=\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-1\" name=\"submitted[would_you_prefer_a_response_via_telephone_or_email]\" value=\"email\" checked=\"checked\" class=\"form-radio\">Email </label>\r\n</div><div class=\"form-item form-item-submitted-would-you-prefer-a-response-via-telephone-or-email form-type-radio radio\"> <label class=\"control-label\" for=\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\"><input required=\"required\" type=\"radio\" id=\"edit-submitted-would-you-prefer-a-response-via-telephone-or-email-2\" name=\"submitted[would_you_prefer_a_response_via_telephone_or_email]\" value=\"telephone\" class=\"form-radio\">Telephone </label>\r\n</div></div>\r\n</div>\r\n<button class=\"button primary-bg webform-submit button-primary btn btn-primary form-submit\" name=\"op\" value=\"Submit\" type=\"submit\">Submit</button>\r\n</form>','2018-11-23 11:08:56','2018-11-23 15:34:38','b3d1bacf-a47f-4f55-a3ef-a9e866036ade'),(7,1923,'en_gb','','','2018-12-10 09:11:48','2018-12-14 18:21:38','ad7766de-4b94-47e5-bf16-306751385792');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_matrixcontent_usercustomfields`
--

LOCK TABLES `craft_matrixcontent_usercustomfields` WRITE;
/*!40000 ALTER TABLE `craft_matrixcontent_usercustomfields` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_migrations`
--

LOCK TABLES `craft_migrations` WRITE;
/*!40000 ALTER TABLE `craft_migrations` DISABLE KEYS */;
INSERT INTO `craft_migrations` VALUES (1,NULL,'m000000_000000_base','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','2eaf975a-b268-4cb4-bfa4-68b4a1226283'),(2,NULL,'m140730_000001_add_filename_and_format_to_transformindex','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','495b0628-c389-4af0-bec2-2a98abbe48be'),(3,NULL,'m140815_000001_add_format_to_transforms','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','1afb1205-ef2e-4633-adbc-a97ebb1db4f9'),(4,NULL,'m140822_000001_allow_more_than_128_items_per_field','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','d6168e8f-5c01-42a3-822f-b84a6e020fb7'),(5,NULL,'m140829_000001_single_title_formats','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','a9e8512c-b255-4359-ae30-41fa543f085a'),(6,NULL,'m140831_000001_extended_cache_keys','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','783e30fe-d457-4345-b3ee-31d2b4ba331d'),(7,NULL,'m140922_000001_delete_orphaned_matrix_blocks','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','2e85c070-c354-49ed-8e15-ca0fdf1829f3'),(8,NULL,'m141008_000001_elements_index_tune','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','314dd934-1d00-4d2e-b899-343955e317f5'),(9,NULL,'m141009_000001_assets_source_handle','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','b91e8b58-de2e-49c2-b9d1-a23eeae2d040'),(10,NULL,'m141024_000001_field_layout_tabs','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','d50d225b-9503-4031-a7af-868e2ca57b50'),(11,NULL,'m141030_000000_plugin_schema_versions','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','b98c3d2e-cdce-4bee-969f-1f00cf93c06b'),(12,NULL,'m141030_000001_drop_structure_move_permission','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','db924f1b-a0eb-4e0d-83da-5e2392e343a6'),(13,NULL,'m141103_000001_tag_titles','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','2a4c986d-4055-4518-a23d-c267204773ef'),(14,NULL,'m141109_000001_user_status_shuffle','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','8cf0a288-38e8-47aa-8d71-19cd893c0cd6'),(15,NULL,'m141126_000001_user_week_start_day','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','01bffd04-15e3-4cbc-bfd4-acc26b70eb23'),(16,NULL,'m150210_000001_adjust_user_photo_size','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','87293d41-215d-4388-ba81-9d459aeb4010'),(17,NULL,'m150724_000001_adjust_quality_settings','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','823acb7d-4682-4d4e-bd00-baf0701c2426'),(18,NULL,'m150827_000000_element_index_settings','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','8bb4c1f3-7b63-420d-9582-146f93c86a2b'),(19,NULL,'m150918_000001_add_colspan_to_widgets','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','8325ef7b-8e34-4b9f-a2e7-597344631042'),(20,NULL,'m151007_000000_clear_asset_caches','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','70402275-6cd3-4dd7-a7b5-3f18c05b9835'),(21,NULL,'m151109_000000_text_url_formats','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','dfac615d-ac88-4e4d-a642-49d5f27985fd'),(22,NULL,'m151110_000000_move_logo','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','fd3ac617-297c-4a0c-a442-8307cfe5e9eb'),(23,NULL,'m151117_000000_adjust_image_widthheight','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','930e62f7-0ec2-472b-9df6-2298fc8841b9'),(24,NULL,'m151127_000000_clear_license_key_status','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','32c71707-4d25-4978-a830-8b4080146567'),(25,NULL,'m151127_000000_plugin_license_keys','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','3a0598c0-6223-4774-b4e9-92308ac1de09'),(26,NULL,'m151130_000000_update_pt_widget_feeds','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','ed22be10-ebc6-4abc-b9ce-862bd7118cc5'),(27,NULL,'m160114_000000_asset_sources_public_url_default_true','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','bdce00a9-8650-4591-8659-65af2c2538c3'),(28,NULL,'m160223_000000_sortorder_to_smallint','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','3619fb25-d12a-45e5-865d-f0c1fd406414'),(29,NULL,'m160229_000000_set_default_entry_statuses','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','5a08032e-f7a9-45b4-9446-52922ffa22e7'),(30,NULL,'m160304_000000_client_permissions','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','f52d0821-c99c-46ef-bf55-c116067d6426'),(31,NULL,'m160322_000000_asset_filesize','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','5bfecd9b-62f9-4783-b494-3278e1a2855b'),(32,NULL,'m160503_000000_orphaned_fieldlayouts','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','1d7215d9-6dc1-413d-8176-e7d412813bfb'),(33,NULL,'m160510_000000_tasksettings','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','f98b105a-07a2-4c7a-b7b2-82107264f492'),(34,NULL,'m160829_000000_pending_user_content_cleanup','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','a1f6e618-2035-476e-9ca8-4922d45ea045'),(35,NULL,'m160830_000000_asset_index_uri_increase','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','ab658839-e7ad-4c91-91f8-c642096a52fa'),(36,NULL,'m160919_000000_usergroup_handle_title_unique','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','b56b1cf3-2a25-471f-88ef-f9f8bd084656'),(37,NULL,'m161108_000000_new_version_format','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','779d7aa5-569b-4846-94f3-3120ab53f2e3'),(38,NULL,'m161109_000000_index_shuffle','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','883bd624-b8b2-469c-8c80-3094f1d3b0db'),(39,NULL,'m170612_000000_route_index_shuffle','2017-10-23 13:26:41','2017-10-23 13:26:41','2017-10-23 13:26:41','1f59e9a2-e913-4a21-bfbe-74052888b324'),(40,NULL,'m171107_000000_assign_group_permissions','2018-04-29 12:43:55','2018-04-29 12:43:55','2018-04-29 12:43:55','e296a7be-1955-4364-b03a-065105050eaf'),(41,NULL,'m171117_000001_templatecache_index_tune','2018-04-29 12:43:55','2018-04-29 12:43:55','2018-04-29 12:43:55','3e4152c0-091d-4d07-9d25-005465c170ee'),(42,NULL,'m171204_000001_templatecache_index_tune_deux','2018-04-29 12:43:55','2018-04-29 12:43:55','2018-04-29 12:43:55','f786fe92-23af-4da1-b311-b2d9e1541932'),(43,NULL,'m180406_000000_pro_upgrade','2018-04-29 12:43:55','2018-04-29 12:43:55','2018-04-29 12:43:55','c03db24e-f159-469b-885a-ed34ba2837ae'),(44,6,'m151229_000001_sproutReports_addReportGroupsTable','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','7f374b67-6880-49f8-853d-6ae6b1e2ed23'),(45,6,'m151229_000002_sproutReports_updateSingleNumberWidgetToNumberWidget','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','a361ee21-1e95-455a-b0cc-32501be172f3'),(46,6,'m151229_000003_sproutReports_removeOldReportColumns','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','7f63ad99-a423-4b05-a3b1-88cbf45d6924'),(47,6,'m151229_000004_sproutReports_addNewReportColumns','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','aa5150aa-d1db-40fc-b94a-65a5033442d4'),(48,6,'m151229_000005_sproutReports_migrateAndRemoveCustomQueryColumn','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','2520f789-83d6-4a9a-9b8e-efecc4206dc9'),(49,6,'m151229_000006_sproutReports_addDetailsToReportsTable','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','e3de4428-a4e4-4acc-b2ad-37486ffd8f18'),(50,6,'m161101_134003_sproutreports_createDataSourceTable','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','e0016f66-7fab-4d9e-a867-eae0a77594f0'),(51,6,'m161122_000000_sproutReports_addAllowHtmlColumn','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','cadd9390-5505-47dd-9647-e4e71b2eb844'),(52,6,'m171020_000000_sproutReports_addDynamicNameColumn','2018-05-02 09:18:48','2018-05-02 09:18:48','2018-05-02 09:18:48','cd8d99ea-0f7f-4027-9407-6d17c9822e05'),(53,7,'m140924_111621_export_CreateExportMap','2018-06-02 09:37:09','2018-06-02 09:37:09','2018-06-02 09:37:09','53a33448-8802-452c-9d21-796aa910756b'),(54,8,'m140430_122214_import_ImportHistory','2018-06-02 09:37:11','2018-06-02 09:37:11','2018-06-02 09:37:11','e4a9f07e-9813-478e-bc52-4be542e6ad6c'),(55,8,'m140616_080724_import_saveEntryIdAndVersion','2018-06-02 09:37:11','2018-06-02 09:37:11','2018-06-02 09:37:11','3fdd9e6b-3b56-49dd-9264-be8759babf80'),(56,8,'m140903_075432_import_ImportElements','2018-06-02 09:37:11','2018-06-02 09:37:11','2018-06-02 09:37:11','325e1b3c-8ac0-4a18-b25e-221992b57f98'),(57,13,'m150901_144609_superTable_fixForContentTables','2018-12-06 17:33:46','2018-12-06 17:33:46','2018-12-06 17:33:46','72d82e5c-8c79-4b83-9185-40500b8bd327');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_plugins`
--

LOCK TABLES `craft_plugins` WRITE;
/*!40000 ALTER TABLE `craft_plugins` DISABLE KEYS */;
INSERT INTO `craft_plugins` VALUES (2,'Status','1.0.0',NULL,NULL,'unknown',1,NULL,'2017-10-24 11:48:49','2017-10-24 11:48:49','2018-12-14 18:18:33','fe2dbfed-7388-4cc0-a5b5-7885929ef5e5'),(4,'Lantra','0.0.1',NULL,NULL,'unknown',1,NULL,'2018-04-24 09:58:57','2018-04-24 09:58:57','2018-12-14 18:18:33','4ab50cac-aee2-4870-9c8f-af13d682f3b2'),(5,'InternalAssets','1.0',NULL,NULL,'unknown',1,NULL,'2018-04-26 16:28:41','2018-04-26 16:28:41','2018-12-14 18:18:33','e03e03e3-cec4-49a6-9816-01265b32ac3f'),(6,'SproutReports','0.9.3','0.9.1',NULL,'unknown',1,NULL,'2018-05-02 09:18:48','2018-05-02 09:18:48','2018-12-14 18:18:33','b504bea7-b005-4991-b0fa-137d617e9d08'),(7,'Export','0.5.10',NULL,NULL,'unknown',1,NULL,'2018-06-02 09:37:09','2018-06-02 09:37:09','2018-12-14 18:18:33','1a12d433-5a43-4573-b1cd-b4bf393331a6'),(8,'Import','0.8.33',NULL,NULL,'unknown',1,NULL,'2018-06-02 09:37:11','2018-06-02 09:37:11','2018-12-14 18:18:33','a2284062-a061-42b6-8fc9-9d116c2347fe'),(9,'Printmaker','1.0.3','0.0.0.0',NULL,'unknown',1,NULL,'2018-06-04 09:23:06','2018-06-04 09:23:06','2018-12-14 18:18:33','63198fa2-bcbf-4724-838b-3a2f08346eec'),(10,'PreparseField','0.3.6','1.0.0',NULL,'unknown',1,NULL,'2018-06-04 10:45:10','2018-06-04 10:45:10','2018-12-14 18:18:33','b866617e-b5b3-4880-b709-28810e59caea'),(12,'Mailer','0.5.1',NULL,NULL,'unknown',1,NULL,'2018-08-08 12:58:45','2018-08-08 12:58:45','2018-12-14 18:18:33','6e91f1b3-e928-4e73-bed8-692639368355'),(13,'SuperTable','1.0.6','1.0.0',NULL,'unknown',1,NULL,'2018-12-06 17:33:46','2018-12-06 17:33:46','2018-12-14 18:18:33','c5d31ad7-2241-44bd-bfcd-2db3f6304091');
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
) ENGINE=InnoDB AUTO_INCREMENT=5087 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_relations`
--

LOCK TABLES `craft_relations` WRITE;
/*!40000 ALTER TABLE `craft_relations` DISABLE KEYS */;
INSERT INTO `craft_relations` VALUES (3972,30,1626,NULL,1478,1,'2018-12-06 17:47:14','2018-12-06 17:47:14','05833af6-d900-4d9a-a186-f54293b60def'),(5081,117,1079,NULL,1269,1,'2018-12-14 18:23:18','2018-12-14 18:23:18','0059676a-1083-4a90-843d-50db98aed2ac'),(5082,110,1091,NULL,1270,1,'2018-12-14 18:23:18','2018-12-14 18:23:18','cfe5e5fb-a318-4579-8118-84b7e8c37876'),(5083,111,1079,NULL,1623,1,'2018-12-14 18:23:18','2018-12-14 18:23:18','ee427d3f-9b75-4a67-a5be-f77dc3a4673e'),(5084,111,1079,NULL,1090,2,'2018-12-14 18:23:18','2018-12-14 18:23:18','dd11134b-b4ab-4376-a5e2-efa2f8874d37'),(5085,172,1079,NULL,1623,1,'2018-12-14 18:23:18','2018-12-14 18:23:18','6b641d66-f011-4e56-82ad-fed43b108473'),(5086,172,1079,NULL,1090,2,'2018-12-14 18:23:18','2018-12-14 18:23:18','b32a0bc6-20b3-47fc-bd47-4c52864445e4');
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_routes`
--

LOCK TABLES `craft_routes` WRITE;
/*!40000 ALTER TABLE `craft_routes` DISABLE KEYS */;
INSERT INTO `craft_routes` VALUES (1,NULL,'{\"0\":\"management\\/\",\"1\":[\"*\",\"[^\\\\\\/]+\"],\"3\":\"\\/edit\\/\",\"4\":[\"*\",\"[^\\\\\\/]+\"]}','management\\/([^\\/]+)\\/edit\\/([^\\/]+)','management/index',3,'2018-03-14 15:28:14','2018-09-04 13:47:59','f14a240f-e452-47a4-8860-599a132604e2'),(2,NULL,'{\"0\":\"management\\/\",\"1\":[\"*\",\"[^\\\\\\/]+\"],\"3\":\"\\/new\"}','management\\/([^\\/]+)\\/new','management/index',4,'2018-03-14 15:28:38','2018-09-04 13:47:59','86fa9b96-eb71-4527-b040-5b962a9ec653'),(3,NULL,'[\"public\\/passport\\/\",[\"*\",\"[^\\\\\\/]+\"]]','public\\/passport\\/([^\\/]+)','public/passport',2,'2018-03-30 15:24:35','2018-09-04 13:47:59','f8b2518c-0ae9-45b9-b102-61561eeae24e'),(6,NULL,'{\"0\":\"cpd\\/\",\"1\":[\"number\",\"\\\\d+\"],\"3\":\"\\/unit\\/\",\"4\":[\"number\",\"\\\\d+\"],\"5\":\"\\/user\\/\",\"6\":[\"number\",\"\\\\d+\"]}','cpd\\/(?P<number>\\d+)\\/unit\\/(?P<number2>\\d+)\\/user\\/(?P<number3>\\d+)','cpd/unit',6,'2018-05-02 08:40:33','2018-09-13 16:22:16','484c6a87-20fe-4391-bda9-13e9bc567809'),(7,NULL,'{\"0\":\"cpd\\/\",\"1\":[\"number\",\"\\\\d+\"],\"3\":\"\\/unit\\/\",\"4\":[\"number\",\"\\\\d+\"],\"6\":\"\\/user\\/\",\"7\":[\"number\",\"\\\\d+\"],\"8\":\"\\/test\"}','cpd\\/(?P<number>\\d+)\\/unit\\/(?P<number2>\\d+)\\/user\\/(?P<number3>\\d+)\\/test','cpd/unit',7,'2018-05-02 08:41:13','2018-09-13 16:22:30','04efe3b7-703e-4004-b61b-d51c1335062a'),(8,NULL,'[\"reporting\\/user\\/\",[\"number\",\"\\\\d+\"]]','reporting\\/user\\/(?P<number>\\d+)','reporting/user',12,'2018-06-03 14:03:45','2018-09-04 13:47:59','ab4653c9-26cb-4b3c-b9e8-cc5c3b8a93a9'),(9,NULL,'[\"reporting\\/\",[\"slug\",\"[^\\\\\\/]+\"]]','reporting\\/(?P<slug>[^\\/]+)','reporting',14,'2018-06-04 08:59:37','2018-09-04 13:47:59','38933379-4841-49d3-a1c4-9b67b33d56f6'),(10,NULL,'[\"reporting\\/\",[\"slug\",\"[^\\\\\\/]+\"],\"\\/csv\"]','reporting\\/(?P<slug>[^\\/]+)\\/csv','reporting',13,'2018-06-04 08:59:52','2018-09-04 13:47:59','ca2f35c1-6d57-4271-9da2-21f968bd1556'),(11,NULL,'[\"public\\/certificate\\/\",[\"number\",\"\\\\d+\"],\"\\/\",[\"number\",\"\\\\d+\"]]','public\\/certificate\\/(?P<number>\\d+)\\/(?P<number2>\\d+)','public/certificate',1,'2018-06-04 09:49:58','2018-09-04 13:47:59','0edddf7a-cb3d-4311-818d-c6a6675b8803'),(13,NULL,'[\"cpd\\/\",[\"number\",\"\\\\d+\"]]','cpd\\/(?P<number>\\d+)','cpd/index',8,'2018-08-29 16:49:05','2018-09-04 13:47:59','c679d894-a6e1-4a4e-925c-78715c8aad07'),(14,NULL,'[\"reporting\\/custom\"]','reporting\\/custom','reporting/custom/index',11,'2018-09-04 13:45:15','2018-09-04 13:47:59','9df15ced-290b-42e8-a4da-e20fcdc2261b'),(15,NULL,'[\"reporting\\/custom\\/new\"]','reporting\\/custom\\/new','reporting/custom/_form',10,'2018-09-04 13:45:26','2018-09-04 13:47:59','e11932aa-6acf-45d3-b31f-61ebdc013904'),(16,NULL,'[\"reporting\\/custom\\/edit\\/\",[\"number\",\"\\\\d+\"]]','reporting\\/custom\\/edit\\/(?P<number>\\d+)','reporting/custom/_form',9,'2018-09-04 13:45:56','2018-09-04 13:47:59','565f50ea-ae72-426c-8093-bf7d571a539c'),(17,NULL,'[\"profile\\/\"]','profile\\/','profile/index',15,'2018-09-12 10:05:02','2018-09-12 10:05:02','d1e6176a-211b-4910-8882-6d7dd1be4811'),(18,NULL,'[\"cpd\\/achievement\\/\",[\"number\",\"\\\\d+\"]]','cpd\\/achievement\\/(?P<number>\\d+)','cpd/achievement',16,'2018-12-06 17:42:24','2018-12-06 17:42:24','e6ff9c53-6302-4e2f-b5b4-1ee3137a21d3'),(19,NULL,'[\"cpd\\/achievement\\/module\\/\",[\"number\",\"\\\\d+\"]]','cpd\\/achievement\\/module\\/(?P<number>\\d+)','cpd/achievement',17,'2018-12-06 17:42:41','2018-12-06 17:42:41','d39a65cb-31f6-4710-9a7b-d9de75c28c92'),(20,NULL,'{\"0\":\"cpd\\/achievement\\/module\\/\",\"1\":[\"number\",\"\\\\d+\"],\"3\":\"\\/user\\/\",\"4\":[\"number\",\"\\\\d+\"]}','cpd\\/achievement\\/module\\/(?P<number>\\d+)\\/user\\/(?P<number2>\\d+)','cpd/achievement',18,'2018-12-06 17:43:04','2018-12-06 17:48:45','fb5687e7-4c8e-42ca-806d-e6e0cee02579');
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
INSERT INTO `craft_searchindex` VALUES (1,'username',0,'en_gb',' jason thisistraffic co uk '),(1,'firstname',0,'en_gb',' jason '),(1,'lastname',0,'en_gb',' church '),(1,'fullname',0,'en_gb',' jason church '),(1,'email',0,'en_gb',' jason thisistraffic co uk '),(1,'slug',0,'en_gb',''),(1438,'field',77,'en_gb',''),(1191,'field',33,'en_gb',''),(1190,'extension',0,'en_gb',' png '),(1190,'kind',0,'en_gb',' image '),(1190,'slug',0,'en_gb',' unit '),(1190,'title',0,'en_gb',' unit '),(1191,'field',29,'en_gb',' pending '),(1255,'field',29,'en_gb',' pending '),(489,'field',73,'en_gb',' 10 '),(489,'field',72,'en_gb',' d m y '),(489,'slug',0,'en_gb',''),(488,'field',71,'en_gb',''),(1170,'title',0,'en_gb',' test for user one '),(1,'field',3,'en_gb',''),(1,'field',30,'en_gb',''),(1269,'filename',0,'en_gb',' lantraawards_logo png '),(1269,'extension',0,'en_gb',' png '),(1269,'kind',0,'en_gb',' image '),(1269,'slug',0,'en_gb',' lantra awards logo '),(1269,'title',0,'en_gb',' lantra awards logo '),(1191,'field',86,'en_gb',''),(1191,'field',34,'en_gb',''),(1191,'field',17,'en_gb',' unit '),(1191,'field',87,'en_gb',''),(143,'field',124,'en_gb',''),(143,'field',84,'en_gb',''),(143,'field',77,'en_gb',''),(143,'field',121,'en_gb',''),(143,'field',122,'en_gb',''),(143,'field',123,'en_gb',''),(143,'field',76,'en_gb',''),(143,'field',3,'en_gb',''),(143,'field',30,'en_gb',''),(143,'username',0,'en_gb',' robin coffeebean design '),(143,'firstname',0,'en_gb',' robin '),(143,'lastname',0,'en_gb',' willmott '),(143,'fullname',0,'en_gb',' robin willmott '),(143,'email',0,'en_gb',' robin coffeebean design '),(143,'slug',0,'en_gb',''),(1190,'filename',0,'en_gb',' unit png '),(194,'field',51,'en_gb',' 1 '),(194,'field',52,'en_gb',' false '),(194,'field',53,'en_gb',' 1 '),(194,'slug',0,'en_gb',''),(195,'field',51,'en_gb',' 182 '),(195,'field',52,'en_gb',' false '),(195,'field',53,'en_gb',' 0 '),(195,'slug',0,'en_gb',''),(196,'field',51,'en_gb',' 182 '),(196,'field',52,'en_gb',' false '),(196,'field',53,'en_gb',' 0 '),(196,'slug',0,'en_gb',''),(370,'field',33,'en_gb',''),(370,'field',34,'en_gb',''),(370,'field',58,'en_gb',''),(370,'field',27,'en_gb',' 0 '),(370,'field',59,'en_gb',' 0 '),(370,'slug',0,'en_gb',' unit 355 robin willmott '),(370,'title',0,'en_gb',' unit 355 robin willmott '),(370,'field',17,'en_gb',''),(369,'title',0,'en_gb',' unit robin willmott '),(370,'field',26,'en_gb',' dummy evidence '),(369,'slug',0,'en_gb',' unit robin willmott '),(369,'field',26,'en_gb',''),(369,'field',17,'en_gb',''),(369,'field',33,'en_gb',''),(369,'field',34,'en_gb',''),(369,'field',58,'en_gb',''),(369,'field',27,'en_gb',' 0 '),(369,'field',59,'en_gb',' 0 '),(1191,'title',0,'en_gb',' dd '),(1191,'slug',0,'en_gb',' dd '),(446,'kind',0,'en_gb',' pdf '),(430,'title',0,'en_gb',' evidence '),(430,'kind',0,'en_gb',' pdf '),(430,'slug',0,'en_gb',' evidence '),(430,'extension',0,'en_gb',' pdf '),(430,'filename',0,'en_gb',' evidence_180423_104254 pdf '),(373,'field',59,'en_gb',' 0 '),(373,'field',27,'en_gb',' 0 '),(373,'field',58,'en_gb',''),(373,'field',34,'en_gb',''),(373,'field',33,'en_gb',''),(373,'field',26,'en_gb',' dummy evidence '),(373,'field',17,'en_gb',''),(373,'slug',0,'en_gb',' unit 355 robin willmott '),(373,'title',0,'en_gb',' unit 355 robin willmott '),(374,'field',26,'en_gb',' dummy evidence '),(374,'field',17,'en_gb',''),(374,'field',33,'en_gb',''),(374,'field',34,'en_gb',''),(374,'field',58,'en_gb',''),(374,'field',27,'en_gb',' 0 '),(374,'field',59,'en_gb',' 0 '),(374,'slug',0,'en_gb',' unit 355 robin willmott '),(374,'title',0,'en_gb',' unit 355 robin willmott '),(375,'field',26,'en_gb',' dummy evidence '),(375,'field',17,'en_gb',''),(375,'field',33,'en_gb',''),(375,'field',34,'en_gb',''),(375,'field',58,'en_gb',''),(375,'field',27,'en_gb',' 0 '),(375,'field',59,'en_gb',' 0 '),(375,'slug',0,'en_gb',' unit 355 robin willmott '),(375,'title',0,'en_gb',' unit 355 robin willmott '),(376,'field',26,'en_gb',' dummy evidence '),(376,'field',17,'en_gb',''),(376,'field',33,'en_gb',''),(376,'field',34,'en_gb',''),(376,'field',58,'en_gb',''),(376,'field',27,'en_gb',' 0 '),(376,'field',59,'en_gb',' 0 '),(376,'slug',0,'en_gb',' unit 355 robin willmott '),(376,'title',0,'en_gb',' unit 355 robin willmott '),(377,'field',26,'en_gb',' dummy evidence '),(377,'field',17,'en_gb',''),(377,'field',33,'en_gb',''),(377,'field',34,'en_gb',''),(377,'field',58,'en_gb',''),(377,'field',27,'en_gb',' 0 '),(377,'field',59,'en_gb',' 0 '),(377,'slug',0,'en_gb',' unit 355 robin willmott '),(377,'title',0,'en_gb',' unit 355 robin willmott '),(446,'extension',0,'en_gb',' pdf '),(446,'filename',0,'en_gb',' evidence_180424_090746 pdf '),(428,'slug',0,'en_gb',' evidence '),(428,'title',0,'en_gb',' evidence '),(428,'kind',0,'en_gb',' pdf '),(428,'extension',0,'en_gb',' pdf '),(428,'filename',0,'en_gb',' evidence pdf '),(440,'filename',0,'en_gb',' evidence_180423_110232 pdf '),(440,'extension',0,'en_gb',' pdf '),(446,'title',0,'en_gb',' evidence '),(497,'kind',0,'en_gb',' pdf '),(497,'slug',0,'en_gb',' evidence '),(497,'title',0,'en_gb',' evidence '),(143,'field',88,'en_gb',''),(440,'kind',0,'en_gb',' pdf '),(440,'slug',0,'en_gb',' evidence '),(440,'title',0,'en_gb',' evidence '),(432,'filename',0,'en_gb',' evidence_180423_105359 pdf '),(432,'extension',0,'en_gb',' pdf '),(432,'kind',0,'en_gb',' pdf '),(432,'slug',0,'en_gb',' evidence '),(432,'title',0,'en_gb',' evidence '),(443,'title',0,'en_gb',' evidence '),(443,'slug',0,'en_gb',' evidence '),(443,'kind',0,'en_gb',' pdf '),(443,'filename',0,'en_gb',' evidence_180423_115500 pdf '),(443,'extension',0,'en_gb',' pdf '),(435,'filename',0,'en_gb',' evidence_180423_105559 pdf '),(435,'extension',0,'en_gb',' pdf '),(435,'kind',0,'en_gb',' pdf '),(435,'slug',0,'en_gb',' evidence '),(435,'title',0,'en_gb',' evidence '),(446,'slug',0,'en_gb',' evidence '),(437,'title',0,'en_gb',' evidence '),(437,'slug',0,'en_gb',' evidence '),(437,'kind',0,'en_gb',' pdf '),(437,'extension',0,'en_gb',' pdf '),(437,'filename',0,'en_gb',' evidence_180423_105654 pdf '),(497,'extension',0,'en_gb',' pdf '),(497,'filename',0,'en_gb',' evidence pdf '),(448,'filename',0,'en_gb',' evidence_180424_091154 pdf '),(448,'extension',0,'en_gb',' pdf '),(448,'kind',0,'en_gb',' pdf '),(448,'slug',0,'en_gb',' evidence '),(448,'title',0,'en_gb',' evidence '),(450,'filename',0,'en_gb',' evidence_180424_091223 pdf '),(450,'extension',0,'en_gb',' pdf '),(450,'kind',0,'en_gb',' pdf '),(450,'slug',0,'en_gb',' evidence '),(450,'title',0,'en_gb',' evidence '),(1200,'filename',0,'en_gb',' unit_180911_120731 png '),(1200,'extension',0,'en_gb',' png '),(1200,'kind',0,'en_gb',' image '),(1200,'slug',0,'en_gb',' unit '),(1200,'title',0,'en_gb',' unit '),(1423,'slug',0,'en_gb',''),(1423,'email',0,'en_gb',' portia hartley lantra co uk '),(1423,'fullname',0,'en_gb',' portia hartley '),(143,'field',132,'en_gb',' 0 '),(143,'field',128,'en_gb',''),(143,'field',129,'en_gb',' 1 '),(488,'field',13,'en_gb',''),(488,'field',15,'en_gb',' 1000 '),(488,'slug',0,'en_gb',''),(1423,'lastname',0,'en_gb',' hartley '),(1423,'field',148,'en_gb',''),(1423,'field',149,'en_gb',''),(1423,'field',151,'en_gb',' 0 '),(1423,'username',0,'en_gb',' portia hartley lantra co uk '),(1423,'firstname',0,'en_gb',' portia '),(488,'field',75,'en_gb',''),(1423,'field',147,'en_gb',' 0 '),(1423,'field',150,'en_gb',' 0 '),(1423,'field',124,'en_gb',''),(1423,'field',77,'en_gb',''),(1423,'field',84,'en_gb',''),(1423,'field',88,'en_gb',''),(1423,'field',76,'en_gb',''),(1423,'field',123,'en_gb',''),(1423,'field',122,'en_gb',''),(1423,'field',121,'en_gb',''),(1423,'field',132,'en_gb',' 0 '),(1423,'field',129,'en_gb',' 1 '),(1423,'field',30,'en_gb',''),(1423,'field',3,'en_gb',''),(1423,'field',128,'en_gb',''),(1,'field',132,'en_gb',' 0 '),(1271,'extension',0,'en_gb',' jpg '),(1271,'kind',0,'en_gb',' image '),(1271,'slug',0,'en_gb',' bground img 2 '),(1271,'title',0,'en_gb',' bground img 2 '),(1272,'filename',0,'en_gb',' bground img 3 jpg '),(1272,'extension',0,'en_gb',' jpg '),(1272,'kind',0,'en_gb',' image '),(1272,'slug',0,'en_gb',' bground img 3 '),(1272,'title',0,'en_gb',' bground img 3 '),(1273,'filename',0,'en_gb',' bground img 4 jpg '),(1273,'extension',0,'en_gb',' jpg '),(1273,'kind',0,'en_gb',' image '),(1273,'slug',0,'en_gb',' bground img 4 '),(1273,'title',0,'en_gb',' bground img 4 '),(488,'field',141,'en_gb',''),(1138,'slug',0,'en_gb',''),(1138,'field',136,'en_gb',' result blocked '),(1138,'field',135,'en_gb',' endorsement required '),(1138,'field',134,'en_gb',' manager summary '),(1138,'field',138,'en_gb',' licences remaining '),(1138,'field',137,'en_gb',' module completed '),(1138,'field',140,'en_gb',' scheme expiry date '),(1138,'field',139,'en_gb',' user expiry date '),(1138,'field',133,'en_gb',''),(1256,'title',0,'en_gb',' test custom result '),(1256,'slug',0,'en_gb',' test custom result '),(1256,'field',33,'en_gb',''),(1256,'field',34,'en_gb',''),(1256,'field',87,'en_gb',''),(1256,'field',17,'en_gb',''),(1256,'field',142,'en_gb',' 2018 09 01 '),(1256,'field',143,'en_gb',' 2018 09 07 '),(1256,'field',144,'en_gb',' london '),(1256,'field',86,'en_gb',' result notes '),(1256,'field',29,'en_gb',' pending '),(1255,'title',0,'en_gb',' test custom result '),(1255,'slug',0,'en_gb',' test custom result '),(1255,'field',144,'en_gb',' london '),(1255,'field',86,'en_gb',' result notes '),(488,'field',127,'en_gb',''),(488,'field',83,'en_gb',' 50 '),(488,'field',82,'en_gb',' 365 '),(488,'field',85,'en_gb',''),(1255,'field',143,'en_gb',' 2018 09 07 '),(1255,'field',142,'en_gb',' 2018 09 01 '),(1255,'field',87,'en_gb',''),(1255,'field',17,'en_gb',''),(488,'field',131,'en_gb',' 0 '),(488,'field',89,'en_gb',''),(1,'field',124,'en_gb',''),(1,'field',123,'en_gb',''),(1,'field',122,'en_gb',''),(1,'field',121,'en_gb',''),(1,'field',129,'en_gb',' 1 '),(1,'field',128,'en_gb',' leroy s test company '),(1170,'field',87,'en_gb',''),(1170,'field',17,'en_gb',''),(1170,'field',86,'en_gb',' test '),(1170,'field',29,'en_gb',' pending '),(1170,'field',33,'en_gb',''),(1170,'field',34,'en_gb',''),(1170,'slug',0,'en_gb',' test for user one '),(1255,'field',34,'en_gb',''),(1014,'filename',0,'en_gb',' koala jpg '),(1014,'extension',0,'en_gb',' jpg '),(1014,'kind',0,'en_gb',' image '),(1014,'slug',0,'en_gb',' koala '),(1014,'title',0,'en_gb',' koala '),(1015,'field',29,'en_gb',' pending '),(1015,'field',26,'en_gb',' working at heights '),(1015,'field',33,'en_gb',''),(1015,'field',34,'en_gb',''),(1015,'field',87,'en_gb',''),(1015,'field',17,'en_gb',' koala '),(1015,'field',86,'en_gb',' koala in a tree '),(1015,'field',58,'en_gb',''),(1015,'field',27,'en_gb',' 0 '),(1015,'slug',0,'en_gb',' unit 880 richard crompton '),(1015,'title',0,'en_gb',' unit 880 richard crompton '),(1438,'field',84,'en_gb',''),(1438,'field',88,'en_gb',''),(1438,'field',76,'en_gb',''),(1438,'field',123,'en_gb',''),(1438,'field',122,'en_gb',''),(1438,'field',121,'en_gb',''),(1438,'field',132,'en_gb',' 0 '),(1438,'field',129,'en_gb',' 1 '),(1438,'field',30,'en_gb',''),(1,'field',76,'en_gb',''),(1,'field',84,'en_gb',''),(1,'field',88,'en_gb',''),(1,'field',77,'en_gb',''),(1255,'field',33,'en_gb',''),(1079,'field',119,'en_gb',' 1c2b39 '),(1079,'field',118,'en_gb',' 2e338f '),(1088,'field',113,'en_gb',' working with industry groups and employers '),(1088,'field',114,'en_gb',' at the heart of lantra s organisation are environmental and land based employers experts in their own field who know first hand the requirements of their industries employers play an integral part in the corporate structure of the organisation from working groups through to lantra s board of directors and play a key leadership role in forming and shaping lantra s strategies products and services '),(1079,'field',117,'en_gb',' lantra awards logo '),(1079,'field',71,'en_gb',' lantra scheme '),(1079,'field',116,'en_gb',''),(1087,'slug',0,'en_gb',''),(1091,'slug',0,'en_gb',''),(1091,'field',110,'en_gb',' bground img 1 '),(1438,'field',3,'en_gb',''),(1438,'field',128,'en_gb',''),(1091,'field',109,'en_gb',' support your own staff and non employed individuals across your entire workforce '),(1091,'field',108,'en_gb',' welcome to the lantra scheme '),(1086,'field',115,'en_gb',''),(1086,'slug',0,'en_gb',' about lantra '),(1086,'title',0,'en_gb',' about lantra '),(1087,'field',113,'en_gb',' how we work '),(1087,'field',114,'en_gb',' the opinions and ideas of such groups help us to change and improve the industry promoting the importance of skills recognition training and development with the aim of increasing productivity sustainability and ultimately profitability liaising closely with industries within the land based sector we represent along with governments funding agencies learning providers trade associations and the media we can shape important strategies for the future '),(1090,'title',0,'en_gb',' contact us '),(1090,'field',115,'en_gb',''),(1090,'slug',0,'en_gb',' contact us '),(1090,'field',112,'en_gb',' you can send a direct contact to lantra by completing this form we will get back to you with our answer by either telephone or email depending on which option that you chose we aim to reply to all enquiries by the end of the next working day name email telephone reason for contacting us select general enquiry comments would you prefer a response via telephone or email email telephone submit '),(1090,'field',2,'en_gb',' we re here to help use the form below to contact a member of the team '),(1090,'field',1,'en_gb',''),(1086,'field',112,'en_gb',' the opinions and ideas of such groups help us to change and improve the industry promoting the importance of skills recognition training and development with the aim of increasing productivity sustainability and ultimately profitability liaising closely with industries within the land based sector we represent along with governments funding agencies learning providers trade associations and the media we can shape important strategies for the future how we work at the heart of lantra s organisation are environmental and land based employers experts in their own field who know first hand the requirements of their industries employers play an integral part in the corporate structure of the organisation from working groups through to lantra s board of directors and play a key leadership role in forming and shaping lantra s strategies products and services working with industry groups and employers '),(1086,'field',1,'en_gb',''),(1086,'field',2,'en_gb',' industry plays an essential part in lantra s work our role often involves working closely with industry groups to deliver solutions to specific industry needs '),(488,'field',116,'en_gb',''),(1270,'filename',0,'en_gb',' bground img 1 jpg '),(1270,'extension',0,'en_gb',' jpg '),(1270,'kind',0,'en_gb',' image '),(1270,'slug',0,'en_gb',' bground img 1 '),(1270,'title',0,'en_gb',' bground img 1 '),(1271,'filename',0,'en_gb',' bground img 2 jpg '),(1079,'slug',0,'en_gb',''),(1079,'field',107,'en_gb',' support your own staff and non employed individuals across your entire workforce bground img 1 welcome to the lantra scheme '),(1079,'field',111,'en_gb',' about us contact us '),(1088,'slug',0,'en_gb',''),(1079,'field',120,'en_gb',' 000000 '),(488,'field',145,'en_gb',''),(1438,'field',124,'en_gb',''),(1438,'field',150,'en_gb',' 0 '),(1438,'field',147,'en_gb',' 0 '),(1438,'field',148,'en_gb',''),(1438,'field',149,'en_gb',''),(1438,'field',151,'en_gb',' 0 '),(1438,'username',0,'en_gb',' margaret murray skills plus co uk '),(1438,'firstname',0,'en_gb',' margaret '),(1438,'lastname',0,'en_gb',' murray '),(1438,'fullname',0,'en_gb',' margaret murray '),(1438,'email',0,'en_gb',' margaret murray skills plus co uk '),(1438,'slug',0,'en_gb',''),(1440,'field',128,'en_gb',''),(1440,'field',3,'en_gb',''),(1440,'field',30,'en_gb',''),(1440,'field',129,'en_gb',' 1 '),(1440,'field',132,'en_gb',' 0 '),(1440,'field',121,'en_gb',''),(1440,'field',122,'en_gb',''),(1440,'field',123,'en_gb',''),(1440,'field',76,'en_gb',''),(1440,'field',88,'en_gb',''),(1440,'field',84,'en_gb',''),(1440,'field',77,'en_gb',''),(1440,'field',124,'en_gb',''),(1440,'field',150,'en_gb',' 0 '),(1440,'field',147,'en_gb',' 0 '),(1440,'field',148,'en_gb',''),(1440,'field',149,'en_gb',''),(1440,'field',151,'en_gb',' 0 '),(1440,'username',0,'en_gb',' stuart smith lantra co uk '),(1440,'firstname',0,'en_gb',' stuart '),(1440,'lastname',0,'en_gb',' smith '),(1440,'fullname',0,'en_gb',' stuart smith '),(1440,'email',0,'en_gb',' stuart smith lantra co uk '),(1440,'slug',0,'en_gb',''),(2041,'field',144,'en_gb',''),(2041,'field',86,'en_gb',''),(2041,'field',17,'en_gb',' current draft '),(1478,'field',150,'en_gb',' 0 '),(1478,'slug',0,'en_gb',' test job role '),(1478,'title',0,'en_gb',' test job role '),(1667,'field',167,'en_gb',' resultstatus resultendorseddate resultevidence '),(1667,'slug',0,'en_gb',' skills '),(1667,'title',0,'en_gb',' skills '),(1650,'field',150,'en_gb',' 0 '),(1650,'slug',0,'en_gb',' cat a member '),(1650,'title',0,'en_gb',' cat a member '),(1651,'field',167,'en_gb',''),(1651,'slug',0,'en_gb',' centre details '),(1651,'title',0,'en_gb',' centre details '),(1925,'filename',0,'en_gb',' dairy milk jpg '),(1925,'extension',0,'en_gb',' jpg '),(1925,'kind',0,'en_gb',' image '),(1925,'slug',0,'en_gb',' dairy milk '),(1925,'title',0,'en_gb',' dairy milk '),(1926,'field',29,'en_gb',' pending '),(1926,'field',26,'en_gb',' first quarter '),(1926,'field',33,'en_gb',''),(1926,'field',130,'en_gb',''),(1926,'field',34,'en_gb',''),(1926,'field',87,'en_gb',''),(1926,'field',17,'en_gb',' dairy milk '),(1926,'field',86,'en_gb',''),(1926,'field',142,'en_gb',''),(1926,'field',143,'en_gb',''),(1926,'field',144,'en_gb',''),(1926,'field',58,'en_gb',''),(1926,'field',27,'en_gb',' 0 '),(1926,'slug',0,'en_gb',' unit 1659 portia hartley '),(1926,'title',0,'en_gb',' unit 1659 portia hartley '),(1637,'slug',0,'en_gb',''),(1637,'field',169,'en_gb',''),(1637,'field',168,'en_gb',' resultevidence '),(1635,'slug',0,'en_gb',''),(1635,'field',169,'en_gb',''),(1635,'field',168,'en_gb',' resultlocation '),(1634,'slug',0,'en_gb',''),(1633,'field',169,'en_gb',''),(1633,'slug',0,'en_gb',''),(1634,'field',168,'en_gb',' resultfinishdate '),(1634,'field',169,'en_gb',''),(1633,'field',168,'en_gb',' resultstartdate '),(1632,'slug',0,'en_gb',''),(1632,'field',169,'en_gb',''),(1632,'field',168,'en_gb',' resultexpirydate '),(1631,'field',169,'en_gb',''),(1631,'slug',0,'en_gb',''),(1631,'field',168,'en_gb',' resultendorseddate '),(1630,'slug',0,'en_gb',''),(1630,'field',169,'en_gb',''),(1630,'field',168,'en_gb',' resultstatus '),(1625,'field',167,'en_gb',' resultstartdate resultfinishdate resultexpirydate resultlocation resultstatus resultendorseddate resultevidence '),(1626,'slug',0,'en_gb',''),(1626,'firstname',0,'en_gb',' test '),(1626,'lastname',0,'en_gb',' user '),(1626,'fullname',0,'en_gb',' test user '),(1626,'email',0,'en_gb',' test user lantra co uk '),(1626,'field',147,'en_gb',' 0 '),(1626,'field',148,'en_gb',''),(1626,'field',149,'en_gb',''),(1626,'field',151,'en_gb',' 0 '),(1626,'username',0,'en_gb',' test user lantra co uk '),(1626,'field',150,'en_gb',' 0 '),(1626,'field',124,'en_gb',''),(1626,'field',77,'en_gb',''),(1626,'field',84,'en_gb',''),(1626,'field',121,'en_gb',''),(1626,'field',122,'en_gb',''),(1626,'field',123,'en_gb',''),(1626,'field',76,'en_gb',''),(1626,'field',88,'en_gb',''),(1626,'field',132,'en_gb',' 0 '),(1626,'field',129,'en_gb',' 1 '),(1626,'field',30,'en_gb',' test job role '),(1626,'field',3,'en_gb',''),(1626,'field',128,'en_gb',''),(1923,'slug',0,'en_gb',''),(1624,'field',167,'en_gb',' resultstartdate resultfinishdate resultlocation points resulthours resultevidence '),(1940,'field',168,'en_gb',' resultstartdate '),(1940,'field',169,'en_gb',''),(1940,'slug',0,'en_gb',''),(1941,'field',168,'en_gb',' resultfinishdate '),(1941,'field',169,'en_gb',''),(1941,'slug',0,'en_gb',''),(1942,'field',168,'en_gb',' resultlocation '),(1942,'field',169,'en_gb',''),(1942,'slug',0,'en_gb',''),(1943,'field',168,'en_gb',' resulthours '),(1943,'field',169,'en_gb',' points '),(1943,'slug',0,'en_gb',''),(1944,'field',168,'en_gb',' resultevidence '),(1944,'field',169,'en_gb',''),(1944,'slug',0,'en_gb',''),(1667,'field',171,'en_gb',' 0 '),(1625,'field',171,'en_gb',' 1 '),(1624,'field',171,'en_gb',' 1 '),(1079,'field',172,'en_gb',' about us contact us '),(2041,'field',142,'en_gb',''),(2041,'field',33,'en_gb',''),(2041,'field',130,'en_gb',''),(2041,'field',34,'en_gb',''),(2041,'field',87,'en_gb',''),(1535,'filename',0,'en_gb',' example evidence docx '),(1535,'extension',0,'en_gb',' docx '),(1535,'kind',0,'en_gb',' word '),(1535,'slug',0,'en_gb',' example evidence '),(1535,'title',0,'en_gb',' example evidence '),(1536,'field',29,'en_gb',' pending '),(1536,'field',26,'en_gb',' demonstrate ability to outline the importance of undertaking inspections '),(1536,'field',33,'en_gb',''),(1536,'field',130,'en_gb',''),(1536,'field',34,'en_gb',''),(1536,'field',87,'en_gb',''),(1536,'field',17,'en_gb',' example evidence '),(1536,'field',86,'en_gb',''),(1536,'field',142,'en_gb',''),(1536,'field',143,'en_gb',''),(1536,'field',144,'en_gb',''),(1536,'field',58,'en_gb',''),(1536,'field',27,'en_gb',' 0 '),(1536,'slug',0,'en_gb',' unit 1479 jason church '),(1536,'title',0,'en_gb',' unit 1479 jason church '),(1537,'filename',0,'en_gb',' example evidence_181107_121324 docx '),(1537,'extension',0,'en_gb',' docx '),(1537,'kind',0,'en_gb',' word '),(1537,'slug',0,'en_gb',' example evidence '),(1537,'title',0,'en_gb',' example evidence '),(1538,'field',29,'en_gb',' pending '),(1538,'field',26,'en_gb',' demonstrate ability to outline the importance of undertaking inspections '),(1538,'field',33,'en_gb',''),(1538,'field',130,'en_gb',''),(1538,'field',34,'en_gb',''),(1538,'field',87,'en_gb',''),(1538,'field',17,'en_gb',' example evidence '),(1538,'field',86,'en_gb',''),(1538,'field',142,'en_gb',''),(1538,'field',143,'en_gb',''),(1538,'field',144,'en_gb',''),(1538,'field',58,'en_gb',''),(1538,'field',27,'en_gb',' 0 '),(1538,'slug',0,'en_gb',' unit 1479 jason church '),(1538,'title',0,'en_gb',' unit 1479 jason church '),(1539,'filename',0,'en_gb',' example evidence_181107_121503 docx '),(1539,'extension',0,'en_gb',' docx '),(1539,'kind',0,'en_gb',' word '),(1539,'slug',0,'en_gb',' example evidence '),(1539,'title',0,'en_gb',' example evidence '),(1540,'field',29,'en_gb',' pending '),(1540,'field',26,'en_gb',' demonstrate ability to outline the importance of undertaking inspections '),(1540,'field',33,'en_gb',''),(1540,'field',130,'en_gb',''),(1540,'field',34,'en_gb',''),(1540,'field',87,'en_gb',''),(1540,'field',17,'en_gb',' example evidence '),(1540,'field',86,'en_gb',''),(1540,'field',142,'en_gb',''),(1540,'field',143,'en_gb',''),(1540,'field',144,'en_gb',''),(1540,'field',58,'en_gb',''),(1540,'field',27,'en_gb',' 0 '),(1540,'slug',0,'en_gb',' unit 1479 jason church '),(1540,'title',0,'en_gb',' unit 1479 jason church '),(1541,'filename',0,'en_gb',' example evidence_181107_121521 docx '),(1541,'extension',0,'en_gb',' docx '),(1541,'kind',0,'en_gb',' word '),(1541,'slug',0,'en_gb',' example evidence '),(1541,'title',0,'en_gb',' example evidence '),(1542,'field',29,'en_gb',' pending '),(1542,'field',26,'en_gb',' demonstrate ability to outline the importance of undertaking inspections '),(1542,'field',33,'en_gb',''),(1542,'field',130,'en_gb',''),(1542,'field',34,'en_gb',''),(1542,'field',87,'en_gb',''),(1542,'field',17,'en_gb',' example evidence '),(1542,'field',86,'en_gb',''),(1542,'field',142,'en_gb',''),(1542,'field',143,'en_gb',''),(1542,'field',144,'en_gb',''),(1542,'field',58,'en_gb',''),(1542,'field',27,'en_gb',' 0 '),(1542,'slug',0,'en_gb',' unit 1479 jason church '),(1542,'title',0,'en_gb',' unit 1479 jason church '),(1543,'filename',0,'en_gb',' example evidence_181107_122321 docx '),(1543,'extension',0,'en_gb',' docx '),(1543,'kind',0,'en_gb',' word '),(1543,'slug',0,'en_gb',' example evidence '),(1543,'title',0,'en_gb',' example evidence '),(1544,'field',29,'en_gb',' pending '),(1544,'field',26,'en_gb',' demonstrate ability to outline the importance of undertaking inspections '),(1544,'field',33,'en_gb',''),(1544,'field',130,'en_gb',''),(1544,'field',34,'en_gb',''),(1544,'field',87,'en_gb',''),(1544,'field',17,'en_gb',' example evidence '),(1544,'field',86,'en_gb',''),(1544,'field',142,'en_gb',''),(1544,'field',143,'en_gb',''),(1544,'field',144,'en_gb',''),(1544,'field',58,'en_gb',''),(1544,'field',27,'en_gb',' 0 '),(1544,'slug',0,'en_gb',' unit 1479 jason church '),(1544,'title',0,'en_gb',' unit 1479 jason church '),(1923,'field',113,'en_gb',''),(1923,'field',114,'en_gb',''),(1896,'filename',0,'en_gb',' example evidence pdf '),(1896,'extension',0,'en_gb',' pdf '),(1896,'kind',0,'en_gb',' pdf '),(1896,'slug',0,'en_gb',' example evidence '),(1896,'title',0,'en_gb',' example evidence '),(1895,'kind',0,'en_gb',' text '),(1895,'slug',0,'en_gb',' example evidence '),(1895,'title',0,'en_gb',' example evidence '),(1895,'extension',0,'en_gb',' txt '),(1895,'filename',0,'en_gb',' example evidence txt '),(1759,'title',0,'en_gb',' level 1 egg grader farm '),(1759,'slug',0,'en_gb',' level 1 egg grader farm '),(1759,'field',150,'en_gb',' 49584 '),(1758,'title',0,'en_gb',' level 3 manager '),(1758,'slug',0,'en_gb',' level 3 manager '),(1758,'field',150,'en_gb',' 49043 '),(1757,'title',0,'en_gb',' level 2 senior egg collector '),(1757,'slug',0,'en_gb',' level 2 senior egg collector '),(1757,'field',150,'en_gb',' 49042 '),(1756,'title',0,'en_gb',' ﻿level 1 egg collector '),(1756,'slug',0,'en_gb',' level 1 egg collector '),(1756,'field',150,'en_gb',' 49041 '),(1760,'field',150,'en_gb',' 49586 '),(1760,'slug',0,'en_gb',' level 2 stock person '),(1760,'title',0,'en_gb',' level 2 stock person '),(1687,'filename',0,'en_gb',' desert jpg '),(1687,'extension',0,'en_gb',' jpg '),(1687,'kind',0,'en_gb',' image '),(1687,'slug',0,'en_gb',' desert '),(1687,'title',0,'en_gb',' desert '),(1688,'field',29,'en_gb',' pending '),(1688,'field',26,'en_gb',' first quarter '),(1688,'field',33,'en_gb',''),(1688,'field',130,'en_gb',''),(1688,'field',34,'en_gb',''),(1688,'field',87,'en_gb',''),(1688,'field',17,'en_gb',' desert '),(1688,'field',86,'en_gb',''),(1688,'field',142,'en_gb',''),(1688,'field',143,'en_gb',''),(1688,'field',144,'en_gb',''),(1688,'field',58,'en_gb',''),(1688,'field',27,'en_gb',' 0 '),(1688,'slug',0,'en_gb',' unit 1659 billy hives '),(1688,'title',0,'en_gb',' unit 1659 billy hives '),(1683,'field',67,'en_gb',' bacteria test '),(1683,'field',146,'en_gb',' 0 '),(1683,'slug',0,'en_gb',' dog grooming '),(1683,'title',0,'en_gb',' dog grooming '),(1683,'field',130,'en_gb',' 1 '),(1683,'field',86,'en_gb',''),(1683,'field',163,'en_gb',''),(1683,'field',144,'en_gb',''),(1683,'field',17,'en_gb',' koala '),(1683,'field',142,'en_gb',''),(1683,'field',143,'en_gb',''),(1683,'field',33,'en_gb',''),(1683,'field',34,'en_gb',''),(1683,'field',87,'en_gb',''),(1683,'field',29,'en_gb',' pending '),(1682,'title',0,'en_gb',' koala '),(1682,'slug',0,'en_gb',' koala '),(1682,'filename',0,'en_gb',' koala_181207_112804 jpg '),(1682,'extension',0,'en_gb',' jpg '),(1682,'kind',0,'en_gb',' image '),(1679,'title',0,'en_gb',' unit 1656 margaret murray '),(1679,'slug',0,'en_gb',' unit 1656 margaret murray '),(1679,'field',58,'en_gb',''),(1679,'field',27,'en_gb',' 0 '),(1679,'field',143,'en_gb',''),(1679,'field',144,'en_gb',''),(1679,'field',142,'en_gb',''),(1679,'field',86,'en_gb',''),(1679,'field',17,'en_gb',' koala '),(1679,'field',33,'en_gb',''),(1679,'field',130,'en_gb',''),(1679,'field',34,'en_gb',''),(1679,'field',87,'en_gb',''),(1679,'field',26,'en_gb',' professional indemnity '),(1679,'field',29,'en_gb',' pending '),(1677,'field',169,'en_gb',''),(1677,'slug',0,'en_gb',''),(1678,'slug',0,'en_gb',' koala '),(1678,'title',0,'en_gb',' koala '),(1676,'field',169,'en_gb',''),(1676,'slug',0,'en_gb',''),(1677,'field',168,'en_gb',' resultevidence '),(1676,'field',168,'en_gb',' resultendorseddate '),(1675,'slug',0,'en_gb',''),(1675,'field',168,'en_gb',' resultstatus '),(1675,'field',169,'en_gb',''),(2089,'filename',0,'en_gb',' lantra_logo png '),(1678,'filename',0,'en_gb',' koala jpg '),(1678,'kind',0,'en_gb',' image '),(1678,'extension',0,'en_gb',' jpg '),(1591,'filename',0,'en_gb',' about_us jpg '),(1591,'extension',0,'en_gb',' jpg '),(1591,'kind',0,'en_gb',' image '),(1591,'slug',0,'en_gb',' about us '),(1591,'title',0,'en_gb',' about us '),(1595,'field',112,'en_gb',''),(1595,'field',115,'en_gb',''),(1595,'field',2,'en_gb',' learning a new skill doesn t have to interrupt your busy schedule our on demand videos and interactive code challenges are there for you when you need them '),(1595,'field',1,'en_gb',''),(1595,'slug',0,'en_gb',' faqs '),(1595,'title',0,'en_gb',' faqs '),(1623,'field',115,'en_gb',''),(1623,'field',112,'en_gb',''),(1623,'field',2,'en_gb',' about us '),(1623,'field',1,'en_gb',''),(1762,'title',0,'en_gb',' site admin '),(1761,'field',150,'en_gb',' 49590 '),(1761,'slug',0,'en_gb',' level 3 assistant manager '),(1761,'title',0,'en_gb',' level 3 assistant manager '),(1762,'field',150,'en_gb',' 49170 '),(1762,'slug',0,'en_gb',' site admin '),(2047,'filename',0,'en_gb',' unit_181211_093556 png '),(2040,'kind',0,'en_gb',' image '),(2040,'filename',0,'en_gb',' current draft jpg '),(2040,'extension',0,'en_gb',' jpg '),(2040,'slug',0,'en_gb',' current draft '),(2040,'title',0,'en_gb',' current draft '),(2041,'field',29,'en_gb',' pending '),(2041,'field',26,'en_gb',' public liability '),(2041,'field',143,'en_gb',''),(2041,'field',58,'en_gb',''),(2041,'field',27,'en_gb',' 0 '),(2041,'slug',0,'en_gb',' unit 1657 robin willmott '),(2041,'title',0,'en_gb',' unit 1657 robin willmott '),(2048,'field',142,'en_gb',''),(2048,'field',143,'en_gb',''),(2048,'field',144,'en_gb',''),(2048,'field',86,'en_gb',''),(2048,'field',33,'en_gb',''),(2048,'field',130,'en_gb',''),(2048,'field',34,'en_gb',''),(2048,'field',87,'en_gb',''),(2048,'field',17,'en_gb',' unit '),(2048,'field',26,'en_gb',' public liability '),(2047,'title',0,'en_gb',' unit '),(2048,'field',29,'en_gb',' pending '),(2047,'slug',0,'en_gb',' unit '),(2047,'extension',0,'en_gb',' png '),(1620,'field',113,'en_gb',''),(1620,'field',114,'en_gb',' you can send a direct contact to lantra by completing this form we will get back to you with our answer by either telephone or email depending on which option that you chose we aim to reply to all enquiries by the end of the next working day name email telephone reason for contacting us select general enquiry comments would you prefer a response via telephone or email email telephone submit '),(1620,'slug',0,'en_gb',''),(1620,'field',165,'en_gb',' hello '),(2047,'kind',0,'en_gb',' image '),(1623,'slug',0,'en_gb',' about us '),(1623,'title',0,'en_gb',' about us '),(1624,'slug',0,'en_gb',' cpd '),(1624,'title',0,'en_gb',' cpd '),(1625,'slug',0,'en_gb',' qualifications '),(1625,'title',0,'en_gb',' qualifications '),(1,'field',150,'en_gb',' 0 '),(1,'field',147,'en_gb',' 0 '),(1,'field',148,'en_gb',''),(1,'field',149,'en_gb',''),(1,'field',151,'en_gb',' 0 '),(2048,'field',58,'en_gb',''),(2048,'field',27,'en_gb',' 0 '),(2048,'slug',0,'en_gb',' unit 1657 robin willmott '),(2048,'title',0,'en_gb',' unit 1657 robin willmott '),(2058,'filename',0,'en_gb',' unit jpg '),(2058,'extension',0,'en_gb',' jpg '),(2058,'kind',0,'en_gb',' image '),(2058,'slug',0,'en_gb',' unit '),(2058,'title',0,'en_gb',' unit '),(2059,'field',29,'en_gb',' pending '),(2059,'field',26,'en_gb',' public liability '),(2059,'field',33,'en_gb',''),(2059,'field',130,'en_gb',''),(2059,'field',34,'en_gb',''),(2059,'field',87,'en_gb',''),(2059,'field',17,'en_gb',' unit '),(2059,'field',86,'en_gb',''),(2059,'field',142,'en_gb',''),(2059,'field',143,'en_gb',''),(2059,'field',144,'en_gb',''),(2059,'field',58,'en_gb',''),(2059,'field',27,'en_gb',' 0 '),(2059,'slug',0,'en_gb',' unit 1657 robin willmott '),(2059,'title',0,'en_gb',' unit 1657 robin willmott '),(2062,'title',0,'en_gb',' ltp qualifications '),(2063,'field',168,'en_gb',' resultstartdate '),(2063,'field',169,'en_gb',''),(2063,'slug',0,'en_gb',''),(2064,'field',168,'en_gb',' resultfinishdate '),(2064,'field',169,'en_gb',''),(2064,'slug',0,'en_gb',''),(2065,'field',168,'en_gb',' resultexpirydate '),(2065,'field',169,'en_gb',''),(2065,'slug',0,'en_gb',''),(2066,'field',168,'en_gb',' resultlocation '),(2066,'field',169,'en_gb',''),(2066,'slug',0,'en_gb',''),(2062,'slug',0,'en_gb',' ltp qualifications '),(2062,'field',171,'en_gb',' 1 '),(2062,'field',167,'en_gb',' resultstartdate resultfinishdate resultexpirydate resultlocation resultevidence resultstatus resultendorseddate '),(2067,'field',168,'en_gb',' resultevidence '),(2067,'field',169,'en_gb',''),(2067,'slug',0,'en_gb',''),(2078,'filename',0,'en_gb',' desert jpg '),(2078,'extension',0,'en_gb',' jpg '),(2078,'kind',0,'en_gb',' image '),(2078,'slug',0,'en_gb',' desert '),(2078,'title',0,'en_gb',' desert '),(2079,'field',29,'en_gb',' pending '),(2079,'field',26,'en_gb',' yearly renewal t c s '),(2079,'field',33,'en_gb',''),(2079,'field',130,'en_gb',''),(2079,'field',34,'en_gb',''),(2079,'field',87,'en_gb',''),(2079,'field',17,'en_gb',' desert '),(2079,'field',86,'en_gb',''),(2079,'field',142,'en_gb',''),(2079,'field',143,'en_gb',''),(2079,'field',144,'en_gb',''),(2079,'field',58,'en_gb',''),(2079,'field',27,'en_gb',' 0 '),(2079,'slug',0,'en_gb',' unit 1658 margaret murray '),(2079,'title',0,'en_gb',' unit 1658 margaret murray '),(2089,'extension',0,'en_gb',' png '),(2089,'kind',0,'en_gb',' image '),(2089,'slug',0,'en_gb',' lantra logo '),(2089,'title',0,'en_gb',' lantra logo '),(2090,'field',29,'en_gb',' pending '),(2090,'field',26,'en_gb',' induction '),(2090,'field',33,'en_gb',''),(2090,'field',130,'en_gb',''),(2090,'field',34,'en_gb',''),(2090,'field',87,'en_gb',''),(2090,'field',17,'en_gb',' lantra logo '),(2090,'field',86,'en_gb',''),(2090,'field',142,'en_gb',''),(2090,'field',143,'en_gb',''),(2090,'field',144,'en_gb',' stoneleigh '),(2090,'field',58,'en_gb',''),(2090,'field',27,'en_gb',' 0 '),(2090,'slug',0,'en_gb',' unit 2070 portia hartley '),(2090,'title',0,'en_gb',' unit 2070 portia hartley '),(1438,'field',170,'en_gb',' 0 '),(2315,'kind',0,'en_gb',' image '),(2315,'slug',0,'en_gb',' unit '),(2315,'title',0,'en_gb',' unit '),(2315,'extension',0,'en_gb',' png '),(2315,'filename',0,'en_gb',' unit_181212_070405 png '),(2269,'filename',0,'en_gb',' unit_181212_031035 png '),(2269,'extension',0,'en_gb',' png '),(2269,'kind',0,'en_gb',' image '),(2269,'slug',0,'en_gb',' unit '),(2269,'title',0,'en_gb',' unit '),(2270,'field',29,'en_gb',' pending '),(2270,'field',33,'en_gb',''),(2270,'field',34,'en_gb',''),(2270,'field',87,'en_gb',''),(2270,'field',17,'en_gb',' unit '),(2270,'field',142,'en_gb',''),(2270,'field',143,'en_gb',''),(2270,'field',144,'en_gb',''),(2270,'field',163,'en_gb',''),(2270,'field',86,'en_gb',''),(2270,'field',130,'en_gb',' 1 '),(2270,'field',67,'en_gb',' level 3 training '),(2270,'field',146,'en_gb',' 0 '),(2270,'slug',0,'en_gb',' test result '),(2270,'title',0,'en_gb',' test result '),(2271,'filename',0,'en_gb',' unit_181212_031132 png '),(2271,'extension',0,'en_gb',' png '),(2271,'kind',0,'en_gb',' image '),(2271,'slug',0,'en_gb',' unit '),(2271,'title',0,'en_gb',' unit '),(2272,'field',29,'en_gb',' pending '),(2272,'field',33,'en_gb',''),(2272,'field',34,'en_gb',''),(2272,'field',87,'en_gb',''),(2272,'field',17,'en_gb',' unit '),(2272,'field',142,'en_gb',''),(2272,'field',143,'en_gb',''),(2272,'field',144,'en_gb',''),(2272,'field',163,'en_gb',''),(2272,'field',86,'en_gb',''),(2272,'field',130,'en_gb',' 1 '),(2272,'field',67,'en_gb',' level 3 training '),(2272,'field',146,'en_gb',' 0 '),(2272,'slug',0,'en_gb',' test result '),(2272,'title',0,'en_gb',' test result '),(2273,'filename',0,'en_gb',' unit_181212_031309 png '),(2273,'extension',0,'en_gb',' png '),(2273,'kind',0,'en_gb',' image '),(2273,'slug',0,'en_gb',' unit '),(2273,'title',0,'en_gb',' unit '),(2274,'field',29,'en_gb',' pending '),(2274,'field',33,'en_gb',''),(2274,'field',34,'en_gb',''),(2274,'field',87,'en_gb',''),(2274,'field',17,'en_gb',' unit '),(2274,'field',142,'en_gb',''),(2274,'field',143,'en_gb',''),(2274,'field',144,'en_gb',''),(2274,'field',163,'en_gb',''),(2274,'field',86,'en_gb',''),(2274,'field',130,'en_gb',' 1 '),(2274,'field',67,'en_gb',' level 3 training '),(2274,'field',146,'en_gb',' 0 '),(2274,'slug',0,'en_gb',' test result '),(2274,'title',0,'en_gb',' test result '),(2275,'filename',0,'en_gb',' unit_181212_053614 jpg '),(2275,'extension',0,'en_gb',' jpg '),(2275,'kind',0,'en_gb',' image '),(2275,'slug',0,'en_gb',' unit '),(2275,'title',0,'en_gb',' unit '),(2276,'field',29,'en_gb',' pending '),(2276,'field',33,'en_gb',''),(2276,'field',34,'en_gb',''),(2276,'field',87,'en_gb',''),(2276,'field',17,'en_gb',' unit '),(2276,'field',142,'en_gb',''),(2276,'field',143,'en_gb',''),(2276,'field',144,'en_gb',''),(2276,'field',163,'en_gb',''),(2276,'field',86,'en_gb',''),(2276,'field',130,'en_gb',' 1 '),(2276,'field',67,'en_gb',' cpd '),(2276,'field',146,'en_gb',' 0 '),(2276,'slug',0,'en_gb',' test result '),(2276,'title',0,'en_gb',' test result '),(2277,'filename',0,'en_gb',' unit_181212_053630 png '),(2277,'extension',0,'en_gb',' png '),(2277,'kind',0,'en_gb',' image '),(2277,'slug',0,'en_gb',' unit '),(2277,'title',0,'en_gb',' unit '),(2278,'field',29,'en_gb',' pending '),(2278,'field',33,'en_gb',''),(2278,'field',34,'en_gb',''),(2278,'field',87,'en_gb',''),(2278,'field',17,'en_gb',' unit '),(2278,'field',142,'en_gb',''),(2278,'field',143,'en_gb',''),(2278,'field',144,'en_gb',''),(2278,'field',163,'en_gb',''),(2278,'field',86,'en_gb',''),(2278,'field',130,'en_gb',' 1 '),(2278,'field',67,'en_gb',' cpd '),(2278,'field',146,'en_gb',' 0 '),(2278,'slug',0,'en_gb',' test result '),(2278,'title',0,'en_gb',' test result '),(2279,'filename',0,'en_gb',' unit_181212_054206 png '),(2279,'extension',0,'en_gb',' png '),(2279,'kind',0,'en_gb',' image '),(2279,'slug',0,'en_gb',' unit '),(2279,'title',0,'en_gb',' unit '),(2280,'field',29,'en_gb',' pending '),(2280,'field',33,'en_gb',''),(2280,'field',34,'en_gb',''),(2280,'field',87,'en_gb',''),(2280,'field',17,'en_gb',' unit '),(2280,'field',142,'en_gb',''),(2280,'field',143,'en_gb',''),(2280,'field',144,'en_gb',''),(2280,'field',163,'en_gb',''),(2280,'field',86,'en_gb',''),(2280,'field',130,'en_gb',' 1 '),(2280,'field',67,'en_gb',' cpd '),(2280,'field',146,'en_gb',' 0 '),(2280,'slug',0,'en_gb',' test result '),(2280,'title',0,'en_gb',' test result '),(2281,'filename',0,'en_gb',' unit_181212_055558 png '),(2281,'extension',0,'en_gb',' png '),(2281,'kind',0,'en_gb',' image '),(2281,'slug',0,'en_gb',' unit '),(2281,'title',0,'en_gb',' unit '),(2282,'field',29,'en_gb',' pending '),(2282,'field',33,'en_gb',''),(2282,'field',34,'en_gb',''),(2282,'field',87,'en_gb',''),(2282,'field',17,'en_gb',' unit '),(2282,'field',142,'en_gb',''),(2282,'field',143,'en_gb',''),(2282,'field',144,'en_gb',''),(2282,'field',163,'en_gb',''),(2282,'field',86,'en_gb',''),(2282,'field',130,'en_gb',' 1 '),(2282,'field',67,'en_gb',' cpd '),(2282,'field',146,'en_gb',' 0 '),(2282,'slug',0,'en_gb',' test result '),(2282,'title',0,'en_gb',' test result '),(2283,'filename',0,'en_gb',' unit_181212_055625 png '),(2283,'extension',0,'en_gb',' png '),(2283,'kind',0,'en_gb',' image '),(2283,'slug',0,'en_gb',' unit '),(2283,'title',0,'en_gb',' unit '),(2284,'field',29,'en_gb',' pending '),(2284,'field',33,'en_gb',''),(2284,'field',34,'en_gb',''),(2284,'field',87,'en_gb',''),(2284,'field',17,'en_gb',' unit '),(2284,'field',142,'en_gb',''),(2284,'field',143,'en_gb',''),(2284,'field',144,'en_gb',''),(2284,'field',163,'en_gb',''),(2284,'field',86,'en_gb',''),(2284,'field',130,'en_gb',' 1 '),(2284,'field',67,'en_gb',' cpd '),(2284,'field',146,'en_gb',' 0 '),(2284,'slug',0,'en_gb',' test result '),(2284,'title',0,'en_gb',' test result '),(2285,'filename',0,'en_gb',' unit_181212_064134 png '),(2285,'extension',0,'en_gb',' png '),(2285,'kind',0,'en_gb',' image '),(2285,'slug',0,'en_gb',' unit '),(2285,'title',0,'en_gb',' unit '),(2286,'field',29,'en_gb',' pending '),(2286,'field',33,'en_gb',''),(2286,'field',34,'en_gb',''),(2286,'field',87,'en_gb',''),(2286,'field',17,'en_gb',' unit '),(2286,'field',142,'en_gb',''),(2286,'field',143,'en_gb',''),(2286,'field',144,'en_gb',''),(2286,'field',163,'en_gb',''),(2286,'field',86,'en_gb',''),(2286,'field',130,'en_gb',' 1 '),(2286,'field',67,'en_gb',' cpd '),(2286,'field',146,'en_gb',' 0 '),(2286,'slug',0,'en_gb',' test result '),(2286,'title',0,'en_gb',' test result '),(2287,'filename',0,'en_gb',' unit_181212_064451 png '),(2287,'extension',0,'en_gb',' png '),(2287,'kind',0,'en_gb',' image '),(2287,'slug',0,'en_gb',' unit '),(2287,'title',0,'en_gb',' unit '),(2288,'field',29,'en_gb',' pending '),(2288,'field',33,'en_gb',''),(2288,'field',34,'en_gb',''),(2288,'field',87,'en_gb',''),(2288,'field',17,'en_gb',' unit '),(2288,'field',142,'en_gb',''),(2288,'field',143,'en_gb',''),(2288,'field',144,'en_gb',''),(2288,'field',163,'en_gb',''),(2288,'field',86,'en_gb',''),(2288,'field',130,'en_gb',' 1 '),(2288,'field',67,'en_gb',' cpd '),(2288,'field',146,'en_gb',' 0 '),(2288,'slug',0,'en_gb',' test result '),(2288,'title',0,'en_gb',' test result '),(2289,'filename',0,'en_gb',' unit_181212_064659 png '),(2289,'extension',0,'en_gb',' png '),(2289,'kind',0,'en_gb',' image '),(2289,'slug',0,'en_gb',' unit '),(2289,'title',0,'en_gb',' unit '),(2290,'field',29,'en_gb',' pending '),(2290,'field',33,'en_gb',''),(2290,'field',34,'en_gb',''),(2290,'field',87,'en_gb',''),(2290,'field',17,'en_gb',' unit '),(2290,'field',142,'en_gb',''),(2290,'field',143,'en_gb',''),(2290,'field',144,'en_gb',''),(2290,'field',163,'en_gb',''),(2290,'field',86,'en_gb',''),(2290,'field',130,'en_gb',' 1 '),(2290,'field',67,'en_gb',' cpd '),(2290,'field',146,'en_gb',' 0 '),(2290,'slug',0,'en_gb',' test result '),(2290,'title',0,'en_gb',' test result '),(2291,'filename',0,'en_gb',' unit_181212_064710 jpg '),(2291,'extension',0,'en_gb',' jpg '),(2291,'kind',0,'en_gb',' image '),(2291,'slug',0,'en_gb',' unit '),(2291,'title',0,'en_gb',' unit '),(2292,'field',29,'en_gb',' pending '),(2292,'field',33,'en_gb',''),(2292,'field',34,'en_gb',''),(2292,'field',87,'en_gb',''),(2292,'field',17,'en_gb',' unit '),(2292,'field',142,'en_gb',''),(2292,'field',143,'en_gb',''),(2292,'field',144,'en_gb',''),(2292,'field',163,'en_gb',''),(2292,'field',86,'en_gb',''),(2292,'field',130,'en_gb',' 1 '),(2292,'field',67,'en_gb',' cpd '),(2292,'field',146,'en_gb',' 0 '),(2292,'slug',0,'en_gb',' test result '),(2292,'title',0,'en_gb',' test result '),(2342,'field',168,'en_gb',' resultstatus '),(2342,'field',169,'en_gb',''),(2342,'slug',0,'en_gb',''),(2343,'field',168,'en_gb',' resultendorseddate '),(2343,'field',169,'en_gb',''),(2343,'slug',0,'en_gb','');
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
INSERT INTO `craft_sections` VALUES (3,NULL,'Companies','companies','channel',0,NULL,0,'2017-10-23 13:43:33','2017-10-23 13:43:33','86faf121-d218-4348-8ab8-de457b46aa63'),(5,NULL,'Teams','teams','channel',0,NULL,0,'2017-10-23 14:46:37','2017-10-23 14:46:37','bcf0ea1d-9495-423e-ac83-ef089790460c'),(6,NULL,'Modules','modules','channel',1,'module/_entry',0,'2017-10-24 09:39:33','2018-05-02 10:05:20','e9e30b72-2c16-4035-b4b9-e1e4c42ebb3a'),(7,NULL,'Units','units','channel',0,NULL,0,'2017-10-24 09:45:57','2018-05-02 10:04:43','090b3c24-a9ac-4935-a919-c4fc8a3099b0'),(10,NULL,'Results','results','channel',0,NULL,1,'2018-02-14 12:41:38','2018-02-14 12:41:38','c0345752-9e56-4de1-a2d2-da641c3708e0'),(12,NULL,'Attempts','attempts','channel',0,NULL,1,'2018-04-13 10:12:01','2018-04-13 10:32:47','51a18e4b-1a36-4210-8769-a5b8f28f3a5f'),(13,NULL,'Reports','reports','channel',1,'reporting/custom/_entry',0,'2018-08-13 12:10:21','2018-09-04 13:48:46','1d590683-b16e-4955-be62-315accb3e628'),(14,NULL,'Pages','pages','channel',1,'pages/_entry',1,'2018-08-21 13:08:51','2018-08-23 08:45:08','8c1af673-0f01-4434-a6f0-86f01675d799');
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
) ENGINE=InnoDB AUTO_INCREMENT=1118 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_sessions`
--

LOCK TABLES `craft_sessions` WRITE;
/*!40000 ALTER TABLE `craft_sessions` DISABLE KEYS */;
INSERT INTO `craft_sessions` VALUES (826,143,'103bedd9dad1d2a31d9ad559390436328c53f5dbczozMjoiQjdVV3FudE91RFZPQXQwbE9XQUhaWDgweXBVMWRaNGsiOw==','2018-10-24 06:07:00','2018-10-24 06:07:00','c656c776-c75e-4d9b-a5e8-99a672830b93'),(827,1,'3ce7dc6ffaf67fbdedb953be248cc5345844baa6czozMjoiQm1xdDFqRH5fNWhLYkdRWWlaVUxvZ282YVBmaHhuQWUiOw==','2018-11-01 11:05:39','2018-11-01 11:07:40','da68a939-6223-40a6-963b-8df7d132a46c'),(828,1,'ff9a8605668cf80c053c3d7aee1e56bdc122dc38czozMjoiY1dqTXR3T0VYYjRnV0FnaUpnZGdyfmJwc2hmNFV0Y0IiOw==','2018-11-06 11:48:27','2018-11-06 11:48:27','b5ee0d9b-0613-4053-8a15-7a1ea2533049'),(829,1,'2df2ede1c4338fe1d37b2f580576bc3fc5a57313czozMjoiZFJIaXhvXzVEanMydlB6b3RFZjh1NWFnfnJxR2g2blgiOw==','2018-11-06 12:14:31','2018-11-06 12:14:31','47eac479-b9e0-4819-8bf9-4fdafcb57dee'),(830,1,'9606c7e3020092a1015f3a2b7f7fc570c0994711czozMjoiT0Vhal9tbDJuSXZsRklvYnNVYXV4UW53MlNaSzBBd1YiOw==','2018-11-06 12:15:44','2018-11-06 12:15:44','4587d996-8f89-4785-a3a6-25678fffa97f'),(831,1,'dbc6bfdec459f713186be4e7364ce3943428aaedczozMjoifnlCb291SktHQ2NfSVhDb3B4eW84NEozR0FhTlo2X0kiOw==','2018-11-06 12:17:23','2018-11-06 12:17:23','8e4abec5-dbda-473f-ba63-4e7a24e1f387'),(832,1,'a4ade6c4d11e13a6f9a200dab76bace9e0f11840czozMjoiT0lfbTFvQ2V5RGRRa3h+cGRSTU5BQkZucjlPUmJKQ1MiOw==','2018-11-06 12:18:39','2018-11-06 12:18:39','94e371b6-6d69-46e7-b3ab-bf5ddfcd7ab1'),(833,1,'4ce7b3ae22c632e17793e1d49b3eaf194a450cf6czozMjoiSENUQkdRT1hmYU9STlFyZkhrQ01wUlhJeGpVcktYRWoiOw==','2018-11-06 13:31:26','2018-11-06 13:31:26','e9ae0f15-ed70-41eb-8f51-d1842d2a8878'),(834,1,'55fe91a3f1ab3e6bb416243ba0d63b135fc88fadczozMjoiVGZHWHp6eUNadHJFMVQxeGRPcVRRWjVJWFlTa25INWsiOw==','2018-11-06 15:38:05','2018-11-06 15:38:05','3008101a-ad62-4c6c-b3f6-b3a1bba4b856'),(835,1,'6801921963204a8715a14ebf1e8ea7240ee08d95czozMjoiYThJN2dkVlA0cnZQUmNYdVhmWGRJfkFPUER0ZTFGdXgiOw==','2018-11-07 11:02:14','2018-11-07 11:02:14','dbc3cc5f-ed5b-4d33-bd57-6f5365565dfb'),(836,1,'a81730bb6e7ef59f839dc68e7ea2ef41461a5711czozMjoiVFFkWEVjY3FZNjYyOFhva3YyR01RZGlvWnNHQkhZX2ciOw==','2018-11-07 12:45:39','2018-11-07 12:45:39','2bcae97d-809b-4c06-8025-19a7e23fe391'),(837,1,'9953889583c99bc06c568b944f55c23ba12feb33czozMjoiUEpNbktPRFRuUHRYQW9qWllNMVF4V19xRkRHaFN6cHciOw==','2018-11-07 14:01:15','2018-11-07 14:01:15','4351e97d-7111-4736-9b95-f825d466e96b'),(838,1,'48a79bcefb749a87a485e16fee6bc3de88fe7e2bczozMjoiTGtLdXpWbHdtOTZ4U1lEQU94TzdpeWxKMFZ1ak5sMVoiOw==','2018-11-08 09:46:45','2018-11-08 09:46:45','2c6987b1-2275-4816-a5d9-4cb8f21e3a13'),(840,1,'a9077f190037efd218d172db228506192ce06ec7czozMjoiRDVFUTBnaUVIaDNtU050c1hlako3cVdPbDEwYkJsb2MiOw==','2018-11-08 11:24:22','2018-11-08 11:24:22','5989805d-d487-47b3-8f14-663730faa13c'),(841,1,'1dbb6cf7447076915cf0db32cc2d38cfb731912dczozMjoiV2JKOURxZTFibmt2ZzVramFHZzREQUpfNmZWV0tHbE4iOw==','2018-11-08 13:14:41','2018-11-08 14:42:02','666e40f4-0057-441b-afc3-b7f02cad5ab3'),(842,1,'91dd73929c15ebdec282d5f0ac527bf6de3c587fczozMjoiTnNia1Y4enJXeklGdUZsZ1JPT1ZXSVZ5X05aRFg1REciOw==','2018-11-15 11:39:28','2018-11-15 11:39:28','e9e0dd93-33e9-45df-9260-41915e320975'),(846,1,'579aea9de4c644bc296d4b0bf991edb465b5fde3czozMjoiM3M1SGY4MWtodkM5WWVRQXI5X1pOUHA5OTR1aDF3bGIiOw==','2018-11-15 15:36:40','2018-11-15 15:36:40','0a57c20f-bb64-434d-840b-0d9f5dba2feb'),(847,1,'759202993102bcccea4611ce7777e59ed8db55ffczozMjoiMWsxNkZEN3J6SnhJcUJubkE5Vn4wNzVmZllhaXJ0YVgiOw==','2018-11-15 16:01:02','2018-11-15 16:01:02','f77e48da-b2a1-4005-b44b-4e72692f960b'),(851,1,'d18fd4c3daecf0a8b7685f2eb303b1d0e2e646daczozMjoiRVlEV2FTb2hsbGVPTnlRSmJVWUp1c3BscGtHam5YZHoiOw==','2018-11-16 09:17:40','2018-11-16 09:17:40','b9a482ee-9b4c-49b1-be27-5203e8a92809'),(853,1,'0c60afc947ffd9395c8f10d830a86a70f90e1237czozMjoiZmt+azVoYkJneFV3X353cWdjOUtEQ201NlFqMWgwbmQiOw==','2018-11-16 09:49:11','2018-11-16 09:49:11','6053535c-895f-47a3-8005-b869fd960301'),(863,1,'656badb8867b117b323bb61a6721658c83631367czozMjoiTW1pbVJxSHBTYzRKR0t0U2dGSjVsYVRKdXcza35ZR2YiOw==','2018-11-20 11:44:22','2018-11-20 11:44:22','26d76c6c-3312-45eb-bf3c-bc3f405d94e9'),(864,1,'e23d814653b0c37c0edc7b49fb49d83b4beaff39czozMjoiN0hNRVVSMkR6Zk5XVH5TemxKNjhUfjcwNWVidERwRnciOw==','2018-11-20 12:09:34','2018-11-20 12:09:34','0a3b80c5-e2da-4714-8c8f-ee3427d97540'),(865,1,'7f5655043320fc4c2d013b392198b62506b68687czozMjoiMmgxMmRScFE2WDRVMTJQZFJnYWo1eVVVbUpXS3BDd2UiOw==','2018-11-20 12:28:44','2018-11-20 12:28:44','e5de4435-02bd-42c4-bec3-060275417c6b'),(866,1,'4fc532de8a4d9063aca824d1515d264dc15ef468czozMjoiNEVkRGdUQTRIczU2T0U1aGcxd1JaSWg5RG1RamZqWV8iOw==','2018-11-20 12:44:17','2018-11-20 12:44:17','23211e52-5ac6-4a32-a3b6-c4258bbb1f63'),(867,1,'d98fafb3899ab1f5d80f1b84a53916d47421c80dczozMjoiQU4zRWRZZF9pRjUwTEpFcEZEUkdVcVZUWFhrTl9nb0ciOw==','2018-11-20 12:47:37','2018-11-20 12:47:37','02913d54-a8cc-4b3f-8c61-90d1510c7442'),(868,1,'efd45a698fe9d40255c9b1e0343e50be76ee743aczozMjoiM1doNERDbm5OTFp5WjhqRVdXU2p6N2Z+Z0dPVXdkVEMiOw==','2018-11-20 12:49:11','2018-11-20 13:41:47','82b52708-655c-457f-8b86-0dfd2e31b4e7'),(869,1,'df1a0db89a58d1053ea56e0a2a46539f75043b70czozMjoiTDR0RXg4RWM1RWZJeDQ5VG1xNX5mTms0MTZzeUI4cmYiOw==','2018-11-20 14:05:51','2018-11-20 14:05:51','df9b15d7-67c3-457a-9c10-2a3259875539'),(870,1,'80566d770e26ccf64b0e291d28ff50930054de4eczozMjoiaXJZVEpRT0l+SDlWcDRIc0F1SkZabWJjc1BHZnRTYXIiOw==','2018-11-20 14:06:04','2018-11-20 14:06:04','25eb6c58-66a7-4d2c-a2c7-29f3398d113e'),(871,1,'aa54eda4c754ca6f0f1b68d38e738594071f989dczozMjoiU3FqeWNmOTVMZTA0SEdtMGZiR3lNdWxseGRXdFZFQnIiOw==','2018-11-20 14:07:58','2018-11-20 14:07:58','64a42217-4e91-477b-aad2-2c81afc6f64b'),(872,1,'e97af4e0ab5a5df6790e06e530433215dfd4fb8bczozMjoiN09lNDMwdDhXa292Un5zcEZmR3NSdH5GRDFkWVRyU3YiOw==','2018-11-20 14:45:40','2018-11-20 14:45:40','9d19dd3b-1088-4615-a80c-aaa8cc0e1a5a'),(873,1,'a72188ef5a6f78b5ac6a8564ef17cead6b9cc727czozMjoiZVBJelQ2aGc2THFLaTRzNHFQX0g4Z0NXRWR6TXNXdk8iOw==','2018-11-20 14:47:22','2018-11-20 14:47:22','77ed6a83-ca31-4a65-97b3-e885de6b38e8'),(874,1,'d2f3108444e3fc5b59f6ef97792f3c77378800d8czozMjoieHFUa0FJUEJMU2JvVWxFVWU4bUpZTlRLYzNhUDlEUEciOw==','2018-11-20 14:48:12','2018-11-20 14:48:12','cc74906a-d37d-486f-9f15-e94ce1f29602'),(875,1,'dda5883b9e19b9e31825e291fcbc57681bdb4536czozMjoibUF3N2w3SHk1aXR1SFlSMno5SW1CbkR5Y3VsOVQ5WEUiOw==','2018-11-20 14:49:25','2018-11-20 14:49:25','bb9096fc-46b1-4876-8d5c-9d74fd3937a8'),(876,1,'a9ca174d992ded7392dcd53742210ec75ab7a0d8czozMjoifjdrc1hufmtIczdhZUtQOU9XYzdQRGJ5TFQ3Q25rY18iOw==','2018-11-20 15:40:06','2018-11-20 15:40:06','99970189-6e3a-4d01-bd37-d88b59eb8201'),(877,1,'05accbf80df03d9c518cf578595b166f22c1b785czozMjoiSUdLMHo3dzJfNW5wMWFEMk16cWZQSGhQbzFuNTM2YV8iOw==','2018-11-20 15:53:53','2018-11-20 15:53:53','f045fc76-0094-4194-b590-0546e822c312'),(878,1,'6690f09ee452d1f0aa5369d8abba310a324e830dczozMjoiNlRITzhTZzRldkhfVklSRGlaSjE3WjUwWnBfb2NtOU8iOw==','2018-11-20 15:56:17','2018-11-20 15:56:17','449d33c2-1a85-4f26-83a4-fe2ebe4e7bda'),(879,1,'bc5443232901b0fa45889ee3bc95ccc5b009a84dczozMjoiRnBXWTNzTnd2ZWFWWGhlQzA2aVF0NUx1ZEhNelNSWGYiOw==','2018-11-20 16:01:27','2018-11-20 16:01:27','a444dfa8-3a0f-4d17-8d8b-93260da30cc1'),(880,1,'0ed8be7b13e11f8f8a4da2741a592f7036b3c727czozMjoib2hVZ2J4V1NBdXpLMk4zU1ZKVE0xNm01NnBRTXFGcHUiOw==','2018-11-20 16:12:26','2018-11-20 16:12:26','471d084f-aeea-4d85-b893-e7a4fe0c09c2'),(881,1,'4d83b3874683b57cbfd8332d0a7575b9e1730e1cczozMjoiUGV3MUpTQlFPcE01R1RVY3BwSTg0fkRWSU1WRVF0ekUiOw==','2018-11-20 16:17:42','2018-11-20 16:17:42','32f63c1d-7d3d-45bf-af95-015c9b9caa09'),(883,1,'3e2410cfefa8f6cd5862fa246438ae2fd7924c36czozMjoicVBuNFVBOEFQTVlqdkI3MkR+fl9TcTRfbkM1MFhpa2kiOw==','2018-11-20 16:22:24','2018-11-20 16:22:24','244a48cf-9e9f-4824-996d-ac3a939998dd'),(884,1,'efe44b9db5e8f6c6b538036f61ebcfc54fe6def2czozMjoiV0txaFFobG5DSllUM0hrU1VZR1pUMF91OEFXamhfSFYiOw==','2018-11-20 16:32:51','2018-11-20 16:32:51','9afee58f-10a8-45b2-b3b4-9dea2cd124fc'),(886,1,'3f875232914c66a43d789109af86d12afa78c33aczozMjoieUo1c3VCcTcxbVFYZEdJNkNZUUF2QTI5TUZhfkRQU0UiOw==','2018-11-21 08:46:58','2018-11-21 08:46:58','fd3ead33-b3e5-440d-a9b7-91e6c363d8ea'),(887,1,'ff589281ddc6c57120eb77a6ab26210417b01ca0czozMjoiZUtQcHh1aXg5Z1RGZEVyek5WdHlIUlFEQUZ1N0F5UFQiOw==','2018-11-21 10:07:15','2018-11-21 10:07:15','3acb25ce-7259-422a-9f6c-64f068a6b4fc'),(888,1,'7964306e1d6583d1748b384e9c041743111ec268czozMjoiMHJsZTN4b0pRZVBEY0plc1h6b3IzR3J6cThhZ3U2VngiOw==','2018-11-21 10:24:25','2018-11-21 10:24:25','fca1e571-5bd1-465f-8b83-452e96f4b24f'),(889,1,'129d02a63e49e6f1673dd589664fa4ef07781bf3czozMjoidDQ5bkt+TzlNcUpjaG0xMmc4UXRTbzBHYU5sNWppMU0iOw==','2018-11-21 11:02:02','2018-11-21 11:02:02','26e3a2bc-3688-47cb-af85-77cdcbbcfef6'),(890,1,'d82fcc8d0a31b7e033f6ac529398d39d98d036bdczozMjoiaTJOamE2VThvWGt+SlhEZ1prUUxRc3Jrdn5qbjBhMnkiOw==','2018-11-21 11:11:51','2018-11-21 11:11:51','95df71bc-57e8-4dbf-b55f-d761b2503851'),(891,1,'52618bc22c969b716fcd0eb994bfd035e10d5cccczozMjoiSTloWnZISzhxYXRwM2ZYUXBNdXFyb0tXR1d4M3dDNHUiOw==','2018-11-21 11:18:22','2018-11-21 11:18:22','f35597bd-52a5-440a-a975-3fecf30fe0e9'),(892,1,'13499ed8e6d44f5fecbf6fd13f0234d2128765ffczozMjoiT1oxVU54S1MyYUZhajNEOWg4UEFLZEcyRUtNenR5b3QiOw==','2018-11-21 11:26:50','2018-11-21 11:26:50','5664bd5b-f68d-473c-9114-07ae394a06c0'),(893,1,'2a4602755d8380b9b2cd4f7dd793eb031b8cb5a5czozMjoiOUp0bFI3aklpSTI0YVdwMlVfck1CNFNkaThQYTZEdnciOw==','2018-11-21 11:35:05','2018-11-21 11:35:05','197aa7b9-c950-4c44-adc8-8e24e30c9e70'),(894,1,'2d8e707de7c6adbf110d3157d4131108e98a2e1fczozMjoiX3lvUDNwdldRZEdSNmJLa3VaR09XYVZOZXJWZ0E3WDUiOw==','2018-11-21 11:36:33','2018-11-21 11:36:33','42b73841-9bd1-48ef-bdfb-a411148b93ec'),(895,1,'1142a50ed366f58f76ce281b41d6e7224322a9aaczozMjoiYmlHR1p4SjB6ZFpYM19JTX5+RWZoVnJ+RjB0SDRUZ34iOw==','2018-11-21 11:43:30','2018-11-21 11:43:30','58d3b3dc-0930-4dd8-aa25-51324a1ca055'),(896,1,'956cbc81ba6282672b229018de04f5f767fd6021czozMjoieDVFSVc5bjFlaWhGUERhNDIxYXRTfl9wWnZqdVlrckEiOw==','2018-11-21 11:47:53','2018-11-21 11:47:53','34a1ae2a-3210-4a7d-af4a-79885cd6feed'),(898,1,'24a5ececa3ef9c911221599a6c6fd3456a963152czozMjoiZWdIX2kyTEtEWXo2N0F4UjVUb0UxQU5SWU41NVZRZzMiOw==','2018-11-21 11:54:00','2018-11-21 11:54:00','2333e017-1281-4860-84c8-66a518231a33'),(899,1,'06af497591545b88322da055dadb5c401f9bea20czozMjoiUlhRb1RMT1lOem1BbHpFZ1BTTUNDN0c0flJsQXViQzQiOw==','2018-11-21 12:01:30','2018-11-21 12:01:30','fa64797e-465c-45b5-a89f-53aabaddefa9'),(900,1,'f902efc9366693d15c6bc1856eddd9a4b1b70fa0czozMjoiaUpUWGM4aUFzYUVfOGFuSGNSSXdyZXlTNmFCTWMwREYiOw==','2018-11-21 12:03:47','2018-11-21 12:03:47','bec2ec20-3fed-403c-a538-a6cf33528b4f'),(901,1,'1de9fca510e8f9026e4316484cfba31adef80ebaczozMjoiYVd4VXNURTdoUDcxVVpmaldhX0s3alB5VzlxdXJCMWoiOw==','2018-11-21 12:16:23','2018-11-21 12:16:23','750fabb1-ff16-42a2-a10a-c90ea1eeffd2'),(902,1,'e179bbb51aa8f9c19a5a6a157fb628b056c1a62bczozMjoiQ3RTcGRIS2d3ZzRxZGF4eTgxZ1Z0UktyX192b3M0UVAiOw==','2018-11-21 13:34:29','2018-11-21 13:34:29','2592cdd9-48f0-4f09-b2b5-ee533362e1a3'),(903,1,'26594c09104ad8a89847cc917ad4fcf71bdb1c73czozMjoiMHJXMHFtNTI1MGVDRGN5aFBBb3hhWmJHbVphYVAxZkYiOw==','2018-11-21 13:36:14','2018-11-21 13:36:14','db373b7e-059e-4482-9b4d-2a9642686d7e'),(904,1,'70fe7985882a0815da66187c6d3dcdcf0d4567a8czozMjoiNEhhUVY4cWRQUVpHNGNSdGpiOTBETG45Y29STHF1VngiOw==','2018-11-21 13:37:02','2018-11-21 13:37:02','b4bb6a39-de8c-416f-948c-55e6fee4c745'),(905,1,'71d9279b87a794867a6a3c9813c4eba69914f91eczozMjoiaktFQlpHenVJVGI3dnA0Q25DWW5ZYmhHR0ZUT1ZoUm4iOw==','2018-11-21 13:39:58','2018-11-21 13:39:58','fdfdaad6-451a-4585-ba8a-3c75d8b86e77'),(906,1,'ecd2851d1f45ff94f8a2f8e52416e13fbd63fd78czozMjoiUFlQaU5IRmE2NWJYWG5EQUl5YmZxTUFmRzFsNzJraEwiOw==','2018-11-21 13:50:30','2018-11-21 13:50:30','33b12fd5-bd2c-4f3a-bc37-23c6b3ab5c3a'),(907,1,'f903cb9807fa493ddb56bdf411aa0aa39b3a4504czozMjoiUUR1b0xmSXpQaW8yVWdMeWljZlVvdDVPU1lyVTJyRnAiOw==','2018-11-21 13:52:37','2018-11-21 13:52:37','f06a0f93-ed1b-4890-b95f-e2d5d2876b29'),(908,1,'7bbcda5bc921ca981d33d6a0b624f791c28c04f9czozMjoiRUZIa3dxVThpOXI1TG5EZ2poT3N1TGkzZVZ4Yld2TW0iOw==','2018-11-21 13:54:30','2018-11-21 13:54:30','61f9d077-e4c3-41a8-88e7-d1ad2a71b7e6'),(909,1,'d5fd5c031f22d229ba77829b6aa00b28dbec92ceczozMjoiQmlhSmgxaGY5a1g5bGt+enVuREdCTXZIcXJQcnIydEQiOw==','2018-11-21 14:03:18','2018-11-21 14:03:18','e2e36538-069a-47c2-973d-480a4a0798b9'),(913,1,'8b0262cadb6937a1c4b55bf1abc8ec3d282f59b5czozMjoiaVZtYWlSUG1QZ0tQUl8xbld0NEN+WXBLTU9uaXhfcFYiOw==','2018-11-21 14:08:47','2018-11-21 14:08:47','a71262c0-11a4-4933-ac60-ff9e4ac44242'),(914,1,'ce74487804409818e9672d4862598649ba21e9f5czozMjoiZ0x+ZVFQVGlSTExKZFhxUmNydjhta3piS3VkWHA1VXkiOw==','2018-11-21 14:12:27','2018-11-21 14:12:27','911719ab-2e3e-4803-bc0f-47e21edd67e0'),(915,1,'5bcc30d676ae9807179ed55885f87750095ecf57czozMjoiZG50UG10dEZmNkNiZFk3TnBtWGRyeH5xRWVNfjRPRVUiOw==','2018-11-21 14:15:20','2018-11-21 14:15:20','ec6fa44e-25e0-4c76-9f55-fed19e6fa043'),(916,1,'e53b9cba085ab019d62775a216d479927f8a9ef8czozMjoiM3VGQkJJVkN4YjJYaEJnXzR5bGJIYTdqcEU0RlozZWsiOw==','2018-11-21 14:18:07','2018-11-21 14:18:07','19a53f5a-b2dc-49c8-af3a-49eda3d54d6e'),(917,1,'6a2d8a82228b5b68a567931f60818e22a3a1eabcczozMjoibWpUNng5aE1Wfk0xVFdxYm1ZSkpyZlZwdGtFaDhtbHciOw==','2018-11-21 14:22:36','2018-11-21 14:22:36','a4c1b930-64cb-4387-95cd-c0970d3dfb5e'),(918,1,'0bd142501947547c294fefd0fcc3dda3e70271a2czozMjoiZ3VZd19rUkVEN3MzUEpheVJMfjFXVmxoajdacm5ncFEiOw==','2018-11-21 14:24:34','2018-11-21 14:24:34','9192a6b7-c381-49ed-b885-1ae0c2fdb27f'),(919,1,'fa79f34c5c8119f639704e7125028caee8b3e6f3czozMjoiQTVSSmRtQlJqOUJJUUtuSTR0cW44ZVE0b0JtYWpVT3YiOw==','2018-11-21 14:25:51','2018-11-21 14:25:51','4c360882-7e96-4244-b764-f9c1309639e5'),(920,1,'b09a820d5b54479b0fcd6c2441c473bbcbab845bczozMjoieTBScVBxRER4T3VrdmlPTVg0Nko1R05jUGdiVkJWdlEiOw==','2018-11-21 14:27:05','2018-11-21 14:27:05','5fa3b8a3-3a6b-48af-9b37-4337aa88cf5d'),(921,1,'6c30b099153f5b3038a79bd54277ae29b7a478adczozMjoiSlp3bGlwZndzWFg4OXljRThuN1cwU09lZDBkcUtLaUwiOw==','2018-11-21 14:28:01','2018-11-21 14:28:01','da023d00-08b5-43be-839d-acac216fe76b'),(922,1,'4e24008be1c419d7f00f37bfb0097c835befaab8czozMjoib083V0RHajFxVjhzUGRteEFRTzVBdHZBYnQ1T0psYmEiOw==','2018-11-21 14:29:29','2018-11-21 14:29:29','8946298a-afd6-40c1-95b0-167bd7393a24'),(923,1,'22cb2d6d5a8cece8bd39835e7e2d4ca005c5c296czozMjoiWjZUQU9lS1ZOODdmbXN2WWtQOHBHOFE1T2Q0akc3WmEiOw==','2018-11-21 14:48:51','2018-11-21 14:48:51','41e002f1-0e40-412a-9942-a4acdade43cc'),(924,1,'a092fe745da13d99f964ce5a38a48b534d98afacczozMjoiM2o2b2JPVTNseGVlc21yaTFDTWhZcWZLOXd4dU5SbzYiOw==','2018-11-21 14:59:50','2018-11-21 14:59:50','ebe01dfb-668a-4b20-943e-0f225bcb5265'),(925,1,'896bed52a4b9ab22ab7ba94d5bab0a77a42e9a38czozMjoib35JSFE0R1ViR2owelN0RnF3UUFjc25YNG94UFVYeXoiOw==','2018-11-21 15:01:03','2018-11-21 15:01:03','9327ec37-4f49-47f6-8663-5a4750363e11'),(926,1,'9890206027aa9e2d68fd7d1ad8e514526021bb2aczozMjoiWFVlamhJU0ZzZ1IyNFJOM0lnYlVyRjl+NWtCVmVUMFYiOw==','2018-11-21 15:04:05','2018-11-21 15:04:05','2e1c2b69-2e3f-46b3-912c-0010a3decc83'),(927,1,'132e459060abb2430d6de7e02d9c7b5f5f0b6677czozMjoiNX5JaFc0Z1RNcmlLX2M3N2hFVjJLMURuVHlHZWNNUHMiOw==','2018-11-21 15:07:02','2018-11-21 15:07:02','f1c6b9ac-576f-4c96-9f42-d034435e628d'),(928,1,'b2e7784d687da6d003d7181fd628c02bd58da531czozMjoiZzRHVVFEVlR1MWZHOUNld2RDd3l4S21BRWt2Q0tmS0EiOw==','2018-11-21 15:09:52','2018-11-21 15:09:52','21e2c3ec-0772-4536-82ec-859ac5c65acf'),(929,1,'fc83d315ecdbe2518e264733fa41ddaf475fbce6czozMjoiYlViSTBsR0RnT2F5NnZic3M4MU1aflJFem9oSVVxQVMiOw==','2018-11-21 15:10:57','2018-11-21 15:10:57','fc00b447-4931-4a36-9597-daf50f28c96d'),(930,1,'05e95d7d0651898215ae8ee6ba0c8acd099df6f4czozMjoiMmVZQmlZdnJTNFBxOTBmbzhzc004d2NZV0luTlBtaU4iOw==','2018-11-21 15:13:21','2018-11-21 15:13:21','81ff76eb-69da-4bfa-9083-eae582333c54'),(931,1,'2458dee19c2912135a9a25afa1f354e803cc6268czozMjoib1BWZV9vNUV5dzdGNkdEVHN6Rl9hUm9tUHlQSEprRTQiOw==','2018-11-21 15:14:00','2018-11-21 15:14:00','f4737bb1-c066-4976-897e-95defc721cc4'),(932,1,'03eb42549d890e6bd3812df904ef990fc0432978czozMjoiNmRWMEJaQVVfcUdHbjA0WjNqUWF6T01uNk01UVNOekEiOw==','2018-11-21 15:15:14','2018-11-21 15:15:14','9bedfe57-1ae9-4357-a879-187da67c93e6'),(933,1,'ef5150f1821b9cf626a9569c147005a168e464e4czozMjoiYWI2WWJyYWFXaXdqX3BxSDBvWF9BaUVFWmZZZTV3SVAiOw==','2018-11-21 15:19:39','2018-11-21 15:19:39','8c0d56a9-2750-4d7a-ad55-dc0db3f23cf4'),(934,1,'16e77a174f77cbc654f2ba6ac4be7eeed7434407czozMjoicjhaOFhDOXdvUXZmSWwxZn5Dc09QekxIcXhNOVJ1WWwiOw==','2018-11-21 15:25:36','2018-11-21 15:25:36','3c600a88-876a-4a1f-9180-cad0ebf1b5e3'),(935,1,'380c60e84e9d63738608f198ff1bb1d7f47ec694czozMjoiSmF1OGlLaTRIZ2ZzT2JwM2NjYWhQczRObVJrMmQzbUEiOw==','2018-11-21 15:26:06','2018-11-21 15:26:06','45e78f17-1104-4fc3-9b51-7da71671e558'),(936,1,'4fd656bfdb681e9b353fa00687c0c67f406c6edeczozMjoiUldqTDl2WVFUWXBfaEtYN1l2dVdrenJ2NkZQMDQ4XzMiOw==','2018-11-21 15:26:51','2018-11-21 15:26:51','9ecc2245-ffcb-49a5-8305-f38aedaef30c'),(937,1,'7c168f36e9cf5de435f9831e9e677e7be5e4f83eczozMjoiQkZGZm5lZzRkUjRPQVFvcnRXQ0lLZGtUMVRMTVdHVWEiOw==','2018-11-21 15:29:26','2018-11-21 15:29:26','6c6b5d65-eacc-41f3-b9f7-e9bb79b10b2d'),(938,1,'faee30047b700ef9b11defcc6095f5e770bf1770czozMjoibEtqZ0dGN0w4djh2NGpMdX44WFF2bWtrZ2tBazM1TXIiOw==','2018-11-21 15:31:54','2018-11-21 15:31:54','57ed9704-c4ee-4b4e-850d-3327e05033ba'),(939,1,'5a02937d1d97816f9349f4d35f3dcbba88a8b501czozMjoiMWFUSnBtUzZkWEJDSE5ZeW9Pa05sdmJYflIwaDUzeWQiOw==','2018-11-21 15:32:39','2018-11-21 15:32:39','68e24082-27bf-4cab-a42d-5497efc9fc09'),(940,1,'c84c99684767413bdf8de223e072cb7ca4f29fd4czozMjoic09rZmVYM211TEJjUFN4WUlOTHF0YXRhWDFta0ZTNXkiOw==','2018-11-21 15:42:28','2018-11-21 15:42:28','91030f0c-ed76-4261-8d6f-cc876838964a'),(941,1,'eb889064d8e42d1814e6cd3b4e25ec6708ca40a1czozMjoiRXpwU1g4NzJHanp6bV9ZTUdFYkhRSXlNOG5ndTZRWU0iOw==','2018-11-21 16:18:59','2018-11-21 16:18:59','daea352c-5593-4f1a-b398-d6b874cb8aba'),(942,1,'da2aedbd85e440b289b7cdae970f3b901747ede3czozMjoicndfWGFIcmZNQXZxdHVwV0xOQUU3ZU85WUp+eWpjYlEiOw==','2018-11-21 16:21:20','2018-11-21 16:21:20','051a7783-dc10-471e-a8c8-4f1cb51db928'),(943,1,'00b3c7e436dd8e07eed96af3de3342ce1f675829czozMjoiWXdWdGJfazdWT3lJcXVTdmIzWVRDcEpDaldKME9pZDciOw==','2018-11-21 16:34:22','2018-11-21 17:14:42','85b448d7-5abc-42fa-ba2b-9fb45e8d77cf'),(944,1,'85c257d9233cf4bbf518e34939a6ffef684ddd12czozMjoickJGa21fcEFMbldkNk5Ddnc5ZndGaXA1akhrbHRpUTQiOw==','2018-11-21 17:16:10','2018-11-21 17:16:10','d51a7f22-e50c-43f5-810b-b1a052df0af1'),(945,1,'621dda944899da262325b88ff55a89f6c9e99a79czozMjoiMmc1fkk3Yn41VDIwZFZVMHFJbFA0ODdxUW81aWtMNWwiOw==','2018-11-21 17:26:33','2018-11-21 17:26:33','a72f56fe-5bcc-443e-9fae-8bd2dd69b7d9'),(946,1,'f5d5868d17355ef748ae86b5cddc2453417772afczozMjoiXzNhaE9JMV95ZTcwbl83b1dEeWpsYW9kc0RoalpoZzIiOw==','2018-11-21 17:27:45','2018-11-21 17:27:45','1a639d17-1c04-4343-9228-31c03e320eb3'),(947,1,'b308d31805f6b215e9b3d41ee36d4afd8cca2244czozMjoiUmlwalNnQjJEWmZfdUdEU2pxMjZENGpFbGlib3U3NXAiOw==','2018-11-21 17:29:06','2018-11-21 17:29:06','a671fc1c-68f2-4bce-8ef9-cb9331180260'),(948,1,'ef54fc86fafe40e0b18953a9117713d8a4a05ae4czozMjoiS1dfZFRrT0ZMMWY1cXh3M0pFcVN1YkVnd0VJX3VSTWQiOw==','2018-11-21 17:32:07','2018-11-21 17:32:07','5467943f-3268-407a-8abd-11fbc228693b'),(950,1,'3bd951f77be9e7c7b08423cdba09b28dfea74585czozMjoidm55VkRGRDdLTTJJTEFFakZoNTF0c3hwNEQ0OXd5al8iOw==','2018-11-22 09:12:59','2018-11-22 09:12:59','11f1b69b-1620-44ac-b9ee-7a4ef032439d'),(951,1,'dbf157ea2d266824ba593d5502f60ab8fba78183czozMjoiYkpQU2UwNEN1cjBvSGd2TU9KUDlRZUZ6cFhCbGFwOEEiOw==','2018-11-22 09:13:03','2018-11-22 09:13:03','db3d33b1-626b-42f3-9ae5-ca6891237d66'),(953,1,'e2808d43313167b3a59267681d4443c2e64b35e9czozMjoiTGc0SktzdlRYRlA1cnoyZDR0Y0p3OUNQSmRBdTZKSTIiOw==','2018-11-22 11:23:01','2018-11-22 11:23:01','ed825052-f010-4474-92c2-76379edc3796'),(955,1,'6408502d2c14a86066a87a5d4f89cb1600aa5b50czozMjoicn4zeHlFcnk4dGVwX2R3XzhkX214cmc0bTNZbjZNb1EiOw==','2018-11-22 13:57:17','2018-11-22 13:57:17','8bf64261-48c3-47f4-af7c-3a7bef09ca36'),(956,1,'bd8ed76886e5c1c0e98ae79cc583a47bd5a59549czozMjoiemdXZll5MFhaSnJCcnA2fk9ZU0t5SXhNZnd+Nmd2b1oiOw==','2018-11-22 14:07:40','2018-11-22 14:07:40','18c304f0-ecaf-44a8-a39b-ea396a7d45dd'),(958,1,'939ede5f9345ee4d3debd094f6260673f950df01czozMjoiSGZQZE1URGZ5OG1RTmtzejJsbHdsVDlXS3pMalZfeEgiOw==','2018-11-22 14:09:29','2018-11-22 14:09:29','de71c849-0795-455e-8aff-fa2e902dd866'),(959,1,'57c2cb116d0a7fd9f4871784ce4b60cd29d354b0czozMjoiRThOVG9JSll+b00wc21Ob2FkWkxMcUMxQ0h3WnZyejgiOw==','2018-11-22 14:14:01','2018-11-22 14:14:01','ae346689-de7c-414e-a32a-afdd3a453048'),(960,1,'f7d006ee3523b806b5934166193892d83f2f97cbczozMjoiVVBVNzQyQ2ZuWHpEcFNGRUs4NmJXQ1g1U0FKOWVkWU8iOw==','2018-11-22 14:16:34','2018-11-22 14:16:34','f72971b1-99c0-4346-b892-238c8ce272a6'),(961,1,'ff5b0e9ec8b389bd62660ac6adb46bdd63f4c630czozMjoiUUxLOVdKMjRVbjhSbmZyZDhuX0hnflR1bGVmTnRLdl8iOw==','2018-11-22 14:20:07','2018-11-22 14:20:07','22ae4618-a451-454b-a40e-d75312b240a1'),(962,1,'b5759bf8a0923f8d9e0818b5159218dd2a6bf08aczozMjoiWEZQX2ZHSkd4MGNjTFc0VE5UVVZkb3EzOGFDNHRFZn4iOw==','2018-11-22 14:24:53','2018-11-22 14:24:53','186c4f18-5b64-4053-83b7-f285479043da'),(963,1,'2cde32e44931fe088adef2cf6c6dda3a464e9aebczozMjoiNEhHQzVTbWMxNXgzcXhWZDJRX2JieWd1Qnl5VzJRTHkiOw==','2018-11-22 14:25:58','2018-11-22 14:25:58','6fd7fb35-bf1d-4eeb-8764-723be267816e'),(964,1,'3996c8c62b7fb1ad8e621b8cb6fe03c0be8c671fczozMjoic1dvfmlYaThUfmtXZ1o1UXJEZ19UT0pNTVY3dWF3WGYiOw==','2018-11-22 14:26:22','2018-11-22 14:26:22','415b6988-ace7-489b-9f29-c72425d55513'),(965,1,'305a5277dffe556f72534b87c91018bbe556e380czozMjoibjlQc1dYRVU5UmtXTXFtTkJYcWl3cE1VazU1MXJoNjgiOw==','2018-11-22 14:27:20','2018-11-22 14:27:20','02176dc8-8039-43e1-b3c3-5c3cf2bd3651'),(966,1,'e1e86d28b19e4e780c6f70839bbca03b45f2b846czozMjoiV1VWek1mV0ZDZDk4dG5wZkhORVlDVlByMXRjUDRSbE0iOw==','2018-11-22 14:27:46','2018-11-22 14:27:46','803b388e-e75e-45cd-907c-4fcc8319b467'),(967,1,'566092cc642491efc53336a7e35d28dacbb3981eczozMjoielZqVW1PWU1BUWtJcmtfcFhfZWRVd2FqWjJzZGNNZGUiOw==','2018-11-22 14:29:03','2018-11-22 14:29:03','faca7329-d011-47ff-a773-62ed6ecb1e7a'),(968,1,'983da80b96f87d052fc91956df3102b8fa8b828fczozMjoiUVUyM1FZelh5VkxoR2R1OFVjdDN3Nm9tMzlRYWRWaXUiOw==','2018-11-22 14:29:33','2018-11-22 14:29:33','62a26293-a9d6-4319-8097-81973840098e'),(969,1,'0dabe7fbb4e21db5503e26f5bf8dff71846a484cczozMjoiZjB2TmpnbG9mVVdCZHZyQTNCY3h0T25qSExYUml2TXoiOw==','2018-11-22 14:31:28','2018-11-22 14:31:28','ae9cf412-13ca-4e63-9b4a-c8458bf2452c'),(970,1,'967b7779b23ebf65759c9321ac2c155d209087f3czozMjoiWF85YWpVRmQ3d0lPfnY3SXdzR0x4cnZFU0JDTGR1U28iOw==','2018-11-22 14:33:07','2018-11-22 14:33:07','9381057b-1ef1-4ae0-9fa5-0a0c57d96f02'),(971,1,'0e5a1a9d670336b3bd3260df45912c9d6d97d520czozMjoiZVJUR0l+c3V4NEp4fmlUVmZXTnZyZE1Fd0RZbkFQTGEiOw==','2018-11-22 14:33:40','2018-11-22 14:33:40','345741f0-3a7d-423e-bf93-0db1960f48ee'),(972,1,'d15ff88ab32a488f18d2a7f7e42ab3b2fc9d696dczozMjoiS082RHkyekVWUWpTUU04MmpOUHFWa0dVZkFyZm9fREIiOw==','2018-11-22 14:39:28','2018-11-22 14:39:28','063c2ff7-f261-4b27-9254-a5e12bb6f712'),(973,1,'e540fbcc03c90f223f21e517b17c5de8ae003e3dczozMjoiU3FmZmUyeXg5WXJ2V2R3VFFhU0ZkMWxTRFJGNzNHTDUiOw==','2018-11-22 14:47:35','2018-11-22 14:47:35','785ec578-f445-4ab4-be55-e77c198690e6'),(974,1,'8c8a9601a8021f552f986f6fd4d28b957168896aczozMjoidEppQ2lXdGNRSlh3cVd3dlZpVEZ0MXhwdEZyTE5oVmoiOw==','2018-11-22 14:49:46','2018-11-22 14:49:46','18a9934a-38d1-4a0e-8592-3e0086c79f9b'),(975,1,'248d846aed5f673c3bd27d4e1727743de374613aczozMjoid1JENjV0dGE4Zm1HcUt6NjZSdG93WGFDOWp0eGFaak4iOw==','2018-11-22 14:54:10','2018-11-22 14:54:10','4cdc5e06-a66d-4a08-864c-8d7f6f0c395a'),(976,1,'022b69c54b38669ed4a981999a5abc04aefd25e9czozMjoiT3BtaVIyT2xXV003dTFydmNFQmRaa35WT3A3eURrenUiOw==','2018-11-22 14:55:03','2018-11-22 14:55:03','7355227e-ecd5-42aa-b4bf-a094360efa19'),(977,1,'1db1eb8c12a71404cbe65f32a700c4c0ae0da513czozMjoiWTJWU1BvNUt3RF8wRmhNWDkzcV9GRjhpVjlHZWdRWjgiOw==','2018-11-22 14:55:33','2018-11-22 14:55:33','44315f75-c9ee-4cae-b15c-7c983fb19636'),(978,1,'4723a403ec5f780cc7c7481f722fd5a4a962c684czozMjoiejFtTTBlckhXYXNYczhzWEVqRHJSdUZRTVVzRkptUnQiOw==','2018-11-22 14:55:54','2018-11-22 14:55:54','30c2c75c-ece8-4612-9bc3-5b7b4bca0449'),(979,1,'13c435dde86ecc4ad10739691a9c89cefbce18bfczozMjoiSEZRZkpqa3g3eVZCNG42UjRYX0VvU2hlX0xuUUFCRjUiOw==','2018-11-22 14:56:42','2018-11-22 14:56:42','6284b66c-6728-4380-8cd4-dee2495cff51'),(980,1,'929d8431cbbad40faca1416f4fa5654e369a6507czozMjoiM3JveG1hWDVMV1R5a1BxVThwdG5SUzIzZEt0V3lmNlEiOw==','2018-11-22 14:56:42','2018-11-22 14:56:42','a01d188e-57b8-42b9-83e7-ea166c502092'),(981,1,'b59b3610e86f968e61ccfe13c384238df03262efczozMjoiUWl5cUtaRXl4MUpFRGVrTVd4QWFZUjVyY21sVDlUYUIiOw==','2018-11-22 14:58:16','2018-11-22 14:58:16','a095f534-6918-4bf4-b7bb-2042d2e8f7df'),(982,1,'da0d9d54a4ad1b9aadd8247f80ceecee2be067adczozMjoiMERYdnNqT0REZWV+cDAzT2FnfkxjZlJEdGI2NFpxSDkiOw==','2018-11-22 14:59:32','2018-11-22 14:59:32','d03f1fca-1dbc-41ef-b90d-57e2bdec6733'),(983,1,'0127d1a53a0c8d7c0c938af5c0ee9ed45e03addbczozMjoiU21LWldwVWs5Z1JaX3JtendUblY5QTBFQ2d0ckRSSEIiOw==','2018-11-22 15:00:25','2018-11-22 15:00:25','74507fa7-6b49-481a-8e9c-c804737bb8aa'),(984,1,'c3d22a9940af1639bfea6b7270e554b7fcc4f016czozMjoiMkUzOWJpaGNPUTRmaUtRNkVfM3JRNGNTM3BNQmZIa0giOw==','2018-11-22 15:03:01','2018-11-22 15:03:01','4cf0b93d-08e7-4796-80e2-2f6f7a50f614'),(985,1,'a149aaf0bb6f6c156ce7788b0be142a79ffc3227czozMjoiZzRkdWJ+ejV+Q2FaNk9LVWJZa0NXenJXcjB6ekJ1eHQiOw==','2018-11-22 15:04:04','2018-11-22 15:04:04','c635aad8-009d-42ec-9e68-9788f0fb66a8'),(986,1,'1c4e2889ee88fc62c2c08b37fb7904f8e2b4c42cczozMjoicWRBaHJISkFUdW12WTFXVDZkUFRXS0QxX0FkUVJYT2MiOw==','2018-11-22 15:06:50','2018-11-22 15:06:50','26b9ac25-df24-48a7-a843-345b65a0057d'),(987,1,'cb3d9aabfdee9da9fdc6b17d75a7182ed7a74358czozMjoiSVpKQ3NRTEZhTlYyN35EZERvWlphd1lpcnNZZ1dSVFkiOw==','2018-11-22 15:08:45','2018-11-22 15:08:45','44f6460f-9d5a-4c5a-84b5-05159249bb59'),(988,1,'227f5084952fb95cca60eba25995f8f5ed874b84czozMjoiX1pJZUVBdWRva3YzUWZsQlFoYThQUnZBNmd0OG5QWUYiOw==','2018-11-22 15:14:08','2018-11-22 15:14:08','f097dc51-6c39-47dd-8518-e15ffb0d2b64'),(989,1,'de6ac8e02971e74926db4c7f3194d14550cd0454czozMjoiOFBkR21SZVRMdlp6aFhXd0NmOVpfZ1JPbHZoZkhYTmsiOw==','2018-11-22 15:20:54','2018-11-22 15:20:54','b1058350-8fc1-4ddd-a021-c4a4b0a72150'),(990,1,'63728408ffe6aa9f476f926940ffe15a131a9081czozMjoiNWlrfkxRcG1iMjZ0ZkpDbTNxcDNkbVRPSnVwNUtkUVgiOw==','2018-11-22 15:21:42','2018-11-22 15:21:42','87a0df9e-ee53-4422-b0bf-03857924c92f'),(991,1,'8c0c652e57ce9fc4703d382c6501aaa0285c20c2czozMjoiT1AxdmRqaWZMd2wyNF84SlRCYUxpQlhxdlZrWDI4SGYiOw==','2018-11-22 15:25:06','2018-11-22 15:25:06','c9b2195e-a64d-486b-93c4-b0ec0fbc580a'),(992,1,'c246c0bdc0838b5ab49c7ebf204e9bf964e344d1czozMjoiMGcxSzFQMkNfYnM5ZXoyRXZJTmJfSlphUnlKQmszMUUiOw==','2018-11-22 15:27:03','2018-11-22 15:27:03','972ad559-b5bf-46de-8327-41ac63cdcdbb'),(993,1,'6915b50b0b996203b2f1aa41106958ad94faa07aczozMjoiWmNkUUhzdUZETn43ZERRfmFXd3lyfjNUNzJmdWdUSnciOw==','2018-11-22 15:31:08','2018-11-22 15:31:08','d260aa97-247c-4954-9e73-850f0094af8c'),(994,1,'81e442a3099aa398a074589ec5dff1cb8f89f6f5czozMjoiazUyX1ZwM0dMV25FT1JjdExGM1BWMnkwV0lLSXA1WDEiOw==','2018-11-22 15:31:56','2018-11-22 15:31:56','45087cfd-63e4-40e3-b5df-e0a967956e62'),(995,1,'9114a00820bed14003838e7ced82b0a5b8c51bedczozMjoieTI1NFNjMGQ4el9JY3dDbl8xVzU5Z2FkSldGNUc4aTMiOw==','2018-11-22 15:59:46','2018-11-22 15:59:46','db5bc0d5-183f-4301-9157-d8c6ec486402'),(996,1,'b912bd395f6ff094c3a24244f2d204dbf833658dczozMjoiQlVPbXFkVGFnQ1NXalczRGllbmNXRXBmSzF1TWRGR0QiOw==','2018-11-22 16:01:02','2018-11-22 16:01:02','329773cb-cdad-4091-83fd-2d44cc157bb3'),(997,1,'b55a27ba39ed98e863de3f39930400b48cc60bcfczozMjoiZnJ6MzduM3BqdVNKN2RqSjNCcVJjVTRTX1MxV2xudWoiOw==','2018-11-22 16:02:11','2018-11-22 16:02:11','49f4b083-ade5-473f-9906-20d47e1c1b58'),(998,1,'8ec47596ec7eea83aef7bd4ca4e41ebb73daae4dczozMjoiRVhPM2NoZEVTNHVOUHNufk83U0pTU1VHQVgyaG5HdTEiOw==','2018-11-22 16:03:13','2018-11-22 16:03:13','e5dac582-ef5b-48b4-b49f-ca2abfca5431'),(999,1,'5eae03906e0a2d20165271c44dba2abba647d430czozMjoiZXh6U3gzcmhIZXBNOHlUdHFyQlRob3RWeVVMSE80ZlEiOw==','2018-11-22 16:06:45','2018-11-22 16:06:45','382cbb47-b35d-4bb0-a19c-a3a66351c682'),(1000,1,'423f66ad53a5363e00b24c04c8e3f7221b9eafddczozMjoieDk3QjdCalJlSGk5bWM5bUJYYX5YNk13cGFMdWlFaUwiOw==','2018-11-22 16:11:12','2018-11-22 16:11:12','ece348aa-ba7d-43a7-88cc-bd63a0d8129a'),(1001,1,'266a597c034c9623018fd3788662956f736ed377czozMjoiUlRuM3pxYk9aamxHeWhuTW50cGkzMnlkbG5iTFg4fnciOw==','2018-11-22 16:39:33','2018-11-22 16:39:33','ce0ceb63-006a-4ca3-b4a6-679f4b8a6ec6'),(1002,1,'f2c1f98cd6b812152e775aa2fe0faa5914f7fdb3czozMjoiRnBxOF80clNzNUtreUFGOTkzb0xJcmUyeGdGZkloRE4iOw==','2018-11-23 09:17:06','2018-11-23 09:17:06','716f23d4-d67e-4f16-b52b-b093bb9c651b'),(1004,1,'64734c131b00b9990f1815a2da3e575207177cc1czozMjoieDU2Z01LV1l3Rnd1RXRkWmZXcXd0dmZYWld4eHNqfnUiOw==','2018-11-23 10:19:23','2018-11-23 10:19:23','f1cb48c9-14f9-4163-b179-4e59a823de56'),(1005,1,'311987f9cc086d47e6f836dc3195fc849e586689czozMjoiUlQ2T1M2S3RsazF2WnladENzcU1waURfZzl+VXRIMzMiOw==','2018-11-23 10:21:27','2018-11-23 10:21:27','655be5fd-491f-4fc4-b6eb-6e8b2be14ee6'),(1006,1,'97fedfa34d1fe8996070e2dee8404b05550c7874czozMjoiblZQMHZ3bURWYzBEQ1VjX2ZOeDU5ZjRiVTFwQkNJNVQiOw==','2018-11-23 10:25:01','2018-11-23 10:25:01','3cdf91fd-5276-48aa-87b3-f2222ad5e546'),(1010,1,'53bb7cdb52892c2219b6b665d5a4df25905164d3czozMjoiUENzQkUyaExibl9NdDAwT2w4cEZ0RUxtZ1FjQVlRT0EiOw==','2018-11-23 11:10:05','2018-11-23 11:10:05','41ecd065-fcf0-4232-a913-e928d0a0ec17'),(1011,1,'e9601ad5f91408320f94d3aaf81a28b6da33b60cczozMjoidElkZkZ+SFV4YWF2N0xqQTRpfjBYcm1hdzE2aVpuOWsiOw==','2018-11-23 11:33:53','2018-11-23 11:33:53','bb0ad77d-edaa-4c84-9c5b-b172ee2971dd'),(1013,1,'faf54a3f657eb020e622de9962decde526ddd42dczozMjoiZjJfVGg0OFhPYVdDbFJCSnJ6dUU2MDhmbVZtZDk3X1ciOw==','2018-11-23 14:14:23','2018-11-23 14:14:23','fba37935-5874-4ffb-945a-d98843aa3e2a'),(1014,1,'6ee2e430fe90e1f55433216c22be085045b212caczozMjoiS0dSOTBLaXNSSGRaVUZtZ1lEcXpBTEdNc2RtU3JJbmUiOw==','2018-11-23 14:30:44','2018-11-23 14:30:44','4fbdd1d9-ee21-4085-acc5-36d414cc52f1'),(1015,1,'693a0c44a29cf61e8a5e871aa7fea286b0141a79czozMjoiczYzanVvQ3dqM3ZmYUh1WDV3bW5iWHNSN1VQVGZLU2kiOw==','2018-11-23 14:36:08','2018-11-23 14:36:08','ebb00136-e901-4114-b630-c5e415346bd4'),(1016,1,'354633e99feffed1a7544096dcb5028cea7ff215czozMjoiS2RKTlVKcmpzYmtSWE5nZ3djeWh+cHNoM0doM0lWSkkiOw==','2018-11-23 14:39:55','2018-11-23 14:39:55','eb155128-ad4b-432e-8ed4-baa1c72db1a2'),(1017,1,'f75b8dc5dd03c1f0c5a6082b189de18a61ca52b0czozMjoiT3hxNU12R1pmQkJ+MHlFTDhoaWx5azRxSXNqc3JNaXAiOw==','2018-11-23 14:51:40','2018-11-23 14:51:40','36ebbe9d-6e2b-469b-8265-aa98f9bcc833'),(1018,1,'b1c693ea8f0f63ff3631dd7c7b68a46afb865d7aczozMjoiWTN4RnRWeVJJTGdoVmFuOGhueDFzcUM0anlNSzVGa2wiOw==','2018-11-23 15:30:33','2018-11-23 15:30:33','8ec3cefa-de35-4a72-9c1a-b9ca361a0653'),(1022,1,'75e8fee3816830e6fe98143d1d1c11ae0bcc19cfczozMjoiSVQwcE5GdmVHcEJCdmVaYmhHWlpKSjZsYjVHeDVNSXIiOw==','2018-11-23 15:47:35','2018-11-23 15:47:35','0bee333a-09dd-4f24-a97d-08db4af4b026'),(1024,1,'b3ffffbad967eee9040af7e0ab5916657b321204czozMjoiaGwwWFNLR1Z+QUVLeDhiYTRJT3dlbHpVb0FvcDhHQ0IiOw==','2018-11-23 15:50:06','2018-11-23 15:50:06','e1adb113-23c2-45ac-b348-cdb7cd671d2c'),(1028,1,'4b1843556946683ecdd7f9ebe070af9a0012e6e7czozMjoic1ZGRXVYZjZaV1AwZXZubEpMNzFlMEh+S0VNTnJ6OHkiOw==','2018-11-23 15:58:58','2018-11-23 15:58:58','ba04753b-443f-4fa1-b792-333ad5a3ca86'),(1031,1,'367dd8b4e55ab7110f218a9c7f0bf3f759a6dc1eczozMjoiQ2xHazR0NUp0aG1vekU5T0JvZ2Y2TzVZcUVHelk1QkIiOw==','2018-11-26 08:46:50','2018-11-26 08:46:50','a9842df1-a07e-490a-8169-382df1d67a89'),(1032,143,'e8e84cdcd079627568c365837b0faff7f676765fczozMjoiVWRYV1V2OXBBN3M4Nk9tWTNmSHhOaUVxS2dvdHN3QnUiOw==','2018-11-26 10:44:16','2018-11-26 10:44:16','c23f9803-7d4a-4b9d-ad74-5a43971b97c9'),(1033,1,'8647322fdefb40f4936c8a68ae05cb1826e740a4czozMjoiRmF6MFJlMjh6fmwzNG44b1dkQ05hazJxWG5wTVpQZ1UiOw==','2018-11-26 10:44:23','2018-11-26 10:44:23','15ff9e23-32f8-4f5b-a83b-504ffe29cb8f'),(1034,1,'83ec8317c12e7f2b1208902470d2db56dee9a332czozMjoicWg5QllWc3hXTTB1RlVIOUs2ZWtSQ2s5aUVoMkM0bWIiOw==','2018-11-26 12:03:53','2018-11-26 12:03:53','3e745b7a-6544-4b62-8529-1fe7e7a5c2b8'),(1035,1,'cc4978d1ca7533c6f574b0859b728f7468b3e002czozMjoiajJpX3UwaTBqQmtWUjcxQThUazdGSnk5dmRuUVpCV1IiOw==','2018-11-26 15:15:02','2018-11-26 15:15:02','a045d5c2-5eae-48fd-b188-de80e3088e06'),(1036,143,'0eac1dc5f25160ae4c4df6562d3b5d654fc414d0czozMjoiYkZuVmc0WkVZeG81NzJ2Q2pmaExGYnhjRlZkTFNQRU4iOw==','2018-11-26 15:15:04','2018-11-26 15:15:04','35f14660-a2dd-4dfb-8f88-a9c2e2eef57f'),(1037,143,'d1200ed27fc1b91019df627d14ef61ed699cd765czozMjoiOE83dFFTUkhGRk9nRjR6R2IyMjB6eHJBWmZFaXhSQ0UiOw==','2018-11-26 17:12:18','2018-11-26 17:12:18','5cdfa5b7-e956-4068-a027-fa7a59da2ed9'),(1039,1,'b67afcca08f64b668c798e5760beea812437935fczozMjoiczlibW9hR2Z0ZkQyOWhnOEg0dW1FdUFIT2FwTXIwakkiOw==','2018-11-27 09:13:36','2018-11-27 09:13:36','1d1a7ed5-36f9-46fe-8721-58795449d1e7'),(1040,1,'bf3e9ae1cac8a578f50201ef32745018bce59723czozMjoiYk9lMmF6WDlMfjN6Y3JtTFdwWF9KbHI4QlZyazIySk8iOw==','2018-11-27 09:13:37','2018-11-27 09:13:37','ffef57e5-29cf-4f65-9d62-d6e4d1b861c0'),(1041,1,'0d71495e8c143efc518b209b789e91383615035fczozMjoiYXIyTFBsWVlqRFpIWTQ0UWhiTVFWaG9EfnkzTVhFWk0iOw==','2018-11-27 09:15:41','2018-11-27 09:15:41','5d4da071-1028-4e98-9753-42ce9eb12d01'),(1042,1,'c9a072c4fd23c625034356807ed5aa03c53a3770czozMjoiVkM4d0E1NlBaNlJLRlN1WnNzOWhLaTJQdFVzbGFYb1MiOw==','2018-11-27 09:16:52','2018-11-27 09:16:52','df20291e-602c-496a-9021-f4cdac8f321d'),(1043,1,'364d97d9a188b0fc9149c39c0ff684d400e1b5c2czozMjoiSEFQZzdvUjlqQm1xcn5tc0tuNU04aEREWU5oUld0RDIiOw==','2018-11-27 09:21:51','2018-11-27 09:21:51','5b495a5b-f0c6-496f-9ec6-6a9d560d860f'),(1044,1,'9bb6a730dd745d2bc94f95c56d0e6c137e3a41a0czozMjoidTAwY3Nqa3Nhb1JPRDJPMTlKQVh6UmlIdFQwc1JyR1QiOw==','2018-11-27 09:23:15','2018-11-27 09:23:15','aba341c4-3ed3-487d-a05f-4689606f2923'),(1045,1,'372816f3b9b07a67b5b4d0bdc12ba355d9f8a505czozMjoiYUF6ZV9sakhHb2hRQU9RY0JxVjlEbmpqVXJaUUVKRGYiOw==','2018-11-27 09:26:01','2018-11-27 09:26:01','b6dc2d07-2fbf-4817-a90c-1719edc4580e'),(1046,1,'4e2d9c5b8618b4c8184a52784d8facf6b59ed3d2czozMjoiUzBoZHlLQlRtcWlhRnphck9TbG9LOHE5THNnaGRxYWIiOw==','2018-11-27 09:28:30','2018-11-27 09:28:30','0f82aaa5-a28e-4d2e-a3fa-450cc4c4d7a4'),(1047,1,'5fa3905db06d67d5314d2872ee1555615af3eab5czozMjoiUXZENFNCMzFIWnFnV0ROQ005NEEyYUNrR3ZwTGEzQWIiOw==','2018-11-27 09:30:54','2018-11-27 09:30:54','fffdcb0f-bb77-4078-b7ba-57974aed71f5'),(1048,1,'954ef8e4df178ab231ce36969ec7ba4de8431ec0czozMjoiZFVpZE0zZ2xmenRGTVNJamt+STR4RU9UcE56a3lkRk0iOw==','2018-11-27 09:39:03','2018-11-27 09:39:03','2d57c712-5aca-4c39-8bbe-b50542335a1a'),(1049,1,'c0c216f346501ba179051e6245b5ce7deeaf0023czozMjoiRXdMTEJYX0M3M2FzNG5lY2hvRDJwQ0xiVVB3Z1lQSDMiOw==','2018-11-27 09:40:35','2018-11-27 09:40:35','df9b276f-92e2-4a82-ad09-b311e529744c'),(1050,143,'05ad51c7b456c8286dd785610a548bf636eab39eczozMjoiNnN1cDlNOGxYSTFDUFpvN24xcVVVTTRFS1lCYUk2OFAiOw==','2018-11-27 09:43:38','2018-11-27 09:43:38','42454a6a-328a-44fc-9502-92959cdc9f87'),(1051,1,'a202478c9b50f7911e4c97fc798a903a309c1209czozMjoidFdoZzR1Tm5pSksyVWR0Um4yVHUxakhmaWRhQ0NHUkkiOw==','2018-11-27 09:55:21','2018-11-27 09:55:21','72efe4c1-f2a7-4946-9d52-7049b07d60ed'),(1052,1,'ef6abcf96d1b5d8c787de0c04f3b10cb1db404d4czozMjoiVzJPM3ZJNW9CazFBd0xSN3ZzVjlqc0J+ZGxVajNmQ1ciOw==','2018-11-27 09:56:31','2018-11-27 09:56:31','b0b264b0-b886-4ea9-8dc5-7b405d488870'),(1053,1,'71f48c9b253d44b89558b3fbd103948d1fc7bfb4czozMjoiVjFSZHJwUk41NVJWMXp2REpBVEl0Y3Z5dl84bWM2YkkiOw==','2018-11-27 10:04:51','2018-11-27 10:04:51','350971b1-4036-4c82-983e-89b3bb186007'),(1054,1,'2073774a9a4b8e3a639543bf2ab648e0faf2c6d1czozMjoiNmJlWVdqZU1wMW4yblExY1I0TTRzTktncVFEbFNlckgiOw==','2018-11-27 10:24:40','2018-11-27 10:26:38','add1f949-73a5-4ea5-9a3e-0dfd50d11aeb'),(1056,143,'b3d4d723d80f526d2358355d45508c2db943b77eczozMjoiX2F5a3BuZ0hFRU5Sb1p0aUJ6Nzl4SlIxMEV4M0lJazMiOw==','2018-11-27 10:55:51','2018-11-27 10:55:51','130bbfa2-7ce8-44f7-bb1d-53db4c00c3db'),(1057,143,'31f2f4b902f09b9a16a6cd26cd33ced4235a7d3fczozMjoiSDJmOHBtVHRUY2F1dUJUSng3NWRDMFNTaEdnfk5hMEMiOw==','2018-12-06 17:26:42','2018-12-06 17:26:42','ba73b323-4cd2-4a92-9243-06936840c437'),(1058,1423,'c10fc275d7c7a84e0cf63680c3b13a5119127718czozMjoidm52eFcyR2x4NkdieUoxUE1DY1lzNE9WeXFOQUNzazkiOw==','2018-12-07 08:28:32','2018-12-07 08:28:32','7d0ec89e-8fb6-4afb-98b1-ad04fa0e8c52'),(1064,1423,'a4280e5157df5820bc043365f2502fc4c261e743czozMjoiaDZYVXd0R0lDQXJVTW5ZdERFc3lpMTlfdkR4ejhYVGsiOw==','2018-12-07 12:01:09','2018-12-07 12:01:09','678a8551-a504-41e9-8dc1-374556ba83fc'),(1068,143,'8161d6e69eaf7c13b5116c1bf4ae705fa492da35czozMjoiNEthZE5WejVsV3FXb2VoUnFZSmhlbXdjZFR5Q2NTangiOw==','2018-12-07 13:37:57','2018-12-07 13:37:57','cec4bd04-a5e3-43f2-97ca-add78cc7806b'),(1071,1423,'34c1d945ba2ba0a6ba5bbc1f99b9f38a738e1b3cczozMjoia3d2ejJmS2JUMTZnSlp0eWZMaGlZek9wZ19tWWxwNEYiOw==','2018-12-10 09:49:21','2018-12-10 09:49:21','fef4f618-a094-4ab1-8f9a-0b79ad848374'),(1072,1423,'7629e832679d0f1df4dca98f3cb61c2cd8c75998czozMjoiV2F2SU4yY2dKdmtQMmRjTmtZMzZQX3d4a3lhelVrSzEiOw==','2018-12-10 15:15:27','2018-12-10 15:15:27','051a9e43-1952-4db6-a912-03ae6f41cbcd'),(1073,143,'f58ac579a027843ae87d600b042487e6ac7894e7czozMjoiMGJCVHFtV05lODZaQnlna05fcmU2Sk8xd2dvT1MxR1UiOw==','2018-12-10 18:27:06','2018-12-10 18:27:06','74df60e9-0371-4428-b544-a997bac5317c'),(1075,143,'abaf3a24b23d1247269057cff15db95cb161d468czozMjoiNVpfeDBFMmJyaXIzQk9COVZUcDk4NU1XSHZmYUpyTWQiOw==','2018-12-11 08:19:12','2018-12-11 08:19:12','aa88c7c5-bc06-4dcf-81ea-4a77be99092f'),(1077,1423,'eed5cf58d3e2c0f7898781f08a595359f49c8002czozMjoiS35zUnlKamYzb0lLNVdvWDdrOV85M1podGZIaDBXQzciOw==','2018-12-11 09:23:32','2018-12-11 09:23:32','773efb9f-6241-496e-b14a-b785f642da11'),(1082,1438,'901dc1033e5cdbcecb7cd6eaf8650f752d98a7b3czozMjoidzVXT3hPRH5+OFNwX1dBMW0zSFE2bVVkZzBoY0d5R00iOw==','2018-12-11 11:34:08','2018-12-11 11:34:08','d4f7aa3f-b43b-4237-9854-062677168f21'),(1083,1423,'bc3ccf2b8b69aa6d6a2029d5d679c7c4d73d6a88czozMjoickdDVkRydUx5YTFDOUl3dkJsR2NYNHVIdGxYY0ROVW0iOw==','2018-12-11 13:13:36','2018-12-11 13:13:36','741c9c4a-76ca-477d-8266-6349fd7bbdeb'),(1084,1423,'e5721823b089cf9ac6af5b1b27008318adc7c715czozMjoiaEQxMnhVTWY5YWFVT1RjdXpLeWpJSmEycXBnTXhPclciOw==','2018-12-11 15:05:27','2018-12-11 15:05:27','0b998e65-a153-436e-b2bd-49d3ac76cebb'),(1085,1,'b1d63ea2efd8ddc8504bb496521b3bbb1b99ae0cczozMjoiVkJqbEc1OUxEa3h+UkVmdFRwU05seHRLZX5nbGY4Mn4iOw==','2018-12-11 15:16:21','2018-12-11 15:16:21','4db31796-44cc-4d17-b3a7-c3392d52337f'),(1086,143,'9d0d85ecfa5dc2e4ae89d0bf67fe995de5c94103czozMjoiWjVtdXY3ZkhIcFhkTDBIVWdWTjZXWXZ6dDd1VVBNWVkiOw==','2018-12-11 23:27:04','2018-12-11 23:27:04','487decf2-0faa-4641-8a94-2fb1a122e0f2'),(1087,1423,'b227895895d5f9fda26f5fab24e1bf4defdeffb2czozMjoiZEd5VUx3THk1M1Q4YnFJa2V1VzM0UmYzYmVvNEt5S1AiOw==','2018-12-12 00:21:59','2018-12-12 00:21:59','1b9803ea-bd88-488a-b3ac-34b07fc07ff9'),(1089,143,'1779cd92ce3683dd2d71a989ed863688c065f9d7czozMjoibFF6ZmNlVU1ZSHVGT1B+SmRHRV9zTjh+ODJCeEtqQ08iOw==','2018-12-12 00:38:38','2018-12-12 00:38:38','ef247fd9-e494-484b-b865-b8de47f84771'),(1090,143,'2661c378a2ca5a851abd788ef1a27a605c95da06czozMjoiQ3hxcUhaMFNZdzhQODhhVm55YTRZVGhraW00dm5BN28iOw==','2018-12-12 03:01:06','2018-12-12 03:01:06','bea5f76f-064f-44b7-960e-4060981165d9'),(1091,143,'ce163740a8fd9cff79e793e8a73e8f0d32db0c11czozMjoibHdnYTJTMkNIblBTUn5oNTJtMjZaQndFRm5DUHZScFkiOw==','2018-12-12 05:35:39','2018-12-12 05:35:39','00dafc7c-db4d-4435-8dcd-331ec6143c99'),(1093,1438,'78e47ceb30be76d0eb07f48a004bb4958c698435czozMjoiUnIzWDZBV2JiMml1dUFwVm1wM1JZV3ptVTBmekxsYWMiOw==','2018-12-12 14:18:29','2018-12-12 14:18:29','9e14f5a2-bce4-4efe-9885-78b13967c806'),(1094,1438,'963dfc9176f57771442556ce443d9628f4dc1e71czozMjoid0xzdFVRWDBNR2pqbVdvTW0yVlJmVm05bDFWNTFtNGkiOw==','2018-12-12 15:42:14','2018-12-12 15:42:14','9e72b5d3-34b6-4b16-b5b6-e442f6e65f3d'),(1095,1438,'90be0c7d3b6f77b5d11eaa45da87ce8917d8e49eczozMjoiOU5pMGFzNGkzMThkNUtyRjdZfjRFWWVlN1EwdFduVGoiOw==','2018-12-13 08:45:55','2018-12-13 08:45:55','51042c16-20a0-4300-aa88-cf602447023c'),(1102,1423,'1702750a7e4a925d2ec54470d145254a91840d10czozMjoiOTc2dVM2NjNudmNyc3VZc1o2fk13SkZtT2xQZU9VX0oiOw==','2018-12-13 11:06:47','2018-12-13 11:06:47','4ccaa352-0a3b-4a48-819c-c75dfc201080'),(1106,1423,'e0efd4e5fdaafb512b7241b7fe6a51d2d1b04c24czozMjoiQ3VSRVk0em8xeTRkZk0xdlNQYWNUd2N3WnB4em1ma18iOw==','2018-12-13 12:00:26','2018-12-13 12:00:26','34c45dd0-2c3a-4a0e-a596-a9f5e1c4ee9d'),(1107,1438,'7303788fb6c8eb9a84962d50bfdb27bd5fa795c4czozMjoiOFF6WGh3T1dhUWVnbngzUGdKT21ZY0xCajljOXVHUlEiOw==','2018-12-13 13:37:27','2018-12-13 13:37:27','9cda379e-bbbc-4e42-b844-6336918a4ad3'),(1110,1423,'b9615b6a0c4b64547bab054256aee0ed6fe21bdfczozMjoiS2ZWaVVFUTlyTF9wY0k3dFpKZEVRR29BMWZNQUV3X0siOw==','2018-12-13 14:28:34','2018-12-13 14:28:34','d9635ee3-1159-4524-bbcb-a3321aff96c2'),(1113,1423,'eeff45c9ec752f9c1c60fa2db0b986f510e067f8czozMjoiUjJvcW9iNXVqRmQ4dGVaejNLWXBINHFtWnk0bUtEQ3AiOw==','2018-12-13 16:29:01','2018-12-13 16:29:01','ce964da3-6ad7-4acd-99a8-c33f4770369c'),(1115,1,'c43a8599e707703c9293c448c839be819b79dc30czozMjoiaEZwQWFEbTh6OWEwNjFSZmtDYTFZRXF1TU50dVM4WEMiOw==','2018-12-14 11:10:43','2018-12-14 11:10:43','adacff9b-9f58-44d7-8ff9-cf00c817a8a1'),(1116,143,'ba943444e952e4d23232c3caa1d981bd989ca16fczozMjoiclM0dE5MYkk2ZGxTT190U2phYkVFcXpUQkFGMmdXT1QiOw==','2018-12-14 17:50:42','2018-12-14 17:50:42','bccb356e-aa6b-4a96-91eb-77dbeffc9fd1'),(1117,143,'e48017600597c27167797a66d1f6035f922f0876czozMjoiWFRIb2tocnp5WVNnXzBCek1qb0FoTHlaV2xUfjNEakciOw==','2018-12-14 18:10:40','2018-12-14 18:10:40','2de8d1ea-06ae-4c77-ac2d-67e45969055f');
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
INSERT INTO `craft_sproutreports_reportgroups` VALUES (1,'Sprout Reports','2018-05-02 09:18:48','2018-05-02 09:18:48','169c0a10-a6bd-45e3-ac7d-553e5492c11d'),(2,'User CPD','2018-05-10 13:32:28','2018-05-10 13:32:28','dc263d07-6b65-4861-a1ed-7f474e304909');
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
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_structureelements`
--

LOCK TABLES `craft_structureelements` WRITE;
/*!40000 ALTER TABLE `craft_structureelements` DISABLE KEYS */;
INSERT INTO `craft_structureelements` VALUES (1,1,NULL,1,1,20,0,'2017-10-23 14:49:48','2017-10-23 14:49:48','e8485dc4-058d-40ed-b468-e62f2e0dcc1a'),(47,1,1478,1,2,3,1,'2018-11-07 11:03:08','2018-11-07 11:03:08','dbd437a6-05b0-4b47-85eb-0af550e1ec04'),(48,2,NULL,48,1,12,0,'2018-11-26 17:52:08','2018-11-26 17:52:08','b80f6bf7-f337-4fad-8d8d-9cdc8409b22d'),(49,2,1624,48,6,7,1,'2018-11-26 17:52:08','2018-11-26 17:52:08','13df1777-8052-4d98-a322-6c22a6b4a820'),(50,2,1625,48,4,5,1,'2018-11-26 17:52:16','2018-11-26 17:52:16','c9a6664d-12e9-44eb-a1d1-0d4bcb54b613'),(51,1,1650,1,4,5,1,'2018-12-07 10:37:23','2018-12-07 10:37:23','d6e07a17-c179-475d-8554-7503d36b3f0d'),(52,2,1651,48,8,9,1,'2018-12-07 10:42:35','2018-12-07 10:42:35','60ca0ce2-62bb-46e3-be1a-8ad0bf197f1d'),(53,2,1667,48,2,3,1,'2018-12-07 11:10:07','2018-12-07 11:10:07','874f0989-1ad4-4859-9bf7-ca08c56050f7'),(61,1,1756,1,6,7,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','6643e03e-c507-47b4-8467-ac5f42e6d538'),(62,1,1757,1,8,9,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','e9d97074-c04d-44aa-9a64-d0b0aea1f3b3'),(63,1,1758,1,10,11,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','ebf8c09d-7cb9-4b52-a898-5ca816ffe7c6'),(64,1,1759,1,12,13,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','5f350f08-d386-496c-a569-490539762ff6'),(65,1,1760,1,14,15,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','769726da-4e03-4ca7-b60b-c972c461d9e2'),(66,1,1761,1,16,17,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','42ccf7f4-80cb-4fea-9c9c-64875794ad09'),(67,1,1762,1,18,19,1,'2018-12-07 13:43:58','2018-12-07 13:43:58','8a63f84f-2d13-4a48-a2c9-6148b37baa43'),(68,2,2062,48,10,11,1,'2018-12-11 10:23:52','2018-12-11 10:23:52','aaeb6913-a618-4189-89fa-03d60796c462');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_structures`
--

LOCK TABLES `craft_structures` WRITE;
/*!40000 ALTER TABLE `craft_structures` DISABLE KEYS */;
INSERT INTO `craft_structures` VALUES (1,1,'2017-10-23 14:49:32','2018-10-04 15:34:00','cddeab59-d20c-4b6e-80e2-3912183a9657'),(2,NULL,'2018-11-26 17:51:54','2018-12-11 07:56:08','bc778126-df63-4aaa-98b1-a3cbd027192f');
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
INSERT INTO `craft_supertableblocks` VALUES (1630,1625,167,2,5,NULL,'2018-12-06 17:49:33','2018-12-11 09:36:32','b077f941-d03f-4b5c-9dfd-62a477c84a04'),(1631,1625,167,2,6,NULL,'2018-12-06 17:49:33','2018-12-11 09:36:32','5ec04ead-b086-453e-8668-51f4a086ce9e'),(1632,1625,167,2,3,NULL,'2018-12-06 17:49:33','2018-12-11 09:36:32','8efc9b42-3ddc-4bab-b5a8-2aeed63da5d9'),(1633,1625,167,2,1,NULL,'2018-12-06 17:49:33','2018-12-11 09:36:32','636d0182-ba4f-4585-a6c4-ac8bac29f327'),(1634,1625,167,2,2,NULL,'2018-12-06 17:49:33','2018-12-11 09:36:32','2c31dfd3-e93d-4455-b71a-8f3aca01171d'),(1635,1625,167,2,4,NULL,'2018-12-06 17:49:33','2018-12-11 09:36:32','080b9468-aa72-4914-a1aa-336c58e498b7'),(1637,1625,167,2,7,NULL,'2018-12-06 17:49:33','2018-12-11 09:36:32','54fa8ba4-a76b-4488-9059-96029e0bc685'),(1675,1667,167,2,1,NULL,'2018-12-07 11:22:16','2018-12-11 07:57:06','3d919a58-93f6-4b29-9d43-5a1e990b264d'),(1676,1667,167,2,2,NULL,'2018-12-07 11:22:16','2018-12-11 07:57:06','2a566d94-b604-47db-8820-81ca4889385e'),(1677,1667,167,2,3,NULL,'2018-12-07 11:22:16','2018-12-11 07:57:06','3ce73800-18db-4c56-aeb2-0ca4aed6434f'),(1940,1624,167,2,1,NULL,'2018-12-10 09:33:53','2018-12-11 07:57:26','5a47ab15-3104-4081-8da6-97a600db6f26'),(1941,1624,167,2,2,NULL,'2018-12-10 09:33:53','2018-12-11 07:57:26','bd95b8b9-5f2d-4351-a9be-1f62af842646'),(1942,1624,167,2,3,NULL,'2018-12-10 09:33:53','2018-12-11 07:57:26','35ec09ea-856f-4764-a86d-4dff204baa0a'),(1943,1624,167,2,4,NULL,'2018-12-10 09:33:53','2018-12-11 07:57:26','0aafc1d6-b1b3-444b-83e0-6684b4d14c07'),(1944,1624,167,2,5,NULL,'2018-12-10 09:33:53','2018-12-11 07:57:26','f8cc4f6d-d0ef-454a-8c7c-c2945dc0eceb'),(2063,2062,167,2,1,NULL,'2018-12-11 10:23:52','2018-12-13 10:19:35','a3a74428-b54e-456a-bf94-847a5b355af3'),(2064,2062,167,2,2,NULL,'2018-12-11 10:23:52','2018-12-13 10:19:35','59f78ba6-faee-4c45-9c9f-49b235c6ba79'),(2065,2062,167,2,3,NULL,'2018-12-11 10:23:52','2018-12-13 10:19:35','37c5f158-9e5e-412a-be59-968a773952a4'),(2066,2062,167,2,4,NULL,'2018-12-11 10:23:52','2018-12-13 10:19:35','ad9c78e3-6a10-4ca2-80db-d8e1e5810d81'),(2067,2062,167,2,5,NULL,'2018-12-11 10:23:52','2018-12-13 10:19:35','f59850b6-e2f0-4507-8301-2c3d0b1ed5a2'),(2342,2062,167,2,6,NULL,'2018-12-13 09:01:03','2018-12-13 10:19:35','917ced54-df50-4c31-9cad-2b1e04c0fb14'),(2343,2062,167,2,7,NULL,'2018-12-13 09:01:03','2018-12-13 10:19:35','ea73b699-716f-4d19-aec5-98f20f9a7b25');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_supertableblocktypes`
--

LOCK TABLES `craft_supertableblocktypes` WRITE;
/*!40000 ALTER TABLE `craft_supertableblocktypes` DISABLE KEYS */;
INSERT INTO `craft_supertableblocktypes` VALUES (1,148,221,'2018-11-27 16:59:46','2018-11-27 16:59:46','3c5cbb51-70a3-4a2d-a11e-113ad119bdcb'),(2,167,250,'2018-12-06 17:37:16','2018-12-06 17:37:16','922f36f2-d6ec-4530-be00-d8784974eb73');
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
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_supertablecontent_columnlayout_elementId_locale_unq_idx` (`elementId`,`locale`),
  KEY `craft_supertablecontent_columnlayout_locale_fk` (`locale`),
  CONSTRAINT `craft_supertablecontent_columnlayout_elementId_fk` FOREIGN KEY (`elementId`) REFERENCES `craft_elements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `craft_supertablecontent_columnlayout_locale_fk` FOREIGN KEY (`locale`) REFERENCES `craft_locales` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_supertablecontent_columnlayout`
--

LOCK TABLES `craft_supertablecontent_columnlayout` WRITE;
/*!40000 ALTER TABLE `craft_supertablecontent_columnlayout` DISABLE KEYS */;
INSERT INTO `craft_supertablecontent_columnlayout` VALUES (3,1630,'en_gb','resultStatus','','2018-12-06 17:49:33','2018-12-11 09:36:32','0b7a6b58-148b-4986-8a4e-341d842cb890'),(4,1631,'en_gb','resultEndorsedDate','','2018-12-06 17:49:33','2018-12-11 09:36:32','f1ffffc8-2757-4a6f-b48e-844d44e42c22'),(5,1632,'en_gb','resultExpiryDate','','2018-12-06 17:49:33','2018-12-11 09:36:32','450e4ff4-23e2-4ffc-b1c0-7f4837fe8286'),(6,1633,'en_gb','resultStartDate','','2018-12-06 17:49:33','2018-12-11 09:36:32','33624f9f-cc7b-4a6e-82b1-287a856786f4'),(7,1634,'en_gb','resultFinishDate','','2018-12-06 17:49:33','2018-12-11 09:36:32','c7437ffe-8d70-4a0e-876f-85c9444817d2'),(8,1635,'en_gb','resultLocation','','2018-12-06 17:49:33','2018-12-11 09:36:32','ea533a3c-bc71-49e6-b46d-08bb44cb0a95'),(10,1637,'en_gb','resultEvidence','','2018-12-06 17:49:33','2018-12-11 09:36:32','375b67bd-20ef-4dfa-a30a-4acda0a904db'),(13,1675,'en_gb','resultStatus','','2018-12-07 11:22:16','2018-12-11 07:57:06','ef9b715c-21b3-4386-94a0-cc7b8ec2eaac'),(14,1676,'en_gb','resultEndorsedDate','','2018-12-07 11:22:16','2018-12-11 07:57:06','4159a165-3490-48d5-9643-31644fbf2e2c'),(15,1677,'en_gb','resultEvidence','','2018-12-07 11:22:16','2018-12-11 07:57:06','ce0f645c-0973-4e43-8ac2-40b1ed4a6f32'),(16,1940,'en_gb','resultStartDate','','2018-12-10 09:33:53','2018-12-11 07:57:26','ef978321-0e1c-4cfd-9308-cb4fe42f8eca'),(17,1941,'en_gb','resultFinishDate','','2018-12-10 09:33:53','2018-12-11 07:57:26','7e984545-9898-45eb-b839-80fad8d1d55d'),(18,1942,'en_gb','resultLocation','','2018-12-10 09:33:53','2018-12-11 07:57:26','68ebe02d-13ef-44b6-93de-5f02a1acc925'),(19,1943,'en_gb','resultHours','Points','2018-12-10 09:33:53','2018-12-11 07:57:26','d587d367-1e71-41b3-8217-b77bf0b4d829'),(20,1944,'en_gb','resultEvidence','','2018-12-10 09:33:53','2018-12-11 07:57:26','6df1b70f-beeb-48d2-9092-0a227dcce39e'),(21,2063,'en_gb','resultStartDate','','2018-12-11 10:23:52','2018-12-13 10:19:35','0de1d043-1755-4dce-9c1e-9bae43f05586'),(22,2064,'en_gb','resultFinishDate','','2018-12-11 10:23:52','2018-12-13 10:19:35','613aef7e-fa31-4d40-934e-73e80a71d084'),(23,2065,'en_gb','resultExpiryDate','','2018-12-11 10:23:52','2018-12-13 10:19:35','be97987c-3085-4eb2-a7e8-51b9190717d8'),(24,2066,'en_gb','resultLocation','','2018-12-11 10:23:52','2018-12-13 10:19:35','9e974088-29b0-416b-953d-ed690ae45ef6'),(25,2067,'en_gb','resultEvidence','','2018-12-11 10:23:52','2018-12-13 10:19:35','0f1c1b32-c760-46b4-b4ae-9e26172ab779'),(26,2342,'en_gb','resultStatus','','2018-12-13 09:01:03','2018-12-13 10:19:35','0a018563-a776-4454-b7cd-7cb6006d4014'),(27,2343,'en_gb','resultEndorsedDate','','2018-12-13 09:01:03','2018-12-13 10:19:35','400da6cc-0a99-435d-aec1-a6adf3a7dca5');
/*!40000 ALTER TABLE `craft_supertablecontent_columnlayout` ENABLE KEYS */;
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
INSERT INTO `craft_systemsettings` VALUES (1,'email','{\"template\":\"\",\"protocol\":\"php\",\"emailAddress\":\"jason@thisistraffic.co.uk\",\"senderName\":\"Lantra CPD\"}','2017-10-23 13:26:43','2018-09-27 10:04:44','674fcaa0-de04-4e9a-8f71-d4d9162bf84c'),(2,'users','{\"requireEmailVerification\":0,\"allowPublicRegistration\":1,\"defaultGroup\":\"5\"}','2018-04-29 12:56:30','2018-05-31 12:57:11','a4832c83-8bf5-4f96-a578-d926632416c5');
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
) ENGINE=InnoDB AUTO_INCREMENT=324 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
INSERT INTO `craft_usergroups` VALUES (1,'Scheme Managers','schemeManagers','2017-10-23 13:50:22','2018-08-24 18:45:22','2a75e796-1574-407e-8869-545d14d6d126'),(2,'Company Managers','companyManagers','2017-10-23 13:50:37','2018-12-12 00:42:30','0ff8d9a2-50f8-473e-93c9-5eb5d41750f6'),(3,'Team Managers','teamManagers','2017-10-23 13:50:51','2018-08-24 18:45:49','7765376d-5d95-4bb2-ad49-e9dbcd7acf4a'),(4,'Users','users','2017-10-23 15:06:23','2018-04-27 19:33:37','ab9197b3-7032-4015-ba4e-4939118952dd'),(5,'Individuals','individuals','2018-05-31 12:56:44','2018-05-31 12:56:44','32ed3edf-a621-4c98-8c9f-785e45ed329f');
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
) ENGINE=InnoDB AUTO_INCREMENT=998 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_usergroups_users`
--

LOCK TABLES `craft_usergroups_users` WRITE;
/*!40000 ALTER TABLE `craft_usergroups_users` DISABLE KEYS */;
INSERT INTO `craft_usergroups_users` VALUES (730,4,1626,'2018-12-06 17:47:14','2018-12-06 17:47:14','d3a9c16d-aae8-484b-a565-af27001a2cfe');
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
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_userpermissions`
--

LOCK TABLES `craft_userpermissions` WRITE;
/*!40000 ALTER TABLE `craft_userpermissions` DISABLE KEYS */;
INSERT INTO `craft_userpermissions` VALUES (1,'createentries:8','2017-10-24 11:28:19','2017-10-24 11:28:19','522df09d-ffe1-4b4e-a917-a808213b500b'),(2,'editentries:8','2017-10-24 11:28:19','2017-10-24 11:28:19','922429ee-823f-4b3c-87f5-f22769a0e2dc'),(3,'accesscp','2017-10-24 11:37:26','2017-10-24 11:37:26','834c5901-4348-49a6-92c8-7d03e4d09e0e'),(4,'publishentries:8','2017-10-24 11:52:27','2017-10-24 11:52:27','6d7a140d-af76-4c36-82a4-94277c797b43'),(5,'deleteentries:8','2017-10-24 11:52:27','2017-10-24 11:52:27','c7bd2e4b-8489-4a54-8c17-2d1d4581fe5a'),(6,'publishpeerentries:8','2017-10-24 11:52:27','2017-10-24 11:52:27','25d71b7d-36c6-44a7-a0cf-67f71ac5d7f1'),(7,'deletepeerentries:8','2017-10-24 11:52:27','2017-10-24 11:52:27','6ab161dc-90ec-445b-8c8a-84bbbfafeeee'),(8,'editpeerentries:8','2017-10-24 11:52:27','2017-10-24 11:52:27','83cd63ec-e65a-4566-9be0-b8d05e5c59f8'),(9,'publishpeerentrydrafts:8','2017-10-24 11:52:27','2017-10-24 11:52:27','bdacb3a4-92a4-4719-be56-2ec0a9d59b4b'),(10,'deletepeerentrydrafts:8','2017-10-24 11:52:27','2017-10-24 11:52:27','6f98a9b1-35c7-4b1c-a4b7-3b37e860f170'),(11,'editpeerentrydrafts:8','2017-10-24 11:52:27','2017-10-24 11:52:27','5a6f0d3f-3705-45a1-8777-b85921cdf526'),(12,'createentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','0311c112-fdfa-4eff-816f-220ab95f5348'),(13,'publishentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','0d6a8e3c-5e66-4dd1-ab6d-bbb916bc6415'),(14,'deleteentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','0036eea5-60d3-4f02-9e42-c14ff2f6120b'),(15,'publishpeerentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','a6a17622-3c67-488f-be63-d58cc5330cbd'),(16,'deletepeerentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','762f18ae-453e-4ebc-b077-8512945c77d8'),(17,'editpeerentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','66a6bb0f-b3a4-4995-81a0-d50197492bf7'),(18,'publishpeerentrydrafts:12','2018-04-13 10:39:01','2018-04-13 10:39:01','83ab6ed6-61d5-4f68-ae2c-6daa9156e2e4'),(19,'deletepeerentrydrafts:12','2018-04-13 10:39:01','2018-04-13 10:39:01','e7a78973-0d7d-445c-9820-477f816c0347'),(20,'editpeerentrydrafts:12','2018-04-13 10:39:01','2018-04-13 10:39:01','307cee2c-b08b-4979-b4e8-2f203c59b76b'),(21,'editentries:12','2018-04-13 10:39:01','2018-04-13 10:39:01','247b62ca-9948-4574-ba2b-3eb6e11c6ca4'),(22,'createentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','854e48c4-4977-4dba-a800-fd7763e9b6a2'),(23,'publishentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','d29c110f-0a3c-44ae-9fb9-b66a4f4d45d9'),(24,'deleteentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','b498e8f3-8e49-421b-8ede-5b548cf2b985'),(25,'publishpeerentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','d7bb2da3-3d75-4603-a4f7-fffa799375ae'),(26,'deletepeerentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','573b7634-54bb-419a-8971-a9616a13e730'),(27,'editpeerentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','3b230546-f668-47fe-858c-4533c6ab9dd4'),(28,'publishpeerentrydrafts:5','2018-04-27 19:32:40','2018-04-27 19:32:40','0a2d58d1-1845-44b0-abd0-11d83df37660'),(29,'deletepeerentrydrafts:5','2018-04-27 19:32:40','2018-04-27 19:32:40','dee1160b-dcb3-4585-ac1d-0a524e378a67'),(30,'editpeerentrydrafts:5','2018-04-27 19:32:40','2018-04-27 19:32:40','77598d92-2e84-4612-b91f-2c43f4bede78'),(31,'editentries:5','2018-04-27 19:32:40','2018-04-27 19:32:40','bed835fd-1f06-4f1a-8ca1-e204d4a2f93a'),(32,'uploadtoassetsource:1','2018-04-27 19:32:40','2018-04-27 19:32:40','3bdffd00-5d15-4292-b25c-0c9e6edda4e4'),(33,'createsubfoldersinassetsource:1','2018-04-27 19:32:40','2018-04-27 19:32:40','5f70d3b5-7b4a-40d3-9e66-8039cd0aef25'),(34,'removefromassetsource:1','2018-04-27 19:32:40','2018-04-27 19:32:40','2989cbec-1dfb-4366-9967-ecda31ea47f9'),(35,'viewassetsource:1','2018-04-27 19:32:40','2018-04-27 19:32:40','f9822513-0272-4a46-8411-6063f68d961e'),(36,'createentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','60c3b22b-7a2e-493f-9a40-39d291d7506c'),(37,'publishentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','989daebc-2d45-44db-8cdf-039a3f330b21'),(38,'deleteentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','c246bc5d-ffba-42f6-9c8f-d97ed39c27ee'),(39,'publishpeerentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','527eae7b-9315-47ba-b1f2-a1f086ff81ad'),(40,'deletepeerentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','57fedc51-6675-4fcd-aa46-970532384936'),(41,'editpeerentries:10','2018-04-27 19:33:37','2018-04-27 19:33:37','fc20e7d3-a0a6-400b-9f6c-71c8875e5d1e'),(42,'publishpeerentrydrafts:10','2018-04-27 19:33:38','2018-04-27 19:33:38','1b376249-680b-4b9b-be9f-d4d5e1dcea90'),(43,'deletepeerentrydrafts:10','2018-04-27 19:33:38','2018-04-27 19:33:38','3ad1add9-ca13-4266-865e-c8b9e046697b'),(44,'editpeerentrydrafts:10','2018-04-27 19:33:38','2018-04-27 19:33:38','90282832-0a4f-4f50-b12b-18b158a47100'),(45,'editentries:10','2018-04-27 19:33:38','2018-04-27 19:33:38','263d66a6-4aa9-4c2b-9b31-09197fb6b4b1'),(46,'registerusers','2018-04-27 19:33:50','2018-04-27 19:33:50','6d3ee0bf-ff81-4739-a076-6c4974d0d7d2'),(47,'assignuserpermissions','2018-04-27 19:33:50','2018-04-27 19:33:50','11e09aed-c382-4c72-876a-42101762c291'),(48,'changeuseremails','2018-04-27 19:33:50','2018-04-27 19:33:50','ba69c6bf-9860-49c8-8b7e-c05a93010f77'),(49,'administrateusers','2018-04-27 19:33:50','2018-04-27 19:33:50','dc561fe6-c662-452a-9ec6-72d18a58a928'),(50,'editusers','2018-04-27 19:33:50','2018-04-27 19:33:50','0bad6d7a-094f-4843-9c6f-637c2bda8da9'),(51,'deleteusers','2018-04-27 19:33:50','2018-04-27 19:33:50','fa3cd546-53be-4854-97f7-b0bbd70b5336'),(52,'createentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','a40366b3-5930-4f8d-83ed-08bac76d5fe9'),(53,'publishentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','c2a2d6d3-d91f-4c80-96a5-d396e63b7a4f'),(54,'deleteentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','433464cc-1a3d-42d2-abed-7dd1a8e5f388'),(55,'publishpeerentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','0c262595-46f6-418d-8aa2-ea7911dfbeab'),(56,'deletepeerentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','04221546-711f-4012-9b58-ec33d3487849'),(57,'editpeerentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','1a3821a5-b599-47b7-9971-dd2d48cc989e'),(58,'publishpeerentrydrafts:3','2018-04-27 19:34:15','2018-04-27 19:34:15','ca6e4f70-e68f-4368-9ee1-ad4612bfb83b'),(59,'deletepeerentrydrafts:3','2018-04-27 19:34:15','2018-04-27 19:34:15','7a33dc4c-a30b-4d30-973c-e7bd37d4e413'),(60,'editpeerentrydrafts:3','2018-04-27 19:34:15','2018-04-27 19:34:15','f8c82046-49ad-4820-beee-09ae1a8d2ede'),(61,'editentries:3','2018-04-27 19:34:15','2018-04-27 19:34:15','f43e33a8-de9c-4ed3-8018-37a13d9a660b'),(62,'editcategories:1','2018-04-27 19:34:15','2018-04-27 19:34:15','94cd895d-961b-42f3-9099-b9f0e96bfc9a'),(63,'assignusergroups','2018-04-29 12:43:55','2018-04-29 12:43:55','a51aaaab-d271-4392-b891-0e2b2f5a2aab'),(64,'assignusergroup:2','2018-04-29 12:43:55','2018-04-29 12:43:55','b0a03411-d7f9-4aba-980b-ac5dc2fbf645'),(65,'assignusergroup:1','2018-04-29 12:43:55','2018-04-29 12:43:55','877aae1a-b8b7-427c-9081-a5decac8b153'),(66,'assignusergroup:3','2018-04-29 12:43:55','2018-04-29 12:43:55','7789273f-dd44-4a42-a230-da794d128932'),(67,'assignusergroup:4','2018-04-29 12:43:55','2018-04-29 12:43:55','4c0beabc-0b32-4d80-9569-75216c2c3fa2'),(68,'createentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','40f24ed8-bb27-4742-840a-5b23a2b1a94c'),(69,'publishentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','8ac2d3b0-904d-42df-a219-b78b152993cf'),(70,'deleteentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','3c08266e-a971-42b0-b774-e30a52e74e88'),(71,'publishpeerentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','55ddcc88-0c2f-4e56-9835-210ef3a88819'),(72,'deletepeerentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','7dc956e2-2d02-4097-aab9-3570082d9148'),(73,'editpeerentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','eb9fed02-390d-4334-bd24-b5e0f302cf4f'),(74,'publishpeerentrydrafts:6','2018-04-29 13:45:38','2018-04-29 13:45:38','49bea8fe-143d-42a4-a81a-62ebd4d91c2c'),(75,'deletepeerentrydrafts:6','2018-04-29 13:45:38','2018-04-29 13:45:38','564c8348-4142-44b1-9a9d-1deea1c425f7'),(76,'editpeerentrydrafts:6','2018-04-29 13:45:38','2018-04-29 13:45:38','7d2193dc-0864-48f9-94f8-c64ebd0c9793'),(77,'editentries:6','2018-04-29 13:45:38','2018-04-29 13:45:38','71d308bb-22be-4cfa-97f2-130f4e584464'),(78,'createentries:13','2018-08-24 18:45:22','2018-08-24 18:45:22','86f7f546-4e25-4929-a192-fab4e55d9f0f'),(79,'publishentries:13','2018-08-24 18:45:22','2018-08-24 18:45:22','104e91b6-9222-4d0f-a75e-dee804d413f9'),(80,'deleteentries:13','2018-08-24 18:45:22','2018-08-24 18:45:22','5b555e50-5083-489f-a8e3-ab96b8e02600'),(81,'publishpeerentries:13','2018-08-24 18:45:23','2018-08-24 18:45:23','8b0bc0fc-b5b0-41db-8acf-73cae312df81'),(82,'deletepeerentries:13','2018-08-24 18:45:23','2018-08-24 18:45:23','654aa191-916a-498a-b1a5-75a889a5c02a'),(83,'editpeerentries:13','2018-08-24 18:45:23','2018-08-24 18:45:23','103f1d46-85c3-4a9f-9aa9-7d4c6b164c2d'),(84,'publishpeerentrydrafts:13','2018-08-24 18:45:23','2018-08-24 18:45:23','e0217d93-3061-4181-bd68-3f7e48f98d52'),(85,'deletepeerentrydrafts:13','2018-08-24 18:45:23','2018-08-24 18:45:23','e406b1d9-3794-419b-bb11-e0e8b4010ac7'),(86,'editpeerentrydrafts:13','2018-08-24 18:45:23','2018-08-24 18:45:23','edbedef1-f9b4-4a8c-9b09-fe7c48334a04'),(87,'editentries:13','2018-08-24 18:45:23','2018-08-24 18:45:23','7cf81fa9-8baa-4456-8b34-0b7b7c94df63');
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
) ENGINE=InnoDB AUTO_INCREMENT=430 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_userpermissions_usergroups`
--

LOCK TABLES `craft_userpermissions_usergroups` WRITE;
/*!40000 ALTER TABLE `craft_userpermissions_usergroups` DISABLE KEYS */;
INSERT INTO `craft_userpermissions_usergroups` VALUES (102,12,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','5178225d-8544-4760-8488-1f9a516ba8b8'),(103,13,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','a7ec1f66-aff4-4327-9505-168cfa0d3b76'),(104,14,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','7d9df0df-3934-4909-b503-9249617e8ca6'),(105,15,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','d0248881-339b-4b7f-bc55-73ffc191afab'),(106,16,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','a5534cb0-9637-4548-b115-0c5f1b687c96'),(107,17,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','da615584-5665-4a4e-8202-f8491d256eec'),(108,18,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','eac3a6d3-0aea-4fc9-9363-3c446b501a7e'),(109,19,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','8326833d-4595-4041-9948-58f52b2cab18'),(110,20,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','f7274415-f471-401e-92f2-72b9adf2bd55'),(111,21,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','581d048e-e4df-4cbf-a982-0f39aaba8755'),(112,36,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','dd155d2b-2dd7-419c-a1e5-962e60942a6a'),(113,37,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','a2a26e6d-e4df-4525-b633-999e308e89e3'),(114,38,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','39d0a838-1cd2-4f4e-845d-1a0e1f8df6fe'),(115,39,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','91f6a328-35a3-443f-b457-82122ebd28db'),(116,40,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','1f25122f-43c4-46ca-bc08-5f4bbd42c22c'),(117,41,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','dcc8fd1b-0cb8-453c-bee7-5fb3fd65e876'),(118,42,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','5935cab4-676a-4eb3-b5dd-9d3c13552c12'),(119,43,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','550fb24c-a97f-4a47-892f-d2db8f58df07'),(120,44,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','ef8ae685-24bf-4154-999f-0d41ae805df6'),(121,45,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','e2ea20e9-0a34-4e37-b8b3-bbccd4153f1c'),(122,32,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','d6357049-b496-4d5b-a4ab-cd27256a176b'),(123,33,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','704e489b-0cd7-4f4e-80ba-85484a5e5c8f'),(124,34,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','2399dde2-5b8b-44b5-bd7e-c1cdebf0c39e'),(125,35,4,'2018-04-27 19:33:38','2018-04-27 19:33:38','af3f0c7e-7c4c-4acd-b0d6-8fa659bff482'),(273,46,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','b3bd33bd-8bdf-4a02-b18f-3ddd9874f1a3'),(274,48,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','31315836-5dfe-42ac-8388-90280b6e5998'),(275,49,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','164989b1-e7aa-41b0-b895-03a289262a2e'),(276,47,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','b27e4480-f42c-4407-89f5-c1efea2f79f4'),(277,64,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','aa9792bf-cfbf-4246-b5c0-55a08854cdd1'),(278,65,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','63b01c23-27c9-43c6-b466-fa38c53249a9'),(279,66,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','80cc4b68-8279-4cf0-b0a0-319a41c83fd9'),(280,67,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','118e2cc6-2869-4176-8344-6b7eedafbcd7'),(281,63,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','dd40f840-c1f2-4769-90d4-c453c44d627e'),(282,50,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','95274512-2fef-4b3e-aa57-d47bb553953c'),(283,51,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','c17caafb-9912-41a9-afa1-f59f6f006a72'),(284,52,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','c298866a-f56f-415c-ab98-69f92a204c91'),(285,53,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','46ed1c86-4da0-4782-9000-dac2b33c29db'),(286,54,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','1ca55de5-743a-4dfb-8f29-57ecd8b4fced'),(287,55,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','58daa306-f73d-4f11-a420-1e5ffb95514d'),(288,56,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','b971d299-28e7-49d9-a575-4ad04b7bed7b'),(289,57,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','3e055815-5353-4ac2-a211-2444b2fec879'),(290,58,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','cb90f982-d1d0-4538-ba06-a34eaf4342f1'),(291,59,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','4c082107-9119-4f38-a8bb-8d0f3d8f8707'),(292,60,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','a775547a-c7f0-47b1-b4f9-497f3162e86f'),(293,61,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','0d7ba230-6daf-45c2-809e-93ef0b2e20da'),(294,68,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','9b67829c-0517-4d80-8a5a-54aab9f442cf'),(295,69,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','20f79160-1b58-4718-87e6-1eb016d3a47b'),(296,70,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','a8f85450-84ab-49be-a8b4-a98c8364b928'),(297,71,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','71f910c2-35c1-44be-946d-89887831badc'),(298,72,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','9d9a8f20-8e46-4641-a668-bedead068fff'),(299,73,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','8f9ec83f-0caa-4d15-b6fe-10b1ec813071'),(300,74,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','d787d3a6-8f1b-46d9-80f9-341dde999532'),(301,75,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','0379d6b6-78b3-4656-8296-d41a16f0bbcb'),(302,76,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','2d8be319-f42c-4e7e-9faa-a45624af2a09'),(303,77,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','99253398-276f-4bc6-b17e-9457ffeca9fb'),(304,78,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','5e7ce46e-e36c-4268-b49f-21ea3ca5f8da'),(305,79,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','c7b92ba0-15de-4809-b1db-46af0042743b'),(306,80,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','a8088da0-bddf-4121-a84d-ec598ba76971'),(307,81,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','f38e8a96-3655-4e69-8b3e-1d7065138590'),(308,82,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','2871cdf8-9ef1-43b6-828c-2c54cef95449'),(309,83,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','45d91c1a-0475-4823-be09-7a4bf1a8e66e'),(310,84,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','a360ab75-d322-4c27-9c58-d9b1e04b6a43'),(311,85,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','9a6c77b4-e249-4b74-9c78-3aacb3dbca9c'),(312,86,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','ea0ea652-c89c-42ba-9f20-a40a85ee9ddc'),(313,87,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','d68b6a24-77d0-43db-b38b-7d865d20f2b0'),(314,22,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','4fa74d2e-5331-4146-a906-a21242908789'),(315,23,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','b23c9999-6c77-4264-b287-437e5edb7d52'),(316,24,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','13792520-d6c4-4750-a039-9f980b60268a'),(317,25,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','a2569580-b75d-4299-853b-4a25d76a4c32'),(318,26,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','b9f8ac2c-c078-433a-b405-e2c31f9762ce'),(319,27,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','6a9202a0-2687-4115-abd4-cd6726f6f755'),(320,28,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','1913b91f-6307-4d9e-8fee-fa12cb819138'),(321,29,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','f9943700-c724-40b9-87fc-e0c7a2a428fe'),(322,30,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','6902f5ab-2e0f-4978-8f77-aba8960fda4f'),(323,31,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','b3c15d81-f969-459f-ad5a-a5d80d0eed47'),(324,62,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','1991a312-3260-4d0c-85e4-a120a2ba2415'),(325,32,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','5a2c6232-721c-4007-babb-6cf3b8a65321'),(326,33,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','99653967-66f5-4a22-9983-d15f58b50345'),(327,34,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','ab50f976-4ea5-4b10-aa61-607d19667f9f'),(328,35,1,'2018-08-24 18:45:23','2018-08-24 18:45:23','b243ccec-cc35-4a65-a077-88368f14b83c'),(365,46,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','aec97587-0bdd-4b3e-9126-dace2802899d'),(366,48,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','92205f08-cc36-44e9-8fc8-f83226ceb4cd'),(367,49,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','ed97895c-6371-4569-9753-c047d39a3679'),(368,47,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','d8187876-8670-4666-80e8-ad81cf22580b'),(369,64,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','8061df49-809a-4676-bead-bc2e8920eddd'),(370,65,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','792c6cee-1e13-4052-a9b9-9111f8768d68'),(371,66,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','32ba3acd-bffc-4245-a870-076daf0af35f'),(372,67,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','9c2fb388-61c5-42f6-ac3f-3c5c24d9f47b'),(373,63,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','770da4bb-80a9-4970-8f89-0daaa00b3af6'),(374,50,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','355fca6c-2421-44fb-9097-57194d5267b4'),(375,51,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','d462fab5-af8b-451a-b2fd-cffdb796f617'),(376,78,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','0a4c5d32-c719-42ec-a4bc-9129240caca8'),(377,79,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','9d080382-4e62-4301-ba94-992c54082f15'),(378,80,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','e87a4688-ddc2-487b-bf8f-b77346281d6b'),(379,81,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','e2319023-2205-41c7-b11e-ed53698780c7'),(380,82,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','31368b80-38e2-49a5-912a-af863790fd70'),(381,83,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','457c8b41-97ee-4947-81d9-c5e3c9f71067'),(382,84,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','9f87fd21-e30c-4806-9493-68e71bdc3f68'),(383,85,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','561b5a43-d76a-471c-bad5-f3d811b6cd89'),(384,86,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','ad4d1407-57f9-487a-9045-e78d2a9cfec0'),(385,87,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','b55c5a1a-667f-404d-99cb-cceed4a167ff'),(386,62,3,'2018-08-24 18:45:49','2018-08-24 18:45:49','1a44c052-8614-46af-b2e5-24c0648c8f33'),(387,46,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','36c11321-e659-45eb-a1cf-7cb82bdb6f83'),(388,48,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','dc067383-5e15-46a6-b25e-e3753fdddc25'),(389,49,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','82e5ecf8-ba79-40c7-9c58-b224b917a40a'),(390,47,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','c4f00358-b0a6-4a63-88da-e713523887dc'),(391,64,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','cd05c9f8-1870-41dc-af3d-58add0407fae'),(392,65,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','2dbb5c1c-4af9-4a57-aa81-0a88ab5cfb18'),(393,66,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','23862555-c832-45ca-98eb-992c07bda0dc'),(394,67,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','a718c99c-0085-43a5-91f4-0f8d7570256e'),(395,63,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','eedb7b08-458c-4ebb-8c2b-6777f19c7f34'),(396,50,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','d0891e83-e2df-468e-a62b-5428aca859ec'),(397,51,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','ab91a1bb-0a6b-4487-8bc1-a40ff0ff59e0'),(398,78,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','e3423ca9-4ddd-4c7b-a473-73f13c3dbfe6'),(399,79,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','01fa1056-adb2-4768-b155-86d01b9f2a15'),(400,80,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','451a3591-ef1c-4f03-862b-dbd28284cb78'),(401,81,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','400de6a5-c015-4b93-8a72-bf8d90dcf9b8'),(402,82,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','63a6bd0a-73b0-4ca5-bb1c-f43c12587ae0'),(403,83,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','c5c8d50f-d361-4e95-80d5-ea0ca495a866'),(404,84,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','370f2ce4-5a8e-49ae-a0e9-3e8b0702826f'),(405,85,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','a7603046-6c6a-4dd2-8d23-09f52ba68f43'),(406,86,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','b0843422-8c0c-49d9-b9b2-05934d21fd94'),(407,87,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','40b625f4-bb07-4637-a088-50f2d7cc28b0'),(408,36,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','f49de788-de17-459d-b6af-8a22db2a719b'),(409,37,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','b0c5a20a-4dac-4f96-ae0b-416980ed1585'),(410,39,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','d68aae1e-3933-49e2-bf4c-03d785165c37'),(411,41,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','3647a7c1-7501-4220-ad9d-1b85561f0f7c'),(412,42,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','d7322110-6b17-4623-b81f-f657eb4e09df'),(413,44,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','357e8aef-d132-49a3-850d-907a96117ac3'),(414,45,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','a796c851-a6a7-4038-9e37-737abb0c7050'),(415,22,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','1a071514-427f-423a-b27d-21fe00ccf3ed'),(416,23,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','758796e6-ce3c-4af7-b89d-53e4f77e2a3b'),(417,24,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','a4a7acb3-53b4-4282-9ddb-c24fbb7955ea'),(418,25,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','284a32c0-1d0e-46f0-9f64-a99d38e2aff9'),(419,26,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','1612f14b-5dd9-4894-b81f-0dd45b590181'),(420,27,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','e7f74329-e95c-4b90-8814-3488f74ee2fd'),(421,28,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','e6e6d284-3833-4531-9d8d-3d66d97d6bed'),(422,29,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','51474557-c0f2-4f16-9b4a-381cdafac388'),(423,30,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','68267869-564d-44b8-a085-66273870c0a8'),(424,31,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','e4e90ccc-c268-49dd-959a-f8c91478aa2c'),(425,62,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','84a561f5-949a-4623-af09-ec3740910f3d'),(426,32,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','82731463-bf44-49f2-a9bd-b5dd64784dab'),(427,33,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','edc0527e-7c99-40fb-a5ec-25fa884d8f3e'),(428,34,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','b4c40d6e-7877-4b0c-87e3-aecb8359fead'),(429,35,2,'2018-12-12 00:42:30','2018-12-12 00:42:30','04291920-3d0f-4d9f-b96a-628417b4c69f');
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
INSERT INTO `craft_users` VALUES (1,'jason@thisistraffic.co.uk','index.png','Jason','Church','jason@thisistraffic.co.uk','$2y$13$YwZnbIjJ.OVBJOjJe2wUHOha6jy9sJU0P4aHXyq7/Sl15CzxcZiZa',NULL,1,1,0,0,0,0,0,'2018-12-14 11:10:43','80.3.48.54',NULL,NULL,'2018-09-11 08:22:54',NULL,NULL,NULL,NULL,0,'2018-06-27 09:24:41','2017-10-23 13:26:42','2018-12-14 11:10:43','9c19a49f-9856-4261-9b3c-0fbe784c7409'),(143,'robin@coffeebean.design','person-unknown.jpg','Robin','Willmott','robin@coffeebean.design','$2y$13$jLStZtSLdcxwQASM53LT4OHSuwMYl2tyh7Xs9p0YYG6zK1anusdQW',NULL,0,1,0,0,0,0,0,'2018-12-14 18:10:40','192.168.33.1',NULL,NULL,'2018-09-26 15:00:24',NULL,NULL,NULL,NULL,0,'2018-04-29 14:55:07','2018-03-10 15:10:18','2018-12-14 18:10:40','493a9c3c-495a-4e76-a6bc-eb19686a2032'),(1423,'portia.hartley@lantra.co.uk',NULL,'Portia','Hartley','portia.hartley@lantra.co.uk','$2y$13$oB/PKSkKxsRXTj99hbM57O1PEHczqeRC55IO6LqmcsQE.RaaJJt5.',NULL,0,1,0,0,0,0,0,'2018-12-13 16:29:01','5.148.54.98',NULL,NULL,'2018-12-11 13:13:29',NULL,NULL,NULL,NULL,0,'2018-10-04 15:36:35','2018-10-04 15:36:00','2018-12-13 16:29:01','0235a0f4-3e0a-4a17-8d38-95b4ed489444'),(1438,'margaret.murray@skills-plus.co.uk','Koala.jpg','Margaret','Murray','margaret.murray@skills-plus.co.uk','$2y$13$aAufxWR0ftAqEdppYAeteuZPZcSeXLdAqK.pWnYseLX6TuZlQQbOC',NULL,0,1,0,0,0,0,0,'2018-12-13 13:37:27','5.148.54.98','2018-12-13 15:32:04',1,'2018-12-13 15:32:04',NULL,'$2y$13$BXz8pEGARW7v1miC2rXtgu.CsmH.VtQf5rmdW/45ui7ubcRr6rAqC','2018-12-13 14:26:09',NULL,0,'2018-12-07 09:29:41','2018-10-11 09:02:13','2018-12-13 15:32:04','4182bda5-f6b8-4125-8893-94a432f7691e'),(1440,'stuart.smith@lantra.co.uk',NULL,'Stuart','Smith','stuart.smith@lantra.co.uk','$2y$13$cyymCrsLr/LXcrEiTpllgetQb9mB95PhwFsYbBHF8N7ESD/c84rzm',NULL,0,1,0,0,0,0,0,'2018-10-12 09:37:16','5.148.54.98',NULL,NULL,NULL,NULL,'$2y$13$ua6pPqKP/lwmmOo6XbGj2u95G.OLT/Ks3hcfjdi.Ke4tiK4fdanFy','2018-10-12 13:44:59',NULL,0,'2018-10-12 09:37:02','2018-10-12 09:36:14','2018-10-12 13:44:59','25793302-2ef2-4e8f-ac24-9fd5eadbbd62'),(1626,'test.user@lantra.co.uk',NULL,'Test','User','test.user@lantra.co.uk',NULL,NULL,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,'2018-12-06 17:47:14','2018-12-06 17:47:14','b4239107-2266-4856-8355-b668e8253657');
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

-- Dump completed on 2018-12-15  7:30:07
