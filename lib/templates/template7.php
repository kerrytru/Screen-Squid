<?php

//report для by hour
//id 7


$colhtext[1]=$_lang['stHOURS'];
$colhtext[2]=$_lang['stMEGABYTES'];
$colhtext[3]=$_lang['stWHO'];


$colftext[1]=$_lang['stTOTAL'];
$colftext[2]="total_column_byte";
$colftext[3]="&nbsp;";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";

$colr[1]="<td>line0</td>";
$colr[2]="<td>line1</td>";
$colr[3]="<td><a href=javascript:GoPartlyReports('id',41,'typeid','0','hour','line2')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports('id',42,'typeid','0','hour','line2')>".$_lang['stIPADDRESSES']."</a></td>";



$colf[1]="<td><b>".$colftext[1]."</b></td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td>".$colftext[3]."</td>";

?>