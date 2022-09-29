<?
/*
denied variables
$result
$row
$row2

*/
function menu_price($id,$url='',$lev=1)
{
	global $cms;
	//echo $url
	$str='';
	if($url=='') $url = $cms->get_url_from_id($id);
	$res = $cms->query("select * from zed_price_category where CATEGORY='$id' order by ORD");
	while ($rowww  = $cms->fetch_object($res))
	{
		$data['name'] = $rowww->NAME;
		$data['url'] = "$url/$rowww->EN_NAME";

		$data['class'] = $data['classo'] = $data['classl'] = '';//echo $_SERVER['REQUEST_URI'].'-'.$data['url'].'<br>';
		if(strstr($_SERVER['REQUEST_URI'],$data['url'])) {$data['class'] = 'class="has-sub active open"';$data['classl'] = 'class="active"'; $data['classo'] = ' style="display: block;"';}
		//if(strstr($_SERVER['REQUEST_URI'],$data['url']) || $rowww->ID==$_POST['tip']) {$data['class'] = ' class="over"'; $data['classo'] = ' class="OpenStatus"';$data['classl'] = 'class="active"';}
		else {$data['class'] = 'class="has-sub"';}
		$data['menu'] = menu_price($rowww->ID,"$url/$rowww->EN_NAME",$lev+1);
		if($data['menu'] =='' && $rowww->PRICE==0) continue;
		if($rowww->PRICE) $str .= $cms->blockparse("menu_li",$data,1)."\n";
		//elseif($lev==1){$data['navi'] ='Продукция';$data['classn'] ='id="mainnavi"'; $str .= $cms->blockparse("border",$data,1)."\n";}
		else  $str .= $cms->blockparse("menu_ul_li",$data,1)."\n";

	}
	return $str;
}

$data['classc'] = 'id="cssmenu"';
$data['content'] = '<ul>'.menu_price($identy,$url).'</ul>';

?>