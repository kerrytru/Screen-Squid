<?php


#Build date Monday 3rd of August 2020 17:04:15 PM
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
		
<table class="countdown" cellspacing="2" cellpadding="2">
	<tr>
		<td><img src="images/1on.png" style="width: 50px; height: auto;" alt="Step 1" /></td>
		<td><img src="images/2on.png" style="width: 50px; height: auto;" alt="Step 2" /></td>
		<td><img src="images/3on.png" style="width: 50px; height: auto;" alt="Step 3" /></td>
	</tr>
</table>

<h3>Checking permissions and PHP settings</h3>



<table class="datatable" border="0">
		<tr>
			<td colspan="2">
				System Information
			</td>
		</tr>
		<tr >
			<td>
				Server Software
			</td>
			<td>
				<?php echo $_SERVER['SERVER_SOFTWARE']; ?>
			</td>
		</tr>
		<tr >
			<td>
				Server API
			</td>
			<td>
				<?php echo PHP_SAPI; ?>
			</td>
		</tr>
		<tr >
			<td>
				Server Operating System
			</td>
			<td>
				<?php echo PHP_OS .' '. php_uname('r') .' '. php_uname('m');?>
			</td>
		</tr>

</table>
<br/>


<table class="datatable" border="0">
		<tr>
			<td colspan="2">
				Required settings
			</td>
		</tr>

		<tr >
			<th>
				Test
			</th>
			<th>
				Result
			</th>
		</tr>
	
		<tr >
			<td>
				Checking for MySQL (MariaDB) database (if you want use MySQL (MariaDB) you should to install it) <br />
					You have 
					<?php $output = shell_exec('mysql -V'); 
						  preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version); 
						  if($version[0] !="") $passdbms=1;
							echo $output; 
					?>		
					</td>
			<td >
				<?php 
				if($passdbms==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/false.gif" alt="Failed" title="Failed" />';
						
				?>
				
			</td>
		</tr>
		
		<tr >
			<td>
	
					Check MysqlI support
					<br />You have 
					<?php $output = shell_exec('php -i |grep "MysqlI Support"'); 
						  preg_match('@enabled@', $output, $version); 
						  if($version[0] !="") {
							$passms=1;
							echo "Mysqli ".$version[0];
						}
						else
						{
								echo "no mysqli library. Please install php-mysql, php-mysqli";
						} 
					?>
					</td>
			<td >
			<?php 
				if($passms==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/false.gif" alt="Failed" title="Failed" />';
						
				?>
			</td>
		</tr>
		
				<tr >
			<td>
				Checking for PostgreSQL database (if you want use PostgreSQL you should to install it) <br />
					You have 
					<?php $output = shell_exec('psql -V'); 
						  preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version); 
						  if($version[0] !="") $passdbps=1;
							echo $output; 
					?>		
					</td>
			<td >
				<?php 
				if($passdbps==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/false.gif" alt="Failed" title="Failed" />';
						
				?>
				
			</td>
		</tr>
		
					<tr >
			<td>
	
					Check PostgreSQL support
					<br />You have 
					<?php $output = shell_exec('php -i |grep "PostgreSQL Support"'); 
						  preg_match('@enabled@', $output, $version); 
						  if($version[0] !="") {
							$passps=1;
							echo "PostgreSQL ".$version[0];
						}
						else
						{
								echo "no pgsql library. Please install php-pgsql";
						} 
					?>
					</td>
			<td >
			<?php 
				if($passps==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/false.gif" alt="Failed" title="Failed" />';
					
				?>
			</td>
		</tr>

		<tr >
			<td>
	
					Checking for JSON support
					<br />You have 
					<?php $output = shell_exec('php -i |grep "json support"'); 
						preg_match('@enabled@', $output, $version); 
						if($version[0] !="") {
						  $passjs=1;
						  echo "JSON ".$version[0];
					  }
					  else
					  {
							  echo "no json library. Please install php-json";
						} 
					?>
					</td>
			<td >
			<?php 
				if($passjs==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/false.gif" alt="Failed" title="Failed" />';
					
				?>
			</td>
		</tr>

		<tr >
			<td>
	
					Checking for GD library
					<br />You have 
					<?php $output = shell_exec('php -i |grep "GD"'); 
						  preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version); 
						  if($version[0] !="") {
							$passgd=1;
							echo "GD ".$version[0];
						}
						else
						{
								echo "no GD library. Please install php-gd";
						} 
					?>
					</td>
			<td >
			<?php 
				if($passgd==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/false.gif" alt="Failed" title="Failed" />';
						
				?>
			</td>
		</tr>
		
				<tr >
			<td>
	
					Check calendar support
					<br />You have 
					<?php $output = shell_exec('php -i |grep "Calendar support"'); 
						  preg_match('@enabled@', $output, $version); 
						  if($version[0] !="") {
							$passcl=1;
							echo "Calendar ".$version[0];
						}
						else
						{
								echo "no calendar library. Please install php-calendar";
						} 
					?>
					</td>
			<td >
			<?php 
				if($passcl==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/false.gif" alt="Failed" title="Failed" />';
				
			?>
			</td>
		</tr>
		
			
		
			
		
	
		
		<tr >
			<td>
	
					Check existing libphp (if apache)
					<br />You have 
					<?php 
					
					
					$output=shell_exec('ls /etc/httpd/modules/ | grep php'); 
					preg_match('@libphp[0-9]@', $output, $version); 
					
						  if($version[0] !="") {
							$passlb=1;
							echo $version[0];
						}
						else
						{
							echo "no libphp. Please install it, libphp5 for PHP5 or libphp7 for PHP7";
						} 
					
					?>
					
					</td>
			<td >
			<?php 
				if($passlb==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/yellow.gif" alt="Failed" title="Failed" />';
				
				?>
			</td>
		</tr>
				<tr >
			<td>
	
					Checking write permission on base directory
					<br />You have 
					<?php 
					$parentdir=dirname(dirname(__FILE__));
					
					shell_exec('touch '.$parentdir.'/test.png'); 
					
					
						  if(is_writeable($parentdir.'/test.png')) {
							$passwr2=1;
							echo $parentdir."/ is writetable";
						}
						else
						{
							echo $parentdir."/ is not writetable for web server";
						} 
					shell_exec('rm '.$parentdir.'/test.png'); 
					?>
					
					</td>
			<td >
			<?php 
				if($passwr2==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/false.gif" alt="Failed" title="Failed" />';
				
				?>
			</td>
		</tr>
		
				<tr >
			<td>
	
					Checking write permission on conf
					<br />You have 
					<?php 
					$parentdir=dirname(dirname(__FILE__));
					
					shell_exec('touch '.$parentdir.'/conf/test.png'); 
					
					
						  if(is_writeable($parentdir.'/conf/test.png')) {
							$passwr1=1;
							echo $parentdir."/conf/ is writetable";
						}
						else
						{
							echo $parentdir."/conf/ is not writetable for web server";
						} 
					shell_exec('rm '.$parentdir.'/conf/test.png'); 
					?>
					
					</td>
			<td >
			<?php 
				if($passwr1==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/false.gif" alt="Failed" title="Failed" />';
				
				?>
			</td>
		</tr>
			<tr >
			<td>
	
					Checking write permission on Chart/pictures
					<br />You have 
					<?php 
					$parentdir=dirname(dirname(__FILE__));
					
					$output=shell_exec('touch '.$parentdir.'/modules/Chart/pictures/test.png'); 
					
					
						  if(is_writeable($parentdir.'/modules/Chart/pictures/test.png')) {
							$passwr=1;
							echo $parentdir."/modules/Chart/pictures/ is writetable";
						}
						else
						{
							echo $parentdir."/modules/Chart/pictures/ is not writetable for web server";
						} 
					?>
					</td>
			<td >
			<?php 
				if($passwr==1)
					echo '<img src="images/true.gif" alt="Success" title="Success" />';
				else
					echo '<img src="images/false.gif" alt="Failed" title="Failed" />';
					
				?>
			</td>
		</tr>
		
	</tbody>
</table>

<br/>

<br />
<?php

#mysql
$passed1=$passdbms+$passms + $passcl + $passgd + $passlb + $passwr + $passwr1+$passwr2;
#postgresql
$passed2=$passdbps+$passps + $passcl + $passgd + $passlb + $passwr + $passwr1+$passwr2;

if($passed1>=7 or $passed2>=7) 

echo '
<form method="post" action="step2.php">
		<div class="continue">
		<span><b>All tests passed (at least at a minimum level). Please click the Continue button.</b></span>
	</div>
		<div class="continue">
		<input type="submit" value="Continue" />
		<input type="hidden" name="default_lang" value="'.$_POST['default_lang'].'" />
			</div>
</form>
';

else

echo '
<form method="post" action="step1.php">
		<div class="continue">
		<span><b>Not all tests are passed. Check it and try again. <br />Or if you understand that all is ok, click "I know what I`m doing" button and go to the next step.</b></span>
	</div>
		
		<input type="submit" value="Try again" />
		<input type="hidden" name="default_lang" value="'.$_POST['default_lang'].'" />
			
</form>
<br />
<form method="post" action="step2.php">
		
		<input type="submit" value="I know what I`m doing" />
		<input type="hidden" name="default_lang" value="'.$_POST['default_lang'].'" />
			
</form>
';
?>
		</div>

	</article>
</div>

<div class="clr"></div>	
<div class="line"></div>

</body></html>

