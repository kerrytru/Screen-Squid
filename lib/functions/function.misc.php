<?php

#узнаем установлен ли модуль категорий
function doQueryExistsModuleCategory($globalSS){

include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

$queryExistModuleCategory = "select count(1) from scsq_modules where name = 'Categorylist';";

$result = doFetchOneQuery($globalSS,$queryExistModuleCategory);

if($result[0] == 1)
    return ', category';
else
    return '';

}

#составим список  дружественных логинов или IP адресов
function doCreateFriendList($globalSS, $type){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

#create list of friends
    if($globalSS['enableNofriends'] == "0") return 0;

    if($globalSS['goodLogins'] == "" && $type == "logins") return 0;
    if($globalSS['goodIpaddress'] == "" && $type == "ipaddress") return 0;


    if($type == 'logins')  { $friendsTable = 'scsq_logins'; $friends=implode("','",explode(" ", $globalSS['goodLogins']));}

    if($type == 'ipaddress') { $friendsTable = 'scsq_ipaddress'; $friends=implode("','",explode(" ", $globalSS['goodIpaddress'])); }

    $goodList = "0";

    $friendsTmp="where name in  ('".$friends."')";
    $sqlGetFriendsId="select id from ".$friendsTable." ".$friendsTmp."";
    $result = doFetchQuery($globalSS,$sqlGetFriendsId);

    foreach ($result as $row) {
        $goodList=$goodList.",".$row[0];
    }

    return $goodList;

}

#составим список  не учитываемых сайтов
function doCreateSitesList($globalSS){

    if($globalSS['enableNoSites'] == "0") return "''";

    if($globalSS['goodSites'] == "") return "''";

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

    $sitesTmp=implode("','",explode(" ", $globalSS['goodSites']));
    $sitesTmp="'".$sitesTmp."'";

    return $sitesTmp;

}


#Функции работы с алиасами
function doPrintAllAliases($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
    $_lang = $globalSS['lang'];

    if($globalSS['connectionParams']['dbtype']==0)
    $str = "group_concat(scsq_groups.name order by scsq_groups.name asc) as gconcat,
        group_concat(scsq_groups.id order by scsq_groups.name asc)";
    
    if($globalSS['connectionParams']['dbtype']==1)
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

    $result = doFetchQuery($globalSS,$queryAllAliases);

    $numrow=1;
    echo 	"<h2>".$_lang['stALIASES'].":</h2>";
    echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2&actid=1>".$_lang['stADDALIAS']."</a>";
    echo "<br /><br />";

    echo "<table class=\"datatable\">
            <tr>
              <th ><b>#</b></th>
              <th><b>".$_lang['stALIASNAME']."&nbsp;</b></th>
              <th ><b>".$_lang['stTYPE']."</b></th>
             <th><b>".$_lang['stVALUE']."</b></th>
             <th><b>".$_lang['stGROUP']."</b></th>
             <th><b>".$_lang['stDELETE']."</b></th>
         </tr>
    ";

   foreach($result as $line) {
     if($line[1]=="1")
       $line[1]="<b><font color=green>".$_lang['stIPADDRESS']."</font></b>";
     else
       $line[1]="<b><font color=red>".$_lang['stLOGIN']."</font></b>";
     echo "
     <tr>
       <td>".$numrow."</td>

       <td align=center><a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2&actid=3&aliasid=".$line[3].">".$line[0]."&nbsp;</a></td>
       <td align=center>".$line[1]."</td>
       <td>".$line[4]."&nbsp;</td>
       <td>";
 $i=0;
 $splitgroupsname=explode(',',$line[5]);
 $splitgroupsid=explode(',',$line[6]);
 foreach($splitgroupsname as $val) {
   echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=3&groupid=".$splitgroupsid[$i].">".$val."</a>&nbsp";
   $i++;

}

 echo "</td>
   <td align=center><a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2&actid=5&aliasid=".$line[3].">".$_lang['stDELETE']."</a></td>

     </tr>
     ";
     $numrow++;
    }  //end while
        
echo "</table>";
   echo "<br />";
   echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2&actid=1>".$_lang['stADDALIAS']."</a>";
   echo "<br />";
    
    }


