
CREATE TABLE `profiles` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nickname` (`nickname`)
) AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accessToken` varchar(255) DEFAULT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `selectedprofile` varchar(255) DEFAULT NULL,
  `clientToken` varchar(255) DEFAULT NULL,
  `serverId` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

