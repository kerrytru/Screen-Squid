#!/usr/bin/perl

#Build date Thursday 16th of April 2020 17:40:02 PM
#Build revision 1.1

=com

This script is quota helper. If user1 reach quota, helper send to squid signal to limit speed user1 access.

As other perl script in Screen Squid, you need to configure DB section.

Additional parameter is "typeid". 
If your authorization by login, you need to set it in 0 (default).
If your authorization by IP, you need to set it in 1.

IMPORTANT: When you configure this script, you need to configure squid.conf.

IMPORTANT 2: Your squid must be >= 3.5 version.

This lines you need to add to conf:

If your authorization by login:

#acl section
external_acl_type e_speed ttl=10 negative_ttl=10 %LOGIN /var/www/html/freetime/modules/Quotas/quotaspeed.pl
acl a_speed external e_speed

If your authorization by IP address:

#acl section
external_acl_type e_speed ttl=10 negative_ttl=10 %SRC /var/www/html/freetime/modules/Quotas/quotaspeed.pl
acl a_speed external e_speed

For both authorization

#http rules section 
http_access allow a_speed


#delay pools section

delay_pools 2
delay_class 1 5
delay_class 2 5


#acl some_group external -m=' ' tag slowspeed
#slowspeed and fastspeed are passphrases which we tell squid. 

acl slowclient note tag slowspeed

acl fastclient note tag1 fastspeed



# Fast clients in a first pool.
delay_access 1 allow fastclient
delay_access 1 deny all

# Slow clients in a second pool
delay_access 2 allow slowclient
delay_access 2 deny all

#Limit speed to delay pools

#First unlimited
delay_parameters 1 -1/16000

#Second set 64 kbit/s

delay_parameters 2 8000/8000

=cut

use DBI; # DBI  Perl!!!

#=======================CONFIGURATION BEGIN============================

my $dbtype = "0"; #type of db - 0 - MySQL, 1 - PostGRESQL

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

#make conection to DB
if($dbtype==0){ #mysql
$dbh = DBI->connect_cached("DBI:mysql:$db:$host:$port",$user,$pass);
}

if($dbtype==1){ #postgre
$dbh = DBI->connect_cached("dbi:Pg:dbname=$db;host=$host","$user",$pass,{PrintError => 1});
}

#get quota status

#if auth by login
if($typeid == 0) {
$sql_getquotastatus="select q.status from scsq_alias a 
			   join scsq_logins l on a.tableid=l.id and a.typeid = 0
			   join scsq_mod_quotas q on q.aliasid = a.id
			   where l.name='".$auth."'";
}			   

#if auth by IP address
if($typeid == 1) {

$sql_getquotastatus="select q.status from scsq_alias a 
			   join scsq_ipaddress l on a.tableid=l.id and a.typeid = 1
			   join scsq_mod_quotas q on q.aliasid = a.id
			   where l.name='".$auth."'";
}			   

			   
$sthr = $dbh->prepare($sql_getquotastatus);
$sthr->execute; #

@row = $sthr->fetchrow_array();

#if no rows are fetched
if($sthr->rows <= 0) {
$row[0]=-1;
} 

&debug ("Got ".$row[0]." from database");

#if quota status = 0 then all is good (pass user) 
        	if($row[0] == 0) {

				$answ='OK tag1=fastspeed';

			}
				else
			{
				#otherwise block
		#		$answ='ERR';
				$answ='OK tag=slowspeed';
		
			}
        
        return $answ;
       
}
#
# Main loop
#

while (<STDIN>) {
        chomp;
        &debug ("Got $_ from squid");

	#some fix for login
		@item = split " -", $_;
		$_=$item[0];

   #     print STDERR "Got from squid";
   
   #get information from squid
        $auth = $_;
   #try to check it
        $ans = &check($auth,$typeid);
       
      
         print "$ans\n";
		
}

