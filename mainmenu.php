<?php 
#build 20191021
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

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="themes/default/global.css"/>

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

function GoRightReport(srv,id)
{
parent.right.location.href='right.php?srv='+srv+'&id='+id;
}

function GoLink(dest_link,dest_target)
{
parent.right.location.href=dest_link;
}


function GoOnlineReport(srv,id)
{
parent.right.location.href='reports/oreports.php?srv='+srv+'&id='+id+'&date='+parent.right.window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+parent.right.window.document.fastdateswitch_form.dom_field_hidden.value;
}



</script>

</head>

<body class="browser">

<?php
$srv=0;

$addr=$address[$srv];
$usr=$user[$srv];
$psw=$pass[$srv];
$dbase=$db[$srv];
$dbtype=$srvdbtype[$srv];

$variableSet = array();
$variableSet['addr']=$addr;
$variableSet['usr']=$usr;
$variableSet['psw']=$psw;
$variableSet['dbase']=$dbase;
$variableSet['dbtype']=$dbtype;

#в зависимости от типа БД, подключаем разные модули
if($dbtype==0)
include("lib/dbDriver/mysqlmodule.php");

if($dbtype==1)
include("lib/dbDriver/pgmodule.php");

$ssq = new ScreenSquid($variableSet); #получим экземпляр класса и будем уже туда закиыдвать запросы на исполнение


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
	var tree = new WebFXTree('ROOT');
	tree.setBehavior('classic');
";

for($i=0;$i<count($srvname);$i++)
{

echo "


//First Level
	var rootproxy = new WebFXTreeItem('".$srvname[$srv]."');

	tree.add(rootproxy);

//Second Level

	rootproxy.add(new WebFXTreeItem('".$_lang['stDASHBOARD']."','javascript:GoReport(".$srv.",49);'));
	rootproxy.icon = 'img/themes/default/Database.png';
	rootproxy.openIcon = 'img/themes/default/Database.png';
  
//Online reports	
	var onlinereports = new WebFXTreeItem('".$_lang['stONLINE']."');
	
	onlinereports.add(new WebFXTreeItem('".$_lang['stACTIVEIPADDRESS']."','javascript:GoOnlineReport(".$srv.",1);'));
	onlinereports.add(new WebFXTreeItem('System','javascript:GoOnlineReport(".$srv.",2);'));
	onlinereports.icon = 'img/themes/default/Reports.png';
	onlinereports.openIcon = 'img/themes/default/Reports.png';
	
	rootproxy.add(onlinereports);
	
//Reports	
	var reports = new WebFXTreeItem('".$_lang['stREPORTS']."');
	
	reports.add(new WebFXTreeItem('".$_lang['stLOGINSTRAFFIC']."','javascript:GoReport(".$srv.",1)'));
	reports.add(new WebFXTreeItem('".$_lang['stIPADDRESSTRAFFIC']."','javascript:GoReport(".$srv.",2)'));
	reports.add(new WebFXTreeItem('".$_lang['stSITESTRAFFIC']."','javascript:GoReport(".$srv.",3)'));
	reports.add(new WebFXTreeItem('".$_lang['stTOPSITESTRAFFIC']."','javascript:GoReport(".$srv.",4)'));
	reports.add(new WebFXTreeItem('".$_lang['stTOPLOGINSTRAFFIC']."','javascript:GoReport(".$srv.",5)'));
	reports.add(new WebFXTreeItem('".$_lang['stTOPIPTRAFFIC']."','javascript:GoReport(".$srv.",6)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURS']."','javascript:GoReport(".$srv.",7)'));
	reports.add(new WebFXTreeItem('".$_lang['stLOGINSTRAFFICWIDE']."','javascript:GoReport(".$srv.",14)'));
	reports.add(new WebFXTreeItem('".$_lang['stIPADDRESSTRAFFICWIDE']."','javascript:GoReport(".$srv.",15)'));
	reports.add(new WebFXTreeItem('".$_lang['stIPADDRESSTRAFFICWITHRESOLVE']."','javascript:GoReport(".$srv.",16)'));
	reports.add(new WebFXTreeItem('".$_lang['stPOPULARSITES']."','javascript:GoReport(".$srv.",17)'));
	reports.add(new WebFXTreeItem('".$_lang['stWHODOWNLOADBIGFILES']."','javascript:GoReport(".$srv.",20)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYPERIODDAY']."','javascript:GoReport(".$srv.",39)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYPERIODDAYNAME']."','javascript:GoReport(".$srv.",40)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYPERIOD']."','javascript:GoReport(".$srv.",21)'));
	reports.add(new WebFXTreeItem('".$_lang['stHTTPSTATUSES']."','javascript:GoReport(".$srv.",30)'));
	reports.add(new WebFXTreeItem('".$_lang['stLOGINSIPTRAFFIC']."','javascript:GoReport(".$srv.",37)'));
	reports.add(new WebFXTreeItem('".$_lang['stIPADDRESSLOGINSTRAFFIC']."','javascript:GoReport(".$srv.",38)'));
	reports.add(new WebFXTreeItem('".$_lang['stMIMETYPESTRAFFIC']."','javascript:GoReport(".$srv.",45)'));
	reports.add(new WebFXTreeItem('".$_lang['stDOMAINZONESTRAFFIC']."','javascript:GoReport(".$srv.",48)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURSLOGINS']."','javascript:GoReport(".$srv.",50)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURSIPADDRESS']."','javascript:GoReport(".$srv.",51)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYCATEGORIES']."','javascript:GoReport(".$srv.",52)'));

	reports.icon = 'img/themes/default/Reports.png';
	reports.openIcon = 'img/themes/default/Reports.png';
	
	rootproxy.add(reports);

