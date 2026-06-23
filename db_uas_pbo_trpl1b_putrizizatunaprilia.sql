-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2026 at 01:18 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uas_pbo_trpl1b_putrizizatunaprilia`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_karyawan`
--

CREATE TABLE `tabel_karyawan` (
  `id_karyawan` int NOT NULL,
  `nama_karyawan` varchar(150) NOT NULL,
  `departemen` varchar(100) NOT NULL,
  `hari_kerja_masuk` int NOT NULL,
  `gaji_dasar_per_hari` decimal(12,2) NOT NULL,
  `jenis_karyawan` enum('Kontrak','Tetap','Magang') NOT NULL,
  `durasi_kontrak_bulan` int DEFAULT NULL,
  `agensi_penyalur` varchar(100) DEFAULT NULL,
  `tunjangan_kesehatan` decimal(12,2) DEFAULT NULL,
  `opsi_saham_id` varchar(50) DEFAULT NULL,
  `uang_saku_bulanan` decimal(12,2) DEFAULT NULL,
  `sertifikat_kampus_merdeka` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_karyawan`
--

INSERT INTO `tabel_karyawan` (`id_karyawan`, `nama_karyawan`, `departemen`, `hari_kerja_masuk`, `gaji_dasar_per_hari`, `jenis_karyawan`, `durasi_kontrak_bulan`, `agensi_penyalur`, `tunjangan_kesehatan`, `opsi_saham_id`, `uang_saku_bulanan`, `sertifikat_kampus_merdeka`) VALUES
(1, 'Andi Wijaya', 'IT Support', 22, 150000.00, 'Kontrak', 12, 'PT Mitra Solusindo', NULL, NULL, NULL, NULL),
(2, 'Siti Rahma', 'Human Resource', 20, 140000.00, 'Kontrak', 6, 'PT Bakti Unggul', NULL, NULL, NULL, NULL),
(3, 'Budi Santoso', 'Finance', 21, 160000.00, 'Kontrak', 12, 'PT Mitra Solusindo', NULL, NULL, NULL, NULL),
(4, 'Citra Lestari', 'Marketing', 19, 135000.00, 'Kontrak', 6, 'PT Talent Source', NULL, NULL, NULL, NULL),
(5, 'Dimas Pratama', 'Operations', 23, 145000.00, 'Kontrak', 24, 'PT Bakti Unggul', NULL, NULL, NULL, NULL),
(6, 'Eka Wahyuni', 'Legal', 22, 170000.00, 'Kontrak', 12, 'PT Talent Source', NULL, NULL, NULL, NULL),
(7, 'Fajar Nugroho', 'IT Support', 20, 150000.00, 'Kontrak', 6, 'PT Mitra Solusindo', NULL, NULL, NULL, NULL),
(8, 'Rian Hidayat', 'Software Engineering', 22, 250000.00, 'Tetap', NULL, NULL, 500000.00, 'SHM-TRPL-001', NULL, NULL),
(9, 'Dewi Sartika', 'Quality Assurance', 21, 220000.00, 'Tetap', NULL, NULL, 450000.00, 'SHM-TRPL-002', NULL, NULL),
(10, 'Hendra Wijaya', 'Data Science', 23, 300000.00, 'Tetap', NULL, NULL, 600000.00, 'SHM-TRPL-003', NULL, NULL),
(11, 'Gita Permata', 'Product Management', 20, 280000.00, 'Tetap', NULL, NULL, 550000.00, 'SHM-TRPL-004', NULL, NULL),
(12, 'Irwan Saputra', 'DevOps', 22, 270000.00, 'Tetap', NULL, NULL, 500000.00, 'SHM-TRPL-005', NULL, NULL),
(13, 'Kartika Putri', 'UI/UX Design', 21, 240000.00, 'Tetap', NULL, NULL, 450000.00, 'SHM-TRPL-006', NULL, NULL),
(14, 'Luthfi Hakim', 'Software Engineering', 22, 260000.00, 'Tetap', NULL, NULL, 500000.00, 'SHM-TRPL-007', NULL, NULL),
(15, 'Rizky Amalia', 'Mobile Development', 18, 80000.00, 'Magang', NULL, NULL, NULL, NULL, 1500000.00, 'MSIB-REVOU-01'),
(16, 'Naufal Abdi', 'Web Development', 20, 80000.00, 'Magang', NULL, NULL, NULL, NULL, 1500000.00, 'MSIB-DICODING-02'),
(17, 'Aditya Putra', 'Network Security', 15, 85000.00, 'Magang', NULL, NULL, NULL, NULL, 1200000.00, 'MSIB-CISCO-03'),
(18, 'Tiara Andini', 'Content Writer', 22, 75000.00, 'Magang', NULL, NULL, NULL, NULL, 1500000.00, 'MSIB-LOKAL-04'),
(19, 'Zacky Ahmad', 'Graphic Design', 19, 75000.00, 'Magang', NULL, NULL, NULL, NULL, 1300000.00, 'MSIB-LOKAL-05'),
(20, 'Putri Amelia', 'Web Development', 21, 80000.00, 'Magang', NULL, NULL, NULL, NULL, 1500000.00, 'MSIB-BISA_AI-06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  MODIFY `id_karyawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
