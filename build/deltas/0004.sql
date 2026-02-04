-- Create Customer table
CREATE TABLE `quiz_Customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postalCode` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `contactPerson` varchar(255) DEFAULT NULL,
  `createdBy` int(10) unsigned NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_quiz_Customer_quiz_User` (`createdBy`),
  CONSTRAINT `fk_quiz_Customer_quiz_User` FOREIGN KEY (`createdBy`) REFERENCES `quiz_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Add customer foreign key to Quiz table
ALTER TABLE `quiz_quiz` 
ADD COLUMN `quiz_Customer_id` int(10) unsigned DEFAULT NULL,
ADD KEY `fk_quiz_Quiz_quiz_Customer` (`quiz_Customer_id`),
ADD CONSTRAINT `fk_quiz_Quiz_quiz_Customer` FOREIGN KEY (`quiz_Customer_id`) REFERENCES `quiz_Customer` (`id`);
