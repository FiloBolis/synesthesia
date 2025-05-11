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

// Inizializza la risposta
$response = [
    'status' => 'ERROR',
    'msg' => 'Si è verificato un errore imprevisto'
];

// Verifica se la richiesta è di tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['msg'] = 'Metodo di richiesta non valido';
    echo json_encode($response);
    exit;
}

// Verifica che tutti i campi obbligatori siano presenti
$requiredFields = ['nome', 'categoria', 'colore', 'stile', 'materiale', 'tipo', 'vestibilita'];
$missingFields = [];

foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    $response['msg'] = 'Campi obbligatori mancanti: ' . implode(', ', $missingFields);
    echo json_encode($response);
    exit;
}

// Recupera i valori dai campi POST
$nome = $_POST['nome'];
$categoria = $_POST['categoria'];
$colore = $_POST['colore'];
$stile = $_POST['stile'];
$materiale = $_POST['materiale'];
$tipo = $_POST['tipo'];
$vestibilita = $_POST['vestibilita'];
$descrizione = isset($_POST['descrizione']) ? $_POST['descrizione'] : '';

// Gestione dell'immagine
$imagePath = null;
if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/wardrobe/';
    $uploadDirTemp = '../uploads/wardrobe/temp/';
    
    // Crea le directory se non esistono
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    if (!file_exists($uploadDirTemp)) {
        mkdir($uploadDirTemp, 0777, true);
    }
    
    // Genera un nome di file unico
    $fileExtension = pathinfo($_FILES['immagine']['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '_' . time();
    $tempFilePath = $uploadDirTemp . $fileName . '.' . $fileExtension;
    
    // Sposta il file caricato nella cartella temporanea
    if (move_uploaded_file($_FILES['immagine']['tmp_name'], $tempFilePath)) {
        // Chiamata all'API di Remove.bg per rimuovere lo sfondo
        $removeBgResult = removeImageBackground($tempFilePath, $uploadDir . $fileName . '.png');
        
        if ($removeBgResult['success']) {
            $imagePath = 'uploads/wardrobe/' . $fileName . '.png'; // Percorso relativo per il database
            
            // Elimina il file temporaneo
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
        } else {
            // Se la rimozione dello sfondo fallisce, usa l'immagine originale
            $targetPath = $uploadDir . $fileName . '.' . $fileExtension;
            rename($tempFilePath, $targetPath);
            $imagePath = 'uploads/wardrobe/' . $fileName . '.' . $fileExtension;
            
            // Nota: potresti voler registrare l'errore di Remove.bg qui
            error_log('Remove.bg error: ' . $removeBgResult['error']);
        }
    } else {
        $response['msg'] = 'Errore durante il caricamento dell\'immagine';
        echo json_encode($response);
        exit;
    }
}

/**
 * Funzione per rimuovere lo sfondo da un'immagine usando l'API di Remove.bg
 * 
 * @param string $inputPath Il percorso del file di input
 * @param string $outputPath Il percorso dove salvare il file di output
 * @return array Risultato dell'operazione
 */
function removeImageBackground($inputPath, $outputPath) {
    // La tua chiave API di Remove.bg
    $apiKey = 'YpxcpqC6XiEw3FKPh6MMCBsf'; // Sostituisci con la tua chiave API
    
    // Prepara i dati per la richiesta
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.remove.bg/v1.0/removebg');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-Api-Key: ' . $apiKey,
    ]);
    
    $postFields = [
        'image_file' => new CURLFile($inputPath),
        'size' => 'auto', // Puoi specificare dimensioni diverse se necessario
        'format' => 'png', // Output in formato PNG con trasparenza
        'type' => 'auto', // Permette a Remove.bg di rilevare automaticamente il tipo di immagine
    ];
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    
    $result = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        curl_close($ch);
        return [
            'success' => false,
            'error' => 'cURL error: ' . curl_error($ch)
        ];
    }
    
    curl_close($ch);
    
    // Controlla lo stato della risposta
    if ($statusCode == 200) {
        // Salva l'immagine risultante
        file_put_contents($outputPath, $result);
        return [
            'success' => true
        ];
    } else {
        // Gestisce errori dall'API
        $error = json_decode($result, true);
        return [
            'success' => false,
            'error' => isset($error['errors'][0]['title']) ? $error['errors'][0]['title'] : 'Unknown error'
        ];
    }
}

try {
    $db = Database::getInstance();
    // Chiama la funzione addCapo con tutti i parametri
    $result = $db->addCapo(
        $nome,
        $categoria,
        $colore,
        $stile,
        $materiale,
        $tipo,
        $vestibilita,
        $descrizione,
        $imagePath,
        $_SESSION['user']->getId()
    );
    
    // Verifica il risultato
    if ($result === true) {
        $response = [
            'status' => 'OK',
            'msg' => 'Capo aggiunto con successo'
        ];
    } else {
        $response['msg'] = 'Errore durante l\'aggiunta del capo: ' . $result;
    }
} catch (Exception $e) {
    $response['msg'] = 'Eccezione: ' . $e->getMessage();
}

$db = Database::getInstance();
$db->addActivity($_SESSION["user"]->getId(), "Inserimento di un nuovo capo nell'armadio");

// Invia la risposta JSON
echo json_encode($response);
?>