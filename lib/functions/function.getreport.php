<?php


function doGetReportData($globalSS,$query,$template_name) {

    #возможно костыль
    $_lang=$globalSS['lang'];

    $dayormonth=$globalSS['dayormonth'];
    $currenthttpname=$globalSS['currenthttpname'];
    $currenthttpstatusid=$globalSS['currenthttpstatusid'];
    $currentipaddressid=$globalSS['currentipaddressid'];
    $currentloginid=$globalSS['currentloginid'];
    $currentipaddress=$globalSS['currentipaddress'];
    $currentlogin=$globalSS['currentlogin'];

    if(isset($globalSS['typeid'])) $typeid=$globalSS['typeid']; else $typeid=0;


    $enableShowDayNameInReports = $globalSS['enableShowDayNameInReports'];
    $enableNofriends = $globalSS['enableNofriends'];
    $enableNoSites = $globalSS['enableNoSites'];
    $showZeroTrafficInReports = $globalSS['showZeroTrafficInReports'];
    $useLoginalias = $globalSS['useLoginalias'];
    $useIpaddressalias = $globalSS['useIpaddressalias'];
    $category = $globalSS['category'];

    $countTopSitesLimit=$globalSS['countTopSitesLimit'];
    $countTopLoginLimit=$globalSS['countTopLoginLimit'];
    $countTopIpLimit=$globalSS['countTopIpLimit'];
    $countPopularSitesLimit=$globalSS['countPopularSitesLimit'];
    $countWhoDownloadBigFilesLimit=$globalSS['countWhoDownloadBigFilesLimit'];

    include_once('function.database.php');
    include_once('function.reportmisc.php');
    include_once(''.$globalSS['root_dir'].'/lib/templates/'.$template_name.'');
    

    if($globalSS['debug']==1) echo $query;

#Получим уникальное имя отчёта
$report_file_name=doGenerateUniqueNameReport($globalSS['params']);

#Если нет данных в кэше, то только тогда лезем в базу. Иначе даже не коннектимся. 
#Можно ведь и в оффлайне поработать с отчётами
if(!file_exists ("".$globalSS['root_dir']."/modules/Cache/data/".$report_file_name))
{


    $json_result=json_encode(doFetchQuery($globalSS,$query));

    #Преобразуем данные в таблицу
    $result_data_json=doPrepareTable($globalSS,$json_result,$colh,$colr,$colf);
    
    #Запишем данные в файл.
    doWriteJsonToFile($globalSS,$result_data_json,$report_file_name);
}
else
{
    #если данные уже есть в кэше, то просто считаем их.
    $result_data_json=file_get_contents("".$globalSS['root_dir']."/modules/Cache/data/".$report_file_name);
}

if($globalSS['makecsv']==1)
    doMakeCSV($globalSS, $result_data_json);


if($globalSS['makepdf']==1)
    doMakePDF($globalSS, $result_data_json);

    

return $result_data_json;

}

function doPrintTable($globalSS, $json_result) {

    #если выводим CSV, то таблицу не показываем.
    if($globalSS['makecsv']==1)
        return;

    #если выводим PDF, то таблицу не показываем.
    if($globalSS['makepdf']==1)
        return;


    $json = json_decode($json_result);

    ///TABLE HEADER old style
    //Так как данные уже готовы, то мы просто делаем заголовки, обозначаем, 
    //что сейчас будет строка и пишем как есть.
 
    //Так как последняя строчка не должна попадать в сортировку, сразу найдем последний элемент.
    //И будем поглядывать на него.
    $lastitem=end($json);
  
    echo "<table id=report_table_id class=datatable>";
        foreach ($json as $row_item) {
            
            if ($row_item === $lastitem)
                echo "<tr class=sortbottom>";
            else
                echo "<tr>";

            foreach ($row_item as $item) {
                echo $item;
                
                }
            echo "	</tr>";
        }
    echo "</table>";

}

