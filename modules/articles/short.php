<? 
$str='';
$i=1;
$s=$cms->query("select * from zed_articles where CATEGORY='$identy' order by ORD");
while($r=$cms->fetch_object($s))
{
	if($i>1)$str.="<div class='col-xs-12 double_solid'>&nbsp;</div>";
	$img=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$r->ID' and `TABLE`='zed_articles' "));
	if($img->NAME!='')
	{
		$d['content']="<div class='col-xs-4 margin_t1'><img src='{$img->PATH}m$img->NAME' /></div><div class='col-xs-8 margin_t1'>$r->FULL</div>";
	}
	else 
	{
		$d['content']=$r->FULL;
	}
 	$str.=$cms->blockparse('articles',$d,1);
	$i++;
}
if($str!='')$data['content']="<div class='col-xs-12 articles red'><span class='fa fa-percent'></span> $name</div>".$str;
else $data['content']='';
?>