<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> function.dictionaries.php </#FN>                                       
*                         File Birth   > <!#FB> 2024/10/10 19:32:31.139 </#FB>                                         *
*                         File Mod     > <!#FT> 2024/10/10 21:58:12.248 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/









#Функции работы со справочниками
#так как они все типовые - id, name то просто будем давать им имена, а механизм CRUD везде одинаков
function doPrintAllItems($globalSS,$dictname){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
    $_lang = $globalSS['lang'];

$dictnames = array();
$fields = array();
$dictnames = doGetDictnames($globalSS,$dictname);

    #здесь будем подкидывать имя таблицы
    $dicttable = $dictnames['dicttable'] ;
    $dictrealname = $dictnames['dictrealname'] ;
    $dictorder = $dictnames['dictorder'] ;

  
    $fields = doGetDictfields($globalSS,$dictname);

    $fields_count=count($fields);

    $fields_string = '';

    #соберем поля для select
    for($k=0;$k<$fields_count;$k++)
    {
      $fields_string .= $fields[$k]['field_name'].' '; 

    }

    $fields_string = trim($fields_string);

    $fields_string = str_replace(" ", ",", $fields_string);
    
    $queryAllItems="
        SELECT 
          id,
          $fields_string

       FROM $dicttable
       ORDER BY $dictorder";

       #соберем все параметры строки
       $dfltAction=doCreateGetArray($globalSS);

     #  echo $queryAllItems;
    
     $result = doFetchQuery($globalSS,$queryAllItems);

     if(isset($_GET['csv']) and $_GET['csv']==1)
       doMakeCSV2($globalSS,json_encode($result));   
     
       else {
  
    $numrow=1;
  
    echo 	"<h2>".$dictrealname.":</h2>";
    echo "<a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=1&dname=".$dictname.">".$_lang['stADD']."</a>";
    echo '<br><br><input type="text" id="QInput" onkeyup="QuickFinder(\'dtg\')" placeholder="'.$_lang['stFASTSEARCH'].'">';
    echo '<button id="ClearFilterBtn" type="button" onclick="ClearFilter(\'dtg\');">'.$_lang['stCLEARFILTER'].'</button>';

    echo "<br><br> <a href=?".$dfltAction."&csv=1><img src='img/csvicon.png' width=32 height=32 alt=Image title='Generate CSV'></a>";

    echo "<br /><br />";

    echo "<table id=\"dtg\" class=\"datatable\">
            <tr>
              <th ><b>#</b></th>";

              for($k=0;$k<$fields_count;$k++)
              {
                echo "<th><b>".$fields[$k]['field_descr']."</b></th>"; 
          
              }
    echo "   
           <th><b>EDIT</b></th>         
          <th><b>DELETE</b></th>
             </tr>
    ";

 

   foreach($result as $line) {

    $line_count_elem = count($line);
  
    echo "
     <tr>
       <td>".$numrow."</td>";
       for($k=1;$k<$line_count_elem;$k++)
       {
         echo "<td>$line[$k]</td>"; 
   
       }
      echo "<td align=center><a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=3&itemid=".$line[0]."&dname=".$dictname.">EDIT</a></td>
     <td align=center><a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=5&itemid=".$line[0]."&dname=".$dictname.">".$_lang['stDELETE']."</a></td>

     </tr>
     ";
     $numrow++;
    }  //end while
        
echo "</table>";
   echo "<br />";
   echo "<a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=1&dname=".$dictname.">".$_lang['stADD']."</a>";
   echo "<br />";
       }
    }


