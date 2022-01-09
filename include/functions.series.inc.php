<?php
require_once "./include/functions.inc.php";

function get_trendingseries(string $time, string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/trending/tv/'.$time.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
				$title[$i] = $data['results'][$i]['name'];
				$vote[$i] = $data['results'][$i]['vote_average'];
			}
		}
	}
	curl_close($curl);
	
	if($time == 'day') {
		$str = "\t<article class='daytrend'>\n";
		if($lang == "en") {
			$str .= "\t<h3>".'Trends of the day'."</h3>\n";
		}
		else {
			$str .= "\t<h3>".'Tendances du jour'."</h3>\n";
		}
	}
	else if($time == 'week') {
		$str = "\t<article class='weektrend'>\n";
		if($lang == "en") {
			$str .= "\t<h3>".'Trends of the week'."</h3>\n";
		}
		else {
			$str .= "\t<h3>".'Tendances de la semaine'."</h3>\n";
		}
	}
	$str .= "\t<ul class=\"scrolltrend\">\n";
	for ($i = 0 ; $i < 10 ; $i++) {	
		$str .= "\t<li class=\"scroll\">\n";
		$str .= "\t<div class=\"moviescroll\">\n";
		$str .= "\t\t<figure>\n";
		$str .= "\t\t\t<img height=\"270\" width=\"185\" src=\"".$config.$poster[$i]."\" alt=\"Série en Tendances\"/>\n";
		$str .= "\t\t\t<figcaption>\n";
		$str .= "\t\t\t<ul>\n";
		$str .= "\t\t\t\t<li><strong>".htmlspecialchars($title[$i])."</strong></li>\n";
		if($lang == "en") {
			$str .= "\t\t\t\t<li>".'('.$vote[$i].'/10)'."<a class=\"button\" style=\"margin-left: 42px; font-size: small;\" href=\"serie.php?serie=".urlencode($title[$i])."\"><span>".'Details'."</span></a></li>\n";
		}
		else {
			$str .= "\t\t\t\t<li>".'('.$vote[$i].'/10)'."<a class=\"button\" style=\"margin-left: 42px; font-size: small;\" href=\"serie.php?serie=".urlencode($title[$i])."\"><span>".'Détails'."</span></a></li>\n";
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

function get_popularseries(string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/popular?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
				$popseries[$i] = $data['results'][$i]['name'];
				$popposter[$i] = $data['results'][$i]['poster_path'];
			}
		}
	}
	curl_close($curl);

	$str = "\t<article class='popm'>\n";
	if($lang == "en") {
		$str .= "\t\t<h3>".'Most popular series of the moment'."</h3>\n";
		$alt = 'Popular serie poster';
	}
	else {
		$str .= "\t\t<h3>".'Séries les plus populaires du moment'."</h3>\n";
		$alt = 'Affiche série populaire';
	}
	$str .= "\t<div class='container2'>\n";
	$str .= "\t\t<div class='slidershow middle'>\n";
	$str .= "\t\t<div class='slides'>\n";
	$str .= "\t\t\t<input type=\"radio\" name=\"r\" checked=\"checked\" id=\"r1\" />\n";
	for($i = 2; $i < 6 ; $i++) {
		$str .= "\t\t\t<input type=\"radio\" name=\"r\" id=\"r$i\" />\n";
	}
	$str .= "\t\t<div class='slide s1'>\n";
	$str .= "\t\t\t<a href=\"serie.php?serie=".urlencode($popseries[0])."\"><img src=\"".$config.$popposter[0]."\" alt=\"$alt\"/></a>\n";
	$str .= "\t\t</div>\n";
	for ($i = 1 ; $i < 5; $i++) {
		$str .= "\t\t<div class='slide'>\n";
		$str .= "\t\t\t<a href=\"serie.php?serie=".urlencode($popseries[$i])."\"><img src=\"".$config.$popposter[$i]."\" alt=\"$alt\"/></a>\n";
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
	$str .= "\t\t\t<a href=\"serie.php?serie=".urlencode($popseries[5])."\"><img src=\"".$config.$popposter[5]."\" alt=\"$alt\"/></a>\n";
	$str .= "\t\t</div>\n";
	for ($i = 6 ; $i < 10; $i++) {
		$str .= "\t\t<div class='slide'>\n";
		$str .= "\t\t\t<a href=\"serie.php?serie=".urlencode($popseries[$i])."\"><img src=\"".$config.$popposter[$i]."\" alt=\"$alt\"/></a>\n";
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

function get_topratedseries(string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/top_rated?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
				$topseries[$i] = $data['results'][$i]['name'];
				$topposter[$i] = $data['results'][$i]['poster_path'];
			}
		}
	}
	curl_close($curl);

	$str = "\t<article class='ratedm'>\n";
	if($lang == "en") {
		$str .= "\t\t<h3>".'Best rated series'."</h3>\n";
		$alt = 'Top note serie poster';
	}
	else {
		$str .= "\t\t<h3>".'Séries les mieux notées'."</h3>\n";
		$alt = 'Affiche série top note';
	}
	$str .= "\t<div class='container2'>\n";
	$str .= "\t\t<div class='slidershow middle'>\n";
	$str .= "\t\t<div class='slides'>\n";
	$str .= "\t\t\t<input type=\"radio\" name=\"r3\" checked=\"checked\" id=\"r11\" />\n";
	for($i = 12; $i < 16 ; $i++) {
		$str .= "\t\t\t<input type=\"radio\" name=\"r3\" id=\"r$i\" />\n";
	}
	$str .= "\t\t<div class='slide s3'>\n";
	$str .= "\t\t\t<a href=\"serie.php?serie=".urlencode($topseries[0])."\"><img src=\"".$config.$topposter[0]."\" alt=\"$alt\"/></a>\n";
	$str .= "\t\t</div>\n";
	for ($i = 1 ; $i < 5; $i++) {
		$str .= "\t\t<div class='slide'>\n";
		$str .= "\t\t\t<a href=\"serie.php?serie=".urlencode($topseries[$i])."\"><img src=\"".$config.$topposter[$i]."\" alt=\"$alt\"/></a>\n";
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
	$str .= "\t\t\t<a href=\"serie.php?serie=".urlencode($topseries[5])."\"><img src=\"".$config.$topposter[5]."\" alt=\"$alt\"/></a>\n";
	$str .= "\t\t</div>\n";
	for ($i = 6 ; $i < 10; $i++) {
		$str .= "\t\t<div class='slide'>\n";
		$str .= "\t\t\t<a href=\"serie.php?serie=".urlencode($topseries[$i])."\"><img src=\"".$config.$topposter[$i]."\" alt=\"$alt\"/></a>\n";
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

function get_airing(string $period, string $lang="fr", string $timezone="UTC+1") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$period.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'&timezone='.$timezone.'');
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
			while(isset($data['results'][$i]) && $i <= 10) {
				$nextserie[$i] = $data['results'][$i]['name'];
				$i++;
			}			
		}
	}
	curl_close($curl);

	if($lang == 'en') {
		if($period == "airing_today") {
			$str = "\t<article class='nowm'>\n";
			$str .= "\t\t<h3>Séries airing today </h3>\n";
		}
		else if($period == "on_the_air") {
			$str = "\t<article class='upcom'>\n";
			$str .= "\t\t<h3> Séries on the air </h3>\n";
		}
	}
	else {
		if($period == "airing_today") {
			$str = "\t<article class='nowm'>\n";
			$str .= "\t\t<h3>Les séries diffusées aujourd'hui</h3>\n";
		}
		else if($period == "on_the_air") {
			$str = "\t<article class='upcom'>\n";
			$str .= "\t\t<h3>Les séries en cours de diffusion</h3>\n";
		}
	}
	$str .= "\t\t\t<ul style=\"list-style: square inside;\">\n";
	$i = 0;
	while(isset($nextserie[$i])) {
		$str .= "\t\t\t\t<li><a class=\"playlink\" href=\"serie.php?serie=".urlencode($nextserie[$i])."\">".htmlspecialchars($nextserie[$i])."</a></li>\n";
		$i++;
	}
	$str .= "\t\t\t</ul>\n";
	$str .= "\t</article>\n";
	return $str;	
}

