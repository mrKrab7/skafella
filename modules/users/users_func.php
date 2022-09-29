<?
function get_input ($form,$nazv,$type,$name,$val,$class,$placeholder)
{
	global $cms;
	$data['NAZV'] =$nazv;
	$data['TYPE'] =$type;
	$data['NAME'] =$name;
	$data['VAL'] = $val;
	$data['CLASS'] = $class;
	$data['PLACEHOLDER'] = $placeholder;
	return $cms->blockparse($form,$data,1);
}
function get_div ($text,$class)
{
	global $cms;
	$data['div'] =$text;
	$data['CLASS'] = $class;
	return $cms->blockparse("div",$data,1);
}
function get_select_form ($form,$nazv,$type,$sel,$class)
{
	global $cms;$str_opt='';
	$select_row = $cms->query("select ID,NAME from zed_select where TYPE='$type' order by NAME");
	while ($select_res=$cms->fetch_object($select_row))
	{
		$dan['NAME'] =$select_res->NAME;
		$dan['VAL'] = $select_res->ID;
		if($sel==$select_res->ID){$dan['S'] = ' selected';}
		else $dan['S'] = '';
		$str_opt.=$cms->blockparse('select_options',$dan,1);
	}
	
	$data['OPTION'] =$str_opt;
	$data['NAZV'] =$nazv;
	$data['TYPE'] =$type;
	$data['CLASS'] = $class;
	return $cms->blockparse($form,$data,1);
}
function checkmail($str)
{
	$badchars = "[ ]+| |\+|=|[|]|{|}|`|\(|\)|,|;|:|!|<|>|%|\*|/|'|\"|~|\?|#|\\$|\\&|\\^|www[.]";
	return (eregi($badchars,$str));
}
function generate_password($number)
{
	$arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
	// Генерируем пароль
	$pass = "";
	for($i = 0; $i < $number; $i++)
	{
		// Вычисляем случайный индекс массива
		$index = rand(0, count($arr) - 1);
		$pass .= $arr[$index];
	}
	return $pass;
}
function Add_Subject ($name,$email,$headersubject,$message)
{
	global $cms;
	$config[admin_email] = "support@beautyboon.ru";
	$config[charset] = "utf-8";
	$config[recip_file] = "modules/feedback/recip.txt";
	$config[features] = "on";
	$config[select_recip] = 1;
	$config[tittle] = "СООБЩЕНИЕ ОТ АДМИНИСТРАЦИИ САЙТА BEAUTYBOON.RU";
	$config[description] = "сайт beautyboon.ru";

	$name = trim($name);
	$email = trim($email);
	$headersubject=$subject=trim($headersubject);
	$message = trim($message);

	$sendmessage = "<html><head><meta http-equiv=\"content-language\" content=\"ru\"><meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\"><meta name=\"author\" content=\"Barinov Alecsei (".$config[admin_email].")\"><meta name=\"robots\" content=\"all\"><meta name=\"description\" content=".$config[description]."><title>".$config[tittle]."</title></head><body><table border='0' cellpadding='2' cellspacing='0' width='100%'><tr><td><font face=\"Verdana\" size=2>".$message."</font></td></tr></table> </body></html>";
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "From: ".$headername."<".$email.">\n";
	$headers .= "Content-Type: text/html; charset=".$config[charset]."\n";
	$headers .= "X-Mailer: PHP/" . phpversion();

	$mail = "$name<$email>";
	set_time_limit(30);
	if (mail($mail, $headersubject, $sendmessage, $headers)){$resultat= 1;}
	else{$resultat= 0;}

	return $resultat;
}


