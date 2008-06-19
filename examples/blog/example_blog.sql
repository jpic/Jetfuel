-- MySQL dump 10.10
--
-- Host: localhost    Database: jfexample
-- ------------------------------------------------------
-- Server version	5.0.24a-Debian_9ubuntu2.4-log

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
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment`
--


/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
LOCK TABLES `comment` WRITE;
INSERT INTO `comment` VALUES (1,2,'Bob User','bob@example.com','Hooray for Lorem Ipsum text!','2008-06-11 17:41:44');
UNLOCK TABLES;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `body` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post`
--


/*!40000 ALTER TABLE `post` DISABLE KEYS */;
LOCK TABLES `post` WRITE;
INSERT INTO `post` VALUES (1,'Test Post','Here\'s a test post.','This is a test post. How about some lorem ipsum text?\r\n\r\nLorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam ut purus vel neque consequat volutpat. Aenean vestibulum magna tristique nunc. Mauris ipsum. Sed egestas. Fusce neque nisl, commodo et, imperdiet vitae, fermentum a, nisl. Nullam pharetra. Aenean ultrices dignissim metus. Quisque porttitor pulvinar nibh. Mauris porta, arcu non facilisis cursus, augue ipsum placerat turpis, non pellentesque leo nibh et nibh. Vivamus a magna sit amet arcu porttitor sollicitudin. Nulla facilisi. Praesent sagittis lacinia pede. Praesent at ligula sed diam rutrum bibendum. ',0,'2008-06-11 17:31:59'),(2,'Another Test Post','Yet another test post.','Test posts are great! Nullam lorem. Sed ac sapien ac elit rhoncus rhoncus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Suspendisse rutrum bibendum orci. Donec sagittis pretium leo. Nulla sit amet mauris a diam interdum blandit. Quisque sit amet risus. Cras id nibh a libero vehicula dapibus. Mauris egestas. Fusce malesuada consequat nisl.\r\n\r\nPellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec magna sem, ultrices vitae, malesuada vitae, tristique at, quam. Praesent neque nisl, fringilla vitae, tincidunt a, posuere nec, pede. Quisque rutrum enim id odio. Ut turpis. Sed ut mi non justo consequat porttitor. Phasellus sollicitudin enim quis leo rutrum imperdiet. Sed pulvinar molestie tortor. Pellentesque nulla quam, cursus id, varius eget, egestas sed, pede. Vivamus dictum dolor ac nisl. Pellentesque in nisl. Integer sodales odio sed justo. Nam ante. Duis at ante. Proin ultricies, est aliquam tempus bibendum, dolor nibh accumsan leo, in laoreet risus tortor interdum ante. ',0,'2008-06-11 17:32:33');
UNLOCK TABLES;
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

