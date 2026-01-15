-- init.sql: creates `games` DB, sample `GAMES` table rows, and an `images` table

CREATE DATABASE IF NOT EXISTS games;
USE games;

CREATE TABLE IF NOT EXISTS `GAMES` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `platform` VARCHAR(45) NOT NULL,
  `released` DATE NULL,
  `summary` TEXT NULL,
  `metascore` DECIMAL NULL,
  `userscore` DECIMAL NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `GAMES` (`name`,`platform`,`released`,`summary`,`metascore`,`userscore`)
VALUES ("angry birds","platform",NULL,"summary",10,9)
ON DUPLICATE KEY UPDATE name=name;

INSERT INTO `GAMES` (`name`,`platform`,`released`,`summary`,`metascore`,`userscore`)
VALUES ("Super Mario Galaxy 2","Wii", "2010-05-23","Super Mario Galaxy 2, the sequel to the galaxy-hopping original game, includes the gravity-defying, physics-based exploration from the first game, but is loaded with entirely new galaxies and features to challenge players. On some stages, Mario can pair up with his dinosaur buddy Yoshi and use his tongue to grab items and spit them back at enemies. Players can also have fun with new items such as a drill that lets our hero tunnel through solid rock.",97, 9.1)
ON DUPLICATE KEY UPDATE name=name;

INSERT INTO `GAMES` (`name`,`platform`,`released`,`summary`,`metascore`,`userscore`)
VALUES ("Grand Theft Auto V","PS4", "2014-11-18","The sprawling sun-soaked metropolis of Los Santos is chock full of self-help coaches, starlets and C-List celebrities, once on top of the media world, now struggling to stay relevant in time of economic malaise and lowest-common-denominator reality TV. Amidst this madness, three unique criminals plan their own chances of survival and success: Franklin, a street-level hustler in search of opportunities for serious money; Michael, an ex-con whose \"retirement\" is a less rosy than he hoped it would be; and Trevor, a violent dude driven by the chance for a quick high and the next big score. Nearly out of options, the crew risks it all in a series of daring and dangerous heists that could set them up for life - one way or the other.",97, 8.3)
ON DUPLICATE KEY UPDATE name=name;

-- Images table for storing uploaded images as BLOBs
CREATE TABLE IF NOT EXISTS `images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(255) NOT NULL,
  `mime` VARCHAR(100) NOT NULL,
  `data` LONGBLOB NOT NULL,
  `size` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

-- Filesystem-backed images table: stores path instead of blob
CREATE TABLE IF NOT EXISTS `images_fs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(255) NOT NULL,
  `mime` VARCHAR(100) NOT NULL,
  `path` VARCHAR(512) NOT NULL,
  `size` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
