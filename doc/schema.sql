
DROP TABLE IF EXISTS `contents`;
CREATE TABLE `contents` (
  `idx` int(10) NOT NULL AUTO_INCREMENT,
  `c_id` varchar(100) NOT NULL,
  `c_type` varchar(10) NOT NULL,
  `s_name` varchar(30) NOT NULL,
  `c_info` text NOT NULL,
  `f_count` int(4) NOT NULL DEFAULT '0',
  `f_enable` enum('y','n') NOT NULL,
  `regdate` datetime NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `contents_search_status`;
CREATE TABLE `contents_search_status` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `c_idx` int(10) NOT NULL,
  `search_word` varchar(200) NOT NULL,
  `c` enum('y','n') NOT NULL,
  `d` enum('y','n') NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `favorites`;
CREATE TABLE `favorites` (
  `idx` int(10) NOT NULL AUTO_INCREMENT,
  `m_idx` int(10) NOT NULL,
  `c_idx` int(10) NOT NULL,
  `search_word` varchar(200) NOT NULL,
  `tags` varchar(500) DEFAULT NULL,
  `regdate` datetime NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `idx` int(10) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(20) DEFAULT NULL,
  `passwd` varchar(40) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `facebook_id` varchar(20) DEFAULT NULL,
  `twitter_id` varchar(20) DEFAULT NULL,
  `level` smallint(1) NOT NULL DEFAULT '9',
  `policy_agree` enum('y','n') NOT NULL DEFAULT 'n',
  `auto_key` varchar(40) DEFAULT NULL,
  `logindate` datetime DEFAULT NULL,
  `joindate` datetime DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `idx` int(10) NOT NULL AUTO_INCREMENT,
  `f_idx` int(10) NOT NULL,
  `m_idx` int(10) NOT NULL,
  `tag` varchar(20) NOT NULL,
  PRIMARY KEY (`idx`),
  KEY `m_idx` (`m_idx`),
  KEY `f_idx` (`f_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
