<?php

/**
 * Affiche la date du jour
 *
 * @param  string $lang Langue du site.
 * @return string
 */
function get_date(string $lang="fr") : string {
						//Dimanche = 0.
	$jourfr = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
						//Janvier = 1.
	$moisfr = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Décembre");
	$jouren = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	$moisen = array("","January","February","March","April","May","June","July","August","September","October","November","December");
	
	if($lang == "en") {
		//Version anglaise (Nom du jour, mois du jour, numéro du jour et année (ex : Monday, September 03, 2020).
		list($nomjour, $mois, $jour, $annee) = explode('/', date("w/n/d/Y"));
		$str = "\t\t<li>".$jouren[$nomjour].', '.$moisen[$mois].' '.$jour.', '.$annee."</li>\n";
		return $str;
	}
	else { 
		//Version française (Nom du jour, numéro du jour, mois du jour et année (ex : Lundi 3 septembre 2020).
		/* w = Jour de la semaine (0 pour Dimanche), d = Le jour du mois avec 0 initiaux (02,04...), j = Le jour du mois sans 0 initiaux (2,4...),
		n = Le mois de l'année (1 pour Janvier), Y = Année sur 4 chiffres. */
		list($nomjour, $jour, $mois, $annee) = explode('/', date("w/j/n/Y"));
		$str = "\t\t<li>".$jourfr[$nomjour].' '.$jour.' '.$moisfr[$mois].' '.$annee."</li>\n";
		return $str;
	}

}

/**
 * Créer les fichiers et incrémente les compteurs global et journaliers
 *
 * @return void
 */
function ajouter_visite() : void {
	$fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'compteurs' . DIRECTORY_SEPARATOR . 'compteur';
	$fichier_journalier = $fichier . '-' . date('d-m-Y');
	incrementer_compteur($fichier);
	incrementer_compteur($fichier_journalier);
}

/**
 * Permet d'incrémenter le compteur 
 *
 * @param  strig $fichier Fichier à incrementer.
 * @return void
 */
function incrementer_compteur(string $fichier) : void {
	$compteur = 1;
	if(file_exists($fichier)) {
		$compteur = (int)file_get_contents($fichier);
		$compteur++;
	}
	file_put_contents($fichier, $compteur);
}

/**
 * Récupère la valeur du compteur du fichier global
 *
 * @return string
 */
function nombre_visites() : string {
	$fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'compteurs' . DIRECTORY_SEPARATOR . 'compteur';
	return file_get_contents($fichier);
}

/**
 * Récupère la valeur du compteur du fichier journalier
 *
 * @param  string $date Date du fichier.
 * @return string
 */
function nombre_visitesjour(string $date) : string {
	$fichier_journalier = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'compteurs' . DIRECTORY_SEPARATOR . 'compteur-'.$date.'';
	if(file_exists($fichier_journalier)) {
		return file_get_contents($fichier_journalier);
	}
	else {
		return '0';
	}
}
 
/**
 * Stocke les informations de recherches des films
 *
 * @param  string $id ID du film recherché.
 * @return void
 */
function stockage(string $id) : void {
	date_default_timezone_set('Europe/Paris');
	$date = date("d/m/Y");
	$hour = date("H:i:s");
	if(isset($_GET['film']) && !empty($_GET['film'])) {
		$genre1 = get_genres($id);
		$name1 = get_titlemovies($id);
		$filmlist = array($name1, $genre1, "Recherche par Nom", $date, $hour);
	}
	else if(isset($_GET['id']) && !empty($_GET['id'])) {
		$genreid = get_genres($id);
		$nameid = get_titlemovies($id);
		$filmlist = array($nameid, $genreid, "Recherche par ID", $date, $hour);
	}
	else if((isset($_GET['genre']) && !empty($_GET['genre'])) && (isset($_GET['y']) && !empty($_GET['y'])) ) {
		$genrefilm = get_genres($id);
		$namefilm = get_titlemovies($id);
		$filmlist = array($namefilm, $genrefilm, "Recherche Aléatoire", $date, $hour);
	}
	if(isset($filmlist)) {
		if(file_exists('stockage.csv')) {
			$file = fopen('stockage.csv', 'a');
			fputcsv($file, $filmlist);
		}
		else {
			$file = fopen('stockage.csv', 'a');
			fputcsv($file, $filmlist);
		}
		fclose($file);
	}
}

/**
 * Compte les types de recherche des films sur le site
 *
 * @return array
 */
