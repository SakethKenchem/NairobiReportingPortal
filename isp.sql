-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2024 at 06:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_credentials`
--

CREATE TABLE `admin_credentials` (
  `adminid` int(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_credentials`
--

INSERT INTO `admin_credentials` (`adminid`, `username`, `password`) VALUES
(1, 'Saketh', '$2y$10$5LHbZbDX/fyDq3A1z5T5SOur2Xa7P4l5dNCzh25Q/G8oUfqUlupUC');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `postid`, `userid`, `username`, `comment`, `timestamp`) VALUES
(8, 9, 1, 'Saketh Kenchem', 'hi', '2023-11-15 06:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_passport` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `city` varchar(100) NOT NULL,
  `issue_type` varchar(100) NOT NULL,
  `issue` text NOT NULL,
  `sub_issues` varchar(255) NOT NULL,
  `if_choice_is_other` varchar(255) NOT NULL,
  `image_path` text DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `userid`, `name`, `email`, `id_passport`, `phone`, `address`, `city`, `issue_type`, `issue`, `sub_issues`, `if_choice_is_other`, `image_path`, `date_created`, `status`) VALUES
(1, 1, 'Saketh Kenchem', 's.kenchem@gmail.com', 'V3735185', '0112716955', '07, Jubilee Apartments, Mpaka Road', 'Westlands', 'Roads and Related Issues', 'there are very many potholes', 'Potholes', '', 'uploads/potholes on road after rain.jpg', '2023-11-17 17:25:35', 'received'),
(2, 1, 'Saketh Kenchem', 's.kenchem@gmail.com', 'V3735185', '0112716955', '07, Jubilee Apartments, Mpaka Road', 'Parklands', 'Roads and Related Issues', 'there are water leakage', 'Water Leakage', '', 'uploads/broken pipe.jpg', '2023-11-17 17:30:42', 'underway'),
(3, 1, 'Saketh Kenchem', 'saketh.kenchem@strathmore.edu', 'V3735185', '0112716955', '07, Jubilee Apartments, Mpaka Road', 'Githurai', 'Water and Sanitation', 'there is water leakage', 'Water Leakage', '', 'uploads/broken pipe.jpg', '2023-11-18 09:10:30', 'underway');

-- --------------------------------------------------------

--
-- Table structure for table `officer_credentials`
--

