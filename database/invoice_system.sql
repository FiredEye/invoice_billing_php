-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2023 at 12:30 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invoice_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `number` varchar(50) NOT NULL,
  `address` varchar(225) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `signature` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `email`, `number`, `address`, `logo`, `signature`, `password`) VALUES
(1, 'Sarswoti Enterprises PVT LTD.', '', '9876543210,01-4561234', 'Tinkune, Kathmandu', '../companyImg/logo.jpg', '../companyImg/stamp1.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `recorded_by` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` varchar(60) NOT NULL,
  `phone_no` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `recorded_by`, `first_name`, `last_name`, `address`, `phone_no`, `email`) VALUES
(4, 4, 'jhonny', 'depp', 'japan', '9807454412', 'jhonny@depp.com'),
(8, 4, 'gagan', 'thapa', 'nepal', '9800000012', 'g@thapa.com'),
(10, 4, 'binita', 'benni', 'britain', '9111864888', 'lama@nn.com'),
(12, 5, 'ujjwal', 'Shrestha', 'chitwan', '9876543214', 'wproj4@gmail.com'),
(20, 5, 'ram', 'pandey', 'birjung', '9876543210', 'ram$$pandey@gmail.com'),
(21, 5, 'yashu', 'sthapit', 'dhalko', '9876543210', 'yashhapit@gmail.com'),
(22, 5, 'anish', 'dangol', 'dharan', '9876543210', 'anish@dangol.com'),
(23, 5, 'gagan', 'thapa', 'nepal', '9800000011', 'thapa@gagan.com'),
(24, 5, 'farhan', 'husen', 'chitwan', '9879876540', 'husen@farhan.com'),
(25, 5, 'Sita', 'Paudel', 'birjung', '9879876541', 'paudel@sita.com');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_order`
--

CREATE TABLE `invoice_order` (
  `order_id` int(11) NOT NULL,
  `recorded_by` varchar(100) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` date DEFAULT NULL,
  `order_receiver_id` varchar(250) NOT NULL,
  `order_total_before_tax` int(10) NOT NULL,
  `order_total_tax` decimal(10,2) NOT NULL,
  `order_tax_per` varchar(250) NOT NULL,
  `order_total_after_tax` int(10) NOT NULL,
  `order_amount_paid` int(10) NOT NULL,
  `order_total_amount_due` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice_order`
--

INSERT INTO `invoice_order` (`order_id`, `recorded_by`, `order_date`, `due_date`, `order_receiver_id`, `order_total_before_tax`, `order_total_tax`, `order_tax_per`, `order_total_after_tax`, `order_amount_paid`, `order_total_amount_due`) VALUES
(1, '4', '2022-08-15 14:43:09', '2022-08-15', '10', 200, '0.00', '', 200, 200, 0),
(2, '4', '2022-08-16 03:06:59', '2022-08-16', '22', 200, '0.00', '', 200, 190, 10),
(3, '5', '2022-08-16 08:58:45', '2022-08-17', '20', 340, '0.00', '', 340, 340, 0),
(4, '5', '2022-08-16 10:11:30', '2022-08-17', '23', 110, '0.00', '', 110, 110, 0),
(5, '5', '2022-08-17 11:02:51', '2022-08-17', '20', 1000, '0.00', '', 1000, 430, 570),
(6, '5', '2022-08-17 12:41:42', '2022-08-17', '24', 340, '0.00', '', 340, 340, 0),
(7, '5', '2022-08-17 14:54:45', '2022-08-17', '20', 110, '0.00', '', 110, 110, 0),
(8, '5', '2022-08-18 06:29:28', '2022-08-18', '25', 4000, '0.00', '', 4000, 0, 4000),
(9, '5', '2022-08-12 08:12:52', '2022-08-16', '12', 200, '0.00', '', 200, 0, 200),
(10, '5', '2022-08-13 15:00:13', '2022-08-15', '25', 100, '0.00', '', 100, 100, 0),
(11, '5', '2022-08-21 14:34:09', '2022-08-23', '23', 2500, '0.00', '', 2500, 0, 2500),
(12, '5', '2022-08-21 20:10:42', '2022-08-22', '24', 600, '0.00', '', 600, 1, 599),
(14, '5', '2022-08-22 10:13:31', '2022-08-23', '24', 500, '0.00', '', 500, 500, 0),
(15, '5', '2022-08-22 10:14:01', '2022-08-24', '23', 200, '0.00', '', 200, 0, 200),
(16, '5', '2022-08-22 12:50:08', '2022-08-22', '23', 770, '0.00', '', 770, 0, 770),
(17, '5', '2022-08-30 07:50:43', '2022-08-31', '22', 2505, '0.00', '', 2505, 2340, 165),
(18, '4', '2022-12-05 08:06:54', '2022-12-06', '4', 880, '0.00', '', 880, 874, 6),
(19, '5', '2022-12-05 14:52:35', '2022-12-06', '20', 700, '0.00', '', 700, 699, 1);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_order_item`
--

