<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> function.misc.php </#FN>                                               
*                         File Birth   > <!#FB> 2021/12/06 23:17:52.156 </#FB>                                         *
*                         File Mod     > <!#FT> 2024/06/25 22:16:38.090 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.9.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






#узнаем установлен ли модуль категорий
function doQueryExistsModuleCategory($globalSS){

include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

$queryExistModuleCategory = "select count(1) from scsq_modules where name = 'Categorylist';";

$result = doFetchOneQuery($globalSS,$queryExistModuleCategory);

if($result[0] == 1)
    return ', category';
else
    return ", '0' as category";

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

    $sitesTmp=implode("','",explode(" ", $globalSS['goodSites']));
    $sitesTmp="'".$sitesTmp."'";

    return $sitesTmp;

}

#составим список  учитываемых сайтов
function doCreateFilterSitesList($globalSS,$whatTable){

  if($globalSS['enableFilterSites'] == "0") return "";

  if($globalSS['filterSites'] == "") return "";


  $sitesTmp=implode("','",explode(" ", $globalSS['filterSites']));
  $sitesTmp="'".$sitesTmp."'";

if($whatTable == "quick")
  $sitesTmp = "AND scsq_quicktraffic.site in (".$sitesTmp.")";
if($whatTable == "traffic")
  $sitesTmp = "AND scsq_traffic.site in (".$sitesTmp.")";


  return $sitesTmp;

}

#составим список  учитываемых сайтов
function doCreateFilterGoodSitesList($globalSS,$whatTable){

  if($globalSS['enableNoSites'] == "0") return "";

  if($globalSS['goodSites'] == "") return "";


  $sitesTmp=implode("','",explode(" ", $globalSS['goodSites']));
  $sitesTmp="'".$sitesTmp."'";

if($whatTable == "quick")
  $sitesTmp = "AND scsq_quicktraffic.site not in (".$sitesTmp.")";
if($whatTable == "traffic")
  $sitesTmp = "AND scsq_traffic.site not in (".$sitesTmp.")";


  return $sitesTmp;

}

#составим фильтр по размеру учитываемого трафика
function doCreateFilterSizeinbytes($globalSS){

  if($globalSS['enableFilterSizeinbytes'] == "0") return "";

  if($globalSS['filterSizeinbytes'] == "") return "";

  $sizeTmp = "AND sizeinbytes ".$globalSS['filterSizeinbytes'];


  return $sizeTmp;

}

