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
            $hlp->deleteExpCompProj($project['id'], "proj");
            header("location:" . $rtr->getMainUrl() . "/cv");
        }
    }

    foreach ($experiences as $experience) {
        if (isset($_POST['edt-exp' . $experience['id']])) {
            header("location:" . $rtr->getMainUrl() . "/cv&edt=exp;" . $experience['id']);
        }
        if (isset($_POST['del-exp' . $experience['id']])) {
            $hlp->deleteExpCompProj($experience['id'], "exp");
            header("location:" . $rtr->getMainUrl() . "/cv");
        }
    }

    foreach ($competences as $competence) {
        if (isset($_POST['edt-comp' . $competence['id']])) {
            header("location:" . $rtr->getMainUrl() . "/cv&edt=comp;" . $competence['id']);
        }
        if (isset($_POST['del-comp' . $competence['id']])) {
            $hlp->deleteExpCompProj($competence['id'], "comp");
            header("location:" . $rtr->getMainUrl() . "/cv");
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
                "id" => $idTarget,
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
    if (isset($_POST['validEdit'])) {
        if ($edtInetls['type'] == "proj") {
            $startDate = "__/__/____";
            $endDate = "__/__/____";
            $projName = $_POST['nameProjEdt'];
            $initLang = $_POST['initLangProj'];
            $lngs = $_POST['langsProjectEdit'];
            $idElem = $_POST['idElem'];
            $lngsArray = explode(";", $lngs);
            $languages = array();
            foreach ($lngsArray as $lng) {
                $lngArr = array(
                    "lang" => $lng,
                    "descript" => "",
                );
                if (isset($_POST['descri-' . $lng])) {
                    $lngArr['descript'] = $_POST['descri-' . $lng];
                }
                array_push($languages, $lngArr);
            }
            if (isset($_POST['startProj']) && $_POST['startProj'] != "") {
                $startDate = date('d/m/Y', strtotime($_POST['startProj']));
            }
            if (isset($_POST['endProj']) && $_POST['endProj'] != "") {
                $endDate = date('d/m/Y', strtotime($_POST['endProj']));
            }
            $hlp->updateProj($projName, $idElem, $initLang, $languages, $startDate, $endDate);
        } else if ($edtInetls['type'] == "exp") {
            $date = "__/__/____ - __/__/____";
            $place = "?";
            $initLang = "en";
            if (isset($_POST['placeExp'])) {
                $place = $_POST['placeExp'];
            }
            if (isset($_POST['dateExp'])) {
                $date = $_POST['dateExp'];
            }
            if (isset($_POST['initLangExp'])) {
                $initLang = $_POST['initLangExp'];
            }
            $lngs = explode(";", $_POST['langsExp']);
            $languages = array();
            foreach ($lngs as $lng) {
                $lngArr = array(
                    "lang" => $lng,
                    "name" => "",
                    "descript" => "",
                );
                if (isset($_POST['name-' . $lng])) {
                    $lngArr['name'] = $_POST['name-' . $lng];
                }
                if (isset($_POST['descri-' . $lng])) {
                    $lngArr['descript'] = $_POST['descri-' . $lng];
                }
                array_push($languages, $lngArr);
            }
            $hlp->updateExp($_POST['idElem'], $initLang, $date, $place, $languages);
        } else if ($edtInetls['type'] == "comp") {
            $name = "?";
            $percent = 0;
            $descript = "";
            if (isset($_POST['nameComp'])) {
                $name = $_POST['nameComp'];
            }
            if (isset($_POST['percentComp'])) {
                $percent = $_POST['percentComp'];
            }
            if (isset($_POST['descriptComp'])) {
                $descript = $_POST['descriptComp'];
            }
            $hlp->updateComp($_POST['idElem'], $name, $percent, $descript);
        }
        header("location:" . $rtr->getMainUrl() . "/cv");
    }
    if (isset($_POST['addExpEdt'])) {
        header("location:" . $rtr->getMainUrl() . "/cv&addElem=exp");
    }
    if (isset($_POST['addProjEdt'])) {
        header("location:" . $rtr->getMainUrl() . "/cv&addElem=proj");
    }
    if (isset($_POST['addCompEdt'])) {
        header("location:" . $rtr->getMainUrl() . "/cv&addElem=comp");
    }

    $addElemType = "";
    if (isset($_GET['addElem'])) {
        if ($_GET['addElem'] == "exp" || $_GET['addElem'] == "proj" || $_GET['addElem'] == "comp") {
            $addElemType = $_GET['addElem'];
        }
    }
    if (isset($_POST['validEditAdd'])) {
        if ($addElemType == "proj") {

        }
        if ($addElemType == "exp") {
            
        }
        if ($addElemType == "comp") {
            $name = "";
            $percent = 0;
            $description = "";
            if (isset($_POST['nameCompAddEdt'])) {
                $name = $_POST['nameCompAddEdt'];
            }
            if (isset($_POST['percentCompAddEdt'])) {
                $percent = $_POST['percentCompAddEdt'];
            }
            if (isset($_POST['descCompAddEdt'])) {
                $description = $_POST['descCompAddEdt'];
            }
            $hlp->addCompetence($name, $percent, $description);
        }
        header("location:" . $rtr->getMainUrl() . "/cv");
    }

    if (isset($_POST['discoAccount'])) {
        $hlp->disconnectUser();
        header("location:" . $rtr->getMainUrl() . "/cv");
    }
    if (isset($_POST['coAccount'])) {
        unset($_SESSION['connexionRedirect']);
        $_SESSION['connexionRedirect'] = $rtr->getMainUrl() . "/cv";
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
        ?>
            <link href="<?=$_SESSION['cssPage']?>" rel="stylesheet">
        <?php
            }
            if (isset($_SESSION['jsPage'])) {
        ?>
            <script src="<?=$_SESSION['jsPage']?>"></script>
        <?php
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
                <a href="copies"><?=$trans->getlanguage("myCopies", "My copies")?></a>
                <a href="projects"><?=$trans->getlanguage("myProjectsTitle", "My projects")?></a>
            </div>
        </div>
        <div class="mainContent">
            <div class="cvBaniereParent">
                <div class="cvBaniere" style="background-image: url('public/ressources/planet1.jpg');">

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
                                <?php
                                    if ($isAdm == true) {
                                ?>
                                    <button name="addProjEdt">Ajouter</button>
                                <?php
                                    }
                                ?>
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
                                <?php
                                    if ($isAdm == true) {
                                ?>
                                    <button name="addExpEdt">Ajouter</button>
                                <?php
                                    }
                                ?>
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
                    <div class="headerComp">
                    <h2><?=$trans->getlanguage("myCompetencesTitle")?></h2>
                    <?php
                        if ($isAdm == true) {
                    ?>
                        <button name="addCompEdt">Ajouter</button>
                    <?php
                        }
                    ?>
                    </div>
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
                header, .navBarWindows, .mainContent {
                    filter: blur(4px);
                    pointer-events: none;
                }

                .popupEdit {
                    position: absolute;
                    width: 400px;
                    left: 50%;
                    top: 10px;
                    transform: translate(-50%, 0);
                    background-color: #f2f2f2;
                    padding-top: 10px;
                    padding-bottom: 10px;
                    text-align: center;
                    z-index: 2;
                    box-shadow: 0px 1px 10px rgba(0,0,0,0.2);
                }

                .popupEdit input {
                    width: 80%;
                    margin-left: 10%;
                    margin-right: 10%;
                    height: 30px;
                }

                .langEdit {
                    width: 80%;
                    padding-top: 10px;
                    padding-bottom: 10px;
                    text-align: center;
                    margin-left: 10%;
                    margin-right: 10%;
                    margin-top: 5px;
                    margin-bottom: 5px;
                }

                .langEdit textarea {
                    width: 100%;
                    resize: vertical;
                }
            </style>
            <form class="popupEdit" method="POST">
                <h2>Edition</h2>
                <input type="number" value="<?=$edtInetls['id']?>" name="idElem" hidden>
                <?php
                    if ($edtInetls['type'] == "proj") {
                        $startDates = explode("/", $edtInetls['content']['startDate']);
                        $startDate = $startDates[2] . "-" . $startDates[1] . "-" . $startDates[0];
                        $endDates = explode("/", $edtInetls['content']['endDate']);
                        $endDate = $endDates[2] . "-" . $endDates[1] . "-" . $endDates[0];
                ?>
                    <input type="text" name="nameProjEdt" placeholder="Name project" value="<?=$edtInetls['content']['name']?>">
                    <label>Début de projet : </label><input type="date" name="startProj" value="<?=$startDate?>">
                    <label>Fin de projet : </label><input type="date" name="endProj" value="<?=$endDate?>">
                    <label>Langage de base</label><input type="text" name="initLangProj" value="<?=$edtInetls['content']['initLang']?>">
                    <?php
                        $lngProj = "";
                        foreach ($edtInetls['content']['languages'] as $language) {
                            if ($lngProj == "") {
                                $lngProj = $language;
                            } else {
                                $lngProj .= ";" . $language;
                            }
                    ?>
                        <div class="langEdit">
                            <p><?=$language?></p>
                        <?php
                            foreach ($edtInetls['content']['descriptions'] as $descri) {
                                if ($descri['lang'] == $language) {
                        ?>
                            <textarea name="descri-<?=$language?>"><?=$descri['description']?></textarea>
                        <?php
                                }
                            }
                        ?>
                        </div>
                        <input type="text" value="<?=$lngProj?>" hidden name="langsProjectEdit">
                    <?php
                        }
                    ?>
                <?php
                    } else if ($edtInetls['type'] == "exp") {
                ?>
                    <label>Place</label>
                    <input type="text" placeholder="Place..." value="<?=$edtInetls['content']['place']?>" name="placeExp">
                    <label>Dates</label>
                    <input type="text" placeholder="Date..." value="<?=$edtInetls['content']['date']?>" name="dateExp">
                    <label>Langage initial</label>
                    <input type="text" value="<?=$edtInetls['content']['initLang']?>" name="initLangExp">
                    <?php
                        $langugesStr = "";
                        foreach ($edtInetls['content']['languages'] as $language) {
                            $cName = "";
                            $cDescript = "";
                            if ($langugesStr == "") {
                                $langugesStr = $language;
                            } else {
                                $langugesStr .= ";" . $language;
                            }
                            foreach ($edtInetls['content']['name'] as $name) {
                                if ($name['lang'] == $language) {
                                    $cName = $name['name'];
                                    break;
                                }
                            }
                            foreach ($edtInetls['content']['descriptions'] as $descipt) {
                                if ($descipt['lang'] == $language) {
                                    $cDescript = $descipt['description'];
                                    break;
                                }
                            }
                    ?>
                        <div class="langEdit">
                            <h4><?=$language?></h4>
                            <label>Name</label>
                            <input type="text" name="name-<?=$language?>" value="<?=$cName?>">
                            <label>Description</label>
                            <textarea name="descri-<?=$language?>" placeholder="Description..."><?=$cDescript?></textarea>
                        </div>
                    <?php
                        }
                    ?>
                    <input hidden type="text" name="langsExp" value="<?=$langugesStr?>">
                <?php
                    } else if ($edtInetls['type'] == "comp") {
                ?>
                    <input type="text" placeholder="Competence name..." name="nameComp" value="<?=$edtInetls['content']['name']?>">
                    <input type="number" min="0" max="100" placeholder="Percent..." name="percentComp" value="<?=$edtInetls['content']['percent']?>">
                    <textarea name="descriptComp"><?=$edtInetls['content']['description']?></textarea>
                <?php
                    }
                ?>
                <button type="submit" name="validEdit">Valider</button>
                <button type="submit" name="backEdit">Retour</button>
            </form>
        <?php
            }
            if ($addElemType != "") {
        ?>
            <style>
                header, .navBarWindows, .mainContent {
                    filter: blur(4px);
                    pointer-events: none;
                }

                .popupEdit {
                    position: absolute;
                    width: 400px;
                    left: 50%;
                    top: 10px;
                    transform: translate(-50%, 0);
                    background-color: #f2f2f2;
                    padding-top: 10px;
                    padding-bottom: 10px;
                    text-align: center;
                    z-index: 2;
                    box-shadow: 0px 1px 10px rgba(0,0,0,0.2);
                }

                .popupEdit input, .langProjEditAdd input {
                    width: 80%;
                    margin-left: 10%;
                    margin-right: 10%;
                    height: 30px;
                    margin-top: 4px;
                    margin-bottom: 4px;
                }

                .langEdit {
                    width: 80%;
                    padding-top: 10px;
                    padding-bottom: 10px;
                    text-align: center;
                    margin-left: 10%;
                    margin-right: 10%;
                    margin-top: 5px;
                    margin-bottom: 5px;
                }

                .langEdit textarea, .langProjEditAdd textarea {
                    width: 100%;
                    resize: vertical;
                    margin-top: 4px;
                    margin-bottom: 4px;
                }

                .langProjEditAdd {
                    width: 90%;
                    margin-left: 5%;
                    margin-right: 5%;
                    border: 1px solid black;
                    border-radius: 5px;
                    margin-top: 5px;
                    margin-bottom: 5px;
                    padding: 5px;
                }
            </style>
            <form class="popupEdit" method="POST">
                <h2>Ajout</h2>
                <?php
                    if ($addElemType == "proj") {
                ?>
                    <h1>Add project</h1>
                    <label>Project Name</label>
                    <input type="text" placeholder="Name..." name="nameAddProj">
                    <label>Start project date</label>
                    <input type="date" placeholder="Start project date..." name="startDateAddProj">
                    <label>End project date</label>
                    <input type="date" placeholder="End project date..." name="endDateAddProj">
                    <button type="button" onclick="addProjLang()">Add Language</button>
                    <input type="number" id="cNbProjLang" name="cNbProjLang" value="0" hidden>
                    <input type="number" id="cIdProjLang" name="cIdProjLang" value="0" hidden>
                    <div id="langsProjAdd">

                    </div>
                <?php
                    } else if ($addElemType == "exp") {
                ?>
                    <h1>Add Experience</h1>
                <?php
                    } else if ($addElemType == "comp") {
                ?>
                    <input type="text" placeholder="Name..." name="nameCompAddEdt">
                    <input type="number" min="0" max="100" name="percentCompAddEdt">
                    <textarea placeholder="Description..." name="descCompAddEdt"></textarea>
                <?php
                    }
                ?>
                <button type="submit" name="validEditAdd">Valider</button>
                <button type="submit" name="backEdit">Retour</button>
            </form>
        <?php
            }
        ?>
    </body>
</html>
