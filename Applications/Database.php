<?php


namespace Applications;


use PDO;
use PDOException;

class Database
{
    /*private static $dbHost = "localhost";
    private static $dbName = "id17853298_snsmgestion";
    private static $dbUsername = "id17853298_snsmroot";
    private static $dbPassword = "W+_2_r]\$ug(NbF)]";*/

    private static $dbHost = "localhost";
    private static $dbName = "portfolio";
    private static $dbUsername = "root";
    private static $dbPassword = "";

    private static $connection = null;

    public function __construct()
    {
    }

    public static function connect() {
        if (self::$connection == null) {
            try {
                self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName, self::$dbUsername, self::$dbPassword);
            } catch (PDOException $e) {
                echo $e->getMessage();
                die($e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function disconnect() {
        self::$connection = null;
    }

    public static function sendNotif($to, $connect, $header, $msg) {
        $stm = $connect->prepare("INSERT INTO notifs(idTarget, header, msg)".
        " VALUES (?, ?, ?)");
        $stm->execute(array($to, $header, $msg));
    }

    public static function str_contains($str, $check):bool {
        $idCheck = 0;
        if (strlen($check) > strlen($str))
            return false;
        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] == $check[$idCheck]) {
                $idCheck++;
            } else {
                if ($idCheck > 0)
                    $idCheck = 0;
            }
            if ($idCheck == strlen($check))
                return true;
        }
        return false;
    }
}
?>