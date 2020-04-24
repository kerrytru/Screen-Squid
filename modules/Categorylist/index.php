<?php

#Build date Friday 24th of April 2020 09:26:33 AM
#Build revision 1.1

#чтобы убрать возможные ошибки с датой, установим на время исполнения скрипта ту зону, которую отдает система.
date_default_timezone_set(date_default_timezone_get());


if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
* {padding:0;margin:0;}
ul {list-style-type:none;padding-left:1em}
body {margin:0.5em;padding:0.5em}

</style>
<link rel="stylesheet" type="text/css" href="../../javascript/example.css"/>
</head>
<body>
<br />


<br />
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

function PartlyReportsLogin(idReport, dom, login,loginname,site)
{
parent.right.location.href='reports/reports.php?srv=<?php echo $srv ?>&id='+idReport+'&date='+window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+dom+'&login='+login+'&loginname='+loginname+'&site='+site;
}

function PartlyReportsIpaddress(idReport, dom, ip,ipname,site)
{
parent.right.location.href='reports/reports.php?srv=<?php echo $srv ?>&id='+idReport+'&date='+window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+dom+'&ip='+ip+'&ipname='+ipname+'&site='+site;
}


</script>


<?php

include("../../config.php");
include("module.php");
include_once("../../lang/$language");
	if (file_exists("langs/".$language))
		include("langs/".$language);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 


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

#в зависимости от типа БД, подключаем разные модули
if($dbtype==0)
include("../../lib/dbDriver/mysqlmodule.php");

if($dbtype==1)
include("../../lib/dbDriver/pgmodule.php");

$start=microtime(true);
      
echo "<h2>".$_lang['stCATWAIT']."</h2>";
         



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
