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

-- --------------------------------------------------------

--
-- 資料表結構 `admin_groups`
--

CREATE TABLE `admin_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `admin_members`
--

CREATE TABLE `admin_members` (
  `members_id` int(11) NOT NULL,
  `members_number` varchar(10) NOT NULL,
  `members_name` varchar(20) NOT NULL,
  `is_leader` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `admin_members`
--

INSERT INTO `admin_members` (`members_id`, `members_number`, `members_name`, `is_leader`) VALUES
(1, '110534107', '唐佳臻', 1),
(2, '110534118', '潘星穎', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `created_by_account` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `student`
--

CREATE TABLE `student` (
  `student_id` int(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `student_name` varchar(20) NOT NULL,
  `class` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `student`
--

INSERT INTO `student` (`student_id`, `number`, `student_name`, `class`) VALUES
(1, '110534107', '唐佳臻', '資五忠'),
(2, '110534118', '潘星穎', '資五忠'),
(3, '110534109', '蕭鈺萱', '資五忠');

-- --------------------------------------------------------

--
-- 資料表結構 `student_uploads`
--

CREATE TABLE `student_uploads` (
  `upload_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `image` varchar(200) NOT NULL,
  `upload_type` enum('證照','獎狀','參賽證明') NOT NULL,
  `uploaded_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `student_uploads`
--

INSERT INTO `student_uploads` (`upload_id`, `title`, `image`, `upload_type`, `uploaded_at`) VALUES
(1, '證照達人-軟體應用丙級', 'image\\logo.jpg', '證照', '2025-07-30 03:58:45'),
(2, '競賽達人-中文打字比賽', 'image\\logo.jpg', '獎狀', '2025-07-30 04:01:56');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `account` text NOT NULL,
  `password` text NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`id`, `account`, `password`, `role`) VALUES
(1, '110534107', 'f231', 'student'),
(2, '110534118', 'a231', 'student'),
(3, 'admin', '56789', 'admin'),
(4, 't2541', '345678', 'teacher');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `admin_groups`
--
ALTER TABLE `admin_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- 資料表索引 `admin_members`
--
ALTER TABLE `admin_members`
  ADD PRIMARY KEY (`members_id`);

--
-- 資料表索引 `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- 資料表索引 `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`);

--
-- 資料表索引 `student_uploads`
--
ALTER TABLE `student_uploads`
  ADD PRIMARY KEY (`upload_id`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `admin_groups`
--
ALTER TABLE `admin_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `admin_members`
--
ALTER TABLE `admin_members`
  MODIFY `members_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `student_uploads`
--
ALTER TABLE `student_uploads`
  MODIFY `upload_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
