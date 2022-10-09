#!/usr/bin/perl

=cut
/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> quotaupdate.pl </#FN>                                                  
*                         File Birth   > <!#FB> 2022/10/05 22:46:05.830 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/10/05 22:46:16.430 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 0.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/

=cut


use DBI; # DBI  Perl!!!

#TODO
#1. Привести всё к функциям
#2. В части расчёта месячной квоты необходимо ввести параметр последней даты расчёта. То есть на новые сутки первый расчёт длинный ( потому что будет считать с начала месяца, а потом просто подбавлять дневную в каждом из расчетов)


#=======================CONFIGURATION BEGIN============================

my $dbtype = "0"; #type of db - 0 - MySQL, 1 - PostGRESQL

#mysql default config
if($dbtype==0){
$host = "localhost"; # host s DB
$port = "3306"; # port DB
$user = "mysql-user"; # username k DB
$pass = "pass"; # pasword k DB
$db = "test4"; # name DB
}
#postgresql default config
if($dbtype==1){
$host = "localhost"; # host s DB
$port = "5432"; # port DB
$user = "postgres"; # username k DB
$pass = "pass"; # pasword k DB
$db = "test"; # name DB
}


#здесь будем хранить данные дат чтобы десять раз за ними не бегать
local $datestart_day;
local $dateend_day;
local $datestart_month;
local $dateend_month;
local $datestart;
local $dateend;

local $countQuotas;



#делаем запросы в базу, получаем ответы
sub doFetchQuery {

    my $sqlquery=shift;


    if($dbtype==0){ #mysql
    $dbh_child = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
    }

    if($dbtype==1){ #postgre
    $dbh_child = DBI->connect("dbi:Pg:dbname=$db;host=$host","$user",$pass,{PrintError => 1});
    }
    
      $sth = $dbh_child->prepare($sqlquery);
      $sth->execute;
      @row=$sth->fetchrow_array;
      $sth->finish();
      $dbh_child->disconnect();

  return @row;

}

#делаем запрос к базе, ничего не возвращаем
sub doQueryToDatabase {

    my $sqlquery=shift;

    if($dbtype==0){ #mysql
    $dbh_child = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
    }

    if($dbtype==1){ #postgre
    $dbh_child = DBI->connect("dbi:Pg:dbname=$db;host=$host","$user",$pass,{PrintError => 1});
    }
    
      $sth = $dbh_child->prepare($sqlquery);
      $sth->execute;
      $sth->finish();
      $dbh_child->disconnect();

}


#очистим таблицу квот от алиасов, которые были удалены, а в таблице квот остались
sub doRefreshListQuota {
	$sql_refreshquota="delete from scsq_mod_quotas where aliasid not in (select id from scsq_alias);";
	doQueryToDatabase($sql_refreshquota);

}

#соберем даты по квотам. для дневной

sub doGetDates {

if($dbtype==0){
#$queryd="sysdate()";
$queryd="'2019-08-07'";
$queryDayStart="date($queryd)";
$queryDayEnd="date($queryd + INTERVAL 1 DAY)";

#$sql_queryDayDate = " unix_timestamp($queryd) ";

$queryMonthStart="DATE_FORMAT($queryd,\"%Y-%m-1\")";
$queryMonthEnd="DATE_FORMAT($queryd + INTERVAL 1 MONTH,\"%Y-%m-1\")";

$sql_queryDate .= "unix_timestamp($queryDayStart),unix_timestamp($queryDayEnd) ,unix_timestamp($queryMonthStart),unix_timestamp($queryMonthEnd)";

}

if($dbtype==1){
#для дня	
#$queryd="current_date";
#$queryd="to_timestamp('2019-08-07','YYYY-MM-DD')"; #for debug
$queryDayStart="current_date";
$queryDayEnd="current_date + INTERVAL '1 DAY'";


#для месяца

$queryMonthStart="to_char($queryd,'YYYY-MM-1')";
$queryMonthEnd="to_char($queryd + INTERVAL '1 MONTH','YYYY-MM-1')";



$sql_queryDate .= "extract(epoch from to_timestamp($queryDayStart,'YYYY-MM-DD')),extract(epoch from to_timestamp($queryDayEnd,'YYYY-MM-DD')),extract(epoch from to_timestamp($queryMonthStart,'YYYY-MM-DD')),extract(epoch from to_timestamp($queryMonthEnd,'YYYY-MM-DD'))";

}

#для месячной

$sql_queryDate = "select ".$sql_queryDate;

@dates=doFetchQuery($sql_queryDate);

#разложим и будем пользоватся в скрипте
$datestart_day =   $dates[0];
$dateend_day = 	   $dates[1];
$datestart_month = $dates[2];
$dateend_month =   $dates[3];

}



