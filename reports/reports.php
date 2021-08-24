<?php

#Build date Wednesday 30th of September 2020 16:58:37 PM
#Build revision 1.12


#чтобы убрать возможные ошибки с датой, установим на время исполнения скрипта ту зону, которую отдает система.
date_default_timezone_set(date_default_timezone_get());

include("../config.php");



$header='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="'.$globalSS['root_http'].'/themes/'.$globalSS['globaltheme'].'/global.css"/>

<style>
body {
  margin: 0;
}

.preloader {
  /*фиксированное позиционирование*/
  position: fixed;
  /* координаты положения */
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  /* фоновый цвет элемента */
  background: #e0e0e0;
  /* размещаем блок над всеми элементами на странице (это значение должно быть больше, чем у любого другого позиционированного элемента на странице) */
  z-index: 1001;
}

.preloader__row {
  position: relative;
  top: 50%;
  left: 50%;
  width: 70px;
  height: 70px;
  margin-top: -35px;
  margin-left: -35px;
  text-align: center;
  animation: preloader-rotate 2s infinite linear;
}

.preloader__item {
  position: absolute;
  display: inline-block;
  top: 0;
  background-color: #337ab7;
  border-radius: 100%;
  width: 35px;
  height: 35px;
  animation: preloader-bounce 2s infinite ease-in-out;
}

.preloader__item:last-child {
  top: auto;
  bottom: 0;
  animation-delay: -1s;
}

@keyframes preloader-rotate {
  100% {
	transform: rotate(360deg);
  }
}

@keyframes preloader-bounce {

  0%,
  100% {
	transform: scale(0);
  }

  50% {
	transform: scale(1);
  }
}

.loaded_hiding .preloader {
  transition: 0.3s opacity;
  opacity: 0;
}

.loaded .preloader {
  display: none;
}
</style>


</head>

<body>
<!-- Прелоадер  -->
<div class="preloader">
  <div class="preloader__row">
	<div class="preloader__item"></div>
	<div class="preloader__item"></div>
  </div>
</div>

';

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;

//reports id

if (isset($_GET['id']))
$id=$_GET['id'];
else
$id=0;

if(isset($_GET['date']))
  $querydate=$_GET['date'];
else
  $querydate=date("d-m-Y");

if(isset($_GET['date2']))
  $querydate2=$_GET['date2'];
else
  $querydate2="";

list($day,$month,$year) = preg_split('/[\/\.-]+/', $querydate);

if(isset($_GET['dom']))
  $dayormonth=$_GET['dom'];
else
  $dayormonth="day";

  if(isset($_GET['loginname']))
  {
	$currentlogin=$_GET['loginname'];
	$currentmime=$_GET['loginname'];
  }
  else
  {
	$currentlogin="";
	$currentmime="";
  }
  
  if(isset($_GET['login']))
	$currentloginid=$_GET['login'];
  else
	$currentloginid="";
  
  
  if(isset($_GET['ipname']))
	$currentipaddress=$_GET['ipname'];
  else
	$currentipaddress="";
  
  if(isset($_GET['ip']))
	$currentipaddressid=$_GET['ip'];
  else
	$currentipaddressid="";
  
  if(isset($_GET['site']))
  {
	$currentsite=$_GET['site'];
  if($currentlogin=="")
	$currentlogin=$_GET['site'];
  if($currentipaddress=="")
	$currentipaddress=$_GET['site'];
  
  //костыль для отчетов 41 и 42 и 43 и 44
  $currenthour=$_GET['site'];
  }
  else
  {
	$currentsite="";
	
	$currenthour="";
  }
  
  if(isset($_GET['group']))
	$currentgroupid=$_GET['group'];
  else
	$currentgroupid="";
  
  if(isset($_GET['typeid']))
	$typeid=$_GET['typeid'];
  else
	$typeid="";
  
  
  if(isset($_GET['groupname']))
	$currentgroup=$_GET['groupname'];
  else
	$currentgroup="";
  
  if(isset($_GET['httpname']))
	$currenthttpname=$_GET['httpname'];
  else
	$currenthttpname="";
  
  if(isset($_GET['httpstatus']))
	$currenthttpstatusid=$_GET['httpstatus'];
  else
	$currenthttpstatusid="";
  
  if(isset($_GET['loiid']))
	$currentloiid=$_GET['loiid'];
  else
	$currentloiid="";
  
  if(isset($_GET['loiname']))
	$currentloiname=$_GET['loiname'];
  else
	$currentloiname="";

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


#подключим главный файл который теперь отвечает за генерацию данных
include(''.$globalSS['root_dir'].'/lib/functions/function.getreport.php');
include(''.$globalSS['root_dir'].'/lib/functions/function.misc.php');
include(''.$globalSS['root_dir'].'/lib/functions/function.reportmisc.php');


/// end

///с этим что-то надо будет сделать. Убрать лишнюю дублирующуюся информацию
$params = array();
$params['dbase']=$dbase;
$params['idReport']=$id;
$params['date']=$querydate;
$params['period']=$dayormonth;
$params['idLogin']=$currentloginid;   
$params['idIpaddress']=$currentipaddress; 

$globalSS['dayormonth']=$dayormonth;
$globalSS['currenthttpname']=$currenthttpname;
$globalSS['currenthttpstatusid']=$currenthttpstatusid;
$globalSS['currentipaddressid']=$currentipaddressid;
$globalSS['currentloginid']=$currentloginid;
$globalSS['currentipaddress']=$currentipaddress;
$globalSS['currentlogin']=$currentlogin;



	$globalSS['lang']=$_lang;
	$globalSS['params']=$params;
	$globalSS['connectionParams']=$variableSet;



#Большой и временный костыль. Пока думаю как лучше уйти
$enableShowDayNameInReports = $globalSS['enableShowDayNameInReports'];
$enableNofriends = $globalSS['enableNofriends'];
$enableNoSites = $globalSS['enableNoSites'];
$showZeroTrafficInReports = $globalSS['showZeroTrafficInReports'];
$useLoginalias = $globalSS['useLoginalias'];
$useIpaddressalias = $globalSS['useIpaddressalias'];


#Большой костыль end

$countTopSitesLimit=$globalSS['countTopSitesLimit'];
$countTopLoginLimit=$globalSS['countTopLoginLimit'];
$countTopIpLimit=$globalSS['countTopIpLimit'];
$countPopularSitesLimit=$globalSS['countPopularSitesLimit'];
$countWhoDownloadBigFilesLimit=$globalSS['countWhoDownloadBigFilesLimit'];


include("".$globalSS['root_dir']."/modules/Chart/module.php");

$grap = new Chart($globalSS); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение


// Include the main TCPDF library (search for installation path).
include("".$globalSS['root_dir']."/lib/tcpdf/tcpdf.php");


//если есть команда pdf, то не выводим заголовки
$globalSS['makepdf']=0;
if(isset($_GET['pdf']))
{
$makepdf=1;
$globalSS['makepdf']=1;
}

$globalSS['makecsv']=0;
//если есть команда csv, то не выводим заголовки
if(isset($_GET['csv']))
{
$makecsv=1;
$globalSS['makecsv']=1;
}

//если есть команда очистить кэш, то затираем файл отчёта на диске

if(isset($_GET['clearcache']))
{
	unlink("".$globalSS['root_dir']."/modules/Cache/data/".doGenerateUniqueNameReport($globalSS['params']));
}


//если не генерируем файл на выход, то выводим заголовки
if(!isset($_GET['pdf']) && !isset($_GET['csv']))
{
echo $header;

}


$start=microtime(true);



// Javascripts

if(!isset($_GET['pdf']) && !isset($_GET['csv'])) {
?>


<script language=JavaScript>

function LeftRightDateSwitch(idReport, dom,lr)
{
  var stringdate=window.document.fastdateswitch_form.date_field_hidden.value;
  var arraydate=stringdate.split('-');
  var leftdate=new Date(arraydate[2],arraydate[1]-1,arraydate[0]);
  var rightdate=new Date(arraydate[2],arraydate[1]-1,arraydate[0]);
  var ldate;
  var rdate;
  if((dom=='day')  || (dom=='btw'))  {
    leftdate.setDate(leftdate.getDate()-1);
    rightdate.setDate(rightdate.getDate()+1);
  }

  if(dom=='month') {
    leftdate.setMonth(leftdate.getMonth()-1);
    rightdate.setMonth(rightdate.getMonth()+1);
  }

  var mp1l=leftdate.getMonth()+1;
  var mp1r=rightdate.getMonth()+1;
  
  if(mp1l<10) mp1l='0'+mp1l;
  
  if(mp1r<10) mp1r='0'+mp1r;

  ldate=leftdate.getDate()+'-'+mp1l+'-'+leftdate.getFullYear();
  rdate=rightdate.getDate()+'-'+mp1r+'-'+rightdate.getFullYear();

  if(lr=='l')
    window.document.fastdateswitch_form.date_field.value=ldate;
  else if(lr=='r')
    window.document.fastdateswitch_form.date_field.value=rdate;
  else
    window.document.fastdateswitch_form.date_field.value=lr;

  FastDateSwitch(idReport,dom);
}

function FastDateSwitch(idReport, dom)
{
  if(window.document.fastdateswitch_form.date_field.value=='')
    parent.right.location.href='reports.php?srv=<?php echo $srv ?>&id='+idReport
+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
+'&dom='+dom
+'&login='+window.document.fastdateswitch_form.login_field_hidden.value
+'&loginname='+window.document.fastdateswitch_form.loginname_field_hidden.value
+'&ip='+window.document.fastdateswitch_form.ip_field_hidden.value
+'&ipname='+window.document.fastdateswitch_form.ipname_field_hidden.value
+'&site='+window.document.fastdateswitch_form.site_field_hidden.value
+'&group='+window.document.fastdateswitch_form.group_field_hidden.value
+'&groupname='+window.document.fastdateswitch_form.groupname_field_hidden.value
+'&typeid='+window.document.fastdateswitch_form.typeid_field_hidden.value
+'&httpstatus='+window.document.fastdateswitch_form.httpstatus_field_hidden.value
+'&httpname='+window.document.fastdateswitch_form.httpname_field_hidden.value
+'&loiid='+window.document.fastdateswitch_form.loiid_field_hidden.value
+'&loiname='+window.document.fastdateswitch_form.loiname_field_hidden.value;
  else
    parent.right.location.href='reports.php?srv=<?php echo $srv ?>&id='+idReport
+'&date='+window.document.fastdateswitch_form.date_field.value
+'&date2='+window.document.fastdateswitch_form.date2_field.value
+'&dom='+dom
+'&login='+window.document.fastdateswitch_form.login_field_hidden.value
+'&loginname='+window.document.fastdateswitch_form.loginname_field_hidden.value
+'&ip='+window.document.fastdateswitch_form.ip_field_hidden.value
+'&ipname='+window.document.fastdateswitch_form.ipname_field_hidden.value
+'&site='+window.document.fastdateswitch_form.site_field_hidden.value
+'&group='+window.document.fastdateswitch_form.group_field_hidden.value
+'&groupname='+window.document.fastdateswitch_form.groupname_field_hidden.value
+'&typeid='+window.document.fastdateswitch_form.typeid_field_hidden.value
+'&httpstatus='+window.document.fastdateswitch_form.httpstatus_field_hidden.value
+'&httpname='+window.document.fastdateswitch_form.httpname_field_hidden.value
+'&loiid='+window.document.fastdateswitch_form.loiid_field_hidden.value
+'&loiname='+window.document.fastdateswitch_form.loiname_field_hidden.value;
}


//JS function to open reports page with some additional parameters.
//idReport - id of report
//dom - day or month report
//id - id login or id ipaddress or id group
//idname - visible name login(Yoda) or name ipaddress(172.16.120.33) or name group(StarWars club)
//idsign - login (0) or ipaddress (1) or group login (3) or group ipaddress (4) or httpstatus id >4
//par1 - sitename if report need it or httpstatus name



function GoPartlyReports(idReport, dom, id, idname, idsign, par1)
{

	if(idsign==0)
	{
		parent.right.location.href='reports.php?srv=<?php echo $srv ?>&id='+idReport+
		'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&login='+id
		+'&loginname='+idname
		+'&typeid=0'
		+'&site='+par1;
	}
	if(idsign==1)
	{
		parent.right.location.href='reports.php?srv=<?php echo $srv ?>&id='+idReport
		+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&ip='+id
		+'&ipname='+idname
		+'&typeid=1'
		+'&site='+par1;
	}
	if(idsign==3)
	{
		parent.right.location.href='reports.php?srv=<?php echo $srv ?>&id='+idReport
		+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&group='+id
		+'&groupname='+idname
		+'&typeid=0';
	}
	if(idsign==4)
	{
		parent.right.location.href='reports.php?srv=<?php echo $srv ?>&id='+idReport
		+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&group='+id
		+'&groupname='+idname
		+'&typeid=1';
	}
	if(idsign>4)
	{
		parent.right.location.href='reports.php?srv=<?php echo $srv ?>&id='+idReport
		+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&httpstatus='+id
		+'&httpname='+idname
		+'&loiid='+idsign
		+'&loiname='+par1;
	}

}


function UpdateLeftMenu(id)
{
  parent.left.location.href='<?php echo $globalSS['root_http']?>/left.php?srv=<?php echo $srv ?>&id='+id
  +'&loginname='+window.document.fastdateswitch_form.loginname_field_hidden.value
  +'&ipname='+window.document.fastdateswitch_form.ipname_field_hidden.value
  +'&groupname='+window.document.fastdateswitch_form.groupname_field_hidden.value;
}

</script>

<script type="text/javascript" src="<?php echo $globalSS['root_http']?>/javascript/sortable.js"></script>


<?php
}

// Javascripts END





///костыль для отчетов по периодам
/// 21 - по месяцам, 39 - по дням, 40 - по имени дня
if(($_GET['id']==21)||($_GET['id']==39)||($_GET['id']==40))
$dayormonth="month";


if($dayormonth=="day") {
  $querydom="%d-%m-%Y";
  $datestart=strtotime($querydate);
  $dateend=strtotime($querydate) + 86400;
  $weekdaynumber=date("w",$datestart);
}
if($dayormonth=="month")  {
  $querydom="%m-%Y";
  $querydate=$month."-".$year;
  $numdaysinmonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  $datestart=mktime(0,0,0,$month,1,$year);
  $dateend=$datestart + 86400*$numdaysinmonth;
}

if($dayormonth=="btw")  {
  $querydom="%d-%m-%Y";
  $datestart=strtotime($querydate);
  $dateend=strtotime($querydate2) + 86400;
  $dayormonth="day";
//  $weekdaynumber=date("w",$datestart);
}



if(isset($weekdaynumber)){
if($weekdaynumber==1)
$dayname="(".$_lang['stMONDAY'].")";
if($weekdaynumber==2)
$dayname="(".$_lang['stTUESDAY'].")";
if($weekdaynumber==3)
$dayname="(".$_lang['stWEDNESDAY'].")";
if($weekdaynumber==4)
$dayname="(".$_lang['stTHURSDAY'].")";
if($weekdaynumber==5)
$dayname="(".$_lang['stFRIDAY'].")";
if($weekdaynumber==6)
$dayname="(".$_lang['stSATURDAY'].")";
if($weekdaynumber==0)
$dayname="(".$_lang['stSUNDAY'].")";

}

if($enableShowDayNameInReports==0)
$dayname="";

if($dayormonth=="month")
$dayname="";




  #если есть дружеские логины, IP адреса или сайты. Соберём их.
$goodLoginsList=doCreateFriendList($globalSS,'logins');
$goodIpaddressList=doCreateFriendList($globalSS,'ipaddress');
$goodSitesList = doCreateSitesList($globalSS);



#split working hours
list($workStart1, $workEnd1, $workStart2, $workEnd2) = explode(":", $globalSS['workingHours']);
list($workStartHour1, $workStartMin1) = explode("-", $workStart1);
list($workEndHour1, $workEndMin1) = explode("-", $workEnd1);
list($workStartHour2, $workStartMin2) = explode("-", $workStart2);
list($workEndHour2, $workEndMin2) = explode("-", $workEnd2);


if($showZeroTrafficInReports==1)
  $msgNoZeroTraffic="";
else
  $msgNoZeroTraffic=" and tmp.s!=0 ";

//проверим, есть ли модуль категорий. Если есть показываем столбец с категориями
$globalSS['category'] = doQueryExistsModuleCategory($globalSS);

$category = $globalSS['category'];
//querys for reports

//if($useLoginalias==0)
$echoLoginAliasColumn="";
if($useLoginalias==1)
$echoLoginAliasColumn=",aliastbl.name";


#mysql version
   $queryLoginsTraffic="
  SELECT 
    nofriends.name,
    tmp.s,
    nofriends.id
	".$echoLoginAliasColumn."
  FROM (SELECT 
          login,
          SUM(sizeinbytes) as 's' 
        FROM scsq_quicktraffic 
        WHERE  date>".$datestart."
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")
	   AND par=1
	GROUP BY CRC32(login),login
	ORDER BY null) 
	AS tmp 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (".$goodLoginsList.")) 
		    AS nofriends 
	ON tmp.login=nofriends.id  
 	LEFT JOIN (SELECT 
		      name,
		      tableid		      
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 
  
  WHERE (1=1)
  ".$msgNoZeroTraffic."

  GROUP BY nofriends.name,
		   nofriends.id,
		   tmp.s
		   ".$echoLoginAliasColumn.";";

#postgre version
if($dbtype==1)
$queryLoginsTraffic="
  SELECT 
    nofriends.name,
    tmp.s,
    nofriends.id
    ".$echoLoginAliasColumn."
  FROM (SELECT 
          login,
          SUM(sizeinbytes) as s 
        FROM scsq_quicktraffic 
        WHERE  date>".$datestart."
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")
	   AND par=1
	   GROUP BY login

	) 
	AS tmp 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (".$goodLoginsList.")) 
		    AS nofriends 
	ON tmp.login=nofriends.id  
 	LEFT JOIN (SELECT 
		      name,
		      tableid		      
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 

	WHERE (1=1)
  ".$msgNoZeroTraffic."

  GROUP BY nofriends.name,
    nofriends.id,
    tmp.s
    ".$echoLoginAliasColumn."
;";

#echo $queryLoginsTraffic;


#$queryLoginsTraffic=$queryLoginsTraffic1;

///echo $queryLoginsTraffic1;

//echo $queryLoginsTraffic1;

if($useIpaddressalias==0)
$echoIpaddressAliasColumn="";
if($useIpaddressalias==1)
$echoIpaddressAliasColumn=",aliastbl.name";

#mysql version
$queryIpaddressTraffic="
  SELECT 
    nofriends.name as 'v_name',
    tmp.s as 'v_traffic',
    nofriends.id as 'v_name_id' 
    ".$echoIpaddressAliasColumn." 
  FROM (SELECT 
	  ipaddress,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
	GROUP BY CRC32(ipaddress),ipaddress
	ORDER BY null) 
	AS tmp 
	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    WHERE id NOT IN (".$goodIpaddressList.")) AS nofriends 
	ON tmp.ipaddress=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=1) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 
	WHERE (1=1)
  ".$msgNoZeroTraffic."

  GROUP BY nofriends.name,
    tmp.s,
    nofriends.id 
	".$echoIpaddressAliasColumn."
	ORDER BY nofriends.name asc
   ;";

  #postgre version
if($dbtype==1)
  $queryIpaddressTraffic="
  SELECT 
    nofriends.name,
    tmp.s,
    nofriends.id 
    ".$echoIpaddressAliasColumn."
  FROM (SELECT 
	  ipaddress,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
	GROUP BY ipaddress
	) 
	AS tmp 
	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    WHERE id NOT IN (".$goodIpaddressList.")) AS nofriends 
	ON tmp.ipaddress=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=1) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 
WHERE (1=1)
  ".$msgNoZeroTraffic."

  GROUP BY nofriends.name,
    tmp.s,
    nofriends.id 
	".$echoIpaddressAliasColumn."
  ORDER BY nofriends.name asc
   ;";


//echo $queryIpaddressTraffic;

#mysql version
$querySitesTraffic="
  SELECT tmp2.site,
	 tmp2.s,
	 case
		when (SUBSTRING_INDEX(site,'/',1) REGEXP '^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?')  
			then 2
			else 1
		end, 
	 ''
	 ".$category."
  
  FROM (SELECT 
		 sum(sizeinbytes) as s,
		 site
		 ".$category."
	       FROM scsq_quicktraffic
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_logins 
			   WHERE id  IN (".$goodLoginsList.")) 
			   AS tmplogin 
	       ON tmplogin.id=scsq_quicktraffic.login
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_ipaddress 
			   WHERE id  IN (".$goodIpaddressList.")) as tmpipaddress 
	       ON tmpipaddress.id=scsq_quicktraffic.ipaddress       	

	       WHERE date>".$datestart." 
	  	 AND date<".$dateend."
 		 AND par=1
		 AND tmplogin.id IS NULL 
	   	 AND tmpipaddress.id IS NULL
	 	 AND site NOT IN (".$goodSitesList.")
	       GROUP BY CRC32(site),site ".$category."
	       ORDER BY null
	      ) as tmp2


  ORDER BY site asc
;";

#postgre version
if($dbtype==1)
$querySitesTraffic="
  SELECT tmp2.site,
	 tmp2.s,
	 case 
		when (left(reverse(split_part(reverse(split_part(site,'/',1)),'.',1)),1) ~ '[0-9]') then 1 else 2 
	 end,	
	 ''
	 ".$category."
  
  FROM (SELECT 
		 sum(sizeinbytes) as s,
		 site
		 ".$category."
	       FROM scsq_quicktraffic
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_logins 
			   WHERE id  IN (".$goodLoginsList.")) 
			   AS tmplogin 
	       ON tmplogin.id=scsq_quicktraffic.login
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_ipaddress 
			   WHERE id  IN (".$goodIpaddressList.")) as tmpipaddress 
	       ON tmpipaddress.id=scsq_quicktraffic.ipaddress       	

	       WHERE date>".$datestart." 
	  	 AND date<".$dateend."
 		 AND par=1
		 AND tmplogin.id IS NULL 
	   	 AND tmpipaddress.id IS NULL
	 	 AND site NOT IN (".$goodSitesList.")
	     
	       GROUP BY site ".$category."
	       
	      ) as tmp2

  ORDER BY site asc
;";


//echo $querySitesTraffic;

#mysql version
$queryTopSitesTraffic="
  SELECT tmp2.site,
	 tmp2.s,
	 case
		when (SUBSTRING_INDEX(site,'/',1) REGEXP '^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?')  
			then 2
			else 1
		end
		 
  FROM (SELECT 
		 sum(sizeinbytes) as s,
		 site
	       FROM scsq_quicktraffic
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_logins 
			   WHERE id  IN (".$goodLoginsList.")) 
			   AS tmplogin 
	       ON tmplogin.id=scsq_quicktraffic.login
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_ipaddress 
			   WHERE id  IN (".$goodIpaddressList.")) as tmpipaddress 
	       ON tmpipaddress.id=scsq_quicktraffic.ipaddress       	

	       WHERE date>".$datestart." 
	  	 AND date<".$dateend."
 		 AND par=1
		 AND tmplogin.id IS NULL 
	   	 AND tmpipaddress.id IS NULL
	 	 AND site NOT IN (".$goodSitesList.")

	       GROUP BY CRC32(site),site
	       ORDER BY null
	      ) as tmp2
 	       
	  
	 
 
	 
  ORDER BY tmp2.s desc
  LIMIT ".$countTopSitesLimit." ";

//echo $queryTopSitesTraffic;

#postgre version
if($dbtype==1)
$queryTopSitesTraffic="
  SELECT tmp2.site,
	 tmp2.s,
	 case 
		when (left(reverse(split_part(reverse(split_part(site,'/',1)),'.',1)),1) ~ '[0-9]') then 1 else 2 
	 end
	   
  FROM (SELECT 
		 sum(sizeinbytes) as s,
		 site
	       FROM scsq_quicktraffic
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_logins 
			   WHERE id  IN (".$goodLoginsList.")) 
			   AS tmplogin 
	       ON tmplogin.id=scsq_quicktraffic.login
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_ipaddress 
			   WHERE id  IN (".$goodIpaddressList.")) as tmpipaddress 
	       ON tmpipaddress.id=scsq_quicktraffic.ipaddress       	

	       WHERE date>".$datestart." 
	  	 AND date<".$dateend."
 		 AND par=1
		 AND tmplogin.id IS NULL 
	   	 AND tmpipaddress.id IS NULL
	 	 AND site NOT IN (".$goodSitesList.")

	       GROUP BY site
	     
	      ) as tmp2
 	       

	 
  ORDER BY tmp2.s desc
  LIMIT ".$countTopSitesLimit." ";

//echo $queryTopSitesTraffic;

#mysql version
$queryTopLoginsTraffic="
  SELECT 
    nofriends.name,
    tmp.s,
    login 
    ".$echoLoginAliasColumn."
  FROM (SELECT 
	  login,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend."
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
	GROUP BY CRC32(login),login 
	ORDER BY null) 
	AS tmp 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (".$goodLoginsList.")) AS nofriends 
	ON tmp.login=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 

  WHERE tmp.s !=0

  ORDER BY s desc 
  LIMIT ".$globalSS['countTopLoginLimit'].";";

#postgresql version
if($dbtype==1)
$queryTopLoginsTraffic="
  SELECT 
    nofriends.name,
    tmp.s,
    login 
    ".$echoLoginAliasColumn."
  FROM (SELECT 
	  login,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend."
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
	GROUP BY login 
	) 
	AS tmp 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (".$goodLoginsList.")) AS nofriends 
	ON tmp.login=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 

  WHERE tmp.s !=0

  ORDER BY s desc 
  LIMIT ".$countTopLoginLimit.";";

#mysql version
$queryTopIpTraffic="
  SELECT 
    nofriends.name,
    tmp.s,
    ipaddress 
    ".$echoIpaddressAliasColumn."
  FROM (SELECT 
	  ipaddress,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
 	  AND par=1
	GROUP BY CRC32(ipaddress),ipaddress 
	ORDER BY null) 
	AS tmp

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    WHERE id NOT IN (".$goodIpaddressList.")) 
		    AS nofriends 
	ON tmp.ipaddress=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=1) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 

  WHERE tmp.s !=0

  ORDER BY s desc 
  LIMIT ".$countTopIpLimit.";";

#postgre version
if($dbtype==1)
$queryTopIpTraffic="
  SELECT 
    nofriends.name,
    tmp.s,
    ipaddress 
    ".$echoIpaddressAliasColumn."
  FROM (SELECT 
	  ipaddress,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
 	  AND par=1
	GROUP BY ipaddress 
	) 
	AS tmp

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    WHERE id NOT IN (".$goodIpaddressList.")) 
		    AS nofriends 
	ON tmp.ipaddress=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=1) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 

  WHERE tmp.s !=0

  ORDER BY s desc 
  LIMIT ".$countTopIpLimit.";";

#mysql version
$queryTrafficByHours="
SELECT
hrs.hr_txt,
tmp2.sum_bytes,
hrs.hr 
from (
  SELECT 
    FROM_UNIXTIME(tmp.date,'%H') AS d,
    SUM(tmp.s) sum_bytes 
  FROM (SELECT 
	  date,
	  SUM(sizeinbytes) AS s
	FROM scsq_quicktraffic 
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is  NULL 
	  AND tmpipaddress.id is  NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
	GROUP BY CRC32(date),date 
	ORDER BY null) 
	AS tmp 

  GROUP BY d
  ) tmp2

  RIGHT JOIN (
	select 0 as hr, '0:00-1:00' as hr_txt 
	UNION all 
	select 1 as hr, '1:00-2:00' as hr_txt 
	UNION all 
	select 2 as hr, '2:00-3:00' as hr_txt 
	UNION all 
	select 3 as hr, '3:00-4:00' as hr_txt 
	UNION all 
	select 4 as hr, '4:00-5:00' as hr_txt 
	UNION all 
	select 5 as hr, '5:00-6:00' as hr_txt 
	UNION all 
	select 6 as hr, '6:00-7:00' as hr_txt 
	UNION all 
	select 7 as hr, '7:00-8:00' as hr_txt 
	UNION all 
	select 8 as hr, '8:00-9:00' as hr_txt 
	UNION all 
	select 9 as hr, '9:00-10:00' as hr_txt 
	UNION all 
	select 10 as hr, '10:00-11:00' as hr_txt 
	UNION all 
	select 11 as hr, '11:00-12:00' as hr_txt 
	UNION all 
	select 12 as hr, '12:00-13:00' as hr_txt 
	UNION all 
	select 13 as hr, '13:00-14:00' as hr_txt 
	UNION all 
	select 14 as hr, '14:00-15:00' as hr_txt 
	UNION all 
	select 15 as hr, '15:00-16:00' as hr_txt 
	UNION all 
	select 16 as hr, '16:00-17:00' as hr_txt 
	UNION all 
	select 17 as hr, '17:00-18:00' as hr_txt 
	UNION all 
	select 18 as hr, '18:00-19:00' as hr_txt 
	UNION all 
	select 19 as hr, '19:00-20:00' as hr_txt 
	UNION all 
	select 20 as hr, '20:00-21:00' as hr_txt 
	UNION all 
	select 21 as hr, '21:00-22:00' as hr_txt 
	UNION all 
	select 22 as hr, '22:00-23:00' as hr_txt 
	UNION all 
	select 23 as hr, '23:00-24:00' as hr_txt 
	
				 
				 ) hrs on hrs.hr=tmp2.d
				 
				 order by hrs.hr asc

  ;";
  
  #postgresql version
if($dbtype==1)
  $queryTrafficByHours="
  SELECT
