-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2025 at 12:56 PM
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
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `published_year` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `genre`, `published_year`, `created_at`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', 'Classic', 1925, '2025-01-31 13:24:35'),
(2, 'To Kill a Mockingbird', 'Harper Lee', 'Drama', 1960, '2025-01-31 13:25:23'),
(3, '1984', 'George Orwell', 'Dystopian', 1949, '2025-01-31 13:26:01'),
(4, 'Pride and Prejudice', 'Jane Austin', 'Romance', 1813, '2025-01-31 13:26:42'),
(5, 'books132', 'lara', 'fantastic', 2020, '2025-09-03 14:43:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `phone`, `address`) VALUES
(3, 'Jane Smith', 'janesmith@example.com', '4ae95041d151e8997a3dde671bf8ae59', 'user', '2025-01-13 02:08:12', '2456784028', 'Canada'),
(4, 'Aylar Yazmyradova', 'aylarayazmyradova@gmail.com', NULL, NULL, '2025-01-13 15:40:18', '+40738669618', 'Strada Jiului, '),
(8, 'Aylar Yazmyradova', 'aylar.yazmyradova@s.unibuc.ro', NULL, NULL, '2025-01-21 16:14:11', '2456784028', 'Pallady'),
(10, 'Laura', 'orazberdiyevaaylar@gmail.com', NULL, NULL, '2025-01-30 17:33:10', '256365792387', 'Bucharest'),
(12, 'admin_is_me', 'menyazovutailar@gmail.com', '$2y$10$hxN7sWBUYpTupkI3.afUIuTqquRL8DZg6glgVtRJ9Od8oqxkpd4X.', 'user', '2025-01-31 13:42:34', NULL, NULL),
(13, 'Test User', 'test@example.com', '$2y$10$TGnixHKVeLtpvX6jsbqAMuRTZw2POFrTV1xtvd3yUvA7b8hUIPbhW', 'user', '2025-04-15 18:09:25', NULL, NULL),
(19, 'aylar134', 'aylaryaz123@gmail.com', '$2y$10$/ilfYDiRoSgu/mRnoDco2OVVN34e7d1eodTjhBk0nmr3/Pj6cy4Ni', 'user', '2025-09-03 14:42:20', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
