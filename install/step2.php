<?php


#Build date Monday 3rd of August 2020 17:04:27 PM
#Build revision 1.0


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
		<td><img src="images/2on.png" style="width: 50px; height: auto;" alt="Step 2" /></td>
		<td><img src="images/3on.png" style="width: 50px; height: auto;" alt="Step 3" /></td>

	</tr>
</table>
<?php
			   echo '
                  <form action="step3.php" method="post">
                 		<table class=datatable>
                 		<tr>
						<td>Database Type:</td>
						<td>
							<select name="srvdbtype">
								<option value="0" />MySQL (MariaDB)</option>
								<option value="1" />PostgreSQL 9</option>
							</select>
						</td>
						</tr>
					   <tr><td>Proxy name:</td> <td><input type="text" name="srvname" value="Proxy0"></td></tr>
					   <tr><td>Database name:</td> <td><input type="text" name="db" value="screensquid"></td></tr>
					   <tr><td>Username:</td> <td><input type="text" name="user" value="mysql-user"></td></tr>
					   <tr><td>Password:</td> <td><input type="text" name="pass" value="pass"></td></tr>
					   <tr><td>Database host address:</td> <td><input type="text" name="address" value="localhost"></td></tr>
					   <tr><td>Squid host:</td> <td><input type="text" name="cfgsquidhost" value="localhost"></td></tr>
					   <tr><td>Squid port:</td> <td><input type="text" name="cfgsquidport" value="3128"></td></tr>
					   <tr><td>Cachemgr password:</td> <td><input type="text" name="cfgcachemgr_passwd"></td></tr>
					   	<tr class="row2">
							<td>Enabled</td>
							<td><input type="checkbox" name="enabled" checked="checked" />
							<br/>Normally this field should be checked at all times.  Use caution when disabling this feature</td>
						</tr>
				   		<tr >
							<td>Create Tables (Warning: Deletes existing data)</td>
							<td><input type="checkbox" name="createtables" checked="checked" />
							<br/>Normally this field should be checked at all times.  Use caution when disabling this feature</td>
						</tr>
						</table>
                 <br />
                 <input type="hidden" name="default_lang" value="'.$_POST['default_lang'].'" />
                  <input type="submit" name=submit value="Continue"><br />
                  </form>
                  ';



?>

		</div>

	</article>
</div>

<div class="clr"></div>	
<div class="line"></div>

</body></html>
