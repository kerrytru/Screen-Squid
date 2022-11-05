<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> function.modules.php </#FN>                                            
*                         File Birth   > <!#FB> 2022/09/27 20:10:44.785 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/09/27 20:11:11.746 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/





#узнаем установлен ли модуль 
function doQueryExistsModule($globalSS,$modulename){

include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

$queryExistModule = "select count(1) from scsq_modules where name = '".$modulename."';";

$result = doFetchOneQuery($globalSS,$queryExistModule);

#1 - exists, 0 - no exists
return $result[0];

}




?>