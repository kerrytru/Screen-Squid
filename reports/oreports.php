<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php

#build 20151031

include("../config.php");

 // Standard pChart inclusions
 include("../lib/pChart/pChart/pData.class");
 include("../lib/pChart/pChart/pChart.class");


$start=microtime(true);

$nowtimestamp=$start;
?>


<?php

//querys for reports



if (isset($_GET['id']))
$id=$_GET['id'];
else
$id=0;


if($id==1)
echo '<META HTTP-EQUIV="REFRESH" CONTENT="'.$refreshPeriod.'">'; ///обновление страницы в секундах

echo "
</head>
<body>
";

$start=microtime(true);


// Javascripts
?>

<script language=JavaScript>
function FastDateSwitch(idReport, dom)
{
if(window.document.fastdateswitch_form.date_field.value=='')
parent.right.location.href='reports.php?id='+idReport+'&date='+window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+dom;
else
parent.right.location.href='reports.php?id='+idReport+'&date='+window.document.fastdateswitch_form.date_field.value+'&dom='+dom;
}


function UpdateLeftMenu(id)
{
parent.left.location.href='../left.php?id='+id;
}


</script>



<?php
// Javascripts END


///date

if(isset($_GET['date']))
$querydate=$_GET['date'];
else
$querydate=date("d-m-Y");

list($day,$month,$year) = split('[/.-]', $querydate);

if(isset($_GET['dom']))
$dayormonth=$_GET['dom'];
else
$dayormonth="day";


if($dayormonth=="day")
{
$querydom="%d-%m-%Y";
$datestart=strtotime($querydate);
$dateend=strtotime($querydate) + 86400;
}
else
{
$querydom="%m-%Y";
$querydate=$month."-".$year;
$numdaysinmonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$datestart=mktime(0,0,0,$month,1,$year);
$dateend=$datestart + 86400*$numdaysinmonth;
}

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;

$squidhost=$cfgsquidhost[$srv];
$squidport=$cfgsquidport[$srv];
$cachemgr_passwd=$cfgcachemgr_passwd[$srv];

$address=$address[$srv];
$user=$user[$srv];
$pass=$pass[$srv];
$db=$db[$srv];

//querys for reports


$queryActiveUsers="select substring_index(ipaddress,':',1) as ipaddr,sum((sizeinbytes/1024)/seconds) as s from scsq_sqper_activerequests group by ipaddr;";



//querys for reports end



mysql_connect("$address", "$user", "$pass") or die(mysql_error());
mysql_select_db($db);



///CALENDAR

?>

<form name=fastdateswitch_form onsubmit="return false;">
<input type="hidden" name=date_field_hidden value="<?php echo $_GET['date']; ?>">
<input type="hidden" name=dom_field_hidden value="<?php echo $dayormonth; ?>">
<input type="hidden" name=loginname_field_hidden value="<?php echo $currentlogin; ?>">
<input type="hidden" name=login_field_hidden value="<?php echo $currentloginid; ?>">
<input type="hidden" name=ipname_field_hidden value="<?php echo $currentipaddress; ?>">
<input type="hidden" name=ip_field_hidden value="<?php echo $currentipaddressid; ?>">
<input type="hidden" name=site_field_hidden value="<?php echo $currentsite; ?>">
<input type="hidden" name=group_field_hidden value="<?php echo $currentgroupid; ?>">
<input type="hidden" name=groupname_field_hidden value="<?php echo $currentgroup; ?>">
<input type="hidden" name=typeid_field_hidden value="<?php echo $typeid; ?>">
<input type="hidden" name=httpname_field_hidden value="<?php echo $currenthttpname; ?>">
<input type="hidden" name=httpstatus_field_hidden value="<?php echo $currenthttpstatusid; ?>">
<input type="hidden" name=loiid_field_hidden value="<?php echo $currentloiid; ?>">
<input type="hidden" name=loiname_field_hidden value="<?php echo $currentloiname; ?>">

</form>

<script src="../javascript/calendar_ru.js" type="text/javascript"></script>


<?php
 ///CALENDAR END



///REPORTS HEADERS


if($id==1)
echo "<h2>".$_lang['stACTIVEIPADDRESS']."</h2>";


///REPORTS HEADERS END


/////////// ACTIVE USERS REPORT

