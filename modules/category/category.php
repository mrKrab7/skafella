<?php

function gen_menu()
{
	global $cms;
	if(isset($_GET['x'])) $id = $_GET['x'];
	else $id = $_GET['id'];
	$str = "<table width=100% border=0 cellpadding=0 cellspacing=3><tr valign=top><td width=33%";
	$res=$cms->query("select ID, NAME,TYPE from zed_category where PARENT='$id' order by NAME");
	$kol = $cms->num_rows($res);
	$curr=0;
	$kk=1;
	$flag=0;
	$step = intval($kol/3);
	$kol = $kol%3;
	if($kol==0) $step--;
	while($row=$cms->fetch_object($res))
	{
		if($curr>$step)
		{
			$kk++;
			if($kol!=0 && $kk>$kol && !$flag){ $step--; $flag=1; }
			$str = substr($str,0,strlen($str)-4);
			$str.="</td><td width=33%>";
			$curr=0;
		}
		//$name = strtoupper($row->NAME);
		$name = $row->NAME;
		$str.="<font color=#336699>&raquo;</font>&nbsp;<a href=/index.php?x=$row->ID class=mgray>$name</a><br>";
		$curr++;
	}
	$str = substr($str,0,strlen($str)-4);
	$str.="</td></tr></table> ";
	return $str;
}
function cmp ($a, $b) 
{
	if ($a->NUMBER == $b->NUMBER) return 0;
	return ($a->NUMBER > $b->NUMBER) ? 1 : -1;
}

