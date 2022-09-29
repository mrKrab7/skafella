<?
function get_select($id)
{
	global $cms;
	$catname="<select name='cat' onchange=\"window.navigate('?modul=main&cat='+this.value)\"><option value='0'>Главная</option>";
	$result=$cms->query("select ID,NAME,TYPE,PARENT from zed_category  order by TYPE");
	while($row=$cms->fetch_object($result))
	{
		$sele  = '';
		if($row->PARENT!="0" && $row->PARENT!="-1" )
		{
			$row2 = $cms->fetch_object($cms->query("select NAME from zed_category where ID='$row->PARENT'"));
			$parentt="$row2->NAME /";
		}
		else $parentt="";
		if($id == $row->ID) $sele = "selected";
		$catname.="<option value='$row->ID' $sele>$parentt$row->NAME</option>";
	}
	$catname.="</select>";
	return $catname;
}
function show_site_menu($id)
{
	global $cms;
	$parent = 0;

	if(isset($_GET['par'])) $parent = $_GET['par'];
	$res = $cms->query("select * from zed_site_menu where WHER='$id' and PARENT='$parent' order by ORD");
	if($id==0) $where = "главная";
	else
	{
		$cat = $cms->fetch_object($cms->query("select NAME from zed_category where ID='$id'"));
		$where = $cat->NAME;
	}
	$newsdat['table']="<tr><th>Рубрика</th><th width=90%>Где</th><th colspan=3 >операции</th></tr>";
	while($row = $cms->fetch_object($res))
	{
		$func="<a href='?modul=main&action=del&id=$row->ID&par=$parent&cat=$id' title='Удалить' onclick=\"return confirm('Удалить?')\"><img src='/zed/templates/default/images/del.png' /></a>";
		$ord1="<a href='?modul=main&par=$parent&cat=$id&id=$row->ID&action=up' title='Поднять'><img src='/zed/templates/default/images/up.png' /></a>";
         $ord2="<a href='?modul=main&action=down&id=$row->ID&par=$parent' title='Опустить'><img src='/zed/templates/default/images/down.png' /></a>";
        if($row->WHO==0) $rr->NAME  = "Главная";
		else $rr = $cms->fetch_object($cms->query("select NAME from zed_category where ID='$row->WHO'"));
		$newsdat['table'].="<tr><td><a class='ext' href='?modul=main&cat=$id&par=$row->ID' title='раздел подменю рубрики'>$rr->NAME</a></td><td>$where</td><td>$ord1</td><td>$ord2</td><td>$func</td></tr>";

	}
	$str = $cms->blockparse('table',$newsdat,3);
	$str.="<center><a href=?modul=main&cat=$id&par=$parent&action=add><b>добавить пункт меню</b></a></center>";
	return $str;
}

function up($id)
{
	global $cms;
	$r1=$cms->query("select * from zed_site_menu where ID='$id'");
	$tpl=$cms->fetch_object($r1);
	$r2=$cms->query("select * from zed_site_menu where WHER='$tpl->WHER' and PARENT='$tpl->PARENT' and ORD < $tpl->ORD  order by ORD desc");
	if($cms->num_rows($r2)>0)
	{
		$rn1=$cms->fetch_object($r2);
		$cms->query("UPDATE zed_site_menu SET ORD='$rn1->ORD' where ID='$id'");
		$cms->query("UPDATE zed_site_menu SET ORD='$tpl->ORD' where ID='$rn1->ID'");
	}
}
function down($id)
{
	global $cms;
	$r1=$cms->query("select * from zed_site_menu where ID='$id'");
	$tpl=$cms->fetch_object($r1);
	$r2=$cms->query("select * from zed_site_menu where WHER='$tpl->WHER' and PARENT='$tpl->PARENT' and ORD > $tpl->ORD order by ORD");
	if($cms->num_rows($r2)>0)
	{
		$rn1=$cms->fetch_object($r2);
		$cms->query("UPDATE zed_site_menu SET ORD='$rn1->ORD' where ID='$id'");
		$cms->query("UPDATE zed_site_menu SET ORD='$tpl->ORD' where ID='$rn1->ID'");
	}
}

function del_admin_menu($id)
{
	global $cms;
	$cms->query("delete from zed_site_menu where ID='$id'");
}

