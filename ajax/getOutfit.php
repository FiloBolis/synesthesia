<?php
require_once "../class/Database.php";
require_once "../class/Utente.php";

// Inizializzazione della sessione
if (!isset($_SESSION)) {
    session_start();
}

// Verifica che l'utente sia loggato
if (!isset($_SESSION["user"])) {
    $ret = [];
    $ret["status"] = "ERR";
    $ret["msg"] = "Utente non autenticato";
    echo json_encode($ret);
    die();
}

if(!isset($_GET["stagione"], $_GET["temperatura"], $_GET["umidita"], $_GET["vento"], $_GET["codice"], $_GET["meteo"])) {
    $ret = [];
    $ret["status"] = "ERR";
    $ret["msg"] = "Parametri mancanti nella richiesta";
    echo json_encode($ret);
    die();
}

// Recupera i parametri dalla richiesta
$stagione = $_GET["stagione"];
$temperatura = $_GET["temperatura"];
$umidita = $_GET["umidita"];
$vento = $_GET["vento"];
$codice = $_GET["codice"];

$meteo = $_GET["meteo"]; // Assicurati di ricevere il meteo

// Ottieni l'ID dell'utente dalla sessione
$idUtente = $_SESSION["user"]->getId();

try {
    // Ottieni l'istanza del database
    $db = Database::getInstance();
    
    // Chiama il metodo per suggerire abbigliamento
    $outfits = $db->suggerisciAbbigliamento($stagione, $temperatura, $umidita, $vento, $codice, $idUtente);

    if ($outfits == null) {
        $ret = [
            "status" => "ERR",
            "msg" => "nessun outfit trovato" // Messaggio di errore dal database
        ];
        echo json_encode($ret);
        die();
    }
    
    $outfitsFormattati = [];
    foreach ($outfits as $outfit) {
        $outfitFormattato = [
            "upper_body" => [],
            "lower_body" => [],
            "calzature" => [],
            "accessori" => []
        ];

        // Aggiungi tutti gli upper_body come array 
        if (!empty($outfit["upper_body"])) {
            $outfitFormattato["upper_body"] = array_values($outfit["upper_body"]);
        }
        
        // Aggiungi tutti i lower_body come array
        if (!empty($outfit["lower_body"])) {
            $outfitFormattato["lower_body"] = array_values($outfit["lower_body"]);
        }
        
        // Aggiungi tutte le calzature come array
        if (!empty($outfit["calzature"])) {
            $outfitFormattato["calzature"] = array_values($outfit["calzature"]);
        }
        
        // Aggiungi tutti gli accessori come array
        if (!empty($outfit["accessori"])) {
            $outfitFormattato["accessori"] = array_values($outfit["accessori"]);
        }

        $outfitsFormattati[] = $outfitFormattato;
    }
    
    // Salva in sessione le informazioni necessarie
    $_SESSION["outfits"] = $outfitsFormattati;
    $_SESSION["stagione"] = $stagione;
    $_SESSION["meteo"] = $meteo;  // Salva il meteo in sessione
    
    // Prepara la risposta
    $ret = [
        "status" => "OK",
        "msg" => "Outfit generati con successo"
    ];
    
    echo json_encode($ret);
    
} catch (Exception $e) {
    $ret = [
        "status" => "ERR",
        "msg" => "Errore: " . $e->getMessage()
    ];
    
    echo json_encode($ret);
}

$db = Database::getInstance();
$db->addActivity($_SESSION["user"]->getId(), "Consigli sull'outfit");

die();
?>