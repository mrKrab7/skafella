<?php
class zedcms
{
	//variables
	var $result;
	var $rubrics_level=1;
	var $url = '';
	var $name = '';
	var $dbname='';
	var $ie6=0;
	var $db_count="";
	var $db = array('users'=>"");
	var $cur_dbname="";
	var $old_cur_dbname="";
	var $dbtype = "";
	var $act_menu = array();

	// генератор контента для контентных блоков.
function tplmanager($id)
{
	global $zsite, $cms;
	$mas = array();
	global $REMOTE_ADDR, $DOCUMENT_ROOT,$HTTP_USER_AGENT; // для работы c register_globals=off
	
	$result=$this->query("select TPL from zed_tplblock");
	while($row=$this->fetch_object($result)) $zsite[$row->TPL]="";
	
	$result=$this->query("select * from zed_siteinfo");
	while($row=$this->fetch_object($result))	$zsite[$row->TYPE]=$row->VALUE;	
	
	$result=$this->query("select * from zed_tplmanager where (WHER='$id' AND WHAT<>0) or ( WHER='-1' AND REWRITE=0 ) order by ID ASC");
	while($row=$this->fetch_object($result))
	{
		$row2=$this->fetch_object($this->query("select NAME,TYPE from zed_category where ID='$row->WHAT'"));
		$identy=$row->WHAT;
		$name = $row2->NAME;
		$url = $this->get_url_from_id($identy);
		$row->SHABLON==''?	$shablon_tpl = $row->TPL : $shablon_tpl = $row->SHABLON;
		if(isset($data)) unset($data);
		include("zed/modules/$row2->TYPE/short.php");
		@$zsite[$row->TPL].=$this->blockparse($shablon_tpl,$data,1);
		$mas[$row->TPL] = 1;
	}
	
	$result=$this->query("select * from zed_tplmanager where WHER='-1' AND  REWRITE=1");
	while($row=$this->fetch_object($result))
	{
		if(isset($mas[$row->TPL])) continue;
		$row2=$this->fetch_object($this->query("select NAME,TYPE from zed_category where ID='$row->WHAT'"));
		$identy=$row->WHAT;
		$name = $row2->NAME;
		$url = $this->get_url_from_id($identy);
		$row->SHABLON==''?	$shablon_tpl = $row->TPL : $shablon_tpl = $row->SHABLON;
		if(isset($data)) unset($data);
		include("zed/modules/$row2->TYPE/short.php");
		@$zsite[$row->TPL].=$this->blockparse($shablon_tpl,$data,1);
	}
}

