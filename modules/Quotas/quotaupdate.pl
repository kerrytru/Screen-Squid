#!/usr/bin/perl

#build 20170105

use DBI; # DBI  Perl!!!

#=======================CONFIGURATION BEGIN============================
my $host = "localhost"; # host s DB
my $port = "3306"; # port DB
my $user = "mysql-user"; # username k DB
my $pass = "pass"; # pasword k DB
my $db = "test"; # name DB
#==========================================================


#make conection to DB
$dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);





#get aliasid from quotas module
$sql_getquota="select aliasid,active, quota, quotamonth, datemodified, quotaday from scsq_mod_quotas";
$sthr = $dbh->prepare($sql_getquota);
$sthr->execute; #

while (@row = $sthr->fetchrow_array())

    {
$aliasid = $row[0];
$active = $row[1];
$quota = $row[2];
$quotamonth = $row[3];
$datemodified = $row[4];
$quotaday = $row[5];

$sql_queryAlias = "SELECT tableid, typeid FROM scsq_alias WHERE id=".$aliasid.";";
$sth = $dbh->prepare($sql_queryAlias);
$sth->execute; #
@rowAlias=$sth->fetchrow_array;

if($rowAlias[1] eq 0){
$columnname = "login";
}
else
{
$columnname = "ipaddress";
}

$queryd="date(sysdate())";
#$queryd="'2018-11-26'"; #for debug
$sql_queryDate = "SELECT unix_timestamp($queryd) ;";
$sth = $dbh->prepare($sql_queryDate);
$sth->execute; #
@datenow=$sth->fetchrow_array;


$querydate=$datenow[0];

$datestart=$querydate;
$dateend=$querydate + 86400;
	
$queryOneAliasTraffic="
 	SELECT 
	   SUM(sizeinbytes) as s
 
		   FROM scsq_quicktraffic 
		   WHERE date>".$datestart." 
		     AND date<".$dateend."
	             AND ".$columnname." = ".$rowAlias[0]." 
		   GROUP BY crc32(".$columnname.")
	;";

$sth = $dbh->prepare($queryOneAliasTraffic);
$sth->execute; #

$DaySumSizeTraffic = $sth->fetchrow_array;
$DaySumSizeTraffic = int(($DaySumSizeTraffic + 0)/1000/1000); 
#print int($DaySumSizeTraffic/1000/1000);
#print "\n";


#month quota

$queryd="sysdate()";
#$queryd="'2018-11-26'"; #for debug
$querydstart="DATE_FORMAT($queryd,\"%Y-%m-1\")";
$querydend="DATE_FORMAT($queryd + INTERVAL 1 MONTH,\"%Y-%m-1\")";

$sql_queryDate = "SELECT unix_timestamp($querydstart),unix_timestamp($querydend) ;";
$sth = $dbh->prepare($sql_queryDate);
$sth->execute; #
@datenow=$sth->fetchrow_array;


$datestart=$datenow[0];
$dateend=$datenow[1];
	
$queryOneAliasTraffic="
 	SELECT 
	   SUM(sizeinbytes) as s
 
		   FROM scsq_quicktraffic 
		   WHERE date>".$datestart." 
		     AND date<".$dateend."
	             AND ".$columnname." = ".$rowAlias[0]." 
		   GROUP BY crc32(".$columnname.")
	;";

$sth = $dbh->prepare($queryOneAliasTraffic);
$sth->execute; #

$MonthSumSizeTraffic = $sth->fetchrow_array;
$MonthSumSizeTraffic = int(($MonthSumSizeTraffic + 0)/1000/1000);

#print int($MonthSumSizeTraffic/1000/1000);
#print "\n";

if($active eq 0){
$status=0;
}
else
{
if($DaySumSizeTraffic > int($quota)){
$status=1; #текущий траффик вышел за пределы квоты

}

if($MonthSumSizeTraffic > int($quotamonth)){

$status=2; #текущий месячный траффик вышел за пределы месячной квоты
}

if(($DaySumSizeTraffic < int($quota))and($MonthSumSizeTraffic < int($quotamonth))){
$status=0; #нет превышения квоты
}


}
if($querydate > $datemodified) { #если текущая дата перешла на новый день, то обновим текущую квоту на дефолтную дня
$newdayquota = "quota=$quotaday,datemodified=$querydate,";
}
else
{
$newdayquota="";
}

#print $querydate." - ".$datemodified;

$sql_updatequota = "UPDATE scsq_mod_quotas SET $newdayquota sumday=$DaySumSizeTraffic,summonth=$MonthSumSizeTraffic, status=$status where aliasid=$aliasid;";
$sth = $dbh->prepare($sql_updatequota);
$sth->execute; #


     }
print "Quotas updated";


#disconnecting from DB
#$rc = $dbh->disconnect;
