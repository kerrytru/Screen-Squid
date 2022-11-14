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
*                         File Birth   > <!#FB> 2022/09/28 21:52:47.264 </#FB>                                         *
*                         File Mod     > <!#FT> 2022/10/25 22:39:51.107 </#FT>                                         *
*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
*                                        <!#LU>  </#LU>                                                                
*                                        <!#LD> MIT License                                                            
*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
*                         File Version > <!#FV> 1.0.1 </#FV>                                                           
*                                                                                                                      *
</#CR>
*/






class Chart
{
var $DataSet;
var $graphPchart;
var $maindir;
var $chartlib;
var $link;

function __construct($variables){ // 
    $this->vars = $variables;
     include_once("pChart/pChart/pData.class");
	 include_once("pChart/pChart/pChart.class");	
	 

	
	$this->chartlib = doGetParam($this->vars,'Chart','chartlib');
	
	#clean directory for svg
	foreach (glob($this->vars['root_dir']."/pictures/*.svg") as $filename) {
	    unlink($filename);
	}
	#clean directory for png
	foreach (glob($this->vars['root_dir']."/pictures/*.png") as $filename) {
		unlink($filename);
	}

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

  function writeArraysToFile($userData) #write point arrays to file for external libs
  {
	  if($userData['charttype']=="line") {
	  #prevent bug with all zeros
	  #Добавим первый незначащий элемент. Костыль для того, чтобы сдвинуть точку на одну вперёд. Иначе рисует не очень понятно.
	  array_splice($userData['arrSerie1'],0,0,'0');
	
	}

	  #data serie
 
	$fp = fopen($this->vars['root_dir'].'/modules/Chart/data/'.$userData['chartname'].'_val.txt', 'w');
    
		fputcsv($fp, $userData['arrSerie1'],';');

	fclose($fp);

	if($userData['arrSerie2'] == "")
		$userData['arrSerie2'] = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
	 

	$fp = fopen($this->vars['root_dir'].'/modules/Chart/data/'.$userData['chartname'].'_label.txt', 'w');
		fputcsv($fp, $userData['arrSerie2'],';');
	fclose($fp);

}

  function drawImage($userData) #рисуем график
  {
	
	#прежде чем рисовать, запишем в файлы данные по графику.
	 $this->writeArraysToFile($userData);

	  //рисуем
	  
	  //default library is pChart
	  if($this->chartlib == "pChart")
		return $this->drawImagePchart($userData);
	
		
	  if($this->chartlib == "pygal")
		return $this->drawImagePygal($userData);
	

	}


  function drawImagePygal($userData) #рисуем график
  {
	  //Тут просто. В зависимости от типа графика запускаем скрипт рисования. Возвращаем результат эхом.
	  if($userData['charttype']=="line"){
			echo passthru("python ".$this->vars['root_dir']."/modules/Chart/pygal/line.py ".$userData['chartname']."");
			echo file_get_contents($this->vars['root_dir'].'/modules/Chart/pictures/'.$userData['chartname'].'.svg');
	  }

	  if($userData['charttype']=="pie"){
			echo passthru("python ".$this->vars['root_dir']."/modules/Chart/pygal/pie.py ".$userData['chartname']."");
			echo file_get_contents($this->vars['root_dir'].'/modules/Chart/pictures/'.$userData['chartname'].'.svg');
	  }


}

  function drawImagePchart($userData) #рисуем график
  {
$start=microtime(true);

/* Это пока выкинуто. Потом доделаем.
if($graphtype['trafficbyhours']==1)
{
// Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint($arrHourMb,"Serie1");

 $DataSet->AddPoint(array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23),"Serie3");
 $DataSet->AddAllSeries();
 $DataSet->RemoveSerie("Serie3");
 $DataSet->SetAbsciseLabelSerie("Serie3");
 $DataSet->SetSerieName("Traffic","Serie1");
 $DataSet->SetYAxisName("Megabytes");

 // Initialise the graph
 $graphPchart = new pChart(700,230);
 $graphPchart->drawGraphAreaGradient(132,173,131,50,TARGET_BACKGROUND);
 $graphPchart->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",8);
 $graphPchart->setGraphArea(120,20,675,190);
 $graphPchart->drawGraphArea(213,217,221,FALSE);
 $graphPchart->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALL,213,217,221,TRUE,0,2,TRUE);
 $graphPchart->drawGraphAreaGradient(163,203,167,50);
 $graphPchart->drawGrid(4,TRUE,230,230,230,20);

 // Draw the bar chart
 $graphPchart->drawStackedBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),70);

 // Draw the legend
 $graphPchart->setFontProperties("../lib/pChart/Fonts/tahoma.ttf",8);
 $graphPchart->drawLegend(610,10,$DataSet->GetDataDescription(),236,238,240,52,58,82);

 // Render the picture
 $graphPchart->addBorder(2);
}*/

//if($graphtype['trafficbyhours']==0)
//{
//pChart Graph 
 // Dataset definition 
 
 if($userData['charttype']=="line"){
 
 $DataSet = new pData;
 $DataSet->AddPoint($userData['arrSerie1'],"Serie1");
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie();
 $DataSet->SetSerieName("Traffic","Serie1");

 // Initialise the graph
 $graphPchart = new pChart(700,230);
 $graphPchart->setFontProperties($this->vars['root_dir']."/modules/Chart/pChart/Fonts/tahoma.ttf",8);
 $graphPchart->setGraphArea(50,30,585,200);
 $graphPchart->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
 $graphPchart->drawRoundedRectangle(5,5,695,225,5,230,230,230);
 $graphPchart->drawGraphArea(255,255,255,TRUE);
 $graphPchart->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
 $graphPchart->drawGrid(4,TRUE,230,230,230,50);

 // Draw the 0 line
 $graphPchart->setFontProperties($this->vars['root_dir']."/modules/Chart/pChart/Fonts/tahoma.ttf",6);
 $graphPchart->drawTreshold(0,143,55,72,TRUE,TRUE);

 // Draw the cubic curve graph
 $graphPchart->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());

 $graphPchart->drawTitle(50,22,$userData['charttitle'],50,50,50,585);

 // Finish the graph
 $graphPchart->setFontProperties($this->vars['root_dir']."/modules/Chart/pChart/Fonts/tahoma.ttf",8);
 $graphPchart->drawLegend(600,30,$DataSet->GetDataDescription(),255,255,255);
 $graphPchart->setFontProperties($this->vars['root_dir']."/modules/Chart/pChart/Fonts/tahoma.ttf",10);
//}

}

 
 if($userData['charttype']=="pie"){
 
 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint($userData['arrSerie1'],"Serie1");
 $DataSet->AddPoint($userData['arrSerie2'],"Serie2");

 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $graphPchart = new pChart(700,400);
 $graphPchart->setFontProperties($this->vars['root_dir']."/modules/Chart/pChart/Fonts/tahoma.ttf",10);
 $graphPchart->drawFilledRoundedRectangle(7,7,693,393,5,240,240,240);
 $graphPchart->drawRoundedRectangle(5,5,695,395,5,230,230,230);

 // Draw the pie chart
 $graphPchart->AntialiasQuality = 0;
 $graphPchart->setShadowProperties(2,2,200,200,200);
 $graphPchart->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),220,200,120,PIE_PERCENTAGE,8);
 $graphPchart->clearShadow();

 $graphPchart->drawTitle(50,22,$userData['charttitle'],50,50,50,585);

 $graphPchart->drawPieLegend(430,45,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

}



  $graphPchart->Render($this->vars['root_dir']."/modules/Chart/pictures/".$userData['chartname']."".$start.".png");
    
  return "<img id=\"".$userData['chartname']."\" src='".$this->vars['root_http']."/modules/Chart/pictures/".$userData['chartname']."".$start.".png' alt='Image'>";
}


 

  function Install()
  	{
	echo "<script language=javascript>alert('This is system module, its already installed')</script>";

	 }
  
 function Uninstall() 
  {
	echo "<script language=javascript>alert('This is system module, you cant uninstall it')</script>";

  }


}
?>
