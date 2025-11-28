-- Fix SQL Import Issues
-- Run this before importing your main SQL file

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Increase import limits
SET SESSION max_allowed_packet = 1073741824; -- 1GB
SET SESSION wait_timeout = 28800; -- 8 hours
SET SESSION interactive_timeout = 28800;

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `ppdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ppdb`;

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;