<?
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
function add_cat_form($parent)
{
	global $cms;
	$mod=$cms->get_modules();
	/*include ("modules/fckeditor/fckeditor.php");
	$oFCK = new FCKeditor('full');*/
	$newsdat['table']="
<form action=?modul=category&action=add&parent=$parent method=post name=cat_add_form>
<tr><td align=left width=5%><b>Название</b></td><td align=left><input type=text size=20 name=cat_name></td></tr>
<tr><td align=left><b>Урл</b></td><td align=left><input type=text size=20 name=en_name></td></tr>
<tr><td align=left><b>Меню</b></td><td align=left><input type=checkbox name=visible value=1></td></tr>
<tr><td align=left><b>Навигация на страницу</b></td><td align=left><input type=checkbox name=navigate value=1 checked></td></tr>
<tr><td><b>Заголовок</b></td></td><td align=left><input type=\"Text\" name=\"name\" size=50></td></tr>
<tr><td><b>Ключевые слова</b></td></td><td align=left><input type=\"Text\" name=\"key\" size=50></td></tr>
<tr><td><b>Описание</b></td></td><td align=left><input type=\"Text\" name=\"des\" size=50></td></tr>
<tr><td align=left><b>Рубрика</b></td><td align=left>".$mod."</td></tr>
<tr><td colspan=2><textarea name='full' ></textarea></td></tr>
	<script>
	CKEDITOR.replace( 'full',{
		'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
		'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
		'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
		'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
		'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
		'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
		</script>
<tr><td colspan=2><input type=submit name=go value=\"Добавить\"></td></tr>";
	$str=$cms->blockparse('table',$newsdat,3);
	return $str;
}

function edit_cat_form($id)
{
	global $cms, $zed_navi;
	$mod=$cms->get_modules();
	/*include ("modules/fckeditor/fckeditor.php");
	$oFCK = new FCKeditor('full');*/
	$row=$cms->fetch_object($cms->query("select * from zed_category where ID='$id'"));
	//$oFCK->Value=$row->DES;
	//$oFCK->Clients_Folder = "qwerty";
	$vis="";
	$nav="";
	if($row->VISIBLE) {$vis="checked";}
	if($row->NAVIGATE) {$nav="checked";}
	$newsdat['table']="
<form action=?modul=category&action=edited&parent=$row->PARENT&id=$id method=post name=cat_add_form>
<tr><td align=left width=5%>Название</td><td align=left><input type=text size=20 name=cat_name value=\"$row->NAME\"></td></tr>
<tr><td>Урл в браузере</td><td align=left><input type=text size=20 name=en_name value=\"$row->EN_NAME\"></td></tr>
<tr><td>Меню</td><td align=left><input type=checkbox name=visible value=1 $vis></td></tr>
<tr><td>Навигация на страницу</td><td align=left><input type=checkbox name=navigate value=1 $nav></td></tr>
<tr><td><b>Заголовок</b></td></td><td align=left><input type=\"Text\" name=\"name\" size=100 value='$row->TITLE'></td></tr>
<tr><td><b>Ключевые слова</b></td></td><td align=left><input type=\"Text\" name=\"key\" size=50 value=\"$row->KEYWORDS\"></td></tr>
<tr bgcolor=#fafafa><td><b>Описание</b></td></td><td align=left><input type=\"Text\" name=\"des\" size=50 value=\"$row->DESCRIPTION\"></td></tr>
<tr><td><b>H1</b></td></td><td align=left><input type=\"Text\" name=\"h\" size=50 value='$row->H'></td></tr>
<tr bgcolor=#fafafa><td><b>Сеотекст</b></td></td><td align=left><input type=\"Text\" name=\"seotext\" size=100 value=\"$row->SEOTEXT\"></td></tr>
<tr><td colspan=2><textarea name='full' >$row->DES</textarea></td></tr>
	<script>
	CKEDITOR.replace( 'full',{
		'filebrowserBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=file',
		'filebrowserImageBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=image',
		'filebrowserFlashBrowseUrl':'/zed/modules/ckeditor/kcfinder/browse.php?type=flash',
		'filebrowserUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=file',
		'filebrowserImageUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=image',
		'filebrowserFlashUploadUrl':'/zed/modules/ckeditor/kcfinder/upload.php?type=flash'});
		</script>
<tr><td colspan=2><input type=submit name=go value=\"Редактировать\"></td></tr>";
	$str=$cms->blockparse('table',$newsdat,3);
	$zed_navi=$cms->navi($row->PARENT);
	return $str;
}

function add_cat($en_name,$catname,$type,$parent,$des,$name,$key,$description,$vis,$nav)
{
	global $cms, $zed_navi;
	$zed_navi=$cms->navi($parent);
	$resss=$cms->fetch_object($cms->query("select ORD from zed_category where PARENT='$parent' order by ORD DESC limit 1"));
	$ord=$resss->ORD+1;
	$res=$cms->query("select ID from zed_category where NAME='$catname' AND PARENT='$parent'");
	$c=$cms->num_rows($res);
	if(!$c)
	{$result=$cms->query("insert into zed_category (ID,NAME,EN_NAME,PARENT,DES,TYPE,TITLE,KEYWORDS,DESCRIPTION,VISIBLE,NAVIGATE,ORD) values ('null','$catname','$en_name','$parent','$des','$type','$name','$key','$description','$vis','$nav','$ord')"); }
	else {$zed_navi.="В этой рубрике уже существует запись с таким именем : ".$name;  }
	if($result) {$zed_navi.=" Добавлено : $name"; }
	return show_category($parent);
}

function edit_cat($id,$en_name,$name,$des,$title,$key,$desc,$h,$seotext,$vis,$nav)
{
	global $cms, $ZED, $zed_navi;
	$zed_navi=$cms->navi($id);
	$res=$cms->query("update zed_category set EN_NAME='$en_name', NAME='$name', DES='$des', TITLE='$title', KEYWORDS='$key', DESCRIPTION='$desc', H='$h', SEOTEXT='$seotext', VISIBLE='$vis', NAVIGATE='$nav'  where ID='$id'");
	$zed_navi=$cms->navi($id);
	return show_des($id);
}

function show_des($level)
{
	global $cms;
	$str="";

	$sql="select * from zed_category where ID=$level";

	$result=$cms->query($sql);
	$newsdat['table']="";
	$str.=$cms->blockparse('news_category',$newsdat,3);
	$row=$cms->fetch_object($result);
	$vis="НЕТ";
	$nav="НЕТ";
	if($row->VISIBLE) {$vis="ДА";}
	if($row->NAVIGATE) {$nav="ДА";}
	$newsdat['table'].="<tr><td width=5%><b>Название</b></td><td>$row->NAME</td></tr>
	<tr><td><b>урл</b></td><td>$row->EN_NAME</td></tr>
	<tr><td><b>Пункт меню</b></td><td>$vis</td></tr>
	<tr><td><b>Навигация</b></td><td>$nav</td></tr>
	<tr><td><b>Заголовок</b></td><td>$row->TITLE</td></tr>
	<tr><td><b>Ключевые слова</b></td><td>$row->KEYWORDS</td></tr>
	<tr><td><b>Описание страницы</b></td><td>$row->DESCRIPTION</td></tr>
	<tr><td><b>Описание рубрики</b></td><td>$row->DES</td></tr>";
	$str=$cms->blockparse('table',$newsdat,3);
	return $str;
}

function Check_Moder_News($id)
{
	global $cms;
	$str="";
	$row = $cms->fetch_object($cms->query("select TYPE from zed_category where PARENT='$id' limit 1"));
	if($row->TYPE!="news") return "";
	if($_SESSION['rank']>=80 || $_SESSION['userid']==424)
	{
		$num = $cms->num_rows($cms->query("select ID from zed_news_request"));
		$str = "<a href=?modul=category&action=open&id=$id&type=moder>На модерацию ($num) </a> : ";
	}
	return $str;
}

function show_category($level)
{
	global $cms;
	$str="";
	$num = "";
	$get = "";
	$fl=0;
	if($level==0)
	{
		if(isset($_GET['up'])) up($_GET['idd'],$_GET['table'],'PARENT',$level);
		if(isset($_GET['down'])) down($_GET['idd'],$_GET['table'],'PARENT',$level);
		$sql="select * from zed_category where PARENT='0' order by ORD";
	}
	else
	{
		if(isset($_GET['up'])) up($_GET['idd'],$_GET['table'],'PARENT',$level);
		if(isset($_GET['down'])) down($_GET['idd'],$_GET['table'],'PARENT',$level);
		$sql="select * from zed_category where PARENT='$level' order by ORD";/**/
	}
	$result=$cms->query($sql);
	if($cms->checklevel("80")) 
	{
		$str.="<div id='button'><a class='url_button_add' href=?modul=category&action=a&id=$level>добавить категорию</a></div>";
		$newsdat['table']="<tr><th width=30><div class='heder_bg_l'>ID</div></th><th><div class='heder_bg_c'>НАЗВАНИЕ</div><th width=140><div class='heder_bg_c'>ВИД МОДУЛЯ</div><th width=178><div class='heder_bg_r'>ДЕЙСТВИЯ</div></th></tr>";
	}
	else
	{
		$newsdat['table']="<tr><th><div class='heder_bg_l'>НАЗВАНИЕ</div></th><th width=178><div class='heder_bg_r'>ДЕЙСТВИЯ</div></th></tr>";
	}
	//$str.=$cms->blockparse('news_category',$newsdat,3);
	while($row=$cms->fetch_object($result))
	{
		if($fl==1){$class="class='table_tr_bg'";$fl=0;}
		else {$class="";$fl=1;}
		
		$vis="";
		if($row->VISIBLE) {$vis="<b>M</b> |";}
		$nav="";
		if($row->NAVIGATE) {$nav="<b>N</b> | ";}
		
		$name="<a href=?modul=$row->TYPE&action=open&id=$row->ID>$row->NAME$num</a>";
		$func="
		<a href=?modul=category&action=showdes&id=$row->ID title='Просмотр на сайте'><img src=templates/default/images/show.png></a>
        <a href=?modul=$row->TYPE&action=open&id=$row->ID title='Редактировать'><img src=templates/default/images/edit.png></a>
        <a href=?modul=category&action=del&id=$row->ID&parent=$level title='Удалить' onclick=\"return confirm('Удалить?')\"><img src=templates/default/images/del.png></a>
		<a href=?modul=category&action=edit&id=$row->ID title='Настройки'><img src=templates/default/images/settings.png></a>
<a href=?modul=category&action=open&up&id=$level&idd=$row->ID&table=zed_category title='Поднять'><img src='/zed/templates/default/images/up.png' /></a>
<a href=?modul=category&action=open&down&id=$level&idd=$row->ID&table=zed_category title='Опустить'><img src='/zed/templates/default/images/down.png' /></a>";
		if($cms->checklevel("80"))
		{
			$newsdat['table'].="<tr $class><td>$row->ID</td><td>$nav$vis $name</td><td>$row->TYPE</td><td>$func</td></tr>";
		}
		else $newsdat['table'].="<tr $class><td>$nav$vis $name</td><td>$func</td></tr>";
	}
	$newsdat['class']='tabl_def';
	$str.=$cms->blockparse('table2',$newsdat,3);
	return $str;
}

function del_category($id)
{
	global $cms;
	$err=0;
	$result=$cms->query("select TYPE,PARENT from zed_category where ID='$id'");
	$row=$cms->fetch_object($result);
	switch ($row->TYPE)
	{
		case "news":
		$res=$cms->check_if_exist("$id","zed_news","CATEGORY");
		if($res>0) {$err=1; break;}
		break;
		case "category":
		$res=$cms->check_if_exist("$id","zed_category","PARENT");
		if($res>0) {$err=1; break;}
		break;
		case "page":
		$res=$cms->check_if_exist("$id","zed_pages","ID");
		if($res>0) {$err=1; break;}
		break;
		case "photo":
		$res=$cms->check_if_exist("$id","zed_photo","CATEGORY");
		if($res>0) {$err=1; break;}
		break;
		/*
		case "vote":
		$res=$cms->check_if_exist("$id","zedcms_vote_questions","qid");
		if($res>0) {$err=1; break;}
		break;/**/
	}
	if($err) {$str=$cms->message("Данная категория содержит записи, удалить не возможно!");}
	else {$cms->query("delete from zed_category where ID='$id'"); $str=$cms->message("Удалено"); }
	$str.=show_category($row->PARENT);
	return $str;
}

############################################

if(isset($_GET['action']))
{
	$zed_adding='<script type="text/javascript" src="/zed/modules/ckeditor/ckeditor.js"></script>';
	switch($_GET['action'])
	{
		case "add":
		$zed_content=add_cat($_POST['en_name'],$_POST['cat_name'],$_POST['mod'],$_GET['parent'],@$_POST['full'],$_POST['name'],$_POST['key'],$_POST['des'],@$_POST['visible'],@$_POST['navigate']);
		break;
		case "a":
		$zed_navi=$cms->navi($_GET['id']);
		$zed_content=add_cat_form($_GET['id']);
		break;
		case "open":
		//$moder = Check_Moder_News($_GET['id']);
		$zed_navi=$cms->navi($_GET['id']);//.$moder;
		$zed_content=show_category($_GET['id']);
		break;
		case "showdes":
		$zed_navi=$cms->navi($_GET['id']);
		$zed_content=show_des($_GET['id']);
		break;
		case "del":
		$zed_navi=$cms->navi($_GET['parent']);
		$zed_content=del_category($_GET['id']);
		break;
		case "edit":
		$zed_content=edit_cat_form($_GET['id']);
		break;
		case "edited":
		$zed_content=edit_cat($_GET['id'],$_POST['en_name'],$_POST['cat_name'],$_POST['full'],$_POST['name'], $_POST['key'], $_POST['des'], $_POST['h'], $_POST['seotext'], @$_POST['visible'],@$_POST['navigate']);
		break;
	}
}
else
{
	$zed_navi=$cms->navi(0);
	$zed_content=show_category(0);
}
?>