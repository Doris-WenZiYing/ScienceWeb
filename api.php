<?php
/**
 * 科學會管理系統 - 統一 API 接口
 * 處理所有前端請求
 */

include("pdo.php");

// 取得 action 參數
$action = $_GET['action'] ?? '';

// 根據 action 執行對應功能
switch ($action) {
    
    // ==========================================
    // 登入/登出系統
    // ==========================================
    
    case 'login':
        handleLogin();
        break;
        
    case 'logout':
        handleLogout();
        break;
        
    case 'check_session':
        checkSession();
        break;
    
    // ==========================================
    // 功能1：行事曆系統
    // ==========================================
    
    case 'get_calendar_events':
        getCalendarEvents();
        break;
        
    case 'add_calendar_event':
        addCalendarEvent();
        break;
        
    case 'update_calendar_event':
        updateCalendarEvent();
        break;
        
    case 'delete_calendar_event':
        deleteCalendarEvent();
        break;
    
    // ==========================================
    // 功能2：活動相簿
    // ==========================================
    
    case 'get_albums':
        getAlbums();
        break;
        
    case 'add_album':
        addAlbum();
        break;
        
    case 'get_album_media':
        getAlbumMedia();
        break;
        
    case 'upload_media':
        uploadMedia();
        break;
    
    // ==========================================
    // 功能3：公告系統
    // ==========================================
    
    case 'get_announcements':
        getAnnouncements();
        break;
        
    case 'add_announcement':
        addAnnouncement();
        break;
        
    case 'get_announcement_detail':
        getAnnouncementDetail();
        break;
    
    // ==========================================
    // 功能4：活動發佈
    // ==========================================
    
    case 'get_activities':
        getActivities();
        break;
        
    case 'add_activity':
        addActivity();
        break;
        
    case 'update_activity':
        updateActivity();
        break;
    
    // ==========================================
    // 功能6：報名管理
    // ==========================================
    
    case 'register_activity':
        registerActivity();
        break;
        
    case 'cancel_registration':
        cancelRegistration();
        break;
        
    case 'get_registrations':
        getRegistrations();
        break;
        
    case 'export_registrations':
        exportRegistrations();
        break;
    
    // ==========================================
    // 功能5：反饋表單
    // ==========================================
    
    case 'get_feedback_forms':
        getFeedbackForms();
        break;
        
    case 'submit_feedback':
        submitFeedback();
        break;
    
    // ==========================================
    // 功能7：比賽成績
    // ==========================================
    
    case 'add_competition_result':
        addCompetitionResult();
        break;
        
    case 'get_competition_results':
        getCompetitionResults();
        break;
    
    // ==========================================
    // 功能9：簽到系統
    // ==========================================
    
    case 'get_attendance_sessions':
        getAttendanceSessions();
        break;
        
    case 'check_in':
        checkIn();
        break;
    
    // ==========================================
    // 功能10：請假系統
    // ==========================================
    
    case 'submit_leave_request':
        submitLeaveRequest();
        break;
        
    case 'get_leave_requests':
        getLeaveRequests();
        break;
        
    case 'review_leave_request':
        reviewLeaveRequest();
        break;
    
    // ==========================================
    // 功能11：開會紀錄
    // ==========================================
    
    case 'get_meeting_records':
        getMeetingRecords();
        break;
        
    case 'add_meeting_record':
        addMeetingRecord();
        break;
    
    // ==========================================
    // 功能12：通知系統
    // ==========================================
    
    case 'get_notifications':
        getNotifications();
        break;
        
    case 'mark_notification_read':
        markNotificationRead();
        break;
    
    default:
        jsonResponse(false, '未知的 API 操作');
}

// ==========================================
// 登入/登出功能實作
// ==========================================

