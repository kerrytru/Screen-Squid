<?php
#build 20170126

$header='<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../javascript/example.css"/>
</head>
<body>
';

include("../config.php");

 // Standard pChart inclusions
 include("../lib/pChart/pChart/pData.class");
 include("../lib/pChart/pChart/pChart.class");

// Include the main TCPDF library (search for installation path).
include("../lib/tcpdf/tcpdf.php");


if(!isset($_GET['pdf']))
{
echo $header;
$makepdf=0;
}
else
$makepdf=1;


$start=microtime(true);

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;

// Javascripts

if(!isset($_GET['pdf']))
{
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
  if(dom=='day') {
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

/*
JS function to open reports page with some additional parameters.
idReport - id of report
dom - day or month report
id - id login or id ipaddress or id group
idname - visible name login(Yoda) or name ipaddress(172.16.120.33) or name group(StarWars club)
idsign - login (0) or ipaddress (1) or group login (3) or group ipaddress (4) or httpstatus id >4
par1 - sitename if report need it or httpstatus name


*/
function GoPartlyReports(idReport, dom, id, idname, idsign, par1)
{

	if(idsign==0)
	{
		parent.right.location.href='reports.php?srv=<?php echo $srv ?>&id='+idReport+
		'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&login='+id
		+'&loginname='+idname
		+'&site='+par1;
	}
	if(idsign==1)
	{
		parent.right.location.href='reports.php?srv=<?php echo $srv ?>&id='+idReport
		+'&date='+window.document.fastdateswitch_form.date_field_hidden.value
		+'&dom='+dom
		+'&ip='+id
		+'&ipname='+idname
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
  parent.left.location.href='../left.php?srv=<?php echo $srv ?>&id='+id;
}

</script>

<script type="text/javascript" src="../javascript/sortable.js"></script>


<?php
}
// Javascripts END



$address=$address[$srv];
$user=$user[$srv];
$pass=$pass[$srv];
$db=$db[$srv];


mysql_connect("$address", "$user", "$pass") or die(mysql_error());
mysql_select_db($db);



if(isset($_GET['date']))
  $querydate=$_GET['date'];
else
  $querydate=date("d-m-Y");

list($day,$month,$year) = split('[/.-]', $querydate);

if(isset($_GET['dom']))
  $dayormonth=$_GET['dom'];
else
  $dayormonth="day";

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
else {
  $querydom="%m-%Y";
  $querydate=$month."-".$year;
  $numdaysinmonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  $datestart=mktime(0,0,0,$month,1,$year);
  $dateend=$datestart + 86400*$numdaysinmonth;
}

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

if($enableShowDayNameInReports==0)
$dayname="";

if($dayormonth=="month")
$dayname="";


if(isset($_GET['loginname']))
  $currentlogin=$_GET['loginname'];
else
  $currentlogin="";

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
//костыль для отчетов 41 и 42 и 43 и 44
$currenthour=$_GET['site'];
}
else
{
  $currentsite="";
  $currenthour=$_GET['site'];
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

  $goodLoginsList="0";
  $goodIpaddressList="0";

#create list of friends
if($enableNofriends==1) {
  $friends=implode("','",explode(" ", $goodLogins));
  $friendsTmp="where name in  ('".$friends."')";
  $sqlGetFriendsId="select id from scsq_logins ".$friendsTmp."";
  $result=mysql_query($sqlGetFriendsId) or die(mysql_error());
  while ($fline = mysql_fetch_array($result,MYSQL_NUM)) {
    $goodLoginsList=$goodLoginsList.",".$fline[0];
  }
 $friends=""; 
 $friends=implode("','",explode(" ", $goodIpaddress));
 $friendsTmp="where name in ('".$friends."')";
  $sqlGetFriendsId="select id from scsq_ipaddress ".$friendsTmp."";
  $result=mysql_query($sqlGetFriendsId) or die(mysql_error());
  while ($fline = mysql_fetch_array($result,MYSQL_NUM)) {
    $goodIpaddressList=$goodIpaddressList.",".$fline[0];
  }
 
}
else {
  $goodLoginsList="0";
  $goodIpaddressList="0";
}

#create list of good sites
if($enableNoSites==1) {
  $sitesTmp=implode("','",explode(" ", $goodSites));
  $sitesTmp="'".$sitesTmp."'";
  $goodSitesList=$sitesTmp;

}
else {
  $goodSitesList="''";
}


if($showZeroTrafficInReports==1)
  $msgNoZeroTraffic="";
else
  $msgNoZeroTraffic=" where tmp.s!=0 ";


//querys for reports

if($useLoginalias==0)
$echoLoginAliasColumn="";
if($useLoginalias==1)
$echoLoginAliasColumn=",aliastbl.name";

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

  ".$msgNoZeroTraffic."

  GROUP BY nofriends.name;";


$queryLoginsTraffic1="
  SELECT 
    nofriends.name,
    tmp.s,
    nofriends.id
    echoLoginAliasColumn
  FROM (SELECT 
          login,
          SUM(sizeinbytes) as 's' 
        FROM scsq_quicktraffic 
        WHERE  date>datestart
	   AND date<=dateend
	   AND site NOT IN (goodSitesList)
	   AND par=1
	GROUP BY CRC32(login)
	ORDER BY null) 
	AS tmp 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (goodLoginsList)) 
		    AS nofriends 
	ON tmp.login=nofriends.id  
 	LEFT JOIN (SELECT 
		      name,
		      tableid 
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   AS aliastbl 
	ON nofriends.id=aliastbl.tableid 

  msgNoZeroTraffic

  GROUP BY nofriends.name;";

$queryLoginsTraffic1=str_replace("goodSitesList",$goodSitesList,$queryLoginsTraffic1);
$queryLoginsTraffic1=str_replace("goodLoginsList",$goodLoginsList,$queryLoginsTraffic1);
$queryLoginsTraffic1=str_replace("datestart",$datestart,$queryLoginsTraffic1);
$queryLoginsTraffic1=str_replace("dateend",$dateend,$queryLoginsTraffic1);
$queryLoginsTraffic1=str_replace("msgNoZeroTraffic",$msgNoZeroTraffic,$queryLoginsTraffic1);

#$queryLoginsTraffic=$queryLoginsTraffic1;

///echo $queryLoginsTraffic1;

//echo $queryLoginsTraffic1;

if($useIpaddressalias==0)
$echoIpaddressAliasColumn="";
if($useIpaddressalias==1)
$echoIpaddressAliasColumn=",aliastbl.name";

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

  ".$msgNoZeroTraffic."

  GROUP BY nofriends.name ;";


//echo $queryIpaddressTraffic;

$querySitesTraffic="
  SELECT tmp2.site,
	 tmp2.s,
	 IF(concat('',(LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(tmp2.site,'/',1),'.',-1),10),1)) * 1)=(LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(tmp2.site,'/',1),'.',-1),10),1)),1,2)
  
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
 

  ORDER BY site asc
;";
////, scsq_categorylist.category
//	  LEFT OUTER JOIN scsq_categorylist ON tmp2.site=scsq_categorylist.site

//echo $querySitesTraffic;

$queryTopSitesTraffic="
  SELECT tmp2.site,
	 tmp2.s,
	 IF(concat('',(LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(tmp2.site,'/',1),'.',-1),10),1)) * 1)=(LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(tmp2.site,'/',1),'.',-1),10),1)),1,2)
  
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
 
	 
  ORDER BY tmp2.s desc
  LIMIT ".$countTopSitesLimit." ";


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
	GROUP BY CRC32(login) 
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
  LIMIT ".$countTopLoginLimit.";";


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
	GROUP BY CRC32(ipaddress) 
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


$queryTrafficByHours="
  SELECT 
    FROM_UNIXTIME(tmp.date,'%H') AS d,
    SUM(tmp.s) 
  FROM (SELECT 
	  date,
	  SUM(sizeinbytes) AS s,
	  login,
	  ipaddress 
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
	GROUP BY CRC32(date) 
	ORDER BY null) 
	AS tmp 

  GROUP BY d;";


$queryLoginsTrafficWide="
  SELECT 
    nofriends.name,
    tmp.s,
    tmp.login,
    tmp.n 
  FROM ((SELECT 
	   login,
	   '2' AS n,
	   SUM(sizeinbytes) AS s 
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
	 GROUP BY CRC32(login) 
	 ORDER BY null) 

  UNION 

	(SELECT 
	   login,
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
	 GROUP BY CRC32(login) 
	 ORDER BY null) 

  UNION 

	(SELECT 
	   login,
	   '1' AS n,
	   SUM(sizeinbytes) AS s 
	 FROM scsq_quicktraffic 
	 WHERE date>".$datestart." 
	   AND date<".$dateend."  
	   AND site NOT IN (".$goodSitesList.")
 	   AND par=1	   
	 GROUP BY crc32(login) 
	 ORDER BY null)) 
	 AS tmp

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_logins 
		     WHERE id NOT IN (".$goodLoginsList.")) AS nofriends 
	 ON tmp.login=nofriends.id 

  ORDER BY nofriends.name asc,tmp.n asc;";


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
	 GROUP BY CRC32(ipaddress) 
	 ORDER BY null) 

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
	 GROUP BY CRC32(ipaddress) 
	 ORDER BY null) 

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
	 GROUP BY CRC32(ipaddress) 
	 ORDER BY null)) 
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
	  date,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	WHERE date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT IN (".$goodSitesList.")
 	  AND par=1
	GROUP BY CRC32(ipaddress) 
	ORDER BY null) 
	AS tmp

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    WHERE id NOT IN (".$goodIpaddressList.")) 
		    AS nofriends 
	ON tmp.ipaddress=nofriends.id 

  ".$msgNoZeroTraffic."

  ORDER BY nofriends.name asc;";


$queryPopularSites="
  SELECT SUBSTRING_INDEX(scsq_traffic.site,'/',1) as stt,
	 tmp.s,
	 tmp.c
  FROM (SELECT 
	  CRC32(SUBSTRING_INDEX(site,'/',1)) AS st,
	  count(*) AS c,
	  sum(sizeinbytes) AS s,
	  scsq_traffic.id
	  
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
   

	GROUP BY st
	ORDER BY null) 
	AS tmp 
  JOIN scsq_traffic ON tmp.id=scsq_traffic.id
  WHERE SUBSTRING_INDEX(scsq_traffic.site,'/',1) NOT IN (".$goodSitesList.")
  ORDER BY tmp.c desc 
  LIMIT ".$countPopularSitesLimit.";";


$queryWhoDownloadBigFiles="
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

  	ORDER BY sizeinbytes desc 
  	LIMIT ".$countWhoDownloadBigFilesLimit.")


	  AS tmp

  INNER JOIN scsq_traffic as scsq_traf on scsq_traf.id=tmp.id
  INNER JOIN scsq_logins as scsq_log on scsq_log.id=tmp.login
  INNER JOIN scsq_ipaddress as scsq_ip on scsq_ip.id=tmp.ipaddress
";

$queryTrafficByPeriod="
  	SELECT
	  FROM_UNIXTIME(scsq_quicktraffic.date,'%m.%Y') AS d1,
	  SUM(scsq_quicktraffic.sizeinbytes), 
	  ipaddress,
	  login

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
	GROUP BY crc32(FROM_UNIXTIME(scsq_quicktraffic.date,'%Y-%m'))
	ORDER BY FROM_UNIXTIME(scsq_quicktraffic.date,'%Y-%m') asc;
;";


$queryTrafficByPeriodDay="
  	SELECT
	  FROM_UNIXTIME(scsq_quicktraffic.date,'%d.%m.%Y') AS d1,
	  SUM(scsq_quicktraffic.sizeinbytes), 
	  ipaddress,
	  login
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
	GROUP BY crc32(FROM_UNIXTIME(scsq_quicktraffic.date,'%Y-%m-%d'))
	ORDER BY FROM_UNIXTIME(scsq_quicktraffic.date,'%Y-%m-%d') asc;
;";


$queryTrafficByPeriodDayname="
  	SELECT
	  FROM_UNIXTIME(scsq_quicktraffic.date,'%w') AS d1,
	  SUM(scsq_quicktraffic.sizeinbytes), 
	  ipaddress,
	  login
	  
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
	GROUP BY crc32(d1)
	
;";


//echo $queryTrafficByPeriodDayname;


$queryHttpStatus= "
  SELECT 
    scsq_httpstatus.name,
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
  GROUP BY CRC32(httpstatus) 
  ORDER BY scsq_httpstatus.name asc;";

$queryCountIpaddressOnLogins="
  SELECT 
    scsq_logins.name,
    count(*) AS ct,
    scsq_logins.id,
    tmp2.name 
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
  GROUP BY tmp.login 
  ORDER BY scsq_logins.name asc;";

$queryCountLoginsOnIpaddress="
  SELECT
    scsq_ipaddress.name,
    count(*) AS ct,
    scsq_ipaddress.id,
    tmp2.name 
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

  GROUP BY tmp.ipaddress 
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

  GROUP BY CRC32(mime) 
  ORDER BY st desc;";


$queryDomainZonesTraffic="
	((SELECT 
		 '-',
		 sum(sizeinbytes) as s,
		 LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-1),10),1) as lastnum
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
		 AND concat('',(LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-1),10),1)) * 1) = LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-1),10),1)
		 AND par<2
	       ORDER BY null
	     ) )	       

