<?php

#Build date Wednesday 22nd of April 2020 16:22:56 PM
#Build revision 1.0

class dbDaemon
{

function __construct($variables){ // 
    $this->vars = $variables;
    	
  
	$this->ssq = new ScreenSquid($variables); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение

	include("langs/".$this->vars['language']); #подтянем файл языка
  	
	$this->lang = $_lang;
}

  function GetDesc()
  {
	  
	  return $this->lang['stMODULEDESC']; 
   
  }

 

  


  function Install()
  {



# Table structure for table `scsq_mod_usermanager`

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
		
		
		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('dbDaemon','modules/Usermanager/index.php');";


		

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
