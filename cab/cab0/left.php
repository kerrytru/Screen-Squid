<?php

#Build date Saturday 16th of May 2020 17:55:55 PM
#Build revision 1.1

include("../../config.php");


$srv=$_COOKIE['srv'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="../../themes/<?php echo $globaltheme; ?>/global.css"/>

<!-- The xtree script file -->
<script src="../../javascript/xtree.js"></script>


<script language=JavaScript>
function TwoByOne(frame1, frame2)
{
parent.left.location.href=frame1;
parent.right.location.href=frame2;
}



function GoReport(srv,id)
{
if(id==999) {
	parent.right.location.href='reports/reports.php?srv='+srv+'&id='+id;
}
else {

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


}


function GoPartlyReport(srv,id)
{
	
parent.right.location.href='reports/reports.php?srv='+srv+'&id='+id+'&date='+parent.right.window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+parent.right.window.document.fastdateswitch_form.dom_field_hidden.value+'&login='+parent.right.window.document.fastdateswitch_form.login_field_hidden.value+'&loginname='+parent.right.window.document.fastdateswitch_form.loginname_field_hidden.value+'&ip='+parent.right.window.document.fastdateswitch_form.ip_field_hidden.value+'&ipname='+parent.right.window.document.fastdateswitch_form.ipname_field_hidden.value;
}


function toggleview (id1,id2) {
		var obj1 = document.getElementById(id1);
		var obj2 = document.getElementById(id2);
		(obj1.className=="itemshown") ? obj1.className="itemhidden" : obj1.className="itemshown"; 
		(obj1.className=="itemshown") ? obj2.innerHTML="<img border='0' src='../img/gray-open.gif' alt='[&ndash;]'>" : obj2.innerHTML="<img border='0' src='../img/gray-closed.gif' alt='[+]'>"; 
	}



</script>
</head>


<body class="browser">
<br />
<?php



echo "
<div style='float:left; top: 70px; left: 10px; height: 100%; width: 100%; padding: 5px; overflow: auto;'>
<script language=JavaScript>

webFXTreeConfig.rootIcon		= '../../img/themes/default/Servers.png';
webFXTreeConfig.openRootIcon	= '../../img/themes/default/Servers.png';
webFXTreeConfig.folderIcon		= '';
webFXTreeConfig.openFolderIcon	= '';
webFXTreeConfig.fileIcon		= '../../img/themes/default/Report.png';
webFXTreeConfig.iIcon			= '../../img/themes/default/I.png';
webFXTreeConfig.lIcon			= '../../img/themes/default/L.png';
webFXTreeConfig.lMinusIcon		= '../../img/themes/default/Lminus.png';
webFXTreeConfig.lPlusIcon		= '../../img/themes/default/Lplus.png';
webFXTreeConfig.tIcon			= '../../img/themes/default/T.png';
webFXTreeConfig.tMinusIcon		= '../../img/themes/default/Tminus.png';
webFXTreeConfig.tPlusIcon		= '../../img/themes/default/Tplus.png';
webFXTreeConfig.blankIcon		= '../../img/themes/default/blank.png';
webFXTreeConfig.loadingIcon		= '../../img/themes/default/Loading.gif';
webFXTreeConfig.loadingText		= 'Loading...';
webFXTreeConfig.errorIcon		= '../../img/themes/default/ObjectNotFound.png';
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

	var reports = new WebFXTree('".$_COOKIE['realname']."');
	reports.setBehavior('classic');
//First Level
	
	

//Second Level

	reports.icon = '../../img/themes/default/User.png';
	reports.openIcon = '../../img/themes/default/User.png';

	
//Reports	
	reports.add(new WebFXTreeItem('".$_lang['stDASHBOARD']."','javascript:GoPartlyReport(".$srv.",61);'));
	reports.add(new WebFXTreeItem('".$_lang['stONELOGINTRAFFIC']."','javascript:GoPartlyReport(".$srv.",8)'));
	reports.add(new WebFXTreeItem('".$_lang['stTOPSITESTRAFFIC']."','javascript:GoPartlyReport(".$srv.",9)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURS']."','javascript:GoPartlyReport(".$srv.",10)'));
	reports.add(new WebFXTreeItem('".$_lang['stVISITINGWEBSITELOGINS']." ".$_lang['stBYDAYTIME']."','javascript:GoPartlyReport(".$srv.",22)'));
	reports.add(new WebFXTreeItem('".$_lang['stONELOGINIPTRAFFIC']."','javascript:GoPartlyReport(".$srv.",35)'));
	reports.add(new WebFXTreeItem('".$_lang['stMIMETYPESTRAFFIC']."','javascript:GoPartlyReport(".$srv.",46)'));
	reports.add(new WebFXTreeItem('".$_lang['stPOPULARSITES']."','javascript:GoPartlyReport(".$srv.",56)'));


	
	

";




if($idmenu==2)
echo "


//First Level

	var reports = new WebFXTree('".$_COOKIE['realname']."');
	reports.setBehavior('classic');
	

//Second Level

	reports.icon = '../../img/themes/default/User.png';
	reports.openIcon = '../../img/themes/default/User.png';

	
//Reports	
	reports.add(new WebFXTreeItem('".$_lang['stDASHBOARD']."','javascript:GoPartlyReport(".$srv.",62);'));
	reports.add(new WebFXTreeItem('".$_lang['stONEIPADRESSTRAFFIC']."','javascript:GoPartlyReport(".$srv.",11)'));
	reports.add(new WebFXTreeItem('".$_lang['stTOPSITESTRAFFIC']."','javascript:GoPartlyReport(".$srv.",12)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURS']."','javascript:GoPartlyReport(".$srv.",13)'));
	reports.add(new WebFXTreeItem('".$_lang['stVISITINGWEBSITEIPADDRESS']." ".$_lang['stBYDAYTIME']."','javascript:GoPartlyReport(".$srv.",23)'));
	reports.add(new WebFXTreeItem('".$_lang['stONEIPADDRESSLOGINSTRAFFIC']."','javascript:GoPartlyReport(".$srv.",36)'));
	reports.add(new WebFXTreeItem('".$_lang['stMIMETYPESTRAFFIC']."','javascript:GoPartlyReport(".$srv.",47)'));
	reports.add(new WebFXTreeItem('".$_lang['stPOPULARSITES']."','javascript:GoPartlyReport(".$srv.",57)'));
	

	
	
";


if($idmenu==4)
echo "


//First Level
	var reports = new WebFXTree('".$_COOKIE['realname']."');
	reports.setBehavior('classic');
	
//Second Level

	reports.icon = '../../img/themes/default/UserGroup.png';
	reports.openIcon = '../../img/themes/default/UserGroup.png';

	
//Reports	
	reports.add(new WebFXTreeItem('".$_lang['stDASHBOARD']."','javascript:GoReport(".$srv.",63);'));
	reports.add(new WebFXTreeItem('".$_lang['stONEGROUPTRAFFIC']."','javascript:GoReport(".$srv.",25)'));
	reports.add(new WebFXTreeItem('".$_lang['stONEGROUPTRAFFIC']." ".$_lang['stEXTENDED']."','javascript:GoReport(".$srv.",26)'));
	reports.add(new WebFXTreeItem('".$_lang['stTOPSITESTRAFFIC']."','javascript:GoReport(".$srv.",27)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURS']."','javascript:GoReport(".$srv.",28)'));
	reports.add(new WebFXTreeItem('".$_lang['stWHODOWNLOADBIGFILES']."','javascript:GoReport(".$srv.",29)'));
	reports.add(new WebFXTreeItem('".$_lang['stPOPULARSITES']."','javascript:GoReport(".$srv.",55)'));
	

	

";


echo "

document.write(reports);
}
</script>
</div>";
?>

</body>
</html>
