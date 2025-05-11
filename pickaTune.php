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
    
    // In un'implementazione reale qui si recupererebbero le tracce di una playlist selezionata
    // Per ora utilizziamo dei dati di esempio
    $tracks = $_SESSION["tracks"];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synesthesia - pickaTune</title>
    <link rel="icon" href="images/logo_no_scritta.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/home_spotify.css">
    <link rel="stylesheet" href="css/pickaTune.css">
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
                <h2><i class="fas fa-music"></i> pickaTune</h2>
                <p>Fai swipe sulle canzoni che ti piacciono e quelle che vuoi saltare</p>
            </div>

            <div class="pickatune-container">
                <div class="swipe-instructions">
                    <div class="instruction left">
                        <i class="fas fa-times-circle"></i>
                        <span>Swipe a sinistra per saltare</span>
                    </div>
                    <div class="instruction right">
                        <i class="fas fa-check-circle"></i>
                        <span>Swipe a destra per salvare</span>
                    </div>
                </div>

                <div class="swipe-container">
                    <div class="swipe-area" id="swipeArea">
                        <!-- Le card delle canzoni verranno inserite qui tramite JavaScript -->
                    </div>
                </div>

                <div class="swipe-actions">
                    <button id="dislikeBtn" class="btn btn-dislike">
                        <i class="fas fa-times"></i>
                    </button>
                    <button id="likeBtn" class="btn btn-like">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <div class="swipe-progress">
                    <div class="progress-text">
                        <span id="currentPosition">1</span>/<span id="totalCards"><?php echo count($tracks); ?></span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" id="progressBar" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            <div class="back-to-hub">
                <a href="home_spotify.php" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Torna all'Hub Musicale
                </a>
            </div>
        </main>

        <footer class="main-footer">
            <p>&copy; 2025 Synesthesia - Dove musica e moda si fondono</p>
        </footer>
    </div>

    <!-- Template per la card della canzone -->
    <template id="trackCardTemplate">
        <div class="track-card" data-id="">
            <div class="card-border left">
                <i class="fas fa-times"></i>
            </div>
            <div class="card-border right">
                <i class="fas fa-check"></i>
            </div>
            <div class="track-info">
                <h3 class="track-name"></h3>
                <p class="track-artist"></p>
            </div>
            <div class="track-player">
                <!-- L'iframe sarà inserito qui -->
            </div>
        </div>
    </template>

    <script>
        // Dati delle tracce da PHP a JavaScript
        const tracks = <?php echo json_encode($tracks); ?>;
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
    <script src="js/home_spotify.js"></script>
    <script src="js/pickaTune.js"></script>
</body>
</html>