<?php


class ScreenSquid
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
	
		$con = @mysqli_connect($this->vars['addr'],$this->vars['usr'],$this->vars['psw'],$this->vars['dbase']);

	return $con;
 }



  function query($query)
  {
	

if(!isset($this->db_object))
	$this->GetConnectionDB();

	 $this->query_object = mysqli_query( $this->db_object, $query );

	 return $this->query_object;
   }


  
  function fetch_array($result)
  {
 
	$query_object = $this->query_object;
  
	return mysqli_fetch_row($query_object);

   }


  function free_result($result)
  {
 	@mysqli_free_result( $this->query_object );
	
	return 1;

   }

 

} //class end
?>
