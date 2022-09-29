<?
//include ("modules/FCKeditor/fckeditor.php");
function up($id,$table,$field='',$value='') {
	global $cms;
	$tpl=$cms->fetch_object($cms->query("select * from $table where ID='$id'"));
	if($field!='') $adding = "and `$field`='$value'";
	else $adding = "";
	$r2=$cms->query("select * from $table where ORD < $tpl->ORD and CATEGORY='$tpl->CATEGORY' $adding order by ORD desc");
	if($cms->num_rows($r2)>0) {
		$rn1=$cms->fetch_object($r2);
		$cms->query("UPDATE $table SET ORD='$rn1->ORD' where ID='$id'");
		$cms->query("UPDATE $table SET ORD='$tpl->ORD' where ID='$rn1->ID'");
	}
}
function down($id,$table,$field='',$value='') {
	global $cms;
	$tpl=$cms->fetch_object($cms->query("select * from $table where ID='$id'"));
	if($field!='') $adding = "and `$field`='$value'";
	else $adding = "";
	$r2=$cms->query("select * from $table where ORD > $tpl->ORD and CATEGORY='$tpl->CATEGORY' $adding order by ORD");
	if($cms->num_rows($r2)>0) {
		$rn1=$cms->fetch_object($r2);
		$cms->query("UPDATE $table SET ORD='$rn1->ORD' where ID='$id'");
		$cms->query("UPDATE $table SET ORD='$tpl->ORD' where ID='$rn1->ID'");
	}
}
function add_foto($id,$table) {

	global $cms;
	$name=$_POST['name'];
	$error='';
	if(isset($_FILES)) {
		foreach ($_FILES as $key=>$value) {
			if ($key=='' || $_FILES[$key]['error']==4)
			{
				/*    if(($_POST['w'.$key]!='' || $_POST['h'.$key]!='') && isset($_POST['id'.$key])) {
				$idim = $cms->fetch_object($cms->query("select * from zed_image where ID={$_POST['id'.$key]}"));
				$delfile = str_replace("/zed/","",$idim->PATH).$idim->NAME;
				$rez = $cms->img_resize($delfile, "photo/m".$idim->NAME, $_POST['w'.$key], $_POST['h'.$key]);

				}/**/
				continue;
			}
			//if ($_FILES[$key]['size']>8400000) $error.="Файл слишком большой!($key)<br />";
			//if ($_FILES[$key]['size']<512) $error.="Файл слишком маленький!($key)<br />";
			if ($_FILES[$key]['type']!="image/x-png" && $_FILES[$key]['type']!="image/pjpeg" && $_FILES[$key]['type']!="image/gif" && $_FILES[$key]['type']!="image/jpeg" && $_FILES[$key]['type']!="image/png" )
			$error.="Это не изображение, либо формат не поддерживается($key)<br />";

			switch($_FILES[$key]['type']) {
				case "image/pjpeg":
				case "image/jpeg":
				$ext="jpg";
				break;
				case "image/gif":
				$ext="gif";
				break;
				case "image/png":
				case "image/x-png":
				$ext="png";
				break;
				default:
				$ext="";
				break;
			}
			if($error=='') {
				$path='/zed/photo/';
				$fl=0;
				if(isset($_POST["id$key"]) && $_POST["id$key"]!='')
				{
					$idimage = $cms->query("select * from zed_image where ID={$_POST["id$key"]}");
					if($cms->num_rows($idimage)>0)
					{
						$idim = $cms->fetch_object();
						$delfile = str_replace("/zed/","",$idim->PATH).$idim->NAME;
						if(is_file($delfile)) unlink($delfile);
						$delfile = str_replace("/zed/","",$idim->PATH)."m".$idim->NAME;
						if(is_file($delfile)) unlink($delfile);
						$delfile = str_replace("/zed/","",$idim->PATH)."l".$idim->NAME;
						if(is_file($delfile)) unlink($delfile);
						$file_name="{$_POST["id$key"]}_$id.$ext";
						$fl=1;
						$idimg = $idim->ID;
					}
				}
				$size = getimagesize($_FILES[$key]['tmp_name']);
				if(!$fl)
				{
					$ord=1;
					$res =$cms->query("select ORD from zed_image where `CATEGORY`=$id and `TABLE`='$table' order by ORD desc limit 1");
					if($cms->num_rows($res)>0) {
						$row = $cms->fetch_object($res);
						$ord = 1+ $row->ORD;
					}
					$cms->query("insert into zed_image(`ID`,`NAME`, `CATEGORY`, `PATH`, W, H, ORD, `TABLE`)
					values (null,'','$id','$path','{$size[0]}','{$size[1]}',$ord,'$table')");
					$idimg = $cms->insert_id();
					$file_name="{$idimg}_$id.$ext";
				}

				if (move_uploaded_file($_FILES[$key]['tmp_name'],"photo/$file_name")) {
					//$width = $_POST['w'.$key];
					//$height = $_POST['h'.$key];
					if($table=='zed_photo_category')
					{$rez = $cms->img_resize("photo/$file_name", "photo/l".$file_name, 350,270);}
					else 
					{$rez = $cms->img_resize("photo/$file_name", "photo/l".$file_name, 800,'',800);}
					$rez = $cms->img_resize("photo/$file_name", "photo/m".$file_name, 350,270);
					//$rez = img_resize2("photo/$file_name", "photo/m".$file_name, "photo/l".$file_name, $width, 400);
					if($rez!='') $error.=$rez;
					else {unlink("photo/".$file_name);
						$result=$cms->query("update zed_image set `NAME` = '$file_name', W='{$size[0]}', H='{$size[1]}' where ID='$idimg'");
					}
				}
				else $error.="ошибка загрузки, нет доступа к папке($key)<br />";
			}
		}
	}
	return $error;
}
function del_foto($id_cat,$cat,$id)
{
	global $cms, $zed_navi;
	$row=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY=$id and `TABLE`='zed_photo'"));
	unlink("{$row->PATH}/m".$row->NAME);
	unlink("{$row->PATH}/l".$row->NAME);
	unlink("{$row->PATH}/".$row->NAME);

	$cms->query("delete from zed_photo where ID='$id'");
	$cms->query("delete from zed_image where `CATEGORY`=$id and `TABLE`='zed_photo'");

	$zed_navi=$cms->navi($id_cat);
	return show_images($id_cat,$cat);
}
function get_select_all($id='')
{
	global $cms;
	$res = $cms->query("select ID,NAME from zed_category where TYPE='page' order by NAME");
	$str="";
	while($row=$cms->fetch_object($res))
	{
		$sele = "";
		if($row->ID==$id) $sele = "selected";
		$str.="<option $sele value='$row->ID'>$row->NAME</option>";
	}
	return $str;
}
function del_cat($id_cat,$cat,$id)
{
	global $cms, $zed_navi;
	$row=$cms->fetch_object($cms->query("select ID from zed_photo where CATEGORY=$id"));

	if($row->ID)
	{
		$result = "Рубрика содержит фото !! Удалите их !!";
	}
	else 
	{
		$cms->query("delete from zed_photo_category where ID='$id'");
		$result = "Рубрика успешно удалена";
	}
	return view_images($id_cat,$result);
}

function show_images($id,$cat,$status='')
{
	global $cms, $zed_navi, $cstart, $cend;
	$str="";
	$i=1;
	if($status!='') $str.="<div  id='message' >$status</div>";
	
	$data['table']="<tr>";
	$result=$cms->query("select * from zed_photo where CATEGORY='$cat' order by ORD");
	$prstr="/zed/?modul=images&action=view&id=$id&cat=$cat";
	$pages=$cms->gen_page($result,200,$prstr);
	$result=$cms->query("select * from zed_photo where CATEGORY='$cat' order by ORD limit $cstart,$cend");
	while($row=$cms->fetch_object($result))
	{
		$dd['name']='';//$row->NAME;
		$dd['funk']="<div class='circle'>$i</div><a href=/zed/?modul=images&action=del&id=$id&cat=$cat&delet=$row->ID onclick=\"return confirm('Удалить?')\" title='Удалить'><img src='templates/default/images/del.png'></a> 
<a href=/zed/?modul=images&action=edit&id=$id&cat=$cat&edited=$row->ID title='Редактировать' ><img src='templates/default/images/edit.png' /></a>
<a href='?modul=images&action=up&id=$id&cat=$cat&edited=$row->ID' title='Поднять'><img src='/zed/templates/default/images/up.png' /></a>
<a href='?modul=images&action=down&id=$id&cat=$cat&edited=$row->ID' title='Опустить'><img src='/zed/templates/default/images/down.png' /></a>";
		$rowww=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$row->ID' and `TABLE`='zed_photo' "));
		if($rowww->W>$rowww->H){$o='width=100';}
		else $o='height=100';
		$dd['foto']="<img src='{$rowww->PATH}m$rowww->NAME' $o >";
		$data['table'].=$cms->blockparse('fotoother',$dd,3);
		$i++;
	}
	$data['table'].="</td></tr>";
	
	$data['class']='tabl_def border';
$str.="<div id='button'><a class='url_button_add' href='/zed/?modul=images&action=add&id=$id&cat=$cat'>Добавить</a></div>";
	$str.=$cms->blockparse('table2',$data,3);
	$str.=$pages;
	
	$r_n=$cms->fetch_object($cms->query("select NAME from zed_photo_category where ID='$cat'"));
	$zed_navi=$cms->navi($id)." > $r_n->NAME";
	return $str;
}

function form_add_cat($id)
{
	global $cms;
	$result!='';
	$name = $enname = "";	
	if(isset($_POST['addcat']))
	{
		$name = trim($_POST['catname']);
		$enname = urlencode(trim($_POST['encatname']));
		$price = $_POST['tovar'];
		if($name=='' || $enname=='') $result = "Не все поля заполнены";
		if($cms->num_rows($cms->query("select ID from zed_photo_category where CATEGORY='$id' and EN_NAME='$enname'"))>0)
		$result.= "<br>Такая ссылка уже есть!!!";
		if($result=='')
		{
			$ord = 1;
			$por = $cms->query("select ID,ORD from zed_photo_category where CATEGORY='$id' order by ORD desc limit 1");
			while($rpor = $cms->fetch_object($por))	$ord = $rpor->ORD+1;
			$cms->query("insert into zed_photo_category (ID,NAME,EN_NAME,CATEGORY,LEVEL,PRICE,ORD)
			values ('null','$name','$enname','$id','null','$price','$ord')");
			$_POST= array();
			$result = 'Рубрика добавлена!';
		}
	}
	if($result!='') $str = "<div  id='message' >$result</div>";
	
	$str.="<form action='?modul=images&action=open&id=$id' name='addcat' method='post'>
	<div id='button'>	
	<div class='bloc_div text_center'><b>Добавление рубрики</b></div>	
	<div class='float_l'><input type='submit' name='addcat' value='Добавить' class='submit add'>&nbsp;&nbsp;&nbsp;</div>
	<div class='float_l w40p'>Название <input type='text' name='catname' class='w70p' value='' /></div>
	<div class='float_l w40p '>Ссылка <input type='text' name='encatname' class='w70p' value='' /></div>
	<div class='bloc_div help_message min_size'><b><i>ВАЖНО !!!</i></b> в <i>Ссылке</i> допустимы буквы латинского алфавита (в нижнем регистре [a,b,c, и т.д.]), цифры и символы '-'(минус) и '_'(подчеркивание). остальные символы включая пробел - <i>недопустимы</i>. Дополнительное условие  - внутри рубрики ссылки <i>не должны</i> быть одинаковыми!!!</div>
	</div></form>";
	
	return $str;
}

function form_edit_cat($id)
{
	global $cms, $zed_navi;
	$result = '';
	$str = '';
	if(isset($_POST['editcat']))
	{
		$name = trim($_POST['catname']);
		$enname = urlencode(trim($_POST['encatname']));
		if($name=='' || $enname=='') $result = "Не все поля заполнены";
		if($cms->num_rows($cms->query("select ID from zed_photo_category where EN_NAME='$enname' and ID<>'{$_GET['edit']}'"))>0)
		$result.= "<br />Такая ссылка уже есть!!";
		if($result=='')
		{
			$cms->query("update zed_photo_category set NAME='$name', EN_NAME='$enname', CATEGORY='$id' where ID='{$_GET['edit']}'");
			$result = "Сохранено!";
			return show_images($id,$_GET['cat'],$result);
		}
	}
	else
	{
		$row = $cms->fetch_object($cms->query("select * from zed_photo_category where ID='{$_GET['edit']}'"));
		$name = $row->NAME;
		$enname = $row->EN_NAME;
	}
	if($result!='') $str = "<div  id='message' >$result</div>";
	
	$str.="<form action='?modul=images&action=open&id=$id&cat={$_GET['cat']}&edit={$_GET['edit']}' name='editcat' method='post'>
	<div id='button'><input type='submit' name='editcat' value='Сохранить' class='submit save'></div>";
	
	$data['table']="
	<tr><td width='150' ><b>Название</b></td><td><input type='text' name='catname' class='w90p' value='$name' /></td></tr>
	<tr><td><b>Ссылка</b></td><td><input type='text' name='encatname' class='w90p' value='$enname' /></td></tr>
	<tr><td colspan=2 class='help_message min_size'><b><i>ВАЖНО !!!</i></b> в <i>Ссылке</i> допустимы буквы латинского алфавита (в нижнем регистре [a,b,c, и т.д.]), цифры и символы '-'(минус) и '_'(подчеркивание). остальные символы включая пробел - <i>недопустимы</i>. Дополнительное условие  - внутри рубрики ссылки <i>не должны</i> быть одинаковыми!!!</td></tr>
	</div></form>";
	$data['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$data,3);
	$zed_navi=$cms->navi($id)." > Редактирование";
	return $str;
}

function view_images($id,$status='')
{
	global $cms, $zed_navi;
	if(isset($_GET['edit'])) return form_edit_cat($id);
	$str='';
	if($status!='') $str.="<div  id='message' >$status</div>";
	$str.=form_add_cat($id);
	
	$res = $cms->query("select * from zed_photo_category where CATEGORY='$id' order by ORD desc");
	if($cms->num_rows($res)==0) $str.="<div id='button' class='text_center'>Нет рубрик</div>";
	else 
	{
		$data['table']="<tr><th width=50%><div class='heder_bg_l help_message'>Рубрика <i>[количество фото]</i></div></th><th width=35%><div class='heder_bg_c'>Ссылка</div></th><th width=150 ><div class='heder_bg_r'>Действия</div></th></tr>";
		while ($row = $cms->fetch_object($res))
		{
			$kol = '';
			$func = "
<a href='?modul=images&action=view&id=$id&cat=$row->ID' title='Редактировать'><img src='/zed/templates/default/images/edit.png' /></a>	
<a href='?modul=images&action=open&id=$id&cat=$row->ID&edit=$row->ID' title='Настрока'><img src='/zed/templates/default/images/settings.png' /></a> 
<a href='?modul=images&action=delet&id=$id&cat=$row->ID&delet=$row->ID' title='Удалить' onclick=\"return confirm('Удалить?')\"><img src='/zed/templates/default/images/del.png' /></a>
<a href='?modul=images&action=downc&id=$id&cat=$row->ID' title='Поднять'><img src='/zed/templates/default/images/up.png' /></a>
<a href='?modul=images&action=upc&id=$id&cat=$row->ID' title='Опустить'><img src='/zed/templates/default/images/down.png' /></a>";
			$num=$cms->num_rows($cms->query("select ID from zed_photo where CATEGORY='$row->ID'"));
			$kol = " <b><i>[$num]</i></b>";
			
			if($fl==1){$class_tr="class='table_tr_bg'"; $class_td="class='table_td_br_d'"; $fl=0;}
			else {$class_tr=""; $class_td="class='table_td_br_l'"; $fl=1;}
			
			$data['table'].="<tr $class_tr><td $class_td><a href='?modul=images&action=view&id=$id&cat=$row->ID'>$row->NAME$kol</a></td><td $class_td>$row->EN_NAME</td><td>$func</td></tr>";
		}
		$data['class']='tabl_def';
		$str.= $cms->blockparse('table2',$data,3);
	}
	$zed_navi=$cms->navi($id);
	return $str;
}

################################################################################
##################            Добавление в базу               ##################
################################################################################
function add_images_form($id,$cat)
{
	global $cms, $zed_navi;$str='';
	if(isset($_POST['added']))
	{
		$name = trim($_POST['name']);
		$row=$cms->query("select ORD from zed_photo where CATEGORY='$cat' order by ORD DESC limit 1");
		if($cms->num_rows($row)>0)
		{
			$res=$cms->fetch_object($row);
			$ord=$res->ORD+1;
		}
		else $ord=1;
		$result=$cms->query("insert into zed_photo(`ID`,`NAME`,`CATEGORY`,`FILE`,`ORD`) values (null,'$name','$cat','','$ord')");
		add_foto($cms->insert_id(),'zed_photo');
		return show_images($id,$cat,'Фото добавлено');
	}
	$str.="<form action='?modul=images&action=add&id=$id&cat=$cat' name='addform' method='post' enctype='multipart/form-data'>";
	$str.="<div id='button'><input type='submit' name='added' value='Добавить' class='submit add' ></div>";
	$data['table']= "
	<tr><td width=100><b>Название</b></td><td><input type=text name='name' class='w90p'></td></tr>
	<tr><td class='help' align='left'  colspan=2>Изображение не должно превышать 2МБ.</td></tr>
	<tr><td><b>Фото</b></td><td><input name='foto' type='file' /></td></tr>
	</form>";
	$data['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$data,3);
	$r_n=$cms->fetch_object($cms->query("select NAME from zed_photo_category where ID='$cat'"));
	$zed_navi=$cms->navi($id)." > $r_n->NAME > Добавление";
	return $str;
}
################################################################################
##################         Редактирование объекта             ##################
################################################################################
function edit_images_form($id_cat,$cat,$id)
{

	global $cms,$zed_navi;$str='';
	
	if(isset($_POST['edited']))
	{
		$name = trim($_POST['name']);
		$cms->query("update zed_photo set NAME='$name' where ID='$id'");

		add_foto($id,'zed_photo');
		return show_images($id_cat,$cat,'Изменения приняты');
	}
	$row=$cms->fetch_object($cms->query("select * from zed_photo where ID='$id'"));
	$name=$row->NAME;

	$rowww=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$row->ID' and `TABLE`='zed_photo' "));
	$foto="<img src='{$rowww->PATH}m$rowww->NAME' width=100 />";
	$str.="<form action='?modul=images&action=edit&id=$id_cat&cat=$cat&edited=$id' name='editedform' method='post' enctype='multipart/form-data'>";
	$str.="<div id='button'><input type='submit' name='edited' value='Сохранить' class='submit save' ></div>";
	$data['table']= "
	<tr><td width=100><b>Название</b></td><td><input type='text' name='name' class='w90p' value='$name'></td></tr>
	<tr><td class='help' align='left'  colspan=2><b><i>Выбор файла только если нужно поменять избражение</i></b></td></tr>
	<tr><td><b>Фото</b><br />$foto</td><td><input name='foto' type='file'>
	<input type='hidden' name='idfoto' value='$rowww->ID'><br />  </td></tr>
	</form>";
	$data['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$data,3);
	$r_n=$cms->fetch_object($cms->query("select NAME from zed_photo_category where ID='$cat'"));
	$zed_navi=$cms->navi($id_cat)." > $r_n->NAME > Редактирование";
	return $str;
}
#####################3
if (isset($_GET['action']))
{
	$zed_adding='<script type="text/javascript" src="/zed/modules/ckeditor/ckeditor.js"></script>';
	switch($_GET['action'])
	{
		case "add":
		$zed_content=add_images_form($_GET['id'],$_GET['cat']);
		break;
		case "edit":
		$zed_content=edit_images_form($_GET['id'],$_GET['cat'],$_GET['edited']);
		break;
		case "delet":
		$zed_content=del_cat($_GET['id'],$_GET['cat'],$_GET['delet']);
		break;
		case "del":
		$zed_content=del_foto($_GET['id'],$_GET['cat'],$_GET['delet']);
		break;
		case "up":
		up($_GET['edited'],'zed_photo');
		$zed_content=show_images($_GET['id'],$_GET['cat']);
		break;
		case "down":
		down($_GET['edited'],'zed_photo');
		$zed_content=show_images($_GET['id'],$_GET['cat']);
		break;
		case "upc":
		up($_GET['cat'],'zed_photo_category');
		$zed_content=view_images($_GET['id']);
		break;
		case "downc":
		down($_GET['cat'],'zed_photo_category');
		$zed_content=view_images($_GET['id']);
		break;
		case "open":
		$zed_content=view_images($_GET['id']);
		break;
		case "view":
		$zed_content=show_images($_GET['id'],$_GET['cat']);
		break;
	}
}


?>


