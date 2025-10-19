<?php
/**
 * 學生專用 API - 修正版
 * 處理所有學生相關的功能請求
 */

session_start();
include("pdo.php");

// 設定JSON回應
header('Content-Type: application/json; charset=utf-8');

// 🔧 開啟錯誤顯示以便除錯
error_reporting(E_ALL);
ini_set('display_errors', 0); // 改為 0 避免破壞 JSON 格式
ini_set('log_errors', 1);

// 獲取action參數
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ⚠️ 測試模式：暫時關閉登入檢查
$test_mode = true; // 正式上線後改為 false

// 檢查登入狀態（除了某些公開API）
$public_actions = ['get_events', 'get_albums', 'get_album_photos', 'get_activities', 'get_announcements'];
if (!$test_mode && !in_array($action, $public_actions) && !isStudentLoggedIn()) {
    jsonResponse(false, '請先登入系統');
}

// 🔧 測試模式下設定假的 Session
if ($test_mode && !isset($_SESSION['account'])) {
    $_SESSION['account'] = 'student001';
    $_SESSION['student_name'] = '測試學生';
    $_SESSION['role'] = 'student';
}

// 路由處理
switch ($action) {
    
    // ==========================================
    // 1. 行事曆功能
    // ==========================================
    case 'get_events':
        getEvents();
        break;
    
    // ==========================================
    // 2. 活動報名功能
    // ==========================================
    case 'get_activities':
        getActivities();
        break;
        
    case 'register_activity':
        registerActivity();
        break;
    
    // ==========================================
    // 3. 我的報名管理
    // ==========================================
    case 'get_my_registrations':
        getMyRegistrations();
        break;
        
    case 'update_registration':
        updateRegistration();
        break;
        
    case 'cancel_registration':
        cancelRegistration();
        break;
    
    // ==========================================
    // 4. 個人活動紀錄
    // ==========================================
    case 'get_my_records':
        getMyRecords();
        break;
    
    // ==========================================
    // 5. 檔案上傳管理
    // ==========================================
    case 'upload_file':
        uploadFile();
        break;
        
    case 'get_my_files':
        getMyFiles();
        break;
        
    case 'delete_file':
        deleteFile();
        break;
    
    // ==========================================
    // 6. 回饋表單
    // ==========================================
    case 'get_feedback_forms':
        getFeedbackForms();
        break;
        
    case 'submit_feedback':
        submitFeedback();
        break;
    
    // ==========================================
    // 7. 活動相簿
    // ==========================================
    case 'get_albums':
        getAlbums();
        break;
        
    case 'get_album_photos':
        getAlbumPhotos();
        break;
    
    // ==========================================
    // 8. 公告
    // ==========================================
    case 'get_announcements':
        getAnnouncements();
        break;
    
    default:
        jsonResponse(false, '未知的操作: ' . $action);
}

// ==========================================
// 功能實作
// ==========================================

/**
 * 1. 獲取行事曆事件
 */