UNION


 (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(concat(site,'/'),'/',1),'.',-1),':',1),
	 tmp.s,
	 tmp.stn
  
  FROM 	(SELECT 
	  '2' as stn,
	   tmp3.s,
	   tmp3.login,
	   tmp3.ipaddress,
	   tmp3.site
	 FROM (SELECT 
		 site,
		 sum(sizeinbytes) as s,
		 date,
		 login,
		 ipaddress,
		 LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-1),10),1) as lastnum
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
	       GROUP BY CRC32(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(concat(site,'/'),'/',1),'.',-1),':',1))
	       ORDER BY site asc
	      ) as tmp3
	
	 WHERE concat('',lastnum * 1) <> lastnum

	 

       ) AS tmp 


  ORDER BY site asc
)
;
";



$queryTrafficByHoursLogins="
SELECT  login,
nofriends.name,
sum(sizeinbytes),
FROM_UNIXTIME(date,'%k')
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
GROUP BY login,FROM_UNIXTIME(date,'%H')
ORDER BY nofriends.name
;
";

$queryTrafficByHoursIpaddress="
SELECT  ipaddress,
nofriends.name,
sum(sizeinbytes),
FROM_UNIXTIME(date,'%k')
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
GROUP BY login,FROM_UNIXTIME(date,'%H')
ORDER BY nofriends.name
;
";

$queryTrafficByHoursLoginsOneSite="
SELECT  login,
nofriends.name,
sum(sizeinbytes),
FROM_UNIXTIME(date,'%k')
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
GROUP BY login,FROM_UNIXTIME(date,'%H')
ORDER BY nofriends.name
;
";

$queryTrafficByHoursIpaddressOneSite="
SELECT  ipaddress,
nofriends.name,
sum(sizeinbytes),
FROM_UNIXTIME(date,'%k')
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
GROUP BY ipaddress,FROM_UNIXTIME(date,'%H')
ORDER BY nofriends.name
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
	   SUM(sizeinbytes) AS s,
	   scsq_categorylist.category 
	 FROM scsq_quicktraffic 
	 LEFT OUTER JOIN scsq_categorylist ON scsq_quicktraffic.site=scsq_categorylist.site
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	   AND par=1
	 GROUP BY CRC32(scsq_quicktraffic.site) 
	 ORDER BY site asc
;";

$queryOneLoginTopSitesTraffic="
	 SELECT 
	   site, 
	   SUM( sizeinbytes ) AS s
	 FROM scsq_quicktraffic
	 WHERE login=".$currentloginid." 
	   AND date>".$datestart." 
	   AND date<".$dateend."
	   AND site NOT IN (".$goodSitesList.")
	   AND par=1
	 GROUP BY CRC32(site)
	 ORDER BY s DESC
	 LIMIT ".$countTopSitesLimit." 
";

$queryOneLoginTrafficByHours="
  SELECT 
    FROM_UNIXTIME(tmp.date,'%H') AS d,
    SUM(tmp.s) 
  FROM (SELECT 
	  date,
	  SUM(sizeinbytes) AS s 
	FROM scsq_quicktraffic 
	
	WHERE login=".$currentloginid." 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
          AND site NOT IN (".$goodSitesList.")
	  AND par=1	
	GROUP BY CRC32(date) 
	ORDER BY null) 
	AS tmp 

  GROUP BY d 
  ORDER BY d asc;";

#костыль для правильного разбора сайтов
if($currentloginid==1)
if($useLoginalias==0)
$queryWhoVisitPopularSiteLogin="
  SELECT 
    nofriends.name, 
    tmp.s,
    'костыль',
    nofriends.id 
  FROM (SELECT 
	  login,
	  SUM(sizeinbytes) AS s 
	FROM scsq_traffic

	WHERE SUBSTRING_INDEX(site,'/',1)='".$currentsite."' 
	  AND date>".$datestart." 
	  AND date<".$dateend."
	
	GROUP BY CRC32(login)) 
	AS tmp 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (".$goodLoginsList.")) 
		    AS nofriends 
	ON tmp.login=nofriends.id  

  ".$msgNoZeroTraffic."

  ORDER BY nofriends.name;";
else
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

	WHERE SUBSTRING_INDEX(site,'/',1)='".$currentsite."' 
	  AND date>".$datestart." 
	  AND date<".$dateend."
	
	GROUP BY CRC32(login)) 
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

  ".$msgNoZeroTraffic."

  ORDER BY nofriends.name;";


if($currentloginid==2)
if($useLoginalias==0)
$queryWhoVisitPopularSiteLogin="
  SELECT 
    nofriends.name, 
    tmp.s,
    'костыль',
    nofriends.id
  FROM (SELECT 
	  login,
	  SUM(sizeinbytes) AS s 
	FROM scsq_traffic

	WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(scsq_traffic.site,'/',1),'.',-2)='".$currentsite."' 
	  AND date>".$datestart." 
	  AND date<".$dateend."
	
	GROUP BY CRC32(login)) 
	AS tmp 

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_logins 
		    WHERE id NOT IN (".$goodLoginsList.")) 
		    AS nofriends 
	ON tmp.login=nofriends.id  

  ".$msgNoZeroTraffic."

  ORDER BY nofriends.name;";
else
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

	WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(scsq_traffic.site,'/',1),'.',-2)='".$currentsite."' 
	  AND date>".$datestart." 
	  AND date<".$dateend."
	
	GROUP BY CRC32(login)) 
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

  ".$msgNoZeroTraffic."

  ORDER BY nofriends.name;";

$queryVisitingWebsiteByTimeLogin="
  SELECT 
    DISTINCT FROM_UNIXTIME(tmp.date,'%d-%m-%Y %H:%i:%s') AS d,
    '0',
    site 
  FROM (SELECT 
	  date,
	  site 
	FROM scsq_traffic 
	WHERE login=".$currentloginid." 
	  AND date>".$datestart." 
	  AND date<".$dateend." 
	  AND site NOT LIKE '%.js%' 
	  AND site NOT LIKE '%.css%' 
	  AND sizeinbytes>8192
          AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
          AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
  
	ORDER BY null) 
	AS tmp  
  
  ORDER BY d asc;";



$queryOneIpaddressTraffic="
	 SELECT 
	   scsq_quicktraffic.site AS st,
	   sum(sizeinbytes) AS s,
	   scsq_categorylist.category 
	 FROM scsq_quicktraffic 
	 LEFT OUTER JOIN scsq_categorylist ON scsq_quicktraffic.site=scsq_categorylist.site
	 WHERE ipaddress=".$currentipaddressid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND scsq_quicktraffic.site NOT IN (".$goodSitesList.")
	   AND par=1
	 GROUP BY CRC32(scsq_quicktraffic.site) 	 
	 ORDER BY scsq_quicktraffic.site asc;";


$queryOneIpaddressTopSitesTraffic="
	 SELECT 
	   site,
	   SUM(sizeinbytes) as s 
	 FROM scsq_quicktraffic 
	 WHERE ipaddress=".$currentipaddressid." 
	   AND date>".$datestart." 
	   AND date<".$dateend." 
	   AND site NOT IN (".$goodSitesList.")	 
	   AND par=1
	 GROUP BY CRC32(site) 
	 ORDER BY s desc 
	 LIMIT ".$countTopSitesLimit." ";


$queryOneIpaddressTrafficByHours="
  SELECT 
    from_unixtime(tmp.date,'%H') as d,
    sum(tmp.s) 
  from (SELECT 
	  date,
	  sum(sizeinbytes) as s 
	from scsq_quicktraffic 
	where ipaddress=".$currentipaddressid." 
	  and date>".$datestart." 
	  and date<".$dateend." 
          AND site NOT IN (".$goodSitesList.")
	  AND par=1
	group by crc32(date) 
	order by null) 
	as tmp 

  group by d 
  order by d asc;";

#костыль для правильного разбора сайтов
if($currentipaddressid==1)
if($useIpaddressalias==0)
$queryWhoVisitPopularSiteIpaddress="
  SELECT 
    nofriends.name, 
    tmp.s,
    'костыль',
    nofriends.id 
  from (SELECT 
	  ipaddress,
	  sum(sizeinbytes) as s 
	from scsq_traffic 
	where substring_index(site,'/',1)='".$currentsite."' 
	  and date>".$datestart." 
	  and date<".$dateend." 
	
	group by crc32(ipaddress)) 
	as tmp

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    where id NOT IN (".$goodIpaddressList.")) as nofriends 
	ON tmp.ipaddress=nofriends.id  

  ".$msgNoZeroTraffic."

  order by nofriends.name;";
else
$queryWhoVisitPopularSiteIpaddress="
  SELECT 
    nofriends.name, 
    tmp.s,
    tmp2.name,
    nofriends.id 
  from (SELECT 
	  ipaddress,
	  sum(sizeinbytes) as s 
	from scsq_traffic 
	where substring_index(site,'/',1)='".$currentsite."' 
	  and date>".$datestart." 
	  and date<".$dateend." 
	
	group by crc32(ipaddress)) 
	as tmp

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
  ".$msgNoZeroTraffic."

  order by nofriends.name;";


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



#костыль для правильного разбора сайтов
if($currentipaddressid==2)
if($useIpaddressalias==0)
$queryWhoVisitPopularSiteIpaddress="
  SELECT 
    nofriends.name, 
    tmp.s,
    'костыль',
    nofriends.id 
  from (SELECT 
	  ipaddress,
	  sum(sizeinbytes) as s 
	from scsq_traffic 
	where SUBSTRING_INDEX(SUBSTRING_INDEX(scsq_traffic.site,'/',1),'.',-2)='".$currentsite."' 
	  and date>".$datestart." 
	  and date<".$dateend." 
	
	group by crc32(ipaddress)) 
	as tmp

	RIGHT JOIN (SELECT 
		      id,
		      name 
		    FROM scsq_ipaddress 
		    where id NOT IN (".$goodIpaddressList.")) as nofriends 
	ON tmp.ipaddress=nofriends.id  

  ".$msgNoZeroTraffic."

  order by nofriends.name;";
else
$queryWhoVisitPopularSiteIpaddress="
  SELECT 
    nofriends.name, 
    tmp.s,
    tmp2.name,
    nofriends.id
  from (SELECT 
	  ipaddress,
	  sum(sizeinbytes) as s 
	from scsq_traffic 
	where SUBSTRING_INDEX(SUBSTRING_INDEX(scsq_traffic.site,'/',1),'.',-2)='".$currentsite."' 
	  and date>".$datestart." 
	  and date<".$dateend." 
	
	group by crc32(ipaddress)) 
	as tmp

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
  ".$msgNoZeroTraffic."

  order by nofriends.name;";


$queryVisitingWebsiteByTimeIpaddress="
  SELECT DISTINCT 
    from_unixtime(tmp.date,'%d-%m-%Y %H:%i:%s') as d,
    '0',
    site 
  from (SELECT 
	  date,
	  site 
	from scsq_traffic 
	where ipaddress=".$currentipaddressid." 
	  and date>".$datestart." 
	  and date<".$dateend." 
	  and site not like '%.js%' 
	  and site not like '%.css%' 
	  and sizeinbytes>8192  
          AND SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2) NOT IN (".$goodSitesList.")
          AND SUBSTRING_INDEX(site,'/',1)  NOT IN (".$goodSitesList.")
	
	order by null) 
	as tmp  
  
  order by d asc;";

$queryLoginsHttpStatus="
  SELECT 
    nofriends.name,
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

$queryIpaddressHttpStatus="
  SELECT 
    nofriends.name,
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
  GROUP BY CRC32(ipaddress)";

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
  GROUP BY CRC32(login)";


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


//partly queries end

//querys for group reports


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

   GROUP BY crc32(tmp.name))

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

  GROUP BY crc32(tmp.name))) as grtable
  order by gname;";



if(($typeid==0)&&($useLoginalias==0))
$useAliasColumn="";
if(($typeid==0)&&($useLoginalias==1))
$useAliasColumn=",scsq_alias.name";

if($typeid==0)
$queryOneGroupTraffic="
  SELECT 
    scsq_logins.name,
    tmp2.s as s1,
    tmp2.login 
    ".$useAliasColumn."
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
 
		   GROUP BY crc32(login)) 
		   tmp2 
	ON tmp2.login=scsq_alias.tableid 
	
	LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id 

	ORDER BY scsq_logins.name asc;";

if(($typeid==1)&&($useIpaddressalias==0))
$useAliasColumn="";
if(($typeid==1)&&($useIpaddressalias==1))
$useAliasColumn=",scsq_alias.name";

if($typeid==1)
$queryOneGroupTraffic="
  SELECT 
    scsq_ipaddress.name,
    tmp2.s as s1,
    tmp2.ipaddress
    ".$useAliasColumn."
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

		   GROUP BY crc32(ipaddress)) 
		   tmp2 
	ON tmp2.ipaddress=scsq_alias.tableid 

	LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 

  ORDER BY scsq_ipaddress.name asc;";

if(($typeid==0)&&($useLoginalias==0))
$useAliasColumn="";
if(($typeid==0)&&($useLoginalias==1))
$useAliasColumn=",tmp.al";

if($typeid==0)
$queryOneGroupTrafficWide="
  SELECT 
    nofriends.name,
    tmp.s,
    tmp.login,
    tmp.n 
    ".$useAliasColumn."
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

	GROUP BY crc32(login) 
	ORDER BY null) 

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
	
	GROUP BY crc32(login) 
	ORDER BY null) 

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

	 GROUP BY crc32(login) 
	 ORDER BY null)) 
	 AS tmp

	 RIGHT JOIN (SELECT 
		       id,
		       name 
		     FROM scsq_logins 
		     WHERE id NOT IN (".$goodLoginsList.")) 
		     AS nofriends 
	 ON tmp.login=nofriends.id

  ORDER BY nofriends.name asc,tmp.n asc;";

