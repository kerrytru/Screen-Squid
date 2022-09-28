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
*                         File Birth   > <!#FB> 2022/04/11 23:57:47.370 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/09/22 21:41:06.860 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/



if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;

include("../../config.php");

#если нет авторизации, сразу выходим
if ((!isset($_COOKIE['loggedAdm'])or($_COOKIE['loggedAdm']==0)) and (file_exists("".$globalSS['root_dir']."/modules/PrivateAuth/pass")) )
{
	header("Location: ".$globalSS['root_http']."/modules/PrivateAuth/login.php"); exit();
}

$language=$globalSS['language'];

include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

include("module.php");
include_once("../../lang/$language");
	
	if (file_exists("langs/".$language))
		include("langs/".$language);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="<?php echo $globalSS['root_http']; ?>/themes/<?php echo $globalSS['globaltheme']; ?>/global.css"/>

</head>
<body>



<?php


$modul_ex = new PrivateAuth($globalSS);
	

if(isset($_GET['actid'])){
  
  $actid = $_GET['actid'];
  
  #set password
  if($actid==1)
    $modul_ex->SetPassword($_POST['fld_password']);

  #clear password
  if($actid==2)
    $modul_ex->ClearPassword();


}


echo "<h2>Auth module</h2><br />";

$start=microtime(true);

?>
<form name=privateauth_set_form action="index.php?actid=1" method=post>
<p><?php echo "Type password"?><p>
<input type="text" name="fld_password">
<input type="submit" value="Set">
</form>
<form name=privateauth_clear_form action="index.php?actid=2" method=post>

<input type="submit" value="Clear password">
	
</form>

   
<?php


$end=microtime(true);

$runtime=$end - $start;

echo "<br /><br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];

$newdate=strtotime(date("d-m-Y"))-86400;
$newdate=date("d-m-Y",$newdate);



?>
<form name=fastdateswitch_form>
    <input type="hidden" name=date_field_hidden value="<?php echo $newdate; ?>">

    </form>
</body>
</html>
