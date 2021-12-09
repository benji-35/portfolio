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
    $projects = $hlp->getMyProjects();
    $experiences = $hlp->getMyExperiences();
    $competences = $hlp->getMyCompetences();
    $isAdm = $hlp->isAdm();
    $isEditing = false;
    $edtInetls = array();

    foreach ($projects as $project) {
        if (isset($_POST['edt-proj' . $project['id']])) {
            header("location:" . $rtr->getMainUrl() . "/cv&edt=proj;" . $project['id']);
        }
        if (isset($_POST['del-proj' . $project['id']])) {
            
        }
    }

    foreach ($experiences as $experience) {
        if (isset($_POST['edt-exp' . $experience['id']])) {
            header("location:" . $rtr->getMainUrl() . "/cv&edt=exp;" . $experience['id']);
        }
        if (isset($_POST['del-exp' . $experience['id']])) {
            
        }
    }

    if ($isAdm && isset($_GET['edt'])) {
        $isEditing = true;
        $intels = explode(";", $_GET['edt'], 2);
        if (count($intels) < 2) {
            $isEditing = false;
        } else {
            $idTarget = $intels[1];
            $edtInetls = array(
                "type" => $intels[0],
                "content" => array(),
            );
            if ($edtInetls["type"] == "proj") {
                foreach ($projects as $project) {
                    if ($project['id'] == $idTarget) {
                        $edtInetls['content'] = $project;
                    }
                }
            } else if ($edtInetls["type"] == "exp") {
                foreach ($experiences as $experience) {
                    if ($experience['id'] == $idTarget) {
                        $edtInetls['content'] = $experience;
                    }
                }
            } else if ($edtInetls["type"] == "comp") {
                foreach ($competences as $competence) {
                    if ($competence['id'] == $idTarget) {
                        $edtInetls['content'] = $competence;
                    }
                }
            } else {
                $edtInetls["content"] = array("no content");
            }
        }
    }
    if (isset($_POST['backEdit'])) {
        header("location:" . $rtr->getMainUrl() . "/cv");
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
            <div class="titleHeader"><?=$trans->getlanguage("myCv")?></div>
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
            <?php
                if ($isAdm == true) {
            ?>
                <form method="POST">
            <?php
                }
            ?>
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
                                    <?php
                                        if ($isAdm == true) {
                                    ?>
                                        <button type="submit" name="edt-proj<?=$project['id']?>"><i class='bx bxs-edit-alt'></i></button>
                                        <button type="submit" name="del-proj<?=$project['id']?>"><i class='bx bxs-trash'></i></button>
                                    <?php
                                        }
                                    ?>
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
                                    <option selected value="0"><?= $trans->getlanguage("w-all")?></option>
                                    <option value="1"><?= $trans->getlanguage("w-programming")?></option>
                                    <option value="2"><?= $trans->getlanguage("w-rescue")?></option>
                                    <option value="3"><?= $trans->getlanguage("vol-exps")?></option>
                                    <option value="4"><?= $trans->getlanguage("pro-exps")?></option>
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
                                        <?php
                                            if ($isAdm == true) {
                                        ?>
                                            <button type="submit" name="edt-exp<?=$experience['id']?>"><i class='bx bxs-edit-alt'></i></button>
                                            <button type="submit" name="del-exp<?=$experience['id']?>"><i class='bx bxs-trash'></i></button>
                                        <?php
                                            }
                                        ?>
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
                            <?php
                                foreach ($competences as $competence) {
                                    $competence['description'] = mb_convert_encoding($competence['description'], "UTF-8", "ASCII");
                                    $competence['name'] = mb_convert_encoding($competence['name'], "UTF-8", "ASCII");
                                    $spansComp = "<div class=\"divPercentParent\"><div style='width: " . $competence['percent'] . "%'><p>" . $competence['percent'] . "%</p></div></div>";
                                    $tmpPercent = $competence['percent'];
                            ?>
                                <tr>
                                    <td class="competencetableName"><?=$competence['name']?></td>
                                    <td class="competencetablePercent"><?=$spansComp?></td>
                                    <td class="competencetableDescription">
                                        <?=$competence['description']?>
                                        <?php
                                            if ($isAdm == true) {
                                        ?>
                                            <button type="submit" name="edt-comp<?=$competence['id']?>"><i class='bx bxs-edit-alt'></i></button>
                                            <button type="submit" name="del-comp<?=$competence['id']?>"><i class='bx bxs-trash'></i></button>
                                        <?php
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
                    if ($isAdm == true) {
                ?>
                    </form>
                <?php
                    }
                ?>
        </div>
        <?php
            if ($isEditing == true && $isAdm == true) {
        ?>
            <style>
                .navBarWindows, .mainContent {
                    filter: blur(4px);
                    pointer-events: none;
                }

                .popupEdit {
                    position: absolute;
                    width: 400px;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%);
                    background-color: #f2f2f2;
                    padding-top: 10px;
                    padding-bottom: 10px;
                    text-align: center;
                }
            </style>
            <form class="popupEdit" method="POST">
                <h2>Edition</h2>
                <?php
                    if ($edtInetls['type'] == "proj") {
                ?>
                    <input type="text" name="nameProjEdt" placeholder="Name project" value="<?=$edtInetls['content']['name']?>">
                <?php
                    } else if ($edtInetls['type'] == "exp") {
                ?>
                <?php
                    } else if ($edtInetls['type'] == "comp") {
                ?>
                    <h4>Type de contenue inconnue</h4>
                <?php
                    }
                ?>
                <button type="submit">Valider</button>
                <button type="submit" name="backEdit">Retour</button>
            </form>
        <?php
            }
        ?>
    </body>
</html>
