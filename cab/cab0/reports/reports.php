<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> reports.php </#FN>                                                     
*                         File Birth   > <!#FB> 2021/09/11 17:04:26.556 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/06/01 20:01:21.604 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/



include("../../../config.php");

$srv=$_COOKIE['srv'];

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

$globalSS['lang']=$_lang;
$globalSS['connectionParams']=$variableSet;


$header='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="'.$globalSS['root_http'].'/themes/'.$globalSS['globaltheme'].'/global.css"/>





</head>

<body>
';

#Выключим прелоадер в режиме отладки
if($globalSS['debug']==0)
$header=$header.'
<!-- Прелоадер  -->
<div class="preloader">
  <div class="preloader__row">
	<div class="preloader__item"></div>
	<div class="preloader__item"></div>
  </div>
</div>


';


///check login


if (isset($_COOKIE['logged'])and($_COOKIE['logged']==1)) {




//fix for cabinet
if(!isset($_GET['id']))
  $_GET['id']=$_COOKIE['idreport'];
//$id=$_GET['id'];



//reports id

//ID номер отчёта
if (isset($_GET['id']))	$id=$_GET['id']; else $id=0;

//даты отбора для периода
if(isset($_REQUEST['date']) && $_REQUEST['date']!="") $querydate=$_REQUEST['date']; else $querydate=date("d-m-Y");
if(isset($_REQUEST['date2'])) $querydate2=$_REQUEST['date2']; else $querydate2="";

list($day,$month,$year) = preg_split('/[\/\.-]+/', $querydate);


//id типа сайта 1 - 10.102.10.22, 2 - www.yandex.ru
  if(isset($_GET['sitetypeid']))	$currentsitetypeid=$_GET['sitetypeid']; else $currentsitetypeid="";

//логин в явном виде
  if(isset($_GET['loginname'])) $currentlogin=$_GET['loginname']; else $currentlogin="";

//id логина
  if(isset($_GET['loginid']))	$currentloginid=$_GET['loginid']; else $currentloginid="";
  
//IP адрес в явном виде
  if(isset($_GET['ipaddressname'])) $currentipaddress=$_GET['ipaddressname']; else $currentipaddress="";
  
//id IP адреса
  if(isset($_GET['ipaddressid'])) $currentipaddressid=$_GET['ipaddressid'];  else	$currentipaddressid="";
  
 //сайт в явном виде 
  if(isset($_GET['site'])) 	$currentsite=$_GET['site']; else	$currentsite="";

 //mime в явном виде 
 if(isset($_GET['mime'])) 	$currentmime=$_GET['mime']; else	$currentmime="";


//час в явном виде для 41 и 42 
if(isset($_GET['hour'])) 	$currenthour=$_GET['hour']; else	$currenthour="0";

  //id группы в явном виде
  if(isset($_GET['groupid']))	$currentgroupid=$_GET['groupid'];  else $currentgroupid="";
  //тип 0 - логины, 1 - ip адреса
  if(isset($_GET['typeid'])) $typeid=$_GET['typeid']; else	$typeid="";
 //имя группы в явном виде 
  if(isset($_GET['groupname']))	$currentgroup=$_GET['groupname']; else $currentgroup="";
 //хттп статус в явном виде
  if(isset($_GET['httpstatusname'])) $currenthttpname=$_GET['httpstatusname']; else $currenthttpname="";
 //хттп статус id
  if(isset($_GET['httpstatusid'])) $currenthttpstatusid=$_GET['httpstatusid']; else	$currenthttpstatusid="";


//fix for cabinet!
if(isset($_COOKIE['tableid']))
  $currentloginid=$_COOKIE['tableid'];
else
  $currentloginid="";


//fix for cabinet!
if(isset($_COOKIE['tableid']))
  $currentipaddressid=$_COOKIE['tableid'];
else
  $currentipaddressid="";



//fix for cabinet!
if(isset($_COOKIE['tableid']))
  $currentgroupid=$_COOKIE['tableid'];
else
  $currentgroupid="";


///с этим что-то надо будет сделать. Убрать лишнюю дублирующуюся информацию
$params = array();
$params['dbase']=$db[$srv];
$params['idReport']=$id;
$params['date']=$querydate;
$params['date2']=$querydate2;
$params['idLogin']=$currentloginid;   
$params['idIpaddress']=$currentipaddress; 

$globalSS['currenthttpname']=$currenthttpname;
$globalSS['currenthttpstatusid']=$currenthttpstatusid;
$globalSS['currentipaddressid']=$currentipaddressid;
$globalSS['currentloginid']=$currentloginid;
$globalSS['currentipaddress']=$currentipaddress;
$globalSS['currentlogin']=$currentlogin;
$globalSS['currenthour']=$currenthour;

$globalSS['params']=$params;

$dbtype = $globalSS['connectionParams']['dbtype'];


#Большой и временный костыль. Пока думаю как лучше уйти
$enableShowDayNameInReports = $globalSS['enableShowDayNameInReports'];
$enableNofriends = $globalSS['enableNofriends'];
$enableNoSites = $globalSS['enableNoSites'];
$showZeroTrafficInReports = $globalSS['showZeroTrafficInReports'];
$useLoginalias = $globalSS['useLoginalias'];
$useIpaddressalias = $globalSS['useIpaddressalias'];


#Большой костыль end


include("".$globalSS['root_dir']."/modules/Chart/module.php");

$grap = new Chart($globalSS); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение




//если есть команда pdf, то не выводим заголовки
$globalSS['makepdf']=0;
if(isset($_GET['pdf']))
{
$makepdf=1;
$globalSS['makepdf']=1;
// Include the main TCPDF library (search for installation path).
include("".$globalSS['root_dir']."/lib/tcpdf/tcpdf.php");

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


$start=microtime(true);



// Javascripts

if(!isset($_GET['pdf']) && !isset($_GET['csv'])) {
//если не генерируем файл на выход, то выводим заголовки
	echo $header;
?>



<script language=JavaScript>



//переход к частным отчетам по логину, IP адресу или ещё чему
//параметры не указываем, а работаем с массивом arguments, т.е. сколько передали, столько и обрабатываем
//чтобы не думать, в функциях будем передать названия полей для GET и его параметр
//например GoPartlyReports('id',8,'login',3) будет означать &id=8&login=3
function GoPartlyReports()
{
//alert(arguments[0]);
var j = 0;

var args = [];
var ret ="";

for (var i = 0; i < arguments.length; i=i+2) {
  args[j] = arguments[i]+'='+arguments[i+1];
  j=j+1;
}

ret = args.join('&');

parent.right.location.href='reports.php?srv=<?php echo $srv ?>&'+ret+
		'&date='+window.document.fastdateswitch_form.date.value+'&date2='+window.document.fastdateswitch_form.date2.value;

}





//Функция, чтобы редактирование алиасов например, открывать прямо тут же, без перезагрузки
var popUpObj;

function showModalPopUp(srv, tableid, typeid){

	popUpObj=window.open("../right.php?srv="+srv+"&id=2&actid=1&modal=1&m_tableid="+tableid+"&m_typeid="+typeid+"","ModalPopUp","width=500," + "height=500");

	popUpObj.focus();

	LoadModalDiv();

}

</script>

<script type="text/javascript" src="<?php echo $globalSS['root_http']?>/javascript/sortable.js"></script>


<?php
}

// Javascripts END


$weekdaynumber="";

//Если второй даты нет, то предполагаем, что трафик за сутки
if($querydate2=="") {
	$datestart=strtotime($querydate);
	$dateend=strtotime($querydate) + 86400;
	$querydate_str=$querydate;
	$weekdaynumber=date("w",$datestart);
  }
  
  //а если вторая дата есть, то трафик с даты по дату
  if($querydate2!="")  {
	$datestart=strtotime($querydate);
	$dateend=strtotime($querydate2) + 86400;
	$dayname="";
	$querydate_str = $querydate." - ".$querydate2; 
  }
  
  ///
  /// 21 - по месяцам, 39 - по дням, 40 - по имени дня
  if(($_GET['id']==39)||($_GET['id']==40))
  {
  $numdaysinmonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  $datestart=mktime(0,0,0,$month,1,$year);
  $dateend=$datestart + 86400*$numdaysinmonth;
  $querydate=date("d-m-Y",$datestart);
  $querydate2=date("d-m-Y",$dateend-86400);
  $querydate_str = $querydate." - ".$querydate2; 

  }
  //Не будем вводить в заблуждение и сотрем даты
  if($_GET['id']==21){

	$querydate="";
	$querydate2="";

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



  #если есть дружеские логины, IP адреса или сайты. Соберём их.
$goodLoginsList=doCreateFriendList($globalSS,'logins');
$goodIpaddressList=doCreateFriendList($globalSS,'ipaddress');
$goodSitesList = doCreateSitesList($globalSS);


#если нужно посмотреть тех, кто лазил по нехорошим сайтам, то просто добавим это в фильтр
#$filterSite="AND scsq_quicktraffic.site in ('vk.com:443')";
#$filterTrafficSite="AND scsq_traffic.site in ('vk.com:443')";

$filterSite = doCreateFilterSitesList($globalSS, "quick");
$filterTrafficSite = doCreateFilterSitesList($globalSS,"traffic");

$filterSizeinbytes = doCreateFilterSizeinbytes($globalSS);



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


#mysql version
$queryLoginsTraffic="
SELECT 
  nofriends.name,
  tmp.s,
  nofriends.id,
  aliastbl.name
  ,ifnull(sumdenied.sum_denied,0)
FROM (SELECT 
		login,
		SUM(sizeinbytes) as 's' 
	  FROM scsq_quicktraffic 
	  WHERE  date>".$datestart."
	 AND date<".$dateend." 
	 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	 ".$filterSite."

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
  LEFT JOIN (SELECT login, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND par=1   
	  GROUP BY crc32(login) ,login
  ) as sumdenied ON nofriends.id=sumdenied.login
WHERE (1=1)
".$msgNoZeroTraffic."

GROUP BY nofriends.name,
		 nofriends.id,
		 tmp.s,
		 aliastbl.name, sumdenied.sum_denied;";

#postgre version
if($dbtype==1)
$queryLoginsTraffic="
SELECT 
  nofriends.name,
  tmp.s,
  nofriends.id,
  aliastbl.name
  , coalesce(sumdenied.sum_denied,0)
FROM (SELECT 
		login,
		SUM(sizeinbytes) as s 
	  FROM scsq_quicktraffic 
	  WHERE  date>".$datestart."
	 AND date<".$dateend." 
	 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	 ".$filterSite."
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
  LEFT JOIN (SELECT login, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND par=1   
	  GROUP BY  login
  ) as sumdenied ON nofriends.id=sumdenied.login
  WHERE (1=1)
".$msgNoZeroTraffic."

GROUP BY nofriends.name,
  nofriends.id,
  tmp.s,
  aliastbl.name, sumdenied.sum_denied
;";


#mysql version
$queryIpaddressTraffic="
SELECT 
  nofriends.name as 'v_name',
  tmp.s as 'v_traffic',
  nofriends.id as 'v_name_id', 
  aliastbl.name
  ,ifnull(sumdenied.sum_denied,0)
FROM (SELECT 
	ipaddress,
	SUM(sizeinbytes) AS s 
  FROM scsq_quicktraffic 
  WHERE date>".$datestart." 
	AND date<".$dateend." 
	AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	".$filterSite."
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
  LEFT JOIN (SELECT ipaddress, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
		  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
		  AND date>".$datestart." 
		  AND date<".$dateend." 
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
		  ".$filterSite."
		  AND par=1   
	  GROUP BY crc32(ipaddress) ,ipaddress
  ) as sumdenied ON nofriends.id=sumdenied.ipaddress
  WHERE (1=1)
".$msgNoZeroTraffic."




GROUP BY nofriends.name,
  tmp.s,
  nofriends.id, 
  aliastbl.name, sumdenied.sum_denied
  ORDER BY nofriends.name asc
 ;";

#postgre version
if($dbtype==1)
$queryIpaddressTraffic="
SELECT 
  nofriends.name,
  tmp.s,
  nofriends.id, 
  aliastbl.name	
  , coalesce(sumdenied.sum_denied,0)
FROM (SELECT 
	ipaddress,
	SUM(sizeinbytes) AS s 
  FROM scsq_quicktraffic 
  WHERE date>".$datestart." 
	AND date<".$dateend." 
	AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	".$filterSite."
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
  LEFT JOIN (SELECT ipaddress, SUM(sizeinbytes) as sum_denied 
  FROM scsq_quicktraffic, scsq_httpstatus 
  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
  AND date>".$datestart." 
  AND date<".$dateend." 
  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
  ".$filterSite."
  AND par=1   
  GROUP BY ipaddress
  ) as sumdenied ON nofriends.id=sumdenied.ipaddress
WHERE (1=1)
".$msgNoZeroTraffic."

GROUP BY nofriends.name,
  tmp.s,
  nofriends.id, 
  aliastbl.name, sumdenied.sum_denied
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
		AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
		".$filterSite."
		 GROUP BY CRC32(site),site
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
		AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
		".$filterSite."
		 GROUP BY site 
		 
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
		AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
		".$filterSite."
		 GROUP BY CRC32(site),site
		 ORDER BY null
		) as tmp2
		  
	
   

   
ORDER BY tmp2.s desc
LIMIT ".$globalSS['countTopSitesLimit']." ";

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
		AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
		".$filterSite."
		 GROUP BY site
	   
		) as tmp2
		  

   
ORDER BY tmp2.s desc
LIMIT ".$globalSS['countTopSitesLimit']." ";

//echo $queryTopSitesTraffic;

#mysql version
$queryTopLoginsTraffic="
SELECT 
  nofriends.name,
  tmp.s,
  nofriends.id, 
  aliastbl.name
  ,ifnull(sumdenied.sum_denied,0)
FROM (SELECT 
	login,
	SUM(sizeinbytes) AS s 
  FROM scsq_quicktraffic 
  WHERE date>".$datestart." 
	AND date<".$dateend."
	AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	".$filterSite."
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
  LEFT JOIN (SELECT login, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND par=1   
	  GROUP BY crc32(login) ,login
  ) as sumdenied ON nofriends.id=sumdenied.login
WHERE tmp.s !=0

ORDER BY s desc 
LIMIT ".$globalSS['countTopLoginLimit'].";";

#postgresql version
if($dbtype==1)
$queryTopLoginsTraffic="
SELECT 
  nofriends.name,
  tmp.s,
  nofriends.id 
  ,aliastbl.name
  ,coalesce(sumdenied.sum_denied,0)
FROM (SELECT 
	login,
	SUM(sizeinbytes) AS s 
  FROM scsq_quicktraffic 
  WHERE date>".$datestart." 
	AND date<".$dateend."
	AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	".$filterSite."
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
  LEFT JOIN (SELECT login, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND par=1   
	  GROUP BY login
  ) as sumdenied ON nofriends.id=sumdenied.login
WHERE tmp.s !=0

ORDER BY s desc 
LIMIT ".$globalSS['countTopLoginLimit'].";";

#mysql version
$queryTopIpTraffic="
SELECT 
  nofriends.name,
  tmp.s,
  nofriends.id 
  ,aliastbl.name
  ,ifnull(sumdenied.sum_denied,0)
FROM (SELECT 
	ipaddress,
	SUM(sizeinbytes) AS s 
  FROM scsq_quicktraffic 

  WHERE date>".$datestart." 
	AND date<".$dateend." 
	AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	".$filterSite."
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
  LEFT JOIN (SELECT ipaddress, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
		  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
		  AND date>".$datestart." 
		  AND date<".$dateend." 
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
		  ".$filterSite."
		  AND par=1   
	  GROUP BY crc32(ipaddress) ,ipaddress
  ) as sumdenied ON nofriends.id=sumdenied.ipaddress
WHERE tmp.s !=0

ORDER BY s desc 
LIMIT ".$globalSS['countTopIpLimit'].";";

#postgre version
if($dbtype==1)
$queryTopIpTraffic="
SELECT 
  nofriends.name,
  tmp.s,
  nofriends.id 
  ".$echoIpaddressAliasColumn."
  ,coalesce(sumdenied.sum_denied,0)
FROM (SELECT 
	ipaddress,
	SUM(sizeinbytes) AS s 
  FROM scsq_quicktraffic 

  WHERE date>".$datestart." 
	AND date<".$dateend." 
	AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	".$filterSite."
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
  LEFT JOIN (SELECT ipaddress, SUM(sizeinbytes) as sum_denied 
  FROM scsq_quicktraffic, scsq_httpstatus 
  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND par=1   
  GROUP BY ipaddress
) as sumdenied ON nofriends.id=sumdenied.ipaddress
WHERE tmp.s !=0

ORDER BY s desc 
LIMIT ".$globalSS['countTopIpLimit'].";";

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
	AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	".$filterSite."
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
	AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	".$filterSite."
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
nofriends.id,
aliastbl.name
FROM (SELECT 
	login,
	SUM(sizeinbytes) as 's' 
  FROM scsq_traffic 
  WHERE  date>".$datestart."
 AND date<".$dateend." 
 AND scsq_traffic.site NOT IN (".$goodSitesList.")
 ".$filterTrafficSite."
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
nofriends.id,
aliastbl.name
FROM (SELECT 
	login,
	SUM(sizeinbytes) as s 
  FROM scsq_traffic 
  WHERE  date>".$datestart."
 AND date<".$dateend." 
 AND scsq_traffic.site NOT IN (".$goodSitesList.")
 ".$filterTrafficSite."
 AND (
  (	
	  (to_char(to_timestamp(date),'HH24:MI')>=to_char(interval '".$workStartHour1."h ".$workStartMin1."m','HH24:MI'))     
   AND(to_char(to_timestamp(date),'HH24:MI') < to_char(interval '".$workEndHour1."h ".$workEndMin1."m','HH24:MI'))
	)
 OR
 (	
  (to_char(to_timestamp(date),'HH24:MI')>=to_char(interval '".$workStartHour2."h ".$workStartMin2."m','HH24:MI'))     
  AND(to_char(to_timestamp(date),'HH24:MI') < to_char(interval '".$workEndHour2."h ".$workEndMin2."m','HH24:MI'))
 )

   )
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
".$msgNoZeroTraffic."";


#mysql version
$queryTopIpWorkingHoursTraffic="
SELECT 
nofriends.name,
tmp.s,
nofriends.id,
aliastbl.name
FROM (SELECT 
ipaddress,
SUM(sizeinbytes) AS s 
FROM scsq_traffic 
WHERE date>".$datestart." 
AND date<".$dateend." 
AND scsq_traffic.site NOT IN (".$goodSitesList.")
".$filterTrafficSite."
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
nofriends.id,
aliastbl.name
FROM (SELECT 
ipaddress,
SUM(sizeinbytes) AS s 
FROM scsq_traffic 
WHERE date>".$datestart." 
AND date<".$dateend." 
AND scsq_traffic.site NOT IN (".$goodSitesList.")
".$filterTrafficSite."
AND (
  (	
	  (to_char(to_timestamp(date),'HH24:MI')>=to_char(interval '".$workStartHour1."h ".$workStartMin1."m','HH24:MI'))     
   AND(to_char(to_timestamp(date),'HH24:MI') < to_char(interval '".$workEndHour1."h ".$workEndMin1."m','HH24:MI'))
	)
 OR
 (	
  (to_char(to_timestamp(date),'HH24:MI')>=to_char(interval '".$workStartHour2."h ".$workStartMin2."m','HH24:MI'))     
  AND(to_char(to_timestamp(date),'HH24:MI') < to_char(interval '".$workEndHour2."h ".$workEndMin2."m','HH24:MI'))
 )
  )

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
".$msgNoZeroTraffic.";";

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
	  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
   AND par=1
GROUP BY ipaddress
) 

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
		 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
 AND par=1	  
GROUP BY ipaddress
) 

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
		 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
  AND par=1	   
GROUP BY ipaddress
)

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
		AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
AND par=1   
GROUP BY ipaddress
)	

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
ORDER BY tmp2.name asc;";


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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
LIMIT ".$globalSS['countPopularSitesLimit'].";";

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
LIMIT ".$globalSS['countPopularSitesLimit'].";";


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
  LIMIT ".$globalSS['countWhoDownloadBigFilesLimit']."
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
	LIMIT ".$globalSS['countWhoDownloadBigFilesLimit']."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
				AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	 AND FROM_UNIXTIME(date,'%k')>=".$currenthour."
	 AND FROM_UNIXTIME(date,'%k')<".($currenthour+1)."
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

order by nofriends.name asc;";

#postgres version
if($dbtype==1)
$queryWhoVisitSiteOneHourLogin="
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
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND(to_char(to_timestamp(date),'HH24')>=to_char(interval '".$currenthour."h' ,'HH24'))     
	  AND(to_char(to_timestamp(date),'HH24') < to_char(interval '".($currenthour+1)."h','HH24'))
 

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

order by nofriends.name asc;";



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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	AND FROM_UNIXTIME(date,'%k')>=".$currenthour."
	AND FROM_UNIXTIME(date,'%k')<".($currenthour+1)."
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

ORDER BY nofriends.name asc ;";

#postgres version
if($dbtype==1)
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
		   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	 ".$filterSite."
	 AND(to_char(to_timestamp(date),'HH24')>=to_char(interval '".$currenthour."h' ,'HH24'))     
	 AND(to_char(to_timestamp(date),'HH24') < to_char(interval '".($currenthour+1)."h','HH24'))
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

ORDER BY nofriends.name asc ;";


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
				   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
				AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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

SELECT tmp3.name,
	 tmp3.login, 
	 COALESCE(sum(tmp3.hr0),0) hr0_value, 
	 COALESCE(sum(tmp3.hr1),0) hr1_value,
	 COALESCE(sum(tmp3.hr2),0) hr2_value, 
	 COALESCE(sum(tmp3.hr3),0) hr3_value, 
	 COALESCE(sum(tmp3.hr4),0) hr4_value, 
	 COALESCE(sum(tmp3.hr5),0) hr5_value, 
	 COALESCE(sum(tmp3.hr6),0) hr6_value, 
	 COALESCE(sum(tmp3.hr7),0) hr7_value, 
	 COALESCE(sum(tmp3.hr8),0) hr8_value, 
	 COALESCE(sum(tmp3.hr9),0) hr9_value, 
	 COALESCE(sum(tmp3.hr10),0) hr10_value, 
	 COALESCE(sum(tmp3.hr11),0) hr11_value, 
	 COALESCE(sum(tmp3.hr12),0) hr12_value, 
	 COALESCE(sum(tmp3.hr13),0) hr13_value, 
	 COALESCE(sum(tmp3.hr14),0) hr14_value, 
	 COALESCE(sum(tmp3.hr15),0) hr15_value, 
	 COALESCE(sum(tmp3.hr16),0) hr16_value, 
	 COALESCE(sum(tmp3.hr17),0) hr17_value, 
	 COALESCE(sum(tmp3.hr18),0) hr18_value, 
	 COALESCE(sum(tmp3.hr19),0) hr19_value, 
	 COALESCE(sum(tmp3.hr20),0) hr20_value, 
	 COALESCE(sum(tmp3.hr21),0) hr21_value, 
	 COALESCE(sum(tmp3.hr22),0) hr22_value, 
	 COALESCE(sum(tmp3.hr23),0) hr23_value 

	 FROM (
SELECT 
  tmp2.login,
  tmp2.name,
  case when hrs.hr = 0 then  COALESCE(tmp2.sum_bytes,0) end hr0,
  case when hrs.hr = 1 then  COALESCE(tmp2.sum_bytes,0) end hr1,
  case when hrs.hr = 2 then  COALESCE(tmp2.sum_bytes,0) end hr2,
  case when hrs.hr = 3 then  COALESCE(tmp2.sum_bytes,0) end hr3,
  case when hrs.hr = 4 then  COALESCE(tmp2.sum_bytes,0) end hr4,
  case when hrs.hr = 5 then  COALESCE(tmp2.sum_bytes,0) end hr5,
  case when hrs.hr = 6 then  COALESCE(tmp2.sum_bytes,0) end hr6,
  case when hrs.hr = 7 then  COALESCE(tmp2.sum_bytes,0) end hr7,
  case when hrs.hr = 8 then  COALESCE(tmp2.sum_bytes,0) end hr8,
  case when hrs.hr = 9 then  COALESCE(tmp2.sum_bytes,0) end hr9,
  case when hrs.hr = 10 then  COALESCE(tmp2.sum_bytes,0) end hr10,
  case when hrs.hr = 11 then  COALESCE(tmp2.sum_bytes,0) end hr11,
  case when hrs.hr = 12 then  COALESCE(tmp2.sum_bytes,0) end hr12,
  case when hrs.hr = 13 then  COALESCE(tmp2.sum_bytes,0) end hr13,
  case when hrs.hr = 14 then  COALESCE(tmp2.sum_bytes,0) end hr14,
  case when hrs.hr = 15 then  COALESCE(tmp2.sum_bytes,0) end hr15,
  case when hrs.hr = 16 then  COALESCE(tmp2.sum_bytes,0) end hr16,
  case when hrs.hr = 17 then  COALESCE(tmp2.sum_bytes,0) end hr17,
  case when hrs.hr = 18 then  COALESCE(tmp2.sum_bytes,0) end hr18,
  case when hrs.hr = 19 then  COALESCE(tmp2.sum_bytes,0) end hr19,
  case when hrs.hr = 20 then  COALESCE(tmp2.sum_bytes,0) end hr20,
  case when hrs.hr = 21 then  COALESCE(tmp2.sum_bytes,0) end hr21,
  case when hrs.hr = 22 then  COALESCE(tmp2.sum_bytes,0) end hr22,
  case when hrs.hr = 23 then  COALESCE(tmp2.sum_bytes,0) end hr23

FROM (

SELECT  login,
nofriends.name,
sum(sizeinbytes) as sum_bytes,
to_char(to_timestamp(date),'HH24') d

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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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

			 
			 ) hrs on hrs.hr=CAST(tmp2.d as integer)
			 
			 order by hrs.hr asc
) tmp3
WHERE tmp3.login is not null
GROUP BY tmp3.login, tmp3.name
ORDER BY tmp3.name asc
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
SELECT tmp3.name,
	 tmp3.ipaddress, 
	 COALESCE(sum(tmp3.hr0),0) hr0_value, 
	 COALESCE(sum(tmp3.hr1),0) hr1_value,
	 COALESCE(sum(tmp3.hr2),0) hr2_value, 
	 COALESCE(sum(tmp3.hr3),0) hr3_value, 
	 COALESCE(sum(tmp3.hr4),0) hr4_value, 
	 COALESCE(sum(tmp3.hr5),0) hr5_value, 
	 COALESCE(sum(tmp3.hr6),0) hr6_value, 
	 COALESCE(sum(tmp3.hr7),0) hr7_value, 
	 COALESCE(sum(tmp3.hr8),0) hr8_value, 
	 COALESCE(sum(tmp3.hr9),0) hr9_value, 
	 COALESCE(sum(tmp3.hr10),0) hr10_value, 
	 COALESCE(sum(tmp3.hr11),0) hr11_value, 
	 COALESCE(sum(tmp3.hr12),0) hr12_value, 
	 COALESCE(sum(tmp3.hr13),0) hr13_value, 
	 COALESCE(sum(tmp3.hr14),0) hr14_value, 
	 COALESCE(sum(tmp3.hr15),0) hr15_value, 
	 COALESCE(sum(tmp3.hr16),0) hr16_value, 
	 COALESCE(sum(tmp3.hr17),0) hr17_value, 
	 COALESCE(sum(tmp3.hr18),0) hr18_value, 
	 COALESCE(sum(tmp3.hr19),0) hr19_value, 
	 COALESCE(sum(tmp3.hr20),0) hr20_value, 
	 COALESCE(sum(tmp3.hr21),0) hr21_value, 
	 COALESCE(sum(tmp3.hr22),0) hr22_value, 
	 COALESCE(sum(tmp3.hr23),0) hr23_value 

	 FROM (
SELECT 
  tmp2.ipaddress,
  tmp2.name,
  case when hrs.hr = 0 then  COALESCE(tmp2.sum_bytes,0) end hr0,
  case when hrs.hr = 1 then  COALESCE(tmp2.sum_bytes,0) end hr1,
  case when hrs.hr = 2 then  COALESCE(tmp2.sum_bytes,0) end hr2,
  case when hrs.hr = 3 then  COALESCE(tmp2.sum_bytes,0) end hr3,
  case when hrs.hr = 4 then  COALESCE(tmp2.sum_bytes,0) end hr4,
  case when hrs.hr = 5 then  COALESCE(tmp2.sum_bytes,0) end hr5,
  case when hrs.hr = 6 then  COALESCE(tmp2.sum_bytes,0) end hr6,
  case when hrs.hr = 7 then  COALESCE(tmp2.sum_bytes,0) end hr7,
  case when hrs.hr = 8 then  COALESCE(tmp2.sum_bytes,0) end hr8,
  case when hrs.hr = 9 then  COALESCE(tmp2.sum_bytes,0) end hr9,
  case when hrs.hr = 10 then  COALESCE(tmp2.sum_bytes,0) end hr10,
  case when hrs.hr = 11 then  COALESCE(tmp2.sum_bytes,0) end hr11,
  case when hrs.hr = 12 then  COALESCE(tmp2.sum_bytes,0) end hr12,
  case when hrs.hr = 13 then  COALESCE(tmp2.sum_bytes,0) end hr13,
  case when hrs.hr = 14 then  COALESCE(tmp2.sum_bytes,0) end hr14,
  case when hrs.hr = 15 then  COALESCE(tmp2.sum_bytes,0) end hr15,
  case when hrs.hr = 16 then  COALESCE(tmp2.sum_bytes,0) end hr16,
  case when hrs.hr = 17 then  COALESCE(tmp2.sum_bytes,0) end hr17,
  case when hrs.hr = 18 then  COALESCE(tmp2.sum_bytes,0) end hr18,
  case when hrs.hr = 19 then  COALESCE(tmp2.sum_bytes,0) end hr19,
  case when hrs.hr = 20 then  COALESCE(tmp2.sum_bytes,0) end hr20,
  case when hrs.hr = 21 then  COALESCE(tmp2.sum_bytes,0) end hr21,
  case when hrs.hr = 22 then  COALESCE(tmp2.sum_bytes,0) end hr22,
  case when hrs.hr = 23 then  COALESCE(tmp2.sum_bytes,0) end hr23

FROM (
SELECT  ipaddress,
nofriends.name,
sum(sizeinbytes) sum_bytes,
to_char(to_timestamp(date),'HH24') d
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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

			 
			 ) hrs on hrs.hr=CAST(tmp2.d as integer)
			 
			 order by hrs.hr asc
) tmp3
WHERE tmp3.ipaddress is not null
GROUP BY tmp3.ipaddress, tmp3.name
ORDER BY tmp3.name asc

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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
				AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
  
  ) 
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
  
  ) 
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
	 ,sumdenied.sum_denied
   FROM scsq_quicktraffic
   LEFT JOIN (SELECT site, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
			  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND login=".$currentloginid." 
	  AND par=1   
   GROUP BY crc32(site) ,site
) as sumdenied ON scsq_quicktraffic.site=sumdenied.site
   WHERE login=".$currentloginid." 
	 AND date>".$datestart." 
	 AND date<".$dateend." 
	 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	 ".$filterSite." 
  AND par=1
   GROUP BY CRC32(scsq_quicktraffic.site) ,scsq_quicktraffic.site, sumdenied.sum_denied
   ORDER BY site asc
;";

#postgre version
if($dbtype==1)
$queryOneLoginTraffic="
  SELECT 
	 scsq_quicktraffic.site,
	 SUM(sizeinbytes) AS s
	 ,sumdenied.sum_denied
   FROM scsq_quicktraffic
   LEFT JOIN (SELECT site, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
			  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND login=".$currentloginid." 
	  AND par=1   
  GROUP BY site
  ) as sumdenied ON scsq_quicktraffic.site=sumdenied.site

   WHERE login=".$currentloginid." 
	 AND date>".$datestart." 
	 AND date<".$dateend." 
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	 ".$filterSite."    
  AND par=1
   GROUP BY scsq_quicktraffic.site, sumdenied.sum_denied
   ORDER BY site asc
;";

//echo $queryOneLoginTraffic;

$queryOneLoginTopSitesTraffic="
   SELECT 
	 scsq_quicktraffic.site, 
	 SUM( sizeinbytes ) AS s
	 ,sumdenied.sum_denied
   FROM scsq_quicktraffic
   LEFT JOIN (SELECT site, SUM(sizeinbytes) as sum_denied 
		  FROM scsq_quicktraffic, scsq_httpstatus 
		  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
		  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
		  AND date>".$datestart." 
		  AND date<".$dateend." 
				  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
		  AND login=".$currentloginid." 
		  AND par=1   
	  GROUP BY crc32(site) ,site
  ) as sumdenied ON scsq_quicktraffic.site=sumdenied.site
   WHERE login=".$currentloginid." 
	 AND date>".$datestart." 
	 AND date<".$dateend."
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	 ".$filterSite." 
	 AND par=1
   GROUP BY CRC32(scsq_quicktraffic.site), scsq_quicktraffic.site, sumdenied.sum_denied
   ORDER BY s DESC
   LIMIT ".$globalSS['countTopSitesLimit']." 
";

#postgre version
if($dbtype==1)
$queryOneLoginTopSitesTraffic="
   SELECT 
	 scsq_quicktraffic.site, 
	 SUM( sizeinbytes ) AS s
	 ,sumdenied.sum_denied
   FROM scsq_quicktraffic
   LEFT JOIN (SELECT site, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
			  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND login=".$currentloginid." 
	  AND par=1   
  GROUP BY site
  ) as sumdenied ON scsq_quicktraffic.site=sumdenied.site

   WHERE login=".$currentloginid." 
	 AND date>".$datestart." 
	 AND date<".$dateend."
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	 ".$filterSite." 
	 AND par=1
   GROUP BY scsq_quicktraffic.site, sumdenied.sum_denied
   ORDER BY s DESC
   LIMIT ".$globalSS['countTopSitesLimit']." 
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
				AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
				AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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



$queryWhoVisitPopularSiteLogin="
SELECT 
  nofriends.name, 
  tmp.s,
  nofriends.id,
  tmp2.name

FROM (SELECT 
	login,
	SUM(sizeinbytes) AS s
  FROM scsq_traffic

  WHERE  
	 date>".$datestart." 
	AND date<".$dateend."
  
  AND 	  (case
			  when (".$currentsitetypeid."=1) and (SUBSTRING_INDEX(site,'/',1)='".$currentsite."') then TRUE 
			  when (".$currentsitetypeid."=2) and (SUBSTRING_INDEX(SUBSTRING_INDEX(scsq_traffic.site,'/',1),'.',-2)) ='".$currentsite."' then TRUE
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
  nofriends.id,
  tmp2.name

FROM (SELECT 
	login,
	SUM(sizeinbytes) AS s
  FROM scsq_traffic

  WHERE  
	 date>".$datestart." 
	AND date<".$dateend."
  
  AND 	  (case
			  when (".$currentsitetypeid."=1) and (split_part(site,'/',1)='".$currentsite."') then TRUE 
			  when (".$currentsitetypeid."=2) and reverse(split_part(reverse(split_part(site,'/',1)),'.',1) ||'.'|| split_part(reverse(split_part(site,'/',1)),'.',2)) ='".$currentsite."' then TRUE
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
		".$filterSizeinbytes."
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
		".$filterSizeinbytes."
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
LIMIT ".$globalSS['countPopularSitesLimit'].";";


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
LIMIT ".$globalSS['countPopularSitesLimit'].";";


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
		 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
		 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
	LIMIT ".$globalSS['countWhoDownloadBigFilesLimit']."
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
	LIMIT ".$globalSS['countWhoDownloadBigFilesLimit']."
";

$queryOneIpaddressTraffic="
   SELECT 
	 scsq_quicktraffic.site AS st,
	 sum(sizeinbytes) AS s
	 , sumdenied.sum_denied
   FROM scsq_quicktraffic 
  LEFT JOIN (SELECT site, SUM(sizeinbytes) as sum_denied 
		  FROM scsq_quicktraffic, scsq_httpstatus 
		  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
		  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
		  AND date>".$datestart." 
		  AND date<".$dateend." 
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
		  AND ipaddress=".$currentipaddressid."
		  AND par=1   
		  GROUP BY crc32(site) ,site
  ) as sumdenied ON scsq_quicktraffic.site=sumdenied.site
   WHERE ipaddress=".$currentipaddressid." 
	 AND date>".$datestart." 
	 AND date<".$dateend." 
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	 ".$filterSite." 
	 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	 ".$filterSite."

	 AND par=1
	 
   GROUP BY CRC32(scsq_quicktraffic.site),scsq_quicktraffic.site, sumdenied.sum_denied	 
   ORDER BY scsq_quicktraffic.site asc;";

#postgre version
if($dbtype==1)
$queryOneIpaddressTraffic="
   SELECT 
	 scsq_quicktraffic.site AS st,
	 sum(sizeinbytes) AS s
	
	 ,sumdenied.sum_denied
   FROM scsq_quicktraffic
   LEFT JOIN (SELECT site, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
			  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND ipaddress=".$currentipaddressid."
	  AND par=1   
	  GROUP BY site
  ) as sumdenied ON scsq_quicktraffic.site=sumdenied.site
   

   WHERE ipaddress=".$currentipaddressid." 
	 AND date>".$datestart." 
	 AND date<".$dateend." 
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	 ".$filterSite." 
	 AND par=1
	 
   GROUP BY scsq_quicktraffic.site, sumdenied.sum_denied	 
   ORDER BY scsq_quicktraffic.site asc;";


$queryOneIpaddressTopSitesTraffic="
   SELECT 
	 scsq_quicktraffic.site,
	 SUM(sizeinbytes) as s
	 ,sumdenied.sum_denied
   FROM scsq_quicktraffic 
  LEFT JOIN (SELECT site, SUM(sizeinbytes) as sum_denied 
		  FROM scsq_quicktraffic, scsq_httpstatus 
		  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
		  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
		  AND date>".$datestart." 
		  AND date<".$dateend." 
				  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
		  AND ipaddress=".$currentipaddressid."
		  AND par=1   
		  GROUP BY crc32(site) ,site
  ) as sumdenied ON scsq_quicktraffic.site=sumdenied.site

   WHERE ipaddress=".$currentipaddressid." 
	 AND date>".$datestart." 
	 AND date<".$dateend." 
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	 ".$filterSite." 	 
	 AND par=1
   GROUP BY CRC32(scsq_quicktraffic.site) ,scsq_quicktraffic.site, sumdenied.sum_denied
   ORDER BY s desc 
   LIMIT ".$globalSS['countTopSitesLimit']." ";

#postgre version
if($dbtype==1)
$queryOneIpaddressTopSitesTraffic="
   SELECT 
	 scsq_quicktraffic.site,
	 SUM(sizeinbytes) as s
	 ,sumdenied.sum_denied
   FROM scsq_quicktraffic 
   LEFT JOIN (SELECT site, SUM(sizeinbytes) as sum_denied 
	  FROM scsq_quicktraffic, scsq_httpstatus 
	  WHERE scsq_httpstatus.name like '%TCP_DENIED%' 
	  AND scsq_httpstatus.id=scsq_quicktraffic.httpstatus 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
			  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	  AND ipaddress=".$currentipaddressid."
	  AND par=1   
	  GROUP BY site
  ) as sumdenied ON scsq_quicktraffic.site=sumdenied.site

   WHERE ipaddress=".$currentipaddressid." 
	 AND date>".$datestart." 
	 AND date<".$dateend." 
			AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")  
	 ".$filterSite." 	 
	 AND par=1
   GROUP BY scsq_quicktraffic.site, sumdenied.sum_denied
   ORDER BY s desc 
   LIMIT ".$globalSS['countTopSitesLimit']." ";

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
				AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
				AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			  when (".$currentsitetypeid."=1) and (SUBSTRING_INDEX(site,'/',1)='".$currentsite."') then TRUE 
			  when (".$currentsitetypeid."=2) and (SUBSTRING_INDEX(SUBSTRING_INDEX(scsq_traffic.site,'/',1),'.',-2)) ='".$currentsite."' then TRUE
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
			  when (".$currentsitetypeid."=1) and (split_part(site,'/',1)='".$currentsite."') then TRUE 
			  when (".$currentsitetypeid."=2) and reverse(split_part(reverse(split_part(site,'/',1)),'.',1) ||'.'|| split_part(reverse(split_part(site,'/',1)),'.',2)) ='".$currentsite."' then TRUE
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
	LIMIT ".$globalSS['countWhoDownloadBigFilesLimit']."
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
	LIMIT ".$globalSS['countWhoDownloadBigFilesLimit']."
";


#костылище для частных отчетов

$queryOneLoginOneHourTraffic="
   SELECT 
	 site,
	 SUM(sizeinbytes) AS s 
   FROM scsq_quicktraffic 
   WHERE login=".$currentloginid." 
	 AND date>".$datestart." 
	 AND date<".$dateend." 
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	 AND FROM_UNIXTIME(date,'%k')>=".$currenthour."
	 AND FROM_UNIXTIME(date,'%k')<".($currenthour+1)."
   GROUP BY CRC32(site),site 
	 ORDER BY site asc;";
	 
	 #postgre version
	 if($dbtype==1)
$queryOneLoginOneHourTraffic="
   SELECT 
	 site,
	 SUM(sizeinbytes) AS s 
   FROM scsq_quicktraffic 
   WHERE login=".$currentloginid." 
	 AND date>".$datestart." 
	 AND date<".$dateend." 
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	 AND FROM_UNIXTIME(date,'%k')>=".$currenthour."
	 AND FROM_UNIXTIME(date,'%k')<".($currenthour+1)."
	GROUP BY CRC32(site) ,site
   ORDER BY site asc ";


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
			 AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
	 AND cast(to_char(to_timestamp(date),'HH24') as int)>=".$currenthour."
	 AND cast(to_char(to_timestamp(date),'HH24') as int)<".($currenthour+1)."
	GROUP BY site 
   ORDER BY site asc ";

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
		".$filterSizeinbytes."
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
	".$filterSizeinbytes."
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
LIMIT ".$globalSS['countPopularSitesLimit'].";";


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
LIMIT ".$globalSS['countPopularSitesLimit'].";";


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
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
  AND par=1
group by crc32(nofriends.name),nofriends.name, login,tmp2.name
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
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
  AND par=1
GROUP BY crc32(nofriends.name),nofriends.name, ipaddress, tmp2.name
ORDER BY nofriends.name asc;";

#postgre version
if($dbtype==1)
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
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
  AND par=1
GROUP BY nofriends.name, ipaddress, tmp2.name 
ORDER BY nofriends.name asc;";

$queryOneLoginOneHttpStatus="
SELECT 
  from_unixtime(date,'%d-%m-%Y %H:%i:%s') as d, 
  scsq_traffic.site 
FROM scsq_traffic 

LEFT JOIN scsq_logins ON scsq_logins.id=scsq_traffic.login 

WHERE login='".$currentloginid."' 
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

WHERE login='".$currentloginid."' 
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

WHERE ipaddress='".$currentipaddressid."' 
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

WHERE ipaddress='".$currentipaddressid."' 
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
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
		  AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	  ".$filterSite."
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
if($dbtype==1)
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
if($dbtype==1)
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
  sg.name,
  sum(alias_sum_traffic.s1),
  sg.id,
  0
  
FROM (SELECT 
	  alias_traffic.al_name as al_name,
	sum(alias_traffic.s) AS s1,
	alias_traffic.al_id as al_id
   
  FROM (
	  SELECT
		  
		  login al_login,
		  ipaddress al_ipaddress,
		  sizeinbytes s,
		  sa.name al_name,
		  sa.id al_id
	  FROM
		 scsq_quicktraffic sq
	  INNER JOIN scsq_alias sa 
	  ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
	  WHERE
		 sq.date>".$datestart." 
		 AND sq.date<".$dateend."
		 AND sq.site NOT IN (".$goodSitesList.")
	  ) alias_traffic

 GROUP BY alias_traffic.al_id,alias_traffic.al_name) alias_sum_traffic

  INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_sum_traffic.al_id
  RIGHT JOIN scsq_groups sg ON sg.id=sag.groupid


GROUP BY sg.id,sg.name
order by sg.name;";


#postgre version
if($dbtype==1)
$queryGroupsTraffic="
SELECT
sg.name,
sum(alias_sum_traffic.s1),
sg.id,
0

FROM (SELECT 
  alias_traffic.al_name as al_name,
sum(alias_traffic.s) AS s1,
alias_traffic.al_id as al_id

FROM (
  SELECT
	  
	  login al_login,
	  ipaddress al_ipaddress,
	  sizeinbytes s,
	  sa.name al_name,
	  sa.id al_id
  FROM
	 scsq_quicktraffic sq
  INNER JOIN scsq_alias sa 
  ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
  WHERE
	 sq.date>".$datestart." 
	 AND sq.date<".$dateend."
	 AND sq.site NOT IN (".$goodSitesList.")
  ) alias_traffic

GROUP BY alias_traffic.al_id,alias_traffic.al_name) alias_sum_traffic

INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_sum_traffic.al_id
RIGHT JOIN scsq_groups sg ON sg.id=sag.groupid


GROUP BY sg.id,sg.name
order by sg.name;";



$queryOneGroupTraffic="
SELECT 
	alias_sum_traffic.al_name,
	alias_sum_traffic.s1 as s1,
	alias_sum_traffic.al_id,
	alias_sum_traffic.al_name,
	alias_sum_traffic.al_typeid,
	alias_sum_traffic.al_tableid
	
FROM (SELECT 
	alias_traffic.al_name as al_name,
  sum(alias_traffic.s) AS s1,
  alias_traffic.al_id as al_id,
  alias_traffic.al_typeid al_typeid,
  alias_traffic.al_tableid al_tableid
FROM (
	SELECT
		
		login al_login,
		ipaddress al_ipaddress,
		sizeinbytes s,
		sa.name al_name,
		sa.id al_id,
		sa.typeid al_typeid, 
		sa.tableid al_tableid
	FROM
	   scsq_quicktraffic sq
	INNER JOIN scsq_alias sa 
	ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
	WHERE
	   sq.date>".$datestart." 
	   AND sq.date<".$dateend."
	   AND sq.site NOT IN (".$goodSitesList.")
	) alias_traffic

GROUP BY alias_traffic.al_id,alias_traffic.al_name, alias_traffic.al_tableid, alias_traffic.al_typeid) alias_sum_traffic

INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_sum_traffic.al_id AND sag.groupid='".$currentgroupid."'


  ORDER BY alias_sum_traffic.al_name asc;";



#postgre version
if($dbtype==1) 

$queryOneGroupTraffic="
SELECT 
alias_sum_traffic.al_name,
alias_sum_traffic.s1 as s1,
alias_sum_traffic.al_id,
alias_sum_traffic.al_name,
alias_sum_traffic.al_typeid,
alias_sum_traffic.al_tableid

FROM (SELECT 
alias_traffic.al_name as al_name,
sum(alias_traffic.s) AS s1,
alias_traffic.al_id as al_id,
alias_traffic.al_typeid al_typeid,
alias_traffic.al_tableid al_tableid
FROM (
SELECT
  
  login al_login,
  ipaddress al_ipaddress,
  sizeinbytes s,
  sa.name al_name,
  sa.id al_id,
  sa.typeid al_typeid, 
  sa.tableid al_tableid
FROM
 scsq_quicktraffic sq
INNER JOIN scsq_alias sa 
ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
WHERE
 sq.date>".$datestart." 
 AND sq.date<".$dateend."
 AND sq.site NOT IN (".$goodSitesList.")
) alias_traffic

GROUP BY alias_traffic.al_id,alias_traffic.al_name, alias_traffic.al_tableid, alias_traffic.al_typeid) alias_sum_traffic

INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_sum_traffic.al_id AND sag.groupid='".$currentgroupid."'


ORDER BY alias_sum_traffic.al_name asc;";




$queryOneGroupTrafficWide="
SELECT 
  tmp2.al_tableid,
  sum(tmp2.sum_in_cache),
  sum(tmp2.sum_out_cache),
  sum(tmp2.sum_bytes),
  sum(tmp2.sum_in_cache)/sum(tmp2.sum_bytes)*100,
  sum(tmp2.sum_out_cache)/sum(tmp2.sum_bytes)*100,
  tmp2.al_name,
  tmp2.al_typeid
FROM (
  SELECT 
	  alias_sum_traffic.al_tableid,
	  alias_sum_traffic.sum_in_cache,
	  alias_sum_traffic.sum_out_cache,
	  alias_sum_traffic.sum_bytes,
	  alias_sum_traffic.al_name,
	  alias_sum_traffic.al_typeid

FROM ((SELECT 
	   alias_traffic.al_tableid,
	 '2' as n,
	 SUM(sizeinbytes) AS sum_in_cache,
	 0 AS sum_out_cache,
	 0 as sum_bytes,
	 alias_traffic.al_name,
	 alias_traffic.al_typeid 
   FROM 
   (
	  SELECT
		  
		  login al_login,
		  ipaddress al_ipaddress,
		  sizeinbytes,
		  sa.name al_name,
		  sa.id al_id,
		  sa.typeid al_typeid, 
		  sa.tableid al_tableid
	  FROM
		 scsq_quicktraffic sq
	  INNER JOIN scsq_alias sa 
	  ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
	  INNER JOIN scsq_httpstatus  ON scsq_httpstatus.id=sq.httpstatus 
	  
	  WHERE
	  
	  (scsq_httpstatus.name like '%TCP_HIT%' 
	 or  scsq_httpstatus.name like '%TCP_IMS_HIT%' 
	 or  scsq_httpstatus.name like '%TCP_MEM_HIT%' 
	 or  scsq_httpstatus.name like '%TCP_OFFLINE_HIT%' 
	 or  scsq_httpstatus.name like '%UDP_HIT%') 
	
	  AND sq.date>".$datestart." 
	  AND sq.date<".$dateend."
	  AND sq.site NOT IN (".$goodSitesList.")
	  
	  
		 ) alias_traffic
  INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_traffic.al_id AND sag.groupid='".$currentgroupid."'


  GROUP BY al_tableid, al_name, al_typeid
  ) 

UNION 

  (SELECT 
	  alias_traffic.al_tableid,
	 '3' as n,
	 0 AS sum_in_cache,
	 SUM(sizeinbytes) AS sum_out_cache,
	 0 as sum_bytes,
	 alias_traffic.al_name,
	 alias_traffic.al_typeid 
   FROM (
	  SELECT
		  
		  login al_login,
		  ipaddress al_ipaddress,
		  sizeinbytes,
		  sa.name al_name,
		  sa.id al_id,
		  sa.typeid al_typeid, 
		  sa.tableid al_tableid
	  FROM
		 scsq_quicktraffic sq
	  INNER JOIN scsq_alias sa 
	  ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
	  INNER JOIN scsq_httpstatus  ON scsq_httpstatus.id=sq.httpstatus 
	  
	  WHERE
	  
	  (scsq_httpstatus.name NOT LIKE '%TCP_HIT%' 
	and  scsq_httpstatus.name not like '%TCP_IMS_HIT%' 
	and  scsq_httpstatus.name not like '%TCP_MEM_HIT%' 
	and  scsq_httpstatus.name not like '%TCP_OFFLINE_HIT%' 
	and  scsq_httpstatus.name not like '%UDP_HIT%') 
	
	  AND sq.date>".$datestart." 
	  AND sq.date<".$dateend."
	  AND sq.site NOT IN (".$goodSitesList.")
	  
	  
		 ) alias_traffic
  INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_traffic.al_id AND sag.groupid='".$currentgroupid."'
  
  GROUP BY al_tableid, al_name, al_typeid
  ) 

UNION 

  (SELECT 
	  alias_traffic.al_tableid,
	 '1' as n,
	 0 AS sum_in_cache,
	 0 AS sum_out_cache,
	 SUM(sizeinbytes) as sum_bytes,
	 alias_traffic.al_name,
	 alias_traffic.al_typeid  
   from 
   (SELECT
		  
   login al_login,
   ipaddress al_ipaddress,
   sizeinbytes,
   sa.name al_name,
   sa.id al_id,
   sa.typeid al_typeid, 
   sa.tableid al_tableid
FROM
  scsq_quicktraffic sq
INNER JOIN scsq_alias sa 
ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
INNER JOIN scsq_httpstatus  ON scsq_httpstatus.id=sq.httpstatus 

WHERE


sq.date>".$datestart." 
AND sq.date<".$dateend."
AND sq.site NOT IN (".$goodSitesList.")


  ) alias_traffic
INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_traffic.al_id AND sag.groupid='".$currentgroupid."'

GROUP BY al_tableid, al_name, al_typeid


   )) 
   AS alias_sum_traffic

   ) tmp2
   
   GROUP BY tmp2.al_tableid,tmp2.al_name
   ORDER BY tmp2.al_name asc;";



#postgre version
if($dbtype==1)
$queryOneGroupTrafficWide="
SELECT 
tmp2.al_tableid,
sum(tmp2.sum_in_cache),
sum(tmp2.sum_out_cache),
sum(tmp2.sum_bytes),
sum(tmp2.sum_in_cache)/sum(tmp2.sum_bytes)*100,
sum(tmp2.sum_out_cache)/sum(tmp2.sum_bytes)*100,
tmp2.al_name
FROM (
SELECT 
  alias_sum_traffic.al_tableid,
  alias_sum_traffic.sum_in_cache,
  alias_sum_traffic.sum_out_cache,
  alias_sum_traffic.sum_bytes,
  alias_sum_traffic.al_name

FROM ((SELECT 
   alias_traffic.al_tableid,
 '2' as n,
 SUM(sizeinbytes) AS sum_in_cache,
 0 AS sum_out_cache,
 0 as sum_bytes,
 alias_traffic.al_name,
 alias_traffic.al_typeid 
FROM 
(
  SELECT
	  
	  login al_login,
	  ipaddress al_ipaddress,
	  sizeinbytes,
	  sa.name al_name,
	  sa.id al_id,
	  sa.typeid al_typeid, 
	  sa.tableid al_tableid
  FROM
	 scsq_quicktraffic sq
  INNER JOIN scsq_alias sa 
  ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
  INNER JOIN scsq_httpstatus  ON scsq_httpstatus.id=sq.httpstatus 
  
  WHERE
  
  (scsq_httpstatus.name like '%TCP_HIT%' 
 or  scsq_httpstatus.name like '%TCP_IMS_HIT%' 
 or  scsq_httpstatus.name like '%TCP_MEM_HIT%' 
 or  scsq_httpstatus.name like '%TCP_OFFLINE_HIT%' 
 or  scsq_httpstatus.name like '%UDP_HIT%') 

  AND sq.date>".$datestart." 
  AND sq.date<".$dateend."
  AND sq.site NOT IN (".$goodSitesList.")
  
  
	 ) alias_traffic
INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_traffic.al_id AND sag.groupid='".$currentgroupid."'


GROUP BY al_tableid, al_name, al_typeid
) 

UNION 

(SELECT 
  alias_traffic.al_tableid,
 '3' as n,
 0 AS sum_in_cache,
 SUM(sizeinbytes) AS sum_out_cache,
 0 as sum_bytes,
 alias_traffic.al_name,
 alias_traffic.al_typeid 
FROM (
  SELECT
	  
	  login al_login,
	  ipaddress al_ipaddress,
	  sizeinbytes,
	  sa.name al_name,
	  sa.id al_id,
	  sa.typeid al_typeid, 
	  sa.tableid al_tableid
  FROM
	 scsq_quicktraffic sq
  INNER JOIN scsq_alias sa 
  ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
  INNER JOIN scsq_httpstatus  ON scsq_httpstatus.id=sq.httpstatus 
  
  WHERE
  
  (scsq_httpstatus.name NOT LIKE '%TCP_HIT%' 
and  scsq_httpstatus.name not like '%TCP_IMS_HIT%' 
and  scsq_httpstatus.name not like '%TCP_MEM_HIT%' 
and  scsq_httpstatus.name not like '%TCP_OFFLINE_HIT%' 
and  scsq_httpstatus.name not like '%UDP_HIT%') 

  AND sq.date>".$datestart." 
  AND sq.date<".$dateend."
  AND sq.site NOT IN (".$goodSitesList.")
  
  
	 ) alias_traffic
INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_traffic.al_id AND sag.groupid='".$currentgroupid."'

GROUP BY al_tableid, al_name, al_typeid
) 

UNION 

(SELECT 
  alias_traffic.al_tableid,
 '1' as n,
 0 AS sum_in_cache,
 0 AS sum_out_cache,
 SUM(sizeinbytes) as sum_bytes,
 alias_traffic.al_name,
 alias_traffic.al_typeid  
from 
(SELECT
	  
login al_login,
ipaddress al_ipaddress,
sizeinbytes,
sa.name al_name,
sa.id al_id,
sa.typeid al_typeid, 
sa.tableid al_tableid
FROM
scsq_quicktraffic sq
INNER JOIN scsq_alias sa 
ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
INNER JOIN scsq_httpstatus  ON scsq_httpstatus.id=sq.httpstatus 

WHERE


sq.date>".$datestart." 
AND sq.date<".$dateend."
AND sq.site NOT IN (".$goodSitesList.")


) alias_traffic
INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_traffic.al_id AND sag.groupid='".$currentgroupid."'

GROUP BY al_tableid, al_name, al_typeid


)) 
AS alias_sum_traffic

) tmp2

GROUP BY tmp2.al_tableid,tmp2.al_name
ORDER BY tmp2.al_name asc;";




$queryOneGroupTopSitesTraffic="
	 SELECT 
	 site,
	 sum(sizeinbytes) as s
   FROM 
   (SELECT
  
   login al_login,
   ipaddress al_ipaddress,
   sizeinbytes,
   sa.name al_name,
   sa.id al_id,
   sa.typeid al_typeid, 
   sa.tableid al_tableid,
   sq.site
FROM
  scsq_quicktraffic sq
INNER JOIN scsq_alias sa 
ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 

WHERE
  sq.date>".$datestart." 
  AND sq.date<".$dateend."
  AND login IN (SELECT id from scsq_logins where id NOT IN (".$goodLoginsList.")) 
  AND ipaddress IN (SELECT id from scsq_ipaddress where id NOT IN (".$goodIpaddressList.")) 
  AND sq.site NOT IN (".$goodSitesList.")
  
  ) alias_traffic
  INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_traffic.al_id AND sag.groupid='".$currentgroupid."'

   GROUP BY site
   ORDER BY s desc 
   LIMIT ".$globalSS['countTopSitesLimit']." ";



#postgre version
if($dbtype==1)
$queryOneGroupTopSitesTraffic="
SELECT 
site,
sum(sizeinbytes) as s
FROM 
(SELECT

login al_login,
ipaddress al_ipaddress,
sizeinbytes,
sa.name al_name,
sa.id al_id,
sa.typeid al_typeid, 
sa.tableid al_tableid,
sq.site
FROM
scsq_quicktraffic sq
INNER JOIN scsq_alias sa 
ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 
WHERE
sq.date>".$datestart." 
AND sq.date<".$dateend."
AND login IN (SELECT id from scsq_logins where id NOT IN (".$goodLoginsList.")) 
AND ipaddress IN (SELECT id from scsq_ipaddress where id NOT IN (".$goodIpaddressList.")) 
AND sq.site NOT IN (".$goodSitesList.")

) alias_traffic
INNER JOIN scsq_aliasingroups sag ON sag.aliasid = alias_traffic.al_id AND sag.groupid='".$currentgroupid."'

GROUP BY site
ORDER BY s desc 
LIMIT ".$globalSS['countTopSitesLimit']." ";




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
login al_login,
ipaddress al_ipaddress,
sizeinbytes s,
sa.name al_name,
sa.id al_id,
sa.typeid al_typeid, 
sa.tableid al_tableid,
sq.site
FROM
scsq_quicktraffic sq
INNER JOIN scsq_alias sa 
ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 

WHERE
sq.date>".$datestart." 
AND sq.date<".$dateend."
AND login IN (SELECT id from scsq_logins where id NOT IN (".$goodLoginsList.")) 
AND ipaddress IN (SELECT id from scsq_ipaddress where id NOT IN (".$goodIpaddressList.")) 
AND sq.site NOT IN (".$goodSitesList.")
) 
  AS tmp 
  INNER JOIN scsq_aliasingroups sag ON sag.aliasid = tmp.al_id AND sag.groupid='".$currentgroupid."'

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
$queryOneGroupTrafficByHours="
SELECT hrs.hr_txt,
	  tmp2.sum_bytes,
	  hrs.hr 
	  FROM (
SELECT 
  cast(to_char(to_timestamp(tmp.date),'HH24') as int) as d,
  sum(tmp.s) sum_bytes
FROM (SELECT
date,
login al_login,
ipaddress al_ipaddress,
sizeinbytes s,
sa.name al_name,
sa.id al_id,
sa.typeid al_typeid, 
sa.tableid al_tableid,
sq.site
FROM
scsq_quicktraffic sq
INNER JOIN scsq_alias sa 
ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 

WHERE
sq.date>".$datestart." 
AND sq.date<".$dateend."
AND login IN (SELECT id from scsq_logins where id NOT IN (".$goodLoginsList.")) 
AND ipaddress IN (SELECT id from scsq_ipaddress where id NOT IN (".$goodIpaddressList.")) 
AND sq.site NOT IN (".$goodSitesList.")
  ) 
  AS tmp 
  INNER JOIN scsq_aliasingroups sag ON sag.aliasid = tmp.al_id AND sag.groupid='".$currentgroupid."'

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
			   
			   order by hrs.hr asc ; ";



$queryOneGroupWhoDownloadBigFiles="
SELECT 
  scsq_logins.name,
  sizeinbytes,
  scsq_ipaddress.name,
  site,
  al_login,
  al_ipaddress 
FROM (SELECT
date,
login al_login,
ipaddress al_ipaddress,
sizeinbytes,
sa.name al_name,
sa.id al_id,
sa.typeid al_typeid, 
sa.tableid al_tableid,
sq.site
FROM
scsq_traffic sq
INNER JOIN scsq_alias sa 
ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 

WHERE
sq.date>".$datestart." 
AND sq.date<".$dateend."
AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
  ) 
  AS tmp
  INNER JOIN scsq_aliasingroups sag ON sag.aliasid = tmp.al_id AND sag.groupid='".$currentgroupid."'

  , scsq_logins, scsq_ipaddress

  WHERE scsq_logins.id=tmp.al_login 
  and scsq_ipaddress.id=tmp.al_ipaddress	
  ORDER BY sizeinbytes desc 
LIMIT ".$globalSS['countWhoDownloadBigFilesLimit'].";";


#postgre version
if($dbtype==1)
$queryOneGroupWhoDownloadBigFiles="
SELECT 
  scsq_logins.name,
  sizeinbytes,
  scsq_ipaddress.name,
  site,
  al_login,
  al_ipaddress 
FROM (SELECT
date,
login al_login,
ipaddress al_ipaddress,
sizeinbytes,
sa.name al_name,
sa.id al_id,
sa.typeid al_typeid, 
sa.tableid al_tableid,
sq.site
FROM
scsq_traffic sq
INNER JOIN scsq_alias sa 
ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 

WHERE
  sq.date>".$datestart." 
  AND sq.date<".$dateend."
	   AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
	AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
  ) 
  AS tmp
  INNER JOIN scsq_aliasingroups sag ON sag.aliasid = tmp.al_id AND sag.groupid='".$currentgroupid."'

  , scsq_logins,scsq_ipaddress 

WHERE scsq_logins.id=tmp.al_login 
  and scsq_ipaddress.id=tmp.al_ipaddress

ORDER BY sizeinbytes desc 
LIMIT ".$globalSS['countWhoDownloadBigFilesLimit'].";";



$queryOneGroupPopularSites="
  SELECT 
	SUBSTRING_INDEX(tmp.site,'/',1) AS st,
	sum(sizeinbytes) AS s,
	count(*) AS c
	
  FROM (SELECT
  date,
  login al_login,
  ipaddress al_ipaddress,
  sizeinbytes,
  sa.name al_name,
  sa.id al_id,
  sa.typeid al_typeid, 
  sa.tableid al_tableid,
  sq.site
  FROM
  scsq_traffic sq
  INNER JOIN scsq_alias sa 
  ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1)

  WHERE
		sq.date>".$datestart." 
		AND sq.date<".$dateend."
		AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
		AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
  ) tmp
  INNER JOIN scsq_aliasingroups sag ON sag.aliasid = tmp.al_id AND sag.groupid='".$currentgroupid."'

  GROUP BY st
  
ORDER BY c desc 
LIMIT ".$globalSS['countPopularSitesLimit'].";";

#postgre version
if($dbtype==1)

$queryOneGroupPopularSites="

SELECT 
	split_part(site,'/',1) AS st,
	 sum(sizeinbytes) AS s,
	count(*) AS c
	
  FROM 
  (SELECT
  date,
  login al_login,
  ipaddress al_ipaddress,
  sizeinbytes,
  sa.name al_name,
  sa.id al_id,
  sa.typeid al_typeid, 
  sa.tableid al_tableid,
  sq.site
  FROM
  scsq_traffic sq
  INNER JOIN scsq_alias sa 
  ON (sa.tableid=sq.login and sa.typeid=0) or (sa.tableid=sq.ipaddress and sa.typeid=1) 

  WHERE
		sq.date>".$datestart." 
		AND sq.date<".$dateend."
	   AND reverse(split_part(reverse(split_part(site,'/',1)),'.',2)) NOT IN (".$goodSitesList.")
		AND split_part(site,'/',1)  NOT IN (".$goodSitesList.")
	  ) tmp

  INNER JOIN scsq_aliasingroups sag ON sag.aliasid = tmp.al_id AND sag.groupid='".$currentgroupid."'

  GROUP BY st

ORDER BY c desc 
LIMIT ".$globalSS['countPopularSitesLimit'].";";

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


<?php

//Вычитаем все GET параметры и соберем в строку. Чтобы ничего не придумывать.
$dfltAction=doCreateGetArray($globalSS);

echo '<form name=fastdateswitch_form action="reports.php?'.$dfltAction.'" method=POST>';
?>
<table align=right>
<tr>
  <td><?php echo $_lang['stFASTDATESWITCH']?></td>
  <td>
<input type="text" name=date value="<?php echo $querydate;?>"  onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)">
<input type="text" name=date2 value="<?php echo $querydate2;?>" onfocus="this.select();lcs(this)" onclick="event.cancelBubble=true;this.select();lcs(this)">&nbsp;&nbsp;
<input type="submit" value="GO">&nbsp;&nbsp;

  &nbsp;&nbsp;

</p>
</td>

</tr>

</table>

</form>
<br><br>



<?php
}
///CALENDAR END



///REPORTS HEADERS

$repheader="";

if($id==1)
$repheader="<h2>".$_lang['stLOGINSTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==2)
$repheader="<h2>".$_lang['stIPADDRESSTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==3)
$repheader= "<h2>".$_lang['stSITESTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==4)
$repheader= "<h2>".$_lang['stTOPSITESTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==5)
$repheader= "<h2>".$_lang['stTOPLOGINSTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==6)
$repheader= "<h2>".$_lang['stTOPIPTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==7)
$repheader= "<h2>".$_lang['stTRAFFICBYHOURS']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==8)
$repheader= "<h2>".$_lang['stONELOGINTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==9)
$repheader= "<h2>".$_lang['stONELOGINTOPSITESTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==10)
$repheader= "<h2>".$_lang['stONELOGINTRAFFICBYHOURS']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==11)
$repheader= "<h2>".$_lang['stONEIPADRESSTRAFFIC']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==12)
$repheader= "<h2>".$_lang['stONEIPADDRESSTOPSITESTRAFFIC']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==13)
$repheader= "<h2>".$_lang['stONEIPADDRESSTRAFFICBYHOURS']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==14)
$repheader= "<h2>".$_lang['stLOGINSTRAFFICWIDE']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==15)
$repheader= "<h2>".$_lang['stIPADDRESSTRAFFICWIDE']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==16)
$repheader= "<h2>".$_lang['stIPADDRESSTRAFFICWITHRESOLVE']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==17)
$repheader= "<h2>".$_lang['stPOPULARSITES']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==18)
$repheader= "<h2>".$_lang['stWHOVISITSITELOGIN']." <b>".$currentsite."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==19)
$repheader= "<h2>".$_lang['stWHOVISITSITEIPADDRESS']." <b>".$currentsite."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==20)
$repheader= "<h2>".$_lang['stWHODOWNLOADBIGFILES']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==21)
$repheader= "<h2>".$_lang['stTRAFFICBYPERIOD']."</h2>";

if($id==22)
$repheader= "<h2>".$_lang['stVISITINGWEBSITELOGINS']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate_str." ".$dayname." ".$_lang['stBYDAYTIME']."</h2>";

if($id==23)
$repheader= "<h2>".$_lang['stVISITINGWEBSITEIPADDRESS']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate_str." ".$dayname." ".$_lang['stBYDAYTIME']."</h2>";

if($id==24)
$repheader= "<h2>".$_lang['stGROUPSTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==25)
$repheader= "<h2>".$_lang['stONEGROUPTRAFFIC']." ".$currentgroup." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==26)
$repheader= "<h2>".$_lang['stONEGROUPTRAFFIC']." ".$currentgroup." ".$_lang['stFOR']." ".$querydate_str." ".$dayname." ".$_lang['stEXTENDED']."</h2>";

if($id==27)
$repheader= "<h2>".$_lang['stONEGROUPTOPSITESTRAFFIC']." ".$currentgroup." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==28)
$repheader= "<h2>".$_lang['stONEGROUPTRAFFICBYHOURS']." ".$currentgroup." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==29)
$repheader= "<h2>".$_lang['stONEGROUPWHODOWNLOADBIGFILES']." ".$currentgroup." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==30)
$repheader= "<h2>".$_lang['stHTTPSTATUSES']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==31)
$repheader= "<h2>".$_lang['stHTTPSTATUS']." ".$currenthttpname." ".$_lang['stLOGINSFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==32)
$repheader= "<h2>".$_lang['stHTTPSTATUS']." ".$currenthttpname." ".$_lang['stIPADDRESSFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==33)
$repheader= "<h2>".$_lang['stHTTPSTATUS']." ".$currenthttpname." (".$currentloginname.") ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==34)
$repheader= "<h2>".$_lang['stHTTPSTATUS']." ".$currenthttpname." (".$currentipname.") ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==35)
$repheader= "<h2>".$_lang['stONELOGINIPTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==36)
$repheader= "<h2>".$_lang['stONEIPADDRESSLOGINSTRAFFIC']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==37)
$repheader= "<h2>".$_lang['stLOGINSIPTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==38)
$repheader= "<h2>".$_lang['stIPADDRESSLOGINSTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==39)
$repheader= "<h2>".$_lang['stTRAFFICBYPERIODDAY']."</h2>";

if($id==40)
$repheader= "<h2>".$_lang['stTRAFFICBYPERIODDAYNAME']."</h2>";

if($id==41)
$repheader= "<h2>".$_lang['stWHOVISITSITES']." ".$_lang['stFOR']." ".$querydate_str." ".$_lang['stFROM']." ".$currenthour." ".$_lang['stUNTIL']." ".($currenthour+1)."</h2>";

if($id==42)
$repheader= "<h2>".$_lang['stWHOVISITSITES']." ".$_lang['stFOR']." ".$querydate_str." ".$_lang['stFROM']." ".$currenthour." ".$_lang['stUNTIL']." ".($currenthour+1)."</h2>";

if($id==43)
$repheader= "<h2>".$_lang['stONELOGINTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate_str." ".$dayname." ".$_lang['stFROM']." ".$currenthour." ".$_lang['stUNTIL']." ".($currenthour+1)."</h2>";

if($id==44)
$repheader= "<h2>".$_lang['stONEIPADRESSTRAFFIC']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate_str." ".$dayname." ".$_lang['stFROM']." ".$currenthour." ".$_lang['stUNTIL']." ".($currenthour+1)."</h2>";

if($id==45)
$repheader= "<h2>".$_lang['stMIMETYPESTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==46)
$repheader= "<h2>".$_lang['stMIMETYPESTRAFFIC']." ".$currentlogin." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==47)
$repheader= "<h2>".$_lang['stMIMETYPESTRAFFIC']." ".$currentipaddress." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==48)
$repheader= "<h2>".$_lang['stDOMAINZONESTRAFFIC']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==49)
$repheader= "<h2>".$_lang['stDASHBOARD']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==50)
$repheader= "<h2>".$_lang['stTRAFFICBYHOURSLOGINS']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==51)
$repheader= "<h2>".$_lang['stTRAFFICBYHOURSIPADDRESS']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==52)
$repheader= "<h2>".$_lang['stTRAFFICBYCATEGORIES']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==53)
$repheader= "<h2>".$_lang['stTRAFFICBYHOURSLOGINSONESITE']." <b>".$currentsite."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==54)
$repheader= "<h2>".$_lang['stTRAFFICBYHOURSIPADDRESSONESITE']." <b>".$currentsite."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==55)
$repheader= "<h2>".$_lang['stPOPULARSITES']." <b>".$currentgroup."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==56)
$repheader= "<h2>".$_lang['stPOPULARSITES']." <b>".$currentlogin."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==57)
$repheader= "<h2>".$_lang['stPOPULARSITES']." <b>".$currentipaddress."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==58)
$repheader= "<h2>".$_lang['stCONTENT']." <b>".$currentmime."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==59)
$repheader= "<h2>".$_lang['stCONTENT']." <b>".$currentmime."</b> <b>".$currentlogin."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==60)
$repheader= "<h2>".$_lang['stCONTENT']." <b>".$currentmime."</b> <b>".$currentipaddress."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==61)
$repheader= "<h2>".$_lang['stDASHBOARD']." <b>".$currentlogin."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==62)
$repheader= "<h2>".$_lang['stDASHBOARD']." <b>".$currentipaddress."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==63)
$repheader= "<h2>".$_lang['stDASHBOARD']." <b>".$currentgroup."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==64)
$repheader= "<h2>".$_lang['stLOGINSTIMEONLINE']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==65)
$repheader= "<h2>".$_lang['stIPADDRESSTIMEONLINE']." ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==66)
$repheader= "<h2>".$_lang['stLOGINBIGFILES']." <b>".$currentlogin."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==67)
$repheader= "<h2>".$_lang['stIPADDRESSBIGFILES']." <b>".$currentipaddress."</b> ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==68)
$repheader= "<h2>".$_lang['stTOPLOGINSWORKINGHOURSTRAFFIC']." (".$workStart1." - ".$workEnd1." | ".$workStart2." - ".$workEnd2.") ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";

if($id==69)
$repheader= "<h2>".$_lang['stTOPIPWORKINGHOURSTRAFFIC']." (".$workStart1." - ".$workEnd1." | ".$workStart2." - ".$workEnd2.") ".$_lang['stFOR']." ".$querydate_str." ".$dayname."</h2>";


if(!isset($_GET['pdf']) && !isset($_GET['csv'])) {

echo "<table width='100%'>";
echo "<tr>";
echo "<td valign=middle width='80%'>".$repheader."</td>";
}



$globalSS['repheader'] = $repheader;
$globalSS['typeid'] = $typeid;


if(!isset($_GET['pdf']) && !isset($_GET['csv'])) {
echo "<td valign=top>&nbsp;&nbsp;<a href=reports.php?date=".$querydate."&date2=".$querydate2."&".$dfltAction."&pdf=1><img src='../../../img/pdficon.jpg' width=32 height=32 alt=Image title='Generate PDF'></a>
							   <a href=reports.php?date=".$querydate."&date2=".$querydate2."&".$dfltAction."&csv=1><img src='../../../img/csvicon.png' width=32 height=32 alt=Image title='Generate CSV'></a>";
#Если файл есть в кэше, то покажем иконку - кэшировано. Если нажать на неё, то удалится только один файл этого отчёта
if(file_exists ("".$globalSS['root_dir']."/modules/Cache/data/".doGenerateUniqueNameReport($globalSS['params'])))
echo "							 <a href=reports.php?date=".$querydate."&date2=".$querydate2."&".$dfltAction."&clearcache=1><img src='../../../img/cached.png' width=35 height=35 alt='Image' title='Clear cache'></a>";

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

if($globalSS['makepdf']==0 && $globalSS['makecsv']==0)
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
  if($globalSS['makepdf']==0 && $globalSS['makecsv']==0)
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

if($globalSS['makepdf']==0 && $globalSS['makecsv']==0)
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
while ($numrow<$globalSS['countTopLoginLimit'])
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
$userData['charttitle']=$_lang['stTOPLOGINSTRAFFIC']." (".$_lang['stTOP']."-".$globalSS['countTopLoginLimit'].")";
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
while ($numrow<$globalSS['countTopIpLimit'])
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
$userData['charttitle']=$_lang['stTOPIPTRAFFIC']." (".$_lang['stTOP']."-".$globalSS['countTopIpLimit'].")";
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
while ($numrow<$globalSS['countTopSitesLimit'])
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
$userData['charttitle']=$_lang['stTOPSITESTRAFFIC']." (".$_lang['stTOP']."-".$globalSS['countTopSitesLimit'].")";
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
while ($numrow<$globalSS['countPopularSitesLimit'])
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
$userData['charttitle']=$_lang['stPOPULARSITES']." (".$_lang['stTOP']."-".$globalSS['countPopularSitesLimit'].")";
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
while ($numrow<$globalSS['countTopSitesLimit'])
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
$userData['charttitle']=$_lang['stTOPSITESTRAFFIC']." (".$_lang['stTOP']."-".$globalSS['countTopSitesLimit'].")";
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
while ($numrow<$globalSS['countPopularSitesLimit'])
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
$userData['charttitle']=$_lang['stPOPULARSITES']." (".$_lang['stTOP']."-".$globalSS['countPopularSitesLimit'].")";
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
while ($numrow<$globalSS['countTopSitesLimit'])
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
$userData['charttitle']=$_lang['stTOPSITESTRAFFIC']." (".$_lang['stTOP']."-".$globalSS['countTopSitesLimit'].")";
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
while ($numrow<$globalSS['countPopularSitesLimit'])
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
$userData['charttitle']=$_lang['stPOPULARSITES']." (".$_lang['stTOP']."-".$globalSS['countPopularSitesLimit'].")";
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
while ($numrow<$globalSS['countTopSitesLimit'])
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
$userData['charttitle']=$_lang['stTOPSITESTRAFFIC']." (".$_lang['stTOP']."-".$globalSS['countTopSitesLimit'].")";
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
while ($numrow<$globalSS['countPopularSitesLimit'])
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
$userData['charttitle']=$_lang['stPOPULARSITES']." (".$_lang['stTOP']."-".$globalSS['countPopularSitesLimit'].")";
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





    





} //if !isset cookie[logged]



//check login end


///mysql_disconnect();



?>

