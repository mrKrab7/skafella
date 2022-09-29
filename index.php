<?php
require("inc/setup.php");

if(isset($_GET['exit'])&&$_GET['exit'])
{
	$cms->loginout(); 
}

if(isset($_POST['loginin']))
{
	$cms->loginin($_POST['login'],$_POST['password']);
}

if(!$cms->check_login())
{
	echo $cms->create_login_form();
}
else
{
if(!$cms->checklevel("40")) die("Доступ закрыт, обратитесь к администратору");
$data['title']="Система управления ZedCMS";	
$data['info']=$cms->login_status();	
$data['menu']=$cms->create_admin_menu();
$data['adding'] = '';	
if (isset($_GET['modul']))
	{
	if(!@include("modules/".$_GET['modul']."/adm_".$_GET['modul'].".php"))
	$data['navi']="Модуль не установлен обратитесь к администратору";
	else $data['navi']=$zed_navi;
	$data['content']=@$zed_content;
	$data['adding']=@$zed_adding;
	} 
else 
{
$data['navi']="";	
$data['content']="";
}
echo $cms->blockparse('admin_main',$data,2);

}
?>