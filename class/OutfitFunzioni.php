<?php
class OutfitFunzioni
{

    public static function analizzaMeteo($codice_meteo)
    {
        $condizioni = [
            'pioggia' => false,
            'neve'    => false,
            'vento'   => false,
            'freddo'  => false,
            'caldo'   => false,
            'sole'    => false,
            'umido'   => false,
        ];

        if ($codice_meteo >= 200 && $codice_meteo < 300) {
            $condizioni['pioggia'] = true;
            $condizioni['umido']   = true;
            $_SESSION["meteo"]     = "Piovoso";
        } elseif ($codice_meteo >= 300 && $codice_meteo < 400) {
            $condizioni['pioggia'] = true;
            $condizioni['umido']   = true;
            $_SESSION["meteo"]     = "Piovoso";
        } elseif ($codice_meteo >= 500 && $codice_meteo < 600) {
            $condizioni['pioggia'] = true;
            $condizioni['umido']   = true;
            $_SESSION["meteo"]     = "Piovoso";
        } elseif ($codice_meteo >= 600 && $codice_meteo < 700) {
            $condizioni['neve']   = true;
            $condizioni['freddo'] = true;
            $_SESSION["meteo"]    = "Neve";
        } elseif ($codice_meteo >= 700 && $codice_meteo < 800) {
            if ($codice_meteo == 771 || $codice_meteo == 781) {
                $condizioni['vento'] = true;
                $_SESSION["meteo"]   = "Ventoso";
            } else {
                $condizioni['umido'] = true;
                $_SESSION["meteo"]   = "Nuvoloso";
            }
        } elseif ($codice_meteo == 800) {
            $condizioni['sole'] = true;
            $_SESSION["meteo"]  = "Soleggiato";
        } elseif ($codice_meteo > 800 && $codice_meteo < 900) {
            $condizioni['nuvoloso'] = true;
            $_SESSION["meteo"]      = "Nuvoloso";
        }

        return $condizioni;
    }

    public static function calcolaTemperaturaPercepita($temperatura, $umidita, $vento)
    {
        if ($temperatura > 27 && $umidita > 40) {
            return $temperatura + ($umidita - 40) / 20;
        } elseif ($temperatura < 10 && $vento > 5) {
            return $temperatura - ($vento - 5) / 10;
        } else {
            return $temperatura;
        }
    }

    public static function determinaEsigenze($temperatura_percepita, $condizioni_meteo, $stagione)
    {
        $esigenze = [];

        if ($temperatura_percepita < 0) {
            $esigenze["upper_body"]["strati"]    = 3;
            $esigenze["upper_body"]["materiali"] = ["lana", "pile", "piumino", "cotone"];
            $esigenze["lower_body"]["materiali"] = ["lana", "jeans"];
            $esigenze["calzature"]               = ["stivali"];
            $esigenze["accessori"]               = ["guanti", "berretto", "sciarpa"];
        } else if ($temperatura_percepita < 10) {
            $esigenze["upper_body"]["strati"]    = 3;
            $esigenze["upper_body"]["materiali"] = ["lana", "piumino", "cotone"];
            $esigenze["lower_body"]["materiali"] = ["cotone", "jeans"];
            $esigenze["calzature"]               = ["stivali", "sneakers"];
            $esigenze["accessori"]               = ["guanti", "berretto"];
        } else if ($temperatura_percepita < 15) {
            $esigenze["upper_body"]["strati"]    = 3;
            $esigenze["upper_body"]["materiali"] = ["cotone", "piumino"];
            $esigenze["lower_body"]["materiali"] = ["cotone", "jeans"];
            $esigenze["calzature"]               = ["sneakers", "stivali"];
            $esigenze["accessori"]               = ["berretto"];
        } else if ($temperatura_percepita < 20) {
            $esigenze["upper_body"]["strati"]    = 2;
            $esigenze["upper_body"]["materiali"] = ["cotone"];
            $esigenze["lower_body"]["materiali"] = ["cotone", "jeans"];
            $esigenze["calzature"]               = ["sneakers", "stivali"];
            $esigenze["accessori"]               = ["cappello"];
        } else if ($temperatura_percepita < 30) {
            $esigenze["upper_body"]["strati"]    = 1;
            $esigenze["upper_body"]["materiali"] = ["cotone"];
            $esigenze["lower_body"]["materiali"] = ["cotone", "jeans"];
            $esigenze["calzature"]               = ["sneakers"];
            $esigenze["accessori"]               = ["cappello"];
        } else {
            $esigenze["upper_body"]["strati"]    = 1;
            $esigenze["upper_body"]["materiali"] = ["cotone"];
            $esigenze["lower_body"]["materiali"] = ["cotone", "jeans"];
            $esigenze["calzature"]               = ["sneakers", "ciabatte"];
            $esigenze["accessori"]               = ["cappello"];
        }

        if ($condizioni_meteo['sole']) {
            $esigenze["accessori"][] = "occhiali da sole";
        }
        if ($condizioni_meteo['pioggia']) {
            $esigenze["accessori"][] = "ombrello";
        }

        return $esigenze;
    }

