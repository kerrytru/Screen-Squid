#!/usr/bin/perl

#Build date Wednesday 22nd of April 2020 17:08:01 PM
#Build revision 1.1


use DBI; # DBI  Perl!!!

=cut

To use this log daemon you need

1. Configure connection to database
2. Configure squid.conf. Add this lines:

#this is default. Log to file.
access_log stdio:/var/log/squid/access.log squid

#log to daemon
access_log daemon: squid
logfile_daemon /path/to/script/fetch_db_daemon.pl



=cut


#=======================CONFIGURATION BEGIN============================



$dbtype = "0"; #type of db - 0 - MySQL, 1 - PostGRESQL

$numproxy = "0"; #num proxy default 0, but you can use one db to many squids log. server1 : $numproxy="0", server2 : $numproxy="1" etc.

#mysql default config
if($dbtype==0){
$host = "localhost"; # host s DB
$port = "3306"; # port DB
$user = "mysql-user"; # username k DB
$pass = "pass"; # pasword k DB
$db = "test5"; # name DB
}

#didnt test on postgre! 

#postgresql default config
#if($dbtype==1){
#$host = "localhost"; # host s DB
#$port = "5432"; # port DB
#$user = "postgres"; # username k DB
#$pass = "pass"; # pasword k DB
#$db = "test"; # name DB
#}


#=======================CONFIGURATION END==============================

$|=1;


if($dbtype==1){ #postgre
$dbh = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
}




# main loop
while (<STDIN>) {
        chomp;

    my $cmd = substr($_, 0, 1);      # extract command byte
	
	$line = substr($_, 1, length($_));

	$startdate=time;

    if ( $cmd eq 'L' ) {

#make conection to DB
if($dbtype==0){ #mysql
	
        eval {                  # we catch db errors 
			#$dbh = DBI->connect_cached("DBI:mysql:$db:$host:$port",$user,$pass);
			DBI->connect_cached("DBI:mysql:$db:$host:$port",$user,$pass,{PrintError => 0});
        
        };
		if ($EVAL_ERROR) { #no connect then exit and dont crash squid. It is quiet mode
			print "Error";
			exit;
		}
		else
		{
			$dbh = DBI->connect_cached("DBI:mysql:$db:$host:$port",$user,$pass,{PrintError => 0}); #if you need log to cache.log set it to 1.
		}
		
	}
		$sql_insert="INSERT INTO scsq_mod_dbDaemon (date, lineitem, numproxy) VALUES (".$startdate.",'".$line."',".$numproxy.")";

        eval {                  # we catch db errors 
			$sth = $dbh->prepare($sql_insert);
            $sth->execute or die $sth->errstr;
        };
       		
    }
}

$dbh->disconnect();
