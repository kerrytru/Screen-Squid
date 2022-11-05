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
*                         File Mod     > <!#FT> 2022/11/05 21:53:43.782 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.2.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/



if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;

include("../../config.php");

#если нет авторизации, сразу выходим
if ((!isset($_COOKIE['loggedAdm'])or($_COOKIE['loggedAdm']==0)) 
	and (file_exists("".$globalSS['root_dir']."/modules/PrivateAuth/pass")) 
	and (!file_exists("".$globalSS['root_dir']."/modules/PrivateAuth/hash"))
	)
{
	header("Location: ".$globalSS['root_http']."/modules/PrivateAuth/login.php"); exit();
}

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





if(!isset($_GET['id']))
echo "<h2>".$_lang['stMODULEDESC']."</h2><br />";

$start=microtime(true);

   #write config 
   if(isset($_POST['submit'])){

	doSetParam($globalSS,'LDAPClient','ldapserver',$_POST['ldapserver']);
	doSetParam($globalSS,'LDAPClient','ldapuser',$_POST['ldapuser']);
	doSetParam($globalSS,'LDAPClient','ldappass',$_POST['ldappass']);
	doSetParam($globalSS,'LDAPClient','ldaptree',$_POST['ldaptree']);
	doSetParam($globalSS,'LDAPClient','fldUsername',$_POST['fldUsername']);
	doSetParam($globalSS,'LDAPClient','LDAP_OPT_PROTOCOL_VERSION',isset($_POST['LDAP_OPT_PROTOCOL_VERSION'])? 1:0);
	doSetParam($globalSS,'LDAPClient','LDAP_OPT_REFERRALS',isset($_POST['LDAP_OPT_REFERRALS'])? 1:0);



  }

  
$globalSS['connectionParams']['ldapserver']=doGetParam($globalSS,'LDAPClient','ldapserver');
$globalSS['connectionParams']['ldapuser']=doGetParam($globalSS,'LDAPClient','ldapuser');
$globalSS['connectionParams']['ldappass']=doGetParam($globalSS,'LDAPClient','ldappass');
$globalSS['connectionParams']['ldaptree']=doGetParam($globalSS,'LDAPClient','ldaptree');
$globalSS['connectionParams']['fldUsername']=doGetParam($globalSS,'LDAPClient','fldUsername');
$globalSS['connectionParams']['LDAP_OPT_PROTOCOL_VERSION']=doGetParam($globalSS,'LDAPClient','LDAP_OPT_PROTOCOL_VERSION');
$globalSS['connectionParams']['LDAP_OPT_REFERRALS']=doGetParam($globalSS,'LDAPClient','LDAP_OPT_REFERRALS');

$ldap_client = new LDAPClient($globalSS);

#если есть дружеские логины, IP адреса или сайты. Соберём их.
$goodLoginsList=doCreateFriendList($globalSS,'logins');
$goodIpaddressList=doCreateFriendList($globalSS,'ipaddress');
$goodSitesList = doCreateSitesList($globalSS);

echo "<h3>Config module</h3>";


  echo '
  <form action="index.php" method="post">
	 <table class=datatable>
	 <tr>
<td>ldapserver</td>
<td>
<input type="text" name="ldapserver" value="'.$globalSS['connectionParams']['ldapserver'].'">
</td>
<td>';
echo $ldap_client->doCheckLDAPBind()==1 ?  'OK: Connection established': 'ERROR: No connection';
echo '</td>
</tr>
<tr>
	<td>ldapuser</td>
	<td><input type="text" name="ldapuser" value="'.$globalSS['connectionParams']['ldapuser'].'"></td>
	<td>&nbsp;</td> 
</tr>
<tr>
	<td>ldappass</td>
	<td><input type="text" name="ldappass" value="'.$globalSS['connectionParams']['ldappass'].'"></td>
	<td>&nbsp;</td> 
</tr>
<tr>
	<td>ldaptree</td>
	<td><input type="text" name="ldaptree" value="'.$globalSS['connectionParams']['ldaptree'].'"></td>
	<td>&nbsp;</td> 
</tr>
<tr>
	<td>fldUsername</td>
	<td><input type="text" name="fldUsername" value="'.$globalSS['connectionParams']['fldUsername'].'"></td>
	<td>&nbsp;</td> 
</tr>
<tr>
	<td>LDAP_OPT_PROTOCOL_VERSION</td>
	<td><input type="checkbox" '.($globalSS['connectionParams']['LDAP_OPT_PROTOCOL_VERSION']==1 ? 'checked' : "").' name="LDAP_OPT_PROTOCOL_VERSION" value="'.$globalSS['connectionParams']['LDAP_OPT_PROTOCOL_VERSION'].'"></td>
	<td>&nbsp;</td> 
</tr>
<tr>
	<td>LDAP_OPT_REFERRALS</td>
	<td><input type="checkbox" '.($globalSS['connectionParams']['LDAP_OPT_REFERRALS']==1 ? 'checked' : "").' name="LDAP_OPT_REFERRALS" ></td>
	<td>&nbsp;</td> 
</tr>


</table>
 <br />
  <input type="submit" name=submit value="Save"><br />
  </form>
  ';




///SQL querys


        $queryAllLogins="select id,name from scsq_logins  where id NOT IN ('".$goodLoginsList."') order by name asc;";
 


            if(!isset($_GET['actid'])) {




echo "<a href=index.php?srv=".$srv."&actid=1 target=right>".$_lang['stLDAPSYNCHRONIZETOLDAP']."</a><br />";
			}
		
		if(isset($_GET['actid']))
          if($_GET['actid']==1) {

         $result=doFetchQuery($globalSS, $queryAllLogins);
		  $numrow=0;
		  
		  $numerror=0;
		  $numadded=0;

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
					
					         $idAlias = GetAliasIdByLoginId($globalSS, $line[0]);
					         
					         
							 echo "Поиск вернул id алиаса = '".$idAlias."'.<br>";
					         
					         #если алиас существует, то обновим его. иначе создадим
					         if($idAlias>0)
							{
							 $alias_params=array();
							 $alias_params['name'] = $aliasname;
							 $alias_params['aliasid'] = $idAlias;
							 $alias_params['external'] = 1;
							 
							 doAliasSave($globalSS,$alias_params);

							 echo "Обновили наименование алиаса для id алиаса = '".$idAlias."'. Теперь имя алиаса = '".$aliasname."'.<br>";
							 $numadded++;
							}
							 else 
								{
									$alias_params=array();
									$alias_params['name'] = $aliasname;
									$alias_params['typeid'] = '0';
									$alias_params['tableid'] = $line[0];
									$alias_params['userlogin'] = $line[1];
									$alias_params['activeauth'] = '0';
									$alias_params['external'] = 1;
									
								
								doAliasAdd($globalSS,$alias_params);	

								echo "Создали новый алиас = '".$aliasname."' для логина = '".$line[1]."'.<br>";
								$numadded++;
							}
							
					   
							
					 echo "Цикл прошёл<br><br>";
					
					
				  }

				  

				  
				  $numrow++;
          }
			

echo $_lang['stLDAPCREATEDUPDATED']." ".$numadded." ".$_lang['stLDAPALIASES'];


			  
          echo "<br><br><a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a><br />";

            } //end actid=1

         



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
