<?php

namespace Applications;

use Exception;

class Helpers {
    private static $errorsConnection = array(
        "Connect success !!",
        "Error while connect to database",
        "Email or password are bad",
    );

    private static $errorsCreation = array(
        0 => "Creation and connection worked !",
        1 => "Error while connect to database",
        2 => "Email already used",
        3 => "Pseudo already used",
        4 => "Creation does not worked",
        10 => "Error while connect to database while try to connect",
        20 => "Email or password are bad while try to connect",
    );

    public function __construct(){}


    private static function accountExists (string $pseudo, string $pwd, int $uid):bool {
        global $db;
        $res = false;
        $connect = $db->connect();
        if ($connect == null) {
            return $res;
        }
        $stm = $connect->prepare("SELECT * FROM account WHERE pseudo=? AND uid=?");
        $stm->execute(array($pseudo, $uid));
        $resStm = $stm->fetch();
        if ($resStm) {
            $res = password_verify($pwd, $resStm['pwd']);
        }
        $db->disconnect();
        return $res;
    }

    public static function isConnected():bool {
        if (!isset($_SESSION['uid']) || !isset($_SESSION['pseudo']) || !isset($_SESSION['pwd'])) {
            return false;
        }
        return self::accountExists($_SESSION['pseudo'], $_SESSION['pwd'], $_SESSION['uid']);
    }

    public static function disconnectUser() {
        if (self::isConnected() == false) {
            return;
        }
        unset($_SESSION['lname']);
        unset($_SESSION['pwd']);
        unset($_SESSION['uid']);
    }

    public static function connectUser(string $pseudo, string $pwd):int {
        self::disconnectUser();
        global $db;
        $connect = $db->connect();
        if ($connect == null) {
            return 1;
        }
        $stm = $connect->prepare("SELECT * FROM account WHERE pseudo=?");
        $stm->execute(array($pseudo));
        while ($resStm = $stm->fetch()) {
            if (password_verify($pwd, $resStm['pwd'])) {
                $_SESSION['pseudo'] = $resStm['pseudo'];
                $_SESSION['pwd'] = $pwd;
                $_SESSION['uid'] = $resStm['uid'];
                $db->disconnect();
                return 0;
            }
        }
        $db->disconnect();
        return 2;
    }

    private static function emailIsUSed(string $email):bool {
        global $db;
        $connect = $db->connect();
        if ($connect == null) {
            return false;
        }
        $stm = $connect->prepare("SELECT * FROM account WHERE email=?");
        $stm->execute(array($email));
        if ($stm->fetch()) {
            $db->disconnect();
            return true;
        }
        $db->disconnect();
        return false;
    }

    private static function pseudoIsUSed(string $pseudo):bool {
        global $db;
        $connect = $db->connect();
        if ($connect == null) {
            return false;
        }
        $stm = $connect->prepare("SELECT * FROM account WHERE pseudo=?");
        $stm->execute(array($pseudo));
        if ($stm->fetch()) {
            $db->disconnect();
            return true;
        }
        $db->disconnect();
        return false;
    }

