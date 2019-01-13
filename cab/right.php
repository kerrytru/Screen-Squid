<?php
#build 20170501

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
* {padding:0;margin:0;}
ul {list-style-type:none;padding-left:1em}
body {margin:0.5em;padding:0.5em}

</style>
<link rel="stylesheet" type="text/css" href="../javascript/example.css"/>
</head>
<body>
<br />


<br />
<script type="text/javascript" src="../javascript/sortable.js"></script>
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

function PartlyReportsLogin(idReport, dom, login,loginname,site)
{
parent.right.location.href='reports/reports.php?srv=<?php echo $srv ?>&id='+idReport+'&date='+window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+dom+'&login='+login+'&loginname='+loginname+'&site='+site;
}

function PartlyReportsIpaddress(idReport, dom, ip,ipname,site)
{
parent.right.location.href='reports/reports.php?srv=<?php echo $srv ?>&id='+idReport+'&date='+window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+dom+'&ip='+ip+'&ipname='+ipname+'&site='+site;
}


</script>


<?php

include("../config.php");

function generateCode($length=6) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

    $code = "";

    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {

            $code .= $chars[mt_rand(0,$clen)];  
    }

    return $code;

}

$address=$address[$srv];
$user=$user[$srv];
$pass=$pass[$srv];
$db=$db[$srv];


$start=microtime(true);

///$connectionStatus=mysqli_connect($address,$user,$pass,$db) or die(mysqli_connect_error());
$connection=mysqli_connect("$address","$user","$pass","$db");

///mysqli_select_db($db);

///echo $connection;



$logged= $_GET['logged'];

///registration

//if($ii==1){
 
if(isset($_POST['submit']))
{
    $err[]="";

    // проверям логин
    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
    {
 //       $err[] = "Логин может состоять только из букв английского алфавита и цифр";
   }

    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    {
 //       $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    // проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($connection, "SELECT id FROM scsq_users WHERE login='".mysqli_real_escape_string($connection, $_POST['login'])."'");
   if(mysqli_num_rows($query) > 0)
   {
//        $err[] = "Пользователь с таким логином уже существует в базе данных";
   }

    // Если нет ошибок, то добавляем в БД нового пользователя


      $login = $_POST['login'];

        // Убираем лишние пробелы и делаем двойное шифрование
        $password = md5(md5(trim($_POST['password'])));

        mysqli_query($connection,"INSERT INTO scsq_users SET login='".$login."', password='".$password."'");
       header("Location: right.php?logged=1"); exit();
  
}  //end GET[id]=1






echo '
<form method="POST">
Логин <input name="login" type="text"><br>
Пароль <input name="password" type="password"><br>
<input name="submit" type="submit" value="Зарегистрироваться">
</form>

      ';
//}


////registration end




if (isset($_COOKIE['logged']))
{

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $query = "SELECT * FROM scsq_users WHERE id = '".intval($_COOKIE['id'])."' LIMIT 1";

$result=mysqli_query($connection,$query, MYSQLI_USE_RESULT);
  $row=mysqli_fetch_assoc($result);


    if(($row['hash'] !== $_COOKIE['hash']) or ($row['id'] !== $_COOKIE['id']))
    {
        setcookie("id", "", 0, "/");
        setcookie("hash", "", 0, "/");
      // something going wrong
    }
    else
    {
print "Вы прошли авторизацию";
    }
}///if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))


}///if (isset($_COOKIE['logged']))

else
{
	echo '
	<form method="POST">

	Логин <input name="login" type="text"><br>

	Пароль <input name="password" type="password"><br>

	<input name="submit" type="submit" value="Войти">

	</form>
	';

if(isset($_POST['submit']))

{

    # Вытаскиваем из БД запись, у которой логин равняеться введенному


    $query = "SELECT id, password FROM scsq_users WHERE login='".mysql_real_escape_string($_POST['login'])."' LIMIT 1";

$result=mysqli_query($connection,$query, MYSQLI_USE_RESULT);
  $row=mysqli_fetch_assoc($result);



    # Соавниваем пароли

    if($row['password'] == md5(md5($_POST['password'])))

    {


        # Генерируем случайное число и шифруем его

        $hash = md5(generateCode(10));

       //    echo $hash; 

      

        # Записываем в БД новый хеш авторизации и IP
mysqli_free_result($result);
    $query = "UPDATE scsq_users SET hash='".$hash."' WHERE id=".$row['id'].";";
//echo $query;

$result=mysqli_query($connection,$query);

        

        # Ставим куки

        setcookie("id", $row['id'], 0);

        setcookie("hash", $hash, 0);

        setcookie("logged", 1, 0);  

        # Переадресовываем браузер на страницу проверки нашего скрипта

         header("Location: right.php"); exit();

    }

    else

    {

        print "Вы ввели неправильный логин/пароль";

    }

}




}


///login end
	

   
$end=microtime(true);

$runtime=$end - $start;

echo "<br /><br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];

$newdate=strtotime(date("d-m-Y"))-86400;
$newdate=date("d-m-Y",$newdate);

  mysqli_free_result($result);
  mysqli_close($link);

?>
<form name=fastdateswitch_form>
    <input type="hidden" name=date_field_hidden value="<?php echo $newdate; ?>">
    <input type="hidden" name=dom_field_hidden value="<?php echo 'day'; ?>">
    <input type="hidden" name=group_field_hidden value="<?php echo $currentgroupid; ?>">
    <input type="hidden" name=groupname_field_hidden value="<?php echo $currentgroup; ?>">
    <input type="hidden" name=typeid_field_hidden value="<?php echo $typeid; ?>">
    </form>
</body>
</html>
