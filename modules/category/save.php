<?php
require_once "../../lib/config.php";
require_once "../../lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");
// Получаем запрос.
$cont = $_REQUEST['q'];
include_once("../../inc/dbcon.php");
include_once("../../inc/zed.class.php");
$cms = new zedcms;	
$data = time();
$rr = strip_tags($cont,'<img>');
$rr_mas = explode('<',$rr);
for($i=1;$i<count($rr_mas);$i++)
{
	$closetags = explode('>',$rr_mas[$i]);
	$img[] = "<".$closetags[0].">";
}
foreach($img as $value)
{
	$dd = explode('src=',$value);
	$ddd = explode(' ',$dd[1]);
	$src[] = trim($ddd[0],"\"\'");
}

foreach($src as $value)
{
	$name[] = end(explode('/',$value));
	//copy($value);
}
$ressss = $cms->query("select * from zed_spam where ID>1");
while($ssss = $cms->fetch_object($ressss))
{
	if($ssss->SPAM!='')
		unlink("../../Image/rss/".$ssss->SPAM);
}
for($i=0;$i<count($src);$i++)
{
	copy($src[$i],"../../Image/rss/".$name[$i]);
	$name_new[$i] = "zed/Image/rss/".$name[$i];
$cont = ereg_replace($src[$i],$name_new[$i],$cont);
}
for($i=0;$i<count($name);$i++)
{
	$iddd = $i+2;
	$mfile  = $name[$i];
	$cms->query("update zed_spam set SPAM='$mfile' where ID='$iddd'");
}
$cms->query("update zed_spam set SPAM='$cont', DATA='$data' where ID='1'");
$_RESULT = array(); 
?>
