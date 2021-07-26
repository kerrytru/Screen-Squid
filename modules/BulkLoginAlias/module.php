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

	//договоримся, что пароль это пароль дважды md5. Но это костыль. Нужно стандартную функцию использовать работы с алиасами
	$userdata['password']=md5(md5(trim($userdata['password'])));

	$findUser = array();

//check if user already exist
	$queryFindUser = "SELECT id FROM scsq_logins t WHERE t.name='".$userdata['user']."';";

	$findUser=doFetchOneQuery($this->vars, $queryFindUser);
	
	//костыль если null
	if(!isset($findUser[0])) $findUser[0]=0;
	 
	
	//Если логин был найден, значит нам можно создать алиас. Иначе создадим логин и потому привяжем его к алиасу
    if($findUser[0] > 0) {


		$tableid[0] = $findUser[0];

		//но сначала, посмотрим. Может алиас уже есть? 
		$queryFindAlias = "SELECT id FROM scsq_alias t WHERE t.tableid='".$tableid[0]."';";

		$findAlias=doFetchOneQuery($this->vars, $queryFindAlias);

		//костыль если null
		if(!isset($findAlias[0])) $findAlias[0]=0;

		
		if($findAlias[0] > 0) {
		
			$queryUpdateAlias = "UPDATE scsq_alias t SET  name='".$userdata['aliasname']."',
														typeid=0,
														tableid='$tableid[0]',
														userlogin='".$userdata['user']."',
														password='".$userdata['password']."',
														active=".$userdata['useractive']." where t.id=$findAlias[0];";			

			}
		else
			{
			
			$queryCreateAlias = "INSERT INTO scsq_alias (name, typeid,tableid,userlogin,password,active) 		VALUES 
													 ('".$userdata['aliasname']."', 0,'$tableid[0]', '".$userdata['user']."','".$userdata['password']."','".$userdata['useractive']."');";
			}									
		}
	 else
		{
		
	
		$queryCreateLogin = "INSERT INTO scsq_logins (name) VALUES ('".$userdata['user']."');"; 
		$result=doQuery($this->vars, $queryCreateLogin) or die ("Can`t create login in scsq_login");

		$tableid=doFetchOneQuery($this->vars, $queryFindUser);

		
		$queryCreateAlias = "INSERT INTO scsq_alias (name, typeid,tableid,userlogin,password,active) 		VALUES 
												 ('".$userdata['aliasname']."', 0,'$tableid[0]', '".$userdata['user']."','".$userdata['password']."','".$userdata['useractive']."');";
		
		}	

		
	//update or create alias
	if($findAlias[0] > 0) 
		$result=doQuery($this->vars, $queryUpdateAlias) or die ("Can`t update alias in scsq_alias");
	else
		$result=doQuery($this->vars, $queryCreateAlias) or die ("Can`t create alias in scsq_alias");


	//create quota
	//Создание квоты
	$queryFindAlias = "SELECT id FROM scsq_alias t WHERE t.tableid='".$findUser[0]."';";

	$aliasid=doFetchOneQuery($this->vars, $queryFindAlias);

		//но сначала, посмотрим. Может алиас уже есть? 
		$queryFindQuota = "SELECT id FROM scsq_mod_quotas t WHERE t.aliasid='".$aliasid[0]."';";

		$findQuota=doFetchOneQuery($this->vars, $queryFindQuota);

		//костыль если null
		if(!isset($findQuota[0])) $findQuota[0]=0;

		//Если квота уже есть, то обновим
		$queryUpdateQuota = "UPDATE scsq_mod_quotas t SET  quotaday='".$userdata['quotaday']."',
															   quotamonth=".$userdata['quotamonth'].",
															   active=".$userdata['quotaactive']." where t.aliasid=$aliasid[0];";			

		$queryCreateQuota="INSERT INTO scsq_mod_quotas (aliasid, quotaday, quotamonth,active) VALUES ($aliasid[0], ".$userdata['quotaday'].", ".$userdata['quotamonth'].",".$userdata['quotaactive'].")";

		#дебаг
		echo $queryCreateQuota;
	//update or create quota
	if($findQuota[0] > 0) 
		doQuery($this->vars, $queryUpdateQuota) or die ("Can`t update quota in scsq_mod_quotas");
	else
		doQuery($this->vars, $queryCreateQuota) or die ("Can`t create quota in scsq_mod_quotas");


	//create user

	//но сначала, посмотрим. Может user уже есть? 
		$queryFindUser = "SELECT id FROM scsq_mod_usermanager t WHERE t.aliasid='".$aliasid[0]."';";

		$findUser=doFetchOneQuery($this->vars, $queryFindUser);

		$queryCreateUser="INSERT INTO scsq_mod_usermanager (aliasid, active) VALUES ($aliasid[0],".$userdata['useractive'].")";
		$queryUpdateUser = "UPDATE scsq_mod_usermanager t SET  active='".$userdata['useractive']."' where t.aliasid=$aliasid[0];";			

	//update or create user
	if($findUser[0] > 0) 
		doQuery($this->vars, $queryUpdateUser) or die ("Can`t update user in scsq_mod_usermanager");
	else
		doQuery($this->vars, $queryCreateUser) or die ("Can`t create user in scsq_mod_usermanager");

  
    
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


		

		echo "".$this->lang['stUNINSTALLED']."<br /><br />";

  }


}
?>
