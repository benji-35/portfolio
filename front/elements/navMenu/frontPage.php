<?php
    global $hlp, $ext, $db, $ep;

    $extName = "portfolioExtension";
    if (!isset($_SESSION['language'])) {
        $_SESSION['language'] = "en";
    }
    if (isset($_POST['portfolioExtension-langChange'])) {
        $_SESSION['language'] = $_POST['portfolioExtension-langChange'];
    }

    $mainPathExtension = $ext->getExtensionFromListByName($extName)['path'];
?>

<div class="portfolio-navMenu-front">
    <div class="portfolio-navMenu-front-left">
        <a id="logoNavMenu" href="<?=$hlp->getMainUrl()?>">
            <div class="logoTurn">BD</div>
        </a>
    </div>
    <div class="portfolio-navMenu-front-right">
        <form method="POST" class="portfolioExtension-formChgLang">
            <select title="<?=$ext->getLangaugeValue($extName, "selectLangTitle")?>" onchange="this.form.submit()" id="displayFlag" name="portfolioExtension-langChange">
                <?php
                    if ($_SESSION['language'] == "en") {
                ?>
                    <option hidden selected value="en">EN</option>
                <?php
                    } else if ($_SESSION['language'] == "fr") {
                ?>
                    <option hidden selected value="fr">FR</option>
                <?php
                    }
                ?>
                <option value="en">EN</option>
                <option value="fr">FR</option>
            </select>
            <input type="submit" hidden>
        </form>
        <a class="btnNavMenu-front" href="<?=$hlp->getMainUrl()?>"><?=$ext->getLangaugeValue($extName, "PresButton")?></a>
        <a class="btnNavMenu-front" href="<?=$hlp->getMainUrl() . "/projects"?>"><?=$ext->getLangaugeValue($extName, "ProjectButton")?></a>
        <a class="btnNavMenu-front" href="<?=$hlp->getMainUrl() . "/contactMe"?>"><?=$ext->getLangaugeValue($extName, "ContactBtn")?></a>
    </div>
</div>
