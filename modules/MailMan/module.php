<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*                         File Name    > <!#FN> module.php </#FN>                                                      
*                         File Birth   > <!#FB> 2022/09/28 21:52:47.271 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/10/18 21:35:01.482 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/


class MailMan
{

function __construct($variables){ // 
    $this->vars = $variables;

	include_once(''.$this->vars['root_dir'].'/lib/functions/function.database.php');

	include(''.$this->vars['root_dir'].'/lang/'.$this->vars['language']);

	if (file_exists("langs/".$this->vars['language']))
		include("langs/".$this->vars['language']);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский.  	
	$this->lang = $_lang;

}

  function GetDesc()
  {
	  
      return $this->lang['stMODULEDESC']; 
  }

#печать всех сообщений
  function doPrintAll($globalSS){


	
    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
	$_lang=$this->lang;
	
	if($globalSS['connectionParams']['dbtype']==0)

	$queryAll="SELECT 
		from_unixtime(eventdate,'%d-%m-%y %k:%i:%s'),
		message,
		sentstate
	 FROM scsq_mod_mailman m
	 ORDER BY id desc;";

if($globalSS['connectionParams']['dbtype']==1)

$queryAll="SELECT 
	to_char(to_timestamp(eventdate),'DD-MM-YYYY HH24:MI:SS'),
	message,
	sentstate
 FROM scsq_mod_mailman m
 ORDER BY id desc;";



$result=doFetchQuery($globalSS, $queryAll);
$numrow=1;

echo "<a href=index.php?srv=".$this->vars['connectionParams']['srv']."&actid=1>Clean mailbox</a>";

echo "<br /><br />";
echo "<table class=datatable>

<tr>
  <th class=unsortable><b>#</b></th>
	  	<th><b>".$_lang['stDATEANDTIME']."</b></th>
 		<th><b>".$_lang['stLOGMESSAGE']."</b></th>
 		<th><b>SENTSTATE</b></th>
 </tr>";

foreach($result as $line) {

  
 echo "<tr >

		<td >".$numrow."</td>
		<td align=center >".$line[0]."</td>                 
		<td  >".$line[1]."&nbsp;</td>
		<td  >".$line[2]."&nbsp;</td>

			 
	  </tr>";
  $numrow++;
}
echo "</table>";
echo "<br />";
echo "<br />";


    
    }


#очистка всех сообщений
function doCleanAll($globalSS){


	
		include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
		
		$_lang=$this->lang;
		
	
		$queryCleanAll="delete from scsq_mod_mailman";
	
	
	
	doQuery($globalSS, $queryCleanAll);

	echo "<script language=javascript>alert('Mailbox cleaned')</script>";
	
		
		}

  function Install()
  {

	#если модуль уже есть, то вернемся.
	if(doQueryExistsModule($this->vars,'MailMan')>0) {
		echo "<script language=javascript>alert('Module already installed')</script>";
		return;
	}

# Table structure for table `scsq_mod_categorylist`

		if($this->vars['connectionParams']['dbtype']==0 ) {

		$CreateTable = "
			CREATE TABLE IF NOT EXISTS scsq_mod_mailman (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  eventdate int(11) NOT NULL,
			  message varchar(500) NOT NULL,
			  sentstate tinyint(4) DEFAULT '0',
			  PRIMARY KEY (id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";

		$UpdateParameters="INSERT INTO `scsq_modules_param` (`module`, `param`, `val`, `switch`, `comment`) VALUES
		('MailMan', 'Last Date Send log', unix_timestamp(sysdate()), 0, 'Last Date Send log')";
	
		}

		if($this->vars['connectionParams']['dbtype']==1){



		$CreateTable = "
			CREATE TABLE IF NOT EXISTS scsq_mod_mailman (
			  id serial NOT NULL,
			  eventdate integer NOT NULL,
			  message text NOT NULL,
			  sentstate integer DEFAULT 0,
			  CONSTRAINT scsq_mod_mailman_pkey PRIMARY KEY (id)
			 
			) ;
		";

		$UpdateParameters="INSERT INTO scsq_modules_param (id, module, param, val, switch, comment) VALUES
		(nextval('scsq_modules_param_id_seq')+1000,'MailMan', 'Last Date Send log', extract(epoch from current_timestamp(0)), 0, 'Last Date Send log')";

		}



		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('MailMan','modules/MailMan/index.php');";


		doQuery($this->vars, $CreateTable) or die ("Can`t create table for MailMan");


		doQuery($this->vars, $UpdateModules) or die ("Can`t update modules table");

		doQuery($this->vars, $UpdateParameters) or die ("Can`t update parameters table");


		echo "<script language=javascript>alert('".$this->lang['stINSTALLED']."')</script>";

 }
  
 function Uninstall() #добавить LANG
  {

		
		$query = "
		DROP TABLE IF EXISTS scsq_mod_mailman;
		";

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'MailMan';";

		$UpdateParameters="DELETE from scsq_modules_param  where module='MailMan'";


		doQuery($this->vars, $query) or die ("Can`t drop table for MailMan");

	
		doQuery($this->vars, $UpdateModules) or die ("Can`t update modules");

		doQuery($this->vars, $UpdateParameters) or die ("Can`t update params");


		echo "<script language=javascript>alert('".$this->lang['stUNINSTALLED']."')</script>";
		
  }


}
?>
