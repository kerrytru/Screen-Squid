<?php


class Quotas
{

function __construct($variables){ // 
    $this->vars = $variables;
}

  function GetDesc()
  {
      return 'Менеджер квот пользователей'; # TODO добавить в lang
  }

  function GetConnectionDB()
  {

	$connection=mysqli_connect($this->vars['addr'],$this->vars['usr'],$this->vars['psw'],$this->vars['dbase']);
	return $connection;
  }

  function GetAliasDayTraffic($aliasid,$goodSitesList) #по алиасу возвращаем его дневной траффик
  {

$connection = $this->GetConnectionDB();

$queryAlias = "
 	SELECT 
	   tableid,
	   typeid
 		   FROM scsq_alias 
		   WHERE id=".$aliasid."
	;";

	$result=mysqli_query($connection,$queryAlias,MYSQLI_USE_RESULT) or die (mysqli_error());

	$row=mysqli_fetch_array($result,MYSQLI_NUM);
		mysqli_free_result($result);

if($row[1] == 0)
$columnname = "login";
else
$columnname = "ipaddress";

$querydate=date("d-m-Y");
#$querydate=date("26-11-2018");# for debug
$datestart=strtotime($querydate);
$dateend=strtotime($querydate) + 86400;

$queryOneAliasTraffic="
 	SELECT 
	   SUM(sizeinbytes) as s
 
		   FROM scsq_quicktraffic 
		   WHERE date>".$datestart." 
		     AND date<".$dateend."
	             AND site NOT IN (".$goodSitesList.")
		     AND ".$columnname." = ".$row[0]." 
		   GROUP BY crc32(".$columnname.")
	;";

	$result=mysqli_query($connection,$queryOneAliasTraffic,MYSQLI_USE_RESULT) or die (mysqli_error());

	$SumSizeTraffic=mysqli_fetch_array($result,MYSQLI_NUM);
		mysqli_free_result($result);

    return $SumSizeTraffic[0]/1000/1000;

    
}

  function GetAliasMonthTraffic($aliasid,$goodSitesList) #по алиасу возвращаем его дневной траффик
  {

$connection = $this->GetConnectionDB();

$queryAlias = "
 	SELECT 
	   tableid,
	   typeid
 		   FROM scsq_alias 
		   WHERE id=".$aliasid."
	;";

	$result=mysqli_query($connection,$queryAlias,MYSQLI_USE_RESULT) or die (mysqli_error());

	$row=mysqli_fetch_array($result,MYSQLI_NUM);
		mysqli_free_result($result);

if($row[1] == 0)
$columnname = "login";
else
$columnname = "ipaddress";

$querydate=date("d-m-Y");
#$querydate=date("26-11-2018");# for debug
list($day,$month,$year) = preg_split('/[\/\.-]+/', $querydate);
$querydate=$month."-".$year;
$numdaysinmonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$datestart=mktime(0,0,0,$month,1,$year);
$dateend=$datestart + 86400*$numdaysinmonth;

$queryOneAliasTraffic="
 	SELECT 
	   SUM(sizeinbytes) as s
 
		   FROM scsq_quicktraffic 
		   WHERE date>".$datestart." 
		     AND date<".$dateend."
	             AND site NOT IN (".$goodSitesList.")
		     AND ".$columnname." = ".$row[0]." 
		   GROUP BY crc32(".$columnname.")
	;";

	$result=mysqli_query($connection,$queryOneAliasTraffic,MYSQLI_USE_RESULT) or die (mysqli_error());

	$SumSizeTraffic=mysqli_fetch_array($result,MYSQLI_NUM);
		mysqli_free_result($result);

    return $SumSizeTraffic[0]/1000/1000;

    
}

  function Install()
  {


$connection = $this->GetConnectionDB();
# Table structure for table `scsq_mod_quotas`

		$CreateTable = "
		CREATE TABLE IF NOT EXISTS `scsq_mod_quotas` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `aliasid` int(11) NOT NULL,
			  `quota` int(11) DEFAULT '0',
			  `status` int(4) DEFAULT '0',
			  `active` int(10) DEFAULT '0',
			  `quotaday` int(11) DEFAULT '0',
			  `quotamonth` int(11) DEFAULT '0',
			  `sumday` int(11) DEFAULT '0',
			  `summonth` int(11) DEFAULT '0',
			  `datemodified` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";

		$UpdateModules = "
		INSERT INTO `scsq_modules` (name,link) VALUES ('Quotas','modules/Quotas/index.php');";


		$result=mysqli_query($connection,$CreateTable,MYSQLI_USE_RESULT) or die (mysqli_error());

		mysqli_free_result($result);
		
		$result=mysqli_query($connection,$UpdateModules,MYSQLI_USE_RESULT) or die (mysqli_error());

		mysqli_free_result($result);
		


		echo "Установлено<br /><br />";
		echo "<a href=right.php?srv=".$srv."&id=7 target=right>".$_lang['stBACK']."</a>";
   }
  
 function Uninstall() #добавить LANG
  {

		$connection = $this->GetConnectionDB();
		$query = "
		DROP TABLE IF EXISTS `scsq_mod_quotas`;
		";

		$UpdateModules = "
		DELETE FROM `scsq_modules` where name = 'QUOTAS';";

		$result=mysqli_query($connection,$query,MYSQLI_USE_RESULT) or die (mysqli_error());

		mysqli_free_result($result);

		$result=mysqli_query($connection,$UpdateModules,MYSQLI_USE_RESULT) or die (mysqli_error());

		mysqli_free_result($result);

		echo "Удалено<br /><br />";
		echo "<a href=right.php?srv=".$srv."&id=7 target=right>".$_lang['stBACK']."</a>";

  }


}
?>
