<?php

#Build date Saturday 16th of May 2020 17:55:42 PM
#Build revision 1.1



?>

<!doctype html public "-//W3C//DTD HTML 3.2 Final//EN">
<?php 
	// Check to see if the configuration file exists, reports if not, explain
	if (file_exists('../../config.php')) {

		include("../../config.php"); 
	}
	else {
		echo 'Configuration error: Copy config.php.default to config.php and edit appropriately.';
		exit;
	}


#если нет авторизации, сразу выходим
if (!isset($_COOKIE['logged'])or($_COOKIE['logged']==0))
{
header("Location: login.php"); exit();
}
#echo "cookie = ".$_COOKIE['logged'];

?>
<html>
<head>
<meta http-Equiv="Cache-Control" Content="no-cache">
<meta http-Equiv="Pragma" Content="no-cache">
<meta http-Equiv="Expires" Content="0">
<title>Screen Squid <?php echo $vers; ?></title>
</head>
<frameset cols="400,*" >
    <frame name="left" src="left.php" frameborder="0" scrolling="no">
<frame name="right" src="reports/reports.php" frameborder="0">
<noframes>
<body>
<p>This page uses frames, but your browser doesn't support them.</p>
</body>
</noframes>
</frameset>
</html>
