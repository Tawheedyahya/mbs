-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2026 at 06:16 AM
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
-- Database: `mbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hospital_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `patient_email` varchar(255) NOT NULL,
  `patient_phone` varchar(255) NOT NULL,
  `age` tinyint(3) UNSIGNED DEFAULT NULL,
  `cause` text DEFAULT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `status` enum('unverified','pending','accepted','rejected','cancelled') NOT NULL DEFAULT 'unverified',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `action_token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `hospital_id`, `doctor_id`, `patient_name`, `patient_email`, `patient_phone`, `age`, `cause`, `booking_date`, `start_time`, `end_time`, `status`, `approved_by`, `approved_at`, `action_token`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'suva', 'suva@gmail.com', '74827346923', 20, 'fever', '2026-02-05', '17:00:00', NULL, 'rejected', NULL, NULL, 'BK-XL5MK36W', '2026-02-05 04:58:28', '2026-02-10 03:27:08'),
(2, 1, 1, 'Tawheed Yahya', 'tawheedyahya0@gmail.com', '23243423', 20, 'fever', '2026-02-06', '10:00:00', NULL, 'rejected', NULL, NULL, 'BK-OQGSTDOO', '2026-02-06 03:03:49', '2026-02-11 03:25:09'),
(3, 1, 1, 'suva', 'suva@gmail.com', '919994780436', 20, 'fever', '2026-02-06', '10:20:00', NULL, 'rejected', NULL, NULL, 'BK-LQQHZMTB', '2026-02-06 04:39:22', '2026-02-10 03:26:40'),
(4, 1, 1, 'Tawheed yahya J', 'tawheedyahya0@gmail.com', '919994780436', 20, 'fever', '2026-02-10', '11:00:00', NULL, 'accepted', NULL, NULL, 'BK-OVVZAUU2', '2026-02-10 03:28:31', '2026-02-10 03:28:45'),
(5, 1, 1, 'Tawheed yahya J', 'tawheedyahya0@gmail.com', '919994780436', 10, 'dasdsa', '2026-02-10', '13:20:00', NULL, 'accepted', NULL, NULL, 'BK-FXZSI3G8', '2026-02-10 03:31:28', '2026-02-10 03:31:40'),
(6, 1, 1, 'Tawheed yahya J', 'tawheedyahya0@gmail.com', '919994780436', 20, 'das', '2026-02-10', '12:20:00', NULL, 'accepted', NULL, NULL, 'BK-ZTVGPL6V', '2026-02-10 03:38:51', '2026-02-10 03:39:07'),
(7, 1, 1, 'suva', 'suva@gmail.com', '919994780436', 20, 'fever', '2026-02-11', '15:00:00', NULL, 'rejected', NULL, NULL, 'BK-XQDXUIET', '2026-02-11 03:24:04', '2026-02-11 03:25:44');

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
('speedbots-cache-css_asset_version', 'i:1770799618;', 1770875007),
('speedbots-cache-js_asset_version', 'i:1768376197;', 1770875007);

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
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hospital_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `specialization_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_code` varchar(255) NOT NULL,
  `experience_years` tinyint(3) UNSIGNED NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slot` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `hospital_id`, `name`, `gender`, `profile_photo`, `specialization_id`, `doctor_code`, `experience_years`, `qualification`, `phone`, `created_at`, `updated_at`, `slot`) VALUES
(1, 1, 'abinav', 'male', 'doctors/1770285329_69846911c2559.png', 1, '93ULQCFFOPJBKI79YHAP', 2, 'MBBS', '9994780436', '2026-02-05 04:25:32', '2026-02-05 04:57:35', 20);

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
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hospital_name` varchar(255) NOT NULL,
  `hospital_code` varchar(255) NOT NULL,
  `hospital_phone` varchar(255) NOT NULL,
  `hospital_logo` varchar(255) DEFAULT NULL COMMENT 'path or url',
  `admin_name` varchar(255) NOT NULL,
  `admin_phone` varchar(255) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `db_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=testing,1=production',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `flow_id` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `hospital_name`, `hospital_code`, `hospital_phone`, `hospital_logo`, `admin_name`, `admin_phone`, `address_line`, `address_line2`, `city`, `country`, `db_status`, `created_at`, `updated_at`, `flow_id`, `token`) VALUES
(1, 'hospital1', 'R9YW7F5QBYREI9QPRZUS', '9994780436', 'hospital_logos/1770285163_Link_(1).png', 'hospital1', '9994780436', 'kuniyamputhur', 'cbe', 'cbe', 'india', 0, '2026-02-05 04:22:47', '2026-02-06 04:03:08', '1770292134026', '1785015.eAUFZyQSPurevCX9jMGIaV5v0kyGx0wYY7b8pf4g9sdz9vR8'),
(2, 'hospital2', 'BNPMBEFZ2GFACPWLCEPE', '09994780436', 'hospital_logos/1770370656_cr7.jpg', 'yahi', '23999123213', '76,vallal nagar', 'Karumbbukatai', 'Coimbatore', 'India', 0, '2026-02-06 04:07:40', '2026-02-06 04:08:33', '1770292134026', '1785015.eAUFZyQSPurevCX9jMGIaV5v0kyGx0wYY7b8pf4g9sdz9vR8');

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

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '1_hospitals', 1),
(2, '2_specialization', 1),
(3, '3_doctors', 1),
(4, '4_users', 1),
(5, '5_cache', 1),
(6, '6_jobs', 1),
(7, '7_specialization_description', 2),
(8, '8_atttr_doctors', 3),
(9, '9_attr_specialization', 4),
(12, '10_schedule', 5),
(16, '11_bookings', 6),
(17, '12_slot_column', 7),
(18, '2026_02_06_084715_add_flowid_in_hospitals', 8),
(19, '2026_02_06_094718_add_token_in_hospitals', 9);

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
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `day` varchar(255) NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `is_off` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `doctor_id`, `day`, `start_time`, `end_time`, `is_off`, `created_at`, `updated_at`) VALUES
(1, 1, 'Monday', '15:00:00', '20:00:00', 0, '2026-02-05 04:57:27', '2026-02-05 04:57:27'),
(2, 1, 'Tuesday', '10:00:00', '15:00:00', 0, '2026-02-05 04:57:27', '2026-02-05 04:57:27'),
(3, 1, 'Wednesday', '15:00:00', '18:56:00', 0, '2026-02-05 04:57:27', '2026-02-05 04:57:27'),
(4, 1, 'Thursday', '16:00:00', '18:00:00', 0, '2026-02-05 04:57:27', '2026-02-05 04:57:27'),
(5, 1, 'Friday', '10:00:00', '15:00:00', 0, '2026-02-05 04:57:27', '2026-02-05 04:57:27'),
(6, 1, 'Saturday', NULL, NULL, 1, '2026-02-05 04:57:27', '2026-02-05 04:57:27'),
(7, 1, 'Sunday', NULL, NULL, 1, '2026-02-05 04:57:27', '2026-02-05 04:57:27');

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
('TEZJUxPZIIrGcTFXOO7KmVncgzoI2cgztevwV5bx', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVEpjZTFMMkhIZ0xsS3J6dUI0TWZnd2gxaGJVNXdiSXVKZ1dOU2V2MiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob3NwaXRhbF9hZG1pbi9zcGVjaWFsaXphdGlvbiI7czo1OiJyb3V0ZSI7czoyOToiaG9zcGl0YWxfYWRtaW4uc3BlY2lhbGl6YXRpb24iO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc3MDg3MTcxNjt9fQ==', 1770871813);

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hospital_id` bigint(20) UNSIGNED DEFAULT NULL,
  `specialization` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`id`, `hospital_id`, `specialization`, `created_at`, `updated_at`, `description`) VALUES
