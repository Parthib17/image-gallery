-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2025 at 12:43 PM
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
-- Database: `image_gallery`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(2, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `file_name`, `title`, `description`, `uploaded_at`) VALUES
(27, '683ac626175fd_woman-skate-park.jpg', 'women skates', 'ita a beautiful picture', '2025-05-31 09:04:38'),
(28, '683ac6486794e_woman-skate-park-training.jpg', 'women skate from another position', 'adfasfvv', '2025-05-31 09:05:12'),
(29, '683ac650e0d20_horn.jpg', 'horn', 'dsfsd', '2025-05-31 09:05:20'),
(30, '683ac660c6469_bulb.jpg', 'Bulbss', 'dfsdf', '2025-05-31 09:05:36'),
(31, '683ac66a85e16_beautiful_lake_scenary.jpg', 'lake', 'dfgfsdfs', '2025-05-31 09:05:46'),
(32, '683ac6766c0e2_Beautiful_flower.jpg', 'flower', 'dfgsdvvhhjkhg', '2025-05-31 09:05:58'),
(33, '683ac67fbf5cd_hills.jpg', 'mountain', 'jkgfhfghhg', '2025-05-31 09:06:07'),
(34, '683ac6975e911_stair.jpg', 'siri', 'dfgsfdsf', '2025-05-31 09:06:31'),
(35, '683ac6a68779e_gully.jpg', 'western gully', 'sefghkyujhf', '2025-05-31 09:06:46'),
(36, '683ac6b8215e8_couple.jpg', 'Mongolian couple', 'edfgsfg hhg', '2025-05-31 09:07:04'),
(37, '683ac6ca4f41e_market.jpg', 'Japanese market ', 'egeefed wsd', '2025-05-31 09:07:22'),
(39, '683ac6e731766_Home.jpg', 'houses', 'sdfghdsf ', '2025-05-31 09:07:51'),
(40, '683ada2c8a59f_small bird.jpg', 'Small and cute bird', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, ', '2025-05-31 10:30:04'),
(41, '683ada41e96e9_architecture-heroic.jpg', 'Heroic_Architechture', 'in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin wor', '2025-05-31 10:30:25'),
(42, '683ada5604c66_duck.jpg', 'Duck on water', 'scovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good', '2025-05-31 10:30:46'),
(43, '683ada676f3cd_camera-9330674_1920.jpg', 'Old Cameras', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, ', '2025-05-31 10:31:03'),
(44, '683ada88458d5_lights-9595813_1920.jpg', 'Beautiful candels', 'opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page ', '2025-05-31 10:31:36'),
(45, '683adaa296749_wood-9616079_1920.jpg', 'forest', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, ', '2025-05-31 10:32:02'),
(46, '683adab870f8d_seeds-9424096_1920.jpg', 'Seeds outline', 'in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin wor', '2025-05-31 10:32:24'),
(47, '683adac913747_mountains-9594042_1920.jpg', 'wow mountain', 'in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin wor', '2025-05-31 10:32:41'),
(48, '683adaf3d2832_forest-9504070_1920.jpg', 'Beautiful Forest', 'scovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good', '2025-05-31 10:33:23'),
(49, '683adb0a256ff_animals-9533774_1920.jpg', 'Dog', 'in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin wor', '2025-05-31 10:33:46'),
(50, '683adb208405a_istockphoto-1290233518-1024x1024.jpg', 'Cat\'s face', 'scovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good', '2025-05-31 10:34:08'),
(51, '683adb324ae7c_white-cat-9595396_1920.jpg', 'White cat', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, ', '2025-05-31 10:34:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'user', 'user@example.com', 'user123'),
(2, 'parthib', 'parthib@hotmail.com', 'parthib123'),
(3, 'deb', 'deb123@gmail.com', 'deb123'),
(6, 'souvik', 'souvik@hotmail.com', 'souvik123'),
(7, 'Sourav Samanta', 'sourav123@gmail.com', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
