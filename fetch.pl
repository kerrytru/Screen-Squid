#!/usr/bin/perl

=cut
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> fetch.pl </#FN>                                                        
*                         File Birth   > <!#FB> 2021/06/24 20:04:51.210 </#FB>                                         *
*                         File Mod     > <!#FT> 2021/11/01 11:33:54.808 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 2.0.1 </#FV>                                                           
*                                                                                                                      *
</#CR>
=cut


use DBI; # DBI  Perl!!!

#=======================CONFIGURATION BEGIN============================
#Enable silent mode ( $silent_mode=1 means enabled ). This means, that you set script in production. No echoes you need
$silent_mode = 0;

$dbtype = "0"; #type of db - 0 - MySQL, 1 - PostGRESQL

$numproxy = "0"; #num proxy default 0, but you can use one db to many access.log. server1 : $numproxy="0", server2 : $numproxy="1" etc.

#mysql default config
if($dbtype==0){
$host = "localhost"; # host s DB
$port = "3306"; # port DB
$user = "mysql-user"; # username k DB
$pass = "pass"; # pasword k DB
$db = "test11"; # name DB
}
#postgresql default config
if($dbtype==1){
$host = "localhost"; # host s DB
$port = "5432"; # port DB
$user = "postgres"; # username k DB
$pass = "pass"; # pasword k DB
$db = "test"; # name DB
}
#==========================================================
#Count lines for one insert. You can change it, if its needed.
#Kolichestvo strok, vstavliaemoe za odin INSERT. Mozhno pokrutit bolshe/menshe dlia skorosti
#Количество строк, вставляемое за один INSERT. Можно покрутить больше/меньше для скорости.
local $count_lines_for_one_insert=1000; #how much INSERT for one 'transaction'
#my $enable_multicore = 0; #pseudo multi-core loading
#my $n = 8; #how many workers will insert data. It depends on max connections your database!
#==========================================================
#Path to access.log. It could be full path, e.g. /var/log/squid/access.log
#Put k access.log. Eto mozhet bit polnii put. Naprimer, /var/log/squid/access.log
#Путь к файлу access.log(имя может другим). Это может быть и полный путь, например, /var/log/squid/access.log
local $filetoparse="access.log";
#==========================================================
#Path to ssquid.log. Log fetch.pl. It could be full path, e.g /var/log/squid/ssquid.log
#File kuda budet zapisivatsia resultat otrabotki skripta fetch.pl. Eto mozhet bit polnii put. Naprimer, /var/log/squid/ssquid.log
local $filetolog="ssquid1.log";
#==========================================================
#Enable delete old data.
#Vkluchit udalenie starih dannih iz bazi
#Включить удаление старых данных из базы
local $enabledelete=0;

#How older data must be deleted. In example, older than 100 days from max date.
#Period, starshe kotorogo dannie budut udaliatsia. Ukazivaetsia v dniah.
#Период, старше которого данные будут удаляться. Указывается в днях.
local $deleteperiod=100; #days
#==========================================================
#min bytes of traffic in one record to be parsed. By default parsed whole log.
#Kolichestvo bait, menshe kotorogo dannie ne budut zapisivatsa v bazu. Ukazivaetsia v baitah. Mozhet ispolzovatsa, chtobi ne zapisivat v bazu dannie o bannerah.
#Количество байт, меньше которого данные не будут записываться в базу. Указывается в байтах. Может использоваться, чтобы не записывать в базу данные о баннерах.
local $minbytestoparse=-1; #bytes, default -1

#=====================================================================
local $useLockFile=1; # 1 = create lock file when script is running, default 1 -enabled. 0 -disabled
#=======================CONFIGURATION END==============================

local $sqltext="";
local $lastdate="";
local $lastday=0;
local $count=0;
local $countlines=0;
local $countadded=0;


