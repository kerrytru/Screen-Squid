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
*                         File Mod     > <!#FT> 2024/06/25 20:56:01.002 </#FT>                                         *
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
#если нет авторизации, сразу выходим
if (!isAuth()) 
{
	header("Location: ".$globalSS['root_http']."/modules/PrivateAuth/login.php"); exit();
}

$language=$globalSS['language'];


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
		doSetParam($globalSS,'IP2NAME','ip2name_table',$_POST['ip2name_table']);
	  }
  
	  $ip2name_file=doGetParam($globalSS,'IP2NAME','ip2name_file');
	  $ip2name_separator=doGetParam($globalSS,'IP2NAME','ip2name_separator');
	  $ip2name_table=doGetParam($globalSS,'IP2NAME','ip2name_table');


	  $ip2name_table_chk = ($ip2name_table == 'on') ? 'checked' : '';

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
  </td>
 <td>
 &nbsp;
   </td>
  </tr>
  <tr>
  <td>ip2name_table (off - ipaddress, on - logins)</td>
  <td>
  <input type="checkbox" name="ip2name_table" '.$ip2name_table_chk.'>
  </td>
 <td>
 &nbsp;
   </td>
  </tr>

  </table>
	 <br />
	  <input type="submit" name=submit value="Save"><br />
	  </form>
	  ';



///SQL querys




     //       if(!isset($_GET['actid'])) {
				echo "<a href=index.php?srv=".$srv."&actid=1 target=right>".$_lang['stIP2NAMESYNCHRONIZE']."</a><br />";
		//	}
			
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
				
					$param_str = explode($ip2name_separator,$str);

					

					$aliasname="";

			if($ip2name_table==""){ 

			  #запросим у БД есть ли такой IP. И если есть то возьмём его id
			  $ipaddress_id=GetIdByIpaddress($globalSS, $param_str[0]);


			  #ip адрес не найден
			  if($ipaddress_id == "") {
				echo "ERROR: $param_str[0] did not match to any ip in scsq_ipaddress table<br>";
				$numerror++;
				continue;
			  }

				}
		
				if($ip2name_table=="on"){ 

					#запросим у БД есть ли такой Login. И если есть то возьмём его id
					$login_id=GetIdByLogin($globalSS, $param_str[0]);
	  
	  
					#ip адрес не найден
					if($login_id == "") {
					  echo "ERROR: $param_str[0] did not match to any ip in scsq_login table<br>";
					  $numerror++;
					  continue;
					}
	  
			  }
			  

				
			  $alias_params=array();
			  
			  #спецсигнал. сообщим функции, что мы используем её извне.
			  $alias_params['externalAlias']=1;

			  $alias_params['name']=$param_str[2];
			  $alias_params['typeid']=($ip2name_table=="")? "1":"0";
			  $alias_params['tableid']=($ip2name_table=="")? $ipaddress_id:$login_id;

			  $alias_params['activeauth']=$param_str[3];
			  $alias_params['changepassword']=1;
			  $alias_params['userlogin'] = $param_str[0];
			  $alias_params['userpassword'] = $param_str[1];
			 

			  #запросим у БД есть ли такой алиас


		if($ip2name_table==""){ 
			  $aliasid = $ipaddress_id != "" ? GetAliasIdByIpaddressId($globalSS, $ipaddress_id) : "";
		}

		if($ip2name_table=="on"){ 
			$aliasid = $login_id != "" ? GetAliasIdByLoginId($globalSS, $login_id) : "";
	  }


		$alias_params['aliasid']=$aliasid; 

	  	$globalSS['alias_params']=$alias_params;

			  $aliasid == "" ? doAliasAdd($globalSS) : doAliasSave($globalSS); 

			  if ($aliasid == "") echo "ADDED: $param_str[0], $param_str[1]<br>";  
			  
			  else echo "UPDATED: $param_str[0], $param_str[1] (aliasid = $aliasid)<br>";


			  $numrow++;

   
            }
          
		echo "<br>";
		echo	$_lang['stIP2NAMECREATEDUPDATED']." ".$numrow." ".$_lang['stIP2NAMEALIASES'];
		echo "<br>";
		echo	"Errors count ".$numerror.". Check log operation.";


			  
  			  
			

		  

            } //end actid=1



$end=microtime(true);

$runtime=$end - $start;

echo "<br /><br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];

$newdate=strtotime(date("d-m-Y"))-86400;
$newdate=date("d-m-Y",$newdate);



?>
<form name=fastdateswitch_form>
    <input type="hidden" name=date value="<?php echo $newdate; ?>">
    <input type="hidden" name=date2 value="">
    </form>
</body>
</html>
