<?php
#report IPADDRESS WIDE

$colhtext[1]="#";
$colhtext[2]=$_lang['stIPADDRESS'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stDENIEDMEGABYTES'];
$colhtext[5]=$_lang['stINCOMEMEGABYTES'];
$colhtext[6]=$_lang['stFROMCACHEMB'];
$colhtext[7]=$_lang['stDIRECTMB'];
$colhtext[8]=$_lang['stFROMCACHEPERCENT'];
$colhtext[9]=$_lang['stDIRECTPERCENT'];


$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="total_column_byte";
$colftext[4]="total_column_byte";
$colftext[5]="total_column_byte";
$colftext[6]="total_column_byte";
$colftext[7]="total_column_byte";
$colftext[8]="&nbsp;";
$colftext[9]="&nbsp;";

$colh[0]=9;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";
$colh[6]="<th>".$colhtext[6]."</th>";
$colh[7]="<th>".$colhtext[7]."</th>";
$colh[8]="<th>".$colhtext[8]."</th>";
$colh[9]="<th>".$colhtext[9]."</th>";

$colr[1]="<td>numrow</td>";
$colr[2]="<td><a href=javascript:GoPartlyReports(11,'".$dayormonth."','line8','line0','1','')>line0</a></td>";
$colr[3]="<td>line3</td>";
$colr[4]="<td>line4</td>";
$colr[5]="<td>line5</td>";
$colr[6]="<td>line1</td>";
$colr[7]="<td>line2</td>";
$colr[8]="<td>line6</td>";
$colr[9]="<td>line7</td>";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
$colf[5]="<td><b>".$colftext[5]."</b></td>";
$colf[6]="<td><b>".$colftext[6]."</b></td>";
$colf[7]="<td><b>".$colftext[7]."</b></td>";
$colf[8]="<td><b>".$colftext[8]."</b></td>";
$colf[9]="<td><b>".$colftext[9]."</b></td>";



?>