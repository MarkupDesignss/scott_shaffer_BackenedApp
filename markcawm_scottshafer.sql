-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 08, 2026 at 02:51 AM
-- Server version: 11.4.12-MariaDB-cll-lve-log
-- PHP Version: 8.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `markcawm_scottshafer`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'qatest02md@gmail.com', '$2y$12$txpddSOFayJkiloD6SV5Ku1UjaiW6dkMMLUXIe3cnfJYrTTCapoTq', NULL, '2026-06-22 11:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_otps`
--

CREATE TABLE `admin_password_otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL,
  `otp` varchar(191) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_password_otps`
--

INSERT INTO `admin_password_otps` (`id`, `email`, `otp`, `expires_at`, `created_at`, `updated_at`) VALUES
(5, 'kushankrajput16@gmail.com', '260752', '2025-12-30 17:18:27', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `app_versions`
--

CREATE TABLE `app_versions` (
  `id` int(11) NOT NULL,
  `latest_version` varchar(20) NOT NULL,
  `min_required_version` varchar(20) NOT NULL,
  `force_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `android_url` varchar(255) DEFAULT 'https://play.google.com/store/apps/details?id=com.app',
  `ios_url` varchar(255) DEFAULT 'https://apps.apple.com/app/id123456'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `app_versions`
--

INSERT INTO `app_versions` (`id`, `latest_version`, `min_required_version`, `force_update`, `created_at`, `updated_at`, `android_url`, `ios_url`) VALUES
(1, '1.1', '0.1', 1, '2026-02-04 12:52:07', '2026-06-23 12:00:12', 'https://play.google.com/store/apps/details?id=com.app1', 'https://apps.apple.com/app/id123457');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(60) NOT NULL,
  `subtitle` varchar(120) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `cta_text` varchar(30) DEFAULT NULL,
  `cta_url` varchar(255) DEFAULT NULL,
  `status` enum('draft','live','paused') DEFAULT 'draft',
  `requires_consent` tinyint(1) DEFAULT 1,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `name`, `title`, `subtitle`, `image_url`, `cta_text`, `cta_url`, `status`, `requires_consent`, `starts_at`, `ends_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(10, 'Peter', 'Meeting', NULL, 'campaigns/c5VRNGKuO10TS3286f3ksj4i4sFSrCQyHbYXxkw9.png', NULL, NULL, 'live', 1, '2026-06-10 17:00:00', '2026-06-11 22:30:00', '2026-03-27 17:00:53', '2026-06-15 18:51:51', NULL),
(11, 'Peter', 'List', 'A \"booklist\" is a curated compilation of reading recommendations, required reading, or specific books tied together by.', 'campaigns/z8w9UOKGXxUp8lzPbyUheDefGRS8vbJwdISPlK3S.jpg', NULL, NULL, 'live', 1, '2026-06-11 18:14:00', '2026-06-19 23:45:00', '2026-06-11 18:16:57', '2026-06-18 13:40:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `campaign_segment`
--

CREATE TABLE `campaign_segment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campaign_id` bigint(20) UNSIGNED NOT NULL,
  `segment_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `campaign_segment`
--

INSERT INTO `campaign_segment` (`id`, `campaign_id`, `segment_id`, `created_at`, `updated_at`) VALUES
(12, 10, 9, '2026-03-27 17:00:53', '2026-03-27 17:00:53'),
(15, 10, 8, '2026-06-11 15:46:01', '2026-06-11 15:46:01'),
(16, 11, 8, '2026-06-11 18:16:57', '2026-06-11 18:16:57');

-- --------------------------------------------------------

--
-- Table structure for table `catalog_categories`
--

CREATE TABLE `catalog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `interest_id` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '0 = Inactive, 1 = Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `catalog_categories`
--

INSERT INTO `catalog_categories` (`id`, `name`, `slug`, `icon`, `color`, `interest_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Travel', 'travel', 'fas fa-shopping-bag', 'blue', '37', 1, '2025-12-17 04:49:46', '2026-06-11 14:36:30'),
(3, 'Songs', 'songs', NULL, NULL, '40', 1, '2025-12-26 19:22:41', '2026-06-11 14:36:19'),
(4, 'Gamingg', 'gamingg', NULL, NULL, '43', 1, '2025-12-31 15:18:51', '2026-06-11 14:35:49'),
(5, 'Books', 'books', NULL, NULL, '72', 1, '2026-01-05 19:05:47', '2026-03-27 15:08:43'),
(6, 'Marvel', 'marvel', NULL, NULL, '49', 1, '2026-01-09 17:36:58', '2026-06-11 14:35:25'),
(7, 'Pika', 'pika', NULL, NULL, '33', 1, '2026-03-27 16:40:08', '2026-03-27 16:40:08'),
(8, 'Food', 'food', NULL, NULL, '62', 1, '2026-04-24 13:45:04', '2026-06-11 14:34:23');

-- --------------------------------------------------------

--
-- Table structure for table `catalog_items`
--

CREATE TABLE `catalog_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '0 = Inactive, 1 = Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `catalog_items`
--

INSERT INTO `catalog_items` (`id`, `category_id`, `sub_category_id`, `name`, `description`, `image_url`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'Annabelle', 'This is horror item', 'category-items/7dl65TncP49Tfrpa50gGZ8AjODZxDll8a0DgF7qZ.png', 1, '2026-02-12 15:08:40', '2026-06-16 15:49:10', NULL),
(2, 3, NULL, 'Kesariya', 'Here you can find songs', 'category-items/M9QwFDEnJbAHko5oHgoorpNHBJaYhw75uhucUY6o.jpg', 1, '2026-02-12 15:09:39', '2026-06-16 09:19:44', NULL),
(3, 4, 3, 'PUBG', 'This is gaming section', 'category-items/Pn9f3ywolL4v1LCWL03gSqLhMlUCKmKywQAprpaS.png', 1, '2026-02-12 15:10:40', '2026-06-16 09:19:30', NULL),
(4, 5, 4, 'Atomic Habits', 'Here books are available', 'category-items/taFkRXqPERbdcxydRYsF8XvF07YRm6enNQzGWOHh.jpg', 1, '2026-02-12 15:11:46', '2026-06-16 09:17:53', NULL),
(5, 8, 6, 'Top Foods', 'Top Foods', 'category-items/mezHtwEX75Fr6ZVSdAL3vvKMbgyz6JCvUCE9GuVR.jpg', 1, '2026-02-12 15:13:13', '2026-06-16 09:17:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `catalog_item_tag`
--

CREATE TABLE `catalog_item_tag` (
  `catalog_item_id` bigint(20) UNSIGNED NOT NULL,
  `catalog_tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `catalog_tags`
--

CREATE TABLE `catalog_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `featured_item_bookmarks`
--

CREATE TABLE `featured_item_bookmarks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `featured_list_item_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `featured_item_bookmarks`
--

INSERT INTO `featured_item_bookmarks` (`id`, `user_id`, `featured_list_item_id`, `created_at`, `updated_at`) VALUES
(88, 112, 5, '2026-06-11 16:52:40', '2026-06-11 16:52:40'),
(92, 113, 8, '2026-06-16 09:48:05', '2026-06-16 09:48:05');

-- --------------------------------------------------------

--
-- Table structure for table `featured_item_likes`
--

CREATE TABLE `featured_item_likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `list_id` varchar(255) DEFAULT NULL,
  `featured_list_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `featured_item_likes`
--

INSERT INTO `featured_item_likes` (`id`, `user_id`, `list_id`, `featured_list_item_id`, `created_at`, `updated_at`) VALUES
(156, 112, NULL, 5, '2026-06-11 16:54:28', '2026-06-11 16:54:28'),
(159, 112, '101', NULL, '2026-06-11 17:23:25', '2026-06-11 17:23:25'),
(160, 112, NULL, 9, '2026-06-12 10:34:18', '2026-06-12 10:34:18'),
(162, 113, '107', NULL, '2026-06-12 14:05:36', '2026-06-12 14:05:36'),
(163, 113, '106', NULL, '2026-06-15 10:02:44', '2026-06-15 10:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `featured_item_shares`
--

CREATE TABLE `featured_item_shares` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `list_id` varchar(255) DEFAULT NULL,
  `featured_list_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `platform` varchar(50) DEFAULT NULL COMMENT 'whatsapp, facebook, instagram, copy_link',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `featured_lists`
--

CREATE TABLE `featured_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `image` varchar(191) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `list_size` tinyint(3) UNSIGNED NOT NULL,
  `status` enum('draft','live') NOT NULL DEFAULT 'draft',
  `display_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `featured_lists`
--

INSERT INTO `featured_lists` (`id`, `title`, `image`, `category_id`, `list_size`, `status`, `display_order`, `created_by`, `created_at`, `updated_at`) VALUES
(5, 'Featured List-1', 'featured_lists/nwFFJOlEuZQwi4GSYuXuwjdYalaZCIutPpkrWU2D.jpg', 1, 6, 'live', 0, 1, '2025-12-26 18:45:31', '2026-06-16 15:56:56'),
(8, 'Featured List-2', 'featured_lists/MuGKg1ZWSfctSDE02QhDNCc17LlIYck3XvmI70YF.jpg', 4, 3, 'live', 0, 1, '2025-12-26 19:46:36', '2026-06-16 15:54:14'),
(9, 'Featured List-6', 'featured_lists/nlVo3gCbWF122psEWeKHE5LNLtrsy9N6da5NVq9y.jpg', 5, 3, 'live', 2, 1, '2025-12-30 18:39:02', '2026-06-11 11:06:32'),
(10, 'Featured List-3', 'featured_lists/4bDNlDim3D7WsFG4neQA6WRNHfozZkyTIc9DWxCq.jpg', 3, 3, 'live', 0, 1, '2025-12-30 20:19:56', '2026-01-06 16:52:31'),
(11, 'Featured List-7', 'featured_lists/qQBT7Q9H1q6WKhHJ0DXh6z1rTLpDfB0cJakvbGrK.jpg', 3, 3, 'live', 2, 1, '2025-12-31 15:20:09', '2026-06-11 11:06:44'),
(13, 'Featured List-4', 'featured_lists/YF88w3QtDMBLrvtKyTOlBlwL5KXVMmK9gdrpomPJ.jpg', 7, 1, 'live', 0, 1, '2026-01-05 19:08:55', '2026-06-11 15:17:50'),
(14, 'Featured List-8', 'featured_lists/EtUf3MLllrEJ0wwaJYsTXuI7OKz3Zwap9nFl8Le7.png', 5, 3, 'live', 2, 1, '2026-01-09 17:40:13', '2026-06-11 15:19:24'),
(15, 'Featured List-5', 'featured_lists/aoJjBDCHoyBWmeTpv8TsUTB2trzaNBWCJPBusGKp.jpg', 6, 1, 'live', 0, 1, '2026-03-27 16:36:25', '2026-06-16 15:53:15'),
(16, 'Feature List 9', 'featured_lists/uVfXiiRhzs5SUiukfXDfroFyvkImFIq6HaJFFuWw.jpg', 8, 1, 'live', 9, 1, '2026-06-11 15:23:35', '2026-06-11 15:23:35');

-- --------------------------------------------------------

--
-- Table structure for table `featured_list_items`
--

CREATE TABLE `featured_list_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `featured_list_id` bigint(20) UNSIGNED NOT NULL,
  `catalog_item_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `position` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `featured_list_items`
--

INSERT INTO `featured_list_items` (`id`, `featured_list_id`, `catalog_item_id`, `status`, `position`, `created_at`, `updated_at`) VALUES
(3, 5, 1, 'active', 6, '2025-12-26 19:48:26', '2026-06-11 17:04:19'),
(4, 8, 1, 'active', 3, '2025-12-26 19:49:03', '2026-03-20 17:21:08'),
(5, 10, 2, 'active', 2, '2025-12-31 15:16:59', '2026-03-20 17:21:28'),
(6, 13, 2, 'active', 1, '2026-01-05 19:09:40', '2026-01-05 23:53:13'),
(7, 14, 4, 'active', 1, '2026-01-09 17:42:22', '2026-03-20 17:20:20'),
(8, 15, 5, 'active', 5, '2026-03-27 16:56:24', '2026-03-27 16:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `interests`
--

CREATE TABLE `interests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `icon` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interests`
--

INSERT INTO `interests` (`id`, `name`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(33, 'Fitness', 'interest-icons/PFY985AWxCyVfgSqszjxR4P61Xp6x8lZKyVsBPud.png', 1, '2026-03-24 17:44:28', '2026-03-24 17:44:28'),
(34, 'Self-Improvement', 'interest-icons/NbrwqmYWR8SZQq2mDpZAgrLnQdkUZ0EWzyHA5yVZ.png', 1, '2026-03-24 17:45:02', '2026-03-24 17:45:02'),
(35, 'Finance', 'interest-icons/cQ4ac5R9BZkwxZcWX3sYw1PZBgoFd0kGLjdoD7Ux.png', 1, '2026-03-24 17:46:31', '2026-03-24 17:46:31'),
(36, 'Entrepreneurship', 'interest-icons/tC0qe8EH4LWQCCvbGznoeuUdS98KPHvNME1mSetl.png', 1, '2026-03-24 17:46:40', '2026-03-24 17:46:40'),
(37, 'Travel', 'interest-icons/6en2EG8X52HNTUQCF3Ougj952StgHXEcaBT4siDz.png', 1, '2026-03-24 17:46:50', '2026-03-24 17:46:50'),
(38, 'Movies', 'interest-icons/DhVmxmAR8aeRy5NeRHE4yH0OwYJvAvANnKKT9wSK.png', 1, '2026-03-24 17:46:58', '2026-03-24 17:46:58'),
(39, 'TV Shows', 'interest-icons/EYaDMwNrgsgCDnd9dJ91wpaoCPmerbsP2CL1PtUH.png', 1, '2026-03-24 17:47:07', '2026-03-24 17:47:07'),
(40, 'Music', 'interest-icons/7tl0RgiCzJ9iAI4jWSm8guGx2sifToWrRIIZC9fu.png', 1, '2026-03-24 17:47:23', '2026-03-24 17:47:23'),
(41, 'Concerts', 'interest-icons/MnN8WonDzgZDvbvhF30HR9uR5yKmOzBlrcLric8W.png', 1, '2026-03-24 17:47:33', '2026-03-24 17:47:33'),
(42, 'Memes', 'interest-icons/in106ZXjgrc4rqgcVMBzdyqx8Sz8TveSPznNTTNf.png', 1, '2026-03-24 17:47:42', '2026-03-24 17:47:42'),
(43, 'Gaming', 'interest-icons/szMEPNJTzED175NwOUg2XiTZFM3u0Nxm0tnGaovT.png', 1, '2026-03-24 17:47:52', '2026-03-24 17:47:52'),
(44, 'AI', 'interest-icons/DIuirLX4fTrGer4SsGgGX4QY8PMHesJmpXnvqTLV.png', 1, '2026-03-24 17:48:03', '2026-03-24 17:48:03'),
(45, 'Psychology', 'interest-icons/rwtX4sBdPhoNLa6juUQCZ0TIyeoHgpECcHsfM0C9.png', 1, '2026-03-24 17:48:18', '2026-03-24 17:48:18'),
(46, 'History', 'interest-icons/y7PcovSdryzTBfvTmeKi2lfOpIDmi1K0VMr17Y1N.png', 1, '2026-03-24 17:51:11', '2026-03-24 17:51:11'),
(47, 'Politics', 'interest-icons/vwJZQ16bSNcoFqJhXmpGWLnTFe0T9NWfJWdbGqIF.png', 1, '2026-03-24 17:51:19', '2026-03-24 17:51:19'),
(48, 'True Crime', 'interest-icons/2lb9gUeGTrvJVYsxvhDcaZjFcc9qdpMlKFgZqmpW.png', 1, '2026-03-24 17:51:30', '2026-03-24 17:51:30'),
(49, 'Business', 'interest-icons/Muw7yRnHlnGW7ZaaqvsXXGfKU6U1rcIYslYtDur3.png', 1, '2026-03-24 17:51:39', '2026-03-24 17:51:39'),
(50, 'Basketball', 'interest-icons/VvkOEzTKBOGZdByutKkYGO4IpVCFsXGOEusxKi4h.png', 1, '2026-03-24 17:51:52', '2026-03-24 17:51:52'),
(51, 'Football', 'interest-icons/jEBUPZGv4nY305TAlGLFNl2bOAuipF73tiCs4Pz6.png', 1, '2026-03-24 17:52:33', '2026-03-24 17:52:33'),
(52, 'Soccer', 'interest-icons/u7u1nt3IzXjs7zwYLIzNklm0Ln6StV78QTeOrV2E.png', 1, '2026-03-24 17:52:50', '2026-03-24 17:52:50'),
(53, 'MMA', 'interest-icons/peZq39uTDUH6uxtkKEwzbxVQMnzMnntSz9I6a7aa.png', 1, '2026-03-24 17:52:59', '2026-03-24 17:52:59'),
(54, 'Golf', 'interest-icons/GrX0oBiTqCEUntpv0ZcbaXC2cGvMk368vYfkUCS0.png', 1, '2026-03-24 17:53:10', '2026-03-24 17:53:10'),
(55, 'Cars', 'interest-icons/vx2mQEsHb0z8e54IAljnCtoZr8zqq6LPqpmFTeSy.png', 1, '2026-03-24 17:53:20', '2026-03-24 17:53:20'),
(56, 'Extreme Sports', 'interest-icons/A1Lkiqn1ICLItLKEvWKkQ7DMaLVMGYD4Zqub81Q5.png', 1, '2026-03-24 17:53:32', '2026-03-24 17:53:32'),
(57, 'Art', 'interest-icons/OCjLC67zNVPWlAPsMqbR3PAX5jom3WXw71zEZtyq.png', 1, '2026-03-24 17:53:40', '2026-03-24 17:53:40'),
(58, 'Photography', 'interest-icons/0jMBxJBubNpSLWWklR06LvoAIy9GHDBJJAqirtKp.png', 1, '2026-03-24 17:53:49', '2026-03-24 17:53:49'),
(59, 'Writing', 'interest-icons/kY8xq29RfuGGY7t4sL9uamvQnwcxiDKQIYVvbrqK.png', 1, '2026-03-24 17:54:00', '2026-03-24 17:54:00'),
(60, 'Fashion', 'interest-icons/MkIL5vCZiuehjzws3cnm8s2PtxJ14VCRXUAz6M6h.png', 1, '2026-03-24 17:55:44', '2026-03-24 17:55:44'),
(61, 'Content Creation', 'interest-icons/cboiBnem5n6KjKLk9V1CsCMn5tJFtyGgA62G9nst.png', 1, '2026-03-24 17:55:54', '2026-03-24 17:55:54'),
(62, 'Food', 'interest-icons/5alz7l1K7b15rqAT4mYhrwLuiatL63y5lQAYjutD.png', 1, '2026-03-24 17:56:02', '2026-03-24 17:56:02'),
(63, 'Cooking', 'interest-icons/U9osgffCis0sotC7ywfgJLVtYRssOBawSV6WMv12.png', 1, '2026-03-24 17:56:17', '2026-03-24 17:56:17'),
(64, 'Nightlife', 'interest-icons/uwQfOlhjvt8cG1oWMlmtTOjvj7gpnwOAqxjsCuCQ.png', 1, '2026-03-24 17:56:32', '2026-03-24 17:56:32'),
(65, 'Dating', 'interest-icons/Ya8zgGo8CFJ4HAcnvdBwxCC4V7LDHBuxFWGA3yrz.png', 1, '2026-03-24 17:56:52', '2026-03-24 17:56:52'),
(66, 'Parenting', 'interest-icons/gafx2rYSNuN7Mo5aZxpLVFvIMm0FgyIQcpbwY0Gn.png', 1, '2026-03-24 17:57:01', '2026-03-24 17:57:01'),
(67, 'Pets', 'interest-icons/IY6A2QoxHvnZUw5XSpC8SQucBYf9eMPTYs9ziJ5Y.png', 1, '2026-03-24 17:57:10', '2026-03-24 17:57:10'),
(68, 'Outdoors', 'interest-icons/XSUZHCdhwnZvg3Xs62OPUaVqYohmwG1AnALeTPSB.png', 1, '2026-03-24 17:57:23', '2026-03-24 17:57:23'),
(69, 'Health & Wellness', 'interest-icons/IbyIvqM4hAEp4SbxjKRdUZOtmp819vPNKsKjzCkN.png', 1, '2026-03-24 17:57:45', '2026-03-24 17:57:45'),
(70, 'Remote Work', 'interest-icons/rl8sUjNhT4sZBed9EHo9hvoFUa5LtgELdO1lFnW0.png', 1, '2026-03-24 17:58:14', '2026-03-24 17:58:14'),
(71, 'Camping', 'interest-icons/XsnnCvHmuyrjLssBQObAWo37zW37aHcziRjUpPGj.png', 1, '2026-03-24 18:00:11', '2026-03-24 18:00:11'),
(72, 'Books', 'interest-icons/cjQnERrDdWiXrvUFO5ARY8dhCDxqR2bpgmIKz89m.png', 1, '2026-03-24 18:00:37', '2026-03-24 18:00:37'),
(73, 'Shopping', 'interest-icons/K97ZUq6fzzvYUlA0STsDfAXFb2eppeafFIle1kbs.png', 1, '2026-03-24 18:00:51', '2026-03-24 18:00:51'),
(74, 'Podcasts', 'interest-icons/9cg3khMQpBpmdNF1RHvu72jjytTf1qaCAK1g6DhI.png', 1, '2026-03-24 18:01:00', '2026-03-24 18:01:00'),
(75, 'Cycling', 'interest-icons/GC0LnYlTmh0gvw6ADTiwa5W0Gjae5vkVXd9NTpgq.png', 1, '2026-03-24 18:01:08', '2026-03-24 18:01:08'),
(76, 'Bodybuilding', 'interest-icons/M0t9jDEsewSLBAar0eAFnKIs4KxqqNFu0mPdwS9n.png', 1, '2026-03-24 18:01:17', '2026-03-24 18:01:17'),
(77, 'Motorcycles', 'interest-icons/uXRbXzdFUmtO4ivjxtmtlyZWbg08r9o7sNlVrSkV.png', 1, '2026-03-24 18:01:27', '2026-03-24 18:01:27'),
(78, 'Nature', 'interest-icons/BsGal5HPiXSQBHedOC03lHjWacmzQVQV1ELoErJc.png', 1, '2026-03-24 18:01:39', '2026-03-24 18:01:39'),
(79, 'Dining Out', 'interest-icons/1N6zdvkDGIT4uV3M40gxGfJBPHXjffJfo5rjZaJg.png', 1, '2026-03-24 18:01:59', '2026-03-24 18:01:59'),
(80, 'Board Games', 'interest-icons/GszMJ1YKEU0OSlEwCM3FJrpfo5XXCUnifWcyTEHG.png', 1, '2026-03-24 18:02:26', '2026-03-24 18:02:26'),
(81, 'Science', 'interest-icons/7QZceINIo61VVFIIbwbDQRDEbFuLmMJF8uveIN2x.png', 1, '2026-03-24 18:02:43', '2026-03-24 18:02:43');

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
-- Table structure for table `lists`
--

CREATE TABLE `lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(80) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `list_size` int(11) NOT NULL DEFAULT 5,
  `is_group` tinyint(1) DEFAULT 0,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `visibility` enum('private','public') NOT NULL DEFAULT 'private',
  `cloned_from_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lists`
--

INSERT INTO `lists` (`id`, `user_id`, `title`, `category_id`, `sub_category_id`, `list_size`, `is_group`, `status`, `visibility`, `cloned_from_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(101, 112, 'Scott List', 5, NULL, 6, 1, 'published', 'public', NULL, '2026-06-11 15:39:00', '2026-06-17 10:30:35', NULL),
(110, 113, 'Scott list 1 (Copy)', 5, NULL, 3, 0, 'published', 'public', NULL, '2026-06-15 13:13:19', '2026-06-16 13:35:27', NULL),
(124, 113, 'Scout list 2 (Copy)', 5, NULL, 2, 0, 'published', 'public', NULL, '2026-06-15 18:20:20', '2026-06-17 11:41:32', NULL),
(125, 113, 'Top 10 fruit', 8, NULL, 3, 1, 'published', 'public', NULL, '2026-06-15 18:28:57', '2026-06-15 18:31:00', NULL),
(129, 113, 'My list', 8, NULL, 10, 0, 'published', 'public', NULL, '2026-06-16 14:25:58', '2026-06-17 11:46:31', NULL),
(135, 112, 'Top 10 fruit', 5, NULL, 4, 1, 'published', 'public', NULL, '2026-06-16 17:38:30', '2026-06-16 17:39:30', NULL),
(136, 113, 'Top 10 food', 8, NULL, 6, 1, 'published', 'public', NULL, '2026-06-16 17:43:24', '2026-06-17 06:21:16', NULL),
(148, 112, 'Test', 5, NULL, 3, 1, 'published', 'public', NULL, '2026-06-18 13:48:23', '2026-06-18 13:48:51', NULL),
(149, 112, 'Test (Copy)', 5, NULL, 3, 0, 'draft', 'private', NULL, '2026-06-18 13:49:24', '2026-06-18 13:49:24', NULL),
(150, 112, 'Test (Copy)', 5, NULL, 3, 0, 'draft', 'private', 148, '2026-06-18 14:11:49', '2026-06-18 14:11:49', NULL),
(151, 112, 'Scott List (Copy)', 5, NULL, 6, 0, 'draft', 'private', 101, '2026-06-18 14:14:32', '2026-06-18 14:14:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `list_items`
--

CREATE TABLE `list_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `list_id` bigint(20) UNSIGNED NOT NULL,
  `catalog_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `custom_item_name` varchar(120) DEFAULT NULL,
  `custom_text` text DEFAULT NULL,
  `position` tinyint(4) DEFAULT NULL,
  `user_positions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`user_positions`)),
  `position_updated_count` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `list_items`
--

INSERT INTO `list_items` (`id`, `list_id`, `catalog_item_id`, `status`, `custom_item_name`, `custom_text`, `position`, `user_positions`, `position_updated_count`, `created_at`, `updated_at`) VALUES
(223, 100, NULL, 'active', 'A', NULL, NULL, NULL, 0, '2026-06-11 15:34:18', '2026-06-11 15:34:18'),
(224, 100, NULL, 'active', 'B', NULL, NULL, NULL, 0, '2026-06-11 15:34:23', '2026-06-11 15:34:23'),
(225, 100, NULL, 'active', 'C', NULL, NULL, NULL, 0, '2026-06-11 15:34:29', '2026-06-11 15:34:29'),
(226, 100, NULL, 'active', 'D', NULL, NULL, NULL, 0, '2026-06-11 15:34:35', '2026-06-11 15:34:35'),
(227, 101, NULL, 'active', 'Afewfewfe', NULL, NULL, '{\"113\":2}', 1, '2026-06-11 15:39:07', '2026-06-17 10:09:12'),
(228, 101, NULL, 'active', 'Bghmghmg', NULL, NULL, '{\"113\":1}', 1, '2026-06-11 15:39:13', '2026-06-17 10:09:27'),
(229, 101, NULL, 'active', 'C', NULL, NULL, '{\"113\":3}', 1, '2026-06-11 15:39:18', '2026-06-11 15:40:28'),
(230, 101, NULL, 'active', 'D', NULL, NULL, '{\"113\":4}', 1, '2026-06-11 15:39:24', '2026-06-11 15:40:28'),
(231, 102, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:26:20', '2026-06-11 16:26:20'),
(232, 102, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:26:20', '2026-06-11 16:26:20'),
(233, 102, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:26:20', '2026-06-11 16:26:20'),
(234, 102, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:26:20', '2026-06-11 16:26:20'),
(235, 103, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:29:39', '2026-06-11 16:29:39'),
(236, 103, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:29:39', '2026-06-11 16:29:39'),
(237, 103, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:29:39', '2026-06-11 16:29:39'),
(238, 103, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:29:39', '2026-06-11 16:29:39'),
(239, 104, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:39:11', '2026-06-11 16:39:11'),
(240, 104, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:39:11', '2026-06-11 16:39:11'),
(241, 104, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:39:11', '2026-06-11 16:39:11'),
(242, 104, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-11 16:39:11', '2026-06-11 16:39:11'),
(243, 105, NULL, 'active', 'Orange', NULL, NULL, NULL, 0, '2026-06-11 17:25:32', '2026-06-11 17:25:32'),
(244, 105, NULL, 'active', 'Apple', NULL, NULL, NULL, 0, '2026-06-11 17:25:39', '2026-06-11 17:25:39'),
(245, 105, NULL, 'active', 'Banana', NULL, NULL, NULL, 0, '2026-06-11 17:25:47', '2026-06-11 17:25:47'),
(246, 106, NULL, 'active', 'Cricket', NULL, NULL, NULL, 0, '2026-06-12 13:29:44', '2026-06-12 13:29:44'),
(247, 106, NULL, 'active', 'Football', NULL, NULL, NULL, 0, '2026-06-12 13:29:51', '2026-06-12 13:29:51'),
(248, 106, NULL, 'active', 'Basketball', NULL, NULL, NULL, 0, '2026-06-12 13:29:58', '2026-06-12 13:29:58'),
(249, 106, NULL, 'active', 'Boxing', NULL, NULL, NULL, 0, '2026-06-12 13:30:09', '2026-06-12 13:30:09'),
(250, 107, NULL, 'active', 'A', NULL, NULL, NULL, 0, '2026-06-12 13:56:06', '2026-06-12 13:56:06'),
(251, 107, NULL, 'active', 'B', NULL, NULL, NULL, 0, '2026-06-12 13:56:11', '2026-06-12 13:56:11'),
(252, 107, NULL, 'active', 'C', NULL, NULL, NULL, 0, '2026-06-12 13:56:17', '2026-06-12 13:56:17'),
(253, 108, NULL, 'active', 'A', NULL, NULL, NULL, 0, '2026-06-15 11:09:21', '2026-06-15 11:09:21'),
(254, 108, NULL, 'active', 'B', NULL, NULL, NULL, 0, '2026-06-15 11:09:27', '2026-06-15 11:09:27'),
(255, 108, NULL, 'active', 'C', NULL, NULL, NULL, 0, '2026-06-15 11:09:32', '2026-06-15 11:09:32'),
(256, 109, NULL, 'active', 'A', NULL, NULL, '{\"112\":2}', 1, '2026-06-15 13:08:10', '2026-06-15 13:09:14'),
(257, 109, NULL, 'active', 'B', NULL, NULL, '{\"112\":1}', 1, '2026-06-15 13:08:14', '2026-06-15 13:09:14'),
(258, 109, NULL, 'active', 'C', NULL, NULL, '{\"112\":3}', 1, '2026-06-15 13:08:19', '2026-06-15 13:09:14'),
(259, 110, NULL, 'active', 'Hello', NULL, NULL, NULL, 0, '2026-06-15 13:13:19', '2026-06-15 17:05:11'),
(260, 110, NULL, 'active', 'B', NULL, NULL, NULL, 0, '2026-06-15 13:13:19', '2026-06-18 13:45:43'),
(261, 110, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 13:13:19', '2026-06-15 13:13:19'),
(262, 111, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 14:35:56', '2026-06-15 14:35:56'),
(263, 111, NULL, 'active', 'th', 'trhtrh', NULL, NULL, 0, '2026-06-15 14:36:00', '2026-06-15 14:36:00'),
(264, 113, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 15:13:38', '2026-06-15 15:13:38'),
(265, 113, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 15:13:38', '2026-06-15 15:13:38'),
(266, 113, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 15:13:38', '2026-06-15 15:13:38'),
(267, 113, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 15:13:38', '2026-06-15 15:13:38'),
(268, 114, NULL, 'active', '3f43', 'fewfwe', NULL, NULL, 0, '2026-06-15 16:47:13', '2026-06-15 16:47:13'),
(269, 114, NULL, 'active', 'eeeweewew', 'fefewf', NULL, NULL, 0, '2026-06-15 16:47:28', '2026-06-15 16:47:28'),
(270, 115, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 16:56:14', '2026-06-15 16:56:14'),
(271, 115, NULL, 'active', 'wfewf', 'ewefewf', NULL, NULL, 0, '2026-06-15 16:57:07', '2026-06-15 16:57:07'),
(272, 115, NULL, 'active', 'wfewf', 'ewefewf', NULL, NULL, 0, '2026-06-15 16:57:25', '2026-06-15 16:57:25'),
(273, 116, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 17:00:37', '2026-06-15 17:00:37'),
(274, 116, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 17:04:29', '2026-06-15 17:04:29'),
(275, 116, NULL, 'active', 'dsvdsvv', 'dsvdsvv', NULL, NULL, 0, '2026-06-15 17:05:42', '2026-06-15 17:05:42'),
(276, 117, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 17:08:47', '2026-06-15 17:08:47'),
(277, 117, NULL, 'active', 'ewfewf', 'ewfewf', NULL, NULL, 0, '2026-06-15 17:08:52', '2026-06-15 17:08:52'),
(278, 118, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 17:11:03', '2026-06-15 17:11:03'),
(279, 118, NULL, 'active', 'dfdfbdfb', 'dfbvdfb', NULL, NULL, 0, '2026-06-15 17:11:08', '2026-06-15 17:11:08'),
(280, 119, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 17:20:29', '2026-06-15 17:20:29'),
(281, 119, NULL, 'active', 'ewfewf', 'ewfewf', NULL, NULL, 0, '2026-06-15 17:20:35', '2026-06-15 17:20:35'),
(282, 120, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 17:29:52', '2026-06-15 17:29:52'),
(283, 120, NULL, 'active', 'efwewf', 'ewfewf', NULL, NULL, 0, '2026-06-15 17:30:02', '2026-06-15 17:30:02'),
(284, 121, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 17:30:54', '2026-06-15 17:30:54'),
(285, 121, NULL, 'active', 'hgrtht', 'rthrth', NULL, NULL, 0, '2026-06-15 17:30:58', '2026-06-15 17:30:58'),
(286, 122, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 17:58:37', '2026-06-15 17:58:37'),
(287, 122, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 17:58:37', '2026-06-15 17:58:37'),
(288, 123, NULL, 'active', 'Aergergeg', NULL, NULL, NULL, 0, '2026-06-15 18:14:49', '2026-06-16 14:18:28'),
(289, 123, NULL, 'active', 'B', NULL, NULL, NULL, 0, '2026-06-15 18:14:56', '2026-06-15 18:14:56'),
(290, 124, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 18:20:20', '2026-06-15 18:20:20'),
(291, 124, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-15 18:20:20', '2026-06-15 18:20:20'),
(292, 125, NULL, 'active', 'A1', 'Test', NULL, NULL, 0, '2026-06-15 18:29:05', '2026-06-15 18:29:17'),
(293, 125, NULL, 'active', 'B', NULL, NULL, NULL, 0, '2026-06-15 18:29:36', '2026-06-15 18:29:36'),
(294, 125, NULL, 'active', 'C', NULL, NULL, NULL, 0, '2026-06-15 18:29:47', '2026-06-15 18:29:47'),
(295, 126, NULL, 'active', 'A', NULL, NULL, NULL, 0, '2026-06-16 10:03:42', '2026-06-16 10:03:42'),
(296, 127, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-16 13:25:20', '2026-06-16 13:25:20'),
(297, 128, NULL, 'active', 'ewfewf', NULL, NULL, NULL, 0, '2026-06-16 13:42:26', '2026-06-16 13:42:26'),
(298, 129, NULL, 'active', 'Hello Updated', 'Updated Description', NULL, NULL, 0, '2026-06-16 14:27:24', '2026-06-16 14:33:57'),
(299, 129, NULL, 'active', 'Hello', 'Hello', NULL, NULL, 0, '2026-06-16 14:29:03', '2026-06-16 14:29:03'),
(300, 129, NULL, 'active', 'kush', 'Updated Description', NULL, NULL, 0, '2026-06-16 14:33:57', '2026-06-16 14:36:13'),
(301, 129, NULL, 'active', 'Table 4', 'Wooden Table', NULL, NULL, 0, '2026-06-16 14:34:21', '2026-06-16 14:34:21'),
(302, 129, NULL, 'active', 'Table 8', 'Wooden Table', NULL, NULL, 0, '2026-06-16 14:36:07', '2026-06-16 14:36:07'),
(303, 129, NULL, 'active', '8', 'Wooden Table', NULL, NULL, 0, '2026-06-16 14:36:13', '2026-06-16 14:36:13'),
(304, 129, NULL, 'active', 'cnjkdnc 8', 'Wooden Table', NULL, NULL, 0, '2026-06-16 14:36:19', '2026-06-16 14:36:19'),
(305, 129, NULL, 'active', 'mxokasjdiuda 8', 'Wooden Table', NULL, NULL, 0, '2026-06-16 14:36:24', '2026-06-16 14:36:24'),
(306, 129, NULL, 'active', 'icaudyugewdyubd 8', 'Wooden Table', NULL, NULL, 0, '2026-06-16 14:36:28', '2026-06-16 14:36:28'),
(307, 129, NULL, 'active', 'xms 8', 'Wooden Table', NULL, NULL, 0, '2026-06-16 14:36:32', '2026-06-16 14:36:32'),
(308, 130, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-16 14:40:24', '2026-06-16 14:40:24'),
(309, 130, NULL, 'active', 'ewfewf', 'ewfewf', NULL, NULL, 0, '2026-06-16 14:40:28', '2026-06-16 14:40:28'),
(310, 131, NULL, 'active', 'A', 'Test A', NULL, NULL, 0, '2026-06-16 15:17:05', '2026-06-16 15:17:05'),
(311, 131, NULL, 'active', 'B', 'Test B', NULL, NULL, 0, '2026-06-16 15:17:21', '2026-06-16 15:17:21'),
(312, 131, NULL, 'active', 'C', 'Test C', NULL, NULL, 0, '2026-06-16 15:17:35', '2026-06-16 15:17:35'),
(313, 131, NULL, 'active', 'D', 'Test D', NULL, NULL, 0, '2026-06-16 15:17:45', '2026-06-16 15:17:45'),
(314, 132, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-16 15:26:23', '2026-06-16 15:26:23'),
(315, 132, NULL, 'active', 'efef', NULL, NULL, NULL, 0, '2026-06-16 15:26:28', '2026-06-16 15:26:28'),
(316, 133, 4, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-16 15:31:46', '2026-06-16 15:31:46'),
(317, 133, NULL, 'active', 'ergerg', NULL, NULL, NULL, 0, '2026-06-16 15:31:50', '2026-06-16 15:31:50'),
(318, 134, 5, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-16 15:32:15', '2026-06-16 15:32:15'),
(319, 134, NULL, 'active', 'egger', 're', NULL, NULL, 0, '2026-06-16 15:32:19', '2026-06-16 15:32:19'),
(320, 134, NULL, 'active', 'egger', 're', NULL, NULL, 0, '2026-06-16 15:32:25', '2026-06-16 15:32:25'),
(321, 135, NULL, 'active', 'A', NULL, NULL, NULL, 0, '2026-06-16 17:38:36', '2026-06-16 17:38:36'),
(322, 135, NULL, 'active', 'B', NULL, NULL, NULL, 0, '2026-06-16 17:38:42', '2026-06-16 17:38:42'),
(323, 135, NULL, 'active', 'C', NULL, NULL, NULL, 0, '2026-06-16 17:38:48', '2026-06-16 17:38:48'),
(324, 135, NULL, 'active', 'D', NULL, NULL, NULL, 0, '2026-06-16 17:38:53', '2026-06-16 17:38:53'),
(325, 136, NULL, 'active', 'A', NULL, NULL, '{\"112\":1}', 2, '2026-06-16 17:43:30', '2026-06-17 11:34:09'),
(326, 136, NULL, 'active', 'B', NULL, NULL, '{\"112\":2}', 2, '2026-06-16 17:43:37', '2026-06-17 11:34:09'),
(327, 113, NULL, 'active', 'ki 8', 'Wooden Table', NULL, NULL, 0, '2026-06-17 09:36:42', '2026-06-17 09:36:42'),
(328, 127, NULL, 'active', 'Mac and Cheese', 'Creamy, cheesy pasta baked to golden perfection.', NULL, NULL, 0, '2026-06-17 10:16:41', '2026-06-17 10:16:41'),
(329, 136, NULL, 'active', 'Mac and Cheese', 'Creamy, cheesy pasta baked to golden perfection.', NULL, '{\"112\":3}', 2, '2026-06-17 10:22:29', '2026-06-17 11:34:09'),
(330, 101, NULL, 'active', 'dtyjtyj', 'tyj', NULL, NULL, 0, '2026-06-17 10:25:44', '2026-06-17 10:25:44'),
(331, 136, NULL, 'active', 'D', NULL, NULL, '{\"112\":4}', 2, '2026-06-17 10:26:08', '2026-06-17 11:34:09'),
(332, 137, 4, 'active', NULL, NULL, NULL, '{\"112\":2}', 1, '2026-06-17 10:27:00', '2026-06-17 10:27:16'),
(333, 137, NULL, 'active', 'ergerg', NULL, NULL, '{\"112\":1}', 1, '2026-06-17 10:27:03', '2026-06-17 10:27:16'),
(334, 137, NULL, 'active', 'gerg', NULL, NULL, NULL, 0, '2026-06-17 10:27:26', '2026-06-17 10:27:26'),
(335, 101, NULL, 'active', 'dd', NULL, NULL, NULL, 0, '2026-06-17 10:34:14', '2026-06-17 10:34:14'),
(336, 137, NULL, 'active', 'dsdsff', NULL, NULL, NULL, 0, '2026-06-17 10:34:42', '2026-06-17 10:34:42'),
(337, 137, NULL, 'active', 'ewfwefwef', NULL, NULL, NULL, 0, '2026-06-17 10:36:32', '2026-06-17 10:36:32'),
(338, 137, NULL, 'active', 'thtrh', NULL, NULL, NULL, 0, '2026-06-17 10:42:07', '2026-06-17 10:42:07'),
(339, 137, NULL, 'active', 'kjkjklj', NULL, NULL, NULL, 0, '2026-06-17 10:42:15', '2026-06-17 10:42:15'),
(340, 136, NULL, 'active', 'E', NULL, NULL, '{\"112\":5}', 2, '2026-06-17 11:30:28', '2026-06-17 11:34:09'),
(341, 136, NULL, 'active', 'F', NULL, NULL, '{\"112\":6}', 2, '2026-06-17 11:30:52', '2026-06-17 11:34:09'),
(342, 138, NULL, 'active', 'A', NULL, NULL, NULL, 0, '2026-06-17 11:32:22', '2026-06-17 11:32:22'),
(343, 138, NULL, 'active', 'B', NULL, NULL, NULL, 0, '2026-06-17 11:34:36', '2026-06-17 11:34:36'),
(344, 139, NULL, 'active', 'A', NULL, NULL, NULL, 0, '2026-06-17 11:36:04', '2026-06-17 11:36:04'),
(345, 139, NULL, 'active', 'B', NULL, NULL, NULL, 0, '2026-06-17 11:36:09', '2026-06-17 11:36:09'),
(346, 139, NULL, 'active', 'C', NULL, NULL, NULL, 0, '2026-06-17 11:36:14', '2026-06-17 11:36:14'),
(347, 140, NULL, 'active', 'A', NULL, NULL, NULL, 0, '2026-06-17 11:37:01', '2026-06-17 11:37:01'),
(348, 140, NULL, 'active', 'B', NULL, NULL, NULL, 0, '2026-06-17 11:37:08', '2026-06-17 11:37:08'),
(349, 140, NULL, 'active', 'C', NULL, NULL, NULL, 0, '2026-06-17 11:38:22', '2026-06-17 11:38:22'),
(350, 140, NULL, 'active', 'D', NULL, NULL, NULL, 0, '2026-06-17 11:38:30', '2026-06-17 11:38:30'),
(351, 140, NULL, 'active', 'E', NULL, NULL, NULL, 0, '2026-06-17 11:38:38', '2026-06-17 11:38:38'),
(352, 141, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:39:10', '2026-06-17 11:39:10'),
(353, 141, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:39:10', '2026-06-17 11:39:10'),
(354, 141, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:39:10', '2026-06-17 11:39:10'),
(355, 141, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:39:10', '2026-06-17 11:39:10'),
(356, 141, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:39:10', '2026-06-17 11:39:10'),
(357, 142, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:39:50', '2026-06-17 11:39:50'),
(358, 142, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:39:50', '2026-06-17 11:39:50'),
(359, 143, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:14', '2026-06-17 11:46:14'),
(360, 143, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:14', '2026-06-17 11:46:14'),
(361, 143, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:14', '2026-06-17 11:46:14'),
(362, 144, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:22', '2026-06-17 11:46:22'),
(363, 144, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:22', '2026-06-17 11:46:22'),
(364, 144, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:22', '2026-06-17 11:46:22'),
(365, 144, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:22', '2026-06-17 11:46:22'),
(366, 144, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:22', '2026-06-17 11:46:22'),
(367, 144, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:22', '2026-06-17 11:46:22'),
(368, 145, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:35', '2026-06-17 11:46:35'),
(369, 145, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:35', '2026-06-17 11:46:35'),
(370, 145, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:35', '2026-06-17 11:46:35'),
(371, 145, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:35', '2026-06-17 11:46:35'),
(372, 145, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:35', '2026-06-17 11:46:35'),
(373, 145, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:35', '2026-06-17 11:46:35'),
(374, 145, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:35', '2026-06-17 11:46:35'),
(375, 145, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:35', '2026-06-17 11:46:35'),
(376, 145, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:35', '2026-06-17 11:46:35'),
(377, 145, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-17 11:46:35', '2026-06-17 11:46:35'),
(378, 146, NULL, 'active', 'A1', NULL, NULL, '{\"113\":2}', 1, '2026-06-18 13:41:04', '2026-06-18 13:44:46'),
(379, 146, NULL, 'active', 'B', NULL, NULL, '{\"113\":1}', 1, '2026-06-18 13:41:08', '2026-06-18 13:43:37'),
(380, 146, NULL, 'active', 'C', NULL, NULL, '{\"113\":3}', 1, '2026-06-18 13:41:13', '2026-06-18 13:43:37'),
(381, 147, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:41:48', '2026-06-18 13:41:48'),
(382, 147, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:41:48', '2026-06-18 13:41:48'),
(383, 147, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:41:48', '2026-06-18 13:41:48'),
(384, 148, NULL, 'active', 'A 1', NULL, NULL, '{\"113\":2}', 1, '2026-06-18 13:48:30', '2026-06-18 13:50:52'),
(385, 148, NULL, 'active', 'B', NULL, NULL, '{\"113\":1}', 1, '2026-06-18 13:48:37', '2026-06-18 13:50:40'),
(386, 148, NULL, 'active', 'C', NULL, NULL, '{\"113\":3}', 1, '2026-06-18 13:49:06', '2026-06-18 13:50:40'),
(387, 149, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:49:24', '2026-06-18 13:49:24'),
(388, 149, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:49:24', '2026-06-18 13:49:24'),
(389, 149, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 13:49:24', '2026-06-18 13:49:24'),
(390, 150, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 14:11:49', '2026-06-18 14:11:49'),
(391, 150, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 14:11:49', '2026-06-18 14:11:49'),
(392, 150, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 14:11:49', '2026-06-18 14:11:49'),
(393, 151, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 14:14:32', '2026-06-18 14:14:32'),
(394, 151, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 14:14:32', '2026-06-18 14:14:32'),
(395, 151, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 14:14:32', '2026-06-18 14:14:32'),
(396, 151, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 14:14:32', '2026-06-18 14:14:32'),
(397, 151, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 14:14:32', '2026-06-18 14:14:32'),
(398, 151, NULL, 'active', NULL, NULL, NULL, NULL, 0, '2026-06-18 14:14:32', '2026-06-18 14:14:32');

-- --------------------------------------------------------

--
-- Table structure for table `list_members`
--

CREATE TABLE `list_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `list_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('invited','accepted','rejected') NOT NULL DEFAULT 'invited',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `list_members`
--

INSERT INTO `list_members` (`id`, `list_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(553, 148, 113, 'accepted', '2026-06-18 13:48:23', '2026-06-18 13:50:25'),
(552, 148, 112, 'accepted', '2026-06-18 13:48:23', '2026-06-18 13:48:23'),
(551, 146, 113, 'accepted', '2026-06-18 13:40:57', '2026-06-18 13:43:09'),
(550, 146, 112, 'accepted', '2026-06-18 13:40:57', '2026-06-18 13:40:57'),
(549, 136, 113, 'invited', '2026-06-16 17:43:24', '2026-06-16 17:43:24'),
(548, 136, 112, 'accepted', '2026-06-16 17:43:24', '2026-06-16 17:43:24'),
(547, 135, 113, 'invited', '2026-06-16 17:38:30', '2026-06-16 17:38:30'),
(546, 135, 112, 'accepted', '2026-06-16 17:38:30', '2026-06-16 17:38:30'),
(545, 131, 112, 'accepted', '2026-06-16 15:16:50', '2026-06-16 15:20:41'),
(544, 131, 113, 'accepted', '2026-06-16 15:16:50', '2026-06-16 15:16:50'),
(543, 125, 112, 'invited', '2026-06-15 18:28:57', '2026-06-15 18:28:57'),
(542, 125, 113, 'accepted', '2026-06-15 18:28:57', '2026-06-15 18:28:57'),
(541, 123, 113, 'accepted', '2026-06-15 18:14:43', '2026-06-15 18:16:55'),
(540, 123, 112, 'accepted', '2026-06-15 18:14:43', '2026-06-15 18:14:43'),
(539, 109, 112, 'accepted', '2026-06-15 13:08:05', '2026-06-15 13:08:52'),
(538, 109, 113, 'accepted', '2026-06-15 13:08:05', '2026-06-15 13:08:05'),
(537, 106, 113, 'accepted', '2026-06-12 13:29:22', '2026-06-12 13:31:38'),
(536, 106, 112, 'accepted', '2026-06-12 13:29:22', '2026-06-12 13:29:22');

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
(4, '2025_12_10_122910_create_users_table', 2),
(5, '2025_12_10_131551_create_personal_access_tokens_table', 3),
(6, '2025_12_11_082953_create_user_consents_table', 4),
(7, '2025_12_11_081137_create_interests_table', 5),
(8, '2025_12_11_083618_create_user_interest_table', 5),
(9, '2025_12_11_102027_create_user_profiles_table', 6),
(10, '2025_12_11_131108_create_admins_table', 6),
(11, '2025_12_15_071727_create_password_resets_table', 7),
(12, '2025_12_15_103622_create_admin_password_otps_table', 8),
(13, '2025_12_16_085358_create_policies_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `sender_id`, `receiver_id`, `type`, `title`, `body`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
(162, 112, 113, 'list_invite', 'List Invitation', 'You have been invited to join a list: Test', '{\"list_id\":148}', '2026-06-18 13:50:25', '2026-06-18 13:48:23', '2026-06-18 13:50:25'),
(163, 113, 112, 'list_invite_accepted', 'Invitation Accepted', 'Flora accepted your list invitation', '{\"list_id\":148}', '2026-06-18 13:50:25', '2026-06-18 13:50:25', '2026-06-18 13:50:25'),
(164, 113, 112, 'list_reordered', 'List Updated', 'Flora reordered items in your list', '{\"list_id\":\"148\"}', '2026-06-18 13:51:29', '2026-06-18 13:50:40', '2026-06-18 13:51:29');

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', 'af4de23e418c16af0bc682ae0b7be8b2a413e679e28b87a8c7ec32d223a7f9b0', '[\"*\"]', NULL, NULL, '2025-12-10 07:46:31', '2025-12-10 07:46:31'),
(3, 'App\\Models\\User', 1, 'auth_token', '507198c021d04e2012067a997d728794131961f0de26d05f15cb6607de30bfb4', '[\"*\"]', '2025-12-10 08:25:35', NULL, '2025-12-10 08:16:24', '2025-12-10 08:25:35'),
(4, 'App\\Models\\User', 1, 'auth_token', 'b2acbc3b6f3eef8b9d94c8fc6bbf109d7a71506397f87800f2026c08743d6116', '[\"*\"]', '2025-12-11 05:12:18', NULL, '2025-12-11 04:43:18', '2025-12-11 05:12:18'),
(5, 'App\\Models\\Admin', 1, 'admin-token', '7bbbe640e3cf9a64cbb0e85a9d1fdd327f10e212da3c28a17bf70ce033a68bfe', '[\"*\"]', NULL, NULL, '2025-12-11 07:51:04', '2025-12-11 07:51:04'),
(6, 'App\\Models\\User', 2, 'auth_token', '29b40ebf8ec4b5d6a32ab4f61dd8260b10d72a147abab5ac9cf0a21c2ee46600', '[\"*\"]', NULL, NULL, '2025-12-15 01:58:54', '2025-12-15 01:58:54'),
(7, 'App\\Models\\User', 2, 'auth_token', 'a33d2dbcea8a9791e30d2140aa36d2ddd1be84cdbc70ca1924b75b91f4c4f904', '[\"*\"]', '2025-12-15 09:01:11', NULL, '2025-12-15 09:01:00', '2025-12-15 09:01:11'),
(8, 'App\\Models\\User', 2, 'auth_token', '02c0b6762b84dc92ae7b733ef3ecd6bdb413f775c7b8df037d49c6c2aeffd271', '[\"*\"]', '2025-12-16 02:59:35', NULL, '2025-12-16 01:08:15', '2025-12-16 02:59:35'),
(9, 'App\\Models\\User', 2, 'auth_token', '621c7a85e74da9d32daca8e80efbabea42a4b345c7542cfa03915c9785a39ae0', '[\"*\"]', NULL, NULL, '2025-12-16 03:05:25', '2025-12-16 03:05:25'),
(11, 'App\\Models\\User', 3, 'auth_token', 'd4cf68426c96a0a1980b874e2e2953f318a23f77ae1d62b0c2038f80f3b2fe81', '[\"*\"]', '2025-12-16 04:56:26', NULL, '2025-12-16 04:56:08', '2025-12-16 04:56:26'),
(12, 'App\\Models\\User', 3, 'auth_token', '7844ca29dc998107e6d7e425b09a253d7b76266827c3c6e961eb7e4457176b63', '[\"*\"]', '2025-12-24 12:55:05', NULL, '2025-12-17 04:43:15', '2025-12-24 12:55:05'),
(13, 'App\\Models\\User', 3, 'auth_token', '216ebba95d89cf1bfe3b2eceea94ef3f3a5de3287fa68d0b72dd1693fc2d1781', '[\"*\"]', '2025-12-22 17:04:36', NULL, '2025-12-17 07:41:54', '2025-12-22 17:04:36'),
(14, 'App\\Models\\User', 3, 'auth_token', '954c62aa850458229497398a1d8309fbf13a5ec82d9fd64443ffea6625542e16', '[\"*\"]', '2025-12-19 16:35:33', NULL, '2025-12-18 03:22:22', '2025-12-19 16:35:33'),
(15, 'App\\Models\\User', 7, 'auth_token', 'feca7f19965463cdc511bda1dcf6578e805f86c4e093643ecf1972cab24c07bf', '[\"*\"]', NULL, NULL, '2025-12-19 16:13:21', '2025-12-19 16:13:21'),
(16, 'App\\Models\\User', 7, 'auth_token', '1f6289063b17d736067529479410a07031ee8230eb1db61c65f3009b9f8775e5', '[\"*\"]', '2025-12-19 16:24:29', NULL, '2025-12-19 16:16:47', '2025-12-19 16:24:29'),
(17, 'App\\Models\\User', 3, 'auth_token', '56c9b6c7067b1b72951183bd52de103bc61c3a6b85ccc409be696ba8bb131229', '[\"*\"]', '2025-12-19 18:27:26', NULL, '2025-12-19 16:21:11', '2025-12-19 18:27:26'),
(18, 'App\\Models\\User', 7, 'auth_token', 'e5a7b63916df0061fd690b059f1a8ead5f2711a7696c6005f01174a9ba6d1281', '[\"*\"]', '2025-12-19 16:27:40', NULL, '2025-12-19 16:26:38', '2025-12-19 16:27:40'),
(19, 'App\\Models\\User', 7, 'auth_token', 'd6f35be08931a5529582cd26780e773954689c0d0faea408536d68eb7a25af08', '[\"*\"]', '2025-12-23 12:14:54', NULL, '2025-12-19 16:29:41', '2025-12-23 12:14:54'),
(20, 'App\\Models\\User', 7, 'auth_token', '1f71c920982c958e46d46d06ebf0320722f1e047b380de57eac3568a025a9bac', '[\"*\"]', '2025-12-23 13:36:52', NULL, '2025-12-19 17:01:14', '2025-12-23 13:36:52'),
(21, 'App\\Models\\User', 7, 'auth_token', 'ba0bc2f584a25a3b57529cc872af48cba5d4f7f393da5f6f46f3967d6728ed6c', '[\"*\"]', '2025-12-19 19:05:59', NULL, '2025-12-19 17:19:19', '2025-12-19 19:05:59'),
(22, 'App\\Models\\User', 3, 'auth_token', '72a1dbd9a296af63bc0988845ec69b6193275966a74ff320e8f7f94eb30221a8', '[\"*\"]', NULL, NULL, '2025-12-19 18:56:52', '2025-12-19 18:56:52'),
(23, 'App\\Models\\User', 7, 'auth_token', '322732bf01025e4c0582cd479d282950fc9c64f9496d537290aecbbf28962eaf', '[\"*\"]', '2025-12-19 19:08:26', NULL, '2025-12-19 19:06:27', '2025-12-19 19:08:26'),
(24, 'App\\Models\\User', 7, 'auth_token', '49f7cc08c560e88cfaff414ff87d6e522327298666ec900ae507b2929344e513', '[\"*\"]', NULL, NULL, '2025-12-20 14:09:33', '2025-12-20 14:09:33'),
(26, 'App\\Models\\User', 7, 'auth_token', '1e3fc057992dcfb75639aa5733523a4dbad1784b35809d43eddd18707aaac47f', '[\"*\"]', NULL, NULL, '2025-12-21 21:37:32', '2025-12-21 21:37:32'),
(27, 'App\\Models\\User', 7, 'auth_token', '6d51d92f1cbcadd013447a0661784d9bc8e60058cca9fd735b8eca59a4d780c5', '[\"*\"]', NULL, NULL, '2025-12-21 22:39:45', '2025-12-21 22:39:45'),
(28, 'App\\Models\\User', 7, 'auth_token', 'f0638cb379ffb8c353197e60f7270ea097cb6f57df571f9740185dfdfb4e10d7', '[\"*\"]', NULL, NULL, '2025-12-21 22:58:26', '2025-12-21 22:58:26'),
(29, 'App\\Models\\User', 7, 'auth_token', 'bc254bd0a8c4447cc4b087a184577ad505e626d16a39b6408ba98781b1f42be6', '[\"*\"]', NULL, NULL, '2025-12-21 23:12:31', '2025-12-21 23:12:31'),
(30, 'App\\Models\\User', 7, 'auth_token', 'e335b09133859c09ad4f350bc1e36bafda9b36c1b7c28e992eaf6c2f4dbe00fb', '[\"*\"]', NULL, NULL, '2025-12-21 23:31:16', '2025-12-21 23:31:16'),
(31, 'App\\Models\\User', 7, 'auth_token', '620959930744f26cbf9f6e84a5d661e62f97b6fd2756bbc0393c634289ead711', '[\"*\"]', NULL, NULL, '2025-12-21 23:33:21', '2025-12-21 23:33:21'),
(32, 'App\\Models\\User', 7, 'auth_token', 'd1124cbae39d81af5253b8bb014ff8bb8d64443d8916214d755bdbad8e48e4d7', '[\"*\"]', NULL, NULL, '2025-12-21 23:56:23', '2025-12-21 23:56:23'),
(33, 'App\\Models\\User', 7, 'auth_token', '911917d629608ffbeaf671ab7b601a06f16d284a8f9f7b1ec5ae39e19652a200', '[\"*\"]', NULL, NULL, '2025-12-22 02:38:44', '2025-12-22 02:38:44'),
(34, 'App\\Models\\User', 16, 'auth_token', '5cb76904f01448263123c749a5a70d0c7524903c754d48272e7f67cdefc97218', '[\"*\"]', NULL, NULL, '2025-12-22 02:41:00', '2025-12-22 02:41:00'),
(35, 'App\\Models\\User', 16, 'auth_token', '05227d82d3fadd97ec4957c83d70c0d503e3ace0b22b126e163f42e9c71b1760', '[\"*\"]', NULL, NULL, '2025-12-22 11:42:08', '2025-12-22 11:42:08'),
(36, 'App\\Models\\User', 16, 'auth_token', '789c1c33ae608022cbb6fa4bbfac1c1e758f19f56142be36b3a2e4dc0ba88fe4', '[\"*\"]', NULL, NULL, '2025-12-22 11:43:17', '2025-12-22 11:43:17'),
(37, 'App\\Models\\User', 17, 'auth_token', 'a49cd412bc2e6b04ddfd2b1629c93aebe50a438133df9d29705369104c63207f', '[\"*\"]', NULL, NULL, '2025-12-22 13:55:51', '2025-12-22 13:55:51'),
(38, 'App\\Models\\User', 7, 'auth_token', 'b657bb2584137bf21a18faf0d94223b4c57511c5ed36f4b447a3d35258fe7eb0', '[\"*\"]', NULL, NULL, '2025-12-23 13:57:30', '2025-12-23 13:57:30'),
(39, 'App\\Models\\User', 3, 'auth_token', '7a6e45b9d4a13be93d9443602fe8b4b97cbba68680ddb1ad1cf93b38fe02eae7', '[\"*\"]', '2025-12-23 18:54:00', NULL, '2025-12-23 18:07:08', '2025-12-23 18:54:00'),
(40, 'App\\Models\\User', 3, 'auth_token', '8c98f8697803e03bbfa8438563740471c710ddfc0f8114325d749225233a5593', '[\"*\"]', '2025-12-23 18:57:01', NULL, '2025-12-23 18:51:22', '2025-12-23 18:57:01'),
(41, 'App\\Models\\User', 3, 'auth_token', '08385412e3bda1d6359c280080616939677ddc9449b1d01e20086f5ad6dce82b', '[\"*\"]', '2025-12-24 13:07:05', NULL, '2025-12-23 18:56:37', '2025-12-24 13:07:05'),
(42, 'App\\Models\\User', 3, 'auth_token', 'f13f8c6593d08fc383593c8a759f488466b3c3a6eebd756752e0746645156be7', '[\"*\"]', '2025-12-23 23:09:32', NULL, '2025-12-23 22:27:09', '2025-12-23 23:09:32'),
(43, 'App\\Models\\User', 3, 'auth_token', '1a585df7f2096818a660f12dcf4d05c76d129beb3590d96d6cd13b31960bc057', '[\"*\"]', '2025-12-24 12:54:50', NULL, '2025-12-24 12:52:32', '2025-12-24 12:54:50'),
(44, 'App\\Models\\User', 3, 'auth_token', '6a1d2200eaec286564d4926b1f11febc7ca45997b224134d5c36f6a6597f2489', '[\"*\"]', '2025-12-24 13:38:41', NULL, '2025-12-24 13:37:42', '2025-12-24 13:38:41'),
(45, 'App\\Models\\User', 38, 'auth_token', 'b180e4ff223db6f3f81bf96e7fd0a019b70ef5abd829717cf8475c52fb7442e4', '[\"*\"]', NULL, NULL, '2025-12-24 18:35:57', '2025-12-24 18:35:57'),
(47, 'App\\Models\\User', 38, 'auth_token', 'd9877ce36070728a705fb740950b70db54d606e66912b9a4ec44726a8f08a12e', '[\"*\"]', NULL, NULL, '2025-12-24 18:42:54', '2025-12-24 18:42:54'),
(48, 'App\\Models\\User', 38, 'auth_token', 'e1eed2aeb1db728968ec21665ced25c6fcb62f98c80f22151696a11a29caf338', '[\"*\"]', NULL, NULL, '2025-12-24 19:10:22', '2025-12-24 19:10:22'),
(51, 'App\\Models\\User', 38, 'auth_token', 'df55db75ab1beed7e1287a419352e0687dcf6250b75f71e067599b6f5a4aca7b', '[\"*\"]', '2025-12-26 11:59:19', NULL, '2025-12-24 19:59:40', '2025-12-26 11:59:19'),
(53, 'App\\Models\\User', 39, 'auth_token', 'bc05ff6ad7ac2ab99789a0cd08941b0834c8bb27ae482aa9ea01811a1812cbcf', '[\"*\"]', NULL, NULL, '2025-12-24 20:14:53', '2025-12-24 20:14:53'),
(54, 'App\\Models\\User', 43, 'auth_token', 'ad02d1ac6b879d279f0dcfde798f47032e02aed297ff54b206318d2a4be700ce', '[\"*\"]', NULL, NULL, '2025-12-24 20:16:29', '2025-12-24 20:16:29'),
(55, 'App\\Models\\User', 38, 'auth_token', '17eb09537fbd4f8bdb09a3ca2cc4df431ae9b7b972ed74874b64da9b828a3f27', '[\"*\"]', NULL, NULL, '2025-12-24 22:36:55', '2025-12-24 22:36:55'),
(56, 'App\\Models\\User', 38, 'auth_token', '5c5fa7e6bb4ceae7b38099791a551e9d2b744b97fad756d3cf9eea27373fb814', '[\"*\"]', '2025-12-30 23:48:49', NULL, '2025-12-24 22:37:51', '2025-12-30 23:48:49'),
(57, 'App\\Models\\User', 38, 'auth_token', '01a569b5342a190cabca05887094b4609ce6d56e4a8d33e3ab6a85b347d7a90e', '[\"*\"]', '2025-12-25 21:43:33', NULL, '2025-12-25 12:25:24', '2025-12-25 21:43:33'),
(58, 'App\\Models\\User', 38, 'auth_token', '38ddb9e8c3aefa5f004ee0296fb6aefef604b6d9f98fe14ccf78176d2d665b19', '[\"*\"]', '2025-12-25 19:36:06', NULL, '2025-12-25 19:20:14', '2025-12-25 19:36:06'),
(59, 'App\\Models\\User', 38, 'auth_token', '0a820719cd4306482a5bea88654c779890d73d7c807b3dd66462590e4628b381', '[\"*\"]', '2025-12-25 19:24:15', NULL, '2025-12-25 19:21:32', '2025-12-25 19:24:15'),
(60, 'App\\Models\\User', 38, 'auth_token', '1e72b615bb6e804a4981885e8bff9bb5f068381fd92e2c4a6404447079374a34', '[\"*\"]', '2025-12-25 22:14:06', NULL, '2025-12-25 22:07:37', '2025-12-25 22:14:06'),
(61, 'App\\Models\\User', 48, 'auth_token', 'fc5597f10896460574867369ea46ee517f26f64bd05646f6949f3fc2816b477c', '[\"*\"]', '2025-12-25 23:09:28', NULL, '2025-12-25 23:09:27', '2025-12-25 23:09:28'),
(62, 'App\\Models\\User', 38, 'auth_token', '86afaa766d3eeaa7c57a43eb847dcb08469d10cdb7aa52722717281f14807160', '[\"*\"]', '2025-12-26 14:56:29', NULL, '2025-12-26 11:56:17', '2025-12-26 14:56:29'),
(63, 'App\\Models\\User', 38, 'auth_token', '0bc3839aa0127db73f517952c36c6207e15ca2549a925ed15e8b9ea8509556b3', '[\"*\"]', NULL, NULL, '2025-12-26 12:21:49', '2025-12-26 12:21:49'),
(64, 'App\\Models\\User', 43, 'auth_token', '02166cb5eed4c51fb44b6d564dea1e4e9a39122f724270294b0eab6119ebba55', '[\"*\"]', '2025-12-27 14:21:51', NULL, '2025-12-26 12:28:18', '2025-12-27 14:21:51'),
(65, 'App\\Models\\User', 38, 'auth_token', '1372fee9617d8c00f4b1c1a11c37acec2b2b960ca4a5e47cd719b775c314e59a', '[\"*\"]', NULL, NULL, '2025-12-26 14:08:04', '2025-12-26 14:08:04'),
(66, 'App\\Models\\User', 50, 'auth_token', '6d9be13d5960d8fdf8be34f364d4064b5438ae7b5b8aea72ec8f60b2e5edbc18', '[\"*\"]', '2025-12-26 14:09:41', NULL, '2025-12-26 14:09:30', '2025-12-26 14:09:41'),
(67, 'App\\Models\\User', 51, 'auth_token', '9303023b17ab6ef7d9c577d6f55cba4f692b728d7d7a1c6508921bb52a9e281f', '[\"*\"]', '2025-12-26 19:29:49', NULL, '2025-12-26 14:57:56', '2025-12-26 19:29:49'),
(68, 'App\\Models\\User', 52, 'auth_token', '50e798e028b1cde247417af50dc636bf0026843dfb1e116a976a61d7f52b9fcb', '[\"*\"]', '2025-12-26 16:39:59', NULL, '2025-12-26 15:08:24', '2025-12-26 16:39:59'),
(69, 'App\\Models\\User', 38, 'auth_token', '1579c36301dafdd59f273c07e03022d8ca61ef19980c637dba76e5bd650997c9', '[\"*\"]', '2025-12-30 12:16:05', NULL, '2025-12-26 15:55:59', '2025-12-30 12:16:05'),
(70, 'App\\Models\\User', 38, 'auth_token', '31837f88c1979e8050701974ca880c89751a8a61669b79955016139bebe4859b', '[\"*\"]', '2025-12-26 19:49:46', NULL, '2025-12-26 18:57:13', '2025-12-26 19:49:46'),
(71, 'App\\Models\\User', 38, 'auth_token', '099cbc262a21b630745e94284d2b764f210e8f71d6f5da735e0b956416034edb', '[\"*\"]', '2025-12-26 19:01:45', NULL, '2025-12-26 18:59:15', '2025-12-26 19:01:45'),
(72, 'App\\Models\\User', 54, 'auth_token', 'd4f7ea2fe3d49e523a550a65349924b31558b5388cc06982ced908041ccaac4c', '[\"*\"]', '2025-12-26 19:49:12', NULL, '2025-12-26 19:42:38', '2025-12-26 19:49:12'),
(73, 'App\\Models\\User', 38, 'auth_token', 'de0fb9a59b6e5151015ca0d5edfa2b713f1c5bc89871a4b184caeb48fbb022fd', '[\"*\"]', '2025-12-26 20:00:35', NULL, '2025-12-26 19:56:45', '2025-12-26 20:00:35'),
(74, 'App\\Models\\User', 38, 'auth_token', '731d21957fd5aca698dbea976899048f872e9d968c2bf6689b4c74bba9f337c2', '[\"*\"]', '2025-12-26 20:31:24', NULL, '2025-12-26 20:15:17', '2025-12-26 20:31:24'),
(75, 'App\\Models\\User', 38, 'auth_token', 'dd643294e31c4e4214ea133750ff37b9336bd187a0425145646beae9dc667598', '[\"*\"]', '2025-12-26 20:45:49', NULL, '2025-12-26 20:45:36', '2025-12-26 20:45:49'),
(76, 'App\\Models\\User', 38, 'auth_token', 'dc007e8d247df8847c689c0bacebad5d48dafb6ae3722e6eaeca4402caca23c0', '[\"*\"]', '2025-12-29 20:15:43', NULL, '2025-12-26 20:49:58', '2025-12-29 20:15:43'),
(77, 'App\\Models\\User', 38, 'auth_token', '016b9be4b51d1e3001a0599d255168aa6b1c0f04f05eab4dfcd1bfed45d93bda', '[\"*\"]', '2025-12-28 00:08:10', NULL, '2025-12-27 12:43:02', '2025-12-28 00:08:10'),
(78, 'App\\Models\\User', 38, 'auth_token', '6971917f3d7789a0961e80641ce95f6d76f2c752f680917f4fb76069f38788a1', '[\"*\"]', '2025-12-27 12:47:40', NULL, '2025-12-27 12:47:24', '2025-12-27 12:47:40'),
(79, 'App\\Models\\User', 43, 'auth_token', '99674340a97870d0c74d8f8234815cd75fb2c07feb5f4b4f531f971335b2c1d7', '[\"*\"]', '2026-01-01 11:24:47', NULL, '2025-12-27 15:38:57', '2026-01-01 11:24:47'),
(80, 'App\\Models\\User', 43, 'auth_token', '5599fb01a3f52333678eb0df47853fcba48f3522bb50457eca8884cc607904f7', '[\"*\"]', '2026-01-05 22:37:42', NULL, '2025-12-27 16:32:54', '2026-01-05 22:37:42'),
(81, 'App\\Models\\User', 43, 'auth_token', 'ab08e7f2c192da0e376bdfa835edf415a9dc21493800d82fa9692b30a6f52e65', '[\"*\"]', '2025-12-31 22:00:09', NULL, '2025-12-27 16:34:54', '2025-12-31 22:00:09'),
(82, 'App\\Models\\User', 43, 'auth_token', '3732f6627e379639233850759b4b1fac95dd0dda4e48cbf26c690de097234ea1', '[\"*\"]', NULL, NULL, '2025-12-28 00:00:33', '2025-12-28 00:00:33'),
(83, 'App\\Models\\User', 38, 'auth_token', '2d317d8ece3814f2aea24f30c9c67fc91fa60ae4e6b383bfed2905e0a2805ce6', '[\"*\"]', NULL, NULL, '2025-12-28 00:01:51', '2025-12-28 00:01:51'),
(84, 'App\\Models\\User', 38, 'auth_token', 'e1b6827e4ffe7d92d57ae52cd49d35400f90fab945dd115e5687f2084663104e', '[\"*\"]', '2025-12-28 00:10:26', NULL, '2025-12-28 00:09:56', '2025-12-28 00:10:26'),
(85, 'App\\Models\\User', 59, 'auth_token', '54c3ca557ef9a95acd336baed8b833f658afe96dee206c2af5282466ce48d497', '[\"*\"]', '2025-12-31 00:06:37', NULL, '2025-12-28 00:12:58', '2025-12-31 00:06:37'),
(86, 'App\\Models\\User', 43, 'auth_token', '4437a83918f6fc342a07ae6b9cfb21b2d63dbe1309683545fecefef171d87be0', '[\"*\"]', NULL, NULL, '2025-12-29 13:09:25', '2025-12-29 13:09:25'),
(87, 'App\\Models\\User', 43, 'auth_token', '6145897738f502b6d6ab723e4fdaef845e1d6c1a4ecf52b1e07246fa439e73a6', '[\"*\"]', '2025-12-29 15:47:10', NULL, '2025-12-29 15:46:30', '2025-12-29 15:47:10'),
(88, 'App\\Models\\User', 43, 'auth_token', '36ba4b7cda2c5c8b6fb65680cb5f8e0afd4d7cad3bca95be2c8ed5ec6edaf2b7', '[\"*\"]', '2026-01-01 19:23:57', NULL, '2025-12-29 15:48:30', '2026-01-01 19:23:57'),
(89, 'App\\Models\\User', 43, 'auth_token', 'ce51d0044166924ce03cf72a5abb2528ecf474840dd5526387548d74b3a70fcc', '[\"*\"]', '2025-12-30 14:42:01', NULL, '2025-12-29 16:05:43', '2025-12-30 14:42:01'),
(90, 'App\\Models\\User', 43, 'auth_token', '47dddb957ea3b00d6ef741e7b18aaf5ad62390e6b3365c83f9a377823f59fad4', '[\"*\"]', '2025-12-30 15:52:41', NULL, '2025-12-29 18:58:45', '2025-12-30 15:52:41'),
(91, 'App\\Models\\User', 43, 'auth_token', '25b2833a6c061f12460d2ce24cbf8236354425d820cdc17991ba9e2954167f70', '[\"*\"]', '2026-01-01 20:10:45', NULL, '2025-12-29 19:19:18', '2026-01-01 20:10:45'),
(92, 'App\\Models\\User', 43, 'auth_token', '00c0698b69c6c4a3a4dcd877671b9a7b334a182f856a56b858cd28aa9fb3ba11', '[\"*\"]', '2025-12-30 19:28:52', NULL, '2025-12-29 19:35:04', '2025-12-30 19:28:52'),
(93, 'App\\Models\\User', 38, 'auth_token', 'a9577d0f3b7120a92974a82d7280aeb44353d9be6d595c989de203eb4e79f8d2', '[\"*\"]', '2025-12-29 22:33:55', NULL, '2025-12-29 20:27:06', '2025-12-29 22:33:55'),
(94, 'App\\Models\\User', 38, 'auth_token', '775d1abbb67c03427ec79dc735f10f8d5cdcc0269704b82f3c5eba32a6b051ca', '[\"*\"]', '2025-12-30 11:41:57', NULL, '2025-12-29 22:35:58', '2025-12-30 11:41:57'),
(95, 'App\\Models\\User', 38, 'auth_token', '773a0735cae130f484cd26bda8aec344b857fb94ef136ea92b28cdba9c8ed452', '[\"*\"]', '2025-12-30 16:53:56', NULL, '2025-12-30 11:23:12', '2025-12-30 16:53:56'),
(96, 'App\\Models\\User', 38, 'auth_token', '95905b34e0c9d4592e0fb8499b32d5398dc7a673c39f20a25037deac8ec30979', '[\"*\"]', '2025-12-30 11:43:29', NULL, '2025-12-30 11:43:28', '2025-12-30 11:43:29'),
(97, 'App\\Models\\User', 38, 'auth_token', '5e99fda5881d54ed3a8ca37b16e2d3ee50575d1bc3133764310c0cf0ca666bcb', '[\"*\"]', '2025-12-30 16:53:51', NULL, '2025-12-30 11:56:53', '2025-12-30 16:53:51'),
(98, 'App\\Models\\User', 43, 'auth_token', '877fa8519d9cd092492dfb42178cc0cb5d84f3ed2ee82804c7ac7179e8b0275f', '[\"*\"]', '2025-12-30 14:32:03', NULL, '2025-12-30 13:34:41', '2025-12-30 14:32:03'),
(99, 'App\\Models\\User', 43, 'auth_token', '10c701cfb39e9b2f0c71d265b34f1a9c26d50a6506673de4567253d26f60ca05', '[\"*\"]', NULL, NULL, '2025-12-30 16:56:15', '2025-12-30 16:56:15'),
(100, 'App\\Models\\User', 38, 'auth_token', 'a8d50d9aa8e9bc0ff1916790cf1e47ee96963a49885f1eac0586faa9832b0f67', '[\"*\"]', '2025-12-30 17:56:45', NULL, '2025-12-30 16:57:31', '2025-12-30 17:56:45'),
(101, 'App\\Models\\User', 60, 'auth_token', 'd0c0a8989017c211af1a26a6ab0fc5fd3784c524cc8a6e94bf61e525af20977d', '[\"*\"]', '2025-12-31 23:09:41', NULL, '2025-12-30 16:57:48', '2025-12-31 23:09:41'),
(102, 'App\\Models\\User', 64, 'auth_token', '186e715fcb7cd1555b13883d9dceadb888df497e78f3a10acfb2858bfd21b39b', '[\"*\"]', '2025-12-30 17:36:59', NULL, '2025-12-30 17:36:58', '2025-12-30 17:36:59'),
(103, 'App\\Models\\User', 60, 'auth_token', 'ff340f345d9e5443581426595890863b7bc12599f652192e5730a29c893ff7d8', '[\"*\"]', '2026-01-01 19:22:38', NULL, '2025-12-30 17:39:53', '2026-01-01 19:22:38'),
(104, 'App\\Models\\User', 57, 'auth_token', '3f2e67d7813e4840fdf3ed8d2d47103677d41466d0d7fcaa74100bfacc3979c2', '[\"*\"]', '2025-12-30 18:05:34', NULL, '2025-12-30 18:05:33', '2025-12-30 18:05:34'),
(105, 'App\\Models\\User', 38, 'auth_token', 'f7e8f10b9dfe123dbfe25a044bbe35fda9ff8164bb07e27a9f21fe3f08792ecf', '[\"*\"]', '2025-12-31 15:56:28', NULL, '2025-12-30 18:09:38', '2025-12-31 15:56:28'),
(106, 'App\\Models\\User', 43, 'auth_token', '3a5fcdb7f05952bf66bbb7f88a3fa607dad7b91b84f0488eef65b72e6bef81fa', '[\"*\"]', '2025-12-30 18:16:13', NULL, '2025-12-30 18:12:44', '2025-12-30 18:16:13'),
(107, 'App\\Models\\User', 38, 'auth_token', 'e3c84d75dd3f28bb713a4fbf6c3d14d1250e2dad52fddda84b34bb769230d24d', '[\"*\"]', '2025-12-30 18:14:07', NULL, '2025-12-30 18:13:19', '2025-12-30 18:14:07'),
(108, 'App\\Models\\User', 64, 'auth_token', '320d784b4ebabd859ccac7f529bf8585e7453fd19992a4d24630aa0095245bb5', '[\"*\"]', '2025-12-30 19:02:23', NULL, '2025-12-30 18:17:47', '2025-12-30 19:02:23'),
(109, 'App\\Models\\User', 38, 'auth_token', 'bcf5e1ea1ff8ef38879061135211415b6df23408d2f41ec2187b96848606482f', '[\"*\"]', '2025-12-30 20:20:53', NULL, '2025-12-30 18:47:19', '2025-12-30 20:20:53'),
(110, 'App\\Models\\User', 64, 'auth_token', 'bbac15eefd572566a823a69649c35b802a29a30065ef4743eb0d89b1f22a683d', '[\"*\"]', NULL, NULL, '2025-12-30 19:05:36', '2025-12-30 19:05:36'),
(111, 'App\\Models\\User', 38, 'auth_token', '57447cebc317a42b85ba71c7a6a574be9d301b46f65464393f07a9dd1a3a8873', '[\"*\"]', '2025-12-30 19:21:16', NULL, '2025-12-30 19:13:07', '2025-12-30 19:21:16'),
(112, 'App\\Models\\User', 38, 'auth_token', 'd29ac7badad82fbe5b24dff166d324eb2db58ce7687e87202f06cf9c634fb875', '[\"*\"]', '2026-01-02 13:04:45', NULL, '2025-12-30 19:24:58', '2026-01-02 13:04:45'),
(113, 'App\\Models\\User', 38, 'auth_token', '71c12c0ed6331df8540b0f08cc9c843b8703e17f3eefb140d1b06408e5d10041', '[\"*\"]', '2025-12-30 20:35:50', NULL, '2025-12-30 20:34:44', '2025-12-30 20:35:50'),
(114, 'App\\Models\\User', 38, 'auth_token', '80428ef1adfb4ab0943ebc27e8c63d7f02ce432e4b9f9662a5ba881cfc59e0a9', '[\"*\"]', '2025-12-30 20:43:05', NULL, '2025-12-30 20:43:04', '2025-12-30 20:43:05'),
(115, 'App\\Models\\User', 38, 'auth_token', '0abefe727dd5d85a8083c10842584635c480bdb49f9352e63c25ac2fad7e4121', '[\"*\"]', '2025-12-31 17:26:41', NULL, '2025-12-30 22:22:44', '2025-12-31 17:26:41'),
(116, 'App\\Models\\User', 38, 'auth_token', 'a700848fec8ebcbcf8df6e9b96468d67f740288a2ef9adb1b85ad86a0d37bf27', '[\"*\"]', NULL, NULL, '2025-12-31 00:07:23', '2025-12-31 00:07:23'),
(117, 'App\\Models\\User', 47, 'auth_token', '4b98b9bab38a3771129573bb3c9e5959075df0112f6e2cc111dbdaaf2bc408ab', '[\"*\"]', '2025-12-31 00:14:15', NULL, '2025-12-31 00:08:57', '2025-12-31 00:14:15'),
(118, 'App\\Models\\User', 38, 'auth_token', '8b206447a1a3b7e14ef66cf4d62a31f1119d7438b010647dbcc84ffb6b46d056', '[\"*\"]', '2025-12-31 22:53:09', NULL, '2025-12-31 00:15:15', '2025-12-31 22:53:09'),
(119, 'App\\Models\\User', 60, 'auth_token', '6c570fa345cd3beed54db8b10aa98c7818ddb7d4868f60b89e9fec8d256b0463', '[\"*\"]', '2025-12-31 11:24:57', NULL, '2025-12-31 10:59:44', '2025-12-31 11:24:57'),
(120, 'App\\Models\\User', 60, 'auth_token', '88ed0a2445f90587a94a95a2d945bb0ea15dfe84662e900e38355d6141f93926', '[\"*\"]', '2025-12-31 11:31:32', NULL, '2025-12-31 11:24:16', '2025-12-31 11:31:32'),
(121, 'App\\Models\\User', 38, 'auth_token', 'c15e318f32f7ab670649b5907c1e521eb845b5539dc7a4ddb9d98e4d3f74e159', '[\"*\"]', '2025-12-31 21:59:29', NULL, '2025-12-31 14:01:35', '2025-12-31 21:59:29'),
(122, 'App\\Models\\User', 38, 'auth_token', '458ca4cfa9f1eb8f9406381df12abf8245f63d9bb6edccffca8984034bae109e', '[\"*\"]', '2025-12-31 17:39:02', NULL, '2025-12-31 15:45:52', '2025-12-31 17:39:02'),
(123, 'App\\Models\\User', 38, 'auth_token', '5a6f84b2f2f431729f5d7dcbc0374889683030fabba4722d34dd3bf3bb7ca9b3', '[\"*\"]', '2026-01-01 19:42:39', NULL, '2025-12-31 15:56:50', '2026-01-01 19:42:39'),
(124, 'App\\Models\\User', 43, 'auth_token', 'd2138ad2433ee5b1be291a4ac0800f52524eeb76b34cd0d74991f05b224951d8', '[\"*\"]', '2025-12-31 21:46:42', NULL, '2025-12-31 21:46:23', '2025-12-31 21:46:42'),
(125, 'App\\Models\\User', 38, 'auth_token', '68cca619a6ab9eab1ef12511f9021972e4c603743ff4dfcf74b097df6d2bda53', '[\"*\"]', '2025-12-31 23:19:23', NULL, '2025-12-31 21:56:18', '2025-12-31 23:19:23'),
(126, 'App\\Models\\User', 38, 'auth_token', '6b02285d7a93823fe9de69429ea79d7d487bd0c54de320c401341c7e616755c7', '[\"*\"]', '2025-12-31 23:33:11', NULL, '2025-12-31 23:17:19', '2025-12-31 23:33:11'),
(127, 'App\\Models\\User', 64, 'auth_token', '468c624cf2a94f6287fb9879ff43aa542fad678d7752c94fd0cd3bccc0cdf16a', '[\"*\"]', '2026-01-02 11:43:01', NULL, '2026-01-01 12:41:59', '2026-01-02 11:43:01'),
(128, 'App\\Models\\User', 38, 'auth_token', 'fbdefb0116369bad788e7d0559ad27a948707be50af8a68c2a9c8f390c4e4a31', '[\"*\"]', '2026-01-01 17:47:26', NULL, '2026-01-01 14:00:30', '2026-01-01 17:47:26'),
(129, 'App\\Models\\User', 38, 'auth_token', '5e2a33d99db82bdc71a4e5e041f961b4e05117debe8ed26f9451dbc945537455', '[\"*\"]', '2026-01-01 18:15:43', NULL, '2026-01-01 18:12:32', '2026-01-01 18:15:43'),
(130, 'App\\Models\\User', 38, 'auth_token', '50f01b79b4ac90e58920bc6a66284f57bc09afd039b65e3f1037a64a095ee597', '[\"*\"]', '2026-01-01 18:26:29', NULL, '2026-01-01 18:24:54', '2026-01-01 18:26:29'),
(131, 'App\\Models\\User', 38, 'auth_token', '227ae9d67a7ca3e3d976474bceeaa0941703628134dd7f2c70577871d7d8b6fe', '[\"*\"]', '2026-01-01 19:06:23', NULL, '2026-01-01 19:06:10', '2026-01-01 19:06:23'),
(132, 'App\\Models\\User', 38, 'auth_token', 'c7c7422f34411944cdf297124d097096656019f59bafa882e686075b07f2823f', '[\"*\"]', '2026-01-06 13:31:19', NULL, '2026-01-01 20:04:42', '2026-01-06 13:31:19'),
(133, 'App\\Models\\User', 38, 'auth_token', '2b7dd1c4ea8d9d65372b9631e3ff137d56dbedb5aceaf89ea7e8b710d8339061', '[\"*\"]', '2026-01-02 19:20:18', NULL, '2026-01-01 20:10:30', '2026-01-02 19:20:18'),
(134, 'App\\Models\\User', 60, 'auth_token', '705617eb7ce76baf7dbe5a0e5051b3503ebd792faf523dd1eeb43970fea17a56', '[\"*\"]', '2026-01-01 20:29:37', NULL, '2026-01-01 20:13:44', '2026-01-01 20:29:37'),
(135, 'App\\Models\\User', 60, 'auth_token', '1d6822c4cd281b36783b9233af2f610fdf24ee1a54e482b9d1741ab27c9dd0be', '[\"*\"]', '2026-01-02 12:46:11', NULL, '2026-01-02 12:34:20', '2026-01-02 12:46:11'),
(136, 'App\\Models\\User', 62, 'auth_token', 'bf8d4c84951dad3a0aff8ca6a6896355ead8dfb99fd1b70caeba3b71896ee3c8', '[\"*\"]', '2026-01-02 12:53:45', NULL, '2026-01-02 12:50:52', '2026-01-02 12:53:45'),
(137, 'App\\Models\\User', 62, 'auth_token', 'aac2d3f569bcbadb42c01f1032b33e21108d16e549e8890dc7e593fd92804400', '[\"*\"]', '2026-01-02 19:43:48', NULL, '2026-01-02 16:30:55', '2026-01-02 19:43:48'),
(138, 'App\\Models\\User', 41, 'auth_token', '3bfa94d7127d3c0398ab28541b0d436082149826ee30d6fa1bf32b8f0a494e14', '[\"*\"]', '2026-01-02 16:46:45', NULL, '2026-01-02 16:38:37', '2026-01-02 16:46:45'),
(139, 'App\\Models\\User', 43, 'auth_token', '9c9d369a1ff72057bb72ed3d3adec1301dab017501822836533415f781798a1f', '[\"*\"]', '2026-01-02 16:51:08', NULL, '2026-01-02 16:41:40', '2026-01-02 16:51:08'),
(140, 'App\\Models\\User', 38, 'auth_token', '926e27abb995b724f723ffda83cf7f21c5f5ea6b6c40f134b35816666cdc6bff', '[\"*\"]', '2026-01-02 18:32:26', NULL, '2026-01-02 17:57:30', '2026-01-02 18:32:26'),
(141, 'App\\Models\\User', 38, 'auth_token', '94f8fe6ee90b4d597381e210b26590ac7d35d575e1fbd51bcd803dd0760419a6', '[\"*\"]', '2026-01-02 19:40:16', NULL, '2026-01-02 18:45:35', '2026-01-02 19:40:16'),
(142, 'App\\Models\\User', 38, 'auth_token', 'f5b5c5dbe4ab8f96cd9c339cfc9f8295d8dacf30d8d30d93527c203acdcd8c67', '[\"*\"]', '2026-01-02 20:00:50', NULL, '2026-01-02 19:42:16', '2026-01-02 20:00:50'),
(143, 'App\\Models\\User', 64, 'auth_token', '519ad16dc9e775028a3569c00d75ec90fbc670b22b1f92338e6d10c746e20d1a', '[\"*\"]', '2026-01-02 20:19:01', NULL, '2026-01-02 20:15:22', '2026-01-02 20:19:01'),
(144, 'App\\Models\\User', 38, 'auth_token', 'a117dff07b68a74fbff052dc31b26313e752b932fa4c0140160d7ac0415b71d5', '[\"*\"]', '2026-01-04 17:03:22', NULL, '2026-01-02 20:20:15', '2026-01-04 17:03:22'),
(145, 'App\\Models\\User', 64, 'auth_token', 'dfc69b5c7357c208f7fcf0d5ab6ed75e4af53384d816af9009ada15bbfac094d', '[\"*\"]', '2026-01-06 10:44:17', NULL, '2026-01-02 20:24:33', '2026-01-06 10:44:17'),
(146, 'App\\Models\\User', 64, 'auth_token', '5dce0a0c1d927a08ea4853e8a3db1ebe425f015ed6b73708cdc15804f61d31a4', '[\"*\"]', '2026-01-05 11:17:28', NULL, '2026-01-05 11:13:19', '2026-01-05 11:17:28'),
(147, 'App\\Models\\User', 70, 'auth_token', '823c3d74a8fed0ee157d76210f2c1964da74b4e62f0649064be4cd1397cfae50', '[\"*\"]', '2026-01-05 14:34:36', NULL, '2026-01-05 12:08:16', '2026-01-05 14:34:36'),
(148, 'App\\Models\\User', 70, 'auth_token', 'ab1d4f72f578630b5bbc5231dc14a52bcbb0a7d9c58e1c8aae63f0b17011005d', '[\"*\"]', '2026-01-05 14:35:37', NULL, '2026-01-05 14:35:36', '2026-01-05 14:35:37'),
(149, 'App\\Models\\User', 62, 'auth_token', 'e9cd3090181b89753c8038761d4565403e5ea35dff6b35e7967adcf7b0b0a037', '[\"*\"]', NULL, NULL, '2026-01-05 14:40:17', '2026-01-05 14:40:17'),
(150, 'App\\Models\\User', 70, 'auth_token', 'e925551a9340da051a055828218cf3f52d232bf23f5d36f937b3b45a954332cd', '[\"*\"]', '2026-01-05 14:57:00', NULL, '2026-01-05 14:51:43', '2026-01-05 14:57:00'),
(151, 'App\\Models\\User', 70, 'auth_token', '49c2f305022238b1ccfe20277dd4ce5fdd9aee469ec0e2348afdd02b5de2bed9', '[\"*\"]', '2026-01-05 15:48:04', NULL, '2026-01-05 15:05:20', '2026-01-05 15:48:04'),
(152, 'App\\Models\\User', 62, 'auth_token', 'f9521dbd935ca0b29848846bd9871864e7082beff6ea515c2e07f344508f9c88', '[\"*\"]', NULL, NULL, '2026-01-05 15:45:55', '2026-01-05 15:45:55'),
(153, 'App\\Models\\User', 70, 'auth_token', '95c7fa144cda27563957c3f7959502da977ce8431683096c559dc93e9c09eeb4', '[\"*\"]', '2026-01-06 11:57:33', NULL, '2026-01-05 15:54:27', '2026-01-06 11:57:33'),
(154, 'App\\Models\\User', 72, 'auth_token', '2ac6df3ef60616c1f57996ea5c5b4c701773d4c2e9bac0ffe9d60c642ba1c769', '[\"*\"]', '2026-01-05 20:31:55', NULL, '2026-01-05 20:15:12', '2026-01-05 20:31:55'),
(155, 'App\\Models\\User', 43, 'auth_token', '78d207e2196d6851153dffbc705feaa228be691d16128a4e87d9f963340e4525', '[\"*\"]', '2026-01-05 22:49:03', NULL, '2026-01-05 22:38:27', '2026-01-05 22:49:03'),
(156, 'App\\Models\\User', 62, 'auth_token', '57016b77eb499935abb252baf877c39f25db1746251b76148b3f8f6b6d589a17', '[\"*\"]', '2026-01-06 15:55:50', NULL, '2026-01-06 10:26:31', '2026-01-06 15:55:50'),
(157, 'App\\Models\\User', 74, 'auth_token', '7f152213e29b235b777d8eb63bf55019d557750f82cfe19b47d3c7c45d45e71d', '[\"*\"]', '2026-01-06 12:39:39', NULL, '2026-01-06 12:39:36', '2026-01-06 12:39:39'),
(158, 'App\\Models\\User', 70, 'auth_token', 'd54ff6a794dd013d289f33ddcd988f0b4dc8bd8a1d12de1f3dd18be72f52ca4c', '[\"*\"]', '2026-01-06 14:06:55', NULL, '2026-01-06 13:19:57', '2026-01-06 14:06:55'),
(159, 'App\\Models\\User', 70, 'auth_token', '7db0c6bff83fbd638bc9e247f9045fe61232570478badda53fb716a8bb406a72', '[\"*\"]', '2026-01-07 11:10:59', NULL, '2026-01-06 13:31:48', '2026-01-07 11:10:59'),
(160, 'App\\Models\\User', 74, 'auth_token', 'e396bcb451dbe6289c932d86f40b19dc8de86769d809f024468f88c2393621a8', '[\"*\"]', '2026-01-06 14:11:58', NULL, '2026-01-06 14:07:38', '2026-01-06 14:11:58'),
(161, 'App\\Models\\User', 76, 'auth_token', 'b121ab3cb55414d2f2adc131a968410819bd4c61231a06ca37e735f29f29c5ea', '[\"*\"]', '2026-01-06 15:09:49', NULL, '2026-01-06 14:11:03', '2026-01-06 15:09:49'),
(162, 'App\\Models\\User', 74, 'auth_token', 'fc65e4ee651323933f3cb64639539b146e7d516b9a5380bbc0d63dae8b263ac9', '[\"*\"]', '2026-01-06 14:35:29', NULL, '2026-01-06 14:16:25', '2026-01-06 14:35:29'),
(163, 'App\\Models\\User', 74, 'auth_token', 'b595edd4ac7356e1b9b9be7a5c16410e0eb3b9cba551291bb1b4d549f7b14e90', '[\"*\"]', '2026-01-06 18:50:49', NULL, '2026-01-06 16:28:33', '2026-01-06 18:50:49'),
(164, 'App\\Models\\User', 74, 'auth_token', 'a5f3910aa5c34d35dca76a2196a8c67c4254121f80fe854b51d9d99217967f3f', '[\"*\"]', '2026-01-06 18:41:35', NULL, '2026-01-06 17:37:30', '2026-01-06 18:41:35'),
(165, 'App\\Models\\User', 74, 'auth_token', '52e589edd601cc1696ee9479986dee6e0cad1a5496df534a713b9b0284e8c71c', '[\"*\"]', '2026-01-06 18:25:37', NULL, '2026-01-06 18:06:43', '2026-01-06 18:25:37'),
(166, 'App\\Models\\User', 38, 'auth_token', '8d0d2d0a861d04da9b0bea61aeb3a389881ca5d5e1ff56b49a3fcf9032da7ece', '[\"*\"]', '2026-01-07 00:25:53', NULL, '2026-01-07 00:25:07', '2026-01-07 00:25:53'),
(167, 'App\\Models\\User', 38, 'auth_token', '5503253c5242b5098f60b62176a2a52e04789f2c68f4a4ab173b3f74966f7c53', '[\"*\"]', '2026-01-07 11:13:03', NULL, '2026-01-07 11:12:44', '2026-01-07 11:13:03'),
(168, 'App\\Models\\User', 38, 'auth_token', 'ced1470084ab911d073145c20eaa5ddc13918e383a6e1c0d7106124371216ead', '[\"*\"]', '2026-01-07 11:13:41', NULL, '2026-01-07 11:13:41', '2026-01-07 11:13:41'),
(169, 'App\\Models\\User', 38, 'auth_token', '473d61009c506cd3cdceda3ace54480bee6566116515f8d59bffffb4cde543ad', '[\"*\"]', '2026-01-07 11:18:43', NULL, '2026-01-07 11:17:03', '2026-01-07 11:18:43'),
(170, 'App\\Models\\User', 85, 'auth_token', '69c13ae0c5a4a474d6c0fe5a7981dd3297580816edb0cd0d58b2095e39b6cbe6', '[\"*\"]', NULL, NULL, '2026-01-07 11:20:55', '2026-01-07 11:20:55'),
(171, 'App\\Models\\User', 86, 'auth_token', '520d2a8bc68f0fed89576d4ce54b8753f59acb375e38cd3a7bb389cc1950960b', '[\"*\"]', '2026-01-07 11:51:59', NULL, '2026-01-07 11:24:49', '2026-01-07 11:51:59'),
(172, 'App\\Models\\User', 86, 'auth_token', 'c8da8daffab86a34684cf029514e881d1cd010e72608d8f7a446f6562524d494', '[\"*\"]', '2026-01-07 11:50:42', NULL, '2026-01-07 11:42:40', '2026-01-07 11:50:42'),
(173, 'App\\Models\\User', 86, 'auth_token', '2e72e58bc11e52274f6ac1203e692a6081a11f96a7729444e4a7846feec5dd56', '[\"*\"]', '2026-01-07 11:56:44', NULL, '2026-01-07 11:56:11', '2026-01-07 11:56:44'),
(174, 'App\\Models\\User', 87, 'auth_token', '77868617199152a0034f9b9303532422727ad0c16918ac2e8ffa45f0c0563572', '[\"*\"]', '2026-01-12 18:34:22', NULL, '2026-01-07 11:57:14', '2026-01-12 18:34:22'),
(175, 'App\\Models\\User', 87, 'auth_token', '4c633434d1d28bc2cba89eb37f0f80751e3867ff7ef310e0a07d4860532deee9', '[\"*\"]', '2026-01-07 11:58:30', NULL, '2026-01-07 11:58:17', '2026-01-07 11:58:30'),
(176, 'App\\Models\\User', 88, 'auth_token', '4f003b4f1562be5316d00089661cf79d1f88bc583983d6cbd7e1ff6c08fd9968', '[\"*\"]', '2026-01-07 14:53:15', NULL, '2026-01-07 14:52:26', '2026-01-07 14:53:15'),
(177, 'App\\Models\\User', 89, 'auth_token', 'e7db393d415beb80cd26b3287928b6e55e1c9b85a952bc36c3ea66c88f26614b', '[\"*\"]', '2026-01-07 14:58:30', NULL, '2026-01-07 14:54:18', '2026-01-07 14:58:30'),
(178, 'App\\Models\\User', 90, 'auth_token', '2dd699a2cb7d7145741876d9ead27867afd0959423c7cea23b4a03fbdb8e0aeb', '[\"*\"]', '2026-01-08 20:27:24', NULL, '2026-01-08 00:35:11', '2026-01-08 20:27:24'),
(179, 'App\\Models\\User', 91, 'auth_token', '7c1a9a38c2990e761f73d6818f82ca26807406c153dbacd406822102d962b914', '[\"*\"]', '2026-01-09 16:30:18', NULL, '2026-01-08 08:07:06', '2026-01-09 16:30:18'),
(180, 'App\\Models\\User', 92, 'auth_token', 'dbcfbd09ae808c5dae8ce46c7faba9f234b42d9d13f6963e584746021c19f4c1', '[\"*\"]', '2026-01-09 15:27:28', NULL, '2026-01-08 12:07:23', '2026-01-09 15:27:28'),
(181, 'App\\Models\\User', 94, 'auth_token', '985f1202a24f014c9330a45ebc910320b4a0881ce87112da30b9f1530c83cb99', '[\"*\"]', '2026-01-08 17:54:10', NULL, '2026-01-08 17:54:02', '2026-01-08 17:54:10'),
(182, 'App\\Models\\User', 93, 'auth_token', '073c8134420e2d5ddf846376d0d4911afa80e9f50542d86d48b45063c826ac08', '[\"*\"]', '2026-01-08 17:56:54', NULL, '2026-01-08 17:56:54', '2026-01-08 17:56:54'),
(183, 'App\\Models\\User', 93, 'auth_token', '63d71690340264eb341c751566e41dc7a6873c2036322ee8120021721832161b', '[\"*\"]', '2026-01-08 18:50:50', NULL, '2026-01-08 17:57:59', '2026-01-08 18:50:50'),
(184, 'App\\Models\\User', 86, 'auth_token', '2c3e29d606156918040d8df6860e2a38eef60ba37a8df6b8b5ab9f73ab03c2dc', '[\"*\"]', '2026-01-08 20:28:09', NULL, '2026-01-08 20:27:36', '2026-01-08 20:28:09'),
(185, 'App\\Models\\User', 95, 'auth_token', '71010cf53b2991288ea8506977513085d06a828230062f00e0236edb0fb47d04', '[\"*\"]', '2026-03-19 16:29:14', NULL, '2026-01-09 11:34:59', '2026-03-19 16:29:14'),
(186, 'App\\Models\\User', 95, 'auth_token', '84a8d286e89cacdbb11df8a8e4b01aaae627f40bad48b36b5e96a536e99625ac', '[\"*\"]', '2026-03-18 16:56:50', NULL, '2026-01-09 12:35:50', '2026-03-18 16:56:50'),
(187, 'App\\Models\\User', 86, 'auth_token', 'e60facdcf58c9fe4980495293b9bcf925797a568c59b67bad475113b7e381e8d', '[\"*\"]', '2026-01-09 13:36:42', NULL, '2026-01-09 13:36:03', '2026-01-09 13:36:42'),
(188, 'App\\Models\\User', 96, 'auth_token', '6894d2bb059bf60a83ab2b0c28a9b38024e22c7dc8086b7425fe26f886f6dfd2', '[\"*\"]', '2026-01-09 16:54:14', NULL, '2026-01-09 13:38:04', '2026-01-09 16:54:14'),
(189, 'App\\Models\\User', 86, 'auth_token', '7a80c8e2636901e398b4e6d19dfe33d8466fcdb74634c2e7aec76134616b31d3', '[\"*\"]', '2026-01-12 18:29:56', NULL, '2026-01-09 14:08:13', '2026-01-12 18:29:56'),
(190, 'App\\Models\\User', 86, 'auth_token', '7e0b89b091941198744fb51d808f774e496c15434e42be0fdf18d6e7ed778692', '[\"*\"]', '2026-01-09 14:29:59', NULL, '2026-01-09 14:22:37', '2026-01-09 14:29:59'),
(192, 'App\\Models\\User', 90, 'auth_token', '554f3084a12a8adc76ec6a29cfa4c9aae96b3c2b7282467d8446eac648b4a111', '[\"*\"]', '2026-01-09 15:46:50', NULL, '2026-01-09 15:38:57', '2026-01-09 15:46:50'),
(193, 'App\\Models\\User', 86, 'auth_token', 'e5fabd6a243e07a1c971a318f3d36db3c6488c560e93fb51bd3ce4a45433ad58', '[\"*\"]', '2026-01-09 15:44:42', NULL, '2026-01-09 15:41:03', '2026-01-09 15:44:42'),
(194, 'App\\Models\\User', 96, 'auth_token', '740b8cd2a6ef5ff93a9825e722e172019c387c89c6cbe3a2be65ad114d9720fd', '[\"*\"]', '2026-01-09 17:45:25', NULL, '2026-01-09 15:46:06', '2026-01-09 17:45:25'),
(195, 'App\\Models\\User', 92, 'auth_token', '433c8d7313aacbf5cce50ef748ccc90b0bdf47abdab5f59cf4db65d09bb0b504', '[\"*\"]', '2026-03-19 13:19:13', NULL, '2026-01-09 15:48:08', '2026-03-19 13:19:13'),
(196, 'App\\Models\\User', 90, 'auth_token', '35291ac539f44cc4325b3da52bde069f916032bcad52c13379cf94f2ff0c89d8', '[\"*\"]', '2026-01-09 16:53:48', NULL, '2026-01-09 16:50:24', '2026-01-09 16:53:48'),
(197, 'App\\Models\\User', 94, 'auth_token', '7d57d163388f00c3a3cccd550cd3188b0186e41e2391c1394b467356f568c6f1', '[\"*\"]', '2026-01-09 18:01:30', NULL, '2026-01-09 17:33:41', '2026-01-09 18:01:30'),
(198, 'App\\Models\\User', 90, 'auth_token', '2f4d4c3b7547faf2c4b2bbaec0bd08e9af0b62b12c150949331506a99463f0ab', '[\"*\"]', '2026-01-10 13:06:47', NULL, '2026-01-09 17:52:07', '2026-01-10 13:06:47'),
(199, 'App\\Models\\User', 94, 'auth_token', '3c00ae4cc16490b2a44ab2310f9fd5802e225f5dca837a6a4493827c07a482cd', '[\"*\"]', '2026-01-12 23:37:06', NULL, '2026-01-09 20:08:40', '2026-01-12 23:37:06'),
(200, 'App\\Models\\User', 97, 'auth_token', 'd2058212ea54d60f3b6e2f184ff82b70a0a99cc63512e8712c686d10a5c062a0', '[\"*\"]', '2026-01-09 20:54:11', NULL, '2026-01-09 20:50:24', '2026-01-09 20:54:11'),
(201, 'App\\Models\\User', 98, 'auth_token', '69fab834032e8be64309272cb270072bc7c03ace4252a108f4f6f6eb649a67e9', '[\"*\"]', '2026-03-11 12:33:55', NULL, '2026-01-09 21:25:48', '2026-03-11 12:33:55'),
(202, 'App\\Models\\User', 90, 'auth_token', '3852691124e3477e7252fdc09f7586e04a93ec2847347a78afed0a95b54f7a40', '[\"*\"]', '2026-01-09 21:32:09', NULL, '2026-01-09 21:31:03', '2026-01-09 21:32:09'),
(203, 'App\\Models\\User', 90, 'auth_token', 'f302b794684fc88f72a52a6602d67fd2d897c3208c58497e242690a99f5ac6e8', '[\"*\"]', '2026-03-19 16:32:49', NULL, '2026-01-09 21:59:27', '2026-03-19 16:32:49'),
(204, 'App\\Models\\User', 86, 'auth_token', 'c73090c2aba137e5c079b853e28d486aec3e862ed8a11ed9218b083eac16d4aa', '[\"*\"]', '2026-01-09 23:28:23', NULL, '2026-01-09 22:16:07', '2026-01-09 23:28:23'),
(205, 'App\\Models\\User', 86, 'auth_token', '91ad5fe79f0768b61c8c6e4a9817548ace96753fab2c9bc3bf59365efabdb967', '[\"*\"]', '2026-01-09 22:20:51', NULL, '2026-01-09 22:20:38', '2026-01-09 22:20:51'),
(206, 'App\\Models\\User', 90, 'auth_token', '30aa35dd431b99ace0028e9000fec953860cd9615ee66f73c96bca2bd821a4a2', '[\"*\"]', '2026-01-13 16:28:26', NULL, '2026-01-09 22:33:32', '2026-01-13 16:28:26'),
(207, 'App\\Models\\User', 86, 'auth_token', 'ec089256ecf673266b11cba6a0c6efd8eec308fda8ff299ec98c4eafbb2f79d4', '[\"*\"]', '2026-01-10 01:14:18', NULL, '2026-01-10 00:06:47', '2026-01-10 01:14:18'),
(208, 'App\\Models\\User', 86, 'auth_token', '6612f8d4723c7fa04ea7bcb2d9e5bf476f0c374771a327a10e6603a2750b9dcb', '[\"*\"]', '2026-01-10 12:03:38', NULL, '2026-01-10 00:36:40', '2026-01-10 12:03:38'),
(209, 'App\\Models\\User', 86, 'auth_token', '7a59904b33f6f90f6dfe16e6f67db8fa388cce8af67c0c234b5d1ab28d0e4d41', '[\"*\"]', NULL, NULL, '2026-01-10 12:04:31', '2026-01-10 12:04:31'),
(210, 'App\\Models\\User', 86, 'auth_token', '0e0467ffba1f2fe5a62c008c705d7abfba78e1bf815ff98f1d04adf3a5648c52', '[\"*\"]', '2026-01-10 12:05:11', NULL, '2026-01-10 12:04:59', '2026-01-10 12:05:11'),
(211, 'App\\Models\\User', 90, 'auth_token', 'f939b08841917d964294f19a5d17ac1d88d8fdde0957a978b6ef39a7d557ad1a', '[\"*\"]', '2026-03-13 14:20:00', NULL, '2026-01-10 12:59:50', '2026-03-13 14:20:00'),
(212, 'App\\Models\\User', 92, 'auth_token', 'a34e31b1caa2da8e1741e6ddb03bebc501b916d1b7605dee7c16d0a834250a47', '[\"*\"]', '2026-01-12 19:53:18', NULL, '2026-01-12 18:30:42', '2026-01-12 19:53:18'),
(213, 'App\\Models\\User', 86, 'auth_token', '7bbb10199950d458f9bcd4e3067f9845ca0e597b643e3c9e04bd79457fae107f', '[\"*\"]', '2026-01-13 19:14:05', NULL, '2026-01-12 18:34:37', '2026-01-13 19:14:05'),
(214, 'App\\Models\\User', 86, 'auth_token', '96cb1169a29e76fa2c0d0b7bc1dede47e1115d2bcc16c9e358fd2c0f781aafd3', '[\"*\"]', '2026-01-13 00:42:44', NULL, '2026-01-12 22:46:03', '2026-01-13 00:42:44'),
(215, 'App\\Models\\User', 86, 'auth_token', '20b9e3678be3cddeffc1ad1814dcaedcaebb7c1f1a7eaccbf103940925de3ec8', '[\"*\"]', '2026-01-12 23:45:33', NULL, '2026-01-12 23:41:18', '2026-01-12 23:45:33'),
(216, 'App\\Models\\User', 91, 'auth_token', 'd0c7cfcf37c9d150a288a17a82d8fe8ea3d4c89202ec7bf2aa484a7c5c8f4d26', '[\"*\"]', '2026-01-13 19:52:17', NULL, '2026-01-13 00:00:15', '2026-01-13 19:52:17'),
(217, 'App\\Models\\User', 90, 'auth_token', '5eecc5c886c276b71d0d081723ea78a1f3a20d320ef2eb3e8d7cd07bd5094545', '[\"*\"]', '2026-01-13 16:25:37', NULL, '2026-01-13 11:49:36', '2026-01-13 16:25:37'),
(218, 'App\\Models\\User', 88, 'auth_token', '1ac92e1728cacdadd0f55781584bc75dc0e0a156913932e8e68f02077c4b0000', '[\"*\"]', '2026-01-13 13:22:54', NULL, '2026-01-13 12:38:32', '2026-01-13 13:22:54'),
(219, 'App\\Models\\User', 92, 'auth_token', 'abb3d892d086073e9d18779b121b101b88c5c860563c684814d43df9ff82ffde', '[\"*\"]', '2026-01-13 19:05:35', NULL, '2026-01-13 15:12:14', '2026-01-13 19:05:35'),
(220, 'App\\Models\\User', 90, 'auth_token', '4f7dfaf27864ff62abf7ee5a7c952ff00ff193ec1a59890b42c5af3d4a221349', '[\"*\"]', '2026-01-13 18:42:52', NULL, '2026-01-13 18:42:00', '2026-01-13 18:42:52'),
(221, 'App\\Models\\User', 92, 'auth_token', 'd486453287f101a5df65e6e182368576e9895b49c0de7de872326b8028cec0a7', '[\"*\"]', '2026-01-13 19:32:52', NULL, '2026-01-13 18:44:59', '2026-01-13 19:32:52'),
(222, 'App\\Models\\User', 90, 'auth_token', '22efc8867a6f71c5d4680da881286c17bc20244b71004e46d0fe5c68501c751b', '[\"*\"]', '2026-01-13 18:47:23', NULL, '2026-01-13 18:47:09', '2026-01-13 18:47:23'),
(223, 'App\\Models\\User', 92, 'auth_token', '50a9ea4bb3967e534341bbe60a05394d72b8d30ff03d9509124c9665eefb0235', '[\"*\"]', '2026-01-13 19:36:20', NULL, '2026-01-13 19:14:52', '2026-01-13 19:36:20'),
(224, 'App\\Models\\User', 92, 'auth_token', 'ab7bbb1983e99614ea344f2145ab88dd915e308b6eee435c71a16ebd31ccc903', '[\"*\"]', '2026-01-14 08:08:59', NULL, '2026-01-13 21:00:48', '2026-01-14 08:08:59'),
(225, 'App\\Models\\User', 92, 'auth_token', '1b4cdab6086291a4d6454e60425b45d261d7c3dcf8aeb9b7649bf2a0e82886f9', '[\"*\"]', '2026-01-14 08:12:47', NULL, '2026-01-14 08:09:48', '2026-01-14 08:12:47'),
(226, 'App\\Models\\User', 92, 'auth_token', '84b0c8898aab173a97a176c370d2c581b8af237194e8f217ec6eea7401532356', '[\"*\"]', '2026-01-16 14:45:56', NULL, '2026-01-14 12:01:17', '2026-01-16 14:45:56'),
(227, 'App\\Models\\User', 92, 'auth_token', 'a2cc12db2c11a1d0c84607c0ff25df36b127eb829aa64dc1d314d29d3166bac6', '[\"*\"]', '2026-01-29 18:27:00', NULL, '2026-01-14 14:09:41', '2026-01-29 18:27:00'),
(228, 'App\\Models\\User', 88, 'auth_token', '238fe6038b78cdf7dbc3d30e973ebc0ee7548fe4b753d3632ad1d60cc021ea72', '[\"*\"]', '2026-01-15 16:05:46', NULL, '2026-01-15 16:03:04', '2026-01-15 16:05:46'),
(229, 'App\\Models\\User', 88, 'auth_token', 'df80b9f2f7c3c8c72a99e83cdd54f0b9da94cc1b977bb11b3ff3d9360b670800', '[\"*\"]', '2026-01-15 19:01:08', NULL, '2026-01-15 19:00:23', '2026-01-15 19:01:08'),
(230, 'App\\Models\\User', 92, 'auth_token', '1a567b8299e05268bec2de564771fc5f657191dfcfd4b9f6dc81509c19a01386', '[\"*\"]', '2026-01-16 15:13:09', NULL, '2026-01-16 15:07:13', '2026-01-16 15:13:09'),
(231, 'App\\Models\\User', 92, 'auth_token', 'fff4125be0318a8be663df2051fab376a2f9d94199c7b87294335d703b71f55d', '[\"*\"]', '2026-01-16 15:13:45', NULL, '2026-01-16 15:13:44', '2026-01-16 15:13:45'),
(232, 'App\\Models\\User', 92, 'auth_token', 'e4d47525c235d1a72110fecb9f360da246b9adfb4988f646d92395104b5dedf8', '[\"*\"]', '2026-01-16 15:59:31', NULL, '2026-01-16 15:14:46', '2026-01-16 15:59:31'),
(233, 'App\\Models\\User', 92, 'auth_token', 'ab5e951c1f82903d638230ddf6894a91e3b54e4cd7d1c2e6e79175cba1e4c949', '[\"*\"]', '2026-01-16 17:32:15', NULL, '2026-01-16 17:28:19', '2026-01-16 17:32:15'),
(234, 'App\\Models\\User', 100, 'auth_token', '3e2dd1a542424c3d062ad19813668f1cb2ea8117ccbb3aba6fa94cdaa9321675', '[\"*\"]', '2026-01-19 19:02:42', NULL, '2026-01-16 17:33:37', '2026-01-19 19:02:42'),
(235, 'App\\Models\\User', 92, 'auth_token', '335696b6d8c19951ca7dae385a052d04f1f153436ffaaf20d65715a43e22e0a8', '[\"*\"]', '2026-01-19 19:05:02', NULL, '2026-01-19 19:03:03', '2026-01-19 19:05:02'),
(236, 'App\\Models\\User', 92, 'auth_token', '6270d84213a16c0efdf32d039f636c3be80d91063f373650b8651745f41bdaf7', '[\"*\"]', '2026-01-19 19:10:35', NULL, '2026-01-19 19:08:18', '2026-01-19 19:10:35'),
(237, 'App\\Models\\User', 101, 'auth_token', '9573ecb9ff96a1ca8354c1a6277a143a77731ebdb0c41a2932ae4e93b1fd2fdf', '[\"*\"]', '2026-01-29 17:48:01', NULL, '2026-01-19 19:11:25', '2026-01-29 17:48:01'),
(238, 'App\\Models\\User', 91, 'auth_token', 'df19dfce29480f90fd237d82fdf9e17dc118a0c4463090ad8dad8146bcaae98e', '[\"*\"]', '2026-01-29 18:22:21', NULL, '2026-01-29 18:07:39', '2026-01-29 18:22:21'),
(239, 'App\\Models\\User', 91, 'auth_token', 'c5bad88d2d5e9df2aa3a68b8487e0a0d5c8699e64e4a18aaaae6fc15c0d0593e', '[\"*\"]', '2026-01-29 18:41:58', NULL, '2026-01-29 18:34:23', '2026-01-29 18:41:58'),
(240, 'App\\Models\\User', 91, 'auth_token', '901feed7dd4927cd2bd9918d54cd92180e12597a13d988b3c3534012473f09e4', '[\"*\"]', '2026-02-16 14:09:25', NULL, '2026-01-29 18:42:34', '2026-02-16 14:09:25'),
(241, 'App\\Models\\User', 102, 'auth_token', '67885c14ed5c7282e8df2cd72d25b761482125acd309ebd2cf1dd510103585e4', '[\"*\"]', '2026-03-10 16:07:58', NULL, '2026-01-29 18:46:04', '2026-03-10 16:07:58'),
(242, 'App\\Models\\User', 90, 'auth_token', '2d0709c561970244310b05090e1caa3f8fd3f9d6ccfad316ed6baf8a6ba5c000', '[\"*\"]', '2026-02-12 18:07:20', NULL, '2026-02-12 14:49:31', '2026-02-12 18:07:20'),
(243, 'App\\Models\\User', 86, 'auth_token', '40ce0517b5fbb387dc6ac849f067329fec874017f2fbb565f3477a0e85a60b44', '[\"*\"]', '2026-02-12 18:17:52', NULL, '2026-02-12 15:24:36', '2026-02-12 18:17:52'),
(244, 'App\\Models\\User', 90, 'auth_token', '83074c66e71219f629852d162acd4f1232e55dc794e0a25877b06ce43955567b', '[\"*\"]', '2026-02-12 17:51:34', NULL, '2026-02-12 16:36:22', '2026-02-12 17:51:34'),
(245, 'App\\Models\\User', 86, 'auth_token', '8a6e9c10862796e33bd7dfd1289eae4bbcedf175933b875e9c6585f3d4873a6a', '[\"*\"]', '2026-02-27 14:23:45', NULL, '2026-02-27 14:10:29', '2026-02-27 14:23:45'),
(246, 'App\\Models\\User', 96, 'auth_token', 'df890547af9d490baaf82f441a8e1841f7a1098e0a529988dc550512f7901a85', '[\"*\"]', '2026-03-11 12:34:42', NULL, '2026-03-10 12:26:29', '2026-03-11 12:34:42'),
(247, 'App\\Models\\User', 96, 'auth_token', '0a93ae454694585965a7bc8f5aa55ffafacfe9d70f67f58c488a71d4e387cc4d', '[\"*\"]', '2026-03-11 12:33:00', NULL, '2026-03-10 14:17:38', '2026-03-11 12:33:00'),
(248, 'App\\Models\\User', 96, 'auth_token', 'e32d90e1aa54a4ec997a0044dee8ecacd486f00433da734d7f4a2c02664c75a2', '[\"*\"]', '2026-03-13 11:55:31', NULL, '2026-03-10 15:18:54', '2026-03-13 11:55:31'),
(249, 'App\\Models\\User', 102, 'auth_token', 'd9d7029b85ef5b2b87b5621e8c870964a0ba273328b77b1c86267ef836edb805', '[\"*\"]', '2026-04-06 17:05:30', NULL, '2026-03-10 21:12:28', '2026-04-06 17:05:30'),
(250, 'App\\Models\\User', 96, 'auth_token', '7c753738f2af41715dc9164b44ccbaa864ea61b40dd4ecf9d442437178ed8314', '[\"*\"]', '2026-03-11 12:50:46', NULL, '2026-03-11 12:36:00', '2026-03-11 12:50:46'),
(251, 'App\\Models\\User', 96, 'auth_token', 'b8f588d26977fab07e1280ac2b0b017c4050b71442e67df2bfc1df0d4a8a8d56', '[\"*\"]', '2026-03-13 14:46:06', NULL, '2026-03-13 14:46:05', '2026-03-13 14:46:06'),
(252, 'App\\Models\\User', 96, 'auth_token', '865c5ed0b2d619dc887f3175ccdf9ead644c7778234f1a9a65ac5fa8dc011491', '[\"*\"]', '2026-03-13 14:47:39', NULL, '2026-03-13 14:47:38', '2026-03-13 14:47:39'),
(253, 'App\\Models\\User', 96, 'auth_token', '9d3300e1678108c007459c1ce9b7e01f298535e1883d63749fe597c8cb049cb3', '[\"*\"]', '2026-03-13 16:42:55', NULL, '2026-03-13 14:48:33', '2026-03-13 16:42:55'),
(254, 'App\\Models\\User', 96, 'auth_token', '1ce327a658f32f4e4e9092ad48f35e65c7c0532667e14d6d9d2ea7318222a0b0', '[\"*\"]', '2026-03-13 15:26:22', NULL, '2026-03-13 15:25:08', '2026-03-13 15:26:22'),
(255, 'App\\Models\\User', 96, 'auth_token', '77923a32b31baa3a6ea3dabf44565574f558c19b5a9ab95d7f5dcbc3954396bd', '[\"*\"]', '2026-03-20 10:39:41', NULL, '2026-03-13 15:28:34', '2026-03-20 10:39:41'),
(256, 'App\\Models\\User', 96, 'auth_token', '7e6e7de8a79b61cdd00e3d69640a83517cc3c6c59382639a877e34b7dc46dc94', '[\"*\"]', '2026-03-17 15:34:16', NULL, '2026-03-13 16:43:57', '2026-03-17 15:34:16'),
(257, 'App\\Models\\User', 96, 'auth_token', '36b3a4e1cd1ff6ad55971701d1691009125824beef80327e3f13906f173224a8', '[\"*\"]', '2026-03-16 19:17:48', NULL, '2026-03-16 19:16:27', '2026-03-16 19:17:48'),
(258, 'App\\Models\\User', 91, 'auth_token', '58d76de59436c895e01174c23bfc52b7d42466fa85115c46ef9ad58e13953131', '[\"*\"]', '2026-03-19 13:11:35', NULL, '2026-03-17 16:13:08', '2026-03-19 13:11:35'),
(259, 'App\\Models\\User', 96, 'auth_token', 'f86bb724d7caaa4beb8764487fd396449ff03c0151752f50161ae0745ef1d8fd', '[\"*\"]', '2026-03-18 11:18:47', NULL, '2026-03-17 16:21:10', '2026-03-18 11:18:47'),
(260, 'App\\Models\\User', 96, 'auth_token', '65f15ebc5f9e3456f12c53001b1a46ed42c95481d4fb807f46ea14fe59bcd859', '[\"*\"]', '2026-03-20 10:46:08', NULL, '2026-03-18 11:20:23', '2026-03-20 10:46:08'),
(261, 'App\\Models\\User', 96, 'auth_token', 'f8431b2ee0080758a1eb9e4b3a114cd37d07f3593d6dc949e0657a2430258cf3', '[\"*\"]', '2026-03-19 15:19:08', NULL, '2026-03-19 13:13:34', '2026-03-19 15:19:08'),
(262, 'App\\Models\\User', 107, 'auth_token', '495f6f6557852978957066bbcbda3207d99a9d7b865b028dba48dc05c1441fb7', '[\"*\"]', '2026-03-19 15:58:34', NULL, '2026-03-19 15:53:00', '2026-03-19 15:58:34'),
(263, 'App\\Models\\User', 86, 'auth_token', '6020bb0213753eb882ddae3b4c093ba60b1135f9c61c0a3ac25f88b0b257cd45', '[\"*\"]', NULL, NULL, '2026-03-19 16:31:07', '2026-03-19 16:31:07');
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(264, 'App\\Models\\User', 90, 'auth_token', 'a0277e52688eb33d7095474d2351a1ff2b458f5e833514385e205de7c4ed9143', '[\"*\"]', '2026-03-19 16:37:47', NULL, '2026-03-19 16:36:42', '2026-03-19 16:37:47'),
(265, 'App\\Models\\User', 90, 'auth_token', 'c8692a0eb2ac1a5d42308ecc42008df3aaebfddf0a8f2d48cbb188df924835de', '[\"*\"]', '2026-03-19 16:38:40', NULL, '2026-03-19 16:37:57', '2026-03-19 16:38:40'),
(266, 'App\\Models\\User', 107, 'auth_token', '9c8efe8df9b8dc95fff85d558a6f14b5dc29688b72b72a1f3954267203d8b8d1', '[\"*\"]', '2026-03-19 17:21:39', NULL, '2026-03-19 17:11:41', '2026-03-19 17:21:39'),
(267, 'App\\Models\\User', 107, 'auth_token', '677a47d01a5edd8c08a6aa19a5072dcc7e0ca881583440678b04309118312368', '[\"*\"]', '2026-03-19 18:18:21', NULL, '2026-03-19 17:24:16', '2026-03-19 18:18:21'),
(268, 'App\\Models\\User', 107, 'auth_token', '425bd713e08ce328d47b3357992758482e40cdee713b68a98a199337bea4d6d1', '[\"*\"]', '2026-04-16 09:44:13', NULL, '2026-03-19 17:24:31', '2026-04-16 09:44:13'),
(269, 'App\\Models\\User', 107, 'auth_token', '2115ed60e9a88bb6c285889f79752d278f2e6237a1d1146a63e87f8606b7c8c1', '[\"*\"]', '2026-03-20 10:53:43', NULL, '2026-03-19 18:18:49', '2026-03-20 10:53:43'),
(270, 'App\\Models\\User', 96, 'auth_token', 'a8de967b81261efd0334d46d3dab0ab6cf559091d4bf986b37d90cc2ab9f4f8e', '[\"*\"]', '2026-03-20 16:47:19', NULL, '2026-03-20 11:12:04', '2026-03-20 16:47:19'),
(271, 'App\\Models\\User', 107, 'auth_token', 'cac3b82a4353f49380ffc9f50604f1953a89f2cd5f363154b0b4650b022251b4', '[\"*\"]', '2026-03-20 18:14:23', NULL, '2026-03-20 11:13:38', '2026-03-20 18:14:23'),
(272, 'App\\Models\\User', 96, 'auth_token', '76dfb4e4ac7c785f269ddb2a4595d73a7f81d7d37c4e8f3b56714dcdad51deb5', '[\"*\"]', '2026-03-20 16:01:29', NULL, '2026-03-20 11:26:22', '2026-03-20 16:01:29'),
(273, 'App\\Models\\User', 86, 'auth_token', 'be5123a8dec98ef3be3d5f9739dba57b5df090636955efc2c8aed80e1879ff24', '[\"*\"]', '2026-03-26 10:39:08', NULL, '2026-03-20 15:54:16', '2026-03-26 10:39:08'),
(274, 'App\\Models\\User', 86, 'auth_token', 'e0f051690fd14c3d821b7605cd6a2428291f7060e5b5e8bec0a9d5213e592419', '[\"*\"]', '2026-03-20 16:45:43', NULL, '2026-03-20 16:03:41', '2026-03-20 16:45:43'),
(275, 'App\\Models\\User', 96, 'auth_token', '577865642e8573d26e99af6eb801acc7570b35cdbcbf838666cc3d32f52f27e5', '[\"*\"]', '2026-03-20 16:49:12', NULL, '2026-03-20 16:48:03', '2026-03-20 16:49:12'),
(276, 'App\\Models\\User', 96, 'auth_token', '16adf05b05df1ba4f4c9dc348f9eb8d24bde23beeb13d7fd50b7799853f957dc', '[\"*\"]', '2026-03-20 18:07:23', NULL, '2026-03-20 16:48:31', '2026-03-20 18:07:23'),
(277, 'App\\Models\\User', 86, 'auth_token', '33687df3b35515afdef977347ec76d274504a6debe3bc634dc86be469cd46424', '[\"*\"]', '2026-03-20 18:09:06', NULL, '2026-03-20 18:08:41', '2026-03-20 18:09:06'),
(278, 'App\\Models\\User', 86, 'auth_token', '01d1b1f360c57a58fa799f5a78112b7c111acdaa63d7571be8d2ceb855980a62', '[\"*\"]', NULL, NULL, '2026-03-20 18:13:40', '2026-03-20 18:13:40'),
(279, 'App\\Models\\User', 86, 'auth_token', '169142772652e441468e4b359782cc1436642e97a6e92ddb4281294d5d99b500', '[\"*\"]', '2026-03-24 19:30:09', NULL, '2026-03-20 18:14:37', '2026-03-24 19:30:09'),
(280, 'App\\Models\\User', 86, 'auth_token', '9a92a20b2347d9c690ff55c69117c7a77684d78e3fd2fd651c2ce657b03a9367', '[\"*\"]', '2026-03-20 18:31:39', NULL, '2026-03-20 18:19:04', '2026-03-20 18:31:39'),
(281, 'App\\Models\\User', 86, 'auth_token', '04bbeddb56033488a89efb8a480762730111271d71c358b4160a9789d2f94a0a', '[\"*\"]', '2026-03-23 11:13:34', NULL, '2026-03-20 18:20:48', '2026-03-23 11:13:34'),
(282, 'App\\Models\\User', 86, 'auth_token', '68e76c3404df49e8a4183d48b3827a4df20cd4ab1d0642acc1dae19d1c2955d0', '[\"*\"]', '2026-03-24 11:41:03', NULL, '2026-03-20 18:32:09', '2026-03-24 11:41:03'),
(283, 'App\\Models\\User', 108, 'auth_token', '0b7675bb79b5c46c43d5b692fd199d3c526d0955e63485f68d94496a38673b1d', '[\"*\"]', '2026-03-23 11:11:40', NULL, '2026-03-23 11:10:26', '2026-03-23 11:11:40'),
(284, 'App\\Models\\User', 96, 'auth_token', '65ded6f34ede8a284ff582d88a0ad0730bee8aa4a41f213e1b86b6f9e874915f', '[\"*\"]', '2026-03-23 14:01:21', NULL, '2026-03-23 11:15:05', '2026-03-23 14:01:21'),
(285, 'App\\Models\\User', 88, 'auth_token', '175cfe199d989bd79e6d0ee12562bb01ac615dae7414bb1de1c3b066d6ce12b7', '[\"*\"]', '2026-03-23 11:17:53', NULL, '2026-03-23 11:17:27', '2026-03-23 11:17:53'),
(286, 'App\\Models\\User', 96, 'auth_token', '03c5c8a93c7eaec29ed3da8d1706e6b54704ec50060dcca187f1385926766c5a', '[\"*\"]', '2026-03-23 13:38:51', NULL, '2026-03-23 11:20:00', '2026-03-23 13:38:51'),
(287, 'App\\Models\\User', 86, 'auth_token', 'bdae704593da44524a118f71bc1c97f905af0cd23036a67a52ca1070feb717eb', '[\"*\"]', '2026-03-24 11:02:05', NULL, '2026-03-23 14:02:16', '2026-03-24 11:02:05'),
(288, 'App\\Models\\User', 107, 'auth_token', '6fcd964f3c1ba23d30a8890166e1dd57e46df32381b6a53598d71ee8da86968d', '[\"*\"]', '2026-03-23 14:17:52', NULL, '2026-03-23 14:09:10', '2026-03-23 14:17:52'),
(289, 'App\\Models\\User', 92, 'auth_token', 'aeafba1dcbb7bc9a8ed08484e72f40cb764825a4e4892801244056466b300285', '[\"*\"]', '2026-03-23 14:23:58', NULL, '2026-03-23 14:20:07', '2026-03-23 14:23:58'),
(290, 'App\\Models\\User', 107, 'auth_token', '336bd75f8e16f8fb6be85973476f565777c115c3b95edfc4787de62a5cbfd18b', '[\"*\"]', '2026-03-24 10:59:45', NULL, '2026-03-23 14:40:47', '2026-03-24 10:59:45'),
(291, 'App\\Models\\User', 92, 'auth_token', 'c1117ca140f66e1e88083ab1d5c17e4d3e7b57ae311a8d824ef8286c2dc97fd3', '[\"*\"]', '2026-03-24 11:25:30', NULL, '2026-03-23 14:49:18', '2026-03-24 11:25:30'),
(292, 'App\\Models\\User', 86, 'auth_token', '53e59f7ef8f0d79f3989f0a1257e25d632c485e05d7584ecbd2e64379f61bc33', '[\"*\"]', '2026-03-25 12:09:06', NULL, '2026-03-24 10:54:06', '2026-03-25 12:09:06'),
(293, 'App\\Models\\User', 107, 'auth_token', 'f4e5638624259961a4c821e37037dde772f03104de4dfca0ae7a6c25ad4aca77', '[\"*\"]', '2026-03-25 11:07:01', NULL, '2026-03-24 11:02:43', '2026-03-25 11:07:01'),
(294, 'App\\Models\\User', 92, 'auth_token', '88cd09861645178dcea3421ed1d54321a9be8400c404b05d12bdf4a8013a34a3', '[\"*\"]', '2026-03-24 11:31:49', NULL, '2026-03-24 11:27:23', '2026-03-24 11:31:49'),
(295, 'App\\Models\\User', 86, 'auth_token', 'a5efad41b137219c05fa222c5809636820e6aac3871b4bacca408c4beaaf1354', '[\"*\"]', '2026-03-24 17:32:04', NULL, '2026-03-24 11:41:29', '2026-03-24 17:32:04'),
(296, 'App\\Models\\User', 86, 'auth_token', '1495a9c509914f8885648008935b1fd2357e4c80f58f85c26634b7f2e05faa53', '[\"*\"]', '2026-03-24 18:11:23', NULL, '2026-03-24 17:32:46', '2026-03-24 18:11:23'),
(297, 'App\\Models\\User', 86, 'auth_token', '3efff3d4f965d8b6c4cc9ffd58dc418eb60943e9090b947d18d426875861428f', '[\"*\"]', '2026-03-24 18:52:45', NULL, '2026-03-24 18:13:48', '2026-03-24 18:52:45'),
(298, 'App\\Models\\User', 96, 'auth_token', 'cb66844f638fec16a61b43c74ef66c320ae4dcf16e38186ebc4ff1a6d4ca8a8f', '[\"*\"]', '2026-03-24 18:53:19', NULL, '2026-03-24 18:53:10', '2026-03-24 18:53:19'),
(299, 'App\\Models\\User', 86, 'auth_token', 'eb78953b51eea5b5e13aa25d7aca5b35a06ffcce74a888d608ce17b84a15013f', '[\"*\"]', '2026-03-25 12:03:36', NULL, '2026-03-24 18:53:51', '2026-03-25 12:03:36'),
(300, 'App\\Models\\User', 86, 'auth_token', '7aee787c5864548cd09383753602b380cb51977140c8344ef4c351a3d94a40bc', '[\"*\"]', '2026-03-24 19:32:04', NULL, '2026-03-24 19:30:29', '2026-03-24 19:32:04'),
(301, 'App\\Models\\User', 86, 'auth_token', '73f361f6412e92a96ae18c0126228c8b617f9234dafaa1833a980505d660937f', '[\"*\"]', '2026-03-26 10:31:21', NULL, '2026-03-24 19:33:10', '2026-03-26 10:31:21'),
(302, 'App\\Models\\User', 92, 'auth_token', 'f504da911133ceb339ede04f75bfef4f2efc215eb9853b06144f7afefd411eb1', '[\"*\"]', '2026-03-25 11:06:02', NULL, '2026-03-25 10:43:25', '2026-03-25 11:06:02'),
(303, 'App\\Models\\User', 92, 'auth_token', 'ae1f239356cb1d7981eddcf3703530d9227517080d0d97ae1ea0d294dfe5286d', '[\"*\"]', '2026-03-25 12:19:58', NULL, '2026-03-25 12:06:00', '2026-03-25 12:19:58'),
(304, 'App\\Models\\User', 110, 'auth_token', '0109e2e854b9fde1eeddaa80ab258c52e1fb67d3e3cd2ce7723b6dd3c4eba918', '[\"*\"]', '2026-04-22 12:33:49', NULL, '2026-03-25 12:21:28', '2026-04-22 12:33:49'),
(305, 'App\\Models\\User', 107, 'auth_token', '44aa036e0f13b23a69aced47284ddc290628f7a81ac5dd56b05f1ea90a3ce244', '[\"*\"]', '2026-04-22 11:58:23', NULL, '2026-03-26 10:33:16', '2026-04-22 11:58:23'),
(306, 'App\\Models\\User', 107, 'auth_token', '8899b8cf72ca758c9dad94ba2f355862a04424b432fdcef4e1ff5eb315897325', '[\"*\"]', '2026-03-27 17:12:55', NULL, '2026-03-27 16:22:11', '2026-03-27 17:12:55'),
(307, 'App\\Models\\User', 86, 'auth_token', '24def4fd526a2d6f1bf813aed60ce756a8daba1dc7800af5f799b94e5af23e0a', '[\"*\"]', '2026-03-27 17:37:51', NULL, '2026-03-27 17:15:37', '2026-03-27 17:37:51'),
(308, 'App\\Models\\User', 86, 'auth_token', '6b4954b95ee02e52cb5008852ff171ca3047b07c32cc44846d4436a003bf9d99', '[\"*\"]', '2026-04-22 12:27:14', NULL, '2026-04-01 17:50:10', '2026-04-22 12:27:14'),
(309, 'App\\Models\\User', 86, 'auth_token', '9fa870dc09a89b4377fe93777147b4b3f6d8a7d8c9a277512d9b582e9d7b3096', '[\"*\"]', '2026-04-22 12:34:56', NULL, '2026-04-22 12:34:55', '2026-04-22 12:34:56'),
(310, 'App\\Models\\User', 107, 'auth_token', 'b0807aa3f7270ee42dfb1842eabe72ab3638256b964a832f4ca0478b9a2e65ea', '[\"*\"]', '2026-04-22 14:33:43', NULL, '2026-04-22 13:36:17', '2026-04-22 14:33:43'),
(311, 'App\\Models\\User', 107, 'auth_token', '88b9e263de873cc631a3a75316c9576c88b2de492a01d937b1ae24e659ebba9a', '[\"*\"]', '2026-04-22 14:35:36', NULL, '2026-04-22 14:35:36', '2026-04-22 14:35:36'),
(312, 'App\\Models\\User', 86, 'auth_token', 'af0646a4b292c8753e393d195378d4c50b2c28c4f120708b3224f12512df1aac', '[\"*\"]', '2026-04-22 14:37:15', NULL, '2026-04-22 14:37:14', '2026-04-22 14:37:15'),
(313, 'App\\Models\\User', 86, 'auth_token', '003c8c5d7565a059686a752b191eec56ee6aab38e1fad44661aa1bb08ef94f16', '[\"*\"]', '2026-04-22 15:18:58', NULL, '2026-04-22 14:37:41', '2026-04-22 15:18:58'),
(314, 'App\\Models\\User', 107, 'auth_token', '942798e690537a08e1d896b56975f13d8de6c87941b192e08fb9d82b402d7d1d', '[\"*\"]', '2026-04-22 15:20:38', NULL, '2026-04-22 15:20:38', '2026-04-22 15:20:38'),
(315, 'App\\Models\\User', 88, 'auth_token', 'aac54bb6fba2b2354a02b04b1d99444be8b38b83251cfbcb311d0d2b8e8b6c82', '[\"*\"]', '2026-04-22 15:27:13', NULL, '2026-04-22 15:24:09', '2026-04-22 15:27:13'),
(316, 'App\\Models\\User', 107, 'auth_token', '0d79cbfb154bd6b4232115aa7e832bb834090b59933d8e17d3834591a5d962a2', '[\"*\"]', '2026-04-22 15:28:27', NULL, '2026-04-22 15:28:02', '2026-04-22 15:28:27'),
(317, 'App\\Models\\User', 88, 'auth_token', 'c8bd85df08b1d38c3b79b89458907ba23f32013ef5f0cc215a2feb6de01356b1', '[\"*\"]', '2026-04-22 15:29:11', NULL, '2026-04-22 15:28:59', '2026-04-22 15:29:11'),
(318, 'App\\Models\\User', 107, 'auth_token', '762295a58172fcca1dbe10cc6b43aabbed779233998730ff210af21b3c32e498', '[\"*\"]', '2026-04-22 15:36:51', NULL, '2026-04-22 15:31:36', '2026-04-22 15:36:51'),
(319, 'App\\Models\\User', 107, 'auth_token', '5da67b799e5b6baf95e070546959002a73571b0f920bf9de6067414140e5b481', '[\"*\"]', '2026-04-22 18:00:41', NULL, '2026-04-22 15:37:21', '2026-04-22 18:00:41'),
(320, 'App\\Models\\User', 86, 'auth_token', '6edd84e1f33cf89e8332ffeb26b1451b36a9a0459406744248778c94f32fa028', '[\"*\"]', '2026-04-22 17:27:28', NULL, '2026-04-22 17:27:28', '2026-04-22 17:27:28'),
(321, 'App\\Models\\User', 86, 'auth_token', '826242171ce5c8b0fed6886382038f7f3c731f1148dc3c0230439b349d3ae0ce', '[\"*\"]', '2026-04-22 17:51:13', NULL, '2026-04-22 17:31:31', '2026-04-22 17:51:13'),
(322, 'App\\Models\\User', 86, 'auth_token', '09d5652328541123d63971f8dcb9382f7f132ae9548c53484e00706b59439fac', '[\"*\"]', '2026-04-22 17:59:42', NULL, '2026-04-22 17:53:54', '2026-04-22 17:59:42'),
(323, 'App\\Models\\User', 86, 'auth_token', '92f0daa8b5a0604dfc9757878a53a5fd1e9cf0ebcac5304dd2f80b3b611f6081', '[\"*\"]', '2026-04-22 17:59:21', NULL, '2026-04-22 17:54:50', '2026-04-22 17:59:21'),
(324, 'App\\Models\\User', 86, 'auth_token', '590fd6f8ea7757070549da3458578da4791c673eca5522cf7b884b5595ccedae', '[\"*\"]', '2026-04-22 18:01:25', NULL, '2026-04-22 18:00:31', '2026-04-22 18:01:25'),
(325, 'App\\Models\\User', 86, 'auth_token', 'ac04692c8a487273ccb06338e9f082ff4461687ff58e0b3242a69af6079a4b2e', '[\"*\"]', '2026-04-22 18:09:08', NULL, '2026-04-22 18:03:27', '2026-04-22 18:09:08'),
(326, 'App\\Models\\User', 86, 'auth_token', '55e0ac825e2533320e4edddb95c47f1193656192ca8fca2f2529cc529bda2ece', '[\"*\"]', '2026-04-23 12:39:45', NULL, '2026-04-22 18:04:01', '2026-04-23 12:39:45'),
(327, 'App\\Models\\User', 86, 'auth_token', '72d3eef789fc313189a8668ba142dd56d81b836dce3bb7bf8f7854f27ec2d6ec', '[\"*\"]', '2026-04-22 18:05:22', NULL, '2026-04-22 18:05:22', '2026-04-22 18:05:22'),
(328, 'App\\Models\\User', 86, 'auth_token', 'dbef67a04684db67aadf6465bbd8c3aa9ed2d14980e835195d96d562bc7ed2f0', '[\"*\"]', '2026-04-22 18:30:33', NULL, '2026-04-22 18:24:31', '2026-04-22 18:30:33'),
(329, 'App\\Models\\User', 86, 'auth_token', 'e9c4a4d4f1a8f1060f38e94cb334a4f9cb2fd4cd16ee5ab4128228a1461c3e88', '[\"*\"]', '2026-04-22 18:26:35', NULL, '2026-04-22 18:26:35', '2026-04-22 18:26:35'),
(330, 'App\\Models\\User', 86, 'auth_token', '1df7fb28099dbb3cdae2c8fb15b48d20b56314cbb971beda867d82e84719cfba', '[\"*\"]', '2026-04-23 12:42:21', NULL, '2026-04-22 18:37:45', '2026-04-23 12:42:21'),
(331, 'App\\Models\\User', 86, 'auth_token', 'e9b277fc32202430ba2ce01a124fe23c904799a537090a04c5ed8d9a634eb7c3', '[\"*\"]', '2026-04-22 22:26:10', NULL, '2026-04-22 22:25:34', '2026-04-22 22:26:10'),
(332, 'App\\Models\\User', 111, 'auth_token', '0e408939bdfa2fcdf8efda38da4da33306f905f33dc8f5a1a6b35311c6f8cbbd', '[\"*\"]', '2026-04-23 13:26:17', NULL, '2026-04-23 11:40:10', '2026-04-23 13:26:17'),
(333, 'App\\Models\\User', 111, 'auth_token', 'bcdb0de0d99eabf42c6c8601a06e6844e87e8ef9b9815905d14c8d7cca928f54', '[\"*\"]', '2026-04-23 14:56:01', NULL, '2026-04-23 11:59:52', '2026-04-23 14:56:01'),
(334, 'App\\Models\\User', 86, 'auth_token', '21f20a1c2f900be706d98e64b0119c1f227d944b8e41c60fcf0138a0bde9bd44', '[\"*\"]', '2026-04-23 13:11:23', NULL, '2026-04-23 13:01:51', '2026-04-23 13:11:23'),
(335, 'App\\Models\\User', 86, 'auth_token', '3734c496916bc06be8d9d94d87b92c6e124307ac433a712c34dedd3cb2591ee6', '[\"*\"]', '2026-04-23 13:15:11', NULL, '2026-04-23 13:15:10', '2026-04-23 13:15:11'),
(336, 'App\\Models\\User', 86, 'auth_token', 'd785eea7a5f0630c71cab85635f74cb1c9ee09f40ca7006312b5b6ff3d65e0a1', '[\"*\"]', '2026-05-13 16:10:45', NULL, '2026-04-23 13:16:47', '2026-05-13 16:10:45'),
(337, 'App\\Models\\User', 86, 'auth_token', 'ae7628df034a39652dab56717dd17940bde0793c562d676ff3f5919c3378765e', '[\"*\"]', '2026-04-23 13:45:12', NULL, '2026-04-23 13:19:49', '2026-04-23 13:45:12'),
(338, 'App\\Models\\User', 111, 'auth_token', '9c91122ea32d115d6f234152b494a5d77f61637c9bef7f182c59998c0ae293ae', '[\"*\"]', '2026-04-23 13:48:57', NULL, '2026-04-23 13:31:29', '2026-04-23 13:48:57'),
(339, 'App\\Models\\User', 86, 'auth_token', '411729200d52347ee112b8c4fdf10d104fe4d777aeb1a228d9363fd97636373b', '[\"*\"]', '2026-05-13 16:14:05', NULL, '2026-04-23 13:47:43', '2026-05-13 16:14:05'),
(340, 'App\\Models\\User', 110, 'auth_token', '176d0b30933345851cbd321b94d2c14a8c10d713a3fb8818b32882fbf87ad466', '[\"*\"]', '2026-04-23 14:00:52', NULL, '2026-04-23 13:56:15', '2026-04-23 14:00:52'),
(341, 'App\\Models\\User', 111, 'auth_token', 'eda40e4aa53d6e93d641b3c8d33968329999d5160d451143f1777c4c56b277c4', '[\"*\"]', '2026-04-23 14:13:27', NULL, '2026-04-23 14:01:28', '2026-04-23 14:13:27'),
(342, 'App\\Models\\User', 107, 'auth_token', '441a20d4351c7682b42b76cbbf9b68700324d769c5d62e4516b857fb0836c012', '[\"*\"]', '2026-04-23 14:16:05', NULL, '2026-04-23 14:14:30', '2026-04-23 14:16:05'),
(343, 'App\\Models\\User', 111, 'auth_token', 'f73fa4669232920cbc35adcb41ae48bfd57202ee4e0b540efdf201f875c1b94c', '[\"*\"]', '2026-04-23 14:17:17', NULL, '2026-04-23 14:17:12', '2026-04-23 14:17:17'),
(344, 'App\\Models\\User', 107, 'auth_token', '627147cdfb6d388575941173b6dc1979ace458de6b8817b31448ca40b73dad06', '[\"*\"]', '2026-04-23 14:34:10', NULL, '2026-04-23 14:33:41', '2026-04-23 14:34:10'),
(345, 'App\\Models\\User', 111, 'auth_token', '71815dcbec0701e537ee6f70bd5e8dd1bcac7cc3a8290981e23a407c3596c03e', '[\"*\"]', '2026-04-24 15:25:12', NULL, '2026-04-23 14:34:34', '2026-04-24 15:25:12'),
(346, 'App\\Models\\User', 102, 'auth_token', 'a628a53b8912e3a614457db912808f61552329959364cfed9f84a8088d18eb67', '[\"*\"]', '2026-04-23 19:54:49', NULL, '2026-04-23 19:50:52', '2026-04-23 19:54:49'),
(347, 'App\\Models\\User', 110, 'auth_token', 'fb7b06ed6016ffb983f4fe75b82f664a63e237ab1c72f503b9e79ea16fe76604', '[\"*\"]', '2026-05-13 16:47:44', NULL, '2026-04-24 14:47:32', '2026-05-13 16:47:44'),
(348, 'App\\Models\\User', 110, 'auth_token', '999197591cdd34049fa79629d4ea961feaaa21ac21d0f54b043e2ff697384a65', '[\"*\"]', '2026-04-24 16:26:39', NULL, '2026-04-24 15:25:58', '2026-04-24 16:26:39'),
(349, 'App\\Models\\User', 107, 'auth_token', 'dafe3aa4deed246ac440e31f39389b5ad0d03d6a46fce7428eb019408b4ca55e', '[\"*\"]', '2026-05-27 16:20:41', NULL, '2026-04-24 16:28:08', '2026-05-27 16:20:41'),
(350, 'App\\Models\\User', 110, 'auth_token', '519659749184dd45ce1c8b67fee88c76d2bd9cd68e2c5b62485d79c434135fbe', '[\"*\"]', '2026-06-11 12:08:44', NULL, '2026-05-13 16:12:15', '2026-06-11 12:08:44'),
(351, 'App\\Models\\User', 110, 'auth_token', '8c7817f93be6c23b686954bc6367d9ab8220bf872498717e899f740b3e78d13e', '[\"*\"]', '2026-05-13 16:18:31', NULL, '2026-05-13 16:14:24', '2026-05-13 16:18:31'),
(352, 'App\\Models\\User', 110, 'auth_token', 'e0b0a898b7a617feccc71d3ac0583e842659572b0577d7344d93e2183c9b5e45', '[\"*\"]', '2026-05-13 18:52:10', NULL, '2026-05-13 18:46:00', '2026-05-13 18:52:10'),
(353, 'App\\Models\\User', 110, 'auth_token', 'e96ebf3d94c8a1f8f85a34aba8e21a600814d4a833f0f7b27fa76421b081d277', '[\"*\"]', '2026-05-13 18:56:16', NULL, '2026-05-13 18:55:47', '2026-05-13 18:56:16'),
(354, 'App\\Models\\User', 102, 'auth_token', 'e202e1cda8314e344c98b599b112118e2bfa06b141e710cfd85bb366c98a6da4', '[\"*\"]', '2026-05-20 20:25:26', NULL, '2026-05-20 18:11:47', '2026-05-20 20:25:26'),
(355, 'App\\Models\\User', 86, 'auth_token', 'ebd001a2c7eb84efb7c9efadb4d6f777f31c57118659736f931f07f2febf9e1a', '[\"*\"]', '2026-05-27 19:26:50', NULL, '2026-05-27 19:25:11', '2026-05-27 19:26:50'),
(356, 'App\\Models\\User', 107, 'auth_token', 'eadb9d681f40786ac35942f3552bd6fdcb98c5489ad668895e75be7d70799c0b', '[\"*\"]', '2026-06-01 13:02:41', NULL, '2026-06-01 12:11:07', '2026-06-01 13:02:41'),
(357, 'App\\Models\\User', 107, 'auth_token', 'ce17d767d4e3106b4c6810aa41a6143c4d1702f67b52102bb2949bf9238918fa', '[\"*\"]', '2026-06-01 13:46:58', NULL, '2026-06-01 13:04:09', '2026-06-01 13:46:58'),
(358, 'App\\Models\\User', 110, 'auth_token', '7d4cd2805c8836ec9cc3cb6fffec22c47a31cf8b156735fa48d78946176136db', '[\"*\"]', NULL, NULL, '2026-06-01 13:44:00', '2026-06-01 13:44:00'),
(359, 'App\\Models\\User', 107, 'auth_token', '0a5f3153d6d7afd53029af8ca4f81e01f9c1043efcb49ba3e73d7563b4a51ed2', '[\"*\"]', '2026-06-08 17:57:09', NULL, '2026-06-01 13:47:29', '2026-06-08 17:57:09'),
(360, 'App\\Models\\User', 107, 'auth_token', '8e6e025fe290418d92f96faae648ad8d7800bc3733441d28b6ef6caa3d112513', '[\"*\"]', '2026-06-10 13:19:56', NULL, '2026-06-10 13:11:36', '2026-06-10 13:19:56'),
(361, 'App\\Models\\User', 88, 'auth_token', 'ff550aea68014aeb0651f6005908715628f1273c8b983d38fda58689fd559912', '[\"*\"]', '2026-06-10 13:32:21', NULL, '2026-06-10 13:21:36', '2026-06-10 13:32:21'),
(362, 'App\\Models\\User', 107, 'auth_token', '3a3e400969c34ecfd8af7587c6777f2769565b02104828596f871cd4f8642911', '[\"*\"]', '2026-06-10 13:34:11', NULL, '2026-06-10 13:32:57', '2026-06-10 13:34:11'),
(363, 'App\\Models\\User', 88, 'auth_token', '625cec22e0db662ed48d4da37f8340a4009adbecea35a3ea81a4bf236f1eef98', '[\"*\"]', '2026-06-11 11:14:36', NULL, '2026-06-10 13:34:52', '2026-06-11 11:14:36'),
(364, 'App\\Models\\User', 107, 'auth_token', '38290b32397c48aee48c67fb318f6c662dc8357fe900b219a09def408e4aae1f', '[\"*\"]', '2026-06-11 13:14:20', NULL, '2026-06-11 11:04:30', '2026-06-11 13:14:20'),
(365, 'App\\Models\\User', 107, 'auth_token', '192ed629a2b011b8a995dddb76090991810609da873ec1e685c0ca074dedb4d6', '[\"*\"]', '2026-06-11 11:15:52', NULL, '2026-06-11 11:15:07', '2026-06-11 11:15:52'),
(366, 'App\\Models\\User', 88, 'auth_token', 'f237f480a2e7877f4cd4bb38f66b67045b4912c45e944fd7b6795c410b300e86', '[\"*\"]', '2026-06-11 14:39:41', NULL, '2026-06-11 11:16:20', '2026-06-11 14:39:41'),
(367, 'App\\Models\\User', 88, 'auth_token', '081d5adf4adf300ae57097ba11af6d7f5ee5e99eec853747168174e62f3fd7a3', '[\"*\"]', '2026-06-11 14:37:53', NULL, '2026-06-11 11:19:39', '2026-06-11 14:37:53'),
(368, 'App\\Models\\User', 88, 'auth_token', '1217ab94e6240793ce8f0fd8ba647b16a5269fce62c7b8c0b7dddea3b574ef55', '[\"*\"]', NULL, NULL, '2026-06-11 11:51:52', '2026-06-11 11:51:52'),
(369, 'App\\Models\\User', 88, 'auth_token', '401b9eac2dae1415af9f912a639a1113501bea0b54c7671e6b43e58b3cce294a', '[\"*\"]', '2026-06-11 13:10:59', NULL, '2026-06-11 11:57:10', '2026-06-11 13:10:59'),
(370, 'App\\Models\\User', 107, 'auth_token', '85fd6de6c0b229626268caaf44307bf9623e047b8e57ea68db648cc1b1f83bd4', '[\"*\"]', '2026-06-11 14:38:07', NULL, '2026-06-11 13:13:19', '2026-06-11 14:38:07'),
(371, 'App\\Models\\User', 107, 'auth_token', '97a6eb0b064362a4ee3a62453e0d5b44cb1626800d1621fabb24378d83d44bef', '[\"*\"]', NULL, NULL, '2026-06-11 14:27:54', '2026-06-11 14:27:54'),
(372, 'App\\Models\\User', 88, 'auth_token', '48ce069eeb0925912519ef5cbae42d99cec32ddddbca3a934031b32bffd9862b', '[\"*\"]', '2026-06-11 14:41:43', NULL, '2026-06-11 14:41:30', '2026-06-11 14:41:43'),
(373, 'App\\Models\\User', 107, 'auth_token', '1d6e95b53e93d77a650a20aebc28ac9ec82559ae164aebf3238687e152b00d5a', '[\"*\"]', '2026-06-11 14:49:40', NULL, '2026-06-11 14:48:40', '2026-06-11 14:49:40'),
(374, 'App\\Models\\User', 112, 'auth_token', '50856de5c61267516e19d9e3bf1a6205cee723b3657d538c942019a8ba139cce', '[\"*\"]', '2026-06-11 14:56:44', NULL, '2026-06-11 14:56:08', '2026-06-11 14:56:44'),
(375, 'App\\Models\\User', 113, 'auth_token', '3baae2a4b663f53ad2c34d49e4e61c401586b00fc624c0e4ce4c3a357c47895b', '[\"*\"]', '2026-06-11 15:19:31', NULL, '2026-06-11 15:00:56', '2026-06-11 15:19:31'),
(376, 'App\\Models\\User', 112, 'auth_token', '38f93362e4261d88b222aff0727692371f0762d93ad7405b0b986ff2abf816dd', '[\"*\"]', '2026-06-11 15:34:45', NULL, '2026-06-11 15:21:05', '2026-06-11 15:34:45'),
(377, 'App\\Models\\User', 112, 'auth_token', '7168a7ac017943fc577db9c7639fde9a2b1c478d01419a90c2a577c7e7a4de0b', '[\"*\"]', '2026-06-11 15:35:37', NULL, '2026-06-11 15:35:31', '2026-06-11 15:35:37'),
(378, 'App\\Models\\User', 112, 'auth_token', '8f5405303561385024e2ed4dd0934f4cf39a0ba67054b08bd178c6aaf65e4a1c', '[\"*\"]', '2026-06-11 15:37:03', NULL, '2026-06-11 15:37:02', '2026-06-11 15:37:03'),
(379, 'App\\Models\\User', 112, 'auth_token', '86e88b56f8ffa71fce4ddc829600130569f9463b4b36e4ddee276f3bf8d5ff84', '[\"*\"]', '2026-06-11 15:39:33', NULL, '2026-06-11 15:38:41', '2026-06-11 15:39:33'),
(380, 'App\\Models\\User', 113, 'auth_token', '0235f98061a5bb19142696ed230402f1b05bdb77273f4383baeb3e2eff4844b5', '[\"*\"]', '2026-06-11 15:40:28', NULL, '2026-06-11 15:39:56', '2026-06-11 15:40:28'),
(381, 'App\\Models\\User', 112, 'auth_token', '6a763d9073beb07beeecd4b317072b48eb1e68d9afc4a566035bec8aa0b881f2', '[\"*\"]', '2026-06-11 15:45:34', NULL, '2026-06-11 15:40:53', '2026-06-11 15:45:34'),
(382, 'App\\Models\\User', 113, 'auth_token', '63dc4b5d6afdf96c38ceb6f50b30235f3c7e559db15d617bfd7a791a14fcaa5f', '[\"*\"]', '2026-06-11 16:14:46', NULL, '2026-06-11 15:46:32', '2026-06-11 16:14:46'),
(383, 'App\\Models\\User', 113, 'auth_token', '80a721f1f3f55dc5e726516dd83a9a7840d0b16317a27914efd5c17e4f5d0223', '[\"*\"]', '2026-06-11 18:18:12', NULL, '2026-06-11 15:49:14', '2026-06-11 18:18:12'),
(384, 'App\\Models\\User', 113, 'auth_token', '1bb4fc1ca55f8537987310db197b5a1a30452a6d81813b767f4037f719e0f218', '[\"*\"]', '2026-06-16 14:26:23', NULL, '2026-06-11 16:19:46', '2026-06-16 14:26:23'),
(385, 'App\\Models\\User', 112, 'auth_token', '385655a1e72ebff892d7e6b2b26b33e5441778811be3722ffe7d74e6cd7927ab', '[\"*\"]', '2026-06-11 17:17:45', NULL, '2026-06-11 16:38:43', '2026-06-11 17:17:45'),
(386, 'App\\Models\\User', 112, 'auth_token', '3301520c8f9b3e7f64cc1d4e23a2aca579961c8e8e75a36fdbe96236ef688394', '[\"*\"]', '2026-06-12 10:35:43', NULL, '2026-06-11 17:22:17', '2026-06-12 10:35:43'),
(387, 'App\\Models\\User', 113, 'auth_token', 'f6d6dba426fabc25c91aab9bdb1a205aff354fff8f7f2fe1358cff5d8ed3d45e', '[\"*\"]', NULL, NULL, '2026-06-12 10:34:33', '2026-06-12 10:34:33'),
(388, 'App\\Models\\User', 112, 'auth_token', '6c33dede029eb0a19000b9981b974a3c476f1a186cc923c8952245ce26587b97', '[\"*\"]', '2026-06-12 13:31:06', NULL, '2026-06-12 10:36:27', '2026-06-12 13:31:06'),
(389, 'App\\Models\\User', 114, 'auth_token', 'cbcdd639c18680c16dc418feacb6d440f1ed8dd184cf7bb6b638801352397f3e', '[\"*\"]', '2026-06-15 09:53:12', NULL, '2026-06-12 11:05:19', '2026-06-15 09:53:12'),
(390, 'App\\Models\\User', 114, 'auth_token', '04220d84a82128a67660d5783f6fb9146bf4103a14cef5b79408cb39904c4587', '[\"*\"]', NULL, NULL, '2026-06-12 12:18:47', '2026-06-12 12:18:47'),
(391, 'App\\Models\\User', 112, 'auth_token', '08adb9ae76c41b5662f1cf11975ff6016b6872aa4860480df3d642d48e840d4f', '[\"*\"]', '2026-06-12 13:07:57', NULL, '2026-06-12 12:20:35', '2026-06-12 13:07:57'),
(392, 'App\\Models\\User', 112, 'auth_token', '9e6517f8d8bf6760ca41736f414cd5ee08795a397f036e4286e156f7df9212ae', '[\"*\"]', '2026-06-12 13:33:29', NULL, '2026-06-12 13:15:20', '2026-06-12 13:33:29'),
(393, 'App\\Models\\User', 113, 'auth_token', 'cc5462ed39d68c4622f4e4c87bee1a73562d9197daa50d77b6434a3eaa007ef4', '[\"*\"]', '2026-06-15 13:05:48', NULL, '2026-06-12 13:31:30', '2026-06-15 13:05:48'),
(394, 'App\\Models\\User', 113, 'auth_token', 'cb7d863a21e6959669cb0f0b642e4fabca0ebabfb338d547a076e4dbdd504e91', '[\"*\"]', '2026-06-15 13:55:48', NULL, '2026-06-12 13:34:44', '2026-06-15 13:55:48'),
(395, 'App\\Models\\User', 113, 'auth_token', '870bc88ab68419c083860093282781571f5651517cbebb5718e0105b92cebd36', '[\"*\"]', NULL, NULL, '2026-06-12 14:59:02', '2026-06-12 14:59:02'),
(396, 'App\\Models\\User', 113, 'auth_token', 'e0df09f153628d0ba3093090bffea9d190dac133d1f0c362697f0c8def7a6078', '[\"*\"]', '2026-06-15 10:21:41', NULL, '2026-06-15 09:28:44', '2026-06-15 10:21:41'),
(397, 'App\\Models\\User', 112, 'auth_token', 'fa4192ecfa0bdb41c7adb9fd6063fb6ec95b93716b74ae7773dd6e5a3ec1852f', '[\"*\"]', '2026-06-15 12:40:44', NULL, '2026-06-15 10:35:49', '2026-06-15 12:40:44'),
(398, 'App\\Models\\User', 113, 'auth_token', '475bbebb81ebce0feb99d5ec4d305a92256facc07c349fcf48e9b8fc3173ceb2', '[\"*\"]', '2026-06-15 13:06:33', NULL, '2026-06-15 13:06:31', '2026-06-15 13:06:33'),
(399, 'App\\Models\\User', 112, 'auth_token', 'a7efa358ec1c0ea31323bc638a3323f4c1f76cb9f0f9fed4019c8fc0e8e31b1b', '[\"*\"]', '2026-06-15 13:07:16', NULL, '2026-06-15 13:07:00', '2026-06-15 13:07:16'),
(400, 'App\\Models\\User', 113, 'auth_token', '1607331be5939781c9932fe481f2a4bf4006aab6c35651145e9780e9635ffb51', '[\"*\"]', '2026-06-15 13:08:31', NULL, '2026-06-15 13:07:46', '2026-06-15 13:08:31'),
(401, 'App\\Models\\User', 112, 'auth_token', '7db9fb734f5646d948d52f3e7cad0dbc489151cc66e37df0b819635388aeb662', '[\"*\"]', '2026-06-15 13:09:14', NULL, '2026-06-15 13:08:48', '2026-06-15 13:09:14'),
(402, 'App\\Models\\User', 113, 'auth_token', 'cc93c920aa484ec258ec6ea85dbd1b1cc4afde463298426977a5cffbfb4b2f7e', '[\"*\"]', '2026-06-15 16:59:10', NULL, '2026-06-15 13:09:37', '2026-06-15 16:59:10'),
(403, 'App\\Models\\User', 113, 'auth_token', '76fd0579b92f427e626bd31e71a0181fad768fc36a592b208e7b8314b6a1d868', '[\"*\"]', '2026-06-17 09:47:29', NULL, '2026-06-15 13:30:06', '2026-06-17 09:47:29'),
(404, 'App\\Models\\User', 113, 'auth_token', '9eca7c291c044eafc5c32bcdd3c97d8c3860395bc9cf94b33cbe788c5bd009ed', '[\"*\"]', '2026-06-16 18:35:47', NULL, '2026-06-15 16:37:07', '2026-06-16 18:35:47'),
(405, 'App\\Models\\User', 112, 'auth_token', '72335ec7972517b9c662f802236b32c351588650390b1fa0e774499fccdb0c54', '[\"*\"]', '2026-06-15 18:16:04', NULL, '2026-06-15 18:07:23', '2026-06-15 18:16:04'),
(406, 'App\\Models\\User', 113, 'auth_token', '5242fba8eabe444dd58922b5bfef75dc8d095313cb0648122ca40e4788105c6f', '[\"*\"]', '2026-06-15 18:35:14', NULL, '2026-06-15 18:16:45', '2026-06-15 18:35:14'),
(407, 'App\\Models\\User', 112, 'auth_token', '0100283246a08d459af46b31e7c443c8245284c2ae4c6d2dc1a8dc61ce3f2e12', '[\"*\"]', '2026-06-16 09:38:45', NULL, '2026-06-15 18:41:16', '2026-06-16 09:38:45'),
(408, 'App\\Models\\User', 113, 'auth_token', '130fccf5a370ebbbe24e3077e4d6abc8d836bc9e9349f08086dfe11848875f49', '[\"*\"]', '2026-06-16 09:33:39', NULL, '2026-06-16 09:05:25', '2026-06-16 09:33:39'),
(409, 'App\\Models\\User', 113, 'auth_token', 'b5eef42f5377844f27b2f18df7716684f1a9fed3236ee9df06663b78760446f7', '[\"*\"]', '2026-06-16 10:25:30', NULL, '2026-06-16 09:40:23', '2026-06-16 10:25:30'),
(410, 'App\\Models\\User', 112, 'auth_token', 'd9d5ff32c333adfdaaf45510bffe3cf567df5288e4141db4ced939f9dd465bc1', '[\"*\"]', '2026-06-16 14:30:41', NULL, '2026-06-16 10:26:00', '2026-06-16 14:30:41'),
(411, 'App\\Models\\User', 113, 'auth_token', '09b413645549b12c989b0882cc884729a33f2dad596867b051ecf579e021c668', '[\"*\"]', '2026-06-16 17:18:40', NULL, '2026-06-16 13:34:33', '2026-06-16 17:18:40'),
(412, 'App\\Models\\User', 113, 'auth_token', '815746353410728c964208fcabe5dcba65bfada31c06a77ff7636b8d110c5ffb', '[\"*\"]', '2026-06-17 11:15:24', NULL, '2026-06-16 13:50:13', '2026-06-17 11:15:24'),
(413, 'App\\Models\\User', 112, 'auth_token', '4fa192325526a5e8bc86675f591ddba6c5e33378d1cb5a7b159f3e824278ced5', '[\"*\"]', '2026-06-17 12:31:18', NULL, '2026-06-16 14:26:54', '2026-06-17 12:31:18'),
(414, 'App\\Models\\User', 113, 'auth_token', '986205e4c0f507ce9a5299d88afe02058c3e93f75d71361e0cd4a21afced08fa', '[\"*\"]', '2026-06-16 16:45:33', NULL, '2026-06-16 15:16:31', '2026-06-16 16:45:33'),
(415, 'App\\Models\\User', 112, 'auth_token', '2f4d848bb946d1892c238b415706788a7b820a77fd9ea544a496f7155cc426d5', '[\"*\"]', '2026-06-16 16:52:32', NULL, '2026-06-16 16:52:29', '2026-06-16 16:52:32'),
(416, 'App\\Models\\User', 112, 'auth_token', 'cfa227bee7279a1c1a318b7d7013b3abc4213e20169dd32a9636c849ddb2d087', '[\"*\"]', '2026-06-16 17:15:24', NULL, '2026-06-16 17:15:22', '2026-06-16 17:15:24'),
(417, 'App\\Models\\User', 112, 'auth_token', '0da39aa0775d294fdfddcf9f225154299d3b4359a2aa754e0a0ebc51f722d5cf', '[\"*\"]', '2026-06-17 09:46:29', NULL, '2026-06-16 17:31:23', '2026-06-17 09:46:29'),
(418, 'App\\Models\\User', 113, 'auth_token', '6095a0922263dcd287de1e2d6935900d653591b2d7fe59ab63bdaf8c1caa9834', '[\"*\"]', '2026-06-17 11:51:35', NULL, '2026-06-17 09:06:45', '2026-06-17 11:51:35'),
(419, 'App\\Models\\User', 113, 'auth_token', '9232bdf002c51e2cb410a3ea9705d9ff6830351fb8e018be0f419648f84dcdef', '[\"*\"]', '2026-06-17 10:27:36', NULL, '2026-06-17 09:42:22', '2026-06-17 10:27:36'),
(420, 'App\\Models\\User', 112, 'auth_token', '9a74100551d2764db6fe65e963ae2580fd6a22f35ac8aac99fb9493fdf72e171', '[\"*\"]', '2026-06-17 12:36:53', NULL, '2026-06-17 11:20:48', '2026-06-17 12:36:53'),
(421, 'App\\Models\\User', 112, 'auth_token', '32cc252db59abf0c687b9a0b489bbbec9d280cdba8c4deb0ca6addf819684d7b', '[\"*\"]', '2026-06-17 11:40:14', NULL, '2026-06-17 11:29:31', '2026-06-17 11:40:14'),
(422, 'App\\Models\\User', 113, 'auth_token', 'a3dec85e267d361b8cb87df2af175a01e2d87d442ce19add37dcb348ac65c658', '[\"*\"]', '2026-06-17 11:41:32', NULL, '2026-06-17 11:40:46', '2026-06-17 11:41:32'),
(423, 'App\\Models\\User', 112, 'auth_token', 'fc35e5c50d02b240fe9532cc5cc8db3e474c0caa377be1f6baa60e4a93e426ea', '[\"*\"]', '2026-06-17 11:41:59', NULL, '2026-06-17 11:41:57', '2026-06-17 11:41:59'),
(424, 'App\\Models\\User', 113, 'auth_token', '1b27c994040488d21f107b6a02ba4dc961c70fc81896545024cf8a07d28ed9e6', '[\"*\"]', '2026-06-17 11:50:15', NULL, '2026-06-17 11:44:08', '2026-06-17 11:50:15'),
(425, 'App\\Models\\User', 113, 'auth_token', 'dbc794f994b8ba8679e32f64eaa43fa5faf84782fe06c2435e1b3f7cbf358c9f', '[\"*\"]', '2026-06-17 12:19:57', NULL, '2026-06-17 12:19:00', '2026-06-17 12:19:57'),
(426, 'App\\Models\\User', 112, 'auth_token', '0725611cb6caa9b2c6af0f02d3260a2c9c01b879ccc3cd6db629d16d589775ea', '[\"*\"]', '2026-06-17 13:12:50', NULL, '2026-06-17 13:06:09', '2026-06-17 13:12:50'),
(427, 'App\\Models\\User', 112, 'auth_token', 'ddfbf9bd4e2d1dcebceb0c12f61d1fd9682b489b3fe3557ef93054ab821c24b1', '[\"*\"]', '2026-06-18 13:36:46', NULL, '2026-06-18 13:36:44', '2026-06-18 13:36:46'),
(428, 'App\\Models\\User', 112, 'auth_token', '064b680f7114d4552ad889980cfdac80b5dc70e090ab85b0fc7a698aa3605fca', '[\"*\"]', '2026-06-18 13:41:57', NULL, '2026-06-18 13:38:13', '2026-06-18 13:41:57'),
(429, 'App\\Models\\User', 113, 'auth_token', '557e6b19c79128e3dcb9ee78c429d17ad11013d461da8b6bce6373b99b313564', '[\"*\"]', '2026-06-18 13:46:02', NULL, '2026-06-18 13:42:47', '2026-06-18 13:46:02'),
(430, 'App\\Models\\User', 112, 'auth_token', '100c161e278649f217291bb98606bfa4697e23ebe31d8040e343430b5958e33d', '[\"*\"]', '2026-06-18 13:46:56', NULL, '2026-06-18 13:46:25', '2026-06-18 13:46:56'),
(431, 'App\\Models\\User', 112, 'auth_token', '7e55d0612d7270434f6c0250453f6212f92f7fd32ac404304dfc74cc0e09398b', '[\"*\"]', '2026-06-18 13:49:46', NULL, '2026-06-18 13:47:30', '2026-06-18 13:49:46'),
(432, 'App\\Models\\User', 113, 'auth_token', '0576ffba5be7927a632d3bc27d73b8da3505098352a40dcacb102d128ffc50dd', '[\"*\"]', '2026-06-18 13:50:52', NULL, '2026-06-18 13:50:17', '2026-06-18 13:50:52'),
(433, 'App\\Models\\User', 112, 'auth_token', 'a7361eff317779b11f55fb323c643bd5053c02ce3f9047da3dab472ee74266af', '[\"*\"]', '2026-06-19 15:45:21', NULL, '2026-06-18 13:51:22', '2026-06-19 15:45:21'),
(434, 'App\\Models\\User', 112, 'auth_token', '5443516efb7b53c97b9ea357156ac64b48662db0a977b320b7ce160d8c168e35', '[\"*\"]', '2026-06-18 14:12:19', NULL, '2026-06-18 13:57:17', '2026-06-18 14:12:19');

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` longtext NOT NULL,
  `slug` varchar(191) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `version` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `name`, `description`, `slug`, `order`, `is_active`, `version`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Welcome & Service Usage', 'Welcome to Scott. By accessing or using our services, you agree to comply with the terms described below. Please review this information carefully.', 'welcome-service-usage', 1, 1, 'v1.0', NULL, '2025-12-16 09:01:26', '2025-12-31 11:58:58'),
(2, 'Privacy & Data Protection', '<p>&lt;h1&gt;Privacy Policy for L1STED!&lt;/h1&gt;</p><p>&lt;p&gt;&lt;strong&gt;Effective Date:&lt;/strong&gt; 07/01/2026&lt;br&gt;<br>&lt;strong&gt;Last Updated:&lt;/strong&gt; 07/01/2026&lt;/p&gt;</p><p>&lt;p&gt;L1STED! is owned and operated by [Company Name / Scott Shafer / Legal Entity Name] (\"L1STED!,\" \"we,\" \"our,\" or \"us\"). This Privacy Policy explains how we collect, use, disclose, and protect your information when you use the L1STED! mobile application, website, services, rankings, lists, social features, and related products collectively referred to as the \"Service.\"&lt;/p&gt;</p><p>&lt;p&gt;By using L1STED!, you agree to the practices described in this Privacy Policy. If you do not agree, please do not use the Service.&lt;/p&gt;</p><p>&lt;hr&gt;</p><p>&lt;h2&gt;1. What L1STED! Is&lt;/h2&gt;<br>&lt;p&gt;L1STED! is a social ranking and list-based platform that allows users to create, view, vote on, rank, react to, and share lists. Users may participate in public rankings, private lists, trending lists, live rankings, and other interactive features.&lt;/p&gt;<br>&lt;p&gt;Because L1STED! is built around user-generated rankings and social engagement, some of the information you choose to post may be visible to other users.&lt;/p&gt;</p><p>&lt;h2&gt;2. Information We Collect&lt;/h2&gt;<br>&lt;p&gt;We may collect the following categories of information:&lt;/p&gt;</p><p>&lt;h3&gt;A. Information You Provide Directly&lt;/h3&gt;<br>&lt;p&gt;This may include:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Name or username&lt;/li&gt;<br>&nbsp;&lt;li&gt;Email address&lt;/li&gt;<br>&nbsp;&lt;li&gt;Password or login credentials&lt;/li&gt;<br>&nbsp;&lt;li&gt;Profile photo or avatar&lt;/li&gt;<br>&nbsp;&lt;li&gt;Bio, display name, or other profile details&lt;/li&gt;<br>&nbsp;&lt;li&gt;Lists, rankings, votes, comments, reactions, and other user-generated content&lt;/li&gt;<br>&nbsp;&lt;li&gt;Messages or communications you send to us&lt;/li&gt;<br>&nbsp;&lt;li&gt;Payment or subscription-related information, if paid features are offered&lt;/li&gt;<br>&nbsp;&lt;li&gt;Support requests, feedback, or survey responses&lt;/li&gt;<br>&lt;/ul&gt;</p><p>&lt;h3&gt;B. Information Collected Automatically&lt;/h3&gt;<br>&lt;p&gt;When you use L1STED!, we may automatically collect:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Device type&lt;/li&gt;<br>&nbsp;&lt;li&gt;Operating system&lt;/li&gt;<br>&nbsp;&lt;li&gt;App version&lt;/li&gt;<br>&nbsp;&lt;li&gt;Browser type, if using a web version&lt;/li&gt;<br>&nbsp;&lt;li&gt;IP address&lt;/li&gt;<br>&nbsp;&lt;li&gt;Device identifiers&lt;/li&gt;<br>&nbsp;&lt;li&gt;Log data&lt;/li&gt;<br>&nbsp;&lt;li&gt;Crash reports&lt;/li&gt;<br>&nbsp;&lt;li&gt;Pages, screens, rankings, and features viewed&lt;/li&gt;<br>&nbsp;&lt;li&gt;Time spent in the app&lt;/li&gt;<br>&nbsp;&lt;li&gt;Search queries within the app&lt;/li&gt;<br>&nbsp;&lt;li&gt;Interaction data, including votes, taps, shares, rankings, and list activity&lt;/li&gt;<br>&lt;/ul&gt;</p><p>&lt;h3&gt;C. Social and Ranking Activity&lt;/h3&gt;<br>&lt;p&gt;Because L1STED! is a ranking-based platform, we may collect and store:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Lists you create&lt;/li&gt;<br>&nbsp;&lt;li&gt;Rankings you submit&lt;/li&gt;<br>&nbsp;&lt;li&gt;Items you rank&lt;/li&gt;<br>&nbsp;&lt;li&gt;Changes to rankings over time&lt;/li&gt;<br>&nbsp;&lt;li&gt;\"Rank-back\" interactions&lt;/li&gt;<br>&nbsp;&lt;li&gt;Likes, dislikes, votes, comments, shares, and reactions&lt;/li&gt;<br>&nbsp;&lt;li&gt;Public leaderboard activity&lt;/li&gt;<br>&nbsp;&lt;li&gt;Trending-list participation&lt;/li&gt;<br>&nbsp;&lt;li&gt;User-to-user engagement data&lt;/li&gt;<br>&lt;/ul&gt;</p><p>&lt;h3&gt;D. Location Information&lt;/h3&gt;<br>&lt;p&gt;We may collect approximate location information, such as city, state, or country, based on your IP address or device settings.&lt;/p&gt;<br>&lt;p&gt;We will only collect precise location information if we ask for your permission and you choose to allow it.&lt;/p&gt;</p><p>&lt;h3&gt;E. Contacts or Friends&lt;/h3&gt;<br>&lt;p&gt;If L1STED! offers a friend-finding, invitation, or contact-syncing feature, we may request access to your contacts. We will only access contacts with your permission.&lt;/p&gt;<br>&lt;p&gt;You can disable contact access through your device settings.&lt;/p&gt;</p><p>&lt;h3&gt;F. Payment Information&lt;/h3&gt;<br>&lt;p&gt;If L1STED! offers subscriptions, paid rankings, premium features, creator tools, boosts, themes, or in-app purchases, payments may be processed by third-party payment providers such as Apple, Google, Stripe, or another payment processor.&lt;/p&gt;<br>&lt;p&gt;We do not directly store your full credit card number unless explicitly stated. Payment processors may collect and process billing information according to their own privacy policies.&lt;/p&gt;</p><p>&lt;h2&gt;3. How We Use Your Information&lt;/h2&gt;<br>&lt;p&gt;We may use your information to:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Create and manage your account&lt;/li&gt;<br>&nbsp;&lt;li&gt;Provide, operate, and improve L1STED!&lt;/li&gt;<br>&nbsp;&lt;li&gt;Display lists, rankings, and user-generated content&lt;/li&gt;<br>&nbsp;&lt;li&gt;Personalize your experience&lt;/li&gt;<br>&nbsp;&lt;li&gt;Show trending, recommended, or relevant lists&lt;/li&gt;<br>&nbsp;&lt;li&gt;Enable voting, ranking, commenting, sharing, and social features&lt;/li&gt;<br>&nbsp;&lt;li&gt;Send app notifications, including ranking changes, list activity, and \"Rank-Back\" alerts&lt;/li&gt;<br>&nbsp;&lt;li&gt;Respond to support requests&lt;/li&gt;<br>&nbsp;&lt;li&gt;Detect fraud, abuse, spam, bots, or harmful behavior&lt;/li&gt;<br>&nbsp;&lt;li&gt;Enforce our Terms of Service and community guidelines&lt;/li&gt;<br>&nbsp;&lt;li&gt;Analyze usage and improve app performance&lt;/li&gt;<br>&nbsp;&lt;li&gt;Develop new features&lt;/li&gt;<br>&nbsp;&lt;li&gt;Process payments or subscriptions&lt;/li&gt;<br>&nbsp;&lt;li&gt;Display advertising or sponsored content, where applicable&lt;/li&gt;<br>&nbsp;&lt;li&gt;Comply with legal obligations&lt;/li&gt;<br>&lt;/ul&gt;<br>&lt;p&gt;The FTC recommends that app developers minimize data collection, limit access and permissions, and build privacy/security into the design of their services.&lt;/p&gt;</p><p>&lt;h2&gt;4. Public Content and Visibility&lt;/h2&gt;<br>&lt;p&gt;L1STED! is designed around lists, rankings, and social visibility.&lt;/p&gt;<br>&lt;p&gt;The following information may be public or visible to other users:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Username&lt;/li&gt;<br>&nbsp;&lt;li&gt;Profile photo or avatar&lt;/li&gt;<br>&nbsp;&lt;li&gt;Public lists&lt;/li&gt;<br>&nbsp;&lt;li&gt;Public rankings&lt;/li&gt;<br>&nbsp;&lt;li&gt;Votes, comments, reactions, and ranking activity&lt;/li&gt;<br>&nbsp;&lt;li&gt;Leaderboard positions&lt;/li&gt;<br>&nbsp;&lt;li&gt;Public profile activity&lt;/li&gt;<br>&nbsp;&lt;li&gt;Shared content&lt;/li&gt;<br>&lt;/ul&gt;<br>&lt;p&gt;Please do not post information you do not want others to see. We are not responsible for how other users save, copy, screenshot, share, or use content you make public.&lt;/p&gt;</p><p>&lt;h2&gt;5. Notifications&lt;/h2&gt;<br>&lt;p&gt;L1STED! may send push notifications, emails, or in-app alerts related to:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Ranking changes&lt;/li&gt;<br>&nbsp;&lt;li&gt;List activity&lt;/li&gt;<br>&nbsp;&lt;li&gt;Comments, votes, or reactions&lt;/li&gt;<br>&nbsp;&lt;li&gt;Friend or follower activity&lt;/li&gt;<br>&nbsp;&lt;li&gt;New features&lt;/li&gt;<br>&nbsp;&lt;li&gt;Promotional messages&lt;/li&gt;<br>&nbsp;&lt;li&gt;Security or account updates&lt;/li&gt;<br>&lt;/ul&gt;<br>&lt;p&gt;You can manage push notifications through your device settings. You may unsubscribe from promotional emails by using the unsubscribe link included in those messages.&lt;/p&gt;</p><p>&lt;h2&gt;6. Advertising, Analytics, and Tracking&lt;/h2&gt;<br>&lt;p&gt;We may use third-party analytics, advertising, or measurement tools to understand how users interact with L1STED!, improve the Service, and, where applicable, show ads or sponsored content.&lt;/p&gt;<br>&lt;p&gt;These third parties may collect information such as:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Device identifiers&lt;/li&gt;<br>&nbsp;&lt;li&gt;IP address&lt;/li&gt;<br>&nbsp;&lt;li&gt;App activity&lt;/li&gt;<br>&nbsp;&lt;li&gt;Ad interactions&lt;/li&gt;<br>&nbsp;&lt;li&gt;Approximate location&lt;/li&gt;<br>&nbsp;&lt;li&gt;Usage data&lt;/li&gt;<br>&nbsp;&lt;li&gt;Crash and performance data&lt;/li&gt;<br>&lt;/ul&gt;<br>&lt;p&gt;If L1STED! uses advertising or tracking technologies, we will update this Privacy Policy and any required app store disclosures to reflect those practices. Apple’s App Privacy Details require developers to disclose data collected by the app and third-party partners.&lt;/p&gt;</p><p>&lt;h2&gt;7. How We Share Information&lt;/h2&gt;<br>&lt;p&gt;We may share information in the following situations:&lt;/p&gt;</p><p>&lt;h3&gt;A. With Other Users&lt;/h3&gt;<br>&lt;p&gt;Your public profile, lists, rankings, comments, votes, and other public activity may be visible to other users.&lt;/p&gt;</p><p>&lt;h3&gt;B. With Service Providers&lt;/h3&gt;<br>&lt;p&gt;We may share information with vendors who help us operate L1STED!, such as:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Cloud hosting providers&lt;/li&gt;<br>&nbsp;&lt;li&gt;Analytics providers&lt;/li&gt;<br>&nbsp;&lt;li&gt;Payment processors&lt;/li&gt;<br>&nbsp;&lt;li&gt;Customer support tools&lt;/li&gt;<br>&nbsp;&lt;li&gt;Email and notification providers&lt;/li&gt;<br>&nbsp;&lt;li&gt;Security and fraud prevention vendors&lt;/li&gt;<br>&nbsp;&lt;li&gt;App performance and crash reporting tools&lt;/li&gt;<br>&lt;/ul&gt;<br>&lt;p&gt;These providers are only permitted to use information as needed to provide services to us.&lt;/p&gt;</p><p>&lt;h3&gt;C. For Legal Reasons&lt;/h3&gt;<br>&lt;p&gt;We may disclose information if required to:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Comply with law, subpoena, court order, or legal process&lt;/li&gt;<br>&nbsp;&lt;li&gt;Protect the rights, safety, or property of L1STED!, users, or others&lt;/li&gt;<br>&nbsp;&lt;li&gt;Investigate fraud, abuse, or security issues&lt;/li&gt;<br>&nbsp;&lt;li&gt;Enforce our Terms of Service&lt;/li&gt;<br>&lt;/ul&gt;</p><p>&lt;h3&gt;D. Business Transfers&lt;/h3&gt;<br>&lt;p&gt;If L1STED! is involved in a merger, acquisition, financing, sale of assets, bankruptcy, or similar transaction, user information may be transferred as part of that transaction.&lt;/p&gt;</p><p>&lt;h3&gt;E. With Your Consent&lt;/h3&gt;<br>&lt;p&gt;We may share information with your consent or at your direction.&lt;/p&gt;</p><p>&lt;h2&gt;8. Data Retention&lt;/h2&gt;<br>&lt;p&gt;We keep your information for as long as necessary to provide the Service, maintain your account, comply with legal obligations, resolve disputes, enforce agreements, and support legitimate business purposes.&lt;/p&gt;<br>&lt;p&gt;User-generated content may remain visible unless deleted by you or removed by us according to our Terms of Service or community rules.&lt;/p&gt;<br>&lt;p&gt;Some information may remain in backups, logs, fraud prevention records, legal records, or archived systems for a limited period after deletion.&lt;/p&gt;</p><p>&lt;h2&gt;9. Your Choices and Privacy Rights&lt;/h2&gt;<br>&lt;p&gt;Depending on where you live, you may have the right to:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Access the personal information we have about you&lt;/li&gt;<br>&nbsp;&lt;li&gt;Correct inaccurate information&lt;/li&gt;<br>&nbsp;&lt;li&gt;Delete your account or personal information&lt;/li&gt;<br>&nbsp;&lt;li&gt;Object to or restrict certain processing&lt;/li&gt;<br>&nbsp;&lt;li&gt;Opt out of certain sharing or targeted advertising&lt;/li&gt;<br>&nbsp;&lt;li&gt;Withdraw consent where processing is based on consent&lt;/li&gt;<br>&nbsp;&lt;li&gt;Request a copy of your data&lt;/li&gt;<br>&lt;/ul&gt;<br>&lt;p&gt;California residents may have rights under the California Consumer Privacy Act, including rights to know, delete, correct, and opt out of certain uses of personal information.&lt;/p&gt;<br>&lt;p&gt;To make a privacy request, contact us at:&lt;br&gt;<br>Email: [Insert Privacy Email]&lt;br&gt;<br>Mailing Address: [Insert Mailing Address]&lt;/p&gt;<br>&lt;p&gt;We may need to verify your identity before completing certain requests.&lt;/p&gt;</p><p>&lt;h2&gt;10. Account Deletion&lt;/h2&gt;<br>&lt;p&gt;You may request deletion of your account by:&lt;/p&gt;<br>&lt;ul&gt;<br>&nbsp;&lt;li&gt;Using the account deletion feature in the app, if available; or&lt;/li&gt;<br>&nbsp;&lt;li&gt;Contacting us at [Insert Email Address]&lt;/li&gt;<br>&lt;/ul&gt;<br>&lt;p&gt;When your account is deleted, we will delete or de-identify personal information associated with your account unless we need to retain certain information for legal, security, fraud prevention, or legitimate business purposes.&lt;/p&gt;<br>&lt;p&gt;Public content you posted may be deleted, anonymized, or retained depending on how the feature works and what is technically or legally required.&lt;/p&gt;</p><p>&lt;h2&gt;11. Children’s Privacy&lt;/h2&gt;<br>&lt;p&gt;L1STED! is not intended for children under the age of 13.&lt;/p&gt;<br>&lt;p&gt;We do not knowingly collect personal information from children under 13. If we learn that we have collected personal information from a child under 13 without appropriate consent, we will take reasonable steps to delete it.&lt;/p&gt;<br>&lt;p&gt;If you believe a child has provided us with personal information, contact us at:&lt;br&gt;<br>Email: [Insert Privacy Email]&lt;/p&gt;<br>&lt;p&gt;If L1STED! is later designed for teenagers or younger users, this section should be reviewed carefully for compliance with children’s privacy laws.&lt;/p&gt;</p><p>&lt;h2&gt;12. Security&lt;/h2&gt;<br>&lt;p&gt;We use reasonable administrative, technical, and physical safeguards to protect your information. However, no method of transmission or storage is completely secure.&lt;/p&gt;<br>&lt;p&gt;You are responsible for keeping your login credentials confidential. Please notify us immediately if you believe your account has been accessed without permission.&lt;/p&gt;</p><p>&lt;h2&gt;13. Third-Party Links and Services&lt;/h2&gt;<br>&lt;p&gt;L1STED! may contain links to third-party websites, content, ads, social platforms, or services. We are not responsible for the privacy practices of third parties.&lt;/p&gt;<br>&lt;p&gt;Your use of third-party services is governed by their own privacy policies and terms.&lt;/p&gt;</p><p>&lt;h2&gt;14. User-Generated Content&lt;/h2&gt;<br>&lt;p&gt;You are responsible for the content you post, rank, upload, comment on, or share through L1STED!.&lt;/p&gt;<br>&lt;p&gt;Do not post personal information about yourself or others unless you have permission to do so.&lt;/p&gt;<br>&lt;p&gt;We may remove content that violates our Terms of Service, community guidelines, applicable law, or the rights of others.&lt;/p&gt;</p><p>&lt;h2&gt;15. International Users&lt;/h2&gt;<br>&lt;p&gt;If you access L1STED! from outside the United States, your information may be processed in the United States or other countries where we or our service providers operate.&lt;/p&gt;<br>&lt;p&gt;By using L1STED!, you understand that your information may be transferred to countries that may have different data protection laws than your country of residence.&lt;/p&gt;</p><p>&lt;h2&gt;16. Changes to This Privacy Policy&lt;/h2&gt;<br>&lt;p&gt;We may update this Privacy Policy from time to time.&lt;/p&gt;<br>&lt;p&gt;If we make material changes, we may notify you through the app, by email, or by updating the \"Last Updated\" date above.&lt;/p&gt;<br>&lt;p&gt;Your continued use of L1STED! after changes become effective means you accept the updated Privacy Policy.&lt;/p&gt;</p><p>&lt;h2&gt;17. Contact Us&lt;/h2&gt;<br>&lt;p&gt;For questions, requests, or concerns about this Privacy Policy, contact us at:&lt;/p&gt;<br>&lt;p&gt;&lt;strong&gt;L1STED!&lt;/strong&gt;&lt;br&gt;<br>Owner/Company: Scott J Shafer&lt;br&gt;<br>Email: L1STEDinfo@gmail.com&lt;br&gt;<br>Mailing Address: 5101 SW 60th Street Rd. #302, Ocala, FL, 34474&lt;br&gt;<br>Website: &lt;/p&gt;</p><p>&lt;hr&gt;</p><p>&lt;h2&gt;Optional Short Version for App Store Listing&lt;/h2&gt;<br>&lt;p&gt;&lt;strong&gt;L1STED! Privacy Summary&lt;/strong&gt;&lt;/p&gt;<br>&lt;p&gt;L1STED! collects information needed to create accounts, operate rankings and lists, improve the app, send notifications, prevent abuse, and support analytics or advertising where applicable. Public rankings, lists, usernames, comments, votes, and profile activity may be visible to other users. Users may request access, correction, or deletion of their information by contacting us at [Insert Email]. L1STED! is not intended for children under 13.&lt;/p&gt;</p><p>&lt;hr&gt;</p><p>&lt;p&gt;&lt;em&gt;Before publishing, the big things to decide are whether L1STED! will collect precise location, use targeted advertising, allow minors, include private messaging, or sell/share data for ad purposes. Those choices change what must be disclosed in the policy and in Apple/Google app store privacy forms.&lt;/em&gt;&lt;/p&gt;</p>', 'privacy-data-protection', 2, 1, 'v1.0', NULL, '2025-12-16 09:01:26', '2026-06-23 11:19:08'),
(3, 'Account & Security Responsibilities', 'You are responsible for maintaining the confidentiality of your account credentials.\n\nAny activity performed through your account is your responsibility.', 'account-security-responsibilities', 3, 1, 'v1.0', NULL, '2025-12-16 09:01:26', '2025-12-16 09:01:26');

-- --------------------------------------------------------

--
-- Table structure for table `segments`
--

CREATE TABLE `segments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `filters` text NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `estimated_users` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `segments`
--

INSERT INTO `segments` (`id`, `name`, `filters`, `status`, `estimated_users`, `created_at`, `updated_at`, `deleted_at`) VALUES
(8, 'Segment-2', '{\"intrest_ids\":[\"33\",\"34\",\"35\",\"36\",\"37\",\"38\",\"39\",\"40\",\"41\",\"42\",\"43\",\"44\",\"45\",\"46\",\"47\",\"48\",\"49\",\"50\",\"51\",\"52\",\"53\",\"54\",\"55\",\"56\",\"57\",\"58\",\"59\",\"60\",\"61\",\"62\",\"63\",\"64\",\"65\",\"66\",\"67\",\"68\",\"69\",\"70\",\"71\",\"72\",\"73\",\"74\",\"75\",\"76\",\"77\",\"78\",\"79\",\"80\",\"81\"]}', 'active', NULL, '2025-12-29 19:27:10', '2026-06-11 15:45:32', NULL),
(9, 'Segment-1', '{\"intrest_ids\":[\"33\",\"34\",\"35\",\"36\",\"37\",\"38\",\"39\",\"40\",\"41\",\"42\",\"43\",\"44\",\"45\",\"46\",\"47\",\"48\",\"49\",\"50\",\"51\",\"52\",\"53\",\"54\",\"55\",\"56\",\"57\",\"58\",\"59\",\"60\",\"61\",\"62\",\"63\",\"64\",\"65\",\"66\",\"67\",\"68\",\"69\",\"70\",\"71\",\"72\",\"73\",\"74\",\"75\",\"76\",\"77\",\"78\",\"79\",\"80\",\"81\"]}', 'active', NULL, '2026-03-27 17:00:02', '2026-06-11 15:45:17', NULL);

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
('aHdVj7sdvLYCzzFuulBUkxjP2B1zAyjYJwwrCass', 1, '119.82.94.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibndXcWNDcTEwWHRpZ3A5TWFzUFlOWnNxeFpuS29xdkxRWFB0Y2haMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTQ6Imh0dHBzOi8vd3d3Lm1hcmt1cGRlc2lnbnMubmV0L3Njb3R0LXNoYWZlci9hZG1pbi91c2VycyI7czo1OiJyb3V0ZSI7czoxNjoiYWRtaW4udXNlci5pbmRleCI7fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1782114621),
('dcYEyFKr7t972jqKW7JXMffvp8nBR0Fcr6eQaRY3', NULL, '119.82.94.105', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQU53V0hLd0ExQWZXZHh4Y25nZUJKcmM4Rjh1Z1Zhekg5d3czeHR2aSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1ODoiaHR0cHM6Ly93d3cubWFya3VwZGVzaWducy5uZXQvc2NvdHQtc2hhZmVyL2FkbWluL2NhbXBhaWducyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjU0OiJodHRwczovL3d3dy5tYXJrdXBkZXNpZ25zLm5ldC9zY290dC1zaGFmZXIvYWRtaW4vbG9naW4iO3M6NToicm91dGUiO3M6MTE6ImFkbWluLmxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1783337059),
('DT4Ude7IYjnKfSXsKjOtVBR0dZInitKApwzpIpu2', 1, '180.151.243.174', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYnhnNlJ3WG1URTdLUlJOUEhKd2hkVUExY2gxVnEyVlAyaHdUYUM0ayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njg6Imh0dHBzOi8vd3d3Lm1hcmt1cGRlc2lnbnMubmV0L3Njb3R0LXNoYWZlci9hZG1pbi9hcHAtdmVyc2lvbnMvMS9lZGl0IjtzOjU6InJvdXRlIjtzOjIzOiJhZG1pbi5hcHBfdmVyc2lvbnMuZWRpdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1782201874),
('EkPJhM3M2sqvdCWxf362FISV0HXWcMV0589OBjGE', 1, '106.219.160.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWFY5STBCUzNRdjBtUXJBckhWd0JnTzFJY0hLTVlPRXBGOHQxV1R1NSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjA6Imh0dHBzOi8vd3d3Lm1hcmt1cGRlc2lnbnMubmV0L3Njb3R0LXNoYWZlci9hZG1pbi9pbnRlcmVzdC82MiI7czo1OiJyb3V0ZSI7czoxOToiYWRtaW4uaW50ZXJlc3Quc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1782131970);

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = Inactive, 1 = Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `name`, `slug`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ghost', 'ghost', NULL, 1, '2026-02-12 14:24:10', '2026-02-12 14:24:10'),
(2, 1, 'Punjabi songs', 'punjabi-songs', NULL, 1, '2026-02-12 14:24:29', '2026-02-12 14:24:29'),
(3, 4, 'Pubg', 'pubg', NULL, 1, '2026-02-12 14:24:55', '2026-02-12 14:24:55'),
(4, 5, 'Comedy books', 'comedy-books', NULL, 1, '2026-02-12 14:25:32', '2026-02-12 14:25:32'),
(5, 6, 'Dr. strange', 'dr-strange', NULL, 1, '2026-02-12 14:25:52', '2026-02-12 14:25:52'),
(6, 8, 'Food', 'food', NULL, 1, '2026-04-24 14:54:06', '2026-04-24 14:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `country_code` varchar(5) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `is_phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_consent_completed` tinyint(1) NOT NULL DEFAULT 0,
  `is_interest_completed` tinyint(1) NOT NULL DEFAULT 0,
  `is_profile_completed` tinyint(1) NOT NULL DEFAULT 0,
  `otp` int(11) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = Inactive, 1 = Active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `country_code`, `phone`, `country`, `is_phone_verified`, `is_consent_completed`, `is_interest_completed`, `is_profile_completed`, `otp`, `otp_expires_at`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(112, 'Peter Lee', 'peter@yopmail.com', '+91', '+911234567890', 'India', 1, 1, 1, 1, NULL, '2026-06-18 14:06:55', NULL, 1, NULL, '2026-06-11 14:54:33', '2026-06-18 13:57:17'),
(113, 'Flora', 'flora@yopmail.com', '+91', '+919259266808', 'china', 1, 1, 1, 1, NULL, '2026-06-18 14:00:08', NULL, 1, NULL, '2026-06-11 14:58:35', '2026-06-18 13:50:17'),
(114, 'Shekhar', 'shekhar@yopmail.com', '+91', '+918765432100', 'India', 1, 1, 1, 1, NULL, '2026-06-12 12:28:31', NULL, 1, NULL, '2026-06-12 10:41:34', '2026-06-12 12:18:47');

-- --------------------------------------------------------

--
-- Table structure for table `user_consents`
--

CREATE TABLE `user_consents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `accepted_terms_privacy` tinyint(1) NOT NULL DEFAULT 0,
  `campaign_marketing` tinyint(1) NOT NULL DEFAULT 0,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_consents`
--

INSERT INTO `user_consents` (`id`, `user_id`, `accepted_terms_privacy`, `campaign_marketing`, `accepted_at`, `created_at`, `updated_at`) VALUES
(72, 112, 1, 1, '2026-06-11 14:54:39', '2026-06-11 14:54:39', '2026-06-11 14:54:39'),
(73, 113, 1, 1, '2026-06-11 14:58:42', '2026-06-11 14:58:42', '2026-06-11 14:58:42'),
(74, 114, 1, 1, '2026-06-12 10:42:50', '2026-06-12 10:42:50', '2026-06-12 10:42:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_data_export_requests`
--

CREATE TABLE `user_data_export_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `file_path` varchar(255) DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `error` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_data_export_requests`
--

INSERT INTO `user_data_export_requests` (`id`, `user_id`, `status`, `file_path`, `requested_at`, `completed_at`, `expires_at`, `error`, `created_at`, `updated_at`) VALUES
(3, 113, 'completed', 'storage/exports/user_113_1781260797/data_export.zip', '2026-06-12 10:39:57', '2026-06-12 14:39:57', '2026-06-19 14:39:57', NULL, '2026-06-12 14:39:57', '2026-06-12 14:39:57'),
(4, 113, 'completed', 'storage/exports/user_113_1781503932/data_export.zip', '2026-06-15 06:12:12', '2026-06-15 10:12:12', '2026-06-22 10:12:12', NULL, '2026-06-15 10:12:12', '2026-06-15 10:12:12'),
(5, 113, 'completed', 'storage/exports/user_113_1781516493/data_export.zip', '2026-06-15 09:41:33', '2026-06-15 13:41:33', '2026-06-22 13:41:33', NULL, '2026-06-15 13:41:33', '2026-06-15 13:41:33'),
(6, 113, 'completed', 'storage/exports/user_113_1781517147/data_export.zip', '2026-06-15 09:52:27', '2026-06-15 13:52:27', '2026-06-22 13:52:27', NULL, '2026-06-15 13:52:27', '2026-06-15 13:52:27'),
(7, 113, 'completed', 'storage/exports/user_113_1781517292/data_export.zip', '2026-06-15 09:54:52', '2026-06-15 13:54:52', '2026-06-22 13:54:52', NULL, '2026-06-15 13:54:52', '2026-06-15 13:54:52'),
(8, 113, 'completed', 'storage/exports/user_113_1781527397/data_export.zip', '2026-06-15 12:43:17', '2026-06-15 16:43:17', '2026-06-22 16:43:17', NULL, '2026-06-15 16:43:17', '2026-06-15 16:43:17'),
(9, 113, 'completed', 'storage/exports/user_113_1781527412/data_export.zip', '2026-06-15 12:43:32', '2026-06-15 16:43:32', '2026-06-22 16:43:32', NULL, '2026-06-15 16:43:32', '2026-06-15 16:43:32'),
(10, 113, 'completed', 'storage/exports/user_113_1781527418/data_export.zip', '2026-06-15 12:43:38', '2026-06-15 16:43:38', '2026-06-22 16:43:38', NULL, '2026-06-15 16:43:38', '2026-06-15 16:43:38'),
(11, 113, 'completed', 'storage/exports/user_113_1781527425/data_export.zip', '2026-06-15 12:43:45', '2026-06-15 16:43:45', '2026-06-22 16:43:45', NULL, '2026-06-15 16:43:45', '2026-06-15 16:43:45'),
(12, 113, 'completed', 'storage/exports/user_113_1781533379/data_export.zip', '2026-06-15 14:22:59', '2026-06-15 18:22:59', '2026-06-22 18:22:59', NULL, '2026-06-15 18:22:59', '2026-06-15 18:22:59'),
(13, 113, 'completed', 'storage/exports/user_113_1781533395/data_export.zip', '2026-06-15 14:23:15', '2026-06-15 18:23:15', '2026-06-22 18:23:15', NULL, '2026-06-15 18:23:15', '2026-06-15 18:23:15'),
(14, 113, 'completed', 'storage/exports/user_113_1781613527/data_export.zip', '2026-06-16 12:38:47', '2026-06-16 16:38:47', '2026-06-23 16:38:47', NULL, '2026-06-16 16:38:47', '2026-06-16 16:38:47'),
(15, 113, 'completed', 'storage/exports/user_113_1781613775/data_export.zip', '2026-06-16 12:42:55', '2026-06-16 16:42:55', '2026-06-23 16:42:55', NULL, '2026-06-16 16:42:55', '2026-06-16 16:42:55'),
(16, 113, 'completed', 'storage/exports/user_113_1781614051/user_data_export.csv', '2026-06-16 12:47:31', '2026-06-16 16:47:31', '2026-06-23 16:47:31', NULL, '2026-06-16 16:47:31', '2026-06-16 16:47:31'),
(17, 113, 'completed', 'exports/user_113_1781614287/user_data_export.csv', '2026-06-16 12:51:27', '2026-06-16 16:51:27', '2026-06-23 16:51:27', NULL, '2026-06-16 16:51:27', '2026-06-16 16:51:27'),
(18, 113, 'processing', NULL, '2026-06-16 12:52:50', NULL, NULL, NULL, '2026-06-16 16:52:50', '2026-06-16 16:52:50'),
(19, 113, 'processing', NULL, '2026-06-16 12:55:03', NULL, NULL, NULL, '2026-06-16 16:55:03', '2026-06-16 16:55:03'),
(20, 113, 'completed', 'exports/user_113_1781614522/user_data_export.csv', '2026-06-16 12:55:22', '2026-06-16 16:55:22', '2026-06-23 16:55:22', NULL, '2026-06-16 16:55:22', '2026-06-16 16:55:22'),
(21, 113, 'completed', 'exports/user_113_1781614615/user_data_export.csv', '2026-06-16 12:56:55', '2026-06-16 16:56:55', '2026-06-23 16:56:55', NULL, '2026-06-16 16:56:55', '2026-06-16 16:56:55'),
(22, 113, 'completed', 'exports/user_113_1781614684/user_data_export.csv', '2026-06-16 12:58:04', '2026-06-16 16:58:04', '2026-06-23 16:58:04', NULL, '2026-06-16 16:58:04', '2026-06-16 16:58:04'),
(23, 113, 'processing', NULL, '2026-06-16 12:58:29', NULL, NULL, NULL, '2026-06-16 16:58:29', '2026-06-16 16:58:29'),
(24, 113, 'completed', 'exports/user_113_1781614756/user_data_export.csv', '2026-06-16 12:59:16', '2026-06-16 16:59:16', '2026-06-23 16:59:16', NULL, '2026-06-16 16:59:16', '2026-06-16 16:59:16'),
(25, 113, 'completed', 'exports/user_113_1781614761/user_data_export.csv', '2026-06-16 12:59:21', '2026-06-16 16:59:21', '2026-06-23 16:59:21', NULL, '2026-06-16 16:59:21', '2026-06-16 16:59:21'),
(26, 113, 'processing', NULL, '2026-06-16 13:00:28', NULL, NULL, NULL, '2026-06-16 17:00:28', '2026-06-16 17:00:28'),
(27, 113, 'completed', 'exports/user_113_1781614921/user_data_export.csv', '2026-06-16 13:02:01', '2026-06-16 17:02:01', '2026-06-23 17:02:01', NULL, '2026-06-16 17:02:01', '2026-06-16 17:02:01'),
(28, 113, 'completed', 'exports/user_113_1781614923/user_data_export.csv', '2026-06-16 13:02:03', '2026-06-16 17:02:03', '2026-06-23 17:02:03', NULL, '2026-06-16 17:02:03', '2026-06-16 17:02:03'),
(29, 113, 'completed', 'exports/user_113_1781614932/user_data_export.csv', '2026-06-16 13:02:12', '2026-06-16 17:02:12', '2026-06-23 17:02:12', NULL, '2026-06-16 17:02:12', '2026-06-16 17:02:12'),
(30, 113, 'completed', 'exports/user_113_1781682672/user_data_export.csv', '2026-06-17 07:51:12', '2026-06-17 11:51:12', '2026-06-24 11:51:12', NULL, '2026-06-17 11:51:11', '2026-06-17 11:51:12'),
(31, 113, 'completed', 'exports/user_113_1781682695/user_data_export.csv', '2026-06-17 07:51:35', '2026-06-17 11:51:35', '2026-06-24 11:51:35', NULL, '2026-06-17 11:51:35', '2026-06-17 11:51:35'),
(32, 112, 'completed', 'exports/user_112_1781687376/user_data_export.csv', '2026-06-17 09:09:36', '2026-06-17 13:09:36', '2026-06-24 13:09:36', NULL, '2026-06-17 13:09:36', '2026-06-17 13:09:36'),
(33, 112, 'completed', 'exports/user_112_1781687481/user_data_export.csv', '2026-06-17 09:11:21', '2026-06-17 13:11:21', '2026-06-24 13:11:21', NULL, '2026-06-17 13:11:21', '2026-06-17 13:11:21'),
(34, 112, 'completed', 'exports/user_112_1781776324/user_data_export.csv', '2026-06-18 09:52:04', '2026-06-18 13:52:04', '2026-06-25 13:52:04', NULL, '2026-06-18 13:52:04', '2026-06-18 13:52:04');

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `device_token` text NOT NULL,
  `device_type` varchar(50) NOT NULL DEFAULT 'android',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_interest`
--

CREATE TABLE `user_interest` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `interest_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_interest`
--

INSERT INTO `user_interest` (`id`, `user_id`, `interest_id`, `created_at`, `updated_at`) VALUES
(270, 112, 43, '2026-06-11 14:55:25', '2026-06-11 14:55:25'),
(271, 112, 62, '2026-06-11 14:55:25', '2026-06-11 14:55:25'),
(272, 112, 72, '2026-06-11 14:55:25', '2026-06-11 14:55:25'),
(273, 112, 37, '2026-06-11 14:55:25', '2026-06-11 14:55:25'),
(280, 114, 33, '2026-06-12 10:43:54', '2026-06-12 10:43:54'),
(281, 114, 34, '2026-06-12 10:43:54', '2026-06-12 10:43:54'),
(282, 114, 35, '2026-06-12 10:43:54', '2026-06-12 10:43:54'),
(285, 113, 43, '2026-06-16 17:12:54', '2026-06-16 17:12:54'),
(286, 113, 62, '2026-06-16 17:12:54', '2026-06-16 17:12:54'),
(287, 113, 72, '2026-06-16 17:12:54', '2026-06-16 17:12:54'),
(288, 113, 33, '2026-06-16 17:18:37', '2026-06-16 17:18:37'),
(289, 113, 37, '2026-06-16 17:18:37', '2026-06-16 17:18:37'),
(290, 112, 38, '2026-06-17 12:31:14', '2026-06-17 12:31:14'),
(291, 112, 78, '2026-06-17 13:07:00', '2026-06-17 13:07:00'),
(292, 112, 34, '2026-06-18 13:52:39', '2026-06-18 13:52:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_list_positions`
--

CREATE TABLE `user_list_positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `list_id` bigint(20) UNSIGNED NOT NULL,
  `position` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `age_band` varchar(191) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `dining_budget` varchar(191) DEFAULT NULL,
  `has_dogs` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `age_band`, `profile_image`, `city`, `dining_budget`, `has_dogs`, `created_at`, `updated_at`) VALUES
(51, 112, '25-34', 'storage/profile-images/6a2ba7a39551a.jpg', 'Delhi', '$100 - $400', 1, '2026-06-11 14:55:45', '2026-06-15 18:09:41'),
(52, 113, '18-25', NULL, 'hongkong', '1000', 1, '2026-06-11 14:59:15', '2026-06-16 17:09:26'),
(53, 114, '18-24', NULL, 'Noida', '100-400', 1, '2026-06-12 11:02:11', '2026-06-12 11:02:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_password_otps`
--
ALTER TABLE `admin_password_otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_versions`
--
ALTER TABLE `app_versions`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaign_segment`
--
ALTER TABLE `campaign_segment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `segment_id` (`segment_id`);

--
-- Indexes for table `catalog_categories`
--
ALTER TABLE `catalog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `catalog_items`
--
ALTER TABLE `catalog_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_catalog_category` (`category_id`);

--
-- Indexes for table `catalog_item_tag`
--
ALTER TABLE `catalog_item_tag`
  ADD PRIMARY KEY (`catalog_item_id`,`catalog_tag_id`);

--
-- Indexes for table `catalog_tags`
--
ALTER TABLE `catalog_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `featured_item_bookmarks`
--
ALTER TABLE `featured_item_bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_item_bookmark` (`user_id`,`featured_list_item_id`);

--
-- Indexes for table `featured_item_likes`
--
ALTER TABLE `featured_item_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_item_like` (`user_id`,`featured_list_item_id`);

--
-- Indexes for table `featured_item_shares`
--
ALTER TABLE `featured_item_shares`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `featured_lists`
--
ALTER TABLE `featured_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status_order` (`status`,`display_order`);

--
-- Indexes for table `featured_list_items`
--
ALTER TABLE `featured_list_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_featured_item` (`featured_list_id`,`catalog_item_id`),
  ADD KEY `idx_list_position` (`featured_list_id`,`position`);

--
-- Indexes for table `interests`
--
ALTER TABLE `interests`
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
-- Indexes for table `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_items`
--
ALTER TABLE `list_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_members`
--
ALTER TABLE `list_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `list_members_list_id_user_id_unique` (`list_id`,`user_id`),
  ADD KEY `list_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `policies_slug_unique` (`slug`);

--
-- Indexes for table `segments`
--
ALTER TABLE `segments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sub_categories_slug_unique` (`slug`),
  ADD KEY `sub_categories_category_id_index` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- Indexes for table `user_consents`
--
ALTER TABLE `user_consents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_consents_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_data_export_requests`
--
ALTER TABLE `user_data_export_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_data_export_requests_user` (`user_id`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_devices_user_id_unique` (`user_id`),
  ADD KEY `user_devices_device_token_index` (`device_token`(768));

--
-- Indexes for table `user_interest`
--
ALTER TABLE `user_interest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_interest_user_id_foreign` (`user_id`),
  ADD KEY `user_interest_interest_id_foreign` (`interest_id`);

--
-- Indexes for table `user_list_positions`
--
ALTER TABLE `user_list_positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_list` (`user_id`,`list_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_list_id` (`list_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_profiles_user_id_unique` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_password_otps`
--
ALTER TABLE `admin_password_otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `app_versions`
--
ALTER TABLE `app_versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `campaign_segment`
--
ALTER TABLE `campaign_segment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `catalog_categories`
--
ALTER TABLE `catalog_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `catalog_items`
--
ALTER TABLE `catalog_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `catalog_tags`
--
ALTER TABLE `catalog_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `featured_item_bookmarks`
--
ALTER TABLE `featured_item_bookmarks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `featured_item_likes`
--
ALTER TABLE `featured_item_likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `featured_item_shares`
--
ALTER TABLE `featured_item_shares`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `featured_lists`
--
ALTER TABLE `featured_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `featured_list_items`
--
ALTER TABLE `featured_list_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `interests`
--
ALTER TABLE `interests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lists`
--
ALTER TABLE `lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `list_items`
--
ALTER TABLE `list_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=399;

--
-- AUTO_INCREMENT for table `list_members`
--
ALTER TABLE `list_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=554;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=435;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `segments`
--
ALTER TABLE `segments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `user_consents`
--
ALTER TABLE `user_consents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `user_data_export_requests`
--
ALTER TABLE `user_data_export_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_interest`
--
ALTER TABLE `user_interest`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;

--
-- AUTO_INCREMENT for table `user_list_positions`
--
ALTER TABLE `user_list_positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `catalog_items`
--
ALTER TABLE `catalog_items`
  ADD CONSTRAINT `fk_catalog_category` FOREIGN KEY (`category_id`) REFERENCES `catalog_categories` (`id`);

--
-- Constraints for table `user_consents`
--
ALTER TABLE `user_consents`
  ADD CONSTRAINT `user_consents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_data_export_requests`
--
ALTER TABLE `user_data_export_requests`
  ADD CONSTRAINT `fk_user_data_export_requests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_interest`
--
ALTER TABLE `user_interest`
  ADD CONSTRAINT `user_interest_interest_id_foreign` FOREIGN KEY (`interest_id`) REFERENCES `interests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_interest_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
