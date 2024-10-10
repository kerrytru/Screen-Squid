<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> function.misc.php </#FN>                                               
*                         File Birth   > <!#FB> 2021/12/06 23:17:52.156 </#FB>                                         *
*                         File Mod     > <!#FT> 2024/10/10 21:58:48.933 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 2.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






#узнаем установлен ли модуль категорий
function doQueryExistsModuleCategory($globalSS){

include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

$queryExistModuleCategory = "select count(1) from scsq_modules where name = 'Categorylist';";

$result = doFetchOneQuery($globalSS,$queryExistModuleCategory);

if($result[0] == 1)
    return ', category';
else
    return ", '0' as category";

}

#составим список  дружественных логинов или IP адресов
function doCreateFriendList($globalSS, $type){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

#create list of friends
    if($globalSS['enableNofriends'] == "0") return 0;

    if($globalSS['goodLogins'] == "" && $type == "logins") return 0;
    if($globalSS['goodIpaddress'] == "" && $type == "ipaddress") return 0;


    if($type == 'logins')  { $friendsTable = 'scsq_logins'; $friends=implode("','",explode(" ", $globalSS['goodLogins']));}

    if($type == 'ipaddress') { $friendsTable = 'scsq_ipaddress'; $friends=implode("','",explode(" ", $globalSS['goodIpaddress'])); }

    $goodList = "0";

    $friendsTmp="where name in  ('".$friends."')";
    $sqlGetFriendsId="select id from ".$friendsTable." ".$friendsTmp."";
    $result = doFetchQuery($globalSS,$sqlGetFriendsId);

    foreach ($result as $row) {
        $goodList=$goodList.",".$row[0];
    }

    return $goodList;

}

#составим список  не учитываемых сайтов
function doCreateSitesList($globalSS){

    if($globalSS['enableNoSites'] == "0") return "''";

    if($globalSS['goodSites'] == "") return "''";

    $sitesTmp=implode("','",explode(" ", $globalSS['goodSites']));
    $sitesTmp="'".$sitesTmp."'";

    return $sitesTmp;

}

#составим список  учитываемых сайтов
function doCreateFilterSitesList($globalSS,$whatTable){

  if($globalSS['enableFilterSites'] == "0") return "";

  if($globalSS['filterSites'] == "") return "";


  $sitesTmp=implode("','",explode(" ", $globalSS['filterSites']));
  $sitesTmp="'".$sitesTmp."'";

if($whatTable == "quick")
  $sitesTmp = "AND scsq_quicktraffic.site in (".$sitesTmp.")";
if($whatTable == "traffic")
  $sitesTmp = "AND scsq_traffic.site in (".$sitesTmp.")";


  return $sitesTmp;

}

#составим список  учитываемых сайтов
function doCreateFilterGoodSitesList($globalSS,$whatTable){

  if($globalSS['enableNoSites'] == "0") return "";

  if($globalSS['goodSites'] == "") return "";


  $sitesTmp=implode("','",explode(" ", $globalSS['goodSites']));
  $sitesTmp="'".$sitesTmp."'";

if($whatTable == "quick")
  $sitesTmp = "AND scsq_quicktraffic.site not in (".$sitesTmp.")";
if($whatTable == "traffic")
  $sitesTmp = "AND scsq_traffic.site not in (".$sitesTmp.")";


  return $sitesTmp;

}

#составим фильтр по размеру учитываемого трафика
function doCreateFilterSizeinbytes($globalSS){

  if($globalSS['enableFilterSizeinbytes'] == "0") return "";

  if($globalSS['filterSizeinbytes'] == "") return "";

  $sizeTmp = "AND sizeinbytes ".$globalSS['filterSizeinbytes'];


  return $sizeTmp;

}

#составим список $_GET параметров
function doCreateGetArray($globalSS){

  $i=0;
  $retarray=array();
  $ret="";
  foreach($_GET as $val => $key){
    #дату не будем записывать предварительно
    if($val=="date") continue;
    if($val=="date2") continue;
    if($val=="clearcache") continue;
    if($val=="csv") continue;

    $retarray[$i]=$val."=".$key;
    $i++;
  }
  $ret=implode("&",$retarray);
  return $ret;

}




