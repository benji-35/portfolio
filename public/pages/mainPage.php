<?php
    global $hlp, $rtr, $db, $trans;

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
        header("location:" . $rtr->getMainUrl());
    }
    if (isset($_POST['coAccount'])) {
        unset($_SESSION['connexionRedirect']);
        $_SESSION['connexionRedirect'] = $rtr->getMainUrl();
        header("location: " . $rtr->getMainUrl() . "/connection");
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
                     <button class="btnManageAccountMain" name="discoAccount" title="<?= $trans->getlanguage("w-logout", "Log out") ?>"><i class='bx bxs-user-minus bx-md' ></i></button>
                <?php
                    } else {
                ?>
                    <button class="btnManageAccountMain" name="coAccount" title="<?= $trans->getlanguage("w-login", "Log in") ?>"><i class='bx bxs-user-plus bx-md'></i></button>
                <?php
                    }
                ?>
                </form>
            </div>
            <div class="logo">
                <a href="<?= $rtr->getMainUrl() ?>"><img src="public/ressources/BDLogo.png" alt=""></a>
            </div> 
            <div class="titleHeader">Portfolio</div>
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
        <div id="hiddingIntelsFront">
            <input type="text" id="phraseTitle1" value="<?=$trans->getlanguage("phraseTitle1", "Phrase 1 not found !")?>">
            <input type="text" id="phraseTitle2" value="<?=$trans->getlanguage("phraseTitle2", "Phrase 2 not found !")?>">
            <input type="text" id="phraseTitle3" value="<?=$trans->getlanguage("phraseTitle3", "Phrase 3 not found !")?>">
            <input type="text" id="phraseTitle4" value="<?=$trans->getlanguage("phraseTitle4", "Phrase 4 not found !")?>">
        </div>
        <div class="navBarWindows">
            <div>
                <a href="cv"><?=$trans->getlanguage("myCv")?></a>
                <a href="copies"><?=$trans->getlanguage("myCopies", "My copies")?></a>
                <a href="projects"><?=$trans->getlanguage("myProjectsTitle", "My projects")?></a>
            </div>
        </div>
        <div class="mainContent">
            <div class="middleDiv">
                <h1 id="titleAnimated"></h1>
            </div>
        </div>
    </body>
</html>
