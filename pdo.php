<?php
/**
 * 科學會管理系統 - 資料庫連線設定
 * Database: im_management_system
 * 支援4種角色: admin, science_club, teacher, student
 */

// 開啟 Session (全系統需要)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 資料庫連線設定
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'im_management_system');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {
    // 建立 PDO 連線
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // 拋出例外
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // 關聯陣列
            PDO::ATTR_EMULATE_PREPARES => false,  // 真正的預處理
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET  // 設定字元集
        ]
    );
    
    // 測試連線成功（開發模式才顯示）
    // echo "✅ 資料庫連線成功<br>";
    
} catch (PDOException $e) {
    // 連線失敗處理
    $error_msg = "❌ 資料庫連接失敗<br><br>";
    $error_msg .= "<strong>錯誤訊息：</strong>" . $e->getMessage() . "<br><br>";
    $error_msg .= "<strong>請檢查：</strong><br>";
    $error_msg .= "1. XAMPP 的 MySQL 是否已啟動（綠色燈）<br>";
    $error_msg .= "2. 資料庫名稱是否正確：<code>" . DB_NAME . "</code><br>";
    $error_msg .= "3. 帳號密碼是否正確（預設 root / 空密碼）<br>";
    $error_msg .= "4. 資料庫是否已建立（執行過 SQL 檔案）<br>";
    
    die($error_msg);
}

// 設定時區（台灣時間）
date_default_timezone_set("Asia/Taipei");

/**
 * 輔助函數：取得當前登入使用者資訊
 */
function getCurrentUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM `user` WHERE `id` = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * 輔助函數：檢查是否已登入
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

/**
 * 輔助函數：檢查角色權限
 */
function checkRole($allowed_roles) {
    if (!isLoggedIn()) {
        return false;
    }
    
    if (is_array($allowed_roles)) {
        return in_array($_SESSION['role'], $allowed_roles);
    }
    
    return $_SESSION['role'] === $allowed_roles;
}

/**
 * 輔助函數：強制登入檢查（未登入則跳轉）
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: index.html");
        exit;
    }
}

/**
 * 輔助函數：角色檢查（權限不足則跳轉）
 */
function requireRole($allowed_roles) {
    requireLogin();
    
    if (!checkRole($allowed_roles)) {
        echo "<script>alert('權限不足！');history.back();</script>";
        exit;
    }
}

/**
 * 輔助函數：JSON 回應
 */
function jsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
?>