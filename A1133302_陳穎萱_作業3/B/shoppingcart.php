<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>shoppingcart</title>
</head>
<body>

<table border="1">
<tr>
    <th>功能</th>
    <th>名稱</th>
    <th>價格</th>
    <th>數量</th>
</tr>

<?php
$flag  = true;
$total = 0;

foreach ($_COOKIE as $arr => $value) {

    if (is_array($value)) {

        $color = $flag ? "#FF99CC" : "#99FFCC";
        $flag = !$flag;

        echo "<tr bgcolor='$color'>";

        echo "<td><a href='delete.php?id=$arr'>刪除</a></td>";
        
        $name     = $value["Name"];
        $price    = $value["Price"];
        $quantity = $value["Quantity"];

        echo "<td>$name</td>";
        echo "<td align='right'>$price</td>";
        echo "<td align='right'>$quantity</td>";

        $total += $price * $quantity;

        echo "</tr>";
    }
}
?>

<tr>
    <td colspan="4" align="right">
        總金額 = NT$<?= $total ?>元
    </td>
</tr>
</table>

<hr color="grey"/>

<a href="catalog.php">商品目錄</a>
<br><br>
<a href="shoppingcart.php">檢視購物車</a>

</body>
</html>