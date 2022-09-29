<? 
//проверка статуса пользователя
if(!$cms->checklevel("60")) die("Доступ закрыт, обратитесь к администратору");

// показать пользователей
function show_users($rank,$active,$currid)
{
global $cms, $cstart,$cend;
if(isset($_POST['nic']) && $_POST['nic']!=""){$str_search=$_POST['nic'];}
else {$str_search='';}
$str="<form name=filtr action=\"?{$_SERVER['QUERY_STRING']}\" method=post><tr><td colspan=7><input type=\"text\" width=40 name='nic' value='$str_search'><input type=submit name=filt value='Поиск'></form></td></tr>
<tr><th>ИД</th><th>Логин</th><th>Фио</th><th>Ранг</th><th>Статус</th><th>Last Дата | Адрес</th><th>Действие</th></tr>";
$usdat['FUNC']="";
if(isset($_POST['nic']) && $_POST['nic']!="")
{
	$sql="select * from zed_users where LOGIN like '%$str_search%' order by LOGIN";
}
else { $sql="select * from zed_users order by LOGIN"; }

$result=$cms->query($sql);
$paramstring="?modul=users";
$str3=$cms->gen_sitepage($result,20,$paramstring);
$result=$cms->query($sql." limit $cstart,$cend");
while($row=$cms->fetch_object($result))
	{
	if($rank<$row->RANK) continue;
	$usdat['ID']=$row->ID;
	$usdat['LOGIN']=$row->LOGIN;
	$usdat['FIO']=$row->FIO;
	$usdat['RANK']=$cms->getrank($row->RANK);
	$usdat['LAST']=$row->LAST;
	$usdat['ACTIVE']="Активный";
	if(!$row->ACTIVE) $usdat['ACTIVE']="Заблокирован";
	if($rank>=80&&$active&&$rank>$row->RANK) 
	{
		if ($row->ACTIVE) 
			$usdat['FUNC'].="<a href=?modul=users&action=block_user&id=$row->ID><img src=templates/default/images/block1.png title=Заблокировать></a>&nbsp;";
		else $usdat['FUNC'].="<a href=?modul=users&action=unblock_user&id=$row->ID><img src=templates/default/images/apply2.png title=Разблокировать></a>&nbsp;";
	}
	else 
	{
		if ($row->ACTIVE)  $usdat['FUNC'].="<img src=templates/default/images/block1.png title='Нет доступа'>&nbsp;";
		else $usdat['FUNC'].="<img src=templates/default/images/apply2.png title='Нет доступа'>&nbsp;";
	}
	if($rank>$row->RANK||$currid==$row->ID)
	{ 
		$usdat['FUNC'].="<a href=?modul=users&action=change_status&id=$row->ID><img src=templates/default/images/bulb.png title='Изменить данные'></a>&nbsp;";
		if($currid==$row->ID)$usdat['FUNC'].="<img src=templates/default/images/del.png title='Нет доступа'>&nbsp;";
		else $usdat['FUNC'].="<a href=?modul=users&action=del_user&id=$row->ID><img src=templates/default/images/del.png title=Удалить></a>";
	}
	else $usdat['FUNC'].="<img src=templates/default/images/bulb.png title='Нет доступа'>&nbsp;<img src=templates/default/images/del.png title='Нет доступа'>";
	$str.=$cms->blockparse('showusers',$usdat,3);
	$usdat['FUNC']="";
	}
$usdat['table']=$str;
return $cms->blockparse('table',$usdat,3).$str3;
}

//разблокировать пользователя

function unblock_user($user_id)
{ global $cms;
$str="";
$result=$cms->query("UPDATE zed_users SET ACTIVE='1' where ID='$user_id'");
$result=$cms->query("UPDATE zed_users SET ACTIVE='1' where PARENT='$user_id'");
if($result) {$str="Разблокирован";} else {$str="Ошибка";}
return $str;
}
// заблокировать пользователя

function block_user($user_id)
{ global $cms;
$str="";
$result=$cms->query("UPDATE zed_users SET ACTIVE='0' where ID='$user_id'");
$result=$cms->query("UPDATE zed_users SET ACTIVE='0' where PARENT='$user_id'");

if($result) {$str="Заблокирован";} else {$str="Ошибка";}
return $str;
}

