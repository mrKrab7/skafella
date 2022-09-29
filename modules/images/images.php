<?
function view_images($id)
{
	global $cms,$zsite;
	$resss=$cms->fetch_object($cms->query("select CATEGORY,NAME from zed_photo_category where ID='$id'"));
	$res=$cms->fetch_object($cms->query("select EN_NAME,NAME from zed_category where ID='$resss->CATEGORY'"));
	$data['content']="<div id='navi' class='col-xs-12 margin_b1'><a href='/'>Главная</a><i class='fa fa-angle-double-right' aria-hidden='true'></i><a href='/$res->EN_NAME'>$res->NAME</a><i class='fa fa-angle-double-right' aria-hidden='true'></i><h1>$resss->NAME</h1></div>";
	
	$result=$cms->query("select * from zed_photo where CATEGORY='$id' order by ORD");
	while($row=$cms->fetch_object($result))
	{ 
	if(strstr($row->NAME,'iframe'))
		{
			$data['content'].="<div class='image'>$row->NAME</div>";
		}
		else 
		{
		$rimg  = $cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$row->ID' and `TABLE`='zed_photo' "));
		$d['src']="{$rimg->PATH}m$rimg->NAME";
		$d['url']="{$rimg->PATH}l$rimg->NAME";
		$d['name']=$row->NAME;
		$d['class']='class="zoom" rel="group"';
		$data['content'].=$cms->blockparse("previewfoto",$d,1);
		}
	}
	
	$zsite['title'].=" :: $resss->NAME" ;
	return $data;
}

function show_images($id)
{
	global $cms,$zsite; 
	
	$zsite['adding'].='

<link rel="stylesheet" type="text/css" href="/zed/lib/fancybox/jquery.fancybox.css" media="screen" />
<script type="text/javascript" src="/zed/lib/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("a.fancyimage").fancybox();
	});
</script>';
	if(isset($_GET[2]) && $_GET[2]!='')
	{
		$cat=$cms->fetch_object($cms->query("select * from zed_photo_category where EN_NAME='{$_GET[2]}'"));
		$data=view_images($cat->ID); 
		return $cms->blockparse("middle",$data,1);
	}
	else 
	{
		$r=$cms->fetch_object($cms->query("select NAME from zed_category where ID='$id'"));
		$zsite['title'].=" :: $r->NAME" ;
		$data['content']="<div id='navi' class='col-xs-12 margin_b1'><a href='/'>Главная</a><i class='fa fa-angle-double-right' aria-hidden='true'></i><h1>$r->NAME</h1></div>";		
		
	$result=$cms->query("select * from zed_photo_category where CATEGORY='$id' order by ORD desc");
	while($row=$cms->fetch_object($result))
	{
		$dd['img']='';
		$dd['img'].="<a href='$cms->url/$row->EN_NAME'><img src='/zed/sitetpl/default/images/viewicon.png'/></a>";
		$resss=$cms->fetch_object($cms->query("select * from zed_photo where CATEGORY='$row->ID' order by ORD limit 1 "));
		$rimg=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$resss->ID' and `TABLE`='zed_photo'"));
		$dd['src']="{$rimg->PATH}m$rimg->NAME";
		$dd['url']="$cms->url/$row->EN_NAME";
		$dd['name']=$row->NAME;
		$data['content'].=$cms->blockparse("show_image",$dd,1);
	}
	

	
	return $cms->blockparse("middle",$data,1);
	}
}

$zsite['navi']=$cms->sitenavi($_GET['x']);
$zsite['middle'].=show_images($_GET['x']);
?>