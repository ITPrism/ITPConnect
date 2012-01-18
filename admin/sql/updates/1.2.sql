ALTER TABLE `#__itpc_users` MODIFY COLUMN `fbuser_id` varchar(50) NOT NULL DEFAULT '0';
ALTER TABLE `#__itpc_users` MODIFY COLUMN `twuser_id` varchar(50) NOT NULL DEFAULT '0';
ALTER TABLE `#__itpc_sessions` MODIFY COLUMN `fbuser_id` varchar(50) NOT NULL DEFAULT '0';
ALTER TABLE `#__itpc_sessions` MODIFY COLUMN `twuser_id` varchar(50) NOT NULL DEFAULT '0';