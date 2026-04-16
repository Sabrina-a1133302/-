<?php
session_start();

$sID="sabrina";
$sPwd="123";

$tID= "derrick";
$tPwd="123456";

$aID = "admin";
$aPwd = "333";

$uID=$_POST['uName'];
$uPwd=$_POST['uPWd'];

$date=strtotime("+5 seconds", time());

if (($uID==$sID) && ($uPwd==$sPwd)){
    $_SESSION['login'] = 'student';
    setcookie("uName",$uID,$date);
    header("Refresh:0;url=student.php");
    
}else if(($uID==$tID) && ($uPwd==$tPwd)){
    $_SESSION['login'] = 'teacher';
    setcookie("uName",$uID,$date);
    header("Refresh:0;url=teacher.php");
    
}else if(($uID==$aID) && ($uPwd==$aPwd)){
    $_SESSION['login'] = 'admin';
    setcookie("uName",$uID,$date);
    header("Refresh:0;url=admin.php");
    
}else{
    echo "Login Failed!";
    header("Refresh:1;url=login.php");
}

?>