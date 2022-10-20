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
*                         File Birth   > <!#FB> 2021/07/22 18:55:43.630 </#FB>                                         *
*                         File Mod     > <!#FT> 2021/07/22 18:56:18.935 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/


#чтобы убрать возможные ошибки с датой, установим на время исполнения скрипта ту зону, которую отдает система.
date_default_timezone_set(date_default_timezone_get());

if(isset($_GET['srv']))
$srv=$_GET['srv'];
else
$srv=0;

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


include("./config.php");
include("module.php");
include("../../lang/$language");
if (file_exists("langs/".$language))
  include("langs/".$language);  #подтянем файл языка если это возможно
else	
  include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 

include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
include_once(''.$globalSS['root_dir'].'/lib/functions/function.misc.php');

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<?php echo '<link rel="stylesheet" type="text/css" href="'.$globalSS['root_http'].'/themes/'.$globalSS['globaltheme'].'/global.css"/>';
?>
</head>
<body>

<script type="text/javascript" src="../../javascript/sortable.js"></script>

<?php



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

$variableSet['language']=$language;

$globalSS['connectionParams']=$variableSet;

$authex = new BulkLoginAlias($globalSS);



if(!isset($_GET['actid']))
echo "<h2>".$_lang['stAUTHMODULE']."</h2><br />";

$start=microtime(true);


$userdata= array();

 
            if(isset($_GET['actid'])) //action ID.
              $actid=$_GET['actid'];
            else
              $actid=0;




///SQL querys





            if(!isset($_GET['actid'])) {


              
              echo "<br /><br />";
			  echo "<a href=index.php?srv=".$srv."&actid=10 target=right>Insert from CSV</a><br />";
			  echo "<br /><br />";

            }

  
 

            if($actid==5) {//удаление DELETE

			$userdata['user']=$_GET['user'];
			
			$authex->DeleteUser($userdata);	
       
              echo "".$_lang['stAUTHDELETED']."<br /><br />";
              echo "<a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a><br />";

            } //end actid=5

      
            if($actid==10) {//load from csv


			$openfile = fopen($importcsv_file,'r');
			
			$numrow = 0;
			
			$userdata=array();
			
			
			while($str = fgets($openfile))
			{
				
			$csvdata = explode($import_separator,$str);

			$userdata['user']=$csvdata[0];
			$userdata['password']=$csvdata[1];
			$userdata['aliasname']=$csvdata[2];
			$userdata['quotaday']=$csvdata[3];
			$userdata['quotamonth']=$csvdata[4];
      $userdata['quotaactive']=$csvdata[5];
      $userdata['useractive']=$csvdata[6];

			$authex->CreateUser($userdata);
			
			$numrow++;
   
            }
          
			echo	"Created ".$numrow." users";

            echo "<a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a><br />";

            } //end actid=10
         



$end=microtime(true);

$runtime=$end - $start;

echo "<br /><br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];

$newdate=strtotime(date("d-m-Y"))-86400;
$newdate=date("d-m-Y",$newdate);



?>
<form name=fastdateswitch_form>
    <input type="hidden" name=date_field_hidden value="<?php echo $newdate; ?>">
    <input type="hidden" name=dom_field_hidden value="<?php echo 'day'; ?>">
    <input type="hidden" name=group_field_hidden value="<?php echo $currentgroupid; ?>">
    <input type="hidden" name=groupname_field_hidden value="<?php echo $currentgroup; ?>">
    <input type="hidden" name=typeid_field_hidden value="<?php echo $typeid; ?>">
    </form>
</body>
</html>
