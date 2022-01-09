<?php
$arr_cookie_options = array (
	'expires' => time() + 60*60*24*30,
	'path' => '/',
	);
    
if(isset($_GET['lang'])) {
	$lang = htmlspecialchars($_GET['lang']);
	setcookie('Lang', "$lang", $arr_cookie_options);
	$_COOKIE['Lang'] = $lang;
}
else {
	$lang = "fr";
}

if(isset($_COOKIE['Lang']) && !empty($_COOKIE['Lang'])) {
	$lang = $_COOKIE['Lang'];
	require_once "./include/$lang.inc.php";		
}
else {
	require_once "./include/fr.inc.php";	
}
if(isset($_GET['saison']) && !empty($_GET['saison'])) {
	$season = $_GET['saison'];
	setcookie('LastSeason', "$season", $arr_cookie_options);
	$_COOKIE['LastSeason'] = $season;
}
if(isset($_GET['idserie']) && !empty($_GET['idserie'])) {
	$idserie = $_GET['idserie'];
	setcookie('LastIdSerie', "$idserie", $arr_cookie_options);
	$_COOKIE['LastIdSerie'] = $idserie;
}

//Choix des meta-données de la page.
$title = "CinéFlix - $sh2 - $seasonh2";
$description = "$descseason"; 
$keywords = "$keyseason";

//Inclusion du fichier comportant le header.
require_once "./include/header.inc.php";
//Inclusion du fichier comportant les fonctions du sites
require_once "./include/functions.inc.php";
//Inclusion du fichier comportant les fonctions du sites
require_once "./include/functions.series.inc.php";
?>
	<div class="sidebar">
		<h2>CinéFlix &#x00A0;<i class="fas fa-film"></i></h2>
			<ul>
				<li><a href="#seas"><i class="fas fa-video"></i><?php echo $seasonh2; ?></a></li>
			</ul>
	</div>
	
	<section>
		<h2 id="seas"><?php echo $seasonh2; ?></h2>
			<a href="serie.php">
				<div class="back"><i class="fas fa-arrow-left"></i></div>
			</a>
		<?php
			$posterconfig = get_posterconfig();
			$profileconfig = get_profileconfig();
			$backdropconfig = get_backdropconfig();
			$logoconfig = get_logoconfig();
			if(isset($_COOKIE['LastSeason']) && isset($_COOKIE['LastIdSerie'])) {
				$season = $_COOKIE['LastSeason'];
				$id = $_COOKIE['LastIdSerie'];
				$name = get_titleseries($id);
				echo get_seasonposter($season, $id, $lang, $posterconfig, $name);
				echo get_maininfoserie($id, $season, $lang);
				echo get_seasoninfoepisodes($season, $id, $backdropconfig, $lang);
			}
		?>
	</section>
	
<?php
require_once "./include/footer.inc.php";
?>