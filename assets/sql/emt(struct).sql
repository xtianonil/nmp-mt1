-- phpMyAdmin SQL Dump
-- version 4.0.10.16
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 26, 2016 at 12:18 PM
-- Server version: 5.5.51
-- PHP Version: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `emt`
--
CREATE DATABASE IF NOT EXISTS `emt` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `emt`;

-- --------------------------------------------------------

--
-- Table structure for table `alarms`
--

DROP TABLE IF EXISTS `alarms`;
CREATE TABLE IF NOT EXISTS `alarms` (
  `alarm_id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_description` varchar(255) DEFAULT NULL,
  `server` varchar(128) DEFAULT NULL,
  `component` varchar(128) DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`alarm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=79 ;

-- --------------------------------------------------------

--
-- Table structure for table `components`
--

DROP TABLE IF EXISTS `components`;
CREATE TABLE IF NOT EXISTS `components` (
  `component_id` int(11) NOT NULL AUTO_INCREMENT,
  `component_name` varchar(128) DEFAULT NULL,
  `seqno` int(11) DEFAULT NULL,
  PRIMARY KEY (`component_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard`
--

DROP TABLE IF EXISTS `dashboard`;
CREATE TABLE IF NOT EXISTS `dashboard` (
  `dashboard_id` int(11) NOT NULL AUTO_INCREMENT,
  `component_id` int(11) NOT NULL,
  `column_id` int(11) NOT NULL,
  `dashboard_date` date NOT NULL,
  `dashboard_value` int(11) NOT NULL,
  PRIMARY KEY (`dashboard_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_column`
--

DROP TABLE IF EXISTS `dashboard_column`;
CREATE TABLE IF NOT EXISTS `dashboard_column` (
  `column_id` int(11) NOT NULL AUTO_INCREMENT,
  `column_name` varchar(20) NOT NULL,
  PRIMARY KEY (`column_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_component`
--

DROP TABLE IF EXISTS `dashboard_component`;
CREATE TABLE IF NOT EXISTS `dashboard_component` (
  `component_id` int(11) NOT NULL AUTO_INCREMENT,
  `component_name` varchar(50) NOT NULL,
  PRIMARY KEY (`component_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `emt_users`
--

DROP TABLE IF EXISTS `emt_users`;
CREATE TABLE IF NOT EXISTS `emt_users` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `u_type_id` int(11) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_blocked` tinyint(4) DEFAULT NULL,
  `is_root` tinyint(4) DEFAULT NULL,
  `last_login_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_ip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `seqno` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `module_functions`
--

DROP TABLE IF EXISTS `module_functions`;
CREATE TABLE IF NOT EXISTS `module_functions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `subfunction` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `pnp_subscriptions`
--

DROP TABLE IF EXISTS `pnp_subscriptions`;
CREATE TABLE IF NOT EXISTS `pnp_subscriptions` (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `column_id` int(11) NOT NULL,
  `subscription_date` date NOT NULL,
  `subscription_value` int(11) NOT NULL,
  PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `pnp_subscriptions_brands`
--

DROP TABLE IF EXISTS `pnp_subscriptions_brands`;
CREATE TABLE IF NOT EXISTS `pnp_subscriptions_brands` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(20) NOT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `pnp_subscriptions_columns`
--

DROP TABLE IF EXISTS `pnp_subscriptions_columns`;
CREATE TABLE IF NOT EXISTS `pnp_subscriptions_columns` (
  `column_id` int(11) NOT NULL AUTO_INCREMENT,
  `column_name` varchar(20) NOT NULL,
  PRIMARY KEY (`column_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

DROP TABLE IF EXISTS `servers`;
CREATE TABLE IF NOT EXISTS `servers` (
  `server_id` int(11) NOT NULL AUTO_INCREMENT,
  `server_type` varchar(128) DEFAULT NULL,
  `server_name` varchar(128) DEFAULT NULL,
  `seqno` int(11) DEFAULT NULL,
  PRIMARY KEY (`server_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `servers_stats`
--

DROP TABLE IF EXISTS `servers_stats`;
CREATE TABLE IF NOT EXISTS `servers_stats` (
  `servers_stats_id` int(10) NOT NULL AUTO_INCREMENT,
  `servers_stats_datetime` datetime DEFAULT NULL,
  `server_name` varchar(128) NOT NULL,
  `idle` double DEFAULT NULL,
  `mem_used` int(11) DEFAULT NULL,
  `buff_used` int(11) DEFAULT NULL,
  `buff_free` int(11) DEFAULT NULL,
  `swap_used` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`servers_stats_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=215906 ;

-- --------------------------------------------------------

--
-- Table structure for table `server_resource_util`
--

DROP TABLE IF EXISTS `server_resource_util`;
CREATE TABLE IF NOT EXISTS `server_resource_util` (
  `sru_id` int(11) NOT NULL AUTO_INCREMENT,
  `sru_value` decimal(10,2) NOT NULL,
  `sru_date` date NOT NULL,
  `server_id` int(11) NOT NULL,
  `column_id` int(11) NOT NULL,
  PRIMARY KEY (`sru_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `server_resource_util_columns`
--

DROP TABLE IF EXISTS `server_resource_util_columns`;
CREATE TABLE IF NOT EXISTS `server_resource_util_columns` (
  `column_id` int(11) NOT NULL AUTO_INCREMENT,
  `column_name` varchar(20) NOT NULL,
  PRIMARY KEY (`column_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `server_resource_util_servers`
--

DROP TABLE IF EXISTS `server_resource_util_servers`;
CREATE TABLE IF NOT EXISTS `server_resource_util_servers` (
  `server_id` int(10) NOT NULL AUTO_INCREMENT,
  `server_name` varchar(20) NOT NULL,
  PRIMARY KEY (`server_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `tat_extract`
--

DROP TABLE IF EXISTS `tat_extract`;
CREATE TABLE IF NOT EXISTS `tat_extract` (
  `tat_extract_id` int(10) NOT NULL AUTO_INCREMENT,
  `extract_date` date NOT NULL,
  `tcserver_name` varchar(20) NOT NULL,
  `component_name` varchar(20) NOT NULL,
  `time_interval` varchar(20) NOT NULL,
  `turnaround_processed` int(11) NOT NULL,
  `turnaround_percentage` decimal(10,2) NOT NULL,
  `record_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tat_extract_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22401 ;

-- --------------------------------------------------------

--
-- Table structure for table `tpm_24hour`
--

DROP TABLE IF EXISTS `tpm_24hour`;
CREATE TABLE IF NOT EXISTS `tpm_24hour` (
  `tpm_24hour_id` int(11) NOT NULL,
  `extract_date` date NOT NULL,
  `tcserver_name` varchar(20) NOT NULL,
  `component_name` varchar(20) NOT NULL,
  `tpm_hour` varchar(20) NOT NULL,
  `transactions` int(11) NOT NULL,
  `record_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

DROP TABLE IF EXISTS `user_logs`;
CREATE TABLE IF NOT EXISTS `user_logs` (
  `u_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `tablename` varchar(255) DEFAULT NULL,
  `operation` varchar(255) DEFAULT NULL,
  `operation_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`u_log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=237 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
CREATE TABLE IF NOT EXISTS `user_types` (
  `u_type_id` int(11) NOT NULL,
  `u_type_name` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`u_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_type_permissions`
--

DROP TABLE IF EXISTS `user_type_permissions`;
CREATE TABLE IF NOT EXISTS `user_type_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_type_id` int(11) NOT NULL,
  `module_function_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
