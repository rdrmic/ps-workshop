-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2018 at 06:20 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 5.6.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zajednic_psw`
--
CREATE DATABASE IF NOT EXISTS `zajednic_psw` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `zajednic_psw`;

-- --------------------------------------------------------

--
-- Table structure for table `biscuit`
--

DROP TABLE IF EXISTS `biscuit`;
CREATE TABLE `biscuit` (
  `id` int(11) NOT NULL,
  `code` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `name` varchar(64) COLLATE latin2_croatian_ci NOT NULL,
  `note` varchar(256) COLLATE latin2_croatian_ci DEFAULT NULL,
  `work_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `materials_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_quantity` int(11) NOT NULL DEFAULT '0',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biscuits`
--

DROP TABLE IF EXISTS `biscuits`;
CREATE TABLE `biscuits` (
  `id` int(11) NOT NULL,
  `biscuit_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biscuit_deleted`
--

DROP TABLE IF EXISTS `biscuit_deleted`;
CREATE TABLE `biscuit_deleted` (
  `id` int(11) NOT NULL,
  `origin_id` int(11) NOT NULL,
  `code` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `name` varchar(64) COLLATE latin2_croatian_ci NOT NULL,
  `note` varchar(256) COLLATE latin2_croatian_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_quantity` float(12,2) NOT NULL DEFAULT '0.00',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `origin_user_id` int(11) NOT NULL,
  `origin_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biscuit_materials`
--

