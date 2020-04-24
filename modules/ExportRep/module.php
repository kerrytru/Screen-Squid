<?php

#Build date Friday 24th of April 2020 07:50:26 AM
#Build revision 1.2


class ExportRep
{

function __construct($variables){ // 
    $this->vars = $variables;
    	
  
	$this->ssq = new ScreenSquid($variables); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение
	// create new PDF document

	if (file_exists("langs/".$this->vars['language']))
		include("langs/".$this->vars['language']);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 

	if (file_exists("../../lang/".$this->vars['language']))
		include("../../lang/".$this->vars['language']); #подтянем глобальный файл языка
  	
	$this->lang = $_lang;
}

  function GetDesc()
  {
	  
	  return $this->lang['stMODULEDESC']; 
   
  }

function CreateLoginsPDF($repvars)
  {

$this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


$querydate = $repvars['querydate'];
$querydate2 = $repvars['querydate2']; 
$currentlogin = $repvars['currentlogin'];
$currentloginid=$repvars['currentloginid'];
$goodSitesList = $repvars['goodSitesList'];
$oneMegabyte = $repvars['oneMegabyte'];
$sortcolumn = $repvars['sortcolumn'];
$sortorder = $repvars['sortorder'];


$datestart=strtotime($querydate);
$dateend=strtotime($querydate2) + 86400;
$querydate = $querydate." - ".$querydate2; 


$repheader= "<h2>".$this->lang['stONELOGINTRAFFIC']." ".$currentlogin." ".$this->lang['stFOR']." ".$querydate."</h2>";



 /////////// ONE LOGIN TRAFFIC REPORT
$queryOneLoginTraffic="
	SELECT 
	   scsq_quicktraffic.site,
	   SUM(sizeinbytes) AS s
	 FROM scsq_quicktraffic
	 
	 WHERE login=".$currentloginid."
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	AND par=1
	 GROUP BY CRC32(scsq_quicktraffic.site) 
	 ORDER BY ".$sortcolumn." ".$sortorder."
;";


#postgre version
if($this->vars['dbtype']==1)
$queryOneLoginTraffic="
	SELECT 
	   scsq_quicktraffic.site,
	   SUM(sizeinbytes) AS s,
	   ".$category." as cat
	 FROM scsq_quicktraffic
	 
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")   
	AND par=1
	 GROUP BY scsq_quicktraffic.site 
	 ORDER BY ".$sortcolumn." ".$sortorder."
;";


//if($id==8)
///{
$colhtext[1]="#";
$colhtext[2]=$this->lang['stSITE'];
$colhtext[3]=$this->lang['stMEGABYTES'];
$colhtext[4]=$this->lang['stCATEGORY'];

$colftext[1]="";
$colftext[2]=$this->lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="";

//если потребуется включим. пока выключено (19.04.2020)
//если есть модуль категорий то добавим столбец
//if($category=="category")
//$colh[0]=4;
//else
$colh[0]=3;

$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$result=$this->ssq->query($queryOneLoginTraffic);

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";
$colr[4]="line2";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";


/////////// ONE LOGIN TRAFFIC REPORT END	 

//}

$totalmb=0;

//PARSE SQL
while ($line = $this->ssq->fetch_array($result)) {
#if($enableUseiconv==1)
#$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));
$line[1]=$line[1] / $oneMegabyte;

$totalmb=$totalmb+$line[1];
@$rows[$numrow]=implode(";;",$line);
$numrow++;
}

//// GENERATE PDF FILE


//PDF

// set margins
$this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$this->pdf->SetHeaderData('','', '', 'powered by TCPDF');
$this->pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));

// set font
$this->pdf->SetFont('dejavusans', '', 10);

$this->pdf->AddPage();

//add header of report
$this->pdf->writeHTML($repheader."<br>", true, false, true, false, 'L');

