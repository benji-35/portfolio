<?php
    global $db, $ext, $hlp, $ep, $cf;

    $pathConfig = $ext->getConfigFileExtension("portfolioExtension");
    $ui_config = $ext->getManagerUiExtension("portfolioExtension");

    $idPanel3 = $cf->getValueFromKeyConf($ui_config, "manager-ui-pannel3-id");

    $projectsListing = explode(",", $cf->getValueFromKeyConf($pathConfig, "projects"));
    $nb_projects = count($projectsListing);

    function portfolioExtension_deleteProjectByName(string $nameProject, int $size_projects, array $listProject, $cf, string $pathConf) {
        global $hlp;
        $n_projectStrList = "";
        for ($i = 0; $i < $size_projects; $i++) {
            if ($listProject[$i] != "") {
                if ($listProject[$i] != $nameProject) {
                    if ($n_projectStrList == "") {
                        $n_projectStrList = $listProject[$i];
                    } else {
                        $n_projectStrList .= "," . $listProject[$i];
                    }
                }
            }
        }
        if ($n_projectStrList == "") {
            $cf->deleteVariableByKey($pathConf, "projects");
        } else {
            $cf->addValueFormKeyConf($pathConf, "projects", $n_projectStrList);
        }
        $cf->deleteVariableByKey($pathConf, $nameProject . "-link");
        $cf->deleteVariableByKey($pathConf, $nameProject . "-description");
    }

    $tableContentRes = "";
    for ($idProject = 0; $idProject < $nb_projects; $idProject++) {
        if ($projectsListing[$idProject] != "") {
            $tableContentRes .= "<tr>";
            $tableContentRes .= "<td>" . $projectsListing[$idProject] . "</td>";
            $tableContentRes .= "<td>" . $cf->getValueFromKeyConf($pathConfig, $projectsListing[$idProject] . "-link") . "</td>";
            $tableContentRes .= "<td>" . $cf->getValueFromKeyConf($pathConfig, $projectsListing[$idProject] . "-description") . "</td>";
            $tableContentRes .= "<td>"
                . '<form method="POST">'
                . '<input type="submit" value="Supprimer" name="portfolioExtension-delete-' . $projectsListing[$idProject] . '">'
                . "</form>"
                . "</td>";
            $tableContentRes .= "</tr>";
            if (isset($_POST['portfolioExtension-delete-' . $projectsListing[$idProject]])) {
                portfolioExtension_deleteProjectByName($projectsListing[$idProject], $nb_projects, $projectsListing, $cf, $pathConfig);
            }
        }
    }

?>
<div class="contextDev" id="<?=$idPanel3?>">
    <div class="portfolio-main">
        <h1>Mes Projets</h1>
        <table id="tableProjectListing-portfolioExtension">
            <caption>Listing of projects</caption>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Link</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?=$tableContentRes?>
            </tbody>
        </table>
        <form method="POST" id="addNProject-portfolioExtension">
            <h2>Créer un projet</h2>
            <input type="text" name="nameProject-portfolioExtension" placeholder="Nom du projet..." required>
            <input type="text" name="linkProject-portfolioExtension" placeholder="Lien du projet...">
            <textarea name="descriptionProject-portfolioExtension" placeholder="Description du projet..." required></textarea>
            <input type="submit" value="Ajouter" name="sendNewProject-portfolioExtension">
        </form>
    </div>
</div>
