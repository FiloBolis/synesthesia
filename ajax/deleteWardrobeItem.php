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

    if(!isset($_GET["id"])) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "ID del capo mancante!";
        echo json_encode($ret);
        die();
    }

    $id = $_GET["id"];
    $db = Database::getInstance();

    if(!$db->deleteCapo($id)) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Errore durante l'eliminazione del capo!";
        echo json_encode($ret);
        die();
    }

    $ret = [];
    $ret["status"] = "OK";
    $ret["msg"] = "Capo eliminato con successo!";
    echo json_encode($ret);
    die();
?>