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

function send_order_notification($email, $name, $id, $status) {
    $mail = new PHPMailer(true);
    $clean_email = 'lantengjing331@gmail.com';
    $clean_password = 'zphy lvui otyn ixrf';

    try {
        // ✅ ✅ ✅ 加這行（顯示SMTP錯誤）
        $mail->SMTPDebug = 2;

        // 2. 伺服器安全設定
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   =  $clean_email;           
        $mail->Password   = $clean_password;        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port       = 587;                                    
        $mail->CharSet    = 'UTF-8';                                

        // 3. 收件人與寄件人設定
        $mail->setFrom($clean_email, 'Layer Master 智能平台');
        $mail->addAddress($email, $name);

        // 4. 動態產出郵件內容
        if ($status === 'completed') {
            $mail->Subject = '🎂 Layer Master：您的客製化千層蛋糕已製作完成！';
            $mail->Body    = "親愛的會員 {$name} 您好：\n\n您在 Layer Master 訂購的客製化千層蛋糕（訂單編號：#{$id}）目前已由烘焙師製作完成，並安排出貨！\n\n感謝您的支持，期待您開啟這份精心製作的美味蛋糕！\n\n---\nLayer Master";
        } else {
            $mail->Subject = '📦 Layer Master：您的蛋糕訂單確認通知';
            $mail->Body    = "親愛的會員 {$name} 您好：\n\n感謝您使用 Layer Master 智能平台設計專屬千層蛋糕。\n我們已收到您的訂單（編號：#{$id}），系統正在為您排單製作。\n\n您隨時可以進入「會員中心」頁面查看歷史訂單。\n\n---\nLayer Master";
        }

        // 5. 執行發送
        $mail->send();
        return true;

    } catch (Exception $e) {
        // ✅ ✅ ✅ 改這裡（原本是 error_log → 改成顯示）
        echo "❌ 郵件發送失敗：" . $mail->ErrorInfo;
        return false;
    }
}
?>