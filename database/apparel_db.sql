-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2026 at 06:01 AM
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
-- Database: `apparel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reservation_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('event-apparel-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897', 'i:1;', 1791256896),
('event-apparel-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897:timer', 'i:1791256896;', 1791256896),
('event-apparel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:2;', 1791298121),
('event-apparel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1791298121;', 1791298121),
('event-apparel-cache-admin@gmail.com|127.0.0.1', 'i:1;', 1791300854),
('event-apparel-cache-admin@gmail.com|127.0.0.1:timer', 'i:1791300854;', 1791300854),
('event-apparel-cache-b1d5781111d84f7b3fe45a0852e59758cd7a87e5', 'i:2;', 1791291094),
('event-apparel-cache-b1d5781111d84f7b3fe45a0852e59758cd7a87e5:timer', 'i:1791291094;', 1791291094);

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
-- Table structure for table `customization_requests`
--

CREATE TABLE `customization_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `request_type` varchar(255) NOT NULL DEFAULT 'Custom Set',
  `selected_item` varchar(255) NOT NULL,
  `customization_details` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `correction_reason` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customization_requests`
--

INSERT INTO `customization_requests` (`id`, `customer_id`, `package_id`, `request_type`, `selected_item`, `customization_details`, `status`, `correction_reason`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 'Custom Set', 'Custom Set', 'Gowns size small and Coat size medium', 'Pending', NULL, '2026-09-15 23:43:47', '2026-09-15 23:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `customization_request_items`
--

CREATE TABLE `customization_request_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customization_request_id` bigint(20) UNSIGNED NOT NULL,
  `rental_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `size` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customization_request_items`
--

INSERT INTO `customization_request_items` (`id`, `customization_request_id`, `rental_item_id`, `quantity`, `size`, `color`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, NULL, NULL, '2026-09-15 23:43:47', '2026-09-15 23:43:47'),
(2, 1, 1, 1, NULL, NULL, '2026-09-15 23:43:47', '2026-09-15 23:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `customization_request_new_items`
--

CREATE TABLE `customization_request_new_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customization_request_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `reference_image_path` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `quoted_price` decimal(12,2) DEFAULT NULL,
  `expense_price` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`id`, `name`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'TUXEDO', NULL, 'category-images/pKzfi1Y91SfucBvPnR67qqEzmQ6WNMWdIAa0iWlp.png', '2026-09-15 21:36:55', '2026-09-22 22:53:58'),
(2, 'WEDDING GOWN', NULL, 'category-images/TJd9812c3HG1uOhK2v5WXDqBRTRYXkGu2MsGViDb.png', '2026-09-15 21:37:17', '2026-09-22 22:53:44'),
(3, 'GOWN', NULL, 'category-images/W1bdYM8fe7rMUepysDqImGQDONGYeZwYjJGLKKTB.png', '2026-09-15 22:29:20', '2026-09-22 22:10:17');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_02_140219_add_role_to_users_table', 1),
(5, '2026_09_04_135707_create_customization_requests_table', 1),
(6, '2026_09_04_150122_create_item_categories_table', 1),
(7, '2026_09_05_123055_create_rental_items_table', 1),
(8, '2026_09_06_020730_create_reservations_table', 1),
(9, '2026_09_07_154705_create_payments_table', 1),
(10, '2026_09_08_165500_add_quantity_fields_to_rental_items_table', 1),
(11, '2026_09_08_204200_remove_damaged_quantity_from_rental_items_table', 1),
(12, '2026_09_08_211600_remove_available_quantity_from_rental_items_table', 1),
(13, '2026_09_08_213000_add_quantity_to_reservations_table', 1),
(14, '2026_09_09_130213_create_appointments_table', 1),
(15, '2026_09_10_010302_create_packages_table', 1),
(16, '2026_09_10_010843_create_package_items_table', 1),
(17, '2026_09_10_050213_create_customization_request_items_table', 1),
(18, '2026_09_10_051615_add_package_id_and_request_type_to_customization_requests_table', 1),
(19, '2026_09_11_135827_create_customization_request_new_items_table', 1),
(20, '2026_09_12_052647_add_return_date_to_reservations_table', 1),
(21, '2026_09_12_064125_add_return_notification_sent_at_to_reservations_table', 1),
(22, '2026_09_14_222323_add_image_to_item_categories_table', 1),
(23, '2026_09_15_120000_add_returned_at_to_reservations_table', 1),
(24, '2026_09_15_130000_add_image_to_rental_items_table', 1),
(25, '2026_09_15_140000_add_blocked_at_to_users_table', 1),
(26, '2026_09_15_140000_add_image_to_packages_table', 1),
(27, '2026_09_15_150000_add_costing_to_customization_request_new_items_table', 1),
(28, '2026_09_15_160000_add_reservation_notification_sent_at_to_reservations_table', 1),
(29, '2026_09_15_170000_add_payment_notification_sent_at_to_payments_table', 1),
(30, '2026_09_23_120000_add_size_and_color_options_to_rental_items_table', 2),
(31, '2026_09_23_130000_add_size_and_color_to_customization_request_items_table', 3),
(32, '2026_10_06_000000_create_otp_sessions_table', 4),
(33, '2026_10_08_000000_audit_fixups', 5);