sub doDeleteOldData {
#delete data stored more than $deleteperiod days
  my $sqlquery;
  my $deldate;

  $sqlquery="select max(date) from scsq_traffic where numproxy=".$numproxy."";
  @row=doFetchQuery($sqlquery);
  $lastdate=$row[0];

  $deldate=$lastdate - $deleteperiod * 86400;
  
  
  $sqlquery="delete from scsq_traffic where date<$deldate and numproxy=".$numproxy."";
  doQueryToDatabase($sqlquery);

}
#собираем строчки

sub doAddString {

  my $params = shift;
  $sqltext=$sqltext."($params[0],'$params[2]','$params[3]','$params[4]','$params[6]','$params[7]','$params[5]','$params[9]',$numproxy),";

}

sub doFetchQuery {

    my $sqlquery=shift;


    if($dbtype==0){ #mysql
    $dbh_child = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
    }

    if($dbtype==1){ #postgre
    $dbh_child = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
    }
    
      $sth = $dbh_child->prepare($sqlquery);
      $sth->execute;
      @row=$sth->fetchrow_array;
      $sth->finish();
      $dbh_child->disconnect();

  return @row;

}

sub doQueryToDatabase {

    my $sqlquery=shift;

    if($dbtype==0){ #mysql
    $dbh_child = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
    }

    if($dbtype==1){ #postgre
    $dbh_child = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
    }
    
      $sth = $dbh_child->prepare($sqlquery);
      $sth->execute;
      $sth->finish();
      $dbh_child->disconnect();

}

sub doUpdateHttpstatus {
    my $sqlquery="";
    #adding httpstatus if it`s absent in table scsq_httpstatus
    $sqlquery="insert into scsq_httpstatus (name) (select tmp.httpstatus from (select distinct httpstatus from scsq_temptraffic$k_table) as tmp left outer join scsq_httpstatus on tmp.httpstatus=scsq_httpstatus.name where scsq_httpstatus.name is null);";

    $sqlquery="insert into scsq_httpstatus (name) (select tmp.httpstatus from (select distinct httpstatus from scsq_temptraffic) as tmp left outer join scsq_httpstatus on tmp.httpstatus=scsq_httpstatus.name where scsq_httpstatus.name is null);";
    doQueryToDatabase($sqlquery);

}   

sub doUpdateIpaddress {
    my $sqlquery="";
    #adding ipaddress if it`s absent in table scsq_ipaddress
    $sqlquery="insert into scsq_ipaddress (name) (select tmp.ipaddress from (select distinct ipaddress from scsq_temptraffic$k_table) as tmp left outer join scsq_ipaddress on tmp.ipaddress=scsq_ipaddress.name where scsq_ipaddress.name is null);";

    $sqlquery="insert into scsq_ipaddress (name) (select tmp.ipaddress from (select distinct ipaddress from scsq_temptraffic) as tmp left outer join scsq_ipaddress on tmp.ipaddress=scsq_ipaddress.name where scsq_ipaddress.name is null);";
    doQueryToDatabase($sqlquery);
}

sub doUpdateLogins {
    my $sqlquery="";
    #adding logins if it`s absent in table scsq_logins
    $sqlquery="insert into scsq_logins (name) (select tmp.login from (select distinct login from scsq_temptraffic$k_table) as tmp left outer join scsq_logins on tmp.login=scsq_logins.name where scsq_logins.name is null);";
    $sqlquery="insert into scsq_logins (name) (select tmp.login from (select distinct login from scsq_temptraffic) as tmp left outer join scsq_logins on tmp.login=scsq_logins.name where scsq_logins.name is null);";
    doQueryToDatabase($sqlquery);
}