#вывод справочника как datalist
    function doPrintAllItemsAsDatalist($globalSS,$dictname,$fieldname){

      include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
      $_lang = $globalSS['lang'];
  
      $dictnames = array();

      $dictnames = doGetDictnames($globalSS,$dictname);
      
          #здесь будем подкидывать имя таблицы
          $dicttable = $dictnames['dicttable'] ;
          $dictrealname = $dictnames['dictrealname'] ;
  
      
      $queryAllItems="
          SELECT 
            id,
            ".$fieldname."
  
         FROM ".$dicttable."
         ORDER BY ".$fieldname." asc";
  
      $result = doFetchQuery($globalSS,$queryAllItems);
  
      $numrow=1;

      echo '<datalist id="'.$dictname.'" >';

   
      foreach($result as $line) {

        echo "
       <option value='".$line[1]."' >";
        $numrow++;
       }  //end while
      
            echo '</datalist>';

      
      }


      #вывод справочника как datalist (с ограничением по пользователю)
    function doPrintAllItemsRestrictedAsDatalist($globalSS,$dictname,$fieldname,$username){

      include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
      $_lang = $globalSS['lang'];
  
      $dictnames = array();

      $dictnames = doGetDictnames($globalSS,$dictname);
      
          #здесь будем подкидывать имя таблицы
          $dicttable = $dictnames['dicttable'] ;
          $dictrealname = $dictnames['dictrealname'] ;
  
      
      $queryAllItems="
          SELECT 
            id,
            ".$fieldname.",
            access_username
  
         FROM ".$dicttable."
         ORDER BY ".$fieldname." asc";
  
      $result = doFetchQuery($globalSS,$queryAllItems);
      


      $numrow=1;
     
      echo '<datalist id="'.$dictname.'" >';
      

   
      foreach($result as $line) {

        #ограничим вывод элементов только теми, что разрешены
        $grantedrow = explode(';',$line[2]);

        $grantedrow = array_map("trim", $grantedrow);

        if(in_array($username,$grantedrow) or $username=='admin')
        echo "
       <option value='".$line[1]."' >";
        $numrow++;
       }  //end while
      
            echo '</datalist>';

      
      }

function doPrintFormAddItem($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

  $m_host="";
  $m_hostname="";

  #закладка на случай модального окна
/*    if(!isset($GET['modal'])) 
    {
      $m_host="value='".$_GET['m_host']."'";
      $m_hostname="value='".$_GET['m_hostname']."'";
    }
  */  

    $dictname = $_GET['dname'];

    $dictnames = array();
    $fields = array();

    $dictnames = doGetDictnames($globalSS,$dictname);
    
    $fields = doGetDictfields($globalSS,$dictname);

    $fields_count=count($fields);

   
    
        #здесь будем подкидывать имя таблицы
        $dicttable = $dictnames['dicttable'] ;
        $dictrealname = $dictnames['dictrealname'] ;

    $_lang = $globalSS['lang'];

          echo "<h2>".$_lang['stADD']."</h2>";
         echo '
       <form action="?srv='.$globalSS['connectionParams']['srv'].'&id=10&actid=2&dname='.$dictname.'" method="post">
       <table class=datatable>';

    
         for($k=0;$k<$fields_count;$k++)
       {
        $dictfield=explode('_',$fields[$k]['field_type']);

        #Если нужно большое поле для ввода, используем textarea
        if($dictfield[0]==='textarea')
            
        echo '<tr><td><b>'.$fields[$k]['field_descr'].'</b></td><td>
        <textarea
          name="'.$fields[$k]['field_name'].'"
          rows="5"
          cols="60"
          '.$required_field.'
          ></textarea></td>';
        
      
      else

         echo '<tr><td><b>'.$fields[$k]['field_descr'].'</b></td><td><input type="text"  name="'.$fields[$k]['field_name'].'"></td></tr>'; 
   
       }
       
echo '      
       </table>
       <br />
       ';
       
     
         echo '
           <input type="submit" value="'.$_lang['stADD'].'"><br />
           </form>
           <br />';
       
         }
       
       
       function doAddItem($globalSS){
       
         include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
         
         $_lang = $globalSS['lang'];
       
         $dictname = $_GET['dname'];

         $fields = array();

         $dictnames = doGetDictnames($globalSS,$dictname);
         
         $fields = doGetDictfields($globalSS,$dictname);
     
         $fields_count=count($fields);
         
             #здесь будем подкидывать имя таблицы
             $dicttable = $dictnames['dicttable'] ;
             $dictrealname = $dictnames['dictrealname'] ;
          
  
             $fields_string = '';

             #соберем поля для select
             for($k=0;$k<$fields_count;$k++)
             {
               $fields_string .= $fields[$k]['field_name'].' '; 
         
             }
         
             $fields_string = trim($fields_string);
         
             $fields_string = str_replace(" ", ",", $fields_string);
       
        $field_value = array();

        foreach($fields as $field) {

         if(!isset($_POST[$field['field_name']])) $field_value[$field['field_name']]="";  else 

                #обрабатываем пустые значения
       if(strlen($_POST[$field['field_name']])===0) {
        $field_value[$field['field_name']]="null";
       }  
       else {

            $field_value[$field['field_name']]="'".$_POST[$field['field_name']]."'";
       }

        }

        $fields_value = implode(',',$field_value);

         $sql="INSERT INTO ".$dicttable." (
          $fields_string
   
          ) VALUES (
           $fields_value
          )";
  
       #echo $sql;
        if (!doQuery($globalSS, $sql)) {
         die('Error: Can`t insert new item');
        }

         echo "".$_lang['stADDED']."<br /><br />";

