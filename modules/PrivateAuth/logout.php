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
*                         File Mod     > <!#FT> 2024/06/25 20:57:11.889 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 2.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/


 

include_once("../../config.php");

unset($_SESSION['user_id']);
unset($_SESSION['user_login']);
?>

<script type="text/javascript">
<?php  echo "window.open('".$globalSS['root_http']."index.php','_parent');";?>
</script>