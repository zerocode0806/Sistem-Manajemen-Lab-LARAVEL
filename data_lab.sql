-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 14, 2026 at 05:24 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem-manajemen-lab-laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_lab`
--

CREATE TABLE `data_lab` (
  `id_lab` int UNSIGNED NOT NULL,
  `nama_lab` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stok` int NOT NULL,
  `jumlah_kursi` int NOT NULL DEFAULT '40',
  `jumlah_meja` int NOT NULL DEFAULT '40',
  `status` enum('availabel','not available') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_lab`
--

INSERT INTO `data_lab` (`id_lab`, `nama_lab`, `lokasi`, `stok`, `jumlah_kursi`, `jumlah_meja`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Game dan Multimedia', 'Gendung Lab lantai 2', 40, 40, 40, 'availabel', NULL, NULL),
(2, 'Sistem Komputer', 'Gendung Lab lantai 2', 40, 40, 40, 'availabel', NULL, NULL),
(3, 'Ruang Baca (RBC)', 'Gendung Lab lantai 2', 40, 40, 40, 'availabel', NULL, NULL),
(4, 'Komputasi', 'Gendung Lab lantai 3', 40, 40, 40, 'availabel', NULL, NULL),
(5, 'Algoritma Pemrograman', 'Gendung Lab lantai 3', 40, 40, 40, 'availabel', NULL, NULL),
(6, 'Sistem Cerdas', 'Gendung Lab lantai 3', 40, 40, 40, 'availabel', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_lab`
--
ALTER TABLE `data_lab`
  ADD PRIMARY KEY (`id_lab`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_lab`
--
ALTER TABLE `data_lab`
  MODIFY `id_lab` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
