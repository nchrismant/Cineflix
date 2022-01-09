<?php
//Inclusion du fichier util inclus dans le fichier footer.
require_once "./include/util.inc.php";
?>
	<footer>
		
			<!-- Logo de l'université. -->
			<figure>
				<img id="cyulogo" width="150" height="60" src="images/logoCYU.png" alt="<?php echo $falt; ?>"/>
			</figure>

		<ul>		
		<?php
		if(isset($_COOKIE['Lang'])) {
			//Appel fonction languedate() affichant soit la version francaise (?lang=fr) ou anglaise (?lang=en).
			echo get_date($lang);
		}
		else {
			//Appel fonction languedate() affichant la date version française(par défaut) si aucun paramètre dans le lien de la page n'est donné.//
			echo get_date();
		}	

		//Appel fonction nombre_visites() affichant le nombre de visiteurs.
		if(!isset($_COOKIE['Visited'])) {
			ajouter_visite();
		}
		$visites = nombre_visites();
		echo "\t\t<li>".$fvisits.' : '.$visites."</li>\n";
		?>
		</ul>

			<!-- Lien vers le haut de la page actuelle -->
			<noscript>
				<nav>	
					<a id="haut" href="#"><?php echo $ftop; ?></a>
				</nav>
			</noscript>
			<div id="scrolltotop">
				<div></div>
			</div>	
	</footer>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script>
		function scroll_to(e){
			e > 500 ? jQuery("#scrolltotop").fadeIn() : jQuery("#scrolltotop").fadeOut()
		}
		jQuery(document).ready(function(){
			scroll_to($(this).scrollTop()),
			jQuery(window).scroll(function(){
				scroll_to($(this).scrollTop())
			}),
			jQuery("#scrolltotop").on("click",function(){
				return jQuery("html, body").animate({scrollTop:0},1200),!1
			})
		})			
	</script>
	
</body>
</html>