function GetData($curdata,$type)
{
	if($type=="news") {$str = "Последние новости на ";}
	else { $str = "Статьи за";}
	switch($curdata[1])
	{
		case 1: $curdata[1] ="Января"; break;
		case 2: $curdata[1] ="Февраля"; break;
		case 3: $curdata[1] ="Марта"; break;
		case 4: $curdata[1] ="Апреля"; break;
		case 5: $curdata[1] ="Мая"; break;
		case 6: $curdata[1] ="Июня"; break;
		case 7: $curdata[1] ="Июля"; break;
		case 8: $curdata[1] ="Августа"; break;
		case 9: $curdata[1] ="Сентября"; break;
		case 10: $curdata[1] ="Октября"; break;
		case 11: $curdata[1] ="Ноября"; break;
		case 12: $curdata[1] ="Декабря"; break;
	}
	$str.=$curdata[2]." ".$curdata[1]." ".$curdata[0];
	$str.=" года.";
	return $str;
}
function show_last_news($rezult)
{
	global $cms, $ZED,$zsite;
	//	$rez = $cms->query("select * from zed_news_category order by ID_CAT ");
	if(isset($_GET['data'])){ $curdata = explode("-",$_GET['data']);}
	else {$curdata=explode("-",date("Y-n-j"));}

	$short = $cms->blockparse($ZED['news_block']);
	$str['curdate']=GetData($curdata,"news");
	$curdata[2]++;
	while($row=$cms->fetch_object($rezult))
	{
		$ID = $row->ID;
		$parent = $row->PARENT;
		$rez = $cms->query("select * from zed_news_category where ID_CAT='$ID'");
		if(!$cms->num_rows($rez)) continue;
		if(isset($_GET['data']))
		{
			$sortdata = strtotime($curdata[0]."-".$curdata[1]."-".$curdata[2]);
			//echo $sortdata." ";
			//echo strtotime("2007-5-8")."<br>";
		}
		else {$sortdata = time();}
		$res1 = $cms->fetch_object($cms->query("select * from zed_news where CATEGORY='$ID' and RAZDEL='1' and DATE<$sortdata order by ID desc limit 1; "));
		$res2 = $cms->fetch_object($cms->query("select * from zed_news where CATEGORY='$ID' and RAZDEL='2' and DATE<$sortdata order by ID desc limit 1; "));
		//		$res3 = $cms->fetch_object($cms->query("select * from zed_news where CATEGORY='$ID' and RAZDEL='3' and DATE<$sortdata order by ID desc limit 1"));
		$data['block_category'] = $row->NAME;
		$data['link_cat'] = "/index.php?x=$ID";
		$data['link_nsk'] = "/index.php?x=$ID&razdel=1";
		$data['link_nso'] = "/index.php?x=$ID&razdel=2";
		//	$data['link_rus'] = "/index.php?x=$ID&razdel=3";
		if(isset($res1->ID))
		{
			$data['data1'] = "<div class=Black>".date("d-m-Y",$res1->DATE)."</div>";
			$data['comment1'] = "Комментариев: ".$res1->COMMENTS;
			$res1->SMALL = strip_tags($res1->SMALL,'');
			if(strlen($res1->SMALL)>180) $res1->SMALL = substr($res1->SMALL,0,177)." ...";
			if($res1->IMAGE!=""){$data['img1'] = "<div align=char><img hspace=5 vspace=5 src=\"zed/Image/news/sm_$res1->IMAGE\" align=left>";}
			else {$data['img1']="";}
			if(strlen($res1->TITLE)>100){$res1->TITLE = substr($res1->TITLE,0,97)."...";}
			$data['title1'] = "<a href=/index.php?x=$res1->CATEGORY&id=$res1->ID title='Читать новость' class=new>$res1->TITLE</a></div>";
			if(strlen($res1->SMALL)>500){$data['small1']= substr($res1->SMALL,0,297)."...";}
			else {$data['small1']=$res1->SMALL;}
		}
		else
		{
			$data['data1'] = "";
			$data['comment1'] = "";
			$data['img1'] = "";
			$data['title1'] = "";
			$data['small1']= "НЕТ НОВОСТИ";
		}
		if(isset($res2->ID))
		{
			$data['data2'] = "<div class=Black>".date("d-m-Y",$res2->DATE)."</div>";
			$data['comment2'] = "Комментариев: ".$res2->COMMENTS;
			$res2->SMALL = strip_tags($res2->SMALL);
			if(strlen($res2->SMALL)>180) $res2->SMALL = substr($res2->SMALL,0,177)." ...";
			if($res2->IMAGE!=""){$data['img2'] = "<div align=justify><img hspace=5 vspace=5 src=\"zed/Image/news/sm_$res2->IMAGE\" align=left>";}
			else {$data['img2']="";}
			if(strlen($res2->TITLE)>100){$res2->TITLE = substr($res2->TITLE,0,97)."...";}
			$data['title2'] = "<a href=/index.php?x=$res2->CATEGORY&id=$res2->ID title='Читать новость' class=new> $res2->TITLE</a></div>";
			if(strlen($res2->SMALL)>500){$data['small2']= substr($res2->SMALL,0,297)."...";}
			else {$data['small2']=$res2->SMALL;}
		}
		else
		{
			$data['data2'] = "";
			$data['comment2'] = "";
			$data['img2'] = "";
			$data['title2'] = "";
			$data['small2']= "НЕТ НОВОСТИ";
		}
		/*
		if(isset($res3->ID))
		{
		$data['data3'] = "<div class=Black>".date("d-m-Y",$res3->DATE)."</div>";
		$data['comment3'] = "Комментариев: ".$res3->COMMENTS;
		$res3->SMALL = strip_tags($res3->SMALL);
		if(strlen($res3->SMALL)>180) $res3->SMALL = substr($res3->SMALL,0,177)." ...";
		if($res3->IMAGE!=""){$data['img3'] = "<div align=char><img hspace=4 vspace=0 src=\"zed/Image/news/sm_$res3->IMAGE\" align=left>";}
		else {$data['img3']="";}
		if(strlen($res3->TITLE)>100){$res3->TITLE = substr($res3->TITLE,0,97)."...";}
		$data['title3'] = "<a href=/index.php?x=$parent&id=$res3->ID title='Читать новость' class=new> $res3->TITLE</a></div>";
		if(strlen($res3->SMALL)>500){$data['small3']= substr($res3->SMALL,0,297)."...";}
		else {$data['small3']=$res3->SMALL;}
		}
		else
		{
		$data['data3'] = "";
		$data['comment3'] = "";
		$data['img3'] = "";
		$data['title3'] = "";
		$data['small3']= "НЕТ НОВОСТИ";
		}
		/**/
		$row2=$cms->fetch_object($rez);
		$sss = "news".$row2->NUMBER;
		$ss = "newsbanner".$row2->NUMBER;
		$str["$sss"] = $cms->blockparse( $ZED['news2'],$data );
		// no banner
		$str["$ss"]="";
	}
	$str['newsbanner13']="";
	$str['newsbanner14']="";
	$zsite['middle'] = $cms->blockparse($short,$str);
	$tram = $cms->fetch_object($cms->query("select * from zed_spam where ID='1'"));
	if((time()-$tram->DATA)>(60*60))
	{
		$zsite['javatram']='<script language="javascript" type="text/javascript" src="http://js.redtram.com/n4p/t/u/tut-54.ru.lf.js"></script>';
		$zsite['rtram']='<div id="rtn4pt5r_lf" ><center>Загрузка ...</center></div>
';
		$zsite['load'] ='onload="Timer_n();"';
		$zsite['scripttr']='<SCRIPT language=JavaScript src="zed/lib/news.js"></SCRIPT>
<SCRIPT language=JavaScript src="zed/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>';
	}
	else
	{
		$zsite['rtram']="<div id=\"rtn4pt5r_lf\" >$tram->SPAM</div>";
	}
	$zsite['middle'].="{RTRAM}<table width=100%  border=0 cellspacing=0 cellpadding=10>
<tr>
    <td valign=top width=20%>{RSS1}</td>
	<td valign=top width=20%>{RSS2}</td>
	<td valign=top width=20%>{RSS3}</td>
	<td valign=top width=20%>{RSS4}</td>
	<td valign=top width=20%>{RSS5}</td>
</tr>
<tr>
    <td valign=top width=20%>{RSS6}</td>
	<td valign=top width=20%>{RSS7}</td>
	<td valign=top width=20%>{RSS8}</td>
	<td valign=top width=20%>{RSS9}</td>
	<td valign=top width=20%>{RSS10}</td>
</tr>
</table>
";
}

