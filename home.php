<?php
if($_SESSION['loggedin'] != 1) {
	?>
		<div class="alert alert-primary">
			<i class="fas fa-exclamation"></i> Um diesen Service zu nutzen, m&uuml;ssen die IP Adressen <kbd>85.214.248.16</kbd> und <kbd>2a01:238:426a:3500:b276:d87c:c088:dede</kbd> in der <kbd>query_ip_whitelist.txt</kbd> der jeweiligen Teamspeak Instanz hinterlegt sein.
		</div>
		<p>Herzlich willkommen im Teamspeak 3 Webinterface von netzhost24.de.<br>Bitte loggen Sie sich ein.</p>
		<form method="post" action="index.php?site=login" name="form_login">
			<div class="form-group">
				<div class="row">
					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="login_ip"><i class="fas fa-cloud"></i></span>
							</div>
							<input type="text" class="form-control" id="login_ts3ip" name="login_ts3ip" placeholder="IP" aria-describedby="login_ip">
						</div>
					</div>
					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="login_queryport"><i class="fas fa-cogs"></i></span>
							</div>
							<input type="text" class="form-control" id="login_ts3queryport" name="login_ts3queryport" placeholder="Queryport" aria-describedby="login_queryport">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="login_username"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" class="form-control" id="login_username" name="login_username" placeholder="Username" aria-describedby="login_username">
						</div>
					</div>
					<div class="col">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="login_password"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" class="form-control" id="login_password" name="login_password" placeholder="Passwort" aria-describedby="login_password">
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-block btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
					</div>
				</div>
			</div>
		</form>
	<?php
} else {
	?>
		<br>
		<div class="jumbotron">	
			<h1>netzhost24.de</h1>
			<p>Herzlich willkommen im Teamspeak 3 Webinterface.</p>
			<p><i class="fas fa-question-circle"></i> Hilfe und Support erh&auml;lst du in unserem <a href="https://www.netzhost24.de/forum" target="_blank">Forum</a>.</p>
			<p><i class="fab fa-cc-paypal"></i> Wenn dir das Webinterface gefällt, freue ich mich zur Unterstützung über eine kleine Spende mit <a href="https://www.paypal.me/PhilippFoos" target="_blank">PayPal.</a></p>
		</div>
	<?php
}
?>