#copy data from temptable to main table
sub doCopyToMainTable {

    my $sqlquery="";

    if($dbtype==0){
#    $sqlquery="insert into scsq_traffic (date,ipaddress,login,httpstatus,sizeinbytes,site,method,mime,numproxy) select date,tmp.id,scsq_logins.id,scsq_httpstatus.id,sizeinbytes,site,method,mime,numproxy from scsq_temptraffic$k_table
#    LEFT JOIN (select id,name from scsq_ipaddress
#    RIGHT JOIN (select distinct ipaddress from scsq_temptraffic$k_table) as tt ON scsq_ipaddress.name=tt.ipaddress) as tmp ON scsq_temptraffic$k_table.ipaddress=tmp.name
#    LEFT JOIN scsq_logins ON scsq_temptraffic$k_table.login=scsq_logins.name
#    LEFT JOIN scsq_httpstatus ON scsq_temptraffic$k_table.httpstatus=scsq_httpstatus.name
#    WHERE numproxy=".$numproxy."
#    ;";
    $sqlquery="insert into scsq_traffic (date,ipaddress,login,httpstatus,sizeinbytes,site,method,mime,numproxy) select date,tmp.id,scsq_logins.id,scsq_httpstatus.id,sizeinbytes,site,method,mime,numproxy from scsq_temptraffic
    LEFT JOIN (select id,name from scsq_ipaddress
    RIGHT JOIN (select distinct ipaddress from scsq_temptraffic) as tt ON scsq_ipaddress.name=tt.ipaddress) as tmp ON scsq_temptraffic.ipaddress=tmp.name
    LEFT JOIN scsq_logins ON scsq_temptraffic.login=scsq_logins.name
    LEFT JOIN scsq_httpstatus ON scsq_temptraffic.httpstatus=scsq_httpstatus.name
    WHERE numproxy=".$numproxy."
    ;";

    }

    if($dbtype==1){
#    $sqlquery="insert into scsq_traffic (date,ipaddress,login,httpstatus,sizeinbytes,site,method,mime,numproxy) select CAST(date as numeric),tmp.id,scsq_logins.id,scsq_httpstatus.id,sizeinbytes,site,method,mime,numproxy from scsq_temptraffic$k_table
#    LEFT JOIN (select id,name from scsq_ipaddress
#    RIGHT JOIN (select distinct ipaddress from scsq_temptraffic$k_table) as tt ON scsq_ipaddress.name=tt.ipaddress) as tmp ON scsq_temptraffic$k_table.ipaddress=tmp.name
#    LEFT JOIN scsq_logins ON scsq_temptraffic$k_table.login=scsq_logins.name
#    LEFT JOIN scsq_httpstatus ON scsq_temptraffic$k_table.httpstatus=scsq_httpstatus.name
#    WHERE numproxy=".$numproxy."
#    ;";
    $sqlquery="insert into scsq_traffic (date,ipaddress,login,httpstatus,sizeinbytes,site,method,mime,numproxy) select CAST(date as numeric),tmp.id,scsq_logins.id,scsq_httpstatus.id,sizeinbytes,site,method,mime,numproxy from scsq_temptraffic
    LEFT JOIN (select id,name from scsq_ipaddress
    RIGHT JOIN (select distinct ipaddress from scsq_temptraffic) as tt ON scsq_ipaddress.name=tt.ipaddress) as tmp ON scsq_temptraffic.ipaddress=tmp.name
    LEFT JOIN scsq_logins ON scsq_temptraffic.login=scsq_logins.name
    LEFT JOIN scsq_httpstatus ON scsq_temptraffic.httpstatus=scsq_httpstatus.name
    WHERE numproxy=".$numproxy."
    ;";

    }

doQueryToDatabase($sqlquery);

}

sub doDeleteFutureDataQuickTraffic {

my $sqlquery = "";
#clear last date in table with data.

$sqlquery="delete from scsq_quicktraffic where date>".$lastday." and numproxy=".$numproxy."";

doQueryToDatabase($sqlquery);
}