CREATE TABLE `invoice_order_item` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_code` varchar(250) NOT NULL,
  `item_name` varchar(250) NOT NULL,
  `order_item_quantity` int(11) NOT NULL,
  `order_item_price` decimal(10,2) NOT NULL,
  `order_item_final_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice_order_item`
--

INSERT INTO `invoice_order_item` (`order_item_id`, `order_id`, `item_code`, `item_name`, `order_item_quantity`, `order_item_price`, `order_item_final_amount`) VALUES
(1, 1, '112', 'large cup', 1, '200.00', '200.00'),
(6, 2, '112', 'large cup', 1, '200.00', '200.00'),
(25, 5, '112', 'large cup', 1, '200.00', '200.00'),
(26, 5, '107', 'pen', 1, '30.00', '30.00'),
(27, 5, '111', 'cup', 7, '110.00', '770.00'),
(29, 3, '114', 'large plate', 1, '90.00', '90.00'),
(30, 3, '111', 'cup', 2, '110.00', '220.00'),
(31, 3, '107', 'pen', 1, '30.00', '30.00'),
(32, 4, '111', 'cup', 1, '110.00', '110.00'),
(33, 6, '106', 'small book', 1, '340.00', '340.00'),
(34, 7, '111', 'cup', 1, '110.00', '110.00'),
(35, 8, '103', 'candle', 1, '500.00', '500.00'),
(36, 8, '118', 'leather belt', 10, '350.00', '3500.00'),
(37, 9, '112', 'large cup', 1, '200.00', '200.00'),
(38, 10, '120', 'pencil box', 1, '100.00', '100.00'),
(39, 11, '103', 'candle', 5, '500.00', '2500.00'),
(40, 12, '113', 'plate', 12, '50.00', '600.00'),
(42, 14, '103', 'candle', 1, '500.00', '500.00'),
(43, 15, '112', 'large cup', 1, '200.00', '200.00'),
(44, 16, '103', 'candle', 1, '500.00', '500.00'),
(45, 16, '104', 'magnet', 1, '220.00', '220.00'),
(46, 16, '113', 'plate', 1, '50.00', '50.00'),
(71, 17, '103', 'candle', 1, '500.00', '500.00'),
(72, 17, '104', 'magnet', 1, '220.00', '220.00'),
(73, 17, '105', 'large books', 1, '900.00', '900.00'),
(74, 17, '106', 'small book', 1, '340.00', '340.00'),
(75, 17, '107', 'pen', 1, '30.00', '30.00'),
(76, 17, '112', 'large cup', 1, '200.00', '200.00'),
(77, 17, '111', 'cup', 1, '110.00', '110.00'),
(78, 17, '102', 'hat', 1, '205.00', '205.00'),
(79, 18, '104', 'magnet', 4, '220.00', '880.00'),
(80, 19, '118', 'leather belt', 2, '350.00', '700.00');

--
-- Triggers `invoice_order_item`
--
DELIMITER $$
CREATE TRIGGER `New_add` AFTER INSERT ON `invoice_order_item` FOR EACH ROW BEGIN
UPDATE items SET quantity=quantity-new.order_item_quantity
WHERE code=new.item_code;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Update_After` AFTER UPDATE ON `invoice_order_item` FOR EACH ROW BEGIN
UPDATE items SET quantity=quantity+old.order_item_quantity
WHERE code=old.item_code;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Update_Before` BEFORE UPDATE ON `invoice_order_item` FOR EACH ROW BEGIN
UPDATE items SET quantity=quantity-new.order_item_quantity
WHERE code=new.item_code;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete` AFTER DELETE ON `invoice_order_item` FOR EACH ROW BEGIN
UPDATE items SET quantity=quantity+old.order_item_quantity
WHERE code=old.item_code;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_users`
--

CREATE TABLE `invoice_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `salary` varchar(255) NOT NULL,
  `image` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `branch` varchar(225) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cpassword` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invoice_users`
--

