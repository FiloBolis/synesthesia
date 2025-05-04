<?php
    class OutfitFunzioni {
        public static function analizzaMeteo($codice_meteo) {
            $condizioni = [
                'pioggia' => false,
                'neve' => false,
                'vento' => false,
                'freddo' => false,
                'caldo' => false,
                'sole' => false,
                'umido' => false
            ];
            
            // Gruppo 2xx: Temporali
            if ($codice_meteo >= 200 && $codice_meteo < 300) {
                $condizioni['pioggia'] = true;
                $condizioni['umido'] = true;
                $_SESSION["meteo"] = "Piovoso";
            }
            // Gruppo 3xx: Pioviggine
            elseif ($codice_meteo >= 300 && $codice_meteo < 400) {
                $condizioni['pioggia'] = true;
                $condizioni['umido'] = true;
                $_SESSION["meteo"] = "Piovoso";
            }
            // Gruppo 5xx: Pioggia
            elseif ($codice_meteo >= 500 && $codice_meteo < 600) {
                $condizioni['pioggia'] = true;
                $condizioni['umido'] = true;
                $_SESSION["meteo"] = "Piovoso";
            }
            // Gruppo 6xx: Neve
            elseif ($codice_meteo >= 600 && $codice_meteo < 700) {
                $condizioni['neve'] = true;
                $condizioni['freddo'] = true;
                $_SESSION["meteo"] = "Neve";
            }
            // Gruppo 7xx: Fenomeni atmosferici
            elseif ($codice_meteo >= 700 && $codice_meteo < 800) {
                // Vento e tornado
                if ($codice_meteo == 771 || $codice_meteo == 781) {
                    $condizioni['vento'] = true;
                    $_SESSION["meteo"] = "Ventoso";
                }
                // Nebbia, foschia
                else {
                    $condizioni['umido'] = true;
                    $_SESSION["meteo"] = "Nuvoloso";
                }
            }
            // Gruppo 800: Sereno
            elseif ($codice_meteo == 800) {
                $condizioni['sole'] = true;
                $_SESSION["meteo"] = "Soleggiato";
            }
            // Gruppo 8xx: Nuvoloso
            elseif ($codice_meteo > 800 && $codice_meteo < 900) {
                $condizioni['nuvoloso'] = true;
                $_SESSION["meteo"] = "Nuvoloso";
            }
            
            return $condizioni;
        }
    
    
        public static function calcolaTemperaturaPercepita($temperatura, $umidita, $vento) {
            // Formula semplificata per la temperatura percepita
            if ($temperatura > 27 && $umidita > 40) {
                // Heat index semplificato
                return $temperatura + ($umidita - 40) / 20;
            } 
            elseif ($temperatura < 10 && $vento > 5) {
                // Wind chill semplificato
                return $temperatura - ($vento - 5) / 10;
            } 
            else {
                return $temperatura;
            }
        }
    
        public static function determinaEsigenze($temperatura_percepita, $condizioni_meteo, $stagione) {
            $esigenze = [];
    
            if($temperatura_percepita < 0) {
                $esigenze["upper_body"]["strati"] = 3;
                $esigenze["upper_body"]["materiali"] = ["lana", "pile", "piumino", "cotone pesante"];
                $esigenze["lower_body"]["materiali"] = ["lana", "jeans"];
                $esigenze["calzature"] = ["stivali"];
                $esigenze["accessori"] = ["guanti", "berretto", "sciarpa"];
            }
            else if($temperatura_percepita < 10) {
                $esigenze["upper_body"]["strati"] = 3;
                $esigenze["upper_body"]["materiali"] = ["lana", "piumino", "cotone pesante"];
                $esigenze["lower_body"]["materiali"] = ["cotone", "jeans"];
                $esigenze["calzature"] = ["stivali", "sneakers"];
                $esigenze["accessori"] = ["guanti", "berretto"];
            }
            else if ($temperatura_percepita < 15) {
                $esigenze["upper_body"]["strati"] = 3;
                $esigenze["upper_body"]["materiali"] = ["cotone", "piumino"];
                $esigenze["lower_body"]["materiali"] = ["cotone", "jeans"];
                $esigenze["calzature"] = ["sneakers", "stivali"];
                $esigenze["accessori"] = ["berretto"];
            }
            else if($temperatura_percepita < 20) {
                $esigenze["upper_body"]["strati"] = 2;
                $esigenze["upper_body"]["materiali"] = ["cotone"];
                $esigenze["lower_body"]["materiali"] = ["cotone", "jeans"];
                $esigenze["calzature"] = ["sneakers", "stivali"];
                $esigenze["accessori"] = ["cappello"];
            }
            else if($temperatura_percepita < 30) {
                $esigenze["upper_body"]["strati"] = 1;
                $esigenze["upper_body"]["materiali"] = ["cotone"];
                $esigenze["lower_body"]["materiali"] = ["cotone", "jeans"];
                $esigenze["calzature"] = ["sneakers"];
                $esigenze["accessori"] = ["cappello"];
            }
            else {
                $esigenze["upper_body"]["strati"] = 1;
                $esigenze["upper_body"]["materiali"] = ["cotone"];
                $esigenze["lower_body"]["materiali"] = ["cotone", "jeans"];
                $esigenze["calzature"] = ["sneakers", "ciabatte"];
                $esigenze["accessori"] = ["cappello"];
            }
    
            if ($condizioni_meteo['sole']) {
                $esigenze["accessori"][] = ["occhiali da sole"];
            }
            if ($condizioni_meteo['pioggia']) {
                $esigenze["accessori"][] = ["ombrello"];
            }
    
            return $esigenze;
        }
    
        //vestiti_disponibili = $db->getVestitiFormattato($id_utente);
        public static function trovaCapiAdatti($vestiti_disponibili, $esigenze, $stile) {
            $upper_body[0] = ["t-shirt"];
            $upper_body[1] = ["felpa", "maglione"];
            $upper_body[2] = ["giacca"];
            
            $vestiti_upper_body = [];
            $numStrati = $esigenze["upper_body"]["strati"];
            $materiali_upper_body = $esigenze["upper_body"]["materiali"];
            for ($i=0; $i < $numStrati; $i++) { 
                foreach ($vestiti_disponibili as $vestito) {
                    if($vestito["categoria"] == "top" && in_array($vestito["materiale"], $materiali_upper_body) && in_array($vestito["tipo"], $upper_body[$i]) && $vestito["stile"] == $stile) {
                        $vestiti_upper_body[] = $vestito;
                    }
                }
            }
            $vestiti_lower_body = [];
            $materiali_lower_body = $esigenze["lower_body"]["materiali"];
            foreach ($vestiti_disponibili as $vestito) {
                if($vestito["categoria"] == "bottom" && in_array($vestito["materiale"], $materiali_lower_body) && $vestito["stile"] == $stile) {
                    $vestiti_lower_body[] = $vestito;
                }
            }
            $vestiti_calzature = [];
            foreach ($vestiti_disponibili as $vestito) {
                if($vestito["categoria"] == "calzature" && in_array($vestito["tipo"], $esigenze["calzature"]) && $vestito["stile"] == $stile) {
                    $vestiti_calzature[] = $vestito;
                }
            }
            $vestiti_accessori = [];
            foreach ($vestiti_disponibili as $vestito) {
                if($vestito["categoria"] == "accessori" && in_array($vestito["tipo"], $esigenze["accessori"]) && $vestito["stile"] == $stile) {
                    $vestiti_accessori[] = $vestito;
                }
            }
    
            $capi_adatti = [
                "upper_body" => $vestiti_upper_body,
                "lower_body" => $vestiti_lower_body,
                "calzature" => $vestiti_calzature,
                "accessori" => $vestiti_accessori
            ];
            return $capi_adatti;
        }

        public static function creaOutfit($esigenze, $capi_adatti) {
            $num_outfit = 3; // Numero di outfit da generare
            $outfits = [];
        
            // Funzione per verificare se due colori sono armonici
            $isArmonico = function($colore1, $colore2) {
                // Converte colore esadecimale in RGB
                $rgb1 = sscanf($colore1, "#%02x%02x%02x");
                $rgb2 = sscanf($colore2, "#%02x%02x%02x");
                
                // Calcola la differenza di colore (metodo semplificato)
                $diff_r = abs($rgb1[0] - $rgb2[0]);
                $diff_g = abs($rgb1[1] - $rgb2[1]);
                $diff_b = abs($rgb1[2] - $rgb2[2]);
                
                // Calcola la luminosità per ciascun colore
                $luminosita1 = (0.299 * $rgb1[0] + 0.587 * $rgb1[1] + 0.114 * $rgb1[2]) / 255;
                $luminosita2 = (0.299 * $rgb2[0] + 0.587 * $rgb2[1] + 0.114 * $rgb2[2]) / 255;
                
                // Verifica se i colori sono complementari, analoghi o monocromatici
                // Complementari: colori opposti sulla ruota dei colori
                // Analoghi: colori vicini sulla ruota dei colori
                // Monocromatici: stesso colore ma diversa luminosità
                
                // Differenza totale (maggiore è il valore, più diversi sono i colori)
                $diff_totale = $diff_r + $diff_g + $diff_b;
                
                // Differenza di luminosità (per colori monocromatici)
                $diff_luminosita = abs($luminosita1 - $luminosita2);
                
                // Regole di armonia cromatica:
                // 1. Complementari: differenza totale alta ma bilanciata (non troppo estrema)
                // 2. Analoghi: differenza totale bassa ma non troppo bassa
                // 3. Monocromatici: differenza di luminosità significativa ma colore simile
                
                // Condizioni per l'armonia
                $e_complementare = $diff_totale > 300 && $diff_totale < 600;
                $e_analogo = $diff_totale > 50 && $diff_totale < 300;
                $e_monocromatico = $diff_totale < 100 && $diff_luminosita > 0.2;
                
                return $e_complementare || $e_analogo || $e_monocromatico;
            };
        
            // Funzione per verificare se un outfit intero è armonico
            $isOutfitArmonico = function($outfit) use ($isArmonico) {
                $colori = [];
                
                // Estrae i colori da tutte le parti dell'outfit
                foreach ($outfit as $categoria => $capo) {
                    if (isset($capo['colore'])) {
                        $colori[] = $capo['colore'];
                    }
                }
                
                // Verifica che tutte le combinazioni di colori siano armoniche
                for ($i = 0; $i < count($colori); $i++) {
                    for ($j = $i + 1; $j < count($colori); $j++) {
                        if (!$isArmonico($colori[$i], $colori[$j])) {
                            return false;
                        }
                    }
                }
                
                return true;
            };
        
            // Genera outfit fino a raggiungere il numero desiderato
            for ($i = 0; $i < $num_outfit; $i++) {
                $tentativi = 0;
                $max_tentativi = 50; // Limite di tentativi per evitare loop infiniti
                
                do {
                    $outfit_corrente = [];
                    $strati_upper = $esigenze["upper_body"]["strati"];
                    
                    // Seleziona capi per il busto in base al numero di strati necessari
                    $upper_strati = [];
                    if (!empty($capi_adatti["upper_body"])) {
                        // Se abbiamo bisogno di più strati, dobbiamo selezionarli in modo appropriato
                        if ($strati_upper >= 1 && count($capi_adatti["upper_body"]) > 0) {
                            // Prendi un capo casuale per lo strato base (t-shirt, camicia)
                            $indice = array_rand($capi_adatti["upper_body"]);
                            $upper_strati[] = $capi_adatti["upper_body"][$indice];
                        }
                        
                        if ($strati_upper >= 2 && count($capi_adatti["upper_body"]) > 1) {
                            // Cerca un capo per il secondo strato (maglione, felpa)
                            do {
                                $indice = array_rand($capi_adatti["upper_body"]);
                                $capo = $capi_adatti["upper_body"][$indice];
                            } while (in_array($capo, $upper_strati) && $tentativi++ < 10);
                            
                            $upper_strati[] = $capo;
                        }
                        
                        if ($strati_upper >= 3 && count($capi_adatti["upper_body"]) > 2) {
                            // Cerca un capo per il terzo strato (giacca, cappotto)
                            do {
                                $indice = array_rand($capi_adatti["upper_body"]);
                                $capo = $capi_adatti["upper_body"][$indice];
                            } while (in_array($capo, $upper_strati) && $tentativi++ < 10);
                            
                            $upper_strati[] = $capo;
                        }
                        
                        $outfit_corrente["upper_body"] = $upper_strati;
                    }
                    
                    // Seleziona pantaloni/gonna
                    if (!empty($capi_adatti["lower_body"])) {
                        $indice = array_rand($capi_adatti["lower_body"]);
                        $outfit_corrente["lower_body"] = $capi_adatti["lower_body"][$indice];
                    }
                    
                    // Seleziona calzature
                    if (!empty($capi_adatti["calzature"])) {
                        $indice = array_rand($capi_adatti["calzature"]);
                        $outfit_corrente["calzature"] = $capi_adatti["calzature"][$indice];
                    }
                    
                    // Seleziona accessori (fino a 2)
                    if (!empty($capi_adatti["accessori"])) {
                        $num_accessori = min(2, count($capi_adatti["accessori"]));
                        $accessori_selezionati = [];
                        
                        for ($j = 0; $j < $num_accessori; $j++) {
                            do {
                                $indice = array_rand($capi_adatti["accessori"]);
                                $accessorio = $capi_adatti["accessori"][$indice];
                            } while (in_array($accessorio, $accessori_selezionati) && $tentativi++ < 10);
                            
                            $accessori_selezionati[] = $accessorio;
                        }
                        
                        $outfit_corrente["accessori"] = $accessori_selezionati;
                    }
                    
                    // Verifica se l'outfit è armonico
                    $is_armonico = $isOutfitArmonico($outfit_corrente);
                    $tentativi++;
                    
                } while (!$is_armonico && $tentativi < $max_tentativi);
                
                // Aggiungi l'outfit se armonico o comunque al termine dei tentativi
                if ($tentativi < $max_tentativi || $i == 0) {
                    $outfits[] = $outfit_corrente;
                }
            }
            
            // Calcola il livello di armonia dell'outfit (punteggio da 1 a 10)
            foreach ($outfits as &$outfit) {
                $colori = [];
                
                // Estrai tutti i colori dall'outfit
                foreach ($outfit as $categoria => $capo) {
                    if (is_array($capo) && isset($capo[0]) && isset($capo[0]['colore'])) {
                        // Per array di capi (come strati o accessori multipli)
                        foreach ($capo as $singolo_capo) {
                            if (isset($singolo_capo['colore'])) {
                                $colori[] = $singolo_capo['colore'];
                            }
                        }
                    } elseif (isset($capo['colore'])) {
                        // Per singoli capi
                        $colori[] = $capo['colore'];
                    }
                }
                
                // Calcola punteggio di armonia (algoritmo semplificato)
                $totale_punteggio = 0;
                $num_confronti = 0;
                
                for ($i = 0; $i < count($colori); $i++) {
                    for ($j = $i + 1; $j < count($colori); $j++) {
                        // Calcola punteggio tra 0 e 10 per questa coppia di colori
                        $rgb1 = sscanf($colori[$i], "#%02x%02x%02x");
                        $rgb2 = sscanf($colori[$j], "#%02x%02x%02x");
                        
                        $diff_r = abs($rgb1[0] - $rgb2[0]);
                        $diff_g = abs($rgb1[1] - $rgb2[1]);
                        $diff_b = abs($rgb1[2] - $rgb2[2]);
                        
                        $diff_totale = $diff_r + $diff_g + $diff_b;
                        
                        // Calcola punteggio su scala 0-10
                        $punteggio = 0;
                        if ($diff_totale > 300 && $diff_totale < 600) {
                            // Complementari: punteggio alto
                            $punteggio = 9 - abs(450 - $diff_totale) / 150;
                        } elseif ($diff_totale > 50 && $diff_totale < 300) {
                            // Analoghi: punteggio medio-alto
                            $punteggio = 8 - abs(175 - $diff_totale) / 125;
                        } elseif ($diff_totale < 100) {
                            // Monocromatici: punteggio medio
                            $punteggio = 7 - $diff_totale / 100;
                        }
                        
                        $totale_punteggio += $punteggio;
                        $num_confronti++;
                    }
                }
                
                if ($num_confronti > 0) {
                    $outfit["punteggio_armonia"] = round($totale_punteggio / $num_confronti, 2);
                } else {
                    $outfit["punteggio_armonia"] = 0;
                }
            }
            
            return $outfits;
        }
    }
?>