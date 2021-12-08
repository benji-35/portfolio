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
    $projects = $hlp->getMyProjects();
    $experiences = $hlp->getMyExperiences();
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
                <a href="cv"><?=$trans->getlanguage("myCv")?></a>
                <a href="copies">Mes Copies</a>
                <a href="projects">Mes Projets</a>
            </div>
        </div>
        <div class="mainContent">
            <div class="cvBaniereParent">
                <div class="cvBaniere" style="background-image: url('public/ressources/spacescape.jpg');">

                </div>
                <div class="cvImgprofile" style="background-image: url('public/ressources/photoMe.jpg');">

                </div>
            </div>
            <div class="totalyMainContent">
                <div class="leftContent">
                    <div class="historyDiv">
                        <h2><?=$trans->getlanguage("myHistoryTitle")?></h2>
                        <p><?=$trans->getlanguage("myHistory")?></p>
                    </div>
                    <div class="historyDiv">
                        <div class="headerHistoryDiv">
                            <h2><?=$trans->getlanguage("myProjectsTitle")?></h2>
                        </div>
                        <?php
                            foreach ($projects as $project) {
                                $descripProj = "";
                                $targetLang = $_SESSION['lang'];
                                $goodLang = false;
                                foreach ($project['languages'] as $projectLanguage) {
                                    if ($projectLanguage == $targetLang) {
                                        $goodLang = true;
                                        break;
                                    }
                                }
                                if ($goodLang == false) {
                                    $targetLang = $project['initLang'];
                                }
                                foreach ($project['descriptions'] as $descriptionProject) {
                                    if ($descriptionProject['lang'] == $targetLang) {
                                        $descripProj = $descriptionProject['description'];
                                        break;
                                    }
                                }
                        ?>
                            <div class="experienceDiv">
                                <h3><?= $project['name'] ?></h3>
                                <div class="headerExpDiv">
                                    <h5>Début : <?= $project['startDate'] ?></h5>
                                    <h5>Fin : <?= $project['endDate'] ?></h5>
                                </div>
                                <p><?= $descripProj ?></p>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="rightContent">
                    <div class="historyDiv">
                        <div class="headerHistoryDiv">
                            <h2><?=$trans->getlanguage("myExperiencesTitle")?></h2>
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
                            <?php
                                foreach ($experiences as $experience) {
                                    $descripProj = "";
                                    $nameExp = "";
                                    $targetLang = $_SESSION['lang'];
                                    $goodLang = false;
                                    foreach ($experience['languages'] as $projectLanguage) {
                                        if ($projectLanguage == $targetLang) {
                                            $goodLang = true;
                                            break;
                                        }
                                    }
                                    if ($goodLang == false) {
                                        $targetLang = $experience['initLang'];
                                    }
                                    foreach ($experience['descriptions'] as $descriptionProject) {
                                        if ($descriptionProject['lang'] == $targetLang) {
                                            $descripProj = $descriptionProject['description'];
                                            break;
                                        }
                                    }
                                    foreach ($experience['name'] as $name) {
                                        if ($name['lang'] == $targetLang) {
                                            $nameExp = $name['name'];
                                            break;
                                        }
                                    }
                            ?>
                                <div class="experienceDiv <?=$experience['types']?>">
                                    <h3><?= $nameExp ?></h3>
                                    <div class="headerExpDiv">
                                        <h5>Place : <?= $experience['place'] ?></h5>
                                        <h5>Date : <?= $experience['date'] ?></h5>
                                    </div>
                                    <p><?= $descripProj ?></p>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="competencesDiv">
                <div class="divComp">
                    <h2><?=$trans->getlanguage("myCompetencesTitle")?></h2>
                    <table class="competencesTable" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="competencetableName"></th>
                                <th class="competencetablePercent"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="competencetableName">PHP (80%)</td>
                                <td class="competencetablePercent">
                                    <span class="spanComp start-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp end-nocomp"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="competencetableName">CSS (60%)</td>
                                <td class="competencetablePercent">
                                    <span class="spanComp start-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp end-nocomp"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="competencetableName">HTML (70%)</td>
                                <td class="competencetablePercent">
                                    <span class="spanComp start-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp end-nocomp"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="competencetableName">C (90%)</td>
                                <td class="competencetablePercent">
                                    <span class="spanComp start-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp end-nocomp"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="competencetableName">C# - Unity 3D (90%)</td>
                                <td class="competencetablePercent">
                                    <span class="spanComp start-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp end-nocomp"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="competencetableName">Python (40%)</td>
                                <td class="competencetablePercent">
                                    <span class="spanComp start-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp end-nocomp"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="competencetableName">Java (50%)</td>
                                <td class="competencetablePercent">
                                    <span class="spanComp start-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp end-nocomp"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="competencetableName">Javascript (20%)</td>
                                <td class="competencetablePercent">
                                    <span class="spanComp start-comp"></span>
                                    <span class="spanComp middle-comp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp middle-nocomp"></span>
                                    <span class="spanComp end-nocomp"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
