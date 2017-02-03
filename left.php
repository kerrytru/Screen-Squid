<?php
#build 20170203
include("config.php");

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
* {padding:0;margin:0;}
ul {list-style-type:none;margin:0.5em}
li {margin-bottom:20px;}
body {margin:0.5em;padding:0.5em}

</style>
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
		(obj1.className=="itemshown") ? obj2.innerHTML="<img border='0' src='img/gray-open.gif' alt='[&ndash;]'>" : obj2.innerHTML="<img border='0' src='images/gray-closed.gif' alt='[+]'>"; 
	}



</script>

<body>
<br />
<?php

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;


if(isset($_GET['id']))
  $idmenu=$_GET['id'];
else
  $idmenu=0;


if($idmenu==1)
  echo "
    <ul>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",8)\">".$_lang['stONELOGINTRAFFIC']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",9)\">".$_lang['stTOPSITESTRAFFIC']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",10)\">".$_lang['stTRAFFICBYHOURS']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",22)\">".$_lang['stVISITINGWEBSITELOGINS'] ." ".$_lang['stBYDAYTIME']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",35)\">".$_lang['stONELOGINIPTRAFFIC']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",46)\">".$_lang['stMIMETYPESTRAFFIC']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",56)\">".$_lang['stPOPULARSITES']."</a>
      <li><a href='mainmenu.php'>".$_lang['stBACK']."</a>
    </ul>";

if($idmenu==2)
  echo "
    <ul>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",11)\">".$_lang['stONEIPADRESSTRAFFIC']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",12)\">".$_lang['stTOPSITESTRAFFIC']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",13)\">".$_lang['stTRAFFICBYHOURS']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",23)\">".$_lang['stVISITINGWEBSITEIPADDRESS']." ".$_lang['stBYDAYTIME']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",36)\">".$_lang['stONEIPADDRESSLOGINSTRAFFIC']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",47)\">".$_lang['stMIMETYPESTRAFFIC']."</a>
      <li><a href=\"javascript:GoPartlyReport(".$srv.",57)\">".$_lang['stPOPULARSITES']."</a>
      <li><a href='mainmenu.php'>".$_lang['stBACK']."</a>
    </ul>";


if($idmenu==4)
  echo "
    <ul>
      <li><a href=\"javascript:GoReport(".$srv.",25)\">".$_lang['stONEGROUPTRAFFIC']."</a>
      <li><a href=\"javascript:GoReport(".$srv.",26)\">".$_lang['stONEGROUPTRAFFIC']." ".$_lang['stEXTENDED']."</a>
      <li><a href=\"javascript:GoReport(".$srv.",27)\">".$_lang['stTOPSITESTRAFFIC']."</a>
      <li><a href=\"javascript:GoReport(".$srv.",28)\">".$_lang['stTRAFFICBYHOURS']."</a>
      <li><a href=\"javascript:GoReport(".$srv.",29)\">".$_lang['stWHODOWNLOADBIGFILES']."</a>
      <li><a href=\"javascript:GoReport(".$srv.",55)\">".$_lang['stPOPULARSITES']."</a>
      <li><a href='mainmenu.php'>".$_lang['stBACK']."</a>
    </ul>";
?>

</body>
</html>