function show_page($id)
{
	global $cms, $data;
	$str='';echo $id;
	$rez=$cms->query("select * from zed_category where PARENT='$id' order by ID ");
	while ($row=$cms->fetch_object($rez))
	{
			$cms->gen_content($row->ID);
	$opt=$cms->fetch_object($cms->query("select * from zed_pages_options where CATEGORY='$row->ID'"));
	$page=$cms->fetch_object($cms->query("select * from zed_pages where ID=$row->ID"));
	
	if($opt->NAVI==1){$data['content']=$page->NAVI; $data['classn']='';}
	else {$data['content']=$row->NAME; $data['classn']=''; }
	
	if($opt->IMAGE==1)
	{
		$shablon=$opt->SHABLON;
		$img=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$row->ID' and `TABLE`='zed_pages' "));	
		$dd['path']=$img->PATH;
		$dd['name']=$img->NAME;
		$dd['w']=$opt->W;
		$dd['h']=$opt->H;
		$dd['full']=$page->FULL;	
		$data['classn']='class="oplata"';
		$data['classc']="class=\"$shablon\"";
		$data['content']=$cms->blockparse("$shablon",$dd,1);
	}
	else {$data['content']=$page->FULL; $data['classc']='';}
	
	 $str.=$cms->blockparse('middle',$data,1);
	}
	return $str;
}

function show_arts($id)
{
	global $cms, $data;
	$result=$cms->query("select ID,TITLE,CATEGORY,SMALL,COMMENTS from zed_articles WHERE CATEGORY='$id' order by ID DESC limit 3");
	$short="<table width=100% cellspacing=0 cellpadding=0>";
	while($row=$cms->fetch_object($result))
	{
		$short.="<tr><td><a href=/index.php?x=$row->CATEGORY&id=$row->ID><b>$row->TITLE</b></a><br>
	$row->SMALL</td></tr><tr><td><hr size=1 color=#cccccc>";
		/*        <a href=/index.php?x=$row->CATEGORY&id=$row->ID>Комментариев: $row->COMMENTS</a>*/

		$short.="</td></tr>";
	}
	$short.="</table>";
	return $short;
}

