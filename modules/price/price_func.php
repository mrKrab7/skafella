<?
function select($mas,$name)
{
	global $cms;
	$out='';
	foreach ($mas as $value => $key)
	{
		$out.="<option  value='$value' >$key</option>";
	}
	$out="<select class='form-control' name='$name' >$out</select>";
	return $out;
}

function gen_site_menu_price($id,$url='',$lev=1)
{
	global $cms;
	$str='';
	if($url=='') $url = $cms->get_url_from_id($id);
	$res = $cms->query("select * from zed_price_category where CATEGORY='$id' order by ORD");
	while ($row  = $cms->fetch_object($res))
	{
		$data['name'] = $row->NAME;
		$data['url'] = "$url/$row->EN_NAME";
		
		$data['class'] = '';
		$data['class1'] = '';
		if(strstr($_SERVER['REQUEST_URI'],$data['url'])) $data['class1'] = 'class="active"';
		$data['menu'] = gen_site_menu_price($row->ID,"$url/$row->EN_NAME",$lev+1);
		if($data['menu'] =='' && $row->PRICE==0) continue;
		if($row->PRICE) $str .= $cms->blockparse("menu_price",$data,1)."\n";
		elseif($lev==1) $str .= $cms->blockparse("menu_price_no",$data,1)."\n";
		else  $str .= $cms->blockparse("menu_price_n",$data,1)."\n";
		
	}
	return $str;	
}
function gettt($id,$level)
{
	global $cms;
	$path='';
	for ($i=0;$i<$level;$i++)
	{
		$row_path  = $cms->fetch_object($cms->query("select * from zed_price_category where ID='$id'"));
		if($row_path->LEVEL==1)	{$srt= "$row_path->EN_NAME$path";}
		else 
		{
			$path= "/$row_path->EN_NAME$path";
			$id=$row_path->CATEGORY;
		}
	}
	return $srt;
}

