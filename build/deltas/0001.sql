-- MySQL dump 10.13  Distrib 5.6.22, for Win32 (x86)
--
-- Host: localhost    Database: quiz
-- ------------------------------------------------------
-- Server version	5.6.22-log

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
-- Table structure for table `quiz_category`
--

DROP TABLE IF EXISTS `quiz_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quiz_question`
--

DROP TABLE IF EXISTS `quiz_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text,
  `points` tinyint(4) NOT NULL,
  `quiz_Category_id` int(10) unsigned NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `createdBy` int(10) unsigned NOT NULL,
  `image` longblob,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_quiz_Question_quiz_Category` (`quiz_Category_id`),
  KEY `fk_quiz_Question_systemUser` (`createdBy`),
  CONSTRAINT `fk_quiz_Question_quiz_Category` FOREIGN KEY (`quiz_Category_id`) REFERENCES `quiz_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quiz_questionlike`
--

DROP TABLE IF EXISTS `quiz_questionlike`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_questionlike` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_User_id` int(10) unsigned NOT NULL,
  `quiz_Question_id` int(10) unsigned NOT NULL,
  `dateCreated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fl_quiz_QuestionLike_quiz_User` (`quiz_User_id`),
  KEY `fk_quiz_QuestionLike_quiz_Question` (`quiz_Question_id`),
  CONSTRAINT `fk_quiz_QuestionLike_quiz_Question` FOREIGN KEY (`quiz_Question_id`) REFERENCES `quiz_question` (`id`),
  CONSTRAINT `fl_quiz_QuestionLike_quiz_User` FOREIGN KEY (`quiz_User_id`) REFERENCES `quiz_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quiz_questiontag`
--

DROP TABLE IF EXISTS `quiz_questiontag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_questiontag` (
  `quiz_Question_id` int(10) unsigned NOT NULL,
  `quiz_Tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`quiz_Question_id`,`quiz_Tag_id`),
  KEY `fk_quiz_QuestionTag_quiz_Tag` (`quiz_Tag_id`),
  CONSTRAINT `fk_quiz_QuestionTag_quiz_Question` FOREIGN KEY (`quiz_Question_id`) REFERENCES `quiz_question` (`id`),
  CONSTRAINT `fk_quiz_QuestionTag_quiz_Tag` FOREIGN KEY (`quiz_Tag_id`) REFERENCES `quiz_tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quiz_quiz`
--

DROP TABLE IF EXISTS `quiz_quiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_quiz` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quiz_quizround`
--

DROP TABLE IF EXISTS `quiz_quizround`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_quizround` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_Quiz_id` int(10) unsigned NOT NULL,
  `number` tinyint(4) NOT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_quiz_QuizRound_quiz_Quiz` (`quiz_Quiz_id`),
  CONSTRAINT `fk_quiz_QuizRound_quiz_Quiz` FOREIGN KEY (`quiz_Quiz_id`) REFERENCES `quiz_quiz` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quiz_quizroundquestion`
--

DROP TABLE IF EXISTS `quiz_quizroundquestion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_quizroundquestion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_QuizRound_id` int(10) unsigned NOT NULL,
  `quiz_Question_id` int(10) unsigned NOT NULL,
  `questionNumber` int(10) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_quiz_QuizRoundQuestion_quiz_Question` (`quiz_Question_id`),
  KEY `fk_quiz_QuizRoundQuestion_quiz_QuizRound` (`quiz_QuizRound_id`),
  CONSTRAINT `fk_quiz_QuizRoundQuestion_quiz_Question` FOREIGN KEY (`quiz_Question_id`) REFERENCES `quiz_question` (`id`),
  CONSTRAINT `fk_quiz_QuizRoundQuestion_quiz_QuizRound` FOREIGN KEY (`quiz_QuizRound_id`) REFERENCES `quiz_quizround` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quiz_tag`
--

DROP TABLE IF EXISTS `quiz_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `createdBy` int(10) unsigned NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `quiz_user`
--

DROP TABLE IF EXISTS `quiz_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `displayName` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-02-04 23:48:29
