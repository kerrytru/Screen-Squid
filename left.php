<?php

#Build date Friday 24th of April 2020 16:31:16 PM
#Build revision 1.1

include("config.php");

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="themes/<?php echo $globaltheme;?>/global.css"/>

<!-- The xtree script file -->
<script src="javascript/xtree.js"></script>



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



</script>

<body class="browser">
<br />
<?php

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;



echo "
<div style='float:left; top: 70px; left: 10px; height: 100%; width: 100%; padding: 5px; overflow: auto;'>
<script language=JavaScript>

webFXTreeConfig.rootIcon		= 'img/themes/default/Servers.png';
webFXTreeConfig.openRootIcon	= 'img/themes/default/Servers.png';
webFXTreeConfig.folderIcon		= '';
webFXTreeConfig.openFolderIcon	= '';
webFXTreeConfig.fileIcon		= 'img/themes/default/Report.png';
webFXTreeConfig.iIcon			= 'img/themes/default/I.png';
webFXTreeConfig.lIcon			= 'img/themes/default/L.png';
webFXTreeConfig.lMinusIcon		= 'img/themes/default/Lminus.png';
webFXTreeConfig.lPlusIcon		= 'img/themes/default/Lplus.png';
webFXTreeConfig.tIcon			= 'img/themes/default/T.png';
webFXTreeConfig.tMinusIcon		= 'img/themes/default/Tminus.png';
webFXTreeConfig.tPlusIcon		= 'img/themes/default/Tplus.png';
webFXTreeConfig.blankIcon		= 'img/themes/default/blank.png';
webFXTreeConfig.loadingIcon		= 'img/themes/default/Loading.gif';
webFXTreeConfig.loadingText		= 'Loading...';
webFXTreeConfig.errorIcon		= 'img/themes/default/ObjectNotFound.png';
webFXTreeConfig.errorLoadingText = 'Error Loading';
webFXTreeConfig.reloadText		= 'Click to reload';

if (document.getElementById) {

";

if(isset($_GET['id']))
  $idmenu=$_GET['id'];
else
  $idmenu=0;


if($idmenu==1)

echo "

	var reports = new WebFXTree('".$_GET['loginname']."');
	reports.setBehavior('classic');
//First Level
	
	

//Second Level

	reports.icon = 'img/themes/default/User.png';
	reports.openIcon = 'img/themes/default/User.png';

	
//Reports	
	reports.add(new WebFXTreeItem('".$_lang['stDASHBOARD']."','javascript:GoPartlyReport(".$srv.",61);'));
	reports.add(new WebFXTreeItem('".$_lang['stONELOGINTRAFFIC']."','javascript:GoPartlyReport(".$srv.",8)'));
	reports.add(new WebFXTreeItem('".$_lang['stTOPSITESTRAFFIC']."','javascript:GoPartlyReport(".$srv.",9)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURS']."','javascript:GoPartlyReport(".$srv.",10)'));
	reports.add(new WebFXTreeItem('".$_lang['stVISITINGWEBSITELOGINS']." ".$_lang['stBYDAYTIME']."','javascript:GoPartlyReport(".$srv.",22)'));
	reports.add(new WebFXTreeItem('".$_lang['stONELOGINIPTRAFFIC']."','javascript:GoPartlyReport(".$srv.",35)'));
	reports.add(new WebFXTreeItem('".$_lang['stMIMETYPESTRAFFIC']."','javascript:GoPartlyReport(".$srv.",46)'));
	reports.add(new WebFXTreeItem('".$_lang['stPOPULARSITES']."','javascript:GoPartlyReport(".$srv.",56)'));
	reports.add(new WebFXTreeItem('".$_lang['stBACK']."','mainmenu.php'));

	
	

";




if($idmenu==2)
echo "


//First Level

	var reports = new WebFXTree('".$_GET['ipname']."');
	reports.setBehavior('classic');
	

//Second Level

	reports.icon = 'img/themes/default/User.png';
	reports.openIcon = 'img/themes/default/User.png';

	
//Reports	
	reports.add(new WebFXTreeItem('".$_lang['stDASHBOARD']."','javascript:GoPartlyReport(".$srv.",62);'));
	reports.add(new WebFXTreeItem('".$_lang['stONEIPADRESSTRAFFIC']."','javascript:GoPartlyReport(".$srv.",11)'));
	reports.add(new WebFXTreeItem('".$_lang['stTOPSITESTRAFFIC']."','javascript:GoPartlyReport(".$srv.",12)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURS']."','javascript:GoPartlyReport(".$srv.",13)'));
	reports.add(new WebFXTreeItem('".$_lang['stVISITINGWEBSITEIPADDRESS']." ".$_lang['stBYDAYTIME']."','javascript:GoPartlyReport(".$srv.",23)'));
	reports.add(new WebFXTreeItem('".$_lang['stONEIPADDRESSLOGINSTRAFFIC']."','javascript:GoPartlyReport(".$srv.",36)'));
	reports.add(new WebFXTreeItem('".$_lang['stMIMETYPESTRAFFIC']."','javascript:GoPartlyReport(".$srv.",47)'));
	reports.add(new WebFXTreeItem('".$_lang['stPOPULARSITES']."','javascript:GoPartlyReport(".$srv.",57)'));
	reports.add(new WebFXTreeItem('".$_lang['stBACK']."','mainmenu.php'));

	
	
";


if($idmenu==4)
echo "


//First Level
	var reports = new WebFXTree('".$_GET['groupname']."');
	reports.setBehavior('classic');
	
//Second Level

	reports.icon = 'img/themes/default/UserGroup.png';
	reports.openIcon = 'img/themes/default/UserGroup.png';

	
//Reports	
	reports.add(new WebFXTreeItem('".$_lang['stDASHBOARD']."','javascript:GoReport(".$srv.",63);'));
	reports.add(new WebFXTreeItem('".$_lang['stONEGROUPTRAFFIC']."','javascript:GoReport(".$srv.",25)'));
	reports.add(new WebFXTreeItem('".$_lang['stONEGROUPTRAFFIC']." ".$_lang['stEXTENDED']."','javascript:GoReport(".$srv.",26)'));
	reports.add(new WebFXTreeItem('".$_lang['stTOPSITESTRAFFIC']."','javascript:GoReport(".$srv.",27)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURS']."','javascript:GoReport(".$srv.",28)'));
	reports.add(new WebFXTreeItem('".$_lang['stWHODOWNLOADBIGFILES']."','javascript:GoReport(".$srv.",29)'));
	reports.add(new WebFXTreeItem('".$_lang['stPOPULARSITES']."','javascript:GoReport(".$srv.",55)'));
	reports.add(new WebFXTreeItem('".$_lang['stBACK']."','mainmenu.php'));

	

";


echo "

document.write(reports);
}
</script>
</div>";
?>

</body>
</html>
