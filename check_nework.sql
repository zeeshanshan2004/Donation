-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2026 at 06:06 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `check_nework`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `remember_token`, `fcm_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@example.com', '$2y$10$c3nhr2ft0Y2MjarbRR5/C.uE1Ev1rW.7nRc0gccDivUGD464zK6Xm', NULL, 'ctW8K-4cHZXb2QhWFJbTMt:APA91bGwa2OyM1GAErKwmvwPKfcJxcw3JRKkhj54t_GwdsbnpCUY1Y6CQt_kncZ2kXEKzd1yg9ULSyouHVoPxAZqLR_V7qErghoCXdDtQSalAaCYoYELEBs', '2025-10-23 00:28:30', '2025-11-25 02:01:34');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(11, 'gef', 'gegt', '1765903945.jpg', 1, '2025-12-16 16:52:16', '2025-12-16 16:52:25'),
(15, 'testingfirst', 'testingfirsttestingfirst', '1765910709.webp', 1, '2025-12-16 18:45:09', '2025-12-16 18:45:09');

-- --------------------------------------------------------

--
-- Table structure for table `causes`
--

CREATE TABLE `causes` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `heading` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` decimal(12,2) NOT NULL DEFAULT '0.00',
  `raised` decimal(12,2) NOT NULL DEFAULT '0.00',
  `days_left` int DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `causes`
--

