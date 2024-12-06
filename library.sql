-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 07:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `authorid` int(9) NOT NULL,
  `authorname` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`authorid`, `authorname`) VALUES
(324, 'Author 3'),
(325, 'Author 4'),
(326, 'Author 5'),
(327, 'Author 6'),
(328, 'Author 7'),
(329, 'Author 8'),
(330, 'Author 9'),
(331, 'Author 10'),
(332, 'Author 11'),
(333, 'Author 12'),
(334, 'Author 13'),
(335, 'Author 14'),
(336, 'Author 15'),
(343, 'Author 22'),
(344, 'Author 23'),
(345, 'Author 24'),
(346, 'Author 25'),
(347, 'Author 26'),
(348, 'Author 27'),
(349, 'Author 28'),
(350, 'Author 29'),
(351, 'Author 30'),
(352, 'Author 31'),
(353, 'Author 32'),
(354, 'Author 33'),
(355, 'Author 34'),
(356, 'Author 35'),
(357, 'Author 36'),
(358, 'Author 37'),
(359, 'Author 38'),
(360, 'Author 39'),
(361, 'Author 40'),
(362, 'Author 41'),
(363, 'Author 42'),
(364, 'Author 43'),
(365, 'Author 44'),
(366, 'Author 45'),
(367, 'Author 46'),
(368, 'Author 47'),
(369, 'Author 48'),
(370, 'Author 49'),
(371, 'Author 50'),
(372, 'Author 51'),
(373, 'Author 52'),
(374, 'Author 53'),
(375, 'Author 54'),
(376, 'Author 55'),
(377, 'Author 56'),
(378, 'Author 57'),
(379, 'Author 58'),
(380, 'Author 59'),
(381, 'Author 60'),
(382, 'Author 61'),
(383, 'Author 62'),
(384, 'Author 63'),
(385, 'Author 64'),
(386, 'Author 65'),
(387, 'Author 66'),
(388, 'Author 67'),
(389, 'Author 68'),
(390, 'Author 69'),
(391, 'Author 70'),
(392, 'Author 71'),
(393, 'Author 72'),
(394, 'Author 73'),
(395, 'Author 74'),
(396, 'Author 75'),
(397, 'Author 76'),
(398, 'Author 77'),
(399, 'Author 78'),
(400, 'Author 79'),
(401, 'Author 80'),
(402, 'Author 81'),
(403, 'Author 82'),
(404, 'Author 83'),
(405, 'Author 84'),
(406, 'Author 85'),
(407, 'Author 86'),
(408, 'Author 87'),
(409, 'Author 88'),
(410, 'Author 89'),
(411, 'Author 90'),
(412, 'Author 91'),
(413, 'Author 92'),
(414, 'Author 93'),
(415, 'Author 94'),
(416, 'Author 95'),
(417, 'Author 96'),
(418, 'Author 97'),
(419, 'Author 98'),
(420, 'Author 99'),
(421, 'Author 100');
(422, 'Author 101'),
(423, 'Author 102')


-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `bookid` int(9) NOT NULL,
  `title` char(255) NOT NULL,
  `genre` char(255) NOT NULL,
  `authorid` int(9) NOT NULL,
  `bookCode` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`bookid`, `title`, `genre`, `authorid`, `bookCode`) VALUES
