<?php
if (! isset($_SESSION)) {
    session_start();
}

// Verifica che l'utente sia loggato
if (! isset($_SESSION["user"])) {
    header("location: ../index.php");
    exit;
}

// Verifica se i parametri richiesti sono presenti
if (! isset($_GET['lat'], $_GET['lon'])) {
    $ret           = [];
    $ret["status"] = "ERR";
    $ret["msg"]    = "Parametri di geolocalizzazione mancanti!";
    echo json_encode($ret);
    die();
}

$lat = $_GET['lat'];
$lon = $_GET['lon'];

// Ottieni i dati meteo da OpenWeatherMap
$apiKey = "cc6910545fc7f6c40f263101a89c2379";
$url    = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=it";

try {
    // Effettua la richiesta per ottenere i dati meteo
    $response = file_get_contents($url);
    if ($response === false) {
        throw new Exception("Impossibile contattare il servizio meteo");
    }

    $weatherData = json_decode($response, true);

    // Verifica se la risposta è valida
    if (! isset($weatherData["weather"])) {
        throw new Exception("Dati meteo non disponibili");
    }

    // Inizializza array risultato
    $ret           = [];
    $ret["status"] = "OK";

    // Ottieni informazioni meteo
    $ret['weatherInfo'] = [
        'temp'         => $weatherData["main"]["temp"],
        'humidity'     => $weatherData["main"]["humidity"],
        'wind_speed'   => $weatherData["wind"]["speed"],
        'description'  => $weatherData["weather"][0]["description"],
        'weather_code' => $weatherData["weather"][0]["id"],
        'weather_icon' => $weatherData["weather"][0]["icon"],
    ];

    // Ottieni l'indirizzo usando l'API di Nominatim (OpenStreetMap)
    $geocodeUrl = "https://nominatim.openstreetmap.org/reverse?lat={$lat}&lon={$lon}&format=json";

    $options = [
        "http" => [
            "header" => "User-Agent: MyGeolocationApp/1.0 (myemail@example.com)",
        ],
    ];
    $context = stream_context_create($options);

    $geocodeResponse = file_get_contents($geocodeUrl, false, $context);
    if ($geocodeResponse === false) {
        $ret['address'] = "Indirizzo non disponibile.";
    } else {
        $addressData = json_decode($geocodeResponse, true);

        if (isset($addressData['address'])) {
            $address = '';
            $address .= isset($addressData['address']['road']) ? $addressData['address']['road'] . ', ' : '';
            $address .= isset($addressData['address']['city']) ? $addressData['address']['city'] . ', ' : '';
            $address .= isset($addressData['address']['town']) ? $addressData['address']['town'] . ', ' : '';
            $address .= isset($addressData['address']['village']) ? $addressData['address']['village'] . ', ' : '';
            $address .= isset($addressData['address']['municipality']) ? $addressData['address']['municipality'] . ', ' : '';
            $address .= isset($addressData['address']['country']) ? $addressData['address']['country'] : 'Indirizzo non disponibile.';

            $ret['address'] = $address;
        } else {
            $ret['address'] = "Indirizzo non disponibile.";
        }
    }

    // Determina la stagione in base all'emisfero
    $month = date('n');
    if ($lat >= 0) { // Emisfero Nord
        if ($month >= 3 && $month <= 5) {
            $ret['season'] = "Primavera";
        } elseif ($month >= 6 && $month <= 8) {
            $ret['season'] = "Estate";
        } elseif ($month >= 9 && $month <= 11) {
            $ret['season'] = "Autunno";
        } else {
            $ret['season'] = "Inverno";
        }
    } else { // Emisfero Sud
        if ($month >= 3 && $month <= 5) {
            $ret['season'] = "Autunno";
        } elseif ($month >= 6 && $month <= 8) {
            $ret['season'] = "Inverno";
        } elseif ($month >= 9 && $month <= 11) {
            $ret['season'] = "Primavera";
        } else {
            $ret['season'] = "Estate";
        }
    }

    // Restituisci i dati in formato JSON
    echo json_encode($ret);

} catch (Exception $e) {
    $addressData = [];
    $ret["status"] = "ERR";
    $ret["msg"] = "Errore: " . $e->getMessage();
    echo json_encode($ret);
}
die();
