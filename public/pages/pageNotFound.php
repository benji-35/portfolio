<?php
    global $db, $rtr, $hlp, $trans;

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
            <div class="titleHeader">
                Erreur 404
            </div>
        </nav>
    </header>
    <body>
        <div class="erreorBox">
            <h3><?= $trans->getlanguage("pageNotFound") ?></h3>
            <p><a href="<?= $rtr->getMainUrl()?>"><?= $trans->getlanguage("backToMainPage") ?></a></p>
        </div>
        <!--  -->
        <!--  -->
    </body>
</html>
