-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 27, 2025 at 03:57 AM
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
-- Database: `im_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` int(11) NOT NULL,
  `activity_name` varchar(200) NOT NULL COMMENT '活動名稱',
  `activity_type` enum('competition','activity','workshop','seminar') NOT NULL COMMENT '活動類型',
  `description` text DEFAULT NULL COMMENT '活動描述',
  `start_date` date NOT NULL COMMENT '活動開始日期',
  `end_date` date DEFAULT NULL COMMENT '活動結束日期',
  `registration_start` datetime NOT NULL COMMENT '報名開始時間',
  `registration_end` datetime NOT NULL COMMENT '報名截止時間',
  `modify_deadline` datetime DEFAULT NULL COMMENT '修改截止時間',
  `max_participants` int(11) DEFAULT NULL COMMENT '人數上限',
  `current_participants` int(11) DEFAULT 0 COMMENT '目前報名人數',
  `location` varchar(200) DEFAULT NULL COMMENT '地點',
  `requirements` text DEFAULT NULL COMMENT '參加要求',
  `status` enum('draft','published','closed','cancelled') DEFAULT 'draft' COMMENT '狀態',
  `created_by` varchar(50) DEFAULT NULL COMMENT '建立者',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='活動/比賽';

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `activity_name`, `activity_type`, `description`, `start_date`, `end_date`, `registration_start`, `registration_end`, `modify_deadline`, `max_participants`, `current_participants`, `location`, `requirements`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '2025 科學探索營', 'activity', '透過實驗和探索，讓學生體驗科學的樂趣', '2025-02-01', '2025-02-05', '2025-01-01 00:00:00', '2025-01-25 00:00:00', NULL, 30, 1, '科學館 3F 實驗室', NULL, 'published', 'admin', '2025-10-19 04:33:10', '2025-10-21 02:46:15'),
(2, '2025 化學營', 'activity', '透過實驗和探索，讓學生體驗科學的樂趣', '2025-10-20', '2025-10-22', '2025-09-20 11:37:17', '2025-10-05 11:37:17', NULL, 50, 3, '化學實驗室', NULL, 'published', NULL, '2025-10-21 03:40:00', '2025-10-21 03:43:31'),
(94, '校園程式設計大賽', 'competition', '培養學生程式邏輯思維', '2025-10-15', '2025-10-15', '2025-10-05 00:00:00', '2025-10-14 23:59:59', '2025-10-13 23:59:59', 40, 70, '電腦教室', '需具備基礎程式能力', 'closed', 'teacher001', '2025-10-01 02:00:00', '2025-10-21 03:00:46'),
(95, '數學競賽初賽', 'competition', '全校數學競賽', '2025-10-20', '2025-10-20', '2025-10-08 00:00:00', '2025-10-19 23:59:59', '2025-10-17 23:59:59', 60, 104, '大禮堂', '無特殊要求', 'closed', 'teacher001', '2025-10-02 02:00:00', '2025-10-21 03:00:46'),
(96, '英語演講比賽', 'competition', '訓練英語口說能力', '2025-11-05', '2025-11-05', '2025-10-20 00:00:00', '2025-11-04 23:59:59', '2025-11-02 23:59:59', 30, 56, '演講廳', '需準備3-5分鐘演講稿', 'published', 'teacher001', '2025-10-15 02:00:00', '2025-10-21 03:00:46'),
(97, '科展競賽', 'competition', '科學研究成果展示', '2025-11-20', '2025-11-22', '2025-11-05 00:00:00', '2025-11-19 23:59:59', '2025-11-17 23:59:59', 50, 90, '科學館', '需提交研究計畫書', 'published', 'teacher001', '2025-11-01 02:00:00', '2025-10-21 03:00:46'),
(98, '機器人格鬥賽', 'competition', '機器人競技比賽', '2025-12-10', '2025-12-10', '2025-11-20 00:00:00', '2025-12-09 23:59:59', '2025-12-07 23:59:59', 30, 30, '體育館', '需自備機器人設備', 'published', 'teacher001', '2025-11-15 02:00:00', '2025-10-21 03:00:46'),
(99, '開學迎新活動', 'activity', '歡迎新生活動', '2025-10-05', '2025-10-05', '2025-09-15 00:00:00', '2025-10-04 23:59:59', '2025-10-02 23:59:59', 100, 98, '大禮堂', '無特殊要求', 'closed', 'teacher001', '2025-09-10 02:00:00', '2025-10-21 03:00:45'),
(100, '科學園遊會', 'activity', '有趣的科學體驗', '2025-10-25', '2025-10-25', '2025-10-10 00:00:00', '2025-10-24 23:59:59', '2025-10-22 23:59:59', 80, 75, '操場', '請穿著運動服', 'closed', 'teacher001', '2025-10-05 02:00:00', '2025-10-21 03:00:45'),
(101, '環保淨灘活動', 'activity', '保護海洋環境', '2025-11-15', '2025-11-15', '2025-11-01 00:00:00', '2025-11-14 23:59:59', '2025-11-12 23:59:59', 50, 42, '海邊', '需自備環保手套', 'published', 'teacher001', '2025-10-25 02:00:00', '2025-10-21 03:00:45'),
(102, '校外教學-博物館', 'activity', '參觀科學展覽', '2025-11-28', '2025-11-28', '2025-11-10 00:00:00', '2025-11-27 23:59:59', '2025-11-25 23:59:59', 45, 40, '科學博物館', '需繳交家長同意書', 'published', 'teacher001', '2025-11-05 02:00:00', '2025-10-21 03:00:45'),
(103, '聖誕慶祝活動', 'activity', '歡慶聖誕節', '2025-12-20', '2025-12-20', '2025-12-01 00:00:00', '2025-12-19 23:59:59', '2025-12-17 23:59:59', 60, 29, '大禮堂', '歡迎全校師生參加', 'published', 'teacher001', '2025-11-20 02:00:00', '2025-10-24 08:07:17'),
(104, 'Python 程式設計工作坊', 'workshop', '學習 Python 基礎', '2025-10-10', '2025-10-12', '2025-09-25 00:00:00', '2025-10-09 23:59:59', '2025-10-07 23:59:59', 25, 23, '電腦教室 A', '需自備筆電', 'closed', 'teacher001', '2025-09-20 02:00:00', '2025-10-21 03:00:45'),
(105, '3D 列印體驗營', 'workshop', '體驗 3D 列印技術', '2025-10-28', '2025-10-29', '2025-10-15 00:00:00', '2025-10-27 23:59:59', '2025-10-25 23:59:59', 20, 20, '創客教室', '無特殊要求', 'closed', 'teacher001', '2025-10-10 02:00:00', '2025-10-21 03:00:45'),
(106, '攝影技巧工作坊', 'workshop', '學習攝影基礎', '2025-11-08', '2025-11-09', '2025-10-25 00:00:00', '2025-11-07 23:59:59', '2025-11-05 23:59:59', 15, 12, '藝術教室', '需自備相機', 'published', 'teacher001', '2025-10-20 02:00:00', '2025-10-21 03:00:45'),
(107, 'Arduino 機器人工作坊', 'workshop', '製作簡易機器人', '2025-11-25', '2025-11-27', '2025-11-10 00:00:00', '2025-11-24 23:59:59', '2025-11-22 23:59:59', 25, 20, '科技教室', '需具備基礎電子知識', 'published', 'teacher001', '2025-11-05 02:00:00', '2025-10-24 04:06:28'),
(108, '網頁設計工作坊', 'workshop', '學習 HTML/CSS/JS', '2025-12-05', '2025-12-07', '2025-11-20 00:00:00', '2025-12-04 23:59:59', '2025-12-02 23:59:59', 30, 22, '電腦教室 B', '需自備筆電', 'published', 'teacher001', '2025-11-15 02:00:00', '2025-10-21 03:00:45'),
(109, '科學家分享會', 'seminar', '知名科學家演講', '2025-10-12', '2025-10-12', '2025-10-01 00:00:00', '2025-10-11 23:59:59', '2025-10-09 23:59:59', 100, 85, '演講廳', '無特殊要求', 'closed', 'teacher001', '2025-09-28 02:00:00', '2025-10-21 03:00:45'),
(110, '升學輔導講座', 'seminar', '大學升學資訊', '2025-10-22', '2025-10-22', '2025-10-08 00:00:00', '2025-10-21 23:59:59', '2025-10-19 23:59:59', 80, 72, '大禮堂', '僅限高三學生', 'closed', 'teacher001', '2025-10-05 02:00:00', '2025-10-21 03:00:46'),
(111, '網路安全講座', 'seminar', '了解網路詐騙防範', '2025-11-03', '2025-11-03', '2025-10-20 00:00:00', '2025-11-02 23:59:59', '2025-10-31 23:59:59', 50, 45, '會議室', '無特殊要求', 'published', 'teacher001', '2025-10-15 02:00:00', '2025-10-21 03:00:46'),
(112, '環保永續講座', 'seminar', '探討環境議題', '2025-11-18', '2025-11-18', '2025-11-05 00:00:00', '2025-11-17 23:59:59', '2025-11-15 23:59:59', 60, 55, '演講廳', '無特殊要求', 'published', 'teacher001', '2025-11-01 02:00:00', '2025-10-21 03:00:46'),
(113, '生涯規劃講座', 'seminar', '職業探索與規劃', '2025-12-08', '2025-12-08', '2025-11-20 00:00:00', '2025-12-07 23:59:59', '2025-12-05 23:59:59', 70, 30, '大禮堂', '無特殊要求', 'published', 'teacher001', '2025-11-15 02:00:00', '2025-10-21 03:00:46'),
(114, 'aaa', 'activity', 'aaa', '2025-10-28', '2025-10-29', '2025-10-28 00:00:00', '2025-10-28 00:00:00', NULL, 111, 0, '111', 'null', 'draft', 'system', '2025-10-27 01:54:43', '2025-10-27 01:54:43'),
(115, 'qqqqq', 'activity', 'qqqqq', '2025-10-28', '2025-10-29', '2025-10-28 00:00:00', '2025-10-28 00:00:00', NULL, 11, 0, '111111', 'null', 'draft', 'system', '2025-10-27 01:56:34', '2025-10-27 01:56:34'),
(116, 'aaaaaaa', 'activity', 'aaaaaaa', '2025-10-28', '2025-10-29', '2025-10-28 00:00:00', '2025-10-28 00:00:00', NULL, 11, 0, '11', 'null', 'draft', 'system', '2025-10-27 01:57:36', '2025-10-27 01:57:36'),
(117, 'aaaaa', 'activity', 'aaaaaa', '2025-10-28', '2025-10-31', '2025-10-28 00:00:00', '2025-10-28 00:00:00', NULL, 11, 2, '111', 'null', 'published', 'system', '2025-10-27 02:14:24', '2025-10-27 02:47:16'),
(118, 'aaaaaa', 'activity', 'aaaaaaa', '2025-10-28', '2025-10-31', '2025-10-28 00:00:00', '2025-10-28 00:00:00', NULL, 11, 0, '11', 'null', 'published', 'system', '2025-10-27 02:32:21', '2025-10-27 02:32:21');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_account` varchar(50) NOT NULL COMMENT '管理員帳號',
  `admin_name` varchar(50) NOT NULL COMMENT '管理員姓名',
  `department` varchar(100) DEFAULT NULL COMMENT '部門',
  `permissions` text DEFAULT NULL COMMENT '權限設定(JSON)',
  `user_id` int(11) DEFAULT NULL COMMENT '關聯帳號ID',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='系統管理員資料';

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_account`, `admin_name`, `department`, `permissions`, `user_id`, `created_at`) VALUES
(1, 'admin', '系統管理員', '資訊管理部', NULL, 1, '2025-10-09 00:59:06');

