<?


####################
if (isset($_GET['x']))
{
	print_r($_GET);
	if(isset($_GET['search'])) {$word=$_GET['search'];$selid=$_GET['selid'];}
	$zsite['adding']='<script src="https://enterprise.api-maps.yandex.ru/2.1/?apikey=bc39f70f-eb7e-44a6-b321-5b4f624d20b4&lang=ru_RU" type="text/javascript"></script>
<link href="/zed/sitetpl/default/searchMap.css" rel="stylesheet">';
	$zsite['counter']='<script defer type="text/javascript" src="/zed/lib/searchMap.js"></script>';
	
		$data['content']=" <div class='map-search py-4'>
      <form id='mapSearchForm' class='d-flex align-items-center' action='$cms->url' name='search'>
        <input class='form-control' type='search' name='search' required value='$word' />
  		<input type='hidden' name='selid'  value='$selid' />
        <button class='btn btn-primary map-search__form-btn' type='submit'>
          Поиск
        </button>
      </form>

      <div id='mapSearch' class='map mt-5'></div>
    </div>";
		$zsite['middle']=$cms->blockparse("middle",$data,1);
}
?>