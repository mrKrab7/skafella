<?
function russian_date($date)
{
	$date=explode(" ", $date);
	$date=explode("-", $date[0]);
	switch ($date[1]){
		case 1: $m='января'; break;
		case 2: $m='февраля'; break;
		case 3: $m='марта'; break;
		case 4: $m='апреля'; break;
		case 5: $m='мая'; break;
		case 6: $m='июня'; break;
		case 7: $m='июля'; break;
		case 8: $m='августа'; break;
		case 9: $m='сентября'; break;
		case 10: $m='октября'; break;
		case 11: $m='ноября'; break;
		case 12: $m='декабря'; break;
	}
	$date[1]=$m;
	return $date;
}

function show_news($id)
{
	global $cms, $cstart, $cend;
	if(isset($_GET["$cms->rubrics_level"]) && intval($_GET["$cms->rubrics_level"])>0)
	{
		return view_news(intval($_GET["$cms->rubrics_level"]));
	}

	$str="";
	$result=$cms->query("select ID from zed_news where CATEGORY='$id' order by DATE DESC");
	$pg=$cms->gen_sitepage($result,21,$cms->url);
	$result=$cms->query("select * from zed_news where CATEGORY='$id' order by DATE DESC limit $cstart,$cend");
	while ($row=$cms->fetch_object($result))
	{
		$data['title']="$row->TITLE";
		$rimg=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$row->ID' and `TABLE`='zed_news'"));
		if($rimg->NAME!=''){$data['src']="{$rimg->PATH}m$rimg->NAME";}
		else $data['src']="/zed/sitetpl/default/images/news_dufault.jpg";
		$date=russian_date($row->DATE);
		$data['date_d'] =$date[2];
		$data['date_m'] =$date[1];
		$data['url'] = "$cms->url/$row->ID";
		$data['small']=$row->SMALL;
		$data['txt']='подробнее';
		$data['class'] = 'news margin_b1';
		$str.=$cms->blockparse('news',$data,1);
	}
	//$n=strtr($cms->name, "абвгдеёжзийклмнопрстуфхцчшщъыьэюя", "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩљЫЬЭЮЯ");
	$dd['content']="<div id='navi' class='col-xs-12 margin_b1'><a href='/'>Главная</a><i class='fa fa-angle-double-right' aria-hidden='true'></i><h1>$cms->name</h1></div>";
	//$dd['classc'] = '';//'class="news"';
	$dd['content'].= $str.$pg;
	//echo $dd['content'];
	return $cms->blockparse('middle',$dd,1);
}


function view_news($id)
{
	global $cms,$zsite;

	$row=$cms->fetch_object($cms->query("select * from zed_news where ID=$id"));
	$title = $row->TITLE;
	$d=date("d-m-Y",$row->DATE);
	$dd['content']="<div id='navi' class='col-xs-12 margin_b1'><a href='/'>Главная</a><i class='fa fa-angle-double-right' aria-hidden='true'></i><a href='".$cms->get_url_from_id($row->CATEGORY)."'>$cms->name</a><i class='fa fa-angle-double-right' aria-hidden='true'></i>$title</div>";
	
	if($row->FULL == ""){$data['FULL']=$row->SMALL; }
	else {$data['FULL']=$row->FULL;}
	$rimg=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$row->ID' and `TABLE`='zed_news'"));
	if($rimg->NAME!=''){$data['src']="{$rimg->PATH}m$rimg->NAME";}
	else $data['src']="";
	$dd['content'].= $cms->blockparse('newsfull',$data,1);
	###############
	$row3=$cms->fetch_object($cms->query("select * from zed_category where ID='$row->CATEGORY'"));
	$zsite['title'].=" :: $row->TITLE";
	//$n=strtr($cms->name, "абвгдеёжзийклмнопрстуфхцчшщъыьэюя", "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩљЫЬЭЮЯ");
	//$dd['navi'] = "<div id='navi'><a href='".$cms->get_url_from_id($row->CATEGORY)."'>$cms->name</a> > $title</div>";
	//$dd['classc']='class="news_text"';
	return $cms->blockparse('middle',$dd,1);
}
?>