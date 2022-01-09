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

//Choix des meta-données de la page.
$title = "CinéFlix - $titleinfo";
$description = "$descinfo"; 
$keywords = "$keyinfo";

//Inclusion du fichier comportant le header.
require_once "./include/header.inc.php";
//Inclusion du fichier comportant les fonctions du sites
require_once "./include/functions.inc.php";
//Inclusion du fichier comportant les fonctions du sites
require_once "./include/functions.series.inc.php";
?>
    <div class="sidebar">
		<h2>CinéFlix &#x00A0;<i class="fas fa-info-circle"></i></h2>
			<ul>
				<li><a href="#stats"><i class="fas fa-chart-bar"></i><?php echo $stat; ?></a></li>
                <li><a href="#cred"><i class="fas fa-book-reader"></i><?php echo $ih2c; ?></a></li>
			</ul>
	</div>

    <section id="stats">
        <h2><?php echo $ih2; ?></h2>
            <article>
                <h3><?php echo $ifilms; ?></h3>
                    <div class="container2">
                        <figure>
                            <img width="900" src="topfilmimg.php" alt="<?php echo $ialt3; ?>"/>
                        </figure>
                    </div>
                    <div class="topf">
                        <?php 
                        $posterconfig = get_posterconfig();
                        $films = counttopmovies();
                        $film = array_keys($films);
                        echo "\t<ul class=\"postop\">\n";
                        $ord = array(3,1,0,2,4);
                        foreach($ord as $num) {
                            echo get_topfilmsposter($film[$num], $posterconfig, $lang);
                        }
                        echo "\t</ul>\n";
                        ?>
                    </div>                     
                    <div class="mtop">
                        <img src="typeimg.php" alt="<?php echo $ialt2; ?>"/>
                        <img id="genre" src="genresfilm.php" alt="<?php echo $ialt4; ?>"/>
                    </div>
            </article>

            <article>
                <h3><?php echo $iseries; ?></h3>
                    <div class="container2">
                        <figure>
                            <img width="900" src="topseries.php" alt="<?php echo $ialt5; ?>"/>
                        </figure>
                    </div>
                    <div class="topf">
                        <?php 
                        $posterconfig = get_posterconfig();
                        $films = counttopseries();
                        $film = array_keys($films);
                        echo "\t<ul class=\"postop\">\n";
                        $ord = array(3,1,0,2,4);
                        foreach($ord as $num) {
                            echo get_topseriesposter($film[$num], $posterconfig, $lang);
                        }
                        echo "\t</ul>\n";
                        ?>
                    </div>   
                    <div class="mtop">                    
                        <img src="typeserieimg.php" alt="<?php echo $ialt2; ?>"/>
                        <img id="genre2" src="genreserie.php" alt="<?php echo $ialt4; ?>"/>
                    </div>
            </article>

            <article>
                <h3><?php echo $ivisits; ?></h3>
                <div class="parent">
                    <div class="div1">
                        <figure>
                            <img src="visiteurimg.php" alt="<?php echo $ialt1; ?>"/>
                        </figure>
                    </div>
                    <div class="div2">
                        <div class="geov">
                        <?php echo ServerPos($lang); ?>
                        </div>
                    </div>
                </div>
            </article>
    </section>

    <section id="cred">
        <h2><?php echo $ih2c; ?></h2>
            <article>
                <h3><?php echo $iaut; ?></h3>
                    <div class="autors">
                        <div class="bio">
                            <ul>
                                <li><strong>CHRIQUI Nathan</strong></li>
                                <li><?php echo $iabout; ?></li>
                            </ul>
                            <p><?php echo $ibio1; ?></p>
                        </div>
                        <div class="bio">
                            <ul>
                                <li><strong>AFATCHAWO Junior</strong></li>
                                <li><?php echo $iabout; ?></li>
                            </ul>
                            <p><?php echo $ibio2; ?></p>
                        </div>
                    </div>
            </article>

            <article>
                <h3><?php echo $ispe; ?></h3>
                    <div class="container2">
                        <div class="credit">
                            <ul>
                                <li><?php echo $iapi1; ?></li>
                                <li><?php echo $iapi2; ?></li>
                                <li><?php echo $iapi3; ?></li>
                                <li><?php echo $iapi4; ?></li>
                            </ul>
                        </div>
                    </div>                        
                    <div class="container2">
                        <div class="nasa">
                        <?php echo NasaPic($lang); ?>
                        </div> 
                    </div>
            </article>               
    </section>
    
<?php
require_once "./include/footer.inc.php";
?>