<?php

#Build date Thursday 7th of May 2020 18:49:01 PM
#Build revision 1.2


class Usermanager
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
 
  function Install()
  {



# Table structure for table `scsq_mod_usermanager`

		if($this->vars['connectionParams']['dbtype']==0) #mysql version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_usermanager (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  aliasid int(11) NOT NULL,
			  active int(10) DEFAULT '0',
			  datemodified int(11) DEFAULT NULL,
			  PRIMARY KEY (id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
		
		if($this->vars['connectionParams']['dbtype']==1) #postgre version
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


		

		doQuery($this->vars, $CreateTable) or die ("Can`t install module!");

		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");


		#copy all created alias to usermanager (only when first install)
		$CopyAlias = "
		INSERT INTO scsq_mod_usermanager (aliasid) select id from scsq_alias;";

		doQuery($this->vars, $CopyAlias) or die ("Can`t copy aliases to module table");

		echo "".$this->lang['stINSTALLED']."<br /><br />";
	 }
  
 function Uninstall() #добавить LANG
  {

		$query = "
		DROP TABLE IF EXISTS scsq_mod_usermanager;
		";

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'Usermanager';";

		doQuery($this->vars,$query) or die ("Can`t uninstall module!");
		doQuery($this->vars,$UpdateModules) or die ("Can`t update module table");

	
		echo "".$this->lang['stUNINSTALLED']."<br /><br />";

  }


}
?>
