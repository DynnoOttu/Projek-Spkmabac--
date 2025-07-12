-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 04:43 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_mabac`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` text NOT NULL,
  `role` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama`, `role`) VALUES
(1, 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Chika', '1'),
(2, 'nell', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'nelchan', '2');

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama` text NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama` text NOT NULL,
  `bobot` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `perhitungan`
--

CREATE TABLE `perhitungan` (
  `id_perhitungan` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `ipk` decimal(3,2) NOT NULL CHECK (`ipk` >= 0 and `ipk` <= 4),
  `penghasilan` decimal(12,2) NOT NULL CHECK (`penghasilan` >= 0),
  `ekstrakurik` tinyint(4) NOT NULL CHECK (`ekstrakurik` between 0 and 5),
  `prestasi` tinyint(4) NOT NULL CHECK (`prestasi` between 0 and 5),
  `total` decimal(8,4) DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nama`, `ipk`, `penghasilan`, `ekstrakurik`, `prestasi`, `total`) VALUES
(1, 'wempos', '3.50', '1500000.00', 1, 2, '0.0000'),
(2, 'wempi ', '2.97', '1000000.00', 5, 5, '0.0000'),
(3, 'JEKONIA NELCHIKA TITING', '3.59', '20000.00', 4, 3, '0.0000'),
(4, 'din', '3.50', '200000.00', 1, 1, '0.0000'),
(5, 'dini', '3.49', '1700000.00', 2, 1, '0.0000'),
(6, 'putra', '3.40', '1500000.00', 4, 2, '0.0000'),
(7, 'JEKONIA NELCHIKA TITING13333', '3.42', '20000.00', 2, 2, '0.0000'),
(8, 'sia', '3.44', '1.00', 1, 1, '0.0000'),
(9, 'ogam', '3.22', '20000.00', 1, 1, '0.0000'),
(10, 'ogam', '3.22', '20000.00', 1, 1, '0.0000'),
(11, 'ratna', '3.11', '20000.00', 1, 1, '0.0000'),
(12, 'yy', '3.80', '60000.00', 1, 1, '0.0000'),
(13, 'oo', '2.23', '100000.00', 1, 1, '0.0000'),
(14, 'll', '3.70', '20000.00', 1, 1, '0.0000'),
(15, 'kita', '2.50', '300000.00', 1, 1, '0.0000'),
(16, 'kita', '2.50', '300000.00', 1, 1, '0.0000'),
(17, 'kita', '2.50', '300000.00', 1, 1, '0.0000'),
(18, 'piutrytyytrr', '3.11', '30000.00', 1, 2, '0.0000'),
(19, 'eweewrwr', '1.39', '22000.00', 1, 1, '0.0000'),
(20, 'CHIKA', '3.60', '2500000.00', 3, 2, '0.0000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `perhitungan`
--
ALTER TABLE `perhitungan`
  ADD PRIMARY KEY (`id_perhitungan`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perhitungan`
--
ALTER TABLE `perhitungan`
  MODIFY `id_perhitungan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
