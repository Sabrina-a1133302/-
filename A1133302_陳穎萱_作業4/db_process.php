<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_input = trim($_POST['email_input'] ?? '');

    if (!empty($email_input) && filter_var($email_input, FILTER_VALIDATE_EMAIL)) {
        $database_file = 'database.txt';
        $existing_emails = [];
        
        if (file_exists($database_file)) {
            $existing_emails = file($database_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        // 檢查是否重複
        if (!in_array($email_input, $existing_emails)) {
            file_put_contents($database_file, $email_input . PHP_EOL, FILE_APPEND | LOCK_EX);
            echo "<p>Email 成功寫入資料庫！</p>";
        } else {
            echo "<p>提示：該 Email 已經存在於資料庫中。</p>";
        }
    } else {
        echo "<p>錯誤：請輸入正確的 Email 格式。</p>";
    }
    echo "<p><a href='index.php'>返回主畫面</a></p>";
} else {
    header('Location: index.php');
    exit;
}
?>