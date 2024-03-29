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
*                         File Birth   > <!#FB> 2022/11/01 22:02:38.065 </#FB>                                         *
*                         File Mod     > <!#FT> 2023/05/30 21:05:38.382 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






class IP2NAME
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
	if(doQueryExistsModule($this->vars,'IP2NAME')>0) {
		echo "<script language=javascript>alert('Module already installed')</script>";
		return;
	}

	$UpdateParameters="INSERT INTO scsq_modules_param (id, module, param, val, switch, comment) VALUES
		(2000,'IP2NAME', 'ip2name_file', 'ip2name_example.csv', 0, 'File to parse'),
		(2001,'IP2NAME', 'ip2name_separator', ';', 0, 'Field separator'),
		(2002,'IP2NAME', 'ip2name_table', '0', 1, '0 - ipaddress table, 1 - logins')";



		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('IP2NAME','modules/IP2NAME/index.php');";

		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");

		doQuery($this->vars, $UpdateParameters) or die ("Can`t update parameters table");


		echo "<script language=javascript>alert('".$this->lang['stINSTALLED']."')</script>";
 }
  
 function Uninstall() #добавить LANG
  {


	$UpdateParameters="DELETE from scsq_modules_param  where module='IP2NAME'";

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'IP2NAME';";

		doQuery($this->vars,$UpdateModules) or die ("Can`t update module table");

		doQuery($this->vars,$UpdateParameters) or die ("Can`t update parameters table");

		echo "<script language=javascript>alert('".$this->lang['stUNINSTALLED']."')</script>";
	
  }


}
?>