//Misc
	rootproxy.add(new WebFXTreeItem('".$_lang['stBYGROUP']."','javascript:GoReport(".$srv.",24);','','img/themes/default/Reports.png','img/themes/default/Reports.png'));
	rootproxy.add(new WebFXTreeItem('".$_lang['stFASTSEARCH']."','javascript:GoRightReport(".$srv.",4)','','img/themes/default/Search.png','img/themes/default/Search.png'));
	rootproxy.add(new WebFXTreeItem('".$_lang['stSTATS']."','javascript:GoRightReport(".$srv.",1)','','img/themes/default/Statistics.png','img/themes/default/Statistics.png'));
	rootproxy.add(new WebFXTreeItem('".$_lang['stLOGTABLE']."','javascript:GoRightReport(".$srv.",5)','','img/themes/default/Job.png','img/themes/default/Job.png'));
	rootproxy.add(new WebFXTreeItem('".$_lang['stALIASES']."','javascript:GoRightReport(".$srv.",2)','','img/themes/default/Users.png','img/themes/default/Users.png'));
	rootproxy.add(new WebFXTreeItem('".$_lang['stGROUPS']."','javascript:GoRightReport(".$srv.",3)','','img/themes/default/UserGroups.png','img/themes/default/UserGroups.png'));
	rootproxy.add(new WebFXTreeItem('".$_lang['stCONFIG']."','javascript:GoRightReport(".$srv.",6)','','img/themes/default/Processes.png','img/themes/default/Processes.png'));

//Modules	
	var modulemanager = new WebFXTreeItem('".$_lang['stMODULEMANAGER']."','javascript:GoRightReport(".$srv.",7)','','img/themes/default/Nodes.png','img/themes/default/Nodes.png');
	

";

$queryModules="select name from scsq_modules order by name asc;";

$result=$ssq->query($queryModules);

$golink="";

while($line = $ssq->fetch_array($result)) {
$golink="modules/".$line[0]."/index.php?srv=".$srv."";
echo "modulemanager.add(new WebFXTreeItem('".$line[0]."','javascript:GoLink(\'".$golink."\',\'right\')','','img/themes/default/Node.png','img/themes/default/Node.png'));\n";
}
$ssq->free_result($result);

echo "rootproxy.add(modulemanager);";

$srv++;
}

echo "

document.write(tree);
}
</script>
</div>";

?>

</body>
</html>
