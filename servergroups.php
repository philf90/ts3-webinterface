<?php
if(isset($_SESSION['loggedin'])) {
	if(!isset($_SESSION['vserver_port'])) {
		echo "<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Es wurde kein vServer aus der Serverliste ausgewählt!</div>";
	} else {
		if($tsAdmin->getElement('success', $tsAdmin->selectServer($_SESSION['vserver_port']))) {

			if($_POST['button_sgid_delete']) {
				if($tsAdmin->getElement('success', $tsAdmin->serverGroupDelete($_POST['hidden_sgid'],1))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Die Servergruppe <strong>".$_POST['hidden_name']."</strong> wurde erolgreich gelöscht.";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Die Servergruppe ".$_POST['hidden_name']." konnte nicht gelöscht werden.</strong>";
					echo "</div>";
				}
			}

			if($_POST['button_sgid_rename']) {
				if($tsAdmin->getElement('success', $tsAdmin->serverGroupRename($_POST['hidden_sgid'],$_POST['sgroup_name']))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Name der Servergruppe [".$_POST['hidden_sgid']."] erfolgreich in <strong>".$_POST['sgroup_name']."</strong> geändert.";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Name der Servergruppe [".$_POST['hidden_sgid']."] konnte nicht geändert werden.</strong>";
					echo "</div>";
				}
			}

			if($_POST['button_sgroup_add']) {
				if($tsAdmin->getElement('success', $output = $tsAdmin->serverGroupAdd($_POST['add_sgroup_name'],1))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Servergruppe <strong>".$_POST['add_sgroup_name']." [".$output['data']['sgid']."]</strong> erfolgreich erstellt.";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Servergruppe ".$_POST['add_sgroup_name']." konnte nicht erstellt werden.</strong>";
					echo "</div>";
				}
			}

			$serverGroupList = $tsAdmin->serverGroupList();
			#print_r($serverGroupList);

			if($_POST['sgid_rename']) {
				?>
				<h3>Servergruppe umbenennen</h3>
					<form method="post" action="index.php?site=servergroups" name="form_servergroup_rename">
						<div class="form-group">
							<table class="table table-striped">
								<tbody>
									<?php
									foreach($serverGroupList['data'] as $servergroups) {
										if($servergroups['sgid'] == $_POST['hidden_sgid']) {
										?>
										<tr>
											<div class="input-group">
												<td class="w-25">Name</td>
												<td class="w-75"><input id="sgroup_name" type="text" class="form-control" name="sgroup_name" placeholder="<?php echo $servergroups['name']; ?>" value="<?php echo $servergroups['name']; ?>"></td>
											</div>
										</tr>
										<?php
										}
									}
									?>
									<tr>
										<div class="input-group">
											<td>&nbsp;</td>
											<td><input type="hidden" name="hidden_sgid" value="<?php echo $_POST['hidden_sgid']; ?>"><input type="submit" class="btn btn-success" name="button_sgid_rename" value="Ändern"> <input type="submit" class="btn btn-danger" name="button_sgid_rename_abort" value="Abbrechen"></td>
										</div>
									</tr>
								</tbody>
							</table>
						</div>
					</form>
				<?php

			} else {

				?>

				<div class="row content">
					<div class="col-sm-9">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sgroup_add"><i class="fas fa-plus"></i> Gruppe hinzufügen</button>
					</div>
					<div class="col-sm-3 text-right">
						<button type="button" class="btn btn-primary" onclick="window.location.href=window.location.href;"><i class="fas fa-sync-alt"></i> Refresh</button>
					</div>
				</div>
				<br>

				<!-- ADD SERVERGROUP -->
				<div id="sgroup_add" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg">

						<!-- Modal content-->
						<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Servergruppe hinzufügen</h3>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<form method="post" action="index.php?site=servergroups" name="form_sgroup_add">
								<div class="form-group">
									<table class="table table-striped">
										<tbody>
											<tr>
												<div class="input-group">
													<td class="w-25">Name</td>
													<td class="w-75"><input id="add_sgroup_name" type="text" class="form-control" name="add_sgroup_name"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td class="w-25">&nbsp;</td>
													<td class="w-75"><input type="submit" class="btn btn-success" name="button_sgroup_add" value="Hinzufügen"></td>
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

				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>SGID</th>
							<th>Name</th>
							<th>Typ</th>
							<th>DB</th>
							<th class="text-right">Aktion</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($serverGroupList['data'] as $servergroups) {
							if($servergroups['sgid'] > 5) {
								echo "<tr>";
									echo "<td class=\"w-5\">[".$servergroups['sgid']."]</td>";
									echo "<td class=\"w-55\"><span class=\"badge badge-dark\">".$servergroups['name']."</span>";
										if($tsAdmin->getElement('success', $serverGroupGetIconBySGID = $tsAdmin->serverGroupGetIconBySGID($servergroups['sgid']))) {
											echo " <img src=\"data:image/png;base64,".$serverGroupGetIconBySGID['data']."\" />";
										}
									echo "</td>";
									if($servergroups['type'] == 0) {
										echo "<td class=\"w-5\">Template</td>";
										echo "<td class=\"w-5\">".$servergroups['savedb']."</td>";
										echo "<td class=\"w-30 text-right\">";
										echo "<form method=\"post\" action=\"index.php?site=servergroups\" name=\"form_servergroups_edit\">";
												echo "<div class=\"form-group\">";
													echo "<input type=\"hidden\" name=\"hidden_sgid\" value=".$servergroups['sgid'].">";
												echo "</div>";
											echo "</form>";
										echo "</td>";
									}
									if($servergroups['type'] == 1) {
										echo "<td class=\"w-5\">Normal</td>";
										echo "<td class=\"w-5\">".$servergroups['savedb']."</td>";
										echo "<td class=\"w-30 text-right\">";
											echo "<form method=\"post\" action=\"index.php?site=servergroups\" name=\"form_servergroups_edit\">";
												echo "<div class=\"form-group\">";
													echo "<input type=\"hidden\" name=\"hidden_sgid\" value=".$servergroups['sgid'].">";
													echo "<input type=\"hidden\" name=\"hidden_name\" value=".$servergroups['name'].">";
													echo "<input type=\"submit\" class=\"btn btn-sm btn-primary\" name=\"sgid_rename\" value=\"Umbenennen\">&nbsp;";
													#echo "<button type=\"button\" class=\"btn btn-sm btn-primary\" data-toggle=\"modal\" data-target=\"#clients_".$servergroups['sgid']."\"><i class=\"fas fa-user\"></i> Clients</button>&nbsp;";
													echo "<a href=\"index.php?site=clients&sgid=".$servergroups['sgid']."\" class=\"btn btn-sm btn-info\" role=\"button\"><i class=\"fas fa-user\"></i> Clients</a>&nbsp;";
													echo "<a href=\"index.php?site=permissions&sgid=".$servergroups['sgid']."\" class=\"btn btn-sm btn-warning\" role=\"button\"><i class=\"far fa-hand-point-up\"></i> Rechte</a>&nbsp;";
													echo "<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_sgid_delete\" value=\"Löschen\">";
												echo "</div>";
											echo "</form>";
										echo "</td>";
									}
									if($servergroups['type'] == 2) {
										echo "<td class=\"w-5\">Query</td>";
										echo "<td class=\"w-5\">".$servergroups['savedb']."</td>";
										echo "<td class=\"w-30 text-right\">";
										echo "<form method=\"post\" action=\"index.php?site=servergroups\" name=\"form_servergroups_edit\">";
												echo "<div class=\"form-group\">";
													echo "<input type=\"hidden\" name=\"hidden_sgid\" value=".$servergroups['sgid'].">";
												echo "</div>";
											echo "</form>";
										echo "</td>";
									}
								echo "</tr>";
							}
							?>

							<!-- SGID CLIENTS -->
							<div id="clients_<?php echo $servergroups['sgid']; ?>" class="modal fade" role="dialog">
								<div class="modal-dialog modal-lg">

									<!-- Modal content-->
									<div class="modal-content">
									<div class="modal-header">
										<h3 class="modal-title"><?php echo $servergroups['name']; ?> - Clients</h3>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									<div class="modal-body">
										<?php
											$serverGroupClientList = $tsAdmin->serverGroupClientList($servergroups['sgid'],true);
											#print_r($serverGroupClientList);
											foreach($serverGroupClientList['data'] as $servergroupclients) {
												#print_r($servergroupclients);
													echo "<strong>".$servergroupclients['client_nickname']."</strong> [".$servergroupclients['client_unique_identifier']."]<br>";
											}
										?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
									</div>
									</div>

								</div>
							</div>

						<?php
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