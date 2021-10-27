-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 26, 2021 at 06:35 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `backupmodule`
--

-- --------------------------------------------------------

--
-- Table structure for table `autobackup`
--

CREATE TABLE `autobackup` (
  `user_level` int(2) NOT NULL DEFAULT 1,
  `admin_enable_backup` int(2) NOT NULL DEFAULT 0,
  `backup_type` varchar(50) DEFAULT NULL,
  `backup_time` varchar(50) DEFAULT NULL,
  `backup_path` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `autobackup`
--

INSERT INTO `autobackup` (`user_level`, `admin_enable_backup`, `backup_type`, `backup_time`, `backup_path`) VALUES
(1, 0, NULL, '00:01', 'AAAREPO');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
