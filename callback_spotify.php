<?php
    require_once 'class/Database.php';
    require_once 'class/Utente.php';

    if(!isset($_SESSION))
        session_start();
    if(!isset($_SESSION["user"])){
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Devi prima autenticarti!";
        echo json_encode($ret);
        die();
    }

    $client_id = '2f12f7caad5a425cb69e554e47673090';
    $client_secret = '45e4795916484272a302015d454334d8';
    $redirect_uri = 'https://progettoscolastico.netsons.org/callback_spotify.php';

    if (!isset($_GET['code'])) {
        header("Location: spotify_login.php");
        exit();
    }

    $code = $_GET['code'];
    $token_url = 'https://accounts.spotify.com/api/token';

    $response = file_get_contents($token_url, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                        "Authorization: Basic " . base64_encode("$client_id:$client_secret") . "\r\n",
            'content' => http_build_query([
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirect_uri
            ]),
        ]
    ]));

    $token_info = json_decode($response, true);
    $_SESSION['access_token'] = $token_info['access_token'];

    header("Location: home_spotify.php");
    exit();
?>