<?php
#20191023

class Quotas
{

function __construct($variables){ // 
    $this->vars = $variables;
    	
  
	$this->ssq = new ScreenSquid($variables); #получим экземпляр класса и будем уже туда закиыдвать запросы на исполнение

	include("langs/".$this->vars['language']); #подтянем файл языка
  	
	$this->lang = $_lang;
}

  function GetDesc()
  {
	  
	  return $this->lang['stMODULEDESC']; 
   
  }

 

  function GetAliasDayTraffic($aliasid,$goodSitesList) #по алиасу возвращаем его дневной траффик
  {



$queryAlias = "
 	SELECT 
	   tableid,
	   typeid
 		   FROM scsq_alias 
		   WHERE id=".$aliasid."
	;";

	$result=$this->ssq->query($queryAlias) or die ("Can`t get alias traffic");

	$row=$this->ssq->fetch_array($result);
		$this->ssq->free_result($result);

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
		   GROUP BY ".$columnname."
	;";

	$result=$this->ssq->query($queryOneAliasTraffic) or die ("Can`t get one alias traffic");

	$SumSizeTraffic=$this->ssq->fetch_array($result);
		$this->ssq->free_result($result);

    return $SumSizeTraffic[0]/1000/1000;

    
}

 function GetAliasValue($aliasid) #по алиасу возвращаем элемент из таблицы логинов/ip адресов
  {


$queryAlias = "
 	SELECT 
	   tableid,
	   typeid
 		   FROM scsq_alias 
		   WHERE id=".$aliasid."
	;";

	$result=$this->ssq->query($queryAlias) or die ("Can`t get alias");

	$row=$this->ssq->fetch_array($result);
		$this->ssq->free_result($result);

if($row[1] == 0)
$tablename = "logins";
else
$tablename = "ipaddress";


$queryOneAliasValue="
 	SELECT 
	   name
 		   FROM scsq_".$tablename." 
		   WHERE id =".$row[0]." 
	 ;";

	$result=$this->ssq->query($queryOneAliasValue) or die ('Error: Cant get login/ipaddress for tableid');
	$row=$this->ssq->fetch_array($result);
	$this->ssq->free_result($result);

    return $row[0];

    
}

function GetQuotaStatusByAlias($aliasid) #по алиасу возвращаем статус 0 - норма, >0 превышение квоты
  {

$aliasid = $aliasid + 0; #защита от пустых значений

$queryAlias = "
 	SELECT 
	   status
 		   FROM scsq_mod_quotas 
		   WHERE aliasid=".$aliasid."
	;";

	$result=$this->ssq->query($queryAlias) or die ("Can`t get quota status by alias");
	
	$row=$this->ssq->fetch_array($result);
	

	$this->ssq->free_result($result);


    return $row[0];

    
}



  function GetAliasMonthTraffic($aliasid,$goodSitesList) #по алиасу возвращаем его дневной траффик
  {


$queryAlias = "
 	SELECT 
	   tableid,
	   typeid
 		   FROM scsq_alias 
		   WHERE id=".$aliasid."
	;";

	$result=$this->ssq->query($queryAlias) or die ("Can`t query alias table");

	$row=$this->ssq->fetch_array($result,MYSQLI_NUM);
		$this->ssq->free_result($result);

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
		   GROUP BY ".$columnname."
	;";

	$result=$this->ssq->query($queryOneAliasTraffic) or die ("Can`t get one alias traffic");

	$SumSizeTraffic=$this->ssq->fetch_array($result);
		$this->ssq->free_result($result);

    return $SumSizeTraffic[0]/1000/1000;

    
}

  function Install()
  {



# Table structure for table `scsq_mod_quotas`

		if($this->vars['dbtype']==0) #mysql version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_quotas (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  aliasid int(11) NOT NULL,
			  quota int(11) DEFAULT '0',
			  status int(4) DEFAULT '0',
			  active int(10) DEFAULT '0',
			  quotaday int(11) DEFAULT '0',
			  quotamonth int(11) DEFAULT '0',
			  sumday int(11) DEFAULT '0',
			  summonth int(11) DEFAULT '0',
			  datemodified int(11) DEFAULT NULL,
			  PRIMARY KEY (id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
		
		if($this->vars['dbtype']==1) #postgre version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_quotas (
			  id serial NOT NULL,
			  aliasid integer NOT NULL,
			  quota integer DEFAULT 0,
			  status integer DEFAULT 0,
			  active integer DEFAULT 0,
			  quotaday integer DEFAULT 0,
			  quotamonth integer DEFAULT 0,
			  sumday integer DEFAULT 0,
			  summonth integer DEFAULT 0,
			  datemodified integer DEFAULT NULL,
			  CONSTRAINT scsq_mod_quotas_pkey PRIMARY KEY (id)
			);
		ALTER TABLE scsq_mod_quotas
			OWNER TO postgres;
		";


		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('Quotas','modules/Quotas/index.php');";


		$result=$this->ssq->query($CreateTable) or die ("Can`t install module!");

		$this->ssq->free_result($result);
		
		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);
		


		echo "Установлено<br /><br />";
		echo "<a href=right.php?srv=".$srv."&id=7 target=right>".$_lang['stBACK']."</a>";
   }
  
 function Uninstall() #добавить LANG
  {

		$query = "
		DROP TABLE IF EXISTS scsq_mod_quotas;
		";

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'Quotas';";

		$result=$this->ssq->query($query) or die ("Can`t uninstall module!");

		$this->ssq->free_result($result);

		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);

		echo "Удалено<br /><br />";
		echo "<a href=right.php?srv=".$srv."&id=7 target=right>".$_lang['stBACK']."</a>";

  }


}
?>