//         if(isset($_POST['field_ismodal']))
  //       echo "Закройте это окно и продолжите работу.";
    //     else 

         echo "<a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&dname=".$dictname." >".$_lang['stBACK']."</a>";
 
       }
       
       
       
       
       
       function doPrintFormEditItem($globalSS,$dictname){
       
       include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
         
       $_lang = $globalSS['lang'];
       
  
       $dictnames = array();
       $fields = array();
       
       $dictnames = doGetDictnames($globalSS,$dictname);
       

       $fields = doGetDictfields($globalSS,$dictname);

       $fields_count=count($fields);
   
       $fields_string = '';
   
       #соберем поля для select
       for($k=0;$k<$fields_count;$k++)
       {
         $fields_string .= $fields[$k]['field_name'].' '; 
   
       }
   
       $fields_string = trim($fields_string);
   
       $fields_string = str_replace(" ", ",", $fields_string);
       
       

           #здесь будем подкидывать имя таблицы
           $dicttable = $dictnames['dicttable'] ;
           $dictrealname = $dictnames['dictrealname'] ;
  


       $itemid=$_GET['itemid'];
       
       $queryOne="select
       id, 
       $fields_string
       from ".$dicttable." d
       where d.id=".$itemid."";
        
       
       
       $line=doFetchOneQuery($globalSS,$queryOne);
       
       
       echo "<h2>".$_lang['stEDIT']."</h2>";
       echo '
       <form action="?srv='.$globalSS['connectionParams']['srv'].'&id=10&actid=4&itemid='.$itemid.'&dname='.$dictname.'" method="post">
       <table class=datatable>';

       for($k=0;$k<$fields_count;$k++)
       {
        $dictfield=explode('_',$fields[$k]['field_type']);

        #Если нужно большое поле для ввода, используем textarea
        if($dictfield[0]==='textarea')
            
        echo '<tr><td><b>'.$fields[$k]['field_descr'].'</b></td><td>
        <textarea
          name="'.$fields[$k]['field_name'].'"
          rows="5"
          cols="60"
          '.$required_field.'
          >'.$line[$k+1].'</textarea></td>';
        
      
      else

         echo '<tr><td><b>'.$fields[$k]['field_descr'].'</b></td><td><input type="text" value=\''.$line[$k+1].'\'   name="'.$fields[$k]['field_name'].'"></td></tr>'; 
   
       }

echo '              
       </table>
       <br />
       ';
       
       echo '
       <input type="submit" value="'.$_lang['stSAVE'].'"><br />
       </form>
       <br />';
       
       echo '
          <form action="?srv='.$globalSS['connectionParams']['srv'].'&id=10&actid=5&itemid='.$itemid.'&dname='.$dictname.'" method="post">
          <input type="submit" value="'.$_lang['stDELETE'].'" ><br />
          </form>
          <br />';
       
       
       }
       
       function doSaveItem($globalSS){
       
       include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
       
       $dictname = $_GET['dname'];


       $dictnames = array();

       
       $fields = array();

       $dictnames = doGetDictnames($globalSS,$dictname);
       
       $fields = doGetDictfields($globalSS,$dictname);
   
       $fields_count=count($fields);
       
           #здесь будем подкидывать имя таблицы
           $dicttable = $dictnames['dicttable'] ;
           $dictrealname = $dictnames['dictrealname'] ;
        

           $fields_string = '';

           #соберем поля для select
           for($k=0;$k<$fields_count;$k++)
           {
             $fields_string .= $fields[$k]['field_name'].' '; 
       
           }
       
           $fields_string = trim($fields_string);
       
           $fields_string = str_replace(" ", ",", $fields_string);
     
      $field_value = array();

      

      foreach($fields as $field) {

       if(!isset($_POST[$field['field_name']])) $field_value[$field['field_name']]="";  else 
        
       #обрабатываем пустые значения
       if(strlen($_POST[$field['field_name']])===0) {
        $field_value[$field['field_name']]="null";
       }  
       else {

            $field_value[$field['field_name']]="'".$_POST[$field['field_name']]."'";
       }
      }

      foreach($fields as $field) {
          $field_value[$field['field_name']] = $field['field_name']."=".$field_value[$field['field_name']]."";   

      }         
       $_lang = $globalSS['lang'];
       
       $itemid = $_GET['itemid'];
       
      $field_value_string = implode(',',$field_value);  
       
       $queryUpdateOne="UPDATE  ".$dicttable." 
         set 
         $field_value_string
          
     
         where id = ".$itemid."  
           ";
       
    
      # echo $queryUpdateOne;
       
       
       if (!doQuery($globalSS, $queryUpdateOne)) {
         die('Error: Cant update one');
       }
       
       echo "".$_lang['stUPDATED']."<br /><br />";
       echo "<a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&dname=".$dictname." >".$_lang['stBACK']."</a>";
       
       
       
       }
       
       function doDeleteItem($globalSS){
       
       include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
       

       $dictname=$_REQUEST['dname'];


       $dictnames = array();

       $dictnames = doGetDictnames($globalSS,$dictname);
       
           #здесь будем подкидывать имя таблицы
           $dicttable = $dictnames['dicttable'] ;
           $dictrealname = $dictnames['dictrealname'] ;
         
       $_lang = $globalSS['lang'];
       
       $itemid = $_GET['itemid'];
       
       
       #удаляем item
       $queryDeleteOne="delete from ".$dicttable." where id='".$itemid."'";
       
       if (!doQuery($globalSS, $queryDeleteOne)) {
       die('Error: Cant delete one item');
       }
       
       echo "".$_lang['stDELETED']."<br /><br />";
       echo "<a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&dname=".$dictname." >".$_lang['stBACK']."</a><br />";
       
       
       }

