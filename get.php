<?php

	include_once ("inc/ndl.class.php");
	include_once ("inc/zed.class.php");
	$cms = new zedcms;	
	include_once("inc/dbcon.php");
	if ( (isset($_GET["file"])) && !empty($_GET["file"]) )
	{
		$res = $cms->query("select * from zed_files where ID='{$_GET["file"]}'");
		$row = $cms->fetch_object($res);
		$ndl = new NDL(@$row->NAME, @$row->SNAME, @$row->DIR);
		$ndl->send();
	}
	else
	{
		echo "Unknown error";
	}

?>