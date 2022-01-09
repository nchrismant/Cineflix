<?php // content="text/plain; charset=utf-8"
require_once ('./jpgraph-4.3.4/src/jpgraph.php');
require_once ('./jpgraph-4.3.4/src/jpgraph_pie.php');
require_once ('./jpgraph-4.3.4/src/jpgraph_pie3d.php');
require_once "./include/util.inc.php";
if(isset($_COOKIE['Lang']) && !empty($_COOKIE['Lang'])) {
	$lang = $_COOKIE['Lang'];
	require_once "./include/$lang.inc.php";		
}
else {
	require_once "./include/fr.inc.php";	
}

$type = countserietype();
$name = $type[0];
$alea = $type[1];

// Some data
$data = array($name, $alea);

// Create the Pie Graph. 
$graph = new PieGraph(320,220);
$graph->SetScale("textlin");

$theme_class= new VividTheme;
$graph->SetTheme($theme_class);

// Set A title for the plot
$graph->title->Set($itype2);
$graph->title->SetColor('darkred');

// Create
$p1 = new PiePlot3D($data);
$graph->Add($p1);
$p1->SetSize(0.4);
$p1->SetSliceColors(array('lightskyblue','royalblue1'));
$p1->ShowBorder();
$p1->SetCenter(0.5,0.4);
$p1->SetColor('black');
$p1->ExplodeAll(8);
$p1->value->SetColor('black');
$p1->value->SetMargin(0);
$p1->SetLegends(array($iname, $ialea));
$graph->Stroke();

?>