<?php
function doConnectToDatabase($params) {

    if($params['dbtype']==0)
        try {
            $dbs = new PDO('mysql:host='.$params['addr'].';dbname='.$params['dbase'].'', ''.$params['usr'].'', ''.$params['psw'].'');
        } catch (Exception $e) {
            return "ErrorConnection";
        }

    if($params['dbtype']==1)
        try {
            $dbs = new PDO('pgsql:host='.$params['addr'].';dbname='.$params['dbase'].'', ''.$params['usr'].'', ''.$params['psw'].'');
        } catch (Exception $e) {
            return "ErrorConnection";
        }

        //    $dbs = new PDO('mysql:host='.$address[$srv_number].';dbname='.$db[$srv_number].', '.$user[$srv_number].', '.$pass[$srv_number].'');

    //    $dbs = new PDO('pgsql:host=localhost;dbname=test5', 'postgres', 'pass');
    return $dbs;
}

function doFetchQuery($globalSS,$query) {

    $dbs = doConnectToDatabase($globalSS['connectionParams']);
    $stmt = $dbs->query($query);

    #TODO: Здесь где-нибудь неплохо было бы писать лог операций. Удобно было бы для диагностики
    
    #получим данные    
    $result = $stmt->fetchAll(PDO::FETCH_NUM);

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