<?php

#Build date Friday 24th of April 2020 09:21:54 AM
#Build revision 1.1

// config connection to LDAP server
//Конфигурация подключения к LDAP серверу

$ldapserver = 'localhost';
$ldapuser      = 'cn=Manager,dc=my-domain,dc=com'; 
$ldappass     = '12345678';
$ldaptree    = "DC=my-domain,DC=com";

//set this field to your in User scheme LDAP. For AD it coulbe sAMAccountName, for LDAP it uid or something else
$fldUsername = "uid";


?>
