<?
function view_tpl($id)
{
	global $cms,$ZED;
	$row = $cms->fetch_object($cms->query("select * from zed_tpl where ID='$id'"));
	switch ($row->TYPE)
	{
		case 0 : $type = "гл. клиентской части"; break;
		case 1 : $type = "доп. клиентской части";break;
		case 2 : $type = "гл. админской части"; break;
		case 3 : $type = "доп. админской части"; break;
		case 4 : $type = "доп. любой части"; break;
	}
	$func="<a href=?modul=tpl&action=edit&id=$row->ID title=\"Редактировать\"><img src=templates/default/images/edit.png></a>
     <a href=?modul=tpl&action=del&id=$row->ID title=\"Удалить\" onclick=\"return confirm('Удалить?')\"><img src=templates/default/images/del.png></a>";
	$newsdat['table']="<tr><td>Название</td><td>$row->NAME</td></tr>";
	$newsdat['table'].="<tr><td>Тип</td><td>$type</td></tr>";
	$newsdat['table'].="<tr><td colspan=2>".htmlspecialchars(stripslashes($row->TPL))."</td></tr>";
	$newsdat['table'].="<tr><td colspan=2>Вид</td></tr>";
	$newsdat['table'].="<tr><td colspan=2>".stripslashes($row->TPL)."</td></tr>";
	$newsdat['table'].="<tr><td colspan=2>$func</td></tr>";
	$str=$cms->blockparse('table',$newsdat,3);
	return $str;
}

function show_tpl()
{
	global $cms;
	$rez = $cms->query("select ID,NAME,TYPE from zed_tpl order by TYPE, NAME");
	$str.="<center><a href=?modul=tpl&action=add><b>добавить шаблон</b></a></center>";
	$newsdat['table']="<tr><th>ID</th><th>Название</th><th>Тип</th><th width=90>Действие</th></tr>";
	while ($row = $cms->fetch_object($rez))
	{
		switch ($row->TYPE)
		{
			case 0 : $type = "гл. клиентской части"; break;
			case 1 : $type = "доп. клиентской части";break;
			case 2 : $type = "гл. админской части"; break;
			case 3 : $type = "доп. админской части"; break;
			case 4 : $type = "доп. любой части"; break;
		}
		
		$func="<a href=?modul=tpl&action=view&id=$row->ID title='Просмотр'><img src=templates/default/images/find.png></a>
         <a href=?modul=tpl&action=edit&id=$row->ID title=\"Редактировать\"><img src=templates/default/images/edit.png></a>
         <a href=?modul=tpl&action=del&id=$row->ID title=\"Удалить\" onclick=\"return confirm('Удалить?')\"><img src=templates/default/images/del.png></a>";
		$newsdat['table'].="<tr bgcolor=#fafafa><td>$row->ID</td><td>$row->NAME</td><td>$type</td><td>$func</td></tr>";
	}
	$str.=$cms->blockparse('table',$newsdat,3);
	$str.="<center><a href=?modul=tpl&action=add><b>добавить шаблон</b></a></center>";

	return $str;
}

function del_tpl($id)
{
	global $cms;
	if($cms->query("delete from zed_tpl where ID='$id'"))
	{
		$str = "<div align=center><h5># Удален успешно #</h5></div>";
	}
	else $str="<div align=center><i>Не могу удалить !!!</i></div>";
	return $str.show_tpl();
}

function get_select($sele='')
{
	$mm = array("гл. клиентской части","доп. клиентской части","гл. админской части","доп. админской части","доп. любой части");
	$str = "<select name='type'>";
	for($i=0;$i<count($mm);$i++)
	{
		$add = "";
		if($sele==$i) $add = "selected";
		$str.="<option $add value='$i'>$mm[$i]</option>";
	}
	$str.= "</select>";
	return $str;
}

function add_tpl()
{
	global $cms;
	$name = $_POST['name'];
	$type = $_POST['type'];
	$tpl = addslashes($_POST['tpl']);
	$cms->query("insert into zed_tpl (ID,NAME,TYPE,TPL) values(null,'$name','$type','$tpl')");
	return show_tpl();
}

function edit_tpl($id)
{
	global $cms;
	$name = $_POST['name'];
	$type = $_POST['type'];
	$tpl = addslashes($_POST['tpl']);
	$cms->query("update zed_tpl set NAME='$name', TYPE='$type', TPL='$tpl' where ID='$id'");
	return show_tpl();
}

function add_tpl_form()
{
	global $cms, $ZED;
	if(isset($_POST['go']))
	{
		return add_tpl();
	}
	$sel = get_select();
	$dd['table']="
<form action=?modul=tpl&action=add method=post name=cat_add_form>
<tr bgcolor=#fafafa><td align=left width=5%>Название</td><td align=left><input type=text size=20 name=name></td></tr>
<tr bgcolor=#fafafa><td nowrap=\"nowrap\"><b>Тип шаблона</b></td></td><td align=left>$sel</td></tr>
<tr bgcolor=#fafafa><td colspan=2><textarea rows=12 style=\"width:99%\" name=tpl></textarea></td></tr>
<tr bgcolor=#fafafa><td colspan=2><input type=submit name=go value=\"Добавить\"></td></tr>";
	$str=$cms->blockparse('table',$dd,3);
	return $str;
}

function edit_tpl_form($id)
{
	global $cms, $ZED;
	$row = $cms->fetch_object($cms->query("select * from zed_tpl where ID='$id'"));
	if(isset($_POST['go']))
	{
		return edit_tpl($id);
	}
	$sel = get_select($row->TYPE);
	$dd['table']="
<form action=?modul=tpl&action=edit&id=$id method=post name=cat_add_form>
<tr bgcolor=#fafafa><td align=left width=5%>Название</td><td align=left><input type=text size=20 name=name value='$row->NAME'></td></tr>
<tr bgcolor=#fafafa><td nowrap=\"nowrap\"><b>Тип шаблона</b></td></td><td align=left>$sel</td></tr>
<tr bgcolor=#fafafa><td colspan=2><textarea rows=30 style=\"width:99%\" name=tpl>".htmlspecialchars(stripcslashes($row->TPL))."</textarea></td></tr>
<tr bgcolor=#fafafa><td colspan=2><input type=submit name=go value='Сохранить'></td></tr>";
	$str=$cms->blockparse('table',$dd,3);
	return $str;
}


$zed_navi=$cms->navi(0)."<a href=?modul=tpl>Редактор шаблонов</a>";
if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case "add":
		$zed_navi.=" :: Добавление";
		$zed_content=add_tpl_form();
		break;
		case "view":
		$zed_content=view_tpl($_GET['id']);
		break;
		case "del":
		$zed_content=del_tpl($_GET['id']);
		break;
		case "edit":
		$zed_navi.=" :: Редактирование";
		$zed_content=edit_tpl_form($_GET['id']);
		break;
	}
}
else
{
	$zed_content=show_tpl();
}


?>