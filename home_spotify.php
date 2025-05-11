<?php
    require_once "class/Utente.php";
    // Inizializzazione della sessione
    if (! isset($_SESSION)) {
        session_start();
    }

    // Verifica che l'utente sia loggato
    if (! isset($_SESSION["user"], $_SESSION["access_token"])) {
        header("location: index.php?error=Devi prima autenticarti!");
        exit;
    }

    // Verifica che l'utente abbia un token Spotify valido
    if (! isset($_SESSION["access_token"]) || $_SESSION["access_token"] == "") {
        header("location: spotify_login.php");
        exit;
    }

    // Recupero le informazioni dell'utente dalla sessione
    $username = $_SESSION["user"]->getUsername();
    $id_utente = $_SESSION["user"]->getId();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synesthesia - Il tuo Spotify Hub</title>
    <link rel="icon" href="images/logo_no_scritta.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/home_spotify.css">
</head>
<body>
    <div class="particles-container" id="particles-js"></div>

    <div class="container-fluid spotify-hub-container">
        <header class="main-header">
            <div class="logo-container">
                <a href="home.php" class="home-link">
                    <img src="images/logo.png" alt="Synesthesia Logo" class="logo-image">
                    <h1 class="brand-name">Synesthesia</h1>
                </a>
            </div>
            <div class="user-info">
                <span class="welcome-text">Benvenuto, <strong><?php echo $username; ?></strong></span>
                <div class="user-actions">
                    <a href="" class="btn btn-spotify-connected">
                        <i class="fab fa-spotify"></i> Connesso
                    </a>
                    <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="section-title">
                <h2><i class="fas fa-headphones-alt"></i> Il tuo Hub Musicale</h2>
                <p>Esplora le tue playlist, scopri nuova musica o lasciati guidare dal tuo mood</p>
            </div>

            <div class="hub-options">
                <div class="hub-card playlist-card">
                    <div class="card-blob blob1"></div>
                    <div class="card-blob blob2"></div>
                    
                    <div class="card-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    
                    <h3>Le tue Playlist</h3>
                    <p>Visualizza tutte le tue playlist Spotify</p>
                    
                    <button class="btn btn-hub" onclick="getPlaylists(<?php echo $id_utente ?>)">
                        <i class="fas fa-arrow-right"></i> Vai alle Playlist
                    </button>
                </div>
                
                <div class="hub-card pickatune-card">
                    <div class="card-blob blob3"></div>
                    <div class="card-blob blob4"></div>
                    
                    <div class="card-icon">
                        <i class="fas fa-guitar"></i>
                    </div>
                    
                    <h3>PickaTune</h3>
                    <p>Scegli brani in base al tuo stile e crea nuove playlist uniche che riflettono la tua personalità</p>
                    
                    <button class="btn btn-hub" onclick="getTracks(20)">
                        <i class="fas fa-arrow-right"></i> Vai a PickaTune
                    </button>
                </div>
            </div>

            <div class="back-to-home">
                <a href="home.php" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Torna alla Home
                </a>
            </div>
        </main>

        <footer class="main-footer">
            <p>&copy; 2025 Synesthesia - Dove musica e moda si fondono</p>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/home_spotify.js"></script>
</body>
</html>