function view_price($id,$url,$p=0)
{
	global $cms,$zsite;
	$dd['content']='';
	$dd['content'].="<div id='divsend'>	<h2>Запрос</h2>	
<form id='feedback' name='feedback' action='#' method='post'>		
<label for='name'>Ф.И.О</label>		<input type='name' id='name' name='name' class='txt'>		
<label for='tel'>Ваш телефон</label>		<input type='tel' id='tel' name='tel' class='txt'>	
<input type='hidden' id='number' name='number' class='number'>	
<button id='send'>Отправить</button>	</form></div>";	
	$img='';
	if($p!=0)
	{
		$result = $cms->fetch_object($cms->query("select * from zed_price where ID=$p "));
		$data['id']=$result->ID;
		$data['url']=$url.'/'.$result->ID;
		$data['name']=$result->NAME;		
		$flag=1;$data['image_dop']='';
		$res  = $cms->query("select * from zed_image where CATEGORY=$result->ID and `TABLE`='zed_price' order by ORD");
		while ($rimg = $cms->fetch_object($res))
		{
			if($flag==1){$flag=0;$data['images']="<div  class='col-xs-12 border_img' ><img itemprop='image' src='{$rimg->PATH}s$rimg->NAME' alt='Купить $result->NAME в Новосибирске.'  /></div>";}
			else {$data['image_dop']="<div  class='col-xs-12 col-xs-2' ><img itemprop='image' src='{$rimg->PATH}s$rimg->NAME' alt='Купить $result->NAME в Новосибирске.'  /></div>";}
		}
		
		if($result->BUTTON==0){$data['button']="<a class='modalfeed' href='#divsend' data-id='$result->ID'>Узнать цену</a>";}
		if($result->BUTTON==1){$data['button']="<a class='modalfeed' href='#divsend' data-id='$result->ID'>Заказать</a>";}
		if($result->BUTTON==2){$data['button']='';}
		
		$mastype=explode(':',$result->TYPE_TH);
		$str_th="";
		foreach ($mastype as $key=>$value)
		{
			if($key==0){continue;}
			else
			{
				$fl=0;$str_th_p='';
				$name_th = $cms->fetch_object($cms->query("select ID,NAME from zed_select where ID='$value'"));
				$res_price_th=$cms->query("select NAME from zed_price_th where ID_TOV='$result->ID' and TYPE='$value' ");
				while ($row_price_th=$cms->fetch_object($res_price_th))
				{

					if($fl==0){$fl=1;$tek_class='col-xs-4 p-minus';}
					$str_th_p.="<br><span>$row_price_th->NAME</span>";
					if($value==9)
					{
						$tek_class='col-xs-12 p-minus size-cost margin_t1 red';
						$str_th_p="<span> $row_price_th->NAME</span>";
					}
						
				}
				$str_th.="<div class='$tek_class'><b>$name_th->NAME:</b>$str_th_p</div>";
			}
		}
		$data['th']=$str_th;
				
		$data['desc1']="<span class='desc' itemprop='description'>$result->DESCRIPTION</span>";
		$data['desc3']=$result->OPISANIE;
		
		$result_cat = $cms->fetch_object($cms->query("select SEOTEXT from zed_price_category where ID=$result->CATEGORY"));
		if($result_cat->SEOTEXT!='' && $result_cat->SEOTEXT!='&nbsp;')
		{$data['desc2']="<div class='col-xs-12 double_solid'>&nbsp;</div><div class='col-xs-12'><noindex>$result_cat->SEOTEXT</noindex></div>";}
		else $data['desc2']='';
		
		
		$zsite['title']=$result->NAME;
    	$zsite['keywords']=$result->NAME;
		$zsite['description']=$result->NAME;
		
		
		$dd['classc']='class="row" itemscope itemtype="http://schema.org/Product"';
		$dd['content'].=$cms->blockparse("veiw_once",$data,1);
    }
    else 
    {    	
    	$r = $cms->fetch_object($cms->query("select * from zed_price_category where ID=$id"));
    	if($r->TITLE!=''){$zsite['title']=$r->TITLE;}
    	if($r->KEYWORDS!='')	$zsite['keywords']=$r->KEYWORDS;
		if($r->DESCRIPTION!='')	$zsite['description']=$r->DESCRIPTION;
    	if($r->H!=''){$h1=$r->H;}
    	if($r->SEOTEXT!=''){$seotext=$r->SEOTEXT;}
    	//$dd['content'].='<h1>'.$h1.'</h1><div>'.$seotext.'</div><br>';
		$sql='';
    	
//print_r($_POST); die();
    	$div['class']="class='p15'";//class='row'
		$result = $cms->query("select * from zed_price where CATEGORY=$id and HIDDEN=0 $sql order by ORD");
	    if($cms->num_rows($result))
	    {
	    	$div['div'].="<div class='row equal'>";$i=1;
		    while ($rowww  = $cms->fetch_object($result))
		    {
	
		    	$d['name']=$rowww->NAME;
		    	$zurl=gettt($rowww->CATEGORY,$r->LEVEL); 
		    	$d['url']=$url_tek='/price/'.$zurl.'/'.$rowww->ID;
		    	
		    	$rimg=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY=$rowww->ID and `TABLE`='zed_price' order by ORD"));
				$d['src']="{$rimg->PATH}s$rimg->NAME";
				
				if($rowww->BUTTON==0){$d['button']="<a class='modalfeed' href='#divsend' data-id='$url_tek'>Узнать цену</a>";}
				if($rowww->BUTTON==1){$d['button']="<a class='modalfeed' href='#divsend' data-id='$url_tek'>Заказать</a>";}
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
						$str_th.="<div class='$tek_class'><b>$name_th->NAME: </b>$str_th_p</div>";
					}
				}
				$d['th']=$str_th;
				
				$div['div'].=$cms->blockparse('veiw_f',$d,1);
				if($i==3)
				{
					$div['div'].="</div><div class='row equal'>";$i=0;
				}
				$i++;			
			}
			if($i==2){$div['div'].="<div class='col-xs-4 p5p'>&nbsp;</div><div class='col-xs-4 p5p'>&nbsp;</div>";}
			if($i==3){$div['div'].="<div class='col-xs-4 p5p'>&nbsp;</div>";}
			$div['div'].="</div>";
		}
		else $div['div'].='В этом разделе каталога нет товаров';
    	$dd['content'].=$cms->blockparse('div',$div,1);
	}
	return $dd;
}

