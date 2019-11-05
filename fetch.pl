#!/usr/bin/perl

#build 20191105

use DBI; # DBI  Perl!!!

#=======================CONFIGURATION BEGIN============================

my $dbtype = "0"; #type of db - 0 - MySQL, 1 - PostGRESQL

#mysql default config
if($dbtype==0){
$host = "localhost"; # host s DB
$port = "3306"; # port DB
$user = "mysql-user"; # username k DB
$pass = "pass"; # pasword k DB
$db = "test"; # name DB
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
my $count_lines_for_one_insert=1000; #how much INSERT for one 'transaction'
#==========================================================
#Path to access.log. It could be full path, e.g. /var/log/squid/access.log
#Put k access.log. Eto mozhet bit polnii put. Naprimer, /var/log/squid/access.log
#Путь к файлу access.log(имя может другим). Это может быть и полный путь, например, /var/log/squid/access.log
my $filetoparse="access.log";
#==========================================================
#Path to ssquid.log. Log fetch.pl. It could be full path, e.g /var/log/squid/ssquid.log
#File kuda budet zapisivatsia resultat otrabotki skripta fetch.pl. Eto mozhet bit polnii put. Naprimer, /var/log/squid/ssquid.log
my $filetolog="ssquid1.log";
#==========================================================
#Enable delete old data.
#Vkluchit udalenie starih dannih iz bazi
#Включить удаление старых данных из базы
my $enabledelete=0;

#How older data must be deleted. In example, older than 100 days from max date.
#Period, starshe kotorogo dannie budut udaliatsia. Ukazivaetsia v dniah.
#Период, старше которого данные будут удаляться. Указывается в днях.
my $deleteperiod=100; #days
#==========================================================
#min bytes of traffic in one record to be parsed. By default parsed whole log.
#Kolichestvo bait, menshe kotorogo dannie ne budut zapisivatsa v bazu. Ukazivaetsia v baitah. Mozhet ispolzovatsa, chtobi ne zapisivat v bazu dannie o bannerah.
#Количество байт, меньше которого данные не будут записываться в базу. Указывается в байтах. Может использоваться, чтобы не записывать в базу данные о баннерах.
my $minbytestoparse=-1; #bytes, default -1

#=======================CONFIGURATION END==============================


my $count=0;
my $lastdate=0;
my $sqltext="";
my $sql_getlastdate="";


#open log file for writing
open(OUT, ">>$filetolog");

#datetime when parse started
print $now=localtime;
$startnow=time;

break;
#print datetime when parsing started
print OUT $now;

$line_count = `wc -l < $filetoparse`;

#make conection to DB
if($dbtype==0){ #mysql
$dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
}

if($dbtype==1){ #postgre
$dbh = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
}

#delete data stored more than $deleteperiod days
if($enabledelete==1){
  $sql_getlastdate="select max(date) from scsq_traffic";
  $sth = $dbh->prepare($sql_getlastdate);
  $sth->execute; #
  @row=$sth->fetchrow_array;
  $lastdate=$row[0];
  $deldate=$lastdate - $deleteperiod * 86400;
  $sql_deleteperiod="delete from scsq_traffic where date<$deldate";
  $sth = $dbh->prepare($sql_deleteperiod);
  $sth->execute; #
}

#get last date in table with data. It used to prevent importing data from log which could be in table yet.
$sql_getlastdate="select max(date) from scsq_traffic";
$sth = $dbh->prepare($sql_getlastdate);
$sth->execute; #
@row=$sth->fetchrow_array;
$lastdate=$row[0];


#get last date in table with data. It used to prevent importing data from log which could be in table yet.

if($dbtype==0){
$sql_getlastdate="select unix_timestamp(from_unixtime(max(date),'%Y-%m-%d')) from scsq_quicktraffic";
}

if($dbtype==1){
$sql_getlastdate="select EXTRACT(epoch from timestamptz (to_char(TO_TIMESTAMP(max(date)),'YYYY-MM-DD'))) from scsq_quicktraffic";
}

$sth = $dbh->prepare($sql_getlastdate);
$sth->execute; #
@row=$sth->fetchrow_array;
$lastday=$row[0];

if($lastday eq ""){
$lastday=0;
}

#clear last date in table with data.
$sql_clearlastday="delete from scsq_quicktraffic where date>".$lastday."";
$sth = $dbh->prepare($sql_clearlastday);
$sth->execute; #



#clear temptable to be sure, that table have no strange data before import.
$sql_cleartemptable="truncate scsq_temptraffic";
$sth = $dbh->prepare($sql_cleartemptable);
$sth->execute; #

#open log file for reading
open(IN, "<$filetoparse"); 

$countlines=0; 
$countadded=0;

print "\n";

#loop for get strings from file one by one.
while (my $line=<IN>) {
#count how much lines in file are parsed
$countlines=$countlines+1;
$completed=int(($countlines/$line_count)*100);

if(time > $seconds+1)
{
$seconds=time;
$insertspeed=$countinsert;
$countinsert=0;
}
$countinsert=$countinsert+1;

print "Completed: ".$completed."% Line: ".$countlines." ".$insertspeed." lines/sec \r";


#split string into items.
  
  @item = split " ", $line; 

# if parsed date is in future, pass it
  if($item[0]>time){
      next
    }
  

#check date before add to sqltext
  
  if($item[0]>$lastdate) {
    if($item[4]>$minbytestoparse) {
      #count how much lines added
      $countadded=$countadded+1;
      #parse sitename from item
      @matches=($item[6]=~ /^(http:\/\/(www\.)?)?([\S]+)/i);
      #replace apostrophes to &quot; 
      $item[2]=~s|'|&()quot;|g; #ipaddress
      $item[3]=~s|'|&()quot;|g; #httpstatus
      $item[4]=~s|'|&()quot;|g; #size in bytes
      $item[5]=~s|'|&()quot;|g; #method
      $matches[2]=~s|'|&()quot;|g; #sitename
      $item[7]=~s|'|&()quot;|g; #login
      $item[9]=~s|'|&()quot;|g; #mime

      $item[2]=~s|"|&()quot;|g; #ipaddress
      $item[3]=~s|"|&()quot;|g; #httpstatus
      $item[4]=~s|"|&()quot;|g; #size in bytes
      $item[5]=~s|"|&()quot;|g; #method
      $matches[2]=~s|"|&()quot;|g; #sitename
      $item[7]=~s|"|&()quot;|g; #login
      $item[9]=~s|"|&()quot;|g; #mime


      #collect an sql insert statement with data. 
      if($count==0) {
        $sqltext="INSERT INTO scsq_temptraffic (date,ipaddress,httpstatus,sizeinbytes,site,login,method,mime) VALUES";
      }
  
      $count++;
      if(eof or $count>=$count_lines_for_one_insert) {
        $sqltext=$sqltext."($item[0],'$item[2]','$item[3]','$item[4]','$matches[2]','$item[7]','$item[5]','$item[9]')";
        $count=0;
        $sth = $dbh->prepare($sqltext);
        $sth->execute;
        $sqltext="";

$sqltext="";
#adding httpstatus if it`s absent in table scsq_httpstatus
$sqltext="insert into scsq_httpstatus (name) (select tmp.httpstatus from (select distinct httpstatus from scsq_temptraffic) as tmp left outer join scsq_httpstatus on tmp.httpstatus=scsq_httpstatus.name where scsq_httpstatus.name is null);";
$sth = $dbh->prepare($sqltext);
$sth->execute; #

$sqltext="";
#adding ipaddress if it`s absent in table scsq_ipaddress
$sqltext="insert into scsq_ipaddress (name) (select tmp.ipaddress from (select distinct ipaddress from scsq_temptraffic) as tmp left outer join scsq_ipaddress on tmp.ipaddress=scsq_ipaddress.name where scsq_ipaddress.name is null);";
$sth = $dbh->prepare($sqltext);
$sth->execute;

$sqltext="";
#adding logins if it`s absent in table scsq_logins
$sqltext="insert into scsq_logins (name) (select tmp.login from (select distinct login from scsq_temptraffic) as tmp left outer join scsq_logins on tmp.login=scsq_logins.name where scsq_logins.name is null);";
$sth = $dbh->prepare($sqltext);
$sth->execute;

#copy data from temptable to main table

if($dbtype==0){
$sqltext="insert into scsq_traffic (date,ipaddress,login,httpstatus,sizeinbytes,site,method,mime) select date,tmp.id,scsq_logins.id,scsq_httpstatus.id,sizeinbytes,site,method,mime from scsq_temptraffic
LEFT JOIN (select id,name from scsq_ipaddress
RIGHT JOIN (select distinct ipaddress from scsq_temptraffic) as tt ON scsq_ipaddress.name=tt.ipaddress) as tmp ON scsq_temptraffic.ipaddress=tmp.name
LEFT JOIN scsq_logins ON scsq_temptraffic.login=scsq_logins.name
LEFT JOIN scsq_httpstatus ON scsq_temptraffic.httpstatus=scsq_httpstatus.name
;";
}

if($dbtype==1){
$sqltext="insert into scsq_traffic (date,ipaddress,login,httpstatus,sizeinbytes,site,method,mime) select CAST(date as numeric),tmp.id,scsq_logins.id,scsq_httpstatus.id,sizeinbytes,site,method,mime from scsq_temptraffic
LEFT JOIN (select id,name from scsq_ipaddress
RIGHT JOIN (select distinct ipaddress from scsq_temptraffic) as tt ON scsq_ipaddress.name=tt.ipaddress) as tmp ON scsq_temptraffic.ipaddress=tmp.name
LEFT JOIN scsq_logins ON scsq_temptraffic.login=scsq_logins.name
LEFT JOIN scsq_httpstatus ON scsq_temptraffic.httpstatus=scsq_httpstatus.name
;";
}

$sth = $dbh->prepare($sqltext);
$sth->execute;

#clear temptable.
$sql_cleartemptable="truncate scsq_temptraffic";
$sth = $dbh->prepare($sql_cleartemptable);
$sth->execute; #

$sqltext="";


      }

      if($count<$count_lines_for_one_insert and $count>0) {
        $sqltext=$sqltext."($item[0],'$item[2]','$item[3]','$item[4]','$matches[2]','$item[7]','$item[5]','$item[9]'),";
      }
    }
  }
}

#close log file
close(IN);

#clear scsq_quicktraffic
#$sqltext="";
#$sqltext="truncate scsq_quicktraffic;";
#$sth = $dbh->prepare($sqltext);
#$sth->execute;

print "\nStarting update scsq_quicktraffic\r";
break;
print "\n";

#update scsq_quicktraffic

$sqltext="";

if($dbtype==0){

$sqltext="insert into scsq_quicktraffic (date,login,ipaddress,sizeinbytes,site,httpstatus,par)
SELECT 
date,
tmp2.login,
tmp2.ipaddress,
sum(tmp2.sizeinbytes),
tmp2.st,
tmp2.httpstatus,
1

FROM (SELECT 
IF(concat('',(LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-1),10),1)) * 1)=(LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-1),10),1)),SUBSTRING_INDEX(site,'/',1),SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2)) as st, 
sizeinbytes,
date,
login,
ipaddress,
httpstatus
FROM scsq_traffic
where date>".$lastday."

) as tmp2

