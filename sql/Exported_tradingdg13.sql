-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2024 at 03:09 PM
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
-- Database: `tradingdg13`
--

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `username` varchar(10) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `buy_sell` varchar(50) NOT NULL,
  `trade_type` varchar(50) NOT NULL,
  `stockPrice` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `totalprice` varchar(50) NOT NULL,
  `createdAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `username`, `identifier`, `buy_sell`, `trade_type`, `stockPrice`, `quantity`, `totalprice`, `createdAt`) VALUES
(1, 'joshi37y', 'MSFT', 'Buy', 'Market Open', '228', 2, '456', '2024-09-13 19:25:25'),
(2, 'joshi37y', 'APPL', 'Buy', 'Market Open', '175', 5, '975', '2024-09-16 19:27:26'),
(3, 'joshi37y', 'TSLA', 'Buy', 'Market Open', '300', 3, '900', '2024-09-16 19:27:54');

-- --------------------------------------------------------

--
-- Table structure for table `userportfolio`
--

CREATE TABLE `userportfolio` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `asset_id` varchar(50) NOT NULL,
  `quantity` varchar(50) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userportfolio`
--

INSERT INTO `userportfolio` (`id`, `username`, `asset_id`, `quantity`, `price`) VALUES
(1, 'joshi37y', 'MSFT', '50', 20000),
(2, 'joshi37y', 'AMGEN', '100', 10000),
(3, 'joshi37y', 'META', '12', 3000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `firstName`, `lastName`, `email`) VALUES
(2, 'beth3921', '3a94b52c80c81b14917d92c0f2d68f23', 'Beth', 'Chang', 'beth374278@gmail.com'),
(3, 'masie92839', '3a94b52c80c81b14917d92c0f2d68f23', 'Masie', 'Chia', 'masie924384@gmail.com'),
(5, 'joshi37y', '3a94b52c80c81b14917d92c0f2d68f23', 'Josh', 'Hill', 'josh2987@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `virtualwallet`
--

CREATE TABLE `virtualwallet` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `balance` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `virtualwallet`
--

INSERT INTO `virtualwallet` (`id`, `username`, `balance`) VALUES
(1, 'joshi37y', '500000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userportfolio`
--
ALTER TABLE `userportfolio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `virtualwallet`
--
ALTER TABLE `virtualwallet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `userportfolio`
--
ALTER TABLE `userportfolio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `virtualwallet`
--
ALTER TABLE `virtualwallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
