<?
if(!$cms->checklevel("60")) die("Доступ закрыт, обратитесь к администратору");
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
					$opt=$cms->fetch_object($cms->query("select W,H from zed_pages_options where CATEGORY='$id'"));	
					$width = $opt->W;
					$height = $opt->H;
					$rez = $cms->img_resize("photo/$file_name", "photo/m".$file_name, $width, $height);
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
function del_img($id,$table)
{
	global $cms;
	$photo = $cms->query("select * from zed_image where CATEGORY='$id' and `TABLE`='$table' ");
	while ($image= $cms->fetch_object($photo))
	{
		$delfile = str_replace("/zed/","",$image->PATH).$image->NAME;
		if(is_file($delfile)) unlink($delfile);
		$delfile = str_replace("/zed/","",$image->PATH)."m".$image->NAME;
		if(is_file($delfile)) unlink($delfile);
		$cms->query("delete from zed_image where ID=$image->ID");
		// удаляем картинки
	}
}
# работа со страницами сайта
# пред установка
function options($id)
{
	global $cms, $zed_navi;
	$data['table']="";
	if(isset($_POST['addoptions']))
	{
		$sqln='';
		$sqld='';
		$navi = $_POST['navi'];
		$img = $_POST['img'];
		if ($navi!=''){$sqln.=',NAVI'; $sqld.=",'$navi'";}
		if ($img!=''){$sqln.=',IMAGE,W,H,SHABLON'; $sqld.=",'$img','{$_POST['w']}','{$_POST['h']}','{$_POST['shablon']}'";}
		if ($sqln!='')
		{
			$cms->query("insert into zed_pages_options (ID,CATEGORY $sqln) values ('null','$id' $sqld)");
			
			return  add_form($id);
		}
		else
		{
			$cms->query("insert into zed_pages_options (ID,CATEGORY) values ('null','$id')");
			return add_form($id);
		}
	}
	
	if(isset($_POST['addshablon']))
	{
		$shablon= "<tr><td>
	&nbsp; &nbsp; 1 - 	<img src=templates/default/images/i_l.png>
	&nbsp; &nbsp; 2 - 	<img src=templates/default/images/i_r.png><br>
	&nbsp; &nbsp; 3 - 	<img src=templates/default/images/i_l_o.png>
	&nbsp; &nbsp; 4 - 	<img src=templates/default/images/i_r_o.png><br>
	&nbsp; &nbsp; 5 - 	<img src=templates/default/images/i_nad.png>
	&nbsp; &nbsp; 6 - 	<img src=templates/default/images/i_pod.png><br>
	<br>	
	&nbsp; &nbsp; 1 - <input type='radio' name='shablon' value='fotoleft' class=bot title='' >
	&nbsp; &nbsp; 2 - <input type='radio' name='shablon' value='fotoright' class=bot title='' >
	&nbsp; &nbsp; 3 - <input type='radio' name='shablon' value='fotolefto' class=bot title='' >
	&nbsp; &nbsp; 4 - <input type='radio' name='shablon' value='fotorighto' class=bot title='' >
	&nbsp; &nbsp; 5 - <input type='radio' name='shablon' value='fototop' class=bot title='' >
	&nbsp; &nbsp; 6 - <input type='radio' name='shablon' value='fotoander' class=bot title='' >
	<br>
	Введите ширину/высоту изображения <input type='text' name='w' class='w30px' value='' /> / <input type='text' name='h' class='w30px' value='' /><input type='hidden' name='img' value='1' />
	</td></tr>";
	}
	else 
	{
		$shablon= "<tr><td valign=top class=bg1>Если шаблон не выбран то страница будет содержать только текст &nbsp; <input type=\"submit\" name=\"addshablon\" value=\"Выбрать шаблон\" class=submit></td></tr>";	
	}

	$data['table'].= "
	<form action=\"?modul=page&action=open&id=$id\" name=\"addoptions\" method=\"post\" ENCTYPE=\"multipart/form-data\">
	<tr><td valign=top class=bg1>Вводить имя вручную <input type='checkbox' name='navi' value='1' class=bot title='ставится если будет вводить имя вручную' $checed ></td></tr>
	$shablon
	<tr><td align=left class=bg1><input type=\"submit\" name=\"addoptions\" value=\"Установить\" class=submit></td></tr></form>";
	$zed_navi=$cms->navi($id);
	$zed_navi.='установка параметров';
	return $cms->blockparse('table',$data,3);
}
# показать полностью
function view_page($id,$status='')
{
	global $cms, $ZED, $zed_navi;
	$data['table']="";
	$str='';
	$result=$cms->query("select * from zed_pages where ID='$id'");
	$row=$cms->fetch_object($result);

	$str.="<div id='button'><a class='url_button_edit' href=?modul=page&action=edit&id=$row->ID>редактировать</a> <a class='url_button_del' href=?modul=page&action=del&id=$row->ID>удалить</a></div>";
	$data['table'].= "<tr><td>$row->FULL</td></tr>";
	$zed_navi=$cms->navi($id).$status;
	$data['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$data,3);
	return $str;
}

function add_form($id,$status='')
{
	global $cms, $ZED, $zed_navi;
	$data['table']="";$str='';
	include_once ("modules/fckeditor/fckeditor.php");
	$oFCKe= new FCKeditor('full');
	$oFCKe->Value="";
	$oFCKe->Height="600";
	if(isset($_POST['addpages']))
	{
		$full=$_POST['full'];
		/*if(isset($_POST['navi'])){$navi=$_POST['navi'];}
		else */$navi='';
		$result=$cms->query("insert into zed_pages (ID, FULL, NAVI) values ('$id','$full','$navi')");
		//if(isset($_POST['img'])){add_foto($id,'zed_pages');}
		if($result)
		{
			return view_page($id," -> Страница добавлена");
		}
	}
	//$opt=$cms->fetch_object($cms->query("select * from zed_pages_options where CATEGORY='$id'"));
	$str.= "<form action=\"?modul=page&action=open&id=$id\" name='addform' method='post' enctype='multipart/form-data'>";
	$str.="<div id='button'><input type='submit' name='addpages' value='Добавить' class='submit add' ></div>";
	/*if($opt->NAVI==1)
	{
		$data['table'].= "<tr><td><input type='text' name='navi' class='inp w70p'  /></td></tr>";
	}
	if($opt->IMAGE==1)
	{
		$data['table'].="<tr><td><b>Добавляемое излображение должно быть не меньше высота/ширина ($opt->W/$opt->H)</b></td></tr>";
		$data['table'].="<tr><td><input type='file' name='foto' /><input type='hidden' name='img' value='1' /></td></tr>";
	}/**/
	$data['table'].= "<tr><td class=bg1 align=left>".$oFCKe->Create()."</td></tr></form>";
	/*$data['table'].= "<tr><td><textarea name='full' >$row->FULL</textarea></td></tr>
	<script>
	CKEDITOR.replace( 'full',{
	'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
	'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
	'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
	'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
	'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
	'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
	</script></form>";/**/
	//<tr><td align=left class=bg1></td></tr>
	$zed_navi=$cms->navi($id).$status;
	$data['class']='tabl_def';
	$str.=$cms->blockparse('table2',$data,3);
	return $str;
}

# редактирование страниц
function edit_page_form($id)
{
	global $cms,  $ZED, $zed_navi;
	include_once ("modules/fckeditor/fckeditor.php");
	$oFCK= new FCKeditor("full");
	$oFCK->Height="600";
	$oFCK->ToolbarSet="MyTools";
	$oFCK->Width="1200";
	$data['table']="";$str='';
	if(isset($_POST['addpages']))
	{
		$full=$_POST['full'];
		/*if(isset($_POST['navi'])){$navi=$_POST['navi'];}
		else*/ $navi='';
		$result=$cms->query("update zed_pages set `FULL`='$full' , `NAVI`='$navi' where ID='$id'");
		//if(isset($_POST['img'])){add_foto($id,'zed_pages');}
		//print_r($_POST); 
		if($result)
		{
			return view_page($id," -> Страница изменена");
		}
	}
	else 
	{
		$row=$cms->fetch_object($cms->query("select * from zed_pages where ID='$id'"));
		//$navi=$row->NAVI;
		$oFCK->Value=$row->FULL;
	}	
	//$opt=$cms->fetch_object($cms->query("select * from zed_pages_options where CATEGORY='$id'"));
	$str.="<form action=\"?modul=page&action=edit&id=$id\" name='editpage' method='post' enctype='multipart/form-data'>";
	$str.="<div id='button'><input type='submit' name='addpages' value='Сохранить' class='submit save' ></div>";
	/*if($opt->NAVI==1)
	{
		$data['table'].= "<tr><td><input type='text' name='navi' class='inp w70p' value='$navi' /></td></tr>";
	}
	if($opt->IMAGE==1)
	{
		$img = $cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$id' and `TABLE`='zed_pages'"));
		$data['table'].="<tr><td><b>Добавляемое излображение должно быть не меньше высота/ширина ($opt->W/$opt->H)</b></td></tr>";
		$data['table'].="<tr><td><img src='{$img->PATH}m$img->NAME' /><input type='file' name='foto' /><input type='hidden' name='img' value='1' /><input type='hidden' name='idfoto' value='$img->ID'></td></tr>";
	}*/
	$data['table'].= "<tr><td class=bg1 align=left>".$oFCK->Create()."</td></tr></form>";
	/*$data['table'].= "<tr><td><textarea name='full' >$row->FULL</textarea></td></tr>
	<script>
	CKEDITOR.replace( 'full',{
		'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
		'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
		'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
		'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
		'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
		'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
		</script></form>";/**/
	$zed_navi=$cms->navi($id)." > <p>Редактирование</p>";
	$data['class']='tabl_def';
	$str.=$cms->blockparse('table2',$data,3);
	return $str;
}

# удаление страницы
function del_page($id)
{
	global $cms, $zed_navi;
/*	$opt=$cms->fetch_object($cms->query("select * from zed_pages_options where CATEGORY='$id'"));
	if($opt->IMAGE==1){del_img($id,'zed_pages');}*/
	$result=$cms->query("delete from zed_pages where ID=$id");
	if($result){$zed_navi=$cms->navi($id);}
	return add_form($id," Страница удалена");
}

### работа скрипта
if (isset($_GET['action']))
{
	//$zed_adding='<script type="text/javascript" src="/zed/modules/ckeditor/ckeditor.js"></script>';
	switch($_GET['action'])
	{
		case "open":
		
			if($cms->check_if_exist($_GET['id'],"zed_pages","ID"))
			{$zed_content=view_page($_GET['id']);}
			else{$zed_content=add_form($_GET['id']);}
		break;
		case "edit":
		$zed_content=edit_page_form($_GET['id']);
		break;
		case "del":
		$zed_content=del_page($_GET['id']);
		break;
	}
}
?>