function get_idseries(string $seriename) : string {
	$curl = curl_init('https://api.themoviedb.org/3/search/tv?api_key=c448da27062330876f0162320dd0f551&query='.$seriename.'');
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

function get_posterseries(string $id, string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
			$title = $data['name'];
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t\t<h3>".'Results for the serie : <em>'.htmlspecialchars($title)."</em></h3>\n";
		$alt = 'Serie poster';
	}
	else {
		$str .= "\t\t<h3>".'Résultats pour la série : <em>'.htmlspecialchars($title)."</em></h3>\n";
		$alt = 'Affiche de la série';
	}
	$str .= "\t\t\t<figure class='affiche'>\n";
	$str .= "\t\t\t<img height=\"270\" width=\"185\" src=\"".$config.$poster."\" alt=\"$alt\"/>\n";
	$str .= "\t\t\t<figcaption><strong>".htmlspecialchars($title)."</strong></figcaption>\n";
	$str .= "\t\t</figure>\n";
	$str .= "\t</article>\n";
	return $str;
}

function get_backdropseries(string $id, string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'&append_to_response=videos');
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
				while(isset($data['videos']['results'][$i]) && ($data['videos']['results'][$i]['type'] != "Trailer") ) {
					$i++;
				}
                if($data['videos']['results'][$i]['type'] == "Trailer") {
				    $trailerkey = $data['videos']['results'][$i]['key'];
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
	else {
		$str = "\t\t<figure class=\"backdrop\">\n";
		$str .= "\t\t<img width=\"320\" height=\"190\" src=\"".$config.$backdrop."\" alt=\"Image de la série\"/>\n";
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

function get_overviewseries(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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

function get_seriesactornames(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'&append_to_response=credits');
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
	while(isset($actor[$i]) && $i < 3) {
		$str .= "\t\t\t\t<li>".$actor[$i]."</li>\n";
		$i++;
	}
	$str .= "\t\t\t</ul>\n";
	$str .= "\t</article>";
	return $str;
}

function Seriesgenre(string $genres, string $lang="fr") : string {
	$genrelistEN = array("10759" => "Action & Adventure", "16" => "Animation", "18" => "Drama", "35" => "Comedy", "37" => "Western","80" => "Crime", "99" => "Documentary", "10762" => "Kids" ,
	"9648" => "Mystery", "10751" => "Family" , "10763" => "News" , "10764" => "Reality", "10765" => "Sci-Fi & Fantasy" , "10766" => "Soap" , "10767" => "Talk", "10768" => "War & Politics");
	$genrelistFR = array("10759" => "Action & Aventure", "16" => "Animation", "18" => "Drame", "35" => "Comédie", "37" => "Western","80" => "Crime", "99" => "Documentaire", "10762" => "Kids" ,
	"9648" => "Mystère", "10751" => "Familial" , "10763" => "News" , "10764" => "Reality", "10765" => "Science-Fiction & Fantastique" , "10766" => "Soap" , "10767" => "Talk", "10768" => "War & Politics");

	if($lang == "fr") {
		$genre = $genrelistFR[$genres];
	}
	else if($lang == "en") {
		$genre = $genrelistEN[$genres];
	}
	return $genre;
}


function get_infoseries(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
			if(isset($data['genres'][0]['id'])){
				$genre1 = $data['genres'][0]['id'];
				$genres = Seriesgenre($genre1, $lang);
			}
			$i = 1;
			while(isset($data['genres'][$i]['id'])) {
				$genre = $data['genres'][$i]['id'];
				$genres .= ', '.Seriesgenre($genre, $lang);
				$i++;
			}
			if($lang == "fr") {				
				$release = dateformatFR($data['first_air_date']);
			}
			else {
				$release = $data['first_air_date'];
			}
			if(isset($data['episode_run_time'][0])) {
				$runtime = $data['episode_run_time'][0];
			}
			else {
				$runtime = 0;
			}
			if(isset($data['vote_average'])) {			
				$vote = $data['vote_average'];
			}
			else {
				$vote = 0;
			}
			if(isset($data['vote_count'])) {
				$votecount = $data['vote_count'];
			}
			else {
				$votecount = 0;
			}
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t<h3>".'Main information'."</h3>\n";
		$str .= "\t<ul>\n";
		$str .= "\t\t<li>".'VO : '.$vo."</li>\n";
        if(isset($genres)) {
		    $str .= "\t\t<li>".'Genre(s) : '.htmlspecialchars($genres)."</li>\n";
        }
		$str .= "\t\t<li>".'Release date : '.$release."</li>\n";
		$str .= "\t\t<li>".'Runtime : '.$runtime.' min'."</li>\n";
		$str .= "\t\t<li>".'Grade : <strong>'.$vote.'</strong>/10 ('.$votecount.' votes)'."</li>\n";
		$str .= "\t</ul>\n";
		$str .= "\t</article>\n";
	}
	else {
		$str .= "\t<h3>".'Principales informations'."</h3>\n";
		$str .= "\t<ul>\n";
		$str .= "\t\t<li>".'VO : '.$vo."</li>\n";
        if(isset($genres)) {
		    $str .= "\t\t<li>".'Genre(s) : '.htmlspecialchars($genres)."</li>\n";
        }
		$str .= "\t\t<li>".'Date de sortie : '.$release."</li>\n";
		$str .= "\t\t<li>".'Durée : '.$runtime.' min'."</li>\n";
		$str .= "\t\t<li>".'Note : <strong>'.$vote.'</strong>/10 ('.$votecount.' votes)'."</li>\n";
		$str .= "\t</ul>\n";
		$str .= "\t</article>\n";
	}
	return $str;
}

function get_imdbid2(string $id) : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'/external_ids?api_key=c448da27062330876f0162320dd0f551');
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

