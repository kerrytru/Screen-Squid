<?php

#Build date Thursday 7th of May 2020 18:21:28 PM
#Build revision 1.1

class p_ScreenSquid
{

var $db_object = null;
var $query_object = false;


function __construct($variables){ // 
    $this->vars = $variables;

    $this->db_object = $this->GetConnectionDB();
}

  function GetDesc()
  {
      return 'screen squid'; # TODO добавить в lang
  }

  function GetConnectionDB()
  {

	$conn_string = "host=".$this->vars['addr']." port=5432 dbname=".$this->vars['dbase']." user=".$this->vars['usr']." password=".$this->vars['psw']."";
	$con = @pg_connect($conn_string);

 	return $con;

  }

  function query($query)
  {

if(!isset($this->db_object))
	$this->GetConnectionDB();

 	return pg_query($query);

   }


  
  function fetch_array($result)
  {
	
	return pg_fetch_array($result,NULL,PGSQL_NUM);

   }


  function free_result($result)
  {

	pg_free_result($result);
	
	return 1;

   }

 

} //class end
?>
