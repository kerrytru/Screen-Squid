<?php



class Cache
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
?>
