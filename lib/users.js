function CheckLogin()
{
        var query = '' + document.getElementById('text_log').value;
        if(query =="")
        {
        document.getElementById('status').innerHTML ="";
        return;
        }
        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4)
            {
                if (req.responseJS)
                {
                    document.getElementById('status').innerHTML =req.responseJS.myq;
                }
            }
        }
        req.caching = true;
        req.open('POST', 'zed/modules/users/load.php', true);
        req.send({ q: query, test:303 });
 }