<?php
if(isset($_SESSION['loggedin'])) {
	if(!isset($_SESSION['vserver_port'])) {
		echo "<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Es wurde kein vServer aus der Serverliste ausgewählt!</div>";
	} else {
        if($tsAdmin->getElement('success', $tsAdmin->selectServer($_SESSION['vserver_port']))) {
            
            if($_POST['button_clear_banlist']) {
                if($tsAdmin->getElement('success', $tsAdmin->banDeleteAll())) {
                    echo "<div class=\"alert alert-success alert-dismissable\">";
                    echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                    echo "<strong>Alle Bans wurden erfolgreich gelöscht.</strong>";
                    echo "</div>";
                } else {
                    echo "<div class=\"alert alert-danger alert-dismissable\">";
                    echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                    echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Die Banliste konnte nicht geleert werden.</strong>";
                    echo "</div>";
                }                
            }
            
            if($_POST['button_ban_delete']) {
                if($tsAdmin->getElement('success', $tsAdmin->banDelete($_POST['hidden_banid']))) {
                    echo "<div class=\"alert alert-success alert-dismissable\">";
                    echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                    echo "<strong>Ban (".$_POST['hidden_banid'].") erfolgreich gelöscht.</strong>";
                    echo "</div>";
                } else {
                    echo "<div class=\"alert alert-danger alert-dismissable\">";
                    echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                    echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>Ban (ID: ".$_POST['hidden_banid'].") konnte nicht gelöscht werden.</strong>";
                    echo "</div>";
                }
            }

            if($_POST['button_ban_add']) {
                if($_POST['select_duration'] != "permanent" && $_POST['duration_count'] == 0) {
                    echo "<div class=\"alert alert-danger alert-dismissable\">";
                    echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                    echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>".$_POST['banInput']."</strong> konnte nicht vom Server gebannt werden. Es wurde keine korrekte Dauer eingegeben.";
                    echo "</div>";
                } else {
                    if($_POST['select_duration'] == "permanent") {
                        $time = 0;
                    }
                    if($_POST['select_duration'] == "Sekunden") {
                        $time = $_POST['duration_count'];
                    }
                    if($_POST['select_duration'] == "Minuten") {
                        $time = $_POST['duration_count']*60;
                    }
                    if($_POST['select_duration'] == "Stunden") {
                        $time = $_POST['duration_count']*3600;
                    }
                    if($_POST['select_duration'] == "Tage") {
                        $time = $_POST['duration_count']*86400;
                    }
                    if($_POST['banMethod'] == "UID"){
                        if($tsAdmin->getElement('success', $tsAdmin->banAddByUid($_POST['banInput'],$time,$_POST['banReason']))) {
                            echo "<div class=\"alert alert-success alert-dismissable\">";
                            echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                            echo "<strong>".$_POST['banInput']." erfolgreich gebannt.</strong>";
                            echo "</div>";
                        } else {
                            echo "<div class=\"alert alert-danger alert-dismissable\">";
                            echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                            echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>".$_POST['banInput']." konnte nicht gebannt werden.</strong>";
                            echo "</div>";
                        }
                    }
                    if($_POST['banMethod'] == "IP"){
                        if($tsAdmin->getElement('success', $tsAdmin->banAddByIp($_POST['banInput'],$time,$_POST['banReason']))) {
                            echo "<div class=\"alert alert-success alert-dismissable\">";
                            echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                            echo "<strong>".$_POST['banInput']." erfolgreich gebannt.</strong>";
                            echo "</div>";
                        } else {
                            echo "<div class=\"alert alert-danger alert-dismissable\">";
                            echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                            echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>".$_POST['banInput']." konnte nicht gebannt werden.</strong>";
                            echo "</div>";
                        }
                    }
                    if($_POST['banMethod'] == "Name"){
                        if($tsAdmin->getElement('success', $tsAdmin->banAddByName($_POST['banInput'],$time,$_POST['banReason']))) {
                            echo "<div class=\"alert alert-success alert-dismissable\">";
                            echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                            echo "<strong>".$_POST['banInput']." erfolgreich gebannt.</strong>";
                            echo "</div>";
                        } else {
                            echo "<div class=\"alert alert-danger alert-dismissable\">";
                            echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                            echo "<i class=\"fas fa-exclamation-triangle\"></i> <strong>".$_POST['banInput']." konnte nicht gebannt werden.</strong>";
                            echo "</div>";
                        }
                    }
                }
            }
            
            $banList = $tsAdmin->banList();
            ?>

            <div class="row content">
				<div class="col-sm-9">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ban_add"><i class="fas fa-plus"></i> Ban hinzufügen</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#clear_banlist"><i class="fas fa-trash-alt"></i> Banliste leeren</button>
				</div>
				<div class="col-sm-3 text-right">
					<button type="button" class="btn btn-primary" onclick="window.location.href=window.location.href;"><i class="fas fa-sync-alt"></i> Refresh</button>
				</div>
			</div>
			<br>

			<!-- Modal BAN HINZUFÜGEN -->
			<div id="ban_add" class="modal fade" role="dialog">
				<div class="modal-dialog modal-lg">

					<!-- Modal content-->
					<div class="modal-content">
					<div class="modal-header">
                        <h3 class="modal-title">Ban hinzufügen</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
                        <form method="post" action="index.php?site=banlist" name="form_ban_user">
							<div class="form-group">
								<table class="table table-striped">
									<tbody>
										<tr>
											<div class="input-group">
												<td class="w-25">Methode</td>
												<td class="w-75">
                                                    <select class="form-control" name="banMethod" id="banMethod">
                                                        <option selected>UID</option>
                                                        <option>IP</option>
                                                        <option>Name</option>
                                                    </select>
                                                </td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
                                                <td>UID/IP/Name</td>
                                                <td><input id="banInput" type="text" class="form-control" name="banInput"></td>
											</div>
										</tr>
                                        <tr>
                                            <div class="input-group">
                                                <td>Dauer</td>
                                                <td>
                                                    <select class="form-control" name="select_duration">
                                                        <option selected>permanent</option>
                                                        <option>Sekunden</option>
                                                        <option>Minuten</option>
                                                        <option>Stunden</option>
                                                        <option>Tage</option>
                                                    </select>
                                                </td>
                                            </div>
                                        </tr>
                                        <tr>
                                            <div class="input-group">
                                                <td>Anzahl</td>
                                                <td><input type="number" class="form-control" name="duration_count" placeholder="0 = permanent"></td>
                                            </div>
                                        </tr>
										<tr>
											<div class="input-group">
                                                <td>Grund</td>
                                                <td><input id="banReason" type="text" class="form-control" name="banReason"></td>
											</div>
										</tr>
										<tr>
											<div class="input-group">
												<td>&nbsp;</td>
												<td><input type="submit" class="btn btn-success" name="button_ban_add" value="Bannen"></td>
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

            <!-- Modal CLEAR BANLISTE -->
            <div id="clear_banlist" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Banliste leeren</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong><i class="fas fa-exclamation-triangle"></i> Mit Ausf&uuml;hrung des Befehls werden alle vorhandenen Bans gel&ouml;scht.</strong>
                        </div>
                        <form method="post" action="index.php?site=banlist" name="form_shutdown" id="form_clear_banlist">
                            <div class="form-group">
                                <input type="submit" class="btn btn-lg btn-block btn-danger" value="Banliste leeren" name="button_clear_banlist">
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
                        <th>ID</th>
                        <th>Name/IP/UID</th>
                        <th>erstellt</th>
                        <th>Dauer</th>
                        <th>von</th>
                        <th>Grund</th>
                        <th class="text-right">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($banList['data'] as $ban) {
                        $invokerClientGetNameFromDbid = $tsAdmin->clientGetNameFromDbid($ban['invokercldbid']);
                        echo "<tr>";
                            echo "<td class=\"w-5\">".$ban['banid']."</td>";
                            echo "<td class=\"w-55\">".$ban['name']."".$ban['ip']."".$ban['uid']."</td>";
                            echo "<td class=\"w-5\">".date("d.m.Y - H:i",$ban['created'])."</td>";
                            echo "<td class=\"w-5\">";
                                if($ban['duration'] == 0){
                                    echo "permanent";
                                } else {
                                    $duration = $tsAdmin->convertSecondsToStrTime($ban['duration']);
                                    echo $duration;
                                }
                            echo "</td>";
                            echo "<td class=\"w-15\">".$invokerClientGetNameFromDbid['data']['name']."</td>";
                            echo "<td class=\"w-10\">".$ban['reason']."</td>";
                            echo "<td class=\"w-5 text-right\">";
                                echo "<form method=\"post\" action=\"index.php?site=banlist\" name=\"form_banlist_action\">";
                                    echo "<div class=\"form-group\">";
                                        echo "<input type=\"hidden\" name=\"hidden_banid\" value=".$ban['banid'].">";
                                        echo "<input type=\"submit\" class=\"btn btn-sm btn-danger\" name=\"button_ban_delete\" value=\"Löschen\">";
                                    echo "</div>";
                                echo "</form>";
                            echo "</td>";
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