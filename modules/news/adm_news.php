<?
//проверка статуса пользователя
if(!$cms->checklevel("40")) die("Доступ закрыт, обратитесь к администратору");
//rabota s catalogom
function add_foto($id,$table)
{
	global $cms;
	$error='';
	if(isset($_FILES)) {
		foreach ($_FILES as $key=>$value) {
			if ($key=='' || $_FILES[$key]['error']==4){continue;}
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
					$idim = $cms->fetch_object($cms->query("select * from zed_image where ID={$_POST["id$key"]}"));
					$delfile = str_replace("/zed/","",$idim->PATH).$idim->NAME;
					if(is_file($delfile)) unlink($delfile);
					$delfile = str_replace("/zed/","",$idim->PATH)."m".$idim->NAME;
					if(is_file($delfile)) unlink($delfile);
					$file_name="{$_POST["id$key"]}_$id.$ext";
					$fl=1;
					$idimg = $idim->ID;
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
					$rez = $cms->img_resize("photo/$file_name", "photo/m".$file_name, 350,270);
					if($rez!='') $error.=$rez;
					else {
						$result=$cms->query("update zed_image set `NAME` = '$file_name', W='{$size[0]}', H='{$size[1]}' where ID='$idimg'");
					}
				}
				else $error.="ошибка загрузки, нет доступа к папке($key)<br />";
			}
		}
	}
	return $error;
}

function Check_Moder_News()
{
	global $_SESSION;
	if($_SESSION['rank']>=80 || $_SESSION['userid']==81 || $_SESSION['userid']==424) // тут могут быть и другие
	{
		return true;
	}
	return false;
}
function navi_moder($id)
{
	global $cms;
	$str="";
	$get = "";
	if(isset($_GET['type']) && $_GET['type']=="moder"){$get = "&type=moder";}
	if ($id==0) {$str=": <a href=?modul=category> Рубрикатор </a> : ".$str;}
	else {
		$row=$cms->fetch_object($cms->query("select * from zed_category where ID=$id"));
		$str="<a href=\"?modul=$row->TYPE&action=open&id=$row->ID$get\">$row->NAME</a> : ".$str;
		$str=navi_moder($row->PARENT).$str;
	}
	return $str;
}
function CheckImage($image_name="")
{
	$allowed_extensions = array("gif", "jpg", "png", "bmp", "jpe", "jpeg");
	if($_FILES['img']['name']!="")
	{
		$image = $_FILES['img']['tmp_name'];
		$image_name = $_FILES['img']['name'];
		$img_name_arr = explode(".",$image_name);
		$type = end($img_name_arr);
		if( !(in_array($type, $allowed_extensions) or in_array(strtolower($type), $allowed_extensions)) )
		{return "Загрузка этих файлов запрещена";}
	}
	return "";
}
function LoadImage($image_name, $id)
{
	$image = $_FILES['img']['tmp_name'];
	$image_name = $_FILES['img']['name'];
	if($image_name=='')return;
	$img_name_arr = explode(".",$image_name);
	$type = end($img_name_arr);
	copy($image, "image/news/img_$id.$type") or $rez = "error";
	if(@$rez == "error") return "";
	img_resize("image/news/img_".$id.".".$type, "image/news/sm_".$id.".".$type, "image/news/md_".$id.".".$type, 90);
	// тут типа формируем small и real images
	unlink("image/news/img_".$id.".".$type);
	return $id.".".$type;
}

