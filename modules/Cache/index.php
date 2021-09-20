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
*                         File Birth   > <!#FB> 2021/09/11 17:07:22.149 </#FB>                                         *
*                         File Mod     > <!#FT> 2021/09/11 17:07:27.120 </#FT>                                         *
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

$language=$globalSS['language'];

include("module.php");
include_once($globalSS['root_dir']."/lang/$language");

#подключим главный файл который теперь отвечает за генерацию данных
include(''.$globalSS['root_dir'].'/lib/functions/function.misc.php');


$addr=$address[$srv];
$usr=$user[$srv];
$psw=$pass[$srv];
$dbase=$db[$srv];
$dbtype=$srvdbtype[$srv];

$variableSet = array();
$variableSet['srv']=$srv;
$variableSet['addr']=$addr;
$variableSet['usr']=$usr;
$variableSet['psw']=$psw;
$variableSet['dbase']=$dbase;
$variableSet['dbtype']=$dbtype;

$globalSS['connectionParams']=$variableSet;


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

<script type="text/javascript" src="../../javascript/sortable.js"></script>
<style>


.text {
  cursor: pointer;
  font-size: 2rem;
  margin-left: 10px;
  font-family: 'Righteous', cursive;
  color: #fff;
  font-weight: 300;
}

.toggle-button {
  position: relative;
  
  width: 50px;
  height: 25px;
  margin: 0;

  vertical-align: top;

  background: #ffffff;
  border: 1px solid #bbc1e1;
  border-radius: 30px;
  outline: none;
  cursor: pointer;

  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
    
  transition: all 0.3s cubic-bezier(0.2, 0.85, 0.32, 1.2);
}

.toggle-button::after {
  content: "";
  
  position: absolute;
  left: 3px;
  top: 1.5px;
  
  width: 19px;
  height: 19px;
  background-color: blue;
  border-radius: 50%;
  
  transform: translateX(0);
  
  transition: all 0.3s cubic-bezier(0.2, 0.85, 0.32, 1.2);
}

.toggle-button:checked::after {
  transform: translateX(calc(100% + 3px));
  background-color: #fff;  
}

.toggle-button:checked {
  background-color: blue;
}
</style>



<?php

if(isset($_GET['act_id']))
{
  
  if($_GET['act_id']==1) 
    {
     
      doSetParam($globalSS,'Cache','enabled',$_POST['param_enabled']);
    }

}


$start=microtime(true);

    
echo "<h2>".$_lang['stMODULEDESC']."</h2>";

$module_param=array();

$module_param['enabled'] = (doGetParam($globalSS,'Cache','enabled') == 'on' ? 'checked' : '');

echo "<h3>Configuration</h3>";


echo '<form method="post" name=module_form action="index.php?srv='.$globalSS['connectionParams']['srv'].'&act_id=1"> ';
echo '<table class=datatable> ';
echo '<tr><td>1</td><td>Enabled</td><td><input class=toggle-button type=checkbox '.$module_param['enabled'].' name=param_enabled ></td></tr>';
echo '<tr><td colspan=3><input type=submit value=Save></td></tr>';
echo '</table>';
echo '</form>';


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
    <input type="hidden" name=group_field_hidden value=0>
    <input type="hidden" name=groupname_field_hidden value=0>
    <input type="hidden" name=typeid_field_hidden value=0>
    </form>
</body>
</html>
