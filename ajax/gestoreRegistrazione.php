<?php
    require_once "../class/Database.php";

    if(!isset($_GET["user"], $_GET["pass"], $_GET["email"])) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Credenziali mancanti!";
        echo json_encode($ret);
        die();
    }

    $db = Database::getInstance();
    $username = $_GET["user"];
    $email = $_GET["email"];
    $password = md5($_GET["pass"]);

    if(!$db->doRegistrazione($username, $password, $email)) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Errore durante la creazione dell'utente";
        echo json_encode($ret);
        die();
    }

    $ret = [];
    $ret["status"] = "OK";
    $ret["msg"] = "";
    echo json_encode($ret);
    die(); 
?>