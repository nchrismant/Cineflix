<?php

/**
 * Récupère la photo ou la video du jour de la Nasa via l'API de la Nasa (APOD)
 *
 * @param  string $lang Langue du site.
 * @return string
 */
function NasaPic(string $lang="fr") : string {
	$today = date('Y-m-d');
	$curl = curl_init('https://api.nasa.gov/planetary/apod?api_key=WjteJdqz0uuFdwCMsqQlICpESha3wHqfdMhATb07&date='.$today.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$url = $data['url'];
			$desc = $data['title'];
			$type = $data['media_type'];
		}
	}
	curl_close($curl);
	
	if($lang == "en") {
		if($type == "image") {
			$str = "\t<figure>\n";
			$str .= "\t\t<img style=\"width: 117pt; height: 95pt;\" src=\"".$url."\" alt=\"NASA Image of the Day\"/>\n";
			$str .= "\t\t<figcaption><strong>".'NASA picture of the day : </strong>' .$desc."</figcaption>\n";
			$str .= "\t</figure>\n";
		}
		else if($type == "video") {
			$str = "\t\t<iframe src=\"".$url."\"></iframe>\n";
		}
	}
	else {
		if($type == "image") {
			$str = "\t<figure>\n";
			$str .= "\t\t<img style=\"width: 117pt; height: 95pt;\" src=\"".$url."\" alt=\"Image NASA du jour\"/>\n";
			$str .= "\t\t<figcaption><strong>".'Image du jour de la NASA : </strong>' .$desc."</figcaption>\n";
			$str .= "\t</figure>\n";
		}
		else if($type == "video") {
			$str = "\t\t<iframe src=\"".$url."\"></iframe>\n";
		}
	}
	return $str;
}

/**
 * Récupère la position approximative de l'internaute via l'API geoPlugin
 *
 * @param  string $lang Langue du site.
 * @return string
 */
function ServerPos(string $lang="fr") : string {
	if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	$xml = simplexml_load_file('http://www.geoplugin.net/xml.gp?ip='.$ip.'');
	
	$continent = $xml->geoplugin_continentName;
	$country = $xml->geoplugin_countryName;
	$city = $xml->geoplugin_city;
	$region = $xml->geoplugin_region;
	$postal = $xml->geoplugin_regionCode;
	$dep = $xml->geoplugin_regionName;

	if($lang == "en") {	
		$str = "\t<p style=\"text-align: center;\"><strong>".'Your current geographic position : '."</strong></p>\n";
		$str .= "\t<ul>\n";
		$str .= "\t\t<li>".'Continent : '.$continent."</li>\n";
		$str .= "\t\t<li>".'Country : '.$country."</li>\n";
		$str .= "\t\t<li>".'Region : '.$region."</li>\n";
		$str .= "\t\t<li>".'Department : '.$dep.' ('.$postal.')'."</li>\n";
		$str .= "\t\t<li>".'City : '.$city."</li>\n";
		$str .= "\t</ul>\n";
	}
	else {
		$str = "\t<p style=\"text-align: center;\"><strong>".'Votre position géographique actuelle : '."</strong></p>\n";
		$str .= "\t<ul>\n";
		$str .= "\t\t<li>".'Continent : '.$continent."</li>\n";
		$str .= "\t\t<li>".'Pays : '.$country."</li>\n";
		$str .= "\t\t<li>".'Région : '.$region."</li>\n";
		$str .= "\t\t<li>".'Département : '.$dep.' ('.$postal.')'."</li>\n";
		$str .= "\t\t<li>".'Ville : '.$city."</li>\n";
		$str .= "\t</ul>\n";
	}
	return $str;
}

/**
 * Permet d'obtenir la configuration afin d'afficher les affiches des films 
 *
 * @param  int $taille Taille concernant l'affiche (ex: 6 = original, 5 = w780).
 * @return string
 */
function get_posterconfig(int $taille=5) : string {
	$curl = curl_init('https://api.themoviedb.org/3/configuration?api_key=c448da27062330876f0162320dd0f551');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$url = $data['images']['secure_base_url'];
			$postersize = $data['images']['poster_sizes'][$taille];
			$posterconfig = $url.$postersize;
		}
	}
	curl_close($curl);

	return $posterconfig;
}

/**
 * Permet d'obtenir la configuration afin d'afficher les logos de production des films 
 *
 * @return string
 */
function get_logoconfig() : string {
	$curl = curl_init('https://api.themoviedb.org/3/configuration?api_key=c448da27062330876f0162320dd0f551');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$url = $data['images']['secure_base_url'];
			$logosize = $data['images']['logo_sizes'][2];
			$logoconfig = $url.$logosize;
		}
	}
	curl_close($curl);

	return $logoconfig;
}

/**
 * Permet d'obtenir la configuration afin d'afficher les portraits des acteurs ou producteurs des films
 *
 * @return string
 */
function get_profileconfig() : string {
	$curl = curl_init('https://api.themoviedb.org/3/configuration?api_key=c448da27062330876f0162320dd0f551');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$url = $data['images']['secure_base_url'];
			$profilesize = ($data['images']['profile_sizes'][2]);
			$profileconfig = $url.$profilesize;
		}
	}
	curl_close($curl);

	return $profileconfig;
}

/**
 * Permet d'obtenir la configuration afin d'afficher les images des toiles de fond des films
 *
 * @return string
 */
function get_backdropconfig() : string {
	$curl = curl_init('https://api.themoviedb.org/3/configuration?api_key=c448da27062330876f0162320dd0f551');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$url = $data['images']['secure_base_url'];
			$backdropsize = ($data['images']['backdrop_sizes'][3]);
			$backdropconfig = $url.$backdropsize;
		}
	}
	curl_close($curl);

	return $backdropconfig;
}

