<?
$s=$cms->query("select * from zed_category where ID='$identy'");
$data['url']=$url;
$data['class']='glav_shot_bg_def';
while($r=$cms->fetch_object($s))
{
	//$img=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$r->ID' and `TABLE`='zed_articles' "));	
	$data['foto']="<img src='/zed/image/3.jpg' />";//"<img src='{$rimg->PATH}m$rimg->NAME' />";
	$data['name']=$r->NAME;
	$data['text']=$r->DES;
}
?>