CREATE TABLE `officer_credentials` (
  `id` int(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `phonenumber` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officer_credentials`
--

INSERT INTO `officer_credentials` (`id`, `username`, `phonenumber`, `password`) VALUES
(1, 'Saketh', '0112716955', '$2y$10$wd6a0jg5Pcv3us8HLRTg7etsybT8724.Vw1juEVD17CjzDZO2J6Pm');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `postid` int(50) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `votes` int(250) DEFAULT 0,
  `comments` text DEFAULT '0',
  `username` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `datecreated` datetime NOT NULL,
  `severity` text NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postid`, `image_path`, `description`, `votes`, `comments`, `username`, `location`, `datecreated`, `severity`, `userid`) VALUES
(9, 'postsImages/no street lights.jpeg', 'no street lights\r\n', 0, '1', 'Saketh Kenchem', 'Mpaka Road', '2023-11-14 20:42:58', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reports_to_admin`
--

CREATE TABLE `reports_to_admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userlogincredentials`
--

CREATE TABLE `userlogincredentials` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `national_id` varchar(20) NOT NULL,
  `phoneNumber` varchar(10) NOT NULL,
  `is_blocked` int(11) NOT NULL,
  `account_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `security_phrase_or_digit` varchar(50) NOT NULL,
  `profile_photo` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlogincredentials`
--

INSERT INTO `userlogincredentials` (`userid`, `username`, `email`, `password`, `national_id`, `phoneNumber`, `is_blocked`, `account_created_at`, `security_phrase_or_digit`, `profile_photo`) VALUES
(1, 'Saketh Kenchem', 's.kenchem@gmail.com', '$2y$10$xGNz2WL9eT0iMTXjoPKP8.TBd2/NZCT1nwOocK.fhw4inEUESYE0S', 'V3735185', '0112716955', 0, '2023-11-07 21:31:06', 'sakethkenchem', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userloginhistory`
--

CREATE TABLE `userloginhistory` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userloginhistory`
--

INSERT INTO `userloginhistory` (`id`, `userid`, `login_time`, `logout_time`) VALUES
(1, 1, '2023-11-16 09:59:50', '2023-11-16 10:20:16'),
(2, 1, '2023-11-16 10:20:22', '2023-11-26 16:33:08'),
(3, 1, '2023-11-17 11:29:17', '2023-11-26 16:33:08'),
(4, 1, '2023-11-17 14:12:31', '2023-11-26 16:33:08'),
(5, 1, '2023-11-17 15:30:08', '2023-11-26 16:33:08'),
(6, 1, '2023-11-18 07:09:46', '2023-11-26 16:33:08'),
(7, 1, '2023-11-18 10:07:40', '2023-11-26 16:33:08'),
(8, 1, '2023-11-23 11:27:56', '2023-11-26 16:33:08'),
(9, 1, '2023-11-25 07:54:48', '2023-11-26 16:33:08'),
(10, 1, '2023-11-26 16:31:09', '2023-11-26 16:33:08'),
(11, 1, '2023-11-26 19:48:27', '2023-11-26 19:49:45'),
(12, 1, '2023-11-26 19:58:19', '2023-11-26 19:58:30'),
(13, 1, '2023-11-26 20:11:44', '2023-12-09 18:51:46'),
(14, 1, '2023-11-27 19:34:07', '2023-12-09 18:51:46'),
(15, 1, '2023-11-28 11:05:01', '2023-12-09 18:51:46'),
(16, 1, '2023-11-30 13:58:02', '2023-12-09 18:51:46'),
(17, 1, '2023-12-09 18:24:32', '2023-12-09 18:51:46'),
(18, 1, '2023-12-09 18:51:52', '2023-12-09 19:00:17'),
(19, 1, '2023-12-09 19:00:30', NULL),
(20, 1, '2023-12-13 11:05:22', NULL),
(21, 1, '2023-12-14 19:22:54', NULL),
(22, 1, '2023-12-15 09:54:22', NULL),
(23, 1, '2023-12-15 16:08:08', NULL),
(24, 1, '2023-12-30 11:20:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT (current_timestamp() + interval 10 minute)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_credentials`
--
ALTER TABLE `admin_credentials`
  ADD PRIMARY KEY (`adminid`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `postid` (`postid`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `officer_credentials`
--
ALTER TABLE `officer_credentials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`postid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `reports_to_admin`
--
ALTER TABLE `reports_to_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_reports_to_admin_userid` (`userid`);

--
-- Indexes for table `userlogincredentials`
--
ALTER TABLE `userlogincredentials`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `userloginhistory`
--
ALTER TABLE `userloginhistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `expires_at_index` (`expires_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_credentials`
--
ALTER TABLE `admin_credentials`
  MODIFY `adminid` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `officer_credentials`
--
ALTER TABLE `officer_credentials`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `postid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reports_to_admin`
--
ALTER TABLE `reports_to_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userlogincredentials`
--
ALTER TABLE `userlogincredentials`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `userloginhistory`
--
ALTER TABLE `userloginhistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_postid` FOREIGN KEY (`postid`) REFERENCES `posts` (`postid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_userid` FOREIGN KEY (`userid`) REFERENCES `userlogincredentials` (`userid`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES `userlogincredentials` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_user_posts` FOREIGN KEY (`userid`) REFERENCES `userlogincredentials` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `userloginhistory`
--
ALTER TABLE `userloginhistory`
  ADD CONSTRAINT `fk_userloginhistory_userid` FOREIGN KEY (`userid`) REFERENCES `userlogincredentials` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD CONSTRAINT `verification_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userlogincredentials` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
