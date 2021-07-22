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
*                         File Birth   > <!#FB> 2021/07/22 18:56:55.315 </#FB>                                         *
*                         File Mod     > <!#FT> 2021/07/22 18:57:03.972 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/

class BulkLoginAlias
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

  function CreateUser($userdata) #создаем пользователя
  {


	$findUser = array();

//check if user already exist
	$queryFindUser = "SELECT id FROM scsq_logins t WHERE t.name='".$userdata['user']."';";

	$findUser=doFetchOneQuery($this->vars, $queryFindUser);
	
	//костыль если null
	if(!isset($findUser[0])) $findUser[0]=0;
	 
	
	//Если логин был найден, значит нам можно создать алиас. Иначе создадим логин и потому привяжем его к алиасу
     if($findUser[0] > 0) {
		 
		$tableid[0] = $findUser[0];
		$queryCreateAlias = "INSERT INTO scsq_alias (name, typeid,tableid) 		VALUES 
												 ('".$userdata['aliasname']."', 0,'$tableid[0]');";
												
		}
	 else
		{
		
	
		$queryCreateLogin = "INSERT INTO scsq_logins (name) VALUES ('".$userdata['user']."');"; 
		$result=doQuery($this->vars, $queryCreateLogin) or die ("Can`t create login in scsq_login");

		$tableid=doFetchOneQuery($this->vars, $queryFindUser);

		
		$queryCreateAlias = "INSERT INTO scsq_alias (name, typeid,tableid) 		VALUES 
												 ('".$userdata['aliasname']."', 0,'$tableid[0]');";
		
		}	
	//create alias
		$result=doQuery($this->vars, $queryCreateAlias) or die ("Can`t create alias in scsq_alias");

	//create quota
		$queryFindAlias = "SELECT id FROM scsq_alias t WHERE t.name='".$userdata['aliasname']."';";

		$aliasid=doFetchOneQuery($this->vars, $queryFindAlias);

		
		
		$queryCreateQuota="INSERT INTO scsq_mod_quotas (aliasid, quotaday, quotamonth,active) VALUES ($aliasid[0], ".$userdata['quotaday'].", ".$userdata['quotamonth'].",".$userdata['quotaactive'].")";

        if (!doQuery($this->vars, $queryCreateQuota)) {
            die('Error: Cant insert into scsq_mod_quotas table' );
          }
	//create user
	  $queryCreateUser="INSERT INTO scsq_mod_usermanager (aliasid, active) VALUES ($aliasid[0],".$userdata['useractive'].")";

	  if (!doQuery($this->vars, $queryCreateUser)) {
		  die('Error: Cant insert into scsq_mod_usermanager table' );
		}
  
    
}


  function DeleteUser($userdata) #удаляем пользователя
  {


$queryFindUser = "SELECT id FROM scsq_alias t WHERE t.tableid in (select id from scsq_logins where name='".$userdata['user']."') and typeid=0;";
$findAlias=doFetchOneQuery($this->vars, $queryFindUser);


$queryDeleteAlias = "DELETE FROM scsq_alias WHERE id=".$findAlias[0]."";
$result=doQuery($this->vars,$queryDeleteAlias);

$queryDeleteQuota = "DELETE FROM scsq_mod_quotas WHERE aliasid=".$findAlias[0].";";
$result=doQuery($this->vars,$queryDeleteQuota);

$queryDeleteUser = "DELETE FROM scsq_mod_usermanager WHERE aliasid=".$findAlias[0].";";
$result=doQuery($this->vars,$queryDeleteUser);

}


  function Install()
  {


		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('BulkLoginAlias','modules/BulkLoginAlias/index.php');";


		$result=doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");
		

		echo "".$this->lang['stINSTALLED']."<br /><br />";
	 }
  
 function Uninstall() #добавить LANG
  {


		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'BulkLoginAlias';";

		$result=doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");


		$result=doQuery($this->vars, $DropTable) or die ("Can`t drop table");

		

		echo "".$this->lang['stUNINSTALLED']."<br /><br />";

  }


}
?>
