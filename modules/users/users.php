<?
$zsite['adding'].='';
$zsite['counter'].='';
		
include "zed/modules/users/users_func.php";
$zsite['navi']=$cms->sitenavi($_GET['x']);
$rl = $cms->rubrics_level;
if(!$cms->check_login())
{
	if(@$_GET[$rl]=="forget")
	{
		$zsite['navi']="<div id='navi'><h1> <a href='$cms->url/forget'>Восстановление пароля</a> </h1></div>";
		$zsite['middle'].=User_Forget($_GET['x']);
	}
	elseif(@$_GET[$rl]=="register")
	{
		$zsite['navi']="<div id='navi'><h1> <a href='$cms->url/register'>Регистрация</a></h1></div>";
		$zsite['middle'].=User_Register($_GET['x']);
	}
	elseif(@$_GET[$rl]=="support")
	{
		$zsite['navi']="<div id='navi'><h1> <a href='$cms->url'>Личный кабинет</a> > <a href='$cms->url/support'>Техническая поддержка пользователя</a></h1></div>";
		$zsite['middle'].=User_Support($_GET['x']);
	}
	else
	{
		$zsite['navi']="<div id='navi'><h1> <a href='$cms->url'>Войти в личный кабинет</a></h1></div>";
		$zsite['middle'].=User_Login($_GET['x']);
	}
}
elseif(isset($_GET[$rl]) && $_GET[$rl]!='')
{
	switch($_GET[$rl])
	{
		case "profiles": $zsite['navi']="<div id='navi'><h1> <a href='$cms->url'>Личный кабинет</a> > <a href='$cms->url/profiles_private'>Настройка личных данных</a></h1></div>";
		$zsite['middle'].=User_Profile_Private($_GET['x'],$_SESSION['type']);
		break;
		case "profilerole": $zsite['navi']="<div id='navi'><h1> <a href='$cms->url'>Личный кабинет</a> > <a href='$cms->url/pd'>Подать объявление</a></h1></div>";
		$zsite['middle'].=UserProfileRole($_GET['x'],$_SESSION['type']);
		break;
		case "lk": $zsite['navi']="<div id='navi'><h1> <a href='$cms->url'>Личный кабинет</a> > <a href='$cms->url/lk'>Личные данные</a></h1></div>";
		$zsite['middle'].=User_lk($_GET['x'],$_SESSION['type']);
		break;
		default:
		$zsite['navi']="<div id='navi'><h1> <a href='$cms->url'>Личный кабинет</a></h1></div>";
		$zsite['middle'].=User_Profile_Private($_GET['x'],$_SESSION['type']);
	}
}
else
{
	$zsite['navi']="<div id='navi'><h1> <a href='$cms->url'>Личный кабинет</a></h1></div>";
	$zsite['middle'].=User_Login($_GET['x']);
}
?>