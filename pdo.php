<?php

try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;dbname=im_management_system;charset=utf8mb4", 
        "root", 
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // 拋出例外
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // 關聯陣列
            PDO::ATTR_EMULATE_PREPARES => false  // 真正的預處理
        ]
    );
} catch (PDOException $e) {
    // 如果連接失敗，顯示詳細錯誤
    die("❌ 資料庫連接失敗：<br>" . $e->getMessage() . "<br><br>" .
        "請檢查：<br>" .
        "1. XAMPP 的 MySQL 是否啟動（綠色）<br>" .
        "2. 資料庫名稱是否正確：im_management_system<br>" .
        "3. 帳號密碼是否正確（root / 空密碼）<br>");
}

// 設定時區
date_default_timezone_set("Asia/Taipei");
?>