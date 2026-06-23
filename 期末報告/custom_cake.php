<?php
session_start();
$user_role = $_SESSION['role'] ?? 'guest';
$username = $_SESSION['username'] ?? '訪客';

// 企劃書 P3 規定：口味推薦系統大數據導航
$market_recommendations = [
    'matcha_strawberry' => '💡 結合後端 CSV 大數據：市場好評率達 94%！大眾認為「抹茶餅皮」搭配「草莓內餡」微苦酸甜比例最完美。',
    'earl_cream' => '💡 經典不敗配方：網評熱銷第一！「可可餅皮」與「經典鮮奶油」在社群的正面情緒評價最高。'
];
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>Layer Master - 客製化千層蛋糕引擎</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>🎂 Layer Master 客製化工作台</h1>
        <nav><p>當前操作工程師：<?php echo htmlspecialchars($username); ?> (權限：<?php echo htmlspecialchars($user_role); ?>) | <a href="index.php" style="color:#fff;">[回首頁導覽]</a></p></nav>
    </header>

    <main>
        <section class="recommendation-panel">
            <h2>💡 AI 口味推薦導航 (市場評價大數據整合)</h2>
            <ul>
                <li><?php echo $market_recommendations['matcha_strawberry']; ?></li>
                <li><?php echo $market_recommendations['earl_cream']; ?></li>
            </ul>
        </section>

        <section class="preview-section">
            <h2>🎂 1/4 幾何視覺預覽模型 (即時渲染)</h2>
            <fieldset>
                <legend>🎯 蛋糕即時幾何外觀投射</legend>
                <p>【蛋糕造型】：<span id="model_shape_view">⭕ 傳統經典圓形</span></p>
                <p>【結構層數】：<span id="model_layers_view">15 層</span></p>
                <p>【奇數層餅皮口味】：<span id="model_crust_odd_view">🥖 經典原味（米黃色）</span></p>
                <p>【偶數層餅皮口味】：<span id="model_crust_even_view">🥖 經典原味（米黃色）</span></p>
                <p>【核心夾層內餡】：<span id="model_filling_view">🥛 香醇特調鮮奶油</span></p>
                <p>【頂部裝飾外觀】：<span id="model_decoration_view">✨ 無裝飾</span></p>
                <p style="border-top: 1px solid #ccc; padding-top: 10px;"><strong>【目前預估金額】：<span id="model_price_view" style="color: #D95360;">NT$ 500</span></strong></p>
            </fieldset>
        </section>

        <form action="cart.php" method="POST">
            <input type="hidden" name="action" value="add_custom_cake">

            <section>
                <h2>1. 選擇結構層數 (身分權限控管)</h2>
                <label for="layers">請選擇總層數：</label>
                <select name="layers" id="layers" onchange="updateCakeModel()">
                    <option value="15">15 層 (入門級滑順口感)</option>
                    <option value="20">20 層 (經典平衡口感)</option>
                    <option value="25">25 層 (匠人豐富層次)</option>
                    <?php if ($user_role === 'vip' || $user_role === 'admin'): ?>
                        <option value="40">40 層 (★ VIP 尊榮尊爵限定規格)</option>
                    <?php endif; ?>
                </select>
                <?php if ($user_role === 'guest' || $user_role === 'user'): ?>
                    <p class="notice" style="color: #D95360;">* 提示：升級 VIP 身分即可解鎖「40層尊榮規格」極致烘焙！</p>
                <?php endif; ?>
            </section>

            <section>
                <h2>2. 餅皮雙重口味組合選擇</h2>
                <label for="crust_odd">奇數層 (1,3,5...) 餅皮口味：</label>
                <select name="crust_odd" id="crust_odd" onchange="updateCakeModel()">
                    <option value="original">經典原味餅皮</option>
                    <option value="matcha">小山園抹茶餅皮</option>
                    <option value="chocolate">法式可可餅皮</option>
                </select>
                <br><br>
                <label for="crust_even">偶數層 (2,4,6...) 餅皮口味：</label>
                <select name="crust_even" id="crust_even" onchange="updateCakeModel()">
                    <option value="original">經典原味餅皮</option>
                    <option value="matcha">小山園抹茶餅皮</option>
                    <option value="chocolate">法式可可餅皮</option>
                </select>
            </section>

            <section>
                <h2>3. 內餡夾層與外層裝飾選擇</h2>
                <label for="filling">內餡夾層風味：</label>
                <select name="filling" id="filling" onchange="updateCakeModel()">
                    <option value="cream">香醇特調鮮奶油 (基礎價)</option>
                    <option value="strawberry">大湖新鮮香草草莓內餡 (+NT$ 150)</option>
                    <option value="taro">大甲綿密手工芋泥內餡 (+NT$ 100)</option>
                </select>
                <br><br>
                <label for="decoration">頂部外層裝飾：</label>
                <select name="decoration" id="decoration" onchange="updateCakeModel()">
                    <option value="none">不加裝飾</option>
                    <option value="sea_salt_cheese">海鹽起司奶蓋淋醬 (+NT$ 80)</option>
                    <option value="mixed_fruits">綜合時令鮮水果擺件 (+NT$ 120)</option>
                </select>
            </section>

            <section>
                <h2>4. 蛋糕幾何造型選擇</h2>
                <label for="shape">蛋糕幾何外觀：</label>
                <select name="shape" id="shape" onchange="updateCakeModel()">
                    <option value="round">傳統經典圓形 (1.0x)</option>
                    <option value="heart">浪漫告白心形 (1.2x 造型手工費)</option>
                </select>
                <br><br>
                <button type="submit">加入購物車</button>
            </section>
        </form>
    </main>

    <script>
    function updateCakeModel() {
        var layers = document.getElementById("layers").value;
        var crust_odd = document.getElementById("crust_odd").value;
        var crust_even = document.getElementById("crust_even").value;
        var filling = document.getElementById("filling").value;
        var decoration = document.getElementById("decoration").value;
        var shape = document.getElementById("shape").value;

        document.getElementById("model_shape_view").innerText = shape === "heart" ? "❤️ 浪漫告白心形" : "⭕ 傳統經典圓形";
        document.getElementById("model_layers_view").innerText = layers + " 層千層結構";
        
        if (crust_odd === "matcha") document.getElementById("model_crust_odd_view").innerText = "🍵 抹茶風味（綠色餅皮面）";
        else if (crust_odd === "chocolate") document.getElementById("model_crust_odd_view").innerText = "🍫 可可風味（深褐色餅皮面）";
        else document.getElementById("model_crust_odd_view").innerText = "🥖 經典原味（米黃色餅皮面）";

        if (crust_even === "matcha") document.getElementById("model_crust_even_view").innerText = "🍵 抹茶風味（綠色餅皮面）";
        else if (crust_even === "chocolate") document.getElementById("model_crust_even_view").innerText = "🍫 可可風味（深褐色餅皮面）";
        else document.getElementById("model_crust_even_view").innerText = "🥖 經典原味（米黃色餅皮面）";

        if (filling === "strawberry") document.getElementById("model_filling_view").innerText = "🍓 草莓夾心（果香粉紅顆粒層）";
        else if (filling === "taro") document.getElementById("model_filling_view").innerText = "🍠 綿密芋泥（在地淡紫粉質層）";
        else document.getElementById("model_filling_view").innerText = "🥛 特調鮮奶油（乳白滑順柔和層）";

        if (decoration === "sea_salt_cheese") document.getElementById("model_decoration_view").innerText = "🧀 起司奶蓋（頂部流沙厚醬裝飾）";
        else if (decoration === "mixed_fruits") document.getElementById("model_decoration_view").innerText = "🍎 繽紛水果（季節鮮果切片堆疊）";
        else document.getElementById("model_decoration_view").innerText = "✨ 無裝飾（保留最上層手工微焦炙燒面）";

        // 即時價格計算邏輯
        var fillingPrices = { 'cream': 0, 'strawberry': 150, 'taro': 100 };
        var decorPrices = { 'none': 0, 'sea_salt_cheese': 80, 'mixed_fruits': 120 };
        var total = 500 + fillingPrices[filling] + decorPrices[decoration];
        if (shape === "heart") total *= 1.2;
        
        document.getElementById("model_price_view").innerText = "NT$ " + Math.round(total);
    }
    </script>
</body>
</html>