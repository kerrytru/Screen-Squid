<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> mainmenu.php </#FN>                                                    
*                         File Birth   > <!#FB> 2021/10/18 22:59:04.068 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/09/13 23:34:06.177 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.4.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/


 

include("config.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="<?php echo $globalSS['root_http']; ?>/themes/<?php echo $globalSS['globaltheme']; ?>/global.css"/>

<!-- The xtree script file -->
<script src="javascript/xtree.js"></script>


<script language=JavaScript>
function TwoByOne(frame1, frame2)
{
parent.left.location.href=frame1;
parent.right.location.href=frame2;
}

//Функция перехода по меню
function GoReport(srv,id)
{

parent.right.location.href='reports/reports.php?srv='+srv+'&id='+id+'&date='+parent.right.window.document.fastdateswitch_form.date.value+'&date2='+parent.right.window.document.fastdateswitch_form.date2.value;


}


function GoRightReport(srv,id)
{
parent.right.location.href='right.php?srv='+srv+'&id='+id;
}

function GoLink(dest_link,dest_target)
{
parent.right.location.href=dest_link;
}

function GoInternetLink(dest_link)
{
	window.open(dest_link, '_blank');
}

function GoOnlineReport(srv,id)
{
parent.right.location.href='reports/oreports.php?srv='+srv+'&id='+id+'&date='+parent.right.window.document.fastdateswitch_form.date.value+'&date2='+parent.right.window.document.fastdateswitch_form.date2.value;
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
<div style='float:left; top: 70px; left: 10px; height: 85%; width: 100%; padding: 5px; overflow:auto;'>
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

$globalSS['connectionParams'] = $variableSet;


echo "//First Level
	var rootproxy = new WebFXTreeItem('".$srvname[$srv]."');

	tree.add(rootproxy);";



if(doConnectToDatabase($globalSS['connectionParams'])!="ErrorConnection")
{
echo "	
//Second Level
    rootproxy.add(new WebFXTreeItem('".$_lang['stDASHBOARD']."','javascript:GoReport(".$srv.",49);'));
	rootproxy.icon = 'img/themes/default/Database.png';
	rootproxy.openIcon = 'img/themes/default/Database.png';";

echo "
//Online reports	
	var onlinereports = new WebFXTreeItem('".$_lang['stONLINE']."');
	
	onlinereports.add(new WebFXTreeItem('".$_lang['stACTIVEIPADDRESS']."','javascript:GoOnlineReport(".$srv.",1);'));
	onlinereports.add(new WebFXTreeItem('System','javascript:GoOnlineReport(".$srv.",2);'));
	onlinereports.icon = 'img/themes/default/Reports.png';
	onlinereports.openIcon = 'img/themes/default/Reports.png';
	
	rootproxy.add(onlinereports);
	
//Reports	
	var reports = new WebFXTreeItem('".$_lang['stREPORTS']."');

	rootproxy.add(reports);

	var reportslogins = new WebFXTreeItem('".$_lang['stREPORTSLOGINS']."');
	reportslogins.add(new WebFXTreeItem('".$_lang['stLOGINSTRAFFIC']."','javascript:GoReport(".$srv.",1)'));
	reportslogins.add(new WebFXTreeItem('".$_lang['stTOPLOGINSTRAFFIC']."','javascript:GoReport(".$srv.",5)'));
	reportslogins.add(new WebFXTreeItem('".$_lang['stLOGINSTRAFFICWIDE']."','javascript:GoReport(".$srv.",14)'));
	reportslogins.add(new WebFXTreeItem('".$_lang['stLOGINSIPTRAFFIC']."','javascript:GoReport(".$srv.",37)'));
	reportslogins.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURSLOGINS']."','javascript:GoReport(".$srv.",50)'));
	reportslogins.add(new WebFXTreeItem('".$_lang['stLOGINSTIMEONLINE']."','javascript:GoReport(".$srv.",64)'));
	reportslogins.add(new WebFXTreeItem('".$_lang['stTOPLOGINSWORKINGHOURSTRAFFIC']."','javascript:GoReport(".$srv.",68)'));

	reports.add(reportslogins);

	var reportsipaddress = new WebFXTreeItem('".$_lang['stREPORTSIPADDRESS']."');

	reportsipaddress.add(new WebFXTreeItem('".$_lang['stIPADDRESSTRAFFIC']."','javascript:GoReport(".$srv.",2)'));
	reportsipaddress.add(new WebFXTreeItem('".$_lang['stTOPIPTRAFFIC']."','javascript:GoReport(".$srv.",6)'));
	reportsipaddress.add(new WebFXTreeItem('".$_lang['stIPADDRESSTRAFFICWIDE']."','javascript:GoReport(".$srv.",15)'));
	reportsipaddress.add(new WebFXTreeItem('".$_lang['stIPADDRESSTRAFFICWITHRESOLVE']."','javascript:GoReport(".$srv.",16)'));
	reportsipaddress.add(new WebFXTreeItem('".$_lang['stIPADDRESSLOGINSTRAFFIC']."','javascript:GoReport(".$srv.",38)'));
	reportsipaddress.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURSIPADDRESS']."','javascript:GoReport(".$srv.",51)'));
	reportsipaddress.add(new WebFXTreeItem('".$_lang['stIPADDRESSTIMEONLINE']."','javascript:GoReport(".$srv.",65)'));
	reportsipaddress.add(new WebFXTreeItem('".$_lang['stTOPIPWORKINGHOURSTRAFFIC']."','javascript:GoReport(".$srv.",69)'));

	reports.add(reportsipaddress);

	var reportscommon = new WebFXTreeItem('".$_lang['stREPORTSCOMMON']."');

	reportscommon.add(new WebFXTreeItem('".$_lang['stSITESTRAFFIC']."','javascript:GoReport(".$srv.",3)'));
	reportscommon.add(new WebFXTreeItem('".$_lang['stTOPSITESTRAFFIC']."','javascript:GoReport(".$srv.",4)'));
	reportscommon.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURS']."','javascript:GoReport(".$srv.",7)'));
	reportscommon.add(new WebFXTreeItem('".$_lang['stPOPULARSITES']."','javascript:GoReport(".$srv.",17)'));
	reportscommon.add(new WebFXTreeItem('".$_lang['stWHODOWNLOADBIGFILES']."','javascript:GoReport(".$srv.",20)'));
	reportscommon.add(new WebFXTreeItem('".$_lang['stTRAFFICBYPERIODDAY']."','javascript:GoReport(".$srv.",39)'));
	reportscommon.add(new WebFXTreeItem('".$_lang['stTRAFFICBYPERIODDAYNAME']."','javascript:GoReport(".$srv.",40)'));
	reportscommon.add(new WebFXTreeItem('".$_lang['stTRAFFICBYPERIOD']."','javascript:GoReport(".$srv.",21)'));
	reportscommon.add(new WebFXTreeItem('".$_lang['stHTTPSTATUSES']."','javascript:GoReport(".$srv.",30)'));
	reportscommon.add(new WebFXTreeItem('".$_lang['stMIMETYPESTRAFFIC']."','javascript:GoReport(".$srv.",45)'));
	reportscommon.add(new WebFXTreeItem('".$_lang['stDOMAINZONESTRAFFIC']."','javascript:GoReport(".$srv.",48)'));

	reports.add(reportscommon);
	
	reports.icon = 'img/themes/default/Reports.png';
	reports.openIcon = 'img/themes/default/Reports.png';
	
	

