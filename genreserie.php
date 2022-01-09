<?php
require_once ('./jpgraph-4.3.4/src/jpgraph.php');
require_once ('./jpgraph-4.3.4/src/jpgraph_bar.php');
require_once "./include/util.inc.php";
require_once "./include/functions.series.inc.php";
if(isset($_COOKIE['Lang']) && !empty($_COOKIE['Lang'])) {
	$lang = $_COOKIE['Lang'];
	require_once "./include/$lang.inc.php";		
}
else {
	require_once "./include/fr.inc.php";	
}

$array = array_seriesgenres();
$newarray = array_uni($array);
$genres = array_map('trim', $newarray);
$topgenres = array_count_values($genres);
arsort($topgenres);
$genre = array_keys($topgenres);
$genre1 = $topgenres[$genre[0]];
$genre2 = $topgenres[$genre[1]];
$genre3 = $topgenres[$genre[2]];
$genre4 = $topgenres[$genre[3]];
$genre5 = $topgenres[$genre[4]];

$datay=array($genre1,$genre2,$genre3,$genre4,$genre5);

if(isset($lang) && $lang == "en") {
    $genre = seriesgenreen($genre);
}

// Create the graph. These two calls are always required
$graph = new Graph(500,220,'auto');
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->Set90AndMargin(200,40,50,10);
$graph->img->SetAngle(90); 

// set major and minor tick positions manually
$graph->SetBox(false);
$graph->xaxis->SetFont(FF_ARIAL);
$graph->xaxis->SetColor('blue','black');
$graph->yaxis->SetColor('blue','black');
$graph->yaxis->SetFont(FF_ARIAL);

$graph->ygrid->SetColor('gray');
$graph->ygrid->Show(true);
$graph->ygrid->SetFill(true,'#EFEFEF@0.5','#DDDDDD@0.5');
$graph->xaxis->SetTickLabels(array($genre[0],$genre[1],$genre[2],$genre[3],$genre[4]));
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(true,false);

// Create the bar plots
$b1plot = new BarPlot($datay);

// ...and add it to the graPH
$graph->Add($b1plot);

$b1plot->SetWeight(0);
$b1plot->SetFillColor('navy');
$b1plot->SetWidth(17);
$graph->title->Set($itopseriegenre);
$graph->title->SetColor('darkred');

// Display the graph
$graph->Stroke();
?>