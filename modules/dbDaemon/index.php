<?php

#Build date Friday 24th of April 2020 12:57:48 PM
#Build revision 1.2


if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;

include("../../config.php");
include("module.php");

	if (file_exists("langs/".$language))
		include("langs/".$language);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="../../themes/<?php echo $globaltheme;?>/global.css"/>

</head>
<body>



<?php


echo "<h2>".$_lang['stDBDAEMONMODULE']."</h2><br />";

$start=microtime(true);

   
 
echo "<br><p>It`s ok, if you see only this line.</p>";
         


$end=microtime(true);

$runtime=$end - $start;

echo "<br /><br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];

$newdate=strtotime(date("d-m-Y"))-86400;
$newdate=date("d-m-Y",$newdate);



?>
<form name=fastdateswitch_form>
    <input type="hidden" name=date_field_hidden value="<?php echo $newdate; ?>">
    <input type="hidden" name=dom_field_hidden value="<?php echo 'day'; ?>">
    <input type="hidden" name=group_field_hidden value=0>
    <input type="hidden" name=groupname_field_hidden value=0>
    <input type="hidden" name=typeid_field_hidden value=0>
    </form>
</body>
</html>
