<?php
/**
 * 科學會管理系統 - 統一 API
 * 所有12個功能完整實作
 */

include("pdo.php");

// ==========================================
// 調試功能（測試完成後可刪除）
// ==========================================
if (isset($_GET['debug']) && $_GET['debug'] == '1') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'session_status' => session_status() === PHP_SESSION_ACTIVE ? 'active' : 'inactive',
        'session_id' => session_id(),
        'session_data' => $_SESSION,
        'is_logged_in' => isset($_SESSION['user_id']),
        'user_id' => $_SESSION['user_id'] ?? null,
        'account' => $_SESSION['account'] ?? null,
        'role' => $_SESSION['role'] ?? null,
        'get_params' => $_GET,
        'post_params' => array_keys($_POST)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 取得 action 參數
$action = $_GET['action'] ?? '';

// 根據 action 執行對應功能
switch ($action) {

    // admin
    case 'get_users':
    getUsers();
    break;
    
    case 'add_user':
        addUser();
        break;
        
    case 'update_user':
        updateUser();
        break;
        
    case 'delete_user':
        deleteUser();
        break;

    case 'get_user_stats':
        getUserStats();
        break;
    
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
        
    case 'delete_album':
        deleteAlbum();
        break;
        
    case 'delete_media':
        deleteMedia();
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
        
    case 'update_announcement':
        updateAnnouncement();
        break;
        
    case 'delete_announcement':
        deleteAnnouncement();
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
        
    case 'delete_activity':
        deleteActivity();
        break;
        
    case 'get_activity_detail':
        getActivityDetail();
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
        
    case 'get_my_registrations':
        getMyRegistrations();
        break;
    
    // ==========================================
    // 功能5：反饋表單
    // ==========================================
    
    case 'get_feedback_forms':
        getFeedbackForms();
        break;
        
    case 'add_feedback_form':
        addFeedbackForm();
        break;
        
    case 'submit_feedback':
        submitFeedback();
        break;
        
    case 'get_feedback_responses':
        getFeedbackResponses();
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
        
    case 'update_competition_result':
        updateCompetitionResult();
        break;
        
    case 'delete_competition_result':
        deleteCompetitionResult();
        break;
    
    // ==========================================
    // 功能8：檔案上傳
    // ==========================================
    
    case 'create_upload_zone':
        createUploadZone();
        break;
        
    case 'get_upload_zones':
        getUploadZones();
        break;
        
    case 'upload_file':
        uploadFile();
        break;
        
    case 'get_uploaded_files':
        getUploadedFiles();
        break;
    
    // ==========================================
    // 功能9：簽到系統
    // ==========================================
    
    case 'create_attendance_session':
        createAttendanceSession();
        break;
        
    case 'get_attendance_sessions':
        getAttendanceSessions();
        break;
        
    case 'check_in':
        checkIn();
        break;
        
    case 'get_attendance_records':
        getAttendanceRecords();
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
        
    case 'update_meeting_record':
        updateMeetingRecord();
        break;
        
    case 'delete_meeting_record':
        deleteMeetingRecord();
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
        
    case 'get_unread_count':
        getUnreadCount();
        break;
    
    default:
        jsonResponse(false, '未知的 API 操作');
}

// ==========================================
// 用戶管理功能
// ==========================================

