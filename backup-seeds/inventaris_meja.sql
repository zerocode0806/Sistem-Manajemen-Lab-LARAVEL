-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 18, 2026 at 12:17 PM
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
-- Database: `manajemen-lab`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventaris_meja`
--

CREATE TABLE `inventaris_meja` (
  `id_meja` int UNSIGNED NOT NULL,
  `id_lab` int UNSIGNED NOT NULL,
  `nomor_meja` int NOT NULL,
  `cpu_kondisi` enum('normal','rusak','instal_ulang') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `keyboard_kondisi` enum('normal','rusak','tidak_ada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `mouse_kondisi` enum('normal','rusak','tidak_ada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `monitor_kondisi` enum('normal','rusak','tidak_ada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `kursi_kondisi` enum('normal','rusak','tidak_ada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `spesifikasi_pc` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventaris_meja`
--

INSERT INTO `inventaris_meja` (`id_meja`, `id_lab`, `nomor_meja`, `cpu_kondisi`, `keyboard_kondisi`, `mouse_kondisi`, `monitor_kondisi`, `kursi_kondisi`, `keterangan`, `spesifikasi_pc`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'Device name	UMSIDA2-GAME14\nProcessor	Intel(R) Core(TM) i7-14700 (2.10 GHz)\nInstalled RAM	8,00 GB (7,62 GB usable)\nGraphics card	Intel(R) UHD Graphics 770 (128 MB)\nStorage	242 GB of 477 GB used\nDevice ID	86C49993-3D1E-4F22-9FA2-31455EBCCA22\nProduct ID	00342-42727-09146-AAOEM\nSystem type	64-bit operating system, x64-based processor\nPen and touch	No pen or touch input is available for this display\n\nEdition	Windows 11 Home Single Language\nVersion	25H2\nInstalled on	‎04/‎09/‎2025\nOS build	26200.9445\nSerial number	GM0TB62Q\nExperience	Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(2, 1, 2, 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'Device name	UMSIDA2-GAME14\nProcessor	Intel(R) Core(TM) i7-14700 (2.10 GHz)\nInstalled RAM	8,00 GB (7,62 GB usable)\nGraphics card	Intel(R) UHD Graphics 770 (128 MB)\nStorage	242 GB of 477 GB used\nDevice ID	86C49993-3D1E-4F22-9FA2-31455EBCCA22\nProduct ID	00342-42727-09146-AAOEM\nSystem type	64-bit operating system, x64-based processor\nPen and touch	No pen or touch input is available for this display\n\nEdition	Windows 11 Home Single Language\nVersion	25H2\nInstalled on	‎04/‎09/‎2025\nOS build	26200.9445\nSerial number	GM0TB62Q\nExperience	Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(3, 1, 3, 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'Device name	UMSIDA2-GAME14\nProcessor	Intel(R) Core(TM) i7-14700 (2.10 GHz)\nInstalled RAM	8,00 GB (7,62 GB usable)\nGraphics card	Intel(R) UHD Graphics 770 (128 MB)\nStorage	242 GB of 477 GB used\nDevice ID	86C49993-3D1E-4F22-9FA2-31455EBCCA22\nProduct ID	00342-42727-09146-AAOEM\nSystem type	64-bit operating system, x64-based processor\nPen and touch	No pen or touch input is available for this display\n\nEdition	Windows 11 Home Single Language\nVersion	25H2\nInstalled on	‎04/‎09/‎2025\nOS build	26200.9445\nSerial number	GM0TB62Q\nExperience	Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(4, 1, 4, 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'Device name	UMSIDA2-GAME14\nProcessor	Intel(R) Core(TM) i7-14700 (2.10 GHz)\nInstalled RAM	8,00 GB (7,62 GB usable)\nGraphics card	Intel(R) UHD Graphics 770 (128 MB)\nStorage	242 GB of 477 GB used\nDevice ID	86C49993-3D1E-4F22-9FA2-31455EBCCA22\nProduct ID	00342-42727-09146-AAOEM\nSystem type	64-bit operating system, x64-based processor\nPen and touch	No pen or touch input is available for this display\n\nEdition	Windows 11 Home Single Language\nVersion	25H2\nInstalled on	‎04/‎09/‎2025\nOS build	26200.9445\nSerial number	GM0TB62Q\nExperience	Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(5, 1, 5, 'normal', 'normal', 'normal', 'normal', 'normal', 'noarmal', 'Device name	UMSIDA2-GAME14\nProcessor	Intel(R) Core(TM) i7-14700 (2.10 GHz)\nInstalled RAM	8,00 GB (7,62 GB usable)\nGraphics card	Intel(R) UHD Graphics 770 (128 MB)\nStorage	242 GB of 477 GB used\nDevice ID	86C49993-3D1E-4F22-9FA2-31455EBCCA22\nProduct ID	00342-42727-09146-AAOEM\nSystem type	64-bit operating system, x64-based processor\nPen and touch	No pen or touch input is available for this display\n\nEdition	Windows 11 Home Single Language\nVersion	25H2\nInstalled on	‎04/‎09/‎2025\nOS build	26200.9445\nSerial number	GM0TB62Q\nExperience	Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(6, 1, 6, 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'Device name	UMSIDA2-GAME14\nProcessor	Intel(R) Core(TM) i7-14700 (2.10 GHz)\nInstalled RAM	8,00 GB (7,62 GB usable)\nGraphics card	Intel(R) UHD Graphics 770 (128 MB)\nStorage	242 GB of 477 GB used\nDevice ID	86C49993-3D1E-4F22-9FA2-31455EBCCA22\nProduct ID	00342-42727-09146-AAOEM\nSystem type	64-bit operating system, x64-based processor\nPen and touch	No pen or touch input is available for this display\n\nEdition	Windows 11 Home Single Language\nVersion	25H2\nInstalled on	‎04/‎09/‎2025\nOS build	26200.9445\nSerial number	GM0TB62Q\nExperience	Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(7, 1, 7, 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'Device name	UMSIDA2-GAME14\nProcessor	Intel(R) Core(TM) i7-14700 (2.10 GHz)\nInstalled RAM	8,00 GB (7,62 GB usable)\nGraphics card	Intel(R) UHD Graphics 770 (128 MB)\nStorage	242 GB of 477 GB used\nDevice ID	86C49993-3D1E-4F22-9FA2-31455EBCCA22\nProduct ID	00342-42727-09146-AAOEM\nSystem type	64-bit operating system, x64-based processor\nPen and touch	No pen or touch input is available for this display\n\nEdition	Windows 11 Home Single Language\nVersion	25H2\nInstalled on	‎04/‎09/‎2025\nOS build	26200.9445\nSerial number	GM0TB62Q\nExperience	Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(8, 1, 8, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(9, 1, 9, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(10, 1, 10, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(11, 1, 11, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(12, 1, 12, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(13, 1, 13, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(14, 1, 14, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(15, 1, 15, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(16, 1, 16, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(17, 1, 17, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(18, 1, 18, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(19, 1, 19, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(20, 1, 20, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(21, 1, 21, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(22, 1, 22, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(23, 1, 23, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(24, 1, 24, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(25, 1, 25, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(26, 1, 26, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(27, 1, 27, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(28, 1, 28, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(29, 1, 29, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(30, 1, 30, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(31, 1, 31, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(32, 1, 32, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(33, 1, 33, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(34, 1, 34, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(35, 1, 35, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(36, 1, 36, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(37, 1, 37, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(38, 1, 38, 'normal', 'normal', 'normal', 'normal', 'normal', 'Nirmal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(39, 1, 39, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(40, 1, 40, 'normal', 'normal', 'normal', 'normal', 'normal', 'Normal', 'Device name UMSIDA2-GAME14 Processor Intel(R) Core(TM) i7-14700 (2.10 GHz) Installed RAM 8,00 GB (7,62 GB usable) Graphics card Intel(R) UHD Graphics 770 (128 MB) Storage 242 GB of 477 GB used Device ID 86C49993-3D1E-4F22-9FA2-31455EBCCA22 Product ID 00342-42727-09146-AAOEM System type 64-bit operating system, x64-based processor Pen and touch No pen or touch input is available for this display Edition Windows 11 Home Single Language Version 25H2 Installed on ‎04/‎09/‎2025 OS build 26200.9445 Serial number GM0TB62Q Experience Windows Feature Experience Pack 1000.26100.360.0', '2026-09-08 22:16:50', '2026-09-08 22:16:50'),
(41, 2, 1, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(42, 2, 2, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(43, 2, 3, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(44, 2, 4, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(45, 2, 5, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(46, 2, 6, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(47, 2, 7, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(48, 2, 8, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(49, 2, 9, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(50, 2, 10, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(51, 2, 11, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(52, 2, 12, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(53, 2, 13, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(54, 2, 14, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(55, 2, 15, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(56, 2, 16, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(57, 2, 17, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(58, 2, 18, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(59, 2, 19, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(60, 2, 20, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(61, 2, 21, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(62, 2, 22, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(63, 2, 23, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(64, 2, 24, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(65, 2, 25, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(66, 2, 26, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(67, 2, 27, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(68, 2, 28, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(69, 2, 29, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(70, 2, 30, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(71, 2, 31, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(72, 2, 32, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(73, 2, 33, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(74, 2, 34, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(75, 2, 35, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(76, 2, 36, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(77, 2, 37, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(78, 2, 38, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(79, 2, 39, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(80, 2, 40, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:12', '2026-09-09 16:55:12'),
(81, 3, 1, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(82, 3, 2, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(83, 3, 3, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(84, 3, 4, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(85, 3, 5, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(86, 3, 6, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(87, 3, 7, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(88, 3, 8, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(89, 3, 9, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(90, 3, 10, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(91, 3, 11, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(92, 3, 12, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(93, 3, 13, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(94, 3, 14, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(95, 3, 15, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(96, 3, 16, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(97, 3, 17, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(98, 3, 18, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(99, 3, 19, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(100, 3, 20, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(101, 3, 21, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(102, 3, 22, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(103, 3, 23, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(104, 3, 24, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(105, 3, 25, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(106, 3, 26, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(107, 3, 27, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(108, 3, 28, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(109, 3, 29, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(110, 3, 30, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(111, 3, 31, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(112, 3, 32, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(113, 3, 33, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(114, 3, 34, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(115, 3, 35, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(116, 3, 36, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(117, 3, 37, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(118, 3, 38, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(119, 3, 39, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(120, 3, 40, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:55:40', '2026-09-09 16:55:40'),
(121, 4, 1, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(122, 4, 2, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(123, 4, 3, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(124, 4, 4, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(125, 4, 5, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(126, 4, 6, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(127, 4, 7, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(128, 4, 8, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(129, 4, 9, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(130, 4, 10, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(131, 4, 11, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(132, 4, 12, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(133, 4, 13, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(134, 4, 14, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(135, 4, 15, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(136, 4, 16, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(137, 4, 17, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(138, 4, 18, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(139, 4, 19, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(140, 4, 20, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(141, 4, 21, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(142, 4, 22, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(143, 4, 23, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(144, 4, 24, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(145, 4, 25, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(146, 4, 26, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(147, 4, 27, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(148, 4, 28, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(149, 4, 29, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(150, 4, 30, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(151, 4, 31, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(152, 4, 32, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(153, 4, 33, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(154, 4, 34, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(155, 4, 35, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(156, 4, 36, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(157, 4, 37, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(158, 4, 38, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(159, 4, 39, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(160, 4, 40, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:02', '2026-09-09 16:56:02'),
(161, 5, 1, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(162, 5, 2, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(163, 5, 3, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(164, 5, 4, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(165, 5, 5, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(166, 5, 6, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(167, 5, 7, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(168, 5, 8, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(169, 5, 9, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(170, 5, 10, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(171, 5, 11, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(172, 5, 12, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(173, 5, 13, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(174, 5, 14, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(175, 5, 15, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(176, 5, 16, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(177, 5, 17, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(178, 5, 18, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(179, 5, 19, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(180, 5, 20, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(181, 5, 21, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(182, 5, 22, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(183, 5, 23, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(184, 5, 24, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(185, 5, 25, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(186, 5, 26, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(187, 5, 27, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(188, 5, 28, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(189, 5, 29, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(190, 5, 30, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(191, 5, 31, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(192, 5, 32, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(193, 5, 33, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(194, 5, 34, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(195, 5, 35, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(196, 5, 36, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(197, 5, 37, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(198, 5, 38, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(199, 5, 39, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(200, 5, 40, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:38', '2026-09-09 16:56:38'),
(201, 6, 1, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(202, 6, 2, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(203, 6, 3, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(204, 6, 4, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(205, 6, 5, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(206, 6, 6, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(207, 6, 7, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(208, 6, 8, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(209, 6, 9, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(210, 6, 10, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(211, 6, 11, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(212, 6, 12, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(213, 6, 13, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(214, 6, 14, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(215, 6, 15, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(216, 6, 16, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(217, 6, 17, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(218, 6, 18, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(219, 6, 19, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(220, 6, 20, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(221, 6, 21, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54');
INSERT INTO `inventaris_meja` (`id_meja`, `id_lab`, `nomor_meja`, `cpu_kondisi`, `keyboard_kondisi`, `mouse_kondisi`, `monitor_kondisi`, `kursi_kondisi`, `keterangan`, `spesifikasi_pc`, `created_at`, `updated_at`) VALUES
(222, 6, 22, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(223, 6, 23, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(224, 6, 24, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(225, 6, 25, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(226, 6, 26, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(227, 6, 27, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(228, 6, 28, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(229, 6, 29, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(230, 6, 30, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(231, 6, 31, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(232, 6, 32, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(233, 6, 33, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(234, 6, 34, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(235, 6, 35, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(236, 6, 36, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(237, 6, 37, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(238, 6, 38, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(239, 6, 39, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54'),
(240, 6, 40, 'normal', 'normal', 'normal', 'normal', 'normal', NULL, NULL, '2026-09-09 16:56:54', '2026-09-09 16:56:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventaris_meja`
--
ALTER TABLE `inventaris_meja`
  ADD PRIMARY KEY (`id_meja`),
  ADD UNIQUE KEY `inventaris_meja_id_lab_nomor_meja_unique` (`id_lab`,`nomor_meja`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventaris_meja`
--
ALTER TABLE `inventaris_meja`
  MODIFY `id_meja` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventaris_meja`
--
ALTER TABLE `inventaris_meja`
  ADD CONSTRAINT `inventaris_meja_id_lab_foreign` FOREIGN KEY (`id_lab`) REFERENCES `data_lab` (`id_lab`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
