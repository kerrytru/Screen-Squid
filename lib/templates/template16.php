<?php
#report GRouP LOGIN/IPADDRESS WIDE

$colhtext[1]="#";

if($typeid==0)
$colhtext[2]=$_lang['stLOGIN'];
if($typeid==1)
$colhtext[2]=$_lang['stIPADDRESS'];

/*
if(($useLoginalias==1)&&($typeid==0))
$colhtext[3]=$_lang['stALIAS'];

if(($useIpaddressalias==1)&&($typeid==1))
$colhtext[3]=$_lang['stALIAS'];
*/

$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stFROMCACHEMB'];
$colhtext[5]=$_lang['stDIRECTMB'];
$colhtext[6]=$_lang['stFROMCACHEPERCENT'];
$colhtext[7]=$_lang['stDIRECTPERCENT'];


$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="total_column_byte";
$colftext[4]="total_column_byte";
$colftext[5]="total_column_byte";
$colftext[6]="&nbsp;";
$colftext[7]="&nbsp;";

$colh[0]=7;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";
$colh[6]="<th>".$colhtext[6]."</th>";
$colh[7]="<th>".$colhtext[7]."</th>";

$colr[1]="<td>numrow</td>";

if($typeid==0)
$colr[2]="<td><a href=javascript:GoPartlyReports(8,'".$dayormonth."','line6','line0','0','')>line0</a></td>";
if($typeid==1)
$colr[2]="<td><a href=javascript:GoPartlyReports(11,'".$dayormonth."','line6','line0','0','')>line0</a></td>";


$colr[3]="<td>line3</td>";
$colr[4]="<td>line1</td>";
$colr[5]="<td>line2</td>";
$colr[6]="<td>line4</td>";
$colr[7]="<td>line5</td>";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
$colf[5]="<td><b>".$colftext[5]."</b></td>";
$colf[6]="<td><b>".$colftext[6]."</b></td>";
$colf[7]="<td><b>".$colftext[7]."</b></td>";

?>