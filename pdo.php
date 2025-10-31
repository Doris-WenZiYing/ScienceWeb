<?php
/**
 * PDO 資料庫連接設定
 */

// 設定錯誤處理
error_reporting(E_ALL);
ini_set('display_errors', 0); // 關閉顯示錯誤
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log'); // 記錄錯誤到檔案

// 設定 Session 保存路徑
$session_path = sys_get_temp_dir();
if (is_writable($session_path)) {
    session_save_path($session_path);
}

// 設定 Session Cookie 參數
ini_set('session.cookie_path', '/');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 3600);

// 啟動 Session (只在這裡啟動一次)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 資料庫連接參數
$db_host = '127.0.0.1';
$db_name = 'im_management_system';
$db_user = 'root';
$db_pass = '';
$db_charset = 'utf8mb4';

// DSN
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";

// PDO 選項
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $db_charset"
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // 記錄錯誤但不顯示敏感資訊
    error_log("Database connection failed: " . $e->getMessage());
    
    // 回傳 JSON 錯誤訊息
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'message' => '資料庫連接失敗，請稍後再試'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 設定時區
date_default_timezone_set('Asia/Taipei');