#!/usr/bin/perl

#build 20160817

use DBI; # DBI  Perl!!!

#=======================CONFIGURATION BEGIN============================
my $host = "localhost"; # host s DB
my $port = "3306"; # port DB
my $user = "mysql-user"; # username k DB
my $pass = "pass"; # pasword k DB
my $db = "test"; # name DB
#==========================================================
#Kolichestvo strok, vstavliaemoe za odin INSERT. Mozhno pokrutit bolshe/menshe dlia skorosti
#Количество строк, вставляемое за один INSERT. Можно покрутить больше/меньше для скорости.
my $count_lines_for_one_insert=100; #how much INSERT for one 'transaction'
#==========================================================
#Put k access.log. Eto mozhet bit polnii put. Naprimer, /var/log/squid/access.log
#Путь к файлу access.log(имя может другим). Это может быть и полный путь, например, /var/log/squid/access.log
my $filetoparse="access.log";
#==========================================================
#File kuda budet zapisivatsia resultat otrabotki skripta fetch.pl. Eto mozhet bit polnii put. Naprimer, /var/log/squid/ssquid.log
my $filetolog="ssquid.log";
#==========================================================
#Vkluchit udalenie starih dannih iz bazi
#Включить удаление старых данных из базы
my $enabledelete=0;
#Period, starshe kotorogo dannie budut udaliatsia. Ukazivaetsia v dniah.
#Период, старше которого данные будут удаляться. Указывается в днях.
my $deleteperiod=100; #days
#==========================================================
#Kolichestvo bait, menshe kotorogo dannie ne budut zapisivatsa v bazu. Ukazivaetsia v baitah. Mozhet ispolzovatsa, chtobi ne zapisivat v bazu dannie o bannerah.
#Количество байт, меньше которого данные не будут записываться в базу. Указывается в байтах. Может использоваться, чтобы не записывать в базу данные о баннерах.
my $minbytestoparse=0; #bytes, default 0

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
$dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);

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
print "Completed: ".$completed."% Parsed line: ".$countlines."\r";

#split string into items.
  
  @item = split " ", $line; 
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
$sqltext="insert into scsq_traffic (date,ipaddress,login,httpstatus,sizeinbytes,site,method,mime) select date,scsq_ipaddress.id,scsq_logins.id,scsq_httpstatus.id,sizeinbytes,site,method,mime from scsq_temptraffic
LEFT JOIN scsq_logins ON scsq_temptraffic.login=scsq_logins.name
LEFT JOIN scsq_ipaddress ON scsq_temptraffic.ipaddress=scsq_ipaddress.name
LEFT JOIN scsq_httpstatus ON scsq_temptraffic.httpstatus=scsq_httpstatus.name
;";
$sth = $dbh->prepare($sqltext);
$sth->execute;

#clear temptable
$sqltext="truncate scsq_temptraffic;";
$sth = $dbh->prepare($sqltext);
$sth->execute;

$sqltext="";

#clear temptable to be sure, that table have no strange data before import.
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