function doPrintFormAddAlias($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
    $_lang = $globalSS['lang'];

    $goodLoginsList=$globalSS['goodLoginsList'];
    $goodIpaddressList=$globalSS['goodIpaddressList'];
 

    $queryAllLoginsToAdd="select id,name from scsq_logins  where id NOT IN (".$goodLoginsList.") and id NOT IN (select scsq_alias.tableid from scsq_alias where typeid=0) order by name asc;";

    $queryAllIpaddressToAdd="select id,name from scsq_ipaddress where id NOT IN (".$goodIpaddressList.") and id NOT IN (select scsq_alias.tableid from scsq_alias where typeid=1) order by name asc;";

    $queryAllLogins="select id,name from scsq_logins  where id NOT IN (".$goodLoginsList.") order by name asc;";
    $queryAllIpaddress="select id,name from scsq_ipaddress where id NOT IN (".$goodIpaddressList.") order by name asc;";


        echo "<h2>".$_lang['stFORMADDALIAS']."</h2>";
        echo '
          <form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=2&actid=2" method="post">
          
          <table class=datatable >
         <tr><td>'.$_lang['stALIASNAME'].':</td> <td><input type="text" name="name"></td></tr>
         <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" onClick="switchTables();" name="typeid"></td></tr>
         <tr><td>'.$_lang['stACTIVEAUTH'].':</td> <td> <input type="checkbox" name="activeauth"></td></tr>
             <tr><td>'.$_lang['stUSERLOGIN'].':</td> <td> <input type="text" name="userlogin"></td></tr>
             <tr><td>'.$_lang['stUSERPASSWORD'].':</td> <td> <input type="text" name="userpassword"></td></tr>
             </table>
         <br />
        <input type="submit" value="'.$_lang['stADD'].'">
         <p>'.$_lang['stVALUE'].':</p>
         <br />
         ';

        $result=doFetchQuery($globalSS,$queryAllLoginsToAdd);
        $numrow=1;

        echo "<table id='loginsTable' class=\"datatable\" style='display:table;'>";
    echo "<tr>";
    echo "    <th >#</th>
          <th >".$_lang['stLOGIN']."</th>
          <th >".$_lang['stINCLUDE']."</th>
      </tr>";
        foreach($result as $line) {
          echo "
          <tr>
            <td >".$numrow."</td>
            <td >".$line[1]."</td>
            <td><input type='radio' name='tableid' value='".$line[0]."';</td>
          </tr>
          ";
          $numrow++;
        }
        echo "</table>";
    

        $result=doFetchQuery($globalSS,$queryAllIpaddressToAdd);
        $numrow=1;

        echo "<table id='ipaddressTable' class=\"datatable\" style='display:none;'>";
    echo "<tr>";
    echo "    <th class=unsortable>#</th>
          <th>".$_lang['stIPADDRESS']."</th>
          <th class=unsortable>".$_lang['stINCLUDE']."</th>
      </tr>";

      foreach($result as $line) {
          echo "
            <tr>
              <td >".$numrow."</td>
              <td >".$line[1]."</td>
              <td><input type='radio' name='tableid' value='".$line[0]."';</td>
            </tr>
          ";
          $numrow++;
        }

    
              
    echo "</table>";
        echo '
          <input type="submit" value="'.$_lang['stADD'].'"><br />
          </form>
          <br />
        ';


    }