/**
 * Récupère l'ID du film donné par l'utilisateur 
 *
 * @param  string $filmname Nom du film recherché par l'utilisateur.
 * @return string
 */
function get_idmovies(string $filmname) : string {
	$curl = curl_init('https://api.themoviedb.org/3/search/movie?api_key=c448da27062330876f0162320dd0f551&query='.$filmname.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$id = $data['results'][0]['id'];
		}
	}
	curl_close($curl);
	return $id;	
}

/**
 * Récupère le titre du film 
 *
 * @param  string $id ID du film.
 * @return string
 */
function get_titlemovies(string $id) : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language=fr');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$title = $data['title'];
		}
	}
	curl_close($curl);
	return $title;	
}

/**
 * Récupère l'affiche/poster du film
 *
 * @param  string $id ID du film.
 * @param  string $config Configuration permettant l'affichage de l'affiche.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_postermovies(string $id, string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$poster = $data['poster_path'];
			$title = $data['title'];
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t\t<h3>".'Results for the film : <em>'.htmlspecialchars($title)."</em></h3>\n";
		$alt = 'Movie poster';
	}
	else {
		$str .= "\t\t<h3>".'Résultats pour le film : <em>'.htmlspecialchars($title)."</em></h3>\n";
		$alt = 'Affiche du film';
	}
	$str .= "\t\t\t<figure class='affiche'>\n";
	$str .= "\t\t\t<img height=\"270\" width=\"185\" src=\"".$config.$poster."\" alt=\"$alt\"/>\n";
	$str .= "\t\t\t<figcaption><strong>".htmlspecialchars($title)."</strong></figcaption>\n";
	$str .= "\t\t</figure>\n";
	$str .= "\t</article>\n";
	return $str;
}

/**
 * Récupère l'aperçu/résumé du film 
 *
 * @param  string $id ID du film.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_overviewmovies(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$overview = $data['overview'];
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t\t<h3>".'Overview'."</h3>\n";	
	}
	else {
		$str .= "\t\t<h3>".'Aperçu'."</h3>\n";
	}
	$str .= "\t\t\t<p style=\"margin: 10px;\">".htmlspecialchars($overview)."</p>\n";
	$str .= "\t</article>\n";
	return $str;
}

/**
 * Récupère l'IMDb ID du film afin d'utiliser cet id au travers de l'API OMDb
 *
 * @param  string $id ID du film.
 * @return string
 */
function get_imdbid(string $id) : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'/external_ids?api_key=c448da27062330876f0162320dd0f551');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$imdbid = $data['imdb_id'];
		}
	}
	curl_close($curl);

	return $imdbid;
}

/**
 * Récupère l'aperçu complet du film via l'API OMDb (seulement disponible en anglais)
 *
 * @param  string $imdbid IMDb ID du film.
 * @return string
 */
function get_fulloverviewmovies(string $imdbid) : string {
	$xml = simplexml_load_file('http://www.omdbapi.com/?apikey=edb13aa4&i='.$imdbid.'&plot=full&r=xml');

	$fulloverview = $xml->movie['plot'];

	$str = "\t<article>\n";
	$str .= "\t\t<h3>".'Full Overview'."</h3>\n";
	$str .= "\t\t\t<p style=\"margin: 10px;\">".$fulloverview."</p>\n";
	$str .= "\t</article>\n";
	return $str;
}

/**
 * Récupère les genres du film 
 *
 * @param  string $id ID du film.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_genres(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			if(isset($data['genres'][0]['id'])){
				$genre1 = $data['genres'][0]['id'];
				$genres = APIgenre($genre1, $lang);
			}
			$i = 1;
			while(isset($data['genres'][$i]['id'])) {
				$genre = $data['genres'][$i]['id'];
				$genres .= ', '.APIgenre($genre, $lang);
				$i++;
			}
		}
	}
	curl_close($curl);
	return $genres;
}

/**
 * Récupère les informations du film : Langue originale, Genres, Date de sortie, Durée du Film, Note + nombre de votes
 *
 * @param  string $id ID du film.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_infomovies(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$vo = $data['original_language'].' ('.VO($data['original_language'], $lang).')';
			$genres = get_genres($id, $lang);
			if($lang == "fr") {				
				$release = dateformatFR($data['release_date']);
			}
			else {
				$release = $data['release_date'];
			}
			$runtime = $data['runtime'];
			$vote = $data['vote_average'];
			$votecount = $data['vote_count'];
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t<h3>".'Main information'."</h3>\n";
		$str .= "\t<ul>\n";
		$str .= "\t\t<li>".'VO : '.$vo."</li>\n";
		$str .= "\t\t<li>".'Genres : '.$genres."</li>\n";
		$str .= "\t\t<li>".'Release date : '.$release."</li>\n";
		$str .= "\t\t<li>".'Runtime : '.$runtime.'min'."</li>\n";
		$str .= "\t\t<li>".'Grade : <strong>'.$vote.'</strong>/10 ('.$votecount.' votes)'."</li>\n";
		$str .= "\t</ul>\n";
		$str .= "\t</article>\n";
	}
	else {
		$str .= "\t<h3>".'Principales informations'."</h3>\n";
		$str .= "\t<ul>\n";
		$str .= "\t\t<li>".'VO : '.$vo."</li>\n";
		$str .= "\t\t<li>".'Genres : '.$genres."</li>\n";
		$str .= "\t\t<li>".'Date de sortie : '.$release."</li>\n";
		$str .= "\t\t<li>".'Durée : '.$runtime.'min'."</li>\n";
		$str .= "\t\t<li>".'Note : <strong>'.$vote.'</strong>/10 ('.$votecount.' votes)'."</li>\n";
		$str .= "\t</ul>\n";
		$str .= "\t</article>\n";
	}
	return $str;
}

/**
 * Récupère la liste des genres des films pour en faire une liste d'options
 *
 * @param  string $lang Langue du site.
 * @return string
 */
