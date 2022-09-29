<?php
$email= 'admin@comfort-nsk.com';
$fio = iconv("UTF-8","windows-1251",$_POST['name']);
$tel = iconv("UTF-8","windows-1251",$_POST['tel']);
$number = iconv("UTF-8","windows-1251",$_POST['number']);
$message=" Сообщение от $fio<br>Телефон  $tel <a href='http://comfort-nsk.com$number' target='_blank' >перейти</a>";
$config[admin_email] = 'admin@comfort-nsk.com';
$config[charset] = "windows-1251";
$config[tittle] = "сообщение от comfort-nsk.com";
$config[description] = "сайт comfort-nsk.com";
$headername=$name = trim('admin');	
$headersubject=$subject = trim('Запрос');	
$sendmessage = "<html><head><meta http-equiv=\"content-language\" content=\"ru\"><meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\"><meta name=\"author\" content=\"Barinov Alecsei (".$config[admin_email].")\"><meta name=\"robots\" content=\"all\"><meta name=\"description\" content=".$config[description]."><title>".$config[tittle]."</title></head><table border='0' cellpadding='2' cellspacing='0' width='100%'>
<tr><td><font face=\"Verdana\" size=2>".$message."</font></td></tr></table> </body></html>";
$headers  = "MIME-Version: 1.0\n";
$headers .= "From: ".$headername."<".$email.">\n";
$headers .= "Content-Type: text/html; charset=".$config[charset]."\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$mail = "Администратор<112@comfort-nsk.com>";
set_time_limit(30);
if (@mail($mail, $headersubject, $sendmessage, $headers)){echo "true";} 
else {echo "false";}
?>