<?php

//report для сайтов
//id 3

$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stWHO'];
$colhtext[5]=$_lang['stBYDAYTIME'];
$colhtext[6]=$_lang['stCATEGORY'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="total_column_byte";
$colftext[4]="&nbsp;";
$colftext[5]="&nbsp;";
$colftext[6]="&nbsp;";

//если есть модуль категорий то добавим столбец
if($category==", category")
$colh[0]=6;
else
$colh[0]=5;

$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";
$colh[6]="<th>".$colhtext[6]."</th>";



$colr[0]=1; 
$colr[1]="<td>numrow</td>";

if($globalSS['gethostbyaddr']==1)
    $colr[2]="<td>line0 gethostbyaddr_line_0</td>";
else
$colr[2]="<td>line0</td>";

$colr[3]="<td>line1</td>";
$colr[4]="<td><a href=javascript:GoPartlyReports('id',18,'sitetypeid','line2','typeid','0','site','line0')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports('id',19,'sitetypeid','line2','typeid','1','site','line0')>".$_lang['stIPADDRESSES']."</a></td>";
$colr[5]="<td><a href=javascript:GoPartlyReports('id',53,'typeid','0','site','line0')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports('id',54,'typeid','1','site','line0')>".$_lang['stIPADDRESSES']."</a></td>";
$colr[6]="<td>getcategory</td>"; ///category

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td>".$colftext[4]."</td>";
$colf[5]="<td>".$colftext[5]."</td>";
$colf[6]="<td>".$colftext[6]."</td>";


?>