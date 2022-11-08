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
*                         File Birth   > <!#FB> 2022/09/28 21:52:47.271 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/11/08 20:46:17.464 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






class ExportRep
{

function __construct($variables){ // 
    $this->vars = $variables;
    	
  
	include_once(''.$this->vars['root_dir'].'/lib/functions/function.database.php');
	include_once(''.$this->vars['root_dir'].'/lib/functions/function.reportmisc.php');
		
	
	if (file_exists("langs/".$this->vars['language']))
		include("langs/".$this->vars['language']);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 

	if (file_exists("../../lang/".$this->vars['language']))
		include("../../lang/".$this->vars['language']); #подтянем глобальный файл языка
  	
	$this->lang = $_lang;
}

  function GetDesc()
  {
	  
	  return $this->lang['stMODULEDESC']; 
   
  }

function CreateLoginsPDF($repvars)
  {


$get = array(
	'srv'  => $this->vars['connectionParams']['srv'],
	'id' => $repvars['id'],
	'loginid' => $repvars['loginid'],
	'loginname' => $repvars['loginname'],
	'typeid' => $repvars['typeid'],
	'date' => $repvars['querydate'],
	'date2' => $repvars['querydate2'],
	'pdf' => '1',
	'external' => '1'
);
 

$ch = curl_init($this->vars['root_http']."/reports/reports.php?" . http_build_query($get));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$html = curl_exec($ch);
curl_close($ch);


  }


function CreateIpaddressPDF($repvars)
  {

	$get = array(
		'srv'  => $this->vars['connectionParams']['srv'],
		'id' => $repvars['id'],
		'ipaddressid' => $repvars['ipaddressid'],
		'ipaddressname' => $repvars['ipaddressname'],
		'typeid' => $repvars['typeid'],
		'date' => $repvars['querydate'],
		'date2' => $repvars['querydate2'],
		'pdf' => '1',
		'external' => '1'
	);
	 
	
	$ch = curl_init($this->vars['root_http']."/reports/reports.php?" . http_build_query($get));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$html = curl_exec($ch);
	curl_close($ch);

}
   
function CreateLoginsCSV($repvars)
  {

	$get = array(
		'srv'  => $this->vars['connectionParams']['srv'],
		'id' => $repvars['id'],
		'loginid' => $repvars['loginid'],
		'loginname' => $repvars['loginname'],
		'typeid' => $repvars['typeid'],
		'date' => $repvars['querydate'],
		'date2' => $repvars['querydate2'],
		'csv' => '1',
		'external' => '1'
	);
	 
	
	$ch = curl_init($this->vars['root_http']."/reports/reports.php?" . http_build_query($get));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$html = curl_exec($ch);
	curl_close($ch);

  }   
   

function CreateIpaddressCSV($repvars)
  {

	$get = array(
		'srv'  => $this->vars['connectionParams']['srv'],
		'id' => $repvars['id'],
		'ipaddressid' => $repvars['ipaddressid'],
		'ipaddressname' => $repvars['ipaddressname'],
		'typeid' => $repvars['typeid'],
		'date' => $repvars['querydate'],
		'date2' => $repvars['querydate2'],
		'csv' => '1',
		'external' => '1'
	);
	 
	
	$ch = curl_init($this->vars['root_http']."/reports/reports.php?" . http_build_query($get));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$html = curl_exec($ch);
	curl_close($ch);

  } 

   

  function Install()
  {

	#если модуль уже есть, то вернемся.
	if(doQueryExistsModule($this->vars,'ExportRep')>0) {
		echo "<script language=javascript>alert('Module already installed')</script>";
		return;
	}

		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('ExportRep','modules/ExportRep/index.php');";

		
		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");



		echo "<script language=javascript>alert('".$this->lang['stINSTALLED']."')</script>";
	 }
  
 function Uninstall() #добавить LANG
  {

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'ExportRep';";

		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");

		echo "<script language=javascript>alert('".$this->lang['stUNINSTALLED']."')</script>";

  }


}
?>