function optGenre(string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/genre/movie/list?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			for ($i = 0; $i < 19 ; $i++) {
				$value[$i] = $data['genres'][$i]['id'];
				$genrename[$i] = $data['genres'][$i]['name'];
			}
		}
	}
	curl_close($curl);

	$str = "\t\t<select name=\"genre\" id=\"fgenre\">\n";
	for ($i = 0; $i < 19; $i++) {
		$attributes = "";
		if(isset($_GET['genre']) && $_GET['genre'] == $value[$i]) {
			$attributes .= "selected='selected'";
		}
		else if(isset($_COOKIE['LastFilmGenre']) && $_COOKIE['LastFilmGenre'] == $value[$i]) {
			$attributes .= "selected='selected'";
		}
		$str .= "\t\t\t<option value='$value[$i]' $attributes>".$genrename[$i]."</option>\n";
	}
	$str .= "\t\t</select>\n";
	return $str;
}

/**
 * Associe l'ID du genre à son nom
 *
 * @param  string $genres ID des genres des différents films.
 * @param  string $lang Langue du site.
 * @return string
 */
function APIgenre(string $genres, string $lang="fr") : string {
	$genrelistEN = array("12" => "Adventure", "14" => "Fantasy", "16" => "Animation", "18" => "Drama", "27" => "Horror", "28" => "Action", "35" => "Comedy", "36" => "History", "37" => "Western", "53" => "Thriller", 
	"80" => "Crime", "99" => "Documentary", "878" => "Science Fiction", "9648" => "Mystery", "10402" => "Music" ,"10749" => "Romance", "10751" => "Family" , "10752" => "War", "10770" => "TV Movie");
	$genrelistFR = array("12" => "Aventure", "14" => "Fantastique", "16" => "Animation", "18" => "Drame", "27" => "Horreur", "28" => "Action", "35" => "Comédie", "36" => "Histoire", "37" => "Western", "53" => "Thriller", 
	"80" => "Crime", "99" => "Documentaire", "878" => "Science-Fiction", "9648" => "Mystère", "10402" => "Musique" ,"10749" => "Romance", "10751" => "Familial" , "10752" => "Guerre", "10770" => "Téléfilm");

	if($lang == "fr") {
		$genre = $genrelistFR[$genres];
	}
	else if($lang == "en") {
		$genre = $genrelistEN[$genres];
	}
	return $genre;
}

/**
 * Associe le sigle iso de la langue originale du film à son nom
 *
 * @param  string $iso Sigle iso.
 * @param  string $lang Langue du site.
 * @return string
 */
function VO(string $iso, string $lang="fr") : string {
	$langueEN = array("en" => "English", "es" => "Spanish", "fr" => "French", "it" => "Italian", "pt" => "Portuguese", "ja" => "Japanese", "nl" => "Dutch", "ru" => "Russian", "pl" => "Polish", "de" => "German", "ko" => "Korean", "zh" => "Mandarin", "ar" => "Arabic", "hi" => "Hindi", "sr" => "Serbian");
	$langueFR = array("en" => "Anglais", "es" => "Espagnol", "fr" => "Français", "it" => "Italien", "pt" => "Portugais", "ja" => "Japonais", "nl" => "Néerlandais", "ru" => "Russe", "pl" => "Polonais", "de" => "Allemand", "ko" => "Coréen", "zh" => "Chinois", "ar" => "Arabe", "hi" => "Hindi", "sr" => "Serbe");

	if($lang == "fr") {
		$vo = $langueFR[$iso];
	}
	else if($lang == "en") {
		$vo = $langueEN[$iso];
	}
	return $vo;
}

/**
 * Transforme l'affichage de la date en format français
 *
 * @param  string $date
 * @return string
 */
function dateformatFR(string $date) : string {
	$d = explode("-", $date);
	$datefr = $d[2].'/'.$d[1].'/'.$d[0];
	return $datefr;
}

/**
 * Récupère seulement l'année d'une date
 *
 * @param  string $date
 * @return string
 */
function year(string $date) : string {
	$y = explode("-", $date);
	$year = $y[0];
	return $year;
}

/**
 * Transforme le genre Français donné en Anglais
 *
 * @param  array $genre
 * @return array
 */
function genreen(array $genre) : array {
	$genreEN = array("Aventure" => "Adventure", "Fantastique" => "Fantasy", "Animation" => "Animation", "Drame" => "Drama", "Horreur" => "Horror", "Action" => "Action", "Comédie" => "Comedy", "Histoire" => "History", "Western" => "Western", "Thriller" => "Thriller",
	"Crime" => "Crime", "Documentaire" => "Documentary", "Science-Fiction" => "Science Fiction" , "Mystère" => "Mystery", "Musique" => "Music", "Romance" => "Romance", "Familial" => "Family", "Guerre" => "War", "Téléfilm" => "Tv Movie"); 

	foreach ($genre as $element) {
		$new[] = $genreEN[$element];
	}
	return $new;
}

/**
 * Transforme le titre Français du film en Anglais
 *
 * @param  string $film Titre du film en français.
 * @return string
 */