function get_seriesactors(string $id, string $config, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'&append_to_response=credits');
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
	}
	else {
		$str .= "\t<h3>".'Acteurs principaux'."</h3>\n";
	}
	$str .= "\t\t<div class='actors'>\n";
	$i = 0;
	while(isset($actor[$i]) && $i < 3) {
		$str .= "\t\t<figure class='act$i'>\n";
		$str .= "\t\t\t<img height=\"270\" width=\"185\" src=\"".$config.$profile[$i]."\" alt=\"Acteur de la série\"/>\n";
		$str .= "\t\t\t<figcaption>\n";
		$str .= "\t\t\t<ul>\n";
		$str .= "\t\t\t\t<li><strong>".$actor[$i]."</strong></li>\n";
		$str .= "\t\t\t\t<li>".'('.$character[$i].')'."</li>\n";
		$str .= "\t\t\t</ul>\n";
		$str .= "\t\t\t</figcaption>\n";
		$str .= "\t\t</figure>\n";
		$i++;
	}
	$str .= "\t\t</div>\n";
	$str .= "\t</article>";
	return $str;
}

function get_seriesproduction(string $id, string $config, string $logoconfig, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'&append_to_response=credits');
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
			$logoname = $data['production_companies'][0]['name'];
            if(isset($data['credits']['crew'][0])) {
                $i = 0;
			    while(isset($data['credits']['crew'][$i]) && ($data['credits']['crew'][$i]['job'] != "Producer") ) {
				    $i++;
			    }
                if(isset($data['credits']['crew'][$i]) && $data['credits']['crew'][$i]['job'] == "Producer") {
				        $director = $data['credits']['crew'][$i]['name'];
                }
                else if(isset($data['credits']['crew'][$i]) && $data['credits']['crew'][$i]['job'] == "Exective Producer") {
				     $execdirector = $data['credits']['crew'][$i]['name'];
                }
                if(isset($data['credits']['crew'][$i]['profile_path'])) {
                    $directorprofile = $data['credits']['crew'][$i]['profile_path'];
                }
            }
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	$str .= "\t<h3>".'Production'."</h3>\n";
	$str .= "\t\t<div class='prod'>\n";
	if(isset($directorprofile) && isset($director)) {
		$str .= "\t\t<figure class='direct'>\n";
		$str .= "\t\t\t<img height=\"270\" width=\"185\" src=\"".$config.$directorprofile."\" alt=\"Director\"/>\n";
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
	else if(isset($directorprofile) && isset($execdirector)) {
		$str .= "\t\t<figure class='direct'>\n";
		$str .= "\t\t\t<img height=\"270\" width=\"185\" src=\"".$config.$directorprofile."\" alt=\"Executive Director\"/>\n";
		$str .= "\t\t\t<figcaption>\n";
		$str .= "\t\t\t<ul>\n";
		$str .= "\t\t\t\t<li><strong>".$execdirector."</strong></li>\n";
		if($lang == "en") {
			$str .= "\t\t\t\t<li>".'(Executive Director)'."</li>\n";	
		}
		else {
			$str .= "\t\t\t\t<li>".'(Réalisateur)'."</li>\n";
		}
		$str .= "\t\t\t</ul>\n";
		$str .= "\t\t\t</figcaption>\n";
		$str .= "\t\t</figure>\n";
    }
    else if(!isset($directorprofile) && isset($director)) {
		$str .= "\t\t\t<ul class='direct'>\n";
		if(!empty($director)){
			$str .= "\t\t\t\t<li><strong>".$director."</strong></li>\n";
		}
		if($lang == "en") {
			$str .= "\t\t\t\t<li>".'(Director)'."</li>\n";	
		}
		else {
			$str .= "\t\t\t\t<li>".'(Réalisateur)'."</li>\n";
		}
		$str .= "\t\t\t</ul>\n";
	}
    else if(!isset($directorprofile) && isset($execdirector)) {
        $str .= "\t\t\t<ul class='direct'>\n";
		if(!empty($director)){
			$str .= "\t\t\t\t<li><strong>".$execdirector."</strong></li>\n";
		}
		if($lang == "en") {
			$str .= "\t\t\t\t<li>".'(Executive Director)'."</li>\n";	
		}
		else {
			$str .= "\t\t\t\t<li>".'(Réalisateur)'."</li>\n";
		}
		$str .= "\t\t\t</ul>\n";
    }
    else {
        if($lang == "en") {
            $str .= "\t\t<p class='direct'>".'No production availabel for this tv serie.'."</p>\n";    
        }
        else {
            $str .= "\t\t<p class='direct'>".'Pas de production disponible pour cette série.'."</p>\n";
        }
    }

	if(isset($logo)) {
		$str .= "\t\t<figure class='logo'>\n";
		$str .= "\t\t\t<img src=\"".$logoconfig.$logo."\" alt=\"Logo de la série\"/>\n";
		$str .= "\t\t\t<figcaption>\n";
		$str .= "\t\t\t<ul>\n";
		if($lang == "en") {
			$str .= "\t\t\t\t<li><strong>".'Serie production'."</strong></li>\n";	
		}
		else {
			$str .= "\t\t\t\t<li><strong>".'Société de production'."</strong></li>\n";
		}
		$str .= "\t\t\t\t<li>".'('.$logoname.')'."</li>\n";
		$str .= "\t\t\t</ul>\n";
		$str .= "\t\t\t</figcaption>\n";
		$str .= "\t\t</figure>\n";
	}
	else {
		$str .= "\t\t\t<ul class='logo'>\n";
		if($lang == "en") {
			$str .= "\t\t\t\t<li><strong>".'Serie production'."</strong></li>\n";	
		}
		else {
			$str .= "\t\t\t\t<li><strong>".'Société de production'."</strong></li>\n";
		}
		$str .= "\t\t\t\t<li>".'('.$logoname.')'."</li>\n";
		$str .= "\t\t\t</ul>\n";
	}
	$str .= "\t\t</div>\n";
	$str .= "\t</article>\n";
	return $str;
}

