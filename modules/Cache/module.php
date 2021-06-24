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

		echo "This is system module, its already installed<br /><br />";
	 }
  
 function Uninstall() 
  {

		echo "This is system module, you cant uninstall it<br /><br />";

  }


}
?>