    public static function trovaCapiAdatti($vestiti_disponibili, $esigenze)
    {
        $materiali_upper_body = $esigenze["upper_body"]["materiali"];
        $vestiti_upper        = [];
        foreach ($vestiti_disponibili as $v) {
            if ($v["categoria"] == "top") {
                foreach ($materiali_upper_body as $m) {
                    if ($v["materiale"] == $m) {
                        $vestiti_upper[] = $v;
                    }
                }
            }
        }

        $materiali_lower_body = $esigenze["lower_body"]["materiali"];
        $vestiti_lower        = [];
        foreach ($vestiti_disponibili as $v) {
            if ($v["categoria"] == "bottom") {
                foreach ($materiali_lower_body as $m) {
                    if ($v["materiale"] == $m) {
                        $vestiti_lower[] = $v;
                    }
                }
            }
        }

        $vestiti_calzature = [];
        foreach ($vestiti_disponibili as $v) {
            if ($v["categoria"] == "calzature") {
                foreach ($esigenze["calzature"] as $e) {
                    if ($v["tipo"] == $e) {
                        $vestiti_calzature[] = $v;
                    }

                }
            }
        }

        $vestiti_accessori = [];
        foreach ($vestiti_disponibili as $v) {
            if ($v["categoria"] == "accessori") {
                foreach ($esigenze["accessori"] as $e) {
                    if ($v["tipo"] == $e) {
                        $vestiti_accessori[] = $v;
                    }

                }
            }
        }

        $capi_adatti = [
            "upper_body" => $vestiti_upper,
            "lower_body" => $vestiti_lower,
            "calzature"  => $vestiti_calzature,
            "accessori"  => $vestiti_accessori,
        ];

        return $capi_adatti;
    }

    public static function creaOutfit($esigenze, $capi_adatti)
    {
        $num_outfit = 3;
        $outfits = [];
        
        // Teniamo traccia dei capi già utilizzati per non riutilizzarli
        $capiUtilizzati = [
            "upper_body" => [],
            "lower_body" => [],
            "calzature" => [],
            "accessori" => []
        ];

        for ($i = 0; $i < $num_outfit; $i++) {
            // Per ogni outfit, creiamo un nuovo array di vestiti scelti
            $vestitiScelti = [
                "upper_body" => [],
                "lower_body" => [],
                "calzature" => [],
                "accessori" => []
            ];
            
            // Flag per determinare se l'outfit è creabile
            $isOutfitCreabile = true;

            // Upper body
            if (empty($capi_adatti["upper_body"])) {
                $isOutfitCreabile = false;
            }

            if (!$isOutfitCreabile) {
                break;
            }

            // Processiamo ogni strato per l'upper body
            for ($j = 0; $j < $esigenze["upper_body"]["strati"]; $j++) {
                $stratoTrovato = false;
                
                foreach ($capi_adatti["upper_body"] as $c) {
                    if ($c["strato"] == $j + 1) {
                        // Verifichiamo se il capo è già stato utilizzato
                        $isSelected = false;
                        foreach ($capiUtilizzati["upper_body"] as $utilizzato) {
                            if ($utilizzato["id"] == $c["id"]) {
                                $isSelected = true;
                                break;
                            }
                        }
                        
                        if (!$isSelected) {
                            $vestitiScelti["upper_body"][$j + 1][] = $c;
                            $capiUtilizzati["upper_body"][] = $c;
                            $stratoTrovato = true;
                            break;
                        }
                    }
                }
                
                // Se non troviamo un capo per questo strato, l'outfit non è creabile
                if (!$stratoTrovato) {
                    $isOutfitCreabile = false;
                    break;
                }
            }

            // Lower body
            if (!$isOutfitCreabile || empty($capi_adatti["lower_body"])) {
                $isOutfitCreabile = false;
                continue; // Passa al prossimo outfit
            }

            $lowerTrovato = false;
            foreach ($capi_adatti["lower_body"] as $c) {
                $isSelected = false;
                foreach ($capiUtilizzati["lower_body"] as $utilizzato) {
                    if ($utilizzato["id"] == $c["id"]) {
                        $isSelected = true;
                        break;
                    }
                }
                
                if (!$isSelected) {
                    $vestitiScelti["lower_body"][] = $c;
                    $capiUtilizzati["lower_body"][] = $c;
                    $lowerTrovato = true;
                    break;
                }
            }
            
            if (!$lowerTrovato) {
                $isOutfitCreabile = false;
                continue;
            }

            // Calzature
            if (!$isOutfitCreabile || empty($capi_adatti["calzature"])) {
                $isOutfitCreabile = false;
                continue;
            }

            $calzatureTrovate = false;
            foreach ($capi_adatti["calzature"] as $c) {
                $isSelected = false;
                foreach ($capiUtilizzati["calzature"] as $utilizzato) {
                    if ($utilizzato["id"] == $c["id"]) {
                        $isSelected = true;
                        break;
                    }
                }
                
                if (!$isSelected) {
                    $vestitiScelti["calzature"][] = $c;
                    $capiUtilizzati["calzature"][] = $c;
                    $calzatureTrovate = true;
                    break;
                }
            }
            
            if (!$calzatureTrovate) {
                $isOutfitCreabile = false;
                continue;
            }

            // Accessori (opzionali, quindi non influenzano isOutfitCreabile)
            if (!empty($capi_adatti["accessori"])) {
                foreach ($capi_adatti["accessori"] as $c) {
                    $isSelected = false;
                    foreach ($capiUtilizzati["accessori"] as $utilizzato) {
                        if ($utilizzato["id"] == $c["id"]) {
                            $isSelected = true;
                            break;
                        }
                    }
                    
                    if (!$isSelected) {
                        $vestitiScelti["accessori"][] = $c;
                        $capiUtilizzati["accessori"][] = $c;
                        break;
                    }
                }
            }

            // Aggiungo l'outfit solo se è creabile
            if ($isOutfitCreabile) {
                $outfits[] = $vestitiScelti;
            }
        }

        if (empty($outfits)) {
            return null;
        }

        return $outfits;
    }
}
