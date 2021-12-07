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
            return self::connectUser($pseudo, $email) * 10;
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
}
