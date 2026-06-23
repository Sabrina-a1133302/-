<?php
// 開啟 Session 紀錄登入狀態
session_start();

// 模擬資料庫連線與驗證 (實際串接時改為 SQL 查詢)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 模擬驗證邏輯
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = '最高管理員';
        $_SESSION['role'] = 'admin'; // 管理員身分
        
        // 導向管理員後台總覽看板
        header('Location: admin_dashboard.php');
        exit;
    } elseif ($username === 'vip_user' && $password === 'vip123') {
        $_SESSION['user_id'] = 2;
        $_SESSION['username'] = '尊榮VIP會員';
        $_SESSION['role'] = 'vip';   // VIP會員身分
        
        header('Location: index.php');
        exit;
    } elseif ($username === 'normal_user' && $password === 'user123') {
        $_SESSION['user_id'] = 3;
        $_SESSION['username'] = '一般會員';
        $_SESSION['role'] = 'user';  // 一般會員身分
        
        header('Location: index.php');
        exit;
    } else {
        // 登入失敗
        header('Location: login.php?error=invalid_credentials');
        exit;
    }
} else {
    // 非 POST 請求直接拒絕存取
    header('Location: login.php');
    exit;
}
?>