hrs.hr_txt,
tmp2.sum_bytes,
hrs.hr 
from (
  SELECT 
    to_char(to_timestamp(tmp.date),'HH24') as d,
    SUM(tmp.s) sum_bytes
  FROM (SELECT 
	  date,
	  SUM(sizeinbytes) AS s
	FROM scsq_quicktraffic 
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is  NULL 
	  AND tmpipaddress.id is  NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
	GROUP BY date)
	AS tmp
	GROUP BY d 
  ) tmp2

  RIGHT JOIN (
	select 0 as hr, '0:00-1:00' as hr_txt 
	UNION all 
	select 1 as hr, '1:00-2:00' as hr_txt 
	UNION all 
	select 2 as hr, '2:00-3:00' as hr_txt 
	UNION all 
	select 3 as hr, '3:00-4:00' as hr_txt 
	UNION all 
	select 4 as hr, '4:00-5:00' as hr_txt 
	UNION all 
	select 5 as hr, '5:00-6:00' as hr_txt 
	UNION all 
	select 6 as hr, '6:00-7:00' as hr_txt 
	UNION all 
	select 7 as hr, '7:00-8:00' as hr_txt 
	UNION all 
	select 8 as hr, '8:00-9:00' as hr_txt 
	UNION all 
	select 9 as hr, '9:00-10:00' as hr_txt 
	UNION all 
	select 10 as hr, '10:00-11:00' as hr_txt 
	UNION all 
	select 11 as hr, '11:00-12:00' as hr_txt 
	UNION all 
	select 12 as hr, '12:00-13:00' as hr_txt 
	UNION all 
	select 13 as hr, '13:00-14:00' as hr_txt 
	UNION all 
	select 14 as hr, '14:00-15:00' as hr_txt 
	UNION all 
	select 15 as hr, '15:00-16:00' as hr_txt 
	UNION all 
	select 16 as hr, '16:00-17:00' as hr_txt 
	UNION all 
	select 17 as hr, '17:00-18:00' as hr_txt 
	UNION all 
	select 18 as hr, '18:00-19:00' as hr_txt 
	UNION all 
	select 19 as hr, '19:00-20:00' as hr_txt 
	UNION all 
	select 20 as hr, '20:00-21:00' as hr_txt 
	UNION all 
	select 21 as hr, '21:00-22:00' as hr_txt 
	UNION all 
	select 22 as hr, '22:00-23:00' as hr_txt 
	UNION all 
	select 23 as hr, '23:00-24:00' as hr_txt 
	
				 
				 ) hrs on hrs.hr=CAST(tmp2.d as integer)
				 
				 order by hrs.hr asc

  ;";



#mysql version
$queryTopLoginsWorkingHoursTraffic="
SELECT 
nofriends.name,
tmp.s,
nofriends.id
".$echoLoginAliasColumn."
FROM (SELECT 
	  login,
	  SUM(sizeinbytes) as 's' 
	FROM scsq_traffic 
	WHERE  date>".$datestart."
   AND date<".$dateend." 
   AND site NOT IN (".$goodSitesList.")
   AND (
	(	
		(FROM_UNIXTIME(date,'%k:%i')>=str_to_date('".$workStartHour1.":".$workStartMin1."','%k:%i'))     
	 AND(FROM_UNIXTIME(date,'%k:%i') < str_to_date('".$workEndHour1.":".$workEndMin1."','%k:%i'))
  	)
   OR
   (	
	(FROM_UNIXTIME(date,'%k:%i')>=str_to_date('".$workStartHour2.":".$workStartMin2."','%k:%i'))     
	AND(FROM_UNIXTIME(date,'%k:%i') < str_to_date('".$workEndHour2.":".$workEndMin2."','%k:%i'))
   )

 	)
 GROUP BY CRC32(login),login 
ORDER BY null) 
AS tmp 

RIGHT JOIN (SELECT 
		  id,
		  name 
		FROM scsq_logins 
		WHERE id NOT IN (".$goodLoginsList.")) 
		AS nofriends 
ON tmp.login=nofriends.id  
 LEFT JOIN (SELECT 
		  name,
		  tableid 
	   FROM scsq_alias 
	   WHERE typeid=0) 
	   AS aliastbl 
ON nofriends.id=aliastbl.tableid 
WHERE (1=1)
".$msgNoZeroTraffic."";


#postgresql version не тестировалось
if($dbtype==1)
$queryTopLoginsWorkingHoursTraffic="
SELECT 
nofriends.name,
tmp.s,
nofriends.id
".$echoLoginAliasColumn."
FROM (SELECT 
	  login,
	  SUM(sizeinbytes) as 's' 
	FROM scsq_quicktraffic 
	WHERE  date>".$datestart."
   AND date<".$dateend." 
   AND site NOT IN (".$goodSitesList.")
   AND ((FROM_UNIXTIME(date,'%k')>=".$workStart1." AND FROM_UNIXTIME(date,'%k')<".$workEnd1.")
   OR (FROM_UNIXTIME(date,'%k')>=".$workStart2." AND FROM_UNIXTIME(date,'%k')<".$workEnd2."))
	 
   AND par=1
GROUP BY login
ORDER BY null) 
AS tmp 

RIGHT JOIN (SELECT 
		  id,
		  name 
		FROM scsq_logins 
		WHERE id NOT IN (".$goodLoginsList.")) 
		AS nofriends 
ON tmp.login=nofriends.id  
 LEFT JOIN (SELECT 
		  name,
		  tableid 
	   FROM scsq_alias 
	   WHERE typeid=0) 
	   AS aliastbl 
ON nofriends.id=aliastbl.tableid 
WHERE (1=1)
".$msgNoZeroTraffic."

GROUP BY nofriends.name, nofriends.id ".$echoLoginAliasColumn.";";


#mysql version
$queryTopIpWorkingHoursTraffic="
SELECT 
nofriends.name,
tmp.s,
nofriends.id 
".$echoIpaddressAliasColumn."
FROM (SELECT 
  ipaddress,
  SUM(sizeinbytes) AS s 
FROM scsq_traffic 
WHERE date>".$datestart." 
  AND date<".$dateend." 
  AND site NOT IN (".$goodSitesList.")
  AND (
	(	
		(FROM_UNIXTIME(date,'%k:%i')>=str_to_date('".$workStartHour1.":".$workStartMin1."','%k:%i'))     
	 AND(FROM_UNIXTIME(date,'%k:%i') < str_to_date('".$workEndHour1.":".$workEndMin1."','%k:%i'))
  	)
   OR
   (	
	(FROM_UNIXTIME(date,'%k:%i')>=str_to_date('".$workStartHour2.":".$workStartMin2."','%k:%i'))     
	AND(FROM_UNIXTIME(date,'%k:%i') < str_to_date('".$workEndHour2.":".$workEndMin2."','%k:%i'))
   )
	   )
	

GROUP BY CRC32(ipaddress),ipaddress
ORDER BY null) 
AS tmp 
RIGHT JOIN (SELECT 
		  id,
		  name 
		FROM scsq_ipaddress 
		WHERE id NOT IN (".$goodIpaddressList.")) AS nofriends 
ON tmp.ipaddress=nofriends.id  
LEFT JOIN (SELECT 
		  name,
		  tableid 
	   FROM scsq_alias 
	   WHERE typeid=1) 
	   AS aliastbl 
ON nofriends.id=aliastbl.tableid 
WHERE (1=1)
".$msgNoZeroTraffic.";";

#postgre version не проверялось
if($dbtype==1)
$queryTopIpWorkingHoursTraffic="
SELECT 
nofriends.name,
tmp.s,
nofriends.id 
".$echoIpaddressAliasColumn."
FROM (SELECT 
  ipaddress,
  SUM(sizeinbytes) AS s 
FROM scsq_quicktraffic 
WHERE date>".$datestart." 
  AND date<".$dateend." 
  AND site NOT IN (".$goodSitesList.")
  AND ((FROM_UNIXTIME(date,'%k')>=".$workStart1." AND FROM_UNIXTIME(date,'%k')<".$workEnd1.")
  OR (FROM_UNIXTIME(date,'%k')>=".$workStart2." AND FROM_UNIXTIME(date,'%k')<".$workEnd2."))
	
  AND par=1
GROUP BY ipaddress 
ORDER BY null) 
AS tmp 
RIGHT JOIN (SELECT 
		  id,
		  name 
		FROM scsq_ipaddress 
		WHERE id NOT IN (".$goodIpaddressList.")) AS nofriends 
ON tmp.ipaddress=nofriends.id  
LEFT JOIN (SELECT 
		  name,
		  tableid 
	   FROM scsq_alias 
	   WHERE typeid=1) 
	   AS aliastbl 
ON nofriends.id=aliastbl.tableid 
WHERE (1=1)
".$msgNoZeroTraffic."

GROUP BY nofriends.name, nofriends.id ".$echoLoginAliasColumn.";";

$queryLoginsTrafficWide="
SELECT 
	tmp2.name,
	sum(tmp2.sum_in_cache),
	sum(tmp2.sum_out_cache),
	sum(tmp2.sum_bytes),
	sum(tmp2.sum_denied),
	sum(tmp2.sum_bytes) - sum(tmp2.sum_denied),
	sum(tmp2.sum_in_cache)/sum(tmp2.sum_bytes)*100,
	sum(tmp2.sum_out_cache)/sum(tmp2.sum_bytes)*100,
	tmp2.login
	FROM (
  SELECT 
    nofriends.name,
    tmp.sum_in_cache,
    tmp.sum_out_cache,
    tmp.sum_bytes,
	tmp.sum_denied,
	tmp.login,
    tmp.n 
  FROM ((SELECT 
	   login,
	   '2' AS n,
	   
	   SUM(sizeinbytes) AS sum_in_cache,
	   0 AS sum_out_cache,
	   0 as sum_bytes,
	   0 as sum_denied

	 FROM scsq_quicktraffic, scsq_httpstatus 

	 WHERE (scsq_httpstatus.name like '%TCP_HIT%' 
	     OR scsq_httpstatus.name like '%TCP_IMS_HIT%' 
	     OR scsq_httpstatus.name like '%TCP_MEM_HIT%' 
	     OR scsq_httpstatus.name like '%TCP_OFFLINE_HIT%' 
	     OR scsq_httpstatus.name like '%UDP_HIT%') 
	    AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	    AND date>".$datestart." 
	    AND date<".$dateend." 
	    AND site NOT IN (".$goodSitesList.")
 	    AND par=1
	 GROUP BY CRC32(login) ,login
	 ORDER BY null) 

  UNION 

	(SELECT 
	   login,
	   '3' AS n,
	   0 as sum_in_cache,
	   SUM(sizeinbytes) as sum_out_cache,
	   0 as sum_bytes,
	   0 as sum_denied
	 FROM scsq_quicktraffic, scsq_httpstatus 

	 WHERE (scsq_httpstatus.name not like '%TCP_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_IMS_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_MEM_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_OFFLINE_HIT%' 
	   AND  scsq_httpstatus.name not like '%UDP_HIT%') 
	   AND  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	   AND  date>".$datestart." 
	   AND  date<".$dateend."  
	   AND site NOT IN (".$goodSitesList.")
	   AND par=1	  
	 GROUP BY CRC32(login) ,login
	 ORDER BY null) 

  UNION 

	(SELECT 
	   login,
	   '1' AS n,
	   0 as sum_in_cache,
	   0 as sum_out_cache,
	   SUM(sizeinbytes) AS sum_bytes,
	   0 as sum_denied 
	 FROM scsq_quicktraffic 
	 WHERE date>".$datestart." 
	   AND date<".$dateend."  
	   AND site NOT IN (".$goodSitesList.")
 	   AND par=1	   
	 GROUP BY crc32(login) ,login
	 ORDER BY null)

   UNION 

	 (SELECT 
		login,
		'4' AS n,
		0 as sum_in_cache,
		0 as sum_out_cache,
		0 AS sum_bytes,
		SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic,scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1   
	  GROUP BY crc32(login) ,login
	  ORDER BY null)	 
	 
	 ) 
	 AS tmp

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_logins 
		     WHERE id NOT IN (".$goodLoginsList.")) AS nofriends 
	 ON tmp.login=nofriends.id 
		) tmp2
  WHERE tmp2.login is not NULL
  GROUP BY tmp2.login,tmp2.name
  ORDER BY tmp2.name asc
  ;";
  
  
#postgre version
if($dbtype==1)
$queryLoginsTrafficWide="
SELECT 
	tmp2.name,
	sum(tmp2.sum_in_cache),
	sum(tmp2.sum_out_cache),
	sum(tmp2.sum_bytes),
	sum(tmp2.sum_denied),
	sum(tmp2.sum_bytes) - sum(tmp2.sum_denied),
	sum(tmp2.sum_in_cache)/sum(tmp2.sum_bytes)*100,
	sum(tmp2.sum_out_cache)/sum(tmp2.sum_bytes)*100,
	tmp2.login
	FROM (
  SELECT 
    nofriends.name,
    tmp.sum_in_cache,
    tmp.sum_out_cache,
    tmp.sum_bytes,
	tmp.sum_denied,
	tmp.login,
    tmp.n 
  FROM ((SELECT 
	   login,
	   '2' AS n,
	   
	   SUM(sizeinbytes) AS sum_in_cache,
	   0 AS sum_out_cache,
	   0 as sum_bytes,
	   0 as sum_denied

	 FROM scsq_quicktraffic, scsq_httpstatus 

	 WHERE (scsq_httpstatus.name like '%TCP_HIT%' 
	     OR scsq_httpstatus.name like '%TCP_IMS_HIT%' 
	     OR scsq_httpstatus.name like '%TCP_MEM_HIT%' 
	     OR scsq_httpstatus.name like '%TCP_OFFLINE_HIT%' 
	     OR scsq_httpstatus.name like '%UDP_HIT%') 
	    AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	    AND date>".$datestart." 
	    AND date<".$dateend." 
	    AND site NOT IN (".$goodSitesList.")
 	    AND par=1
	 GROUP BY login
	 ) 

  UNION 

	(SELECT 
	   login,
	   '3' AS n,
	   0 as sum_in_cache,
	   SUM(sizeinbytes) as sum_out_cache,
	   0 as sum_bytes,
	   0 as sum_denied
	 FROM scsq_quicktraffic, scsq_httpstatus 

	 WHERE (scsq_httpstatus.name not like '%TCP_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_IMS_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_MEM_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_OFFLINE_HIT%' 
	   AND  scsq_httpstatus.name not like '%UDP_HIT%') 
	   AND  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	   AND  date>".$datestart." 
	   AND  date<".$dateend."  
	   AND site NOT IN (".$goodSitesList.")
	   AND par=1	  
	 GROUP BY login
	 ) 

  UNION 

	(SELECT 
	   login,
	   '1' AS n,
	   0 as sum_in_cache,
	   0 as sum_out_cache,
	   SUM(sizeinbytes) AS sum_bytes,
	   0 as sum_denied 
	 FROM scsq_quicktraffic 
	 WHERE date>".$datestart." 
	   AND date<".$dateend."  
	   AND site NOT IN (".$goodSitesList.")
 	   AND par=1	   
	 GROUP BY login
	 )

   UNION 

	 (SELECT 
		login,
		'4' AS n,
		0 as sum_in_cache,
		0 as sum_out_cache,
		0 AS sum_bytes,
		SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic,scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1   
	  GROUP BY login
	  )	 
	 
	 ) 
	 AS tmp

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_logins 
		     WHERE id NOT IN (".$goodLoginsList.")) AS nofriends 
	 ON tmp.login=nofriends.id 
		) tmp2
  WHERE tmp2.login is not NULL
  GROUP BY tmp2.login,tmp2.name
  ORDER BY tmp2.name asc  

;";  
  


$queryIpaddressTrafficWide="
SELECT 
	tmp2.name,
	sum(tmp2.sum_in_cache),
	sum(tmp2.sum_out_cache),
	sum(tmp2.sum_bytes),
	sum(tmp2.sum_denied),
	sum(tmp2.sum_bytes) - sum(tmp2.sum_denied),
	sum(tmp2.sum_in_cache)/sum(tmp2.sum_bytes)*100,
	sum(tmp2.sum_out_cache)/sum(tmp2.sum_bytes)*100,
	tmp2.ipaddress
	FROM (
  SELECT 
    nofriends.name,
    tmp.sum_in_cache,
    tmp.sum_out_cache,
    tmp.sum_bytes,
	tmp.sum_denied,
	tmp.ipaddress,
    tmp.n 
  FROM ((SELECT 
	  ipaddress,
	   '2' AS n,
	   
	   SUM(sizeinbytes) AS sum_in_cache,
	   0 AS sum_out_cache,
	   0 as sum_bytes,
	   0 AS sum_denied
	 FROM scsq_quicktraffic, scsq_httpstatus 

	 WHERE (scsq_httpstatus.name like '%TCP_HIT%' 
	     OR scsq_httpstatus.name like '%TCP_IMS_HIT%' 
	     OR scsq_httpstatus.name like '%TCP_MEM_HIT%' 
	     OR scsq_httpstatus.name like '%TCP_OFFLINE_HIT%' 
	     OR scsq_httpstatus.name like '%UDP_HIT%') 
	    AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	    AND date>".$datestart." 
	    AND date<".$dateend." 
	    AND site NOT IN (".$goodSitesList.")
 	    AND par=1
	 GROUP BY CRC32(ipaddress) ,ipaddress
	 ORDER BY null) 

  UNION 

	(SELECT 
		ipaddress,
	   '3' AS n,
	   0 as sum_in_cache,
	   SUM(sizeinbytes) as sum_out_cache,
	   0 as sum_bytes,
	   0 AS sum_denied
	 FROM scsq_quicktraffic, scsq_httpstatus 

	 WHERE (scsq_httpstatus.name not like '%TCP_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_IMS_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_MEM_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_OFFLINE_HIT%' 
	   AND  scsq_httpstatus.name not like '%UDP_HIT%') 
	   AND  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	   AND  date>".$datestart." 
	   AND  date<".$dateend."  
	   AND site NOT IN (".$goodSitesList.")
	   AND par=1	  
	 GROUP BY CRC32(ipaddress) ,ipaddress
	 ORDER BY null) 

  UNION 

	(SELECT 
	ipaddress,
	   '1' AS n,
	   0 as sum_in_cache,
	   0 as sum_out_cache,
	   SUM(sizeinbytes) AS sum_bytes,
	   0 AS sum_denied
	 FROM scsq_quicktraffic 
	 WHERE date>".$datestart." 
	   AND date<".$dateend."  
	   AND site NOT IN (".$goodSitesList.")
 	   AND par=1	   
	 GROUP BY crc32(ipaddress) ,ipaddress
	 ORDER BY null)
	
 UNION 

	 (SELECT 
	 ipaddress,
		'4' AS n,
		0 as sum_in_cache,
		0 as sum_out_cache,
		0 AS sum_bytes,
		SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic,scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1   
	  GROUP BY crc32(ipaddress) ,ipaddress
	  ORDER BY null)	
	 
	 ) 
	 AS tmp

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_ipaddress 
		     WHERE id NOT IN (".$goodIpaddressList.")) 
		     AS nofriends 
	 ON tmp.ipaddress=nofriends.id 
		) tmp2
  GROUP BY tmp2.ipaddress,tmp2.name
  ORDER BY tmp2.name asc";
  
#postgre version
if($dbtype==1)  
$queryIpaddressTrafficWide="
  SELECT 
    nofriends.name,
    tmp.s,
    tmp.ipaddress,
    tmp.n 
  FROM ((SELECT 
	   ipaddress,
	   '2' AS n,
	   sum(sizeinbytes) AS s 
	 FROM scsq_quicktraffic, scsq_httpstatus 
	 WHERE (scsq_httpstatus.name like '%TCP_HIT%' 
	    OR  scsq_httpstatus.name like '%TCP_IMS_HIT%' 
	    OR  scsq_httpstatus.name like '%TCP_MEM_HIT%' 
	    OR  scsq_httpstatus.name like '%TCP_OFFLINE_HIT%' 
	    OR  scsq_httpstatus.name like '%UDP_HIT%') 
	   AND  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	   AND  date>".$datestart." 
	   AND  date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")
 	   AND par=1
	 GROUP BY ipaddress 
	 ) 

  UNION

	(SELECT 
	   ipaddress,
	   '3' AS n,
	   SUM(sizeinbytes) AS s 
	 FROM scsq_quicktraffic, scsq_httpstatus 
	 WHERE (scsq_httpstatus.name not like '%TCP_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_IMS_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_MEM_HIT%' 
	   AND  scsq_httpstatus.name not like '%TCP_OFFLINE_HIT%' 
	   AND  scsq_httpstatus.name not like '%UDP_HIT%') 
	   AND  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	   AND  date>".$datestart." 
	   AND  date<".$dateend."  
	   AND site NOT IN (".$goodSitesList.")
 	   AND par=1
	 GROUP BY ipaddress 
	 ) 

  UNION 

	(SELECT 
	   ipaddress,
	   '1' AS n,
	   SUM(sizeinbytes) AS s 
	 FROM scsq_quicktraffic 
	 WHERE date>".$datestart." 
	   AND date<".$dateend."  
	   AND site NOT IN (".$goodSitesList.")
 	   AND par=1
	 GROUP BY ipaddress 
	 )) 
	 AS tmp

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_ipaddress 
		     WHERE id NOT IN (".$goodIpaddressList.")) 
		     AS nofriends 
	 ON tmp.ipaddress=nofriends.id 

  ORDER BY nofriends.name asc,tmp.n asc;";


$queryIpaddressTrafficWithResolve="
  SELECT 
    nofriends.name,
    tmp.s,
    ipaddress 
  FROM (SELECT 
	  ipaddress,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
 	  AND par=1
	GROUP BY CRC32(ipaddress) , ipaddress
	ORDER BY null) 
	AS tmp

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    WHERE id NOT IN (".$goodIpaddressList.")) 
		    AS nofriends 
	ON tmp.ipaddress=nofriends.id 
WHERE (1=1)
  ".$msgNoZeroTraffic."

  ORDER BY nofriends.name asc;";
  
  #postgre version
if($dbtype==1)
  $queryIpaddressTrafficWithResolve="
  SELECT 
    nofriends.name,
    tmp.s,
    ipaddress 
  FROM (SELECT 
	  ipaddress,	  
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
 	  AND par=1
	GROUP BY ipaddress
	) 
	AS tmp

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    WHERE id NOT IN (".$goodIpaddressList.")) 
		    AS nofriends 
	ON tmp.ipaddress=nofriends.id 
WHERE (1=1)
  ".$msgNoZeroTraffic."

  ORDER BY nofriends.name asc;";


$queryPopularSites="
	SELECT 
	  SUBSTRING_INDEX(site,'/',1) AS st,
	  sum(sizeinbytes) AS s,
	  count(1) AS c
	  
	  
	FROM scsq_traffic 

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) as tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL
	  AND tmpipaddress.id is NULL
	  AND SUBSTRING_INDEX(site,'/',1) NOT IN (".$goodSitesList.")

   GROUP BY st
   
  ORDER BY c desc 
  LIMIT ".$countPopularSitesLimit.";";
  
#postgre version
if($dbtype==1)
$queryPopularSites="
  SELECT 
	  split_part(site,'/',1) AS st,
	  sum(sizeinbytes) AS s,
	  count(1) AS c
 
	  
	FROM scsq_traffic 

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) as tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL
	  AND tmpipaddress.id is NULL
	  AND split_part(scsq_traffic.site,'/',1) NOT IN (".$goodSitesList.")

	GROUP BY st
  
  ORDER BY c desc 
  LIMIT ".$countPopularSitesLimit.";";


$queryWhoDownloadBigFiles="
  SELECT 
    scsq_log.name,
    scsq_traf.sizeinbytes,
    scsq_ip.name, 
    scsq_traf.site,
    scsq_log.id,
    scsq_ip.id 
  FROM (SELECT 
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic
	
	LEFT OUTER JOIN (SELECT 
			   scsq_logins.id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   scsq_ipaddress.id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress


	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
	  AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")

  	)
  	


	  AS tmp
 
  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
  
  	ORDER BY sizeinbytes desc 
    LIMIT ".$countWhoDownloadBigFilesLimit."
";


#postgre version
if($dbtype==1)
$queryWhoDownloadBigFiles="
  SELECT 
    scsq_log.name,
    scsq_traf.sizeinbytes,
    scsq_ip.name, 
    scsq_traf.site,
    scsq_log.id,
    scsq_ip.id 
  FROM (SELECT 
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic
	
	LEFT OUTER JOIN (SELECT 
			   scsq_logins.id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   scsq_ipaddress.id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress


	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
	  AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")

  )


	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
  	ORDER BY sizeinbytes desc 
  	LIMIT ".$countWhoDownloadBigFilesLimit."
";

$queryTrafficByPeriod="
  	SELECT
	  FROM_UNIXTIME(scsq_quicktraffic.date,'%m.%Y') AS d1,
	  SUM(scsq_quicktraffic.sizeinbytes)

	FROM scsq_quicktraffic

	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login
	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1 
	GROUP BY crc32(FROM_UNIXTIME(scsq_quicktraffic.date,'%Y-%m')),d1
	ORDER BY FROM_UNIXTIME(scsq_quicktraffic.date,'%Y-%m') asc;
;";

#postgre version 
if($dbtype==1)
$queryTrafficByPeriod="
  	SELECT
	  to_char(to_timestamp(scsq_quicktraffic.date),'MM.YYYY') AS d1,
	  SUM(scsq_quicktraffic.sizeinbytes)

	FROM scsq_quicktraffic

	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login
	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1 
	GROUP BY d1
	ORDER BY d1 asc;
;";



$queryTrafficByPeriodDay="
  	SELECT
	  FROM_UNIXTIME(scsq_quicktraffic.date,'%d.%m.%Y') AS d1,
	  SUM(scsq_quicktraffic.sizeinbytes),
	  FROM_UNIXTIME(scsq_quicktraffic.date,'%d-%m-%Y') AS d2,
	  FROM_UNIXTIME(scsq_quicktraffic.date,'%Y-%m-%d') AS d3

	FROM scsq_quicktraffic

	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login
	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND par=1
	GROUP BY d1, d2, d3
	ORDER BY d3 asc;
;";

#postgre version
if($dbtype==1)
$queryTrafficByPeriodDay="
  	SELECT
	  to_char(to_timestamp(scsq_quicktraffic.date),'DD.MM.YYYY') AS d1,
	  SUM(scsq_quicktraffic.sizeinbytes),
	  to_char(to_timestamp(scsq_quicktraffic.date),'DD-MM-YYYY') AS d2,
	  to_char(to_timestamp(scsq_quicktraffic.date),'YYYY-MM-DD') AS d3
	  
	FROM scsq_quicktraffic

	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login
	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND par=1
	GROUP BY d1, d2, d3
	ORDER BY d3 asc
	
;";

//echo $queryTrafficByPeriodDay;

$queryTrafficByPeriodDayname="
SELECT days.day_txt,
		tmp2.sum_bytes
		
		FROM
		(
  	SELECT
	  FROM_UNIXTIME(scsq_quicktraffic.date,'%w') AS d1,
	  SUM(scsq_quicktraffic.sizeinbytes) as sum_bytes
	  
	FROM scsq_quicktraffic

	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login
	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND par=1
	GROUP BY crc32(d1),d1

	) tmp2
  
	RIGHT JOIN (
	  select 0 as day_num, 'stSUNDAY' as day_txt, 7 as day_rus_num
	  UNION all 
	  select 1 as day_num, 'stMONDAY' as day_txt, 1 as day_rus_num
	  UNION all 
	  select 2 as day_num, 'stTUESDAY' as day_txt, 2 as day_rus_num 
	  UNION all 
	  select 3 as day_num, 'stWEDNESDAY' as day_txt, 3 as day_rus_num 
	  UNION all 
	  select 4 as day_num, 'stTHURSDAY' as day_txt, 4 as day_rus_num 
	  UNION all 
	  select 5 as day_num, 'stFRIDAY' as day_txt, 5 as day_rus_num 
	  UNION all 
	  select 6 as day_num, 'stSATURDAY' as day_txt, 6 as day_rus_num 

	  
				   
				   ) days on days.day_num=tmp2.d1
				   
				   order by days.day_rus_num asc
	

;";

#postgre version
if($dbtype==1)
$queryTrafficByPeriodDayname="
  	SELECT
	  to_char(to_timestamp(scsq_quicktraffic.date),'ID') AS d1,
	  SUM(scsq_quicktraffic.sizeinbytes)
	  	  
	FROM scsq_quicktraffic

	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login
	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND par=1
	GROUP BY d1
	
;";


//echo $queryTrafficByPeriodDayname;


$queryHttpStatus= "
  SELECT 
    scsq_httpstatus.name,
    '',
    count(*),
    scsq_quicktraffic.httpstatus 
  FROM scsq_quicktraffic 

  LEFT OUTER JOIN (SELECT 
		     id,
		     name 
		   FROM scsq_logins 
		   WHERE id IN (".$goodLoginsList.")) 
		   AS tmplogin 
  ON tmplogin.id=scsq_quicktraffic.login

  LEFT OUTER JOIN (SELECT 
		     id,
		     name 
		   FROM scsq_ipaddress 
		   WHERE id IN (".$goodIpaddressList.")) 
		   AS tmpipaddress 
  ON tmpipaddress.id=scsq_quicktraffic.ipaddress

  LEFT JOIN scsq_httpstatus 
  ON scsq_httpstatus.id=scsq_quicktraffic.httpstatus 

  WHERE date>".$datestart." 
    AND date<".$dateend." 
    AND tmplogin.id is NULL 
    AND tmpipaddress.id IS NULL
    AND site NOT IN (".$goodSitesList.")
    AND par=1
  GROUP BY CRC32(httpstatus),httpstatus,scsq_httpstatus.name
  ORDER BY scsq_httpstatus.name asc;";


#postgre version
if($dbtype==1)
$queryHttpStatus= "
  SELECT 
    scsq_httpstatus.name,
    '',
    count(*),
    scsq_quicktraffic.httpstatus 
  FROM scsq_quicktraffic 

  LEFT OUTER JOIN (SELECT 
		     id,
		     name 
		   FROM scsq_logins 
		   WHERE id IN (".$goodLoginsList.")) 
		   AS tmplogin 
  ON tmplogin.id=scsq_quicktraffic.login

  LEFT OUTER JOIN (SELECT 
		     id,
		     name 
		   FROM scsq_ipaddress 
		   WHERE id IN (".$goodIpaddressList.")) 
		   AS tmpipaddress 
  ON tmpipaddress.id=scsq_quicktraffic.ipaddress

  LEFT JOIN scsq_httpstatus 
  ON scsq_httpstatus.id=scsq_quicktraffic.httpstatus 

  WHERE date>".$datestart." 
    AND date<".$dateend." 
    AND tmplogin.id is NULL 
    AND tmpipaddress.id IS NULL
    AND site NOT IN (".$goodSitesList.")
    AND par=1
  GROUP BY scsq_httpstatus.name, httpstatus 
  ORDER BY scsq_httpstatus.name asc;";



$queryCountIpaddressOnLogins="
  SELECT 
    scsq_logins.name,
    '',
    scsq_logins.id,
    tmp2.name,
    count(*) AS ct 
  FROM (SELECT DISTINCT 
	  login, 
	  ipaddress 
	FROM scsq_quicktraffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND par=1
	  AND scsq_quicktraffic.login NOT IN (SELECT 
					   id 
					 FROM scsq_logins 
					 WHERE id IN (".$goodLoginsList.")) 
	  AND scsq_quicktraffic.ipaddress NOT IN (SELECT 
					       id 
					     FROM scsq_ipaddress 
					     WHERE id IN (".$goodIpaddressList."))
	  AND site NOT IN (".$goodSitesList.")
	)
	AS tmp

	LEFT JOIN (SELECT 
		     name,
		     tableid 
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   tmp2 
	ON tmp.login=tmp2.tableid 


	, scsq_logins 

  WHERE scsq_logins.id=tmp.login
  GROUP BY scsq_logins.id, scsq_logins.name, tmp2.name
  ORDER BY scsq_logins.name asc;";

$queryCountLoginsOnIpaddress="
  SELECT
    scsq_ipaddress.name,
    '',
    scsq_ipaddress.id,
    tmp2.name,
    count(*) AS ct 
  FROM (SELECT DISTINCT 
	  login, 
	  ipaddress 
	FROM scsq_quicktraffic 

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND par=1
          AND site NOT IN (".$goodSitesList.")
	  AND scsq_quicktraffic.ipaddress NOT IN (SELECT 
					       id 
					     FROM scsq_ipaddress 
					     WHERE id IN (".$goodIpaddressList.")) 
					       AND scsq_quicktraffic.login NOT IN (SELECT 
										id 
									      FROM scsq_logins 
									      WHERE id IN (".$goodLoginsList."))) 
					     AS tmp

					     LEFT JOIN (SELECT 
							  name,
							  tableid 
							FROM scsq_alias 
							WHERE typeid=1) 
							tmp2 
					     ON tmp.ipaddress=tmp2.tableid 

	, scsq_ipaddress 

  WHERE scsq_ipaddress.id=tmp.ipaddress

  GROUP BY scsq_ipaddress.id, scsq_ipaddress.name, tmp2.name
  ORDER BY scsq_ipaddress.name asc;";

////КОСТЫЛЬ
///$echoLoginAliasColumn=",aliastbl.name";
if($useLoginalias==0)
$echoLoginAliasColumn="";
if($useLoginalias==1)
$echoLoginAliasColumn=",aliastbl.name";

$queryWhoVisitSiteOneHourLogin="
  SELECT 
    nofriends.name,
    tmp.s,
    nofriends.id
    ".$echoLoginAliasColumn."
  FROM (SELECT 
          login,
          SUM(sizeinbytes) as 's' 
        FROM scsq_quicktraffic 
        WHERE  date>".$datestart."
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")
	   AND FROM_UNIXTIME(date,'%k')>=".$currenthour."
	   AND FROM_UNIXTIME(date,'%k')<".($currenthour+1)."
	   AND par=1
	GROUP BY CRC32(login) 
	ORDER BY null) 
	AS tmp 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (".$goodLoginsList.")) 
		    AS nofriends 
	ON tmp.login=nofriends.id  
 	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 
WHERE (1=1)
  ".$msgNoZeroTraffic."

  GROUP BY nofriends.name;";

if($useIpaddressalias==0)
$echoIpaddressAliasColumn="";
if($useIpaddressalias==1)
$echoIpaddressAliasColumn=",aliastbl.name";

$queryWhoVisitSiteOneHourIpaddress="
  SELECT 
    nofriends.name,
    tmp.s,
    nofriends.id 
    ".$echoIpaddressAliasColumn."
  FROM (SELECT 
	  ipaddress,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
	  AND FROM_UNIXTIME(date,'%k')>=".$currenthour."
	  AND FROM_UNIXTIME(date,'%k')<".($currenthour+1)."
	  AND par=1
	GROUP BY CRC32(ipaddress) 
	ORDER BY null) 
	AS tmp 
	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    WHERE id NOT IN (".$goodIpaddressList.")) AS nofriends 
	ON tmp.ipaddress=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=1) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 