DROP TABLE IF EXISTS `biscuit_materials`;
CREATE TABLE `biscuit_materials` (
  `id` int(11) NOT NULL,
  `biscuit_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `material_quantity_used` float(13,3) NOT NULL DEFAULT '0.000',
  `material_calculated_price` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `factured`
--

DROP TABLE IF EXISTS `factured`;
CREATE TABLE `factured` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `current_quantity` int(11) NOT NULL DEFAULT '0',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `factureds`
--

DROP TABLE IF EXISTS `factureds`;
CREATE TABLE `factureds` (
  `id` int(11) NOT NULL,
  `factured_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomplete`
--

DROP TABLE IF EXISTS `incomplete`;
CREATE TABLE `incomplete` (
  `id` int(11) NOT NULL,
  `code` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `name` varchar(64) COLLATE latin2_croatian_ci NOT NULL,
  `note` varchar(256) COLLATE latin2_croatian_ci DEFAULT NULL,
  `work_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `biscuits_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `materials_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_quantity` int(11) NOT NULL DEFAULT '0',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incompletes`
--

DROP TABLE IF EXISTS `incompletes`;
CREATE TABLE `incompletes` (
  `id` int(11) NOT NULL,
  `incomplete_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomplete_biscuits`
--

DROP TABLE IF EXISTS `incomplete_biscuits`;
CREATE TABLE `incomplete_biscuits` (
  `id` int(11) NOT NULL,
  `incomplete_id` int(11) NOT NULL,
  `biscuit_id` int(11) NOT NULL,
  `biscuit_quantity_used` int(11) NOT NULL DEFAULT '0',
  `biscuit_calculated_price` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomplete_deleted`
--

DROP TABLE IF EXISTS `incomplete_deleted`;
CREATE TABLE `incomplete_deleted` (
  `id` int(11) NOT NULL,
  `origin_id` int(11) NOT NULL,
  `code` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `name` varchar(64) COLLATE latin2_croatian_ci NOT NULL,
  `note` varchar(256) COLLATE latin2_croatian_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_quantity` float(12,2) NOT NULL DEFAULT '0.00',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `origin_user_id` int(11) NOT NULL,
  `origin_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomplete_materials`
--

DROP TABLE IF EXISTS `incomplete_materials`;
CREATE TABLE `incomplete_materials` (
  `id` int(11) NOT NULL,
  `incomplete_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `material_quantity_used` float(13,3) NOT NULL DEFAULT '0.000',
  `material_calculated_price` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_types`
--

DROP TABLE IF EXISTS `item_types`;
CREATE TABLE `item_types` (
  `id` int(11) NOT NULL,
  `type` varchar(16) COLLATE latin2_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

DROP TABLE IF EXISTS `material`;
CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `code` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `name` varchar(64) COLLATE latin2_croatian_ci NOT NULL,
  `note` varchar(256) COLLATE latin2_croatian_ci DEFAULT NULL,
  `package_quantity` float(9,2) NOT NULL,
  `package_measure_unit` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_quantity` float(13,3) NOT NULL DEFAULT '0.0000',
  `current_quantity_in_measure_unit` float(13,3) NOT NULL DEFAULT '0.000',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_deleted`
--

DROP TABLE IF EXISTS `material_deleted`;
CREATE TABLE `material_deleted` (
  `id` int(11) NOT NULL,
  `origin_id` int(11) NOT NULL,
  `code` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `name` varchar(64) COLLATE latin2_croatian_ci NOT NULL,
  `note` varchar(256) COLLATE latin2_croatian_ci DEFAULT NULL,
  `package_quantity` float(9,2) NOT NULL,
  `package_measure_unit` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_quantity` float(13,3) NOT NULL DEFAULT '0.00',
  `current_quantity_in_measure_unit` float(13,3) NOT NULL DEFAULT '0.000',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `origin_user_id` int(11) NOT NULL,
  `origin_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_version`
--

DROP TABLE IF EXISTS `material_version`;
CREATE TABLE `material_version` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `name` varchar(64) COLLATE latin2_croatian_ci NOT NULL,
  `note` varchar(256) COLLATE latin2_croatian_ci DEFAULT NULL,
  `package_quantity` float(9,2) NOT NULL,
  `package_measure_unit` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_quantity` float(13,3) NOT NULL DEFAULT '0.00',
  `current_quantity_in_measure_unit` float(13,3) NOT NULL DEFAULT '0.000',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `code` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `name` varchar(64) COLLATE latin2_croatian_ci NOT NULL,
  `note` varchar(256) COLLATE latin2_croatian_ci DEFAULT NULL,
  `work_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `incompletes_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `materials_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_quantity` int(11) NOT NULL DEFAULT '0',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_deleted`
--

DROP TABLE IF EXISTS `product_deleted`;
CREATE TABLE `product_deleted` (
  `id` int(11) NOT NULL,
  `origin_id` int(11) NOT NULL,
  `code` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `name` varchar(64) COLLATE latin2_croatian_ci NOT NULL,
  `note` varchar(256) COLLATE latin2_croatian_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_quantity` float(12,2) NOT NULL DEFAULT '0.00',
  `current_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `origin_user_id` int(11) NOT NULL,
  `origin_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_incompletes`
--

DROP TABLE IF EXISTS `product_incompletes`;
CREATE TABLE `product_incompletes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `incomplete_id` int(11) NOT NULL,
  `incomplete_quantity_used` int(11) NOT NULL DEFAULT '0',
  `incomplete_calculated_price` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_materials`
--

DROP TABLE IF EXISTS `product_materials`;
CREATE TABLE `product_materials` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `material_quantity_used` float(13,3) NOT NULL DEFAULT '0.000',
  `material_calculated_price` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `password` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `generated_code` varchar(16) COLLATE latin2_croatian_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biscuit`
--
ALTER TABLE `biscuit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UK_CODE` (`code`),
  ADD KEY `FK_BISCUIT_USER` (`user_id`);

--
-- Indexes for table `biscuits`
--
ALTER TABLE `biscuits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_BISCUITS_USER` (`user_id`),
  ADD KEY `IK_BISCUIT_ID` (`biscuit_id`);

--
-- Indexes for table `biscuit_deleted`
--
ALTER TABLE `biscuit_deleted`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_BISCUITDELETED_USER` (`user_id`),
  ADD KEY `I_BISCUITDELETED_CODE` (`code`),
  ADD KEY `I_BISCUITDELETED_ORIGIN` (`origin_id`),
  ADD KEY `FK_BISCUITDELETED_ORIGIN_USER` (`origin_user_id`);

--
-- Indexes for table `biscuit_materials`
--
ALTER TABLE `biscuit_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factured`
--
ALTER TABLE `factured`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IK_PRODUCT_ID` (`product_id`);

--
-- Indexes for table `factureds`
--
ALTER TABLE `factureds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IK_FACTURED_ID` (`factured_id`),
  ADD KEY `FK_FACTUREDS_USER` (`user_id`);

--
-- Indexes for table `incomplete`
--
ALTER TABLE `incomplete`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UK_CODE` (`code`),
  ADD KEY `FK_INCOMPLETE_USER` (`user_id`);

--
-- Indexes for table `incompletes`
--
ALTER TABLE `incompletes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IK_INCOMPLETE_ID` (`incomplete_id`),
  ADD KEY `FK_INCOMPLETES_USER` (`user_id`);

--
-- Indexes for table `incomplete_biscuits`
--
ALTER TABLE `incomplete_biscuits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomplete_deleted`
--
ALTER TABLE `incomplete_deleted`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_INCOMPLETEDELETED_USER` (`user_id`),
  ADD KEY `I_INCOMPLETEDELETED_CODE` (`code`),
  ADD KEY `I_INCOMPLETEDELETED_ORIGIN` (`origin_id`),
  ADD KEY `FK_INCOMPLETEDELETED_ORIGIN_USER` (`origin_user_id`);

--
-- Indexes for table `incomplete_materials`
--
ALTER TABLE `incomplete_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_types`
--
ALTER TABLE `item_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UK_CODE` (`code`) USING BTREE,
  ADD KEY `FK_MATERIAL_USER` (`user_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_MATERIALS_USER` (`user_id`),
  ADD KEY `IK_MATERIAL_ID` (`material_id`);

--
-- Indexes for table `material_deleted`
--
ALTER TABLE `material_deleted`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_MATERIALDELETED_USER` (`user_id`),
  ADD KEY `I_MATERIALDELETED_CODE` (`code`),
  ADD KEY `I_MATERIALDELETED_ORIGIN` (`origin_id`),
  ADD KEY `FK_MATERIALDELETED_ORIGIN_USER` (`origin_user_id`);

--
-- Indexes for table `material_version`
--
ALTER TABLE `material_version`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_MATERIALVERSION_USER` (`user_id`),
  ADD KEY `IK_MATERIAL_ID` (`material_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UK_code` (`code`),
  ADD KEY `FK_PRODUCT_USER` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_PRODUCTS_USER` (`user_id`),
  ADD KEY `IK_PRODUCT_ID` (`product_id`);

--
-- Indexes for table `product_deleted`
--
ALTER TABLE `product_deleted`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_PRODUCTDELETED_USER` (`user_id`),
  ADD KEY `I_PRODUCTDELETED_CODE` (`code`),
  ADD KEY `I_PRODUCTDELETED_ORIGIN` (`origin_id`),
  ADD KEY `FK_PRODUCTDELETED_ORIGIN_USER` (`origin_user_id`);

--
-- Indexes for table `product_incompletes`
--
ALTER TABLE `product_incompletes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_materials`
--
ALTER TABLE `product_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `biscuit`
--
ALTER TABLE `biscuit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `biscuits`
--
ALTER TABLE `biscuits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `biscuit_deleted`
--
ALTER TABLE `biscuit_deleted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `biscuit_materials`
--
ALTER TABLE `biscuit_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factured`
--
ALTER TABLE `factured`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factureds`
--
ALTER TABLE `factureds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomplete`
--
ALTER TABLE `incomplete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incompletes`
--
ALTER TABLE `incompletes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomplete_biscuits`
--
ALTER TABLE `incomplete_biscuits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomplete_deleted`
--
ALTER TABLE `incomplete_deleted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomplete_materials`
--
ALTER TABLE `incomplete_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_deleted`
--
ALTER TABLE `material_deleted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_version`
--
ALTER TABLE `material_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_deleted`
--
ALTER TABLE `product_deleted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_incompletes`
--
ALTER TABLE `product_incompletes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_materials`
--
ALTER TABLE `product_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `biscuit`
--
ALTER TABLE `biscuit`
  ADD CONSTRAINT `FK_BISCUIT_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `biscuits`
--
ALTER TABLE `biscuits`
  ADD CONSTRAINT `FK_BISCUITS_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `biscuit_deleted`
--
ALTER TABLE `biscuit_deleted`
  ADD CONSTRAINT `FK_BISCUITDELETED_ORIGIN_USER` FOREIGN KEY (`origin_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_BISCUITDELETED_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `factureds`
--
ALTER TABLE `factureds`
  ADD CONSTRAINT `FK_FACTUREDS_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `incomplete`
--
ALTER TABLE `incomplete`
  ADD CONSTRAINT `FK_INCOMPLETE_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `incompletes`
--
ALTER TABLE `incompletes`
  ADD CONSTRAINT `FK_INCOMPLETES_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `incomplete_deleted`
--
ALTER TABLE `incomplete_deleted`
  ADD CONSTRAINT `FK_INCOMPLETEDELETED_ORIGIN_USER` FOREIGN KEY (`origin_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_INCOMPLETEDELETED_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `FK_MATERIAL_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `FK_MATERIALS_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `material_deleted`
--
ALTER TABLE `material_deleted`
  ADD CONSTRAINT `FK_MATERIALDELETED_ORIGIN_USER` FOREIGN KEY (`origin_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_MATERIALDELETED_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `material_version`
--
ALTER TABLE `material_version`
  ADD CONSTRAINT `FK_MATERIALVERSION_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_PRODUCT_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_PRODUCTS_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `product_deleted`
--
ALTER TABLE `product_deleted`
  ADD CONSTRAINT `FK_PRODUCTDELETED_ORIGIN_USER` FOREIGN KEY (`origin_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_PRODUCTDELETED_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



INSERT INTO `user` (`username`, `password`, `generated_code`) VALUES
('tester', 'test_123', '159753'),
('rade', '000', 'habab'),
('ivan', 'ivan', 'ivan');


INSERT INTO `item_types`(`id`, `type`) VALUES
('1', 'material'),
('2', 'biscuit'),
('3', 'incomplete'),
('4', 'product');

COMMIT;
