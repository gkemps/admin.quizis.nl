/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

INSERT INTO `quiz_category` (`id`, `name`, `icon`) VALUES (15,'Sport','fa fa-soccer-ball-o'),(16,'Muziek','glyphicon glyphicon-music');

INSERT INTO `quiz_question` (`id`, `question`, `answer`, `points`, `quiz_Category_id`, `source`, `createdBy`, `image`, `dateCreated`, `dateUpdated`) VALUES (166,'Wie voetbalde er toen voor wie?','Hij',1,15,'',15,NULL,'2015-02-04 23:57:01',NULL),(167,'Wie zong er toen in band x?','zanger y',1,16,'bla',15,NULL,'2015-02-04 23:57:24',NULL),(168,'Wie is er wereldkampioen voetbal?','Duitsland',1,15,'',15,NULL,'2015-02-05 19:10:08',NULL),(169,'Wie is er wereldkampioen hockey','Australie',1,15,'',15,NULL,'2015-02-05 19:10:23',NULL),(170,'Wie is er wereldkampioen handbal','Spanje',1,15,'',15,NULL,'2015-02-05 19:10:55',NULL),(171,'Wie is er wereldkampioen basketbal','Verenigde Staten',1,15,'',15,NULL,'2015-02-05 19:11:26',NULL),(172,'Wie is er wereldkampioen korfbal','Nederland',1,15,'',15,NULL,'2015-02-05 19:11:57',NULL),(173,'Wie is er wereldkampioen vollybal','Polen',1,15,'',15,NULL,'2015-02-05 19:12:46',NULL),(174,'Wie is er wereldkampioen honkbal','Nederland',1,16,'',15,NULL,'2015-02-05 19:13:31',NULL),(175,'Wie is er wereldkampioen waterpolo','Hongarije',1,15,'',15,NULL,'2015-02-05 19:14:51',NULL),(176,'Wie is er wereldkampioen ijshockey','Finland',1,16,'',15,NULL,'2015-02-05 19:18:00',NULL);

INSERT INTO `quiz_questionlike` (`id`, `quiz_User_id`, `quiz_Question_id`, `dateCreated`) VALUES (14,15,167,'2015-02-04 23:57:30');


INSERT INTO `quiz_quiz` (`id`, `name`, `location`, `date`, `dateCreated`, `dateUpdated`) VALUES (2,'Quiz','Cafe Bed','2016-05-01 23:55:48','2015-02-04 23:55:57',NULL);

INSERT INTO `quiz_quizround` (`id`, `quiz_Quiz_id`, `number`, `theme`, `dateCreated`, `dateUpdated`) VALUES (8,2,1,'Mijn thema','2015-02-04 23:56:30',NULL);

INSERT INTO `quiz_quizroundquestion` (`id`, `quiz_QuizRound_id`, `quiz_Question_id`, `questionNumber`, `dateCreated`, `dateUpdated`) VALUES (35,8,167,10,'2015-02-04 23:57:33',NULL);


INSERT INTO `quiz_user` (`id`, `username`, `email`, `displayName`, `password`, `state`) VALUES (15,'geert','kemzy@gewis.nl','Geert','$2y$14$5tR.1ZuvNfS3rWcoGEC5FuJNa0FSmHQamIax.vSpEf8W8K/Q7B9yi',NULL);
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

