<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> logout.php </#FN>                                                      
*                         File Birth   > <!#FB> 2022/09/28 21:17:02.900 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/09/28 21:17:09.560 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/


 

include("../../config.php");

setcookie("loggedAdm", 0, 0,'/');
unlink('hash');

?>

<script type="text/javascript">
<?php  echo "window.open('".$globalSS['root_http']."/index.php','_parent');";?>
</script>