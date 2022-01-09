<?php
//Inclusion du fichier comportant les fonctions.
require_once "./include/functions.series.inc.php";

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

if(isset($_GET['serie']) && !empty($_GET['serie'])) {
	$serieapi = urlencode($_GET['serie']);
	$seriename = htmlspecialchars($_GET['serie']);
	$serieid = get_idseries($serieapi);
	setcookie('LastSerie', "$seriename", $arr_cookie_options);
	setcookie('Serie', "$serieid", $arr_cookie_options);
	$_COOKIE['LastSerie'] = $seriename;
	$_COOKIE['Serie'] = $serieid;
}
else if(isset($_GET['sgenre'], $_GET['year']) && !empty($_GET['sgenre']) && !empty($_GET['year'])) {
	$seriegenre = $_GET['sgenre'];
	$serieyear = $_GET['year'];
	$serieid = get_Seriealea($seriegenre, $serieyear);
	setcookie('LastSerieGenre', "$seriegenre", $arr_cookie_options);
	$_COOKIE['LastSerieGenre'] = $seriegenre;
	setcookie('LastSerieYear', "$serieyear", $arr_cookie_options);
	$_COOKIE['LastSerieYear'] = $serieyear;
	setcookie('Serie', "$serieid", $arr_cookie_options);
	$_COOKIE['Serie'] = $serieid;
}

if(isset($_GET['stype']) && !empty($_GET['stype'])) {
	$type = $_GET['stype'];
	setcookie('SType', "$type", $arr_cookie_options);
	$_COOKIE['SType'] = $type;
}

if(isset($_GET['fuseau']) && !empty($_GET['fuseau'])) {
	$fuseau = $_GET['fuseau'];
	setcookie('Fuseau', "$fuseau", $arr_cookie_options);
	$_COOKIE['Fuseau'] = $fuseau;
}

//Choix des meta-données de la page.
$title = "CinéFlix - $sh2";
$description = "$descseries"; 
$keywords = "$keyseries";