//Misc
//internal module
var dictmodule = new WebFXTreeItem('".$_lang['stDICTIONARY']."');
dictmodule.add(new WebFXTreeItem('".$_lang['stLOGINS']."','javascript:GoLink(\'modules/dictionary/index.php?srv=".$srv."&id=1\',\'right\')','','img/themes/default/User.png','img/themes/default/User.png'));
dictmodule.add(new WebFXTreeItem('".$_lang['stIPADDRESS']."','javascript:GoLink(\'modules/dictionary/index.php?srv=".$srv."&id=2\',\'right\')','','img/themes/default/User.png','img/themes/default/User.png'));

dictmodule.add(new WebFXTreeItem('".$_lang['stALIASES']."','javascript:GoRightReport(".$srv.",2)','','img/themes/default/Users.png','img/themes/default/Users.png'));
dictmodule.add(new WebFXTreeItem('".$_lang['stGROUPS']."','javascript:GoRightReport(".$srv.",3)','','img/themes/default/UserGroups.png','img/themes/default/UserGroups.png'));

rootproxy.add(dictmodule);

rootproxy.add(new WebFXTreeItem('".$_lang['stBYGROUP']."','javascript:GoReport(".$srv.",24);','','img/themes/default/Reports.png','img/themes/default/Reports.png'));
	rootproxy.add(new WebFXTreeItem('".$_lang['stFASTSEARCH']."','javascript:GoRightReport(".$srv.",4)','','img/themes/default/Search.png','img/themes/default/Search.png'));
	rootproxy.add(new WebFXTreeItem('".$_lang['stSTATS']."','javascript:GoRightReport(".$srv.",1)','','img/themes/default/Statistics.png','img/themes/default/Statistics.png'));
	rootproxy.add(new WebFXTreeItem('".$_lang['stLOGTABLE']."','javascript:GoRightReport(".$srv.",5)','','img/themes/default/Job.png','img/themes/default/Job.png'));



