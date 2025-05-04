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

    $utente = $_SESSION["user"];
    $username = $utente->getUsername();
    $email = $utente->getEmail();
    $bio = $utente->getBio();
    $generePreferito = $utente->getGenere();
    $stilePreferito = $utente->getStile();

    $db = Database::getInstance();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synesthesia - Profilo</title>
    <link rel="icon" href="images/logo_no_scritta.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="particles-container" id="particles-js"></div>
    
    <div class="container-fluid profile-container">
        <header class="main-header">
            <div class="logo-container">
                <a href="home.php" class="link-home">
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
            <div class="profile-header">
                <div class="profile-avatar-container">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-blur-effect"></div>
                </div>
                <div class="profile-info">
                    <h1><?php echo $username; ?></h1>
                    <p class="profile-bio"><?php echo $bio ?></p>
                </div>
                <div class="profile-actions">
                    <button class="btn btn-edit" id="btnEditProfile">
                        <i class="fas fa-edit"></i> Modifica Profilo
                    </button>
                </div>
            </div>

            <div class="row profile-sections">
                <!-- Informazioni utente -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="profile-section">
                        <div class="section-header">
                            <h2><i class="fas fa-user-circle"></i> Informazioni Personali</h2>
                        </div>
                        <div class="section-content">
                            <div class="info-item">
                                <div class="info-label">Username:</div>
                                <div class="info-value"><?php echo $username; ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Email:</div>
                                <div class="info-value"><?php echo $email; ?></div>
                            </div>
                        </div>
                        <div class="card-blob blob1"></div>
                        <div class="card-blob blob2"></div>
                    </div>
                </div>

                <!-- Preferenze musicali -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="profile-section">
                        <div class="section-header">
                            <h2><i class="fas fa-music"></i> Preferenze Musicali</h2>
                        </div>
                        <div class="section-content">
                            <div class="tags-container">
                                <?php 
                                if ($generePreferito != null) {
                                    echo '<span class="tag music-tag">' . $generePreferito . '</span>';
                                } else {
                                    echo '<p>Nessun genere preferito impostato</p>';
                                }
                                ?>
                            </div>
                            <div class="sound-wave-mini">
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                            </div>
                        </div>
                        <div class="card-blob blob1"></div>
                        <div class="card-blob blob2"></div>
                    </div>
                </div>

                <!-- Preferenze di stile -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="profile-section">
                        <div class="section-header">
                            <h2><i class="fas fa-tshirt"></i> Stile Personale</h2>
                        </div>
                        <div class="section-content">
                            <div class="tags-container">
                                <?php 
                                if ($stilePreferito != null) {
                                    echo '<span class="tag style-tag">' . $stilePreferito . '</span>';
                                } else {
                                    echo '<p>Nessuno stile preferito impostato</p>';
                                }
                                ?>
                            </div>
                            <div class="style-color-palette">
                                <div class="color-swatch" style="background-color: #e91e63;"></div>
                                <div class="color-swatch" style="background-color: #9c27b0;"></div>
                                <div class="color-swatch" style="background-color: #3f51b5;"></div>
                                <div class="color-swatch" style="background-color: #2196f3;"></div>
                            </div>
                        </div>
                        <div class="card-blob blob1"></div>
                        <div class="card-blob blob2"></div>
                    </div>
                </div>
            </div>

            <!-- Recenti attività -->
            <div class="recent-activity">
                <div class="section-header full-width">
                    <h2><i class="fas fa-history"></i> Attività Recenti</h2>
                </div>
                <div class="activities-container">
                    <!-- <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <div class="activity-content">
                            <h3>Hai creato una nuova playlist</h3>
                            <p>"Vibes estive" - 12 brani</p>
                            <span class="activity-time">3 giorni fa</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <div class="activity-content">
                            <h3>Hai aggiunto un nuovo capo al guardaroba</h3>
                            <p>Giacca denim vintage</p>
                            <span class="activity-time">1 settimana fa</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <div class="activity-content">
                            <h3>Hai creato una nuova combinazione</h3>
                            <p>"Urban Night" - ispirata a playlist Lofi Beats</p>
                            <span class="activity-time">2 settimane fa</span>
                        </div>
                    </div> -->
                </div>
            </div>
        </main>

        <!-- Modal di modifica profilo -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Modifica Profilo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- <form id="profileEditForm" action="update_profile.php" method="post"> -->
                            <input type="hidden" id="id" value="<?php echo $utente->getId(); ?>">
                            <div class="mb-3">
                                <label for="editUsername" class="form-label">Username</label>
                                <input type="text" class="form-control" id="editUsername" name="username" value="<?php echo $username; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo $email; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="editBio" class="form-label">Bio</label>
                                <textarea class="form-control" id="editBio" name="bio" rows="3"><?php echo $bio ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="editGenere" class="form-label">Genere Musicale Preferito</label>
                                <select class="form-control" id="editGenere" name="genere">
                                    <?php
                                        $generi = $db->getGeneriMusicali();
                                        $generePreferito = $utente->getGenere();
                                        echo '<option value=""></option>';
                                        foreach ($generi as $genere) {
                                            if($genere == $generePreferito)
                                                echo '<option value="'.$genere.'" selected>'.$genere.'</option>';
                                            else
                                                echo '<option value="'.$genere.'">'.$genere.'</option>';    
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editStile" class="form-label">Stile Preferito</label>
                                <select class="form-control" id="editStile" name="stile">
                                    <?php
                                        $stili = $db->getStili();
                                        $stilePreferito = $utente->getStile();
                                        echo '<option value=""></option>';
                                        foreach ($stili as $stile) {
                                            if($stile == $stilePreferito)
                                                echo '<option value="'.$stile.'" selected>'.$stile.'</option>';
                                            else
                                                echo '<option value="'.$stile.'">'.$stile.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        <!-- </form> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="saveProfileChanges" onclick="editProfilo()">Salva modifiche</button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <p>&copy; 2025 Synesthesia - Dove musica e moda si fondono</p>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/home.js"></script>
    <script src="js/profile.js"></script>
</body>
</html>