function countmovietype() : array {
	$csvFile = file('stockage.csv');
	$data = [];
  	foreach ($csvFile as $line) {
		$data[] = str_getcsv($line);
  	}
	$r = '';
  	$nbelem = count($data)-1;
  	for($i = 0 ; $i <= $nbelem; $i++) {
		$filmcsv = explode(",", $data[$i][2]);
		$r .= $filmcsv[0];
	}
	$rechnom = substr_count($r, "Recherche par Nom");
	$rechid = substr_count($r, "Recherche par ID");
	$rechalea = substr_count($r, "Recherche Aléatoire");
	$type = array($rechnom, $rechid, $rechalea);
	return $type;
}

/**
 * Compte les films les plus recherchés sur le site
 *
 * @return array
 */
function counttopmovies() : array {
	$csvFile = file('stockage.csv');
	$data = [];
	foreach ($csvFile as $line) {
		$data[] = str_getcsv($line);
	}
	$nbelem = count($data)-1;
	for($i = 0 ; $i <= $nbelem; $i++) {
		$films[$i] = $data[$i][0];
	}	
	$f = (array_count_values($films));
	arsort($f);
	return $f;
}

/**
 * Récupère les genres recherchés sur le site
 *
 * @return array
 */
function array_genres() : array {
	$csvFile = file('stockage.csv');
	$data = [];
	foreach ($csvFile as $line) {
		$data[] = str_getcsv($line);
	}
	$nbelem = count($data)-1;
	for($i = 0 ; $i <= $nbelem; $i++) {
		$genres[$i] = explode(",",$data[$i][1]);
	}
	return $genres;
}

/**
 * Transforme un tableau multidimensionnel en un tableau unidimensionnel
 *
 * @param  array $array Tableau multidimensionnel.
 * @return array
 */
function array_uni(array $array) : array {
    $result = array();

    if (!is_array($array)) {
        $array = func_get_args();
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, array_uni($value));
        } else {
            $result = array_merge($result, array($key => $value));
        }
    }
    return $result;
}

function stockage2(string $id) : void {
	date_default_timezone_set('Europe/Paris');
	$date = date("d/m/Y");
	$hour = date("H:i:s");
	if(isset($_GET['serie']) && !empty($_GET['serie'])) {
		$genre1 = get_genreseries($id);
		$name1 = get_titleseries($id);
		$serielist = array($name1, $genre1, "Recherche par Nom", $date, $hour);
	}
	else if((isset($_GET['sgenre']) && !empty($_GET['sgenre'])) && (isset($_GET['year']) && !empty($_GET['year'])) ) {
		$genreserie = get_genreseries($id);
		$nameserie = get_titleseries($id);
		$serielist = array($nameserie, $genreserie, "Recherche Aléatoire", $date, $hour);
	}
	if(isset($serielist)) {
		if(file_exists('stockage2.csv')) {
			$file = fopen('stockage2.csv', 'a');
			fputcsv($file, $serielist);
		}
		else {
			$file = fopen('stockage2.csv', 'a');
			fputcsv($file, $serielist);
		}
		fclose($file);
	}
}

function counttopseries() : array {
	$csvFile = file('stockage2.csv');
	$data = [];
	foreach ($csvFile as $line) {
		$data[] = str_getcsv($line);
	}
	$nbelem = count($data)-1;
	for($i = 0 ; $i <= $nbelem; $i++) {
		$films[$i] = $data[$i][0];
	}	
	$f = (array_count_values($films));
	arsort($f);
	return $f;
}

function countserietype() : array {
	$csvFile = file('stockage2.csv');
	$data = [];
  	foreach ($csvFile as $line) {
		$data[] = str_getcsv($line);
  	}
	$r = '';
  	$nbelem = count($data)-1;
  	for($i = 0 ; $i <= $nbelem; $i++) {
		$filmcsv = explode(",", $data[$i][2]);
		$r .= $filmcsv[0];
	}
	$rechnom = substr_count($r, "Recherche par Nom");
	$rechalea = substr_count($r, "Recherche Aléatoire");
	$type = array($rechnom, $rechalea);
	return $type;
}

function array_seriesgenres() : array {
	$csvFile = file('stockage2.csv');
	$data = [];
	foreach ($csvFile as $line) {
		$data[] = str_getcsv($line);
	}
	$nbelem = count($data)-1;
	for($i = 0 ; $i <= $nbelem; $i++) {
		$genres[$i] = explode(",",$data[$i][1]);
	}
	return $genres;
}
?>