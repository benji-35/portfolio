<!DOCTYPE html>
<html lang="<?=$_SESSION['lang']?>">
    <head>
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
        <link rel="icon" href="public/ressources/googleLogo2.webp">
    </head>
    <header>
        <div class="googleAccount">
            <a href="#" class="gmail">Gmail</a>
            <a href="#" class="Images">Images</a>
            <button class="btnSoftwares"><i class='bx bxs-grid bx-md'></i></button>
            <button class="btnAccount" onclick="showHidePopup()"><img src="public/ressources/kap35Logo.bmp"></button>
        </div>
    </header>
    <body>
        <div class="contentBody">
            <img class="pictMainGoogle" src="public/ressources/googleImgSearch.gif" alt="description of gif" />
            <div class="searchBarParent">
                <div class="searchbar">
                    <i class='bx bx-search searchLoop bx-sm'></i>
                </div>
            </div>
            <div class="buttonBehindSearch">
                <button>Recherche Google</button>
                <button>J'ai de la chance</button>
            </div>
            <p class="languageAvailable">Disponible en : <a href="?lang=en">English</a></p>
        </div>
        <div id="accountPopup">
            <div class="accountIntelsPopup">
                <img class="logoAccountPopup" src="public/ressources/kap35Logo.bmp">
                <p>kap35</p>
                <p>delvertus35@gmail.com</p>
                <button>Gérer votre compte</button>
            </div>
            <div class="othersAccountPopup">
                <button class="accountOther">
                    <img class="logoOtherAccount">
                    <p>Mon autre compte</p>
                    <p>monautrecompte@gmail.com</p>
                </button>
                <button class="accountOther">
                    <img class="logoOtherAccount">
                    <p>Ajouter un compte</p>
                </button>
            </div>
            <div class="disconnectAccountPopup">

            </div>
        </div>
    </body>
    <footer>
        <div class="footerUp">
            <p>France</p>
        </div>
        <div class="footerDown">
            <div class="footerDownLeft">
                <ul>
                    <a href="#">A propos</a>
                    <a href="#">Publicité</a>
                    <a href="#">Entreprise</a>
                    <a href="#">Comment fonctionne la recherche Google ?</a>
                </ul>
            </div>
            <div class="footerDownMiddle">
                <a href="#"><img src="public/ressources/noCarbon.png">Neutre en carbonne depuis 2007</a>
            </div>
            <div class="footerDownRight">
                <ul>
                    <a href="#">Informations Consommateurs</a>
                    <a href="#">Confidentialité</a>
                    <a href="#">Conditions</a>
                    <a href="#">Paramètres</a>
                </ul>
            </div>
        </div>
    </footer>
</html>