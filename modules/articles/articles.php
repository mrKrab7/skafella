<?
include_once("articles_func.php");
if (isset($_GET['x']))
{
	$cms->gen_content($_GET['x']);
	$zsite['navi'] = $cms->sitenavi($_GET['x']);
    $zsite['middle']=show_articles($_GET['x']);
}

?>