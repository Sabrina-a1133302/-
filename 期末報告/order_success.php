<?php
session_start();
// 確保使用者是完成結帳後才進入此頁面
// 您可以在 checkout.php 結帳完成後，將購物車清空並轉址到此頁
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>訂購成功 - Layer Master</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fff9f7; color: #7d6b62; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .container { max-width: 600px; text-align: center; background: #ffffff; padding: 60px; border-radius: 40px; border: 3px solid #ffe4e1; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        h1 { color: #e9967a; margin-bottom: 20px; }
        .icon { font-size: 5rem; margin-bottom: 20px; }
        p { font-size: 1.2rem; line-height: 1.6; color: #5d4a3e; }
        .nav-btn { display: inline-block; margin-top: 30px; padding: 15px 40px; background: #ff69b4; color: white; border-radius: 30px; text-decoration: none; font-weight: bold; font-size: 1.1rem; transition: background 0.3s; }
        .nav-btn:hover { background: #e9967a; }
    </style>
</head>
<body>

<div class="container">
    <div class="icon">✨</div>
    <h1>訂購成功！</h1>
    <p>感謝您選擇 Layer Master！<br>我們已經收到您的千層蛋糕訂單，師傅正為您精心準備中。</p>
    <p style="font-size: 0.9rem; color: #aaa; margin-top: 20px;">訂單確認信已寄送至您的信箱。</p>
    
    <a href="index.php" class="nav-btn">繼續逛逛 →</a>
</div>

</body>
</html>