<?

function show_profile($id)
{
	global $cms,$zsite;
	$zsite['adding']='<link href="/zed/sitetpl/default/searchMap.css" rel="stylesheet">';
	
	if(is_numeric($id))
	{
		$data['content']="<div class='row'>";
		
		$rez_user=$cms->fetch_object($cms->query("select FIO,COORDS from zed_users where ID='$id'"));
		$user_card['id']=$id;
		$user_card['name']=$rez_user->FIO;
		$user_card['url']="/profile/$id";
		
		//static
		$user_card['src']="/zed/image/top.jpg";
		$user_card['geo']="Новосибирск,";
		$user_card['desc']="Я мастер кератирования и восстановления волос. Я помогу тебе сэкономить время на укладке, расскажу все секреты как отрастить быстро волосы, а также как за ними правильно ухаживать)";
		$user_card['images']="
				<img src='/zed/image/beta.jpg' class='img-fluid round w-25'/>
						<img src='/zed/image/kupon.jpg' class='img-fluid round w-25'/>
								<img src='/zed/image/skidka.jpg' class='img-fluid round w-25'/>";
		$user_card['listuslug']="Стрижка волос 100р";
			
		$data['content'].=$cms->blockparse("user_profile",$user_card,1);
		$data['content'].="<div>";
	}
	else 
	{
		$data['content']="что-то пошло не так";
	}
	return $cms->blockparse("middle",$data,1);
}



$zsite['middle'].=show_profile($_GET['2']);
?>