GROUP BY CRC32(tmp2.st),FROM_UNIXTIME(date,'%Y-%m-%d-%H'),login,ipaddress,httpstatus
ORDER BY null;
";
}



if($dbtype==1){

$sqltext="insert into scsq_quicktraffic (date,login,ipaddress,sizeinbytes,site,httpstatus,par)
SELECT 
date,
tmp2.login,
tmp2.ipaddress,
sum(tmp2.sizeinbytes),
tmp2.st,
tmp2.httpstatus,
1

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
where date>".$lastday."

) as tmp2

GROUP BY tmp2.st,to_char(to_timestamp(date),'YYYY-MM-DD-HH24'),login,ipaddress,httpstatus,tmp2.date
";
}

print $sqltext;
$sth = $dbh->prepare($sqltext);
$sth->execute;


#update2 scsq_quicktraffic
$sqltext="";

if($dbtype==0) {
$sqltext="insert into scsq_quicktraffic (date,login,ipaddress,sizeinbytes,site,par)
SELECT 
tmp2.date,
'0',
'0',
tmp2.sums,
tmp2.st,
2

FROM (SELECT 
IF(concat('',(LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-1),10),1)) * 1)=(LEFT(RIGHT(SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-1),10),1)),SUBSTRING_INDEX(site,'/',1),SUBSTRING_INDEX(SUBSTRING_INDEX(site,'/',1),'.',-2)) as st, 
sum(sizeinbytes) as sums,
date
FROM scsq_traffic
where date>".$lastday."
GROUP BY FROM_UNIXTIME(date,'%Y-%m-%d-%H'),crc32(st)

) as tmp2


