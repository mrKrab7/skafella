<?
function search_page($search)
{
$req=explode(" ",$search);
$quer="";
foreach($req as $word)
{
$quer.="OR FULL LIKE '%$word%' ";
}
global $cms;
if($req>1){
$result=$cms->query("SELECT ID FROM zed_pages WHERE FULL LIKE '%$search%' $quer" );
}
else $result=$cms->query("SELECT ID FROM zed_pages WHERE FULL LIKE '%$search%'" );
 

$cnt=$cms->num_rows($result);
if ($cnt) {
return "<tr bgcolor=#fafafa><td><a href=?modul=search&action=view&cat=".$_GET['cat']."&type=page&search=".rawurlencode($search).">Документов найдено: $cnt</a></td></tr>";
}
else return "<tr bgcolor=#fafafa><td>Документов найдено: $cnt</td></tr>";

}



function searchin_page($search)
{global $cms, $pg, $cstart, $cend; 
$str="";
$req=explode(" ",$search);
$quer="";
foreach($req as $word)
{
$quer.="OR FULL LIKE '%$word%' ";
}
$result=$cms->query("SELECT * FROM zed_pages WHERE FULL LIKE '%$search%' $quer" );
$pg=$cms->gen_sitepage($result,20,"/index.php?modul=search&action=view&cat=".$_GET['cat']."&type=page&search=$search");
$result=$cms->query("SELECT *, MATCH FULL AGAINST ('$search') as relev FROM zed_pages ORDER BY relev DESC limit $cstart,$cend");


while ($ss=$cms->fetch_object($result))
	{	$highlight = str_replace(" ", "|", $search);
		$ss->FULL = eregi_replace($highlight, "<font color=#cc0000>\\0</font>", strip_tags($ss->FULL));
		$nn=$cms->fetch_object($cms->query("select NAME from zed_category where ID=$ss->ID"));
			$str.="<tr bgcolor=#fafafa><td><a href=/index.php?modul=page&action=open&id=$ss->ID><b class=title>".$nn->NAME."<b></a></td></tr>";
		$str.="<tr bgcolor=#fafafa><td><a href=/index.php?modul=page&action=open&id=$ss->ID>$ss->FULL</a></td></tr>";
		
	}	$str.="<tr bgcolor=#fafafa><td>&nbsp;</td></tr>";
return $str.$pg;
}
?>