<?php
    require_once "../class/Database.php";

    if(!isset($_GET["user"], $_GET["pass"])) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Credenziali mancanti!";
        echo json_encode($ret);
        die();
    }

    $username = $_GET["user"];
    $password = md5($_GET["pass"]);

    $db = Database::getInstance();
    if(!$db->doLogin($username, $password)) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Username o password errati!";
        echo json_encode($ret);
        die();
    }

    $ret = [];
    $ret["status"] = "OK";
    $ret["msg"] = "";
    echo json_encode($ret);
    die();
?>