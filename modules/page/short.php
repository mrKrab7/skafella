<? 
function gettt($id,$level)
{
	global $cms;
	$path='';
	for ($i=0;$i<$level;$i++)
	{
		$row_path  = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$id'"));
		if($row_path->LEVEL==1)	{$srt= "$row_path->EN_NAME$path";}
		else
		{
			$path= "/$row_path->EN_NAME$path";
			$id=$row_path->CATEGORY;
		}
	}
	return $srt;
}
function catalog()
{
	global $cms;
	$out="";
	$result = $cms->query("select * from zed_price_category where LEVEL=1 ");
	if($cms->num_rows($result)==0)
	{
		$out.='';
	}
	else
	{
		$out.="<div class='row'>";
		while ($rowww  = $cms->fetch_object($result))
		{
			$d['name']=$rowww->NAME;
			//$zurl=gettt($rowww->CATEGORY,$r->LEVEL);
			$d['url']=$url_tek='/price/'.$rowww->EN_NAME;

			$rimg=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY=$rowww->ID and `TABLE`='zed_price_category' order by ORD"));
			$d['src']="{$rimg->PATH}s$rimg->NAME";
				
			
			$out.=$cms->blockparse('veiw_shot',$d,1);
		}
		$out.="</div>";
	}
	return $out;
}
	$page=$cms->fetch_object($cms->query("select * from zed_pages where ID=$identy"));
	$data['content']=$page->FULL;
	if($identy==16)
	{
		$data['content'].=catalog();
	}
?>