function doGetDictnames($globalSS,$dictname){

  $_lang = $globalSS['lang'];

  $sqlquery="select field_dicttable, field_dictrealname, field_order from scsq_dicts t where t.field_dictname='$dictname'";
  
  $row=doFetchOneQuery($globalSS,$sqlquery);
     
$dictnames = array();

$dictnames['dicttable'] = $row[0];
$dictnames['dictrealname'] = $row[1];
$dictnames['dictorder'] = $row[2];


return $dictnames;


}

function doGetDictfields($globalSS,$dictname){

  $_lang = $globalSS['lang'];
  $_lang = $globalSS['lang'];

  $fields = array();
  
  $sqlquery="select field_fields from scsq_dicts t where t.field_dictname='$dictname'";
  
  $row=doFetchOneQuery($globalSS,$sqlquery);



  $row_field = explode(':',$row[0]);

  $i=0;
  foreach ($row_field as $line)
  {
    
    $field_line = explode(';',$line);

    $fields[$i]['field_name'] = $field_line[0];
    $fields[$i]['field_descr'] = $field_line[1];
    $fields[$i]['field_type'] = $field_line[2];
    $i++;
  }

  #echo var_dump($fields);
  return $fields;


}


function doPrintAllDicts($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
    $_lang = $globalSS['lang'];

    
    $queryAllItems="
        SELECT 
          id,
          field_dictname,
          field_dictrealname,
          field_dicttable,
          field_order

       FROM scsq_dicts
       ORDER BY field_dictrealname asc";

    #   echo $queryAllItems;
    $result = doFetchQuery($globalSS,$queryAllItems);

    $numrow=1;
    echo 	"<h2>Справочники:</h2>";
    echo "<a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=11>".$_lang['stADD']."</a>";
    echo "<br /><br />";

    echo "<table class=\"datatable\">
            <tr>
              <th ><b>#</b></th>";
                echo "<th><b>Имя справочника в системе</b></th>"; 
                echo "<th><b>Имя справочника для пользователя</b></th>"; 
                echo "<th><b>Имя таблицы справочника</b></th>"; 
                echo "<th><b>Сортировка по полю</b></th>"; 
          
              
    echo "   
           <th><b>EDIT</b></th>         
          <th><b>DELETE</b></th>
             </tr>
    ";

 

   foreach($result as $line) {

    $line_count_elem = count($line);
  
    echo "
     <tr>
       <td>".$numrow."</td>";
       for($k=1;$k<$line_count_elem;$k++)
       {
         echo "<td>$line[$k]</td>"; 
   
       }
      echo "<td align=center><a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=13&itemid=".$line[0].">EDIT</a></td>
     <td align=center><a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=15&itemid=".$line[0].">".$_lang['stDELETE']."</a></td>

     </tr>
     ";
     $numrow++;
    }  //end while
        
echo "</table>";
   echo "<br />";
   echo "<a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=11>".$_lang['stADD']."</a>";
   echo "<br />";
    
    }




