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


#make conection to DB
$dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);

#обновляем категории
$sql="select id,site,category from scsq_quicktraffic where category is NULL;";
$sthr = $dbh->prepare($sql);
$sthr->execute; #

while (@row = $sthr->fetchrow_array())

    {
$id = $row[0];
$site = $row[1];

@itm= split ":", $site; #преобразуем, отрезав двоеточие

$site = $itm[0];

$sql_category="select category from scsq_mod_categorylist where site = '$site' limit 1";
$sth = $dbh->prepare($sql_category);
$sth->execute; #
@rowcat = $sth->fetchrow_array();

$category = $rowcat[0];


	if($sth->rows>0){
	$sql_setcategory="update scsq_quicktraffic set category='$category' where id=$id;";
	$sth = $dbh->prepare($sql_setcategory);
	$sth->execute; #
	}

}
print "Categories in scsq_quicktraffic table updated";

#disconnecting from DB
$rc = $dbh->disconnect;