#составим список $_GET параметров
function doCreateGetArray($globalSS){

  $i=0;
  $retarray=array();
  $ret="";
  foreach($_GET as $val => $key){
    #дату не будем записывать предварительно
    if($val=="date") continue;
    if($val=="date2") continue;
    if($val=="clearcache") continue;
    if($val=="csv") continue;

    $retarray[$i]=$val."=".$key;
    $i++;
  }
  $ret=implode("&",$retarray);
  return $ret;

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
    
    $isChecked="";
    #если окно модальное
    if (isset($_GET['modal'])) {
      $modal_tableid=$_GET['m_tableid'];
      $modal_typeid=$_GET['m_typeid'];
      $queryOneAlias="select id from scsq_alias where tableid=".$modal_tableid." and typeid=".$modal_typeid.";";

      $line=doFetchOneQuery($globalSS, $queryOneAlias);

      #А что если пытаемся добавить алиас который уже существует? Сразу переходим в редактирование тогда.
      if($line[0]>0) 
      echo "<script language=javascript>window.location.href='".$globalSS['root_http']."right.php?srv=".$globalSS['connectionParams']['srv']."&id=2&actid=3&aliasid=".$line[0]."&modal=1'</script>";

      if($modal_typeid==1) $isChecked="checked";  else  $isChecked="";
    }

    



    $_lang = $globalSS['lang'];

    $goodLoginsList=$globalSS['goodLoginsList'];
    $goodIpaddressList=$globalSS['goodIpaddressList'];


    $queryAllLoginsToAdd="select id,name from scsq_logins  where id NOT IN (".$goodLoginsList.") and id NOT IN (select scsq_alias.tableid from scsq_alias where typeid=0) order by name asc;";

    $queryAllIpaddressToAdd="select id,name from scsq_ipaddress where id NOT IN (".$goodIpaddressList.") and id NOT IN (select scsq_alias.tableid from scsq_alias where typeid=1) order by name asc;";

    $queryAllLogins="select id,name from scsq_logins  where id NOT IN (".$goodLoginsList.") order by name asc;";
    $queryAllIpaddress="select id,name from scsq_ipaddress where id NOT IN (".$goodIpaddressList.") order by name asc;";


        echo "<h2>".$_lang['stFORMADDALIAS']."</h2>";
        
      if (!isset($_GET['modal']))          
        echo '
          <form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=2&actid=2" method="post">';
        else
          echo '
          <form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=2&actid=2&modal=1" method="post">';
          
        echo '  <table class=datatable >
         <tr><td>'.$_lang['stALIASNAME'].':</td> <td><input type="text" name="name"></td></tr>
         <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" '.$isChecked.' onClick="switchTables();" name="typeid"></td></tr>
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


    echo '<input type="text" id="QInput" onkeyup="QuickFinder(\'alias\')" placeholder="'.$_lang['stFASTSEARCH'].'">';

    #Если в модальном окне передали тип, то сразу покажем нужную таблицу иначе - действуем как обычно.
    
    if(isset($modal_typeid))
      if($modal_typeid == 0)
        echo "<table id='' class=\"datatable\" style='display:table;'>";
      else
        echo "<table id='loginsTable' class=\"datatable\" style='display:none;'>";
    else 
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
            <td >".$line[1]."</td>";
          if(isset($modal_tableid))
            if(($modal_tableid==$line[0])&&($ischecked==""))
              echo "<td><input type='radio' name='tableid' checked value='".$line[0]."'></td>";
            else
              echo "<td><input type='radio' name='tableid' value='".$line[0]."'></td>";  
          else
          echo "<td><input type='radio' name='tableid' value='".$line[0]."';</td>";
          
          echo "</tr>";
          $numrow++;
        }
        echo "</table>";
    

        $result=doFetchQuery($globalSS,$queryAllIpaddressToAdd);
        $numrow=1;

    #Если в модальном окне передали тип, то сразу покажем нужную таблицу иначе - действуем как обычно.
    if(isset($modal_typeid))
      if($modal_typeid == 1)
        echo "<table id='ipaddressTable' class=\"datatable\" style='display:block;'>";
      else
        echo "<table id='ipaddressTable' class=\"datatable\" style='display:none;'>";
    else 
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
              <td >".$line[1]."</td>";
          if(isset($modal_tableid))
            if(($modal_tableid==$line[0])&&($isChecked=="checked"))
                echo "<td><input type='radio' name='tableid' checked value='".$line[0]."'></td>";
            else
                echo "<td><input type='radio' name='tableid' value='".$line[0]."'></td>";
     
          else
            echo  "<td><input type='radio' name='tableid' value='".$line[0]."';</td>";
          echo "</tr>";
          $numrow++;
        }

    
              
    echo "</table>";
        echo '
          <input type="submit" value="'.$_lang['stADD'].'"><br />
          </form>
          <br />
        ';


    }

