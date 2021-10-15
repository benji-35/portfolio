<?php
    global $hlp, $ext, $db, $ep, $cf;

    $pathConf = $ext->getConfigFileExtension("portfolioExtension");

    $projectsListing = explode(",", $cf->getValueFromKeyConf($pathConf, "projects"));

    $projectsDisplaying = "";
    for ($i = 0; $i < count($projectsListing); $i++) {
        if ($projectsListing[$i] != "") {
            $projectsDisplaying .= $projectsListing[$i] . "<br>";
        }
    }
?>
<div class="portfolio-projects-front">
    <div class="portfolio-projects-content">
        <?php
            for ($i = 0; $i < count($projectsListing); $i++) {
                $pathImg = $hlp->getMainUrl() . "/" . $ext->getMainPathExtension("portfolioExtension") . "/ressources/" . $cf->getValueFromKeyConf($pathConf, $projectsListing[$i] . "-img");
        ?>
            <div class="card">
                <img src="<?=$pathImg?>" alt="Denim Jeans" style="width:100%; height: 140px">
                <h1><?=$projectsListing[$i]?></h1>
                <p class="descript-portfolioProject"><?=$cf->getValueFromKeyConf($pathConf, $projectsListing[$i] . "-description")?></p>
                <p class="btn-bottom-portfolioProject"><a class="redirectProject-portfolio" target="_blank" href="<?=$cf->getValueFromKeyConf($pathConf, $projectsListing[$i] . "-link")?>">voir le projet</a></p>
            </div> 
        <?php
            }
        ?>
    </div>
</div>