function get_seriesrecommendations(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'/similar?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
				while(isset($data['results'][$i]['name']) && isset($data['results'][$i]['first_air_date'])) {
					$serie[$i] = $data['results'][$i]['name'];
					$date[$i] = $data['results'][$i]['first_air_date'];
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
	if(isset($serie[1])) {
		$str .= "\t\t\t<ul style=\"list-style: square inside;\" class=\"recolink\">\n";
		$i = 0;
		while(isset($serie[$i]) && $i < 10) {
			$str .= "\t\t\t<li><a href=\"serie.php?serie=".urlencode($serie[$i])."\">".htmlspecialchars($serie[$i]).' ('.$year[$i].')'."</a></li>\n";;
			$i++;
		}
		$str .= "\t\t\t</ul>\n";
	}
	else {
		if($lang == "en") {
			$str .= "\t\t<p style=\"margin: 10px;\">".'No recommendations available for this serie.'."</p>\n";
		}
		else {
			$str .= "\t\t<p style=\"margin: 10px;\">".'Pas de recommandations disponibles pour cette série.'."</p>\n";
		}
	}
	$str .= "\t</article>\n";
	return $str;
}

function serieGenre(string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/genre/tv/list?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
			for ($i = 0; $i < 16 ; $i++) {
				$value[$i] = $data['genres'][$i]['id'];
				$genrename[$i] = $data['genres'][$i]['name'];
			}
		}
	}
	curl_close($curl);

	$str = "\t\t<select name=\"sgenre\" id=\"sgenre\">\n";
	for ($i = 0; $i < 16; $i++) {
		$attributes = "";
		if(isset($_GET['sgenre']) && $_GET['sgenre'] == $value[$i]) {
			$attributes .= "selected='selected'";
		}
		else if(isset($_COOKIE['LastSerieGenre']) && $_COOKIE['LastSerieGenre'] == $value[$i]) {
			$attributes .= "selected='selected'";
		}
		$str .= "\t\t\t<option value='$value[$i]' $attributes>".htmlspecialchars($genrename[$i])."</option>\n";
	}
	$str .= "\t\t</select>\n";
	return $str;
}

