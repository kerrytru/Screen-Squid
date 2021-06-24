<?php

//report WHO LOGIN VISIT SITE ONE HOUR REPORT

$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="total_column_byte";
$colftext[4]="&nbsp;";

$colh[0]=3+$useLoginalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."<a href=?></a></th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$colr[0]=1; ///report type 1 - prostoi, 2 - po vremeni, 3 - wide
$colr[1]="<td>numrow</td>";
$colr[2]="<td><a href=javascript:GoPartlyReports(43,'".$dayormonth."','line2','line0','0','".$currenthour."')>line0</a></td>";
$colr[3]="<td>line1</td>";
$colr[4]="<td>line3</td>";



$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td>".$colftext[4]."</td>";


?>