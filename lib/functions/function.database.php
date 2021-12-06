<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> function.database.php </#FN>                                           
*                         File Birth   > <!#FB> 2021/11/01 16:41:57.143 </#FB>                                         *
*                         File Mod     > <!#FT> 2021/12/06 23:20:48.266 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






#This function pass connection params and return dbs connection
function doConnectToDatabase($params) {

        if($params['dbtype']==0)
        try {
            $dbs = new PDO('mysql:host='.$params['addr'].';dbname='.$params['dbase'].'', ''.$params['usr'].'', ''.$params['psw'].'');
        } catch (Exception $e) {
            return "ErrorConnection";
        }

        if($params['dbtype']==1)
        try {
            if (strlen($params['addr'])==0) {
                # no addr given : use unix socket connexion
                $dbs = new PDO('pgsql:dbname='.$params['dbase'].'', ''.$params['usr'].'', ''.$params['psw'].'');
            } else { 
                $dbs = new PDO('pgsql:host='.$params['addr'].';dbname='.$params['dbase'].'', ''.$params['usr'].'', ''.$params['psw'].'');
            }
        } catch (Exception $e) {
            return "ErrorConnection";
        }

    return $dbs;
}



function doFetchQuery($globalSS,$query) {
    #echo $query;
    $dbs = doConnectToDatabase($globalSS['connectionParams']);
    $stmt = $dbs->query($query);
   
    #TODO: Здесь где-нибудь неплохо было бы писать лог операций. Удобно было бы для диагностики
    
    #получим данные    
    if ($stmt!==false) 
       $result = $stmt->fetchAll(PDO::FETCH_NUM);
    else 
    {
        $result=0;
    }
    return $result;
}

function doFetchOneQuery($globalSS,$query) {

    $dbs = doConnectToDatabase($globalSS['connectionParams']);
    $stmt = $dbs->query($query);
    
    #TODO: Здесь где-нибудь неплохо было бы писать лог операций. Удобно было бы для диагностики
    
    #получим данные    
    $result = $stmt->fetch(PDO::FETCH_NUM);

    return $result;
}

function doQuery($globalSS,$query) {

    $dbs = doConnectToDatabase($globalSS['connectionParams']);
    $stmt = $dbs->query($query);

    #TODO: Здесь где-нибудь неплохо было бы писать лог операций. Удобно было бы для диагностики
 
    return $stmt;
}

?>