function get_Seriealea(string $genre, string $year) : string {
	$curl = curl_init('https://api.themoviedb.org/3/discover/tv?api_key=c448da27062330876f0162320dd0f551&sort_by=popularity.desc&with_genres='.$genre.'&first_air_date_year='.$year.'');
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

function get_episodeinfo(string $episode, string $saison, string $id, string $lang, string $config) : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'/season/'.$saison.'/episode/'.$episode.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
			$sortie = $data['air_date'];
			$name = $data['name'];
			$resume = $data['overview'];
			$vote = $data['vote_average'];
			$votecount = $data['vote_count'];
			$poster = $data['still_path'];
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t<h3>".'Main information of the episode'."</h3>\n";
		$str .= "\t<ul>\n";
		$str .= "\t\t<li>".'Release date : '.$sortie."</li>\n";
		$str .= "\t\t<li>".'Title : '.$name.' min'."</li>\n";
		$str .= "\t\t<li>".'Overview : '.$resume. "</li>\n";
		$str .= "\t\t<li>".'Grade : <strong>'.$vote.'</strong>/10 ('.$votecount.' votes)'."</li>\n";
		$str .= "\t\t\t<figure class='affiche'>\n";
		$str .= "\t\t\t<img src=\"".$config.$poster."\" alt=\"Affiche de la série\"/>\n";
		$str .= "\t\t\t<figcaption><strong>Image of the episode</strong></figcaption>\n";
		$str .= "\t\t</figure>\n";
		$str .= "\t</ul>\n";
		$str .= "\t</article>\n";
	}
	else {
		$str .= "\t<h3>".'Principales informations de l&apos;épisode'."</h3>\n";
		$str .= "\t<ul>\n";
		$str .= "\t\t<li>".'Date de sortie : '.$sortie."</li>\n";
		$str .= "\t\t<li>".'Titre : '.$name."</li>\n";
		$str .= "\t\t<li>".'Résumé : '.$resume. "</li>\n";
		$str .= "\t\t<li>".'Note : <strong>'.$vote.'</strong>/10 ('.$votecount.' votes)'."</li>\n";
		$str .= "\t\t\t<figure class='affiche'>\n";
		$str .= "\t\t\t<img src=\"".$config.$poster."\" alt=\"Affiche de la série\"/>\n";
		$str .= "\t\t\t<figcaption><strong>Image de l&apos;épisode</strong></figcaption>\n";
		$str .= "\t\t</figure>\n";
		$str .= "\t</ul>\n";
		$str .= "\t</article>\n";
	}
	return $str;
}