function titleen(string $film) : string {
	$filmencode = urlencode($film);
	$curl = curl_init('https://api.themoviedb.org/3/search/movie?api_key=c448da27062330876f0162320dd0f551&query='.$filmencode.'&language=en');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$new = $data['results'][0]['title'];
		}
	}
	curl_close($curl);
	return $new;
}

/**
 * Récupère l'affiche des 5 films les plus consultés sur le site
 *
 * @param  string $film
 * @param  string $config Configuration permettant l'affichage de l'affiche.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_topfilmsposter(string $film, string $config, string $lang="fr") : string {
	$encodfilm = urlencode($film);
	$curl = curl_init('https://api.themoviedb.org/3/search/movie?api_key=c448da27062330876f0162320dd0f551&query='.$encodfilm.'&language='.$lang.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$title = $data['results'][0]['title'];
			$poster = $data['results'][0]['poster_path'];
		}
	}
	curl_close($curl);
	if($lang == "en") {
		$alt = "poster top films";
	}
	else {
		$alt = "affiche top films";
	}
	$str = "\t\t<li>\n";
	$str .= "\t\t<div class=\"moviestop\">\n";
	$str .= "\t\t\t\t<a href=\"film.php?film=".urlencode($title)."\"><img width=\"120\" height=\"160\" src=\"".$config.$poster."\" alt=\"$alt\"/>\n";
	$str .= "\t\t\t\t<span>".htmlspecialchars($title)."</span></a>\n";
	$str .= "\t\t</div>\n";
	$str .= "\t\t</li>\n";
	return $str;
}

/**
 * Récupère le nom et le portrait des 3 acteurs principaux du film
 *
 * @param  string $id ID du film.
 * @param  string $config Configuration permettant l'affichage des portraits.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_actors(string $id, string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'&append_to_response=credits');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$i = 0;
			while(isset($data['credits']['cast'][$i]) && $i < 3) {
				$actor[$i] = $data['credits']['cast'][$i]['original_name'];
				$character[$i] = $data['credits']['cast'][$i]['character'];
				$profile[$i] = $data['credits']['cast'][$i]['profile_path'];
				$i++;
			}
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t<h3>".'Main actors'."</h3>\n";
		$alt = 'Movie actor';
	}
	else {
		$str .= "\t<h3>".'Acteurs principaux'."</h3>\n";
		$alt = 'Acteur du film';
	}
	$str .= "\t\t<div class='actors'>\n";
	$i = 0;
	if(isset($actor[0])) {
		while(isset($actor[$i]) && $i<3) {
			$str .= "\t\t<figure class=\"act$i\">\n";
			$str .= "\t\t\t<img height=\"270\" width=\"185\" src=\"".$config.$profile[$i]."\" alt=\"$alt\"/>\n";
			$str .= "\t\t\t<figcaption>\n";
			$str .= "\t\t\t<ul>\n";
			$str .= "\t\t\t\t<li><strong>".$actor[$i]."</strong></li>\n";
			$str .= "\t\t\t\t<li>".'('.$character[$i].')'."</li>\n";
			$str .= "\t\t\t</ul>\n";
			$str .= "\t\t\t</figcaption>\n";
			$str .= "\t\t</figure>\n";
			$i++;
		}
	}
	else {
		if($lang == "en") {
			$str .= "\t\t<p>"."No information about actors available."."</p>\n";
		}
		else {
			$str .= "\t\t<p>"."Pas d'informations concernant les acteurs disponibles."."</p>\n";
		}
	}
	$str .= "\t\t</div>\n";
	$str .= "\t</article>\n";
	return $str;
}

/**
 * Récupère les informations concernant la production du film (producteur, logo ...)
 *
 * @param  string $id ID du film.
 * @param  string $config Configuration permettant l'affichage des portraits.
 * @param  string $logoconfig Configuration permettant l'affichage du logo.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_production(string $id, string $config, string $logoconfig, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'&append_to_response=credits');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			if(isset($data['production_companies'][0]['logo_path'])) {
				$logo = $data['production_companies'][0]['logo_path'];
			}
			if(isset($data['production_companies'][0]['name'])) {
				$logoname = $data['production_companies'][0]['name'];
			}
			$i = 0;
			while(isset($data['credits']['crew'][$i]) && (($data['credits']['crew'][$i]['job']) != "Director") ) {
				$i++;
			}
			$director = $data['credits']['crew'][$i]['name'];
			if(isset($data['credits']['crew'][$i]['profile_path'])) {
				$directorprofile = $data['credits']['crew'][$i]['profile_path'];
			}
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	$str .= "\t<h3>".'Production'."</h3>\n";
	$str .= "\t\t<div class='prod'>\n";
	if(isset($directorprofile)) {
		$str .= "\t\t<figure class='direct'>\n";
		if($lang == "en") {
			$alt = 'Director';
		}
		else {
			$alt = 'Directeur';
		}
		$str .= "\t\t\t<img height=\"270\" width=\"185\" src=\"".$config.$directorprofile."\" alt=\"$alt\"/>\n";
		$str .= "\t\t\t<figcaption>\n";
		$str .= "\t\t\t<ul>\n";
		$str .= "\t\t\t\t<li><strong>".$director."</strong></li>\n";
		if($lang == "en") {
			$str .= "\t\t\t\t<li>".'(Director)'."</li>\n";	
		}
		else {
			$str .= "\t\t\t\t<li>".'(Réalisateur)'."</li>\n";
		}
		$str .= "\t\t\t</ul>\n";
		$str .= "\t\t\t</figcaption>\n";
		$str .= "\t\t</figure>\n";
	}
	else {
		$str .= "\t\t\t<ul class='direct'>\n";
		$str .= "\t\t\t\t<li><strong>".$director."</strong></li>\n";
		if($lang == "en") {
			$str .= "\t\t\t\t<li>".'(Director)'."</li>\n";	
		}
		else {
			$str .= "\t\t\t\t<li>".'(Réalisateur)'."</li>\n";
		}
		$str .= "\t\t\t</ul>\n";
	}

	if(isset($logo) && isset($logoname)) {
		$str .= "\t\t<figure class='logo'>\n";
		$str .= "\t\t\t<img src=\"".$logoconfig.$logo."\" alt=\"Logo\"/>\n";
		$str .= "\t\t\t<figcaption>\n";
		$str .= "\t\t\t<ul>\n";
		if($lang == "en") {
			$str .= "\t\t\t\t<li><strong>".'Film production'."</strong></li>\n";	
		}
		else {
			$str .= "\t\t\t\t<li><strong>".'Société de production'."</strong></li>\n";
		}
		$str .= "\t\t\t\t<li>".'('.$logoname.')'."</li>\n";
		$str .= "\t\t\t</ul>\n";
		$str .= "\t\t\t</figcaption>\n";
		$str .= "\t\t</figure>\n";
	}
	else if(!isset($logo) && isset($logoname)) {
		$str .= "\t\t\t<ul class='logo'>\n";
		if($lang == "en") {
			$str .= "\t\t\t\t<li><strong>".'Film production'."</strong></li>\n";	
		}
		else {
			$str .= "\t\t\t\t<li><strong>".'Société de production'."</strong></li>\n";
		}
		$str .= "\t\t\t\t<li>".'('.$logoname.')'."</li>\n";
		$str .= "\t\t\t</ul>\n";
	}
	else if(isset($logo) && !isset($logoname)) {
		$str .= "\t\t<figure class='logo'>\n";
		$str .= "\t\t\t<img src=\"".$logoconfig.$logo."\" alt=\"Logo\"/>\n";
		$str .= "\t\t\t<figcaption>\n";
		$str .= "\t\t\t<ul>\n";
		if($lang == "en") {
			$str .= "\t\t\t\t<li><strong>".'Film production'."</strong></li>\n";	
		}
		else {
			$str .= "\t\t\t\t<li><strong>".'Société de production'."</strong></li>\n";
		}
		$str .= "\t\t\t</ul>\n";
		$str .= "\t\t\t</figcaption>\n";
		$str .= "\t\t</figure>\n";
	}
	else {
		if($lang == "en") {
			$str .= "\t\t<p class='logo'>".'No production logo available for this movie.'."</p>\n";
		}
		else {
			$str .= "\t\t<p class='logo'>".'Pas de logo de production disponible pour ce film.'."</p>\n";
		}
	}
	$str .= "\t\t</div>\n";
	$str .= "\t</article>\n";
	return $str;
}

/**
 * Récupère seulement le nom des 3 acteurs principaux du films
 *
 * @param  string $id ID du film.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_actornames(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'&append_to_response=credits');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$i = 0;
			while(isset($data['credits']['cast'][$i]['original_name']) && $i < 3) {
				$actor[$i] = $data['credits']['cast'][$i]['original_name'];
				$i++;
			}
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t<h3>".'Main actors'."</h3>\n";
	}
	else {
		$str .= "\t<h3>".'Acteurs principaux'."</h3>\n";
	}
	$str .= "\t\t\t<ul>\n";
	$i = 0;
	if(isset($actor[0])) {
		while(isset($actor[$i]) && $i < 3) {
			$str .= "\t\t\t\t<li>".$actor[$i]."</li>\n";
			$i++;
		}
	}
	else {
		if($lang == "en") {
			$str .= "\t\t<p style=\"margin: 10px;\">"."No information about actors available."."</p>\n";
		}
		else {
			$str .= "\t\t<p style=\"margin: 10px;\">"."Pas d'informations concernant les acteurs disponibles."."</p>\n";
		}
	}
	$str .= "\t\t\t</ul>\n";
	$str .= "\t</article>\n";
	return $str;
}

/**
 * Récupère la bande-annonce du film si elle existe ou récupère la toile de fond du film
 *
 * @param  string $id ID du film.
 * @param  string $config Configuration permettant l'affichage de la toile de fond.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_backdropmovies(string $id, string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'&append_to_response=videos');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$backdrop = $data['backdrop_path'];
			if(isset($data['videos']['results'][0])) {
				$i = 0;
				while(isset($data['videos']['results'][$i]) && (($data['videos']['results'][$i]['type']) != "Trailer") ) {
					$i++;
				}
				if(isset($data['videos']['results'][$i]) && $data['videos']['results'][$i]['type'] == "Trailer") {
					$trailerkey = $data['videos']['results'][$i]['key'];
				}
				else if(isset($data['videos']['results'][$i]) && $data['videos']['results'][$i]['type'] == "Teaser") {
					$teaserkey = $data['videos']['results'][$i]['key'];
				}
			}
		}
	}
	curl_close($curl);

	if(isset($trailerkey)) {
		$str = "\t<ul class=\"trailer\">\n";
		$str .= "\t\t<li><iframe width=\"480\" height=\"270\" src=\"//www.youtube.com/embed/$trailerkey?autoplay=1&amp;mute=1\" name=\"youtube embed\" allow=\"autoplay; encrypted-media; fullscreen\"></iframe></li>\n";
		if($lang == "en") {
			$str .= "\t\t<li><strong>".'Trailer'."</strong></li>\n";	
		}
		else {
			$str .= "\t\t<li><strong>".'Bande-Annonce'."</strong></li>\n";
		}
		$str .= "\t</ul>\n";
	}
	else if(isset($teaserkey)) {
		$str = "\t<ul class=\"trailer\">\n";
		$str .= "\t\t<li><iframe width=\"480\" height=\"270\" src=\"//www.youtube.com/embed/$teaserkey?autoplay=1&amp;mute=1\" name=\"youtube embed\" allow=\"autoplay; encrypted-media; fullscreen\"></iframe></li>\n";
		if($lang == "en") {
			$str .= "\t\t<li><strong>".'Teaser'."</strong></li>\n";	
		}
		else {
			$str .= "\t\t<li><strong>".'Extrait'."</strong></li>\n";
		}
		$str .= "\t</ul>\n";
	}
	else {
		$str = "\t\t<figure class=\"backdrop\">\n";
		if($lang == "en") {
			$alt = 'Backdrop';
		}
		else {
			$alt = 'Toile de fond';
		}
		$str .= "\t\t<img width=\"320\" height=\"190\" src=\"".$config.$backdrop."\" alt=\"$alt\"/>\n";
		if($lang == "en") {
			$str .= "\t\t<figcaption><strong>".'Backdrop'."</strong></figcaption>\n";	
		}
		else {
			$str .= "\t\t<figcaption><strong>".'Toile de fond'."</strong></figcaption>\n";
		}
		$str .= "\t</figure>\n";
	}
	return $str;
}

/**
 * Récupère les films en tendances en fonction de la période voulue (jour ou semaine)
 *
 * @param  string $time Période : tendances du jour ou de la semaine.
 * @param  string $config Configuration permettant l'affichage de l'affiche du film.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_trendingmovies(string $time, string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/trending/movie/'.$time.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			for($i = 0 ; $i < 10 ; $i++) {
				$poster[$i] = $data['results'][$i]['poster_path'];
				$title[$i] = $data['results'][$i]['title'];
				$vote[$i] = $data['results'][$i]['vote_average'];
			}
		}
	}
	curl_close($curl);	
	
	if($time == 'day') {
		$str = "\t<article class='daytrend'>\n";
		if($lang == "en") {
			$str .= "\t<h3>".'Trends of the day'."</h3>\n";
			$alt = 'Trending movie';
		}
		else {
			$str .= "\t<h3>".'Tendances du jour'."</h3>\n";
			$alt = 'Film en Tendances';
		}
	}
	else if($time == 'week') {
		$str = "\t<article class='weektrend'>\n";
		if($lang == "en") {
			$str .= "\t<h3>".'Trends of the week'."</h3>\n";
			$alt = 'Trending movie';
		}
		else {
			$str .= "\t<h3>".'Tendances de la semaine'."</h3>\n";
			$alt = 'Film en Tendances';
		}
	}
	$str .= "\t<ul class=\"scrolltrend\">\n";
	for ($i = 0 ; $i < 10; $i++) {
		$str .= "\t<li class=\"scroll\">\n";
		$str .= "\t<div class=\"moviescroll\">\n";
		$str .= "\t\t<figure>\n";
		$str .= "\t\t\t<img height=\"270\" width=\"185\" src=\"".$config.$poster[$i]."\" alt=\"$alt\"/>\n";
		$str .= "\t\t\t<figcaption>\n";
		$str .= "\t\t\t<ul>\n";
		$str .= "\t\t\t\t<li><strong>".htmlspecialchars($title[$i])."</strong></li>\n";
		if($lang == "en") {
			$str .= "\t\t\t\t<li>".'('.$vote[$i].'/10)'."<a class=\"button\" style=\"margin-left: 42px; font-size: small;\" href=\"film.php?film=".urlencode($title[$i])."\"><span>".'Details'."</span></a></li>\n";
		}
		else {
			$str .= "\t\t\t\t<li>".'('.$vote[$i].'/10)'."<a class=\"button\" style=\"margin-left: 42px; font-size: small;\" href=\"film.php?film=".urlencode($title[$i])."\"><span>".'Détails'."</span></a></li>\n";
		}
		$str .= "\t\t\t</ul>\n";
		$str .= "\t\t\t</figcaption>\n";
		$str .= "\t\t</figure>\n";
		$str .= "\t</div>\n";
		$str .= "\t</li>\n";
	}
	$str .= "\t</ul>\n";
	$str .= "\t</article>\n";
	return $str;
}

/**
 * Récupère les recommandations des films
 *
 * @param  string $id ID du film.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_recommendations(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$id.'/similar?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			if(isset($data['results'][0])) {
				$i = 0;
				while(isset($data['results'][$i]['title']) && isset($data['results'][$i]['release_date'])) {
					$film[$i] = $data['results'][$i]['title'];
					$date[$i] = $data['results'][$i]['release_date'];
					$year[$i] = year($date[$i]);
					$i++;
				}
			}
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t\t<h3>".'Recommendations'."</h3>\n";	
	}
	else {
		$str .= "\t\t<h3>".'Recommandations'."</h3>\n";
	}
	if(isset($film[1])) {
		$str .= "\t\t\t<ul style=\"list-style: square inside;\" class=\"recolink\">\n";
		$i = 0;
		while(isset($film[$i]) && $i < 10) {
			$str .= "\t\t\t<li><a href=\"film.php?film=".urlencode($film[$i])."\">".htmlspecialchars($film[$i]).' ('.$year[$i].')'."</a></li>\n";
			$i++;
		}
		$str .= "\t\t\t</ul>\n";
	}
	else {
		if($lang == "en") {
			$str .= "\t\t<p style=\"margin: 10px;\">".'No recommendations available for this movie.'."</p>\n";
		}
		else {
			$str .= "\t\t<p style=\"margin: 10px;\">".'Pas de recommandations disponibles pour ce film.'."</p>\n";
		}
	}
	$str .= "\t</article>\n";
	return $str;
}

/**
 * Récupère l'ID d'un film aléatoirement en fonction du genre et de l'année de sortie.
 *
 * @param  string $genre Genre du film.
 * @param  string $year Année de sortie du film.
 * @return string
 */
