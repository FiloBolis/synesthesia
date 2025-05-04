<?php
    require_once "../class/Database.php";
    require_once "../class/Vestito.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    // Verifica che l'utente sia loggato
    if (!isset($_SESSION["user"])) {
        header("location: ../index.php");
        exit;
    }
    
    $db = Database::getInstance();
    
    $vestiti = $db->getVestiti();
    if ($vestiti == null) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Nessun vestito trovato!";
        echo json_encode($ret);
        die();
    }

    // $ret = [];
    // $ret["status"] = "OK";
    // $ret["msg"] = "Vestiti trovati!";
    // $ret["vestiti"] = $vestiti;
    // echo json_encode($ret);
    // die();

    //richiamare metodo che mi fa prendere lo stile dall'id, successivamente popolare il vettore in questo modo:
        //$vestiti = [
            // ["id" => 1, "nome" => "Camicia", "prezzo" => 29.99],
            // ["id" => 2, "nome" => "Pantaloni", "prezzo" => 49.99],
            // altri oggetti...
        // ];

    $vettVestiti = [];
    foreach ($vestiti as $v) {
        $stile = $db->getStileVestito($v);
        $vettVestiti[] = [
            "id" => $v->getId(),
            "categoria" => $v->getCategoria(),
            "colore" => $v->getColore(),
            "stile" => $stile,
            "materiale" => $v->getMateriale(),
            "vestibilita" => $v->getVestibilita(),
            "descrizione" => $v->getDescrizione(),
            "img_path" => $v->getImgPath(),
            "nome" => $v->getNome()
        ];
    }

    $ret = [];
    $ret["status"] = "OK";
    $ret["msg"] = "Vestiti trovati!";
    $ret["vestiti"] = $vettVestiti;
    echo json_encode($ret);
    die();
?>