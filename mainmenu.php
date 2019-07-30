<?php 
#build 20170105
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
* {padding:0;margin:0;}
ul {list-style-type:none;padding-left:1em}
body {margin:0.5em;padding:0.5em}
</style>

<?php include("config.php");

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0
 ?>

<link rel="stylesheet" type="text/css" href="javascript/example.css"/>
<script language=JavaScript>
function TwoByOne(frame1, frame2)
{
parent.left.location.href=frame1;
parent.right.location.href=frame2;
}

function GoReport(srv,id)
{
if((id==21) || (id==39) || (id==40) ) {
parent.right.location.href='reports/reports.php?srv='+srv+'&id='+id+'&date='+parent.right.window.document.fastdateswitch_form.date_field_hidden.value
+'&dom=month&groupname='+parent.right.window.document.fastdateswitch_form.groupname_field_hidden.value
+'&typeid='+parent.right.window.document.fastdateswitch_form.typeid_field_hidden.value
+'&group='+parent.right.window.document.fastdateswitch_form.group_field_hidden.value;
}
else
{
parent.right.location.href='reports/reports.php?srv='+srv+'&id='+id+'&date='+parent.right.window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+parent.right.window.document.fastdateswitch_form.dom_field_hidden.value
+'&groupname='+parent.right.window.document.fastdateswitch_form.groupname_field_hidden.value
+'&typeid='+parent.right.window.document.fastdateswitch_form.typeid_field_hidden.value
+'&group='+parent.right.window.document.fastdateswitch_form.group_field_hidden.value;
}

}


function GoPartlyReport(srv,id)
{
parent.right.location.href='reports/reports.php?srv='+srv+'&id='+id+'&date='+parent.right.window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+parent.right.window.document.fastdateswitch_form.dom_field_hidden.value+'&login='+parent.right.window.document.fastdateswitch_form.login_field_hidden.value+'&loginname='+parent.right.window.document.fastdateswitch_form.loginname_field_hidden.value+'&ip='+parent.right.window.document.fastdateswitch_form.ip_field_hidden.value+'&ipname='+parent.right.window.document.fastdateswitch_form.ipname_field_hidden.value;
}


function toggleview (id1,id2) {
		var obj1 = document.getElementById(id1);
		var obj2 = document.getElementById(id2);
		(obj1.className=="itemshown") ? obj1.className="itemhidden" : obj1.className="itemshown"; 
		(obj1.className=="itemshown") ? obj2.innerHTML="<img border='0' src='img/gray-open.png' alt='[&ndash;]'>" : obj2.innerHTML="<img border='0' src='img/gray-closed.png' alt='[+]'>"; 
	}

function GoOnlineReport(srv,id)
{
parent.right.location.href='reports/oreports.php?srv='+srv+'&id='+id+'&date='+parent.right.window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+parent.right.window.document.fastdateswitch_form.dom_field_hidden.value;
}



</script>


<body>
<br />
<ul>
  <li><b>Screen Squid v.<?php echo $vers; ?></b>
