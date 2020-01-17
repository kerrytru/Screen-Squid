<?php

include("config.php");

echo "Test page<br /><br />";

$variableSet = array();

for($i=0;$i<count($srvname);$i++)
{

$addr=$address[$i];
$usr=$user[$i];
$psw=$pass[$i];
$dbase=$db[$i];
$dbtype=$srvdbtype[$i];

$squidhost=$cfgsquidhost[$i];
$squidport=$cfgsquidport[$i];
$cachemgr_passwd=$cfgcachemgr_passwd[$i];


$variableSet['addr']=$addr;
$variableSet['usr']=$usr;
$variableSet['psw']=$psw;
$variableSet['dbase']=$dbase;
$variableSet['dbtype']=$dbtype;


#в зависимости от типа БД, подключаем разные модули
if($dbtype==0)
include_once("lib/dbDriver/mysqlmodule.php");

if($dbtype==1)
include_once("lib/dbDriver/pgmodule.php");


//вывод на экран диагностической информации

echo "<b>Check configuration settings, server ".$srvname[$i]."</b><br />";

if($dbtype==0)
	echo "Type DB MySQL<br />";	
if($dbtype==1)
	echo "Type DB PostGRESQL<br />";	

echo "Trying connect to DB...";

$ssq = new ScreenSquid($variableSet); #получим экземпляр класса и будем уже туда закиыдвать запросы на исполнение

$ssq->query("select 1 from scsq_traffic limit 1");

if($ssq->db_object==true)
echo "Ok!";
else {
echo "Error!";
echo "<br>";
echo "
1. Check that DB server is ONLINE.<br>
2. Check config.php for connection settings (login, pass,db, host).<br>
3. Check that you can connect from your system to database server on default DB port (3306 to MySQL or 5432 to PostGRESQL).<br>
4. Check that user ".$usr." have rights to connect to DB.<br>
5. If you have no idea about problem, join our telegram group t.me/screensquid. We try to help you.
";
}

echo "<br /><br />";

echo "Trying connect to Cachemgr...";

$fp = fsockopen($squidhost,$squidport, $errno, $errstr, 10); 

if($fp)
	{
	echo "Ok!";
	fclose($fp);
	}
else
	{
	echo "Error! ";
	echo "<br>";
	echo "
	1. Check config.php for connection settings.<br>
	2. Disable SElinux (if you have it). <br>
	2. Check this solution https://sourceforge.net/p/screen-squid/tickets/21/<br>
	3. If you have no idea about problem, join our telegram group t.me/screensquid. We try to help you.
	";
	}
echo "<br /><br />";

}

?>
