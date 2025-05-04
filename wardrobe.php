<?php
    require_once "class/Utente.php";
    require_once "class/Database.php";
    require_once "class/Vestito.php";
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
    $db = Database::getInstance();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synesthesia - Il Tuo Guardaroba</title>
    <link rel="icon" href="images/logo_no_scritta.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/wardrobe.css">
</head>
<body>
    <div class="particles-container" id="particles-js"></div>
    
    <div class="container-fluid wardrobe-container">
        <header class="main-header">
            <div class="logo-container">
                <img src="images/logo.png" alt="Synesthesia Logo" class="logo-image">
                <h1 class="brand-name">Synesthesia</h1>
            </div>
            <div class="navigation-links">
                <a href="home.php" class="nav-link"><i class="fas fa-home"></i> Home</a>
                <a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i> Profilo</a>
                <a href="playlist.php" class="nav-link"><i class="fas fa-music"></i> Playlist</a>
            </div>
            <div class="user-info">
                <span class="welcome-text">Benvenuto, <strong><?php echo $username; ?></strong></span>
                <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>

        <main class="main-content">
            <div class="page-header">
                <h2 class="section-title"><i class="fas fa-tshirt"></i> Il Tuo Guardaroba</h2>
                <p class="section-description">Gestisci i tuoi capi di abbigliamento e ottieni suggerimenti di stile basati sulla tua musica</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Sezione principale del guardaroba -->
                    <div class="wardrobe-section">
                        <div class="section-header">
                            <h3>I Tuoi Capi</h3>
                            <button class="btn btn-add-item" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                <i class="fas fa-plus"></i> Aggiungi Capo
                            </button>
                        </div>

                        <div class="filter-controls">
                            <div class="search-container">
                                <input type="text" id="searchItems" class="form-control search-input" placeholder="Cerca capi...">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                            <div class="filter-buttons">
                                <button class="btn btn-filter active" data-filter="all">Tutti</button>
                                <button class="btn btn-filter" data-filter="top">Top</button>
                                <button class="btn btn-filter" data-filter="bottom">Bottom</button>
                                <button class="btn btn-filter" data-filter="footwear">Calzature</button>
                                <button class="btn btn-filter" data-filter="accessories">Accessori</button>
                            </div>
                        </div>

                        <div class="items-container" id="wardrobeItems">
                            <!-- Qui verranno caricati dinamicamente i capi del guardaroba -->
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Pannello laterale per outfit consigliati e statistiche -->
                    <div class="sidebar-section">
                        <div class="stats-card">
                            <h4>Statistiche del Guardaroba</h4>
                            <div class="stats-content">
                                <div class="stat-item">
                                    <span class="stat-label">Capi Totali</span>
                                    <span class="stat-value" id="totalItems">0</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Colore Predominante</span>
                                    <span class="stat-value color-tag" id="dominantColor">
                                        <span class="color-dot"></span>
                                        Nessuno
                                    </span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Stile Prevalente</span>
                                    <span class="stat-value" id="dominantStyle">Nessuno</span>
                                </div>
                            </div>
                        </div>

                        <div class="advice-card">
                            <h4>Consigli di Stile</h4>
                            <p>Ottieni suggerimenti personalizzati basati sul tuo guardaroba.</p>
                            <div class="mb-3">
                                <select class="form-select" id="styleSelect">
                                    <option value="" disabled selected>Seleziona stile</option>
                                    <?php
                                        $stili = $db->getStili();
                                        foreach ($stili as $stile) {
                                            echo '<option value="'.$stile.'">'.$stile.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <button onclick="mostraConsigli()" class="btn btn-get-advice">
                                <i class="fas fa-lightbulb"></i> Scopri Outfit Consigliati
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="main-footer">
            <p>&copy; 2025 Synesthesia - Dove musica e moda si fondono</p>
        </footer>
    </div>

    <!-- Modal per aggiungere un nuovo capo -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Aggiungi Nuovo Capo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addItemForm">
                        <div class="mb-3">
                            <label for="itemName" class="form-label">Nome del Capo</label>
                            <input type="text" class="form-control" id="itemName" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemCategory" class="form-label">Categoria</label>
                            <select class="form-select" id="itemCategory" required>
                                <option value="" disabled selected>Seleziona una categoria</option>
                                <option value="top">Top</option>
                                <option value="bottom">Bottom</option>
                                <option value="calzature">Calzature</option>
                                <option value="accessori">Accessori</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemColor" class="form-label">Colore</label>
                            <input type="color" class="form-control" id="itemColor" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemStyle" class="form-label">Stile</label>
                            <select class="form-select" id="itemStyle" required>
                                <option value="" disabled selected>Seleziona uno stile</option>
                                <?php
                                    $stili = $db->getStili();
                                    foreach ($stili as $stile) {
                                        echo '<option value="'.$stile.'">'.$stile.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemMaterial" class="form-label">Materiale</label>
                            <input type="text" class="form-control" id="itemMaterial" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemFit" class="form-label">Vestibilità</label>
                            <select class="form-select" id="itemFit" required>
                                <option value="" disabled selected>Seleziona una vestibilità</option>
                                <option value="slim">Slim Fit</option>
                                <option value="regular">Regular Fit</option>
                                <option value="oversized">Oversized</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemDescription" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="itemDescription"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="itemImage" class="form-label">Immagine</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-image"></i></span>
                                <input type="file" class="form-control custom-file-input" id="itemImage" accept="image/*">
                            </div>
                            <div class="form-text text-muted">Scegli un'immagine per il tuo capo (PNG, JPG, max 5MB)</div>
                            <div class="mt-2 image-preview-container d-none">
                                <div class="position-relative">
                                    <img id="imagePreview" class="img-thumbnail" style="max-height: 200px; width: auto;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" id="removeImage">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addCapo()" class="btn btn-primary w-100"><i class="fas fa-plus"></i> Aggiungi Capo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal per visualizzare i dettagli di un capo -->
    <div class="modal fade" id="viewItemModal" tabindex="-1" aria-labelledby="viewItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewItemModalLabel">Dettagli Capo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewItemDetails">
                    <!-- Qui verranno visualizzati i dettagli del capo selezionato -->
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnDeleteItem" class="btn btn-delete-item" data-item-id=""><i class="fas fa-trash-alt"></i> Elimina</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast per notifiche -->
    <div class="toast-container">
        <div class="toast success" role="alert" aria-live="assertive" aria-atomic="true" id="successToast">
            <div class="toast-header">
                <i class="fas fa-check-circle me-2 text-success"></i>
                <strong class="me-auto">Operazione completata</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="successToastMessage">
                Operazione completata con successo!
            </div>
        </div>
        <div class="toast error" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast">
            <div class="toast-header">
                <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                <strong class="me-auto">Errore</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="errorToastMessage">
                Si è verificato un errore.
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/wardrobe.js"></script>
</body>
</html>