-- Add photosOptimized timestamp to Quiz table
ALTER TABLE `quiz_quiz`
ADD COLUMN `photosOptimized` DATETIME DEFAULT NULL COMMENT 'Timestamp van laatste foto optimalisatie';
