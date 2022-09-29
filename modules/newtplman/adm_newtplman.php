<?
include "sitetpl/default/mini.tpl";

function add_form()
{
	global $cms, $zed_navi, $tplmain, $tplblock, $tplsubblock;
	$catname="<option value='0'>рубрика</option>";
	$result=$cms->query("select ID,NAME,TYPE,PARENT from zed_category  order by TYPE");
	while($row=$cms->fetch_object($result))
	{
		if($row->PARENT!="0" && $row->PARENT!="-1" )
		{
			$row2 = $cms->fetch_object($cms->query("select NAME from zed_category where ID='$row->PARENT'"));
			$parentt="$row2->NAME /";
		}
		else {$parentt="";}
		$catname.="<option value=$row->ID>$parentt$row->NAME($row->TYPE)</option>";
	}
	
	$result = $cms->query("select ID,TPL,NAME from zed_tplblock where LEVEL='0'");
	while ($row = $cms->fetch_object($result))
	{
		$kol = $cms->num_rows($cms->query("select ID from zed_tpl where NAME like '%:$row->TPL:%' "));
		if($kol==0) continue;
		@$maintpl.="<option value='$row->ID'>$row->NAME</option>";
	}
	if(isset($_GET['r'])&& $_GET['r']!=''){    $r = "&r=".$_GET['r'];}
	else {$r="";}
	$data['table']="<form name=add action=\"?modul=newtplman$r&action=add\" method=\"post\">
    <tr><td><select name=catname>$catname</select></td>
    <td><select name=maintpl>$maintpl</select></td>
    <td><input type=submit name=submit value='Добавить'></td>
    </tr></form>";
	return $cms->blockparse('table',$data,3);
}

function show_tplmanager()
{
	global $cms, $zed_navi, $tplmain;

	$result=$cms->query("select * from zed_tplmanager where TPLMAIN>'0' order by TPLMAIN");
	$data['table']="<tr bgcolor=#cccccc><td colspan=3 align=center>ВЫБРАННЫЕ ШАБЛОНЫ</td></tr>
<tr><td colspan=3 align=left><div onclick=\"show_raz();\" style=\"cursor:pointer; font-weight:bold;\">Скрыть/Показать</div></tr><tr><td colspan=3>
<div id=\"razdels_main\"><table width=100% cellpadding=3 cellspacing=1 border=0>
<tr bgcolor=#cccccc><td width=45%>Рубрика</td><td width=45%>Шаблон</td><td width=10%>Действие</td></tr>";
	while($row=$cms->fetch_object($result))
	{
		$row3=$cms->fetch_object($cms->query("select TPL from zed_tplblock where ID='$row->TPLMAIN'"));
		$row2=$cms->fetch_object($cms->query("select NAME from zed_category where ID='$row->CATEGORY'"));
		$data['table'].="<tr><td>$row2->NAME</td><td>$row3->TPL</td><td><a href=index.php?modul=newtplman&action=del&id=$row->ID>удалить</a></td></tr>";
	}
	$data['table'].="</td></tr></table></div>";
	return $cms->blockparse('table',$data,3);
}

function add_main($cat,$main)
{
	global $cms;
	if($cms->checklevel(100))
	{
		if($cat==0 || $main=='main'){}
		else
		{
			$cms->query("insert into zed_tplmanager (ID,CATEGORY,TPLMAIN) values ('null','$cat','$main')");
		}
		$str=add_form();
		$str.=show_tplmanager();
		return $str;
	}
	return '';
}

function del($id)
{
	global $cms;
	$cms->query("delete from zed_tplmanager where ID='$id'");
	if($cms->checklevel(100))return show_tplmanager();
	else return '';
}


###############################################3
function add2($where,$what,$tpl)
{
	global $cms;
	$cms->query("insert into zed_tplmanager (ID,CATEGORY,TPLMAIN,WHER,WHAT,TPL) values ('null','0','0','$where','$what','$tpl')");
	$str=add_form();
	$str.=show_tplmanager2();
	return $str;
}

function add3()
{
	global $cms;
	$tpl=$_POST['tpl'];
	$what=$_POST['what'];
	$where=$_POST['where'];
	$shablon = $_POST['shablon'];
	//echo count($where).":".count($what);
	if(count($where)==0) return ;
	if(count($what)==0 || $tpl=='') return ;
	//echo "post[main1]".$_POST['main1'];
	if(isset($_POST['where']))
	{
		$where=$_POST['where'];
		foreach($where as $wheres)
		{
			if($wheres=="main") {$wheres=0;}
			foreach($what as $whats)
			{
				$resss=$cms->query("select ID from zed_tplmanager where WHER='$wheres' AND WHAT='$whats' AND TPL='$tpl'");
				if(!$cms->num_rows($resss))
				{
					$cms->query("insert into zed_tplmanager (ID,CATEGORY,TPLMAIN,WHER,WHAT,TPL,SHABLON) values ('null','0','0','$wheres','$whats','$tpl','$shablon')");
				}
			}
		}
	}/**/
}

