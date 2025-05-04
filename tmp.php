<?php
// Esempio di utilizzo della funzione creaOutfit e visualizzazione dei risultati
// Supponiamo che $esigenze e $capi_adatti siano già stati definiti
$outfits = OutfitFunzioni::creaOutfit($esigenze, $capi_adatti);

// Funzione per visualizzare un outfit in formato HTML
function visualizzaOutfit($outfit, $numero_outfit) {
    echo "<div class='outfit' style='margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>";
    echo "<h2>Outfit #{$numero_outfit} - Punteggio di armonia: {$outfit['armonia']}/10</h2>";
    
    // Visualizza strati upper body
    echo "<div class='categoria'>";
    echo "<h3>Parte superiore:</h3>";
    if (isset($outfit['upper_body']) && is_array($outfit['upper_body'])) {
        echo "<div class='strati' style='display: flex; flex-wrap: wrap;'>";
        foreach ($outfit['upper_body'] as $index => $capo) {
            $strato = $index + 1;
            echo "<div class='capo' style='margin: 10px; padding: 15px; background-color: {$capo['colore']}; color: " . (isColorDark($capo['colore']) ? "#fff" : "#000") . ";'>";
            echo "<h4>Strato {$strato}:</h4>";
            echo "<p><strong>Tipo:</strong> " . htmlspecialchars($capo['tipo']) . "</p>";
            echo "<p><strong>Materiale:</strong> " . htmlspecialchars($capo['materiale']) . "</p>";
            echo "<p><strong>Stile:</strong> " . htmlspecialchars($capo['stile']) . "</p>";
            echo "<p><strong>Colore:</strong> " . htmlspecialchars($capo['colore']) . "</p>";
            if (isset($capo['nome'])) {
                echo "<p><strong>Nome:</strong> " . htmlspecialchars($capo['nome']) . "</p>";
            }
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>Nessun capo selezionato per la parte superiore.</p>";
    }
    echo "</div>";
    
    // Visualizza lower body
    echo "<div class='categoria'>";
    echo "<h3>Parte inferiore:</h3>";
    if (isset($outfit['lower_body']) && is_array($outfit['lower_body'])) {
        echo "<div class='capo' style='margin: 10px; padding: 15px; background-color: {$outfit['lower_body']['colore']}; color: " . (isColorDark($outfit['lower_body']['colore']) ? "#fff" : "#000") . ";'>";
        echo "<p><strong>Tipo:</strong> " . htmlspecialchars($outfit['lower_body']['tipo']) . "</p>";
        echo "<p><strong>Materiale:</strong> " . htmlspecialchars($outfit['lower_body']['materiale']) . "</p>";
        echo "<p><strong>Stile:</strong> " . htmlspecialchars($outfit['lower_body']['stile']) . "</p>";
        echo "<p><strong>Colore:</strong> " . htmlspecialchars($outfit['lower_body']['colore']) . "</p>";
        if (isset($outfit['lower_body']['nome'])) {
            echo "<p><strong>Nome:</strong> " . htmlspecialchars($outfit['lower_body']['nome']) . "</p>";
        }
        echo "</div>";
    } else {
        echo "<p>Nessun capo selezionato per la parte inferiore.</p>";
    }
    echo "</div>";
    
    // Visualizza calzature
    echo "<div class='categoria'>";
    echo "<h3>Calzature:</h3>";
    if (isset($outfit['calzature']) && is_array($outfit['calzature'])) {
        echo "<div class='capo' style='margin: 10px; padding: 15px; background-color: {$outfit['calzature']['colore']}; color: " . (isColorDark($outfit['calzature']['colore']) ? "#fff" : "#000") . ";'>";
        echo "<p><strong>Tipo:</strong> " . htmlspecialchars($outfit['calzature']['tipo']) . "</p>";
        echo "<p><strong>Materiale:</strong> " . htmlspecialchars($outfit['calzature']['materiale']) . "</p>";
        echo "<p><strong>Stile:</strong> " . htmlspecialchars($outfit['calzature']['stile']) . "</p>";
        echo "<p><strong>Colore:</strong> " . htmlspecialchars($outfit['calzature']['colore']) . "</p>";
        if (isset($outfit['calzature']['nome'])) {
            echo "<p><strong>Nome:</strong> " . htmlspecialchars($outfit['calzature']['nome']) . "</p>";
        }
        echo "</div>";
    } else {
        echo "<p>Nessun capo selezionato per le calzature.</p>";
    }
    echo "</div>";
    
    // Visualizza accessori
    echo "<div class='categoria'>";
    echo "<h3>Accessori:</h3>";
    if (isset($outfit['accessori']) && is_array($outfit['accessori'])) {
        echo "<div class='accessori' style='display: flex; flex-wrap: wrap;'>";
        foreach ($outfit['accessori'] as $accessorio) {
            echo "<div class='capo' style='margin: 10px; padding: 15px; background-color: {$accessorio['colore']}; color: " . (isColorDark($accessorio['colore']) ? "#fff" : "#000") . ";'>";
            echo "<p><strong>Tipo:</strong> " . htmlspecialchars($accessorio['tipo']) . "</p>";
            echo "<p><strong>Materiale:</strong> " . htmlspecialchars($accessorio['materiale']) . "</p>";
            echo "<p><strong>Stile:</strong> " . htmlspecialchars($accessorio['stile']) . "</p>";
            echo "<p><strong>Colore:</strong> " . htmlspecialchars($accessorio['colore']) . "</p>";
            if (isset($accessorio['nome'])) {
                echo "<p><strong>Nome:</strong> " . htmlspecialchars($accessorio['nome']) . "</p>";
            }
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>Nessun accessorio selezionato.</p>";
    }
    echo "</div>";
    
    echo "</div>"; // Fine outfit
}

// Funzione per determinare se un colore è scuro (per scegliere il colore del testo)
function isColorDark($hex) {
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    return $luminance < 0.5;
}

// Visualizza tutti gli outfit
echo "<h1>Outfit consigliati</h1>";
foreach ($outfits as $index => $outfit) {
    visualizzaOutfit($outfit, $index + 1);
}

// Versione alternativa per visualizzare outfit in formato più semplice (senza HTML)
function stampaOutfitConsole($outfits) {
    foreach ($outfits as $index => $outfit) {
        if (isset($outfit['upper_body']) && is_array($outfit['upper_body'])) {
            foreach ($outfit['upper_body'] as $index => $capo) {
                echo "  Strato " . ($index + 1) . ":\n";
                echo "    Tipo: " . $capo['tipo'] . "\n";
                echo "    Materiale: " . $capo['materiale'] . "\n";
                echo "    Stile: " . $capo['stile'] . "\n";
                echo "    Colore: " . $capo['colore'] . "\n";
                if (isset($capo['nome'])) {
                    echo "    Nome: " . $capo['nome'] . "\n";
                }
                echo "\n";
            }
        } else {
            echo "  Nessun capo selezionato\n\n";
        }
        
        // Stampa parte inferiore
        echo "PARTE INFERIORE:\n";
        if (isset($outfit['lower_body']) && is_array($outfit['lower_body'])) {
            echo "  Tipo: " . $outfit['lower_body']['tipo'] . "\n";
            echo "  Materiale: " . $outfit['lower_body']['materiale'] . "\n";
            echo "  Stile: " . $outfit['lower_body']['stile'] . "\n";
            echo "  Colore: " . $outfit['lower_body']['colore'] . "\n";
            if (isset($outfit['lower_body']['nome'])) {
                echo "  Nome: " . $outfit['lower_body']['nome'] . "\n";
            }
            echo "\n";
        } else {
            echo "  Nessun capo selezionato\n\n";
        }
        
        // Stampa calzature
        echo "CALZATURE:\n";
        if (isset($outfit['calzature']) && is_array($outfit['calzature'])) {
            echo "  Tipo: " . $outfit['calzature']['tipo'] . "\n";
            echo "  Materiale: " . $outfit['calzature']['materiale'] . "\n";
            echo "  Stile: " . $outfit['calzature']['stile'] . "\n";
            echo "  Colore: " . $outfit['calzature']['colore'] . "\n";
            if (isset($outfit['calzature']['nome'])) {
                echo "  Nome: " . $outfit['calzature']['nome'] . "\n";
            }
            echo "\n";
        } else {
            echo "  Nessun capo selezionato\n\n";
        }
        
        // Stampa accessori
        echo "ACCESSORI:\n";
        if (isset($outfit['accessori']) && is_array($outfit['accessori'])) {
            foreach ($outfit['accessori'] as $accessorio) {
                echo "  Accessorio:\n";
                echo "    Tipo: " . $accessorio['tipo'] . "\n";
                echo "    Materiale: " . $accessorio['materiale'] . "\n";
                echo "    Stile: " . $accessorio['stile'] . "\n";
                echo "    Colore: " . $accessorio['colore'] . "\n";
                if (isset($accessorio['nome'])) {
                    echo "    Nome: " . $accessorio['nome'] . "\n";
                }
                echo "\n";
            }
        } else {
            echo "  Nessun accessorio selezionato\n\n";
        }
        
        echo "=====================================\n\n";
    }
}
?>