sub doGetParameters {

my $sqlquery="";
#get last date in table with data. It used to prevent importing data from log which could be in table yet.
$sqlquery="select max(date) from scsq_traffic where numproxy=".$numproxy."";
@row=doFetchQuery($sqlquery);
$lastdate=$row[0];

if($dbtype==0){
$sqlquery="select unix_timestamp(from_unixtime(max(date),'%Y-%m-%d')) from scsq_quicktraffic where numproxy=".$numproxy."";
}

if($dbtype==1){
$sqlquery="select EXTRACT(epoch from timestamptz (to_char(TO_TIMESTAMP(max(date)),'YYYY-MM-DD'))) from scsq_quicktraffic where numproxy=".$numproxy."";
}

@row=doFetchQuery($sqlquery);
$lastday=$row[0];

if($lastday eq ""){
$lastday=0;
}

}


sub doFlushTempTable{
    my $sqlquery="";
    #truncate data to 
    #    $sqlquery="TRUNCATE TABLE scsq_temptraffic$k_table;";
    $sqlquery="TRUNCATE TABLE scsq_temptraffic;";

    doQueryToDatabase($sqlquery);

}

sub doCopyToQuickTraffic {

my $sqlquery="";

if($dbtype==0){

$sqlquery="insert into scsq_quicktraffic (date,login,ipaddress,sizeinbytes,site,httpstatus,par, numproxy)
SELECT 
date,
tmp2.login,
tmp2.ipaddress,
sum(tmp2.sizeinbytes),
tmp2.st,
tmp2.httpstatus,
1,
".$numproxy."

FROM (SELECT 
case

	when (SUBSTRING_INDEX(site,'/',1) REGEXP '^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?')  
		then SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2)
		else SUBSTRING_INDEX(site,'/',1) 
	end as st, 
sizeinbytes,
date,
login,
ipaddress,
httpstatus
FROM scsq_traffic
where date>".$lastday." and numproxy=".$numproxy."
ORDER BY id asc 
) as tmp2

GROUP BY CRC32(tmp2.st),FROM_UNIXTIME(date,'%Y-%m-%d-%H'),login,ipaddress,httpstatus,tmp2.st,date
;
";
}



if($dbtype==1){

$sqlquery="insert into scsq_quicktraffic (date,login,ipaddress,sizeinbytes,site,httpstatus,par,numproxy)
SELECT 
date,
tmp2.login,
tmp2.ipaddress,
sum(tmp2.sizeinbytes),
tmp2.st,
tmp2.httpstatus,
1,
".$numproxy."

FROM (SELECT 
case

	when (left(reverse(split_part(reverse(split_part(site,'/',1)),'.',1)),1) ~ '[0-9]')  
		then split_part(site,'/',1) 
		else reverse(split_part(reverse(split_part(site,'/',1)),'.',1) ||'.'|| split_part(reverse(split_part(site,'/',1)),'.',2)) 
		
	end as st, 
sizeinbytes,
date,
login,
ipaddress,
httpstatus
FROM scsq_traffic
where date>".$lastday." and numproxy=".$numproxy."
ORDER BY id asc
) as tmp2

GROUP BY tmp2.st,to_char(to_timestamp(date),'YYYY-MM-DD-HH24'),login,ipaddress,httpstatus,tmp2.date

";
}

doQueryToDatabase($sqlquery);
}



