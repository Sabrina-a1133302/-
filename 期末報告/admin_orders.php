<?php
// 1. 建立連線並設定編碼
$link = mysqli_connect("localhost", "root", "123456", "layermaster");

if (!$link) {
    die("資料庫連線失敗: " . mysqli_connect_error());
}
mysqli_set_charset($link, "utf8mb4");

// 處理狀態更新請求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($link, $_POST['status']);
    
    mysqli_query($link, "UPDATE orders SET status = '$status' WHERE id = $id");
    
    // 更新後重新導向，避免重新整理表單重複送出
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// 2. 獲取訂單資料
$query = "SELECT * FROM orders ORDER BY id DESC";
$result = mysqli_query($link, $query);

if (!$result) {
    die("查詢失敗: " . mysqli_error($link));
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>訂單管理系統</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 20px; background-color: #f9f9f9; }
        .back-btn { display: inline-block; padding: 10px 20px; background: #607d8b; color: white; text-decoration: none; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #eee; }
        select { padding: 5px; border-radius: 4px; }
        button { padding: 5px 10px; cursor: pointer; background-color: #4CAF50; color: white; border: none; border-radius: 4px; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>

<a href="admin_panel.php" class="back-btn">⬅ 返回後台管理系統</a>

<h2>訂單管理系統</h2>

<table>
    <thead>
        <tr><th>訂單 ID</th><th>Email</th><th>狀態</th><th>操作</th></tr>
    </thead>
    <tbody>
        <?php 
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) { 
                $current_status = $row['status'] ?? '待處理';
        ?>
            <tr>
                <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['email'] ?? '無'); ?></td>
                <td>
                    <form method="POST" style="display: flex; gap: 5px;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <select name="status">
                            <option value="待處理" <?php echo ($current_status == '待處理') ? 'selected' : ''; ?>>待處理</option>
                            <option value="已出貨" <?php echo ($current_status == '已出貨') ? 'selected' : ''; ?>>已出貨</option>
                            <option value="未出貨" <?php echo ($current_status == '未出貨') ? 'selected' : ''; ?>>未出貨</option>
                        </select>
                </td>
                <td>
                        <button type="submit" name="update_status">儲存</button>
                    </form>
                </td>
            </tr>
        <?php 
            } 
        } else {
            echo "<tr><td colspan='4' style='text-align:center;'>目前沒有訂單資料</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
<?php
mysqli_free_result($result);
mysqli_close($link);
?>