<?php
if(isset($_SESSION['loggedin'])) {
	
    if($_POST['button_shutdown'] || $_POST['button_newpassword']) {
		if($_POST['button_shutdown']) {
			if($tsAdmin->getElement('success', $tsAdmin->serverProcessStop())) {
				echo "<div class=\"alert alert-success alert-dismissable\">";
				echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
				echo "<strong>Die Instanz wurde erfolgreich heruntergefahren und der Prozess gestoppt.</strong>";
				echo "</div>";
				header("refresh:3;url=index.php?site=logout");
			} else {
				echo "<div class=\"alert alert-danger alert-dismissable\">";
				echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
				echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Die Instanz konnte nicht heruntergefahren werden.</strong>";
				echo "</div>";
				header("refresh:3;url=index.php?site=instanz");
			}
		}
		if($_POST['button_newpassword']) {
			if(filter_var($_POST['email-newpassword'], FILTER_VALIDATE_EMAIL)) {
				if($tsAdmin->getElement('success', $output = $tsAdmin->clientSetServerQueryLogin($_SESSION['username']))) {
					$to = $_POST['email-newpassword'];
					$subject = "Teamspeak 3 Server: Neues Admin Passwort via netzhost24.de Webinterface";
					$headers = "From: noreply@netzhost24.de\r\n";
					$headers .= "Replay-To: ts3wi@pf90.de\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                    $message = "<html><body>";
                    $message .= "<div style=\"font-family: Arial, Helvetica, sans-serif;\">";
                    $message .= "<img src=\"https://ts.pf90.de/images/header/motd_2015_clear_back.png\" alt=\"Header\"><br>";
                    $message .= "Hallo ".$_SESSION['username'].",<br><br>";
                    $message .= "dein neues Passwort lautet: ".$output['data']['client_login_password']."<br><br>";
                    $message .= "Vielen Dank für die Nutzung des <a href=\"https://ts.pf90.de\" target=\"_blank\">Teamspeak 3 Webinterfaces</a> von netzhost24.de.<br><br>";
                    $message .= "Mit freundlichen Grüßen<br>";
                    $message .= "<a href=\"https://www.netzhost24.de\" target=\"_blank\">netzhost24.de</a><br><br>";
                    $message .= "<small>Diese Email wurde automatisch generiert. Es kann darauf nicht geantwortet werden.</small>";
                    $message .= "</div>";
                    $message .= "</body></html>";
					if(mail($to, $subject, $message, $headers)) {
						echo "<div class=\"alert alert-success alert-dismissable\">";
						echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
						echo "<i class=\"fas fa-check\"></i> Email erfolgreich gesendet.<br>Das neue Passwort für <strong>".$_SESSION['username']."</strong> lautet: <strong>".$output['data']['client_login_password']."</strong><br>Achtung: Automatischer Logout in 10 Sekunden.";
						echo "</div>";
					} else {
						echo "<div class=\"alert alert-success alert-dismissable\">";
						echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
						echo "<i class=\"fas fa-check\"></i> <span class=\"text-danger\">Email konnte nicht gesendet werden.</span><br>Das neue Passwort für <strong>".$_SESSION['username']."</strong> lautet: <strong>".$output['data']['client_login_password']."</strong><br>Achtung: Automatischer Logout in 10 Sekunden.";
						echo "</div>";
					}
					header("refresh:10;url=index.php?site=logout");

				} else {
					echo "<div class=\"alert alert-danger alert-dismissable\">";
					echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
					echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Es konnte kein neues Passwort generiert werden.</strong>";
					echo "</div>";
					header("refresh:3;url=index.php?site=instanz");
				}
			} else {
				echo "<div class=\"alert alert-danger alert-dismissable\">";
				echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
				echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Die eingegebene Email Adresse ist nicht korrekt. Es konnte kein neues Passwort generiert und verschickt werden.</strong>";
				echo "</div>";
				header("refresh:3;url=index.php?site=instanz");
			}
		}
    } else {

		$hostinfo = $tsAdmin->hostInfo();
		$instanceinfo = $tsAdmin->instanceInfo();
		$serverGroupList = $tsAdmin->serverGroupList();
		?>

		<div class="row content">
			<div class="col-sm-9">
				<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#shutdown"><i class="fas fa-stop-circle"></i> Instanz herunterfahren</button>
				<button type="button" class="btn btn-info" data-toggle="modal" data-target="#newpassword"><i class="fas fa-sync"></i> Neues Admin Passwort</button>
			</div>
			<div class="col-sm-3 text-right">
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#view_logs"><i class="far fa-newspaper"></i> Logs</button>
				<button type="button" class="btn btn-primary" onclick="window.location.href=window.location.href;"><i class="fas fa-sync-alt"></i> Refresh</button>
			</div>
		</div>
		<br>

		<!-- Modal SHUTDOWN -->
		<div id="shutdown" class="modal fade">
			<div class="modal-dialog modal-lg">

				<!-- Modal content-->
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Instanz herunterfahren</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">
						<strong><i class="fas fa-exclamation-triangle"></i> Mit Ausf&uuml;hrung des Befehls wird die gesamte Instanz heruntergefahren und der Prozess gestoppt. Ein erneutes Starten der Instanz ist nur manuell m&ouml;glich.</strong>
					</div>
					<form method="post" action="index.php?site=instanz" name="form_shutdown" id="form_shutdown">
						<div class="form-group">
							<input type="submit" class="btn btn-lg btn-block btn-danger" value="Instanz herunterfahren" name="button_shutdown">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
				</div>
				</div>

			</div>
		</div>

		<!-- Modal NEWPASSWORD -->
		<div id="newpassword" class="modal fade">
			<div class="modal-dialog modal-lg">

				<!-- Modal content-->
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Neues Admin Passwort</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="alert alert-info">
						<i class="fas fa-exclamation-triangle"></i> Es wird automatisiert ein neues Passwort für den User <strong><?php echo $_SESSION['username']; ?></strong> generiert und per Email verschickt.
					</div>
					<form method="post" action="index.php?site=instanz" name="form_newpassword" id="form_newpassword">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="newpassword-email">Email</span>
							</div>
							<input type="text" name="email-newpassword" class="form-control" aria-label="Default" aria-describedby="newpassword-email">
						</div>
							<input type="submit" class="btn btn-lg btn-block btn-danger" value="Neues Passwort generieren" name="button_newpassword">
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
					<table class="table table-striped table-hover table-responsive">
						<tbody>
							<?php
								$logView = $tsAdmin->logView(50,1,1);
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
						<td>Instanz Uptime</td>
						<td><?php echo $tsAdmin->convertSecondsToStrTime($hostinfo['data']['instance_uptime']); ?></td>
					</tr>
					<tr>
						<td>Timestamp</td>
						<td><?php echo date("d.m.Y - H:i",$hostinfo['data']['host_timestamp_utc']); ?></td>
					</tr>
					<tr>
						<td>vServer</td>
						<td><?php echo $hostinfo['data']['virtualservers_running_total']; ?></td>
					</tr>
					<tr>
						<td>Slots (Summe)</td>
						<td><?php echo $hostinfo['data']['virtualservers_total_clients_online']; ?> / <?php echo $hostinfo['data']['virtualservers_total_maxclients']; ?></td>
					</tr>
					<tr>
						<td>Channels (Summe)</td>
						<td><?php echo $hostinfo['data']['virtualservers_total_channels_online']; ?></td>
					</tr>
					<tr>
						<td>Pakete gesendet</td>
						<td><?php echo number_format($hostinfo['data']['connection_packets_sent_total'],0,",","."); ?></td>
					</tr>
					<tr>
						<td>Daten gesendet</td>
						<td><?php echo number_format(($hostinfo['data']['connection_bytes_sent_total']/pow(1024,3)),2,",","."); ?> Gbyte</td>
					</tr>
					<tr>
						<td>Pakete empfangen</td>
						<td><?php echo number_format($hostinfo['data']['connection_packets_received_total'],0,",","."); ?></td>
					</tr>
					<tr>
						<td>Daten empfangen</td>
						<td><?php echo number_format(($hostinfo['data']['connection_bytes_received_total']/pow(1024,3)),2,",","."); ?> Gbyte</td>
					</tr>
					<tr>
						<td>Datenbank Version</td>
						<td><?php echo $instanceinfo['data']['serverinstance_database_version']; ?></td>
					</tr>
					<tr>
						<td>Permissions Version</td>
						<td><?php echo $instanceinfo['data']['serverinstance_permissions_version']; ?></td>
					</tr>
					<tr>
						<td>Filetransfer Port</td>
						<td><?php echo $instanceinfo['data']['serverinstance_filetransfer_port']; ?></td>
					</tr>
					<tr>
						<td>Serverquery Flood Commands</td>
						<td><?php echo $instanceinfo['data']['serverinstance_serverquery_flood_commands']; ?></td>
					</tr>
					<tr>
						<td>Serverquery Flood Time</td>
						<td><?php echo $instanceinfo['data']['serverinstance_serverquery_flood_time']; ?> Sekunden</td>
					</tr>
					<tr>
						<td>Serverquery Flood Ban Time</td>
						<td><?php echo $instanceinfo['data']['serverinstance_serverquery_ban_time']; ?> Sekunden</td>
					</tr>
				</tbody>
			</table>
		</div>

<?php
	}
} else {
	?>
	<div class="alert alert-danger">
	  <strong><i class="fas fa-exclamation-triangle"></i> Zugriff verweigert!</strong> Sie sind nicht eingeloggt.
	</div>
	<?php
}
?>