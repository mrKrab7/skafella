<?
	
	$er='';
	//$fio=$phone=$description=$emailo=$org=$dol=$tema="";
	$str='';$str_comment='';
	if(isset($_POST['send']))
	{
		$fio = trim($_POST['fio']);
		$phone = $_POST['tel'];
		$emailo = trim($_POST['email']);
		$foo = $_POST['foo'];
		$description = trim($_POST['desc']);
		if($fio=='' ) $resultat.= "Как к Вам обращаться?<br>";
		if($emailo=='') $resultat.= "Обязательно укажите Email.<br>";
		if($description=='') $resultat.= "Что Вы хотели спросить?";
		
		if($resultat=='')
		{
			///////////////////////////////////////
			$config[admin_email] = "admin@comfort-nsk.com";
			$config[charset] = "windows-1251";
			$config[recip_file] = "modules/feedback/recip.txt";
			$config[features] = "on";
			$config[select_recip] = 1;
			$config[tittle] = "СООБЩЕНИЕ ОТ АДМИНИСТРАЦИИ САЙТА COMFORT-NSK.COM";
			$config[description] = "сайт comfort-nsk.com";

			$name = trim('admin');
			$email = trim('admin@comfort-nsk.com');
			$headersubject=$subject = trim('Вам поступило сообщение');
			$message = trim('Вы можите просмотреть сообщение в административной понели <br /> для обработки сообщения можите перейти по ссылки <br /> <a href="http://comfort-nsk.com/zed/?modul=feedback&action=open&id=8" >Перейти</a>');

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
			$str_comment="Спасибо за обращение, мы обязательно с Вами свяжемся!";
		}
		else $str_comment=$resultat;
	}
	if($str_comment=='') $str_comment="Здесь Вы можете задать вопрос.";
	$data['content']='<div class="row">
	<div class="col-xs-12 text-center margin_tb2">
	'.$str_comment.'
	</div>
	
	<form action="/contakt" name="feedback" method="post">
	<div class="col-xs-4">
		<div class="input-group form-group"> 
		<span class="input-group-addon" id="basic-addon11">@</span> 
		<input type="text" class="form-control" placeholder="Email" aria-describedby="basic-addon1" name="email"> 
		</div> 
	</div>
	<div class="col-xs-4">
		<div class="input-group form-group"> 
		<span class="input-group-addon" id="basic-addon11"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span> 
		<input type="text" class="form-control" placeholder="ФИО" aria-describedby="basic-addon1" name="fio"> 
		</div> 
	</div>
	
	<div class="col-xs-4">
		<div class="input-group form-group"> 
		<span class="input-group-addon" id="basic-addon11"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span></span> 
		<input type="text" class="form-control" placeholder="Телефон" aria-describedby="basic-addon1" name="tel"> 
		</div> 
	</div>
	
	<div class="col-xs-12">
		<div class="input-group"> 
		<span class="input-group-addon" id="basic-addon11"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></span> 
		<textarea name="desc" placeholder="Сообщение" class="form-control r1"></textarea> 
		</div> 
	</div>
	
	<div class="col-xs-12 button_feedback">
	<input type="submit" name="send" value="ОТПРАВИТЬ">
	</div>
	</form>
</div>';
?>