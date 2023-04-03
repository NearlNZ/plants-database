-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2023 at 09:51 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comsci-plants`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cateID` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสหมวดหมู่',
  `cateName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อหมวดหมู่'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cateID`, `cateName`) VALUES
('CATE-642681f135db4844', 'ไม้ประดับ'),
('CATE-642681f7341bf675', 'ไม้ผล'),
('CATE-642b0d57c05ca173', 'ไม้ดอก');

-- --------------------------------------------------------

--
-- Table structure for table `plantimages`
--

CREATE TABLE `plantimages` (
  `imgID` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสภาพ',
  `plantID` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสพืช',
  `imgPath` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รูปพืช',
  `imgUpload` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plantimages`
--

INSERT INTO `plantimages` (`imgID`, `plantID`, `imgPath`, `imgUpload`) VALUES
('IMG-642ad8b3488e4104', 'PLANT-6426b9b3c2454468', 'IMG-642ad8b3488e4104.jpg', '2023-04-03'),
('IMG-642b0dc9de9c2990', 'PLANT-642b0d9c42286817', 'IMG-642b0dc9de9c2990.jpg', '2023-04-04'),
('IMG-642b0df3c69c0858', 'PLANT-642b0d9c42286817', 'IMG-642b0df3c69c0858.jpg', '2023-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `plants`
--

CREATE TABLE `plants` (
  `plantID` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสพืช',
  `userID` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'รหัสผู้ใช้',
  `plantName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อพืช',
  `cateID` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'รหัสหมวดหมู่',
  `plantDescription` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รายละเอียดพืช',
  `plantRegist` date NOT NULL COMMENT 'วันที่ลงทะเบียน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plants`
--

INSERT INTO `plants` (`plantID`, `userID`, `plantName`, `cateID`, `plantDescription`, `plantRegist`) VALUES
('PLANT-6426b9b3c2454468', 'USER-642b01ef13969520', 'ทุเรียน', 'CATE-642681f7341bf675', 'เป็นทุเรียนเรือนต้นไม่ดี ทิ้งกิ่ง ลำต้นจะสูงชะลูดทิ้งกิ่ง เปลือกสีน้ำตาลและมีสะเก็ดบ้างเล็กน้อย ช่วงกิ่งยาวมากและเกือบตั้งฉากกับลำต้น แผ่นใบเป็นรูปรีจนถึงเรียวยาวมีขนาดกว้างประมาณ 6-8 เซนติเมตร ยาวประมาณ 17-18 เซนติเมตร ฐานใบเป็นรูปเหลี่ยมป้าน ปลายใบแหลมและงอโค้งก้านใบยาวประมาณ 2 เซนติเมตร ออกเป็นรูปไข่ยาวรี ปลายแหลมและโคนดอกจะเรียวก้านดอกจะยาวมาก จะมี 6 พูหนามมีขนาดโตเกือบเสมอกัน พันธุ์นี้ก้านยาวกว่าพันธุ์อื่นเปลือกค่อนข้างหนา เนื้อมีลักษณะละเอียด นิ่ม สีเหลือง รสชาติหวานมันกลมกล่อม เมล็ดกลม', '2023-03-31'),
('PLANT-642b0d9c42286817', 'USER-642b01ef13969520', 'ดาวเรือง', 'CATE-642b0d57c05ca173', 'ลำต้นมีสีเขียวผิวเกลี้ยงและเป็นสัน ขึ้นแบบตั้งตรง ด้านในมีเนื้ออ่อน ความสูงประมาณ 30-100 เซนติเมตร แตกกิ่งเป็นทรงพุ่มแน่น มีใบประกอบแบบขนนก ปลายคี่ ออกตรงข้ามกันเป็นคู่ มีใบย่อยประมาณ 11-17 ใบ ลักษณะคล้ายรูปหอก โคนใบสอบ ปลายใบแหลม ขอบใบหยัก ใบยาวประมาณ 4-11 เซนติเมตร ออกดอกที่ปลายก้าน สีขึ้นอยู่กับสายพันธุ์ ได้แก่ เหลือง ส้ม ทอง ขาว ฯลฯ ขนาดเส้นผ่านศูนย์กลาง 5-15 เซนติเมตร มีสันเป็นทางยาว 7-13 สัน เมล็ดมีลักษณะแห้งเป็นสีดำ โคนมนกว้างปลายเรียว\r\n', '2023-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสผู้ใช้',
  `userFname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อ',
  `userLname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'สกุล',
  `userProfile` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default-avatar.png' COMMENT 'รูปโปรไฟล์',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Username',
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสผ่าน',
  `userLevel` enum('สมาชิก') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'สมาชิก' COMMENT 'ระดับผู้ใช้',
  `userRegist` date NOT NULL COMMENT 'วันที่ลงทะเบียน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userFname`, `userLname`, `userProfile`, `username`, `password`, `userLevel`, `userRegist`) VALUES
('USER-642b01ef13969520', 'สุรพัศ', 'ทิพย์ภักดี', 'USER-642b0ecc67fb6182.png', 'surapat@test', '$2y$10$MjcRtvietycOh2zWYZsuaewyVEzlbNLl1L5yEcpy7R1LCmAl05rXW', 'สมาชิก', '2023-03-30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cateID`);

--
-- Indexes for table `plantimages`
--
ALTER TABLE `plantimages`
  ADD PRIMARY KEY (`imgID`),
  ADD KEY `plantID` (`plantID`);

--
-- Indexes for table `plants`
--
ALTER TABLE `plants`
  ADD PRIMARY KEY (`plantID`),
  ADD KEY `plants_ibfk_1` (`userID`),
  ADD KEY `plants_ibfk_2` (`cateID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plantimages`
--
ALTER TABLE `plantimages`
  ADD CONSTRAINT `plantimages_ibfk_1` FOREIGN KEY (`plantID`) REFERENCES `plants` (`plantID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `plants`
--
ALTER TABLE `plants`
  ADD CONSTRAINT `plants_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `plants_ibfk_2` FOREIGN KEY (`cateID`) REFERENCES `categories` (`cateID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
