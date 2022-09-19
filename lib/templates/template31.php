<?php
#report TRAFFIC BY HOURS LOGINS REPORT

$colhtext[1]="#";

if($typeid==0)
$colhtext[2]=$_lang['stLOGIN'];
if($typeid==1)
$colhtext[2]=$_lang['stIPADDRESS'];

$colhtext[3]="0";
$colhtext[4]="1";
$colhtext[5]="2";
$colhtext[6]="3";
$colhtext[7]="4";
$colhtext[8]="5";
$colhtext[9]="6";
$colhtext[10]="7";
$colhtext[11]="8";
$colhtext[12]="9";
$colhtext[13]="10";
$colhtext[14]="11";
$colhtext[15]="12";
$colhtext[16]="13";
$colhtext[17]="14";
$colhtext[18]="15";
$colhtext[19]="16";
$colhtext[20]="17";
$colhtext[21]="18";
$colhtext[22]="19";
$colhtext[23]="20";
$colhtext[24]="21";
$colhtext[25]="22";
$colhtext[26]="23";


$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="total_column_byte";
$colftext[4]="total_column_byte";
$colftext[5]="total_column_byte";
$colftext[6]="total_column_byte";
$colftext[7]="total_column_byte";
$colftext[8]="total_column_byte";
$colftext[9]="total_column_byte";
$colftext[10]="total_column_byte";
$colftext[11]="total_column_byte";
$colftext[12]="total_column_byte";
$colftext[13]="total_column_byte";
$colftext[14]="total_column_byte";
$colftext[15]="total_column_byte";
$colftext[16]="total_column_byte";
$colftext[17]="total_column_byte";
$colftext[18]="total_column_byte";
$colftext[19]="total_column_byte";
$colftext[20]="total_column_byte";
$colftext[21]="total_column_byte";
$colftext[22]="total_column_byte";
$colftext[23]="total_column_byte";
$colftext[24]="total_column_byte";
$colftext[25]="total_column_byte";
$colftext[26]="total_column_byte";


$colh[0]=26;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";
$colh[6]="<th>".$colhtext[6]."</th>";
$colh[7]="<th>".$colhtext[7]."</th>";
$colh[8]="<th>".$colhtext[8]."</th>";
$colh[9]="<th>".$colhtext[9]."</th>";
$colh[10]="<th>".$colhtext[10]."</th>";
$colh[11]="<th>".$colhtext[11]."</th>";
$colh[12]="<th>".$colhtext[12]."</th>";
$colh[13]="<th>".$colhtext[13]."</th>";
$colh[14]="<th>".$colhtext[14]."</th>";
$colh[15]="<th>".$colhtext[15]."</th>";
$colh[16]="<th>".$colhtext[16]."</th>";
$colh[17]="<th>".$colhtext[17]."</th>";
$colh[18]="<th>".$colhtext[18]."</th>";
$colh[19]="<th>".$colhtext[19]."</th>";
$colh[20]="<th>".$colhtext[20]."</th>";
$colh[21]="<th>".$colhtext[21]."</th>";
$colh[22]="<th>".$colhtext[22]."</th>";
$colh[23]="<th>".$colhtext[23]."</th>";
$colh[24]="<th>".$colhtext[24]."</th>";
$colh[25]="<th>".$colhtext[25]."</th>";
$colh[26]="<th>".$colhtext[26]."</th>";


$colr[1]="<td>numrow</td>";

if($typeid==0)
$colr[2]="<td><a href=javascript:GoPartlyReports('id',8,'loginid','line1','loginname','line0','typeid','0')>line0</a></td>";
if($typeid==1)
$colr[2]="<td><a href=javascript:GoPartlyReports('id',11,'ipaddressid','line1','ipaddressname','line0','typeid','1')>line0</a></td>";


$colr[3]="<td>line2</td>";
$colr[4]="<td>line3</td>";
$colr[5]="<td>line4</td>";
$colr[6]="<td>line5</td>";
$colr[7]="<td>line6</td>";
$colr[8]="<td>line7</td>";
$colr[9]="<td>line8</td>";
$colr[10]="<td>line9</td>";
$colr[11]="<td>line10</td>";
$colr[12]="<td>line11</td>";
$colr[13]="<td>line12</td>";
$colr[14]="<td>line13</td>";
$colr[15]="<td>line14</td>";
$colr[16]="<td>line15</td>";
$colr[17]="<td>line16</td>";
$colr[18]="<td>line17</td>";
$colr[19]="<td>line18</td>";
$colr[20]="<td>line19</td>";
$colr[21]="<td>line20</td>";
$colr[22]="<td>line21</td>";
$colr[23]="<td>line22</td>";
$colr[23]="<td>line23</td>";
$colr[24]="<td>line24</td>";
$colr[25]="<td>line25</td>";
$colr[26]="<td>line26</td>";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
$colf[5]="<td><b>".$colftext[5]."</b></td>";
$colf[6]="<td><b>".$colftext[6]."</b></td>";
$colf[7]="<td><b>".$colftext[7]."</b></td>";
$colf[8]="<td><b>".$colftext[8]."</b></td>";
$colf[9]="<td><b>".$colftext[9]."</b></td>";
$colf[10]="<td><b>".$colftext[10]."</b></td>";
$colf[11]="<td><b>".$colftext[11]."</b></td>";
$colf[12]="<td><b>".$colftext[12]."</b></td>";
$colf[13]="<td><b>".$colftext[13]."</b></td>";
$colf[14]="<td><b>".$colftext[14]."</b></td>";
$colf[15]="<td><b>".$colftext[15]."</b></td>";
$colf[16]="<td><b>".$colftext[16]."</b></td>";
$colf[17]="<td><b>".$colftext[17]."</b></td>";
$colf[18]="<td><b>".$colftext[18]."</b></td>";
$colf[19]="<td><b>".$colftext[19]."</b></td>";
$colf[20]="<td><b>".$colftext[20]."</b></td>";
$colf[21]="<td><b>".$colftext[21]."</b></td>";
$colf[22]="<td><b>".$colftext[22]."</b></td>";
$colf[23]="<td><b>".$colftext[23]."</b></td>";
$colf[24]="<td><b>".$colftext[24]."</b></td>";
$colf[25]="<td><b>".$colftext[25]."</b></td>";
$colf[26]="<td><b>".$colftext[26]."</b></td>";

?>