    public static function createAccount(string $pseudo, string $pwd, string $email): int {
        global $db;
        $connect = $db->connect();

        if ($connect == null) {
            return 1;
        }
        if (self::emailIsUSed($email) == true) {
            return 2;
        }
        if (self::pseudoIsUSed($pseudo) == true) {
            return 3;
        }
        $hashed = password_hash($pwd, PASSWORD_DEFAULT);
        $stm = $connect->prepare("INSERT INTO account (email, pwd, pseudo) VALUES (?, ?, ?)");
        $stm->execute(array(
            $email, 
            $hashed,
            $pseudo, 
        ));
        $db->disconnect();
        if (self::emailIsUSed($email) == true) {
            $headers = 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $accountCreateEmailContent = '
                <html>
                    <head>
                        <title>Benjamin DELVERT [Création de votre compte]</title>
                        <style>
                            .allContent {
                                width: 100%;
                                position: relative;
                                text-align: center;
                            }
                            .mainDiv {
                                width: 400px;
                                padding-top: 15px;
                                padding-bottom: 15px;
                                background-color: #f2f2f2;
                                border: none;
                                border-radius: 5px;
                                
                                position: relative;
                                left: 50%;
                                top: 50%;
                                transform: translate(-50%, -50%);
                                text-align: center;
                            }
                            
                            ul {
                                margin: 0;
                                padding: 0;
                            }

                            .listingContent {
                                width: 90%;
                                text-align: left;
                                position: relative;
                                margin-left: 5%;
                                margin-right: 5%
                            }
                        </style>
                    </head>
                    <body>
                        <div class="allContent">
                        <center>
                            <div class="mainDiv" style="box-shadow: 0px 1px 10px rgba(0,0,0,0.2);">
                                <h2>Bonjour, ' . $pseudo . '</h2>
                                <p>Merci de vous être inscrit sur le site Benjamin DELVERT. Vous pourrez profiter de tous les contenus du site internet.</p>
                                <p>Vous pourrez bientôt retrouver un espace qui vous sera dédié pour votre compte et sa gestion.</p>
                                <br>
                                <h4>Contenue disponible pour le moment :</h4>
                                <ul class="listingContent">
                                    <li>Mon Portfolio</li>
                                    <li>Mon CV</li>
                                    <li>Mes copies de sites</li>
                                    <li>Mes projets</li>
                                    <li>Dispatch (site pour jeu Roleplay)</li>
                                    <li>Discuss (système de discussion entre comptes créés)</li>
                                </ul>
                            </div>
                            </center>
                        </div>
                    </body>
                </html>
                ';
            mail($email, "Benjamin DELVERT [Création compte]", $accountCreateEmailContent, $headers);
            return self::connectUser($pseudo, $pwd) * 10;
        }
        return 4;
    }

    public static function getAvailableLanguages():array {
        return array(
            array("en", "English"),
            array("fr", "Français"),
            array("de", "Deutsch"),
            array("ar", "العربية"),
        );
    }

    public static function getLangName(string $lang):string {
        $languages = self::getAvailableLanguages();
        foreach ($languages as $language) {
            if ($language[0] == $lang) {
                return $language[1];
            }
        }
        return "English";
    }

    public static function getConnectionErrors(int $id):string {
        if (key_exists($id, self::$errorsConnection)) {
            return self::$errorsConnection[$id];
        }
        return "";
    }

    public static function getAccountCreationErrors(int $id):string {
        if (key_exists($id, self::$errorsCreation)) {
            return self::$errorsCreation[$id];
        }
        return "";
    }

    public static function strToBool(string $bl):bool {
        if ($bl == "1" || $bl == "true" || $bl == "TRUE" || $bl == "True") {
            return true;
        }
        return false;
    }