function get_fulloverviewseries(string $imdbid) : string {
	$xml = simplexml_load_file('http://www.omdbapi.com/?apikey=edb13aa4&i='.$imdbid.'&plot=full&r=xml');

	$fulloverview = $xml->movie['plot'];

	$str = "\t<article>\n";
	$str .= "\t\t<h3>".'Full Overview'."</h3>\n";
	$str .= "\t\t\t<p style=\"margin: 10px;\">".$fulloverview."</p>\n";
	$str .= "\t</article>\n";
	return $str;
}

function get_seasonposter(string $saison, string $id, string $lang, string $config, string $name) : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'/season/'.$saison.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
			$title = $data['name'];
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t\t<h3>".'Results for the season '.$saison.' of : <em>'.htmlspecialchars($name)."</em></h3>";
		$alt = 'Season poster';
	}
	else {
		$str .= "\t\t<h3>".'Résultats pour la saison '.$saison.' de : <em>'.htmlspecialchars($name)."</em></h3>\n";
		$alt = 'Affiche de la saison';
	}
	$str .= "\t\t\t<figure style='text-align: center;'>\n";
	$str .= "\t\t\t<img height=\"270\" width=\"185\" src=\"".$config.$poster."\" alt=\"$alt\"/>\n";
	$str .= "\t\t\t<figcaption style='margin-top: 10px;'><strong>".$title."</strong></figcaption>\n";
	$str .= "\t\t</figure>\n";
	$str .= "\t</article>\n";
	return $str;
}