function User_Login($id)
{
	global $cms;
	$er='';
	$data['classc']='class="users"';
	
	if(@$_SESSION['userid']!='')
	{
		$data['content'].=User_Profile_Private($_GET['x'],$_SESSION['type']);
		return $data['content'];
	}	
	else 
	{		
		$data['content'].= "<form  action='$cms->url' method='post'>";
		if(@$_POST['loginin'])
		{
			$data['content'].= get_div("Неверный Е-mail или пароль, или ваш аккаунт заблокирован.",'class="error"');
		}
		$data['content'].= get_input("form_i",'Е-mail','text','login','','class="blocks"');
		$data['content'].= get_input("form_i",'пароль','password','password','','class="blocks"');
		$data['content'].= get_input("form_i",'','submit','loginin','ВХОД','class="btn_default"');
		$data['content'].= get_div("<a class='link_forget' href='$cms->url/forget'>Забыли пароль?</a><a class='link_register' href='$cms->url/register'>Регистрация</a>",'class="ok"');
		$data['content'].= "</form>";
	}
	return $cms->blockparse("middle",$data,1);
}

function User_Profile_Private($id,$vid)
{
	global $cms,$zsite;

	echo '$_SESSION  <br>';
	print_r($_SESSION);
	echo '<br> $_POST  <br>';
	print_r($_POST);
	echo '<br> $_GET  <br>';
	print_r($_GET);/**/
	
	$data['content']='';
	
	$data['content'].= get_div("<a  href='$cms->url/pd'>Подать объявление</a><br><br><a href='$cms->url/lk'>Личные данные</a>",'class="ok"');
	/* $data['classc']='class="users"';
	$data['content']='';
	$er='';
	
	if(@$_SESSION['rank']>=60) {$zsite['navi'].="<a class='link_adm' href='/zed/'>Перейти в админку</a>";}
	
	$user = $_SESSION['userid'];
	$ruser = $cms->fetch_object($cms->query("select * from zed_users where ID='$user' "));
	$fio = $ruser->FIO;
	$phone =  $ruser->PHONE;		
	$gorod = $ruser->GOROD;
	$date = $ruser->DATE;
	$pol = $ruser->POL;
	$desc = $ruser->DESCRIPTION;
	$face = $ruser->FACE;
	echo "<br>$fio - $phone";
	if(isset($_POST['add_data_user'])) $er = User_Save_Data($user,$vid);
	if($er!="")
	{
		@$fio = $_POST['fio'];
		@$phone =  $_POST['phone'];		
		@$gorod = $_POST['gorod'];
		@$date = $_POST['date'];
		@$pol = $_POST['pol'];
		@$desc = $_POST['desc'];
	}
	elseif(isset($_POST['add_data_user']))
	{
		$data['content'].= get_div("Изменения сохранены успешно",'class="ok"');
	}
	$data['content'].="<form action='$cms->url/profiles_private' method='post' enctype='multipart/form-data'>";
	if($er!=""){$data['content'].= get_div($er,'class="error"');}
	if($vid==0)
	{
		if($face==1){$str_face='Вы зарегистрированы как Юридическое лицо';}
		elseif ($face==2){$str_face='Вы зарегистрированы как Индивидуальный Предпринематель';}
		elseif ($face==3){$str_face='Вы зарегистрированы как физическое лицо';}
		$data['content'].= get_div("$str_face",'class="ok"');
	}
	
	$data['content'].= get_input('form_i','ФИО','text','fio',"$fio",'class="blocks"');
	$data['content'].= get_input('form_i','Телефон','text','phone',"$phone",'class="blocks"');
	$data['content'].= get_input('form_i','Город','text','gorod',"$gorod",'class="blocks"');
	if($vid==1)
	{
		$data['content'].= get_input('form_i','Дата рождения','text','date',"$date",'class="blocks"');
	}
	$data['content'].= get_input('form_t','Коментарий','text','desc',"$desc",'class="blocks"');

	$data['content'].= get_div("Заполняется если нужно сменить пароль",'class="info"');
	$data['content'].= get_input('form_i','Пароль','password','pass','','class="blocks"');
	$data['content'].= get_input('form_i','Повтор пароля','password','repass','','class="blocks"');
	$data['content'].= get_input("form_i",'','submit','add_data_user','СОХРАНИТЬ','class="btn_default"');
	
	$data['content'].="</form>";
	*/
	return $cms->blockparse("middle",$data,1);
}

