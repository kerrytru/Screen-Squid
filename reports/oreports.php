<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">



<?php

#Build date Tuesday 21st of July 2020 13:20:10 PM
#Build revision 1.8



if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;

include("../config.php");

//<!-- The themes file -->
echo '<link rel="stylesheet" type="text/css" href="../themes/'.$globaltheme.'/global.css"/>';


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



$squidhost=$cfgsquidhost[$srv];
$squidport=$cfgsquidport[$srv];
$cachemgr_passwd=$cfgcachemgr_passwd[$srv];

$address=$address[$srv];
$user=$user[$srv];
$pass=$pass[$srv];
$db=$db[$srv];


#в зависимости от типа БД, подключаем разные модули
if($dbtype==0)
{
include("../lib/dbDriver/mysqlmodule.php");
$ssq = new m_ScreenSquid($variableSet); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение
}

if($dbtype==1)
{
include("../lib/dbDriver/pgmodule.php");
$ssq = new p_ScreenSquid($variableSet); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение
}

 // Standard pChart inclusions
# include("../lib/pChart/pChart/pData.class");
# include("../lib/pChart/pChart/pChart.class");


include("../modules/Chart/module.php");

$grap = new Chart($variableSet); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение


$start=microtime(true);

$nowtimestamp=$start;
?>


<?php

//querys for reports



if (isset($_GET['id']))
$id=$_GET['id'];
else
$id=0;

if (isset($_GET['norefresh']))
$norefresh=$_GET['norefresh'];
else
$norefresh=0;


if($norefresh==0){
if($id==1||$id==2)
echo '<META HTTP-EQUIV="REFRESH" CONTENT="'.$refreshPeriod.'">'; ///обновление страницы в секундах
}

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
<script type="text/javascript" src="../javascript/sortable.js"></script>



<?php
// Javascripts END


///date

if(isset($_GET['date']))
$querydate=$_GET['date'];
else
$querydate=date("d-m-Y");

list($day,$month,$year) = explode('[/.-]', $querydate);

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



$friendsLogin="0";
$friendsIpaddress="0";

#create list of friends
if($enableNofriends==1) {
if($goodLogins!="")
  $friendsLogin=implode("','",explode(" ", $goodLogins));
if($goodIpaddress!="")
  $friendsIpaddress=implode("','",explode(" ", $goodIpaddress));
}


/*
$queryActiveUsers1="select substring_index(ipaddress,':',1) as ipaddr,
			sum((sizeinbytes/1024)/seconds) as s,
		username 
		   from scsq_sqper_activerequests 
		   where substring_index(ipaddress,':',1) not IN ('".$friendsIpaddress."')
		     AND trim(username) not IN ('".$friendsLogin."')
		   group by ipaddr,username;";
*/
//		   LEFT JOIN (SELECT 
//		  	      name,
//			      tableid 
//			   FROM scsq_alias 
//			   WHERE typeid=1) 
//			   AS aliastbl 
//		   ON nofriends.id=aliastbl.tableid 
//
if($dbtype==0) #mysql version
$queryActiveUsers="select 
	table1.ipaddr,
	sum(table1.sums/1024/table1.seconds) as s, 
	table1.username, table1.id,table1.aliasname 
from 
		(select 
			DISTINCT substring_index(ipaddress,':',1) as ipaddr,
			sizeinbytes as sums, 
			seconds, 
			username, 
			ipaddresstbl.id,
			aliastbl.name as aliasname 
	from scsq_sqper_activerequests
	LEFT OUTER JOIN (SELECT scsq_ipaddress.id,scsq_ipaddress.name,substring_index(scsq_sqper_activerequests.ipaddress,':',1) as ipadr FROM scsq_ipaddress,scsq_sqper_activerequests ) as ipaddresstbl ON substring_index(ipaddress,':',1)=ipaddresstbl.name 
	LEFT JOIN (SELECT scsq_alias.id,scsq_alias.name,scsq_alias.tableid FROM scsq_alias ) as aliastbl ON ipaddresstbl.id=aliastbl.tableid 
	) as table1

	where 
		table1.ipaddr not IN ('".$friendsIpaddress."') 
	AND trim(table1.username) not IN ('".$friendsLogin."') 
	
	group by table1.ipaddr,table1.username,table1.id,table1.aliasname ; 
	
";

