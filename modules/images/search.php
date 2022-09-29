<?
function search_photo($search)
{$quer="";
$req=explode("%",$search);
if (count($req)>1) {
foreach($req as $word)
{
$quer.="OR NAME LIKE '%$word%' OR DESCRIPTION LIKE '%$word%' ";
}
}
global $cms;
$result=$cms->query("SELECT ID FROM zed_photo WHERE NAME LIKE '%$search%' OR DESCRIPTION LIKE '%$search%' $quer" );
$cnt=$cms->num_rows($result);
if($cnt){
return "<tr bgcolor=#fafafa><td><a href=/index.php?modul=search&action=view&cat=".$_GET['cat']."&type=photo&search=$search>Изображений найдено: $cnt</a></td></tr>";
}
else return "<tr bgcolor=#fafafa><td>Изображений найдено: $cnt</td></tr>";
}

function searchin_photo($search)
{global $cms,  $cstart,$cend, $pg; 
$str="";$quer="";
$req=explode("%",$search);
if (count($req)>1) {
foreach($req as $word)
{
$quer.="OR NAME LIKE '%$word%' OR DESCRIPTION LIKE '%$word%'";
}
}
$result=$cms->query("SELECT ID FROM zed_photo WHERE DESCRIPTION LIKE '%$search%' OR NAME LIKE '%$search%' $quer" );
$pg=$cms->gen_sitepage($result,20,"/index.php?modul=search&action=view&cat=".$_GET['cat']."&type=category&search=$search");
$result=$cms->query("SELECT * FROM zed_photo WHERE DESCRIPTION LIKE '%$search%' OR NAME LIKE '%$search%' $quer limit $cstart,$cend" );

$s=ereg_replace("%"," ",$search);
$str.="<tr bgcolor=#fafafa><td><b class=title>Изображения и фото<b></a></td></tr>";
while ($ss=$cms->fetch_object($result))
	{	
			if(iffind($s,$ss->NAME) OR iffind($s,$ss->DESCRIPTION)) {
			$str.="<tr bgcolor=#fafafa><td class=title><b><a href=/index.php?modul=photo&action=view&id=$ss->ID&cat=$ss->CATEGORY>$ss->NAME</a></b><br>
			<a href=/index.php?modul=photo&action=view&id=$ss->ID&cat=$ss->CATEGORY><img src=zed/modules/photo/photo/sm_$ss->FILE align=left hspace=2 vspace=2>".strhilight($s,$ss->DESCRIPTION)."</a></td></tr>";
			$str.="<tr bgcolor=#fafafa><td>&nbsp;</td></tr>";}
	}
return $str;
}
?>