function edit_news($id)
{
	global $cms;
	$error="";
	$title = $_POST['title']; if($title==""){$error = "Нет заголовка";}
	$small = $_POST['small'];if($small==""){if($error!="")$error.="<br>"; $error.="Отсутствует новость вкратце";}
	$full = $_POST['full'];if($full==""){if($error!="")$error.="<br>"; $error.="Отсутствует сама новость";}
	$user_edit = $_SESSION['userid'];
	$data_edit = date('Y-m-d H:i:s');
	if($error=="")
	{
		$row=$cms->fetch_object($cms->query("select * from zed_news where ID='$id'"));
		$error.= CheckImage($row->IMAGE);
	}
	if($error!=""){return edit_news_form($id, $error);}
	else
	{
		add_foto($id,'zed_news');
		$result=$cms->query("update zed_news set TITLE='$title',IMAGE='$image', SMALL='$small', FULL='$full',
		 USER_EDIT='$user_edit', DATE_EDIT='$data_edit' where ID='$id' ")or $cms->error();
		if($result)
		{
			$result="<div id='message' >Изменения приняты</div>";
			return show_news($row->CATEGORY,$result);
		}
		else 
		{
			$result="<div id='message' >ошибка</div>";
			return edit_news_form($id,$result);
		}
	}
}

function edit_news_form($id, $error="")
{
	global $cms, $zed_navi;

	$row=$cms->fetch_object($cms->query("select * from zed_news where ID='$id'"));
	$cat_id=$row->CATEGORY;
	$zed_navi=navi_moder($cat_id).$row->TITLE." - Редактирование";

	$str="<form action=?modul=news&action=edited&id=$id method=post enctype=\"multipart/form-data\">";
	$str.="<div id='button'><input type='submit' name='add_news' value='Сохранить' class='submit save'></div>";
	
	if($error!=""){$str.="<div  id='message' >$error</div>";}
	$img = $cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$id' and `TABLE`='zed_news'"));
	
	$newsdat['table'].="
	<tr ><td><b>Заголовок</b>  <input type='text' name='title' class='w90p' value='$row->TITLE'></td></tr>
	<tr><td align=left><b>Картинка</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Выбрать изображение в том случае если необходимо заменить картинку.<br><img src='{$img->PATH}m$img->NAME' style='width: 100px' />&nbsp; &nbsp; <input type='file' name='foto' /><input type='hidden' name='img' value='1' /><input type='hidden' name='idfoto' value='$img->ID'></td></tr>
	<tr ><td><b>Коротко о событии</b></td></tr><tr ><td><textarea name='small' class='w90p' rows='5'>$row->SMALL</textarea></td></tr>
	<tr ><td><b>Новость</b></td></tr><tr ><td><textarea name='full' >$row->FULL</textarea>
<script>
CKEDITOR.replace( 'full',{
	'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
	'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
	'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
	'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
	'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
	'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
	</script></td></tr>
   ";
	$newsdat['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$newsdat,3);
	$str.="</form>";
	return $str;
}

function del_news($id)
{
	global $cms,  $zed_navi;
	$str="";	
	$row=$cms->fetch_object($cms->query("select * from zed_news where ID='$id'"));
	
	$result=$cms->query("delete from zed_news where ID=$id");
	$zed_navi=navi_moder($row->CATEGORY);
	$result="<div id='message' >Новость удалена</div>";
	
	return show_news($row->CATEGORY,$result);
}

function show_news($id,$status='')
{
	global $cms, $cstart,$cend,$zed_navi;
	$str="";
	if($status!='') $str = $status;
	$result=$cms->query("select ID,DATE,TITLE,AUTHOR,RAZDEL,USER_EDIT,DATE_EDIT from zed_news where CATEGORY='$id' order by ID DESC");
	$paramstring="?modul=news&action=open&id=$id";
	$str3=$cms->gen_page($result,20,$paramstring);
	$result=$cms->query("select ID,DATE,TITLE,AUTHOR,RAZDEL,USER_EDIT,DATE_EDIT from zed_news where CATEGORY='$id' order by ID DESC limit $cstart,$cend");
	$newsdat['table']="";
	while($row=$cms->fetch_object($result))
	{
		$avtor=$cms->fetch_object($cms->query("select FIO from zed_users where ID='$row->AUTHOR'"));
		if($row->USER_EDIT!='0')
		{
			$last_edit_user = $cms->fetch_object($cms->query("select FIO from zed_users where ID='$row->USER_EDIT'"));
			$last_edit_time = date("d-m-Y",$row->DATE_EDIT);
		}
		else{$last_edit_user="";$last_edit_time="";}
		$url_sait=$cms->get_url_from_id($id);
		$date=split(' ',$row->DATE);
		$newsdat['title']="<i>".$date[0]."</i>  |<b><a href=?modul=news&action=edit&id=$row->ID title='Редактировать'>$row->TITLE</a></b> | $avtor->FIO";
		$newsdat['func']="
	<a href='$url_sait/$row->ID' target='_blank' title='Просмотр на сайте'><img src=templates/default/images/show.png /></a>
	<a href=?modul=news&action=edit&id=$row->ID title='Редактировать'><img src=templates/default/images/edit.png /></a>
	<a href=?modul=news&action=del&id=$row->ID title='Удалить' onclick=\"return confirm('Удалить?')\"><img src=templates/default/images/del.png /></a>"; 
		
		if($fl==1){$newsdat['class']="class='table_tr_bg'";$fl=0;}
		else {$newsdat['class']="";$fl=1;}
		
		$str2=$cms->blockparse('show_news_small',$newsdat,3);
		$newsdat['table'].=$str2;
	}
	$newsdat['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$newsdat,3)."<br>";
	$str.=$str3;
	$zed_navi=navi_moder($id)."<a href='?modul=news&action=add&category=$id'>Добавить новость</a>";
	return $str;
}

function show_news_full($id)
{
	global $cms, $zed_navi;
	$str="";
	$get = "";
	$gett = "";
	if(isset($_GET['type']) && $_GET['type']=="moder"){$get = "_request";$gett = "&type=moder";}
	$row=$cms->fetch_object($cms->query("select * from zed_news$get where ID='$id'"));
	$avtor=$cms->fetch_object($cms->query("select FIO from zed_users where ID='$row->AUTHOR'"));
	$newsdat['DATE']=date("d-m-Y",$row->DATE);
	$newsdat['NAME']=$row->TITLE;
	$newsdat['AUTHOR']=$avtor->FIO;
	$newsdat['SMALL']=$row->SMALL;
	$newsdat['FULL']=$row->FULL;
	$newsdat['IMAGE']="<img src=\"Image/news/sm_$row->IMAGE\">";
	switch($row->RAZDEL)
	{
		case 1: $newsdat['RAZDEL']="Новосибирск";break;
		case 2: $newsdat['RAZDEL']="НСО";break;
		case 3: $newsdat['RAZDEL']="Россия / Мир";break;
	}

	if(Check_Moder_News())
	{
		if(isset($_GET['type']) && $_GET['type']=="moder")
		{
			$newsdat['func']="<a href=?modul=news&action=edit&id=$row->ID title=\"Редактировать\"><img src=templates/default/images/appllications2.png border=0></a>
		<a href=?modul=news&action=post&id=$row->ID title=\"Разместить\"><img src=templates/default/images/files_add.png border=0></a>
		<a href=?modul=news&action=del&id=$row->ID title=\"Удалить\" onclick=\"return confirm('Удалить?')\"><img src=templates/default/images/del.png border=0></a>";
		}
		else
		{
			$newsdat['func']="<a href=?modul=news&action=edit&id=$row->ID title=\"Редактировать\"><img src=templates/default/images/appllications2.png border=0></a>
		<a href=?modul=news&action=del&id=$row->ID title=\"Удалить\" onclick=\"return confirm('Удалить?')\"><img src=templates/default/images/del.png border=0></a>";
		}
	} else {$newsdat['func']=""; }
	$str2=$cms->blockparse('newspost',$newsdat,3);
	$newsdat['table']=$str2;
	$str.=$cms->blockparse('table',$newsdat,3);

	$zed_navi=navi_moder($row->CATEGORY)." ".$row->TITLE;
	return $str;
}

function add_news_form($cat_id, $errmess="")
{
	global  $cms,$zed_navi;
	if($errmess!="")
	{
		global $_POST;
		$title = $_POST['title'];
		$small = $_POST['small'];
		$full = $_POST['full'];
	}
	else
	{
		$title = "";
		$small ="";
		$full = "";
	}
	$zed_navi=navi_moder($cat_id)."Добавление новости";
	
	$str="<form action=?modul=news&action=added&category=$cat_id method=post enctype=\"multipart/form-data\">";
	
	$str.="<div id='button'><input type=submit name='add_news' value='Добавить' class='submit save'></div>";
	
	if($errmess!= "")$str.="<div  id='message' >$errmess</div>";
	$newsdat['table'].="<tr ><td ><b>Заголовок</b> <input type=text name='title' class='w90p' maxlenght=255 value='$title' ></td></tr>";
	$newsdat['table'].="<tr ><td class='help_message'><i>Загружаемое изображение не должно превышать 2МБ, и быть не меньше 370х200 pix</i></td></tr>";
	$newsdat['table'].="<tr ><td ><b>Картинка</b> <input type='file' name='img' ></td></tr>";
	$newsdat['table'].="<tr ><td ><b>Коротко о событии</b></td></tr><tr ><td><textarea name='small'  class='w90p' rows='5' >$small</textarea></td></tr>";
	$newsdat['table'].="<tr ><td ><b>Новость</b></td></tr><tr ><td><textarea name='full' ></textarea>
	<script>
	CKEDITOR.replace( 'full',{
		'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
		'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
		'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
		'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
		'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
		'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
		</script></td></tr>";
	//$str.="<tr ><td ><b>Источник</b> <input type=text name=link size=50 maxlenght=255></td></tr>";
	//$str.="<tr ><td ><input type=submit name=add_news value=\"Добавить\"></td></tr>";
	
	//$newsdat['table']=$str;
	$newsdat['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$newsdat,3);
	$str.="</form>";
	return $str;
}

function add_news($category)
{
	global $cms,$_POST;
	$result = CheckImage();
	$title = $_POST['title'];
	$small = $_POST['small'];
	$full = $_POST['full'];
	$author = $_SESSION['userid'];
	$date=date('Y-m-d H:i:s');
	if($title == "")$result.= "<br>Нет заголовка";
	if($small == "")$result.= "<br>Нет привью новости";
	if($full == "")$result.= "<br>Нет самой новости";
	if($result=="")
	{
		$result=$cms->query("insert into zed_news (TITLE,SMALL,FULL,AUTHOR,LINK,CATEGORY,DATE)
	values ('$title','$small','$full','$author','$source_link','$category','$date')");
		
		$id = $cms->insert_id();
		add_foto($id,'zed_news');
		$result="<div id='message' >добавлено</div>";
		return show_news($category, $result);
	}
	else
	{
		return add_news_form($category, $result);
	}
}

if (isset($_GET['action']))
{
	$zed_adding='<script type="text/javascript" src="/zed/modules/ckeditor/ckeditor.js"></script>';
	switch($_GET['action'])
	{
		case "open":
		$zed_content=show_news($_GET['id']);
		
		break;
		case "add":
		$zed_content=add_news_form($_GET['category']);
		break;
		case "added":
		$zed_content=add_news($_GET['category']);
		break;
		case "del":
		$zed_content=del_news($_GET['id']);
		break;
		case "post":
		$zed_content=post_news($_GET['id']);
		break;
		case "show":
		$zed_content=show_news_full($_GET['id']);
		break;
		case "edit":
		$zed_content=edit_news_form($_GET['id']);
		break;
		case "edited":
		$zed_content=edit_news($_GET['id']);
		break;
	}
}
?>