sub doReadSquidLogFile {
    my $params = {};

    open(IN, "<$filetoparse"); 
   
    $count=0;

    while (my $line=<IN>) {

$countlines=$countlines+1;
#split string into items.
  
  my @item = split " ", $line; 

#check date before add to sqltext
  
  if($item[0]>$lastdate+1) {
#  if($item[0]>0) {

    if($item[4]>$minbytestoparse) {

      #count how much lines added
      $countadded=$countadded+1;
      #parse sitename from item
      my @matches=($item[6]=~ /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?([\S]+)/i);
      #Регулярка была изменена потому что не работала в режиме SSL BUMP. и чтобы дальше не менять воткнем костыль.
	    #$matches[2] = $matches[1];
	  
	  #replace apostrophes to &quot; 
      $item[2]=~s|'|&()quot;|g; #ipaddress
      $item[3]=~s|'|&()quot;|g; #httpstatus
      $item[4]=~s|'|&()quot;|g; #size in bytes
      $item[5]=~s|'|&()quot;|g; #method
      $matches[1]=~s|'|&()quot;|g; #sitename
      $item[7]=~s|'|&()quot;|g; #login
      $item[9]=~s|'|&()quot;|g; #mime

      $item[2]=~s|"|&()quot;|g; #ipaddress
      $item[3]=~s|"|&()quot;|g; #httpstatus
      $item[4]=~s|"|&()quot;|g; #size in bytes
      $item[5]=~s|"|&()quot;|g; #method
      $matches[1]=~s|"|&()quot;|g; #sitename
      $item[7]=~s|"|&()quot;|g; #login
      $item[9]=~s|"|&()quot;|g; #mime


      #Чтобы не было разнородных переменных для вставки
      $params[0]=$item[0];#date
      $params[2]=$item[2];#ipaddress
      $params[3]=$item[3]; #httpstatus
      $params[4]=$item[4]; #size in bytes
      $params[5]=$item[5]; #method
      $params[6]=$matches[1]; #sitename
      $params[7]=$item[7]; #login
      $params[9]=$item[9]; #mime
      doAddString($params);
$count++;

if ($count==$count_lines_for_one_insert or eof) {

 
  #удаляем последнюю запятую в Insert
  $sqltext="INSERT INTO scsq_temptraffic (date,ipaddress,httpstatus,sizeinbytes,site,login,method,mime, numproxy) VALUES ".$sqltext;
  $sqltext = substr($sqltext, 0, length($sqltext)-1);
  doQueryToDatabase($sqltext);

  doUpdateHttpstatus;
  doUpdateIpaddress;
  doUpdateLogins;
  doCopyToMainTable;
  doFlushTempTable;

$count=0;
$sqltext="";

}

    }   

  
  }
}
#when finish, close handler
close(IN);  
}

sub doWriteToLogFile {

  my $logmessage = shift;
  open(OUT, ">>$filetolog");
print OUT $logmessage."\n";

#close log file
close(OUT);

}

sub doWriteToLogTable {

  my $logmessage = shift;

  my $sqlquery="insert into scsq_logtable (datestart,dateend,message) VALUES ('$startnow','$endnow','$logmessage');";

  doQueryToDatabase($sqlquery);

}

sub doSetLockFile {

#check if script already launched (cause cron), then exit.
#else create lock file
if (-e "fetch.lock") {
  doWriteToLogFile("Cant start script cause fetch.lock is found");
  doWriteToLogTable("Cant start script cause fetch.lock is found");
  exit;
}
else
{
  open(OUT, ">>fetch.lock");
  close(OUT);
}


}

sub doWriteToTerminal {
  my $msg = shift;

  if($silent_mode==0){
    print $msg."\n";
  }
}


#main program 
$now1=localtime;


doWriteToTerminal($now1);

$startnow=time;
$endnow=$startnow;

if($useLockFile == 1){
doSetLockFile;
}

#удалим  старые данные данные если надо
if($enabledelete==1){
  doDeleteOldData;
}

doGetParameters;
doFlushTempTable;
doReadSquidLogFile;

doWriteToTerminal("Starting update scsq_quicktraffic");

#нет смысла обновлять таблицу если не было добавлено ни одной записи
if($countadded > 0) {
doDeleteFutureDataQuickTraffic;
doCopyToQuickTraffic;
}

$endnow=time;
$now2=localtime;

doWriteToTerminal($now2);

doWriteToLogFile("$now1 ===> $now2     records counted $countlines,   records added $countadded");
doWriteToLogTable("$countlines records counted , $countadded records added ");

#delete lock file
unlink("fetch.lock");