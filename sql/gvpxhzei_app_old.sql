-- MySQL dump 10.16  Distrib 10.1.25-MariaDB, for osx10.6 (i386)
--
-- Host: localhost    Database: gvpxhzei_app
-- ------------------------------------------------------
-- Server version	10.1.25-MariaDB

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
  `Status` int(1) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driveralert`
--

LOCK TABLES `driveralert` WRITE;
/*!40000 ALTER TABLE `driveralert` DISABLE KEYS */;
/*!40000 ALTER TABLE `driveralert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drivernote`
--

DROP TABLE IF EXISTS `drivernote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drivernote` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DriverId` int(11) NOT NULL,
  `Note` int(2) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivernote`
--

LOCK TABLES `drivernote` WRITE;
/*!40000 ALTER TABLE `drivernote` DISABLE KEYS */;
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
  `Lat` varchar(255) NOT NULL,
  `Lon` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driverposition`
--

LOCK TABLES `driverposition` WRITE;
/*!40000 ALTER TABLE `driverposition` DISABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivers`
--

LOCK TABLES `drivers` WRITE;
/*!40000 ALTER TABLE `drivers` DISABLE KEYS */;
INSERT INTO `drivers` VALUES (73,'Wagner','(99) 9999-9999','wagner@emprezaz.com','356a192b7913b04c54574d18c28d46e6395428ab','9999999999','999.999.999-9',0,4,7,'3333333333','3333333333');
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
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enterpriseaddress`
--

LOCK TABLES `enterpriseaddress` WRITE;
/*!40000 ALTER TABLE `enterpriseaddress` DISABLE KEYS */;
INSERT INTO `enterpriseaddress` VALUES (63,38,'SC','Lages','Rua Otacílio Vieira da Costa','4565','88501-050'),(64,38,'SC','Lages','Marechal Floriano','5644','88501512'),(65,38,'RJ','Rio de Janeiro','Qualquer uma que achar','4984984','88502-145'),(66,39,'SP','São Paulo','Av Paulista','84445','20040-903'),(67,39,'Ri','Rio de Janeiro','Avenida','94898498','20040-90'),(68,39,'SC','Lages',' Rua Carlos Jofre do Amaral ','988489','88501-005'),(69,39,'6c','Lages','Ba BA TESTE','48489','4545456546'),(70,37,'SC','Lages','Rua Raimundo Correia','534545','88503-090'),(71,37,'SC','Lages','Coral','89598','88523-07');
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enterprisecontact`
--

LOCK TABLES `enterprisecontact` WRITE;
/*!40000 ALTER TABLE `enterprisecontact` DISABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enterprises`
--

LOCK TABLES `enterprises` WRITE;
/*!40000 ALTER TABLE `enterprises` DISABLE KEYS */;
INSERT INTO `enterprises` VALUES (37,'teste de endereço','81.979.814/9494-19',1,'40bd001563085fc35165329ea1ff5c5ecbdbbeef'),(38,'Imaginaria','78.879.879/8789-78',1,'356a192b7913b04c54574d18c28d46e6395428ab'),(39,'Emprezaz','48.948.949/8498-48',1,'356a192b7913b04c54574d18c28d46e6395428ab');
/*!40000 ALTER TABLE `enterprises` ENABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freight`
--

LOCK TABLES `freight` WRITE;
/*!40000 ALTER TABLE `freight` DISABLE KEYS */;
INSERT INTO `freight` VALUES (142,39,'Pilha de madeiras',5000.00,1,NULL,NULL,NULL,'2018-02-20','2018-02-20'),(143,37,'2',2.00,1,NULL,NULL,NULL,'2018-02-20','2018-02-20'),(144,37,'2',2.00,1,NULL,NULL,NULL,'2018-02-20','2018-02-20'),(145,37,'2',2.00,1,NULL,NULL,NULL,'2018-02-20','2018-02-20'),(146,39,'222222',2222.22,1,NULL,NULL,NULL,'2018-02-21','2018-02-21'),(148,39,'222222',2222.22,1,NULL,NULL,NULL,'2018-02-21','2018-02-21'),(149,39,'222222',2222.22,1,NULL,NULL,NULL,'2018-02-21','2018-02-21'),(150,39,'1',1.00,1,NULL,NULL,NULL,'2018-02-21','2018-02-22');
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
) ENGINE=MyISAM AUTO_INCREMENT=232 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freightbodyworks`
--

LOCK TABLES `freightbodyworks` WRITE;
/*!40000 ALTER TABLE `freightbodyworks` DISABLE KEYS */;
INSERT INTO `freightbodyworks` VALUES (192,150,1),(193,149,0),(194,149,0),(195,142,0);
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
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freightdelivery`
--

LOCK TABLES `freightdelivery` WRITE;
/*!40000 ALTER TABLE `freightdelivery` DISABLE KEYS */;
INSERT INTO `freightdelivery` VALUES (91,131,'20040-903','RJ','Rio de Janeiro','Avenida Rio Branco','84484894'),(92,140,'88503-090','SC','Lages','Rua Raimundo Correia','534545'),(93,141,'88523-07','SC','Lages','Coral','89598'),(94,142,'20040-903','SP','São Paulo','Av Paulista','84445'),(95,143,'88503-090','SC','Lages','Rua Raimundo Correia','534545'),(96,144,'88503-090','SC','Lages','Rua Raimundo Correia','534545'),(97,149,'4545456546','6c','Lages','Ba BA TESTE','48489'),(98,150,'20040-903','SP','São Paulo','Av Paulista','84445');
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freightdriver`
--

LOCK TABLES `freightdriver` WRITE;
/*!40000 ALTER TABLE `freightdriver` DISABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freightvehicles`
--

LOCK TABLES `freightvehicles` WRITE;
/*!40000 ALTER TABLE `freightvehicles` DISABLE KEYS */;
INSERT INTO `freightvehicles` VALUES (128,149,6),(129,149,3),(130,149,2),(131,150,1),(132,142,2),(133,142,4),(134,142,1),(135,142,3),(136,142,3);
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
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freightwithdrawal`
--

LOCK TABLES `freightwithdrawal` WRITE;
/*!40000 ALTER TABLE `freightwithdrawal` DISABLE KEYS */;
INSERT INTO `freightwithdrawal` VALUES (91,131,'88501-005','SC','Lages',' Rua Carlos Jofre do Amaral ','988489'),(92,139,'88523-07','SC','Lages','Coral','89598'),(93,140,'88503-090','SC','Lages','Rua Raimundo Correia','534545'),(94,141,'88523-07','SC','Lages','Coral','89598'),(95,142,'4545456546','6c','Lages','Ba BA TESTE','48489'),(96,143,'88523-07','SC','Lages','Coral','89598'),(97,144,'88523-07','SC','Lages','Coral','89598'),(98,149,'4545456546','6c','Lages','Ba BA TESTE','48489'),(99,150,'88501-005','SC','Lages',' Rua Carlos Jofre do Amaral ','988489');
/*!40000 ALTER TABLE `freightwithdrawal` ENABLE KEYS */;
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

-- Dump completed on 2018-02-23  9:14:27
