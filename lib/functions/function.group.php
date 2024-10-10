<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> function.group.php </#FN>                                              
*                         File Birth   > <!#FB> 2024/10/10 21:51:26.179 </#FB>                                         *
*                         File Mod     > <!#FT> 2024/10/10 21:58:29.398 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/





#Функции работы с группами
function doPrintAllGroups($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
    $_lang = $globalSS['lang'];
  
    $queryAllGroups="SELECT 
    '',
    scsq_groups.name,
    scsq_groups.typeid,
    scsq_groups.id,
    scsq_groups.comment,
    count(scsq_aliasingroups.aliasid)
       FROM scsq_aliasingroups 
       RIGHT OUTER JOIN scsq_groups ON scsq_groups.id=scsq_aliasingroups.groupid
       GROUP BY scsq_aliasingroups.groupid, scsq_groups.name, scsq_groups.id, scsq_groups.typeid, scsq_groups.comment
       ORDER BY name asc;";
  
     

       #соберем все параметры строки
       $dfltAction=doCreateGetArray($globalSS);

    if(isset($_GET['csv']) and $_GET['csv']==1)
{
    $queryAllGroups="SELECT 
    '',
    scsq_groups.name,
    scsq_groups.comment,
    count(scsq_aliasingroups.aliasid)
       FROM scsq_aliasingroups 
       RIGHT OUTER JOIN scsq_groups ON scsq_groups.id=scsq_aliasingroups.groupid
       GROUP BY scsq_aliasingroups.groupid, scsq_groups.name, scsq_groups.id, scsq_groups.typeid, scsq_groups.comment
       ORDER BY name asc;";

        $result=doFetchQuery($globalSS, $queryAllGroups);
    doMakeCSV2($globalSS,json_encode($result));   
}

    else {

        $result=doFetchQuery($globalSS, $queryAllGroups);
          $numrow=1;
          echo 	"<h2>".$_lang['stGROUPS'].":</h2>";
          echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=1>".$_lang['stADDGROUP']."</a>";
          echo '<br><br><input type="text" id="QInput" onkeyup="QuickFinder(\'dtg\')" placeholder="'.$_lang['stFASTSEARCH'].'">';
          echo '<button id="ClearFilterBtn" type="button" onclick="ClearFilter(\'dtg\');">'.$_lang['stCLEARFILTER'].'</button>';
      
          echo "<br><br> <a href=?".$dfltAction."&csv=1><img src='img/csvicon.png' width=32 height=32 alt=Image title='Generate CSV'></a>";
      
          echo "<br /><br />";
          echo "<table id=\"dtg\" class=datatable>
          <tr>
            <th class=unsortable><b>#</b></th>
            <th><b>".$_lang['stGROUPNAME']."</b></th>
            <th class=unsortable><b>".$_lang['stCOMMENT']."</b></th>
            <th><b>".$_lang['stQUANTITYALIASES']."</b></th>
  
          </tr>";
  
          foreach($result as $line) {
   
            echo "
              <tr>
                <td>".$numrow."</td>
                <td align=center><a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=3&groupid=".$line[3].">".$line[1]."&nbsp;</a></td>
                <td align=center>".$line[4]."&nbsp;</td>
                <td align=center>".$line[5]."&nbsp;<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=6&groupid=".$line[4].">".$_lang['stWHO']."</a></td>
             </tr>";
            $numrow++;
          }
          echo "</table>";
          echo "<br />";
          echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=1>".$_lang['stADDGROUP']."</a>";
          echo "<br />";
       
        } //if(isset($_GET['csv']) and $_GET['csv']==1)
        
    }
  
  
  function doPrintFormAddGroup($globalSS){
  
    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
    $_lang = $globalSS['lang'];
  
  
    $queryAllAlias="select id,name from scsq_alias order by name asc;";
  
  
    echo "<h2>".$_lang['stFORMADDGROUP']."</h2>";
    echo '
  <form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=3&actid=2" method="post">
  <table class=datatable>
   <tr><td>'.$_lang['stGROUPNAME'].':</td> <td><input type="text" name="name"></td></tr>
   <tr><td>'.$_lang['stCOMMENT'].':</td> <td> <input type="text" name="comment"></td></tr>
   <tr><td>'.$_lang['stACTIVEAUTH'].':</td> <td> <input type="checkbox" name="activeauth"></td></tr>
   <tr><td>'.$_lang['stUSERLOGIN'].':</td> <td> <input type="text" name="userlogin"></td></tr>
   <tr><td>'.$_lang['stUSERPASSWORD'].':</td> <td> <input type="text" name="userpassword"></td></tr>
  </table>
  <br />
  <br />
      '.$_lang['stVALUE'].':<br />';
  
      $result=doFetchQuery($globalSS, $queryAllAlias);
      $numrow=1;
      echo '<input type="text" id="QInput" onkeyup="QuickFinder(\'group\')" placeholder="'.$_lang['stFASTSEARCH'].'">';
  
    echo "<table id='aliasTable' class=datatable>";
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
          <td><input type='checkbox' name='aliasid[]' value='".$line[0]."';</td>
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
  
    if(!isset($_POST['comment']))
      $comment="";
    else
      $comment=$_POST['comment'];
  
      if(!isset($_POST['activeauth'])) $activeauth=0; else $activeauth=1;
  
      $userlogin=$_POST['userlogin'];
      $userpassword=md5(md5(trim($_POST['userpassword'])));
  
    $sql="INSERT INTO scsq_groups (name, typeid, comment,userlogin, password,active) VALUES ('$name', '0', '$comment','$userlogin','$userpassword','$activeauth')";
  
    
    if (!doQuery($globalSS, $sql)) {
      die('Error: Can`t insert new group');
    }
  
    $sql="select id from scsq_groups where name='".$name."';";
    $newid=doFetchOneQuery($globalSS, $sql);
    
    
    $sql="INSERT INTO scsq_aliasingroups (groupid, aliasid) VALUES  ";
  
      foreach($_POST['aliasid'] as $key=>$value)
        $sql = $sql." ('".$newid[0]."','". $value."'),";
  
      $sql=substr($sql,0,strlen($sql)-1);
      $sql=$sql.";";
   
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
   
    $queryAllAlias="select id,name from scsq_alias  order by name asc;";
  
  
    $line=doFetchOneQuery($globalSS,$queryOneGroup);
  
  
    if($line[1]==1)  $isChecked="checked";  else  $isChecked="";
  
    if($line[5]==1) $activeIsChecked="checked"; else $activeIsChecked="";
  
  
  echo "<h2>".$_lang['stFORMEDITGROUP']."</h2>";
  echo '
  <form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=3&actid=4&groupid='.$groupid.'" method="post">
  <table class=datatable>
   <tr><td>'.$_lang['stGROUPNAME'].':</td> <td><input type="text" name="name" value="'.$line[0].'"></td></tr>
   <tr><td>'.$_lang['stCOMMENT'].':</td> <td> <input type="text" name="comment" value="'.$line[3].'"></td></tr>
   <tr><td>'.$_lang['stACTIVEAUTH'].':</td> <td> <input type="checkbox" '.$activeIsChecked.' name="activeauth"></td></tr>
   <tr><td>'.$_lang['stUSERLOGIN'].':</td> <td> <input type="text" name="userlogin" value="'.$line[4].'"></td></tr>
   <tr><td>'.$_lang['stCHANGEPASSWORD'].':</td> <td> <input type="checkbox" name="changepassword"></td></tr>
   <tr><td>'.$_lang['stUSERPASSWORD'].':</td> <td> <input type="text" name="userpassword"></td></tr>
  </table>
  <br />			  
  <br />
     '.$_lang['stVALUE'].':<br />';
  
     $result=doFetchQuery($globalSS, $queryAllAlias);
     $result1=doFetchQuery($globalSS, $queryGroupMembers);
  
     $numrow=1;
     echo '<input type="text" id="QInput" onkeyup="QuickFinder(\'group\')" placeholder="'.$_lang['stFASTSEARCH'].'">';
  
   
       echo "<table id='aliasTable' class=datatable style='display:table;'>";
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
         echo "<td><input type='checkbox' name='aliasid[]' checked value='".$line[0]."'></td>";
       else
         echo "<td><input type='checkbox' name='aliasid[]' value='".$line[0]."'></td>";
  echo "</tr>";
         ;
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
  
    if(!isset($_POST['activeauth'])) $activeauth=0; else $activeauth=1;
  
    $comment=$_POST['comment'];
    $userlogin=$_POST['userlogin'];
    $userpassword=md5(md5(trim($_POST['userpassword'])));
  
    if(isset($_POST['changepassword'])) // признак. Если установлено - изменить пароль
    $changepassword=1;
    else
    $changepassword=0;
  
      if($changepassword==1) 
              $queryUpdateOneGroup="update scsq_groups set name='".$name."',typeid='0',comment='".$comment."',userlogin='".$userlogin."',password='".$userpassword."',active='".$activeauth."' where id='".$groupid."'";
      else
              $queryUpdateOneGroup="update scsq_groups set name='".$name."',typeid='0',comment='".$comment."',userlogin='".$userlogin."',active='".$activeauth."' where id='".$groupid."'";
  
  
    if (!doQuery($globalSS, $queryUpdateOneGroup)) {
      die('Error: Cant update one group');
    }
  
    $sql="delete from scsq_aliasingroups where groupid='".$groupid."';";
  
    doQuery($globalSS, $sql) or die();
  
    $sql="INSERT INTO scsq_aliasingroups (groupid, aliasid) VALUES  ";
  
      foreach($_POST['aliasid'] as $key=>$value)
        $sql = $sql." ('".$groupid."','". $value."'),";
  
      $sql=substr($sql,0,strlen($sql)-1);
      $sql=$sql.";";
    
  
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