<?
function get_input ($form,$nazv,$type,$name,$val,$class)
{
	global $cms;
	$data['NAZV'] =$nazv;
	$data['TYPE'] =$type;
	$data['NAME'] =$name;
	$data['VAL'] = $val;
	$data['CLASS'] = $class;
	return $cms->blockparse($form,$data,1);
}
function get_div ($text,$class)
{
	global $cms;
	$data['div'] =$text;
	$data['CLASS'] = $class;
	return $cms->blockparse("div",$data,1);
}

function feedback($id)
{
	global $cms,$zsite;
	$zsite['adding'].='<link rel="stylesheet" type="text/css" media="screen"  href="/zed/sitetpl/default/forms.css" />';
	$er='';
	$data['navi'].= "<div id='navi'><h1>$cms->name</h1></div>";
	$data['classc']='class="blocks"';
	$fio=$phone=$description=$emailo=$org=$dol=$tema="";
	$data['content']='';
	if($er!="")
	{
		@$fio = $_POST['fio'];
		@$emailo = $_POST['emailo'];
		@$phone =  $_POST['phone'];
		//@$org =  $_POST['org'];
		//@$dol =  $_POST['dol'];
		//@$tema =  $_POST['tema'];
		@$desc = $_POST['desc'];
	}
	elseif(isset($_POST['send']))
	{
	$fio = trim($_POST['fio']);
	$phone = $_POST['phone'];
	$emailo = trim($_POST['emailo']);
	//$org =  trim($_POST['org']);
	//$dol =  trim($_POST['dol']);
	//$tema =  trim($_POST['tema']);
	$description = trim($_POST['desc']);
	//if($fio=='' || $emailo=='' || $description) $resultat = "Не все поля заполнены.<br>";
	if($fio=='' ) $resultat.= "Как к Вам обращаться?<br>";	
	if($emailo=='') $resultat.= "Обязательно укажите Email.<br>";
	if($description=='') $resultat.= "Что Вы хотели спросить?";

	if($resultat=='')
	{
	///////////////////////////////////////
	$config[admin_email] = "info@comfort-nsk.com";
	$config[charset] = "windows-1251";
	$config[recip_file] = "modules/feedback/recip.txt";
	$config[features] = "on";
	$config[select_recip] = 1;
	$config[tittle] = "СООБЩЕНИЕ ОТ АДМИНИСТРАЦИИ САЙТА COMFORT-NSK.COM";
	$config[description] = "сайт comfort-nsk.com";

	$name = trim('admin');
	$email = trim('info@comfort-nsk.com');
	$headersubject=$subject = trim('Вам поступило сообщение');
	$message = trim('Вы можите просмотреть сообщение в административной понели <br /> для обработки сообщения можите перейти по ссылки <br /> http://comfort-nsk.com/zed/?modul=feedback&action=open&id=8');
	
	$sendmessage = "<html><head><meta http-equiv=\"content-language\" content=\"ru\"><meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\"><meta name=\"author\" content=\"Barinov Alecsei (".$config[admin_email].")\"><meta name=\"robots\" content=\"all\"><meta name=\"description\" content=".$config[description]."><title>".$config[tittle]."</title></head><table border='0' cellpadding='2' cellspacing='0' width='100%'>
<tr><td><font face=\"Verdana\" size=2>".$message."</font></td></tr></table> </body></html>";
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "From: ".$headername."<".$email.">\n";
	$headers .= "Content-Type: text/html; charset=".$config[charset]."\n";
	$headers .= "X-Mailer: PHP/" . phpversion();
	
	$adres = $cms->fetch_object($cms->query("select * from zed_select where TYPE='email'"));
	
	$mail = "Администратор<$adres->NAME>";
	set_time_limit(30);
	if (mail($mail, $headersubject, $sendmessage, $headers))
	{$resultat= "<b>СООБЩЕНИЕ ОТПРАВЛЕНО!</b><br />Спасибо, ".$name.", ваше сообщение успешно отправлено.";}
	else
	{$resultat.= "<b>СООБЩЕНИЕ НЕ ОТПРАВЛЕНО!</b><br />Произошла непредвиденная ошибка при попытке отправить сообщение. Пожалуйста попробуйте снова.";}
	
	$name=$headersubject=$subject=$message='';
	$email = $config[admin_email];
	///////////////////////////////////////
		$cms->query("insert into zed_feedback (ID,FIO,PHONE,EMAIL,DESCRIPTION) values ('null','$fio','$phone','$emailo','$description')");
		$fio = $phone = $emailo = $description ='';
		$data['content'].= get_div("Спасибо за обращение, мы обязательно с Вами свяжемся!",'class="info"');
		return $cms->blockparse("middle",$data,1);
	}
	}
	
	$data['content'].="<form action='{$_SERVER['REQUEST_URI']}' name='feedback' method='post' >";

	if($resultat!=""){$data['content'].= get_div($resultat,'class="error"');}
	
	$data['content'].= get_input('form_i','Е-mail','text','emailo',"$emailo",'class="blocks_c"');
	$data['content'].= get_input('form_i','ФИО','text','fio',"$fio",'class="blocks_c"');
	$data['content'].= get_input('form_i','Телефон','text','phone',"$phone",'class="blocks_c"');
	$data['content'].= get_input("form_t",'Сообщение','','desc',"$desc",'class="blocks_c"');
	$data['content'].= get_input("form_i",'','submit','send','ОТПРАВИТЬ','class="btn_default"');
	$data['content'].="</form>";


	return $cms->blockparse("middle",$data,1);
}

if(isset($_GET['x']))
{
	$cms->gen_content($_GET['x']);
	$zsite['navi']=$cms->sitenavi($_GET['x']);
	$zsite['middle']=feedback($_GET['x']);
}
?>