function get_alea(string $genre, string $year) : string {
	$curl = curl_init('https://api.themoviedb.org/3/discover/movie?api_key=c448da27062330876f0162320dd0f551&with_genres='.$genre.'&primary_release_year='.$year.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$i = rand(0,19);
			$aleaid = $data['results'][$i]['id'];
		}
	}
	curl_close($curl);

	return $aleaid;
}

/**
 * Récupère la liste des films actuellement ou prochainement au cinéma 
 *
 * @param  string $period Période de diffusion.
 * @param  string $lang Langue du site.
 * @param  string $region Pays de diffusion.
 * @return string
 */
function get_playing(string $period, string $lang="fr", string $region="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/'.$period.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'&region='.$region.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			$date = $data['dates']['minimum'];
			$dateend = $data['dates']['maximum'];			
			if($lang == "fr") {
				$firstdate = dateformatFR($date);
				$enddate = dateformatFR($dateend);
			}
			else {
				$firstdate = $date;
				$enddate = $dateend;
			}
			$i = 0;
			while(isset($data['results'][$i]) && $i <= 10) {
				$nextfilm[$i] = $data['results'][$i]['title'];
				$i++;
			}
		}
	}
	curl_close($curl);		

	if($lang == 'en') {
		if($period == "now_playing") {
			$str = "\t<article class='nowm'>\n";
			$str .= "\t\t<h3>".'Movies in theatres : date range between '.$firstdate.' and '.$enddate."</h3>\n";
		}
		else if($period == "upcoming") {
			$str = "\t<article class='upcom'>\n";
			$str .= "\t\t<h3>".'Upcoming movies in theatres : date range between '.$firstdate.' and '.$enddate."</h3>\n";
		}
	}
	else {
		if($period == "now_playing") {
			$str = "\t<article class='nowm'>\n";
			$str .= "\t\t<h3>".'Les films à voir actuellement au cinéma : période du '.$firstdate.' au '.$enddate."</h3>\n";
		}
		else if($period == "upcoming") {
			$str = "\t<article class='upcom'>\n";
			$str .= "\t\t<h3>".'Les prochains films à voir au cinéma : période du '.$firstdate.' au '.$enddate."</h3>\n";
		}
	}
	$str .= "\t\t\t<ul style=\"list-style: square inside;\">\n";
	$i = 0;
	while(isset($nextfilm[$i])) {
		$str .= "\t\t\t\t<li><a class=\"playlink\" href=\"film.php?film=".urlencode($nextfilm[$i])."\">".htmlspecialchars($nextfilm[$i])."</a></li>\n";
		$i++;
	}
	$str .= "\t\t\t</ul>\n";
	$str .= "\t</article>\n";
	return $str;
}

