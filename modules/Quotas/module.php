<?php

#Build date Thursday 7th of May 2020 18:47:37 PM
#Build revision 1.2

class Quotas
{

function __construct($variables){ // 
    $this->vars = $variables;
    	
  
	include_once(''.$this->vars['root_dir'].'/lib/functions/function.database.php');
	
	if (file_exists("langs/".$this->vars['language']))
		include("langs/".$this->vars['language']);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 
		 	
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

	$row=doFetchOneQuery($queryAlias) or die ("Can`t get alias traffic");

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

	$SumSizeTraffic=doFetchOneQuery($this->vars, $queryOneAliasTraffic) or die ("Can`t get one alias traffic");

#Задействовать другую функцию!
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

	$row=doFetchOneQuery($this->vars, $queryAlias) or die ("Can`t get alias");

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

	$row=doFetchOneQuery($this->vars, $queryOneAliasValue) or die ('Error: Cant get login/ipaddress for tableid');


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

	$row=doFetchOneQuery($this->vars, $queryAlias) or die ("Can`t get quota status by alias");
	
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

	$row=doFetchOneQuery($queryAlias) or die ("Can`t query alias table");

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

	$SumSizeTraffic=doFetchQuery($this->vars, $queryOneAliasTraffic) or die ("Can`t get one alias traffic");

    return $SumSizeTraffic[0]/1000/1000;

    
}

  function Install()
  {



# Table structure for table `scsq_mod_quotas`

		if($this->vars['connectionParams']['dbtype']==0) #mysql version
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
		
		if($this->vars['connectionParams']['dbtype']==1) #postgre version
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


		doQuery($this-vars, $CreateTable) or die ("Can`t install module!");
		
		doQuery($this-vars, $UpdateModules) or die ("Can`t update module table");

		echo "".$this->lang['stINSTALLED']."<br /><br />";
	 }
  
 function Uninstall() #добавить LANG
  {

		$query = "
		DROP TABLE IF EXISTS scsq_mod_quotas;
		";

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'Quotas';";

		doQuery($this-vars, $query) or die ("Can`t uninstall module!");

		doQuery($this-vars, $UpdateModules) or die ("Can`t update module table");

		echo "".$this->lang['stUNINSTALLED']."<br /><br />";

  }


}
?>