WHERE (1=1)
  ".$msgNoZeroTraffic."

  GROUP BY nofriends.name ;";

$queryMimeTypesTraffic="
  SELECT 
    mime,
    SUM(sizeinbytes) as st
  FROM scsq_traffic 

  LEFT OUTER JOIN (SELECT 
		     id,
		     name 
		   FROM scsq_logins 
		   WHERE id IN (".$goodLoginsList.")) 
		   AS tmplogin 
  ON tmplogin.id=scsq_traffic.login

  LEFT OUTER JOIN (SELECT 
		     id,
		     name 
		   FROM scsq_ipaddress 
		   WHERE id IN (".$goodIpaddressList.")) 
		   AS tmpipaddress 
  ON tmpipaddress.id=scsq_traffic.ipaddress


  WHERE date>".$datestart." 
    AND date<".$dateend." 
    AND tmplogin.id is NULL 
    AND tmpipaddress.id IS NULL
    AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
    AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")

  GROUP BY CRC32(mime) ,mime
  ORDER BY st desc;";

#postgre version
if($dbtype==1)
$queryMimeTypesTraffic="
  SELECT 
    mime,
    SUM(sizeinbytes) as st
  FROM scsq_traffic 

  LEFT OUTER JOIN (SELECT 
		     id,
		     name 
		   FROM scsq_logins 
		   WHERE id IN (".$goodLoginsList.")) 
		   AS tmplogin 
  ON tmplogin.id=scsq_traffic.login

  LEFT OUTER JOIN (SELECT 
		     id,
		     name 
		   FROM scsq_ipaddress 
		   WHERE id IN (".$goodIpaddressList.")) 
		   AS tmpipaddress 
  ON tmpipaddress.id=scsq_traffic.ipaddress


  WHERE date>".$datestart." 
    AND date<".$dateend." 
    AND tmplogin.id is NULL 
    AND tmpipaddress.id IS NULL
	AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
	AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")

  GROUP BY mime 
  ORDER BY st desc;";

$queryDomainZonesTraffic="
	SELECT 
		 case
			when (SUBSTRING_INDEX(site,'/',1) REGEXP '^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$')  
			then SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(concat(site,'/'),'/',1),'.',-1),':',1)
			else '-'
		 end domzone,
		 
		 sum(sizeinbytes) as s
		 
		 FROM scsq_quicktraffic

	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_logins 
			   WHERE id  IN (".$goodLoginsList.")) 
			   AS tmplogin 
	       ON tmplogin.id=scsq_quicktraffic.login
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_ipaddress 
			   WHERE id  IN (".$goodIpaddressList.")) as tmpipaddress 
	       ON tmpipaddress.id=scsq_quicktraffic.ipaddress       	

	       WHERE date>".$datestart." 
			 AND date<".$dateend."
			 AND tmplogin.id IS NULL 
			 AND tmpipaddress.id IS NULL
			 AND site NOT IN (".$goodSitesList.")
			 AND par<2
			 
	     GROUP BY domzone	       
;
";


#postgre version
if($dbtype==1)
$queryDomainZonesTraffic="
	SELECT 
		 case
			when (left(reverse(split_part(reverse(split_part(site,'/',1)),'.',1)),1) ~ '[0-9]')   then '-' 
			else split_part(split_part((split_part(site,'/',1)),'.',2),':',1)
		 end domzone,
		 
		 sum(sizeinbytes) as s
		 
		 FROM scsq_quicktraffic

	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_logins 
			   WHERE id  IN (".$goodLoginsList.")) 
			   AS tmplogin 
	       ON tmplogin.id=scsq_quicktraffic.login
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_ipaddress 
			   WHERE id  IN (".$goodIpaddressList.")) as tmpipaddress 
	       ON tmpipaddress.id=scsq_quicktraffic.ipaddress       	

	       WHERE date>".$datestart." 
	  	 AND date<".$dateend."
		 AND tmplogin.id IS NULL 
	   	 AND tmpipaddress.id IS NULL
	 	 AND site NOT IN (".$goodSitesList.")
		 AND par<2
	     
	     GROUP BY domzone	       
;
";


$queryTrafficByHoursLogins="
SELECT tmp3.name,
	   tmp3.login, 
	   IFNULL(sum(tmp3.hr0),0) hr0_value, 
	   IFNULL(sum(tmp3.hr1),0) hr1_value,
	   IFNULL(sum(tmp3.hr2),0) hr2_value, 
	   IFNULL(sum(tmp3.hr3),0) hr3_value, 
	   IFNULL(sum(tmp3.hr4),0) hr4_value, 
	   IFNULL(sum(tmp3.hr5),0) hr5_value, 
	   IFNULL(sum(tmp3.hr6),0) hr6_value, 
	   IFNULL(sum(tmp3.hr7),0) hr7_value, 
	   IFNULL(sum(tmp3.hr8),0) hr8_value, 
	   IFNULL(sum(tmp3.hr9),0) hr9_value, 
	   IFNULL(sum(tmp3.hr10),0) hr10_value, 
	   IFNULL(sum(tmp3.hr11),0) hr11_value, 
	   IFNULL(sum(tmp3.hr12),0) hr12_value, 
	   IFNULL(sum(tmp3.hr13),0) hr13_value, 
	   IFNULL(sum(tmp3.hr14),0) hr14_value, 
	   IFNULL(sum(tmp3.hr15),0) hr15_value, 
	   IFNULL(sum(tmp3.hr16),0) hr16_value, 
	   IFNULL(sum(tmp3.hr17),0) hr17_value, 
	   IFNULL(sum(tmp3.hr18),0) hr18_value, 
	   IFNULL(sum(tmp3.hr19),0) hr19_value, 
	   IFNULL(sum(tmp3.hr20),0) hr20_value, 
	   IFNULL(sum(tmp3.hr21),0) hr21_value, 
	   IFNULL(sum(tmp3.hr22),0) hr22_value, 
	   IFNULL(sum(tmp3.hr23),0) hr23_value 

	   FROM (
SELECT 
	tmp2.login,
	tmp2.name,
	case when hrs.hr = 0 then  IFNULL(tmp2.sum_bytes,0) end hr0,
	case when hrs.hr = 1 then  IFNULL(tmp2.sum_bytes,0) end hr1,
	case when hrs.hr = 2 then  IFNULL(tmp2.sum_bytes,0) end hr2,
	case when hrs.hr = 3 then  IFNULL(tmp2.sum_bytes,0) end hr3,
	case when hrs.hr = 4 then  IFNULL(tmp2.sum_bytes,0) end hr4,
	case when hrs.hr = 5 then  IFNULL(tmp2.sum_bytes,0) end hr5,
	case when hrs.hr = 6 then  IFNULL(tmp2.sum_bytes,0) end hr6,
	case when hrs.hr = 7 then  IFNULL(tmp2.sum_bytes,0) end hr7,
	case when hrs.hr = 8 then  IFNULL(tmp2.sum_bytes,0) end hr8,
	case when hrs.hr = 9 then  IFNULL(tmp2.sum_bytes,0) end hr9,
	case when hrs.hr = 10 then  IFNULL(tmp2.sum_bytes,0) end hr10,
	case when hrs.hr = 11 then  IFNULL(tmp2.sum_bytes,0) end hr11,
	case when hrs.hr = 12 then  IFNULL(tmp2.sum_bytes,0) end hr12,
	case when hrs.hr = 13 then  IFNULL(tmp2.sum_bytes,0) end hr13,
	case when hrs.hr = 14 then  IFNULL(tmp2.sum_bytes,0) end hr14,
	case when hrs.hr = 15 then  IFNULL(tmp2.sum_bytes,0) end hr15,
	case when hrs.hr = 16 then  IFNULL(tmp2.sum_bytes,0) end hr16,
	case when hrs.hr = 17 then  IFNULL(tmp2.sum_bytes,0) end hr17,
	case when hrs.hr = 18 then  IFNULL(tmp2.sum_bytes,0) end hr18,
	case when hrs.hr = 19 then  IFNULL(tmp2.sum_bytes,0) end hr19,
	case when hrs.hr = 20 then  IFNULL(tmp2.sum_bytes,0) end hr20,
	case when hrs.hr = 21 then  IFNULL(tmp2.sum_bytes,0) end hr21,
	case when hrs.hr = 22 then  IFNULL(tmp2.sum_bytes,0) end hr22,
	case when hrs.hr = 23 then  IFNULL(tmp2.sum_bytes,0) end hr23

FROM (

SELECT  login,
nofriends.name,
sum(sizeinbytes) as sum_bytes,
FROM_UNIXTIME(date,'%k') d

FROM scsq_quicktraffic
	LEFT JOIN (SELECT 
	id,
	name 
	FROM scsq_logins)
	AS nofriends 
	ON scsq_quicktraffic.login=nofriends.id  
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is  NULL 
	  AND tmpipaddress.id is  NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
GROUP BY login, d, nofriends.name

) tmp2

RIGHT JOIN (
  select 0 as hr, '0:00-1:00' as hr_txt 
  UNION all 
  select 1 as hr, '1:00-2:00' as hr_txt 
  UNION all 
  select 2 as hr, '2:00-3:00' as hr_txt 
  UNION all 
  select 3 as hr, '3:00-4:00' as hr_txt 
  UNION all 
  select 4 as hr, '4:00-5:00' as hr_txt 
  UNION all 
  select 5 as hr, '5:00-6:00' as hr_txt 
  UNION all 
  select 6 as hr, '6:00-7:00' as hr_txt 
  UNION all 
  select 7 as hr, '7:00-8:00' as hr_txt 
  UNION all 
  select 8 as hr, '8:00-9:00' as hr_txt 
  UNION all 
  select 9 as hr, '9:00-10:00' as hr_txt 
  UNION all 
  select 10 as hr, '10:00-11:00' as hr_txt 
  UNION all 
  select 11 as hr, '11:00-12:00' as hr_txt 
  UNION all 
  select 12 as hr, '12:00-13:00' as hr_txt 
  UNION all 
  select 13 as hr, '13:00-14:00' as hr_txt 
  UNION all 
  select 14 as hr, '14:00-15:00' as hr_txt 
  UNION all 
  select 15 as hr, '15:00-16:00' as hr_txt 
  UNION all 
  select 16 as hr, '16:00-17:00' as hr_txt 
  UNION all 
  select 17 as hr, '17:00-18:00' as hr_txt 
  UNION all 
  select 18 as hr, '18:00-19:00' as hr_txt 
  UNION all 
  select 19 as hr, '19:00-20:00' as hr_txt 
  UNION all 
  select 20 as hr, '20:00-21:00' as hr_txt 
  UNION all 
  select 21 as hr, '21:00-22:00' as hr_txt 
  UNION all 
  select 22 as hr, '22:00-23:00' as hr_txt 
  UNION all 
  select 23 as hr, '23:00-24:00' as hr_txt 
  
			   
			   ) hrs on hrs.hr=tmp2.d
			   
			   order by hrs.hr asc
) tmp3
WHERE tmp3.login is not null
GROUP BY tmp3.login, tmp3.name
ORDER BY tmp3.name asc
;
";

#postgre version
if($dbtype==1)
$queryTrafficByHoursLogins="

SELECT  login,
nofriends.name,
sum(sizeinbytes),
CAST (to_char(to_timestamp(date),'HH24') as int) d
FROM scsq_quicktraffic
	LEFT JOIN (SELECT 
	id,
	name 
	FROM scsq_logins)
	AS nofriends 
	ON scsq_quicktraffic.login=nofriends.id  
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is  NULL 
	  AND tmpipaddress.id is  NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
GROUP BY login,d, nofriends.name
ORDER BY nofriends.name
;
";


$queryTrafficByHoursIpaddress="
SELECT tmp3.name,
	   tmp3.ipaddress, 
	   IFNULL(sum(tmp3.hr0),0) hr0_value, 
	   IFNULL(sum(tmp3.hr1),0) hr1_value,
	   IFNULL(sum(tmp3.hr2),0) hr2_value, 
	   IFNULL(sum(tmp3.hr3),0) hr3_value, 
	   IFNULL(sum(tmp3.hr4),0) hr4_value, 
	   IFNULL(sum(tmp3.hr5),0) hr5_value, 
	   IFNULL(sum(tmp3.hr6),0) hr6_value, 
	   IFNULL(sum(tmp3.hr7),0) hr7_value, 
	   IFNULL(sum(tmp3.hr8),0) hr8_value, 
	   IFNULL(sum(tmp3.hr9),0) hr9_value, 
	   IFNULL(sum(tmp3.hr10),0) hr10_value, 
	   IFNULL(sum(tmp3.hr11),0) hr11_value, 
	   IFNULL(sum(tmp3.hr12),0) hr12_value, 
	   IFNULL(sum(tmp3.hr13),0) hr13_value, 
	   IFNULL(sum(tmp3.hr14),0) hr14_value, 
	   IFNULL(sum(tmp3.hr15),0) hr15_value, 
	   IFNULL(sum(tmp3.hr16),0) hr16_value, 
	   IFNULL(sum(tmp3.hr17),0) hr17_value, 
	   IFNULL(sum(tmp3.hr18),0) hr18_value, 
	   IFNULL(sum(tmp3.hr19),0) hr19_value, 
	   IFNULL(sum(tmp3.hr20),0) hr20_value, 
	   IFNULL(sum(tmp3.hr21),0) hr21_value, 
	   IFNULL(sum(tmp3.hr22),0) hr22_value, 
	   IFNULL(sum(tmp3.hr23),0) hr23_value 

	   FROM (
SELECT 
	tmp2.ipaddress,
	tmp2.name,
	case when hrs.hr = 0 then  IFNULL(tmp2.sum_bytes,0) end hr0,
	case when hrs.hr = 1 then  IFNULL(tmp2.sum_bytes,0) end hr1,
	case when hrs.hr = 2 then  IFNULL(tmp2.sum_bytes,0) end hr2,
	case when hrs.hr = 3 then  IFNULL(tmp2.sum_bytes,0) end hr3,
	case when hrs.hr = 4 then  IFNULL(tmp2.sum_bytes,0) end hr4,
	case when hrs.hr = 5 then  IFNULL(tmp2.sum_bytes,0) end hr5,
	case when hrs.hr = 6 then  IFNULL(tmp2.sum_bytes,0) end hr6,
	case when hrs.hr = 7 then  IFNULL(tmp2.sum_bytes,0) end hr7,
	case when hrs.hr = 8 then  IFNULL(tmp2.sum_bytes,0) end hr8,
	case when hrs.hr = 9 then  IFNULL(tmp2.sum_bytes,0) end hr9,
	case when hrs.hr = 10 then  IFNULL(tmp2.sum_bytes,0) end hr10,
	case when hrs.hr = 11 then  IFNULL(tmp2.sum_bytes,0) end hr11,
	case when hrs.hr = 12 then  IFNULL(tmp2.sum_bytes,0) end hr12,
	case when hrs.hr = 13 then  IFNULL(tmp2.sum_bytes,0) end hr13,
	case when hrs.hr = 14 then  IFNULL(tmp2.sum_bytes,0) end hr14,
	case when hrs.hr = 15 then  IFNULL(tmp2.sum_bytes,0) end hr15,
	case when hrs.hr = 16 then  IFNULL(tmp2.sum_bytes,0) end hr16,
	case when hrs.hr = 17 then  IFNULL(tmp2.sum_bytes,0) end hr17,
	case when hrs.hr = 18 then  IFNULL(tmp2.sum_bytes,0) end hr18,
	case when hrs.hr = 19 then  IFNULL(tmp2.sum_bytes,0) end hr19,
	case when hrs.hr = 20 then  IFNULL(tmp2.sum_bytes,0) end hr20,
	case when hrs.hr = 21 then  IFNULL(tmp2.sum_bytes,0) end hr21,
	case when hrs.hr = 22 then  IFNULL(tmp2.sum_bytes,0) end hr22,
	case when hrs.hr = 23 then  IFNULL(tmp2.sum_bytes,0) end hr23

FROM (
SELECT  ipaddress,
nofriends.name,
sum(sizeinbytes) sum_bytes,
FROM_UNIXTIME(date,'%k') d
FROM scsq_quicktraffic
	LEFT JOIN (SELECT 
	id,
	name 
	FROM scsq_ipaddress)
	AS nofriends 
	ON scsq_quicktraffic.ipaddress=nofriends.id  
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is  NULL 
	  AND tmpipaddress.id is  NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
GROUP BY ipaddress,d, nofriends.name

) tmp2

RIGHT JOIN (
  select 0 as hr, '0:00-1:00' as hr_txt 
  UNION all 
  select 1 as hr, '1:00-2:00' as hr_txt 
  UNION all 
  select 2 as hr, '2:00-3:00' as hr_txt 
  UNION all 
  select 3 as hr, '3:00-4:00' as hr_txt 
  UNION all 
  select 4 as hr, '4:00-5:00' as hr_txt 
  UNION all 
  select 5 as hr, '5:00-6:00' as hr_txt 
  UNION all 
  select 6 as hr, '6:00-7:00' as hr_txt 
  UNION all 
  select 7 as hr, '7:00-8:00' as hr_txt 
  UNION all 
  select 8 as hr, '8:00-9:00' as hr_txt 
  UNION all 
  select 9 as hr, '9:00-10:00' as hr_txt 
  UNION all 
  select 10 as hr, '10:00-11:00' as hr_txt 
  UNION all 
  select 11 as hr, '11:00-12:00' as hr_txt 
  UNION all 
  select 12 as hr, '12:00-13:00' as hr_txt 
  UNION all 
  select 13 as hr, '13:00-14:00' as hr_txt 
  UNION all 
  select 14 as hr, '14:00-15:00' as hr_txt 
  UNION all 
  select 15 as hr, '15:00-16:00' as hr_txt 
  UNION all 
  select 16 as hr, '16:00-17:00' as hr_txt 
  UNION all 
  select 17 as hr, '17:00-18:00' as hr_txt 
  UNION all 
  select 18 as hr, '18:00-19:00' as hr_txt 
  UNION all 
  select 19 as hr, '19:00-20:00' as hr_txt 
  UNION all 
  select 20 as hr, '20:00-21:00' as hr_txt 
  UNION all 
  select 21 as hr, '21:00-22:00' as hr_txt 
  UNION all 
  select 22 as hr, '22:00-23:00' as hr_txt 
  UNION all 
  select 23 as hr, '23:00-24:00' as hr_txt 
  
			   
			   ) hrs on hrs.hr=tmp2.d
			   
			   order by hrs.hr asc
) tmp3
WHERE tmp3.ipaddress is not null
GROUP BY tmp3.ipaddress, tmp3.name
ORDER BY tmp3.name asc

;
";

#postgre version
if($dbtype==1)
$queryTrafficByHoursIpaddress="
SELECT  ipaddress,
nofriends.name,
sum(sizeinbytes),
CAST(to_char(to_timestamp(date),'HH24') as int) d
FROM scsq_quicktraffic
	LEFT JOIN (SELECT 
	id,
	name 
	FROM scsq_ipaddress)
	AS nofriends 
	ON scsq_quicktraffic.ipaddress=nofriends.id  
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is  NULL 
	  AND tmpipaddress.id is  NULL
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
GROUP BY ipaddress,d, nofriends.name
ORDER BY nofriends.name, d
;
";



$queryTrafficByHoursLoginsOneSite="
SELECT tmp3.name,
	   tmp3.login, 
	   IFNULL(sum(tmp3.hr0),0) hr0_value, 
	   IFNULL(sum(tmp3.hr1),0) hr1_value,
	   IFNULL(sum(tmp3.hr2),0) hr2_value, 
	   IFNULL(sum(tmp3.hr3),0) hr3_value, 
	   IFNULL(sum(tmp3.hr4),0) hr4_value, 
	   IFNULL(sum(tmp3.hr5),0) hr5_value, 
	   IFNULL(sum(tmp3.hr6),0) hr6_value, 
	   IFNULL(sum(tmp3.hr7),0) hr7_value, 
	   IFNULL(sum(tmp3.hr8),0) hr8_value, 
	   IFNULL(sum(tmp3.hr9),0) hr9_value, 
	   IFNULL(sum(tmp3.hr10),0) hr10_value, 
	   IFNULL(sum(tmp3.hr11),0) hr11_value, 
	   IFNULL(sum(tmp3.hr12),0) hr12_value, 
	   IFNULL(sum(tmp3.hr13),0) hr13_value, 
	   IFNULL(sum(tmp3.hr14),0) hr14_value, 
	   IFNULL(sum(tmp3.hr15),0) hr15_value, 
	   IFNULL(sum(tmp3.hr16),0) hr16_value, 
	   IFNULL(sum(tmp3.hr17),0) hr17_value, 
	   IFNULL(sum(tmp3.hr18),0) hr18_value, 
	   IFNULL(sum(tmp3.hr19),0) hr19_value, 
	   IFNULL(sum(tmp3.hr20),0) hr20_value, 
	   IFNULL(sum(tmp3.hr21),0) hr21_value, 
	   IFNULL(sum(tmp3.hr22),0) hr22_value, 
	   IFNULL(sum(tmp3.hr23),0) hr23_value 

	   FROM (
SELECT 
	tmp2.login,
	tmp2.name,
	case when hrs.hr = 0 then  IFNULL(tmp2.sum_bytes,0) end hr0,
	case when hrs.hr = 1 then  IFNULL(tmp2.sum_bytes,0) end hr1,
	case when hrs.hr = 2 then  IFNULL(tmp2.sum_bytes,0) end hr2,
	case when hrs.hr = 3 then  IFNULL(tmp2.sum_bytes,0) end hr3,
	case when hrs.hr = 4 then  IFNULL(tmp2.sum_bytes,0) end hr4,
	case when hrs.hr = 5 then  IFNULL(tmp2.sum_bytes,0) end hr5,
	case when hrs.hr = 6 then  IFNULL(tmp2.sum_bytes,0) end hr6,
	case when hrs.hr = 7 then  IFNULL(tmp2.sum_bytes,0) end hr7,
	case when hrs.hr = 8 then  IFNULL(tmp2.sum_bytes,0) end hr8,
	case when hrs.hr = 9 then  IFNULL(tmp2.sum_bytes,0) end hr9,
	case when hrs.hr = 10 then  IFNULL(tmp2.sum_bytes,0) end hr10,
	case when hrs.hr = 11 then  IFNULL(tmp2.sum_bytes,0) end hr11,
	case when hrs.hr = 12 then  IFNULL(tmp2.sum_bytes,0) end hr12,
	case when hrs.hr = 13 then  IFNULL(tmp2.sum_bytes,0) end hr13,
	case when hrs.hr = 14 then  IFNULL(tmp2.sum_bytes,0) end hr14,
	case when hrs.hr = 15 then  IFNULL(tmp2.sum_bytes,0) end hr15,
	case when hrs.hr = 16 then  IFNULL(tmp2.sum_bytes,0) end hr16,
	case when hrs.hr = 17 then  IFNULL(tmp2.sum_bytes,0) end hr17,
	case when hrs.hr = 18 then  IFNULL(tmp2.sum_bytes,0) end hr18,
	case when hrs.hr = 19 then  IFNULL(tmp2.sum_bytes,0) end hr19,
	case when hrs.hr = 20 then  IFNULL(tmp2.sum_bytes,0) end hr20,
	case when hrs.hr = 21 then  IFNULL(tmp2.sum_bytes,0) end hr21,
	case when hrs.hr = 22 then  IFNULL(tmp2.sum_bytes,0) end hr22,
	case when hrs.hr = 23 then  IFNULL(tmp2.sum_bytes,0) end hr23

FROM (
SELECT  login,
nofriends.name,
sum(sizeinbytes) sum_bytes,
FROM_UNIXTIME(date,'%k') d
FROM scsq_quicktraffic
	LEFT JOIN (SELECT 
	id,
	name 
	FROM scsq_logins)
	AS nofriends 
	ON scsq_quicktraffic.login=nofriends.id  
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is  NULL 
	  AND tmpipaddress.id is  NULL
	  AND site='".$currentsite."'
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
GROUP BY login,d,nofriends.name
) tmp2

RIGHT JOIN (
  select 0 as hr, '0:00-1:00' as hr_txt 
  UNION all 
  select 1 as hr, '1:00-2:00' as hr_txt 
  UNION all 
  select 2 as hr, '2:00-3:00' as hr_txt 
  UNION all 
  select 3 as hr, '3:00-4:00' as hr_txt 
  UNION all 
  select 4 as hr, '4:00-5:00' as hr_txt 
  UNION all 
  select 5 as hr, '5:00-6:00' as hr_txt 
  UNION all 
  select 6 as hr, '6:00-7:00' as hr_txt 
  UNION all 
  select 7 as hr, '7:00-8:00' as hr_txt 
  UNION all 
  select 8 as hr, '8:00-9:00' as hr_txt 
  UNION all 
  select 9 as hr, '9:00-10:00' as hr_txt 
  UNION all 
  select 10 as hr, '10:00-11:00' as hr_txt 
  UNION all 
  select 11 as hr, '11:00-12:00' as hr_txt 
  UNION all 
  select 12 as hr, '12:00-13:00' as hr_txt 
  UNION all 
  select 13 as hr, '13:00-14:00' as hr_txt 
  UNION all 
  select 14 as hr, '14:00-15:00' as hr_txt 
  UNION all 
  select 15 as hr, '15:00-16:00' as hr_txt 
  UNION all 
  select 16 as hr, '16:00-17:00' as hr_txt 
  UNION all 
  select 17 as hr, '17:00-18:00' as hr_txt 
  UNION all 
  select 18 as hr, '18:00-19:00' as hr_txt 
  UNION all 
  select 19 as hr, '19:00-20:00' as hr_txt 
  UNION all 
  select 20 as hr, '20:00-21:00' as hr_txt 
  UNION all 
  select 21 as hr, '21:00-22:00' as hr_txt 
  UNION all 
  select 22 as hr, '22:00-23:00' as hr_txt 
  UNION all 
  select 23 as hr, '23:00-24:00' as hr_txt 
  
			   
			   ) hrs on hrs.hr=tmp2.d
			   
			   order by hrs.hr asc
) tmp3
WHERE tmp3.login is not null
GROUP BY tmp3.login, tmp3.name
ORDER BY tmp3.name asc
;
";

