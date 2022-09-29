<?
function add_foto($id,$table) 
{
	global $cms;
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
			if ($_FILES[$key]['size']>2100000) $error.="Файл слишком большой!($key)<br />";
			if ($_FILES[$key]['size']<512) $error.="Файл слишком маленький!($key)<br />";
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
					$delfile = str_replace("/zed/","",$idim->PATH)."s".$idim->NAME;
					if(is_file($delfile)) unlink($delfile);/*
					$delfile = str_replace("/zed/","",$idim->PATH)."m".$idim->NAME;
					if(is_file($delfile)) unlink($delfile);
					$delfile = str_replace("/zed/","",$idim->PATH)."lm".$idim->NAME;
					if(is_file($delfile)) unlink($delfile);
					$delfile = str_replace("/zed/","",$idim->PATH)."l".$idim->NAME;
					if(is_file($delfile)) unlink($delfile);*/
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
					//$width = $_POST['w'.$key];
					//$height = $_POST['h'.$key];
					$rez = $cms->img_resize("photo/$file_name", "photo/s".$file_name, 350,'', 270);
					/*	header("Content-type: $_FILES[$key]['type']");
					$srcp=imagecreatefrompng('photo/i800.png');
					$icfunc = "imagecreatefrom".$ext;
					$idest=$icfunc("photo/l".$file_name);
					imagecopyresized ($idest,$srcp,0,0,0,0,imagesx($idest),imagesy($idest),imagesx($srcp),imagesy($srcp));
					$idest=$icfunc("photo/m".$file_name);
					$srcp=imagecreatefrompng('photo/i200.png');
					imagecopy ($idest,$srcp,0,0,0,0,200,200);
					$func='image'.$ext;
					$func($idest);
					imagedestroy($idest);	
					imagedestroy($srcp);	/**/
					//$rez = img_resize2("photo/$file_name", "photo/m".$file_name, "photo/l".$file_name, $width, 400);
					if($rez!='') $error.=$rez;
					else {
						$result=$cms->query("update zed_image set `NAME` = '$file_name', W='{$size[0]}', H='{$size[1]}' where ID='$idimg'");
					}
				}
				else {$error.="ошибка загрузки, нет доступа к папке($key)<br />";}
			}
		}
	}
	return $error;
}

function add($id) {
	global $cms;
	foreach ($_POST['ids'] as $type=>$dan)
	{
		/*echo '<br> - '.$id.' - ';
		print_r($dan);
		//echo '<br>';/**/
		foreach ($dan as $key=>$value)
		{
			/*echo '<br> - '.$key.' - ';
			//print_r();
			echo $value.'<br>';/**/
			if($value=='')
			{
					
				//echo '<br> - '.$_POST['name_d'][$type][$key].' - <br>';
				$cms->query("insert into zed_price_th(`NAME`, `TYPE`,`ID_TOV`)	values ('{$_POST['name_th'][$type][$key]}','$type',$id)");
			}
			else
			{
				$cms->query("update zed_price_th set  NAME='{$_POST['name_th'][$type][$key]}'  where ID=$value");
			}
		}
	}
}

function upper($id,$table,$field='',$value='') {
	global $cms;
	$tpl=$cms->fetch_object($cms->query("select * from $table where ID='$id'"));
	$r2=$cms->query("select ORD from $table where CATEGORY='$tpl->CATEGORY' order by ORD desc limit 1");
	if($cms->num_rows($r2)>0) {
		$rn1=$cms->fetch_object($r2);
		$ord=$rn1->ORD+1;
		$cms->query("UPDATE $table SET ORD='$ord' where ID='$id'");
	}
}
function up($id,$table,$field='',$value='') {
	global $cms;
	$tpl=$cms->fetch_object($cms->query("select * from $table where ID='$id'"));
	if($field!='') $adding = "and `$field`='$value'";
	else {$adding = "";}
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
	else {$adding = "";}
	$r2=$cms->query("select * from $table where ORD > $tpl->ORD and CATEGORY='$tpl->CATEGORY' $adding order by ORD");
	if($cms->num_rows($r2)>0) {
		$rn1=$cms->fetch_object($r2);
		$cms->query("UPDATE $table SET ORD='$rn1->ORD' where ID='$id'");
		$cms->query("UPDATE $table SET ORD='$tpl->ORD' where ID='$rn1->ID'");
	}
}

function gen_price_menu($id)
{
	global $cms;
	$str='';
	$cat = 0;
	if(isset($_GET['cat'])) $cat = $_GET['cat'];
	if($cat!=0)
	{
		$row = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$cat'"));
		while (1)
		{
			$str = " <a href=\"?modul=price&id=$id&cat=$row->ID\">$row->NAME</a> &raquo;".$str;
			if($row->CATEGORY==0) break;
			$row = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$row->CATEGORY'"));
		}
	}
	$str=$cms->navi($_GET['id']).$str;
	return $str;
}

