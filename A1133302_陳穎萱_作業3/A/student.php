<?php
session_start();
if (isset($_SESSION['login'])){
    if($_SESSION['login']=='student'){
        echo "<h1>Welcome! student Login Success</h1></br>";
        echo "<a href='logout.php'>Logout</a>";

    }else{ 
        echo "<h1>非法進入網頁會看不到東西!2秒後回登入畫面</h1>";
        header("Refresh:3;url=login.php");

    }
}else{ 
    echo "<h1>非法進入網頁會看不到東西!2秒後回登入畫面</h1>";
    header("Refresh:3;url=login.php");

}

?>