/**
 * Récupère les 10 films les plus populaires
 *
 * @param  string $config Configuration permettant l'affichage de l'affiche.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_popular(string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/popular?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			for ($i = 0 ; $i < 10 ; $i++) {
				$popfilm[$i] = $data[$i] = $data['results'][$i]['title'];
				$popposter[$i] = $data['results'][$i]['poster_path'];
			}
		}
	}
	curl_close($curl);	

	$str = "\t<article class='popm'>\n";
	if($lang == "en") {
		$str .= "\t\t<h3>".'Most popular movies of the moment'."</h3>\n";
		$alt = 'Popular movie poster';
	}
	else {
		$str .= "\t\t<h3>".'Films les plus populaires du moment'."</h3>\n";
		$alt = 'Affiche film populaire';
	}
	$str .= "\t<div class='container2'>\n";
	$str .= "\t\t<div class='slidershow middle'>\n";
	$str .= "\t\t<div class='slides'>\n";
	$str .= "\t\t\t<input type=\"radio\" name=\"r\" checked=\"checked\" id=\"r1\" />\n";
	for($i = 2; $i < 6 ; $i++) {
		$str .= "\t\t\t<input type=\"radio\" name=\"r\" id=\"r$i\" />\n";
	}
	$str .= "\t\t<div class='slide s1'>\n";
	$str .= "\t\t\t<a href=\"film.php?film=".urlencode($popfilm[0])."\"><img src=\"".$config.$popposter[0]."\" alt=\"$alt\"/></a>\n";
	$str .= "\t\t</div>\n";
	for ($i = 1 ; $i < 5; $i++) {
		$str .= "\t\t<div class='slide'>\n";
		$str .= "\t\t\t<a href=\"film.php?film=".urlencode($popfilm[$i])."\"><img src=\"".$config.$popposter[$i]."\" alt=\"$alt\"/></a>\n";
		$str .= "\t\t</div>\n";
	}
	$str .= "\t\t</div>\n";
	$str .= "\t\t<div class='navslide'>\n";
	for($i = 1; $i < 6; $i++) {
		$str .= "\t\t\t<label for=\"r$i\" class=\"bar\"></label>\n";
	}
	$str .= "\t\t</div>\n";
	$str .= "\t\t</div>\n";
	
	$str .= "\t\t<div class='slidershow middle2'>\n";
	$str .= "\t\t<div class='slides'>\n";
	$str .= "\t\t\t<input type=\"radio\" name=\"r2\" checked=\"checked\" id=\"r6\" />\n";
	for($i = 7; $i < 11 ; $i++) {
		$str .= "\t\t\t<input type=\"radio\" name=\"r2\" id=\"r$i\" />\n";
	}
	$str .= "\t\t<div class='slide s2'>\n";
	$str .= "\t\t\t<a href=\"film.php?film=".urlencode($popfilm[5])."\"><img src=\"".$config.$popposter[5]."\" alt=\"$alt\"/></a>\n";
	$str .= "\t\t</div>\n";
	for ($i = 6 ; $i < 10; $i++) {
		$str .= "\t\t<div class='slide'>\n";
		$str .= "\t\t\t<a href=\"film.php?film=".urlencode($popfilm[$i])."\"><img src=\"".$config.$popposter[$i]."\" alt=\"$alt\"/></a>\n";
		$str .= "\t\t</div>\n";
	}
	$str .= "\t\t</div>\n";
	$str .= "\t\t<div class='navslide'>\n";
	for($i = 6; $i < 11; $i++) {
		$str .= "\t\t\t<label for=\"r$i\" class=\"bar\"></label>\n";
	}
	$str .= "\t\t</div>\n";
	$str .= "\t\t</div>\n";
	$str .= "\t</div>\n";
	$str .= "\t</article>\n";
	return $str;
}

/**
 * Récupère les 10 films les mieux notés
 *
 * @param  string $config Configuration permettant l'affichage de l'affiche.
 * @param  string $lang Langue du site.
 * @return string
 */