#возьмем и рассчитаем разом сразу все значения для модуля квот.
sub doCalcQuotas {

$datestart=$datestart_day;
$dateend=$dateend_day;


#mysql version
   $queryAliasDayTraffic="
UPDATE scsq_mod_quotas q
JOIN (

SELECT
	aliasdata.id,
    aliasdata.s/1024/1024 sumday
	

FROM (
  SELECT 
    tmp.s,
	aliastbl.id,
	aliastbl.name,
	aliastbl.tableid
  FROM (SELECT 
          login,
          SUM(sizeinbytes) as 's' 
        FROM scsq_quicktraffic 
        WHERE  date>".$datestart." 
	   AND date<".$dateend."
	   AND par=1
	GROUP BY CRC32(login),login
	ORDER BY null) 
	AS tmp 

 	INNER JOIN (SELECT 
		      id, name,tableid		      
		   FROM scsq_alias 
		   WHERE typeid=0) 
		   AS aliastbl 
	ON tmp.login=aliastbl.tableid
	INNER JOIN scsq_mod_quotas q ON aliastbl.id=q.aliasid and q.active=1

  GROUP BY aliastbl.name,aliastbl.id,aliastbl.tableid,tmp.s
UNION ALL
SELECT 
    tmp.s,
	aliastbl.id,
	aliastbl.name,
	aliastbl.tableid
  FROM (SELECT 
          ipaddress,
          SUM(sizeinbytes) as 's' 
        FROM scsq_quicktraffic 
        WHERE  date>".$datestart."  
	   AND date<".$dateend."  
	   AND par=1
	GROUP BY CRC32(ipaddress),ipaddress
	ORDER BY null) 
	AS tmp 

 	INNER JOIN (SELECT 
		      id, name,tableid		      
		   FROM scsq_alias 
		   WHERE typeid=1) 
		   AS aliastbl 
	ON tmp.ipaddress=aliastbl.tableid
	INNER JOIN scsq_mod_quotas q ON aliastbl.id=q.aliasid and q.active=1

	GROUP BY aliastbl.name,aliastbl.id,aliastbl.tableid,tmp.s
   ) aliasdata
) res on q.aliasid=res.id
set q.sumday=res.sumday

   ;";		   
		   

doQueryToDatabase($queryAliasDayTraffic);		   


$countQuotas=$countQuotas+1;



}



#установим статусы
sub doSetStatus {
	
$MonthSumSizeTraffic=0;

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

#print $querydate." - ".$datemodified;

#update if we have something to update.
#if($statusquota!=$status or $summonth!=$MonthSumSizeTraffic or $sumday!=$DaySumSizeTraffic) {

}

#Если новый день наступил, то обнулим используемый трафик
sub doRefreshQuotaByDay {
	$sql_refreshdayly = "update scsq_mod_quotas set quota=quotaday, sumday=0, datemodified=".$datestart_day." where datemodified<".$datestart_day."";
	doQueryToDatabase($sql_refreshdayly);

}


#main program

print $now=localtime;
doGetDates;
doRefreshListQuota;
doRefreshQuotaByDay;
doCalcQuotas;

print "\n";
print $now=localtime;
print "\nQuotas updated (".$countQuotas." items)\n";


#disconnecting from DB
#$rc = $dbh->disconnect;