ORDER BY null;
";
}

if($dbtype==1){

$sqltext="insert into scsq_quicktraffic (date,login,ipaddress,sizeinbytes,site,par)
SELECT 
tmp2.date,
'0',
'0',
tmp2.sums,
tmp2.st,
2

FROM (SELECT 
case

	when (left(reverse(split_part(reverse(split_part(site,'/',1)),'.',1)),1) ~ '[0-9]')  
		then split_part(site,'/',1) 
		else reverse(split_part(reverse(split_part(site,'/',1)),'.',1) ||'.'|| split_part(reverse(split_part(site,'/',1)),'.',2)) 
		
	end as st, 

sum(sizeinbytes) as sums,
date
FROM scsq_traffic
where date>".$lastday."
GROUP BY to_char(to_timestamp(date),'YYYY-MM-DD-HH24'),st,date

) as tmp2

";
}

print $sqltext;
$sth = $dbh->prepare($sqltext);
$sth->execute;





#print datetime when import ended
print "\n";
print $now=localtime;
$endnow=time;

#print datetime when parsing ended
print OUT " ====> ";
print OUT $now;
print OUT "    records counted ";
print OUT $countlines;
print OUT "  records added ";
print OUT $countadded;
print OUT "\n";

#fill scsq_logtable
$sqltext="";
$sqltext="insert into scsq_logtable (datestart,dateend,message) VALUES ('$startnow','$endnow','$countlines records counted, $countadded records added');";
$sth = $dbh->prepare($sqltext);
$sth->execute;

#close log file
close(OUT);

#disconnecting from DB
$rc = $dbh->disconnect;
