<?php
    require_once "class/Utente.php";
    // Inizializzazione della sessione
    if (! isset($_SESSION)) {
        session_start();
    }

    // Verifica che l'utente sia loggato
    if (! isset($_SESSION["user"])) {
        header("location: index.php?error=Devi prima autenticarti!");
        exit;
    }

    // Recupero le informazioni dell'utente dalla sessione
    $username = $_SESSION["user"]->getUsername();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synesthesia - Le Tue Playlist</title>
    <link rel="icon" href="images/logo_no_scritta.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/spotify_login.css">
</head>
<body>
    <div class="particles-container" id="particles-js"></div>

    <div class="container-fluid playlist-container">
        <header class="main-header">
            <div class="logo-container">
                <a href="home.php" class="home-link">
                    <img src="images/logo.png" alt="Synesthesia Logo" class="logo-image">
                    <h1 class="brand-name">Synesthesia</h1>
                </a>
            </div>
            <div class="user-info">
                <span class="welcome-text">Benvenuto, <strong><?php echo $username; ?></strong></span>
                <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>

        <main class="main-content">
            <div class="section-title">
                <h2><i class="fas fa-music"></i> Le Tue Playlist</h2>
                <p>Connetti il tuo account Spotify per sincronizzare le tue playlist con il tuo stile</p>
            </div>

            <div class="spotify-connect-container">
                <div class="connect-card">
                    <div class="card-blob blob1"></div>
                    <div class="card-blob blob2"></div>

                    <div class="spotify-logo">
                        <i class="fab fa-spotify"></i>
                    </div>

                    <h3>Connetti Spotify</h3>
                    <p>Collega il tuo account Spotify per scoprire come la tua musica si sincronizza con il tuo stile</p>

                    <div class="connect-div">
                        <button onclick="connetti()" class="btn btn-spotify">
                            <i class="fab fa-spotify"></i> Connetti con Spotify
                        </button>
                    </div>

                    <div class="spotify-benefits">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-palette"></i>
                            </div>
                            <div class="benefit-text">
                                <h4>Palette Musicali</h4>
                                <p>Crea palette di colori basate sulle tue canzoni preferite</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-tshirt"></i>
                            </div>
                            <div class="benefit-text">
                                <h4>Abbinamenti Musicali</h4>
                                <p>Suggerimenti di outfit basati sulle tue playlist</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="benefit-text">
                                <h4>Analisi dello Stile</h4>
                                <p>Scopri come la tua musica influenza il tuo stile</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="music-visualizer">
                <h3>Visual Synesthesia</h3>
                <p>La tua musica trasformata in colori e forme</p>

                <div class="visualizer-container">
                    <div class="central-circle"></div>
                    <div class="wave-container">
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                        <div class="wave-bar"></div>
                    </div>
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
    <script src="js/spotify_login.js"></script>
</body>
</html>