<?php

namespace Applications;

class Dispatch {
    public function __construct() {}

    private static $dispatchInterface = "";
    private static $dispatchEditInterface = "";
    private static $responderInterface = "<div class=\"responderInterface\">
        <div class=\"\"></div>
    </div>";
    private static $callSignMenu = "";
    //display all available calls
    private static $callInterface = "<div class=\"callInterface\"></div>";
    //display the call menu to alert
    private static $callMenu = "";

    private static function generateSid(int $time):string {
        $availableChars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str_time = "" . $time;
        $lengthTime = strlen($str_time);
        $lengthAvail = strlen($availableChars);
        for ($i = $lengthTime; $i < 19; $i++) {
            $str_time = "0" . $str_time;
        }
        for ($i = 0; $i < 11; $i++) {
            $str_time .= $availableChars[rand(0, ($lengthAvail - 1))];
        }
        return $str_time;
    }

    private static function generateInviteLinkString():string {
        global $db;
        $res = "";
        $availableChars = "abcdefghijklmnopqrstuvwxyz0123456789ABSDEFGHIJKLMNOPQRSTUVWXYZ";
        $size = strlen($availableChars);
        for ($i = 0; $i < 4; $i++) {
            $tmpChars = "";
            for ($x = 0; $x < 12; $x++) {
                $tmpChars .= $availableChars[rand(0, ($size - 1))];
            }
            if ($res == "") {
                $res = $tmpChars;
            } else {
                $res .= "-" . $tmpChars;
            }
        }
        $connect = $db->connect();
        if ($connect == null) {
            return $res;
        }
        $stm = $connect->prepare("SELECT * FROM invitelinkdispatch WHERE link=?");
        $stm->execute(array($res));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            $res = self::generateInviteLinkString();
        }
        return $res;
    }

    private static function formatArrayToSqlTeams(array $teams):string {
        $res = "";
        foreach ($teams as $team) {
            $cCall = "0";
            $cDisp = "0";
            $iResp = "0";
            $needCallSign = "0";
            if ($team['cCall'] == true) {
                $cCall = "1";
            }
            if ($team['cDisp'] == true) {
                $cDisp = "1";
            }
            if ($team['iResp'] == true) {
                $iResp = "1";
            }
            if ($team['ndClS'] == true) {
                $needCallSign = "1";
            }
            $strTeam = $team['tname'] . ":" . $cCall . ":" . $cDisp . ":" . $iResp . ":" . $needCallSign;
            if ($res == "") {
                $res = $strTeam;
            } else {
                $res .= ";" . $strTeam;
            }
        }
        return $res;
    }

    public static function createDispatchServer(string $name, array $teams, string $description="", string $pwd="") {
        global $hlp, $db;

        if ($hlp->isConnected() == false) {
            return;
        }
        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        if ($pwd != "") {
            $pwd = password_hash($pwd, PASSWORD_DEFAULT);
        }
        $ctime = time();
        $sid = self::generateSid($ctime);
        $nbTeams = count($teams);
        $teamFormat = self::formatArrayToSqlTeams($teams);
        $stm = $connect->prepare("INSERT INTO dispatchserver (sid, s_name, s_description, s_nteams, s_teams, subscribers, cr_date, ls_upt, onlineUnits, uid_cr, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stm->execute(array(
            $sid,
            $name,
            "",
            $nbTeams,
            $teamFormat,
            "",
            $ctime,
            $ctime,
            "",
            $_SESSION['uid'],
            $pwd,
        ));
        $db->disconnect();
    }

    public static function connectToDisptahcServer(string $sid) {
        global $db;
        self::disconnectToDispatchServer();

        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        $stm = $connect->prepare("SELECT * FROM dispatchserver WHERE sid=?");
        $stm->execute(array($sid));
        $resStm = $stm->fetch();
        if ($resStm) {
            if (self::isDispatchServerThatCanGetAccess($resStm)) {
                $_SESSION['dispConnect'] = $sid;
            }
        }
        $db->disconnect();
    }

    public static function disconnectToDispatchServer() {
        if (isset($_SESSION['dispConnect'])) {
            unset($_SESSION['dispConnect']);
        }
        if (isset($_SESSION['cTeam'])) {
            unset($_SESSION['cTeam']);
        }
    }

    public static function getDispatchServerTeams():array {
        global $db, $hlp;
        $res = array();
        if (self::isConnectToDispatchServer() == false) {
            return $res;
        }
        $sid = $_SESSION['dispConnect'];
        $connect = $db->connect();
        if ($connect == null) {
            return $res;
        }
        $stm = $connect->prepare("SELECT * FROM dispatchserver WHERE sid=?");
        $stm->execute(array($sid));
        $resStm = $stm->fetch();
        if ($resStm) {
            $teamsArray = $resStm['s_teams'];
            if ($teamsArray != "") {
                $teams = explode(";", $teamsArray);
                foreach ($teams as $team) {
                    $teamIntels = explode(":", $team);
                    $teamArray = array(
                        "name" => $teamIntels[0],
                        "cCall" => $hlp->strToBool($teamIntels[1]),
                        "cDisp" => $hlp->strToBool($teamIntels[2]),
                        "iResp" => $hlp->strToBool($teamIntels[3]),
                        "ndClS" => $hlp->strToBool($teamIntels[4]),
                    );
                    array_push($res, $teamArray);
                }
            }
        }
        $db->disconnect();
        return $res;
    }

    public static function isConnectToDispatchServer() {
        global $db;
        $res = false;
        if (!isset($_SESSION['dispConnect'])) {
            return $res;
        }
        $connect = $db->connect();
        if ($connect == null) {
            return $res;
        }
        $stm = $connect->prepare("SELECT * FROM dispatchserver WHERE sid=?");
        $stm->execute(array($_SESSION['dispConnect']));
        $resStm = $stm->fetch();
        if ($resStm) {
            $res = self::isDispatchServerThatCanGetAccess($resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function getNavBarDispatchServer():string {
        global $db;

        $res = "";
        if (self::isConnectToDispatchServer() == false) {
            return $res;
        }
        $res .= '<button type="submit" name="changeTeam" class="disconnectButton">Change team</button>';
        $res .= '<button type="submit" name="disconnectServer" class="disconnectButton">Disconnect</button>';
        return $res;
    }

    public static function getBoxContainerDispatchServer():string {
        global $db;

        $res = "";
        if (self::isConnectToDispatchServer() == false) {
            return $res;
        }
        if (!isset($_SESSION['cTeam'])) {
            return $res;
        }
        $intels = self::getServerIntels($_SESSION['dispConnect']);
        $serverTeams = self::getDispatchServerTeams();
        $serverTeams = array($serverTeams[$_SESSION['cTeam']]);
        $splitScreen = false;
        if ($serverTeams[0]['iResp'] == true || $serverTeams[0]['cDisp'] == true) {
            $splitScreen = true;
        }
        $rightIntels = "";
        $leftIntels = "";
        if ($serverTeams[0]['iResp'] == true && $serverTeams[0]['cDisp'] == false) {
            $rightIntels .= self::$dispatchInterface;
        } else if ($serverTeams[0]['iResp'] == false && $serverTeams[0]['cDisp'] == true) {
            $rightIntels .= self::$dispatchEditInterface;
        } else if ($serverTeams[0]['cDisp'] == true && $serverTeams[0]['iResp'] == true) {
            $rightIntels .= self::$dispatchEditInterface;
            $rightIntels .= self::$dispatchInterface;
        }
        if ($serverTeams[0]['iResp'] == true || $serverTeams[0]['cDisp'] == true) {
            $leftIntels .= self::$callInterface;
        }
        if ($serverTeams[0]['ndClS'] == true) {
            $leftIntels .= self::$callSignMenu;
        }
        if ($serverTeams[0]['cCall'] == true) {
            $leftIntels .= self::$callMenu;
        }
        if ($splitScreen) {
            $res .= "<div class=\"splitScreen\"><div class=\"leftSplitScreen\">$leftIntels</div><div class=\"rightSplitScreen\">$rightIntels</div></div>";
        } else {
            $res .= "<div class=\"monoScreen\">$leftIntels</div>";
        }
        return $res;
    }

    public static function getDispatchServersList():array {
        global $db;
        $res = array();

        $connect = $db->connect();
        if ($connect == null) {
            return $res;
        }
        $stm = $connect->prepare("SELECT * FROM dispatchserver WHERE 1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function getDispatchServersListSubscribe():array {
        global $hlp;
        $res = array();
        if ($hlp->isConnected() == false) {
            return $res;
        }
        $listing = self::getDispatchServersList();
        foreach ($listing as $server) {
            if ($server['uid_cr'] == $_SESSION['uid']) {
                array_push($res, $server);
            } else {
                $subs = explode(";", $server['subscribers']);
                foreach ($subs as $sub) {
                    if ($sub == $_SESSION['uid']) {
                        array_push($res, $server);
                    }
                }
            }
        }
        return $res;
    }

    private static function isDispatchServerThatCanGetAccess(array $server) {
        if ($server['uid_cr'] == $_SESSION['uid']) {
            return true;
        } else {
            $subs = explode(";", $server['subscribers']);
            foreach ($subs as $sub) {
                if ($sub == $_SESSION['uid']) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function getServerIntels(string $sid) {
        global $db;
        $res = array();
        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        $stm = $connect->prepare("SELECT * FROM dispatchserver WHERE sid=?");
        $stm->execute(array($sid));
        $resStm = $stm->fetch();
        if ($resStm) {
            $res = $resStm;
        }
        $db->disconnect();
        return $res;
    }

    public static function deleteCurrServer() {
        global $db;

        if (self::isConnectToDispatchServer() == false) {
            return;
        }
        $intels = self::getServerIntels($_SESSION['dispConnect']);
        if ($_SESSION['uid'] != $intels['uid_cr']) {
            return;
        }
        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        $stm = $connect->prepare("DELETE FROM dispatchserver WHERE sid=?");
        $stm->execute(array($_SESSION['dispConnect']));
        $db->disconnect();
        self::disconnectToDispatchServer();
    }

    public static function generateInviteLink() {
        global $db;
        if (self::isConnectToDispatchServer() == false) {
            return;
        }
        if (isset($_SESSION['displayInviteLink'])) {
            unset($_SESSION['displayInviteLink']);
        }
        $nlink = self::generateInviteLinkString();
        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        $stm = $connect->prepare("INSERT INTO invitelinkdispatch (link, sid) VALUES (?, ?)");
        $stm->execute(array(
            $nlink,
            $_SESSION['dispConnect']
        ));
        $db->disconnect();
        $_SESSION['displayInviteLink'] = $nlink;

    }

    public static function joinServer(string $link) {
        global $db, $hlp;

        if ($hlp->isConnected() == false) {
            return;
        }
        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        $stm = $connect->prepare("SELECT * FROM invitelinkdispatch WHERE link=?");
        $stm->execute(array($link));
        $resStm = $stm->fetch();
        if ($resStm) {
            $stm2 = $connect->prepare("SELECT * FROM dispatchserver WHERE sid=?");
            $stm2->execute(array($resStm['sid']));
            $resStm = $stm2->fetch();
            if ($resStm) {
                $nPerson = $resStm['subscribers'];
                if ($nPerson == "") {
                    $nPerson = $_SESSION['uid'];
                } else {
                    $nPerson .= ";" . $_SESSION['uid'];
                }
                $stm3 = $connect->prepare("UPDATE dispatchserver SET subscribers=? WHERE sid=?");
                $stm3->execute(array($nPerson, $resStm['sid']));
            }
        }
        $db->disconnect();
    }

    public static function addTeamInCurrServer (array $team) {
        global $db;

        if (self::isConnectToDispatchServer() == false) {
            return;
        }
        $servIntels = self::getServerIntels($_SESSION['dispConnect']);
        if ($servIntels['uid_cr'] != $_SESSION['uid']) {
            return;
        }
        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        $n_teamList = $servIntels['s_teams'];
        if ($n_teamList != "") {
            $n_teamList .= ";" . self::formatArrayToSqlTeams($team);
        } else {
            $n_teamList = self::formatArrayToSqlTeams($team);
        }
        $n_nteam = $servIntels['s_nteams'] + 1;
        $stm = $connect->prepare("UPDATE dispatchserver SET s_teams=?, s_nteams=? WHERE sid=?");
        $stm->execute(array(
            $n_teamList,
            $n_nteam,
            $servIntels['sid'],
        ));
        $db->disconnect();
    }

    public static function deleteTeamInCurrServer (string $teamName) {
        global $db;

        if (self::isConnectToDispatchServer() == false) {
            return;
        }
        $servIntels = self::getServerIntels($_SESSION['dispConnect']);
        if ($servIntels['uid_cr'] != $_SESSION['uid']) {
            return;
        }
        $connect = $db->connect();
        if ($connect == null) {
            return;
        }
        $teamList = $servIntels['s_teams'];
        $n_nteam = $servIntels['s_nteams'];
        $n_teamList = "";

        $teams = explode(";", $teamList);
        foreach ($teams as $team) {
            $intelsTeam = explode(":", $team, 2);
            if ($intelsTeam[0] != $teamName) {
                if ($n_teamList == "") {
                    $n_teamList = $team;
                } else {
                    $n_teamList .= ";" . $team;
                }
            } else {
                $n_nteam--;
            }
        }

        $stm = $connect->prepare("UPDATE dispatchserver SET s_teams=?, s_nteams=? WHERE sid=?");
        $stm->execute(array(
            $n_teamList,
            $n_nteam,
            $servIntels['sid'],
        ));
        $db->disconnect();
    }
}
