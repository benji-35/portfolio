<?php
    global $hlp, $rtr, $db, $trans, $dispatch;

    $connectToServ = "-1";
    $srvIntels = array();
    $currTeam = "-1";
    $serverTeams = array();

    if (isset($_SESSION['dispConnect'])) {
        $connectToServ = $_SESSION['dispConnect'];
        $srvIntels = $dispatch->getServerIntels($connectToServ);
    }
    if (isset($_SESSION['cTeam'])) {
        $currTeam = $_SESSION['cTeam'];
        $serverTeams = $dispatch->getDispatchServerTeams();
        $serverTeams = array($serverTeams[$currTeam]);
    } else {
        $serverTeams = $dispatch->getDispatchServerTeams();
    }

    if (isset($_POST['disconnectDisptach'])) {
        $dispatch->disconnectToDispatchServer();
        $hlp->disconnectUser();
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }
    if (isset($_POST['disconnectServer'])) {
        unset($_SESSION['errorTeam']);
        unset($_SESSION['errorValidPwd']);
        unset($_SESSION['wpwdServer']);
        unset($_SESSION['tmpServer']);
        $dispatch->disconnectToDispatchServer();
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }
    if (isset($_POST['createServer'])) {
        $teams = array();
        for ($i = 0; $i < $_POST['maxId']; $i++) {
            if (isset($_POST['nameAddServerDispatch-Team' . $i])) {
                $teamArray = array(
                    "tname" => $_POST['nameAddServerDispatch-Team' . $i],
                    "cCall" => false,
                    "cDisp" => false,
                    "iResp" => false,
                    "ndClS" => false,
                );
                if (isset($_POST['cCall-Team' . $i])) {
                    $teamArray['cCall'] = true;
                }
                if (isset($_POST['cDisp-Team' . $i])) {
                    $teamArray['cDisp'] = true;
                }
                if (isset($_POST['iResp-Team' . $i])) {
                    $teamArray['iResp'] = true;
                }
                if (isset($_POST['ndClS-Team' . $i])) {
                    $teamArray['ndClS'] = true;
                }
                array_push($teams, $teamArray);
            }
        }
        $mp = "";
        if (isset($_POST['passwordServerAdd'])) {
            $mp = $_POST['passwordServerAdd'];
        }
        $dispatch->createDispatchServer($_POST['nameServerAdd'], $teams, "", $mp);
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }
    if (isset($_POST['changeTeam'])) {
        if (isset($_SESSION['cTeam'])) {
            unset($_SESSION['cTeam']);
        }
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }
    if (isset($_POST['removeServer'])) {
        $dispatch->deleteCurrServer();
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }
    if (isset($_POST['joinTheServer'])) {
        $dispatch->joinServer($_POST['joinTheServerLink']);
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }
    if (isset($_POST['createTeamChooseTeam'])) {
        if ($_POST['chooseTeamNewTeam']) {
            $teamArray = array(
                "tname" => $_POST['chooseTeamNewTeam'],
                "cCall" => false,
                "cDisp" => false,
                "iResp" => false,
                "ndClS" => false,
            );
            if (isset($_POST['cCallChooseNewTeam'])) {
                $teamArray['cCall'] = true;
            }
            if (isset($_POST['cDispChooseNewTeam'])) {
                $teamArray['cDisp'] = true;
            }
            if (isset($_POST['iRespChooseNewTeam'])) {
                $teamArray['iResp'] = true;
            }
            if (isset($_POST['ndClSChooseNewTeam'])) {
                $teamArray['ndClS'] = true;
            }
            $dispatch->addTeamInCurrServer(array($teamArray));
        }
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }

    $listingServer = $dispatch->getDispatchServersListSubscribe();

    if (isset($_POST['validPwd']) && isset($_POST['passwordServer'])) {
        unset($_SESSION['errorValidPwd']);
        if (isset($_SESSION['wpwdServer']) && isset($_SESSION['tmpServer'])) {
            foreach ($listingServer as $server) {
                if ($server['sid'] == $_SESSION['tmpServer']) {
                    if (password_verify($_POST['passwordServer'], $server['password'])) {
                        $dispatch->connectToDisptahcServer($server['sid']);
                    } else {
                        $_SESSION['errorValidPwd'] = "Mot passe faux";
                        header("Location: " . $rtr->getMainUrl() . "/dispatch");
                        return;
                    }
                }
            }
        }
        unset($_SESSION['wpwdServer']);
        unset($_SESSION['tmpServer']);
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }
    if (isset($_POST['cancelValidePwd'])) {
        unset($_SESSION['errorValidPwd']);
        unset($_SESSION['wpwdServer']);
        unset($_SESSION['tmpServer']);
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }
    if (isset($_POST['okShareLink'])) {
        unset($_SESSION['displayInviteLink']);
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }
    if (isset($_POST['shareServer'])) {
        $dispatch->generateInviteLink();
        header("Location: " . $rtr->getMainUrl() . "/dispatch");
    }

    foreach ($listingServer as $server) {
        if (isset($_POST['accessServerDispatch-' . $server['sid']])) {
            if ($server['password'] != "") {
                $_SESSION['wpwdServer'] = true;
                $_SESSION['tmpServer'] = $server['sid'];
            } else {
                $dispatch->connectToDisptahcServer($server['sid']);
            }
            header("Location: " . $rtr->getMainUrl() . "/dispatch");
        }
    }

    foreach ($serverTeams as $i => $teamServer) {
        if (isset($_POST['cT-' . $i])) {
            unset($_SESSION['errorTeam']);
            if ($teamServer['ndClS'] == true && (!isset($_POST['callSign-' . $i]) || $_POST['callSign-' . $i] == "")) {
                $_SESSION['errorTeam'] = $i . "=Please unter a call sign";
            } else {
                $_SESSION['cTeam'] = $i;
                if ($teamServer['ndClS'] == true) {
                    $_SESSION['cS'] = $_POST['callSign-' . $i];
                } else {
                    $_SESSION['cS'] = "";
                }
            }
            header("Location: " . $rtr->getMainUrl() . "/dispatch");
        }
        if (isset($_POST['dltT-' . $i])) {
            $dispatch->deleteTeamInCurrServer($teamServer['name']);
            header("Location: " . $rtr->getMainUrl() . "/dispatch");
        }
    }
