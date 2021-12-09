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
        <link rel="icon" href="public/ressources/YoutubeLogo.svg">
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
        <div class="catHeader logoAndNavBtn">
            <button class="btnNavToggle">
                <i class='bx bx-menu bx-md'></i>
            </button>
            <div class="websiteLogo">
                <img src="public/ressources/YoutubeLogo.svg">
                <p>YouTube<sup><?= $_SESSION['lang'] ?></sup></p>
            </div>
        </div>
        <div class="catHeader searchDiv">
            <div class="searchBar">
                <p>Search</p>
            </div>
            <button class="btnLoop"><div><i class='bx bx-search bx-xs'></i></div></button>
            <button class="voiceSearch"><i class='bx bxs-microphone bx-xs'></i></button>
        </div>
        <div class="catHeader accountHeader">
            <button class="btnHeaderAccount" title="Add video"><i class='bx bx-video-plus bx-sm'></i></button>
            <button class="btnHeaderAccount" title="Youtube applications"><i class='bx bxs-grid bx-sm'></i></button>
            <button class="btnHeaderAccount" title="Notifications"><i class='bx bx-bell bx-sm'></i></button>
            <button class="btnHeaderAccount" title="My Account"><img class="accountImg" src="public/ressources/kap35Logo.bmp"></button>
        </div>
    </header>
    <body>
        <div class="mainContent">
            <div class="navMenu">
                <div class="blockNav mainNav">
                    <button><i class='bx bxs-home'></i>Acceuil</button>
                    <button><i class='bx bx-compass'></i> Explorer</button>
                    <button><i class='bx bxs-videos'></i>Abonnements</button>
                </div>
                <div class="separateLine"></div>
                <div class="blockNav myNav">
                    <button><i class='bx bxs-videos'></i>Bibliothèque</button>
                    <button><i class='bx bxs-home'></i>Historique</button>
                    <button><i class='bx bxs-right-arrow'></i> Vos vidéos</button>
                    <button><i class='bx bx-time-five'></i>A regarder plus tard</button>
                    <button><i class='bx bx-like' ></i>Vidéo "J'aime"</button>
                    <button><i class='bx bx-down-arrow'></i>Plus</button>
                </div>
                <div class="separateLine"></div>
                <div class="blockNav subscribesNav">
                    <p>Abonnements</p>
                </div>
                <div class="separateLine"></div>
                <div class="blockNav othersNav">
                    <p>Autre contenue YouTube</p>
                </div>
                <div class="separateLine"></div>
                <div class="blockNav">
                    <div id="guide-links-primary" class="moreLinks">
                      <a href="https://www.youtube.com/about/">Présentation</a>
                      <a href="https://www.youtube.com/about/press/">Presse</a>
                      <a href="https://www.youtube.com/about/copyright/">Droits d'auteur</a>
                      <a href="/t/contact_us/">Nous contacter</a>
                      <a href="https://www.youtube.com/creators/">Créateurs</a>
                      <a href="https://www.youtube.com/ads/">Publicité</a>
                      <a href="https://developers.google.com/youtube">Développeurs</a>
                    </div>
                    <div id="guide-links-secondary" class="moreLinks">
                      <a href="/t/terms">Conditions d'utilisation</a>
                      <a href="https://policies.google.com/privacy?hl=fr">Confidentialité</a>
                      <a href="https://www.youtube.com/about/policies/">Règles et sécurité</a>
                      <a href="https://www.youtube.com/howyoutubeworks?utm_campaign=ytgen&amp;utm_source=ythp&amp;utm_medium=LeftNav&amp;utm_content=txt&amp;u=https%3A%2F%2Fwww.youtube.com%2Fhowyoutubeworks%3Futm_source%3Dythp%26utm_medium%3DLeftNav%26utm_campaign%3Dytgen">Premiers pas sur YouTube</a>
                      <a href="/new">Tester de nouvelles fonctionnalités</a>
                    </div>
                    <div id="copyright" slot="copyright">
                        <div>© 2021 Google LLC</div>
                    </div>
                </div>
            </div>
            <div class="mainPage">
                <div class="filterDiv">

                </div>
                <div class="mainVideos">

                </div>
            </div>
        </div>
    </body>
</html>