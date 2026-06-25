<?php
/**
 * Layer Master 核心郵件通知系統 - 結合 PHPMailer 完美版
 */

// 1. 引入 PHPMailer 的核心檔案
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_order_notification($to_email, $username, $order_id, $status) {
    $mail = new PHPMailer(true);

    try {
        // 2. 伺服器安全設定
        $mail->isSMTP();                                            // 使用 SMTP 發信
        $mail->Host       = 'smtp.gmail.com';                     // Gmail SMTP 伺服器
        $mail->SMTPAuth   = true;                                   // 開啟 SMTP 驗證
        $mail->Username   = 'lantengjing@gmail.com';             // 您的 Gmail 帳號
        $mail->Password   = 'zphy lvui otyn ixrf';           // 您的 Google 應用程式密碼
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // 啟用 TLS 加密
        $mail->Port       = 587;                                    // TLS 連接埠
        $mail->CharSet    = 'UTF-8';                                // 設定編碼防止中文亂碼

        // 3. 收件人與寄件人設定
        $mail->setFrom('lantengjing331@gmail.com', 'Layer Master 智能平台');
        $mail->addAddress($to_email, $username);                    // 新增收件人

        // 4. 動態產出郵件主旨與文字內容 (依據企劃書動態觸發)
        if ($status === 'completed') {
            $mail->Subject = '🎂 Layer Master：您的客製化千層蛋糕已製作完成！';
            $mail->Body    = "親愛的會員 {$username} 您好：\n\n您在 Layer Master 訂購的客製化千層蛋糕（訂單編號：#{$order_id}）目前已由烘焙師製作完成，並安排出貨！\n\n感謝您的支持，期待您開啟這份精心渲染的美味結構！\n\n---\nLayer Master 第25組專題研發團隊";
        } else {
            $mail->Subject = '📦 Layer Master：您的蛋糕訂單確認通知';
            $mail->Body    = "親愛的會員 {$username} 您好：\n\n感謝您使用 Layer Master 智能平台設計專屬千層蛋糕。\n我們已收到您的訂單（編號：#{$order_id}），系統正在為您排單製作。\n\n您隨時可以登入「歷史訂單與配方追蹤」頁面查看最新進度。\n\n---\nLayer Master 第25組專題研發團隊";
        }

        // 5. 執行發送
        $mail->send();
        return true;
    } catch (Exception $e) {
        // 如果失敗，可以將錯誤訊息紀錄（Demo 時可用來除錯）
        error_log("郵件發送失敗。Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