?>
<!DOCTYPE html>
<html lang="<?=$_SESSION['lang']?>">
    <head>
        <link rel="icon" href="public/ressources/emergencyLogo.png">
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <title>
            <?php
                if ($connectToServ != "-1") {
                    echo $srvIntels['s_name'];
                } else {
            ?>
                <?=$_SESSION['titlePage']?>
            <?php
                }
            ?>
        </title>
        <meta charset="utf-8">
        <?php
            if (isset($_SESSION['cssPage'])) {
        ?>
            <link href="<?=$_SESSION['cssPage']?>" rel="stylesheet">
        <?php
            }
            if (isset($_SESSION['jsPage'])) {
        ?>
            <script src="<?=$_SESSION['jsPage']?>"></script>
        <?php
            }
        ?>
    </head>
    <body>
        <div class="content">
            <div class="header">
                <button class="btnNavMenu" onclick="showHideNavBar()"><i class='bx bx-menu bx-md'></i></button>
                <img src="public/ressources/emergencyLogo.png"v class="headerImg">
            </div>
            <div class="mainContent">
                <div id="navMar">
                    <?php
                        if ($connectToServ == "-1") {
                    ?>
                        <button class="btnConnectServer" onclick="joinServer()"><img src="public/ressources/emergencyLogo.png" class="imgConnectServer">Join a server</button>
                        <button class="btnConnectServer" onclick="displayCreateServer()"><img src="public/ressources/emergencyLogo.png" class="imgConnectServer">Create own server</button>
                        <div class="selectServer">
                            <p class="title">Select a server</p>
                            <form method="POST">
                                <?php
                                    foreach ($listingServer as $server) {
                                ?>
                                   <button type="submit" class="btnConnectServer" name="<?="accessServerDispatch-" . $server['sid']?>"><img src="public/ressources/emergencyLogo.png" class="imgConnectServer"><?=$server['s_name']?></button>
                                <?php
                                    }
                                    if (count($listingServer) <= 0) {
                                ?>
                                    <p>You are not registered to a server</p>
                                <?php
                                    }
                                ?>
                                <button type="submit" name="disconnectDisptach" class="disconnectButton">Disconnect</button>
                            </form>
                        </div>
                    <?php
                        } else {
                            if ($srvIntels['uid_cr'] == $_SESSION['uid']) {
                                ?>
                                <form method="POST">
                                    <p class="title">Creator Server Options</p>
                                    <button class="shareButton" name="shareServer">Share Server</button>
                                    <button class="disconnectButton" name="removeServer">Delete Server</button>
                                </form>
                                <?php
                            }
                            if ($currTeam != "-1") {
                            ?>
                                <form method="POST">
                                    <p class="title"><?= $srvIntels['s_name'] . " Panel"?></p>
                            <?php
                                echo $dispatch->getNavBarDispatchServer();
                            ?>
                                </form>
                            <?php
                            } else if ($currTeam == "-1") {
                                ?>
                                <form method="POST">
                                    <p class="title">Select your Team</p>
                                    <button type="submit" name="disconnectServer" class="disconnectButton">Disconnect</button>
                                </form>
                                <?php
                            }
                        }
                    ?>
                </div>
                <div id="boxContent">
                    <?php
                        if ($connectToServ == "-1") {
                    ?>
                    <div id="createServer" style="display: none;">
                        <form class="formCreateSerever" method="POST">
                            <label>Create own Server</label>
                            <input type="text" placeholder="Srever name..." name="nameServerAdd" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required>
                            <input type="password" placeholder="Password..." name="passwordServerAdd" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                            <input type="number" id="nbTeamsCreateServer" value="0" name="nbTeams" hidden>
                            <input type="number" id="maxIdteamCreateServer" value="0" name="maxId" hidden>
                            <button class="btnChooseTeam" type="button" onclick="addTeamCreateSrever()">Add team</button>
                            <div id="teamsList">
                            </div>
                            <button class="btnChooseTeam" name="createServer">Create server</button>
                        </form>
                    </div>
                    <div id="joinServer" style="display: none;">
                        <form class="formCreateSerever" method="POST">
                            <label>Join a Server</label>
                            <input type="text" placeholder="Link..." name="joinTheServerLink">
                            <button class="btnChooseTeam" type="submit" name="joinTheServer">Join</button>
                        </form>
                    </div>
                    <?php
                        } else if ($currTeam == "-1") {
                    ?>
                        <div id="chooseTeam">
                            <form method="POST" class="formChooseTeams">
                                <?php
                                    foreach ($serverTeams as $i => $team) {
                                ?>
                                    <div class="divChooseTeam">
                                        <h4><?=$team['name']?></h4>
                                        <div class="inputsChooseTeam">
                                            <?php
                                                if (isset($_SESSION['errorTeam'])) {
                                                    $statsError = explode("=", $_SESSION['errorTeam'], 2);
                                                    if ($statsError[0] == $i) {
                                            ?>
                                                <div class="errorCallSign">
                                                    <p><?=$statsError[1]?></p>
                                                </div>
                                            <?php
                                                    }
                                                }
                                            ?>
                                            <?php
                                                if ($team['ndClS'] == true) {
                                            ?>
                                                <input class="callSignInput" type="text" name="callSign-<?=$i?>" placeholder="Call sign">
                                            <?php
                                                }
                                            ?>
                                            <button type="submit" class="btnChooseTeam" name="cT-<?=$i?>">Choisir</button>
                                            <?php
                                                if ($srvIntels['uid_cr'] == $_SESSION['uid']) {
                                            ?>
                                                <button class="btnEditionTeamChoose" title="Edit team"><i class='bx bx-edit-alt bx-sm'></i></button>
                                                <button class="btnEditionTeamChoose" name="dltT-<?=$i?>" title="Delete team"><i class='bx bx-trash bx-sm'></i></button>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                <?php
                                    }
                                ?>
                                <div class="divChooseTeam">
                                    <div class="alignDivChooseAdd">
                                        <h4>New team</h4>
                                        <input class="callSignInput" type="text" placeholder="Team name..." name="chooseTeamNewTeam" onkeyup="restrictLetter(this)">
                                    </div>
                                    <div class="inputsChooseTeam">
                                        <div class="toggle chooseToggle"><input type="checkbox" name="cCallChooseNewTeam"><label>Can call</label></div>
                                        <div class="toggle chooseToggle"><input type="checkbox" name="cDispChooseNewTeam"><label>Can dispatch</label></div>
                                        <div class="toggle chooseToggle"><input type="checkbox" name="iRespChooseNewTeam"><label>Is Responder</label></div>
                                        <div class="toggle chooseToggle"><input type="checkbox" name="ndClSChooseNewTeam"><label>Need a call sign ?</label></div>
                                        <button type="submit" class="btnChooseTeam" name="createTeamChooseTeam">Créer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php
                        } else {
                            echo $dispatch->getBoxContainerDispatchServer();
                            ?>
                            <div class="responderInterface">
                                <div class="headerInterface"></div>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php
            if (isset($_SESSION['wpwdServer'])) {
        ?>
            <style>
                .content {
                    filter: blur(4px);
                    pointer-events: none;
                }
                .popupWantPwd {
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%);
                    font-family: 'Didact Gothic', sans-serif;
                }

                .formNeedPwd {
                    text-align: center;
                    width: 400px;
                    color: rgb(141, 141, 141);
                    background-color: #333;
                    padding-top: 20px;
                    padding-bottom: 20px;
                    padding-right: 10px;
                    padding-left: 10px;
                    border-radius: 5px;
                }

                .formNeedPwd input[type="password"] {
                    width: 80%;
                    margin-left: 10%;
                    margin-right: 10%;
                    height: 50px;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .formNeedPwd label {
                    font-size: large;
                    font-weight: bold;
                }

                .formNeedPwd button {
                    width: 50%;
                    margin-left: 25%;
                    margin-right: 25%;
                    height: 40px;
                    margin-top: 10px;
                    margin-bottom: 10px;
                    border: none;
                    background-color: black;
                    color: white;
                    font-weight: bold;
                }

                .formNeedPwd button:hover {
                    cursor: pointer;
                    background-color: rgb(41, 41, 41);
                }
            </style>
            <div class="popupWantPwd">
                <form method="POST" class="formNeedPwd">
                    <label>Server need password</label>
                    <input type="password" name="passwordServer" placeholder="Password..." value="" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                    <?php
                        if (isset($_SESSION['errorValidPwd'])) {
                    ?>
                        <div class="errorValidPwd">
                            <p><?=$_SESSION['errorValidPwd']?></p>
                        </div>
                    <?php
                        }
                    ?>
                    <button name="validPwd">OK</button>
                    <button name="cancelValidePwd">Cancel</button>
                </form>
            </div>
        <?php
            }
        ?>
            <div id="popupJoinServer" class="popupWantPwd">
                <form method="POST" class="formNeedPwd">
                    <label>Join a server</label>
                    <input type="password" name="passwordServer" placeholder="Password..." value="" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                    <?php
                        if (isset($_SESSION['errorValidPwd'])) {
                    ?>
                        <div class="errorValidPwd">
                            <p><?=$_SESSION['errorValidPwd']?></p>
                        </div>
                    <?php
                        }
                    ?>
                    <button name="validPwd">OK</button>
                    <button name="cancelValidePwd">Cancel</button>
                </form>
            </div>
        <?php
            if (isset($_SESSION['displayInviteLink'])) {
        ?>
            <style>
                .content {
                    filter: blur(4px);
                    pointer-events: none;
                }
                .popupShareLink {
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%);
                    font-family: 'Didact Gothic', sans-serif;
                }

                .ShareLink {
                    text-align: center;
                    width: 400px;
                    color: rgb(141, 141, 141);
                    background-color: #333;
                    padding-top: 20px;
                    padding-bottom: 20px;
                    padding-right: 10px;
                    padding-left: 10px;
                    border-radius: 5px;
                }

                .ShareLink input[type="password"] {
                    width: 80%;
                    margin-left: 10%;
                    margin-right: 10%;
                    height: 50px;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .ShareLink label {
                    font-size: large;
                    font-weight: bold;
                }

                .ShareLink button {
                    width: 50%;
                    margin-left: 25%;
                    margin-right: 25%;
                    height: 40px;
                    margin-top: 10px;
                    margin-bottom: 10px;
                    border: none;
                    background-color: black;
                    color: white;
                    font-weight: bold;
                }

                .ShareLink button:hover {
                    cursor: pointer;
                    background-color: rgb(41, 41, 41);
                }
            </style>
            <div class="popupShareLink">
                <form method="POST" class="ShareLink">
                    <label>Server Share Link</label>
                    <p><?=$_SESSION['displayInviteLink']?></p>
                    <button name="okShareLink">OK</button>
                </form>
            </div>
        <?php
            }
        ?>
    </body>
</html>
