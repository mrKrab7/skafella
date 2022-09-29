<?
function search_cat($search)
{
	$quer="";
	$req=explode("%",$search);
	if (count($req)>1) {
		foreach($req as $word)
		{
			$quer.="OR NAME LIKE '%$word%' OR DES LIKE '%word%' ";
		}
	}
	global $cms;
	$result=$cms->query("SELECT ID FROM zed_category WHERE NAME LIKE '%$search%' OR DES LIKE '%$search%' $quer" );
	$cnt=$cms->num_rows($result);
	if($cnt){
		return "<tr bgcolor=#fafafa><td><a href=/index.php?modul=search&action=view&cat=".$_GET['cat']."&type=category&search=".rawurlencode($search).">Папок и рубрик найдено: $cnt</a></td></tr>";
	}
	else return "<tr bgcolor=#fafafa><td>Папок и рубрик найдено: $cnt</td></tr>";
}

function searchin_cat($search)
{
	global $cms, $cstart,$cend, $pg;
	$str="";$quer="";
	$req=explode("%",$search);
	if (count($req)>1) {
		foreach($req as $word)
		{
			$quer.="OR NAME LIKE '%$word%' OR NAME LIKE '%$word%'";
		}
	}
	$result=$cms->query("SELECT ID FROM zed_category WHERE DES LIKE '%$search%' OR NAME LIKE '%$search%' $quer" );
	$pg=$cms->gen_sitepage($result,20,"/index.php?modul=search&action=view&cat=".$_GET['cat']."&type=category&search=$search");
	$result=$cms->query("SELECT * FROM zed_category WHERE DES LIKE '%$search%' OR NAME LIKE '%$search%' $quer limit $cstart,$cend" );

	$s=ereg_replace("%"," ",$search);
	$str.="<tr bgcolor=#fafafa><td><b class=title>Папки и рубрики<b></a></td></tr>";
	while ($ss=$cms->fetch_object($result))
	{	$highlight = str_replace(" ", "|", $search);
	$ss->NAME = eregi_replace($highlight, "<font color=#cc0000>\\0</font>", strip_tags($ss->NAME));
	$ss->DES = eregi_replace($highlight, "<font color=#cc0000>\\0</font>", strip_tags($ss->DEs));


	$str.="<tr bgcolor=#fafafa><td class=title><b><a href=/index.php?modul=$ss->TYPE&action=open&id=$ss->ID>$ss->NAME</a></b><br>
			<a href=/index.php?modul=$ss->TYPE&action=open&id=$ss->ID>$ss->DES</a></td></tr>";


	}$str.="<tr bgcolor=#fafafa><td>&nbsp;</td></tr>";
	return $str;
}
?>