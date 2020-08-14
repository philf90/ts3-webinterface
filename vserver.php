<?php
if(isset($_SESSION['loggedin'])) {
	if(!isset($_SESSION['vserver_port'])) {
		echo "<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Es wurde kein vServer aus der Serverliste ausgewählt!</div>";
	} else {
		if($tsAdmin->getElement('success', $tsAdmin->selectServer($_SESSION['vserver_port']))) {
			
			$tsAdmin->selectServer($_SESSION['vserver_port']);
			
			if($_POST['button_vserver_pw_edit']) {
				$data = array();
				$data['virtualserver_password'] = $_POST['edit_password'];
				if($tsAdmin->getElement('success', $tsAdmin->serverEdit($data))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>Passwort erfolgreich geändert.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Passwort konnte nicht geändert werden.</strong>";
					echo "</div>";
				}

			}
			
			if($_POST['vserver_pw_delete']) {
				$data = array();
				$data['virtualserver_password'] = "";
				if($tsAdmin->getElement('success', $tsAdmin->serverEdit($data))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>Passwort erfolgreich gelöscht.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Passwort konnte nicht gelöscht werden.</strong>";
					echo "</div>";
				}
			}
			
			if($_POST['button_vserver_edit']) {
				$data = array();
				$data['virtualserver_name'] = $_POST['edit_servername'];
				$data['virtualserver_welcomemessage'] = $_POST['edit_welcomemessage'];
				if($_POST['edit_codec_encryption_mode'] == "Global aus") {
					$data['virtualserver_codec_encryption_mode'] = 1;
				} elseif($_POST['edit_codec_encryption_mode'] == "Global an") {
					$data['virtualserver_codec_encryption_mode'] = 2;
				} else {
					$data['virtualserver_codec_encryption_mode'] = 0;
				}
				if($_POST['edit_autostart'] == "Ja") {
					$data['virtualserver_autostart'] = 1;
				} else {
					$data['virtualserver_autostart'] = 0;
				}
				if($_POST['edit_weblist'] == "Ja") {
					$data['virtualserver_weblist_enabled'] = 1;
				} else {
					$data['virtualserver_weblist_enabled'] = 0;
				}
				$data['virtualserver_default_server_group'] = $_POST['edit_default_server_group'];
				$data['virtualserver_default_channel_group'] = $_POST['edit_default_channel_group'];
				$data['virtualserver_default_channel_admin_group'] = $_POST['edit_default_channel_admin_group'];
				if($_POST['edit_hostmessage_mode'] == "Keine Nachricht") {
					$data['virtualserver_hostmessage_mode'] = 0;
				} elseif($_POST['edit_hostmessage_mode'] == "Nachricht im Log anzeigen") {
					$data['virtualserver_hostmessage_mode'] = 1;
				} elseif($_POST['edit_hostmessage_mode'] == "Nachricht als Fenster anzeigen") {
					$data['virtualserver_hostmessage_mode'] = 2;
				} else {
					$data['virtualserver_hostmessage_mode'] = 3;
				}
				$data['virtualserver_hostmessage'] = $_POST['edit_hostmessage'];
				$data['virtualserver_hostbanner_url'] = $_POST['edit_hostbanner_url'];
				$data['virtualserver_hostbanner_gfx_url'] = $_POST['edit_hostbanner_gfx_url'];
				$data['virtualserver_hostbanner_gfx_interval'] = $_POST['edit_hostbanner_gfx_interval'];
				if($_POST['edit_hostbanner_mode'] == "Anpassen, Seitenverhältnis ignorieren") {
					$data['virtualserver_hostbanner_mode'] = 1;
				} elseif($_POST['edit_hostbanner_mode'] == "Anpassen, Seitenverhältnis beachten") {
					$data['virtualserver_hostbanner_mode'] = 2;
				} else {
					$data['virtualserver_hostbanner_mode'] = 0;
				}
				$data['virtualserver_hostbutton_tooltip'] = $_POST['edit_hostbutton_tooltip'];
				$data['virtualserver_hostbutton_url'] = $_POST['edit_hostbutton_url'];
				$data['virtualserver_hostbutton_gfx_url'] = $_POST['edit_hostbutton_gfx_url'];
				if($tsAdmin->getElement('success', $tsAdmin->serverEdit($data))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>vServer erfolgreich editiert.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>vServer konnte nicht editiert werden.</strong>";
					echo "</div>";
				}
			}
			
			if($_POST['button_send_gm']) {
				if($tsAdmin->getElement('success', $tsAdmin->gm($_POST['gm']))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Nachricht <strong>".$_POST['gm']."</strong> als <strong>".$_POST['gm_setname']."</strong> erfolgreich gesendet.";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Nachricht konnte nicht gesendet werden.</strong>";
					echo "</div>";
				}
			}
			
			$info = $tsAdmin->serverInfo();
			$servergroups = $tsAdmin->serverGroupList();
			$channelgroups = $tsAdmin->channelGroupList();
			
			?>
			
			<div class="row content">
				<div class="col-sm-9">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vserver_edit"><i class="fas fa-pencil-alt"></i> Bearbeiten</button>
					<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vserver_pw_edit"><i class="fas fa-key"></i> Passwort ändern</button>-->
				</div>
				<div class="col-sm-3 text-right">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#view_logs"><i class="far fa-newspaper"></i> Logs</button>
					<button type="button" class="btn btn-primary" onclick="window.location.href=window.location.href;"><i class="fas fa-sync-alt"></i> Refresh</button>
				</div>
			</div>
			<br>

			<!-- Modal PASSWORT ÄNDERN -->
			<div id="vserver_pw_edit" class="modal fade">
				<div class="modal-dialog modal-lg">

					<!-- Modal content-->
					<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Passwort ändern</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<form method="post" action="index.php?site=vserver" name="form_vserver_pw_edit">
							<div class="form-group">
								<table class="table table-striped">
									<tbody>
										<div class="row content">
											<tr>
												<div class="input-group">
													<td class="col-xs-2">Passwort</td>
													<td class="col-xs-10"><input id="edit_password" type="password" class="form-control" name="edit_password"></td>
												</div>
											</tr>
										</div>
										<div class="row content">
											<tr>
												<div class="input-group">
													<td>&nbsp;</td>
													<td>
														<input type="submit" class="btn btn-success" name="button_vserver_pw_edit" value="Ändern">
														<?php
														if($info['data']['virtualserver_password'] != "") {
														?>
															<input type="submit" class="btn btn-danger" name="vserver_pw_delete" value="Passwort löschen">
														<?php
														}
														?>
													</td>
												</div>
											</tr>
										</div>
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

			<!-- Modal VSERVER BEARBEITEN -->
			<div id="vserver_edit" class="modal fade">
				<div class="modal-dialog modal-lg">

					<!-- Modal content-->
					<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">vServer bearbeiten</h3>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<form method="post" action="index.php?site=vserver" name="form_vserver_edit">
							<div class="form-group">
								<h4>Allgemein</h4>
								<table class="table table-striped">
									<tbody>
										<tr>
											<div class="input-group">
												<td class="w-25">Servername</td>
												<td class="w-75"><input id="edit_servername" type="text" class="form-control" name="edit_servername" placeholder="<?php echo $info['data']['virtualserver_name']; ?>"></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Willkommensnachricht</td>
												<td><textarea class="form-control" rows="3" name="edit_welcomemessage" id="edit_welcomemessage" placeholder="<?php echo $info['data']['virtualserver_welcomemessage']; ?>"><?php echo $info['data']['virtualserver_welcomemessage']; ?></textarea></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Codec Encryption Mode</td>
												<td>
													<select class="form-control" name="edit_codec_encryption_mode" id="edit_codec_encryption_mode">
														<?php
															if($info['data']['virtualserver_codec_encryption_mode'] == 1) {
																echo "<option selected>Global aus</option>
																	<option>Global an</option>
																	<option>Channel individuell einstellen</option>";
															} elseif($info['data']['virtualserver_codec_encryption_mode'] == 2) {
																echo "<option selected>Global an</option>
																	<option>Global aus</option>
																	<option>Channel individuell einstellen</option>";
															} else {
																echo "<option selected>Channel individuell einstellen</option>
																	<option>Global aus</option>
																	<option>Global an</option>";
															}
														?>
													</select>
												</td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Autostart</td>
												<td>
													<select class="form-control" name="edit_autostart" id="edit_autostart">
														<?php
															if($info['data']['virtualserver_autostart'] == 1) {
																echo "<option selected>Ja</option>
																	<option>Nein</option>";
															} else {
																echo "<option selected>Nein</option>
																	<option>Ja</option>";
															}
														?>
													</select>
												</td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Server in Webliste</td>
												<td>
													<select class="form-control" name="edit_weblist" id="edit_weblist">
														<?php
															if($info['data']['virtualserver_weblist_enabled'] == 1) {
																echo "<option selected>Ja</option>
																	<option>Nein</option>";
															} else {
																echo "<option selected>Nein</option>
																	<option>Ja</option>";
															}
														?>
													</select>
												</td>
											</div>
										</tr>
									</tbody>
								</table>
								<h4>Default Groups</h4>
								<table class="table table-striped">
									<tbody>
										<tr>
											<div class="input-group">
												<td class="w-25">Default Server Group</td>
												<td class="w-75">
													<select class="form-control" name="edit_default_server_group">";
														<?php									
															foreach($servergroups['data'] as $servergroup) {
																if($servergroup['sgid'] > 5) {
																	if ($servergroup['sgid'] == $info['data']['virtualserver_default_server_group']) {
																		echo "<option value=\"".$servergroup['sgid']."\" selected>[".$servergroup['sgid']."] ".$servergroup['name']."</option>";
																	} else {
																		echo "<option value=\"".$servergroup['sgid']."\">[".$servergroup['sgid']."] ".$servergroup['name']."</option>";
																	}
																}
															}
														?>
													</select>
												</td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Default Channel Group</td>
												<td>
													<select class="form-control" name="edit_default_channel_group">";
														<?php									
															foreach($channelgroups['data'] as $channelgroup) {
																if($channelgroup['cgid'] > 4) {
																	if ($channelgroup['cgid'] == $info['data']['virtualserver_default_channel_group']) {
																		echo "<option value=\"".$channelgroup['cgid']."\" selected>[".$channelgroup['cgid']."] ".$channelgroup['name']."</option>";
																	} else {
																		echo "<option value=\"".$channelgroup['cgid']."\">[".$channelgroup['cgid']."] ".$channelgroup['name']."</option>";
																	}
																}
															}
														?>
													</select>
												</td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Default Channel Admin Group</td>
												<td>
													<select class="form-control" name="edit_default_channel_admin_group">";
														<?php									
															foreach($channelgroups['data'] as $channelgroup) {
																if($channelgroup['cgid'] > 4) {
																	if ($channelgroup['cgid'] == $info['data']['virtualserver_default_channel_admin_group']) {
																		echo "<option value=\"".$channelgroup['cgid']."\" selected>[".$channelgroup['cgid']."] ".$channelgroup['name']."</option>";
																	} else {
																		echo "<option value=\"".$channelgroup['cgid']."\">[".$channelgroup['cgid']."] ".$channelgroup['name']."</option>";
																	}
																}
															}
														?>
													</select>
												</td>
											</div>
										</tr>
									</tbody>
								</table>
								<h4>Hostmessage</h4>
								<table class="table table-striped">
									<tbody>
										<tr>
											<div class="input-group">
												<td class="w-25">Hostmessage</td>
												<td class="w-75"><input id="edit_hostmessage" type="text" class="form-control" name="edit_hostmessage" value="<?php echo $info['data']['virtualserver_hostmessage']; ?>" placeholder="<?php echo $info['data']['virtualserver_hostmessage']; ?>"></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Hostmessage Mode</td>
												<td>
													<select class="form-control" name="edit_hostmessage_mode" id="edit_hostmessage_mode">
														<?php
															if($info['data']['virtualserver_hostmessage_mode'] == 0) {
																echo "<option selected>Keine Nachricht</option>
																	<option>Nachricht im Log anzeigen</option>
																	<option>Nachricht als Fenster anzeigen</option>
																	<option>Nachricht als Fenster anzeigen und Verbindung trennen</option>";
															} elseif($info['data']['virtualserver_hostmessage_mode'] == 1) {
																echo "<option selected>Nachricht im Log anzeigen</option>
																	<option>Keine Nachricht</option>
																	<option>Nachricht als Fenster anzeigen</option>
																	<option>Nachricht als Fenster anzeigen und Verbindung trennen</option>";
															} elseif($info['data']['virtualserver_hostmessage_mode'] == 2) {
																echo "<option selected>Nachricht als Fenster anzeigen</option>
																	<option>Keine Nachricht</option>
																	<option>Nachricht im Log anzeigen</option>
																	<option>Nachricht als Fenster anzeigen und Verbindung trennen</option>";
															} else {
																echo "<option selected>Nachricht als Fenster anzeigen und Verbindung trennen</option>
																	<option>Keine Nachricht</option>
																	<option>Nachricht im Log anzeigen</option>
																	<option>Nachricht als Fenster anzeigen</option>";
															}
														?>
													</select>
												</td>
											</div>
										</tr>
									</tbody>
								</table>
								<h4>Hostbanner</h4>
								<table class="table table-striped">
									<tbody>
										<tr>
											<div class="input-group">
												<td class="w-25">Hostbanner URL</td>
												<td class="w-75"><input id="edit_hostbanner_url" type="text" class="form-control" name="edit_hostbanner_url" value="<?php echo $info['data']['virtualserver_hostbanner_url']; ?>" placeholder="<?php echo $info['data']['virtualserver_hostbanner_url']; ?>"></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Hostbanner Gfx URL</td>
												<td><input id="edit_hostbanner_gfx_url" type="text" class="form-control" name="edit_hostbanner_gfx_url" value="<?php echo $info['data']['virtualserver_hostbanner_gfx_url']; ?>" placeholder="<?php echo $info['data']['virtualserver_hostbanner_gfx_url']; ?>"></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Hostbanner Gfx Intervall</td>
												<td><input id="edit_hostbanner_gfx_interval" type="text" class="form-control" name="edit_hostbanner_gfx_interval" value="<?php echo $info['data']['virtualserver_hostbanner_gfx_interval']; ?>" placeholder="<?php echo $info['data']['virtualserver_hostbanner_gfx_interval']; ?>"></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Hostbanner Mode</td>
												<td>
													<select class="form-control" name="edit_hostbanner_mode" id="edit_hostbanner_mode">
														<?php
															if($info['data']['virtualserver_hostbanner_mode'] == 1) {
																echo "<option selected>Anpassen, Seitenverhältnis ignorieren</option>
																	<option>Anpassen, Seitenverhältnis beachten</option>
																	<option>Nicht anpassen</option>";
															} elseif($info['data']['virtualserver_hostbanner_mode'] == 2) {
																echo "<option selected>Anpassen, Seitenverhältnis beachten</option>
																	<option>Anpassen, Seitenverhältnis ignorieren</option>
																	<option>Nicht anpassen</option>";
															} else {
																echo "<option selected>Nicht anpassen</option>
																	<option>Anpassen, Seitenverhältnis ignorieren</option>
																	<option>Anpassen, Seitenverhältnis beachten</option>";
															}
														?>
													</select>
												</td>
											</div>
										</tr>
									</tbody>
								</table>
								<h4>Hostbutton</h4>
								<table class="table table-striped">
									<tbody>
										<tr>
											<div class="input-group">
												<td class="w-25">Hostbutton Tooltip</td>
												<td class="w-75"><input id="edit_hostbutton_tooltip" type="text" class="form-control" name="edit_hostbutton_tooltip" value="<?php echo $info['data']['virtualserver_hostbutton_tooltip']; ?>" placeholder="<?php echo $info['data']['virtualserver_hostbutton_tooltip']; ?>"></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Hostbutton URL</td>
												<td><input id="edit_hostbutton_url" type="text" class="form-control" name="edit_hostbutton_url" value="<?php echo $info['data']['virtualserver_hostbutton_url']; ?>" placeholder="<?php echo $info['data']['virtualserver_hostbutton_url']; ?>"></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>Hostbutton Gfx URL</td>
												<td><input id="edit_hostbutton_gfx_url" type="text" class="form-control" name="edit_hostbutton_gfx_url" value="<?php echo $info['data']['virtualserver_hostbutton_gfx_url']; ?>" placeholder="<?php echo $info['data']['virtualserver_hostbutton_gfx_url']; ?>"></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>&nbsp;</td>
												<td><input type="submit" class="btn btn-success" name="button_vserver_edit" value="Bearbeiten"></td>
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

			<!-- VIEW LOGS -->
			<div id="view_logs" class="modal fade">
				<div class="modal-dialog modal-lg">

					<!-- Modal content-->
					<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Letzte 50 Logs</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<table class="table table-striped table-hover">
							<tbody>
								<?php
									$logView = $tsAdmin->logView(50,1,0);
									$i = 0;
									#print_r($logView);
									foreach($logView['data'] as $logs) {
										echo "<tr>";
											echo "<td>".$i."</td>";
											echo "<td>".$logs['l']."</td>";
										echo "</tr>";
										$i++;
									}
								?>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
					</div>
					</div>

				</div>
			</div>

			<h3>Globale Nachricht</h3>
			<form method="post" action="index.php?site=vserver" name="form_gm">
				<div class="form-group">
					<div class="table-responsive">
						<table class="table table-striped">
							<tbody>
								<tr>
									<div class="input-group">
										<td class="w-25">Nachricht</td>
										<td class="w-75"><textarea class="form-control" rows="3" name="gm" placeholder="Nachricht"></textarea></td>
									</div>
								</tr>
								<tr>
									<div class="input-group">
										<td>&nbsp;</td>
										<td><input type="submit" class="btn btn-success" name="button_send_gm" value="Senden"></td>
									</div>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</form>

			<h3>Passwort</h3>
			<form method="post" action="index.php?site=vserver" name="form_gm">
				<div class="form-group">
					<div class="table-responsive">
						<table class="table table-striped">
							<tbody>
								<tr>
									<div class="input-group">
										<td class="w-25">Passwort</td>
										<td class="w-75">
											<?php 
												if($info['data']['virtualserver_password'] == "") {
													echo "<input id=\"myInput\" type=\"password\" class=\"form-control\" name=\"edit_password\" placeholder=\"Kein Passwort gesetzt\">";
												} else {
													echo "<input id=\"myInput\" type=\"password\" class=\"form-control\" name=\"edit_password\" placeholder=\"".$info['data']['virtualserver_password']." (verschl&uuml;sselt)\">";
												}
											?>
										</td>
									</div>
								</tr>
								<tr>
									<div class="input-group">
										<td>&nbsp;</td>
										<td>
											<button class="btn btn-dark" type="button" onclick="myFunction()">Passwort anzeigen</button>
											<input type="submit" class="btn btn-success" name="button_vserver_pw_edit" value="Ändern">
											<?php
											if($info['data']['virtualserver_password'] != "") {
											?>
												<input type="submit" class="btn btn-danger" name="vserver_pw_delete" value="Passwort löschen">
											<?php
											}
											?>
										</td>
									</div>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</form>
			
			<h3>Info</h3>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th class="w-25">Info</th>
							<th class="w-75">Wert</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Name</td>
							<td><strong><?php echo $info['data']['virtualserver_name']; ?></strong></td>
						</tr>
						<tr>
							<td>UID</td>
							<td><?php echo $info['data']['virtualserver_unique_identifier']; ?></td>
						</tr>
						<tr>
							<td>ID</td>
							<td><?php echo $info['data']['virtualserver_id']; ?></td>
						</tr>
						<tr>
							<td>Port</td>
							<td><?php echo $info['data']['virtualserver_port']; ?></td>
						</tr>
						<tr>
							<td>Version</td>
							<td><?php echo $info['data']['virtualserver_version']; ?></td>
						</tr>
						<tr>
							<td>Plattform</td>
							<td><?php echo $info['data']['virtualserver_platform']; ?></td>
						</tr>
						<tr>
							<td>Erstellungsdatum</td>
							<td><?php echo date("d.m.Y - H:i",$info['data']['virtualserver_created']); ?></td>
						</tr>
						<tr>
							<td>Uptime</td>
							<td><?php echo $tsAdmin->convertSecondsToStrTime($info['data']['virtualserver_uptime']); ?></td>
						</tr>
						<tr>
							<td>Slots</td>
							<td><?php echo $info['data']['virtualserver_clientsonline']; ?> / <?php echo $info['data']['virtualserver_maxclients']; ?></td>
						</tr>
						<tr>
							<td>Channels</td>
							<td><?php echo $info['data']['virtualserver_channelsonline']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Allgemein</h3>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th class="w-25">Info</th>
							<th class="w-75">Wert</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Autostart</td>
							<td><?php 
									if($info['data']['virtualserver_autostart'] == 1) {
										echo "Ja";
									} else {
										echo "Nein";
									}
								?>
							</td>
						</tr>
						<tr>
							<td>Passwort</td>
							<td><?php 
									if($info['data']['virtualserver_password'] == "") {
										echo "<em>Keine Passwort gesetzt</em>";
									} else {
										echo $info['data']['virtualserver_password']." <em>(verschlüsselt <i class=\"fas fa-key\"></i>)</em>";
									}
								?>
							</td>
						</tr>
						<tr>
							<td>Codec Encryption Mode</td>
							<td><?php
									if($info['data']['virtualserver_codec_encryption_mode'] == 1) {
										echo "Global aus";
									} elseif($info['data']['virtualserver_codec_encryption_mode'] == 2) {
										echo "Global an";
									} else {
										echo "Channel individuell einstellen";
									}
								?></td>
						</tr>
						<tr>
							<td>Server in Webliste</td>
							<td><?php 
									if($info['data']['virtualserver_weblist_enabled'] == 1) {
										echo "Ja";
									} else {
										echo "Nein";
									}
								?></td>
						</tr>
						<tr>
							<td>Willkommensnachricht</td>
							<td><?php 
									if($info['data']['virtualserver_welcomemessage'] == "") {
										echo "<em>Keine Willkommensnachricht gesetzt</em>";
									} else {
										echo $info['data']['virtualserver_welcomemessage'];
									}
								?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Default Groups</h3>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th class="w-25">Info</th>
							<th class="w-75">Wert</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Default Server Group</td>
							<td>
								<?php
								foreach($servergroups['data'] as $servergroup) {
									if($servergroup['sgid'] == $info['data']['virtualserver_default_server_group']) {
										echo "[".$servergroup['sgid']."] ".$servergroup['name'];
									}
								}
								?>
							</td>
						</tr>
						<tr>
							<td>Default Channel Group</td>
							<td>
								<?php
								foreach($channelgroups['data'] as $channelgroup) {
									if($channelgroup['cgid'] == $info['data']['virtualserver_default_channel_group']) {
										echo "[".$channelgroup['cgid']."] ".$channelgroup['name'];
									}
								}
								?>
							</td>
						</tr>
						<tr>
							<td>Default Channel Admin Group</td>
							<td>
								<?php
								foreach($channelgroups['data'] as $channelgroup) {
									if($channelgroup['cgid'] == $info['data']['virtualserver_default_channel_admin_group']) {
										echo "[".$channelgroup['cgid']."] ".$channelgroup['name'];
									}
								}
								?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Hostmessage</h3>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th class="w-25">Info</th>
							<th class="w-75">Wert</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Hostmessage</td>
							<td><?php 
									if($info['data']['virtualserver_hostmessage'] == "") {
										echo "<em>Keine Hostmessage gesetzt</em>";
									} else {
										echo $info['data']['virtualserver_hostmessage'];
									}
								?>
							</td>
						</tr>
						<tr>
							<td>Hostmessage Mode</td>
							<td><?php if($info['data']['virtualserver_hostmessage_mode'] == 0) {
									echo "Keine Nachricht";
								} elseif($info['data']['virtualserver_hostmessage_mode'] == 1) {
									echo "Nachricht im Log anzeigen";
								} elseif($info['data']['virtualserver_hostmessage_mode'] == 2) {
									echo "Nachricht als Fenster anzeigen";
								} else {
									echo "Nachricht als Fenster anzeigen und Verbindung trennen";
								}
								?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Hostbanner</h3>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th class="w-25">Info</th>
							<th class="w-75">Wert</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Hostbanner URL</td>
							<td><?php echo $info['data']['virtualserver_hostbanner_url']; ?></td>
						</tr>
						<tr>
							<td>Hostbanner Gfx URL</td>
							<td><?php echo $info['data']['virtualserver_hostbanner_gfx_url']; ?></td>
						</tr>
						<tr>
							<td>Hostbanner Gfx Intervall</td>
							<td><?php echo $info['data']['virtualserver_hostbanner_gfx_interval']; ?></td>
						</tr>
						<tr>
							<td>Hostbanner Mode</td>
							<td><?php if($info['data']['virtualserver_hostbanner_mode'] == 0) {
									echo "Nicht anpassen";
								} elseif($info['data']['virtualserver_hostbanner_mode'] == 1) {
									echo "Anpassen, Seitenverhältnis ignorieren";
								} elseif($info['data']['virtualserver_hostbanner_mode'] == 2) {
									echo "Anpassen, Seitenverhältnis beachten";
								}
								?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Hostbutton</h3>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th class="w-25">Info</th>
							<th class="w-75">Wert</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Hostbutton Tooltip</td>
							<td><?php echo $info['data']['virtualserver_hostbutton_tooltip']; ?></td>
						</tr>
						<tr>
							<td>Hostbutton URL</td>
							<td><?php echo $info['data']['virtualserver_hostbutton_url']; ?></td>
						</tr>
						<tr>
							<td>Hostbutton Gfx URL</td>
							<td><?php echo $info['data']['virtualserver_hostbutton_gfx_url']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<h3>Client Versionen</h3>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th class="w-25">Info</th>
							<th class="w-75">Wert</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Min. Client Version</td>
							<td><?php echo "Build: ".$info['data']['virtualserver_min_client_version']; ?></td>
						</tr>
						<tr>
							<td>Min. Android Client Version</td>
							<td><?php echo "Build: ".$info['data']['virtualserver_min_android_version']; ?></td>
						</tr>
						<tr>
							<td>Min. iOS Client Version</td>
							<td><?php echo "Build: ".$info['data']['virtualserver_min_ios_version']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<?php
			
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
