<?

function Users_login($id)
{
	global $cms;
	$url = $cms->get_url_from_id($id);
	$rurl = str_replace("/exit",'',$_SERVER['REQUEST_URI']);
	if($rurl=='')$rurl="/";
	$str= "<a href='$url'>ВХОД</a> / <a href='$url/register'>РЕГИСТРАЦИЯ</a>";
	/*$str= "<form action='$rurl' method='post'>
<p>логин</p><b><input type='text' name='login' /></b>
<p>пароль</p><b><input type='password' name='password' /></b>
<a href='$url/register'>регистрация</a>
<span><input type='submit' value='вход' name='loginin' title='вход' /></span>
<i>Зарегистрированным пользователям скидка 3%</i><div><a href='$url/forget'>Забыли пароль?</a></div>
</form>
";/**/
return $str;
}

function User_loginin($id,$login,$rank,$userid)
{
	global $cms;
	//if($_SESSION['koshel']>10000 && $_SESSION['skidka']==3){$cms->query("UPDATE zed_users SET SKIDKA='5' where ID='$userid'");$s=5;}else{$s=$_SESSION['skidka'];}
	/**/$s=$_SESSION['skidka'];
 
  if($_SERVER['REQUEST_URI']=='/') $url = '';
  else $url = $_SERVER['REQUEST_URI'];
  //@$str.=" <b><a href='$url/exit' class='exit'>выход</a></b> <br /><p>Добрый день, <a href='/users/profiles'>{$_SESSION['user']}</a>";
  $str.="<a href='/users/profiles'>ПРОФИЛЬ</a> / ";
 // $str.=" <br><a href='/users/profiles'>Редактирование информации о пользователе</a>";/**/
 // $str.="<a href='/users/profiles'>Профиль</a> ";
  if($_SERVER['REQUEST_URI']=='/') $url = '';
  else $url = $_SERVER['REQUEST_URI'];
  @$str.="<a href='$url/exit' class='exit'>ВЫХОД</a>";
/*
  $p = $cms->fetch_object($cms->query("select PARENT from zed_users where ID='{$_SESSION['userid']}'"));
  if($p->PARENT==1){$str.="<p><i>Необходимо сменить пароль, перейдите в Профиль</i></p>";}
  else 
  {
  	//@$str.="<p class='hi'><span>Добрый день, </span><a href='/users/profiles'>{$_SESSION['user']}</a>";
	  if($_SESSION['opt']==0)$str.="<p class='hi'><span>Вы </span><a href='/users/profiles'>{$_SESSION['user']}</a><span>&nbsp; оптовик</span>,<br>в каталоге указаны оптовые цены.</p>";
	  else $str.="<p class='hi'><span>Добрый день, </span><a href='/users/profiles'>{$_SESSION['user']}</a><br>Ваша скидка <b>$s</b>%</p>";  
	  if($rank>=40) $str.="<p><a href='/zed/'>Админка</a></p>";
  }  */
 
  return $str;
}

function SaveLogin2($id)
{
	global $REMOTE_ADDR,$cms;
	$str = date("d.m.Y");
	$str.="|".$REMOTE_ADDR;
	$cms->query("update zed_users set LAST='$str' where ID='$id'");
}

foreach ($_GET as $key){	if($key=="exit"){$cms->loginout();break;}}

if(isset($_POST['loginin']))
{
	if(isset($_POST['login']) && isset($_POST['password']))
	{
		$login = strip_tags($_POST['login']);
		$password = strip_tags($_POST['password']);
		
		$cms->loginin($_POST['login'], $_POST['password'] );
		if(!$cms->check_login())
		{
			//$data['navi']="<a href='$url/register' >Регистрация</a>";
			$data['navi']="";
			$data['classc']='class="user_shot"';
	 		$data['content']=Users_login($identy);	
			$data['content'].= "<p class='hi'><font color=#ff0000 >Неверное имя или пароль, или ваш аккаунт заблокирован.</font></p>";
		}
		else 
		{
			//$data['navi']="<a href='$url/profiles'>{$_SESSION['user']}</a>";
			$data['navi']="";
			SaveLogin2($_SESSION['userid']);
			$data['classc']='class="user_shot"';
			$data['content']=User_loginin($identy,$_SESSION['user'],$_SESSION['rank'],$_SESSION['userid']);
		}
	}
	else 
	{
	$data['classc']='class="user_shot"';
		if(!$cms->check_login()){ $data['content']=Users_login($identy);}
		else {$data['content']=User_loginin($identy,$_SESSION['user'],$_SESSION['rank']);$data['classc']='class="user_shot"';}
	}
}
else
{
	if(!$cms->check_login())
	{ 
		//$data['navi']="<a href='$url/register' >Регистрация</a>";
		$data['navi']="";
		$data['classc']='class="user_shot"';
		$data['content']=Users_login($identy);
	}
	else 
	{
		//$data['navi']="<a href='$url/profiles'>{$_SESSION['user']}</a>";
		$data['navi']="";
		$data['classc']='class="user_shot"';
		$data['content']=User_loginin($identy,$_SESSION['user'],$_SESSION['rank'],$_SESSION['userid']);
	}
}
//$data['class'] = 'class="user"';
?>