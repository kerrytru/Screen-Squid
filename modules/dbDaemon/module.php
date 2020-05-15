<?php

#Build date Friday 15th of May 2020 13:18:35 PM
#Build revision 1.3

class dbDaemon
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



# Table structure for table `scsq_mod_dbDaemon`

		if($this->vars['dbtype']==0) #mysql version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_dbDaemon (
			  id bigint NOT NULL AUTO_INCREMENT,
			  date int(11) NOT NULL,
			  lineitem varchar(4000) DEFAULT NULL,
			  numproxy tinyint(4) DEFAULT '0',
			  PRIMARY KEY (id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
		
				if($this->vars['dbtype']==1) #postgre version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_dbDaemon (
			  id serial NOT NULL,
			  date integer NOT NULL,
			  lineitem text,
			  numproxy integer DEFAULT 0,
			  CONSTRAINT scsq_mod_dbDaemon_pkey PRIMARY KEY (id)
			);
		ALTER TABLE scsq_mod_dbDaemon
			OWNER TO postgres;
		";
		
		
		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('dbDaemon','modules/dbDaemon/index.php');";


		

		$result=$this->ssq->query($CreateTable) or die ("Can`t install module!");

		$this->ssq->free_result($result);
		
		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);
		

		echo "".$this->lang['stINSTALLED']."<br /><br />";
	 }
  
 function Uninstall() #добавить LANG
  {

		$query = "
		DROP TABLE IF EXISTS scsq_mod_dbDaemon;
		";

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'dbDaemon';";

		$result=$this->ssq->query($query) or die ("Can`t uninstall module!");

		$this->ssq->free_result($result);

		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);

		echo "".$this->lang['stUNINSTALLED']."<br /><br />";

  }


}
?>