(143, 'Book Title Three', 'Science', 324, 'EF789'),
(144, 'Book Title Four', 'Mystery', 325, 'GH012'),
(145, 'Book Title Five', 'History', 326, 'IJ345'),
(146, 'Book Title Six', 'Fiction', 327, 'KL678'),
(147, 'Book Title Seven', 'Fantasy', 328, 'MN901'),
(148, 'Book Title Eight', 'Science', 329, 'OP234'),
(149, 'Book Title Nine', 'Mystery', 330, 'QR567'),
(150, 'Book Title Ten', 'History', 331, 'ST890'),
(151, 'Book Title Eleven', 'Fiction', 332, 'UV123'),
(152, 'Book Title Twelve', 'Fantasy', 333, 'WX456'),
(153, 'Book Title Thirteen', 'Science', 334, 'YZ789'),
(154, 'Book Title Fourteen', 'Mystery', 335, 'AB012'),
(155, 'Book Title Fifteen', 'History', 336, 'CD345'),
(156, 'Book Title Sixteen', 'Fiction', 337, 'EF678'),
(157, 'Book Title Seventeen', 'Fantasy', 338, 'GH901'),
(158, 'Book Title Eighteen', 'Science', 339, 'IJ234'),
(159, 'Book Title Nineteen', 'Mystery', 340, 'KL567'),
(160, 'Book Title Twenty', 'History', 341, 'MN890'),
(161, 'Book Title Twenty-One', 'Fiction', 342, 'OP123'),
(162, 'Book Title Twenty-Two', 'Fantasy', 343, 'QR456'),
(163, 'Book Title Twenty-Three', 'Science', 344, 'ST789'),
(164, 'Book Title Twenty-Four', 'Mystery', 345, 'UV012'),
(165, 'Book Title Twenty-Five', 'History', 346, 'WX345'),
(166, 'Book Title Twenty-Six', 'Fiction', 347, 'YZ678'),
(167, 'Book Title Twenty-Seven', 'Fantasy', 348, 'AB901'),
(168, 'Book Title Twenty-Eight', 'Science', 349, 'CD234'),
(169, 'Book Title Twenty-Nine', 'Mystery', 350, 'EF567'),
(170, 'Book Title Thirty', 'History', 351, 'GH890'),
(171, 'Book Title Thirty-One', 'Fiction', 352, 'IJ123'),
(172, 'Book Title Thirty-Two', 'Fantasy', 353, 'KL456'),
(173, 'Book Title Thirty-Three', 'Science', 354, 'MN789'),
(174, 'Book Title Thirty-Four', 'Mystery', 355, 'OP012'),
(175, 'Book Title Thirty-Five', 'History', 356, 'QR345'),
(176, 'Book Title Thirty-Six', 'Fiction', 357, 'ST678'),
(177, 'Book Title Thirty-Seven', 'Fantasy', 358, 'UV901'),
(178, 'Book Title Thirty-Eight', 'Science', 359, 'WX234'),
(179, 'Book Title Thirty-Nine', 'Mystery', 360, 'YZ567'),
(180, 'Book Title Forty', 'History', 361, 'AB890'),
(181, 'Book Title Forty-One', 'Fiction', 362, 'CD123'),
(182, 'Book Title Forty-Two', 'Fantasy', 363, 'EF456'),
(183, 'Book Title Forty-Three', 'Science', 364, 'GH789'),
(184, 'Book Title Forty-Four', 'Mystery', 365, 'IJ012'),
(185, 'Book Title Forty-Five', 'History', 366, 'KL345'),
(186, 'Book Title Forty-Six', 'Fiction', 367, 'MN678'),
(187, 'Book Title Forty-Seven', 'Fantasy', 368, 'OP901'),
(188, 'Book Title Forty-Eight', 'Science', 369, 'QR234'),
(189, 'Book Title Forty-Nine', 'Mystery', 370, 'ST567'),
(190, 'Book Title Fifty', 'History', 371, 'UV890'),
(191, 'Book Title Fifty-One', 'Fiction', 372, 'WX123'),
(192, 'Book Title Fifty-Two', 'Fantasy', 373, 'YZ456'),
(193, 'Book Title Fifty-Three', 'Science', 374, 'AB789'),
(194, 'Book Title Fifty-Four', 'Mystery', 375, 'CD012'),
(195, 'Book Title Fifty-Five', 'History', 376, 'EF345'),
(196, 'Book Title Fifty-Six', 'Fiction', 377, 'GH678'),
(197, 'Book Title Fifty-Seven', 'Fantasy', 378, 'IJ901'),
(198, 'Book Title Fifty-Eight', 'Science', 379, 'KL234'),
(199, 'Book Title Fifty-Nine', 'Mystery', 380, 'MN567'),
(200, 'Book Title Sixty', 'History', 381, 'OP890'),
(201, 'Book Title Sixty-One', 'Fiction', 382, 'QR123'),
(202, 'Book Title Sixty-Two', 'Fantasy', 383, 'ST456'),
(203, 'Book Title Sixty-Three', 'Science', 384, 'UV789'),
(204, 'Book Title Sixty-Four', 'Mystery', 385, 'WX012'),
(205, 'Book Title Sixty-Five', 'History', 386, 'YZ345'),
(206, 'Book Title Sixty-Six', 'Fiction', 387, 'AB678'),
(207, 'Book Title Sixty-Seven', 'Fantasy', 388, 'CD901'),
(208, 'Book Title Sixty-Eight', 'Science', 389, 'EF234'),
(209, 'Book Title Sixty-Nine', 'Mystery', 390, 'GH567'),
(210, 'Book Title Seventy', 'History', 391, 'IJ890'),
(211, 'Book Title Seventy-One', 'Fiction', 392, 'KL123'),
(212, 'Book Title Seventy-Two', 'Fantasy', 393, 'MN456'),
(213, 'Book Title Seventy-Three', 'Science', 394, 'OP789'),
(214, 'Book Title Seventy-Four', 'Mystery', 395, 'QR012'),
(215, 'Book Title Seventy-Five', 'History', 396, 'ST345'),
(216, 'Book Title Seventy-Six', 'Fiction', 397, 'UV678'),
(217, 'Book Title Seventy-Seven', 'Fantasy', 398, 'WX901'),
(218, 'Book Title Seventy-Eight', 'Science', 399, 'YZ234'),
(219, 'Book Title Seventy-Nine', 'Mystery', 400, 'AB567'),
(220, 'Book Title Eighty', 'History', 401, 'CD890'),
(221, 'Book Title Eighty-One', 'Fiction', 402, 'EF123'),
(222, 'Book Title Eighty-Two', 'Fantasy', 403, 'GH456'),
(223, 'Book Title Eighty-Three', 'Science', 404, 'IJ789'),
(224, 'Book Title Eighty-Four', 'Mystery', 405, 'KL012'),
(225, 'Book Title Eighty-Five', 'History', 406, 'MN345'),
(226, 'Book Title Eighty-Six', 'Fiction', 407, 'OP678'),
(227, 'Book Title Eighty-Seven', 'Fantasy', 408, 'QR901'),
(228, 'Book Title Eighty-Eight', 'Science', 409, 'ST234'),
(229, 'Book Title Eighty-Nine', 'Mystery', 410, 'UV567'),
(230, 'Book Title Ninety', 'History', 411, 'WX890'),
(231, 'Book Title Ninety-One', 'Fiction', 412, 'YZ123'),
(232, 'Book Title Ninety-Two', 'Fantasy', 413, 'AB456'),
(233, 'Book Title Ninety-Three', 'Science', 414, 'CD789'),
(234, 'Book Title Ninety-Four', 'Mystery', 415, 'EF012'),
(235, 'Book Title Ninety-Five', 'History', 416, 'GH345'),
(236, 'Book Title Ninety-Six', 'Fiction', 417, 'IJ678'),
(237, 'Book Title Ninety-Seven', 'Fantasy', 418, 'KL901'),
(238, 'Book Title Ninety-Eight', 'Science', 419, 'MN234'),
(239, 'Book Title Ninety-Nine', 'Mystery', 420, 'OP567'),
(240, 'Book Title One Hundred', 'History', 421, 'QR890'),
(241, 'Book Title One Hundred-One', 'History', 422, 'ST123'),
(242, 'Book Title One Hundred-Two', 'History', 423, 'UV456');





