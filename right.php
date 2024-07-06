<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> right.php </#FN>                                                       
*                         File Birth   > <!#FB> 2021/10/19 22:32:00.052 </#FB>                                         *
*                         File Mod     > <!#FT> 2024/06/25 20:54:08.894 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.12.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/

  
  include("config.php");

 #если нет авторизации, сразу выходим
if (!isAuth()) 
{
	header("Location: ".$globalSS['root_http']."/modules/PrivateAuth/login.php"); exit();
}

  #если есть дружеские логины, IP адреса или сайты. Соберём их.
  $globalSS['goodLoginsList']=doCreateFriendList($globalSS,'logins');
  $globalSS['goodIpaddressList']=doCreateFriendList($globalSS,'ipaddress');
  $globalSS['goodSitesList'] = doCreateSitesList($globalSS);

  $dbtype = $globalSS['connectionParams']['dbtype'];

  if(!isset($_GET['csv'])) {

?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="<?php echo $globalSS['root_http']; ?>/themes/<?php echo $globalSS['globaltheme']; ?>/global.css"/>

</head>
<body>

<script type="text/javascript" src="javascript/sortable.js"></script>
<script type="text/javascript" src="javascript/misc.js"></script>
<script language=javascript>

function switchTables()
{
   if (document.getElementById("loginsTable").style.display == "table" ) {
          document.getElementById("loginsTable").style.display="none";

} else {
document.getElementById("loginsTable").style.display="table";
}
   if (document.getElementById("ipaddressTable").style.display == "table" ) {
          document.getElementById("ipaddressTable").style.display="none";

} else {
document.getElementById("ipaddressTable").style.display="table";
}

}

//переход к частным отчетам по логину, IP адресу или ещё чему
//параметры не указываем, а работаем с массивом arguments, т.е. сколько передали, столько и обрабатываем
//чтобы не думать, в функциях будем передать названия полей для GET и его параметр
//например GoPartlyReports('id',8,'login',3) будет означать &id=8&login=3
function GoPartlyReports()
{
//alert(arguments[0]);
var j = 0;

var args = [];
var ret ="";

for (var i = 0; i < arguments.length; i=i+2) {
  args[j] = arguments[i]+'='+arguments[i+1];
  j=j+1;
}

ret = args.join('&');

parent.right.location.href='reports/reports.php?srv=<?php echo $srv ?>&'+ret;

}


</script>


<?php

} //if isset($_GET['csv'])


if(!isset($_GET['id'])) {
echo "<h2>".$_lang['stWELCOME']."".$vers."</h2>";

}

$start=microtime(true);



       if(isset($_POST['userlogin'])) // логин для авторизации в кабинете
          $userlogin=$_POST['userlogin'];
        else
          $userlogin="";

        if(isset($_POST['userpassword'])) // пароль для авторизации в кабинете
          $userpassword=md5(md5(trim($_POST['userpassword'])));
        else
          $userpassword="";

        if(isset($_POST['activeauth'])) // включить авторизацию
          $activeauth=1;
        else
          $activeauth=0;

        if(isset($_POST['changepassword'])) // признак. Если установлено - изменить пароль
          $changepassword=1;
        else
          $changepassword=0;

