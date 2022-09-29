<?php
include('../../inc/dbcon.php');

if($_POST['search_t'])
{
	$mas_profi=array();$i=0;
	$res_profi=mysql_query("select * from zed_select where NAME like '%{$_POST['search_t']}%' order by NAME");
	while ($row_profi=mysql_fetch_object($res_profi))
	{
		$mas_profi[$i]=$row_profi->NAME;
		$i++;
	}
	$list=array("list"=>$mas_profi);
	echo json_encode($list);
}

if($_POST['search'])
{
	$mas_card=array();$i=0;
	//$row_profi=mysql_fetch_object(mysql_query("select ID from zed_select where NAME='{$_POST['search']}'"));
	
	$res_users=mysql_query("select USERID from zed_usluga where USLUGAID='{$_POST['selid']}' order by PRIORITET DESC");
	while ($row_users=mysql_fetch_object($res_users))
	{
		$rez_user=mysql_fetch_object(mysql_query("select FIO,COORDS from zed_users where ID='$row_users->USERID'"));
		$mas_card[$i]['id']=$row_users->USERID;
		$mas_card[$i]['title']=$rez_user->FIO;
		$mas_card[$i]['coords']=$rez_user->COORDS;
		$i++;
	}
	$list=array("list"=>$mas_card);
	echo json_encode($list);
}
if($_POST['gitID'])
{
	$mas_card=array();
	$rez = mysql_fetch_object(mysql_query("SELECT * FROM zed_users WHERE ID='{$_POST['gitID']}'"));

	$mas_card['title']=$rez->FIO;
	$mas_card['name']=$rez->FIO;
	$mas_card['url']="/profile/$rez->ID";
	$mas_card['time']='с 9 до 6';//$rez->NAME;
	//$mas_card['professiya']=$rez->NAME;
	$mas_card['ouser']=$rez->DESCRIPTION;
	$mas_card['adress']=$rez->ADRESS;

	$data=array("data"=>$mas_card);
	echo json_encode($data);
}
?>