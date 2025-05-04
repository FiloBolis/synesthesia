<?php
    require_once '../class/Database.php';
    require_once '../class/Utente.php';
    
    if (!isset($_SESSION)) {
        session_start();
    }
    
    // Verifica che l'utente sia loggato
    if (!isset($_SESSION["user"])) {
        header("location: ../index.php");
        exit;
    }

    if(!isset($_GET["stagione"], $_GET["temperatura"], $_GET["umidita"], $_GET["vento"], $_GET["codice"], $_GET["stile"])) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Parametri non validi.";
        echo json_encode($ret);
        die();
    }

    $stagione = $_GET["stagione"];
    $temperatura = $_GET["temperatura"];
    $umidita = $_GET["umidita"];
    $vento = $_GET["vento"];
    $codice = $_GET["codice"];
    $stile = $_GET["stile"];
    $idUtente = $_SESSION["user"]->getId();

    $db = Database::getInstance();
    $outfits = $db->suggerisciAbbigliamento($stagione, $temperatura, $umidita, $vento, $codice, $stile, $idUtente);
    //poi inserisci nella $_SESSION gli outfit
    $_SESSION["outfits"] = $outfits;
    $_SESSION["stagione"] = $stagione;
    $ret = [];
    $ret["status"] = "OK";
    echo json_encode($ret);
    die();
?>