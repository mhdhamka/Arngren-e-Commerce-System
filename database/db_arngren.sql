-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2026 at 08:35 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_arngren`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminID` int(255) NOT NULL,
  `adminUsername` varchar(255) NOT NULL,
  `adminPassword` varchar(255) NOT NULL,
  `logStatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `adminUsername`, `adminPassword`, `logStatus`) VALUES
(1, 'admin', 'admin123', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `orderQty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartID`, `userID`, `productID`, `productName`, `orderQty`) VALUES
(0, 2, 2, 'Electric Go-Kart', 2),
(0, 2, 7, 'Torxxer 1:16 Scale Brushless RC Truck', 1),
(0, 2, 1, 'Electric All-Terrain Vehicles (ATV)', 2),
(0, 2, 4, 'Electric T-Truck with open box', 1),
(0, 2, 5, 'Scooter X50', 2),
(0, 1, 2, 'Electric Go-Kart', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `productCtgry` varchar(100) DEFAULT NULL,
  `productQty` int(11) NOT NULL,
  `productPrice` decimal(10,2) NOT NULL,
  `productIMG` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `productName`, `productCtgry`, `productQty`, `productPrice`, `productIMG`) VALUES
(1, 'Electric All-Terrain Vehicles (ATV)', 'Electric Vehicles', 1234, '1670.97', '../../assets/images/ATV.PNG'),
(2, 'Electric Go-Kart', 'Go-Kart', 1365, '1367.18', '../../assets/images/gokart.JPG'),
(3, 'Electric Jeep & Golf Car', 'Jeep', 365, '13674.55', '../../assets/images/jeep.JPG'),
(4, 'Electric T-Truck with open box', 'Electric Vehicles', 328, '18233.16', '../../assets/images/etruck.JPG'),
(5, 'Scooter X50', 'Scooter', 500, '2500.00', '../../assets/images/scooter.JPG'),
(6, 'Sony DVD Player (SR370)', 'DVD-Player', 234, '139.00', '../../assets/images/dvd.JPG'),
(7, 'Torxxer 1:16 Scale Brushless RC Truck', 'Hobby & RC', 579, '7301.00', '../../assets/images/hobby.JPG'),
(8, 'Leica Noctivid 8 X 42 Compact Binoculars', 'Binoculars', 876, '14720.00', '../../assets/images/binocular.JPG');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `orderID` int(255) NOT NULL,
  `userID` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `orderTime` time NOT NULL,
  `subTotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `address` varchar(1000) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`orderID`, `userID`, `orderDate`, `orderTime`, `subTotal`, `total`, `address`, `state`, `city`, `zip`) VALUES
(11, 1, '2026-01-05', '10:35:00', '2500.00', '2650.00', 'No.12 Jalan Merpati', 'Sarawak', 'Kuching', 93000),
(12, 2, '2026-01-08', '14:15:00', '2734.36', '2898.42', 'Lot 25 Taman Desa', 'Sabah', 'Kota Kinabalu', 88000),
(13, 1, '2026-01-12', '09:20:00', '417.00', '442.02', 'Jalan Universiti 5', 'Selangor', 'Shah Alam', 40100),
(14, 2, '2026-01-15', '16:45:00', '7301.00', '7739.06', 'No.8 Jalan Kenanga', 'Johor', 'Johor Bahru', 80000),
(15, 1, '2026-01-18', '11:10:00', '14720.00', '15603.20', 'Taman Harmoni Block B', 'Penang', 'George Town', 10300),
(16, 2, '2026-01-20', '15:30:00', '3341.94', '3542.46', 'Jalan Putra Heights', 'Kuala Lumpur', 'Kuala Lumpur', 50450),
(17, 1, '2026-01-22', '08:55:00', '13674.55', '14494.02', 'No.45 Jalan Mawar', 'Perak', 'Ipoh', 30000),
(18, 2, '2026-01-25', '13:40:00', '18233.16', '19327.15', 'Taman Sri Aman', 'Sarawak', 'Miri', 98000),
(19, 1, '2026-01-28', '17:25:00', '1367.18', '1449.21', 'Jalan Bukit Bintang', 'Kuala Lumpur', 'Kuala Lumpur', 55100),
(20, 2, '2026-02-02', '12:05:00', '5000.00', '5300.00', 'Kampung Baru Road', 'Sarawak', 'Sibu', 96000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `logStatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `fullName`, `password`, `email`, `logStatus`) VALUES
(1, 'Erling Hamka', '$2y$10$v1iEH5xyy9o.rWvcls09AOgtw5awxB/4PPLz/C9CikGh3DoudcJTe', 'erling@gmail.com', 1),
(2, 'Jude Harith', '$2y$10$6f/cjsnAay3DA7crPrrmreUE3jNNGnfdCQQIhDF8Wx3d8SWpvUtWW', 'jude@gmail.com', 0),
(3, 'Lionel Fai', 'abc123', 'messi@gmail.com', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD KEY `userID` (`userID`),
  ADD KEY `cart_ibfk_2` (`productID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `orderID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