function doAliasAdd($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
    $_lang = $globalSS['lang'];

    $name=$_POST['name'];

    if(!isset($_POST['typeid'])) $typeid=0;  else  $typeid=1;
    if(!isset($_POST['activeauth'])) $activeauth=0; else $activeauth=1;

    $tableid=$_POST['tableid'];
    $userlogin=$_POST['userlogin'];
    $userpassword=md5(md5(trim($_POST['userpassword'])));

    $sql="INSERT INTO scsq_alias (name, typeid,tableid,userlogin,password,active) VALUES ('$name', '$typeid','$tableid','$userlogin','$userpassword','$activeauth')";

    if (!doQuery($globalSS,$sql)) {
      return('Error: Can`t insert alias into table!');
    }
    echo "".$_lang['stALIASADDED']."<br /><br />";
    echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2 target=right>".$_lang['stBACK']."</a>";          
    
    
  }

  function doAliasEdit($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
    $_lang = $globalSS['lang'];
    
    $aliasid = $_GET['aliasid'];

    $goodLoginsList=$globalSS['goodLoginsList'];
    $goodIpaddressList=$globalSS['goodIpaddressList'];
 
    $queryAllLogins="select id,name from scsq_logins  where id NOT IN (".$goodLoginsList.") order by name asc;";
    $queryAllIpaddress="select id,name from scsq_ipaddress where id NOT IN (".$goodIpaddressList.") order by name asc;";


    $queryOneAlias="select name,typeid,tableid,id,userlogin,password,active from scsq_alias where id='".$aliasid."';";

    $line=doFetchOneQuery($globalSS, $queryOneAlias);
          
	  $typeid=$line[1]; #сохраняем тип алиаса.

    if($typeid==1) $isChecked="checked";  else  $isChecked="";

          $tableid=$line[2];
          $aliasid=$line[3];

    if($line[6]==1)  $activeIsChecked="checked";  else  $activeIsChecked="";
		

		  echo "<h2>".$_lang['stFORMEDITALIAS']."</h2>";
          echo '
            <form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=2&actid=4&aliasid='.$aliasid.'" method="post">
            <table class=datatable>
           <tr><td>'.$_lang['stALIASNAME'].':</td> <td><input type="text" name="name" value="'.$line[0].'"></td></tr>
           <tr><td>'.$_lang['stACTIVEAUTH'].':</td> <td> <input type="checkbox" '.$activeIsChecked.' name="activeauth"></td></tr>
		   <tr><td>'.$_lang['stUSERLOGIN'].':</td> <td> <input type="text" name="userlogin" value="'.$line[4].'"></td></tr>
		   <tr><td>'.$_lang['stCHANGEPASSWORD'].':</td> <td> <input type="checkbox" name="changepassword"></td></tr>
		   <tr><td>'.$_lang['stUSERPASSWORD'].':</td> <td> <input type="text" name="userpassword"></td></tr>
		   </table>
		   <br />
           <p>'.$_lang['stVALUE'].':</p>
           <br />
                ';

      $result=doFetchQuery($globalSS,$queryAllLogins);
          $numrow=1;

          if($isChecked=="checked")
            echo "<table id='loginsTable' class=datatable style='display:none;'>";
          else
            echo "<table id='loginsTable' class=datatable style='display:table;'>";
	  echo "<tr>";
	  echo "    <th class=unsortable>#</th>
		    <th>".$_lang['stLOGIN']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
	        </tr>";

          foreach($result as $line) {
            echo "<tr>";
            echo "<td >".$numrow."</td>";
            echo "<td >".$line[1]."</td>";
            if(($tableid==$line[0])&&($ischecked==""))
              echo "<td><input type='radio' name='tableid' checked value='".$line[0]."'></td>";
            else
              echo "<td><input type='radio' name='tableid' value='".$line[0]."'></td>";
	    echo   "</tr>";
            $numrow++;
          }
          echo "</table>";

          $result=doFetchQuery($globalSS, $queryAllIpaddress);
          $numrow=1;

          if($isChecked=="checked")
            echo "<table id='ipaddressTable' class=datatable style='display:table;'>";
          else
            echo "<table id='ipaddressTable' class=datatable style='display:none;'>";
   
           echo "<tr>";
	         echo "    <th class=unsortable>#</th>
		                 <th>".$_lang['stIPADDRESS']."</th>
		                 <th class=unsortable>".$_lang['stINCLUDE']."</th>
	              </tr>";
          
      foreach($result as $line) {
            echo "<tr>";
          	echo    "<td >".$numrow."</td>";
	          echo    "<td >".$line[1]."</td>";
            
            if(($tableid==$line[0])&&($isChecked=="checked"))
              echo "<td><input type='radio' name='tableid' checked value='".$line[0]."'></td>";
            else
              echo "<td><input type='radio' name='tableid' value='".$line[0]."'></td>";
      
            echo "</tr>";
      
            $numrow++;
      }

      echo "</table>";
	  if($typeid == 1)
	  echo '<input type="hidden" name=typeid value="'.$typeid.'">';
         
	  echo '
	    <input type="submit" value="'.$_lang['stSAVE'].'"><br />
            </form>
            <form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=2&actid=5&aliasid='.$aliasid.'" method="post">
            <input type="submit" value="'.$_lang['stDELETE'].'"><br />
            </form>
            <br />';

  }

  function doAliasSave($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
    $_lang = $globalSS['lang'];
  

    $aliasid = $_GET['aliasid'];
    $name=$_POST['name'];

    if(!isset($_POST['typeid'])) $typeid=0;  else  $typeid=1;
    if(!isset($_POST['activeauth'])) $activeauth=0; else $activeauth=1;

    $tableid=$_POST['tableid'];
    $userlogin=$_POST['userlogin'];
    $userpassword=md5(md5(trim($_POST['userpassword'])));

    if(isset($_POST['changepassword'])) // признак. Если установлено - изменить пароль
    $changepassword=1;
    else
    $changepassword=0;

    

    if($changepassword==1)        
    $queryUpdateOneAlias="update scsq_alias set name='".$name."',typeid='".$typeid."',tableid='".$tableid."',userlogin='".$userlogin."',password='".$userpassword."',active='".$activeauth."' where id='".$aliasid."'";
    else
    $queryUpdateOneAlias="update scsq_alias set name='".$name."',typeid='".$typeid."',tableid='".$tableid."',userlogin='".$userlogin."',active='".$activeauth."' where id='".$aliasid."'";
    

    if (!doQuery($globalSS, $queryUpdateOneAlias)) {
    return 'Error: Can`t update 1 alias';
  }
  echo "".$_lang['stALIASUPDATED']."<br /><br />";
  echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2 target=right>".$_lang['stBACK']."</a>";
}

