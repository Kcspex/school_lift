<?php

    $domain = $_SERVER['HTTP_HOST'];
    $full_domain = preg_replace('/^www\./i', '', $domain); // Remove "www." if it exists
    $domain = preg_replace('/\.(com\.ng|com|ng|org\.ng|org)$/i', '', $full_domain); // Remove common extensions


    $server = 'localhost';
    if ($domain == 'kencrestacademy'){
        $username = 'trixschool';
	    $password = 'De1ve2lop3ment4@';
	    $database = 'trixschool_kencrestacademy';
    }
    
    if ($domain == 'boldstepschool'){
        $username = 'trixschool';
	    $password = 'De1ve2lop3ment4@';
	    $database = 'trixschool_boldstepschool';
    }
    
    if ($domain == 'joyfoundationacademy'){
        $username = 'trixschool_kencrestadmin';
	    $password = 'kencrest@1111';
	    $database = 'trixschool_joyfoundationacademy';
    }
    
    if ($domain == 'apexstaracademy'){
        $username = 'trixschool_apexstaradmin';
	    $password = 'apexstar@1111';
	    $database = 'trixschool_apexstaracademy';
    }
    
    if ($domain == 'apexstaracademyaso'){
        $username = 'trixschool_apexstarasoadmin';
	    $password = 'apexstaraso@1111';
	    $database = 'trixschool_apexstaracademyaso';
    }
    
    if ($domain == 'royalcreedacademy'){
        $username = 'trixschool_royalcreedadmin';
	    $password = 'royalcreed@1111';
	    $database = 'trixschool_royalcreedacademy';
    }
    
    if ($domain == 'topstepsacademy'){
        $username = 'trixschool_topstepsadmin';
	    $password = 'topsteps@1111';
	    $database = 'trixschool_topstepsacademy';
    }

	
	$link = mysqli_connect($server, $username, $password, $database);
	
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

    $connection = new PDO('mysql:host=localhost;dbname=' . $database, $username, $password);

	
	$sqlsch_settings = "SELECT * FROM `sch_settings`";
    $resultsch_settings = mysqli_query($link, $sqlsch_settings);
    $rowsch_settings = mysqli_fetch_assoc($resultsch_settings);
    $row_cntsch_settings = mysqli_num_rows($resultsch_settings);
    
    $defRUl = 'https://www.'. $full_domain . '/';
    $defRUlsec = 'www.'. $full_domain;
    
    ob_start();
    // server should keep session data for AT LEAST 1 hour
    ini_set('session.gc_maxlifetime', 3600);
    
    // each client should remember their session id for EXACTLY 1 hour
    session_set_cookie_params(3600);

    
    session_start();

    $now = time();
    
    if(!isset($_SESSION['username']) && empty($_SESSION['username']) && $now > $_SESSION['discard_after']) 
    {
        // this session has worn out its welcome; kill it and start a brand new one
        session_unset();
        session_destroy();
        session_start();
        
        $header = "Location: https://www.". $full_domain ."/site/login";
        header($header);
       
        // if(session_destroy())
        // {
        //     header("Location: https://www.kencrestacademy.com/site/login");
        // }
       
    }
    else
    {
        $Usename = $_SESSION['username'];
        
        // either new or old, it should live at most for another hour
        $_SESSION['discard_after'] = $now + 3600;
        
        $sqlstaff = "SELECT * FROM `staff` WHERE email = '$Usename'";
        $resultstaff = mysqli_query($link, $sqlstaff);
        $rowstaff = mysqli_fetch_assoc($resultstaff);
        $row_cntstaff = mysqli_num_rows($resultstaff);
        
        if($row_cntstaff > 0)
        {
            $id=$rowstaff['id'];
            $rolefirst='staff';
        }
        else
        {
            $sqlstudent = "SELECT * FROM `users` WHERE username = '$Usename' AND role = 'student'";
            $resultstudent = mysqli_query($link, $sqlstudent);
            $rowstudent = mysqli_fetch_assoc($resultstudent);
            $row_cntstudent = mysqli_num_rows($resultstudent);
            
            if($row_cntstudent > 0)
            {
                $id=$rowstudent['user_id'];
                $rolefirst='student';
            }
            else
            {
                $sqlparent = "SELECT * FROM `users` WHERE username = '$Usename' AND role = 'parent'";
                $resultparent = mysqli_query($link, $sqlparent);
                $rowparent = mysqli_fetch_assoc($resultparent);
                $row_cntparent = mysqli_num_rows($resultparent);
                
                if($row_cntparent > 0)
                {
                    $id=$rowparent['id'];
                    $rolefirst='parent';
                }
                else
                {
                    $id=0;
                    $rolefirst='nil';
                }
            }
        }
    }
    
?>
