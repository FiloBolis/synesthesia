<?php
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

    if(!isset($_GET["max_tracks"])){
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Parametri mancanti!";
        echo json_encode($ret);
        die();
    }

    $id_utente = $_SESSION["user"]->getId();
    $access_token = $_SESSION["access_token"];
    $max_tracks = intval($_GET["max_tracks"]);

    // Se max_tracks è 0, recupera tutte le tracce disponibili
    // Per ogni richiesta usiamo sempre il limite massimo consentito da Spotify API (50)
    // ma rispetteremo comunque il numero totale richiesto da max_tracks (se > 0)
    $limit = 50; // Massimo consentito da Spotify API per richiesta

    $tracks = [];
    $urlPlaylist = "https://api.spotify.com/v1/me/tracks?limit=$limit";
    $totalFetched = 0;
    
    do {
        $ch = curl_init($urlPlaylist);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $access_token"
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response === false) {
            $ret = [];
            $ret["status"] = "ERR";
            $ret["msg"] = "Errore nella comunicazione con Spotify API";
            echo json_encode($ret);
            die();
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['items'])) {
            $ret = [];
            $ret["status"] = "ERR";
            $ret["msg"] = "Formato risposta non valido o nessuna traccia trovata";
            echo json_encode($ret);
            die();
        }
        
        foreach ($data['items'] as $track) {       
            // Verifica che i dati necessari esistano per evitare errori
            if (isset($track['track']['id'], $track['track']['name'], $track['track']['artists'][0]['name'])) {
                $tracks[] = [
                    'id' => $track['track']['id'],
                    'name' => $track['track']['name'],
                    'artist' => $track['track']['artists'][0]['name']
                ];
                $totalFetched++;
                
                // Se max_tracks è specificato e abbiamo raggiunto il limite, usciamo dal ciclo
                if($max_tracks > 0 && $totalFetched >= $max_tracks) {
                    break 2;  // Esce da entrambi i cicli
                }
            }
        }
        
        // Se max_tracks è 0 e ci sono altre pagine da recuperare, prepara l'URL per la prossima pagina
        $urlPlaylist = null;
        if(($max_tracks === 0 || $totalFetched < $max_tracks) && isset($data['next']) && $data['next']) {
            $urlPlaylist = $data['next'];
        }
        
    } while($urlPlaylist !== null);

    // Se non abbiamo trovato nessuna traccia valida
    if (empty($tracks)) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Nessuna traccia salvata trovata per l'utente";
        echo json_encode($ret);
        die();
    }

    $ret = [];
    $ret["status"] = "OK";
    $_SESSION["tracks"] = $tracks;
    echo json_encode($ret);
    die();
?>