INSERT INTO `causes` (`id`, `category_id`, `title`, `heading`, `author`, `author_image`, `image`, `target`, `raised`, `days_left`, `description`, `is_featured`, `status`, `created_at`, `updated_at`, `featured`) VALUES
(4, NULL, 'pikas', 'pikass sadasdasd', 'pikasss', NULL, '1761330361.png', 5500.00, 840.00, 9, 'sadsad', 1, 1, '2025-10-25 01:26:01', '2026-01-08 14:33:56', 0),
(11, NULL, 'testtt', 'asdfas3', 'dsf', NULL, '1761700088.jpg', 556.00, 34.00, 4, 'dsf', 0, 1, '2025-10-29 08:08:08', '2025-11-04 01:34:50', 1),
(12, NULL, 'gfghghf', 'testapi', 'apitestcasue', NULL, '1761941352.jpg', 555.00, 500.00, 24, 'sdasdf', 0, 1, '2025-11-01 03:09:12', '2025-11-04 02:53:07', 0),
(13, NULL, 'gdsf', 'asdfas4sdf', 'sadasd3fdas', NULL, '1761941375.jpg', 344.00, 12.00, 22, 'asdf', 0, 1, '2025-11-01 03:09:35', '2025-11-04 02:45:25', 0),
(14, NULL, 'hasdh', 'dsf', 'sdfsd', NULL, '1762195682.jpg', 23.00, 12.00, 12, 'dsf', 0, 1, '2025-11-04 02:48:02', '2025-11-04 02:58:41', 0),
(17, 15, 'testingfirsttestingfirsttestingfirst', 'testtt', 'testtestingfirst', NULL, '1765910738.webp', 222.00, 123.00, 20, 'testingfirsttestingfirsttestingfirst', 0, 1, '2025-12-16 18:45:38', '2025-12-16 18:45:38', 0),
(18, 11, 'aaaa', '234', '123', NULL, '1765913078.jpg', 234.00, 234.00, 123, '1231', 0, 1, '2025-12-16 19:24:38', '2025-12-16 19:24:38', 0),
(19, 11, 'rgdg', 'cbvc', 'cb', '1765929501.jpg', '1765928361.jpg', 2342.00, 324.00, 34, 'bcvb vb', 0, 1, '2025-12-16 23:39:21', '2026-01-19 13:36:19', 0);

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `cause_id` bigint UNSIGNED NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usd',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `cause_id`, `transaction_id`, `stripe_payment_intent_id`, `amount`, `currency`, `status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 4, NULL, 'manual_1767897111_6924', 5000, 'usd', 'paid', '2026-01-08 13:31:51', '2026-01-08 13:31:51', '2026-01-08 13:31:51'),
(2, 1, 4, NULL, 'manual_1767897209_3283', 5000, 'usd', 'paid', '2026-01-08 13:33:29', '2026-01-08 13:33:29', '2026-01-08 13:33:29'),
(3, 1, 4, NULL, 'manual_1767897669_1607', 5000, 'usd', 'paid', '2026-01-08 13:41:09', '2026-01-08 13:41:09', '2026-01-08 13:41:09'),
(4, 1, 4, NULL, 'manual_1767899954_3081', 5000, 'usd', 'paid', '2026-01-08 14:19:14', '2026-01-08 14:19:14', '2026-01-08 14:19:14'),
(5, 1, 4, NULL, 'manual_1767899966_8228', 5000, 'usd', 'paid', '2026-01-08 14:19:26', '2026-01-08 14:19:26', '2026-01-08 14:19:26'),
(6, 1, 4, NULL, 'manual_1767900836_7447', 5000, 'usd', 'paid', '2026-01-08 14:33:56', '2026-01-08 14:33:56', '2026-01-08 14:33:56'),
(7, 1, 19, NULL, 'manual_1767901009_1246', 5000, 'usd', 'paid', '2026-01-08 14:36:49', '2026-01-08 14:36:49', '2026-01-08 14:36:49'),
(8, 1, 19, NULL, 'manual_1767901023_1661', 5000, 'usd', 'paid', '2026-01-08 14:37:03', '2026-01-08 14:37:03', '2026-01-08 14:37:03'),
(9, 1, 19, NULL, 'manual_1768842643_2443', 5000, 'usd', 'paid', '2026-01-19 12:10:43', '2026-01-19 12:10:43', '2026-01-19 12:10:43'),
(10, 1, 19, NULL, 'manual_1768847653_2167', 5000, 'usd', 'paid', '2026-01-19 13:34:14', '2026-01-19 13:34:14', '2026-01-19 13:34:14'),
(11, 1, 19, '1000', 'manual_1768847748_9936', 5000, 'usd', 'paid', '2026-01-19 13:35:48', '2026-01-19 13:35:48', '2026-01-19 13:35:48'),
(12, 1, 19, '4234234', 'manual_1768847779_9906', 5000, 'usd', 'paid', '2026-01-19 13:36:19', '2026-01-19 13:36:19', '2026-01-19 13:36:19');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `question` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'asdsa', 'asdasd', 1, '2025-10-23 01:43:40', '2025-10-23 01:43:40'),
(2, 'tttt', 'tttdesdfsdf', 1, '2025-10-25 02:06:54', '2025-10-25 02:06:54'),
(3, 'sdcsc', 'sdcsdc', 1, '2025-12-15 23:29:10', '2025-12-15 23:29:10'),
(4, 'hey', 'hello', 1, '2025-12-16 17:55:32', '2025-12-16 17:55:32'),
(5, 'test', 'testing11', 1, '2025-12-16 17:58:47', '2025-12-16 17:58:47'),
(6, 'hey test11', 'hey test222', 1, '2025-12-16 18:45:53', '2025-12-16 18:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `fcm_tokens`
--

CREATE TABLE `fcm_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `device` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kyc_submissions`
--

CREATE TABLE `kyc_submissions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `country_of_residence` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_legal_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `residential_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_id_type` enum('passport','drivers_license','greencard','visa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_id_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `face_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_agreement_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `agreement_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kyc_submissions`
--

INSERT INTO `kyc_submissions` (`id`, `user_id`, `country_of_residence`, `full_legal_name`, `date_of_birth`, `residential_address`, `photo_id_type`, `photo_id_path`, `face_photo_path`, `is_agreement_confirmed`, `agreement_path`, `status`, `reviewed_by`, `reviewed_at`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 25, '', 'tofil', '2008-02-13', '', 'passport', '', '', 0, NULL, 'approved', NULL, '2026-02-13 15:42:25', NULL, '2026-02-13 15:26:56', '2026-02-13 15:42:25'),
(2, 26, 'pk', 'tofil1231111q', '2012-12-12', 'NY', 'passport', 'kyc/photo_id_26_1771015770.jpg', 'kyc/face_photo_26_1771015770.jpg', 0, NULL, 'pending', NULL, NULL, NULL, '2026-02-13 15:43:01', '2026-02-13 15:49:30'),
(3, 27, '', 'tofil', '2008-02-13', '', 'passport', '', '', 0, NULL, 'pending', NULL, NULL, NULL, '2026-02-13 15:58:26', '2026-02-13 15:58:26'),
(4, 28, 'pk', 'tofil1231111q', '2012-12-12', 'NY', 'passport', 'kyc/photo_id_28_1771021068.jpg', 'kyc/face_photo_28_1771021068.jpg', 0, NULL, 'pending', NULL, NULL, NULL, '2026-02-13 17:12:41', '2026-02-13 17:17:48'),
(5, 29, 'pk', 'tofil1231111q', '2012-12-12', 'NY', 'passport', 'kyc/photo_id_29_1771021435.jpg', 'kyc/face_photo_29_1771021435.jpg', 0, NULL, 'pending', NULL, NULL, NULL, '2026-02-13 17:21:38', '2026-02-13 17:23:55'),
(6, 30, 'pk', 'tofil1231111q', '2012-12-12', 'NY', 'passport', 'kyc/photo_id_30_1771022553.jpg', 'kyc/face_photo_30_1771022553.jpg', 0, NULL, 'approved', NULL, '2026-02-13 17:47:25', NULL, '2026-02-13 17:27:25', '2026-02-13 17:47:25'),
(7, 31, 'pk', 'tofil1231111q', '2012-12-12', 'NY', 'passport', 'kyc/photo_id_31_1771025393.jpg', 'kyc/face_photo_31_1771025393.jpg', 0, NULL, 'approved', NULL, '2026-02-13 18:30:38', NULL, '2026-02-13 18:26:34', '2026-02-13 18:30:38'),
(8, 1, 'US', 'John Doe', '1990-01-01', '123 Main St', 'passport', 'path/1.jpg', 'path/2.jpg', 1, NULL, 'pending', NULL, NULL, NULL, '2026-03-16 13:32:39', '2026-03-16 13:33:15'),
(9, 32, 'pk', 'tofil1231111q', '2012-12-12', 'NY', 'passport', 'kyc/photo_id_32_1773687478.jpg', 'kyc/face_photo_32_1773687478.jpg', 1, NULL, 'pending', NULL, NULL, NULL, '2026-03-16 13:36:35', '2026-03-16 14:01:04'),
(10, 33, 'pk', 'tofil1231111q', '2012-12-12', 'NY', 'passport', 'kyc/photo_id_33_1773691895.jpg', 'kyc/face_photo_33_1773691895.jpg', 1, 'kyc/agreement_33_1773692358.jpg', 'approved', NULL, '2026-03-16 15:20:13', NULL, '2026-03-16 15:05:09', '2026-03-16 15:20:13'),
(11, 34, 'pk', 'tofil1231111q', '2012-12-12', 'NY', 'passport', 'kyc/photo_id_34_1773692541.jpg', 'kyc/face_photo_34_1773692541.jpg', 1, 'kyc/agreement_34_1773692580.jpg', 'approved', NULL, '2026-03-16 15:23:22', NULL, '2026-03-16 15:20:51', '2026-03-16 15:23:22'),
(12, 35, '', 'tofil', '2008-03-16', '', 'passport', '', '', 0, NULL, 'pending', NULL, NULL, NULL, '2026-03-16 17:01:19', '2026-03-16 17:01:19'),
(13, 36, 'pk', 'tofil1231111q', '2012-12-12', 'NY', 'passport', 'kyc/photo_id_36_1773699138.jpg', 'kyc/face_photo_36_1773699139.jpg', 1, 'kyc/agreement_36_1773699176.jpg', 'approved', NULL, '2026-03-16 17:13:27', NULL, '2026-03-16 17:07:18', '2026-03-16 17:13:27');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `receiver_id` bigint UNSIGNED NOT NULL,
  `sender_type` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `sender_type`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(110, 1, 19, 'admin', 'hey testing check', 0, '2025-12-16 18:46:22', '2025-12-16 18:46:22'),
(111, 1, 1, 'admin', 'Hi', 0, '2025-12-16 22:39:16', '2025-12-16 22:39:16'),
(112, 1, 1, 'admin', 'hey', 0, '2026-02-19 15:01:55', '2026-02-19 15:01:55'),
(113, 31, 1, 'user', 'test', 1, '2026-02-19 15:05:37', '2026-02-19 16:01:22'),
(114, 31, 1, 'user', 'dd', 1, '2026-02-19 15:33:46', '2026-02-19 16:01:22'),
(115, 31, 1, 'user', 'hello', 1, '2026-02-19 15:59:23', '2026-02-19 16:01:22'),
(116, 31, 1, 'user', 'ping', 1, '2026-02-19 15:59:49', '2026-02-19 16:01:22'),
(117, 31, 1, 'user', 'null', 1, '2026-02-19 16:01:37', '2026-02-19 16:01:38'),
(118, 31, 1, 'user', 'test', 1, '2026-02-19 16:22:25', '2026-02-19 16:22:32'),
(119, 31, 1, 'user', 'test', 1, '2026-02-19 16:23:59', '2026-02-19 16:24:13'),
(120, 31, 1, 'user', 'new', 1, '2026-02-19 16:24:04', '2026-02-19 16:24:13'),
(121, 1, 31, 'admin', 'hello', 1, '2026-02-19 16:24:20', '2026-02-19 16:24:23'),
(122, 31, 1, 'user', 'hello', 1, '2026-02-19 16:38:42', '2026-02-19 16:38:47'),
(123, 31, 1, 'user', 'hello from postman', 1, '2026-02-19 16:41:37', '2026-02-19 16:41:44'),
(124, 1, 31, 'admin', 'aa', 1, '2026-02-19 16:44:48', '2026-02-19 16:45:43'),
(125, 31, 1, 'user', 'hello from postman again', 1, '2026-02-19 16:54:32', '2026-02-19 16:54:51'),
(126, 31, 1, 'user', 'hello from postman again pp', 1, '2026-02-19 17:12:21', '2026-02-19 17:12:45'),
(127, 1, 31, 'admin', 'ok', 1, '2026-02-19 17:13:20', '2026-02-19 17:15:45'),
(128, 1, 31, 'admin', 'ok', 1, '2026-02-19 17:13:24', '2026-02-19 17:15:45'),
(129, 1, 31, 'admin', 'ok', 1, '2026-02-19 17:13:36', '2026-02-19 17:15:45'),
(130, 31, 1, 'user', 'hello from postman again pasdasdasdp', 1, '2026-02-19 19:05:54', '2026-02-19 19:06:05'),
(131, 31, 1, 'user', 'hello from postman again pasdasdasdp', 1, '2026-02-19 19:06:14', '2026-02-19 19:06:16'),
(132, 31, 1, 'user', 'hello from postman again new', 1, '2026-02-19 19:06:27', '2026-02-19 19:06:30'),
(133, 1, 31, 'admin', 'hello', 1, '2026-02-19 19:06:39', '2026-02-19 19:06:58'),
(134, 1, 31, 'admin', 'hello', 1, '2026-02-19 19:06:45', '2026-02-19 19:06:58'),
(135, 1, 31, 'admin', 'sdsd', 0, '2026-02-19 19:08:04', '2026-02-19 19:08:04'),
(136, 1, 31, 'admin', 'sdsd', 0, '2026-02-19 19:08:11', '2026-02-19 19:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(12, '2014_10_12_000000_create_users_table', 1),
(13, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(14, '2019_08_19_000000_create_failed_jobs_table', 1),
(15, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(17, '2025_10_20_185341_add_nickname_country_gender_to_users_table', 1),
(18, '2025_10_21_172312_create_admins_table', 1),
(19, '2025_10_21_174450_create_faqs_table', 1),
(20, '2025_10_21_181241_create_categories_table', 1),
(21, '2025_10_22_171259_create_terms_conditions_table', 1),
(22, '2025_10_22_171823_create_terms_conditions_table', 1),
(23, '2025_10_24_172911_create_causes_table', 2),
(24, '2025_10_28_185204_add_is_featured_to_causes_table', 3),
(25, '2025_10_28_185721_add_featured_and_status_to_causes_table', 4),
(28, '2025_10_28_191903_add_is_featured_to_causes_table', 5),
(29, '2025_10_28_213624_add_description_to_causes_table', 5),
(30, '2025_11_03_181145_add_category_id_to_causes_table', 6),
(31, '2025_11_03_225639_create_user_notification_settings_table', 7),
(32, '2025_11_04_195247_create_messages_table', 8),
(33, '2025_11_04_230909_add_fcm_token_to_users_table', 9),
(34, '2025_11_06_191746_create_fcm_tokens_table', 10),
(35, '2025_11_06_203424_create_chats_table', 10),
(36, '2025_11_13_194027_add_role_to_users_table', 11),
(37, '2025_11_13_195430_add_role_to_users_table', 12),
(38, '2025_11_14_234402_create_conversations_table', 13),
(39, '2025_11_14_234408_create_messages_table', 13),
(40, '2025_11_14_234414_create_user_devices_table', 13),
(41, '2025_11_17_201724_create_messages_table', 14),
(42, '2025_12_29_183144_create_donations_table', 15),
(44, '2026_01_19_175859_add_transaction_id_to_donations_table', 16),
(45, '2026_02_13_183915_create_kyc_submissions_table', 17),
(46, '2026_02_13_183917_add_kyc_status_to_users_table', 17),
(47, '2026_02_13_202032_change_kyc_status_to_boolean_in_users_table', 18),
(48, '2026_02_13_210213_update_kyc_file_paths_to_storage', 19),
(49, '2026_02_17_235511_create_packages_table', 20),
(50, '2026_02_18_005736_add_tax_percentage_to_packages_table', 21),
(51, '2026_02_19_195947_add_is_read_to_messages_table', 22),
(52, '2026_02_23_201253_create_package_orders_table', 23),
(53, '2026_03_16_182903_add_agreement_confirmed_to_kyc_submissions_table', 24),
(54, '2026_03_16_191242_add_agreement_path_to_kyc_submissions', 25),
(55, '2026_03_16_211625_add_referral_and_payment_fields_to_users_table', 26),
(56, '2026_03_16_211625_create_settings_table', 26);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `referral_required` int NOT NULL,
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT '6.00',
  `community_share` decimal(15,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `amount`, `referral_required`, `tax_percentage`, `community_share`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Entry Plan', 25.00, 13, 6.00, 10.00, 1, '2026-02-17 20:02:12', '2026-02-17 20:02:12');

-- --------------------------------------------------------

--
-- Table structure for table `package_orders`
--

CREATE TABLE `package_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `progress` int NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `package_orders`
--

INSERT INTO `package_orders` (`id`, `user_id`, `package_id`, `amount`, `progress`, `status`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100.00, 13, 'completed', '2026-02-23 17:13:21', '2026-02-23 16:31:58', '2026-02-23 17:13:21'),
(2, 2, 1, 25.00, 0, 'active', NULL, '2026-02-23 16:56:43', '2026-02-23 16:56:43'),
(3, 3, 1, 25.00, 0, 'active', NULL, '2026-02-23 17:13:21', '2026-02-23 17:13:21');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'signup_fee', '50', '2026-03-16 16:37:24', '2026-03-16 16:37:24'),
(2, 'referral_discount_percentage', '10', '2026-03-16 16:37:24', '2026-03-16 16:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `terms_conditions`
--

CREATE TABLE `terms_conditions` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terms_conditions`
--

INSERT INTO `terms_conditions` (`id`, `title`, `content`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Terms & Conditions', '<p style=\"color: rgb(21, 21, 21); font-family: system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 14px; background-color: rgb(237, 241, 243);\">new test completely understand your frustration regarding the previous link and the back-and-forth on changes. I want to clarify that all the updates you requested — including content, styling, and the most recent changes — have now been fully implemented. Please note that these changes are available on the new updated link:&nbsp;<noindex><a href=\"https://checkyourproject.info/angela-harvey/supporting_potential/\" target=\"_blank\" rel=\"nofollow\" style=\"color: rgb(32, 102, 176); text-decoration-line: none; transition: border-bottom-color 0.3s linear; border-bottom: 1px solid transparent;\">https://checkyourproject.info/angela-harvey/supporting_potential/</a></noindex></p><p style=\"color: rgb(21, 21, 21); font-family: system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 14px; background-color: rgb(237, 241, 243);\">The previous link:&nbsp;<noindex><a href=\"https://checkyourproject.info/angela-harvey/web/\" target=\"_blank\" rel=\"nofollow\" style=\"color: rgb(32, 102, 176); text-decoration-line: none; transition: border-bottom-color 0.3s linear; border-bottom: 1px solid transparent;\">https://checkyourproject.info/angela-harvey/web/</a></noindex>&nbsp;was the older version, which is why some updates were not visible there. All edits, adjustments, and new content have been correctly reflected on the new link. You can review everything there, including the LMS sticky header, YouTube clip upload, and all style/content changes. Kindly use this new link for all future review and feedback. This will ensure you see the latest and correct version of the website.&nbsp;</p><p style=\"color: rgb(21, 21, 21); font-family: system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 14px; background-color: rgb(237, 241, 243);\">I also noticed the section with the heading “Replace with” in the screenshot you shared. Could you please confirm&nbsp;<span style=\"font-weight: 600;\">where exactly you want these red bullet points to be placed</span>&nbsp;on the page?I completely understand your frustration regarding the previous link and the back-and-forth on changes. I want to clarify that all the updates you requested — including content, styling, and the most recent changes — have now been fully implemented. Please note that these changes are available on the new updated link:&nbsp;<noindex><a href=\"https://checkyourproject.info/angela-harvey/supporting_potential/\" target=\"_blank\" rel=\"nofollow\" style=\"color: rgb(32, 102, 176); text-decoration-line: none; transition: border-bottom-color 0.3s linear; border-bottom: 1px solid transparent;\">https://checkyourproject.info/angela-harvey/supporting_potential/</a></noindex><br></p><p style=\"color: rgb(21, 21, 21); font-family: system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 14px; background-color: rgb(237, 241, 243);\">The previous link:&nbsp;<noindex><a href=\"https://checkyourproject.info/angela-harvey/web/\" target=\"_blank\" rel=\"nofollow\" style=\"color: rgb(32, 102, 176); text-decoration-line: none; transition: border-bottom-color 0.3s linear; border-bottom: 1px solid transparent;\">https://checkyourproject.info/angela-harvey/web/</a></noindex>&nbsp;was the older version, which is why some updates were not visible there. All edits, adjustments, and new content have been correctly reflected on the new link. You can review everything there, including the LMS sticky header, YouTube clip upload, and all style/content changes. Kindly use this new link for all future review and feedback. This will ensure you see the latest and correct version of the website.</p><p style=\"color: rgb(21, 21, 21); font-family: system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 14px; background-color: rgb(237, 241, 243);\">I also noticed the section with the heading “Replace with” in the screenshot you shared. Could you please confirm&nbsp;<span style=\"font-weight: 600;\">where exactly you want these red bullet points to be placed</span>&nbsp;on the page?I completely understand your frustration regarding the previous link and the back-and-forth on changes. I want to clarify that all the updates you requested — including content, styling, and the most recent changes — have now been fully implemented. Please note that these changes are available on the new updated link:&nbsp;<noindex><a href=\"https://checkyourproject.info/angela-harvey/supporting_potential/\" target=\"_blank\" rel=\"nofollow\" style=\"color: rgb(32, 102, 176); text-decoration-line: none; transition: border-bottom-color 0.3s linear; border-bottom: 1px solid transparent;\">https://checkyourproject.info/angela-harvey/supporting_potential/</a></noindex><br></p><p style=\"color: rgb(21, 21, 21); font-family: system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 14px; background-color: rgb(237, 241, 243);\">The previous link:&nbsp;<noindex><a href=\"https://checkyourproject.info/angela-harvey/web/\" target=\"_blank\" rel=\"nofollow\" style=\"color: rgb(32, 102, 176); text-decoration-line: none; transition: border-bottom-color 0.3s linear; border-bottom: 1px solid transparent;\">https://checkyourproject.info/angela-harvey/web/</a></noindex>&nbsp;was the older version, which is why some updates were not visible there. All edits, adjustments, and new content have been correctly reflected on the new link. You can review everything there, including the LMS sticky header, YouTube clip upload, and all style/content changes. Kindly use this new link for all future review and feedback. This will ensure you see the latest and correct version of the website.</p><p style=\"color: rgb(21, 21, 21); font-family: system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Ubuntu, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 14px; background-color: rgb(237, 241, 243);\">I also noticed the section with the heading “Replace with” in the screenshot you shared. Could you please confirm&nbsp;<span style=\"font-weight: 600;\">where exactly you want these red bullet points to be placed</span>&nbsp;on the page?<br></p>', 1, '2025-10-23 00:35:27', '2025-12-16 18:46:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `kyc_status` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fcm_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by` bigint UNSIGNED DEFAULT NULL,
  `referral_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `nickname`, `country`, `gender`, `email`, `phone`, `role`, `password`, `otp`, `is_verified`, `kyc_status`, `remember_token`, `created_at`, `updated_at`, `fcm_token`, `referred_by`, `referral_code`, `is_paid`) VALUES
(1, 'xigala', NULL, NULL, NULL, 'daku@memeazon.com', NULL, 'user', '$2y$12$Wzg3R5jA4JVD2iJ0Cek7IevvfbaGWlTM9xZixgYDIEBpNRTrhebr.', NULL, 1, 0, NULL, '2025-11-04 01:28:57', '2025-11-04 01:31:17', NULL, NULL, NULL, 0),
(2, 'xigala', NULL, NULL, NULL, 'daku@memeazons.com', NULL, 'user', '$2y$12$64uNIEmSlXO2Oj7XbPGjm.W8O0UrLv1DoKJngh0R.RZ5EFqfzOBmy', NULL, 1, 0, NULL, '2025-11-04 07:24:24', '2025-11-04 07:25:20', NULL, NULL, NULL, 0),
(3, 'jojo', NULL, NULL, NULL, 'jojo123@yopmail.com', NULL, 'user', '$2y$12$vKIFRPFHrtHGyWjF6hKJ6eWVnrkkHg5HxcxPcXYJJJPdCv52nXbSK', NULL, 1, 0, NULL, '2025-11-11 04:01:03', '2025-12-15 16:59:22', 'ctW8K-4cHZXb2QhWFJbTMt:APA91bEYjx6tDyFYH_4txMlsUaWLTVFmJD8T9iABTBKpasBT5dD90quSMa6Wj-uMcxoB2Ghg8yKaCp41MxnJncJ-PVlb9Y19CLqwW7BBxTmzrPR2cY8abA4', NULL, NULL, 0),
(4, 'Test User', NULL, NULL, NULL, 'testuser@example.com', NULL, 'user', '$2y$12$3FG.rCH5Dl/yj..sh.Xbdejy4eX75C1dEBMBY0q6gInD35kTrD7uu', NULL, 1, 0, NULL, '2025-11-11 07:09:02', '2025-11-11 07:09:02', NULL, NULL, NULL, 0),
(6, 'tofil', NULL, NULL, NULL, 'tofil@gmail.com', NULL, 'user', '$2y$12$tsjq5YMTH6PT8BVd6Geev.IdZz9splkn7Q1rpJJW4PPwu/zh4n1SW', '3918', 0, 0, NULL, '2025-11-27 09:54:16', '2025-11-27 09:54:16', NULL, NULL, NULL, 0),
(7, 'test', NULL, NULL, NULL, 'test@gmail.com', NULL, 'user', '$2y$12$oyQc456zsRrEqFoyrBmZAOL6jhQKJ7kfoOQ2FkkFcso6FwQdYlHlS', '8069', 0, 0, NULL, '2025-11-27 16:05:50', '2025-11-27 16:05:50', NULL, NULL, NULL, 0),
(8, 'test', NULL, NULL, NULL, 'test12@gmail.com', NULL, 'user', '$2y$12$dCbEbNNYRKaYhX6pQdq8ouJ4fFzwglRcFbMVwKd7tojeOiIV9Y3wS', '8857', 0, 0, NULL, '2025-11-27 17:24:40', '2025-11-27 17:24:40', NULL, NULL, NULL, 0),
(9, 'test299', NULL, NULL, NULL, 'test299@gmail.com', NULL, 'user', '$2y$12$p29/4GxlMP0zoFnjLE07Dujir/RdvpfFlBlhmG.rbfXJpWhZTidsy', '5099', 0, 0, NULL, '2025-11-28 14:36:05', '2025-11-28 14:36:05', NULL, NULL, NULL, 0),
(10, 'bnbnjb', NULL, NULL, NULL, 'hnh@gmail.com', NULL, 'user', '$2y$12$WFl4Bromrf3yHUaR4cIwM.f5zHNJlPg48y.MI6pJArE6iWs/JLpn2', '3165', 0, 0, NULL, '2025-11-28 14:39:25', '2025-11-28 14:39:25', NULL, NULL, NULL, 0),
(11, 'bnbnjb', NULL, NULL, NULL, 'dennoyoquaki-2805@yopmail.com', NULL, 'user', '$2y$12$9CgGfqooTeWz2vTwiytiBecYobCcnnXy7AZ.EsO1gfTWs3ChTIxx6', NULL, 1, 0, NULL, '2025-11-28 14:47:04', '2025-11-28 14:47:41', NULL, NULL, NULL, 0),
(12, 'test1234', NULL, NULL, NULL, 'test1234@yopmail.com', NULL, 'user', '$2y$12$IPucH/fzUNHTnFgvzKN5jeRakVNjMdax5gjX88k3Kjetj0hnYkDZG', NULL, 1, 0, NULL, '2025-12-01 14:47:41', '2025-12-01 14:54:33', NULL, NULL, NULL, 0),
(13, 'test1234', NULL, NULL, NULL, 'test12344@yopmail.com', NULL, 'user', '$2y$12$LawQ2fCBhznh7Z4BpGUrXOQOIhT2PlaSOKntFofCs08EYvsyoWf36', NULL, 1, 0, NULL, '2025-12-01 14:55:42', '2025-12-01 14:56:01', NULL, NULL, NULL, 0),
(14, 'test1234', NULL, NULL, NULL, 'test127344@yopmail.com', NULL, 'user', '$2y$12$khZXfrRO2V/IXcksm5vZ8O3zKScz8KIlwbtYOHMQ/4JQbTInWCO6e', NULL, 1, 0, NULL, '2025-12-01 14:58:33', '2025-12-01 14:58:53', NULL, NULL, NULL, 0),
(15, 'test1234', NULL, NULL, NULL, 'test12734488@yopmail.com', NULL, 'user', '$2y$12$T2s1XkPN1mt9etUjj6qPT.ByhCLlIdrULGSuXLeTPLhb9i5DKFSZa', NULL, 1, 0, NULL, '2025-12-01 15:06:02', '2025-12-01 15:06:21', NULL, NULL, NULL, 0),
(16, 'test299', NULL, NULL, NULL, 'test488@yopmail.com', NULL, 'user', '$2y$12$v1P8LmhdMmhSwkcYOGdgBu98.rqv7bzwW7HCIuGULAJ7eTuu7WLre', '1104', 1, 0, NULL, '2025-12-01 15:32:56', '2025-12-04 18:05:41', NULL, NULL, NULL, 0),
(17, 'logout', NULL, NULL, NULL, 'logout@yopmail.com', NULL, 'user', '$2y$12$q0.Vh4LUJP6lviKsIrAXBOHUa3amSwoSGQp0s.xxuwVPU.RQqSOJe', NULL, 1, 0, NULL, '2025-12-01 16:29:58', '2025-12-01 16:30:17', NULL, NULL, NULL, 0),
(19, 'tofil', NULL, NULL, NULL, 'tofil1234@gmail.com', NULL, 'user', '$2y$12$URwCSXnhYZ4teY6zhPPnd.a57axJRrrrRarbGyDy2rapuYxI2iYYy', '7526', 0, 0, NULL, '2025-12-16 17:30:14', '2025-12-16 17:30:14', NULL, NULL, NULL, 0),
(20, 'checkingtestuser5658', 'checking user5609', 'United States', 'female', 'testuser@yopmail.com', '234-678-09', 'user', '$2y$12$mRKtOvcSTAabPO4U/cpSNeuZboRupxLsEaRDvIjPQNHsQzl.pkf4G', NULL, 1, 0, NULL, '2025-12-16 18:48:03', '2025-12-18 18:36:07', '3423423534564', NULL, NULL, 0),
(21, 'tofil', NULL, NULL, NULL, 'kelsauser1@yopmail.com', NULL, 'user', '$2y$12$Iezfco/9l4UPRT2ZSe85x.oa7iGMj0IXI29gJVU2wRNzY147whjIi', NULL, 1, 0, NULL, '2025-12-16 23:24:04', '2025-12-16 23:26:41', NULL, NULL, NULL, 0),
(22, 'tofil', NULL, NULL, NULL, 'tofil123@gmail.com', NULL, 'user', '$2y$12$eye5QYiVfJolggQMDmuD8u0XUo/2f/6PVB8vI5sLZGimJHFgGx50u', '1982', 0, 0, NULL, '2026-01-07 12:29:46', '2026-01-07 12:29:46', NULL, NULL, NULL, 0),
(23, 'tofil', NULL, NULL, NULL, 'tofil1231@gmail.com', NULL, 'user', '$2y$12$szWxdCKerpXWrAn9Ph/4deUe/WJQ0c8ba6xbzDr9YKbcfg.voAbCO', '5441', 0, 0, NULL, '2026-02-13 14:34:39', '2026-02-13 14:34:39', NULL, NULL, NULL, 0),
(24, 'tofil', NULL, NULL, NULL, 'tofil12311@gmail.com', NULL, 'user', '$2y$12$aJIvxuRTtXTO4YNh97epbu/hEKYmhfyh77/IVqgB2XKsiLAZIi7Zi', NULL, 1, 0, NULL, '2026-02-13 14:37:46', '2026-02-13 14:40:35', NULL, NULL, NULL, 0),
(25, 'tofil', NULL, NULL, NULL, 'tofil1231111@gmail.com', NULL, 'user', '$2y$12$kKl3G/chP9Qymi35gHl98Og2/hAbyY0fvUjD5SSdUkS1v73P59.qO', NULL, 1, 1, NULL, '2026-02-13 15:26:56', '2026-02-13 15:42:25', NULL, NULL, NULL, 0),
(26, 'tofil', NULL, NULL, NULL, 'tofil1231111q@gmail.com', NULL, 'user', '$2y$12$AULMaN8GLuG796DvF3ij1.5Ej6zTc4.PlsaDRxyqVIPMluowEUhCO', NULL, 1, 0, NULL, '2026-02-13 15:43:01', '2026-02-13 15:44:28', NULL, NULL, NULL, 0),
(27, 'tofil', NULL, NULL, NULL, 'tofil1231111qa@gmail.com', NULL, 'user', '$2y$12$lXr2dnkx1aQKaIeDB4cuc.5khuuYWcxVHiW5QUEae9YEVJwlDLrVS', '9612', 0, 0, NULL, '2026-02-13 15:58:26', '2026-02-13 15:58:26', NULL, NULL, NULL, 0),
(28, 'tofil', NULL, NULL, NULL, 'tofil1231111qq@gmail.com', NULL, 'user', '$2y$12$vd4uSznElSpsYUHWQQ65H.mHQHokhSblcUFS.r3lrksOCmX9tH0.G', NULL, 1, 0, NULL, '2026-02-13 17:12:41', '2026-02-13 17:15:49', NULL, NULL, NULL, 0),
(29, 'tofil', NULL, NULL, NULL, 'tofil1231111qqq@gmail.com', NULL, 'user', '$2y$12$RSol6k4n6j14bZdmxpy4IuFjISqH9foA8xYTC0wCK.y4Ju8aMOP8G', NULL, 1, 0, NULL, '2026-02-13 17:21:38', '2026-02-13 17:23:11', NULL, NULL, NULL, 0),
(30, 'tofil', NULL, NULL, NULL, 'tofil1231111qqqq@gmail.com', NULL, 'user', '$2y$12$GomOe6Ruoq2D2s9zkwede.xc2myLy/nFz0bXE5h.41MW7.qgLHhcy', NULL, 1, 1, NULL, '2026-02-13 17:27:25', '2026-02-13 17:47:25', NULL, NULL, NULL, 0),
(31, 'tofil', NULL, NULL, NULL, 'tofil1231111qqqqq@gmail.com', NULL, 'user', '$2y$12$bwjKVzeTjFSTKcr3EiDNpuefKZe3HbTj7tjBykTjYzsrI0LBJFdI6', NULL, 1, 1, NULL, '2026-02-13 18:26:34', '2026-02-13 18:30:38', NULL, NULL, NULL, 0),
(32, 'tofil', NULL, NULL, NULL, 'tofil1231111qqaqqqaaaa@gmail.com', NULL, 'user', '$2y$12$uqQZ5DaOUkxcyM.fPSSmo.x3otAJ..2QC5/uzrnj7wx6BXwXIWmWW', NULL, 1, 0, NULL, '2026-03-16 13:36:35', '2026-03-16 13:39:19', NULL, NULL, NULL, 0),
(33, 'tofil', NULL, NULL, NULL, 'a@gmail.com', NULL, 'user', '$2y$12$6XxUYTlP4d9MZQ0wwfLwm.CcjyHL1Boz48bvGnvvXkVJdz83XUYGm', NULL, 1, 1, NULL, '2026-03-16 15:05:09', '2026-03-16 15:20:13', NULL, NULL, NULL, 0),
(34, 'tofil', NULL, NULL, NULL, 'b@gmail.com', NULL, 'user', '$2y$12$axhHLIYyyz/EwCG98WK8mO6KKo/s7c1dxXQnqAvqO6yBqJ8SMWZmW', NULL, 1, 1, NULL, '2026-03-16 15:20:51', '2026-03-16 15:23:22', NULL, NULL, NULL, 0),
(35, 'tofil', NULL, NULL, NULL, 'ba@gmail.com', NULL, 'user', '$2y$12$B//3dRpE6KwkE7FRaAtsnu3G7mUeJvtAcRV7bmMv/NQKX3p7k4gaq', NULL, 1, 0, NULL, '2026-03-16 17:01:19', '2026-03-16 17:05:29', NULL, NULL, 'REF-1A701A', 1),
(36, 'tofil', NULL, NULL, NULL, 'c@gmail.com', NULL, 'user', '$2y$12$YY2Sc3frEgwjXVtHbY4CROocQ6Z1z5q6SDS31mf7IfXWB7bmtcjHu', NULL, 1, 1, NULL, '2026-03-16 17:07:18', '2026-03-16 17:13:27', NULL, 35, 'REF-89EEAC', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `device_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notification_settings`
--

CREATE TABLE `user_notification_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `email_notifications` tinyint(1) NOT NULL DEFAULT '0',
  `push_notifications` tinyint(1) NOT NULL DEFAULT '0',
  `special_announcements` tinyint(1) NOT NULL DEFAULT '0',
  `blessing_updates` tinyint(1) NOT NULL DEFAULT '0',
  `community_announcements` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_notification_settings`
--

INSERT INTO `user_notification_settings` (`id`, `user_id`, `email_notifications`, `push_notifications`, `special_announcements`, `blessing_updates`, `community_announcements`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, 1, 0, '2025-11-04 07:34:41', '2025-11-05 02:33:39'),
(3, 20, 0, 0, 0, 0, 1, '2025-12-18 13:41:36', '2025-12-19 18:23:41'),
(4, 21, 1, 1, 1, 1, 1, '2025-12-18 17:03:30', '2025-12-19 18:22:05');

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `causes`
--
ALTER TABLE `causes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `causes_category_id_foreign` (`category_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_user_id_foreign` (`user_id`),
  ADD KEY `conversations_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `donations_stripe_payment_intent_id_unique` (`stripe_payment_intent_id`),
  ADD KEY `donations_user_id_foreign` (`user_id`),
  ADD KEY `donations_cause_id_foreign` (`cause_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fcm_tokens_token_unique` (`token`),
  ADD KEY `fcm_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `kyc_submissions`
--
ALTER TABLE `kyc_submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kyc_submissions_user_id_unique` (`user_id`),
  ADD KEY `kyc_submissions_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_orders`
--
ALTER TABLE `package_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_orders_user_id_foreign` (`user_id`),
  ADD KEY `package_orders_package_id_foreign` (`package_id`);

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
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  ADD KEY `users_referred_by_foreign` (`referred_by`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_devices_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_notification_settings`
--
ALTER TABLE `user_notification_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_notification_settings_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `causes`
--
ALTER TABLE `causes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kyc_submissions`
--
ALTER TABLE `kyc_submissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `package_orders`
--
ALTER TABLE `package_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_notification_settings`
--
ALTER TABLE `user_notification_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `causes`
--
ALTER TABLE `causes`
  ADD CONSTRAINT `causes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_cause_id_foreign` FOREIGN KEY (`cause_id`) REFERENCES `causes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kyc_submissions`
--
ALTER TABLE `kyc_submissions`
  ADD CONSTRAINT `kyc_submissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kyc_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_orders`
--
ALTER TABLE `package_orders`
  ADD CONSTRAINT `package_orders_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_referred_by_foreign` FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD CONSTRAINT `user_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_notification_settings`
--
ALTER TABLE `user_notification_settings`
  ADD CONSTRAINT `user_notification_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
