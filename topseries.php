<?php // content="text/plain; charset=utf-8"
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

$series = counttopseries();
$serie = array_keys($series);
$top1 = $series[$serie[0]];
$top2 = $series[$serie[1]];
$top3 = $series[$serie[2]];
$top4 = $series[$serie[3]];
$top5 = $series[$serie[4]];

$datay=array($top4,$top2,$top1,$top3,$top5);

if(isset($lang) && $lang == "en") {
	for($i = 0 ; $i < 5 ; $i++) {
    	$serie[$i] = serietitleen($serie[$i]);
	}
}

// Create the graph. These two calls are always required
$graph = new Graph(1200,400,'auto');
$graph->SetScale("textlin");

$theme_class="UniversalTheme";
$graph->SetTheme(new $theme_class());

$graph->SetBox(false);
$graph->xaxis->SetFont(FF_ARIAL);
$graph->yaxis->SetFont(FF_ARIAL);

$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels(array($serie[3],$serie[1],$serie[0],$serie[2],$serie[4]));
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
$graph->title->Set($itopserie);
$graph->title->SetColor('darkred');

// Create the bar plots
$b1plot = new BarPlot($datay);

// ...and add it to the graPH
$graph->Add($b1plot);

$b1plot->SetColor("white");
$b1plot->SetFillGradient('royalblue','royalblue1', GRAD_LEFT_REFLECTION);
$b1plot->SetWidth(45);

// Display the graph
$graph->Stroke();
?>