function UserProfileRole($id)
{
	global $cms,$zsite;

	$zsite['adding']='<link href="/zed/sitetpl/default/profile.css" rel="stylesheet">
<script src="https://enterprise.api-maps.yandex.ru/2.1/?apikey=bc39f70f-eb7e-44a6-b321-5b4f624d20b4&lang=ru_RU" type="text/javascript"></script>';
	$zsite['counter']='<script defer type="text/javascript" src="/zed/lib/profile.js"></script>';
	
	echo '$_SESSION  <br>';
	print_r($_SESSION);
	echo '<br> $_POST  <br>';
	print_r($_POST);
	echo '<br> $_GET  <br>';
	print_r($_GET);/**/

	$er='';
	if(isset($_POST['add_user']))
	{
		$fio = $_POST['fio'];
		if(isset($_POST['email']) && $_POST['email']!='') $sql.=", EMAIL={$_POST['email']}";
		if(isset($_POST['phone']) && $_POST['phone']!='') $sql.=", PHONE={$_POST['phone']}";
		$date = $_POST['date'];
		$sex = $_POST['sex'];
		$coords = $_POST['coords'];
		$adress = $_POST['adress'];
		$query = "UPDATE zed_users SET FIO='$fio', DATE='$date', SEX='$sex', ADRESS='$adress', COORDS='$coords' $sql  where ID='{$_SESSION['userid']}'";
		$result=$cms->query($query);
	}	
	if(isset($_POST['add_master']))
	{
	
	}
	if(isset($_POST['add_admin']))
	{
	
	}
	
	
	
	$ep = $cms->fetch_object($cms->query("select EMAIL,PHONE from zed_users where ID='{$_SESSION['userid']}'"));
	if($ep->EMAIL!='')
	{
		$dd['ep']= "
			<div class='mb-3'><label class='form-label'>Почта</label><span>$ep->EMAIL</span></div>
			<div><label class='form-label'>Телефон</label><input class='form-control' type='text' minlength='13' name='phone' /></div>";
	}
	if($ep->PHONE!='')
	{
		$dd['ep']= "
				
			<div class='mb-3'><label class='form-label'>Почта</label><input class='form-control' type='email' name='email' /></div>
			<div><label class='form-label'>Телефон</label><span>$ep->PHONE</span></div>";
	}
	$dd['form']=" action='$cms->url/profilerole' method='post' enctype='multipart/form-data'";
	$data['content'].=$cms->blockparse('profilerole',$dd,1);
	
	/*$data['content']='';

	$data['content'].= get_div("Страница ВЫБОР РОЛИ ПОЛЬЗОВАТЕЛЯ",'class="ok"');
	$data['content'].="<form action='$cms->url/profilerole' method='post' enctype='multipart/form-data'>";
	if($er!=''){$data['content'].= get_div($er,'class="error"');}
		
	$data['content'].= get_input('form_i','ФИО','text','fio',"",'class="blocks"');
	if($ep->EMAIL!='')
	{
		$data['content'].= get_input('form_i','Email','text','email',"$email",'class="blocks" disabled');
		$data['content'].= get_input('form_i','Телефон','text','phone',"$phone",'class="blocks"');
	}
	if($ep->PHONE!='')
	{
		$data['content'].= get_input('form_i','Email','text','email',"$email",'class="blocks"');
		$data['content'].= get_input('form_i','Телефон','text','phone',"$phone",'class="blocks" disabled');
	}
	
	$data['content'].= get_input('form_i','Дата рождения','text','date',"",'class="blocks"');
	
	$data['content'].= get_input("form_i",'','submit','add_user','ПЕРЕЙТИ НА СЕРВИС УДОБСТВО КРАСОТЫ','class="btn_default"');
	$data['content'].= get_input("form_i",'','submit','add_master','АКТИВИРОВАТЬ ПОЛЬЗОВАТЕЛЯ КАК ИСПОЛНИТЕЛЯ','class="btn_default"');
	$data['content'].= get_input("form_i",'','submit','add_admin','ЗАРЕГИСТРОВАТЬ ЮРИДЕЧЕСКОЕ ЛИЦО','class="btn_default"');
	
	$data['content'].="</form>";*/

	return $cms->blockparse("middle",$data,1);
}

