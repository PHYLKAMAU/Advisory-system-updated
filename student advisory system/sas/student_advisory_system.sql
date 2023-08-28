-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2023 at 09:24 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_advisory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(3, 'BED'),
(1, 'SCI'),
(2, 'SON');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `scheduled_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `advisor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `scheduled_on`, `advisor_id`) VALUES
(1, 'Test', 'Testing 123', '2023-08-31 05:46:00', 3),
(2, 'class evaluation', 'hiofwy\r\n', '2023-08-25 06:00:00', 6);

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `scheduled_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `student_id` int(11) NOT NULL,
  `advisor_id` int(11) NOT NULL,
  `accepted` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `title`, `description`, `scheduled_on`, `student_id`, `advisor_id`, `accepted`) VALUES
(6, 'Test', 'This is a test', '2023-08-26 07:13:00', 2, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `name`) VALUES
(1, 'Computing (SCI)'),
(2, 'Eductaion (BED)'),
(3, 'Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_number` varchar(255) DEFAULT NULL,
  `joined_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastaccess` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `registration_number`, `joined_on`, `lastaccess`, `role`, `school_id`, `department_id`) VALUES
(1, 'admin@sas.com', '$2y$10$I48fjvN8MPaz6hRKvx4Wnu2tT81pFzOFH/B.TW8P96hzeqlqCgYbK', NULL, '2023-08-23 16:32:22', '2023-08-24 05:52:43', 'admin', NULL, NULL),
(2, 'bahati@gmail.com', '$2y$10$h1mDU2EiR0HLMGB90tVEqeuq3dJE5VX7CSKOIavOQPkr698FWTw1i', 'CT5001/33/4/56', '2023-08-23 21:13:23', '2023-08-24 06:15:25', 'student', 1, NULL),
(3, 'advisor@gmail.com', '$2y$10$bl/AzCF7MdbIHvXSBvzxW.zho6T0whL7lrJ6cuOXqKe5dNQiTd5Ki', NULL, '2023-08-23 21:20:03', '2023-08-24 06:23:49', 'advisor', NULL, 3),
(4, 'bahatiii@gmail.com', '$2y$10$7Dv3TQzdqarIquS7F/vHjOc6Oe.5o3CvoaaBOpQs.TnO3GFRuozPu', 'CT501/33/4/56', '2023-08-24 00:02:24', '2023-08-24 06:11:29', 'student', 1, NULL),
(5, 'advisorr@gmail.com', '$2y$10$b4IfXbOrriHiQ.UmvEqWaOVwpMRfpeys4A/NpIJMG1hTocURnvo/.', NULL, '2023-08-24 00:04:19', '2023-08-23 23:04:28', 'advisor', NULL, 1),
(6, 'advisorrr@gmail.com', '$2y$10$d1MIeiUnL1lhfbhYbp6zzedJqVE6zQvqVnCuAcvFnZZaEu6j/7fa6', NULL, '2023-08-24 06:54:06', '2023-08-24 05:54:17', 'advisor', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advisor_event_fk` (`advisor_id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_fk` (`student_id`),
  ADD KEY `advisor_fk` (`advisor_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `advisor_event_fk` FOREIGN KEY (`advisor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `advisor_fk` FOREIGN KEY (`advisor_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_fk` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
