<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> login.php </#FN>                                                       
*                         File Birth   > <!#FB> 2021/11/02 23:11:12.035 </#FB>                                         *
*                         File Mod     > <!#FT> 2021/11/02 23:12:18.676 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/


 



function generateCode($length=6) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

    $code = "";

    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {

            $code .= $chars[mt_rand(0,$clen)];  
    }

    return $code;

}



include("../../config.php");
include("config.php");

$start=microtime(true);

$message = array();

$message['datestart']=$start;
$message['dateend']=$start;

//Определим номер базы, по имени указанной в конфиге. Так проще.
$srv=array_search($cab_dbname, $db); 

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

$globalSS['connectionParams']=$variableSet;


//блок авторизации
if(isset($_POST['submit']))

{
echo '<script src="../../javascript/navigator.js" type="text/javascript"></script>';

if($dbtype==0)
	$query = "SELECT id, password,active,tableid,typeid,name FROM scsq_alias WHERE userlogin='".$_POST['userlogin']."' LIMIT 1";

if($dbtype==1)
	$query = "SELECT id, password,active,tableid,typeid,name FROM scsq_alias WHERE userlogin='".$_POST['userlogin']."' LIMIT 1";


	$row=doFetchOneQuery($globalSS, $query);


	
	if(!isset($row[0]))
		{		

if($dbtype==0)
			$query = "SELECT id, password,active,'',typeid,name FROM scsq_groups WHERE userlogin='".$_POST['userlogin']."' LIMIT 1";

if($dbtype==1)
			$query = "SELECT id, password,active,'',typeid,name FROM scsq_groups WHERE userlogin='".$_POST['userlogin']."' LIMIT 1";



			$row=doFetchOneQuery($globalSS, $query);
		
			$groupquery=1; //признак что сработал групповой запрос

		
		}

	#	if($row['password'] == md5(md5($_POST['password'])) && $row['active'] == 1)
	if($row[1] == md5(md5($_POST['password'])) && $row[2] == 1 && $row[0]>0)

		    {
				   # на тот случай, если хакер подкинет куки, мы создадим хэш и загрузим его в базу.
				   # если куки есть, а хэш не совпадает, то вероятно этот хакер авторизацию не проходил
		        $hash = md5(generateCode(10));
		
			if($groupquery==1)
				$query = "UPDATE scsq_groups SET hash='".$hash."' WHERE id=".$row[0].";";
			else
				$query = "UPDATE scsq_alias SET hash='".$hash."' WHERE id=".$row[0].";";

			$result=doQuery($globalSS, $query);
    

			# set cookie

			setcookie("idalias", $row[0], 0);

			setcookie("hash", $hash, 0);

			setcookie("logged", 1, 0);

			
			
			//setcookie("typeid", $row['typeid'], 0);
			setcookie("typeid", $row[4], 0);
			

		

			if($groupquery==1)
			{
			setcookie("tableid", $row[0], 0);
			setcookie("idmenu", 4, 0);

			setcookie("srv", $srv, 0);
			setcookie("idreport", 25, 0);
			setcookie("typeid", $row[4], 0);
			setcookie("querydate", $querydate, 0);
			setcookie("namelogin", $_POST['userlogin'], 0);
			setcookie("realname", $row[5], 0);

			$message['message']="CABINET INFO: Client IP = ".$_SERVER['REMOTE_ADDR'].", LOGIN = ".$row[5]." AUTH OK.";
			doWriteToLogTable($globalSS,$message);

			#header("Location: reports.php?srv=".$srv."&id=25&date=".$querydate."&dom=day&login=&groupname=&typeid=".$row[4]."&group=".$row[0].""); exit();
			header("Location: index.php"); exit();
			}
			
			else		
			{
			if($row[4]==0){
	#			setcookie("tableid", $row['tableid'], 0);
				setcookie("tableid", $row[3], 0);
				setcookie("srv", $srv, 0);
				setcookie("idreport", 8, 0);
				setcookie("typeid", $row[4], 0);
				setcookie("querydate", $querydate, 0);
				setcookie("namelogin", $_POST['userlogin'], 0);
				setcookie("realname", $row[5], 0);
				setcookie("idmenu", 1, 0);

				$message['message']="CABINET INFO: Client IP = ".$_SERVER['REMOTE_ADDR'].", ALIAS = ".$row[5]." AUTH OK.";
				doWriteToLogTable($globalSS,$message);
		
				#header("Location: reports/reports.php?srv=".$srv."&id=8&date=".$querydate."&dom=day&login=".$row[3]."&groupname=&typeid=".$row[4]."&group="); exit();
				header("Location: index.php"); exit();
			}			
			if($row[4]==1)
			{
				setcookie("tableid", $row[3], 0);
				setcookie("idmenu", 2, 0);

				setcookie("srv", $srv, 0);
				setcookie("idreport", 11, 0);
				setcookie("typeid", $row[4], 0);
				setcookie("querydate", $querydate, 0);
				setcookie("namelogin", $_POST['userlogin'], 0);
				setcookie("realname", $row[5], 0);

				$message['message']="CABINET INFO: Client IP = ".$_SERVER['REMOTE_ADDR'].", ALIAS = ".$row[5]." AUTH OK.";
				doWriteToLogTable($globalSS,$message);
					
				header("Location: index.php"); exit();
				}
			}

		    }

		else

		    {
			//wrong password
			$message['message']="CABINET INFO: Client IP = ".$_SERVER['REMOTE_ADDR'].", LOGIN = ".$_POST['userlogin']." AUTH ERROR.";
			doWriteToLogTable($globalSS,$message);

		       echo "<script> alert('".$_lang['stAUTHFAIL']."')</script>";
			

		    }

} //if(isset($_POST['submit']))

