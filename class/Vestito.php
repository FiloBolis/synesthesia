<?php
    class Vestito {
        private $id;
        private $id_utente;
        private $categoria;
        private $id_tipo;
        private $colore;
        private $id_stile;
        private $id_materiale;
        private $vestibilita;
        private $descrizione;
        private $img_path;
        private $nome;

        public function __construct($id, $id_utente, $categoria, $id_tipo, $colore, $id_stile, $id_materiale, $vestibilita, $descrizione, $img_path, $nome) {
            $this->id = $id;
            $this->id_utente = $id_utente;
            $this->categoria = $categoria;
            $this->id_tipo = $id_tipo;
            $this->colore = $colore;
            $this->id_stile = $id_stile;
            $this->id_materiale = $id_materiale;
            $this->vestibilita = $vestibilita;
            $this->descrizione = $descrizione;
            $this->img_path = $img_path;
            $this->nome = $nome;
        }

        public function getId() {
            return $this->id;
        }

        public function getIdUtente() {
            return $this->id_utente;
        }

        public function getCategoria() {
            return $this->categoria;
        }

        public function getIdTipo() {
            return $this->id_tipo;
        }

        public function getColore() {
            return $this->colore;
        }

        public function getIdStile() {
            return $this->id_stile;
        }

        public function getIdMateriale() {
            return $this->id_materiale;
        }

        public function getVestibilita() {
            return $this->vestibilita;
        }

        public function getDescrizione() {
            return $this->descrizione;
        }

        public function getImgPath() {
            return $this->img_path;
        }

        public function getNome() {
            return $this->nome;
        }
    }
?>