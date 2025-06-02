-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 05:04 PM
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
(2, 'admin', '$2a$12$FUc/0eoOjZv43ZewlIlI8u4AtfTCh9OxbO296mg7DIauVGvR7nda2');

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
(60, '683b36c80d141_683adb324ae7c_white-cat-9595396_1920.jpg', 'Cat', 'kjdsfhjvjdbnkls;dcn', '2025-05-31 17:05:12'),
(62, '683b37f5b1a3d_683ada2c8a59f_small bird.jpg', 'Bird', 'djfjsjkodfkldfvlpb;', '2025-05-31 17:10:14'),
(63, '683b38652c71a_683ada676f3cd_camera-9330674_1920.jpg', 'Camera', 'hsajdojsdlks', '2025-05-31 17:12:05'),
(64, '683b38e28eb4d_683ac6ca4f41e_market.jpg', 'Market', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:10'),
(65, '683b38e2a3c86_683ac6e731766_Home.jpg', 'Home', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:10'),
(66, '683b38e2de8ec_683ac66a85e16_beautiful_lake_scenary.jpg', 'Beautiful lake scenary', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:10'),
(67, '683b38e2effc8_683ac67fbf5cd_hills.jpg', 'Hills', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:11'),
(68, '683b38e31157f_683ac6766c0e2_Beautiful_flower.jpg', 'Beautiful flowers', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:11'),
(69, '683b38e36d67d_683ac6975e911_stair.jpg', 'Stair', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:11'),
(70, '683b38e38e5d6_683ac626175fd_woman-skate-park.jpg', 'Skating', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:11'),
(71, '683b38e3b98c3_683ac6486794e_woman-skate-park-training.jpg', 'Skating', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:11'),
(72, '683b38e3deb30_683ada41e96e9_architecture-heroic.jpg', 'Architecture', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:11'),
(73, '683b38e3effc5_683ada5604c66_duck.jpg', 'Duck', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:12'),
(74, '683b38e40069c_683ada88458d5_lights-9595813_1920.jpg', 'lights', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:12'),
(75, '683b38e4038e8_683adaa296749_wood-9616079_1920.jpg', 'Wood', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:12'),
(76, '683b38e40ce19_683adab870f8d_seeds-9424096_1920.jpg', 'Images', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:12'),
(77, '683b38e40f048_683adac913747_mountains-9594042_1920.jpg', 'Images', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:12'),
(78, '683b38e41acfc_683adaf3d2832_forest-9504070_1920.jpg', 'Images', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:12'),
(79, '683b38e42a7ca_683adb0a256ff_animals-9533774_1920.jpg', 'Images', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:12'),
(80, '683b38e42f40b_683adb208405a_istockphoto-1290233518-1024x1024.jpg', 'Images', 'DSNKJFLXJCVLKDFJXKL', '2025-05-31 17:14:12'),
(83, '683b3acd182f9_683b17db6203a_positive-thinking.gif', 'Animation', 'ksfhdkfhdsi', '2025-05-31 17:22:21'),
(87, '683b3e9b4d32c_3500500.jpg', 'Kimi No Namae wa', 'adfkjddkjsjdkjas', '2025-05-31 17:38:35'),
(88, '683b3f195a861_fca0cd6c-fd43-4e2c-8747-f3359826a35d.jpeg', 'Japanese Shrine', 'A Shinto shrine (ç¥žç¤¾, jinja) is a place of worship in Japan dedicated to the kami, or deities, of the Shinto religion.', '2025-05-31 17:40:41'),
(89, '683b405c9c4a7_Photo by Nitin Shivaprasad on Unsplash.jpeg', 'Yellow Taxi', 'sajhfkshfsdhkjfhksdhf', '2025-05-31 17:46:04'),
(90, '683b40ee12055_download.jpeg', 'Kolkata\'s tram', 'grhtjhjkgfds', '2025-05-31 17:48:30'),
(91, '683b417d26a05_8e65035a0ba7bb2f5eb332cb5948602c.jpg', 'Victoria Memorial', 'ghjdszgfsdhjfghjsdgf', '2025-05-31 17:50:53'),
(92, '683c10ff49f6b_Snapinsta.app_441029861_1345131509494382_9030706074068793370_n_1080.jpg', 'Scenery ', 'Ababababababababbaba', '2025-06-01 08:36:15'),
(93, '683db9da2bb12_e9759d25dda53d99a74a148c63c16231.jpg', 'Howrah Bridge', 'ashdkahsdhajsh', '2025-06-02 14:48:58');

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
(17, 'user', 'user@gmail.com', '$2y$10$wEUoOZMp1gq9E7QtT/9z/uAYNawQPjxstczRlgzUajZVqSNvDfCQa'),
(18, 'parthib17', 'parthibmitra1278@gmail.com', '$2y$10$MFThPVWaTN4LEZHGwuGMH.bkDlmsIWolzyvDCea2l0B39CSOq5Cbi');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
