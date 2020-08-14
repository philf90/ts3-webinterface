<?php
//SESSION LOGIN PRÜFEN
if(isset($_SESSION['loggedin'])) {
	//SESSION VSERVER PORT PRÜFEN
	if(!isset($_SESSION['vserver_port'])) {
		echo "<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Es wurde kein vServer aus der Serverliste ausgewählt!</div>";
	} else {
		//VSERVER AUSWÄHLEN
		if($tsAdmin->getElement('success', $tsAdmin->selectServer($_SESSION['vserver_port']))) {
			
			//CHANNEL LÖSCHEN
			if($_POST['button_channel_delete']) {
				if($tsAdmin->getElement('success', $tsAdmin->channelDelete($_POST['hidden_cid']))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Channel <strong>".$_POST['hidden_channel_name']."</strong> erfolgreich gelöscht.";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> Channel <strong>".$_POST['hidden_channel_name']."</strong> konnte nicht gelöscht werden.";
					echo "</div>";
				}
			}
			
			//CHANNEL PW LÖSCHEN
			if($_POST['button_channel_pw_delete']) {
				$data = array();
				$data['channel_password'] = "";
				if($tsAdmin->getElement('success', $tsAdmin->channelEdit($_POST['pw_edit_hidden_cid'],$data))) {
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
			
			//CHANNEL HINZUFÜGEN
			if($_POST['button_channel_add']) {
				$permissions = array();
				$data = array();
				$data['channel_name'] = $_POST['channelname'];
				$data['channel_description'] = $_POST['description'];
				if($_POST['channelpassword'] != "") {
					$data['channel_password'] = $_POST['channelpassword'];
				}
				if($_POST['permanent'] == "Ja") {
					$data['channel_flag_permanent'] = 1;
				} else {
					$data['channel_flag_semi_permanent'] = 1;
				}
				if($_POST['codec'] == "Speex Schmalband") {
					$data['channel_codec'] = 0;
				} elseif($_POST['codec'] == "Speex Breitband") {
					$data['channel_codec'] = 1;
				} elseif($_POST['codec'] == "Speex Ultra-Breitband") {
					$data['channel_codec'] = 2;
				} elseif($_POST['codec'] == "CELT Mono") {
					$data['channel_codec'] = 3;
				} elseif($_POST['codec'] == "Opus Voice") {
					$data['channel_codec'] = 4;
				} else {
					$data['channel_codec'] = 5;
				}
				if($_POST['codec_quality'] == "2.73 KiB/s") {
					$data['channel_codec_quality'] = 0;
				} elseif($_POST['codec_quality'] == "3.22 KiB/s") {
					$data['channel_codec_quality'] = 1;
				} elseif($_POST['codec_quality'] == "3.71 KiB/s") {
					$data['channel_codec_quality'] = 2;
				} elseif($_POST['codec_quality'] == "4.20 KiB/s") {
					$data['channel_codec_quality'] = 3;
				} elseif($_POST['codec_quality'] == "4.74 KiB/s") {
					$data['channel_codec_quality'] = 4;
				} elseif($_POST['codec_quality'] == "5.22 KiB/s") {
					$data['channel_codec_quality'] = 5;
				} elseif($_POST['codec_quality'] == "5.71 KiB/s") {
					$data['channel_codec_quality'] = 6;
				} elseif($_POST['codec_quality'] == "6.20 KiB/s") {
					$data['channel_codec_quality'] = 7;
				} elseif($_POST['codec_quality'] == "6.74 KiB/s") {
					$data['channel_codec_quality'] = 8;
				} elseif($_POST['codec_quality'] == "7.23 KiB/s") {
					$data['channel_codec_quality'] = 9;
				} else {
					$data['channel_codec_quality'] = 10;
				}
				if($_POST['parentchannel'] != "Kein übergeordneter Channel") {
					$data['cpid'] = $_POST['parentchannel'];
				}
				if($_POST['talk_power'] == "") {
					$data['channel_needed_talk_power'] = 0;
				} else {
					$data['channel_needed_talk_power'] = $_POST['talk_power'];
				}
				$permissions['i_channel_needed_permission_modify_power'] = 75;
				$permissions['i_channel_needed_modify_power'] = $_POST['modify_power'];
				$permissions['i_channel_needed_delete_power'] = $_POST['delete_power'];
				if($tsAdmin->getElement('success', $output = $tsAdmin->channelCreate($data))) {
					if($tsAdmin->getElement('success', $tsAdmin->channelAddPerm($output['data']['cid'],$permissions))) {
						echo "<div class=\"alert alert-success alert-dismissable\">";
						echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
						echo "Channel <strong>".$data['channel_name']."</strong> erfolgreich erstellt.";
						echo "</div>";
					}
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> Channel <strong>".$data['channel_name']."</strong> konnte nicht erstellt werden.";
					echo "</div>";
				}
			}
			
			//CHANNEL PW ÄNDERN
			if($_POST['button_channel_pw_edit']) {
				$data = array();
				$data['channel_password'] = $_POST['edit_channelpassword'];
				if($tsAdmin->getElement('success', $tsAdmin->channelEdit($_POST['pw_edit_hidden_cid'],$data))) {
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
			
			//CHANNEL ÄNDERN
			if($_POST['button_channel_edit']) {
				$data = array();
				if($_POST['channel_edit_hidden_channel_name'] != $_POST['edit_channelname']) {
					$data['channel_name'] = $_POST['edit_channelname'];
				}
				$data['channel_description'] = $_POST['edit_channel_description'];
				if($_POST['edit_codec'] == "Speex Schmalband") {
					$data['channel_codec'] = 0;
				} elseif($_POST['edit_codec'] == "Speex Breitband") {
					$data['channel_codec'] = 1;
				} elseif($_POST['edit_codec'] == "Speex Ultra-Breitband") {
					$data['channel_codec'] = 2;
				} elseif($_POST['edit_codec'] == "CELT Mono") {
					$data['channel_codec'] = 3;
				} elseif($_POST['edit_codec'] == "Opus Voice") {
					$data['channel_codec'] = 4;
				} else {
					$data['channel_codec'] = 5;
				}
				if($_POST['edit_codec_quality'] == "2.73 KiB/s") {
					$data['channel_codec_quality'] = 0;
				} elseif($_POST['edit_codec_quality'] == "3.22 KiB/s") {
					$data['channel_codec_quality'] = 1;
				} elseif($_POST['edit_codec_quality'] == "3.71 KiB/s") {
					$data['channel_codec_quality'] = 2;
				} elseif($_POST['edit_codec_quality'] == "4.20 KiB/s") {
					$data['channel_codec_quality'] = 3;
				} elseif($_POST['edit_codec_quality'] == "4.74 KiB/s") {
					$data['channel_codec_quality'] = 4;
				} elseif($_POST['edit_codec_quality'] == "5.22 KiB/s") {
					$data['channel_codec_quality'] = 5;
				} elseif($_POST['edit_codec_quality'] == "5.71 KiB/s") {
					$data['channel_codec_quality'] = 6;
				} elseif($_POST['edit_codec_quality'] == "6.20 KiB/s") {
					$data['channel_codec_quality'] = 7;
				} elseif($_POST['edit_codec_quality'] == "6.74 KiB/s") {
					$data['channel_codec_quality'] = 8;
				} elseif($_POST['edit_codec_quality'] == "7.23 KiB/s") {
					$data['channel_codec_quality'] = 9;
				} else {
					$data['channel_codec_quality'] = 10;
				}
				if($_POST['edit_channel_needed_talk_power'] == "") {
					$data['channel_needed_talk_power'] = 0;
				} else {
					$data['channel_needed_talk_power'] = $_POST['edit_channel_needed_talk_power'];
				}
				if($tsAdmin->getElement('success', $tsAdmin->channelEdit($_POST['channel_edit_hidden_cid'],$data))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Channel <strong>".$_POST['channel_edit_hidden_channel_name']."</strong> erfolgreich bearbeitet.";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> Channel <strong>".$_POST['channel_edit_hidden_channel_name']."</strong> konnte nicht bearbeitet werden.";
					echo "</div>";
				}
			}
			
			//TS3CLASS FUNKTIONSAUFRUFE
			$channels = $tsAdmin->channelList();
			$servergroups = $tsAdmin->serverGroupList();
			$channelgroups = $tsAdmin->channelGroupList();
			
			//CHANNEL HINZUFÜGEN INPUT
			if($_POST['channel_edit'] || $_POST['channel_pw_edit']) {
				
				if($_POST['channel_edit']) {
					$channelInfo = $tsAdmin->channelInfo($_POST['hidden_cid']);
					#print_r($channelInfo);
					?>
						<h3>Channel bearbeiten</h3>
						<form method="post" action="index.php?site=channel" name="form_channel_edit">
							<div class="form-group">
								<div class="table">	
									<table class="table table-striped">
										<tbody>
											<tr>
												<div class="input-group">
													<td class="w-25">Channelname</td>
													<td class="w-75"><input id="edit_channelname" type="text" class="form-control" name="edit_channelname" placeholder="<?php echo $channelInfo['data']['channel_name']; ?>" value="<?php echo $channelInfo['data']['channel_name']; ?>"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Beschreibung</td>
													<td><input id="edit_channel_description" type="text" class="form-control" name="edit_channel_description" placeholder="<?php echo $channelInfo['data']['channel_description']; ?>" value="<?php echo $channelInfo['data']['channel_description']; ?>"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Codec</td>
													<td>
														<select class="form-control" name="edit_codec" id="edit_codec">
															<?php
															if($channelInfo['data']['channel_codec'] == 0) {
																?>
																<option selected>Speex Schmalband</option>
																<option>Speex Breitband</option>
																<option>Speex Ultra-Breitband</option>
																<option>CELT Mono</option>
																<option>Opus Voice</option>
																<option>Opus Music</option>
																<?php
															} elseif($channelInfo['data']['channel_codec'] == 1) {
																?>
																<option>Speex Schmalband</option>
																<option selected>Speex Breitband</option>
																<option>Speex Ultra-Breitband</option>
																<option>CELT Mono</option>
																<option>Opus Voice</option>
																<option>Opus Music</option>
																<?php
															} elseif($channelInfo['data']['channel_codec'] == 2) {
																?>
																<option>Speex Schmalband</option>
																<option>Speex Breitband</option>
																<option selected>Speex Ultra-Breitband</option>
																<option>CELT Mono</option>
																<option>Opus Voice</option>
																<option>Opus Music</option>
																<?php
															} elseif($channelInfo['data']['channel_codec'] == 3) {
																?>
																<option>Speex Schmalband</option>
																<option>Speex Breitband</option>
																<option>Speex Ultra-Breitband</option>
																<option selected>CELT Mono</option>
																<option>Opus Voice</option>
																<option>Opus Music</option>
																<?php
															} elseif($channelInfo['data']['channel_codec'] == 4) {
																?>
																<option>Speex Schmalband</option>
																<option>Speex Breitband</option>
																<option>Speex Ultra-Breitband</option>
																<option>CELT Mono</option>
																<option selected>Opus Voice</option>
																<option>Opus Music</option>
																<?php
															} else {
																?>
																<option>Speex Schmalband</option>
																<option>Speex Breitband</option>
																<option>Speex Ultra-Breitband</option>
																<option>CELT Mono</option>
																<option>Opus Voice</option>
																<option selected>Opus Music</option>
																<?php
															}
														?>
														</select>
													</td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Qualität</td>
													<td>
														<select class="form-control" name="edit_codec_quality" id="edit_codec_quality">
															<?php
															if($channelInfo['data']['channel_codec_quality'] == 0) {
																?>
																<option selected>2.73 KiB/s</option>
																<option>3.22 KiB/s</option>
																<option>3.71 KiB/s</option>
																<option>4.20 KiB/s</option>
																<option>4.74 KiB/s</option>
																<option>5.22 KiB/s</option>
																<option>5.71 KiB/s</option>
																<option>6.20 KiB/s</option>
																<option>6.74 KiB/s</option>
																<option>7.23 KiB/s</option>
																<option>7.71 KiB/s</option>
																<?php
															} elseif($channelInfo['data']['channel_codec_quality'] == 1) {
																?>
																<option >2.73 KiB/s</option>
																<option selected>3.22 KiB/s</option>
																<option>3.71 KiB/s</option>
																<option>4.20 KiB/s</option>
																<option>4.74 KiB/s</option>
																<option>5.22 KiB/s</option>
																<option>5.71 KiB/s</option>
																<option>6.20 KiB/s</option>
																<option>6.74 KiB/s</option>
																<option>7.23 KiB/s</option>
																<option>7.71 KiB/s</option>
																<?php
															} elseif($channelInfo['data']['channel_codec_quality'] == 2) {
																?>
																<option >2.73 KiB/s</option>
																<option>3.22 KiB/s</option>
																<option selected>3.71 KiB/s</option>
																<option>4.20 KiB/s</option>
																<option>4.74 KiB/s</option>
																<option>5.22 KiB/s</option>
																<option>5.71 KiB/s</option>
																<option>6.20 KiB/s</option>
																<option>6.74 KiB/s</option>
																<option>7.23 KiB/s</option>
																<option>7.71 KiB/s</option>
																<?php
															} elseif($channelInfo['data']['channel_codec_quality'] == 3) {
																?>
																<option >2.73 KiB/s</option>
																<option>3.22 KiB/s</option>
																<option>3.71 KiB/s</option>
																<option selected>4.20 KiB/s</option>
																<option>4.74 KiB/s</option>
																<option>5.22 KiB/s</option>
																<option>5.71 KiB/s</option>
																<option>6.20 KiB/s</option>
																<option>6.74 KiB/s</option>
																<option>7.23 KiB/s</option>
																<option>7.71 KiB/s</option>
																<?php
															} elseif($channelInfo['data']['channel_codec_quality'] == 4) {
																?>
																<option >2.73 KiB/s</option>
																<option>3.22 KiB/s</option>
																<option>3.71 KiB/s</option>
																<option>4.20 KiB/s</option>
																<option selected>4.74 KiB/s</option>
																<option>5.22 KiB/s</option>
																<option>5.71 KiB/s</option>
																<option>6.20 KiB/s</option>
																<option>6.74 KiB/s</option>
																<option>7.23 KiB/s</option>
																<option>7.71 KiB/s</option>
																<?php
															} elseif($channelInfo['data']['channel_codec_quality'] == 5) {
																?>
																<option >2.73 KiB/s</option>
																<option>3.22 KiB/s</option>
																<option>3.71 KiB/s</option>
																<option>4.20 KiB/s</option>
																<option>4.74 KiB/s</option>
																<option selected>5.22 KiB/s</option>
																<option>5.71 KiB/s</option>
																<option>6.20 KiB/s</option>
																<option>6.74 KiB/s</option>
																<option>7.23 KiB/s</option>
																<option>7.71 KiB/s</option>
																<?php
															} elseif($channelInfo['data']['channel_codec_quality'] == 6) {
																?>
																<option >2.73 KiB/s</option>
																<option>3.22 KiB/s</option>
																<option>3.71 KiB/s</option>
																<option>4.20 KiB/s</option>
																<option>4.74 KiB/s</option>
																<option>5.22 KiB/s</option>
																<option selected>5.71 KiB/s</option>
																<option>6.20 KiB/s</option>
																<option>6.74 KiB/s</option>
																<option>7.23 KiB/s</option>
																<option>7.71 KiB/s</option>
																<?php
															} elseif($channelInfo['data']['channel_codec_quality'] == 7) {
																?>
																<option >2.73 KiB/s</option>
																<option>3.22 KiB/s</option>
																<option>3.71 KiB/s</option>
																<option>4.20 KiB/s</option>
																<option>4.74 KiB/s</option>
																<option>5.22 KiB/s</option>
																<option>5.71 KiB/s</option>
																<option selected>6.20 KiB/s</option>
																<option>6.74 KiB/s</option>
																<option>7.23 KiB/s</option>
																<option>7.71 KiB/s</option>
																<?php
															} elseif($channelInfo['data']['channel_codec_quality'] == 8) {
																?>
																<option >2.73 KiB/s</option>
																<option>3.22 KiB/s</option>
																<option>3.71 KiB/s</option>
																<option>4.20 KiB/s</option>
																<option>4.74 KiB/s</option>
																<option>5.22 KiB/s</option>
																<option>5.71 KiB/s</option>
																<option>6.20 KiB/s</option>
																<option selected>6.74 KiB/s</option>
																<option>7.23 KiB/s</option>
																<option>7.71 KiB/s</option>
																<?php
															} elseif($channelInfo['data']['channel_codec_quality'] == 9) {
																?>
																<option >2.73 KiB/s</option>
																<option>3.22 KiB/s</option>
																<option>3.71 KiB/s</option>
																<option>4.20 KiB/s</option>
																<option>4.74 KiB/s</option>
																<option>5.22 KiB/s</option>
																<option>5.71 KiB/s</option>
																<option>6.20 KiB/s</option>
																<option>6.74 KiB/s</option>
																<option selected>7.23 KiB/s</option>
																<option>7.71 KiB/s</option>
																<?php
															} else {
																?>
																<option >2.73 KiB/s</option>
																<option>3.22 KiB/s</option>
																<option>3.71 KiB/s</option>
																<option>4.20 KiB/s</option>
																<option>4.74 KiB/s</option>
																<option>5.22 KiB/s</option>
																<option>5.71 KiB/s</option>
																<option>6.20 KiB/s</option>
																<option>6.74 KiB/s</option>
																<option>7.23 KiB/s</option>
																<option selected>7.71 KiB/s</option>
																<?php
															}
																?>
														</select>
													</td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Talk Power</td>
													<td><input id="edit_channel_needed_talk_power" type="text" class="form-control" name="edit_channel_needed_talk_power" placeholder="<?php echo $channelInfo['data']['channel_needed_talk_power']; ?>" value="<?php echo $channelInfo['data']['channel_needed_talk_power']; ?>"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>&nbsp;</td>
													<td><input type="hidden" name="channel_edit_hidden_channel_name" value="<?php echo $_POST['hidden_channel_name']; ?>"><input type="hidden" name="channel_edit_hidden_cid" value="<?php echo $_POST['hidden_cid']; ?>"><input type="submit" class="btn btn-success" name="button_channel_edit" value="Ändern"> <input type="submit" class="btn btn-danger" name="button_channel_edit_abort" value="Abbrechen"></td>
												</div>
											</tr>
										</tbody>
									</table>
														</div>
							</div>
						</form>
					<?php
				}
				
				if($_POST['channel_pw_edit']) {
					$channelInfo = $tsAdmin->channelInfo($_POST['hidden_cid']);
					?>

						<h3>Passwort ändern</h3>
						<form method="post" action="index.php?site=channel" name="form_channel_pw_edit">
							<div class="form-group">
								<table class="table table-striped">
									<tbody>
										<tr>
											<div class="input-group">
												<td class="w-25">Passwort</td>
												<td class="w-75"><input id="myInput" type="password" class="form-control" name="edit_channelpassword"></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>&nbsp;</td>
												<td>
													<input type="hidden" name="pw_edit_hidden_cid" value="<?php echo $_POST['hidden_cid']; ?>">
													<button class="btn btn-dark" type="button" onclick="myFunction()">Passwort anzeigen</button>
													<input type="submit" class="btn btn-success" name="button_channel_pw_edit" value="Ändern">
													<input type="submit" class="btn btn-danger" name="button_channel_pw_edit_abort" value="Abbrechen">
													<?php
													if(!empty($channelInfo['data']['channel_password'])) {
														?>
														<input type="submit" class="btn btn-danger" name="button_channel_pw_delete" value="Passwort löschen">
													<?php
													}
													?>
												</td>
											</div>
										</tr>
									</tbody>
								</table>
							</div>
						</form>
					<?php
				}
				
			} else {
			
			?>

				<div class="row content">
					<div class="col-sm-9">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#channel_add"><i class="fas fa-plus"></i> Channel erstellen</button>
					</div>
					<div class="col-sm-3 text-right">
						<button type="button" class="btn btn-primary" onclick="window.location.href=window.location.href;"><i class="fas fa-sync-alt"></i> Refresh</button>
					</div>
				</div>
				<br>

				<!-- Modal CHANNEL ADD -->
				<div id="channel_add" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg">

						<!-- Modal content-->
						<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title">Channel erstellen</h3>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<form method="post" action="index.php?site=channel" name="form_channel_add">
								<div class="form-group">
									<table class="table table-striped">
										<tbody>
											<tr>
												<div class="input-group">
													<td class="w-25">Channelname</td>
													<td class="w-75"><input id="channelname" type="text" class="form-control" name="channelname" placeholder="Channelname"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Beschreibung</td>
													<td><input id="description" type="text" class="form-control" name="description" placeholder="Beschreibung"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Channel Passwort</td>
													<td><input id="channelpassword" type="password" class="form-control" name="channelpassword"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Permanent</td>
													<td>
														<select class="form-control" name="permanent" id="permanent">
															<option select>Ja</option>
															<option>Nein</option>
														</select>
													</td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Codec</td>
													<td>
														<select class="form-control" name="codec" id="codec">
															<option>Speex Schmalband</option>
															<option>Speex Breitband</option>
															<option>Speex Ultra-Breitband</option>
															<option>CELT Mono</option>
															<option selected>Opus Voice</option>
															<option>Opus Music</option>
														</select>
													</td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Codec Qualität</td>
													<td>
														<select class="form-control" name="codec_quality" id="codec_quality">
															<option>2.73 KiB/s</option>
															<option>3.22 KiB/s</option>
															<option>3.71 KiB/s</option>
															<option>4.20 KiB/s</option>
															<option>4.74 KiB/s</option>
															<option>5.22 KiB/s</option>
															<option selected>5.71 KiB/s</option>
															<option>6.20 KiB/s</option>
															<option>6.74 KiB/s</option>
															<option>7.23 KiB/s</option>
															<option>7.71 KiB/s</option>
														</select>
													</td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Einordnen nach</td>
													<td>
														<select class="form-control" name="parentchannel" id="parentchannel">
															<option selected>Kein übergeordneter Channel</<option>
															<?php
																foreach($channels['data'] as $channel) {
																	//$channelInfo = $tsAdmin->channelInfo($channel['cid']);
																	echo "<option value=\"".$channel['cid']."\">[".$channel['cid']."] ".$channel['channel_name']."</option>";
																}
															?>
														</select>
													</td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Talk Power</td>
													<td><input id="talk_power" type="text" class="form-control" name="talk_power" placeholder="0"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Modify Power</td>
													<td><input id="talk_power" type="text" class="form-control" name="modify_power" placeholder="75" value="75"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>Delete Power</td>
													<td><input id="talk_power" type="text" class="form-control" name="delete_power" placeholder="75" value="75"></td>
												</div>
											</tr>
											<tr>
												<div class="input-group">
													<td>&nbsp;</td>
													<td><input type="submit" class="btn btn-success" name="button_channel_add" value="Erstellen"></td>
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
							<tr class="d-flex">
								<th class="col-1">Channel ID</th>
								<th class="col-1">Parent ID</th>
								<th class="col-7">Name</th>
								<th class="col-3 text-right">Aktion</th>
							</tr>
						</thead>
						<tbody>
							<?php
								#AUSGABE DER CHANNELS
								foreach($channels['data'] as $channel) {
									echo "<tr class=\"d-flex\">";
										echo "<td class=\"col-1\">[".$channel['cid']."]</td>";
										echo "<td class=\"col-1\">[".$channel['pid']."]</td>";
										echo "<td class=\"col-6\">";
											$channelInfo = $tsAdmin->channelInfo($channel['cid']); // Channel Info abfragen
											$channelClientList = $tsAdmin->channelClientList($channel['cid'], "-groups"); // Clients innerhalb des Channels inkl. der Gruppen abfragen
											#ABFRAGE CLIENTANZAHL FÜR TOGGLE CLIENTS
											if($channel['total_clients'] != 0) {
												#CLIENTS VORHANDEN, TOGGLE WIRD ANGEZEIGT
												#EINRÜCKEN VON SUBCHANNELS
												if($channel['pid'] > 0) {
													echo "&nbsp;&nbsp;&nbsp;<a href=\"#".$channel['cid']."\"data-toggle=\"collapse\">".$channel['channel_name']."</a>";
												} else {
													echo "<a href=\"#".$channel['cid']."\" data-toggle=\"collapse\">".$channel['channel_name']."</a>";
												}
												#MARKIERUNG DES DEFAULT CHANNELS
												if($channelInfo['data']['channel_flag_default'] == 1) {
													echo " <i class=\"fas fa-check-circle\"></i>";
												}
												#ABFRAGE NACH CHANNELPASSWORT
												if($channelInfo['data']['channel_flag_password'] == 1) {
													echo " <i class=\"fas fa-lock\"></i>";
												}
												#CHANNEL MODERIERT ODER NICHT
												if($channelInfo['data']['channel_needed_talk_power'] > 0) {
													echo " <i class=\"fas fa-microphone-slash\"></i>";
												}
												#ABFRAGE DER CHANNELICONS
												if($tsAdmin->getElement('success', $channelIcon = $tsAdmin->channelGetIconByChannelID($channel['cid']))) {
													echo " <img src=\"data:image/png;base64,".$channelIcon['data']."\" />";
												}
												#AUSGABE DER AKTIVEN CLIENTS IM CHANNEL
												echo " <em>(".$channel['total_clients']." User online)</em>";
												#AUSGABE DES TOGGLES
												echo "<div id=\"".$channel['cid']."\" class=\"collapse\">";
													#AUSGABE DER CLIENTS IM CHANNEL
													foreach($channelClientList['data'] as $channelClients) {
														$clientInfo = $tsAdmin->clientInfo($channelClients['clid']);
														$toggle = $clientInfo['data']['client_version']." ".$clientInfo['data']['client_platform'];
														$i = 0; // Laufvariable für Ausgabe der Servergruppenicons
														#EINRÜCKEN DER CLIENTS IM SUBCHANNEL
														if($channel['pid'] > 0) {
															#SUBCHANNEL CLIENTS WERDEN EINGERÜCKT
															echo "&nbsp;&nbsp;&nbsp;";
															$sgroups = explode(",",$channelClients['client_servergroups']);
															#AUSGABE DER SERVERGRUPPEN ICONS VOR DEM NAMEN
															foreach($servergroups['data'] as $servergroup) {
																if($servergroup['sgid'] == $sgroups[$i]) {
																	if($servergroup['name'] != "Guest") {
																		echo "<span class=\"badge badge-dark\">".$servergroup['name']."</span> ";
																	}
																	#if($tsAdmin->getElement('success', $servergroupicon = $tsAdmin->serverGroupGetIconBySGID($sgroups[$i]))) {
																	#	echo "<img src=\"data:image/png;base64,".$servergroupicon['data']."\" /> ";
																	#}
																	$i++;                                        
																}
															}
															#AUSGABE DER CHANNELGRUPPEN ICONS VOR DEM NAMEN
															foreach($channelgroups['data'] as $channelgroup) {
																if($channelgroup['cgid'] == $channelClients['client_channel_group_id']) {
																	if($channelgroup['name'] != "Guest") {
																		echo "<span class=\"badge badge-info\">".$channelgroup['name']."</span> ";
																	}
																	#if($tsAdmin->getElement('success', $channelgroupicon = $tsAdmin->channelGroupGetIconByCGID($channelClients['client_channel_group_id']))) {
																	#	echo "<img src=\"data:image/png;base64,".$channelgroupicon['data']."\" /> ";
																	#}
																}
															}
															if($clientInfo['data']['client_input_muted'] == 1) {
																echo " <i class=\"fas fa-microphone-slash\"></i>";
															} else {
																if($clientInfo['data']['client_input_hardware'] == 1) {
																	echo " <i class=\"fas fa-microphone\"></i>";
																}
															}
															if($clientInfo['data']['client_output_muted'] == 1) {
																echo " <i class=\"fas fa-volume-off\"></i> ";
															} else {
																if($clientInfo['data']['client_output_hardware'] == 1) {
																	echo " <i class=\"fas fa-volume-up\"></i> ";
																}
															}
															#AUSGABE DES NAMEN INKLUSIVE LINK ZUR USERDETAIL SEITE
															echo "<a data-toggle=\"tooltip\" data-placement=\"right\" title=\"".$toggle."\" href=\"index.php?site=userdetail&cldbid=".$channelClients['client_database_id']."\">".$channelClients['client_nickname']."</a><br>";
														} else {
															#KEIN SUBCHANNEL, KEIN EINRÜCKEN DER CLIENTS
															$sgroups = explode(",",$channelClients['client_servergroups']);
															#AUSGABE DER SERVERGRUPPEN ICONS VOR DEM NAMEN
															foreach($servergroups['data'] as $servergroup) {
																if($servergroup['sgid'] == $sgroups[$i]) {
																	if($servergroup['name'] != "Guest") {
																		echo "<span class=\"badge badge-dark\">".$servergroup['name']."</span> ";
																	}
																	#if($tsAdmin->getElement('success', $servergroupicon = $tsAdmin->serverGroupGetIconBySGID($sgroups[$i]))) {
																	#	echo "<img src=\"data:image/png;base64,".$servergroupicon['data']."\" /> ";
																	#}
																	$i++;                                        
																}
															}
															#AUSGABE DER CHANNELGRUPPEN ICONS VOR DEM NAMEN
															foreach($channelgroups['data'] as $channelgroup) {
																if($channelgroup['cgid'] == $channelClients['client_channel_group_id']) {
																	if($channelgroup['name'] != "Guest") {
																		echo "<span class=\"badge badge-info\">".$channelgroup['name']."</span> ";
																	}
																	#if($tsAdmin->getElement('success', $channelgroupicon = $tsAdmin->channelGroupGetIconByCGID($channelClients['client_channel_group_id']))) {
																	#	echo "<img src=\"data:image/png;base64,".$channelgroupicon['data']."\" /> ";
																	#}
																}
															}
															if($clientInfo['data']['client_input_muted'] == 1) {
																echo " <i class=\"fas fa-microphone-slash\"></i>";
															} else {
																if($clientInfo['data']['client_input_hardware'] == 1) {
																	echo " <i class=\"fas fa-microphone\"></i>";
																}
															}
															if($clientInfo['data']['client_output_muted'] == 1) {
																echo " <i class=\"fas fa-volume-off\"></i> ";
															} else {
																if($clientInfo['data']['client_output_hardware'] == 1) {
																	echo " <i class=\"fas fa-volume-up\"></i> ";
																}
															}
															#AUSGABE DES NAMEN INKLUSIVE LINK ZUR USERDETAIL SEITE
															echo "<a data-toggle=\"tooltip\" data-placement=\"right\" title=\"".$toggle."\" href=\"index.php?site=userdetail&cldbid=".$channelClients['client_database_id']."\">".$channelClients['client_nickname']."</a><br>";
														}
													}
												echo "</div>";
											} else {
												#KEINE CLIENTS VORHANDEN, KEIN TOGGLE
												if($channel['pid'] > 0) {
													echo "&nbsp;&nbsp;&nbsp;".$channel['channel_name']."";
												} else {
														echo $channel['channel_name'];
												}
												#MARKIERUNG DES DEFAULT CHANNELS
												if($channelInfo['data']['channel_flag_default'] == 1) {
													echo " <i class=\"fas fa-check-circle\"></i>";
												}
												#ABFRAGE NACH CHANNELPASSWORT
												if($channelInfo['data']['channel_flag_password'] == 1) {
													echo " <i class=\"fas fa-lock\"></i>";
												}
												#CHANNEL MODERIERT ODER NICHT
												if($channelInfo['data']['channel_needed_talk_power'] > 0) {
													echo " <i class=\"fas fa-microphone-slash\"></i>";
												}
												#ABFRAGE DER CHANNELICONS
												if($tsAdmin->getElement('success', $channelIcon = $tsAdmin->channelGetIconByChannelID($channel['cid']))) {
													echo " <img src=\"data:image/png;base64,".$channelIcon['data']."\" />";
												}
											}
										echo "</td>";
										echo "<td class=\"col-4 text-right\">";
											echo "<form method=\"post\" action=\"index.php?site=channel\" name=\"channel_edit\">";
													echo "<div class=\"form-group\">";
														echo "<input type=\"hidden\" name=\"hidden_cid\" value=".$channel['cid'].">";
														echo "<input type=\"hidden\" name=\"hidden_channel_name\" value=".$channel['channel_name'].">";
														echo "<input type=\"submit\" class=\"btn btn-sm btn-primary\" name=\"channel_edit\" value=\"Bearbeiten\">&nbsp;";
														echo "<input type=\"submit\" class=\"btn btn-sm btn-dark\" name=\"channel_pw_edit\" value=\"Passwort\">&nbsp;";
														echo "<a href=\"index.php?site=permissions&cid=".$channel['cid']."\" class=\"btn btn-sm btn-info\" role=\"button\"><i class=\"far fa-hand-point-up\"></i> Rechte</a>&nbsp;";
														echo "<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_channel_delete\" value=\"Löschen\">&nbsp;";
												echo "</div>";
											echo "</form>";
										echo "</td>";
									echo "</tr>";
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
