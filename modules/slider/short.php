<?
$fl=1;$i=0;
$z=$cms->query("select * from zed_photo where CATEGORY='$identy' order by ORD");
while($r=$cms->fetch_object($z))
{
	$rimg=$cms->fetch_object($cms->query("select * from zed_image where CATEGORY='$r->ID' and `TABLE`='zed_photo' "));
	if($fl==1){$c_act="class='active'";$act="active";$fl=0;}
	else {$act=$c_act='';}
	$str_num.="<li data-target='#myCarousel' data-slide-to='$i' $c_act ></li>
	";
	$i++;
	if($rimg->NAME!=''){$str_img.="<div class='$act item'><a href='$r->NAME'><img class='img-responsive' src='{$rimg->PATH}l$rimg->NAME' alt='' /></a></div>
	";}
	else $str_img.="<div class='item'><img class='img-responsive' src='/zed/sitetpl/default/images/default.png' alt='' /></div>
			";
}
$data['content']= "<!-- Карусель <div class='row'>--> 
<div id='myCarousel' class='carousel slide' data-interval='3000' data-ride='carousel' data-pause='false'><!-- Индикаторы для карусели --> 
	<ol class='carousel-indicators'>
		$str_num	
	</ol> <!-- Слайды карусели --> <div class='carousel-inner'>
$str_img
</div> <!-- Навигация для карусели </div>--></div><br>";
//$data['classc']='class="slider"';
//$data['navi']="";

?>