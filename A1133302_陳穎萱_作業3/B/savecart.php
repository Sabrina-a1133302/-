<?php
session_start();

if (isset($_POST["Item"])) {

    $id = $_POST["Item"];
    $newQuantity = (int)$_POST["Quantity"];

    switch ($id) {
        case "S001":
            $name  = "10吋平板電腦";
            $price = 12000;
            break;

        case "S002":
            $name  = "15.6吋筆記型電腦";
            $price = 27000;
            break;

        case "S003":
            $name  = "iPhone智慧型手機";
            $price = 21000;
            break;
    }

    if (isset($_COOKIE[$id]["Quantity"])) {
        $quantity = $_COOKIE[$id]["Quantity"] + $newQuantity;
    } else {
        $quantity = $newQuantity;
    }

    setcookie($id."[Name]", $name, time()+3600);
    setcookie($id."[Price]", $price, time()+3600);
    setcookie($id."[Quantity]", $quantity, time()+3600);
}

header("Location: shoppingcart.php");
?>