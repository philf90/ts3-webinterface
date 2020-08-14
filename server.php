<?php
if(isset($_SESSION['loggedin'])) {
	if($_POST['button_vserver_stop']) {
		if($tsAdmin->getElement('success', $tsAdmin->serverStop($_POST['hidden_vserver_id']))) {
			echo "<div class=\"alert alert-success alert-dismissable\">";
			echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
			echo "<strong>vServer mit ID ".$_POST['hidden_vserver_id']." wurde gestoppt.</strong>";
			echo "</div>";
		} else {
			echo "<div class=\"alert alert-danger alert-dismissable\">";
			echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
			echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>vServer mit ID ".$_POST['hidden_vserver_id']." konnte nicht gestoppt werden.</strong>";
			echo "</div>";
		}
	}
	if($_POST['button_vserver_start']) {
		if($tsAdmin->getElement('success', $tsAdmin->serverStart($_POST['hidden_vserver_id']))) {
			echo "<div class=\"alert alert-success alert-dismissable\">";
			echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
			echo "<strong>Server mit ID ".$_POST['hidden_vserver_id']." wurde gestartet.</strong>";
			echo "</div>";
		} else {
			echo "<div class=\"alert alert-danger alert-dismissable\">";
			echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
			echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>vServer mit ID ".$_POST['hidden_vserver_id']." konnte nicht gestartet werden.</strong>";
			echo "</div>";
		}
	}
	if($_POST['button_vserver_delete']) {
		if($tsAdmin->getElement('success', $tsAdmin->serverDelete($_POST['hidden_vserver_id']))) {
			echo "<div class=\"alert alert-success alert-dismissable\">";
			echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
			echo "<strong>Server mit ID ".$_POST['hidden_vserver_id']." wurde gelöscht.</strong>";
			echo "</div>";
		} else {
			echo "<div class=\"alert alert-danger alert-dismissable\">";
			echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
			echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>vServer mit ID ".$_POST['hidden_vserver_id']." konnte nicht gelöscht werden.</strong>";
			echo "</div>";
		}
	}
	if($_POST['button_vserver_select']) {
		$_SESSION['vserver_port'] = $_POST['hidden_vserver_port'];
		header("Location: index.php?site=vserver");
	}
	
	if($_POST['button_server_create']) {
		$data = array();
		$data['virtualserver_name'] = $_POST['servername'];
		$data['virtualserver_maxclients'] = $_POST['slots'];
		$data['virtualserver_password'] = $_POST['serverpw'];
		$data['virtualserver_port'] = $_POST['port'];
		if($tsAdmin->getElement('success', $output = $tsAdmin->serverCreate($data))) {
			echo "<div class=\"alert alert-success alert-dismissable\">";
			echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
			echo "vServer erfolgreich mit ID ".$output['data']['sid']." unter <strong>Port ".$output['data']['virtualserver_port']."</strong> erstellt.<br><strong>SA Token: ".$output['data']['token']."</strong>";
			echo "</div>";
		} else {
			echo "<div class=\"alert alert-danger alert-dismissable\">";
			echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
			echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>vServer konnte nicht erstellt werden.</strong>";
			echo "</div>";
		}
	}

	?>

	<div class="row content">
		<div class="col-sm-9">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#server_create"><i class="fas fa-plus"></i> vServer erstellen</button>
		</div>
		<div class="col-sm-3 text-right">
			<button type="button" class="btn btn-primary" onclick="window.location.href=window.location.href;"><i class="fas fa-sync-alt"></i> Refresh</button>
		</div>
	</div>
	<br>

	<!-- Modal -->
	<div id="server_create" class="modal fade">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">vServer erstellen</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<form method="post" action="index.php?site=server" name="form_vserver_create">
					<div class="form-group">
						<table class="table table-striped">
							<tbody>
								<tr>
									<div class="input-group">
										<td>Servername</td>
										<td><input id="servername" type="text" class="form-control" name="servername" placeholder="Servername"></td>
									</div>
								</tr>
								<tr>
									<div class="input-group">
										<td>Passwort</td>
										<td><input id="serverpw" type="text" class="form-control" name="serverpw" placeholder="Passwort (optional)"></td>
									</div>
								</tr>
								<tr>
									<div class="input-group">
										<td>Slots</td>
										<td><input id="slots" type="number" class="form-control" min="10" max="50" name="slots" placeholder="32"></td>
									</div>
								</tr>
								<tr>
									<div class="input-group">
										<td>Port</td>
										<td><input id="port" type="number" class="form-control" min="9987" max="9999" name="port"></td>
									</div>
								</tr>
								<tr>
									<div class="input-group">
										<td>&nbsp;</td>
										<td><input type="submit" class="btn btn-success" name="button_server_create" value="Erstellen"></td>
									</div>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
			</div>
			</div>

		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
			<tr>
				<th class="w-5">ID</th>
				<th class="w-55">Servername</th>
				<th class="w-5">Port</th>
				<th class="w-5">Slots</th>
				<th class="w-5">Status</th>
				<th class="w-25 text-right">Aktion</th>
			</tr>
			</thead>
			<tbody>
				<?php
				$servers = $tsAdmin->serverList();
				foreach($servers['data'] as $server) {
					echo "<tr>";
						echo "<td>".$server['virtualserver_id']."</td>";
						$tsAdmin->selectServer($server['virtualserver_port']);
						$info = $tsAdmin->serverInfo();
						if($info['data']['virtualserver_password'] == "" || !isset($info['data']['virtualserver_password'])) {
							echo "<td>".$server['virtualserver_name']."</td>";
						} else {
							echo "<td>".$server['virtualserver_name']." <i class=\"fas fa-lock\"></td>";
						}
						echo "<td>".$server['virtualserver_port']."</td>";
						if($server['virtualserver_status'] == 'online') {
							echo "<td>".$server['virtualserver_clientsonline']." / ".$server['virtualserver_maxclients']."</td>";
							echo "<td><span class=\"badge badge-success\">online</span></td>";
							echo "<td class=\" text-right\"><form method=\"post\" action=\"index.php?site=server\" name=\"vserver_stop\">
									<div class=\"form-group\">
										<input type=\"hidden\" name=\"hidden_vserver_port\" value=".$server['virtualserver_port'].">
										<input type=\"hidden\" name=\"hidden_vserver_id\" value=".$server['virtualserver_id'].">
										<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_vserver_stop\" value=\"Stopp\">
										<input type=\"submit\" class=\"btn btn-sm btn-info\" name=\"button_vserver_select\" value=\"Auswählen\">
										<a href=\"ts3server://".$_SESSION['ts3ip']."/?port=".$server['virtualserver_port']."\" class=\"btn btn-sm btn-dark\" role=\"button\"><i class=\"fas fa-sign-in-alt\"></i> Connect</a>
									</div>
								</form></td>";
						} else {
							echo "<td>&nbsp;</td>";
							echo "<td><span class=\"badge badge-danger\">offline</span></td>";
							echo "<td class=\"text-right\"><form method=\"post\" action=\"index.php?site=server\" name=\"vserver_start\">
									<div class=\"form-group\">
										<input type=\"hidden\" name=\"hidden_vserver_port\" value=".$server['virtualserver_port'].">
										<input type=\"hidden\" name=\"hidden_vserver_id\" value=".$server['virtualserver_id'].">
										<input type=\"submit\" class=\"btn btn-sm btn-success\" name=\"button_vserver_start\" value=\"Start\">
										<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_vserver_delete\" value=\"Löschen\">
									</div>
								</form></td>";
						}
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
	</div>

<?php
} else {
	?>
	<div class="alert alert-danger">
	  <strong><i class="fas fa-exclamation-triangle"></i> Zugriff verweigert!</strong> Sie sind nicht eingeloggt.
	</div>
	<?php
}
?>