?>
<html><head>

<meta http-equiv="Cache-Control" content="no-cache"> 	
<title><?php echo $_lang['stLOGINTOCABINET'];?></title>

<link rel="stylesheet" type="text/css" href="css/cabinet.css"/>

</head>

<body>
	<div id="page" align="center">
		<div id="page_in" align="left">

	
			<header align="center">
				<div id="logo"><img src="img/logo.png" style="width: 116px; height: auto;" align="left"></div>
				
				<h1>
					<?php echo $_lang['stLOGINTOCABINET'];?>
				</h1>
			</header>
		</div>
			<div class="clr"></div>

<div class="clr"></div>	
<div class="line"></div>


	<article>
		<div id="left_col">
		
		</div>
		
			<div id="login" align="center">
			<form action="login.php" id="form_login" name="form_login" method="POST">
				<fieldset>
					<p><label for="email"><?php echo $_lang['stUSERLOGIN'];?>:</label></p>
					<p><input type="text" name="userlogin" id="email" value="<?php echo $_lang['stUSERLOGIN'];?>" onblur="if(this.value=='')this.value='<?php echo $_lang['stUSERLOGIN'];?>'" onfocus="if(this.value=='<?php echo $_lang['stUSERLOGIN'];?>')this.value=''"></p>

					<p><label for="password"><?php echo $_lang['stUSERPASSWORD'];?>:</label></p>
					<p><input type="password" id="password" name="password" value="<?php echo $_lang['stUSERPASSWORD'];?>" onblur="if(this.value=='')this.value='<?php echo $_lang['stUSERPASSWORD'];?>'" onfocus="if(this.value=='<?php echo $_lang['stUSERPASSWORD'];?>')this.value=''"></p> 
					<br>
					<p><input name="submit" type="submit" value="<?php echo $_lang['stENTER'];?>"></p>
				</fieldset>
			</form>

		</div>

	</article>
</div>

<div class="clr"></div>	
<div class="line"></div>

</body></html>