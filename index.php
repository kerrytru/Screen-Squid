<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> index.php </#FN>                                                       
*                         File Birth   > <!#FB> 2021/09/11 17:04:26.523 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/09/28 22:24:26.877 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/





   

?>

<!doctype html public "-//W3C//DTD HTML 3.2 Final//EN">
<?php 
	// Check to see if the configuration file exists, if not, explain


	if (file_exists('config.php')) {

		include("config.php"); 
	}
	else {
		echo '<script type="text/javascript">parent.location.href="install/index.php";</script>';

		#echo 'Configuration error: Copy config.php.default to config.php and edit appropriately.';
		exit;
	}

#если нет авторизации, сразу выходим
if ((!isset($_COOKIE['loggedAdm'])or($_COOKIE['loggedAdm']==0)) and (file_exists("".$globalSS['root_dir']."/modules/PrivateAuth/pass")) )
{
	header("Location: ".$globalSS['root_http']."/modules/PrivateAuth/login.php"); exit();
}


?>
<html>
<head>
<meta http-Equiv="Cache-Control" Content="no-cache">
<meta http-Equiv="Pragma" Content="no-cache">
<meta http-Equiv="Expires" Content="0">
<title>Screen Squid <?php echo $vers; ?></title>
</head>
<frameset cols="400,*" >
    <frame name="left" src="mainmenu.php" frameborder="0" scrolling="no" />
<frame name="right" src="right.php" frameborder="0" />
<noframes>
<body>
<p>This page uses frames, but your browser doesn't support them.</p>
</body>
</noframes>
</frameset>
</html>
