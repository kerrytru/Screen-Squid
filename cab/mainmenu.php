<?php
#build 20200227
include("../config.php");

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="../themes/default/global.css"/>

<!-- The xtree script file -->
<script src="../javascript/xtree.js"></script>


<link rel="stylesheet" type="text/css" href="../javascript/example.css"/>
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


	<div class="logo">
		<a href="right.php" target="right">
			Screen Squid 
		</a>
	</div>


<?php


echo "
<div style='float:left; top: 70px; left: 10px; height: 100%; width: 100%; padding: 5px; overflow: auto;'>
<script language=JavaScript>

webFXTreeConfig.rootIcon		= '../img/themes/default/Servers.png';
webFXTreeConfig.openRootIcon	= '../img/themes/default/Servers.png';
webFXTreeConfig.folderIcon		= '';
webFXTreeConfig.openFolderIcon	= '';
webFXTreeConfig.fileIcon		= '../img/themes/default/Report.png';
webFXTreeConfig.iIcon			= '../img/themes/default/I.png';
webFXTreeConfig.lIcon			= '../img/themes/default/L.png';
webFXTreeConfig.lMinusIcon		= '../img/themes/default/Lminus.png';
webFXTreeConfig.lPlusIcon		= '../img/themes/default/Lplus.png';
webFXTreeConfig.tIcon			= '../img/themes/default/T.png';
webFXTreeConfig.tMinusIcon		= '../img/themes/default/Tminus.png';
webFXTreeConfig.tPlusIcon		= '../img/themes/default/Tplus.png';
webFXTreeConfig.blankIcon		= '../img/themes/default/blank.png';
webFXTreeConfig.loadingIcon		= '../img/themes/default/Loading.gif';
webFXTreeConfig.loadingText		= 'Loading...';
webFXTreeConfig.errorIcon		= '../img/themes/default/ObjectNotFound.png';
webFXTreeConfig.errorLoadingText = 'Error Loading';
webFXTreeConfig.reloadText		= 'Click to reload';

if (document.getElementById) {
	var tree = new WebFXTree('ROOT');
	tree.setBehavior('classic');
";

$variableSet = array();

for($i=0;$i<count($srvname);$i++)
{

$addr=$address[$i];
$usr=$user[$i];
$psw=$pass[$i];
$dbase=$db[$i];
$dbtype=$srvdbtype[$i];


$variableSet['addr']=$addr;
$variableSet['usr']=$usr;
$variableSet['psw']=$psw;
$variableSet['dbase']=$dbase;
$variableSet['dbtype']=$dbtype;

#в зависимости от типа БД, подключаем разные модули
if($dbtype==0)
include_once("../lib/dbDriver/mysqlmodule.php");

if($dbtype==1)
include_once("../lib/dbDriver/pgmodule.php");

$ssq = new ScreenSquid($variableSet); #получим экземпляр класса и будем уже туда закиыдвать запросы на исполнение

$ssq->query("select 1 from scsq_traffic limit 1");

echo "//First Level
	var rootproxy = new WebFXTreeItem('".$srvname[$srv]."','javascript:GoReport(".$srv.",999)');

	tree.add(rootproxy);";


if($ssq->db_object==true)
{
echo "	
//Second Level
 	rootproxy.icon = '../img/themes/default/Database.png';
	rootproxy.openIcon = '../img/themes/default/Database.png';";

}
else
{
echo "

//Second Level	
 	rootproxy.icon = '../img/themes/default/DisconnectedDatabase.png';
	rootproxy.openIcon = '../img/themes/default/DisconnectedDatabase.png';
	

	";
$srv++;
continue;
}  



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
