<?php
    global $db, $ext, $hlp, $ep, $cf;

    $extName = "portfolioExtension";
    $idPanel = $cf->getValueFromKeyConf($ext->getManagerUiExtension($extName), "manager-ui-pannel1-id");
    $configPathExtension = $ext->getConfigFileExtension($extName);

    if (isset($_POST['sendContactMe-portfolioExtension'])) {
        $confPath = $ext->getConfigFileExtension($extName);
        $confLangFr = $ext->getLanguageConfPath($extName, "fr");
        $confLangEN = $ext->getLanguageConfPath($extName, "en");

        $countryInput = " ";
        if (isset($_POST['countryAdress-contactMe-portfolioExtension'])) {
            $countryInput = $_POST['countryAdress-contactMe-portfolioExtension'];
        }
        $cityInput = " ";
        if (isset($_POST['cityAdress-contactMe-portfolioExtension'])) {
            $cityInput = $_POST['cityAdress-contactMe-portfolioExtension'];
        }
        $cpInput = " ";
        if (isset($_POST['codepostalAdress-contactMe-portfolioExtension'])) {
            $cpInput = $_POST['codepostalAdress-contactMe-portfolioExtension'];
        }
        $adress_input = " ";
        if (isset($_POST['adresseAdress-contactMe-portfolioExtension'])) {
            $adress_input = $_POST['adresseAdress-contactMe-portfolioExtension'];
        }
        $mainIntleFr = " ";
        if (isset($_POST['contactIntelsMain-portfolioExtension-fr'])) {
            $mainIntleFr = $_POST['contactIntelsMain-portfolioExtension-fr'];
        }
        $mainIntelEn = " ";
        if (isset($_POST['contactIntelsMain-portfolioExtension-en'])) {
            $mainIntelEn = $_POST['contactIntelsMain-portfolioExtension-en'];
        }
        $emailInput = " ";
        if (isset($_POST['email-contactMe-portfolioExtension'])) {
            $emailInput = $_POST['email-contactMe-portfolioExtension'];
        }
        $phoneINput = " ";
        if (isset($_POST['tel-contactMe-portfolioExtension'])) {
            $phoneINput = $_POST['tel-contactMe-portfolioExtension'];
        }

        $ext->updateLangValue($extName, "contactIntelsText", "fr", $mainIntleFr);
        $ext->updateLangValue($extName, "contactIntelsText", "en", $mainIntelEn);
        $cf->addValueFormKeyConf($confPath, "contactIntel-country", $countryInput);
        $cf->addValueFormKeyConf($confPath, "contactIntel-city", $cityInput);
        $cf->addValueFormKeyConf($confPath, "contactIntel-codePostal", $cpInput);
        $cf->addValueFormKeyConf($confPath, "contactIntel-adress", $adress_input);
        $cf->addValueFormKeyConf($confPath, "contactIntel-email", $emailInput);
        $cf->addValueFormKeyConf($confPath, "contactIntel-phone", $phoneINput);
    }
?>
<div class="contextDev" id="<?=$idPanel?>">
    <div class="portfolio-main">
        <h1>Portfolio Manager</h1>
        <form method="POST" class="contactMeIntels">
            <h2>Me contacter</h2>
            <div class="contactMeIntel-portfolioExtension">
                <h3>Informations Générales</h3>
                <h5>Français</h5>
                <textarea name="contactIntelsMain-portfolioExtension-fr" placeholder="Informations générales..."><?=$ext->getLangaugeValueFromLang($extName, "contactIntelsText", "fr")?></textarea>
                <h5>English</h5>
                <textarea name="contactIntelsMain-portfolioExtension-en" placeholder="General information..."><?=$ext->getLangaugeValueFromLang($extName, "contactIntelsText", "en")?></textarea>
            </div>
            <div class="contactMeIntel-portfolioExtension">
                <h3>Adresse</h3>
                <input type="text" name="countryAdress-contactMe-portfolioExtension" placeholder="Pays..." value="<?=$cf->getValueFromKeyConf($configPathExtension, "contactIntel-country")?>">
                <input type="text" name="cityAdress-contactMe-portfolioExtension" placeholder="Ville..." value="<?=$cf->getValueFromKeyConf($configPathExtension, "contactIntel-city")?>">
                <input type="text" name="codepostalAdress-contactMe-portfolioExtension" placeholder="Code postal..." value="<?=$cf->getValueFromKeyConf($configPathExtension, "contactIntel-codePostal")?>">
                <input type="text" name="adresseAdress-contactMe-portfolioExtension" placeholder="Adresse..." value="<?=$cf->getValueFromKeyConf($configPathExtension, "contactIntel-adress")?>">
            </div>
            <div class="contactMeIntel-portfolioExtension">
                <h3>Email</h3>
                <input type="email" name="email-contactMe-portfolioExtension" placeholder="Email..." value="<?=$cf->getValueFromKeyConf($configPathExtension, "contactIntel-email")?>">
            </div>
            <div class="contactMeIntel-portfolioExtension">
                <h3>Téléphone</h3>
                <input type="text" name="tel-contactMe-portfolioExtension" placeholder="Téléphone..." value="<?=$cf->getValueFromKeyConf($configPathExtension, "contactIntel-phone")?>">
            </div>
            <input type="submit" value="Enregistrer" name="sendContactMe-portfolioExtension">
        </form>
    </div>
</div>