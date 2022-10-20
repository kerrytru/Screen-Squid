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
*                         File Birth   > <!#FB> 2022/09/15 21:20:37.972 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/09/15 21:21:23.504 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/


include("../../config.php");

#если нет авторизации, сразу выходим
if ((!isset($_COOKIE['loggedAdm'])or($_COOKIE['loggedAdm']==0)) 
	and (file_exists("".$globalSS['root_dir']."/modules/PrivateAuth/pass")) 
	and (!file_exists("".$globalSS['root_dir']."/modules/PrivateAuth/hash"))
	)
{
	header("Location: ".$globalSS['root_http']."/modules/PrivateAuth/login.php"); exit();
}

$language=$globalSS['language'];

include("module.php");
include_once("../../lang/$language");

	if (file_exists("langs/".$language))
		include("langs/".$language);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 

include_once(''.$globalSS['root_dir'].'/lib/functions/function.misc.php');
include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
		




if(isset($_GET['srv']))  $srv=$_GET['srv']; else  $srv=0;
if(isset($_GET['csv']))  $globalSS['makecsv']=$_GET['csv']; else  $globalSS['makecsv']=0;
if(isset($_GET['id']))  $globalSS['dictid']=$_GET['id']; else  $globalSS['dictid']=0;






$header='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="'.$globalSS['root_http'].'/themes/'.$globalSS['globaltheme'].'/global.css"/>



</head>
<body>
';
if($globalSS['makecsv']==0)
echo $header;

$addr=$address[$srv];
$usr=$user[$srv];
$psw=$pass[$srv];
$dbase=$db[$srv];
$dbtype=$srvdbtype[$srv];

$variableSet = array();
$variableSet['addr']=$addr;
$variableSet['usr']=$usr;
$variableSet['psw']=$psw;
$variableSet['dbase']=$dbase;
$variableSet['dbtype']=$dbtype;
$variableSet['srv']=$srv;

$variableSet['language']=$language;

$globalSS['connectionParams']=$variableSet;



$start=microtime(true);

//visual part
if(isset($_GET['id'])){

	if($_GET['id']==1){

	if($globalSS['makecsv']==0) {
		echo "<h2>".$_lang['stLOGINS']."</h2><br />";
		echo "<a href=index.php?srv=".$srv."&id=".$globalSS['dictid']."&csv=1><img src='../../img/csvicon.png' width=32 height=32 alt=Image title='Generate CSV'></a>";

		echo "<br>";
	}

		if(isset($_GET['actid'])) //action ID.
	$actid=$_GET['actid'];
  else
	$actid=0;


		//логины
		if(!isset($_GET['actid'])) {
			doPrintAllLogins($globalSS);

		} // end if(!isset...


	}

	//ip адреса
	if($_GET['id']==2) {

		if($globalSS['makecsv']==0) {
			echo "<h2>".$_lang['stIPADDRESS']."</h2><br />";
			echo "<a href=index.php?srv=".$srv."&id=".$globalSS['dictid']."&csv=1><img src='../../img/csvicon.png' width=32 height=32 alt=Image title='Generate CSV'></a>";
	
			echo "<br>";
		}
	
			if(isset($_GET['actid'])) //action ID.
		$actid=$_GET['actid'];
	  else
		$actid=0;
	
	
			//логины
			if(!isset($_GET['actid'])) {
				doPrintAllIpaddress($globalSS);
	
			} // end if(!isset...
	}



} //if(isset($_GET['id'])){

		 
	//Открыть отчёт от текущей даты или от вчера по умолчанию
if($globalSS['DefaultRepDate'])
$newdate=strtotime(date("d-m-Y"))-86400;
else
$newdate=strtotime(date("d-m-Y"));

$newdate=date("d-m-Y",$newdate);


?>
<form name=fastdateswitch_form>
  <input type="hidden" name=date value="<?php echo $newdate; ?>">
  <input type="hidden" name=date2 value="">
  </form>

<?php

$end=microtime(true);

$runtime=$end - $start;

if($globalSS['makecsv']==0){
echo "<br /><br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";
echo "</body>";
echo "</html>";
}




?>

