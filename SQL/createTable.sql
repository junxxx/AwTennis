CREATE TABLE `wx_awtennis`.`awt_enroll_articles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uniacid` INT NOT NULL,
  `title` VARCHAR(50) NOT NULL,
  `detail` TEXT NOT NULL,
  `is_display` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0不显示,1显示',
  `createtime` INT(10) NOT NULL,
  `updatetime` INT(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
