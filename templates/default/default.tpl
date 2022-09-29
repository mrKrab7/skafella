<?
### SITE TEMPLATES

//simple table
$ZED['table']=<<<ZED
<table width=90% cellspacing=0 cellpadding=0 border=0 align=center>
<tr>
<td bgcolor=#666666>
<table width=100% cellspacing=1 cellpadding=4 border=0>
{TABLE}
</table>
</td>
</tr>
</table><br>
ZED;

//simple table 2
$ZED['table2']=<<<ZED
<table width=100% cellspacing=0 cellpadding=0 border=0 align=center>
<tr>
<td bgcolor=#666666>
<table width=100% cellspacing=1 cellpadding=4 border=0>
{TABLE}
</table>
</td>
</tr>
</table>
ZED;

$ZED['fotoall']=<<<ZED
<tr bgcolor=#f3f3f3><td colspan=2><b>{NAME}</b></td></tr>
<tr bgcolor=#fafafa><td width=150>{FOTO}</td><td valign=top>{DESC}</td></tr>
ZED;

$ZED['foto']=<<<ZED
<tr bgcolor=#f3f3f3><td><b>{NAME}</b></td></tr>
<tr bgcolor=#fafafa><td align=center>{FOTO}</td></tr>
<tr bgcolor=#fafafa><td valign=top>{DESC}</td></tr>
ZED;

#message сообщение
$ZED['message']=<<<ZED
<div align=center><h5># {MESSAGE} #</h5></div>
ZED;
//news box fo dysplay on site
$ZED['newspost']=<<<ZED
<tr>
        <td bgcolor=#f3f3f3>
        <i>{DATE}</i> | <b>{NAME}</b>
        </td>
</tr>
<tr>
        <td bgcolor=#fafafa>
        {SMALL}
        </td>
</tr>
<tr>
        <td bgcolor=#fafafa>
        {IMAGE}
        </td>
</tr>
<tr>
        <td bgcolor=#fafafa>
        {FULL}
        </td>
</tr>
<tr>
        <td bgcolor=#f3f3f3>
        Автор: {AUTHOR} | {FUNC}
        </td>
</tr>
ZED;

### FACTORY TEMPLATES

$ZED['loginform']=<<<ZED
<form action="{ACTION}" name="login" {CLASS} method=post>
{LOGIN} <input type=text name=login><br>
{PASSWORD}<input type=password name=password><br>
<input type=submit name=loginin value='{LOGININ}'>
</form>
ZED;

$ZED['admin_main']=<<<ZED
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>{TITLE}</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<LINK href="/zed/templates/default/style.css" type=text/css rel=stylesheet>
<script language="JavaScript" src="/zed/lib/Subsys/JsHttpRequest/Js.js"></script>
<script language="JavaScript"> 
function show_raz()
{
	if(window.document.getElementById('razdels_main').style.display=='block'){
	window.document.getElementById('razdels_main').style.display="none";}
	else {
	window.document.getElementById('razdels_main').style.display="block";}
}
function Load(force,type_oper) 
{
	var query = force;
	var t_oper = type_oper;
	var req = new Subsys_JsHttpRequest_Js();
	req.onreadystatechange = function() 
	{
		if (req.readyState == 4) 
		{
			if (req.responseJS) 
			{
				document.getElementById('sel_cat').innerHTML = req.responseJS.myq;
			}
		}
	}
	req.caching = true;
	req.open('POST', '/zed/modules/catalog/load.php', true);
	req.send({ q: query, test:t_oper });
}
function Open_block(Block_ID)
{
var B_ID = 'id_'+Block_ID;
var IM_ID = 'img_'+Block_ID;
var SRC_IM = window.document.getElementById(IM_ID).src;
if(window.document.getElementById(B_ID).style.display=='none')
{
	window.document.getElementById(IM_ID).src = SRC_IM.replace("plus.gif","minus.gif");
	window.document.getElementById(B_ID).style.display="block";
}
else 
{
	window.document.getElementById(IM_ID).src = SRC_IM.replace('minus.gif','plus.gif');
	window.document.getElementById(B_ID).style.display="none";
}
}
</script>
</head>
<body style="margin:0px; ">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td background="zed/templates/default/images/topbg.gif" bgcolor="#FF9900"><img src="zed/templates/default/images/topbg.gif" width="1" height="42"></td>
    <td colspan="2" background="templates/default/images/topbg.gif" bgcolor="#FF9900" style="padding-left:60px;">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="30%" valign="middle">{INFO}</td>
    <td width="70%" align="right"><b>{TITLE}</b>
&nbsp;
</td>
  </tr>
</table>  </td>
  </tr></table>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="10" style=" border-right:1px solid #666666; border-bottom:1px solid #666666;">
&nbsp;</td>
    <td style=" border-bottom:1px solid #666666; border-right:1px dashed #666666;" width=200 valign=top><br>
<hr>
<div align=center><a href=index.php><b>Меню управления сайтом</b></a></div>
<hr>
{MENU}
<hr>
    </td>
        <td style="border-bottom:1px solid #666666;" valign=top><br>
        <div align=center><div style="border:1px solid #666666; width:90%">{NAVI}</div></div>
        <br>{CONTENT}<br></td>
        </tr></table>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td width="60" height="100%" bgcolor="#FF6600">&nbsp;</td>
    <td bgcolor="#FF6600">
<br>
<p>
<a href=http://zedcity.net class=copy>&copy; ZedCity WEB &nbsp; "ООО Инотех +" 2006</a>
</p>
<br>
</td>
  </tr>
</table>
</body>
</html>
ZED;

### ADMIN TEMPLATES

### modul templates,
#####modul users
########admin
$ZED['showusers']=<<<ZED
<tr bgcolor=#fafafa><td>{ID}</td><td>{LOGIN}</td><td>{FIO}</td><td>{RANK}</td><td>{ACTIVE}</td><td>{LAST}</td><td>{FUNC}</td></tr>
ZED;

$ZED['news_category']=<<<ZED
<tr bgcolor=#fafafa><td>{ID}</td><td>{NAME}</td><td width=90>{FUNC}</td></tr>
ZED;

$ZED['show_news_small']=<<<ZED
<tr bgcolor=#fafafa><td align=left>{TITLE}</td><td width=90>{FUNC}</td></tr>
ZED;
$ZED['pages']=<<<ZED
<table width=100% cellspacing=0 cellpadding=0 align=center>
	<tr>
	<td style="font-size:12px;">
		<b>{CPG}</b>
	</td>
	</tr>
</table>
ZED;
?>