//Inclusion du fichier comportant le header.
require_once "./include/header.inc.php";
?>
	<div class="sidebar">
		<h2>CinéFlix &#x00A0;<i class="fas fa-film"></i></h2>
			<ul>
				<li><a href="#rechF"><i class="fas fa-search"></i><?php echo $rec; ?></a></li>
				<li><a href="#trend"><i class="fas fa-chart-line"></i><?php echo $stend; ?></a></li>
				<li><a href="#infom"><i class="fas fa-info"></i><?php echo $info; ?></a></li>
			</ul>
	</div>
	
	<section id="rechF">
		<h2><?php echo $sh2; ?></h2>
			<div class="typeform">
				<form action="serie.php" method="get">
				<h3><?php echo $radio ?></h3>
					<div class="type">
						<div class="group">
							<input type="radio" name="stype" id="rb1" value="part" onclick="this.form.submit();" checked="checked"/>
							<label for="rb1"><?php echo $radio1 ?></label>
							<input type="radio" name="stype" id="rb2" value="all" onclick="this.form.submit();" <?php if (isset($_GET['stype']) && $_GET['stype'] == "all") { echo "checked='checked'";} else if (isset($_COOKIE['SType']) && $_COOKIE['SType'] == "all") { echo "checked='checked'";} ?> />
							<label for="rb2"><?php echo $radio2 ?></label>
						</div>
					</div>
				</form>
			</div>
			<div class="container">
				<div class="formulaires">
					<form action="serie.php" method="get">
						<fieldset>
							<legend><?php echo $fform1; ?></legend>
							<div class="c100">
								<div class="form__group field">
									<input type="text" class="form__field" placeholder="<?php echo $sform1ph; ?>" name="serie" id='sname' required="required" value="<?php if (isset($_GET['serie'])) { echo htmlspecialchars($_GET['serie']);} else if(isset($_COOKIE['LastSerie']) ) { echo $_COOKIE['LastSerie'];} ?>" />
									<label for="sname" class="form__label"><?php echo $sform1ph; ?></label>
								</div>
							</div>
							<div class="c100">
								<input type="submit" value="<?php echo $submit; ?>"/>
							</div>
						</fieldset>
					</form>
				
					<form action="serie.php" method="get">
						<fieldset>
							<legend><?php echo $fform3; ?></legend>
								<div class="c100">
									<div class="select-custom">
										<label for="sgenre"><?php echo $sform3opt1; ?></label>
										<?php
										echo serieGenre($lang);
										?>
									</div>
								</div>
								<div class="c100">
									<div class="select-custom">
										<label for="sdate"><?php echo $sform3opt2; ?></label>
										<select name="year" id="sdate">
										<?php
										$year = date("Y");
										for($j = $year ; $j >= 1980 ; $j--) {
												$attributes = "";
												if(isset($_GET['year']) && $_GET['year'] == $j) {
													$attributes .= "selected='selected'";
												}
												else if(isset($_COOKIE['LastSerieYear']) && $_COOKIE['LastSerieYear'] == $j) {
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
			$logoconfig = get_logoconfig();
			if(isset($_COOKIE['Serie']) && !empty($_COOKIE['Serie'])) {
				$id = $_COOKIE['Serie'];
				stockage2($id);
				if(isset($_COOKIE['SType']) && $_COOKIE['SType'] == "all") {
					$imdbid = get_imdbid2($id);
					echo "\t<div class='results'>\n";
					echo "\t<div class='poster'>\n";
					echo get_posterseries($id, $posterconfig, $lang);
					echo get_backdropseries($id, $backdropconfig, $lang);
					echo "\t</div>\n";
					if($lang == "en") {
						echo get_fulloverviewseries($imdbid);
					}else {
						echo get_overviewseries($id, $lang);
					}
					echo get_seriesactors($id, $profileconfig, $lang);
					echo get_seriesproduction($id, $profileconfig, $logoconfig, $lang);
					echo get_infoseries($id, $lang);
					echo get_seasonlink($id, $lang);
					echo get_seriesrecommendations($id, $lang);
					echo "\t</div>\n";
				}
				else {
					echo "\t<div class='results'>\n";
					echo "\t<div class='poster'>\n";
					echo get_posterseries($id, $posterconfig, $lang);
					echo get_backdropseries($id, $backdropconfig, $lang);
					echo "\t</div>\n";
					echo get_overviewseries($id, $lang);
					echo get_seriesactornames($id, $lang);
					echo get_infoseries($id, $lang);
					echo get_seasonlink($id, $lang);
					echo "\t</div>\n";
				}
			}
			else {
				echo "\t<div class=\"noserie\">\n";
				echo "\t</div>\n";
			}							
			?>
		</div>
	</section>
	
	<section id="trend">
		<h2><?php echo $stend; ?></h2>
		<?php
		$posterconfig = get_posterconfig();
		echo "\t<div class='trending'>\n";
		echo get_trendingseries('day' ,$posterconfig, $lang);
		echo get_trendingseries('week' ,$posterconfig, $lang);
		echo "\t</div>\n";
		?>		
	</section>
	
	<section id="infom">
		<h2><?php echo $info; ?></h2>
		<?php
		$posterconfig = get_posterconfig();
		echo get_popularseries($posterconfig, $lang);
		echo get_topratedseries($posterconfig, $lang);
		?>
		<form action="serie.php#live" method="get" id="live">
			<fieldset>
				<legend><?php echo $splay; ?></legend>
					<div class="c100">
						<div class="select-custom">
							<label for="fuseau"><?php echo $sformplay; ?></label>
							<select name="fuseau" id="fuseau">
								<option value="UTC+1" <?php if (isset($_GET['fuseau']) && $_GET['fuseau'] == "UTC+1") { echo "selected='selected'";} else if (isset($_COOKIE['Fuseau']) && $_COOKIE['Fuseau'] == "UTC+1") { echo "selected='selected'";} ?>><?php echo $france." (Paris)" ?></option>
								<option value="UTC" <?php if (isset($_GET['fuseau']) && $_GET['fuseau'] == "UTC") { echo "selected='selected'";} else if (isset($_COOKIE['Fuseau']) && $_COOKIE['Fuseau'] == "UTC") { echo "selected='selected'";} ?>><?php echo $runi." ".$lon ?></option>
								<option value="UTC-8" <?php if (isset($_GET['fuseau']) && $_GET['fuseau'] == "UTC-8") { echo "selected='selected'";} else if (isset($_COOKIE['Fuseau']) && $_COOKIE['Fuseau'] == "UTC-8") { echo "selected='selected'";} ?>><?php echo $etatsu." (Los Angeles)" ?></option>								
							</select>
						</div>
					</div>
					<div class="c100">
						<input type="submit" value="<?php echo $submit; ?>"/>
					</div>
			</fieldset>
		</form>
		<?php
		if(isset($_COOKIE['Fuseau']) && !empty($_COOKIE['Fuseau']) ) {
			$fuseau = $_COOKIE['Fuseau'];
			echo "\t<div class='cine'>\n";
			echo get_airing('airing_today', $lang, "$fuseau");
			echo get_airing('on_the_air', $lang, "$fuseau");
			echo "\t</div>\n";
		}
		?>
	</section>

<?php
require_once "./include/footer.inc.php";
?>
