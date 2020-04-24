#!/usr/bin/perl

#Build date Friday 24th of April 2020 09:17:37 AM
#Build revision 1.2

=com
This script is quota helper. If user1 reach quota, helper send to squid signal to block user1 access.

As other perl script in Screen Squid, you need to configure DB section.

Additional parameter is "typeid". 
If your authorization by login, you need to set it in 0 (default).
If your authorization by IP, you need to set it in 1.

IMPORTANT: When you configure this script, you need to configure squid.conf.

This lines you need to add to conf:

If your authorization by login:

#acl section
external_acl_type e_lock ttl=10 negative_ttl=10 %LOGIN /path/to/script/userlock.pl
acl a_block external e_lock

If your authorization by IP address:

#acl section
external_acl_type e_lock ttl=10 negative_ttl=10 %SRC /path/to/script/userlock.pl
acl a_lock external e_lock

For both authorization

#http rules section
http_access allow a_lock

=cut

use DBI; # DBI  Perl!!!

#=======================CONFIGURATION BEGIN============================

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

#what you send to script? 0 - login, 1 - ipaddress
$typeid=0;

#show messages in stdout. Only for debug
$debug=0;

#=======================CONFIGURATION END==============================



#make conection to DB
if($dbtype==0){ #mysql
$dbh = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
}

if($dbtype==1){ #postgre
$dbh = DBI->connect("dbi:Pg:dbname=$db","$user",$pass,{PrintError => 1});
}


# Disable output buffering
$|=1;

#open log file for writing

sub debug {
        # Uncomment this to enable debugging
        if($debug==1) {
        
			print STDOUT "@_\n";
		}
}

#
# Check if a user belongs to a group
#
sub check {

#get quota status

#if auth by login
if($typeid == 0) {
$sql_getstatus="select u.active from scsq_alias a 
			   join scsq_logins l on a.tableid=l.id and a.typeid = 0
			   join scsq_mod_usermanager u on u.aliasid = a.id
			   where l.name='".$auth."'";
}			   

#if auth by IP address
if($typeid == 1) {

$sql_getstatus="select u.active from scsq_alias a 
			   join scsq_ipaddress l on a.tableid=l.id and a.typeid = 1
			   join scsq_mod_usermanager u on u.aliasid = a.id
			   where l.name='".$auth."'";
}			   

			   
$sthr = $dbh->prepare($sql_getstatus);
$sthr->execute; #

@row = $sthr->fetchrow_array();

#if no rows are fetched
if($sthr->rows <= 0) {
$row[0]=-1;
} 

&debug ("Got ".$row[0]." from database");

#if active = 1 then  all is good (pass user) 
#       	if($row[0] != 0 ) { if only active = 0 then ERR  
			if($row[0] == 1 ) { #by default only active = 1 then OK

				$answ='OK';
			}
				else
			{
				#otherwise block
				$answ='ERR';
				
			}
        
        return $answ;
       
}
#
# Main loop
#

while (<STDIN>) {
        chomp;
        &debug ("Got $_ from squid");

   #     print STDERR "Got from squid";
   
	#some fix for login
		@item = split " -", $_;
		$_=$item[0];
   
   #get information from squid
        $auth = $_;
   #try to check it
        $ans = &check($auth,$typeid);
       
      
         print "$ans\n";
		
}
