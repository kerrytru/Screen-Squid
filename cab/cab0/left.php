<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> left.php </#FN>                                                        
*                         File Birth   > <!#FB> 2022/10/19 21:15:30.138 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/10/20 21:05:40.889 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






include("../../config.php");


$srv=$_COOKIE['srv'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="<?php echo $globalSS['root_http']; ?>/themes/<?php echo $globalSS['globaltheme']; ?>/global.css"/>

<!-- The xtree script file -->
<script src="../../javascript/xtree.js"></script>


<script language=JavaScript>
function TwoByOne(frame1, frame2)
{
parent.left.location.href=frame1;
parent.right.location.href=frame2;
}

function GoLink(dest_link,dest_target)
{
parent.right.location.href=dest_link;
}




//Функция перехода по частным отчетам. Всё также берем srv и id отчета. Остальное припишем прямо из адресной строки правого фрейма
function GoPartlyReport(srv,id)
{
	var loc = ""; //возьмем адрес из правого фрейма 
	var arr = []
	var ret = "";

	loc = parent.right.location.href;

	arr = loc.split('?');
	//Удалим первые два параметра srv и id.
	arr = arr[1].split('&');
	arr.shift(); //раз
	arr.shift(); //два

	//объединим оставшиеся элементы через & и это будет наша строка
	ret = arr.join('&');

parent.right.location.href='reports/reports.php?srv='+srv+'&id='+id+'&date='+parent.right.window.document.fastdateswitch_form.date.value+'&date2='+parent.right.window.document.fastdateswitch_form.date2.value;
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

if(isset($_COOKIE['idmenu']))
  $idmenu=$_COOKIE['idmenu'];
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
reports.add(new WebFXTreeItem('Logout','javascript:GoLink(\'logout.php\')','','',''));
document.write(reports);
}
</script>
</div>";
?>

</body>
</html>
