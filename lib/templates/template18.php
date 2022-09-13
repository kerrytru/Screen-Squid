<?php
#report HTTP status
$colhtext[1]="#";
$colhtext[2]=$_lang['stHTTPSTATUS'];
$colhtext[3]=$_lang['stQUANTITY'];
$colhtext[4]=$_lang['stWHO'];

$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]="&nbsp;";
$colftext[4]="&nbsp;";

$colh[0]=4;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";


$colr[1]="<td>numrow</td>";
$colr[2]="<td>line0</td>";
$colr[3]="<td>line2</td>";
$colr[4]="<td><a href=javascript:GoPartlyReports('id',31,'httpstatusid','line3','httpstatusname','line0')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports('id',32,'httpstatusid','line3','httpstatusname','line0')>".$_lang['stIPADDRESSES']."</a></td>";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";

?>