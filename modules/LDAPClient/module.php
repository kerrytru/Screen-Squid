<?php

#Build date Thursday 7th of May 2020 18:46:02 PM
#Build revision 1.2

class LDAPClient
{

var $ldap_conn = false;

function __construct($variables){ // 

	
    $this->vars = $variables;

	    #в зависимости от типа БД, подключаем разные модули
		if($this->vars['dbtype']==0)
		$this->ssq = new m_ScreenSquid($variables); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение
	
		if($this->vars['dbtype']==1)
		$this->ssq = new p_ScreenSquid($variables); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение
	
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


  $this->ldap_conn = @ldap_connect($this->vars['ldapserver']) or die("Could not connect to LDAP server.");

 return true;

 }
 

  function GetUsernameByLogin($loginname) #по алиасу возвращаем его дневной траффик
  {

	if(!$this->GetConnectionLDAP())
		$this->GetConnectionLDAP();

if( $this->ldap_conn ) {
	
    // binding to ldap server
    $ldapbind = ldap_bind( $this->ldap_conn, $this->vars['ldapuser'], $this->vars['ldappass']) or die ("Error trying to bind: ".ldap_error($this->ldap_conn));
    // verify binding
    if ($ldapbind) {
       // echo "LDAP bind successful...<br /><br />";
       
       
        $result = ldap_search( $this->ldap_conn,$this->vars['ldaptree'], "(uid=".$loginname.")") or die ("Error in search query: ".ldap_error($this->ldap_conn));
        $data = ldap_get_entries( $this->ldap_conn, $result);
       
      
        // iterate over array and print data for each entry
     //   echo '<h1>Show me the users</h1>';
        for ($i=0; $i<$data["count"]; $i++) {
    //        echo "dn is: ". $data[$i]["dn"] ."<br />";
			  $username=$data[$i]["cn"][0];
         //   echo "User: ". $data[$i]["cn"][0] ."<br />";
    //        echo "Uid: ". $data[$i]["uid"][0] ."<br />";
    
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


		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);
		


		echo "".$this->lang['stINSTALLED']."<br /><br />";
 }
  
 function Uninstall() #добавить LANG
  {

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'LDAPClient';";

		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);

		echo "".$this->lang['stUNINSTALLED']."<br /><br />";
	
  }


}
?>