	function get_date()
	{
		$t=time();
		return date("d_m_Y",$t);
	}
	#################################ADMIN METODS
	//навигация в системе администрирования
	function navi($id)
	{
		$str="";
		if ($id==0)
		{
			$str="<a href=\"?modul=category\"> Рубрикатор </a>".$str;
			return $str;
		}
		else
		{
			$row=$this->fetch_object($this->query("select * from zed_category where ID=$id"));
			$str=" > <a href=\"?modul=$row->TYPE&action=open&id=$row->ID\">$row->NAME</a>".$str;
			$str=$this->navi($row->PARENT).$str;
			return $str;
		}
	}
	function is_search($s_str,$id,$type=0)
	{
		if($s_str=='') return;
		$mas = explode("qoogle",$s_str);
		if(count($mas)>1)
		{
			// Это google
			$mm = explode("q=",$s_str);
			$m = explode('&',$mm[1]);
			$str = urldecode($m[0]);
			$str = str_replace("+"," ",$str);
			if($type==1)
			{
				$this->query("insert into zed_keywords (ID,P_ID,WORDS) values ('null','$id','$str')");
			}

		}

	}
	function counter()
	{
		$adb = $this->cur_dbname;
		$this->seldb($this->db_count);
		$IP =$_SERVER['REMOTE_ADDR'];
		$URL = $_SERVER['REQUEST_URI'];
		if(isset($_SERVER['HTTP_REFERER']))	$REQUEST = $_SERVER['HTTP_REFERER'];
		else $REQUEST = "";
		if($_SERVER['HTTP_ACCEPT']=="*/*") return;
		$DATA = date("Y-m-d");
		$SITE = $_SERVER['HTTP_HOST'];
		$res = $this->query("select ID,COUNT from zed_counter where SITE='$SITE' and DATA='$DATA' and IP='$IP' and REQUEST='$REQUEST' and URL='$URL'");
		if($this->num_rows($res)>0)
		{
			$row = $this->fetch_object($res);
			$count = $row->COUNT+1;
			$this->query("update zed_counter set COUNT='$count' where ID='$row->ID'");
			$this->is_search($REQUEST,$row->ID);
		}
		else
		{
			$this->query("insert into zed_counter (ID,SITE,IP,REQUEST,URL,COUNT,DATA) values ('null','$SITE','$IP','$REQUEST','$URL','1','$DATA') ");
			$this->is_search($REQUEST,$this->insert_id(),1);
		}
		$this->seldb($adb);
	}
	function modRewrite()
	{
		if(stristr($_SERVER["HTTP_USER_AGENT"], "MSIE") && !stristr($_SERVER["HTTP_USER_AGENT"], "7."))
		$this->ie6=1;
		if(isset($_GET['rule']))
		{
			$rule  = explode("/",$_GET['rule']);//$rule  = split("/",$_GET['rule']);
			$_GET[] = '';
			foreach ($rule as $key)
			{
				if($key=='') continue;
				$_GET[] = $key;
			}
			unset($_GET[0]);
			unset($_GET['rule']);
		}		
		foreach($_GET as $key=>$value)
		{
			if(!intval($key))
			{
				$_GET[$key] = str_replace("+"," ",$value);
				continue;
			}
			$param[] = $value;
		}
		if(isset($param))
		{
			$x = 0;
			$y = 0;
			$modul='';
			//$par = 0;
			for($i=0;$i<count($param);$i++)
			{
				$mod = $param[$i];
				if($mod=='') break;
				if($y) $parent = "PARENT='$y'";
				else  $parent = "PARENT='$x'";
				$rez = $this->query("select ID,PARENT,TYPE,NAVIGATE,NAME from zed_category where EN_NAME='$mod' and $parent");
				if($this->num_rows($rez)>0)
				{
					$row = $this->fetch_object($rez);
					$this->rubrics_level++;
					if($row->NAVIGATE==1)
					{
						$y  = 0;
						$x = $row->ID;
						$modul = $row->TYPE;
						$this->name = $row->NAME;
					}
					else {
						$y = $row->ID;
					}
					//$par = $row->ID;
				}
				else
				{
					break;
				}
			}
			if($x!=0) $_GET['x'] = $x;
			$_GET['mod'] = $modul;
			$this->url = $this->get_url_from_id($x);
		}
		//echo " x = $x modul = $modul pub_lev = $this->rubrics_level";
		//print_r($param);
	}
	function get_url_from_id($id)
	{
		if($id=='-1' || $id==0) return '';
		$edb = $this->cur_dbname;
		if($edb != $this->dbname) $this->seldb($this->dbname);
		$rez = $this->fetch_object($this->query("select ID,PARENT,EN_NAME from zed_category where ID='$id'"));
		$this->seldb($edb);
		$str = "/$rez->EN_NAME";
		if($rez->PARENT!='0')
		{
			$str = $this->get_url_from_id($rez->PARENT).$str;
		}
		return $str;
	}
	//Функция возвращает список для выбора из установленных модулей
	function get_modules()
	{
		include "inc/modules.ini";
		$i=0;
		$str="<select name=mod>";
		foreach($modules as $a=>$b)
		{	
			$str.="<option value='$a'>$b</option>";
			$i++;
		}
		return $str;
	}
	function get_modul($id)
	{
		$type='';
		$res = $this->query("select TYPE from zed_category where ID='$id'");
		if($this->num_rows($res)>0)
		{
			$rs = $this->fetch_object($res);
			$type = $rs->TYPE;
		}
		return $type;
	}
	// ресайзинг изображения
function img_resize($src, $dest,$width='',$height='',$max_h_w='', $rgb=0xFFFFFF, $quality=100)
{
	if (!file_exists($src)) return "не найден исходный файл<br />";
	$size = getimagesize($src);
	if ($size === false) return "ошибка исходного файла<br />";
	if($width=='' && $height==''){ $width = $size[0]; $height = $size[1]; }
	$format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
	$icfunc = "imagecreatefrom".$format;
	if (!function_exists($icfunc))  return "не установлена функция $icfunc<br />";
	if($width!='') $x_ratio = $width / $size[0];
	if($height!='') $y_ratio = $height / $size[1];
	if($width!='' && $height!='')
	{
		// точные размеры
		if($x_ratio>1 || $y_ratio>1) return "картинка меньше заказанных размеров<br />";
		$new_width   = $width;
		$new_height  = $height;
	}
	elseif ($width!='')
	{
		// точная ширина
		if($max_h_w!='') 
		{
			$y_ratio = $max_h_w / $size[1];
			$ratio = min($x_ratio, $y_ratio);
		}
		else $ratio = $x_ratio;
	}
	else 
	{
		// точная высота или пропорция
		if($max_h_w!='') 
		{
			$x_ratio = $max_h_w / $size[0];
			$ratio = min($x_ratio, $y_ratio);
		}
		else $ratio = $y_ratio;
	}
	$src_left    = 0;
	$src_top     = 0;
	if(!($width!='' && $height!=''))
	{
		// точная высота или ширина в пропорции
		if($ratio>=1) { copy($src,$dest); return '';}
		$new_width   = floor($size[0] * $ratio);
		$new_height  = floor($size[1] * $ratio);
	}
	else 
	{
		// точные размеры картинки
		if($height/$width > $size[1]/$size[0])
		{
			$prop = $size[1]/$height;
			$s0  = $width*$prop;
			$src_left = floor(($size[0]-$s0)/2.0);
			$size[0] = floor($s0);
		}
		elseif ($height/$width < $size[1]/$size[0])
		{
			$prop = $size[0]/$width;
			$s1  = $height*$prop;
			$src_top = floor(($size[1]-$s1)/2.0);
			$size[1] = floor($s1);
		}
	}
	$isrc = $icfunc($src);
	$idest = imagecreatetruecolor($new_width, $new_height);
	imagefill($idest, 0, 0, $rgb);
	if($icfunc!="imagecreatefromjpeg")
	{
		$rgbr =  base_convert(substr($rgb,0,2),16,10);
		$rgbg =  base_convert(substr($rgb,2,2),16,10);
		$rgbb = base_convert(substr($rgb,4,2),16,10);
		// base_convert 
		$white = imagecolorallocate ($idest,$rgbr,$rgbg,$rgbb);
		imagecolortransparent($idest,$white);
	}
	imagecopyresampled($idest, $isrc, 0, 0, $src_left , $src_top , $new_width, $new_height, $size[0], $size[1]);
	$img = "image" . $format;
	if(!function_exists($img)) return "не установлена функция $img<br />";
	if($format=="jpeg") $img($idest, $dest, $quality);
	else $img($idest, $dest);
	imagedestroy($srcp);
	imagedestroy($isrc);
	imagedestroy($idest);
	return '';
}

function img_resize_s($src, $dest,$width='',$height='',$max_h_w='', $rgb=0xFFFFFF, $quality=100)
{
	if (!file_exists($src)) return "не найден исходный файл<br />";
	$size = getimagesize($src);
	if ($size === false) return "ошибка исходного файла<br />";
	if($width=='' && $height==''){ $width = $size[0]; $height = $size[1]; }
	$format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
	$icfunc = "imagecreatefrom".$format;
	if (!function_exists($icfunc))  return "не установлена функция $icfunc<br />";
	if($width!='') $x_ratio = $width / $size[0];
	if($height!='') $y_ratio = $height / $size[1];
	if($width!='' && $height!='')
	{
		// точные размеры
		if($x_ratio>1 || $y_ratio>1) return "картинка меньше заказанных размеров<br />";
		$new_width   = $width;
		$new_height  = $height;
	}
	elseif ($width!='')
	{
		// точная ширина
		if($max_h_w!='')
		{
			$y_ratio = $max_h_w / $size[1];
			$ratio = min($x_ratio, $y_ratio);
		}
		else $ratio = $x_ratio;
	}
	else
	{
		// точная высота или пропорция
		if($max_h_w!='')
		{
			$x_ratio = $max_h_w / $size[0];
			$ratio = min($x_ratio, $y_ratio);
		}
		else $ratio = $y_ratio;
	}
	$src_left    = 0;
	$src_top     = 0;
	if(!($width!='' && $height!=''))
	{
		// точная высота или ширина в пропорции
		if($ratio>=1) { copy($src,$dest); return '';}
		$new_width   = floor($size[0] * $ratio);
		$new_height  = floor($size[1] * $ratio);
	}
	else
	{
		// точные размеры картинки
		if($height/$width > $size[1]/$size[0])
		{
			$prop = $size[1]/$height;
			$s0  = $width*$prop;
			$src_left = floor(($size[0]-$s0)/2.0);
			$size[0] = floor($s0);
		}
		elseif ($height/$width < $size[1]/$size[0])
		{
			$prop = $size[0]/$width;
			$s1  = $height*$prop;
			$src_top = floor(($size[1]-$s1)/2.0);
			$size[1] = floor($s1);
		}
	}
	$isrc = $icfunc($src);
	$idest = imagecreatetruecolor($new_width, $new_height);
	imagefill($idest, 0, 0, $rgb);
	if($icfunc!="imagecreatefromjpeg")
	{
		$rgbr =  base_convert(substr($rgb,0,2),16,10);
		$rgbg =  base_convert(substr($rgb,2,2),16,10);
		$rgbb = base_convert(substr($rgb,4,2),16,10);
		// base_convert
		$white = imagecolorallocate ($idest, $rgbr, $rgbg, $rgbb);
		imagecolortransparent($idest,$white);
	}
	imagecopyresampled($idest, $isrc, 0, 0, $src_left , $src_top , $new_width, $new_height, $size[0], $size[1]);
	$img = "image" . $format;
	if(!function_exists($img)) return "не установлена функция $img<br />";
	if($format=="jpeg") $img($idest, $dest, $quality);
	else $img($idest, $dest);
	imagedestroy($srcp);
	imagedestroy($isrc);
	imagedestroy($idest);
	return '';
}


