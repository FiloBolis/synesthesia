<?php
    require_once "../class/Database.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    // Verifica che l'utente sia loggato
    if (!isset($_SESSION["user"])) {
        header("location: ../index.php");
        exit;
    }
    
    if(!isset($_GET["id"], $_GET["user"], $_GET["email"], $_GET["bio"], $_GET["genere"], $_GET["stile"])) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Parametri mancanti!";
        echo json_encode($ret);
        die();
    }

    $id = $_GET["id"];
    $username = $_GET["user"];
    $email = $_GET["email"];
    $bio = $_GET["bio"];
    $genere = $_GET["genere"];
    $stile = $_GET["stile"];

    $db = Database::getInstance();
    
    if(!$db->editProfilo($id, $username, $email, $bio, $genere, $stile)) {
        $ret = [];
        $ret["status"] = "UGUALE";
        $ret["msg"] = "";
        echo json_encode($ret);
        die();
    }

    $ret = [];
    $ret["status"] = "OK";
    $ret["msg"] = "Utente modificato con successo!";
    echo json_encode($ret);
    die();
?>