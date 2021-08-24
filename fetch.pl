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
*                         File Mod     > <!#FT> 2021/08/17 23:25:17.602 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.3.0 </#FV>                                                           
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
$db = "test1"; # name DB
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
my $count_lines_for_one_insert=10000; #how much INSERT for one 'transaction'
my $n = 8; #how many workers will insert data. It depends on max connections your database!
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


#check if script already launched (cause cron), then exit.
#else create lock file
if (-e "fetch.lock") {
  exit;
}
else
{
  open(OUT, ">>fetch.lock");
  close(OUT);
}



my $count=0;
my $lastdate=0;
my $sqltext="";
my $sql_getlastdate="";


#open log file for writing
open(OUT, ">>$filetolog");


#datetime when parse started
if($silent_mode == 0) {
print $now=localtime;
}

$startnow=time;

#print datetime when parsing started
print OUT $now;

if($silent_mode == 0) {

$line_count = `wc -l < $filetoparse`;
}

#make conection to DB
if($dbtype==0){ #mysql
$dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
}

if($dbtype==1){ #postgre
$dbh = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
}

#delete data stored more than $deleteperiod days
if($enabledelete==1){
  $sql_getlastdate="select max(date) from scsq_traffic where numproxy=".$numproxy."";
  $sth = $dbh->prepare($sql_getlastdate);
  $sth->execute; #
  @row=$sth->fetchrow_array;
  $lastdate=$row[0];
  $deldate=$lastdate - $deleteperiod * 86400;
  $sql_deleteperiod="delete from scsq_traffic where date<$deldate and numproxy=".$numproxy."";
  $sth = $dbh->prepare($sql_deleteperiod);
  $sth->execute; #
}

#get last date in table with data. It used to prevent importing data from log which could be in table yet.
$sql_getlastdate="select max(date) from scsq_traffic where numproxy=".$numproxy."";
$sth = $dbh->prepare($sql_getlastdate);
$sth->execute; #
@row=$sth->fetchrow_array;
$lastdate=$row[0];


#get last date in table with data. It used to prevent importing data from log which could be in table yet.

if($dbtype==0){
$sql_getlastdate="select unix_timestamp(from_unixtime(max(date),'%Y-%m-%d')) from scsq_quicktraffic where numproxy=".$numproxy."";
}

if($dbtype==1){
$sql_getlastdate="select EXTRACT(epoch from timestamptz (to_char(TO_TIMESTAMP(max(date)),'YYYY-MM-DD'))) from scsq_quicktraffic where numproxy=".$numproxy."";
}

$sth = $dbh->prepare($sql_getlastdate);
$sth->execute; #
@row=$sth->fetchrow_array;
$lastday=$row[0];

if($lastday eq ""){
$lastday=0;
}

#clear last date in table with data.
$sql_clearlastday="delete from scsq_quicktraffic where date>".$lastday." and numproxy=".$numproxy."";
$sth = $dbh->prepare($sql_clearlastday);
$sth->execute; #



#clear temptable to be sure, that table have no strange data before import.
$sql_cleartemptable="delete from scsq_temptraffic where numproxy=".$numproxy."";
$sth = $dbh->prepare($sql_cleartemptable);
$sth->execute; #

#open log file for reading
open(IN, "<$filetoparse"); 

$countlines=0; 
$countadded=0;

if($silent_mode == 0) {
print "\n";

}

my $k = 0;




    while ($k < $n) {
    $k++;   
     $sqltext="";
    #copy data to child proccess
    $sqltext="DROP TABLE IF EXISTS scsq_temptraffic$k;";
    $sth = $dbh->prepare($sqltext);
    $sth->execute; #
   
  }

my $k = 0;

    while ($k < $n) {
    $k++;  
     $sqltext="";
    #copy data to child proccess
    if($dbtype==0){
      $sqltext="CREATE TABLE scsq_temptraffic$k  (SELECT * FROM scsq_temptraffic);";
    }

    if($dbtype==1){
      $sqltext="CREATE TABLE scsq_temptraffic$k as (SELECT * FROM scsq_temptraffic);";
    }


    $sth = $dbh->prepare($sqltext);
    $sth->execute; #
    
  }

