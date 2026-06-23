<?php
// 1. 啟動 Session 以便存取當前的登入狀態
session_start();

// 2. 清空所有的 Session 變數值
$_SESSION = array();

// 3. 如果是用 Cookie 記錄 Session ID，徹底將用戶端的 Cookie 銷毀（安全防護機制）
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 4. 徹底銷毀伺服器端的所有 Session 紀錄
session_destroy();
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>Layer Master - 安全登出系統</title>
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="refresh" content="3;url=index.php">
</head>
<body>

    <header>
        <h1>🎂 Layer Master 千層蛋糕智能平台</h1>
    </header>

    <main>
        <section>
            <h2>👋 安全登出成功</h2>
            <p>您已成功安全登出 <strong>Layer Master</strong> 系統。</p>
            <p>感謝您的光臨，系統將在 <span>3 秒後</span> 自動為您導回平台首頁...</p>
            <br>
            <p><a href="index.php">👉 若網頁沒有自動跳轉，請點擊此處返回首頁</a></p>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Layer Master 客製化千層蛋糕網站. 第25組 期末專題成果展現.</p>
    </footer>

</body>
</html>