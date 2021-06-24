<?php

//report для who download big files
//id 20
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stIPADDRESS'];
$colhtext[4]=$_lang['stMEGABYTES'];
$colhtext[5]=$_lang['stFROMWEBSITE'];


$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]=$_lang['stTOTAL'];
$colftext[4]="total_column_byte";
$colftext[5]="&nbsp;";

$colh[0]=5;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";


$colr[1]="<td>numrow</td>";
$colr[2]="<td><a href=javascript:GoPartlyReports(8,'".$dayormonth."','line4','line0','0','')>line0</a></td>";
$colr[3]="<td><a href=javascript:GoPartlyReports(11,'".$dayormonth."','line5','line2','1','')>line2</a></td>";
$colr[4]="<td>line1</td>";
$colr[5]="<td>line3</td>";


$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
$colf[5]="<td><b>".$colftext[5]."</b></td>";


?>