if(!isset($_GET['id'])) {echo "OK";  $_GET['id'] = 0;}//удалить надо
			//echo $_lang['stALLISOK'];

  if(1==null) { 
  //  echo $_lang['stDONTFORGET']; //и это тоже удалить
	echo ""; 
  }
  else
  {
	  
	
	  
	if(isset($_GET['id'])) {

    if($_GET['id']==1) { //краткая статистика
      doGetStatistics($globalSS);
    }

  //end GET[id]=1
//    else

      if($_GET['id']==2) {  //aliases

        if(isset($_GET['actid'])) //action ID.
          $actid=$_GET['actid'];
        else
          $actid=0;


        if(isset($_GET['aliasid'])) //alias ID из таблицы scsq_alias для редактирования/удаления alias.
          $aliasid=$_GET['aliasid'];
        else
          $aliasid=0;

        if(isset($_POST['name'])) // имя
          $name=$_POST['name'];
        else
          $name=0;

        if(isset($_POST['typeid'])) // login или ipaddress 0 - логин, 1 - ipadddress 
          $typeid=1;
        else
          $typeid=0;

        if(isset($_POST['tableid'])) // id логина или ipaddress
          $tableid=$_POST['tableid'];
        else
          $tableid=0;

 


///надо добавить обработку ошибки подключения к БД

        if(!isset($_GET['actid'])) {
          doPrintAllAliases($globalSS);

        } // end if(!isset...
    

        if($actid==1) {
          doPrintFormAddAlias($globalSS);
        }  //end if($actid==1..

        if($actid==2) {  //добавление алиаса
          
          #соберем параметры в массив и отправим. Удобно будет потом отовсюду пользоватся.
          $alias_params = array();
          
          $alias_params['name']=$_POST['name'];

          if(!isset($_POST['typeid'])) $alias_params['typeid']=0;  else  $alias_params['typeid']=1;
          if(!isset($_POST['activeauth'])) $alias_params['activeauth']=0; else $alias_params['activeauth']=1;
      
          #если не выбран ни один логин или IP адрес, вернём ошибку. 
          #По хорошему, нужно напиать валидатор формы, чтобы JS не давал пройти дальше.
         # echo "tableid=".$_POST['tableid'];
          if($_POST['tableid']=="") die ('Error: No login or ipaddress choosed! Alias cant be added');
      
          $alias_params['tableid']=$_POST['tableid'];
          $alias_params['userlogin']=$_POST['userlogin'];
          $alias_params['userpassword']=md5(md5(trim($_POST['userpassword'])));
      
          doAliasAdd($globalSS,$alias_params);
        }

        if($actid==3) { ///Редактирование алиаса
          doAliasEdit($globalSS);

        }
        if($actid==4) { //сохранение изменений UPDATE

         #соберем параметры в массив и отправим. Удобно будет потом отовсюду пользоватся.
         $alias_params = array();

         $alias_params['name']=$_POST['name'];
         $alias_params['aliasid'] = $_GET['aliasid'];

         if(isset($_POST['changepassword'])) // признак. Если установлено - изменить пароль
         $alias_params['changepassword']=1;
         else
         $alias_params['changepassword']=0;

         if(!isset($_POST['typeid'])) $alias_params['typeid']=0;  else  $alias_params['typeid']=1;
         if(!isset($_POST['activeauth'])) $alias_params['activeauth']=0; else $alias_params['activeauth']=1;
     
         $alias_params['tableid']=$_POST['tableid'];
         $alias_params['userlogin']=$_POST['userlogin'];
         $alias_params['userpassword']=md5(md5(trim($_POST['userpassword'])));

          doAliasSave($globalSS,$alias_params);
 
        }

        if($actid==5) { //удаление DELETE
          $aliasid = $_GET['aliasid'];
          doAliasDelete($globalSS,$aliasid);

        } //удаление
      } ///end if($_GET['id']==2

//else

         if($_GET['id']==3) { //группы

            if(isset($_GET['actid'])) //action ID.
              $actid=$_GET['actid'];
            else
              $actid=0;


              if(isset($_GET['groupid'])) //group ID из таблицы scsq_groups для редактирования/удаления group.
                $groupid=$_GET['groupid'];
              else
                $groupid=0;

              if(isset($_POST['name'])) // имя
                $name=$_POST['name'];
              else
                $name=0;

              if(isset($_POST['typeid'])) // login или ipaddress 0 - логин, 1 - ipadddress 
                $typeid=1;
              else
                $typeid=0;

              if(isset($_POST['comment'])) // комментарий к группе 
                $comment=$_POST['comment'];
              else
                $comment="";

///SQL querys


          
        
            $queryGroupMembers="select aliasid from scsq_aliasingroups where groupid='".$groupid."'";



            

            if(!isset($_GET['actid'])) {
              doPrintAllGroups($globalSS);
            }

            if($actid==1) {
              doPrintFormAddGroup($globalSS);
            }

            if($actid==2) { ///добавление группы
              doGroupAdd($globalSS);
            }

            if($actid==3) { ///Редактирование группы
              doGroupEdit($globalSS);
 
            } // end actid=3

            if($actid==4) { //сохранение изменений UPDATE
              doGroupSave($globalSS);


            } //end actid=4

            if($actid==5) {//удаление DELETE

              doGroupDelete($globalSS);

            } //end actid=5

            if($actid==6) { ///Просмотр группы
              doGroupList($globalSS);
        
            } // end actid=6

          } ///end GET[id]=3

//else

            if($_GET['id']==4) { ///быстрый поиск
           
              if(isset($_GET['actid'])) //action ID.
                $actid=$_GET['actid'];
              else
              $actid=0;


              if(isset($_POST['findstr'])) //alias ID из таблицы scsq_alias для редактирования/удаления alias.
                $findstr=$_POST['findstr'];
              else
                $findstr="";

              if(isset($_POST['typeid'])) // login или ipaddress 0 - логин, 1 - ipadddress 
                $typeid=1;
              else
                $typeid=0;

///SQL querys

              if($typeid==0)
                $queryForFindstr="select name,id from (select name,id from scsq_logins where lower(name) like lower('%".$findstr."%')) as tmp where tmp.id NOT IN (".$globalSS['goodLoginsList'].") order by name asc;";
              
              if($typeid==1)
                $queryForFindstr="select name,id from (select name,id from scsq_ipaddress where name like '%".$findstr."%') as tmp where tmp.id NOT IN (".$globalSS['goodIpaddressList'].") order by name asc;";


              if(!isset($_GET['actid'])) {

                echo "<h2>".$_lang['stSEARCHFORM']."</h2>";
                echo '
                  <form action="right.php?srv='.$srv.'&id=4&actid=1" method="post">
                 		<table class=datatable>
					   <tr><td>'.$_lang['stSEARCHSTRING'].':</td> <td><input type="text" name="findstr"></td></tr>
					   <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" name="typeid"></td></tr>
						</table>
                 <br />
                  <input type="submit" value="'.$_lang['stSEARCH'].'"><br />
                  </form>
                  <br />';
              }

              if($actid==1) { ///результаты поиска

                echo $_lang['stSEARCHFORM'];
                echo '
                  <form action="right.php?srv='.$srv.'&id=4&actid=1" method="post">
                 		<table class=datatable>
					   <tr><td>'.$_lang['stSEARCHSTRING'].':</td> <td><input type="text" name="findstr"></td></tr>
					   <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" name="typeid"></td></tr>
						</table>
                 <br />
                  <input type="submit" value="'.$_lang['stSEARCH'].'"><br />
                  </form>
                  <br />';

/////////// LOGINS

                if($typeid==0) {
                  echo "
                    <table id=report_table_id_1 class=datatable>
                    <tr>
                      <th >
                        #
                      </th>
                      <th>
                        ".$_lang['stLOGIN']."
                      </th>
                    </tr>";

                  $result=doFetchQuery($globalSS, $queryForFindstr) or die ('Error find str');
                  $numrow=1;

                  foreach ($result as $line) {
                    echo "<tr>";
                    echo "<td>".$numrow."</td>";
                    echo "<td><a href=javascript:GoPartlyReports('id','8','loginid','".$line[1]."','loginname','".$line[0]."','typeid','0')>".$line[0]."</td>";
                    echo "</tr>";
                    $numrow++;
                  }
		           
                  echo "</table>";
                }

/////////// LOGINS  END

//////////// IPADDRESS

                if($typeid==1) {
                  echo "
                    <table id=report_table_id_1 class=datatable>
                    <tr>
                      <th >
                      #
                      </th>
                      <th>
                      ".$_lang['stIPADDRESS']."
                      </th>
                    </tr>";

                  $result=doFetchQuery($globalSS, $queryForFindstr) or die ('Cant find str');
                  $numrow=1;

                  foreach ($result as $line) {
                    echo "<tr>";
                    echo "<td>".$numrow."</td>";
                    echo "<td><a href=javascript:GoPartlyReports('id','11','ipaddressid','".$line[1]."','ipaddressname','".$line[0]."','typeid','1')>".$line[0]."</td>";
                    echo "</tr>";
                    $numrow++;
                  }
		  
                  echo "</table>";

                }
              }
/////////////// IPADDRESS END

            } ///end GET[id]=4
 ///	    else
	

   if($_GET['id']==5) {
      echo "
      <h2>".$_lang['stLOGTABLE'].":</h2>
      <table class=datatable>
      <tr>
         <th>#</th>
         <th>".$_lang['stLOGDATESTART']."</th>
         <th>".$_lang['stLOGDATEEND']."</th>
         <th>".$_lang['stLOGMESSAGE']."</th>
      </tr>
   ";
  		
  		if($dbtype==0)
  		#mysql
		  $queryLogTable="SELECT
			FROM_UNIXTIME(datestart,'%Y-%m-%d %H:%i:%s') as d1,
			FROM_UNIXTIME(dateend,'%Y-%m-%d %H:%i:%s'),
			message
		  FROM scsq_logtable order by d1 desc";

		if($dbtype==1)
		#postgre 
		  $queryLogTable="SELECT
			to_char(to_timestamp(datestart),'YYYY-MM-DD HH24:MI:SS') as d1,
			to_char(to_timestamp(dateend),'YYYY-MM-DD HH24:MI:SS'),
 			message
		  FROM scsq_logtable order by d1 desc";



                  $result=doFetchQuery($globalSS,$queryLogTable);
                  $numrow=1;

                  foreach ($result as $line) {
                    echo "<tr>";
                    echo "<td>".$numrow."</td>";
                    echo "<td>".$line[0]."</td>";
                    echo "<td>".$line[1]."</td>";
                    echo "<td>".$line[2]."</td>";
                    echo "</tr>";
                    $numrow++;
                  }
		 
                  echo "</table>";

    }  //end GET[id]=5

//id==6 перенесен ниже


 if($_GET['id']==7) {
   	

	

$path    = 'modules/';
$files = array_diff(scandir($path), array('.', '..'));



    echo "
      <h2>".$_lang['stMODULEMANAGER']." Screen Squid:</h2>
      <table class=datatable>
      <tr>
         <th>#</th>
         <th>".$_lang['stNAME']."</th>
	 <th>".$_lang['stDESCRIPTION']."</th>
         <th>".$_lang['stINSTALL']."</th>
         <th>".$_lang['stUNINSTALL']."</th>
      </tr>
	";
$num=1;
foreach($files as $file)
{
	include("modules/".$file."/module.php");
	$module = new $file($globalSS);

echo "
      <tr>

         <td>".$num."</td>

         <td><a href=\"modules/".$file."/index.php?srv=".$srv."\">".$file."</a></td>
         <td>".$module->GetDesc()."</td>
         <td><a href=\"right.php?srv=".$srv."&id=7&actid=10&mod=".$file."\">".$_lang['stINSTALL']."</a></td>
         <td><a href=\"right.php?srv=".$srv."&id=7&actid=11&mod=".$file."\">".$_lang['stUNINSTALL']."</a></td>

      </tr>
	";
$num++;
}
echo "	</table>
   ";

		if(isset($_GET['actid']))
		{
			if($_GET['actid'] == 10) ///установить
			{
			
			$test = new $_GET['mod']($globalSS);
			echo $test->Install();
				

			} //if($_GET['actid'] == 10) 
			
			if($_GET['actid'] == 11) ///удалить
			{
			
			$test = new $_GET['mod']($globalSS);	
			$test->Uninstall();
				

			} //if($_GET['actid'] == 11) 

		} //if(isset($_GET['actid']))
                  
                  

    }  //end GET[id]=7



    if($_GET['id']==10) {  //dicts

      if(isset($_GET['actid'])) //action ID.
        $actid=$_GET['actid'];
      else
        $actid=0;
    
    
    
    ///надо добавить обработку ошибки подключения к БД
    
      if(!isset($_GET['actid'])) {
        doPrintAllItems($globalSS,$_GET['dname']);
    
      } // end if(!isset...
    
    
      if($actid==1) {
        doPrintFormAddItem($globalSS,$_GET['dname']);
      }  //end if($actid==1..
    
      if($actid==2) {  //добавление 
        doAddItem($globalSS);
      }
    
      if($actid==3) { ///Редактирование 
        doPrintFormEditItem($globalSS,$_GET['dname']);
    
      }
      if($actid==4) { //сохранение изменений UPDATE
        doSaveItem($globalSS);
    
      }
    
      if($actid==5) { //удаление DELETE
        doDeleteItem($globalSS);
    
      } //удаление
  
    
      if($actid==10) {
        doPrintAllDicts($globalSS);
    
        } // end if(!isset...
      
    
        if($actid==11) {
        doPrintFormAddDict($globalSS);
        }  //end if($actid==1..
    
        if($actid==12) {  //добавление 
        doAddDict($globalSS);
        }
    
        if($actid==13) { ///Редактирование 
        doPrintFormEditDict($globalSS);
    
        }
        if($actid==14) { //сохранение изменений UPDATE
        doSaveDict($globalSS);
    
        }
    
        if($actid==15) { //удаление DELETE
        doDeleteDict($globalSS);
    
        } //удаление
    
       
  
  
  
  
      } ///end if($_GET['id']==10


    if($_GET['id']==999) {
   	//тестовая страница
     echo "Test page<br /><br />";

 
     $squidhost=$cfgsquidhost[$srv];
     $squidport=$cfgsquidport[$srv];
     $cachemgr_passwd=$cfgcachemgr_passwd[$srv];
     
     
      
     //вывод на экран диагностической информации
     
     echo "<b>Check configuration settings, server ".$srvname[$srv]."</b><br /><br />";
     
     if($dbtype==0)
       echo "Type DB MySQL<br />";	
     if($dbtype==1)
       echo "Type DB PostGRESQL<br />";	
     
     echo "Step1. Trying connect to DB.<br /><br />";
     
     echo "Connection parameters:<br /><br />";
     echo "Host: ".$globalSS['connectionParams']['addr']."<br />";
     echo "Database name: ".$globalSS['connectionParams']['dbase']."<br />";
     echo "Username: ".$globalSS['connectionParams']['usr']."<br />";
     echo "Password: ".$globalSS['connectionParams']['psw']."<br /><br />";

     
     

     if(doConnectToDatabase($globalSS['connectionParams'])!="ErrorConnection")
     echo "Result: <b>Ok!</b>";
     else {
     echo "Result: <b>Error!</b>";
     echo "<br><br>";
     echo "
     Some solutions:<br>
     1. Check that DB server is ONLINE.<br>
     2. Check for connection settings (login, pass,db, host).<br>
     3. Check that you can connect from your system to database server on default DB port (3306 to MySQL or 5432 to PostGRESQL).<br>
     4. Check that user <b>".$usr."</b> have rights to connect to DB.<br>
     5. If you have no idea about problem, join our telegram group <a href=http://t.me/screensquid>here</a>. We try to help you.
     ";
     }
     
     echo "<br /><br />";
     

     echo "Step2. ";
     
     echo "Trying connect to Cachemgr...";
     
     $fp = fsockopen($squidhost,$squidport, $errno, $errstr, 10); 
     
     if($fp)
       {
       echo "Ok!";
       fclose($fp);
       }
     else
       {
       echo "Error! ";
       echo "<br>";
       echo "
       1. Check for connection settings.<br>
       2. Disable SElinux (if you have it). <br>
       2. Check this solution https://sourceforge.net/p/screen-squid/tickets/21/<br>
       3. If you have no idea about problem, join our telegram group <a href=http://t.me/screensquid>here</a>. We try to help you.
       ";
       }
     echo "<br /><br />";
      
          
                        
                        
      
          }  //end GET[id]=999


///            else


 } //if(isset($_GET['id'])) {   

            

 } /// else { 

