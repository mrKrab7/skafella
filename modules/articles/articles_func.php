<?
function show_articles($id)
{
	global $cms, $cstart, $cend,$zsite; 
	$zsite['countergoogl'].='
	<script type="text/javascript" src="http://pcvector.net/templates/pcv/js/pcvector.js"></script>	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="/zed/lib/readmore.js"></script>';
	$zsite['countergoogl'].="<script>
		$('article').readmore({
			maxHeight: 200,
			moreLink: '<a href=\"#\">Подробнее</a>',
			lessLink: '<a href=\"#\">Скрыть</a>'
		});
	</script>";
	if(isset($_GET["$cms->rubrics_level"]) && intval($_GET["$cms->rubrics_level"])>0)
	{
		return view_articles(intval($_GET["$cms->rubrics_level"]));
	}

	$str="";
	$result=$cms->query("select ID from zed_articles where CATEGORY='$id' order by ORD");
	$pg=$cms->gen_sitepage($result,20,$cms->url);
	$result=$cms->query("select * from zed_articles where CATEGORY='$id' order by ORD limit $cstart,$cend");
	while ($row=$cms->fetch_object($result))
	{
		$data['title']=$row->TITLE;
		$data['full']=$row->FULL;
		$str.=$cms->blockparse('articles',$data,1);
	}
	$dd['navi']="<h1>$cms->name</h1>";
	$dd['classc'] = '';
	$dd['content'] = $str.$pg;
	
	return $cms->blockparse('middle',$dd,1);
}

?>