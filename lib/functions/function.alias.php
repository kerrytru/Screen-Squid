<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> function.alias.php </#FN>                                              
*                         File Birth   > <!#FB> 2024/10/10 21:47:48.413 </#FB>                                         *
*                         File Mod     > <!#FT> 2024/10/10 21:57:34.513 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






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
        '',
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

   

       #соберем все параметры строки
       $dfltAction=doCreateGetArray($globalSS);

    if(isset($_GET['csv']) and $_GET['csv']==1)
    {
      #этот запрос меньше данных содержит для CSV. Зачем коды пользователю
      $queryAllAliases="
      SELECT 
      '',
        tmp.alname,
        case when tmp.altypeid = 0 then 'Login' else 'Ipaddress' end,
        tmp.tablename
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

      doMakeCSV2($globalSS,json_encode($result));   

    }
 

    else {
      $result = doFetchQuery($globalSS,$queryAllAliases);

    $numrow=1;
    echo 	"<h2>".$_lang['stALIASES'].":</h2>";
    echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2&actid=1>".$_lang['stADDALIAS']."</a>";
    echo '<br><br><input type="text" id="QInput" onkeyup="QuickFinder(\'dtg\')" placeholder="'.$_lang['stFASTSEARCH'].'">';
    echo '<button id="ClearFilterBtn" type="button" onclick="ClearFilter(\'dtg\');">'.$_lang['stCLEARFILTER'].'</button>';

    echo "<br><br> <a href=?".$dfltAction."&csv=1><img src='img/csvicon.png' width=32 height=32 alt=Image title='Generate CSV'></a>";


    echo "<br /><br />";

    echo "<table id=\"dtg\" class=\"datatable\">
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
     if($line[2]=="1")
       $line[2]="<b><font color=green>".$_lang['stIPADDRESS']."</font></b>";
     else
       $line[2]="<b><font color=red>".$_lang['stLOGIN']."</font></b>";
     echo "
     <tr>
       <td>".$numrow."</td>

       <td align=center><a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2&actid=3&aliasid=".$line[4].">".$line[1]."&nbsp;</a></td>
       <td align=center>".$line[2]."</td>
       <td>".$line[5]."&nbsp;</td>
       <td>";
 $i=0;
 $splitgroupsname=explode(',',$line[6]);
 $splitgroupsid=explode(',',$line[7]);
 foreach($splitgroupsname as $val) {
   echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=3&actid=3&groupid=".$splitgroupsid[$i].">".$val."</a>&nbsp";
   $i++;

}

 echo "</td>
   <td align=center><a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2&actid=5&aliasid=".$line[4].">".$_lang['stDELETE']."</a></td>

     </tr>
     ";
     $numrow++;
    }  //end while
        
echo "</table>";
   echo "<br />";
   echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2&actid=1>".$_lang['stADDALIAS']."</a>";
   echo "<br />";
   
  }  // if(isset($_GET['csv']) and $_GET['csv']==1)

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

function doAliasAdd($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
    $_lang = $globalSS['lang'];

     
 
          if(!isset($_POST['typeid'])) $typeid=0;  else  $typeid=1;
          if(!isset($_POST['activeauth'])) $activeauth=0; else $activeauth=1;
      
          #если не выбран ни один логин или IP адрес, вернём ошибку. 
          #По хорошему, нужно напиать валидатор формы, чтобы JS не давал пройти дальше.
         # echo "tableid=".$_POST['tableid'];
          if($_POST['tableid']=="") die ('Error: No login or ipaddress choosed! Alias cant be added');
      
          $tableid=$_POST['tableid'];
          $userlogin=$_POST['userlogin'];
          $alias_params['userpassword']=md5(md5(trim($_POST['userpassword'])));


    $name = $_POST['name'];
    $userlogin = isset($_POST['userlogin']) ? $_POST['userlogin'] : '';
    $userpassword = isset($_POST['userpassword']) ? md5(md5(trim($_POST['userpassword']))) : '';
   

    $sql="INSERT INTO scsq_alias (name, typeid,tableid,userlogin,password,active) VALUES ('$name', '$typeid','$tableid','$userlogin','$userpassword','$activeauth')";

    if (!doQuery($globalSS,$sql)) {
      return('Error: Can`t insert alias into table!');
    }

      #если передаем спец сигнал external, то значит действуем из какого-нибудь модуля.
if (!isset($globalSS['externalAlias'])) {


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

  function doAliasSave($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
    $_lang = $globalSS['lang'];
  
  

    $aliasid = $_GET['aliasid'];
  
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $typeid = isset($_POST['typeid']) ? $_POST['typeid'] : 0;
    $tableid = isset($_POST['tableid']) ? $_POST['tableid'] : 0;
    $userlogin = isset($_POST['userlogin']) ? $_POST['userlogin'] : "";
    $userpassword = isset($_POST['userpassword']) ? md5(md5($_POST['userpassword'])) : "";
    $activeauth = isset($_POST['activeauth']) ? 1 : 0;
    $changepassword =isset($_POST['changepassword']) ? 1 : 0; 


    if($changepassword==1)        
    $queryUpdateOneAlias="update scsq_alias set name='".$name."',typeid='".$typeid."',tableid='".$tableid."',userlogin='".$userlogin."',password='".$userpassword."',active='".$activeauth."' where id='".$aliasid."'";
    else
    $queryUpdateOneAlias="update scsq_alias set name='".$name."',typeid='".$typeid."',tableid='".$tableid."',userlogin='".$userlogin."',active='".$activeauth."' where id='".$aliasid."'";
    

    if (!doQuery($globalSS, $queryUpdateOneAlias)) {
    return 'Error: Can`t update 1 alias';
  }
  #если передаем спец сигнал external, то значит действуем из какого-нибудь модуля.
if (!isset($globalSS['externalAlias'])) {


  echo "".$_lang['stALIASUPDATED']."<br /><br />";

  if(!isset($_GET['modal']))
  echo "<a href=right.php?srv=".$globalSS['connectionParams']['srv']."&id=2 target=right>".$_lang['stBACK']."</a>";
else
  echo "Please, close this window"; 
}

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





?>