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
*                         File Birth   > <!#FB> 2022/09/28 22:01:45.780 </#FB>                                         *
*                         File Mod     > <!#FT> 2024/10/19 22:59:26.045 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 2.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






include("../../config.php");

#если есть управляющий сигнал сверху
if (isset($_GET['external']) and $_SERVER['REMOTE_ADDR']=='127.0.0.1') 
{
	#проходим дальше
}
else {

#если нет авторизации, сразу выходим
if (!isAuth()) 
{
	header("Location: ".$globalSS['root_http']."/modules/PrivateAuth/login.php"); exit();
}
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
		
$timelimit = 600; 
#добавим себе время для исполнения скрипта. в секундах
set_time_limit($timelimit);

  #если есть дружеские логины, IP адреса или сайты. Соберём их.
  $goodLoginsList=doCreateFriendList($globalSS,'logins');
  $goodIpaddressList=doCreateFriendList($globalSS,'ipaddress');
  $goodSitesList = doCreateSitesList($globalSS);

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;


$header='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="'.$globalSS['root_http'].'/themes/'.$globalSS['globaltheme'].'/global.css"/>



</head>
<body>
';

echo $header;


// Include the main TCPDF library (search for installation path).
include("../../lib/tcpdf/tcpdf.php");

///CALENDAR

?>

<script language=JavaScript>


function CreateDoc(actid)
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
  $querydate2="";


$exportrep_ex = new ExportRep($globalSS);



if(!isset($_GET['id']))
echo "<h2>".$_lang['stEXPORTREPMODULE']."</h2><br />";

$start=microtime(true);


//visual part
?>

<form name=checkdate_form onsubmit="index.php" method="GET">
<p><?php echo $_lang['stSETDATEPERIOD']?><p>
<input type="text" name=date onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)">
<br /><br />
<input type="text" name=date2  onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)">&nbsp;
	<br><br>
<input type="submit" value="Set">
	</form>
 <?php echo "<h3>Reports from ".$querydate." to ".$querydate2."</h3>"?>
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
			<?php echo '<td><a href="index.php?actid=8&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=8&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>
		</tr>	
    	<tr>
			<td>2</td>
			<td><?php echo $_lang['stONELOGINTOPSITESTRAFFIC']; ?></td>
			<?php echo '<td><a href="index.php?actid=9&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=9&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>
		</tr>	
    	<tr>
			<td>3</td>
			<td><?php echo $_lang['stONELOGINTRAFFICBYHOURS']; ?></td>
			<?php echo '<td><a href="index.php?actid=10&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=10&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>
		</tr>	
    	<tr>
			<td>4</td>
			<td><?php echo $_lang['stONELOGINIPTRAFFIC']; ?></td>
			<?php echo '<td><a href="index.php?actid=35&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=35&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>
		</tr>	
    	<tr>
			<td>5</td>
			<td><?php echo $_lang['stMIMETYPESTRAFFIC']; ?></td>
			<?php echo '<td><a href="index.php?actid=46&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=46&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>
		</tr>	
    	<tr>
			<td>6</td>
			<td><?php echo $_lang['stPOPULARSITES']; ?></td>
			<?php echo '<td><a href="index.php?actid=56&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=56&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>
		</tr>	
    	<tr>
			<td>7</td>
			<td><?php echo $_lang['stLOGINBIGFILES']; ?></td>
			<?php echo '<td><a href="index.php?actid=66&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=66&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>
		</tr>	
    	<tr>
			<td>8</td>
			<td><?php echo $_lang['stTRAFFICBYPERIODDAY']; ?></td>
			<?php echo '<td><a href="index.php?actid=70&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=70&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>
		</tr>	
    	<tr>
			<td>9</td>
			<td><?php echo $_lang['stTRAFFICBYPERIODDAYNAME']; ?></td>
			<?php echo '<td><a href="index.php?actid=71&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=71&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>
		</tr>	

    	<tr>
			<td>10</td>
			<td><?php echo $_lang['stONEIPADRESSTRAFFIC']; ?></td>
			<?php echo '<td><a href="index.php?actid=11&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=11&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>

		</tr>	
		<tr>
			<td>11</td>
			<td><?php echo $_lang['stONEIPADDRESSTOPSITESTRAFFIC']; ?></td>
			<?php echo '<td><a href="index.php?actid=12&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=12&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>

		</tr>
		<tr>
			<td>12</td>
			<td><?php echo $_lang['stONEIPADDRESSTRAFFICBYHOURS']; ?></td>
			<?php echo '<td><a href="index.php?actid=13&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=13&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>

		</tr>
		<tr>
			<td>13</td>
			<td><?php echo $_lang['stONEIPADDRESSLOGINSTRAFFIC']; ?></td>
			<?php echo '<td><a href="index.php?actid=36&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=36&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>

		</tr>
		<tr>
			<td>14</td>
			<td><?php echo $_lang['stMIMETYPESTRAFFIC']; ?></td>
			<?php echo '<td><a href="index.php?actid=47&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=47&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>

		</tr>
		<tr>
			<td>15</td>
			<td><?php echo $_lang['stPOPULARSITES']; ?></td>
			<?php echo '<td><a href="index.php?actid=57&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=57&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>

		</tr>
		<tr>
			<td>16</td>
			<td><?php echo $_lang['stIPADDRESSBIGFILES']; ?></td>
			<?php echo '<td><a href="index.php?actid=67&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=67&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>

		</tr>
		<tr>
			<td>17</td>
			<td><?php echo $_lang['stTRAFFICBYPERIODDAY']; ?></td>
			<?php echo '<td><a href="index.php?actid=72&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=72&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>

		</tr>
		<tr>
			<td>18</td>
			<td><?php echo $_lang['stTRAFFICBYPERIODDAYNAME']; ?></td>
			<?php echo '<td><a href="index.php?actid=73&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=73&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>

		</tr>
		<tr>
			<td>19</td>
			<td><?php echo $_lang['stUNAUTHTRAFFIC']; ?></td>
			<?php echo '<td><a href="index.php?actid=74&pdf=1&date='.$querydate.'&date2='.$querydate2.'">PDF</a></td>' ?>
			<?php echo '<td><a href="index.php?actid=74&csv=1&date='.$querydate.'&date2='.$querydate2.'">CSV</a></td>' ?>

		</tr>
	
	</table>


