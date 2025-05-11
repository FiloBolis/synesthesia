<?php
    require_once "../class/Database.php";
    require_once "../class/Vestito.php";
    require_once "../class/Utente.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    // Verifica che l'utente sia loggato
    if (!isset($_SESSION["user"])) {
        header("location: ../index.php");
        exit;
    }
    
    $db = Database::getInstance();
    
    $vestiti = $db->getVestiti($_SESSION["user"]->getId());
    if ($vestiti == null) {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "Nessun vestito trovato!";
        echo json_encode($ret);
        die();
    }

    $vettVestiti = [];
    foreach ($vestiti as $v) {
        $stile = $db->getStileVestito($v);
        $materiale = $db->getMaterialeVestito($v);
        $tipo = $db->getTipoVestito($v);
        $vettVestiti[] = [
            "id" => $v->getId(),
            "categoria" => $v->getCategoria(),
            "colore" => $v->getColore(),
            "stile" => $stile,
            "materiale" => $materiale,
            "tipo" => $tipo,
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