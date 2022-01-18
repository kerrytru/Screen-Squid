<?php

$colhtext[1]="#";
$colhtext[2]=$_lang['stIPADDRESS'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stDENIEDMEGABYTES'];
$colhtext[5]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="total_column_byte";
$colftext[4]="total_column_byte";
$colftext[5]="&nbsp;";

$colh[0]=4+$useIpaddressalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th aligh=right>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";


$colr[0]=1; ///report type 1 - prostoi, 2 - po vremeni, 3 - wide
$colr[1]="<td>numrow</td>";
$colr[2]="<td><a href=\"javascript:GoPartlyReports(11,'".$dayormonth."','line2','line0 (line3)',1,'')\">line0</a></td>";
$colr[3]="<td>line1</td>";
$colr[4]="<td>line4</td>";
$colr[5]="<td>line3 <input id=\"ButtonAdd\" type=\"button\" value=\"+\" onclick=\"showModalPopUp(".$globalSS['connectionParams']['srv'].",'line2',1)\" /></td>";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td>".$colftext[4]."</td>";
$colf[5]="<td>".$colftext[5]."</td>";


?>