<?php

#Build date Monday 3rd of August 2020 16:14:38 PM
#Build revision 1.0


    // Пример #1. Простое подключение
   

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
