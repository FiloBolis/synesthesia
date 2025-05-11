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

    if(!isset($_GET["id"])){
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Parametri mancanti!";
        echo json_encode($ret);
        die();
    }

    $id_utente = $_GET["id"];
    $access_token = $_SESSION["access_token"];
    $playlists = [];
    $url = "https://api.spotify.com/v1/me/playlists?limit=50";

    while ($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $access_token"
        ]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['items'])) {
            foreach ($data['items'] as $playlist) {
                // Ottieni l'immagine della copertina (se disponibile)
                $cover_image = !empty($playlist['images'][0]['url']) ? $playlist['images'][0]['url'] : '';
                
                // Ottieni il numero di tracce
                $tracks_count = isset($playlist['tracks']['total']) ? $playlist['tracks']['total'] : 0;
                
                $playlists[] = [
                    "id" => $playlist["id"],
                    "name" => $playlist["name"],
                    "images" => [
                        [
                            "url" => $cover_image
                        ]
                    ],
                    "tracks" => [
                        "total" => $tracks_count
                    ]
                ];
            }
        }

        $url = $data['next'] ?? null;
    }

    // Aggiungi "Brani che ti piacciono" all'inizio
    array_unshift($playlists, [
        'id' => 'liked_songs', 
        'name' => 'Brani che ti piacciono',
        'images' => [
            [
                "url" => 'https://t.scdn.co/images/3099b3803ad9496896c43f22fe9be8c4.png', // Immagine predefinita per i brani piaciuti
            ]
        ],
        'tracks' => [
            "total" => '?' // Non abbiamo il conteggio esatto qui
        ]
    ]);

    // Se vuoi ottenere il conteggio esatto per i "Brani che ti piacciono"
    if (!empty($playlists) && $playlists[0]['id'] === 'liked_songs') {
        $ch = curl_init("https://api.spotify.com/v1/me/tracks?limit=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $access_token"
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        if (isset($data['total'])) {
            $playlists[0]['tracks']['total'] = $data['total'];
        }
    }

    $ret = [];
    $ret["status"] = "OK";
    $_SESSION["playlists"] = $playlists;
    echo json_encode($ret);
    die();
?>