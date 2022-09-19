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
*                         File Birth   > <!#FB> 2022/04/11 23:57:47.378 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/09/19 21:44:56.538 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.4.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






class LDAPClient
{

var $ldap_conn = false;

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

  function GetConnectionLDAP()
  {

    try {
        $this->ldap_conn = @ldap_connect($this->vars['connectionParams']['ldapserver']) or die("Could not connect to LDAP server.");
        ldap_set_option($this->ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
    } catch (\Error $e) {
        echo 'Error: Something going wrong. I cant connect to LDAP server. Check have you php-ldap module and correct config.php in module directory';
        die();
    }

 return true;

 }
 

  function GetUsernameByLogin($loginname) #по алиасу возвращаем его дневной траффик
  {

	if(!$this->GetConnectionLDAP())
        $this->GetConnectionLDAP();
        
        //
if( $this->ldap_conn ) {

    //Инициализируем переменную
    $username="(not found)";
    // binding to ldap server
    $ldapbind = ldap_bind( $this->ldap_conn, $this->vars['connectionParams']['ldapuser'], $this->vars['connectionParams']['ldappass']) or die ("Error trying to bind: ".ldap_error($this->ldap_conn));

    // verify binding
    if ($ldapbind) {
       // echo "LDAP bind successful...<br /><br />";
       
       //Попробуем отловить проблемные логины. Со всякими спезнаками. Их будем просто пропускать. 
       //Просто иначе полуаем Bad search filter.
       try {
        $result = ldap_search( $this->ldap_conn,$this->vars['connectionParams']['ldaptree'], "(".$this->vars['connectionParams']['fldUsername']."=".$loginname.")");
        $data = ldap_get_entries( $this->ldap_conn, $result);
  
         } catch (\Exception $e) {
            $username="(not found)";
         }
        // iterate over array and print data for each entry
        //echo '<h1>Show me the users</h1>';
        for ($i=0; $i<$data["count"]; $i++) {
           // echo "dn is: ". $data[$i]["dn"] ."<br />";
			  $username=$data[$i]["cn"][0];
           // echo "User: ". $data[$i]["cn"][0] ."<br />";
           // echo "Uid: ". $data[$i]["uid"][0] ."<br />";
    
        }
       
    } else {
        echo "LDAP bind failed...";
    }

return $username;


}

// all done? clean up
ldap_close($this->ldap_conn);
   
}


  function Install()
  {



		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('LDAPClient','modules/LDAPClient/index.php');";


		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");


		echo "".$this->lang['stINSTALLED']."<br /><br />";
 }
  
 function Uninstall() #добавить LANG
  {

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'LDAPClient';";

		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");

		echo "".$this->lang['stUNINSTALLED']."<br /><br />";
	
  }


}
?>