function show_last_arts($id)
{
	//	if(isset($_GET['data'])){ $curdata = explode(".",$_GET['data']); }
	//	else {$curdata=explode(".",date("j.n.Y"));}
	//	if(isset($_GET['data']))
	//	{
	//		$sortdata = strtotime($curdata[2]."-".$curdata[1]."-".$curdata[0]);
	//	}
	//	else {$sortdata = time();}
	global $cms, $ZED, $zsite, $modul, $cstart, $cend;
	$res=$cms->query("select * from zed_category where PARENT='$id' order by ID DESC");
	$query = "";
	while($row=$cms->fetch_object($res))
	{
		$query.=" CATEGORY='$row->ID' or";
	}
	$query = substr($query,0,strlen($query)-3);
	$res = $cms->query("select ID from zed_articles where $query order by ID DESC");
	$pg=$cms->gen_sitepage($res,7,"/index.php?x=$id");
	$result=$cms->query("select * from zed_articles where $query order by ID DESC limit $cstart,$cend");
	$short="<table width=100% cellspacing=0 cellpadding=0>";
	while($row=$cms->fetch_object($result))
	{
		$short.="<tr><td><font color=#D21F1F><b>$row->DATE ::</b></font> <a href=/index.php?x=$row->CATEGORY&id=$row->ID><b>$row->TITLE</b></a><br>
	$row->SMALL</td></tr><tr><td><hr size=1 color=#cccccc>";
		/*        <a href=/index.php?x=$row->CATEGORY&id=$row->ID>Комментариев: $row->COMMENTS</a>*/
		$short.="</td></tr>";
	}
	$short.="</table>";
	return "<br>".$pg."<br>".$short."<br>".$pg."<br>";
}

function show_category($id)
{
	global $cms, $ZED, $zsite, $modul, $cstart, $cend;

	$res=$cms->query("select * from zed_category where PARENT='$id' order by ID");
	$url = $cms->get_url_from_id($id);
	$pg=$cms->gen_sitepage($res,10,$url);
	$result=$cms->query("select * from zed_category where PARENT='$id' order by ID limit $cstart,$cend");
	$str="";
	//$cms->gen_content($id);
	$rez=$cms->query("select * from zed_category where PARENT='$id' order by ORD");
	//$row=$cms->fetch_object($rez);
	$data['content'] = "<div class='bg-flor'><div class='container margin-1'><div class='col-xs-12 margin_tb2 text-center produkt-name'><h1>$cms->name</h1></div>";
	while($r=$cms->fetch_object($rez))
	{
		$dan['src']="/zed/image/produkt_$r->ID.jpg";
		$dan['name']=$r->NAME;
		$dan['url']="$url/$r->EN_NAME";
		$dan['text']=$r->SEOTEXT;
		$data['content'].=$cms->blockparse('category',$dan,1);
	}
	$data['content'].='</div></div>';
	/*$data['navi']="<div id='navi'>Слушателям &middot; </div><h1>$cms->name</h1>";
	$data['content']=$row->DES;
	$data['classc']='class="cont"';*/
	return $cms->blockparse('middle',$data,1);
	//echo $row->TYPE;
	/*switch($row->TYPE)
	{
		case "page":
			$zsite['middle2']=show_page($id);
		//	$cms->sitenavi($id);
		//$data['navi']=$cms->name;
		//	$zsite['middle']='';
			return $data['navi']="<h1>$cms->name</h1>";
		break;
		
		case "news":
		//echo "news";
		//show_last_news($res);
		//$zsite['navinews'] = gen_menu();
		
		return $cms->sitenavi($id);
		break;
		case "articles":
		if($id==51)
		{
			//$data['navi']="Разделы статей";
			$zsite['middle2']=gen_menu();
			//$str=$cms->blockparse($ZED['middle'],$data);
			$data['navi']="Последние статьи:";
			$data['content']=show_last_arts($id);
			$str.=$cms->blockparse($ZED['middle'],$data);
			$zsite['middle']=$str;
			return $cms->sitenavi($id);
		}
		else
		{
			while($row=$cms->fetch_object($result))
			{
				$data['navi']="<a href=/index.php?x=$row->ID><b>$row->NAME</b></a>";
				$data['content']=show_arts($row->ID);
				#echo $data['content'];
				$str.=$cms->blockparse($ZED['middle'],$data);
				//$str.="<hr size=1 color=#f3f3f3>";
			}
			$zsite['middle']=$str."<br>".$pg."<br>";
			return $cms->sitenavi($id);
		}
		break;
		default:
		while($row=$cms->fetch_object($result))
		{
			$full="";
			$data['navi']="<a href=/index.php?x=$row->ID><b>$row->NAME</b></a>";
			$data['content']="$row->DES";
			$str.=$cms->blockparse($ZED['middle'],$data);
		}
		break;
	}
	return $zsite['middle']=$str."<br>".$pg."<br>";*/
	//return $cms->sitenavi($id);
}

if (isset($_GET['x']))
{
		$zsite['navi'] = $cms->sitenavi($_GET['x']);
		$zsite['middle']=show_category($_GET['x']);
}
?>