<?php



//compute
$repvars=array();
$repvars['querydate'] = $querydate;
$repvars['querydate2'] = $querydate2; 

#надо подумать есть ли смысл
$repvars['sortcolumn'] = $sortcolumn;
$repvars['sortorder'] = $sortorder;

$loginReports=array(8, 9, 10, 35, 46, 56, 66, 70, 71);

$ipaddressReports=array(11, 12, 13, 36, 47, 57, 67, 72, 73, 74);


if(isset($_GET['actid'])){
{
		if(in_array($_GET['actid'],$loginReports)) {//сформировать отчёты по логинам 

		  
	   	  $numrow=0;
		  $sqlGetId="select id,name from scsq_logins where id not in (".$goodLoginsList.")";
		  $result=doFetchQuery($globalSS, $sqlGetId);

		  $repvars['id']=$_GET['actid'];
		  $repvars['typeid']="0";



		  echo "proccess started<br />";
		  foreach ($result as $line) {

			$repvars['loginid']=$line[0];
			$repvars['loginname']=$line[1];
			
			if($_GET['pdf']==1)
				$exportrep_ex->CreateLoginsPDF($repvars);

			if($_GET['csv']==1)
				$exportrep_ex->CreateLoginsCSV($repvars);
			
			$numrow++;
		  }
		  
		 } //actid=1
		  		  
		 if(in_array($_GET['actid'],$ipaddressReports)) {//сформировать отчёты по ip адресам PDF

		  $numrow=0;
		  $sqlGetId="select id,name from scsq_ipaddress where id not in (".$goodIpaddressList.")";
		  $result=doFetchQuery($globalSS, $sqlGetId);

		  $repvars['id']=$_GET['actid'];
		  $repvars['typeid']="1";

		  echo "proccess started<br />";
		  foreach ($result as $line) {
			$repvars['ipaddressid']=$line[0];
			$repvars['ipaddressname']=$line[1];

			if($_GET['pdf']==1)
			$exportrep_ex->CreateIpaddressPDF($repvars);

		if($_GET['csv']==1)
			$exportrep_ex->CreateIpaddressCSV($repvars);

		
			$numrow++;

		 }
		} //actid=2
		
		
		 
		echo $numrow." files created (check output directory)";

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
