
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- songs
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `songs`;

CREATE TABLE `songs`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `album_id` INTEGER NOT NULL,
    `position` SMALLINT NOT NULL,
    `name` VARCHAR(64) NOT NULL,
    `duration` TIME NOT NULL,
    `disc` SMALLINT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `FI_g2album` (`album_id`),
    CONSTRAINT `song2album`
        FOREIGN KEY (`album_id`)
        REFERENCES `album` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- album
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `album`;

CREATE TABLE `album`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `artist` VARCHAR(100) NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `discs` SMALLINT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- person
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `person`;

CREATE TABLE `person`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- friend
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `friend`;

CREATE TABLE `friend`
(
    `person_1` INTEGER NOT NULL,
    `person_2` INTEGER NOT NULL,
    PRIMARY KEY (`person_1`,`person_2`),
    INDEX `FI_son_2` (`person_2`),
    CONSTRAINT `person_1`
        FOREIGN KEY (`person_1`)
        REFERENCES `person` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `person_2`
        FOREIGN KEY (`person_2`)
        REFERENCES `person` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
