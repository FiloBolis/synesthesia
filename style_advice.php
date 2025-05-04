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
     
    // Ottieni stagione e meteo
    $stagione = $_SESSION["stagione"];
    $meteo = $_SESSION["meteo"];
    
    // Simulazione di outfit consigliati (in un'app reale, questa logica sarebbe più complessa)
    $outfitConsigliati = [];
    
    // Outfit per Primavera
    if ($stagione == "Primavera") {
        if ($meteo == "Soleggiato") {
            $outfitConsigliati[] = [
                "descrizione" => "Per una giornata di primavera soleggiata, ti consiglio di indossare una t-shirt bianca basic e abbinarla con jeans slim fit, sneakers basse e un cardigan leggero.",
                "capi" => [
                    ["nome" => "T-shirt bianca basic", "immagine" => "images/outfits/tshirt_bianca.jpg"],
                    ["nome" => "Jeans slim fit blu", "immagine" => "images/outfits/jeans_slim.jpg"],
                    ["nome" => "Sneakers basse bianche", "immagine" => "images/outfits/sneakers_bianche.jpg"],
                    ["nome" => "Cardigan leggero grigio", "immagine" => "images/outfits/cardigan_grigio.jpg"]
                ]
            ];
        } else if ($meteo == "Piovoso" || $meteo == "Nuvoloso") {
            $outfitConsigliati[] = [
                "descrizione" => "Con questo meteo incerto di primavera, ti suggerisco di indossare una camicia a quadri e abbinarla con chino pants, stivaletti impermeabili e una giacca leggera antipioggia.",
                "capi" => [
                    ["nome" => "Camicia a quadri", "immagine" => "images/outfits/camicia_quadri.jpg"],
                    ["nome" => "Chino pants beige", "immagine" => "images/outfits/chino_beige.jpg"],
                    ["nome" => "Stivaletti impermeabili", "immagine" => "images/outfits/stivaletti.jpg"],
                    ["nome" => "Giacca antipioggia leggera", "immagine" => "images/outfits/giacca_antipioggia.jpg"]
                ]
            ];
        } else {
            $outfitConsigliati[] = [
                "descrizione" => "Per una giornata ventosa di primavera, ti consiglio di indossare un maglione leggero e abbinarlo con jeans straight fit, una sciarpa sottile e mocassini.",
                "capi" => [
                    ["nome" => "Maglione leggero blu", "immagine" => "images/outfits/maglione_leggero.jpg"],
                    ["nome" => "Jeans straight fit", "immagine" => "images/outfits/jeans_straight.jpg"],
                    ["nome" => "Sciarpa sottile", "immagine" => "images/outfits/sciarpa_sottile.jpg"],
                    ["nome" => "Mocassini in pelle", "immagine" => "images/outfits/mocassini.jpg"]
                ]
            ];
        }
    }
    // Outfit per Estate
    else if ($stagione == "Estate") {
        if ($meteo == "Soleggiato") {
            $outfitConsigliati[] = [
                "descrizione" => "Per una giornata estiva e soleggiata, ti consiglio di indossare una polo colorata e abbinarla con bermuda in cotone, sandali comodi e un cappello panama.",
                "capi" => [
                    ["nome" => "Polo azzurra", "immagine" => "images/outfits/polo_azzurra.jpg"],
                    ["nome" => "Bermuda in cotone beige", "immagine" => "images/outfits/bermuda_beige.jpg"],
                    ["nome" => "Sandali in pelle", "immagine" => "images/outfits/sandali.jpg"],
                    ["nome" => "Cappello panama", "immagine" => "images/outfits/cappello_panama.jpg"]
                ]
            ];
        } else {
            $outfitConsigliati[] = [
                "descrizione" => "Per un'estate con tempo variabile, ti consiglio di indossare una camicia di lino e abbinarla con pantaloni leggeri, sneakers in tela e una giacca leggera da tenere a portata di mano.",
                "capi" => [
                    ["nome" => "Camicia di lino bianca", "immagine" => "images/outfits/camicia_lino.jpg"],
                    ["nome" => "Pantaloni leggeri navy", "immagine" => "images/outfits/pantaloni_leggeri.jpg"],
                    ["nome" => "Sneakers in tela", "immagine" => "images/outfits/sneakers_tela.jpg"],
                    ["nome" => "Giacca leggera beige", "immagine" => "images/outfits/giacca_leggera.jpg"]
                ]
            ];
        }
    }
    // Outfit per Autunno
    else if ($stagione == "Autunno") {
        if ($meteo == "Soleggiato" || $meteo == "Nuvoloso") {
            $outfitConsigliati[] = [
                "descrizione" => "Per una giornata autunnale, ti consiglio di indossare un maglione a collo alto e abbinarlo con jeans scuri, stivaletti chelsea e una giacca di pelle.",
                "capi" => [
                    ["nome" => "Maglione a collo alto bordeaux", "immagine" => "images/outfits/maglione_collo_alto.jpg"],
                    ["nome" => "Jeans scuri slim fit", "immagine" => "images/outfits/jeans_scuri.jpg"],
                    ["nome" => "Stivaletti chelsea neri", "immagine" => "images/outfits/stivaletti_chelsea.jpg"],
                    ["nome" => "Giacca di pelle nera", "immagine" => "images/outfits/giacca_pelle.jpg"]
                ]
            ];
        } else {
            $outfitConsigliati[] = [
                "descrizione" => "Con questo meteo autunnale variabile, ti suggerisco di indossare una camicia flanella e abbinarla con chino pants, boots impermeabili e un trench coat classico.",
                "capi" => [
                    ["nome" => "Camicia in flanella", "immagine" => "images/outfits/camicia_flanella.jpg"],
                    ["nome" => "Chino pants marrone", "immagine" => "images/outfits/chino_marrone.jpg"],
                    ["nome" => "Boots impermeabili", "immagine" => "images/outfits/boots.jpg"],
                    ["nome" => "Trench coat beige", "immagine" => "images/outfits/trench.jpg"]
                ]
            ];
        }
    }
    // Outfit per Inverno
    else {
        if ($meteo == "Soleggiato" || $meteo == "Nuvoloso") {
            $outfitConsigliati[] = [
                "descrizione" => "Per una giornata invernale fresca, ti consiglio di indossare un maglione pesante e abbinarlo con pantaloni di lana, stivali imbottiti e un cappotto elegante.",
                "capi" => [
                    ["nome" => "Maglione pesante grigio", "immagine" => "images/outfits/maglione_pesante.jpg"],
                    ["nome" => "Pantaloni di lana", "immagine" => "images/outfits/pantaloni_lana.jpg"],
                    ["nome" => "Stivali imbottiti", "immagine" => "images/outfits/stivali_imbottiti.jpg"],
                    ["nome" => "Cappotto elegante navy", "immagine" => "images/outfits/cappotto_navy.jpg"]
                ]
            ];
        } else {
            $outfitConsigliati[] = [
                "descrizione" => "Con questo meteo invernale rigido, ti suggerisco di indossare una felpa termica e abbinarla con jeans imbottiti, boots impermeabili, un parka e accessori caldi come guanti e sciarpa.",
                "capi" => [
                    ["nome" => "Felpa termica", "immagine" => "images/outfits/felpa_termica.jpg"],
                    ["nome" => "Jeans imbottiti", "immagine" => "images/outfits/jeans_imbottiti.jpg"],
                    ["nome" => "Boots impermeabili", "immagine" => "images/outfits/boots_impermeabili.jpg"],
                    ["nome" => "Parka con cappuccio", "immagine" => "images/outfits/parka.jpg"],
                    ["nome" => "Set guanti e sciarpa", "immagine" => "images/outfits/guanti_sciarpa.jpg"]
                ]
            ];
        }
    }
    
    // Aggiungi un secondo outfit per ogni stagione
    if ($stagione == "Primavera") {
        $outfitConsigliati[] = [
            "descrizione" => "Per un look più elegante in primavera, ti consiglio di indossare una camicia oxford azzurra e abbinarla con chino pants, loafers e un blazer leggero.",
            "capi" => [
                ["nome" => "Camicia oxford azzurra", "immagine" => "images/outfits/camicia_oxford.jpg"],
                ["nome" => "Chino pants navy", "immagine" => "images/outfits/chino_navy.jpg"],
                ["nome" => "Loafers in pelle", "immagine" => "images/outfits/loafers.jpg"],
                ["nome" => "Blazer leggero beige", "immagine" => "images/outfits/blazer_leggero.jpg"]
            ]
        ];
    } else if ($stagione == "Estate") {
        $outfitConsigliati[] = [
            "descrizione" => "Per una serata estiva, ti consiglio di indossare una camicia a maniche corte stampata e abbinarla con bermuda eleganti, mocassini senza calze e un orologio sportivo.",
            "capi" => [
                ["nome" => "Camicia a maniche corte stampata", "immagine" => "images/outfits/camicia_stampata.jpg"],
                ["nome" => "Bermuda eleganti blu", "immagine" => "images/outfits/bermuda_eleganti.jpg"],
                ["nome" => "Mocassini estivi", "immagine" => "images/outfits/mocassini_estivi.jpg"],
                ["nome" => "Orologio sportivo", "immagine" => "images/outfits/orologio_sportivo.jpg"]
            ]
        ];
    } else if ($stagione == "Autunno") {
        $outfitConsigliati[] = [
            "descrizione" => "Per un look casual autunnale, ti consiglio di indossare un hoodie oversize e abbinarlo con joggers, sneakers chunky e una giacca di jeans.",
            "capi" => [
                ["nome" => "Hoodie oversize grigio", "immagine" => "images/outfits/hoodie_oversize.jpg"],
                ["nome" => "Joggers neri", "immagine" => "images/outfits/joggers.jpg"],
                ["nome" => "Sneakers chunky", "immagine" => "images/outfits/sneakers_chunky.jpg"],
                ["nome" => "Giacca di jeans", "immagine" => "images/outfits/giacca_jeans.jpg"]
            ]
        ];
    } else {
        $outfitConsigliati[] = [
            "descrizione" => "Per un'occasione elegante in inverno, ti consiglio di indossare un dolcevita nero e abbinarlo con pantaloni di flanella, stivaletti chelsea e un cappotto lungo.",
            "capi" => [
                ["nome" => "Dolcevita nero", "immagine" => "images/outfits/dolcevita.jpg"],
                ["nome" => "Pantaloni di flanella grigi", "immagine" => "images/outfits/pantaloni_flanella.jpg"],
                ["nome" => "Stivaletti chelsea marrone", "immagine" => "images/outfits/chelsea_marrone.jpg"],
                ["nome" => "Cappotto lungo nero", "immagine" => "images/outfits/cappotto_lungo.jpg"]
            ]
        ];
    }

    $outfitConsigliati = $_SESSION["outfits"];
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
                
                <!-- <p class="outfit-description"> -->
                    <?php 
                        //echo $outfit["descrizione"]; 
                    ?>
                <!-- </p> -->

                <?php
                    $capi = [];
                    foreach ($outfit['upper_body'] as $o) {
                        $capi[] = $o;
                    }
                    foreach ($outfit['lower_body'] as $o) {
                        $capi[] = $o;
                    }
                    foreach ($outfit['calzature'] as $o) {
                        $capi[] = $o;
                    }
                    foreach ($outfit['accessori'] as $o) {
                        $capi[] = $o;
                    }
                ?>
                
                <div class="outfit-items">
                    <?php foreach($capi as $capo): ?>
                    <div class="outfit-item">
                        <div class="outfit-item-image" style="background-image: url('<?php echo $capo["immagine"]; ?>');">
                            <!-- Mostra immagine di fallback se l'immagine non esiste -->
                            <?php if(!file_exists($capo["immagine"])): ?>
                            <i class="fas fa-tshirt fa-3x" style="color: rgba(255,255,255,0.5);"></i>
                            <?php endif; ?>
                        </div>
                        <div class="outfit-item-name"><?php echo $capo["nome"]; ?></div>
                    </div>
                    <?php endforeach; ?>
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