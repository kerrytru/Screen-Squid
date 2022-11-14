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
*                         File Birth   > <!#FB> 2022/09/19 20:34:00.897 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/09/19 20:36:57.273 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






class dictionary
{

function __construct($variables){ // 
    $this->vars = $variables;
    

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
	echo "<script language=javascript>alert('This is system module, its already installed')</script>";

	 }
  
 function Uninstall() 
  {
	echo "<script language=javascript>alert('This is system module, you cant uninstall it')</script>";

  }

}


#Функции работы со списком логинов
function doPrintAllLogins($globalSS){


    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    include_once(''.$globalSS['root_dir'].'/lib/functions/function.getreport.php');
    
    $_lang = $globalSS['lang'];

       
    $queryAll="
        SELECT 
			'',
			name 
			from scsq_logins
	   
		order by name desc";

    

    $result = doFetchQuery($globalSS,$queryAll);


	$json_result=json_encode(doFetchQuery($globalSS,$queryAll));
	if($globalSS['makecsv']==1){
		doMakeCSV($globalSS,$json_result);
		return;
	}

    $numrow=1;
	echo "<br />";

	echo "<table id=report_table_id class=datatable>";
	echo "<tr>";
	echo "<th>#</th>";
	echo "<th>".$_lang['stLOGIN']."</th>";

	echo "</tr>";

	foreach ($result as $line) {
			  
		echo "<tr>";

		echo "<td>$numrow</td>";
		echo "<td>".$line[1]."</td>";
		
		
	echo "	</tr>";
	$numrow++;
	}

        
   echo "</table>";
   echo "<br />";
    
    }


	#Функции работы со списком IP адресов
function doPrintAllIpaddress($globalSS){


    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    include_once(''.$globalSS['root_dir'].'/lib/functions/function.getreport.php');
    
    $_lang = $globalSS['lang'];

       
    $queryAll="
        SELECT 
			'',
			name 
			from scsq_ipaddress
	   
		order by name desc";

    

    $result = doFetchQuery($globalSS,$queryAll);


	$json_result=json_encode(doFetchQuery($globalSS,$queryAll));
	if($globalSS['makecsv']==1){
		doMakeCSV($globalSS,$json_result);
		return;
	}

    $numrow=1;
	echo "<br />";

	echo "<table id=report_table_id class=datatable>";
	echo "<tr>";
	echo "<th>#</th>";
	echo "<th>".$_lang['stIPADDRESS']."</th>";

	echo "</tr>";

	foreach ($result as $line) {
			  
		echo "<tr>";

		echo "<td>$numrow</td>";
		echo "<td>".$line[1]."</td>";
		
		
	echo "	</tr>";
	$numrow++;
	}

        
   echo "</table>";
   echo "<br />";
    
    }




?>
