<?php
require_once "../../lib/config.php";
require_once "../../lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");
// Получаем запрос.
$q = $_REQUEST['q'];
$str = "";
require_once "../../inc/dbcon.php";
$rez = mysql_query("SELECT * FROM zed_users WHERE LOGIN='$q'");
if(mysql_num_rows($rez)>0){$str = "<font color=red>Логин уже существует!!</font>"; $rr='1';}
else {$str = "<font color=green>Логин ок!!!</font>"; $rr='0';}
$_RESULT = array( "myq"     => $str, "res" => $rr); 
?>