if(($colh[0]==3)or($colh[0]==5))
{
	  $this->pdf->Cell(30, 6, $colhtext[1], 1, 0, 'L', 0);
      $this->pdf->Cell(120, 6, $colhtext[2], 1, 0, 'L', 0);
      $this->pdf->Cell(30, 6, $colhtext[3], 1, 0, 'R', 0);
      $this->pdf->Ln();
}

$i=1;

while ($i<$numrow) {
$line=explode(';;',$rows[$i]);


for($j=2;$j<=$colh[0];$j++){
$resultcolr[$j]=$colr[$j];
$resultcolr[$j]=preg_replace("/line0/i", $line[0], $resultcolr[$j]);
$resultcolr[$j]=preg_replace("/line1/i", round($line[1],2), $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line2/i", $line[2], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line3/i", $line[3], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line4/i", $line[4], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line5/i", $line[5], $resultcolr[$j]);
if(preg_match('/<a(.+)>(.*?)<\/a>/s', $resultcolr[$j], $matches))
$resultcolr[$j]=$matches[2];
//HTML array in $matches[1]
}




if(($colh[0]==3)or($colh[0]==5))
{
      $this->pdf->Cell(30, 6, $i, 1, 0, 'L', 0);
      $this->pdf->Cell(120, 6, $resultcolr[2], 1, 0, 'L', 0);
      $this->pdf->Cell(30, 6, $resultcolr[3], 1, 0, 'R', 0);
      $this->pdf->Ln();
}



for($j=1;$j<=$colh[0];$j++)
$resultcolr[$j]="";

$i++;
}

for($i=1;$i<=$colh[0];$i++){
if (preg_match("/totalmb/i", $colf[$i])) {
preg_replace("/totalmb/i", $totalmb, $colf[$i]);
$colftext[$i]=round($totalmb,2);
}
}


if(($colh[0]==3)or($colh[0]==5))
{
	  $this->pdf->Cell(30, 6, $colftext[1], 1, 0, 'L', 0);
      $this->pdf->Cell(120, 6, $colftext[2], 1, 0, 'L', 0);
      $this->pdf->Cell(30, 6, $colftext[3], 1, 0, 'R', 0);
      $this->pdf->Ln();
}


//Close and output PDF document

$this->pdf->Output("output/TrafficLogin_".$currentlogin."_".$querydate.".pdf", 'F');

unset($this->pdf);

//PDF END

//echo $pdff;

/// GENERATE PDF FILE END

  }


function CreateIpaddressPDF($repvars)
  {

$this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


$querydate = $repvars['querydate'];
$querydate2 = $repvars['querydate2']; 
$currentipaddress = $repvars['currentipaddress'];
$currentipaddressid=$repvars['currentipaddressid'];
$goodSitesList = $repvars['goodSitesList'];
$oneMegabyte=$repvars['oneMegabyte'];
$sortcolumn = $repvars['sortcolumn'];
$sortorder = $repvars['sortorder'];

$datestart=strtotime($querydate);
$dateend=strtotime($querydate2) + 86400;
$querydate = $querydate." - ".$querydate2; 


$repheader= "<h2>".$this->lang['stONEIPADRESSTRAFFIC']." ".$currentipaddress." ".$this->lang['stFOR']." ".$querydate."</h2>";



 /////////// ONE IPADDRESS TRAFFIC REPORT
$queryOneIpaddressTraffic="
	SELECT 
	   scsq_quicktraffic.site,
	   SUM(sizeinbytes) AS s
	 FROM scsq_quicktraffic
	 
	 WHERE ipaddress=".$currentipaddressid."
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	AND par=1
	 GROUP BY CRC32(scsq_quicktraffic.site) 
	 ORDER BY ".$sortcolumn." ".$sortorder."
;";


#postgre version
if($this->vars['dbtype']==1)
$queryOneIpaddressTraffic="
	SELECT 
	   scsq_quicktraffic.site,
	   SUM(sizeinbytes) AS s,
	   ''
	 FROM scsq_quicktraffic
	 
	 WHERE ipaddress=".$currentipaddressid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")   
	AND par=1
	 GROUP BY scsq_quicktraffic.site 
	 ORDER BY ".$sortcolumn." ".$sortorder."
