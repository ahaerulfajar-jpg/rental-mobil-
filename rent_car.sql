-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2025 at 01:31 PM
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
-- Database: `rent_car`
--

-- --------------------------------------------------------

--
-- Table structure for table `mobil`
--

CREATE TABLE `mobil` (
  `id` int(11) NOT NULL,
  `nama_mobil` varchar(100) NOT NULL,
  `tipe_mobil` varchar(50) NOT NULL,
  `tahun` int(11) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `transmisi` varchar(20) NOT NULL,
  `bahan_bakar` varchar(20) NOT NULL,
  `kursi` int(11) NOT NULL,
  `harga_sewa_per_hari` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Tersedia',
  `gambar_mobil` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mobil`
--

INSERT INTO `mobil` (`id`, `nama_mobil`, `tipe_mobil`, `tahun`, `kapasitas`, `transmisi`, `bahan_bakar`, `kursi`, `harga_sewa_per_hari`, `status`, `gambar_mobil`, `created_at`) VALUES
(5, 'Toyota Alphard', 'MPV', 2025, 8, 'Automatic', 'Bensin', 0, 2000000, 'Tersedia', 'alphard.png', '2025-11-16 18:18:26'),
(6, 'Toyota Fortuner', 'SUV', 2022, 5, 'Manual/Automatic', 'Solar', 0, 1000000, 'Tersedia', 'fortuner.png', '2025-11-16 18:22:04'),
(7, 'Toyota Kijang Innova Reborn', 'Minibus', 2022, 8, 'Manual/Automatic', 'Solar', 0, 500000, 'Tersedia', 'innova reborn.jpg', '2025-11-16 18:31:03'),
(8, 'Daihatsu Sigra', 'Minibus', 2022, 6, 'Manual/Automatic', 'Bensin', 0, 250000, 'Tersedia', 'sigra.jpg', '2025-11-16 19:02:19'),
(9, 'Hiace Premio', 'Minibus', 2021, 11, 'Manual/Automatic', 'Bensin', 0, 1500000, 'Disewa', 'hiace.png', '2025-11-17 17:56:54'),
(10, 'Honda Brio', 'Hatchback', 2023, 5, 'Manual/Automatic', 'Bensin', 0, 300000, 'Tersedia', 'VmfueMMOko09BwpogWPFmBUShLbLDzik4wPP6AFz.png', '2025-11-17 18:21:49'),
(14, 'Honda Mobilio', 'Minibus', 2020, 7, 'Manual/Automatic', 'Bensin', 0, 300000, 'Tersedia', 'mobilio.png', '2025-11-18 16:29:36'),
(15, 'All New Xenia', 'Minibus', 2023, 7, 'Manual/Automatic', 'Bensin', 0, 300000, 'Tersedia', 'xenia.png', '2025-11-24 14:05:30'),
(16, 'All New Avanza', 'Minibus', 2023, 7, 'Manual/Automatic', 'Bensin', 0, 300000, 'Tersedia', 'veloz.png', '2025-11-24 14:29:05'),
(17, 'Mitshubishi Xpander', 'MPV', 2022, 7, 'CVT', 'Bensin', 0, 400000, 'Tersedia', 'xpander.png', '2025-11-25 20:00:24');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `email`, `telepon`, `alamat`, `password`, `created_at`) VALUES
(1, 'Haerul Fajar', 'ahaerulfajar@gmail.com', '082189997569', NULL, '$2y$10$Rzc6VDFnjEvTTSP/0cLbX.bgB2CsNRTdPuRkHAQbu0QYvWgMG2y7e', '2025-11-24 17:20:53');

-- --------------------------------------------------------

--
-- Table structure for table `sopir`
--

CREATE TABLE `sopir` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','dipesan','tidak_aktif') DEFAULT 'tersedia',
  `harga_per_hari` int(11) DEFAULT 100000,
  `tanggal_bergabung` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sopir`
--

INSERT INTO `sopir` (`id`, `nama`, `telepon`, `email`, `alamat`, `foto`, `status`, `harga_per_hari`, `tanggal_bergabung`, `created_at`) VALUES
(1, 'Haerul Fajar', '082189997569', NULL, NULL, '1764170786_person.jpg', 'dipesan', 100000, NULL, '2025-11-26 15:26:26'),
(2, 'Ferdiansyah', '1234567890', NULL, NULL, '1765040702_1764170786_person.jpg', 'tersedia', 50000, NULL, '2025-12-06 17:05:02');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `mobil_id` int(11) NOT NULL,
  `pelanggan_id` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `alamat_jemput` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `pakai_sopir` enum('ya','tidak') DEFAULT 'tidak',
  `sopir_id` int(11) DEFAULT NULL,
  `biaya_sopir` int(11) DEFAULT 0,
  `status` enum('Menunggu','Disetujui','Ditolak','Selesai') DEFAULT 'Menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','pelanggan') DEFAULT 'pelanggan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(5, 'qwerty', 'admin@gmail.com', '$2y$10$.6FU.K3BcR31DFwfy62xiui67wZL69k3YDeA/aCDMNaCj19vS.ZtC', 'admin', '2025-11-05 12:57:15'),
(6, 'herul', 'herul@gmail.com', '$2y$10$4xZcQL2PMM9YVpe/Oe2fYOZWWFukaRxKarZ6oUUU1F7cXCxKfZ3D2', 'admin', '2025-11-05 20:04:57'),
(8, 'herul', 'qwerty@gmail.com', '$2y$10$2yfNcmV43FHUtI/.zQOPquY3iqA3/gjzKd1h7u7Dk3da8DDtZVr3m', '', '2025-11-14 00:18:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `sopir`
--
ALTER TABLE `sopir`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mobil`
--
ALTER TABLE `mobil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sopir`
--
ALTER TABLE `sopir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
