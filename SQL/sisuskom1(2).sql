-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 24 Jan 2026 pada 14.00
-- Versi server: 10.11.13-MariaDB-0ubuntu0.24.04.1
-- Versi PHP: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sisuskom1`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_asesi`
--

CREATE TABLE `tb_asesi` (
  `id_asesi` int(11) NOT NULL,
  `nama_asesi` varchar(100) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `jenis_kelamin` varchar(50) NOT NULL,
  `kebangsaan` varchar(20) NOT NULL,
  `alamat_rumah` varchar(100) NOT NULL,
  `kode_pos` varchar(6) NOT NULL,
  `phone_rumah` varchar(15) DEFAULT NULL,
  `phone_kantor` varchar(15) DEFAULT NULL,
  `hp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pendidikan` varchar(50) DEFAULT NULL,
  `nama_institusi` varchar(30) NOT NULL,
  `jabatan` varchar(17) NOT NULL,
  `alamat_institusi` varchar(100) NOT NULL,
  `kode_pos_institusi` varchar(6) NOT NULL,
  `telp_institusi` varchar(15) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email_institusi` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_asesi`
--

INSERT INTO `tb_asesi` (`id_asesi`, `nama_asesi`, `nik`, `jenis_kelamin`, `kebangsaan`, `alamat_rumah`, `kode_pos`, `phone_rumah`, `phone_kantor`, `hp`, `email`, `pendidikan`, `nama_institusi`, `jabatan`, `alamat_institusi`, `kode_pos_institusi`, `telp_institusi`, `fax`, `email_institusi`) VALUES
(1, 'zee', '1234343414341', 'Laki-laki', 'indo', 'qiueroiuqioruqiwruo', '21313', 'NULL', 'NULL', '0811111111', 'rb4k0r1sk@mozmail.com', 'jmp', 'wkwkkw', 'wkwkkwkwk', 'kwkwkw', '11234', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_asesor`
--

CREATE TABLE `tb_asesor` (
  `id_asesor` int(11) NOT NULL,
  `no_reg` varchar(30) NOT NULL,
  `nama_asesor` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(50) NOT NULL,
  `alamat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_elemen`
--

CREATE TABLE `tb_elemen` (
  `id_elemen` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `no_elemen` varchar(3) NOT NULL,
  `nama_elemen` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_elemen`
--

INSERT INTO `tb_elemen` (`id_elemen`, `id_unit`, `no_elemen`, `nama_elemen`) VALUES
(1, 1, '100', 'HTML dan CSS'),
(2, 2, '200', 'JavaScript'),
(3, 3, '300', 'Layout'),
(4, 4, '400', 'Air');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kuk`
--

CREATE TABLE `tb_kuk` (
  `id_kuk` int(11) NOT NULL,
  `id_elemen` int(11) NOT NULL,
  `no_kuk` varchar(3) NOT NULL,
  `kuk` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_kuk`
--

INSERT INTO `tb_kuk` (`id_kuk`, `id_elemen`, `no_kuk`, `kuk`) VALUES
(1, 1, '100', 'HTML'),
(2, 1, '200', 'CSS'),
(3, 2, '300', 'js'),
(4, 3, '400', 'Membuat grid');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_skema`
--

CREATE TABLE `tb_skema` (
  `id_skema` int(11) NOT NULL,
  `nomor_skema` varchar(100) NOT NULL,
  `judul_skema` varchar(100) NOT NULL,
  `standar_kompetensi_kerja` varchar(100) NOT NULL,
  `id_asesor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_skema`
--

INSERT INTO `tb_skema` (`id_skema`, `nomor_skema`, `judul_skema`, `standar_kompetensi_kerja`, `id_asesor`) VALUES
(1, 'SKM-001', 'Skema Webb', 'SKKNI 20900', 1),
(2, 'SKM-002', 'Skema Desai', 'SKKNI 2029999', 2),
(3, 'SKM-003', 'Skema Web3', 'SKKNI 209023', 3),
(4, 'SKM-004', 'Skema Web4', 'SKKNI 20934783', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_unit_kompetensi`
--

CREATE TABLE `tb_unit_kompetensi` (
  `id_unit` int(11) NOT NULL,
  `id_skema` int(11) NOT NULL,
  `kode_unit` varchar(100) NOT NULL,
  `judul_unit` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_unit_kompetensi`
--

INSERT INTO `tb_unit_kompetensi` (`id_unit`, `id_skema`, `kode_unit`, `judul_unit`) VALUES
(1, 1, 'WP01', 'Dasar Web'),
(2, 2, 'WP02', 'Frontend'),
(3, 3, 'DG01', 'Dasar'),
(4, 4, 'OOO1', 'DASARNYA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--   
    

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Asesor','Asesi') NOT NULL,
  `id_referensi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `id_referensi`) VALUES
(15, 'adminmudikal', 'Admin1234', 'Admin', NULL),
(17, 'asesor1', 'Admin1234', 'Asesor', NULL),
(18, 'asesor2', 'Admin1234', 'Asesor', NULL),
(19, 'assesi1', 'Admin1234', 'Asesi', 1),
(20, 'assesi2', 'Admin1234', 'Asesi', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_asesi`
--
ALTER TABLE `tb_asesi`
  ADD PRIMARY KEY (`id_asesi`);

--
-- Indeks untuk tabel `tb_asesor`
--
ALTER TABLE `tb_asesor`
  ADD PRIMARY KEY (`id_asesor`);

--
-- Indeks untuk tabel `tb_elemen`
--
ALTER TABLE `tb_elemen`
  ADD PRIMARY KEY (`id_elemen`),
  ADD KEY `fk_elemen_unit` (`id_unit`);

--
-- Indeks untuk tabel `tb_kuk`
--
ALTER TABLE `tb_kuk`
  ADD PRIMARY KEY (`id_kuk`),
  ADD KEY `fk_kuk_elemen` (`id_elemen`);

--
-- Indeks untuk tabel `tb_skema`
--
ALTER TABLE `tb_skema`
  ADD PRIMARY KEY (`id_skema`),
  ADD KEY `fk_skema_asesor` (`id_asesor`);

--
-- Indeks untuk tabel `tb_unit_kompetensi`
--
ALTER TABLE `tb_unit_kompetensi`
  ADD PRIMARY KEY (`id_unit`),
  ADD KEY `id_skema` (`id_skema`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `nik` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_asesi`
--
ALTER TABLE `tb_asesi`
  MODIFY `id_asesi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_asesor`
--
ALTER TABLE `tb_asesor`
  MODIFY `id_asesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tb_elemen`
--
ALTER TABLE `tb_elemen`
  MODIFY `id_elemen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_kuk`
--
ALTER TABLE `tb_kuk`
  MODIFY `id_kuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_skema`
--
ALTER TABLE `tb_skema`
  MODIFY `id_skema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_unit_kompetensi`
--
ALTER TABLE `tb_unit_kompetensi`
  MODIFY `id_unit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_elemen`
--
ALTER TABLE `tb_elemen`
  ADD CONSTRAINT `fk_elemen_unit` FOREIGN KEY (`id_unit`) REFERENCES `tb_unit_kompetensi` (`id_unit`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_kuk`
--
ALTER TABLE `tb_kuk`
  ADD CONSTRAINT `fk_kuk_elemen` FOREIGN KEY (`id_elemen`) REFERENCES `tb_elemen` (`id_elemen`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_skema`
--
ALTER TABLE `tb_skema`
  ADD CONSTRAINT `fk_skema_asesor` FOREIGN KEY (`id_asesor`) REFERENCES `tb_asesor` (`id_asesor`);

--
-- Ketidakleluasaan untuk tabel `tb_unit_kompetensi`
--
ALTER TABLE `tb_unit_kompetensi`
  ADD CONSTRAINT `fk_unit_skema` FOREIGN KEY (`id_skema`) REFERENCES `tb_skema` (`id_skema`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_unit_kompetensi_ibfk_1` FOREIGN KEY (`id_skema`) REFERENCES `tb_skema` (`id_skema`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