</ul>
<br />
<?php
$srv=0;
for($i=0;$i<count($srvname);$i++)
{
echo "
<div class='linkwithicon'><a href=\"javascript:toggleview('srvname".$srv."','togglesrvname".$srv."')\" id='togglesrvname".$srv."'><img border='0' src='img/gray-closed.png' alt='[+]'></a>
<div class='aftericon'><a href=\"javascript:toggleview('srvname".$srv."','togglesrvname".$srv."')\" id='togglesrvname".$srv."'><font color=#000000>".$srvname[$srv]."</font></a></div></div>
<div class='itemhidden' id='srvname".$srv."'>

<div class='linkindented'><a href=\"javascript:GoReport(".$srv.",49)\">".$_lang['stDASHBOARD']."</a></div>

<!--online reports-->

<div class='linkwithicon1'><a href=\"javascript:toggleview('oreports".$srv."','toggleoreports".$srv."')\" id='toggleoreports".$srv."'><img border='0' src='img/gray-closed.png' alt='[+]'></a>
<div class='aftericon'><a href=\"javascript:toggleview('oreports".$srv."','toggleoreports".$srv."')\" id='toggleoreports".$srv."'><font color=#000000>".$_lang['stONLINE']."</font></a></div></div>

<div class='itemhidden' id='oreports".$srv."'>

<div class='linkindented1'><a href=\"javascript:GoOnlineReport(".$srv.",1)\">".$_lang['stACTIVEIPADDRESS']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoOnlineReport(".$srv.",2)\">System</a></div>
</div>
<!--online reports end-->

<div class='linkwithicon1'><a href=\"javascript:toggleview('reports".$srv."','togglereports".$srv."')\" id='togglereports".$srv."'><img border='0' src='img/gray-closed.png' alt='[+]'></a>
<div class='aftericon'><a href=\"javascript:toggleview('reports".$srv."','togglereports".$srv."')\" id='togglereports".$srv."'><font color=#000000>".$_lang['stREPORTS']."</font></a></div></div>

<div class='itemhidden' id='reports".$srv."'>

<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",1)\">".$_lang['stLOGINSTRAFFIC']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",2)\">".$_lang['stIPADDRESSTRAFFIC']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",3)\">".$_lang['stSITESTRAFFIC']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",4)\">".$_lang['stTOPSITESTRAFFIC']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",5)\">".$_lang['stTOPLOGINSTRAFFIC']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",6)\">".$_lang['stTOPIPTRAFFIC']."</a></div>

<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",7)\">".$_lang['stTRAFFICBYHOURS']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",14)\">".$_lang['stLOGINSTRAFFICWIDE']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",15)\">".$_lang['stIPADDRESSTRAFFICWIDE']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",16)\">".$_lang['stIPADDRESSTRAFFICWITHRESOLVE']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",17)\">".$_lang['stPOPULARSITES']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",20)\">".$_lang['stWHODOWNLOADBIGFILES']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",39)\">".$_lang['stTRAFFICBYPERIODDAY']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",40)\">".$_lang['stTRAFFICBYPERIODDAYNAME']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",21)\">".$_lang['stTRAFFICBYPERIOD']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",30)\">".$_lang['stHTTPSTATUSES']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",37)\">".$_lang['stLOGINSIPTRAFFIC']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",38)\">".$_lang['stIPADDRESSLOGINSTRAFFIC']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",45)\">".$_lang['stMIMETYPESTRAFFIC']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",48)\">".$_lang['stDOMAINZONESTRAFFIC']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",50)\">".$_lang['stTRAFFICBYHOURSLOGINS']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",51)\">".$_lang['stTRAFFICBYHOURSIPADDRESS']."</a></div>
<div class='linkindented1'><a href=\"javascript:GoReport(".$srv.",52)\">".$_lang['stTRAFFICBYCATEGORIES']."</a></div>
</div>
<!--reports-->

<!-- group reports-->

<div class='linkindented'><a href=\"javascript:GoReport(".$srv.",24)\">".$_lang['stBYGROUP']."</a></div>

<!--group reports end-->
<div class='linkwithicon'><a href='right.php?srv=".$srv."&id=4' target=right>".$_lang['stFASTSEARCH']."</a></div>
<div class='linkwithicon'><a href=\"right.php?srv=".$srv."&id=1\" target=right>".$_lang['stSTATS']."</a></div>
<div class='linkwithicon'><a href=\"right.php?srv=".$srv."&id=5\" target=right>".$_lang['stLOGTABLE']."</a></div>
<div class='linkwithicon'><a href=\"right.php?srv=".$srv."&id=2\" target=right>".$_lang['stALIASES']."</a></div>
<div class='linkwithicon'><a href=\"right.php?srv=".$srv."&id=3\" target=right>".$_lang['stGROUPS']."</a></div>
<div class='linkwithicon'><a href=\"right.php?srv=".$srv."&id=6\" target=right>".$_lang['stCONFIG']."</a></div>


<!--moduletemplate-->

<div class='linkwithicon1'><a href=\"javascript:toggleview('modules".$srv."','togglemodules".$srv."')\" id='togglemodules".$srv."'><img border='0' src='img/gray-closed.png' alt='[+]'></a>
<div class='aftericon'><a href=\"javascript:toggleview('modules".$srv."','togglemodules".$srv."')\" id='togglemodules".$srv."'><font color=#000000>Модули</font></a></div></div>

<div class='itemhidden' id='modules".$srv."'>

<div class='linkindented1'><a href=\"right.php?srv=".$srv."&id=7\" target=right>".$_lang['stMODULEMANAGER']."</a></div>
";

$addr=$address[$srv];
$usr=$user[$srv];
$psw=$pass[$srv];
$dbase=$db[$srv];

$connection=mysqli_connect("$addr","$usr","$psw","$dbase");

$queryModules="select name from scsq_modules order by name asc;";

$result=mysqli_query($connection,$queryModules,MYSQLI_USE_RESULT);

while($line = mysqli_fetch_row($result)) {

echo "<div class='linkindented1'><a href=\"modules/".$line[0]."/index.php\" target=right>".$line[0]."</a></div>";
}
mysqli_free_result($result);
echo "

</div>


</div> <!--srvname-->


 
";
$srv++;
}


?>

</body>
</html>
