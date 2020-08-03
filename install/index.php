<?php


#Build date Monday 3rd of August 2020 17:04:03 PM
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
					Install System v1.0 Screen Squid
				</h1>
			

			</header>
		</div>
			<div class="clr"></div>

<div class="clr"></div>	
<div class="line"></div>


	<article>
		<div id="left_col">
		
		</div>
		
	
<form action="step1.php" method="post">
<table class="datatable" border="0">
	<thead class="tbcaption">
    
    <tr>
    <td style="font-size: 0.8em; text-align:center;">Choose the language you would prefer to use.<br/><br/></td>
    </tr>
    </thead>
	<tbody>
	<tr>
		<td align="center">
			<select name="default_lang">
				<option value="en">English</option>
				<option value="cn">Chinese</option>
				<option value="es">Spanish</option>
				<option value="pt-br">Portuguese-Brazil</option>
				<option value="ru">Russian</option>
				<option value="ua">Ukrainian</option>
			</select>
		</td>
	</tr>
	</tbody>
</table>

<div class="continue">
		<input type="submit" name="submit" value="Submit" />
</div>

</form>
		</div>

	</article>
</div>

<div class="clr"></div>	
<div class="line"></div>

</body></html>
