<?php
//Inclusion du fichier comportant les fonctions.
require_once "./include/functions.inc.php";

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

if(isset($_GET['film']) && !empty($_GET['film'])) {
	$filmapi = urlencode($_GET['film']);
	$filmname = htmlspecialchars($_GET['film']);
	$filmid = get_idmovies($filmapi);
	setcookie('LastFilm', "$filmname", $arr_cookie_options);
	setcookie('Film', "$filmid", $arr_cookie_options);
	$_COOKIE['LastFilm'] = $filmname;
	$_COOKIE['Film'] = $filmid;
}
else if(isset($_GET['id']) && !empty($_GET['id'])) {
	$filmid = htmlspecialchars($_GET['id']);
	setcookie('LastFilmID', "$filmid", $arr_cookie_options);
	setcookie('Film', "$filmid", $arr_cookie_options);
	$_COOKIE['LastFilmID'] = $filmid;
	$_COOKIE['Film'] = $filmid;
}
else if(isset($_GET['genre'], $_GET['y']) && !empty($_GET['genre']) && !empty($_GET['y'])) {
	$filmgenre = $_GET['genre'];
	$filmyear = $_GET['y'];
	$filmid = get_alea($filmgenre, $filmyear);
	setcookie('LastFilmGenre', "$filmgenre", $arr_cookie_options);
	$_COOKIE['LastFilmGenre'] = $filmgenre;
	setcookie('LastFilmYear', "$filmyear", $arr_cookie_options);
	$_COOKIE['LastFilmYear'] = $filmyear;
	setcookie('Film', "$filmid", $arr_cookie_options);
	$_COOKIE['Film'] = $filmid;
}

if(isset($_GET['type']) && !empty($_GET['type'])) {
	$type = $_GET['type'];
	setcookie('Type', "$type", $arr_cookie_options);
	$_COOKIE['Type'] = $type;
}

if(isset($_GET['region']) && !empty($_GET['region'])) {
	$region = $_GET['region'];
	setcookie('Region', "$region", $arr_cookie_options);
	$_COOKIE['Region'] = $region;
}

//Choix des meta-données de la page.
$title = "CinéFlix - $fh2";
$description = "$descfilms"; 
$keywords = "$keyfilms";