function show_price($id,$url,$l=0)
{
	global $cms,$zsite;

	if($l!=0)
	{
		$r = $cms->fetch_object($cms->query("select * from zed_price_category where ID=$id"));
		if($r->TITLE!=''){$zsite['title']=$r->TITLE;}
    	if($r->KEYWORDS!='')	$zsite['keywords']=$r->KEYWORDS;
		if($r->DESCRIPTION!='')	$zsite['description']=$r->DESCRIPTION;
    	if($r->H!=''){$h1=$r->H;}
    	if($r->SEOTEXT!=''){$seotext=$r->SEOTEXT;}
    	    $str="Вам необходимо выбратьрубрику";
        	$data['content'].='<div class="col-sx-12">'.$str.'</div>';
	}
	else 
	{
		$r = $cms->fetch_object($cms->query("select * from zed_category where ID=$id"));
		if($r->TITLE!=''){$zsite['title']=$r->TITLE;}
    	if($r->KEYWORDS!='')	$zsite['keywords']=$r->KEYWORDS;
		if($r->DESCRIPTION!='')	$zsite['description']=$r->DESCRIPTION;
    	if($r->H!=''){$h1=$r->H;}
        	$str="Вам необходимо выбратьрубрику";
        	$data['content'].='<div class="col-sx-12">'.$str.'</div>';
	}
	return $data;
}

function price($id)
{
	global $cms;
	
	$parent = $id;
	$navi ="";// $cms->sitenavi($_GET['x']);
	$data['cat'] = $data['page'] = $data['id'] =  '';
	$url = $cms->url;
	while (isset($_GET[$cms->rubrics_level]) && $_GET[$cms->rubrics_level]!='')
	{
		$lev = $_GET[$cms->rubrics_level];
		$res = $cms->query("select ID,NAME,CATEGORY,PRICE from zed_price_category where CATEGORY='$parent' and EN_NAME='$lev' ");
		if($cms->num_rows($res)>0)
		{
			$row = $cms->fetch_object($res);
			if($row->PRICE==1)
			{
				$parent = $row->ID;
				$data['id'] = $row->ID;
				$cms->rubrics_level++;
				$url.="/$lev";
			}
			else 
			{
				$parent = $row->ID;
				$data['cat'] = $row->ID;
				$cms->rubrics_level++;
				$url.="/$lev";
			}
			$navi.="<p><a href='$url'>$row->NAME</a> :: </p> ";
		}
		else break;
	}
	//echo $navi; print_r($_GET); print_r($_GET);print_r($_POST);
	//echo 'ID-'.$data['id'].'CAT-'.$data['cat'].'$id-'.$id;
	if(!is_int($lev)) $p=$lev;
	else $p=0;
	if($data['id']!='')
	{ //echo '+';
		$dd['classc']='';
		$dd = view_price($data['id'],$url,$p); 
		//$dd['navi']='';//'<div class="navicat">'.$navi.'</div>';
		return $cms->blockparse("middle",$dd,1);
	}
	else 
	{//echo '-';
		$dd['classc']='';
		if(isset($data['cat']) && $data['cat']!='')
		$dd = show_price($data['cat'],$url,1);
		else $dd = show_price($id,$url);
		//$dd['navi']='';//'<div class="navicat">'.$navi.'</div>';
		return $cms->blockparse("middle",$dd,1);
	}	
}
?>