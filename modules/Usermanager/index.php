<?php

#Build date Friday 24th of April 2020 16:05:25 PM
#Build revision 1.2

#чтобы убрать возможные ошибки с датой, установим на время исполнения скрипта ту зону, которую отдает система.
date_default_timezone_set(date_default_timezone_get());

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;

include("../../config.php");

$language=$globalSS['language'];

include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

include("module.php");
include_once("../../lang/$language");
	
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
</script>


<?php


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

$globalSS['connectionParams']=$variableSet;


$usermanagerex = new Usermanager($globalSS);
	

if(!isset($_GET['id']))
echo "<h2>".$_lang['stUSERMANAGERMODULE']."</h2><br />";

$start=microtime(true);


   
 
            if(isset($_GET['actid'])) //action ID.
              $actid=$_GET['actid'];
            else
              $actid=0;


              if(isset($_GET['userid'])) //user ID из таблицы scsq_mod_usermanager для редактирования/удаления quota.
                $userid=$_GET['userid'];
              else
                $userid=0;
		
		  if(isset($_POST['aliasid'])) $aliasid=$_POST['aliasid']; else $aliasid='';
	  
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
	  



            $queryAllUsers="SELECT 
				sm.id,
				sm.aliasid,
				sa.name,
				sm.active,
				sa.id
			     FROM scsq_mod_usermanager sm, scsq_alias sa
			     WHERE sm.aliasid = sa.id
			     ORDER BY aliasid asc;";


   
            $queryOneUser="select aliasid,
								  sm.active,
								  sa.name,
								  sm.id 
						   from scsq_mod_usermanager sm, scsq_alias sa 
						   where sm.id='".$userid."' 
							 and sm.aliasid=sa.id
							;";

$querydate=date("d-m-Y");
#$querydate=date("07-08-2019");# for debug
$datestart=strtotime($querydate);


   	    $queryUpdateOneUser="update scsq_mod_usermanager set active=".$active.", datemodified=".$datestart." where id=".$userid."";

   	    $queryCopyAliases="INSERT INTO scsq_mod_usermanager(aliasid) select id from scsq_alias t where t.id not in (SELECT aliasid from scsq_mod_usermanager)";

   	    $queryDeleteAliases="DELETE FROM scsq_mod_usermanager where aliasid not in (select id from scsq_alias);";

        $queryNeedRefresh="select count(1) from scsq_alias sa where sa.id not in (select aliasid from scsq_mod_usermanager);";
			
		$needLine=doFetchOneQuery($globalSS, $queryNeedRefresh);
				
		$needRefresh = $needLine[0];
        
        $queryNeedRefresh="select count(1) from scsq_mod_usermanager sm where sm.aliasid not in (select id from scsq_alias);";
			
		$needLine=doFetchOneQuery($globalSS, $queryNeedRefresh);
		
		$needRefresh += $needLine[0];
 

            if(!isset($_GET['actid'])) {

              $result=doFetchQuery($globalSS, $queryAllUsers);
              $numrow=1;
              //обновим список пользователей, если вдруг созданы алиасы
	      echo "<a href=index.php?srv=".$srv."&actid=10>".$_lang['stREFRESH']."</a>";
	      
	      //Если вдруг алиасов в таблице алиасов больше чем в модуле, то выводим сообщение "нажми обновить" 
	      if($needRefresh>0) echo " (Please, click Refresh)";
	      
              echo "<br /><br />";
              echo "<table class=datatable>
      
             <tr>
                <th class=unsortable><b>#</b></th>
                <th><b>".$_lang['stALIAS']."</b></th>
				<th><b>".$_lang['stVALUE']."</b></th>
				<th><b>".$_lang['stUSERMANAGERACTIVE']."</b></th>

              </tr>";

              foreach($result as $line) {
		

               echo "<tr>
	       
                    <td>".$numrow."</td>
                    <td align=center><a href=index.php?srv=".$srv."&actid=3&userid=".$line[0].">".$line[2]."&nbsp;</a></td>
					<td>".$usermanagerex->GetAliasValue($line[1])."</td>
					<td align=center>".$line[3]."</td>                 
		</tr>";
                $numrow++;
              }
              echo "</table>";
              echo "<br />";
		
            }


            if($actid==3) { ///Редактирование 
              $result=doFetchOneQuery($globalSS, $queryOneUser) or die ('Error: Cant select one user from scsq_mod_usermanager');
             
	    
              if($line[1]==1)
                $isChecked="checked";
              else
                $isChecked="";

            $queryOneUser="select aliasid,
								  sm.active,
								  sa.name,
								  sm.id 
						   from scsq_mod_usermanager sm, scsq_alias sa 
						   where sm.id='".$userid."' 
							 and sm.aliasid=sa.id
							;";

	      $userid = $line[3];
              echo '
                <form action="index.php?srv='.$srv.'&actid=4&userid='.$userid.'" method="post">
	       '	.$_lang['stALIAS'].': '.$line[2].'<br /><br />
               '.$_lang['stUSERMANAGERACTIVE'].': <input type="checkbox" '.$isChecked.' name="active"><br /> 

                 <input type="submit" value="'.$_lang['stSAVE'].'"><br />
                 </form>
                 <br />';
            } // end actid=3

            if($actid==4) { //сохранение изменений UPDATE

              if (!doQuery($globalSS, $queryUpdateOneUser)) {
                die('Error: Can`t update one user');
              }


              echo "".$_lang['stUSERMANAGERUPDATED']."<br /><br />";
              echo "<a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a>";
            } //end actid=4


                if($actid==10) {//обновление списка

              if (!doQuery($globalSS, $queryCopyAliases)) {
                die('Error: Cant copy aliases to scsq_mod_usermanager ');
              }
 
              if (!doQuery($globalSS, $queryDeleteAliases)) {
                die('Error: Cant delete aliases from scsq_mod_usermanager ');
              }
 
       
              echo "".$_lang['stUSERMANAGERALIASCOPIED']."<br /><br />";
              echo "<a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a><br />";

            } //end actid=10
  

         



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
