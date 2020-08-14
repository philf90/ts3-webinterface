<?php
if(isset($_SESSION['loggedin'])) {
	if(!isset($_SESSION['vserver_port'])) {
		echo "<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Es wurde kein vServer aus der Serverliste ausgewählt!</div>";
	} else {
		if($tsAdmin->getElement('success', $tsAdmin->selectServer($_SESSION['vserver_port']))) {
			
			//TOKEN LÖSCHEN
			if($_POST['button_token_delete']) {
				if($tsAdmin->getElement('success', $tsAdmin->tokenDelete($_POST['hidden_token']))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<strong>Token erfolgreich gelöscht.</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Token konnte nicht gelöscht werden.</strong>";
					echo "</div>";
				}
			}
		
			//SERVERTOKEN HINZUFÜGEN
			if($_POST['button_servertoken_add']) {
				if($tsAdmin->getElement('success', $output = $tsAdmin->tokenAdd(0,$_POST['servergroup_dropdown'],'',$_POST['token_description']))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Token erfolgreich erstellt.<br><strong>Token: ".$output['data']['token']."</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Token konnte nicht erstellt werden.</strong>";
					echo "</div>";
				}
			}
		
			//CHANNELTOKEN HINZUFÜGEN
			if($_POST['button_channeltoken_add']) {
				if($tsAdmin->getElement('success', $output = $tsAdmin->tokenAdd(1,$_POST['channelgroup_dropdown'],$_POST['channel_dropdown'],$_POST['token_description']))) {
					echo "<div class=\"alert alert-success alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "Token erfolgreich erstellt.<br><strong>Token: ".$output['data']['token']."</strong>";
					echo "</div>";
				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Token konnte nicht erstellt werden.</strong>";
					echo "</div>";
				}
			}
			
			$tsAdmin->selectServer($_SESSION['vserver_port']);
			$tokens = $tsAdmin->privilegekeyList();
			$servergroups = $tsAdmin->serverGroupList();
			$channelgroups = $tsAdmin->channelGroupList();
			$channels = $tsAdmin->channelList();
			
			?>	

			<div class="row content">
				<div class="col-sm-9">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#token_add_server"><i class="fas fa-plus"></i> Servertoken hinzufügen</button>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#token_add_channel"><i class="fas fa-plus"></i> Channeltoken hinzufügen</button>
				</div>
				<div class="col-sm-3 text-right">
					<button type="button" class="btn btn-primary" onclick="window.location.href=window.location.href;"><i class="fas fa-sync-alt"></i> Refresh</button>
				</div>
			</div>
			<br>

			<!-- Modal SERVERTOKEN -->
			<div id="token_add_server" class="modal fade" role="dialog">
				<div class="modal-dialog modal-lg">

					<!-- Modal content-->
					<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Servertoken hinzufügen</h3>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
					<?php
						echo "<form method=\"post\" action=\"index.php?site=token\" name=\"servertoken_add\">
								<div class=\"form-group\">
									<table class=\"table table-striped\">									
										<tbody>
											<tr>
												<div class=\"input-group\">
													<td class=\"ws-25\">Servergruppe</td>
													<td class=\"ws-75\">
														<select class=\"form-control\" name=\"servergroup_dropdown\">";
															foreach($servergroups['data'] as $servergroup) {
																if($servergroup['sgid'] > 5) {
																	echo "<option value=\"".$servergroup['sgid']."\">[".$servergroup['sgid']."] ".$servergroup['name']."</option>";
																}
															}
														echo "</select>
													</td>
												</div>
											</tr>
											<tr>
												<div class=\"input-group\">
													<td>Beschreibung</td>
													<td><input type=\"text\" class=\"form-control\" name=\"token_description\" placeholder=\"Beschreibung\"></td>
												</div>
											</tr>
											<tr>
												<div class=\"input-group\">
													<td>&nbsp;</td>
													<td><input type=\"submit\" class=\"btn btn-success\" name=\"button_servertoken_add\" value=\"Hinzufügen\"></td>
												</div>
											</tr>
										</tbody>
									</table>
								</div>
						</form>";
					?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
					</div>
					</div>

				</div>
			</div>

			<!-- Modal CHANNELTOKEN -->
			<div id="token_add_channel" class="modal fade" role="dialog">
				<div class="modal-dialog modal-lg">

					<!-- Modal content-->
					<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Channeltoken hinzufügen</h3>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
					<?php
						echo "<form method=\"post\" action=\"index.php?site=token\" name=\"channeltoken_add\">
								<div class=\"form-group\">
									<table class=\"table table-striped\">									
										<tbody>
											<tr>
												<div class=\"input-group\">
													<td class=\"ws-25\">Channelgruppe</td>
													<td class=\"ws-75\">
														<select class=\"form-control\" name=\"channelgroup_dropdown\">";
															foreach($channelgroups['data'] as $channelgroup) {
																if($channelgroup['cgid'] > 4) {
																	echo "<option value=\"".$channelgroup['cgid']."\">[".$channelgroup['cgid']."] ".$channelgroup['name']."</option>";
																}
															}
														echo "</select>
													</td>
												</div>
											</tr>
											<tr>
												<div class=\"input-group\">
													<td>Channel</td>
													<td>
														<select class=\"form-control\" name=\"channel_dropdown\">";
														foreach($channels['data'] as $channel) {
															echo "<option value=\"".$channel['cid']."\">[".$channel['cid']."] ".$channel['channel_name']."</option>";
														}
														echo "</select>
													</td>
												</div>
											</tr>
											<tr>
												<td>Beschreibung</td>
												<td><input type=\"text\" class=\"form-control\" name=\"token_description\" placeholder=\"Beschreibung\"></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td><input type=\"submit\" class=\"btn btn-success\" name=\"button_channeltoken_add\" value=\"Hinzufügen\"></td>
											</tr>
										</tbody>
									</table>
								</div>
						</form>";
					?>
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
						<th>Token</th>
						<th>Typ</th>
						<th>Gruppe</th>
						<th>Channel</th>
						<th>erstellt</th>
						<th>Beschreibung</th>
						<th class="text-right">Aktion</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($tokens['data'] as $token) {
							echo "<tr>";
								echo "<td>".$token['token']."</td>";
								if($token['token_type'] == 0) {
									echo "<td>Server</td>";
									foreach($servergroups['data'] as $servergroup) {
										if($servergroup['sgid'] == $token['token_id1']) {
											echo "<td>".$servergroup['name']."</td>";
										}
									}
								} else {
									echo "<td>Channel</td>";
									foreach($channelgroups['data'] as $channelgroup) {
										if($channelgroup['cgid'] == $token['token_id1']) {
											echo "<td>".$channelgroup['name']."</td>";
										}
									}
								}
								if($token['token_id2'] == 0) {
									echo "<td>&nbsp;</td>";
								} else {
									foreach($channels['data'] as $channel) {
										if($channel['cid'] == $token['token_id2']) {
											echo "<td>".$channel['channel_name']."</td>";
										}
									}
								}
								echo "<td>".date("d.m.Y - H:i",$token['token_created'])."</td>";
								echo "<td>".$token['token_description']."</td>";
								echo "<td class=\"text-right\"><form method=\"post\" action=\"index.php?site=token\" name=\"token_delete\">
								<div class=\"form-group\">
								<input type=\"hidden\" name=\"hidden_token\" value=".$token['token'].">
								<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_token_delete\" value=\"Löschen\">
								</div></form></td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
    
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