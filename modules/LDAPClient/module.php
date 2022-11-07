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
*                         File Mod     > <!#FT> 2022/11/07 21:42:33.794 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.6.0 </#FV>                                                           
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

  #just for check credentials
  function doCheckLDAPBind()
  {

    if(!$this->GetConnectionLDAP())
         $this->GetConnectionLDAP();
    
    $ldapbind = ldap_bind( $this->ldap_conn, $this->vars['connectionParams']['ldapuser'], $this->vars['connectionParams']['ldappass']);

    ldap_close($this->ldap_conn);
    return $ldapbind;


 }


  function GetConnectionLDAP()
  {

    try {
        $this->ldap_conn = @ldap_connect($this->vars['connectionParams']['ldapserver']);
        if($this->vars['connectionParams']['LDAP_OPT_PROTOCOL_VERSION']==1)
            ldap_set_option($this->ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        if($this->vars['connectionParams']['LDAP_OPT_REFERRALS']==1)
            ldap_set_option($this->ldap_conn, LDAP_OPT_REFERRALS, 0);
 
        } catch (\Error $e) {
       
        echo 'Error: Something going wrong. I cant connect to LDAP server. Check have you php-ldap module and correct config.php in module directory';
        die();
    }


    return true;


 }
 

  function GetUsernameByLogin($loginname) 
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
       //Просто иначе получаем Bad search filter.
        $result = ldap_search( $this->ldap_conn,$this->vars['connectionParams']['ldaptree'], "(".$this->vars['connectionParams']['fldUsername']."=".$loginname.")");
        if(!is_bool($result))
          $data = ldap_get_entries( $this->ldap_conn, $result);
  
        // iterate over array and print data for each entry
        //echo '<h1>Show me the users</h1>';
        for ($i=0; $i<$data["count"]; $i++) {
           // echo "dn is: ". $data[$i]["dn"] ."<br />";
			  $username=$data[$i]["cn"][0];

    
        }
       
    } else {
        echo "LDAP bind failed...";
    }

return $username;


}

// all done? clean up
ldap_close($this->ldap_conn);
   
}


function GetOneFromScheme() 
  {

	if(!$this->GetConnectionLDAP())
        $this->GetConnectionLDAP();
        
        //
if( $this->ldap_conn ) {


    // binding to ldap server
    $ldapbind = ldap_bind( $this->ldap_conn, $this->vars['connectionParams']['ldapuser'], $this->vars['connectionParams']['ldappass']) or die ("Error trying to bind: ".ldap_error($this->ldap_conn));

    // verify binding
    if ($ldapbind) {
       // echo "LDAP bind successful...<br /><br />";
       
       //Попробуем отловить проблемные логины. Со всякими спезнаками. Их будем просто пропускать. 
       //Просто иначе получаем Bad search filter.
        $result = ldap_search( $this->ldap_conn,$this->vars['connectionParams']['ldaptree'],"(cn=*)",[],0, $sizelimit=1);
        if(!is_bool($result))
          $data = ldap_get_entries( $this->ldap_conn, $result);
  
          $json_data=json_encode($data,JSON_PRETTY_PRINT);

          
         echo "<pre>$json_data</pre>";
    } else {
        echo "LDAP bind failed...";
    }


}

// all done? clean up
ldap_close($this->ldap_conn);
   
}




  function Install()
  {
	#если модуль уже есть, то вернемся.
	if(doQueryExistsModule($this->vars,'LDAPClient')>0) {
		echo "<script language=javascript>alert('Module already installed')</script>";
		return;
	}

	$UpdateParameters="INSERT INTO scsq_modules_param (id, module, param, val, switch, comment) VALUES
		(2100,'LDAPClient', 'ldapserver', 'localhost', 0, 'LDAP server IP'),
        (2101,'LDAPClient', 'ldapuser', 'cn=Manager,dc=my-domain,dc=com', 0, 'Username to connect'),
        (2102,'LDAPClient', 'ldappass', '12345678', 0, 'password to connect'),
        (2103,'LDAPClient', 'ldaptree', 'DC=my-domain,DC=com', 0, 'ldap tree'),
        (2104,'LDAPClient', 'fldUsername', 'uid', 0, 'Field where username is stored'),
        (2105,'LDAPClient', 'LDAP_OPT_PROTOCOL_VERSION', 0, 1, 'Enable LDAP_OPT_PROTOCOL_VERSION 3'),
        (2106,'LDAPClient', 'LDAP_OPT_REFERRALS', 0, 1, 'Enable LDAP_OPT_REFERRALS')";


		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('LDAPClient','modules/LDAPClient/index.php');";


		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");

        doQuery($this->vars, $UpdateParameters) or die ("Can`t update parameters table");

        echo "<script language=javascript>alert('".$this->lang['stINSTALLED']."')</script>";
 }
  
 function Uninstall() #добавить LANG
  {

        $UpdateParameters="DELETE from scsq_modules_param  where module='LDAPClient'";

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'LDAPClient';";

        doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");
        
        doQuery($this->vars, $UpdateParameters) or die ("Can`t update parameters table");

		echo "<script language=javascript>alert('".$this->lang['stUNINSTALLED']."')</script>";
	
  }


}
?>
