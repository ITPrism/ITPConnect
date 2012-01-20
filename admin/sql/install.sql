CREATE TABLE IF NOT EXISTS `#__itpc_users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `users_id` int(11) UNSIGNED NOT NULL,
  `fbuser_id` bigint(20) NOT NULL DEFAULT '0',
  `twuser_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY(`id`)
)
ENGINE=INNODB
CHARACTER SET utf8 
COLLATE utf8_general_ci ;