$queryTrafficByHoursIpaddressOneSite="
SELECT tmp3.name,
	   tmp3.ipaddress, 
	   IFNULL(sum(tmp3.hr0),0) hr0_value, 
	   IFNULL(sum(tmp3.hr1),0) hr1_value,
	   IFNULL(sum(tmp3.hr2),0) hr2_value, 
	   IFNULL(sum(tmp3.hr3),0) hr3_value, 
	   IFNULL(sum(tmp3.hr4),0) hr4_value, 
	   IFNULL(sum(tmp3.hr5),0) hr5_value, 
	   IFNULL(sum(tmp3.hr6),0) hr6_value, 
	   IFNULL(sum(tmp3.hr7),0) hr7_value, 
	   IFNULL(sum(tmp3.hr8),0) hr8_value, 
	   IFNULL(sum(tmp3.hr9),0) hr9_value, 
	   IFNULL(sum(tmp3.hr10),0) hr10_value, 
	   IFNULL(sum(tmp3.hr11),0) hr11_value, 
	   IFNULL(sum(tmp3.hr12),0) hr12_value, 
	   IFNULL(sum(tmp3.hr13),0) hr13_value, 
	   IFNULL(sum(tmp3.hr14),0) hr14_value, 
	   IFNULL(sum(tmp3.hr15),0) hr15_value, 
	   IFNULL(sum(tmp3.hr16),0) hr16_value, 
	   IFNULL(sum(tmp3.hr17),0) hr17_value, 
	   IFNULL(sum(tmp3.hr18),0) hr18_value, 
	   IFNULL(sum(tmp3.hr19),0) hr19_value, 
	   IFNULL(sum(tmp3.hr20),0) hr20_value, 
	   IFNULL(sum(tmp3.hr21),0) hr21_value, 
	   IFNULL(sum(tmp3.hr22),0) hr22_value, 
	   IFNULL(sum(tmp3.hr23),0) hr23_value 

	   FROM (
SELECT 
	tmp2.ipaddress,
	tmp2.name,
	case when hrs.hr = 0 then  IFNULL(tmp2.sum_bytes,0) end hr0,
	case when hrs.hr = 1 then  IFNULL(tmp2.sum_bytes,0) end hr1,
	case when hrs.hr = 2 then  IFNULL(tmp2.sum_bytes,0) end hr2,
	case when hrs.hr = 3 then  IFNULL(tmp2.sum_bytes,0) end hr3,
	case when hrs.hr = 4 then  IFNULL(tmp2.sum_bytes,0) end hr4,
	case when hrs.hr = 5 then  IFNULL(tmp2.sum_bytes,0) end hr5,
	case when hrs.hr = 6 then  IFNULL(tmp2.sum_bytes,0) end hr6,
	case when hrs.hr = 7 then  IFNULL(tmp2.sum_bytes,0) end hr7,
	case when hrs.hr = 8 then  IFNULL(tmp2.sum_bytes,0) end hr8,
	case when hrs.hr = 9 then  IFNULL(tmp2.sum_bytes,0) end hr9,
	case when hrs.hr = 10 then  IFNULL(tmp2.sum_bytes,0) end hr10,
	case when hrs.hr = 11 then  IFNULL(tmp2.sum_bytes,0) end hr11,
	case when hrs.hr = 12 then  IFNULL(tmp2.sum_bytes,0) end hr12,
	case when hrs.hr = 13 then  IFNULL(tmp2.sum_bytes,0) end hr13,
	case when hrs.hr = 14 then  IFNULL(tmp2.sum_bytes,0) end hr14,
	case when hrs.hr = 15 then  IFNULL(tmp2.sum_bytes,0) end hr15,
	case when hrs.hr = 16 then  IFNULL(tmp2.sum_bytes,0) end hr16,
	case when hrs.hr = 17 then  IFNULL(tmp2.sum_bytes,0) end hr17,
	case when hrs.hr = 18 then  IFNULL(tmp2.sum_bytes,0) end hr18,
	case when hrs.hr = 19 then  IFNULL(tmp2.sum_bytes,0) end hr19,
	case when hrs.hr = 20 then  IFNULL(tmp2.sum_bytes,0) end hr20,
	case when hrs.hr = 21 then  IFNULL(tmp2.sum_bytes,0) end hr21,
	case when hrs.hr = 22 then  IFNULL(tmp2.sum_bytes,0) end hr22,
	case when hrs.hr = 23 then  IFNULL(tmp2.sum_bytes,0) end hr23

FROM (

	SELECT  ipaddress,
		nofriends.name,
		sum(sizeinbytes) sum_bytes,
		FROM_UNIXTIME(date,'%k') d
	FROM scsq_quicktraffic
	LEFT JOIN (SELECT 
	id,
	name 
	FROM scsq_ipaddress)
	AS nofriends 
	ON scsq_quicktraffic.ipaddress=nofriends.id  
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_quicktraffic.login

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is  NULL 
	  AND tmpipaddress.id is  NULL
	  AND site='".$currentsite."'
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
GROUP BY ipaddress,d,nofriends.name
) tmp2

RIGHT JOIN (
  select 0 as hr, '0:00-1:00' as hr_txt 
  UNION all 
  select 1 as hr, '1:00-2:00' as hr_txt 
  UNION all 
  select 2 as hr, '2:00-3:00' as hr_txt 
  UNION all 
  select 3 as hr, '3:00-4:00' as hr_txt 
  UNION all 
  select 4 as hr, '4:00-5:00' as hr_txt 
  UNION all 
  select 5 as hr, '5:00-6:00' as hr_txt 
  UNION all 
  select 6 as hr, '6:00-7:00' as hr_txt 
  UNION all 
  select 7 as hr, '7:00-8:00' as hr_txt 
  UNION all 
  select 8 as hr, '8:00-9:00' as hr_txt 
  UNION all 
  select 9 as hr, '9:00-10:00' as hr_txt 
  UNION all 
  select 10 as hr, '10:00-11:00' as hr_txt 
  UNION all 
  select 11 as hr, '11:00-12:00' as hr_txt 
  UNION all 
  select 12 as hr, '12:00-13:00' as hr_txt 
  UNION all 
  select 13 as hr, '13:00-14:00' as hr_txt 
  UNION all 
  select 14 as hr, '14:00-15:00' as hr_txt 
  UNION all 
  select 15 as hr, '15:00-16:00' as hr_txt 
  UNION all 
  select 16 as hr, '16:00-17:00' as hr_txt 
  UNION all 
  select 17 as hr, '17:00-18:00' as hr_txt 
  UNION all 
  select 18 as hr, '18:00-19:00' as hr_txt 
  UNION all 
  select 19 as hr, '19:00-20:00' as hr_txt 
  UNION all 
  select 20 as hr, '20:00-21:00' as hr_txt 
  UNION all 
  select 21 as hr, '21:00-22:00' as hr_txt 
  UNION all 
  select 22 as hr, '22:00-23:00' as hr_txt 
  UNION all 
  select 23 as hr, '23:00-24:00' as hr_txt 
  
			   
			   ) hrs on hrs.hr=tmp2.d
			   
			   order by hrs.hr asc
) tmp3
WHERE tmp3.ipaddress is not null
GROUP BY tmp3.ipaddress, tmp3.name
ORDER BY tmp3.name asc
;
";


$queryCategorySitesTraffic="
  SELECT scsq_categorylist.category,
	 count(id),
	 tmp2.s
  
  FROM ((SELECT 
		 sum(sizeinbytes) as s,
		 date,
		 login,
		 ipaddress,
		 site
	       FROM scsq_quicktraffic
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_logins 
			   WHERE id  IN (".$goodLoginsList.")) 
			   AS tmplogin 
	       ON tmplogin.id=scsq_quicktraffic.login
	       LEFT  JOIN (SELECT 
			     id 
			   FROM scsq_ipaddress 
			   WHERE id  IN (".$goodIpaddressList.")) as tmpipaddress 
	       ON tmpipaddress.id=scsq_quicktraffic.ipaddress       	

	       WHERE date>".$datestart." 
	  	 AND date<".$dateend."
 		 AND par=1
		 AND tmplogin.id IS NULL 
	   	 AND tmpipaddress.id IS NULL
	 	 AND site NOT IN (".$goodSitesList.")
	       GROUP BY CRC32(site)
	       ORDER BY null
	      ) as tmp2
	
	 )
  LEFT OUTER JOIN scsq_categorylist ON tmp2.site=scsq_categorylist.site
  GROUP BY scsq_categorylist.category	  

;";





#mysql version
   $queryLoginsTimeOnline="
  SELECT 
    nofriends.name,
    '',
    count(1) cnt,
    round(count(1)/60,0)cntMin,
    nofriends.id
    ".$echoLoginAliasColumn."
  FROM (SELECT DISTINCT
          login,
          date
           
        FROM scsq_traffic 
        WHERE  date>".$datestart."
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")
	
	ORDER BY null) 
	AS tmp 

	LEFT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (".$goodLoginsList.")) 
		    AS nofriends 
	ON tmp.login=nofriends.id  
 	LEFT JOIN (SELECT 
		      name,
		      tableid		      
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 
  
  WHERE (1=1)
  

  GROUP BY nofriends.name,
		   nofriends.id
		   ".$echoLoginAliasColumn.";";


#mysql version
$queryIpaddressTimeOnline="
  SELECT 
	nofriends.name,
    '',
    count(1) cnt,
    round(count(1)/60,0)cntMin,
    nofriends.id
    ".$echoIpaddressAliasColumn."
  FROM (SELECT DISTINCT
	  ipaddress,
	  date 
	FROM scsq_traffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
	
	ORDER BY null) 
	AS tmp 
	LEFT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    WHERE id NOT IN (".$goodIpaddressList.")) AS nofriends 
	ON tmp.ipaddress=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=1) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 
	WHERE (1=1)


  GROUP BY nofriends.name,
    nofriends.id 
    ".$echoIpaddressAliasColumn."
   ;";
   
  #postgre version
if($dbtype==1)
  $queryIpaddressTimeOnline="
  SELECT 
    nofriends.name,
    tmp.s,
    nofriends.id 
    ".$echoIpaddressAliasColumn."
  FROM (SELECT 
	  ipaddress,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
	  AND par=1
	GROUP BY ipaddress
	) 
	AS tmp 
	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    WHERE id NOT IN (".$goodIpaddressList.")) AS nofriends 
	ON tmp.ipaddress=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=1) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 
WHERE (1=1)
  ".$msgNoZeroTraffic."

  GROUP BY nofriends.name,
    tmp.s,
    nofriends.id 
    ".$echoIpaddressAliasColumn."
   ;";




//===============================================
//**********************************************
//**********************************************
//**********************************************
//**********************************************

//PARTLY queries for login,ipaddress, http etc

//**********************************************
//**********************************************
//**********************************************
//**********************************************
//===============================================



$queryOneLoginTraffic="
	SELECT 
	   scsq_quicktraffic.site,
	   SUM(sizeinbytes) AS s
	   ".$category."
	 FROM scsq_quicktraffic
	 
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")   
	AND par=1
	 GROUP BY CRC32(scsq_quicktraffic.site) ,site ".$category."
	 ORDER BY site asc
;";

#postgre version
if($dbtype==1)
$queryOneLoginTraffic="
	SELECT 
	   scsq_quicktraffic.site,
	   SUM(sizeinbytes) AS s
	   ".$category."
	 FROM scsq_quicktraffic
	 
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")   
	AND par=1
	 GROUP BY scsq_quicktraffic.site ".$category."
	 ORDER BY site asc
;";

//echo $queryOneLoginTraffic;

$queryOneLoginTopSitesTraffic="
	 SELECT 
	   site, 
	   SUM( sizeinbytes ) AS s
	   ".$category."
	 FROM scsq_quicktraffic
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND site NOT IN (".$goodSitesList.")
	   AND par=1
	 GROUP BY CRC32(site), site ".$category."
	 ORDER BY s DESC
	 LIMIT ".$countTopSitesLimit." 
";

#postgre version
if($dbtype==1)
$queryOneLoginTopSitesTraffic="
	 SELECT 
	   site, 
	   SUM( sizeinbytes ) AS s
	   ".$category."
	 FROM scsq_quicktraffic
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND site NOT IN (".$goodSitesList.")
	   AND par=1
	 GROUP BY site ".$category."
	 ORDER BY s DESC
	 LIMIT ".$countTopSitesLimit." 
";

$queryOneLoginTrafficByHours="
SELECT
hrs.hr_txt,
tmp2.sum_bytes,
hrs.hr 
from (
  SELECT 
    FROM_UNIXTIME(tmp.date,'%H') AS d,
    SUM(tmp.s) sum_bytes
  FROM (SELECT 
	  date,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	
	WHERE login=".$currentloginid." 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
          AND site NOT IN (".$goodSitesList.")
	  AND par=1	
	GROUP BY CRC32(date) , date
	ORDER BY null) 
	AS tmp 

  GROUP BY d 
  ) tmp2

  RIGHT JOIN (
	select 0 as hr, '0:00-1:00' as hr_txt 
	UNION all 
	select 1 as hr, '1:00-2:00' as hr_txt 
	UNION all 
	select 2 as hr, '2:00-3:00' as hr_txt 
	UNION all 
	select 3 as hr, '3:00-4:00' as hr_txt 
	UNION all 
	select 4 as hr, '4:00-5:00' as hr_txt 
	UNION all 
	select 5 as hr, '5:00-6:00' as hr_txt 
	UNION all 
	select 6 as hr, '6:00-7:00' as hr_txt 
	UNION all 
	select 7 as hr, '7:00-8:00' as hr_txt 
	UNION all 
	select 8 as hr, '8:00-9:00' as hr_txt 
	UNION all 
	select 9 as hr, '9:00-10:00' as hr_txt 
	UNION all 
	select 10 as hr, '10:00-11:00' as hr_txt 
	UNION all 
	select 11 as hr, '11:00-12:00' as hr_txt 
	UNION all 
	select 12 as hr, '12:00-13:00' as hr_txt 
	UNION all 
	select 13 as hr, '13:00-14:00' as hr_txt 
	UNION all 
	select 14 as hr, '14:00-15:00' as hr_txt 
	UNION all 
	select 15 as hr, '15:00-16:00' as hr_txt 
	UNION all 
	select 16 as hr, '16:00-17:00' as hr_txt 
	UNION all 
	select 17 as hr, '17:00-18:00' as hr_txt 
	UNION all 
	select 18 as hr, '18:00-19:00' as hr_txt 
	UNION all 
	select 19 as hr, '19:00-20:00' as hr_txt 
	UNION all 
	select 20 as hr, '20:00-21:00' as hr_txt 
	UNION all 
	select 21 as hr, '21:00-22:00' as hr_txt 
	UNION all 
	select 22 as hr, '22:00-23:00' as hr_txt 
	UNION all 
	select 23 as hr, '23:00-24:00' as hr_txt 
	
				 
				 ) hrs on hrs.hr=tmp2.d
				 
				 order by hrs.hr asc 
 
  ";

  


 #postgre version
  if($dbtype==1)
  $queryOneLoginTrafficByHours="
  SELECT
	hrs.hr_txt,
	tmp2.sum_bytes,
	hrs.hr 
	from ( 
  SELECT 
    cast(to_char(to_timestamp(tmp.date),'HH24') as int) AS d,
    SUM(tmp.s) as sum_bytes 
  FROM (SELECT 
	  date,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	
	WHERE login=".$currentloginid." 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
          AND site NOT IN (".$goodSitesList.")
	  AND par=1	
	GROUP BY date 
	) 
	AS tmp 

  GROUP BY d 
  ) tmp2

  RIGHT JOIN (
	select 0 as hr, '0:00-1:00' as hr_txt 
	UNION all 
	select 1 as hr, '1:00-2:00' as hr_txt 
	UNION all 
	select 2 as hr, '2:00-3:00' as hr_txt 
	UNION all 
	select 3 as hr, '3:00-4:00' as hr_txt 
	UNION all 
	select 4 as hr, '4:00-5:00' as hr_txt 
	UNION all 
	select 5 as hr, '5:00-6:00' as hr_txt 
	UNION all 
	select 6 as hr, '6:00-7:00' as hr_txt 
	UNION all 
	select 7 as hr, '7:00-8:00' as hr_txt 
	UNION all 
	select 8 as hr, '8:00-9:00' as hr_txt 
	UNION all 
	select 9 as hr, '9:00-10:00' as hr_txt 
	UNION all 
	select 10 as hr, '10:00-11:00' as hr_txt 
	UNION all 
	select 11 as hr, '11:00-12:00' as hr_txt 
	UNION all 
	select 12 as hr, '12:00-13:00' as hr_txt 
	UNION all 
	select 13 as hr, '13:00-14:00' as hr_txt 
	UNION all 
	select 14 as hr, '14:00-15:00' as hr_txt 
	UNION all 
	select 15 as hr, '15:00-16:00' as hr_txt 
	UNION all 
	select 16 as hr, '16:00-17:00' as hr_txt 
	UNION all 
	select 17 as hr, '17:00-18:00' as hr_txt 
	UNION all 
	select 18 as hr, '18:00-19:00' as hr_txt 
	UNION all 
	select 19 as hr, '19:00-20:00' as hr_txt 
	UNION all 
	select 20 as hr, '20:00-21:00' as hr_txt 
	UNION all 
	select 21 as hr, '21:00-22:00' as hr_txt 
	UNION all 
	select 22 as hr, '22:00-23:00' as hr_txt 
	UNION all 
	select 23 as hr, '23:00-24:00' as hr_txt 
	
				 
				 ) hrs on hrs.hr=tmp2.d
				 
				 order by hrs.hr asc 
 
  ";

#костыль для правильного разбора сайтов (currentloginid 1 или 2)

$queryWhoVisitPopularSiteLogin="
  SELECT 
    nofriends.name, 
    tmp.s,
    tmp2.name,
    nofriends.id

  FROM (SELECT 
	  login,
	  SUM(sizeinbytes) AS s
	FROM scsq_traffic

	WHERE  
	   date>".$datestart." 
	  AND date<".$dateend."
	
	AND 	  (case
				when (".$currentloginid."=1) and (SUBSTRING_INDEX(site,'/',1)='".$currentsite."') then TRUE 
				when (".$currentloginid."=2) and (SUBSTRING_INDEX(SUBSTRING_INDEX(scsq_traffic.site,'/',1),'.',-2)) ='".$currentsite."' then TRUE
				else FALSE
			end ) = TRUE
	
	GROUP BY CRC32(login), login) 
	AS tmp 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (".$goodLoginsList.")) 
		    AS nofriends 
	ON tmp.login=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   tmp2 
	ON tmp2.tableid=nofriends.id
WHERE (1=1)
  ".$msgNoZeroTraffic."
  

  ORDER BY nofriends.name;";


#postgre version
if($dbtype==1)
$queryWhoVisitPopularSiteLogin="
  SELECT 
    nofriends.name, 
    tmp.s,
    tmp2.name,
    nofriends.id

  FROM (SELECT 
	  login,
	  SUM(sizeinbytes) AS s
	FROM scsq_traffic

	WHERE  
	   date>".$datestart." 
	  AND date<".$dateend."
	
	AND 	  (case
				when (".$currentloginid."=1) and (split_part(site,'/',1)='".$currentsite."') then TRUE 
				when (".$currentloginid."=2) and reverse(split_part(reverse(split_part(site,'/',1)),'.',1) ||'.'|| split_part(reverse(split_part(site,'/',1)),'.',2)) ='".$currentsite."' then TRUE
				else FALSE
			end ) = TRUE
	
	GROUP BY login) 
	AS tmp 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (".$goodLoginsList.")) 
		    AS nofriends 
	ON tmp.login=nofriends.id  
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   tmp2 
	ON tmp2.tableid=nofriends.id
WHERE (1=1)
  ".$msgNoZeroTraffic."
  

  ORDER BY nofriends.name;";

//echo $queryWhoVisitPopularSiteLogin;

$queryVisitingWebsiteByTimeLogin="
  SELECT DISTINCT 
	site,
	'0',
    FROM_UNIXTIME(tmp.date,'%d-%m-%Y %H:%i:%s') AS d
     
  FROM (SELECT 
	  date,
	  site 
	FROM scsq_traffic 
	WHERE login=".$currentloginid." 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
          AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
          AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
  
	ORDER BY null) 
	AS tmp  
  
  ORDER BY d asc;";
  
  
  #postgre version
  if($dbtype==1)
  $queryVisitingWebsiteByTimeLogin="
  SELECT DISTINCT 
    site,
    '0',
    to_char(to_timestamp(tmp.date),'DD-MM-YYYY HH24:MI:SS') AS d
   
  FROM (SELECT 
	  date,
	  site 
	FROM scsq_traffic 
	WHERE login=".$currentloginid." 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
          AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
          AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
  
	) 
	AS tmp  
  
  ORDER BY d asc;";

$queryOneLoginPopularSites="
  SELECT 
	  SUBSTRING_INDEX(site,'/',1) AS st,
      sum(sizeinbytes) AS s,
	  count(*) AS c

	  
	FROM scsq_traffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND login=".$currentloginid."
	  AND SUBSTRING_INDEX(site,'/',1) NOT IN (".$goodSitesList.")
	GROUP BY st
	
  ORDER BY c desc 
  LIMIT ".$countPopularSitesLimit.";";


  #postgre version
  if($dbtype==1)  
  $queryOneLoginPopularSites="
  SELECT 
	  split_part(site,'/',1) AS st,
	  sum(sizeinbytes) AS s,
	  count(*) AS c
	FROM scsq_traffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND login=".$currentloginid."
	  AND split_part(site,'/',1) NOT IN (".$goodSitesList.")

  GROUP BY st

  ORDER BY c desc 
  LIMIT ".$countPopularSitesLimit.";";


///IP адреса на одном логине
$queryOneLoginIpTraffic="
  SELECT 
    nofriends.name,
    sum(sizeinbytes),
    ipaddress,
    tmp2.name 
  FROM scsq_quicktraffic

  RIGHT JOIN (SELECT 
		id,
		name 
	      FROM scsq_ipaddress 
	      WHERE id NOT IN (".$goodIpaddressList.")) 
	      AS nofriends 
  ON scsq_quicktraffic.ipaddress=nofriends.id 

  LEFT JOIN (SELECT 
		name,
		tableid 
	     FROM scsq_alias 
	     WHERE typeid=1) 
	     tmp2 
  ON nofriends.id=tmp2.tableid 

 where login=".$currentloginid." 
   and date>".$datestart." 
   and date<".$dateend." 
   AND site NOT IN (".$goodSitesList.")
   AND par=1
  GROUP BY CRC32(ipaddress), ipaddress";
  
  # postgre version
  if($dbtype==1)
  $queryOneLoginIpTraffic="
  SELECT 
    nofriends.name,
    sum(sizeinbytes),
    ipaddress,
    tmp2.name 
  FROM scsq_quicktraffic

  RIGHT JOIN (SELECT 
		id,
		name 
	      FROM scsq_ipaddress 
	      WHERE id NOT IN (".$goodIpaddressList.")) 
	      AS nofriends 
  ON scsq_quicktraffic.ipaddress=nofriends.id 

  LEFT JOIN (SELECT 
		name,
		tableid 
	     FROM scsq_alias 
	     WHERE typeid=1) 
	     tmp2 
  ON nofriends.id=tmp2.tableid 

 where login=".$currentloginid." 
   and date>".$datestart." 
   and date<".$dateend." 
   AND site NOT IN (".$goodSitesList.")
   AND par=1
  GROUP BY ipaddress, nofriends.name, tmp2.name ";

//по типу контента
$queryOneLoginMimeTypesTraffic="
	SELECT 
	   mime,
	   SUM(sizeinbytes) AS s 
	 FROM scsq_traffic 
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
	   AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
  GROUP BY mime
  ORDER BY s desc ";



#postgre version
if($dbtype==1)
$queryOneLoginMimeTypesTraffic="
	SELECT 
	   mime,
	   SUM(sizeinbytes) AS s 
	 FROM scsq_traffic 
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
       AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
  GROUP BY mime
  ORDER BY s desc ";


$queryLoginDownloadBigFiles="
  SELECT 
    scsq_log.name,
    tmp.sizeinbytes,
    scsq_ip.name, 
    scsq_traf.site,
    scsq_log.id,
    scsq_ip.id 
  FROM (SELECT 
	  sizeinbytes,
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
	  AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
	  AND login=".$currentloginid." 
  	)


	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
  ORDER BY sizeinbytes desc 
  	LIMIT ".$countWhoDownloadBigFilesLimit."
";

#postgre version
if($dbtype==1)
$queryLoginDownloadBigFiles="
  SELECT 
    scsq_log.name,
    tmp.sizeinbytes,
    scsq_ip.name, 
    scsq_traf.site,
    scsq_log.id,
    scsq_ip.id 
  FROM (SELECT 
	  sizeinbytes,
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic
	
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
	  AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
	  AND login=".$currentloginid." 
  	)


	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
  ORDER BY sizeinbytes desc 
  	LIMIT ".$countWhoDownloadBigFilesLimit."
";

$queryOneIpaddressTraffic="
	 SELECT 
	   scsq_quicktraffic.site AS st,
	   sum(sizeinbytes) AS s
	   ".$category."
	 FROM scsq_quicktraffic 
	 WHERE ipaddress=".$currentipaddressid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	   AND par=1
	   
	 GROUP BY CRC32(scsq_quicktraffic.site),site ".$category."	 
	 ORDER BY scsq_quicktraffic.site asc;";

#postgre version
if($dbtype==1)
$queryOneIpaddressTraffic="
	 SELECT 
	   scsq_quicktraffic.site AS st,
	   sum(sizeinbytes) AS s
	   ".$category."
	   
	 FROM scsq_quicktraffic 
	 WHERE ipaddress=".$currentipaddressid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	   AND par=1
	   
	 GROUP BY scsq_quicktraffic.site ".$category."	 
	 ORDER BY scsq_quicktraffic.site asc;";


$queryOneIpaddressTopSitesTraffic="
	 SELECT 
	   site,
	   SUM(sizeinbytes) as s
	   ".$category."
	 FROM scsq_quicktraffic 
	 WHERE ipaddress=".$currentipaddressid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")	 
	   AND par=1
	 GROUP BY CRC32(site) ,site ".$category."
	 ORDER BY s desc 
	 LIMIT ".$countTopSitesLimit." ";

#postgre version
if($dbtype==1)
$queryOneIpaddressTopSitesTraffic="
	 SELECT 
	   site,
	   SUM(sizeinbytes) as s
	   ".$category."
	 FROM scsq_quicktraffic 
	 WHERE ipaddress=".$currentipaddressid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")	 
	   AND par=1
	 GROUP BY site ".$category."
	 ORDER BY s desc 
	 LIMIT ".$countTopSitesLimit." ";

$queryOneIpaddressTrafficByHours="
SELECT hrs.hr_txt,
		tmp2.sum_bytes,
		hrs.hr 
		FROM (
  SELECT 
    from_unixtime(tmp.date,'%H') as d,
    sum(tmp.s) sum_bytes
  from (SELECT 
	  date,
	  sum(sizeinbytes) as s 
	from scsq_quicktraffic 
	where ipaddress=".$currentipaddressid." 
	  and date>".$datestart." 
	  and date<".$dateend." 
          AND site NOT IN (".$goodSitesList.")
	  AND par=1
	group by crc32(date),date 
	order by null) 
	as tmp 

  group by d 
  ) tmp2

  RIGHT JOIN (
	select 0 as hr, '0:00-1:00' as hr_txt 
	UNION all 
	select 1 as hr, '1:00-2:00' as hr_txt 
	UNION all 
	select 2 as hr, '2:00-3:00' as hr_txt 
	UNION all 
	select 3 as hr, '3:00-4:00' as hr_txt 
	UNION all 
	select 4 as hr, '4:00-5:00' as hr_txt 
	UNION all 
	select 5 as hr, '5:00-6:00' as hr_txt 
	UNION all 
	select 6 as hr, '6:00-7:00' as hr_txt 
	UNION all 
	select 7 as hr, '7:00-8:00' as hr_txt 
	UNION all 
	select 8 as hr, '8:00-9:00' as hr_txt 
	UNION all 
	select 9 as hr, '9:00-10:00' as hr_txt 
	UNION all 
	select 10 as hr, '10:00-11:00' as hr_txt 
	UNION all 
	select 11 as hr, '11:00-12:00' as hr_txt 
	UNION all 
	select 12 as hr, '12:00-13:00' as hr_txt 
	UNION all 
	select 13 as hr, '13:00-14:00' as hr_txt 
	UNION all 
	select 14 as hr, '14:00-15:00' as hr_txt 
	UNION all 
	select 15 as hr, '15:00-16:00' as hr_txt 
	UNION all 
	select 16 as hr, '16:00-17:00' as hr_txt 
	UNION all 
	select 17 as hr, '17:00-18:00' as hr_txt 
	UNION all 
	select 18 as hr, '18:00-19:00' as hr_txt 
	UNION all 
	select 19 as hr, '19:00-20:00' as hr_txt 
	UNION all 
	select 20 as hr, '20:00-21:00' as hr_txt 
	UNION all 
	select 21 as hr, '21:00-22:00' as hr_txt 
	UNION all 
	select 22 as hr, '22:00-23:00' as hr_txt 
	UNION all 
	select 23 as hr, '23:00-24:00' as hr_txt 
	
				 
				 ) hrs on hrs.hr=tmp2.d
				 
				 order by hrs.hr asc 
 
  ";


  #postgre version
  if($dbtype==1)
  $queryOneIpaddressTrafficByHours="
  SELECT 
	hrs.hr_txt,
	tmp2.sum_bytes,
	hrs.hr 
  FROM ( 
  SELECT 
    cast(to_char(to_timestamp(tmp.date),'HH24') as int) as d,
    sum(tmp.s) sum_bytes
  from (SELECT 
			date,
			sum(sizeinbytes) as s 
	from scsq_quicktraffic 
	where ipaddress=".$currentipaddressid." 
	  and date>".$datestart." 
	  and date<".$dateend." 
          AND site NOT IN (".$goodSitesList.")
	  AND par=1
	group by date 
	) 
	as tmp 

  group by d 
  ) tmp2

  RIGHT JOIN (
	select 0 as hr, '0:00-1:00' as hr_txt 
	UNION all 
	select 1 as hr, '1:00-2:00' as hr_txt 
	UNION all 
	select 2 as hr, '2:00-3:00' as hr_txt 
	UNION all 
	select 3 as hr, '3:00-4:00' as hr_txt 
	UNION all 
	select 4 as hr, '4:00-5:00' as hr_txt 
	UNION all 
	select 5 as hr, '5:00-6:00' as hr_txt 
	UNION all 
	select 6 as hr, '6:00-7:00' as hr_txt 
	UNION all 
	select 7 as hr, '7:00-8:00' as hr_txt 
	UNION all 
	select 8 as hr, '8:00-9:00' as hr_txt 
	UNION all 
	select 9 as hr, '9:00-10:00' as hr_txt 
	UNION all 
	select 10 as hr, '10:00-11:00' as hr_txt 
	UNION all 
	select 11 as hr, '11:00-12:00' as hr_txt 
	UNION all 
	select 12 as hr, '12:00-13:00' as hr_txt 
	UNION all 
	select 13 as hr, '13:00-14:00' as hr_txt 
	UNION all 
	select 14 as hr, '14:00-15:00' as hr_txt 
	UNION all 
	select 15 as hr, '15:00-16:00' as hr_txt 
	UNION all 
	select 16 as hr, '16:00-17:00' as hr_txt 
	UNION all 
	select 17 as hr, '17:00-18:00' as hr_txt 
	UNION all 
	select 18 as hr, '18:00-19:00' as hr_txt 
	UNION all 
	select 19 as hr, '19:00-20:00' as hr_txt 
	UNION all 
	select 20 as hr, '20:00-21:00' as hr_txt 
	UNION all 
	select 21 as hr, '21:00-22:00' as hr_txt 
	UNION all 
	select 22 as hr, '22:00-23:00' as hr_txt 
	UNION all 
	select 23 as hr, '23:00-24:00' as hr_txt 
	
				 
				 ) hrs on hrs.hr=tmp2.d
				 
				 order by hrs.hr asc 
 
  ";


