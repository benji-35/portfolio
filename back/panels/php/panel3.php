<?php
    global $ext, $hlp, $cf, $ep;

    if (isset($_POST['sendNewProject-portfolioExtension'])) {
        $confPath = $ext->getConfigFileExtension("portfolioExtension");
        $currProjects = $cf->getValueFromKeyConf($confPath, "projects");
        $nameProject = $_POST['nameProject-portfolioExtension'];
        $toAdd = "";
        if ($currProjects == "") {
            $currProjects = $nameProject;
        } else {
            $currProjects .= "," . $nameProject;
        }
        $cf->addValueFormKeyConf($confPath, "projects", $currProjects);
        $cf->addValueFormKeyConf($confPath, $nameProject . "-description", $_POST['descriptionProject-portfolioExtension']);

        if (isset($_POST['linkProject-portfolioExtension'])) {
            $cf->addValueFormKeyConf($confPath, $nameProject . "-link", $_POST['linkProject-portfolioExtension']);
        }
    }
?>
