#!/usr/bin/perl

#build 20190807

use DBI; # DBI  Perl!!!
#=======================CONFIGURATION BEGIN============================
my $host = "localhost"; # host s DB
my $port = "3306"; # port DB
my $user = "mysql-user"; # username k DB
my $pass = "pass"; # pasword k DB
my $db = "test"; # name DB
#==========================================================
my $count_lines_for_one_insert=10000; 

#Важно! Скрипт не умеет распознавать, наличие того или иного сайта в таблице назначения. Поэтому он затирает её (scsq_mod_categorylist) полностью и заливает 
#заново.

#файлы для загрузки. Можно добавлять, убавлять. Главное, чтобы в цикле дальше while($i<55) правильное количество стояло.
#например, если нужно первые 10, то нужно установить while($i<10). Предполагается, что файлы лежат в каталоге BL относительно скрипта.
$filetoparse[0]="BL/adv/domains";
$filetoparse[1]="BL/aggressive/domains";
$filetoparse[2]="BL/alcohol/domains";
$filetoparse[3]="BL/anonvpn/domains";
$filetoparse[4]="BL/finance/banking";
$filetoparse[5]="BL/chat/domains";
$filetoparse[6]="BL/costtraps/domains";
$filetoparse[7]="BL/dating/domains";
$filetoparse[8]="BL/downloads/domains";
$filetoparse[9]="BL/drugs/domains";
$filetoparse[10]="BL/dynamic/domains";
$filetoparse[11]="BL/education/schools/domains";
$filetoparse[12]="BL/finance/moneylending/domains";
$filetoparse[13]="BL/fortunetelling/domains";
$filetoparse[14]="BL/forum/domains";
$filetoparse[15]="BL/gamble/domains";
$filetoparse[16]="BL/government/domains";
$filetoparse[17]="BL/hacking/domains";
$filetoparse[18]="BL/hobby/games-online/domains";
$filetoparse[19]="BL/homestyle/domains";
$filetoparse[20]="BL/hospitals/domains";
$filetoparse[21]="BL/imagehosting/domains";
$filetoparse[22]="BL/isp/domains";
$filetoparse[23]="BL/jobsearch/domains";
$filetoparse[24]="BL/library/domains";
$filetoparse[25]="BL/military/domains";
$filetoparse[26]="BL/models/domains";
$filetoparse[27]="BL/movies/domains";
$filetoparse[28]="BL/music/domains";
$filetoparse[29]="BL/news/domains";
$filetoparse[30]="BL/podcasts/domains";
$filetoparse[31]="BL/politics/domains";
$filetoparse[32]="BL/porn/domains";
$filetoparse[33]="BL/radiotv/domains";
$filetoparse[34]="BL/recreation/humor/domains";
$filetoparse[35]="BL/redirector/domains";
$filetoparse[36]="BL/religion/domains";
$filetoparse[37]="BL/remotecontrol/domains";
$filetoparse[38]="BL/ringtones/domains";
$filetoparse[39]="BL/science/domains";
$filetoparse[40]="BL/searchengines/domains";
$filetoparse[41]="BL/sex/education/domains";
$filetoparse[42]="BL/shopping/domains";
$filetoparse[43]="BL/socialnet/domains";
$filetoparse[44]="BL/spyware/domains";
$filetoparse[45]="BL/tracker/domains";
$filetoparse[46]="BL/updatesites/domains";
$filetoparse[47]="BL/urlshortener/domains";
$filetoparse[48]="BL/violence/domains";
$filetoparse[49]="BL/warez/domains";
$filetoparse[50]="BL/weapons/domains";
$filetoparse[51]="BL/webmail/domains";
$filetoparse[52]="BL/webphone/domains";
$filetoparse[53]="BL/webradio/domains";
$filetoparse[54]="BL/webtv/domains";


#make conection to DB
$dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);



#очистим таблицу категорий чтобы обновить список
$sql_refreshcats="truncate scsq_mod_categorylist;";
$sth = $dbh->prepare($sql_refreshcats);
$sth->execute; #

#установим счетчик в 1. защитимся от переполнения
$sql="ALTER TABLE scsq_mod_categorylist AUTO_INCREMENT = 1;";
$sth = $dbh->prepare($sql);
$sth->execute; #



$count=0;
$countlines=0; 
$countadded=0;
$i=0;

while($i<55) {

@itm= split "/", $filetoparse[$i]; 

$category=$itm[1];

#open log file for reading
open(IN, "<$filetoparse[$i]"); 

$line_count = `wc -l < $filetoparse[$i]`;

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


  
  @item = split " ", $line; 

      #collect an sql insert statement with data. 
      if($count==0) {
        $sqltext="INSERT INTO scsq_mod_categorylist (category,site) VALUES";
      }
  
      $count++;
      if(eof or $count>=$count_lines_for_one_insert) {
        $sqltext=$sqltext."('$category','$item[0]')";
        $count=0;
        $sth = $dbh->prepare($sqltext);
        $sth->execute;
        $sqltext="";

$sqltext="";


      }

      if($count<$count_lines_for_one_insert and $count>0) {
        $sqltext=$sqltext."('$category','$item[0]'),";
      }
}

#close in file
close(IN);
$i=$i+1;
}
print "Categories in scsq_mod_categorylist table added";


#disconnecting from DB
$rc = $dbh->disconnect;
