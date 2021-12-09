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
        </div>
    </body>
</html>