function getEvents() {
    global $pdo;
    
    try {
        // 只顯示公開的活動
        $stmt = $pdo->query("
            SELECT 
                event_id as id,
                title,
                event_date as date,
                start_time,
                end_time,
                location,
                description
            FROM calendar_events 
            WHERE is_public = 1 
            ORDER BY event_date ASC
        ");
        
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 🔧 直接回傳陣列，不包裝（配合前端期待）
        echo json_encode($events, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 2. 獲取可報名的活動列表
 * 🔧 修正：移除日期限制，顯示所有已發布的活動
 */
function getActivities() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                activity_id as id,
                activity_name as name,
                activity_type,
                description,
                start_date,
                end_date,
                registration_start,
                registration_end,
                modify_deadline,
                max_participants,
                current_participants,
                location,
                requirements,
                status
            FROM activities 
            WHERE status = 'published'
            ORDER BY start_date DESC
        ");
        
        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 🔧 直接回傳陣列
        echo json_encode($activities, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 8. 獲取公告
 */
function getAnnouncements() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                announcement_id as id,
                title,
                content,
                announcement_type as type,
                is_important,
                publish_date,
                created_by
            FROM announcements 
            ORDER BY publish_date DESC
            LIMIT 20
        ");
        
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 直接回傳陣列
        echo json_encode($announcements, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 3. 報名活動
 * 🔧 修正：支援多種參數格式
 */
function registerActivity() {
    global $pdo;
    
    // 支援 POST 或 JSON 格式
    $input = json_decode(file_get_contents('php://input'), true);
    
    $activity_id = $_POST['eventId'] ?? $_POST['activity_id'] ?? $input['eventId'] ?? $input['activity_id'] ?? 0;
    $name = trim($_POST['name'] ?? $input['name'] ?? '');
    $class = trim($_POST['class'] ?? $input['class'] ?? '');
    $email = trim($_POST['email'] ?? $input['email'] ?? '');
    
    if (empty($name) || empty($class)) {
        jsonResponse(false, '請填寫完整報名資料');
    }
    
    try {
        // 檢查是否已報名
        $stmt = $pdo->prepare("
            SELECT registration_id 
            FROM registrations 
            WHERE activity_id = ? AND student_number = ?
        ");
        $stmt->execute([$activity_id, $_SESSION['account']]);
        
        if ($stmt->fetch()) {
            jsonResponse(false, '您已經報名過此活動');
        }
        
        // 檢查活動是否存在
        $stmt = $pdo->prepare("SELECT * FROM activities WHERE activity_id = ?");
        $stmt->execute([$activity_id]);
        $activity = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$activity) {
            jsonResponse(false, '找不到此活動');
        }
        
        // 檢查人數限制
        if ($activity['max_participants'] && 
            $activity['current_participants'] >= $activity['max_participants']) {
            jsonResponse(false, '報名人數已滿');
        }
        
        // 新增報名
        $stmt = $pdo->prepare("
            INSERT INTO registrations 
            (activity_id, student_number, student_name, student_class, status)
            VALUES (?, ?, ?, ?, 'registered')
        ");
        
        $result = $stmt->execute([
            $activity_id,
            $_SESSION['account'],
            $name,
            $class
        ]);
        
        if ($result) {
            // 更新活動參與人數
            $pdo->prepare("
                UPDATE activities 
                SET current_participants = current_participants + 1 
                WHERE activity_id = ?
            ")->execute([$activity_id]);
            
            jsonResponse(true, '報名成功！', [
                'registration_id' => $pdo->lastInsertId()
            ]);
        } else {
            jsonResponse(false, '報名失敗，請稍後再試');
        }
        
    } catch (Exception $e) {
        jsonResponse(false, '報名失敗: ' . $e->getMessage());
    }
}

/**
 * 4. 獲取我的報名記錄
 */
function getMyRegistrations() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                r.registration_id as id,
                r.student_name as name,
                r.student_class as class,
                r.registration_time as date,
                r.status,
                a.activity_name as eventName,
                a.start_date,
                a.modify_deadline
            FROM registrations r
            JOIN activities a ON r.activity_id = a.activity_id
            WHERE r.student_number = ?
            AND r.status = 'registered'
            ORDER BY r.registration_time DESC
        ");
        
        $stmt->execute([$_SESSION['account']]);
        $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 為每筆記錄加上 email（前端需要）
        foreach($registrations as &$reg) {
            $reg['email'] = ''; // 可以從 student 表查詢
        }
        
        // 🔧 直接回傳陣列
        echo json_encode($registrations, JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 5. 修改報名資料
 */
function updateRegistration() {
    global $pdo;
    
    // 支援多種格式
    $input = json_decode(file_get_contents('php://input'), true);
    
    $registration_id = $_POST['id'] ?? $_GET['id'] ?? $input['id'] ?? 0;
    $name = trim($_POST['name'] ?? $input['name'] ?? '');
    $email = trim($_POST['email'] ?? $input['email'] ?? '');
    
    if (empty($name)) {
        jsonResponse(false, '請填寫姓名');
    }
    
    try {
        // 檢查是否為自己的報名記錄
        $stmt = $pdo->prepare("
            SELECT r.*, a.modify_deadline 
            FROM registrations r
            JOIN activities a ON r.activity_id = a.activity_id
            WHERE r.registration_id = ? AND r.student_number = ?
        ");
        $stmt->execute([$registration_id, $_SESSION['account']]);
        $registration = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$registration) {
            jsonResponse(false, '找不到此報名記錄');
        }
        
        // 更新報名資料
        $stmt = $pdo->prepare("
            UPDATE registrations 
            SET student_name = ?,
                modified_time = NOW()
            WHERE registration_id = ?
        ");
        
        $result = $stmt->execute([$name, $registration_id]);
        
        jsonResponse($result, $result ? '修改成功' : '修改失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '修改失敗: ' . $e->getMessage());
    }
}

/**
 * 6. 取消報名
 */
function cancelRegistration() {
    global $pdo;
    
    $registration_id = $_POST['id'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("
            SELECT r.*, a.activity_id
            FROM registrations r
            JOIN activities a ON r.activity_id = a.activity_id
            WHERE r.registration_id = ? AND r.student_number = ?
        ");
        $stmt->execute([$registration_id, $_SESSION['account']]);
        $registration = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$registration) {
            jsonResponse(false, '找不到此報名記錄');
        }
        
        // 更新狀態為已取消
        $stmt = $pdo->prepare("
            UPDATE registrations 
            SET status = 'cancelled'
            WHERE registration_id = ?
        ");
        
        $result = $stmt->execute([$registration_id]);
        
        if ($result) {
            // 減少活動參與人數
            $pdo->prepare("
                UPDATE activities 
                SET current_participants = current_participants - 1 
                WHERE activity_id = ?
            ")->execute([$registration['activity_id']]);
            
            jsonResponse(true, '取消報名成功');
        } else {
            jsonResponse(false, '取消失敗');
        }
        
    } catch (Exception $e) {
        jsonResponse(false, '取消失敗: ' . $e->getMessage());
    }
}

/**
 * 7. 獲取個人活動紀錄
 */
function getMyRecords() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                r.registration_id,
                a.activity_name as eventName,
                a.activity_type as competition,
                r.registration_time as date,
                r.student_name as name,
                r.status,
                cr.rank,
                cr.score,
                cr.award
            FROM registrations r
            JOIN activities a ON r.activity_id = a.activity_id
            LEFT JOIN competition_results cr ON 
                cr.activity_id = a.activity_id AND 
                cr.student_number = r.student_number
            WHERE r.student_number = ?
            ORDER BY r.registration_time DESC
        ");
        
        $stmt->execute([$_SESSION['account']]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(true, '獲取成功', $records);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 8. 上傳檔案
 */
function uploadFile() {
    global $pdo;
    
    if (!isset($_FILES['file'])) {
        jsonResponse(false, '沒有上傳檔案');
    }
    
    $file = $_FILES['file'];
    $title = $_POST['title'] ?? $file['name'];
    $upload_type = $_POST['upload_type'] ?? '其他';
    
    // 檢查檔案大小（10MB限制）
    $max_size = 10 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        jsonResponse(false, '檔案大小超過10MB限制');
    }
    
    // 檢查上傳錯誤
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error_messages = [
            UPLOAD_ERR_INI_SIZE => '檔案大小超過 php.ini 限制',
            UPLOAD_ERR_FORM_SIZE => '檔案大小超過表單限制',
            UPLOAD_ERR_PARTIAL => '檔案只有部分被上傳',
            UPLOAD_ERR_NO_FILE => '沒有檔案被上傳',
            UPLOAD_ERR_NO_TMP_DIR => '缺少臨時資料夾',
            UPLOAD_ERR_CANT_WRITE => '檔案寫入失敗',
        ];
        jsonResponse(false, $error_messages[$file['error']] ?? '未知上傳錯誤');
    }
    
    // 檢查檔案類型
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, $allowed_types)) {
        jsonResponse(false, '不支援的檔案格式: ' . $file_ext);
    }
    
    try {
        // 創建上傳目錄
        $upload_dir = 'uploads/students/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // 生成唯一檔名
        $new_filename = uniqid() . '_' . time() . '.' . $file_ext;
        $file_path = $upload_dir . $new_filename;
        
        // 移動檔案
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // 儲存到資料庫
            $stmt = $pdo->prepare("
                INSERT INTO student_uploads 
                (student_number, student_name, title, image, upload_type, file_extension, file_size)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $_SESSION['account'],
                $_SESSION['student_name'] ?? $_SESSION['account'],
                $title,
                $file_path,
                $upload_type,
                $file_ext,
                $file['size']
            ]);
            
            if ($result) {
                jsonResponse(true, '上傳成功', [
                    'upload_id' => $pdo->lastInsertId(),
                    'file_path' => $file_path
                ]);
            } else {
                // 刪除已上傳的檔案
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                jsonResponse(false, '資料庫儲存失敗');
            }
        } else {
            jsonResponse(false, '檔案移動失敗，請檢查目錄權限');
        }
        
    } catch (Exception $e) {
        jsonResponse(false, '上傳失敗: ' . $e->getMessage());
    }
}

/**
 * 9. 獲取我的檔案列表
 */
function getMyFiles() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                upload_id as id,
                title as name,
                image as url,
                upload_type as type,
                file_size as size,
                file_extension as extension,
                uploaded_at as uploadDate
            FROM student_uploads
            WHERE student_number = ?
            ORDER BY uploaded_at DESC
        ");
        
        $stmt->execute([$_SESSION['account']]);
        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(true, '獲取成功', $files);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 10. 刪除檔案
 */
function deleteFile() {
    global $pdo;
    
    $file_id = $_POST['id'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM student_uploads 
            WHERE upload_id = ? AND student_number = ?
        ");
        $stmt->execute([$file_id, $_SESSION['account']]);
        $file = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$file) {
            jsonResponse(false, '找不到此檔案');
        }
        
        // 刪除實體檔案
        if (file_exists($file['image'])) {
            unlink($file['image']);
        }
        
        // 刪除資料庫記錄
        $stmt = $pdo->prepare("DELETE FROM student_uploads WHERE upload_id = ?");
        $result = $stmt->execute([$file_id]);
        
        jsonResponse($result, $result ? '刪除成功' : '刪除失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '刪除失敗: ' . $e->getMessage());
    }
}

/**
 * 11. 獲取回饋表單列表
 */
function getFeedbackForms() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                form_id as id,
                form_name as name,
                activity_name,
                description,
                created_at
            FROM feedback_forms
            WHERE is_active = 1
            ORDER BY created_at DESC
        ");
        
        $forms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(true, '獲取成功', $forms);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 12. 提交回饋表單
 */
function submitFeedback() {
    global $pdo;
    
    $form_id = $_POST['form_id'] ?? $_POST['activityId'] ?? 0;
    $student_name = trim($_POST['student_name'] ?? $_POST['studentName'] ?? '');
    $student_class = trim($_POST['student_class'] ?? $_POST['class'] ?? '');
    $satisfaction_score = $_POST['satisfaction_score'] ?? $_POST['satisfaction'] ?? 3;
    $feedback_text = trim($_POST['feedback_text'] ?? $_POST['comment'] ?? '');
    
    if (empty($student_name)) {
        jsonResponse(false, '請填寫姓名');
    }
    
    try {
        // 檢查是否已提交過
        $stmt = $pdo->prepare("
            SELECT response_id 
            FROM feedback_responses 
            WHERE form_id = ? AND student_number = ?
        ");
        $stmt->execute([$form_id, $_SESSION['account']]);
        
        if ($stmt->fetch()) {
            jsonResponse(false, '您已經提交過此表單');
        }
        
        // 新增回饋
        $stmt = $pdo->prepare("
            INSERT INTO feedback_responses 
            (form_id, student_number, student_name, student_class, satisfaction_score, feedback_text)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $form_id,
            $_SESSION['account'],
            $student_name,
            $student_class,
            $satisfaction_score,
            $feedback_text
        ]);
        
        jsonResponse($result, $result ? '提交成功，感謝您的回饋！' : '提交失敗');
        
    } catch (Exception $e) {
        jsonResponse(false, '提交失敗: ' . $e->getMessage());
    }
}

/**
 * 13. 獲取相簿列表
 */
function getAlbums() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                album_id as id,
                album_name as title,
                activity_name,
                description,
                cover_image,
                created_at
            FROM photo_albums
            WHERE is_public = 1
            ORDER BY created_at DESC
        ");
        
        $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(true, '獲取成功', $albums);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

/**
 * 14. 獲取相簿照片
 */
function getAlbumPhotos() {
    global $pdo;
    
    $album_id = $_GET['album_id'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                media_id as id,
                media_type as type,
                file_path as path,
                file_name as name,
                description,
                uploaded_at
            FROM album_media
            WHERE album_id = ?
            ORDER BY upload_order ASC, uploaded_at DESC
        ");
        
        $stmt->execute([$album_id]);
        $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(true, '獲取成功', $photos);
        
    } catch (Exception $e) {
        jsonResponse(false, '獲取失敗: ' . $e->getMessage());
    }
}

// ==========================================
// 輔助函數
// ==========================================

/**
 * 檢查學生是否已登入
 */
function isStudentLoggedIn() {
    return isset($_SESSION['user_id']) && 
           isset($_SESSION['account']) && 
           isset($_SESSION['role']) &&
           $_SESSION['role'] === 'student';
}

/**
 * JSON回應輸出
 */
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