if($id==1)
{
 
$sqltext="truncate scsq_sqper_activerequests;";
$result=mysql_query($sqltext) or die (mysql_error());


$sqltext="ALTER TABLE scsq_sqper_activerequests AUTO_INCREMENT = 1 ;";
$result=mysql_query($sqltext) or die (mysql_error());

$cmd = "GET cache_object://".$squidhost."/active_requests HTTP/1.0\r\n";
if($cachemgr_passwd!="") 
$cmd.="Authorization: Basic ".base64_encode("cachemgr:$cachemgr_passwd")."\r\n";
$cmd.="\r\n";

//echo $cmd;

 $fp = fsockopen($squidhost,$squidport, $errno, $errstr, 10); 
if($fp) {
 fwrite($fp, $cmd); 
$count=0;
#если будет ошибка при получении данных, установить в 1.
$errCheck=0;

 while(!feof($fp)) 
  { 
  $allsize=0;
$tmp=fgets($fp,2048);


if(preg_match("/HTTP/1.0 200 OK/",$tmp)){
echo "Error: No connection to Squid";
$errCheck=1;
break;
}

preg_match("/(peer|remote):(.+)/",$tmp,$match);
if(($match[2] != "")&&($ipaddress=="")){
$ipaddress=$match[2];
$ipaddress=trim($ipaddress);
#preg_match("s/^\s+//",$ipaddress,$ipaddress);
}

preg_match("/uri(.+)/",$tmp,$match);
if(($match[1] != "")&&($site == "")){
$site=$match[1];
#preg_match("s/^\s+//",$site,$site);
}

preg_match("/out\.size(.+)/",$tmp,$match);
if(($match[1] != "")&&($size == "")){
$size=trim($match[1]);
}

preg_match("/\((.+)seconds/",$tmp,$match);
if(($match[1] != "")&&($seconds == "")){
$seconds=trim($match[1]);
}


if($seconds != "") {
$seconds=$seconds;

if($seconds == 0) {
#костыль чтобы избежать деления на ноль
$seconds=1;
}


#preg_match("/^(http:\/\/(www\.)?)?([\S]+)/i",$site,$match);


#@matches=($site=~ /^(http:\/\/(www\.)?)?([\S]+)/i);

	$sqltext="INSERT INTO scsq_sqper_activerequests (date,ipaddress,site,sizeinbytes,seconds) VALUES";
	$sqltext=$sqltext."($nowtimestamp,'$ipaddress','$site','$size','$seconds')";
	$result=mysql_query($sqltext) or die (mysql_error());
	$sqltext="";


#print $ipaddress." ".$site." ".$size." ".$seconds;
#printf (" %6.2f kb/sec\n",$speedres);
$ipaddress="";
$site="";
$size="";
$seconds="";

}
$count++;
  }
 
  fclose($fp); 

if($errCheck==0)
{

$result=mysql_query("select from_unixtime(date,'%d.%m.%Y %H:%i:%s') as d from scsq_sqper_activerequests") or die (mysql_error());
$lastUpdateDate = mysql_fetch_array($result,MYSQL_NUM);
echo "
<p>".$_lang['stREFRESHED']." ".$lastUpdateDate[0]."</p><br />
<table id=report_table_id_1 class=sortable border=1>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stIPADDRESS']."
    </th>
    <th>
    ".$_lang['stSPEED']."
    </th>

</tr>
";
$result=mysql_query($queryActiveUsers) or die (mysql_error());
$numrow=1;

$totalspeed=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
echo "<tr>";
echo "<td>".$numrow."</td>";
if($line[0]=='')
echo "<td>::1</td>";
else
echo "<td>".$line[0]."</td>";
$line[1]=round($line[1],2);
echo "<td>".$line[1]."</td>";
echo "</tr>";
$numrow++;
$totalspeed+=$line[1];
    }
echo "<tr>
<td>&nbsp;</td>
<td>".$_lang['stTOTAL']."</td>
<td>".$totalspeed."</td>
</tr>
";

echo "</table>";
#trend totalspeed
   $sqltext="INSERT INTO scsq_sqper_trend10 (date,value) VALUES ($nowtimestamp,$totalspeed)";
mysql_query($sqltext) or die (mysql_error());


foreach (glob("../lib/pChart/pictures/*.png") as $filename) {
   unlink($filename);
}

#если тренды не открывали более 5 минут, таблица очищается
$sqltext="select max(date) from scsq_sqper_trend10";
$result=mysql_query($sqltext) or die (mysql_error());
$linedate = mysql_fetch_array($result,MYSQL_NUM);
$resdate=$nowtimestamp - $linedate[0];

if($resdate>=60)
{
$sqltext="truncate scsq_sqper_trend10";
$result=mysql_query($sqltext) or die (mysql_error());
}



   $sqltext="delete from scsq_sqper_trend10 where date<($nowtimestamp-400)";
$result=mysql_query($sqltext) or die (mysql_error());

   $sqltext="select value from (select value,date from scsq_sqper_trend10 order by date desc limit 30) as tmp order by date asc";
$result=mysql_query($sqltext) or die (mysql_error());

$countValues=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
$arrValues[$countValues]=$line[0];
$countValues++;
}
//pChart Graph 
 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint($arrValues,"Serie1");

$DataSet->AddAllSeries();
$DataSet->SetAbsciseLabelSerie();
 $DataSet->SetSerieName("Traffic","Serie1");

 // Initialise the graph
 $Test = new pChart(700,230);
# $Test->setFixedScale(-2,8);
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",8);
 $Test->setGraphArea(50,30,585,200);
 $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
 $Test->drawGraphArea(255,255,255,TRUE);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);

 // Draw the 0 line
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",6);
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

 // Draw the cubic curve graph
 $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());

 // Finish the graph
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",8);
 $Test->drawLegend(600,30,$DataSet->GetDataDescription(),255,255,255);
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",10);
 $Test->Render("../lib/pChart/pictures/trend".$start.".png");

echo "<img src='../lib/pChart/pictures/trend".$start.".png' alt='Image'>";

///pChart Graph END
} ///if no error. errCheck=0

} // if(fp)

else
echo "Error: No connection to Squid. Not open listening port";

}

/////////// ACTIVE USERS REPORT END



$end=microtime(true);

$runtime=$end - $start;

echo "<br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];



///mysql_disconnect();


?>

</body>
</html>
