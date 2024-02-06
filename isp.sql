-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2024 at 12:56 PM
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
  `date_created` datetime NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `userid`, `name`, `email`, `id_passport`, `phone`, `address`, `city`, `issue_type`, `issue`, `sub_issues`, `if_choice_is_other`, `date_created`, `status`) VALUES
(3, 1, 'Saketh Kenchem', 's.kenchem@gmail.com', 'V3735185', '0112716955', 'sasasasa', 'Embakasi', 'Water and Sanitation', 'fffffffffggggggggggggggggggggggggggggg', 'Water Contamination', '', '2024-02-06 14:41:25', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_images`
--

CREATE TABLE `complaint_images` (
  `image_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `complaint_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint_images`
--

INSERT INTO `complaint_images` (`image_id`, `file_path`, `complaint_id`) VALUES
(5, 'uploads/broken pipe.jpg', 3),
(6, 'uploads/garbage on streets.jpg', 3),
(7, 'uploads/no street lights.jpeg', 3),
(8, 'uploads/potholes  on road after rain.jpg', 3);

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

INSERT INTO `posts` (`postid`, `description`, `votes`, `comments`, `username`, `location`, `datecreated`, `severity`, `userid`) VALUES
(9, 'no street lights\r\n', 0, '1', 'Saketh Kenchem', 'Mpaka Road', '2023-11-14 20:42:58', '', 1),
(10, 'saaaaaaaaaa', 1, '0', 'Saketh Kenchem', 'saaaaaaaa', '2024-01-19 19:39:56', '', 1),
(11, 'weeeeee', 0, '0', 'Saketh Kenchem', 'ewwwwww', '2024-02-01 09:16:15', '', 1),
(12, 'ewwwwwwwwww', 0, '0', 'Saketh Kenchem', 'ewwwww', '2024-02-01 09:16:22', '', 1),
(13, 'ttttttttttttttttttttttttttt', 0, '0', 'Saketh Kenchem', 'saaaaaaaa', '2024-02-01 09:18:45', '', 1),
(14, 'ttttttttttttt', 0, '2', 'Saketh Kenchem', 'tttttttttt', '2024-02-01 09:18:52', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `image_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `post_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(19, 1, '2023-12-09 19:00:30', '2024-01-19 10:58:31'),
(20, 1, '2023-12-13 11:05:22', '2024-01-19 10:58:31'),
(21, 1, '2023-12-14 19:22:54', '2024-01-19 10:58:31'),
(22, 1, '2023-12-15 09:54:22', '2024-01-19 10:58:31'),
(23, 1, '2023-12-15 16:08:08', '2024-01-19 10:58:31'),
(24, 1, '2023-12-30 11:20:41', '2024-01-19 10:58:31'),
(25, 1, '2024-01-19 10:54:54', '2024-01-19 10:58:31'),
(26, 1, '2024-01-19 17:28:58', '2024-01-31 15:37:26'),
(27, 1, '2024-01-19 17:39:33', '2024-01-31 15:37:26'),
(28, 1, '2024-01-29 08:52:29', '2024-01-31 15:37:26'),
(29, 1, '2024-01-31 15:32:46', '2024-01-31 15:37:26'),
(30, 1, '2024-01-31 15:37:48', '2024-01-31 15:44:21'),
(31, 1, '2024-01-31 15:44:27', '2024-01-31 16:03:28'),
(32, 1, '2024-01-31 18:51:34', '2024-01-31 18:56:52'),
(33, 1, '2024-01-31 18:57:30', '2024-01-31 18:58:12'),
(34, 1, '2024-01-31 18:58:52', '2024-01-31 19:00:24'),
(35, 1, '2024-01-31 19:03:56', '2024-02-01 06:46:16'),
(36, 1, '2024-02-01 06:24:37', '2024-02-01 06:46:16'),
(37, 1, '2024-02-01 06:45:52', '2024-02-01 06:46:16'),
(38, 1, '2024-02-01 06:47:19', '2024-02-03 10:41:10'),
(39, 1, '2024-02-01 07:02:06', '2024-02-03 10:41:10'),
(40, 1, '2024-02-03 10:30:16', '2024-02-03 10:41:10'),
(41, 1, '2024-02-06 10:54:41', NULL);

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
-- Indexes for table `complaint_images`
--
ALTER TABLE `complaint_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `complaint_images_ibfk_1` (`complaint_id`);

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
-- Indexes for table `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `post_id` (`post_id`);

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
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `complaint_images`
--
ALTER TABLE `complaint_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `officer_credentials`
--
ALTER TABLE `officer_credentials`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `postid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

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
-- Constraints for table `complaint_images`
--
ALTER TABLE `complaint_images`
  ADD CONSTRAINT `complaint_images_ibfk_1` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`complaint_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_user_posts` FOREIGN KEY (`userid`) REFERENCES `userlogincredentials` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `post_images`
--
ALTER TABLE `post_images`
  ADD CONSTRAINT `post_images_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`postid`);

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
