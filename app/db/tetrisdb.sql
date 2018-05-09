-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2018 at 05:58 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tetrisdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `id_remitter` int(11) NOT NULL,
  `id_receiver` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friend`
--

CREATE TABLE `friend` (
  `id_user` int(11) NOT NULL,
  `id_friend` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id_message` int(11) NOT NULL,
  `id_chat` int(11) NOT NULL,
  `content` varchar(300) COLLATE utf8mb4_spanish_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id_room` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `max_players` int(11) NOT NULL,
  `current_players` int(11) NOT NULL,
  `difficulty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id_room`, `name`, `description`, `max_players`, `current_players`, `difficulty`) VALUES
(11, 'salaPrueba', '', 3, 3, 1),
(13, 'salaPrueba2', 'esta es una descripcion', 5, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `roomusers`
--

CREATE TABLE `roomusers` (
  `id_room` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `roomusers`
--

INSERT INTO `roomusers` (`id_room`, `id_user`) VALUES
(11, 1),
(13, 3),
(11, 4),
(11, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`) VALUES
(1, 'brauni800', 'Okla2217'),
(2, 'michelin2', 'acm1pt'),
(3, 'elGaver', '12345678'),
(4, 'unFulano', '12345'),
(5, 'otroFulano', '54321'),
(6, 'yOtroMas', '98765');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `id_remitter` (`id_remitter`) USING BTREE,
  ADD KEY `id_receiver` (`id_receiver`);

--
-- Indexes for table `friend`
--
ALTER TABLE `friend`
  ADD UNIQUE KEY `id_user_2` (`id_user`),
  ADD UNIQUE KEY `id_friend_2` (`id_friend`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_friend` (`id_friend`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_chat` (`id_chat`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id_room`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `roomusers`
--
ALTER TABLE `roomusers`
  ADD KEY `id_room` (`id_room`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `Chat_ibfk_1` FOREIGN KEY (`id_remitter`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `Chat_ibfk_2` FOREIGN KEY (`id_receiver`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `friend`
--
ALTER TABLE `friend`
  ADD CONSTRAINT `Friend_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `Friend_ibfk_2` FOREIGN KEY (`id_friend`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `Message_ibfk_2` FOREIGN KEY (`id_chat`) REFERENCES `chat` (`id_chat`);

--
-- Constraints for table `roomusers`
--
ALTER TABLE `roomusers`
  ADD CONSTRAINT `Room-Users_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `room` (`id_room`),
  ADD CONSTRAINT `Room-Users_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
