<?php
    global $hlp, $ext, $db, $ep, $cf;

    $extName = "portfolioExtension";
    $confPath = $ext->getConfigFileExtension($extName);

    $country = $cf->getValueFromKeyConf($confPath, "contactIntel-country");
    $city = $cf->getValueFromKeyConf($confPath, "contactIntel-city");
    $codePoste = $cf->getValueFromKeyConf($confPath, "contactIntel-codePostal");
    $adress = $cf->getValueFromKeyConf($confPath, "contactIntel-adress");
    $email = $cf->getValueFromKeyConf($confPath, "contactIntel-email");
    $phone = $cf->getValueFromKeyConf($confPath, "contactIntel-phone");
?>


<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

<div class="portfolio-contactMe-front">
    <div class="contactMe-left">
        <form method="POST" class="formContactMe">
            <label><?=$ext->getLangaugeValue($extName, "writeUs")?></label>
            <input type="text" name="" class="nameContactForm" placeholder="<?=$ext->getLangaugeValue($extName, "nameInput")?>" required>
            <input type="email" name="" class="emailContactForm" placeholder="Email" required>
            <textarea name="" placeholder="Message" class="msgContactForm" required></textarea>
            <input type="submit" name="sendContact" class="sendFormContactMe" value="<?=$ext->getLangaugeValue($extName, "SendContact")?>">
        </form>
    </div>
    <div class="contactMe-right">
        <div class="contactIntels">
            <div class="mainContactIntels">
                <h4>Contact informations</h4>
                <p><?=$ext->getLangaugeValue($extName, "contactIntelsText")?></p>
            </div>
            <div class="othersContactIntels">
                <div class="otherIntel">
                    <p class="descriContent">
                        <span class="titleDescri"><?=$ext->getLangaugeValue($extName, "AdressTitle")?></span>
                        <span class="contentDescri"><?=$country . ", " . $adress . " " . $codePoste . " " . $city?></span>
                    </p>
                </div>
                <?php
                    if ($email != "") {
                ?> 
                <div class="otherIntel">
                    <p class="descriContent">
                        <span class="titleDescri"><?="Email : "?></span>
                        <span class="contentDescri"><?=$email?></span>
                    </p>
                </div> 
                <?php
                    }
                    if ($phone != "") {
                ?>
                <div class="otherIntel">
                    <p class="descriContent">
                        <span class="titleDescri"><?=$ext->getLangaugeValue($extName, "PhoneTitle")?></span>
                        <span class="contentDescri"><?=$phone?></span>
                    </p>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
