<?php

#Build date Thursday 7th of May 2020 18:37:27 PM
#Build revision 1.2

class Categorylist
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



  function Install()
  {


# Table structure for table `scsq_mod_categorylist`

		if($this->vars['connectionParams']['dbtype']==0 ) {

		$CreateTable = "
			CREATE TABLE IF NOT EXISTS scsq_mod_categorylist (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  category varchar(100) NOT NULL,
			  site varchar(300) NOT NULL,
			  PRIMARY KEY (id),
			  KEY site (site)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";

		$queryAddColumn = "
		ALTER TABLE scsq_quicktraffic ADD category VARCHAR( 30 ) NULL DEFAULT NULL ;
		";
		}

		if($this->vars['connectionParams']['dbtype']==1){



		$CreateTable = "
			CREATE TABLE IF NOT EXISTS scsq_mod_categorylist (
			  id serial NOT NULL,
			  category text NOT NULL,
			  site text NOT NULL,
			  CONSTRAINT scsq_mod_categorylist_pkey PRIMARY KEY (id)
			 
			) ;
			ALTER TABLE scsq_mod_categorylist
			OWNER TO postgres;
		";

		$queryAddColumn = "
		ALTER TABLE scsq_quicktraffic ADD category text NULL DEFAULT NULL ;
		";
		}



		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('Categorylist','modules/Categorylist/index.php');";


		doQuery($this->vars, $CreateTable) or die ("Can`t create table for Categories");


		doQuery($this->vars, $queryAddColumn) or die ("Can`t add column Category");

		
		doQuery($this->vars, $UpdateModules) or die ("Can`t update modules table");

		


		echo "".$this->lang['stINSTALLED']."<br /><br />";
 }
  
 function Uninstall() #добавить LANG
  {

		
		$query = "
		DROP TABLE IF EXISTS scsq_mod_categorylist;
		";

		$queryDropColumn = "
		ALTER TABLE scsq_quicktraffic  DROP category;
		";


		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'Categorylist';";

		doQuery($this->vars, $query) or die ("Can`t drop table for Categories");

		doQuery($this->vars, $queryDropColumn) or die ("Can`t drop column Category");

		doQuery($this->vars, $UpdateModules) or die ("Can`t update modules");


		echo "".$this->lang['stUNINSTALLED']."<br /><br />";
		
  }


}
?>
