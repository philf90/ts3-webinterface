<?php
if(isset($_SESSION['loggedin'])) {
	if(!isset($_SESSION['vserver_port'])) {
		echo "<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Es wurde kein vServer aus der Serverliste ausgewählt!</div>";
	} else {
		if($tsAdmin->getElement('success', $tsAdmin->selectServer($_SESSION['vserver_port']))) {

			if($_POST['button_cgid_delete']) {
				if($tsAdmin->getElement('success', $tsAdmin->channelGroupDelete($_POST['hidden_cgid'],1))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Die Channelgruppe <strong>".$_POST['hidden_name']."</strong> wurde erolgreich gelöscht.";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Die Channelgruppe ".$_POST['hidden_name']." konnte nicht gelöscht werden.</strong>";
					echo "</div>";
				}
			}

			if($_POST['button_cgid_rename']) {
				if($tsAdmin->getElement('success', $tsAdmin->channelGroupRename($_POST['hidden_cgid'],$_POST['cgroup_name']))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Name der Channelgruppe erfolgreich in <strong>".$_POST['cgroup_name']."</strong> geändert.";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Name der Channelgruppe konnte nicht geändert werden.</strong>";
					echo "</div>";
				}
			}

			if($_POST['button_cgroup_add']) {
				if($tsAdmin->getElement('success', $output = $tsAdmin->channelGroupAdd($_POST['add_cgroup_name'],1))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Channelgruppe <strong>".$_POST['add_cgroup_name']." [".$output['data']['sgid']."]</strong> erfolgreich erstellt.";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Channelgruppe ".$_POST['add_cgroup_name']." konnte nicht erstellt werden.</strong>";
					echo "</div>";
				}
			}

			$channelGroupList = $tsAdmin->channelGroupList();

			if($_POST['cgid_rename'] || $_POST['cgid_permissions']) {

				if($_POST['cgid_rename']) {
					?>
					<h3>Channelgruppe umbenennen</h3>
						<form method="post" action="index.php?site=channelgroups" name="form_channelgroup_rename">
							<div class="form-group">
								<table class="table table-striped">
									<tbody>
										<?php
										foreach($channelGroupList['data'] as $channelgroups) {
											if($channelgroups['cgid'] == $_POST['hidden_cgid']) {
											?>
											<tr>
												<div class="input-group">
													<td class="w-25">Name</td>
													<td class="w-75"><input id="cgroup_name" type="text" class="form-control" name="cgroup_name" placeholder="<?php echo $channelgroups['name']; ?>" value="<?php echo $channelgroups['name']; ?>"></td>
												</div>
											</tr>
											<?php
											}
										}
										?>
										<tr>
											<div class="input-group">
												<td>&nbsp;</td>
												<td><input type="hidden" name="hidden_cgid" value="<?php echo $_POST['hidden_cgid']; ?>"><input type="submit" class="btn btn-success" name="button_cgid_rename" value="Ändern"> <input type="submit" class="btn btn-danger" name="button_cgid_rename_abort" value="Abbrechen"></td>
											</div>
										</tr>
									</tbody>
								</table>
							</div>
						</form>
					<?php
				}

				if($_POST['cgid_permissions']) {
					$channelGroupPermList = $tsAdmin->channelGroupPermList($_POST['hidden_cgid'],true);
					$permissionList = $tsAdmin->permissionList();
					#print_r($channelGroupPermList);

					?>
					<a data-toggle="collapse" href="#collapse1"><h3>Zugeteilt</h3></a>
					<div id="collapse1" class="panel-collapse collapse in">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Description</th>
									<th>Negated</th>
									<th>Value</th>
									<th>Skip</th>
									<th class="text-right">Aktion</th>
								</tr>
							</thead>
							<tbody>
						
								<?php
								foreach($permissionList['data'] as $permission) {
									foreach($channelGroupPermList['data'] as $channelgroupperm) {
										if($permission['permname'] == $channelgroupperm['permsid']) {
											echo "<tr>";
												echo "<td class=\"col-xs-1\">".$permission['permid']."</td>";
												if($permission['permdesc'] == "") {
													echo "<td class=\"col-xs-6\">Keine Beschreibung verfügbar<br><i>".$channelgroupperm['permsid']."</i></td>";
												} else {
													echo "<td class=\"col-xs-6\">".$permission['permdesc']."<br><i>".$channelgroupperm['permsid']."</i></td>";
												}
												echo "<td class=\"col-xs-1\">".$channelgroupperm['permnegated']."</td>";
												echo "<td class=\"col-xs-1\">".$channelgroupperm['permvalue']."</td>";
												echo "<td class=\"col-xs-1\">".$channelgroupperm['permskip']."</td>";
												echo "<td class=\"col-xs-2 text-right\">";
													echo "<form method=\"post\" action=\"index.php?site=permissions\" name=\"form_permission_edit\">";
														echo "<div class=\"form-group\">";
															echo "<input type=\"hidden\" name=\"hidden_cgid\" value=".$_POST['hidden_cgid'].">";
															echo "<input type=\"hidden\" name=\"hidden_cgid_permsid\" value=".$channelgroupperm['permsid'].">";
															echo "<input type=\"submit\" class=\"btn btn-sm btn-primary\" name=\"permission_edit\" value=\"Ändern\">";
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
					</div>

					<a data-toggle="collapse" href="#collapse2"><h3>Nicht zugeteilt</h3></a>
					<div id="collapse2" class="panel-collapse collapse">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Description</th>
									<th class="text-right">Aktion</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
					<?php
				}

			} else {

				?>

				<div class="row content">
					<div class="col-sm-9">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cgroup_add"><i class="fas fa-plus"></i> Gruppe hinzufügen</button>
					</div>
					<div class="col-sm-3 text-right">
						<button type="button" class="btn btn-primary" onclick="window.location.href=window.location.href;"><i class="fas fa-sync-alt"></i> Refresh</button>
					</div>
				</div>
				<br>

				<!-- ADD CHANNELGROUP -->
				<div id="cgroup_add" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg">

						<!-- Modal content-->
						<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Channelgruppe hinzufügen</h3>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<form method="post" action="index.php?site=channelgroups" name="form_cgroup_add">
								<div class="form-group">
									<table class="table table-striped">
										<tbody>
											<tr>
												<div class="input-group">
													<td class="w-25">Name</td>
													<td class="w-75"><input id="add_cgroup_name" type="text" class="form-control" name="add_cgroup_name"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td class="w-25">&nbsp;</td>
													<td class="w-75"><input type="submit" class="btn btn-success" name="button_cgroup_add" value="Hinzufügen"></td>
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
								<th>CGID</th>
								<th>Name</th>
								<th>Typ</th>
								<th>DB</th>
								<th class="text-right">Aktion</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($channelGroupList['data'] as $channelgroups) {
								if($channelgroups['cgid'] > 4) {
									echo "<tr>";
										echo "<td class=\"w-5\">[".$channelgroups['cgid']."]</td>";
										echo "<td class=\"w-55\"><span class=\"badge badge-info\">".$channelgroups['name']."</span>";
											if($tsAdmin->getElement('success', $channelGroupGetIconBySGID = $tsAdmin->channelGroupGetIconBySGID($channelgroups['cgid']))) {
												echo " <img src=\"data:image/png;base64,".$channelGroupGetIconBySGID['data']."\" />";
											}
										echo "</td>";
										if($channelgroups['type'] == 0) {
											echo "<td class=\"w-5\">Template</td>";
											echo "<td class=\"w-5\">".$channelgroups['savedb']."</td>";
											echo "<td class=\"w-30 text-right\">";
											echo "<form method=\"post\" action=\"index.php?site=channelgroups\" name=\"form_channelgroups_edit\">";
													echo "<div class=\"form-group\">";
														echo "<input type=\"hidden\" name=\"hidden_cgid\" value=".$channelgroups['cgid'].">";
													echo "</div>";
												echo "</form>";
											echo "</td>";
										}
										if($channelgroups['type'] == 1) {
											echo "<td class=\"w-5\">Normal</td>";
											echo "<td class=\"w-5\">".$channelgroups['savedb']."</td>";
											echo "<td class=\"w-30 text-right\">";
												echo "<form method=\"post\" action=\"index.php?site=channelgroups\" name=\"form_channelgroups_edit\">";
													echo "<div class=\"form-group\">";
														echo "<input type=\"hidden\" name=\"hidden_cgid\" value=".$channelgroups['cgid'].">";
														echo "<input type=\"hidden\" name=\"hidden_name\" value=".$channelgroups['name'].">";
														echo "<input type=\"submit\" class=\"btn btn-sm btn-primary\" name=\"cgid_rename\" value=\"Umbenennen\">&nbsp;";
														#echo "<input type=\"submit\" class=\"btn btn-sm btn-primary\" name=\"cgid_clients\" value=\"Clients\">&nbsp;";
														#echo "<input type=\"submit\" class=\"btn btn-sm btn-warning\" name=\"cgid_permissions\" value=\"Rechte\">&nbsp;";
														echo "<a href=\"index.php?site=permissions&cgid=".$channelgroups['cgid']."\" class=\"btn btn-sm btn-warning\" role=\"button\"><i class=\"far fa-hand-point-up\"></i> Rechte</a>&nbsp;";
														echo "<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_cgid_delete\" value=\"Löschen\">";
													echo "</div>";
												echo "</form>";
											echo "</td>";
										}
										if($channelgroups['type'] == 2) {
											echo "<td class=\"w-5\">Query</td>";
											echo "<td class=\"w-5\">".$channelgroups['savedb']."</td>";
											echo "<td class=\"w-30 text-right\">";
											echo "<form method=\"post\" action=\"index.php?site=channelgroups\" name=\"form_channelgroups_edit\">";
													echo "<div class=\"form-group\">";
														echo "<input type=\"hidden\" name=\"hidden_cgid\" value=".$channelgroups['cgid'].">";
													echo "</div>";
												echo "</form>";
											echo "</td>";
										}
									echo "</tr>";
								}
							}
							?>
						</tbody>
					</table>
				</div>

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
