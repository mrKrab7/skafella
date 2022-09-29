<?
function strhilight($str,$find)
{
	$st=explode(" ",$str);
	$find=strip_tags($find);
	foreach($st as $word)
	{
		if($word!="") {
		$find=eregi_replace($word,"<b style=\"color:red;\">$word</b>",$find);	}
	}	
	if(strlen($find)>500)return  substr_replace($find, '...',500);
	else return $find;
}
function iffind($search,$find)
{
return 1;
}

function reg_search_string($search)
{
	$search= eregi_replace("SELECT","",$search);
	$search= eregi_replace("UPDATE","",$search);
	$search= eregi_replace("DELETE","",$search);
	$search= eregi_replace("INSERT","",$search);
	$search= eregi_replace("WHERE","",$search);
	$search= eregi_replace("UNION","",$search);
	$search= eregi_replace("JOIN","",$search);
	$search= eregi_replace("ON","",$search);
	$search = substr($search, 0, 64);
	$search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
	$good = trim(preg_replace("/\s(\S{1,2})\s/", " ", 
	ereg_replace(" +", "  "," $search ")));
	$good = ereg_replace(" +", " ", $good);
	return $good;

}
function Select_all()
{
	global $cms;
	$str = "<select name=razdels style=\" width:170px;\"><option selected value='0'>На портале</option>";
	$rez = $cms->query("select * from zed_search order by DESCRIPT");
	while($row = $cms->fetch_object($rez))
	{
		if((isset($_POST['razdels']) && $_POST['razdels']==$row->ID_CAT) || (isset($_GET['type']) && $_GET['type']==$row->MODUL))$str.="<option selected value='$row->ID_CAT'>$row->DESCRIPT</option>";
		else $str.="<option value='$row->ID_CAT'>$row->DESCRIPT</option>";
	}
	$str.="</select>";
	return $str;
}

function gen_search_form($id, $search='')
{
	global $cms;
	//$select = Select_all();
	//print_r($_POST);
	$search=$_POST['searchstring'];
	if(isset($_POST['search_in']))
		{$old_search = $_POST['old_search']." ".$_POST['searchstring'];}
	elseif(isset($_POST['searchstring'])) $old_search = $_POST['searchstring'];
	elseif (isset($_GET['search']))  $old_search = $_GET['search'];
	else {$old_search = '';}
	if (isset($_POST['name'])){$n="class='act'";}
	elseif (isset($_POST['art'])){$a="class='act'";}
	elseif (isset($_POST['brend'])){$b="class='act'";}
	elseif (isset($_POST['desc'])){$d="class='act'";}
	else {$all="class='act'";}
	$str="<form action=$cms->url method=post>
	<div class='input-group'>
		<input type='text' name='searchstring' value='$search' class='form-control btn-search'>
		<input type=hidden name=fullsearch value=1>
		<input type=hidden name=old_search value='$old_search'>
		<span class='input-group-btn'><input type=hidden  name=fullsearch value=1>
			<button value='&nbsp;' class='btn btn-search' type='submit'><span class='fa fa-search'>  </span></button>
		</span>
	</div>
	</form>";
	if(!isset($_POST['search_btn']))
	{
		return $str;
		//$data['content']=$str; 
		//$data['navi']="Поиск по сайту :"; 
		//return $cms->blockparse("middle",$data,1);
	}
	return $str;
}
function search($id)
{
	global $cms;
	$search_result="";
	//print_r($_GET);
	//print_r($_POST);
	//print_r($_SESSION);
if(isset($_SESSION['search']) && $_SESSION['search'])
{
$_POST['searchstring']=$_SESSION['search']; 
if($_SESSION['whea']=='name') {$_POST['name']='по названию';}
elseif ($_SESSION['whea']=='brend'){$_POST['brend']='по бренду';}
elseif ($_SESSION['whea']=='art'){$_POST['art']='по артиклу';}
elseif ($_SESSION['whea']=='desc'){$_POST['desc']='по особенностям модели';}
elseif ($_SESSION['whea']=='all'){$_POST['search_btn']='везде';}
unset($_SESSION['search']);unset($_SESSION['whea']);
}
	$row = $cms->fetch_object($cms->query("select MODUL from zed_search "));
	$modul = $row->MODUL;
	if(isset($_POST['searchstring']) && $_POST['searchstring'])
	{
		$search = $_POST['searchstring'];
		if(isset($_POST['search_in']))
		{
			$search = $_POST['old_search']." ".$search;
		}
		$search=reg_search_string($search);
		$err=0;
		if(strlen($search) <1) {$err=1; $errmess="Ваш запрос слишком короткий"; }
		if(substr_count($search,'хуйня')||substr_count($search,'ХУЙНЯ')||substr_count($search,'Хуйня')){$err=1; $errmess="Мы - Фирма солидная, \"Хуйню\" не держим";}
		if($err) 
		{
			$data['navi']=$errmess;
			$data['content']=gen_search_form($id);
			return $cms->blockparse('middle',$data,1);
		}
		else 
		{
			if(isset($_POST['razdels']) && $_POST['razdels']!='0')
			{
				$cat_s = $_POST['razdels'];
			//	$row_s = $cms->fetch_object($cms->query("select * from zed_search where ID_CAT='$cat_s'"));
				//$modul = $row_s->MODUL;
				include "zed/modules/$modul/search.php";
				$search_result = call_user_func("searchin_$modul",$search);
			}
			else
			{
				$rez_ss = $cms->query("select * from zed_search order by MODUL");
				$search_result="";
				while($row_s = $cms->fetch_object($rez_ss))
				{
					$modul = $row_s->MODUL;
					$mas[0]=$search;					
					if (isset($_POST['name'])){$mas[1]='name';}
					elseif (isset($_POST['art'])){$mas[1]='art';}
					elseif (isset($_POST['brend'])){$mas[1]='brend';}
					elseif (isset($_POST['desc'])){$mas[1]='desc';}
					else {$mas[1]='all';}	
					include "zed/modules/$modul/search.php";
					$search_result.= call_user_func("search_$modul",$mas);
					$_SESSION['s']=$search;
					$_SESSION['w']=$mas[1];
				}
				/*$search_result="<table width=100%>".$search_result."</table>";/**/
			}
		}
	}/*
	if(isset($_GET['search']) && $_GET['search']!='')
	{
		//$search = $_GET['search'];
		//$modul = $_GET['type'];
		include "zed/modules/$modul/search.php";
		$search_result = call_user_func("search_$modul",$search);
//		$search_result = call_user_func("searchin_$modul",$search);
	}/**/
	$data['classn']='class="search_n"';
	$data['classc']='class="search_c"';
	$data['content']=gen_search_form($id,$search).$search_result;
	$data['navi']='';//"Результат поиска: ".$search;
	//.$search_result
	return $cms->blockparse("middle",$data,1);//."<br>".$pg;
}

####################
if (isset($_GET['x']))
{
	$cms->gen_content($_GET['x']);
	$zsite['navi']=$cms->sitenavi($_GET['x']);	
	if(isset($_POST['searchstring']) || isset($_GET['search']) || $_SESSION['search']) 
	{
		$zsite['middle']=search($_GET['x']);
	}
	else
	{
		//$data['navi']="";
		$data['content']=gen_search_form($_GET['x']);
		//$data['classn']='class="search_n"';
		//$data['classc']='class="search_c"';
		$zsite['middle']=$cms->blockparse("middle",$data,1);
	}
}
?>