function del_img_id($id)
{
	global $cms;
	$photo = $cms->query("select * from zed_image where ID='$id'");
	while ($image= $cms->fetch_object($photo))
	{
		$delfile = str_replace("/zed/","",$image->PATH).$image->NAME;
		if(is_file($delfile)) unlink($delfile);
		$delfile = str_replace("/zed/","",$image->PATH)."m".$image->NAME;
		if(is_file($delfile)) unlink($delfile);
		$delfile = str_replace("/zed/","",$image->PATH)."l".$image->NAME;
		if(is_file($delfile)) unlink($delfile);
		$delfile = str_replace("/zed/","",$image->PATH)."s".$image->NAME;
		if(is_file($delfile)) unlink($delfile);
		$cms->query("delete from zed_image where ID=$id");
		// удаляем картинки
	}
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
		$delfile = str_replace("/zed/","",$image->PATH)."l".$image->NAME;
		if(is_file($delfile)) unlink($delfile);
		$delfile = str_replace("/zed/","",$image->PATH)."s".$image->NAME;
		if(is_file($delfile)) unlink($delfile);
		$cms->query("delete from zed_image where ID=$image->ID");
		// удаляем картинки
	}
}
function del_th($mas,$id)
{
	global $cms;
	$p = $cms->query("select * from zed_price where ID='$id'");
	$p= $cms->fetch_object($cms->query("select * from zed_price where ID='$id'"));	
	foreach ($mas as $key=>$value)
	{
		$type_th=str_replace(":$key",'',$p->TYPE_TH);
	}
	$cms->query("update zed_price set TYPE_TH='$type_th' where ID='$id'");
	$cms->query("delete from zed_price_th where TYPE=$key and ID_TOV=$id ");
}