(1, 1, 'MBBS', '2026-02-05 04:24:17', '2026-02-05 04:24:17', 'specialized in child care');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','hospital_admin','doctor') NOT NULL,
  `hospital_id` bigint(20) UNSIGNED DEFAULT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=inactive,1=active',
  `api_code` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `hospital_id`, `doctor_id`, `status`, `api_code`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'admin@gmail.com', '2026-02-05 04:19:55', '$2y$12$aDYG3RYTASs9DiCqzOVuLeEYSdqoUUcfJ9U6GX115CLXzXeSL0PPW', 'super_admin', NULL, NULL, 1, 'nithii', 'u2gFbrLRqxCNPw5oFMY92r6k09DJN2rXAla7EnRp6AtkZn6KCHiOkR31XWHt', '2026-02-05 04:19:56', '2026-02-05 04:19:56'),
(3, 'hospital1', 'hospital1@gmail.com', NULL, '$2y$12$XWYI4K8VjD0B8vUQU28uKuNOY/IeMOeDhxXkdGsn/MwDo4ODldBvq', 'hospital_admin', 1, NULL, 1, 'MMNBCQQIR2HSBJK9DLIK', NULL, '2026-02-05 04:22:47', '2026-02-05 04:22:47'),
(4, 'abinav', 'abinav@gmail.com', NULL, '$2y$12$2sHdED.oyEuXMkGolg2ay.YenI4WfAV/rjFuXjRaGrIdOqEr23vny', 'doctor', 1, 1, 1, 'NBZ6OIZYXQ7JZRBWRSIP', NULL, '2026-02-05 04:25:32', '2026-02-05 04:25:32'),
(5, 'yahi', 'hospital2@gmail.com', NULL, '$2y$12$2E/W6sATTglu8WG6KUwRtuXHFpzD1HlqV1yF0TCuORM/B37L7BqRO', 'hospital_admin', 2, NULL, 1, '90NKHUAF7CEVWIS0UASF', NULL, '2026-02-06 04:07:40', '2026-02-06 04:07:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_action_token_unique` (`action_token`),
  ADD KEY `doctor_date_idx` (`doctor_id`,`booking_date`),
  ADD KEY `hospital_date_idx` (`hospital_id`,`booking_date`),
  ADD KEY `bookings_approved_by_foreign` (`approved_by`),
  ADD KEY `bookings_patient_email_index` (`patient_email`),
  ADD KEY `bookings_patient_phone_index` (`patient_phone`),
  ADD KEY `bookings_booking_date_index` (`booking_date`),
  ADD KEY `bookings_status_index` (`status`);

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
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctors_hospital_id_phone_unique` (`hospital_id`,`phone`),
  ADD KEY `doctors_specialization_id_foreign` (`specialization_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hospitals_hospital_name_unique` (`hospital_name`),
  ADD UNIQUE KEY `hospitals_hospital_code_unique` (`hospital_code`),
  ADD UNIQUE KEY `hospitals_hospital_phone_unique` (`hospital_phone`),
  ADD UNIQUE KEY `hospitals_admin_phone_unique` (`admin_phone`),
  ADD KEY `hospitals_city_index` (`city`),
  ADD KEY `hospitals_country_index` (`country`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specializations_hospital_id_foreign` (`hospital_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_hospital_id_email_unique` (`hospital_id`,`email`),
  ADD UNIQUE KEY `users_hospital_id_api_code_unique` (`hospital_id`,`api_code`),
  ADD KEY `users_doctor_id_foreign` (`doctor_id`),
  ADD KEY `users_role_index` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_specialization_id_foreign` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `specializations`
--
ALTER TABLE `specializations`
  ADD CONSTRAINT `specializations_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
