-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-08-07 03:41:23
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `im_management_system`
--

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================
-- 1. 核心使用者系統 (4種角色)
-- ============================================

-- 統一使用者帳號表 (整合原有 user 表)
CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `account` VARCHAR(50) NOT NULL COMMENT '帳號',
  `password` VARCHAR(255) NOT NULL COMMENT '密碼',
  `role` ENUM('admin', 'teacher', 'student', 'science_club') NOT NULL COMMENT '角色:管理員/老師/學生/科學會',
  `email` VARCHAR(100) DEFAULT NULL COMMENT 'Email',
  `is_active` TINYINT(1) DEFAULT 1 COMMENT '是否啟用',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`),
  INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='使用者帳號表';

-- 學生資料表 (整合原有 student 表)
CREATE TABLE IF NOT EXISTS `student` (
  `student_id` INT(11) NOT NULL AUTO_INCREMENT,
  `number` VARCHAR(10) NOT NULL COMMENT '學號',
  `student_name` VARCHAR(20) NOT NULL COMMENT '姓名',
  `class` VARCHAR(20) NOT NULL COMMENT '班級',
  `phone` VARCHAR(20) DEFAULT NULL COMMENT '電話',
  `email` VARCHAR(100) DEFAULT NULL COMMENT 'Email',
  `parent_phone` VARCHAR(20) DEFAULT NULL COMMENT '家長電話',
  `user_id` INT(11) DEFAULT NULL COMMENT '關聯帳號ID',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `number` (`number`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='學生資料表';

-- 科學會成員表 (整合原有 science_club_members)
CREATE TABLE IF NOT EXISTS `science_club_members` (
  `members_id` INT(11) NOT NULL AUTO_INCREMENT,
  `members_number` VARCHAR(10) NOT NULL COMMENT '成員學號/編號',
  `members_name` VARCHAR(20) NOT NULL COMMENT '成員姓名',
  `group_id` INT(11) DEFAULT NULL COMMENT '組別ID',
  `position` VARCHAR(50) DEFAULT NULL COMMENT '職位',
  `is_leader` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否為組長',
  `user_id` INT(11) DEFAULT NULL COMMENT '關聯帳號ID',
  `join_date` DATE DEFAULT NULL COMMENT '加入日期',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`members_id`),
  UNIQUE KEY `members_number` (`members_number`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='科學會成員表';

-- 科學會組別表 (整合原有 science_club_groups)
CREATE TABLE IF NOT EXISTS `science_club_groups` (
  `group_id` INT(11) NOT NULL AUTO_INCREMENT,
  `group_name` VARCHAR(50) NOT NULL COMMENT '組別名稱',
  `description` TEXT DEFAULT NULL COMMENT '組別描述',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='科學會組別表';

-- 老師資料表 (新增)
CREATE TABLE IF NOT EXISTS `teachers` (
  `teacher_id` INT(11) NOT NULL AUTO_INCREMENT,
  `teacher_number` VARCHAR(10) NOT NULL COMMENT '教師編號',
  `teacher_name` VARCHAR(20) NOT NULL COMMENT '教師姓名',
  `department` VARCHAR(50) DEFAULT NULL COMMENT '科系/部門',
  `phone` VARCHAR(20) DEFAULT NULL COMMENT '電話',
  `email` VARCHAR(100) DEFAULT NULL COMMENT 'Email',
  `user_id` INT(11) DEFAULT NULL COMMENT '關聯帳號ID',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `teacher_number` (`teacher_number`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='教師資料表';

-- ============================================
-- 2. 功能1：行事曆系統
-- ============================================

CREATE TABLE IF NOT EXISTS `calendar_events` (
  `event_id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL COMMENT '活動標題',
  `description` TEXT DEFAULT NULL COMMENT '活動描述',
  `event_date` DATE NOT NULL COMMENT '活動日期',
  `start_time` TIME DEFAULT NULL COMMENT '開始時間',
  `end_time` TIME DEFAULT NULL COMMENT '結束時間',
  `event_type` ENUM('competition', 'activity', 'meeting', 'other') NOT NULL COMMENT '活動類型:比賽/活動/會議/其他',
  `color` VARCHAR(20) NOT NULL DEFAULT 'red' COMMENT '顏色標記',
  `is_public` TINYINT(1) DEFAULT 0 COMMENT '是否公開給學生(紅底=1,橘底=0)',
  `location` VARCHAR(200) DEFAULT NULL COMMENT '地點',
  `created_by_account` VARCHAR(50) DEFAULT NULL COMMENT '建立者帳號',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`event_id`),
  INDEX `idx_event_date` (`event_date`),
  INDEX `idx_is_public` (`is_public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='行事曆活動';

-- ============================================
-- 3. 功能2：活動相簿系統
-- ============================================

CREATE TABLE IF NOT EXISTS `photo_albums` (
  `album_id` INT(11) NOT NULL AUTO_INCREMENT,
  `album_name` VARCHAR(200) NOT NULL COMMENT '相簿名稱',
  `activity_name` VARCHAR(200) NOT NULL COMMENT '活動名稱',
  `description` TEXT DEFAULT NULL COMMENT '相簿描述',
  `cover_image` VARCHAR(255) DEFAULT NULL COMMENT '封面圖片路徑',
  `is_public` TINYINT(1) DEFAULT 1 COMMENT '是否公開',
  `created_by` VARCHAR(50) DEFAULT NULL COMMENT '建立者',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='活動相簿';

CREATE TABLE IF NOT EXISTS `album_media` (
  `media_id` INT(11) NOT NULL AUTO_INCREMENT,
  `album_id` INT(11) NOT NULL COMMENT '相簿ID',
  `media_type` ENUM('image', 'video') NOT NULL COMMENT '媒體類型',
  `file_path` VARCHAR(255) NOT NULL COMMENT '檔案路徑',
  `file_name` VARCHAR(200) DEFAULT NULL COMMENT '檔案名稱',
  `description` TEXT DEFAULT NULL COMMENT '描述',
  `upload_order` INT(11) DEFAULT 0 COMMENT '排序順序',
  `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`media_id`),
  KEY `album_id` (`album_id`),
  FOREIGN KEY (`album_id`) REFERENCES `photo_albums`(`album_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='相簿媒體檔案';

-- ============================================
-- 4. 功能3：公告系統
-- ============================================

CREATE TABLE IF NOT EXISTS `announcements` (
  `announcement_id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL COMMENT '公告標題',
  `content` TEXT NOT NULL COMMENT '公告內容',
  `announcement_type` ENUM('general', 'competition', 'activity', 'award') NOT NULL COMMENT '公告類型',
  `is_important` TINYINT(1) DEFAULT 0 COMMENT '是否重要',
  `has_detail` TINYINT(1) DEFAULT 0 COMMENT '是否有詳細內容',
  `detail_content` TEXT DEFAULT NULL COMMENT '詳細內容',
  `attachment` VARCHAR(255) DEFAULT NULL COMMENT '附件路徑',
  `publish_date` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '發佈日期',
  `created_by` VARCHAR(50) DEFAULT NULL COMMENT '建立者',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`announcement_id`),
  INDEX `idx_publish_date` (`publish_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='公告';

-- ============================================
-- 5. 功能4：活動/比賽發佈系統
-- ============================================

CREATE TABLE IF NOT EXISTS `activities` (
  `activity_id` INT(11) NOT NULL AUTO_INCREMENT,
  `activity_name` VARCHAR(200) NOT NULL COMMENT '活動名稱',
  `activity_type` ENUM('competition', 'activity', 'workshop', 'seminar') NOT NULL COMMENT '活動類型',
  `description` TEXT DEFAULT NULL COMMENT '活動描述',
  `start_date` DATE NOT NULL COMMENT '活動開始日期',
  `end_date` DATE DEFAULT NULL COMMENT '活動結束日期',
  `registration_start` DATETIME NOT NULL COMMENT '報名開始時間',
  `registration_end` DATETIME NOT NULL COMMENT '報名截止時間',
  `modify_deadline` DATETIME DEFAULT NULL COMMENT '修改截止時間',
  `max_participants` INT(11) DEFAULT NULL COMMENT '人數上限',
  `current_participants` INT(11) DEFAULT 0 COMMENT '目前報名人數',
  `location` VARCHAR(200) DEFAULT NULL COMMENT '地點',
  `requirements` TEXT DEFAULT NULL COMMENT '參加要求',
  `status` ENUM('draft', 'published', 'closed', 'cancelled') DEFAULT 'draft' COMMENT '狀態',
  `created_by` VARCHAR(50) DEFAULT NULL COMMENT '建立者',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`activity_id`),
  INDEX `idx_activity_date` (`start_date`, `end_date`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='活動/比賽';

-- ============================================
-- 6. 功能6：報名系統
-- ============================================

CREATE TABLE IF NOT EXISTS `registrations` (
  `registration_id` INT(11) NOT NULL AUTO_INCREMENT,
  `activity_id` INT(11) NOT NULL COMMENT '活動ID',
  `student_number` VARCHAR(10) NOT NULL COMMENT '學號',
  `student_name` VARCHAR(50) NOT NULL COMMENT '姓名',
  `student_class` VARCHAR(20) NOT NULL COMMENT '班級',
  `registration_time` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '報名時間',
  `modified_time` DATETIME DEFAULT NULL COMMENT '修改時間',
  `status` ENUM('registered', 'cancelled', 'attended', 'absent') DEFAULT 'registered' COMMENT '狀態',
  `notes` TEXT DEFAULT NULL COMMENT '備註',
  PRIMARY KEY (`registration_id`),
  UNIQUE KEY `unique_registration` (`activity_id`, `student_number`),
  KEY `activity_id` (`activity_id`),
  KEY `idx_student` (`student_number`),
  FOREIGN KEY (`activity_id`) REFERENCES `activities`(`activity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='活動報名';

-- ============================================
-- 7. 功能5：反饋表單系統
-- ============================================

CREATE TABLE IF NOT EXISTS `feedback_forms` (
  `form_id` INT(11) NOT NULL AUTO_INCREMENT,
  `form_name` VARCHAR(200) NOT NULL COMMENT '表單名稱',
  `activity_name` VARCHAR(200) NOT NULL COMMENT '活動名稱',
  `activity_id` INT(11) DEFAULT NULL COMMENT '關聯活動ID',
  `description` TEXT DEFAULT NULL COMMENT '表單說明',
  `is_active` TINYINT(1) DEFAULT 1 COMMENT '是否開放填寫',
  `created_by` VARCHAR(50) DEFAULT NULL COMMENT '建立者',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`form_id`),
  KEY `activity_id` (`activity_id`),
  FOREIGN KEY (`activity_id`) REFERENCES `activities`(`activity_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='反饋表單';

CREATE TABLE IF NOT EXISTS `feedback_responses` (
  `response_id` INT(11) NOT NULL AUTO_INCREMENT,
  `form_id` INT(11) NOT NULL COMMENT '表單ID',
  `student_number` VARCHAR(10) NOT NULL COMMENT '學號',
  `student_name` VARCHAR(50) NOT NULL COMMENT '姓名',
  `student_class` VARCHAR(20) NOT NULL COMMENT '班級',
  `satisfaction_score` TINYINT(4) NOT NULL COMMENT '滿意度(1-5)',
  `feedback_text` TEXT DEFAULT NULL COMMENT '開放式意見',
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`response_id`),
  KEY `form_id` (`form_id`),
  FOREIGN KEY (`form_id`) REFERENCES `feedback_forms`(`form_id`) ON DELETE CASCADE,
  CHECK (`satisfaction_score` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='反饋回應';

-- ============================================
-- 8. 功能7：比賽成績統計
-- ============================================

CREATE TABLE IF NOT EXISTS `competition_results` (
  `result_id` INT(11) NOT NULL AUTO_INCREMENT,
  `activity_id` INT(11) NOT NULL COMMENT '比賽ID',
  `student_number` VARCHAR(10) NOT NULL COMMENT '學號',
  `student_name` VARCHAR(50) NOT NULL COMMENT '姓名',
  `student_class` VARCHAR(20) NOT NULL COMMENT '班級',
  `score` DECIMAL(10,2) DEFAULT NULL COMMENT '分數',
  `rank` INT(11) DEFAULT NULL COMMENT '名次',
  `award` VARCHAR(50) DEFAULT NULL COMMENT '獎項',
  `notes` TEXT DEFAULT NULL COMMENT '備註',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`result_id`),
  KEY `activity_id` (`activity_id`),
  FOREIGN KEY (`activity_id`) REFERENCES `activities`(`activity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='比賽成績';

-- ============================================
-- 9. 功能8：檔案上傳系統 (整合原有 student_uploads)
-- ============================================

CREATE TABLE IF NOT EXISTS `file_upload_zones` (
  `zone_id` INT(11) NOT NULL AUTO_INCREMENT,
  `zone_name` VARCHAR(200) NOT NULL COMMENT '上傳區名稱',
  `description` TEXT DEFAULT NULL COMMENT '說明',
  `activity_id` INT(11) DEFAULT NULL COMMENT '關聯活動ID',
  `is_active` TINYINT(1) DEFAULT 1 COMMENT '是否開放上傳',
  `file_types` VARCHAR(200) DEFAULT NULL COMMENT '允許的檔案類型',
  `max_file_size` INT(11) DEFAULT 10 COMMENT '檔案大小上限(MB)',
  `deadline` DATETIME DEFAULT NULL COMMENT '上傳截止時間',
  `created_by` VARCHAR(50) DEFAULT NULL COMMENT '建立者',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`zone_id`),
  KEY `activity_id` (`activity_id`),
  FOREIGN KEY (`activity_id`) REFERENCES `activities`(`activity_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='檔案上傳區';

CREATE TABLE IF NOT EXISTS `student_uploads` (
  `upload_id` INT(11) NOT NULL AUTO_INCREMENT,
  `zone_id` INT(11) DEFAULT NULL COMMENT '上傳區ID',
  `student_number` VARCHAR(10) NOT NULL COMMENT '學號',
  `student_name` VARCHAR(50) DEFAULT NULL COMMENT '學生姓名',
  `title` VARCHAR(100) NOT NULL COMMENT '標題',
  `image` VARCHAR(200) NOT NULL COMMENT '檔案路徑',
  `upload_type` ENUM('證照','獎狀','參賽證明','其他') NOT NULL COMMENT '上傳類型',
  `file_size` INT(11) DEFAULT NULL COMMENT '檔案大小(bytes)',
  `uploaded_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`upload_id`),
  KEY `zone_id` (`zone_id`),
  KEY `idx_student` (`student_number`),
  FOREIGN KEY (`zone_id`) REFERENCES `file_upload_zones`(`zone_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='學生上傳檔案';

-- ============================================
-- 10. 功能9：線上簽到系統
-- ============================================

CREATE TABLE IF NOT EXISTS `attendance_sessions` (
  `session_id` INT(11) NOT NULL AUTO_INCREMENT,
  `session_name` VARCHAR(200) NOT NULL COMMENT '簽到名稱',
  `session_date` DATE NOT NULL COMMENT '日期',
  `start_time` TIME DEFAULT NULL COMMENT '開始時間',
  `end_time` TIME DEFAULT NULL COMMENT '結束時間',
  `location` VARCHAR(200) DEFAULT NULL COMMENT '地點',
  `is_active` TINYINT(1) DEFAULT 1 COMMENT '是否開放簽到',
  `created_by` VARCHAR(50) DEFAULT NULL COMMENT '建立者',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='簽到場次';

CREATE TABLE IF NOT EXISTS `attendance_records` (
  `record_id` INT(11) NOT NULL AUTO_INCREMENT,
  `session_id` INT(11) NOT NULL COMMENT '場次ID',
  `member_name` VARCHAR(50) NOT NULL COMMENT '成員姓名',
  `member_number` VARCHAR(20) DEFAULT NULL COMMENT '成員編號',
  `check_in_time` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '簽到時間',
  `status` ENUM('present', 'late', 'absent', 'leave') DEFAULT 'present' COMMENT '狀態',
  `notes` TEXT DEFAULT NULL COMMENT '備註',
  PRIMARY KEY (`record_id`),
  KEY `session_id` (`session_id`),
  FOREIGN KEY (`session_id`) REFERENCES `attendance_sessions`(`session_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='簽到紀錄';

-- ============================================
-- 11. 功能10：請假申請系統
-- ============================================

CREATE TABLE IF NOT EXISTS `leave_requests` (
  `request_id` INT(11) NOT NULL AUTO_INCREMENT,
  `applicant_name` VARCHAR(50) NOT NULL COMMENT '申請人姓名',
  `applicant_number` VARCHAR(20) NOT NULL COMMENT '申請人編號',
  `leave_type` ENUM('sick', 'personal', 'official', 'other') NOT NULL COMMENT '請假類型',
  `leave_date` DATE NOT NULL COMMENT '請假日期',
  `start_time` TIME DEFAULT NULL COMMENT '開始時間',
  `end_time` TIME DEFAULT NULL COMMENT '結束時間',
  `reason` TEXT NOT NULL COMMENT '請假原因',
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' COMMENT '審核狀態',
  `reviewed_by` VARCHAR(50) DEFAULT NULL COMMENT '審核者',
  `review_comment` TEXT DEFAULT NULL COMMENT '審核意見',
  `reviewed_at` DATETIME DEFAULT NULL COMMENT '審核時間',
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='請假申請';

-- ============================================
-- 12. 功能11：開會紀錄
-- ============================================

CREATE TABLE IF NOT EXISTS `meeting_records` (
  `meeting_id` INT(11) NOT NULL AUTO_INCREMENT,
  `meeting_title` VARCHAR(200) NOT NULL COMMENT '會議主題',
  `meeting_date` DATE NOT NULL COMMENT '會議日期',
  `start_time` TIME DEFAULT NULL COMMENT '開始時間',
  `end_time` TIME DEFAULT NULL COMMENT '結束時間',
  `location` VARCHAR(200) DEFAULT NULL COMMENT '地點',
  `attendees` TEXT DEFAULT NULL COMMENT '出席人員',
  `agenda` TEXT DEFAULT NULL COMMENT '會議議程',
  `content` TEXT NOT NULL COMMENT '會議內容',
  `decisions` TEXT DEFAULT NULL COMMENT '決議事項',
  `action_items` TEXT DEFAULT NULL COMMENT '待辦事項',
  `next_meeting` DATE DEFAULT NULL COMMENT '下次會議日期',
  `created_by` VARCHAR(50) DEFAULT NULL COMMENT '紀錄者',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`meeting_id`),
  INDEX `idx_meeting_date` (`meeting_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='開會紀錄';

-- ============================================
-- 13. 功能12：通知系統
-- ============================================

CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` INT(11) NOT NULL AUTO_INCREMENT,
  `recipient_type` ENUM('student', 'admin', 'teacher', 'science_club', 'all') NOT NULL COMMENT '接收者類型',
  `recipient_account` VARCHAR(50) DEFAULT NULL COMMENT '接收者帳號',
  `notification_type` ENUM('registration', 'announcement', 'reminder', 'deadline', 'approval', 'system') NOT NULL COMMENT '通知類型',
  `title` VARCHAR(200) NOT NULL COMMENT '通知標題',
  `message` TEXT NOT NULL COMMENT '通知內容',
  `related_type` VARCHAR(50) DEFAULT NULL COMMENT '關聯類型',
  `related_id` INT(11) DEFAULT NULL COMMENT '關聯ID',
  `is_read` TINYINT(1) DEFAULT 0 COMMENT '是否已讀',
  `read_at` DATETIME DEFAULT NULL COMMENT '閱讀時間',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  INDEX `idx_recipient` (`recipient_type`, `recipient_account`),
  INDEX `idx_created` (`created_at`),
  INDEX `idx_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='通知';

-- ============================================
-- 14. 系統設定
-- ============================================

CREATE TABLE IF NOT EXISTS `system_settings` (
  `setting_id` INT(11) NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL COMMENT '設定鍵',
  `setting_value` TEXT DEFAULT NULL COMMENT '設定值',
  `description` VARCHAR(200) DEFAULT NULL COMMENT '說明',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='系統設定';

-- ============================================
-- 初始資料 (保留並擴充原有資料)
-- ============================================

-- 插入預設使用者帳號 (保留原有帳號)
INSERT INTO `user` (`account`, `password`, `role`, `email`) VALUES 
('admin', '56789', 'admin', 'admin@school.edu.tw'),
('t2541', '345678', 'teacher', 'teacher@school.edu.tw'),
('110534107', 'f231', 'student', NULL),
('110534118', 'a231', 'student', NULL),
('sciclub', 'sciclub123', 'science_club', 'scienceclub@school.edu.tw');

-- 插入學生資料 (保留原有資料)
INSERT INTO `student` (`number`, `student_name`, `class`, `user_id`) VALUES
('110534107', '唐佳臻', '資五忠', (SELECT id FROM `user` WHERE account='110534107')),
('110534118', '潘星穎', '資五忠', (SELECT id FROM `user` WHERE account='110534118')),
('110534109', '蕭鈺萱', '資五忠', NULL);

-- 插入科學會成員資料 (保留原有資料)
INSERT INTO `science_club_members` (`members_number`, `members_name`, `is_leader`, `user_id`) VALUES
('110534107', '唐佳臻', 1, (SELECT id FROM `user` WHERE account='110534107')),
('110534118', '潘星穎', 1, (SELECT id FROM `user` WHERE account='110534118'));

-- 插入學生上傳資料 (保留原有資料)
INSERT INTO `student_uploads` (`title`, `image`, `upload_type`, `uploaded_at`, `student_number`) VALUES
('證照達人-軟體應用丙級', 'image/logo.jpg', '證照', '2025-07-30 03:58:45', '110534107'),
('競賽達人-中文打字比賽', 'image/logo.jpg', '獎狀', '2025-07-30 04:01:56', '110534107');

-- 插入系統設定
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `description`) VALUES
('notification_enabled', '1', '是否啟用通知系統'),
('reminder_days_before', '3', '活動前幾天提醒'),
('max_upload_size', '10', '檔案上傳大小上限(MB)'),
('school_name', '科學會管理系統', '學校名稱'),
('system_email', 'system@school.edu.tw', '系統Email');

-- ============================================
-- 索引優化
-- ============================================

ALTER TABLE `science_club_members`
  ADD CONSTRAINT `fk_science_club_members_group` FOREIGN KEY (`group_id`) REFERENCES `science_club_groups`(`group_id`) ON DELETE SET NULL;

-- ============================================
-- AUTO_INCREMENT 設定
-- ============================================

ALTER TABLE `user` AUTO_INCREMENT=6;
ALTER TABLE `student` AUTO_INCREMENT=4;
ALTER TABLE `science_club_members` AUTO_INCREMENT=3;
ALTER TABLE `science_club_groups` AUTO_INCREMENT=1;
ALTER TABLE `student_uploads` AUTO_INCREMENT=3;

-- ============================================
-- 觸發器 (自動化功能)
-- ============================================

DELIMITER //

-- 報名時自動建立通知並更新人數
CREATE TRIGGER `after_registration_insert`
AFTER INSERT ON `registrations`
FOR EACH ROW
BEGIN
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
END//

-- 取消報名時更新人數
CREATE TRIGGER `after_registration_cancel`
AFTER UPDATE ON `registrations`
FOR EACH ROW
BEGIN
    IF NEW.status = 'cancelled' AND OLD.status = 'registered' THEN
        UPDATE `activities` 
        SET current_participants = current_participants - 1 
        WHERE activity_id = NEW.activity_id;
    END IF;
END//

-- 公告發佈時通知所有學生
CREATE TRIGGER `after_announcement_insert`
AFTER INSERT ON `announcements`
FOR EACH ROW
BEGIN
    INSERT INTO `notifications` 
    (`recipient_type`, `notification_type`, `title`, `message`, `related_type`, `related_id`)
    VALUES ('all', 'announcement', '新公告', NEW.title, 'announcement', NEW.announcement_id);
END//

DELIMITER ;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;