function get_maininfoserie(string $id, string $saison, string $lang) : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'/season/'.$saison.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
			$str = "\t<article>\n";
			if($lang == "en") {
				$str .= "\t<h3>".'Season information'."</h3>\n";
				$str .= "\t<ul>\n";
				$str .= "\t\t<li>".'Release date : '.$data['air_date']."</li>\n";
				$str .= "\t\t<li class=\"episode\">".'Overview : '.$data['overview']."</li>\n";
				$str .= "\t</ul>\n";
			}
			else {
				$str .= "\t<h3>".'Information de la saison'."</h3>\n";
				$str .= "\t<ul>\n";
				$str .= "\t\t<li>".'Date de sortie : '.dateformatfr($data['air_date'])."</li>\n";
				$str .= "\t\t<li class=\"episode\">".'Résumé : '.$data['overview']."</li>\n";
				$str .= "\t</ul>\n";
			}
			$str .= "\t</article>\n";
		}
	}
	return $str;
}

function get_genreseries(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
				$genres = Seriesgenre($genre1, $lang);
			}
			$i = 1;
			while(isset($data['genres'][$i]['id'])) {
				$genre = $data['genres'][$i]['id'];
				$genres .= ', '.Seriesgenre($genre, $lang);
				$i++;
			}
		}
	}
	curl_close($curl);
	return $genres;
}

function get_titleseries(string $id) : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language=fr');
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
			$title = $data['name'];
		}
	}
	curl_close($curl);
	return $title;	
}

function serietitleen(string $serie) : string {
	$serieencode = urlencode($serie);
	$curl = curl_init('https://api.themoviedb.org/3/search/tv?api_key=c448da27062330876f0162320dd0f551&query='.$serieencode.'&language=en');
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
			$new = $data['results'][0]['name'];
		}
	}
	curl_close($curl);
	return $new;
}

function get_topseriesposter(string $series, string $config, string $lang="fr") : string {
	$serieencode = urlencode($series);
	$curl = curl_init('https://api.themoviedb.org/3/search/tv?api_key=c448da27062330876f0162320dd0f551&query='.$serieencode.'&language='.$lang.'');
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
			$title = $data['results'][0]['name'];
			$poster = $data['results'][0]['poster_path'];
		}
	}
	curl_close($curl);
	if($lang == "en") {
		$alt = "poster top series";
	}
	else {
		$alt = "affiche top series";
	}
	$str = "\t\t<li>\n";
	$str .= "\t\t<div class=\"moviestop\">\n";
	$str .= "\t\t\t\t<a href=\"serie.php?serie=".urlencode($title)."\"><img width=\"120\" height=\"160\" src=\"".$config.$poster."\" alt=\"$alt\"/>\n";
	$str .= "\t\t\t\t<span>".htmlspecialchars($title)."</span></a>\n";
	$str .= "\t\t</div>\n";
	$str .= "\t\t</li>\n";
	return $str;
}

function seriesgenreen(array $genre) : array {
	$genreEN = array("Action & Aventure" => "Action & Adventure", "Animation" => "Animation", "Drame" => "Drama", "Comédie" => "Comedy", "Western" => "Western","Crime" => "Crime", "Documentaire" => "Documentary", "Kids" => "Kids" ,
	"Mystère" => "Mystery", "Familial" => "Family" , "News" => "News" , "Reality" => "Reality", "Science-Fiction & Fantastique" => "Sci-Fi & Fantasy" , "Soap" => "Soap" , "Talk" => "Talk", "War & Politics" => "War & Politics");

	foreach ($genre as $element) {
		$new[] = $genreEN[$element];
	}
	return $new;
}