function User_Register($id)
{
	global $cms,$zsite;
	$zsite['adding']='<link href="/zed/sitetpl/default/registr.css" rel="stylesheet">';
	$zsite['counter']='<script defer type="text/javascript" src="/zed/lib/registr.js"></script>';

	echo '$_SESSION  <br>';
	print_r($_SESSION);
	echo '<br> $_POST  <br>';
	print_r($_POST);
	//echo '<br> $_GET  <br>';
	//print_r($_GET);

	$er='';
	if(isset($_POST['reset'])) 
	{
		unset($_SESSION['shag']);
		$cms->query("DELETE FROM zed_users where LOGIN='{$_POST['login']}'");
	}
	if(isset($_POST['add_user']))
	{
		$activ=0;
		$pref = "0|0|0|0|0|0|0|0";
		$pass=generate_password(8);
		$repass = md5($pass);
		
		if($_POST['email']!='')
		{
			if($cms->check_if_exist($_POST['email'],"zed_users","EMAIL")>0){$er.= "Пользователь уже существует";}
			else 
			{
				$_SESSION['tip']='email';
				$_SESSION['login']=$login=$email=$_POST['email'];
				$result=$cms->query("insert into zed_users (RANK,PREFERENS,ACTIVE,PASS,LOGIN,EMAIL,KOD) values ('20','$pref','$activ','$repass','$login','$email','$pass')");
				
				$name='Пользователь';
				$headersubject='Регистрация пользователя';
				$message="Код подтверждения $pass <br><a href='http://test.itvaksa.ru/$cms->url/register?shagmail=$email'>перейти для ввода</a>";
				Add_Subject ($name,$email,$headersubject,$message);
				$_SESSION['shag']=1;
			}
		}
		elseif($_POST['tel']!='')
		{
			if($cms->check_if_exist($_POST['tel'],"zed_users","PHONE")>0){$er.= "Пользователь уже существует";}
			else
			{
				$login=$_POST['tel'];
				$login=str_replace(" ", '', $login);
				$login=str_replace("-", '', $login);
				$_SESSION['login']=$phone=$login;
				
				$result=$cms->query("insert into zed_users (RANK,PREFERENS,ACTIVE,PASS,LOGIN,EMAIL,PHONE,KOD) values ('20','$pref','$activ','$repass','$login','$phone','$pass')");
				$_SESSION['shag']=1;
			}
		}
		else 
		{
			
		}
	}
	if(isset($_POST['activ_user']))
	{
		$pass=$_POST['kod'];
		$cms->query("update zed_users set ACTIVE='1' where LOGIN='{$_SESSION['login']}'");
		$cms->loginin($_SESSION['login'],$pass);
		return UserProfileRole();
	}

/* ввод логина*/	
	if(!isset($_SESSION['shag']))
	{
		$dd['form']="action='$cms->url/register' name='add' method='post'";
		if($er!='')$dd['error']=get_div("<h2 class='headline text-danger'>$er",'class="error"');
		else $dd['error']='';
		$data['content'].=$cms->blockparse('register_et',$dd,1);
	}
//-- подтверждение --//
	if(isset($_SESSION['shag'])==1 || isset($_GET['shagmail']))
	{
		if(isset($_GET['shagmail']) || $_SESSION['tip']=='email')
		{
			if(isset($_GET['shagmail']))
			{
				$tEmail=$_SESSION['login']=$_GET['shagmail'];
			}
			else $tEmail=$_SESSION['login'];
			$dd['header']="Мы отправили письмо с кодом на почту <span data-login >$tEmail</span> , введите код из письма:";
			$dd['iLogin']=$tEmail;
		}
		else
		{
			$dd['header']="Мы отправили смс с кодом на номер +7 {$_SESSION['login']}, введите код из смс:";
			$dd['iLogin']=$_SESSION['login'];
		}
		$dd['form']="action='$cms->url/register' name='activ' method='post'";
		$data['content'].=$cms->blockparse('register_kod',$dd,1);
	}
	return $cms->blockparse("middle",$data,1);
}

