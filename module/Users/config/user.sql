-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 27, 2014 at 02:13 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zend2`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `logLastAttmp` datetime NOT NULL,
  `logFailedCount` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `address`, `phone`, `logLastAttmp`, `logFailedCount`) VALUES
(1, 'Sanjay Shah', 'sanjay.shah@stigasoft.com', 'e10adc3949ba59abbe56e057f20f883e', 'Gurgaon', '4646445', '0000-00-00 00:00:00', 0),
(7, 'Shuvendu', 'shuvendu@stigasoft.com', 'e10adc3949ba59abbe56e057f20f883e', 'Faridabad', '4654654645', '2014-08-27 12:51:36', 0),
(8, 'Alok.s', 'alok@stigasoft.com', 'e10adc3949ba59abbe56e057f20f883e', 'Gurgaon', '1456456', '0000-00-00 00:00:00', 0),
(9, 'sshah', 'sanjay@stigasoft.com', 'e10adc3949ba59abbe56e057f20f883e', 'Gurgaon', '85698414', '0000-00-00 00:00:00', 0),
(10, 'sanjayk', 'sanjayk@stigasoft.com', 'e10adc3949ba59abbe56e057f20f883e', 'Delhi', '98764814', '0000-00-00 00:00:00', 0),
(11, 'SanjayS', 'sanjay.stigasoft@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Gurgaon', '6546444567', '2014-08-27 10:16:18', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
