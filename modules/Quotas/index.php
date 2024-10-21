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
*                         File Birth   > <!#FB> 2022/09/28 22:01:17.324 </#FB>                                         *
*                         File Mod     > <!#FT> 2024/10/21 22:55:39.203 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.4.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/





if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;

include("../../config.php");

#если нет авторизации, сразу выходим
if (!isAuth()) 
{
	header("Location: ".$globalSS['root_http']."/modules/PrivateAuth/login.php"); exit();
}

include("module.php");
include_once($globalSS['root_dir']."/lang/".$globalSS['language']);
	if (file_exists("langs/".$globalSS['language']))
		include(__DIR__."/langs/".$globalSS['language']);  #подтянем файл языка если это возможно
	else	
  include(__DIR__."/langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- special css for module -->
<link rel="stylesheet" type="text/css" href="css/example.css"/>

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="<?php echo $globalSS['root_http']; ?>/themes/<?php echo $globalSS['globaltheme']; ?>/global.css"/>

</head>
<body>

<script type="text/javascript" src="../../javascript/sortable.js"></script>
<script language=javascript>

function switchTables()
{
   if (document.getElementById("loginsTable").style.display == "table" ) {
          document.getElementById("loginsTable").style.display="none";

} else {
document.getElementById("loginsTable").style.display="table";
}
   if (document.getElementById("ipaddressTable").style.display == "table" ) {
          document.getElementById("ipaddressTable").style.display="none";

} else {
document.getElementById("ipaddressTable").style.display="table";
}

}

function UpdatePageModule(srv,id,status)
{
		parent.right.location.href='?srv='+srv+'&status='+status+'';

}

function launch_toast(msg_element) {
	var x = document.getElementById(msg_element)
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
}

</script>


<?php


$quotaex = new Quotas($globalSS);

///<!-- The toasts -->
if(isset($_GET['status']) )
{
if($_GET['status']=='1quotasadded')
echo '<div id="toast_success"><div id="desc">'.$_lang['stQUOTASADDED'].'</div></div>';
if($_GET['status']=='1quotasupdated')
echo '<div id="toast_success"><div id="desc">'.$_lang['stQUOTASUPDATED'].'</div></div>';
if($_GET['status']=='1quotasdeleted')
echo '<div id="toast_success"><div id="desc">'.$_lang['stQUOTASDELETED'].'</div></div>';


}
	

if(!isset($_GET['id']))
echo "<h2>".$_lang['stQUOTASMODULE']."</h2><br />";

$start=microtime(true);


   
     //action ID.
      if(isset($_GET['actid'])) $actid=$_GET['actid']; else $actid=0;

      //quota ID из таблицы scsq_mod_quotas для редактирования/удаления quota.
      if(isset($_REQUEST['quotaid'])) $quotaid=$_REQUEST['quotaid']; else $quotaid=0;
		  if(isset($_POST['aliasid'])) $aliasid=$_POST['aliasid']; else $aliasid='';
	    if(isset($_POST['quotaday'])) $quotaday=$_POST['quotaday']+0;  else $quotaday=0;
	    if(isset($_POST['quotamonth'])) $quotamonth=$_POST['quotamonth']+0;  else $quotamonth=0;
      if(isset($_POST['sumday'])) $sumday=$_POST['sumday'];
      if(isset($_POST['summonth'])) $summonth=$_POST['summonth'];

      if(isset($_POST['quota'])) $quota=$_POST['quota']; else $quota=0;
      if(isset($_POST['active'])) $active=1; else $active=0;


      #после приема данных, сформируем массив с которым и будем работать.
      $paramsQuota = array();



$querydate=date("d-m-Y");
$datestart=strtotime($querydate);

$paramsQuota['aliasid']=$aliasid;
$paramsQuota['quotaday']=$quotaday;
$paramsQuota['quotamonth']=$quotamonth;
$paramsQuota['active']=$active;
$paramsQuota['quotaid']=$quotaid;
$paramsQuota['quota']=$quota;
#$paramsQuota['sumday']=$sumday;
#$paramsQuota['summonth']=$summonth;
$paramsQuota['datestart']=$datestart;





            if(!isset($_GET['actid'])) {
              $quotaex->doPrintAll($globalSS);
            }

          if($actid==1) {
              $quotaex->doPrintFormAdd($globalSS);
            }

            if($actid==2) { ///добавление 


              $quotaex->doAdd($globalSS,$paramsQuota);

              echo "<script language='javascript'>UpdatePageModule(".$globalSS['connectionParams']['srv'].",0,'1quotasadded')</script>"; 
             }

            if($actid==3) { ///Редактирование 
              $quotaex->doEdit($globalSS,$paramsQuota);
            } // end actid=3

            if($actid==4) { //сохранение изменений UPDATE
              $quotaex->doSave($globalSS,$paramsQuota);

              echo "<script language='javascript'>UpdatePageModule(".$globalSS['connectionParams']['srv'].",0,'1quotasupdated')</script>"; 
            } //end actid=4

            if($actid==5) {//удаление DELETE
              $quotaex->doDelete($globalSS,$paramsQuota);

       
              echo "<script language='javascript'>UpdatePageModule(".$globalSS['connectionParams']['srv'].",0,'1quotasdeleted')</script>"; 

            } //end actid=5


$end=microtime(true);

$runtime=$end - $start;

echo "<br /><br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];

$newdate=strtotime(date("d-m-Y"))-86400;
$newdate=date("d-m-Y",$newdate);


//если пришел какой-то статус - отобразим это во всплывающем сообщении
if(isset($_GET['status']))
echo "<script language=javascript>launch_toast('toast_success')</script>";
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