function SaveLogin($id)
{
	global $cms;
	$str = date("d.m.Y");
	$str.=" | ".$_SERVER['SERVER_ADDR'];
	$cms->query("update zed_users set LAST='$str' where ID='$id'");
}

function User_Forget($id)
{
	global $cms;
	$data['classc']='class="users"';
	$er="";
	if(isset($_POST['user_forget']))
	{
		$email = $_POST['email'];
		$res = $cms->fetch_object($cms->query("select LOGIN,EMAIL from zed_users where EMAIL='$email'"));
		$login=$res->LOGIN;
		if($res->EMAIL==$email && $email!='')
		{
			if($res->EMAIL==$email){$er="";}			
			else{$er.= "Пользователь с таким почтовым ящиком не зарегистрирован";}
		}		
		if($er=="")
		{
			$pass = rand(100000,10000000);
			////////////////////////////
			$name = trim($login);
			$headersubject='Востановление пароля';
			$message = "Здравствуйте!<br><br>Ваши данные для доступа к сайту <a href='gornoski.ru'>www.gornoski.ru</a> были изменены.<br><br>Ваш временный пароль: $pass<br>При следующем входе на сайт <a href='gornoski.ru'>www.gornoski.ru</a> Вам будет предложено сменить пароль на постоянный. Рекомендуем сделать это, так как временный пароль не отвечает правилам безопасности.<br><br>С уважением,<br>Администрация сайта <a href='gornoski.ru'>www.gornoski.ru</a><br>e-mail:  info@gornoski.ru";
			$rez=Add_Subject($name,$email,$headersubject,$message);
			if ($rez==1)
			{
				$hash = md5($pass);
				$cms->query("update zed_users set `PASS`='$hash',`PASSEDIT`='1' where LOGIN='$login'");	
				$er="Вам на электронную почту отправлен новый пароль.<br>Рекомендуем его сменить, так как он не отвечает правилам безопасности.";
			}
			else{$er= "СООБЩЕНИЕ НЕ ОТПРАВЛЕНО!<br>Произошла непредвиденная ошибка при попытке отправить сообщение.<br>Пожалуйста попробуйте снова.";}					
		}
	}
	
	$data['content']="<form id='form' action='$cms->url/forget' method='post'>";
	if($er!=""){$data['content'].= get_div($er,'class="error"');}
	$data['content'].= get_input("form_i",'Напишите e-mail указанный при регистрации','text','login','','class="blocks_forget"');
	$data['content'].= get_input("form_i",'','submit','user_forget','Отправить новый пароль','class="btn_default"');

	$data['content'].= "<div class='clear'></div></form>";
	
	return $cms->blockparse("middle",$data,1);
}

function User_pd($id,$vid)
{
	global $cms,$zsite;
/*
	echo '$_SESSION  <br>';
	print_r($_SESSION);
	echo '<br> $_POST  <br>';
	print_r($_POST);
	echo '<br> $_GET  <br>';
	print_r($_GET);*/
	$data['content']='';
	
	$data['content'].= get_div("1",'class="ok"');
	return $cms->blockparse("middle",$data,1);
}

function User_lk($id,$vid)
{
	global $cms,$zsite;
/*
	echo '$_SESSION  <br>';
	print_r($_SESSION);
	echo '<br> $_POST  <br>';
	print_r($_POST);
	echo '<br> $_GET  <br>';
	print_r($_GET);*/
	$data['content']='';
	
	$data['content'].= get_div("2",'class="ok"');
	return $cms->blockparse("middle",$data,1);
}

?>