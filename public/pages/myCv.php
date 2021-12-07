<?php
    global $hlp, $rtr, $db, $trans;

    $heightLangButton = 30;

    $languages = $hlp->getAvailableLanguages();

    $minHeight = 0;
    foreach ($languages as $language) {
        $minHeight += $heightLangButton;
        if (isset($_POST['changeLang-' . $language[0]])) {
            $_SESSION['lang'] = $language[0];
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
            <div class="hideLink">
                <a href="<?= $rtr->getMainUrl() . "/hide" ?>">
                    <i class='bx bxs-hide'></i>
                </a>
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
                <a href="cv">Mon CV</a>
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
                <div class="leftContent">
                    <div class="historyDiv">
                        <h2>My History</h2>
                        <p>Born the 1st January 2002 in France,<br><br>
                        I am a generalist course of the French school system. I obtained my baccalaureate in 2019 with the mention "Good".<br><br>
                        I joined a school called Epitech in Rennes.<br><br>
                        In 2018 I joined French Lifeguard (SNSM). During Summer I'm on beaches to save people and for the rest of the year I'm in rescue post or in ambulance.<br>
                        With this I learned what give your life for others did mean. This passion takes me a lot of time.<br><br>
                        From young to my 19 I was in swimming club with a regional level.
                        </p>
                    </div>
                    <div class="historyDiv">
                        <div class="headerHistoryDiv">
                            <h2>My Projects</h2>
                        </div>
                        <div class="experienceDiv">
                            <h3>CMS Kapweb</h3>
                            <div class="headerExpDiv">
                                <h5>Début : 10 Novembre 2021</h5>
                                <h5>Fin : en cours de developpement</h5>
                            </div>
                            <p>Développement du CMS kapweb (premières versions) disponible sur github</p>
                        </div>
                        <div class="experienceDiv">
                            <h3>Indentation</h3>
                            <div class="headerExpDiv">
                                <h5>Début : 7 Janvier 2021</h5>
                                <h5>Fin : 17 Mars 2021</h5>
                            </div>
                            <p>Programme permettant de vérifier la stylisation du code C avec le format Epitech (format 2021)</p>
                        </div>
                        <div class="experienceDiv">
                            <h3>MGPB Experiment</h3>
                            <div class="headerExpDiv">
                                <h5>Début : 2 Avril 2021</h5>
                                <h5>Fin : 4 Avril 2021</h5>
                            </div>
                            <p>MGPB Experiment un jeu dévloppé durant une JAM Epitech. On avait 72h pour créer</p>
                        </div>
                    </div>
                </div>
                <div class="rightContent">
                    <div class="historyDiv">
                        <div class="headerHistoryDiv">
                            <h2>My experiences</h2>
                            <select id="selectExp" onchange="filterExp()">
                                <option selected value="0">All</option>
                                <option value="1">Programming</option>
                                <option value="2">Rescue</option>
                                <option value="3">volunteering Experiences</option>
                                <option value="4">Professional Experiences</option>
                                <option value="5">Own Experiences</option>
                            </select>
                        </div>
                        <div id="listExp">
                            <div class="experienceDiv proExp rescueExp">
                                <h3>Qualified Lifeguard</h3>
                                <div class="headerExpDiv">
                                    <h5>Place : Saint Malo, France</h5>
                                    <h5>Date : Août 2020</h5>
                                </div>
                                <p>Surveillance of the Minihic, Val and Bas-Sablons beaches</p>
                            </div>
                            <div class="experienceDiv proExp rescueExp">
                                <h3>Stage de 3ème</h3>
                                <div class="headerExpDiv">
                                    <h5>Place : Rennes, France</h5>
                                    <h5>Date : Mars 2017</h5>
                                </div>
                                <p>Discovery of the professions of the National Gendarmerie</p>
                            </div>
                            <div class="experienceDiv volExp rescueExp">
                                <h3>Rescuer</h3>
                                <div class="headerExpDiv">
                                    <h5>Place : *not mentionned*</h5>
                                    <h5>Date : 2018-2021</h5>
                                </div>
                                <p>Festival du Roi Arthur le 25 août 2019,<br>Matchs de Foot au Stade Rennais,<br>Championnat de France de Natation 2019,<br>Concours Hippiques à Maure de Bretagne,<br>Entraînement Super Motard</p>
                            </div>
                            <div class="experienceDiv volExp rescueExp">
                                <h3>Chief of first aid station</h3>
                                <div class="headerExpDiv">
                                    <h5>Place : *not mentionned*</h5>
                                    <h5>Date : 2018-2021</h5>
                                </div>
                                <p>Open de Tennis 2019 Rennes et Open de Tennis 2021 Rennes</p>
                            </div>
                            <div class="experienceDiv proExp progExp">
                                <h3>Stage de 2ème année Epitech</h3>
                                <div class="headerExpDiv">
                                    <h5>Place : Granville, France</h5>
                                    <h5>Date : 9 août 2021 - 23 décembre 2021</h5>
                                </div>
                                <p>Stage chez VDP 3.0 pour le dévloppement de l'application Quivive<sup>App</sup></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
