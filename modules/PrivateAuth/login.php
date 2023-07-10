<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> login.php </#FN>                                                       
*                         File Birth   > <!#FB> 2021/11/02 23:11:12.035 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/10/18 22:15:55.249 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/


 



function generateCode($length=6) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

    $code = "";

    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {

            $code .= $chars[mt_rand(0,$clen)];  
    }

    return $code;

}


include("../../config.php");

$start=microtime(true);

$message = array();

$message['datestart']=$start;
$message['dateend']=$start;

$language=$globalSS['language'];

include_once("../../lang/$language");
	
	if (file_exists("langs/".$language))
		include("langs/".$language);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 

//блок авторизации
if(isset($_POST['submit']))

{

$pass=file_get_contents("".$globalSS['root_dir']."/modules/PrivateAuth/pass");

//if($pass == md5(md5($_POST['password'])) && $row[2] == 1 && $row[0]>0)


if($pass == md5(md5($_POST['password'])))

		    {
			$hash = md5(generateCode(10));	
			# set cookie

			$message['message']="PRIVATEAUTH INFO: Client IP = ".$_SERVER['REMOTE_ADDR']." succesfully auth.";
			doWriteToLogTable($globalSS,$message);


			#запишем хэш в файл.
			file_put_contents('hash',$hash);

			setcookie("loggedAdm", 1, time()+3600, '/');
			

			header("Location: ".$globalSS['root_http']."/index.php"); exit();
				
			}

		    

		else

		    {
			//wrong password
			$message['message']="PRIVATEAUTH INFO: Client IP = ".$_SERVER['REMOTE_ADDR']." ERROR AUTH.";
			doWriteToLogTable($globalSS,$message);
			echo "<script> alert('".$_lang['stAUTHFAIL']."')</script>";
			

		    }

} //if(isset($_POST['submit']))

?>
<html><head>

<meta http-equiv="Cache-Control" content="no-cache"> 	
<title><?php echo $_lang['stLOGINTOADMIN'];?></title>

<link rel="stylesheet" type="text/css" href="css/cabinet.css"/>

</head>

<body>
	<div id="page" align="center">
		<div id="page_in" align="left">

	
			<header align="center">
				<div id="logo"><img src="img/logo.png" style="width: 116px; height: auto;" align="left"></div>
				
				<h1>
					<?php echo $_lang['stLOGINTOADMIN'];?>
				</h1>
			</header>
		</div>
			<div class="clr"></div>

<div class="clr"></div>	
<div class="line"></div>


	<article>
		<div id="left_col">
		
		</div>
		
			<div id="login" align="center">
			<form action="login.php" id="form_login" name="form_login" method="POST">
				<fieldset>
					<p><label for="password"><?php echo $_lang['stUSERPASSWORD'];?>:</label></p>
					<p><input type="password" id="password" name="password" value="<?php echo $_lang['stUSERPASSWORD'];?>" onblur="if(this.value=='')this.value='<?php echo $_lang['stUSERPASSWORD'];?>'" onfocus="if(this.value=='<?php echo $_lang['stUSERPASSWORD'];?>')this.value=''"></p> 
					<br>
					<p><input name="submit" type="submit" value="<?php echo $_lang['stENTER'];?>"></p>
				</fieldset>
			</form>

		</div>

	</article>
</div>

<div class="clr"></div>	
<div class="line"></div>

</body></html>