#костыль для правильного разбора сайтов (currentipaddressid 1 или 2)

$queryWhoVisitPopularSiteIpaddress="
  SELECT 
    nofriends.name, 
    tmp.s,
	nofriends.id,
	tmp2.name

  FROM (SELECT 
	  ipaddress,
	  SUM(sizeinbytes) AS s
	FROM scsq_traffic

	WHERE  
	   date>".$datestart." 
	  AND date<".$dateend."
	
	AND 	  (case
				when (".$currentipaddressid."=1) and (SUBSTRING_INDEX(site,'/',1)='".$currentsite."') then TRUE 
				when (".$currentipaddressid."=2) and (SUBSTRING_INDEX(SUBSTRING_INDEX(scsq_traffic.site,'/',1),'.',-2)) ='".$currentsite."' then TRUE
				else FALSE
			end ) = TRUE
	
	GROUP BY CRC32(ipaddress),ipaddress) 
	AS tmp 

	RIGHT JOIN (SELECT 
					id,
					name 
				FROM scsq_ipaddress 
				where id NOT IN (".$goodIpaddressList.")) as nofriends 
	ON tmp.ipaddress=nofriends.id 
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=1) 
		   tmp2 
	ON tmp2.tableid=nofriends.id
WHERE (1=1)
  ".$msgNoZeroTraffic."
  

  ORDER BY nofriends.name;";


#postgre version
if($dbtype==1)
$queryWhoVisitPopularSiteIpaddress="
  SELECT 
    nofriends.name, 
    tmp.s,
    nofriends.id,
	tmp2.name
  FROM (SELECT 
	  ipaddress,
	  SUM(sizeinbytes) AS s
	FROM scsq_traffic

	WHERE  
	   date>".$datestart." 
	  AND date<".$dateend."
	
	AND 	  (case
				when (".$currentipaddressid."=1) and (split_part(site,'/',1)='".$currentsite."') then TRUE 
				when (".$currentipaddressid."=2) and reverse(split_part(reverse(split_part(site,'/',1)),'.',1) ||'.'|| split_part(reverse(split_part(site,'/',1)),'.',2)) ='".$currentsite."' then TRUE
				else FALSE
			end ) = TRUE
	
	GROUP BY ipaddress) 
	AS tmp 

	RIGHT JOIN (SELECT 
					id,
					name 
				FROM scsq_ipaddress 
				where id NOT IN (".$goodIpaddressList.")) as nofriends 
	ON tmp.ipaddress=nofriends.id 
	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=1) 
		   tmp2 
	ON tmp2.tableid=nofriends.id
WHERE (1=1)
  ".$msgNoZeroTraffic."
  

  ORDER BY nofriends.name;";


$queryIpaddressDownloadBigFiles="
  SELECT 
    scsq_log.name,
    scsq_traf.sizeinbytes,
    scsq_ip.name, 
    scsq_traf.site,
    scsq_log.id,
    scsq_ip.id 
  FROM (SELECT 
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
	  AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
	  AND ipaddress=".$currentipaddressid." 
  )


	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
  	ORDER BY sizeinbytes desc 
  	LIMIT ".$countWhoDownloadBigFilesLimit."
";

#postgre version
if($dbtype==1)
$queryIpaddressDownloadBigFiles="
  SELECT 
    scsq_log.name,
    scsq_traf.sizeinbytes,
    scsq_ip.name, 
    scsq_traf.site,
    scsq_log.id,
    scsq_ip.id 
  FROM (SELECT 
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic
	
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
	  AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
	  AND ipaddress=".$currentipaddressid." 
  )


	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
  	ORDER BY sizeinbytes desc 
  	LIMIT ".$countWhoDownloadBigFilesLimit."
";


#костылище для частных отчетов

$queryOneLoginOneHourTraffic="
	 SELECT 
	   site,
	   SUM(sizeinbytes) AS s 
	 FROM scsq_traffic 
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")
	   AND FROM_UNIXTIME(date,'%k')>=".$currenthour."
	   AND FROM_UNIXTIME(date,'%k')<".($currenthour+1)."
	 GROUP BY CRC32(site) 
  	 ORDER BY site asc;";
  	 
  	 #postgre version
  	 if($dbtype==1)
$queryOneLoginOneHourTraffic="
	 SELECT 
	   site,
	   SUM(sizeinbytes) AS s 
	 FROM scsq_traffic 
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")
	   AND cast(to_char(to_timestamp(date),'HH24') as int)>=".$currenthour."
	   AND cast(to_char(to_timestamp(date),'HH24') as int)<".($currenthour+1)."
	 GROUP BY site 
  	 ORDER BY site asc;";

$queryOneIpaddressOneHourTraffic="
 	 SELECT 
	   site,
	   sum(sizeinbytes) AS s 
	 FROM scsq_quicktraffic 
	 WHERE ipaddress=".$currentipaddressid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")
	   AND FROM_UNIXTIME(date,'%k')>=".$currenthour."
	   AND FROM_UNIXTIME(date,'%k')<".($currenthour+1)."
 	 GROUP BY CRC32(site) 
	 ORDER BY st asc ";


#postgre version
if($dbtype==1)
$queryOneIpaddressOneHourTraffic="
 	 SELECT 
	   site,
	   sum(sizeinbytes) AS s 
	 FROM scsq_quicktraffic 
	 WHERE ipaddress=".$currentipaddressid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")
	   AND cast(to_char(to_timestamp(date),'HH24') as int)>=".$currenthour."
	   AND cast(to_char(to_timestamp(date),'HH24') as int)<".($currenthour+1)."
 	 GROUP BY site 
	 ORDER BY st asc ";

$queryVisitingWebsiteByTimeIpaddress="
  SELECT DISTINCT 
    site,
    '0',
    from_unixtime(tmp.date,'%d-%m-%Y %H:%i:%s') as d
     
  from (SELECT 
	  date,
	  site 
	from scsq_traffic 
	where ipaddress=".$currentipaddressid." 
	  and date>".$datestart." 
	  and date<".$dateend." 
          AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
          AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
	
	order by null) 
	as tmp  
  
  order by d asc;";
  
  #postgre version
  if($dbtype==1)
  $queryVisitingWebsiteByTimeIpaddress="
  SELECT DISTINCT 
     site ,
     '0',
	 to_char(to_timestamp(tmp.date),'DD-MM-YYYY HH24:MI:SS') as d
    
  from (SELECT 
	  date,
	  site 
	from scsq_traffic 
	where ipaddress=".$currentipaddressid." 
	  and date>".$datestart." 
	  and date<".$dateend." 
      AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
      AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
	
	) 
	as tmp  
  
  order by d asc;";

$queryOneIpaddressPopularSites="
	SELECT 
	  SUBSTRING_INDEX(site,'/',1) AS st,
	  sum(sizeinbytes) AS s,
	  count(*) AS c

	FROM scsq_traffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND ipaddress=".$currentipaddressid."
	  AND SUBSTRING_INDEX(scsq_traffic.site,'/',1) NOT IN (".$goodSitesList.")

	GROUP BY st
  ORDER BY c desc 
  LIMIT ".$countPopularSitesLimit.";";


#postgre version
if($dbtype==1)
$queryOneIpaddressPopularSites="
  SELECT 
	  split_part(site,'/',1) AS st,
	  sum(sizeinbytes) AS s,
	  count(*) AS c
	  
	FROM scsq_traffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND ipaddress=".$currentipaddressid."
	  AND split_part(site,'/',1) NOT IN (".$goodSitesList.")
    
	GROUP BY st 
  
  ORDER BY c desc 
  LIMIT ".$countPopularSitesLimit.";";


$queryLoginsHttpStatus="
  SELECT 
    nofriends.name,
    '',
    count(*),
    login,
    tmp2.name 
  from scsq_quicktraffic 

  RIGHT JOIN (SELECT 
		id,
		name 
	      from scsq_logins 
	      WHERE id NOT IN (".$goodLoginsList.")) 
	      AS nofriends 
  ON scsq_quicktraffic.login=nofriends.id  

  LEFT JOIN (SELECT 
	       name,
	       tableid 
	     FROM scsq_alias 
	     where typeid=0) 
	     tmp2 
  ON nofriends.id=tmp2.tableid 

  where httpstatus='".$currenthttpstatusid."' 
    and date>".$datestart." 
    and date<".$dateend." 
    AND site NOT IN (".$goodSitesList.")
    AND par=1
  group by crc32(nofriends.name) 
  order by nofriends.name asc;";
  
  
#postgre version
if($dbtype==1)  
  $queryLoginsHttpStatus="
  SELECT 
    nofriends.name,
    '',
    count(*),
    login,
    tmp2.name 
  from scsq_quicktraffic 

  RIGHT JOIN (SELECT 
		id,
		name 
	      from scsq_logins 
	      WHERE id NOT IN (".$goodLoginsList.")) 
	      AS nofriends 
  ON scsq_quicktraffic.login=nofriends.id  

  LEFT JOIN (SELECT 
	       name,
	       tableid 
	     FROM scsq_alias 
	     where typeid=0) 
	     tmp2 
  ON nofriends.id=tmp2.tableid 

  where httpstatus='".$currenthttpstatusid."' 
    and date>".$datestart." 
    and date<".$dateend." 
    AND site NOT IN (".$goodSitesList.")
    AND par=1
  group by nofriends.name,login,tmp2.name  
  order by nofriends.name asc;";
  

$queryIpaddressHttpStatus="
  SELECT 
    nofriends.name,
    '',
    count(*),
    ipaddress,
    tmp2.name 
  from scsq_quicktraffic 

  RIGHT JOIN (SELECT 
		id,
		name 
	      FROM scsq_ipaddress 
	      where id NOT IN (".$goodIpaddressList.")) 
	      AS nofriends 
  ON scsq_quicktraffic.ipaddress=nofriends.id 

  LEFT JOIN  (SELECT 
		name,
		tableid 
	      FROM scsq_alias 
	      where typeid=1) 
	      tmp2 
  ON nofriends.id=tmp2.tableid 

  where httpstatus='".$currenthttpstatusid."' 
    and date>".$datestart." 
    and date<".$dateend." 
    AND site NOT IN (".$goodSitesList.")
    AND par=1
  GROUP BY crc32(nofriends.name)
  ORDER BY nofriends.name asc;";
  
  #postgre version
  $queryIpaddressHttpStatus="
  SELECT 
    nofriends.name,
    '',
    count(*),
    ipaddress,
    tmp2.name 
  from scsq_quicktraffic 

  RIGHT JOIN (SELECT 
		id,
		name 
	      FROM scsq_ipaddress 
	      where id NOT IN (".$goodIpaddressList.")) 
	      AS nofriends 
  ON scsq_quicktraffic.ipaddress=nofriends.id 

  LEFT JOIN  (SELECT 
		name,
		tableid 
	      FROM scsq_alias 
	      where typeid=1) 
	      tmp2 
  ON nofriends.id=tmp2.tableid 

  where httpstatus='".$currenthttpstatusid."' 
    and date>".$datestart." 
    and date<".$dateend." 
    AND site NOT IN (".$goodSitesList.")
    AND par=1
  GROUP BY nofriends.name, ipaddress, tmp2.name 
  ORDER BY nofriends.name asc;";

$queryOneLoginOneHttpStatus="
  SELECT 
    from_unixtime(date,'%d-%m-%Y %H:%i:%s') as d, 
    scsq_traffic.site 
  FROM scsq_traffic 

  LEFT JOIN scsq_logins ON scsq_logins.id=scsq_traffic.login 

  WHERE login='".$currentloiid."' 
    and httpstatus='".$currenthttpstatusid."' 
    and date>".$datestart." 
    and date<".$dateend." 
    AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
    AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
  
  order by date asc;";
  
  #postgre version
  if($dbtype==1)
  $queryOneLoginOneHttpStatus="
  SELECT 
    to_char(to_timestamp(date),'DD-MM-YYYY HH24:MI:SS') as d, 
    scsq_traffic.site 
  FROM scsq_traffic 

  LEFT JOIN scsq_logins ON scsq_logins.id=scsq_traffic.login 

  WHERE login='".$currentloiid."' 
    and httpstatus='".$currenthttpstatusid."' 
    and date>".$datestart." 
    and date<".$dateend." 
    AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
    AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
  
  order by date asc;";

$queryOneIpaddressOneHttpStatus="
  SELECT 
    from_unixtime(date,'%d-%m-%Y %H:%i:%s') as d, 
    scsq_traffic.site 
  FROM scsq_traffic 

  LEFT JOIN scsq_ipaddress ON scsq_ipaddress.id=scsq_traffic.ipaddress 

  WHERE ipaddress='".$currentloiid."' 
    and httpstatus='".$currenthttpstatusid."' 
    and date>".$datestart." 
    and date<".$dateend." 
    AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
    AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")

  ORDER BY date asc;";
  
  #postgre version
  if($dbtype==1)
  $queryOneIpaddressOneHttpStatus="
  SELECT 
    to_char(to_timestamp(date),'DD-MM-YYYY HH24:MI:SS') as d,  
    scsq_traffic.site 
  FROM scsq_traffic 

  LEFT JOIN scsq_ipaddress ON scsq_ipaddress.id=scsq_traffic.ipaddress 

  WHERE ipaddress='".$currentloiid."' 
    and httpstatus='".$currenthttpstatusid."' 
    and date>".$datestart." 
    and date<".$dateend." 
   AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
    AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
 
  ORDER BY date asc;";



///Логины на одном IP адресе
$queryOneIpaddressLoginsTraffic="
  SELECT 
    nofriends.name,
    SUM(sizeinbytes),
    login,
    tmp2.name 
  from  scsq_quicktraffic

  RIGHT JOIN (SELECT 
		id,
		name 
	      FROM scsq_logins 
	      where id NOT IN (".$goodLoginsList.")) 
	      AS nofriends 
  ON scsq_quicktraffic.login=nofriends.id 

  LEFT JOIN (SELECT 
		name,
		tableid 
	     from scsq_alias 
	     where typeid=0) 
	     tmp2 
  ON nofriends.id=tmp2.tableid 


  WHERE ipaddress=".$currentipaddressid." 
    and date>".$datestart." 
    and date<".$dateend." 
    AND site NOT IN (".$goodSitesList.")
    AND par=1
  GROUP BY CRC32(login),login";


#postgre version
if($dbtype==1)
$queryOneIpaddressLoginsTraffic="
  SELECT 
    nofriends.name,
    SUM(sizeinbytes),
    login,
    tmp2.name 
  from  scsq_quicktraffic

  RIGHT JOIN (SELECT 
		id,
		name 
	      FROM scsq_logins 
	      where id NOT IN (".$goodLoginsList.")) 
	      AS nofriends 
  ON scsq_quicktraffic.login=nofriends.id 

  LEFT JOIN (SELECT 
		name,
		tableid 
	     from scsq_alias 
	     where typeid=0) 
	     tmp2 
  ON nofriends.id=tmp2.tableid 


  WHERE ipaddress=".$currentipaddressid." 
    and date>".$datestart." 
    and date<".$dateend." 
    AND site NOT IN (".$goodSitesList.")
    AND par=1
  GROUP BY login, nofriends.name, tmp2.name";


$queryOneIpaddressMimeTypesTraffic="
	SELECT 
	   mime,
	   SUM(sizeinbytes) AS s 
	 FROM scsq_traffic 
	 WHERE ipaddress=".$currentipaddressid."  
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
	   AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
  GROUP BY mime
  ORDER BY s desc ";
  
  #postgre version
  if($dbtype==1)
  $queryOneIpaddressMimeTypesTraffic="
	SELECT 
	   mime,
	   SUM(sizeinbytes) AS s 
	 FROM scsq_traffic 
	 WHERE ipaddress=".$currentipaddressid."  
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
       AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
  GROUP BY mime
  ORDER BY s desc ";

$queryOneMime="
  SELECT 
    scsq_log.name,
    tmp.sizeinbytes,
    scsq_ip.name, 
    scsq_traf.site,
    scsq_log.id,
    scsq_ip.id 
  FROM (SELECT 
	  sizeinbytes,
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic
	
	LEFT OUTER JOIN (SELECT 
			   scsq_logins.id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   scsq_ipaddress.id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress


	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
	  AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
	  AND mime='".$currentmime."'
  	ORDER BY sizeinbytes desc 
  	)
	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
";

#postgre version
if($dbtype==1)
$queryOneMime="
  SELECT 
    scsq_log.name,
    tmp.sizeinbytes,
    scsq_ip.name, 
    scsq_traf.site,
    scsq_log.id,
    scsq_ip.id 
  FROM (SELECT 
	  sizeinbytes,
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic
	
	LEFT OUTER JOIN (SELECT 
			   scsq_logins.id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   scsq_ipaddress.id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress


	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
      AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
	  AND mime='".$currentmime."'
  	ORDER BY sizeinbytes desc 
  	)
	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
";

$queryOneMimeOneLogin="
  SELECT 
    scsq_traf.site,
    tmp.sizeinbytes
  FROM (SELECT 
	  sizeinbytes,
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic
	
	LEFT OUTER JOIN (SELECT 
			   scsq_logins.id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   scsq_ipaddress.id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress


	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
	  AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
	  AND mime='".$currentmime."'
	  AND login=".$currentloginid."
  	ORDER BY sizeinbytes desc 
  	)
	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
";

#postgre version
$queryOneMimeOneLogin="
  SELECT 
    scsq_traf.site,
    tmp.sizeinbytes
  FROM (SELECT 
	  sizeinbytes,
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic
	
	LEFT OUTER JOIN (SELECT 
			   scsq_logins.id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   scsq_ipaddress.id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress


	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
      AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
	  AND mime='".$currentmime."'
	  AND login=".$currentloginid."
  	ORDER BY sizeinbytes desc 
  	)
	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
";

$queryOneMimeOneIpaddress="
  SELECT 
    scsq_traf.site,
    tmp.sizeinbytes
  FROM (SELECT 
	  sizeinbytes,
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic
	
	LEFT OUTER JOIN (SELECT 
			   scsq_logins.id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   scsq_ipaddress.id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress


	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
	  AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
	  AND mime='".$currentmime."'
	  AND ipaddress=".$currentipaddressid."
  	ORDER BY sizeinbytes desc 
  	)
	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
";

#postgre version
$queryOneMimeOneIpaddress="
  SELECT 
    scsq_traf.site,
    tmp.sizeinbytes
  FROM (SELECT 
	  sizeinbytes,
	  scsq_traffic.id,
	  scsq_traffic.login,
	  scsq_traffic.ipaddress 
	FROM scsq_traffic
	
	LEFT OUTER JOIN (SELECT 
			   scsq_logins.id,
			   name 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   scsq_ipaddress.id,
			   name 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress


	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL 
	  AND tmpipaddress.id IS NULL
	  AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
      AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
	  AND mime='".$currentmime."'
	  AND ipaddress=".$currentipaddressid."
  	ORDER BY sizeinbytes desc 
  	)
	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
";

//partly queries end

//************************************
//************************************
//querys for group reports
//************************************
//************************************
//************************************

$queryGroupsTraffic="
  SELECT
    gname,
    s1,
    gid,
    gtypeid
  FROM ((SELECT 
	  tmp.name as gname,
	  sum(tmp2.s) AS s1,
 	  tmp.id as gid,
     	  tmp.typeid as gtypeid 
	FROM (SELECT 
		id,
		name,
		typeid 
	      FROM scsq_groups 
	      WHERE typeid=0) 
	      AS tmp 

	 LEFT JOIN scsq_aliasingroups ON tmp.id=scsq_aliasingroups.groupid 
	 LEFT JOIN scsq_alias on scsq_alias.id=scsq_aliasingroups.aliasid 

	 LEFT JOIN (SELECT 
		      sum(sizeinbytes) as s,
		      login 
		    from scsq_quicktraffic 
		    where scsq_quicktraffic.date>".$datestart." 
		      and scsq_quicktraffic.date<".$dateend." 
         	      AND site NOT IN (".$goodSitesList.")

		    GROUP BY login) 
		    tmp2 
	 ON tmp2.login=scsq_alias.tableid 

   GROUP BY crc32(tmp.name),tmp.name, tmp.id, tmp.typeid)

 UNION

  (SELECT 
     tmp.name as gname,
     sum(tmp2.s) as s1,
     tmp.id as gid,
     tmp.typeid as gtypeid 
   FROM (SELECT 
	   id,
	   name,
	   typeid 
	 FROM scsq_groups 
	 where typeid=1) 
	 AS tmp 

	 LEFT JOIN scsq_aliasingroups ON tmp.id=scsq_aliasingroups.groupid 
	 LEFT JOIN scsq_alias ON scsq_alias.id=scsq_aliasingroups.aliasid 

	 LEFT JOIN (SELECT 
		      sum(sizeinbytes) as s,
		      ipaddress 
		    FROM scsq_quicktraffic 
		    WHERE scsq_quicktraffic.date>".$datestart." 
		      and scsq_quicktraffic.date<".$dateend." 
        	      AND site NOT IN (".$goodSitesList.")

		    GROUP BY ipaddress) 
		    tmp2 
	 ON tmp2.ipaddress=scsq_alias.tableid 

  GROUP BY crc32(tmp.name),tmp.name, tmp.id, tmp.typeid)) as grtable
  order by gname;";


#postgre version
if($dbtype==1)
$queryGroupsTraffic="
  SELECT
    gname,
    s1,
    gid,
    gtypeid
  FROM ((SELECT 
	  tmp.name as gname,
	  sum(tmp2.s) AS s1,
 	  tmp.id as gid,
     	  tmp.typeid as gtypeid 
	FROM (SELECT 
		id,
		name,
		typeid 
	      FROM scsq_groups 
	      WHERE typeid=0) 
	      AS tmp 

	 LEFT JOIN scsq_aliasingroups ON tmp.id=scsq_aliasingroups.groupid 
	 LEFT JOIN scsq_alias on scsq_alias.id=scsq_aliasingroups.aliasid 

	 LEFT JOIN (SELECT 
		      sum(sizeinbytes) as s,
		      login 
		    from scsq_quicktraffic 
		    where scsq_quicktraffic.date>".$datestart." 
		      and scsq_quicktraffic.date<".$dateend." 
         	      AND site NOT IN (".$goodSitesList.")

		    GROUP BY login) 
		    tmp2 
	 ON tmp2.login=scsq_alias.tableid

   GROUP BY tmp.name, tmp.id, tmp.typeid)

 UNION

  (SELECT 
     tmp.name as gname,
     sum(tmp2.s) as s1,
     tmp.id as gid,
     tmp.typeid as gtypeid 
   FROM (SELECT 
	   id,
	   name,
	   typeid 
	 FROM scsq_groups 
	 where typeid=1) 
	 AS tmp 

	 LEFT JOIN scsq_aliasingroups ON tmp.id=scsq_aliasingroups.groupid 
	 LEFT JOIN scsq_alias ON scsq_alias.id=scsq_aliasingroups.aliasid 

	 LEFT JOIN (SELECT 
		      sum(sizeinbytes) as s,
		      ipaddress 
		    FROM scsq_quicktraffic 
		    WHERE scsq_quicktraffic.date>".$datestart." 
		      and scsq_quicktraffic.date<".$dateend." 
        	      AND site NOT IN (".$goodSitesList.")

		    GROUP BY ipaddress) 
		    tmp2 
	 ON tmp2.ipaddress=scsq_alias.tableid 

  GROUP BY tmp.name, tmp.id, tmp.typeid)) as grtable
  order by gname;";



if($typeid==0)
$queryOneGroupTraffic="
  SELECT 
    scsq_logins.name,
    tmp2.s as s1,
    tmp2.login,
    scsq_alias.name
  FROM (SELECT 
	  id,
	  name 
	FROM scsq_groups 
	WHERE typeid=0 
	  and id='".$currentgroupid."') 
	AS tmp 
	LEFT JOIN scsq_aliasingroups ON tmp.id=scsq_aliasingroups.groupid 
	LEFT JOIN scsq_alias ON scsq_alias.id=scsq_aliasingroups.aliasid 
	LEFT JOIN (SELECT 
		     SUM(sizeinbytes) as s,
		     login 
		   FROM scsq_quicktraffic 
		   WHERE scsq_quicktraffic.date>".$datestart." 
		     and scsq_quicktraffic.date<".$dateend."
	             AND site NOT IN (".$goodSitesList.")
 
		   GROUP BY crc32(login),login) 
		   tmp2 
	ON tmp2.login=scsq_alias.tableid 
	
	LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id 

	ORDER BY scsq_logins.name asc;";



if($typeid==1)
$queryOneGroupTraffic="
  SELECT 
    scsq_ipaddress.name,
    tmp2.s as s1,
    tmp2.ipaddress,
    scsq_alias.name
  FROM (SELECT 
	  id,
	  name 
	FROM scsq_groups 
	WHERE typeid=1 
	  and id='".$currentgroupid."') 
	AS tmp 

	LEFT JOIN scsq_aliasingroups ON tmp.id=scsq_aliasingroups.groupid 
	LEFT JOIN scsq_alias on scsq_alias.id=scsq_aliasingroups.aliasid 
	LEFT JOIN (SELECT
		     sum(sizeinbytes) as s,
		     ipaddress 
		   FROM scsq_quicktraffic 
		   WHERE scsq_quicktraffic.date>".$datestart." 
		     and scsq_quicktraffic.date<".$dateend." 
	             AND site NOT IN (".$goodSitesList.")

		   GROUP BY crc32(ipaddress),ipaddress) 
		   tmp2 
	ON tmp2.ipaddress=scsq_alias.tableid 

	LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 

  ORDER BY scsq_ipaddress.name asc;";
  
  
 #postgre version
 if($dbtype==1) 
  if($typeid==0)
$queryOneGroupTraffic="
  SELECT 
    scsq_logins.name,
    tmp2.s as s1,
    tmp2.login,
    scsq_alias.name
  FROM (SELECT 
	  id,
	  name 
	FROM scsq_groups 
	WHERE typeid=0 
	  and id='".$currentgroupid."') 
	AS tmp 
	LEFT JOIN scsq_aliasingroups ON tmp.id=scsq_aliasingroups.groupid 
	LEFT JOIN scsq_alias ON scsq_alias.id=scsq_aliasingroups.aliasid 
	LEFT JOIN (SELECT 
		     SUM(sizeinbytes) as s,
		     login 
		   FROM scsq_quicktraffic 
		   WHERE scsq_quicktraffic.date>".$datestart." 
		     and scsq_quicktraffic.date<".$dateend."
	             AND site NOT IN (".$goodSitesList.")
 
		   GROUP BY login) 
		   tmp2 
	ON tmp2.login=scsq_alias.tableid 
	
	LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id 

	ORDER BY scsq_logins.name asc;";


 #postgre version
if($dbtype==1)
if($typeid==1)
$queryOneGroupTraffic="
  SELECT 
    scsq_ipaddress.name,
    tmp2.s as s1,
    tmp2.ipaddress,
    scsq_alias.name
  FROM (SELECT 
	  id,
	  name 
	FROM scsq_groups 
	WHERE typeid=1 
	  and id='".$currentgroupid."') 
	AS tmp 

	LEFT JOIN scsq_aliasingroups ON tmp.id=scsq_aliasingroups.groupid 
	LEFT JOIN scsq_alias on scsq_alias.id=scsq_aliasingroups.aliasid 
	LEFT JOIN (SELECT
		     sum(sizeinbytes) as s,
		     ipaddress 
		   FROM scsq_quicktraffic 
		   WHERE scsq_quicktraffic.date>".$datestart." 
		     and scsq_quicktraffic.date<".$dateend." 
	             AND site NOT IN (".$goodSitesList.")

		   GROUP BY ipaddress) 
		   tmp2 
	ON tmp2.ipaddress=scsq_alias.tableid 

	LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 

  ORDER BY scsq_ipaddress.name asc;";
  

if($typeid==0)
$queryOneGroupTrafficWide="
SELECT 
	tmp2.name,
	sum(tmp2.sum_in_cache),
	sum(tmp2.sum_out_cache),
	sum(tmp2.sum_bytes),
	sum(tmp2.sum_in_cache)/sum(tmp2.sum_bytes)*100,
	sum(tmp2.sum_out_cache)/sum(tmp2.sum_bytes)*100,
	tmp2.al
FROM (
	SELECT 
		nofriends.name,
		tmp.sum_in_cache,
		tmp.sum_out_cache,
		tmp.sum_bytes,
		tmp.al,
		tmp.login

  FROM ((SELECT 
	   login,
	   '2' as n,
	   SUM(sizeinbytes) AS sum_in_cache,
	   0 AS sum_out_cache,
	   0 as sum_bytes,
	   listaliases.name as al 
	 FROM scsq_quicktraffic

	 RIGHT JOIN (SELECT 
		       * 
		     FROM scsq_alias) 
		     AS listaliases  
	 ON listaliases.tableid=scsq_quicktraffic.login

	 RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 
	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=0 
		       and id='".$currentgroupid."') 
		     AS curgroup  
	 ON scsq_aliasingroups.groupid=curgroup.id

	,scsq_httpstatus 

	WHERE (scsq_httpstatus.name like '%TCP_HIT%' 
	   or  scsq_httpstatus.name like '%TCP_IMS_HIT%' 
	   or  scsq_httpstatus.name like '%TCP_MEM_HIT%' 
	   or  scsq_httpstatus.name like '%TCP_OFFLINE_HIT%' 
	   or  scsq_httpstatus.name like '%UDP_HIT%') 
	  and  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  and  date>".$datestart." 
	  and  date<".$dateend." 
          AND site NOT IN (".$goodSitesList.")

	GROUP BY crc32(login),login, listaliases.name
	ORDER BY null) 

  UNION 

	(SELECT 
	   login,
	   '3' as n,
	   0 AS sum_in_cache,
	   SUM(sizeinbytes) AS sum_out_cache,
	   0 as sum_bytes,
	   listaliases.name as al 
	 FROM scsq_quicktraffic

	 RIGHT JOIN (SELECT 
		       * 
		     FROM scsq_alias) 
		     AS listaliases  
	 ON listaliases.tableid=scsq_quicktraffic.login

	 RIGHT JOIN scsq_aliasingroups ON listaliases.id=scsq_aliasingroups.aliasid 
	 
	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=0 
		       and id='".$currentgroupid."') 
		     AS curgroup  
	 ON scsq_aliasingroups.groupid=curgroup.id

	,scsq_httpstatus 

	WHERE (scsq_httpstatus.name NOT LIKE '%TCP_HIT%' 
	  and  scsq_httpstatus.name not like '%TCP_IMS_HIT%' 
	  and  scsq_httpstatus.name not like '%TCP_MEM_HIT%' 
	  and  scsq_httpstatus.name not like '%TCP_OFFLINE_HIT%' 
	  and  scsq_httpstatus.name not like '%UDP_HIT%') 
	  and  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  and  date>".$datestart." 
	  and  date<".$dateend."  
          AND site NOT IN (".$goodSitesList.")
	
	GROUP BY crc32(login) ,login, listaliases.name
	ORDER BY null) 

  UNION 

	(SELECT 
	   login,
	   '1' as n,
	   0 AS sum_in_cache,
	   0 AS sum_out_cache,
	   SUM(sizeinbytes) as sum_bytes,
	   listaliases.name as al 
	 from scsq_quicktraffic 

	 RIGHT JOIN (select 
		       * 
		     from scsq_alias) 
		     as listaliases 
	 ON listaliases.tableid=scsq_quicktraffic.login

	 RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=0 
		       and id='".$currentgroupid."') 
		     AS curgroup  
	 ON scsq_aliasingroups.groupid=curgroup.id

	 WHERE date>".$datestart." 
	   and date<".$dateend."  
           AND site NOT IN (".$goodSitesList.")

	 GROUP BY crc32(login) ,login, listaliases.name
	 ORDER BY null)) 
	 AS tmp

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_logins 
		     WHERE id NOT IN (".$goodLoginsList.")) 
		     AS nofriends 
	 ON tmp.login=nofriends.id

	 ) tmp2
	 WHERE tmp2.login is not null
	 GROUP BY tmp2.login,tmp2.name
	 ORDER BY tmp2.name asc;";



if($typeid==1)
$queryOneGroupTrafficWide="
SELECT 
	tmp2.name,
	sum(tmp2.sum_in_cache),
	sum(tmp2.sum_out_cache),
	sum(tmp2.sum_bytes),
	sum(tmp2.sum_in_cache)/sum(tmp2.sum_bytes)*100,
	sum(tmp2.sum_out_cache)/sum(tmp2.sum_bytes)*100,
	tmp2.al
FROM (
	SELECT 
		nofriends.name,
		tmp.sum_in_cache,
		tmp.sum_out_cache,
		tmp.sum_bytes,
		tmp.al,
		tmp.ipaddress

  
  FROM ((SELECT 
	   ipaddress,
	   '2' as n,
	   SUM(sizeinbytes) AS sum_in_cache,
	   0 AS sum_out_cache,
	   0 as sum_bytes,
	   listaliases.name as al  
	 FROM scsq_quicktraffic

	 RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.ipaddress

	 RIGHT JOIN scsq_aliasingroups ON listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=1 
		       and id='".$currentgroupid."') 
		     AS curgroup  
	 ON scsq_aliasingroups.groupid=curgroup.id

	,scsq_httpstatus 

	 WHERE (scsq_httpstatus.name like '%TCP_HIT%' 
	    or  scsq_httpstatus.name like '%TCP_IMS_HIT%' 
	    or  scsq_httpstatus.name like '%TCP_MEM_HIT%' 
	    or  scsq_httpstatus.name like '%TCP_OFFLINE_HIT%' 
	    or  scsq_httpstatus.name like '%UDP_HIT%') 
	   and  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	   and  date>".$datestart." 
	   and  date<".$dateend." 
           AND site NOT IN (".$goodSitesList.")

	 GROUP BY CRC32(ipaddress) ,ipaddress, listaliases.name
	 ORDER BY null) 

  UNION 

	(SELECT 
	   ipaddress,
	   '3' as n,
	   0 AS sum_in_cache,
	   SUM(sizeinbytes) AS sum_out_cache,
	   0 as sum_bytes,
	   listaliases.name as al 
	 FROM scsq_quicktraffic

	 RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.ipaddress

	 RIGHT JOIN scsq_aliasingroups ON listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=1 
		       and id='".$currentgroupid."') 
		     AS curgroup  
	 ON scsq_aliasingroups.groupid=curgroup.id

	 ,scsq_httpstatus 

	 WHERE (scsq_httpstatus.name not like '%TCP_HIT%' 
	   and  scsq_httpstatus.name not like '%TCP_IMS_HIT%' 
	   and  scsq_httpstatus.name not like '%TCP_MEM_HIT%' 
	   and  scsq_httpstatus.name not like '%TCP_OFFLINE_HIT%' 
	   and  scsq_httpstatus.name not like '%UDP_HIT%') 
	   and  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	   and  date>".$datestart." 
	   and  date<".$dateend."  
           AND site NOT IN (".$goodSitesList.")

	 GROUP BY CRC32(ipaddress) , ipaddress, listaliases.name
	 ORDER BY null) 

  UNION 

	(SELECT 
	   ipaddress,
	   '1' as n,
	   0 AS sum_in_cache,
	   0 AS sum_out_cache,
	   SUM(sizeinbytes) as sum_bytes,
	   listaliases.name as al 
	 FROM scsq_quicktraffic 

	 RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.ipaddress

	 RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=1 
		       and id='".$currentgroupid."') 
		     AS curgroup 
	 ON scsq_aliasingroups.groupid=curgroup.id

	 WHERE date>".$datestart." 
	   and date<".$dateend."  
           AND site NOT IN (".$goodSitesList.")

	 GROUP BY crc32(ipaddress) , ipaddress, listaliases.name
	 ORDER BY null)) 
	 AS tmp

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_ipaddress 
		     WHERE id NOT IN (".$goodIpaddressList.")) 
		     AS nofriends 
	 ON tmp.ipaddress=nofriends.id

	 ) tmp2
	 WHERE tmp2.ipaddress is not null
	 GROUP BY tmp2.ipaddress,tmp2.name
	 ORDER BY tmp2.name asc;";


