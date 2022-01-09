<?php
require_once "./include/util.inc.php";
if(isset($_COOKIE['Lang']) && !empty($_COOKIE['Lang'])) {
	$lang = $_COOKIE['Lang'];
	require_once "./include/$lang.inc.php";		
}
else {
	require_once "./include/fr.inc.php";	
}

$type = countmovietype();
$name = $type[0];
$id = $type[1];
$alea = $type[2];

$recherche = array($name, $id, $alea);
//On calcule le nombre total des types de recherches.
$nbrRecherche = array_sum($recherche);
//On calcule l'angle de départ et l'angle de fin pour les éléments
$parciel[0] = 0;
for ($i = 0; $i <= 2 ; $i++) {
    $angle[$i] = ($recherche[$i]/$nbrRecherche)*360;
    $parciel[$i+1] = array_sum($angle);
}

$font_file = realpath('./fonts/calibri.ttf');
$largeurImage = 320;
$hauteurImage = 220;
//On crée l'image du départ
$img  = imagecreatetruecolor($largeurImage, $hauteurImage) or die ("Erreur lors de la création de l'image");
$blanc = imagecolorallocate($img, 255, 255, 255);
$noir = imagecolorallocate($img, 0, 0, 0);
$darkred = imagecolorallocate($img, 139, 0, 0);
imagefill($img, 0, 0, $blanc);
//On crée les couleurs pour les éléments 
$elementcolor[0] = imagecolorallocate($img, 135, 206, 250);
$elementcolor[1] = imagecolorallocate($img, 72, 118, 255);
$elementcolor[2] = imagecolorallocate($img, 0, 0, 139);
//on créer les couleurs pour l'effet 3D
$delementcolor[0] = imagecolorallocate($img, 85, 156, 200);
$delementcolor[1] = imagecolorallocate($img, 22, 68, 205);
$delementcolor[2] = imagecolorallocate($img, 0, 0, 89);
//on crée l'effet 3D
for ($i = 0; $i <= 2 ; $i++) {
    for ($n = 110; $n > 95; $n--) {                                   
        imagefilledarc($img, 160, $n, 210, 110, $parciel[$i],
        $parciel[$i + 1], $delementcolor[$i], IMG_ARC_PIE);
    }
}
//On crée le diagramme des types de recherches
for ($i = 0; $i <= 2 ; $i++) { 
    imagefilledarc($img, 160, 95, 210, 110, $parciel[$i],
    $parciel[$i + 1], $elementcolor[$i], IMG_ARC_PIE);
}
imagefttext($img, 11, 0, 74, 20, $darkred, $font_file, $itype);
imagefilledrectangle($img, 80, 190, 115, 206, $elementcolor[0]);
imagefttext($img, 8, 0, 79, 187, $noir, $font_file, $iname);
imagefilledrectangle($img, 145, 190, 180, 206, $elementcolor[1]);
imagefttext($img, 8, 0, 144, 187, $noir, $font_file, $iid);
imagefilledrectangle($img, 210, 190, 245, 206, $elementcolor[2]);
imagefttext($img, 8, 0, 209, 187, $noir, $font_file, $ialea);
//On affiche l'image
header('Content-type: image/png');
imagepng($img);
imagedestroy($img);
?>