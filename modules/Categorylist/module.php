<?php


class Categorylist
{

function __construct($variables){ // 
    $this->vars = $variables;
}

  function GetDesc()
  {
      return 'Модуль категорий сайтов'; # TODO добавить в lang
  }

  function GetConnectionDB()
  {

	$connection=mysqli_connect($this->vars['addr'],$this->vars['usr'],$this->vars['psw'],$this->vars['dbase']);
	return $connection;
  }



  function Install()
  {


$connection = $this->GetConnectionDB();
# Table structure for table `scsq_mod_quotas`

		$CreateTable = "
			CREATE TABLE IF NOT EXISTS `scsq_mod_categorylist` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `category` varchar(100) NOT NULL,
			  `site` varchar(300) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `site` (`site`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";

		$queryAddColumn = "
		ALTER TABLE `scsq_quicktraffic` ADD `category` VARCHAR( 30 ) NULL DEFAULT NULL ;
		";



		$UpdateModules = "
		INSERT INTO `scsq_modules` (name,link) VALUES ('Categorylist','modules/Categorylist/index.php');";


		$result=mysqli_query($connection,$CreateTable,MYSQLI_USE_RESULT) or die (mysqli_error());

		mysqli_free_result($result);

		$result=mysqli_query($connection,$queryAddColumn,MYSQLI_USE_RESULT) or die (mysqli_error());

		mysqli_free_result($result);
		
		$result=mysqli_query($connection,$UpdateModules,MYSQLI_USE_RESULT) or die (mysqli_error());

		mysqli_free_result($result);
		


		echo "Установлено<br /><br />";
		echo "<a href=right.php?srv=".$srv."&id=7 target=right>".$_lang['stBACK']."</a>";
   }
  
 function Uninstall() #добавить LANG
  {

		$connection = $this->GetConnectionDB();
		$query = "
		DROP TABLE IF EXISTS `scsq_mod_categorylist`;
		";

		$queryDropColumn = "
		ALTER TABLE `scsq_quicktraffic`  DROP `category`;
		";


		$UpdateModules = "
		DELETE FROM `scsq_modules` where name = 'Categorylist';";

		$result=mysqli_query($connection,$query,MYSQLI_USE_RESULT) or die (mysqli_error());

		mysqli_free_result($result);

		$result=mysqli_query($connection,$queryDropColumn,MYSQLI_USE_RESULT) or die (mysqli_error());

		mysqli_free_result($result);

		$result=mysqli_query($connection,$UpdateModules,MYSQLI_USE_RESULT) or die (mysqli_error());

		mysqli_free_result($result);

		echo "Удалено<br /><br />";
		echo "<a href=right.php?srv=".$srv."&id=7 target=right>".$_lang['stBACK']."</a>";

  }


}
?>
