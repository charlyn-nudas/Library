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
(322, 'Author 1'),
(323, 'Author 2'),
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
(421, 'Author 100'),
(422, 'Author One');

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
(141, 'Book Title One', 'Fiction', 422, '411ZT'),
(146, 'Book Title One', 'Fiction', 422, '999XD');

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
(141, 141, 422),
(146, 146, 422);

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