function getUsers() {
    global $pdo;
    
    error_log("getUsers called - Session: " . json_encode($_SESSION));
    
    // 檢查登入狀態
    if (!isLoggedIn()) {
        error_log("getUsers - Not logged in");
        jsonResponse(false, '請先登入系統', [
            'session_id' => session_id(),
            'session_data' => $_SESSION
        ]);
        return;
    }
    
    // 檢查權限
    if ($_SESSION['role'] !== 'admin') {
        error_log("getUsers - Insufficient permissions, role: " . $_SESSION['role']);
        jsonResponse(false, '權限不足，需要管理員權限');
        return;
    }
    
    try {
        $stmt = $pdo->query("
            SELECT id, account, role, email, is_active, created_at 
            FROM `user` 
            ORDER BY created_at DESC
        ");
        
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("getUsers - Success, found " . count($users) . " users");
        jsonResponse(true, '取得成功', $users);
        
    } catch (Exception $e) {
        error_log("getUsers - Error: " . $e->getMessage());
        jsonResponse(false, '取得失敗: ' . $e->getMessage());
    }
}

function addUser() {
    global $pdo;
    
    if (!isLoggedIn()) {
        jsonResponse(false, '請先登入');
        return;
    }
    
    if ($_SESSION['role'] !== 'admin') {
        jsonResponse(false, '權限不足');
        return;
    }
    
    $account = trim($_POST['account'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'student');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($account) || empty($password)) {
        jsonResponse(false, '帳號和密碼不能為空');
        return;
    }
    
    $stmt = $pdo->prepare("SELECT id FROM `user` WHERE `account` = ?");
    $stmt->execute([$account]);
    
    if ($stmt->fetch()) {
        jsonResponse(false, '此帳號已存在');
        return;
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO `user` (`account`, `password`, `role`, `email`, `is_active`)
            VALUES (?, ?, ?, ?, 1)
        ");
        
        $result = $stmt->execute([$account, $password, $role, $email]);
        
        if ($result) {
            jsonResponse(true, '新增成功', [
                'id' => $pdo->lastInsertId(),
                'account' => $account,
                'role' => $role,
                'email' => $email
            ]);
        } else {
            jsonResponse(false, '新增失敗');
        }
        
    } catch (Exception $e) {
        jsonResponse(false, '新增失敗: ' . $e->getMessage());
    }
}

function updateUser() {
    global $pdo;
    
    if (!isLoggedIn()) {
        jsonResponse(false, '請先登入');
        return;
    }
    
    if ($_SESSION['role'] !== 'admin') {
        jsonResponse(false, '權限不足');
        return;
    }
    
    $id = $_POST['id'] ?? 0;
    $account = trim($_POST['account'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($account) || empty($role)) {
        jsonResponse(false, '帳號和角色不能為空');
        return;
    }
    
    $stmt = $pdo->prepare("SELECT id FROM `user` WHERE `account` = ? AND id != ?");
    $stmt->execute([$account, $id]);
    
    if ($stmt->fetch()) {
        jsonResponse(false, '此帳號已被使用');
        return;
    }
    
    try {
        if (!empty($password)) {
            $stmt = $pdo->prepare("
                UPDATE `user` 
                SET `account` = ?, `password` = ?, `role` = ?, `email` = ?
                WHERE `id` = ?
            ");
            $result = $stmt->execute([$account, $password, $role, $email, $id]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE `user` 
                SET `account` = ?, `role` = ?, `email` = ?
                WHERE `id` = ?
            ");
            $result = $stmt->execute([$account, $role, $email, $id]);
        }
        
        jsonResponse($result, $result ? '更新成功' : '更新失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '更新失敗: ' . $e->getMessage());
    }
}

function deleteUser() {
    global $pdo;
    
    if (!isLoggedIn()) {
        jsonResponse(false, '請先登入');
        return;
    }
    
    if ($_SESSION['role'] !== 'admin') {
        jsonResponse(false, '權限不足');
        return;
    }
    
    $id = $_POST['id'] ?? 0;
    
    if ($id <= 0) {
        jsonResponse(false, '無效的用戶ID');
        return;
    }
    
    if ($id == $_SESSION['user_id']) {
        jsonResponse(false, '不能刪除自己的帳號');
        return;
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM `user` WHERE `id` = ?");
        $result = $stmt->execute([$id]);
        
        jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '刪除失敗: ' . $e->getMessage());
    }
}

function getUserStats() {
    global $pdo;
    
    if (!isLoggedIn()) {
        jsonResponse(false, '請先登入');
        return;
    }
    
    if ($_SESSION['role'] !== 'admin') {
        jsonResponse(false, '權限不足');
        return;
    }
    
    try {
        $stmt = $pdo->query("
            SELECT 
                role,
                COUNT(*) as count
            FROM `user`
            WHERE `is_active` = 1
            GROUP BY role
        ");
        
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $result = [
            'admin' => 0,
            'teacher' => 0,
            'student' => 0,
            'science_club' => 0
        ];
        
        foreach ($stats as $stat) {
            $result[$stat['role']] = (int)$stat['count'];
        }
        
        jsonResponse(true, '取得成功', $result);
        
    } catch (Exception $e) {
        jsonResponse(false, '取得失敗: ' . $e->getMessage());
    }
}


// ==========================================
// 登入/登出功能實作
// ==========================================

function handleLogin() {
    global $pdo;
    
    $account = trim($_POST['account'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');
    
    // 記錄登入嘗試
    error_log("Login attempt - Account: $account, Role: $role");
    
    if (empty($account) || empty($password) || empty($role)) {
        ?>
        <script>
            alert("請輸入完整帳號、密碼與身分");
            history.back();
        </script>
        <?php
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM `user` 
            WHERE `account` = ? AND `password` = ? AND `role` = ? AND `is_active` = 1
        ");
        $stmt->execute([$account, $password, $role]);
        $user = $stmt->fetch();
        
        if ($user) {
            // 重新生成 Session ID
            session_regenerate_id(true);
            
            // 設置 Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['account'] = $user['account'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_time'] = time();
            
            // ⭐ 關鍵修正：強制寫入 Session 數據
            session_write_close();
            
            // 記錄成功登入
            error_log("Login successful - User ID: {$user['id']}, Account: {$user['account']}, Role: {$user['role']}");
            error_log("Session data saved: " . json_encode($_SESSION));
            
            // 重新啟動 Session（因為我們調用了 write_close）
            session_start();
            
            $redirect_map = [
                'admin' => 'admin_index.html',
                'science_club' => 'science_club_activity_calendar.html',
                'teacher' => 'teacher_dashboard.html',
                'student' => 'student_index.html'
            ];
            
            $redirect = $redirect_map[$role] ?? 'index.html';
            
            ?>
            <script>
                alert("✅ 登入成功！歡迎使用科學會管理系統");
                location.href = "<?php echo $redirect; ?>";
            </script>
            <?php
            
        } else {
            error_log("Login failed - Invalid credentials");
            ?>
            <script>
                alert("❌ 帳號、密碼或身分錯誤，請重新輸入");
                history.back();
            </script>
            <?php
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        ?>
        <script>
            alert("❌ 登入發生錯誤，請聯絡管理員");
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
    error_log("Checking session - " . json_encode($_SESSION));
    
    if (isLoggedIn()) {
        jsonResponse(true, '已登入', [
            'account' => $_SESSION['account'],
            'role' => $_SESSION['role'],
            'user_id' => $_SESSION['user_id']
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
    
    $role = $_SESSION['role'] ?? 'student';
    
    if ($role === 'student') {
        $stmt = $pdo->query("
            SELECT * FROM `calendar_events` 
            WHERE `is_public` = 1 
            ORDER BY `event_date` ASC
        ");
    } else {
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
    
    jsonResponse($result, $result ? '新增成功' : '新增失敗');
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
// 功能2：活動相簿
// ==========================================

function getAlbums() {
    global $pdo;
    
    $role = $_SESSION['role'] ?? 'student';
    
    if ($role === 'student') {
        $stmt = $pdo->query("SELECT * FROM `photo_albums` WHERE `is_public` = 1 ORDER BY `created_at` DESC");
    } else {
        $stmt = $pdo->query("SELECT * FROM `photo_albums` ORDER BY `created_at` DESC");
    }
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function addAlbum() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $album_name = $_POST['album_name'] ?? '';
    $activity_name = $_POST['activity_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $is_public = $_POST['is_public'] ?? 1;
    
    $stmt = $pdo->prepare("
        INSERT INTO `photo_albums` 
        (`album_name`, `activity_name`, `description`, `is_public`, `created_by`)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([$album_name, $activity_name, $description, $is_public, $_SESSION['account']]);
    
    if ($result) {
        jsonResponse(true, '新增成功', ['album_id' => $pdo->lastInsertId()]);
    } else {
        jsonResponse(false, '新增失敗');
    }
}

function getAlbumMedia() {
    global $pdo;
    
    $album_id = $_GET['album_id'] ?? 0;
    
    $stmt = $pdo->prepare("
        SELECT * FROM `album_media` 
        WHERE `album_id` = ? 
        ORDER BY `upload_order` ASC, `uploaded_at` DESC
    ");
    $stmt->execute([$album_id]);
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function uploadMedia() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $album_id = $_POST['album_id'] ?? 0;
    $description = $_POST['description'] ?? '';
    
    if (!isset($_FILES['media_file'])) {
        jsonResponse(false, '沒有上傳檔案');
    }
    
    $file = $_FILES['media_file'];
    $upload_dir = 'uploads/albums/';
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_images = ['jpg', 'jpeg', 'png', 'gif'];
    $allowed_videos = ['mp4', 'avi', 'mov'];
    
    if (in_array($file_ext, $allowed_images)) {
        $media_type = 'image';
    } elseif (in_array($file_ext, $allowed_videos)) {
        $media_type = 'video';
    } else {
        jsonResponse(false, '不支援的檔案格式');
    }
    
    $new_filename = uniqid() . '_' . time() . '.' . $file_ext;
    $file_path = $upload_dir . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        $stmt = $pdo->prepare("
            INSERT INTO `album_media` 
            (`album_id`, `media_type`, `file_path`, `file_name`, `description`)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([$album_id, $media_type, $file_path, $file['name'], $description]);
        
        jsonResponse($result, $result ? '上傳成功' : '上傳失敗');
    } else {
        jsonResponse(false, '檔案上傳失敗');
    }
}

function deleteAlbum() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $album_id = $_POST['album_id'] ?? 0;
    
    // 先刪除相簿內的所有媒體檔案
    $stmt = $pdo->prepare("SELECT file_path FROM `album_media` WHERE `album_id` = ?");
    $stmt->execute([$album_id]);
    $files = $stmt->fetchAll();
    
    foreach ($files as $file) {
        if (file_exists($file['file_path'])) {
            unlink($file['file_path']);
        }
    }
    
    // 刪除相簿
    $stmt = $pdo->prepare("DELETE FROM `photo_albums` WHERE `album_id` = ?");
    $result = $stmt->execute([$album_id]);
    
    jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
}

function deleteMedia() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $media_id = $_POST['media_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT file_path FROM `album_media` WHERE `media_id` = ?");
    $stmt->execute([$media_id]);
    $media = $stmt->fetch();
    
    if ($media && file_exists($media['file_path'])) {
        unlink($media['file_path']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM `album_media` WHERE `media_id` = ?");
    $result = $stmt->execute([$media_id]);
    
    jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
}

// ==========================================
// 功能3：公告系統
// ==========================================

function getAnnouncements() {
    global $pdo;
    
    $limit = $_GET['limit'] ?? 20;
    $type = $_GET['type'] ?? '';
    
    $sql = "SELECT * FROM `announcements`";
    if ($type) {
        $sql .= " WHERE `announcement_type` = '$type'";
    }
    $sql .= " ORDER BY `publish_date` DESC LIMIT $limit";
    
    $stmt = $pdo->query($sql);
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function addAnnouncement() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $type = $_POST['announcement_type'] ?? 'general';
    $is_important = $_POST['is_important'] ?? 0;
    $has_detail = $_POST['has_detail'] ?? 0;
    $detail_content = $_POST['detail_content'] ?? '';
    
    $stmt = $pdo->prepare("
        INSERT INTO `announcements` 
        (`title`, `content`, `announcement_type`, `is_important`, `has_detail`, `detail_content`, `created_by`)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([$title, $content, $type, $is_important, $has_detail, $detail_content, $_SESSION['account']]);
    
    jsonResponse($result, $result ? '發佈成功' : '發佈失敗');
}

function getAnnouncementDetail() {
    global $pdo;
    
    $id = $_GET['id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM `announcements` WHERE `announcement_id` = ?");
    $stmt->execute([$id]);
    $announcement = $stmt->fetch();
    
    if ($announcement) {
        jsonResponse(true, '取得成功', $announcement);
    } else {
        jsonResponse(false, '公告不存在');
    }
}

function updateAnnouncement() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $id = $_POST['announcement_id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    
    $stmt = $pdo->prepare("UPDATE `announcements` SET `title` = ?, `content` = ? WHERE `announcement_id` = ?");
    $result = $stmt->execute([$title, $content, $id]);
    
    jsonResponse($result, $result ? '更新成功' : '更新失敗');
}

function deleteAnnouncement() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $id = $_POST['announcement_id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM `announcements` WHERE `announcement_id` = ?");
    $result = $stmt->execute([$id]);
    
    jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
}

// ==========================================
// 功能4：活動發佈
// ==========================================

function getActivities() {
    global $pdo;
    
    $status = $_GET['status'] ?? 'published';
    
    $stmt = $pdo->prepare("SELECT * FROM `activities` WHERE `status` = ? ORDER BY `start_date` DESC");
    $stmt->execute([$status]);
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function addActivity() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $activity_name = $_POST['activity_name'] ?? '';
    $activity_type = $_POST['activity_type'] ?? 'activity';
    $description = $_POST['description'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $registration_start = $_POST['registration_start'] ?? '';
    $registration_end = $_POST['registration_end'] ?? '';
    $modify_deadline = $_POST['modify_deadline'] ?? null;
    $max_participants = $_POST['max_participants'] ?? null;
    $location = $_POST['location'] ?? '';
    $requirements = $_POST['requirements'] ?? '';
    
    $stmt = $pdo->prepare("
        INSERT INTO `activities` 
        (`activity_name`, `activity_type`, `description`, `start_date`, `end_date`, 
         `registration_start`, `registration_end`, `modify_deadline`, `max_participants`, 
         `location`, `requirements`, `status`, `created_by`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'published', ?)
    ");
    
    $result = $stmt->execute([
        $activity_name, $activity_type, $description, $start_date, $end_date,
        $registration_start, $registration_end, $modify_deadline, $max_participants,
        $location, $requirements, $_SESSION['account']
    ]);
    
    jsonResponse($result, $result ? '發佈成功' : '發佈失敗');
}

function updateActivity() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $activity_id = $_POST['activity_id'] ?? 0;
    $activity_name = $_POST['activity_name'] ?? '';
    $description = $_POST['description'] ?? '';
    
    $stmt = $pdo->prepare("
        UPDATE `activities` 
        SET `activity_name` = ?, `description` = ? 
        WHERE `activity_id` = ?
    ");
    
    $result = $stmt->execute([$activity_name, $description, $activity_id]);
    
    jsonResponse($result, $result ? '更新成功' : '更新失敗');
}

function deleteActivity() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $activity_id = $_POST['activity_id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM `activities` WHERE `activity_id` = ?");
    $result = $stmt->execute([$activity_id]);
    
    jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
}

function getActivityDetail() {
    global $pdo;
    
    $activity_id = $_GET['activity_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM `activities` WHERE `activity_id` = ?");
    $stmt->execute([$activity_id]);
    
    jsonResponse(true, '取得成功', $stmt->fetch());
}

// ==========================================
// 功能6：報名管理
// ==========================================

function registerActivity() {
    global $pdo;
    requireRole('student');
    
    $activity_id = $_POST['activity_id'] ?? 0;
    $student_number = $_POST['student_number'] ?? '';
    $student_name = $_POST['student_name'] ?? '';
    $student_class = $_POST['student_class'] ?? '';
    
    // 檢查是否已報名
    $stmt = $pdo->prepare("SELECT * FROM `registrations` WHERE `activity_id` = ? AND `student_number` = ?");
    $stmt->execute([$activity_id, $student_number]);
    
    if ($stmt->fetch()) {
        jsonResponse(false, '您已經報名過此活動');
    }
    
    // 檢查人數限制
    $stmt = $pdo->prepare("SELECT * FROM `activities` WHERE `activity_id` = ?");
    $stmt->execute([$activity_id]);
    $activity = $stmt->fetch();
    
    if ($activity['max_participants'] && $activity['current_participants'] >= $activity['max_participants']) {
        jsonResponse(false, '報名人數已滿');
    }
    
    // 新增報名
    $stmt = $pdo->prepare("
        INSERT INTO `registrations` 
        (`activity_id`, `student_number`, `student_name`, `student_class`)
        VALUES (?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([$activity_id, $student_number, $student_name, $student_class]);
    
    jsonResponse($result, $result ? '報名成功' : '報名失敗');
}

function cancelRegistration() {
    global $pdo;
    
    $registration_id = $_POST['registration_id'] ?? 0;
    
    $stmt = $pdo->prepare("UPDATE `registrations` SET `status` = 'cancelled' WHERE `registration_id` = ?");
    $result = $stmt->execute([$registration_id]);
    
    jsonResponse($result, $result ? '取消成功' : '取消失敗');
}

function getRegistrations() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $activity_id = $_GET['activity_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM `registrations` WHERE `activity_id` = ? ORDER BY `registration_time` DESC");
    $stmt->execute([$activity_id]);
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function getMyRegistrations() {
    global $pdo;
    requireRole('student');
    
    $student_number = $_SESSION['account'];
    
    $stmt = $pdo->prepare("
        SELECT r.*, a.activity_name, a.start_date 
        FROM `registrations` r 
        JOIN `activities` a ON r.activity_id = a.activity_id 
        WHERE r.student_number = ? 
        ORDER BY r.registration_time DESC
    ");
    $stmt->execute([$student_number]);
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function exportRegistrations() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $activity_id = $_GET['activity_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM `registrations` WHERE `activity_id` = ?");
    $stmt->execute([$activity_id]);
    $registrations = $stmt->fetchAll();
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=registrations_' . $activity_id . '.csv');
    
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
    
    fputcsv($output, ['學號', '姓名', '班級', '報名時間', '狀態']);
    
    foreach ($registrations as $reg) {
        fputcsv($output, [
            $reg['student_number'],
            $reg['student_name'],
            $reg['student_class'],
            $reg['registration_time'],
            $reg['status']
        ]);
    }
    
    fclose($output);
    exit;
}

// ==========================================
// 功能5：反饋表單
// ==========================================

function getFeedbackForms() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM `feedback_forms` WHERE `is_active` = 1 ORDER BY `created_at` DESC");
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function addFeedbackForm() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $form_name = $_POST['form_name'] ?? '';
    $activity_name = $_POST['activity_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $activity_id = $_POST['activity_id'] ?? null;
    
    $stmt = $pdo->prepare("
        INSERT INTO `feedback_forms` 
        (`form_name`, `activity_name`, `description`, `activity_id`, `created_by`)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([$form_name, $activity_name, $description, $activity_id, $_SESSION['account']]);
    
    jsonResponse($result, $result ? '新增成功' : '新增失敗');
}

function submitFeedback() {
    global $pdo;
    requireRole('student');
    
    $form_id = $_POST['form_id'] ?? 0;
    $student_number = $_POST['student_number'] ?? '';
    $student_name = $_POST['student_name'] ?? '';
    $student_class = $_POST['student_class'] ?? '';
    $satisfaction_score = $_POST['satisfaction_score'] ?? 0;
    $feedback_text = $_POST['feedback_text'] ?? '';
    
    $stmt = $pdo->prepare("
        INSERT INTO `feedback_responses` 
        (`form_id`, `student_number`, `student_name`, `student_class`, `satisfaction_score`, `feedback_text`)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([$form_id, $student_number, $student_name, $student_class, $satisfaction_score, $feedback_text]);
    
    jsonResponse($result, $result ? '提交成功' : '提交失敗');
}

function getFeedbackResponses() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $form_id = $_GET['form_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM `feedback_responses` WHERE `form_id` = ? ORDER BY `submitted_at` DESC");
    $stmt->execute([$form_id]);
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

// ==========================================
// 功能7：比賽成績
// ==========================================

function addCompetitionResult() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $activity_id = $_POST['activity_id'] ?? 0;
    $student_number = $_POST['student_number'] ?? '';
    $student_name = $_POST['student_name'] ?? '';
    $student_class = $_POST['student_class'] ?? '';
    $score = $_POST['score'] ?? null;
    $rank = $_POST['rank'] ?? null;
    $award = $_POST['award'] ?? '';
    
    $stmt = $pdo->prepare("
        INSERT INTO `competition_results` 
        (`activity_id`, `student_number`, `student_name`, `student_class`, `score`, `rank`, `award`)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([$activity_id, $student_number, $student_name, $student_class, $score, $rank, $award]);
    
    jsonResponse($result, $result ? '新增成功' : '新增失敗');
}

function getCompetitionResults() {
    global $pdo;
    
    $activity_id = $_GET['activity_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM `competition_results` WHERE `activity_id` = ? ORDER BY `rank` ASC");
    $stmt->execute([$activity_id]);
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function updateCompetitionResult() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $result_id = $_POST['result_id'] ?? 0;
    $score = $_POST['score'] ?? null;
    $rank = $_POST['rank'] ?? null;
    $award = $_POST['award'] ?? '';
    
    $stmt = $pdo->prepare("UPDATE `competition_results` SET `score` = ?, `rank` = ?, `award` = ? WHERE `result_id` = ?");
    $result = $stmt->execute([$score, $rank, $award, $result_id]);
    
    jsonResponse($result, $result ? '更新成功' : '更新失敗');
}

function deleteCompetitionResult() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $result_id = $_POST['result_id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM `competition_results` WHERE `result_id` = ?");
    $result = $stmt->execute([$result_id]);
    
    jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
}

// ==========================================
// 功能8：檔案上傳
// ==========================================

function createUploadZone() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $zone_name = $_POST['zone_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $activity_id = $_POST['activity_id'] ?? null;
    $file_types = $_POST['file_types'] ?? '';
    $max_file_size = $_POST['max_file_size'] ?? 10;
    $deadline = $_POST['deadline'] ?? null;
    
    $stmt = $pdo->prepare("
        INSERT INTO `file_upload_zones` 
        (`zone_name`, `description`, `activity_id`, `file_types`, `max_file_size`, `deadline`, `created_by`)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([$zone_name, $description, $activity_id, $file_types, $max_file_size, $deadline, $_SESSION['account']]);
    
    jsonResponse($result, $result ? '新增成功' : '新增失敗');
}

function getUploadZones() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM `file_upload_zones` WHERE `is_active` = 1 ORDER BY `created_at` DESC");
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function uploadFile() {
    global $pdo;
    requireRole('student');
    
    $zone_id = $_POST['zone_id'] ?? 0;
    $student_number = $_POST['student_number'] ?? '';
    $student_name = $_POST['student_name'] ?? '';
    $title = $_POST['title'] ?? '';
    $upload_type = $_POST['upload_type'] ?? '其他';
    
    if (!isset($_FILES['file'])) {
        jsonResponse(false, '沒有上傳檔案');
    }
    
    $file = $_FILES['file'];
    $upload_dir = 'uploads/files/';
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '_' . time() . '.' . $file_ext;
    $file_path = $upload_dir . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        $stmt = $pdo->prepare("
            INSERT INTO `student_uploads` 
            (`zone_id`, `student_number`, `student_name`, `title`, `image`, `upload_type`, `file_size`)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([$zone_id, $student_number, $student_name, $title, $file_path, $upload_type, $file['size']]);
        
        jsonResponse($result, $result ? '上傳成功' : '上傳失敗');
    } else {
        jsonResponse(false, '檔案上傳失敗');
    }
}

function getUploadedFiles() {
    global $pdo;
    
    $zone_id = $_GET['zone_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM `student_uploads` WHERE `zone_id` = ? ORDER BY `uploaded_at` DESC");
    $stmt->execute([$zone_id]);
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

// ==========================================
// 功能9：簽到系統
// ==========================================

function createAttendanceSession() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $session_name = $_POST['session_name'] ?? '';
    $session_date = $_POST['session_date'] ?? '';
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $location = $_POST['location'] ?? '';
    
    $stmt = $pdo->prepare("
        INSERT INTO `attendance_sessions` 
        (`session_name`, `session_date`, `start_time`, `end_time`, `location`, `created_by`)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([$session_name, $session_date, $start_time, $end_time, $location, $_SESSION['account']]);
    
    jsonResponse($result, $result ? '新增成功' : '新增失敗');
}

function getAttendanceSessions() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM `attendance_sessions` ORDER BY `session_date` DESC");
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function checkIn() {
    global $pdo;
    
    $session_id = $_POST['session_id'] ?? 0;
    $member_name = $_POST['member_name'] ?? '';
    $member_number = $_POST['member_number'] ?? '';
    
    // 檢查是否已簽到
    $stmt = $pdo->prepare("SELECT * FROM `attendance_records` WHERE `session_id` = ? AND `member_number` = ?");
    $stmt->execute([$session_id, $member_number]);
    
    if ($stmt->fetch()) {
        jsonResponse(false, '您已經簽到過了');
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO `attendance_records` 
        (`session_id`, `member_name`, `member_number`)
        VALUES (?, ?, ?)
    ");
    
    $result = $stmt->execute([$session_id, $member_name, $member_number]);
    
    jsonResponse($result, $result ? '簽到成功' : '簽到失敗');
}

function getAttendanceRecords() {
    global $pdo;
    
    $session_id = $_GET['session_id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM `attendance_records` WHERE `session_id` = ? ORDER BY `check_in_time` DESC");
    $stmt->execute([$session_id]);
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

// ==========================================
// 功能10：請假系統
// ==========================================

function submitLeaveRequest() {
    global $pdo;
    
    $applicant_name = $_POST['applicant_name'] ?? '';
    $applicant_number = $_POST['applicant_number'] ?? '';
    $leave_type = $_POST['leave_type'] ?? 'personal';
    $leave_date = $_POST['leave_date'] ?? '';
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $reason = $_POST['reason'] ?? '';
    
    $stmt = $pdo->prepare("
        INSERT INTO `leave_requests` 
        (`applicant_name`, `applicant_number`, `leave_type`, `leave_date`, `start_time`, `end_time`, `reason`)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([$applicant_name, $applicant_number, $leave_type, $leave_date, $start_time, $end_time, $reason]);
    
    jsonResponse($result, $result ? '提交成功' : '提交失敗');
}

function getLeaveRequests() {
    global $pdo;
    
    $status = $_GET['status'] ?? 'all';
    
    if ($status === 'all') {
        $stmt = $pdo->query("SELECT * FROM `leave_requests` ORDER BY `submitted_at` DESC");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM `leave_requests` WHERE `status` = ? ORDER BY `submitted_at` DESC");
        $stmt->execute([$status]);
    }
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function reviewLeaveRequest() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $request_id = $_POST['request_id'] ?? 0;
    $status = $_POST['status'] ?? 'approved';
    $review_comment = $_POST['review_comment'] ?? '';
    
    $stmt = $pdo->prepare("
        UPDATE `leave_requests` 
        SET `status` = ?, `review_comment` = ?, `reviewed_by` = ?, `reviewed_at` = NOW() 
        WHERE `request_id` = ?
    ");
    
    $result = $stmt->execute([$status, $review_comment, $_SESSION['account'], $request_id]);
    
    jsonResponse($result, $result ? '審核成功' : '審核失敗');
}

// ==========================================
// 功能11：開會紀錄
// ==========================================

function getMeetingRecords() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM `meeting_records` ORDER BY `meeting_date` DESC");
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function addMeetingRecord() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $meeting_title = $_POST['meeting_title'] ?? '';
    $meeting_date = $_POST['meeting_date'] ?? '';
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $location = $_POST['location'] ?? '';
    $attendees = $_POST['attendees'] ?? '';
    $agenda = $_POST['agenda'] ?? '';
    $content = $_POST['content'] ?? '';
    $decisions = $_POST['decisions'] ?? '';
    $action_items = $_POST['action_items'] ?? '';
    $next_meeting = $_POST['next_meeting'] ?? null;
    
    $stmt = $pdo->prepare("
        INSERT INTO `meeting_records` 
        (`meeting_title`, `meeting_date`, `start_time`, `end_time`, `location`, 
         `attendees`, `agenda`, `content`, `decisions`, `action_items`, `next_meeting`, `created_by`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([
        $meeting_title, $meeting_date, $start_time, $end_time, $location,
        $attendees, $agenda, $content, $decisions, $action_items, $next_meeting, $_SESSION['account']
    ]);
    
    jsonResponse($result, $result ? '新增成功' : '新增失敗');
}

function updateMeetingRecord() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $meeting_id = $_POST['meeting_id'] ?? 0;
    $content = $_POST['content'] ?? '';
    $decisions = $_POST['decisions'] ?? '';
    
    $stmt = $pdo->prepare("UPDATE `meeting_records` SET `content` = ?, `decisions` = ? WHERE `meeting_id` = ?");
    $result = $stmt->execute([$content, $decisions, $meeting_id]);
    
    jsonResponse($result, $result ? '更新成功' : '更新失敗');
}

function deleteMeetingRecord() {
    global $pdo;
    requireRole(['admin', 'science_club']);
    
    $meeting_id = $_POST['meeting_id'] ?? 0;
    
    $stmt = $pdo->prepare("DELETE FROM `meeting_records` WHERE `meeting_id` = ?");
    $result = $stmt->execute([$meeting_id]);
    
    jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
}

// ==========================================
// 功能12：通知系統
// ==========================================

function getNotifications() {
    global $pdo;
    
    $recipient_account = $_SESSION['account'];
    $recipient_type = $_SESSION['role'];
    
    $stmt = $pdo->prepare("
        SELECT * FROM `notifications` 
        WHERE (`recipient_account` = ? OR `recipient_type` = 'all') 
        AND `recipient_type` IN (?, 'all')
        ORDER BY `created_at` DESC 
        LIMIT 50
    ");
    $stmt->execute([$recipient_account, $recipient_type]);
    
    jsonResponse(true, '取得成功', $stmt->fetchAll());
}

function markNotificationRead() {
    global $pdo;
    
    $notification_id = $_POST['notification_id'] ?? 0;
    
    $stmt = $pdo->prepare("UPDATE `notifications` SET `is_read` = 1, `read_at` = NOW() WHERE `notification_id` = ?");
    $result = $stmt->execute([$notification_id]);
    
    jsonResponse($result, $result ? '標記成功' : '標記失敗');
}

function getUnreadCount() {
    global $pdo;
    
    $recipient_account = $_SESSION['account'];
    
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count FROM `notifications` 
        WHERE `recipient_account` = ? AND `is_read` = 0
    ");
    $stmt->execute([$recipient_account]);
    $result = $stmt->fetch();
    
    jsonResponse(true, '取得成功', ['count' => $result['count']]);
}

function jsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json; charset=utf-8');
    
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

function isLoggedIn() {
    $loggedIn = isset($_SESSION['user_id']) && 
                isset($_SESSION['role']) && 
                isset($_SESSION['account']);
    
    // 記錄檢查結果
    if (!$loggedIn) {
        error_log("isLoggedIn: FALSE - Session: " . json_encode($_SESSION));
    }
    
    return $loggedIn;
}
?>