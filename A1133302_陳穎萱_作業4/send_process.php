<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// 引入您提供的 PHPMailer 核心檔案
require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

// 關閉輸出快取，確保進度日誌可以即時逐行顯示在瀏覽器上
if (ob_get_level() == 0) ob_start();

echo "<!DOCTYPE html><html lang='zh-TW'><head><meta charset='UTF-8'><title>郵件發送狀態</title></head><body>";
echo "<h1>郵件發送進度日誌</h1>";
echo "<hr>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = htmlspecialchars($_POST['mail_subject']);
    $content = nl2br(htmlspecialchars($_POST['mail_content']));
    $send_mode = $_POST['send_mode'];
    $random_size = intval($_POST['random_size']);
    $sec_min = intval($_POST['sec_min']);
    $sec_max = intval($_POST['sec_max']);

    $database_file = 'database.txt';
    if (!file_exists($database_file)) {
        echo "<p>錯誤：目前資料庫中沒有任何名單！</p>";
        echo "<a href='index.php'>返回</a>";
        exit;
    }
    $all_emails = file($database_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $total_in_db = count($all_emails);

    // 篩選與抽選發送名單
    $target_list = [];
    if ($send_mode === 'all') {
        $target_list = $all_emails;
    } else {
        // 安全檢查：若輸入的隨機筆數大於資料庫總數，則限制為總數
        if ($random_size > $total_in_db) {
            $random_size = $total_in_db;
        }
        
        // 核心邏輯：隨機選出指定筆數的 Key
        $random_keys = array_rand($all_emails, $random_size);
        
        // 如果使用者只選擇隨機寄送 1 筆，array_rand 回傳的會是單一數值，需轉為陣列處理
        if (!is_array($random_keys)) {
            $random_keys = [$random_keys];
        }
        
        // 建立隨機抽樣出的名單
        foreach ($random_keys as $key) {
            $target_list[] = $all_emails[$key];
        }
    }

    $total_targets = count($target_list);
    if ($total_targets === 0) {
        echo "<p>發送目標筆數為 0，請確認發送設定。</p>";
        echo "<a href='index.php'>返回</a>";
        exit;
    }

    echo "<p>任務啟動，本次預計發送總筆數：" . $total_targets . " 筆</p>";
    echo "<hr>";

    // 開始逐筆發送
    foreach ($target_list as $index => $email) {
        $current_index = $index + 1;
        
        // 計算並動態顯示目前發送進度百分比 (例如：5%)
        $progress = round(($current_index / $total_targets) * 100);
        $timestamp = date('H:i:s');

        // PHPMailer 基本實例化與發信
        $mail = new PHPMailer(true);
        $status = "";

        try {
            // === 請在此處根據您的郵件服務商填寫發信伺服器資訊 (以 Gmail 為例) ===
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';         // 請改為你的 SMTP 伺服器
            $mail->SMTPAuth   = true;
            $mail->Username   = 'lantengjing331@gmail.com';   // 請改為你的信箱
            $mail->Password   = 'zphy lvui otyn ixrf';      // 請改為你的密碼
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;


            $mail->setFrom('your_email@gmail.com', '系統批次發送');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $content;

            $mail->send();
            $status = "成功";
        } catch (Exception $e) {
            $status = "失敗 (原因: " . $mail->ErrorInfo . ")";
        }

        // 即時輸出該筆發送的進度、目標、時間與發送結果
        echo "<p><strong>[進度：" . $progress . "%]</strong> 正在發送至 " . htmlspecialchars($email) . " ... 時間：" . $timestamp . " 狀態：" . $status . "</p>";
        
        // 隨機設定寄送郵件間隔秒數（最後一筆發完不需再等待）
        if ($current_index < $total_targets) {
            $delay = rand($sec_min, $sec_max);
            echo "<p><em>--> 隨機郵件防護機制：等待 " . $delay . " 秒後寄送下一筆...</em></p>";
            ob_flush();
            flush();
            sleep($delay);
        } else {
            ob_flush();
            flush();
        }
    }

    echo "<hr>";
    echo "<h3>所有排程郵件已處理完畢！最終進度：100%</h3>";
    echo "<p><a href='index.php'>返回主畫面</a></p>";

} else {
    echo "<p>無效的請求方式。</p>";
    echo "<a href='index.php'>返回</a>";
}

echo "</body></html>";
ob_end_flush();
?>