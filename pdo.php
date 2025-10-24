<?php
/**
 * PDO 資料庫連接設定
 */

// 設定 Session 保存路徑（確保有寫入權限）
$session_path = sys_get_temp_dir();
if (is_writable($session_path)) {
    session_save_path($session_path);
}

// 設定 Session Cookie 參數
ini_set('session.cookie_path', '/');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_lifetime', 0); // 瀏覽器關閉時過期
ini_set('session.gc_maxlifetime', 3600); // Session 有效期 1 小時

// 啟動 Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    
    // 記錄 Session 啟動
    error_log("Session started - ID: " . session_id() . ", Save path: " . session_save_path());
}

// 資料庫連接參數
$db_host = '127.0.0.1';           // 資料庫主機
$db_name = 'im_management_system'; // 資料庫名稱
$db_user = 'root';                 // 資料庫使用者名稱
$db_pass = '';                     // 資料庫密碼
$db_charset = 'utf8mb4';           // 字元集

// DSN (資料來源名稱)
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";

// PDO 選項設定
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // 錯誤模式：拋出例外
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // 預設抓取模式：關聯陣列
    PDO::ATTR_EMULATE_PREPARES   => false,                   // 停用模擬預處理語句
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $db_charset"  // 設定字元集
];

try {
    // 建立 PDO 連接
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    
} catch (PDOException $e) {
    // 連接失敗時的錯誤處理
    die("資料庫連接失敗：" . $e->getMessage());
}

// 設定時區
date_default_timezone_set('Asia/Taipei');
?>