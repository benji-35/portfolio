<?php
    global $hlp, $rtr, $db, $trans, $discuss;

    $heightLangButton = 30;

    $languages = $hlp->getAvailableLanguages();

    $minHeight = 0;
    foreach ($languages as $language) {
        $minHeight += $heightLangButton;
        if (isset($_POST['changeLang-' . $language[0]])) {
            $_SESSION['lang'] = $language[0];
            header("Refresh:0");
        }
    }
    if ($minHeight == 0) {
        $minHeight = $heightLangButton;
    }

    if (isset($_POST['discoAccount'])) {
        $hlp->disconnectUser();
        header("location:" . $rtr->getMainUrl() . "/discuss");
    }
    if (isset($_POST['coAccount'])) {
        unset($_SESSION['connexionRedirect']);
        $_SESSION['connexionRedirect'] = $rtr->getMainUrl() . "/discuss";
        header("location: " . $rtr->getMainUrl() . "/connection");
    }

    $mDiscusses = $discuss->getMyDiscussions();
    $messages = array();

    if (isset($_GET['discuusId']) && isset($_POST['sendMsg']) && isset($discuss)) {
        $msg = $_POST['msgToSendEncoded'];
        $idDiscuss = $_GET['discuusId'];
        $discuss->sendMessageDiscuss($idDiscuss, $msg);
        header("location: " . $rtr->getMainUrl() . "/discuss&discuusId=" . $_GET['discuusId']);
    }

    if (isset($_GET['discuusId'])) {
        $messages = $discuss->getMessagesDiscussion($_GET['discuusId']);
    }

    if ($mDiscusses['errorMsg'] == "") {
        $tmpArrDiscusses = $mDiscusses;
        unset($tmpArrDiscusses['errorMsg']);
        foreach ($tmpArrDiscusses as $discuss) {
            if (isset($_POST['accessDiscuss-' . $discuss['id']])) {
                header("location: " . $rtr->getMainUrl() . "/discuss&discuusId=" . $discuss['id']);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="<?=$_SESSION['lang']?>">
    <head>
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <title><?=$_SESSION['titlePage']?></title>
        <meta charset="utf-8">
        <?php
            if (isset($_SESSION['cssPage'])) {
                foreach ($_SESSION['cssPage'] as $cssScript) {
        ?>
            <link rel="stylesheet" href="<?=$cssScript?>">
        <?php
                }
            }
            if (isset($_SESSION['jsPage'])) {
                foreach ($_SESSION['jsPage'] as $jsScript) {
        ?>
            <script src="<?=$jsScript?>"></script>
        <?php
                }
            }
            if (isset($_SESSION['pageIcon'])) {
        ?>
            <link rel="icon" href="<?=$_SESSION['pageIcon']?>">
        <?php
            }
        ?>
    </head>
    <header>
        <nav>
            <div class="accountArea">
                <form method="POST">
                <?php
                    if ($hlp->isConnected()) {
                ?>
                     <button class="btnManageAccountMain" name="discoAccount"><i class='bx bxs-user-minus bx-md' ></i></button>
                <?php
                    } else {
                ?>
                    <button class="btnManageAccountMain" name="coAccount"><i class='bx bxs-user-plus bx-md'></i></button>
                <?php
                    }
                ?>
                </form>
            </div>
            <div class="logo">
                <a href="<?= $rtr->getMainUrl() ?>"><img src="public/ressources/BDLogo.png" alt=""></a>
            </div> 
            <div class="titleHeader"><?= $trans->getlanguage("myDiscussionTitle", "My discussions") ?></div>
            <div class="lang-menu">
                <div class="selected-lang <?=$_SESSION['lang']?>">
                    <?=$hlp->getLangName($_SESSION['lang'])?>
                </div>
                <ul>
                    <form method="POST">
                    <?php
                        foreach ($languages as $language) {
                            if ($language[0] != $_SESSION['lang']) {
                    ?>
                    <li>
                        <button type="submit" name="changeLang-<?=$language[0]?>" class="<?=$language[0]?>"><?=$language[1]?></button>
                    </li>
                    <?php
                            }
                        }
                    ?>
                    </form>
                </ul>
                
            </div>
        </nav>
    </header>
    <body>
        <div class="mainContent">
            <div class="parentDiscusses">
                <div class="discussionsListPart">
                    <?php
                        if ($mDiscusses['errorMsg'] == "") {
                            unset($mDiscusses['errorMsg']);
                    ?>
                    <form method="POST">
                        <?php
                            foreach ($mDiscusses as $discuss) {
                        ?>
                            <button name="accessDiscuss-<?= $discuss['id'] ?>"><?= $discuss['title']?></button>
                        <?php
                            }
                        ?>
                    </form>
                    <?php
                        } else {
                            echo "<h3>" . $mDiscusses['errorMsg'] . "</h3>";
                        }
                    ?>
                </div>
                <div class="messagesDiscussionPart">
                    <?php
                        if (count($messages) > 1) {
                            unset($messages['errorMsg']);
                    ?>
                    <div class="msgContent">
                        <?php
                            foreach ($messages as $idMsg => $msg) {
                                $classSender = "otherSender";
                                $styleMessageParent = "style=\"justify-content: ";
                                $msgContent = $msg['msg'];
                                if ($msg['sender'] == "" . $_SESSION['uid']) {
                                    $classSender = "iSendMessage";
                                    $styleMessageParent .= "end\"";
                                } else {
                                    $styleMessageParent .= "start\"";
                                }
                        ?>
                            <div class="message" <?=$styleMessageParent?>>
                                <div class="<?=$classSender?>">
                                    <p id="decodeContent-<?= $idMsg ?>"></p>
                                    <script src="<?=$_SESSION['jsPage']?>" onload="decodeMsg('<?=$msgContent?>', 'decodeContent-<?=$idMsg?>')">
                                    </script>
                                </div>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                    <?php
                        } else {
                    ?>
                        <div class="msgContent">
                            <h3>Commencez votre discussion dès maintenant</h3>
                        </div>
                    <?php
                        }
                    ?>
                    <form method="POST" class="msgSenderForm">
                        <input type="text" id="msgToSendNormal" placeholder="Message..." oninput="encodeMsgFromInput('msgToSendNormal', 'msgToSendEncoded')" required>
                        <input type="text" id="msgToSendEncoded" name="msgToSendEncoded">
                        <button type="submit" name="sendMsg">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
