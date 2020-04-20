<?php

#Build date Monday 20th of April 2020 15:45:06 PM
#Build revision 1.0

// config
// Конфигурация

#because operation need more time than 30 seconds, we need increase it for execution script.
#look at https://www.php.net/manual/en/function.set-time-limit.php for more information.
$timelimit = 600; 


#default sort column in all export reports ("site" - for ordering by site, "s" - for ordering by size in bytes) 
$sortcolumn = "site";

#default order in all export reports ("asc" - ascending (from 0 to 100 for example), "desc" - descending (from 100 to 0 for example) 
$sortorder = "desc";


?>
