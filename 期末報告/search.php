<?php
require_once 'db_config.php'; // 確保第一步的資料庫名稱對了！

$search_keyword = isset($_POST['keyword']) ? mysqli_real_escape_string($link, $_POST['keyword']) : '';

// 模糊搜尋餅皮或內餡
$query = "SELECT * FROM cake_models WHERE crust LIKE '%$search_keyword%' OR filling LIKE '%$search_keyword%'";
$result = mysqli_query($link, $query);

// 這樣就能順利吐出搜尋結果了！
?>