if($dbtype==1)
$queryActiveUsers="
 select table1.ipaddr,sum(table1.sums/1024/table1.seconds) as s, table1.username, table1.id,table1.aliasname 
 from 
 (select DISTINCT split_part(ipaddress,':',1) as ipaddr,sizeinbytes as sums, seconds, username, ipaddresstbl.id,aliastbl.name as aliasname from scsq_sqper_activerequests 
 LEFT OUTER JOIN (SELECT scsq_ipaddress.id,scsq_ipaddress.name,split_part(scsq_sqper_activerequests.ipaddress,':',1) as ipadr FROM scsq_ipaddress,scsq_sqper_activerequests ) as ipaddresstbl ON split_part(ipaddress,':',1)=ipaddresstbl.name 
 LEFT JOIN (SELECT scsq_alias.id,scsq_alias.name,scsq_alias.tableid FROM scsq_alias ) as aliastbl ON ipaddresstbl.id=aliastbl.tableid ) as table1 
 where table1.ipaddr not IN ('".$friendsIpaddress."') AND trim(table1.username) not IN ('".$friendsLogin."') group by table1.ipaddr,table1.username,table1.id,table1.aliasname ; 

";

//echo $queryActiveUsers;

//querys for reports end


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

#если тренды не открывали более 5 минут, таблица очищается
$sqltext="select max(date) from scsq_sqper_trend10";
$result=$ssq->query($sqltext);
$linedate = $ssq->fetch_array($result);
$resdate=$nowtimestamp - $linedate[0];
$ssq->free_result($result);
if($resdate>=60)
{
$sqltext="truncate scsq_sqper_trend10";
$result=$ssq->query($sqltext);
$ssq->free_result($result);
}



/////////// ACTIVE USERS REPORT

if($id==1)
{
 
$sqltext="truncate scsq_sqper_activerequests;";
$result=$ssq->query($sqltext);
$ssq->free_result($result);

if($dbtype==0)
$sqltext="ALTER TABLE scsq_sqper_activerequests AUTO_INCREMENT = 1 ;";

if($dbtype==1)
$sqltext="ALTER SEQUENCE scsq_mod_categorylist_id_seq RESTART WITH 1;";


$result=$ssq->query($sqltext);
$ssq->free_result($result);

$cmd = "GET cache_object://".$squidhost."/active_requests HTTP/1.0\r\n";
//$cmd = "GET cache_object://".$squidhost."/active_requests";

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

$ptmp="";


 while(!feof($fp)) 
  { 
  $allsize=0;
$tmp=fgets($fp,2048);
$ptmp.=$tmp;
}

if(preg_match("/HTTP/1.0 200 OK/",$ptmp)){
echo "Error: No connection to Squid";
$errCheck=1;
}
preg_match_all("/username(.+)/",$ptmp,$match);
preg_match_all("/(peer|remote):(.+)/",$ptmp,$matchpeer);
preg_match_all("/uri(.+)/",$ptmp,$matchuri);
preg_match_all("/out\.size(.+)/",$ptmp,$matchsize);
preg_match_all("/\((.+)seconds/",$ptmp,$matchsec);



for ($i=0; $i< count($match[1]); $i++) {
if($matchsec[1][$i]==0)
$matchsec[1][$i]=1;
$ipaddress=trim($matchpeer[2][$i]);
$username=$match[1][$i];
$site=$matchuri[1][$i];
$site=preg_replace("/\'/i", "&quot;", $site);
$size=$matchsize[1][$i];
$seconds=round($matchsec[1][$i],0);

	$sqltext="INSERT INTO scsq_sqper_activerequests (date,ipaddress,username,site,sizeinbytes,seconds) VALUES";
	$sqltext=$sqltext."($nowtimestamp,'$ipaddress','$username','$site','$size','$seconds')";
	$result=$ssq->query($sqltext);


	$sqltext="";
	
}


  fclose($fp); 

if($errCheck==0)
{
$getInspLines=$_GET['insp'];
///add to inspect
if(isset($_GET['iadd']))
$getInspLines=$getInspLines.",".$_GET['iadd'];
///delete from inspect
if(isset($_GET['idel']))
$getInspLines=str_replace($_GET['idel'], "", $getInspLines);
//delete trash from inspect like double commas
$getInspLines=str_replace(",,", ",", $getInspLines);

if(isset($_GET['insp']))
$_GET['insp']=$getInspLines;
//show inspect table if we had something to show
if(($_GET['insp']!=",")&&(isset($_GET['insp']))&&(strlen($_GET['insp'])>0)){
$showInspectTable=1;
$inspLines=explode(",",$getInspLines);
}
else
$showInspectTable=0;


echo "<a href=?srv=".$srv."&id=".$id."&date=".$querydate."&dom=day&insp=".$getInspLines."&norefresh=0>".$_lang['stSTART']."</a>&nbsp;<a href=?srv=".$srv."&id=".$id."&date=".$querydate."&dom=day&insp=".$getInspLines."&norefresh=1>".$_lang['stSTOP']."</a>";

if($dbtype==0)#mysql version
$result=$ssq->query("select from_unixtime(date,'%d.%m.%Y %H:%i:%s') as d from scsq_sqper_activerequests");

if($dbtype==1)#postgre version
$result=$ssq->query("select to_char(to_timestamp(date),'DD.MM.YYYY HH24:MI:SS') as d from scsq_sqper_activerequests");


$lastUpdateDate = $ssq->fetch_array($result);
$ssq->free_result($result);

$result=$ssq->query($queryActiveUsers);
$numrow=1;
$totalspeed=0;
$insptotalspeed=0; //total speed for inspected table

while ($line = $ssq->fetch_array($result)) {
if($enableUseiconv==1)
$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));
if($line[0]=='')
$line[0]="::1";

@$rows[$numrow]=implode(";;",$line);
$numrow++;
}
$ssq->free_result($result);