	// создание меню для админа
	function create_admin_menu()
	{
		$res="<ul>";
		$rank=$_SESSION['rank'];
		$result=$this->query("select * from zed_admin_menu where $rank>=RANK order by ID");
		while($row=$this->fetch_object($result))
		{
			if($row->NAME=="hr")$res.="<hr>";
			else $res.="<li><a href='$row->LINK'>$row->NAME</a></li>";
		}
		return $res."</ul>";
	}
	# вывод сообщения;
	function message($message)
	{
		$data['message']=$message;
		return $this->blockparse('message',$data,3);
	}
	#################################DATABASE METODS
	//Функция возвращает результат запроса
	function query($sql,$db='')
	{
		if($this->dbtype=="mysql")
		{
			if($db!='') $this->seldb($db);
			$result=mysql_query($sql) or  die ("Ошибка в запросе $sql:".mysql_error());
			if($db!='') $this->retdb();
		}
		return $result;
	}
	function fetch_object($sql)
	{
		if($this->dbtype=="mysql")
		{
			$result=mysql_fetch_object($sql);
		}
		return $result;
	}
	function num_rows($sql)
	{
		if($this->dbtype=="mysql")
		{
			$result=mysql_num_rows($sql);
		}
		return $result;
	}
	function insert_id()
	{
		if($this->dbtype=="mysql")
		{
			$result=mysql_insert_id();
		}
		return $result;
	}
	//проверка есть ли в базе така запись
	function check_if_exist($tocheck,$table,$fieldis)
	{
		$num_rows=0;
		$sql="SELECT * FROM $table WHERE $fieldis='$tocheck'";
		$result = $this->query($sql);
		$num_rows=$this->num_rows($result);
		return $num_rows;
	}
	//проверка есть ли в базе така запись, для ячейки где много 
	//записей "вида запись1:запис2:запись3"
	function check_if_exist_razdel($tocheck,$table,$fieldis)
	{
		$num_rows=0;
		$where=":$tocheck:";
		$sql="SELECT * FROM $table WHERE $fieldis like '%$where%'";
		$result = $this->query($sql);
		$num_rows=$this->num_rows($result);
		return $num_rows;
	}
	//выбор базы данных
	function seldb($name,$key=0)
	{
		if($name==$this->cur_dbname) return ;
		if($this->dbtype=="mysql")
		{
			if(!$key)
			{
				mysql_select_db($name);
				$this->cur_dbname = $name;
			}
			else 
			{
				
			}
		}
	}
	//возврат к основной базе данных
	function retdb()
	{
		if($this->cur_dbname==$this->dbname) return ;
		if($this->dbtype=="mysql")
		{
			mysql_select_db("$this->dbname");
			$this->cur_dbname = $this->dbname;
		}
	}
	#################################TEMPLATES METODS
	# подстановка блока шаблона, возвращает шаблон с подставленными данными, 
	//для вставки в другой шаблон
	function blockparse_old($tpl,$array = "")
	{
		if($array)
		{
			foreach($array as $key => $value)
			{
				$tpl = str_replace("{".strtoupper($key)."}",$value,$tpl);
			}
		}
		return $tpl;
	}

