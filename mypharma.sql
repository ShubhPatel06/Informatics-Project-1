-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2022 at 06:43 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mypharma`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(25) NOT NULL,
  `product_price` varchar(15) NOT NULL,
  `quantity` int(10) NOT NULL,
  `total_price` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories`
--

CREATE TABLE `tbl_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_categories`
--

INSERT INTO `tbl_categories` (`category_id`, `category_name`) VALUES
(1, 'Medical Conditions'),
(2, 'Vitamins and Supplements'),
(3, 'Beauty and Skin Care');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hospitalstaff`
--

CREATE TABLE `tbl_hospitalstaff` (
  `staff_id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `phoneNo` int(11) NOT NULL,
  `hospital_email` varchar(60) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_hospitalstaff`
--

INSERT INTO `tbl_hospitalstaff` (`staff_id`, `first_name`, `last_name`, `phoneNo`, `hospital_email`, `gender`, `password`, `role`) VALUES
(1, 'test', 'case', 719291001, 'hospital@gmail.com', 'female', '$2y$10$PZmdLbK64R7nCE09vbu/m.lCLzXX.71vt7cD9ZQCvo1uW/o5jFH3q', 3),
(2, 'cust ', 'test2', 1038920101, 'hospital2@gmail.com', 'female', '$2y$10$bf403B1x7Lzn.ADuz7ppI.He8rRC8pbjMdaYx5iTkZlCZivbdzDGW', 3),
(4, 'email', 'test', 82909101, 'shubhpatel891@gmail.com', 'male', '$2y$10$eI2wGGq59OMdB2U73UEXDO6hLAej5phdVzSGngXLfKbs0bgXPIEAO', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_medicinetype`
--

CREATE TABLE `tbl_medicinetype` (
  `type_id` int(11) NOT NULL,
  `medicine_type` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_medicinetype`
--

INSERT INTO `tbl_medicinetype` (`type_id`, `medicine_type`) VALUES
(1, 'Over the Counter'),
(2, 'Prescription Only');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_amount` double NOT NULL DEFAULT 0,
  `order_status` varchar(30) NOT NULL DEFAULT 'Paid',
  `created_at` datetime NOT NULL,
  `payment_type` int(11) NOT NULL,
  `delivery_address` text NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`order_id`, `customer_id`, `order_amount`, `order_status`, `created_at`, `payment_type`, `delivery_address`, `updated_at`) VALUES
