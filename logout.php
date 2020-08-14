<?php
	#LOGOUT DESTROY ALL
	$tsAdmin->logout();
	session_destroy();
	header("Location: index.php?site=home");
?>