# удалить пользователя
function del_user($user_id)
{
global $cms;
$str="";
$pp=$cms->fetch_object($cms->query("select KOSHEL,PARENT FROM zed_users where ID='$user_id'"));
$res = $cms->query("select ID FROM zed_users where PARENT='$user_id'");
if($pp->PARENT==0)
{
	$result=$cms->query("DELETE FROM zed_billing_purse where purse='$pp->KOSHEL'");
	$result=$cms->query("DELETE FROM zed_billing_connected_servises where purse='$pp->KOSHEL'");
//	$result=$cms->query("DELETE FROM zed_billing_count where purse='$pp->KOSHEL'");
}
	
	$result=$cms->query("DELETE FROM zed_users where ID='$user_id'");
	if($cms->num_rows($res)>0) $result=$cms->query("DELETE FROM zed_users where PARENT='$user_id'");
if($result) {$str="Пользователь удален";} else {$str="Ошибка";}
return $str;
}


# показать панель операций над юзерами
function show_user_panel()
{
$str="<a href=?modul=users&action=add>Добавить пользователя</a>";
return $str;
}

function Agentstvo($id)
{
	global $cms;
	$mass = $cms->query("SELECT * FROM zed_massmedia");
	$rez = $cms->query("SELECT * FROM zed_users_media  where USERID=$id");
	if(!$cms->num_rows($rez)){
	 $cms->query("insert into zed_users_media (USERID) values ('$id')");
	 $rez = $cms->query("SELECT * FROM zed_users_media  where USERID=$id");}
	$str = "";
	$row = $cms->fetch_object($rez);
	if($row->AGENTID==0)	$str.= "<option value=0 selected>&nbsp;</option>";
	while($mas = $cms->fetch_object($mass))
	{
		if($mas->ID==$row->AGENTID){$str.="<option value=$mas->ID selected>$mas->NAME</option>";}
		else {$str.="<option value=$mas->ID>$mas->NAME</option>";}
	}
	return $str;
}

// вывод формы для изменения пользователя;
function form_change_user_status($user_id,$rang)
{global $cms;
$str=""; $str2="";
$user=$cms->fetch_object($cms->query("SELECT * FROM zed_users where ID=$user_id"));
switch($user->RANK)
	{
		case 100: $str.="<option value=100 selected>Супервайзер</option>"; break;
		case 90: $str.="<option value=90 selected>Администратор</option>"; break;
		case 80:$str.="<option value=80 selected>Модератор</option>" ; break;
		case 60:$str.="<option value=60 selected>Редактор</option>" ; break;
		case 40:$str.="<option value=40 selected>Контент менеджер</option>" ; break;
		case 20:$str.="<option value=20 selected>Пользователь</option>" ; break;
		case 10:$str.="<option value=10 selected>Гость</option>" ; break;
	}
if($user->RANK==40&& $rang>40) $str2 = Agentstvo($user->ID); 
if($user->RANK!=90 && $rang>90) $str.="<option value=90>Администратор</option>";
if($user->RANK!=80 && $rang>80) $str.="<option value=80>Модератор</option>" ; 
if($user->RANK!=60 && $rang>60) $str.="<option value=60 >Редактор</option>" ;
if($user->RANK!=40 && $rang>40) $str.="<option value=40 >Контент менеджер</option>" ; 
if($user->RANK!=20 && $rang>20) $str.="<option value=20 >Пользователь</option>" ; 
if($user->RANK!=10 && $rang>10) $str.="<option value=10 >Гость</option>" ;
if($user->TYPE==0)$strType = "<option value=0 selected>Физическое лицо</option><option value=1 >Юридическое лицо</option>";
else $strType = "<option value=1 selected>Юридическое лицо</option><option value=0 >Физическое лицо</option>";
$usdat['table']="<form name=st_form action=?modul=users&action=change_status&status=yes&id=$user_id method=post>
<tr bgcolor=#fafafa><td width=150 bgcolor=#f3f3f3>Логин </td><td>$user->LOGIN</td></tr>
<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>ФИО(Название)</td><td><input type=text name=fio size=50 maxlength=255 value=\"$user->FIO\" /></td></tr>";
if($user->TYPE==1)$usdat['table'].="<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>Организация</td><td><input type=text name=org size=50 value=\"$user->ORGANISATION\" /></td></tr>";
$usdat['table'].="<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>Е-Майл</td><td><input type=text name=Email size=50 maxlength=255 value=\"$user->EMAIL\" /></td></tr>
<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>Адрес</td><td><input type=text name=adress size=50 maxlength=255 value=\"$user->ADRESS\" /></td></tr>
<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>Сайт</td><td><input type=text name=site size=50 maxlength=255 value=\"$user->SITE\" /></td></tr>
<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>Телефон</td><td><input type=text name=phone size=50 maxlength=255 value=\"$user->PHONE\" /></td></tr>
<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>№ счета</td><td>$user->TYPE$user->KOSHEL</td></tr>
<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>Статус</td><td><select name=change_status style=\"width:150px;\">$str</select></td></tr>";
if($user->RANK==40&& $rang>40)$usdat['table'].="<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>Агентство новостей</td><td><select name=usersagent style=\"width:150px;\">$str2</select></td></tr>";
if($rang>80) $usdat['table'].="<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>Тип пользователя</td><td><select name=change_type style=\"width:150px;\">$strType</select></td></tr>";
$usdat['table'].="<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>Пароль</td><td><input type=password name=pass size=20 maxlength=50 value=\"\" /><span style=\"color:#ff0000\"> *Заполняется если нужно сменить пароль</span></td></tr>
<tr bgcolor=#fafafa><td bgcolor=#f3f3f3>Повтор пароля</td><td><input type=password name=confpass size=20 maxlength=50 value=\"\" /></td></tr>
<tr bgcolor=#fafafa><td colspan=2 align=center>О себе</td></tr>
<tr bgcolor=#fafafa><td colspan=2 align=center><textarea name=desc rows=7 cols=60>$user->DESCRIPTION</textarea></td></tr>
<tr bgcolor=#fafafa><td colspan=2 align=center><input type=submit name=change_sts value=Сохранить></td></tr>
</form>
";
$str=$cms->blockparse('table',$usdat,3);
return $str;
}

