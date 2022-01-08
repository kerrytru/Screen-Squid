<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> function.reportmisc.php </#FN>                                         
*                         File Birth   > <!#FB> 2021/11/02 20:44:42.135 </#FB>                                         *
*                         File Mod     > <!#FT> 2021/12/07 22:15:36.335 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






function doWriteJsonToFile($globalSS,$json_result,$report_name) {
    $fp = fopen(''.$globalSS['root_dir'].'/modules/Cache/data/'.$report_name.'', 'w');
    fwrite($fp, $json_result);
    fclose($fp);

}


//Функция подготовки данных для таблицы
//возвращает json набор данных, мы его пишем в файл и показываем пользователю.
//а когда он запросит то же самое - достанем из кэша
function doPrepareTable($globalSS, $json_result,$colh,$colr,$colf) {

    //возьмём данные, которые нам вернул запрос и переведем их из JSON в обычный массив
    $json = json_decode($json_result);
    $_lang = $globalSS['lang'];
    $row_line_arr=[];
    
    $chunked_array =array();


    $result[0] =array();
    $result[0] = array_slice($colh, 1); ;

    $chunked_array = array_chunk($result[0],$colh[0]);

    $result[0] = $chunked_array[0];

//TABLE BODY

//        echo $item."<br />";
#Ещё один костыль ( почему-то не видит config.php)
$roundTrafficDigit=-1;
$i=0;

#возможно лучший костыль. Идея такая. Если в шаблоне в конце указано слово total
#то будем этот столбец считать. Номер столбца это номер totals
$totals = array();
$totals[0] = 0;

///for($i=1;$i<$numrow;$i++) {
foreach ($json as $row_item) {

    $i++;
$row_line="";


//вот этот костыль надо убирать!
foreach ($row_item as $item) {
    $row_line=$row_line.$item.";;";
    
    }
  
$line=explode(';;',$row_line);




for($j=1;$j<=$colh[0];$j++){

#если это нужно считать, то сразу приведем это к нормальному виду. 
if(preg_match("/total_column_byte/i", $colf[$j]))
{
    $line_index =strip_tags(preg_replace("/line/i", "", $colr[$j]));
    $line[$line_index]=sprintf("%f",doConvertBytes($line[$line_index],'mbytes'));  //disable scientific format, like 5E-10

}

#Это если просто цифры сложить. с 2 десятичными знаками
if(preg_match("/total_column_float/i", $colf[$j]))
{
    $line_index =strip_tags(preg_replace("/line/i", "", $colr[$j]));
    $line[$line_index]=sprintf("%0.2f",$line[$line_index]);  //disable scientific format, like 5E-10
}

#Это если просто цифры сложить
if(preg_match("/total_column_number/i", $colf[$j]))
{
    $line_index =strip_tags(preg_replace("/line/i", "", $colr[$j]));
    $line[$line_index]=sprintf("%d",$line[$line_index]); 
}



$resultcolr=$colr[$j];


if(preg_match("/lang_var/i", $resultcolr))
    $resultcolr=preg_replace("/lang_var_line_0/i", $_lang[$line[0]], $resultcolr);


    #убираем предупреждения если используются 2 или 3 элемента из массива line
if(isset($line[0]) && preg_match("/gethostbyaddr_line_0/i", $resultcolr))
    $resultcolr=preg_replace("/gethostbyaddr_line_0/i", gethostbyaddr($line[0]), $resultcolr);


for($n=0;$n<=30;$n++){
    if(isset($line[$n]))
        $resultcolr=preg_replace("/\bline".$n."\b/i", $line[$n], $resultcolr);
       
}

#Если есть модуль категорий то подтянем налету категорию.
$itm= explode(":",$line[0]);
if(preg_match("/getcategory/i", $resultcolr)) {
    $resrow=doFetchOneQuery($globalSS,"select category from scsq_mod_categorylist where site = '$itm[0]' limit 1");
    $resultcolr=preg_replace("/getcategory/i", $resrow[0], $resultcolr);
}

if(isset($i))
$resultcolr=preg_replace("/numrow/i", $i, $resultcolr);

$row_line_arr[$j]=$resultcolr;

if(preg_match("/total_column/i", $colf[$j])) {
    if(!isset($totals[$j]))
        $totals[$j] = 0;
    $totals[$j] = $totals[$j] + strip_tags($row_line_arr[$j]);
}

#$row_line_arr[$j]=preg_replace("/\./",",",$resultcolr);
    

}

$result[$i] = $row_line_arr;


}

///TABLE FOOTER


if(isset($colh))
for($k=1;$k<=$colh[0];$k++){
    if (preg_match("/total_column/i", $colf[$k])) {
        if(!isset($totals[$k])) $totals[$k] = 0;
        $colf[$k]= preg_replace("/total_column(.+)\</i", $totals[$k]."<", $colf[$k]);
    }
//echo $colf[$i];
}

$chunked_array = array_chunk($colf,$colh[0]);

$result[$i+1] = $chunked_array[0];

$result_json=json_encode($result);

return $result_json;
}



//Конвертируем байты в мегабайты. Возможно потом еще добавим вариант - гигабайты
function doConvertBytes($bytes,$toWhat) {
//$oneMegabyte = 1024 * 1024;
$oneMegabyte = 1000 * 1000;

#Если вдруг прилетела пустота
if($bytes=='')
    $bytes = 0;

    if($toWhat == 'mbytes')
        return $bytes / $oneMegabyte;

    else
        //если ничего не укажем, будем -1 показывать
        return -1;
}

//Автоматически придумываем уникальное имя для отчёта. С этим именем будет создан файл
function doGenerateUniqueNameReport($params) {
    //Параметры однозначно идентифицирующие отчет.
    //имя БД (т.к. брать id - некорректно. Завтра базу выключат и под её ID кэш будет показываться)
    //id отчета
    //date
    //Дневной, месячный или промежуточный
    //id объекта (логина, ip адреса, группы)
    return "report_".$params['dbase']."_".$params['idReport']."_".$params['date']."_".$params['period']."_".$params['idLogin']."_".$params['idIpaddress'];   
}    

?>