<?php
session_start();
require_once 'db_config.php'; // 這裡載入了 $pdo 物件

$message = "";
$status = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; 

    // 使用 PDO 檢查帳號是否已存在
    $check_stmt = $pdo->prepare("SELECT * FROM `members` WHERE `username` = ?");
    $check_stmt->execute([$username]);

    if ($check_stmt->rowCount() > 0) {
        $message = "❌ 抱歉，此帳號已被註冊。";
        $status = "error";
    } else {
        // 使用 PDO 寫入新會員 (正式環境建議加入 password_hash)
        $insert_query = "INSERT INTO `members` (username, email, password, role) VALUES (?, ?, ?, 'member')";
        $insert_stmt = $pdo->prepare($insert_query);
        
        if ($insert_stmt->execute([$username, $email, $password])) {
            $message = "✅ 註冊成功！系統將在 3 秒後自動導向登入頁面...";
            $status = "success";
        } else {
            $message = "❌ 註冊失敗，請聯繫管理員。";
            $status = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>Layer Master - 加入會員</title>
    <?php if ($status === 'success'): ?>
        <meta http-equiv="refresh" content="3;url=login.php">
    <?php endif; ?>
</head>
<body style="font-family: 'PingFang TC', sans-serif; background-color: #faf7f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background-image: radial-gradient(#ecdcd0 1px, transparent 0); background-size: 20px 20px;">

    <div style="max-width: 450px; width: 100%; background: #fff; border-radius: 16px; padding: 40px; box-shadow: 0 15px 35px rgba(74,59,50,0.1);">
        <h2 style="color: #4a3b32; text-align: center; margin-bottom: 25px;">註冊新會員</h2>
        
        <?php if ($message): ?>
            <div style="padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold; 
                <?php echo ($status === 'success') ? 'background: #f0fff4; border: 1px solid #b7eb8f; color: #389e0d;' : 'background: #fff5f5; border: 1px solid #ffa8a8; color: #e63946;'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($status !== 'success'): ?>
        <form action="register.php" method="POST">
            <div style="margin-bottom: 15px;">
                <label>使用者帳號：</label>
                <input type="text" name="username" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>電子郵件：</label>
                <input type="email" name="email" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box;">
            </div>
            <div style="margin-bottom: 20px;">
                <label>密碼：</label>
                <input type="password" name="password" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box;">
            </div>
            <button type="submit" style="width:100%; padding:15px; background:#cd6143; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">立即註冊</button>
        </form>
        
        <p style="text-align:center; margin-top:20px;">
            已有帳號？<a href="login.php" style="color:#cd6143;">直接登入</a>
        </p>
        <?php endif; ?>
    </div>
</body>
</html>