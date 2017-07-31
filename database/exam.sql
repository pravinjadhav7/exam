
CREATE DATABASE `exam`;

USE `exam`;


DROP TABLE IF EXISTS `adminlogin`;

CREATE TABLE `adminlogin` (
  `admname` varchar(32) NOT NULL,
  `admpassword` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`admname`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `adminlogin` VALUES ('root','root');





DROP TABLE IF EXISTS `student`;

CREATE TABLE `student` (
  `stdid` bigint(20) NOT NULL,
  `stdname` varchar(40) DEFAULT NULL,
  `stdpassword` varchar(40) DEFAULT NULL,
  `emailid` varchar(40) DEFAULT NULL,
  `contactno` varchar(20) DEFAULT NULL,
  `address` varchar(40) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`stdid`),
  UNIQUE KEY `stdname` (`stdname`),
  UNIQUE KEY `emailid` (`emailid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;






DROP TABLE IF EXISTS `subject`;

CREATE TABLE `subject` (
  `subid` int(11) NOT NULL,
  `subname` varchar(40) DEFAULT NULL,
  `subdesc` varchar(100) DEFAULT NULL,
  `tcid` bigint(20),
  PRIMARY KEY (`subid`),
  UNIQUE KEY `subname` (`subname`),
  CONSTRAINT `subject_fk1` FOREIGN KEY (`tcid`) REFERENCES `testconductor` (`tcid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `testid` bigint(20) NOT NULL,
  `testname` varchar(30) NOT NULL,
  `testdesc` varchar(100) DEFAULT NULL,
  `testdate` date DEFAULT NULL,
  `testtime` time DEFAULT NULL,
  `subid` int(11) DEFAULT NULL,
  `testfrom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `testto` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `duration` int(11) DEFAULT NULL,
  `totalquestions` int(11) DEFAULT NULL,
  `attemptedstudents` bigint(20) DEFAULT NULL,
  `testcode` varchar(40) NOT NULL,
  `tcid` bigint(20),
  PRIMARY KEY (`testid`),
  UNIQUE KEY `testname` (`testname`),
  CONSTRAINT `test_fk1` FOREIGN KEY (`subid`) REFERENCES `subject` (`subid`),
  CONSTRAINT `test_fk2` FOREIGN KEY (`tcid`) REFERENCES `testconductor` (`tcid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





DROP TABLE IF EXISTS `question`;

CREATE TABLE `question` (
  `testid` bigint(20) NOT NULL DEFAULT '0',
  `qnid` int(11) NOT NULL DEFAULT '0',
  `question` varchar(500) DEFAULT NULL,
  `optiona` varchar(100) DEFAULT NULL,
  `optionb` varchar(100) DEFAULT NULL,
  `optionc` varchar(100) DEFAULT NULL,
  `optiond` varchar(100) DEFAULT NULL,
  `correctanswer` enum('optiona','optionb','optionc','optiond') DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  PRIMARY KEY (`testid`,`qnid`),
  CONSTRAINT `question_ibfk_1` FOREIGN KEY (`testid`) REFERENCES `test` (`testid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





DROP TABLE IF EXISTS `studentquestion`;

CREATE TABLE `studentquestion` (
  `stdid` bigint(20) NOT NULL DEFAULT '0',
  `testid` bigint(20) NOT NULL DEFAULT '0',
  `qnid` int(11) NOT NULL DEFAULT '0',
  `answered` enum('answered','unanswered','review') DEFAULT NULL,
  `stdanswer` enum('optiona','optionb','optionc','optiond') DEFAULT NULL,
  PRIMARY KEY (`stdid`,`testid`,`qnid`),
  KEY `testid` (`testid`,`qnid`),
  CONSTRAINT `studentquestion_ibfk_1` FOREIGN KEY (`stdid`) REFERENCES `student` (`stdid`),
  CONSTRAINT `studentquestion_ibfk_2` FOREIGN KEY (`testid`, `qnid`) REFERENCES `question` (`testid`, `qnid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





DROP TABLE IF EXISTS `studenttest`;

CREATE TABLE `studenttest` (
  `stdid` bigint(20) NOT NULL DEFAULT '0',
  `testid` bigint(20) NOT NULL DEFAULT '0',
  `starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `endtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `correctlyanswered` int(11) DEFAULT NULL,
  `status` enum('over','inprogress') DEFAULT NULL,
  PRIMARY KEY (`stdid`,`testid`),
  KEY `testid` (`testid`),
  CONSTRAINT `studenttest_ibfk_1` FOREIGN KEY (`stdid`) REFERENCES `student` (`stdid`),
  CONSTRAINT `studenttest_ibfk_2` FOREIGN KEY (`testid`) REFERENCES `test` (`testid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tbl_uploads`;
CREATE TABLE IF NOT EXISTS `tbl_uploads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `file` varchar(100) NOT NULL,
  `type` varchar(30) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