#postgre version
if($dbtype==1)
if($typeid==0)
$queryOneGroupTrafficWide="
  SELECT 
    nofriends.name,
    tmp.s,
    tmp.login,
    tmp.n, 
    tmp.al
  FROM ((SELECT 
	   login,
	   '2' as n,
	   sum(sizeinbytes) as s,
	   listaliases.name as al 
	 FROM scsq_quicktraffic

	 RIGHT JOIN (SELECT 
		       * 
		     FROM scsq_alias) 
		     AS listaliases  
	 ON listaliases.tableid=scsq_quicktraffic.login

	 RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 
	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=0 
		       and id='".$currentgroupid."') 
		     AS curgroup  
	 ON scsq_aliasingroups.groupid=curgroup.id

	,scsq_httpstatus 

	WHERE (scsq_httpstatus.name like '%TCP_HIT%' 
	   or  scsq_httpstatus.name like '%TCP_IMS_HIT%' 
	   or  scsq_httpstatus.name like '%TCP_MEM_HIT%' 
	   or  scsq_httpstatus.name like '%TCP_OFFLINE_HIT%' 
	   or  scsq_httpstatus.name like '%UDP_HIT%') 
	  and  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  and  date>".$datestart." 
	  and  date<".$dateend." 
          AND site NOT IN (".$goodSitesList.")

	GROUP BY login, listaliases.name 
	) 

  UNION 

	(SELECT 
	   login,
	   '3' as n,
	   sum(sizeinbytes) as s,
	   listaliases.name as al 
	 FROM scsq_quicktraffic

	 RIGHT JOIN (SELECT 
		       * 
		     FROM scsq_alias) 
		     AS listaliases  
	 ON listaliases.tableid=scsq_quicktraffic.login

	 RIGHT JOIN scsq_aliasingroups ON listaliases.id=scsq_aliasingroups.aliasid 
	 
	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=0 
		       and id='".$currentgroupid."') 
		     AS curgroup  
	 ON scsq_aliasingroups.groupid=curgroup.id

	,scsq_httpstatus 

	WHERE (scsq_httpstatus.name NOT LIKE '%TCP_HIT%' 
	  and  scsq_httpstatus.name not like '%TCP_IMS_HIT%' 
	  and  scsq_httpstatus.name not like '%TCP_MEM_HIT%' 
	  and  scsq_httpstatus.name not like '%TCP_OFFLINE_HIT%' 
	  and  scsq_httpstatus.name not like '%UDP_HIT%') 
	  and  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  and  date>".$datestart." 
	  and  date<".$dateend."  
          AND site NOT IN (".$goodSitesList.")
	
	GROUP BY login, listaliases.name
	) 

  UNION 

	(SELECT 
	   login,
	   '1' as n,
	   sum(sizeinbytes) as s,
	   listaliases.name as al 
	 from scsq_quicktraffic 

	 RIGHT JOIN (select 
		       * 
		     from scsq_alias) 
		     as listaliases 
	 ON listaliases.tableid=scsq_quicktraffic.login

	 RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=0 
		       and id='".$currentgroupid."') 
		     AS curgroup  
	 ON scsq_aliasingroups.groupid=curgroup.id

	 WHERE date>".$datestart." 
	   and date<".$dateend."  
           AND site NOT IN (".$goodSitesList.")

	 GROUP BY login,listaliases.name
	 )) 
	 AS tmp

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_logins 
		     WHERE id NOT IN (".$goodLoginsList.")) 
		     AS nofriends 
	 ON tmp.login=nofriends.id

  ORDER BY nofriends.name asc,tmp.n asc;";


#postgre version
if($dbtype==1)
if($typeid==1)
$queryOneGroupTrafficWide="
  SELECT 
    nofriends.name,
    tmp.s,
    tmp.ipaddress,
    tmp.n,
    tmp.al 
  
  FROM ((SELECT 
	   ipaddress,
	   '2' as n,
	   sum(sizeinbytes) as s,
	   listaliases.name as al  
	 FROM scsq_quicktraffic

	 RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.ipaddress

	 RIGHT JOIN scsq_aliasingroups ON listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=1 
		       and id='".$currentgroupid."') 
		     AS curgroup  
	 ON scsq_aliasingroups.groupid=curgroup.id

	,scsq_httpstatus 

	 WHERE (scsq_httpstatus.name like '%TCP_HIT%' 
	    or  scsq_httpstatus.name like '%TCP_IMS_HIT%' 
	    or  scsq_httpstatus.name like '%TCP_MEM_HIT%' 
	    or  scsq_httpstatus.name like '%TCP_OFFLINE_HIT%' 
	    or  scsq_httpstatus.name like '%UDP_HIT%') 
	   and  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	   and  date>".$datestart." 
	   and  date<".$dateend." 
           AND site NOT IN (".$goodSitesList.")

	 GROUP BY ipaddress, listaliases.name 
	 ) 

  UNION 

	(SELECT 
	   ipaddress,
	   '3' as n,
	   sum(sizeinbytes) as s,
	   listaliases.name as al 
	 FROM scsq_quicktraffic

	 RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.ipaddress

	 RIGHT JOIN scsq_aliasingroups ON listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=1 
		       and id='".$currentgroupid."') 
		     AS curgroup  
	 ON scsq_aliasingroups.groupid=curgroup.id

	 ,scsq_httpstatus 

	 WHERE (scsq_httpstatus.name not like '%TCP_HIT%' 
	   and  scsq_httpstatus.name not like '%TCP_IMS_HIT%' 
	   and  scsq_httpstatus.name not like '%TCP_MEM_HIT%' 
	   and  scsq_httpstatus.name not like '%TCP_OFFLINE_HIT%' 
	   and  scsq_httpstatus.name not like '%UDP_HIT%') 
	   and  scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	   and  date>".$datestart." 
	   and  date<".$dateend."  
           AND site NOT IN (".$goodSitesList.")

	 GROUP BY ipaddress, listaliases.name
	 ) 

  UNION 

	(SELECT 
	   ipaddress,
	   '1' as n,
	   sum(sizeinbytes) as s,
	   listaliases.name as al 
	 FROM scsq_quicktraffic 

	 RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.ipaddress

	 RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=1 
		       and id='".$currentgroupid."') 
		     AS curgroup 
	 ON scsq_aliasingroups.groupid=curgroup.id

	 WHERE date>".$datestart." 
	   and date<".$dateend."  
           AND site NOT IN (".$goodSitesList.")

	 GROUP BY ipaddress, listaliases.name 
	 )) 
	 AS tmp

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_ipaddress 
		     WHERE id NOT IN (".$goodIpaddressList.")) 
		     AS nofriends 
	 ON tmp.ipaddress=nofriends.id

  ORDER BY nofriends.name asc,tmp.n asc;";


if($typeid==0)
$queryOneGroupTopSitesTraffic="
  	 SELECT 
	   site,
	   sum(sizeinbytes) as s,
	   login,
	   ipaddress 
	 FROM scsq_quicktraffic 

	 RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.login

	 RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=0 
		       and id='".$currentgroupid."') 
		     AS curgroup 
	 ON scsq_aliasingroups.groupid=curgroup.id

	 WHERE date>".$datestart." 
	   AND date<".$dateend." 
	   AND login IN (SELECT id from scsq_logins where id NOT IN (".$goodLoginsList.")) 
	   AND ipaddress IN (SELECT id from scsq_ipaddress where id NOT IN (".$goodIpaddressList.")) 
	   AND site NOT IN (".$goodSitesList.")
	 GROUP BY crc32(site), site, login,ipaddress
	 ORDER BY s desc 
	 LIMIT ".$countTopSitesLimit." ";


if($typeid==1)
$queryOneGroupTopSitesTraffic="
    	 SELECT 
	   site,
	   sum(sizeinbytes) as s,
	   login,
	   ipaddress 
	 FROM scsq_quicktraffic 

	 RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.ipaddress

	 RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=1 
		       and id='".$currentgroupid."') 
		     AS curgroup 
	 ON scsq_aliasingroups.groupid=curgroup.id

	 WHERE date>".$datestart." 
	   AND date<".$dateend." 
	   AND login IN (SELECT id from scsq_logins where id NOT IN (".$goodLoginsList.")) 
	   AND ipaddress IN (SELECT id from scsq_ipaddress where id NOT IN (".$goodIpaddressList.")) 
	   AND site NOT IN (".$goodSitesList.")
	 GROUP BY crc32(site), site ,login, ipaddress
	 ORDER BY s desc 
	 LIMIT ".$countTopSitesLimit." ";


#postgre version
if($dbtype==1)
if($typeid==0)
$queryOneGroupTopSitesTraffic="
  	 SELECT 
	   site,
	   sum(sizeinbytes) as s
	 FROM scsq_quicktraffic 

	 RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.login

	 RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=0 
		       and id='".$currentgroupid."') 
		     AS curgroup 
	 ON scsq_aliasingroups.groupid=curgroup.id

	 WHERE date>".$datestart." 
	   AND date<".$dateend." 
	   AND login IN (SELECT id from scsq_logins where id NOT IN (".$goodLoginsList.")) 
	   AND ipaddress IN (SELECT id from scsq_ipaddress where id NOT IN (".$goodIpaddressList.")) 
	   AND site NOT IN (".$goodSitesList.")
	 GROUP BY site 
	 ORDER BY s desc 
	 LIMIT ".$countTopSitesLimit." ";

#postgre version
if($dbtype==1)
if($typeid==1)
$queryOneGroupTopSitesTraffic="
    	 SELECT 
	   site,
	   sum(sizeinbytes) as s
	 FROM scsq_quicktraffic 

	 RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.ipaddress

	 RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_groups 
		     WHERE typeid=1 
		       and id='".$currentgroupid."') 
		     AS curgroup 
	 ON scsq_aliasingroups.groupid=curgroup.id

	 WHERE date>".$datestart." 
	   AND date<".$dateend." 
	   AND login IN (SELECT id from scsq_logins where id NOT IN (".$goodLoginsList.")) 
	   AND ipaddress IN (SELECT id from scsq_ipaddress where id NOT IN (".$goodIpaddressList.")) 
	   AND site NOT IN (".$goodSitesList.")
	 GROUP BY site
	 ORDER BY s desc 
	 LIMIT ".$countTopSitesLimit." ";



if($typeid==0)
$queryOneGroupTrafficByHours="
SELECT hrs.hr_txt,
		tmp2.sum_bytes,
		hrs.hr 
		FROM (
  SELECT 
    from_unixtime(tmp.date,'%H') as d,
    sum(tmp.s) as sum_bytes
  FROM (SELECT 
	  date,
	  sum(sizeinbytes) as s,
	  login,
	  ipaddress 
	FROM scsq_quicktraffic 

	RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.login

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    WHERE typeid=0 
		      and id='".$currentgroupid."') 
		    AS curgroup 
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (SELECT id from scsq_logins where id IN (".$goodLoginsList.")) as tmplogin ON tmplogin.id=scsq_quicktraffic.login
	LEFT OUTER JOIN (select id from scsq_ipaddress where id IN (".$goodIpaddressList.")) as tmpipaddress ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  and date<".$dateend." 
	  and tmplogin.id is  NULL 
	  and tmpipaddress.id is  NULL
          AND site NOT IN (".$goodSitesList.")
	
	GROUP BY crc32(date) ,date, login, ipaddress
	ORDER BY null) 
	AS tmp 

	group by d 
	) tmp2
  
	RIGHT JOIN (
	  select 0 as hr, '0:00-1:00' as hr_txt 
	  UNION all 
	  select 1 as hr, '1:00-2:00' as hr_txt 
	  UNION all 
	  select 2 as hr, '2:00-3:00' as hr_txt 
	  UNION all 
	  select 3 as hr, '3:00-4:00' as hr_txt 
	  UNION all 
	  select 4 as hr, '4:00-5:00' as hr_txt 
	  UNION all 
	  select 5 as hr, '5:00-6:00' as hr_txt 
	  UNION all 
	  select 6 as hr, '6:00-7:00' as hr_txt 
	  UNION all 
	  select 7 as hr, '7:00-8:00' as hr_txt 
	  UNION all 
	  select 8 as hr, '8:00-9:00' as hr_txt 
	  UNION all 
	  select 9 as hr, '9:00-10:00' as hr_txt 
	  UNION all 
	  select 10 as hr, '10:00-11:00' as hr_txt 
	  UNION all 
	  select 11 as hr, '11:00-12:00' as hr_txt 
	  UNION all 
	  select 12 as hr, '12:00-13:00' as hr_txt 
	  UNION all 
	  select 13 as hr, '13:00-14:00' as hr_txt 
	  UNION all 
	  select 14 as hr, '14:00-15:00' as hr_txt 
	  UNION all 
	  select 15 as hr, '15:00-16:00' as hr_txt 
	  UNION all 
	  select 16 as hr, '16:00-17:00' as hr_txt 
	  UNION all 
	  select 17 as hr, '17:00-18:00' as hr_txt 
	  UNION all 
	  select 18 as hr, '18:00-19:00' as hr_txt 
	  UNION all 
	  select 19 as hr, '19:00-20:00' as hr_txt 
	  UNION all 
	  select 20 as hr, '20:00-21:00' as hr_txt 
	  UNION all 
	  select 21 as hr, '21:00-22:00' as hr_txt 
	  UNION all 
	  select 22 as hr, '22:00-23:00' as hr_txt 
	  UNION all 
	  select 23 as hr, '23:00-24:00' as hr_txt 
	  
				   
				   ) hrs on hrs.hr=tmp2.d
				   
				   order by hrs.hr asc ";

if($typeid==1)
$queryOneGroupTrafficByHours="
SELECT hrs.hr_txt,
		tmp2.sum_bytes,
		hrs.hr 
		FROM (
  SELECT 
    from_unixtime(tmp.date,'%H') as d,
    sum(tmp.s) as sum_bytes
  FROM (SELECT 
	  date,
	  sum(sizeinbytes) as s,
	  login,
	  ipaddress 
	FROM scsq_quicktraffic 

	RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.ipaddress

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    WHERE typeid=1 
		      and id='".$currentgroupid."') 
		    AS curgroup 
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (SELECT id from scsq_logins where id IN (".$goodLoginsList.")) as tmplogin ON tmplogin.id=scsq_quicktraffic.login
	LEFT OUTER JOIN (select id from scsq_ipaddress where id IN (".$goodIpaddressList.")) as tmpipaddress ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  and date<".$dateend." 
	  and tmplogin.id is  NULL 
	  and tmpipaddress.id is  NULL
          AND site NOT IN (".$goodSitesList.")
	
	GROUP BY crc32(date) ,date, login, ipaddress
	ORDER BY null) 
	AS tmp 

    group by d 
  ) tmp2

  RIGHT JOIN (
	select 0 as hr, '0:00-1:00' as hr_txt 
	UNION all 
	select 1 as hr, '1:00-2:00' as hr_txt 
	UNION all 
	select 2 as hr, '2:00-3:00' as hr_txt 
	UNION all 
	select 3 as hr, '3:00-4:00' as hr_txt 
	UNION all 
	select 4 as hr, '4:00-5:00' as hr_txt 
	UNION all 
	select 5 as hr, '5:00-6:00' as hr_txt 
	UNION all 
	select 6 as hr, '6:00-7:00' as hr_txt 
	UNION all 
	select 7 as hr, '7:00-8:00' as hr_txt 
	UNION all 
	select 8 as hr, '8:00-9:00' as hr_txt 
	UNION all 
	select 9 as hr, '9:00-10:00' as hr_txt 
	UNION all 
	select 10 as hr, '10:00-11:00' as hr_txt 
	UNION all 
	select 11 as hr, '11:00-12:00' as hr_txt 
	UNION all 
	select 12 as hr, '12:00-13:00' as hr_txt 
	UNION all 
	select 13 as hr, '13:00-14:00' as hr_txt 
	UNION all 
	select 14 as hr, '14:00-15:00' as hr_txt 
	UNION all 
	select 15 as hr, '15:00-16:00' as hr_txt 
	UNION all 
	select 16 as hr, '16:00-17:00' as hr_txt 
	UNION all 
	select 17 as hr, '17:00-18:00' as hr_txt 
	UNION all 
	select 18 as hr, '18:00-19:00' as hr_txt 
	UNION all 
	select 19 as hr, '19:00-20:00' as hr_txt 
	UNION all 
	select 20 as hr, '20:00-21:00' as hr_txt 
	UNION all 
	select 21 as hr, '21:00-22:00' as hr_txt 
	UNION all 
	select 22 as hr, '22:00-23:00' as hr_txt 
	UNION all 
	select 23 as hr, '23:00-24:00' as hr_txt 
	
				 
				 ) hrs on hrs.hr=tmp2.d
				 
				 order by hrs.hr asc ";


#postgre version
if($dbtype==1)
if($typeid==0)
$queryOneGroupTrafficByHours="
  SELECT 
    cast(to_char(to_timestamp(tmp.date),'HH24') as int) as d,
    sum(tmp.s) 
  FROM (SELECT 
	  date,
	  sum(sizeinbytes) as s

	FROM scsq_quicktraffic 

	RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.login

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    WHERE typeid=0 
		      and id='".$currentgroupid."') 
		    AS curgroup 
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (SELECT id from scsq_logins where id IN (".$goodLoginsList.")) as tmplogin ON tmplogin.id=scsq_quicktraffic.login
	LEFT OUTER JOIN (select id from scsq_ipaddress where id IN (".$goodIpaddressList.")) as tmpipaddress ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  and date<".$dateend." 
	  and tmplogin.id is  NULL 
	  and tmpipaddress.id is  NULL
          AND site NOT IN (".$goodSitesList.")
	
	GROUP BY date 
	) 
	AS tmp 

  GROUP BY d;";


#postgre version
if($dbtype==1)
if($typeid==1)
$queryOneGroupTrafficByHours="
  SELECT 
    cast(to_char(to_timestamp(tmp.date),'HH24') as int) as d,
    sum(tmp.s) 
  FROM (SELECT 
	  date,
	  sum(sizeinbytes) as s
	FROM scsq_quicktraffic 

	RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_quicktraffic.ipaddress

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    WHERE typeid=1 
		      and id='".$currentgroupid."') 
		    AS curgroup 
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (SELECT id from scsq_logins where id IN (".$goodLoginsList.")) as tmplogin ON tmplogin.id=scsq_quicktraffic.login
	LEFT OUTER JOIN (select id from scsq_ipaddress where id IN (".$goodIpaddressList.")) as tmpipaddress ON tmpipaddress.id=scsq_quicktraffic.ipaddress

	WHERE date>".$datestart." 
	  and date<".$dateend." 
	  and tmplogin.id is  NULL 
	  and tmpipaddress.id is  NULL
          AND site NOT IN (".$goodSitesList.")
	
	GROUP BY date 
	) 
	AS tmp 

  GROUP BY d;";

if($typeid==0)
$queryOneGroupWhoDownloadBigFiles="
  SELECT 
    scsq_logins.name,
    sizeinbytes,
    scsq_ipaddress.name,
    site,
    login,
    ipaddress 
  FROM (SELECT 
	  sizeinbytes,
	  site,
	  login,
	  ipaddress 
	FROM scsq_traffic

	RIGHT JOIN (select * from scsq_alias where typeid=0) as listaliases  ON listaliases.tableid=scsq_traffic.login

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    WHERE typeid=0 
		      and id='".$currentgroupid."') 
		    AS curgroup 
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (select id,name from scsq_logins where id IN (".$goodLoginsList.")) as tmplogin ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (select id,name from scsq_ipaddress where id IN (".$goodIpaddressList.")) as tmpipaddress ON tmpipaddress.id=scsq_traffic.ipaddress

	WHERE date>".$datestart." 
	  and date<".$dateend." 
	  and tmplogin.id is NULL 
	  and tmpipaddress.id IS NULL
          AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
          AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
	) 
	AS tmp, scsq_logins,scsq_ipaddress 

  WHERE scsq_logins.id=tmp.login 
    and scsq_ipaddress.id=tmp.ipaddress
  ORDER BY sizeinbytes desc 
  LIMIT ".$countWhoDownloadBigFilesLimit.";";

if($typeid==1)
$queryOneGroupWhoDownloadBigFiles="
  SELECT 
    scsq_logins.name, 
    sizeinbytes,
    scsq_ipaddress.name, 
    site,
    login,
    ipaddress 
  FROM (SELECT 
	  sizeinbytes,
	  site,
	  login,
	  ipaddress 
	FROM scsq_traffic

	RIGHT JOIN (select * from scsq_alias where typeid=1) as listaliases  ON listaliases.tableid=scsq_traffic.ipaddress

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 
	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    where typeid=1
		      and id='".$currentgroupid."') 
		    as curgroup  
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 from scsq_logins 
			 where id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 from scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress

	where date>".$datestart." 
	  and date<".$dateend." 
	  and tmplogin.id is NULL 
	  and tmpipaddress.id IS NULL
          AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
          AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
	) 
	as tmp

	,scsq_logins,scsq_ipaddress 

	WHERE scsq_logins.id=tmp.login 
	  and scsq_ipaddress.id=tmp.ipaddress
  order by sizeinbytes desc limit ".$countWhoDownloadBigFilesLimit.";";


#postgre version
if($dbtype==1)
if($typeid==0)
$queryOneGroupWhoDownloadBigFiles="
  SELECT 
    scsq_logins.name,
    sizeinbytes,
    scsq_ipaddress.name,
    site,
    login,
    ipaddress 
  FROM (SELECT 
	  sizeinbytes,
	  site,
	  login,
	  ipaddress 
	FROM scsq_traffic

	RIGHT JOIN (select * from scsq_alias where typeid=0) as listaliases  ON listaliases.tableid=scsq_traffic.login

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    WHERE typeid=0 
		      and id='".$currentgroupid."') 
		    AS curgroup 
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (select id,name from scsq_logins where id IN (".$goodLoginsList.")) as tmplogin ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (select id,name from scsq_ipaddress where id IN (".$goodIpaddressList.")) as tmpipaddress ON tmpipaddress.id=scsq_traffic.ipaddress

	WHERE date>".$datestart." 
	  and date<".$dateend." 
	  and tmplogin.id is NULL 
	  and tmpipaddress.id IS NULL
   	  AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
      AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
	) 
	AS tmp, scsq_logins,scsq_ipaddress 

  WHERE scsq_logins.id=tmp.login 
    and scsq_ipaddress.id=tmp.ipaddress
  
  ORDER BY sizeinbytes desc 
  LIMIT ".$countWhoDownloadBigFilesLimit.";";

#postgre version
if($dbtype==1)
if($typeid==1)
$queryOneGroupWhoDownloadBigFiles="
  SELECT 
    scsq_logins.name, 
    sizeinbytes,
    scsq_ipaddress.name, 
    site,
    login,
    ipaddress 
  FROM (SELECT 
	  sizeinbytes,
	  site,
	  login,
	  ipaddress 
	FROM scsq_traffic

	RIGHT JOIN (select * from scsq_alias where typeid=1) as listaliases  ON listaliases.tableid=scsq_traffic.ipaddress

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 
	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    where typeid=1
		      and id='".$currentgroupid."') 
		    as curgroup  
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 from scsq_logins 
			 where id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   id,
			   name 
			 from scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) 
			 AS tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress

	where date>".$datestart." 
	  and date<".$dateend." 
	  and tmplogin.id is NULL 
	  and tmpipaddress.id IS NULL
      AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
      AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
	) 
	as tmp

	,scsq_logins,scsq_ipaddress 

	WHERE scsq_logins.id=tmp.login 
	  and scsq_ipaddress.id=tmp.ipaddress
  order by sizeinbytes desc limit ".$countWhoDownloadBigFilesLimit.";";


if($typeid==0)
$queryOneGroupPopularSites="
	SELECT 
	  SUBSTRING_INDEX(site,'/',1) AS st,
	  sum(sizeinbytes) AS s,
	  count(*) AS c
	  
	FROM scsq_traffic 

	RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_traffic.login

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 
	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    where typeid=0 
		      and id='".$currentgroupid."') 
		    as curgroup  
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) as tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL
	  AND tmpipaddress.id is NULL
          AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
          AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
   

	GROUP BY st
	
  ORDER BY c desc 
  LIMIT ".$countPopularSitesLimit.";";

