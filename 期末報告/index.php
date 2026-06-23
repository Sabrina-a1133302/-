<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_config.php'; // 使用您剛更新的 PDO 連線檔

$username = $_SESSION['username'] ?? '訪客';
$user_role = $_SESSION['role'] ?? 'guest';

$search_keyword = $_GET['search'] ?? '';

// 使用 PDO 進行資料庫查詢
if (!empty($search_keyword)) {
    // 預備語句處理搜尋
    $stmt = $pdo->prepare("SELECT * FROM `cake_models` WHERE `name` LIKE ? OR `crust` LIKE ? ORDER BY `id` DESC LIMIT 6");
    $searchTerm = "%$search_keyword%";
    $stmt->execute([$searchTerm, $searchTerm]);
} else {
    // 預備語句處理一般查詢
    $stmt = $pdo->query("SELECT * FROM `cake_models` ORDER BY `id` DESC LIMIT 6");
}
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 更新後的表情判斷邏輯
function getCakeEmoji($name) {
    $name = mb_strtolower($name, 'UTF-8');
    if (strpos($name, '起司') !== false) return '🧀';
    if (strpos($name, '可可') !== false || strpos($name, '巧克力') !== false) return '🍫';
    if (strpos($name, '伯爵') !== false || strpos($name, '茶') !== false) return '🫖';
    if (strpos($name, '抹茶') !== false) return '🍵';
    if (strpos($name, '草莓') !== false) return '🍓';
    if (strpos($name, '香草') !== false) return '🍦';
    if (strpos($name, '芋') !== false) return '🍠';
    return '🎂';
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>Layer Master - 法式精品千層蛋糕</title>
    <style>
        .card-container { perspective: 1000px; width: 100%; height: 450px; }
        .card-inner { position: relative; width: 100%; height: 100%; transition: transform 0.8s; transform-style: preserve-3d; cursor: pointer; }
        .card-container:hover .card-inner { transform: rotateY(180deg); }
        .card-front, .card-back { position: absolute; width: 100%; height: 100%; backface-visibility: hidden; border-radius: 16px; border: 1px solid #f4ece8; background: #fff; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box; box-shadow: 0 8px 24px rgba(141,115,98,0.08); }
        .card-back { transform: rotateY(180deg); background: #fffaf7; border-color: #e7a984; text-align: center; }

        #backToTop {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: none;
            background-color: #cd6143;
            color: white;
            padding: 15px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: background 0.3s;
        }
        #backToTop:hover { background-color: #a04a32; }
    </style>
</head>
<body style="font-family: 'PingFang TC', 'Microsoft JhengHei', sans-serif; background-color: #fdfaf7; color: #4a3b32; margin: 0; padding: 0; background-image: radial-gradient(#f3ece6 1px, transparent 0); background-size: 24px 24px;">

    <header style="background: linear-gradient(135deg, #fceade 0%, #f3be9f 100%); padding: 50px 20px; box-shadow: 0 4px 20px rgba(74,59,50,0.08); text-align: center; border-bottom: 3px solid #e7a984;">
        <h1 style="margin: 0 0 15px 0; color: #4a3b32; font-size: 2.8rem; letter-spacing: 3px;">🥞 Layer Master 精品千層烘焙坊</h1>
        <nav style="margin-top: 25px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <a href="index.php" style="color: #4a3b32; text-decoration: none; font-weight: bold; padding: 10px 20px; background: rgba(255,255,255,0.5); border-radius: 20px;">首頁大廳</a>
            <a href="custom_cake.php" style="color: #4a3b32; text-decoration: none; font-weight: bold; padding: 10px 20px; background: #ffe8d6; border-radius: 20px;">🎨 客製化配置</a>
            <a href="cart.php" style="color: #4a3b32; text-decoration: none; font-weight: bold; padding: 10px 20px; background: rgba(255,255,255,0.5); border-radius: 20px;">🛒 我的購物車</a>
            
            <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['member', 'vip'])): ?>
                <a href="member_center.php" style="color: #4a3b32; text-decoration: none; font-weight: bold; padding: 10px 20px; background: rgba(255,255,255,0.5); border-radius: 20px;">👤 會員中心</a>
            <?php endif; ?>
            
            <?php if ($user_role === 'admin'): ?>
                <a href="admin_panel.php" style="color: #fff; text-decoration: none; font-weight: bold; padding: 10px 20px; background: #e63946; border-radius: 20px;">⚙️ 後台管理</a>
            <?php endif; ?>

            <?php if(isset($_SESSION['username'])): ?>
                <a href="logout.php" style="color: #e63946; text-decoration: none; font-weight: bold; padding: 10px 20px; border: 1px solid #ffa8a8; border-radius: 20px; background: #fff;">安全登出</a>
            <?php else: ?>
                <a href="login.php" style="color: #fff; text-decoration: none; font-weight: bold; padding: 10px 20px; background: #cd6143; border-radius: 20px;">🔑 登入系統</a>
            <?php endif; ?>
        </nav>
    </header>

    <main style="max-width: 1100px; margin: 50px auto; padding: 0 20px;">
        <section style="background: #fff; border-radius: 16px; padding: 35px; border: 1px solid #f4ece8; margin-bottom: 50px; text-align: center;">
            <form action="index.php" method="GET" style="display: flex; justify-content: center; gap: 10px;">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="搜尋產品..." style="padding: 14px 20px; border-radius: 30px; border: 1px solid #d3c2b5; width: 300px;">
                <button type="submit" style="background: #cd6143; color: #fff; border: none; padding: 14px 28px; border-radius: 30px; cursor: pointer;">搜尋</button>
            </form>
        </section>

        <?php if (count($results) > 0): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; margin-bottom: 60px;">
                <?php foreach ($results as $row): ?>
                <div class="card-container">
                    <div class="card-inner">
                        <div class="card-front">
                            <div style="font-size: 5rem; margin-bottom: 15px;"><?php echo getCakeEmoji($row['name']); ?></div>
                            <h4 style="margin: 0; font-size: 1.3rem;"><?php echo htmlspecialchars($row['name']); ?></h4>
                            <div style="font-size: 1.4rem; font-weight: bold; color: #cd6143; margin-top: 10px;">NT$ <?php echo number_format($row['price']); ?></div>
                        </div>
                        <div class="card-back">
                            <p style="padding: 0 15px; font-size: 1rem; line-height: 1.6;">
                                <strong>餅皮：</strong><?php echo htmlspecialchars($row['crust']); ?><br>
                                這款千層蛋糕層次豐富，口感細緻綿密，是層層堆疊出的極致美味。
                            </p>
                            <a href="cart.php?action=add&id=<?php echo $row['id']; ?>" style="text-decoration:none; background:#cd6143; color:#fff; padding:10px 20px; border-radius:20px; font-weight:bold;">🛒 加入購物車</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 100px 20px; background: #fff; border-radius: 16px; border: 1px dashed #d3c2b5; margin-bottom: 60px;">
                <div style="font-size: 4rem; margin-bottom: 20px;">🔍</div>
                <h2 style="color: #887466;">很抱歉，查無此資料</h2>
                <p style="color: #a89689;">請嘗試搜尋其他口味或回到首頁瀏覽全部商品。</p>
                <a href="index.php" style="display:inline-block; margin-top:20px; padding: 12px 30px; background: #cd6143; color: #fff; text-decoration: none; border-radius: 30px; font-weight: bold;">回首頁</a>
            </div>
        <?php endif; ?>

        <section style="background: #fff; border-radius: 16px; padding: 35px; box-shadow: 0 10px 30px rgba(141,115,98,0.06); border: 1px solid #f4ece8;">
            <h2 style="margin-top: 0; color: #cd6143; border-left: 6px solid #e7a984; padding-left: 15px; font-size: 1.5rem; margin-bottom: 15px;">✨ 經典名店熱門口味推薦</h2>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php
                $shops = [
                    ["name" => "1. LADY KELLY", "url" => "https://www.ladykelly.com.tw/"],
                    ["name" => "2. 時飴 Approprié", "url" => "https://www.approprie.co/"],
                    ["name" => "3. 紅葉蛋糕", "url" => "https://www.hongyeh-cake.com.tw/"],
                    ["name" => "4. 耶里 ellie", "url" => "https://www.ellie.com.tw/"],
                    ["name" => "5. 亞尼克 Yannick", "url" => "https://www.yannick.com.tw/"],
                    ["name" => "6. 狸小路", "url" => "https://www.tanukikoji.com.tw/"],
                    ["name" => "7. 唐緹 Tartine", "url" => "https://www.tartine.com.tw/"],
                    ["name" => "8. 艾波索 Aposo", "url" => "https://www.aposo2035.com.tw/"],
                    ["name" => "9. 聖保羅烘焙花園", "url" => "https://www.saintpaul.com.tw/"],
                    ["name" => "10. 向陽房 Shinehouse", "url" => "https://www.shinehouse.com.tw/"]
                ];
                foreach ($shops as $shop) {
                    echo '<div style="padding: 14px 20px; background: #faf6f2; border-radius: 10px; border-left: 4px solid #e7a984;">
                            <a href="'.$shop['url'].'" target="_blank" rel="noopener noreferrer" style="color: #cd6143; font-weight: bold; text-decoration: none;">'.$shop['name'].'</a>
                          </div>';
                }
                ?>
            </div>
        </section>
    </main>

    <div id="backToTop" onclick="scrollToTop()">↑ 回到頂部</div>

    <script>
        window.onscroll = function() {
            var btn = document.getElementById("backToTop");
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };
        function scrollToTop() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
    </script>
</body>
</html>