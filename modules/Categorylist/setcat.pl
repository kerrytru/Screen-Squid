#!/usr/bin/perl

#Build date Friday 24th of April 2020 09:26:33 AM
#Build revision 1.1

use DBI; # DBI  Perl!!!
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
$db = "test4"; # name DB
}

my $procs=256; #kol-vo processov
#=======================CONFIGURATION END==============================

#make conection to DB
if($dbtype==0){ #mysql
$dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
}

if($dbtype==1){ #postgre
$dbh = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
}

#обновляем категории
$sql="select distinct site from scsq_quicktraffic where category is NULL;";
$sthr = $dbh->prepare($sql);
$sthr->execute; #

#посчитаем количество уникальных сайтов, которым попробуем присвоить категории
$sql="select count(1) from (select distinct site from scsq_quicktraffic where category is NULL) t;";
$sth = $dbh->prepare($sql);
$sth->execute; #

@countlines=$sth->fetchrow_array();

$completed=0;

while (@row = $sthr->fetchrow_array())

    {
#$id = $row[0];


print STDERR "Completed: ".$completedP."% Line:".$completed." Total: ".$countlines[0]." line \r";

$completed=$completed+1;

$completedP=int($completed/$countlines[0] *100);

$site = $row[0];

@itm= split ":", $site; #преобразуем, отрезав двоеточие

$itm[0];

$sql_category="select category from scsq_mod_categorylist where site = '$itm[0]' limit 1";
$sth = $dbh->prepare($sql_category);
$sth->execute; #
@rowcat = $sth->fetchrow_array();

$category = $rowcat[0];


	if($sth->rows>0){
	$sql_setcategory="update scsq_quicktraffic set category='$category' where site='$site';";
	$sth = $dbh->prepare($sql_setcategory);
	$sth->execute; #
	}

}
print "Categories in scsq_quicktraffic table updated";

#disconnecting from DB
$rc = $dbh->disconnect;
