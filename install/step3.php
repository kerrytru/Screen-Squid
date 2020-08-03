<?php


#Build date Monday 3rd of August 2020 17:04:43 PM
#Build revision 1.0

$start=microtime(true);

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

					
				file_put_contents('../conf/db'.$start.'.php', '<?php return '. var_export($config, true) . ';?>');
			}
			#set default config as new config
		
			rename("../config.php.default","../config.php");
			

			
			if($_POST['createtables']){
			
					#try to create tables
							
					if($_POST['srvdbtype']==0)
					{
						$query=file_get_contents("../createdb/createdb.sql");
						$con=mysqli_connect($_POST['address'],$_POST['user'],$_POST['pass'],$_POST['db']);
					
						if (!$con) 	echo "Error: Cant connect to mysql server." . PHP_EOL;
						
						else
						
						if(!mysqli_multi_query($con,$query))
						
						echo "Error: Cant execute query." . PHP_EOL;
						
						
					
					}
					
		}
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
      Process complete. The installation process has completed, at your request database tables were created. Config file has been reset and all pre-installation tests have passed. Thank you, and here is your <a href="http://localhost/test7">Screen Squid</a>
    </div>

	</article>
</div>

<div class="clr"></div>	
<div class="line"></div>

</body></html>