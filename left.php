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
*                         File Birth   > <!#FB> 2021/09/11 17:04:26.557 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/09/28 22:24:38.416 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.4.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/


include("config.php");

#если нет авторизации, сразу выходим
if ((!isset($_COOKIE['loggedAdm'])or($_COOKIE['loggedAdm']==0)) 
	and (file_exists("".$globalSS['root_dir']."/modules/PrivateAuth/pass")) 
	and (!file_exists("".$globalSS['root_dir']."/modules/PrivateAuth/hash"))
	)
{
	header("Location: ".$globalSS['root_http']."/modules/PrivateAuth/login.php"); exit();
}

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

parent.right.location.href='reports/reports.php?srv='+srv+'&id='+id+'&date='+parent.right.window.document.fastdateswitch_form.date.value+'&date2='+parent.right.window.document.fastdateswitch_form.date2.value+'&'+ret;
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
	reports.add(new WebFXTreeItem('".$_lang['stBIGFILES']."','javascript:GoPartlyReport(".$srv.",66)'));
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
	reports.add(new WebFXTreeItem('".$_lang['stBIGFILES']."','javascript:GoPartlyReport(".$srv.",67)'));

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
	reports.add(new WebFXTreeItem('".$_lang['stDASHBOARD']."','javascript:GoPartlyReport(".$srv.",63);'));
	reports.add(new WebFXTreeItem('".$_lang['stONEGROUPTRAFFIC']."','javascript:GoPartlyReport(".$srv.",25)'));
	reports.add(new WebFXTreeItem('".$_lang['stONEGROUPTRAFFIC']." ".$_lang['stEXTENDED']."','javascript:GoPartlyReport(".$srv.",26)'));
	reports.add(new WebFXTreeItem('".$_lang['stTOPSITESTRAFFIC']."','javascript:GoPartlyReport(".$srv.",27)'));
	reports.add(new WebFXTreeItem('".$_lang['stTRAFFICBYHOURS']."','javascript:GoPartlyReport(".$srv.",28)'));
	reports.add(new WebFXTreeItem('".$_lang['stWHODOWNLOADBIGFILES']."','javascript:GoPartlyReport(".$srv.",29)'));
	reports.add(new WebFXTreeItem('".$_lang['stPOPULARSITES']."','javascript:GoPartlyReport(".$srv.",55)'));
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
