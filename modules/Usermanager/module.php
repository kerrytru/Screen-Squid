<?php

#Build date Friday 24th of April 2020 09:15:27 AM
#Build revision 1.1


class Usermanager
{

function __construct($variables){ // 
    $this->vars = $variables;
    	
  
	$this->ssq = new ScreenSquid($variables); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение

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
 
  function Install()
  {



# Table structure for table `scsq_mod_usermanager`

		if($this->vars['dbtype']==0) #mysql version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_usermanager (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  aliasid int(11) NOT NULL,
			  active int(10) DEFAULT '0',
			  datemodified int(11) DEFAULT NULL,
			  PRIMARY KEY (id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
		
		if($this->vars['dbtype']==1) #postgre version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_usermanager (
			  id serial NOT NULL,
			  aliasid integer NOT NULL,
			  active integer DEFAULT 0,
			  datemodified integer DEFAULT NULL,
			  CONSTRAINT scsq_mod_usermanager_pkey PRIMARY KEY (id)
			);
		ALTER TABLE scsq_mod_usermanager
			OWNER TO postgres;
		";


		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('Usermanager','modules/Usermanager/index.php');";


		

		$result=$this->ssq->query($CreateTable) or die ("Can`t install module!");

		$this->ssq->free_result($result);
		
		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);
		

		#copy all created alias to usermanager (only when first install)
		$CopyAlias = "
		INSERT INTO scsq_mod_usermanager (aliasid) select id from scsq_alias;";

		$result=$this->ssq->query($CopyAlias) or die ("Can`t copy aliases to module table");

		$this->ssq->free_result($result);


		echo "".$this->lang['stINSTALLED']."<br /><br />";
	 }
  
 function Uninstall() #добавить LANG
  {

		$query = "
		DROP TABLE IF EXISTS scsq_mod_usermanager;
		";

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'Usermanager';";

		$result=$this->ssq->query($query) or die ("Can`t uninstall module!");

		$this->ssq->free_result($result);

		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);

		echo "".$this->lang['stUNINSTALLED']."<br /><br />";

  }


}
?>
