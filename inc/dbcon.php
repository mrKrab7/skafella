<?     	
		$cms->dbtype = "mysql";
        
// параметры подключения к sql серверу
	 $server   = "p269200.mysql.ihc.ru";   			// сервер
	$admin_login="p269200_shkaf";       			// пользователь
    $admin_password="tW7YJC2z32";        			// пароль
    $cms->dbname = "p269200_shkaf";		// база данных

     
        
        $cms->cur_dbname = $cms->dbname;
        $cms->db_count = "{$cms->dbname}_counter";
        $link=mysql_pconnect("$server", "$admin_login", "$admin_password");
        if (!$link) die ("Couldn't connect to MySQL ");
        mysql_select_db("$cms->dbname",$link) or die (mysql_error(). " : ".mysql_errno());
        mysql_query("SET CHARACTER SET UTF8"); 
?>