if(($typeid==1)&&($useIpaddressalias==0))
$useAliasColumn="";
if(($typeid==1)&&($useIpaddressalias==1))
$useAliasColumn=",tmp.al";

if($typeid==1)
$queryOneGroupTrafficWide="
  SELECT 
    nofriends.name,
    tmp.s,
    tmp.ipaddress,
    tmp.n 
    ".$useAliasColumn."
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

	 GROUP BY CRC32(ipaddress) 
	 ORDER BY null) 

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

	 GROUP BY CRC32(ipaddress) 
	 ORDER BY null) 

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

	 GROUP BY crc32(ipaddress) 
	 ORDER BY null)) 
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
	 GROUP BY crc32(site) 
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
	 GROUP BY crc32(site) 
	 ORDER BY s desc 
	 LIMIT ".$countTopSitesLimit." ";


if($typeid==0)
$queryOneGroupTrafficByHours="
  SELECT 
    from_unixtime(tmp.date,'%H') as d,
    sum(tmp.s) 
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
	
	GROUP BY crc32(date) 
	ORDER BY null) 
	AS tmp 

  GROUP BY d;";

if($typeid==1)
$queryOneGroupTrafficByHours="
  SELECT 
    from_unixtime(tmp.date,'%H') as d,
    sum(tmp.s) 
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
	
	GROUP BY crc32(date) 
	ORDER BY null) 
	AS tmp 

  GROUP BY d;";


if($typeid==0)
$queryOneGroupWhoDownloadBigFiles="
  SELECT 
    scsq_logins.name,
    sizeinbytes,site,
    scsq_ipaddress.name,
    login,
    ipaddress 
  FROM (SELECT 
	  sizeinbytes,
	  site,
	  login,
	  ipaddress 
	FROM scsq_traffic

	RIGHT JOIN (select * from scsq_alias) as listaliases  ON listaliases.tableid=scsq_traffic.login

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

//querys for group reports end

//querys for reports end

//reports id

if (isset($_GET['id']))
$id=$_GET['id'];
else
$id=0;




///CALENDAR

if(!isset($_GET['pdf'])){

?>


<script src="../javascript/calendar_ru.js" type="text/javascript"></script>

<form name=fastdateswitch_form onsubmit="return false;">
<p><?php echo $_lang['stFASTDATESWITCH']?><p>
<input type="text" name=date_field onfocus="this.select();lcs(this)"
    onclick="event.cancelBubble=true;this.select();lcs(this)">
<a href="Javascript:FastDateSwitch(<?php echo $_GET['id'] ?>,'day')"><?php echo $_lang['stDAY']?></a>&nbsp;<a href="Javascript:FastDateSwitch(<?php echo $_GET['id']; ?>,'month')"><?php echo $_lang['stMONTH']?></a>
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



<a href="Javascript:LeftRightDateSwitch(<?php echo $_GET['id'];?>,'<?php echo $dayormonth; ?>','l')"><<</a>
&nbsp;<?php echo $querydate; ?>&nbsp;
<a href="Javascript:LeftRightDateSwitch(<?php echo $_GET['id'];?>,'<?php echo $dayormonth; ?>','r')">>></a>

<?php
}
///CALENDAR END

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

if(!isset($_GET['pdf'])){
echo "<table>";
echo "<tr>";
echo "<td valign=middle>".$repheader."</td>";

if(($id>=1 and $id<=2)or($id>=4 and $id<=6)or($id>=8 and $id<=9)or($id>=11 and $id<=12)or($id>=17 and $id<=19)or($id>=21 and $id<=25)or($id==27)or($id>=30 and $id<=32)or($id>=31 and $id<=32)or($id>=35 and $id<=36)or($id>=41 and $id<=48))
echo "<td valign=top>&nbsp;&nbsp;<a href=reports.php??srv=".$_GET['srv']."&id=".$_GET['id']."&date=".$_GET['date']."&dom=".$_GET['dom']."&login=".$_GET['login']."&loginname=".$_GET['loginname']."&ip=".$_GET['ip']."&ipname".$_GET['ipname']."=&site=".$_GET['site']."&group=".$_GET['group']."&groupname=".$_GET['groupname']."&typeid=".$_GET['typeid']."&httpstatus=".$_GET['httpstatus']."&httpname=".$_GET['httpname']."&loiid=".$_GET['loiid']."&loiname=".$_GET['loiname']."&pdf=1><img src='../img/pdficon.jpg' width=32 height=32 alt='Image'></a></td>";
echo "</tr>";
echo "</table>";
}
///REPORTS HEADERS END

/////////// LOGINS TRAFFIC REPORT


if($id==1)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=3+$useLoginalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$result=mysql_query($queryLoginsTraffic) or die (mysql_error());

$colr[0]=1; ///report type 1 - prostoi, 2 - po vremeni, 3 - wide
$colr[1]="numrow";
$colr[2]="<a href=\"javascript:GoPartlyReports(8,'".$dayormonth."','line2','line0',0,'')\">line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$row = mysql_fetch_array($resultmax,MYSQL_NUM);
$collength[4]=$row[0];
$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td>".$colftext[4]."</td>";

}

/////////// LOGINS TRAFFIC REPORT END


//////////// IPADDRESS TRAFFIC REPORT



if($id==2)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stIPADDRESS'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=3+$useIpaddressalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$result=mysql_query($queryIpaddressTraffic) or die (mysql_error());

$colr[0]=1; ///report type 1 - prostoi, 2 - po vremeni, 3 - wide
$colr[1]="numrow";
$colr[2]="<a href=\"javascript:GoPartlyReports(11,'".$dayormonth."','line2','line0',1,'')\">line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td>".$colftext[4]."</td>";

}

/////////////// IPADDRESS TRAFFIC REPORT END

/////////////// SITES TRAFFIC REPORT