function up($id)
{
	global $cms;
	$r1=$cms->query("select * from zed_tplmanager where ID='$id'");
	$tpl=$cms->fetch_object($r1);
	$twr=$tpl->WHER;
	$twh=$tpl->WHAT;
	$tshbl=$tpl->SHABLON;
	$r2=$cms->query("select * from zed_tplmanager where TPL='$tpl->TPL' AND WHER='$tpl->WHER' order by ID");
	while ($rn1=$cms->fetch_object($r2))
	{
		if($id==$rn1->ID)
		{
			if(!isset($wr)) {return ;}
			else
			{
				$cms->query("UPDATE zed_tplmanager SET WHER='$wr',WHAT='$wh',SHABLON='$shbl' where ID='$id'");
				$cms->query("UPDATE zed_tplmanager SET WHER='$twr',WHAT='$twh',SHABLON='$tshbl' where ID='$i'");
				return 1;
			}
		}
		$i=$rn1->ID;
		$wr=$rn1->WHER;
		$wh=$rn1->WHAT;
		$shbl = $rn1->SHABLON;
	}
}

function down($id)
{
	global $cms;
	$r1=$cms->query("select * from zed_tplmanager where ID='$id'");
	$tpl=$cms->fetch_object($r1);
	$twr=$tpl->WHER;
	$twh=$tpl->WHAT;
	$tshbl=$tpl->SHABLON;
	$r2=$cms->query("select * from zed_tplmanager where TPL='$tpl->TPL' AND WHER='$tpl->WHER' order by ID");
	$n=0;
	while ($rn1=$cms->fetch_object($r2))
	{
		$i=$rn1->ID;
		$wr=$rn1->WHER;
		$wh=$rn1->WHAT;
		$shbl = $rn1->SHABLON;
		if ($n>0)
		{
			$cms->query("UPDATE zed_tplmanager SET WHER='$wr',WHAT='$wh',SHABLON='$shbl' where ID='$id'");
			$cms->query("UPDATE zed_tplmanager SET WHER='$twr',WHAT='$twh',SHABLON='$tshbl' where ID='$i'");
			return 1;
		}
		if($id==$rn1->ID){	$n++;}
	}
}


function show_raz($r=0)
{
	global $cms, $tplmain,$SITE;
	$result1=$cms->query("select * from zed_tplmanager where WHER='$r'");

	while($row11=$cms->fetch_object($result1))
	{
		$data[$row11->TPL]="$row11->TPL<br>";
	}
	$row2=$cms->fetch_object($cms->query("select NAME from zed_category where ID='$r'"));
	if(!$r) $nn="Главная"; 
	else $nn=$row2->NAME; 
	$data['middle']=$nn;

	$result=$cms->query("select * from zed_tplmanager where (WHER='$r' and WHAT<>0) OR  WHER='-1' order by ID");
	while($row=$cms->fetch_object($result))
	{
		if($row->SHABLON!='') $addshab = "$row->SHABLON |";
		else $addshab="";
		$row3=$cms->fetch_object($cms->query("select ID,NAME,TYPE from zed_category where ID='$row->WHAT'"));
		$data[$row->TPL].="
       	   <div class=\"show_raz\"><span><b>$row3->ID</b> | $addshab $row3->NAME</span> ";
		if($cms->checklevel(100) || $row3->TYPE=="brotator")
		$data[$row->TPL].="
	       <i><a href='?modul=newtplman&action=up&id=$row->ID&r=$r'><img src='/zed/modules/newtplman/images/up.gif' /></a>
	       <a href='?modul=newtplman&action=down&id=$row->ID&r=$r'><img src='/zed/modules/newtplman/images/down.gif' /></a></i><a href='?modul=newtplman&action=del&id=$row->ID&r=$r'><img src='/zed/modules/newtplman/images/trash.gif' /></a></div>";
		else $data[$row->TPL].="</div>";
	}
	return "<div align=center>".$nn."</div><hr>".$cms->blockparse_old($SITE['main'],$data);
}

