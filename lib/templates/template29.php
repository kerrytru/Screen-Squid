<?php

//report для ONE LOGIN MIME TYPES TRAFFIC REPORT
//id 46
$colhtext[1]="#";
$colhtext[2]=$_lang['stMIME'];
$colhtext[3]=$_lang['stMEGABYTES'];


$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="total_column_byte";



$colh[0]=3;

$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";



$colr[1]="<td>numrow</td>";
$colr[2]="<td><a href=\"javascript:GoPartlyReports(60,'".$dayormonth."','".$currentipaddressid."','line0',1,'".$currentipaddress."')\">line0</a></td>";
$colr[3]="<td>line1</td>";


$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";



?>