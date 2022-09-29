<?
	
	function get_user($id)
	{
	global $cms;	
	$row=$cms->fetch_object($cms->query("select LOGIN from zed_users where ID='$id'"));
	return $row->LOGIN;
	}	

function show_pmes($userid)
{
	global $cms,$ZED, $cstart, $cend, $cstart1, $cend1;
	$rank = $_SESSION['rank'];
	if(isset($_GET['x'])) $id = $_GET['x'];
	else $id = $_GET['id'];
	$sql="select * from zed_pmesg WHERE TOUSER='$userid' OR TOGROUP='$rank' order by ID DESC";

	$sql2="select * from zed_pmesg_from WHERE USERFROM='$userid' order by ID DESC";
	$result=$cms->query($sql);
	if (isset($_GET['prpag1'])) {$prpag1="&prpag1=".$_GET['prpag1'];} else {$prpag1="";}
	$pg=$cms->gen_sitepage($result,10,"/index.php?x=$id&user=message$prpag1");
	$result=$cms->query("$sql limit $cstart,$cend");
	$str="";
	$data['table']="<form action=/index.php?x=$id&user=delmes&in=1 method=post>";
	$data['table'].="<tr bgcolor=#999999><td colspan=5><font color=#ffffff><b>Входящие сообщения</b></font></td></tr>";
	$data['table'].="<tr bgcolor=#cccccc><td width=1%>Статус</td><td>Заголовок</td><td width=10%>От кого</td><td width=10%>Дата</td><td width=1%>Удалить</td></tr>";
	while($row=$cms->fetch_object($result))
		{	
			$from=get_user($row->USERFROM);
			
			if($row->READM) {$read="<img src=/zed/modules/users/oldmes.gif>";
			$tit=$row->TITLE;
			} else {$read="<img src=/zed/modules/users/getmes.gif>";
			$tit="<b>".$row->TITLE."</b>";
			}
			
			$data['table'].="<tr bgcolor=#f3f3f3><td>$read</td>
			<td><a href=/index.php?x=$id&user=readmin&mes=$row->ID>$tit</a></td>
			<td>$from</td><td>$row->DATE</td><td><input type=checkbox name=delmessage[] value=$row->ID></td></tr>";
		}
	$data['table'].="<tr bgcolor=#cccccc><td colspan=5>$pg</td></tr>";
	$data['table'].="<tr bgcolor=#cccccc><td colspan=5><input type=submit value=Удалить></td></tr></form>";
	$str.=$cms->blockparse($ZED['table'],$data);

	$result=$cms->query($sql2);
	if (isset($_GET['prpage'])) {$prpage="&prpage=".$_GET['prpage'];} else {$prpage="";}
	$pg=gen_sitepage($result,10,"/index.php?x=$id&user=message$prpage");
	$result=$cms->query("$sql2 limit $cstart1,$cend1");
	$data['table']="<form action=/index.php?x=$id&user=delmes&out=1 method=post>";
	$data['table'].="<tr bgcolor=#999999><td colspan=5><font color=#ffffff><b>Исходящие сообщения</b></font></td></tr>";
	$data['table'].="<tr bgcolor=#cccccc><td width=1%>Статус</td><td>Заголовок</td><td width=10%>Кому</td><td width=10%>Дата</td><td width=1%>Удалить</td></tr>";
	while($row=$cms->fetch_object($result))
		{
			$from=get_user($row->TOUSER);
				
			if($row->READM) {$read="<img src=/zed/modules/users/oldmes.gif>";
			$tit=$row->TITLE;
			} else {$read="<img src=/zed/modules/users/getmes.gif>";
			$tit="<b>".$row->TITLE."</b>";
			}
			$data['table'].="<tr bgcolor=#f3f3f3><td>$read</td>
			<td><a href=/index.php?x=$id&user=readmout&mes=$row->ID>$tit</a></td>
			<td>$from</td><td>$row->DATE</td><td><input type=checkbox name=delmessage[] value=$row->ID></td></tr>";
		}
	$data['table'].="<tr bgcolor=#cccccc><td colspan=5>$pg</td></tr>";
	$data['table'].="<tr bgcolor=#cccccc><td colspan=5><input type=submit value=Удалить></td></tr>";
	$str.=$cms->blockparse($ZED['table'],$data);

	return $str;
}	

