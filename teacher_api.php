<?php
/**
 * 教師專用 API - 完整可用版
 */

session_start();
include("pdo.php");

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// 測試模式
$test_mode = true;

if ($test_mode && !isset($_SESSION['account'])) {
    $_SESSION['account'] = 'teacher001';
    $_SESSION['teacher_name'] = '王老師';
    $_SESSION['role'] = 'teacher';
    $_SESSION['user_id'] = 1;
}

// 路由處理
switch ($action) {
    // 點名系統
    case 'get_student_list':
        getStudentList();
        break;
    case 'create_rollcall_session':
        createRollcallSession();
        break;
    case 'save_rollcall':
        saveRollcall();
        break;
    case 'export_rollcall':
        exportRollcall();
        break;
    
    // 排班系統
    case 'get_schedule_week':
        getScheduleWeek();
        break;
    case 'save_schedule_cell':
        saveScheduleCell();
        break;
    case 'clear_schedule_week':
        clearScheduleWeek();
        break;
    case 'export_schedule':
        exportSchedule();
        break;
    
    // 工作時數
    case 'get_work_hours_list':
        getWorkHoursList();
        break;
    case 'add_work_hour_record':
        addWorkHourRecord();
        break;
    case 'update_work_hour_record':
        updateWorkHourRecord();
        break;
    case 'delete_work_hour_record':
        deleteWorkHourRecord();
        break;
    case 'get_work_hour_stats':
        getWorkHourStats();
        break;

    case 'get_user_info':
        getUserInfo();
        break;
    
    // 1. 首頁統計數據
    case 'get_dashboard_stats':
        getDashboardStats();
        break;
    
    // 2. 活動管理
    case 'get_activities':
        getActivities();
        break;
    case 'get_activity_detail':
        getActivityDetail();
        break;
    case 'update_activity_status':
        updateActivityStatus();
        break;
    case 'pause_activity':
        pauseActivity();
        break;
    case 'cancel_activity':
        cancelActivity();
        break;
    
    // 3. 報名統計
    case 'get_registration_stats':
        getRegistrationStats();
        break;
    case 'get_activity_registrations':
        getActivityRegistrations();
        break;
    case 'export_registrations':
        exportRegistrations();
        break;
    
    // 4. 學生回饋
    case 'get_feedback_list':
        getFeedbackList();
        break;
    case 'get_feedback_detail':
        getFeedbackDetail();
        break;
    case 'mark_feedback_read':
        markFeedbackRead();
        break;
    case 'reply_feedback':
        replyFeedback();
        break;
    
    // 5. 排班管理
    case 'get_schedules':
        getSchedules();
        break;
    case 'save_schedule':
        saveSchedule();
        break;
    case 'clear_schedule':
        clearSchedule();
        break;
    
    // 6. 請假審核
    case 'get_leave_requests':
        getLeaveRequests();
        break;
    case 'approve_leave':
        approveLeave();
        break;
    case 'reject_leave':
        rejectLeave();
        break;
    case 'get_leave_stats':
        getLeaveStats();
        break;
    
    // 7. 點名系統
    case 'get_attendance_sessions':
        getAttendanceSessions();
        break;
    case 'create_attendance_session':
        createAttendanceSession();
        break;
    case 'get_session_members':
        getSessionMembers();
        break;
    case 'record_attendance':
        recordAttendance();
        break;
    case 'get_attendance_statistics':
        getAttendanceStatistics();
        break;
    
    // 8. 工作時數
    case 'get_work_hours':
        getWorkHours();
        break;
    case 'add_work_record':
        addWorkRecord();
        break;
    case 'update_work_record':
        updateWorkRecord();
        break;
    case 'delete_work_record':
        deleteWorkRecord();
        break;
    case 'get_work_stats':
        getWorkStats();
        break;
    case 'export_work_hours':
        exportWorkHours();
        break;

    case 'get_notifications':
        getNotifications();
        break;
    case 'mark_notification_read':
        markNotificationRead();
        break;
    case 'mark_all_notifications_read':
        markAllNotificationsRead();
        break;
    case 'delete_notification':
        deleteNotification();
        break;
    
    default:
        jsonResponse(false, '未知的操作: ' . $action);
}

/**
 * 獲取活動列表（含報名狀況）
 */
