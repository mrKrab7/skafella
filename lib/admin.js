function show_raz()
{
    var disp = document.getElementById('razdels_main');
	if(disp.style.display=='block') disp.style.display="none";
	else disp.style.display="block";
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