if($id==3)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stWHO'];
$colhtext[5]=$_lang['stBYDAYTIME'];
$colhtext[6]=$_lang['stCATEGORY'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";
$colftext[5]="&nbsp;";
$colftext[6]="&nbsp;";

$colh[0]=6;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";
$colh[6]="<th>".$colhtext[6]."</th>";

$result=mysql_query($querySitesTraffic) or die (mysql_error());

/*
if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/

$colr[0]=1; 
$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";
$colr[4]="<a href=javascript:GoPartlyReports(18,'".$dayormonth."','line2','','0','line0')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports(19,'".$dayormonth."','line2','','1','line0')>".$_lang['stIPADDRESSES']."</a>";
$colr[5]="<a href=javascript:GoPartlyReports(53,'".$dayormonth."','line3','','0','line0')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports(54,'".$dayormonth."','line3','','1','line0')>".$_lang['stIPADDRESSES']."</a>";
$colr[6]="line4"; ///category

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td>".$colftext[4]."</td>";
$colf[5]="<td>".$colftext[5]."</td>";
$colf[6]="<td>".$colftext[6]."</td>";

}

/////////////// SITES TRAFFIC REPORT END

/////////////// TOP SITES TRAFFIC REPORT

if($id==4)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stWHO'];
$colhtext[5]=$_lang['stBYDAYTIME'];


$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";
$colftext[5]="&nbsp;";


$colh[0]=5;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";


$result=mysql_query($queryTopSitesTraffic) or die (mysql_error());

/*
if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/
$colr[0]=1;
$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";
$colr[4]="<a href=javascript:GoPartlyReports(18,'".$dayormonth."','line2','','0','line0')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports(19,'".$dayormonth."','line2','','1','line0')>".$_lang['stIPADDRESSES']."</a>";
$colr[5]="<a href=javascript:GoPartlyReports(53,'".$dayormonth."','line3','','0','line0')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports(54,'".$dayormonth."','line3','','1','line0')>".$_lang['stIPADDRESSES']."</a>";


$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td>".$colftext[4]."</td>";
$colf[5]="<td>".$colftext[5]."</td>";

}

/////////////// TOP SITES TRAFFIC REPORT END

/////////////// TOP LOGINS TRAFFIC REPORT

if($id==5)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=3+$useLoginalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$result=mysql_query($queryTopLoginsTraffic) or die (mysql_error());

$colr[0]=1;
$colr[1]="numrow";
$colr[2]="<a href=\"javascript:GoPartlyReports(8,'".$dayormonth."','line2','line0',0,'')\">line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td>".$colftext[4]."</td>";

}

/////////////// TOP LOGINS TRAFFIC REPORT END

/////////////// TOP IPADDRESS TRAFFIC REPORT

if($id==6)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stIPADDRESS'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=3+$useIpaddressalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$result=mysql_query($queryTopIpTraffic) or die (mysql_error());

$colr[0]=1;
$colr[1]="numrow";
$colr[2]="<a href=\"javascript:GoPartlyReports(11,'".$dayormonth."','line2','line0',1,'')\">line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td>".$colftext[4]."</td>";
}

/////////////// TOP IPADDRESS TRAFFIC REPORT END


/////////////// TRAFFIC BY HOURS REPORT

if($id==7)
{

//delete graph if exists

foreach (glob("../lib/pChart/pictures/*.png") as $filename) {
   unlink($filename);
}

$result=mysql_query($queryTrafficByHours) or die (mysql_error());

$HourCounter=0;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {

while($HourCounter<24)
{
if($HourCounter<$line[0])
{
$arrHourMb[$HourCounter]=0;
}
if($HourCounter==$line[0])
break;

$HourCounter++;
}
$line[1]=$line[1] / 1000000;
$arrHourMb[$HourCounter]=$line[1];
$totalmb=$totalmb+$line[1];
$HourCounter++;
}

while($HourCounter<24)
{
$arrHourMb[$HourCounter]=0;
$HourCounter++;
}

//pChart Graph 
 // Dataset definition 
 $DataSet = new pData;
# $DataSet->AddPoint(array(1,4,3,4,3,3,2,1,0,7,4,3,2,3,3,5,1,0,7),"Serie1");
 $DataSet->AddPoint($arrHourMb,"Serie1");
# $DataSet->AddPoint(array("00:00-01:00","01:00-02:00","02:00-03:00","00:03-04:00","04:00-05:00","05:00-06:00","06:00-07:00","08:00-09:00","10:00-11:00","11:00-12:00","12:00-13:00","13:00-14:00","14:00-15:00","15:00-16:00","16:00-17:00","17:00-18:00","18:00-19:00","19:00-20:00","20:00-21:00","21:00-22:00","22:00-23:00","23:00-24:00"),"Serie2");

# $DataSet->AddPoint(array(1,4,2,6,2,3,0,1,5,1,2,4,5,2,1,0,6,4,2),"Serie2");
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie();
 $DataSet->SetSerieName("Traffic","Serie1");
# $DataSet->SetSerieName("February","Serie2");

 // Initialise the graph
 $Test = new pChart(700,230);
# $Test->setFixedScale(-2,8);
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",8);
 $Test->setGraphArea(50,30,585,200);
 $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
 $Test->drawGraphArea(255,255,255,TRUE);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
 $Test->drawGrid(4,TRUE,230,230,230,50);

 // Draw the 0 line
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",6);
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

 // Draw the cubic curve graph
 $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());

 // Finish the graph
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",8);
 $Test->drawLegend(600,30,$DataSet->GetDataDescription(),255,255,255);
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",10);
# $Test->drawTitle(50,22,"Example 2",50,50,50,585);
 $Test->Render("../lib/pChart/pictures/trafficbyhours".$start.".png");

echo "<img src='../lib/pChart/pictures/trafficbyhours".$start.".png' alt='Image'>";

///pChart Graph END

echo "<br /><br />";
echo "
<table id=report_table_id_7 class=sortable>
<tr>
    <th class=unsortable>
    ".$_lang['stHOURS']."
    </th>
    <th class=unsortable>
    ".$_lang['stMEGABYTES']."
    </th>
    <th class=unsortable>
    ".$_lang['stWHO']."
    </th>
</tr>
";

$result=mysql_query($queryTrafficByHours) or die (mysql_error());

$HourCounter=0;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {

while($HourCounter<24)
{
if($HourCounter<$line[0])
{
echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
echo "<td>0</td>";
echo "<td><a href=javascript:GoPartlyReports(41,'".$dayormonth."','1','','0','".$HourCounter."')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports(42,'".$dayormonth."','1','','1','".$HourCounter."')>".$_lang['stIPADDRESSES']."</a></td>";
echo "</tr>";
$arrHourMb[$HourCounter]=0;
}
if($HourCounter==$line[0])
break;

$HourCounter++;
}

echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
$line[1]=$line[1] / 1000000;
echo "<td>".$line[1]."</td>";
echo "<td><a href=javascript:GoPartlyReports(41,'".$dayormonth."','1','','0','".$HourCounter."')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports(42,'".$dayormonth."','1','','1','".$HourCounter."')>".$_lang['stIPADDRESSES']."</a></td>";
echo "</tr>";
$arrHourMb[$HourCounter]=$line[1];

$totalmb=$totalmb+$line[1];
$HourCounter++;
}

while($HourCounter<24)
{
echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
echo "<td>0</td>";
echo "<td><a href=javascript:GoPartlyReports(41,'".$dayormonth."','1','','0','".$HourCounter."')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports(42,'".$dayormonth."','1','','1','".$HourCounter."')>".$_lang['stIPADDRESSES']."</a></td>";
echo "</tr>";
$arrHourMb[$HourCounter]=0;
$HourCounter++;
}
echo "<tr class=sortbottom>
<td><b>".$_lang['stTOTAL']."</b></td>
<td><b>".$totalmb."</b></td>
<td><b>&nbsp;</b></td>
</tr>";

echo "</table>";



}

/////////////// TRAFFIC BY HOURS REPORT END

/////////// ONE LOGIN TRAFFIC REPORT

if($id==8)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

/*
$tmpLine=explode(':',$line[0]);

if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$result=mysql_query($queryOneLoginTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";


$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
if($makepdf==0)
echo "<script>UpdateLeftMenu(1);</script>";
}

/////////// ONE LOGIN TRAFFIC REPORT END

/////////////// TOP SITES FOR ONE LOGIN TRAFFIC REPORT

if($id==9)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

/*
$tmpLine=explode(':',$line[0]);

if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$result=mysql_query($queryOneLoginTopSitesTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";


$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
}

/////////////// TOP SITES FOR ONE LOGIN TRAFFIC REPORT END

/////////////// TRAFFIC BY HOURS FOR ONE LOGIN REPORT

if($id==10)
{
echo "
<table id=report_table_id_10 class=sortable>
<tr>
    <th class=unsortable>
    ".$_lang['stHOURS']."
    </th>
    <th class=unsortable>
    ".$_lang['stMEGABYTES']."
    </th>
</tr>
";

$result=mysql_query($queryOneLoginTrafficByHours) or die (mysql_error());

$HourCounter=0;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {

while($HourCounter<24)
{
if($HourCounter<$line[0])
{
echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
echo "<td>0</td>";
echo "</tr>";
}
if($HourCounter==$line[0])
break;

$HourCounter++;
}

echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
$line[1]=$line[1] / 1000000;
echo "<td>".$line[1]."</td>";
echo "</tr>";
$totalmb=$totalmb+$line[1];
$HourCounter++;
}

while($HourCounter<24)
{
echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
echo "<td>0</td>";
echo "</tr>";
$HourCounter++;
}
echo "<tr class=sortbottom>
<td><b>".$_lang['stTOTAL']."</b></td>
<td><b>".$totalmb."</b></td>
</tr>";
echo "</table>";

}

/////////////// TRAFFIC BY HOURS FOR ONE LOGIN REPORT END

/////////// ONE IPADDRESS TRAFFIC REPORT

if($id==11)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

/*
$tmpLine=explode(':',$line[0]);

if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$result=mysql_query($queryOneIpaddressTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";


$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
if($makepdf==0)
echo "<script>UpdateLeftMenu(2);</script>";
}

/////////// ONE IPADDRESS TRAFFIC REPORT END

/////////////// TOP SITES FOR ONE IPADDRESS TRAFFIC REPORT

if($id==12)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

/*
$tmpLine=explode(':',$line[0]);

if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$result=mysql_query($queryOneIpaddressTopSitesTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
}

/////////////// TOP SITES FOR ONE IPADDRESS TRAFFIC REPORT END

/////////////// TRAFFIC BY HOURS FOR ONE IPADDRESS REPORT

if($id==13)
{
echo "
<table id=report_table_id_13 class=sortable>
<tr>
    <th class=unsortable>
    ".$_lang['stHOURS']."
    </th>
    <th class=unsortable>
    ".$_lang['stMEGABYTES']."
    </th>
</tr>
";

$result=mysql_query($queryOneIpaddressTrafficByHours) or die (mysql_error());
$totalmb=0;
$HourCounter=0;

while ($line = mysql_fetch_array($result,MYSQL_NUM)) {

while($HourCounter<24)
{
if($HourCounter<$line[0])
{
echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
echo "<td>0</td>";
echo "</tr>";
}
if($HourCounter==$line[0])
break;

$HourCounter++;
}

echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
$line[1]=$line[1] / 1000000;
echo "<td>".$line[1]."</td>";
$totalmb=$totalmb+$line[1];
echo "</tr>";
$HourCounter++;
}

while($HourCounter<24)
{
echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
echo "<td>0</td>";
echo "</tr>";
$HourCounter++;
}
echo "<tr class=sortbottom>
<td><b>".$_lang['stTOTAL']."</b></td>
<td><b>".$totalmb."</b></td>
</tr>";

echo "</table>";

}

/////////////// TRAFFIC BY HOURS FOR ONE IPADDRESS REPORT END

/////////// LOGINS TRAFFIC WIDE REPORT

if($id==14)
{
echo "
<table id=report_table_id_14 class=sortable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stLOGIN']."
    </th>
    <th>
    ".$_lang['stMEGABYTES']."
    </th>
    <th>
    ".$_lang['stFROMCACHEMB']."
    </th>
    <th>
    ".$_lang['stDIRECTMB']."
    </th>
    <th>
    ".$_lang['stFROMCACHEPERCENT']."
    </th>
    <th>
    ".$_lang['stDIRECTPERCENT']."
    </th>
</tr>
";

$numrow=1;
$incachemb=0;
$outcachemb=0;
$trafficmb=0;
$havemarker=0;
$incachepc=0;
$outcachepc=0;
$totaltrafficmb=0;
$totalincachemb=0;
$totaloutcachemb=0;
$totalincachepc=0;
$totaloutcachepc=0;


$result=mysql_query($queryLoginsTrafficWide) or die (mysql_error());
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {


if(($line[3]==1)&&($havemarker>0))
{
if($havemarker==1)
{
$outcachemb=0;
echo "<td>".$outcachemb."</td>";
}
$havemarker=0;
$incachepc=round($incachemb/$trafficmb*100,2);
$outcachepc=round($outcachemb/$trafficmb*100,2);
echo "<td>".$incachepc."</td>";
echo "<td>".$outcachepc."</td>";
$totaltrafficmb=$totaltrafficmb+$trafficmb;
$totalincachemb=$totalincachemb+$incachemb;
$totaloutcachemb=$totaloutcachemb+$outcachemb;
echo "</tr>";
$numrow++;
}

$line[1]=$line[1] / 1000000;

if($line[3]==1)
{
echo "<tr>";
echo "<td>".$numrow."</td>";

if($enableUseiconv==1)
$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));

echo "<td><a href=javascript:GoPartlyReports(8,'".$dayormonth."','".$line[2]."','".$line[0]."','0','')>".$line[0]."</a></td>";
$trafficmb=$line[1];
echo "<td>".$trafficmb."</td>";
}
if($line[3]==2)
{
$incachemb=$line[1];
echo "<td>".$incachemb."</td>";
$havemarker=1;
}


if($line[3]==3)
{
$outcachemb=$line[1];
if($havemarker==0)
{
$incachemb=0;
echo "<td>".$incachemb."</td>";
}
echo "<td>".$outcachemb."</td>";

$havemarker=2;
}

                }
if($trafficmb>0){
$incachepc=round($incachemb/$trafficmb*100,2);
$outcachepc=round($outcachemb/$trafficmb*100,2);
echo "<td>".$incachepc."</td>";
echo "<td>".$outcachepc."</td>";
$totaltrafficmb=$totaltrafficmb+$trafficmb;
$totalincachemb=$totalincachemb+$incachemb;
$totaloutcachemb=$totaloutcachemb+$outcachemb;
echo "</tr>";
}


echo "<tr class=sortbottom>";
echo "<td>&nbsp;</td>";
echo "<td><b>".$_lang['stTOTAL']."</b></td>";
echo "<td><b>".$totaltrafficmb."</b></td>";
echo "<td><b>".$totalincachemb."</b></td>";
echo "<td><b>".$totaloutcachemb."</b></td>";
echo "<td><b>".(round($totalincachemb/$totaltrafficmb*100,2))."</b></td>";
echo "<td><b>".(round($totaloutcachemb/$totaltrafficmb*100,2))."</b></td>";

echo "</tr>";
echo "</table>";

}

/////////// LOGINS TRAFFIC WIDE REPORT END

/////////// IPADDRESS TRAFFIC WIDE REPORT

if($id==15)
{
echo "
<table id=report_table_id_15 class=sortable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stIPADDRESS']."
    </th>
    <th>
    ".$_lang['stMEGABYTES']."
    </th>
    <th>
    ".$_lang['stFROMCACHEMB']."
    </th>
    <th>
    ".$_lang['stDIRECTMB']."
    </th>
    <th>
    ".$_lang['stFROMCACHEPERCENT']."
    </th>
    <th>
    ".$_lang['stDIRECTPERCENT']."
    </th>
</tr>
";

$numrow=1;
$incachemb=0;
$outcachemb=0;
$trafficmb=0;
$havemarker=0;
$incachepc=0;
$outcachepc=0;
$totaltrafficmb=0;
$totalincachemb=0;
$totaloutcachemb=0;
$totalincachepc=0;
$totaloutcachepc=0;


$result=mysql_query($queryIpaddressTrafficWide) or die (mysql_error());
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {


if(($line[3]==1)&&($havemarker>0))
{
if($havemarker==1)
{
$outcachemb=0;
echo "<td>".$outcachemb."</td>";
}
$havemarker=0;
$incachepc=round($incachemb/$trafficmb*100,2);
$outcachepc=round($outcachemb/$trafficmb*100,2);
echo "<td>".$incachepc."</td>";
echo "<td>".$outcachepc."</td>";
$totaltrafficmb=$totaltrafficmb+$trafficmb;
$totalincachemb=$totalincachemb+$incachemb;
$totaloutcachemb=$totaloutcachemb+$outcachemb;
echo "</tr>";
$numrow++;
}

$line[1]=$line[1] / 1000000;

if($line[3]==1)
{
echo "<tr>";
echo "<td>".$numrow."</td>";
echo "<td><a href=javascript:GoPartlyReports(11,'".$dayormonth."','".$line[2]."','".$line[0]."','1','')>".$line[0]."</td>";
$trafficmb=$line[1];
echo "<td>".$trafficmb."</td>";
}
if($line[3]==2)
{
$incachemb=$line[1];
echo "<td>".$incachemb."</td>";
$havemarker=1;
}


if($line[3]==3)
{
$outcachemb=$line[1];
if($havemarker==0)
{
$incachemb=0;
echo "<td>".$incachemb."</td>";
}
echo "<td>".$outcachemb."</td>";

$havemarker=2;
}

                }
if($trafficmb>0){
$incachepc=round($incachemb/$trafficmb*100,2);
$outcachepc=round($outcachemb/$trafficmb*100,2);
echo "<td>".$incachepc."</td>";
echo "<td>".$outcachepc."</td>";
$totaltrafficmb=$totaltrafficmb+$trafficmb;
$totalincachemb=$totalincachemb+$incachemb;
$totaloutcachemb=$totaloutcachemb+$outcachemb;
echo "</tr>";
}


echo "<tr class=sortbottom>";
echo "<td>&nbsp;</td>";
echo "<td><b>".$_lang['stTOTAL']."</b></td>";
echo "<td><b>".$totaltrafficmb."</b></td>";
echo "<td><b>".$totalincachemb."</b></td>";
echo "<td><b>".$totaloutcachemb."</b></td>";
echo "<td><b>".(round($totalincachemb/$totaltrafficmb*100,2))."</b></td>";
echo "<td><b>".(round($totaloutcachemb/$totaltrafficmb*100,2))."</b></td>";

echo "</tr>";
echo "</table>";

}

/////////// IPADDRESS TRAFFIC WIDE REPORT END

//////////// IPADDRESS TRAFFIC REPORT WITH RESOLVE

if($id==16)
{

echo "
<table id=report_table_id_16 class=sortable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stIPADDRESS']."
    </th>
    <th>
    ".$_lang['stMEGABYTES']."
    </th>
    <th>
    ".$_lang['stHOSTNAMERESOLVE']."
    </th>

</tr>
";

$result=mysql_query($queryIpaddressTrafficWithResolve) or die (mysql_error());
$numrow=1;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
echo "<tr>";
echo "<td>".$numrow."</td>";
echo "<td><a href=javascript:GoPartlyReports(11,'".$dayormonth."','".$line[2]."','".$line[0]."','1','')>".$line[0]."</td>";
$line[1]=$line[1] / 1000000;
echo "<td>".$line[1]."</td>";
echo "<td>".gethostbyaddr($line[0])."</td>";
echo "</tr>";
$numrow++;
$totalmb=$totalmb+$line[1];
}
echo "<tr class=sortbottom>
<td>&nbsp;</td>
<td><b>".$_lang['stTOTAL']."</b></td>
<td><b>".$totalmb."</b></td>
<td>&nbsp;</td>
</tr>";

echo "</table>";
}

/////////////// IPADDRESS TRAFFIC REPORT WITH RESOLVE END

/////////////// POPULAR SITES REPORT

if($id==17)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stREQUESTS'];
$colhtext[4]=$_lang['stMEGABYTES'];
$colhtext[5]=$_lang['stWHO'];


$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]=$_lang['stTOTAL'];
$colftext[4]="totalmb";
$colftext[5]="&nbsp;";

$colh[0]=5;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";

/*
$tmpLine=explode(':',$line[0]);

if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/

$result=mysql_query($queryPopularSites) or die (mysql_error());

$colr[0]=1;
$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line2";
$colr[4]="line1";
$colr[5]="<a href=javascript:GoPartlyReports(18,'".$dayormonth."','1','','0','line0')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports(19,'".$dayormonth."','1','','1','line0')>".$_lang['stIPADDRESSES']."</a>";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
$colf[5]="<td><b>".$colftext[5]."</b></td>";

}

/////////////// POPULAR SITES REPORT END


/////////////// WHO LOGIN VISIT POPULAR SITE REPORT

if($id==18)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];


$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=3+$useLoginalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";


$result=mysql_query($queryWhoVisitPopularSiteLogin) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(8,'".$dayormonth."','line3','line0','0','')>line0</a>";
$colr[3]="line1";
$colr[4]="line2";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
}

/////////////// WHO VISIT POPULAR SITE LOGIN REPORT END

/////////////// WHO IPADDRESS VISIT POPULAR SITE REPORT

if($id==19)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stIPADDRESS'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];


$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=3+$useIpaddressalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryWhoVisitPopularSiteIpaddress) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(11,'".$dayormonth."','line3','line0','1','')>line0</a>";
$colr[3]="line1";
$colr[4]="line2";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
}

/////////////// WHO VISIT POPULAR SITE IPADDRESS REPORT END

/////////////// WHO DOWNLOAD BIG FILES REPORT

if($id==20)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stIPADDRESS'];
$colhtext[4]=$_lang['stMEGABYTES'];
$colhtext[5]=$_lang['stFROMWEBSITE'];


$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]=$_lang['stTOTAL'];
$colftext[4]="totalmb";
$colftext[5]="&nbsp;";

$colh[0]=5;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";

$result=mysql_query($queryWhoDownloadBigFiles) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(8,'".$dayormonth."','line4','line0','0','')>line0</a>";
$colr[3]="<a href=javascript:GoPartlyReports(11,'".$dayormonth."','line5','line2','1','')>line2</a>";
$colr[4]="line1";
$colr[5]="line3";


$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
$colf[5]="<td><b>".$colftext[5]."</b></td>";
}

/////////////// WHO DOWNLOAD BIG FILES REPORT END

/////////////// TRAFFIC BY PERIOD REPORT

if($id==21)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stMONTHYEAR'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stWHO'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=4;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryTrafficByPeriod) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";
$colr[4]="<a href=javascript:LeftRightDateSwitch(1,'month','$dateTmp')>Логины</a> / 
<a href=javascript:LeftRightDateSwitch(2,'month','$dateTmp')>IP адреса</a>";


$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
}

/////////////// TRAFFIC BY PERIOD REPORT END

/////////////// VISITING WEBSITE BY TIME LOGIN REPORT

if($id==22)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stDATEANDTIME'];
$colhtext[3]=$_lang['stWEBSITE'];

$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]="&nbsp;";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";

$result=mysql_query($queryVisitingWebsiteByTimeLogin) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line2";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
}

/////////////// VISITING WEBSITE BY TIME REPORT LOGIN END

/////////////// VISITING WEBSITE BY TIME IPADDRESS REPORT

if($id==23)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stDATEANDTIME'];
$colhtext[3]=$_lang['stWEBSITE'];

$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]="&nbsp;";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";

$result=mysql_query($queryGroupsTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line2";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
}

/////////////// VISITING WEBSITE BY TIME REPORT IPADDRESS END

/////////// GROUPS TRAFFIC REPORT

if($id==24)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stGROUP'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";

$result=mysql_query($queryGroupsTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=\"javascript:GoPartlyReports(25,'".$dayormonth."','line2','line0',3+line3,'')\">line0</a>";
$colr[3]="line1";


$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
}

/////////// GROUPS TRAFFIC REPORT END



/////////// ONE GROUP LOGINS/IPADDRESS TRAFFIC REPORT

if($id==25)
{
$colhtext[1]="#";
if($typeid==0)
$colhtext[2]=$_lang['stLOGIN'];
else
$colhtext[2]=$_lang['stIPADDRESS'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

if($typeid==0)
$colh[0]=3+$useLoginalias;
else
$colh[0]=3+$useIpaddressalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryOneGroupTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=\"javascript:GoPartlyReports(8+3*$typeid,'".$dayormonth."','line2','line0',0+$typeid,'')\">line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
if($makepdf==0)
echo "<script>UpdateLeftMenu(4);</script>";
}

/////////// ONE GROUP LOGINS/IPADDRESS TRAFFIC REPORT END


/////////// ONE GROUP LOGINS/IPADDRESS TRAFFIC WIDE REPORT

if($id==26)
{
echo "
<table id=report_table_id_26 class=sortable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>";

if($typeid==0)
echo $_lang['stLOGIN'];
if($typeid==1)
echo $_lang['stIPADDRESS'];
echo "</th>";

if(($useLoginalias==1)&&($typeid==0))
echo "<th>".$_lang['stALIAS']."</th>";

if(($useIpaddressalias==1)&&($typeid==1))
echo "<th>".$_lang['stALIAS']."</th>";
echo "<th>
    ".$_lang['stMEGABYTES']."
    </th>
    <th>
    ".$_lang['stFROMCACHEMB']."
    </th>
    <th>
    ".$_lang['stDIRECTMB']."
    </th>
    <th>
    ".$_lang['stFROMCACHEPERCENT']."
    </th>
    <th>
    ".$_lang['stDIRECTPERCENT']."
    </th>
</tr>
";

$numrow=1;
$incachemb=0;
$outcachemb=0;
$trafficmb=0;
$havemarker=0;
$incachepc=0;
$outcachepc=0;
$totaltrafficmb=0;
$totalincachemb=0;
$totaloutcachemb=0;
$totalincachepc=0;
$totaloutcachepc=0;

$result=mysql_query($queryOneGroupTrafficWide) or die (mysql_error());

while ($line = mysql_fetch_array($result,MYSQL_NUM)) {


if(($line[3]==1)&&($havemarker>0))
{
if($havemarker==1)
{
$outcachemb=0;
echo "<td>".$outcachemb."</td>";
}
$havemarker=0;
$incachepc=round($incachemb/$trafficmb*100,2);
$outcachepc=round($outcachemb/$trafficmb*100,2);
echo "<td>".$incachepc."</td>";
echo "<td>".$outcachepc."</td>";
$totaltrafficmb=$totaltrafficmb+$trafficmb;
$totalincachemb=$totalincachemb+$incachemb;
$totaloutcachemb=$totaloutcachemb+$outcachemb;
echo "</tr>";
$numrow++;
}

$line[1]=$line[1] / 1000000;

if($line[3]==1)
{
echo "<tr>";
echo "<td>".$numrow."</td>";

if($enableUseiconv==1)
$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));

if($typeid==0)
echo "<td><a href=javascript:GoPartlyReports(8,'".$dayormonth."','".$line[2]."','".$line[0]."','0','')>".$line[0]."</a></td>";
if($typeid==1)
echo "<td><a href=javascript:GoPartlyReports(11,'".$dayormonth."','".$line[2]."','".$line[0]."','1','')>".$line[0]."</a></td>";

if(($useLoginalias==1)&&($typeid==0))
echo "<td>".$line[4]."</td>";

if(($useIpaddressalias==1)&&($typeid==1))
echo "<td>".$line[4]."</td>";

$trafficmb=$line[1];
echo "<td>".$trafficmb."</td>";
}
if($line[3]==2)
{
$incachemb=$line[1];
echo "<td>".$incachemb."</td>";
$havemarker=1;
}


if($line[3]==3)
{
$outcachemb=$line[1];
if($havemarker==0)
{
$incachemb=0;
echo "<td>".$incachemb."</td>";
}
echo "<td>".$outcachemb."</td>";

$havemarker=2;
}

                }
if($trafficmb>0){
$incachepc=round($incachemb/$trafficmb*100,2);
$outcachepc=round($outcachemb/$trafficmb*100,2);
echo "<td>".$incachepc."</td>";
echo "<td>".$outcachepc."</td>";
$totaltrafficmb=$totaltrafficmb+$trafficmb;
$totalincachemb=$totalincachemb+$incachemb;
$totaloutcachemb=$totaloutcachemb+$outcachemb;
echo "</tr>";
}


echo "<tr class=sortbottom>";
echo "<td>&nbsp;</td>";
echo "<td><b>".$_lang['stTOTAL']."</b></td>";

if(($useLoginalias==1)&&($typeid==0))
echo "<td>&nbsp;</td>";
if(($useIpaddressalias==1)&&($typeid==1))
echo "<td>&nbsp;</td>";

echo "<td><b>".$totaltrafficmb."</b></td>";
echo "<td><b>".$totalincachemb."</b></td>";
echo "<td><b>".$totaloutcachemb."</b></td>";
echo "<td><b>".(round($totalincachemb/$totaltrafficmb*100,2))."</b></td>";
echo "<td><b>".(round($totaloutcachemb/$totaltrafficmb*100,2))."</b></td>";

echo "</tr>";
echo "</table>";

}

/////////// ONE GROUP LOGINS/IPADDRESS TRAFFIC WIDE REPORT END


/////////////// ONE GROUP TOP SITES TRAFFIC REPORT

if($id==27)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
/*
if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/
$result=mysql_query($queryOneGroupTopSitesTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
}

/////////////// ONE GROUP TOP SITES TRAFFIC REPORT END


/////////////// ONE GROUP TRAFFIC BY HOURS REPORT

if($id==28)
{
echo "
<table id=report_table_id_28 class=sortable>
<tr>
    <th class=unsortable>
    ".$_lang['stHOURS']."
    </th>
    <th class=unsortable>
    ".$_lang['stMEGABYTES']."
    </th>
</tr>
";

$result=mysql_query($queryOneGroupTrafficByHours) or die (mysql_error());

$HourCounter=0;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {

while($HourCounter<24)
{
if($HourCounter<$line[0])
{
echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
echo "<td>0</td>";
echo "</tr>";
}
if($HourCounter==$line[0])
break;

$HourCounter++;
}

echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
$line[1]=$line[1] / 1000000;
echo "<td>".$line[1]."</td>";
echo "</tr>";
$totalmb=$totalmb+$line[1];
$HourCounter++;
}

while($HourCounter<24)
{
echo "<tr>";
echo "<td>".$HourCounter.":00-".($HourCounter+1).":00</td>";
echo "<td>0</td>";
echo "</tr>";
$HourCounter++;
}
echo "<tr class=sortbottom>
<td><b>".$_lang['stTOTAL']."</b></td>
<td><b>".$totalmb."</b></td>
</tr>";

echo "</table>";

}

/////////////// ONE GROUP TRAFFIC BY HOURS REPORT END

/////////////// ONE GROUP WHO DOWNLOAD BIG FILES REPORT

if($id==29)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stIPADDRESS'];
$colhtext[4]=$_lang['stMEGABYTES'];
$colhtext[5]=$_lang['stFROMWEBSITE'];

$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]=$_lang['stTOTAL'];
$colftext[4]="totalmb";
$colftext[5]="&nbsp;";

$colh[0]=5;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";

/*
if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/
$result=mysql_query($queryOneGroupWhoDownloadBigFiles) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=\"javascript:GoPartlyReports(8,'".$dayormonth."','line4','line0','0','')\">line0</a>";
$colr[3]="<a href=\"javascript:GoPartlyReports(11,'".$dayormonth."','line5','line1','1','')\">line2</a>";
$colr[4]="line1";
$colr[5]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
$colf[5]="<td><b>".$colftext[5]."</b></td>";
}

/////////////// ONE GROUP WHO DOWNLOAD BIG FILES REPORT END

/////////// HTTP STATUS TRAFFIC REPORT

if($id==30)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stHTTPSTATUS'];
$colhtext[3]=$_lang['stQUANTITY'];
$colhtext[4]=$_lang['stWHO'];

$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]="&nbsp;";
$colftext[4]="&nbsp;";

$colh[0]=4;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryHttpStatus) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";
$colr[4]="<a href=javascript:GoPartlyReports(31,'".$dayormonth."','line2','line0','5','')>".$_lang['stLOGINS']."</a>&nbsp;/&nbsp;<a href=javascript:GoPartlyReports(32,'".$dayormonth."','line2','line0','5','')>".$_lang['stIPADDRESSES']."</a>";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
}

/////////// HTTP STATUS REPORT END

/////////// LOGINS HTTP STATUS TRAFFIC REPORT

if($id==31)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stQUANTITY'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]="&nbsp;";
$colftext[4]="&nbsp;";

$colh[0]=3+$useLoginalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryLoginsHttpStatus) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(33,'".$dayormonth."','".$currenthttpstatusid."','".$currenthttpname."','line2','line0')>line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
}

/////////// LOGINS HTTP STATUS REPORT END

/////////// IPADDRESS HTTP STATUS TRAFFIC REPORT

if($id==32)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stIPADDRESS'];
$colhtext[3]=$_lang['stQUANTITY'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]="&nbsp;";
$colftext[4]="&nbsp;";

$colh[0]=3+$useIpaddressalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryIpaddressHttpStatus) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(34,'".$dayormonth."','".$currenthttpstatusid."','".$currenthttpname."','line2','line0')>line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
}

/////////// IPADDRESS HTTP STATUS REPORT END

/////////// ONE LOGIN ONE HTTP STATUS TRAFFIC REPORT

if($id==33) //неработает пока
{
echo "
<table id=report_table_id_33 class=sortable>
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

$result=mysql_query($queryOneLoginOneHttpStatus) or die (mysql_error());
$numrow=1;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
echo "<tr>";
echo "<td>".$numrow."</td>";

echo "<td>".$line[0]."</td>";
echo "<td>".$line[1]."</td>";
echo "</tr>";
$numrow++;
    }
echo "</tbody></table>";
}

/////////// ONE LOGIN ONE HTTP STATUS REPORT END

/////////// ONE IPADDRESS ONE HTTP STATUS TRAFFIC REPORT

if($id==34) //не работает пока
{
echo "
<table id=report_table_id_34 class=sortable>
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

$result=mysql_query($queryOneIpaddressOneHttpStatus) or die (mysql_error());
$numrow=1;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
echo "<tr>";
echo "<td>".$numrow."</td>";

echo "<td>".$line[0]."</td>";
echo "<td>".$line[1]."</td>";
echo "</tr>";
$numrow++;
    }
echo "</tbody></table>";
}

/////////// ONE IPADDRESS ONE HTTP STATUS REPORT END

/////////// LOGIN IPADRESSES TRAFFIC REPORT

if($id==35)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stIPADDRESS'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=3+$useIpaddressalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryOneLoginIpTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(11,'".$dayormonth."','line2','line0','1','')>line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
if($makepdf==0)
echo "<script>UpdateLeftMenu(1);</script>";
}


/////////// LOGIN IPADRESSES TRAFFIC REPORT END

/////////// IPADRESS LOGIN TRAFFIC REPORT

if($id==36)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=3+$useLoginalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryOneIpaddressLoginsTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(8,'".$dayormonth."','line2','line0','0','')>line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
if($makepdf==0)
echo "<script>UpdateLeftMenu(2);</script>";
}


/////////// IPADRESS LOGINS TRAFFIC REPORT END

/////////// COUNT IPADDRESS ON LOGINS REPORT

if($id==37)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stQUANTITYIPADDRESS'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]="&nbsp;";
$colftext[4]="&nbsp;";

$colh[0]=3+$useLoginalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryCountIpaddressOnLogins) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(8,'".$dayormonth."','line2','line0','0','')>line0</a>";
$colr[3]="<a href=javascript:GoPartlyReports(35,'".$dayormonth."','line2','line0','0','')>line1</a>";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
}

/////////// COUNT IPADDRESS ON LOGINS REPORT END

/////////// COUNT LOGINS ON IPADDRESS REPORT

if($id==38)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stIPADDRESS'];
$colhtext[3]=$_lang['stQUANTITYIPADDRESS'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]="&nbsp;";
$colftext[3]="&nbsp;";
$colftext[4]="&nbsp;";

$colh[0]=3+$useIpaddressalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryCountLoginsOnIpaddress) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(11,'".$dayormonth."','line2','line0','1','')>line0</a>";
$colr[3]="<a href=javascript:GoPartlyReports(36,'".$dayormonth."','line2','line0','1','')>line1</a>";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
}

/////////// COUNT LOGINS ON IPADDRESS REPORT END

/////////////// TRAFFIC BY PERIOD PER DAY REPORT

if($id==39)
{
echo "
<table id=report_table_id_39 class=sortable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stDAYMONTHYEAR']."
    </th>
    <th>
    ".$_lang['stMEGABYTES']."
    </th>
    <th>
    ".$_lang['stWHO']."
    </th>
</tr>
";

$result=mysql_query($queryTrafficByPeriodDay) or die (mysql_error());

$numrow=1;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {


echo "<tr>";
echo "<td>".$numrow."</td>";
echo "<td>".$line[0]."</td>";
$line[1]=$line[1] / 1000000;
echo "<td>".$line[1]."</td>";
$explodeTmp=explode(".", $line[0]);
$dateTmp=$explodeTmp[0]."-".$explodeTmp[1]."-".$explodeTmp[2];
echo "<td><a href=javascript:LeftRightDateSwitch(1,'day','$dateTmp')>Логины</a> / 
<a href=javascript:LeftRightDateSwitch(2,'day','$dateTmp')>IP адреса</a></td>";


echo "</tr>";

$totalmb=$totalmb+$line[1];
$numrow++;
}

echo "<tr class=sortbottom>
<td>&nbsp;</td>
<td><b>".$_lang['stTOTAL']."</b></td>
<td><b>".$totalmb."</b></td>
<td><b>&nbsp;</b></td>

</tr>";

echo "</table>";

}

/////////////// TRAFFIC BY PERIOD PER DAY REPORT END

/////////////// TRAFFIC BY PERIOD PER DAYNAME REPORT

if($id==40)
{
echo "
<table id=report_table_id_40 class=sortable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th>
    ".$_lang['stDAYNAME']."
    </th>
    <th>
    ".$_lang['stMEGABYTES']."
    </th>
</tr>
";

$result=mysql_query($queryTrafficByPeriodDayname) or die (mysql_error());

$numrow=1;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
if($line[0]=='0')
$linevalue[7]=$line[1];
if($line[0]=='1')
$linevalue[1]=$line[1];
if($line[0]=='2')
$linevalue[2]=$line[1];
if($line[0]=='3')
$linevalue[3]=$line[1];
if($line[0]=='4')
$linevalue[4]=$line[1];
if($line[0]=='5')
$linevalue[5]=$line[1];
if($line[0]=='6')
$linevalue[6]=$line[1];

$line[1]=$line[1] / 1000000;
$totalmb=$totalmb+$line[1];
}
echo "<tr>";
echo "<td>1</td>";
echo "<td>".$_lang['stMONDAY']."</td>";
$line[1]=$linevalue[1] / 1000000;
echo "<td>".$line[1]."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>2</td>";
echo "<td>".$_lang['stTUESDAY']."</td>";
$line[1]=$linevalue[2] / 1000000;
echo "<td>".$line[1]."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>3</td>";
echo "<td>".$_lang['stWEDNESDAY']."</td>";
$line[1]=$linevalue[3] / 1000000;
echo "<td>".$line[1]."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>4</td>";
echo "<td>".$_lang['stTHURSDAY']."</td>";
$line[1]=$linevalue[4] / 1000000;
echo "<td>".$line[1]."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>5</td>";
echo "<td>".$_lang['stFRIDAY']."</td>";
$line[1]=$linevalue[5] / 1000000;
echo "<td>".$line[1]."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>6</td>";
echo "<td>".$_lang['stSATURDAY']."</td>";
$line[1]=$linevalue[6] / 1000000;
echo "<td>".$line[1]."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>7</td>";
echo "<td>".$_lang['stSUNDAY']."</td>";
$line[1]=$linevalue[7] / 1000000;
echo "<td>".$line[1]."</td>";
echo "</tr>";


echo "<tr class=sortbottom>
<td>&nbsp;</td>
<td><b>".$_lang['stTOTAL']."</b></td>
<td><b>".$totalmb."</b></td>
</tr>";

echo "</table>";

}

/////////////// TRAFFIC BY PERIOD PER DAYNAME REPORT END

/////////////// WHO LOGIN VISIT SITE ONE HOUR REPORT

if($id==41)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stLOGIN'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=3+$useLoginalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryWhoVisitSiteOneHourLogin) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(43,'".$dayormonth."','line2','line0','0','".$currenthour."')>line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
}

/////////////// WHO LOGIN VISIT SITE ONE HOUR REPORT END

/////////////// WHO IPADDRESS VISIT SITE ONE HOUR REPORT

if($id==42)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stIPADDRESS'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stALIAS'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";
$colftext[4]="&nbsp;";

$colh[0]=3+$useIpaddressalias;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";

$result=mysql_query($queryWhoVisitSiteOneHourIpaddress) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="<a href=javascript:GoPartlyReports(44,'".$dayormonth."','line2','line0','1','".$currenthour."')>line0</a>";
$colr[3]="line1";
$colr[4]="line3";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
}

/////////////// WHO IPADDRESS VISIT SITE ONE HOUR REPORT END

/////////// ONE LOGIN ONE HOUR TRAFFIC REPORT

if($id==43)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";

$result=mysql_query($queryOneLoginOneHourTraffic) or die (mysql_error());

/*
if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
if($makepdf==0)
echo "<script>UpdateLeftMenu(1);</script>";
}

/////////// ONE LOGIN ONE HOUR TRAFFIC REPORT END

/////////// ONE IPADDRESS ONE HOUR TRAFFIC REPORT

if($id==44)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";

$result=mysql_query($queryOneIpaddressOneHourTraffic) or die (mysql_error());

/*
if($tmpLine[1]==443)
echo "<td><a href='https://".$line[0]."' target=blank>".$line[0]."</a></td>";
else
echo "<td><a href='http://".$line[0]."' target=blank>".$line[0]."</a></td>";
*/

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
if($makepdf==0)
echo "<script>UpdateLeftMenu(2);</script>";
}

