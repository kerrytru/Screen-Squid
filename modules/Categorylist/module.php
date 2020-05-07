<?php

#Build date Thursday 7th of May 2020 18:37:27 PM
#Build revision 1.2

class Categorylist
{

function __construct($variables){ // 
    $this->vars = $variables;
    
	    #в зависимости от типа БД, подключаем разные модули
		if($this->vars['dbtype']==0)
		$this->ssq = new m_ScreenSquid($variables); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение
	
		if($this->vars['dbtype']==1)
		$this->ssq = new p_ScreenSquid($variables); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение
	
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

		if($this->vars['dbtype']==0 ) {

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

		if($this->vars['dbtype']==1){



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


		$result=$this->ssq->query($CreateTable) or die ("Can`t create table for Categories");

		$this->ssq->free_result($result);

		$result=$this->ssq->query($queryAddColumn) or die ("Can`t add column Category");

		$this->ssq->free_result($result);
		
		$result=$this->ssq->query($UpdateModules) or die ("Can`t update modules table");

		$this->ssq->free_result($result);
		


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

		$result=$this->ssq->query($query) or die ("Can`t drop table for Categories");

		$this->ssq->free_result($result);

		$result=$this->ssq->query($queryDropColumn) or die ("Can`t drop column Category");

		$this->ssq->free_result($result);

		$result=$this->ssq->query($UpdateModules) or die ("Can`t update modules");

		$this->ssq->free_result($result);

		echo "".$this->lang['stUNINSTALLED']."<br /><br />";
		
  }


}
?>
