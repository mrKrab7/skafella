<?

function get_user_card($word,$selid)
{
	global $cms;
	$data['content'].="<div class='row'>";
	$res_users=$cms->query("select USERID from zed_usluga where USLUGAID='$selid' order by PRIORITET DESC");
	while ($row_users=$cms->fetch_object($res_users))
	{
		$rez_user=$cms->fetch_object($cms->query("select FIO,COORDS from zed_users where ID='$row_users->USERID'"));
		$user_card['id']=$row_users->USERID;
		$user_card['name']=$rez_user->FIO;
		$user_card['url']="/profile/$row_users->USERID";
		$user_card['src']="/zed/image/top.jpg";
		
		//static
		$user_card['geo']="Новосибирск,";
		$user_card['desc']="Я мастер кератирования и восстановления волос. Я помогу тебе сэкономить время на укладке, расскажу все секреты как отрастить быстро волосы, а также как за ними правильно ухаживать)";
		$user_card['images']="
				<img src='/zed/image/beta.jpg' class='img-fluid round w-25'/>
						<img src='/zed/image/kupon.jpg' class='img-fluid round w-25'/>
								<img src='/zed/image/skidka.jpg' class='img-fluid round w-25'/>";
		$user_card['listuslug']="Стрижка волос 100р";
		
		$data['content'].=$cms->blockparse("user_card",$user_card,1);
	}
	$data['content'].="<div>";
	return $cms->blockparse("middle",$data,1);
}

####################
if (isset($_GET['x']))
{
	print_r($_GET);
	if(isset($_GET['search'])) {$word=$_GET['search'];$selid=$_GET['selid'];}
	$zsite['adding']='<link href="/zed/sitetpl/default/searchMap.css" rel="stylesheet">';
	/*$zsite['counter']='<script defer type="text/javascript" src="/zed/lib/searchMap.js"></script>';*/
	
		$data['content']=" <div class='user-search py-4'>
      <form id='userSearchForm' class='d-flex align-items-center' action='$cms->url' name='search'>
        <input class='form-control' type='search' name='search' required value='$word' />
  		<input type='hidden' name='selid'  value='$selid' />
        <button class='btn btn-primary user-search__form-btn' type='submit'>
          Поиск
        </button>
      </form>

      <div id='userSearch' class='user mt-5'></div>
    </div>";
		$data['content'].=get_user_card($word,$selid);
		$zsite['middle']=$cms->blockparse("middle",$data,1);
}
?>