<?php
#build 20160313

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
<link rel="stylesheet" type="text/css" href="javascript/example.css"/>
</head>
<body>
<br />


<br />
<script type="text/javascript" src="javascript/sortable.js"></script>
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

include("config.php");



$address=$address[$srv];
$user=$user[$srv];
$pass=$pass[$srv];
$db=$db[$srv];

if(!isset($_GET['id']))
echo "<h2>".$_lang['stWELCOME']."".$vers."</h2>";

$start=microtime(true);

$connectionStatus=mysql_connect("$address","$user","$pass") or die("error");
mysql_select_db($db);


if($enableNofriends==1) {
  $friendsTmp=implode("','",explode(" ", $goodLogins));
  $friendsTmp=",'".$friendsTmp."'";
  $goodLoginsList=$friendsTmp;
  $friendsTmp=implode("','",explode(" ", $goodIpaddress));
  $friendsTmp=",'".$friendsTmp."'";
  $goodIpaddressList=$friendsTmp;
  }
    else {
      $goodLoginsList="";
      $goodIpaddressList="";
      }


if($_GET['id']==1) {
  $queryCountRowsTraffic="select count(*) from scsq_traffic";
  $queryCountRowsLogin="select count(*) from scsq_logins";
  $queryCountRowsIpaddress="select count(*) from scsq_ipaddress";
  $queryMinMaxDateTraffic="select min(date),max(date) from scsq_quicktraffic";
  $querySumSizeTraffic="select sum(sizeinbytes) from scsq_quicktraffic group by crc32('1') order by null";
  $queryCountObjectsTraffic1="select count(id) from scsq_traffic where sizeinbytes<=1000";
  $queryCountObjectsTraffic2="select count(id) from scsq_traffic where sizeinbytes>1000 and sizeinbytes<=5000";
  $queryCountObjectsTraffic3="select count(id) from scsq_traffic where sizeinbytes>5000 and sizeinbytes<=10000";
  $queryCountObjectsTraffic4="select count(id) from scsq_traffic where sizeinbytes>10000";

  $result=mysql_query($queryCountRowsTraffic);
  $CountRowsTraffic=mysql_fetch_array($result,MYSQL_NUM);
  $result=mysql_query($queryCountRowsLogin);
  $CountRowsLogin=mysql_fetch_array($result,MYSQL_NUM);
  $result=mysql_query($queryCountRowsIpaddress);
  $CountRowsIpaddress=mysql_fetch_array($result,MYSQL_NUM);
  $result=mysql_query($queryMinMaxDateTraffic);
  $MinMaxDateTraffic=mysql_fetch_array($result,MYSQL_NUM);
  $result=mysql_query($querySumSizeTraffic);
  $SumSizeTraffic=mysql_fetch_array($result,MYSQL_NUM);

	if($enableTrafficObjectsInStat==1)
	{
	  $result=mysql_query($queryCountObjectsTraffic1);
	  $CountObjects1=mysql_fetch_array($result,MYSQL_NUM);
	  $result=mysql_query($queryCountObjectsTraffic2);
	  $CountObjects2=mysql_fetch_array($result,MYSQL_NUM);
	  $result=mysql_query($queryCountObjectsTraffic3);
	  $CountObjects3=mysql_fetch_array($result,MYSQL_NUM);
	  $result=mysql_query($queryCountObjectsTraffic4);
	  $CountObjects4=mysql_fetch_array($result,MYSQL_NUM);
	}

  }


  if($connectionStatus=="error") {
    echo $_lang['stDONTFORGET'];
  }
  else
  {

    if($_GET['id']==1) {
      echo "
      <h3>".$_lang['stSTATS'].":</h3>
      <table border=1>
      <tr>
         <td>".$_lang['stQUANTITYRECORDS']."</td>
         <td>".$CountRowsTraffic[0]."</td>
      </tr>
      <tr>
         <td>".$_lang['stQUANTITYLOGINS']."</td>
         <td>".$CountRowsLogin[0]."</td>
      </tr>
      <tr>
         <td>".$_lang['stQUANTITYIPADDRESSES']."</td>
         <td>".$CountRowsIpaddress[0]."</td>
      </tr>
      <tr>
         <td>".$_lang['stMINDATE']."</td>
         <td>".(date('d-m-Y h:m:s',$MinMaxDateTraffic[0]))."</td>
      </tr>
      <tr>
         <td>".$_lang['stMAXDATE']."</td>
         <td>".(date('d-m-Y h:m:s',$MinMaxDateTraffic[1]))."</td>
      </tr>
      <tr>
         <td>".$_lang['stTRAFFICSUM']."</td>
         <td>".($SumSizeTraffic[0]/1000000)."</td>
      </tr>
";

	if($enableTrafficObjectsInStat==1)
	{
	echo "
	      <tr>
	         <td colspan=2 align=center><b>".$_lang['stTRAFFICOBJECTS']."</b></td>
	      </tr>
	      <tr>
	         <td> < 1 kB</td>
	         <td>".$CountObjects1[0]."</td>
	      </tr>
	      <tr>
	         <td> > 1 kB & < 5 kB </td>
	         <td>".$CountObjects2[0]."</td>
	      </tr>
	      <tr>
	         <td> > 5 kB & < 10 kB </td>
	         <td>".$CountObjects3[0]."</td>
	      </tr>
	      <tr>
	         <td> > 10 kB </td>
	         <td>".$CountObjects4[0]."</td>
	      </tr>
      ";
	}

echo "</table>";

    }  //end GET[id]=1
    else

      if($_GET['id']==2) {

        if(isset($_GET['actid'])) //action ID.
          $actid=$_GET['actid'];
        else
          $actid=0;


        if(isset($_GET['aliasid'])) //alias ID из таблицы scsq_alias для редактирования/удаления alias.
          $aliasid=$_GET['aliasid'];
        else
          $aliasid=0;

        if(isset($_POST['name'])) // имя
          $name=$_POST['name'];
        else
          $name=0;

        if(isset($_POST['typeid'])) // login или ipaddress 0 - логин, 1 - ipadddress 
          $typeid=1;
        else
          $typeid=0;

        if(isset($_POST['tableid'])) // id логина или ipaddress
          $tableid=$_POST['tableid'];
        else
          $tableid=$_POST['tableid'];

///SQL querys

        $queryAllLoginsToAdd="select id,name from scsq_logins  where name NOT IN (''".$goodLoginsList.") and id NOT IN (select scsq_alias.tableid from scsq_alias where typeid=0) order by name asc;";

        $queryAllIpaddressToAdd="select id,name from scsq_ipaddress where name NOT IN (''".$goodIpaddressList.") and id NOT IN (select scsq_alias.tableid from scsq_alias where typeid=1) order by name asc;";


        $queryAllLogins="select id,name from scsq_logins  where name NOT IN (''".$goodLoginsList.") order by name asc;";
        $queryAllIpaddress="select id,name from scsq_ipaddress where name NOT IN (''".$goodIpaddressList.") order by name asc;";
        $queryAllAliases="
	  SELECT 
	     alname,
	     altypeid,
	     altableid,
	     alid,
	     tablename,
	     group_concat(scsq_groups.name order by scsq_groups.name asc) as gconcat,
	     group_concat(scsq_groups.id order by scsq_groups.name asc)
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
	  GROUP BY altableid
	  ORDER BY alname asc";

        $queryOneAlias="select name,typeid,tableid,id from scsq_alias where id='".$aliasid."';";
        $queryUpdateOneAlias="update scsq_alias set name='".$name."',typeid='".$typeid."',tableid='".$tableid."' where id='".$aliasid."'";
        $queryDeleteOneAlias="delete from scsq_alias where id='".$aliasid."'";
        $queryDeleteOneAliasFromGroup="delete from scsq_aliasingroups where aliasid='".$aliasid."'";

///SQL querys end

//mysql_connect($address,$user,$pass);
//mysql_select_db($db);

///надо добавить обработку ошибки подключения к БД

        if(!isset($_GET['actid'])) {
          $result=mysql_query($queryAllAliases);
          $numrow=1;
	 
          echo "<a href=right.php?srv=".$srv."&id=2&actid=1>".$_lang['stADDALIAS']."</a>";
          echo "<br /><br />";

          echo "<table id=report_table_id_alias border=1 class=sortable>
       		   <tr>
       		     <th class=unsortable><b>#</b></th>
      		      <th><b>".$_lang['stALIASNAME']."&nbsp;</b></th>
        		    <th class=unsortable><b>".$_lang['stTYPE']."</b></th>
         	      <th><b>".$_lang['stVALUE']."</b></th>
         	      <th><b>".$_lang['stGROUP']."</b></th>
         	  </tr>
          ";

         while($line = mysql_fetch_row($result)) {
           if($line[1]=="1")
             $line[1]="<b><font color=green>".$_lang['stIPADDRESS']."</font></b>";
           else
             $line[1]="<b><font color=red>".$_lang['stLOGIN']."</font></b>";

           echo "
           <tr>
             <td>".$numrow."</td>
             <td align=center><a href=right.php?srv=".$srv."&id=2&actid=3&aliasid=".$line[3].">".$line[0]."&nbsp;</a></td>
             <td align=center>".$line[1]."</td>
             <td>".$line[4]."&nbsp;</td>
             <td>";
	   $i=0;
	   $splitgroupsname=split(',',$line[5]);
	   $splitgroupsid=split(',',$line[6]);
	   foreach($splitgroupsname as $val) {
	     echo "<a href=right.php?srv=".$srv."&id=3&actid=3&groupid=".$splitgroupsid[$i].">".$val."</a>&nbsp";
	     $i++;
	   }

	   echo "</td>
           </tr>
           ";
           $numrow++;
          }  //end while
         echo "</table>";
         echo "<br />";
         echo "<a href=right.php?srv=".$srv."&id=2&actid=1>".$_lang['stADDALIAS']."</a>";
         echo "<br />";
        } // end if(!isset...
    

        if($actid==1) {

          echo $_lang['stFORMADDALIAS'];
          echo '
            <form action="right.php?srv='.$srv.'&id=2&actid=2" method="post">
           '.$_lang['stALIASNAME'].': <input type="text" name="name"><br />
           '.$_lang['stTYPECHECK'].': <input type="checkbox" onClick="switchTables();" name="typeid"><br />
           '.$_lang['stVALUE'].':<br /> 
           ';

          $result=mysql_query($queryAllLoginsToAdd);
          $numrow=1;

          echo "<table id='loginsTable' class=sortable style='display:table;'>";
	  echo "<tr>";
	  echo "    <th class=unsortable>#</th>
		    <th>".$_lang['stLOGIN']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		</tr>";
          while($line = mysql_fetch_row($result)) {
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

          $result=mysql_query($queryAllIpaddressToAdd);
          $numrow=1;

          echo "<table id='ipaddressTable' class=sortable style='display:none;'>";
	  echo "<tr>";
	  echo "    <th class=unsortable>#</th>
		    <th>".$_lang['stIPADDRESS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		</tr>";

          while($line = mysql_fetch_row($result)) {
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
        }  //end if($actid==1..

        if($actid==2) {  //добавление алиаса
          $name=$_POST['name'];

          if(!isset($_POST['typeid']))
            $typeid=0;
          else
            $typeid=1;

          $tableid=$_POST['tableid'];

          $sql="INSERT INTO scsq_alias (name, typeid,tableid) VALUES ('$name', '$typeid','$tableid')";

          if (!mysql_query($sql)) {
            die('Error: ' . mysql_error());
          }
          echo "".$_lang['stALIASADDED']."<br /><br />";
          echo "<a href=right.php?srv=".$srv."&id=2 target=right>".$_lang['stBACK']."</a>";
        }

        if($actid==3) { ///Редактирование алиаса
          $result=mysql_query($queryOneAlias);
          $line=mysql_fetch_row($result);

          if($line[1]==1)
            $isChecked="checked";
          else
            $isChecked="";

          $tableid=$line[2];
          $aliasid=$line[3];
          echo '
            <form action="right.php?srv='.$srv.'&id=2&actid=4&aliasid='.$aliasid.'" method="post">
            '.$_lang['stALIASNAME'].': <input type="text" name="name" value="'.$line[0].'"><br />
            '.$_lang['stVALUE'].':<br /> 
          ';
#Удалено 20150903     '.$_lang['stTYPECHECK'].': <input type="checkbox" onClick="switchTables();" name="typeid" '.$isChecked.' ><br />

          $result=mysql_query($queryAllLogins);
          $numrow=1;

          if($isChecked=="checked")
            echo "<table id='loginsTable' class=sortable style='display:none;'>";
          else
            echo "<table id='loginsTable' class=sortable style='display:table;'>";
	  echo "<tr>";
	  echo "    <th class=unsortable>#</th>
		    <th>".$_lang['stLOGIN']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
	        </tr>";

          while($line = mysql_fetch_row($result)) {
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
        
          $result=mysql_query($queryAllIpaddress);
          $numrow=1;

          if($isChecked=="checked")
            echo "<table id='ipaddressTable' class=sortable style='display:table;'>";
          else
            echo "<table id='ipaddressTable' class=sortable style='display:none;'>";
	  echo "<tr>";
	  echo "    <th class=unsortable>#</th>
		    <th>".$_lang['stIPADDRESS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
	        </tr>";
          while($line = mysql_fetch_row($result)) {
            echo "<tr>";
	    echo "<td >".$numrow."</td>";
	    echo "<td >".$line[1]."</td>";
            if(($tableid==$line[0])&&($isChecked=="checked"))
              echo "<td><input type='radio' name='tableid' checked value='".$line[0]."'></td>";
            else
              echo "<td><input type='radio' name='tableid' value='".$line[0]."'></td>";
	    echo "</tr>";
            $numrow++;
          }
          echo "</table>";

          echo '
            <input type="submit" value="'.$_lang['stSAVE'].'"><br />
            </form>
            <form action="right.php?srv='.$srv.'&id=2&actid=5&aliasid='.$aliasid.'" method="post">
            <input type="submit" value="'.$_lang['stDELETE'].'"><br />
            </form>
            <br />';
        }
        if($actid==4) { //сохранение изменений UPDATE
 
          if (!mysql_query($queryUpdateOneAlias)) {
            die('Error: ' . mysql_error());
          }
          echo "".$_lang['stALIASUPDATED']."<br /><br />";
          echo "<a href=right.php?srv=".$srv."&id=2 target=right>".$_lang['stBACK']."</a>";
        }

        if($actid==5) { //удаление DELETE
        
          if (!mysql_query($queryDeleteOneAlias)) {
            die('Error: ' . mysql_error());
          }
          if (!mysql_query($queryDeleteOneAliasFromGroup)) {
            die('Error: ' . mysql_error());
          }
          
	  echo "".$_lang['stALIASDELETED']."<br /><br />";
          echo "<a href=right.php?srv=".$srv."&id=2 target=right>".$_lang['stBACK']."</a>";

        } //удаление
      } ///end if($_GET['id']==2

        else
          if($_GET['id']==3) {

            if(isset($_GET['actid'])) //action ID.
              $actid=$_GET['actid'];
            else
              $actid=0;


              if(isset($_GET['groupid'])) //group ID из таблицы scsq_groups для редактирования/удаления group.
                $groupid=$_GET['groupid'];
              else
                $groupid=0;

              if(isset($_POST['name'])) // имя
                $name=$_POST['name'];
              else
                $name=0;

              if(isset($_POST['typeid'])) // login или ipaddress 0 - логин, 1 - ipadddress 
                $typeid=1;
              else
                $typeid=0;

              if(isset($_POST['comment'])) // комментарий к группе 
                $comment=$_POST['comment'];
              else
                $comment="";

///SQL querys

            $queryAllLogins="select id,name from scsq_alias where typeid=0 order by name asc;";
            $queryAllIpaddress="select id,name from scsq_alias where typeid=1 order by name asc;";

            $queryAllGroups="SELECT 
				scsq_groups.name,
				scsq_groups.typeid,
				scsq_groups.id,
				scsq_groups.comment,
				count(scsq_aliasingroups.aliasid)
			     FROM scsq_aliasingroups 
			     RIGHT OUTER JOIN scsq_groups ON scsq_groups.id=scsq_aliasingroups.groupid
			     GROUP BY scsq_aliasingroups.groupid
			     ORDER BY name asc;";
            $queryGroupMembers="select aliasid from scsq_aliasingroups where groupid='".$groupid."'";
            $queryOneGroup="select name,typeid,id,comment from scsq_groups where id='".$groupid."';";
            $queryOneGroupList="SELECT
				   scsq_groups.name, 
				   scsq_alias.name
				FROM scsq_aliasingroups 
				RIGHT JOIN scsq_alias ON scsq_alias.id=scsq_aliasingroups.aliasid
				RIGHT JOIN scsq_groups ON scsq_groups.id=scsq_aliasingroups.groupid
				WHERE groupid='".$groupid."'
				ORDER BY scsq_alias.name asc;";

            $queryUpdateOneGroup="update scsq_groups set name='".$name."',typeid='".$typeid."',comment='".$comment."' where id='".$groupid."'";
            $queryDeleteOneGroup="delete from scsq_groups where id='".$groupid."'";

            if(!isset($_GET['actid'])) {
              $result=mysql_query($queryAllGroups);
              $numrow=1;
              echo "<a href=right.php?srv=".$srv."&id=3&actid=1>".$_lang['stADDGROUP']."</a>";
              echo "<br /><br />";
              echo "<table id=report_table_id_group border=1 class=sortable>
              <tr>
                <th class=unsortable><b>#</b></th>
                <th><b>".$_lang['stGROUPNAME']."</b></th>
                <th class=unsortable><b>".$_lang['stTYPE']."</b></th>
                <th class=unsortable><b>".$_lang['stCOMMENT']."</b></th>
		<th><b>".$_lang['stQUANTITYALIASES']."</b></th>

              </tr>";

              while($line = mysql_fetch_row($result)) {
                if($line[1]=="1")
                  $line[1]="<b><font color=green>".$_lang['stIPADDRESS']."</font></b>";
                else
                  $line[1]="<b><font color=red>".$_lang['stLOGIN']."</font></b>";

                echo "
                  <tr>
                    <td>".$numrow."</td>
                    <td align=center><a href=right.php?srv=".$srv."&id=3&actid=3&groupid=".$line[2].">".$line[0]."&nbsp;</a></td>
                    <td align=center>".$line[1]."</td>
                    <td align=center>".$line[3]."&nbsp;</td>
                    <td align=center>".$line[4]."&nbsp;<a href=right.php?srv=".$srv."&id=3&actid=6&groupid=".$line[2].">".$_lang['stWHO']."</a></td>
                 </tr>";
                $numrow++;
              }
              echo "</table>";
              echo "<br />";
              echo "<a href=right.php?srv=".$srv."&id=3&actid=1>".$_lang['stADDGROUP']."</a>";
              echo "<br />";
            }

            if($actid==1) {
              echo $_lang['stFORMADDGROUP'];
              echo '
                <form action="right.php?srv='.$srv.'&id=3&actid=2" method="post">
                '.$_lang['stGROUPNAME'].': <input type="text" name="name"><br />
                '.$_lang['stTYPECHECK'].': <input type="checkbox" onClick="switchTables();" name="typeid"><br />
                '.$_lang['stCOMMENT'].': <input type="text" name="comment"><br />
                '.$_lang['stVALUE'].':<br />';

                $result=mysql_query($queryAllLogins);
                $numrow=1;

              echo "<table id='loginsTable' class=sortable style='display:table;'>";
              echo "<tr>
		    <th class=unsortable>#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		    </tr>";

              while($line = mysql_fetch_row($result)) {
                echo "
                  <tr>
                    <td >".$numrow."</td>
                    <td >".$line[1]."</td>
                    <td><input type='checkbox' name='tableidl[]' value='".$line[0]."';</td>
                  </tr>";
                $numrow++;
              }
              echo "</table>";

              $result=mysql_query($queryAllIpaddress);
              $numrow=1;

              echo "<table id='ipaddressTable' class=sortable style='display:none;'>";
              echo "<tr>
		    <th class=unsortable>#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		    </tr>";
              while($line = mysql_fetch_row($result)) {
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

            if($actid==2) { ///добавление группы

              $name=$_POST['name'];

              if(!isset($_POST['typeid']))
                $typeid=0;
              else
                $typeid=1;

              if(!isset($_POST['comment']))
                $comment="";
              else
                $comment=$_POST['comment'];


              $sql="INSERT INTO scsq_groups (name, typeid,comment) VALUES ('$name', '$typeid','$comment')";

              if (!mysql_query($sql)) {
                die('Error: ' . mysql_error());
              }

              $sql="select id from scsq_groups where name='".$name."';";
              $result=mysql_query($sql);
              $newid=mysql_fetch_row($result);

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

              mysql_query($sql);

              echo "".$_lang['stGROUPADDED']."<br /><br />";
              echo "<a href=right.php?srv=".$srv."&id=3 target=right>".$_lang['stBACK']."</a>";
            }

            if($actid==3) { ///Редактирование группы

              $result=mysql_query($queryOneGroup);
              $line=mysql_fetch_row($result);

              if($line[1]==1)
                $isChecked="checked";
              else
                $isChecked="";

              echo '
                <form action="right.php?srv='.$srv.'&id=3&actid=4&groupid='.$groupid.'" method="post">
               '.$_lang['stGROUPNAME'].': <input type="text" name="name" value="'.$line[0].'"><br />
               '.$_lang['stTYPECHECK'].': <input type="checkbox" onClick="switchTables();" name="typeid" '.$isChecked.' ><br />
               '.$_lang['stCOMMENT'].': <input type="text" name="comment" value="'.$line[3].'"><br />
               '.$_lang['stVALUE'].':<br />';

               $result=mysql_query($queryAllLogins);
               $result1=mysql_query($queryGroupMembers);

               $numrow=1;

               if($isChecked=="checked")
                 echo "<table id='loginsTable' class=sortable style='display:none;'>";
               else
                 echo "<table id='loginsTable' class=sortable style='display:table;'>";
               echo "<tr>
		    <th class=unsortable>#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		    </tr>";
               $groupmembers=array();
               while($line = mysql_fetch_row($result1))
                 $groupmembers[]= $line[0];

               while($line = mysql_fetch_row($result)) {
                 echo "<tr>";
		 echo "<td >".$numrow."</td>";
		 echo "<td >".$line[1]."</td>";
                 if((in_array($line[0],$groupmembers))&&($ischecked==""))
                   echo "<td><input type='checkbox' name='tableidl[]' checked value='".$line[0]."'></td>";
                 else
                   echo "<td><input type='checkbox' name='tableidl[]' value='".$line[0]."'></td>";
		 echo "</tr>";
                   ;
                 $numrow++;
               }
               echo "</table>";

               $result=mysql_query($queryAllIpaddress);
               $numrow=1;

               if($isChecked=="checked")
                 echo "<table id='ipaddressTable' class=sortable style='display:table;'>";
               else
                 echo "<table id='ipaddressTable' class=sortable style='display:none;'>";
               echo "<tr>
		    <th class=unsortable>#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		    </tr>";
               while($line = mysql_fetch_row($result)) {

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
                 <form action="right.php?srv='.$srv.'&id=3&actid=5&groupid='.$groupid.'" method="post">
                 <input type="submit" value="'.$_lang['stDELETE'].'"><br />
                 </form>
                 <br />';
            } // end actid=3

            if($actid==4) { //сохранение изменений UPDATE

              if (!mysql_query($queryUpdateOneGroup)) {
                die('Error: ' . mysql_error());
              }

              $sql="delete from scsq_aliasingroups where groupid='".$groupid."';";

              mysql_query($sql) or die();

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

              mysql_query($sql);

              echo "".$_lang['stGROUPUPDATED']."<br /><br />";
              echo "<a href=right.php?srv=".$srv."&id=3 target=right>".$_lang['stBACK']."</a>";
            } //end actid=4

            if($actid==5) {//удаление DELETE

              if (!mysql_query($queryDeleteOneGroup)) {
                die('Error: ' . mysql_error());
              }
              $sql="delete from scsq_aliasingroups where groupid='".$groupid."';";

              mysql_query($sql) or die();

              echo "".$_lang['stGROUPDELETED']."<br /><br />";
              echo "<a href=right.php?srv=".$srv."&id=3 target=right>".$_lang['stBACK']."</a><br />";

            } //end actid=5

            if($actid==6) { ///Просмотр группы

              $result=mysql_query($queryOneGroupList);

	      $numrow=1;

               while($line = mysql_fetch_row($result)) {
		 if($numrow==1) {
		   echo "".$_lang['stGROUPNAME']." : <b>".$line[0]."</b><br /><br />";
                   echo "<table id='OneGroupList' class=sortable >";
                   echo "<tr>
		      <th class=unsortable>#</th>
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
               echo "<a href=right.php?srv=".$srv."&id=3>".$_lang['stBACKTOGROUPLIST']."</a>";
                 
            } // end actid=6

          } ///end GET[id]=3

          else

            if($_GET['id']==4) { ///быстрый поиск
           
              if(isset($_GET['actid'])) //action ID.
                $actid=$_GET['actid'];
              else
              $actid=0;


              if(isset($_POST['findstr'])) //alias ID из таблицы scsq_alias для редактирования/удаления alias.
                $findstr=$_POST['findstr'];
              else
                $findstr="";

              if(isset($_POST['typeid'])) // login или ipaddress 0 - логин, 1 - ipadddress 
                $typeid=1;
              else
                $typeid=0;

///SQL querys

              if($typeid==0)
                $queryForFindstr="select name,id from (select name,id from scsq_logins where name like '%".$findstr."%') as tmp where tmp.name NOT IN (''".$goodLoginsList.") order by name asc;";
              
              if($typeid==1)
                $queryForFindstr="select name,id from (select name,id from scsq_ipaddress where name like '%".$findstr."%') as tmp where tmp.name NOT IN (''".$goodIpaddressList.") order by name asc;";


              if(!isset($_GET['actid'])) {

                echo $_lang['stSEARCHFORM'];
                echo '
                  <form action="right.php?srv='.$srv.'&id=4&actid=1" method="post">
                  '.$_lang['stSEARCHSTRING'].': <input type="text" name="findstr"><br />
                  '.$_lang['stTYPECHECK'].': <input type="checkbox" name="typeid"><br />
                  <input type="submit" value="'.$_lang['stSEARCH'].'"><br />
                  </form>
                  <br />';
              }

              if($actid==1) { ///результаты поиска

                echo $_lang['stSEARCHFORM'];
                echo '
                  <form action="right.php?srv='.$srv.'&id=4&actid=1" method="post">
                  '.$_lang['stSEARCHSTRING'].': <input type="text" name="findstr"><br />
                  '.$_lang['stTYPECHECK'].': <input type="checkbox" name="typeid"><br />
                  <input type="submit" value="'.$_lang['stSEARCH'].'"><br />
                  </form>
                  <br />';

/////////// LOGINS

                if($typeid==0) {
                  echo "
                    <table id=report_table_id_1 class=sortable>
                    <tr>
                      <th class=unsortable>
                        #
                      </th>
                      <th>
                        ".$_lang['stLOGIN']."
                      </th>
                    </tr>";

                  $result=mysql_query($queryForFindstr) or die (mysql_error());
                  $numrow=1;

                  while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
                    echo "<tr>";
                    echo "<td>".$numrow."</td>";
                    echo "<td><a href=javascript:PartlyReportsLogin(8,'day','".$line[1]."','".$line[0]."','')>".$line[0]."</td>";
                    echo "</tr>";
                    $numrow++;
                  }
                  echo "</table>";
                }

/////////// LOGINS  END

//////////// IPADDRESS

                if($typeid==1) {
                  echo "
                    <table id=report_table_id_1 class=sortable>
                    <tr>
                      <th class=unsortable>
                      #
                      </th>
                      <th>
                      ".$_lang['stIPADDRESS']."
                      </th>
                    </tr>";

                  $result=mysql_query($queryForFindstr) or die (mysql_error());
                  $numrow=1;

                  while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
                    echo "<tr>";
                    echo "<td>".$numrow."</td>";
                    echo "<td><a href=javascript:PartlyReportsIpaddress(11,'day','".$line[1]."','".$line[0]."','')>".$line[0]."</td>";
                    echo "</tr>";
                    $numrow++;
                  }
                  echo "</table>";

                }
              }
/////////////// IPADDRESS END

            } ///end GET[id]=4
 	    else
	    if($_GET['id']==5) {
      echo "
      <h3>".$_lang['stLOGTABLE'].":</h3>
      <table border=1>
      <tr>
         <th>#</th>
         <th>".$_lang['stLOGDATESTART']."</th>
         <th>".$_lang['stLOGDATEEND']."</th>
         <th>".$_lang['stLOGMESSAGE']."</th>
      </tr>
   ";
  		$queryLogTable="SELECT
			FROM_UNIXTIME(datestart,'%Y-%m-%d %H:%i:%s') as d1,
			FROM_UNIXTIME(dateend,'%Y-%m-%d %H:%i:%s'),
			message
		  FROM scsq_logtable order by d1 desc";


                  $result=mysql_query($queryLogTable) or die (mysql_error());
                  $numrow=1;

                  while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
                    echo "<tr>";
                    echo "<td>".$numrow."</td>";
                    echo "<td>".$line[0]."</td>";
                    echo "<td>".$line[1]."</td>";
                    echo "<td>".$line[2]."</td>";
                    echo "</tr>";
                    $numrow++;
                  }
                  echo "</table>";

    }  //end GET[id]=5
            else

            echo $_lang['stALLISOK'];
 } 

$end=microtime(true);

$runtime=$end - $start;

echo "<br /><br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];


?>
<form name=fastdateswitch_form>
    <input type="hidden" name=date_field_hidden value="<?php echo date('d-m-Y'); ?>">
    <input type="hidden" name=dom_field_hidden value="<?php echo 'day'; ?>">
    <input type="hidden" name=group_field_hidden value="<?php echo $currentgroupid; ?>">
    <input type="hidden" name=groupname_field_hidden value="<?php echo $currentgroup; ?>">
    <input type="hidden" name=typeid_field_hidden value="<?php echo $typeid; ?>">
    </form>
</body>
</html>
