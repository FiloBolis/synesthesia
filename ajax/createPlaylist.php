<?php
    require_once "../class/Database.php";
    require_once "../class/Utente.php";

    if(!isset($_SESSION))
        session_start();
    if(!isset($_SESSION["user"], $_SESSION["access_token"])){
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Devi prima autenticarti!";
        echo json_encode($ret);
        die();
    }

    $access_token = $_SESSION['access_token'];
    
    // Ottenere l'ID utente Spotify usando cURL invece di file_get_contents
    $ch = curl_init('https://api.spotify.com/v1/me');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token]);
    
    $user_response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Verifica se la richiesta ha avuto successo
    if ($http_code != 200) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Impossibile ottenere l'ID utente Spotify. Codice HTTP: " . $http_code . ". Token probabilmente scaduto.";
        echo json_encode($ret);
        die();
    }

    $user_data = json_decode($user_response, true);
    if (!isset($user_data['id'])) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Impossibile ottenere l'ID utente Spotify.";
        echo json_encode($ret);
        die();
    }

    $user_id = $user_data['id'];
    $playlist_name = date("d_m_Y"); // Nome di default con la data
    if(isset($_GET["nome"]))
        $playlist_name = $_GET["nome"];
    
    // Creare una nuova playlist usando cURL
    $playlist_data = [
        'name' => $playlist_name,
        'public' => true
    ];
    
    $ch = curl_init("https://api.spotify.com/v1/users/$user_id/playlists");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($playlist_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ]);
    
    $playlist_response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Verifica se la richiesta ha avuto successo
    if ($http_code != 201 && $http_code != 200) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Errore nella creazione della playlist. Codice HTTP: " . $http_code;
        echo json_encode($ret);
        die();
    }

    $playlist_data = json_decode($playlist_response, true);

    if (!isset($playlist_data['id'])) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Errore nella creazione della playlist.";
        echo json_encode($ret);
        die();
    }

    $db = Database::getInstance();
    $db->addActivity($_SESSION["user"]->getId(), "Creazione di una nuova playlist su Spotify");

    $ret = [];
    $ret["status"] = "OK";
    $ret["playlist_id"] = $playlist_data['id'];
    echo json_encode($ret);
    die();
?>