echo "<p>".$_lang['stREFRESHED']." ".$lastUpdateDate[0]."</p><br />";

#inspect table

if($showInspectTable) {
echo "
<table id=report_table_id_10 class=datatable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stIPADDRESS']."
    </th>
    <th>
    ".$_lang['stLOGIN']."
    </th>
    <th>
    ".$_lang['stSPEED']."
    </th>
    <th>
    ".$_lang['stINSPECT']."
    </th>
    <th>
    ".$_lang['stALIAS']."
    </th>
</tr>
";

for($i=1;$i<$numrow;$i++) {

$line=explode(';;',$rows[$i]);

if(in_array($line[0],$inspLines))
{
echo "<tr>";
echo "<td>".$i."</td>";
echo "<td>".$line[0]."</td>";
echo "<td>".$line[2]."</td>"; //username
$line[1]=round($line[1],2);
echo "<td>".$line[1]."</td>";
echo "<td><a href=?srv=".$srv."&id=".$id."&date=".$querydate."&dom=day&insp=".$getInspLines."&idel=".$line[0].">".$_lang['stDELETE']."</a></td>";
echo "<td>".$line[4]."</td>"; //username
echo "</tr>";
$insptotalspeed+=$line[1];
}
    }
echo "<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>".$_lang['stTOTAL']."</td>
<td>".$insptotalspeed."</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
";

echo "</table>";
echo "<br />";
}
#inspect table end


echo "
<table id=report_table_id_10 class=datatable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stIPADDRESS']."
    </th>
    <th>
    ".$_lang['stLOGIN']."
    </th>
    <th>
    ".$_lang['stSPEED']."
    </th>
    <th>
    ".$_lang['stINSPECT']."
    </th>
    <th>
    ".$_lang['stALIAS']."
    </th>
</tr>
";

for($i=1;$i<$numrow;$i++) {

$line=explode(';;',$rows[$i]);
echo "<tr>";
echo "<td>".$i."</td>";
echo "<td>".$line[0]."</td>";
echo "<td>".$line[2]."</td>"; //username
$line[1]=round($line[1],2);
echo "<td>".$line[1]."</td>";
echo "<td><a href=?srv=".$srv."&id=".$id."&date=".$querydate."&dom=day&insp=".$getInspLines."&iadd=".$line[0].">".$_lang['stADD']."</a></td>";
echo "<td>".$line[4]."</td>"; //username
echo "</tr>";
$totalspeed+=$line[1];
    }
echo "<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>".$_lang['stTOTAL']."</td>
<td>".$totalspeed."</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
";

echo "</table>";




#trend totalspeed
   $sqltext="INSERT INTO scsq_sqper_trend10 (date,value,par) VALUES ($nowtimestamp,$totalspeed,1)";
$ssq->query($sqltext);