function doAliasDelete($globalSS){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
  $_lang = $globalSS['lang'];


  $aliasid = $_GET['aliasid'];

  $queryDeleteOneAlias="delete from scsq_alias where id='".$aliasid."'";
  $queryDeleteOneAliasFromGroup="delete from scsq_aliasingroups where aliasid='".$aliasid."'";


  if (!doQuery($globalSS, $queryDeleteOneAlias)) {
    die('Error: Can`t delete 1 alias');
  }
  if (!doQuery($globalSS, $queryDeleteOneAliasFromGroup)) {
    die('Error1: Cant delete 1 alias from group');
  }
  
echo "".$_lang['stALIASDELETED']."<br /><br />";
  echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2 target=right>".$_lang['stBACK']."</a>";


}


#Функции работы с группами
function doPrintAllGroups($globalSS){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
  
  $_lang = $globalSS['lang'];

  $queryAllGroups="SELECT 
  scsq_groups.name,
  scsq_groups.typeid,
  scsq_groups.id,
  scsq_groups.comment,
  count(scsq_aliasingroups.aliasid)
     FROM scsq_aliasingroups 
     RIGHT OUTER JOIN scsq_groups ON scsq_groups.id=scsq_aliasingroups.groupid
     GROUP BY scsq_aliasingroups.groupid, scsq_groups.name, scsq_groups.id, scsq_groups.typeid, scsq_groups.comment
     ORDER BY name asc;";

        $result=doFetchQuery($globalSS, $queryAllGroups);
        $numrow=1;
        echo 	"<h2>".$_lang['stGROUPS'].":</h2>";
        echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=1>".$_lang['stADDGROUP']."</a>";
        echo "<br /><br />";
        echo "<table id=report_table_id_group class=datatable>
        <tr>
          <th class=unsortable><b>#</b></th>
          <th><b>".$_lang['stGROUPNAME']."</b></th>
          <th class=unsortable><b>".$_lang['stTYPE']."</b></th>
          <th class=unsortable><b>".$_lang['stCOMMENT']."</b></th>
<th><b>".$_lang['stQUANTITYALIASES']."</b></th>

        </tr>";

        foreach($result as $line) {
          if($line[1]=="1")
            $line[1]="<b><font color=green>".$_lang['stIPADDRESS']."</font></b>";
          else
            $line[1]="<b><font color=red>".$_lang['stLOGIN']."</font></b>";

          echo "
            <tr>
              <td>".$numrow."</td>
              <td align=center><a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=3&groupid=".$line[2].">".$line[0]."&nbsp;</a></td>
              <td align=center>".$line[1]."</td>
              <td align=center>".$line[3]."&nbsp;</td>
              <td align=center>".$line[4]."&nbsp;<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=6&groupid=".$line[2].">".$_lang['stWHO']."</a></td>
           </tr>";
          $numrow++;
        }
        echo "</table>";
        echo "<br />";
        echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=1>".$_lang['stADDGROUP']."</a>";
        echo "<br />";

      
  }


