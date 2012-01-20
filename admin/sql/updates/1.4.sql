DROP TABLE IF EXISTS `#__itpc_sessions`;
ALTER TABLE `#__itpc_users` ENGINE=InnoDB;
ALTER TABLE `#__itpc_users`  MODIFY COLUMN `fbuser_id` bigint(20) NOT NULL DEFAULT '0';
ALTER TABLE `#__itpc_users`  MODIFY COLUMN `twuser_id` bigint(20) NOT NULL DEFAULT '0';