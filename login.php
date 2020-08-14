<?php
$_SESSION['username'] = $_POST['login_username'];
$_SESSION['password'] = $_POST['login_password'];
$_SESSION['ts3ip'] = $_POST['login_ts3ip'];
$_SESSION['ts3queryport'] = $_POST['login_ts3queryport'];
$tsAdmin = new ts3admin($_SESSION['ts3ip'], $_SESSION['ts3queryport']);
if($tsAdmin->getElement('success', $tsAdmin->connect())) {
	if($tsAdmin->getElement('success', $tsAdmin->login($_SESSION['username'],$_SESSION['password']))) {
		$_SESSION['loggedin'] = 1;
		header("Location: index.php?site=home");
	} else {
		$_SESSION['loggedin'] = '';
		header("Location: index.php?site=home");
	}
} else {
	?>
	<!-- TS3 ERROR CONNECTION -->
	<div class="container-fluid">
		<div class="alert alert-danger">
			<strong><i class="fas fa-exclamation-triangle"></i> Achtung!</strong> Es konnte keine Verbindung zur Teamspeak 3 Instanz aufgebaut werden.
		</div>
	</div>
	<?php
	header("refresh:3;url=index.php?site=home");
}
?>