/////////// ONE IPADDRESS ONE HOUR TRAFFIC REPORT END

/////////////// MIME TYPES TRAFFIC REPORT

if($id==45)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stMIME'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";

$result=mysql_query($queryMimeTypesTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
}

/////////////// MIME TYPES TRAFFIC REPORT END

/////////////// ONE LOGIN MIME TYPES TRAFFIC REPORT

if($id==46)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stMIME'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";

$result=mysql_query($queryOneLoginMimeTypesTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
}

/////////////// ONE LOGIN MIME TYPES TRAFFIC REPORT END


/////////////// ONE IPADDRESS MIME TYPES TRAFFIC REPORT

if($id==47)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stMIME'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";

$result=mysql_query($queryOneIpaddressMimeTypesTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
}

/////////////// ONE IPADDRESS MIME TYPES TRAFFIC REPORT END

/////////////// DOMAIN ZONES TRAFFIC REPORT

if($id==48)
{
$colhtext[1]="#";
$colhtext[2]=$_lang['stDOMAINZONE'];
$colhtext[3]=$_lang['stMEGABYTES'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="totalmb";

$colh[0]=3;
$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";

$result=mysql_query($queryDomainZonesTraffic) or die (mysql_error());

$colr[1]="numrow";
$colr[2]="line0";
$colr[3]="line1";

$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
}

/////////////// DOMAIN ZONES TRAFFIC REPORT END

////////////// DASHBOARD REPORT

if($id==49)
{

//delete graph if exists

foreach (glob("../lib/pChart/pictures/*.png") as $filename) {
   unlink($filename);
}

$result=mysql_query($queryTrafficByHours) or die (mysql_error());

$HourCounter=0;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {

while($HourCounter<24)
{
if($HourCounter<$line[0])
{
$arrHourMb[$HourCounter]=0;
}
if($HourCounter==$line[0])
break;

$HourCounter++;
}
$line[1]=$line[1] / 1000000;
$arrHourMb[$HourCounter]=$line[1];
$totalmb=$totalmb+$line[1];
$HourCounter++;
}

while($HourCounter<24)
{
$arrHourMb[$HourCounter]=0;
$HourCounter++;
}

//pChart Graph BY hours
 // Dataset definition 
 $DataSet = new pData;
# $DataSet->AddPoint(array(1,4,3,4,3,3,2,1,0,7,4,3,2,3,3,5,1,0,7),"Serie1");
 $DataSet->AddPoint($arrHourMb,"Serie1");

# $DataSet->AddPoint(array("00:00-01:00","01:00-02:00","02:00-03:00","00:03-04:00","04:00-05:00","05:00-06:00","06:00-07:00","08:00-09:00","10:00-11:00","11:00-12:00","12:00-13:00","13:00-14:00","14:00-15:00","15:00-16:00","16:00-17:00","17:00-18:00","18:00-19:00","19:00-20:00","20:00-21:00","21:00-22:00","22:00-23:00","23:00-24:00"),"Serie2");

# $DataSet->AddPoint(array(1,4,2,6,2,3,0,1,5,1,2,4,5,2,1,0,6,4,2),"Serie2");
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie();
 $DataSet->SetSerieName("Traffic","Serie1");
# $DataSet->SetSerieName("February","Serie2");

 // Initialise the graph
 $Test = new pChart(700,230);
# $Test->setFixedScale(-2,8);
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",8);
 $Test->setGraphArea(50,30,585,200);
 $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
 $Test->drawGraphArea(255,255,255,TRUE);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
 $Test->drawGrid(4,TRUE,230,230,230,50);

 // Draw the 0 line
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",6);
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

 // Draw the cubic curve graph
 $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());

 // Finish the graph
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",8);
 $Test->drawLegend(600,30,$DataSet->GetDataDescription(),255,255,255);
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",10);
 $Test->drawTitle(50,22,$_lang['stTRAFFICBYHOURS'],50,50,50,585);
 $Test->Render("../lib/pChart/pictures/trafficbyhours".$start.".png");

echo "<img src='../lib/pChart/pictures/trafficbyhours".$start.".png' alt='Image'>";

///pChart Graph BY HOURS END

///top logins


$numrow=1;
while ($numrow<$countTopLoginLimit)
{
$arrLine0[$numrow-1]="NO DATA";
$arrLine1[$numrow-1]=0;
$numrow++;
}

$result=mysql_query($queryTopLoginsTraffic) or die (mysql_error());
$numrow=1;
$totalmb=0;

while ($line = mysql_fetch_array($result,MYSQL_NUM)) {

if($enableUseiconv==1)
$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));
$line[1]=$line[1] / 1000000;

