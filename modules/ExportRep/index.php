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
*                         File Mod     > <!#FT> 2024/06/25 20:55:38.679 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
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
			<td><a href="Javascript:CreateDoc(1)">PDF</a></td>
			<td><a href="Javascript:CreateDoc(3)">CSV</a></td>
		</tr>	
    	<tr>
			<td>2</td>
			<td><?php echo $_lang['stONEIPADRESSTRAFFIC']; ?></td>
			<td><a href="Javascript:CreateDoc(2)">PDF</a></td>
			<td><a href="Javascript:CreateDoc(4)">CSV</a></td>
		</tr>	
	</table>
</form>

<?php



//compute
$repvars=array();
$repvars['querydate'] = $querydate;
$repvars['querydate2'] = $querydate2; 

#надо подумать есть ли смысл
$repvars['sortcolumn'] = $sortcolumn;
$repvars['sortorder'] = $sortorder;




if(isset($_GET['actid'])){
{
		if($_GET['actid']==1) {//сформировать отчёты по логинам PDF

		  
	   	  $numrow=0;
		  $sqlGetId="select id,name from scsq_logins where id not in (".$goodLoginsList.")";
		  $result=doFetchQuery($globalSS, $sqlGetId);

		  $repvars['id']="8";
		  $repvars['typeid']="0";



		  echo "proccess started<br />";
		  foreach ($result as $line) {

			$repvars['loginid']=$line[0];
			$repvars['loginname']=$line[1];
			
			$exportrep_ex->CreateLoginsPDF($repvars);
			$numrow++;
		  }
		  
		 } //actid=1
		  		  
		if($_GET['actid']==2) {//сформировать отчёты по ip адресам PDF

		  $numrow=0;
		  $sqlGetId="select id,name from scsq_ipaddress where id not in (".$goodIpaddressList.")";
		  $result=doFetchQuery($globalSS, $sqlGetId);

		  $repvars['id']="11";
		  $repvars['typeid']="1";

		  echo "proccess started<br />";
		  foreach ($result as $line) {
			$repvars['ipaddressid']=$line[0];
			$repvars['ipaddressname']=$line[1];
			$exportrep_ex->CreateIpaddressPDF($repvars);
			$numrow++;

		 }
		} //actid=2
		
		if($_GET['actid']==3) {//сформировать отчёты по логинам CSV
			$numrow=0;
			$sqlGetId="select id,name from scsq_logins where id not in (".$goodLoginsList.")";
			$result=doFetchQuery($globalSS, $sqlGetId);

			$repvars['id']="8";
			$repvars['typeid']="0";
	  
			echo "proccess started<br />";
			foreach ($result as $line) {
	  
 	 		  $repvars['loginid']=$line[0];
			  $repvars['loginname']=$line[1];
				  
		 	  $exportrep_ex->CreateLoginsCSV($repvars);
				$numrow++;
				}
		 } //actid=3
		 
 		if($_GET['actid']==4) {//сформировать отчёты по ip адресам CSV

			$numrow=0;
			$sqlGetId="select id,name from scsq_ipaddress where id not in (".$goodIpaddressList.")";
			$result=doFetchQuery($globalSS, $sqlGetId);
		  
			$repvars['id']="11";
			$repvars['typeid']="1";
		  
			echo "proccess started<br />";
			foreach ($result as $line) {
			  $repvars['ipaddressid']=$line[0];
			  $repvars['ipaddressname']=$line[1];
			  $exportrep_ex->CreateIpaddressCSV($repvars);
			  $numrow++;
			}
		} //actid=4
		
		
		 
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
