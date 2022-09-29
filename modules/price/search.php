<?
function anotherword($word)
{
	if(strlen($word)>4)
	{
		$leter=substr($word,-1);
		if(strstr('йуеыаоэяиюьъ',$leter))
		{
			$word=substr_replace($word,'',-1,1);
			$word=anotherword($word);
		}
	}
	return $word;
}
function pricenavi($cat,$id)
{
	global $cms;
	if($cat=='' || $cat==0) return "/";
	$path='';
	for ($i=0;$i<2;$i++)
	{
		$row_path  = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$cat'"));
		if($row_path->LEVEL==1)	{$srt= "$row_path->EN_NAME$path";$row_cat=$row_path->CATEGORY;}
		else 
		{
			$path.= "/$row_path->EN_NAME$path";
			$cat=$row_path->CATEGORY;
		}
	}
	$row_cat=$cms->fetch_object($cms->query("select EN_NAME from zed_category where ID='$row_cat'"));
	$url= "$row_cat->EN_NAME/$srt/$id";
	return $url;
}
function gettt($id,$level)
{
	global $cms;
	$path='';
	for ($i=0;$i<2;$i++)
	{
		$row_path  = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$id'"));
		if($row_path->LEVEL==1)	{$srt= "$row_path->EN_NAME$path";}
		else
		{
			$path.= "/$row_path->EN_NAME$path";
			$id=$row_path->CATEGORY;
		}
	}
	return $srt;
}
function checkbox2($mas)
{
	global $cms;
	$out='';
	foreach ($mas as $key)
	{
		$out.="<p><input type='checkbox' name='razm_chek[]' value='$key' >$key</p>";
	}
	return $out;
}