//изменение ранга пользователя
function change_user_status($user_id)
{
global  $cms ,$_POST;
$str = "";
$fio = $_POST['fio'];
$Email = $_POST['Email'];
$adres = $_POST['adress'];
$site = $_POST['site'];
$phone = $_POST['phone'];
if(isset($_POST['org'])){$org = $_POST['org'];}
else {$org ="";}
if(isset($_POST['usersagent']))
{
	$agent = $_POST['usersagent'];
	$cms->query("UPDATE zed_users_media SET AGENTID='$agent'");
}
$change_status = $_POST['change_status'];
$pass = $_POST['pass'];
$confpass = $_POST['confpass'];
$desc = $_POST['desc'];
$query = "UPDATE zed_users SET RANK='$change_status', ADRESS='$adres', FIO='$fio',ORGANISATION='$org', EMAIL='$Email', SITE='$site', PHONE='$phone', DESCRIPTION='$desc'";
if(isset($_POST['change_type'])) {
	$change_type = $_POST['change_type'];
	$query.=", TYPE='$change_type'";}
$query.=" where ID=$user_id";
$result=$cms->query($query);
if(!$result){$str="Ошибка";return $str;}
if($pass!="" && $pass==$confpass && $str=="")
{
	$pass = md5($pass);
	$result = $cms->query("UPDATE zed_users SET PASS='$pass' where ID=$user_id");
}
if($result) $str.="Изменения произведены успешно"; else $str.="Ошибка";
return $str;
}

// форма для добавления пользователей

function add_user_form($rang)
{
global $cms;
$strType = "<option value=0 selected>Физическое лицо</option><option value=1 >Юридическое лицо</option>";

$strRank = "";
if($rang>100) $strRank.="<option value=100>Супервайзер</option>";
if($rang>90) $strRank.="<option value=90>Администратор</option>";
if($rang>80) $strRank.="<option value=80>Модератор</option>" ; 
if($rang>60) $strRank.="<option value=60 >Редактор</option>" ;
if($rang>40) $strRank.="<option value=40 >Контент менеджер</option>" ; 
if($rang>20) $strRank.="<option value=20 >Пользователь</option>" ; 
if($rang>10) $strRank.="<option value=10 >Гость</option>" ;
$str="
<form action=?modul=users&action=add&status=yes method=post>
<tr bgcolor=#fafafa><td align=left width=30%>Логин *</td><td align=left><input type=text name=login size=25></td></tr>
<tr bgcolor=#fafafa><td align=left>Пароль *</td><td align=left><input type=password name=pass size=25></td></tr>
<tr bgcolor=#fafafa><td align=left>Повтор Пароля *</td><td align=left><input type=password name=repass size=25></td></tr>
<tr bgcolor=#fafafa><td align=left>ФИО *</td><td align=left><input type=text name=fio size=25></td></tr>
<tr bgcolor=#fafafa><td align=left>Организация </td><td align=left><input type=text name=org size=25></td></tr>
<tr bgcolor=#fafafa><td align=left>Е-майл</td><td align=left><input type=text name=email size=25></td></tr>
<tr bgcolor=#fafafa><td align=left>Адрес</td><td align=left><input type=text name=adress size=25></td></tr>
<tr bgcolor=#fafafa><td align=left>Сайт</td><td align=left><input type=text name=site size=25></td></tr>
<tr bgcolor=#fafafa><td align=left>Телефон</td><td align=left><input type=text name=phone size=25></td></tr>
<tr bgcolor=#fafafa><td align=left>Тип пользователя</td><td align=left><select name=change_type style=\"width:180px;\">$strType</select></td></tr>
<tr bgcolor=#fafafa><td align=left>Ранг пользователя</td><td align=left><select name=change_rank style=\"width:180px;\">$strRank</select></td></tr>
<tr bgcolor=#fafafa><td colspan=2 align=center>О себе</td></tr>
<tr bgcolor=#fafafa><td colspan=2 align=center><textarea name=desc rows=7 cols=60></textarea></td></tr>
<tr bgcolor=#fafafa><td colspan=2 align=center><input type=submit name=add_user value=Добавить></td></tr>
</form>
";
$usdat['table']=$str;
$str=$cms->blockparse('table',$usdat,3);
return $str;
}

