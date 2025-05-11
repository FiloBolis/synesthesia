<?php
    if(!isset($_SESSION))
        session_start();
    if(!isset($_SESSION["user"], $_SESSION["access_token"])){
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Devi prima autenticarti!";
        echo json_encode($ret);
        die();
    }

    if(!isset($_GET["songId"], $_GET["playlistId"])){
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Parametri mancanti!";
        echo json_encode($ret);
        die();
    }

    $access_token = $_SESSION['access_token'];
    $songId = $_GET["songId"];
    $playlist_id = $_GET["playlistId"];

    // Ottenere l'ID utente Spotify
    $user_url = "https://api.spotify.com/v1/me";
    $user_response = file_get_contents($user_url, false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "Authorization: Bearer $access_token\r\n"
        ]
    ]));

    $user_data = json_decode($user_response, true);
    if (!isset($user_data['id'])) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Impossibile ottenere l'ID utente Spotify.";
        echo json_encode($ret);
        die();
    }

    $user_id = $user_data['id'];

    // Cercare la playlist dell'utente
    $search_playlist_url = "https://api.spotify.com/v1/users/$user_id/playlists";
    $playlists_response = file_get_contents($search_playlist_url, false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "Authorization: Bearer $access_token\r\n"
        ]
    ]));

    $playlists_data = json_decode($playlists_response, true);

    if (!$playlist_id) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Playlist non trovata.";
        echo json_encode($ret);
        die();
    }

    // Aggiungere la canzone alla playlist usando direttamente l'ID
    $track_uri = "spotify:track:" . $songId;
    $add_song_url = "https://api.spotify.com/v1/playlists/$playlist_id/tracks";

    $add_song_response = file_get_contents($add_song_url, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n" .
                        "Authorization: Bearer $access_token\r\n",
            'content' => json_encode([
                'uris' => [$track_uri]
            ])
        ]
    ]));

    $add_song_data = json_decode($add_song_response, true);

    if (!isset($add_song_data['snapshot_id'])) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Errore nell'aggiunta della canzone alla playlist.";
        echo json_encode($ret);
        die();
    }

    $ret = [];
    $ret["status"] = "OK";
    $ret["msg"] = "Canzone aggiunta alla playlist con successo!";
    echo json_encode($ret);
    die();

?>