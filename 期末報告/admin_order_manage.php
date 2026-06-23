<?php
session_start();

// 1. 權限控管
if (($_SESSION['role'] ?? 'guest') !== 'admin') {
    die("❌ 權限不足！本作業僅限系統管理員操作。");
}

require_once 'db_config.php';
require_once 'send_mail.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $receiver_email = isset($_POST['receiver_email']) ? mysqli_real_escape_string($link, $_POST['receiver_email']) : '';
    
    $new_status = 'completed';

    if ($order_id > 0 && !empty($receiver_email)) {
        $update_query = "UPDATE `orders` SET `order_status` = '$new_status' WHERE `order_id` = $order_id";
        if (mysqli_query($link, $update_query)) {
            $mail_success = send_order_notification($receiver_email, "貴賓", $order_id, $new_status);
            $_SESSION['msg'] = $mail_success ? "✅ 訂單已更新並發信。" : "⚠️ 訂單已更新，發信失敗。";
        }
    }
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>訂單管理</title>
</head>
<body>
    <div style="margin: 20px;">
        <a href="admin_panel.php" style="padding: 10px 20px; background: #607d8b; color: white; text-decoration: none; border-radius: 5px;">
            ⬅ 返回後台管理系統
        </a>
    </div>
    <h1>訂單管理系統</h1>
    <p>正在處理訂單中...</p>
</body>
</html>