$k=0;

my @children_pids_p1;
my @children_pids_p1_tmp;

$k_table=1;

#loop for get strings from file one by one.
while (my $line=<IN>) {



#count how much lines in file are parsed
$countlines=$countlines+1;

if($silent_mode == 0) {
$completed=int(($countlines/$line_count)*100);

if(time > $seconds+1)
{
$seconds=time;
$insertspeed=$countinsert;
$countinsert=0;
}
$countinsert=$countinsert+1;

print "Completed: ".$completed."% Line: ".$countlines." ".$insertspeed." lines/sec \r";
}

#split string into items.
  
  @item = split " ", $line; 

# if parsed date is in future, pass it
  if($item[0]>time){
      next
    }
  

#check date before add to sqltext
  
  if($item[0]>$lastdate+1) {
    if($item[4]>$minbytestoparse) {
      #count how much lines added
      $countadded=$countadded+1;
      #parse sitename from item
      @matches=($item[6]=~ /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?([\S]+)/i);
      #Регулярка была изменена потому что не работала в режиме SSL BUMP. и чтобы дальше не менять воткнем костыль.
	  $matches[2] = $matches[1];
	  
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
        $k++;
        $sqltext="INSERT INTO scsq_temptraffic$k_table (date,ipaddress,httpstatus,sizeinbytes,site,login,method,mime, numproxy) VALUES";
      }
  
      $count++;
      if(eof or $count>=$count_lines_for_one_insert) {


$k=scalar(@children_pids_p1);



#if maximum child in progress, wait
if($k >= $n){
$j=0;

@children_pids_p1_tmp =@children_pids_p1;
#for($j=0;$j<$n/4;$j++){

waitpid $children_pids_p1[$j], 0;
shift(@children_pids_p1_tmp);
#}

  #$k=scalar;
  @children_pids_p1=@children_pids_p1_tmp;
}

#
#$k++;
  $sqltext=$sqltext."($item[0],'$item[2]','$item[3]','$item[4]','$matches[2]','$item[7]','$item[5]','$item[9]',$numproxy)";

  my $pid = fork;
  push @children_pids_p1,$pid;
  if (not $pid) {
#make conection to DB

    if($dbtype==0){ #mysql
    $dbh_child = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
    }

    if($dbtype==1){ #postgre
    $dbh_child = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
    }

 
      $sth = $dbh_child->prepare($sqltext);
      $sth->execute;


    #put here child work

    $sqltext="";
    #adding httpstatus if it`s absent in table scsq_httpstatus
    $sqltext="insert into scsq_httpstatus (name) (select tmp.httpstatus from (select distinct httpstatus from scsq_temptraffic$k_table) as tmp left outer join scsq_httpstatus on tmp.httpstatus=scsq_httpstatus.name where scsq_httpstatus.name is null);";
    $sth = $dbh_child->prepare($sqltext);
    $sth->execute; #

    $sqltext="";
    #adding ipaddress if it`s absent in table scsq_ipaddress
    $sqltext="insert into scsq_ipaddress (name) (select tmp.ipaddress from (select distinct ipaddress from scsq_temptraffic$k_table) as tmp left outer join scsq_ipaddress on tmp.ipaddress=scsq_ipaddress.name where scsq_ipaddress.name is null);";
    $sth = $dbh_child->prepare($sqltext);
    $sth->execute;

    $sqltext="";
    #adding logins if it`s absent in table scsq_logins
    $sqltext="insert into scsq_logins (name) (select tmp.login from (select distinct login from scsq_temptraffic$k_table) as tmp left outer join scsq_logins on tmp.login=scsq_logins.name where scsq_logins.name is null);";
    $sth = $dbh_child->prepare($sqltext);
    $sth->execute;

    #copy data from temptable to main table

    if($dbtype==0){
    $sqltext="insert into scsq_traffic (date,ipaddress,login,httpstatus,sizeinbytes,site,method,mime,numproxy) select date,tmp.id,scsq_logins.id,scsq_httpstatus.id,sizeinbytes,site,method,mime,numproxy from scsq_temptraffic$k_table
    LEFT JOIN (select id,name from scsq_ipaddress
    RIGHT JOIN (select distinct ipaddress from scsq_temptraffic$k_table) as tt ON scsq_ipaddress.name=tt.ipaddress) as tmp ON scsq_temptraffic$k_table.ipaddress=tmp.name
    LEFT JOIN scsq_logins ON scsq_temptraffic$k_table.login=scsq_logins.name
    LEFT JOIN scsq_httpstatus ON scsq_temptraffic$k_table.httpstatus=scsq_httpstatus.name
    WHERE numproxy=".$numproxy."
    ;";
    }

    if($dbtype==1){
    $sqltext="insert into scsq_traffic (date,ipaddress,login,httpstatus,sizeinbytes,site,method,mime,numproxy) select CAST(date as numeric),tmp.id,scsq_logins.id,scsq_httpstatus.id,sizeinbytes,site,method,mime,numproxy from scsq_temptraffic$k_table
    LEFT JOIN (select id,name from scsq_ipaddress
    RIGHT JOIN (select distinct ipaddress from scsq_temptraffic$k_table) as tt ON scsq_ipaddress.name=tt.ipaddress) as tmp ON scsq_temptraffic$k_table.ipaddress=tmp.name
    LEFT JOIN scsq_logins ON scsq_temptraffic$k_table.login=scsq_logins.name
    LEFT JOIN scsq_httpstatus ON scsq_temptraffic$k_table.httpstatus=scsq_httpstatus.name
    WHERE numproxy=".$numproxy."
    ;";
    }

    $sth = $dbh_child->prepare($sqltext);
    $sth->execute;

    #truncate data to 
    $sqltext="TRUNCATE TABLE scsq_temptraffic$k_table;";
    $sth = $dbh_child->prepare($sqltext);
    $sth->execute; #


    $sth->finish();
    $dbh_child->disconnect();
    exit;
  }
$k_table++;

if($k_table>$n) {
  $k_table=1;
}


#go next
$count=0;


$sqltext="";


      }

      if($count<$count_lines_for_one_insert and $count>0) {
        $sqltext=$sqltext."($item[0],'$item[2]','$item[3]','$item[4]','$matches[2]','$item[7]','$item[5]','$item[9]',$numproxy),";
      }
    }
  }
}