#разность запросов в typeid и ON listaliases.tableid=scsq_traffic.login(ipaddress). костыль 
if($typeid==1)
$queryOneGroupPopularSites="
  SELECT SUBSTRING_INDEX(scsq_traffic.site,'/',1) as stt,
	 tmp.s,
	 tmp.c
  FROM (SELECT 
	  CRC32(SUBSTRING_INDEX(site,'/',1)) AS st,
	  count(*) AS c,
	  sum(sizeinbytes) AS s,
	  scsq_traffic.id
	  
	FROM scsq_traffic 

	RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_traffic.ipaddress

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 
	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    where typeid=1 
		      and id='".$currentgroupid."') 
		    as curgroup  
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) as tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL
	  AND tmpipaddress.id is NULL
          AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
          AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
   

	GROUP BY st
	ORDER BY null) 
	AS tmp 
  JOIN scsq_traffic ON tmp.id=scsq_traffic.id
  WHERE SUBSTRING_INDEX(scsq_traffic.site,'/',1) NOT IN (".$goodSitesList.")
  ORDER BY tmp.c desc 
  LIMIT ".$countPopularSitesLimit.";";

#postgre version
if($dbtype==1)
if($typeid==0)
$queryOneGroupPopularSites="
  
  SELECT 
	  split_part(site,'/',1) AS st,
	   sum(sizeinbytes) AS s,
	  count(*) AS c
	  
	FROM scsq_traffic 

	RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_traffic.login

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 
	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    where typeid=0 
		      and id='".$currentgroupid."') 
		    as curgroup  
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) as tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL
	  AND tmpipaddress.id is NULL
       AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
      AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
 
	GROUP BY st

  ORDER BY c desc 
  LIMIT ".$countPopularSitesLimit.";";

#разность запросов в typeid и ON listaliases.tableid=scsq_traffic.login(ipaddress). костыль 

#postgre version
if($dbtype==1)
if($typeid==1)
$queryOneGroupPopularSites="
  
  SELECT 
	  split_part(site,'/',1) AS st,
	   sum(sizeinbytes) AS s,
	  count(*) AS c
	  
	FROM scsq_traffic 

	RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_traffic.ipaddress

	RIGHT JOIN scsq_aliasingroups on listaliases.id=scsq_aliasingroups.aliasid 
	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_groups 
		    where typeid=1 
		      and id='".$currentgroupid."') 
		    as curgroup  
	ON scsq_aliasingroups.groupid=curgroup.id

	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_logins 
			 WHERE id IN (".$goodLoginsList.")) 
			 AS tmplogin 
	ON tmplogin.id=scsq_traffic.login
	LEFT OUTER JOIN (SELECT 
			   id 
			 FROM scsq_ipaddress 
			 WHERE id IN (".$goodIpaddressList.")) as tmpipaddress 
	ON tmpipaddress.id=scsq_traffic.ipaddress

	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND tmplogin.id is NULL
	  AND tmpipaddress.id is NULL
       AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
      AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
 
	GROUP BY st

  ORDER BY c desc 
  LIMIT ".$countPopularSitesLimit.";";



//querys for group reports end

//querys for reports end






///CALENDAR
if(!isset($_GET['pdf']) && !isset($_GET['csv'])) {
?>

<script type="text/javascript">

//data for calendar
a_dayname=new Array(
'<?php echo $_lang['stMONDAY']; ?>',
'<?php echo $_lang['stTUESDAY']; ?>',
'<?php echo $_lang['stWEDNESDAY']; ?>',
'<?php echo $_lang['stTHURSDAY']; ?>',
'<?php echo $_lang['stFRIDAY']; ?>',
'<?php echo $_lang['stSATURDAY']; ?>',
'<?php echo $_lang['stSUNDAY']; ?>');

a_today = '<?php echo $_lang['stTODAY']; ?>'; 
//Month names
mn=new Array(
'<?php echo $_lang['stJANUARY']; ?>',
'<?php echo $_lang['stFEBRUARY']; ?>',
'<?php echo $_lang['stMARCH']; ?>',
'<?php echo $_lang['stAPRIL']; ?>',
'<?php echo $_lang['stMAY']; ?>',
'<?php echo $_lang['stJUNE']; ?>',
'<?php echo $_lang['stJULY']; ?>',
'<?php echo $_lang['stAUGUST']; ?>',
'<?php echo $_lang['stSEPTEMBER']; ?>',
'<?php echo $_lang['stOCTOBER']; ?>',
'<?php echo $_lang['stNOVEMBER']; ?>',
'<?php echo $_lang['stDECEMBER']; ?>');

</script>


<script src="../javascript/calendar_ru.js" type="text/javascript"></script>


<form name=fastdateswitch_form onsubmit="return false;">
<p><?php echo $_lang['stFASTDATESWITCH']?><p>
<input type="text" name=date_field onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)">
<a href="Javascript:FastDateSwitch(<?php echo $_GET['id'] ?>,'day')"><?php echo $_lang['stDAY']?></a>&nbsp;<a href="Javascript:FastDateSwitch(<?php echo $_GET['id']; ?>,'month')"><?php echo $_lang['stMONTH']?></a>
<br /><br />
<input type="text" name=date2_field onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)">&nbsp;<a href="Javascript:FastDateSwitch(<?php echo $_GET['id']; ?>,'btw')"><?php echo $_lang['stSPECIFIED']; ?></a>
<input type="hidden" name=date_field_hidden value="<?php echo $_GET['date']; ?>">
<input type="hidden" name=dom_field_hidden value="<?php echo $dayormonth; ?>">
<input type="hidden" name=loginname_field_hidden value="<?php echo $currentlogin; ?>">
<input type="hidden" name=login_field_hidden value="<?php echo $currentloginid; ?>">
<input type="hidden" name=ipname_field_hidden value="<?php echo $currentipaddress; ?>">
<input type="hidden" name=ip_field_hidden value="<?php echo $currentipaddressid; ?>">
<input type="hidden" name=site_field_hidden value="<?php echo $currentsite; ?>">
<input type="hidden" name=group_field_hidden value="<?php echo $currentgroupid; ?>">
<input type="hidden" name=groupname_field_hidden value="<?php echo $currentgroup; ?>">
<input type="hidden" name=typeid_field_hidden value="<?php echo $typeid; ?>">
<input type="hidden" name=httpname_field_hidden value="<?php echo $currenthttpname; ?>">
<input type="hidden" name=httpstatus_field_hidden value="<?php echo $currenthttpstatusid; ?>">
<input type="hidden" name=loiid_field_hidden value="<?php echo $currentloiid; ?>">
<input type="hidden" name=loiname_field_hidden value="<?php echo $currentloiname; ?>">

</form>



<h3><a href="Javascript:LeftRightDateSwitch(<?php echo $_GET['id'];?>,'<?php echo $dayormonth; ?>','l')"><<</a>
&nbsp;<?php echo $querydate; ?>&nbsp;
<a href="Javascript:LeftRightDateSwitch(<?php echo $_GET['id'];?>,'<?php echo $dayormonth; ?>','r')">>></a>
</h3>
<?php
}
///CALENDAR END

#костыль для перехода с произвольных отчетов
if($_GET['dom']=="btw"){
$dayname="";
$querydate = $querydate." - ".$querydate2; 
}

///REPORTS HEADERS

$repheader="";

if($id==1)
$repheader="<h2>".$_lang['stLOGINSTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==2)
$repheader="<h2>".$_lang['stIPADDRESSTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==3)
$repheader= "<h2>".$_lang['stSITESTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==4)
$repheader= "<h2>".$_lang['stTOPSITESTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==5)
$repheader= "<h2>".$_lang['stTOPLOGINSTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==6)
$repheader= "<h2>".$_lang['stTOPIPTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==7)
$repheader= "<h2>".$_lang['stTRAFFICBYHOURS']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==8)
$repheader= "<h2>".$_lang['stONELOGINTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==9)
$repheader= "<h2>".$_lang['stONELOGINTOPSITESTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==10)
$repheader= "<h2>".$_lang['stONELOGINTRAFFICBYHOURS']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==11)
$repheader= "<h2>".$_lang['stONEIPADRESSTRAFFIC']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==12)
$repheader= "<h2>".$_lang['stONEIPADDRESSTOPSITESTRAFFIC']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==13)
$repheader= "<h2>".$_lang['stONEIPADDRESSTRAFFICBYHOURS']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==14)
$repheader= "<h2>".$_lang['stLOGINSTRAFFICWIDE']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==15)
$repheader= "<h2>".$_lang['stIPADDRESSTRAFFICWIDE']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==16)
$repheader= "<h2>".$_lang['stIPADDRESSTRAFFICWITHRESOLVE']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==17)
$repheader= "<h2>".$_lang['stPOPULARSITES']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==18)
$repheader= "<h2>".$_lang['stWHOVISITSITELOGIN']." <b>".$currentsite."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==19)
$repheader= "<h2>".$_lang['stWHOVISITSITEIPADDRESS']." <b>".$currentsite."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==20)
$repheader= "<h2>".$_lang['stWHODOWNLOADBIGFILES']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==21)
$repheader= "<h2>".$_lang['stTRAFFICBYPERIOD']."</h2>";

if($id==22)
$repheader= "<h2>".$_lang['stVISITINGWEBSITELOGINS']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate." ".$dayname." ".$_lang['stBYDAYTIME']."</h2>";

if($id==23)
$repheader= "<h2>".$_lang['stVISITINGWEBSITEIPADDRESS']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate." ".$dayname." ".$_lang['stBYDAYTIME']."</h2>";

if($id==24)
$repheader= "<h2>".$_lang['stGROUPSTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==25)
$repheader= "<h2>".$_lang['stONEGROUPTRAFFIC']." ".$currentgroup." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==26)
$repheader= "<h2>".$_lang['stONEGROUPTRAFFIC']." ".$currentgroup." ".$_lang['stFOR']." ".$querydate." ".$dayname." ".$_lang['stEXTENDED']."</h2>";

if($id==27)
$repheader= "<h2>".$_lang['stONEGROUPTOPSITESTRAFFIC']." ".$currentgroup." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==28)
$repheader= "<h2>".$_lang['stONEGROUPTRAFFICBYHOURS']." ".$currentgroup." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==29)
$repheader= "<h2>".$_lang['stONEGROUPWHODOWNLOADBIGFILES']." ".$currentgroup." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==30)
$repheader= "<h2>".$_lang['stHTTPSTATUSES']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==31)
$repheader= "<h2>".$_lang['stHTTPSTATUS']." ".$currenthttpname." ".$_lang['stLOGINSFOR']." ".$querydate." ".$dayname."</h2>";

if($id==32)
$repheader= "<h2>".$_lang['stHTTPSTATUS']." ".$currenthttpname." ".$_lang['stIPADDRESSFOR']." ".$querydate." ".$dayname."</h2>";

if($id==33)
$repheader= "<h2>".$_lang['stHTTPSTATUS']." ".$currenthttpname." (".$currentloiname.") ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==34)
$repheader= "<h2>".$_lang['stHTTPSTATUS']." ".$currenthttpname." (".$currentloiname.") ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==35)
$repheader= "<h2>".$_lang['stONELOGINIPTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==36)
$repheader= "<h2>".$_lang['stONEIPADDRESSLOGINSTRAFFIC']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==37)
$repheader= "<h2>".$_lang['stLOGINSIPTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==38)
$repheader= "<h2>".$_lang['stIPADDRESSLOGINSTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==39)
$repheader= "<h2>".$_lang['stTRAFFICBYPERIODDAY']."</h2>";

if($id==40)
$repheader= "<h2>".$_lang['stTRAFFICBYPERIODDAYNAME']."</h2>";

if($id==41)
$repheader= "<h2>".$_lang['stWHOVISITSITES']." ".$_lang['stFOR']." ".$querydate." ".$_lang['stFROM']." ".$currenthour." ".$_lang['stUNTIL']." ".($currenthour+1)."</h2>";

if($id==42)
$repheader= "<h2>".$_lang['stWHOVISITSITES']." ".$_lang['stFOR']." ".$querydate." ".$_lang['stFROM']." ".$currenthour." ".$_lang['stUNTIL']." ".($currenthour+1)."</h2>";

if($id==43)
$repheader= "<h2>".$_lang['stONELOGINTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate." ".$dayname." ".$_lang['stFROM']." ".$currenthour." ".$_lang['stUNTIL']." ".($currenthour+1)."</h2>";

if($id==44)
$repheader= "<h2>".$_lang['stONELOGINTOPSITESTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate." ".$dayname." ".$_lang['stFROM']." ".$currenthour." ".$_lang['stUNTIL']." ".($currenthour+1)."</h2>";

if($id==45)
$repheader= "<h2>".$_lang['stMIMETYPESTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==46)
$repheader= "<h2>".$_lang['stMIMETYPESTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==47)
$repheader= "<h2>".$_lang['stMIMETYPESTRAFFIC']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==48)
$repheader= "<h2>".$_lang['stDOMAINZONESTRAFFIC']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==49)
$repheader= "<h2>".$_lang['stDASHBOARD']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==50)
$repheader= "<h2>".$_lang['stTRAFFICBYHOURSLOGINS']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==51)
$repheader= "<h2>".$_lang['stTRAFFICBYHOURSIPADDRESS']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==52)
$repheader= "<h2>".$_lang['stTRAFFICBYCATEGORIES']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==53)
$repheader= "<h2>".$_lang['stTRAFFICBYHOURSLOGINSONESITE']." <b>".$currentsite."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==54)
$repheader= "<h2>".$_lang['stTRAFFICBYHOURSIPADDRESSONESITE']." <b>".$currentsite."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==55)
$repheader= "<h2>".$_lang['stPOPULARSITES']." <b>".$currentgroup."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==56)
$repheader= "<h2>".$_lang['stPOPULARSITES']." <b>".$currentlogin."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==57)
$repheader= "<h2>".$_lang['stPOPULARSITES']." <b>".$currentipaddress."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==58)
$repheader= "<h2>".$_lang['stCONTENT']." <b>".$currentmime."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==59)
$repheader= "<h2>".$_lang['stCONTENT']." <b>".$currentmime."</b> <b>".$currentlogin."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==60)
$repheader= "<h2>".$_lang['stCONTENT']." <b>".$currentmime."</b> <b>".$currentipaddress."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==61)
$repheader= "<h2>".$_lang['stDASHBOARD']." <b>".$currentlogin."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==62)
$repheader= "<h2>".$_lang['stDASHBOARD']." <b>".$currentipaddress."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==63)
$repheader= "<h2>".$_lang['stDASHBOARD']." <b>".$currentgroup."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==64)
$repheader= "<h2>".$_lang['stLOGINSTIMEONLINE']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==65)
$repheader= "<h2>".$_lang['stIPADDRESSTIMEONLINE']." ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==66)
$repheader= "<h2>".$_lang['stLOGINBIGFILES']." <b>".$currentlogin."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==67)
$repheader= "<h2>".$_lang['stIPADDRESSBIGFILES']." <b>".$currentipaddress."</b> ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==68)
$repheader= "<h2>".$_lang['stTOPLOGINSWORKINGHOURSTRAFFIC']." (".$workStart1." - ".$workEnd1." | ".$workStart2." - ".$workEnd2.") ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";

if($id==69)
$repheader= "<h2>".$_lang['stTOPIPWORKINGHOURSTRAFFIC']." (".$workStart1." - ".$workEnd1." | ".$workStart2." - ".$workEnd2.") ".$_lang['stFOR']." ".$querydate." ".$dayname."</h2>";


if(!isset($_GET['pdf']) && !isset($_GET['csv'])) {

echo "<table width='100%'>";
echo "<tr>";
echo "<td valign=middle width='80%'>".$repheader."</td>";
}

#если переменных нет, то скажем что они пусты
if(isset($_GET['date2'])) $v_date2 = "&date2=".$_GET['date2']; else $v_date2="";
if(isset($_GET['login'])) $v_login = "&login=".$_GET['login']; else $v_login="";
if(isset($_GET['loginname'])) $v_loginname = "&loginname=".$_GET['loginname']; else $v_loginname="";
if(isset($_GET['ip'])) $v_ip = "&ip=".$_GET['ip']; else $v_ip="";
if(isset($_GET['ipname'])) $v_ipname = "&ipname=".$_GET['ipname']; else $v_ipname="";
if(isset($_GET['httpstatus'])) $v_httpstatus = "&httpstatus=".$_GET['httpstatus']; else $v_httpstatus="";
if(isset($_GET['httpname'])) $v_httpname = "&httpname=".$_GET['httpname']; else $v_httpname="";
if(isset($_GET['loiid'])) $v_loiid = "&loiid=".$_GET['loiid']; else $v_loiid="";
if(isset($_GET['loiname'])) $v_loiname = "&loiname=".$_GET['loiname']; else $v_loiname="";
if(isset($_GET['site'])) $v_site = "&site=".$_GET['site']; else $v_site="";
if(isset($_GET['group'])) $v_group = "&group=".$_GET['group']; else $v_group="";
if(isset($_GET['groupname'])) $v_groupname = "&groupname=".$_GET['groupname']; else $v_groupname="";
if(isset($_GET['typeid'])) $v_typeid = "&typeid=".$_GET['typeid']; else $v_typeid="";

$globalSS['repheader'] = $repheader;
$globalSS['typeid'] = $typeid;


if(!isset($_GET['pdf']) && !isset($_GET['csv'])) {
echo "<td valign=top>&nbsp;&nbsp;<a href=reports.php?srv=".$_GET['srv']."&id=".$_GET['id']."&date=".$_GET['date'].$v_date2."&dom=".$_GET['dom'].$v_login.$v_loginname.$v_ip.$v_ipname.$v_site.$v_group.$v_groupname.$v_typeid.$v_httpstatus.$v_httpname.$v_loiid.$v_loiname."&pdf=1><img src='../img/pdficon.jpg' width=32 height=32 alt=Image title='Generate PDF'></a>
								 <a href=reports.php?srv=".$_GET['srv']."&id=".$_GET['id']."&date=".$_GET['date'].$v_date2."&dom=".$_GET['dom'].$v_login.$v_loginname.$v_ip.$v_ipname.$v_site.$v_group.$v_groupname.$v_typeid.$v_httpstatus.$v_httpname.$v_loiid.$v_loiname."&csv=1><img src='../img/csvicon.png' width=32 height=32 alt=Image title='Generate CSV'></a>";
#Если файл есть в кэше, то покажем иконку - кэшировано. Если нажать на неё, то удалится только один файл этого отчёта
if(file_exists ("".$globalSS['root_dir']."/modules/Cache/data/".doGenerateUniqueNameReport($globalSS['params'])))
echo "							 <a href=reports.php?srv=".$_GET['srv']."&id=".$_GET['id']."&date=".$_GET['date'].$v_date2."&dom=".$_GET['dom'].$v_login.$v_loginname.$v_ip.$v_ipname.$v_site.$v_group.$v_groupname.$v_typeid.$v_httpstatus.$v_httpname.$v_loiid.$v_loiname."&clearcache=1><img src='../img/cached.png' width=35 height=35 alt='Image' title='Clear cache'></a>";

echo "</td>";

echo "</tr>";
echo "</table>";

}
///REPORTS HEADERS END







/////////// LOGINS TRAFFIC REPORT


if($id==1)
{

	$json_result=doGetReportData($globalSS,$queryLoginsTraffic,'template1.php');
	doPrintTable($globalSS,$json_result);

}

/////////// LOGINS TRAFFIC REPORT END


//////////// IPADDRESS TRAFFIC REPORT



if($id==2)
{

	$json_result=doGetReportData($globalSS,$queryIpaddressTraffic,'template2.php');
	doPrintTable($globalSS,$json_result);
}

/////////////// IPADDRESS TRAFFIC REPORT END

/////////////// SITES TRAFFIC REPORT

if($id==3)
{
	$json_result=doGetReportData($globalSS,$querySitesTraffic,'template3.php');
	doPrintTable($globalSS,$json_result);
}

/////////////// SITES TRAFFIC REPORT END

/////////////// TOP SITES TRAFFIC REPORT

if($id==4)
{
	$json_result=doGetReportData($globalSS,$queryTopSitesTraffic,'template3.php');
	doPrintTable($globalSS,$json_result);
}

/////////////// TOP SITES TRAFFIC REPORT END

/////////////// TOP LOGINS TRAFFIC REPORT