#Функция получения значения параметра из scsq_modules_param
function doGetParam($globalSS,$module,$param){

        include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
        $queryGetParam="select val from scsq_modules_param  where module='".$module."' and param='".$param."';";
        $row=doFetchOneQuery($globalSS,$queryGetParam);

        return $row[0];
        }

#Функция установки значения параметра в scsq_modules_param
function doSetParam($globalSS,$module,$param,$val){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

  $querySetParam="update scsq_modules_param set val='".$val."' where module='".$module."' and param='".$param."';";

  doQuery($globalSS,$querySetParam);

  }

#Функция чтения глобальных значенией параметров в scsq_modules_param
function doReadGlobalParamsTable($globalSS){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

  $queryReadParams="select param,val,switch from scsq_modules_param where module='Global';";

  $result=doFetchQuery($globalSS,$queryReadParams);
 
  foreach($result as $line ){
   
    #if param is switch, then convert On to 1, '' to  0
    if($line[2] == '1')
      $globalSS[$line[0]] = ($line[1] == 'on' ? '1' : '0');
    else
      $globalSS[$line[0]] = $line[1];
  }
    return $globalSS;
  }

  function doReadGlobalParamsTableToConfig($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
  
    $queryReadParams="select param,val,switch,comment from scsq_modules_param where module='Global' order by id asc;";
  
    $result=doFetchQuery($globalSS,$queryReadParams);
   
    foreach($result as $line ){
     
      #if param is switch, then convert On to 1, '' to  0
      if($line[2] == '1')
        $globalSS['gParams'][$line[0]]['value'] = ($line[1] == 'on' ? 'on' : 'off');
      else
        $globalSS['gParams'][$line[0]]['value'] = $line[1];

        $globalSS['gParams'][$line[0]]['comment'] = $line[3];
      }
      return $globalSS;
    }



function doWriteToLogTable($globalSS, $params){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
 
  $sqlquery="insert into scsq_logtable (datestart,dateend,message) VALUES ('".$params['datestart']."','".$params['dateend']."','".$params['message']."');";

  doQuery($globalSS,$sqlquery);

      
  }


  function GetIdByIpaddress($globalSS, $ipaddress) #по ip получаем  ID
  {
    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

	$sqlGetId = "
		SELECT id FROM scsq_ipaddress t where t.name = '$ipaddress';";


		$result=doFetchOneQuery($globalSS, $sqlGetId);

return isset($result[0]) ? $result[0] : "" ;


}

function GetIdByLogin($globalSS,$login) #по логину получаем  ID
{

$sqlGetId = "
  SELECT id FROM scsq_logins t where t.name = '$login';";


  $result=doFetchOneQuery($globalSS, $sqlGetId);

return isset($result[0]) ? $result[0] : "" ;


}

function GetAliasIdByIpaddressId($globalSS,$ipaddress_id) #по ip_id получаем  Alias ID
{

  $sqlGetId = "
	  SELECT id FROM scsq_alias t where t.tableid = '$ipaddress_id' and typeid=1;";


	  $result=doFetchOneQuery($globalSS, $sqlGetId);

	  return isset($result[0]) ? $result[0] : "" ;


}

function GetAliasIdByLoginId($globalSS,$login_id) #по login_id получаем  Alias ID
{

  $sqlGetId = "
	  SELECT id FROM scsq_alias t where t.tableid = '$login_id' and typeid=0;";


	  $result=doFetchOneQuery($globalSS, $sqlGetId);

	  return isset($result[0]) ? $result[0] : "" ;


}


function GetDirectorySize($path){
  $bytestotal = 0;
  $path = realpath($path);
  if($path!==false && $path!='' && file_exists($path)){
      foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
          $bytestotal += $object->getSize();
      }
  }
  return $bytestotal;
}

function DeleteAllFilesFromDirectory($path){
 
  $path = realpath($path);
  $files = glob($path.'/*'); // get all file names
  foreach($files as $file){ // iterate files
    if(is_file($file)) {
      unlink($file); // delete file
    }
  }
}



?>