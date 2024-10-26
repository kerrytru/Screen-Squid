<?php


#Build date Thursday 6th of August 2020 12:58:11 PM
#Build revision 1.2

$start=microtime(true);


function pathUrl($dir = __DIR__){

    $root = "";
    $dir = str_replace('\\', '/', realpath($dir));

    //HTTPS or HTTP
    $root .= !empty($_SERVER['HTTPS']) ? 'https' : 'http';

    //HOST
    $root .= '://' . $_SERVER['HTTP_HOST'];

    //ALIAS
    if(!empty($_SERVER['CONTEXT_PREFIX'])) {
        $root .= $_SERVER['CONTEXT_PREFIX'];
        $root .= substr($dir, strlen($_SERVER[ 'CONTEXT_DOCUMENT_ROOT' ]));
    } else {
        $root .= substr($dir, strlen($_SERVER[ 'DOCUMENT_ROOT' ]));
    }

    $root .= '/';

    return $root;
}

$linkto=pathUrl();

$linkto=str_replace('install/','',$linkto);

		#write config from step2
			if(isset($_POST['submit'])){
				if($_POST['enabled']=='on')
				$config['enabled']=1;
				else
				$config['enabled']=0;
				
				$config['srvname']=$_POST['srvname'];
				$config['db']=$_POST['db'];
				$config['user']=$_POST['user'];
				$config['pass']=$_POST['pass'];
				$config['address']=$_POST['address'];
				$config['cfgsquidhost']=$_POST['cfgsquidhost'];
				$config['cfgsquidport']=$_POST['cfgsquidport'];
				$config['cfgcachemgr_passwd']=$_POST['cfgcachemgr_passwd'];
				$config['srvdbtype']=$_POST['srvdbtype'];

					
			}
			#set default config as new config
		
			copy("../config.php.default","../config.php");
			

			
			if($_POST['createtables']){
			
					#try to create tables
							
							#mysql
					if($_POST['srvdbtype']==0)
					{
					
						
						$query=file_get_contents("../createdb/createdb.sql");
					
					try {
						$con=mysqli_connect($_POST['address'],$_POST['user'],$_POST['pass'],$_POST['db']);
					}
					catch (Exception $e) {
						echo 'Error: php-mysqli not activated. Restart web-server and try again.';
						exit;
					}
						if (!$con) 	echo "Error: Cant connect to mysql server." . PHP_EOL;
						
						else
						
						if(!mysqli_multi_query($con,$query))
						
						echo "Error: Cant execute query." . PHP_EOL;
						
						else 				
						
						file_put_contents('../conf/db'.$start.'.php', '<?php return '. var_export($config, true) . ';?>');

						
					
					}
					
					#postgresql
					if($_POST['srvdbtype']==1)
					{
						$query=file_get_contents("../createdb/pgcreatedb.sql");
						
						
						$conn_string = "host=".$_POST['address']." port=5432 dbname=".$_POST['db']." user=".$_POST['user']." password=".$_POST['pass']."";
					
							try {
									$con = pg_connect($conn_string);
								}
								catch (Exception $e) {
									echo 'Error: php-pgsql not activated. Restart web-server and try again.';
									exit;
								}
								
						
						if (!$con) 	echo "Error: Cant connect to PostgreSQL server." . PHP_EOL;
						
						else
						
						if(!pg_query($con,$query))
						
						echo "Error: Cant execute query." . PHP_EOL;
						
						else

						file_put_contents('../conf/db'.$start.'.php', '<?php return '. var_export($config, true) . ';?>');

						
					
					}
					
		}

if ($_SERVER['SERVER_PORT'] == '80') {
	$referer = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
} 
else {
	$referer = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
}
preg_match('/(.*)\install/',$referer,$link);

?>

<html><head>

<meta http-equiv="Cache-Control" content="no-cache"> 	
<title><?php echo "Install System";?></title>

<link rel="stylesheet" type="text/css" href="css/install.css"/>

</head>

<body>
	<div id="page" align="center">
		<div id="page_in" align="left">

	
			<header align="center">
				<div id="logo"><img src="images/logo.png" style="width: 116px; height: auto;" align="left"></div>
				
				<h1>
					Install System 
				</h1>
			

			</header>
		</div>
			<div class="clr"></div>

<div class="clr"></div>	
<div class="line"></div>


	<article>
		<div id="left_col">
		
		</div>
		
<table cellspacing="2" cellpadding="2">
	<tr>
		<td><img src="images/1off.png" style="width: 50px; height: auto;" alt="Step 1" /></td>
		<td><img src="images/2off.png" style="width: 50px; height: auto;" alt="Step 2" /></td>
		<td><img src="images/3on.png" style="width: 50px; height: auto;" alt="Step 3" /></td>

	</tr>
</table>

<br/>
  <div >
	  
      Process complete. The installation process has completed, at your request database tables were created. Config file has been reset and all pre-installation tests have passed. Thank you, and here is your <a href="<?php echo $linkto;  ?>">Screen Squid</a>. If something went wrong, you can type in address string http://your_ip/path_where_screen_squid_installed.
    </div>

	</article>
</div>

<div class="clr"></div>	
<div class="line"></div>

</body></html>