function doAliasAdd($globalSS,$alias_params){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
    $_lang = $globalSS['lang'];

    $name = $alias_params['name'];
    $typeid = $alias_params['typeid'];
    $tableid = $alias_params['tableid'];
    $userlogin = isset($alias_params['userlogin']) ? $alias_params['userlogin'] : '';
    $userpassword = isset($alias_params['userpassword']) ? $alias_params['userpassword'] : '';
    $activeauth = isset($alias_params['activeauth']) ? $alias_params['activeauth'] : '0';

    $sql="INSERT INTO scsq_alias (name, typeid,tableid,userlogin,password,active) VALUES ('$name', '$typeid','$tableid','$userlogin','$userpassword','$activeauth')";

    if (!doQuery($globalSS,$sql)) {
      return('Error: Can`t insert alias into table!');
    }

      #если передаем спец сигнал external, то значит действуем из какого-нибудь модуля.
if (!isset($alias_params['external'])) {


    echo "".$_lang['stALIASADDED']."<br /><br />";
  if(!isset($_GET['modal']))
    echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2 target=right>".$_lang['stBACK']."</a>";          
  else
    echo "Please, close this window";  
  
}
 
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
      if(!isset($_GET['modal']))
        echo '
            <form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=2&actid=4&aliasid='.$aliasid.'" method="post">';
      else
       echo '<form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=2&actid=4&aliasid='.$aliasid.'&modal=1" method="post">';

      echo '       <table class=datatable>
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
          echo '<input type="text" id="QInput" onkeyup="QuickFinder(\'alias\')" placeholder="'.$_lang['stFASTSEARCH'].'">';

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
            if(($tableid==$line[0])&&($isChecked==""))
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

  function doAliasSave($globalSS,$alias_params){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
    $_lang = $globalSS['lang'];
  
    #Так как мы не всегда сможем все параметры алиаса передать, а чаще будем передавать только изменяемые параметры, то чтобы не переживать - старые параметры будем оставлять на месте.

    $queryOneAlias="select name,typeid,tableid,id,userlogin,password,active from scsq_alias where id='".$alias_params['aliasid']."';";

    $line=doFetchOneQuery($globalSS, $queryOneAlias);

    $aliasid = $alias_params['aliasid'];
  
    $name = isset($alias_params['name']) ? $alias_params['name'] : $line[0];
    $typeid = isset($alias_params['typeid']) ? $alias_params['typeid'] : $line[1];
    $tableid = isset($alias_params['tableid']) ? $alias_params['tableid'] : $line[2];
    $userlogin = isset($alias_params['userlogin']) ? $alias_params['userlogin'] : $line[4];
    $userpassword = isset($alias_params['userpassword']) ? $alias_params['userpassword'] : $line[5];
    $activeauth = isset($alias_params['activeauth']) ? $alias_params['activeauth'] : $line[6];
    $changepassword = $alias_params['changepassword'];


    if($changepassword==1)        
    $queryUpdateOneAlias="update scsq_alias set name='".$name."',typeid='".$typeid."',tableid='".$tableid."',userlogin='".$userlogin."',password='".$userpassword."',active='".$activeauth."' where id='".$aliasid."'";
    else
    $queryUpdateOneAlias="update scsq_alias set name='".$name."',typeid='".$typeid."',tableid='".$tableid."',userlogin='".$userlogin."',active='".$activeauth."' where id='".$aliasid."'";
    

    if (!doQuery($globalSS, $queryUpdateOneAlias)) {
    return 'Error: Can`t update 1 alias';
  }
  #если передаем спец сигнал external, то значит действуем из какого-нибудь модуля.
if (!isset($alias_params['external'])) {


  echo "".$_lang['stALIASUPDATED']."<br /><br />";

  if(!isset($_GET['modal']))
  echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2 target=right>".$_lang['stBACK']."</a>";
else
  echo "Please, close this window"; 
}

}

function doAliasDelete($globalSS,$aliasid){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
  $_lang = $globalSS['lang'];


  

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
          <th class=unsortable><b>".$_lang['stCOMMENT']."</b></th>
          <th><b>".$_lang['stQUANTITYALIASES']."</b></th>

        </tr>";

        foreach($result as $line) {
 
          echo "
            <tr>
              <td>".$numrow."</td>
              <td align=center><a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=3&groupid=".$line[2].">".$line[0]."&nbsp;</a></td>
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

#Функция получения значения параметра из scsq_modules_param
function doGetParam($globalSS,$module,$param){

        include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
        $queryGetParam="select val from scsq_modules_param  where module='".$module."' and param='".$param."';";
        $row=doFetchOneQuery($globalSS,$queryGetParam);

        return $row[0];
        }

#Функция установки значения параметра в scsq_modules_param
function doSetParam($globalSS,$module,$param,$val){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

  $querySetParam="update scsq_modules_param set val='".$val."' where module='".$module."' and param='".$param."';";

  doQuery($globalSS,$querySetParam);

  }

#Функция чтения глобальных значенией параметров в scsq_modules_param
function doReadGlobalParamsTable($globalSS){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

  $queryReadParams="select param,val,switch from scsq_modules_param where module='Global';";

  $result=doFetchQuery($globalSS,$queryReadParams);
 
  foreach($result as $line ){
   
    #if param is switch, then convert On to 1, '' to  0
    if($line[2] == '1')
      $globalSS[$line[0]] = ($line[1] == 'on' ? '1' : '0');
    else
      $globalSS[$line[0]] = $line[1];
  }
    return $globalSS;
  }

  function doReadGlobalParamsTableToConfig($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
  
    $queryReadParams="select param,val,switch,comment from scsq_modules_param where module='Global' order by id asc;";
  
    $result=doFetchQuery($globalSS,$queryReadParams);
   
    foreach($result as $line ){
     
      #if param is switch, then convert On to 1, '' to  0
      if($line[2] == '1')
        $globalSS['gParams'][$line[0]]['value'] = ($line[1] == 'on' ? 'on' : 'off');
      else
        $globalSS['gParams'][$line[0]]['value'] = $line[1];

        $globalSS['gParams'][$line[0]]['comment'] = $line[3];
      }
      return $globalSS;
    }



function doWriteToLogTable($globalSS, $params){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
 
  $sqlquery="insert into scsq_logtable (datestart,dateend,message) VALUES ('".$params['datestart']."','".$params['dateend']."','".$params['message']."');";

  doQuery($globalSS,$sqlquery);

      
  }


  function GetIdByIpaddress($globalSS, $ipaddress) #по ip получаем  ID
  {
    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

	$sqlGetId = "
		SELECT id FROM scsq_ipaddress t where t.name = '$ipaddress';";


		$result=doFetchOneQuery($globalSS, $sqlGetId);

return isset($result[0]) ? $result[0] : "" ;


}

function GetIdByLogin($globalSS,$login) #по логину получаем  ID
{

$sqlGetId = "
  SELECT id FROM scsq_logins t where t.name = '$login';";


  $result=doFetchOneQuery($globalSS, $sqlGetId);

return isset($result[0]) ? $result[0] : "" ;


}

function GetAliasIdByIpaddressId($globalSS,$ipaddress_id) #по ip_id получаем  Alias ID
{

  $sqlGetId = "
	  SELECT id FROM scsq_alias t where t.tableid = '$ipaddress_id' and typeid=1;";


	  $result=doFetchOneQuery($globalSS, $sqlGetId);

	  return isset($result[0]) ? $result[0] : "" ;


}

function GetAliasIdByLoginId($globalSS,$login_id) #по login_id получаем  Alias ID
{

  $sqlGetId = "
	  SELECT id FROM scsq_alias t where t.tableid = '$login_id' and typeid=0;";


	  $result=doFetchOneQuery($globalSS, $sqlGetId);

	  return isset($result[0]) ? $result[0] : "" ;


}


function GetDirectorySize($path){
  $bytestotal = 0;
  $path = realpath($path);
  if($path!==false && $path!='' && file_exists($path)){
      foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
          $bytestotal += $object->getSize();
      }
  }
  return $bytestotal;
}

function DeleteAllFilesFromDirectory($path){
 
  $path = realpath($path);
  $files = glob($path.'/*'); // get all file names
  foreach($files as $file){ // iterate files
    if(is_file($file)) {
      unlink($file); // delete file
    }
  }
}



?>