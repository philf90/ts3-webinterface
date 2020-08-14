<?php
if(isset($_SESSION['loggedin'])) {
	if(!isset($_SESSION['vserver_port'])) {
		echo "<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Es wurde kein vServer aus der Serverliste ausgewählt!</div>";
	} else {
		if($tsAdmin->getElement('success', $tsAdmin->selectServer($_SESSION['vserver_port']))) {

			if($_POST['button_sgroupclient_delete']) {
				if($tsAdmin->getElement('success', $tsAdmin->serverGroupDeleteClient($_POST['hidden_sgid'],$_POST['hidden_cldbid']))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Der Client <strong>".$_POST['hidden_clientname']."</strong> wurde erfolgreich aus der Servergruppe <strong>".$_POST['hidden_name']."</strong> gelöscht.";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Die Servergruppe ".$_POST['hidden_name']." konnte nicht gelöscht werden.</strong>";
					echo "</div>";
				}
			}

			if($_POST['button_sgroup_clientadd']) {
				if($tsAdmin->getElement('success', $tsAdmin->serverGroupAddClient($_GET['sgid'],$_POST['servergroup_clientdropdown']))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>Client wurder der Gruppe erfolgreich hinzugefügt.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Der Client konnte nicht zur Gruppe hinzugefügt werden.</strong>";
					echo "</div>";
				}
			}
			
			?>
			<div class="row content">
				<div class="col-sm-9">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sgroup_clientadd"><i class="fas fa-plus"></i> Client hinzufügen</button>
				</div>
				<div class="col-sm-3 text-right">
					<button type="button" class="btn btn-primary" onclick="history.back();"><i class="fas fa-angle-double-left"></i> Zurück</button>
					<button type="button" class="btn btn-primary" onclick="window.location.href=window.location.href;"><i class="fas fa-sync-alt"></i> Refresh</button>
				</div>
			</div>
			<br>

			<?php
			$clientList = $tsAdmin->clientList();
			?>

			<!-- ADD SERVERGROUP -->
			<div id="sgroup_clientadd" class="modal fade" role="dialog">
				<div class="modal-dialog modal-lg">

					<!-- Modal content-->
					<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Client hinzufügen</h3>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<form method="post" action="index.php?site=clients&sgid=<?php echo $_GET['sgid']; ?>" name="form_sgroup_clientadd">
							<div class="form-group">
								<table class="table table-striped">
									<tbody>
										<tr>
											<div class="input-group">
												<td class="w-25">Name</td>
												<td class="w-75">
													<select class="form-control" name="servergroup_clientdropdown">";
														<?php
															foreach($clientList['data'] as $clients) {
																echo "<option value=\"".$clients['client_database_id']."\">".$clients['client_nickname']."</option>";
															}
														?>
													</select>
												</td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td class="w-25">&nbsp;</td>
												<td class="w-75"><input type="submit" class="btn btn-success" name="button_sgroup_clientadd" value="Hinzufügen"></td>
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

			<?php
			
			if($_GET['sgid'] != "") {

				$sgid = $_GET['sgid'];
				#print_r($sgid);

				$serverGroupList = $tsAdmin->serverGroupList();

				?>
				<h3>
					<?php
					foreach($serverGroupList['data'] as $servergroups) {
						if($servergroups['sgid'] == $sgid) {
							echo $servergroups['name'];
						}
					}
					?>
				</h3>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>UID</th>
							<th class="text-right">Aktion</th>
						</tr>
					</thead>
					<tbody>
				<?php
				
						foreach($serverGroupList['data'] as $servergroups) {
							if($servergroups['sgid'] == $sgid) {
								$serverGroupClientList = $tsAdmin->serverGroupClientList($servergroups['sgid'],true);
								foreach($serverGroupClientList['data'] as $servergroupclients) {
									echo "<tr>";
										echo "<td><strong>".$servergroupclients['client_nickname']."</strong></td>";
										echo "<td>".$servergroupclients['client_unique_identifier']."</td>";
										echo "<td class=\"text-right\">";
											echo "<form method=\"post\" action=\"index.php?site=clients&sgid=".$sgid."\" name=\"form_servergroupclient_delete\">";
												echo "<div class=\"form-group\">";
													echo "<input type=\"hidden\" name=\"hidden_sgid\" value=".$servergroups['sgid'].">";
													echo "<input type=\"hidden\" name=\"hidden_name\" value=".$servergroups['name'].">";
													echo "<input type=\"hidden\" name=\"hidden_cldbid\" value=".$servergroupclients['cldbid'].">";
													echo "<input type=\"hidden\" name=\"hidden_clientname\" value=".$servergroupclients['client_nickname'].">";
													echo "<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_sgroupclient_delete\" value=\"Löschen\">";
												echo "</div>";
											echo "</form>";
										echo "</td>";
									echo "</tr>";
								}
							}
						}
					?>
					</tbody>
				</table>
				<?php

			}

		} else {
			echo "<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Es konnte keine Verbindung zum ausgewählten vServer hergestellt werden. Existiert der vServer noch?</div>";
		}	
	}
} else {
	?>
	<div class="alert alert-danger">
	  <strong><i class="fas fa-exclamation-triangle"></i> Zugriff verweigert!</strong> Sie sind nicht eingeloggt.
	</div>
	<?php
}
?>