// функция добавления пользователя
function CreateSchet($id)
{
	global $cms;
	$str = "";
	$num = strlen("".$id."");
	for($i=9-$num;$i>0;$i--)
		$str.="0";
	$str.=$id;
	$cms->query("UPDATE zed_users SET KOSHEL='$str' WHERE ID='$id'");
}  

function add_user()
{
global $cms,$_POST;
$login = $_POST['login'];
$pass = $_POST['pass'];
$repass = $_POST['repass'];
$fio = $_POST['fio'];
$email = $_POST['email'];
$adress = $_POST['adress'];
$site = $_POST['site'];
$phone = $_POST['phone'];
$change_type = $_POST['change_type'];
if($change_type==1){ $org = $_POST['org'];}
else {$org = "";}
$change_rank = $_POST['change_rank'];
$desc = $_POST['desc'];
	if($repass!=$pass||$pass=="") {$str=$cms->message("Пароль не совпадают"); return $str;}
	else {
		if (!$cms->check_if_exist($login,"zed_users","LOGIN") && !$cms->check_if_exist($email,"zed_users","EMAIL") && $login!=""){
			$pass = md5($pass);
			$result=$cms->query("insert into zed_users (LOGIN,PASS,FIO,ORGANISATION,RANK,ACTIVE,EMAIL,PHONE,ADRESS,SITE,TYPE,DESCRIPTION) values ('$login','$pass','$fio','$org','$change_rank','1','$email','$phone','$adress','$site','$change_type','$desc')");
			if($result){ 
				$str=$cms->message("Пользователь добавлен : $login"); 
				$row=$cms->insert_id();
				//$row=$cms->fetch_object($cms->query("select ID from users where LOGIN='$login'"));
				// длина кода 9 символов
				CreateSchet($row);}  
			else $str=$cms->message("Ошибка");
			return $str;}
		else { return $cms->message("Такой пользователь существует");}
		}
}

#================================================================================================= 
if(isset($_GET['action'])){
	switch($_GET['action'])
		{
		case "block_user": $zed_navi=block_user($_GET['id']);
			$zed_content=show_users($_SESSION['rank'],$_SESSION['active'],$_SESSION['userid']); 
		break;
		case "unblock_user": $zed_navi=unblock_user($_GET['id']); 
			$zed_content=show_users($_SESSION['rank'],$_SESSION['active'],$_SESSION['userid']); 
		break;
		case "del_user": $zed_navi=del_user($_GET['id']); 
			$zed_content=show_users($_SESSION['rank'],$_SESSION['active'],$_SESSION['userid']); 
		break;
		case "change_status": 
		if(isset($_GET['status'])&&$_GET['status']=="yes"){
			$zed_navi=change_user_status($_GET['id']);
			$zed_content=show_users($_SESSION['rank'],$_SESSION['active'],$_SESSION['userid']);} 
		else {
			$zed_content=form_change_user_status($_GET['id'],$_SESSION['rank']); 
			$zed_navi="Изменить информацию о пользователе";} 
		break;
		case "add":
		if (isset($_GET['status'])&&$_GET['status']=="yes")
			$zed_content=add_user($_POST['login'],$_POST['pass'],$_POST['repass'],$_POST['fio'],$_POST['email']);
		else $zed_content=add_user_form($_SESSION['rank']); 
		$zed_navi="Добавить пользователя"; 
		break;
		default:
		$zed_content=show_users($_SESSION['rank'],$_SESSION['active'],$_SESSION['userid']); 
		$zed_navi=show_user_panel();
		}
	}
else {
$zed_content=show_users($_SESSION['rank'],$_SESSION['active'],$_SESSION['userid']); 
$zed_navi=show_user_panel();
} ?>