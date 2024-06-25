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
*                         File Mod     > <!#FT> 2024/06/25 20:54:48.669 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.2.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/





include("../../config.php");

#если нет авторизации, сразу выходим
if (!isAuth()) 
{
	header("Location: ".$globalSS['root_http']."/modules/PrivateAuth/login.php"); exit();
}

$language=$globalSS['language'];

include("module.php");
include_once($globalSS['root_dir']."/lang/$language");


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


?>

</body>
</html>
