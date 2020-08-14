<!DOCTYPE html>
<html lang="en">
<head>
	<title>TeamSpeak 3 Webinterface | netzhost24.de</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Teamspeak 3 Webinterface von netzhost24.de. Administration der gesamten Instanz leicht gemacht." />
	<meta name="keywords" content="Teamspeak, Webinterface, Administration" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>

	<script>
		//TOOLTIP TOOGLE FÜR CHANNELÜBERSICHT
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip(); 
		});

		//NAVBAR DYNAMIC ACTIVE CLASS
		$(document).ready(function() {
			$('li.active').removeClass('active');
			$('a[href="' + location.pathname + '"]').closest('li').addClass('active'); 
		});

	</script>

	<script type="text/javascript">
		function myFunction() {
			var x = document.getElementById("myInput");
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		}
	</script>

<!-- Matomo -->
<script type="text/javascript">
  var _paq = _paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//piwik.netzhost24.de/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '2']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//piwik.netzhost24.de/piwik.php?idsite=2&amp;rec=1" style="border:0;" alt="" /></p></noscript>
<!-- End Matomo Code -->

</head>

<body>
<!-- PHP REQUIREMENT -->
<?php

	#REQUIREMENTS/INCLUDES
	date_default_timezone_set("Europe/Berlin"); // Timezone
	require("lib/ts3admin.class.php"); // TS3 PHP Library
	
	#INITIALIZE VARS
	ob_start();
	session_start();
	$site = $_GET["site"];

	if($_SESSION['loggedin'] == 1) {
		$tsAdmin = new ts3admin($_SESSION['ts3ip'], $_SESSION['ts3queryport']);
		$tsAdmin->connect();
		$tsAdmin->login($_SESSION['username'],$_SESSION['password']);
	}
	
?>

	<!-- HEADER -->
	<!-- NAVIGATION -->
	<div class="container-fluid">
		<img class="img-fluid" src="images/header/motd_2015_clear_back.png" alt="Header">
		<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<!-- Brand -->
			<a class="navbar-brand" href="index.php?site=home">netzhost24.de</a>

			<!-- Links -->
			<div class="collapse navbar-collapse" id="navbarTogglerDemo03">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="index.php?site=home">Home</a>
					</li>
					<?php
						if($_SESSION['loggedin'] == 1) {
					?>
						<li class="nav-item">
							<a class="nav-link" href="index.php?site=instanz">Instanz</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="index.php?site=server">Server</a>
						</li>
						<?php
							if(isset($_SESSION['vserver_port'])) {
						?>
							<li class="nav-item">
								<a class="nav-link" href="index.php?site=vserver">vServer</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="index.php?site=channel">Channel</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="index.php?site=token">Token</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="index.php?site=banlist">Bans</a>
							</li>
							<!-- Dropdown -->
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Gruppen</a>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="index.php?site=servergroups">Server</a>
									<a class="dropdown-item" href="index.php?site=channelgroups">Channel</a>
								</div>
							</li>
						<?php
							}
						}
					?>
				</ul>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href="index.php?site=imprint"><i class="fas fa-info"></i> Impressum</a>
					</li>
					<?php
						if($_SESSION['loggedin'] == 1) {
					?>
						<li class="nav-item">
							<a href="index.php?site=logout" class="btn btn-danger" role="button"><i class="fas fa-sign-out-alt"></i> Logout</a>
						</li>
					<?php
						}
					?>
				</ul>
			</div>
		</nav>
	</div>

<!-- SITE -->
<div class="container-fluid">
	
	<!-- CONTENT -->
	<div class="card">
		<!-- <div class="card-header text-uppercase"><?php echo $site; ?></div> -->
		<div class="card-body">
			<?php
				if(isset($site) && file_exists($site.".php")) {
					include($site.".php");
				} elseif($site == "") {
					$site = "home";
					include($site.".php");
				} else {
					$site = "error";
					include($site.".php");
				}
			?>
		</div>
	
	<!-- FOOTER -->
		<div class="card-footer">
			<div class="row">
				<div class="w-50 text-left">
					<?php
					if($_SESSION['loggedin'] == 1) {
						$version = $tsAdmin->version();
						echo "Teamspeak 3 Server Version ".$version['data']['version']." (Build: ".$version['data']['build'].") unter ".$version['data']['platform'];
					}
					?>					
				</div>
				<div class="w-50 text-right">
					&copy; 2018 netzhost24.de <kbd>ts3wi-v0.8.0</kbd>
				</div>
			</div>
		</div>
	</div>	
</div>
<br>
<div class="container-fluid">
	<div class="text-center">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_donations">
			<input type="hidden" name="business" value="philipp.foos@t-online.de">
			<input type="hidden" name="lc" value="DE">
			<input type="hidden" name="no_note" value="0">
			<input type="hidden" name="currency_code" value="EUR">
			<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
			<input type="image" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal.">
			<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
</div>

</body>
</html>