INSERT INTO `invoice_users` (`id`, `username`, `email`, `mobile`, `address`, `salary`, `image`, `role`, `branch`, `password`, `cpassword`, `token`, `status`) VALUES
(1, 'Gagan Thapa', 'wpro5@gmail.com', '9876543211', 'Kathmandu,Nepal', '', '../img/defaultProfile.jpg', 'Admin', 'main', '$2y$10$/CjeeA1OkpJKNEBXOlNZ9uzaEZMqJ/P6dR4axeLLm.4Bc.5JRjgUm', '$2y$10$RDIA4r1kr5K3V5gzl/P.uOev6RS6QEPh3xcuG0Wia0IQrGDMRZZPy', 'a31122c521906b6b4f5c02b4aae1e6', 'active'),
(4, 'Ram Yadav', 'dhar@gmail.com', '9876543212', 'Chitwan', '12000', '../img/profile.webp', 'SalesPerson', 'Maharajgunj', '$2y$10$DQE/299N9DSK2I1VZvcWROHOh/rejQHd/ZnB/K4qPEe0mbzPyn3my', '$2y$10$sn53RvMjE1BIG30cdRXmE.0NwkrIDuuNQMQVTsV.3PwjpNwZifl4C', 'e3d49330f9d8242e4d8b861a0e00a9', 'active'),
(5, 'Ganesh Shrestha', 'ganesh@stha.com', '9876543210', 'Jhapa', '12000', '../img/stamp1.jpg', 'SalesPerson', 'Durbarmarga', ' $2y$10$qWfOTptImhv2Zh6KRfaMV.7H2FXN.sktNIM3ziWF81R2J4DsSRq/2', ' $2y$10$qmkghpKk0TDV/IP6hw4Lo.hpRDa5O.aMjn/YZzwtWAZ4v5tdUf0je', 'f4f43245c8364543edd4496ad72752', 'active'),
(14, 'Subin Neraula', 'wpro44@gmail.com', '9777564654', 'Jhapa', '13000', '../img/pexels-christina-morillo-1181391.jpg', 'SalesPerson', 'Maitidevi', '$2y$10$/YCmUjSHosz.KaES2PCSouQEPXlZTNpA7AD614cYZE/UihMGdhlja', '$2y$10$cJZ2WqEsrxfPxp8dbXQ66utQHA8XfF9iOd0u61zPy4M75pE5kTdQK', '8635378d281f049e9539e14afe261e', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `code` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `price` int(225) NOT NULL,
  `quantity` int(225) NOT NULL,
  `min_quantity` int(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`code`, `name`, `price`, `quantity`, `min_quantity`) VALUES
(102, 'hat', 205, 1002, 150),
(103, 'candle', 490, 501, 500),
(104, 'magnet', 220, 1097, 1000),
(105, 'large books', 900, 6087, 2000),
(106, 'small book', 340, 2990, 200),
(107, 'pen', 30, 6007, 350),
(111, 'cup', 110, 6318, 400),
(112, 'large cup', 200, 6140, 400),
(113, 'plate', 50, 6137, 300),
(114, 'large plate', 90, 6012, 200),
(118, 'leather belt', 350, 19, 20),
(119, 'bottle', 150, 31, 10),
(120, 'pencil box', 100, 6499, 300),
(121, 'sketch book', 190, 4300, 200);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `message` varchar(225) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `from_id`, `to_id`, `message`, `time`, `status`) VALUES
(1, 5, 1, 'Added new Item/s.', '2022-08-21 08:11:41', 1),
(2, 5, 5, 'Added new Item/s.', '2022-08-21 08:11:38', 1),
(3, 5, 1, 'Inserted new Invoice No:11.', '2022-08-21 20:09:39', 1),
(4, 5, 5, 'Inserted new Invoice No:11.', '2022-08-21 14:34:19', 1),
(5, 5, 1, 'Inserted new Invoice No:12.', '2022-08-21 20:10:48', 1),
(6, 5, 5, 'Inserted new Invoice No:12.', '2022-08-21 20:12:34', 1),
(7, 5, 1, 'Inserted new Invoice No:13.', '2022-08-22 04:18:55', 1),
(8, 5, 5, 'Inserted new Invoice No:13.', '2022-08-22 04:10:32', 1),
(9, 5, 1, 'Updated details of customer id:12.', '2022-08-22 04:53:33', 1),
(10, 5, 5, 'Updated details of customer id:12.', '2022-08-22 04:53:41', 1),
(11, 5, 1, 'Inserted new Invoice No:14.', '2022-08-22 10:14:13', 1),
(12, 5, 5, 'Inserted new Invoice No:14.', '2022-08-22 10:14:17', 1),
(13, 5, 1, 'Inserted new Invoice No:15.', '2022-08-22 10:14:13', 1),
(14, 5, 5, 'Inserted new Invoice No:15.', '2022-08-22 10:14:17', 1),
(15, 5, 1, 'Payment of Rs.27 by Customer: farhan husen.', '2022-08-22 10:15:38', 1),
(16, 5, 5, 'Payment of Rs.27 by Customer: farhan husen.', '2022-08-22 10:15:36', 1),
(17, 5, 1, 'Inserted new Invoice No:16.', '2022-08-22 17:12:09', 1),
(18, 5, 5, 'Inserted new Invoice No:16.', '2022-08-22 17:32:02', 1),
(19, 5, 1, 'Payment of Rs.500 by Customer: farhan husen.', '2022-09-09 10:12:52', 1),
(20, 5, 5, 'Payment of Rs.500 by Customer: farhan husen.', '2022-08-25 15:47:52', 1),
(21, 5, 1, 'Inserted new Invoice No:17.', '2022-09-09 10:12:52', 1),
(22, 5, 5, 'Inserted new Invoice No:17.', '2022-08-30 07:51:24', 1),
(23, 5, 1, 'Updated Invoice No:17.', '2022-09-09 10:12:52', 1),
(24, 5, 5, 'Updated Invoice No:17.', '2022-08-30 07:59:24', 1),
(25, 5, 1, 'Updated Invoice No:17.', '2022-09-09 10:12:52', 1),
(26, 5, 5, 'Updated Invoice No:17.', '2022-08-30 07:59:24', 1),
(27, 5, 1, 'Deleted Invoice No:13.', '2022-09-09 10:12:52', 1),
(28, 5, 5, 'Deleted Invoice No:13.', '2022-08-30 11:40:29', 1),
(29, 5, 1, 'Deleted details of Item code:2343.', '2022-09-09 10:12:52', 1),
(30, 5, 5, 'Deleted details of Item code:2343.', '2022-08-30 13:05:50', 1),
(31, 5, 1, 'Updated details of item code:103.', '2022-09-09 10:12:52', 1),
(32, 5, 5, 'Updated details of item code:103.', '2022-08-30 14:19:15', 1),
(33, 5, 1, 'Updated details of item code:103.', '2022-09-09 10:12:52', 1),
(34, 5, 5, 'Updated details of item code:103.', '2022-08-30 16:08:03', 1),
(35, 5, 1, 'Added new Item/s.', '2022-09-09 10:12:52', 1),
(36, 5, 5, 'Added new Item/s.', '2022-08-30 16:09:18', 1),
(37, 5, 1, 'Added new Item/s.', '2022-09-09 10:12:52', 1),
(38, 5, 5, 'Added new Item/s.', '2022-08-30 16:10:23', 1),
(39, 5, 1, 'Updated details of item code:102.', '2022-09-09 10:12:52', 1),
(40, 5, 5, 'Updated details of item code:102.', '2022-08-30 16:10:23', 1),
(41, 5, 1, 'Updated Invoice No:17.', '2022-09-09 10:12:52', 1),
(42, 5, 5, 'Updated Invoice No:17.', '2022-08-30 16:38:40', 1),
(43, 4, 1, 'Inserted new Invoice No:18.', '2022-12-05 08:07:03', 1),
(44, 4, 4, 'Inserted new Invoice No:18.', '2022-12-05 08:07:16', 1),
(45, 4, 1, 'Payment of Rs.430 by Customer: jhonny depp.', '2022-12-05 08:08:48', 1),
(46, 4, 4, 'Payment of Rs.430 by Customer: jhonny depp.', '2022-12-05 08:08:36', 1),
(47, 5, 1, 'Payment of Rs.100 by Customer: Sita Paudel.', '2022-12-05 14:22:05', 1),
(48, 5, 5, 'Payment of Rs.100 by Customer: Sita Paudel.', '2022-12-05 14:24:38', 1),
(49, 5, 1, 'Inserted new Invoice No:19.', '2022-12-05 14:54:14', 1),
(50, 5, 5, 'Inserted new Invoice No:19.', '2022-12-05 14:52:40', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_order`
--
ALTER TABLE `invoice_order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `invoice_order_item`
--
ALTER TABLE `invoice_order_item`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `invoice_users`
--
ALTER TABLE `invoice_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `invoice_order`
--
ALTER TABLE `invoice_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `invoice_order_item`
--
ALTER TABLE `invoice_order_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `invoice_users`
--
ALTER TABLE `invoice_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
