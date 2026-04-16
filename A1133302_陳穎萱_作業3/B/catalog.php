<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>catalog</title>
</head>
<body>

<form method="post" action="savecart.php">
選擇商品:
<select name="Item">
    <option value="S001">10吋平板電腦 - $12000</option>
    <option value="S002">15.6吋筆記型電腦 - $27000</option>
    <option value="S003">iPhone智慧型手機 - $21000</option>
</select>

數量：
<input type="text" name="Quantity" size="3" value="1">
<input type="submit" value="訂購">
</form>

<hr color="grey"/>

<a href="catalog.php">商品目錄</a>
<br><br>
<a href="shoppingcart.php">檢視購物車</a>

</body>
</html>
