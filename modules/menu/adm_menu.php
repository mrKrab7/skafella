<?
#выводит пункты меню сис админа
function show_admin_menu()
{
global $cms;
$tplname="default";
$result=$cms->query("select * from zed_admin_menu order by ID");
$data['table']="<tr><th>ID</th><th>Пункт меню</th><th>Ссылка</th><th>Уровень доступа</th><th>Операции</th></tr>";
while($row=$cms->fetch_object($result))
{
$data['table'].="<tr bgcolor=#fafafa><td>$row->ID</td><td>$row->NAME</td><td>$row->LINK</td><td>$row->RANK</td><td><a href=?modul=menu&block=admin&action=edit&id=$row->ID><img src=\"templates/$tplname/images/edit.gif\" border=0 alt=\"Редактировать\"></a><a href=?modul=menu&block=admin&action=del&id=$row->ID><img src=\"templates/$tplname/images/del.gif\" border=0 alt=\"Удалить\"></a></td></tr>";
}
$data['table'].="<tr bgcolor=#fafafa><td colspan=5><a href=?modul=menu&block=admin&action=add>Добавить</a></td></tr>";
return $cms->blockparse('table',$data,3);
}

function add_admin_menu_form()
{
global $cms;
$data['table']="<form action=?modul=menu&block=admin&action=added method=post>";
$data['table'].="<tr bgcolor=#fafafa><td>Название</td><td>Ссылка</td><td>Уровень доступа</td><td>&nbsp;</td></tr>";
$data['table'].="<tr bgcolor=#fafafa><td><input type=text name=name></td><td><input type=text name=link></td><td><input type=text name=rank></td><td><input type=submit name=sub value='Добавить'></td></tr>";
$data['table'].="</form>";
$str=$cms->message("ДОБАВИТЬ НОВЫЙ ПУНКТ МЕНЮ");
$str.=$cms->blockparse('table',$data,3);
return $str;
}

function add_admin_menu()
{
global $cms, $_POST;
$name=$_POST['name'];
$link=$_POST['link'];
$rank=$_POST['rank'];
$cms->query("insert into zed_admin_menu (ID,NAME,LINK,RANK) values ('null','$name','$link','$rank')");
return show_admin_menu();
}


function edit_admin_menu_form($id)
{
global $cms;
$row=$cms->fetch_object($cms->query("select * from zed_admin_menu where ID='$id'"));
$data['table']="<form action=?modul=menu&block=admin&action=edited&id=$row->ID method=post>";
$data['table'].="<tr bgcolor=#fafafa><td>Название</td><td>Ссылка</td><td>Уровень доступа</td><td>&nbsp;</td></tr>";
$data['table'].="<tr bgcolor=#fafafa><td><input type=text name=name value='$row->NAME'></td><td><input type=text name=link value='$row->LINK'></td><td><input type=text name=rank value='$row->RANK'></td><td><input type=submit name=sub value='Изменить'></td></tr>";
$data['table'].="</form>";
$str=$cms->message("РЕДАКТИРОВАТЬ");
$str.=$cms->blockparse('table',$data,3);
return $str;
}

function del_admin_menu($id)
{
global $cms;
$cms->query("delete from zed_admin_menu where ID='$id'");
return show_admin_menu();
}


function edit_admin_menu($id)
{
global $cms;
$name=$_POST['name'];
$link=$_POST['link'];
$rank=$_POST['rank'];
$cms->query("update zed_admin_menu set NAME='$name', LINK='$link', RANK='$rank' where ID='$id'");
return show_admin_menu();
}


###########################################################################
if(isset($_GET['action']))
	{
		switch($_GET['action'])
			{
			case "add":
				$zed_content=add_admin_menu_form();
				$zed_navi="<a href=?modul=menu>Вывод списка меню</a>";
			break;	
			case "added":
				$zed_content=add_admin_menu();
				$zed_navi="<a href=?modul=menu>Вывод списка меню</a>";
			break;	
			case "edit":
				$zed_content=edit_admin_menu_form($_GET['id']);
				$zed_navi="<a href=?modul=menu>Вывод списка меню</a>";
			break;	
			case "edited":
				$zed_content=edit_admin_menu($_GET['id']);
				$zed_navi="<a href=?modul=menu>Вывод списка меню</a>";
			break;
			case "del":
				$zed_content=del_admin_menu($_GET['id']);
				$zed_navi="<a href=?modul=menu>Вывод списка меню</a>";
			break;
			}
	}
else 
{
$zed_navi="Вывод списка меню";
$zed_content=$cms->message("НАВИГАЦИЯ В СИСТЕМЕ АДМИНИСТРИРОВАНИЯ");
$zed_content.=show_admin_menu();
}
?>