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
if(isset($_COOKIE['Lang']) && !empty($_COOKIE['Lang'])) {
	$lang = $_COOKIE['Lang'];
	require_once "./include/$lang.inc.php";		
}
else {
	require_once "./include/fr.inc.php";	
}

//Choix des meta-données de la page.
$title = "CinéFlix - $titleindex";
$description = "$descindex"; 
$keywords = "$keyindex";

//Inclusion du fichier comportant le header.
require_once "./include/header.inc.php";
?>
		<div class="sidebar">
			<h2>CinéFlix &#x00A0;<i class="fas fa-home"></i></h2>
				<ul>
					<li><a href="#pres"><i class="fas fa-door-open"></i><?php echo $pres; ?></a></li>
				</ul>
		</div>

		<section id="pres">
			<h2><?php echo $presh2; ?></h2>
				<div class="divindex">	
					<figure>		
						<img id="img" src="images/films.jpg" alt="<?php echo $altindex; ?>"/>
					</figure>
					<p><?php echo $txtpres; ?></p>
				</div>
		</section>

		<div class="exp">
			<div class="film">
				<figure>
					<a href="film.php">
					<img id="films" style="width: 140pt; height: 180pt;" src="images/film.jpeg" alt="<?php echo $fi; ?>"/>
					</a>
				</figure>
				<ul>
					<li><?php echo $ftxt1; ?></li>
					<li><?php echo $ftxt2; ?></li> 
					<li><?php echo $ftxt3; ?></li>
				</ul>
			</div>		

			<div class="serie">
				<figure>
					<a href="serie.php">
					<img id="serie" style="width: 140pt; height: 180pt;" src="images/serie.jpg" <?php echo "alt=\"$se\""; ?>/>
					</a>
				</figure>
				<ul>
					<li><?php echo $stxt1; ?></li>
					<li><?php echo $stxt2; ?></li>
					<li><?php echo $stxt3; ?></li>
				</ul>
			</div>
		</div>
<?php
require_once "./include/footer.inc.php";
?>
