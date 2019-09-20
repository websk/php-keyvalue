CREATE TABLE `key_value` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `created_at_ts` int NOT NULL DEFAULT '0',
    `name` varchar(255) NOT NULL,
    `value` mediumtext,
    `description` varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
