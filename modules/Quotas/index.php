<?php
#build 20191023

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
<link rel="stylesheet" type="text/css" href="css/example.css"/>

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
include_once("langs/$language");



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

$quotaex = new Quotas($variableSet);


$ssq = new ScreenSquid($variableSet); #получим экземпляр класса и будем уже туда закиыдвать запросы на исполнение


if(!isset($_GET['id']))
echo "<h2>".$_lang['stQUOTASMODULE']."</h2><br />";

$start=microtime(true);


   
 
            if(isset($_GET['actid'])) //action ID.
              $actid=$_GET['actid'];
            else
              $actid=0;


              if(isset($_GET['quotaid'])) //quota ID из таблицы scsq_mod_quotas для редактирования/удаления quota.
                $quotaid=$_GET['quotaid'];
              else
                $quotaid=0;
		
		  if(isset($_POST['aliasid'])) $aliasid=$_POST['aliasid']; else $aliasid='';
	  
	      if(isset($_POST['quotaday'])) $quotaday=$_POST['quotaday']+0;  else $quotaday=0;
	      if(isset($_POST['quotamonth'])) $quotamonth=$_POST['quotamonth']+0;  else $quotamonth=0;

	      if(isset($_POST['sumday'])) $sumday=$_POST['sumday'];
	      if(isset($_POST['summonth'])) $summonth=$_POST['summonth'];

              if(isset($_POST['quota'])) // 
                $quota=$_POST['quota']+0;
              else
                $quota=0;

              if(isset($_POST['active'])) //  
                $active=1;
              else
                $active=0;



///SQL querys

if($dbtype==0)
$str = "group_concat(scsq_groups.name order by scsq_groups.name asc) as gconcat,
	group_concat(scsq_groups.id order by scsq_groups.name asc)";

if($dbtype==1)
$str = "string_agg(scsq_groups.name, ',' order by scsq_groups.name asc) as gconcat,
	string_agg(CAST(scsq_groups.id as text), ',' order by scsq_groups.name asc)";
	
    
               $queryAllAliases="
	   SELECT 
	     tmp.alname,
	     tmp.altypeid,
	     tmp.altableid,
	     tmp.alid,
	     tmp.tablename,
	     ".$str."
	  FROM ((SELECT 
		scsq_alias.name as alname,
		scsq_alias.typeid as altypeid,
		scsq_alias.tableid as altableid,
		scsq_alias.id as alid,
		scsq_logins.name as tablename 
	       FROM scsq_alias 
	       LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id 
	       WHERE scsq_alias.typeid=0) 

	       UNION 
			
	        (SELECT 
		   scsq_alias.name as alname,
		   scsq_alias.typeid as altypeid,
		   scsq_alias.tableid as altableid,
		   scsq_alias.id as alid,
		   scsq_ipaddress.name as tablename 
		 FROM scsq_alias 
		 LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 
		 WHERE scsq_alias.typeid=1)) as tmp
	  
	  LEFT JOIN scsq_aliasingroups ON scsq_aliasingroups.aliasid=tmp.alid
	  LEFT JOIN scsq_groups ON scsq_aliasingroups.groupid=scsq_groups.id
	  GROUP BY tmp.altableid,tmp.alname,tmp.altypeid,tmp.alid,tmp.tablename
	  ORDER BY alname asc";
	  
	  
        $queryAllAliasesHaveNotQuota="
  	   SELECT 
	     tmp.alname,
	     tmp.altypeid,
	     tmp.altableid,
	     tmp.alid,
	     tmp.tablename,
	     ".$str."
	  FROM ((SELECT 
		scsq_alias.name as alname,
		scsq_alias.typeid as altypeid,
		scsq_alias.tableid as altableid,
		scsq_alias.id as alid,
		scsq_logins.name as tablename 
	       FROM scsq_alias 
	       LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id 
	       WHERE scsq_alias.typeid=0) 

	       UNION 
			
	        (SELECT 
		   scsq_alias.name as alname,
		   scsq_alias.typeid as altypeid,
		   scsq_alias.tableid as altableid,
		   scsq_alias.id as alid,
		   scsq_ipaddress.name as tablename 
		 FROM scsq_alias 
		 LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 
		 WHERE scsq_alias.typeid=1)) as tmp
	  
	  LEFT JOIN scsq_aliasingroups ON scsq_aliasingroups.aliasid=tmp.alid
	  LEFT JOIN scsq_groups ON scsq_aliasingroups.groupid=scsq_groups.id
	  
	  WHERE alid NOT IN (select aliasid from scsq_mod_quotas)
	  GROUP BY tmp.altableid,tmp.alname,tmp.altypeid,tmp.alid,tmp.tablename
	  ORDER BY alname asc";


            $queryAllQuotas="SELECT 
				sm.id,
				sm.aliasid,
				sa.name,
				sumday,
				summonth,
				quotaday,
				quotamonth,
				quota,
				status,
				sm.active,
				sa.id
			     FROM scsq_mod_quotas sm, scsq_alias sa
			     WHERE sm.aliasid = sa.id
			     ORDER BY aliasid asc;";


   
            $queryOneQuota="select aliasid,quota,quotaday,quotamonth,sm.active,sa.name,sm.id,sumday,summonth from scsq_mod_quotas sm, scsq_alias sa where sm.id='".$quotaid."' 
		and sm.aliasid=sa.id
		;";

