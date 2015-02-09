SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for quiz_quizlog
-- ----------------------------
DROP TABLE IF EXISTS `quiz_quizlog`;
CREATE TABLE `quiz_quizlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `quiz_User_id` int(10) unsigned NOT NULL,
  `quiz_Quiz_id` int(10) unsigned NOT NULL,
  `dateCreated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_quiz_QuizLog_quiz_User` (`quiz_User_id`),
  KEY `fk_quiz_QuizLog_quiz_Quiz` (`quiz_Quiz_id`),
  CONSTRAINT `fk_quiz_QuizLog_quiz_Quiz` FOREIGN KEY (`quiz_Quiz_id`) REFERENCES `quiz_quiz` (`id`),
  CONSTRAINT `fk_quiz_QuizLog_quiz_User` FOREIGN KEY (`quiz_User_id`) REFERENCES `quiz_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for quiz_quizroundquestioncomment
-- ----------------------------
DROP TABLE IF EXISTS `quiz_quizroundquestioncomment`;
CREATE TABLE `quiz_quizroundquestioncomment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `quiz_User_id` int(10) unsigned NOT NULL,
  `quiz_QuizRoundQuestion_id` int(10) unsigned NOT NULL,
  `dateCreated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_quiz_QuizRoundQuestionComment_quiz_User` (`quiz_User_id`),
  KEY `fk_quiz_QuizRoundQuestionComment_quiz_QuizRoundQuestion` (`quiz_QuizRoundQuestion_id`),
  CONSTRAINT `fk_quiz_QuizRoundQuestionComment_quiz_QuizRoundQuestion` FOREIGN KEY (`quiz_QuizRoundQuestion_id`) REFERENCES `quiz_quizroundquestion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_quiz_QuizRoundQuestionComment_quiz_User` FOREIGN KEY (`quiz_User_id`) REFERENCES `quiz_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
