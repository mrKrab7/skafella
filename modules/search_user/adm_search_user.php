<?
//проверка статуса пользователя
$cms->checklevel("100");	

function show_search($id)
{
	global $cms, $ZED;
	$sql="select * from zed_search order by MODUL";
	$result=$cms->query($sql);
	$newsdat['table']="<tr bgcolor=#f3f3f3><td>ID</td><td>МОДУЛЬ</td><td>ID модуля</td><td>Тип</td><td>Описание</td><td width=90>Действие</td></tr>";
	$str=$cms->blockparse($ZED['news_category'],$newsdat);
	while($row=$cms->fetch_object($result))
    {
	    $func="<a href=?modul=search&action=edit&id=$row->ID&parent=$id title=\"Редактировать\"><img src=templates/default/images/app_edit.png></a>
	     <a href=?modul=search&action=del&id=$row->ID&parent=$id title=\"Удалить\" onclick=\"return confirm('Удалить?')\"><img src=templates/default/images/del.png></a>";
	$newsdat['table'].="<tr bgcolor=#fafafa><td>$row->ID</td><td>$row->MODUL</td><td>$row->ID_CAT</td><td>$row->TYPE</td><td>$row->DESCRIPT</td><td>$func</td></tr>";
    }
	$str=$cms->blockparse('table',$newsdat,3);
	$str.="<center><a href=?modul=search&action=add&id=$id><b>добавить раздел поиска</b></a></center><br>";
	$str.="<center><b>Внимание !!!</b> добавлять только разделы для которых поисковик уже <font color=red><b>существует</b></font></center>";
	return $str;
}
function add_search($id)
{
	global $cms,$ZED;
	if(isset($_POST['s_add']))
	{
		$modul = $_POST['s_modul'];
		$cat_id = $_POST['s_c_id'];
		$descript = $_POST['descript'];
		$type = $_POST['type'];
		$rez = $cms->query("insert into zed_search (ID,MODUL,ID_CAT,TYPE,DESCRIPT) values('null','$modul','$cat_id','$type','$descript')");
		if($rez) { $str = $cms->message("Добавлено"); }
		else {$str = $cms->message("Ошибка");}
		return $str.show_search($id);
	}
	else
	{
		$newsdat['table']="<tr bgcolor=#fafafa><td colspan=2 align=center><b>Добавление поискового модуля</b></td></tr>
		<form action=?modul=search&action=add&id=$id method=post name=add_search_form>
		<tr bgcolor=#fafafa><td align=left width=5%>Модуль</td><td align=left><input type=text size=30 name=s_modul value=''></td></tr>
		<tr bgcolor=#fafafa><td align=left width=5%>ID&nbsp;раздела</td><td align=left><input type=text size=30 name=s_c_id  value=''></td></tr>
		<tr bgcolor=#fafafa><td align=left width=5%>Тип</td><td align=left><input type=text size=30 name=type  value='0'> (<font color=red> 0 </font>)-обычный, (<font color=red> 1 </font>)-приоритетный</td></tr>
		<tr bgcolor=#fafafa><td align=left width=5%>Описание</td><td align=left><textarea rows=4 cols=50 name='descript'></textarea></td></tr>
		<tr bgcolor=#fafafa><td colspan=2><input type=submit name=s_add value=\"Добавить\"></td></tr>";	
		$str=$cms->blockparse('table',$newsdat,3);
		return $str;
	}
}

function del_search($id)
{
	global $cms;
	$cms->query("delete from zed_search where ID='$id'");
	return $cms->message("Удалено").show_search($_GET['parent']);
}

function edit_search($id)
{
	global $cms,$ZED;
	if(isset($_POST['s_edit']))
	{
		$modul = $_POST['s_modul'];
		$cat_id = $_POST['s_c_id'];
		$descript = $_POST['descript'];
		$type = $_POST['type'];
		$rez = $cms->query("update zed_search set MODUL='$modul', ID_CAT='$cat_id',TYPE='$type', DESCRIPT='$descript' where ID='$id'");
		if($rez) { $str = $cms->message("Изменено"); }
		else {$str = $cms->message("Ошибка");}
		return $str.show_search($_GET['parent']);
	}
	else
	{
		$row=$cms->fetch_object($cms->query("select * from zed_search where ID='$id'"));
		$newsdat['table']="<tr bgcolor=#fafafa><td colspan=2 align=center><b>Редактирование поискового модуля</b></td></tr>
		<form action=?modul=search&action=edit&parent=".$_GET['parent']."&id=$id method=post name=edit_search_form>
		<tr bgcolor=#fafafa><td align=left width=5%>Модуль</td><td align=left><input type=text size=30 name=s_modul value=\"$row->MODUL\"></td></tr>
		<tr bgcolor=#fafafa><td align=left width=5%>ID&nbsp;раздела</td><td align=left><input type=text size=30 name=s_c_id  value=\"$row->ID_CAT\"></td></tr>
		<tr bgcolor=#fafafa><td align=left width=5%>Тип</td><td align=left><input type=text size=30 name=type  value=\"$row->TYPE\"> (<font color=red> 0 </font>)-обычный, (<font color=red> 1 </font>)-приоритетный</td></tr>
		<tr bgcolor=#fafafa><td align=left width=5%>Описание</td><td align=left><textarea rows=3 cols=50 name='descript'>$row->DESCRIPT</textarea></td></tr>
		<tr bgcolor=#fafafa><td colspan=2><input type=submit name=s_edit value=\"Редактировать\"></td></tr>";	
		$str=$cms->blockparse('table',$newsdat,3);
		return $str;
	}
}
#################################################################################################################
if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case "add":
		$zed_navi=$cms->navi($_GET['id'])."Добавление";
		$zed_content=add_search($_GET['id']);
		break;
		case "open":
		$zed_navi=$cms->navi($_GET['id']);
		$zed_content=show_search($_GET['id']);
		break;
		case "del":
		$zed_navi=$cms->navi($_GET['parent']);
		$zed_content=del_search($_GET['id']);
		break;
		case "edit":
		$zed_content=edit_search($_GET['id']);
		$zed_navi=$cms->navi($_GET['parent'])."Редактирование";
		break;
	}
}
?>