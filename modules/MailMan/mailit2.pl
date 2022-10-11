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
*                         File Birth   > <!#FB> 2022/06/10 21:31:40.946 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/08/11 21:07:50.546 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
=cut

   #SMTP SSL!!!
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


#=======================CONFIGURATION END============================


sub send_mail {
   my $to = $_[0];
   my $subject = $_[1];
   my $body = $_[2];

   my $from = $mail_from;
   my $password = $mail_password;

   my $smtp;

   if (not $smtp = Net::SMTP::SSL->new($mailserver,
                              Port => 465,
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



&send_mail($mail_to, 'Еще одно тестовое письмо', 'Добрый день! <br> Письмо с переносом строки.');