foreach (glob("../lib/pChart/pictures/*.png") as $filename) {
   unlink($filename);
}




   $sqltext="delete from scsq_sqper_trend10 where date<($nowtimestamp-400)";
$result=$ssq->query($sqltext);

   $sqltext="select value from (select value,date from scsq_sqper_trend10 where par=1 order by date desc limit 30) as tmp order by date asc";
$result=$ssq->query($sqltext);

$countValues=0;
while ($line = $ssq->fetch_array($result)) {
$arrValues[$countValues]=$line[0];
$countValues++;
}
$ssq->free_result($result);
//pChart Graph 

#соберем данные для графика
$userData['charttype']="line";
$userData['chartname']="trafficbyhours";
$userData['charttitle']="";
$userData['arrSerie1']=$arrValues;
$userData['arrSerie2']="";


//create chart
$pathtoimage = $grap->drawImage($userData);

//display
echo $pathtoimage;


///pChart Graph END
} ///if no error. errCheck=0

} // if(fp)

else
echo "Error: No connection to Squid. Not open listening port";

}

/////////// ACTIVE USERS REPORT END

/////////// GENERAL HEALTH REPORT

if($id==2)
{
 
$cmd = "GET cache_object://".$squidhost."/info HTTP/1.0\r\n";
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
///echo $tmp;

if(preg_match("/HTTP/1.0 200 OK/",$tmp)){
echo "Error: No connection to Squid";
$errCheck=1;
break;
}
preg_match("/UP Time:(.+) /",$tmp,$match);
if(($match[1] != "")&&($uptime=="")){
$uptime=$match[1];
}

preg_match("/CPU Usage:(.+)%/",$tmp,$match);
if(($match[1] != "")&&($cpuusage == "")){
$cpuusage=$match[1];
}

$count++;
  }
 
  fclose($fp); 

///basic authenticator 
$cmd = "GET cache_object://".$squidhost."/basicauthenticator HTTP/1.0\r\n";
if($cachemgr_passwd!="") 
$cmd.="Authorization: Basic ".base64_encode("cachemgr:$cachemgr_passwd")."\r\n";
$cmd.="\r\n";

//echo $cmd;

 $fp = fsockopen($squidhost,$squidport, $errno, $errstr, 10); 

 fwrite($fp, $cmd); 
$count=0;
#если будет ошибка при получении данных, установить в 1.
$errCheck=0;

 while(!feof($fp)) 
  { 
  $allsize=0;
$tmp=fgets($fp,2048);
///echo $tmp;

preg_match("/queue length:(.+)/",$tmp,$match);
if(($match[1] != "")&&($basicqlength=="")){
$basicqlength=$match[1];
}

preg_match("/avg service time:(.+)/",$tmp,$match);
if(($match[1] != "")&&($basicavgsvc == "")){
$basicavgsvc=$match[1];
}

$count++;
  }
 
  fclose($fp); 

///basic authenticator end

///digest authenticator 
$cmd = "GET cache_object://".$squidhost."/digestauthenticator HTTP/1.0\r\n";
if($cachemgr_passwd!="") 
$cmd.="Authorization: Basic ".base64_encode("cachemgr:$cachemgr_passwd")."\r\n";
$cmd.="\r\n";

//echo $cmd;

 $fp = fsockopen($squidhost,$squidport, $errno, $errstr, 10); 

 fwrite($fp, $cmd); 
$count=0;
#если будет ошибка при получении данных, установить в 1.
$errCheck=0;

 while(!feof($fp)) 
  { 
  $allsize=0;
$tmp=fgets($fp,2048);
///echo $tmp;

preg_match("/queue length:(.+)/",$tmp,$match);
if(($match[1] != "")&&($digestqlength=="")){
$digestqlength=$match[1];
}

preg_match("/avg service time:(.+)/",$tmp,$match);
if(($match[1] != "")&&($digestavgsvc == "")){
$digestavgsvc=$match[1];
}

$count++;
  }
 
  fclose($fp); 

///digest authenticator end

///negotiate authenticator 
$cmd = "GET cache_object://".$squidhost."/negotiateauthenticator HTTP/1.0\r\n";
if($cachemgr_passwd!="") 
$cmd.="Authorization: Basic ".base64_encode("cachemgr:$cachemgr_passwd")."\r\n";
$cmd.="\r\n";

//echo $cmd;

 $fp = fsockopen($squidhost,$squidport, $errno, $errstr, 10); 

 fwrite($fp, $cmd); 
