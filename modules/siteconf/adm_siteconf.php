<?
#Выводит информацию о модулях;
function show_site_info($modul)
{
global $cms;
	$data['table']="
	<form action=?modul=siteconf&action=del method=post>
	<tr><th>МОДУЛЬ</th><th>ТИП</th><th>ЗНАЧЕНИЕ</th><th>&nbsp;</th><th>УД</th></tr>";
$mod="";
if ($modul!="ALL") {$mod="where MODUL='$modul' AND MODUL='ALL'";} else {$mod="where MODUL like '%'";}	
$result=$cms->query("select * from zed_siteinfo $mod"); 
while($row=mysql_fetch_object($result))
	{
	$data['table'].="<tr><td>$row->MODUL</td><td>$row->TYPE</td><td>".htmlspecialchars($row->VALUE)."</td>
		<td><a href=?modul=siteconf&action=edit&id=$row->ID>Редактировать</a></td>
		<td><input type=checkbox name=id[] value=$row->ID></td>
	</tr>";
	}
	$data['table'].="<tr><td colspan=5 align=right><input type=submit value=\"Удалить\"></td></tr></form>";
return $cms->blockparse('table',$data,3);
}

# добавить запись форма
function add_item_form()
{
global $cms;
	$data['table']="
	<form action=?modul=siteconf&action=additem method=post>
	<tr bgcolor=#f3f3f3>
		<td>МОДУЛЬ</td><td>ТИП</td></tr>
		<tr bgcolor=#fafafa>
		<td><input type=text name=mod></td><td><input type=text name=type></td>
		</tr>
		<tr bgcolor=#f3f3f3>
		<td colspan=2>ЗНАЧЕНИЕ</td>
		</tr><tr bgcolor=#fafafa>
		<td colspan=2><textarea name=value rows=5 style=\"width:99%\"></textarea></td>
	</tr>
	";
	$data['table'].="<tr bgcolor=#f3f3f3><td>&nbsp;</td><td><input type=submit value=\"Добавить\"></td></tr></form>";
return $cms->blockparse('table',$data,3);
}
# добавление
function add_item()
{
global $cms, $_POST;
$mod=$_POST['mod'];
$type=$_POST['type'];
$value=$_POST['value'];
$cms->query("insert into zed_siteinfo (ID,MODUL,TYPE,VALUE) values ('null','$mod','$type','$value')");
return show_site_info("ALL");
}

# удаление 
function del_item()
{
global $cms, $_POST;
$id=$_POST['id'];
foreach($id as $del)
{
$cms->query("delete from zed_siteinfo where ID='$del'");
}
return show_site_info("ALL");
}

# форма для редактирования записи
function edit_item_form($id)
{
global $cms;
	$row=mysql_fetch_object($cms->query("select * from zed_siteinfo where ID='$id'"));
	$data['table']="
	<form action=?modul=siteconf&action=edititem&id=$row->ID method=post>
	<tr bgcolor=#f3f3f3>
		<td>МОДУЛЬ</td><td>ТИП</td></tr>
		<tr bgcolor=#fafafa>
		<td><input type=text name=mod value='$row->MODUL'></td><td><input type=text name=type value='$row->TYPE'></td>
		</tr><tr bgcolor=#f3f3f3>
		<td colspan=2>ЗНАЧЕНИЕ</td>
		</tr><tr bgcolor=#fafafa>
		<td colspan=2><textarea name=value rows=20 style=\"width:99%\">$row->VALUE</textarea></td>
	</tr>
	";
	$data['table'].="<tr bgcolor=#f3f3f3><td>&nbsp;</td><td><input type=submit value=\"Изменить\"></td></tr></form>";
return $cms->blockparse('table',$data,3);
}

function edit_item($id)
{
global $cms, $_POST;
$mod=$_POST['mod'];
$type=$_POST['type'];
$value=$_POST['value'];
$cms->query("update zed_siteinfo set MODUL='$mod', TYPE='$type', VALUE='$value' where ID=$id");
return edit_item_form($id);
} 

###############################################################3
if (isset($_GET['action']))
	{
	 	switch ($_GET['action'])
	 		{
	 		case "add":
	 			$zed_navi="<a href=?modul=siteconf>Информация о сайте</a>";	
	 			$zed_content=add_item_form();
	 		break;
	 		case "additem":
	 			$zed_navi="<a href=?modul=siteconf>Информация о сайте</a> &raquo; <a href=?modul=siteconf&action=add>Добавить</a>";	
				$zed_content=add_item();
	 		break;	
	 		case "del":
	 			$zed_navi="<a href=?modul=siteconf>Информация о сайте</a> &raquo; <a href=?modul=siteconf&action=add>Добавить</a>";	
				$zed_content=del_item();
	 		break;
	 		case "edit":
	 			$zed_navi="<a href=?modul=siteconf>Информация о сайте</a> &raquo; <a href=?modul=siteconf&action=add>Добавить</a>";	
				$zed_content=edit_item_form($_GET['id']);
	 		break;	
	 		case "edititem":
	 			$zed_navi="<a href=?modul=siteconf>Информация о сайте</a> &raquo; <b>Отредактировано</b>";	
				$zed_content=edit_item($_GET['id']);
	 		break;	
	 		}
	}
else 
	{
	$zed_navi="<a href=?modul=siteconf>Информация о сайте</a> &raquo; <a href=?modul=siteconf&action=add>Добавить</a>";	
	$zed_content=show_site_info("ALL");
	}
?>