<?php
// 保持 Session 啟動
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 引入連線檔，確保 $pdo 物件可用
require_once 'db_config.php';

$error_msg = "";

// 登入處理邏輯
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_user = $_POST['username'] ?? '';
    $input_pass = $_POST['password'] ?? '';

    try {
        // 使用 PDO 預備語句查詢 members 資料表
        $stmt = $pdo->prepare("SELECT * FROM `members` WHERE `username` = :username LIMIT 1");
        $stmt->execute(['username' => $input_user]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data) {
            // 比對密碼 (若未來有加密需求，此處可改為 password_verify)
            if ($input_pass === $user_data['password']) {
                // 登入成功，寫入 Session
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['role'] = $user_data['role'] ?? 'member';
                $_SESSION['id'] = $user_data['id'] ?? '';
                $_SESSION['email'] = $user_data['email'] ?? '';
                
                // 跳轉至首頁
                header("Location: index.php");
                exit();
            } else {
                $error_msg = "❌ 認證密碼不正確，請重新輸入。";
            }
        } else {
            $error_msg = "❌ 找不到此帳號，請確認拼字或聯繫後台。";
        }
    } catch (PDOException $e) {
        $error_msg = "系統錯誤，請稍後再試。";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>Layer Master - 會員中心登入</title>
</head>
<body style="font-family: 'PingFang TC', 'Microsoft JhengHei', sans-serif; background-color: #faf7f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background-image: radial-gradient(#ecdcd0 1px, transparent 0); background-size: 20px 20px;">

    <div style="max-width: 450px; width: 100%; background: #fff; border-radius: 16px; padding: 40px; box-shadow: 0 15px 35px rgba(74,59,50,0.1); border: 1px solid #f4ece8; margin: 20px; box-sizing: border-box;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 3rem; margin-bottom: 10px;">🔑</div>
            <h2 style="color: #4a3b32; font-size: 1.8rem; margin: 0 0 8px 0;">登入 Layer Master</h2>
            <p style="color: #8d7362; margin: 0; font-size: 0.95rem;">進入您的專屬千層客製控制面板</p>
        </div>

        <?php if(!empty($error_msg)): ?>
            <div style="background: #fff5f5; color: #e63946; border: 1px solid #ffa8a8; padding: 12px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 20px; text-align: center; font-weight: bold;">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div style="margin-bottom: 20px;">
                <label for="username" style="display:block; margin-bottom: 8px; font-weight:bold; color:#4a3b32; font-size: 0.95rem;">使用者帳號 (Username)：</label>
                <input type="text" name="username" id="username" required placeholder="請輸入註冊帳號" style="width: 100%; padding: 14px; border-radius: 8px; border: 1px solid #d3c2b5; box-sizing: border-box; font-size: 1rem; color: #4a3b32; background: #fafaf9; outline: none;">
            </div>

            <div style="margin-bottom: 25px;">
                <label for="password" style="display:block; margin-bottom: 8px; font-weight:bold; color:#4a3b32; font-size: 0.95rem;">安全驗證密碼 (Password)：</label>
                <input type="password" name="password" id="password" required placeholder="請輸入密碼" style="width: 100%; padding: 14px; border-radius: 8px; border: 1px solid #d3c2b5; box-sizing: border-box; font-size: 1rem; color: #4a3b32; background: #fafaf9; outline: none;">
            </div>

            <button type="submit" style="width: 100%; background: linear-gradient(135deg, #e7a984 0%, #cd6143 100%); color: #fff; border: none; padding: 15px; border-radius: 8px; font-size: 1.1rem; font-weight: bold; cursor: pointer; box-shadow: 0 6px 16px rgba(205,97,67,0.3); letter-spacing: 2px;">
                登入 ➔
            </button>
        </form>

        <div style="margin-top: 25px; text-align: center; border-top: 1px dashed #eedfd5; padding-top: 25px;">
            <div style="margin-bottom: 15px;">
                <a href="register.php" style="display: block; width: 100%; padding: 12px; border: 2px solid #e7a984; border-radius: 8px; color: #cd6143; text-decoration: none; font-weight: bold; font-size: 1rem; box-sizing: border-box;">
                    註冊新會員
                </a>
            </div>
            
            <a href="index.php" style="color: #8d7362; text-decoration: none; font-size: 0.9rem; font-weight: 500;">返回前台首頁</a>
        </div>
    </div>
</body>
</html>