<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>垃圾郵件寄送系統</title>
</head>
<body>

    <h1>垃圾郵件寄送系統</h1>
    <hr>

    <h2>A. 建構資料庫 (新增名單)</h2>
    <form action="db_process.php" method="POST">
        <label>請輸入 Email 位址：</label>
        <input type="email" name="email_input" required>
        <button type="submit">新增至資料庫</button>
    </form>

    <br>
    <h3>資料庫目前名單</h3>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $database_file = 'database.txt';
            $emails = [];
            if (file_exists($database_file)) {
                $emails = file($database_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            }

            if (empty($emails)) {
                echo "<tr><td colspan='2'>目前資料庫內無資料</td></tr>";
            } else {
                foreach ($emails as $index => $email) {
                    echo "<tr>";
                    echo "<td>" . ($index + 1) . "</td>";
                    echo "<td>" . htmlspecialchars($email) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <hr>

    <h2>B. 寄信功能與隨機排程設定</h2>
    <form action="send_process.php" method="POST">
        
        <h3>基本郵件介面</h3>
        <label>郵件主旨：</label><br>
        <input type="text" name="mail_subject" size="50" required><br><br>
        
        <label>郵件內容：</label><br>
        <textarea name="mail_content" rows="5" cols="50" required></textarea><br>

        <h3>發送模式與時間間隔設定</h3>
        <label>發送範圍：</label><br>
        <input type="radio" name="send_mode" value="all" checked> 全部寄送 (目前共 <?php echo count($emails); ?> 筆)<br>
        
        <input type="radio" name="send_mode" value="random"> 隨機寄送幾筆？數量：
        <input type="number" name="random_size" min="1" max="<?php echo count($emails); ?>" value="1"><br><br>

        <label>設定寄送郵件間隔秒數：</label><br>
        最小：<input type="number" name="sec_min" min="0" value="1" required> 秒 ~ 
        最大：<input type="number" name="sec_max" min="0" value="3" required> 秒

        <br><br>
        <button type="submit">開始批次寄送郵件</button>
    </form>

</body>
</html>