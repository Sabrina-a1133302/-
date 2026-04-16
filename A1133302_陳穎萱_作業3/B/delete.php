<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    setcookie($id."[Name]", "", time() - 3600);
    setcookie($id."[Price]", "", time() - 3600);
    setcookie($id."[Quantity]", "", time() - 3600);
}

header("Location: shoppingcart.php");
?>
