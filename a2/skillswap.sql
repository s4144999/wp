-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2025 at 01:50 AM
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
-- Database: `skillswap`
--

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `rate_per_hr` decimal(8,2) NOT NULL,
  `level` enum('Beginner','Intermediate','Expert') NOT NULL DEFAULT 'Intermediate',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `title`, `description`, `category`, `image_path`, `rate_per_hr`, `level`, `created_at`) VALUES
(1, 'Beginner Guitar Lessons', 'Learn the basics of guitar playing.', 'Music', '1.png', 30.00, 'Beginner', '2025-09-23 00:06:27'),
(2, 'Intermediate Fingerstyle', 'Master fingerstyle guitar techniques.', 'Music', '2.png', 45.00, 'Intermediate', '2025-09-23 00:06:27'),
(3, 'Artisan Bread Baking', 'Learn to bake artisan sourdough and breads.', 'Cooking', '3.png', 25.00, 'Beginner', '2025-09-23 00:06:27'),
(4, 'French Pastry Making', 'Master the art of French pastry making.', 'Cooking', '4.png', 50.00, 'Expert', '2025-09-23 00:06:27'),
(5, 'Watercolor Basics', 'Introduction to watercolor painting.', 'Art', '5.png', 20.00, 'Beginner', '2025-09-23 00:06:27'),
(6, 'Digital Illustration with Procreate', 'Create stunning illustrations using Procreate.', 'Art', '6.png', 40.00, 'Intermediate', '2025-09-23 00:06:27'),
(7, 'Morning Vinyasa Flow', 'A refreshing yoga flow for mornings.', 'Wellness', '7.png', 35.00, 'Intermediate', '2025-09-23 00:06:27'),
(8, 'Intro to PHP & MySQL', 'Get started with backend development.', 'Programming', '8.png', 55.00, 'Expert', '2025-09-23 00:06:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