$arrLine0[$numrow-1]=$line[0];
$arrLine1[$numrow-1]=$line[1];


if($numrow==$countTopLoginLimit)
break;

$numrow++;
}


//top logins end


/// pchart top Logins


 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint($arrLine1,"Serie1");
// $DataSet->AddPoint(array(10,2,3,5,3,12),"Serie1");

/// $DataSet->AddPoint(array("Jan","Feb","Mar","Apr","May","111"),"Serie2");

 $DataSet->AddPoint($arrLine0,"Serie2");

 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $Test = new pChart(700,400);
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",10);
 $Test->drawFilledRoundedRectangle(7,7,693,393,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,395,5,230,230,230);

 // Draw the pie chart
 $Test->AntialiasQuality = 0;
 $Test->setShadowProperties(2,2,200,200,200);
 $Test->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),220,200,120,PIE_PERCENTAGE,8);
 $Test->clearShadow();

 $Test->drawTitle(50,22,$_lang['stTOPLOGINSTRAFFIC']." (".$_lang['stTOP']."-".$countTopLoginLimit.")",50,50,50,585);

 $Test->drawPieLegend(430,45,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

 $Test->Render("../lib/pChart/pictures/toplogins".$start.".png");

echo "<img src='../lib/pChart/pictures/toplogins".$start.".png' alt='Image'>";


/// pchart top logins end

///top ipaddress




$numrow=1;
while ($numrow<$countTopIpLimit)
{
$arrLine0[$numrow-1]="NO DATA";
$arrLine1[$numrow-1]=0;
$numrow++;
}

$result=mysql_query($queryTopIpTraffic) or die (mysql_error());
$numrow=1;
$totalmb=0;

while ($line = mysql_fetch_array($result,MYSQL_NUM)) {

if($enableUseiconv==1)
$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));
$line[1]=$line[1] / 1000000;

