START TRANSACTION ;

ALTER TABLE `quiz_Question`
ADD COLUMN `audio`  longblob NULL AFTER `image`;

COMMIT;