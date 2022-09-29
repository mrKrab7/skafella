<?
function search_news($search)
{
	global $cms;
	if(isset($_GET['x'])) $id = $_GET['x'];
	else $id = $_GET['cat'];
	$quer="";
	$req=explode(" ",$search);
	if (count($req)>1) 
	{
		foreach($req as $word)
		{
			$quer.="( TITLE LIKE '%$word%' OR SMALL LIKE '%$word%' OR FULL LIKE '%$word%') AND ";
		}
		$quer = substr($quer,0,strlen($quer)-5);
	}
	else {$quer.="TITLE LIKE '%$search%' OR SMALL LIKE '%$search%' OR FULL LIKE '%$search%'";}
	$result=$cms->query("SELECT ID FROM zed_news WHERE $quer" );
	$cnt=$cms->num_rows($result);
	if ($cnt) {
	return "<tr bgcolor=#fafafa><td><a href=/index.php?x=$id&type=news&search=".rawurlencode($search).">Новостей найдено: $cnt</a></td></tr>";
	}
	else return "<tr bgcolor=#fafafa><td>Новостей найдено: $cnt</td></tr>";
}

function searchin_news($search)
{
	global $ZED,$cms,$cstart,$cend, $pg; 
	if(isset($_GET['x'])) $id = $_GET['x'];
	else $id = $_GET['cat'];
	$quer="";
	$req=explode(" ",$search);
	if (count($req)>1) 
	{
		foreach($req as $word)
		{
			$quer.="( TITLE LIKE '%$word%' OR SMALL LIKE '%$word%' OR FULL LIKE '%$word%') AND ";
		}
		$quer = substr($quer,0,strlen($quer)-5);
	}
	else {$quer.="TITLE LIKE '%$search%' OR SMALL LIKE '%$search%' OR FULL LIKE '%$search%'";}
	$result=$cms->query("SELECT ID FROM zed_news WHERE $quer" );
	$count_news = $cms->num_rows($result);
	$pg=$cms->gen_sitepage($result,20,"/index.php?x=$id&type=news&search=".rawurlencode($search));
	$result=$cms->query("SELECT * FROM zed_news WHERE $quer order by ID desc limit $cstart,$cend");
	$count_cur = $cstart+ $cms->num_rows($result);
//	$result=$cms->query("SELECT *, MATCH TITLE AGAINST ('$search')+MATCH SMALL,FULL AGAINST ('$search') as relev FROM zed_news ORDER BY relev DESC limit $cstart,$cend");
	$data['navi']="Новостей найдено: $count_news показано : $cstart - $count_cur";
	$data['content']="";
	while ($ss=$cms->fetch_object($result))
		{		 
				$highlight = str_replace(" ", "|", $search);
				$ss->TITLE = eregi_replace($highlight, "<font color=#cc0000>\\0</font>", strip_tags($ss->TITLE));
				$ss->SMALL = eregi_replace($highlight, "<font color=#cc0000>\\0</font>", strip_tags($ss->SMALL));
				$data['content'].="<tr bgcolor=#fafafa><td class=title><b><a href=/index.php?x=$ss->CATEGORY&id=$ss->ID>$ss->TITLE</a></b><br>
				<a href=/index.php?x=$ss->CATEGORY&id=$ss->ID class=news><p align=justify>$ss->SMALL</p></a></td></tr>";
				$data['content'].="<tr bgcolor=#fafafa><td>&nbsp;</td></tr>";
		}
$str = $cms->blockparse($ZED['middle'],$data);
return $str.$pg;
}
?>