	function blockparse($tpl_name,$array = "", $lev=0)
	{
		global $ZED;
		if(!isset($ZED[$tpl_name]))
		{
			$adb = $this->cur_dbname;
			if($adb!=$this->dbname) $this->seldb($this->dbname);
			$row=$this->fetch_object($this->query("select TPL from zed_tpl where NAME like '%:$tpl_name:%' and TYPE='$lev'"));
			$this->seldb($adb);
			if($row) $ZED[$tpl_name] = stripcslashes($row->TPL);
			else return " шаблона $tpl_name нет ";

		}
		$tpl = $ZED[$tpl_name];
		if($array)
		{
			foreach($array as $key => $value)
			{
				$tpl = str_replace("{".strtoupper($key)."}",$value,$tpl);
			}
		}
		return $tpl;
	}
	#################################FACTORY METODS
	# создать форму для авторизации
	function create_login_form()
	{
		$data['action']="";
		if($_SERVER['QUERY_STRING']!='')$data['action'].="?{$_SERVER['QUERY_STRING']}";
		$data['class']='';
		$data['login']="Логин";
		$data['loginin']="Войти";
		$data['password']="Пароль";
		$res=$this->blockparse('loginform',$data,3);
		return $res;
	}
	// генерирует список страниц, и выводит текущую страницу
	function gen_page($sa_result,$items,$paramstring)
	{
		global $prpage,$cstart,$cend;
		if(isset($_GET['prpage'])) $prpage = $_GET['prpage'];
		$countpages=$this->num_rows($sa_result);

		if (($prpage==1)or(!$prpage))
		{
			$pg=1;
			if ($countpages<$items)
			{
				$cstart=0;
				$cend=$countpages;
			}
			else
			{
				$cstart=0;
				$cend=$items;
			}
		}
		else
		{
			$pg=$prpage;
			$cstart=$prpage*$items-$items;
			$cend=$items;
		}
		if ($cstart<0) $cstart=0;

		$y=0;
		$j=1;
		$cpg=": <a href=".$paramstring."&prpage=".$j.">".$j."</a> :";


		for($i=1;$i<$countpages;$i++)
		{
			$y++;
			if ($y==$items)
			{
				$y=0;
				$j++;
				$cpg=$cpg." <a href=".$paramstring."&prpage=".$j.">".$j."</a> : ";
			}
		}

		$pg="<div align=center><table width=90% cellspacing=0 cellpadding=0><tr>
		<td style=\"font-size:14px;\"><b>$cpg</b></td>
		<td style=\"width:30px; height:30px; background-color:#0076c9; font-size:24px; color:#ffffff;\" align=center>$pg</td>
		</tr></table></div>";
		$sa_content=$pg;
		return $sa_content;
	}
	
