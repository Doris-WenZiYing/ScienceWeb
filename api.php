<?php
include("pdo.php");

if ($_GET['action'] == "login") {
    $acc = $_POST['account'];
    $pw = $_POST['password'];
    $role = $_POST['role'];

    // 防呆：欄位不能空白
    if ($acc === "" || $pw === "" || $role === "") {
        ?>
        <script>
            alert("請輸入完整帳號、密碼與身分");
            history.back();
        </script>
        <?php
        exit;
    }

    // 使用預處理防止SQL injection
    $stmt = $pdo->prepare("SELECT * FROM `user` WHERE `account` = ? AND `password` = ?");
    $stmt->execute([$acc, $pw]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if ($row['role'] === $role) {
            // 登入成功，身分一致
            if ($role == 'admin') {
                $redirect = "activity_calendar.html";
            } elseif ($role == 'teacher') {
                $redirect = "teacher_dashboard.html";
            } elseif ($role == 'student') {
                $redirect = "student_dashboard.html";
            } else {
                $redirect = "index.html";
            }
            ?>
            <script>
                alert("登入成功");
                location.href = "<?php echo $redirect; ?>";
            </script>
            <?php
        } else {
            // 帳密正確但身分不符
            ?>
            <script>
                alert("您選擇的身分錯誤，請重新選擇正確身分");
                history.back();
            </script>
            <?php
        }
    } else {
        ?>
        <script>
            alert("帳號或密碼錯誤，請重新輸入");
            history.back();
        </script>
        <?php
    }
}
?>