CREATE TABLE `members` (
  `memberID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `First name` varchar(255) NOT NULL,
  `Last name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Secondary Email` varchar(255) NOT NULL,
  `Institution` varchar(255) NOT NULL,
  `Institutional phone` varchar(255) NOT NULL,
  `Personal phone` varchar(255) NOT NULL,
  `ORCID` varchar(255) NOT NULL,
  `Google scholar profile` varchar(255) NOT NULL,
  `ResearchGate profile` varchar(255) NOT NULL,
  `Academia profile` varchar(255) NOT NULL,
  `Personal website` varchar(255) NOT NULL,
  `active` varchar(255) NOT NULL,
  `resetToken` varchar(255) DEFAULT NULL,
  `resetComplete` varchar(3) DEFAULT 'No',
  PRIMARY KEY (`memberID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