#Костыль для того, чтобы получить данные для графиков
function doGetArrayData($globalSS,$json_result,$column_number) {

    #если выводим CSV, то сразу уходим.
    if($globalSS['makecsv']==1)
        return;

    if($globalSS['makepdf']==1)
        return;
        
    $json = json_decode($json_result);

    $arrayData = array();
    
    $i=0;
    #нам нужны только данные, поэтому вырежем первую и последнюю строки
    $i_count=count($json);

    foreach ($json as $row_item) {
            $j=0;
            foreach ($row_item as $item) {
                if($j==$column_number)
                    $arrayData[$i]=strip_tags($item);
                $j++;
            }
            $i++;

            if($i==$i_count-1) break;
        }

    return array_slice($arrayData,1);

}

function doMakeCSV($globalSS, $json_result) {

    $json = json_decode($json_result);

    
    //Так как данные уже готовы, то мы просто чистим от тэгов и заполняем файл заголовки, обозначаем, 
    //что сейчас будет строка и пишем как есть.

/// GENERATE CSV FILE

	header("Content-Type:application/csv"); 
	header("Content-Disposition:attachment;filename=report.csv"); 
	echo "\xEF\xBB\xBF"; // UTF-8 BOM
    $row_line_arr = array();    
        
    $fp = fopen("php://output",'w') or die("Can't open php://output");

 
    foreach ($json as $row)
    {
        $j=0;
        #   
        foreach ($row as $row_item) {
            $row_line_arr[$j]=strip_tags($row_item);
            if(is_numeric($row_line_arr[$j])) { 
                $row_line_arr[$j]= str_replace('.',$globalSS['csv_decimalSymbol'],$row_line_arr[$j]);
                $row_line_arr[$j]= str_replace(',',$globalSS['csv_decimalSymbol'],$row_line_arr[$j]); 
            }
            $j++;
            
        }

        fputcsv($fp, $row_line_arr);
    }
    fclose($fp) or die("Can't close php://output");
    return;

}

function doMakePDF($globalSS,$json_result) {

    $json = json_decode($json_result);

    
    //Так как данные уже готовы, то мы просто чистим от тэгов и заполняем файл заголовки, обозначаем, 
    //что сейчас будет строка и пишем как есть.

/// GENERATE PDF FILE
include_once("".$globalSS['root_dir']."/lib/tcpdf/tcpdf.php");


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetHeaderData('','', '', 'powered by TCPDF');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));

// set font
$pdf->SetFont('dejavusans', '', 10);

$pdf->AddPage();

$pdf->writeHTML($globalSS['repheader']."<br>", true, false, true, false, 'L');


$row_line_arr = array();

#Здесь было бы неплохо, как-то узнавать, сколько столбцов в отчёте. Я думаю это можно посчитать.

foreach ($json as $row)
{
    $j=0;
    #   
    foreach ($row as $row_item) {
        $row_line_arr[$j]=strip_tags($row_item);
        $j++;
    }

    $pdf->Cell(30, 6, $row_line_arr[0], 1, 0, 'L', 0);
    $pdf->Cell(120, 6, $row_line_arr[1], 1, 0, 'L', 0);
    $pdf->Cell(30, 6, $row_line_arr[2], 1, 0, 'R', 0);
    $pdf->Ln();
}

//Close and output PDF document

$pdf->Output("".$globalSS['root_dir']."/output/report.pdf", 'D');

unset($pdf);
//PDF END
    return;

}

