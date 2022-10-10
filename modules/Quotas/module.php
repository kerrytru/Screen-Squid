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
*                         File Birth   > <!#FB> 2022/10/10 21:13:58.662 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/10/10 22:00:22.569 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.0 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






#TODO


class Quotas
{

function __construct($variables){ // 
    $this->vars = $variables;
    	
  
	include_once(''.$this->vars['root_dir'].'/lib/functions/function.database.php');
	include_once(''.$this->vars['root_dir'].'/lib/functions/function.reportmisc.php');

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


 function GetAliasValue($aliasid) #по алиасу возвращаем элемент из таблицы логинов/ip адресов
  {



$queryAlias = "
			SELECT 
			tmp.tablename

			FROM ((SELECT 
			scsq_logins.name as tablename 
			FROM scsq_alias 
			LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id
			WHERE scsq_alias.typeid=0 and scsq_alias.id=".$aliasid.") 

			UNION 
			
			(SELECT 
			scsq_ipaddress.name as tablename 
			FROM scsq_alias 
			LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 
			WHERE scsq_alias.typeid=1 and scsq_alias.id=".$aliasid.")) as tmp
			GROUP BY tmp.tablename";


	$row=doFetchOneQuery($this->vars, $queryAlias) or die ('Error: Cant get login/ipaddress for tableid');


    return $row[0];

    
}

#Функционал работы с квотами
function doPrintAll($globalSS){

	
    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
	$_lang=$this->lang;
	
	$queryAll="SELECT 
	sm.id,
	sm.aliasid,
	sa.name,
	sumday,
	summonth,
	quotaday,
	quotamonth,
	quota,
	status,
	sm.active,
	sa.id
	 FROM scsq_mod_quotas sm, scsq_alias sa
	 WHERE sm.aliasid = sa.id
	 ORDER BY aliasid asc;";


$result=doFetchQuery($globalSS, $queryAll);
$numrow=1;
  echo "<a href=index.php?srv=".$this->vars['connectionParams']['srv'].">".$_lang['stREFRESH']."</a>";
echo "<br /><br />";
echo "<table class=datatable>

<tr>
  <th class=unsortable><b>#</b></th>
	  	<th><b>".$_lang['stQUOTASSTATUS']."</b></th>
 		<th><b>".$_lang['stALIAS']."</b></th>
	  	<th><b>".$_lang['stVALUE']."</b></th>
	 	<th class=unsortable><b>".$_lang['stQUOTASCURRENT']."</b></th>
		<th class=unsortable><b>".$_lang['stQUOTASDAY']."</b></th>
		<th class=unsortable><b>".$_lang['stQUOTASTRAFFICDAY']."</b></th>
		<th><b>".$_lang['stQUOTASACTIVE']."</b></th>
		<th><b>Action</b></th>
</tr>";

foreach($result as $line) {

#раскрасим строки в зависимости от статуса по квотам
if($line[8] == 0) $alarmclass=""; #нет превышения
if($line[8] == 1) $alarmclass="class=quotaAlm1"; #дневная превышена
if($line[8] == 2) $alarmclass="class=quotaAlm2"; #месячная превышена

  
 echo "<tr >

		<td $alarmclass>".$numrow."</td>
		<td align=center $alarmclass>".$line[8]."</td>                 
		<td align=center $alarmclass><a href=index.php?srv=".$this->vars['connectionParams']['srv']."&actid=3&quotaid=".$line[0].">".$line[2]."&nbsp;</a></td>
		<td $alarmclass>".$this->GetAliasValue($line[10])."</td>
		<td align=center $alarmclass>".$line[7]."&nbsp;</td>
		<td align=center $alarmclass>".$line[5]."&nbsp;</td>
		<td align=center $alarmclass>".$line[3]."</td>
		<td align=center $alarmclass>".$line[9]."</td>  
		<td align=center $alarmclass><a href=index.php?srv=".$this->vars['connectionParams']['srv']."&actid=5&quotaid=".$line[0].">Delete&nbsp;</a></td>

			 
	  </tr>";
  $numrow++;
}
echo "</table>";
echo "<br />";
echo "<a href=index.php?srv=".$this->vars['connectionParams']['srv']."&actid=1>".$_lang['stQUOTASADDQUOTA']."</a>";
echo "<br />";


    
    }


function doPrintFormAdd($globalSS){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
	
	$_lang=$this->lang;

	if($this->vars['connectionParams']['dbtype']==0)
	$str = "group_concat(scsq_groups.name order by scsq_groups.name asc) as gconcat,
		group_concat(scsq_groups.id order by scsq_groups.name asc)";
	
	if($this->vars['connectionParams']['dbtype']==1)
	$str = "string_agg(scsq_groups.name, ',' order by scsq_groups.name asc) as gconcat,
		string_agg(CAST(scsq_groups.id as text), ',' order by scsq_groups.name asc)";
		
		
				   $queryAllAliases="
		   SELECT 
			 tmp.alname,
			 tmp.altypeid,
			 tmp.altableid,
			 tmp.alid,
			 tmp.tablename,
			 ".$str."
		  FROM ((SELECT 
			scsq_alias.name as alname,
			scsq_alias.typeid as altypeid,
			scsq_alias.tableid as altableid,
			scsq_alias.id as alid,
			scsq_logins.name as tablename 
			   FROM scsq_alias 
			   LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id 
			   WHERE scsq_alias.typeid=0) 
	
			   UNION 
				
				(SELECT 
			   scsq_alias.name as alname,
			   scsq_alias.typeid as altypeid,
			   scsq_alias.tableid as altableid,
			   scsq_alias.id as alid,
			   scsq_ipaddress.name as tablename 
			 FROM scsq_alias 
			 LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 
			 WHERE scsq_alias.typeid=1)) as tmp
		  
		  LEFT JOIN scsq_aliasingroups ON scsq_aliasingroups.aliasid=tmp.alid
		  LEFT JOIN scsq_groups ON scsq_aliasingroups.groupid=scsq_groups.id
		  GROUP BY tmp.altableid,tmp.alname,tmp.altypeid,tmp.alid,tmp.tablename
		  ORDER BY alname asc";
		  
		  
			$queryAllAliasesHaveNotQuota="
			 SELECT 
			 tmp.alname,
			 tmp.altypeid,
			 tmp.altableid,
			 tmp.alid,
			 tmp.tablename,
			 ".$str."
		  FROM ((SELECT 
			scsq_alias.name as alname,
			scsq_alias.typeid as altypeid,
			scsq_alias.tableid as altableid,
			scsq_alias.id as alid,
			scsq_logins.name as tablename 
			   FROM scsq_alias 
			   LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id 
			   WHERE scsq_alias.typeid=0) 
	
			   UNION 
				
				(SELECT 
			   scsq_alias.name as alname,
			   scsq_alias.typeid as altypeid,
			   scsq_alias.tableid as altableid,
			   scsq_alias.id as alid,
			   scsq_ipaddress.name as tablename 
			 FROM scsq_alias 
			 LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 
			 WHERE scsq_alias.typeid=1)) as tmp
		  
		  LEFT JOIN scsq_aliasingroups ON scsq_aliasingroups.aliasid=tmp.alid
		  LEFT JOIN scsq_groups ON scsq_aliasingroups.groupid=scsq_groups.id
		  
		  WHERE alid NOT IN (select aliasid from scsq_mod_quotas)
		  GROUP BY tmp.altableid,tmp.alname,tmp.altypeid,tmp.alid,tmp.tablename
		  ORDER BY alname asc";


	echo $_lang['stQUOTASFORMADDQUOTA'];
	echo '
	  <form action="index.php?srv='.$this->vars['connectionParams']['srv'].'&actid=2" method="post">
	  '.$_lang['stQUOTASDAY'].': <input type="text" value="0" name="quotaday" ><br />
	  <input type="hidden" name=sumday value="0">
	  <input type="hidden" name=summonth value="0">
	  '.$_lang['stQUOTASACTIVE'].': <input type="checkbox" name="active"><br /> 
	  '.$_lang['stQUOTASEXCLUDE'].': <input type="checkbox" onClick="switchTables();" name="typeid"><br /><br />
	  '.$_lang['stVALUE'].':<br />';


	  $result=doFetchQuery($this->vars,$queryAllAliases) or die ('Error: Cant select aliases from scsq_alias table');
$numrow=1;

	echo "<table id='loginsTable' class=datatable style='display:table;'>";
	echo "<tr>
  <th class=unsortable>#</th>
  <th>".$_lang['stALIAS']."</th>
  <th class=unsortable>".$_lang['stINCLUDE']."</th>
  </tr>";

	foreach($result as $line) {
	  echo "
		<tr>
		  <td >".$numrow."</td>
		  <td >".$line[0]."</td>
		  <td><input type='radio' name='aliasid' value='".$line[3]."';</td>
		</tr>";
	  $numrow++;
	}

	echo "</table>";

$result=doFetchQuery($this->vars, $queryAllAliasesHaveNotQuota);       
$numrow=1;

	echo "<table id='ipaddressTable' class=datatable style='display:none;'>";
	echo "<tr>
  <th class=unsortable>#</th>
  <th>".$_lang['stALIAS']."</th>
  <th class=unsortable>".$_lang['stINCLUDE']."</th>
  </tr>";

	foreach($result as $line) {
	  echo "
		<tr>
		  <td >".$numrow."</td>
		  <td >".$line[0]."</td>
		  <td><input type='radio' name='aliasid' value='".$line[3]."';</td>
		</tr>";
	  $numrow++;
	}

	echo "</table>";



	 echo '
	  <input type="submit" value="'.$_lang['stADD'].'"><br />
	  </form>
	  <br />';


    }

function doAdd($globalSS,$params){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
    $_lang = $this->lang;


	$aliasid=$params['aliasid'];
	$quotaday=$params['quotaday'];
	$quotamonth=$params['quotamonth'];
	$active=$params['active'];


			$sql="INSERT INTO scsq_mod_quotas (aliasid, quota, quotaday, quotamonth,active) VALUES ($aliasid, $quotaday,$quotaday,$quotamonth,$active)";

			if (!doQuery($this->vars, $sql)) {
			  die('Error: Cant insert into scsq_mod_quotas table' );
			}
  }

  function doEdit($globalSS,$params){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
    $_lang = $this->lang;

	if($this->vars['connectionParams']['dbtype']==0)
	$str = "group_concat(scsq_groups.name order by scsq_groups.name asc) as gconcat,
		group_concat(scsq_groups.id order by scsq_groups.name asc)";
	
	if($this->vars['connectionParams']['dbtype']==1)
	$str = "string_agg(scsq_groups.name, ',' order by scsq_groups.name asc) as gconcat,
		string_agg(CAST(scsq_groups.id as text), ',' order by scsq_groups.name asc)";
		
		
				   $queryAllAliases="
		   SELECT 
			 tmp.alname,
			 tmp.altypeid,
			 tmp.altableid,
			 tmp.alid,
			 tmp.tablename,
			 ".$str."
		  FROM ((SELECT 
			scsq_alias.name as alname,
			scsq_alias.typeid as altypeid,
			scsq_alias.tableid as altableid,
			scsq_alias.id as alid,
			scsq_logins.name as tablename 
			   FROM scsq_alias 
			   LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id 
			   WHERE scsq_alias.typeid=0) 
	
			   UNION 
				
				(SELECT 
			   scsq_alias.name as alname,
			   scsq_alias.typeid as altypeid,
			   scsq_alias.tableid as altableid,
			   scsq_alias.id as alid,
			   scsq_ipaddress.name as tablename 
			 FROM scsq_alias 
			 LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 
			 WHERE scsq_alias.typeid=1)) as tmp
		  
		  LEFT JOIN scsq_aliasingroups ON scsq_aliasingroups.aliasid=tmp.alid
		  LEFT JOIN scsq_groups ON scsq_aliasingroups.groupid=scsq_groups.id
		  GROUP BY tmp.altableid,tmp.alname,tmp.altypeid,tmp.alid,tmp.tablename
		  ORDER BY alname asc";
		  
		  
			$queryAllAliasesHaveNotQuota="
			 SELECT 
			 tmp.alname,
			 tmp.altypeid,
			 tmp.altableid,
			 tmp.alid,
			 tmp.tablename,
			 ".$str."
		  FROM ((SELECT 
			scsq_alias.name as alname,
			scsq_alias.typeid as altypeid,
			scsq_alias.tableid as altableid,
			scsq_alias.id as alid,
			scsq_logins.name as tablename 
			   FROM scsq_alias 
			   LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id 
			   WHERE scsq_alias.typeid=0) 
	
			   UNION 
				
				(SELECT 
			   scsq_alias.name as alname,
			   scsq_alias.typeid as altypeid,
			   scsq_alias.tableid as altableid,
			   scsq_alias.id as alid,
			   scsq_ipaddress.name as tablename 
			 FROM scsq_alias 
			 LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 
			 WHERE scsq_alias.typeid=1)) as tmp
		  
		  LEFT JOIN scsq_aliasingroups ON scsq_aliasingroups.aliasid=tmp.alid
		  LEFT JOIN scsq_groups ON scsq_aliasingroups.groupid=scsq_groups.id
		  
		  WHERE alid NOT IN (select aliasid from scsq_mod_quotas)
		  GROUP BY tmp.altableid,tmp.alname,tmp.altypeid,tmp.alid,tmp.tablename
		  ORDER BY alname asc";



	$quotaid=$params['quotaid'];

    $queryOneQuota="select aliasid,quota,quotaday,quotamonth,sm.active,sa.name,sm.id,sumday,summonth from scsq_mod_quotas sm, scsq_alias sa where sm.id='".$quotaid."' and sm.aliasid=sa.id;";

	$line=doFetchOneQuery($this->vars, $queryOneQuota) or die ('Error: Cant select one quota from scsq_mod_quotas');
             

	if($line[4]==1)  $isChecked="checked"; else  $isChecked="";


	echo '
	  <form action="index.php?srv='.$this->vars['connectionParams']['srv'].'&actid=4&quotaid='.$quotaid.'" method="post">
 	 '	.$_lang['stALIAS'].': '.$line[5].'<br /><br />
	 '.$_lang['stQUOTASCURRENT'].': <input type="text" name="quota" value="'.$line[1].'"><br />
	 '.$_lang['stQUOTASDAY'].': <input type="text" name="quotaday" value="'.$line[2].'"><br />
 	<input type="hidden" name=sumday value="'.$line[7].'">
 	<input type="hidden" name=summonth value="'.$line[8].'">
	 '.$_lang['stQUOTASACTIVE'].': <input type="checkbox" '.$isChecked.' name="active"><br /> 

	'.$_lang['stQUOTASEXCLUDE'].': <input type="checkbox" onClick="switchTables();" name="typeid"><br /><br />
	 '.$_lang['stVALUE'].':<br />';

	  $checkedAliasid = $line[0];

	  $result=doFetchQuery($this->vars, $queryAllAliases) or die('Error: Cant select all aliases from scsq_alias');
	  $numrow=1;

	echo "<table id='loginsTable' class=datatable style='display:table;'>";
	echo "<tr>
  <th class=unsortable>#</th>
  <th>".$_lang['stALIAS']."</th>
  <th class=unsortable>".$_lang['stINCLUDE']."</th>
  </tr>";

	foreach($result as $line) {
	  echo "
		<tr>
		  <td >".$numrow."</td>
		  <td >".$line[0]."</td>";
if($line[3]==$checkedAliasid)
echo        "<td><input type='radio' name='aliasid' checked value='".$line[3]."';</td>";
else
echo         "<td><input type='radio' name='aliasid' value='".$line[3]."';</td>";
echo         "</tr>";
	  $numrow++;
	}

	echo "</table>";

$result=doFetchQuery($this->vars, $queryAllAliasesHaveNotQuota);       
$numrow=1;

	echo "<table id='ipaddressTable' class=datatable style='display:none;'>";
	echo "<tr>
  <th class=unsortable>#</th>
  <th>".$_lang['stALIAS']."</th>
  <th class=unsortable>".$_lang['stINCLUDE']."</th>
  </tr>";

	foreach($result as $line) {
	  echo "
		<tr>
		  <td >".$numrow."</td>
		  <td >".$line[0]."</td>
		  <td><input type='radio' name='aliasid' value='".$line[3]."';</td>
		</tr>";
	  $numrow++;
	}

	echo "</table>";

	 echo '
	   <input type="submit" value="'.$_lang['stSAVE'].'"><br />
	   </form>
	   <form action="index.php?srv='.$this->vars['connectionParams']['srv'].'&actid=5&quotaid='.$quotaid.'" method="post">
	   <input type="submit" value="'.$_lang['stDELETE'].'"><br />
	   </form>
	   <br />';
  }

  function doSave($globalSS,$params){

    include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
      
    $_lang = $this->lang;
  
	$aliasid=$params['aliasid'];
	$quotaday=$params['quotaday'];
	$quotamonth=$params['quotamonth'];
	$active=$params['active'];
	$quotaid=$params['quotaid'];
	$datestart=$params['datestart'];
	$quota=$params['quota'];
	
	$queryUpdateOneQuota="update scsq_mod_quotas set aliasid=".$aliasid.",quota=".$quota.",quotaday=".$quotaday.",quotamonth=".$quotamonth.",active=".$active.", datemodified=".$datestart." where id=".$quotaid."";


	if (!doQuery($this->vars, $queryUpdateOneQuota)) {
		die('Error: Can`t update one quota');
	  }

}

function doDelete($globalSS,$params){

  include_once(''.$globalSS['root_dir'].'/lib/functions/function.database.php');
    
  $_lang = $this->lang;

  $aliasid=$params['aliasid'];
  $quotaday=$params['quotaday'];
  $quotamonth=$params['quotamonth'];
  $active=$params['active'];
  $quotaid=$params['quotaid'];
  $datestart=$params['datestart'];

  $queryDeleteOneQuota="delete from scsq_mod_quotas where id=".$quotaid.";";

  
   if (!doQuery($this->vars, $queryDeleteOneQuota)) {
	die('Error: Cant DELETE one quota from scsq_mod_quotas ');
  }

}


  function Install()
  {

	#если модуль уже есть, то вернемся.
	if(doQueryExistsModule($this->vars,'Quotas')>0) {
		echo "<script language=javascript>alert('Module already installed')</script>";
		return;
	}

# Table structure for table `scsq_mod_quotas`

		if($this->vars['connectionParams']['dbtype']==0) #mysql version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_quotas (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  aliasid int(11) NOT NULL,
			  quota int(11) DEFAULT '0',
			  status int(4) DEFAULT '0',
			  active int(10) DEFAULT '0',
			  quotaday int(11) DEFAULT '0',
			  quotamonth int(11) DEFAULT '0',
			  sumday int(11) DEFAULT '0',
			  summonth int(11) DEFAULT '0',
			  datemodified int(11) DEFAULT NULL,
			  datecalc int(11) DEFAULT NULL,
			  PRIMARY KEY (id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
		
		if($this->vars['connectionParams']['dbtype']==1) #postgre version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_quotas (
			  id serial NOT NULL,
			  aliasid integer NOT NULL,
			  quota integer DEFAULT 0,
			  status integer DEFAULT 0,
			  active integer DEFAULT 0,
			  quotaday integer DEFAULT 0,
			  quotamonth integer DEFAULT 0,
			  sumday integer DEFAULT 0,
			  summonth integer DEFAULT 0,
			  datemodified integer DEFAULT NULL,
			  datecalc integer DEFAULT NULL,
			  CONSTRAINT scsq_mod_quotas_pkey PRIMARY KEY (id)
			);

		";


		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('Quotas','modules/Quotas/index.php');";


		doQuery($this->vars, $CreateTable) or die ("Can`t install module!");
		
		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");

		echo "<script language=javascript>alert('".$this->lang['stINSTALLED']."')</script>";
	 }
  
 function Uninstall() #добавить LANG
  {

		$query = "
		DROP TABLE IF EXISTS scsq_mod_quotas;
		";

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'Quotas';";

		doQuery($this->vars, $query) or die ("Can`t uninstall module!");

		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");

		echo "<script language=javascript>alert('".$this->lang['stUNINSTALLED']."')</script>";

  }


}
?>
