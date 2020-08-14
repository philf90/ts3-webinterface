<?php
if(isset($_SESSION['loggedin'])) {
    $tsAdmin->selectServer($_SESSION['vserver_port']);
    if(isset($_GET['cldbid']) && $_GET['cldbid'] != "") {

        $cldbid = $_GET['cldbid'];
            
        if($_POST['button_send_poke']) {
            if($tsAdmin->getElement('success', $tsAdmin->clientPoke($_POST['hidden_clid'],$_POST['poke']))) {
                echo "<div class=\"alert alert-success alert-dismissable\">";
                echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                echo "Nachricht erfolgreich an <strong>".$_POST['hidden_client_nickname']."</strong> gesendet.";
                echo "</div>";
            } else {
                echo "<div class=\"alert alert-danger alert-dismissable\">";
                echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                echo "<i class=\"fas fa-exclamation-triangle\"></i> Nachricht konnte nicht an <strong>".$_POST['hidden_client_nickname']."</strong> gesendet werden.";
                echo "</div>";
            }
        }
        
        if($_POST['button_kick_client'] || $_POST['button_ban_client']) {

            if($_POST['button_kick_client']) {
                $kickMode = "server";
                if($tsAdmin->getElement('success', $tsAdmin->clientKick($_POST['hidden_clid'],$kickMode,$_POST['kickmsg']))) {
                    echo "<div class=\"alert alert-success alert-dismissable\">";
                    echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                    echo "User <strong>".$_POST['hidden_client_nickname']."</strong> erfolgreich vom Server gekickt.";
                    echo "</div>";
                } else {
                    echo "<div class=\"alert alert-danger alert-dismissable\">";
                    echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                    echo "<i class=\"fas fa-exclamation-triangle\"></i> User <strong>".$_POST['hidden_client_nickname']."</strong> konnte nicht vom Server gekickt werden.";
                    echo "</div>";
                }
                header("refresh:3;url=index.php?site=channel");
            }

            if($_POST['button_ban_client']) {
                if($_POST['select_duration'] != "permanent" && $_POST['duration_count'] == 0) {
                    echo "<div class=\"alert alert-danger alert-dismissable\">";
                    echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                    echo "<i class=\"fas fa-exclamation-triangle\"></i> User <strong>".$_POST['hidden_client_nickname']."</strong> konnte nicht vom Server gebannt werden. Es wurde keine korrekte Dauer eingegeben.";
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
                    if($tsAdmin->getElement('success', $tsAdmin->banAddByUid($_POST['hidden_cluid'],$time,$_POST['banreason']))) {
                        if($_POST['select_duration'] == "permanent") {
                            echo "<div class=\"alert alert-success alert-dismissable\">";
                            echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                            echo "User <strong>".$_POST['hidden_client_nickname']."</strong> erfolgreich ".$_POST['select_duration']." vom Server gebannt.";
                            echo "</div>";
                        } else {
                            echo "<div class=\"alert alert-success alert-dismissable\">";
                            echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                            echo "User <strong>".$_POST['hidden_client_nickname']."</strong> erfolgreich f&uuml;r ".$_POST['duration_count']." ".$_POST['select_duration']." vom Server gebannt.";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class=\"alert alert-danger alert-dismissable\">";
                        echo "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>";
                        echo "<i class=\"fas fa-exclamation-triangle\"></i> User <strong>".$_POST['hidden_client_nickname']."</strong> konnte nicht vom Server gebannt werden.";
                        echo "</div>";
                    }
                }
                header("refresh:3;url=index.php?site=channel");
            }

        } else {

            if($tsAdmin->getElement('success', $clientDbInfo = $tsAdmin->clientDbInfo($_GET['cldbid']))) {
                $clientGetIds = $tsAdmin->clientGetIds($clientDbInfo['data']['client_unique_identifier']);
                $clientInfo = $tsAdmin->clientInfo($clientGetIds['data']['0']['clid']);
                $servergroups = $tsAdmin->serverGroupList();
                $channelgroups = $tsAdmin->channelGroupList();
                $i = 0;
                #print_r($clientInfo);

                    ?>

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#kick_client"><i class="fas fa-minus-circle"></i> <?php echo $clientInfo['data']['client_nickname']; ?> kicken</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ban_client"><i class="fas fa-ban"></i> <?php echo $clientInfo['data']['client_nickname']; ?> bannen</button>
                        <!--<button type="submit" class="btn btn-primary"><i class="far fa-hand-point-up"></i> Rechte</button>-->
                        <!--<a href="index.php?site=permissions&cldbid=<?php echo $cldbid; ?>" class="btn btn-primary" role="button"><i class="far fa-hand-point-up"></i> Rechte</a>-->
                    <br><br>

                    <!-- USER KICKEN -->
                    <div id="kick_client" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">

                            <!-- Modal content-->
                            <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title"><?php echo $clientInfo['data']['client_nickname']; ?> kicken</h3>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                            <?php
                                echo "<form method=\"post\" action=\"index.php?site=userdetail&cldbid=".$cldbid."\" name=\"form_kick_client\">
                                    <div class=\"form-group\">
                                        <table class=\"table table-striped\">									
                                            <tbody>
                                                <tr>
                                                    <div class=\"input-group\">
                                                        <td class=\"w-25\">Grund</td>
                                                        <td class=\"w-75\"><input type=\"text\" class=\"form-control\" name=\"kickmsg\" maxlength=\"40\" placeholder=\"Grund\"></td>
                                                    </div>
                                                </tr>
                                                <tr>
                                                    <div class=\"input-group\">
                                                        <td>&nbsp;</td>
                                                        <td><input type=\"hidden\" name=\"hidden_client_nickname\" value=\"".$clientInfo['data']['client_nickname']."\"><input type=\"hidden\" name=\"hidden_clid\" value=\"".$clientGetIds['data']['0']['clid']."\"><input type=\"submit\" class=\"btn btn-success\" name=\"button_kick_client\" value=\"".$clientInfo['data']['client_nickname']." kicken\"></td>
                                                    </div>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>";
                            ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                            </div>
                            </div>

                        </div>
                    </div>

                    <!-- USER bannen -->
                    <div id="ban_client" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">

                            <!-- Modal content-->
                            <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title"><?php echo $clientInfo['data']['client_nickname']; ?> bannen</h3>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                            <?php
                                echo "<form method=\"post\" action=\"index.php?site=userdetail&cldbid=".$cldbid."\" name=\"form_ban_client\">
                                    <div class=\"form-group\">
                                        <table class=\"table table-striped\">
                                            <tbody>
                                                <tr>
                                                    <div class=\"input-group\">
                                                        <td class=\"w-25\">Grund</td>
                                                        <td class=\"w-75\"><input type=\"text\" class=\"form-control\" name=\"banreason\" maxlength=\"40\" placeholder=\"Grund\"></td>
                                                    </div>
                                                </tr>
                                                <tr>
                                                    <div class=\"input-group\">
                                                        <td>Dauer</td>
                                                        <td>
                                                            <select class=\"form-control\" name=\"select_duration\">
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
                                                    <div class=\"input-group\">
                                                        <td>Anzahl</td>
                                                        <td><input type=\"number\" class=\"form-control\" name=\"duration_count\" placeholder=\"0 = permanent\"></td>
                                                    </div>
                                                </tr>
                                                <tr>
                                                    <div class=\"input-group\">
                                                        <td>&nbsp;</td>
                                                        <td><input type=\"hidden\" name=\"hidden_client_nickname\" value=\"".$clientInfo['data']['client_nickname']."\"><input type=\"hidden\" name=\"hidden_cluid\" value=\"".$clientDbInfo['data']['client_unique_identifier']."\"><input type=\"submit\" class=\"btn btn-success\" name=\"button_ban_client\" value=\"".$clientInfo['data']['client_nickname']." bannen\"></td>
                                                    </div>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>";
                            ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                            </div>
                            </div>

                        </div>
                    </div>

                    <h2>
                        <?php
                            echo $clientInfo['data']['client_nickname'];
                            if($clientInfo['data']['client_input_muted'] == 1) {
                                echo " <i class=\"fas fa-microphone-slash\"></i>";
                            } else {
                                if($clientInfo['data']['client_input_hardware'] == 1) {
                                    echo " <i class=\"fas fa-microphone\"></i>";
                                }
                            }
                            if($clientInfo['data']['client_output_muted'] == 1) {
                                echo " <i class=\"fas fa-volume-off\"></i>";
                            } else {
                                if($clientInfo['data']['client_output_hardware'] == 1) {
                                    echo " <i class=\"fas fa-volume-up\"></i>";
                                }
                            }
                        ?>               
                    </h2>

                    <h3>Anstupsen</h3>
                    <form method="post" action="index.php?site=userdetail&cldbid=<?php echo $cldbid; ?>" name="form_poke">
                        <div class="form-group">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <div class="input-group">
                                            <td class="w-25">Nachricht</td>
                                            <td class="w-75"><textarea class="form-control" rows="3" name="poke" placeholder="Nachricht"></textarea></td>
                                        </div>
                                    </tr>
                                    <tr>
                                        <div class="input-group">
                                            <td>&nbsp;</td>
                                            <td>
                                                <input type="hidden" name="hidden_client_nickname" value="<?php echo $clientInfo['data']['client_nickname']; ?>">
                                                <input type="hidden" name="hidden_clid" value="<?php echo $clientGetIds['data']['0']['clid']; ?>">
                                                <input type="submit" class="btn btn-sm btn-success" name="button_send_poke" value="Senden">
                                            </td>
                                        </div>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Info</th>
                                <th>Wert</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="w-25">Database ID</td>
                                <td class="w-75"><?php echo $clientInfo['data']['client_database_id']; ?></td>
                            </tr>
                            <tr>
                                <td>UID</td>
                                <td><?php echo $clientInfo['data']['client_unique_identifier']; ?></td>
                            </tr>
                            <tr>
                                <td>Erstellt</td>
                                <td><?php echo date("d.m.Y - H:i", $clientInfo['data']['client_created']); ?></td>
                            </tr>
                            <tr>
                                <td>Zuletzt online</td>
                                <td><?php echo date("d.m.Y - H:i", $clientInfo['data']['client_lastconnected']); ?></td>
                            </tr>
                            <tr>
                                <td>Summe Verbindungen</td>
                                <td><?php echo $clientInfo['data']['client_totalconnections']; ?></td>
                            </tr>
                            <tr>
                                <td>Version</td>
                                <td><?php echo $clientInfo['data']['client_version']; ?></td>
                            </tr>
                            <tr>
                                <td>Plattform</td>
                                <td><?php echo $clientInfo['data']['client_platform']; ?></td>
                            </tr>
                            <tr>
                                <td>Land</td>
                                <?php
                                    if($clientInfo['data']['client_country'] != "") {
                                        echo "<td><img src=\"images/flags/".$clientInfo['data']['client_country'].".png\" alt=\"".$clientInfo['data']['client_country']."\" title=\"".$clientInfo['data']['client_country']."\" /></td>";
                                    } else {
                                        echo "<td><img src=\"images/flags/rainbow.png\" alt=\"\" /></td>";
                                    }
                                ?>
                            </tr>
                            <tr>
                                <td>Online seit</td>
                                <td><?php echo $clientInfo['data']['connection_connected_time']; ?></td>
                            </tr>
                            <tr>
                                <td>Aktuelle IP</td>
                                <td><?php echo $clientInfo['data']['connection_client_ip']; ?></td>
                            </tr>
                            <tr>
                                <td>Servergruppen</td>
                                <td>
                                    <?php
                                    $sgroups = explode(",",$clientInfo['data']['client_servergroups']);
                                    foreach($servergroups['data'] as $servergroup) {
                                        if($servergroup['sgid'] == $sgroups[$i]) {
                                            if($i > 0) {
                                                #if($tsAdmin->getElement('success', $servergroupicon = $tsAdmin->serverGroupGetIconBySGID($sgroups[$i]))) {
                                                #    echo ", <img src=\"data:image/png;base64,".$servergroupicon['data']."\" /> <span class=\"label label-primary\">".$servergroup['name']."</span>";
                                                #}
                                                echo " <span class=\"badge badge-dark\">".$servergroup['name']."</span>";
                                                $i++; 
                                            } else {
                                                #if($tsAdmin->getElement('success', $servergroupicon = $tsAdmin->serverGroupGetIconBySGID($sgroups[$i]))) {
                                                #    echo "<img src=\"data:image/png;base64,".$servergroupicon['data']."\" /> ";
                                                #}
                                                echo "<span class=\"badge badge-dark\">".$servergroup['name']."</span>";
                                                $i++;                                        
                                            }
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Channelgruppen</td>
                                <td>
                                    <?php
                                    foreach($channelgroups['data'] as $channelgroup) {
                                        if($channelgroup['cgid'] == $clientInfo['data']['client_channel_group_id']) {
                                            #if($tsAdmin->getElement('success', $channelgroupicon = $tsAdmin->channelGroupGetIconByCGID($clientInfo['data']['client_channel_group_id']))) {
                                            #    echo "<img src=\"data:image/png;base64,".$channelgroupicon['data']."\" /> ";
                                            #}
                                            echo "<span class=\"badge badge-info\">".$channelgroup['name']."</span>";
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Avatar</td>
                                <td>
                                    <?php
                                    //$avatar = $tsAdmin->clientAvatar($clientInfo['data']['client_unique_identifier']);
                                    if($tsAdmin->getElement('success', $avatar = $tsAdmin->clientAvatar($clientInfo['data']['client_unique_identifier']))) {
                                        echo "<img src=\"data:image/png;base64,".$avatar["data"]."\" />";
                                    } else {
                                        echo "<i>Kein Avatar vorhanden</i>";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php
                    
            } else {
                if($_GET['cldbid'] == 1) {
                    ?>
                    <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-triangle"></i> Der "serveradmin" kann im Detail nicht angeschaut werden.</strong>
                    </div>       
                    <?php
                    header("refresh:3;url=index.php?site=channel");
                } else {
                    ?>
                    <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-triangle"></i> Der User ist derzeit nicht online.</strong>
                    </div>       
                    <?php
                    header("refresh:3;url=index.php?site=channel");
                }
            }
        }
    } else {
        ?>
        <div class="alert alert-danger">
        <strong><i class="fas fa-exclamation-triangle"></i> Es wurde kein User ausgew&auml;hlt.</strong>
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
