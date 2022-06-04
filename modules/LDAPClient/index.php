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
*                         File Birth   > <!#FB> 2022/06/04 23:00:24.599 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/06/04 23:14:50.470 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






#чтобы убрать возможные ошибки с датой, установим на время исполнения скрипта ту зону, которую отдает система.
date_default_timezone_set(date_default_timezone_get());

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;

include("../../config.php");

$language=$globalSS['language'];


include("config.php");
include("module.php");
include("../../lang/$language");
	if (file_exists("langs/".$language))
		include("langs/".$language);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 

include_once(''.$globalSS['root_dir'].'/lib/functions/function.misc.php');
include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<?php echo '<link rel="stylesheet" type="text/css" href="'.$globalSS['root_http'].'/themes/'.$globalSS['globaltheme'].'/global.css"/>';
?>
</head>
<body>

<script type="text/javascript" src="../../javascript/sortable.js"></script>


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
$variableSet['ldapserver']=$ldapserver;
$variableSet['ldapuser']=$ldapuser;
$variableSet['ldappass']=$ldappass;
$variableSet['ldaptree']=$ldaptree;

$variableSet['language']=$language;

$globalSS['connectionParams']=$variableSet;

$ldap_client = new LDAPClient($globalSS);

  #если есть дружеские логины, IP адреса или сайты. Соберём их.
  $goodLoginsList=doCreateFriendList($globalSS,'logins');
  $goodIpaddressList=doCreateFriendList($globalSS,'ipaddress');
  $goodSitesList = doCreateSitesList($globalSS);


if(!isset($_GET['id']))
echo "<h2>".$_lang['stMODULEDESC']."</h2><br />";

$start=microtime(true);





///SQL querys


        $queryAllLogins="select id,name from scsq_logins  where id NOT IN ('".$goodLoginsList."') order by name asc;";
 


            if(!isset($_GET['actid'])) {




echo "<a href=index.php?srv=".$srv."&actid=1 target=right>".$_lang['stLDAPSYNCHRONIZETOLDAP']."</a><br />";
			}
		
		if(isset($_GET['actid']))
          if($_GET['actid']==1) {

         $result=doFetchQuery($globalSS, $queryAllLogins);
          $numrow=0;
			# попробуем полностью протестировать код.
			echo "Начинаем синхронизацию с LDAP <br>";
          foreach($result as $line) {
			echo "Цикл итерация ".$numrow."<br><br>";

			echo "Ищем значение алиаса для логина ".$line[1]."<br>";
			  $aliasname="";
			  #запросим у LDAP есть ли такой логин. И если есть то возьмём его имя
			  $aliasname=$ldap_client->GetUsernameByLogin($line[1]);

			  echo "Функция вернула алиас = '".$aliasname."' для логина '".$line[1]."'<br>";
		
			  if($aliasname !="(not found)")
				  {
					echo "Начинаем запись.<br>";
					echo "Узнаем, есть ли уже алиас для логина = '".$line[1]."'.<br>";
					
					         $sql="select id from scsq_alias where tableid=$line[0] and typeid=0";
					         $idAlias = doFetchOneQuery($globalSS, $sql);
					         
					         
							 echo "Поиск вернул id алиаса = '".$idAlias[0]."'.<br>";
					         
					         #если алиас существует, то обновим его. иначе создадим
					         if($idAlias[0]>0)
							{
							 $sql="UPDATE scsq_alias SET name='$aliasname' WHERE id='$idAlias[0]'";
							 echo "Обновили наименование алиаса для id алиаса = '".$idAlias[0]."'. Теперь имя алиаса = '".$aliasname."'.<br>";
							}
							 else 
								{
								$sql="INSERT INTO scsq_alias (name, typeid,tableid,userlogin,password,active) VALUES ('$aliasname', '0','$line[0]','$line[1]','','0')";
								echo "Создали новый алиас = '".$aliasname."' для логина = '".$line[1]."'.<br>";
								}
							
					   
							  if (!doQuery($globalSS, $sql)) {
								die('Error: Can`t insert alias into table!');
							  }
					 echo "Цикл прошёл<br><br>";
					
					 
				  }
				  $numrow++;
            
          }
			

echo $_lang['stLDAPCREATEDUPDATED']." ".$numrow." ".$_lang['stLDAPALIASES'];


			  
          echo "<a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a><br />";

            } //end actid=1


     
echo "<br /><br/>
".$_lang['stLDAPATTENTIONMESSAGE']."
<br /><br />".$_lang['stLDAPIMPORTANTVARIABLES']."
<br /><br />
<table class=datatable>
<tr>
	<th class=unsortable>
		".$_lang['stLDAPVARIABLE']."
	</th>

	<th class=unsortable>
		".$_lang['stLDAPDEFAULTVALUE']."
	</th>
	<th class=unsortable>
		".$_lang['stLDAPCOMMENT']."
	</th>

</tr>
<tr>
	<td>
		".$_lang['stLDAPHOST']."
	</td>

	<td>
		".$_lang['stLDAPHOSTVALUE']."
	</td>
	<td>
		".$_lang['stLDAPHOSTCOMMENT']."
	</td>

</tr>
<tr>
	<td>
		".$_lang['stLDAPUSER']."
	</td>
	<td>
		".$_lang['stLDAPUSERVALUE']."
	</td>
	<td>
		".$_lang['stLDAPUSERCOMMENT']."
	</td>

</tr>
<tr>
	<td>
		".$_lang['stLDAPPASS']."
	</td>

	<td>
		".$_lang['stLDAPPASSVALUE']."
	</td>
	<td>
		".$_lang['stLDAPPASSCOMMENT']."
	</td>

</tr>
<tr>
	<td>
		".$_lang['stLDAPBRANCH']."
	</td>

	<td>
		".$_lang['stLDAPBRANCHVALUE']."
	</td>
	<td>
		".$_lang['stLDAPBRANCHCOMMENT']."
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
    <input type="hidden" name=group_field_hidden value=0>
    <input type="hidden" name=groupname_field_hidden value=0>
    <input type="hidden" name=typeid_field_hidden value=0>
    </form>
</body>
</html>