-- --------------------------------------------------------

--
-- Table structure for table `otp_sessions`
--

CREATE TABLE `otp_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purpose` varchar(32) NOT NULL,
  `otp_hash` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attempts` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `max_attempts` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `used_at` timestamp NULL DEFAULT NULL,
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_sessions`
--

INSERT INTO `otp_sessions` (`id`, `session_id`, `email`, `user_id`, `purpose`, `otp_hash`, `expires_at`, `attempts`, `max_attempts`, `used_at`, `last_attempt_at`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, '3cb035d028c69c06572585b7894499aa', 'otpsmoke@example.com', 5, 'email_verification', '$2y$12$YW69Mjl1TGmIqlobfgonx.Xu72rh5azn.Gml1BsazEMkhj0qbzgSe', '2026-10-06 01:23:59', 1, 5, '2026-10-05 17:23:59', '2026-10-05 17:23:58', '127.0.0.1', 'curl/8.21.0', '2026-10-05 17:23:34', '2026-10-05 17:23:59'),
(2, '419f6584a82871855bc77b84f39d4b88', 'otpsmoke@example.com', 5, 'password_reset', '$2y$12$uvSJJ0EcIxklVVRbjdE7jeuUubuwV0WrYX3Jvzn2HVjPSsPHJKRoy', '2026-10-06 01:24:24', 1, 5, '2026-10-05 17:24:24', '2026-10-05 17:24:23', '127.0.0.1', 'curl/8.21.0', '2026-10-05 17:24:13', '2026-10-05 17:24:24'),
(3, '10a2b63a8efee0a266b0eb07b640fe2c', 'zairee884@gmail.com', NULL, 'email_verification', '$2y$12$vYZ6Cl8kqrW1Uol4BtnVJukkl3jsaTUii7B4aW7MT4Rb4PWwzkEVC', '2026-10-05 17:29:38', 0, 5, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-10-05 17:26:38', '2026-10-05 17:26:38'),
(4, '16f3680b9af98e10c293da48424cf838', 'otpsmoke2@example.com', 7, 'email_verification', '$2y$12$/uskzdHMpp1RyDJ8FyyZdOXJ0EdJsljWZ4YZgyXodn2J0IWt3nqmW', '2026-10-06 01:35:33', 1, 5, '2026-10-05 17:35:33', '2026-10-05 17:35:33', '127.0.0.1', 'curl/8.21.0', '2026-10-05 17:35:13', '2026-10-05 17:35:33'),
(5, '03e9c3136008280a3e83ab6d7e124597', 'jaybondholliete1@gmail.com', NULL, 'email_verification', '$2y$12$kaFH5qL1MXR1mbK0CcatsO9C480E0dvB/9T8iYAeW0170Z0IPHNoa', '2026-10-06 03:20:09', 0, 5, '2026-10-05 19:20:09', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-10-05 17:54:06', '2026-10-05 19:20:09'),
(6, '75dba90e693802e09473728faeabf0cc', 'jaybondholliete1@gmail.com', 9, 'email_verification', '$2y$12$WVmIfr3MCyVZEWVWtZLy8.Bk0cC7hoQp6BsvOZj2UuhtVcSyC4efi', '2026-10-06 03:20:37', 1, 5, '2026-10-05 19:20:37', '2026-10-05 19:20:36', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-10-05 19:20:10', '2026-10-05 19:20:37'),
(7, '2841fdd223ca2ffe3fd9cb45c2b2e725', 'alylxalavina@gmail.com', 10, 'email_verification', '$2y$12$ear7bgTx93iQruOMuReINug5CRB1pJRwUBn2YuP777A5ekbrHcxUG', '2026-10-06 12:50:49', 2, 5, '2026-10-06 04:50:49', '2026-10-06 04:50:49', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-10-06 04:49:50', '2026-10-06 04:50:49'),
(8, 'ddf798d8e8f14859583ab18870a62a09', 'alylxalavina@gmail.com', 10, 'password_reset', '$2y$12$EHgu/KQd3oytYFxIbuLGmedofJyniTfG7MoYEB4Fo/jdlqxkvaXKe', '2026-10-06 14:48:33', 1, 5, '2026-10-06 06:48:33', '2026-10-06 06:48:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', '2026-10-06 06:47:41', '2026-10-06 06:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `event_type`, `price`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Olive Green Package', 'wedding', 10000.00, NULL, 'packages/dh0XeOrS15NIPkpz0G6KgTVQU6Iz2qBWaEu0lmDf.png', 'Active', '2026-09-15 22:35:02', '2026-09-23 17:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `package_items`
--

CREATE TABLE `package_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `rental_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `package_items`
--

INSERT INTO `package_items` (`id`, `package_id`, `rental_item_id`, `quantity`, `created_at`, `updated_at`) VALUES
(10, 1, 1, 1, '2026-09-23 17:35:14', '2026-09-23 17:35:14'),
(11, 1, 2, 7, '2026-09-23 17:35:14', '2026-09-23 17:35:14'),
(12, 1, 5, 7, '2026-09-23 17:35:14', '2026-09-23 17:35:14'),
(13, 1, 4, 7, '2026-09-23 17:35:14', '2026-09-23 17:35:14'),
(14, 1, 6, 7, '2026-09-23 17:35:14', '2026-09-23 17:35:14'),
(15, 1, 8, 5, '2026-09-23 17:35:14', '2026-09-23 17:35:14'),
(16, 1, 3, 1, '2026-09-23 17:35:14', '2026-09-23 17:35:14');

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reservation_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `reference_number` varchar(255) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_notification_sent_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `reservation_id`, `amount`, `payment_method`, `status`, `reference_number`, `payment_date`, `payment_notification_sent_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1000.00, 'Cash', 'Pending', NULL, '2026-09-16', NULL, 'Downpayment', '2026-09-16 00:04:00', '2026-09-16 00:04:00');

-- --------------------------------------------------------

--
-- Table structure for table `rental_items`
--

CREATE TABLE `rental_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `rental_price` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Available',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `size_quantities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`size_quantities`)),
  `colors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`colors`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rental_items`
--

INSERT INTO `rental_items` (`id`, `item_code`, `name`, `description`, `image`, `category_id`, `rental_price`, `status`, `quantity`, `size_quantities`, `colors`, `created_at`, `updated_at`) VALUES
(1, 'TUX-01', 'Tuxedo', 'Color Black Medium size', 'rental-items/tJxWycl5LVqrJORcS8WdcIMM9GFLYOIEeNfgSKH5.png', 1, 1500.00, 'Available', 5, '{\"Large\":3,\"Medium\":2}', '[\"Black\"]', '2026-09-15 22:26:30', '2026-09-23 17:04:44'),
(2, 'GWN001', 'Olive Gown', 'Medium size', 'rental-items/xmbj4YHyHS6Cah4KPKonS78sWc0mmyyf8RBG8fAF.png', 3, 500.00, 'Available', 7, '{\"Medium\":4,\"Small\":2,\"Large\":1}', '[\"Olive Green\"]', '2026-09-15 22:30:05', '2026-09-23 17:02:15'),
(3, 'GWN002', 'Corset Gown', 'Small size', 'rental-items/aJeOTe6C9efQ8nLu8UlIn9OwDZPVdHyvAn2pHa97.png', 2, 1000.00, 'Available', 1, NULL, NULL, '2026-09-15 22:32:46', '2026-09-22 17:28:58'),
(4, 'TUX-02', 'Adult Slacks', NULL, 'rental-items/3DrkmGsXZf5Qn4zZGZ6r9eW1JoGzG4JQUqeBIhaz.png', 1, 200.00, 'Available', 10, '{\"Medium\":4,\"Large\":2,\"XL\":2,\"XXl\":1,\"Small\":1}', '[\"Black\"]', '2026-09-23 16:42:47', '2026-09-23 17:07:45'),
(5, 'TUX-03', 'Adult Vest', NULL, 'rental-items/15Phx3RGANjwRZwg8vYG0inmolNomLsZRSnWEMQc.png', 1, 200.00, 'Available', 10, '{\"Medium\":4,\"Small\":2,\"Large\":2,\"XL\":1,\"XXL\":1}', '[\"Black\"]', '2026-09-23 16:46:15', '2026-09-23 17:09:22'),
(6, 'TUX-04', 'Adult Long-sleeved shirt', NULL, 'rental-items/MJZRpEoPGnf5jlAw9uWkZZ1haeKKOWPF0arRdxUk.png', 1, 300.00, 'Available', 25, '{\"Large\":5,\"Medium\":13,\"Small\":3,\"XL\":3,\"XXL\":1}', '[\"White\"]', '2026-09-23 16:51:02', '2026-09-23 17:11:12'),
(7, 'TUX-05', 'Neckties', NULL, 'rental-items/ziZhHwOqqZN1decVU4yPaK7R85fbyrwNK9ZyppVd.png', 1, 200.00, 'Available', 10, NULL, '[\"Black & Grey stripes\"]', '2026-09-23 16:54:42', '2026-09-23 17:14:48'),
(8, 'GWN0X', 'Flower girl Dress', NULL, 'rental-items/8yUGmDhELvDFCjBW4lgDjmSInJ63sboUJQnaialX.png', 2, 500.00, 'Available', 5, '{\"Small\":3,\"Medium\":2}', '[\"Olive Green\"]', '2026-09-23 17:26:29', '2026-09-23 17:26:29');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `rental_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `reservation_date` date NOT NULL,
  `event_date` date NOT NULL,
  `reservation_notification_sent_at` timestamp NULL DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `return_notification_sent_at` timestamp NULL DEFAULT NULL,
  `returned_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `customer_id`, `rental_item_id`, `quantity`, `reservation_date`, `event_date`, `reservation_notification_sent_at`, `return_date`, `return_notification_sent_at`, `returned_at`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, '2026-09-16', '2026-09-30', '2026-09-15 23:37:42', '2026-10-01', NULL, NULL, 'Confirmed', NULL, '2026-09-15 23:36:33', '2026-09-15 23:37:42');

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
('1tfHWWK0ccfQZFNwX7BGXQN2j7QdgHMeTRQ8lsn5', NULL, '127.0.0.1', 'curl/8.21.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUTRYeE42TldLVWNtbk9NdHE0dTFUWEk4RGptSEIwa3UycHNlNVhOVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1791296480),
('8NWEVQTMD7WQsXvUe4bncdKpcJuZL7UCyN19Hz9p', NULL, '127.0.0.1', 'curl/8.21.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMEZiSEVoeURIOFZVdlliUm5OWVFGSFNFZFJIU3pyMFpVakxKbEpvdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1791301012),
('AxHyhM9doT1lAcDah1yue3rjrclvkSX9u7puQ64s', NULL, '127.0.0.1', 'curl/8.21.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUHFJRjdSNXozVDE5M2dMRnJXMzk4NFluVDlxcU1Ta3pFYkszZGJ6QSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1791296480),
('BqJnvr379KBU5q35hFJjkrpYowBmCV4GrgFFFSYu', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36 Edg/154.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidDV4Ukk0QzMweHB1b201UDlrcGpMUTNHZ28xalRCZ0xJVDBwYUJCdiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MCI7czo1OiJyb3V0ZSI7Tjt9fQ==', 1791301630),
('bz26lL6fbuzdJ26N22QyoN2ShJZM1rQLAmYqJr8a', NULL, '127.0.0.1', 'curl/8.21.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibUJyVHJVZnVwMndWbExkYjVtZ2NpQ2VWek9rQzRkeTFRUnh1MGdvcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1791300508),
('c2UXsnS6fUrJVKqW2db7Dxhgeh7FHTjnNT3zs2d1', NULL, '127.0.0.1', 'curl/8.21.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR040MEE5RXc5NHNhOGFGeFEwNEg4VWxycU8wTWczenZpalo5bnJvciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1791301457),
('Msz5FamJa6kslc1qUGDQSuJw5DYd9HQQw490Nvje', NULL, '127.0.0.1', 'curl/8.21.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidEJWbkU3dkpOZTROem03dUxwYTYyNjVqeWZwYVBsUmFXdktWVEVqbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1791296025),
('SlOp7QKixwas2RjMaDLUf9hbhAMQXoxbeHrJKaoe', NULL, '127.0.0.1', 'curl/8.21.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU20wZjMwR054aDdvWk9YVkl5V2prR283TzdQOWxLdHRDTk1vcWNnaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1791300507);

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
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `blocked_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `blocked_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@test.com', '2026-10-06 01:38:11', '$2y$12$R1Kzz1odjKU6f3SXtrvol.rsqifb2icbtf1Y2im11kx2Q2FaioEpG', 'admin', NULL, 'ujIGETSK2GF2aBNMtLhjYn3ek4f55Mv4ki8qvJkJl4lol4gtdnC1FPKQQKCa', '2026-09-15 21:02:42', '2026-09-15 21:02:42'),
(2, 'Joy', 'v1995joy@gmail.com', '2026-10-06 01:38:11', '$2y$12$Gl5XIyuSj9PQA8PgGZReke/IQXkG2DYYN253Vkyv2fEC3YoJu7mZy', 'customer', NULL, NULL, '2026-09-15 21:12:04', '2026-09-22 23:49:59'),
(3, 'Angelita', 'angelitagambe200209@gmail.com', '2026-10-06 01:38:11', '$2y$12$o5b/Ejh2O.4kNR64FXJz5uPE1l6dEuHxyftzoYGvI/xNgnrMZUHRW', 'customer', NULL, NULL, '2026-09-21 16:33:59', '2026-09-21 16:33:59'),
(4, 'LadyMyre', 'dinopolladymyre@gmail.com', '2026-10-06 01:38:11', '$2y$12$cVedTV0p97IiG2oLSdqe/OzFeP3fAteVGybTTiDCwlCpa0uzTOlcq', 'customer', NULL, NULL, '2026-09-21 16:35:37', '2026-09-21 16:35:37'),
(5, 'OTP Smoke', 'otpsmoke@example.com', '2026-10-05 17:23:59', '$2y$12$tKhOETCTkKFVUGdfTRhsYuSV1htRWX2BGopJOXkhKvc/S..c5k9u6', 'customer', NULL, 'rVdwLr7FOk30xckIiDo95NkFIalVIbIVCyTkmjrVjXN1htKHeB6Awp6QTCvk', '2026-10-05 17:23:34', '2026-10-05 17:24:24'),
(7, 'OTP Smoke 2', 'otpsmoke2@example.com', '2026-10-05 17:35:33', '$2y$12$9O2ekYl3Mdo1OYtRvW/d0e68VsEOAfkbhZcvSV25zy65jAQm5cqCK', 'customer', NULL, NULL, '2026-10-05 17:35:12', '2026-10-05 17:35:33'),
(9, 'Yellow Tulips', 'jaybondholliete1@gmail.com', '2026-10-05 19:20:37', '$2y$12$/6GRz8Kqt4zbpDI04KvzIOrgniO42yqDFbLAW1ljTpeFq4yRmEc62', 'customer', NULL, NULL, '2026-10-05 19:20:09', '2026-10-05 19:20:37'),
(10, 'alykxa lavina', 'alylxalavina@gmail.com', '2026-10-06 04:50:49', '$2y$12$ZbO9RIcs8uj/K72qQbCkDO./RS5yOaVlxm/CPmLTIHhgEeZ6zpuhq', 'customer', NULL, 'WwyFlILgqc1py3xM6wo1CgVWxFytYJnMjriiRBaoTKgP9Sqkujky2UAaKnBH', '2026-10-06 04:49:49', '2026-10-06 06:48:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_reservation_id_foreign` (`reservation_id`),
  ADD KEY `appointments_customer_id_foreign` (`customer_id`),
  ADD KEY `appointments_appointment_date_index` (`appointment_date`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `customization_requests`
--
ALTER TABLE `customization_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customization_requests_customer_id_foreign` (`customer_id`),
  ADD KEY `customization_requests_package_id_foreign` (`package_id`),
  ADD KEY `customization_requests_status_index` (`status`);

--
-- Indexes for table `customization_request_items`
--
ALTER TABLE `customization_request_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `custom_request_item_unique` (`customization_request_id`,`rental_item_id`),
  ADD KEY `customization_request_items_rental_item_id_foreign` (`rental_item_id`);

--
-- Indexes for table `customization_request_new_items`
--
ALTER TABLE `customization_request_new_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customization_request_new_items_customization_request_id_foreign` (`customization_request_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
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
-- Indexes for table `otp_sessions`
--
ALTER TABLE `otp_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `otp_sessions_session_id_unique` (`session_id`),
  ADD KEY `otp_sessions_user_id_foreign` (`user_id`),
  ADD KEY `otp_lookup_idx` (`email`,`purpose`,`expires_at`),
  ADD KEY `otp_sessions_email_index` (`email`),
  ADD KEY `otp_sessions_purpose_index` (`purpose`),
  ADD KEY `otp_sessions_expires_at_index` (`expires_at`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_items`
--
ALTER TABLE `package_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `package_items_package_id_rental_item_id_unique` (`package_id`,`rental_item_id`),
  ADD KEY `package_items_rental_item_id_foreign` (`rental_item_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_reservation_id_foreign` (`reservation_id`),
  ADD KEY `payments_status_index` (`status`);

--
-- Indexes for table `rental_items`
--
ALTER TABLE `rental_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rental_items_item_code_unique` (`item_code`),
  ADD KEY `rental_items_category_id_foreign` (`category_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservations_customer_id_foreign` (`customer_id`),
  ADD KEY `reservations_rental_item_id_foreign` (`rental_item_id`),
  ADD KEY `reservations_status_index` (`status`),
  ADD KEY `reservations_event_date_index` (`event_date`),
  ADD KEY `reservations_return_date_index` (`return_date`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customization_requests`
--
ALTER TABLE `customization_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customization_request_items`
--
ALTER TABLE `customization_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customization_request_new_items`
--
ALTER TABLE `customization_request_new_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `otp_sessions`
--
ALTER TABLE `otp_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `package_items`
--
ALTER TABLE `package_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rental_items`
--
ALTER TABLE `rental_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customization_requests`
--
ALTER TABLE `customization_requests`
  ADD CONSTRAINT `customization_requests_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customization_requests_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customization_request_items`
--
ALTER TABLE `customization_request_items`
  ADD CONSTRAINT `customization_request_items_customization_request_id_foreign` FOREIGN KEY (`customization_request_id`) REFERENCES `customization_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customization_request_items_rental_item_id_foreign` FOREIGN KEY (`rental_item_id`) REFERENCES `rental_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customization_request_new_items`
--
ALTER TABLE `customization_request_new_items`
  ADD CONSTRAINT `customization_request_new_items_customization_request_id_foreign` FOREIGN KEY (`customization_request_id`) REFERENCES `customization_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `otp_sessions`
--
ALTER TABLE `otp_sessions`
  ADD CONSTRAINT `otp_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `package_items`
--
ALTER TABLE `package_items`
  ADD CONSTRAINT `package_items_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_items_rental_item_id_foreign` FOREIGN KEY (`rental_item_id`) REFERENCES `rental_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rental_items`
--
ALTER TABLE `rental_items`
  ADD CONSTRAINT `rental_items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `item_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_rental_item_id_foreign` FOREIGN KEY (`rental_item_id`) REFERENCES `rental_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