$arrLine0[$numrow-1]=$line[0];
$arrLine1[$numrow-1]=$line[1];


if($numrow==$countTopIpLimit)
break;

$numrow++;
}


//top ip end


/// pchart top ip


 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint($arrLine1,"Serie1");
// $DataSet->AddPoint(array(10,2,3,5,3,12),"Serie1");

/// $DataSet->AddPoint(array("Jan","Feb","Mar","Apr","May","111"),"Serie2");

 $DataSet->AddPoint($arrLine0,"Serie2");

 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $Test = new pChart(700,400);
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",10);
 $Test->drawFilledRoundedRectangle(7,7,693,393,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,395,5,230,230,230);

 // Draw the pie chart
 $Test->AntialiasQuality = 0;
 $Test->setShadowProperties(2,2,200,200,200);
 $Test->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),220,200,120,PIE_PERCENTAGE,8);
 $Test->clearShadow();

 $Test->drawTitle(50,22,$_lang['stTOPIPTRAFFIC']." (".$_lang['stTOP']."-".$countTopIpLimit.")",50,50,50,585);

 $Test->drawPieLegend(430,45,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

 $Test->Render("../lib/pChart/pictures/topips".$start.".png");

echo "<img src='../lib/pChart/pictures/topips".$start.".png' alt='Image'>";


/// pchart top ip end

///top sites

$numrow=1;
while ($numrow<$countTopSitesLimit)
{
$arrLine0[$numrow-1]="NO DATA";
$arrLine1[$numrow-1]=0;
$numrow++;
}

$result=mysql_query($queryTopSitesTraffic) or die (mysql_error());
$numrow=1;
$totalmb=0;

while ($line = mysql_fetch_array($result,MYSQL_NUM)) {

if($line[3]=='2')
$line[0]=$line[2];

if($enableUseiconv==1)
$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));
$line[1]=$line[1] / 1000000;

$arrLine0[$numrow-1]=$line[0];
$arrLine1[$numrow-1]=$line[1];


if($numrow==$countTopSitesLimit)
break;

$numrow++;
}


//top IP end


/// pchart top sites


 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint($arrLine1,"Serie1");
// $DataSet->AddPoint(array(10,2,3,5,3,12),"Serie1");

/// $DataSet->AddPoint(array("Jan","Feb","Mar","Apr","May","111"),"Serie2");

 $DataSet->AddPoint($arrLine0,"Serie2");

 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $Test = new pChart(700,400);
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",10);
 $Test->drawFilledRoundedRectangle(7,7,693,393,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,395,5,230,230,230);

 // Draw the pie chart
 $Test->AntialiasQuality = 0;
 $Test->setShadowProperties(2,2,200,200,200);
 $Test->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),220,200,120,PIE_PERCENTAGE,8);
 $Test->clearShadow();

 $Test->drawTitle(50,22,$_lang['stTOPSITESTRAFFIC']." (".$_lang['stTOP']."-".$countTopSitesLimit.")",50,50,50,585);


 $Test->drawPieLegend(430,45,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

 $Test->Render("../lib/pChart/pictures/topsites".$start.".png");

echo "<img src='../lib/pChart/pictures/topsites".$start.".png' alt='Image'>";


/// pchart top sites end

///top popular

$numrow=1;
while ($numrow<$countPopularSitesLimit)
{
$arrLine0[$numrow-1]="NO DATA";
$arrLine1[$numrow-1]=0;
$numrow++;
}

$result=mysql_query($queryPopularSites) or die (mysql_error());
$numrow=1;
$totalmb=0;

while ($line = mysql_fetch_array($result,MYSQL_NUM)) {

if($enableUseiconv==1)
$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));
$line[1]=$line[1];

$arrLine0[$numrow-1]=$line[0];
$arrLine1[$numrow-1]=$line[1];


if($numrow==$countPopularSitesLimit)
break;

$numrow++;
}


//top popular end


/// pchart top popular


 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint($arrLine1,"Serie1");
// $DataSet->AddPoint(array(10,2,3,5,3,12),"Serie1");

/// $DataSet->AddPoint(array("Jan","Feb","Mar","Apr","May","111"),"Serie2");

 $DataSet->AddPoint($arrLine0,"Serie2");

 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $Test = new pChart(700,400);
 $Test->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",10);
 $Test->drawFilledRoundedRectangle(7,7,693,393,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,395,5,230,230,230);

 // Draw the pie chart
 $Test->AntialiasQuality = 0;
 $Test->setShadowProperties(2,2,200,200,200);
 $Test->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),220,200,120,PIE_PERCENTAGE,8);
 $Test->clearShadow();

 $Test->drawTitle(50,22,$_lang['stPOPULARSITES']." (".$_lang['stTOP']."-".$countPopularSitesLimit.")",50,50,50,585);

 $Test->drawPieLegend(430,45,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

 $Test->Render("../lib/pChart/pictures/toppop".$start.".png");

echo "<img src='../lib/pChart/pictures/toppop".$start.".png' alt='Image'>";


/// pchart top popular end


}

/////////////// DASHBOARD REPORT END


/////////////// TRAFFIC BY HOURS LOGINS REPORT

if($id==50)
{

echo "
<table id=report_table_id_50 class=sortable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th class=unsortable>
    ".$_lang['stLOGIN']."
    </th>
    <th class=unsortable>
    0
    </th>
    <th class=unsortable>
    1
    </th>
    <th class=unsortable>
    2
    </th>
    <th class=unsortable>
    3
    </th>
    <th class=unsortable>
    4
    </th>
    <th class=unsortable>
    5
    </th>
    <th class=unsortable>
    6
    </th>
    <th class=unsortable>
    7
    </th>
    <th class=unsortable>
    8
    </th>
    <th class=unsortable>
    9
    </th>
    <th class=unsortable>
    10
    </th>
    <th class=unsortable>
    11
    </th>
    <th class=unsortable>
    12
    </th>
    <th class=unsortable>
    13
    </th>
    <th class=unsortable>
    14
    </th>
    <th class=unsortable>
    15
    </th>
    <th class=unsortable>
    16
    </th>
    <th class=unsortable>
    17
    </th>
    <th class=unsortable>
    18
    </th>
    <th class=unsortable>
    19
    </th>
    <th class=unsortable>
    20
    </th>
    <th class=unsortable>
    21
    </th>
    <th class=unsortable>
    22
    </th>
    <th class=unsortable>
    23
    </th>
    <th class=unsortable>
    TOTAL
    </th>
</tr>
";

$result=mysql_query($queryTrafficByHoursLogins) or die (mysql_error());


$HourCounter=0;
$totalmb=0;
$curLogin=0;
$prevLogin=0;
$prevLoginName="";
$curHour=0;
$prevHour=0;
$numrow=1;
$i=0;
$totalmb=0;
while($i<24)
{
$arrHourTraffic[$i]=0;
$hourTotalmb[$i]=0;
$i++;
}


@$arrayLine="";
$j=0;

while($line = mysql_fetch_array($result,MYSQL_NUM)){
@$arrayLine[$j]=$line[0].";".$line[1].";".$line[2].";".$line[3];
$j++;
}

$k=0;

while($k<$j)
{
$line=explode(';',$arrayLine[$k]);
$line1=explode(';',$arrayLine[$k+1]);
if($line[1]==$line1[1])
$arrHourTraffic[$line[3]]=round($line[2]/1000000,2);
else
{
$arrHourTraffic[$line[3]]=round($line[2]/1000000,2);
			echo "<tr>";
			echo "<td>".$numrow."</td>";	
			echo "<td><a href=javascript:GoPartlyReports(8,'".$dayormonth."','".$line[0]."','".$line[1]."','0','')>".$line[1]."</td>";
			$i=0;
			$totalmb=0;
			while($i<24) {
				echo "<td>$arrHourTraffic[$i]</td>";
				$totalmb=$totalmb+$arrHourTraffic[$i];
				$hourTotalmb[$i]=$hourTotalmb[$i]+$arrHourTraffic[$i];
				$arrHourTraffic[$i]=0;
				$i++;
			}
		echo "<td>$totalmb</td>";
		echo "</tr>";
$numrow++;

}

$k++;
}

$i=0;
$totalmb=0;
echo "<tr>";
echo "<td colspan=2>TOTAL</td>";
while($i<24)
{
echo "<td>$hourTotalmb[$i]</td>";
$i++;
$totalmb=$totalmb+$hourTotalmb[$i];
}
echo "<td>$totalmb</td>";
echo "</tr>";

echo "</table>";



}

/////////////// TRAFFIC BY HOURS LOGINS REPORT END


/////////////// TRAFFIC BY HOURS IPADDRESS REPORT

if($id==51)
{

echo "
<table id=report_table_id_51 class=sortable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th class=unsortable>
    ".$_lang['stIPADDRESS']."
    </th>
    <th class=unsortable>
    0
    </th>
    <th class=unsortable>
    1
    </th>
    <th class=unsortable>
    2
    </th>
    <th class=unsortable>
    3
    </th>
    <th class=unsortable>
    4
    </th>
    <th class=unsortable>
    5
    </th>
    <th class=unsortable>
    6
    </th>
    <th class=unsortable>
    7
    </th>
    <th class=unsortable>
    8
    </th>
    <th class=unsortable>
    9
    </th>
    <th class=unsortable>
    10
    </th>
    <th class=unsortable>
    11
    </th>
    <th class=unsortable>
    12
    </th>
    <th class=unsortable>
    13
    </th>
    <th class=unsortable>
    14
    </th>
    <th class=unsortable>
    15
    </th>
    <th class=unsortable>
    16
    </th>
    <th class=unsortable>
    17
    </th>
    <th class=unsortable>
    18
    </th>
    <th class=unsortable>
    19
    </th>
    <th class=unsortable>
    20
    </th>
    <th class=unsortable>
    21
    </th>
    <th class=unsortable>
    22
    </th>
    <th class=unsortable>
    23
    </th>
    <th class=unsortable>
    TOTAL
    </th>
</tr>
";

$result=mysql_query($queryTrafficByHoursIpaddress) or die (mysql_error());

$HourCounter=0;
$totalmb=0;
$curLogin=0;
$prevLogin=0;
$prevLoginName="";
$curHour=0;
$prevHour=0;
$numrow=1;
$i=0;
$totalmb=0;
while($i<24)
{
$arrHourTraffic[$i]=0;
$hourTotalmb[$i]=0;
$i++;
}

@$arrayLine="";
$j=0;

while($line = mysql_fetch_array($result,MYSQL_NUM)){
@$arrayLine[$j]=$line[0].";".$line[1].";".$line[2].";".$line[3];
$j++;
}

$k=0;

while($k<$j)
{
$line=explode(';',$arrayLine[$k]);
$line1=explode(';',$arrayLine[$k+1]);
if($line[1]==$line1[1])
$arrHourTraffic[$line[3]]=round($line[2]/1000000,2);
else
{
$arrHourTraffic[$line[3]]=round($line[2]/1000000,2);
			echo "<tr>";
			echo "<td>".$numrow."</td>";	
			echo "<td><a href=javascript:GoPartlyReports(11,'".$dayormonth."','".$line[0]."','".$line[1]."','1','')>".$line[1]."</td>";
			$i=0;
			$totalmb=0;
			while($i<24) {
				echo "<td>$arrHourTraffic[$i]</td>";
				$totalmb=$totalmb+$arrHourTraffic[$i];
				$hourTotalmb[$i]=$hourTotalmb[$i]+$arrHourTraffic[$i];
				$arrHourTraffic[$i]=0;
				$i++;
			}
		echo "<td>$totalmb</td>";
		echo "</tr>";
$numrow++;

}

$k++;
}

$i=0;
$totalmb=0;
echo "<tr>";
echo "<td colspan=2>TOTAL</td>";
while($i<24)
{
echo "<td>$hourTotalmb[$i]</td>";
$i++;
$totalmb=$totalmb+$hourTotalmb[$i];
}
echo "<td>$totalmb</td>";
echo "</tr>";

echo "</table>";



}

/////////////// TRAFFIC BY HOURS IPADDRESS REPORT END

/////////////// TRAFFIC BY CATEGORY REPORT

if($id==52)
{
echo "
<table id=report_table_id_48 class=sortable>
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
$result=mysql_query($queryCategorySitesTraffic) or die (mysql_error());
$numrow=1;
$totalmb=0;
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
echo "<tr>";
echo "<td>".$numrow."</td>";


if($enableUseiconv==1)
$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));