$count=0;
#если будет ошибка при получении данных, установить в 1.
$errCheck=0;

 while(!feof($fp)) 
  { 
  $allsize=0;
$tmp=fgets($fp,2048);
///echo $tmp;

preg_match("/queue length:(.+)/",$tmp,$match);
if(($match[1] != "")&&($negqlength=="")){
$negqlength=$match[1];
}

preg_match("/avg service time:(.+)/",$tmp,$match);
if(($match[1] != "")&&($negavgsvc == "")){
$negavgsvc=$match[1];
}

$count++;
  }
 
  fclose($fp); 

///negotiate authenticator end



if($errCheck==0)
{

///$result=mysql_query("select from_unixtime(date,'%d.%m.%Y %H:%i:%s') as d from scsq_sqper_activerequests") or die (mysql_error());
///$lastUpdateDate = mysql_fetch_array($result,MYSQL_NUM);
echo "
<table id=report_table_id_10 class=datatable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th  class=unsortable>
    ".$_lang['stPARAMNAME']."
    </th>
    <th  class=unsortable>
    ".$_lang['stPARAMVALUE']."
    </th>

</tr>
";
$numrow=1;

$totalspeed=0;



$upt[0]=floor(($uptime/3600)/24); //days
$upt[1]=floor(($uptime-$upt[0]*86400)/3600); ///hours
$upt[2]=floor(($uptime - ($upt[0]*86400 + $upt[1]*3600 ))/60); //mins
$upt[3]=$upt[0]."_days_".$upt[1]."_hours_".$upt[2]."_min";

echo "	<tr>
		<td>1</td>
		<td>UP time</td>
		<td>".$upt[3]."</td>
	</tr>
	<tr>
		<td>2</td>
		<td>CPU Usage (%)</td> 
		<td>".($cpuusage)."</td>
	</tr>
	<tr>
		<td colspan=3>Basic Authenticator</td>
	</tr>
	<tr>
		<td>3</td>
		<td>Queue length:</td>
		<td>".$basicqlength."</td>
	</tr>
	<tr>
		<td>4</td>
		<td>avg service time:</td>
		<td>".$basicavgsvc."</td>
	</tr>
	<tr>
		<td colspan=3>Digest Authenticator</td>
	</tr>
	<tr>
		<td>3</td>
		<td>Queue length:</td>
		<td>".$digestqlength."</td>
	</tr>
	<tr>
		<td>4</td>
		<td>avg service time:</td>
		<td>".$digestavgsvc."</td>
	</tr>
	<tr>
		<td colspan=3>Negotiate Authenticator</td>
	</tr>
	<tr>
		<td>5</td>
		<td>Queue length:</td>
		<td>".$negqlength."</td>
	</tr>
	<tr>
		<td>6</td>
		<td>avg service time:</td>
		<td>".$negavgsvc."</td>
	</tr>
";

echo "</table>";
#trend cpuusage
$cpuusage=$cpuusage*100;
   $sqltext="INSERT INTO scsq_sqper_trend10 (date,value,par) VALUES ($nowtimestamp,$cpuusage,2)";
$sql->query($connection,$sqltext);


foreach (glob("../lib/pChart/pictures/*.png") as $filename) {
   unlink($filename);
}




   $sqltext="delete from scsq_sqper_trend10 where date<($nowtimestamp-400)";
$result=mysqli_query($connection,$sqltext);

   $sqltext="select value from (select value,date from scsq_sqper_trend10 where par=2 order by date desc limit 30) as tmp order by date asc";
$result=mysqli_query($connection,$sqltext,MYSQLI_USE_RESULT);

$countValues=0;
while ($line = mysqli_fetch_array($result,MYSQLI_NUM)) {
$arrValues[$countValues]=$line[0]/100;
$countValues++;
}
mysqli_free_result($result);

//pChart Graph 
 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint($arrValues,"Serie1");

$DataSet->AddAllSeries();
$DataSet->SetAbsciseLabelSerie();
 $DataSet->SetSerieName("CPU Usage","Serie1");

 // Initialise the graph
 $Test = new pChart(700,230);
 $Test->setFixedScale(0,100);
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

/////////// GENERAL HEALTH REPORT END



$end=microtime(true);

$runtime=$end - $start;

echo "<br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];



///mysql_disconnect();


?>

</body>
</html>