-- --------------------------------------------------------

--
-- Table structure for table `books_collection`
--

CREATE TABLE `books_collection` (
  `collectionid` int(9) NOT NULL,
  `bookid` int(9) NOT NULL,
  `authorid` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books_collection`
--

INSERT INTO `books_collection` (`collectionid`, `bookid`, `authorid`) VALUES
(3, 143, 324),
(4, 144, 325),
(5, 145, 326),
(6, 146, 327),
(7, 147, 328),
(8, 148, 329),
(9, 149, 330),
(10, 150, 331),
(11, 151, 332),
(12, 152, 333),
(13, 153, 334),
(14, 154, 335),
(15, 155, 336),
(16, 156, 337),
(17, 157, 338),
(18, 158, 339),
(19, 159, 340),
(20, 160, 341),
(21, 161, 342),
(22, 162, 343),
(23, 163, 344),
(24, 164, 345),
(25, 165, 346),
(26, 166, 347),
(27, 167, 348),
(28, 168, 349),
(29, 169, 350),
(30, 170, 351),
(31, 171, 352),
(32, 172, 353),
(33, 173, 354),
(34, 174, 355),
(35, 175, 356),
(36, 176, 357),
(37, 177, 358),
(38, 178, 359),
(39, 179, 360),
(40, 180, 361),
(41, 181, 362),
(42, 182, 363),
(43, 183, 364),
(44, 184, 365),
(45, 185, 366),
(46, 186, 367),
(47, 187, 368),
(48, 188, 369),
(49, 189, 370),
(50, 190, 371),
(51, 191, 372),
(52, 192, 373),
(53, 193, 374),
(54, 194, 375),
(55, 195, 376),
(56, 196, 377),
(57, 197, 378),
(58, 198, 379),
(59, 199, 380),
(60, 200, 381),
(61, 201, 382),
(62, 202, 383),
(63, 203, 384),
(64, 204, 385),
(65, 205, 386),
(66, 206, 387),
(67, 207, 388),
(68, 208, 389),
(69, 209, 390),
(70, 210, 391),
(71, 211, 392),
(72, 212, 393),
(73, 213, 394),
(74, 214, 395),
(75, 215, 396),
(76, 216, 397),
(77, 217, 398),
(78, 218, 399),
(79, 219, 400),
(80, 220, 401),
(81, 221, 402),
(82, 222, 403),
(83, 223, 404),
(84, 224, 405),
(85, 225, 406),
(86, 226, 407),
(87, 227, 408),
(88, 228, 409),
(89, 229, 410),
(90, 230, 411),
(91, 231, 412),
(92, 232, 413),
(93, 233, 414),
(94, 234, 415),
(95, 235, 416),
(96, 236, 417),
(97, 237, 418),
(98, 238, 419),
(99, 239, 420),
(100, 240, 421),
(101, 241, 422),
(102, 242, 423);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(9) NOT NULL,
  `username` char(255) NOT NULL,
  `password` text NOT NULL,
  `access_level` varchar(10) NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books_collection`
--


INSERT INTO `users` (`userid`, `username`, `password`, `access_level`, `token`, `email`, `created_at` ) VALUES
(126, 'user2', '$2y$10$Ki9ucnBDZZaeSM5pyiaV8e.EgUpyIuiWoFTWj.nCaafzfvYjQlcRy', 'admin', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGlicmFyeS5vcmciLCJhdWQiOiJodHRwOi8vbGlicmFyeS5jb20iLCJpYXQiOjE3MzM0OTAxMDAsImV4cCI6MTczMzQ5MzcwMCwiZGF0YSI6eyJ1c2VyaWQiOjEyNiwibmFtZSI6InJvb3QiLCJhY2Nlc3NfbGV2ZWwiOiJhZG1pbiJ9fQ.V_xiK7CYb1I_w4sl1Yur6VxZKv77wI2wk-W4IUh8EyM', '', 2024-12-06 21:00:07 )

-- --------------------------------------------------------
  

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`authorid`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`bookid`),
  ADD KEY `authorid` (`authorid`);

--
-- Indexes for table `books_collection`
--
ALTER TABLE `books_collection`
  ADD PRIMARY KEY (`collectionid`),
  ADD KEY `bookid` (`bookid`),
  ADD KEY `authorid` (`authorid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `authorid` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=423;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `bookid` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `books_collection`
--
ALTER TABLE `books_collection`
  MODIFY `collectionid` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`authorid`) REFERENCES `authors` (`authorid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `books_collection`
--
ALTER TABLE `books_collection`
  ADD CONSTRAINT `books_collection_ibfk_1` FOREIGN KEY (`bookid`) REFERENCES `books` (`bookid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `books_collection_ibfk_2` FOREIGN KEY (`authorid`) REFERENCES `authors` (`authorid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
