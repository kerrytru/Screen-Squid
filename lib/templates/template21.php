<?php

///////////// COUNT IPADDRESS ON LOGINS REPORT
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stQUANTITYIPADDRESS'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]="&nbsp;";
$colftext[4]="&nbsp;";

$colh[0]=3+$useLoginalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$colr[0]=1; ///report type 1 - prostoi, 2 - po vremeni, 3 - wide
$colr[1]="<td>numrow</td>";
$colr[2]="<td><a href=javascript:GoPartlyReports('id',8,'loginid','line2','loginname','line0','typeid','0')>line0</a></td>";
$colr[3]="<td><a href=javascript:GoPartlyReports('id',35,'loginid','line2','loginname','line0','typeid','0')>line4</a></td>";
$colr[4]="<td>line3</td>";



$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td>".$colftext[4]."</td>";


?>