function get_check($cat,$par)
{
	global $cms;
	$str='';
	$categ = $cms->query("select ID,NAME,PARENT from zed_category order by PARENT, NAME");
	$res=$cms->query("select WHO from zed_site_menu where WHER='$cat' and PARENT='$par' order by ORD");
	while ($row = $cms->fetch_object($res))  $data[] = $row->WHO;
	$str.= "<div class='fl45p10'>";
	$kol = ceil(($cms->num_rows($categ))/2);
	$dis = "";
	$ckol=0;
	$i=1;
	if(isset($data) && is_array($data) && in_array(0,$data)) $dis = " disabled"; ;
	$str.="<input type='checkbox' name='who[]' value='0'$dis> Главная<br />\n";
	while ($row = $cms->fetch_object($categ))
	{
		//echo "$rtbl->TBL - $ckol - $i - $kol<br>";
		$dis = "";
		if(isset($data) && is_array($data) && in_array($row->ID,$data)) $dis = " disabled"; ;
		$str.="<input type='checkbox' name='who[]' value='$row->ID'$dis> $row->NAME($row->PARENT)<br />\n";
		$i++;
		$ckol++;
		if($i>=$kol)
		{
			if($ckol<$kol) $str.="</div><div class='fl45p10'>";
			$i=0;
		}
	}
	$str.="</div>";
	return $str;
}
function add_site_menu($cat,$par)
{
	global $cms;
	if($par!=0)
	{
		//WHO
		$row = $cms->fetch_object($cms->query("select WHO from zed_site_menu where ID='$par'"));
		$row = $cms->fetch_object($cms->query("select NAME from zed_category where ID='$row->WHO'"));
		$str = $cms->message("Добавление подпунктов меню для страницы '$row->NAME'");
		
	}
	elseif($cat == 0) $str = $cms->message("Добавление пунктов меню для страницы 'главная'");
	else 
	{
		$row = $cms->fetch_object($cms->query("select NAME from zed_category where ID='$cat'"));
		 $str = $cms->message("Добавление пунктов меню для страницы '$row->NAME'");
	}
	if(isset($_POST['add_menu']))
	{
		$row = $cms->fetch_object($cms->query("select ORD from zed_site_menu where WHER='$cat' and PARENT='$par' order by ORD desc limit 1"));
		if(isset($row->ORD)) $ord = $row->ORD;
		else $ord=0;
		foreach ($_POST['who'] as $key)
		{
			$ord++;
			$cms->query("insert into zed_site_menu (ID, PARENT, WHO, WHER, ORD) values('null', '$par', '$key', '$cat', '$ord' )");
			//echo "<br />insert into zed_site_menu (ID, PARENT, WHO, WHER, ORD) values('null', '$par', '$key', '$cat', '$ord' )";
		}
		return show_site_menu($_GET['cat']);
	}
	else 
	{
		$data['table'] = "<form action='?modul=main&cat=$cat&par=$par&action=add' method='post'>
		<tr><td>".get_check($cat,$par)."</td></tr>
		<tr><td><input type=submit name='add_menu' value='Добавить'></td></tr></form>";
		return $str.$cms->blockparse("table",$data,3);
	}
}
###########################################################################
	$zed_navi="<a href=?modul=main>список меню сайта</a>";
	if(!isset($_GET['cat'])) $_GET['cat']=0;

if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case "add":
		$zed_content=add_site_menu($_GET['cat'],$_GET['par']);
		$zed_navi="<a href=?modul=main>список меню сайта</a>";
		break;
		case "del":
		del_admin_menu($_GET['id']);
		$zed_navi="<a href=?modul=main>список меню сайта</a>";
		$zed_content=show_site_menu($_GET['cat']);
		break;
		case "up":
		up($_GET['id']);
		$zed_navi="<a href=?modul=main>список меню сайта</a>";
		$zed_content=show_site_menu($_GET['cat']);
		break;
		case "down":
		down($_GET['id']);
		$zed_navi="<a href=?modul=main>список меню сайта</a>";
		$zed_content=show_site_menu($_GET['cat']);
		break;
	}
}
else
{
	$zed_navi="<a href=?modul=main>список меню сайта</a>";
	$zed_content=show_site_menu($_GET['cat']);
}
?>