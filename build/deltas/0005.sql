-- Add invoice fields to Quiz table
ALTER TABLE `quiz_quiz` 
ADD COLUMN `priceInvoice` FLOAT DEFAULT NULL COMMENT 'Factuur bedrag',
ADD COLUMN `pricePerPersonInvoice` FLOAT DEFAULT NULL COMMENT 'Factuur bedrag per persoon',
ADD COLUMN `discountAmount` FLOAT DEFAULT NULL COMMENT 'Kortings bedrag',
ADD COLUMN `discountPercentage` FLOAT DEFAULT NULL COMMENT 'Kortings percentage',
ADD COLUMN `dateInvoiced` DATETIME DEFAULT NULL COMMENT 'Timestamp van factuur';
