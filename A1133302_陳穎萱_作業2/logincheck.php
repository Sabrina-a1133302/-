<?php
$fID="Sabrina";
$fPWD="12345";
if (isset($_POST["uID"]) && isset($_POST["uPWD"])){
    $uID=$_POST["uID"];
    $uPWD=$_POST["uPWD"];

    if (($fID==$uID) && ($fPWD==$uPWD)){
        header("Location: A1133302_陳穎萱_作業1.php");
    }else{
        header("Location: fail.php");
    }
}

?>