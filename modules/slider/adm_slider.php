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
					$rez = $cms->img_resize_s("photo/$file_name", "photo/l".$file_name, 871, 350);
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
function del_foto($id_cat,$id)
{
	global $cms, $zed_navi;
	$row=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$id' and `TABLE`='zed_photo'"));
	unlink("{$row->PATH}/l".$row->NAME);

	$cms->query("delete from zed_photo where ID='$id'");
	$cms->query("delete from zed_image where `CATEGORY`=$id and `TABLE`='zed_photo'");

	$zed_navi=$cms->navi($id_cat);
	return show_images($id_cat);
}

function show_images($id,$status='')
{
	global $cms, $zed_navi, $cstart, $cend;
	$str="";
	$i=1;
	if($status!='') $str.="<div  id='message' >$status</div>";
	
	$data['table']="<tr>";
	$result=$cms->query("select * from zed_photo where CATEGORY='$id' order by ORD");
	$prstr="/zed/?modul=slider&action=view&id=$id";
	$pages=$cms->gen_page($result,200,$prstr);
	$result=$cms->query("select * from zed_photo where CATEGORY='$id' order by ORD limit $cstart,$cend");
	while($row=$cms->fetch_object($result))
	{
		$dd['name']='';
		$dd['funk']="<div class='circle'>$i</div><a href=/zed/?modul=slider&action=del&id=$id&delet=$row->ID onclick=\"return confirm('Удалить?')\" title='Удалить'><img src='templates/default/images/del.png'></a> 
<a href=/zed/?modul=slider&action=edit&id=$id&edited=$row->ID title='Редактировать' ><img src='templates/default/images/edit.png' /></a>
<a href='?modul=slider&action=up&id=$id&edited=$row->ID' title='Поднять'><img src='/zed/templates/default/images/up.png' /></a>
<a href='?modul=slider&action=down&id=$id&edited=$row->ID' title='Опустить'><img src='/zed/templates/default/images/down.png' /></a>";
		$rowww=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$row->ID' and `TABLE`='zed_photo' "));
		if($rowww->W>$rowww->H){$o='width=100';}
		else $o='height=100';
		$dd['foto']="<img src='{$rowww->PATH}l$rowww->NAME' $o >";
		$data['table'].=$cms->blockparse('fotoother',$dd,3);
		$i++;
	}
	$data['table'].="</td></tr>";
	
	$data['class']='tabl_def border';
$str.="<div id='button'><a class='url_button_add' href='/zed/?modul=slider&action=add&id=$id'>Добавить</a></div>";
	$str.=$cms->blockparse('table2',$data,3);
	$str.=$pages;
	
	$r_n=$cms->fetch_object($cms->query("select NAME from zed_photo_category where ID='$cat'"));
	$zed_navi=$cms->navi($id)." > $r_n->NAME";
	return $str;
}

################################################################################
##################            Добавление в базу               ##################
################################################################################
function add_images_form($id)
{
	global $cms, $zed_navi;$str='';
	if(isset($_POST['added']))
	{
		$name = trim($_POST['name']);
		$row=$cms->query("select ORD from zed_photo where CATEGORY='$id' order by ORD DESC limit 1");
		if($cms->num_rows($row)>0)
		{
			$res=$cms->fetch_object($row);
			$ord=$res->ORD+1;
		}
		else $ord=1;
		$result=$cms->query("insert into zed_photo(`ID`,`CATEGORY`,`NAME`,`ORD`) values (null,'$id','$name','$ord')");
		add_foto($cms->insert_id(),'zed_photo');
		return show_images($id,'Фото добавлено');
	}
	$str.="<form action='?modul=slider&action=add&id=$id' name='addform' method='post' enctype='multipart/form-data'>";
	$str.="<div id='button'><input type='submit' name='added' value='Добавить' class='submit add' ></div>";
	$data['table']= "
	<tr><td width=100><b>URL</b></td><td><input type=text name='name' class='w90p'></td></tr>
	<tr><td class='help' align='left'  colspan=2>Изображение не должно превышать 2МБ, рамер больше 1260х540 px.</td></tr>
	<tr><td><b>Фото</b></td><td><input name='foto' type='file' /></td></tr>
	</form>";
	$data['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$data,3);
	$zed_navi=$cms->navi($id)." > Добавление";
	return $str;
}
################################################################################
##################         Редактирование объекта             ##################
################################################################################
function edit_images_form($id_cat,$id)
{

	global $cms,$zed_navi;$str='';
	
	if(isset($_POST['edited']))
	{
		$name = trim($_POST['name']);
		$cms->query("update zed_photo set NAME='$name' where ID='$id'");

		add_foto($id,'zed_photo');
		return show_images($id_cat,'Изменения приняты');
	}
	$row=$cms->fetch_object($cms->query("select * from zed_photo where ID='$id'"));
	$name=$row->NAME;

	$rowww=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$row->ID' and `TABLE`='zed_photo' "));
	$foto="<img src='{$rowww->PATH}l$rowww->NAME' width=600 />";
	$str.="<form action='?modul=slider&action=edit&id=$id_cat&edited=$id' name='editedform' method='post' enctype='multipart/form-data'>";
	$str.="<div id='button'><input type='submit' name='edited' value='Сохранить' class='submit save' ></div>";
	$data['table']= "
	<tr><td width=100><b>Название</b></td><td><input type='text' name='name' class='w90p' value='$name'></td></tr>
	<tr><td class='help' align='left'  colspan=2><b><i>Выбор файла только если нужно поменять избражение</i></b></td></tr>
	<tr><td><b>Фото</b><br />$foto</td><td><input name='foto' type='file'>
	<input type='hidden' name='idfoto' value='$rowww->ID'><br />  </td></tr>
	</form>";
	$data['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$data,3);
	$zed_navi=$cms->navi($id_cat)." > Редактирование";
	return $str;
}
#####################3
if (isset($_GET['action']))
{
	$zed_adding='<script type="text/javascript" src="/zed/modules/ckeditor/ckeditor.js"></script>';
	switch($_GET['action'])
	{
		case "add":
		$zed_content=add_images_form($_GET['id']);
		break;
		case "edit":
		$zed_content=edit_images_form($_GET['id'],$_GET['edited']);
		break;
		case "delet":
		$zed_content=del_cat($_GET['id'],$_GET['delet']);
		break;
		case "del":
		$zed_content=del_foto($_GET['id'],$_GET['delet']);
		break;
		case "up":
		up($_GET['edited'],'zed_photo');
		$zed_content=show_images($_GET['id']);
		break;
		case "down":
		down($_GET['edited'],'zed_photo');
		$zed_content=show_images($_GET['id']);
		break;
		case "open":
		$zed_content=show_images($_GET['id']);
		break;
	}
}


?>


