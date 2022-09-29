<?
/*$s=$cms->fetch_object($cms->query("select FULL from zed_pages where ID='$identy'"));
$data['content']='';
$data['navi']="";
if ($identy==11)
{
	$data['content']="<a href='$url'/'$s->EN_NAME' ><img src='/zed/sitetpl/default/images/dostav.jpg' /></a>";$data['classc'] = 'class="pageshot"';
}
elseif ($identy==10)
{
$data['content']="<a href='$url'/'$s->EN_NAME' ><img src='/zed/sitetpl/default/images/oplata.jpg' /></a>";$data['classc'] = 'class="pageshot"';
}*/
/**/
$n=$cms->fetch_object($cms->query("select NAME from zed_category where ID='$identy'"));
$s=$cms->query("select * from zed_category where PARENT='$identy' order by ORD");
$data['content'] = "<div class='bg-flor'><div class='container'><div class='col-xs-12 margin_tb2 text-center produkt-name'><h2>$n->NAME</h2></div>";
while($r=$cms->fetch_object($s))
{
	$dan['src']="/zed/image/produkt_$r->ID.jpg";
	$dan['name']=$r->NAME;
	$dan['url']="$url/$r->EN_NAME";
	$dan['text']=$r->SEOTEXT;
	$data['content'].=$cms->blockparse('category',$dan,1);
}
$data['content'].='</div></div>';
?>
