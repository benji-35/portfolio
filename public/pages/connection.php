<?php
    global $hlp, $rtr, $db, $trans;

    $pageLang = $_SESSION['lang'];
    $redirectConnect = "";
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
    if (isset($_POST['sendFrgtPwd'])) {
        $hlp->forgotPassword($_POST['emailFrgtPwd']);
        header("Refresh:0");
    }
    $pathChange = "";
    if (isset($_GET['lkpwd'])) {
        if ($hlp->isPathChangePasswordAvailable($_GET['lkpwd']) == false) {
            header("location: " . $rtr->getMainUrl() . "/pageNotFound");
        }
        $pathChange = $_GET['lkpwd'];
    }
    if (isset($_POST['validNewPassword']) && isset($_GET['lkpwd'])) {
        $hlp->setNewPwd($_GET['lkpwd'], $_POST['emailChgPwd'], $_POST['pwdNewChange']);
        header("location: " . $rtr->getMainUrl() . "/connection");
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
        ?>
            <script type="text/javascript" src="<?=$_SESSION['jsPage']?>"></script>
        <?php
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
            <?php
                if ($pathChange == "") {
            ?>
            <form method="POST" class="formConnect" id="connectAccount">
                <h2><?= $trans->getlanguage("w-connection", "Connexion")?></h2>
                <input type="text" placeholder="Pseudo..." name="pseudo">
                <input type="password" placeholder="<?= $trans->getlanguage("w-password", "Password") . "..." ?>" name="password">
                <input type="submit" value="<?= $trans->getlanguage("w-connection", "Connexion")?>" name="connect">
                <button type="button" onclick="create()" class="optionalBtns">Pas de compte ?</button>
                <button type="button" onclick="forgotPassword()" class="optionalBtns">J'ai oublié mon mot de passe</button>
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
            <form method="POST" class="formConnect" id="forgotPwd">
                <h2>J'ai oublié mon mot de passe</h2>
                <input type="email" placeholder="Email..." required name="emailFrgtPwd">
                <input type="submit" value="Send" name="sendFrgtPwd">
                <button type="button" onclick="connect()" class="optionalBtns">Retour</button>
            </form>
            <?php
                } else {
            ?>
                <form method="POST" class="formConnect">
                    <h2>Changer mon mot de passe</h2>
                    <input type="email" placeholder="Email..." name="emailChgPwd">
                    <input type="password" placeholder="<?=$trans->getlanguage("w-password", "Password")?>..." name="pwdNewChange">
                    <input type="submit" value="Changer" name="validNewPassword">
                </form>
            <?php
                }
            ?>
        </div>
    </body>
</html>
