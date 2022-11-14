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
*                         File Mod     > <!#FT> 2022/09/20 21:26:18.829 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/




class PrivateAuth
{

function __construct($variables){ // 
    $this->vars = $variables;
    	
	include_once(''.$this->vars['root_dir'].'/lib/functions/function.database.php');
	include_once(''.$this->vars['root_dir'].'/lib/functions/function.modules.php');

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

	if(file_put_contents('pass',$passhash)) echo "<script language=javascript>alert('Password is setted')</script>";
		else
			echo "<script language=javascript>alert('Password not setted')</script>";
  }

  #Функция сброса пароля
  function ClearPassword(){
	if(unlink('pass')) echo "<script language=javascript>alert('Password cleared')</script>";
		else echo "<script language=javascript>alert('Password not cleared')</script>";
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
?>