;";



$colhtext[1]="#";
$colhtext[2]=$this->lang['stSITE'];
$colhtext[3]=$this->lang['stMEGABYTES'];
$colhtext[4]=$this->lang['stCATEGORY'];

$colftext[1]="";
$colftext[2]=$this->lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="";

//если потребуется включим. пока выключено (19.04.2020)
//если есть модуль категорий то добавим столбец
//if($category=="category")
//$colh[0]=4;
//else
$colh[0]=3;

$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$result=$this->ssq->query($queryOneIpaddressTraffic);

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";
$colr[4]="line2";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";


/////////// ONE IPADDRESS TRAFFIC REPORT END	 

//}

$totalmb=0;

//PARSE SQL
while ($line = $this->ssq->fetch_array($result)) {
#if($enableUseiconv==1)
#$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));
$line[1]=$line[1] / $oneMegabyte;

$totalmb=$totalmb+$line[1];
@$rows[$numrow]=implode(";;",$line);
$numrow++;
}

//// GENERATE PDF FILE


//PDF

// set margins
$this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$this->pdf->SetHeaderData('','', '', 'powered by TCPDF');
$this->pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));

// set font
$this->pdf->SetFont('dejavusans', '', 10);

$this->pdf->AddPage();

//add header of report
$this->pdf->writeHTML($repheader."<br>", true, false, true, false, 'L');

if(($colh[0]==3)or($colh[0]==5))
{
	  $this->pdf->Cell(30, 6, $colhtext[1], 1, 0, 'L', 0);
      $this->pdf->Cell(120, 6, $colhtext[2], 1, 0, 'L', 0);
      $this->pdf->Cell(30, 6, $colhtext[3], 1, 0, 'R', 0);
      $this->pdf->Ln();
}

$i=1;

while ($i<$numrow) {
$line=explode(';;',$rows[$i]);


for($j=2;$j<=$colh[0];$j++){
$resultcolr[$j]=$colr[$j];
$resultcolr[$j]=preg_replace("/line0/i", $line[0], $resultcolr[$j]);
$resultcolr[$j]=preg_replace("/line1/i", round($line[1],2), $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line2/i", $line[2], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line3/i", $line[3], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line4/i", $line[4], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line5/i", $line[5], $resultcolr[$j]);
if(preg_match('/<a(.+)>(.*?)<\/a>/s', $resultcolr[$j], $matches))
$resultcolr[$j]=$matches[2];
//HTML array in $matches[1]
}




if(($colh[0]==3)or($colh[0]==5))
{
      $this->pdf->Cell(30, 6, $i, 1, 0, 'L', 0);
      $this->pdf->Cell(120, 6, $resultcolr[2], 1, 0, 'L', 0);
      $this->pdf->Cell(30, 6, $resultcolr[3], 1, 0, 'R', 0);
      $this->pdf->Ln();
}



for($j=1;$j<=$colh[0];$j++)
$resultcolr[$j]="";

$i++;
}

for($i=1;$i<=$colh[0];$i++){
if (preg_match("/totalmb/i", $colf[$i])) {
preg_replace("/totalmb/i", $totalmb, $colf[$i]);
$colftext[$i]=round($totalmb,2);
}
}


if(($colh[0]==3)or($colh[0]==5))
{
	  $this->pdf->Cell(30, 6, $colftext[1], 1, 0, 'L', 0);
      $this->pdf->Cell(120, 6, $colftext[2], 1, 0, 'L', 0);
      $this->pdf->Cell(30, 6, $colftext[3], 1, 0, 'R', 0);
      $this->pdf->Ln();
}



//Close and output PDF document