#close log file
close(IN);


#when adding is done wait
    for (1 .. $n) {
    wait();
  }

my $k = 0;

    if($dbtype==0){ #mysql
    $dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
    }

    if($dbtype==1){ #postgre
    $dbh = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
    }


    while ($k < $n) {
    $k++;   
     $sqltext="";
    #copy data to child proccess
    $sqltext="DROP TABLE scsq_temptraffic$k;";
    $sth = $dbh->prepare($sqltext);
    $sth->execute; #
   
  }

    $dbh->disconnect();

    if($dbtype==0){ #mysql
    $dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
    }

    if($dbtype==1){ #postgre
    $dbh = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
    }


#clear scsq_quicktraffic
#$sqltext="";
#$sqltext="truncate scsq_quicktraffic;";
#$sth = $dbh->prepare($sqltext);
#$sth->execute;

if($silent_mode==0) {

print "\nStarting update scsq_quicktraffic\r";
break;
print "\n";
}

$k=0;
    while ($k < $n) {
    $k++;   
     $sqltext="";
    #copy data to child proccess
    $sqltext="DROP TABLE IF EXISTS scsq_quicktraffic$k ;";
    $sth = $dbh->prepare($sqltext);
    $sth->execute; #
   
  }

$k=0;

#create quicktraffic tables
    while ($k < $n) {
    $k++;  
     $sqltext="";
    if($dbtype==0){
      $sqltext="CREATE TABLE scsq_quicktraffic$k  (SELECT * FROM scsq_quicktraffic);";
    }
    if($dbtype==1){
      $sqltext="CREATE TABLE scsq_quicktraffic$k as (SELECT * FROM scsq_quicktraffic);";
    }

    $sth = $dbh->prepare($sqltext);
    $sth->execute; #
    
  }

