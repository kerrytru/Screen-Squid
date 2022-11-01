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
*                         File Birth   > <!#FB> 2022/11/01 22:07:09.322 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/11/01 22:09:51.577 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
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

include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
include_once(''.$globalSS['root_dir'].'/lib/functions/function.misc.php');

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



$ip2name_ex = new IP2NAME($globalSS);


$goodLoginsList=doCreateFriendList($globalSS,'logins');

if(!isset($_GET['id']))
echo "<h2>".$_lang['stMODULEDESC']."</h2><br />";

$start=microtime(true);

    #write config 
    if(isset($_POST['submit'])){
		doSetParam($globalSS,'IP2NAME','ip2name_file',$_POST['ip2name_file']);
		doSetParam($globalSS,'IP2NAME','ip2name_separator',$_POST['ip2name_separator']);
	  
	  }
  
	  $ip2name_file=doGetParam($globalSS,'IP2NAME','ip2name_file');
	  $ip2name_separator=doGetParam($globalSS,'IP2NAME','ip2name_separator');

  echo "<h3>Config module</h3>";
  
 
	  echo '
	  <form action="index.php" method="post">
		 <table class=datatable>
		 <tr>
  <td>ip2name_file</td>
  <td>
  <input type="text" name="ip2name_file" value="'.$ip2name_file.'">
  </td>
<td>';
echo file_exists("".$globalSS['root_dir']."/modules/IP2NAME/".$ip2name_file."") ?  'OK: File found': 'ERROR: File not found';
echo '</td>
  </tr>
  <tr>
  <td>ip2name_separator</td>
  <td>
  <input type="text" name="ip2name_separator" value="'.$ip2name_separator.'">
 <td>
 &nbsp;
 </td> 
  </td>
  </tr>

  </table>
	 <br />
	  <input type="submit" name=submit value="Save"><br />
	  </form>
	  ';



///SQL querys




            if(!isset($_GET['actid'])) {
				echo "<a href=index.php?srv=".$srv."&actid=1 target=right>".$_lang['stIP2NAMESYNCHRONIZE']."</a><br />";
			}
			
		if(isset($_GET['actid']))
          if($_GET['actid']==1) {

			//считаем файл и будем идти построчно
						
			$openfile = fopen($ip2name_file,'r');
			
			//зададим изначальный IP адрес. мало ли, всё-таки отбираем через like
			$ip2name_ipaddress = "000.000.000.000";
			$numrow = 0;
			$numerror = 0;
			echo "<h4>LOG OPERATION:</h4>";
			while($str = fgets($openfile))
			{
				
					$ipaddress_str = explode($ip2name_separator,$str);

					

					$aliasname="";
			  #запросим у БД есть ли такой IP. И если есть то возьмём его id
			  $ipaddress_id=$ip2name_ex->GetIdByIp($ipaddress_str[0]);


			  #ip адрес не найден
			  if($ipaddress_id == "") {
				echo "ERROR: $ipaddress_str[0] did not match to any ip in scsq_ipaddress table<br>";
				$numerror++;
				continue;
			  }
			  
			  $alias_params=array();
			  
			  #спецсигнал. сообщим функции, что мы используем её извне.
 	 	  	  $alias_params['external']=1;
			  $alias_params['name']=$ipaddress_str[1];
			  $alias_params['typeid']=1;
			  $alias_params['tableid']=$ipaddress_id;
			  #эти параметры заглушим пока.
			  $alias_params['userlogin']="";
			  $alias_params['userpassword']="";
			  $alias_params['activeauth']=0;
			  $alias_params['changepassword']=0;


			  #запросим у БД есть ли такой алиас
			  $aliasid = $ipaddress_id != "" ? $ip2name_ex->GetAliasIdByIp($ipaddress_id) : "";

			  $alias_params['aliasid']=$aliasid; 

			  $aliasid == "" ? doAliasAdd($globalSS,$alias_params) : doAliasSave($globalSS,$alias_params); 

			  if ($aliasid == "") echo "ADDED: $ipaddress_str[0], $ipaddress_str[1]<br>";  
			  
			  else echo "UPDATED: $ipaddress_str[0], $ipaddress_str[1] (aliasid = $aliasid)<br>";


			  $numrow++;

   
            }
          
		echo "<br>";
		echo	$_lang['stIP2NAMECREATEDUPDATED']." ".$numrow." ".$_lang['stIP2NAMEALIASES'];
		echo "<br>";
		echo	"Errors count ".$numerror.". Check log operation.";


			  
          echo "<br><br><br><a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a><br />";
			  
			

		  

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
    <input type="hidden" name=group_field_hidden value="<?php echo $currentgroupid; ?>">
    <input type="hidden" name=groupname_field_hidden value="<?php echo $currentgroup; ?>">
    <input type="hidden" name=typeid_field_hidden value="<?php echo $typeid; ?>">
    </form>
</body>
</html>