	function gen_sitepage($sa_result,$items,$paramstring, $lvl='')
	{
		global $cstart,$cend;
		if($lvl=='') $lvl = $this->rubrics_level;
		if(isset($_GET["$lvl"]) && $_GET["$lvl"]!='')
		{
			if(substr($_GET["$lvl"],0,2)=="pg")
			$prpage=substr($_GET["$lvl"],2);
		}
		$countpages=$this->num_rows($sa_result);

		if (!isset($prpage) || $prpage==1)
		{
			$pg=1;
			$cstart=0;
			$cend=$items;
			if ($countpages<=$items) return "";
		}
		else
		{
			$pg=$prpage;
			$cstart=$prpage*$items-$items;
			$cend=$items;
		}
		if ($cstart<0) $cstart=0;
		$kol_page = ceil($countpages/$items);
		$str = ": [$pg] :";
		for($j=1;$kol_page/$j>1;$j*=10)
		{
			if($j==1)
			{
				$b = $pg-1;
				$e = $pg+1;
			}
			if($j==1) $step = 3;
			else $step=6;
			for($i=1;$i<$step;$i++)
			{
				if($b>0) $str =": <a href=\"$paramstring/pg$b\">$b</a> ".$str;
				if($e<$kol_page+1) $str =$str." <a href=\"$paramstring/pg$e\">$e</a> :";
				$b -=$j;
				$e +=$j;
			}
			$b =floor($b/($j*10))*$j*10;
			$e =ceil($e/($j*10))*$j*10;
			if($pg-$b < 3) $b-=($j*10);
			if($e-$pg < 3) $e+=($j*10);
		}
		if($pg > 3) $str = " : <a href=$paramstring title='первая'>1</a> ".$str;
		if($kol_page-$pg > 3) $str = $str." <a href=$paramstring/pg$kol_page  title='последняя'>$kol_page</a> : ";
		if($pg==1) $str = "<< ".$str;
		else
		{
			$i=$pg-1;
			$str = "<a href=\"$paramstring/pg$i\"  title='предыдущая'><<</a>".$str;
		}
		if($pg==$kol_page) $str.=" >>";
		else
		{
			$i=$pg+1;
			$str= $str." <a href=$paramstring/pg$i  title='следующая'>>></a> ";
		}
		$data['cpg']=$str;
		$pg=$this->blockparse('pages',$data,4);
		return $pg;
	}
	#################################AUTH METODS
	function check_usl($type)
	{
		if(!$this->check_login()) return false;
		$usl=$this->fetch_object($this->query("select id from zed_billing_servises where TYPE='$type'"));
		$user = $_SESSION['kosh'];
		$date = time();
		$sql = "select * from zed_billing_connected_servises where purse='$user' and servis='$usl->id'";
		if($type!='do') $sql.=" and DATE>'$date'";
		//if( $type=='catalog' ) echo $sql." ";
		$res = $this->query($sql);
		//if( $type=='catalog' ) echo $this->num_rows($res).'<br />';
		if($this->num_rows($res)>0)	return true;
		return false;
	}
	function check_firm()
	{
		if(!$this->check_login()) return false;
		$user = $_SESSION['userid'];
		$this->seldb("tutinfo_utf");
		$res = $this->query("select ART from user_org where USER_ID='$user'");
		$this->retdb();
		if($this->num_rows($res)==0) return false;
		$rez= $this->fetch_object($res);
		return $rez->ART;
	}
	function check_firm_usl($art)
	{
		global $cur_dbname,$dbname;
		$ins_db = $cur_dbname;
		if($ins_db!="tutinfo_utf") $this->seldb("tutinfo_utf");
		$res = $this->query("select USER_ID from user_org where ART='$art'");
		$this->retdb();
		if($this->num_rows($res)==0)
		{
			$this->seldb($ins_db);
			return false;
		}
		$rez= $this->fetch_object($res);
		$data = time();
		$usl=$this->fetch_object($this->query("select id from zed_billing_servises where TYPE='catalog'"));
		$kosh = $this->fetch_object($this->query("select KOSHEL from zed_users where ID='$rez->USER_ID'"));
		$_SESSION['dd']=$kosh->KOSHEL;
		$res = $this->query("select id from zed_billing_connected_servises  where purse='$kosh->KOSHEL' and servis='$usl->id' and DATE>'$data'");
		$this->seldb($ins_db);
		if($this->num_rows($res)>0) return true;
		return false;
	}
	#проверка залогинен ли пользователь
	function check_login()
	{
		if (isset($_SESSION['islogin'])&& $_SESSION['islogin'] )
		{
			$adb = $this->cur_dbname;
			$res=$this->query("select * from zed_users where ID=".$_SESSION['userid']);
			$row=$this->fetch_object($res);
			$this->seldb($adb);
			if($_SESSION['identy']==md5(@$row->LOGIN.$row->PASS.$row->ID.$row->RANK))
			return true;
		}
		return false;
	}
	#логинится	//Исправлено на md5
	function loginin($login,$pass)
	{
		//$abd = $this->cur_dbname;
		$row=$this->fetch_object($this->query("select * from zed_users where LOGIN='$login'"));
		if(md5($pass)==@$row->PASS && $row->ACTIVE)
		{
			$_SESSION['islogin']=1;
			$_SESSION['user']=$row->LOGIN;
			$_SESSION['userid']=$row->ID;
			$_SESSION['rank']=$row->RANK;
			$_SESSION['active']=$row->ACTIVE;
			$_SESSION['identy']=md5($row->LOGIN.$row->PASS.$row->ID.$row->RANK);
			$str = date("d.m.Y");
			$str.="|".$_SERVER['REMOTE_ADDR'];
			$this->query("update zed_users set LAST='$str' where ID='$row->ID'");
		//	$this->seldb($abd);
			return true;
		}
		//$this->seldb($abd);
		return false;
	}
	// разлогинизация
	function loginout()
	{
		unset($_SESSION['islogin']);
		unset($_SESSION['user']);
		unset($_SESSION['userid']);
		unset($_SESSION['rank']);
		unset($_SESSION['active']);
		unset($_SESSION['identy']);
	}
	# проверить ранк пользователя
	function getrank($rank)
	{
		switch($rank)
		{
			case "10": $res="Гость";					break;
			case "20": $res="Пользователь"; 			break;
			case "40": $res="Контент менеджер"; 		break;
			case "50": $res="Главный контент менеджер"; break;
			case "60": $res="Редактор сайта"; 			break;
			case "80": $res="Модератор"; 				break;
			case "90": $res="Администратор"; 			break;
			case "100":$res="Супервайзер"; 				break;
		}
		return $res;
	}
	#проверка статуса, залогинен или нет
	function login_status()
	{
		$res="<div>".$this->getrank($_SESSION['rank'])." <b>".$_SESSION['user']."</b> -
		<a href=?exit=1>Выход</a></div>";
		return $res;
	}
	# проверка, есть ли у пользователя доступ к этому уровню
	function checklevel($level)
	{
		if (!$this->check_login() || $_SESSION['rank']<$level)
		{
			return false;
			//die("Доступ закрыт, обратитесь к администратору");
		}
		return true;
	}
	function get_user($id)
	{
		$catid=$this->fetch_object($this->query("select ID from zed_category where TYPE='users'"));
		$row=$this->fetch_object($this->query("select LOGIN from zed_users where ID='$id'"));
		return "<a href=/?modul=users&action=open&id=$catid->ID&user=view&userid=$id><b>$row->LOGIN</b></a>";
	}
	######################################### SITE METOS #########################################
	// генерирует дополнительное меню
	function gen_menu()
	{
		$str="";
		$result=$this->query("select * from zed_site_menu order by ORD");
		while($row=$this->fetch_object($result))
		{
			$menu['item']="<a href=".$row->LINK." class=menu>$row->NAME</a>";
			$str.=$this->blockparse('menuitem',$menu,4);
		}
		$menu['menu']=$str;
		$str=$this->blockparse('menu',$menu,4);
		return $str;
	}
	// поиск текущего меню
	function find_site_menu()
	{
		$x = 0;
		if(isset($_GET['x'])) $x = $_GET['x'];
		$r=$this->query("select ID from zed_site_menu where WHER='$x' and PARENT='0'");
		while ($this->num_rows($r)==0)
		{
			if($x==0) break;
		    $row=$this->fetch_object($this->query("select ID,PARENT from zed_category where ID='$x'"));
		    $x = $row->PARENT;
			$r=$this->query("select ID from zed_site_menu where WHER='$x' and PARENT='0'");
		}
		return $x;
	}
	// поиск активного пункта меню
	function find_act_menu($x,$act)
	{
		if($act==0) return;
		$r=$this->query("select ID from zed_site_menu where WHER='$x' and WHO='$act'");
		if($this->num_rows($r)>0) $this->act_menu[] = $act;
		$r=$this->fetch_object($this->query("select ID,PARENT from zed_category where ID='$act'"));
		$this->find_act_menu($x,$r->PARENT);
	}
	//генерирует всплывающие меню
	function gen_site_menu()
	{
		$x = $this->find_site_menu();
		if(isset($_GET['x'])) $this->find_act_menu($x,$_GET['x']);
		$result=$this->query("select * from zed_site_menu where WHER='$x' and PARENT='0' order by ORD");
		$menu['menu']='';
		$i=0;
		while($r=$this->fetch_object($result))
		{
			if($r->EXT==1)
			{
				$rr = $this->fetch_object($this->query("select TYPE from zed_category where ID='$r->WHO'"));
				$func = "gen_site_menu_$rr->TYPE";
				if(!function_exists($func))
				{
					include("zed/modules/$rr->TYPE/{$rr->TYPE}_func.php");
				}
					$menu['menu'].= $func($r->WHO);
				continue;
			}
			
			if($r->WHO==0)
			{
				$dd['url'] = '/';
				$dd['name'] = "ГЛАВНАЯ";
				$row->NAVIGATE=1;
				$row->ID = 0;
			}
			elseif($r->EXT==2)
			{
				$dd['url'] = '/catalog/sale';
				$dd['name'] = "Распродажа";
				$row->NAVIGATE=1;
				$row->ID = 0;
			}
			else
			{
				$row = $this->fetch_object($this->query("select * from zed_category where ID='$r->WHO'"));
				if($row->TYPE!="redirect")	$dd['url'] = $this->get_url_from_id($row->ID);
				else 
				{	// redirect
					$red = $this->fetch_object($this->query("select * from zed_redirect where ID='$r->WHO'"));

					$dd['url'] = $red->FULL;
				}
				$dd['name'] = $row->NAME;
			} 
			$i++;
			if($i>1)$dd['id'] = 'id="divider-vertical"';// class="divider-vertical"
			else $dd['id'] = '';
			$dd['class'] = '';
			$dd['menu'] = '';
			$shabl = 'menuitem';
			if($row->NAVIGATE==0) $shabl = 'menuitem_no';
			if(in_array($row->ID,$this->act_menu))	$dd['class'] = 'class="act"';
			if($r->ID!=0) $up = $this->query("select ID from zed_site_menu where PARENT='$r->ID'");
			if($this->num_rows($up)>0){ $dd['class1'] = 'class="mult"'; $dd['menu'] = $this->gen_topmenu($r->ID);}
			/*
			if($this->ie6 && $dd['menu']!='')
			{
				$shabl = 'menuitem_ie6';
				$dd['activation'] = "onmouseover=\"show('menu$row->ID')\" onmouseout=\"Timer()\"";
			}
			*/
			$menu['menu'].=$this->blockparse($shabl,$dd,1)."\n";
		}
		return $this->blockparse("menu",$menu,1);

	}
	//генерирует дополнительное меню
	function gen_topmenu($id=0)
	{
		$res=$this->query("select * from zed_site_menu where PARENT='$id' order by ORD");
		if($this->num_rows($res)==0) return '';
		$menu['menu']='';
		$kol = $this->num_rows($res);
		$i=0;
		while($r=$this->fetch_object($res))
		{
			$i++;
			$row = $this->fetch_object($this->query("select * from zed_category where ID='$r->WHO'"));
			$dd['url'] = $this->get_url_from_id($row->ID);
			$dd['name'] = $row->NAME;
			$dd['class'] = 'class="sa"'; 
			$dd['class1'] = ''; 
			if($i==$kol) {$dd['class'] = 'class="sal"'; $dd['class1'] = 'class="mlast"'; }
			//$dd['menu'] = '';
			$menu['menu'].=$this->blockparse('menu_sub_item',$dd,1)."\n";
		}
		$shabl = "menu_sub";
		/*
		if($this->ie6)
		{
			$shabl='menu_ie6';
			$menu['act'] = " id=\"menu$id\"  onmouseover=\"resethide();\" onMouseOut=\"Timer();\"";
		}
		/**/
		return $this->blockparse($shabl,$menu,1);
	}
	#интеграция с баннер ротатором pbmadmin
	function insertbanner($zid)
	{
		$this->seldb("tut_brotator");
		include ("banner.php");
		$this->retdb();
		$zed_banner;
		return $zed_banner;
	}
	## парсер основной информации для сайта
	function get_site_info($modul, $type)
	{
		$result=$this->query("select VALUE from zed_siteinfo where MODUL='$modul' AND TYPE='$type' OR MODUL='ALL' AND TYPE='$type'");
		$row=$this->fetch_object($result);
		return $row->VALUE;
	}
	## генератор основного меню 2
	function gen_slmenu()
	{
		$result=$this->query("select * from zed_site_menu where TYPE='MAIN' order by ORD DESC");
		$menu['menu']="";
		while ($row=$this->fetch_object($result))
		{
			$data['item']="<a href=$row->LINK class=menu>$row->NAME</a>";
			$menu['menu'].=$this->blockparse('menuitem',$data,4);
		}
		return $this->blockparse('menu',$menu,4);
	}
	//навигация
	function sitenavi($id)
	{
		$str="";
		//if ($id==0)	return " | <a href='/'> главная </a> | ";
		if ($id==0)	return '';//"<a href='/'>главная / </a>";
		$row=$this->fetch_object($this->query("select ID,NAME,PARENT,VISIBLE,NAVIGATE from zed_category where ID=$id"));
		$url = $this->get_url_from_id($row->ID);
		$rr = $this->query("select ID from zed_site_menu where WHER='$id'");
		if($row->VISIBLE == 0)
		{ 
			//if($row->NAVIGATE==1)$str="<a href='$url'>$row->NAME</a> | ".$str;
			//else $str="<b>$row->NAME</b> | ".$str;
			if($row->NAVIGATE==1)$str="<a href='$url'>$row->NAME</a> ".$str;
			else $str="<b>$row->NAME</b>".$str;
		}
		$str=$this->sitenavi($row->PARENT).$str;
		return $str;
	}