//Inclusion du fichier comportant le header.
require_once "./include/header.inc.php";
?>
	<div class="sidebar">
		<h2>CinéFlix &#x00A0;<i class="fas fa-film"></i></h2>
			<ul>
				<li><a href="#rechF"><i class="fas fa-search"></i><?php echo $rec; ?></a></li>
				<li><a href="#trend"><i class="fas fa-chart-line"></i><?php echo $ftend; ?></a></li>
				<li><a href="#infom"><i class="fas fa-info"></i><?php echo $info; ?></a></li>
			</ul>
	</div>

	<section id="rechF">
		<h2><?php echo $fh2; ?></h2>
			<div class="typeform">
				<form action="film.php" method="get">
					<h3><?php echo $radio ?></h3>
						<div class="type">
							<div class="group">
								<input type="radio" name="type" id="rb1" value="part" onclick="this.form.submit();" checked="checked"/>
								<label for="rb1"><?php echo $radio1 ?></label>
								<input type="radio" name="type" id="rb2" value="all" onclick="this.form.submit();" <?php if (isset($_GET['type']) && $_GET['type'] == "all") { echo "checked='checked'";} else if (isset($_COOKIE['Type']) && $_COOKIE['Type'] == "all") { echo "checked='checked'";} ?> />
								<label for="rb2"><?php echo $radio2 ?></label>
							</div>
						</div>
				</form>
			</div>
			<div class="container">
				<div class="formulaires">
					<form action="film.php" method="get">
						<fieldset>
							<legend><?php echo $fform1; ?></legend>
								<div class="c100">
									<div class="form__group field">
										<input type="text" class="form__field" placeholder="<?php echo $fform1ph; ?>" name="film" id='fname' required="required" value="<?php if (isset($_GET['film'])) { echo htmlspecialchars($_GET['film']);} else if(isset($_COOKIE['LastFilm']) ) { echo $_COOKIE['LastFilm'];} ?>" />
										<label for="fname" class="form__label"><?php echo $fform1ph; ?></label>
									</div>
								</div>
								<div class="c100">
									<input type="submit" value="<?php echo $submit; ?>"/>
								</div>
						</fieldset>
					</form>
					
					<form action="film.php" method="get">
						<fieldset>
							<legend><?php echo $fform2; ?></legend>
								<div class="c100">
									<div class="form__group field">
										<input type="text" class="form__field" placeholder="<?php echo $fform2ph; ?>" name="id" id='fid' required="required" value="<?php if (isset($_GET['id'])) { echo $_GET['id'];} else if (isset($_COOKIE['LastFilmID']) ) { echo $_COOKIE['LastFilmID'];} ?>" />
										<label for="fid" class="form__label"><?php echo $fform2ph; ?></label>
									</div>
								</div>
								<div class="c100">
									<input type="submit" value="<?php echo $submit; ?>"/>
								</div>
						</fieldset>
					</form>
		
					<form action="film.php" method="get">
						<fieldset>
							<legend><?php echo $fform3; ?></legend>
								<div class="c100">
									<div class="select-custom">
										<label for="fgenre"><?php echo $fform3opt1; ?></label>
										<?php
										echo optGenre($lang);
										?>
									</div>
								</div>
								<div class="c100">
									<div class="select-custom">
										<label for="fdate"><?php echo $fform3opt2; ?></label>
										<select name="y" id="fdate">
										<?php
										$year = date("Y");
										for($j = $year ; $j >= 1980 ; $j--) {
												$attributes = "";
												if(isset($_GET['y']) && $_GET['y'] == $j) {
													$attributes .= "selected='selected'";
												}
												else if(isset($_COOKIE['LastFilmYear']) && $_COOKIE['LastFilmYear'] == $j) {
													$attributes .= "selected='selected'";
												}
											echo "\t\t<option $attributes>".$j."</option>\n";
										}
										?>
										</select>
									</div>
								</div>
								<div class="c100">
									<input type="submit" value="<?php echo $submit; ?>"/>
								</div>
						</fieldset>
					</form>
				</div>
				<?php
				$posterconfig = get_posterconfig();
				$profileconfig = get_profileconfig();
				$backdropconfig = get_backdropconfig();
				$logoconfig= get_logoconfig();
				if(isset($_COOKIE['Film']) && !empty($_COOKIE['Film'])) {
					$id = $_COOKIE['Film'];
					stockage($id);
					if(isset($_COOKIE['Type']) && $_COOKIE['Type'] == "all") {
						$imdbid = get_imdbid($id);
						echo "\t<div class='results'>\n";
						echo "\t<div class='poster'>\n";
						echo get_postermovies($id, $posterconfig, $lang);
						echo get_backdropmovies($id, $backdropconfig, $lang);
						echo "\t</div>\n";
						if($lang == "en") {
							echo get_fulloverviewmovies($imdbid);
						}
						else {
							echo get_overviewmovies($id, $lang);
						}
						echo get_actors($id, $profileconfig, $lang);
						echo get_production($id, $profileconfig, $logoconfig, $lang);
						echo get_infomovies($id, $lang);
						echo get_recommendations($id, $lang);
						echo "\t</div>\n";
					}
					else {
						echo "\t<div class='results'>\n";
						echo "\t<div class='poster'>\n";
						echo get_postermovies($id, $posterconfig, $lang);
						echo get_backdropmovies($id, $backdropconfig, $lang);
						echo "\t</div>\n";
						echo get_overviewmovies($id, $lang);
						echo get_actornames($id, $lang);
						echo get_infomovies($id, $lang);
						echo "\t</div>\n";
					}
				}
				else {
					echo "\t<div class=\"nofilm\">\n";
					echo "\t</div>\n";
				}							
				?>
		</div>
	</section>

	<section id="trend">
		<h2><?php echo $ftend; ?></h2>
		<?php
		$posterconfig = get_posterconfig();
		echo get_trendingmovies('day' ,$posterconfig, $lang);
		echo get_trendingmovies('week' ,$posterconfig, $lang);
		?>		
	</section>

	<section id="infom">
		<h2><?php echo $info; ?></h2>
		<?php
		$posterconfig = get_posterconfig();
		echo get_popular($posterconfig, $lang);
		echo get_toprated($posterconfig, $lang);
		?>
		<form action="film.php#salles" method="get" id="salles">
			<fieldset>
				<legend><?php echo $fplay; ?></legend>
					<div class="c100">
						<div class="select-custom">
							<label for="region"><?php echo $fformplay; ?></label>
							<select name="region" id="region">
								<option value="fr" <?php if (isset($_GET['region']) && $_GET['region'] == "fr") { echo "selected='selected'";} else if (isset($_COOKIE['Region']) && $_COOKIE['Region'] == "fr") { echo "selected='selected'";} ?>><?php echo $france ?></option>
								<option value="us" <?php if (isset($_GET['region']) && $_GET['region'] == "us") { echo "selected='selected'";} else if (isset($_COOKIE['Region']) && $_COOKIE['Region'] == "us") { echo "selected='selected'";} ?>><?php echo $etatsu ?></option>
								<option value="gb" <?php if (isset($_GET['region']) && $_GET['region'] == "gb") { echo "selected='selected'";} else if (isset($_COOKIE['Region']) && $_COOKIE['Region'] == "gb") { echo "selected='selected'";} ?>><?php echo $runi ?></option>
							</select>
						</div>
					</div>
					<div class="c100">
						<input type="submit" value="<?php echo $submit; ?>"/>
					</div>
			</fieldset>
		</form>
		<?php
		if(isset($_COOKIE['Region']) && !empty($_COOKIE['Region']) ) {
			$region = $_COOKIE['Region'];
			echo "\t<div class='cine'>\n";
			echo get_playing('now_playing', $lang, $region);
			echo get_playing('upcoming', $lang, $region);
			echo "\t</div>\n";
		}		
		?>
	</section>

<?php
require_once "./include/footer.inc.php";
?>
