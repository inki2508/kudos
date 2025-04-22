-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2025 at 02:39 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kudos`
--

-- --------------------------------------------------------

--
-- Table structure for table `bodega1_stocks`
--

CREATE TABLE `bodega1_stocks` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bodega1_stocks`
--

INSERT INTO `bodega1_stocks` (`id`, `item_name`, `quantity`, `date_added`) VALUES
(1, 'san miguel pilsen', 100, '2025-04-21 22:21:54'),
(2, 'redhorse 1L', 10, '2025-04-21 22:22:29'),
(3, 'redhorse stallion', 28, '2025-04-21 23:05:38');

-- --------------------------------------------------------

--
-- Table structure for table `bodega2_stocks`
--

CREATE TABLE `bodega2_stocks` (
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bodega2_stocks`
--

INSERT INTO `bodega2_stocks` (`item_name`, `quantity`, `date_added`, `id`) VALUES
('redhorse 1L', 34, '2025-04-21 22:28:11', 1),
('san miguel pilsen', 70, '2025-04-21 22:55:04', 3),
('redhorse stallion', 5, '2025-04-21 23:07:02', 7);

-- --------------------------------------------------------

--
-- Table structure for table `counter_stocks`
--

CREATE TABLE `counter_stocks` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `counter_stocks`
--

INSERT INTO `counter_stocks` (`id`, `item_name`, `quantity`, `date_added`) VALUES
(1, 'mani', 33, '2025-04-21 23:16:19'),
(2, 'pancit canton', 57, '2025-04-21 23:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `kitchen_stocks`
--

CREATE TABLE `kitchen_stocks` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kitchen_stocks`
--

INSERT INTO `kitchen_stocks` (`id`, `item_name`, `quantity`, `date_added`) VALUES
(1, 'pork sisig', 40, '2025-04-21 23:32:24'),
(2, 'chicken sisig', 56, '2025-04-21 23:32:38'),
(3, 'fried chicken', 294, '2025-04-21 23:32:56');

-- --------------------------------------------------------

--
-- Table structure for table `office_stocks`
--

CREATE TABLE `office_stocks` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office_stocks`
--

INSERT INTO `office_stocks` (`id`, `item_name`, `quantity`, `date_added`) VALUES
(1, 'bacardi', 22, '2025-04-21 23:14:45');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `location` enum('store','kitchen') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `item_name`, `quantity`, `sale_date`, `location`) VALUES
(1, 'mani', 1, '2025-04-22', 'store'),
(2, 'redhorse 1L', 3, '2025-04-22', 'store'),
(3, 'mani', 1, '2025-04-22', 'store'),
(4, 'pork sisig', 4, '2025-04-22', 'kitchen'),
(5, 'pork sisig', 1, '2025-04-22', 'kitchen'),
(6, 'fried chicken', 46, '2025-04-22', 'kitchen');

-- --------------------------------------------------------

--
-- Table structure for table `store_stocks`
--

CREATE TABLE `store_stocks` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_stocks`
--

INSERT INTO `store_stocks` (`id`, `item_name`, `quantity`, `date_added`) VALUES
(1, 'redhorse 1L', 9, '2025-04-21 23:10:37'),
(2, 'mani', 0, '2025-04-21 23:18:05'),
(3, 'bacardi', 23, '2025-04-21 23:22:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bodega1_stocks`
--
ALTER TABLE `bodega1_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bodega2_stocks`
--
ALTER TABLE `bodega2_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counter_stocks`
--
ALTER TABLE `counter_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kitchen_stocks`
--
ALTER TABLE `kitchen_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office_stocks`
--
ALTER TABLE `office_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_stocks`
--
ALTER TABLE `store_stocks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bodega1_stocks`
--
ALTER TABLE `bodega1_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bodega2_stocks`
--
ALTER TABLE `bodega2_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `counter_stocks`
--
ALTER TABLE `counter_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kitchen_stocks`
--
ALTER TABLE `kitchen_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `office_stocks`
--
ALTER TABLE `office_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `store_stocks`
--
ALTER TABLE `store_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
