-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: gvpxhzei_app
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.30-MariaDB

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Login` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','7c4a8d09ca3762af61e59520943dc26494f8941b','Admin');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `availabledriver`
--

DROP TABLE IF EXISTS `availabledriver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `availabledriver` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DriverId` int(11) NOT NULL,
  `Date` date NOT NULL,
  `UF` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `availabledriver`
--

LOCK TABLES `availabledriver` WRITE;
/*!40000 ALTER TABLE `availabledriver` DISABLE KEYS */;
/*!40000 ALTER TABLE `availabledriver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bodyworks`
--

DROP TABLE IF EXISTS `bodyworks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bodyworks` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bodyworks`
--

LOCK TABLES `bodyworks` WRITE;
/*!40000 ALTER TABLE `bodyworks` DISABLE KEYS */;
INSERT INTO `bodyworks` VALUES (1,'Baú'),(2,'Baú Frigorífico'),(3,'Boiadeiro'),(4,'Bug Porta Container'),(5,'Caçamba'),(6,'Gaiola'),(7,'Grade Baixa'),(8,'Graneleiro'),(9,'Munck'),(10,'Passageiros'),(11,'Prancha'),(12,'Sider'),(13,'Silo'),(14,'Tanque'),(15,'Para Tora');
/*!40000 ALTER TABLE `bodyworks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driveralert`
--

DROP TABLE IF EXISTS `driveralert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driveralert` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DriverId` int(11) NOT NULL,
  `Number` varchar(45) NOT NULL,
  `Date` datetime NOT NULL,
  `DateReturn` datetime NOT NULL,
  `Status` int(1) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driveralert`
--

LOCK TABLES `driveralert` WRITE;
/*!40000 ALTER TABLE `driveralert` DISABLE KEYS */;
INSERT INTO `driveralert` VALUES (1,73,'5758686868686','2018-02-27 17:04:28','2018-03-06 10:05:34',1),(2,77,'535238635383','2018-02-28 08:43:48','2018-03-06 17:49:36',1),(3,79,'484848489489','2018-02-28 08:43:45','2018-03-08 16:11:25',1);
/*!40000 ALTER TABLE `driveralert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driverbill`
--

DROP TABLE IF EXISTS `driverbill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driverbill` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DriverId` int(11) NOT NULL,
  `FreightId` int(11) NOT NULL,
  `Value` decimal(11,2) NOT NULL,
  `DateGenerator` datetime NOT NULL,
  `DatePayment` date NOT NULL,
  `DateConfirmPayment` datetime DEFAULT NULL,
  `FormPayment` varchar(255) NOT NULL,
  `Observation` text NOT NULL,
  `Status` int(1) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driverbill`
--

LOCK TABLES `driverbill` WRITE;
/*!40000 ALTER TABLE `driverbill` DISABLE KEYS */;
INSERT INTO `driverbill` VALUES (1,81,6,30.00,'2018-03-13 14:40:53','2018-03-13','2018-03-13 14:48:58','Deposito','obs',1),(5,79,4,30.00,'2018-03-15 08:21:53','2018-03-15','2018-03-23 11:42:26','Boleto','',1),(6,81,7,30.00,'2018-03-15 08:46:28','2018-03-15','2018-03-23 11:45:36','','cliente pagou boleto antigo!',1);
/*!40000 ALTER TABLE `driverbill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drivernote`
--

DROP TABLE IF EXISTS `drivernote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drivernote` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FreightId` int(11) NOT NULL,
  `DriverId` int(11) NOT NULL,
  `Note` int(2) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivernote`
--

LOCK TABLES `drivernote` WRITE;
/*!40000 ALTER TABLE `drivernote` DISABLE KEYS */;
INSERT INTO `drivernote` VALUES (1,0,1,5),(2,0,1,0),(3,0,1,2);
/*!40000 ALTER TABLE `drivernote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driverposition`
--

DROP TABLE IF EXISTS `driverposition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driverposition` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DriverId` int(11) NOT NULL,
  `FreightId` int(11) NOT NULL,
  `Lat` varchar(255) NOT NULL,
  `Lon` varchar(255) NOT NULL,
  `Date` datetime NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driverposition`
--

LOCK TABLES `driverposition` WRITE;
/*!40000 ALTER TABLE `driverposition` DISABLE KEYS */;
INSERT INTO `driverposition` VALUES (1,81,6,'-27.81750615','-50.32753405','2018-03-11 14:16:12'),(2,81,6,'-27.81451593','-50.32807441','2018-03-11 15:12:22'),(3,81,6,'-27.81383147','-50.3238375','2018-03-12 19:50:01'),(4,81,6,'-27.81663769','-50.32551514','2018-03-13 13:15:35'),(6,81,6,'-27.81666538','-50.32743173','2018-03-14 13:45:55'),(7,81,7,'-27.81666538','-50.32743173','2018-03-14 13:45:55'),(8,81,7,'-27.81663769','-50.32551514','2018-03-15 13:40:18');
/*!40000 ALTER TABLE `driverposition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drivers` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Phone` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `CNH` varchar(255) NOT NULL,
  `CPF` varchar(255) NOT NULL,
  `Status` int(1) NOT NULL DEFAULT '1',
  `Vehicle` int(11) NOT NULL,
  `Bodywork` int(11) NOT NULL,
  `Board` varchar(255) NOT NULL,
  `ANTT` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivers`
--

LOCK TABLES `drivers` WRITE;
/*!40000 ALTER TABLE `drivers` DISABLE KEYS */;
INSERT INTO `drivers` VALUES (81,'Pedro Oliveira de Alcantra','','pedro@gmail.com','da4b9237bacccdf19c0760cab7aec4a8359010b0','89789','789.789.7',1,7,8,'7','7897'),(80,'Marcio','(3','fulaninhograu@hotmail.com','77de68daecd823babbb58edb1c8e14d7106e83bb','3','3',0,3,3,'3','3'),(77,'Romeu','04999797-4521','romeu@hotmail.com','2','2222222','22222222222',1,1,1,'222222222','2222222222'),(79,'Guilherme','(49) 99975-4222','fulaninho1000grau@hotmail.com','356a192b7913b04c54574d18c28d46e6395428ab','484848484848','087.555.222-1',1,3,12,'mvc-7878','498489');
/*!40000 ALTER TABLE `drivers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enterpriseaddress`
--

DROP TABLE IF EXISTS `enterpriseaddress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enterpriseaddress` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `EnterprisesId` int(11) NOT NULL,
  `UF` varchar(2) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `Number` varchar(255) NOT NULL,
  `CEP` varchar(45) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enterpriseaddress`
--

LOCK TABLES `enterpriseaddress` WRITE;
/*!40000 ALTER TABLE `enterpriseaddress` DISABLE KEYS */;
INSERT INTO `enterpriseaddress` VALUES (69,39,'6c','Lages','Ba BA TESTE','48489','4545456546'),(68,39,'SC','Lages',' Rua Carlos Jofre do Amaral ','988489','88501-005'),(67,39,'Ri','Rio de Janeiro','Avenida','94898498','20040-90'),(72,57,'1','1','1','1','11'),(70,37,'SC','Lages','Rua Raimundo Correia','534545','88503-090'),(71,37,'SC','Lages','Coral','89598','88523-07'),(73,58,'1','1','1','1','11'),(74,59,'1','1','1','1','11'),(63,38,'SC','Lages','Rua Otacílio Vieira da Costa','4565','88501-050'),(75,61,'2','2','2','2','2'),(76,62,'3','3','3','3','3'),(66,39,'SP','São Paulo','Av Paulista','84445','20040-903');
/*!40000 ALTER TABLE `enterpriseaddress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enterprisecontact`
--

DROP TABLE IF EXISTS `enterprisecontact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enterprisecontact` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `EnterprisesId` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Phone` varchar(45) NOT NULL,
  `Email` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enterprisecontact`
--

LOCK TABLES `enterprisecontact` WRITE;
/*!40000 ALTER TABLE `enterprisecontact` DISABLE KEYS */;
INSERT INTO `enterprisecontact` VALUES (1,59,'1','(1','1'),(6,59,'1','(1','1'),(7,61,'2','(2','2'),(8,62,'3','(3','3');
/*!40000 ALTER TABLE `enterprisecontact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enterprisenote`
--

DROP TABLE IF EXISTS `enterprisenote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enterprisenote` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FreightId` int(11) NOT NULL,
  `EnterpriseId` int(11) NOT NULL,
  `Note` int(2) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enterprisenote`
--

LOCK TABLES `enterprisenote` WRITE;
/*!40000 ALTER TABLE `enterprisenote` DISABLE KEYS */;
/*!40000 ALTER TABLE `enterprisenote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enterprises`
--

DROP TABLE IF EXISTS `enterprises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enterprises` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `CNPJ` varchar(255) NOT NULL,
  `Status` int(1) NOT NULL DEFAULT '1',
  `Password` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enterprises`
--

LOCK TABLES `enterprises` WRITE;
/*!40000 ALTER TABLE `enterprises` DISABLE KEYS */;
INSERT INTO `enterprises` VALUES (39,'Empresa 1','48.948.949/8498-48',1,'202cb962ac59075b964b07152d234b70'),(38,'Code','78.879.879/8789-78',1,'356a192b7913b04c54574d18c28d46e6395428ab'),(61,'Empresa 2','2',1,'da4b9237bacccdf19c0760cab7aec4a8359010b0'),(62,'Empresa 3','3',1,'77de68daecd823babbb58edb1c8e14d7106e83bb'),(59,'Empresa 4','1',1,'356a192b7913b04c54574d18c28d46e6395428ab');
/*!40000 ALTER TABLE `enterprises` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formpayment`
--

DROP TABLE IF EXISTS `formpayment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formpayment` (
  `Id` int(11) NOT NULL,
  `Type` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formpayment`
--

LOCK TABLES `formpayment` WRITE;
/*!40000 ALTER TABLE `formpayment` DISABLE KEYS */;
INSERT INTO `formpayment` VALUES (1,'Deposito'),(2,'Transferência'),(3,'Dinheiro'),(4,'Boleto');
/*!40000 ALTER TABLE `formpayment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freight`
--

DROP TABLE IF EXISTS `freight`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `freight` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `EnterprisesId` int(11) NOT NULL,
  `Freight` varchar(255) DEFAULT NULL,
  `Weight` decimal(11,2) DEFAULT NULL,
  `Block` int(2) DEFAULT NULL,
  `Height` decimal(11,2) DEFAULT NULL,
  `Length` decimal(11,2) DEFAULT NULL,
  `Width` decimal(11,2) DEFAULT NULL,
  `WithdrawalTime` date DEFAULT NULL,
  `DeliveryTime` date DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freight`
--

LOCK TABLES `freight` WRITE;
/*!40000 ALTER TABLE `freight` DISABLE KEYS */;
INSERT INTO `freight` VALUES (4,59,'1',1.00,1,NULL,NULL,NULL,'2018-03-12','2018-03-24'),(2,61,'Teste5',1.00,1,NULL,NULL,NULL,'2018-03-09','2018-03-23'),(3,62,'3',3.00,1,NULL,NULL,NULL,'2018-03-10','2018-03-22'),(6,39,'Madeira',2.00,1,NULL,NULL,NULL,'2018-03-11','2018-03-19'),(7,39,'5',5.00,1,NULL,NULL,NULL,'2018-03-26','2018-03-30');
/*!40000 ALTER TABLE `freight` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freightbodyworks`
--

DROP TABLE IF EXISTS `freightbodyworks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `freightbodyworks` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FreightId` int(11) NOT NULL,
  `BodyworksId` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freightbodyworks`
--

LOCK TABLES `freightbodyworks` WRITE;
/*!40000 ALTER TABLE `freightbodyworks` DISABLE KEYS */;
INSERT INTO `freightbodyworks` VALUES (28,2,14),(27,2,5),(26,2,4),(25,2,3),(20,3,3),(21,4,4),(22,5,4),(29,6,3),(31,7,4);
/*!40000 ALTER TABLE `freightbodyworks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freightdelivery`
--

DROP TABLE IF EXISTS `freightdelivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `freightdelivery` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FreightId` int(11) NOT NULL,
  `CEP` varchar(255) NOT NULL,
  `UF` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `Number` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freightdelivery`
--

LOCK TABLES `freightdelivery` WRITE;
/*!40000 ALTER TABLE `freightdelivery` DISABLE KEYS */;
INSERT INTO `freightdelivery` VALUES (16,5,'2','2','2','2','2'),(15,4,'11','1','1','1','1'),(14,3,'3','3','3','3','3'),(19,2,'2','2','2','2','2'),(20,6,'20040-903','SP','São Paulo','Av Paulista','84445'),(22,7,'88501-005','SC','Lages',' Rua Carlos Jofre do Amaral ','988489');
/*!40000 ALTER TABLE `freightdelivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freightdriver`
--

DROP TABLE IF EXISTS `freightdriver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `freightdriver` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FreightId` int(11) NOT NULL,
  `DriverId` int(11) NOT NULL,
  `Status` int(1) NOT NULL COMMENT '0 - aguardando - 1 ativo ',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freightdriver`
--

LOCK TABLES `freightdriver` WRITE;
/*!40000 ALTER TABLE `freightdriver` DISABLE KEYS */;
INSERT INTO `freightdriver` VALUES (1,6,81,1),(6,4,79,1),(5,4,77,0),(7,7,81,1);
/*!40000 ALTER TABLE `freightdriver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freightvehicles`
--

DROP TABLE IF EXISTS `freightvehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `freightvehicles` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FreightId` int(11) NOT NULL,
  `VehiclesId` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freightvehicles`
--

LOCK TABLES `freightvehicles` WRITE;
/*!40000 ALTER TABLE `freightvehicles` DISABLE KEYS */;
INSERT INTO `freightvehicles` VALUES (44,2,3),(43,2,4),(42,2,4),(41,2,6),(40,2,3),(39,2,5),(34,3,3),(35,4,1),(36,5,2),(45,6,2),(47,7,2);
/*!40000 ALTER TABLE `freightvehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freightwithdrawal`
--

DROP TABLE IF EXISTS `freightwithdrawal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `freightwithdrawal` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `FreightId` int(11) NOT NULL,
  `CEP` varchar(255) NOT NULL,
  `UF` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `Number` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freightwithdrawal`
--

LOCK TABLES `freightwithdrawal` WRITE;
/*!40000 ALTER TABLE `freightwithdrawal` DISABLE KEYS */;
INSERT INTO `freightwithdrawal` VALUES (18,2,'2','2','2','2','2'),(13,3,'3','3','3','3','3'),(14,4,'11','1','1','1','1'),(15,5,'2','2','2','2','2'),(19,6,'88501-005','SC','Lages',' Rua Carlos Jofre do Amaral ','988489'),(21,7,'20040-903','SP','São Paulo','Av Paulista','84445');
/*!40000 ALTER TABLE `freightwithdrawal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Value` decimal(11,2) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,29866.66);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DriverBillId` int(11) NOT NULL,
  `PagarmeId` varchar(255) NOT NULL,
  `Url` text NOT NULL,
  `Bar` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (1,5,'3143942','https://pagar.me','1234 5678'),(2,6,'3143971','https://pagar.me','1234 5678');
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicles` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) NOT NULL,
  `Tons` decimal(11,2) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,'VLC',3.50),(2,'Três  Quartos',4.00),(3,'Toco',8.00),(4,'Truck',15.00),(5,'Bitruck',22.00),(6,'Carreta',25.00),(7,'Carreta LS ',27.00),(8,'Bitrem',35.00),(9,'RodoTrem',40.00);
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-21 12:18:34