	//генератор дополнительной контентной информации
	function gen_content($catid)
	{
		global $zsite;
		$row=$this->fetch_object($this->query("select TITLE,KEYWORDS,DESCRIPTION,PARENT from zed_category where ID='$catid'"));
		
		if(isset($_GET[2]) && $_GET[2]!='') $zsite['title'].='';
		elseif($row->KEYWORDS!='') $zsite['title']=$row->TITLE;
		elseif($row->KEYWORDS!='' && empty ($_GET[2])) $zsite['title']=$row->TITLE;
		if(isset($_GET[2]) && $_GET[2]!='') $zsite['keywords'].='';
		elseif($row->KEYWORDS!='')	$zsite['keywords']=$row->KEYWORDS;
		elseif($row->KEYWORDS!='' && empty ($_GET[2]))	$zsite['keywords']=$row->KEYWORDS;
		if(isset($_GET[2]) && $_GET[2]!='') $zsite['description'].='';
		elseif($row->DESCRIPTION!='')	$zsite['description']=$row->DESCRIPTION;
		elseif($row->DESCRIPTION!='' && empty ($_GET[2]))	$zsite['description']=$row->DESCRIPTION;
		
		$str = " :: $row->TITLE";
		
		while($row->PARENT!=0)
		{
			$row=$this->fetch_object($this->query("select TITLE,PARENT from zed_category where ID='$row->PARENT'"));
			$str = " - $row->TITLE$str";
		}
		$zsite['title'].=$str;
		$row=$this->fetch_object($this->query("select TPLMAIN from zed_tplmanager where CATEGORY='$catid'"));
		if($row && $row->TPLMAIN)
		{
			$row2=$this->fetch_object($this->query("select TPL from zed_tplblock where ID='$row->TPLMAIN'"));
			$this->main=$row2->TPL;
		}
		else $this->main="main";
	}
	# форма для комментов
	function gencomform()
	{
		global $QUERY_STRING;
		$query_mas=explode("&",$QUERY_STRING);
		$query = "";
		for($i=0;$i<count($query_mas);$i++)
		{
			if(substr($query_mas[$i],0,3)=="del") continue;
			if(substr($query_mas[$i],0,4)=="edit") continue;
			$query.=$query_mas[$i]."&";
		}
		$query = substr($query,0,strlen($query)-1);

		$str="<hr size=1><table width=100%>
		<form name=\"comment\" action=/?$query method=post><tr>";
		if($this->check_login())
		{
			$str.="<td><b>".$_SESSION['user']."</b></td><td>Добавить комментарий</td></tr>";
		}
		else
		{
			/*<tr><td colspan=2>Пароль если вы зарегистрированы</td></tr><tr><td colspan=2><input name=password type=password size=50 maxlength=255 /></td></tr>
			<tr><td colspan=2>Е-mail<font color=red size=-5> (будет открыт всем)</font></td></tr><tr><td colspan=2><input name=mail type=text size=50 maxlength=255 /></td></tr>*/
			$str.="<td colspan=2>Ваше имя (логин) <font color=red>*</font></td></tr>
			<tr><td colspan=2><input name=login type=text size=50 maxlength=255 /></td></tr>";
		}
		$str.="<tr><td colspan=2>Комментарий <font color=red>*</font></td></tr>
		<tr><td colspan=2><textarea name=addcomment cols=38 rows=5></textarea></td></tr>
		<tr><td>&nbsp;</td><td><input type=submit name=\"loginin\" value=\"Добавить\"></td></tr></form>
		</table>";
		return $str;
	}
	function add_comment($catid,$mesid,$comm,$modul)
	{
		$comm++;
		$dd=date("d-m-Y");
		if($this->check_login())
		{
			if( $_POST['addcomment']!="")	$result=$this->query("insert into zed_comments (CATID,MESID,USERID,COMMENT,DATE)  values ('$catid','$mesid','".$_SESSION['userid']."','".$_POST['addcomment']."','$dd')");
			else return 4;
		}
		elseif (isset($_POST['password']) && $_POST['password']!="")
		{
			$name = $_POST['login'];
			$password = $_POST['password'];
			if($this->loginin($name,$password))
			{
				if($_POST['addcomment']!="") $result=$this->query("insert into zed_comments (CATID,MESID,USERID,COMMENT,DATE)  values ('$catid','$mesid','".$_SESSION['userid']."','".$_POST['addcomment']."','$dd')");
				else return 4;
			}
			else return 2;
		}
		elseif ($_POST['login']=="" ) return 3;
		elseif ($_POST['addcomment']=="") return 4;
		else
		{
			$result=$this->query("insert into zed_comments (CATID,MESID,NAME,COMMENT,DATE)  values ('$catid','$mesid','".$_POST['login']."','".$_POST['addcomment']."','$dd')");
		}

		if ($result)
		{
			$this->query("update zed_$modul set COMMENTS='$comm' where ID=$mesid");
			return 0;
		}
		else return 1;
	}
	function showcomments($catid,$mesid)
	{
		global $QUERY_STRING;
		$query_mas=explode("&",$QUERY_STRING);
		$query = "";
		for($i=0;$i<count($query_mas);$i++)
		{
			if(substr($query_mas[$i],0,3)=="del") continue;
			if(substr($query_mas[$i],0,4)=="edit") continue;
			$query.=$query_mas[$i]."&";
		}
		$query = substr($query,0,strlen($query)-1);
		$uscat=$this->fetch_object($this->query("select ID from zed_category where TYPE='users'"));
		if(isset($_GET['del']) && $this->check_login() && $_SESSION['rank']>=60)
		{
			$rez3 = $this->fetch_object($this->query("select * from zed_comments where ID='".$_GET['del']."'"));
			$rez = $this->fetch_object($this->query("select TYPE from zed_category where ID='$rez3->CATID'"));
			$rez2 = $this->fetch_object($this->query("select COMMENTS from zed_$rez->TYPE where ID='$rez3->MESID'"));
			$rez2->COMMENTS = $rez2->COMMENTS-1;
			$this->query("delete from zed_comments where ID='".$_GET['del']."'");
			$this->query("update zed_$rez->TYPE set COMMENTS='$rez2->COMMENTS' where ID='$rez3->MESID'");
		}
		$str="<hr size=1><table width=100%>";
		$result=$this->query("select * from zed_comments where CATID='$catid' AND MESID='$mesid' order by ID DESC");
		while($row=$this->fetch_object($result))
		{
			$str_admin="";
			if(isset($_SESSION['rank']) && $_SESSION['rank']>=60)
			{
				//$edit = ereg_replace("index.php","zed/index.php",$PHP_SELF);
				$str_admin="<br><a href=/?$query&del=$row->ID title=\"Удалить\" onclick=\"return confirm('Удалить?')\"><img src=\"zed/Image/del.gif\" width=20 height=20 border=0></a>
				<a href=/zed/?modul=comments&type=all&oper=edit&id=$row->ID target=\"_blank\"><img src=\"zed/Image/hand.gif\" alt=\"редактировать\" width=20 height=20 border=0></a>"; 
			}
			if($row->USERID!=0)
			{
				$user=$this->fetch_object($this->query("select LOGIN,ID from zed_users where ID='$row->USERID'"));
				$str.="<tr><td width=100 valign=top class=bot>$row->DATE<br>
				<b><a href=/?modul=users&action=open&id=$uscat->ID&user=view&userid=$user->ID>
				$user->LOGIN</a></b></td><td class=bot valign=top>$row->COMMENT $str_admin</td></tr>";
			}
			else
			{
				$str.="<tr><td width=100 valign=top class=bot>$row->DATE<br>";
				if($row->MAIL!="")
				{
					$str.="<b><a href=\"mailto:$row->MAIL\">$row->NAME</a></b>";
				}
				else
				{
					$str.="<b>$row->NAME</b>";
				}
				$str.="</td><td class=bot valign=top>$row->COMMENT $str_admin</td></tr>";
			}
		}
		$str.="</table>";
		return $str;
	}
	function add_ban($IP=0)
	{
		$IP==0? $IP=$_SERVER['REMOTE_ADDR'] : $IP=$IP;
		// смотрим, был ли этот ИП за последние 30 секунд
		$result=$this->query("INSERT INTO zed_ban (IP, actiontime) VALUES ('$IP', NOW())");
		if(!$result) return false;
		else return true;
	}
	// проверка, делал ли что-нибудь человек (не обязательно зарегестрированный) 
	//в течении некоторого времени
	function is_ban($IP=0)
	{
		$IP==0? $IP=$_SERVER['REMOTE_ADDR'] : $IP=$IP;
		// смотрим, был ли этот ИП за последние 30 секунд
		$result=$this->query("SELECT IP FROM zed_ban WHERE IP='$IP' and actiontime>=DATE_SUB(NOW(),INTERVAL 30 SECOND)");
		if(!$result || $this->num_rows($result)==0) return false;
		return true;
	}
}
//end of class
?>