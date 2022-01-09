<?php
require_once ('./jpgraph-4.3.4/src/jpgraph.php');
require_once ('./jpgraph-4.3.4/src/jpgraph_bar.php');
require_once "./include/util.inc.php";
require_once "./include/functions.inc.php";
if(isset($_COOKIE['Lang']) && !empty($_COOKIE['Lang'])) {
	$lang = $_COOKIE['Lang'];
	require_once "./include/$lang.inc.php";		
}
else {
	require_once "./include/fr.inc.php";	
}

$films = counttopmovies();
$film = array_keys($films);
$top1 = $films[$film[0]];
$top2 = $films[$film[1]];
$top3 = $films[$film[2]];
$top4 = $films[$film[3]];
$top5 = $films[$film[4]];

$datay=array($top4,$top2,$top1,$top3,$top5);

if(isset($lang) && $lang == "en") {
	for($i = 0 ; $i < 5 ; $i++) {
    	$film[$i] = titleen($film[$i]);
	}
}

// Création du graphe
$graph = new Graph(1200,400,'auto');
$graph->SetScale("textlin");

$theme_class="UniversalTheme";
$graph->SetTheme(new $theme_class());

$graph->SetBox(false);
$graph->xaxis->SetFont(FF_ARIAL);
$graph->yaxis->SetFont(FF_ARIAL);

$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels(array($film[3],$film[1],$film[0],$film[2],$film[4]));
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
$graph->title->Set($itopfilm);
$graph->title->SetColor('darkred');

// Création du graphe en bar
$b1plot = new BarPlot($datay);

$graph->Add($b1plot);

$b1plot->SetColor("white");
$b1plot->SetFillGradient('royalblue','royalblue1', GRAD_LEFT_REFLECTION);
$b1plot->SetWidth(45);

// Display the graph
$graph->Stroke();
?>