    public static function getMyProjects():array {
        global $db;
        $res = array();
        $connect = $db->connect();
        if ($connect == null) {
            return $res;
        }
        $stm = $connect->prepare("SELECT * FROM mprjct WHERE 1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            $projArray = array(
                "name" => mb_convert_encoding($resStm['p_name'], "UTF-8"),
                "startDate" => mb_convert_encoding($resStm['p_start'], "UTF-8"),
                "endDate" => mb_convert_encoding($resStm['p_end'], "UTF-8"),
                "descriptions" => array(),
                "languages" => array(),
                "initLang" => "en",
                "id" => $resStm['id'],
            );
            $splitted1 = explode(";", $resStm['description']);
            foreach ($splitted1 as $lineIntel) {
                $intels = explode(":", $lineIntel, 2);
                if ($intels[0] == "initLang") {
                    $projArray['initLang'] = $intels[1];
                } else {
                    $langArr = array("lang" => $intels[0], "description" => mb_convert_encoding($intels[1], "UTF-8"));
                    array_push($projArray['languages'], $intels[0]);
                    array_push($projArray['descriptions'], $langArr);
                }
            }
            array_push($res, $projArray);
        }
        $db->disconnect();
        return $res;
    }

    public static function getMyExperiences():array {
        global $db;
        $res = array();
        $connect = $db->connect();
        if ($connect == null) {
            return $res;
        }
        $stm = $connect->prepare("SELECT * FROM mexp WHERE 1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            $projArray = array(
                "name" => array(),
                "place" => mb_convert_encoding($resStm['m_place'], "UTF-8"),
                "date" => mb_convert_encoding($resStm['m_date'], "UTF-8"),
                "descriptions" => array(),
                "languages" => array(),
                "initLang" => "en",
                "types" => mb_convert_encoding($resStm['m_type'], "UTF-8"),
                "id" => $resStm['id'],
            );
            $splitted1 = explode(";", $resStm['m_description']);
            foreach ($splitted1 as $lineIntel) {
                $intels = explode(":", $lineIntel, 2);
                if ($intels[0] == "initLang") {
                    $projArray['initLang'] = $intels[1];
                } else {
                    $langArr = array("lang" => $intels[0], "description" => mb_convert_encoding($intels[1], "UTF-8"));
                    array_push($projArray['languages'], mb_convert_encoding($intels[0], "UTF-8"));
                    array_push($projArray['descriptions'], $langArr);
                }
            };
            $splitted1 = explode(";", $resStm['m_name']);
            foreach ($splitted1 as $name) {
                $intelName = explode(":", $name, 2);
                array_push($projArray['name'], array("lang" => $intelName[0], "name" => mb_convert_encoding($intelName[1], "UTF-8")));
            }
            array_push($res, $projArray);
        }
        $db->disconnect();
        return $res;
    }

    public static function getMyCompetences():array {
        global $db;
        $res = array();
        $connect = $db->connect();
        if ($connect == null) {
            return $res;
        }
        $stm = $connect->prepare("SELECT * FROM mcomp WHERE 1 ORDER BY percent DESC");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function isAdm():bool {
        global $db;

        $res = false;
        if (self::isConnected() == false) {
            return $res;
        }
        $connect = $db->connect();
        if ($connect == null) {
            return $res;
        }
        $stm = $connect->prepare("SELECT isAdm FROM account WHERE uid=? AND pseudo=?");
        $stm->execute(array($_SESSION['uid'], $_SESSION['pseudo']));
        $resStm = $stm->fetch();
        if ($resStm) {
            if ($resStm['isAdm'] == 1) {
                $res = true;
            }
        }
        $db->disconnect();
        return $res;
    }

    public static function updateProj(string $name, int $id, string $initLang, array $languages=array(), string $startDate = "", string $endDate = "") {
        global $db;
        if (self::isAdm() == false) {
            return;
        }
        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        $description = "initLang:" . $initLang;
        foreach ($languages as $language) {
            $description .= ";" . $language['lang'] . ":" . $language['descript'];
        }
        $stm = $connect->prepare("UPDATE mprjct SET p_name=?, p_start=?, p_end=?, description=? WHERE id=?");
        $stm->execute(array(
            $name,
            $startDate,
            $endDate,
            $description,
            $id
        ));
        $db->disconnect();
    }

    public static function updateExp(int $id, string $initLang, string $date, string $place, array $languages = array()) {
        global $db;
        if (self::isAdm() == false) {
            return;
        }
        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        $name = "";
        $description = "initLang:" . $initLang;
        foreach ($languages as $language) {
            $description .= ";" . $language['lang'] . ":" . $language['descript'];
            if ($name == "") {
                $name = $language['lang'] . ":" . $language['name'];
            } else {
                $name .= ";" . $language['lang'] . ":" . $language['name'];
            }
        }
        $stm = $connect->prepare("UPDATE mexp SET m_name=?, m_date=?, m_place=?, m_description=? WHERE id=?");
        $stm->execute(array(
            $name,
            $date,
            $place,
            $description,
            $id
        ));
        $db->disconnect();
    }

    public static function deleteExpCompProj(int $id, string $type="") {
        global $db;
        if (self::isAdm() == false) {
            return;
        }
        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        if ($type == "proj") {
            $stm = $connect->prepare("DELETE FROM mprjct WHERE id=?");
            $stm->execute(array($id));
        } else if ($type == "exp") {
            $stm = $connect->prepare("DELETE FROM mexp WHERE id=?");
            $stm->execute(array($id));
        } else if ($type = "comp") {
            $stm = $connect->prepare("DELETE FROM mcomp WHERE id=?");
            $stm->execute(array($id));
        }
        $db->disconnect();
    }
}
