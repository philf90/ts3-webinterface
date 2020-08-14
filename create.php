<?php
if(isset($_SESSION['loggedin'])) {
	if($_POST['erstellen']) {
		$data = array();
		$data['virtualserver_name'] = $_POST['servername'];
		$data['virtualserver_maxclients'] = $_POST['slots'];
		$data['virtualserver_password'] = $_POST['serverpw'];
		#print_r($data);
		$output = $tsAdmin->serverCreate($data);
		echo "<div class=\"alert alert-success alert-dismissable\">";
		echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
		echo "ID: ".$output['data']['sid']."<br><strong>SA Token: ".$output['data']['token']."</strong><br> Port: ".$output['data']['virtualserver_port'];
		echo "</div>";
	}
?>

<form method="post" action="index.php?site=create" name="form_vserver_create">
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon">Servername</span>
			<input id="servername" type="text" class="form-control" name="servername" placeholder="Servername">
		</div>
		<div class="input-group">
			<span class="input-group-addon">Passwort</span>
			<input id="serverpw" type="text" class="form-control" name="serverpw" placeholder="Passwort (optional)">
		</div>
		<div class="input-group">
			<span class="input-group-addon">Slots</span>
			<input id="slots" type="number" class="form-control" min="10" max="50" name="slots" placeholder="10">
		</div>
		<br>
		<input type="submit" class="btn btn-success" name="erstellen" value="Create">
	</div>
</form>

<?php
} else {
	?>
	<div class="alert alert-danger">
	  <strong><i class="fas fa-exclamation-triangle"></i> Zugriff verweigert!</strong> Sie sind nicht eingeloggt.
	</div>
	<?php
}
?>