#Get total rows to update
$sqltext="SELECT count(1) FROM scsq_traffic
where date>".$lastday." and numproxy=".$numproxy."";
$sth = $dbh->prepare($sqltext);
$sth->execute; #
@row=$sth->fetchrow_array;

#row line for offset
$row_line=0;

$k_table=1;


my @children_pids;
my @children_pids_tmp;

#update scsq_quicktraffic
while($row_line<=$row[0]){

$k=scalar(@children_pids);



#if maximum child in progress, wait
if($k >= $n){
$j=0;

@children_pids_tmp =@children_pids;
for($j=0;$j<$n/4;$j++){

waitpid $children_pids[$j], 0;
shift(@children_pids_tmp);
}

  #$k=scalar;
  @children_pids=@children_pids_tmp;
}

$sqltext="";

#$k++;
#print "\n".scalar(@children_pids);

  my $pid = fork;

  push @children_pids,$pid;
  if (not $pid) {
#make conection to DB

    if($dbtype==0){ #mysql
    $dbh_child = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
    }

    if($dbtype==1){ #postgre
    $dbh_child = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
    }

if($dbtype==0){

$sqltext="insert into scsq_quicktraffic$k_table (date,login,ipaddress,sizeinbytes,site,httpstatus,par, numproxy)
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
LIMIT ".$row_line.",".$count_lines_for_one_insert."
) as tmp2

GROUP BY CRC32(tmp2.st),FROM_UNIXTIME(date,'%Y-%m-%d-%H'),login,ipaddress,httpstatus,tmp2.st,date
;
";
}



if($dbtype==1){

$sqltext="insert into scsq_quicktraffic$k_table (date,login,ipaddress,sizeinbytes,site,httpstatus,par,numproxy)
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
LIMIT ".$count_lines_for_one_insert." OFFSET ".$row_line."
) as tmp2

GROUP BY tmp2.st,to_char(to_timestamp(date),'YYYY-MM-DD-HH24'),login,ipaddress,httpstatus,tmp2.date

";
}

#print $sqltext;
$sth = $dbh_child->prepare($sqltext);
$sth->execute;

    $sth->finish();
    $dbh_child->disconnect();
    exit;
  }
$k_table++;
$row_line = $row_line+$count_lines_for_one_insert;
if($k_table>$n) {
  $k_table=1;
}
}

    if($dbtype==0){ #mysql
    $dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
    }

    if($dbtype==1){ #postgre
    $dbh = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
    }

$k=0;

#drop quicktraffic tables
    while ($k < $n) {
    $k++;  
     $sqltext="";
    #copy data to child proccess
    $sqltext="INSERT INTO scsq_quicktraffic (date,login,ipaddress,site,sizeinbytes,httpstatus,par,numproxy) (select date,login,ipaddress,site,sizeinbytes,httpstatus,par,numproxy from scsq_quicktraffic$k)";
    $sth = $dbh->prepare($sqltext);
    $sth->execute; #

     $sqltext="";
    #copy data to child proccess
    $sqltext="DROP TABLE scsq_quicktraffic$k";
    $sth = $dbh->prepare($sqltext);
    $sth->execute; #
    
  }

#when adding is done wait
    for (1 .. $n) {
    wait();
  }


#print datetime when import ended
if($silent_mode==0) {
print "\n";
print $now=localtime;
}
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

#delete lock
unlink("fetch.lock");

#disconnecting from DB
$rc = $dbh->disconnect;