function doPrintFormAddGroup($globalSS){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
  
  $_lang = $globalSS['lang'];


  $queryAllLogins="select id,name from scsq_alias where typeid=0 order by name asc;";
  $queryAllIpaddress="select id,name from scsq_alias where typeid=1 order by name asc;";


  echo "<h2>".$_lang['stFORMADDGROUP']."</h2>";
  echo '
<form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=3&actid=2" method="post">
<table class=datatable>
 <tr><td>'.$_lang['stGROUPNAME'].':</td> <td><input type="text" name="name"></td></tr>
 <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" onClick="switchTables();" name="typeid"></td></tr>
 <tr><td>'.$_lang['stCOMMENT'].':</td> <td> <input type="text" name="comment"></td></tr>
 <tr><td>'.$_lang['stACTIVEAUTH'].':</td> <td> <input type="checkbox" name="activeauth"></td></tr>
 <tr><td>'.$_lang['stUSERLOGIN'].':</td> <td> <input type="text" name="userlogin"></td></tr>
 <tr><td>'.$_lang['stUSERPASSWORD'].':</td> <td> <input type="text" name="userpassword"></td></tr>
</table>
<br />
<br />
    '.$_lang['stVALUE'].':<br />';

    $result=doFetchQuery($globalSS, $queryAllLogins);
    $numrow=1;

  echo "<table id='loginsTable' class=datatable style='display:table;'>";
  echo "<tr>
<th >#</th>
<th>".$_lang['stALIAS']."</th>
<th >".$_lang['stINCLUDE']."</th>
</tr>";

  foreach($result as $line) {
    echo "
      <tr>
        <td >".$numrow."</td>
        <td >".$line[1]."</td>
        <td><input type='checkbox' name='tableidl[]' value='".$line[0]."';</td>
      </tr>";
    $numrow++;
  }
  echo "</table>";

  $result=doFetchQuery($globalSS, $queryAllIpaddress);
  $numrow=1;

  echo "<table id='ipaddressTable' class=datatable style='display:none;'>";
  echo "<tr>
<th class=unsortable>#</th>
<th>".$_lang['stALIAS']."</th>
<th class=unsortable>".$_lang['stINCLUDE']."</th>
</tr>";
  foreach($result as $line) {
    echo "
      <tr>
        <td >".$numrow."</td>
        <td >".$line[1]."</td>
        <td><input type='checkbox' name='tableidip[]' value='".$line[0]."';</td>
      </tr>";
    $numrow++;
  }

echo "</table>";

  echo '
    <input type="submit" value="'.$_lang['stADD'].'"><br />
    </form>
    <br />';

  }

function doGroupAdd($globalSS){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
  
  $_lang = $globalSS['lang'];



  $name=$_POST['name'];

  if(!isset($_POST['typeid']))
    $typeid=0;
  else
    $typeid=1;

  if(!isset($_POST['comment']))
    $comment="";
  else
    $comment=$_POST['comment'];

    if(!isset($_POST['activeauth'])) $activeauth=0; else $activeauth=1;

    $userlogin=$_POST['userlogin'];
    $userpassword=$_POST['userpassword'];

  $sql="INSERT INTO scsq_groups (name, typeid,comment,userlogin, password,active) VALUES ('$name', '$typeid','$comment','$userlogin','$userpassword','$activeauth')";

  if (!doQuery($globalSS, $sql)) {
    die('Error: Can`t insert new group');
  }

  $sql="select id from scsq_groups where name='".$name."';";
  $newid=doFetchOneQuery($globalSS, $sql);
  
  
  $sql="INSERT INTO scsq_aliasingroups (groupid, aliasid) VALUES  ";

  if($typeid==0) {
    foreach($_POST['tableidl'] as $key=>$value)
      $sql = $sql." ('".$newid[0]."','". $value."'),";

    $sql=substr($sql,0,strlen($sql)-1);
    $sql=$sql.";";
  }
  if($typeid==1) {
    foreach($_POST['tableidip'] as $key=>$value)
      $sql = $sql." ('".$newid[0]."','". $value."'),";
    $sql=substr($sql,0,strlen($sql)-1);
    $sql=$sql.";";
  }

  doQuery($globalSS, $sql);

  echo "".$_lang['stGROUPADDED']."<br /><br />";
  echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3 target=right>".$_lang['stBACK']."</a>";



  
}

