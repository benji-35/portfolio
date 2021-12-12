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
        <link rel="icon" href="public/ressources/BDLogo.png">
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <title><?=$_SESSION['titlePage']?></title>
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
        <div class="navBarWindows">
            <div>
                <a href="cv"><?=$trans->getlanguage("myCv")?></a>
                <a href="copies">Mes Copies</a>
                <a href="projects">Mes Projets</a>
            </div>
        </div>
        <div class="mainContent">
            <div class="alignDivs">
                <div class="leftMainContent">
                    <?= str_replace("\\n", "<br>", $trans->getlanguage("titleDescription")) ?>
                </div>
                <div class="rightMainContent">
                    
                </div>
            </div>
            <div class="totalyMainContent">

            </div>
        </div>
    </body>
</html>