function search_price($mas)
{
	global $cms;$str="";$num=1;
	$search_str=$search=$mas[0];
	if(isset($_GET['x'])) $id = $_GET['x'];
	else $id = $_GET['cat'];
	$quern=$quera=$querb=$querd="";
	$s=ereg_replace("%","",$search);
	$req=explode(" ",$search);
	$str="";	$cntn=0; $cntn2=0;$cnta=0; $cnta2=0;$cntb=0; $cntb2=0;$cntd=0; $cntd2=0; $d['s']='target="_blank"';
	if($mas[1]=='name' || $mas[1]=='all')
	{
		if (count($req)>1){
			foreach($req as $word){$word=anotherword($word); $quern.="NAME LIKE '%$word%' AND ";}
			$quern = substr($quern,0,strlen($quern)-5);}
		else{$search=anotherword($search); $quern.="NAME LIKE '%$search%'";}
		$resultn=$cms->query("SELECT * FROM zed_price WHERE $quern and HIDDEN=0" );
		$cntn = $cms->num_rows($resultn);
		//$str.= "<h6>В каталоге товаров с именем найдено: $cntn</h6>";
		if($cntn)
		{
			$str.="<div class='row equal'>";$i=1;
		    while ($rowww  = $cms->fetch_object($resultn))
		    {
		  		$zurl=gettt($rowww->CATEGORY,$r->LEVEL);
		  		$d['url']='/price/'.$zurl.'/'.$rowww->ID;
				$d['name']=$rowww->NAME;
		    	$rimg=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY=$rowww->ID and `TABLE`='zed_price' order by ORD"));
				$d['src']="{$rimg->PATH}s$rimg->NAME";
				
				if($rowww->BUTTON==0){$d['button']="<a class='modalfeed' href='#divsend'>Узнать цену</a>";}
				if($rowww->BUTTON==1){$d['button']="<a class='modalfeed' href='#divsend'>Заказать</a>";}
				if($rowww->BUTTON==2){$d['button']='';}
				
				$mastype=explode(':',$rowww->TYPE_TH);
				$str_th="";
				foreach ($mastype as $key=>$value)
				{
					if($key==0){continue;}
					else
					{
						$fl=0;$str_th_p='';
						$name_th = $cms->fetch_object($cms->query("select ID,NAME from zed_select where ID='$value'"));
						$res_price_th=$cms->query("select NAME from zed_price_th where ID_TOV='$rowww->ID' and TYPE='$value' ");
						while ($row_price_th=$cms->fetch_object($res_price_th))
						{
							if($fl==0)
							{
								$fl=1;
								$len=strlen($row_price_th->NAME);
								if($value==9){$tek_class='col-xs-12 p-minus size-cost text-center';}
								elseif($len>17){$tek_class='col-xs-12 p-minus';}
								else {$tek_class='col-xs-6 p-minus';}
							}
							$str_th_p.="<span>$row_price_th->NAME</span>";
						}
						$str_th.="<div class='$tek_class'><b>$name_th->NAME:</b>$str_th_p</div>";
					}
				}
				$d['th']=$str_th;
				
				$str.=$cms->blockparse('veiw_f',$d,1);
				if($i==3)
				{
					$str.="</div><div class='row equal'>";$i=0;
				}
				$i++;
			}
			if($i==2){$str.="<div class='col-xs-4 p5p'>&nbsp;</div><div class='col-xs-4 p5p'>&nbsp;</div>";}
			if($i==3){$str.="<div class='col-xs-4 p5p'>&nbsp;</div>";}
			$str.="</div>";
		}
	}
	if($mas[1]=='desc' || $mas[1]=='all')
	{
		if (count($req)>1){
			foreach($req as $word){$word=anotherword($word); $querd.="DESCRIPTION LIKE '%$word%' AND ";}
			$querd = substr($querd,0,strlen($querd)-5);}
		else{$search=anotherword($search); $querd.="DESCRIPTION LIKE '%$search%'";}
		$resultd=$cms->query("SELECT * FROM zed_price WHERE $querd and HIDDEN=0" );
		$cntd = $cms->num_rows($resultd);	
		$str.= "<div class='row'><div class='col-xs-12 text-center margin_t1'>Товаров с описанием найдено: $cntd</div></div>";
		if($cntd)
		{
			$str.="<div class='row equal'>";$i=1;
		    while ($rowww  = $cms->fetch_object($resultd))
		    {
		    	$zurl=gettt($rowww->CATEGORY); 
				$d['name']=$rowww->NAME;
		    	$rimg=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY=$rowww->ID and `TABLE`='zed_price' order by ORD"));
				$d['src']="{$rimg->PATH}s$rimg->NAME";
				
				if($rowww->BUTTON==0){$d['button']="<a class='modalfeed' href='#divsend'>Узнать цену</a>";}
				if($rowww->BUTTON==1){$d['button']="<a class='modalfeed' href='#divsend'>Заказать</a>";}
				if($rowww->BUTTON==2){$d['button']='';}
				
				$mastype=explode(':',$rowww->TYPE_TH);
				$str_th="";
				foreach ($mastype as $key=>$value)
				{
					if($key==0){continue;}
					else
					{
						$fl=0;$str_th_p='';
						$name_th = $cms->fetch_object($cms->query("select ID,NAME from zed_select where ID='$value'"));
						$res_price_th=$cms->query("select NAME from zed_price_th where ID_TOV='$rowww->ID' and TYPE='$value' ");
						while ($row_price_th=$cms->fetch_object($res_price_th))
						{
							if($fl==0)
							{
								$fl=1;
								$len=strlen($row_price_th->NAME);
								if($value==9){$tek_class='col-xs-12 p-minus size-cost text-center';}
								elseif($len>17){$tek_class='col-xs-12 p-minus';}
								else {$tek_class='col-xs-6 p-minus';}
							}
							$str_th_p.="<span>$row_price_th->NAME</span>";
						}
						$str_th.="<div class='$tek_class'><b>$name_th->NAME:</b>$str_th_p</div>";
					}
				}
				$d['th']=$str_th;
				
				$str.=$cms->blockparse('veiw_f',$d,1);
				if($i==3)
				{
					$str.="</div><div class='row equal'>";$i=0;
				}
				$i++;
			}
			if($i==2){$str.="<div class='col-xs-4 p5p'>&nbsp;</div><div class='col-xs-4 p5p'>&nbsp;</div>";}
			if($i==3){$str.="<div class='col-xs-4 p5p'>&nbsp;</div>";}
			$str.="</div>";
		}
	}
	$num=$cntd+$cntb+$cnta+$cntn;
	$str_search="<div class='col-sx-12 text-center margin_t1 margin_b1'>По запросу <b>&laquo;$search_str&raquo;</b> найдено: <b>$num</b> позиции</div>";
	return $str_search."<div class='p15'>$str</div>";
}

function searchin_price($search)
{
	global $cnt;
	search_price($search);
	if($cnt) {return "".search_price($search)."";}
	else return "<h6 align=center>Ничего не найдено!</h6>";
}
?>