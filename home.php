<?php
    require_once "class/Utente.php";
    // Inizializzazione della sessione
    if (!isset($_SESSION)) {
        session_start();
    }

    // Verifica che l'utente sia loggato
    if (!isset($_SESSION["user"])) {
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
    <title>Synesthesia - Home</title>
    <link rel="icon" href="images/logo_no_scritta.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <div class="particles-container" id="particles-js"></div>
    
    <div class="container-fluid home-container">
        <header class="main-header">
            <div class="logo-container">
                <img src="images/logo.png" alt="Synesthesia Logo" class="logo-image">
                <h1 class="brand-name">Synesthesia</h1>
            </div>
            <div class="user-info">
                <span class="welcome-text">Benvenuto, <strong><?php echo $username; ?></strong></span>
                <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>

        <main class="main-content">
            <div class="row feature-cards">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card profile-card" data-destination="profile.php">
                        <div class="card-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h2>Il Tuo Profilo</h2>
                        <p>Visualizza e modifica le tue informazioni personali</p>
                        <div class="card-hover-content">
                            <button class="btn btn-enter">Entra <i class="fas fa-arrow-right"></i></button>
                        </div>
                        <div class="card-blob blob1"></div>
                        <div class="card-blob blob2"></div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card wardrobe-card" data-destination="wardrobe.php">
                        <div class="card-icon">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <h2>Il Tuo Guardaroba</h2>
                        <p>Esplora e gestisci il tuo guardaroba personale</p>
                        <div class="card-hover-content">
                            <button class="btn btn-enter">Entra <i class="fas fa-arrow-right"></i></button>
                        </div>
                        <div class="card-blob blob1"></div>
                        <div class="card-blob blob2"></div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card playlist-card" data-destination="spotify_login.php">
                        <div class="card-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <h2>Le Tue Playlist</h2>
                        <p>Ascolta e crea playlist che rispecchiano il tuo stile</p>
                        <div class="card-hover-content">
                            <button class="btn btn-enter">Entra <i class="fas fa-arrow-right"></i></button>
                        </div>
                        <div class="card-blob blob1"></div>
                        <div class="card-blob blob2"></div>
                    </div>
                </div>
            </div>

            <div class="interactive-section">
                <div class="mood-visualizer">
                    <div class="visualizer-header">
                        <h3>Sinestesia del Giorno</h3>
                        <p>Scopri come la tua musica e il tuo stile si fondono oggi</p>
                    </div>
                    <div class="visualizer-content">
                        <div class="color-blob main-blob"></div>
                        <div class="music-wave">
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="main-footer">
            <p>&copy; 2025 Synesthesia - Dove musica e moda si fondono</p>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/home.js"></script>
</body>
</html>