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
*                         File Mod     > <!#FT> 2024/06/25 20:56:59.847 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 2.0.0 </#FV>                                                           
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


include_once("../../config.php");

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

	$query = "SELECT id, login, pass FROM scsq_users WHERE login='".$_POST['userlogin']."' LIMIT 1";
	
	
	$row=doFetchOneQuery($globalSS, $query);
$pass=$row[2];

//if($pass == md5(md5($_POST['password'])) && $row[2] == 1 && $row[0]>0)


if($pass == md5(md5($_POST['password'])))

		    {
			$hash = md5(generateCode(10));	
			# set cookie

			$message['message']="PRIVATEAUTH INFO: Client IP = ".$_SERVER['REMOTE_ADDR']." succesfully auth LOGIN ".$_POST['userlogin'].".";
			doWriteToLogTable($globalSS,$message);

			$session_data = array();

			$session_data['hash']=$hash;
			$session_data['user_login']=$row[1];
			$session_data['client_address']=$_SERVER['REMOTE_ADDR'];
			$session_data['session_start']=microtime(true);

			$json_session_data=json_encode($session_data);
			#запишем хэш в файл.
			file_put_contents('hash/'.$hash,$json_session_data);

			$_SESSION['user_id']=$row[0];
			$_SESSION['user_login']=$row[1];
			$_SESSION['scsq_hash']=$hash;


		

			header("Location: ".$globalSS['root_http']."index.php"); exit();
				
			}

		    

		else

		    {
			//wrong password
			$message['message']="PRIVATEAUTH INFO: Client IP = ".$_SERVER['REMOTE_ADDR']." ERROR AUTH LOGIN ".$_POST['userlogin'].".";
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
			
					<p><input type="hidden" name="userlogin" id="email" value="admin" onblur="if(this.value=='')this.value='<?php echo $_lang['stUSERLOGIN'];?>'" onfocus="if(this.value=='<?php echo $_lang['stUSERLOGIN'];?>')this.value=''"></p>

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