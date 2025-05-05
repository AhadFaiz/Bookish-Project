-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 05:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookish`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Customer','Admin') DEFAULT 'Customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `role`) VALUES
(11, 'Reema', 'Reema@hotmail.com', '1234', 'Admin'),
(12, 'Noor', 'Noor@hotmail.com', '0000', 'Admin'),
(13, 'Deena', 'Deena@hotmail.com', '112233', 'Admin'),
(14, 'Linah', 'Linah@hotmail.com', '123456', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(1, 'Fredrik Backman'),
(2, 'Khaled Hosseini'),
(3, 'Colleen Hoover'),
(4, 'Alex Michaelides'),
(5, 'Sahil Bloom'),
(6, 'Sarah Wynn-Williams'),
(7, 'Agatha Christie'),
(8, 'George Orwell'),
(9, 'Emma Donoghue');

-- --------------------------------------------------------
--  table `product`
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `title`, `author_id`, `category`, `price`, `image_name`, `stock`, `description`) VALUES
(1, 'A Man Called Ove', 1, 'Contemporary Fiction / Drama', 55.00, 'img_1.jpg', 10, 'A heartwarming story about an old man rediscovering life.'),
(2, 'The Kite Runner', 2, 'Contemporary Fiction / Drama', 61.00, 'img_2.jpg', 10, ' A powerful story of friendship, betrayal, and redemption.'),
(3, 'It Ends with Us', 3, 'Contemporary Fiction / Drama', 42.55, 'img_3.jpg', 14, ' A thought-provoking exploration of relationships and resilience.'),
(4, 'The Silent Patient	', 4, 'Mystery / Thriller', 98.36, 'img_4.jpg', 10, 'A practical guide to building good habits and breaking bad ones.'),
(5, 'The 5 Types of Wealth', 5, 'Non-fiction / Self-help\r\n', 78.85, 'img_5.jpg', 10, ' A dystopian novel about totalitarianism and surveillance.'),
(6, 'Careless People', 6, 'Non-fiction / Self-help', 86.36, 'img_6.jpg', 10, ' A satirical allegory about power and corruption.'),
(7, 'And Then There Were None', 7, 'Mystery / Thriller', 42.00, 'img_7.jpg', 10, 'mystery novel by Agatha Christie where ten strangers are invited to an isolated island and are killed one by one, with no way to escape and no clear killer.'),
(8, 'Nineteen Eighty-Four', 8, 'Political Fiction', 43.31, 'img_9.jpg', 10, 'dystopian novel about a totalitarian regime that uses surveillance, propaganda, and control to oppress its people, as one man secretly rebels against it.'),
(9, 'Room', 9, 'Contemporary Fiction / Drama', 107.00, 'img_10.jpg', 10, 'A gripping tale of survival and resilience in a confined space.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
