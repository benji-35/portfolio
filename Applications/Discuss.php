<?php

namespace Applications;

class Discuss {
    public function __construct() {}

    public static function getMessagesDiscussion(int $idDiscuss) {
        global $hlp, $db;
        $res = array(
            "errorMsg" => "",
        );

        if ($hlp->isConnected() == false) {
            $res['errorMsg'] = "You are not connected";
            return $res;
        }
        $connect = $db->connect();
        if ($connect == null) {
            $res['errorMsg'] = "Error while try to connect to msg server";
            return $res;
        }
        $stm = $connect->prepare("SELECT * FROM message WHERE id_discuss=? ORDER BY cr_date ASC");
        $stm->execute(array($idDiscuss));
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        if (count($res) <= 1) {
            $res['errorMsg'] = "Vous n'avez de message dans cette discussion";
        }
        return $res;
    }

    public static function getMyDiscussions() {
        global $hlp, $db;
        $res = array(
            "errorMsg" => "",
        );

        if ($hlp->isConnected() == false) {
            $res['errorMsg'] = "You are not connected";
            return $res;
        }
        $connect = $db->connect();
        if ($connect == null) {
            $res['errorMsg'] = "Error while try to connect to msg server";
            return $res;
        }
        $stm = $connect->prepare("SELECT * FROM discussion WHERE 1");
        $stm->execute(array());
        while ($resStm = $stm->fetch()) {
            $exp = explode(",", $resStm['participants']);
            foreach ($exp as $idTry) {
                if ($idTry == $_SESSION['uid']) {
                    $arrTarget = array(
                        "id" => $resStm['id'],
                        "title" => $resStm['name'],
                    );
                    array_push($res, $arrTarget);
                    break;
                }
            }
        }
        $db->disconnect();
        if (count($res) <= 1) {
            $res['errorMsg'] = "Vous n'avez pas de discussions. Faites vous des amies";
        }
        return $res;
    }

    public static function sendMessageDiscuss(int $idDiscuss, string $msg) {
        global $db;

        $mDiscussions = self::getMyDiscussions();
        $autorizes = false;

        if (count($mDiscussions) <= 1) {
            return;
        }
        unset($mDiscussions['errorMsg']);
        foreach ($mDiscussions as $discussion) {
            if ($discussion['id'] == $idDiscuss) {
                $autorizes = true;
                break;
            }
        }
        if ($autorizes == false) {
            return;
        }
        $cTime = time();
        $sender = $_SESSION['uid'];
        $connect = $db->connect();
        $idMsg = "snd" . $idDiscuss . "MsgAt" . $cTime . "-" . $sender;
        if ($connect == null) {
            return;
        }
        $stm = $connect->prepare("INSERT INTO message (id_discuss, cr_date, msg, sender, idMsg) VALUES (?, ?, ?, ?, ?)");
        $stm->execute(array(
            $idDiscuss,
            $cTime,
            $msg,
            $sender,
            $idMsg
        ));
        $db->disconnect();        
    }
}

?>
