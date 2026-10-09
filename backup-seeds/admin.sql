-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 14, 2026 at 05:23 AM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `email`, `username`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'M Ubaidilla Dahlan', 'dahlanubed@gmail.com', 'opet', '$2y$12$gU3hFOKE1tHlunWnyS.Yn.Nm59RMsJz6rPbCjD9WdiQYAe4OW4Csq', NULL, '2026-09-09 05:14:17', '2026-09-09 05:14:17'),
(7, 'Ferdian', 'ferdian@gmail.com', 'joran', '$2y$12$MyS3ra196kNuam4wiqLXKOjjRf8hfmfMGUa3sUOVXNbLsZ.0G0MTG', NULL, '2026-09-10 07:08:51', '2026-09-10 01:52:41'),
(8, 'Aira', 'aira@gmail.com', 'weci', '$2y$12$gXjYOPbXdllKpUD3Tzc.wOCBOjFjcd12KoX16.biyYfuUTZxu8wTW', NULL, '2026-09-10 07:08:51', '2026-09-10 02:40:35'),
(9, 'Apriza', 'apriza@gmail.com', 'ape', '$2y$12$CBlcgKRPDbfcmMArtsfkw.FP0zHtn/9jlabo3lZpJOcoaemlANye.', NULL, '2026-09-10 07:08:51', '2026-09-10 00:09:08'),
(10, 'Angga', 'angga@gmail.com', 'pragos', '$2y$10$0vmrvBaTi0KIZPtsavEAReS1pyZhQR/hwDusBiDHJZwNIaBNE4bny', NULL, '2026-09-10 07:08:51', '2026-09-10 07:08:51'),
(11, 'Agung', 'agung@gmail.com', 'enol', '$2y$10$sVHQv../1NFdjyqL8lW1f.678NUSHKvHchm8UKP.dAPF1CENFymdS', NULL, '2026-09-10 07:08:51', '2026-09-10 07:08:51'),
(12, 'Zia', 'zia@gmail.com', 'bluetooth', '$2y$10$O6Zb4399HzGBYa2XAhCY5u7l604S9cuOxXwCMBijejbWz05MVq0NW', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(13, 'Adrian', 'adrian@gmail.com', 'awing', '$2y$10$CAJwl2lrBuMsK07apf/Jje.vdrhzhp8pNJ79lh9zLodCb.ZKC5c3W', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(14, 'Amel', 'amel@gmail.com', 'saleh', '$2y$10$3QlKraDvxh1X25iojrfj6.V5qlP7th9ATXZ9pih/K49ndSCNcMTqu', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(15, 'Dhea', 'dhea@gmail.com', 'dhewor', '$2y$10$9NMmTFlYr4QJmpkdoaf2tOnUqWGa8wV9oRdVBPDlS0W20qrJdBEye', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(16, 'Lian', 'lian@gmail.com', 'genjreng', '$2y$10$yeT0/iFfC.rzpPqXHSHIL.RbFp1HYI3jKJClavHoyoT11Q.SD.LS.', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(17, 'Ardira', 'ardira@gmail.com', 'dora', '$2y$10$m/1/2SGIgxeLzDqffNTve.QOQ0i4FLEbX3kt76gUkiWUP2G2byoRO', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(18, 'Bintang', 'bintang@gmail.com', 'gopal', '$2y$10$CyuUq/dCcRU.EUHZrzKpfe0ksjbhV78xoN/d.30nf8KPDV5zZb0rC', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(19, 'Nadya', 'nadya@gmail.com', 'ipeh', '$2y$10$uCwcaVJ0EYd2lIL6.SJhGOSZUH5SyCpJrYHYe0mfPUqiL.fzGHYv6', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(20, 'Emil', 'emil@gmail.com', 'congek', '$2y$10$nIhLtHnBJigBP40/40.za.iAc0y0HtReGcr7bptXdQdoWs9G/OULK', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(21, 'Lia', 'lia@gmail.com', 'gendur', '$2y$10$400Nj8R.1eaNvnyUTxdo7.bOhE2Nm/DgALFg/VJBsnyUecLdPp7sW', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(22, 'Abiyu', 'abiyu@gmail.com', 'bongek', '$2y$10$76M/SSvcpwZpOY5tl.9NV.ZjRxx.ZWgLwCdf8ikUPeERfzS1YfRSm', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(23, 'Arifin', 'arifin@gmail.com', 'ipul', '$2y$10$a71MQbYvTQ5qFl5u6.OwR.SZ0BCVt5izBduMI4Iadzo0WpOlHXr6G', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(24, 'Nadtasya', 'nadtasya@gmail.com', 'naong', '$2y$10$pl8Rf3Z7sTm8HygPknb4VOlzLicL6LQgXWsRhVTt8UX4A3Ri2YbEm', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(25, 'Julia', 'julia@gmail.com', 'pramuy', '$2y$10$j0/KQIEIX04N0.CfqP7mtOKlF96iTxxAjKbmUxiP6KmFnP2fcN/BO', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(26, 'Brian', 'brian@gmail.com', 'brewok', '$2y$10$q3Uai4ORkmuSKROIcKZIeuSC.eDkI7hhbTc8uCafGBPByA9yiJGUW', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(27, 'Isvander', 'isvander@gmail.com', 'cakim', '$2y$10$uP.ugCmMXZooI8gRnUhg8ueUgrvGOezZVqs3FqV6Ii/JeECr4W7na', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48'),
(28, 'Arya', 'arya@gmail.com', 'saruto', '$2y$10$T1DTiTEK.hl2.Ue/d7gOkOphJWj4lTt7f.0Vwbtr8ieioV0AALoSe', NULL, '2026-09-14 05:22:48', '2026-09-14 05:22:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `admin_email_unique` (`email`),
  ADD UNIQUE KEY `admin_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