function doGroupEdit($globalSS){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
  $_lang = $globalSS['lang'];

  $groupid=$_GET['groupid'];

  $queryOneGroup="select name,typeid,id,comment,userlogin,active from scsq_groups where id='".$groupid."';";
  $queryGroupMembers="select aliasid from scsq_aliasingroups where groupid='".$groupid."'";
 
  $queryAllLogins="select id,name from scsq_alias where typeid=0 order by name asc;";
  $queryAllIpaddress="select id,name from scsq_alias where typeid=1 order by name asc;";


  $line=doFetchOneQuery($globalSS,$queryOneGroup);


  if($line[1]==1)  $isChecked="checked";  else  $isChecked="";

  if($line[5]==1) $activeIsChecked="checked"; else $activeIsChecked="";


echo "<h2>".$_lang['stFORMEDITGROUP']."</h2>";
echo '
<form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=3&actid=4&groupid='.$groupid.'" method="post">
<table class=datatable>
 <tr><td>'.$_lang['stGROUPNAME'].':</td> <td><input type="text" name="name" value="'.$line[0].'"></td></tr>
 <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" onClick="switchTables();" name="typeid" '.$isChecked.' ></td></tr>
 <tr><td>'.$_lang['stCOMMENT'].':</td> <td> <input type="text" name="comment" value="'.$line[3].'"></td></tr>
 <tr><td>'.$_lang['stACTIVEAUTH'].':</td> <td> <input type="checkbox" '.$activeIsChecked.' name="activeauth"></td></tr>
 <tr><td>'.$_lang['stUSERLOGIN'].':</td> <td> <input type="text" name="userlogin" value="'.$line[4].'"></td></tr>
 <tr><td>'.$_lang['stCHANGEPASSWORD'].':</td> <td> <input type="checkbox" name="changepassword"></td></tr>
 <tr><td>'.$_lang['stUSERPASSWORD'].':</td> <td> <input type="text" name="userpassword"></td></tr>
</table>
<br />			  
<br />
   '.$_lang['stVALUE'].':<br />';

   $result=doFetchQuery($globalSS, $queryAllLogins);
   $result1=doFetchQuery($globalSS, $queryGroupMembers);

   $numrow=1;

   if($isChecked=="checked")
     echo "<table id='loginsTable' class=datatable style='display:none;'>";
   else
     echo "<table id='loginsTable' class=datatable style='display:table;'>";
   echo "<tr>
<th >#</th>
<th>".$_lang['stALIAS']."</th>
<th >".$_lang['stINCLUDE']."</th>
</tr>";
   $groupmembers=array();
   foreach($result1 as $line)
     $groupmembers[]= $line[0];

     foreach($result as $line) {
     echo "<tr>";
echo "<td >".$numrow."</td>";
echo "<td >".$line[1]."</td>";
     if((in_array($line[0],$groupmembers))&&($isChecked==""))
       echo "<td><input type='checkbox' name='tableidl[]' checked value='".$line[0]."'></td>";
     else
       echo "<td><input type='checkbox' name='tableidl[]' value='".$line[0]."'></td>";
echo "</tr>";
       ;
     $numrow++;
   }
   echo "</table>";

   $result=doFetchQuery($globalSS, $queryAllIpaddress);
   $numrow=1;

   if($isChecked=="checked")
     echo "<table id='ipaddressTable' class=datatable style='display:table;'>";
   else
     echo "<table id='ipaddressTable' class=datatable style='display:none;'>";
   echo "<tr>
<th >#</th>
<th>".$_lang['stALIAS']."</th>
<th >".$_lang['stINCLUDE']."</th>
</tr>";
   foreach($result as $line) {

     echo "<tr>";
echo "<td >".$numrow."</td>";
echo "<td >".$line[1]."</td>";
     if((in_array($line[0],$groupmembers))&&($isChecked=="checked"))
       echo "<td><input type='checkbox' name='tableidip[]' checked value='".$line[0]."'></td>";
     else
       echo "<td><input type='checkbox' name='tableidip[]' value='".$line[0]."'></td>";
     echo "</tr>";

     $numrow++;
   }
   
echo "</table>";

   echo '
     <input type="submit" value="'.$_lang['stSAVE'].'"><br />
     </form>
     <form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=3&actid=5&groupid='.$groupid.'" method="post">
     <input type="submit" value="'.$_lang['stDELETE'].'"><br />
     </form>
     <br />';


}