function doGetStatistics($globalSS) {

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

    $_lang = $globalSS['lang'];
    		
    $queryCountRowsTraffic="select count(*) from scsq_traffic";
    $queryCountRowsLogin="select count(*) from scsq_logins";
    $queryCountRowsIpaddress="select count(*) from scsq_ipaddress";
    $queryMinMaxDateTraffic="select from_unixtime(t.mindate,'%d-%m-%Y %H:%i:%s'),from_unixtime(t.maxdate,'%d-%m-%Y %H:%i:%s') from ( select min(date) as mindate,max(date) as maxdate from scsq_traffic) t";
    
    if($globalSS['connectionParams']['dbtype']==1) #postgres 
    $queryMinMaxDateTraffic="select to_char(to_timestamp(t.mindate),'DD-MM-YYYY HH24:MI:SS'),to_char(to_timestamp(t.maxdate),'DD-MM-YYYY HH24:MI:SS') from ( select min(date) as mindate,max(date) as maxdate from scsq_traffic) t";
    
    
    $querySumSizeTraffic="select sum(sizeinbytes) from scsq_quicktraffic where par=1";
    $queryCountObjectsTraffic1="select count(id) from scsq_traffic where sizeinbytes<=1000";
    $queryCountObjectsTraffic2="select count(id) from scsq_traffic where sizeinbytes>1000 and sizeinbytes<=5000";
    $queryCountObjectsTraffic3="select count(id) from scsq_traffic where sizeinbytes>5000 and sizeinbytes<=10000";
    $queryCountObjectsTraffic4="select count(id) from scsq_traffic where sizeinbytes>10000";

  #  $result=mysqli_query($connection,$queryCountRowsTraffic, MYSQLI_USE_RESULT);
  $CountRowsTraffic = doFetchOneQuery($globalSS,$queryCountRowsTraffic);

  $CountRowsLogin = doFetchOneQuery($globalSS,$queryCountRowsLogin);
 
  $CountRowsIpaddress = doFetchOneQuery($globalSS,$queryCountRowsIpaddress);
 
  $MinMaxDateTraffic = doFetchOneQuery($globalSS,$queryMinMaxDateTraffic);

  $SumSizeTraffic = doFetchOneQuery($globalSS,$querySumSizeTraffic);
  
      if($globalSS['enableTrafficObjectsInStat']==1)
      {
        $CountObjects1 = doFetchOneQuery($globalSS,$queryCountObjectsTraffic1);
     
        $CountObjects2 = doFetchOneQuery($globalSS,$queryCountObjectsTraffic2);
     
        $CountObjects3 = doFetchOneQuery($globalSS,$queryCountObjectsTraffic3);
     
        $CountObjects4 = doFetchOneQuery($globalSS,$queryCountObjectsTraffic4);
      }



echo "
<h2>".$_lang['stSTATS'].":</h2>
<table class=\"datatable\">
<tr>
<td>".$_lang['stQUANTITYRECORDS']."</td>
<td>".$CountRowsTraffic[0]."</td>
</tr>
<tr>
<td>".$_lang['stQUANTITYLOGINS']."</td>
<td>".$CountRowsLogin[0]."</td>
</tr>
<tr>
<td>".$_lang['stQUANTITYIPADDRESSES']."</td>
<td>".$CountRowsIpaddress[0]."</td>
</tr>
<tr>
<td>".$_lang['stMINDATE']."</td>
<td>".$MinMaxDateTraffic[0]."</td>
</tr>
<tr>
<td>".$_lang['stMAXDATE']."</td>
<td>".$MinMaxDateTraffic[1]."</td>
</tr>
<tr>
<td>".$_lang['stTRAFFICSUM']."</td>
<td>".doConvertBytes($SumSizeTraffic[0],'mbytes')."</td>
</tr>
";

if($globalSS['enableTrafficObjectsInStat']==1)
{
echo "
<tr>
   <td colspan=2 align=center><b>".$_lang['stTRAFFICOBJECTS']."</b></td>
</tr>
<tr>
   <td> < 1 kB</td>
   <td>".$CountObjects1[0]."</td>
</tr>
<tr>
   <td> > 1 kB & < 5 kB </td>
   <td>".$CountObjects2[0]."</td>
</tr>
<tr>
   <td> > 5 kB & < 10 kB </td>
   <td>".$CountObjects3[0]."</td>
</tr>
<tr>
   <td> > 10 kB </td>
   <td>".$CountObjects4[0]."</td>
</tr>
";
}

echo "</table>";

}

?>