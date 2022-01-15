<?php
    global $hlp, $rtr, $db, $trans;

    $pageLang = $_SESSION['lang'];
    $redirectConnect = "home";
    if (isset($_SESSION['connexionRedirect'])) {
        $redirectConnect = $_SESSION['connexionRedirect'];
        if ($redirectConnect == "") {
            $redirectConnect = $rtr->getMainUrl();
        }
    } else {
        $redirectConnect = $rtr->getMainUrl();
    }
    if (isset($_POST['connect'])) {
        if (isset($_SESSION['connectError'])) {
            unset($_SESSION['connectError']);
        }
        $resConnect = $hlp->connectUser($_POST['pseudo'], $_POST['password']);
        if ($resConnect == 0) {
            unset($_SESSION['connexionRedirect']);
            header("location: " . $redirectConnect);
        } else {
            $_SESSION['connectError'] = $hlp->getConnectionErrors($resConnect);
        }
    }
    if (isset($_POST['create'])) {
        if (isset($_SESSION['connectError'])) {
            unset($_SESSION['connectError']);
        }
        $resCreate = $hlp->createAccount($_POST['pseudoCr'], $_POST['passwordCr'], $_POST['emailCr']);
        if ($resCreate == 0) {
            unset($_SESSION['connexionRedirect']);
            header("location: " . $redirectConnect);
        } else {
            $_SESSION['connectError'] = $hlp->getAccountCreationErrors($resCreate);
        }
    }
?>

<!DOCTYPE html>
<html lang="<?=$pageLang?>">
    <head>
        <title><?=$_SESSION['titlePage']?></title>
        <meta charset="utf-8">
        <?php
            if (isset($_SESSION['cssPage'])) {
        ?>
            <link rel="stylesheet" href="<?=$_SESSION['cssPage']?>">
        <?php
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
    <body>
        <div class="mainContent">
            <form method="POST" class="formConnect" id="connectAccount">
                <img class="logoDispatch" src="public/ressources/emergencyLogo.png">
                <h2><?= $trans->getlanguage("w-connection", "Connexion")?></h2>
                <input type="text" placeholder="Pseudo..." name="pseudo" required>
                <input type="password" placeholder="<?= $trans->getlanguage("w-password", "Password") . "..." ?>" name="password" required>
                <input type="submit" value="<?= $trans->getlanguage("w-connection", "Connexion")?>" name="connect">
                <button type="button" onclick="create()" class="optionalBtns">Pas de compte ?</button>
                <?php
                    if (isset($_SESSION['connectError'])) {
                ?>
                    <div class="errorPopup">
                        <button class="btnCloseError" type="button" onclick="closeError()">X</button>
                        <p><?=$_SESSION['connectError']?></p>
                    </div>
                <?php
                    }
                ?>
            </form>
            <form method="POST" class="formConnect" id="createAccount">
                <img class="logoDispatch" src="public/ressources/emergencyLogo.png">
                <h2><?= $trans->getlanguage("createAccount", "Create Account")?></h2>
                <input type="text" placeholder="Pseudo..." required name="pseudoCr">
                <input type="email" placeholder="Email..." required name="emailCr">
                <input type="password" placeholder="<?= $trans->getlanguage("w-password", "Password") . "..." ?>" name="passwordCr" required>
                <input type="submit" value="<?= $trans->getlanguage("w-create", "Create")?>" name="create">
                <button type="button" onclick="connect()" class="optionalBtns">Déjà un compte ?</button>
                <?php
                    if (isset($_SESSION['connectError'])) {
                ?>
                    <div class="errorPopup">
                        <button class="btnCloseError" type="button" onclick="closeError()">X</button>
                        <p><?=$_SESSION['connectError']?></p>
                    </div>
                <?php
                    }
                ?>
            </form>
        </div>
    </body>
</html>
