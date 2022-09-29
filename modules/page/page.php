<?
function view_page($id)
{
	global $cms;
	$parent = $cms->fetch_object($cms->query("select H from zed_category where ID=$id"));
	$data['content']="<div id='navi' class='col-xs-12 margin_b1'><a href='/'>Главная</a><i class='fa fa-angle-double-right' aria-hidden='true'></i><h1>$parent->H</h1></div>";
		
	$page = $cms->fetch_object($cms->query("select FULL from zed_pages where ID=$id"));
	$data['content'].=$page->FULL; 
	return $cms->blockparse('middle',$data,1);
}

if (isset($_GET['x']))
{
	//$cms->gen_content($_GET['x']);
	$zsite['middle']=view_page($_GET['x']);
	//$zsite['navi']=$cms->sitenavi($_GET['x']);
}
?>