function get_toprated(string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/movie/top_rated?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
	curl_setopt_array($curl, [
		CURLOPT_CAINFO 		   => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem',
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 20,
	]);
	$data = curl_exec($curl);
	if($data == false || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
		echo (curl_error($curl));
	}
	else {
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
			$data = json_decode($data, true);
			for($i = 0 ; $i < 10 ; $i++) {
				$topfilm[$i] = $data['results'][$i]['title'];
				$topposter[$i] = $data['results'][$i]['poster_path'];
			}
		}
	}
	curl_close($curl);	

	$str = "\t<article class='ratedm'>\n";
	if($lang == "en") {
		$str .= "\t\t<h3>".'Best rated movies'."</h3>\n";
		$alt = 'Top note movie poster';
	}
	else {
		$str .= "\t\t<h3>".'Films les mieux notés'."</h3>\n";
		$alt = 'Affiche film top note';
	}
	$str .= "\t<div class='container2'>\n";
	$str .= "\t\t<div class='slidershow middle'>\n";
	$str .= "\t\t<div class='slides'>\n";
	$str .= "\t\t\t<input type=\"radio\" name=\"r3\" checked=\"checked\" id=\"r11\" />\n";
	for($i = 12; $i < 16 ; $i++) {
		$str .= "\t\t\t<input type=\"radio\" name=\"r3\" id=\"r$i\" />\n";
	}
	$str .= "\t\t<div class='slide s3'>\n";
	$str .= "\t\t\t<a href=\"film.php?film=".urlencode($topfilm[0])."\"><img src=\"".$config.$topposter[0]."\" alt=\"$alt\"/></a>\n";
	$str .= "\t\t</div>\n";
	for ($i = 1 ; $i < 5; $i++) {
		$str .= "\t\t<div class='slide'>\n";
		$str .= "\t\t\t<a href=\"film.php?film=".urlencode($topfilm[$i])."\"><img src=\"".$config.$topposter[$i]."\" alt=\"$alt\"/></a>\n";
		$str .= "\t\t</div>\n";
	}
	$str .= "\t\t</div>\n";
	$str .= "\t\t<div class='navslide'>\n";
	for($i = 11; $i < 16; $i++) {
		$str .= "\t\t\t<label for=\"r$i\" class=\"bar\"></label>\n";
	}
	$str .= "\t\t</div>\n";
	$str .= "\t\t</div>\n";

	$str .= "\t\t<div class='slidershow middle2'>\n";
	$str .= "\t\t<div class='slides'>\n";
	$str .= "\t\t\t<input type=\"radio\" name=\"r4\" checked=\"checked\" id=\"r16\" />\n";
	for($i = 17; $i < 21 ; $i++) {
		$str .= "\t\t\t<input type=\"radio\" name=\"r4\" id=\"r$i\" />\n";
	}
	$str .= "\t\t<div class='slide s4'>\n";
	$str .= "\t\t\t<a href=\"film.php?film=".urlencode($topfilm[5])."\"><img src=\"".$config.$topposter[5]."\" alt=\"$alt\"/></a>\n";
	$str .= "\t\t</div>\n";
	for ($i = 6 ; $i < 10; $i++) {
		$str .= "\t\t<div class='slide'>\n";
		$str .= "\t\t\t<a href=\"film.php?film=".urlencode($topfilm[$i])."\"><img src=\"".$config.$topposter[$i]."\" alt=\"$alt\"/></a>\n";
		$str .= "\t\t</div>\n";
	}
	$str .= "\t\t</div>\n";
	$str .= "\t\t<div class='navslide'>\n";
	for($i = 16; $i < 21; $i++) {
		$str .= "\t\t\t<label for=\"r$i\" class=\"bar\"></label>\n";
	}
	$str .= "\t\t</div>\n";
	$str .= "\t\t</div>\n";
	$str .= "\t</div>\n";
	$str .= "\t</article>\n";
	return $str;
}
?>