//if($connectionStatus!="error")

 if($_GET['id']==6) {
  

if(isset($_GET['actid']))

	if($_GET['actid'] == 3) ///сохранить настройки
	{
    $globalSS=doReadGlobalParamsTableToConfig($globalSS);
    foreach ($globalSS['gParams'] as $key => $value)
      doSetParam($globalSS,'Global',$key,$_POST[$key]);
		

	} //if($_GET['actid'] == 3) 
	
  $globalSS=doReadGlobalParamsTableToConfig($globalSS);
  

    echo "
      <h2>".$_lang['stCONFIG']." Screen Squid:</h2>
      <table class=datatable>
      <tr>
         <th>#</th>
         <th>".$_lang['stPARAMNAME']."</th>
         <th>".$_lang['stPARAMVALUE']."</th>
         <th>".$_lang['stCOMMENT']."</th>
      </tr>
   ";
		
	$num = 1; //
	
	echo '<form name=configphp_form action="right.php?srv='.$globalSS['connectionParams']['srv'].'&id=6&actid=3" method="post">';
  
  foreach ($globalSS['gParams'] as $key => $param) {
    $checked="";

		echo '
			<tr>
				<td>'.$num.'</td>
        <td>'.$key.'</td>';
      $checked = $param['value'] == 'on' ? 'checked' : '';
      if(($param['value'] == 'on')||($param['value'] == 'off'))
        echo	'<td><input class=toggle-button type=checkbox name="'.$key.'" '.$checked.'></td>';
      else
        echo	'<td><input type="text" name="'.$key.'" value="'.$param['value'].'"></td>';
      echo '<td>'.$param['comment'].'</td>
			</tr>
    ';
    $num++;
  } //endfor

	

	
	echo "</table>";
	echo "<br />";
	echo '<input type="submit" value="'.$_lang['stSAVE'].'"><br />';
   	echo " </form>";


                  
                  

    }  //end GET[id]=6

