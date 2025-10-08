<?php
session_start();
$host = "localhost";
$dbname = "im_management_system";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

$account = trim($_POST['account']);
$pass = trim($_POST['password']);
$role = trim($_POST['role']);

if ($account === "" || $pass === "" || $role === "") {
    echo "<script>alert('請輸入完整帳號、密碼與身分');history.back();</script>";
    exit;
}

$sql = "SELECT * FROM user WHERE account = ? AND password = ? AND role = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $account, $pass, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['account'] = $user['account'];
    $_SESSION['role'] = $user['role'];

    if ($role == 'student') {
        header("Location: student.html");
    } elseif ($role == 'teacher') {
        header("Location: teacher.html");
    } elseif ($role == 'admin') {
        header("Location: admin.html");
    } else {
        echo "使用者身分錯誤";
    }
} else {
    echo "<script>alert('帳號、密碼或身分錯誤');history.back();</script>";
}

$conn->close();
?>