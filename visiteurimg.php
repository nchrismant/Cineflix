<?php
require_once('./include/util.inc.php');
if(isset($_COOKIE['Lang']) && !empty($_COOKIE['Lang'])) {
	$lang = $_COOKIE['Lang'];
	require_once "./include/$lang.inc.php";		
}
else {
	require_once "./include/fr.inc.php";	
}

$x=320;
$y=220;
$marge=50;
$intX=($x-(2*$marge))/7;
$intY=($y-(2*$marge))/4;
for($i = -6 ; $i <= 0; $i++) {
	$datemk = mktime(0, 0, 0, (int) date('m'), (int) date('d') + ($i), (int) date('Y') );
	$dates = date('d-m-Y', $datemk);
    if(isset($lang) && $lang == "en") {
		$datefr = date('m-d', $datemk);
	}
	else {
		$datefr = date('d/m', $datemk);
	}
	$date[$i] = $dates;
	$date2[$i] = $datefr;
	$nbvisite[$i] = nombre_visitesjour($date[$i]);
}
$visites = array($nbvisite[-6], $nbvisite[-5], $nbvisite[-4], $nbvisite[-3], $nbvisite[-2], $nbvisite[-1], $nbvisite[0]);
$jours = array($date2[-6], $date2[-5], $date2[-4], $date2[-3], $date2[-2], $date2[-1], $date2[0]);

$img = imagecreatetruecolor($x, $y) or die("Erreur lors de la création de l'image");
$font_file = realpath('./fonts/LiberationSerif-Regular.ttf');
$noir = imagecolorallocate($img, 0, 0, 0);
$blanc = imagecolorallocate($img, 255, 255, 255);
$gris = imagecolorallocate($img, 220, 220, 220);
$bleu = imagecolorallocate($img, 0,  0, 255);
$navy = imagecolorallocate($img, 0, 0, 128);
imagefill($img, 0, 0, $blanc);
imageline($img, $marge, $y-$marge, $x-$marge, $y-$marge, $bleu);
imageline($img, $marge, $y-$marge, $marge, $marge, $bleu);
imagettftext($img, 19, 0, $x-$marge-5, $y-$marge+30, $noir, $font_file, $idays);
imagettftext($img, 19, 0, $marge-45, $marge-25, $noir, $font_file, $ivisit);
for($i = 0; $i <= 4; $i++) {
    imageline($img, $marge-2, $y-$marge-($i*$intY), $marge+2, $y-$marge-($i*$intY), $bleu);
    imagettftext($img, 10, 0, $marge-42 ,$y-$marge-($i*$intY), $noir, $font_file, $i*100);
   if($i > 0) {
      imageline($img, $marge+2, $y-$marge-($i*$intY), $x-$marge, $y-$marge-($i*$intY), $gris);
   }
}
for($i = 0; $i < 7; $i++) {
    imageline($img, $marge+$i*$intX, $y-$marge-2, $marge+$i*$intX, $y-$marge+2, $bleu);
    imagettftext($img, 10, -45, $marge+$i*$intX, $y-$marge+20, $noir, $font_file, $jours[$i]);
    imagesetthickness($img, 1);
    if($i < 6) {
        imageline($img, $marge+$i*$intX+1, $y-$marge-($visites[$i]*($y-2*$marge)/400), $marge+($i+1)*$intX+1, $y-$marge-($visites[$i+1]*($y-2*$marge)/400), $navy);
    }
    imagefilledellipse($img, $marge+$i*$intX+1, $y-$marge-($visites[$i]*($y-2*$marge)/400), 10, 10, $navy);
    imagettftext($img, 12, 0, $marge+$i*$intX+3, $y-$marge-($visites[$i]*($y-2*$marge)/400)-10, $noir, $font_file, $visites[$i]);
}
//On affiche l'image
header('Content-type: image/png');
imagepng($img);
imagedestroy($img);
?>