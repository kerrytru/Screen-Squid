#!/usr/bin/perl

=cut
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> mailit2.pl </#FN>                                                        
*                         File Birth   > <!#FB> 2022/06/10 21:31:40.946 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/10/18 21:32:37.116 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
=cut

   #SMTP SSL!!!
   use DBI;
   use Net::SMTP::SSL;

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
$db = "test5"; # name DB
}

#mail info
# dont change ' to " !!!

$mail_to = 'admin@mycompany.com';

$mail_from = 'screensquid@mycompany.com'; #login to mailserver
$mail_password = 'mypasswordfrommailbox'; #password to mailserver

$mailserver = 'smtp.mycompany.com';

$mailport = 465;


#=======================CONFIGURATION END============================




sub doQueryToDatabase {

    my $sqlquery=shift;

    if($dbtype==0){ #mysql
       $dbh_child1 = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
    }

    if($dbtype==1){ #postgre
       $dbh_child1 = DBI->connect("dbi:Pg:dbname=$db;host=$host","$user",$pass,{PrintError => 1});
    }
    
      $sth1 = $dbh_child1->prepare($sqlquery);
      $sth1->execute;
      $sth1->finish();
      $dbh_child1->disconnect();

}


sub doGrabMessages {

    

    if($dbtype==0){ #mysql
         $dbh_child = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);
         $sqlquery = "INSERT INTO scsq_mod_mailman (eventdate, message) select dateend, message from scsq_logtable where dateend>=(select val from scsq_modules_param where param='Last Date Send log')";

         $sqldateupdate = "update scsq_modules_param set val=unix_timestamp(sysdate()) where param='Last Date Send log'";

    }

    if($dbtype==1){ #postgre
         $dbh_child = DBI->connect("dbi:Pg:dbname=$db;host=$host","$user",$pass,{PrintError => 1});

         $sqlquery = "INSERT INTO scsq_mod_mailman (eventdate, message) select dateend, message from scsq_logtable where dateend>=(select val::integer from scsq_modules_param where param='Last Date Send log')";


         $sqldateupdate = "update scsq_modules_param set val=extract(epoch from current_timestamp(0)) where param='Last Date Send log'";

    }
    
      $sth = $dbh_child->prepare($sqlquery);
      $sth->execute;
#      @row=$sth->fetchrow_array;
      $sth->finish();

      $dbh_child->disconnect();

#после того как забрали события из лога, обновим дату в параметрах. Чтобы старые события не дублировать
   doQueryToDatabase($sqldateupdate);

#  return @row;

}

sub doSendMessages {



    if($dbtype==0){ #mysql

      $dbh_child = DBI->connect("DBI:mysql:$db:$host:$port",$user,$pass);

      $sqlquery = "select id, from_unixtime(eventdate,'%d-%m-%y %k:%i:%s') ,message from scsq_mod_mailman where sentstate=0";

    }

    if($dbtype==1){ #postgre

       $dbh_child = DBI->connect("dbi:Pg:dbname=$db;host=$host","$user",$pass,{PrintError => 1});

       $sqlquery = "select id, to_char(to_timestamp(eventdate),'DD-MM-YYYY HH24:MI:SS') ,message from scsq_mod_mailman where sentstate=0";

    }
    
      $sth = $dbh_child->prepare($sqlquery);
      $sth->execute;
  
      while(@row=$sth->fetchrow_array) {
         #сформируем письмо
         @subj=split(':', $row[2]);
      
         &send_mail($mail_to, $subj[0], $row[1].' '.$subj[1]);

         $sqlupdate = "update scsq_mod_mailman set sentstate=1 where id=$row[0]";

         doQueryToDatabase($sqlupdate);

      }
  
      $sth->finish();
      $dbh_child->disconnect();

}

sub send_mail {
   my $to = $_[0];
   my $subject = $_[1];
   my $body = $_[2];

   my $from = $mail_from;
   my $password = $mail_password;

   my $smtp;

   if (not $smtp = Net::SMTP::SSL->new($mailserver,
                              Port => $mailport,
                              Debug => 0)) {
      die "Could not connect to server\n";
   }

   $smtp->auth($from, $password)
      || die "Authentication failed!\n";

   $smtp->mail($from . "\n");
   my @recepients = split(/,/, $to);
   foreach my $recp (@recepients) {
      $smtp->to($recp . "\n");
   }
   $smtp->data();
   $smtp->datasend("From: " . $from . "\n");
   $smtp->datasend("To: " . $to . "\n");
   $smtp->datasend("Subject: " . $subject . "\n");
   $smtp->datasend("\n");
   $smtp->datasend($body . "\n");
   $smtp->dataend();
   $smtp->quit;
}

# Send away!



#
doGrabMessages;
doSendMessages;