echo "<td>".$line[0]."</td>";
echo "<td>".$line[1]."</td>";
$line[2]=$line[2] / 1000000;
$line[2]=sprintf("%f",$line[2]); //disable scientific format e.g. 5E-10
echo "<td>".$line[2]."</td>";
echo "</tr>";
$numrow++;
}
echo "<tr class=sortbottom>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>";
echo "</table>";

}

/////////////// DOMAIN ZONES TRAFFIC REPORT END

/////////////// TRAFFIC BY HOURS LOGINS ONE SITE REPORT

if($id==53)
{

echo "
<table id=report_table_id_50 class=sortable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th class=unsortable>
    ".$_lang['stLOGIN']."
    </th>
    <th class=unsortable>
    0
    </th>
    <th class=unsortable>
    1
    </th>
    <th class=unsortable>
    2
    </th>
    <th class=unsortable>
    3
    </th>
    <th class=unsortable>
    4
    </th>
    <th class=unsortable>
    5
    </th>
    <th class=unsortable>
    6
    </th>
    <th class=unsortable>
    7
    </th>
    <th class=unsortable>
    8
    </th>
    <th class=unsortable>
    9
    </th>
    <th class=unsortable>
    10
    </th>
    <th class=unsortable>
    11
    </th>
    <th class=unsortable>
    12
    </th>
    <th class=unsortable>
    13
    </th>
    <th class=unsortable>
    14
    </th>
    <th class=unsortable>
    15
    </th>
    <th class=unsortable>
    16
    </th>
    <th class=unsortable>
    17
    </th>
    <th class=unsortable>
    18
    </th>
    <th class=unsortable>
    19
    </th>
    <th class=unsortable>
    20
    </th>
    <th class=unsortable>
    21
    </th>
    <th class=unsortable>
    22
    </th>
    <th class=unsortable>
    23
    </th>
    <th class=unsortable>
    TOTAL
    </th>
</tr>
";

$result=mysql_query($queryTrafficByHoursLoginsOneSite) or die (mysql_error());

$HourCounter=0;
$totalmb=0;
$curLogin=0;
$prevLogin=0;
$prevLoginName="";
$curHour=0;
$prevHour=0;
$numrow=1;
$i=0;
$totalmb=0;
while($i<24)
{
$arrHourTraffic[$i]=0;
$hourTotalmb[$i]=0;
$i++;
}

@$arrayLine="";
$j=0;

while($line = mysql_fetch_array($result,MYSQL_NUM)){
@$arrayLine[$j]=$line[0].";".$line[1].";".$line[2].";".$line[3];
$j++;
}

$k=0;

while($k<$j)
{
$line=explode(';',$arrayLine[$k]);
$line1=explode(';',$arrayLine[$k+1]);
if($line[1]==$line1[1])
$arrHourTraffic[$line[3]]=round($line[2]/1000000,2);
else
{
$arrHourTraffic[$line[3]]=round($line[2]/1000000,2);
			echo "<tr>";
			echo "<td>".$numrow."</td>";	
			echo "<td><a href=javascript:GoPartlyReports(8,'".$dayormonth."','".$line[0]."','".$line[1]."','0','')>".$line[1]."</td>";
			$i=0;
			$totalmb=0;
			while($i<24) {
				echo "<td>$arrHourTraffic[$i]</td>";
				$totalmb=$totalmb+$arrHourTraffic[$i];
				$hourTotalmb[$i]=$hourTotalmb[$i]+$arrHourTraffic[$i];
				$arrHourTraffic[$i]=0;
				$i++;
			}
		echo "<td>$totalmb</td>";
		echo "</tr>";
$numrow++;

}

$k++;
}

$i=0;
$totalmb=0;
echo "<tr>";
echo "<td colspan=2>TOTAL</td>";
while($i<24)
{
echo "<td>$hourTotalmb[$i]</td>";
$i++;
$totalmb=$totalmb+$hourTotalmb[$i];
}
echo "<td>$totalmb</td>";
echo "</tr>";

echo "</table>";



}

/////////////// TRAFFIC BY HOURS LOGINS ONE SITE REPORT END


/////////////// TRAFFIC BY HOURS IPADDRESS ONE SITE REPORT

if($id==54)
{

echo "
<table id=report_table_id_51 class=sortable>
<tr>
    <th class=unsortable>
    #
    </th>
    <th class=unsortable>
    ".$_lang['stIPADDRESS']."
    </th>
    <th class=unsortable>
    0
    </th>
    <th class=unsortable>
    1
    </th>
    <th class=unsortable>
    2
    </th>
    <th class=unsortable>
    3
    </th>
    <th class=unsortable>
    4
    </th>
    <th class=unsortable>
    5
    </th>
    <th class=unsortable>
    6
    </th>
    <th class=unsortable>
    7
    </th>
    <th class=unsortable>
    8
    </th>
    <th class=unsortable>
    9
    </th>
    <th class=unsortable>
    10
    </th>
    <th class=unsortable>
    11
    </th>
    <th class=unsortable>
    12
    </th>
    <th class=unsortable>
    13
    </th>
    <th class=unsortable>
    14
    </th>
    <th class=unsortable>
    15
    </th>
    <th class=unsortable>
    16
    </th>
    <th class=unsortable>
    17
    </th>
    <th class=unsortable>
    18
    </th>
    <th class=unsortable>
    19
    </th>
    <th class=unsortable>
    20
    </th>
    <th class=unsortable>
    21
    </th>
    <th class=unsortable>
    22
    </th>
    <th class=unsortable>
    23
    </th>
    <th class=unsortable>
    TOTAL
    </th>
</tr>
";

$result=mysql_query($queryTrafficByHoursIpaddressOneSite) or die (mysql_error());

$HourCounter=0;
$totalmb=0;
$curLogin=0;
$prevLogin=0;
$prevLoginName="";
$curHour=0;
$prevHour=0;
$numrow=1;
$i=0;
$totalmb=0;
while($i<24)
{
$arrHourTraffic[$i]=0;
$hourTotalmb[$i]=0;
$i++;
}

@$arrayLine="";
$j=0;

while($line = mysql_fetch_array($result,MYSQL_NUM)){
@$arrayLine[$j]=$line[0].";".$line[1].";".$line[2].";".$line[3];
$j++;
}

$k=0;

while($k<$j)
{
$line=explode(';',$arrayLine[$k]);
$line1=explode(';',$arrayLine[$k+1]);
if($line[1]==$line1[1])
$arrHourTraffic[$line[3]]=round($line[2]/1000000,2);
else
{
$arrHourTraffic[$line[3]]=round($line[2]/1000000,2);
			echo "<tr>";
			echo "<td>".$numrow."</td>";	
			echo "<td><a href=javascript:GoPartlyReports(11,'".$dayormonth."','".$line[0]."','".$line[1]."','1','')>".$line[1]."</td>";
			$i=0;
			$totalmb=0;
			while($i<24) {
				echo "<td>$arrHourTraffic[$i]</td>";
				$totalmb=$totalmb+$arrHourTraffic[$i];
				$hourTotalmb[$i]=$hourTotalmb[$i]+$arrHourTraffic[$i];
				$arrHourTraffic[$i]=0;
				$i++;
			}
		echo "<td>$totalmb</td>";
		echo "</tr>";
$numrow++;

}

$k++;
}


$i=0;
$totalmb=0;
echo "<tr>";
echo "<td colspan=2>TOTAL</td>";
while($i<24)
{
echo "<td>$hourTotalmb[$i]</td>";
$i++;
$totalmb=$totalmb+$hourTotalmb[$i];
}
echo "<td>$totalmb</td>";
echo "</tr>";

echo "</table>";



}

/////////////// TRAFFIC BY HOURS IPADDRESS REPORT ONE SITE END


/////universal table

$numrow=1;
$totalmb=0;

//PARSE SQL
while ($line = mysql_fetch_array($result,MYSQL_NUM)) {
if($enableUseiconv==1)
$line[0]=iconv("CP1251","UTF-8",urldecode($line[0]));
$line[1]=$line[1] / 1000000;
$totalmb=$totalmb+$line[1];
@$rows[$numrow]=implode(";;",$line);
$numrow++;
}

if($makepdf==0)
{

///TABLE HEADER
echo "<table id=report_table_id class=sortable>
	<tr>";

for($i=1;$i<=$colh[0];$i++)
echo $colh[$i];
echo "	</tr>";


//TABLE BODY

for($i=1;$i<$numrow;$i++) {
$line=explode(';;',$rows[$i]);
$line[1]=sprintf("%f",$line[1]);  //disable scientific format, like 5E-10

echo "<tr>";

for($j=1;$j<=$colh[0];$j++){
$resultcolr=$colr[$j];
$resultcolr=preg_replace("/line0/i", $line[0], $resultcolr);
$resultcolr=preg_replace("/line1/i", $line[1], $resultcolr);
$resultcolr=preg_replace("/line2/i", $line[2], $resultcolr);
$resultcolr=preg_replace("/line3/i", $line[3], $resultcolr);
$resultcolr=preg_replace("/line4/i", $line[4], $resultcolr);
$resultcolr=preg_replace("/line5/i", $line[5], $resultcolr);
$resultcolr=preg_replace("/numrow/i", $i, $resultcolr);

echo 	"<td>".$resultcolr."</td>";

}



echo "</tr>";
}

///TABLE FOOTER

echo "<tr class=sortbottom>";

for($i=1;$i<=$colh[0];$i++){
if (preg_match("/totalmb/i", $colf[$i])) {
echo preg_replace("/totalmb/i", $totalmb, $colf[$i]);
$colftext[$i]=$totalmb;
}
else
echo $colf[$i];
}

echo "	</tr>";

echo "</table>";

///universal table end
}

///костыль
if($_GET['id']==17)
$colh[0]=4;

if($_GET['id']==39)
$colh[0]=3;
///костыль end


//// GENERATE PDF FILE

if($makepdf==1)
{
//PDF

$pdff="";
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetHeaderData('','', '', 'powered by TCPDF');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));

// set font
$pdf->SetFont('dejavusans', '', 10);

$pdff.="<table border=\"1\" cellpadding=\"2\" width=\"100%\" >";

if($colh[0]==4)
{
$pdff.="<tr><th width=\"5%\" align=\"center\">".$colhtext[1]."</th><th width=\"30%\" align=\"center\">".$colhtext[2]."</th><th width=\"20%\" align=\"center\">".$colhtext[3]."</th><th width=\"45%\" align=\"center\">".$colhtext[4]."</th></tr>";
}
if(($colh[0]==3)or($colh[0]==5))
{
$pdff.="<tr><th width=\"5%\" align=\"center\">".$colhtext[1]."</th><th width=\"60%\" align=\"center\">".$colhtext[2]."</th><th width=\"30%\" align=\"center\">".$colhtext[3]."</th></tr>";
}

$i=1;

while ($i<$numrow) {
$line=explode(';;',$rows[$i]);

for($j=2;$j<=$colh[0];$j++){
$resultcolr[$j]=$colr[$j];
$resultcolr[$j]=preg_replace("/line0/i", $line[0], $resultcolr[$j]);
$resultcolr[$j]=preg_replace("/line1/i", round($line[1],2), $resultcolr[$j]);
$resultcolr[$j]=preg_replace("/line2/i", $line[2], $resultcolr[$j]);
$resultcolr[$j]=preg_replace("/line3/i", $line[3], $resultcolr[$j]);
$resultcolr[$j]=preg_replace("/line4/i", $line[4], $resultcolr[$j]);
$resultcolr[$j]=preg_replace("/line5/i", $line[5], $resultcolr[$j]);
if(preg_match('/<a(.+)>(.*?)<\/a>/s', $resultcolr[$j], $matches))
$resultcolr[$j]=$matches[2];
//HTML array in $matches[1]
}

if($colh[0]==4)
{
$pdff.="<tr><td>".$i."</td><td>".$resultcolr[2]."</td><td align=\"right\">".$resultcolr[3]."</td><td>".$resultcolr[4]."</td></tr>";
}
if(($colh[0]==3)or($colh[0]==5))
{
$pdff.="<tr><td>".$i."</td><td>".$resultcolr[2]."</td><td align=\"right\">".$resultcolr[3]."</td></tr>";
}

for($j=1;$j<=$colh[0];$j++)
$resultcolr[$j]="";

$i++;
}

for($i=1;$i<=$colh[0];$i++){
if (preg_match("/totalmb/i", $colf[$i])) {
preg_replace("/totalmb/i", $totalmb, $colf[$i]);
$colftext[$i]=round($totalmb,2);
}
}


if($colh[0]==4)
{
$pdff.="<tr><td>".$colftext[1]."</td><td>".$colftext[2]."</td><td align=\"right\">".$colftext[3]."</td><td>".$colftext[4]."</td></tr>";
}
if(($colh[0]==3)or($colh[0]==5))
{
$pdff.="<tr><td>".$colftext[1]."</td><td>".$colftext[2]."</td><td align=\"right\">".$colftext[3]."</td></tr>";
}

$pdff.="</table>";
// add a page
$pdf->AddPage();

$pdf->writeHTML($repheader."<br>", true, false, true, false, 'L');
$pdf->writeHTML($pdff, true, false, true, false, 'L');

//Close and output PDF document
$pdf->Output("../output/report.pdf", 'D');


//PDF END

}

//echo $pdff;

/// GENERATE PDF FILE END

$end=microtime(true);

$runtime=$end - $start;

echo "<br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];

///mysql_disconnect();

?>

</body>
</html>
