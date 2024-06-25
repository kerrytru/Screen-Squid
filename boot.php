<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> boot.php </#FN>                                                        
*                         File Birth   > <!#FB> 2024/06/25 20:47:17.013 </#FB>                                         *
*                         File Mod     > <!#FT> 2024/06/25 20:52:11.664 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/







#попробуем работать вместо кук с сессиями
session_start();

$globalSS['root_http']=pathUrl();	



$i=0;

#try to get conf
$path    = $globalSS['root_dir'].'/conf/';



$files = array_diff(scandir($path), array('.', '..','.gitignore'));


foreach($files as $file) {
$config= include 'conf/'.$file;

if($config['enabled']!=0)
    {
   $srvname[$i]=$config['srvname'];
   $db[$i]=$config['db'];
   $user[$i]=$config['user'];
   $pass[$i]=$config['pass'];
   $address[$i]=$config['address'];
   $srvdbtype[$i]=$config['srvdbtype'];
   $globalSS['config'][$i]=$config;
   $i++;
   
}

}


if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;


$connectionParams = array();
$connectionParams['srv']=$srv;
$connectionParams['addr']=$address[$srv];
$connectionParams['usr']=$user[$srv];
$connectionParams['psw']=$pass[$srv];
$connectionParams['dbase']=$db[$srv];
$connectionParams['dbtype']=$srvdbtype[$srv];

if(isset($_SESSION['scsq_hash']))
doUpdateHashLogin($_SESSION['scsq_hash']);

if(isset($_SESSION['user_login']) and $_SESSION['user_login']=='admin')
    doDeleteOldSessions();

function isAuth(): bool
{

   
    if(file_exists("".__DIR__."/modules/PrivateAuth/enabled")) $authEnabled=true; else $authEnabled=false;

    if((isset($_SESSION['user_id']) and $_SESSION['user_login']==doGetHashLogin($_SESSION['scsq_hash'] ) ) or ($authEnabled==false)) 
        return true;
    else return false;

}


#Функция чтения хэша
function doGetHashLogin($hash){
      
    $json_session_data=file_get_contents(__DIR__.'/modules/PrivateAuth/hash/'.$hash);

    $session_data = array();

    $session_data = json_decode($json_session_data,true);


     return $session_data['user_login'];
      
}

        #Функция обновим хэш (дату)
function doUpdateHashLogin($hash){

    $json_session_data=file_get_contents(__DIR__.'/modules/PrivateAuth/hash/'.$hash);

      if((time() - filemtime(__DIR__.'/modules/PrivateAuth/hash/'.$hash))>500 )
    file_put_contents(__DIR__.'/modules/PrivateAuth/hash/'.$hash,$json_session_data);
        
}

    #Удалим старые сессии
function doDeleteOldSessions(){
#try to get conf
$path    = __DIR__.'/modules/PrivateAuth/hash/';
$files = array_diff(scandir($path), array('.', '..','.gitignore'));

    foreach($files as $file) {
        if((time() - filemtime(__DIR__.'/modules/PrivateAuth/hash/'.$file) >3600 ))
            unlink(__DIR__.'/modules/PrivateAuth/hash/'.$file);
        
        }

    }



function pathUrl($dir = __DIR__){

    $root = "";
    $dir = str_replace('\\', '/', realpath($dir));

    //HTTPS or HTTP
    $root .= !empty($_SERVER['HTTPS']) ? 'https' : 'http';

    //HOST
    $root .= '://' . $_SERVER['HTTP_HOST'];

    //ALIAS
    if(!empty($_SERVER['CONTEXT_PREFIX'])) {
        $root .= $_SERVER['CONTEXT_PREFIX'];
        $root .= substr($dir, strlen($_SERVER[ 'CONTEXT_DOCUMENT_ROOT' ]));
    } else {
        $root .= substr($dir, strlen($_SERVER[ 'DOCUMENT_ROOT' ]));
    }

    $root .= '/';

    return $root;
}
?>