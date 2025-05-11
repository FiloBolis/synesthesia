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
    $playlists = $_SESSION["playlists"];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synesthesia - Le tue Playlist</title>
    <link rel="icon" href="images/logo_no_scritta.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/home_spotify.css">
    <link rel="stylesheet" href="css/playlists.css">
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
                <h2><i class="fas fa-list-music"></i> Le tue Playlist</h2>
                <p>Esplora tutte le tue playlist di Spotify</p>
            </div>

            <div class="playlist-grid" id="playlistGrid">
                <?php foreach ($playlists as $playlist): ?>
                <div class="playlist-card">
                    <div class="playlist-image-container">
                        <img src="<?php echo $playlist["images"][0]["url"]; ?>" alt="<?php echo $playlist["name"]; ?>" class="playlist-image">
                        <div class="playlist-overlay">
                            <a href="https://open.spotify.com/playlist/<?php echo $playlist["id"]; ?>" target="_blank" class="play-button">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                    <div class="playlist-info">
                        <h3 class="playlist-name"><?php echo $playlist["name"]; ?></h3>
                        <div class="playlist-meta">
                            <span class="playlist-tracks">
                                <i class="fas fa-music"></i> <?php echo $playlist["tracks"]["total"]; ?> brani
                            </span>
                            <span class="playlist-owner">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div id="noResults" class="no-results" style="display: none;">
                <i class="fas fa-search"></i>
                <p>Nessuna playlist trovata</p>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/home_spotify.js"></script>
    <script src="js/playlists.js"></script>
</body>
</html>