function doPrintFormAddDict($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');

  $m_host="";
  $m_hostname="";

  #закладка на случай модального окна
/*    if(!isset($GET['modal'])) 
    {
      $m_host="value='".$_GET['m_host']."'";
      $m_hostname="value='".$_GET['m_hostname']."'";
    }
  */  

    $_lang = $globalSS['lang'];

          echo "<h2>".$_lang['stADD']."</h2>";
         echo '
       <form action="?srv='.$globalSS['connectionParams']['srv'].'&id=10&actid=12" method="post">
       <table class=datatable>';

    
         echo '<tr><td><b>Имя справочника в системе</b></td><td><input type="text"  name="field_dictname"></td></tr>'; 
         echo '<tr><td><b>Имя справочника для пользователя</b></td><td><input type="text"  name="field_dictrealname"></td></tr>'; 
         echo '<tr><td><b>Имя таблицы справочника</b></td><td><input type="text"  name="field_dicttable"></td></tr>'; 
         echo '<tr><td><b>Поля</b></td>
         <td><textarea
         name="field_fields"
         rows="5"
         cols="30"
         placeholder="Поля"></textarea></td></tr>'; 
         echo '<tr><td><b>Сортировка по полю</b></td><td><input type="text"  name="field_order"></td></tr>'; 
   
       
echo '      
       </table>
       <br />
       ';
       
     
         echo '
           <input type="submit" value="'.$_lang['stADD'].'"><br />
           </form>
           <br />';
       
         }
       
       
       function doAddDict($globalSS){
       
         include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
         
         $_lang = $globalSS['lang'];
       
         if(!isset($_POST['field_dictname'])) $field_dictname="";  else $field_dictname=$_POST['field_dictname'];
         if(!isset($_POST['field_dictrealname'])) $field_dictrealname="";  else $field_dictrealname=$_POST['field_dictrealname'];
         if(!isset($_POST['field_dicttable'])) $field_dicttable="";  else $field_dicttable=$_POST['field_dicttable'];
         if(!isset($_POST['field_fields'])) $field_fields="";  else $field_fields=$_POST['field_fields'];
         if(!isset($_POST['field_order'])) $field_order="";  else $field_order=$_POST['field_order'];
  

    
         $sql="INSERT INTO scsq_dicts (
          field_dictname,
          field_dictrealname,
          field_dicttable,
          field_fields,
          field_order
          ) VALUES (
          '$field_dictname',
          '$field_dictrealname',
          '$field_dicttable',
          '$field_fields',
          '$field_order'
          )";
  
       #echo $sql;
        if (!doQuery($globalSS, $sql)) {
         die('Error: Can`t insert new dict');
        }

         echo "".$_lang['stADDED']."<br /><br />";