-- --------------------------------------------------------

--
-- Table structure for table `album_media`
--

CREATE TABLE `album_media` (
  `media_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL COMMENT '相簿ID',
  `media_type` enum('image','video') NOT NULL COMMENT '媒體類型',
  `file_path` varchar(255) NOT NULL COMMENT '檔案路徑',
  `file_extension` varchar(10) DEFAULT NULL COMMENT '檔案副檔名',
  `file_name` varchar(200) DEFAULT NULL COMMENT '檔案名稱',
  `description` text DEFAULT NULL COMMENT '描述',
  `upload_order` int(11) DEFAULT 0 COMMENT '排序順序',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='相簿媒體檔案';

--
-- Dumping data for table `album_media`
--

INSERT INTO `album_media` (`media_id`, `album_id`, `media_type`, `file_path`, `file_extension`, `file_name`, `description`, `upload_order`, `uploaded_at`) VALUES
(1, 1, 'image', 'uploads/albums/68fdbd16bfb86_1761459478.png', NULL, 'Screenshot 2025-10-19 at 13.24.27.png', 'Screenshot 2025-10-19 at 13.24.27.png', 0, '2025-10-26 06:17:58'),
(2, 4, 'image', 'uploads/albums/68fedb6112a7d_1761532769.png', NULL, 'Screenshot 2025-10-19 at 13.24.27.png', 'Screenshot 2025-10-19 at 13.24.27.png', 0, '2025-10-27 02:39:29');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL COMMENT '公告標題',
  `content` text NOT NULL COMMENT '公告內容',
  `announcement_type` enum('general','competition','activity','award') NOT NULL COMMENT '公告類型',
  `is_important` tinyint(1) DEFAULT 0 COMMENT '是否重要',
  `has_detail` tinyint(1) DEFAULT 0 COMMENT '是否有詳細內容',
  `detail_content` text DEFAULT NULL COMMENT '詳細內容',
  `attachment` varchar(255) DEFAULT NULL COMMENT '附件路徑',
  `publish_date` datetime DEFAULT current_timestamp() COMMENT '發佈日期',
  `created_by` varchar(50) DEFAULT NULL COMMENT '建立者',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='公告';

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `content`, `announcement_type`, `is_important`, `has_detail`, `detail_content`, `attachment`, `publish_date`, `created_by`, `created_at`) VALUES
(1, '🎉 2025科學探索營開始報名！', '一年一度的科學探索營即將開始，名額有限，報名從速！活動時間：2025/02/01-05，地點：科學館3F實驗室。', 'activity', 1, 0, NULL, NULL, '2025-10-19 12:42:35', 'admin', '2025-10-19 04:42:35'),
(2, '📢 校園科學競賽徵件開始', '2025年度校園科學競賽開始徵件，歡迎有興趣的同學組隊參加！競賽日期：2025/03/15。', 'competition', 1, 0, NULL, NULL, '2025-10-19 12:42:35', 'admin', '2025-10-19 04:42:35'),
(3, 'ℹ️ 科學會定期活動通知', '本學期科學會每週三下午3點於會議室舉辦定期聚會，歡迎參加！', 'general', 0, 0, NULL, NULL, '2025-10-19 12:42:35', 'admin', '2025-10-19 04:42:35'),
(4, 'abccba', 'asfajkef', 'general', 0, 0, '', NULL, '2025-10-27 00:52:04', 'sciclub', '2025-10-26 16:52:04'),
(5, 'adawdawd', 'adawd', 'general', 0, 0, '', NULL, '2025-10-27 01:24:45', 'sciclub', '2025-10-26 17:24:45'),
(6, 'ascadc', 'adadvc', 'general', 0, 0, '', NULL, '2025-10-27 01:27:28', 'sciclub', '2025-10-26 17:27:28'),
(7, 'zsdcSDV', 'SDvSDvzsd', 'general', 0, 0, '', NULL, '2025-10-27 02:02:25', 'sciclub', '2025-10-26 18:02:25'),
(8, '測試公告 - 2025/10/27 上午9:17:14', '這是一條測試公告內容', 'general', 0, 0, '', NULL, '2025-10-27 09:17:14', 'sciclub', '2025-10-27 01:17:14'),
(9, 'aaa', 'aaaa', 'general', 0, 0, '', NULL, '2025-10-27 09:38:38', 'sciclub', '2025-10-27 01:38:38'),
(10, 'aa', 'aaa', 'general', 0, 0, '', NULL, '2025-10-27 09:57:00', 'sciclub', '2025-10-27 01:57:00'),
(11, '111', '1111', 'general', 0, 0, '', NULL, '2025-10-26 19:18:28', 'teacher01', '2025-10-27 02:18:28'),
(12, 'qqq', 'qqq', 'general', 0, 0, '', NULL, '2025-10-26 19:40:33', 'sciclub', '2025-10-27 02:40:33');

--
-- Triggers `announcements`
--
DELIMITER $$
CREATE TRIGGER `after_announcement_insert` AFTER INSERT ON `announcements` FOR EACH ROW BEGIN
    INSERT INTO `notifications` 
    (`recipient_type`, `notification_type`, `title`, `message`, `related_type`, `related_id`)
    VALUES ('all', 'announcement', '新公告', NEW.title, 'announcement', NEW.announcement_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

CREATE TABLE `attendance_records` (
  `record_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL COMMENT '場次ID',
  `member_name` varchar(50) NOT NULL COMMENT '成員姓名',
  `member_number` varchar(20) DEFAULT NULL COMMENT '成員編號',
  `department` varchar(50) DEFAULT NULL,
  `position` varchar(80) DEFAULT NULL,
  `check_in_time` datetime DEFAULT current_timestamp() COMMENT '簽到時間',
  `status` enum('present','late','absent','leave') DEFAULT 'present' COMMENT '狀態',
  `notes` text DEFAULT NULL COMMENT '備註'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='簽到紀錄';

--
-- Dumping data for table `attendance_records`
--

INSERT INTO `attendance_records` (`record_id`, `session_id`, `member_name`, `member_number`, `department`, `position`, `check_in_time`, `status`, `notes`) VALUES
(2, 1, '王小明', 'SC001', 'activity', '活動組長', '2025-10-22 08:45:00', 'present', ''),
(3, 1, '李美華', 'SC002', 'publicity', '美宣組員', '2025-10-22 09:10:00', 'late', '遲到10分鐘'),
(4, 1, '陳志強', 'SC003', 'secretariat', '文書組員', NULL, 'leave', '病假'),
(5, 1, '林雅婷', 'SC004', 'finance', '總務組長', '2025-10-22 08:30:00', 'present', '準時到'),
(6, 2, '張世杰', 'SC005', 'activity', '活動組員', '2025-10-22 14:05:00', 'present', ''),
(7, 2, '黃怡如', 'SC006', 'publicity', '美宣組長', '2025-10-22 14:20:00', 'late', '延誤15分鐘'),
(8, 2, '何志宏', 'SC007', 'secretariat', '文書組長', '2025-10-22 13:55:00', 'present', ''),
(9, 2, '吳佩蓉', 'SC008', 'finance', '出納', NULL, 'leave', '事假');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_sessions`
--

CREATE TABLE `attendance_sessions` (
  `session_id` int(11) NOT NULL COMMENT '場次ID',
  `session_name` varchar(100) NOT NULL COMMENT '場次名稱 (例如: 早上訓練)',
  `session_date` date NOT NULL COMMENT '日期',
  `start_time` time NOT NULL COMMENT '開始時間',
  `end_time` time NOT NULL COMMENT '結束時間',
  `location` varchar(100) DEFAULT NULL COMMENT '地點',
  `status` varchar(20) DEFAULT 'active' COMMENT '狀態: active, completed, cancelled',
  `created_by` int(11) DEFAULT NULL COMMENT '建立者ID',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '建立時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='點名場次表';

--
-- Dumping data for table `attendance_sessions`
--

INSERT INTO `attendance_sessions` (`session_id`, `session_name`, `session_date`, `start_time`, `end_time`, `location`, `status`, `created_by`, `created_at`) VALUES
(1, '早上訓練', '2025-10-22', '09:00:00', '12:00:00', '訓練場', 'active', 1, '2025-10-22 08:41:34'),
(2, '下午活動', '2025-10-22', '14:00:00', '17:00:00', '活動中心', 'active', 1, '2025-10-22 08:41:34');

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

CREATE TABLE `calendar_events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL COMMENT '活動標題',
  `description` text DEFAULT NULL COMMENT '活動描述',
  `event_date` date NOT NULL COMMENT '活動日期',
  `start_time` time DEFAULT NULL COMMENT '開始時間',
  `end_time` time DEFAULT NULL COMMENT '結束時間',
  `event_type` enum('competition','activity','meeting','other') NOT NULL COMMENT '活動類型:比賽/活動/會議/其他',
  `color` varchar(20) NOT NULL DEFAULT 'red' COMMENT '顏色標記',
  `is_public` tinyint(1) DEFAULT 0 COMMENT '是否公開給學生(紅底=1,橘底=0)',
  `location` varchar(200) DEFAULT NULL COMMENT '地點',
  `created_by_account` varchar(50) DEFAULT NULL COMMENT '建立者帳號',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(100) DEFAULT NULL COMMENT '建立者'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='行事曆活動';

--
-- Dumping data for table `calendar_events`
--

INSERT INTO `calendar_events` (`event_id`, `title`, `description`, `event_date`, `start_time`, `end_time`, `event_type`, `color`, `is_public`, `location`, `created_by_account`, `created_at`, `updated_at`, `created_by`) VALUES
(1, '科學營報名截止', '2025科學探索營報名最後一天', '2025-01-25', '23:59:00', NULL, 'other', 'red', 1, '線上', 'admin', '2025-10-19 04:42:35', '2025-10-19 04:42:35', NULL),
(2, '科學營開始', '2025科學探索營第一天', '2025-02-01', '09:00:00', '17:00:00', 'activity', 'blue', 1, '科學館 3F', 'admin', '2025-10-19 04:42:35', '2025-10-19 04:42:35', NULL),
(3, '科學競賽', '校園科學競賽日', '2025-03-15', '09:00:00', '16:00:00', 'competition', 'green', 1, '大禮堂', 'admin', '2025-10-19 04:42:35', '2025-10-19 04:42:35', NULL),
(4, 'AI講座', 'AI與機器學習專題講座', '2025-01-30', '14:00:00', '16:00:00', 'activity', 'purple', 1, '視聽教室A', 'admin', '2025-10-19 04:42:35', '2025-10-19 04:42:35', NULL),
(5, '化學工作坊', '化學實驗工作坊', '2025-01-22', '13:00:00', '17:00:00', 'activity', 'orange', 1, '化學實驗室', 'admin', '2025-10-19 04:42:35', '2025-10-19 04:42:35', NULL),
(6, '科技展開幕', '校園科技展覽開幕式', '2025-02-10', '10:00:00', '12:00:00', 'activity', 'cyan', 1, '活動中心', 'admin', '2025-10-19 04:42:35', '2025-10-19 04:42:35', NULL),
(7, '期末檢討會議', '科學會期末檢討會議', '2025-01-28', '15:00:00', '17:00:00', 'meeting', 'gray', 1, '會議室', 'admin', '2025-10-19 04:42:35', '2025-10-19 04:42:35', NULL),
(8, '科學營 2025', NULL, '2025-10-15', '09:00:00', '17:00:00', 'competition', 'red', 1, NULL, NULL, '2025-10-19 06:09:24', '2025-10-19 06:09:24', NULL),
(9, '物理實驗', NULL, '2025-10-20', '14:00:00', '16:00:00', 'competition', 'red', 1, '化學實驗室', NULL, '2025-10-19 06:09:24', '2025-10-20 09:57:40', NULL),
(10, '校園科技展', NULL, '2025-11-10', '10:00:00', '15:00:00', 'competition', 'red', 1, NULL, NULL, '2025-10-19 06:09:24', '2025-10-19 06:09:24', NULL),
(11, 'test', 'test', '2025-10-25', '12:00:00', '13:00:00', 'activity', 'red', 1, '', 'student001', '2025-10-25 04:06:38', '2025-10-25 04:06:38', NULL),
(12, 'abc', '', '2025-10-27', '11:11:00', '11:11:00', 'activity', 'red', 1, '', 'sciclub', '2025-10-27 02:39:10', '2025-10-27 02:39:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `competition_results`
--

CREATE TABLE `competition_results` (
  `result_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL COMMENT '比賽ID',
  `student_number` varchar(10) NOT NULL COMMENT '學號',
  `student_name` varchar(50) NOT NULL COMMENT '姓名',
  `student_class` varchar(20) NOT NULL COMMENT '班級',
  `score` decimal(10,2) DEFAULT NULL COMMENT '分數',
  `rank` int(11) DEFAULT NULL COMMENT '名次',
  `award` varchar(50) DEFAULT NULL COMMENT '獎項',
  `notes` text DEFAULT NULL COMMENT '備註',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='比賽成績';

--
-- Dumping data for table `competition_results`
--

INSERT INTO `competition_results` (`result_id`, `activity_id`, `student_number`, `student_name`, `student_class`, `score`, `rank`, `award`, `notes`, `created_at`, `updated_at`) VALUES
(4, 97, 'S1001', '王小明', '三年甲班', 95.50, 1, '金牌', '表現優秀', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(5, 97, 'S1002', '林美麗', '三年乙班', 92.00, 2, '銀牌', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(6, 97, 'S1003', '陳志強', '三年丙班', 90.50, 3, '銅牌', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(7, 97, 'S1004', '李佩君', '三年甲班', 88.00, 4, '佳作', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(8, 97, 'S1005', '張家豪', '三年乙班', 85.50, 5, '佳作', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(9, 96, 'S2001', '黃欣怡', '二年甲班', 98.00, 1, '金牌', '語音清晰', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(10, 96, 'S2002', '陳柏翰', '二年乙班', 96.00, 2, '銀牌', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(11, 96, 'S2003', '劉雅婷', '二年丙班', 93.00, 3, '銅牌', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(12, 96, 'S2004', '許哲偉', '二年甲班', 91.00, 4, '佳作', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(13, 98, 'S3001', '吳冠霖', '三年乙班', 100.00, 1, '冠軍', '機器人穩定度高', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(14, 98, 'S3002', '蔡依庭', '三年丙班', 97.50, 2, '亞軍', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(15, 98, 'S3003', '林信宏', '三年甲班', 95.00, 3, '季軍', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(16, 98, 'S3004', '高子萱', '三年乙班', 90.00, 4, '佳作', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(17, 106, 'S4001', '陳怡安', '一年甲班', 93.00, 1, '金牌', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(18, 106, 'S4002', '張文傑', '一年乙班', 90.50, 2, '銀牌', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57'),
(19, 106, 'S4003', '李佳穎', '一年甲班', 88.00, 3, '銅牌', '', '2025-10-26 10:34:57', '2025-10-26 10:34:57');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_forms`
--

CREATE TABLE `feedback_forms` (
  `form_id` int(11) NOT NULL,
  `form_name` varchar(200) NOT NULL COMMENT '表單名稱',
  `activity_name` varchar(200) NOT NULL COMMENT '活動名稱',
  `activity_id` int(11) DEFAULT NULL COMMENT '關聯活動ID',
  `description` text DEFAULT NULL COMMENT '表單說明',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '是否開放填寫',
  `created_by` varchar(50) DEFAULT NULL COMMENT '建立者',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='反饋表單';

--
-- Dumping data for table `feedback_forms`
--

INSERT INTO `feedback_forms` (`form_id`, `form_name`, `activity_name`, `activity_id`, `description`, `is_active`, `created_by`, `created_at`) VALUES
(1, '科學營滿意度調查', '2025 科學探索營', 1, '請填寫您對本次科學營的意見與建議', 1, 'admin', '2025-10-19 04:42:35'),
(2, '科學競賽回饋表', '校園科學競賽 2025', NULL, '競賽活動回饋與改進建議', 1, 'admin', '2025-10-19 04:42:35'),
(3, 'AI講座問卷', 'AI與機器學習講座', NULL, '講座內容與講師評價', 1, 'admin', '2025-10-19 04:42:35'),
(5, 'ada', '化學實驗競賽', 2, 'adadfc', 1, 'sciclub', '2025-10-26 17:20:07'),
(6, 'adadfa', '化學實驗競賽', 2, '', 1, 'sciclub', '2025-10-26 18:05:48'),
(7, '測試表單 - 2025/10/27 上午9:17:30', '測試活動', 1, '這是一個測試反饋表單', 1, 'sciclub', '2025-10-27 01:17:30'),
(8, '測試表單 - 2025/10/27 上午9:25:29', '測試活動', 1, '這是一個測試反饋表單', 1, 'sciclub', '2025-10-27 01:25:29');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_responses`
--

CREATE TABLE `feedback_responses` (
  `response_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL COMMENT '表單ID',
  `student_number` varchar(10) NOT NULL COMMENT '學號',
  `student_name` varchar(50) NOT NULL COMMENT '姓名',
  `student_class` varchar(20) NOT NULL COMMENT '班級',
  `satisfaction_score` tinyint(4) NOT NULL COMMENT '滿意度(1-5)',
  `feedback_text` text DEFAULT NULL COMMENT '開放式意見',
  `is_read` tinyint(1) DEFAULT 0,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `feedback_responses`
--

INSERT INTO `feedback_responses` (`response_id`, `form_id`, `student_number`, `student_name`, `student_class`, `satisfaction_score`, `feedback_text`, `is_read`, `submitted_at`) VALUES
(1, 2, 'student001', 'test', '', 5, 'test', 1, '2025-10-19 06:11:34'),
(2, 3, 'student001', 'abc', '', 5, 'abc', 1, '2025-10-20 13:46:14'),
(3, 5, 'student001', 'qq', '', 5, 'qq', 0, '2025-10-27 02:49:25');

-- --------------------------------------------------------

--
-- Table structure for table `file_upload_zones`
--

CREATE TABLE `file_upload_zones` (
  `zone_id` int(11) NOT NULL,
  `zone_name` varchar(200) NOT NULL COMMENT '上傳區名稱',
  `description` text DEFAULT NULL COMMENT '說明',
  `activity_id` int(11) DEFAULT NULL COMMENT '關聯活動ID',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '是否開放上傳',
  `file_types` varchar(200) DEFAULT NULL COMMENT '允許的檔案類型',
  `max_file_size` int(11) DEFAULT 10 COMMENT '檔案大小上限(MB)',
  `deadline` datetime DEFAULT NULL COMMENT '上傳截止時間',
  `created_by` varchar(50) DEFAULT NULL COMMENT '建立者',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='檔案上傳區';

--
-- Dumping data for table `file_upload_zones`
--

INSERT INTO `file_upload_zones` (`zone_id`, `zone_name`, `description`, `activity_id`, `is_active`, `file_types`, `max_file_size`, `deadline`, `created_by`, `created_at`) VALUES
(1, 'abc', 'abc', NULL, 0, '.pdf', 10, '2025-10-26 18:46:00', 'sciclub', '2025-10-26 10:46:43'),
(3, 'abc', 'asdasd', NULL, 0, 'jpg', 10, '2025-10-31 19:05:00', 'sciclub', '2025-10-26 11:05:13'),
(4, 'qqqqq', 'qqqq', NULL, 1, 'pdf', 10, '2025-10-26 20:43:00', 'sciclub', '2025-10-27 02:43:26');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `request_id` int(11) NOT NULL,
  `applicant_name` varchar(50) NOT NULL COMMENT '申請人姓名',
  `applicant_number` varchar(20) NOT NULL COMMENT '申請人編號',
  `leave_type` enum('sick','personal','official','other') NOT NULL COMMENT '請假類型',
  `leave_date` date NOT NULL COMMENT '請假日期',
  `end_date` date DEFAULT NULL COMMENT '請假結束日期',
  `start_time` time DEFAULT NULL COMMENT '開始時間',
  `end_time` time DEFAULT NULL COMMENT '結束時間',
  `reason` text NOT NULL COMMENT '請假原因',
  `status` enum('pending','approved','rejected') DEFAULT 'pending' COMMENT '審核狀態',
  `reviewed_by` varchar(50) DEFAULT NULL COMMENT '審核者',
  `review_comment` text DEFAULT NULL COMMENT '審核意見',
  `reviewed_at` datetime DEFAULT NULL COMMENT '審核時間',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='請假申請';

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`request_id`, `applicant_name`, `applicant_number`, `leave_type`, `leave_date`, `end_date`, `start_time`, `end_time`, `reason`, `status`, `reviewed_by`, `review_comment`, `reviewed_at`, `submitted_at`) VALUES
(1, '王小明', 'T001', 'sick', '2025-10-25', '2025-10-25', '08:00:00', '17:00:00', '感冒發燒需要休息，已附上醫生證明', 'approved', 'sciclub', '核准', '2025-10-26 19:21:44', '2025-10-23 01:30:00'),
(2, '李小華', 'T002', 'personal', '2025-10-28', '2025-10-28', '13:00:00', '17:00:00', '家中有事需要處理', 'rejected', 'sciclub', '該時段有重要活動需要參與', '2025-10-26 19:21:46', '2025-10-23 02:15:00'),
(3, '張大同', 'T003', 'official', '2025-11-01', '2025-11-03', '08:00:00', '17:00:00', '參加教育訓練研習課程，預計請假三天', 'approved', '王老師', '核准，請記得帶回研習證明', '2025-10-22 14:30:00', '2025-10-20 03:20:00'),
(4, '陳美玲', 'T004', 'sick', '2025-10-24', '2025-10-24', '08:00:00', '12:00:00', '需要去醫院複診', 'rejected', '系統', 'test', '2025-10-22 11:54:53', '2025-10-23 00:45:00'),
(5, '林志明', 'T005', 'other', '2025-10-26', '2025-10-27', '08:00:00', '17:00:00', '個人因素需要請假兩天', 'rejected', 'sciclub', 'aaa', '2025-10-26 19:44:24', '2025-10-22 02:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_records`
--

CREATE TABLE `meeting_records` (
  `meeting_id` int(11) NOT NULL,
  `meeting_title` varchar(200) NOT NULL COMMENT '會議主題',
  `meeting_date` date NOT NULL COMMENT '會議日期',
  `start_time` time DEFAULT NULL COMMENT '開始時間',
  `end_time` time DEFAULT NULL COMMENT '結束時間',
  `location` varchar(200) DEFAULT NULL COMMENT '地點',
  `attendees` text DEFAULT NULL COMMENT '出席人員',
  `agenda` text DEFAULT NULL COMMENT '會議議程',
  `content` text NOT NULL COMMENT '會議內容',
  `decisions` text DEFAULT NULL COMMENT '決議事項',
  `action_items` text DEFAULT NULL COMMENT '待辦事項',
  `next_meeting` date DEFAULT NULL COMMENT '下次會議日期',
  `created_by` varchar(50) DEFAULT NULL COMMENT '紀錄者',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='開會紀錄';

--
-- Dumping data for table `meeting_records`
--

INSERT INTO `meeting_records` (`meeting_id`, `meeting_title`, `meeting_date`, `start_time`, `end_time`, `location`, `attendees`, `agenda`, `content`, `decisions`, `action_items`, `next_meeting`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '1', '2025-10-24', '12:00:00', '13:00:00', '1', 'a', 'a', 'a', 'a', 'a', '2025-10-01', 'a', '2025-10-24 15:08:17', '2025-10-25 04:54:35'),
(2, 'aaa', '2025-10-26', '12:00:00', '12:00:00', 'a', 'a', 'a', 'a', 'aaa', 'aaa', '2025-11-11', 'sciclub', '2025-10-26 05:55:57', '2025-10-26 05:55:57'),
(3, 'qqq', '2025-10-28', '11:01:00', '11:11:00', 'qqq', 'qqq', 'qqq', 'qq', 'qqq', 'qqq', '2025-10-30', 'sciclub', '2025-10-27 02:46:01', '2025-10-27 02:46:01');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `recipient_type` enum('student','admin','teacher','science_club','all') NOT NULL COMMENT '接收者類型',
  `recipient_account` varchar(50) DEFAULT NULL COMMENT '接收者帳號',
  `notification_type` enum('registration','announcement','reminder','deadline','approval','system') NOT NULL COMMENT '通知類型',
  `title` varchar(200) NOT NULL COMMENT '通知標題',
  `message` text NOT NULL COMMENT '通知內容',
  `related_type` varchar(50) DEFAULT NULL COMMENT '關聯類型',
  `related_id` int(11) DEFAULT NULL COMMENT '關聯ID',
  `is_read` tinyint(1) DEFAULT 0 COMMENT '是否已讀',
  `read_at` datetime DEFAULT NULL COMMENT '閱讀時間',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='通知';

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `recipient_type`, `recipient_account`, `notification_type`, `title`, `message`, `related_type`, `related_id`, `is_read`, `read_at`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(5, 'science_club', NULL, 'announcement', '1', '1', NULL, NULL, 1, '2025-10-26 19:06:01', '2025-10-25 04:46:49', 0, NULL),
(6, 'teacher', NULL, 'deadline', '6', '6', NULL, NULL, 1, '2025-10-22 21:03:07', '2025-10-22 11:18:28', 0, NULL),
(7, 'student', NULL, 'approval', '7', '7', NULL, NULL, 1, '2025-10-22 21:02:54', '2025-10-22 12:55:49', 0, NULL),
(196, 'student', 'student001', 'registration', '報名成功', '您已成功報名「Arduino 機器人工作坊」活動', 'activity', 107, 0, NULL, '2025-10-24 04:06:28', 0, NULL),
(197, 'student', 'teacher01', 'registration', '報名成功', '您已成功報名「聖誕慶祝活動」活動', 'activity', 103, 0, NULL, '2025-10-24 08:07:17', 0, NULL),
(198, 'all', NULL, 'announcement', '新公告', 'abccba', 'announcement', 4, 0, NULL, '2025-10-26 16:52:04', 0, NULL),
(199, 'all', NULL, 'announcement', '新公告', 'adawdawd', 'announcement', 5, 0, NULL, '2025-10-26 17:24:45', 0, NULL),
(200, 'all', NULL, 'announcement', '新公告', 'ascadc', 'announcement', 6, 0, NULL, '2025-10-26 17:27:28', 0, NULL),
(201, 'all', NULL, 'announcement', '新公告', 'zsdcSDV', 'announcement', 7, 0, NULL, '2025-10-26 18:02:25', 0, NULL),
(202, 'all', NULL, 'announcement', '新公告', '測試公告 - 2025/10/27 上午9:17:14', 'announcement', 8, 0, NULL, '2025-10-27 01:17:14', 0, NULL),
(203, 'all', NULL, 'announcement', '新公告', 'aaa', 'announcement', 9, 0, NULL, '2025-10-27 01:38:38', 0, NULL),
(204, 'all', NULL, 'announcement', '新公告', 'aa', 'announcement', 10, 0, NULL, '2025-10-27 01:57:00', 0, NULL),
(205, 'all', NULL, 'announcement', '新公告', '111', 'announcement', 11, 0, NULL, '2025-10-27 02:18:28', 0, NULL),
(206, 'all', NULL, 'announcement', '新公告', 'qqq', 'announcement', 12, 0, NULL, '2025-10-27 02:40:33', 0, NULL),
(207, 'student', 'student001', 'registration', '報名成功', '您已成功報名「aaaaa」活動', 'activity', 117, 0, NULL, '2025-10-27 02:47:16', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `photo_albums`
--

CREATE TABLE `photo_albums` (
  `album_id` int(11) NOT NULL,
  `album_name` varchar(200) NOT NULL COMMENT '相簿名稱',
  `activity_name` varchar(200) NOT NULL COMMENT '活動名稱',
  `description` text DEFAULT NULL COMMENT '相簿描述',
  `cover_image` varchar(255) DEFAULT NULL COMMENT '封面圖片路徑',
  `is_public` tinyint(1) DEFAULT 1 COMMENT '是否公開',
  `created_by` varchar(50) DEFAULT NULL COMMENT '建立者',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='活動相簿';

--
-- Dumping data for table `photo_albums`
--

INSERT INTO `photo_albums` (`album_id`, `album_name`, `activity_name`, `description`, `cover_image`, `is_public`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '2024科學營精彩花絮', '2024科學探索營', '去年科學營的精彩瞬間', NULL, 1, 'admin', '2025-10-19 04:42:35', '2025-10-19 04:42:35'),
(2, '科學競賽得獎作品', '2024科學競賽', '優秀作品展示', NULL, 1, 'admin', '2025-10-19 04:42:35', '2025-10-19 04:42:35'),
(3, '實驗室開放日', '實驗室參觀', '實驗室設備介紹', NULL, 1, 'admin', '2025-10-19 04:42:35', '2025-10-19 04:42:35'),
(4, 'abcv', 'abc', 'abcfcd', NULL, 1, 'sciclub', '2025-10-26 06:18:40', '2025-10-26 06:20:13');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `registration_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL COMMENT '活動ID',
  `student_number` varchar(10) NOT NULL COMMENT '學號',
  `student_name` varchar(50) NOT NULL COMMENT '姓名',
  `student_class` varchar(20) NOT NULL COMMENT '班級',
  `registration_time` datetime DEFAULT current_timestamp() COMMENT '報名時間',
  `modified_time` datetime DEFAULT NULL COMMENT '修改時間',
  `status` enum('registered','cancelled','attended','absent') DEFAULT 'registered' COMMENT '狀態',
  `notes` text DEFAULT NULL COMMENT '備註'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='活動報名';

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`registration_id`, `activity_id`, `student_number`, `student_name`, `student_class`, `registration_time`, `modified_time`, `status`, `notes`) VALUES
(2, 2, 'S1025', 'Test', '資二甲', '2025-10-21 11:43:31', NULL, 'registered', NULL),
(83, 94, 'S1001', '王小明', '高一1班', '2025-10-10 09:15:30', NULL, 'registered', NULL),
(84, 94, 'S1002', '李小華', '高一2班', '2025-10-10 10:22:15', NULL, 'registered', NULL),
(85, 94, 'S1003', '張小美', '高一3班', '2025-10-10 14:30:45', NULL, 'registered', NULL),
(86, 94, 'S1004', '陳大力', '高二1班', '2025-10-11 08:45:20', NULL, 'registered', NULL),
(87, 94, 'S1005', '林小雅', '高二2班', '2025-10-11 11:20:35', NULL, 'registered', NULL),
(88, 94, 'S1006', '黃志明', '高二3班', '2025-10-11 15:10:50', NULL, 'registered', NULL),
(89, 94, 'S1007', '吳佳玲', '高三1班', '2025-10-12 09:30:10', NULL, 'registered', NULL),
(90, 94, 'S1008', '鄭宇翔', '高三2班', '2025-10-12 13:45:25', NULL, 'registered', NULL),
(91, 94, 'S1009', '劉雅婷', '高一1班', '2025-10-12 16:20:40', NULL, 'registered', NULL),
(92, 94, 'S1010', '蔡明哲', '高一2班', '2025-10-13 10:15:55', NULL, 'registered', NULL),
(93, 94, 'S1011', '許文華', '高一3班', '2025-10-13 14:30:10', NULL, 'registered', NULL),
(94, 94, 'S1012', '謝志豪', '高二1班', '2025-10-13 16:45:25', NULL, 'registered', NULL),
(95, 94, 'S1013', '楊淑芬', '高二2班', '2025-10-14 09:20:40', NULL, 'registered', NULL),
(96, 94, 'S1014', '賴俊傑', '高二3班', '2025-10-14 11:35:55', NULL, 'registered', NULL),
(97, 94, 'S1015', '周雅慧', '高三1班', '2025-10-14 15:50:10', NULL, 'registered', NULL),
(98, 94, 'S1016', '林建宏', '高一1班', '2025-10-14 16:10:20', NULL, 'registered', NULL),
(99, 94, 'S1017', '陳雅琪', '高一2班', '2025-10-14 16:25:30', NULL, 'registered', NULL),
(100, 94, 'S1018', '張偉傑', '高一3班', '2025-10-14 16:40:40', NULL, 'registered', NULL),
(101, 94, 'S1019', '李佳穎', '高二1班', '2025-10-14 16:55:50', NULL, 'registered', NULL),
(102, 94, 'S1020', '黃俊豪', '高二2班', '2025-10-14 17:10:00', NULL, 'registered', NULL),
(103, 94, 'S1021', '吳淑貞', '高二3班', '2025-10-14 17:25:10', NULL, 'registered', NULL),
(104, 94, 'S1022', '鄭文凱', '高三1班', '2025-10-14 17:40:20', NULL, 'registered', NULL),
(105, 94, 'S1023', '劉建成', '高三2班', '2025-10-14 17:55:30', NULL, 'registered', NULL),
(106, 94, 'S1024', '蔡雅芳', '高一1班', '2025-10-14 18:10:40', NULL, 'registered', NULL),
(107, 94, 'S1025', '許志偉', '高一2班', '2025-10-14 18:25:50', NULL, 'registered', NULL),
(108, 94, 'S1026', '謝佳玲', '高一3班', '2025-10-14 18:40:00', NULL, 'registered', NULL),
(109, 94, 'S1027', '楊俊賢', '高二1班', '2025-10-14 18:55:10', NULL, 'registered', NULL),
(110, 94, 'S1028', '賴淑華', '高二2班', '2025-10-14 19:10:20', NULL, 'registered', NULL),
(111, 94, 'S1029', '周文傑', '高二3班', '2025-10-14 19:25:30', NULL, 'registered', NULL),
(112, 94, 'S1030', '林雅婷', '高三1班', '2025-10-14 19:40:40', NULL, 'registered', NULL),
(113, 94, 'S1031', '陳建宏', '高三2班', '2025-10-14 19:55:50', NULL, 'registered', NULL),
(114, 94, 'S1032', '張佳琪', '高一1班', '2025-10-14 20:10:00', NULL, 'registered', NULL),
(115, 94, 'S1033', '李偉傑', '高一2班', '2025-10-14 20:25:10', NULL, 'registered', NULL),
(116, 94, 'S1034', '黃佳穎', '高一3班', '2025-10-14 20:40:20', NULL, 'registered', NULL),
(117, 94, 'S1035', '吳俊豪', '高二1班', '2025-10-14 20:55:30', NULL, 'registered', NULL),
(118, 95, 'S2001', '學生1', '高2年1班', '2025-10-15 08:30:00', NULL, 'registered', NULL),
(119, 95, 'S2002', '學生2', '高3年1班', '2025-10-15 09:00:00', NULL, 'registered', NULL),
(120, 95, 'S2003', '學生3', '高1年1班', '2025-10-15 09:30:00', NULL, 'registered', NULL),
(121, 95, 'S2004', '學生4', '高2年1班', '2025-10-15 10:00:00', NULL, 'registered', NULL),
(122, 95, 'S2005', '學生5', '高3年1班', '2025-10-15 10:30:00', NULL, 'registered', NULL),
(123, 95, 'S2006', '學生6', '高1年1班', '2025-10-15 11:00:00', NULL, 'registered', NULL),
(124, 95, 'S2007', '學生7', '高2年1班', '2025-10-15 11:30:00', NULL, 'registered', NULL),
(125, 95, 'S2008', '學生8', '高3年1班', '2025-10-15 12:00:00', NULL, 'registered', NULL),
(126, 95, 'S2009', '學生9', '高1年1班', '2025-10-15 12:30:00', NULL, 'registered', NULL),
(127, 95, 'S2010', '學生10', '高2年1班', '2025-10-15 13:00:00', NULL, 'registered', NULL),
(128, 95, 'S2011', '學生11', '高3年1班', '2025-10-15 13:30:00', NULL, 'registered', NULL),
(129, 95, 'S2012', '學生12', '高1年1班', '2025-10-15 14:00:00', NULL, 'registered', NULL),
(130, 95, 'S2013', '學生13', '高2年1班', '2025-10-15 14:30:00', NULL, 'registered', NULL),
(131, 95, 'S2014', '學生14', '高3年1班', '2025-10-15 15:00:00', NULL, 'registered', NULL),
(132, 95, 'S2015', '學生15', '高1年1班', '2025-10-15 15:30:00', NULL, 'registered', NULL),
(133, 95, 'S2016', '學生16', '高2年1班', '2025-10-15 16:00:00', NULL, 'registered', NULL),
(134, 95, 'S2017', '學生17', '高3年1班', '2025-10-15 16:30:00', NULL, 'registered', NULL),
(135, 95, 'S2018', '學生18', '高1年1班', '2025-10-15 17:00:00', NULL, 'registered', NULL),
(136, 95, 'S2019', '學生19', '高2年1班', '2025-10-15 17:30:00', NULL, 'registered', NULL),
(137, 95, 'S2020', '學生20', '高3年1班', '2025-10-15 18:00:00', NULL, 'registered', NULL),
(138, 95, 'S2021', '學生21', '高1年2班', '2025-10-15 18:30:00', NULL, 'registered', NULL),
(139, 95, 'S2022', '學生22', '高2年2班', '2025-10-15 19:00:00', NULL, 'registered', NULL),
(140, 95, 'S2023', '學生23', '高3年2班', '2025-10-15 19:30:00', NULL, 'registered', NULL),
(141, 95, 'S2024', '學生24', '高1年2班', '2025-10-15 20:00:00', NULL, 'registered', NULL),
(142, 95, 'S2025', '學生25', '高2年2班', '2025-10-15 20:30:00', NULL, 'registered', NULL),
(143, 95, 'S2026', '學生26', '高3年2班', '2025-10-15 21:00:00', NULL, 'registered', NULL),
(144, 95, 'S2027', '學生27', '高1年2班', '2025-10-15 21:30:00', NULL, 'registered', NULL),
(145, 95, 'S2028', '學生28', '高2年2班', '2025-10-15 22:00:00', NULL, 'registered', NULL),
(146, 95, 'S2029', '學生29', '高3年2班', '2025-10-15 22:30:00', NULL, 'registered', NULL),
(147, 95, 'S2030', '學生30', '高1年2班', '2025-10-15 23:00:00', NULL, 'registered', NULL),
(148, 95, 'S2031', '學生31', '高2年2班', '2025-10-15 23:30:00', NULL, 'registered', NULL),
(149, 95, 'S2032', '學生32', '高3年2班', '2025-10-16 00:00:00', NULL, 'registered', NULL),
(150, 95, 'S2033', '學生33', '高1年2班', '2025-10-16 00:30:00', NULL, 'registered', NULL),
(151, 95, 'S2034', '學生34', '高2年2班', '2025-10-16 01:00:00', NULL, 'registered', NULL),
(152, 95, 'S2035', '學生35', '高3年2班', '2025-10-16 01:30:00', NULL, 'registered', NULL),
(153, 95, 'S2036', '學生36', '高1年2班', '2025-10-16 02:00:00', NULL, 'registered', NULL),
(154, 95, 'S2037', '學生37', '高2年2班', '2025-10-16 02:30:00', NULL, 'registered', NULL),
(155, 95, 'S2038', '學生38', '高3年2班', '2025-10-16 03:00:00', NULL, 'registered', NULL),
(156, 95, 'S2039', '學生39', '高1年2班', '2025-10-16 03:30:00', NULL, 'registered', NULL),
(157, 95, 'S2040', '學生40', '高2年2班', '2025-10-16 04:00:00', NULL, 'registered', NULL),
(158, 95, 'S2041', '學生41', '高3年3班', '2025-10-16 04:30:00', NULL, 'registered', NULL),
(159, 95, 'S2042', '學生42', '高1年3班', '2025-10-16 05:00:00', NULL, 'registered', NULL),
(160, 95, 'S2043', '學生43', '高2年3班', '2025-10-16 05:30:00', NULL, 'registered', NULL),
(161, 95, 'S2044', '學生44', '高3年3班', '2025-10-16 06:00:00', NULL, 'registered', NULL),
(162, 95, 'S2045', '學生45', '高1年3班', '2025-10-16 06:30:00', NULL, 'registered', NULL),
(163, 95, 'S2046', '學生46', '高2年3班', '2025-10-16 07:00:00', NULL, 'registered', NULL),
(164, 95, 'S2047', '學生47', '高3年3班', '2025-10-16 07:30:00', NULL, 'registered', NULL),
(165, 95, 'S2048', '學生48', '高1年3班', '2025-10-16 08:00:00', NULL, 'registered', NULL),
(166, 95, 'S2049', '學生49', '高2年3班', '2025-10-16 08:30:00', NULL, 'registered', NULL),
(167, 95, 'S2050', '學生50', '高3年3班', '2025-10-16 09:00:00', NULL, 'registered', NULL),
(168, 95, 'S2051', '學生51', '高1年3班', '2025-10-16 09:30:00', NULL, 'registered', NULL),
(169, 95, 'S2052', '學生52', '高2年3班', '2025-10-16 10:00:00', NULL, 'registered', NULL),
(181, 96, 'S3001', '同學1', '高2年1班', '2025-10-30 08:45:00', NULL, 'registered', NULL),
(182, 96, 'S3002', '同學2', '高3年1班', '2025-10-30 09:30:00', NULL, 'registered', NULL),
(183, 96, 'S3003', '同學3', '高1年1班', '2025-10-30 10:15:00', NULL, 'registered', NULL),
(184, 96, 'S3004', '同學4', '高2年1班', '2025-10-30 11:00:00', NULL, 'registered', NULL),
(185, 96, 'S3005', '同學5', '高3年1班', '2025-10-30 11:45:00', NULL, 'registered', NULL),
(186, 96, 'S3006', '同學6', '高1年1班', '2025-10-30 12:30:00', NULL, 'registered', NULL),
(187, 96, 'S3007', '同學7', '高2年1班', '2025-10-30 13:15:00', NULL, 'registered', NULL),
(188, 96, 'S3008', '同學8', '高3年1班', '2025-10-30 14:00:00', NULL, 'registered', NULL),
(189, 96, 'S3009', '同學9', '高1年1班', '2025-10-30 14:45:00', NULL, 'registered', NULL),
(190, 96, 'S3010', '同學10', '高2年1班', '2025-10-30 15:30:00', NULL, 'registered', NULL),
(191, 96, 'S3011', '同學11', '高3年1班', '2025-10-30 16:15:00', NULL, 'registered', NULL),
(192, 96, 'S3012', '同學12', '高1年1班', '2025-10-30 17:00:00', NULL, 'registered', NULL),
(193, 96, 'S3013', '同學13', '高2年1班', '2025-10-30 17:45:00', NULL, 'registered', NULL),
(194, 96, 'S3014', '同學14', '高3年1班', '2025-10-30 18:30:00', NULL, 'registered', NULL),
(195, 96, 'S3015', '同學15', '高1年1班', '2025-10-30 19:15:00', NULL, 'registered', NULL),
(196, 96, 'S3016', '同學16', '高2年2班', '2025-10-30 20:00:00', NULL, 'registered', NULL),
(197, 96, 'S3017', '同學17', '高3年2班', '2025-10-30 20:45:00', NULL, 'registered', NULL),
(198, 96, 'S3018', '同學18', '高1年2班', '2025-10-30 21:30:00', NULL, 'registered', NULL),
(199, 96, 'S3019', '同學19', '高2年2班', '2025-10-30 22:15:00', NULL, 'registered', NULL),
(200, 96, 'S3020', '同學20', '高3年2班', '2025-10-30 23:00:00', NULL, 'registered', NULL),
(201, 96, 'S3021', '同學21', '高1年2班', '2025-10-30 23:45:00', NULL, 'registered', NULL),
(202, 96, 'S3022', '同學22', '高2年2班', '2025-10-31 00:30:00', NULL, 'registered', NULL),
(203, 96, 'S3023', '同學23', '高3年2班', '2025-10-31 01:15:00', NULL, 'registered', NULL),
(204, 96, 'S3024', '同學24', '高1年2班', '2025-10-31 02:00:00', NULL, 'registered', NULL),
(205, 96, 'S3025', '同學25', '高2年2班', '2025-10-31 02:45:00', NULL, 'registered', NULL),
(206, 96, 'S3026', '同學26', '高3年2班', '2025-10-31 03:30:00', NULL, 'registered', NULL),
(207, 96, 'S3027', '同學27', '高1年2班', '2025-10-31 04:15:00', NULL, 'registered', NULL),
(208, 96, 'S3028', '同學28', '高2年2班', '2025-10-31 05:00:00', NULL, 'registered', NULL),
(212, 97, 'S4001', '參賽者1', '高2年1班', '2025-11-10 08:20:00', NULL, 'registered', NULL),
(213, 97, 'S4002', '參賽者2', '高3年1班', '2025-11-10 08:40:00', NULL, 'registered', NULL),
(214, 97, 'S4003', '參賽者3', '高1年1班', '2025-11-10 09:00:00', NULL, 'registered', NULL),
(215, 97, 'S4004', '參賽者4', '高2年1班', '2025-11-10 09:20:00', NULL, 'registered', NULL),
(216, 97, 'S4005', '參賽者5', '高3年1班', '2025-11-10 09:40:00', NULL, 'registered', NULL),
(217, 97, 'S4006', '參賽者6', '高1年1班', '2025-11-10 10:00:00', NULL, 'registered', NULL),
(218, 97, 'S4007', '參賽者7', '高2年1班', '2025-11-10 10:20:00', NULL, 'registered', NULL),
(219, 97, 'S4008', '參賽者8', '高3年1班', '2025-11-10 10:40:00', NULL, 'registered', NULL),
(220, 97, 'S4009', '參賽者9', '高1年1班', '2025-11-10 11:00:00', NULL, 'registered', NULL),
(221, 97, 'S4010', '參賽者10', '高2年1班', '2025-11-10 11:20:00', NULL, 'registered', NULL),
(222, 97, 'S4011', '參賽者11', '高3年1班', '2025-11-10 11:40:00', NULL, 'registered', NULL),
(223, 97, 'S4012', '參賽者12', '高1年1班', '2025-11-10 12:00:00', NULL, 'registered', NULL),
(224, 97, 'S4013', '參賽者13', '高2年1班', '2025-11-10 12:20:00', NULL, 'registered', NULL),
(225, 97, 'S4014', '參賽者14', '高3年1班', '2025-11-10 12:40:00', NULL, 'registered', NULL),
(226, 97, 'S4015', '參賽者15', '高1年1班', '2025-11-10 13:00:00', NULL, 'registered', NULL),
(227, 97, 'S4016', '參賽者16', '高2年1班', '2025-11-10 13:20:00', NULL, 'registered', NULL),
(228, 97, 'S4017', '參賽者17', '高3年1班', '2025-11-10 13:40:00', NULL, 'registered', NULL),
(229, 97, 'S4018', '參賽者18', '高1年1班', '2025-11-10 14:00:00', NULL, 'registered', NULL),
(230, 97, 'S4019', '參賽者19', '高2年2班', '2025-11-10 14:20:00', NULL, 'registered', NULL),
(231, 97, 'S4020', '參賽者20', '高3年2班', '2025-11-10 14:40:00', NULL, 'registered', NULL),
(232, 97, 'S4021', '參賽者21', '高1年2班', '2025-11-10 15:00:00', NULL, 'registered', NULL),
(233, 97, 'S4022', '參賽者22', '高2年2班', '2025-11-10 15:20:00', NULL, 'registered', NULL),
(234, 97, 'S4023', '參賽者23', '高3年2班', '2025-11-10 15:40:00', NULL, 'registered', NULL),
(235, 97, 'S4024', '參賽者24', '高1年2班', '2025-11-10 16:00:00', NULL, 'registered', NULL),
(236, 97, 'S4025', '參賽者25', '高2年2班', '2025-11-10 16:20:00', NULL, 'registered', NULL),
(237, 97, 'S4026', '參賽者26', '高3年2班', '2025-11-10 16:40:00', NULL, 'registered', NULL),
(238, 97, 'S4027', '參賽者27', '高1年2班', '2025-11-10 17:00:00', NULL, 'registered', NULL),
(239, 97, 'S4028', '參賽者28', '高2年2班', '2025-11-10 17:20:00', NULL, 'registered', NULL),
(240, 97, 'S4029', '參賽者29', '高3年2班', '2025-11-10 17:40:00', NULL, 'registered', NULL),
(241, 97, 'S4030', '參賽者30', '高1年2班', '2025-11-10 18:00:00', NULL, 'registered', NULL),
(242, 97, 'S4031', '參賽者31', '高2年2班', '2025-11-10 18:20:00', NULL, 'registered', NULL),
(243, 97, 'S4032', '參賽者32', '高3年2班', '2025-11-10 18:40:00', NULL, 'registered', NULL),
(244, 97, 'S4033', '參賽者33', '高1年2班', '2025-11-10 19:00:00', NULL, 'registered', NULL),
(245, 97, 'S4034', '參賽者34', '高2年2班', '2025-11-10 19:20:00', NULL, 'registered', NULL),
(246, 97, 'S4035', '參賽者35', '高3年2班', '2025-11-10 19:40:00', NULL, 'registered', NULL),
(247, 97, 'S4036', '參賽者36', '高1年2班', '2025-11-10 20:00:00', NULL, 'registered', NULL),
(248, 97, 'S4037', '參賽者37', '高2年3班', '2025-11-10 20:20:00', NULL, 'registered', NULL),
(249, 97, 'S4038', '參賽者38', '高3年3班', '2025-11-10 20:40:00', NULL, 'registered', NULL),
(250, 97, 'S4039', '參賽者39', '高1年3班', '2025-11-10 21:00:00', NULL, 'registered', NULL),
(251, 97, 'S4040', '參賽者40', '高2年3班', '2025-11-10 21:20:00', NULL, 'registered', NULL),
(252, 97, 'S4041', '參賽者41', '高3年3班', '2025-11-10 21:40:00', NULL, 'registered', NULL),
(253, 97, 'S4042', '參賽者42', '高1年3班', '2025-11-10 22:00:00', NULL, 'registered', NULL),
(254, 97, 'S4043', '參賽者43', '高2年3班', '2025-11-10 22:20:00', NULL, 'registered', NULL),
(255, 97, 'S4044', '參賽者44', '高3年3班', '2025-11-10 22:40:00', NULL, 'registered', NULL),
(256, 97, 'S4045', '參賽者45', '高1年3班', '2025-11-10 23:00:00', NULL, 'registered', NULL),
(275, 98, 'S5001', '選手1', '高2年1班', '2025-11-25 09:00:00', NULL, 'registered', NULL),
(276, 98, 'S5002', '選手2', '高3年1班', '2025-11-25 10:00:00', NULL, 'registered', NULL),
(277, 98, 'S5003', '選手3', '高1年1班', '2025-11-25 11:00:00', NULL, 'registered', NULL),
(278, 98, 'S5004', '選手4', '高2年1班', '2025-11-25 12:00:00', NULL, 'registered', NULL),
(279, 98, 'S5005', '選手5', '高3年1班', '2025-11-25 13:00:00', NULL, 'registered', NULL),
(280, 98, 'S5006', '選手6', '高1年1班', '2025-11-25 14:00:00', NULL, 'registered', NULL),
(281, 98, 'S5007', '選手7', '高2年1班', '2025-11-25 15:00:00', NULL, 'registered', NULL),
(282, 98, 'S5008', '選手8', '高3年1班', '2025-11-25 16:00:00', NULL, 'registered', NULL),
(283, 98, 'S5009', '選手9', '高1年2班', '2025-11-25 17:00:00', NULL, 'registered', NULL),
(284, 98, 'S5010', '選手10', '高2年2班', '2025-11-25 18:00:00', NULL, 'registered', NULL),
(285, 98, 'S5011', '選手11', '高3年2班', '2025-11-25 19:00:00', NULL, 'registered', NULL),
(286, 98, 'S5012', '選手12', '高1年2班', '2025-11-25 20:00:00', NULL, 'registered', NULL),
(287, 98, 'S5013', '選手13', '高2年2班', '2025-11-25 21:00:00', NULL, 'registered', NULL),
(288, 98, 'S5014', '選手14', '高3年2班', '2025-11-25 22:00:00', NULL, 'registered', NULL),
(289, 98, 'S5015', '選手15', '高1年2班', '2025-11-25 23:00:00', NULL, 'registered', NULL),
(290, 103, 'sciclub', 'Student abc', '資二忠', '2025-10-24 11:52:25', NULL, 'registered', NULL),
(291, 107, 'student001', 'AAA', '資一忠', '2025-10-24 12:06:28', NULL, 'registered', NULL),
(292, 103, 'teacher01', 'BBB', '資四忠', '2025-10-24 16:07:17', NULL, 'registered', NULL),
(293, 117, 'student001', 'BBB', '資一忠', '2025-10-26 19:47:16', NULL, 'registered', NULL);

--
-- Triggers `registrations`
--
DELIMITER $$
CREATE TRIGGER `after_registration_cancel` AFTER UPDATE ON `registrations` FOR EACH ROW BEGIN
    IF NEW.status = 'cancelled' AND OLD.status = 'registered' THEN
        UPDATE `activities` 
        SET current_participants = current_participants - 1 
        WHERE activity_id = NEW.activity_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_registration_insert` AFTER INSERT ON `registrations` FOR EACH ROW BEGIN
    -- 建立報名成功通知
    INSERT INTO `notifications` 
    (`recipient_type`, `recipient_account`, `notification_type`, `title`, `message`, `related_type`, `related_id`)
    SELECT 'student', NEW.student_number, 'registration', '報名成功', 
           CONCAT('您已成功報名「', activity_name, '」活動'), 
           'activity', NEW.activity_id
    FROM `activities` WHERE activity_id = NEW.activity_id;
    
    -- 更新活動報名人數
    UPDATE `activities` 
    SET current_participants = current_participants + 1 
    WHERE activity_id = NEW.activity_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rollcall_records`
--

CREATE TABLE `rollcall_records` (
  `record_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL COMMENT '場次ID',
  `student_id` int(11) NOT NULL COMMENT '學生ID（student表的id）',
  `is_present` tinyint(1) DEFAULT 0 COMMENT '是否出席 0=未到 1=已到',
  `check_time` datetime DEFAULT NULL COMMENT '點名時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='點名記錄表';

--
-- Dumping data for table `rollcall_records`
--

INSERT INTO `rollcall_records` (`record_id`, `session_id`, `student_id`, `is_present`, `check_time`) VALUES
(1, 2, 1, 0, NULL),
(2, 2, 2, 0, NULL),
(3, 2, 3, 0, NULL),
(4, 3, 1, 0, NULL),
(5, 3, 2, 0, NULL),
(6, 3, 3, 0, NULL),
(7, 4, 1, 1, '2025-10-24 13:34:00'),
(8, 4, 2, 1, '2025-10-24 13:34:01'),
(9, 4, 3, 1, '2025-10-24 13:34:02'),
(10, 5, 1, 0, NULL),
(11, 5, 2, 0, NULL),
(12, 5, 3, 0, NULL),
(13, 6, 1, 1, '2025-10-24 16:03:14'),
(14, 6, 2, 1, '2025-10-24 16:03:16'),
(15, 6, 3, 1, '2025-10-24 16:03:16'),
(16, 7, 1, 0, NULL),
(17, 7, 2, 0, NULL),
(18, 7, 3, 0, NULL),
(19, 8, 1, 1, '2025-10-24 16:05:55'),
(20, 8, 2, 1, '2025-10-24 16:05:56'),
(21, 8, 3, 1, '2025-10-24 16:05:57'),
(22, 9, 1, 0, NULL),
(23, 9, 2, 0, NULL),
(24, 9, 3, 0, NULL),
(25, 10, 1, 0, NULL),
(26, 10, 2, 0, NULL),
(27, 10, 3, 0, NULL),
(28, 11, 1, 1, '2025-10-27 10:37:05'),
(29, 11, 2, 1, '2025-10-27 10:37:10'),
(30, 11, 3, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rollcall_sessions`
--

CREATE TABLE `rollcall_sessions` (
  `session_id` int(11) NOT NULL,
  `session_name` varchar(100) NOT NULL COMMENT '場次名稱',
  `session_date` date NOT NULL COMMENT '點名日期',
  `session_time` time NOT NULL COMMENT '點名時間',
  `created_by` int(11) NOT NULL COMMENT '創建教師ID',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='點名場次表';

--
-- Dumping data for table `rollcall_sessions`
--

INSERT INTO `rollcall_sessions` (`session_id`, `session_name`, `session_date`, `session_time`, `created_by`, `created_at`) VALUES
(1, 'test', '2025-10-24', '13:32:00', 1, '2025-10-24 07:32:11'),
(2, '例行點名_2025-10-24', '2025-10-24', '13:33:00', 10, '2025-10-24 13:33:19'),
(3, '例行點名_2025-10-24', '2025-10-24', '13:33:00', 10, '2025-10-24 13:33:32'),
(4, '例行點名_2025-10-24', '2025-10-24', '13:33:00', 10, '2025-10-24 13:33:57'),
(5, '例行點名_2025-10-24', '2025-10-24', '15:43:00', 10, '2025-10-24 15:43:29'),
(6, '例行點名_2025-10-24', '2025-10-24', '16:03:00', 6, '2025-10-24 16:03:12'),
(7, '例行點名_2025-10-24', '2025-10-24', '16:05:00', 6, '2025-10-24 16:05:22'),
(8, '例行點名_2025-10-24', '2025-10-24', '16:05:00', 6, '2025-10-24 16:05:50'),
(9, '例行點名_2025-10-24', '2025-10-24', '16:06:00', 6, '2025-10-24 16:06:04'),
(10, '例行點名_2025-10-27', '2025-10-27', '19:05:00', 6, '2025-10-26 19:05:03'),
(11, '例行點名_2025-10-27', '2025-10-27', '19:36:00', 6, '2025-10-26 19:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(11) NOT NULL,
  `week_start_date` date NOT NULL COMMENT '週起始日期(星期一)',
  `day_of_week` varchar(10) NOT NULL COMMENT '星期幾',
  `time_slot` varchar(20) NOT NULL COMMENT '時段 (例如: 08:00-09:00)',
  `staff_name` varchar(50) DEFAULT NULL COMMENT '值班人員姓名',
  `notes` text DEFAULT NULL COMMENT '備註',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='排班表';

-- --------------------------------------------------------

--
-- Table structure for table `science_club_groups`
--

CREATE TABLE `science_club_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(50) NOT NULL COMMENT '組別名稱',
  `description` text DEFAULT NULL COMMENT '組別描述',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='科學會組別表';

-- --------------------------------------------------------

--
-- Table structure for table `science_club_members`
--

CREATE TABLE `science_club_members` (
  `member_id` int(11) NOT NULL,
  `members_number` varchar(10) NOT NULL COMMENT '成員學號/編號',
  `members_name` varchar(20) NOT NULL COMMENT '成員姓名',
  `group_id` int(11) DEFAULT NULL COMMENT '組別ID',
  `position` varchar(50) DEFAULT NULL COMMENT '職位',
  `is_leader` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否為組長',
  `user_id` int(11) DEFAULT NULL COMMENT '關聯帳號ID',
  `join_date` date DEFAULT NULL COMMENT '加入日期',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='科學會成員表';

--
-- Dumping data for table `science_club_members`
--

INSERT INTO `science_club_members` (`member_id`, `members_number`, `members_name`, `group_id`, `position`, `is_leader`, `user_id`, `join_date`, `created_at`) VALUES
(1, '110534107', '唐佳臻', NULL, NULL, 1, 3, NULL, '2025-10-09 00:43:31'),
(2, '110534118', '潘星穎', NULL, NULL, 1, 4, NULL, '2025-10-09 00:43:31');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `number` varchar(10) NOT NULL COMMENT '學號',
  `student_name` varchar(20) NOT NULL COMMENT '姓名',
  `class` varchar(20) NOT NULL COMMENT '班級',
  `phone` varchar(20) DEFAULT NULL COMMENT '電話',
  `email` varchar(100) DEFAULT NULL COMMENT 'Email',
  `parent_phone` varchar(20) DEFAULT NULL COMMENT '家長電話',
  `user_id` int(11) DEFAULT NULL COMMENT '關聯帳號ID',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='學生資料表';

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `number`, `student_name`, `class`, `phone`, `email`, `parent_phone`, `user_id`, `created_at`) VALUES
(1, '110534107', '唐佳臻', '資五忠', NULL, NULL, NULL, 3, '2025-10-09 00:43:30'),
(2, '110534118', '潘星穎', '資五忠', NULL, NULL, NULL, 4, '2025-10-09 00:43:30'),
(3, '110534109', '蕭鈺萱', '資五忠', NULL, NULL, NULL, 12, '2025-10-09 00:43:30');

-- --------------------------------------------------------

--
-- Table structure for table `student_uploads`
--

CREATE TABLE `student_uploads` (
  `upload_id` int(11) NOT NULL,
  `zone_id` int(11) DEFAULT NULL COMMENT '上傳區ID',
  `student_number` varchar(10) NOT NULL COMMENT '學號',
  `student_name` varchar(50) DEFAULT NULL COMMENT '學生姓名',
  `title` varchar(100) NOT NULL COMMENT '標題',
  `image` varchar(200) NOT NULL COMMENT '檔案路徑',
  `file_extension` varchar(10) DEFAULT NULL COMMENT '檔案副檔名',
  `upload_type` enum('證照','獎狀','參賽證明','其他') NOT NULL COMMENT '上傳類型',
  `file_size` int(11) DEFAULT NULL COMMENT '檔案大小(bytes)',
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='學生上傳檔案';

--
-- Dumping data for table `student_uploads`
--

INSERT INTO `student_uploads` (`upload_id`, `zone_id`, `student_number`, `student_name`, `title`, `image`, `file_extension`, `upload_type`, `file_size`, `uploaded_at`) VALUES
(1, NULL, '110534107', NULL, '證照達人-軟體應用丙級', 'image/logo.jpg', 'jpg', '證照', NULL, '2025-07-30 03:58:45'),
(2, NULL, '110534107', NULL, '競賽達人-中文打字比賽', 'image/logo.jpg', 'jpg', '獎狀', NULL, '2025-07-30 04:01:56'),
(3, NULL, 'student001', 'student001', 'Screenshot 2025-10-19 at 13.24.27.png', 'uploads/students/68f47dc2c2902_1760853442.png', 'png', '證照', 183597, '2025-10-19 13:57:22');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL COMMENT '設定鍵',
  `setting_value` text DEFAULT NULL COMMENT '設定值',
  `description` varchar(200) DEFAULT NULL COMMENT '說明',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='系統設定';

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES
(1, 'notification_enabled', '1', '是否啟用通知系統', '2025-10-09 00:43:31'),
(2, 'reminder_days_before', '3', '活動前幾天提醒', '2025-10-09 00:43:31'),
(3, 'max_upload_size', '10', '檔案上傳大小上限(MB)', '2025-10-09 00:43:31'),
(4, 'school_name', '科學會管理系統', '學校名稱', '2025-10-09 00:43:31'),
(5, 'system_email', 'system@school.edu.tw', '系統Email', '2025-10-09 00:43:31');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `teacher_number` varchar(10) NOT NULL COMMENT '教師編號',
  `teacher_name` varchar(20) NOT NULL COMMENT '教師姓名',
  `department` varchar(50) DEFAULT NULL COMMENT '科系/部門',
  `phone` varchar(20) DEFAULT NULL COMMENT '電話',
  `email` varchar(100) DEFAULT NULL COMMENT 'Email',
  `user_id` int(11) DEFAULT NULL COMMENT '關聯帳號ID',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='教師資料表';

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `teacher_number`, `teacher_name`, `department`, `phone`, `email`, `user_id`, `created_at`) VALUES
(1, 'T001', '王老師', '資訊科', '0912-345-678', 'wang@school.edu', NULL, '2025-10-24 05:30:57'),
(2, 'T002', '李老師', '數學科', '0923-456-789', 'lee@school.edu', NULL, '2025-10-24 05:30:57'),
(3, 'T003', '張老師', '英文科', '0934-567-890', 'chang@school.edu', NULL, '2025-10-24 05:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_files`
--

CREATE TABLE `uploaded_files` (
  `file_id` int(11) NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` float DEFAULT NULL,
  `uploader` varchar(100) DEFAULT NULL,
  `upload_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upload_zones`
--

CREATE TABLE `upload_zones` (
  `zone_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `max_file_size` int(11) DEFAULT NULL,
  `max_files` int(11) DEFAULT NULL,
  `allowed_types` varchar(255) DEFAULT NULL,
  `status` enum('active','closed') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `account` varchar(50) NOT NULL COMMENT '帳號',
  `name` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL COMMENT '密碼',
  `role` enum('admin','teacher','student','science_club') NOT NULL COMMENT '角色:管理員/老師/學生/科學會',
  `email` varchar(100) DEFAULT NULL COMMENT 'Email',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '是否啟用',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='使用者帳號表';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `account`, `name`, `password`, `role`, `email`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin123', 'admin', 'admin@school.edu.tw', 1, '2025-10-09 00:43:30', '2025-10-22 04:09:03'),
(2, 't2541', 't2541', '345678', 'teacher', 'teacher@school.edu.tw', 1, '2025-10-09 00:43:30', '2025-10-22 04:09:03'),
(3, '110534107', '110534107', 'f231', 'science_club', NULL, 1, '2025-10-09 00:43:30', '2025-10-22 04:09:03'),
(4, '110534118', '110534118', 'a231', 'science_club', NULL, 1, '2025-10-09 00:43:30', '2025-10-22 04:09:03'),
(5, 'sciclub', 'sciclub', 'sciclub123', 'science_club', 'scienceclub@school.edu.tw', 1, '2025-10-09 00:43:30', '2025-10-22 04:09:03'),
(6, 'teacher01', 'teacher01', 'teacher123', 'teacher', 'teacher@school.edu.tw', 1, '2025-10-09 00:59:06', '2025-10-22 04:09:03'),
(7, 'student01', 'student01', 'student123', 'student', 'student@school.edu.tw', 1, '2025-10-09 00:59:06', '2025-10-22 04:09:03'),
(8, 'test-admin', 'test-admin', 'test', 'admin', '', 1, '2025-10-18 04:21:29', '2025-10-22 04:09:03'),
(9, 'test_stu', 'test_stu', 'stu', 'student', '', 1, '2025-10-19 03:52:20', '2025-10-22 04:09:03'),
(10, 'student001', 'student001', 'password123', 'student', 'student001@example.com', 1, '2025-10-19 04:32:40', '2025-10-22 04:09:03'),
(11, 'student002', 'student002', 'password123', 'student', 'student002@example.com', 1, '2025-10-19 04:32:40', '2025-10-22 04:09:03'),
(12, 'student003', 'student003', 'password123', 'student', 'student003@example.com', 1, '2025-10-19 04:32:40', '2025-10-22 04:09:03');

-- --------------------------------------------------------

--
-- Table structure for table `work_hours`
--

CREATE TABLE `work_hours` (
  `work_id` int(11) NOT NULL,
  `work_date` date NOT NULL COMMENT '工作日期',
  `staff_name` varchar(50) NOT NULL COMMENT '成員姓名',
  `shift_time` varchar(50) NOT NULL COMMENT '班次時段',
  `check_in_time` time DEFAULT NULL COMMENT '簽到時間',
  `check_out_time` time DEFAULT NULL COMMENT '簽退時間',
  `work_hours` decimal(5,2) DEFAULT 0.00 COMMENT '工作時數',
  `status` enum('present','late','absent') DEFAULT 'present' COMMENT '狀態',
  `notes` text DEFAULT NULL COMMENT '備註',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='工作時數記錄表';

--
-- Dumping data for table `work_hours`
--

INSERT INTO `work_hours` (`work_id`, `work_date`, `staff_name`, `shift_time`, `check_in_time`, `check_out_time`, `work_hours`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(7, '2025-10-24', '張老師', '早班 08:00-12:00', '12:00:00', '13:00:00', 1.00, 'present', '', '2025-10-24 15:30:23', NULL),
(8, '2025-10-24', '王老師', '晚班 18:00-22:00', '18:00:00', '22:00:00', 4.00, 'present', '', '2025-10-24 15:30:56', NULL),
(9, '2025-10-27', '李老師', '午班 13:00-17:00', '11:01:00', '11:11:00', 0.20, 'present', '', '2025-10-26 19:37:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `work_schedules`
--

CREATE TABLE `work_schedules` (
  `schedule_id` int(11) NOT NULL COMMENT '班表ID',
  `week_start_date` date NOT NULL COMMENT '週次起始日',
  `time_slot` varchar(20) NOT NULL COMMENT '時段 (例如: 08:00-09:00)',
  `day_of_week` varchar(20) NOT NULL COMMENT '星期 (例如: 星期一)',
  `staff_name` varchar(100) DEFAULT NULL COMMENT '執勤人員姓名',
  `shift_type` varchar(50) DEFAULT 'default' COMMENT '班次類型',
  `notes` text DEFAULT NULL COMMENT '備註',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '建立時間',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='排班表';

--
-- Dumping data for table `work_schedules`
--

INSERT INTO `work_schedules` (`schedule_id`, `week_start_date`, `time_slot`, `day_of_week`, `staff_name`, `shift_type`, `notes`, `created_at`, `updated_at`) VALUES
(10, '2025-10-22', '08:00-09:00', '星期一', '王老師', 'default', '', '2025-10-22 08:41:34', '2025-10-27 02:35:46'),
(11, '2025-10-22', '09:00-10:00', '星期一', '李老師', 'default', '', '2025-10-22 08:41:34', '2025-10-27 02:35:46'),
(12, '2025-10-22', '08:00-09:00', '星期二', '張老師', 'default', '', '2025-10-22 08:41:34', '2025-10-27 02:35:46'),
(33, '2025-10-20', '08:00-09:00', '星期一', 'a', 'default', '', '2025-10-24 08:02:47', '2025-10-24 08:02:47'),
(34, '2025-10-20', '09:00-10:00', '星期二', 'b', 'default', '', '2025-10-24 08:02:47', '2025-10-24 08:02:47'),
(35, '2025-10-20', '10:00-11:00', '星期三', 'c', 'default', '', '2025-10-24 08:02:47', '2025-10-24 08:02:47'),
(36, '2025-10-22', '09:00-10:00', '星期二', 'a', 'default', '', '2025-10-27 02:35:46', '2025-10-27 02:35:46'),
(37, '2025-10-22', '10:00-11:00', '星期三', 'a', 'default', '', '2025-10-27 02:35:47', '2025-10-27 02:35:47'),
(38, '2025-10-22', '11:00-12:00', '星期四', 'a', 'default', '', '2025-10-27 02:35:47', '2025-10-27 02:35:47'),
(39, '2025-10-22', '12:00-13:00', '星期五', 'a', 'default', '', '2025-10-27 02:35:47', '2025-10-27 02:35:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `idx_activity_date` (`start_date`,`end_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_account` (`admin_account`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `album_media`
--
ALTER TABLE `album_media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `album_id` (`album_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `idx_publish_date` (`publish_date`);

--
-- Indexes for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `attendance_sessions`
--
ALTER TABLE `attendance_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_date` (`session_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_creator` (`created_by`);

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `idx_event_date` (`event_date`),
  ADD KEY `idx_is_public` (`is_public`);

--
-- Indexes for table `competition_results`
--
ALTER TABLE `competition_results`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `feedback_forms`
--
ALTER TABLE `feedback_forms`
  ADD PRIMARY KEY (`form_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `feedback_responses`
--
ALTER TABLE `feedback_responses`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `file_upload_zones`
--
ALTER TABLE `file_upload_zones`
  ADD PRIMARY KEY (`zone_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `meeting_records`
--
ALTER TABLE `meeting_records`
  ADD PRIMARY KEY (`meeting_id`),
  ADD KEY `idx_meeting_date` (`meeting_date`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_recipient` (`recipient_type`,`recipient_account`),
  ADD KEY `idx_created` (`created_at`),
  ADD KEY `idx_read` (`is_read`);

--
-- Indexes for table `photo_albums`
--
ALTER TABLE `photo_albums`
  ADD PRIMARY KEY (`album_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD UNIQUE KEY `unique_registration` (`activity_id`,`student_number`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `idx_student` (`student_number`);

--
-- Indexes for table `rollcall_records`
--
ALTER TABLE `rollcall_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_student` (`student_id`);

--
-- Indexes for table `rollcall_sessions`
--
ALTER TABLE `rollcall_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_date` (`session_date`),
  ADD KEY `idx_teacher` (`created_by`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD UNIQUE KEY `unique_schedule` (`week_start_date`,`day_of_week`,`time_slot`),
  ADD KEY `idx_week` (`week_start_date`),
  ADD KEY `idx_day` (`day_of_week`);

--
-- Indexes for table `science_club_groups`
--
ALTER TABLE `science_club_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `science_club_members`
--
ALTER TABLE `science_club_members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `members_number` (`members_number`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `student_uploads`
--
ALTER TABLE `student_uploads`
  ADD PRIMARY KEY (`upload_id`),
  ADD KEY `zone_id` (`zone_id`),
  ADD KEY `idx_student` (`student_number`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `teacher_number` (`teacher_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `zone_id` (`zone_id`);

--
-- Indexes for table `upload_zones`
--
ALTER TABLE `upload_zones`
  ADD PRIMARY KEY (`zone_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account` (`account`),
  ADD KEY `idx_role` (`role`);

--
-- Indexes for table `work_hours`
--
ALTER TABLE `work_hours`
  ADD PRIMARY KEY (`work_id`),
  ADD KEY `idx_date` (`work_date`),
  ADD KEY `idx_staff` (`staff_name`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD UNIQUE KEY `unique_schedule` (`week_start_date`,`time_slot`,`day_of_week`),
  ADD KEY `idx_week` (`week_start_date`),
  ADD KEY `idx_staff` (`staff_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `album_media`
--
ALTER TABLE `album_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `attendance_records`
--
ALTER TABLE `attendance_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `attendance_sessions`
--
ALTER TABLE `attendance_sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '場次ID', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `competition_results`
--
ALTER TABLE `competition_results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `feedback_forms`
--
ALTER TABLE `feedback_forms`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `feedback_responses`
--
ALTER TABLE `feedback_responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_upload_zones`
--
ALTER TABLE `file_upload_zones`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `meeting_records`
--
ALTER TABLE `meeting_records`
  MODIFY `meeting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `photo_albums`
--
ALTER TABLE `photo_albums`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;

--
-- AUTO_INCREMENT for table `rollcall_records`
--
ALTER TABLE `rollcall_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `rollcall_sessions`
--
ALTER TABLE `rollcall_sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `science_club_groups`
--
ALTER TABLE `science_club_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `science_club_members`
--
ALTER TABLE `science_club_members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_uploads`
--
ALTER TABLE `student_uploads`
  MODIFY `upload_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upload_zones`
--
ALTER TABLE `upload_zones`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `work_hours`
--
ALTER TABLE `work_hours`
  MODIFY `work_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `work_schedules`
--
ALTER TABLE `work_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '班表ID', AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `album_media`
--
ALTER TABLE `album_media`
  ADD CONSTRAINT `album_media_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `photo_albums` (`album_id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD CONSTRAINT `attendance_records_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `attendance_sessions` (`session_id`) ON DELETE CASCADE;

--
-- Constraints for table `competition_results`
--
ALTER TABLE `competition_results`
  ADD CONSTRAINT `competition_results_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_forms`
--
ALTER TABLE `feedback_forms`
  ADD CONSTRAINT `feedback_forms_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`) ON DELETE SET NULL;

--
-- Constraints for table `feedback_responses`
--
ALTER TABLE `feedback_responses`
  ADD CONSTRAINT `feedback_responses_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `feedback_forms` (`form_id`) ON DELETE CASCADE;

--
-- Constraints for table `file_upload_zones`
--
ALTER TABLE `file_upload_zones`
  ADD CONSTRAINT `file_upload_zones_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`) ON DELETE SET NULL;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`) ON DELETE CASCADE;

--
-- Constraints for table `science_club_members`
--
ALTER TABLE `science_club_members`
  ADD CONSTRAINT `fk_admin_members_group` FOREIGN KEY (`group_id`) REFERENCES `science_club_groups` (`group_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `science_club_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `student_uploads`
--
ALTER TABLE `student_uploads`
  ADD CONSTRAINT `student_uploads_ibfk_1` FOREIGN KEY (`zone_id`) REFERENCES `file_upload_zones` (`zone_id`) ON DELETE SET NULL;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  ADD CONSTRAINT `uploaded_files_ibfk_1` FOREIGN KEY (`zone_id`) REFERENCES `upload_zones` (`zone_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