function getActivities() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                activity_id,
                activity_name,
                activity_type,
                start_date,
                end_date,
                location,
                max_participants,
                current_participants,
                status,
                description
            FROM activities 
            ORDER BY start_date DESC
        ");
        
        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($activities, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 獲取活動詳情
 */
function getActivityDetail() {
    global $pdo;
    
    $activity_id = $_GET['activity_id'] ?? 0;
    
    try {
        // 活動基本資訊
        $stmt = $pdo->prepare("SELECT * FROM activities WHERE activity_id = ?");
        $stmt->execute([$activity_id]);
        $activity = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$activity) {
            jsonResponse(false, '找不到此活動');
        }
        
        // 報名學生列表
        $stmt = $pdo->prepare("
            SELECT 
                r.registration_id,
                r.student_number,
                r.student_name,
                r.student_class,
                r.registration_time,
                r.status
            FROM registrations r
            WHERE r.activity_id = ?
            ORDER BY r.registration_time DESC
        ");
        $stmt->execute([$activity_id]);
        $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $activity['registrations'] = $registrations;
        
        jsonResponse(true, '獲取成功', $activity);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 更新活動狀態
 */
function updateActivityStatus() {
    global $pdo;
    
    $activity_id = $_POST['activity_id'] ?? 0;
    $status = $_POST['status'] ?? '';
    
    $allowed_status = ['draft', 'published', 'closed', 'cancelled'];
    if (!in_array($status, $allowed_status)) {
        jsonResponse(false, '無效的狀態值');
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE activities SET status = ? WHERE activity_id = ?");
        $result = $stmt->execute([$status, $activity_id]);
        
        jsonResponse($result, $result ? '狀態更新成功' : '更新失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '更新失敗: ' . $e->getMessage());
    }
}

/**
 * 暫停活動
 */
function pauseActivity() {
    global $pdo;
    
    $activity_id = $_POST['activity_id'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("UPDATE activities SET status = 'closed' WHERE activity_id = ?");
        $result = $stmt->execute([$activity_id]);
        
        jsonResponse($result, $result ? '活動已暫停' : '操作失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '操作失敗: ' . $e->getMessage());
    }
}

/**
 * 取消活動
 */
function cancelActivity() {
    global $pdo;
    
    $activity_id = $_POST['activity_id'] ?? 0;
    
    try {
        $pdo->beginTransaction();
        
        // 更新活動狀態
        $stmt = $pdo->prepare("UPDATE activities SET status = 'cancelled' WHERE activity_id = ?");
        $stmt->execute([$activity_id]);
        
        // 取消所有報名
        $stmt = $pdo->prepare("UPDATE registrations SET status = 'cancelled' WHERE activity_id = ?");
        $stmt->execute([$activity_id]);
        
        $pdo->commit();
        jsonResponse(true, '活動已取消');
        
    } catch (Exception $e) {
        $pdo->rollBack();
        jsonResponse(false, '操作失敗: ' . $e->getMessage());
    }
}

// ==========================================
// 3. 報名統計
// ==========================================

function getRegistrationStats() {
    global $pdo;
    
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-t');
    $category = $_GET['category'] ?? '';
    $range_type = $_GET['range_type'] ?? 'month'; // week, month, quarter, year
    
    try {
        // 計算上期日期範圍（用於趨勢對比）
        $date_diff = (strtotime($end_date) - strtotime($start_date)) / 86400 + 1;
        $prev_start_date = date('Y-m-d', strtotime($start_date) - ($date_diff * 86400));
        $prev_end_date = date('Y-m-d', strtotime($start_date) - 86400);
        
        // ===== 當期數據 =====
        
        // 總報名人次
        $sql = "SELECT COUNT(*) as total FROM registrations r 
                JOIN activities a ON r.activity_id = a.activity_id 
                WHERE r.registration_time BETWEEN ? AND ?";
        $params = [$start_date . ' 00:00:00', $end_date . ' 23:59:59'];
        
        if ($category) {
            $sql .= " AND a.activity_type = ?";
            $params[] = $category;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $totalRegistrations = $stmt->fetch()['total'];
        
        // 活躍活動數
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as total FROM activities 
            WHERE status IN ('published', 'closed') 
            AND start_date BETWEEN ? AND ?
        ");
        $stmt->execute([$start_date, $end_date]);
        $activeActivities = $stmt->fetch()['total'];
        
        // 平均報名率
        $stmt = $pdo->prepare("
            SELECT 
                AVG(CASE 
                    WHEN max_participants > 0 
                    THEN (current_participants / max_participants * 100)
                    ELSE 0 
                END) as avg_rate
            FROM activities 
            WHERE start_date BETWEEN ? AND ?
        ");
        $stmt->execute([$start_date, $end_date]);
        $avgFillRate = round($stmt->fetch()['avg_rate'] ?? 0);
        
        // 總參與人數
        $stmt = $pdo->prepare("
            SELECT SUM(current_participants) as total FROM activities 
            WHERE start_date BETWEEN ? AND ?
        ");
        $stmt->execute([$start_date, $end_date]);
        $totalParticipants = $stmt->fetch()['total'] ?? 0;
        
        // ===== 上期數據（計算趨勢）=====
        
        // 上期總報名人次
        $sql_prev = "SELECT COUNT(*) as total FROM registrations r 
                     JOIN activities a ON r.activity_id = a.activity_id 
                     WHERE r.registration_time BETWEEN ? AND ?";
        $params_prev = [$prev_start_date . ' 00:00:00', $prev_end_date . ' 23:59:59'];
        if ($category) {
            $sql_prev .= " AND a.activity_type = ?";
            $params_prev[] = $category;
        }
        $stmt = $pdo->prepare($sql_prev);
        $stmt->execute($params_prev);
        $prevRegistrations = $stmt->fetch()['total'];
        
        // 上期活躍活動數
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as total FROM activities 
            WHERE status IN ('published', 'closed') 
            AND start_date BETWEEN ? AND ?
        ");
        $stmt->execute([$prev_start_date, $prev_end_date]);
        $prevActivities = $stmt->fetch()['total'];
        
        // 上期平均報名率
        $stmt = $pdo->prepare("
            SELECT AVG(CASE 
                WHEN max_participants > 0 
                THEN (current_participants / max_participants * 100)
                ELSE 0 
            END) as avg_rate
            FROM activities 
            WHERE start_date BETWEEN ? AND ?
        ");
        $stmt->execute([$prev_start_date, $prev_end_date]);
        $prevAvgFillRate = round($stmt->fetch()['avg_rate'] ?? 0);
        
        // 上期總參與人數
        $stmt = $pdo->prepare("
            SELECT SUM(current_participants) as total FROM activities 
            WHERE start_date BETWEEN ? AND ?
        ");
        $stmt->execute([$prev_start_date, $prev_end_date]);
        $prevParticipants = $stmt->fetch()['total'] ?? 0;
        
        // ===== 計算趨勢百分比 =====
        $registrationsTrend = calculateTrend($totalRegistrations, $prevRegistrations);
        $activitiesTrend = calculateTrend($activeActivities, $prevActivities);
        $fillRateTrend = calculateTrend($avgFillRate, $prevAvgFillRate);
        $participantsTrend = calculateTrend($totalParticipants, $prevParticipants);
        
        // ===== 報名趨勢（根據時間範圍類型）=====
        $weeklyTrend = [];
        
        switch ($range_type) {
            case 'week':
                // 本週：顯示週一到週日
                $stmt = $pdo->prepare("
                    SELECT 
                        CASE DAYOFWEEK(DATE(registration_time))
                            WHEN 1 THEN '週一'
                            WHEN 2 THEN '週二'
                            WHEN 3 THEN '週三'
                            WHEN 4 THEN '週四'
                            WHEN 5 THEN '週五'
                            WHEN 6 THEN '週六'
                            WHEN 7 THEN '週日'
                            
                        END as day_name,
                        DAYOFWEEK(DATE(registration_time)) as day_num,
                        COUNT(*) as count
                    FROM registrations
                    WHERE registration_time BETWEEN ? AND ?
                    GROUP BY DATE(registration_time), day_name, day_num
                    ORDER BY day_num
                ");
                $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                $rawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // 補全缺失的星期（週日到週六）
                $dayNames = ['週一', '週二', '週三', '週四', '週五', '週六', '週日'];
                $weeklyTrend = [];
                
                foreach ($dayNames as $dayName) {
                    $found = false;
                    foreach ($rawData as $item) {
                        if ($item['day_name'] === $dayName) {
                            $weeklyTrend[] = $item;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $weeklyTrend[] = [
                            'day_name' => $dayName,
                            'count' => 0
                        ];
                    }
                }
                break;
                
            case 'month':
                // 本月：按日期顯示
                $stmt = $pdo->prepare("
                    SELECT 
                        DATE_FORMAT(registration_time, '%m/%d') as day_name,
                        COUNT(*) as count
                    FROM registrations
                    WHERE registration_time BETWEEN ? AND ?
                    GROUP BY DATE(registration_time)
                    ORDER BY DATE(registration_time)
                ");
                $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                $weeklyTrend = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
                
            case 'quarter':
                // 本季：顯示當季的月份
                $stmt = $pdo->prepare("
                    SELECT 
                        CASE MONTH(registration_time)
                            WHEN 1 THEN '1月'
                            WHEN 2 THEN '2月'
                            WHEN 3 THEN '3月'
                            WHEN 4 THEN '4月'
                            WHEN 5 THEN '5月'
                            WHEN 6 THEN '6月'
                            WHEN 7 THEN '7月'
                            WHEN 8 THEN '8月'
                            WHEN 9 THEN '9月'
                            WHEN 10 THEN '10月'
                            WHEN 11 THEN '11月'
                            WHEN 12 THEN '12月'
                        END as day_name,
                        MONTH(registration_time) as month_num,
                        COUNT(*) as count
                    FROM registrations
                    WHERE registration_time BETWEEN ? AND ?
                    GROUP BY YEAR(registration_time), MONTH(registration_time)
                    ORDER BY YEAR(registration_time), MONTH(registration_time)
                ");
                $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                $rawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // 補全當季的所有月份
                $startMonth = (int)date('n', strtotime($start_date));
                $endMonth = (int)date('n', strtotime($end_date));
                $monthNames = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];
                $weeklyTrend = [];
                
                for ($m = $startMonth; $m <= $endMonth; $m++) {
                    $monthName = $monthNames[$m - 1];
                    $found = false;
                    foreach ($rawData as $item) {
                        if ($item['day_name'] === $monthName) {
                            $weeklyTrend[] = $item;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $weeklyTrend[] = [
                            'day_name' => $monthName,
                            'count' => 0
                        ];
                    }
                }
                break;
                
            case 'year':
                // 本年：顯示1月到12月
                $stmt = $pdo->prepare("
                    SELECT 
                        CASE MONTH(registration_time)
                            WHEN 1 THEN '1月'
                            WHEN 2 THEN '2月'
                            WHEN 3 THEN '3月'
                            WHEN 4 THEN '4月'
                            WHEN 5 THEN '5月'
                            WHEN 6 THEN '6月'
                            WHEN 7 THEN '7月'
                            WHEN 8 THEN '8月'
                            WHEN 9 THEN '9月'
                            WHEN 10 THEN '10月'
                            WHEN 11 THEN '11月'
                            WHEN 12 THEN '12月'
                        END as day_name,
                        MONTH(registration_time) as month_num,
                        COUNT(*) as count
                    FROM registrations
                    WHERE registration_time BETWEEN ? AND ?
                    GROUP BY MONTH(registration_time)
                    ORDER BY MONTH(registration_time)
                ");
                $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                $rawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // 補全12個月
                $monthNames = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];
                $weeklyTrend = [];
                
                foreach ($monthNames as $monthName) {
                    $found = false;
                    foreach ($rawData as $item) {
                        if ($item['day_name'] === $monthName) {
                            $weeklyTrend[] = $item;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $weeklyTrend[] = [
                            'day_name' => $monthName,
                            'count' => 0
                        ];
                    }
                }
                break;
                
            default:
                // 自訂範圍：按日期顯示
                $stmt = $pdo->prepare("
                    SELECT 
                        DATE_FORMAT(registration_time, '%m/%d') as day_name,
                        COUNT(*) as count
                    FROM registrations
                    WHERE registration_time BETWEEN ? AND ?
                    GROUP BY DATE(registration_time)
                    ORDER BY DATE(registration_time)
                ");
                $stmt->execute([$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                $weeklyTrend = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
        }
        
        // ===== 活動類別分佈 =====
        $stmt = $pdo->prepare("
            SELECT 
                activity_type,
                COUNT(*) as count
            FROM activities
            WHERE start_date BETWEEN ? AND ?
            GROUP BY activity_type
            ORDER BY count DESC
        ");
        $stmt->execute([$start_date, $end_date]);
        $categoryDistribution = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(true, '獲取成功', [
            'totalRegistrations' => $totalRegistrations,
            'activeActivities' => $activeActivities,
            'avgFillRate' => $avgFillRate,
            'totalParticipants' => $totalParticipants,
            'registrationsTrend' => $registrationsTrend,
            'activitiesTrend' => $activitiesTrend,
            'fillRateTrend' => $fillRateTrend,
            'participantsTrend' => $participantsTrend,
            'weeklyTrend' => $weeklyTrend,
            'categoryDistribution' => $categoryDistribution,
            'debug' => [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'prev_start_date' => $prev_start_date,
                'prev_end_date' => $prev_end_date,
                'date_diff' => $date_diff,
                'range_type' => $range_type,
                'current_values' => [
                    'registrations' => $totalRegistrations,
                    'activities' => $activeActivities,
                    'fillRate' => $avgFillRate,
                    'participants' => $totalParticipants
                ],
                'previous_values' => [
                    'registrations' => $prevRegistrations,
                    'activities' => $prevActivities,
                    'fillRate' => $prevAvgFillRate,
                    'participants' => $prevParticipants
                ]
            ]
        ]);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 計算趨勢百分比
 */
function calculateTrend($current, $previous) {
    if ($previous == 0) {
        return $current > 0 ? 100 : 0;
    }
    return round((($current - $previous) / $previous) * 100, 1);
}

/**
 * 獲取特定活動的報名詳情
 */
function getActivityRegistrations() {
    global $pdo;
    
    $activity_id = $_GET['activity_id'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                r.registration_id,
                r.student_number,
                r.student_name,
                r.student_class,
                r.registration_time,
                r.status,
                s.number as student_id
            FROM registrations r
            LEFT JOIN student s ON r.student_number = s.number
            WHERE r.activity_id = ?
            ORDER BY r.registration_time DESC
        ");
        $stmt->execute([$activity_id]);
        $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($registrations, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

// ==========================================
// 4. 學生回饋
// ==========================================

/**
 * 獲取回饋列表
 */
function getFeedbackList() {
    global $pdo;
    
    $activity_filter = $_GET['activity'] ?? '';
    $rating_filter = $_GET['rating'] ?? '';
    $start_date = $_GET['start_date'] ?? '';
    $end_date = $_GET['end_date'] ?? '';
    
    try {
        $sql = "
            SELECT 
                fr.response_id,
                fr.student_name,
                fr.student_class,
                fr.satisfaction_score,
                fr.feedback_text,
                fr.submitted_at,
                fr.is_read,
                ff.form_name,
                ff.activity_name
            FROM feedback_responses fr
            JOIN feedback_forms ff ON fr.form_id = ff.form_id
            WHERE 1=1
        ";
        $params = [];
        
        if ($activity_filter) {
            $sql .= " AND ff.activity_name LIKE ?";
            $params[] = "%$activity_filter%";
        }
        
        if ($rating_filter) {
            $sql .= " AND fr.satisfaction_score >= ?";
            $params[] = $rating_filter;
        }
        
        if ($start_date && $end_date) {
            $sql .= " AND fr.submitted_at BETWEEN ? AND ?";
            $params[] = $start_date;
            $params[] = $end_date;
        }
        
        $sql .= " ORDER BY fr.submitted_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($feedbacks, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 標記回饋為已讀
 */
function markFeedbackRead() {
    global $pdo;
    
    $response_id = $_POST['response_id'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("UPDATE feedback_responses SET is_read = 1 WHERE response_id = ?");
        $result = $stmt->execute([$response_id]);
        
        jsonResponse($result, $result ? '已標記為已讀' : '操作失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '操作失敗: ' . $e->getMessage());
    }
}

// ==========================================
// 5. 排班管理
// ==========================================

/**
 * 獲取排班表
 * 注意：需要創建 schedules 表，或使用 attendance_sessions
 */
function getSchedules() {
    global $pdo;
    
    $week_start = $_GET['week_start'] ?? date('Y-m-d', strtotime('monday this week'));
    
    try {
        // 這裡使用 attendance_sessions 表來儲存排班
        $stmt = $pdo->prepare("
            SELECT 
                session_id,
                session_name,
                session_date,
                start_time,
                end_time,
                location
            FROM attendance_sessions
            WHERE session_date >= ? AND session_date < DATE_ADD(?, INTERVAL 7 DAY)
            ORDER BY session_date, start_time
        ");
        $stmt->execute([$week_start, $week_start]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($schedules, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 儲存排班表
 */
function saveSchedule() {
    global $pdo;
    
    // 接收 JSON 格式的排班數據
    $input = json_decode(file_get_contents('php://input'), true);
    $schedule_data = $input['scheduleData'] ?? [];
    
    if (empty($schedule_data)) {
        jsonResponse(false, '沒有排班資料');
    }
    
    try {
        $pdo->beginTransaction();
        
        $success_count = 0;
        foreach ($schedule_data as $item) {
            // 檢查是否已存在
            $stmt = $pdo->prepare("
                SELECT session_id FROM attendance_sessions 
                WHERE session_date = ? AND start_time = ?
            ");
            $stmt->execute([$item['date'], $item['time']]);
            
            if ($stmt->fetch()) {
                // 更新
                $stmt = $pdo->prepare("
                    UPDATE attendance_sessions 
                    SET session_name = ?
                    WHERE session_date = ? AND start_time = ?
                ");
                $stmt->execute([$item['staff'], $item['date'], $item['time']]);
            } else {
                // 新增
                $stmt = $pdo->prepare("
                    INSERT INTO attendance_sessions 
                    (session_name, session_date, start_time, is_active)
                    VALUES (?, ?, ?, 1)
                ");
                $stmt->execute([$item['staff'], $item['date'], $item['time']]);
            }
            $success_count++;
        }
        
        $pdo->commit();
        jsonResponse(true, "成功儲存 $success_count 筆排班記錄");
        
    } catch (Exception $e) {
        $pdo->rollBack();
        jsonResponse(false, '儲存失敗: ' . $e->getMessage());
    }
}

/**
 * 清空排班表
 */
function clearSchedule() {
    global $pdo;
    
    $week_start = $_POST['week_start'] ?? date('Y-m-d', strtotime('monday this week'));
    
    try {
        $stmt = $pdo->prepare("
            DELETE FROM attendance_sessions
            WHERE session_date >= ? AND session_date < DATE_ADD(?, INTERVAL 7 DAY)
        ");
        $result = $stmt->execute([$week_start, $week_start]);
        
        jsonResponse($result, $result ? '排班表已清空' : '清空失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '操作失敗: ' . $e->getMessage());
    }
}

// ==========================================
// 6. 請假審核
// ==========================================

/**
 * 獲取請假申請列表
 */
function getLeaveRequests() {
    global $pdo;
    
    $status_filter = $_GET['status'] ?? 'all';
    $type_filter = $_GET['type'] ?? 'all';
    $search = $_GET['search'] ?? '';
    
    try {
        $sql = "
            SELECT 
                lr.request_id,
                lr.applicant_name,
                lr.applicant_number,
                lr.leave_type,
                lr.leave_date,
                lr.end_date,
                lr.start_time,
                lr.end_time,
                lr.reason,
                lr.status,
                lr.reviewed_by,
                lr.review_comment,
                lr.reviewed_at,
                lr.submitted_at,
                DATEDIFF(COALESCE(lr.end_date, lr.leave_date), lr.leave_date) + 1 as days
            FROM leave_requests lr
            WHERE 1=1
        ";
        $params = [];
        
        if ($status_filter !== 'all') {
            $sql .= " AND lr.status = ?";
            $params[] = $status_filter;
        }
        
        if ($type_filter !== 'all') {
            $sql .= " AND lr.leave_type = ?";
            $params[] = $type_filter;
        }
        
        if ($search) {
            $sql .= " AND lr.applicant_name LIKE ?";
            $params[] = "%$search%";
        }
        
        $sql .= " ORDER BY lr.submitted_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($requests, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 核准請假
 */
function approveLeave() {
    global $pdo;
    
    $request_id = $_POST['request_id'] ?? 0;
    $review_note = $_POST['review_note'] ?? '';
    
    try {
        $stmt = $pdo->prepare("
            UPDATE leave_requests 
            SET status = 'approved',
                reviewed_at = NOW(),
                reviewed_by = ?,
                review_comment = ?
            WHERE request_id = ?
        ");
        
        $result = $stmt->execute([
            $_SESSION['teacher_name'] ?? '系統',
            $review_note,
            $request_id
        ]);
        
        jsonResponse($result, $result ? '已核准請假' : '操作失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '操作失敗: ' . $e->getMessage());
    }
}

/**
 * 拒絕請假
 */
function rejectLeave() {
    global $pdo;
    
    $request_id = $_POST['request_id'] ?? 0;
    $review_note = $_POST['review_note'] ?? '';
    
    if (empty($review_note)) {
        jsonResponse(false, '請填寫拒絕原因');
    }
    
    try {
        $stmt = $pdo->prepare("
            UPDATE leave_requests 
            SET status = 'rejected',
                reviewed_at = NOW(),
                reviewed_by = ?,
                review_comment = ?
            WHERE request_id = ?
        ");
        
        $result = $stmt->execute([
            $_SESSION['teacher_name'] ?? '系統',
            $review_note,
            $request_id
        ]);
        
        jsonResponse($result, $result ? '已拒絕請假' : '操作失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '操作失敗: ' . $e->getMessage());
    }
}

/**
 * 獲取請假統計
 */
function getLeaveStats() {
    global $pdo;
    
    try {
        // 待審核
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM leave_requests WHERE status = 'pending'");
        $pending = $stmt->fetch()['count'] ?? 0;
        
        // 已核准
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM leave_requests WHERE status = 'approved'");
        $approved = $stmt->fetch()['count'] ?? 0;
        
        // 已拒絕
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM leave_requests WHERE status = 'rejected'");
        $rejected = $stmt->fetch()['count'] ?? 0;
        
        // 總請假天數
        $stmt = $pdo->query("
            SELECT SUM(
                DATEDIFF(
                    COALESCE(end_date, leave_date), 
                    leave_date
                ) + 1
            ) as total_days 
            FROM leave_requests 
            WHERE status = 'approved'
        ");
        $totalDays = $stmt->fetch()['total_days'] ?? 0;
        
        echo json_encode([
            'success' => true,
            'data' => [
                'pending' => (int)$pending,
                'approved' => (int)$approved,
                'rejected' => (int)$rejected,
                'totalDays' => (int)$totalDays
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => '獲取統計失敗: ' . $e->getMessage(),
            'data' => [
                'pending' => 0,
                'approved' => 0,
                'rejected' => 0,
                'totalDays' => 0
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// ==========================================
// 7. 點名系統
// ==========================================

/**
 * 獲取簽到場次列表
 */
function getAttendanceSessions() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                session_id,
                session_name,
                session_date,
                start_time,
                end_time,
                location,
                is_active,
                created_at
            FROM attendance_records
            ORDER BY session_date DESC, start_time DESC
            LIMIT 50
        ");
        
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($sessions, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 創建簽到場次
 */
function createAttendanceSession() {
    global $pdo;
    
    $session_name = $_POST['session_name'] ?? '';
    $session_date = $_POST['session_date'] ?? date('Y-m-d');
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $location = $_POST['location'] ?? '';
    
    if (empty($session_name)) {
        jsonResponse(false, '請輸入場次名稱');
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO attendance_sessions 
            (session_name, session_date, start_time, end_time, location, is_active)
            VALUES (?, ?, ?, ?, ?, 1)
        ");
        
        $result = $stmt->execute([
            $session_name,
            $session_date,
            $start_time,
            $end_time,
            $location
        ]);
        
        if ($result) {
            jsonResponse(true, '場次創建成功', [
                'session_id' => $pdo->lastInsertId()
            ]);
        } else {
            jsonResponse(false, '創建失敗');
        }
        
    } catch (Exception $e) {
        jsonResponse(false, '創建失敗: ' . $e->getMessage());
    }
}

/**
 * 獲取場次的成員列表（用於點名）
 */
function getSessionMembers() {
    global $pdo;
    
    $session_id = $_GET['session_id'] ?? 0;
    
    try {
        // 獲取所有科學會成員
        $stmt = $pdo->query("
            SELECT 
                member_id,
                members_number,
                members_name,
                class,
                position,
                is_leader
            FROM science_club_members
            WHERE status = 'active'
            ORDER BY members_name
        ");
        
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 獲取已簽到記錄
        $stmt = $pdo->prepare("
            SELECT 
                record_id,
                member_name,
                check_in_time,
                status
            FROM attendance_records
            WHERE session_id = ?
        ");
        $stmt->execute([$session_id]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 合併資料
        foreach ($members as &$member) {
            $record = array_filter($records, function($r) use ($member) {
                return $r['member_name'] === $member['members_name'];
            });
            
            if (!empty($record)) {
                $record = reset($record);
                $member['checked'] = true;
                $member['check_in_time'] = $record['check_in_time'];
                $member['status'] = $record['status'];
            } else {
                $member['checked'] = false;
            }
        }
        
        echo json_encode($members, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 記錄簽到
 */
function recordAttendance() {
    global $pdo;
    
    $session_id = $_POST['session_id'] ?? 0;
    $member_name = $_POST['member_name'] ?? '';
    $status = $_POST['status'] ?? 'present'; // present, late, absent, leave
    
    try {
        // 檢查是否已簽到
        $stmt = $pdo->prepare("
            SELECT record_id FROM attendance_records 
            WHERE session_id = ? AND member_name = ?
        ");
        $stmt->execute([$session_id, $member_name]);
        
        if ($stmt->fetch()) {
            // 更新狀態
            $stmt = $pdo->prepare("
                UPDATE attendance_records 
                SET status = ?, check_in_time = NOW()
                WHERE session_id = ? AND member_name = ?
            ");
            $result = $stmt->execute([$status, $session_id, $member_name]);
        } else {
            // 新增記錄
            $stmt = $pdo->prepare("
                INSERT INTO attendance_records 
                (session_id, member_name, check_in_time, status)
                VALUES (?, ?, NOW(), ?)
            ");
            $result = $stmt->execute([$session_id, $member_name, $status]);
        }
        
        jsonResponse($result, $result ? '記錄成功' : '記錄失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '記錄失敗: ' . $e->getMessage());
    }
}

/**
 * 獲取出席統計
 */
function getAttendanceStatistics() {
    global $pdo;
    
    try {
        // 總簽到場次
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM attendance_sessions");
        $totalSessions = $stmt->fetch()['count'];
        
        // 總簽到人次
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM attendance_records WHERE status = 'present'");
        $totalPresent = $stmt->fetch()['count'];
        
        // 遲到人次
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM attendance_records WHERE status = 'late'");
        $totalLate = $stmt->fetch()['count'];
        
        // 缺席人次
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM attendance_records WHERE status = 'absent'");
        $totalAbsent = $stmt->fetch()['count'];
        
        jsonResponse(true, '獲取成功', [
            'totalSessions' => $totalSessions,
            'totalPresent' => $totalPresent,
            'totalLate' => $totalLate,
            'totalAbsent' => $totalAbsent
        ]);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

// ==========================================
// 8. 工作時數管理
// ==========================================

/**
 * 獲取工作時數記錄
 */
function getWorkHours() {
    global $pdo;
    
    $staff_filter = $_GET['staff'] ?? 'all';
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-t');
    
    try {
        $sql = "
            SELECT 
                ar.record_id,
                ar.member_name,
                ar.check_in_time,
                ar.check_out_time,
                ar.status,
                ar.notes,
                asn.session_name,
                asn.session_date,
                TIMESTAMPDIFF(HOUR, ar.check_in_time, ar.check_out_time) as work_hours
            FROM attendance_records ar
            JOIN attendance_sessions asn ON ar.session_id = asn.session_id
            WHERE asn.session_date BETWEEN ? AND ?
        ";
        $params = [$start_date, $end_date];
        
        if ($staff_filter !== 'all') {
            $sql .= " AND ar.member_name = ?";
            $params[] = $staff_filter;
        }
        
        $sql .= " ORDER BY asn.session_date DESC, ar.check_in_time DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($records, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 新增工作記錄
 */
function addWorkRecord() {
    global $pdo;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $date = $input['date'] ?? '';
    $member_name = $input['name'] ?? '';
    $shift = $input['shift'] ?? '';
    $check_in = $input['checkIn'] ?? '';
    $check_out = $input['checkOut'] ?? '';
    $status = $input['status'] ?? 'present';
    $note = $input['note'] ?? '';
    
    if (empty($date) || empty($member_name)) {
        jsonResponse(false, '請填寫完整資料');
    }
    
    try {
        $pdo->beginTransaction();
        
        // 先創建或獲取場次
        $stmt = $pdo->prepare("
            SELECT session_id FROM attendance_sessions 
            WHERE session_date = ? AND session_name = ?
        ");
        $stmt->execute([$date, $shift]);
        $session = $stmt->fetch();
        
        if (!$session) {
            // 創建新場次
            $stmt = $pdo->prepare("
                INSERT INTO attendance_sessions 
                (session_name, session_date, is_active)
                VALUES (?, ?, 1)
            ");
            $stmt->execute([$shift, $date]);
            $session_id = $pdo->lastInsertId();
        } else {
            $session_id = $session['session_id'];
        }
        
        // 新增記錄
        $stmt = $pdo->prepare("
            INSERT INTO attendance_records 
            (session_id, member_name, check_in_time, check_out_time, status, notes)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $check_in_datetime = $check_in ? "$date $check_in:00" : null;
        $check_out_datetime = $check_out ? "$date $check_out:00" : null;
        
        $result = $stmt->execute([
            $session_id,
            $member_name,
            $check_in_datetime,
            $check_out_datetime,
            $status,
            $note
        ]);
        
        $pdo->commit();
        jsonResponse($result, $result ? '新增成功' : '新增失敗');
        
    } catch (Exception $e) {
        $pdo->rollBack();
        jsonResponse(false, '新增失敗: ' . $e->getMessage());
    }
}

/**
 * 更新工作記錄
 */
function updateWorkRecord() {
    global $pdo;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $record_id = $input['id'] ?? 0;
    $check_in = $input['checkIn'] ?? '';
    $check_out = $input['checkOut'] ?? '';
    $status = $input['status'] ?? 'present';
    $note = $input['note'] ?? '';
    
    try {
        $stmt = $pdo->prepare("
            UPDATE attendance_records 
            SET check_in_time = ?,
                check_out_time = ?,
                status = ?,
                notes = ?
            WHERE record_id = ?
        ");
        
        $result = $stmt->execute([
            $check_in,
            $check_out,
            $status,
            $note,
            $record_id
        ]);
        
        jsonResponse($result, $result ? '更新成功' : '更新失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '更新失敗: ' . $e->getMessage());
    }
}

/**
 * 刪除工作記錄
 */
function deleteWorkRecord() {
    global $pdo;
    
    $record_id = $_POST['id'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("DELETE FROM attendance_records WHERE record_id = ?");
        $result = $stmt->execute([$record_id]);
        
        jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '刪除失敗: ' . $e->getMessage());
    }
}

/**
 * 獲取工時統計
 */
function getWorkStats() {
    global $pdo;
    
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-t');
    
    try {
        // 總工作時數
        $stmt = $pdo->prepare("
            SELECT 
                SUM(TIMESTAMPDIFF(HOUR, ar.check_in_time, ar.check_out_time)) as total_hours
            FROM attendance_records ar
            JOIN attendance_sessions asn ON ar.session_id = asn.session_id
            WHERE asn.session_date BETWEEN ? AND ?
            AND ar.check_in_time IS NOT NULL 
            AND ar.check_out_time IS NOT NULL
        ");
        $stmt->execute([$start_date, $end_date]);
        $totalHours = $stmt->fetch()['total_hours'] ?? 0;
        
        // 平均出席率
        $stmt = $pdo->prepare("
            SELECT 
                COUNT(CASE WHEN status = 'present' THEN 1 END) * 100.0 / COUNT(*) as avg_attendance
            FROM attendance_records ar
            JOIN attendance_sessions asn ON ar.session_id = asn.session_id
            WHERE asn.session_date BETWEEN ? AND ?
        ");
        $stmt->execute([$start_date, $end_date]);
        $avgAttendance = round($stmt->fetch()['avg_attendance'] ?? 0, 1);
        
        // 遲到次數
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM attendance_records ar
            JOIN attendance_sessions asn ON ar.session_id = asn.session_id
            WHERE asn.session_date BETWEEN ? AND ?
            AND ar.status = 'late'
        ");
        $stmt->execute([$start_date, $end_date]);
        $lateCount = $stmt->fetch()['count'];
        
        // 缺席次數
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM attendance_records ar
            JOIN attendance_sessions asn ON ar.session_id = asn.session_id
            WHERE asn.session_date BETWEEN ? AND ?
            AND ar.status = 'absent'
        ");
        $stmt->execute([$start_date, $end_date]);
        $absentCount = $stmt->fetch()['count'];
        
        jsonResponse(true, '獲取成功', [
            'totalHours' => $totalHours,
            'avgAttendance' => $avgAttendance . '%',
            'lateCount' => $lateCount,
            'absentCount' => $absentCount
        ]);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

function getUserInfo() {
    global $pdo;
    
    $userId = $_SESSION['user_id'] ?? 0;
    if (!$userId) {
        jsonResponse(false, '未登入');
    }
    
    // ✅ 注意：表名是 user 不是 users
    $stmt = $pdo->prepare("
        SELECT id, account, name, email, role 
        FROM user 
        WHERE id = ?
    ");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        jsonResponse(false, '找不到使用者');
    }
    
    jsonResponse(true, '獲取成功', $user);
}

/**
 * 獲取教師首頁統計數據
 */
function getDashboardStats() {
    global $pdo;
    
    try {
        // 進行中的活動數
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM activities WHERE status = 'published'");
        $activeActivities = $stmt->fetch()['count'];
        
        // 總報名人數
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM registrations WHERE status = 'registered'");
        $totalStudents = $stmt->fetch()['count'];
        
        // 平均評分
        $stmt = $pdo->query("SELECT AVG(satisfaction_score) as avg_rating FROM feedback_responses");
        $avgRating = round($stmt->fetch()['avg_rating'] ?? 0, 1);
        
        // 待審核表單
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM feedback_responses WHERE is_read = 0");
        $pendingReviews = $stmt->fetch()['count'];
        
        // 今日課程安排（從行事曆取得）
        $today = date('Y-m-d');
        $stmt = $pdo->prepare("
            SELECT 
                title,
                start_time,
                end_time,
                location
            FROM calendar_events 
            WHERE event_date = ? AND is_public = 1
            ORDER BY start_time
            LIMIT 4
        ");
        $stmt->execute([$today]);
        $todaySchedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(true, '獲取成功', [
            'activeActivities' => $activeActivities,
            'totalStudents' => $totalStudents,
            'avgRating' => $avgRating,
            'pendingReviews' => $pendingReviews,
            'todaySchedule' => $todaySchedule
        ]);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

// ==========================================
// 點名系統
// ==========================================

function getStudentList() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                student_id,
                student_name,
                class,
                phone,
                email
            FROM student 
            ORDER BY class, student_name
        ");
        
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(true, '獲取成功', $students);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

function createRollcallSession() {
    global $pdo;
    
    $session_name = $_POST['session_name'] ?? '';
    $session_date = $_POST['session_date'] ?? date('Y-m-d');
    $session_time = $_POST['session_time'] ?? date('H:i');
    $teacher_id = $_SESSION['user_id'] ?? 0;
    
    if (!$session_name) {
        jsonResponse(false, '請輸入場次名稱');
    }
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("
            INSERT INTO rollcall_sessions (session_name, session_date, session_time, created_by, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$session_name, $session_date, $session_time, $teacher_id]);
        $session_id = $pdo->lastInsertId();
        
        $stmt = $pdo->query("SELECT id FROM student");
        $students = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $stmt = $pdo->prepare("
            INSERT INTO rollcall_records (session_id, student_id, is_present, check_time)
            VALUES (?, ?, 0, NULL)
        ");
        
        foreach ($students as $student_id) {
            $stmt->execute([$session_id, $student_id]);
        }
        
        $pdo->commit();
        jsonResponse(true, '場次創建成功', ['session_id' => $session_id]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        jsonResponse(false, '創建失敗: ' . $e->getMessage());
    }
}

function saveRollcall() {
    global $pdo;
    
    $session_id = $_POST['session_id'] ?? 0;
    $student_id = $_POST['student_id'] ?? 0;
    $is_present = $_POST['is_present'] ?? 0;
    
    if (!$session_id || !$student_id) {
        jsonResponse(false, '參數錯誤');
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT record_id FROM rollcall_records 
            WHERE session_id = ? AND student_id = ?
        ");
        $stmt->execute([$session_id, $student_id]);
        $exists = $stmt->fetch();
        
        $check_time = $is_present ? date('Y-m-d H:i:s') : null;
        
        if ($exists) {
            $stmt = $pdo->prepare("
                UPDATE rollcall_records 
                SET is_present = ?, check_time = ?
                WHERE session_id = ? AND student_id = ?
            ");
            $stmt->execute([$is_present, $check_time, $session_id, $student_id]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO rollcall_records (session_id, student_id, is_present, check_time)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$session_id, $student_id, $is_present, $check_time]);
        }
        
        jsonResponse(true, '保存成功');
        
    } catch (Exception $e) {
        jsonResponse(false, '保存失敗: ' . $e->getMessage());
    }
}

function exportRollcall() {
    global $pdo;
    
    $session_id = $_GET['session_id'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                rs.session_name,
                rs.session_date,
                s.student_name,
                s.student_idnumber,
                s.class,
                CASE WHEN rr.is_present = 1 THEN '已出席' ELSE '未出席' END as status,
                rr.check_time
            FROM rollcall_records rr
            JOIN student s ON rr.student_id = s.id
            JOIN rollcall_sessions rs ON rr.session_id = rs.session_id
            WHERE rr.session_id = ?
            ORDER BY s.class, s.student_name
        ");
        $stmt->execute([$session_id]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(true, '獲取成功', $records);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

// ==========================================
// 排班系統
// ==========================================

function getScheduleWeek() {
    global $pdo;
    
    $week_start = $_GET['week_start'] ?? date('Y-m-d');
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                schedule_id,
                week_start_date,
                day_of_week,
                time_slot,
                staff_name,
                notes
            FROM schedules
            WHERE week_start_date = ?
            ORDER BY 
                FIELD(day_of_week, '星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期日'),
                time_slot
        ");
        $stmt->execute([$week_start]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(true, '獲取成功', $schedules);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

function saveScheduleCell() {
    global $pdo;
    
    $week_start = $_POST['week_start'] ?? '';
    $day_of_week = $_POST['day_of_week'] ?? '';
    $time_slot = $_POST['time_slot'] ?? '';
    $staff_name = $_POST['staff_name'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    if (!$week_start || !$day_of_week || !$time_slot) {
        jsonResponse(false, '參數錯誤');
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT schedule_id FROM schedules 
            WHERE week_start_date = ? AND day_of_week = ? AND time_slot = ?
        ");
        $stmt->execute([$week_start, $day_of_week, $time_slot]);
        $exists = $stmt->fetch();
        
        if ($exists) {
            $stmt = $pdo->prepare("
                UPDATE schedules 
                SET staff_name = ?, notes = ?, updated_at = NOW()
                WHERE week_start_date = ? AND day_of_week = ? AND time_slot = ?
            ");
            $stmt->execute([$staff_name, $notes, $week_start, $day_of_week, $time_slot]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO schedules (week_start_date, day_of_week, time_slot, staff_name, notes, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$week_start, $day_of_week, $time_slot, $staff_name, $notes]);
        }
        
        jsonResponse(true, '保存成功');
        
    } catch (Exception $e) {
        jsonResponse(false, '保存失敗: ' . $e->getMessage());
    }
}

function clearScheduleWeek() {
    global $pdo;
    
    $week_start = $_POST['week_start'] ?? '';
    
    if (!$week_start) {
        jsonResponse(false, '參數錯誤');
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM schedules WHERE week_start_date = ?");
        $stmt->execute([$week_start]);
        
        jsonResponse(true, '清空成功');
        
    } catch (Exception $e) {
        jsonResponse(false, '清空失敗: ' . $e->getMessage());
    }
}

function exportSchedule() {
    global $pdo;
    
    $week_start = $_GET['week_start'] ?? date('Y-m-d');
    
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM schedules 
            WHERE week_start_date = ?
            ORDER BY 
                FIELD(day_of_week, '星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期日'),
                time_slot
        ");
        $stmt->execute([$week_start]);
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(true, '獲取成功', $schedules);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

// ==========================================
// 工作時數
// ==========================================

function getWorkHoursList() {
    global $pdo;
    
    $staff_name = $_GET['staff_name'] ?? 'all';
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-t');
    $page = $_GET['page'] ?? 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    
    try {
        $where = "work_date BETWEEN ? AND ?";
        $params = [$start_date, $end_date];
        
        if ($staff_name !== 'all') {
            $where .= " AND staff_name = ?";
            $params[] = $staff_name;
        }
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM work_hours WHERE $where");
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        $stmt = $pdo->prepare("
            SELECT 
                work_id,
                work_date,
                staff_name,
                shift_time,
                check_in_time,
                check_out_time,
                work_hours,
                status,
                notes
            FROM work_hours 
            WHERE $where
            ORDER BY work_date DESC, check_in_time DESC
            LIMIT $limit OFFSET $offset
        ");
        $stmt->execute($params);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(true, '獲取成功', [
            'records' => $records,
            'total' => $total,
            'page' => $page,
            'total_pages' => ceil($total / $limit)
        ]);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

function addWorkHourRecord() {
    global $pdo;
    
    $work_date = $_POST['work_date'] ?? '';
    $staff_name = $_POST['staff_name'] ?? '';
    $shift_time = $_POST['shift_time'] ?? '';
    $check_in = $_POST['check_in_time'] ?? '';
    $check_out = $_POST['check_out_time'] ?? '';
    $hours = $_POST['work_hours'] ?? 0;
    $status = $_POST['status'] ?? 'present';
    $notes = $_POST['notes'] ?? '';
    
    if (!$work_date || !$staff_name || !$shift_time) {
        jsonResponse(false, '必填欄位不完整');
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO work_hours 
            (work_date, staff_name, shift_time, check_in_time, check_out_time, work_hours, status, notes, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $result = $stmt->execute([
            $work_date, $staff_name, $shift_time, $check_in, $check_out, $hours, $status, $notes
        ]);
        
        jsonResponse($result, $result ? '新增成功' : '新增失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '新增失敗: ' . $e->getMessage());
    }
}

function updateWorkHourRecord() {
    global $pdo;
    
    $work_id = $_POST['work_id'] ?? 0;
    $work_date = $_POST['work_date'] ?? '';
    $staff_name = $_POST['staff_name'] ?? '';
    $shift_time = $_POST['shift_time'] ?? '';
    $check_in = $_POST['check_in_time'] ?? '';
    $check_out = $_POST['check_out_time'] ?? '';
    $hours = $_POST['work_hours'] ?? 0;
    $status = $_POST['status'] ?? 'present';
    $notes = $_POST['notes'] ?? '';
    
    if (!$work_id) {
        jsonResponse(false, '記錄ID不能為空');
    }
    
    try {
        $stmt = $pdo->prepare("
            UPDATE work_hours SET
                work_date = ?,
                staff_name = ?,
                shift_time = ?,
                check_in_time = ?,
                check_out_time = ?,
                work_hours = ?,
                status = ?,
                notes = ?,
                updated_at = NOW()
            WHERE work_id = ?
        ");
        
        $result = $stmt->execute([
            $work_date, $staff_name, $shift_time, $check_in, $check_out, $hours, $status, $notes, $work_id
        ]);
        
        jsonResponse($result, $result ? '更新成功' : '更新失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '更新失敗: ' . $e->getMessage());
    }
}

function deleteWorkHourRecord() {
    global $pdo;
    
    $work_id = $_POST['work_id'] ?? 0;
    
    if (!$work_id) {
        jsonResponse(false, '記錄ID不能為空');
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM work_hours WHERE work_id = ?");
        $result = $stmt->execute([$work_id]);
        
        jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '刪除失敗: ' . $e->getMessage());
    }
}

function getWorkHourStats() {
    global $pdo;
    
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-t');
    
    try {
        $stmt = $pdo->prepare("
            SELECT SUM(work_hours) as total_hours
            FROM work_hours
            WHERE work_date BETWEEN ? AND ?
        ");
        $stmt->execute([$start_date, $end_date]);
        $total_hours = $stmt->fetch()['total_hours'] ?? 0;
        
        $stmt = $pdo->prepare("
            SELECT 
                COUNT(CASE WHEN status IN ('present', 'late') THEN 1 END) * 100.0 / COUNT(*) as attendance_rate
            FROM work_hours
            WHERE work_date BETWEEN ? AND ?
        ");
        $stmt->execute([$start_date, $end_date]);
        $attendance_rate = round($stmt->fetch()['attendance_rate'] ?? 0, 1);
        
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as late_count
            FROM work_hours
            WHERE work_date BETWEEN ? AND ? AND status = 'late'
        ");
        $stmt->execute([$start_date, $end_date]);
        $late_count = $stmt->fetch()['late_count'];
        
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as absent_count
            FROM work_hours
            WHERE work_date BETWEEN ? AND ? AND status = 'absent'
        ");
        $stmt->execute([$start_date, $end_date]);
        $absent_count = $stmt->fetch()['absent_count'];
        
        jsonResponse(true, '獲取成功', [
            'total_hours' => round($total_hours, 1),
            'attendance_rate' => $attendance_rate,
            'late_count' => $late_count,
            'absent_count' => $absent_count
        ]);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

function exportWorkHours() {
    global $pdo;
    
    $staff_name = $_GET['staff_name'] ?? 'all';
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-t');
    
    try {
        $where = "work_date BETWEEN ? AND ?";
        $params = [$start_date, $end_date];
        
        if ($staff_name !== 'all') {
            $where .= " AND staff_name = ?";
            $params[] = $staff_name;
        }
        
        $stmt = $pdo->prepare("
            SELECT 
                work_date,
                staff_name,
                shift_time,
                check_in_time,
                check_out_time,
                work_hours,
                status,
                notes
            FROM work_hours 
            WHERE $where
            ORDER BY work_date DESC, check_in_time DESC
        ");
        $stmt->execute($params);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(true, '獲取成功', $records);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

// ==========================================
// 通知系統
// ==========================================

/**
 * 獲取教師的通知列表
 */
function getNotifications() {
    global $pdo;
    
    $teacher_name = $_SESSION['teacher_name'] ?? 'teacher';
    $limit = $_GET['limit'] ?? 20;
    
    try {
        // 獲取針對教師的通知
        $stmt = $pdo->prepare("
            SELECT 
                notification_id,
                title,
                message,
                notification_type,
                is_read,
                created_at,
                related_id
            FROM notifications
            WHERE (recipient_type = 'teacher' OR recipient_type = 'all')
            AND is_deleted = 0
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 處理通知數據，添加更多信息
        foreach ($notifications as &$notification) {
            // 格式化時間
            $notification['time_ago'] = formatTimeAgo($notification['created_at']);
            
            // 根據類型添加圖標和鏈接
            switch ($notification['notification_type']) {
                case 'leave_request':
                    $notification['icon'] = 'clipboard-check';
                    $notification['link'] = 'teacher_ask_for_leave.html';
                    $notification['type_label'] = '請假申請';
                    break;
                case 'registration':
                    $notification['icon'] = 'user-plus';
                    $notification['link'] = 'teacher_registration_statistics.html';
                    $notification['type_label'] = '活動報名';
                    break;
                case 'feedback':
                    $notification['icon'] = 'comment-dots';
                    $notification['link'] = 'teacher_form_feedback.html';
                    $notification['type_label'] = '學生反饋';
                    break;
                case 'attendance':
                    $notification['icon'] = 'user-check';
                    $notification['link'] = 'teacher_rollcall.html';
                    $notification['type_label'] = '出勤提醒';
                    break;
                default:
                    $notification['icon'] = 'bell';
                    $notification['link'] = null;
                    $notification['type_label'] = '系統通知';
            }
        }
        
        echo json_encode([
            'success' => true,
            'data' => $notifications
        ], JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取通知失敗: ' . $e->getMessage());
    }
}

/**
 * 標記單個通知為已讀
 */
function markNotificationRead() {
    global $pdo;
    
    $notification_id = $_POST['notification_id'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("
            UPDATE notifications 
            SET is_read = 1, read_at = NOW()
            WHERE notification_id = ?
        ");
        $result = $stmt->execute([$notification_id]);
        
        jsonResponse($result, $result ? '已標記為已讀' : '操作失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '操作失敗: ' . $e->getMessage());
    }
}

/**
 * 標記所有通知為已讀
 */
function markAllNotificationsRead() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            UPDATE notifications 
            SET is_read = 1, read_at = NOW()
            WHERE (recipient_type = 'teacher' OR recipient_type = 'all')
            AND is_read = 0
            AND is_deleted = 0
        ");
        $result = $stmt->execute();
        
        jsonResponse($result, $result ? '全部已標記為已讀' : '操作失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '操作失敗: ' . $e->getMessage());
    }
}

/**
 * 刪除通知（軟刪除）
 */
function deleteNotification() {
    global $pdo;
    
    $notification_id = $_POST['notification_id'] ?? 0;
    
    try {
        // 使用軟刪除，不真正從資料庫移除
        $stmt = $pdo->prepare("
            UPDATE notifications 
            SET is_deleted = 1, deleted_at = NOW()
            WHERE notification_id = ?
        ");
        $result = $stmt->execute([$notification_id]);
        
        jsonResponse($result, $result ? '通知已刪除' : '刪除失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '刪除失敗: ' . $e->getMessage());
    }
}

/**
 * 格式化時間為相對時間
 */
function formatTimeAgo($datetime) {
    if (!$datetime) return '剛剛';
    
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return '剛剛';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . '分鐘前';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . '小時前';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . '天前';
    } else {
        return date('Y-m-d', $timestamp);
    }
}

// ==========================================
// 輔助函數
// ==========================================

function jsonResponse($success, $message, $data = null) {
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
?>