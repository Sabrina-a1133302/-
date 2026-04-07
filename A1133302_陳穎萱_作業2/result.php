<?php

$nName=$_POST["nName"];
$nAct=$_POST["nActivity"];
$nGender=$_POST["nGender"];
$nWill=$_POST["nWillingness"];
$nDate=$_POST["nDate"];
$nPhone=$_POST["nphoneNumber"];
$nPhone=$_POST["nphoneNumber"];
$nEmail=$_POST["nEmail"];
$nAge=$_POST["nAge"];
$comment=$_POST["comment"];

echo "你選擇的夏令營是:".$nAct."<br/>";
echo "你的名字是:".$nName."<br/>";

if($nGender=="m"){
	echo "你的性別是:男性<br/>";
}else{
	echo "你的性別是:女性<br/>";
}

foreach($nWill as $nI2){
    switch($nI2){
        case "yes":
            echo "願意收到通知";
            break;
        case "no";
            echo "不願意收到通知";
            break;
    }
    echo $nI2."<br/>";
}
echo "日期時間:".$nDate."<br/>";
echo "聯絡方式:"."<br/>";
echo "電話:".$nPhone."<br/>";
echo "信箱:".$nEmail."<br/>";
echo "電話:".$nPhone."<br/><br/>";
echo "你的年齡:".$nAge."<br/>";
echo "建議:".stripslashes(nl2br(strip_tags($comment)));

?>