(1, 3, 2720, 'Paid', '2022-07-18 21:00:39', 1, 'Nairobi West', '0000-00-00 00:00:00'),
(2, 1, 3690, 'Paid', '2022-07-18 21:01:46', 1, 'Langata', '0000-00-00 00:00:00'),
(3, 3, 2830, 'Paid', '2022-07-18 21:03:18', 1, 'Langata', '0000-00-00 00:00:00'),
(4, 3, 5240, 'Paid', '2022-07-18 21:04:30', 2, 'Strathmore University', '0000-00-00 00:00:00'),
(5, 1, 7240, 'Paid', '2022-07-18 21:06:10', 2, 'South C', '0000-00-00 00:00:00'),
(6, 1, 2220, 'Paid', '2022-07-19 13:13:57', 1, 'Strathmore University', '2022-07-19 14:25:57'),
(7, 1, 640, 'Paid', '2022-07-19 17:07:30', 1, 'Langata', '0000-00-00 00:00:00'),
(8, 1, 430, 'Paid', '2022-07-19 17:13:35', 1, 'Trial', '0000-00-00 00:00:00'),
(9, 1, 3800, 'Paid', '2022-07-19 17:59:52', 1, 'Nairobi West', '2022-07-19 18:04:39'),
(10, 1, 1280, 'Paid', '2022-07-19 19:53:59', 1, 'Langata', '0000-00-00 00:00:00'),
(11, 1, 3800, 'Paid', '2022-07-19 20:02:27', 1, 'Nairobi west', '2022-07-19 20:04:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orderdetails`
--

CREATE TABLE `tbl_orderdetails` (
  `orderdetails_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product` varchar(50) NOT NULL,
  `product_price` double NOT NULL,
  `order_quantity` int(11) NOT NULL DEFAULT 1,
  `orderdetails_total` double NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_orderdetails`
--

INSERT INTO `tbl_orderdetails` (`orderdetails_id`, `order_id`, `product_id`, `product`, `product_price`, `order_quantity`, `orderdetails_total`, `created_at`) VALUES
(1, 1, 8, 'Benylin 4 flu', 830, 2, 1660, '2022-07-18 21:00:39'),
(2, 1, 1, 'Diben Berries', 640, 1, 640, '2022-07-18 21:00:39'),
(3, 1, 5, 'Dextracin Eye/Ear Drops', 210, 2, 420, '2022-07-18 21:00:39'),
(4, 2, 6, 'Ascoril Expectorant', 430, 3, 1290, '2022-07-18 21:01:46'),
(5, 2, 10, 'Berocca Boost ', 350, 2, 700, '2022-07-18 21:01:46'),
(6, 2, 12, 'Acnes Daily Care Kit', 1700, 1, 1700, '2022-07-18 21:01:46'),
(7, 3, 6, 'Ascoril Expectorant', 430, 1, 430, '2022-07-18 21:03:18'),
(8, 3, 9, 'Amifer Syrup', 480, 5, 2400, '2022-07-18 21:03:18'),
(9, 4, 2, 'Amaryl 2mg Tablets', 1580, 2, 3160, '2022-07-18 21:04:30'),
(10, 4, 7, 'Aerovent Inhaler', 1040, 2, 2080, '2022-07-18 21:04:30'),
(11, 5, 3, 'Getryl 1mg Tablets', 320, 2, 640, '2022-07-18 21:06:10'),
(12, 5, 11, 'USN Blue Lab Whey', 3300, 2, 6600, '2022-07-18 21:06:10'),
(13, 6, 1, 'Diben Berries', 640, 1, 640, '2022-07-19 13:13:57'),
(14, 6, 2, 'Amaryl 2mg Tablets', 1580, 1, 1580, '2022-07-19 13:13:57'),
(15, 7, 1, 'Diben Berries', 640, 1, 640, '2022-07-19 17:07:30'),
(16, 8, 6, 'Ascoril Expectorant', 430, 1, 430, '2022-07-19 17:13:35'),
(17, 9, 2, 'Amaryl 2mg Tablets', 1580, 2, 3160, '2022-07-19 17:59:52'),
(18, 9, 3, 'Getryl 1mg Tablets', 320, 2, 640, '2022-07-19 17:59:52'),
(19, 10, 1, 'Diben Berries', 640, 2, 1280, '2022-07-19 19:54:00'),
(20, 11, 2, 'Amaryl 2mg Tablets', 1580, 2, 3160, '2022-07-19 20:02:27'),
(21, 11, 3, 'Getryl 1mg Tablets', 320, 2, 640, '2022-07-19 20:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_paymenttypes`
--

CREATE TABLE `tbl_paymenttypes` (
  `paymenttype_id` int(11) NOT NULL,
  `paymenttype_name` varchar(20) NOT NULL,
  `description` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_paymenttypes`
--

INSERT INTO `tbl_paymenttypes` (`paymenttype_id`, `paymenttype_name`, `description`) VALUES
(1, 'Credit Card', 'Card payment method using stripe'),
(2, 'Pending', 'Admin places order based on prescription');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prescription`
--

CREATE TABLE `tbl_prescription` (
  `prescription_id` int(11) NOT NULL,
  `prescription_image` varchar(250) NOT NULL,
  `added_by` int(11) NOT NULL,
  `customer_name` varchar(30) NOT NULL,
  `hospital_email` varchar(60) NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'processing',
  `approved_by` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_prescription`
--

INSERT INTO `tbl_prescription` (`prescription_id`, `prescription_image`, `added_by`, `customer_name`, `hospital_email`, `status`, `approved_by`) VALUES
(1, 'prescription1.jpg', 1, 'shubh patel', 'hospital@gmail.com', 'approved', 1),
(2, 'prescription2.jpg', 3, 'customer user', 'hospital2@gmail.com', 'processed', 2),
(3, 'prescription2.jpg', 1, 'shubh patel', 'hospital2@gmail.com', 'processing', 1),
(4, 'prescription1.jpg', 1, 'shubh patel', 'hospital2@gmail.com', 'processing', 1),
(5, 'prescription1.jpg', 1, 'shubh patel', 'patelshubh547@gmail.com', 'processing', 1),
(6, 'prescription2.jpg', 1, 'shubh patel', 'shubhpatel891@gmail.com', 'rejected', 4),
(7, 'prescription1.jpg', 1, 'shubh patel', 'vinniepp270401@gmail.com', 'processing', 1),
(8, 'prescription2.jpg', 3, 'customer user', 'patelshubh547@gmail.com', 'processing', 1),
(9, 'prescription1.jpg', 3, 'customer user', 'vinniepp2704@gmail.com', 'processing', 1),
(10, 'prescription1.jpg', 1, 'shubh patel', 'ryanmk2001@gmail.com', 'processing', 1),
(11, 'prescription2.jpg', 1, 'shubh patel', 'shubhpatel891@gmail.com', 'approved', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(25) NOT NULL,
  `product_description` text NOT NULL,
  `product_image` varchar(250) NOT NULL,
  `unit_price` double NOT NULL,
  `available_quantity` int(11) UNSIGNED NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `medicine_type` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_name`, `product_description`, `product_image`, `unit_price`, `available_quantity`, `subcategory_id`, `medicine_type`, `created_at`, `updated_at`, `added_by`) VALUES
(1, 'Diben Berries', 'Diben Forest Berries 200ml - Complete Nutrition for Diabetics\r\nNutritionally complete, high calorie oral supplement with fibre.', 'Diben Forest Berries.JPG', 640, 20, 1, 1, '2022-07-17 23:02:30', '2022-07-18 00:02:30', 2),
(2, 'Amaryl 2mg Tablets', 'Amaryl is used to treat a certain form of diabetes (type 2 diabetes mellitus) when diet, physical exercise and weight reduction alone have not been able to control your blood sugar levels.', 'Amaryl.jpg', 1580, 20, 1, 2, '2022-07-17 23:04:40', '2022-07-18 00:04:40', 2),
(3, 'Getryl 1mg Tablets', 'Getryl 1mg is an anti-diabetic medication (sulphonylurea) that contains glimepiride which is used in the treatment of type 2 diabetes.', 'Getryl.jpg', 320, 20, 1, 2, '2022-07-17 23:05:55', '2022-07-18 00:05:55', 2),
(4, 'Acular Eye Drops', 'Acular Eye Drops contains Ketorolac trometamol (0.5% w/v) which is used in eye pain.It is used to prevent and treat inflammation and associated symptoms following eye surgery.', 'Acular.jpg', 1345, 20, 2, 2, '2022-07-17 23:08:05', '2022-07-18 00:08:05', 2),
(5, 'Dextracin Eye/Ear Drops', 'Dextracin Eye drops contains contains Dexamethasone Phosphate and Neomycin Sulphate usually used to treat bacterial infection on eyes and ears.', 'Dextracin.jpg', 210, 20, 2, 1, '2022-07-17 23:09:55', '2022-07-18 00:09:55', 2),
(6, 'Ascoril Expectorant', 'Ascoril Expectorant works by increasing the viscid or excessive mucus in respiratory tract thus making phlegm thinner; temporarily relieving minor pain; relaxing and opening the airways; reducing the phlegm in the air passages.', 'Ascoril.JPG', 430, 20, 3, 1, '2022-07-17 23:12:38', '2022-07-18 00:12:38', 2),
(7, 'Aerovent Inhaler', 'Aerovent Inhaler is a combination of two medicines: Salbutamol and Beclometasone. Salbutamol is a bronchodilator which works by relaxing the muscles in the airways and widens the airways.', 'Aerovent.jpg', 1040, 20, 3, 2, '2022-07-17 23:14:34', '2022-07-18 00:14:34', 2),
(8, 'Benylin 4 flu', 'Benylin 4 flu liquid is used to relieve cold and flu symptoms.It contains paracetamol, an analgesic which helps to relieve aches and pains and fever', 'Benylin.JPG', 830, 20, 3, 1, '2022-07-17 23:16:53', '2022-07-18 00:16:53', 2),
(9, 'Amifer Syrup', 'Amifer syrup is used to treat and prevent iron and folic acid deficiency. This syrup can be used by people with an iron deficiency or low dietary iron, during pregnancy, after delivery for prevention of anemia, of iron and folic acid deficiency', 'Amifer.JPG', 480, 20, 4, 1, '2022-07-17 23:19:41', '2022-07-18 00:19:41', 2),
(10, 'Berocca Boost ', 'Berocca Boost helps energise you. It contains Caffeine and Guarana, to help you feel more awake and alert.', 'Berroca.JPG', 350, 20, 4, 1, '2022-07-17 23:21:35', '2022-07-18 00:21:35', 2),
(11, 'USN Blue Lab Whey', 'BlueLa 100% Whey is an ultra-premium blend of the highest quality whey protein isolate, concentrate and hydrolysate for optimal muscle development, support and recovery.', 'Whey.JPG', 3300, 20, 5, 1, '2022-07-17 23:23:22', '2022-07-18 00:23:22', 2),
(12, 'Acnes Daily Care Kit', 'Acnes Daily Care Kit provides the daily support you need to maintain a healthy and radiant skin. Gentle and nourishing, the Daily Care Kit is a simple 3-step ritual that cleanses, tones and deeply moisturises the skin ', 'Acnes.JPG', 1700, 20, 6, 1, '2022-07-17 23:25:23', '2022-07-18 00:25:23', 2),
(13, 'Acnes Scar Care', 'Acnes Scar Care is specifically a post-acne gel that contains vital vitamins which help heal the skin by fading out dark spots.', 'Scars.JPG', 460, 20, 6, 1, '2022-07-17 23:26:40', '2022-07-18 00:26:40', 2),
(14, 'Malibu Kids Lotion', 'Malibu Kids Lotion for kids and it includes UV protection', 'Lotion.JPG', 650, 20, 7, 1, '2022-07-17 23:29:17', '2022-07-18 00:29:17', 2),
(15, 'Demelan SPF 50 Lotion', 'Demelan SPF 50 face sunscreen Lotion 60ml which is a non-irritant, non-greasy and a water-resistant sunscreen providing broad-spectrum protection from the sun.', 'SPF.JPG', 2900, 20, 7, 1, '2022-07-17 23:30:24', '2022-07-18 00:30:24', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`role_id`, `role_name`) VALUES
(1, 'Customer'),
(2, 'Admin'),
(3, 'Hospital staff');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subcategories`
--

CREATE TABLE `tbl_subcategories` (
  `subcategory_id` int(11) NOT NULL,
  `subcategory_name` varchar(25) NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_subcategories`
--

INSERT INTO `tbl_subcategories` (`subcategory_id`, `subcategory_name`, `category`) VALUES
(1, 'Diabetes', 1),
(2, 'Eye Care', 1),
(3, 'Cough & Flu', 1),
(4, 'Supplements', 2),
(5, 'Workout Essentials', 2),
(6, 'Face Care', 3),
(7, 'Sun Protection', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `phoneNo` int(15) NOT NULL,
  `email` varchar(60) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `password` varchar(60) NOT NULL,
  `dob` date DEFAULT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `first_name`, `last_name`, `phoneNo`, `email`, `gender`, `password`, `dob`, `role`) VALUES
(1, 'shubh', 'patel', 700112233, 'shubh@gmail.com', 'male', '$2y$10$ZNqJFCUkLtLA8BN7EDXqQefoaun/qfHzVVmZUuCpJWmN4ZXNtuVHy', '2022-07-21', 1),
(2, 'admin', 'shubh', 710203040, 'admin@gmail.com', 'male', '$2y$10$oXmzNUsSY.pGHvKrAXT9XOnAwq4i2HZypLnB/1RUq0i00oG7VFQlG', '2021-06-08', 2),
(3, 'customer', 'user', 789988998, 'customer@gmail.com', 'male', '$2y$10$eve4eCPD7.6l4/Dc.s71T.hbxkbjM3TWzZ0YbutCla3Xz6X26unee', '2001-08-08', 1),
(4, 'dob', 'test', 781939012, 'dob@gmail.com', 'female', '$2y$10$j1svw6D4rq6qB973G7aoCuI.07.A1Gf.WKU95RKY4kgE.CP0S5FrW', '2003-05-15', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_hospitalstaff`
--
ALTER TABLE `tbl_hospitalstaff`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `role` (`role`);

--
-- Indexes for table `tbl_medicinetype`
--
ALTER TABLE `tbl_medicinetype`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `payment_type` (`payment_type`);

--
-- Indexes for table `tbl_orderdetails`
--
ALTER TABLE `tbl_orderdetails`
  ADD PRIMARY KEY (`orderdetails_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `tbl_paymenttypes`
--
ALTER TABLE `tbl_paymenttypes`
  ADD PRIMARY KEY (`paymenttype_id`);

--
-- Indexes for table `tbl_prescription`
--
ALTER TABLE `tbl_prescription`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `subcategory_id` (`subcategory_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `medicine_type` (`medicine_type`);

--
-- Indexes for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `tbl_subcategories`
--
ALTER TABLE `tbl_subcategories`
  ADD PRIMARY KEY (`subcategory_id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_hospitalstaff`
--
ALTER TABLE `tbl_hospitalstaff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_medicinetype`
--
ALTER TABLE `tbl_medicinetype`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_orderdetails`
--
ALTER TABLE `tbl_orderdetails`
  MODIFY `orderdetails_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_paymenttypes`
--
ALTER TABLE `tbl_paymenttypes`
  MODIFY `paymenttype_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_prescription`
--
ALTER TABLE `tbl_prescription`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_subcategories`
--
ALTER TABLE `tbl_subcategories`
  MODIFY `subcategory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_hospitalstaff`
--
ALTER TABLE `tbl_hospitalstaff`
  ADD CONSTRAINT `tbl_hospitalstaff_ibfk_1` FOREIGN KEY (`role`) REFERENCES `tbl_roles` (`role_id`);

--
-- Constraints for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD CONSTRAINT `tbl_order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `tbl_users` (`user_id`),
  ADD CONSTRAINT `tbl_order_ibfk_2` FOREIGN KEY (`payment_type`) REFERENCES `tbl_paymenttypes` (`paymenttype_id`);

--
-- Constraints for table `tbl_orderdetails`
--
ALTER TABLE `tbl_orderdetails`
  ADD CONSTRAINT `tbl_orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tbl_order` (`order_id`),
  ADD CONSTRAINT `tbl_orderdetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`product_id`);

--
-- Constraints for table `tbl_prescription`
--
ALTER TABLE `tbl_prescription`
  ADD CONSTRAINT `tbl_prescription_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `tbl_users` (`user_id`),
  ADD CONSTRAINT `tbl_prescription_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `tbl_hospitalstaff` (`staff_id`);

--
-- Constraints for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD CONSTRAINT `tbl_product_ibfk_1` FOREIGN KEY (`subcategory_id`) REFERENCES `tbl_subcategories` (`subcategory_id`),
  ADD CONSTRAINT `tbl_product_ibfk_2` FOREIGN KEY (`added_by`) REFERENCES `tbl_users` (`user_id`),
  ADD CONSTRAINT `tbl_product_ibfk_3` FOREIGN KEY (`medicine_type`) REFERENCES `tbl_medicinetype` (`type_id`);

--
-- Constraints for table `tbl_subcategories`
--
ALTER TABLE `tbl_subcategories`
  ADD CONSTRAINT `tbl_subcategories_ibfk_1` FOREIGN KEY (`category`) REFERENCES `tbl_categories` (`category_id`);

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `tbl_roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