if($_GET['id']==8) {
	
	
   	
   	if(!isset($_GET['actid'])){
		echo "<h2>".$_lang['stADDREMOVE']." DB Screen Squid:</h2>";
   	             echo "<a href=right.php?srv=".$srv."&id=8&actid=1>".$_lang['stADD']."</a>";
              echo "<br /><br />";
              echo "<table id=report_table_id_group class=datatable>
              <tr>
                <th class=unsortable><b>#</b></th>
                <th><b>".$_lang['stDBNAME']."</b></th>
                <th class=unsortable><b>".$_lang['stCONFIGFILE']."</b></th>
                <th class=unsortable><b>".$_lang['stACTION']."</b></th>

              </tr>";

#try to get conf
$path    = 'conf/';
$files = array_diff(scandir($path), array('.', '..','.gitignore'));
$numrow=1;
			foreach($files as $file) {
			 $config= include 'conf/'.$file;

                echo "
                  <tr>
                    <td>".$numrow."</td>
                    <td align=center><a href=right.php?srv=".$srv."&id=8&actid=2&filename=".$file.">".$config['srvname']."</a></td>
                    <td align=center>".$file."</td>
                    <td align=center><a href=right.php?srv=".$srv."&id=8&actid=5&filename=".$file.">".$_lang['stDELETE']."</a></td>
                 </tr>";
                $numrow++;
              }
              echo "</table>";
              echo "<br />";
              echo "<a href=right.php?srv=".$srv."&id=8&actid=1>".$_lang['stADD']."</a>";
              echo "<br />";
   	
   	
 } // 	if(!isset($_GET['actid'])){

if(isset($_GET['actid']))
		{
			
			if($_GET['actid'] == 1) ///добавить
			{
			   echo "<h2>Add new DB Screen Squid:</h2>";
			   echo '
                  <form action="right.php?srv='.$srv.'&id=8&actid=1" method="post">
                 	<table class=datatable>
                 		<tr>
						<td>Database Type:</td>
						<td>
							<select name="srvdbtype">
								<option value="0" />MySQL (MariaDB)</option>
								<option value="1" />PostgreSQL 9</option>
							</select>
						</td>
						</tr>
					   <tr><td>Proxy name:</td> <td><input type="text" name="srvname" value="Proxy0"></td></tr>
					   <tr><td>Database name:</td> <td><input type="text" name="db" value="screensquid"></td></tr>
					   <tr><td>Username:</td> <td><input type="text" name="user" value="mysql-user"></td></tr>
					   <tr><td>Password:</td> <td><input type="text" name="pass" value="pass"></td></tr>
					   <tr><td>Database host address:</td> <td><input type="text" name="address" value="localhost"></td></tr>
					   <tr><td>Squid host:</td> <td><input type="text" name="cfgsquidhost" value="localhost"></td></tr>
					   <tr><td>Squid port:</td> <td><input type="text" name="cfgsquidport" value="3128"></td></tr>
					   <tr><td>Cachemgr password:</td> <td><input type="text" name="cfgcachemgr_passwd"></td></tr>
					   	<tr class="row2">
							<td>Enabled</td>
							<td><input type="checkbox" name="enabled" checked="checked" />
							<br/>Normally this field should be checked at all times.  Use caution when disabling this feature</td>
						</tr>
				   	
						</table>
                 <br />
                  <input type="submit" name=submit value="'.$_lang['stADD'].'"><br />
                  </form>
                  ';
			
				if(isset($_POST['submit'])){
        
        if(isset($_POST['enabled']))	$config['enabled']=1; else $config['enabled']=0;

				$config['srvname']=$_POST['srvname'];
				$config['db']=$_POST['db'];
				$config['user']=$_POST['user'];
				$config['pass']=$_POST['pass'];
				$config['address']=$_POST['address'];
				$config['cfgsquidhost']=$_POST['cfgsquidhost'];
				$config['cfgsquidport']=$_POST['cfgsquidport'];
				$config['cfgcachemgr_passwd']=$_POST['cfgcachemgr_passwd'];
				$config['srvdbtype']=$_POST['srvdbtype'];

					
				file_put_contents('conf/db'.$start.'.php', '<?php return '. var_export($config, true) . ';?>');
				
				echo '<script type="text/javascript">parent.left.location.href="mainmenu.php?srv=0";parent.right.location.href="right.php?srv=0&id=8";</script>';
				}

			} //if($_GET['actid'] == 1) 
			
			if($_GET['actid'] == 2) ///редактировать
			{
				
				$config = include("conf/".$_GET['filename']);
        
        $dbEngine = array();
        $dbEngine[0] = "MySQL (MariaDB)";
        $dbEngine[1] = "PostgreSQL 9";
        

			   echo "<h2>Edit DB Screen Squid:</h2>";
			   echo '
                  <form action="right.php?srv='.$srv.'&id=8&actid=3&filename='.$_GET['filename'].'" method="post">
                 			<table class=datatable>
                 		<tr>
						<td>Database Type:</td>
						<td>
              <select name="srvdbtype">
              ';
            $numEngine =0;
            foreach($dbEngine as $engine) {
 
              if($numEngine == $config['srvdbtype']) $selected = "selected"; else $selected="";
 
              echo  '<option value="'.$numEngine.'" '.$selected.'/>'.$engine.'</option>';
            $numEngine++;
            }
						echo '</select>
						</td>
						</tr>
					   <tr><td>Proxy name:</td> <td><input type="text" name="srvname" value="'.$config['srvname'].'"></td></tr>
					   <tr><td>Database name:</td> <td><input type="text" name="db" value="'.$config['db'].'"></td></tr>
					   <tr><td>Username:</td> <td><input type="text" name="user" value="'.$config['user'].'"></td></tr>
					   <tr><td>Password:</td> <td><input type="text" name="pass" value="'.$config['pass'].'"></td></tr>
					   <tr><td>Database host address:</td> <td><input type="text" name="address" value="'.$config['address'].'"></td></tr>
					   <tr><td>Squid host:</td> <td><input type="text" name="cfgsquidhost" value="'.$config['cfgsquidhost'].'"></td></tr>
					   <tr><td>Squid port:</td> <td><input type="text" name="cfgsquidport" value="'.$config['cfgsquidport'].'"></td></tr>
					   <tr><td>Cachemgr password:</td> <td><input type="text" name="cfgcachemgr_passwd" value="'.$config['cfgcachemgr_passwd'].'"></td></tr>
					   	<tr class="row2">
							<td>Enabled</td>
							<td><input type="checkbox" name="enabled" ';if($config['enabled']==1) echo "checked=checked"; echo ' />
							<br/>Normally this field should be checked at all times.  Use caution when disabling this feature</td>
						</tr>
				   		
						</table>
                 		
  
                 <br />
                  <input type="submit" name=submit value="'.$_lang['stSAVE'].'"><br />
                  </form>
                  ';
			
				

			} //if($_GET['actid'] == 2) 
			
			if($_GET['actid'] == 3) ///сохранить
			{
				if(isset($_POST['enabled'])) $config['enabled']=1; else $config['enabled']=0;
				
				$config['srvname']=$_POST['srvname'];
				$config['db']=$_POST['db'];
				$config['user']=$_POST['user'];
				$config['pass']=$_POST['pass'];
				$config['address']=$_POST['address'];
				$config['cfgsquidhost']=$_POST['cfgsquidhost'];
				$config['cfgsquidport']=$_POST['cfgsquidport'];
				$config['cfgcachemgr_passwd']=$_POST['cfgcachemgr_passwd'];
				$config['srvdbtype']=$_POST['srvdbtype'];

					
				file_put_contents('conf/'.$_GET['filename'], '<?php return '. var_export($config, true) . ';?>');
				
				echo '<script type="text/javascript">parent.left.location.href="mainmenu.php?srv=0";parent.right.location.href="right.php?srv=0&id=8";</script>';

				

			} //if($_GET['actid'] == 3) 
			
			if($_GET['actid'] == 5) ///удалить
			{
				if(unlink("conf/".$_GET['filename'])) echo "Delete successfully";
				
				echo '<script type="text/javascript">parent.right.location.href="right.php?srv='.$srv.'&id=8";</script>';

				

			} //if($_GET['actid'] == 5) 

		} //if(isset($_GET['actid']))


                  

    }  //end GET[id]=8

    if($_GET['id']==9) {
	
	
   	
     echo "<h2>PHPINFO:</h2>";
  phpinfo();
 
 
 
                   
 
     }  //end GET[id]=9

$end=microtime(true);

$runtime=$end - $start;

if(!isset($_GET['csv']) and $_GET['csv']!=1) {

echo "<br /><br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];


//Открыть отчёт от текущей даты или от вчера по умолчанию
if($globalSS['DefaultRepDate'])
  $newdate=strtotime(date("d-m-Y"))-86400;
else
  $newdate=strtotime(date("d-m-Y"));

$newdate=date("d-m-Y",$newdate);


?>
<form name=fastdateswitch_form>
    <input type="hidden" name=date value="<?php echo $newdate; ?>">
    <input type="hidden" name=date2 value="">
    </form>
</body>
</html>

<?php } ?>