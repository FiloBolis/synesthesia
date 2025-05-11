<?php
    require_once "../class/Database.php";
    require_once "../class/Utente.php";

    if(!isset($_SESSION))
        session_start();
    if(!isset($_SESSION["user"])){
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Devi prima autenticarti!";
        echo json_encode($ret);
        die();
    }

    // Configurazione Spotify
    $client_id = '2f12f7caad5a425cb69e554e47673090'; // Sostituisci con il tuo Client ID
    $redirect_uri = 'https://progettoscolastico.netsons.org/callback_spotify.php'; // Sostituisci con l'URL corretto
    $scope = 'user-library-read playlist-modify-public playlist-modify-private user-read-email playlist-read-private playlist-read-collaborative';

    // Genera URL di autorizzazione
    $auth_url = "https://accounts.spotify.com/authorize?" . http_build_query([
        'client_id' => $client_id,
        'response_type' => 'code',
        'redirect_uri' => $redirect_uri,
        'scope' => $scope
    ]);

    $db = Database::getInstance();
    $db->addActivity($_SESSION["user"]->getId(), "Connessione all'account Spotify");

    $ret = [];
    $ret["status"] = "OK";
    $ret["msg"] = $auth_url;
    echo json_encode($ret);
    die();
?>