function send_form2($to)
{
	global $cms;
	if(isset($_GET['x'])) $id = $_GET['x'];
	else $id = $_GET['id'];
	$us=$cms->getrank($to);
global $cms, $ZED;
$data['table']="<form action=/index.php?x=$id&user=sended1&usergroup=$to method=post>";
$data['table'].="<tr bgcolor=#cccccc><td><b>Отправить сообщение: группе $us"."ов</b></td></tr>";
$data['table'].="<tr bgcolor=#f3f3f3><td><input type=text size=66 name=title value=></td></tr>";
$data['table'].="<tr bgcolor=#fafafa><td><textarea name=full cols=50 rows=5></textarea></td></tr>";
$data['table'].="<tr bgcolor=#f3f3f3><td><input type=submit value=Отправить></td></tr></form>";
return $cms->blockparse($ZED['table'],$data);
}


function send_form($to)
{
global $cms, $ZED;
	if(isset($_GET['x'])) $id = $_GET['x'];
	else $id = $_GET['id'];
$us=get_user($to);
$data['table']="<form action=/index.php?x=$id&user=sended&userid=$to method=post>";
$data['table'].="<tr bgcolor=#cccccc><td><b>Отправить сообщение: $us</b></td></tr>";
$data['table'].="<tr bgcolor=#f3f3f3><td><input type=text size=66 name=title value=></td></tr>";
$data['table'].="<tr bgcolor=#fafafa><td><textarea name=full cols=50 rows=5></textarea></td></tr>";
$data['table'].="<tr bgcolor=#f3f3f3><td><input type=submit value=Отправить></td></tr></form>";
return $cms->blockparse($ZED['table'],$data);
}