#при обновлении квоты, не будем дожидатся сработку скрипта, а сразу обновим статус
if($active == 0){
$status=0;
}
else
{
#if((0 < ($quota))and(0 < ($quotamonth))){
$status=0; #нет превышения квоты
#}

if(($sumday > $quota)&&($quota >0 )){
$status=1; #текущий траффик вышел за пределы квоты

}

if(($summonth > $quotamonth) &&($quotamonth >0 )){

$status=2; #текущий месячный траффик вышел за пределы месячной квоты
}

if(($sumday < ($quota))&&($summonth < ($quotamonth))){
$status=0; #нет превышения квоты
}


}

$querydate=date("d-m-Y");
#$querydate=date("07-08-2019");# for debug
$datestart=strtotime($querydate);


   	    $queryUpdateOneQuota="update scsq_mod_quotas set aliasid=".$aliasid.",quota=".$quota.",quotaday=".$quotaday.",quotamonth=".$quotamonth.",active=".$active.",status=".$status.", datemodified=".$datestart." where id=".$quotaid."";



            $queryDeleteOneQuota="delete from scsq_mod_quotas where id=".$quotaid."";

            if(!isset($_GET['actid'])) {

              $result=$ssq->query($queryAllQuotas);
              $numrow=1;
	      echo "<a href=index.php?srv=".$srv.">".$_lang['stREFRESH']."</a>";
              echo "<br /><br />";
              echo "<table id=report_table_id_group border=1 class=sortable>
      
             <tr>
                <th class=unsortable><b>#</b></th>
		<th><b>".$_lang['stQUOTASSTATUS']."</b></th>
                <th><b>".$_lang['stALIAS']."</b></th>
		<th><b>".$_lang['stVALUE']."</b></th>
 		<th class=unsortable><b>".$_lang['stQUOTASCURRENT']."</b></th>
                <th class=unsortable><b>".$_lang['stQUOTASDAY']."</b></th>
                <th class=unsortable><b>".$_lang['stQUOTASTRAFFICDAY']."</b></th>
                <th class=unsortable><b>".$_lang['stQUOTASMONTH']."</b></th>
                <th class=unsortable><b>".$_lang['stQUOTASTRAFFICMONTH']."</b></th>
		<th><b>".$_lang['stQUOTASACTIVE']."</b></th>

              </tr>";

              while($line = $ssq->fetch_array($result)) {
		
		#раскрасим строки в зависимости от статуса по квотам
		if($line[8] == 0)
			$alarmclass=""; #нет превышения
		if($line[8] == 1)
			$alarmclass="class=quotaAlm1"; #дневная превышена
		if($line[8] == 2)
			$alarmclass="class=quotaAlm2"; #месячная превышена

                
               echo "<tr ".$alarmclass.">
	       
                    <td>".$numrow."</td>
                    <td align=center>".$line[8]."</td>                 
                    <td align=center><a href=index.php?srv=".$srv."&actid=3&quotaid=".$line[0].">".$line[2]."&nbsp;</a></td>
		    <td>".$quotaex->GetAliasValue($line[10])."</td>
                    <td align=center>".$line[7]."&nbsp;</td>
                    <td align=center>".$line[5]."&nbsp;</td>
		    <td align=center>".$line[3]."</td>
                    <td align=center>".$line[6]."&nbsp;</td>
  		    <td align=center>".$line[4]."</td>                 
		    <td align=center>".$line[9]."</td>                 
		</tr>";
                $numrow++;
              }
              echo "</table>";
              echo "<br />";
              echo "<a href=index.php?srv=".$srv."&actid=1>".$_lang['stQUOTASADDQUOTA']."</a>";
              echo "<br />";
		$ssq->free_result($result);
            }

          if($actid==1) {
            echo $_lang['stQUOTASFORMADDQUOTA'];
              echo '
                <form action="index.php?srv='.$srv.'&actid=2" method="post">
                '.$_lang['stQUOTASDAY'].': <input type="text" name="quotaday" ><br />
                '.$_lang['stQUOTASMONTH'].': <input type="text" name="quotamonth"><br />
		'.$_lang['stQUOTASACTIVE'].': <input type="checkbox" name="active"><br /> 
		'.$_lang['stQUOTASEXCLUDE'].': <input type="checkbox" onClick="switchTables();" name="typeid"><br /><br />
                '.$_lang['stVALUE'].':<br />';
  

                $result=$ssq->query($queryAllAliases) or die ('Error: Cant select aliases from scsq_alias table');
         $numrow=1;

              echo "<table id='loginsTable' class=sortable style='display:table;'>";
              echo "<tr>
		    <th class=unsortable>#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		    </tr>";

              while($line = $ssq->fetch_array($result)) {
                echo "
                  <tr>
                    <td >".$numrow."</td>
                    <td >".$line[0]."</td>
                    <td><input type='radio' name='aliasid' value='".$line[3]."';</td>
                  </tr>";
                $numrow++;
              }
	      $ssq->free_result($result);
              echo "</table>";

	$result=$ssq->query($queryAllAliasesHaveNotQuota) or die ('Error: Cant select aliases from scsq_alias table');       
         $numrow=1;

              echo "<table id='ipaddressTable' class=sortable style='display:none;'>";
              echo "<tr>
		    <th class=unsortable>#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		    </tr>";

              while($line = $ssq->fetch_array($result)) {
                echo "
                  <tr>
                    <td >".$numrow."</td>
                    <td >".$line[0]."</td>
                    <td><input type='radio' name='aliasid' value='".$line[3]."';</td>
                  </tr>";
                $numrow++;
              }
	      $ssq->free_result($result);
              echo "</table>";



               echo '
                <input type="submit" value="'.$_lang['stADD'].'"><br />
                </form>
                <br />';
            }

            if($actid==2) { ///добавление 

              $aliasid=$_POST['aliasid'];
	  //    $quota=$_POST['quota'];
	
              if(!isset($_POST['active']))
                $active=0;
              else
                $active=1;



              $sql="INSERT INTO scsq_mod_quotas (aliasid, quota, quotaday, quotamonth,active) VALUES ($aliasid, $quotaday,$quotaday,$quotamonth,$active)";

              if (!$ssq->query($sql)) {
                die('Error: Cant insert into scsq_mod_quotas table' );
              }

 

              echo "".$_lang['stQUOTASADDED']."<br /><br />";
              echo "<a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a>";
            }

            if($actid==3) { ///Редактирование 
              $result=$ssq->query($queryOneQuota) or die ('Error: Cant select one quota from scsq_mod_quotas');
              $line=$ssq->fetch_array($result);
	      $ssq->free_result($result);
              if($line[4]==1)
                $isChecked="checked";
              else
                $isChecked="";

	      $quotaid = $line[6];
              echo '
                <form action="index.php?srv='.$srv.'&actid=4&quotaid='.$quotaid.'" method="post">
	       '	.$_lang['stALIAS'].': '.$line[5].'<br /><br />
               '.$_lang['stQUOTASCURRENT'].': <input type="text" name="quota" value="'.$line[1].'"><br />
               '.$_lang['stQUOTASDAY'].': <input type="text" name="quotaday" value="'.$line[2].'"><br />
               М'.$_lang['stQUOTASMONTH'].': <input type="text" name="quotamonth" value="'.$line[3].'"><br />
	       <input type="hidden" name=sumday value="'.$line[7].'">
	       <input type="hidden" name=summonth value="'.$line[8].'">
               '.$_lang['stQUOTASACTIVE'].': <input type="checkbox" '.$isChecked.' name="active"><br /> 
	
		'.$_lang['stQUOTASEXCLUDE'].': <input type="checkbox" onClick="switchTables();" name="typeid"><br /><br />
               '.$_lang['stVALUE'].':<br />';

		$checkedAliasid = $line[0];

                $result=$ssq->query($queryAllAliases) or die('Error: Cant select all aliases from scsq_alias');
                $numrow=1;

              echo "<table id='loginsTable' class=sortable style='display:table;'>";
              echo "<tr>
		    <th class=unsortable>#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		    </tr>";

              while($line = $ssq->fetch_array($result)) {
                echo "
                  <tr>
                    <td >".$numrow."</td>
                    <td >".$line[0]."</td>";
if($line[3]==$checkedAliasid)
   	echo        "<td><input type='radio' name='aliasid' checked value='".$line[3]."';</td>";
else
	echo         "<td><input type='radio' name='aliasid' value='".$line[3]."';</td>";
        echo         "</tr>";
                $numrow++;
              }
	      $ssq->free_result($result);
              echo "</table>";

		$result=$ssq->query($queryAllAliasesHaveNotQuota) or die ('Error: Cant select aliases from scsq_alias table');       
         $numrow=1;

              echo "<table id='ipaddressTable' class=sortable style='display:none;'>";
              echo "<tr>
		    <th class=unsortable>#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		    </tr>";

              while($line = $ssq->fetch_array($result)) {
                echo "
                  <tr>
                    <td >".$numrow."</td>
                    <td >".$line[0]."</td>
                    <td><input type='radio' name='aliasid' value='".$line[3]."';</td>
                  </tr>";
                $numrow++;
              }
	      $ssq->free_result($result);
              echo "</table>";

               echo '
                 <input type="submit" value="'.$_lang['stSAVE'].'"><br />
                 </form>
                 <form action="index.php?srv='.$srv.'&actid=5&quotaid='.$quotaid.'" method="post">
                 <input type="submit" value="'.$_lang['stDELETE'].'"><br />
                 </form>
                 <br />';
            } // end actid=3

            if($actid==4) { //сохранение изменений UPDATE

              if (!$ssq->query($queryUpdateOneQuota)) {
                die('Error: Can`t update one quota');
              }


              echo "".$_lang['stQUOTASUPDATED']."<br /><br />";
              echo "<a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a>";
            } //end actid=4

            if($actid==5) {//удаление DELETE

              if (!$ssq->query($queryDeleteOneQuota)) {
                die('Error: Cant DELETE one quota from scsq_mod_quotas ');
              }
       
              echo "".$_lang['stQUOTASDELETED']."<br /><br />";
              echo "<a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a><br />";

            } //end actid=5

      

         



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
