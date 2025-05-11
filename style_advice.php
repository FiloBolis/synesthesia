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
     
    // Ottieni stagione e meteo dalla sessione
    $stagione = $_SESSION["stagione"] ?? "Primavera";
    $meteo = $_SESSION["meteo"] ?? "Soleggiato";
    
    // Verifica che gli outfit siano presenti in sessione
    if (!isset($_SESSION["outfits"]) || empty($_SESSION["outfits"])) {
        // Reindirizza alla pagina di selezione se non ci sono outfit
        header("location: wardrobe.php?error=Nessun outfit disponibile");
        exit;
    }
    
    $outfitConsigliati = $_SESSION["outfits"];
    
    // Debug: Stampa nella console con JavaScript
    echo "<script>
        window.outfitData = " . json_encode($outfitConsigliati) . ";
        console.log('Dati degli outfit caricati:', window.outfitData);
    </script>";
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synesthesia - Consigli di Stile</title>
    <link rel="icon" href="images/logo_no_scritta.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/wardrobe.css">
    <link rel="stylesheet" href="css/style_advice.css">
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
                <a href="wardrobe.php" class="nav-link"><i class="fas fa-tshirt"></i> Guardaroba</a>
                <a href="playlist.php" class="nav-link"><i class="fas fa-music"></i> Playlist</a>
            </div>
            <div class="user-info">
                <span class="welcome-text">Benvenuto, <strong><?php echo $username; ?></strong></span>
                <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>

        <main class="main-content">
            <div class="page-header">
                <h2 class="section-title"><i class="fas fa-lightbulb"></i> Consigli di Stile</h2>
                <p class="section-description">Outfit consigliati in base alla stagione, al clima e al tuo guardaroba personale</p>
            </div>
            
            <div class="intro-section">
                <div class="season-badge">
                    <?php 
                    // Icona in base alla stagione
                    $iconaStagione = "";
                    switch($stagione) {
                        case "Primavera": 
                            $iconaStagione = '<i class="fas fa-seedling"></i>'; 
                            break;
                        case "Estate": 
                            $iconaStagione = '<i class="fas fa-sun"></i>'; 
                            break;
                        case "Autunno": 
                            $iconaStagione = '<i class="fas fa-leaf"></i>'; 
                            break;
                        case "Inverno": 
                            $iconaStagione = '<i class="fas fa-snowflake"></i>'; 
                            break;
                    }
                    echo $iconaStagione . " " . $stagione;
                    ?>
                </div>
                <p class="intro-description">
                    In base alla stagione attuale (<?php echo $stagione; ?>), al meteo (<?php echo $meteo; ?>) e al tuo stile personale,
                    abbiamo selezionato alcuni outfit che potrebbero essere perfetti per te oggi.
                    Questi suggerimenti sono personalizzati considerando i capi presenti nel tuo guardaroba e le tue preferenze musicali.
                </p>
            </div>

            <?php foreach($outfitConsigliati as $index => $outfit): ?>
            <div class="outfit-container">
                <div class="outfit-header">
                    <h3 class="outfit-title">Outfit <?php echo $index + 1; ?></h3>
                    <div class="weather-info">
                        <?php 
                        // Icona in base al meteo
                        $iconaMeteo = "";
                        switch($meteo) {
                            case "Soleggiato": 
                                $iconaMeteo = '<i class="fas fa-sun weather-icon"></i>'; 
                                break;
                            case "Nuvoloso": 
                                $iconaMeteo = '<i class="fas fa-cloud weather-icon"></i>'; 
                                break;
                            case "Piovoso": 
                                $iconaMeteo = '<i class="fas fa-cloud-rain weather-icon"></i>'; 
                                break;
                            case "Ventoso": 
                                $iconaMeteo = '<i class="fas fa-wind weather-icon"></i>'; 
                                break;
                            case "Neve":
                                $iconaMeteo = '<i class="fas fa-snowflake weather-icon"></i>'; 
                                break;
                        }
                        echo $iconaMeteo . '<span class="weather-text">' . $meteo . '</span>';
                        ?>
                    </div>
                </div>

                <div class="outfit-items">
                    <?php
                    // Debug dell'outfit corrente
                    echo "<!-- Debug dell'outfit " . ($index + 1) . ": " . print_r($outfit, true) . " -->";
                    
                    // Sezione Upper Body
                    if (!empty($outfit['upper_body'])): ?>
                        <div class="outfit-category">
                        <h4 class="category-title">Parte Superiore</h4>
                        <div class="category-items">
                        <?php
                            foreach ($outfit['upper_body'] as $capo): ?>
                                <?php $capo = $capo[0] ?>
                                <div class="outfit-item">
                                    <div class="outfit-item-image" style="background-image: url('<?php echo isset($capo["img_path"]) ? $capo["img_path"] : ''; ?>');">
                                        <?php if(!isset($capo["img_path"]) || empty($capo["img_path"]) || !file_exists($capo["img_path"])): ?>
                                        <i class="fas fa-tshirt fa-3x" style="color: rgba(255,255,255,0.5);"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="outfit-item-details">
                                        <div class="outfit-item-name"><?php echo isset($capo["nome"]) ? $capo["nome"] : "Capo senza nome"; ?></div>
                                        <div class="outfit-item-category"><?php echo isset($capo["categoria"]) ? ucfirst($capo["categoria"]) : ""; ?></div>
                                        <?php if(isset($capo["strato"])): ?>
                                        <div class="outfit-item-layer">Strato: <?php echo $capo["strato"]; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach;
                        ?>
                    <?php endif; ?>
                    
                    <!-- Sezione Lower Body -->
                    <?php if (!empty($outfit['lower_body'])): ?>
                        <div class="outfit-category">
                            <h4 class="category-title">Parte Inferiore</h4>
                            <div class="category-items">
                                <?php 
                                // Gestiamo sia array che oggetto singolo
                                if (isset($outfit['lower_body'][0])) {
                                    // Array di capi
                                    foreach ($outfit['lower_body'] as $capo): ?>
                                        <div class="outfit-item">
                                            <div class="outfit-item-image" style="background-image: url('<?php echo isset($capo["img_path"]) ? $capo["img_path"] : ''; ?>');">
                                                <?php if(!isset($capo["img_path"]) || empty($capo["img_path"]) || !file_exists($capo["img_path"])): ?>
                                                <i class="fas fa-tshirt fa-3x" style="color: rgba(255,255,255,0.5);"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="outfit-item-details">
                                                <div class="outfit-item-name"><?php echo isset($capo["nome"]) ? $capo["nome"] : "Capo senza nome"; ?></div>
                                                <div class="outfit-item-category"><?php echo isset($capo["categoria"]) ? ucfirst($capo["categoria"]) : ""; ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                } else {
                                    // Singolo capo
                                    $capo = $outfit['lower_body']; ?>
                                    <div class="outfit-item">
                                        <div class="outfit-item-image" style="background-image: url('<?php echo isset($capo["img_path"]) ? $capo["img_path"] : ''; ?>');">
                                            <?php if(!isset($capo["img_path"]) || empty($capo["img_path"]) || !file_exists($capo["img_path"])): ?>
                                            <i class="fas fa-tshirt fa-3x" style="color: rgba(255,255,255,0.5);"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="outfit-item-details">
                                            <div class="outfit-item-name"><?php echo isset($capo["nome"]) ? $capo["nome"] : "Capo senza nome"; ?></div>
                                            <div class="outfit-item-category"><?php echo isset($capo["categoria"]) ? ucfirst($capo["categoria"]) : ""; ?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Sezione Calzature -->
                    <?php if (!empty($outfit['calzature'])): ?>
                        <div class="outfit-category">
                            <h4 class="category-title">Calzature</h4>
                            <div class="category-items">
                                <?php 
                                // Gestiamo sia array che oggetto singolo
                                if (isset($outfit['calzature'][0])) {
                                    // Array di calzature
                                    foreach ($outfit['calzature'] as $capo): ?>
                                        <div class="outfit-item">
                                            <div class="outfit-item-image" style="background-image: url('<?php echo isset($capo["img_path"]) ? $capo["img_path"] : ''; ?>');">
                                                <?php if(!isset($capo["img_path"]) || empty($capo["img_path"]) || !file_exists($capo["img_path"])): ?>
                                                <i class="fas fa-tshirt fa-3x" style="color: rgba(255,255,255,0.5);"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="outfit-item-details">
                                                <div class="outfit-item-name"><?php echo isset($capo["nome"]) ? $capo["nome"] : "Capo senza nome"; ?></div>
                                                <div class="outfit-item-category"><?php echo isset($capo["categoria"]) ? ucfirst($capo["categoria"]) : ""; ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                } else {
                                    // Singola calzatura
                                    $capo = $outfit['calzature']; ?>
                                    <div class="outfit-item">
                                        <div class="outfit-item-image" style="background-image: url('<?php echo isset($capo["img_path"]) ? $capo["img_path"] : ''; ?>');">
                                            <?php if(!isset($capo["img_path"]) || empty($capo["img_path"]) || !file_exists($capo["img_path"])): ?>
                                            <i class="fas fa-tshirt fa-3x" style="color: rgba(255,255,255,0.5);"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="outfit-item-details">
                                            <div class="outfit-item-name"><?php echo isset($capo["nome"]) ? $capo["nome"] : "Capo senza nome"; ?></div>
                                            <div class="outfit-item-category"><?php echo isset($capo["categoria"]) ? ucfirst($capo["categoria"]) : ""; ?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Sezione Accessori -->
                    <?php if (!empty($outfit['accessori'])): ?>
                        <div class="outfit-category">
                            <h4 class="category-title">Accessori</h4>
                            <div class="category-items">
                                <?php 
                                // Gestiamo sia array che oggetto singolo
                                if (isset($outfit['accessori'][0])) {
                                    // Array di accessori
                                    foreach ($outfit['accessori'] as $capo): ?>
                                        <div class="outfit-item">
                                            <div class="outfit-item-image" style="background-image: url('<?php echo isset($capo["img_path"]) ? $capo["img_path"] : ''; ?>');">
                                                <?php if(!isset($capo["img_path"]) || empty($capo["img_path"]) || !file_exists($capo["img_path"])): ?>
                                                <i class="fas fa-tshirt fa-3x" style="color: rgba(255,255,255,0.5);"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="outfit-item-details">
                                                <div class="outfit-item-name"><?php echo isset($capo["nome"]) ? $capo["nome"] : "Capo senza nome"; ?></div>
                                                <div class="outfit-item-category"><?php echo isset($capo["categoria"]) ? ucfirst($capo["categoria"]) : ""; ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                } else {
                                    // Singolo accessorio
                                    $capo = $outfit['accessori']; ?>
                                    <div class="outfit-item">
                                        <div class="outfit-item-image" style="background-image: url('<?php echo isset($capo["img_path"]) ? $capo["img_path"] : ''; ?>');">
                                            <?php if(!isset($capo["img_path"]) || empty($capo["img_path"]) || !file_exists($capo["img_path"])): ?>
                                            <i class="fas fa-tshirt fa-3x" style="color: rgba(255,255,255,0.5);"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="outfit-item-details">
                                            <div class="outfit-item-name"><?php echo isset($capo["nome"]) ? $capo["nome"] : "Capo senza nome"; ?></div>
                                            <div class="outfit-item-category"><?php echo isset($capo["categoria"]) ? ucfirst($capo["categoria"]) : ""; ?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(empty($outfit['upper_body']) && empty($outfit['lower_body']) && empty($outfit['calzature']) && empty($outfit['accessori'])): ?>
                        <div class="alert alert-info">Nessun capo disponibile per questo outfit</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>    
            
            <div class="text-center mt-4 mb-5">
                <a href="wardrobe.php" class="btn btn-back-wardrobe">
                    <i class="fas fa-tshirt"></i> Torna al Guardaroba
                </a>
            </div>
        </main>

        <footer class="main-footer">
            <p>&copy; 2025 Synesthesia - Dove musica e moda si fondono</p>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/style_advice.js"></script>
</body>
</html>