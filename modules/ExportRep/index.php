<?php

#Build date Sunday 19th of April 2020 22:37:30 PM
#Build revision 1.0

#добавим себе время для исполнения скрипта. в секундах
set_time_limit(600);

#чтобы убрать возможные ошибки с датой, установим на время исполнения скрипта ту зону, которую отдает система.
date_default_timezone_set(date_default_timezone_get());

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;



include("../../config.php");
include("module.php");
include_once("../../lang/$language");
include_once("langs/$language");

$header='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../../javascript/example.css"/>

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="../../themes/default/global.css"/>


</head>
<body>
';

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

$variableSet['language']=$language;



#в зависимости от типа БД, подключаем разные модули
if($dbtype==0)
include("../../lib/dbDriver/mysqlmodule.php");

if($dbtype==1)
include("../../lib/dbDriver/pgmodule.php");

// Include the main TCPDF library (search for installation path).
include("../../lib/tcpdf/tcpdf.php");


$ssq = new ScreenSquid($variableSet); #получим экземпляр класса и будем уже туда закиыдвать запросы на исполнение


///CALENDAR

?>

<script language=JavaScript>


function CreatePDF(actid)
{
    parent.right.location.href='index.php?srv=<?php echo $srv ?>&actid='+actid
+'&date='+window.document.checkdate_form.date_field.value
+'&date2='+window.document.checkdate_form.date2_field.value;
}


</script>



<script type="text/javascript">

//data for calendar
a_dayname=new Array(
'<?php echo $_lang['stMONDAY']; ?>',
'<?php echo $_lang['stTUESDAY']; ?>',
'<?php echo $_lang['stWEDNESDAY']; ?>',
'<?php echo $_lang['stTHURSDAY']; ?>',
'<?php echo $_lang['stFRIDAY']; ?>',
'<?php echo $_lang['stSATURDAY']; ?>',
'<?php echo $_lang['stSUNDAY']; ?>');

a_today = '<?php echo $_lang['stTODAY']; ?>'; 
//Month names
mn=new Array(
'<?php echo $_lang['stJANUARY']; ?>',
'<?php echo $_lang['stFEBRUARY']; ?>',
'<?php echo $_lang['stMARCH']; ?>',
'<?php echo $_lang['stAPRIL']; ?>',
'<?php echo $_lang['stMAY']; ?>',
'<?php echo $_lang['stJUNE']; ?>',
'<?php echo $_lang['stJULY']; ?>',
'<?php echo $_lang['stAUGUST']; ?>',
'<?php echo $_lang['stSEPTEMBER']; ?>',
'<?php echo $_lang['stOCTOBER']; ?>',
'<?php echo $_lang['stNOVEMBER']; ?>',
'<?php echo $_lang['stDECEMBER']; ?>');

</script>


<script src="../../javascript/calendar_ru.js" type="text/javascript"></script>



<?php

///CALENDAR END

if(isset($_GET['date']))
  $querydate=$_GET['date'];
else
  $querydate=date("d-m-Y");

if(isset($_GET['date2']))
  $querydate2=$_GET['date2'];
else
  $querydate2=date("d-m-Y");


$exportrep_ex = new ExportRep($variableSet);



if(!isset($_GET['id']))
echo "<h2>".$_lang['stEXPORTREPMODULE']."</h2><br />";

$start=microtime(true);



#create list of good sites
if($enableNoSites==1) {
  $sitesTmp=implode("','",explode(" ", $goodSites));
  $sitesTmp="'".$sitesTmp."'";
  $goodSitesList=$sitesTmp;

}
else {
  $goodSitesList="''";
}

#create list of friends
if($enableNofriends==1) {
  $friends=implode("','",explode(" ", $goodLogins));
  $friendsTmp="where name in  ('".$friends."')";
  $sqlGetFriendsId="select id from scsq_logins ".$friendsTmp."";
  $result=$ssq->query($sqlGetFriendsId);
  while ($fline = $ssq->fetch_array($result)) {
    $goodLoginsList=$goodLoginsList.",".$fline[0];
  }
 $ssq->free_result($result);
 $friends=""; 
 $friends=implode("','",explode(" ", $goodIpaddress));
 $friendsTmp="where name in ('".$friends."')";
  $sqlGetFriendsId="select id from scsq_ipaddress ".$friendsTmp."";
  $result=$ssq->query($sqlGetFriendsId);
  while ($fline = $ssq->fetch_array($result)) {
    $goodIpaddressList=$goodIpaddressList.",".$fline[0];
  }
 $ssq->free_result($result);
 
}
else {
  $goodLoginsList="0";
  $goodIpaddressList="0";
}


//visual part
?>

<form name=checkdate_form onsubmit="return false;">
<p><?php echo $_lang['stSETDATEPERIOD']?><p>
<input type="text" name=date_field onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)">
<br /><br />
<input type="text" name=date2_field onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)">&nbsp;
 <br /><br />
		<table class=datatable>
		<tr>
			<th>#</th>
			<th><?php echo $_lang['stEXPORTREPNAME']; ?></th>
			<th>PDF</th>
			<th>CSV</th>
		</tr>	
    	<tr>
			<td>1</td>
			<td><?php echo $_lang['stONELOGINTRAFFIC']; ?></td>
			<td><a href="Javascript:CreatePDF(1)">PDF</a></td>
			<td>CSV</td>
		</tr>	
    	<tr>
			<td>2</td>
			<td><?php echo $_lang['stONEIPADRESSTRAFFIC']; ?></td>
			<td><a href="Javascript:CreatePDF(2)">PDF</a></td>
			<td>CSV</td>
		</tr>	
	</table>
</form>

<?php



//compute

$repvars['querydate'] = $querydate;
$repvars['querydate2'] = $querydate2; 
$repvars['goodSitesList'] = $goodSitesList;
$repvars['oneMegabyte'] = $oneMegabyte;


if(isset($_GET['actid'])){
{
		if($_GET['actid']==1) {//сформировать отчёты по логинам

		  $numrow=0;
		  $sqlGetId="select id,name from scsq_logins where id not in (".$goodLoginsList.")";
		  $result=$ssq->query($sqlGetId);
		  
		  echo "proccess started<br />";
		  while ($line = $ssq->fetch_array($result)) {
			$repvars['currentloginid'] = $line[0];
			$repvars['currentlogin'] = $line[1];
			$exportrep_ex->CreateLoginsPDF($repvars);
			$numrow++;
		  }
		 } //actid=1
		  		  
		if($_GET['actid']==2) {//сформировать отчёты по логинам

		  $numrow=0;
		  $sqlGetId="select id,name from scsq_ipaddress where id not in (".$goodIpaddressList.")";
		  $result=$ssq->query($sqlGetId);
		  
		  echo "proccess started<br />";
		  while ($line = $ssq->fetch_array($result)) {
			$repvars['currentipaddressid'] = $line[0];
			$repvars['currentipaddress'] = $line[1];
			$exportrep_ex->CreateIpaddressPDF($repvars);
			$numrow++;
		 }
		} //actid=2
		
		
		
		 $ssq->free_result($result);
		
		echo $numrow." files created (check output direcory)";

	} //isset actid

}






 

         



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
