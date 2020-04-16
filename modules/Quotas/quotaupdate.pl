#!/usr/bin/perl

#Build date Thursday 16th of April 2020 17:31:34 PM
#Build revision 1.2

use DBI; # DBI  Perl!!!

#=======================CONFIGURATION BEGIN============================

my $dbtype = "0"; #type of db - 0 - MySQL, 1 - PostGRESQL

#mysql default config
if($dbtype==0){
$host = "localhost"; # host s DB
$port = "3306"; # port DB
$user = "mysql-user"; # username k DB
$pass = "pass"; # pasword k DB
$db = "test5"; # name DB
}
#postgresql default config
if($dbtype==1){
$host = "localhost"; # host s DB
$port = "5432"; # port DB
$user = "postgres"; # username k DB
$pass = "pass"; # pasword k DB
$db = "test"; # name DB
}

print $now=localtime;

#make conection to DB
if($dbtype==0){ #mysql
$dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
}

if($dbtype==1){ #postgre
$dbh = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
}

$countQuotas=0;

#очистим таблицу квот от алиасов, которые были удалены, а в таблице квот остались
$sql_refreshquota="delete from scsq_mod_quotas where aliasid not in (select id from scsq_alias);";
$sth = $dbh->prepare($sql_refreshquota);
$sth->execute; #

#соберем даты по квотам. для дневной
if($dbtype==0){
$queryd="date(sysdate())";
$sql_queryDate = " unix_timestamp($queryd) ";
}

if($dbtype==1){
	
$queryd="current_date";
#$queryd="to_timestamp('2019-08-07','YYYY-MM-DD')"; #for debug

$sql_queryDate = " extract(epoch from $queryd) ";
}


#$queryd="'2018-11-26'"; #for debug

#для месячной

if($dbtype==0){
$queryd="sysdate()";
$querydstart="DATE_FORMAT($queryd,\"%Y-%m-1\")";
$querydend="DATE_FORMAT($queryd + INTERVAL 1 MONTH,\"%Y-%m-1\")";

$sql_queryDate .= ",unix_timestamp($querydstart),unix_timestamp($querydend)";
}

if($dbtype==1){
$queryd="current_date";
#$queryd="to_timestamp('2019-08-07','YYYY-MM-DD')"; #for debug
$querydstart="to_char($queryd,'YYYY-MM-1')";
$querydend="to_char($queryd + INTERVAL '1 MONTH','YYYY-MM-1')";

$sql_queryDate .= ",extract(epoch from to_timestamp($querydstart,'YYYY-MM-DD')),extract(epoch from to_timestamp($querydend,'YYYY-MM-DD'))";
}

$sql_queryDate = "select ".$sql_queryDate;

$sth = $dbh->prepare($sql_queryDate);
$sth->execute; #
@datenow=$sth->fetchrow_array;

#get aliasid from quotas module 
$sql_getquota="select aliasid,q.active, quota, quotamonth, datemodified, quotaday ,a.tableid, a.typeid, sumday, summonth , status 
				from scsq_mod_quotas q, scsq_alias a
				where a.id=q.aliasid";
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

$tableid = $row[6];
$typeid = $row[7];

$sumday = $row[8];
$summonth = $row[9];
$statusquota = $row[10];

if($typeid == 0){
$columnname = "login";
}
else
{
$columnname = "ipaddress";
}

$querydate=$datenow[0]; #curday

$datestart=$querydate;
$dateend=$querydate + 86400;
	
$queryOneAliasTraffic="
 	SELECT 
	   SUM(sizeinbytes) as s
 
		   FROM scsq_quicktraffic 
		   WHERE date>".$datestart." 
		     AND date<".$dateend."
	             AND ".$columnname." = ".$tableid." 
		   GROUP BY ".$columnname."
	;";

$sth = $dbh->prepare($queryOneAliasTraffic);
$sth->execute; #

$DaySumSizeTraffic = $sth->fetchrow_array;
$DaySumSizeTraffic = int(($DaySumSizeTraffic + 0)/1024/1024); 
#print int($DaySumSizeTraffic/1000/1000);
#print "\n";


#month quota


$datestart=$datenow[1]; #first day in month
$dateend=$datenow[2]; #first day next month
	
$queryOneAliasTraffic="
 	SELECT 
	   SUM(sizeinbytes) as s
 
		   FROM scsq_quicktraffic 
		   WHERE date>".$datestart." 
		     AND date<".$dateend."
	             AND ".$columnname." = ".$tableid." 
		   GROUP BY ".$columnname."
	;";

$sth = $dbh->prepare($queryOneAliasTraffic);
$sth->execute; #

$MonthSumSizeTraffic = $sth->fetchrow_array;
$MonthSumSizeTraffic = int(($MonthSumSizeTraffic + 0)/1024/1024);

#print int($MonthSumSizeTraffic);


$status=0;
if($active eq 0){
$status=0;
}
else
{
if(($DaySumSizeTraffic > int($quota)) and int($quota >= 0 )){
$status=1; #текущий траффик вышел за пределы квоты

}

if(($MonthSumSizeTraffic > int($quotamonth))and(int($quotamonth) >=0)){

$status=2; #текущий месячный траффик вышел за пределы месячной квоты
}



if(($DaySumSizeTraffic < int($quota))and($MonthSumSizeTraffic < int($quotamonth))){
$status=0; #нет превышения квоты
}


#if((0 < $quota)and(0 < $quotamonth)){
#$status=0; #нет превышения квоты
#}



}
if($querydate > $datemodified) { #если текущая дата перешла на новый день, то обновим текущую квоту на дефолтную дня
$newdayquota = "quota=$quotaday,datemodified=$querydate,";
}
else
{
$newdayquota="";
}

#print $querydate." - ".$datemodified;

#update if we have something to update.
if($statusquota!=$status or $summonth!=$MonthSumSizeTraffic or $sumday!=$DaySumSizeTraffic) {
$sql_updatequota = "UPDATE scsq_mod_quotas SET $newdayquota sumday=$DaySumSizeTraffic,summonth=$MonthSumSizeTraffic, status=$status where aliasid=$aliasid;";
$sth = $dbh->prepare($sql_updatequota);
$sth->execute; #
$countQuotas=$countQuotas+1;
}

     }

print "\n";
print $now=localtime;
print "\nQuotas updated (".$countQuotas." items)";


#disconnecting from DB
#$rc = $dbh->disconnect;
