<?
function gen_site_menu_search($url)
{//bg_snoubord_desrch
	$str="<form action='$url' method=post>
	<input type=hidden  name=fullsearch value=1><p><input type=text name=searchstring placeholder='поиск' /></p><b><input type=submit value='&nbsp;' /></b></form>";
	return $str;
}
?>