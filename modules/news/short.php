<? 

$s=$cms->query("select * from zed_news where CATEGORY='$identy'");
$num=$cms->num_rows($s);
$a=rand(1,$num);
$s=$cms->query("select * from zed_news where CATEGORY='$identy' order by ID DESC limit $a");
$data['content'] = "<div style='text-align: center'><img width='500px' height='60px' src='/zed/image/na.jpg' /></div>";
$data['all'] = "";
//$r=$cms->fetch_object($s);
while ($r=$cms->fetch_object($s))
	{$idd=$r->ID;$tit=$r->TITLE;$sm = $r->SMALL;}
	$dd['title']=$tit;
	$img=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$idd' and `TABLE`='zed_news' "));	
	$dd['img']="<img src='{$img->PATH}m{$img->NAME}' />";
	$dd['url'] = "$url/$idd";
	$dd['small']='';//$sm;
	$data['content'].=$cms->blockparse('shot_news',$dd,1);
$data['navi']='';
$data['classc'] = 'class="shot_news"';
$data['classn'] = 'class="shot_news"';
?>