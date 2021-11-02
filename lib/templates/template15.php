<?php
#report ONE GROUP report


$colhtext[1]="#";
$colhtext[2]=$_lang['stALIAS'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="total_column_byte";
$colftext[4]="&nbsp;";


$colh[0]=3;

$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";


$colr[1]="<td>numrow</td>";
$colr[2]="<td><a href=\"javascript:GoPartlyReports(8+3*line4,'".$dayormonth."','line5','line0',0+line4,'')\">line0</a></td>";

$colr[3]="<td>line1</td>";
$colr[4]="<td>line3</td>";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";

?>