$this->pdf->Output("output/TrafficIP_".$currentipaddress."_".$querydate.".pdf", 'F');


//PDF END

unset($this->pdf);

//echo $pdff;

/// GENERATE PDF FILE END

}
   
function CreateLoginsCSV($repvars)
  {


$querydate = $repvars['querydate'];
$querydate2 = $repvars['querydate2']; 
$currentlogin = $repvars['currentlogin'];
$currentloginid=$repvars['currentloginid'];
$goodSitesList = $repvars['goodSitesList'];
$oneMegabyte = $repvars['oneMegabyte'];
$sortcolumn = $repvars['sortcolumn'];
$sortorder = $repvars['sortorder'];

$datestart=strtotime($querydate);
$dateend=strtotime($querydate2) + 86400;
$querydate = $querydate." - ".$querydate2; 


$repheader= "<h2>".$this->lang['stONELOGINTRAFFIC']." ".$currentlogin." ".$this->lang['stFOR']." ".$querydate."</h2>";



 /////////// ONE LOGIN TRAFFIC REPORT
$queryOneLoginTraffic="
	SELECT 
	   scsq_quicktraffic.site,
	   SUM(sizeinbytes) AS s
	 FROM scsq_quicktraffic
	 
	 WHERE login=".$currentloginid."
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	AND par=1
	 GROUP BY CRC32(scsq_quicktraffic.site) 
	 ORDER BY ".$sortcolumn." ".$sortorder."
;";


#postgre version
if($this->vars['dbtype']==1)
$queryOneLoginTraffic="
	SELECT 
	   scsq_quicktraffic.site,
	   SUM(sizeinbytes) AS s,
	   ".$category." as cat
	 FROM scsq_quicktraffic
	 
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")   
	AND par=1
	 GROUP BY scsq_quicktraffic.site 
	 ORDER BY ".$sortcolumn." ".$sortorder."
;";


//if($id==8)
///{
$colhtext[1]="#";
$colhtext[2]=$this->lang['stSITE'];
$colhtext[3]=$this->lang['stMEGABYTES'];
$colhtext[4]=$this->lang['stCATEGORY'];

$colftext[1]="";
$colftext[2]=$this->lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="";

//если потребуется включим. пока выключено (19.04.2020)
//если есть модуль категорий то добавим столбец
//if($category=="category")
//$colh[0]=4;
//else
$colh[0]=3;

$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$result=$this->ssq->query($queryOneLoginTraffic);

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";
$colr[4]="line2";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";


/////////// ONE LOGIN TRAFFIC REPORT END	 

//}

$totalmb=0;

//PARSE SQL
while ($line = $this->ssq->fetch_array($result)) {
#if($enableUseiconv==1)
#$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));
$line[1]=$line[1] / $oneMegabyte;

$totalmb=$totalmb+$line[1];
@$rows[$numrow]=implode(";;",$line);
$numrow++;
}


//generate CSV file

if($colh[0]==4)
{
$csvfile[0][0] = $colhtext[1];
$csvfile[0][1] = $colhtext[2];
$csvfile[0][2] = $colhtext[3];
$csvfile[0][3] = $colhtext[4];

}
if(($colh[0]==3)or($colh[0]==5))
{
$csvfile[0][0] = $colhtext[1];
$csvfile[0][1] = $colhtext[2];
$csvfile[0][2] = $colhtext[3];
}

$i=1;

while ($i<$numrow) {
$line=explode(';;',$rows[$i]);


for($j=2;$j<=$colh[0];$j++){
$resultcolr[$j]=$colr[$j];
$resultcolr[$j]=preg_replace("/line0/i", $line[0], $resultcolr[$j]);
$resultcolr[$j]=preg_replace("/line1/i", round($line[1],2), $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line2/i", $line[2], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line3/i", $line[3], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line4/i", $line[4], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line5/i", $line[5], $resultcolr[$j]);
if(preg_match('/<a(.+)>(.*?)<\/a>/s', $resultcolr[$j], $matches))
$resultcolr[$j]=$matches[2];


}



if($colh[0]==4)
{
$csvfile[$i][0] = $i;
$csvfile[$i][1] = $resultcolr[2];
$csvfile[$i][2] = $resultcolr[3];
$csvfile[$i][3] = $resultcolr[4];
	
}
if(($colh[0]==3)or($colh[0]==5))
{
$csvfile[$i][0] = $i;
$csvfile[$i][1] = $resultcolr[2];
$csvfile[$i][2] = $resultcolr[3];
}

for($j=1;$j<=$colh[0];$j++)
$resultcolr[$j]="";

$i++;
}

for($i=1;$i<=$colh[0];$i++){
if (preg_match("/totalmb/i", $colf[$i])) {
preg_replace("/totalmb/i", $totalmb, $colf[$i]);
$colftext[$i]=round($totalmb,2);
}
}


$i = count($csvfile);

if($colh[0]==4)
{
$csvfile[$i][0] = $colftext[1];
$csvfile[$i][1] = $colftext[2];
$csvfile[$i][2] = $colftext[3];
$csvfile[$i][3] = $colftext[4];
}
if(($colh[0]==3)or($colh[0]==5))
{
$csvfile[$i][0] = $colftext[1];
$csvfile[$i][1] = $colftext[2];
$csvfile[$i][2] = $colftext[3];
}


	
$output = fopen("output/TrafficLogin_".$currentlogin."_".$querydate.".csv",'w') or die("Can't open file to write");

fprintf($output, "\xEF\xBB\xBF"); // UTF-8 BOM

foreach($csvfile as $product) {
    fputcsv($output, $product,';');
}
fclose($output) or die("Can't close file");


//generate CSV end

  }   
   

