<?
if(!$cms->checklevel("60")) die("Доступ закрыт, обратитесь к администратору");
function view_feedback($id,$idv)
{
	global $cms;
	$sale = $cms->fetch_object($cms->query("select * from zed_feedback where ID=$idv"));

	$data['table'] = "<tr><td align='center' colspan=3>Вопрос от : <b>$sale->DATE</b> Клиента : <i>$sale->FIO</i> </td></tr>
	<tr><td align='left' colspan=3>E-mail: <b>$sale->EMAIL</b></td></tr>
	<tr><td align='left' colspan=3>Телефон: <b>$sale->PHONE</b> </td></tr>
	<tr><td align='left' colspan=3>Сообщение: <b>$sale->DESCRIPTION</b> </td></tr>";
	return $cms->blockparse("table",$data,3);
}

function show_feedback($id,$result='')
{
	global $cms;
	$str = "";
	$str_spisok = "";
	$url = "";
	$navigate = "";
	$func = "";
	//if(isset($_POST['rewrite'])) {$cms->query("update zed_select set NAME='{$_POST['name']}' where TYPE='email' "); $save= '<div align=center>Сохранено</div>';}
	if(isset($_GET['addread'])) $cms->query("update zed_feedback set STATUS=2 where ID={$_GET['addread']}");
	if(isset($_GET['addarchiv'])) $cms->query("update zed_feedback set STATUS=3 where ID={$_GET['addarchiv']}");
	if(isset($_GET['del'])) $cms->query("update zed_feedback set STATUS=0 where ID={$_GET['del']}");
	if(isset($_GET['view'])) return view_feedback($id,$_GET['view']);
	// -- email -- //
	if(isset($_POST['rewrite']))
	{
		$cms->query("update zed_select set NAME='{$_POST['name']}' where TYPE='email' ");
		$save="<div  id='message' >Сохранено</div>";
	}
	$em = $cms->fetch_object($cms->query("select NAME from zed_select where TYPE='email' "));
	// -- формирование статуса -- //
	if(isset($_GET['read'])) 
	{
		$url = "&read=0";
		$sql = "select * from zed_feedback where STATUS=2 order by DATE desc";
		$navigate = "<a href=/zed/?modul=feedback&action=open&id=$id>Новые</a> :: <b>Просмотренные</b> :: <a href=/zed/?modul=feedback&action=open&id=$id&archiv=0>В архив</a>";
	}
	elseif (isset($_GET['archiv'])) 
	{
		$url = "&archiv=0";
		$sql = "select * from zed_feedback where STATUS=3 order by DATE desc";
		$navigate = "<a href=/zed/?modul=feedback&action=open&id=$id>Новые</a> :: <b>В архив</b>";
	}
	else 
	{
		$sql = "select * from zed_feedback where STATUS=1 order by DATE desc";
		$navigate = "<b>Новые</b> :: <a href=/zed/?modul=feedback&action=open&id=$id&archiv=0>В архив</a>";
	}
	// -- формирование списка -- //
	$rez = $cms->query($sql);		
	while ($row = $cms->fetch_object($rez))
	{
		if ($url=="&read=0")
		{//<a href='?modul=feedback&action=open&id=$id{$url}&addread=$row->ID' title='Редактировать'><img src='templates/default/images/edit.png' /></a>
			$func="<a href='?modul=feedback&action=open&id=$id{$url}&view=$row->ID' title='Подробнее'><img src=templates/default/images/show.png></a>  <a href='?modul=feedback&action=open&id=$id{$url}&addarchiv=$row->ID' title='Добавить в архив'><img src='templates/default/images/archiv.png' /></a>";
		}
		elseif ($url=="&archiv=0")
		{//<a href='?modul=feedback&action=open&id=$id{$url}&addread=$row->ID' title='Редактировать'><img src='templates/default/images/edit.png' /></a>
			$func="<a href='?modul=feedback&action=open&id=$id{$url}&view=$row->ID' title='Подробнее'><img src=templates/default/images/show.png></a> ";
		}
		else
		{//<a href='?modul=feedback&action=open&id=$id{$url}&addread=$row->ID' title='Редактировать'><img src='templates/default/images/edit.png' /></a>
			$func="<a href='?modul=feedback&action=open&id=$id{$url}&view=$row->ID' title='Подробнее'><img src=templates/default/images/show.png></a> 
			<a href='?modul=feedback&action=open&id=$id{$url}&addarchiv=$row->ID' title='Добавить в архив'><img src='templates/default/images/archiv.png' /></a>";
		}
		$func.=" <a href='?modul=feedback&action=open&id=$id{$url}&del=$row->ID' title='удалить' onclick=\"return confirm('Удалить?')\"><img src='templates/default/images/del.png' /></a>";
	
		if($fl==1){$class_tr="class='table_tr_bg'"; $class_td="class='table_td_br_d'"; $fl=0;}
		else {$class_tr=""; $class_td="class='table_td_br_l'"; $fl=1;}
		$date=split(' ',$row->DATE);
		$str_spisok.="<tr $class_tr><td $class_td>$date[0]</td><td $class_td>$row->FIO</td><td $class_td>$row->EMAIL</td><td $class_td>$row->PHONE</td><td $class_td>$row->DESCRIPTION</td><td>$func</td></tr>";
	}
	// -- контент -- //
	$str.="<form action='?modul=feedback&action=open&id=$id' name='rewrite' method='post'>
	$save$result
	<div  id='button'>&nbsp;Емаил для получения вопросов
	<input type='text' name='name' value='$em->NAME' />
	<input type='submit' name='rewrite' value='Сохранить' class='submit save' ></div></form><br />";
	
	$str.="<div id='button' class='text_center'>$navigate</div>";
	$data['table']="<tr><th width='90'><div class='heder_bg_l'>Дата</div></th>
	<th width='150'><div class='heder_bg_c'>ФИО</div></th>
	<th width='150'><div class='heder_bg_c'>E-mail</div></th>
	<th width='150'><div class='heder_bg_c'>Телефон</div></th>
	<th widht='100%'><div class='heder_bg_c'>Комментарий</div></th>
	<th width='120'><div class='heder_bg_r'>Операции</div></th></tr>$str_spisok";
	$data['class']='tabl_def';
	$str.=$cms->blockparse('table2',$data,3);
	$zed_navi = $cms->navi($_GET['id']).$navi;
	return $str;
	
}
$zed_navi = $cms->navi($_GET['id']);
$zed_content=show_feedback($_GET['id']);

?>