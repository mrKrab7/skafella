<?
include_once("news_func.php");
if (isset($_GET['x']))
{
	$cms->gen_content($_GET['x']);
	$zsite['navi'] = $cms->sitenavi($_GET['x']);
    $zsite['middle']=show_news($_GET['x']);
}

?>