function handleLogin() {
    global $pdo;
    
    $account = trim($_POST['account'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');
    
    // 防呆：欄位不能空白
    if (empty($account) || empty($password) || empty($role)) {
        ?>
        <script>
            alert("請輸入完整帳號、密碼與身分");
            history.back();
        </script>
        <?php
        exit;
    }
    
    // 查詢使用者
    $stmt = $pdo->prepare("
        SELECT * FROM `user` 
        WHERE `account` = ? AND `password` = ? AND `role` = ? AND `is_active` = 1
    ");
    $stmt->execute([$account, $password, $role]);
    $user = $stmt->fetch();
    
    if ($user) {
        // 登入成功，設定 Session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['account'] = $user['account'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_time'] = time();
        
        // 根據角色跳轉
        $redirect_map = [
            'admin' => 'science_club_event_album.html',
            'science_club' => 'activity_calendar.html',
            'teacher' => 'teacher_dashboard.html',
            'student' => 'student_dashboard.html'
        ];
        
        $redirect = $redirect_map[$role] ?? 'index.html';
        
        ?>
        <script>
            alert("✅ 登入成功！歡迎使用科學會管理系統");
            location.href = "<?php echo $redirect; ?>";
        </script>
        <?php
        
    } else {
        // 登入失敗
        ?>
        <script>
            alert("❌ 帳號、密碼或身分錯誤，請重新輸入");
            history.back();
        </script>
        <?php
    }
}

function handleLogout() {
    session_destroy();
    ?>
    <script>
        alert("已成功登出");
        location.href = "index.html";
    </script>
    <?php
}

function checkSession() {
    if (isLoggedIn()) {
        jsonResponse(true, '已登入', [
            'account' => $_SESSION['account'],
            'role' => $_SESSION['role']
        ]);
    } else {
        jsonResponse(false, '未登入');
    }
}

// ==========================================
// 功能1：行事曆系統
// ==========================================

function getCalendarEvents() {
    global $pdo;
    
    // 取得角色
    $role = $_SESSION['role'] ?? 'student';
    
    // 學生只能看公開的行事曆
    if ($role === 'student') {
        $stmt = $pdo->query("
            SELECT * FROM `calendar_events` 
            WHERE `is_public` = 1 
            ORDER BY `event_date` ASC
        ");
    } else {
        // 科學會、老師、管理員可看全部
        $stmt = $pdo->query("
            SELECT * FROM `calendar_events` 
            ORDER BY `event_date` ASC
        ");
    }
    
    $events = $stmt->fetchAll();
    jsonResponse(true, '取得成功', $events);
}

function addCalendarEvent() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $event_type = $_POST['event_type'] ?? 'activity';
    $color = $_POST['color'] ?? 'red';
    $is_public = $_POST['is_public'] ?? 0;
    $location = $_POST['location'] ?? '';
    
    $stmt = $pdo->prepare("
        INSERT INTO `calendar_events` 
        (`title`, `description`, `event_date`, `start_time`, `end_time`, 
         `event_type`, `color`, `is_public`, `location`, `created_by_account`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([
        $title, $description, $event_date, $start_time, $end_time,
        $event_type, $color, $is_public, $location, $_SESSION['account']
    ]);
    
    if ($result) {
        jsonResponse(true, '新增成功');
    } else {
        jsonResponse(false, '新增失敗');
    }
}

function updateCalendarEvent() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $event_id = $_POST['event_id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $event_type = $_POST['event_type'] ?? 'activity';
    $color = $_POST['color'] ?? 'red';
    $is_public = $_POST['is_public'] ?? 0;
    $location = $_POST['location'] ?? '';
    
    $stmt = $pdo->prepare("
        UPDATE `calendar_events` SET
        `title` = ?, `description` = ?, `event_date` = ?, 
        `start_time` = ?, `end_time` = ?, `event_type` = ?,
        `color` = ?, `is_public` = ?, `location` = ?
        WHERE `event_id` = ?
    ");
    
    $result = $stmt->execute([
        $title, $description, $event_date, $start_time, $end_time,
        $event_type, $color, $is_public, $location, $event_id
    ]);
    
    jsonResponse($result, $result ? '更新成功' : '更新失敗');
}

function deleteCalendarEvent() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $event_id = $_POST['event_id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM `calendar_events` WHERE `event_id` = ?");
    $result = $stmt->execute([$event_id]);
    
    jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
}

// ==========================================
// 其他功能待實作...
// ==========================================

function getAlbums() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM `photo_albums` WHERE `is_public` = 1 ORDER BY `created_at` DESC");
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function addAlbum() {
    requireRole(['admin', 'science_club']);
    jsonResponse(false, '功能開發中');
}

function getAlbumMedia() {
    jsonResponse(false, '功能開發中');
}

function uploadMedia() {
    jsonResponse(false, '功能開發中');
}

function getAnnouncements() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM `announcements` ORDER BY `publish_date` DESC LIMIT 20");
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function addAnnouncement() {
    requireRole(['admin', 'science_club']);
    jsonResponse(false, '功能開發中');
}

function getAnnouncementDetail() {
    jsonResponse(false, '功能開發中');
}

function getActivities() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM `activities` WHERE `status` = 'published' ORDER BY `start_date` DESC");
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function addActivity() {
    requireRole(['admin', 'science_club']);
    jsonResponse(false, '功能開發中');
}

function updateActivity() {
    requireRole(['admin', 'science_club']);
    jsonResponse(false, '功能開發中');
}

function registerActivity() {
    requireRole('student');
    jsonResponse(false, '功能開發中');
}

function cancelRegistration() {
    jsonResponse(false, '功能開發中');
}

function getRegistrations() {
    requireRole(['admin', 'science_club']);
    jsonResponse(false, '功能開發中');
}

function exportRegistrations() {
    requireRole(['admin', 'science_club']);
    jsonResponse(false, '功能開發中');
}

function getFeedbackForms() {
    jsonResponse(false, '功能開發中');
}

function submitFeedback() {
    jsonResponse(false, '功能開發中');
}

function addCompetitionResult() {
    jsonResponse(false, '功能開發中');
}

function getCompetitionResults() {
    jsonResponse(false, '功能開發中');
}

function getAttendanceSessions() {
    jsonResponse(false, '功能開發中');
}

function checkIn() {
    jsonResponse(false, '功能開發中');
}

function submitLeaveRequest() {
    jsonResponse(false, '功能開發中');
}

function getLeaveRequests() {
    jsonResponse(false, '功能開發中');
}

function reviewLeaveRequest() {
    jsonResponse(false, '功能開發中');
}

function getMeetingRecords() {
    jsonResponse(false, '功能開發中');
}

function addMeetingRecord() {
    jsonResponse(false, '功能開發中');
}

function getNotifications() {
    jsonResponse(false, '功能開發中');
}

function markNotificationRead() {
    jsonResponse(false, '功能開發中');
}
?>