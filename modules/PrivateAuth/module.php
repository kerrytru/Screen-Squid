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
*                         File Birth   > <!#FB> 2022/04/11 23:57:47.356 </#FB>                                         *
*                         File Mod     > <!#FT> 2024/06/25 19:40:39.564 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 2.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/




class PrivateAuth
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

#Функция записи пароля в файл
  function SetPassword($password){
	$passhash=md5(md5($password));

	$queryUpdate="UPDATE scsq_users SET pass='".$passhash."' WHERE login='admin'";

	if(doQuery($this->vars,$queryUpdate)) echo "<script language=javascript>alert('Password is setted')</script>";
		else
			echo "<script language=javascript>alert('Password not setted')</script>";
  }

  #Функция сброса пароля
  function ClearPassword(){
	$passhash=md5(md5("admin"));

	$queryUpdate="UPDATE scsq_users SET pass='".$passhash."' where login='admin'";

	if(doQuery($this->vars,$queryUpdate)) echo "<script language=javascript>alert('Password cleared')</script>";
		else echo "<script language=javascript>alert('Password not cleared')</script>";
  }



  function Install()
  	{
		file_put_contents(__DIR__.'/enabled','1');
		
	echo "<script language=javascript>alert('Installed')</script>";

	 }
  
 function Uninstall() 
  {
	unlink(__DIR__.'/enabled');
	echo "<script language=javascript>alert('Uninstalled')</script>";

  }


}
?>