function doGroupSave($globalSS){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
  $_lang = $globalSS['lang'];

  $groupid = $_GET['groupid'];

  $name=$_POST['name'];

  if(!isset($_POST['typeid'])) $typeid=0;  else  $typeid=1;
  if(!isset($_POST['activeauth'])) $activeauth=0; else $activeauth=1;

  $comment=$_POST['comment'];
  $userlogin=$_POST['userlogin'];
  $userpassword=$_POST['userpassword'];

  if(isset($_POST['changepassword'])) // признак. Если установлено - изменить пароль
  $changepassword=1;
  else
  $changepassword=0;

	if($changepassword==1) 
            $queryUpdateOneGroup="update scsq_groups set name='".$name."',typeid='".$typeid."',comment='".$comment."',userlogin='".$userlogin."',password='".$userpassword."',active='".$activeauth."' where id='".$groupid."'";
	else
            $queryUpdateOneGroup="update scsq_groups set name='".$name."',typeid='".$typeid."',comment='".$comment."',userlogin='".$userlogin."',active='".$activeauth."' where id='".$groupid."'";


  if (!doQuery($globalSS, $queryUpdateOneGroup)) {
    die('Error: Cant update one group');
  }

  $sql="delete from scsq_aliasingroups where groupid='".$groupid."';";

  doQuery($globalSS, $sql) or die();

  $sql="INSERT INTO scsq_aliasingroups (groupid, aliasid) VALUES  ";

  if($typeid==0) {
    foreach($_POST['tableidl'] as $key=>$value)
      $sql = $sql." ('".$groupid."','". $value."'),";

    $sql=substr($sql,0,strlen($sql)-1);
    $sql=$sql.";";
  }

  if($typeid==1) {
    foreach($_POST['tableidip'] as $key=>$value)
      $sql = $sql." ('".$groupid."','". $value."'),";

    $sql=substr($sql,0,strlen($sql)-1);
    $sql=$sql.";";
  }

  doQuery($globalSS, $sql);

  echo "".$_lang['stGROUPUPDATED']."<br /><br />";
  echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3 target=right>".$_lang['stBACK']."</a>";



}

function doGroupDelete($globalSS){

include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
  
$_lang = $globalSS['lang'];

$groupid = $_GET['groupid'];

$queryDeleteOneGroup="delete from scsq_groups where id='".$groupid."'";

if (!doQuery($globalSS, $queryDeleteOneGroup)) {
  die('Error: Cant delete one group');
}
$sql="delete from scsq_aliasingroups where groupid='".$groupid."';";

doQuery($globalSS, $sql) or die();

echo "".$_lang['stGROUPDELETED']."<br /><br />";
echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3 target=right>".$_lang['stBACK']."</a><br />";


}

function doGroupList($globalSS){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
  $_lang = $globalSS['lang'];

  $groupid = $_GET['groupid'];

  $queryOneGroupList="SELECT
                      scsq_groups.name, 
                      scsq_alias.name
                    FROM scsq_aliasingroups 
                    RIGHT JOIN scsq_alias ON scsq_alias.id=scsq_aliasingroups.aliasid
                    RIGHT JOIN scsq_groups ON scsq_groups.id=scsq_aliasingroups.groupid
                    WHERE groupid='".$groupid."'
                    ORDER BY scsq_alias.name asc;";

$result=doFetchQuery($globalSS, $queryOneGroupList);

$numrow=1;

       foreach($result as $line) {
if($numrow==1) {
echo "".$_lang['stGROUPNAME']." : <b>".$line[0]."</b><br /><br />";
           echo "<table id='OneGroupList' class=datatable >";
           echo "<tr>
  <th >#</th>
  <th>".$_lang['stALIAS']."</th>
  </tr>";
}
         echo "<tr>";
echo "<td >".$numrow."</td>";
echo "<td >".$line[1]."</td>";
echo "</tr>";
         $numrow++;
       }
 
       echo "</table>";
 echo "<br />";
       echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3>".$_lang['stBACKTOGROUPLIST']."</a>";
      }

?>