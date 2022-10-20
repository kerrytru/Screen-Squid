<?php

#Build date Thursday 7th of May 2020 18:44:28 PM
#Build revision 1.3

#чтобы убрать возможные ошибки с датой, установим на время исполнения скрипта ту зону, которую отдает система.
date_default_timezone_set(date_default_timezone_get());

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
$variableSet['ip2name_file']=$ip2name_file;
$variableSet['ip2name_separator']=$ip2name_separator;

$variableSet['language']=$language;

$globalSS['connectionParams']=$variableSet;



$ip2name_ex = new IP2NAME($globalSS);


$goodLoginsList=doCreateFriendList($globalSS,'logins');

if(!isset($_GET['id']))
echo "<h2>".$_lang['stMODULEDESC']."</h2><br />";

$start=microtime(true);





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
			
			while($str = fgets($openfile))
			{
				
					$ipaddress_str = explode($ip2name_separator,$str);
			
					$aliasname="";
			  #запросим у БД есть ли такой IP. И если есть то возьмём его id
			  $ipaddress_id=$ip2name_ex->GetIdByIp($ipaddress_str[0]);
			 
			 if($ipaddress_id !="")
				  {
			
					         $sql="select id from scsq_alias where tableid='$ipaddress_id' and typeid=1";
					         $idAlias = doFetchOneQuery($globalSS,$sql);
					         
					         					         
					         #если алиас существует, то обновим его. иначе создадим
					         if($idAlias[0]>0)
								$sql="UPDATE scsq_alias SET name='$ipaddress_str[1]' WHERE id='$idAlias[0]'";
							
							 else 
								$sql="INSERT INTO scsq_alias (name, typeid,tableid,userlogin,password,active) VALUES ('$ipaddress_str[1]', '1','$ipaddress_id','','','0')";
      
							 
							  if (!doQuery($globalSS, $sql)) {
								die('Error: Can`t insert alias into table!');
							  }
					$numrow++;
				  }
   
            }
          
		
		echo	$_lang['stIP2NAMECREATEDUPDATED']." ".$numrow." ".$_lang['stIP2NAMEALIASES'];


			  
          echo "<a href=index.php?srv=".$srv." target=right>".$_lang['stBACK']."</a><br />";
			  
			

		  

            } //end actid=1


     
echo "<br /><br/>
".$_lang['stIP2NAMEATTENTIONMESSAGE']."
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