//         if(isset($_POST['field_ismodal']))
  //       echo "Закройте это окно и продолжите работу.";
    //     else 

         echo "<a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=10 >".$_lang['stBACK']."</a>";
 
       }
       
       
       
       
       
       function doPrintFormEditDict($globalSS){
       
       include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
         
       $_lang = $globalSS['lang'];

       $itemid=$_GET['itemid'];
       
       $queryOne="select
       id, 
       field_dictname,
          field_dictrealname,
          field_dicttable,
          field_fields,
          field_order
       from scsq_dicts d
       where d.id=".$itemid."";
        
       
       
       $line=doFetchOneQuery($globalSS,$queryOne);
       
       
       echo "<h2>".$_lang['stEDIT']."</h2>";
       echo '
       <form action="?srv='.$globalSS['connectionParams']['srv'].'&id=10&actid=14&itemid='.$itemid.'" method="post">
       <table class=datatable>';

       echo '<tr><td><b>Имя справочника в системе</b></td><td><input type="text" value="'.$line[1].'"  name="field_dictname"></td></tr>'; 
       echo '<tr><td><b>Имя справочника для пользователя</b></td><td><input type="text" value="'.$line[2].'"  name="field_dictrealname"></td></tr>'; 
       echo '<tr><td><b>Имя таблицы справочника</b></td><td><input type="text" value="'.$line[3].'"  name="field_dicttable"></td></tr>'; 
       echo '<tr><td><b>Поля</b></td>
       <td><textarea
       name="field_fields"
       rows="5"
       cols="30"
       >'.$line[4].'</textarea></td></tr>'; 
       echo '<tr><td><b>Сортировка по полю</b></td><td><input type="text" value="'.$line[5].'"  name="field_order"></td></tr>'; 
 

echo '              
       </table>
       <br />
       ';
       
       echo '
       <input type="submit" value="'.$_lang['stSAVE'].'"><br />
       </form>
       <br />';
       
       echo '
          <form action="?srv='.$globalSS['connectionParams']['srv'].'&id=10&actid=15&itemid='.$itemid.'" method="post">
          <input type="submit" value="'.$_lang['stDELETE'].'" ><br />
          </form>
          <br />';
       
       
       }
       
       function doSaveDict($globalSS){
       
       include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
       
       $_lang = $globalSS['lang'];

        $itemid=$_GET['itemid'];

       if(!isset($_POST['field_dictname'])) $field_dictname="";  else $field_dictname=$_POST['field_dictname'];
       if(!isset($_POST['field_dictrealname'])) $field_dictrealname="";  else $field_dictrealname=$_POST['field_dictrealname'];
       if(!isset($_POST['field_dicttable'])) $field_dicttable="";  else $field_dicttable=$_POST['field_dicttable'];
       if(!isset($_POST['field_fields'])) $field_fields="";  else $field_fields=$_POST['field_fields'];
       if(!isset($_POST['field_order'])) $field_order="";  else $field_order=$_POST['field_order'];

 
       $queryUpdateOne="UPDATE  scsq_dicts 
         set 
         field_dictname='".$field_dictname."',
         field_dictrealname='".$field_dictrealname."',
         field_dicttable='".$field_dicttable."',
         field_fields='".$field_fields."',
         field_order='".$field_order."'
         where id = ".$itemid."  
           ";
       
    
       #echo $queryUpdateOne;
       
       
       if (!doQuery($globalSS, $queryUpdateOne)) {
         die('Error: Cant update one');
       }
       
       echo "".$_lang['stUPDATED']."<br /><br />";
       echo "<a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=10 >".$_lang['stBACK']."</a>";
       
       
       
       }
       
       function doDeleteDict($globalSS){
       
       include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
       

         
       $_lang = $globalSS['lang'];
       
       $itemid = $_GET['itemid'];
       
       
       #удаляем item
       $queryDeleteOne="delete from scsq_dicts where id='".$itemid."'";
       
       if (!doQuery($globalSS, $queryDeleteOne)) {
       die('Error: Cant delete one dict');
       }
       
       echo "".$_lang['stDELETED']."<br /><br />";
       echo "<a href=?srv=".$globalSS['connectionParams']['srv']."&id=10&actid=10 >".$_lang['stBACK']."</a><br />";
       
       
       }




?>