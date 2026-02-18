-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2026 at 05:42 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tugasakhirs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('karya|127.0.0.1', 'i:2;', 1746430295),
('karya|127.0.0.1:timer', 'i:1746430295;', 1746430295),
('s160421056|127.0.0.1', 'i:1;', 1755080782),
('s160421056|127.0.0.1:timer', 'i:1755080782;', 1755080782);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `distributors`
--

CREATE TABLE `distributors` (
  `id` int(11) NOT NULL,
  `nama` varchar(225) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `distributors`
--
-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gudangs`
--

CREATE TABLE `gudangs` (
  `id` int(11) NOT NULL,
  `lokasi` varchar(45) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `gudangs`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notabelis`
--

CREATE TABLE `notabelis` (
  `id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `pegawai_id` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `notabelis`
--
-- --------------------------------------------------------

--
-- Table structure for table `notabelis_has_produks`
--

CREATE TABLE `notabelis_has_produks` (
  `notabelis_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` double NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `produkbatches_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `notabelis_has_produks`
--
-- --------------------------------------------------------

--
-- Table structure for table `notajuals`
--

CREATE TABLE `notajuals` (
  `id` int(11) NOT NULL,
  `pegawai_id` int(11) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `notajuals`
--


--
-- Table structure for table `notajuals_has_produks`
--

CREATE TABLE `notajuals_has_produks` (
  `notajuals_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` double NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `produkbatches_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `notajuals_has_produks`
--
-- --------------------------------------------------------

--
-- Table structure for table `notajuals_has_racikans`
--

CREATE TABLE `notajuals_has_racikans` (
  `notajuals_id` int(11) NOT NULL,
  `racikans_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` double NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `notajuals_has_racikans`
--
-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produkbatches`
--

CREATE TABLE `produkbatches` (
  `id` int(11) NOT NULL,
  `produks_id` int(11) NOT NULL,
  `tgl_produksi` date DEFAULT NULL,
  `tgl_datang` date DEFAULT NULL,
  `tgl_kadaluarsa` date DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `unitprice` double NOT NULL,
  `status` enum('discontinued','tersedia','proses_order') DEFAULT NULL,
  `distributors_id` int(11) NOT NULL,
  `satuans_id` int(11) NOT NULL,
  `gudangs_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produkbatches`
--
-- --------------------------------------------------------

--
-- Table structure for table `produks`
--

CREATE TABLE `produks` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `golongan` enum('bebas','terbatas','keras') NOT NULL,
  `sellingprice` double NOT NULL,
  `image` blob DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `produks`
--
-- --------------------------------------------------------

--
-- Table structure for table `profilapoteks`
--

CREATE TABLE `profilapoteks` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) NOT NULL,
  `alamat` varchar(45) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` blob DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `jam_operasional` varchar(255) NOT NULL,
  `pemilik_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `profilapoteks`
--
-- --------------------------------------------------------

--
-- Table structure for table `racikanproduks`
--

CREATE TABLE `racikanproduks` (
  `racikans_id` int(11) NOT NULL,
  `produks_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `racikanproduks`
--
-- --------------------------------------------------------

--
-- Table structure for table `racikans`
--

CREATE TABLE `racikans` (
  `id` int(11) NOT NULL,
  `nama` varchar(225) NOT NULL,
  `biaya_embalase` double NOT NULL,
  `deskripsi` text NOT NULL,
  `nama_dokter` varchar(255) NOT NULL,
  `nama_pasien` varchar(255) NOT NULL,
  `aturan_pakai` text NOT NULL,
  `bukti_resep` blob DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `racikans`
--
-- --------------------------------------------------------

--
-- Table structure for table `satuans`
--

CREATE TABLE `satuans` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `satuans`
--
-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('A0Qbz96OUzEQX5WN4JZSrV5MBaiKimS72owyaPAA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYmx0ZDhTZVp3a21IYm9ONUtkd3VraXNXV3NqUUxmc1ZiQ3YxUTZXbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771389518);

-- --------------------------------------------------------

--
-- Table structure for table `terimabatches`
--

CREATE TABLE `terimabatches` (
  `id` int(11) NOT NULL,
  `produkbatches_id` int(11) NOT NULL,
  `pegawai_id` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `gudangs_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terimabatches`
--
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(225) NOT NULL,
  `email` varchar(105) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `image` blob DEFAULT NULL,
  `tipe_user` enum('karyawan','admin') NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `email_verified_at`, `no_hp`, `username`, `password`, `remember_token`, `created_at`, `updated_at`, `image`, `tipe_user`, `deleted_at`) VALUES
(1, 'Admin', 'admin@email.com', NULL, '081081081', 'admin', '$2y$12$Byy6MsDm0gqeAJtsEZaIoeM8pTYUOpx99umTyPxT5CHGf38lFyqMu', NULL, '2025-04-08 08:11:51', '2025-07-22 17:34:24', 0x313735333230353636345f6578616d706c652070726f66207069632e706e67, 'admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `distributors`
--
ALTER TABLE `distributors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gudangs`
--
ALTER TABLE `gudangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notabelis`
--
ALTER TABLE `notabelis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notabelis_users1_idx` (`pegawai_id`);

--
-- Indexes for table `notabelis_has_produks`
--
ALTER TABLE `notabelis_has_produks`
  ADD PRIMARY KEY (`notabelis_id`,`produkbatches_id`),
  ADD KEY `fk_notabelis_has_produks_notabelis1_idx` (`notabelis_id`),
  ADD KEY `fk_notabelis_has_produks_produkbatches1_idx` (`produkbatches_id`);

--
-- Indexes for table `notajuals`
--
ALTER TABLE `notajuals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tNotaJual_tUser1_idx` (`pegawai_id`);

--
-- Indexes for table `notajuals_has_produks`
--
ALTER TABLE `notajuals_has_produks`
  ADD PRIMARY KEY (`notajuals_id`,`produkbatches_id`),
  ADD KEY `fk_notajuals_has_produks_notajuals1_idx` (`notajuals_id`),
  ADD KEY `fk_notajuals_has_produks_produkbatches1_idx` (`produkbatches_id`);

--
-- Indexes for table `notajuals_has_racikans`
--
ALTER TABLE `notajuals_has_racikans`
  ADD PRIMARY KEY (`notajuals_id`,`racikans_id`),
  ADD KEY `fk_notajuals_has_racikans_racikans1_idx` (`racikans_id`),
  ADD KEY `fk_notajuals_has_racikans_notajuals1_idx` (`notajuals_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `produkbatches`
--
ALTER TABLE `produkbatches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produk_batches_distributors1_idx` (`distributors_id`),
  ADD KEY `fk_produk_batches_satuans1_idx` (`satuans_id`),
  ADD KEY `fk_produk_batches_gudangs1_idx` (`gudangs_id`),
  ADD KEY `fk_produk_batches_produks` (`produks_id`);

--
-- Indexes for table `produks`
--
ALTER TABLE `produks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profilapoteks`
--
ALTER TABLE `profilapoteks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tProfilApotek_tUser1_idx` (`pemilik_id`);

--
-- Indexes for table `racikanproduks`
--
ALTER TABLE `racikanproduks`
  ADD PRIMARY KEY (`racikans_id`,`produks_id`),
  ADD KEY `fk_racikans_has_produks_produks1_idx` (`produks_id`),
  ADD KEY `fk_racikans_has_produks_racikans_idx` (`racikans_id`);

--
-- Indexes for table `racikans`
--
ALTER TABLE `racikans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `satuans`
--
ALTER TABLE `satuans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `terimabatches`
--
ALTER TABLE `terimabatches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_terimabatches_users1_idx` (`pegawai_id`),
  ADD KEY `fk_terimabatches_batch_idx` (`produkbatches_id`),
  ADD KEY `fk_terimabatches_gudangs1_idx` (`gudangs_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `distributors`
--
ALTER TABLE `distributors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gudangs`
--
ALTER TABLE `gudangs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notabelis`
--
ALTER TABLE `notabelis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `notajuals`
--
ALTER TABLE `notajuals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `produkbatches`
--
ALTER TABLE `produkbatches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `produks`
--
ALTER TABLE `produks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `profilapoteks`
--
ALTER TABLE `profilapoteks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `racikans`
--
ALTER TABLE `racikans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `satuans`
--
ALTER TABLE `satuans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `terimabatches`
--
ALTER TABLE `terimabatches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notabelis`
--
ALTER TABLE `notabelis`
  ADD CONSTRAINT `fk_notabelis_users1` FOREIGN KEY (`pegawai_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notabelis_has_produks`
--
ALTER TABLE `notabelis_has_produks`
  ADD CONSTRAINT `fk_notabelis_has_produks_notabelis1` FOREIGN KEY (`notabelis_id`) REFERENCES `notabelis` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_notabelis_has_produks_produkbatches1` FOREIGN KEY (`produkbatches_id`) REFERENCES `produkbatches` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notajuals`
--
ALTER TABLE `notajuals`
  ADD CONSTRAINT `fk_tNotaJual_tUser1` FOREIGN KEY (`pegawai_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notajuals_has_produks`
--
ALTER TABLE `notajuals_has_produks`
  ADD CONSTRAINT `fk_notajuals_has_produks_notajuals1` FOREIGN KEY (`notajuals_id`) REFERENCES `notajuals` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_notajuals_has_produks_produkbatches1` FOREIGN KEY (`produkbatches_id`) REFERENCES `produkbatches` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notajuals_has_racikans`
--
ALTER TABLE `notajuals_has_racikans`
  ADD CONSTRAINT `fk_notajuals_has_racikans_notajuals1` FOREIGN KEY (`notajuals_id`) REFERENCES `notajuals` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_notajuals_has_racikans_racikans1` FOREIGN KEY (`racikans_id`) REFERENCES `racikans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `produkbatches`
--
ALTER TABLE `produkbatches`
  ADD CONSTRAINT `fk_produk_batches_distributors1` FOREIGN KEY (`distributors_id`) REFERENCES `distributors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produk_batches_gudangs1` FOREIGN KEY (`gudangs_id`) REFERENCES `gudangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produk_batches_produks` FOREIGN KEY (`produks_id`) REFERENCES `produks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produk_batches_satuans1` FOREIGN KEY (`satuans_id`) REFERENCES `satuans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `profilapoteks`
--
ALTER TABLE `profilapoteks`
  ADD CONSTRAINT `fk_tProfilApotek_tUser1` FOREIGN KEY (`pemilik_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `racikanproduks`
--
ALTER TABLE `racikanproduks`
  ADD CONSTRAINT `fk_racikans_has_produks_produks1` FOREIGN KEY (`produks_id`) REFERENCES `produks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_racikans_has_produks_racikans` FOREIGN KEY (`racikans_id`) REFERENCES `racikans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `terimabatches`
--
ALTER TABLE `terimabatches`
  ADD CONSTRAINT `fk_terimabatches_gudangs1` FOREIGN KEY (`gudangs_id`) REFERENCES `gudangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_terimabatches_produkbatches` FOREIGN KEY (`produkbatches_id`) REFERENCES `produkbatches` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_terimabatches_users1` FOREIGN KEY (`pegawai_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
