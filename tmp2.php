<?php
    function suggerisciAbbigliamento($stagione, $temperatura, $umidita, $vento, $codice_meteo, $vestiti_disponibili, $stile) {
        // Analizza condizioni meteo
        $condizioni_meteo = analizzaMeteo($codice_meteo);
        
        // Calcola temperatura percepita (considerando vento e umidità)
        $temperatura_percepita = calcolaTemperaturaPercepita($temperatura, $umidita, $vento);
        
        // Determina esigenze base in base alla temperatura e condizioni
        $esigenze = determinaEsigenze($temperatura_percepita, $condizioni_meteo, $stagione);
        
        // Trova i capi più adatti dal guardaroba disponibile
        $outfit = trovaCapiAdatti($vestiti_disponibili, $esigenze, $stile);
        
        return $outfit;
    }
    
    function analizzaMeteo($codice_meteo) {
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
        }
        // Gruppo 3xx: Pioviggine
        elseif ($codice_meteo >= 300 && $codice_meteo < 400) {
            $condizioni['pioggia'] = true;
            $condizioni['umido'] = true;
        }
        // Gruppo 5xx: Pioggia
        elseif ($codice_meteo >= 500 && $codice_meteo < 600) {
            $condizioni['pioggia'] = true;
            $condizioni['umido'] = true;
        }
        // Gruppo 6xx: Neve
        elseif ($codice_meteo >= 600 && $codice_meteo < 700) {
            $condizioni['neve'] = true;
            $condizioni['freddo'] = true;
        }
        // Gruppo 7xx: Fenomeni atmosferici
        elseif ($codice_meteo >= 700 && $codice_meteo < 800) {
            // Vento e tornado
            if ($codice_meteo == 771 || $codice_meteo == 781) {
                $condizioni['vento'] = true;
            }
            // Nebbia, foschia
            else {
                $condizioni['umido'] = true;
            }
        }
        // Gruppo 800: Sereno
        elseif ($codice_meteo == 800) {
            $condizioni['sole'] = true;
        }
        // Gruppo 8xx: Nuvoloso
        elseif ($codice_meteo > 800 && $codice_meteo < 900) {
            $condizioni['nuvoloso'] = true;
        }
        
        return $condizioni;
    }


    function calcolaTemperaturaPercepita($temperatura, $umidita, $vento) {
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

    function determinaEsigenze($temperatura_percepita, $condizioni_meteo, $stagione) {
        $esigenze = [];

        if($temperatura_percepita < 0) {
            $esigenze["upper_body"]["strati"] = 3;
            $esigenze["upper_body"]["materiali"] = ["lana", "pile", "piumino", "cotone pesante"];
            $esigenze["lower_body"]["materiali"] = ["lana", "jeans"];
            $esigenze["calzature"] = "stivali";
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
            $esigenze["accessori"][] = "occhiali da sole";
        }
        if ($condizioni_meteo['pioggia']) {
            $esigenze["accessori"][] = "ombrello";
        }

        return $esigenze;
    }

    //vestiti_disponibili = $db->getVestitiFormattato($id_utente);
    function trovaCapiAdatti($vestiti_disponibili, $esigenze, $stile) {
        $upper_body[0] = ["t-shirt"];
        $upper_body[1] = ["felpa", "maglione"];
        $upper_body[2] = ["giacca"];

        $vestiti_upper_body = [];
        $numStrati = $esigenze["upper_body"]["strati"];
        $materiali_upper_body = $esigenze["upper_body"]["materiali"];
        for ($i=0; $i < sizeof($numStrati); $i++) { 
            foreach ($vestiti_disponibili as $vestito) {
                if($vestito["categoria"] == "top" && in_array($vestito["materiale"], $materiali_upper_body) && in_array($upper_body[$i], $vestito["tipo"]) && $vestito["stile"] == $stile) {
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
?>