if($id==5)
{

	$json_result=doGetReportData($globalSS,$queryTopLoginsTraffic,'template1.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// TOP LOGINS TRAFFIC REPORT END

/////////////// TOP IPADDRESS TRAFFIC REPORT

if($id==6)
{

	$json_result=doGetReportData($globalSS,$queryTopIpTraffic,'template2.php');
	doPrintTable($globalSS,$json_result);
}

/////////////// TOP IPADDRESS TRAFFIC REPORT END


/////////////// TRAFFIC BY HOURS REPORT

if($id==7)
{



//delete graph if exists

foreach (glob("../modules/Chart/pictures/*.png") as $filename) {
	unlink($filename);
 }
 
 
 $json_result=doGetReportData($globalSS,$queryTrafficByHours,'template7.php');
 
 $arrHourMb = array();
 $arrHourMb=doGetArrayData($globalSS,$json_result,1);
 
if(count($arrHourMb)<=1) { $arrHourMb = array_fill(0,24, 0);}

 #вот этот кусок тоже надо убрать. Чтобы рисование график было в отдельной функции
 if($makecsv==0){
	 #соберем данные для графика
	 $userData['charttype']="line";
	 $userData['chartname']="trafficbyhours";
	 $userData['charttitle']="";
	 $userData['arrSerie1']=$arrHourMb;
	 $userData['arrSerie2']="";
	 
	 
	 //create chart
	 $pathtoimage = $grap->drawImage($userData);
	 
	 //display
	 echo $pathtoimage;
	 
	 ///pChart Graph END
	 }
	 
 
 doPrintTable($globalSS,$json_result);
 #так как здесь рисуем график, то функция не только  рисует таблицу, но и возвращает
 


}

/////////////// TRAFFIC BY HOURS REPORT END

/////////// ONE LOGIN TRAFFIC REPORT

if($id==8)
{

	$json_result=doGetReportData($globalSS,$queryOneLoginTraffic,'template4.php');

	doPrintTable($globalSS,$json_result);

if($makepdf==0 && $makecsv==0)
echo "<script>UpdateLeftMenu(1);</script>";
}

/////////// ONE LOGIN TRAFFIC REPORT END

/////////////// TOP SITES FOR ONE LOGIN TRAFFIC REPORT

if($id==9)
{

	$json_result=doGetReportData($globalSS,$queryOneLoginTopSitesTraffic,'template4.php');
	doPrintTable($globalSS,$json_result);
}

/////////////// TOP SITES FOR ONE LOGIN TRAFFIC REPORT END

/////////////// TRAFFIC BY HOURS FOR ONE LOGIN REPORT

if($id==10)
{



//delete graph if exists

foreach (glob("../modules/Chart/pictures/*.png") as $filename) {
	unlink($filename);
 }
 
 
 $json_result=doGetReportData($globalSS,$queryOneLoginTrafficByHours,'template8.php');
 
 $arrHourMb = array();
 $arrHourMb=doGetArrayData($globalSS,$json_result,1);
 
 #вот этот кусок тоже надо убрать. Чтобы рисование график было в отдельной функции
 if($makecsv==0){
	 #соберем данные для графика
	 $userData['charttype']="line";
	 $userData['chartname']="trafficbyhours";
	 $userData['charttitle']="";
	 $userData['arrSerie1']=$arrHourMb;
	 $userData['arrSerie2']="";
	 
	 
	 //create chart
	 $pathtoimage = $grap->drawImage($userData);
	 
	 //display
	 echo $pathtoimage;
	 
	 ///pChart Graph END
	 }
	 
 
 doPrintTable($globalSS,$json_result);
 #так как здесь рисуем график, то функция не только  рисует таблицу, но и возвращает
 


}

/////////////// TRAFFIC BY HOURS FOR ONE LOGIN REPORT END

/////////// ONE IPADDRESS TRAFFIC REPORT

if($id==11)
{

	$json_result=doGetReportData($globalSS,$queryOneIpaddressTraffic,'template4.php');

	doPrintTable($globalSS,$json_result);
	if($makepdf==0 && $makecsv==0)
	echo "<script>UpdateLeftMenu(2);</script>";
}

/////////// ONE IPADDRESS TRAFFIC REPORT END

/////////////// TOP SITES FOR ONE IPADDRESS TRAFFIC REPORT

if($id==12)
{
	$json_result=doGetReportData($globalSS,$queryOneIpaddressTopSitesTraffic,'template4.php');
	doPrintTable($globalSS,$json_result);
}

/////////////// TOP SITES FOR ONE IPADDRESS TRAFFIC REPORT END

/////////////// TRAFFIC BY HOURS FOR ONE IPADDRESS REPORT

if($id==13)
{

//delete graph if exists

foreach (glob("../modules/Chart/pictures/*.png") as $filename) {
	unlink($filename);
 }
 
 
 $json_result=doGetReportData($globalSS,$queryOneIpaddressTrafficByHours,'template8.php');
 
 $arrHourMb = array();
 $arrHourMb=doGetArrayData($globalSS,$json_result,1);
 
 #вот этот кусок тоже надо убрать. Чтобы рисование график было в отдельной функции
 if($makecsv==0){
	 #соберем данные для графика
	 $userData['charttype']="line";
	 $userData['chartname']="trafficbyhours";
	 $userData['charttitle']="";
	 $userData['arrSerie1']=$arrHourMb;
	 $userData['arrSerie2']="";
	 
	 
	 //create chart
	 $pathtoimage = $grap->drawImage($userData);
	 
	 //display
	 echo $pathtoimage;
	 
	 ///pChart Graph END
	 }
	 
 
 doPrintTable($globalSS,$json_result);
 #так как здесь рисуем график, то функция не только  рисует таблицу, но и возвращает
 


}

/////////////// TRAFFIC BY HOURS FOR ONE IPADDRESS REPORT END

/////////// LOGINS TRAFFIC WIDE REPORT

if($id==14)
{

	$json_result=doGetReportData($globalSS,$queryLoginsTrafficWide,'template12.php');
	doPrintTable($globalSS,$json_result);

}

/////////// LOGINS TRAFFIC WIDE REPORT END

/////////// IPADDRESS TRAFFIC WIDE REPORT

if($id==15)
{
	$json_result=doGetReportData($globalSS,$queryIpaddressTrafficWide,'template13.php');
	doPrintTable($globalSS,$json_result);


}

/////////// IPADDRESS TRAFFIC WIDE REPORT END

//////////// IPADDRESS TRAFFIC REPORT WITH RESOLVE

if($id==16)
{

	$json_result=doGetReportData($globalSS,$queryIpaddressTrafficWithResolve,'template9.php');
 
	doPrintTable($globalSS,$json_result);

}

/////////////// IPADDRESS TRAFFIC REPORT WITH RESOLVE END

/////////////// POPULAR SITES REPORT

if($id==17)
{

	$json_result=doGetReportData($globalSS,$queryPopularSites,'template5.php');
	doPrintTable($globalSS,$json_result);
}

/////////////// POPULAR SITES REPORT END


/////////////// WHO LOGIN VISIT POPULAR SITE REPORT

if($id==18)
{

	$json_result=doGetReportData($globalSS,$queryWhoVisitPopularSiteLogin,'template1.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// WHO VISIT POPULAR SITE LOGIN REPORT END

/////////////// WHO IPADDRESS VISIT POPULAR SITE REPORT

if($id==19)
{
	$json_result=doGetReportData($globalSS,$queryWhoVisitPopularSiteIpaddress,'template2.php');
	doPrintTable($globalSS,$json_result);
}

/////////////// WHO VISIT POPULAR SITE IPADDRESS REPORT END

/////////////// WHO DOWNLOAD BIG FILES REPORT

if($id==20)
{

	$json_result=doGetReportData($globalSS,$queryWhoDownloadBigFiles,'template6.php');
	doPrintTable($globalSS,$json_result);
}

/////////////// WHO DOWNLOAD BIG FILES REPORT END

/////////////// TRAFFIC BY PERIOD REPORT

if($id==21)
{

	$json_result=doGetReportData($globalSS,$queryTrafficByPeriod,'template10.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// TRAFFIC BY PERIOD REPORT END

/////////////// VISITING WEBSITE BY TIME LOGIN REPORT

if($id==22)
{

	$json_result=doGetReportData($globalSS,$queryVisitingWebsiteByTimeLogin,'template11.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// VISITING WEBSITE BY TIME REPORT LOGIN END

/////////////// VISITING WEBSITE BY TIME IPADDRESS REPORT

if($id==23)
{

	$json_result=doGetReportData($globalSS,$queryVisitingWebsiteByTimeIpaddress,'template11.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// VISITING WEBSITE BY TIME REPORT IPADDRESS END

/////////// GROUPS TRAFFIC REPORT

if($id==24)
{
	$json_result=doGetReportData($globalSS,$queryGroupsTraffic,'template14.php');
	doPrintTable($globalSS,$json_result);

}

/////////// GROUPS TRAFFIC REPORT END



/////////// ONE GROUP LOGINS/IPADDRESS TRAFFIC REPORT

if($id==25)
{
	$globalSS['typeid'] = $typeid;
	$json_result=doGetReportData($globalSS,$queryOneGroupTraffic,'template15.php');
	doPrintTable($globalSS,$json_result);

if($makepdf==0 && $makecsv==0)
echo "<script>UpdateLeftMenu(4);</script>";
}

/////////// ONE GROUP LOGINS/IPADDRESS TRAFFIC REPORT END


/////////// ONE GROUP LOGINS/IPADDRESS TRAFFIC WIDE REPORT

if($id==26)
{

	$json_result=doGetReportData($globalSS,$queryOneGroupTrafficWide,'template16.php');
	doPrintTable($globalSS,$json_result);


}

/////////// ONE GROUP LOGINS/IPADDRESS TRAFFIC WIDE REPORT END


/////////////// ONE GROUP TOP SITES TRAFFIC REPORT

if($id==27)
{

	$json_result=doGetReportData($globalSS,$queryOneGroupTopSitesTraffic,'template4.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// ONE GROUP TOP SITES TRAFFIC REPORT END


/////////////// ONE GROUP TRAFFIC BY HOURS REPORT

if($id==28)
{



//delete graph if exists

foreach (glob("../modules/Chart/pictures/*.png") as $filename) {
	unlink($filename);
 }
 
 
 $json_result=doGetReportData($globalSS,$queryOneGroupTrafficByHours,'template8.php');
 
 $arrHourMb = array();
 $arrHourMb=doGetArrayData($globalSS,$json_result,1);
 
 #вот этот кусок тоже надо убрать. Чтобы рисование график было в отдельной функции
 if($makecsv==0){
	 #соберем данные для графика
	 $userData['charttype']="line";
	 $userData['chartname']="trafficbyhours";
	 $userData['charttitle']="";
	 $userData['arrSerie1']=$arrHourMb;
	 $userData['arrSerie2']="";
	 
	 
	 //create chart
	 $pathtoimage = $grap->drawImage($userData);
	 
	 //display
	 echo $pathtoimage;
	 
	 ///pChart Graph END
	 }
	 
 
 doPrintTable($globalSS,$json_result);
 

}

/////////////// ONE GROUP TRAFFIC BY HOURS REPORT END

/////////////// ONE GROUP WHO DOWNLOAD BIG FILES REPORT

if($id==29)
{	
	$json_result=doGetReportData($globalSS,$queryOneGroupWhoDownloadBigFiles,'template17.php');
	doPrintTable($globalSS,$json_result);
}

/////////////// ONE GROUP WHO DOWNLOAD BIG FILES REPORT END

/////////// HTTP STATUS TRAFFIC REPORT

if($id==30)
{
	$json_result=doGetReportData($globalSS,$queryHttpStatus,'template18.php');
	doPrintTable($globalSS,$json_result);

}

/////////// HTTP STATUS REPORT END

/////////// LOGINS HTTP STATUS TRAFFIC REPORT

if($id==31)
{

	$json_result=doGetReportData($globalSS,$queryLoginsHttpStatus,'template19.php');
	doPrintTable($globalSS,$json_result);

}

/////////// LOGINS HTTP STATUS REPORT END

/////////// IPADDRESS HTTP STATUS TRAFFIC REPORT

if($id==32)
{

	$json_result=doGetReportData($globalSS,$queryIpaddressHttpStatus,'template20.php');
	doPrintTable($globalSS,$json_result);

}

/////////// IPADDRESS HTTP STATUS REPORT END

/////////// ONE LOGIN ONE HTTP STATUS TRAFFIC REPORT

if($id==33) //неработает пока
{
	echo "Отчёт не работает. Напомните мне, я возможно забыл.";
/*
	echo "
<table id=report_table_id_33 class=datatable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stDATE']."
    </th>
    <th>
    ".$_lang['stSITE']."
    </th>";

$result=$ssq->query($queryOneLoginOneHttpStatus);
$numrow=1;
$totalmb=0;
while ($line = $ssq->fetch_array($result)) {
echo "<tr>";
echo "<td>".$numrow."</td>";

echo "<td>".$line[0]."</td>";
echo "<td>".$line[1]."</td>";
echo "</tr>";
$numrow++;
    }
echo "</tbody></table>";
$ssq->free_result($result);*/
}

/////////// ONE LOGIN ONE HTTP STATUS REPORT END

/////////// ONE IPADDRESS ONE HTTP STATUS TRAFFIC REPORT

if($id==34) //не работает пока
{
	echo "Отчёт не работает. Напомните мне, я возможно забыл.";

/*	echo "
<table id=report_table_id_34 class=datatable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stDATE']."
    </th>
    <th>
    ".$_lang['stSITE']."
    </th>";

$result=$ssq->query($queryOneIpaddressOneHttpStatus);
$numrow=1;
$totalmb=0;
while ($line = $ssq->fetch_array($result)) {
echo "<tr>";
echo "<td>".$numrow."</td>";

echo "<td>".$line[0]."</td>";
echo "<td>".$line[1]."</td>";
echo "</tr>";
$numrow++;
    }
echo "</tbody></table>";*/
}

/////////// ONE IPADDRESS ONE HTTP STATUS REPORT END

/////////// LOGIN IPADRESSES TRAFFIC REPORT

if($id==35)
{

	$json_result=doGetReportData($globalSS,$queryOneLoginIpTraffic,'template2.php');
	doPrintTable($globalSS,$json_result);


if($makepdf==0 && $makecsv==0)
echo "<script>UpdateLeftMenu(1);</script>";
}


/////////// LOGIN IPADRESSES TRAFFIC REPORT END

/////////// IPADRESS LOGIN TRAFFIC REPORT

if($id==36)
{

	$json_result=doGetReportData($globalSS,$queryOneIpaddressLoginsTraffic,'template1.php');
	doPrintTable($globalSS,$json_result);

if($makepdf==0 && $makecsv==0)
echo "<script>UpdateLeftMenu(2);</script>";
}


/////////// IPADRESS LOGINS TRAFFIC REPORT END

/////////// COUNT IPADDRESS ON LOGINS REPORT

if($id==37)
{
	$json_result=doGetReportData($globalSS,$queryCountIpaddressOnLogins,'template21.php');
	doPrintTable($globalSS,$json_result);

}

/////////// COUNT IPADDRESS ON LOGINS REPORT END

/////////// COUNT LOGINS ON IPADDRESS REPORT

if($id==38)
{

	$json_result=doGetReportData($globalSS,$queryCountLoginsOnIpaddress,'template22.php');
	doPrintTable($globalSS,$json_result);

}

/////////// COUNT LOGINS ON IPADDRESS REPORT END

/////////////// TRAFFIC BY PERIOD PER DAY REPORT

if($id==39)
{

	$json_result=doGetReportData($globalSS,$queryTrafficByPeriodDay,'template23.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// TRAFFIC BY PERIOD PER DAY REPORT END

/////////////// TRAFFIC BY PERIOD PER DAYNAME REPORT

if($id==40)
{

	$json_result=doGetReportData($globalSS,$queryTrafficByPeriodDayname,'template24.php');
	doPrintTable($globalSS,$json_result);


}

/////////////// TRAFFIC BY PERIOD PER DAYNAME REPORT END

/////////////// WHO LOGIN VISIT SITE ONE HOUR REPORT

if($id==41)
{

	$json_result=doGetReportData($globalSS,$queryWhoVisitSiteOneHourLogin,'template25.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// WHO LOGIN VISIT SITE ONE HOUR REPORT END

/////////////// WHO IPADDRESS VISIT SITE ONE HOUR REPORT

if($id==42)
{

	$json_result=doGetReportData($globalSS,$queryWhoVisitSiteOneHourIpaddress,'template26.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// WHO IPADDRESS VISIT SITE ONE HOUR REPORT END

/////////// ONE LOGIN ONE HOUR TRAFFIC REPORT

if($id==43)
{

	$json_result=doGetReportData($globalSS,$queryOneLoginOneHourTraffic,'template4.php');
	doPrintTable($globalSS,$json_result);

if($makepdf==0 && $makecsv==0)
echo "<script>UpdateLeftMenu(1);</script>";
}

/////////// ONE LOGIN ONE HOUR TRAFFIC REPORT END

/////////// ONE IPADDRESS ONE HOUR TRAFFIC REPORT

if($id==44)
{

	$json_result=doGetReportData($globalSS,$queryOneIpaddressOneHourTraffic,'template4.php');
	doPrintTable($globalSS,$json_result);

if($makepdf==0 and $makecsv==0)
echo "<script>UpdateLeftMenu(2);</script>";
}

/////////// ONE IPADDRESS ONE HOUR TRAFFIC REPORT END

/////////////// MIME TYPES TRAFFIC REPORT

if($id==45)
{

	$json_result=doGetReportData($globalSS,$queryMimeTypesTraffic,'template27.php');
	doPrintTable($globalSS,$json_result);


}

/////////////// MIME TYPES TRAFFIC REPORT END

/////////////// ONE LOGIN MIME TYPES TRAFFIC REPORT

if($id==46)
{

	$json_result=doGetReportData($globalSS,$queryOneLoginMimeTypesTraffic,'template28.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// ONE LOGIN MIME TYPES TRAFFIC REPORT END


/////////////// ONE IPADDRESS MIME TYPES TRAFFIC REPORT

if($id==47)
{

	$json_result=doGetReportData($globalSS,$queryOneIpaddressMimeTypesTraffic,'template29.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// ONE IPADDRESS MIME TYPES TRAFFIC REPORT END

/////////////// DOMAIN ZONES TRAFFIC REPORT

if($id==48)
{

	$json_result=doGetReportData($globalSS,$queryDomainZonesTraffic,'template30.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// DOMAIN ZONES TRAFFIC REPORT END

////////////// DASHBOARD REPORT

if($id==49)
{

//delete graph if exists

foreach (glob("../modules/Chart/pictures/*.png") as $filename) {
   unlink($filename);
}

#Чтобы корректно работать прям тут присвоим другие номера отчёту.
#То есть DASHBOARD это в конечном счете те же самые отчеты. Только все разом. 
#Так что если в Dashboard человек открыл уже по времени, то он быстро соберется. Ну и наоборот.
$globalSS['params']['idReport']=7;
$json_result=doGetReportData($globalSS,$queryTrafficByHours,'template7.php');
 
$arrHourMb = array();
$arrHourMb=doGetArrayData($globalSS,$json_result,1);

#вот этот кусок тоже надо убрать. Чтобы рисование график было в отдельной функции

	#соберем данные для графика
	$userData['charttype']="line";
	$userData['chartname']="trafficbyhours";
	$userData['charttitle']="";
	$userData['arrSerie1']=$arrHourMb;
	$userData['arrSerie2']="";
	
	
	//create chart
	$pathtoimage = $grap->drawImage($userData);
	
	//display
	echo $pathtoimage;
	
	///pChart Graph END
	



///top logins



$globalSS['params']['idReport']=5;
$json_result=doGetReportData($globalSS,$queryTopLoginsTraffic,'template1.php');

$arrLine0 = array(); //logins
$arrLine0=doGetArrayData($globalSS,$json_result,1);

$arrLine1 = array(); //megabytes
$arrLine1=doGetArrayData($globalSS,$json_result,2);

$numrow=1;
while ($numrow<$countTopLoginLimit)
{
	if (!isset($arrLine0[$numrow-1])) {
		$arrLine0[$numrow-1]="NO DATA";
		$arrLine1[$numrow-1]=0;
	}
$numrow++;
}

//top logins end


/// pchart top Logins

 
$userData['charttype']="pie";
$userData['chartname']="toplogins";
$userData['charttitle']=$_lang['stTOPLOGINSTRAFFIC']." (".$_lang['stTOP']."-".$countTopLoginLimit.")";
$userData['arrSerie1']=$arrLine1;
$userData['arrSerie2']=$arrLine0;

//create chart
$pathtoimage = $grap->drawImage($userData);

//display
echo $pathtoimage;

/// pchart top logins end

///top ipaddress

$globalSS['params']['idReport']=6;
$json_result=doGetReportData($globalSS,$queryTopIpTraffic,'template2.php');

$arrLine0 = array(); //ipaddress
$arrLine0=doGetArrayData($globalSS,$json_result,1);

$arrLine1 = array(); //megabytes
$arrLine1=doGetArrayData($globalSS,$json_result,2);

$numrow=1;
while ($numrow<$countTopIpLimit)
{
	if ($arrLine0[$numrow-1]=="") {
		$arrLine0[$numrow-1]="NO DATA";
		$arrLine1[$numrow-1]=0;
	}
$numrow++;
}


//top ip end

/// pchart top ip

$userData['charttype']="pie";
$userData['chartname']="topips";
$userData['charttitle']=$_lang['stTOPIPTRAFFIC']." (".$_lang['stTOP']."-".$countTopIpLimit.")";
$userData['arrSerie1']=$arrLine1;
$userData['arrSerie2']=$arrLine0;

//create chart
$pathtoimage = $grap->drawImage($userData);

//display
echo $pathtoimage;

/// pchart top ip end

///top sites
$globalSS['params']['idReport']=4;
$json_result=doGetReportData($globalSS,$queryTopSitesTraffic,'template3.php');

$arrLine0 = array(); //sites
$arrLine0=doGetArrayData($globalSS,$json_result,1);

$arrLine1 = array(); //megabytes
$arrLine1=doGetArrayData($globalSS,$json_result,2);

$numrow=1;
while ($numrow<$countTopSitesLimit)
{
	if ($arrLine0[$numrow-1]=="") {
		$arrLine0[$numrow-1]="NO DATA";
		$arrLine1[$numrow-1]=0;
	}
$numrow++;
}


//top IP end

/// pchart top sites


$userData['charttype']="pie";
$userData['chartname']="topsites";
$userData['charttitle']=$_lang['stTOPSITESTRAFFIC']." (".$_lang['stTOP']."-".$countTopSitesLimit.")";
$userData['arrSerie1']=$arrLine1;
$userData['arrSerie2']=$arrLine0;

//create chart
$pathtoimage = $grap->drawImage($userData);

//display
echo $pathtoimage;

/// pchart top sites end

///top popular
$globalSS['params']['idReport']=17;
$json_result=doGetReportData($globalSS,$queryPopularSites,'template5.php');

$arrLine0 = array(); //sites
$arrLine0=doGetArrayData($globalSS,$json_result,1);

$arrLine1 = array(); //megabytes
$arrLine1=doGetArrayData($globalSS,$json_result,2);

$numrow=1;
while ($numrow<$countPopularSitesLimit)
{
	if ($arrLine0[$numrow-1]=="") {
		$arrLine0[$numrow-1]="NO DATA";
		$arrLine1[$numrow-1]=0;
	}
$numrow++;
}


//top popular end


/// pchart top popular


$userData['charttype']="pie";
$userData['chartname']="toppop";
$userData['charttitle']=$_lang['stPOPULARSITES']." (".$_lang['stTOP']."-".$countPopularSitesLimit.")";
$userData['arrSerie1']=$arrLine1;
$userData['arrSerie2']=$arrLine0;

//create chart
$pathtoimage = $grap->drawImage($userData);

//display
echo $pathtoimage;

/// pchart top popular end


}

/////////////// DASHBOARD REPORT END


/////////////// TRAFFIC BY HOURS LOGINS REPORT

if($id==50)
{
	$globalSS['typeid']=0; #костыль
	$json_result=doGetReportData($globalSS,$queryTrafficByHoursLogins,'template31.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// TRAFFIC BY HOURS LOGINS REPORT END


/////////////// TRAFFIC BY HOURS IPADDRESS REPORT

if($id==51)
{
	$globalSS['typeid']=1; #костыль
	$json_result=doGetReportData($globalSS,$queryTrafficByHoursIpaddress,'template31.php');
	doPrintTable($globalSS,$json_result);


}

/////////////// TRAFFIC BY HOURS IPADDRESS REPORT END

/////////////// TRAFFIC BY CATEGORY REPORT не работает!!!

if($id==52)
{
echo "
<table id=report_table_id_48 class=datatable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stCATEGORY']."
    </th>
    <th>
    ".$_lang['stREQUESTS']."
    </th>
    <th>
    ".$_lang['stMEGABYTES']."
    </th>
</tr>
";
$result=$ssq->query($queryCategorySitesTraffic);
$numrow=1;
$totalmb=0;
while ($line = $ssq->fetch_array($result)) {
echo "<tr>";
echo "<td>".$numrow."</td>";


if($enableUseiconv==1)
$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));

echo "<td>".$line[0]."</td>";
echo "<td>".$line[1]."</td>";
$line[2]=$line[2] / $oneMegabyte;
$line[2]=sprintf("%f",$line[2]); //disable scientific format e.g. 5E-10
echo "<td>".$line[2]."</td>";
echo "</tr>";
$numrow++;
}

$ssq->free_result($result);
echo "<tr class=sortbottom>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>";
echo "</table>";

}

/////////////// TRAFFIC BY CATEGORY REPORT END

/////////////// TRAFFIC BY HOURS LOGINS ONE SITE REPORT

if($id==53)
{

	$json_result=doGetReportData($globalSS,$queryTrafficByHoursLoginsOneSite,'template31.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// TRAFFIC BY HOURS LOGINS ONE SITE REPORT END


/////////////// TRAFFIC BY HOURS IPADDRESS ONE SITE REPORT

if($id==54)
{

	$json_result=doGetReportData($globalSS,$queryTrafficByHoursIpaddressOneSite,'template31.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// TRAFFIC BY HOURS IPADDRESS REPORT ONE SITE END

/////////////// GROUP POPULAR SITES REPORT

if($id==55)
{

	$json_result=doGetReportData($globalSS,$queryOneGroupPopularSites,'template5.php');
	doPrintTable($globalSS,$json_result);


}

/////////////// ONE GROUP POPULAR SITES REPORT END

/////////////// ONE LOGIN POPULAR SITES REPORT

if($id==56)
{

	$json_result=doGetReportData($globalSS,$queryOneLoginPopularSites,'template32.php');
	doPrintTable($globalSS,$json_result);


}

/////////////// ONE LOGIN POPULAR SITES REPORT END

/////////////// ONE IPADDRESS POPULAR SITES REPORT

if($id==57)
{

	$json_result=doGetReportData($globalSS,$queryOneIpaddressPopularSites,'template32.php');
	doPrintTable($globalSS,$json_result);


}

/////////////// ONE IPADDRESS POPULAR SITES REPORT END

/////////////// ONE MIME SITES REPORT

if($id==58)
{

	$json_result=doGetReportData($globalSS,$queryOneMime,'template17.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// ONE MIME SITES REPORT END

/////////////// ONE MIME LOGIN SITES REPORT

if($id==59)
{

	$json_result=doGetReportData($globalSS,$queryOneMimeOneLogin,'template33.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// ONE MIME LOGIN SITES REPORT END


/////////////// ONE MIME IPADDRESS SITES REPORT

if($id==60)
{

	$json_result=doGetReportData($globalSS,$queryOneMimeOneIpaddress,'template33.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// ONE MIME IPADDRESS SITES REPORT END

////////////// ONE LOGIN DASHBOARD REPORT

if($id==61)
{


//delete graph if exists

foreach (glob("../modules/Chart/pictures/*.png") as $filename) {
	unlink($filename);
 }
 
 #Чтобы корректно работать прям тут присвоим другие номера отчёту.
 #То есть DASHBOARD это в конечном счете те же самые отчеты. Только все разом. 
 #Так что если в Dashboard человек открыл уже по времени, то он быстро соберется. Ну и наоборот.
 $globalSS['params']['idReport']=10;
 $json_result=doGetReportData($globalSS,$queryOneLoginTrafficByHours,'template7.php');
  
 $arrHourMb = array();
 $arrHourMb=doGetArrayData($globalSS,$json_result,1);
 
 #вот этот кусок тоже надо убрать. Чтобы рисование график было в отдельной функции
 
	 #соберем данные для графика
	 $userData['charttype']="line";
	 $userData['chartname']="trafficbyhours";
	 $userData['charttitle']="";
	 $userData['arrSerie1']=$arrHourMb;
	 $userData['arrSerie2']="";
	 
	 
	 //create chart
	 $pathtoimage = $grap->drawImage($userData);
	 
	 //display
	 echo $pathtoimage;
	 
	 ///pChart Graph END
	 
 
 

 ///top sites
 $globalSS['params']['idReport']=9;
 $json_result=doGetReportData($globalSS,$queryOneLoginTopSitesTraffic,'template3.php');
 
 $arrLine0 = array(); //sites
 $arrLine0=doGetArrayData($globalSS,$json_result,1);
 
 $arrLine1 = array(); //megabytes
 $arrLine1=doGetArrayData($globalSS,$json_result,2);
 
 $numrow=1;
 while ($numrow<$countTopSitesLimit)
 {
	 if ($arrLine0[$numrow-1]=="") {
		 $arrLine0[$numrow-1]="NO DATA";
		 $arrLine1[$numrow-1]=0;
	 }
 $numrow++;
 }
 
 
 //top IP end
 
 /// pchart top sites
 
 
 $userData['charttype']="pie";
 $userData['chartname']="topsites";
 $userData['charttitle']=$_lang['stTOPSITESTRAFFIC']." (".$_lang['stTOP']."-".$countTopSitesLimit.")";
 $userData['arrSerie1']=$arrLine1;
 $userData['arrSerie2']=$arrLine0;
 
 //create chart
 $pathtoimage = $grap->drawImage($userData);
 
 //display
 echo $pathtoimage;
 
 /// pchart top sites end
 
 ///top popular
 $globalSS['params']['idReport']=56;
 $json_result=doGetReportData($globalSS,$queryOneLoginPopularSites,'template5.php');
 
 $arrLine0 = array(); //sites
 $arrLine0=doGetArrayData($globalSS,$json_result,1);
 
 $arrLine1 = array(); //megabytes
 $arrLine1=doGetArrayData($globalSS,$json_result,2);

 $numrow=1;
 while ($numrow<$countPopularSitesLimit)
 {
	 if ($arrLine0[$numrow-1]=="") {
		 $arrLine0[$numrow-1]="NO DATA";
		 $arrLine1[$numrow-1]=0;
	 }
 $numrow++;
 }
 
 
 //top popular end
 
 
 /// pchart top popular
 
 
 $userData['charttype']="pie";
 $userData['chartname']="toppop";
 $userData['charttitle']=$_lang['stPOPULARSITES']." (".$_lang['stTOP']."-".$countPopularSitesLimit.")";
 $userData['arrSerie1']=$arrLine1;
 $userData['arrSerie2']=$arrLine0;
 
 //create chart
 $pathtoimage = $grap->drawImage($userData);
 
 //display
 echo $pathtoimage;
 
 /// pchart top popular end

}

/////////////// ONE LOGIN DASHBOARD REPORT END


////////////// ONE IPADDRESS DASHBOARD REPORT

if($id==62)
{


//delete graph if exists

foreach (glob("../modules/Chart/pictures/*.png") as $filename) {
	unlink($filename);
 }
 
 #Чтобы корректно работать прям тут присвоим другие номера отчёту.
 #То есть DASHBOARD это в конечном счете те же самые отчеты. Только все разом. 
 #Так что если в Dashboard человек открыл уже по времени, то он быстро соберется. Ну и наоборот.
 $globalSS['params']['idReport']=13;
 $json_result=doGetReportData($globalSS,$queryOneIpaddressTrafficByHours,'template7.php');
  
 $arrHourMb = array();
 $arrHourMb=doGetArrayData($globalSS,$json_result,1);
 
 #вот этот кусок тоже надо убрать. Чтобы рисование график было в отдельной функции
 
	 #соберем данные для графика
	 $userData['charttype']="line";
	 $userData['chartname']="trafficbyhours";
	 $userData['charttitle']="";
	 $userData['arrSerie1']=$arrHourMb;
	 $userData['arrSerie2']="";
	 
	 
	 //create chart
	 $pathtoimage = $grap->drawImage($userData);
	 
	 //display
	 echo $pathtoimage;
	 
	 ///pChart Graph END
	 
 
 

 ///top sites
 $globalSS['params']['idReport']=12;
 $json_result=doGetReportData($globalSS,$queryOneIpaddressTopSitesTraffic,'template3.php');
 
 $arrLine0 = array(); //sites
 $arrLine0=doGetArrayData($globalSS,$json_result,1);
 
 $arrLine1 = array(); //megabytes
 $arrLine1=doGetArrayData($globalSS,$json_result,2);
 
 $numrow=1;
 while ($numrow<$countTopSitesLimit)
 {
	 if ($arrLine0[$numrow-1]=="") {
		 $arrLine0[$numrow-1]="NO DATA";
		 $arrLine1[$numrow-1]=0;
	 }
 $numrow++;
 }
 
 
 //top IP end
 
 /// pchart top sites
 
 
 $userData['charttype']="pie";
 $userData['chartname']="topsites";
 $userData['charttitle']=$_lang['stTOPSITESTRAFFIC']." (".$_lang['stTOP']."-".$countTopSitesLimit.")";
 $userData['arrSerie1']=$arrLine1;
 $userData['arrSerie2']=$arrLine0;
 
 //create chart
 $pathtoimage = $grap->drawImage($userData);
 
 //display
 echo $pathtoimage;
 
 /// pchart top sites end
 
 ///top popular
 $globalSS['params']['idReport']=57;
 $json_result=doGetReportData($globalSS,$queryOneIpaddressPopularSites,'template5.php');
 
 $arrLine0 = array(); //sites
 $arrLine0=doGetArrayData($globalSS,$json_result,1);
 
 $arrLine1 = array(); //megabytes
 $arrLine1=doGetArrayData($globalSS,$json_result,2);

 $numrow=1;
 while ($numrow<$countPopularSitesLimit)
 {
	 if ($arrLine0[$numrow-1]=="") {
		 $arrLine0[$numrow-1]="NO DATA";
		 $arrLine1[$numrow-1]=0;
	 }
 $numrow++;
 }
 
 
 //top popular end
 
 
 /// pchart top popular
 
 
 $userData['charttype']="pie";
 $userData['chartname']="toppop";
 $userData['charttitle']=$_lang['stPOPULARSITES']." (".$_lang['stTOP']."-".$countPopularSitesLimit.")";
 $userData['arrSerie1']=$arrLine1;
 $userData['arrSerie2']=$arrLine0;
 
 //create chart
 $pathtoimage = $grap->drawImage($userData);
 
 //display
 echo $pathtoimage;
 
 /// pchart top popular end



}

/////////////// ONE IPADDRESS DASHBOARD REPORT END


////////////// ONE GROUP DASHBOARD REPORT

if($id==63)
{


//delete graph if exists

foreach (glob("../modules/Chart/pictures/*.png") as $filename) {
	unlink($filename);
 }
 
 #Чтобы корректно работать прям тут присвоим другие номера отчёту.
 #То есть DASHBOARD это в конечном счете те же самые отчеты. Только все разом. 
 #Так что если в Dashboard человек открыл уже по времени, то он быстро соберется. Ну и наоборот.
 $globalSS['params']['idReport']=28;
 $json_result=doGetReportData($globalSS,$queryOneGroupTrafficByHours,'template7.php');
  
 $arrHourMb = array();
 $arrHourMb=doGetArrayData($globalSS,$json_result,1);
 
 #вот этот кусок тоже надо убрать. Чтобы рисование график было в отдельной функции
 
	 #соберем данные для графика
	 $userData['charttype']="line";
	 $userData['chartname']="trafficbyhours";
	 $userData['charttitle']="";
	 $userData['arrSerie1']=$arrHourMb;
	 $userData['arrSerie2']="";
	 
	 
	 //create chart
	 $pathtoimage = $grap->drawImage($userData);
	 
	 //display
	 echo $pathtoimage;
	 
	 ///pChart Graph END
	 
 
 

 ///top sites
 $globalSS['params']['idReport']=27;
 $json_result=doGetReportData($globalSS,$queryOneGroupTopSitesTraffic,'template3.php');
 
 $arrLine0 = array(); //sites
 $arrLine0=doGetArrayData($globalSS,$json_result,1);
 
 $arrLine1 = array(); //megabytes
 $arrLine1=doGetArrayData($globalSS,$json_result,2);
 
 $numrow=1;
 while ($numrow<$countTopSitesLimit)
 {
	 if ($arrLine0[$numrow-1]=="") {
		 $arrLine0[$numrow-1]="NO DATA";
		 $arrLine1[$numrow-1]=0;
	 }
 $numrow++;
 }
 
 
 //top IP end
 
 /// pchart top sites
 
 
 $userData['charttype']="pie";
 $userData['chartname']="topsites";
 $userData['charttitle']=$_lang['stTOPSITESTRAFFIC']." (".$_lang['stTOP']."-".$countTopSitesLimit.")";
 $userData['arrSerie1']=$arrLine1;
 $userData['arrSerie2']=$arrLine0;
 
 //create chart
 $pathtoimage = $grap->drawImage($userData);
 
 //display
 echo $pathtoimage;
 
 /// pchart top sites end
 
 ///top popular
 $globalSS['params']['idReport']=55;
 $json_result=doGetReportData($globalSS,$queryOneGroupPopularSites,'template5.php');
 
 $arrLine0 = array(); //sites
 $arrLine0=doGetArrayData($globalSS,$json_result,1);
 
 $arrLine1 = array(); //megabytes
 $arrLine1=doGetArrayData($globalSS,$json_result,2);

 $numrow=1;
 while ($numrow<$countPopularSitesLimit)
 {
	 if ($arrLine0[$numrow-1]=="") {
		 $arrLine0[$numrow-1]="NO DATA";
		 $arrLine1[$numrow-1]=0;
	 }
 $numrow++;
 }
 
 
 //top popular end
 
 
 /// pchart top popular
 
 
 $userData['charttype']="pie";
 $userData['chartname']="toppop";
 $userData['charttitle']=$_lang['stPOPULARSITES']." (".$_lang['stTOP']."-".$countPopularSitesLimit.")";
 $userData['arrSerie1']=$arrLine1;
 $userData['arrSerie2']=$arrLine0;
 
 //create chart
 $pathtoimage = $grap->drawImage($userData);
 
 //display
 echo $pathtoimage;
 
 /// pchart top popular end



}

/////////////// ONE GROUP DASHBOARD REPORT END


/////////// LOGINS TIME ONLINE REPORT


if($id==64)
{

	$json_result=doGetReportData($globalSS,$queryLoginsTimeOnline,'template34.php');
	doPrintTable($globalSS,$json_result);


}

/////////// LOGINS TIME ONLINE REPORT END


//////////// IPADDRESS TIME ONLINE REPORT



if($id==65)
{
	$json_result=doGetReportData($globalSS,$queryIpaddressTimeOnline,'template35.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// IPADDRESS TIME ONLINE REPORT END


/////////////// LOGIN DOWNLOAD BIG FILES REPORT

if($id==66)
{

	$json_result=doGetReportData($globalSS,$queryLoginDownloadBigFiles,'template6.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// LOGIN DOWNLOAD BIG FILES REPORT END

/////////////// IPADDRESS DOWNLOAD BIG FILES REPORT

if($id==67)
{

	$json_result=doGetReportData($globalSS,$queryIpaddressDownloadBigFiles,'template6.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// IPADDRESS DOWNLOAD BIG FILES REPORT END

/////////////// TOP LOGINS TRAFFIC WORKING HOURS REPORT

if($id==68)
{

	$json_result=doGetReportData($globalSS,$queryTopLoginsWorkingHoursTraffic,'template1.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// TOP LOGINS TRAFFIC WORKING HOURS REPORT END

/////////////// TOP IPADDRESS TRAFFIC WORKING HOURS REPORT

if($id==69)
{

	$json_result=doGetReportData($globalSS,$queryTopIpWorkingHoursTraffic,'template2.php');
	doPrintTable($globalSS,$json_result);

}

/////////////// TOP IPADDRESS TRAFFIC WORKING HOURS REPORT END

echo "<script>
window.onload = function () {
  document.body.classList.add('loaded');
}
</script>";

if(!isset($_GET['pdf'])&& !isset($_GET['csv'])) {

$end=microtime(true);

$runtime=$end - $start;

echo "<br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];


///mysql_disconnect();


echo "

</body>
</html>";

} // if(!isset($_GET['pdf'])&& !isset($_GET['csv']))

?>
