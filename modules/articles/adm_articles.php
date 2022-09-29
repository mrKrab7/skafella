<?
//проверка статуса пользователя
if(!$cms->checklevel("40")) die("Доступ закрыт, обратитесь к администратору");

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
					$rez = $cms->img_resize("photo/$file_name", "photo/m".$file_name, 350,'',270);
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

function up($id,$table,$field='',$value='') {
	global $cms;
	$tpl=$cms->fetch_object($cms->query("select * from $table where ID='$id'"));
	if($field!='') $adding = "and `$field`='$value'";
	else $adding = "";
	$r2=$cms->query("select * from $table where ORD < $tpl->ORD $adding order by ORD desc");
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
	$r2=$cms->query("select * from $table where ORD > $tpl->ORD $adding order by ORD");
	if($cms->num_rows($r2)>0) {
		$rn1=$cms->fetch_object($r2);
		$cms->query("UPDATE $table SET ORD='$rn1->ORD' where ID='$id'");
		$cms->query("UPDATE $table SET ORD='$tpl->ORD' where ID='$rn1->ID'");
	}
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

function edit_articles($id)
{
	global $cms;
	$artmain=0;
	$error="";
	$title = $_POST['title']; if($title==""){$error = "Нет заголовка";}
	//$small = $_POST['small'];if($small==""){if($error!="")$error.="<br>"; $error.="Отсутствует краткое описание";}
	$full = $_POST['full'];
	$user_edit = $_SESSION['userid'];
	$data_edit = time();
	if($error=="")
	{
		$row=$cms->fetch_object($cms->query("select * from zed_articles where ID='$id'"));
	}
	if($error!=""){return edit_articles_form($id, $error);}
	add_foto($id,'zed_articles');
	$result=$cms->query("update zed_articles set TITLE='$title', SMALL='$small', FULL='$full',
	 USER_EDIT='$user_edit', DATE_EDIT='$data_edit' where ID='$id' ")or $cms->error();
	if($result)
	{
		$str="<div  id='message' >Изменения приняты</div>".show_articles($row->CATEGORY);
	}
	else {$str="<div  id='message' >ошибка</div>".edit_articles_form($id);}
	
	$zed_navi=navi_moder($row->CATEGORY);
	return $str;
}

function edit_articles_form($id, $error="")
{
	global $cms, $zed_navi;
	
	$row=$cms->fetch_object($cms->query("select * from zed_articles where ID='$id'"));
	$cat_id=$row->CATEGORY;
	/*include_once ("modules/fckeditor/fckeditor.php");
	$oFCKeditor2 = new FCKeditor('full');
	$oFCKeditor2->Value=$row->FULL;*/
	$zed_navi=navi_moder($cat_id).$row->TITLE." - Редактирование";

	$str="<form action=?modul=articles&action=edited&id=$id method=post enctype=\"multipart/form-data\">";
	if($error!=""){$str.="<div  id='message' >$error</div>";}
	$img = $cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$id' and `TABLE`='zed_news'"));
	
	$str.="<div id='button'><input type='submit' name='add_articles' value='Сохранить' class='submit save'></div>";
	
	$articlesdat['table'].="<tr bgcolor=#f3f3f3><td align=left><b>Заголовок</b>  <input type=text name=title size=50 maxlenght=255 value='".$row->TITLE."'></td></tr>
	<tr><td align=left><b>Картинка</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Выбрать изображение в том случае если необходимо заменить картинку.<br><img src='{$img->PATH}m$img->NAME' style='width: 100px' />&nbsp; &nbsp; <input type='file' name='foto' /><input type='hidden' name='img' value='1' /><input type='hidden' name='idfoto' value='$img->ID'></td></tr>
	</tr><tr bgcolor=#fafafa><td><textarea name='full' >$row->FULL</textarea>
<script>
CKEDITOR.replace( 'full',{
	'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
	'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
	'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
	'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
	'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
	'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
	</script></td></tr>";

	$articlesdat['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$articlesdat,3);
	$str.="</form>";
	return $str;

}

function del_articles($id)
{
	global $cms,  $zed_navi;
	$str="";
	$row=$cms->fetch_object($cms->query("select * from zed_articles where ID='$id'"));
	$result=$cms->query("delete from zed_articles$get where ID=$id");
	$zed_navi=navi_moder($row->CATEGORY);
	$str=$cms->message("удалено");
	$str.=show_articles($row->CATEGORY);
	
	return $str;
}

function post_articles($id)
{
	global $cms, $zed_navi;
	if(Check_Moder_articles())
	{
		$row=$cms->fetch_object($cms->query("select * from zed_articles_request where ID='$id'"));
		$row->SMALL = addslashes($row->SMALL);
		$row->FULL = addslashes($row->FULL);
		$result=$cms->query("insert into zed_articles
			(ID,TITLE,SMALL,FULL,AUTHOR,LINK,CATEGORY,DATE,RAZDEL,IMAGE,MAIN)
		values ('null','$row->TITLE','$row->SMALL','$row->FULL',
				'$row->AUTHOR','$row->LINK','$row->CATEGORY',
				'$row->DATE','$row->RAZDEL','$row->IMAGE','1')");
		$result=$cms->query("delete from zed_articles_request where ID=$id");
		$zed_navi=navi_moder($row->CATEGORY);
		$str=$cms->message("Новость размещена");
		$str.=show_articles($row->CATEGORY);
	}
	else
	{
		$row=$cms->fetch_object($cms->query("select CATEGORY from zed_articles_request where ID='$id'"));
		$zed_navi=navi_moder($row->CATEGORY);
		$str=$cms->message("У вас нет прав на данную операцию");
		$str.=show_articles($row->CATEGORY);
	}
	return $str;
}

function show_articles($id,$status='')
{
	global $cms, $cstart,$cend,$zed_navi;
	
	if(isset($_GET['up'])) up($_GET['idd'],'zed_articles','CATEGORY',$id);
	if(isset($_GET['down'])) down($_GET['idd'],'zed_articles','CATEGORY',$id);
	
	$str="";
	if($status!='') $str = $status;
	$result=$cms->query("select ID,DATE,TITLE,AUTHOR,USER_EDIT,DATE_EDIT from zed_articles where CATEGORY='$id' order by ORD");
	$paramstring="?modul=articles&action=open&id=$id";
	$str3=$cms->gen_page($result,20,$paramstring);
	$result=$cms->query("select ID,DATE,TITLE,AUTHOR,USER_EDIT,DATE_EDIT from zed_articles where CATEGORY='$id' order by ORD limit $cstart,$cend");
	$articlesdat['table']="";
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
//<i>".date("d-m-Y",$row->DATE)."</i> |
		$articlesdat['title']="<b><a href='?modul=articles&action=edit&id=$row->ID' title='Редактировать'> $row->TITLE</a></b> | $avtor->FIO";

		$articlesdat['func']="
<a href='$url_sait/$row->ID' target='_blank' title='Просмотр на сайте'><img src=templates/default/images/show.png /></a>
<a href=?modul=articles&action=edit&id=$row->ID title='Редактировать'><img src=templates/default/images/edit.png /></a>
<a href=?modul=articles&action=del&id=$row->ID title='Удалить' onclick=\"return confirm('Удалить?')\"><img src=templates/default/images/del.png /></a>
<a href='?modul=articles&action=open&up&idd=$row->ID&id=$id'' title='Поднять'><img src='/zed/templates/default/images/up.png' /></a>
<a href='?modul=articles&action=open&down&idd=$row->ID&id=$id'' title='Опустить'><img src='/zed/templates/default/images/down.png' /></a>";		

		if($fl==1){$articlesdat['class']="class='table_tr_bg'";$fl=0;}
		else {$articlesdat['class']="";$fl=1;}
		$str2=$cms->blockparse('show_news_small',$articlesdat,3);
		$articlesdat['table'].=$str2;
	}
	$articlesdat['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$articlesdat,3)."<br>";
	$str.=$str3;
	$zed_navi=navi_moder($id)."<a href='?modul=articles&action=add&category=$id'>Добавить</a>";	
	return $str;
}

function add_articles_form($cat_id, $errmess="")
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
	/*include_once ("modules/fckeditor/fckeditor.php");
	$oFCKeditor2 = new FCKeditor('full') ;
	$oFCKeditor2->Value= $full ;*/
	$zed_navi=navi_moder($cat_id)."Добавление";
	$str="<form action=?modul=articles&action=added&category=$cat_id method=post enctype=\"multipart/form-data\">";
	$str.="<div id='button'><input type=submit name='add_articles' value='Добавить' class='submit add'></div>";
	if($errmess!= "")$str.="<div  id='message' >$errmess</div>";
	$articlesdat['table'].="<tr bgcolor=#f3f3f3><td align=left><b>Заголовок</b> <input type=text name=title class='w90p' maxlenght=255 value=$title></td></tr>";
	$articlesdat['table'].="<tr ><td class='help_message'><i>Загружаемое изображение не должно превышать 2МБ, и быть не меньше 250х250 pix</i></td></tr>";
	$articlesdat['table'].="<tr ><td ><b>Картинка</b> <input type='file' name='img' ></td></tr>";
	$articlesdat['table'].="<tr bgcolor=#fafafa><td><textarea name='full' ></textarea>
	<script>
	CKEDITOR.replace( 'full',{
		'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
		'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
		'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
		'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
		'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
		'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
		</script></td></tr>";

	$articlesdat['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$articlesdat,3);
	$str.="</form>";

	return $str;
}

function add_articles($category)
{
	global $cms,$_POST;
	$title = $_POST['title'];
	$small = $_POST['small'];
	$full = $_POST['full'];
	$author = $_SESSION['userid'];
	$date=time();
	if($title == "")$result.= "Нет заголовка";
	if($result=="")
	{
		$row=$cms->query("select ORD from zed_articles where CATEGORY='$category' order by ORD DESC limit 1");
		if($cms->num_rows($row)>0)
		{
			$res=$cms->fetch_object($row);
			$ord=$res->ORD+1;
		}
		else $ord=1;
		$result=$cms->query("insert into zed_articles (TITLE,SMALL,FULL,AUTHOR,CATEGORY,DATE,ORD)
	values ('$title','$small','$full','$author','$category','$date','$ord')");
		add_foto($id,'zed_articles');
		$result="<div  id='message' >добавлено</div>";
		return show_articles($category, $result);
	}
	else
	{
		return add_articles_form($category, $result);
	}
}

if (isset($_GET['action']))
{
	$zed_adding='<script type="text/javascript" src="/zed/modules/ckeditor/ckeditor.js"></script>';
	switch($_GET['action'])
	{

		case "open":
		$zed_content=show_articles($_GET['id']);
		break;
		case "add":
		$zed_content=add_articles_form($_GET['category']);
		break;
		case "added":
		$zed_navi=navi_moder($_GET['category']);
		$zed_content=add_articles($_GET['category']);
		break;
		case "del":
		$zed_content=del_articles($_GET['id']);
		break;
		case "post":
		$zed_content=post_articles($_GET['id']);
		break;
		case "edit":
		$zed_content=edit_articles_form($_GET['id']);
		break;
		case "edited":
		$zed_content=edit_articles($_GET['id']);
		break;		
	}
}
?>