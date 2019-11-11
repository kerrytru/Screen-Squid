<?php
#build 20191111


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

include("config.php");
include("module.php");
include_once("../../lang/$language");
#include_once("lang/$language");


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
$variableSet['ldapserver']=$ldapserver;
$variableSet['ldapuser']=$ldapuser;
$variableSet['ldappass']=$ldappass;
$variableSet['ldaptree']=$ldaptree;

#в зависимости от типа БД, подключаем разные модули
if($dbtype==0)
include("../../lib/dbDriver/mysqlmodule.php");

if($dbtype==1)
include("../../lib/dbDriver/pgmodule.php");

$ldap_client = new LDAPClient($variableSet);


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

	      $aliasid=$_POST['aliasid'];
	      $quotaday=$_POST['quotaday']+0;
	      $quotamonth=$_POST['quotamonth']+0;

	      $sumday=$_POST['sumday'];
	      $summonth=$_POST['summonth'];

              if(isset($_POST['quota'])) // 
                $quota=$_POST['quota']+0;
              else
                $quota=0;

              if(isset($_POST['active'])) //  
                $active=1;
              else
                $active=0;



///SQL querys


        $queryAllLogins="select id,name from scsq_logins  where name NOT IN (''".$goodLoginsList.") order by name asc;";
 


            if(!isset($_GET['actid'])) {




echo "<a href=index.php?srv=".$srv."&actid=1 target=right>Синхронизировать c LDAP</a><br />";
			}
          if($actid==1) {

         $result=$ssq->query($queryAllLogins);
          $numrow=0;

          while($line = $ssq->fetch_array($result)) {
			  
			  $aliasname="";
			  #запросим у LDAP есть ли такой логин. И если есть то возьмём его имя
			  $aliasname=$ldap_client->GetUsernameByLogin($line[1]);
		
			  if($aliasname !="")
				  {
			
					         $sql="select id from scsq_alias where userlogin='$line[1]'";
					         $resquery = $ssq->query($sql);
					         
					         $idAlias = $ssq->fetch_array($resquery);
					         
					         #если алиас существует, то обновим его. иначе создадим
					         if($idAlias[0]>0)
								$sql="UPDATE scsq_alias SET name='$aliasname' WHERE id='$idAlias[0]'";
							
							 else 
								$sql="INSERT INTO scsq_alias (name, typeid,tableid,userlogin,password,active) VALUES ('$aliasname', '0','$line[0]','$line[1]','','0')";
      
							$ssq->free_result($resquery);
					   
							  if (!$ssq->query($sql)) {
								die('Error: Can`t insert alias into table!');
							  }
					$numrow++;
				  }
   
            
          }
			$ssq->free_result($result);

echo "создано/обновлено ".$numrow." алиасов";


			  
          echo "<a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a><br />";

            } //end actid=1

      
echo "<br /><br/>
Прежде чем нажать кнопку <b>Синхронизировать</b>, необходимо занести  параметры в файл ../LDAPClient/config.php.
<br /><br />Необходимые параметры:
<br /><br />
<table id=report_table_id_group border=1 class=sortable>
<tr>
	<th class=unsortable>
		Переменная
	</th>

	<th class=unsortable>
		Значение по умолчанию
	</th>
	<th class=unsortable>
		Комментарий
	</th>

</tr>
<tr>
	<td>
\$ldapserver
	</td>

	<td>
		'localhost';
	</td>
	<td>
		//хост с LDAP
	</td>

</tr>
<tr>
	<td>
\$ldapuser
	</td>
	<td>
		'cn=Manager,dc=my-domain,dc=com';
	</td>
	<td>
		//Имя пользователя от имени которого можно подключится
	</td>

</tr>
<tr>
	<td>
		\$ldappass
	</td>

	<td>
		'pass';
	</td>
	<td>
		//Пароль пользователя
	</td>

</tr>
<tr>
	<td>
\$ldaptree
	</td>

	<td>
		\"OU=Users,DC=my-domain,DC=com\"
	</td>
	<td>
		//Ветка в которой искать
	</td>
	
</tr>
</table>

";
         



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