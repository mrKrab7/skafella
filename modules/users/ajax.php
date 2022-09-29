<?php
include('../../inc/dbcon.php');
if($_POST['kod'])
{
	$rez =mysql_fetch_object(mysql_query("SELECT KOD FROM zed_users WHERE LOGIN='{$_POST['login']}'"));
	if($rez->KOD==$_POST['kod']) echo '1';
	else echo '0';
}
?>