function CreateIpaddressCSV($repvars)
  {


$querydate = $repvars['querydate'];
$querydate2 = $repvars['querydate2']; 
$currentipaddress = $repvars['currentipaddress'];
$currentipaddressid=$repvars['currentipaddressid'];
$goodSitesList = $repvars['goodSitesList'];
$oneMegabyte=$repvars['oneMegabyte'];
$sortcolumn = $repvars['sortcolumn'];
$sortorder = $repvars['sortorder'];

$datestart=strtotime($querydate);
$dateend=strtotime($querydate2) + 86400;
$querydate = $querydate." - ".$querydate2; 


$repheader= "<h2>".$this->lang['stONEIPADRESSTRAFFIC']." ".$currentipaddress." ".$this->lang['stFOR']." ".$querydate."</h2>";



 /////////// ONE IPADDRESS TRAFFIC REPORT
$queryOneIpaddressTraffic="
	SELECT 
	   scsq_quicktraffic.site,
	   SUM(sizeinbytes) AS s
	 FROM scsq_quicktraffic
	 
	 WHERE ipaddress=".$currentipaddressid."
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	AND par=1
	 GROUP BY CRC32(scsq_quicktraffic.site) 
	 ORDER BY ".$sortcolumn." ".$sortorder."
;";


#postgre version
if($this->vars['dbtype']==1)
$queryOneIpaddressTraffic="
	SELECT 
	   scsq_quicktraffic.site,
	   SUM(sizeinbytes) AS s,
	   ''
	 FROM scsq_quicktraffic
	 
	 WHERE ipaddress=".$currentipaddressid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")   
	AND par=1
	 GROUP BY scsq_quicktraffic.site 
	 ORDER BY ".$sortcolumn." ".$sortorder."
;";



$colhtext[1]="#";
$colhtext[2]=$this->lang['stSITE'];
$colhtext[3]=$this->lang['stMEGABYTES'];
$colhtext[4]=$this->lang['stCATEGORY'];

$colftext[1]="";
$colftext[2]=$this->lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="";

//если потребуется включим. пока выключено (19.04.2020)
//если есть модуль категорий то добавим столбец
//if($category=="category")
//$colh[0]=4;
//else
$colh[0]=3;

