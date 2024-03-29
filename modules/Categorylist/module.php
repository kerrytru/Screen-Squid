<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> module.php </#FN>                                                      
*                         File Birth   > <!#FB> 2022/09/28 21:52:47.265 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/10/22 21:43:46.145 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/



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


		#если модуль уже есть, то вернемся.
		if(doQueryExistsModule($this->vars,'Categorylist')>0) {
			echo "<script language=javascript>alert('Module already installed')</script>";
			return;
		}


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

	
		}

		if($this->vars['connectionParams']['dbtype']==1){



		$CreateTable = "
			CREATE TABLE IF NOT EXISTS scsq_mod_categorylist (
			  id serial NOT NULL,
			  category text NOT NULL,
			  site text NOT NULL,
			  CONSTRAINT scsq_mod_categorylist_pkey PRIMARY KEY (id)
			 
			) ;

		";
		}


		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('Categorylist','modules/Categorylist/index.php');";


		doQuery($this->vars, $CreateTable) or die ("Can`t create table for Categories");


		
		doQuery($this->vars, $UpdateModules) or die ("Can`t update modules table");

		


		echo "<script language=javascript>alert('".$this->lang['stINSTALLED']."')</script>";
 }
  
 function Uninstall() #добавить LANG
  {

		
		$query = "
		DROP TABLE IF EXISTS scsq_mod_categorylist;
		";




		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'Categorylist';";

		doQuery($this->vars, $query) or die ("Can`t drop table for Categories");

	
		doQuery($this->vars, $UpdateModules) or die ("Can`t update modules");


		echo "<script language=javascript>alert('".$this->lang['stUNINSTALLED']."')</script>";
		
  }


}
?>
