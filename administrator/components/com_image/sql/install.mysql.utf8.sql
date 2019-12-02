CREATE TABLE IF NOT EXISTS `#__payedsumsimage` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`projectname` VARCHAR(255)  NOT NULL ,
`summa` DECIMAL NOT NULL ,
`mediamanager` VARCHAR(255)  NOT NULL ,
`imagelist` VARCHAR(255)  NOT NULL ,
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8mb4_unicode_ci;