//Modules	
	var modulemanager = new WebFXTreeItem('".$_lang['stMODULEMANAGER']."','javascript:GoRightReport(".$srv.",7)','','img/themes/default/Nodes.png','img/themes/default/Nodes.png');
	

";

$queryModules="select name from scsq_modules order by name asc;";

$result=doFetchQuery($globalSS, $queryModules);

$golink="";

foreach($result as $line) {
$golink="modules/".$line[0]."/index.php?srv=".$srv."";
echo "modulemanager.add(new WebFXTreeItem('".$line[0]."','javascript:GoLink(\'".$golink."\',\'right\')','','img/themes/default/Node.png','img/themes/default/Node.png'));\n";
}
echo "rootproxy.add(new WebFXTreeItem('".$_lang['stCONFIG']."','javascript:GoRightReport(".$srv.",6)','','img/themes/default/Processes.png','img/themes/default/Processes.png'));
";
echo "rootproxy.add(modulemanager);";

$srv++;

}
else
{
echo "

//Second Level	
//Если проблемы с подключением разместим тестовую страницу.
	rootproxy.add(new WebFXTreeItem('Test connection page','javascript:GoRightReport(".$srv.",999)'));
	rootproxy.icon = 'img/themes/default/DisconnectedDatabase.png';
	rootproxy.openIcon = 'img/themes/default/DisconnectedDatabase.png';
	

	";
	
$srv++;
continue;

}  

}

//Пункт для добавления удаления БД
echo "
    tree.add(new WebFXTreeItem('".$_lang['stADDREMOVE']."','javascript:GoRightReport(0,8)','','img/themes/default/Processes.png','img/themes/default/Processes.png'));
    tree.add(new WebFXTreeItem('DONATE','javascript:GoInternetLink(\'https://sobe.ru/na/na_novuyu_versiyu_screen_squid\')','','',''));
    tree.add(new WebFXTreeItem('Wiki','javascript:GoInternetLink(\'https://sourceforge.net/p/screen-squid/wiki/Home/\')','','',''));
";
if($globalSS['debug']==1) {
	echo "
	var debugtree = new WebFXTreeItem('Debug');
	
	debugtree.add(new WebFXTreeItem('phpinfo','javascript:GoRightReport(0,9)'));
	
	tree.add(debugtree);
	";
	
	}

echo "
document.write(tree);
}
</script>
</div>";

?>

</body>
</html>
