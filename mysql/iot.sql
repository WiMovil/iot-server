-- MySQL dump 10.16  Distrib 10.1.37-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: iot
-- ------------------------------------------------------
-- Server version	10.1.37-MariaDB-0+deb9u1

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
-- Table structure for table `tb_actuador`
--

DROP TABLE IF EXISTS `tb_actuador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_actuador` (
  `id_actuador` bigint(20) NOT NULL AUTO_INCREMENT,
  `nom_actuador` varchar(100) DEFAULT NULL,
  `mac_actuador` char(17) DEFAULT NULL,
  `ipa_actuador` char(15) DEFAULT NULL,
  `dig_actuador` char(40) DEFAULT NULL,
  `id_estado` int(1) DEFAULT NULL,
  `id_ambiente` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_actuador`),
  KEY `FK_tb_actuador__tb_estado__id_estado` (`id_estado`),
  KEY `FK_tb_actuador__tb_ambiente__id_ambiente` (`id_ambiente`),
  CONSTRAINT `FK_tb_actuador__tb_ambiente__id_ambiente` FOREIGN KEY (`id_ambiente`) REFERENCES `tb_ambiente` (`id_ambiente`),
  CONSTRAINT `FK_tb_actuador__tb_estado__id_estado` FOREIGN KEY (`id_estado`) REFERENCES `tb_estado` (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_actuador`
--

LOCK TABLES `tb_actuador` WRITE;
/*!40000 ALTER TABLE `tb_actuador` DISABLE KEYS */;
INSERT INTO `tb_actuador` VALUES (1,'Dormitorio Gerardo','2C:3A:E8:43:91:CF','172.16.30.132','100',1,1);
/*!40000 ALTER TABLE `tb_actuador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_ambiente`
--

DROP TABLE IF EXISTS `tb_ambiente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_ambiente` (
  `id_ambiente` bigint(20) NOT NULL AUTO_INCREMENT,
  `nom_ambiente` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_ambiente`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_ambiente`
--

LOCK TABLES `tb_ambiente` WRITE;
/*!40000 ALTER TABLE `tb_ambiente` DISABLE KEYS */;
INSERT INTO `tb_ambiente` VALUES (1,'default');
/*!40000 ALTER TABLE `tb_ambiente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_estado`
--

DROP TABLE IF EXISTS `tb_estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_estado` (
  `id_estado` int(1) NOT NULL,
  `des_estado` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_estado`
--

LOCK TABLES `tb_estado` WRITE;
/*!40000 ALTER TABLE `tb_estado` DISABLE KEYS */;
INSERT INTO `tb_estado` VALUES (1,'Activado'),(2,'Desactivado');
/*!40000 ALTER TABLE `tb_estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_puerto_actuador`
--

DROP TABLE IF EXISTS `tb_puerto_actuador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_puerto_actuador` (
  `id_puerto_actuador` bigint(20) NOT NULL AUTO_INCREMENT,
  `est_puerto_actuador` int(4) DEFAULT NULL,
  `des_puerto_actuador` varchar(100) DEFAULT NULL,
  `id_actuador` bigint(20) DEFAULT NULL,
  `id_tipo_actuador` int(2) DEFAULT NULL,
  `id_estado` int(1) DEFAULT NULL,
  `id_ambiente` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_puerto_actuador`),
  KEY `FK_tb_puerto_actuador__tb_actuador__id_actuador` (`id_actuador`),
  KEY `FK_tb_puerto_actuador__tb_tipo_actuador__id_tipo_actuador` (`id_tipo_actuador`),
  KEY `FK_tb_puerto_actuador__tb_estado__id_estado` (`id_estado`),
  KEY `FK_tb_puerto_actuador__tb_ambiente__id_ambiente` (`id_ambiente`),
  CONSTRAINT `FK_tb_puerto_actuador__tb_actuador__id_actuador` FOREIGN KEY (`id_actuador`) REFERENCES `tb_actuador` (`id_actuador`),
  CONSTRAINT `FK_tb_puerto_actuador__tb_ambiente__id_ambiente` FOREIGN KEY (`id_ambiente`) REFERENCES `tb_ambiente` (`id_ambiente`),
  CONSTRAINT `FK_tb_puerto_actuador__tb_estado__id_estado` FOREIGN KEY (`id_estado`) REFERENCES `tb_estado` (`id_estado`),
  CONSTRAINT `FK_tb_puerto_actuador__tb_tipo_actuador__id_tipo_actuador` FOREIGN KEY (`id_tipo_actuador`) REFERENCES `tb_tipo_actuador` (`id_tipo_actuador`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_puerto_actuador`
--

LOCK TABLES `tb_puerto_actuador` WRITE;
/*!40000 ALTER TABLE `tb_puerto_actuador` DISABLE KEYS */;
INSERT INTO `tb_puerto_actuador` VALUES (1,255,'Luz de fondo',1,1,1,1),(2,0,'Luz de closet',1,1,1,1),(3,0,'Luz de Centro',1,1,1,1),(4,0,'Luz de escritorio',1,1,1,1);
/*!40000 ALTER TABLE `tb_puerto_actuador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_puerto_actuador_puerto_sensor`
--

DROP TABLE IF EXISTS `tb_puerto_actuador_puerto_sensor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_puerto_actuador_puerto_sensor` (
  `id_puerto_actuador_puerto_sensor` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_puerto_actuador` bigint(20) DEFAULT NULL,
  `id_puerto_sensor` bigint(20) DEFAULT NULL,
  `id_estado` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_puerto_actuador_puerto_sensor`),
  KEY `FK_tb_puerto_actuador_puerto_sensor__tb_pue_act__id_pue_act` (`id_puerto_actuador`),
  KEY `FK_tb_puerto_actuador_puerto_sensor__tb_pue_sen__id_pue_sen` (`id_puerto_sensor`),
  KEY `FK_tb_puerto_actuador_puerto_sensor__tb_estado__id_estado` (`id_estado`),
  CONSTRAINT `FK_tb_puerto_actuador_puerto_sensor__tb_estado__id_estado` FOREIGN KEY (`id_estado`) REFERENCES `tb_estado` (`id_estado`),
  CONSTRAINT `FK_tb_puerto_actuador_puerto_sensor__tb_pue_act__id_pue_act` FOREIGN KEY (`id_puerto_actuador`) REFERENCES `tb_puerto_actuador` (`id_puerto_actuador`),
  CONSTRAINT `FK_tb_puerto_actuador_puerto_sensor__tb_pue_sen__id_pue_sen` FOREIGN KEY (`id_puerto_sensor`) REFERENCES `tb_puerto_sensor` (`id_puerto_sensor`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_puerto_actuador_puerto_sensor`
--

LOCK TABLES `tb_puerto_actuador_puerto_sensor` WRITE;
/*!40000 ALTER TABLE `tb_puerto_actuador_puerto_sensor` DISABLE KEYS */;
INSERT INTO `tb_puerto_actuador_puerto_sensor` VALUES (12,2,1,1),(13,3,2,1),(14,4,3,1),(15,2,4,1),(16,3,4,1),(17,4,4,1);
/*!40000 ALTER TABLE `tb_puerto_actuador_puerto_sensor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_puerto_sensor`
--

DROP TABLE IF EXISTS `tb_puerto_sensor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_puerto_sensor` (
  `id_puerto_sensor` bigint(20) NOT NULL AUTO_INCREMENT,
  `des_puerto_sensor` varchar(100) DEFAULT NULL,
  `id_sensor` bigint(20) DEFAULT NULL,
  `id_tipo_sensor` int(2) DEFAULT NULL,
  `id_estado` int(1) DEFAULT NULL,
  `id_ambiente` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_puerto_sensor`),
  KEY `FK_tb_puerto_sensor__tb_sensor__id_sensor` (`id_sensor`),
  KEY `FK_tb_puerto_sensor__tb_tipo_sensor__id_tipo_sensor` (`id_tipo_sensor`),
  KEY `FK_tb_puerto_sensor__tb_estado__id_estado` (`id_estado`),
  KEY `FK_tb_puerto_sensor__tb_ambiente__id_ambiente` (`id_ambiente`),
  CONSTRAINT `FK_tb_puerto_sensor__tb_ambiente__id_ambiente` FOREIGN KEY (`id_ambiente`) REFERENCES `tb_ambiente` (`id_ambiente`),
  CONSTRAINT `FK_tb_puerto_sensor__tb_estado__id_estado` FOREIGN KEY (`id_estado`) REFERENCES `tb_estado` (`id_estado`),
  CONSTRAINT `FK_tb_puerto_sensor__tb_sensor__id_sensor` FOREIGN KEY (`id_sensor`) REFERENCES `tb_sensor` (`id_sensor`),
  CONSTRAINT `FK_tb_puerto_sensor__tb_tipo_sensor__id_tipo_sensor` FOREIGN KEY (`id_tipo_sensor`) REFERENCES `tb_tipo_sensor` (`id_tipo_sensor`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_puerto_sensor`
--

LOCK TABLES `tb_puerto_sensor` WRITE;
/*!40000 ALTER TABLE `tb_puerto_sensor` DISABLE KEYS */;
INSERT INTO `tb_puerto_sensor` VALUES (1,'Boton Touch Escritorio',1,1,1,1),(2,'Boton Touch Centro',1,1,1,1),(3,'Boton Touch Closet',1,1,1,1),(4,'Boton Touch Libre',1,1,1,1),(5,'Boton Touch Libre',1,2,1,1);
/*!40000 ALTER TABLE `tb_puerto_sensor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_sensor`
--

DROP TABLE IF EXISTS `tb_sensor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_sensor` (
  `id_sensor` bigint(20) NOT NULL AUTO_INCREMENT,
  `nom_sensor` varchar(100) DEFAULT NULL,
  `mac_sensor` char(17) DEFAULT NULL,
  `ipa_sensor` char(15) DEFAULT NULL,
  `dig_sensor` char(40) DEFAULT NULL,
  `id_estado` int(1) DEFAULT NULL,
  `id_ambiente` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_sensor`),
  KEY `FK_tb_sensor__tb_estado__id_estado` (`id_estado`),
  KEY `FK_tb_sensor__tb_ambiente__id_ambiente` (`id_ambiente`),
  CONSTRAINT `FK_tb_sensor__tb_ambiente__id_ambiente` FOREIGN KEY (`id_ambiente`) REFERENCES `tb_ambiente` (`id_ambiente`),
  CONSTRAINT `FK_tb_sensor__tb_estado__id_estado` FOREIGN KEY (`id_estado`) REFERENCES `tb_estado` (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_sensor`
--

LOCK TABLES `tb_sensor` WRITE;
/*!40000 ALTER TABLE `tb_sensor` DISABLE KEYS */;
INSERT INTO `tb_sensor` VALUES (1,'Dormitorio Gerardo','2C:3A:E8:43:91:CF','172.16.30.132','100',1,1);
/*!40000 ALTER TABLE `tb_sensor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_tipo_actuador`
--

DROP TABLE IF EXISTS `tb_tipo_actuador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_tipo_actuador` (
  `id_tipo_actuador` int(2) NOT NULL,
  `nom_tipo_actuador` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_actuador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_tipo_actuador`
--

LOCK TABLES `tb_tipo_actuador` WRITE;
/*!40000 ALTER TABLE `tb_tipo_actuador` DISABLE KEYS */;
INSERT INTO `tb_tipo_actuador` VALUES (1,'Switch'),(2,'Dimmer');
/*!40000 ALTER TABLE `tb_tipo_actuador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_tipo_sensor`
--

DROP TABLE IF EXISTS `tb_tipo_sensor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_tipo_sensor` (
  `id_tipo_sensor` int(2) NOT NULL,
  `nom_tipo_sensor` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_sensor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_tipo_sensor`
--

LOCK TABLES `tb_tipo_sensor` WRITE;
/*!40000 ALTER TABLE `tb_tipo_sensor` DISABLE KEYS */;
INSERT INTO `tb_tipo_sensor` VALUES (1,'Touch boton'),(2,'PIR');
/*!40000 ALTER TABLE `tb_tipo_sensor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'iot'
--

--
-- Dumping routines for database 'iot'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-12-30 21:58:16
