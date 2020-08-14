<?php
if(isset($_SESSION['loggedin'])) {
	if(!isset($_SESSION['vserver_port'])) {
		echo "<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Es wurde kein vServer aus der Serverliste ausgewählt!</div>";
	} else {
		if($tsAdmin->getElement('success', $tsAdmin->selectServer($_SESSION['vserver_port']))) {

			$toggle_value = "Wert des zugewiesenen Rechtes.";
			$toggle_negated = "tbd";
			$toggle_skip = "Recht wird nicht von Channel(Gruppen)Rechten überschrieben.";

			if($_POST['button_cid_permission_edit']) {
				$permname = $_POST['hidden_permname'];
				$permissions = array();
				$permissions[$permname] = $_POST['edit_permission_value'];
				if($tsAdmin->getElement('success', $tsAdmin->channelAddPerm($_POST['hidden_cid'],$permissions))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>Erfolgreich.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Nicht erfolgreich.</strong>";
					echo "</div>";
				}
			}

			if($_POST['button_permission_channel_delete']) {
				$permissions = array();
				$permissions[] = $_POST['hidden_cid_permsid'];
				if($tsAdmin->getElement('success', $tsAdmin->channelDelPerm($_POST['hidden_cid'],$permissions))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>Erfolgreich.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Nicht erfolgreich.</strong>";
					echo "</div>";
				}
			}

			if($_POST['button_cgid_permission_edit']) {
				$permname = $_POST['hidden_permname'];
				$permissions = array();
				$permissions[$permname] = $_POST['edit_permission_value'];
				if($tsAdmin->getElement('success', $tsAdmin->channelGroupAddPerm($_POST['hidden_cgid'],$permissions))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>Erfolgreich.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Nicht erfolgreich.</strong>";
					echo "</div>";
				}
			}

			if($_POST['button_permission_cgid_delete']) {
				$permissions = array();
				$permissions[] = $_POST['hidden_cgid_permsid'];
				if($tsAdmin->getElement('success', $tsAdmin->channelGroupDelPerm($_POST['hidden_cgid'],$permissions))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>Erfolgreich.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Nicht erfolgreich.</strong>";
					echo "</div>";
				}
			}

			if($_POST['button_sgid_permission_edit']) {
				$permname = $_POST['hidden_permname'];
				$permissions = array();
				$permissions[$permname] = $_POST['edit_permission_value'];
				if($tsAdmin->getElement('success', $tsAdmin->serverGroupAddPerm($_POST['hidden_sgid'],$permissions))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>Erfolgreich.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Nicht erfolgreich.</strong>";
					echo "</div>";
				}
			}

			if($_POST['button_permission_sgid_delete']) {
				$permissions = array();
				$permissions[] = $_POST['hidden_sgid_permsid'];
				if($tsAdmin->getElement('success', $tsAdmin->serverGroupDeletePerm($_POST['hidden_sgid'],$permissions))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>Erfolgreich.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Nicht erfolgreich.</strong>";
					echo "</div>";
				}
			}
			
			?>
				<div class="row content">
					<div class="col-sm-9">
						<button type="button" class="btn btn-primary" onclick="history.back();"><i class="fas fa-angle-double-left"></i> Zurück</button>
					</div>
					<div class="col-sm-3 text-right">
						<button type="button" class="btn btn-primary" onclick="window.location.href=window.location.href;"><i class="fas fa-sync-alt"></i> Refresh</button>
					</div>
				</div>
				<br>
			<?php
			
			$permissionList = $tsAdmin->permissionList();
			
			if($_GET['cldbid'] != "") {
				$cldbid = $_GET['cldbid'];
				$clientPermList = $tsAdmin->clientPermlist($cldbid,true);
				print_r($clientPermList);
			}

			if($_GET['sgid'] != "") {
				$sgid = $_GET['sgid'];
				$serverGroupList = $tsAdmin->serverGroupList();
				$serverGroupPermList = $tsAdmin->serverGroupPermList($sgid,true);

				if($_POST['permission_sgid_edit']) {
					?>
					<h3>Rechte ändern</h3>
					<form method="post" action="index.php?site=permissions&sgid=<?php echo $_POST['hidden_sgid']; ?>" name="form_sgid_pw_edit">
						<div class="form-group">
							<table class="table table-striped">
								<tbody>
									<?php
									foreach($serverGroupPermList['data'] as $servergroupperm) {
										if($servergroupperm['permsid'] == $_POST['hidden_sgid_permsid']) {
											foreach($permissionList['data'] as $permission) {
												if($permission['permname'] == $_POST['hidden_sgid_permsid']) {
													echo "<tr>";
														echo "<div class=\"input-group\">";
															echo "<td class=\"w-25\">".$permission['permdesc']."<br><i>".$permission['permname']."</i></td>";
															echo "<td class=\"w-75\"><input id=\"edit_permission_value\" type=\"text\" class=\"form-control\" name=\"edit_permission_value\" placeholder=\"".$servergroupperm['permvalue']."\" value=\"".$servergroupperm['permvalue']."\"></td>";
														echo "</div>";
													echo "</tr>";
												}
											}
										}
									}
									?>
									<tr>
										<div class="input-group">
											<td>&nbsp;</td>
											<td><input type="hidden" name="hidden_permname" value="<?php echo $_POST['hidden_sgid_permsid']; ?>"><input type="hidden" name="hidden_sgid" value="<?php echo $sgid; ?>"><input type="submit" class="btn btn-success" name="button_sgid_permission_edit" value="Ändern"></td>
										</div>
									</tr>
								</tbody>
							</table>
						</div>
					</form>
					<?php
				} else {
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
							<th>ID</th>
							<th>Beschreibung</th>
							<th><a data-toggle="tooltip" data-placement="top" title="<?php echo $toggle_value; ?>">Wert</a></th>
							<th><a data-toggle="tooltip" data-placement="top" title="<?php echo $toggle_negated; ?>">Negiert</a></th>
							<th><a data-toggle="tooltip" data-placement="top" title="<?php echo $toggle_skip; ?>">Überspringen</a></th>
							<th class="text-right">Aktion</th>
						</tr>
					</thead>
					<tbody>
				
						<?php
						foreach($permissionList['data'] as $permission) {
							foreach($serverGroupPermList['data'] as $servergroupperm) {
								if($permission['permname'] == $servergroupperm['permsid']) {
									echo "<tr>";
										echo "<td class=\"w-5\">".$permission['permid']."</td>";
										if($permission['permdesc'] == "") {
											echo "<td class=\"w-50\">Keine Beschreibung verfügbar<br><i>".$servergroupperm['permsid']."</i></td>";
										} else {
											echo "<td class=\"w-50\">".$permission['permdesc']."<br><i>".$servergroupperm['permsid']."</i></td>";
										}
										echo "<td class=\"w-5\">".$servergroupperm['permvalue']."</td>";
										if($servergroupperm['permnegated'] == 1) {
											echo "<td class=\"w-5\"><i class=\"fas fa-check\"></i></td>";
										} else {
											echo "<td class=\"w-5\"><i class=\"fas fa-minus\"></i></td>";
											#echo "<td class=\"w-5\">".$servergroupperm['permnegated']."</td>";
										}
										if($servergroupperm['permskip'] == 1) {
											echo "<td class=\"w-5\"><i class=\"fas fa-check\"></i></td>";
										} else {
											echo "<td class=\"w-5\"><i class=\"fas fa-minus\"></i></td>";
											#echo "<td class=\"w-5\">".$servergroupperm['permskip']."</td>";
										}
										echo "<td class=\"w-30 text-right\">";
											echo "<form method=\"post\" action=\"index.php?site=permissions&sgid=".$sgid."\" name=\"form_permission_edit\">";
												echo "<div class=\"form-group\">";
													echo "<input type=\"hidden\" name=\"hidden_sgid_permid\" value=".$permission['permid'].">";
													echo "<input type=\"hidden\" name=\"hidden_sgid\" value=".$sgid.">";
													echo "<input type=\"hidden\" name=\"hidden_sgid_permsid\" value=".$servergroupperm['permsid'].">";
													echo "<input type=\"submit\" class=\"btn btn-sm btn-primary\" name=\"permission_sgid_edit\" value=\"Ändern\">&nbsp;";
													echo "<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_permission_sgid_delete\" value=\"Löschen\">";
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
			}

			if($_GET['cgid'] != "") {
				$cgid = $_GET['cgid'];
				$channelGroupList = $tsAdmin->channelGroupList();
				$channelGroupPermList = $tsAdmin->channelGroupPermList($cgid,true);

				if($_POST['permission_cgid_edit']) {
					?>
					<h3>Rechte ändern</h3>
					<form method="post" action="index.php?site=permissions&cgid=<?php echo $_POST['hidden_cgid']; ?>" name="form_cgid_pw_edit">
						<div class="form-group">
							<table class="table table-striped">
								<tbody>
									<?php
									foreach($channelGroupPermList['data'] as $channelgroupperm) {
										if($channelgroupperm['permsid'] == $_POST['hidden_cgid_permsid']) {
											foreach($permissionList['data'] as $permission) {
												if($permission['permname'] == $_POST['hidden_cgid_permsid']) {
													echo "<tr>";
														echo "<div class=\"input-group\">";
															echo "<td class=\"w-25\">".$permission['permdesc']."<br><i>".$permission['permname']."</i></td>";
															echo "<td class=\"w-75\"><input id=\"edit_permission_value\" type=\"text\" class=\"form-control\" name=\"edit_permission_value\" placeholder=\"".$channelgroupperm['permvalue']."\" value=\"".$channelgroupperm['permvalue']."\"></td>";
														echo "</div>";
													echo "</tr>";
												}
											}
										}
									}
									?>
									<tr>
										<div class="input-group">
											<td>&nbsp;</td>
											<td><input type="hidden" name="hidden_permname" value="<?php echo $_POST['hidden_cgid_permsid']; ?>"><input type="hidden" name="hidden_cgid" value="<?php echo $cgid; ?>"><input type="submit" class="btn btn-success" name="button_cgid_permission_edit" value="Ändern"></td>
										</div>
									</tr>
								</tbody>
							</table>
						</div>
					</form>
					<?php
				} else {
				?>

				<h3>
					<?php
					foreach($channelGroupList['data'] as $channelgroups) {
						if($channelgroups['cgid'] == $cgid) {
							echo $channelgroups['name'];
						}
					}
					?>
				</h3>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Beschreibung</th>
							<th><a data-toggle="tooltip" data-placement="top" title="<?php echo $toggle_value; ?>">Wert</a></th>
							<th><a data-toggle="tooltip" data-placement="top" title="<?php echo $toggle_negated; ?>">Negiert</a></th>
							<th><a data-toggle="tooltip" data-placement="top" title="<?php echo $toggle_skip; ?>">Überspringen</a></th>
							<th class="text-right">Aktion</th>
						</tr>
					</thead>
					<tbody>
				
						<?php
						foreach($permissionList['data'] as $permission) {
							foreach($channelGroupPermList['data'] as $channelgroupperm) {
								if($permission['permname'] == $channelgroupperm['permsid']) {
									echo "<tr>";
										echo "<td class=\"w-5\">".$permission['permid']."</td>";
										if($permission['permdesc'] == "") {
											echo "<td class=\"w-50\">Keine Beschreibung verfügbar<br><i>".$channelgroupperm['permsid']."</i></td>";
										} else {
											echo "<td class=\"w-50\">".$permission['permdesc']."<br><i>".$channelgroupperm['permsid']."</i></td>";
										}
										echo "<td class=\"w-5\">".$channelgroupperm['permvalue']."</td>";
										if($channelgroupperm['permnegated'] == 1) {
											echo "<td class=\"w-5\"><i class=\"fas fa-check\"></i></td>";
										} else {
											echo "<td class=\"w-5\"><i class=\"fas fa-minus\"></i></td>";
											#echo "<td class=\"w-5\">".$channelgroupperm['permnegated']."</td>";
										}
										if($channelgroupperm['permskip'] == 1) {
											echo "<td class=\"w-5\"><i class=\"fas fa-check\"></i></td>";
										} else {
											echo "<td class=\"w-5\"><i class=\"fas fa-minus\"></i></td>";
											#echo "<td class=\"w-5\">".$channelgroupperm['permskip']."</td>";
										}
										echo "<td class=\"w-30 text-right\">";
											echo "<form method=\"post\" action=\"index.php?site=permissions&cgid=".$cgid."\" name=\"form_permission_edit\">";
												echo "<div class=\"form-group\">";
													echo "<input type=\"hidden\" name=\"hidden_cgid_permid\" value=".$permission['permid'].">";
													echo "<input type=\"hidden\" name=\"hidden_cgid\" value=".$cgid.">";
													echo "<input type=\"hidden\" name=\"hidden_cgid_permsid\" value=".$channelgroupperm['permsid'].">";
													echo "<input type=\"submit\" class=\"btn btn-sm btn-primary\" name=\"permission_cgid_edit\" value=\"Ändern\">&nbsp;";
													echo "<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_permission_cgid_delete\" value=\"Löschen\">";
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
			}

			if($_GET['cid'] != "") {
				$cid = $_GET['cid'];
				$channelList = $tsAdmin->channelList();
				$channelPermList = $tsAdmin->channelPermList($cid,true);

				if($_POST['permission_channel_edit']) {
					?>
					<h3>Rechte ändern</h3>
					<form method="post" action="index.php?site=permissions&cid=<?php echo $_POST['hidden_cid']; ?>" name="form_channel_pw_edit">
						<div class="form-group">
							<table class="table table-striped">
								<tbody>
									<?php
									foreach($channelPermList['data'] as $channelperm) {
										if($channelperm['permsid'] == $_POST['hidden_cid_permsid']) {
											foreach($permissionList['data'] as $permission) {
												if($permission['permname'] == $_POST['hidden_cid_permsid']) {
													echo "<tr>";
														echo "<div class=\"input-group\">";
															echo "<td class=\"w-25\">".$permission['permdesc']."<br><i>".$permission['permname']."</i></td>";
															echo "<td class=\"w-75\"><input id=\"edit_permission_value\" type=\"text\" class=\"form-control\" name=\"edit_permission_value\" placeholder=\"".$channelperm['permvalue']."\" value=\"".$channelperm['permvalue']."\"></td>";
														echo "</div>";
													echo "</tr>";
												}
											}
										}
									}
									?>
									<tr>
										<div class="input-group">
											<td>&nbsp;</td>
											<td><input type="hidden" name="hidden_permname" value="<?php echo $_POST['hidden_cid_permsid']; ?>"><input type="hidden" name="hidden_cid" value="<?php echo $cid; ?>"><input type="submit" class="btn btn-success" name="button_cid_permission_edit" value="Ändern"></td>
										</div>
									</tr>
								</tbody>
							</table>
						</div>
					</form>
					<?php
				} else {
				?>

					<h3>
					<?php
						foreach($channelList['data'] as $channels) {
							if($channels['cid'] == $cid) {
								echo $channels['channel_name'];
							}
						}
					?>
					</h3>
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>ID</th>
								<th>Beschreibung</th>
								<th><a data-toggle="tooltip" data-placement="top" title="<?php echo $toggle_value; ?>">Wert</a></th>
								<th><a data-toggle="tooltip" data-placement="top" title="<?php echo $toggle_negated; ?>">Negiert</a></th>
								<th><a data-toggle="tooltip" data-placement="top" title="<?php echo $toggle_skip; ?>">Überspringen</a></th>
								<th class="text-right">Aktion</th>
							</tr>
						</thead>
						<tbody>
					
							<?php
							foreach($permissionList['data'] as $permission) {
								foreach($channelPermList['data'] as $channelperm) {
									if($permission['permname'] == $channelperm['permsid']) {
										echo "<tr>";
											echo "<td class=\"w-5\">".$permission['permid']."</td>";
											if($permission['permdesc'] == "") {
												echo "<td class=\"w-50\">Keine Beschreibung verfügbar<br><i>".$channelperm['permsid']."</i></td>";
											} else {
												echo "<td class=\"w-50\">".$permission['permdesc']."<br><i>".$channelperm['permsid']."</i></td>";
											}
											echo "<td class=\"w-5\">".$channelperm['permvalue']."</td>";
											if($channelperm['permnegated'] == 1) {
												echo "<td class=\"w-5\"><i class=\"fas fa-check\"></i></td>";
											} else {
												echo "<td class=\"w-5\"><i class=\"fas fa-minus\"></i></td>";
												#echo "<td class=\"w-5\">".$channelperm['permnegated']."</td>";
											}
											if($channelperm['permskip'] == 1) {
												echo "<td class=\"w-5\"><i class=\"fas fa-check\"></i></td>";
											} else {
												echo "<td class=\"w-5\"><i class=\"fas fa-minus\"></i></td>";
												#echo "<td class=\"w-5\">".$channelperm['permskip']."</td>";
											}
											echo "<td class=\"w-30 text-right\">";
												echo "<form method=\"post\" action=\"index.php?site=permissions&cid=".$cid."\" name=\"form_permission_edit\">";
													echo "<div class=\"form-group\">";
														echo "<input type=\"hidden\" name=\"hidden_cid_permid\" value=".$permission['permid'].">";
														echo "<input type=\"hidden\" name=\"hidden_cid\" value=".$cid.">";
														echo "<input type=\"hidden\" name=\"hidden_cid_permsid\" value=".$channelperm['permsid'].">";
														echo "<input type=\"submit\" class=\"btn btn-sm btn-primary\" name=\"permission_channel_edit\" value=\"Ändern\">&nbsp;";
														echo "<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_permission_channel_delete\" value=\"Löschen\">";
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