function form_add_cat($id,$cat)
{
	global $cms;
	$result = '';
//	$idlevel='';
	$name = $enname = "";
	if($id==$cat){$lev = 1;}
	else
	{
		$parent = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$cat'"));
		$lev = $parent->LEVEL+1;
	}
	$opt = $cms->query("select PROPERTY,VAL from zed_options where MODUL='price'");
	while ($op = $cms->fetch_object($opt))
	{
		$option[$op->PROPERTY] = $op->VAL;
	}

	if(isset($_POST['addcat']))
	{
		$name = trim($_POST['catname']);
		$enname = urlencode(trim($_POST['encatname']));
		$price = $_POST['tovar'];
		if($name=='' || $enname=='') $result = "Не все поля заполнены";
		if($cms->num_rows($cms->query("select ID from zed_price_category where CATEGORY='$cat' and EN_NAME='$enname'"))>0)
		$result.= "<br />Такая ссылка уже есть!!";
		if($result=='')
		{
			$ord = 1;
			$por = $cms->query("select ID,ORD from zed_price_category where CATEGORY='$cat' order by ORD desc limit 1");
			while($rpor = $cms->fetch_object($por))	$ord = $rpor->ORD+1;
			$cms->query("insert into zed_price_category (ID,NAME,EN_NAME,CATEGORY,LEVEL,PRICE,ORD)
			values ('null','$name','$enname','$cat','$lev','$price','$ord')");
//			if($lev==1) $idlevel=$cms->insert_id();
			$result = "Рубрика добавлена!";
		}
	}
	$change_all = true;
	$price_all = 0;
	if($option['maxrubrics_level']!='auto' && $option['maxrubrics_level']<=$lev)
	{
		$change_all = false;
		$price_all = 1;
	}
	elseif($option['dinamic_rubrics']==0)
	{
		$res = $cms->query("select ID,PRICE from zed_price_category where CATEGORY='$cat'");
		if($cms->num_rows($res)>0)
		{
			$row = $cms->fetch_object($res);
			$change_all = false;
			$price_all = $row->PRICE;
		}
	}
	if($result!='') $str = "<div  id='message' >$result</div>";
	if($change_all)	$str_change="<div class='bloc_div'><input type='checkbox' name='tovar' value='1' title='ставится если будет содержать товары, а не подрубрики' /> содержит товары</div>";
	
	$str.="<form action='?modul=price&id=$id&cat=$cat' name='addcat' method='post'>
	<div id='button'><input type='hidden' name='tovar' value='$price_all' />
	<div class='bloc_div text_center'><b>Добавление рубрики</b></div>
	<div class='float_l'><input type='submit' name='addcat' value='Добавить' class='submit add'>&nbsp;&nbsp;&nbsp;</div>
	<div class='float_l w40p'>Название <input type='text' name='catname' class='w70p' value='' /></div>
	<div class='float_l w40p '>Ссылка <input type='text' name='encatname' class='w70p' value='' /></div>
	<div class='bloc_div help_message min_size'><b><i>ВАЖНО !!!</i></b> в <i>Ссылке</i> допустимы буквы латинского алфавита (в нижнем регистре [a,b,c, и т.д.]), цифры и символы '-'(минус) и '_'(подчеркивание). остальные символы включая пробел - <i>недопустимы</i>. Дополнительное условие  - внутри рубрики ссылки <i>не должны</i> быть одинаковыми!!!</div>
	$str_change
	</div></form>";
	
	return $str;
}

function form_edit_cat($id,$cat,$edit='')
{
	global $cms;
	if($edit!='') $_GET['edit'] = $edit;
	$result = '';
	$str = '';
	$doprub = '';
	if($id==$cat){$lev = 1; $level = 1;}
	else
	{
		$parent = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$cat'"));
		$lev = $parent->LEVEL;
	}
	$opt = $cms->query("select PROPERTY,VAL from zed_options where MODUL='price'");
	while ($op = $cms->fetch_object($opt))
	{
		$option[$op->PROPERTY] = $op->VAL;
	}

	if(isset($_POST['editcat']))
	{
		$name = trim($_POST['catname']);
		$enname = urlencode(trim($_POST['encatname']));
		$title = trim($_POST['tit']);
		$keywords = trim($_POST['key']);
		$description = trim($_POST['description']);
		$h = trim($_POST['h']);
		$seotext = trim($_POST['seotext']);
		//$description = '';
		//if(isset($_POST['description'])) $description = trim($_POST['description']);
		$price = $_POST['tovar'];
		//$typetov = trim($_POST['typetov']);
		if($name=='' || $enname=='' ) $result = "Не все поля заполнены";
		//if($level== 1 && $description=='') $result = "Не все поля заполнены";
		if($cms->num_rows($cms->query("select ID from zed_price_category where CATEGORY='$cat' and EN_NAME='$enname' and ID<>'{$_GET['edit']}'"))>0)
		$result.= "<br />Такая ссылка уже есть!!";
		if($result=='')
		{
			$cms->query("update zed_price_category set NAME='$name', EN_NAME='$enname', CATEGORY='$cat', PRICE='$price', TITLE='$title', KEYWORDS='$keywords', DESCRIPTION='$description', H='$h', SEOTEXT='$seotext' where ID='{$_GET['edit']}'");
			add_foto($_GET['edit'],'zed_price_category');
			$result = "Сохранено!";
			return show_price_cat($id,$cat,$result);
		}
	}
	else
	{
		$row = $cms->fetch_object($cms->query("select * from zed_price_category where ID='{$_GET['edit']}'"));
		$name = $row->NAME;
		$enname = $row->EN_NAME;
		$price = $row->PRICE;
		$title = $row->TITLE;
		$keywords = $row->KEYWORDS;
		$description = $row->DESCRIPTION;
		$h = $row->H;
		$seotext = $row->SEOTEXT;
		if ($price==1){$che='checked';}
		//$typetov = $row->TYPE;
		//		$description = $row->DESCRIPTION;
	}
	if(isset($_GET['delimg']))
	{
		$image = $cms->fetch_object($cms->query("select * from zed_image where ID='{$_GET['delimg']}'"));
					$delfile = str_replace("/zed/","",$image->PATH)."s".$image->NAME;
					if(is_file($delfile)) unlink($delfile);
					$delfile = str_replace("/zed/","",$image->PATH)."m".$image->NAME;
					if(is_file($delfile)) unlink($delfile);
					$delfile = str_replace("/zed/","",$image->PATH)."lm".$image->NAME;
					if(is_file($delfile)) unlink($delfile);
					$delfile = str_replace("/zed/","",$image->PATH)."l".$image->NAME;
					if(is_file($delfile)) unlink($delfile);
		$cms->query("delete from zed_image where ID='{$_GET['delimg']}'");
	}

	$change_all = true;
	$price_all = 0;
	if($option['maxrubrics_level']!='auto' && $option['maxrubrics_level']<=$lev)
	{
		$change_all = false;
		$price_all = 1;
	}
	elseif($option['dinamic_rubrics']==0)
	{
		$res = $cms->query("select ID,PRICE from zed_price_category where CATEGORY='$cat'");
		if($cms->num_rows($res)>0)
		{
			$row = $cms->fetch_object($res);
			$change_all = false;
			$price_all = $row->PRICE;
		}
	}

	$data['table']= "<form action=\"?modul=price&id=$id&cat=$cat&edit={$_GET['edit']}\" name='editcat' method='post' ENCTYPE=\"multipart/form-data\">
	<tr><td colspan=2><b>Редактирование рубрики</b></td></tr>";
	if($result!='') $data['table'].="<tr><td colspan=2><b>$result</b></td></tr>";
	$data['table'].="<tr><td width=10% >Название</td><td ><input type='text' name='catname' class='inp w70p' value='$name' /></td></tr>";
	$data['table'].="<tr><td >Ссылка</td><td ><input type='text' name='encatname' class='inp w70p' value='$enname' /></td></tr>";
	$data['table'].="<tr><td><b>Заголовок</b></td></td><td><input type='Text' name='tit' class='inp w70p' value='$title'></td></tr>";
	$data['table'].="<tr><td><b>Ключевые слова</b></td></td><td><input type='Text' name='key' class='inp w70p' value='$keywords'></td></tr>";
	$data['table'].="<tr><td><b>Описание</b></td></td><td><input type='Text' name='description' class='inp w70p' value='$description'></td></tr>";
	$data['table'].="<tr><td><b>H1</b></td></td><td><input type='Text' name='h' class='inp w70p' value='$h'></td></tr>";
	$data['table'].="<tr><td  colspan=2 valign=top class=bg1>Описание</td></tr>
	<tr><td colspan=2 class=bg1 align=left><textarea name='seotext' >$seotext</textarea>
<script>
CKEDITOR.replace( 'seotext',{
	'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
	'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
	'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
	'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
	'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
	'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
	</script></td></tr>";
	
	$imgres = $cms->query("select * from zed_image where CATEGORY='{$_GET['edit']}'  and `TABLE`='zed_price_category'  order by ORD");
	$i=1;
	$fl=$cms->num_rows($imgres);
	if($fl>0)
	{
	$img = $cms->fetch_object($imgres);
	$data['table'].="<tr><td><img src='{$img->PATH}s$img->NAME'></td><td><input type='file' name='image$i' /><input type='hidden' name='idimage$i' value='$img->ID'></td></tr>";
	}
	else 
	{
		$data['table'].="<tr><td>Добавить фаил</td><td>&nbsp; <input type='file' name='image$i' /></td></tr>";
	}
	
	if($change_all){
	$data['table'].="<tr><td colspan=2><input type=\"submit\" name=\"editcat\" value=\"Изменить\"><input type='hidden' name='tovar' value='$price_all' /></td></tr></form>	";}
	else {
	$data['table'].="<tr><td colspan=2><input type='checkbox' name='tovar' $che value='1' title='ставится если будет содержать товары, а не подрубрики'> содержит товары<br /><br /><input type=\"submit\" name=\"editcat\" value=\"Изменить\">
	<br /> <b><i>ВАЖНО !!!</i></b> в \"Ссылке\" допустимы буквы латинского алфавита (лучше в нижнем регистре [a,b,c, и т.д.]), цифры и символы \"-\"(минус) и \"_\"(подчеркивание). остальные символы включая пробел - недопустимы. Дополнительное условие  - внутри рубрики ссылки <i>не должны</i> быть одинаковыми!!!
	</td></tr>
	</form>";}
	
	return $cms->blockparse('table',$data,3);
}

function form_add_tov($id,$cat)
{
	global $cms;
	$result = '';
	$str = '';
	$result = '';
	$row = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$cat'"));
	if(isset($_POST['addtov']))
	{
		$name = trim($_POST['name']);
		$description = $_POST['description'];		
		if($name=='') {$result = "Не все поля заполнены";}
		if($result=='')
		{
			$roword=$cms->query("select ORD from zed_price where CATEGORY='$cat' order by ORD DESC limit 1");
			if($cms->num_rows($roword)>0)
			{
				$resord=$cms->fetch_object($roword);
				$ord=$resord->ORD+1;
			}
			else {$ord=1;}
			
			if($ord>1)
			{
				$res_th=$cms->fetch_object($cms->query("select ID,TYPE_TH from zed_price where CATEGORY='$cat' order by ID desc limit 1"));//
				$cms->query("insert into zed_price (ID, NAME, CATEGORY, DESCRIPTION, ORD,TYPE_TH) values ('null','$name','$cat','$description','$ord','$res_th->TYPE_TH')");
				$edit=$cms->insert_id();
				$res_price_th=$cms->query("select * from zed_price_th where ID_TOV='$res_th->ID'  order by TYPE");
				while ($row_price_th=$cms->fetch_object($res_price_th))
				{
					$cms->query("insert into zed_price_th(`NAME`, `TYPE`,`ID_TOV`)	values ('$row_price_th->NAME','$row_price_th->TYPE',$edit)");
				}
			}			
			else
			{
				$cms->query("insert into zed_price (ID, NAME, CATEGORY, DESCRIPTION, ORD) values ('null','$name','$cat','$description','$ord')");
				$edit=$cms->insert_id();
			}			
			add_foto($edit,'zed_price');
			return form_edit_tov($id,$cat,$edit);
		}
	}
	else{$name = '';}
	
	if(isset($result) && $result!=''){$str="<div  id='message' >$result</div>";}
	
	$str.= "<form action='?modul=price&id=$id&cat=$cat&addtov' name='addtov' method='post' enctype='multipart/form-data'>";
	$str.="<div id='button'><input type='submit' name='addtov' value='Добавить' class='submit add' ></div>";
		
	$data['table']="<tr><th colspan=2>Добавление товара</th></tr>";
	$data['table'].="<tr><td width=200>Наименование товара <b></b></td><td>&nbsp; <input type='text' name='name' value='$name' class='inp w90p'></td></tr>";
	$data['table'].="<tr><td  colspan=2 valign=top class=bg1>Описание</td></tr>
	<tr><td colspan=2 class=bg1 align=left><textarea name='description' ></textarea>
<script>
CKEDITOR.replace( 'description',{
	'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
	'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
	'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
	'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
	'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
	'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
	</script></td></tr>";	
	$data['table'].="<tr><td>Фото</td><td>&nbsp; <input type='file' name='foto' /></td></tr>";
	$data['table'].="</form>";
	
	$data['class']='tabl_def border';
	$str.=$cms->blockparse('table2',$data,3);
	return $str;
}

function form_edit_tov($id,$cat,$edit='')
{
	global $cms;//print_r($_POST);
	
	if(isset($_GET['up'])) up($_GET['idd'],$_GET['table']);
	if(isset($_GET['down'])) down($_GET['idd'],$_GET['table']);
	if(isset($_GET["delimg"])){del_img_id($_GET["delimg"]);}
	if(isset($_POST['del_price_nth']))	del_th($_POST['del_price_nth'],$_GET['edittov']);
	if($edit!=''){$_GET['edittov']=$edit;}
	
	if (isset($_POST['addselect_th']))
	{
		$nameselect = $_POST['nameselect_th'];
		//$res_th = $cms->fetch_object($cms->query("select ID from zed_select where 1 order by ID desc limit 1"));
		$typeselect = 'th';
		$res_th = $cms->query("select NAME from zed_select where NAME='$nameselect' order by NAME");
		$kol_th=$cms->num_rows($res_th);
		if($nameselect!='' || $kol_th==0)
		{$cms->query("insert into zed_select (ID, NAME, TYPE) values ('null','$nameselect','$typeselect')");}
	}
	if (isset($_POST['addnewth']))
	{
		if($_POST['th']!=0)
		{
			$th=$_POST['th'];
			$cms->query("update zed_price set  TYPE_TH=concat(TYPE_TH,':$th') where ID='{$_GET['edittov']}'");
		}
	}
	
	if(isset($_POST['save']))
	{
		add($_GET['edittov']);
		$name = trim($_POST['name']);
		$hid = $_POST['hidden'];
		$new = $_POST['new'];
		$description = $_POST['description'];
		$opisanie = $_POST['opisanie'];
		$button = $_POST['button'];
		if($name=='') $result = "Не все поля заполнены";
		if($result=='')
		{
			$cms->query("update zed_price set  NAME='$name', HIDDEN='$hid', NEW='$new', DESCRIPTION='$description', OPISANIE='$opisanie', BUTTON='$button' where ID='{$_GET['edittov']}'");
			$result=add_foto($_GET['edittov'],'zed_price');
			$result.="Сохранено";
		}
	}
	if(isset($_POST['edittov']))
	{
		add($_GET['edittov']);
		$name = trim($_POST['name']);
		$hid = $_POST['hidden'];
		$new = $_POST['new'];
		$description = $_POST['description'];
		$opisanie = $_POST['opisanie'];
		$button = $_POST['button'];
		if($name=='' ) $result = "Не все поля заполнены";
		if($result=='')
		{
			$cms->query("update zed_price set  NAME='$name',  HIDDEN='$hid', NEW='$new', DESCRIPTION='$description', OPISANIE='$opisanie', BUTTON='$button' where ID='{$_GET['edittov']}'");
			$result=add_foto($_GET['edittov'],'zed_price');
			$result.="Сохранено";
			if(isset($_POST['edittov'])){return show_price_tovar($id,$cat,$result);}
		}
	}
	else 
	{
		$row = $cms->fetch_object($cms->query("select * from zed_price where ID='{$_GET['edittov']}'"));
		$name = $row->NAME;
		$description = $row->DESCRIPTION;
		$opisanie = $row->OPISANIE;
		$typr_th = $row->TYPE_TH;
		//if($row->NEW==0){$new="<input type='checkbox' name='new' value='1'>Отобразить товар на главной";}
		//else {$new="<input type='checkbox' name='new' value='1' checked >Отобразить товар на главной";}
		if($row->HIDDEN==0){$hidden="<input type='checkbox' name='hidden' value='1'>Скрыть товар";}
		else {$hidden="<input type='checkbox' name='hidden' value='1' checked >Скрыть товар";}
		/*if($row->BUTTON==0){$checked_0='checked';$checked_1=$checked_2='';}
		if($row->BUTTON==1){$checked_1='checked';$checked_0=$checked_2='';}
		if($row->BUTTON==2){$checked_2='checked';$checked_0=$checked_1='';}/**/
	}	

	if(isset($_POST['saveth'])){add($_GET['edittov']);}
	
//////////////////		
	$data['table']="<form action=\"?modul=price&id=$id&cat=$cat&edittov={$_GET['edittov']}\" name='edittov' method='post' enctype='multipart/form-data'>
	<tr><th colspan=2>Редактирование товара</th></tr>";
	$data['table'].="<tr><td width=200>Наименование товара</td><td>&nbsp; <input type='text' name='name' class='w40p'  value='$name'> $new $hidden</td></tr>";
	
	//$data['table'].="<tr><td>Вид конопки</td><td>&nbsp; <input type='radio'  name='button' value='0'  $checked_0 />Узнать цену <input type='radio'  name='button' value='1' $checked_1 />Заказать <input type='radio'  name='button' value='2' $checked_2 />Не отображать кнопку</td></tr>";
	
	$data['table'].="<tr><td>Добавить новую характеристику</td><td>Название характеристики &nbsp;<input type='text' name='nameselect_th' value='' class='inp w45p'> &nbsp; <input type='submit' name='addselect_th' value='Добавить характеристику'></td></tr>";
	$res_th = $cms->query("select ID,NAME from zed_select where TYPE='th' order by NAME");
	while ($select_th = $cms->fetch_object($res_th))
	{
		$th_select.="<option  value='$select_th->ID' >&nbsp; $select_th->NAME</option>";
	}
	$data['table'].="<tr><td>Добавить характеристику</td><td><select name='th'><option  value='0' >&nbsp; Выбрать</option>$th_select</select> &nbsp; <input type='submit' name='addnewth' value='Добавить характеристику'></td></tr>";
	
	$mastype=explode(':',$typr_th);
	foreach ($mastype as $key=>$value)
	{		
		if($key==0)	{continue;}
		else 
		{
			$name_th = $cms->fetch_object($cms->query("select ID,NAME from zed_select where ID='$value'"));
			$num=$name_th->ID;
			if(isset($_POST["addnew$num"]))
			{
				add($_GET['edittov']);
				$strnew[$num]="<div>
				<input type='hidden' name='ids[$num][]' >
				<input type='text' name='name_th[$num][]' style='width: 200px;' value=''/>
				</div>";//<input type='text' name='kol[$num][]' style='width: 30px;' value=''/>
			}
			$str_th='';
			$res_price_th=$cms->query("select * from zed_price_th where ID_TOV={$_GET['edittov']} and TYPE=$num ");
			while ($row_price_th=$cms->fetch_object($res_price_th))
			{
				
				if(isset($_POST["del_price_th"]))
				{
					$th_del=$_POST["del_price_th"];
					if($th_del[$num][$row_price_th->ID]!=''){$cms->query("delete from zed_price_th where ID=$row_price_th->ID");}
					else
					$str_th.="<div>
					<input type='text' name='name_th[$num][]' style='width: 200px;' value='$row_price_th->NAME'/>
					<input type='submit' name='del_price_th[$num][$row_price_th->ID]' value='Удалить' >
					<input type='hidden' name='ids[$num][]' value='$row_price_th->ID'></div>";
				}
				else 
				$str_th.="<div>
				<input type='text' name='name_th[$num][]' style='width: 200px;' value='$row_price_th->NAME'/>
				<input type='submit' name='del_price_th[$num][$row_price_th->ID]' value='Удалить' >
				<input type='hidden' name='ids[$num][]' value='$row_price_th->ID'></div>";
			}
			
			$data['table'].="<tr><td>$name_th->NAME<br><input type='submit' name='del_price_nth[$num]' value='Удалить' ></td><td>$str_th $strnew[$num]<input type='submit' name='addnew$num' value='Добавить еще строку' ></td></tr>";
		}
	}
	$data['table'].="<tr><td><input type='submit' name='saveth' value='Сохранить характеристики' ></td><td></td></tr>";
/////////////////////////
	$data['table'].="<tr><td  colspan=2 valign=top class=bg1>Описание товара</td></tr>
	<tr><td colspan=2 class=bg1 align=left><textarea name='description' >$description</textarea>
<script>
CKEDITOR.replace( 'description',{
	'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
	'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
	'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
	'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
	'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
	'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
	</script></td></tr>";

	$imgres = $cms->query("select * from zed_image where CATEGORY='{$_GET['edittov']}'  and `TABLE`='zed_price'  order by ORD");
	$fl=$cms->num_rows($imgres);
	for($i=1;$i<$fl+1;$i++)
	{
		$img = $cms->fetch_object($imgres);
		$data['table'].="<tr><td><img src='{$img->PATH}s$img->NAME'></td><td><input type='file' name='image$i' /><input type='hidden' name='idimage$i' value='$img->ID'> 
		<a href='/zed/?modul=price&id=$id&cat=$cat&edittov={$_GET['edittov']}&delimg=$img->ID' onclick=\"return confirm('Удалить?')\">удалить</a></td></tr>";
	}
	if (isset($num))
	{
		for($j=$num;$j>0;$j--)
		{
			if($i<5){
			$data['table'].="<tr><td>Добавить фаил</td><td>&nbsp; <input type='file' name='image$i' /></td></tr>";
			$i++;}
		}
	}
	if($i<5){$data['table'].="<tr><td>Добавить фаил</td><td>&nbsp; <input type='file' name='image$i' /></td></tr><tr><td>Добавить форму для файла</td><td><input type='submit' name='add_file' value='добавить форму для файла'><input type='hidden' name='add_file_num' value='$i'><input type='hidden' name='add_file_fl' value='$fl'></td></tr>";}
	
	
	$data['table'].="<tr><td colspan=2><input type='submit' name='save' value='Сохранить'><input type='submit' name='edittov' value='Сохранить и закрыть'></td></tr></form>";
	if($result!='') $str = $cms->message($result);
	return $str.$cms->blockparse('table',$data,3);
}

function show_price_tovar($id,$cat,$result)
{
	global $cms;
	$str='';
	$row = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$cat'"));
	if(isset($_GET['upper'])) upper($_GET['idd'],$_GET['table']);
	if(isset($_GET['up'])) up($_GET['idd'],$_GET['table']);
	if(isset($_GET['down'])) down($_GET['idd'],$_GET['table']);
	if(isset($_GET['deltov']))
	{
		del_img($_GET['deltov'],$_GET['table']);
		/*$rowd=$cms->query("select * from zed_price_karteg where CATEGORY='{$_GET['deltov']}'");
		while ($rezd = $cms->fetch_object($rowd))
		{
			$cms->query("delete from zed_price_karteg where ID='$rezd->ID'");
		}*/
		$cms->query("delete from {$_GET['table']} where ID='{$_GET['deltov']}'");
	}
	$res = $cms->query("select * from zed_price where CATEGORY='$cat' order by ORD");// and HIDDEN=0
	$data['table']="<tr><th width=15%><div class='heder_bg_l'>Статус кнопки</div></th><th width=70%><div class='heder_bg_c'>Наименование товара</div></th><th class='oper' width=15%><div class='heder_bg_r'>Действия</div></th></tr>";
	
	if($cms->num_rows($res)==0)
	$data['table']="<tr><th>Нет товаров</th></tr>";
	while ($row = $cms->fetch_object($res))
	{
		if($row->BUTTON==0){$str_button='Узнать цену';}
		if($row->BUTTON==1){$str_button='Заказать';}
		if($row->BUTTON==2){$str_button='Не отображать кнопку';}
		if($fl==1){$class="class='table_tr_bg'";$fl=0;}
		else {$class="";$fl=1;}
		$func = "<a href='?modul=price&id=$id&cat=$cat&edittov=$row->ID' title='Редактировать'><img src='templates/default/images/edit.png'></a>
<a href='?modul=price&id=$id&cat=$cat&deltov=$row->ID&table=zed_price'  onclick='return confirm('Удалить?')' title='Удалить'><img src='templates/default/images/del.png'></a>
<a href='?modul=price&up&id=$id&cat=$cat&idd=$row->ID&table=zed_price' title='Поднять'><img src='/zed/templates/default/images/up.png' /></a>
<a href='?modul=price&down&id=$id&cat=$cat&idd=$row->ID&table=zed_price' title='Опустить'><img src='/zed/templates/default/images/down.png' /></a>";
		if($row->HIDDEN!=0){$data['table'].="<tr $class><td>$row->ART</td><td><i>$row->NAME</i></td>";}
		else $data['table'].="<tr $class><td>$str_button</td><td>$row->NAME</td>";
		$data['table'].="<td>$func</td></tr>";
	}

	if($result!='') $str = "<div  id='message' >$result</div>";
	$str.="<div id='button'><a class='url_button_add' href='?modul=price&id=$id&cat=$cat&addtov'>Добавление товара</a></div>";
	$data['class']='tabl_def';
	@$str.= $cms->blockparse('table2',$data,3);	
	return $str;
}

function show_price_cat($id,$cat,$result)
{
	global $cms;
	if(isset($_GET['up'])) up($_GET['idd'],$_GET['table']);
	if(isset($_GET['down'])) down($_GET['idd'],$_GET['table']);
	$form = form_add_cat($id,$cat);
	$res = $cms->query("select * from zed_price_category where CATEGORY='$cat' order by ORD");
	$data['table']="<tr><th  width=8%><div class='heder_bg_l'>Ссылка</div></th><th width=75%><div class='heder_bg_c'>Раздел <i>[кол. записей]</i></div></th><th  width=17%><div class='heder_bg_r'>Действия</div></th></tr>";
	//if($result!='') $data['table'].="<tr><td colspan=5><i>$result</i></td></tr>";
	if($cms->num_rows($res)==0)
	$data['table']="<tr><th>Нет рубрик</th></tr>";
	while ($row = $cms->fetch_object($res))
	{
		if($fl==1){$class="class='table_tr_bg'";$fl=0;}
		else {$class="";$fl=1;}
		$kol = '';
		$func = "
		<a href='?modul=price&id=$id&cat=$row->ID' title='Просмотр на сайте'><img src=templates/default/images/show.png></a>
        <a href='?modul=price&id=$id&cat=$cat&edit=$row->ID' title='Редактировать'><img src=templates/default/images/edit.png></a>
        <a href='?modul=price&id=$id&cat=$cat&del=$row->ID' title='Удалить' onclick=\"return confirm('Удалить?')\"><img src=templates/default/images/del.png></a>
		<a href='?modul=price&up&id=$id&cat=$cat&idd=$row->ID&table=zed_price_category' title=\"Поднять\"><img src='/zed/templates/default/images/up.png' /></a>
	<a href='?modul=price&down&id=$id&cat=$cat&idd=$row->ID&table=zed_price_category' title=\"Опустить\"><img src='/zed/templates/default/images/down.png' /></a>";
		if($row->PRICE!=0) 
		{
			$num=$cms->num_rows($cms->query("select ID from zed_price where CATEGORY='$row->ID'"));
			$numh=$cms->num_rows($cms->query("select ID from zed_price where CATEGORY='$row->ID' and HIDDEN=1"));
			$kol = " <b><i>[$num]</i></b> - <b><em>[$numh]</em></b>";
		}
		$data['table'].="<tr $class><td>$row->EN_NAME</td><td><a href=\"?modul=price&id=$id&cat=$row->ID\">$row->NAME$kol</a></td><td>$func</td></tr>";
	}
	if($result!='') $str = "<div  id='message' >$result</div>";
	$str.=$form;
	$data['class']='tabl_def';
	@$str.= $cms->blockparse('table2',$data,3);
	
	return $str;
}

function show_price($id)
{
	global $cms;
	$cat = $id;
	if(isset($_GET['cat'])) $cat = $_GET['cat'];
	$result = '';
	// проверка на удаление рубрики
	if(isset($_GET['del']))
	{
		$rub = $cms->fetch_object($cms->query("select ID,PRICE from zed_price_category where ID='{$_GET['del']}'"));
		if($rub->PRICE)
		{
			$res = $cms->query("select ID from zed_price where CATEGORY='{$_GET['del']}'");
			if($cms->num_rows($res)>0) $result = "Рубрика содержит товары !! Удалите их !!";
		}
		else
		{
			$res = $cms->query("select ID from zed_price_category where CATEGORY='{$_GET['del']}'");
			if($cms->num_rows($res)>0) $result = "Рубрика содержит подрубрики !! Удалите их !!";
		}
		if($result=='')
		{
			$cms->query("delete from zed_price_category where ID='{$_GET['del']}'");
			del_img($_GET['del'],'zed_price_category');
			$result = "Рубрика удалена !!";
		}
	}/*
	$tec_id_down='28';
	$tec_id_up='44';
	$row1 = $cms->fetch_object($cms->query("select SEOTEXT from zed_price_category where ID='$tec_id_down'"));
	$cms->query("update zed_price_category set SEOTEXT='$row1->SEOTEXT' where ID='$tec_id_up'");
		
		$res2 =$cms->query("select * from zed_price where CATEGORY='$tec_id_down'");
		while ($row2 = $cms->fetch_object($res2))
		{
			$roword=$cms->query("select ORD from zed_price where CATEGORY='$row1->ID' order by ORD DESC limit 1");
			if($cms->num_rows($roword)>0)
			{
				$resord=$cms->fetch_object($roword);
				$ord=$resord->ORD+1;
			}
			else {$ord=1;}

			$cms->query("insert into zed_price (ID, NAME, CATEGORY, DESCRIPTION, OPISANIE, ORD,TYPE_TH) values ('null','$row2->NAME','$tec_id_up','$row2->DESCRIPTION','$row2->OPISANIE','$ord','$row2->TYPE_TH')");
			$edit=$cms->insert_id();
			$res_price_th=$cms->query("select * from zed_price_th where ID_TOV='$row2->ID'  order by TYPE");
			while ($row_price_th=$cms->fetch_object($res_price_th))
			{
				$cms->query("insert into zed_price_th(`NAME`, `TYPE`,`ID_TOV`)	values ('$row_price_th->NAME','$row_price_th->TYPE',$edit)");
			}
			$res_price_s=$cms->query("select * from zed_image where `CATEGORY`=$row2->ID and `TABLE`='zed_price' order by ORD");
			while ($row_price_s=$cms->fetch_object($res_price_s))
			{
					$cms->query("insert into zed_image(`ID`,`NAME`, `CATEGORY`, `PATH`, W, H, ORD, `TABLE`)
					values (null,'','$edit','/zed/photo/','$row_price_s->W','$row_price_s->H',$row_price_s->ORD,'zed_price')");
					$idimg = $cms->insert_id();
					$file_name="{$idimg}_$edit.png";
					copy("photo/s$row_price_s->NAME","photo/s$file_name");
					$resultq=$cms->query("update zed_image set `NAME` = '$file_name' where ID='$idimg'");
			}	
		}/**/
	
	// конец проверки на удаление
	// проверка на редактирование
	if(isset($_GET['edit'])) return form_edit_cat($id,$cat);
	if(isset($_GET['edittov'])) return form_edit_tov($id,$cat,$_GET['edittov']);
	// конец проверки на удаление
	// проверка на добавление
	if(isset($_GET['addtov']))  return form_add_tov($id,$cat);
	// конец проверки на добавление
	$rub = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$cat'"));
	if(@$rub->PRICE==0)	$str = show_price_cat($id,$cat,$result);
	else {$str = show_price_tovar($id,$cat,$result);}
	return $str;
}

$zed_content=show_price($_GET['id']);
$zed_navi=gen_price_menu($_GET['id']);
$zed_adding='<script type="text/javascript" src="/zed/modules/ckeditor/ckeditor.js"></script>';
?>