function sh_tpl_man_view()
{
	global $cms;
	if(isset($_GET['r'])&&$_GET['r']!=''){$r="&r=".$_GET['r'];}
	else {$r="";}
	$str="<div style='margin:10px auto; width:90%;'><form name='ad' action='?modul=newtplman$r&action=add3' method='post' class='ad'>
		<div class='fl45'><i>Где отображать</i>\n";
	$str.=sh_tpl(0,1,1);
	$str.="</div><div class='fl45'>";
	$str.="<i>Какой раздел</i>\n";
	$str.=sh_tpl(0,1);
	$str.="</div><div class='fl10'>\n";
	$str.="<i>Место</i>\n";
	$str.=show_zone();
	if($cms->checklevel(100))
	{
		$str.="<i>Шаблон</i>
		<select name=shablon><option value=''>......</option>";
		$res = $cms->query("select NAME from zed_tpl where TYPE=1");
		while ($row = $cms->fetch_object($res))
		{
			$ss = explode(":",$row->NAME);
			for($i=0;$i<count($ss);$i++)
			{
				if($ss[$i]=='')continue;
				$arr[] = $ss[$i];
			}
		}
		sort($arr);
		for($i=0;$i<count($arr);$i++)
		{
			$str.="<option value=".$arr[$i].">".$arr[$i]."</option>";
		}
		$str.="</select>";
	}
	$str.="</div>
	<div style=' clear: both; margin:10px;'><input type=submit name=submit value='Добавить'></div></form></div>";
	return $str;
}


function sh_tpl($level,$u,$type_col=0)
{
	global $cms;
	$result=$cms->query("select ID,NAME,TYPE from zed_category where PARENT='$level' order by NAME");
	$str="";
	if($type_col==1)
	{
		if(!$level){$u=1;
		$str="<p><img src='/zed/modules/newtplman/images/category.gif'><a href='?modul=newtplman'>Главная</a><input type='checkbox' name=where[] value=0></p>\n";}
	}
	while($row=$cms->fetch_object($result))
	{
		if($row->ID==216)continue;
		$upr="";
		$plus = 0;
		if($cms->check_if_exist($row->ID,"zed_category","PARENT")) $plus=1;
		if($plus) // значит это папка
		{
			$upr="<img id=\"img_$row->ID$type_col\" src='modules/newtplman/images/plus.gif' alt='раскрыть' onclick=\"Open_block('$row->ID$type_col');\" class='p_m' />\n";
		}
		if(file_exists("modules/newtplman/images/$row->TYPE.gif")) $img="<img src='/zed/modules/newtplman/images/$row->TYPE.gif' />\n";
		else $img="<img src='/zed/modules/newtplman/images/default.gif'>\n";
		$str.="<p>$upr$img<a href='?modul=newtplman&r=$row->ID'>$row->NAME</a><input type=checkbox name=";
		if($type_col==1){$str.="where[]";}
		else
		{
			$str.="what[]";
			if(!$cms->checklevel(100) && $row->TYPE!='brotator' ) $str.=" disabled"; ;
		}
		$str.=" value=$row->ID></p>\n";
		if($plus)$str.="<div id=\"id_$row->ID$type_col\" style=\" display:none;\">\n";
		$str.=sh_tpl($row->ID,$u+1,$type_col);
		if($plus)$str.="</div>\n";
	}
	return $str;
}

function show_zone()
{
	global $cms;
	$tpl="<p>";
	$result=$cms->query("select TPL,DANY from zed_tplblock where LEVEL=2");
	while($row=$cms->fetch_object($result))
	{
		if(isset($_GET['r']) && $_GET['r']!='' && $row->TPL=="middle" && !$cms->checklevel($row->DANY)) continue;
		if($cms->checklevel($row->DANY))$tpl.="<input name='tpl' type='radio' value='$row->TPL'> $row->TPL<br />";
	}
	$tpl.="</p>";
	return $tpl;
}
####################################################################################################################
if (isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case "add":
		$zed_content=add_main($_POST['catname'],$_POST['maintpl']);
		break;
		case "add3":
		add3();
		break;
		case "del":
		$zed_content=del($_GET['id']);
		break;
		case "up":
		up($_GET['id']);
		break;
		case "down":
		down($_GET['id']);
		break;
	}
}
if($cms->checklevel(100)) $zed_content=add_form();
if($cms->checklevel(100))$zed_content.=show_tplmanager();
$zed_content.=sh_tpl_man_view();
if(isset($_GET['r']))$zed_content.=show_raz($_GET['r']);
else $zed_content.=show_raz();
$zed_navi="РАЗДЕЛЫ ДЛЯ РУБРИК";

?>