function send()
{
global $cms;
if (strlen($_POST['title'])>1&&strlen($_POST['full'])>1) {
$result=$cms->query("insert into zed_pmesg (TITLE,FULL,DATE,TOUSER,USERFROM) 
values ('".$_POST['title']."','".$_POST['full']."','".$cms->get_date()."','".$_GET['userid']."','".$_SESSION['userid']."')");
if ($result){
$result=$cms->query("insert into zed_pmesg_from (TITLE,FULL,DATE,TOUSER,USERFROM) 
values ('".$_POST['title']."','".$_POST['full']."','".$cms->get_date()."','".$_GET['userid']."','".$_SESSION['userid']."')");
}
if ($result) return "Отправлено"; }
}

function sendg()
{
global $cms;
if (strlen($_POST['title'])>1&&strlen($_POST['full'])>1) {
	$rezz = $cms->query("select * from zed_users where RANK='".$_GET['usergroup']."'");
	while($row=$cms->fetch_object($rezz))
		{
			if($row->ID==$_SESSION['userid']) continue;
			$result=$cms->query("insert into zed_pmesg (TITLE,FULL,DATE,TOUSER,USERFROM) 
			values ('".$_POST['title']."','".$_POST['full']."','".$cms->get_date()."','".$row->ID."','".$_SESSION['userid']."')");
		}
if ($result)
		{
			$result=$cms->query("insert into zed_pmesg_from (TITLE,FULL,DATE,TOGROUP,USERFROM) 
			values ('".$_POST['title']."','".$_POST['full']."','".$cms->get_date()."','".$_GET['usergroup']."','".$_SESSION['userid']."')");
		}
	}
if ($result){ return "Отправлено"; } 
}

function del()
{
global $cms;
@$delmessage=$_POST['delmessage'];

if(@$_GET['in'])
{
if (isset($_GET['mes'])){	
$cms->query("delete from zed_pmesg where ID='".$_GET['mes']."'");}	
foreach($delmessage as $mes_id){	
$cms->query("delete from zed_pmesg where ID='$mes_id'"); }
}
if(@$_GET['out'])
{
if (isset($_GET['mes'])){	
$cms->query("delete from zed_pmesg_from where ID='".$_GET['mes']."'");}	
	
foreach($delmessage as $mes_id){	
$cms->query("delete from zed_pmesg_from where ID='$mes_id'"); }
}
}

function readmessage($id,$st)
{
	global $cms,$ZED;
	if(isset($_GET['x'])) $idd = $_GET['x'];
	else $idd = $_GET['id'];
	if($st=="in")
{
$row=$cms->fetch_object($cms->query("select * from zed_pmesg where ID='$id'"));
$cms->query("UPDATE zed_pmesg set READM='1' where ID='$id'");
$cms->query("UPDATE zed_pmesg_from set READM='1' where ID='$id'");

$user=get_user($row->USERFROM);
$data['table']="<tr bgcolor=#cccccc>
<td><b>Сообщение от <a href=/index.php?x=$idd&user=view&userid=$row->USERFROM>$user</a>
 | <a href=/index.php?x=$idd&user=send&userid=$row->USERFROM>ответить</a>
 | <a href=/index.php?x=$idd&user=delmes&mes=$id&in=1>Удалить</a></b></td></tr>";
$data['table'].="<tr bgcolor=#f3f3f3><td><b>$row->TITLE</b> | $row->DATE |</td></tr>";
$data['table'].="<tr bgcolor=#fafafa><td>$row->FULL</td></tr>";
return $cms->blockparse($ZED['table'],$data);
}
if($st=="out")
{
$row=$cms->fetch_object($cms->query("select * from zed_pmesg_from where ID='$id'"));
$user=get_user($row->TOUSER);
$data['table']="<tr bgcolor=#cccccc><td><b>Сообщение для <a href=\"/index.php?x=$idd&user=view&userid=$row->TOUSER\">$user</a> | <a href=/index.php?x=$idd&user=send&userid=$row->TOUSER>ответить</a>
 | <a href=/index.php?x=$idd&user=delmes&mes=$id&out=1>Удалить</a>
</b></td></tr>";
$data['table'].="<tr bgcolor=#f3f3f3><td><b>$row->TITLE</b> | $row->DATE |</td></tr>";
$data['table'].="<tr bgcolor=#fafafa><td>$row->FULL</td></tr>";
return $cms->blockparse($ZED['table'],$data);
}
}

	function gen_sitepage($sa_result,$items,$paramstring)
	{
	global $cstart1,$cend1, $ZED, $cms;
	if(isset($_GET['prpag1'])) 
	{
	$prpag1=$_GET['prpag1']; 
	} else $prpag1=0;
	$countpages=$cms->num_rows($sa_result);
   
    if (($prpag1==1)or(!$prpag1)) 
    {$pg=1;
    if ($countpages<$items){$cstart1=0; $cend1=$countpages;} else	
    {$cstart1=0; $cend1=$items;}
    }
    else
    {
    $pg=$prpag1;
    $cstart1=$prpag1*$items-$items;
    $cend1=$items; 
    
    } 
    if ($cstart1<0) {$cstart1=0;}
    
 $y=0; 
 $j=1;
 $cpg=": <a href=".$paramstring."&prpag1=".$j.">".$j."</a> :";
 
 
 for($i=1;$i<$countpages;$i++)
 {$y++;
 if ($y==$items){$y=0; $j++;  $cpg=$cpg." <a href=".$paramstring."&prpag1=".$j.">".$j."</a> : ";}
 }
 $data['pg']=$pg;
 $data['cpg']=$cpg;
 $pg=$cms->blockparse($ZED['pages'],$data);

return $pg;
}	
function SendMail($id, $login)
{
	global $cms,$zsite,$_POST,$ZED;
   	$res1=$cms->fetch_object($cms->query("select * from zed_users where LOGIN='$login'"));
	$str="";
	if($_SESSION['islogin']==0)
	{
		$data['table']="<tr bgcolor=#fafafa><td>Вы не вошли и не имеете прав на отправку сообщений.</td></tr>";
	}
	elseif(!isset($_POST['header']))
	{
		$str="<form action=/index.php?x=$id&user=sendmail&login=$login method=post>
		<tr bgcolor=#fafafa><td>Отправка почтового сообщения пользователю : $login</td></tr>
		<tr bgcolor=#fafafa><td><input type=text name=header size=45 value=\"Заголовок сообщения\"></td></tr>
		<tr bgcolor=#fafafa><td>Сообщение</td></tr>
		<tr bgcolor=#fafafa><td><textarea name=desc rows=7 cols=60></textarea></td></tr>
		<tr bgcolor=#fafafa><td><input type=submit name=mail value=Отправить></td></tr>
		<tr bgcolor=#fafafa><td><a href=\"/index.php?x=$id&user=view&userid=$res1->ID\">вернуться</a></td></tr></form>";
	    $data['table'] = $str;
    }
    else
    {
    	$userid = $_SESSION['userid'];
    	$res2=$cms->fetch_object($cms->query("select EMAIL from zed_users where ID='$userid'"));
    	if($res1->EMAIL==$res2->EMAIL)$data['table'] = "Отправлять самому себе ?? зачем ??<br /><a href=\"/index.php?x=$id&user=view&userid=$res1->ID\">вернуться</a>";
    	else
    	{
    		$to = $res1->EMAIL;
    		$sub = $_POST['header'];
    		$message =$_POST['desc'];
    		$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=win-1251\r\n";
			$headers .= "From: $res2->EMAIL\r\n";
			mail($to, $sub, $message, $headers);
			$data['table'] = "Сообщение отправлено<br /><a href=\"/index.php?x=$id&user=view&userid=$res1->ID\">вернуться</a>";
    	}
    }
$zsite['middle']=$cms->blockparse($ZED['table'],$data);
return $cms->sitenavi($id)."Отправка почты";
}
?>