function get_seasoninfoepisodes(string $saison, string $id, string $config ,string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'/season/'.$saison.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
			if(isset($data['episodes'][0])) {
				$i = 0;
				$str = "\t<article>\n";
				if($lang == "fr"){
					while(isset($data['episodes'][$i]) ) {
						$str .= "\t<h3>".'Episode '.$data['episodes'][$i]['episode_number']. "</h3>\n";
						$str .= "\t\t<figure class='container2' style='padding: 1em;'>\n";
						$str .= "\t\t\t<img width=\"320\" height=\"190\" src=\"".$config.$data['episodes'][$i]['still_path']."\" alt=\"Image de l'épisode\"/>\n";
						$str .= "\t\t</figure>\n";
						$str .= "\t<ul>\n";
						$str .= "\t\t<li>".'Date de sortie : '.dateformatfr($data['episodes'][$i]['air_date'])."</li>\n";
						$str .= "\t\t<li>".'Titre : '.$data['episodes'][$i]['name']."</li>\n";
						$str .= "\t\t<li class=\"episode\">".'Résumé : '.$data['episodes'][$i]['overview']."</li>\n";
						$str .= "\t</ul>\n";
						$i++;
					}
				}
				else {
					while(isset($data['episodes'][$i]) ) {
						$str .= "\t<h3>".'Episode '.$data['episodes'][$i]['episode_number']. "</h3>\n";
						$str .= "\t\t<figure class='container2' style='padding: 1em;'>\n";
						$str .= "\t\t\t<img width=\"320\" height=\"190\" src=\"".$config.$data['episodes'][$i]['still_path']."\" alt=\"Image of the episode\"/>\n";
						$str .= "\t\t</figure>\n";
						$str .= "\t<ul>\n";
						$str .= "\t\t<li>".'Release date : '.$data['episodes'][$i]['air_date']."</li>\n";
						$str .= "\t\t<li>".'Title : '.$data['episodes'][$i]['name']."</li>\n";
						$str .= "\t\t<li class=\"episode\">".'Overview : '.$data['episodes'][$i]['overview']."</li>\n";
						$str .= "\t</ul>\n";
						$i++;
					}
				}
				$str .= "\t</article>\n";
			}
		}
	}
	curl_close($curl);

	return $str;
}

function get_seasonlink(string $id, string $lang="fr") : string {
	$curl = curl_init('https://api.themoviedb.org/3/tv/'.$id.'?api_key=c448da27062330876f0162320dd0f551&language='.$lang.'');
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
			$nbseason = $data['number_of_seasons'];
		}
	}
	curl_close($curl);

	$str = "\t<article>\n";
	if($lang == "en") {
		$str .= "\t\t<h3>".'Seasons'."</h3>\n";	
	}
	else {
		$str .= "\t\t<h3>".'Saisons'."</h3>\n";
	}
	if(isset($nbseason)) {
		if($nbseason == 1) {
			$str .= "\t\t\t<ul style=\"list-style: square inside; margin: 10px;\" class=\"recolink\">\n";
		}
		else {
			$str .= "\t\t\t<ul style=\"list-style: square inside; column-count: 2; margin: 10px;\" class=\"recolink\">\n";
		}		
		$i = 1;
		while($i <= $nbseason) {
			if($lang == "fr") {
				$str .= "\t\t\t\t<li><a href=\"saisons.php?saison=".$i."&amp;idserie=".$id."\">".'Saison '.$i."</a></li>\n";
			}
			else {
				$str .= "\t\t\t\t<li><a href=\"saisons.php?saison=".$i."&amp;idserie=".$id."\">".'Season '.$i."</a></li>\n";
			}
			$i++;
		}
		$str .= "\t\t\t</ul>\n";
	}
	else {
		if($lang == "en") {
			$str .= "\t\t<p>".'No seasons available for this serie.'."</p>\n";
		}
		else {
			$str .= "\t\t<p>".'Pas de saisons disponibles pour cette série.'."</p>\n";
		}
	}	
	$str .= "\t</article>\n";
	return $str;
}

?>