$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$result=$this->ssq->query($queryOneIpaddressTraffic);

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";
$colr[4]="line2";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";


/////////// ONE IPADDRESS TRAFFIC REPORT END	 


$totalmb=0;

//PARSE SQL
while ($line = $this->ssq->fetch_array($result)) {
#if($enableUseiconv==1)
#$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));
$line[1]=$line[1] / $oneMegabyte;

$totalmb=$totalmb+$line[1];
@$rows[$numrow]=implode(";;",$line);
$numrow++;
}


//generate CSV file

if($colh[0]==4)
{
$csvfile[0][0] = $colhtext[1];
$csvfile[0][1] = $colhtext[2];
$csvfile[0][2] = $colhtext[3];
$csvfile[0][3] = $colhtext[4];

}
if(($colh[0]==3)or($colh[0]==5))
{
$csvfile[0][0] = $colhtext[1];
$csvfile[0][1] = $colhtext[2];
$csvfile[0][2] = $colhtext[3];
}

$i=1;

while ($i<$numrow) {
$line=explode(';;',$rows[$i]);


for($j=2;$j<=$colh[0];$j++){
$resultcolr[$j]=$colr[$j];
$resultcolr[$j]=preg_replace("/line0/i", $line[0], $resultcolr[$j]);
$resultcolr[$j]=preg_replace("/line1/i", round($line[1],2), $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line2/i", $line[2], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line3/i", $line[3], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line4/i", $line[4], $resultcolr[$j]);
//$resultcolr[$j]=preg_replace("/line5/i", $line[5], $resultcolr[$j]);
if(preg_match('/<a(.+)>(.*?)<\/a>/s', $resultcolr[$j], $matches))
$resultcolr[$j]=$matches[2];


}



if($colh[0]==4)
{
$csvfile[$i][0] = $i;
$csvfile[$i][1] = $resultcolr[2];
$csvfile[$i][2] = $resultcolr[3];
$csvfile[$i][3] = $resultcolr[4];
	
}
if(($colh[0]==3)or($colh[0]==5))
{
$csvfile[$i][0] = $i;
$csvfile[$i][1] = $resultcolr[2];
$csvfile[$i][2] = $resultcolr[3];
}

for($j=1;$j<=$colh[0];$j++)
$resultcolr[$j]="";

$i++;
}

for($i=1;$i<=$colh[0];$i++){
if (preg_match("/totalmb/i", $colf[$i])) {
preg_replace("/totalmb/i", $totalmb, $colf[$i]);
$colftext[$i]=round($totalmb,2);
}
}


$i = count($csvfile);

if($colh[0]==4)
{
$csvfile[$i][0] = $colftext[1];
$csvfile[$i][1] = $colftext[2];
$csvfile[$i][2] = $colftext[3];
$csvfile[$i][3] = $colftext[4];
}
if(($colh[0]==3)or($colh[0]==5))
{
$csvfile[$i][0] = $colftext[1];
$csvfile[$i][1] = $colftext[2];
$csvfile[$i][2] = $colftext[3];
}


	
$output = fopen("output/TrafficLogin_".$currentipaddress."_".$querydate.".csv",'w') or die("Can't open file to write");

fprintf($output, "\xEF\xBB\xBF"); // UTF-8 BOM

foreach($csvfile as $product) {
    fputcsv($output, $product,';');
}
fclose($output) or die("Can't close file");


//generate CSV end

  } 

   

  function Install()
  {



		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('ExportRep','modules/ExportRep/index.php');";

		
		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);
		

		$this->ssq->free_result($result);


		echo "".$this->lang['stINSTALLED']."<br /><br />";
	 }
  
 function Uninstall() #добавить LANG
  {

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'ExportRep';";

		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);

		echo "".$this->lang['stUNINSTALLED']."<br /><br />";

  }


}
?>
